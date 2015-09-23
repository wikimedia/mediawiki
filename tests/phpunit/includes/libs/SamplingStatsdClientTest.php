<?php

use Liuggio\StatsdClient\Entity\StatsdData;

class SamplingStatsdClientTest extends PHPUnit_Framework_TestCase {
	/**
	 * @dataProvider samplingDataProvider
	 */
	public function testSampling( $data, $sampleRate, $seed, $expectWrite ) {
		$sender = $this->getMock( 'Liuggio\StatsdClient\Sender\SenderInterface' );
		$sender->expects( $this->any() )->method( 'open' )->will( $this->returnValue( true ) );
		if ( $expectWrite ) {
			$sender->expects( $this->once() )->method( 'write' )
				->with( $this->anything(), $this->equalTo( $data ) );
		} else {
			$sender->expects( $this->never() )->method( 'write' );
		}
		mt_srand( $seed );
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

		return array(
			// $data, $sampleRate, $seed, $expectWrite
			array( $unsampled, 1, 0 /*0.44*/, $unsampled ),
			array( $sampled, 1, 0 /*0.44*/, null ),
			array( $sampled, 1, 4 /*0.03*/, $sampled ),
			array( $unsampled, 0.1, 4 /*0.03*/, $sampled ),
			array( $sampled, 0.5, 0 /*0.44*/, null ),
			array( $sampled, 0.5, 4 /*0.03*/, $sampled ),
		);
	}
}
