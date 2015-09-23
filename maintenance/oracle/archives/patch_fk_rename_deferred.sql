define mw_prefix='{$wgDBprefix}';

/*$mw$*/
BEGIN
-- drop all, recreate manual in case anyone was missing
  FOR cc1 IN (SELECT uc.table_name,
                     uc.constraint_name
                FROM user_constraints  uc
               WHERE uc.constraint_type = 'R') LOOP
    EXECUTE IMMEDIATE 'ALTER TABLE &mw_prefix.' || cc1.table_name ||
                      ' DROP CONSTRAINT ' || cc1.constraint_name;
  END LOOP;
END;
/*$mw$*/

ALTER TABLE &mw_prefix.user_groups ADD CONSTRAINT &mw_prefix.user_groups_fk1 FOREIGN KEY (ug_user) REFERENCES &mw_prefix.mwuser(user_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;
ALTER TABLE &mw_prefix.user_newtalk ADD CONSTRAINT &mw_prefix.user_newtalk_fk1 FOREIGN KEY (user_id) REFERENCES &mw_prefix.mwuser(user_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;
ALTER TABLE &mw_prefix.revision ADD CONSTRAINT &mw_prefix.revision_fk1 FOREIGN KEY (rev_page) REFERENCES &mw_prefix.page(page_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;
ALTER TABLE &mw_prefix.revision ADD CONSTRAINT &mw_prefix.revision_fk2 FOREIGN KEY (rev_user) REFERENCES &mw_prefix.mwuser(user_id) DEFERRABLE INITIALLY DEFERRED;
ALTER TABLE &mw_prefix.archive ADD CONSTRAINT &mw_prefix.archive_fk1 FOREIGN KEY (ar_user) REFERENCES &mw_prefix.mwuser(user_id) ON DELETE SET NULL DEFERRABLE INITIALLY DEFERRED;
ALTER TABLE &mw_prefix.pagelinks ADD CONSTRAINT &mw_prefix.pagelinks_fk1 FOREIGN KEY (pl_from) REFERENCES &mw_prefix.page(page_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;
ALTER TABLE &mw_prefix.templatelinks ADD CONSTRAINT &mw_prefix.templatelinks_fk1 FOREIGN KEY (tl_from) REFERENCES &mw_prefix.page(page_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;
ALTER TABLE &mw_prefix.imagelinks ADD CONSTRAINT &mw_prefix.imagelinks_fk1 FOREIGN KEY (il_from) REFERENCES &mw_prefix.page(page_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;
ALTER TABLE &mw_prefix.categorylinks ADD CONSTRAINT &mw_prefix.categorylinks_fk1 FOREIGN KEY (cl_from) REFERENCES &mw_prefix.page(page_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;
ALTER TABLE &mw_prefix.externallinks ADD CONSTRAINT &mw_prefix.externallinks_fk1 FOREIGN KEY (el_from) REFERENCES &mw_prefix.page(page_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;
ALTER TABLE &mw_prefix.langlinks ADD CONSTRAINT &mw_prefix.langlinks_fk1 FOREIGN KEY (ll_from) REFERENCES &mw_prefix.page(page_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;
ALTER TABLE &mw_prefix.ipblocks ADD CONSTRAINT &mw_prefix.ipblocks_fk1 FOREIGN KEY (ipb_user) REFERENCES &mw_prefix.mwuser(user_id) ON DELETE SET NULL DEFERRABLE INITIALLY DEFERRED;
ALTER TABLE &mw_prefix.ipblocks ADD CONSTRAINT &mw_prefix.ipblocks_fk2 FOREIGN KEY (ipb_by) REFERENCES &mw_prefix.mwuser(user_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;
ALTER TABLE &mw_prefix.image ADD CONSTRAINT &mw_prefix.image_fk1 FOREIGN KEY (img_user) REFERENCES &mw_prefix.mwuser(user_id) ON DELETE SET NULL DEFERRABLE INITIALLY DEFERRED;
ALTER TABLE &mw_prefix.oldimage ADD CONSTRAINT &mw_prefix.oldimage_fk1 FOREIGN KEY (oi_name) REFERENCES &mw_prefix.image(img_name) ON DELETE SET NULL DEFERRABLE INITIALLY DEFERRED;
ALTER TABLE &mw_prefix.oldimage ADD CONSTRAINT &mw_prefix.oldimage_fk2 FOREIGN KEY (oi_user) REFERENCES &mw_prefix.mwuser(user_id) ON DELETE SET NULL DEFERRABLE INITIALLY DEFERRED;
ALTER TABLE &mw_prefix.filearchive ADD CONSTRAINT &mw_prefix.filearchive_fk1 FOREIGN KEY (fa_deleted_user) REFERENCES &mw_prefix.mwuser(user_id) ON DELETE SET NULL DEFERRABLE INITIALLY DEFERRED;
ALTER TABLE &mw_prefix.filearchive ADD CONSTRAINT &mw_prefix.filearchive_fk2 FOREIGN KEY (fa_user) REFERENCES &mw_prefix.mwuser(user_id) ON DELETE SET NULL DEFERRABLE INITIALLY DEFERRED;
ALTER TABLE &mw_prefix.recentchanges ADD CONSTRAINT &mw_prefix.recentchanges_fk1 FOREIGN KEY (rc_user) REFERENCES &mw_prefix.mwuser(user_id) ON DELETE SET NULL DEFERRABLE INITIALLY DEFERRED;
ALTER TABLE &mw_prefix.recentchanges ADD CONSTRAINT &mw_prefix.recentchanges_fk2 FOREIGN KEY (rc_cur_id) REFERENCES &mw_prefix.page(page_id) ON DELETE SET NULL DEFERRABLE INITIALLY DEFERRED;
ALTER TABLE &mw_prefix.watchlist ADD CONSTRAINT &mw_prefix.watchlist_fk1 FOREIGN KEY (wl_user) REFERENCES &mw_prefix.mwuser(user_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;
ALTER TABLE &mw_prefix.logging ADD CONSTRAINT &mw_prefix.logging_fk1 FOREIGN KEY (log_user) REFERENCES &mw_prefix.mwuser(user_id) ON DELETE SET NULL DEFERRABLE INITIALLY DEFERRED;
ALTER TABLE &mw_prefix.redirect ADD CONSTRAINT &mw_prefix.redirect_fk1 FOREIGN KEY (rd_from) REFERENCES &mw_prefix.page(page_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;
ALTER TABLE &mw_prefix.page_restrictions ADD CONSTRAINT &mw_prefix.page_restrictions_fk1 FOREIGN KEY (pr_page) REFERENCES &mw_prefix.page(page_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;

