-- first step of migrating recentchanges rc_type to rc_source
ALTER TABLE /*$wgDBprefix*/recentchanges
  ADD rc_source varbinary(16) NOT NULL default '';

-- Populate rc_source field with the data from rc_type
-- Large wiki's might prefer the PopulateRecentChangeSource maintenance
-- script to batch updates into groups rather than all at once.
UPDATE /*$wgDBprefix*/recentchanges
  SET rc_source = CASE
    WHEN rc_type = 0 THEN 'mw.edit'
    WHEN rc_type = 1 THEN 'mw.new'
    WHEN rc_type = 3 THEN 'mw.log'
    WHEN rc_type = 5 THEN 'mw.external'
    ELSE ''
  END
WHERE rc_source = '';
