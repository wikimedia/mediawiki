-- Make the image name index unique

ALTER TABLE image DROP INDEX img_name;

ALTER TABLE image
  ADD UNIQUE INDEX img_name (img_name);
