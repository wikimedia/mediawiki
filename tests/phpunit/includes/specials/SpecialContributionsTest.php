<?php

use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\UltimateAuthority;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Specials\SpecialContributions;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\User\UserFactory;
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

	private $pageName = __CLASS__ . 'BlaBlaTest';
	private static $admin;
	private static $user;
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
				$this->pageName, 'Test Content', 'test', NS_MAIN, self::$admin
			)->isOK(),
			'Edit failed for admin'
		);

		self::$user = $this->getTestUser()->getUser();
		$this->assertTrue(
			$this->editPage(
				'Test', 'Test Content', 'test', NS_MAIN, self::$user
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
				self::$user->getName(),
				self::$admin,
				'infinity',
				'',
				[ 'isHideUser' => true ],
			)
			->placeBlock();
		$this->assertStatusGood( $blockStatus, 'Block was not placed' );
	}

	public function testExecuteEmptyTarget() {
		[ $html ] = $this->executeSpecialPage();
		// This 'topOnly' filter should always be added to Special:Contributions
		$this->assertStringContainsString( 'topOnly', $html );
		$this->assertStringNotContainsString( 'mw-pager-body', $html );
	}

	public function testExecuteHiddenTarget() {
		[ $html ] = $this->executeSpecialPage(
			self::$user->getName()
		);
		$this->assertStringNotContainsString( 'mw-pager-body', $html );
	}

	public function testExecuteHiddenTargetWithPermissions() {
		[ $html ] = $this->executeSpecialPage(
			self::$user->getName(),
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
		yield 'Nonexistent user should not have blocklink for admin' => [ __CLASS__, false ];
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
			$this->assertStringContainsString( $this->pageName, $html );
		} else {
			$this->assertStringNotContainsString( $this->pageName, $html );
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
			$services->getDatabaseBlockStore()
		);
	}

}
