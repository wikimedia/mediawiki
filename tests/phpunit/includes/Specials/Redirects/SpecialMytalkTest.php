<?php
namespace MediaWiki\Tests\Specials\Redirects;

use Generator;
use MediaWiki\Block\BlockManager;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\ChangeTags\ChangeTags;
use MediaWiki\Context\RequestContext;
use MediaWiki\Exception\UserNotLoggedIn;
use MediaWiki\Permissions\Authority;
use MediaWiki\Request\FauxRequest;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Specials\Redirects\SpecialMytalk;
use MediaWiki\Tests\Specials\SpecialPageTestBase;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\User\LoggedOutEditToken;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentityValue;

/**
 * @group SpecialPage
 * @group Database
 * @covers \MediaWiki\Specials\Redirects\SpecialMytalk
 * @covers \MediaWiki\Auth\AuthManager
 */
class SpecialMytalkTest extends SpecialPageTestBase {
	use TempUserTestTrait;
	use MockAuthorityTrait;

	protected function tearDown(): void {
		// Avoid leaking session over tests
		RequestContext::getMain()->getRequest()->getSession()->clear();

		parent::tearDown();
	}

	/**
	 * @inheritDoc
	 */
	protected function newSpecialPage() {
		return new SpecialMytalk(
			$this->getServiceContainer()->getTempUserConfig(),
			$this->getServiceContainer()->getTempUserCreator(),
			$this->getServiceContainer()->getAuthManager()
		);
	}

	private function testRedirect( Authority $user, string $subpage = '' ) {
		[ , $response ] = $this->executeSpecialPage(
			$subpage,
			null,
			null,
			$user
		);
		$this->assertStringContainsString(
			'User_talk:' . $user->getUser()->getName() . ( $subpage ? '/' . $subpage : '' ),
			$response->getHeader( 'LOCATION' )
		);
	}

	public function testLoggedIn() {
		$this->testRedirect( $this->mockRegisteredNullAuthority() );
	}

	public function testLoggedInWithSubpage() {
		$this->testRedirect( $this->mockRegisteredNullAuthority(), 'Testing' );
	}

	public function testTempAccount() {
		$this->enableAutoCreateTempUser();
		$this->testRedirect( $this->mockTempNullAuthority() );
	}

	public function testLoggedOut() {
		$this->disableAutoCreateTempUser();
		$this->testRedirect( $this->mockAnonNullAuthority() );
	}

	public function testLoggedOutWithTempAccountsEnabled() {
		$this->enableAutoCreateTempUser();
		$this->expectException( UserNotLoggedIn::class );
		$this->executeSpecialPage();
	}

	/** @dataProvider provideBlockOptions */
	public function testLoggedOutAndBlockedWithTempAccountsEnabled(
		string $username,
		string $blockTargetName,
		array $blockOptions
	) {
		$this->enableAutoCreateTempUser();
		$blockTarget = $this->getServiceContainer()
			->getBlockTargetFactory()
			->newFromString( $blockTargetName );

		$user = new UserIdentityValue( 0, $username );
		$block = new DatabaseBlock( [
			'target' => $blockTarget,
			'by' => $this->getTestSysop()->getUser(),
		] + $blockOptions );
		$blockManager = $this->createMock( BlockManager::class );
		$blockManager->method( 'getBlock' )->willReturn( $block );
		$this->setService( 'BlockManager', $blockManager );

		$accessible = $block->isUsertalkEditAllowed();
		if ( !$accessible ) {
			$this->expectException( UserNotLoggedIn::class );
		}
		[ $html, ] = $this->executeSpecialPage(
			'',
			null,
			null,
			$this->mockUserAuthorityWithBlock( $user, $block )
		);
		if ( $accessible ) {
			$this->assertStringContainsString( 'mytalk-appeal-submit', $html );
		} else {
			$this->assertStringNotContainsString( 'mytalk-appeal-submit', $html );
		}
	}

