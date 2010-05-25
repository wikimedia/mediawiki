-- 
-- Track inline interwiki links
--
CREATE TABLE /*_*/iwlinks (
  -- page_id of the referring page
  iwl_from int unsigned NOT NULL default 0,
  
  -- Interwiki prefix code of the target
  iwl_prefix varbinary(20) NOT NULL default '',

  -- Title of the target, including namespace
  iwl_title varchar(255) binary NOT NULL default ''
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/iwl_from ON /*_*/iwlinks (iwl_from, iwl_prefix, iwl_title);
CREATE INDEX /*i*/iwl_prefix_from_title ON /*_*/iwlinks (iwl_prefix, iwl_from, iwl_title);
