-- Add a 'sortkey' field to page_props so pages can be efficiently
-- queried by the numeric value of a property.

ALTER TABLE /*_*/page_props
        ADD pp_sortkey float DEFAULT NULL;

CREATE UNIQUE INDEX /*i*/pp_propname_sortkey_page
        ON /*_*/page_props ( pp_propname, pp_sortkey, pp_page );
