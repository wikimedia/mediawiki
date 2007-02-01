-- Log_id field that means one log entry can be referred to with a single number,
-- rather than a dirty great big mess of features.
-- This might be useful for single-log-entry deletion, et cetera.
-- Andrew Garrett, February 2007.

ALTER TABLE logging
	ADD COLUMN log_id SERIAL,
	ADD PRIMARY KEY log_id (log_id);
