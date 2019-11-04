<?php

/**
 * Exception thrown when trying to access a disabled service.
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

namespace Wikimedia\Services;

use Psr\Container\ContainerExceptionInterface;
use RuntimeException;

/**
 * Exception thrown when trying to instantiate a currently instantiating service.
 *
 * @since 1.35
 */
class RecursiveServiceDependencyException extends RuntimeException
	implements ContainerExceptionInterface {

	/**
	 * @param string $serviceName
	 */
	public function __construct( $serviceName ) {
		parent::__construct( "Recursive service instantiation: $serviceName" );
	}

}
