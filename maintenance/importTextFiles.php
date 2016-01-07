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
		$this->mDescription = "Reads in text files and imports their content to pages of the wiki";
		$this->addOption( 'user', 'Username to which edits should be attributed. ' .
			'Default: "Maintenance script"', false, true, 'u' );
		$this->addOption( 'summary', 'Specify edit summary for the edits', false, true, 's' );
		$this->addOption( 'use-timestamp', 'Use the modification date of the text file ' .
			'as the timestamp for the edit' );
		$this->addArg( 'titles', 'Titles of article to edit' );
	}

	public function execute() {
		$userName = $this->getOption( 'user', false );
		$summary = $this->getOption( 'summary', 'Imported from text file' );
		$useTimestamp = $this->hasOption( 'use-timestamp' );

		// Get all the arguments. A loop is required since Maintenance doesn't
		// suppport an arbitrary number of arguments.
		$files = array();
		$i = 0;
		while ( $arg = $this->getArg( $i++ ) ) {
			if ( file_exists( $arg ) ) {
				$files[$arg] = file_get_contents( $arg );
			} else {
				$this->error( "Fatal error: The file '$arg' does not exist!", 1 );
			}
		};

		$count = count( $files );
		$this->output( "Creating $count pages...\n" );

		if ( $userName === false ) {
			$user = User::newSystemUser( 'Maintenance script', array( 'steal' => true ) );
		} else {
			$user = User::newFromName( $userName );
		}

		if ( !$user ) {
			$this->error( "Invalid username\n", true );
		}
		if ( $user->isAnon() ) {
			$user->addToDatabase();
		}

		$exit = 0;

		$successCount = 0;
		$failCount = 0;
		$skipCount = 0;

		foreach ( $files as $file => $text ) {
			$pageName = pathinfo( $file, PATHINFO_FILENAME );
			$title = Title::newFromText( $pageName );
			if ( !$title ) {
				$this->error( "Invalid title $pageName. Skipping.\n" );
				$skipCount++;
				continue;
			}

			if ( $title->exists() ) {
				$actualTitle = $title->getPrefixedText();
				$this->output( "Title $pageName already exists. Skipping.\n" );
				$skipCount++;
				continue;
			}

			$actualTitle = $title->getPrefixedText();

			$rev = new WikiRevision( ConfigFactory::getDefaultInstance()->makeConfig( 'main' ) );
			$rev->setText( $text );
			$rev->setTitle( $title );
			$rev->setUserObj( $user );
			$rev->setComment( $summary );
			if ( $useTimestamp ) {
				$rev->setTimestamp( wfTimestamp( TS_UNIX, filemtime( $file ) ) );
			} else {
				$rev->setTimestamp( wfTimestampNow() );
			}

			$status = $rev->importOldRevision();
			if ( $status ) {
				$this->output( "Successfully created $actualTitle\n" );
				$successCount++;
			} else {
				$actualTitle = $title->getPrefixedText();
				$this->output( "Failed to create $actualTitle\n" );
				$failCount++;
				$exit = 1;
			}
		}
		$this->output( "Done! $successCount successfully created, $skipCount skipped.\n" );
		if ( $exit ) {
			$this->error( "Import failed with $failCount failed pages.\n", $exit );
		}
	}
}

$maintClass = "ImportTextFiles";
require_once RUN_MAINTENANCE_IF_MAIN;
