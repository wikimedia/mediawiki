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
 * @file
 * @ingroup Maintenance
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that deletes all pages in the MediaWiki namespace
 * of which the content is equal to the system default.
 *
 * @ingroup Maintenance
 */
class DeleteEqualMessages extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Deletes all pages in the MediaWiki namespace that are equal to the default message";
		$this->addOption( 'delete', 'Actually delete the pages (default: dry run)' );
		$this->addOption( 'delete-talk', 'Don\'t leave orphaned talk pages behind during deletion' );
		$this->addOption( 'lang-code', 'Check for subpages of this lang-code (default: root page against content language)', false, true );
	}

	public function execute() {
		global $wgUser, $wgContLang;

		$doDelete = $this->hasOption( 'delete' );
		$doDeleteTalk = $this->hasOption( 'delete-talk' );
		$forLangCode = $this->getOption( 'lang-code' );

		if ( $forLangCode ) {
			$langObj = Language::factory( $forLangCode );
			$langCode = $langObj->getCode();
			$nonContLang = true;
		} else {
			$langObj = $wgContLang;
			$langCode = $wgContLang->getCode();
			$nonContLang = false;
		}

		$this->output( "Checking for pages with default message..." );

		/* Based on SpecialAllmessages::reallyDoQuery #filter=modified */

		$messageNames = Language::getLocalisationCache()->getSubitemList( 'en', 'messages' );
		// Normalise message names for NS_MEDIAWIKI page_title
		$messageNames = array_map( array( $langObj, 'ucfirst' ), $messageNames );
		// TODO: Do the below for each language code (e.g. delete /xxx subpage if equal to MessagesXxx)
		// Right now it only takes care of the root override, which is enough since most wikis aren't multi-lang wikis.
		$statuses = AllmessagesTablePager::getCustomisedStatuses( $messageNames, $langCode, $nonContLang );

		$relevantPages = 0;
		$equalPages = 0;
		$equalPagesTalks = 0;
		$results = array();
		foreach ( $messageNames as $key ) {
			$customised = isset( $statuses['pages'][$key] );
			if ( $customised ) {
				$actual = wfMessage( $key )->inLanguage( $langCode )->plain();
				$default = wfMessage( $key )->inLanguage( $langCode )->useDatabase( false )->plain();

				$relevantPages++;
				if ( $actual === $default ) {
					$hasTalk = isset( $statuses['talks'][$key] );
					$results[] = array(
						'title' => $key,
						'hasTalk' => $hasTalk,
					);
					$equalPages++;
					if ( $hasTalk ) {
						$equalPagesTalks++;
					}
				}
			}
		}

		if ( $equalPages === 0 ) {
			// No more equal messages left
			$this->output( "done.\n" );
			return;
		}

		$this->output( "\n{$relevantPages} pages in the MediaWiki namespace override messages." );
		$this->output( "\n{$equalPages} pages are equal to the default message (+ {$equalPagesTalks} talk pages).\n" );

		if ( !$doDelete ) {
			$list = '';
			foreach ( $results as $result ) {
				$title = Title::makeTitle( NS_MEDIAWIKI, $result['title'] );
				$list .= "* [[$title]]\n";
				if ( $result['hasTalk'] ) {
					$title = Title::makeTitle( NS_MEDIAWIKI_TALK, $result['title'] );
					$list .= "* [[$title]]\n";
				}
			}
			$this->output( "\nList:\n$list\nRun the script again with --delete to delete these pages" );
			if ( $equalPagesTalks !== 0 ) {
				$this->output( " (include --delete-talk to also delete the talk pages)" );
			}
			$this->output( "\n" );
			return;
		}

		$user = User::newFromName( 'MediaWiki default' );
		if ( !$user ) {
			$this->error( "Invalid username", true );
		}
		$wgUser = $user;

		// Hide deletions from RecentChanges
		$user->addGroup( 'bot' );

		// Handle deletion
		$this->output( "\n...deleting equal messages (this may take a long time!)..." );
		$dbw = wfGetDB( DB_MASTER );
		foreach ( $results as $result ) {
			wfWaitForSlaves();
			$dbw->ping();
			$dbw->begin( __METHOD__ );
			$title = Title::makeTitle( NS_MEDIAWIKI, $result['title'] );
			$this->output( "\n* [[$title]]" );
			$page = WikiPage::factory( $title );
			$error = ''; // Passed by ref
			$page->doDeleteArticle( 'No longer required', false, 0, false, $error, $user );
			if ( $result['hasTalk'] && $doDeleteTalk ) {
				$title = Title::makeTitle( NS_MEDIAWIKI_TALK, $result['title'] );
				$this->output( "\n* [[$title]]" );
				$page = WikiPage::factory( $title );
				$error = ''; // Passed by ref
				$page->doDeleteArticle( 'Orphaned talk page of no longer required message', false, 0, false, $error, $user );
			}
			$dbw->commit( __METHOD__ );
		}
		$this->output( "\n\ndone!\n" );
	}
}

$maintClass = "DeleteEqualMessages";
require_once RUN_MAINTENANCE_IF_MAIN;
