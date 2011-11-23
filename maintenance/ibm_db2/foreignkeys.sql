-- good
ALTER TABLE user_groups ADD CONSTRAINT USER_GROUPS_FK1 FOREIGN KEY (ug_user) REFERENCES user(user_id) ON DELETE CASCADE
;

-- good
ALTER TABLE user_newtalk ADD CONSTRAINT USER_NEWTALK_FK1 FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE CASCADE
;

-- referenced value not found
ALTER TABLE revision ADD CONSTRAINT REVISION_PAGE_FK FOREIGN KEY (rev_page) REFERENCES page(page_id) ON DELETE CASCADE
;
-- referenced value not found
ALTER TABLE revision ADD CONSTRAINT REVISION_USER_FK FOREIGN KEY (rev_user) REFERENCES user(user_id) ON DELETE RESTRICT
;

-- good
ALTER TABLE page_restrictions ADD CONSTRAINT PAGE_RESTRICTIONS_PAGE_FK FOREIGN KEY (pr_page) REFERENCES page(page_id) ON DELETE CASCADE
;

-- good
ALTER TABLE page_props ADD CONSTRAINT PAGE_PROPS_PAGE_FK FOREIGN KEY (pp_page) REFERENCES page(page_id) ON DELETE CASCADE
;

-- cannot contain null values
-- ALTER TABLE archive ADD CONSTRAINT ARCHIVE_USER_FK FOREIGN KEY (ar_user) REFERENCES user(user_id) ON DELETE SET NULL
--;

-- referenced value not found
ALTER TABLE redirect ADD CONSTRAINT REDIRECT_FROM_FK FOREIGN KEY (rd_from) REFERENCES page(page_id) ON DELETE CASCADE
;

-- referenced value not found
ALTER TABLE pagelinks ADD CONSTRAINT PAGELINKS_FROM_FK FOREIGN KEY (pl_from) REFERENCES page(page_id) ON DELETE CASCADE
;

-- good
ALTER TABLE templatelinks ADD CONSTRAINT TEMPLATELINKS_FROM_FK FOREIGN KEY (tl_from) REFERENCES page(page_id) ON DELETE CASCADE
;

-- good
ALTER TABLE imagelinks ADD CONSTRAINT IMAGELINKS_FROM_FK FOREIGN KEY (il_from) REFERENCES page(page_id) ON DELETE CASCADE
;

-- good
ALTER TABLE categorylinks ADD CONSTRAINT CATEGORYLINKS_FROM_FK FOREIGN KEY (cl_from) REFERENCES page(page_id) ON DELETE CASCADE
;

-- good
ALTER TABLE externallinks ADD CONSTRAINT EXTERNALLINKS_FROM_FK FOREIGN KEY (el_from) REFERENCES page(page_id) ON DELETE CASCADE
;

-- good
ALTER TABLE langlinks ADD CONSTRAINT LANGLINKS_FROM_FK FOREIGN KEY (ll_from) REFERENCES page(page_id) ON DELETE CASCADE
;

-- cannot contain null values
-- ALTER TABLE ipblocks ADD CONSTRAINT IPBLOCKS_USER_FK FOREIGN KEY (ipb_user) REFERENCES user(user_id) ON DELETE SET NULL
--;

-- good
ALTER TABLE ipblocks ADD CONSTRAINT IPBLOCKS_BY_FK FOREIGN KEY (ipb_by) REFERENCES user(user_id) ON DELETE CASCADE
;

-- cannot contain null values
-- ALTER TABLE image ADD CONSTRAINT IMAGE_USER_FK FOREIGN KEY (img_user) REFERENCES user(user_id) ON DELETE SET NULL
--;

-- cannot contain null values
-- ALTER TABLE oldimage ADD CONSTRAINT OLDIMAGE_USER_FK FOREIGN KEY (oi_user) REFERENCES user(user_id) ON DELETE SET NULL
--;

-- good
ALTER TABLE oldimage ADD CONSTRAINT OLDIMAGE_NAME_FK FOREIGN KEY (oi_name) REFERENCES image(img_name) ON DELETE CASCADE
;

-- cannot contain null values
-- ALTER TABLE filearchive ADD CONSTRAINT FILEARCHIVE_DELETED_USER_FK FOREIGN KEY (fa_deleted_user) REFERENCES user(user_id) ON DELETE SET NULL
--;

-- cannot contain null values
-- ALTER TABLE filearchive ADD CONSTRAINT FILEARCHIVE_USER_FK FOREIGN KEY (fa_user) REFERENCES user(user_id) ON DELETE SET NULL
--;

-- cannot contain null values
-- ALTER TABLE recentchanges ADD CONSTRAINT RECENTCHANGES_USER_FK FOREIGN KEY (rc_user) REFERENCES user(user_id) ON DELETE SET NULL
--;

-- cannot contain null values
-- ALTER TABLE recentchanges ADD CONSTRAINT RECENTCHANGES_CUR_ID_FK FOREIGN KEY (rc_cur_id) REFERENCES page(page_id) ON DELETE SET NULL
--;

-- good
ALTER TABLE watchlist ADD CONSTRAINT WATCHLIST_USER_FK FOREIGN KEY (wl_user) REFERENCES user(user_id) ON DELETE CASCADE
;

-- cannot contain null values
-- ALTER TABLE protected_titles ADD CONSTRAINT PROTECTED_TITLES_USER_FK FOREIGN KEY (pt_user) REFERENCES user(user_id) ON DELETE SET NULL
--;

-- cannot contain null values
-- ALTER TABLE logging ADD CONSTRAINT LOGGING_USER_FK FOREIGN KEY (log_user) REFERENCES user(user_id) ON DELETE SET NULL
--;