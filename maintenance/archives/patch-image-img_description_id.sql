--
-- patch-image-img_description_id.sql
--
-- T188132. Add `img_description_id` to the `image` table.

ALTER TABLE /*_*/image
  ADD COLUMN img_description_id bigint unsigned NOT NULL DEFAULT 0 AFTER img_description;
