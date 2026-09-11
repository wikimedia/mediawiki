<?php

namespace MediaWiki\Tests\Notification\Types;

use MediaWiki\Notification\AgentAware;
use MediaWiki\Notification\TitleAware;
use MediaWiki\Notification\Types\AgentNotification;
use MediaWiki\User\UserIdentityValue;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\Notification\Types\AgentNotification
 */
class AgentNotificationTest extends MediaWikiUnitTestCase {

	public function testConstruct() {
		$agent = new UserIdentityValue( 1, 'Agent' );
		$notification = new AgentNotification( 'test', $agent, [ 'key' => 'value' ] );

		$this->assertInstanceOf( AgentAware::class, $notification );
		$this->assertNotInstanceOf( TitleAware::class, $notification );
		$this->assertSame( 'test', $notification->getType() );
		$this->assertSame( $agent, $notification->getAgent() );
		$this->assertSame( [ 'key' => 'value' ], $notification->getProperties() );
	}

}
