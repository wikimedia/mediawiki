-- SQL to load database with initial values for testing.
-- Most will be overwritten by install script.
--

INSERT INTO user (user_name,user_rights,user_password)
  VALUES ('WikiSysop','sysop','d41d8cd98f00b204e9800998ecf8427e'),
  ('WikiDeveloper','sysop,developer','d41d8cd98f00b204e9800998ecf8427e');

INSERT INTO cur (cur_namespace,cur_title,cur_text,cur_restrictions)
  VALUES (4,'Upload_log','Below is a list of the most recent file uploads.\nAll times shown are server time (UTC).\n<ul>\n</ul>\n','sysop'),
  (4,'Deletion_log','Below is a list of the most recent deletions.\nAll times shown are server time (UTC).\n<ul>\n</ul>\n','sysop'),
  (0,'Main_Page','Wiki software successfully installed!',''),
  (4,'Block log', 'This is a log of user blocking and unblocking actions. Automatically 
blocked IP addresses are not be listed. See the [[Special:Ipblocklist|IP block list]] for
the list of currently operational bans and blocks.', 'sysop');

INSERT INTO site_stats VALUES (1,0,0,0);

