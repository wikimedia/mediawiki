define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.user_properties MODIFY up_property varchar2(255);
