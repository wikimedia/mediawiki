--
-- Kill the old iwl_prefix index, which may be present on some
-- installs if they ran update.php between it being added and being renamed
--

DROP INDEX /*i*/iwl_prefix ON /*_*/iwlinks;

