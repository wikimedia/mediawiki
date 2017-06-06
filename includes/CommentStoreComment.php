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

use Wikimedia\Rdbms\IDatabase;

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
	 * @private For use by CommentStore only
	 * @param int|null $id
	 * @param string $text
	 * @param Message|null $message
	 * @param array|null $data
	 */
	public function __construct( $id, $text, Message $message = null, array $data = null ) {
		$this->id = $id;
		$this->text = $text;
		$this->message = $message ?: new RawMessage( '$1', [ $text ] );
		$this->data = $data;
	}
}
