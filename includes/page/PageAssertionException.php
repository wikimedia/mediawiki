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

namespace MediaWiki\Page;

use InvalidArgumentException;
use Throwable;
use Wikimedia\NormalizedException\INormalizedException;
use Wikimedia\NormalizedException\NormalizedExceptionTrait;

/**
 * Exception if a PageIdentity is an invalid argument
 *
 * @newable
 * @since 1.38
 */
class PageAssertionException extends InvalidArgumentException implements INormalizedException {

	use NormalizedExceptionTrait;

	/**
	 * @stable to call
	 * @param string $normalizedMessage The exception message, with PSR-3 style placeholders.
	 * @param array $messageContext Message context, with values for the placeholders.
	 * @param int $code The exception code.
	 * @param Throwable|null $previous The previous throwable used for the exception chaining.
	 */
	public function __construct(
		string $normalizedMessage = '',
		array $messageContext = [],
		int $code = 0,
		?Throwable $previous = null
	) {
		$this->normalizedMessage = $normalizedMessage;
		$this->messageContext = $messageContext;
		parent::__construct(
			self::getMessageFromNormalizedMessage( $normalizedMessage, $messageContext ),
			$code,
			$previous
		);
	}
}
