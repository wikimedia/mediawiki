<?php
/**
 * @license GPL-2.0-or-later
 * @author Legoktm
 */

use MediaWiki\Context\DerivativeContext;
use MediaWiki\Context\RequestContext;
use MediaWiki\Hook\SpecialLogResolveLogTypeHook;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Specials\SpecialLog;

/**
 * @group Database
 * @covers \MediaWiki\Specials\SpecialLog
 */
class SpecialLogTest extends SpecialPageTestBase {

	/**
	 * Returns a new instance of the special page under test.
	 *
	 * @return SpecialPage
	 */
	protected function newSpecialPage() {
		$services = $this->getServiceContainer();
		return new SpecialLog(
			$services->getLinkBatchFactory(),
			$services->getConnectionProvider(),
			$services->getActorNormalization(),
			$services->getUserIdentityLookup(),
			$services->getUserNameUtils(),
			$services->getLogFormatterFactory()
		);
	}

	/**
	 * Verify that no exception was thrown for an invalid date
	 * @see T201411
	 */
	public function testInvalidDate() {
		[ $html, ] = $this->executeSpecialPage(
			'',
			// There is no 13th month
			new FauxRequest( [ 'wpdate' => '2018-13-01' ] ),
			'qqx'
		);
		$this->assertStringContainsString( '(log-summary)', $html );
	}

	public function testSuppressionLog() {
		// Have "BadGuy" create a revision
		$user = ( new TestUser( 'BadGuy' ) )->getUser();
		$title = $this->insertPage( 'Foo', 'Bar', null, $user )['title'];
		$revId = $title->getLatestRevID();

		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setUser( $this->getTestUser( [ 'sysop', 'suppress' ] )->getUser() );
		// Hide our revision's comment
		$list = RevisionDeleter::createList( 'revision', $context, $title, [ $revId ] );
		$status = $list->setVisibility( [
			'value' => [
				RevisionRecord::DELETED_RESTRICTED => 1,
				RevisionRecord::DELETED_COMMENT => 1
			],
			'comment' => 'SpecialLogTest'
		] );
		$this->assertStatusGood( $status );

		// Allow everyone to read the suppression log
		$this->mergeMwGlobalArrayValue(
			'wgGroupPermissions', [
				'*' => [
					'suppressionlog' => true
				]
			]
		);

		[ $html, ] = $this->executeSpecialPage(
			'suppress',
			new FauxRequest( [ 'offender' => 'BadGuy' ] ),
			'qqx'
		);
		$this->assertStringNotContainsString( '(logempty)', $html );
		$this->assertStringContainsString( '(logentry-suppress-revision', $html );

		// Full suppression log
		[ $html, ] = $this->executeSpecialPage(
			'suppress',
			new FauxRequest(),
			'qqx'
		);
		$this->assertStringNotContainsString( '(logempty)', $html );
		$this->assertStringContainsString( '(logentry-suppress-revision', $html );

		// Suppression log for unknown user should be empty
		[ $html, ] = $this->executeSpecialPage(
			'suppress',
			new FauxRequest( [ 'offender' => 'GoodGuy' ] ),
			'qqx'
		);
		$this->assertStringContainsString( '(logempty)', $html );
		$this->assertStringNotContainsString( '(logentry-suppress-revision', $html );
	}

	/**
	 * Test T389355 URL hack
	 */
	public function testPageArray() {
		$sysop = $this->getTestSysop()->getUser();
		$buf = $this->getServiceContainer()->getBlockUserFactory();
		$buf->newBlockUser( '127.0.0.1', $sysop, 'infinity' )->placeBlock();
		$buf->newBlockUser( '127.0.0.0/24', $sysop, 'infinity' )->placeBlock();
		[ $html, ] = $this->executeSpecialPage(
			'block',
			new FauxRequest( [ 'page' => [ 'User:127.0.0.1', 'User:127.0.0.0/24' ] ] ),
			'qqx'
		);
		$this->assertMatchesRegularExpression( '/logentry-block-block:.*127\.0\.0\.1/', $html );
		$this->assertMatchesRegularExpression( '/logentry-block-block:.*127\.0\.0\.0\/24/', $html );
	}

	public function testResolveLogTypeHookCall(): void {
		$sysop = $this->getTestSysop()->getUser();
		$buf = $this->getServiceContainer()->getBlockUserFactory();
		$buf->newBlockUser( '127.0.0.1', $sysop, 'infinity' )->placeBlock();

		$handlerMock = $this->createMock( SpecialLogResolveLogTypeHook::class );
		$handlerMock
			->expects( $this->once() )
			->method( 'onSpecialLogResolveLogType' )
			->with( [ 'alias-string' ] )
			->willReturnCallback( static function ( array $params, &$type ): void {
				$type = 'block';
			} );

		$this->setTemporaryHook( 'SpecialLogResolveLogType', $handlerMock );

		[ $html ] = $this->executeSpecialPage(
			'alias-string',
			new FauxRequest( [ 'page' => [ 'User:127.0.0.1' ] ] ),
			'qqx'
		);

		// Verify the page contents are as expected after resolving the alias
		$this->assertMatchesRegularExpression(
			'/logentry-block-block:(.*)127\.0\.0\.1/',
			$html
		);
	}
}
