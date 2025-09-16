<?php

use MediaWiki\Block\BlockUser;
use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\Context\RequestContext;
use MediaWiki\Logging\LogEventsList;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\UltimateAuthority;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentity;

/**
 * @group Database
 * @covers \MediaWiki\Logging\LogEventsList::getBlockLogWarningBox
 */
class LogEventsListTest extends MediaWikiIntegrationTestCase {
	// TODO: Fully replace `sp-contributions-blocked-notice` messages with
	// the newer `blocked-notice-logextract` ones (T393902)

	/** @var UltimateAuthority */
	private static $admin;
	/** @var User */
	private static $badActor;

	protected function setUp(): void {
		parent::setUp();
		$this->overrideConfigValues( [
			MainConfigNames::LanguageCode => 'qqx',
			MainConfigNames::EnableMultiBlocks => true
		] );
	}

	public function addDBDataOnce(): void {
		self::$admin = new UltimateAuthority( $this->getTestSysop()->getUser() );
		self::$badActor = $this->getTestUser()->getUser();
	}

	private function getBlockNotice(
		?UserIdentity $user,
		?Title $title = null,
		array|callable $additionalParams = []
	): ?string {
		$services = $this->getServiceContainer();
		$context = new RequestContext();
		$context->setLanguage( 'qqx' );

		return LogEventsList::getBlockLogWarningBox(
			$services->getDatabaseBlockStore(),
			$services->getNamespaceInfo(),
			$context,
			$services->getLinkRenderer(),
			$user,
			$title,
			$additionalParams
		);
	}

	public function testBlockNoticeForNoUser() {
		$this->assertNull(
			$this->getBlockNotice( null ),
			'Should return null when no user is passed'
		);
	}

	public function testBlockNoticeForUnblockedUser() {
		$this->assertNull(
			$this->getBlockNotice( self::$badActor ),
			'Should return null for unblocked user'
		);
	}

	public static function provideSitewideBlockData(): array {
		return [
			'Blocked account' => [ static fn () => self::$badActor ],
			'Blocked IP' => [ static fn () => null ],
		];
	}

	/**
	 * @dataProvider provideSitewideBlockData
	 * Covers:
	 * - blocked-notice-logextract
	 * - blocked-notice-logextract-anon
	 */
	public function testBlockNoticeForSitewideBlock( callable $userCallback ) {
		$services = $this->getServiceContainer();
		$user = $userCallback() ?? $services->getUserFactory()->newAnonymous();
		$block = $services->getBlockUserFactory()->newBlockUser(
			$user,
			self::$admin,
			'infinity'
		)->placeBlock();
		$this->assertStatusGood( $block, 'Sitewide block was not placed' );

		$html = $this->getBlockNotice( $user );
		$this->assertNotNull( $html, 'Expected a block notice' );
		if ( $user->isAnon() ) {
			$this->assertMatchesRegularExpression(
				'/sp-contributions-blocked-notice-anon(?!-)/',
				$html,
				'Should contain sitewide anon block message'
			);
		} else {
			$this->assertMatchesRegularExpression(
				'/blocked-notice-logextract(?!-)/',
				$html,
				'Should contain sitewide block message'
			);
		}
	}

	public static function providePartialBlockData(): array {
		return [
			'Account blocked in User NS' => [ static fn () => self::$badActor, NS_USER ],
			'Account blocked in Main NS' => [ static fn () => self::$badActor, NS_MAIN ],
			'IP blocked in User NS' => [ static fn () => null, NS_USER ],
			'IP blocked in Main NS' => [ static fn () => null, NS_MAIN ],
		];
	}

	/**
	 * @dataProvider providePartialBlockData
	 * Covers:
	 * - blocked-notice-logextract-anon-partial
	 * - blocked-notice-logextract-partial
	 */
	public function testBlockNoticeForPartialBlock( callable $userCallback, int $titleNs ) {
		$services = $this->getServiceContainer();
		$user = $userCallback() ?? $services->getUserFactory()->newAnonymous();
		$block = $services->getBlockUserFactory()->newBlockUser(
			$user,
			self::$admin,
			'infinity',
			'',
			[ 'isPartial' => true ],
			[ new NamespaceRestriction( 0, NS_USER ) ]
		)->placeBlock();
		$this->assertStatusGood( $block, 'Partial block was not placed' );

		$html = $this->getBlockNotice(
			$user,
			Title::makeTitle( $titleNs, $user->getName() )
		);
		if ( $titleNs === NS_USER ) {
			$this->assertNotNull( $html, 'Expected a block notice' );
			if ( $user->isAnon() ) {
				$pattern = 'sp-contributions-blocked-notice-anon-partial';
				$error = 'Should contain partial anon block message';
			} else {
				$pattern = 'sp-contributions-blocked-notice-partial';
				$error = 'Should contain partial block message';
			}
			$this->assertStringContainsString( $pattern, $html, $error );
		} else {
			$this->assertNull( $html, 'Expected no block notice' );
		}
	}

	public static function provideMultiBlockData(): array {
		return self::provideSitewideBlockData();
	}

	/**
	 * @dataProvider provideMultiBlockData
	 * Covers:
	 * - blocked-notice-logextract-anon-multi
	 * - blocked-notice-logextract-multi
	 */
	public function testBlockNoticeForMultiBlocks( callable $userCallback ) {
		$services = $this->getServiceContainer();
		$user = $userCallback() ?? $services->getUserFactory()->newAnonymous();
		$blockUserFactory = $services->getBlockUserFactory();
		for ( $i = 1; $i <= 2; $i++ ) {
			$block = $blockUserFactory->newBlockUser(
				$user,
				self::$admin,
				'infinity',
				$i
			)->placeBlock( BlockUser::CONFLICT_NEW );
			$this->assertStatusGood( $block, "Block $i was not placed" );
		}

		$html = $this->getBlockNotice( $user );
		$this->assertNotNull( $html, 'Expected a block notice' );
		if ( $user->isAnon() ) {
			$pattern = 'sp-contributions-blocked-notice-anon-multi';
			$error = 'Should contain anon multi-block message';
		} else {
			$pattern = 'blocked-notice-logextract-multi';
			$error = 'Should contain multi-block message';
		}
		$this->assertStringContainsString( $pattern, $html, $error );
	}
}
