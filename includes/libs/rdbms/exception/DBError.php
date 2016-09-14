<?php
/**
 * This file contains database error classes.
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
 * @ingroup Database
 */

/**
 * Database error base class
 * @ingroup Database
 */
class DBError extends Exception {
	/** @var IDatabase|null */
	public $db;

	/**
	 * Construct a database error
	 * @param IDatabase $db Object which threw the error
	 * @param string $error A simple error message to be used for debugging
	 */
	function __construct( IDatabase $db = null, $error ) {
		$this->db = $db;
		parent::__construct( $error );
	}
}

/**
 * Base class for the more common types of database errors. These are known to occur
 * frequently, so we try to give friendly error messages for them.
 *
 * @ingroup Database
 * @since 1.23
 */
class DBExpectedError extends DBError implements MessageSpecifier {
	/** @var string[] Message parameters */
	protected $params;

	function __construct( IDatabase $db = null, $error, array $params = [] ) {
		parent::__construct( $db, $error );
		$this->params = $params;
	}

	public function getKey() {
		return 'databaseerror-text';
	}

	public function getParams() {
		return $this->params;
	}
}

/**
 * @ingroup Database
 */
class DBConnectionError extends DBExpectedError {
	/**
	 * @param IDatabase $db Object throwing the error
	 * @param string $error Error text
	 */
	function __construct( IDatabase $db = null, $error = 'unknown error' ) {
		$msg = 'Cannot access the database';
		if ( trim( $error ) != '' ) {
			$msg .= ": $error";
		}

		parent::__construct( $db, $msg );
	}
}

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

	/**
	 * @param IDatabase $db
	 * @param string $error
	 * @param int|string $errno
	 * @param string $sql
	 * @param string $fname
	 */
	function __construct( IDatabase $db, $error, $errno, $sql, $fname ) {
		if ( $db instanceof DatabaseBase && $db->wasConnectionError( $errno ) ) {
			$message = "A connection error occured. \n" .
				"Query: $sql\n" .
				"Function: $fname\n" .
				"Error: $errno $error\n";
		} else {
			$message = "A database error has occurred. Did you forget to run " .
				"maintenance/update.php after upgrading?  See: " .
				"https://www.mediawiki.org/wiki/Manual:Upgrading#Run_the_update_script\n" .
				"Query: $sql\n" .
				"Function: $fname\n" .
				"Error: $errno $error\n";
		}
		parent::__construct( $db, $message );

		$this->error = $error;
		$this->errno = $errno;
		$this->sql = $sql;
		$this->fname = $fname;
	}
}

/**
 * @ingroup Database
 */
class DBReadOnlyError extends DBExpectedError {
}

/**
 * @ingroup Database
 */
class DBTransactionError extends DBExpectedError {
}

/**
 * @ingroup Database
 */
class DBTransactionSizeError extends DBTransactionError {
	function getKey() {
		return 'transaction-duration-limit-exceeded';
	}
}

/**
 * Exception class for replica DB wait timeouts
 * @ingroup Database
 */
class DBReplicationWaitError extends DBExpectedError {
}

/**
 * @ingroup Database
 */
class DBUnexpectedError extends DBError {
}

/**
 * Exception class for attempted DB access
 * @ingroup Database
 */
class DBAccessError extends DBUnexpectedError {
	public function __construct() {
		parent::__construct( "Mediawiki tried to access the database via wfGetDB(). " .
			"This is not allowed, because database access has been disabled." );
	}
}

