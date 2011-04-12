CREATE TABLE user_properties (
  -- Foreign key to user.user_id
  up_user BIGINT NOT NULL,
  
  -- Name of the option being saved. This is indexed for bulk lookup.
  up_property VARCHAR(32) FOR BIT DATA NOT NULL,
  
  -- Property value as a string.
  up_value CLOB(64K) INLINE LENGTH 4096
);
