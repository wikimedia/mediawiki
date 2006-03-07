<?php
/**
 * @todo document
 * @package MediaWiki
 * @subpackage Maintenance
 */

/** */
$wikiuser_pass = `wikiuser_pass`;
$wikiadmin_pass = `wikiadmin_pass`;
$wikisql_pass = `wikisql_pass`;

if ( @$argv[1] == 'yaseo' ) {
	$hosts = array(
		'localhost',
		'211.115.107.158',
		'211.115.107.159',
		'211.115.107.160',
		'211.115.107.138',
		'211.115.107.139',
		'211.115.107.140',
		'211.115.107.141',
		'211.115.107.142',
		'211.115.107.143',
		'211.115.107.144',
		'211.115.107.145',
		'211.115.107.146',
		'211.115.107.147',
		'211.115.107.148',
		'211.115.107.149',
		'211.115.107.150',
		'211.115.107.152',
		'211.115.107.153',
		'211.115.107.154',
		'211.115.107.155',
		'211.115.107.156',
		'211.115.107.157',
	);
} else {
	$hosts = array(
		'localhost',
		'207.142.131.194',
		'207.142.131.195',
		'207.142.131.196',
		'207.142.131.197',
		'207.142.131.198',
		'207.142.131.199',
		'207.142.131.221',
		'207.142.131.226',
		'207.142.131.227',
		'207.142.131.228',
		'207.142.131.229',
		'207.142.131.230',
		'207.142.131.231',
		'207.142.131.232',
		'207.142.131.233',
		'207.142.131.234',
		'207.142.131.237',
		'207.142.131.238',
		'207.142.131.239',
		'207.142.131.243',
		'207.142.131.244',
		'207.142.131.249',
		'207.142.131.250',
		'207.142.131.216',
		'10.0.%',
	);
}

$databases = array(
	'%wikibooks',
	'%wiki',
	'%wikiquote',
	'%wiktionary',
	'%wikisource',
	'%wikinews',
	'%wikiversity',
	'%wikimedia',
);

foreach( $hosts as $host ) {
	print "--\n-- $host\n--\n\n-- wikiuser\n\n";
	print "GRANT REPLICATION CLIENT,PROCESS ON *.* TO 'wikiuser'@'$host' IDENTIFIED BY '$wikiuser_pass';\n";
	print "GRANT ALL PRIVILEGES ON `boardvote`.* TO 'wikiuser'@'$host' IDENTIFIED BY '$wikiuser_pass';\n";
	print "GRANT ALL PRIVILEGES ON `boardvote2005`.* TO 'wikiuser'@'$host' IDENTIFIED BY '$wikiuser_pass';\n";
	foreach( $databases as $db ) {
		print "GRANT SELECT, INSERT, UPDATE, DELETE ON `$db`.* TO 'wikiuser'@'$host' IDENTIFIED BY '$wikiuser_pass';\n";
	}

/*
	print "\n-- wikisql\n\n";
	foreach ( $databases as $db ) {
print <<<EOS
GRANT SELECT ON `$db`.`old` TO 'wikisql'@'$host' IDENTIFIED BY '$wikisql_pass';
GRANT SELECT ON `$db`.`imagelinks` TO 'wikisql'@'$host' IDENTIFIED BY '$wikisql_pass';
GRANT SELECT ON `$db`.`image` TO 'wikisql'@'$host' IDENTIFIED BY '$wikisql_pass';
GRANT SELECT ON `$db`.`watchlist` TO 'wikisql'@'$host' IDENTIFIED BY '$wikisql_pass';
GRANT SELECT ON `$db`.`site_stats` TO 'wikisql'@'$host' IDENTIFIED BY '$wikisql_pass';
GRANT SELECT ON `$db`.`archive` TO 'wikisql'@'$host' IDENTIFIED BY '$wikisql_pass';
GRANT SELECT ON `$db`.`links` TO 'wikisql'@'$host' IDENTIFIED BY '$wikisql_pass';
GRANT SELECT ON `$db`.`ipblocks` TO 'wikisql'@'$host' IDENTIFIED BY '$wikisql_pass';
GRANT SELECT ON `$db`.`cur` TO 'wikisql'@'$host' IDENTIFIED BY '$wikisql_pass';
GRANT SELECT (user_rights, user_id, user_name, user_options) ON `$db`.`user` TO 'wikisql'@'$host' IDENTIFIED BY '$wikisql_pass';
GRANT SELECT ON `$db`.`oldimage` TO 'wikisql'@'$host' IDENTIFIED BY '$wikisql_pass';
GRANT SELECT ON `$db`.`recentchanges` TO 'wikisql'@'$host' IDENTIFIED BY '$wikisql_pass';
GRANT SELECT ON `$db`.`math` TO 'wikisql'@'$host' IDENTIFIED BY '$wikisql_pass';
GRANT SELECT ON `$db`.`brokenlinks` TO 'wikisql'@'$host' IDENTIFIED BY '$wikisql_pass';

EOS;
	}*/

	print "\n-- wikiadmin\n\n";
	print "GRANT PROCESS, REPLICATION CLIENT ON *.* TO 'wikiadmin'@'$host' IDENTIFIED BY '$wikiadmin_pass';\n";
	print "GRANT ALL PRIVILEGES ON `boardvote`.* TO wikiadmin@'$host' IDENTIFIED BY '$wikiadmin_pass';\n";
	print "GRANT ALL PRIVILEGES ON `boardvote2005`.* TO wikiadmin@'$host' IDENTIFIED BY '$wikiadmin_pass';\n";
	foreach ( $databases as $db ) {
		print "GRANT ALL PRIVILEGES ON `$db`.* TO wikiadmin@'$host' IDENTIFIED BY '$wikiadmin_pass';\n";
	}
	print "\n";
}
?>
