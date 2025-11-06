<?php
namespace MediaWiki\Tests\Logging;

use MediaWiki\Block\BlockUser;
use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\Context\RequestContext;
use MediaWiki\Logging\LogEventsList;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\UltimateAuthority;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use MediaWikiIntegrationTestCase;

/**
 * @group Database
 * @covers \MediaWiki\Logging\LogEventsList::getBlockLogWarningBox
 */
class LogEventsListTest extends MediaWikiIntegrationTestCase {

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
				'/blocked-notice-logextract-anon(?!-)/',
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
				$pattern = 'blocked-notice-logextract-anon-partial';
				$error = 'Should contain partial anon block message';
			} else {
				$pattern = 'blocked-notice-logextract-partial';
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
			$pattern = 'blocked-notice-logextract-anon-multi';
			$error = 'Should contain anon multi-block message';
		} else {
			$pattern = 'blocked-notice-logextract-multi';
			$error = 'Should contain multi-block message';
		}
		$this->assertStringContainsString( $pattern, $html, $error );
	}

	public static function provideMultiIpBlockData(): array {
		$ip = '255.255.255.255';
		$cidr31 = '255.255.255.254/31';
		$cidr30 = '255.255.255.252/30';
		return [
			'Single direct IP block' => [
				'targets' => [ $ip ],
				'expected' => $ip,
				'blockId' => 1
			],
			'Direct IP + range block (direct preferred)' => [
				'targets' => [ $ip, $cidr31 ],
				'expected' => $ip,
				'blockId' => 1
			],
			'Multiple identical direct IP blocks (latest wins)' => [
				'targets' => [ $ip, $ip, $cidr31 ],
				'expected' => $ip,
				'blockId' => 2
			],
			'Interleaved IP and range blocks (latest direct IP wins)' => [
				'targets' => [ $ip, $cidr31, $ip ],
				'expected' => $ip,
				'blockId' => 3
			],
			'Single range block' => [
				'targets' => [ $cidr31 ],
				'expected' => $cidr31,
				'blockId' => 1
			],
			'Two different ranges (prefer more specific /31)' => [
				'targets' => [ $cidr31, $cidr30 ],
				'expected' => $cidr31,
				'blockId' => 1
			],
			'Duplicate range blocks (latest wins)' => [
				'targets' => [ $cidr31, $cidr31, $cidr30 ],
				'expected' => $cidr31,
				'blockId' => 2
			],
			'Interleaved range blocks (latest /31 wins)' => [
				'targets' => [ $cidr31, $cidr30, $cidr31 ],
				'expected' => $cidr31,
				'blockId' => 3
			],
		];
	}

	/**
	 * @dataProvider provideMultiIpBlockData
	 */
	public function testBlockNoticeForMultiIpBlocks( array $rawIps, string $rawIpForLog, int $blockId ) {
		$blockUserFactory = $this->getServiceContainer()->getBlockUserFactory();
		foreach ( $rawIps as $i => $ipStr ) {
			$id = $i + 1;
			$block = $blockUserFactory->newBlockUser(
				$ipStr,
				self::$admin,
				'infinity',
				$id
			)->placeBlock( BlockUser::CONFLICT_NEW );
			$this->assertStatusGood( $block, "Block #$id for $ipStr was not placed" );
		}

		$html = $this->getBlockNotice( UserIdentityValue::newAnonymous( $rawIpForLog ) );
		$this->assertNotNull( $html, 'Expected a block notice' );
		if ( count( $rawIps ) > 1 ) {
			$this->assertStringContainsString( 'blocked-notice-logextract-anon-multi', $html );
		} else {
			$this->assertMatchesRegularExpression( '/blocked-notice-logextract-anon(?!-)/', $html );
		}
		$this->assertStringContainsString( $rawIpForLog, $html,
			'The block notice does not contain a log for ' . $rawIpForLog
		);
		$this->assertStringContainsString( "(parentheses: $blockId)", $html,
			"Block log for ID: $blockId not shown"
		);
	}
}
