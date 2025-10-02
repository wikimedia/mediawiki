<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Integration\Notification;

use MediaWiki\MediaWikiServices;
use MediaWiki\Notification\MiddlewareException;
use MediaWiki\Notification\Notification;
use MediaWiki\Notification\NotificationMiddlewareInterface;
use MediaWiki\Notification\RecipientSet;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\User\UserIdentity;
use MediaWikiIntegrationTestCase;
use Wikimedia\ScopedCallback;

/**
 * @group Mail
 * @covers \MediaWiki\Notification\MiddlewareChain
 */
class MiddlewareChainTest extends MediaWikiIntegrationTestCase {

	/**
	 * Test case when Middleware calls NotificationService::notify() to inject new notification
	 * This can cause endless loops where Middleware triggers a notification, that triggers the
	 * middleware again.
	 *
	 * @return void
	 */
	public function testMiddlewareCannotTriggerNotificationService() {
		$this->expectException( MiddlewareException::class );

		$scope = ExtensionRegistry::getInstance()->setAttributeForTest(
			'NotificationMiddleware', [
				[
					"factory" => static function ()  {
						return new class implements NotificationMiddlewareInterface {
							public function handle( $batch, callable $next ): void {
								MediaWikiServices::getInstance()
									->getNotificationService()
									->notify(
										new Notification( "bad" ), new RecipientSet( [] )
									);
								$next();
							}
						};
					},
				],
			]
		);
		$sut = MediaWikiServices::getInstance()->getNotificationService();
		$user = $this->createMock( UserIdentity::class );
		$sut->notify(
			new Notification( "good" ), new RecipientSet( [ $user ] )
		);
		ScopedCallback::consume( $scope );
	}
}
