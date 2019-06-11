<?php

namespace Wikimedia\Services;

use Exception;
use Psr\Container\ContainerExceptionInterface;
use RuntimeException;

/**
 * Exception thrown when trying to replace an already active service.
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
 *
 * @since 1.27
 */

/**
 * Exception thrown when trying to replace an already active service.
 */
class CannotReplaceActiveServiceException extends RuntimeException
	implements ContainerExceptionInterface {

	/**
	 * @param string $serviceName
	 * @param Exception|null $previous
	 */
	public function __construct( $serviceName, Exception $previous = null ) {
		parent::__construct( "Cannot replace an active service: $serviceName", 0, $previous );
	}

}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.33
 */
class_alias( CannotReplaceActiveServiceException::class,
	'MediaWiki\Services\CannotReplaceActiveServiceException' );
