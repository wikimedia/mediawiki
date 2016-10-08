DROP INDEX /*i*/oi_name_archive_name ON /*_*/oldimage;

CREATE NONCLUSTERED INDEX /*i*/oi_name_archive_name ON /*_*/oldimage (oi_name,oi_archive_name);
