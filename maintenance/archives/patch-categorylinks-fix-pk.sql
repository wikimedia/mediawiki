ALTER TABLE /*_*/categorylinks DROP KEY /*i*/cl_from, ADD PRIMARY KEY (cl_from,cl_to);
