--
-- Tracking links to books by ISBN
--
CREATE TABLE /*_*/booklinks(
  -- Foreign key to page.page_id
  bl_page int NOT NULL default '0',

  -- ISBN 13 value it should be string since X is an accetable value
  bl_isbn13 varchar(255) NOT NULL default ''

) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/bl_page on /*_*/booklinks (bl_page,bl_isbn13);
CREATE INDEX /*i*/bl_isbn13 on /*_*/booklinks (bl_isbn13,bl_page);
