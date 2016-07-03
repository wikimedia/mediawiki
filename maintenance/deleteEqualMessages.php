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
		$this->addDescription( 'Deletes all pages in the MediaWiki namespace that are equal to '
			. 'the default message' );
		$this->addOption( 'delete', 'Actually delete the pages (default: dry run)' );
		$this->addOption( 'delete-talk', 'Don\'t leave orphaned talk pages behind during deletion' );
		$this->addOption( 'lang-code', 'Check for subpages of this language code (default: root '
			. 'page against content language). Use value "*" to run for all mwfile language code '
			. 'subpages (including the base pages that override content language).', false, true );
	}

	/**
	 * @param string|bool $langCode See --lang-code option.
	 * @param array &$messageInfo
	 */
	protected function fetchMessageInfo( $langCode, array &$messageInfo ) {
		global $wgContLang;

		if ( $langCode ) {
			$this->output( "\n... fetching message info for language: $langCode" );
			$nonContLang = true;
		} else {
			$this->output( "\n... fetching message info for content language" );
			$langCode = $wgContLang->getCode();
			$nonContLang = false;
		}

		/* Based on SpecialAllmessages::reallyDoQuery #filter=modified */

		$l10nCache = Language::getLocalisationCache();
		$messageNames = $l10nCache->getSubitemList( 'en', 'messages' );
		// Normalise message names for NS_MEDIAWIKI page_title
		$messageNames = array_map( [ $wgContLang, 'ucfirst' ], $messageNames );

		$statuses = AllMessagesTablePager::getCustomisedStatuses(
			$messageNames, $langCode, $nonContLang );
		// getCustomisedStatuses is stripping the sub page from the page titles, add it back
		$titleSuffix = $nonContLang ? "/$langCode" : '';

		foreach ( $messageNames as $key ) {
			$customised = isset( $statuses['pages'][$key] );
			if ( $customised ) {
				$actual = wfMessage( $key )->inLanguage( $langCode )->plain();
				$default = wfMessage( $key )->inLanguage( $langCode )->useDatabase( false )->plain();

				$messageInfo['relevantPages']++;

				if (
					// Exclude messages that are empty by default, such as sitenotice, specialpage
					// summaries and accesskeys.
					$default !== '' && $default !== '-' &&
						$actual === $default
				) {
					$hasTalk = isset( $statuses['talks'][$key] );
					$messageInfo['results'][] = [
						'title' => $key . $titleSuffix,
						'hasTalk' => $hasTalk,
					];
					$messageInfo['equalPages']++;
					if ( $hasTalk ) {
						$messageInfo['equalPagesTalks']++;
					}
				}
			}
		}
	}

	public function execute() {
		$doDelete = $this->hasOption( 'delete' );
		$doDeleteTalk = $this->hasOption( 'delete-talk' );
		$langCode = $this->getOption( 'lang-code' );

		$messageInfo = [
			'relevantPages' => 0,
			'equalPages' => 0,
			'equalPagesTalks' => 0,
			'results' => [],
		];

		$this->output( 'Checking for pages with default message...' );

		// Load message information
		if ( $langCode ) {
			$langCodes = Language::fetchLanguageNames( null, 'mwfile' );
			if ( $langCode === '*' ) {
				// All valid lang-code subpages in NS_MEDIAWIKI that
				// override the messsages in that language
				foreach ( $langCodes as $key => $value ) {
					$this->fetchMessageInfo( $key, $messageInfo );
				}
				// Lastly, the base pages in NS_MEDIAWIKI that override
				// messages in content language
				$this->fetchMessageInfo( false, $messageInfo );
			} else {
				if ( !isset( $langCodes[$langCode] ) ) {
					$this->error( 'Invalid language code: ' . $langCode, 1 );
				}
				$this->fetchMessageInfo( $langCode, $messageInfo );
			}
		} else {
			$this->fetchMessageInfo( false, $messageInfo );
		}

		if ( $messageInfo['equalPages'] === 0 ) {
			// No more equal messages left
			$this->output( "\ndone.\n" );

			return;
		}

		$this->output( "\n{$messageInfo['relevantPages']} pages in the MediaWiki namespace "
			. "override messages." );
		$this->output( "\n{$messageInfo['equalPages']} pages are equal to the default message "
			. "(+ {$messageInfo['equalPagesTalks']} talk pages).\n" );

		if ( !$doDelete ) {
			$list = '';
			foreach ( $messageInfo['results'] as $result ) {
				$title = Title::makeTitle( NS_MEDIAWIKI, $result['title'] );
				$list .= "* [[$title]]\n";
				if ( $result['hasTalk'] ) {
					$title = Title::makeTitle( NS_MEDIAWIKI_TALK, $result['title'] );
					$list .= "* [[$title]]\n";
				}
			}
			$this->output( "\nList:\n$list\nRun the script again with --delete to delete these pages" );
			if ( $messageInfo['equalPagesTalks'] !== 0 ) {
				$this->output( " (include --delete-talk to also delete the talk pages)" );
			}
			$this->output( "\n" );

			return;
		}

		$user = User::newSystemUser( 'MediaWiki default', [ 'steal' => true ] );
		if ( !$user ) {
			$this->error( "Invalid username", true );
		}
		global $wgUser;
		$wgUser = $user;

		// Hide deletions from RecentChanges
		$user->addGroup( 'bot' );

		// Handle deletion
		$this->output( "\n...deleting equal messages (this may take a long time!)..." );
		$dbw = $this->getDB( DB_MASTER );
		foreach ( $messageInfo['results'] as $result ) {
			wfWaitForSlaves();
			$dbw->ping();
			$title = Title::makeTitle( NS_MEDIAWIKI, $result['title'] );
			$this->output( "\n* [[$title]]" );
			$page = WikiPage::factory( $title );
			$error = ''; // Passed by ref
			$success = $page->doDeleteArticle( 'No longer required', false, 0, true, $error, $user );
			if ( !$success ) {
				$this->output( " (Failed!)" );
			}
			if ( $result['hasTalk'] && $doDeleteTalk ) {
				$title = Title::makeTitle( NS_MEDIAWIKI_TALK, $result['title'] );
				$this->output( "\n* [[$title]]" );
				$page = WikiPage::factory( $title );
				$error = ''; // Passed by ref
				$success = $page->doDeleteArticle( 'Orphaned talk page of no longer required message',
					false, 0, true, $error, $user );
				if ( !$success ) {
					$this->output( " (Failed!)" );
				}
			}
		}
		$this->output( "\n\ndone!\n" );
	}
}

$maintClass = "DeleteEqualMessages";
require_once RUN_MAINTENANCE_IF_MAIN;
