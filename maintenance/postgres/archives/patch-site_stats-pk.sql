ALTER TABLE site_stats DROP CONSTRAINT site_stats_ss_row_id_key;
ALTER TABLE site_stats ADD PRIMARY KEY (ss_row_id);
ALTER TABLE site_stats ALTER ss_row_id SET DEFAULT 0;
