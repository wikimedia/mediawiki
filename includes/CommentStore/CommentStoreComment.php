<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\CommentStore;

use InvalidArgumentException;
use MediaWiki\Language\RawMessage;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;

/**
 * Value object for a comment stored by CommentStore.
 *
 * The fields should be considered read-only.
 *
 * @ingroup CommentStore
 * @since 1.30
 */
class CommentStoreComment {

	/** @var int|null Comment ID, if any */
	public $id;

	/** @var string Text version of the comment */
	public $text;

	/** @var Message Message version of the comment. Might be a RawMessage */
	public $message;

	/** @var array|null Structured data of the comment */
	public $data;

	/**
	 * @internal For use by CommentStore only. Use self::newUnsavedComment() instead.
	 * @param int|null $id
	 * @param string $text
	 * @param Message|null $message
	 * @param array|null $data
	 */
	public function __construct( $id, string $text, ?Message $message = null, ?array $data = null ) {
		$this->id = (int)$id;
		$this->text = $text;
		$this->message = $message
			?: new RawMessage(
				'$1',
				[ Message::plaintextParam( $this->text ) ]
			);
		$this->data = $data;
	}

	/**
	 * Create a new, unsaved CommentStoreComment
	 *
	 * @param string|Message|CommentStoreComment $comment Comment text or Message object.
	 *  A CommentStoreComment is also accepted here, in which case it is returned unchanged.
	 * @param array|null $data Structured data to store. Keys beginning with '_' are reserved.
	 *  Ignored if $comment is a CommentStoreComment.
	 * @return CommentStoreComment
	 */
	public static function newUnsavedComment( $comment, ?array $data = null ) {
		if ( $comment instanceof CommentStoreComment ) {
			return $comment;
		}

		if ( $data !== null ) {
			foreach ( $data as $k => $v ) {
				if ( str_starts_with( $k, '_' ) ) {
					throw new InvalidArgumentException( 'Keys in $data beginning with "_" are reserved' );
				}
			}
		}

		if ( $comment instanceof Message ) {
			$message = clone $comment;
			// Avoid $wgForceUIMsgAsContentMsg
			$text = $message->inLanguage( MediaWikiServices::getInstance()->getContentLanguage() )
				->setInterfaceMessageFlag( true )
				->text();
			return new CommentStoreComment( null, $text, $message, $data );
		} else {
			return new CommentStoreComment( null, $comment, null, $data );
		}
	}
}

// This alias can not be removed, because serialized instances of this class are stored in Echo
// tables, until we either migrate to JSON serialization (T325703) or expire those events (T383948).
/** @deprecated class alias since 1.40 */
class_alias( CommentStoreComment::class, 'CommentStoreComment' );
