-- T182678: Make ar_rev_id not nullable
ALTER TABLE archive
  ALTER COLUMN ar_rev_id SET NOT NULL;
