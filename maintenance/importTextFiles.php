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
		$this->addDescription( 'Reads in text files and imports their content to pages of the wiki' );
		$this->addOption( 'user', 'Username to which edits should be attributed. ' .
			'Default: "Maintenance script"', false, true, 'u' );
		$this->addOption( 'summary', 'Specify edit summary for the edits', false, true, 's' );
		$this->addOption( 'use-timestamp', 'Use the modification date of the text file ' .
			'as the timestamp for the edit' );
		$this->addOption( 'overwrite', 'Overwrite existing pages. If --use-timestamp is passed, this ' .
			'will only overwrite pages if the file has been modified since the page was last modified.' );
		$this->addOption( 'prefix', 'A string to place in front of the file name', false, true, 'p' );
		$this->addOption( 'bot', 'Mark edits as bot edits in the recent changes list.' );
		$this->addOption( 'rc', 'Place revisions in RecentChanges.' );
		$this->addArg( 'files', 'Files to import' );
	}

	public function execute() {
		$userName = $this->getOption( 'user', false );
		$summary = $this->getOption( 'summary', 'Imported from text file' );
		$useTimestamp = $this->hasOption( 'use-timestamp' );
		$rc = $this->hasOption( 'rc' );
		$bot = $this->hasOption( 'bot' );
		$overwrite = $this->hasOption( 'overwrite' );
		$prefix = $this->getOption( 'prefix', '' );

		// Get all the arguments. A loop is required since Maintenance doesn't
		// suppport an arbitrary number of arguments.
		$files = [];
		$i = 0;
		while ( $arg = $this->getArg( $i++ ) ) {
			if ( file_exists( $arg ) ) {
				$files[$arg] = file_get_contents( $arg );
			} else {
				$this->error( "Fatal error: The file '$arg' does not exist!", 1 );
			}
		};

		$count = count( $files );
		$this->output( "Importing $count pages...\n" );

		if ( $userName === false ) {
			$user = User::newSystemUser( 'Maintenance script', [ 'steal' => true ] );
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
			$pageName = $prefix . pathinfo( $file, PATHINFO_FILENAME );
			$timestamp = $useTimestamp ? wfTimestamp( TS_UNIX, filemtime( $file ) ) : wfTimestampNow();

			$title = Title::newFromText( $pageName );
			$exists = $title->exists();
			$oldRevID = $title->getLatestRevID();
			$oldRev = $oldRevID ? Revision::newFromId( $oldRevID ) : null;

			if ( !$title ) {
				$this->error( "Invalid title $pageName. Skipping.\n" );
				$skipCount++;
				continue;
			}

			$actualTitle = $title->getPrefixedText();

			if ( $exists ) {
				$touched = wfTimestamp( TS_UNIX, $title->getTouched() );
				if ( !$overwrite ) {
					$this->output( "Title $actualTitle already exists. Skipping.\n" );
					$skipCount++;
					continue;
				} elseif ( $useTimestamp && intval( $touched ) >= intval( $timestamp ) ) {
					$this->output( "File for title $actualTitle has not been modified since the " .
						"destination page was touched. Skipping.\n" );
					$skipCount++;
					continue;
				}
			}

			$rev = new WikiRevision( ConfigFactory::getDefaultInstance()->makeConfig( 'main' ) );
			$rev->setText( rtrim( $text ) );
			$rev->setTitle( $title );
			$rev->setUserObj( $user );
			$rev->setComment( $summary );
			$rev->setTimestamp( $timestamp );

			if ( $exists && $overwrite && $rev->getContent()->equals( $oldRev->getContent() ) ) {
				$this->output( "File for title $actualTitle contains no changes from the current " .
					"revision. Skipping.\n" );
				$skipCount++;
				continue;
			}

			$status = $rev->importOldRevision();
			$newId = $title->getLatestRevID();

			if ( $status ) {
				$action = $exists ? 'updated' : 'created';
				$this->output( "Successfully $action $actualTitle\n" );
				$successCount++;
			} else {
				$action = $exists ? 'update' : 'create';
				$this->output( "Failed to $action $actualTitle\n" );
				$failCount++;
				$exit = 1;
			}

			// Create the RecentChanges entry if necessary
			if ( $rc && $status ) {
				if ( $exists ) {
					if ( is_object( $oldRev ) ) {
						$oldContent = $oldRev->getContent();
						RecentChange::notifyEdit(
							$timestamp,
							$title,
							$rev->getMinor(),
							$user,
							$summary,
							$oldRevID,
							$oldRev->getTimestamp(),
							$bot,
							'',
							$oldContent ? $oldContent->getSize() : 0,
							$rev->getContent()->getSize(),
							$newId,
							1 /* the pages don't need to be patrolled */
						);
					}
				} else {
					RecentChange::notifyNew(
						$timestamp,
						$title,
						$rev->getMinor(),
						$user,
						$summary,
						$bot,
						'',
						$rev->getContent()->getSize(),
						$newId,
						1
					);
				}
			}
		}

		$this->output( "Done! $successCount succeeded, $skipCount skipped.\n" );
		if ( $exit ) {
			$this->error( "Import failed with $failCount failed pages.\n", $exit );
		}
	}
}

$maintClass = "ImportTextFiles";
require_once RUN_MAINTENANCE_IF_MAIN;
