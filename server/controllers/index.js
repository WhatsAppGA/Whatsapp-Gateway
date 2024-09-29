/**************************/
/*    Nulled & Decoded    */
/*   By Magd Almuntaser   */
/*         TTMTT          */
/*   Only For WA v7.0.0   */
/**************************/

'use strict'
const { formatReceipt } = require('../lib/helper'),
  wa = require('../whatsapp'),
  createInstance = async (requestData, responseHandler) => {
    const { token: token } = requestData.body
    if (token) {
      try {
        const waConnection = await wa.connectToWhatsApp(token, requestData.io),
          status = waConnection?.status,
          message = waConnection?.message
        return responseHandler.send({
          status: status ?? 'processing',
          qrcode: waConnection?.qrcode,
          message: message ? message : 'Processing',
        })
      } catch (error) {
        return (
          console.log(error),
          responseHandler.send({
            status: false,
            error: error,
          })
        )
      }
    }
    responseHandler.status(403).end('Token needed')
  },
  sendAvailable = async (requestData, responseHandler) => {
    const {
      body: body,
    } = requestData.body
    const sendAvailableResult = await wa.sendAvailable(body)
	return
  },
  sendText = async (requestData, responseHandler) => {
    const {
      token: token,
      number: number,
      text: text,
    } = requestData.body
    if (token && number && text) {
      const sendMessageResult = await wa.sendText(token, number, text)
      return handleResponSendMessage(sendMessageResult, responseHandler)
    }
    responseHandler.send({
      status: false,
      message: 'Check your parameter',
    })
  },
  sendLocation = async (requestData, responseHandler) => {
    const {
      token: token,
      number: number,
      latitude: latitude,
	  longitude: longitude,
    } = requestData.body
    if (token && number && latitude && longitude) {
      const sendLocationResult = await wa.sendLocation(token, number, latitude, longitude)
      return handleResponSendMessage(sendLocationResult, responseHandler)
    }
    responseHandler.send({
      status: false,
      message: 'Check your parameter',
    })
  },
  sendVcard = async (requestData, responseHandler) => {
    const {
      token: token,
      number: number,
      name: name,
	  phone: phone,
    } = requestData.body
    if (token && number && name && phone) {
      const sendVcardResult = await wa.sendVcard(token, number, name, phone)
      return handleResponSendMessage(sendVcardResult, responseHandler)
    }
    responseHandler.send({
      status: false,
      message: 'Check your parameter',
    })
  },
  sendMedia = async (requestData, responseHandler) => {
    const {
      token: token,
      number: number,
      type: type,
      url: url,
      caption: caption,
      ptt: ptt,
	  viewonce: viewonce,
      filename: filename,
    } = requestData.body
    if (token && number && type && url) {
      const sendMediaResult = await wa.sendMedia(
        token,
        number,
        type,
        url,
        caption ?? '',
        ptt,
		viewonce ?? false,
        filename
      )
      return handleResponSendMessage(sendMediaResult, responseHandler)
    }
    responseHandler.send({
      status: false,
      message: 'Check your parameter',
    })
  },
  sendSticker = async (requestData, responseHandler) => {
    const {
      token: token,
      number: number,
      type: type,
      url: url,
      filename: filename,
    } = requestData.body
    if (token && number && type && url) {
      const sendStickerResult = await wa.sendSticker(
        token,
        number,
        type,
        url,
        filename
      )
      return handleResponSendMessage(sendStickerResult, responseHandler)
    }
    responseHandler.send({
      status: false,
      message: 'Check your parameter',
    })
  },
  sendButtonMessage = async (requestData, responseHandler) => {
    const {
      token: token,
      number: number,
      button: button,
      message: message,
      footer: footer,
      image: image,
    } = requestData.body
    const parsedButton = JSON.parse(button)
    if (token && number && button && message) {
      const sendButtonResult = await wa.sendButtonMessage(
        token,
        number,
        parsedButton,
        message,
        footer,
        image
      )
      return handleResponSendMessage(sendButtonResult, responseHandler)
    }
    responseHandler.send({
      status: false,
      message: 'Check your parameterr',
    })
  },
  sendTemplateMessage = async (requestData, responseHandler) => {
    const {
      token: token,
      number: number,
      button: button,
      text: text,
      footer: footer,
      image: image,
    } = requestData.body
    if (token && number && button && text && footer) {
      const sendTemplateResult = await wa.sendTemplateMessage(
        token,
        number,
        JSON.parse(button),
        text,
        footer,
        image
      )
      return handleResponSendMessage(sendTemplateResult, responseHandler)
    }
    responseHandler.send({
      status: false,
      message: 'Check your parameter',
    })
  },
  sendListMessage = async (requestData, responseHandler) => {
    const {
      token: token,
      number: number,
      list: list,
      text: text,
      footer: footer,
      title: title,
      buttonText: buttonText,
    } = requestData.body
    if (
      token &&
      number &&
      list &&
      text &&
      title &&
      buttonText
    ) {
      const sendListResult = await wa.sendListMessage(
        token,
        number,
        JSON.parse(list),
        text,
        footer ?? '',
        title,
        buttonText
      )
      return handleResponSendMessage(sendListResult, responseHandler)
    }
    responseHandler.send({
      status: false,
      message: 'Check your parameterr',
    })
  },
  sendPoll = async (requestData, responseHandler) => {
    const {
      token: token,
      number: number,
      name: name,
      options: options,
      countable: countable,
    } = requestData.body
    if (token && number && name && options && countable) {
      const sendPollResult = await wa.sendPollMessage(
        token,
        number,
        name,
        JSON.parse(options),
        countable
      )
      return handleResponSendMessage(sendPollResult, responseHandler)
    }
    responseHandler.send({
      status: false,
      message: 'Check your parameterrss',
    })
  }
