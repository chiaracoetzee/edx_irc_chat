CREATE DATABASE IF NOT EXISTS `cs169.1x.T32013.chat`;
USE `cs169.1x.T32013.chat`;
CREATE TABLE `consent` (
   username VARCHAR(256) PRIMARY KEY,
   nextpopuptime DATETIME,
   consentedtime DATETIME,
   rejectedtime DATETIME,
   isadmin BOOLEAN
);