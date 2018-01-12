<?php

namespace MediaWiki\Storage;

use CommentStore;
use CommentStoreComment;
use Content;
use ContentHandler;
use IDBAccessObject;
use InvalidArgumentException;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use Message;
use MWException;
use stdClass;
use Title;
use User;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LoadBalancer;

class SingleContentRevisionFactory
	implements IDBAccessObject, RevisionFactory, NullRevisionFactory {

	use DatabaseWikiIdChecker;
	use SingleContentRevisionQueryInfo;

	/**
	 * @var SqlBlobStore
	 */
	private $blobStore;

	/**
	 * @var RevisionTitleLookup
	 */
	private $revisionTitleLookup;

	/**
	 * @var bool|string
	 */
	private $wikiId;

	/**
	 * @var boolean
	 */
	private $contentHandlerUseDB = true;

	/**
	 * @var LoadBalancer
	 */
	private $loadBalancer;

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

		$this->loadBalancer = $loadBalancer;
		$this->blobStore = $blobStore;
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
	private function getDBConnection( $mode ) {
		return $this->loadBalancer->getConnection( $mode, [], $this->wikiId );
	}

	/**
	 * Make a fake revision object from an archive table row. This is queried
	 * for permissions or even inserted (as in Special:Undelete)
	 *
	 * MCR migration note: this replaces Revision::newFromArchiveRow
	 *
	 * @param object $row
	 * @param int $queryFlags
	 * @param Title|null $title
	 * @param array $overrides associative array with fields of $row to override. This may be
	 *   used e.g. to force the parent revision ID or page ID. Keys in the array are fields
	 *   names from the archive table without the 'ar_' prefix, i.e. use 'parent_id' to
	 *   override ar_parent_id.
	 *
	 * @return RevisionRecord
	 * @throws MWException
	 */
	public function newRevisionFromArchiveRow(
		$row,
		$queryFlags = 0,
		Title $title = null,
		array $overrides = []
	) {
		Assert::parameterType( 'object', $row, '$row' );

		// check second argument, since Revision::newFromArchiveRow had $overrides in that spot.
		Assert::parameterType( 'integer', $queryFlags, '$queryFlags' );

		if ( !$title && isset( $overrides['title'] ) ) {
			if ( !( $overrides['title'] instanceof Title ) ) {
				throw new MWException( 'title field override must contain a Title object.' );
			}

			$title = $overrides['title'];
		}

		if ( !isset( $title ) ) {
			if ( isset( $row->ar_namespace ) && isset( $row->ar_title ) ) {
				$title = Title::makeTitle( $row->ar_namespace, $row->ar_title );
			} else {
				throw new InvalidArgumentException(
					'A Title or ar_namespace and ar_title must be given'
				);
			}
		}

		foreach ( $overrides as $key => $value ) {
			$field = "ar_$key";
			$row->$field = $value;
		}

		$user = $this->getUserIdentityFromRowObject( $row, 'ar_' );

		$comment = CommentStore::newKey( 'ar_comment' )
			// Legacy because $row may have come from self::selectFields()
			->getCommentLegacy( $this->getDBConnection( DB_REPLICA ), $row, true );

		$mainSlot = $this->emulateMainSlot_1_29( $row, $queryFlags, $title );
		$slots = new RevisionSlots( [ 'main' => $mainSlot ] );

		return new RevisionArchiveRecord( $title, $user, $comment, $row, $slots, $this->wikiId );
	}

	/**
	 * @see RevisionFactory::newRevisionFromRow
	 *
	 * MCR migration note: this replaces Revision::newFromRow
	 *
	 * @param object $row
	 * @param int $queryFlags
	 * @param Title|null $title
	 *
	 * @return RevisionRecord
	 */
	public function newRevisionFromRow( $row, $queryFlags = 0, Title $title = null ) {
		return $this->newRevisionFromRow_1_29( $row, $queryFlags, $title );
	}

	/**
	 * Constructs a new MutableRevisionRecord based on the given associative array following
	 * the MW1.29 convention for the Revision constructor.
	 *
	 * MCR migration note: this replaces Revision::newFromRow
	 *
	 * @param array $fields
	 * @param int $queryFlags
	 * @param Title|null $title
	 *
	 * @return MutableRevisionRecord
	 * @throws MWException
	 * @throws RevisionAccessException
	 */
	public function newMutableRevisionFromArray(
		array $fields,
		$queryFlags = 0,
		Title $title = null
	) {
		if ( !$title && isset( $fields['title'] ) ) {
			if ( !( $fields['title'] instanceof Title ) ) {
				throw new MWException( 'title field must contain a Title object.' );
			}

			$title = $fields['title'];
		}

		if ( !$title ) {
			$pageId = isset( $fields['page'] ) ? $fields['page'] : 0;
			$revId = isset( $fields['id'] ) ? $fields['id'] : 0;

			$title = $this->revisionTitleLookup->getTitle( $pageId, $revId, $queryFlags );
		}

		if ( !isset( $fields['page'] ) ) {
			$fields['page'] = $title->getArticleID( $queryFlags );
		}

		// if we have a content object, use it to set the model and type
		if ( !empty( $fields['content'] ) ) {
			if ( !( $fields['content'] instanceof Content ) ) {
				throw new MWException( 'content field must contain a Content object.' );
			}

			if ( !empty( $fields['text_id'] ) ) {
				throw new MWException(
					"Text already stored in external store (id {$fields['text_id']}), " .
					"can't serialize content object"
				);
			}
		}

		// Replaces old lazy loading logic in Revision::getUserText.
		if ( !isset( $fields['user_text'] ) && isset( $fields['user'] ) ) {
			if ( $fields['user'] instanceof UserIdentity ) {
				/** @var User $user */
				$user = $fields['user'];
				$fields['user_text'] = $user->getName();
				$fields['user'] = $user->getId();
			} else {
				// TODO: wrap this in a callback to make it lazy again.
				$name = $fields['user'] === 0 ? false : User::whoIs( $fields['user'] );

				if ( $name === false ) {
					throw new MWException(
						'user_text not given, and unknown user ID ' . $fields['user']
					);
				}

				$fields['user_text'] = $name;
			}
		}

		if (
			isset( $fields['comment'] )
			&& !( $fields['comment'] instanceof CommentStoreComment )
		) {
			$commentData = isset( $fields['comment_data'] ) ? $fields['comment_data'] : null;

			if ( $fields['comment'] instanceof Message ) {
				$fields['comment'] = CommentStoreComment::newUnsavedComment(
					$fields['comment'],
					$commentData
				);
			} else {
				$commentText = trim( strval( $fields['comment'] ) );
				$fields['comment'] = CommentStoreComment::newUnsavedComment(
					$commentText,
					$commentData
				);
			}
		}

		$mainSlot = $this->emulateMainSlot_1_29( $fields, $queryFlags, $title );

		$revision = new MutableRevisionRecord( $title, $this->wikiId );
		$this->initializeMutableRevisionFromArray( $revision, $fields );
		$revision->setSlot( $mainSlot );

		return $revision;
	}

	/**
	 * Constructs a RevisionRecord for the revisions main slot, based on the MW1.29 schema.
	 *
	 * @param object|array $row Either a database row or an array
	 * @param int $queryFlags for callbacks
	 * @param Title $title
	 *
	 * @return SlotRecord The main slot, extracted from the MW 1.29 style row.
	 * @throws MWException
	 */
	private function emulateMainSlot_1_29( $row, $queryFlags, Title $title ) {
		$mainSlotRow = new stdClass();
		$mainSlotRow->role_name = 'main';

		$content = null;
		$blobData = null;
		$blobFlags = null;

		if ( is_object( $row ) ) {
			// archive row
			if ( !isset( $row->rev_id ) && isset( $row->ar_user ) ) {
				$row = $this->mapArchiveFields( $row );
			}

			if ( isset( $row->rev_text_id ) && $row->rev_text_id > 0 ) {
				$mainSlotRow->cont_address = 'tt:' . $row->rev_text_id;
			} elseif ( isset( $row->ar_id ) ) {
				$mainSlotRow->cont_address = 'ar:' . $row->ar_id;
			}

			if ( isset( $row->old_text ) ) {
				// this happens when the text-table gets joined directly, in the pre-1.30 schema
				$blobData = isset( $row->old_text ) ? strval( $row->old_text ) : null;
				// Check against selects that might have not included old_flags
				if ( !property_exists( $row, 'old_flags' ) ) {
					throw new InvalidArgumentException( 'old_flags was not set in $row' );
				}
				$blobFlags = ( $row->old_flags === null ) ? '' : $row->old_flags;
			}

			$mainSlotRow->slot_revision = intval( $row->rev_id );

			$mainSlotRow->cont_size = isset( $row->rev_len ) ? intval( $row->rev_len ) : null;
			$mainSlotRow->cont_sha1 = isset( $row->rev_sha1 ) ? strval( $row->rev_sha1 ) : null;
			$mainSlotRow->model_name = isset( $row->rev_content_model )
				? strval( $row->rev_content_model )
				: null;
			// XXX: in the future, we'll probably always use the default format, and drop content_format
			$mainSlotRow->format_name = isset( $row->rev_content_format )
				? strval( $row->rev_content_format )
				: null;
		} elseif ( is_array( $row ) ) {
			$mainSlotRow->slot_revision = isset( $row['id'] ) ? intval( $row['id'] ) : null;

			$mainSlotRow->cont_address = isset( $row['text_id'] )
				? 'tt:' . intval( $row['text_id'] )
				: null;
			$mainSlotRow->cont_size = isset( $row['len'] ) ? intval( $row['len'] ) : null;
			$mainSlotRow->cont_sha1 = isset( $row['sha1'] ) ? strval( $row['sha1'] ) : null;

			$mainSlotRow->model_name = isset( $row['content_model'] )
				? strval( $row['content_model'] ) : null;  // XXX: must be a string!
			// XXX: in the future, we'll probably always use the default format, and drop content_format
			$mainSlotRow->format_name = isset( $row['content_format'] )
				? strval( $row['content_format'] ) : null;
			$blobData = isset( $row['text'] ) ? rtrim( strval( $row['text'] ) ) : null;
			// XXX: If the flags field is not set then $blobFlags should be null so that no
			// decoding will happen. An empty string will result in default decodings.
			$blobFlags = isset( $row['flags'] ) ? trim( strval( $row['flags'] ) ) : null;

			// if we have a Content object, override mText and mContentModel
			if ( !empty( $row['content'] ) ) {
				if ( !( $row['content'] instanceof Content ) ) {
					throw new MWException( 'content field must contain a Content object.' );
				}

				/** @var Content $content */
				$content = $row['content'];
				$handler = $content->getContentHandler();

				$mainSlotRow->model_name = $content->getModel();

				// XXX: in the future, we'll probably always use the default format.
				if ( $mainSlotRow->format_name === null ) {
					$mainSlotRow->format_name = $handler->getDefaultFormat();
				}
			}
		} else {
			throw new MWException( 'Revision constructor passed invalid row format.' );
		}

		// With the old schema, the content changes with every revision.
		// ...except for null-revisions. Would be nice if we could detect them.
		$mainSlotRow->slot_inherited = 0;

		if ( $mainSlotRow->model_name === null ) {
			$mainSlotRow->model_name = function ( SlotRecord $slot ) use ( $title ) {
				// TODO: MCR: consider slot role in getDefaultModelFor()! Use LinkTarget!
				// TODO: MCR: deprecate $title->getModel().
				return ContentHandler::getDefaultModelFor( $title );
			};
		}

		if ( !$content ) {
			$content = function ( SlotRecord $slot )
			use ( $blobData, $blobFlags, $queryFlags, $mainSlotRow )
			{
				return $this->loadSlotContent(
					$slot,
					$blobData,
					$blobFlags,
					$mainSlotRow->format_name,
					$queryFlags
				);
			};
		}

		return new SlotRecord( $mainSlotRow, $content );
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
	private function loadSlotContent(
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
	private function getUserIdentityFromRowObject( $row, $prefix = 'rev_' ) {
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

	/**
	 * @see RevisionFactory::newRevisionFromRow_1_29
	 *
	 * MCR migration note: this replaces Revision::newFromRow
	 *
	 * @param object $row
	 * @param int $queryFlags
	 * @param Title|null $title
	 *
	 * @return RevisionRecord
	 * @throws MWException
	 * @throws RevisionAccessException
	 */
	private function newRevisionFromRow_1_29( $row, $queryFlags = 0, Title $title = null ) {
		Assert::parameterType( 'object', $row, '$row' );

		if ( !$title ) {
			$pageId = isset( $row->rev_page ) ? $row->rev_page : 0; // XXX: also check page_id?
			$revId = isset( $row->rev_id ) ? $row->rev_id : 0;

			$title = $this->revisionTitleLookup->getTitle( $pageId, $revId, $queryFlags );
		}

		if ( !isset( $row->page_latest ) ) {
			$row->page_latest = $title->getLatestRevID();
			if ( $row->page_latest === 0 && $title->exists() ) {
				wfWarn( 'Encountered title object in limbo: ID ' . $title->getArticleID() );
			}
		}

		$user = $this->getUserIdentityFromRowObject( $row );

		$comment = CommentStore::newKey( 'rev_comment' )
			// Legacy because $row may have come from self::selectFields()
			->getCommentLegacy( $this->getDBConnection( DB_REPLICA ), $row, true );

		$mainSlot = $this->emulateMainSlot_1_29( $row, $queryFlags, $title );
		$slots = new RevisionSlots( [ 'main' => $mainSlot ] );

		return new RevisionStoreRecord( $title, $user, $comment, $row, $slots, $this->wikiId );
	}

	/**
	 * @param MutableRevisionRecord $record
	 * @param array $fields
	 */
	private function initializeMutableRevisionFromArray(
		MutableRevisionRecord $record,
		array $fields
	) {
		/** @var UserIdentity $user */
		$user = null;

		if ( isset( $fields['user'] ) && ( $fields['user'] instanceof UserIdentity ) ) {
			$user = $fields['user'];
		} elseif ( isset( $fields['user'] ) && isset( $fields['user_text'] ) ) {
			$user = new UserIdentityValue( intval( $fields['user'] ), $fields['user_text'] );
		} elseif ( isset( $fields['user'] ) ) {
			$user = User::newFromId( intval( $fields['user'] ) );
		} elseif ( isset( $fields['user_text'] ) ) {
			$user = User::newFromName( $fields['user_text'] );

			// User::newFromName will return false for IP addresses (and invalid names)
			if ( $user == false ) {
				$user = new UserIdentityValue( 0, $fields['user_text'] );
			}
		}

		if ( $user ) {
			$record->setUser( $user );
		}

		$timestamp = isset( $fields['timestamp'] )
			? strval( $fields['timestamp'] )
			: wfTimestampNow(); // TODO: use a callback, so we can override it for testing.

		$record->setTimestamp( $timestamp );

		if ( isset( $fields['page'] ) ) {
			$record->setPageId( intval( $fields['page'] ) );
		}

		if ( isset( $fields['id'] ) ) {
			$record->setId( intval( $fields['id'] ) );
		}
		if ( isset( $fields['parent_id'] ) ) {
			$record->setParentId( intval( $fields['parent_id'] ) );
		}

		if ( isset( $fields['sha1'] ) ) {
			$record->setSha1( $fields['sha1'] );
		}
		if ( isset( $fields['size'] ) ) {
			$record->setSize( intval( $fields['size'] ) );
		}

		if ( isset( $fields['minor_edit'] ) ) {
			$record->setMinorEdit( intval( $fields['minor_edit'] ) !== 0 );
		}
		if ( isset( $fields['deleted'] ) ) {
			$record->setVisibility( intval( $fields['deleted'] ) );
		}

		if ( isset( $fields['comment'] ) ) {
			Assert::parameterType(
				CommentStoreComment::class,
				$fields['comment'],
				'$row[\'comment\']'
			);
			$record->setComment( $fields['comment'] );
		}
	}

	/**
	 * Create a new null-revision for insertion into a page's
	 * history. This will not re-save the text, but simply refer
	 * to the text from the previous version.
	 *
	 * Such revisions can for instance identify page rename
	 * operations and other such meta-modifications.
	 *
	 * MCR migration note: this replaces Revision::newNullRevision
	 *
	 * @todo Introduce newFromParentRevision(). newNullRevision can then be based on that
	 * (or go away).
	 *
	 * @param IDatabase $dbw
	 * @param Title $title Title of the page to read from
	 * @param CommentStoreComment $comment RevisionRecord's summary
	 * @param bool $minor Whether the revision should be considered as minor
	 * @param User $user The user to attribute the revision to
	 * @return RevisionRecord|null RevisionRecord or null on error
	 */
	public function newNullRevision(
		IDatabase $dbw,
		Title $title,
		CommentStoreComment $comment,
		$minor,
		User $user
	) {
		$this->checkDatabaseWikiId( $dbw, $this->wikiId );

		$fields = [ 'page_latest', 'page_namespace', 'page_title',
					'rev_id', 'rev_text_id', 'rev_len', 'rev_sha1' ];

		if ( $this->contentHandlerUseDB ) {
			$fields[] = 'rev_content_model';
			$fields[] = 'rev_content_format';
		}

		$current = $dbw->selectRow(
			[ 'page', 'revision' ],
			$fields,
			[
				'page_id' => $title->getArticleID(),
				'page_latest=rev_id',
			],
			__METHOD__,
			[ 'FOR UPDATE' ] // T51581
		);

		if ( $current ) {
			$fields = [
				'page'       => $title->getArticleID(),
				'user_text'  => $user->getName(),
				'user'       => $user->getId(),
				'comment'    => $comment,
				'minor_edit' => $minor,
				'text_id'    => $current->rev_text_id,
				'parent_id'  => $current->page_latest,
				'len'        => $current->rev_len,
				'sha1'       => $current->rev_sha1
			];

			if ( $this->contentHandlerUseDB ) {
				$fields['content_model'] = $current->rev_content_model;
				$fields['content_format'] = $current->rev_content_format;
			}

			$fields['title'] = Title::makeTitle( $current->page_namespace, $current->page_title );

			$mainSlot = $this->emulateMainSlot_1_29( $fields, 0, $title );
			$revision = new MutableRevisionRecord( $title, $this->wikiId );
			$this->initializeMutableRevisionFromArray( $revision, $fields );
			$revision->setSlot( $mainSlot );
		} else {
			$revision = null;
		}

		return $revision;
	}

	// TODO: move relevant methods from Title here, e.g. getFirstRevision, isBigDeletion, etc.

}
