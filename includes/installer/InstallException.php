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

namespace MediaWiki\Installer;

use Throwable;

/**
 * Exception thrown if an error occur which installation
 * @ingroup Exception
 */
class InstallException extends \MWException {
	/**
	 * @var \Status State when an exception occurs
	 */
	private $status;

	/**
	 * InstallException constructor.
	 * @param \Status $status State when an exception occurs
	 * @param string $message The Exception message to throw
	 * @param int $code The Exception code
	 * @param Throwable|null $previous The previous throwable used for the exception chaining
	 */
	public function __construct( \Status $status, $message = '', $code = 0,
		Throwable $previous = null ) {
		parent::__construct( $message, $code, $previous );
		$this->status = $status;
	}

	public function getStatus() : \Status {
		return $this->status;
	}
}
