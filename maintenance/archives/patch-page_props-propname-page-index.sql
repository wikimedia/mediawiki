--
-- Creates the pp_propname_page index on page_props
--
CREATE UNIQUE INDEX /*i*/pp_propname_page ON /*_*/page_props (pp_propname, pp_page);
