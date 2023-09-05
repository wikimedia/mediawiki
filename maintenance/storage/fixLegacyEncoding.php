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
 * @ingroup Maintenance ExternalStorage
 */

use MediaWiki\Storage\SqlBlobStore;

require_once __DIR__ . '/moveToExternal.php';

class FixLegacyEncoding extends MoveToExternal {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Change encoding of stored content from legacy encoding to UTF-8' );
	}

	protected function getConditions( $blockStart, $blockEnd, $dbr ) {
		return [
			"old_id BETWEEN $blockStart AND $blockEnd",
			'old_flags NOT ' . $dbr->buildLike( $dbr->anyString(), 'utf-8', $dbr->anyString() ),
			'old_flags NOT ' . $dbr->buildLike( $dbr->anyString(), 'utf8', $dbr->anyString() ),
		];
	}

	protected function resolveText( $text, $flags ) {
		if ( in_array( 'error', $flags ) ) {
			return [ $text, $flags ];
		}
		$blobStore = $this->getServiceContainer()->getBlobStore();
		if ( in_array( 'external', $flags ) && $blobStore instanceof SqlBlobStore ) {
			$text = $blobStore->expandBlob( $text, $flags );
			// It will be put back in external storage again
			$flags = array_diff( $flags, [ 'external' ] );
		}
		if ( in_array( 'gzip', $flags ) ) {
			$text = gzinflate( $text );
			$flags = array_diff( $flags, [ 'gzip' ] );
		}
		return [ $text, $flags ];
	}

}

$maintClass = FixLegacyEncoding::class;
require_once RUN_MAINTENANCE_IF_MAIN;
