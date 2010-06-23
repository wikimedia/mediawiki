<?php

/**
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
 * @ingroup Maintenance
 * @see wfWaitForSlaves()
 */

define( 'MW_NO_DB', 1 );
define( 'MW_NO_SESSION', 1 );
define( 'MW_CONFIG_CALLBACK', 'wfInstallerConfig' );

$IP = dirname( dirname( __FILE__ ) );

require_once( "$IP/maintenance/Maintenance.php" );

class CommandLineInstaller extends Maintenance {
	public function __construct() {
		$this->addArg( 'name', 'The name of the wiki', true);
		$this->addArg( 'admin', 'The username of the wiki administrator (WikiSysop)', false);
		$this->addOption( 'pass', 'The password for the wiki administrator.  You will be prompted for this if it isn\'t provided', false, true);
		/* $this->addOption( 'email', 'The email for the wiki administrator', false, true); */
		$this->addOption( 'lang', 'The language to use (en)', false, true );
		/* $this->addOption( 'cont-lang', 'The content language (en)', false, true ); */
		$this->addOption( 'db-type', 'The type of database (mysql)', false, true );
		/* $this->addOption( 'db-host', 'The database host (localhost)', false, true ); */
		/* $this->addOption( 'db-port', 'The database port (3306 for mysql, 5432 for pg)', false, true ); */
		$this->addOption( 'db-name', 'The database name (my_wiki)', false, true );
		$this->addOption( 'db-path', 'The path for the SQLite DB (/var/data)', false, true );
		/* $this->addOption( 'db-schema', 'The schema for the MediaWiki DB in pg (mediawiki)', false, true ); */
		/* $this->addOption( 'db-tsearch2-schema', 'The schema for the tsearch2 DB in pg (public)', false, true ); */
		/* $this->addOption( 'namespace', 'The project namespace (same as the name)', false, true ); */
	}

	public function execute() {
		$installer = new CliInstaller( $this->mArgs[0], $this->mArgs[1], $this->mOptions );

		$installer->execute();
	}
}

$maintClass = "CommandLineInstaller";

require_once( DO_MAINTENANCE );
