-- T182678: Make ar_rev_id not nullable
ALTER TABLE /*_*/archive
  CHANGE COLUMN ar_rev_id ar_rev_id int unsigned NOT NULL;
