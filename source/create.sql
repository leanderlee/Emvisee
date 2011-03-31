-- *******************************************************
--
--   The Project: Database Creation
--  
--   Leander Lee, 2009-2010
--   Contact: me@leander.ca
--
--   Modified Date: Oct 18, 2010
--   Creation Date: May 25, 2009
--
--
-- +--------------------------------------------------+
-- | Please refer to the documentation for additional |
-- | information about this web application.          |
-- +--------------------------------------------------+
--


-- ------------------------------------------------------
--                                                     --
--              Begin Creating Tables                  --
--                                                     --
-- ------------------------------------------------------

DROP DATABASE IF EXISTS cabinet_db;
CREATE DATABASE IF NOT EXISTS cabinet_db DEFAULT CHARACTER SET = utf8;
USE cabinet_db;


-- Users --
CREATE TABLE IF NOT EXISTS `Users` (
  ID            bigint unsigned not null auto_increment unique primary key,
  Status        tinyint unsigned default 1,
  Name          varchar(255) unique,
  Password      varchar(255),
  IP            varchar(32) default '',
  LoginDate     bigint unsigned default 0,
  CreateDate    bigint unsigned,
  
  -- Indices --
  INDEX (Name)
  
) ENGINE=InnoDB;

INSERT INTO `Users` (Name, Password, CreateDate) VALUES ("root", "$1$Ha0.Er0.$hmalPosOZ2.N4D0OpioMs1", UNIX_TIMESTAMP());


-- Groups --
CREATE TABLE IF NOT EXISTS `Groups` (
  ID            bigint unsigned not null auto_increment unique primary key,
  Status        tinyint unsigned default 1,
  Name          varchar(255) unique,
  Owner         bigint unsigned,
  CreateDate    bigint unsigned,
  
  -- Indices --
  INDEX (Name)
  
) ENGINE=InnoDB;


-- Settings --
CREATE TABLE IF NOT EXISTS `Settings` (
  ID            bigint unsigned not null auto_increment unique primary key,
  Active        tinyint unsigned default 1,
  Setting       varchar(255),
  Widget        varchar(255),
  Design        bigint unsigned default 0,
  Property      text,
  Owner         bigint unsigned,
  CreateDate    bigint unsigned,
  ModifyDate    bigint unsigned,
  
  -- Indices --
  INDEX (ID, Active),
  INDEX (Setting, Active),
  INDEX (Setting),
  INDEX (Widget, Design)
  
) ENGINE=InnoDB;


-- Members --
CREATE TABLE IF NOT EXISTS `Members` (
  ID            bigint unsigned not null auto_increment unique primary key,
  GroupID       bigint unsigned,
  MemberID      bigint unsigned,
  Clearance     int unsigned,
  AuthorizedBy  bigint unsigned,
  Comments      text,
  ExpiryDate    bigint unsigned,
  CreateDate    bigint unsigned,
  ModifyDate    bigint unsigned,
  
  -- Indices --
  UNIQUE INDEX (GroupID, MemberID),
  INDEX (ExpiryDate)
  
) ENGINE=InnoDB;


-- Instances --
CREATE TABLE IF NOT EXISTS `Instances` (
  ID            bigint unsigned not null auto_increment unique primary key,
  Active        tinyint unsigned default 1,
  Creator       bigint unsigned,
  Design        bigint unsigned,
  GroupID       bigint unsigned default 0,
  ModifyDate    bigint unsigned,
  CreateDate    bigint unsigned,
  
  -- Indices --
  INDEX (ID, Active),
  INDEX (Creator),
  INDEX (Design, Active)
  
) ENGINE=InnoDB;


-- Properties --
CREATE TABLE IF NOT EXISTS `Properties` (
  ID            bigint unsigned AUTO_INCREMENT PRIMARY KEY,
  Active        tinyint unsigned default 1,
  Instance      bigint unsigned,
  Tag           bigint unsigned,
  Property      text,
  MostRecent    tinyint unsigned default 1,
  GroupID       bigint unsigned default 0,
  Creator       bigint unsigned,
  CreateDate    bigint unsigned,
  
  -- Indices --
  INDEX (ID, Active),
  INDEX (Instance),
  INDEX (Tag),
  INDEX (MostRecent),
  INDEX (Creator),
  INDEX (CreateDate)
  
) ENGINE=InnoDB;


-- Designs --
CREATE TABLE IF NOT EXISTS `Designs` (
  ID            bigint unsigned not null auto_increment unique primary key,
  Active        tinyint unsigned default 1,
  Slug          varchar(255) unique,
  Name          varchar(255),
  Single        varchar(255),
  Plural        varchar(255),
  GroupID       bigint unsigned default 0,
  ModifyDate    bigint unsigned,
  CreateDate    bigint unsigned,
  
  -- Indices --
  INDEX (Slug, Active)
  
) ENGINE=InnoDB;


-- Tags --
CREATE TABLE IF NOT EXISTS `Tags` (
  ID            bigint unsigned not null auto_increment unique primary key,
  Active        tinyint unsigned default 1,
  Position      int,
  Design        bigint unsigned,
  Rules         text,
  GroupID       bigint unsigned default 0,
  ModifyDate    bigint unsigned,
  CreateDate    bigint unsigned,
  
  -- Indices --
  INDEX (ID, Active),
  INDEX (Design),
  INDEX (Position)
  
) ENGINE=InnoDB;

