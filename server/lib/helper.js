const {
  default: makeWASocket,
  downloadContentFromMessage,
  prepareWAMessageMedia,
  generateWAMessageFromContent,
} = require('@whiskeysockets/baileys');
const mime = require('mime-types');
const fs = require('fs');
const { join } = require('path');
const { default: axios } = require('axios');

function formatReceipt(phoneNumber) {
  try {
    if (phoneNumber.endsWith('@g.us')) {
      return phoneNumber;
    }
    let formattedNumber = phoneNumber.replace(/\D/g, '');
    if (formattedNumber.startsWith('0')) {
      formattedNumber = '62' + formattedNumber.substr(1);
    }
    if (!formattedNumber.endsWith('@c.us')) {
      formattedNumber += '@c.us';
    }
    return formattedNumber;
  } catch (error) {
    return phoneNumber;
  }
}

async function asyncForEach(array, callback) {
  for (let index = 0; index < array.length; index++) {
    await callback(array[index], index, array);
  }
}

async function removeForbiddenCharacters(inputString) {
  return inputString.replace(/[\x00-\x1F\x7F-\x9F'\\"]/g, '');
}

async function parseIncomingMessage(incomingMessage) {
  const messageType = Object.keys(incomingMessage.message || {})[0];
  let messageContent = '';

  if (messageType === 'conversation' && incomingMessage.message.conversation) {
    messageContent = incomingMessage.message.conversation;
  } else if (messageType == 'imageMessage' && incomingMessage.message.imageMessage.caption) {
    messageContent = incomingMessage.message.imageMessage.caption;
  } else if (messageType == 'videoMessage' && incomingMessage.message.videoMessage.caption) {
    messageContent = incomingMessage.message.videoMessage.caption;
  } else if (messageType == 'extendedTextMessage' && incomingMessage.message.extendedTextMessage.text) {
    messageContent = incomingMessage.message.extendedTextMessage.text;
  } else if (messageType == 'messageContextInfo' && incomingMessage.message.listResponseMessage?.title) {
    messageContent = incomingMessage.message.listResponseMessage.title;
  } else if (messageType == 'messageContextInfo') {
    messageContent = incomingMessage.message.buttonsResponseMessage.selectedDisplayText;
  }

  const lowerCaseContent = messageContent.toLowerCase();
  const sanitizedContent = await removeForbiddenCharacters(lowerCaseContent);
  const pushName = incomingMessage?.pushName || '';
  const senderNumber = incomingMessage.key.remoteJid.split('@')[0];

  let imageBuffer = null;
  if (messageType === 'imageMessage') {
    const imageStream = await downloadContentFromMessage(
      incomingMessage.message.imageMessage,
      'image'
    );
    let buffer = Buffer.from([]);
    for await (const chunk of imageStream) {
      buffer = Buffer.concat([buffer, chunk]);
    }
    imageBuffer = buffer.toString('base64');
  }

  return {
    command: sanitizedContent,
    bufferImage: imageBuffer,
    from: senderNumber,
  };
}

function getSavedPhoneNumber(token) {
  return new Promise((resolve, reject) => {
    const savedPhoneNumber = token;
    if (savedPhoneNumber) {
      setTimeout(() => {
        resolve(savedPhoneNumber);
      }, 2000);
    } else {
      reject(new Error('Nomor telepon tidak ditemukan.'));
    }
  });
}

const prepareMediaMessage = async (socket, mediaOptions) => {
  try {
    const preparedMedia = await prepareWAMessageMedia(
      { [mediaOptions.mediatype]: { url: mediaOptions.media } },
      { upload: socket.waUploadToServer }
    );
    const messageKey = mediaOptions.mediatype + 'Message';

    if (mediaOptions.mediatype === 'document' && !mediaOptions.fileName) {
      const fileNameRegex = /.*\/(.+?)\./;
      const fileNameMatch = fileNameRegex.exec(mediaOptions.media);
      mediaOptions.fileName = fileNameMatch[1];
    }

    let mimetype = mime.lookup(mediaOptions.media);
    if (!mimetype) {
      const response = await axios.head(mediaOptions.media);
      mimetype = response.headers['content-type'];
    }
    if (mediaOptions.media.includes('.cdr')) {
      mimetype = 'application/cdr';
    }

    preparedMedia[messageKey].caption = mediaOptions?.caption;
    preparedMedia[messageKey].mimetype = mimetype;
    preparedMedia[messageKey].fileName = mediaOptions.fileName;

    if (mediaOptions.mediatype === 'video') {
      preparedMedia[messageKey].jpegThumbnail = Uint8Array.from(
        fs.readFileSync(join(process.cwd(), 'public', 'images', 'video-cover.png'))
      );
      preparedMedia[messageKey].gifPlayback = false;
    }

    let userJid = socket.user.id.replace(/:\d+/, '');
    return await generateWAMessageFromContent(
      '',
      { [messageKey]: { ...preparedMedia[messageKey] } },
      { userJid: userJid }
    );
  } catch (prepareError) {
    console.log('error prepare', prepareError);
    return false;
  }
};

module.exports = {
  formatReceipt,
  asyncForEach,
  removeForbiddenCharacters,
  parseIncomingMessage,
  getSavedPhoneNumber,
  prepareMediaMessage,
};