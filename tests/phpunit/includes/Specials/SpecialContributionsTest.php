<?php
namespace MediaWiki\Tests\Specials;

use MediaWiki\Block\BlockUser;
use MediaWiki\Block\DatabaseBlockStore;
use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\UltimateAuthority;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Specials\SpecialContributions;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\User\User;
use MediaWiki\User\UserFactory;
use TestUser;
use Wikimedia\Parsoid\Utils\DOMCompat;
use Wikimedia\Parsoid\Utils\DOMUtils;

/**
 * @author Ammarpad
 * @group Database
 * @covers \MediaWiki\Specials\SpecialContributions
 * @covers \MediaWiki\SpecialPage\ContributionsSpecialPage
 */
class SpecialContributionsTest extends SpecialPageTestBase {
	use TempUserTestTrait;

	private const CLAZZ = 'SpecialContributionTest';

	private const PAGE_NAME = self::CLAZZ . 'TestBlaBlaTest';
	/** @var Authority */
	private static $admin;
	/** @var User */
	private static $hiddenUser;
	/** @var User */
	private static $topUser;
	/** @var User */
	private static $zeroUser;
	private static int $useModWikiIPRevId;

	public function addDBDataOnce() {
		$this->overrideConfigValue(
			MainConfigNames::RangeContributionsCIDRLimit,
			[
				'IPv4' => 16,
				'IPv6' => 32,
			]
		);
		$this->setTemporaryHook(
			'SpecialContributionsBeforeMainOutput',
			static function () {
			}
		);
		self::$admin = new UltimateAuthority( $this->getTestSysop()->getUser() );
		$this->assertTrue(
			$this->editPage(
				self::PAGE_NAME, 'Test Content', 'test', NS_MAIN, self::$admin
			)->isOK(),
			'Edit failed for admin'
		);

		self::$hiddenUser = $this->getTestUser()->getUser();
		$this->assertTrue(
			$this->editPage(
				'Test', 'Test Content', 'test', NS_MAIN, self::$hiddenUser
			)->isOK(),
			'Edit failed for user'
		);

		// self::$topUser made the last edit to 'TopTest'. It should
		// not be edited by a different user after this.
		self::$topUser = $this->getTestUser( 'TopUser' )->getUser();
		$this->assertTrue(
			$this->editPage(
				'TopTest', 'Test Content', 'test', NS_MAIN, self::$topUser
			)->isOK(),
			'Edit failed for user'
		);

		// The name of this user is '0' which is a valid name.
		self::$zeroUser = ( new TestUser( '0' ) )->getUser();
		$this->assertTrue(
			$this->editPage(
				'TestPage', 'Test Content', 'test', NS_MAIN, self::$zeroUser
			)->isOK(),
			'Edit failed for user'
		);

		$this->disableAutoCreateTempUser();
		$useModWikiIP = $this->getServiceContainer()->getUserFactory()
			->newFromName( '1.2.3.xxx', UserFactory::RIGOR_NONE );
		$useModWikiIPEditStatus = $this->editPage( 'Test1234', 'Test Content', 'test', NS_MAIN, $useModWikiIP );
		$this->assertStatusGood( $useModWikiIPEditStatus, 'Edit failed for IP in usemod format' );
		static::$useModWikiIPRevId = $useModWikiIPEditStatus->getNewRevision()->getId();

		$blockStatus = $this->getServiceContainer()->getBlockUserFactory()
			->newBlockUser(
				self::$hiddenUser->getName(),
				self::$admin,
				'infinity',
				'',
				[ 'isHideUser' => true ],
			)
			->placeBlock();
		$this->assertStatusGood( $blockStatus, 'Block was not placed' );
	}

	public function testExecuteForZeroUser() {
		[ $html ] = $this->executeSpecialPage(
			self::$zeroUser->getName()
		);

		$this->assertStringContainsString( 'mw-pager-body', $html );
	}

	/**
	 * @dataProvider executeForUserWithWhitespacesDataProvider
	 */
	public function testExecuteForUserWithWhitespaces(
		string $expected,
		string $target
	): void {
		[ $html ] = $this->executeSpecialPage( $target );

		// Assert that the Javascript code in the page contains the quoted
		// username with the whitespaces removed.
		$this->assertStringContainsString(
			sprintf( '"%s"', $expected ),
			$html
		);
	}

	public static function executeForUserWithWhitespacesDataProvider(): array {
		return [
			'With an empty target' => [
				'expected' => '',
				'target' => ''
			],
			'With a target with no whitespaces' => [
				'expected' => 'TopUser',
				'target' => 'TopUser'
			],
			'With a target with leading whitespaces' => [
				'expected' => 'TopUser',
				'target' => ' TopUser'
			],
			'With a target with trailing whitespaces' => [
				'expected' => 'TopUser',
				'target' => 'TopUser '
			],
			'With a target with whitespaces at both sides' => [
				'expected' => 'TopUser',
				'target' => ' TopUser '
			],
		];
	}

