DROP INDEX ss_row_id ON site_stats;
ALTER TABLE /*_*/site_stats ADD CONSTRAINT /*i*/ss_row_id PRIMARY KEY (ss_row_id);
