<?php
/**
 * Wikimedia specific
 *
 * This script generates SQL used to update MySQL users on a hardcoded
 * list of hosts. It takes care of setting the wikiuser for every
 * database as well as setting up wikiadmin.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @todo document
 * @file
 * @ingroup Maintenance
 * @ingroup Wikimedia
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

foreach ( $hosts as $host ) {
	print "--\n-- $host\n--\n";
	print "\n-- wikiuser\n\n";
	print "GRANT REPLICATION CLIENT,PROCESS ON *.* TO 'wikiuser'@'$host' IDENTIFIED BY '$wikiuser_pass';\n";
	print "GRANT ALL PRIVILEGES ON `boardvote%`.* TO 'wikiuser'@'$host' IDENTIFIED BY '$wikiuser_pass';\n";
	foreach ( $databases as $db ) {
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

