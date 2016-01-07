<?php
/**
 * Import pages from text files
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
 * @file
 * @ingroup Maintenance
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script which reads in text files
 * and imports their content to a page of the wiki.
 *
 * @ingroup Maintenance
 */
class ImportTextFiles extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Reads in text files and imports their content to a page of the wiki";
		$this->addOption( 'user', 'Username to which edits should be attributed. ' +
			'Default: "Maintenance script"', false, true, 'u' );
		$this->addOption( 'summary', 'Specify edit summary for the edits', false, true, 's' );
		$this->addOption( 'use-timestamp', 'Use the modification date of the text file ' +
			'as the timestamp for the edit' );
		$this->addArg( 'titles', 'Titles of article to edit' );
	}

	public function execute() {
		$userName = $this->getOption( 'user', false );
		$summary = $this->getOption( 'summary', '' );
		$useTimestamp = $this->hasOption( 'use-timestamp' );

		// Get all the arguments. A loop is required since Maintenance doesn't
		// suppport an arbitrary number of arguments.
		$files = array();
		$i = 0;
		while ( $arg = $this->getArg( $i++ ) ) {
			$files[$arg] = file_get_contents( $arg );
		};

		$count = count( $files );
		$this->output( "Creating {$count} pages...\n" );

		if ( $userName === false ) {
			$wgUser = User::newSystemUser( 'Maintenance script', array( 'steal' => true ) );
		} else {
			$wgUser = User::newFromName( $userName );
		}

		if ( !$wgUser ) {
			$this->error( "Invalid username\n", true );
		}
		if ( $wgUser->isAnon() ) {
			$wgUser->addToDatabase();
		}

		$exit = 0;

		foreach ( $files as $file => $text ) {
			$pageName = pathinfo( $file, PATHINFO_FILENAME );
			$title = Title::newFromText( $pageName );
			if ( !$title ) {
				$this->error( "Invalid title '{$pageName}'. Skipping.\n", true );

			}

			if ( $title->exists() ) {
				$this->output( "Title '{$pageName}' already exists. Skipping.\n" );
				continue;
			}

			$rev = new WikiRevision( ConfigFactory::getDefaultInstance()->makeConfig( 'main' ) );
			$rev->setText( $text );
			$rev->setTitle( $title );
			$rev->setUserObj( $wgUser );
			if ( $useTimestamp ) {
				$rev->setTimestamp( wfTimestamp( TS_UNIX, filemtime( $file ) ) );
			} else {
				$rev->setTimestamp( wfTimestampNow() );
			}

			$status = $rev->importOldRevision();
			if ( $status ) {
				$this->output( "Successfully created '{$pageName}.' \n" );
			} else {
				$this->output( "Failed to create '{$pageName}.\n" );
				$exit = 1;
			}
		}
		exit( $exit );
	}
}

$maintClass = "ImportTextFiles";
require_once RUN_MAINTENANCE_IF_MAIN;
