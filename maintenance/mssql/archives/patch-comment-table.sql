--
-- patch-comment-table.sql
--
-- T166732. Add a `comment` table and various columns (and temporary tables) to reference it.

CREATE TABLE /*_*/comment (
  comment_id bigint unsigned NOT NULL PRIMARY KEY IDENTITY(0,1),
  comment_hash INT NOT NULL,
  comment_text nvarchar(max) NOT NULL,
  comment_data nvarchar(max)
);
CREATE INDEX /*i*/comment_hash ON /*_*/comment (comment_hash);

-- dummy row for FKs. Hash is intentionally wrong so CommentStore won't match it.
INSERT INTO /*_*/comment (comment_hash, comment_text) VALUES (-1, '** dummy **');


CREATE TABLE /*_*/revision_comment_temp (
  revcomment_rev INT NOT NULL CONSTRAINT FK_revcomment_rev FOREIGN KEY REFERENCES /*_*/revision(rev_id) ON DELETE CASCADE,
  revcomment_comment_id bigint unsigned NOT NULL CONSTRAINT FK_revcomment_comment_id FOREIGN KEY REFERENCES /*_*/comment(comment_id),
  CONSTRAINT PK_revision_comment_temp PRIMARY KEY (revcomment_rev, revcomment_comment_id)
);
CREATE UNIQUE INDEX /*i*/revcomment_rev ON /*_*/revision_comment_temp (revcomment_rev);


CREATE TABLE /*_*/image_comment_temp (
  imgcomment_name nvarchar(255) NOT NULL CONSTRAINT FK_imgcomment_name FOREIGN KEY REFERENCES /*_*/image(imgcomment_name) ON DELETE CASCADE,
  imgcomment_description_id bigint unsigned NOT NULL CONSTRAINT FK_imgcomment_description_id FOREIGN KEY REFERENCES /*_*/comment(comment_id),
  CONSTRAINT PK_image_comment_temp PRIMARY KEY (imgcomment_name, imgcomment_description_id)
);
CREATE UNIQUE INDEX /*i*/imgcomment_name ON /*_*/image_comment_temp (imgcomment_name);


ALTER TABLE /*_*/revision ALTER COLUMN rev_comment ADD CONSTRAINT DF_rev_comment DEFAULT '';

ALTER TABLE /*_*/archive ALTER COLUMN ar_comment ADD CONSTRAINT DF_ar_comment DEFAULT '';
ALTER TABLE /*_*/archive ADD ar_comment_id bigint unsigned NOT NULL CONSTRAINT DF_ar_comment_id DEFAULT 0 CONSTRAINT FK_ar_comment_id FOREIGN KEY REFERENCES /*_*/comment(comment_id);

ALTER TABLE /*_*/ipblocks ALTER COLUMN ipb_reason ADD CONSTRAINT DF_ipb_reason DEFAULT '';
ALTER TABLE /*_*/ipblocks ADD ipb_reason_id bigint unsigned NOT NULL CONSTRAINT DF_ipb_reason_id DEFAULT 0 CONSTRAINT FK_ipb_reason_id FOREIGN KEY REFERENCES /*_*/comment(comment_id);

ALTER TABLE /*_*/image ALTER COLUMN img_description ADD CONSTRAINT DF_img_description DEFAULT '';

ALTER TABLE /*_*/oldimage ALTER COLUMN oi_description ADD CONSTRAINT DF_oi_description DEFAULT '';
ALTER TABLE /*_*/oldimage ADD oi_description_id bigint unsigned NOT NULL CONSTRAINT DF_oi_description_id DEFAULT 0 CONSTRAINT FK_oi_description_id FOREIGN KEY REFERENCES /*_*/comment(comment_id);

ALTER TABLE /*_*/filearchive ALTER COLUMN fa_deleted_reason ADD CONSTRAINT DF_fa_deleted_reason DEFAULT '';
ALTER TABLE /*_*/filearchive ADD fa_deleted_reason_id bigint unsigned NOT NULL CONSTRAINT DF_fa_deleted_reason_id DEFAULT 0 CONSTRAINT FK_fa_deleted_reason_id FOREIGN KEY REFERENCES /*_*/comment(comment_id);
ALTER TABLE /*_*/filearchive ALTER COLUMN fa_description ADD CONSTRAINT DF_fa_description DEFAULT '';
ALTER TABLE /*_*/filearchive ADD fa_description_id bigint unsigned NOT NULL CONSTRAINT DF_fa_description_id DEFAULT 0 CONSTRAINT FK_fa_description_id FOREIGN KEY REFERENCES /*_*/comment(comment_id);

ALTER TABLE /*_*/recentchanges ADD rc_comment_id bigint unsigned NOT NULL CONSTRAINT DF_rc_comment_id DEFAULT 0 CONSTRAINT FK_rc_comment_id FOREIGN KEY REFERENCES /*_*/comment(comment_id);

ALTER TABLE /*_*/logging ADD log_comment_id bigint unsigned NOT NULL CONSTRAINT DF_log_comment_id DEFAULT 0 CONSTRAINT FK_log_comment_id FOREIGN KEY REFERENCES /*_*/comment(comment_id);

ALTER TABLE /*_*/protected_titles ALTER COLUMN pt_reason ADD CONSTRAINT DF_pt_reason DEFAULT '';
ALTER TABLE /*_*/protected_titles ADD pt_reason_id bigint unsigned NOT NULL CONSTRAINT DF_pt_reason_id DEFAULT 0 CONSTRAINT FK_pt_reason_id FOREIGN KEY REFERENCES /*_*/comment(comment_id);
