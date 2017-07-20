ALTER TABLE /*$wgDBprefix*/image
	DROP CONSTRAINT img_media_type_ckc;

ALTER TABLE /*$wgDBprefix*/image
	ADD CONSTRAINT img_media_type_ckc
	CHECK (img_media_type IN("UNKNOWN", "BITMAP", "DRAWING", "AUDIO", "VIDEO", "MULTIMEDIA", "OFFICE", "TEXT", "EXECUTABLE", "ARCHIVE", "3D"));

ALTER TABLE /*$wgDBprefix*/oldimage
	DROP CONSTRAINT oi_media_type_ckc;

ALTER TABLE /*$wgDBprefix*/oldimage
	ADD CONSTRAINT oi_media_type_ckc
	CHECK (oi_media_type IN("UNKNOWN", "BITMAP", "DRAWING", "AUDIO", "VIDEO", "MULTIMEDIA", "OFFICE", "TEXT", "EXECUTABLE", "ARCHIVE", "3D"));

ALTER TABLE /*$wgDBprefix*/filearchive
	DROP CONSTRAINT fa_media_type_ckc;

ALTER TABLE /*$wgDBprefix*/filearchive
	ADD CONSTRAINT fa_media_type_ckc
	CHECK (fa_media_type IN("UNKNOWN", "BITMAP", "DRAWING", "AUDIO", "VIDEO", "MULTIMEDIA", "OFFICE", "TEXT", "EXECUTABLE", "ARCHIVE", "3D"));

ALTER TABLE /*$wgDBprefix*/uploadstash
	DROP CONSTRAINT us_media_type_ckc;

ALTER TABLE /*$wgDBprefix*/uploadstash
	ADD CONSTRAINT us_media_type_ckc
	CHECK (us_media_type IN("UNKNOWN", "BITMAP", "DRAWING", "AUDIO", "VIDEO", "MULTIMEDIA", "OFFICE", "TEXT", "EXECUTABLE", "ARCHIVE", "3D"));
