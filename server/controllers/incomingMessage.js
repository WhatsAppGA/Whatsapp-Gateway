const { parseIncomingMessage, formatReceipt, prepareMediaMessage } = require('../lib/helper');
const { Sticker, createSticker, StickerTypes } = require('wa-sticker-formatter');
require('dotenv').config();
const axios = require('axios');
const { isExistsEqualCommand, isExistsContainCommand, getUrlWebhook, getDevice } = require('../database/model');

function removeNameFromContent(content, name) {
  const regex = new RegExp(`\\s*${name}\\s*`, 'g');
  return content.replace(regex, '').trim();
}

const IncomingMessage = async (incomingData, socket) => {
  try {
    let isQuoted = false;
    if (!incomingData.messages) return;
    
    incomingData = incomingData.messages[0];
    const pushName = incomingData?.pushName || '';
    if (incomingData.key.fromMe === true) return;
    if (incomingData.key.remoteJid === 'status@broadcast') return;
    
    const participantNumber = incomingData.key.participant && formatReceipt(incomingData.key.participant);
    const { command, bufferImage: imageBuffer, from: senderNumber } = await parseIncomingMessage(incomingData);
    
    let replyContent, commandMatch;
    const userId = socket.user.id.split(':')[0];
    const exactMatch = await isExistsEqualCommand(command, userId);
    commandMatch = exactMatch.length > 0 ? exactMatch : await isExistsContainCommand(command, userId);

    if (commandMatch.length === 0) {
      const getDeviceAll = await getDevice(userId);
      const webhookUrl = await getUrlWebhook(userId);
      let webhookResponse = false;

      if (webhookUrl != null) {
        webhookResponse = await sendWebhook({
          command: command,
          bufferImage: imageBuffer,
          from: senderNumber,
          url: webhookUrl,
          participant: participantNumber,
        });
      }

      if (webhookResponse === false || webhookResponse === undefined || typeof webhookResponse != 'object') {
        const replyorno = getDeviceAll[0].reply_when === 'All' ||
          (getDeviceAll[0].reply_when === 'Group' && incomingData.key.remoteJid.includes('@g.us')) ||
          (getDeviceAll[0].reply_when === 'Personal' && !incomingData.key.remoteJid.includes('@g.us'));

        if (replyorno === false) return;

        if (getDeviceAll[0].typebot == 1 && (getDeviceAll[0].chatgpt_api != null || getDeviceAll[0].gemini_api != null || getDeviceAll[0].claude_api != null || getDeviceAll[0].dalle_api != null)) {
          if (getDeviceAll[0].can_read_message != 0) {
            socket.readMessages([incomingData.key]);
          }
          await socket.presenceSubscribe(incomingData.key.remoteJid);

          if (getDeviceAll[0].gemini_api) {
            if (getDeviceAll[0].bot_typing != 0) {
              await socket.presenceSubscribe(incomingData.key.remoteJid);
              await socket.sendPresenceUpdate('composing', incomingData.key.remoteJid);
            }
            geminiResponse = await sendGemini({
              command: command,
              from: senderNumber,
              geminiKey: getDeviceAll[0].gemini_api,
              participant: participantNumber,
            });
            if (geminiResponse === false || geminiResponse === undefined) return;
            isQuoted = geminiResponse?.quoted ? true : false;
            replyContent = geminiResponse;
          } else if (getDeviceAll[0].chatgpt_api) {
            if (getDeviceAll[0].bot_typing != 0) {
              await socket.presenceSubscribe(incomingData.key.remoteJid);
              await socket.sendPresenceUpdate('composing', incomingData.key.remoteJid);
            }
            chatgptResponse = await sendChatgpt({
              command: command,
              from: senderNumber,
              chatgptKey: getDeviceAll[0].chatgpt_api,
              participant: participantNumber,
            });
            if (chatgptResponse === false || chatgptResponse === undefined) return;
            isQuoted = chatgptResponse?.quoted ? true : false;
            replyContent = chatgptResponse;
          } else if (getDeviceAll[0].dalle_api) {
            if (getDeviceAll[0].bot_typing != 0) {
              await socket.presenceSubscribe(incomingData.key.remoteJid);
              await socket.sendPresenceUpdate('composing', incomingData.key.remoteJid);
            }
            dalleResponse = await sendDalle({
              command: command,
              dalleKey: getDeviceAll[0].dalle_api,
            });
            if (dalleResponse === false || dalleResponse === undefined) return;
            isQuoted = dalleResponse?.quoted ? true : false;
            replyContent = dalleResponse;
          } else if (getDeviceAll[0].claude_api) {
            if (getDeviceAll[0].bot_typing != 0) {
              await socket.presenceSubscribe(incomingData.key.remoteJid);
              await socket.sendPresenceUpdate('composing', incomingData.key.remoteJid);
            }
            claudeResponse = await sendClaude({
              command: command,
              from: senderNumber,
              claudeKey: getDeviceAll[0].claude_api,
              participant: participantNumber,
            });
            if (claudeResponse === false || claudeResponse === undefined) return;
            isQuoted = claudeResponse?.quoted ? true : false;
            replyContent = claudeResponse;
          } else {
            return;
          }
        } else if (getDeviceAll[0].typebot == 2 && (getDeviceAll[0].chatgpt_api != null || getDeviceAll[0].gemini_api != null || getDeviceAll[0].claude_api != null || getDeviceAll[0].dalle_api != null)) {
		  const AiName = (incomingData.message?.conversation?.toLowerCase()) || (incomingData.message?.extendedTextMessage?.text?.toLowerCase()) || '';
          if (getDeviceAll[0].can_read_message != 0) {
            socket.readMessages([incomingData.key]);
          }

          if (getDeviceAll[0].gemini_api && AiName.includes(getDeviceAll[0].gemini_name.toLowerCase())) {
            if (getDeviceAll[0].bot_typing != 0) {
              await socket.presenceSubscribe(incomingData.key.remoteJid);
              await socket.sendPresenceUpdate('composing', incomingData.key.remoteJid);
            }
            const commandRemove = removeNameFromContent(command, getDeviceAll[0].gemini_name.toLowerCase());
            geminiResponse = await sendGemini({
              command: commandRemove,
              from: senderNumber,
              geminiKey: getDeviceAll[0].gemini_api,
              participant: participantNumber,
            });
            if (geminiResponse === false || geminiResponse === undefined) return;
            isQuoted = geminiResponse?.quoted ? true : false;
            replyContent = geminiResponse;
          } else if (getDeviceAll[0].chatgpt_api && AiName.includes(getDeviceAll[0].chatgpt_name.toLowerCase())) {
            if (getDeviceAll[0].bot_typing != 0) {
              await socket.presenceSubscribe(incomingData.key.remoteJid);
              await socket.sendPresenceUpdate('composing', incomingData.key.remoteJid);
            }
            const commandRemove = removeNameFromContent(command, getDeviceAll[0].chatgpt_name.toLowerCase());
            chatgptResponse = await sendChatgpt({
              command: commandRemove,
              from: senderNumber,
              chatgptKey: getDeviceAll[0].chatgpt_api,
              participant: participantNumber,
            });
            if (chatgptResponse === false || chatgptResponse === undefined) return;
            isQuoted = chatgptResponse?.quoted ? true : false;
            replyContent = chatgptResponse;
          } else if (getDeviceAll[0].dalle_api && AiName.includes(getDeviceAll[0].dalle_name.toLowerCase())) {
            if (getDeviceAll[0].bot_typing != 0) {
              await socket.presenceSubscribe(incomingData.key.remoteJid);
              await socket.sendPresenceUpdate('composing', incomingData.key.remoteJid);
            }
            const commandRemove = removeNameFromContent(command, getDeviceAll[0].dalle_name.toLowerCase());
            dalleResponse = await sendDalle({
              command: commandRemove,
              dalleKey: getDeviceAll[0].dalle_api,
            });
            if (dalleResponse === false || dalleResponse === undefined) return;
            isQuoted = dalleResponse?.quoted ? true : false;
            replyContent = dalleResponse;
          } else if (getDeviceAll[0].claude_api && AiName.includes(getDeviceAll[0].claude_name.toLowerCase())) {
            if (getDeviceAll[0].bot_typing != 0) {
              await socket.presenceSubscribe(incomingData.key.remoteJid);
              await socket.sendPresenceUpdate('composing', incomingData.key.remoteJid);
            }
            const commandRemove = removeNameFromContent(command, getDeviceAll[0].claude_name.toLowerCase());
            caludeResponse = await sendClaude({
              command: commandRemove,
              from: senderNumber,
              claudeKey: getDeviceAll[0].claude_api,
              participant: participantNumber,
            });
            if (caludeResponse === false || caludeResponse === undefined) return;
            isQuoted = caludeResponse?.quoted ? true : false;
            replyContent = caludeResponse;
          } else {
            return;
          }
        }
      } else {
        if (getDeviceAll[0].webhook_read != 0) {
          socket.readMessages([incomingData.key]);
        }
        if (getDeviceAll[0].webhook_typing != 0) {
          await socket.presenceSubscribe(incomingData.key.remoteJid);
          await socket.sendPresenceUpdate('composing', incomingData.key.remoteJid);
        }
        if (getDeviceAll[0].delay != 0) {
          await new Promise(resolve => setTimeout(resolve, getDeviceAll[0].delay + '000'));
        }
        isQuoted = webhookResponse?.quoted ? true : false;
        replyContent = JSON.stringify(webhookResponse);
      }
      if (!replyContent) return;
    } else {
      const replyorno = commandMatch[0].reply_when === 'All' ||
        (commandMatch[0].reply_when === 'Group' && incomingData.key.remoteJid.includes('@g.us')) ||
        (commandMatch[0].reply_when === 'Personal' && !incomingData.key.remoteJid.includes('@g.us'));

      if (replyorno === false) return;
	  
	  if (commandMatch[0].is_read != 0) {
        socket.readMessages([incomingData.key]);
      }
	  if (commandMatch[0].is_typing != 0) {
        await socket.presenceSubscribe(incomingData.key.remoteJid);
        await socket.sendPresenceUpdate('composing', incomingData.key.remoteJid);
      }
      if (commandMatch[0].delay != 0) {
        await new Promise(resolve => setTimeout(resolve, commandMatch[0].delay + '000'));
      }

      isQuoted = commandMatch[0].is_quoted ? true : false;
      replyContent = typeof commandMatch[0].reply === 'object' ? JSON.stringify(commandMatch[0].reply) : commandMatch[0].reply;
    }

    replyContent = replyContent.replace(/{name}/g, pushName);
    replyContent = JSON.parse(replyContent);

    if ('type' in replyContent) {
      let userJid = socket.user.id.replace(/:\d+/, '');
      if (replyContent.type == 'audio') {
        return await socket.sendMessage(incomingData.key.remoteJid, {
          audio: { url: replyContent.url },
          ptt: true,
          mimetype: 'audio/mpeg',
        });
      }
	  
	  if (replyContent.type == 'sticker') {
		const sticker = new Sticker(replyContent.url, {
			pack: '', 
			author: '',
			type: StickerTypes.FULL,
			quality: 50,
		});
		const buffer = await sticker.toBuffer();
		await sticker.toFile('sticker.webp');
		
        return await socket.sendMessage(incomingData.key.remoteJid, await sticker.toMessage());
      }
	  
      const preparedMedia = await prepareMediaMessage(socket, {
		  caption: replyContent.caption ? replyContent.caption : '',
		  fileName: replyContent.filename,
		  media: replyContent.url,
		  mediatype: replyContent.type !== 'video' && replyContent.type !== 'image' ? 'document' : replyContent.type,
	  });

		const forwardMessage = JSON.parse(JSON.stringify(preparedMedia.message));

		if (forwardMessage.imageMessage) {
		  forwardMessage.imageMessage.viewOnce = replyContent.viewonce;
		} else if (forwardMessage.videoMessage) {
		  forwardMessage.videoMessage.viewOnce = replyContent.viewonce;
		}

		await socket.sendPresenceUpdate('paused', incomingData.key.remoteJid);

		return await socket.sendMessage(
		  incomingData.key.remoteJid,
		  {
			forward: {
			  key: {
				remoteJid: userJid,
				fromMe: true,
			  },
			  message: forwardMessage,
			},
		  },
		  {
			quoted: isQuoted ? incomingData : null,
		  }
		);
    } else {
      await socket
        .sendMessage(incomingData.key.remoteJid, replyContent, {
          quoted: isQuoted ? incomingData : null,
        })
        .catch((sendError) => {
          console.log(sendError);
        });
    }
    return true;
  } catch (error) {
    console.log(error);
  }
};

