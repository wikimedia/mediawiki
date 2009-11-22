<?php
/**
 * Outputs page text to stdout, useful for command-line editing automation.
 * Example: php getText.php "page title" | sed -e '...' | php edit.php "page title"
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

require_once( dirname(__FILE__) . '/Maintenance.php' );

class GetTextMaint extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = 'Outputs page text to stdout';
		$this->addArg( 'title', 'Page title' );
	}

	public function execute() {
		$this->db = wfGetDB( DB_MASTER );

		$titleText = $this->getArg( 0 );
		$title = Title::newFromText( $titleText );
		if ( !$title ) {
			$this->error( "$titleText is not a valid title\n", true );
		}

		$rev = Revision::newFromTitle( $title );
		if ( !$rev ) {
			$titleText = $title->getText();
			$this->error( "Page $titleText does not exist\n", true );
		}

		$this->output( $rev->getText() );
	}
}

$maintClass = "GetTextMaint";
require_once( DO_MAINTENANCE );