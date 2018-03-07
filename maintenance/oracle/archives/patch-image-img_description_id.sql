--
-- patch-image-img_description_id.sql
--
-- T188132. Add `img_description_id` to the `image` table.

ALTER TABLE &mw_prefix.image ADD ( img_description_id NUMBER DEFAULT 0 NOT NULL );
ALTER TABLE &mw_prefix.image ADD CONSTRAINT &mw_prefix.oldimage_fk2 FOREIGN KEY (img_description_id) REFERENCES &mw_prefix."COMMENT"(comment_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;
