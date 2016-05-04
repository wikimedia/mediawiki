<?php
namespace MediaWiki\Services;

/**
 * Delayed loading of a global service instance.
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
use MediaWiki\MediaWikiServices;
use StubObject;
use Wikimedia\Assert\Assert;

/**
 * Delayed loading of a global service instance.
 * The service instance will be acquired from MediaWikiServices::getInstance()->getService().
 *
 * This is similar to a ServicesPromise, with some important differences: GlobalServiceStub
 * always uses the current global instance of MediaWikiServices, and it sets a global variable
 * when unstubbing. It also acts as an adapter between MediaWikiServices and StubObject.
 *
 * @see MediaWikiServices
 * @see ServicesPromise
 */
class GlobalServiceStub extends StubObject {

	/**
	 * @var string
	 */
	protected $serviceName;

	/**
	 * DeprecatedGlobal constructor.
	 *
	 * @param string $variableName
	 * @param string $serviceName
	 */
	function __construct( $variableName, $serviceName ) {
		Assert::parameterType( 'string', $variableName, '$variableName' );
		Assert::parameterType( 'string', $serviceName, '$serviceName' );

		parent::__construct( $variableName );

		$this->serviceName = $serviceName;
	}

	// @codingStandardsIgnoreStart
	// PSR2.Methods.MethodDeclaration.Underscore
	// PSR2.Classes.PropertyDeclaration.ScopeMissing
	function _newObject() {
		return MediaWikiServices::getInstance()->getService( $this->serviceName );
	}
	// @codingStandardsIgnoreEnd
}
