-- 
-- Recalculate the article count
-- 

SELECT @foo:=COUNT(*) FROM cur
  WHERE cur_namespace=0 AND cur_is_redirect=0 AND cur_text like '%[[%';
UPDATE site_stats SET ss_good_articles=@foo;

