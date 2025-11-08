-- These are carefully crafted to work in all three supported databases

CREATE TABLE /*_*/actormigration1 (
  am1_id integer not null,
  am1_user integer,
  am1_user_text varchar(200),
  am1_actor integer
);

CREATE TABLE /*_*/actormigration2 (
  am2_id integer not null,
  am2_xxx integer,
  am2_xxx_text varchar(200),
  am2_xxx_actor integer
);
