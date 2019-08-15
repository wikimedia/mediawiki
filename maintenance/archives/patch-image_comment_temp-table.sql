CREATE TABLE /*_*/image_comment_temp (
  imgcomment_name varchar(255) binary NOT NULL,
  imgcomment_description_id bigint unsigned NOT NULL,
  PRIMARY KEY (imgcomment_name, imgcomment_description_id)
) /*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/imgcomment_name ON /*_*/image_comment_temp (imgcomment_name);