	public function testExecuteEmptyTarget() {
		[ $html ] = $this->executeSpecialPage();
		// This 'topOnly' filter should always be added to Special:Contributions
		$this->assertStringContainsString( 'topOnly', $html );
		$this->assertStringNotContainsString( 'mw-pager-body', $html );
	}

	public function testExecuteHiddenTarget() {
		[ $html ] = $this->executeSpecialPage(
			self::$hiddenUser->getName()
		);
		$this->assertStringNotContainsString( 'mw-contributions-list', $html );
	}

	public function testExecuteHiddenTargetWithPermissions() {
		[ $html ] = $this->executeSpecialPage(
			self::$hiddenUser->getName(),
			null,
			'qqx',
			// This is necessary because permission checks aren't actually
			// done on the UlitmateAuthority that is self::$admin. Instead,
			// they are done on a UserAuthority. See the TODO comment in
			// User::getThisAsAuthority for more details.
			$this->getTestUser( [
				'sysop',
				'bureaucrat',
				'suppress'
			] )->getUser()
		);
		$this->assertStringContainsString( 'mw-pager-body', $html );
	}

	public function testExecuteInvalidNamespace() {
		[ $html ] = $this->executeSpecialPage(
			'::1',
			new FauxRequest( [
				'namespace' => -1,
			] )
		);
		$this->assertStringNotContainsString( 'mw-pager-body', $html );
	}

	/** @dataProvider provideExecuteNoResultsForIPTarget */
	public function testExecuteNoResultsForIPTarget( $temporaryAccountsEnabled, $expectedPageTitleMessageKey ) {
		if ( $temporaryAccountsEnabled ) {
			$this->enableAutoCreateTempUser();
		} else {
			$this->disableAutoCreateTempUser();
		}
		[ $html ] = $this->executeSpecialPage( '4.3.2.1', null, null, null, true );
		$specialPageDocument = DOMUtils::parseHTML( $html );
		$contentHtml = DOMCompat::querySelector( $specialPageDocument, '#content' )->nodeValue;
		$this->assertStringNotContainsString( 'mw-pager-body', $contentHtml );
		$this->assertStringContainsString( "($expectedPageTitleMessageKey: 4.3.2.1", $contentHtml );
	}

	public static function provideExecuteNoResultsForIPTarget() {
		return [
			'Temporary accounts not enabled' => [ false, 'contributions-title' ],
			'Temporary accounts enabled' => [
				true, 'contributions-title-for-ip-when-temporary-accounts-enabled',
			],
		];
	}

	public function testExecuteForUseModWikiIP() {
		// Regression test for T370413
		[ $html ] = $this->executeSpecialPage( '1.2.3.xxx' );
		$contributionsList = DOMCompat::querySelectorAll( DOMUtils::parseHTML( $html ), '.mw-contributions-list' );
		$this->assertCount(
			1, $contributionsList, 'Should have the contributions list as 1.2.3.xxx has made one edit'
		);
		$matchingLines = DOMCompat::querySelectorAll(
			$contributionsList[0], '[data-mw-revid="' . static::$useModWikiIPRevId . '"]'
		);
		$this->assertCount( 1, $matchingLines, "The edit made by the usemod IP is missing" );
	}

	/**
	 * @dataProvider provideTestExecuteRange
	 */
	public function testExecuteRange( $username, $shouldShowLinks ) {
		[ $html ] = $this->executeSpecialPage( $username, null, 'qqx', self::$admin, true );

		if ( $shouldShowLinks ) {
			$this->assertStringContainsString( 'blocklink', $html );
		} else {
			$this->assertStringNotContainsString( 'blocklink', $html );
			$this->assertStringContainsString( 'sp-contributions-outofrange', $html );
		}
	}

	/**
	 * @dataProvider provideTestExecuteNonRange
	 */
	public function testExecuteNonRange( $username, $shouldShowLinks ) {
		[ $html ] = $this->executeSpecialPage( $username, null, 'qqx', self::$admin, true );

		if ( $shouldShowLinks ) {
			$this->assertStringContainsString( 'blocklink', $html );
		} else {
			$this->assertStringNotContainsString( 'blocklink', $html );
		}
	}

