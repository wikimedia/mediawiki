DROP INDEX cl_from;
ALTER TABLE categorylinks
 ADD PRIMARY KEY (cl_from, cl_to);
