<?php

use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\UltimateAuthority;

/**
 * @author Ammarpad
 * @group Database
 * @covers SpecialContributions
 */
class SpecialContributionsTest extends SpecialPageTestBase {
	private $pageName = __CLASS__ . 'BlaBlaTest';
	private $admin;

	protected function setUp(): void {
		parent::setUp();
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
				return;
			}
		);
		$this->admin = new UltimateAuthority( $this->getTestSysop()->getUser() );
		$this->assertTrue(
			$this->editPage(
				$this->pageName, 'Test Content', 'test', NS_MAIN, $this->admin
			)->isOK(),
			'Admin contributed'
		);
	}

	/**
	 * @covers SpecialContributions::execute
	 * @dataProvider provideTestExecuteRange
	 */
	public function testExecuteRange( $username, $shouldShowLinks ) {
		list( $html ) = $this->executeSpecialPage( $username, null, 'qqx', $this->admin, true );

		if ( $shouldShowLinks ) {
			$this->assertStringContainsString( 'blocklink', $html );
		} else {
			$this->assertStringNotContainsString( 'blocklink', $html );
			$this->assertStringContainsString( 'sp-contributions-outofrange', $html );
		}
	}

	/**
	 * @covers SpecialContributions::execute
	 * @dataProvider provideTestExecuteNonRange
	 */
	public function testExecuteNonRange( $username, $shouldShowLinks ) {
		list( $html ) = $this->executeSpecialPage( $username, null, 'qqx', $this->admin, true );

		if ( $shouldShowLinks ) {
			$this->assertStringContainsString( 'blocklink', $html );
		} else {
			$this->assertStringNotContainsString( 'blocklink', $html );
		}
	}

	public function provideTestExecuteRange() {
		yield 'Queryable IPv4 range should have blocklink for admin'
			=> [ '24.237.208.166/30', true ];
		yield 'Queryable IPv6 range should have blocklink for admin'
			=> [ '2001:DB8:0:0:0:0:0:01/43', true ];
		yield 'Unqueryable IPv4 range should not have blocklink for admin'
			=> [ '212.35.31.121/14', false ];
		yield 'Unqueryable IPv6 range should not have blocklink for admin'
			=> [ '2000::/24', false ];
	}

	public function provideTestExecuteNonRange() {
		yield 'Valid IPv4 should have blocklink for admin' => [ '124.24.52.13', true ];
		yield 'Valid IPv6 should have blocklink for admin' => [ '2001:db8::', true ];
		yield 'Local user should have blocklink for admin' => [ 'UTSysop', true ];
		yield 'Invalid IP should not have blocklink for admin' => [ '24.237.222208.166', false ];
		yield 'External user should not have blocklink for admin' => [ 'imported>UTSysop', false ];
		yield 'Nonexistent user should not have blocklink for admin' => [ __CLASS__, false ];
	}

	public function provideYearMonthParams() {
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
	 * @covers SpecialContributions::execute
	 * @dataProvider provideYearMonthParams
	 */
	public function testYearMonthParams( string $year, string $month, bool $expect ) {
		[ $html ] = $this->executeSpecialPage(
			$this->admin->getUser()->getName(),
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

	protected function newSpecialPage(): SpecialContributions {
		$services = $this->getServiceContainer();

		return new SpecialContributions(
			$services->getLinkBatchFactory(),
			$services->getPermissionManager(),
			$services->getDBLoadBalancer(),
			$services->getActorMigration(),
			$services->getRevisionStore(),
			$services->getNamespaceInfo(),
			$services->getUserNameUtils(),
			$services->getUserNamePrefixSearch(),
			$services->getUserOptionsLookup()
		);
	}

}
