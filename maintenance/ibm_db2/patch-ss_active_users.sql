CREATE TABLE site_stats (
  ss_row_id         BIGINT	  NOT NULL  UNIQUE,
  ss_total_views    BIGINT            DEFAULT 0,
  ss_total_edits    BIGINT            DEFAULT 0,
  ss_good_articles  BIGINT             DEFAULT 0,
  ss_total_pages    INTEGER            DEFAULT -1,
  ss_users          INTEGER            DEFAULT -1,
  ss_active_users   INTEGER            DEFAULT -1,
  ss_admins         INTEGER            DEFAULT -1,
  ss_images         INTEGER            DEFAULT 0
);
