ALTER TABLE /*_*/recentchanges
	ADD COLUMN rc_cur_id_assoc int UNSIGNED NULL AFTER rc_cur_id;
