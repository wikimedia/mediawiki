-- 
-- patch-indexes.sql
-- 
-- Fix up table indexes; new to stable release in November 2003
-- 

ALTER TABLE links
   DROP INDEX l_from,
   ADD INDEX l_from (l_from);

ALTER TABLE brokenlinks
   DROP INDEX bl_to,
   ADD INDEX bl_to (bL_to);

ALTER TABLE recentchanges
   ADD INDEX rc_timestamp (rc_timestamp),
   ADD INDEX rc_namespace_title (rc_namespace, rc_title),
   ADD INDEX rc_cur_id (rc_cur_id);

ALTER TABLE archive
   ADD KEY name_title_timestamp (ar_namespace,ar_title,ar_timestamp);

ALTER TABLE watchlist
   ADD KEY namespace_title (wl_namespace,wl_title);
