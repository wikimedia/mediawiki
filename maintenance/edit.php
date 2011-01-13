<?php
/**
 * Make an edit
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
 * @ingroup Maintenance
 */

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class EditCLI extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Edit an article from the command line, text is from stdin";
		$this->addOption( 'u', 'Username', false, true );
		$this->addOption( 's', 'Edit summary', false, true );
		$this->addOption( 'm', 'Minor edit' );
		$this->addOption( 'b', 'Bot edit' );
		$this->addOption( 'a', 'Enable autosummary' );
		$this->addOption( 'no-rc', 'Do not show the change in recent changes' );
		$this->addArg( 'title', 'Title of article to edit' );
	}

	public function execute() {
		global $wgUser;

		$userName = $this->getOption( 'u', 'Maintenance script' );
		$summary = $this->getOption( 's', '' );
		$minor = $this->hasOption( 'm' );
		$bot = $this->hasOption( 'b' );
		$autoSummary = $this->hasOption( 'a' );
		$noRC = $this->hasOption( 'no-rc' );

		$wgUser = User::newFromName( $userName );
		if ( !$wgUser ) {
			$this->error( "Invalid username", true );
		}
		if ( $wgUser->isAnon() ) {
			$wgUser->addToDatabase();
		}

		$title = Title::newFromText( $this->getArg() );
		if ( !$title ) {
			$this->error( "Invalid title", true );
		}

		$article = new Article( $title );

		# Read the text
		$text = $this->getStdin( Maintenance::STDIN_ALL );

		# Do the edit
		$this->output( "Saving... " );
		$status = $article->doEdit( $text, $summary,
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
require_once( RUN_MAINTENANCE_IF_MAIN );

