-- 
-- patch-curid-covering.sql
-- 
-- Create covering index on cur
-- 

ALTER TABLE /*$wgDBprefix*/cur
   ADD KEY `id_title_ns_red` (`cur_id`,`cur_title`,`cur_namespace`,`cur_is_redirect`);
