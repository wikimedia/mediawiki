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

namespace MediaWiki\Session;

use Exception;
use UnexpectedValueException;

/**
 * Subclass of UnexpectedValueException annotated with data for debug logs.
 *
 * @newable
 * @since 1.27
 * @ingroup Session
 * @copyright © 2016 Wikimedia Foundation and contributors
 */
class MetadataMergeException extends UnexpectedValueException {
	/** @var array */
	protected $context;

	/**
	 * @param string $message
	 * @param int $code
	 * @param Exception|null $previous
	 * @param array $context Additional context data
	 */
	public function __construct(
		$message = '',
		$code = 0,
		?Exception $previous = null,
		array $context = []
	) {
		parent::__construct( $message, $code, $previous );
		$this->context = $context;
	}

	/**
	 * Get context data.
	 * @return array
	 */
	public function getContext() {
		return $this->context;
	}

	/**
	 * Set context data.
	 */
	public function setContext( array $context ) {
		$this->context = $context;
	}
}
