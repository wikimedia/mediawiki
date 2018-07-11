DROP INDEX IF EXISTS /*i*/pp_page_propname ON /*_*/page_props;
ALTER TABLE /*_*/page_props ADD CONSTRAINT PK_page_props PRIMARY KEY(pp_page,pp_propname);