ALTER TABLE revision RENAME rev_rev_id_val TO revision_rev_id_seq;
ALTER TABLE revision ALTER COLUMN rev_id SET DEFAULT NEXTVAL('revision_rev_id_seq');

ALTER TABLE pagecontent RENAME text_old_id_val TO text_old_id_seq;
ALTER TABLE pagecontent ALTER COLUMN old_id SET DEFAULT nextval('text_old_id_seq');

ALTER TABLE category RENAME category_id_seq TO category_cat_id_seq;
ALTER TABLE category ALTER COLUMN cat_id SET DEFAULT nextval('category_cat_id_seq');

ALTER TABLE ipblocks RENAME ipblocks_ipb_id_val TO ipblocks_ipb_id_seq;
ALTER TABLE ipblocks ALTER COLUMN ipb_id SET DEFAULT nextval('ipblocks_ipb_id_seq');

ALTER TABLE recentchanges RENAME rc_rc_id_seq TO recentchanges_rc_id_seq;
ALTER TABLE recentchanges ALTER COLUMN rc_id SET DEFAULT nextval('recentchanges_rc_id_seq');

ALTER TABLE logging RENAME log_log_id_seq TO logging_log_id_seq;
ALTER TABLE logging ALTER COLUMN log_id SET DEFAULT nextval('logging_log_id_seq');

ALTER TABLE page_restrictions RENAME pr_id_val TO page_restrictions_pr_id_seq;
ALTER TABLE page_restrictions ALTER COLUMN pr_id SET DEFAULT nextval('page_restrictions_pr_id_seq');
