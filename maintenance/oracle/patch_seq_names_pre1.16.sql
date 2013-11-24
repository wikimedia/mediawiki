-- script for renameing sequence names to conform with <table>_<field>_seq format
RENAME rev_rev_id_val TO revision_rev_id_seq;
RENAME text_old_id_val TO text_old_id_seq;
RENAME category_id_seq TO category_cat_id_seq;
RENAME ipblocks_ipb_id_val TO ipblocks_ipb_id_seq;
RENAME rc_rc_id_seq TO recentchanges_rc_id_seq;
RENAME log_log_id_seq TO logging_log_id_seq;
RENAME pr_id_val TO page_restrictions_pr_id_seq;