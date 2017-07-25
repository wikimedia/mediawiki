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
 * @ingroup Database
 */

namespace Wikimedia\Rdbms;

use Message;

/**
 * @ingroup Database
 */
class DBQueryError extends DBExpectedError {
	/** @var string */
	public $error;
	/** @var integer */
	public $errno;
	/** @var string */
	public $sql;
	/** @var string */
	public $fname;

	/** @var Message */
	private $messageObject;

	/**
	 * @param IDatabase $db
	 * @param string $error
	 * @param int|string $errno
	 * @param string $sql
	 * @param string $fname
	 */
	function __construct( IDatabase $db, $error, $errno, $sql, $fname ) {
		$key = ( $db instanceof Database && $db->wasConnectionError( $errno ) )
			? 'databaseerror-connection' : 'databaseerror-real';
		$this->messageObject = new Message( $key, [ $sql, $fname, $errno, $error ] );

		$message = Message::newFromSpecifier( $this->messageObject )->useDatabase( false )
			->inLanguage( 'en' )->plain();
		parent::__construct( $db, $message );

		$this->error = $error;
		$this->errno = $errno;
		$this->sql = $sql;
		$this->fname = $fname;
	}

	/**
	 * @inheritDoc
	 */
	public function getKey() {
		return $this->messageObject->getKey();
	}

	/**
	 * @inheritDoc
	 */
	public function getParams() {
		return $this->messageObject->getParams();
	}

	/**
	 * @inheritDoc
	 */
	public function getMessageObject() {
		return $this->messageObject;
	}
}

class_alias( DBQueryError::class, 'DBQueryError' );
