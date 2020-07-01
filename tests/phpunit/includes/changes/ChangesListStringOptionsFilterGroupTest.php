<?php

use Wikimedia\TestingAccessWrapper;

/**
 * @covers ChangesListStringOptionsFilterGroup
 */
class ChangesListStringOptionsFilterGroupTest extends MediaWikiIntegrationTestCase {

	public function testIsFullCoverage() {
		$falseGroup = TestingAccessWrapper::newFromObject(
			new ChangesListStringOptionsFilterGroup( [
				'name' => 'group',
				'filters' => [],
				'isFullCoverage' => false,
				'queryCallable' => function () {
				},
				'default' => '',
			] )
		);

		$this->assertSame(
			false,
			$falseGroup->isFullCoverage
		);

		$this->expectException( MWException::class );
		$this->expectExceptionMessage( 'You must specify isFullCoverage' );
		$undefinedFullCoverageGroup = new ChangesListStringOptionsFilterGroup( [
			'name' => 'othergroup',
			'filters' => [],
		] );
	}

	/**
	 * @param array $filterDefinitions Array of filter definitions
	 * @param array $expectedValues Array of values callback should receive
	 * @param string $input Value in URL
	 *
	 * @dataProvider provideModifyQuery
	 */
	public function testModifyQuery( $filterDefinitions, $expectedValues, $input ) {
		$queryCallable = function (
			$className,
			$ctx,
			$dbr,
			&$tables,
			&$fields,
			&$conds,
			&$query_options,
			&$join_conds,
			$actualSelectedValues
		) use ( $expectedValues ) {
			$this->assertSame(
				$expectedValues,
				$actualSelectedValues
			);
		};

		$groupDefinition = [
			'name' => 'group',
			'default' => '',
			'isFullCoverage' => true,
			'filters' => $filterDefinitions,
			'queryCallable' => $queryCallable,
		];

		$this->modifyQueryHelper( $groupDefinition, $input );
	}

	public function provideModifyQuery() {
		$mixedFilters = [
			[
				'name' => 'foo',
			],
			[
				'name' => 'baz',
			],
			[
				'name' => 'goo'
			],
		];

		return [
			[
				$mixedFilters,
				[ 'baz', 'foo', ],
				'foo;bar;BaZ;invalid',
			],

			[
				$mixedFilters,
				[ 'baz', 'foo', 'goo' ],
				'all',
			],
		];
	}

	/**
	 * @param array $filterDefinitions Array of filter definitions
	 * @param string $input Value in URL
	 * @param string $message Message thrown by exception
	 *
	 * @dataProvider provideNoOpModifyQuery
	 */
	public function testNoOpModifyQuery( $filterDefinitions, $input, $message ) {
		$noFiltersAllowedCallable = function (
			$className,
			$ctx,
			$dbr,
			&$tables,
			&$fields,
			&$conds,
			&$query_options,
			&$join_conds,
			$actualSelectedValues
		) use ( $message ) {
			throw new MWException( $message );
		};

		$groupDefinition = [
			'name' => 'group',
			'default' => '',
			'isFullCoverage' => true,
			'filters' => $filterDefinitions,
			'queryCallable' => $noFiltersAllowedCallable,
		];

		$this->modifyQueryHelper( $groupDefinition, $input );

		$this->assertTrue(
			true,
			'Test successfully completed without calling queryCallable'
		);
	}

	public function provideNoOpModifyQuery() {
		$noFilters = [];

		$normalFilters = [
			[
				'name' => 'foo',
			],
			[
				'name' => 'bar',
			]
		];

		return [
			[
				$noFilters,
				'disallowed1;disallowed3',
				'The queryCallable should not be called if there are no filters',
			],

			[
				$normalFilters,
				'',
				'The queryCallable should not be called if no filters are selected',
			],

			[
				$normalFilters,
				'invalid1',
				'The queryCallable should not be called if no valid filters are selected',
			],
		];
	}

	protected function getSpecialPage() {
		return $this->getMockBuilder( ChangesListSpecialPage::class )
			->setConstructorArgs( [
					'ChangesListSpecialPage',
					'',
				] )
			->getMockForAbstractClass();
	}

	/**
	 * @param array $groupDefinition Group definition
	 * @param string $input Value in URL
	 */
	protected function modifyQueryHelper( $groupDefinition, $input ) {
		$ctx = $this->createMock( IContextSource::class );
		$dbr = $this->createMock( Wikimedia\Rdbms\IDatabase::class );
		$tables = $fields = $conds = $query_options = $join_conds = [];

		$group = new ChangesListStringOptionsFilterGroup( $groupDefinition );

		$specialPage = $this->getSpecialPage();
		$opts = new FormOptions();
		$opts->add( $groupDefinition[ 'name' ], $input );

		$group->modifyQuery(
			$dbr,
			$specialPage,
			$tables,
			$fields,
			$conds,
			$query_options,
			$join_conds,
			$opts,
			true
		);
	}

	public function testGetJsData() {
		$definition = [
			'name' => 'some-group',
			'title' => 'some-group-title',
			'default' => 'foo',
			'priority' => 1,
			'isFullCoverage' => false,
			'queryCallable' => function () {
			},
			'filters' => [
				[
					'name' => 'foo',
					'label' => 'foo-label',
					'description' => 'foo-description',
					'priority' => 2,
				],
				[
					'name' => 'bar',
					'label' => 'bar-label',
					'description' => 'bar-description',
					'priority' => 4,
				]
			],
		];

		$group = new ChangesListStringOptionsFilterGroup( $definition );

		$this->assertArrayEquals(
			[
				'name' => 'some-group',
				'title' => 'some-group-title',
				'type' => ChangesListStringOptionsFilterGroup::TYPE,
				'default' => 'foo',
				'priority' => 1,
				'fullCoverage' => false,
				'filters' => [
					[
						'name' => 'bar',
						'label' => 'bar-label',
						'description' => 'bar-description',
						'priority' => 4,
						'cssClass' => null,
						'conflicts' => [],
						'subset' => [],
						'defaultHighlightColor' => null,
					],
					[
						'name' => 'foo',
						'label' => 'foo-label',
						'description' => 'foo-description',
						'priority' => 2,
						'cssClass' => null,
						'conflicts' => [],
						'subset' => [],
						'defaultHighlightColor' => null,
					],
				],
				'conflicts' => [],
				'separator' => ';',
				'messageKeys' => [
					'some-group-title',
					'bar-label',
					'bar-description',
					'foo-label',
					'foo-description',
				],
			],
			$group->getJsData(),
			/** ordered= */ false,
			/** named= */ true
		);
	}
}
