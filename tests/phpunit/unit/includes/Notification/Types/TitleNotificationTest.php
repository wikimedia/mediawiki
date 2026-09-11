<?php

namespace MediaWiki\Tests\Notification\Types;

use MediaWiki\Notification\AgentAware;
use MediaWiki\Notification\TitleAware;
use MediaWiki\Notification\Types\TitleNotification;
use MediaWiki\Page\PageIdentityValue;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\Notification\Types\TitleNotification
 */
class TitleNotificationTest extends MediaWikiUnitTestCase {

	public function testConstruct() {
		$title = PageIdentityValue::localIdentity( 1, NS_MAIN, 'Test' );
		$notification = new TitleNotification( 'test', $title, [ 'key' => 'value' ] );

		$this->assertInstanceOf( TitleAware::class, $notification );
		$this->assertNotInstanceOf( AgentAware::class, $notification );
		$this->assertSame( 'test', $notification->getType() );
		$this->assertSame( $title, $notification->getTitle() );
		$this->assertSame( [ 'key' => 'value' ], $notification->getProperties() );
	}

}
