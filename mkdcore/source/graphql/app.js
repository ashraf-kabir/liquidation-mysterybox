"use strict";
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2020*/
/**
 * App
 * @copyright 2020 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
const express = require("express");
const { ApolloServer, gql } = require("apollo-server-express");
const fs = require("fs");
const path = require("path");
const body_parser = require("body-parser");
const db = require("./src/models");
const jwt = require("./src/services/jwt_service");
// Construct a schema, using GraphQL schema language
const typeDefs = fs.readFileSync(
  path.join(__dirname, "/src/types/schema.graphql"),
  "utf8"
);
// Provide resolver functions for your schema fields
const resolvers = require("./src/resolvers");
const { AuthenticationError } = require("apollo-server-express");
// var FirebaseService = require(__dirname + "/src/services/FirebaseService");
// var fcm = new FirebaseService();

const server = new ApolloServer({
  typeDefs,
  resolvers,
  context: async ({ req }) => {
    const token = req.headers.authorization;
    const cleanToken = token.replace("Bearer ", "");
    const verify = jwt.verifyAccessToken(cleanToken);

    if (!verify) {
      throw new AuthenticationError("Token Not Match");
    }

    if (verify.role_id !== 1) {
      throw new AuthenticationError("Access Denied");
    }

    return {
      authScope: verify.id,
      credential: verify,
      fcm,
      db: db,
    };
  },
  formatError: (err) => {
    // Don't give the specific errors to the client.
    if (err.message.indexOf("Invalid Credential") > -1) {
      return new Error("Invalid Credential");
    }
    if (err.message.indexOf("Authorization Token Invalid") > -1) {
      return new Error("Authorization Token Invalid");
    }
    if (err.message.indexOf("Authorization Invalid Role") > -1) {
      return new Error("Authorization Invalid Role");
    }

    if (err.message.indexOf("Access Denied") > -1) {
      return new Error("Access Denied");
    }
    return err;
  },
});

const app = express();
const uri = "/graphql";

app.use(body_parser.json({ limit: "50mb" }));
server.applyMiddleware({ app, uri });

module.exports = app;
