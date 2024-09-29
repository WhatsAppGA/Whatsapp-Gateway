"use strict";

const wa = require("./server/whatsapp");
const fs = require("fs");
const dbs = require('./server/database/index');
require("dotenv").config();
const lib = require("./server/lib");
global.log = lib.log;

/**
 * EXPRESS FOR ROUTING
 */
const express = require("express");
const app = express();
const http = require("http");
const server = http.createServer(app);

/**
 * SOCKET.IO
 */
const { Server } = require("socket.io");
const io = new Server(server, {
  pingInterval: 25000,
  pingTimeout: 10000,
});
const port = process.env.PORT_NODE;

app.use((req, res, next) => {
  res.set("Cache-Control", "no-store");
  req.io = io;
  next();
});

const bodyParser = require("body-parser");

app.use(
  bodyParser.urlencoded({
    extended: false,
    limit: "50mb",
    parameterLimit: 100000,
  })
);

app.use(bodyParser.json());
app.use(express.static("src/public"));
app.use(require("./server/router"));

io.on("connection", (socket) => {
  console.log("A user connected");

  socket.on("StartConnection", (data) => {
    wa.connectToWhatsApp(data, io);
  });

  socket.on("ConnectViaCode", (data) => {
    wa.connectToWhatsApp(data, io, true);
  });

  socket.on("LogoutDevice", (device) => {
    wa.deleteCredentials(device, io);
  });

  socket.on("disconnect", () => {
    console.log("A user disconnected");
  });
});

server.listen(port, () => {
    console.log(`Server running and listening on port: ${port}`);
});

dbs.db.query("SELECT * FROM devices WHERE status = 'Connected'", (err, results) => {
  if (err) {
    console.error('Error executing query:', err);
  }
  results.forEach(row => {
    const number = row.body;
    if (/^\d+$/.test(number)) {
      wa.connectToWhatsApp(number);
    }
  });
});
