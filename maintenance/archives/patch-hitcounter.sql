--
-- hitcounter table is used to buffer page hits before they are periodically 
-- counted and added to the cur_counter column in the cur table.
-- December 2003
--

CREATE TABLE hitcounter (
  hc_id INTEGER UNSIGNED NOT NULL
) TYPE=HEAP MAX_ROWS=25000;
