<?php

if( php_sapi_name() != 'cli' ) {
	die( 'Must be run from the command line.' );
}

error_reporting( E_ALL );
define( "MEDIAWIKI", true );

require_once( 'PHPUnit.php' );

$testOptions = array(
	'mysql3' => array(
		'server' => null,
		'user' => null,
		'password' => null,
		'database' => null ),
	'mysql4' => array(
		'server' => null,
		'user' => null,
		'password' => null,
		'database' => null ),
	'postgresql' => array(
		'server' => null,
		'user' => null,
		'password' => null,
		'database' => null ),
	);

if( file_exists( 'LocalTestSettings.php' ) ) {
	include( './LocalTestSettings.php' );
}

$tests = array(
	'GlobalTest',
	'DatabaseTest',
	'SearchMySQL3Test',
	'SearchMySQL4Test',
	'ArticleTest',
	);
foreach( $tests as $test ) {
	require_once( $test . '.php' );
	$suite = new PHPUnit_TestSuite( $test );
	$result = PHPUnit::run( $suite );
	echo $result->toString();
}

/**
 * @param string $serverType
 * @param array $tables
 */
function &buildTestDatabase( $serverType, $tables ) {
	global $testOptions, $wgDBprefix;
	$wgDBprefix = 'parsertest';
	$db =& new Database(
		$testOptions[$serverType]['server'],
		$testOptions[$serverType]['user'],
		$testOptions[$serverType]['password'],
		$testOptions[$serverType]['database'] );
	if( $db->isOpen() ) {
		if (!(strcmp($db->getServerVersion(), '4.1') < 0 and stristr($db->getSoftwareLink(), 'MySQL'))) {
			# Database that supports CREATE TABLE ... LIKE
			foreach ($tables as $tbl) {
				$newTableName = $db->tableName( $tbl );
				#$tableName = $this->oldTableNames[$tbl];
				$tableName = $tbl;
				$db->query("CREATE TEMPORARY TABLE $newTableName (LIKE $tableName INCLUDING DEFAULTS)");
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
					die("could not create temporary table $tbl");
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

?>