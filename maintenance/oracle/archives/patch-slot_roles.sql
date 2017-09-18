CREATE SEQUENCE slot_roles_role_id_seq;
CREATE TABLE &mw_prefix.slot_roles (
  role_id NUMBER NOT NULL,
  role_name VARCHAR2(64) NOT NULL
);

ALTER TABLE &mw_prefix.slot_roles ADD CONSTRAINT &mw_prefix.slot_roles_pk PRIMARY KEY (role_id);

CREATE UNIQUE INDEX &mw_prefix.role_name_u01 ON &mw_prefix.slot_roles (role_name);

/*$mw$*/
CREATE TRIGGER &mw_prefix.slot_roles_seq_trg BEFORE INSERT ON &mw_prefix.slot_roles
	FOR EACH ROW WHEN (new.role_id IS NULL)
BEGIN
	&mw_prefix.lastval_pkg.setLastval(slot_roles_role_id_seq.nextval, :new.role_id);
END;
/*$mw$*/