-- Make the image name index unique

ALTER TABLE image DROP INDEX img_name;

ALTER TABLE image
  ADD PRIMARY KEY img_name (img_name);
