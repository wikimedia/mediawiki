<?php

namespace MediaWiki\Storage\TextTable;

use Content;
use ContentHandler;
use ExternalStore;
use IDBAccessObject;
use MediaWiki\Storage\RevisionContentException;
use MediaWiki\Storage\RevisionSlot;
use Revision;
use Title;

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
 * @since 1.27
 *
 * @file
 * @ingroup Storage
 *
 * @author Daniel Kinzler
 */

/**
 * The TextTable class is a low level data access service bound to the text table in the SQL
 * database schema.
 *
 * @todo: Make this an injectable service by making the public methods non-static.
 */
class TextTable {

	/**
	 * @todo make this non-static
	 *
	 * @param int $textId
	 * @param int $db DB_SLAVE or DB_MASTER
	 * @param string $method
	 * @param array $options Query options as supported by DatabaseBase::select
	 *
	 * @return false|object
	 */
	public static function getTextRow( $textId, $db = DB_SLAVE, $method = __METHOD__, $options = array() ) {
		$dbr = wfGetDB( $db );
		$row = $dbr->selectRow( 'text',
			array( 'old_text', 'old_flags' ),
			array( 'old_id' => $textId ),
			$method,
			$options );

		return $row;
	}

	/**
	 * Get revision text associated with an old or archive row
	 * $row is usually an object from wfFetchRow(), both the flags and the text
	 * field must be included.
	 *
	 * @todo make this non-static
	 *
	 * @param object $row The text data
	 * @param string $prefix Table prefix (default 'old_')
	 * @param string|bool $wiki The name of the wiki to load the revision text from
	 *   (same as the the wiki $row was loaded from) or false to indicate the local
	 *   wiki (this is the default). Otherwise, it must be a symbolic wiki database
	 *   identifier as understood by the LoadBalancer class.
	 * @return string Text the text requested or false on failure
	 */
	public static function getRevisionText( $row, $prefix = 'old_', $wiki = false ) {

		# Get data
		$textField = $prefix . 'text';
		$flagsField = $prefix . 'flags';

		if ( isset( $row->$flagsField ) ) {
			$flags = explode( ',', $row->$flagsField );
		} else {
			$flags = array();
		}

		if ( isset( $row->$textField ) ) {
			$text = $row->$textField;
		} else {
			return false;
		}

		# Use external methods for external objects, text in table is URL-only then
		if ( in_array( 'external', $flags ) ) {
			$url = $text;
			$parts = explode( '://', $url, 2 );
			if ( count( $parts ) == 1 || $parts[1] == '' ) {
				return false;
			}
			$text = ExternalStore::fetchFromURL( $url, array( 'wiki' => $wiki ) );
		}

		// If the text was fetched without an error, convert it
		if ( $text !== false ) {
			$text = self::decompressRevisionText( $text, $flags );
		}
		return $text;
	}

	/**
	 * Re-converts revision text according to it's flags.
	 *
	 * @todo make this non-static
	 * @todo remove access to global state
	 *
	 * @param mixed $text Reference to a text
	 * @param array $flags Compression flags
	 * @return string|bool Decompressed text, or false on failure
	 */
	public static function decompressRevisionText( $text, $flags ) {
		if ( in_array( 'gzip', $flags ) ) {
			# Deal with optional compression of archived pages.
			# This can be done periodically via maintenance/compressOld.php, and
			# as pages are saved if $wgCompressRevisions is set.
			$text = gzinflate( $text );

			if ( $text === false ) {
				wfLogWarning( __METHOD__ . ': gzinflate() failed' );
				return false;
			}
		}

		if ( in_array( 'object', $flags ) ) {
			# Generic compressed storage
			$obj = unserialize( $text );
			if ( !is_object( $obj ) ) {
				// Invalid object
				return false;
			}
			$text = $obj->getText();
		}

		global $wgLegacyEncoding;
		if ( $text !== false && $wgLegacyEncoding
			&& !in_array( 'utf-8', $flags ) && !in_array( 'utf8', $flags )
		) {
			# Old revisions kept around in a legacy encoding?
			# Upconvert on demand.
			# ("utf8" checked for compatibility with some broken
			#  conversion scripts 2008-12-30)
			global $wgContLang;
			$text = $wgContLang->iconv( $wgLegacyEncoding, 'UTF-8', $text );
		}

		return $text;
	}

}
