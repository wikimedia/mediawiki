-- More statistics, for version 1.6

ALTER TABLE /*$wgDBprefix*/site_stats ADD ss_images int(10) default '0';
SELECT @images := COUNT(*) FROM /*$wgDBprefix*/image;
UPDATE site_stats SET ss_images=@images;
