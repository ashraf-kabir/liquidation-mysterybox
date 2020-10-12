var dotenv = require("dotenv");
dotenv.config();

module.exports = {
  development: {
    database: {
      dialect: "mysql",
      username: "root",
      password: "root",
      database: "konfor",
      host: "localhost",
      logging: console.log,
      timezone: "-04:00",
      pool: {
        maxConnections: 1,
        minConnections: 0,
        maxIdleTime: 100,
      },
      define: {
        timestamps: false,
        underscoredAll: true,
        underscored: true,
      },
    },
    mail_public_key: process.env.MAILGUN_PUBLIC_API_KEY,
    mail_secret_key: process.env.MAILGUN_SECRET_API_KEY,
    from_email: process.env.MAILGUN_FROM_EMAIL,
  },
  test: {
    dialect: "mysql",
    username: "root",
    password: "root",
    database: "konfor",
    host: "localhost",
  },
  production: {
    database: {
      username: process.env.DB_USERNAME,
      password: process.env.DB_PASSWORD,
      database: process.env.DB_NAME,
      host: process.env.DB_HOSTNAME,
      dialect: "postgres",
      logging: true,
      timezone: "-04:00",
      pool: {
        maxConnections: 1,
        minConnections: 0,
        maxIdleTime: 100,
      },
      define: {
        timestamps: false,
        underscoredAll: true,
        underscored: true,
      },
    },
  },
};
