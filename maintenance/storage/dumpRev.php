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
 * @ingroup Maintenance ExternalStorage
 */

require_once( __DIR__ . '/../Maintenance.php' );

class DumpRev extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addArg( 'rev-id', 'Revision ID', true );
	}

	public function execute() {
		$dbr = wfGetDB( DB_SLAVE );
		$row = $dbr->selectRow(
			array( 'text', 'revision' ),
			array( 'old_flags', 'old_text' ),
			array( 'old_id=rev_text_id', 'rev_id' => $this->getArg() )
		);
		if ( !$row ) {
			$this->error( "Row not found", true );
		}

		$flags = explode( ',',  $row->old_flags );
		$text = $row->old_text;
		if ( in_array( 'external', $flags ) ) {
			$this->output( "External $text\n" );
			if ( preg_match( '!^DB://(\w+)/(\w+)/(\w+)$!', $text, $m ) ) {
				$es = ExternalStore::getStoreObject( 'DB' );
				$blob = $es->fetchBlob( $m[1], $m[2], $m[3] );
				if ( strtolower( get_class( $blob ) ) == 'concatenatedgziphistoryblob' ) {
					$this->output( "Found external CGZ\n" );
					$blob->uncompress();
					$this->output( "Items: (" . implode( ', ', array_keys( $blob->mItems ) ) . ")\n" );
					$text = $blob->getItem( $m[3] );
				} else {
					$this->output( "CGZ expected at $text, got " . gettype( $blob ) . "\n" );
					$text = $blob;
				}
			} else {
				$this->output( "External plain $text\n" );
				$text = ExternalStore::fetchFromURL( $text );
			}
		}
		if ( in_array( 'gzip', $flags ) ) {
			$text = gzinflate( $text );
		}
		if ( in_array( 'object', $flags ) ) {
			$obj = unserialize( $text );
			$text = $obj->getText();
		}

		if ( is_object( $text ) ) {
			$this->error( "Unexpectedly got object of type: " . get_class( $text ) );
		} else {
			$this->output( "Text length: " . strlen( $text ) . "\n" );
			$this->output( substr( $text, 0, 100 ) . "\n" );
		}
	}
}

$maintClass = "DumpRev";
require_once( RUN_MAINTENANCE_IF_MAIN );
