ALTER TABLE /*_*/categorylinks
ADD COLUMN cl_realto varchar(255) binary NOT NULL default '';

CREATE INDEX /*i*/cl_realto ON /*_*/categorylinks (cl_realto, cl_to, cl_from);

DROP INDEX /*i*/cl_from;

CREATE INDEX /*i*/cl_from ON /*_*/categorylinks (cl_from, cl_to);

UPDATE /*_*/categorylinks
SET cl_realto = (
	SELECT rd_title FROM /*_*/page
	INNER JOIN /*_*/redirect ON rd_from = page_id
	WHERE page_namespace = 14 AND page_title = cl_to
);

UPDATE /*_*/categorylinks
SET cl_realto = cl_to
WHERE cl_realto IS NULL;
