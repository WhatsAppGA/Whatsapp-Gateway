const { Boom } = require('@hapi/boom')
const {
  default: makeWASocket,
  Browsers,
  fetchLatestBaileysVersion,
  useMultiFileAuthState,
  makeCacheableSignalKeyStore,
  DisconnectReason,
} = require('@whiskeysockets/baileys');
const { Sticker, createSticker, StickerTypes } = require('wa-sticker-formatter');
const path = require('path');
const QRCode = require('qrcode'),
  fs = require('fs'),
  {
    isExistsEqualCommand,
    isExistsContainCommand,
    getUrlWebhook,
	getDevice,
  } = require('./database/model')
let sock = [],
  qrcode = [],
  pairingCode = [],
  intervalStore = []
const { setStatus } = require('./database/index'),
  { IncomingMessage } = require('./controllers/incomingMessage'),
  {
    formatReceipt,
    getSavedPhoneNumber,
    prepareMediaMessage,
  } = require('./lib/helper'),
  MAIN_LOGGER = require('./lib/pino'),
  NodeCache = require('node-cache'),
  logger = MAIN_LOGGER.child({}),
  msgRetryCounterCache = new NodeCache();
  const connectToWhatsApp = async (token, socket = null, usePairingCode = false) => {
  let reconnectInterval;
  
  const checkConnectionStatus = async (connection) => {
    if (!connection) return false;
    try {
      await connection.fetchStatus(connection.user.id);
      return true;
    } catch (error) {
      console.log('Connection check failed:', error);
      return false;
    }
  };

  const attemptConnection = async () => {
    try {
      if (sock[token] && await checkConnectionStatus(sock[token])) {
        console.log('Connection is active');
        const userId = `${sock[token].user.id.split(':')[0]}@s.whatsapp.net`;
        const ppUrl = await getPpUrl(token, userId);
        socket?.emit('connection-open', {
          token,
          user: sock[token].user,
          ppUrl,
        });
        delete qrcode[token];
        delete pairingCode[token];
        clearInterval(reconnectInterval);
        reconnectInterval = null;
        return { status: true, message: 'Already connected' };
      } else {
        console.log('Connection is not active, attempting to reconnect');
      }
    } catch (error) {
      console.error('Error checking existing connection:', error);
      socket?.emit('message', { token, message: 'Connecting.. (1)..' });
    }

    const { state, saveCreds } = await useMultiFileAuthState(`credentials/${token}`);
    const { version, isLatest } = await fetchLatestBaileysVersion();

    console.log(`Using WA v${version.join('.')}. isLatest: ${isLatest}`);

    const getDeviceAll = await getDevice(token);
    const markOnline = getDeviceAll[0].set_available !== 0;

    const connectionOptions = {
      waWebSocketUrl: 'wss://web.whatsapp.com:5222/ws/chat',
      version,
      logger,
      fireInitQueries: false,
      printQRInTerminal: false,
      auth: {
        creds: state.creds,
        keys: makeCacheableSignalKeyStore(state.keys, logger),
      },
      browser: ["Safari", "Chrome", "Firefox"],
      markOnlineOnConnect: markOnline,
      generateHighQualityLinkPreview: true,
      connectTimeoutMs: 30_000,
      defaultQueryTimeoutMs: undefined,
      keepAliveIntervalMs: 20_000,
      emitOwnEvents: false,
      retryRequestDelayMs: 250,
    };

    sock[token] = makeWASocket(connectionOptions);

    sock[token].ev.on('connection.update', async (update) => {
      const { connection, lastDisconnect, qr } = update;

      if (qr) {
        try {
          const url = await QRCode.toDataURL(qr);
          qrcode[token] = url;
          socket?.emit('qrcode', { token, data: url, message: 'Scan this QR code with your WhatsApp' });
        } catch (err) {
          console.error('QR Code generation failed:', err);
        }
      }

      if (connection === 'close') {
        const shouldReconnect = (lastDisconnect?.error instanceof Boom) && lastDisconnect.error.output.statusCode !== DisconnectReason.loggedOut;
        console.log('Connection closed due to', lastDisconnect?.error, 'Reconnecting:', shouldReconnect);

        if (shouldReconnect) {
          if (!reconnectInterval) {
            reconnectInterval = setInterval(() => attemptConnection(), 10000);
          }
        } else {
          setStatus(token, 'Disconnect');
          console.log('Connection closed. You are logged out.');
          socket?.emit('message', { token, message: 'Connection closed. You are logged out.' });
          await clearConnection(token);
          clearInterval(reconnectInterval);
          reconnectInterval = null;
        }
      } else if (connection === 'open') {
        setStatus(token, 'Connected');
        console.log('Connected successfully!');
        const userId = `${sock[token].user.id.split(':')[0]}@s.whatsapp.net`;
        const ppUrl = await getPpUrl(token, userId);
        socket?.emit('connection-open', { token, user: sock[token].user, ppUrl });
        delete qrcode[token];
        delete pairingCode[token];
        clearInterval(reconnectInterval);
        reconnectInterval = null;
      }
    });

    sock[token].ev.on('creds.update', saveCreds);

    if (usePairingCode && !state.creds.registered) {
      const phoneNumber = await getSavedPhoneNumber(token);
      if (phoneNumber) {
        try {
          const code = await sock[token].requestPairingCode(phoneNumber);
          pairingCode[token] = code;
          socket?.emit('code', { token, data: code, message: 'Use this code to pair your device' });
        } catch (error) {
          console.error('Failed to request pairing code:', error);
          socket?.emit('message', { token, message: 'Failed to generate pairing code. Please try again.' });
        }
      } else {
        console.error('No saved phone number found for pairing code generation');
        socket?.emit('message', { token, message: 'No phone number available for pairing' });
      }
    }

    sock[token].ev.on('messages.upsert', async (messagesUpsert) => {
      IncomingMessage(messagesUpsert, sock[token]);
    });
  };

  await attemptConnection();
  
  sock[token].ws.on('CB:call', async call => {
    const getDeviceWa = await getDevice(sock[token].user.id.split(':')[0]);
    const TextCall = getDeviceWa[0].reject_message;
    if (TextCall !== null) {
      if (call.content[0].tag == 'offer') {
        const callerJid = call.content[0].attrs['call-creator'];
        const { platform, notify, t } = call.attrs;
        const caption = TextCall;
        await sock[token].sendMessage(callerJid, { text: caption });
      }
    }
  });
	  
  sock[token].ev.on('call', async (node) => {
    const getDeviceWa = await getDevice(sock[token].user.id.split(':')[0]);
    const reject_call = getDeviceWa[0].reject_call;
    const webhook_reject_call = getDeviceWa[0].webhook_reject_call;
    if (reject_call === 1 || webhook_reject_call === 1) {
      const { from, id, status } = node[0];
      if (status == 'offer') {
        const sendresult = {
          tag: 'call',
          attrs: {
            from: sock[token].user.id,
            to: from,
            id: sock[token].generateMessageTag(),
          },
          content: [
            {
              tag: 'reject',
              attrs: {
                'call-id': id,
                'call-creator': from,
                count: '0',
              },
              content: undefined,
            },
          ],
        };
        await sock[token].query(sendresult);
      }
    }
  });

  return { sock: sock[token], qrcode: qrcode[token] };
};



