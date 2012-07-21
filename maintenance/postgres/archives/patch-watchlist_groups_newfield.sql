ALTER TABLE watchlist ADD COLUMN wl_group INTEGER NOT NULL DEFAULT 0;

DROP INDEX wl_group_user_namespace_title;
CREATE UNIQUE INDEX wl_group_user_namespace_title ON watchlist (wl_user, wl_group, wl_namespace, wl_title);