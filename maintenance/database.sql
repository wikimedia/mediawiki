-- SQL script to create database for wiki.  This is run from
-- the installation script which replaces the variables with
-- their values from local settings.
--

DROP DATABASE IF EXISTS `{$wgDBname}`;
CREATE DATABASE `{$wgDBname}`;
