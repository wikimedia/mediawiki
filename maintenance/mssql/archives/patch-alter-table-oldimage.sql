--

DROP INDEX /*i*/oi_name_archive_name ON /*_*/oldimage;

CREATE INDEX /*i*/oi_name_archive_name ON /*_*/oldimage (oi_name(150), oi_archive_name(150));
