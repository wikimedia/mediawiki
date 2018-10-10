<?php
/**
 * Make a page edit.
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

use MediaWiki\Revision\SlotRecord;

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script to make a page edit.
 *
 * @ingroup Maintenance
 */
class EditCLI extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Edit an article from the command line, text is from stdin' );
		$this->addOption( 'user', 'Username', false, true, 'u' );
		$this->addOption( 'summary', 'Edit summary', false, true, 's' );
		$this->addOption( 'remove', 'Remove a slot (requires --slot).', false, false );
		$this->addOption( 'minor', 'Minor edit', false, false, 'm' );
		$this->addOption( 'bot', 'Bot edit', false, false, 'b' );
		$this->addOption( 'autosummary', 'Enable autosummary', false, false, 'a' );
		$this->addOption( 'no-rc', 'Do not show the change in recent changes', false, false, 'r' );
		$this->addOption( 'nocreate', 'Don\'t create new pages', false, false );
		$this->addOption( 'createonly', 'Only create new pages', false, false );
		$this->addOption( 'slot', 'Slot role name', false, true );
		$this->addArg( 'title', 'Title of article to edit' );
	}

	public function execute() {
		global $wgUser;

		$userName = $this->getOption( 'user', false );
		$summary = $this->getOption( 'summary', '' );
		$remove = $this->hasOption( 'remove' );
		$minor = $this->hasOption( 'minor' );
		$bot = $this->hasOption( 'bot' );
		$autoSummary = $this->hasOption( 'autosummary' );
		$noRC = $this->hasOption( 'no-rc' );
		$slot = $this->getOption( 'slot', SlotRecord::MAIN );

		if ( $userName === false ) {
			$wgUser = User::newSystemUser( 'Maintenance script', [ 'steal' => true ] );
		} else {
			$wgUser = User::newFromName( $userName );
		}
		if ( !$wgUser ) {
			$this->fatalError( "Invalid username" );
		}
		if ( $wgUser->isAnon() ) {
			$wgUser->addToDatabase();
		}

		$title = Title::newFromText( $this->getArg() );
		if ( !$title ) {
			$this->fatalError( "Invalid title" );
		}

		if ( $this->hasOption( 'nocreate' ) && !$title->exists() ) {
			$this->fatalError( "Page does not exist" );
		} elseif ( $this->hasOption( 'createonly' ) && $title->exists() ) {
			$this->fatalError( "Page already exists" );
		}

		$page = WikiPage::factory( $title );

		if ( $remove ) {
			if ( $slot === SlotRecord::MAIN ) {
				$this->fatalError( "Cannot remove main slot! Use --slot to specify." );
			}

			$content = false;
		} else {
			# Read the text
			$text = $this->getStdin( Maintenance::STDIN_ALL );
			$content = ContentHandler::makeContent( $text, $title );
		}

		# Do the edit
		$this->output( "Saving... " );
		$updater = $page->newPageUpdater( $wgUser );

		$flags = ( $minor ? EDIT_MINOR : 0 ) |
			( $bot ? EDIT_FORCE_BOT : 0 ) |
			( $autoSummary ? EDIT_AUTOSUMMARY : 0 ) |
			( $noRC ? EDIT_SUPPRESS_RC : 0 );

		if ( $content === false ) {
			$updater->removeSlot( $slot );
		} else {
			$updater->setContent( $slot, $content );
		}

		$updater->saveRevision( CommentStoreComment::newUnsavedComment( $summary ), $flags );
		$status = $updater->getStatus();

		if ( $status->isOK() ) {
			$this->output( "done\n" );
			$exit = 0;
		} else {
			$this->output( "failed\n" );
			$exit = 1;
		}
		if ( !$status->isGood() ) {
			$this->output( $status->getWikiText( false, false, 'en' ) . "\n" );
		}
		exit( $exit );
	}
}

$maintClass = EditCLI::class;
require_once RUN_MAINTENANCE_IF_MAIN;
