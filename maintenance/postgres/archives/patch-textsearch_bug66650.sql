UPDATE /*_*/pagecontent SET textvector=to_tsvector(old_text)
WHERE textvector IS NULL AND old_id IN 
(SELECT  max(rev_text_id) FROM revision GROUP BY rev_page);

INSERT INTO /*_*/updatelog(ul_key) VALUES ('patch-textsearch_bug66650.sql');
