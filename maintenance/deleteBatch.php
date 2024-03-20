<?php
/**
 * Deletes a batch of pages.
 * Usage: php deleteBatch.php [-u <user>] [-r <reason>] [-i <interval>] [--by-id] [listfile]
 * where
 *   [listfile] is a file where each line contains the title of a page to be
 *     deleted, standard input is used if listfile is not given.
 *   <user> is the username
 *   <reason> is the delete reason
 *   <interval> is the number of seconds to sleep for after each delete
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

use MediaWiki\MediaWikiServices;

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script to delete a batch of pages.
 *
 * @ingroup Maintenance
 */
class DeleteBatch extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Deletes a batch of pages' );
		$this->addOption( 'u', 'User to perform deletion', false, true );
		$this->addOption( 'r', 'Reason to delete page', false, true );
		$this->addOption( 'i', 'Interval to sleep (in seconds) between deletions' );
		$this->addOption( 'by-id', 'Delete by page ID instead of by page name', false, false );
		$this->addArg( 'listfile', 'File with titles to delete, separated by newlines. ' .
			'If not given, stdin will be used.', false );
	}

	public function execute() {
		# Change to current working directory
		$oldCwd = getcwd();
		chdir( $oldCwd );

		# Options processing
		$username = $this->getOption( 'u', false );
		$reason = $this->getOption( 'r', '' );
		$interval = $this->getOption( 'i', 0 );
		$byId = $this->hasOption( 'by-id' );

		if ( $username === false ) {
			$user = User::newSystemUser( 'Delete page script', [ 'steal' => true ] );
		} else {
			$user = User::newFromName( $username );
		}
		if ( !$user ) {
			$this->fatalError( "Invalid username" );
		}
		StubGlobalUser::setUser( $user );

		if ( $this->hasArg( 0 ) ) {
			$file = fopen( $this->getArg( 0 ), 'r' );
		} else {
			$file = $this->getStdin();
		}

		# Setup
		if ( !$file ) {
			$this->fatalError( "Unable to read file, exiting" );
		}

		$services = MediaWikiServices::getInstance();
		$lbFactory = $services->getDBLoadBalancerFactory();
		$wikiPageFactory = $services->getWikiPageFactory();
		$repoGroup = $services->getRepoGroup();

		# Handle each entry
		for ( $linenum = 1; !feof( $file ); $linenum++ ) {
			$line = trim( fgets( $file ) );
			if ( $line == '' ) {
				continue;
			}
			if ( $byId === false ) {
				$target = Title::newFromText( $line );
				if ( $target === null ) {
					$this->output( "Invalid title '$line' on line $linenum\n" );
					continue;
				}
				if ( !$target->exists() ) {
					$this->output( "Skipping nonexistent page '$line'\n" );
					continue;
				}
			} else {
				$target = Title::newFromID( (int)$line );
				if ( $target === null ) {
					$this->output( "Invalid page ID '$line' on line $linenum\n" );
					continue;
				}
				if ( !$target->exists() ) {
					$this->output( "Skipping nonexistent page ID '$line'\n" );
					continue;
				}
			}
			if ( $target->getNamespace() === NS_FILE ) {
				$img = $repoGroup->findFile(
					$target, [ 'ignoreRedirect' => true ]
				);
				if ( $img && $img->isLocal() && !$img->deleteFile( $reason, $user ) ) {
					$this->output( " FAILED to delete associated file..." );
				}
			}
			$page = $wikiPageFactory->newFromTitle( $target );
			$error = '';
			$status = $page->doDeleteArticleReal(
				$reason,
				$user,
				false,
				null,
				$error,
				null,
				[],
				'delete',
				true
			);
			if ( $status->isOK() ) {
				$this->output( " Deleted!\n" );
			} else {
				$this->output( " FAILED to delete article\n" );
			}

			if ( $interval ) {
				sleep( $interval );
			}
			$lbFactory->waitForReplication();
		}
	}
}

$maintClass = DeleteBatch::class;
require_once RUN_MAINTENANCE_IF_MAIN;
