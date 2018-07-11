DROP INDEX protected_titles_unique;
ALTER TABLE protected_titles ADD PRIMARY KEY (pt_namespace, pt_title);