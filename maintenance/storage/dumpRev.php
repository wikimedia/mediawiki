<?php
/**
 * Get the text of a revision, resolving external storage if needed.
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
 * @ingroup Maintenance ExternalStorage
 */

use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\SlotRecord;

require_once __DIR__ . '/../Maintenance.php';

/**
 * Maintenance script that gets the text of a revision,
 * resolving external storage if needed.
 *
 * @ingroup Maintenance ExternalStorage
 */
class DumpRev extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addArg( 'rev-id', 'Revision ID', true );
	}

	public function execute() {
		$id = (int)$this->getArg( 0 );

		$lookup = MediaWikiServices::getInstance()->getRevisionLookup();
		$rev = $lookup->getRevisionById( $id );
		if ( !$rev ) {
			$this->fatalError( "Row not found" );
		}

		$content = $rev->getContent( SlotRecord::MAIN );
		if ( !$content ) {
			$this->fatalError( "Text not found" );
		}

		$blobStore = MediaWikiServices::getInstance()->getBlobStore();
		$slot = $rev->getSlot( SlotRecord::MAIN );
		$text = $blobStore->getBlob( $slot->getAddress() );

		$this->output( "Text length: " . strlen( $text ) . "\n" );
		$this->output( substr( $text, 0, 100 ) . "\n" );
	}
}

$maintClass = DumpRev::class;
require_once RUN_MAINTENANCE_IF_MAIN;
