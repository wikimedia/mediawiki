CREATE TABLE module_deps (
	md_module 	TEXT NOT NULL,
	md_skin		TEXT NOT NULL,
	md_deps		TEXT NOT NULL
);

CREATE UNIQUE INDEX md_module_skin ON module_deps (md_module, md_skin);
