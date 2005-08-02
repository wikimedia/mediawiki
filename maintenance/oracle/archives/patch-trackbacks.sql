CREATE SEQUENCE trackbacks_id_seq;
CREATE TABLE trackbacks (
        tb_id           NUMBER PRIMARY KEY,
        tb_page         NUMBER(8) REFERENCES page(page_id) ON DELETE CASCADE,
        tb_title        VARCHAR(255) NOT NULL,
        tb_url          VARCHAR(255) NOT NULL,
        tb_ex           CLOB,
        tb_name         VARCHAR(255)
);
CREATE INDEX tb_name_page_idx ON trackbacks(tb_page);
