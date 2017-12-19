define mw_prefix='{$wgDBprefix}';

-- Package to help with making Oracle more like other DBs with respect to
-- auto-incrementing columns.
/*$mw$*/
CREATE PACKAGE &mw_prefix.lastval_pkg IS
  lastval NUMBER;
  PROCEDURE setLastval(val IN NUMBER, field OUT NUMBER);
  FUNCTION getLastval RETURN NUMBER;
END;
/*$mw$*/

/*$mw$*/
CREATE PACKAGE BODY &mw_prefix.lastval_pkg IS
  PROCEDURE setLastval(val IN NUMBER, field OUT NUMBER) IS BEGIN
    lastval := val;
    field := val;
  END;

  FUNCTION getLastval RETURN NUMBER IS BEGIN
    RETURN lastval;
  END;
END;
/*$mw$*/

/*$mw$*/
CREATE TRIGGER &mw_prefix.mwuser_seq_trg BEFORE INSERT ON &mw_prefix.mwuser
	FOR EACH ROW WHEN (new.user_id IS NULL)
BEGIN
	&mw_prefix.lastval_pkg.setLastval(user_user_id_seq.nextval, :new.user_id);
END;
/*$mw$*/

/*$mw$*/
CREATE TRIGGER &mw_prefix.page_seq_trg BEFORE INSERT ON &mw_prefix.page
	FOR EACH ROW WHEN (new.page_id IS NULL)
BEGIN
	&mw_prefix.lastval_pkg.setLastval(page_page_id_seq.nextval, :new.page_id);
END;
/*$mw$*/

/*$mw$*/
CREATE TRIGGER &mw_prefix.revision_seq_trg BEFORE INSERT ON &mw_prefix.revision
	FOR EACH ROW WHEN (new.rev_id IS NULL)
BEGIN
	&mw_prefix.lastval_pkg.setLastval(revision_rev_id_seq.nextval, :new.rev_id);
END;
/*$mw$*/

/*$mw$*/
CREATE TRIGGER &mw_prefix.pagecontent_seq_trg BEFORE INSERT ON &mw_prefix.pagecontent
	FOR EACH ROW WHEN (new.old_id IS NULL)
BEGIN
	&mw_prefix.lastval_pkg.setLastval(text_old_id_seq.nextval, :new.old_id);
END;
/*$mw$*/

/*$mw$*/
CREATE TRIGGER &mw_prefix.archive_seq_trg BEFORE INSERT ON &mw_prefix.archive
	FOR EACH ROW WHEN (new.ar_id IS NULL)
BEGIN
	&mw_prefix.lastval_pkg.setLastval(archive_ar_id_seq.nextval, :new.ar_id);
END;
/*$mw$*/

/*$mw$*/
CREATE TRIGGER &mw_prefix.category_seq_trg BEFORE INSERT ON &mw_prefix.category
	FOR EACH ROW WHEN (new.cat_id IS NULL)
BEGIN
	&mw_prefix.lastval_pkg.setLastval(category_cat_id_seq.nextval, :new.cat_id);
END;
/*$mw$*/

/*$mw$*/
CREATE TRIGGER &mw_prefix.externallinks_seq_trg BEFORE INSERT ON &mw_prefix.externallinks
	FOR EACH ROW WHEN (new.el_id IS NULL)
BEGIN
	&mw_prefix.lastval_pkg.setLastval(externallinks_el_id_seq.nextval, :new.el_id);
END;
/*$mw$*/

/*$mw$*/
CREATE TRIGGER &mw_prefix.ipblocks_seq_trg BEFORE INSERT ON &mw_prefix.ipblocks
	FOR EACH ROW WHEN (new.ipb_id IS NULL)
BEGIN
	&mw_prefix.lastval_pkg.setLastval(ipblocks_ipb_id_seq.nextval, :new.ipb_id);
END;
/*$mw$*/

/*$mw$*/
CREATE TRIGGER &mw_prefix.filearchive_seq_trg BEFORE INSERT ON &mw_prefix.filearchive
	FOR EACH ROW WHEN (new.fa_id IS NULL)
BEGIN
	&mw_prefix.lastval_pkg.setLastval(filearchive_fa_id_seq.nextval, :new.fa_id);
END;
/*$mw$*/

/*$mw$*/
CREATE TRIGGER &mw_prefix.uploadstash_seq_trg BEFORE INSERT ON &mw_prefix.uploadstash
	FOR EACH ROW WHEN (new.us_id IS NULL)
BEGIN
	&mw_prefix.lastval_pkg.setLastval(uploadstash_us_id_seq.nextval, :new.us_id);
END;
/*$mw$*/

/*$mw$*/
CREATE TRIGGER &mw_prefix.recentchanges_seq_trg BEFORE INSERT ON &mw_prefix.recentchanges
	FOR EACH ROW WHEN (new.rc_id IS NULL)
BEGIN
	&mw_prefix.lastval_pkg.setLastval(recentchanges_rc_id_seq.nextval, :new.rc_id);
END;
/*$mw$*/

/*$mw$*/
CREATE TRIGGER &mw_prefix.logging_seq_trg BEFORE INSERT ON &mw_prefix.logging
	FOR EACH ROW WHEN (new.log_id IS NULL)
BEGIN
	&mw_prefix.lastval_pkg.setLastval(logging_log_id_seq.nextval, :new.log_id);
END;
/*$mw$*/

/*$mw$*/
CREATE TRIGGER &mw_prefix.job_seq_trg BEFORE INSERT ON &mw_prefix.job
	FOR EACH ROW WHEN (new.job_id IS NULL)
BEGIN
	&mw_prefix.lastval_pkg.setLastval(job_job_id_seq.nextval, :new.job_id);
END;
/*$mw$*/

/*$mw$*/
CREATE TRIGGER &mw_prefix.page_restrictions_seq_trg BEFORE INSERT ON &mw_prefix.page_restrictions
	FOR EACH ROW WHEN (new.pr_id IS NULL)
BEGIN
	&mw_prefix.lastval_pkg.setLastval(page_restrictions_pr_id_seq.nextval, :new.pr_id);
END;
/*$mw$*/

/*$mw$*/
CREATE TRIGGER &mw_prefix.sites_seq_trg BEFORE INSERT ON &mw_prefix.sites
	FOR EACH ROW WHEN (new.site_id IS NULL)
BEGIN
	&mw_prefix.lastval_pkg.setLastval(sites_site_id_seq.nextval, :new.site_id);
END;
/*$mw$*/
