CREATE TABLE image_comment_temp (
	imgcomment_name       TEXT NOT NULL,
	imgcomment_description_id INTEGER NOT NULL,
	PRIMARY KEY (imgcomment_name, imgcomment_description_id)
);
CREATE UNIQUE INDEX imgcomment_name ON image_comment_temp (imgcomment_name);
