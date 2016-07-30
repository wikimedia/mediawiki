ALTER TABLE revision
    DROP COLUMN rev_content_model;

ALTER TABLE revision
  DROP COLUMN rev_content_format;

ALTER TABLE revision
  DROP COLUMN rev_text_id;

ALTER TABLE archive
  DROP COLUMN ar_content_model;

ALTER TABLE archive
  DROP COLUMN ar_content_format;

ALTER TABLE archive
  DROP COLUMN ar_text_id;

ALTER TABLE page
  DROP COLUMN page_content_model;

-- ALTER TABLE page
--   DROP COLUMN page_language;