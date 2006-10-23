-- Timestamp of the last time when a new password was
-- sent, for throttling purposes
ALTER TABLE user ADD user_newpass_time char(14) binary;

