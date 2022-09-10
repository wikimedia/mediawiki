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

use Wikimedia\NormalizedException\INormalizedException;
use Wikimedia\NormalizedException\NormalizedExceptionTrait;

/**
 * @newable
 * @stable to extend
 * @ingroup Database
 */
class DBTransactionError extends DBExpectedError implements INormalizedException {

	use NormalizedExceptionTrait;

	/**
	 * @stable to call
	 * @param IDatabase|null $db
	 * @param string $error
	 * @param array $params parameters to be passed down to the i18n message
	 * @param \Throwable|null $prev
	 * @param array $errorParams PSR-3 message context
	 */
	public function __construct(
		?IDatabase $db, $error, array $params = [], \Throwable $prev = null, $errorParams = []
	) {
		$this->normalizedMessage = $error;
		$this->messageContext = $errorParams;
		parent::__construct(
			$db,
			self::getMessageFromNormalizedMessage( $error, $params ),
			$params,
			$prev
		);
	}
}

/**
 * @deprecated since 1.29
 */
class_alias( DBTransactionError::class, 'DBTransactionError' );
