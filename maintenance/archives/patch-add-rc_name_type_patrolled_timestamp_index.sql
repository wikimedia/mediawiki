-- @since 1.28
CREATE INDEX /*i*/rc_name_type_patrolled_timestamp ON /*_*/recentchanges (rc_namespace, rc_type, rc_patrolled, rc_timestamp);
