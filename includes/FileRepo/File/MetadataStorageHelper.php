<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\FileRepo\File;

use InvalidArgumentException;
use MediaWiki\FileRepo\LocalRepo;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Status\Status;
use MediaWiki\Storage\BlobStore;

/**
 * Helper for storage of metadata. Sharing the code between LocalFile and ArchivedFile
 *
 * @internal
 * @ingroup FileAbstraction
 */
class MetadataStorageHelper {
	/** @var LocalRepo */
	private $repo;

	public function __construct( LocalRepo $repo ) {
		$this->repo = $repo;
	}

	/**
	 * Get metadata in JSON format ready for DB insertion, optionally splitting
	 * items out to BlobStore.
	 *
	 * @param LocalFile|ArchivedFile $file
	 * @param array $envelope
	 * @return array
	 */
	public function getJsonMetadata( $file, $envelope ) {
		// Try encoding
		$s = $this->jsonEncode( $envelope );

		// Decide whether to try splitting the metadata.
		// Return early if it's not going to happen.
		if ( !$this->repo->isSplitMetadataEnabled()
			|| !$file->getHandler()
			|| !$file->getHandler()->useSplitMetadata()
		) {
			return [ $s, [] ];
		}
		$threshold = $this->repo->getSplitMetadataThreshold();
		if ( !$threshold || strlen( $s ) <= $threshold ) {
			return [ $s, [] ];
		}
		$blobStore = $this->repo->getBlobStore();
		if ( !$blobStore ) {
			return [ $s, [] ];
		}

		// The data as a whole is above the item threshold. Look for
		// large items that can be split out.
		$blobAddresses = [];
		foreach ( $envelope['data'] as $name => $value ) {
			$encoded = $this->jsonEncode( $value );
			if ( strlen( $encoded ) > $threshold ) {
				$blobAddresses[$name] = $blobStore->storeBlob(
					$encoded,
					[ BlobStore::IMAGE_HINT => $file->getName() ]
				);
			}
		}
		// Remove any items that were split out
		$envelope['data'] = array_diff_key( $envelope['data'], $blobAddresses );
		$envelope['blobs'] = $blobAddresses;
		$s = $this->jsonEncode( $envelope );

		return [ $s, $blobAddresses ];
	}

	/**
	 * Do JSON encoding with local flags. Callers must make sure that the data can be serialized.
	 *
	 * @param mixed $data
	 * @return string
	 */
	public function jsonEncode( $data ): string {
		$s = json_encode( $data,
			JSON_INVALID_UTF8_IGNORE |
			JSON_UNESCAPED_SLASHES |
			JSON_UNESCAPED_UNICODE );
		if ( $s === false ) {
			throw new InvalidArgumentException( __METHOD__ . ': metadata is not JSON-serializable ' );
		}
		return $s;
	}

	public function getMetadataFromBlobStore( array $addresses ): array {
		$result = [];
		if ( $addresses ) {
			$blobStore = $this->repo->getBlobStore();
			if ( !$blobStore ) {
				LoggerFactory::getInstance( 'LocalFile' )->warning(
					"Unable to load metadata: repo has no blob store" );
				return $result;
			}
			$status = $blobStore->getBlobBatch( $addresses );
			if ( !$status->isGood() ) {
				$msg = Status::wrap( $status )->getWikiText(
					false, false, 'en' );
				LoggerFactory::getInstance( 'LocalFile' )->warning(
					"Error loading metadata from BlobStore: $msg" );
			}
			foreach ( $addresses as $itemName => $address ) {
				$json = $status->getValue()[$address] ?? null;
				if ( $json !== null ) {
					$value = $this->jsonDecode( $json );
					$result[$itemName] = $value;
				}
			}
		}
		return $result;
	}

	/**
	 * Do JSON decoding with local flags.
	 *
	 * This doesn't use JsonCodec because JsonCodec can construct objects,
	 * which we don't want.
	 *
	 * Does not throw. Returns false on failure.
	 *
	 * @param string $s
	 * @return mixed The decoded value, or false on failure
	 */
	public function jsonDecode( string $s ) {
		// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
		return @json_decode( $s, true, 512, JSON_INVALID_UTF8_IGNORE );
	}

}

/** @deprecated class alias since 1.44 */
class_alias( MetadataStorageHelper::class, 'MetadataStorageHelper' );
