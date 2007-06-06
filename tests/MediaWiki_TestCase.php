<?php

abstract class MediaWiki_TestCase extends PHPUnit_Framework_TestCase {
	/**
	 * @param string $serverType
	 * @param array $tables
	 */
	protected function buildTestDatabase( $tables ) {
		global $testOptions, $wgDBprefix, $wgDBserver, $wgDBadminuser, $wgDBadminpassword, $wgDBname;
		$wgDBprefix = 'parsertest';
		$db = new Database(
			$wgDBserver,
			$wgDBadminuser,
			$wgDBadminpassword,
			$wgDBname );
		if( $db->isOpen() ) {
			if (!(strcmp($db->getServerVersion(), '4.1') < 0 and stristr($db->getSoftwareLink(), 'MySQL'))) {
				# Database that supports CREATE TABLE ... LIKE
				foreach ($tables as $tbl) {
					$newTableName = $db->tableName( $tbl );
					#$tableName = $this->oldTableNames[$tbl];
					$tableName = $tbl;
					$db->query("CREATE TEMPORARY TABLE $newTableName (LIKE $tableName)");
				}
			} else {
				# Hack for MySQL versions < 4.1, which don't support
				# "CREATE TABLE ... LIKE". Note that
				# "CREATE TEMPORARY TABLE ... SELECT * FROM ... LIMIT 0"
				# would not create the indexes we need....
				foreach ($tables as $tbl) {
					$res = $db->query("SHOW CREATE TABLE $tbl");
					$row = $db->fetchRow($res);
					$create = $row[1];
					$create_tmp = preg_replace('/CREATE TABLE `(.*?)`/', 'CREATE TEMPORARY TABLE `'
						. $wgDBprefix . '\\1`', $create);
					if ($create === $create_tmp) {
						# Couldn't do replacement
						wfDie( "could not create temporary table $tbl" );
					}
					$db->query($create_tmp);
				}

			}
			return $db;
		} else {
			// Something amiss
			return null;
		}
	}
}

?>