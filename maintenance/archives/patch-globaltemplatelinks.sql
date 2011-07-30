-- Table tracking interwiki transclusions in the spirit of templatelinks.
-- This table tracks transclusions of this wiki's templates on another wiki
-- The gtl_from_* fields describe the (remote) page the template is transcluded from
-- The gtl_to_* fields describe the (local) template being transcluded
CREATE TABLE /*_*/globaltemplatelinks (
  -- The wiki ID of the remote wiki
  gtl_from_wiki varchar(64) NOT NULL,

  -- The page ID of the calling page on the remote wiki
  gtl_from_page int unsigned NOT NULL,

  -- The namespace of the calling page on the remote wiki
  -- Needed for display purposes, since the foreign namespace ID doesn't necessarily match a local one
  -- The link between the namespace and the namespace name is made by the globalnamespaces table 
  gtl_from_namespace int NOT NULL,

  -- The title of the calling page on the remote wiki
  -- Needed for display purposes
  gtl_from_title varchar(255) binary NOT NULL,

  -- The interwiki prefix of the wiki that hosts the transcluded page
  gtl_to_prefix varchar(32) NOT NULL,

  -- The namespace of the transcluded page on that wiki
  gtl_to_namespace int NOT NULL,

  -- The namespace name of transcluded page
  -- Needed for display purposes, since the local namespace ID doesn't necessarily match a distant one
  gtl_to_namespacetext varchar(255) NOT NULL,

  -- The title of the transcluded page on that wiki
  gtl_to_title varchar(255) binary NOT NULL
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/gtl_to_from ON /*_*/globaltemplatelinks (gtl_to_prefix, gtl_to_namespace, gtl_to_title, gtl_from_wiki, gtl_from_page);
CREATE UNIQUE INDEX /*i*/gtl_from_to ON /*_*/globaltemplatelinks (gtl_from_wiki, gtl_from_page, gtl_to_prefix, gtl_to_namespace, gtl_to_title);
