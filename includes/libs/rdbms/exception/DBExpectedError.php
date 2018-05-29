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
 */

namespace Wikimedia\Rdbms;

use MessageSpecifier;

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

	/**
	 * @param IDatabase|null $db
	 * @param string $error
	 * @param array $params
	 * @param \Exception|\Throwable|null $prev
	 */
	public function __construct(
		IDatabase $db = null, $error, array $params = [], $prev = null
	) {
		parent::__construct( $db, $error, $prev );
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
 * @deprecated since 1.29
 */
class_alias( DBExpectedError::class, 'DBExpectedError' );
