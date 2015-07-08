ALTER TABLE /*_*/page_props RENAME TO /*_*/page_props_temp;

CREATE TABLE /*_*/page_props (
  pp_page int NOT NULL,
  pp_propname varbinary(60) NOT NULL,
  pp_value blob NOT NULL,
  pp_sortkey double DEFAULT NULL
) /*$wgDBTableOptions*/;

INSERT INTO /*_*/page_props (pp_page, pp_propname, pp_value, pp_sortkey)
SELECT pp_page, pp_propname, pp_value, pp_sortkey FROM /*_*/page_props_temp;

DROP TABLE /*_*/page_props_temp;

CREATE UNIQUE INDEX /*i*/pp_page_propname ON /*_*/page_props (pp_page,pp_propname);
CREATE UNIQUE INDEX /*i*/pp_propname_page ON /*_*/page_props (pp_propname,pp_page);
CREATE UNIQUE INDEX /*i*/pp_propname_sortkey_page ON /*_*/page_props (pp_propname,pp_sortkey,pp_page);
