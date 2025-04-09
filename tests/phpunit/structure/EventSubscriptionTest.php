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
 */

use MediaWiki\DomainEvent\DomainEventSource;
use MediaWiki\DomainEvent\EventDispatchEngine;
use MediaWiki\Registration\ExtensionRegistry;
use Wikimedia\TestingAccessWrapper;

/**
 * Validates event subscriber registration of all loaded extensions and skins.
 * @coversNothing
 */
class EventSubscriptionTest extends MediaWikiIntegrationTestCase {

	private function newSpyEvenSource( &$events ): DomainEventSource {
		$services = $this->getServiceContainer();

		$dispatcher = $this->getMockBuilder( EventDispatchEngine::class )
			->setConstructorArgs( [
				$services->getObjectFactory(),
				$services->getHookContainer()
			] )
			->onlyMethods( [ 'registerListener' ] )
			->getMock();

		$dispatcher->method( 'registerListener' )
			->willReturnCallback( static function ( $event ) use ( &$events ) {
				$events[] = $event;
			} );

		return $dispatcher;
	}

	public static function provideEventIngressesSpecs() {
		$subscriberSpecs = ExtensionRegistry::getInstance()
			->getAttribute( 'DomainEventIngresses' );

		foreach ( $subscriberSpecs as $spec ) {
			yield [ $spec ];
		}
	}

	/**
	 * This checks that domain event subscribers actually subscriber for the
	 * events that they declare in the extension registration.
	 * @dataProvider provideEventIngressesSpecs
	 */
	public function testPassesValidation( $spec ) {
		$this->assertArrayHasKey( 'events', $spec );
		$expected = $spec['events'];

		$actual = [];
		$source = $this->newSpyEvenSource( $actual );
		$source = TestingAccessWrapper::newFromObject( $source );

		$source->applySubscriberSpec( $spec );

		$path = $spec['extensionPath'];
		$this->assertEquals( $expected, $actual, "Events subscribed to in $path" );
	}
}
