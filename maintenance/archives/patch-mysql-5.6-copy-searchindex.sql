-- Backups table since we will be dopping and recreating it in MySQL 5.6
CREATE TABLE searchindex_old SELECT * FROM searchindex;
