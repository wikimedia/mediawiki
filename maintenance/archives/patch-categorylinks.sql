--
-- Track category inclusions *used inline*
-- cl_from keys to cur_id, cl_to keys to cur_title of the category page.
-- cl_sortkey is the title of the linking page or an optional override
-- cl_timestamp marks when the link was last added
--
CREATE TABLE categorylinks (
  cl_from int(8) unsigned NOT NULL default '0',
  cl_to varchar(255) binary NOT NULL default '',
  cl_sortkey varchar(255) binary NOT NULL default '',
  cl_timestamp timestamp NOT NULL,
  UNIQUE KEY cl_from(cl_from,cl_to),
  KEY cl_sortkey(cl_to,cl_sortkey(128)),
  KEY cl_timestamp(cl_to,cl_timestamp)
);
