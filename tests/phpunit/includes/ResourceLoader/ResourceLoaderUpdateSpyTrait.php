<?php

namespace MediaWiki\Tests\ResourceLoader;

use EmptyBagOStuff;
use Wikimedia\ObjectCache\WANObjectCache;

/**
 * Trait for asserting that the resource loader component is getting notified
 * about changes as expected.
 */
trait ResourceLoaderUpdateSpyTrait {

	/**
	 * Register expectations about updates that should get triggered.
	 * The parameters of this method represent known kinds of updates.
	 * If a parameter is added, tests calling this method should be forced
	 * to specify their expectations with respect to that kind of update.
	 * For this reason, this method should not be split, and all parameters
	 * should be required.
	 */
	private function expectResourceLoaderUpdates( int $wanKeyTouches ) {
		// Make sure ResourceLoaderEventIngress is triggered and updates the message
		$wanObjectCache = $this->getMockBuilder( WANObjectCache::class )
			->setConstructorArgs( [
				[ 'cache' => new EmptyBagOStuff() ]
			] )
			->onlyMethods( [ 'touchCheckKey' ] )
			->getMock();

		// this is the relevant assertion:
		$wanObjectCache->expects( $this->exactly( $wanKeyTouches ) )
			->method( 'touchCheckKey' )->with(
				$this->matchesRegularExpression( '/\bresourceloader-titleinfo\b/' )
			);

		$this->setService( 'MainWANObjectCache', $wanObjectCache );
	}

}
