-- Table listing distant wiki namespace texts.
CREATE TABLE /*_*/globalnamespaces (
  -- The wiki ID of the remote wiki
  gn_wiki varchar(64) NOT NULL,

  -- The namespace ID of the transcluded page on that wiki
  gn_namespace int NOT NULL,

  -- The namespace text of transcluded page
  -- Needed for display purposes, since the local namespace ID doesn't necessarily match a distant one
  gn_namespacetext varchar(255) NOT NULL

) /*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/gn_index ON /*_*/globalnamespaces (gn_wiki, gn_namespace, gn_namespacetext);