	public static function provideTestExecuteRange() {
		yield 'Queryable IPv4 range should have blocklink for admin'
			=> [ '24.237.208.166/30', true ];
		yield 'Queryable IPv6 range should have blocklink for admin'
			=> [ '2001:DB8:0:0:0:0:0:01/43', true ];
		yield 'Unqueryable IPv4 range should not have blocklink for admin'
			=> [ '212.35.31.121/14', false ];
		yield 'Unqueryable IPv6 range should not have blocklink for admin'
			=> [ '2000::/24', false ];
	}

	public static function provideTestExecuteNonRange() {
		yield 'Valid IPv4 should have blocklink for admin' => [ '124.24.52.13', true ];
		yield 'Valid IPv6 should have blocklink for admin' => [ '2001:db8::', true ];
		yield 'Local user should have blocklink for admin' => [ 'UTSysop', true ];
		yield 'Invalid IP should not have blocklink for admin' => [ '24.237.222208.166', false ];
		yield 'External user should not have blocklink for admin' => [ 'imported>UTSysop', false ];
		yield 'Nonexistent user should not have blocklink for admin' => [ __METHOD__, false ];
	}

	public static function provideYearMonthParams() {
		yield 'Current year/month' => [
			'year' => date( 'Y' ),
			'month' => date( 'm' ),
			'expect' => true,
		];
		yield 'Old year/moth' => [
			'year' => '2007',
			'month' => '01',
			'expect' => false,
		];
		yield 'Garbage' => [
			'year' => '123garbage123',
			'month' => date( 'm' ),
			'expect' => true,
		];
	}

	/**
	 * @dataProvider provideYearMonthParams
	 */
	public function testYearMonthParams( string $year, string $month, bool $expect ) {
		[ $html ] = $this->executeSpecialPage(
			self::$admin->getUser()->getName(),
			new FauxRequest( [
				'year' => $year,
				'month' => $month,
			] ) );
		if ( $expect ) {
			$this->assertStringContainsString( self::PAGE_NAME, $html );
		} else {
			$this->assertStringNotContainsString( self::PAGE_NAME, $html );
		}
	}

	public function testBotParam() {
		[ $html ] = $this->executeSpecialPage(
			'::1',
			new FauxRequest( [
				'bot' => 1,
			] ),
			null,
			self::$admin
		);
		$this->assertStringContainsString( 'bot', $html );
	}

	public function testFeedFormat() {
		$specialPage = $this->newSpecialPage();
		[ $html ] = ( new SpecialPageExecutor() )->executeSpecialPage(
			$specialPage,
			'::1',
			new FauxRequest( [
				'feed' => 'atom',
				'namespace' => 2,
				'topOnly' => true,
				'newOnly' => true,
				'hideMinor' => true,
				'deletedOnly' => true,
				'tagfilter' => 'mw-reverted',
				'year' => '2000',
				'month' => '01',
			] )
		);
		$url = $specialPage->getOutput()->getRedirect();
		$this->assertStringContainsString( 'namespace', $url );
		$this->assertStringContainsString( 'toponly', $url );
		$this->assertStringContainsString( 'newonly', $url );
		$this->assertStringContainsString( 'hideminor', $url );
		$this->assertStringContainsString( 'deletedonly', $url );
		$this->assertStringContainsString( 'tagfilter', $url );
		$this->assertStringContainsString( 'year', $url );
		$this->assertStringContainsString( 'month', $url );
	}

	public function testCSSClasses() {
		// Regression test for T378132
		[ $html ] = $this->executeSpecialPage( self::$topUser->getName() );

		$this->assertStringContainsString( "mw-contributions-current", $html );
	}

	/**
	 * @dataProvider providePrefixSearchSubpages
	 */
	public function testPrefixSearchSubpages( $search, $expected ) {
		$specialPage = $this->newSpecialPage();
		$this->assertCount(
			$expected,
			$specialPage->prefixSearchSubpages( $search, 10, 0 )
		);
	}

	public static function providePrefixSearchSubpages() {
		return [
			'Invalid prefix' => [ '/', 0 ],
			'Valid prefix' => [ 'U', 1 ],
		];
	}

	public function testIncludesSitewideBlockClass() {
		$blockedUser = $this->getTestUser()->getUser();

		$block = $this->getServiceContainer()->getBlockUserFactory()->newBlockUser(
			$blockedUser->getUser(),
			self::$admin,
			'infinity'
		)->placeBlock();
		$this->assertStatusGood( $block, 'Failed to place sitewide block' );

		[ $html ] = $this->executeSpecialPage( $blockedUser->getName() );

		$this->assertMatchesRegularExpression(
			'/mw-contributions-blocked-notice(?!-partial)/',
			$html,
			'Sitewide block CSS class missing'
		);
	}

