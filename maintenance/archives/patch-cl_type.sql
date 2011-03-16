--
-- Change cl_type to a varchar from an enum because of the weird semantics of
-- the < and > operators when working with enums
--

ALTER TABLE /*_*/categorylinks MODIFY cl_type varchar(6) NOT NULL default 'page';
