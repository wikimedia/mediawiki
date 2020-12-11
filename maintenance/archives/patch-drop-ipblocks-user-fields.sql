--
-- patch-drop-ipblocks-user-fields.sql
--
-- T188327. Drop old xx_user and xx_user_text fields, and defaults from xx_actor fields.

ALTER TABLE /*_*/ipblocks
  DROP COLUMN ipb_by,
  DROP COLUMN ipb_by_text,
  ALTER COLUMN ipb_by_actor DROP DEFAULT;
