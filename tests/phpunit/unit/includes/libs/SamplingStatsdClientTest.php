<?php

use Liuggio\StatsdClient\Entity\StatsdData;
use Liuggio\StatsdClient\Sender\SenderInterface;

/**
 * @covers SamplingStatsdClient
 */
class SamplingStatsdClientTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	protected function setUp(): void {
		parent::setUp();
		if ( version_compare( PHP_VERSION, '8.2', '>=' ) ) {
			// Tracked on T326386
			$this->markTestSkipped( "PHP 8.2 isn't supported for this test" );
		}
	}

	/**
	 * @dataProvider samplingDataProvider
	 */
	public function testSampling( $data, $sampleRate, $seed, $expectWrite ) {
		if ( version_compare( PHP_VERSION, '8.3', '>=' ) ) {
			// Use of deprecated MT_RAND_PHP - T352908
			$this->markTestSkipped( "PHP 8.3 isn't supported for this test" );
		}

		$sender = $this->createMock( SenderInterface::class );
		$sender->method( 'open' )->willReturn( true );
		if ( $expectWrite ) {
			$sender->expects( $this->once() )->method( 'write' )
				->with( $this->anything(), $data );
		} else {
			$sender->expects( $this->never() )->method( 'write' );
		}
		if ( defined( 'MT_RAND_PHP' ) ) {
			mt_srand( $seed, MT_RAND_PHP );
		} else {
			mt_srand( $seed );
		}
		$client = new SamplingStatsdClient( $sender );
		$client->send( $data, $sampleRate );
	}

	public function samplingDataProvider() {
		$unsampled = new StatsdData();
		$unsampled->setKey( 'foo' );
		$unsampled->setValue( 1 );

		$sampled = new StatsdData();
		$sampled->setKey( 'foo' );
		$sampled->setValue( 1 );
		$sampled->setSampleRate( '0.1' );

		return [
			// $data, $sampleRate, $seed, $expectWrite
			[ $unsampled, 1, 0 /*0.44*/, true ],
			[ $sampled, 1, 0 /*0.44*/, false ],
			[ $sampled, 1, 4 /*0.03*/, true ],
			[ $unsampled, 0.1, 0 /*0.44*/, false ],
			[ $sampled, 0.5, 0 /*0.44*/, false ],
			[ $sampled, 0.5, 4 /*0.03*/, false ],
		];
	}

	public function testSetSamplingRates() {
		$matching = new StatsdData();
		$matching->setKey( 'foo.bar' );
		$matching->setValue( 1 );

		$nonMatching = new StatsdData();
		$nonMatching->setKey( 'oof.bar' );
		$nonMatching->setValue( 1 );

		$sender = $this->createMock( SenderInterface::class );
		$sender->method( 'open' )->willReturn( true );
		$sender->expects( $this->once() )->method( 'write' )
			->with( $this->anything(), $nonMatching );

		$client = new SamplingStatsdClient( $sender );
		$client->setSamplingRates( [ 'foo.*' => 0.2 ] );

		mt_srand( 0 ); // next random is 0.44
		$client->send( $matching );
		mt_srand( 0 );
		$client->send( $nonMatching );
	}
}
