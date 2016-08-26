-- Make reorderings of UNIQUE indices non-UNIQUE
-- Since 1.24, these indices have been non-UNIQUE in tables.sql.
-- However, an earlier update from 1.15 that made the indices
-- UNIQUE was not removed until 1.28 (T78513).

DROP INDEX /*i*/pl_namespace ON /*_*/pagelinks;
CREATE INDEX /*i*/pl_namespace ON /*_*/pagelinks (pl_namespace, pl_title, pl_from);
DROP INDEX /*i*/tl_namespace ON /*_*/templatelinks;
CREATE INDEX /*i*/tl_namespace ON /*_*/templatelinks (tl_namespace, tl_title, tl_from);
DROP INDEX /*i*/il_to ON /*_*/imagelinks;
CREATE INDEX /*i*/il_to ON /*_*/imagelinks (il_to, il_from);
