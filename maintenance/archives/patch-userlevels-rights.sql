-- Oct. 24 2004
-- Adds the gr_rights field missing from early dev work

-- Hold group name and description
ALTER TABLE /*$wgDBprefix*/groups ADD gr_rights tinyblob;
