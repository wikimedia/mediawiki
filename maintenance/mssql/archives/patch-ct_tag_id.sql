ALTER TABLE /*_*/change_tags
    ADD ct_tag_id int NULL REFERENCES /*_*/tag(tag_id);
