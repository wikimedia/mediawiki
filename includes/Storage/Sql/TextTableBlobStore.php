<?php

namespace MediaWiki\Storage\Sql;

use DatabaseBase;
use ExternalStore;
use InvalidArgumentException;
use MediaWiki\Storage\BlobLookup;
use MediaWiki\Storage\BlobStore;
use MediaWiki\Storage\NotFoundException;
use MediaWiki\Storage\StorageException;

/**
 * Storage service for storing and loading blobs, based on MediaWiki's legacy
 * storage system using the "text" table in the local database.
 *
 * This includes support for the built-in "external storage" mechanism.
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
class TextTableBlobStore implements BlobStore, BlobLookup {

	private $defaultExternalStore;

	function __construct( $defaultExternalStore = false ) {
		//FIXME: this should be the actual external store implementation...
		$this->defaultExternalStore = $defaultExternalStore;
	}

	/**
	 * Stores a binary data blob and returns an address for retrieving it later.
	 * Optionally, an address may provided to be replaced. Implementations may
	 * or may not remove the previous content, and may or may not return the
	 * same address again. Content addressable stores shall, by nature,
	 * ignore $replaceAddress completely.
	 *
	 * @param string $data binary data to store
	 * @param string|null $replaceAddress the address of a blob to replace
	 *
	 * @throws StorageException if a storage level error occurred
	 * @throws InvalidArgumentException if $protoAddress is not supported
	 *
	 * @return string the permanent canonical address of the blob. Can be used
	 *         with BlobLookup::loadData() to retrieve the data.
	 */
	public function storeData( $data, $replaceAddress = null ) {
		// FIXME: get this from an injected ConnectionManager; find a good way to manage transactions.
		$dbw = wfGetDB( DB_WRITE );

		$old_id = $this->insert( $dbw, $data );
		return "$old_id";
	}

	/**
	 * @param string $address The desired blob's address, as returned by BlobStore::storeData().
	 *
	 * @throws NotFoundException if the requested data blob was not found
	 * @throws StorageException if a storage level error occurred
	 *
	 * @return string the binary data
	 */
	public function loadData( $address ) {
		//FIXME !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	}


	/**
	 * Insert a new revision into the database, returning the new revision ID
	 * number on success and dies horribly on failure.
	 *
	 * @param DatabaseBase $dbw (master connection)
	 * @throws StorageException, DBError
	 * @return int
	 */
	private function insert( DatabaseBase $dbw, $data ) {
		$flags = self::compressRevisionText( $data );

		# Write to external storage if required
		if ( $this->defaultExternalStore ) {
			// Store and get the URL
			// TODO: replace static ExternalStore interface with a proper BlobStore implementation.
			$data = ExternalStore::insertToDefault( $data );
			if ( !$data ) {
				throw new StorageException( "Unable to store text to external storage" );
			}
			if ( $flags ) {
				$flags .= ',';
			}
			$flags .= 'external';
		}

		$old_id = $dbw->nextSequenceValue( 'text_old_id_seq' );
		$dbw->insert( 'text',
			array(
				'old_id' => $old_id,
				'old_text' => $data,
				'old_flags' => $flags,
			), __METHOD__
		);

		$old_id = $dbw->insertId();
		return $old_id;
	}

	/**
	 * Get revision text associated with an old or archive row
	 * $row is usually an object from wfFetchRow(), both the flags and the text
	 * field must be included.
	 *
	 * @note: This is public for backward compatibility only. Avoid direct usage.
	 *
	 * @param stdClass $row The text data
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
	 * If $wgCompressRevisions is enabled, we will compress data.
	 * The input string is modified in place.
	 * Return value is the flags field: contains 'gzip' if the
	 * data is compressed, and 'utf-8' if we're saving in UTF-8
	 * mode.
	 *
	 * @note: This is public for backward compatibility only. Avoid direct usage.
	 *
	 * @param mixed $text Reference to a text
	 * @return string
	 */
	public static function compressRevisionText( &$text ) {
		global $wgCompressRevisions;
		$flags = array();

		# Revisions not marked this way will be converted
		# on load if $wgLegacyCharset is set in the future.
		$flags[] = 'utf-8';

		if ( $wgCompressRevisions ) {
			if ( function_exists( 'gzdeflate' ) ) {
				$deflated = gzdeflate( $text );

				if ( $deflated === false ) {
					wfLogWarning( __METHOD__ . ': gzdeflate() failed' );
				} else {
					$text = $deflated;
					$flags[] = 'gzip';
				}
			} else {
				wfDebug( __METHOD__ . " -- no zlib support, not compressing\n" );
			}
		}
		return implode( ',', $flags );
	}

	/**
	 * Re-converts revision text according to it's flags.
	 *
	 * @note: This is public for backward compatibility only. Avoid direct usage.
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
