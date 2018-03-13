--
-- patch-comment-table.sql
--
-- T166732. Add a `comment` table and various columns (and temporary tables) to reference it.

CREATE TABLE /*_*/comment (
  comment_id bigint NOT NULL PRIMARY KEY IDENTITY(0,1),
  comment_hash INT NOT NULL,
  comment_text nvarchar(max) NOT NULL,
  comment_data nvarchar(max)
);
CREATE INDEX /*i*/comment_hash ON /*_*/comment (comment_hash);

-- dummy row for FKs. Hash is intentionally wrong so CommentStore won't match it.
INSERT INTO /*_*/comment (comment_hash, comment_text) VALUES (-1, '** dummy **');


CREATE TABLE /*_*/revision_comment_temp (
  revcomment_rev INT NOT NULL CONSTRAINT FK_revcomment_rev FOREIGN KEY REFERENCES /*_*/revision(rev_id) ON DELETE CASCADE,
  revcomment_comment_id bigint NOT NULL CONSTRAINT FK_revcomment_comment_id FOREIGN KEY REFERENCES /*_*/comment(comment_id),
  CONSTRAINT PK_revision_comment_temp PRIMARY KEY (revcomment_rev, revcomment_comment_id)
);
CREATE UNIQUE INDEX /*i*/revcomment_rev ON /*_*/revision_comment_temp (revcomment_rev);


CREATE TABLE /*_*/image_comment_temp (
  imgcomment_name nvarchar(255) NOT NULL CONSTRAINT FK_imgcomment_name FOREIGN KEY REFERENCES /*_*/image(img_name) ON DELETE CASCADE,
  imgcomment_description_id bigint NOT NULL CONSTRAINT FK_imgcomment_description_id FOREIGN KEY REFERENCES /*_*/comment(comment_id),
  CONSTRAINT PK_image_comment_temp PRIMARY KEY (imgcomment_name, imgcomment_description_id)
);
CREATE UNIQUE INDEX /*i*/imgcomment_name ON /*_*/image_comment_temp (imgcomment_name);


ALTER TABLE /*_*/revision ADD CONSTRAINT DF_rev_comment DEFAULT '' FOR rev_comment;

ALTER TABLE /*_*/archive ADD CONSTRAINT DF_ar_comment DEFAULT '' FOR ar_comment;
ALTER TABLE /*_*/archive ADD ar_comment_id bigint NOT NULL CONSTRAINT DF_ar_comment_id DEFAULT 0 CONSTRAINT FK_ar_comment_id FOREIGN KEY REFERENCES /*_*/comment(comment_id);

ALTER TABLE /*_*/ipblocks ADD CONSTRAINT DF_ipb_reason DEFAULT '' FOR ipb_reason;
ALTER TABLE /*_*/ipblocks ADD ipb_reason_id bigint NOT NULL CONSTRAINT DF_ipb_reason_id DEFAULT 0 CONSTRAINT FK_ipb_reason_id FOREIGN KEY REFERENCES /*_*/comment(comment_id);

ALTER TABLE /*_*/image ADD CONSTRAINT DF_img_description DEFAULT '' FOR img_description;

ALTER TABLE /*_*/oldimage ADD CONSTRAINT DF_oi_description DEFAULT '' FOR oi_description;
ALTER TABLE /*_*/oldimage ADD oi_description_id bigint NOT NULL CONSTRAINT DF_oi_description_id DEFAULT 0 CONSTRAINT FK_oi_description_id FOREIGN KEY REFERENCES /*_*/comment(comment_id);

ALTER TABLE /*_*/filearchive ADD CONSTRAINT DF_fa_deleted_reason DEFAULT '' FOR fa_deleted_reason;
ALTER TABLE /*_*/filearchive ADD fa_deleted_reason_id bigint NOT NULL CONSTRAINT DF_fa_deleted_reason_id DEFAULT 0 CONSTRAINT FK_fa_deleted_reason_id FOREIGN KEY REFERENCES /*_*/comment(comment_id);
ALTER TABLE /*_*/filearchive ADD CONSTRAINT DF_fa_description DEFAULT '' FOR fa_description;
ALTER TABLE /*_*/filearchive ADD fa_description_id bigint NOT NULL CONSTRAINT DF_fa_description_id DEFAULT 0 CONSTRAINT FK_fa_description_id FOREIGN KEY REFERENCES /*_*/comment(comment_id);

ALTER TABLE /*_*/recentchanges ADD rc_comment_id bigint NOT NULL CONSTRAINT DF_rc_comment_id DEFAULT 0 CONSTRAINT FK_rc_comment_id FOREIGN KEY REFERENCES /*_*/comment(comment_id);

ALTER TABLE /*_*/logging ADD log_comment_id bigint NOT NULL CONSTRAINT DF_log_comment_id DEFAULT 0 CONSTRAINT FK_log_comment_id FOREIGN KEY REFERENCES /*_*/comment(comment_id);

ALTER TABLE /*_*/protected_titles ADD CONSTRAINT DF_pt_reason DEFAULT '' FOR pt_reason;
ALTER TABLE /*_*/protected_titles ADD pt_reason_id bigint NOT NULL CONSTRAINT DF_pt_reason_id DEFAULT 0 CONSTRAINT FK_pt_reason_id FOREIGN KEY REFERENCES /*_*/comment(comment_id);
