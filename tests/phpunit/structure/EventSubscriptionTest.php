<?php
/**
 * @license GPL-2.0-or-later
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
