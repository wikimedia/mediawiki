-- $Id$
-- access rights for wiki database users
CREATE USER {$wgDBuser}
	PASSWORD '{$wgDBpassword}';

GRANT SELECT,INSERT,UPDATE,DELETE ON
	archive,brokenlinks,categorylinks,cur,
	cur_cur_id_seq,hitcounter,image,imagelinks,
	interwiki,ipblocks,ipblocks_ipb_id_seq,links,
	linkscc,math,objectcache,"old",old_old_id_seq,
	oldimage,profiling,querycache,recentchanges,
	recentchanges_rc_id_seq,searchindex,site_stats,
	site_stats_ss_row_id_seq,"user",user_newtalk,
	user_rights,user_user_id_seq,validate,watchlist 
TO {$wgDBuser};
