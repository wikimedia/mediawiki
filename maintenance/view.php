<?php
/**
 * Show page contents.
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
 * Maintenance script to show page contents.
 *
 * @ingroup Maintenance
 */
class ViewCLI extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Show article contents on the command line' );
		$this->addArg( 'title', 'Title of article to view' );
	}

	public function execute() {
		$title = Title::newFromText( $this->getArg() );
		if ( !$title ) {
			$this->error( "Invalid title", true );
		}

		$page = WikiPage::factory( $title );

		$content = $page->getContent( Revision::RAW );
		if ( !$content ) {
			$this->error( "Page has no content", true );
		}
		if ( !$content instanceof TextContent ) {
			$this->error( "Non-text content models not supported", true );
		}

		$this->output( $content->getNativeData() );
	}
}

$maintClass = "ViewCLI";
require_once RUN_MAINTENANCE_IF_MAIN;