	/** @dataProvider provideBlockOptions */
	public function testLoggedOutAndBlockedWithTempAccountsEnabledSubmit(
		string $username,
		string $blockTargetName,
		array $blockOptions
	) {
		$this->enableAutoCreateTempUser();
		$services = $this->getServiceContainer();
		$blockTarget = $services
			->getBlockTargetFactory()
			->newFromString( $blockTargetName );

		$user = new UserIdentityValue( 0, $username );
		$block = new DatabaseBlock( [
			'target' => $blockTarget,
			'by' => $this->getTestSysop()->getUser(),
		] + $blockOptions );
		$blockManager = $this->createMock( BlockManager::class );
		$blockManager->method( 'getBlock' )->willReturn( $block );
		$this->setService( 'BlockManager', $blockManager );

		// After TempUserCreator auto-creates a new temp user, AuthManager adds the newly
		// created temp user to the main context's request's session, because AuthManager
		// is constructed using the main context. Therefore the special page must be
		// executed with the main context.
		$context = RequestContext::getMain();
		$request = new FauxRequest( [], true );
		$request->setVal( 'wpEditToken', new LoggedOutEditToken() );
		$context->setRequest( $request );
		$context->setTitle( SpecialPage::getTitleFor( 'Mytalk' ) );
		$context->setLanguage( 'qqx' );
		$context->setUser( User::newFromIdentity( $user ) );
		$context->setAuthority( $this->mockUserAuthorityWithBlock( $user, $block ) );

		$accessible = $block->isUsertalkEditAllowed();
		if ( !$accessible ) {
			$this->expectException( UserNotLoggedIn::class );
		}
		[ $html, $response ] = $this->executeSpecialPage(
			'',
			null,
			null,
			null,
			false,
			$context
		);
		if ( !$accessible ) {
			$this->assertStringContainsString( 'exception-nologin-text', $html );
			return;
		}

		$tempUser = $request->getSession()->getUser();
		$this->assertStringContainsString(
			'User_talk:' . $tempUser->getName(),
			$response->getHeader( 'LOCATION' )
		);

		$db = $this->getDb();
		$builder = $db->newSelectQueryBuilder();
		$logid = $builder
			->select( 'log_id' )
			->from( 'logging' )
			->where( [
				'log_type' => 'newusers',
				'log_action' => 'autocreate',
				'log_namespace' => NS_USER,
				'log_title' => $tempUser->getTitleKey()
			] )
			->orderBy( 'log_timestamp', $builder::SORT_DESC )
			->limit( 1 )
			->caller( __METHOD__ )
			->fetchField();
		$this->assertNotFalse( $logid, 'Auto-creation is not logged' );

		$tags = $services->getChangeTagsStore()->getTags( $db, null, null, (int)$logid );
		$ipblockAppeal = ChangeTags::TAG_IPBLOCK_APPEAL;
		if ( $block->isCreateAccountBlocked() && $block->appliesToNamespace( NS_USER_TALK ) ) {
			$this->assertSame( [ $ipblockAppeal ], $tags, "$ipblockAppeal tag missing" );
		} else {
			$this->assertSame( [], $tags, "$ipblockAppeal tag applied unexpectedly" );
		}
	}

	public static function provideBlockOptions(): Generator {
		foreach ( self::provideIpTargets() as $ipLabel => $ip ) {
			foreach ( self::provideBlockScenarios() as $scenarioLabel => $scenario ) {
				yield "$ipLabel / $scenarioLabel" => [
					'username' => $ip['username'],
					'blockTargetName' => $ip['blockTarget'],
					'blockOptions' => $scenario['blockOptions'],
				];
			}
		}
	}

	private static function provideIpTargets(): array {
		return [
			'IP address block target' => [
				'username' => '127.0.0.1',
				'blockTarget' => '127.0.0.1',
			],
			'IP range block target' => [
				'username' => '127.0.0.1',
				'blockTarget' => '127.0.0.0/31',
			],
		];
	}

	private static function provideBlockScenarios(): array {
		// ACB: Account Creation Blocked
		// TPD: Talk Page Disabled
		return [
			// The `mytalk-appeal-submit` button should be shown
			// The `mw-ipblock-appeal` tag should be applied
			'Sitewide block (ACB)' => [
				'blockOptions' => [
					'sitewide' => true,
					'createAccount' => true,
					'allowUsertalk' => true,
				],
			],
			// The `mytalk-appeal-submit` button should NOT be shown
			// The `mw-ipblock-appeal` tag should NOT be applied (*)
			'Sitewide block (TPD)' => [
				'blockOptions' => [
					'sitewide' => true,
					'createAccount' => false,
					'allowUsertalk' => false,
				],
			],
			// The `mytalk-appeal-submit` button should be shown
			// The `mw-ipblock-appeal` tag should NOT be applied
			'Partial block (NS_MAIN)' => [
				'blockOptions' => [
					'sitewide' => false,
					'restrictions' => [ new NamespaceRestriction( 1, NS_MAIN ) ],
					'createAccount' => false,
					'allowUsertalk' => true,
				],
			],
		];
	}

	public function testGetRedirectForLoggedOutUser(): void {
		$this->enableAutoCreateTempUser();

		$context = RequestContext::getMain();
		$context->setUser( $this->getServiceContainer()->getUserFactory()->newAnonymous( '1.2.3.4' ) );

		$this->assertFalse( $this->newSpecialPage()->getRedirect( '' ) );
	}
}
