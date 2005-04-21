-- Extra image exif metadata, added for 1.5

ALTER TABLE /*$wgDBprefix*/image ADD (
  img_exif mediumblob NOT NULL
);
