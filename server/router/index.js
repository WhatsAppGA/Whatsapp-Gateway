"use strict";
const cache = require("./../lib/cache");
const express = require("express");
const router = express.Router();

/**
 * THIS IS MAIN ROUTER
 */
const controllers = require("../controllers");
//const store = require("../controllers/store");
const { initialize } = require("../whatsapp");
const { sendBlastMessage } = require("../controllers/blast");
//const CryptoJS = require("crypto-js");
const {
  checkDestination,
  checkConnectionBeforeBlast,
} = require("../lib/middleware");
//const validation = process.env.AUTH;

// sendFile will from here. Delete or comment if no use anymore
router.get("/", (req, res) => {
  const path = require("path");
  res.sendFile(path.join(__dirname, "../../public/index.html"));
});

router.post("/backend-logout", controllers.deleteCredentials);

router.post("/backend-generate-qr", controllers.createInstance);

router.post("/backend-initialize", initialize);

router.post(
  "/backend-send-list",
  checkDestination,
  controllers.sendListMessage
);

router.post(
  "/backend-send-template",
  checkDestination,
  controllers.sendTemplateMessage
);

router.post(
  "/backend-send-button",
  checkDestination,
  controllers.sendButtonMessage
);
router.post("/backend-send-media", checkDestination, controllers.sendMedia);

router.post("/backend-send-sticker", checkDestination, controllers.sendSticker);

router.post("/backend-send-text", checkDestination, controllers.sendText);

router.post("/backend-send-location", checkDestination, controllers.sendLocation);

router.post("/backend-send-vcard", checkDestination, controllers.sendVcard);

router.post("/backend-send-poll", checkDestination, controllers.sendPoll);

router.post("/backend-getgroups", controllers.fetchGroups);

router.post("/backend-blast", checkConnectionBeforeBlast, sendBlastMessage);
router.post("/backend-logout-device", controllers.logoutDevice);

router.post("/backend-check-number", controllers.checkNumber);

router.post("/backend-clearCache", async (req, res) => {
  await controllers.sendAvailable(req, res);
  await cache.myCache.flushAll();
  console.log("Cache cleared");
  return res.json({ status: "success" });
});

// STORE
//router.post('/backend-store-chats', store.chats)

module.exports = router;
