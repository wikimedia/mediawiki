-- @since 1.29
ALTER TABLE /*$wgDBprefix*/externallinks
	ADD COLUMN el_index_60 varbinary(60) NOT NULL DEFAULT '',
	ADD INDEX /*i*/el_index_60 (el_index_60, el_id),
	ADD INDEX /*i*/el_from_index_60 (el_from, el_index_60, el_id);
