ALTER TABLE /*_*/log_search DROP KEY /*i*/ls_field_val, ADD PRIMARY KEY (ls_field,ls_value,ls_log_id);
