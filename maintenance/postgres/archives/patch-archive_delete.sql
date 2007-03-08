CREATE RULE archive_delete AS ON DELETE TO archive
DO INSTEAD DELETE FROM archive2 WHERE
  archive2.ar_title = OLD.ar_title AND
  archive2.ar_namespace = OLD.ar_namespace AND
  archive2.ar_rev_id = OLD.ar_rev_id;
