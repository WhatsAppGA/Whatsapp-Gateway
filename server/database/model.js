/**************************/
/*    Nulled & Decoded    */
/*   By Magd Almuntaser   */
/*         TTMTT          */
/*   Only For WA v7.0.0   */
/**************************/

const { dbQuery } = require('./index')
const cache = require('./../lib/cache'),
  myCache = cache.myCache,
  isExistsEqualCommand = async (keyword, deviceBody) => {
    if (myCache.has(keyword + deviceBody)) {
      return myCache.get(keyword + deviceBody)
    }
    let deviceResult = await dbQuery(
      "SELECT * FROM devices WHERE body = '" + deviceBody + "' LIMIT 1"
    )
    if (deviceResult.length === 0) {
      return []
    }
    let deviceId = deviceResult[0].id
    let autoReplyResult = await dbQuery(
      'SELECT * FROM autoreplies WHERE keyword = "' +
        keyword +
        "\" AND type_keyword = 'Equal' AND device_id = " +
        deviceId +
        " AND status = 'Active' LIMIT 1"
    )
    if (autoReplyResult.length === 0) {
      return []
    }
    return myCache.set(keyword + deviceBody, autoReplyResult), autoReplyResult
  },
  isExistsContainCommand = async (keyword, deviceBody) => {
    if (myCache.has('contain' + keyword + deviceBody)) {
      return myCache.get('contain' + keyword + deviceBody)
    }
    let deviceResult = await dbQuery(
      "SELECT * FROM devices WHERE body = '" + deviceBody + "' LIMIT 1"
    )
    if (deviceResult.length === 0) {
      return []
    }
    let deviceId = deviceResult[0].id
    let autoReplyResult = await dbQuery(
      'SELECT * FROM autoreplies WHERE LOCATE(keyword, "' +
        keyword +
        "\") > 0 AND type_keyword = 'Contain' AND device_id = " +
        deviceId +
        " AND status = 'Active' LIMIT 1"
    )
    if (autoReplyResult.length === 0) {
      return []
    }
    return myCache.set('contain' + keyword + deviceBody, autoReplyResult), autoReplyResult
  },
  getUrlWebhook = async (deviceBody) => {
    if (myCache.has('webhook' + deviceBody)) {
      return myCache.get('webhook' + deviceBody)
    }
    let webhook = null,
      deviceResult = await dbQuery(
        "SELECT webhook FROM devices WHERE body = '" + deviceBody + "' LIMIT 1"
      )
    return (
      deviceResult.length > 0 && (webhook = deviceResult[0].webhook),
      myCache.set('webhook' + deviceBody, webhook),
      webhook
    )
  },
  getDevice = async (deviceBody) => {
    if (myCache.has('deviceall' + deviceBody)) {
      return myCache.get('deviceall' + deviceBody)
    }
    let deviceall = null,
      deviceResult = await dbQuery(
        "SELECT * FROM devices WHERE body = '" + deviceBody + "' LIMIT 1"
      )
    return (
      deviceResult.length > 0 && (deviceall = deviceResult),
      myCache.set('deviceall' + deviceBody, deviceall),
      deviceall
    )
  }
module.exports = {
  isExistsEqualCommand: isExistsEqualCommand,
  isExistsContainCommand: isExistsContainCommand,
  getUrlWebhook: getUrlWebhook,
  getDevice: getDevice,
}
