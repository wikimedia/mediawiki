-- Holds configuration options
CREATE TABLE /*_*/config (
  -- group the setting belongs to (ex: main for core, others for extensions/skins)
  cf_group VARCHAR(255) NOT NULL,
  -- name of the setting (ex: "Sitename")
  cf_name VARCHAR(255) NOT NULL,
  -- JSON encoded value of the setting
  cf_value BLOB NOT NULL,
  -- wiki ID or group that this setting applies to
  cf_wiki VARCHAR(255) NOT NULL
);

CREATE UNIQUE INDEX /*i*/config_group_name_wiki ON /*_*/config(cf_group, cf_name, cf_wiki);
CREATE INDEX /*i*/config_wiki ON /*_*/config(cf_wiki);

