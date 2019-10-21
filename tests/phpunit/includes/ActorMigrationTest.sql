-- These are carefully crafted to work in all five supported databases

CREATE TABLE /*_*/actormigration1 (
  am1_id integer not null,
  am1_user integer,
  am1_user_text varchar(200),
  am1_actor integer
);

CREATE TABLE /*_*/actormigration2 (
  am2_id integer not null,
  am2_user integer,
  am2_user_text varchar(200)
);

CREATE TABLE /*_*/actormigration2_temp (
  am2t_id integer not null,
  am2t_actor integer
);

CREATE TABLE /*_*/actormigration3 (
  am3_id integer not null,
  am3_xxx integer,
  am3_xxx_text varchar(200),
  am3_xxx_actor integer
);