const fetchGroups = async (requestData, responseHandler) => {
    const { token: token } = requestData.body
    if (token) {
      const fetchGroupsResult = await wa.fetchGroups(token)
      return handleResponSendMessage(fetchGroupsResult, responseHandler)
    }
    responseHandler.send({
      status: false,
      message: 'Check your parameter',
    })
  },
  deleteCredentials = async (requestData, responseHandler) => {
    const { token: token } = requestData.body
    if (token) {
      const deleteCredentialsResult = await wa.deleteCredentials(token)
      return handleResponSendMessage(deleteCredentialsResult, responseHandler)
    }
    responseHandler.send({
      status: false,
      message: 'Check your parameter',
    })
  },
  handleResponSendMessage = (sendMessageResult, responseHandler, extraParam = null) => {
    if (sendMessageResult) {
      return responseHandler.send({
        status: true,
        data: sendMessageResult,
      })
    }
    return responseHandler.send({
      status: false,
      message: 'Check your whatsapp connection',
    })
  },
  checkNumber = async (requestData, responseHandler) => {
    const { token: token, number: number } = requestData.body
    if (token && number) {
      const isExistResult = await wa.isExist(token, number)
      return (
        console.log(isExistResult),
        responseHandler.send({
          status: true,
          active: isExistResult,
        })
      )
    }
    responseHandler.send({
      status: false,
      message: 'Check your parameter',
    })
  },
  logoutDevice = async (requestData, responseHandler) => {
    const { token: token } = requestData.body
    if (token) {
      const deleteCredentialsResult = await wa.deleteCredentials(token)
      return responseHandler.send(deleteCredentialsResult)
    }
    return responseHandler.send({
      status: false,
      message: 'Check your parameter',
    })
  }
module.exports = {
  createInstance: createInstance,
  sendAvailable: sendAvailable,
  sendText: sendText,
  sendLocation: sendLocation,
  sendVcard: sendVcard,
  sendMedia: sendMedia,
  sendSticker: sendSticker,
  sendButtonMessage: sendButtonMessage,
  sendTemplateMessage: sendTemplateMessage,
  sendListMessage: sendListMessage,
  deleteCredentials: deleteCredentials,
  fetchGroups: fetchGroups,
  sendPoll: sendPoll,
  logoutDevice: logoutDevice,
  checkNumber: checkNumber,
}
