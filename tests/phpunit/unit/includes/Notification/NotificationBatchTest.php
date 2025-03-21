<?php

namespace MediaWiki\Tests\Notification;

use MediaWiki\Notification\Notification;
use MediaWiki\Notification\NotificationEnvelope;
use MediaWiki\Notification\NotificationsBatch;
use MediaWiki\Notification\RecipientSet;
use MediaWikiUnitTestCase;
use stdClass;

/**
 * @covers \MediaWiki\Notification\NotificationsBatch
 * @covers \MediaWiki\Notification\NotificationEnvelope
 */
class NotificationBatchTest extends MediaWikiUnitTestCase {

	public function testAdds() {
		$first = new NotificationEnvelope( new Notification( 'first' ), new RecipientSet( [] ) );
		$second = new NotificationEnvelope( new Notification( 'second' ), new RecipientSet( [] ) );

		$batch = new NotificationsBatch( $first );
		$batch->add( $second );

		$this->assertCount( 2, $batch );
		$envelopesArray = iterator_to_array( $batch );
		$this->assertSame( $first, $envelopesArray[0] );
		$this->assertSame( $second, $envelopesArray[1] );
	}

	public function testRemoveWhenExists() {
		$first = new NotificationEnvelope( new Notification( 'first' ), new RecipientSet( [] ) );
		$second = new NotificationEnvelope( new Notification( 'second' ), new RecipientSet( [] ) );
		$third = new NotificationEnvelope( new Notification( 'third' ), new RecipientSet( [] ) );
		$last = new NotificationEnvelope( new Notification( 'fourth' ), new RecipientSet( [] ) );

		$batch = new NotificationsBatch( $first, $second, $third, $last );
		$this->assertCount( 4, $batch );

		$batch->remove( $second );
		$this->assertCount( 3, $batch );
		$envelopesArray = iterator_to_array( $batch );
		$this->assertSame( $first, $envelopesArray[0] );
		$this->assertSame( $third, $envelopesArray[1] );
		$this->assertSame( $last, $envelopesArray[2] );

		$batch->remove( $first );
		$this->assertCount( 2, $batch );
		$envelopesArray = iterator_to_array( $batch );
		$this->assertSame( $third, $envelopesArray[0] );
		$this->assertSame( $last, $envelopesArray[1] );

		$batch->remove( $last );
		$this->assertCount( 1, $batch );
		$envelopesArray = iterator_to_array( $batch );
		$this->assertSame( $third, $envelopesArray[0] );
	}

	public function testRemovesWhenNotExists() {
		$first = new NotificationEnvelope( new Notification( 'first' ), new RecipientSet( [] ) );
		$second = new NotificationEnvelope( new Notification( 'second' ), new RecipientSet( [] ) );
		$other = new NotificationEnvelope( new Notification( 'third' ), new RecipientSet( [] ) );

		$batch = new NotificationsBatch( $first, $second );
		$batch->remove( $other );

		$this->assertCount( 2, $batch );
		$envelopesArray = iterator_to_array( $batch );
		$this->assertSame( $first, $envelopesArray[0] );
		$this->assertSame( $second, $envelopesArray[1] );
	}

	public function testFilters() {
		$first = new NotificationEnvelope( new Notification( 'first' ), new RecipientSet( [] ) );
		$second = new NotificationEnvelope( new Notification( 'second' ), new RecipientSet( [] ) );

		$filterMock = $this->getMockBuilder( stdClass::class )
			->addMethods( [ '__invoke' ] )
			->getMock();

		$filterMock->expects( $this->exactly( 2 ) )
			->method( '__invoke' )
			->willReturnCallback( static function ( NotificationEnvelope $envelope ) use ( $first ) {
				return $envelope->equals( $first );
			} );

		$batch = new NotificationsBatch( $first, $second );
		$batch->filter( $filterMock );
		$this->assertCount( 1, $batch );
		$envelopesArray = iterator_to_array( $batch );
		$this->assertSame( $first, $envelopesArray[0] );
	}

}
