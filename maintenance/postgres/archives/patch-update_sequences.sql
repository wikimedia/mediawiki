ALTER SEQUENCE rev_rev_id_val RENAME TO revision_rev_id_seq;
ALTER TABLE revision ALTER COLUMN rev_id SET DEFAULT NEXTVAL('revision_rev_id_seq');

ALTER SEQUENCE text_old_id_val RENAME TO text_old_id_seq;
ALTER TABLE pagecontent ALTER COLUMN old_id SET DEFAULT nextval('text_old_id_seq');

ALTER SEQUENCE category_id_seq RENAME TO category_cat_id_seq;
ALTER TABLE category ALTER COLUMN cat_id SET DEFAULT nextval('category_cat_id_seq');

ALTER SEQUENCE ipblocks_ipb_id_val RENAME TO ipblocks_ipb_id_seq;
ALTER TABLE ipblocks ALTER COLUMN ipb_id SET DEFAULT nextval('ipblocks_ipb_id_seq');

ALTER SEQUENCE rc_rc_id_seq RENAME TO recentchanges_rc_id_seq;
ALTER TABLE recentchanges ALTER COLUMN rc_id SET DEFAULT nextval('recentchanges_rc_id_seq');

ALTER SEQUENCE log_log_id_seq RENAME TO logging_log_id_seq;
ALTER TABLE logging ALTER COLUMN log_id SET DEFAULT nextval('logging_log_id_seq');

ALTER SEQUENCE pr_id_val RENAME TO page_restrictions_pr_id_seq;
ALTER TABLE page_restrictions ALTER COLUMN pr_id SET DEFAULT nextval('page_restrictions_pr_id_seq');
