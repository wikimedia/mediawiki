CREATE TABLE /*_*/page_props_tmp (
  pp_page int NOT NULL,
  pp_propname varbinary(60) NOT NULL,
  pp_value blob NOT NULL,
  pp_sortkey float DEFAULT NULL,

  PRIMARY KEY (pp_page,pp_propname)
) /*$wgDBTableOptions*/;

INSERT INTO /*_*/page_props_tmp
	SELECT * FROM /*_*/page_props;

DROP TABLE /*_*/page_props;

ALTER TABLE /*_*/page_props_tmp RENAME TO /*_*/page_props;

CREATE UNIQUE INDEX /*i*/pp_propname_page ON /*_*/page_props (pp_propname,pp_page);
CREATE UNIQUE INDEX /*i*/pp_propname_sortkey_page ON /*_*/page_props (pp_propname,pp_sortkey,pp_page);