async function connectWaBeforeSend(waToken) {
  let isConnected = undefined,
    connectionResult
  connectionResult = await connectToWhatsApp(waToken)
  await connectionResult.sock.ev.on('connection.update', (update) => {
    const { connection: connectionStatus, qr: qrCode } = update
    connectionStatus === 'open' && (isConnected = true)
    qrCode && (isConnected = false)
  })
  let retryCount = 0
  while (typeof isConnected === 'undefined') {
    retryCount++
    if (retryCount > 4) {
      break
    }
    await new Promise((resolve) => setTimeout(resolve, 1000))
  }
  return isConnected
}
const sendAvailable = async (body) => {
	const getDeviceAll = await getDevice(body);
    try {
	  if (getDeviceAll[0].set_available != 1) {
	     const sendAvailableResult = await sock[body].sendPresenceUpdate('available');
	  } else {
		 const sendAvailableResult = await sock[body].sendPresenceUpdate('unavailable');
	  }
      return sendAvailableResult
    } catch (error) {
      return false
    }
  },
  sendText = async (waToken, recipient, message) => {
    try {
      const sendMessageResult = await sock[waToken].sendMessage(
        formatReceipt(recipient),
        { text: message }
      )
      return sendMessageResult
    } catch (error) {
      return false
    }
  },
  sendMessage = async (waToken, recipient, message) => {
    try {
      const sendMessageResult = await sock[waToken].sendMessage(
        formatReceipt(recipient),
        JSON.parse(message)
      )
      return sendMessageResult
    } catch (error) {
      return false
    }
  }
