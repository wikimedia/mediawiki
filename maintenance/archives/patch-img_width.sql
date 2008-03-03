-- Extra image metadata, added for 1.5

-- NOTE: as by patch-img_media_type.sql, the img_type
-- column is no longer used and has therefore be removed from this patch

ALTER TABLE /*$wgDBprefix*/image ADD (
  img_width int(5) NOT NULL default 0,
  img_height int(5) NOT NULL default 0,
  img_bits int(5) NOT NULL default 0
);

ALTER TABLE /*$wgDBprefix*/oldimage ADD (
  oi_width int(5) NOT NULL default 0,
  oi_height int(5) NOT NULL default 0,
  oi_bits int(3) NOT NULL default 0
);


