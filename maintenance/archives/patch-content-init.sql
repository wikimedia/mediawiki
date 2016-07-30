INSERT INTO content_roles ( role_id, role_name )
VALUES ( 1, 'main' );

INSERT INTO content_models ( model_id, model_name, model_format )
VALUES
  ( 1, 'wikitext', 1 ),
  ( 2, 'javascript', 2 ),
  ( 3, 'css', 3 ),
  ( 4, 'text', 4 ),
  ( 5, 'json', 7 );

INSERT INTO content_formats ( format_id, format_name )
VALUES
  ( 1, 'text/x-wiki' ),
  ( 2, 'text/javascript' ),
  ( 3, 'text/css' ),
  ( 4, 'text/plain' ),
  ( 5, 'text/html' ),
  ( 6, 'application/vnd.php.serialized' ),
  ( 7, 'application/json' ),
  ( 8, 'application/xml' );


-- Mapping namespaces to default content models.
-- Needed during migration only.
CREATE TEMPORARY TABLE /*$wgDBprefix*/namespace_content_models (
  -- do we need a row id here as a primary key?
  ncm_namespace INT NOT NULL,
  ncm_suffix VARCHAR(32) NOT NULL DEFAULT '',
  ncm_model INT NOT NULL -- -> content_models
);

INSERT INTO namespace_content_models( ncm_namespace, ncm_suffix, ncm_model )
VALUES
  ( 0, '', 1 ),
  ( 1, '', 1 ),
  ( 2, '', 1 ),
  ( 3, '', 1 ),
  ( 4, '', 1 ),
  ( 5, '', 1 ),
  ( 6, '', 1 ),
  ( 7, '', 1 ),
  ( 8, '', 1 ),
  ( 9, '', 1 ),
  ( 10, '', 1 ),
  ( 11, '', 1 ),
  ( 12, '', 1 ),
  ( 13, '', 1 ),
  ( 14, '', 1 ),
  ( 15, '', 1 );

-- populate content from revision
-- Note that "null" revisions will get redundant entries in content. This is harmless.
INSERT IGNORE INTO /*$wgDBprefix*/content (
  cnt_revision,
  cnt_role,
  cnt_address,
  cnt_timestamp,
  cnt_blob_length,
  cnt_model,
  cnt_format,
  cnt_hash,
  cnt_logical_size,
  cnt_deleted
)
SELECT
  rev_id,
  1,
  CONCAT( 'tt:', rev_text_id ),
  rev_timestamp, -- or just NULL?
  rev_len, -- fixme: not really, needs to be calculated
  model_id,
  format_id,
  rev_sha1, -- fixme:needs to be calculated if NULL
  rev_len,  -- fixme:needs to be calculated if NULL
  rev_deleted
FROM revision
LEFT JOIN content_models ON ( model_name = rev_content_model ) -- fixme: can't join on null
LEFT JOIN content_formats ON ( format_name = rev_content_format ); -- fixme: can't join on null

-- fix 0 content model. Big join, could be improved by having cnt_page
UPDATE content
JOIN revision ON ( rev_id = cnt_revision )
JOIN page ON ( rev_page = page_id )
JOIN namespace_content_models ON ( ncm_namespace = page_namespace )
SET cnt_model = ncm_model -- todo: consider title suffixes!
WHERE cnt_model = 0;

-- fix 0 content format
UPDATE content
JOIN content_models ON ( model_id = cnt_model )
SET cnt_format = model_format
WHERE cnt_format = 0;

-- populate revision_content based on revision origins
INSERT INTO /*$wgDBprefix*/revision_content (
  revc_revision,
  revc_content
)
SELECT
    cnt_revision,
    cnt_id
FROM content;
