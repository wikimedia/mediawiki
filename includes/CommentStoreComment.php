<?php
/**
 * Value object for CommentStore
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
 */
use MediaWiki\MediaWikiServices;

/**
 * CommentStoreComment represents a comment stored by CommentStore. The fields
 * should be considered read-only.
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
	 * @private For use by CommentStore only. Use self::newUnsavedComment() instead.
	 * @param int|null $id
	 * @param string $text
	 * @param Message|null $message
	 * @param array|null $data
	 */
	public function __construct( $id, $text, Message $message = null, array $data = null ) {
		$this->id = $id;
		$this->text = $text;
		$this->message = $message ?: new RawMessage( '$1', [ Message::plaintextParam( $text ) ] );
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
	public static function newUnsavedComment( $comment, array $data = null ) {
		if ( $comment instanceof CommentStoreComment ) {
			return $comment;
		}

		if ( $data !== null ) {
			foreach ( $data as $k => $v ) {
				if ( substr( $k, 0, 1 ) === '_' ) {
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
