--
-- patch-categorylinks-better-collation.sql
--
--
-- Track category inclusions *used inline*
-- This tracks a single level of category membership
-- (folksonomic tagging, really).
--
CREATE TABLE categorylinks (
  cl_from       BIGINT      NOT NULL  DEFAULT 0,
  -- REFERENCES page(page_id) ON DELETE CASCADE,
  cl_to         VARCHAR(255)         NOT NULL,
  -- cl_sortkey has to be at least 86 wide 
  -- in order to be compatible with the old MySQL schema from MW 1.10
  --cl_sortkey    VARCHAR(86),
  cl_sortkey VARCHAR(230) FOR BIT DATA  NOT NULL ,
  cl_sortkey_prefix VARCHAR(255) FOR BIT DATA  NOT NULL ,
  cl_timestamp  TIMESTAMP(3)  NOT NULL,
  cl_collation VARCHAR(32) FOR BIT DATA  NOT NULL ,
  cl_type VARCHAR(6) FOR BIT DATA  NOT NULL
);