	public function testIncludesPartialBlockClass() {
		$blockedUser = $this->getTestUser()->getUser();

		$block = $this->getServiceContainer()->getBlockUserFactory()->newBlockUser(
			$blockedUser->getUser(),
			self::$admin,
			'infinity',
			'',
			[ 'isPartial' => true ],
			[ new NamespaceRestriction( 0, NS_MAIN ) ]
		)->placeBlock();
		$this->assertStatusGood( $block, 'Failed to place partial block' );

		[ $html ] = $this->executeSpecialPage( $blockedUser->getName() );

		$this->assertStringContainsString(
			'mw-contributions-blocked-notice-partial',
			$html,
			'Partial block CSS class missing'
		);
	}

	public function testShowsOnlyNewestActiveBlockLog() {
		$this->overrideConfigValue( MainConfigNames::EnableMultiBlocks, true );
		$services = $this->getServiceContainer();
		$blockFactory = $services->getBlockUserFactory();
		$blockedUser = $this->getTestUser()->getUser();

		// Place an infinite sitewide block that should remain active
		$blockIndef = $blockFactory->newBlockUser(
			$blockedUser->getName(),
			self::$admin,
			'infinity'
		)->placeBlock( BlockUser::CONFLICT_NEW );
		$this->assertStatusGood( $blockIndef, 'Failed to place infinite sitewide block' );

		// Place an temporary partial block that should also remain active
		$blockPartial = $blockFactory->newBlockUser(
			$blockedUser->getName(),
			self::$admin,
			'3 months',
			'',
			[ 'isPartial' => true ],
			[ new NamespaceRestriction( 0, NS_MAIN ) ]
		)->placeBlock( BlockUser::CONFLICT_NEW );
		$this->assertStatusGood( $blockPartial, 'Failed to place temporary partial block' );

		// Place a third short block, then immediately remove it to simulate an expired block
		$symbol = 'This is a symbolic block summary.';
		$blockTemp = $blockFactory->newBlockUser(
			$blockedUser->getName(),
			self::$admin,
			'1 minute',
			$symbol
		)->placeBlock( BlockUser::CONFLICT_NEW );
		$this->assertStatusGood( $blockTemp, 'Failed to place short-lived block' );

		$blocks = $services->getDatabaseBlockStore()->newListFromTarget(
			$blockedUser->getName(),
			null,
			false,
			DatabaseBlockStore::AUTO_NONE
		);
		$blockToLift = null;
		foreach ( $blocks as $block ) {
			if ( $block->getReasonComment()->text === $symbol ) {
				$blockToLift = $block;
				break;
			}
		}
		$this->assertNotNull( $blockToLift, 'Short-lived block to remove was not found in database' );

		// Remove the third block (this also creates an unblock log entry)
		$unblock = $services->getUnblockUserFactory()->newRemoveBlock(
			$blockToLift,
			self::$admin,
			''
		)->unblock();
		$this->assertStatusGood( $unblock, 'Failed to remove short-lived block' );

		[ $html ] = $this->executeSpecialPage( $blockedUser->getName() );

		// The page HTML should contain the sitewide block class even if the newest block is partial
		$this->assertMatchesRegularExpression(
			'/mw-contributions-blocked-notice(?!-partial)/',
			$html,
			'Sitewide block CSS class missing'
		);

		// The page HTML should not contain the partial block class
		$this->assertStringNotContainsString(
			'mw-contributions-blocked-notice-partial',
			$html,
			'Partial block CSS class unexpectedly included'
		);

		// The active sitewide infinite block should not appear because it's older
		$this->assertStringNotContainsString(
			'infinity',
			$html,
			'Older infinite sitewide block log incorrectly shown'
		);

		// The active partial block must be visible
		$this->assertStringContainsString(
			'3 months',
			$html,
			'Active temporary partial block log not shown'
		);

		// The lifted/expired short block should not appear
		$this->assertStringNotContainsString(
			'1 minute',
			$html,
			'Expired block log incorrectly shown'
		);

		// The unblock log itself must not appear
		$this->assertStringNotContainsString(
			'logentry-block-unblock',
			$html,
			'Unblock log incorrectly shown'
		);
	}

	protected function newSpecialPage(): SpecialContributions {
		$services = $this->getServiceContainer();

		return new SpecialContributions(
			$services->getLinkBatchFactory(),
			$services->getPermissionManager(),
			$services->getConnectionProvider(),
			$services->getRevisionStore(),
			$services->getNamespaceInfo(),
			$services->getUserNameUtils(),
			$services->getUserNamePrefixSearch(),
			$services->getUserOptionsLookup(),
			$services->getCommentFormatter(),
			$services->getUserFactory(),
			$services->getUserIdentityLookup(),
			$services->getDatabaseBlockStore(),
			$services->getUserGroupAssignmentService(),
			$services->getTempUserConfig()
		);
	}

}
