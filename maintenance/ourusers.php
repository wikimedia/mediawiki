<?php
/**
 * Wikimedia specific
 *
 * This script generates SQL used to update MySQL users on a hardcoded
 * list of hosts. It takes care of setting the wikiuser for every
 * database as well as setting up wikiadmin.
 *
 * @todo document
 * @file
 * @ingroup Maintenance
 */

/** */
$wikiuser_pass = `wikiuser_pass`;
$wikiadmin_pass = `wikiadmin_pass`;
$nagios_pass = `nagios_sql_pass`;

$hosts = array(
	'localhost',
	'10.0.%',
	'66.230.200.%',
	'208.80.152.%',
);

$databases = array(
	'%wik%',
	'centralauth',
);

print "/*!40100 set old_passwords=1 */;\n";
print "/*!40100 set global old_passwords=1 */;\n";

foreach( $hosts as $host ) {
	print "--\n-- $host\n--\n";
	print "\n-- wikiuser\n\n";
	print "GRANT REPLICATION CLIENT,PROCESS ON *.* TO 'wikiuser'@'$host' IDENTIFIED BY '$wikiuser_pass';\n";
	print "GRANT ALL PRIVILEGES ON `boardvote%`.* TO 'wikiuser'@'$host' IDENTIFIED BY '$wikiuser_pass';\n";
	foreach( $databases as $db ) {
		print "GRANT SELECT, INSERT, UPDATE, DELETE ON `$db`.* TO 'wikiuser'@'$host' IDENTIFIED BY '$wikiuser_pass';\n";
	}

	print "\n-- wikiadmin\n\n";
	print "GRANT PROCESS, REPLICATION CLIENT ON *.* TO 'wikiadmin'@'$host' IDENTIFIED BY '$wikiadmin_pass';\n";
	print "GRANT ALL PRIVILEGES ON `boardvote%`.* TO wikiadmin@'$host' IDENTIFIED BY '$wikiadmin_pass';\n";
	foreach ( $databases as $db ) {
		print "GRANT ALL PRIVILEGES ON `$db`.* TO wikiadmin@'$host' IDENTIFIED BY '$wikiadmin_pass';\n";
	}
	print "\n-- nagios\n\n";
	print "GRANT REPLICATION CLIENT ON *.* TO 'nagios'@'$host' IDENTIFIED BY '$nagios_pass';\n";

	print "\n";
}

