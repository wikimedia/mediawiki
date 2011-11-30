-- Adding us_chunk_inx field
ALTER TABLE /*$wgDBprefix*/uploadstash
  ADD us_chunk_inx int unsigned NULL;
