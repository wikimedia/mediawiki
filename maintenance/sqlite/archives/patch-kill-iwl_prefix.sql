--
-- Kill the old iwl_prefix index, which may be present on some
-- installs if they ran update.php between it being added and being renamed
--

DROP INDEX IF EXISTS /*i*/iwl_prefix;

