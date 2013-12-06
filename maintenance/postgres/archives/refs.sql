-- Name: archive_ar_user_fkey; Type: FK CONSTRAINT; Schema: mediawiki; Owner: wikiuser
--

INSERT INTO unittest_mwuser SELECT * FROM mwuser;

ALTER TABLE ONLY unittest_archive
    ADD CONSTRAINT ut_archive_ar_user_fkey FOREIGN KEY (ar_user) REFERENCES unittest_mwuser(user_id) ON DELETE SET NULL DEFERRABLE INITIALLY DEFERRED;


--
-- Name: categorylinks_cl_from_fkey; Type: FK CONSTRAINT; Schema: mediawiki; Owner: wikiuser
--

ALTER TABLE ONLY unittest_categorylinks
    ADD CONSTRAINT ut_categorylinks_cl_from_fkey FOREIGN KEY (cl_from) REFERENCES unittest_page(page_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;


--
-- Name: externallinks_el_from_fkey; Type: FK CONSTRAINT; Schema: mediawiki; Owner: wikiuser
--

ALTER TABLE ONLY unittest_externallinks
    ADD CONSTRAINT ut_externallinks_el_from_fkey FOREIGN KEY (el_from) REFERENCES unittest_page(page_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;


--
-- Name: filearchive_fa_deleted_user_fkey; Type: FK CONSTRAINT; Schema: mediawiki; Owner: wikiuser
--

ALTER TABLE ONLY unittest_filearchive
    ADD CONSTRAINT ut_filearchive_fa_deleted_user_fkey FOREIGN KEY (fa_deleted_user) REFERENCES unittest_mwuser(user_id) ON DELETE SET NULL DEFERRABLE INITIALLY DEFERRED;


--
-- Name: filearchive_fa_user_fkey; Type: FK CONSTRAINT; Schema: mediawiki; Owner: wikiuser
--

ALTER TABLE ONLY unittest_filearchive
    ADD CONSTRAINT ut_filearchive_fa_user_fkey FOREIGN KEY (fa_user) REFERENCES unittest_mwuser(user_id) ON DELETE SET NULL DEFERRABLE INITIALLY DEFERRED;


--
-- Name: image_img_user_fkey; Type: FK CONSTRAINT; Schema: mediawiki; Owner: wikiuser
--

ALTER TABLE ONLY unittest_image
    ADD CONSTRAINT ut_image_img_user_fkey FOREIGN KEY (img_user) REFERENCES unittest_mwuser(user_id) ON DELETE SET NULL DEFERRABLE INITIALLY DEFERRED;


--
-- Name: imagelinks_il_from_fkey; Type: FK CONSTRAINT; Schema: mediawiki; Owner: wikiuser
--

ALTER TABLE ONLY unittest_imagelinks
    ADD CONSTRAINT ut_imagelinks_il_from_fkey FOREIGN KEY (il_from) REFERENCES unittest_page(page_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;


--
-- Name: ipblocks_ipb_by_fkey; Type: FK CONSTRAINT; Schema: mediawiki; Owner: wikiuser
--

ALTER TABLE ONLY unittest_ipblocks
    ADD CONSTRAINT ut_ipblocks_ipb_by_fkey FOREIGN KEY (ipb_by) REFERENCES unittest_mwuser(user_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;


--
-- Name: ipblocks_ipb_parent_block_id_fkey; Type: FK CONSTRAINT; Schema: mediawiki; Owner: wikiuser
--

ALTER TABLE ONLY unittest_ipblocks
    ADD CONSTRAINT ut_ipblocks_ipb_parent_block_id_fkey FOREIGN KEY (ipb_parent_block_id) REFERENCES unittest_ipblocks(ipb_id) ON DELETE SET NULL DEFERRABLE INITIALLY DEFERRED;


--
-- Name: ipblocks_ipb_user_fkey; Type: FK CONSTRAINT; Schema: mediawiki; Owner: wikiuser
--

ALTER TABLE ONLY unittest_ipblocks
    ADD CONSTRAINT ut_ipblocks_ipb_user_fkey FOREIGN KEY (ipb_user) REFERENCES unittest_mwuser(user_id) ON DELETE SET NULL DEFERRABLE INITIALLY DEFERRED;


--
-- Name: langlinks_ll_from_fkey; Type: FK CONSTRAINT; Schema: mediawiki; Owner: wikiuser
--

ALTER TABLE ONLY unittest_langlinks
    ADD CONSTRAINT ut_langlinks_ll_from_fkey FOREIGN KEY (ll_from) REFERENCES unittest_page(page_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;


--
-- Name: logging_log_user_fkey; Type: FK CONSTRAINT; Schema: mediawiki; Owner: wikiuser
--

ALTER TABLE ONLY unittest_logging
    ADD CONSTRAINT ut_logging_log_user_fkey FOREIGN KEY (log_user) REFERENCES unittest_mwuser(user_id) ON DELETE SET NULL DEFERRABLE INITIALLY DEFERRED;


--
-- Name: oldimage_oi_name_fkey_cascaded; Type: FK CONSTRAINT; Schema: mediawiki; Owner: wikiuser
--

ALTER TABLE ONLY unittest_oldimage
    ADD CONSTRAINT ut_oldimage_oi_name_fkey_cascaded FOREIGN KEY (oi_name) REFERENCES unittest_image(img_name) ON UPDATE CASCADE ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;


--
-- Name: oldimage_oi_user_fkey; Type: FK CONSTRAINT; Schema: mediawiki; Owner: wikiuser
--

ALTER TABLE ONLY unittest_oldimage
    ADD CONSTRAINT ut_oldimage_oi_user_fkey FOREIGN KEY (oi_user) REFERENCES unittest_mwuser(user_id) ON DELETE SET NULL DEFERRABLE INITIALLY DEFERRED;


--
-- Name: page_props_pp_page_fkey; Type: FK CONSTRAINT; Schema: mediawiki; Owner: wikiuser
--

ALTER TABLE ONLY unittest_page_props
    ADD CONSTRAINT ut_page_props_pp_page_fkey FOREIGN KEY (pp_page) REFERENCES unittest_page(page_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;


--
-- Name: page_restrictions_pr_page_fkey; Type: FK CONSTRAINT; Schema: mediawiki; Owner: wikiuser
--

ALTER TABLE ONLY unittest_page_restrictions
    ADD CONSTRAINT ut_page_restrictions_pr_page_fkey FOREIGN KEY (pr_page) REFERENCES unittest_page(page_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;


--
-- Name: pagelinks_pl_from_fkey; Type: FK CONSTRAINT; Schema: mediawiki; Owner: wikiuser
--

ALTER TABLE ONLY unittest_pagelinks
    ADD CONSTRAINT ut_pagelinks_pl_from_fkey FOREIGN KEY (pl_from) REFERENCES unittest_page(page_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;


--
-- Name: protected_titles_pt_user_fkey; Type: FK CONSTRAINT; Schema: mediawiki; Owner: wikiuser
--

ALTER TABLE ONLY unittest_protected_titles
    ADD CONSTRAINT ut_protected_titles_pt_user_fkey FOREIGN KEY (pt_user) REFERENCES unittest_mwuser(user_id) ON DELETE SET NULL DEFERRABLE INITIALLY DEFERRED;


--
-- Name: recentchanges_rc_cur_id_fkey; Type: FK CONSTRAINT; Schema: mediawiki; Owner: wikiuser
--

ALTER TABLE ONLY unittest_recentchanges
    ADD CONSTRAINT ut_recentchanges_rc_cur_id_fkey FOREIGN KEY (rc_cur_id) REFERENCES unittest_page(page_id) ON DELETE SET NULL DEFERRABLE INITIALLY DEFERRED;


--
-- Name: recentchanges_rc_user_fkey; Type: FK CONSTRAINT; Schema: mediawiki; Owner: wikiuser
--

ALTER TABLE ONLY unittest_recentchanges
    ADD CONSTRAINT ut_recentchanges_rc_user_fkey FOREIGN KEY (rc_user) REFERENCES unittest_mwuser(user_id) ON DELETE SET NULL DEFERRABLE INITIALLY DEFERRED;


--
-- Name: redirect_rd_from_fkey; Type: FK CONSTRAINT; Schema: mediawiki; Owner: wikiuser
--

ALTER TABLE ONLY unittest_redirect
    ADD CONSTRAINT ut_redirect_rd_from_fkey FOREIGN KEY (rd_from) REFERENCES unittest_page(page_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;


--
-- Name: revision_rev_page_fkey; Type: FK CONSTRAINT; Schema: mediawiki; Owner: wikiuser
--

ALTER TABLE ONLY unittest_revision
    ADD CONSTRAINT ut_revision_rev_page_fkey FOREIGN KEY (rev_page) REFERENCES unittest_page(page_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;


--
-- Name: revision_rev_user_fkey; Type: FK CONSTRAINT; Schema: mediawiki; Owner: wikiuser
--

ALTER TABLE ONLY unittest_revision
    ADD CONSTRAINT ut_revision_rev_user_fkey FOREIGN KEY (rev_user) REFERENCES unittest_mwuser(user_id) ON DELETE RESTRICT DEFERRABLE INITIALLY DEFERRED;


--
-- Name: templatelinks_tl_from_fkey; Type: FK CONSTRAINT; Schema: mediawiki; Owner: wikiuser
--

ALTER TABLE ONLY unittest_templatelinks
    ADD CONSTRAINT ut_templatelinks_tl_from_fkey FOREIGN KEY (tl_from) REFERENCES unittest_page(page_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;


--
-- Name: user_former_groups_ufg_user_fkey; Type: FK CONSTRAINT; Schema: mediawiki; Owner: wikiuser
--

ALTER TABLE ONLY unittest_user_former_groups
    ADD CONSTRAINT ut_user_former_groups_ufg_user_fkey FOREIGN KEY (ufg_user) REFERENCES unittest_mwuser(user_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;


--
-- Name: user_groups_ug_user_fkey; Type: FK CONSTRAINT; Schema: mediawiki; Owner: wikiuser
--

ALTER TABLE ONLY unittest_user_groups
    ADD CONSTRAINT ut_user_groups_ug_user_fkey FOREIGN KEY (ug_user) REFERENCES unittest_mwuser(user_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;


--
-- Name: user_newtalk_user_id_fkey; Type: FK CONSTRAINT; Schema: mediawiki; Owner: wikiuser
--

ALTER TABLE ONLY unittest_user_newtalk
    ADD CONSTRAINT ut_user_newtalk_user_id_fkey FOREIGN KEY (user_id) REFERENCES unittest_mwuser(user_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;


--
-- Name: user_properties_up_user_fkey; Type: FK CONSTRAINT; Schema: mediawiki; Owner: wikiuser
--

ALTER TABLE ONLY unittest_user_properties
    ADD CONSTRAINT ut_user_properties_up_user_fkey FOREIGN KEY (up_user) REFERENCES unittest_mwuser(user_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;


--
-- Name: watchlist_wl_user_fkey; Type: FK CONSTRAINT; Schema: mediawiki; Owner: wikiuser
--

ALTER TABLE ONLY unittest_watchlist
    ADD CONSTRAINT ut_watchlist_wl_user_fkey FOREIGN KEY (wl_user) REFERENCES unittest_mwuser(user_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;
