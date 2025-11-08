<?php

use MediaWiki\Context\RequestContext;
use MediaWiki\Html\FormOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\RecentChanges\ChangesListBooleanFilterGroup;
use MediaWiki\RecentChanges\ChangesListFilterGroup;
use MediaWiki\RecentChanges\ChangesListFilterGroupContainer;
use MediaWiki\Request\FauxRequest;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\RecentChanges\ChangesListFilterGroupContainer
 */
class ChangesListFilterGroupContainerTest extends MediaWikiIntegrationTestCase {
	/**
	 * @return array{0:ChangesListFilterGroup[],1:ChangesListFilterGroupContainer}
	 */
	private function simpleSetup() {
		$groups = [
			new ChangesListBooleanFilterGroup( [
				'name' => 'changeType',
				'filters' => [],
			] ),
			new ChangesListStringOptionsFilterGroup( [
				'name' => 'userExpLevel',
				'isFullCoverage' => true,
				'queryCallable' => null,
				'default' => '',
				'filters' => [],
			] ),
			new ChangesListBooleanFilterGroup( [
				'name' => 'significance',
				'filters' => [],
			] )
		];
		$container = new ChangesListFilterGroupContainer();
		foreach ( $groups as $group ) {
			$container->registerGroup( $group );
		}
		return [ $groups, $container ];
	}

	public function testGetIterator() {
		[ $groups, $container ] = $this->simpleSetup();
		$result = [];
		foreach ( $container as $g ) {
			$result[] = $g;
		}
		$this->assertSame( $groups, $result );
	}

	public function testGetGroup() {
		[ $groups, $container ] = $this->simpleSetup();
		$this->assertSame( $groups[0], $container->getGroup( 'changeType' ) );
		$this->assertNull( $container->getGroup( 'nonexistent' ) );
	}

	/**
	 * @param array $parameters
	 * @return array{0:FormOptions,1:ChangesListFilterGroupContainer}
	 */
	private function integratedSetup( $parameters ) {
		$context = new RequestContext;
		$context->setRequest( new FauxRequest( $parameters ) );

		$user = $this->createMock( User::class );
		// Enable patrol group
		$user->method( 'useRCPatrol' )->willReturn( true );
		// Enable watchlist group
		$user->method( 'isRegistered' )->willReturn( true );
		$user->method( 'isAllowed' )->willReturn( true );
		$context->setUser( $user );

		/** @var SpecialRecentChanges $page */
		$page = $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'Recentchanges' );
		$page->setContext( $context );
		$opts = $page->setup( '' );
		$tpage = TestingAccessWrapper::newFromObject( $page );

		return [ $opts, $tpage->filterGroups ];
	}

	public function testHasGroup() {
		[ $groups, $container ] = $this->simpleSetup();
		$this->assertTrue( $container->hasGroup( 'changeType' ) );
		$this->assertFalse( $container->hasGroup( 'nonexistent' ) );
	}

	public static function provideAreFiltersInConflict() {
		return [
			[
				"parameters" => [],
				"expectedConflicts" => false,
			],
			[
				"parameters" => [
					"hideliu" => true,
					"userExpLevel" => "newcomer",
				],
				"expectedConflicts" => false,
			],
			[
				"parameters" => [
					"hideanons" => true,
					"userExpLevel" => "learner",
				],
				"expectedConflicts" => false,
			],
			[
				"parameters" => [
					"hidemajor" => true,
					"hidenewpages" => true,
					"hidepageedits" => true,
					"hidecategorization" => false,
					"hidelog" => true,
					"hideWikidata" => true,
				],
				"expectedConflicts" => true,
			],
			[
				"parameters" => [
					"hidemajor" => true,
					"hidenewpages" => false,
					"hidepageedits" => true,
					"hidecategorization" => false,
					"hidelog" => false,
					"hideWikidata" => true,
				],
				"expectedConflicts" => true,
			],
			[
				"parameters" => [
					"hidemajor" => true,
					"hidenewpages" => false,
					"hidepageedits" => false,
					"hidecategorization" => true,
					"hidelog" => true,
					"hideWikidata" => true,
				],
				"expectedConflicts" => false,
			],
			[
				"parameters" => [
					"hideminor" => true,
					"hidenewpages" => true,
					"hidepageedits" => true,
					"hidecategorization" => false,
					"hidelog" => true,
					"hideWikidata" => true,
				],
				"expectedConflicts" => false,
			],
			[
				"parameters" => [
					"hidenewpages" => true,
					"hidepageedits" => true,
					"hidecategorization" => false,
					"hidelog" => true,
					"hidenewuserlog" => true,
					"hideWikidata" => true,
					"reviewStatus" => "unpatrolled",
				],
				"expectedConflicts" => true,
			],
			[
				"parameters" => [
					"hidenewpages" => true,
					"hidepageedits" => true,
					"hidecategorization" => false,
					"hidelog" => true,
					"hidenewuserlog" => true,
					"hideWikidata" => true,
					"reviewStatus" => "unpatrolled;manual",
				],
				"expectedConflicts" => true,
			],
			[
				"parameters" => [
					"hidenewpages" => true,
					"hidepageedits" => true,
					"hidecategorization" => false,
					"hidelog" => true,
					"hidenewuserlog" => true,
					"hideWikidata" => true,
					"reviewStatus" => "unpatrolled;auto",
				],
				"expectedConflicts" => false,
			],
		];
	}

	/**
	 * @covers \MediaWiki\RecentChanges\ChangesListFilterGroup::getConflictingFilters
	 * @covers \MediaWiki\RecentChanges\ChangesListFilter::getConflictingFilters
	 * @covers \MediaWiki\RecentChanges\ChangesListFilter::activelyInConflictWithFilter
	 * @dataProvider provideAreFiltersInConflict
	 */
	public function testAreFiltersInConflict( $parameters, $expectedConflicts ) {
		$this->overrideConfigValue( MainConfigNames::RCWatchCategoryMembership, true );
		[ $formOpts, $container ] = $this->integratedSetup( $parameters );

		$this->assertSame(
			$expectedConflicts,
			$container->areFiltersInConflict( $formOpts )
		);
	}

	public static function provideAddOptions() {
		$bools = [ false, true ];
		return ArrayUtils::cartesianProduct( $bools, $bools );
	}

	/**
	 * Trivial unit test
	 * @dataProvider provideAddOptions
	 * @param bool $allowDefaults
	 * @param bool $structured
	 */
	public function testAddOptions( $allowDefaults, $structured ) {
		$opts = new FormOptions;
		$createMockGroup = function ( $name ) use ( $opts, $allowDefaults, $structured ) {
			$group = $this->createMock( ChangesListFilterGroup::class );
			$group->expects( $this->once() )->method( 'addOptions' )
				->with( $opts, $allowDefaults, $structured );
			$group->method( 'getName' )->willReturn( $name );
			return $group;
		};
		$group1 = $createMockGroup( 'a' );
		$group2 = $createMockGroup( 'b' );
		$container = new ChangesListFilterGroupContainer();
		$container->registerGroup( $group1 );
		$container->registerGroup( $group2 );
		$container->addOptions( $opts, $allowDefaults, $structured );
	}
}
