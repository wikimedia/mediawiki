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

use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Content\ContentHandler;
use MediaWiki\Language\RawMessage;
use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\StubObject\StubGlobalUser;
use MediaWiki\Title\Title;
use MediaWiki\User\User;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

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
		$this->addOption(
			'parse-title',
			'Parse title input as a message, e.g. "{{int:mainpage}}" or "News_{{CURRENTYEAR}}',
			false, false, 'p'
		);
		$this->addArg( 'title', 'Title of article to edit' );
	}

	/** @inheritDoc */
	public function execute() {
		$userName = $this->getOption( 'user', false );
		$summary = $this->getOption( 'summary', '' );
		$remove = $this->hasOption( 'remove' );
		$minor = $this->hasOption( 'minor' );
		$bot = $this->hasOption( 'bot' );
		$autoSummary = $this->hasOption( 'autosummary' );
		$noRC = $this->hasOption( 'no-rc' );
		$slot = $this->getOption( 'slot', SlotRecord::MAIN );

		if ( $userName === false ) {
			$user = User::newSystemUser( User::MAINTENANCE_SCRIPT_USER, [ 'steal' => true ] );
		} else {
			$user = User::newFromName( $userName );
		}
		if ( !$user ) {
			$this->fatalError( "Invalid username" );
		}
		if ( $user->isAnon() ) {
			$user->addToDatabase();
		}
		StubGlobalUser::setUser( $user );

		$titleInput = $this->getArg( 0 );

		if ( $this->hasOption( 'parse-title' ) ) {
			$titleInput = ( new RawMessage( '$1' ) )->params( $titleInput )->text();
		}

		$title = Title::newFromText( $titleInput );
		if ( !$title ) {
			$this->fatalError( "Invalid title" );
		}

		if ( $this->hasOption( 'nocreate' ) && !$title->exists() ) {
			$this->fatalError( "Page does not exist" );
		} elseif ( $this->hasOption( 'createonly' ) && $title->exists() ) {
			$this->fatalError( "Page already exists" );
		}

		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );

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
		$this->output( "Saving..." );
		$updater = $page->newPageUpdater( $user );

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
		} else {
			$this->output( "failed\n" );
		}
		if ( !$status->isGood() ) {
			$this->error( $status );
		}
		return $status->isOK();
	}
}

// @codeCoverageIgnoreStart
$maintClass = EditCLI::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
