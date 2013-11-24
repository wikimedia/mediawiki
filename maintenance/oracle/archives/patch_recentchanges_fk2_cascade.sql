define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.recentchanges DROP CONSTRAINT &mw_prefix.recentchanges_fk2;
ALTER TABLE &mw_prefix.recentchanges ADD CONSTRAINT &mw_prefix.recentchanges_fk2 FOREIGN KEY (rc_cur_id) REFERENCES &mw_prefix.page(page_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;

