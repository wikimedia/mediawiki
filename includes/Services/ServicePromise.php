<?php
namespace MediaWiki\Services;

/**
 * Promise object allowing deferred instantiation of services from a
 * ServiceContainer.
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
use InvalidArgumentException;
use LogicException;
use MediaWiki\MediaWikiServices;
use Wikimedia\Assert\Assert;

/**
 * Promise object allowing deferred instantiation of services from a
 * ServiceContainer.
 *
 * @see MediaWikiServices
 */
class ServicePromise { // FIXME: TEST ME!

	/**
	 * @var ServiceContainer
	 */
	private $container;

	/**
	 * @var string The service name to resolve
	 */
	private $name;

	/**
	 * @var string A qualified class name. The service returned by resolve()
	 */
	private $type;

	/**
	 * @var object|null The service object, once it has been resolved.
	 */
	private $service = null;

	/**
	 * ServicePromise constructor.
	 *
	 * @param ServiceContainer $container
	 * @param string $name
	 * @param string $type
	 */
	public function __construct( ServiceContainer $container, $name, $type ) {
		Assert::parameterType( 'string', $name, '$name' );
		Assert::parameterType( 'string', $type, '$type' );

		$this->container = $container;
		$this->name = $name;
		$this->type = $type;
	}

	/**
	 * @return string A qualified class name. resolve() is guaranteed to return an
	 *         object of this type.
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param string $expectedType The type of the service the caller expects resolve() to return,
	 *               as a fully qualified class name.
	 * @param string $methodName The name of the calling method, usually given as __METHOD__.
	 *
	 * @throws InvalidArgumentException
	 */
	public function checkType( $expectedType, $methodName ) {
		if ( !is_subclass_of( $this->type, $expectedType ) ) {
			throw new InvalidArgumentException();
		}
	}

	/**
	 * @return object A service instance of the type returned by getType()
	 */
	public function resolve() {
		if ( !$this->service ) {
			$service = $this->container->getService( $this->name );

			if ( !is_subclass_of( $service, $this->type ) ) {
				throw new LogicException(
					'Resolving ' . $this->name . ' was expected to return a '
					. $this->type . ', but returned a ' . get_class( $this->service )
				);
			}

			$this->service = $service;
		}

		return $this->service;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return __CLASS__ . ': ' . $this->type . ' (' . $this->name . ')';
	}

}
