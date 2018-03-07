--
-- patch-image-img_description_id.sql
--
-- T188132. Add `img_description_id` to the `image` table.

ALTER TABLE /*_*/image ADD img_description_id bigint NOT NULL CONSTRAINT DF_img_description_id DEFAULT 0 CONSTRAINT FK_img_description_id FOREIGN KEY REFERENCES /*_*/comment(comment_id);
