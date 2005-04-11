-- Extra image metadata, added for 1.5

ALTER TABLE /*$wgDBprefix*/image ADD (
  img_width int(5) NOT NULL default '0',
  img_height int(5) NOT NULL default '0',
  img_bits int(5) NOT NULL default '0',
  img_type int(5) NOT NULL default '0'
);

ALTER TABLE /*$wgDBprefix*/oldimage ADD (
  oi_width int(5) NOT NULL default 0,
  oi_height int(5) NOT NULL default 0,
  oi_bits int(3) NOT NULL default 0,
  oi_type int(3) NOT NULL default 0
);


