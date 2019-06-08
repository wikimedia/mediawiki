-- These are carefully crafted to work in all five supported databases

CREATE TABLE /*_*/commentstore1 (
  cs1_id integer not null,
  cs1_comment varchar(200),
  cs1_comment_id integer
);

CREATE TABLE /*_*/commentstore2 (
  cs2_id integer not null,
  cs2_comment varchar(200)
);

CREATE TABLE /*_*/commentstore2_temp (
  cs2t_id integer not null,
  cs2t_comment_id integer
);
