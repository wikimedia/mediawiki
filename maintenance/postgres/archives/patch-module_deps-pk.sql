DROP INDEX md_module_skin;
ALTER TABLE module_deps
 ADD PRIMARY KEY (md_module, md_skin);
