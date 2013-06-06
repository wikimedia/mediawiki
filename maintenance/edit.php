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

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script to make a page edit.
 *
 * @ingroup Maintenance
 */
class EditCLI extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Edit an article from the command line, text is from stdin";
		$this->addOption( 'user', 'Username', false, true, 'u' );
		$this->addOption( 'summary', 'Edit summary', false, true, 's' );
		$this->addOption( 'minor', 'Minor edit', false, false, 'm' );
		$this->addOption( 'bot', 'Bot edit', false, false, 'b' );
		$this->addOption( 'autosummary', 'Enable autosummary', false, false, 'a' );
		$this->addOption( 'no-rc', 'Do not show the change in recent changes', false, false, 'r' );
		$this->addArg( 'title', 'Title of article to edit' );
	}

	public function execute() {
		global $wgUser, $wgTitle;

		$userName = $this->getOption( 'user', 'Maintenance script' );
		$summary = $this->getOption( 'summary', '' );
		$minor = $this->hasOption( 'minor' );
		$bot = $this->hasOption( 'bot' );
		$autoSummary = $this->hasOption( 'autosummary' );
		$noRC = $this->hasOption( 'no-rc' );

		$wgUser = User::newFromName( $userName );
		$context = RequestContext::getMain();
		$context->setUser( $wgUser );
		if ( !$wgUser ) {
			$this->error( "Invalid username", true );
		}
		if ( $wgUser->isAnon() ) {
			$wgUser->addToDatabase();
		}

		$wgTitle = Title::newFromText( $this->getArg() );
		if ( !$wgTitle ) {
			$this->error( "Invalid title", true );
		}
		$context->setTitle( $wgTitle );

		$page = WikiPage::factory( $wgTitle );

		# Read the text
		$text = $this->getStdin( Maintenance::STDIN_ALL );
		$content = ContentHandler::makeContent( $text, $wgTitle );

		# Do the edit
		$this->output( "Saving... " );
		$status = $page->doEditContent( $content, $summary,
			( $minor ? EDIT_MINOR : 0 ) |
			( $bot ? EDIT_FORCE_BOT : 0 ) |
			( $autoSummary ? EDIT_AUTOSUMMARY : 0 ) |
			( $noRC ? EDIT_SUPPRESS_RC : 0 ) );
		if ( $status->isOK() ) {
			$this->output( "done\n" );
			$exit = 0;
		} else {
			$this->output( "failed\n" );
			$exit = 1;
		}
		if ( !$status->isGood() ) {
			$this->output( $status->getWikiText() . "\n" );
		}
		exit( $exit );
	}
}

$maintClass = "EditCLI";
require_once RUN_MAINTENANCE_IF_MAIN;
