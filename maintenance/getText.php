<?php
/**
 * Outputs page text to stdout.
 * Useful for command-line editing automation.
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
 * @file
 * @ingroup Maintenance
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that outputs page text to stdout.
 *
 * @ingroup Maintenance
 */
class GetTextMaint extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Outputs page text to stdout' );
		$this->addOption( 'show-private', 'Show the text even if it\'s not available to the public' );
		$this->addArg( 'title', 'Page title' );
	}

	public function execute() {
		$titleText = $this->getArg( 0 );
		$title = Title::newFromText( $titleText );
		if ( !$title ) {
			$this->fatalError( "$titleText is not a valid title.\n" );
		}

		$rev = Revision::newFromTitle( $title );
		if ( !$rev ) {
			$titleText = $title->getPrefixedText();
			$this->fatalError( "Page $titleText does not exist.\n" );
		}
		$content = $rev->getContent( $this->hasOption( 'show-private' )
			? Revision::RAW
			: Revision::FOR_PUBLIC );

		if ( $content === false ) {
			$titleText = $title->getPrefixedText();
			$this->fatalError( "Couldn't extract the text from $titleText.\n" );
		}
		$this->output( $content->serialize() );
	}
}

$maintClass = GetTextMaint::class;
require_once RUN_MAINTENANCE_IF_MAIN;
