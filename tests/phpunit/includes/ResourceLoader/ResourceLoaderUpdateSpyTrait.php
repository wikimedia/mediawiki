<?php

namespace MediaWiki\Tests\ResourceLoader;

use Wikimedia\ObjectCache\EmptyBagOStuff;
use Wikimedia\ObjectCache\WANObjectCache;

/**
 * Trait for asserting that the resource loader component is getting notified
 * about changes as expected.
 */
trait ResourceLoaderUpdateSpyTrait {

	/** @var ?int */
	private $expectedResourceLoaderWanKeyTouches = null;

	/** @var int */
	private $actualResourceLoaderWanKeyTouches = 0;

	/**
	 * Register expectations about updates that should get triggered.
	 * The parameters of this method represent known kinds of updates.
	 * If a parameter is added, tests calling this method should be forced
	 * to specify their expectations with respect to that kind of update.
	 * For this reason, this method should not be split, and all parameters
	 * should be required.
	 */
	private function expectResourceLoaderUpdates( int $wanKeyTouches ) {
		$this->expectedResourceLoaderWanKeyTouches =
			( $this->expectedResourceLoaderWanKeyTouches ?? 0 ) + $wanKeyTouches;

		// Make sure ResourceLoaderEventIngress is triggered and updates the message
		$wanObjectCache = $this->getMockBuilder( WANObjectCache::class )
			->setConstructorArgs( [
				[ 'cache' => new EmptyBagOStuff() ]
			] )
			->onlyMethods( [ 'touchCheckKey' ] )
			->getMock();

		// this is the relevant assertion:
		$wanObjectCache->method( 'touchCheckKey' )->willReturnCallback(
			function ( $key ) {
				if ( preg_match( '/\bresourceloader-titleinfo\b/', $key ) ) {
					$this->actualResourceLoaderWanKeyTouches++;
				}
			}
		);
		$this->setService( 'MainWANObjectCache', $wanObjectCache );
	}

	/**
	 * @postCondition
	 */
	public function wanKeyTouchCountPostConditions() {
		if ( $this->expectedResourceLoaderWanKeyTouches !== null ) {
			$this->assertSame(
				$this->expectedResourceLoaderWanKeyTouches,
				$this->actualResourceLoaderWanKeyTouches,
				'Expected number of ReasourceLoader module cache resets'
			);
		}
	}

}
