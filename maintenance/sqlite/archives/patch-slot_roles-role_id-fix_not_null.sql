CREATE TABLE /*_*/slot_roles_tmp (
  role_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  role_name BLOB NOT NULL
);

INSERT INTO /*_*/slot_roles_tmp
	SELECT role_id, role_name
		FROM /*_*/slot_roles;
DROP TABLE /*_*/slot_roles;
ALTER TABLE /*_*/slot_roles_tmp RENAME TO /*_*/slot_roles;
CREATE UNIQUE INDEX role_name ON /*_*/slot_roles (role_name);
