DROP  INDEX IF EXISTS tl_namespace;
DROP  INDEX IF EXISTS tl_backlinks_namespace;
ALTER TABLE  templatelinks
DROP  tl_namespace;
ALTER TABLE  templatelinks
DROP  tl_title;
