CREATE TABLE /*_*/revision_actor_temp (
  revactor_rev int unsigned NOT NULL,
  revactor_actor bigint unsigned NOT NULL,
  revactor_timestamp binary(14) NOT NULL default '',
  revactor_page int unsigned NOT NULL,
  PRIMARY KEY (revactor_rev, revactor_actor)
) /*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/revactor_rev ON /*_*/revision_actor_temp (revactor_rev);
CREATE INDEX /*i*/actor_timestamp ON /*_*/revision_actor_temp (revactor_actor,revactor_timestamp);
CREATE INDEX /*i*/page_actor_timestamp ON /*_*/revision_actor_temp (revactor_page,revactor_actor,revactor_timestamp);
