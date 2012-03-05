-- Rename the primary unique index from PRIMARY to ls_field_val
-- This is for MySQL only and is necessary only for databases which were updated
-- between MW 1.16 development revisions r50567 and r51465.
ALTER TABLE /*_*/log_search 
	DROP PRIMARY KEY, 
	ADD UNIQUE INDEX ls_field_val (ls_field,ls_value,ls_log_id);

