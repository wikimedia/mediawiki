-- Experimental: create shared international database
-- for new interlinking code.
--

CREATE DATABASE intl;

GRANT DELETE,INSERT,SELECT,UPDATE ON intl.*
TO wikiuser@'%' IDENTIFIED BY 'userpass';
GRANT DELETE,INSERT,SELECT,UPDATE ON intl.*
TO wikiuser@localhost IDENTIFIED BY 'userpass';
GRANT DELETE,INSERT,SELECT,UPDATE ON intl.*
TO wikiuser@localhost.localdomain IDENTIFIED BY 'userpass';

USE intl;

CREATE TABLE ilinks (
 lang_from varchar(5) default NULL,
 lang_to varchar(5) default NULL,
 title_from tinyblob,
 title_to tinyblob,
 target_exists tinyint(1) default NULL
) TYPE=MyISAM;

CREATE TABLE recentchanges (
 user_name tinyblob,
 user_lang varchar(5) default NULL,
 date timestamp(14) NOT NULL,
 message tinyblob
) TYPE=MyISAM;


