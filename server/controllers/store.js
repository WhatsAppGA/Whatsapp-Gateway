/**************************/
/*    Nulled & Decoded    */
/*   By Magd Almuntaser   */
/*         TTMTT          */
/*   Only For WA v7.0.0   */
/**************************/

'use strict'
const fs = require('fs'),
  chats = (requestData, responseHandler) => {
    const { token: token, type: type, jid: jid } = requestData.body
    if (token && type) {
      try {
        const fileContent = fs.readFileSync(
          'credentials/' + token + '/multistore.js',
          { encoding: 'utf8' }
        )
        let parsedData = JSON.parse(fileContent)
        if (type === 'chats') {
          parsedData = parsedData.chats
        } else {
          if (type === 'contacts') {
            parsedData = parsedData.contacts
          } else {
            if (type === 'messages') {
              jid
                ? (parsedData = parsedData.messages[jid])
                : (parsedData = parsedData.messages)
            } else {
              return responseHandler.send({
                status: false,
                message: 'Unknown type',
              })
            }
          }
        }
        if (typeof parsedData === 'undefined') {
          return responseHandler.send({
            status: false,
            message: 'Data Not Found',
          })
        }
        return responseHandler.send(
          parsedData.length > 0 ? parsedData.reverse() : parsedData
        )
      } catch (error) {
        return (
          process.env.NODE_ENV !== 'production' ? console.log(error) : null,
          responseHandler.send({
            status: false,
            error: error,
          })
        )
      }
    }
    responseHandler.send({
      status: false,
      error: 'wrong parameters',
    })
  }
module.exports = { chats: chats }
