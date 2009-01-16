-- 
-- patch-pl-tl-il-unique-index.sql
-- 
-- Make reorderings of UNIQUE indices UNIQUE as well

DROP INDEX pl_namespace ON /*_*/pagelinks;
CREATE UNIQUE INDEX pl_namespace ON /*_*/pagelinks (pl_namespace, pl_title, pl_from);
DROP INDEX tl_namespace ON /*_*/templatelinks;
CREATE UNIQUE INDEX tl_namespace ON /*_*/templatelinks (tl_namespace, tl_title, tl_from);
DROP INDEX il_to ON /*_*/imagelinks;
CREATE UNIQUE INDEX il_to ON /*_*/imagelinks (il_to, il_from);