async function sendWebhook({ command, bufferImage, from, url, participant }) {
  try {
    const webhookData = {
      message: command,
      bufferImage: bufferImage == undefined ? null : bufferImage,
      from: from,
      participant: participant,
    };
    const headers = { 'Content-Type': 'application/json; charset=utf-8' };
    const response = await axios.post(url, webhookData, headers).catch(() => {
      return false;
    });
    return response.data;
  } catch (error) {
    console.log('error send webhook', error);
    return false;
  }
}

async function sendDalle({ command, dalleKey }) {
  try {
    const dalleUrl = process.env.DALLE_URL;
    const dalleData = {
      prompt: command,
      n: 1,
      size: process.env.DALLE_SIZE,
    };
    const headers = {
      'Content-Type': 'application/json',
      'Authorization': 'Bearer ' + dalleKey,
    };
    const response = await axios.post(dalleUrl, dalleData, { headers: headers }).catch(() => {
      return false;
    });
    if (!response) return false;
    const responseDall = {
      url: response.data.data[0].url,
      type: 'image',
      quoted: 0
    };
    return JSON.stringify(responseDall, null, 2);
  } catch (error) {
    console.log('error sendDalle', error);
    return false;
  }
}

async function sendChatgpt({ command, senderNumber, chatgptKey, participantNumber }) {
  try {
    const gptUrl = process.env.CHATGPT_URL;
    const chatgptData = {
      model: process.env.CHATGPT_MODEL,
      messages: [
        { role: 'user', content: command },
      ],
    };
    const headers = {
      'Content-Type': 'application/json',
      'Authorization': 'Bearer ' + chatgptKey,
    };
    const response = await axios.post(gptUrl, chatgptData, { headers: headers }).catch(() => {
      return false;
    });
    let content = response.data.choices[0].message.content;
    content = content.replace(/["']/g, '');
    return JSON.stringify({ text: content, quoted: false });
  } catch (error) {
    console.log('error send gptchat', error);
    return false;
  }
}

async function sendGemini({ command, senderNumber, geminiKey, participantNumber }) {
  try {
    const geminiUrl = process.env.GEMINI_URL;
    const geminiData = {
      contents: [
        {
          parts: [
            { text: command }
          ]
        }
      ]
    };
    const headers = {
      'Content-Type': 'application/json'
    };
    const response = await axios.post(`${geminiUrl}?key=${geminiKey}`, geminiData, { headers: headers }).catch(() => {
      return false;
    });
    if (response && response.data && response.data.candidates && response.data.candidates.length > 0) {
      let content = response.data.candidates[0].content.parts[0].text;
      content = content.replace(/["']/g, '');
      return JSON.stringify({ text: content, quoted: false });
    } else {
      return false;
    }
  } catch (error) {
    console.log('error send Gemini', error);
    return false;
  }
}

async function sendClaude({ command, senderNumber, claudeKey, participantNumber }) {
  try {
    const claudeUrl = process.env.CLAUDE_URL;
    const claudeData = {
      model: process.env.CLAUDE_MODEL,
      max_tokens: 1024,
      messages: [
        { role: 'user', content: command },
      ],
      system: "You are Claude, an AI assistant created by Anthropic.",
    };
    const headers = {
      'Content-Type': 'application/json',
      'x-api-key': claudeKey,
      'anthropic-version': '2023-06-01'
    };
    const response = await axios.post(claudeUrl, claudeData, { headers: headers });
    if (response.data && response.data.content && response.data.content[0] && response.data.content[0].text) {
      let content = response.data.content[0].text;
      content = content.replace(/["']/g, '');
      return JSON.stringify({ text: content, quoted: false });
    } else {
      console.log('Unexpected response structure:', response.data);
      return false;
    }
  } catch (error) {
    console.log('Error sending Claude request:');
    if (error.response) {
      console.log('Error data:', error.response.data);
      console.log('Error status:', error.response.status);
      console.log('Error headers:', error.response.headers);
    } else if (error.request) {
      console.log('Error request:', error.request);
    } else {
      console.log('Error message:', error.message);
    }
    return false;
  }
}

module.exports = { IncomingMessage };