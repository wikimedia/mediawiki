CREATE SEQUENCE slot_roles_role_id_seq;
CREATE TABLE slot_roles (
  role_id    SMALLINT  NOT NULL PRIMARY KEY DEFAULT nextval('slot_roles_role_id_seq'),
  role_name  TEXT      NOT NULL
);

CREATE UNIQUE INDEX role_name ON slot_roles (role_name);