async function sendLocation(
  waToken,
  recipient,
  latitude,
  longitude
) {
    try {
      const sendLocationResult = await sock[waToken].sendMessage(
        formatReceipt(recipient),
        {
			location: { degreesLatitude: latitude, degreesLongitude: longitude }
		}
      )
      return sendLocationResult
    } catch (error) {
      return false
    }
}
async function sendVcard(
  waToken,
  recipient,
  name,
  phone
) {
    try {
      const vcard = 'BEGIN:VCARD\n' // metadata of the contact card
            + 'VERSION:3.0\n' 
            + 'FN:'+name+'\n' // full name
            + 'TEL;type=CELL;type=VOICE;waid='+phone+':+'+phone+'\n' // WhatsApp ID + phone number
            + 'END:VCARD';
      const sendLocationResult = await sock[waToken].sendMessage(
        formatReceipt(recipient),
        {
			contacts: { 
               displayName: name, 
               contacts: [{ vcard }] 
            }
		}
      )
      return sendLocationResult
    } catch (error) {
      return false
    }
}
async function sendSticker(
  waToken,
  recipient,
  mediaType,
  mediaPath,
  message,
  fileName
) {
  const formattedRecipient = formatReceipt(recipient)
  let userId = sock[waToken].user.id.replace(/:\d+/, '')
  const sticker = new Sticker(mediaPath, {
      pack: '', 
      author: '',
      type: StickerTypes.FULL,
      quality: 50,
  });
  const buffer = await sticker.toBuffer();
  await sticker.toFile('sticker.webp');
  return await sock[waToken].sendMessage(formattedRecipient, await sticker.toMessage())
}
async function sendMedia(
  waToken,
  recipient,
  mediaType,
  mediaPath,
  caption,
  message,
  viewonce,
  fileName
) {
  const formattedRecipient = formatReceipt(recipient);
  let userId = sock[waToken].user.id.replace(/:\d+/, '');

  if (mediaType === 'audio') {
    return await sock[waToken].sendMessage(formattedRecipient, {
      audio: { url: mediaPath },
      ptt: true,
      mimetype: 'audio/mpeg',
    });
  }

  if (mediaType === 'image' || mediaType === 'video') {
    return await sock[waToken].sendMessage(formattedRecipient, {
      [mediaType]: { url: mediaPath },
      caption: caption ? caption : '',
      viewOnce: viewonce,
    });
  }

  const mediaMessage = await prepareMediaMessage(sock[waToken], {
    caption: caption ? caption : '',
    fileName: fileName,
    media: mediaPath,
    mediatype:
      mediaType !== 'video' && mediaType !== 'image' ? 'document' : mediaType,
  });

  const forwardMessage = { ...mediaMessage.message };

  return await sock[waToken].sendMessage(formattedRecipient, {
    forward: {
      key: {
        remoteJid: userId,
        fromMe: true,
      },
      message: forwardMessage,
    },
  });
}

