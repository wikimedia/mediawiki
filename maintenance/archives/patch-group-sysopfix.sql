-- Fix to alpha versions that had incorrect rights assignments
-- breaking protected page edits by sysops.
-- 2004-10-27

UPDATE /*$wgDBprefix*/`group`
   SET group_rights=CONCAT(group_rights,',sysop')
 WHERE group_name IN('Sysops','Bureaucrat');

-- Add 'grouprights' right as for bureaucrats
-- 2005-01-17
UPDATE /*$wgDBprefix*/`group`
   SET group_rights=CONCAT(group_rights,',grouprights')
 WHERE group_name IN('Bureaucrat');
