-- This file should be phased out.
-- It's useless importing dumps that already have indexes in their definitions.
--

-- SQL to add non-unique indexes to Wikipedia database tables.
-- This is read and executed by the install script; you should
-- never have to run it by itself.
--

ALTER TABLE user
  ADD INDEX user_name (user_name(10));

ALTER TABLE user_newtalk
  ADD INDEX user_id (user_id),
  ADD INDEX user_ip (user_ip);

ALTER TABLE cur
  ADD INDEX cur_namespace (cur_namespace),
  ADD INDEX cur_title (cur_title(20)),
  ADD INDEX cur_timestamp (cur_timestamp),
  ADD INDEX (cur_random),
  ADD INDEX name_title_timestamp (cur_namespace,cur_title,inverse_timestamp),
  ADD INDEX user_timestamp (cur_user,inverse_timestamp),
  ADD INDEX usertext_timestamp (cur_user_text,inverse_timestamp),
  ADD INDEX namespace_redirect_timestamp(cur_namespace,cur_is_redirect,cur_timestamp);

ALTER TABLE old
  ADD INDEX (old_namespace,old_title(20)),
  ADD INDEX old_timestamp (old_timestamp),
  ADD INDEX name_title_timestamp (old_namespace,old_title,inverse_timestamp),
  ADD INDEX user_timestamp (old_user,inverse_timestamp),
  ADD INDEX usertext_timestamp (old_user_text,inverse_timestamp);

ALTER TABLE ipblocks
  ADD INDEX ipb_address (ipb_address),
  ADD INDEX ipb_user (ipb_user);

ALTER TABLE image
  ADD INDEX img_name (img_name(10)),
  ADD INDEX img_size (img_size),
  ADD INDEX img_timestamp (img_timestamp);

ALTER TABLE oldimage
  ADD INDEX oi_name (oi_name(10));

ALTER TABLE searchindex
  ADD FULLTEXT si_title (si_title),
  ADD FULLTEXT si_text (si_text);

ALTER TABLE recentchanges
  ADD INDEX rc_timestamp (rc_timestamp),
  ADD INDEX rc_namespace_title (rc_namespace, rc_title),
  ADD INDEX rc_cur_id (rc_cur_id),
  ADD INDEX new_name_timestamp(rc_new,rc_namespace,rc_timestamp),
  ADD INDEX rc_ip (rc_ip);

ALTER TABLE archive
  ADD KEY `name_title_timestamp` (`ar_namespace`,`ar_title`,`ar_timestamp`);

ALTER TABLE watchlist
  ADD KEY namespace_title (wl_namespace,wl_title);