async function sendButtonMessage(
  waToken,
  recipient,
  buttons,
  message,
  footer,
  imageUrl
) {
  let url = 'url'
  try {
    const buttonArray = buttons.map((button, index) => {
      return {
        buttonId: index,
        buttonText: { displayText: button.displayText },
        type: 1,
      }
    })
    if (imageUrl) {
      var buttonMessage = {
        image:
          url == 'url'
            ? { url: imageUrl }
            : fs.readFileSync('src/public/temp/' + imageUrl),
        caption: message,
        footer: footer,
        buttons: buttonArray,
        headerType: 4,
        viewOnce: true,
      }
    } else {
      var buttonMessage = {
        text: message,
        footer: footer,
        buttons: buttonArray,
        headerType: 1,
        viewOnce: true,
      }
    }
    const sendButtonMessageResult = await sock[waToken].sendMessage(
      formatReceipt(recipient),
      buttonMessage
    )
    return sendButtonMessageResult
  } catch (error) {
    return console.log(error), false
  }
}
async function sendTemplateMessage(
  waToken,
  recipient,
  templateButtons,
  message,
  footer,
  imageUrl
) {
  try {
    if (imageUrl) {
      var templateMessage = {
        caption: message,
        footer: footer,
        viewOnce: true,
        templateButtons: templateButtons,
        image: { url: imageUrl },
        viewOnce: true,
      }
    } else {
      var templateMessage = {
        text: message,
        footer: footer,
        viewOnce: true,
        templateButtons: templateButtons,
      }
    }
    const sendTemplateMessageResult = await sock[waToken].sendMessage(
      formatReceipt(recipient),
      templateMessage
    )
    return sendTemplateMessageResult
  } catch (error) {
    return console.log(error), false
  }
}
async function sendListMessage(
  waToken,
  recipient,
  sections,
  message,
  footer,
  title,
  buttonText
) {
  try {
    const listMessage = {
        text: message,
        footer: footer,
        title: title,
        buttonText: buttonText,
        sections: [sections],
      },
      sendListMessageResult = await sock[waToken].sendMessage(
        formatReceipt(recipient),
        listMessage,
        { ephemeralExpiration: 604800 }
      )
    return sendListMessageResult
  } catch (error) {
    return console.log(error), false
  }
}
async function sendPollMessage(
  waToken,
  recipient,
  pollName,
  pollValues,
  selectableCount
) {
  try {
    const sendPollMessageResult = await sock[waToken].sendMessage(
      formatReceipt(recipient),
      {
        poll: {
          name: pollName,
          values: pollValues,
          selectableCount: selectableCount,
        },
      }
    )
    return sendPollMessageResult
  } catch (error) {
    return console.log(error), false
  }
}
async function fetchGroups(waToken) {
  try {
	if (typeof sock[waToken] === 'undefined') {
      const connectionResult = await connectWaBeforeSend(waToken)
      if (!connectionResult) {
        return false
      }
    }
    let allGroups = await sock[waToken].groupFetchAllParticipating(),
      groupList = Object.entries(allGroups)
        .slice(0)
        .map((groupEntry) => groupEntry[1])
    return groupList
  } catch (error) {
    return false
  }
}
async function isExist(waToken, phoneNumber) {
  try {
    if (typeof sock[waToken] === 'undefined') {
      const connectionResult = await connectWaBeforeSend(waToken)
      if (!connectionResult) {
        return false
      }
    }
    if (phoneNumber.includes('@g.us')) {
      return true
    } else {
      const [isOnWhatsApp] = await sock[waToken].onWhatsApp('+' + phoneNumber)
      return phoneNumber.length > 11 ? isOnWhatsApp : true
    }
  } catch (error) {
    return false
  }
}
async function getPpUrl(waToken, userId, error) {
  let profilePictureUrl
  try {
    return (
      (profilePictureUrl = await sock[waToken].profilePictureUrl(userId)),
      profilePictureUrl
    )
  } catch (error) {
    return 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/6b/WhatsApp.svg/1200px-WhatsApp.svg.png'
  }
}
async function deleteCredentials(waToken, socket = null) {
  socket !== null &&
    socket.emit('message', {
      token: waToken,
      message: 'Logout Progres..',
    })
  try {
    if (typeof sock[waToken] === 'undefined') {
      const connectionResult = await connectWaBeforeSend(waToken)
      connectionResult && (sock[waToken].logout(), delete sock[waToken])
    } else {
      sock[waToken].logout()
      delete sock[waToken]
    }
    return (
      delete qrcode[waToken],
      clearInterval(intervalStore[waToken]),
      setStatus(waToken, 'Disconnect'),
      socket != null &&
        (socket.emit('Unauthorized', waToken),
        socket.emit('message', {
          token: waToken,
          message: 'Connection closed. You are logged out.',
        })),
      fs.existsSync('./credentials/' + waToken) &&
        fs.rmSync(
          './credentials/' + waToken,
          {
            recursive: true,
            force: true,
          },
          (error) => {
            if (error) {
              console.log(error)
            }
          }
        ),
      {
        status: true,
        message: 'Deleting session and credential',
      }
    )
  } catch (error) {
    return (
      console.log(error),
      {
        status: true,
        message: 'Nothing deleted',
      }
    )
  }
}
function clearConnection(waToken) {
  clearInterval(intervalStore[waToken]);
  delete sock[waToken];
  delete qrcode[waToken];
  delete pairingCode[waToken];
  setStatus(waToken, 'Disconnect');
  fs.existsSync('./credentials/' + waToken) &&
    (fs.rmSync(
      './credentials/' + waToken,
      {
        recursive: true,
        force: true,
      },
      (error) => {
        if (error) {
          console.log(error)
        }
      }
    ),
    console.log('credentials/' + waToken + ' is deleted'))
}
async function initialize(req, res) {
  const { token: token } = req.body
  if (token) {
    const fs = require('fs'),
      credentialsPath = './credentials/' + token
    if (fs.existsSync(credentialsPath)) {
      sock[token] = undefined
      const connectionResult = await connectWaBeforeSend(token)
      return connectionResult
        ? res.status(200).json({
            status: true,
            message: token + ' connection restored',
          })
        : res.status(200).json({
            status: false,
            message: token + ' connection failed',
          })
    }
    return res.send({
      status: false,
      message: token + ' Connection failed,please scan first',
    })
  }
  return res.send({
    status: false,
    message: 'Wrong Parameterss',
  })
}
module.exports = {
  connectToWhatsApp,
  sendAvailable,
  sendText,
  sendLocation,
  sendVcard,
  sendSticker,
  sendMedia,
  sendButtonMessage,
  sendTemplateMessage,
  sendListMessage,
  sendPollMessage,
  isExist,
  getPpUrl,
  fetchGroups,
  deleteCredentials,
  sendMessage,
  initialize,
  connectWaBeforeSend,
  sock,
}
