DROP INDEX si_type_key;
ALTER TABLE site_identifiers ADD PRIMARY KEY (si_type,si_key);