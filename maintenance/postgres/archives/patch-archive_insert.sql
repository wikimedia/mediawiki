CREATE OR REPLACE RULE archive_insert AS ON INSERT TO archive
DO INSTEAD INSERT INTO archive2 VALUES (
  NEW.ar_namespace, NEW.ar_title, NEW.ar_text, NEW.ar_comment, NEW.ar_user, NEW.ar_user_text,
  TO_TIMESTAMP(NEW.ar_timestamp, 'YYYYMMDDHH24MISS'),
  NEW.ar_minor_edit, NEW.ar_flags, NEW.ar_rev_id, NEW.ar_text_id
);
