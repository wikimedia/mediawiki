<?php

namespace MediaWiki\Storage;

use Content;
use ContentHandler;
use IDBAccessObject;
use MediaWiki\User\UserIdentityValue;
use User;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LoadBalancer;

abstract class AbstractRevisionFactory
	implements IDBAccessObject, RevisionFactory, NullRevisionFactory {

	/**
	 * @var SqlBlobStore
	 */
	private $blobStore;

	/**
	 * @var boolean
	 */
	protected $contentHandlerUseDB = true;

	/**
	 * @var LoadBalancer
	 */
	protected $loadBalancer;

	/**
	 * @var bool|string
	 */
	protected $wikiId;

	/**
	 * @todo $blobStore should be allowed to be any BlobStore!
	 *
	 * @param LoadBalancer $loadBalancer
	 * @param SqlBlobStore $blobStore
	 * @param RevisionTitleLookup $revisionTitleLookup
	 * @param bool|string $wikiId
	 */
	public function __construct(
		LoadBalancer $loadBalancer,
		SqlBlobStore $blobStore,
		RevisionTitleLookup $revisionTitleLookup,
		$wikiId = false
	) {
		Assert::parameterType( 'string|boolean', $wikiId, '$wikiId' );

		$this->blobStore = $blobStore;
		$this->loadBalancer = $loadBalancer;
		$this->revisionTitleLookup = $revisionTitleLookup;
		$this->wikiId = $wikiId;
	}

	/**
	 * @return bool
	 */
	public function getContentHandlerUseDB() {
		return $this->contentHandlerUseDB;
	}

	/**
	 * @param bool $contentHandlerUseDB
	 */
	public function setContentHandlerUseDB( $contentHandlerUseDB ) {
		$this->contentHandlerUseDB = $contentHandlerUseDB;
	}

	/**
	 * @param int $mode DB_MASTER or DB_REPLICA
	 *
	 * @return IDatabase
	 */
	protected function getDBConnection( $mode ) {
		return $this->loadBalancer->getConnection( $mode, [], $this->wikiId );
	}

	/**
	 * Loads a Content object based on a slot row.
	 *
	 * This method does not call $slot->getContent(), and may be used as a callback
	 * called by $slot->getContent().
	 *
	 * MCR migration note: this roughly corresponds to Revision::getContentInternal
	 *
	 * @param SlotRecord $slot The SlotRecord to load content for
	 * @param string|null $blobData The content blob, in the form indicated by $blobFlags
	 * @param string|null $blobFlags Flags indicating how $blobData needs to be processed.
	 *  null if no processing should happen.
	 * @param string|null $blobFormat MIME type indicating how $dataBlob is encoded
	 * @param int $queryFlags
	 *
	 * @throw RevisionAccessException
	 * @return Content
	 */
	protected function loadSlotContent(
		SlotRecord $slot,
		$blobData = null,
		$blobFlags = null,
		$blobFormat = null,
		$queryFlags = 0
	) {
		if ( $blobData !== null ) {
			Assert::parameterType( 'string', $blobData, '$blobData' );
			Assert::parameterType( 'string|null', $blobFlags, '$blobFlags' );

			$cacheKey = $slot->hasAddress() ? $slot->getAddress() : null;

			if ( $blobFlags === null ) {
				$data = $blobData;
			} else {
				$data = $this->blobStore->expandBlob( $blobData, $blobFlags, $cacheKey );
				if ( $data === false ) {
					throw new RevisionAccessException(
						"Failed to expand blob data using flags $blobFlags (key: $cacheKey)"
					);
				}
			}

		} else {
			$address = $slot->getAddress();
			try {
				$data = $this->blobStore->getBlob( $address, $queryFlags );
			} catch ( BlobAccessException $e ) {
				throw new RevisionAccessException(
					"Failed to load data blob from $address: " . $e->getMessage(), 0, $e
				);
			}
		}

		// Unserialize content
		$handler = ContentHandler::getForModelID( $slot->getModel() );

		$content = $handler->unserializeContent( $data, $blobFormat );
		return $content;
	}

	/**
	 * @param object $row
	 * @param string $prefix Field prefix, such as 'rev_' or 'ar_'.
	 *
	 * @return UserIdentityValue
	 */
	protected function getUserIdentityFromRowObject( $row, $prefix = 'rev_' ) {
		$idField = "{$prefix}user";
		$nameField = "{$prefix}user_text";

		$userId = intval( $row->$idField );

		if ( isset( $row->user_name ) ) {
			$userName = $row->user_name;
		} elseif ( isset( $row->$nameField ) ) {
			$userName = $row->$nameField;
		} else {
			$userName = User::whoIs( $userId );
		}

		if ( $userName === false ) {
			wfWarn( __METHOD__ . ': Cannot determine user name for user ID ' . $userId );
			$userName = '';
		}

		return new UserIdentityValue( $userId, $userName );
	}

}