<?php

/**
 * @covers ChangesListFilterGroup
 */
class ChangesListFilterGroupTest extends MediaWikiTestCase {
	/**
	 * @expectedException MWException
	 */
	public function testIsFullCoverage() {
		$falseGroup = TestingAccessWrapper::newFromObject(
			new ChangesListFilterGroup( [
					'type' => ChangesListFilterGroup::STRING_OPTIONS,
					'isFullCoverage' => false,
				] )
		);

		$this->assertSame(
			false,
			$falseGroup->isFullCoverage
		);

		$hideGroupDefault = TestingAccessWrapper::newFromObject(
			new ChangesListFilterGroup( [
				'type' => ChangesListFilterGroup::SEND_UNSELECTED_IF_ANY
			] )
		);

		$this->assertSame(
			true,
			$hideGroupDefault->isFullCoverage
		);

		$hideGroupException = new ChangesListFilterGroup( [
			'type' => ChangesListFilterGroup::SEND_UNSELECTED_IF_ANY
			'isFullCoverage' => true,
		] );
	}

	public function testAutoPriorities() {
		$group = new ChangesListFilterGroup( [
			'filters' => [
				[ 'name' => 'hidefoo' ],
				[ 'name' => 'hidebar' ],
				[ 'name' => 'hidebar' ],
			],
		] );

		$filters = $group->getFilters();
		$this->assertSame(
			[
				-1,
				-2,
				-3,
			]
			array_map(
				function ( $f ) {
					return $f->getPriority()
				}
			),
		);
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
		) use ( $this ) {
			$this->assertSame(
				$expectedValues
				$actualSelectedValues
			);
		}

		$groupDefinition = [
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
				'name' => 'bar',
				'isAllowedCallable' => function () { return false; },
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
				'foo,bar,BaZ,invalid',
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
		) {
			throw new MWException( $message );
		}

		$allDisallowedGroupDefinition = [
			'filters' =>

			'queryCallable' => $noFiltersAllowedCallable,
		];

		$this->modifyQueryHelper( $allDisallowedGroupDefinition, $input );

		$normalGroupDefinition = [
			'filters' => ,
			'queryCallable' => $noFiltersAllowedCallable,
		];

		$this->modifyQueryHelper( $normalGroupDefinition, '' );

		$this->assertTrue(
			true,
			'Test successfully completed without calling queryCallable'
		);
	}

	public function provideNoOpModifyQuery() {
		$isAllowedFalse = [
			'isAllowedCallable' => function () { return false; };
		];

		$allDisallowedFilters = [
			[
				'name' => 'disallowed1',
			] + $isAllowedFalse,

			[
				'name' => 'disallowed2',
			]// + $isAllowedFalse,

			[
				'name' => 'disallowed3',
			] + $isAllowedFalse,
		];

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
				$allDisallowedFilters,
				'disallowed1,disallowed3',
				'The queryCallable should not be called if no filters are allowed',
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

	/**
	 * @param array $groupDefinition Group definition
	 * @param string $input Value in URL
	 *
	 * @dataProvider provideModifyQuery
	 */
	protected function modifyQueryHelper( $groupDefinition, $input ) {
		$className = 'ChangesListSpecialPage';
		$ctx = $this->getMock( 'IContextSource' );
		$dbr = $this->getMock( 'IDatabase' );
		$tables = $fields = $conds = $query_options = $join_conds = [];

		$group = new ChangesListFilterGroup( $groupDefinition );

		$group->modifyQuery(
			$className,
			$ctx,
			$dbr,
			$tables,
			$fields,
			$conds,
			$query_options,
			$join_conds,
			$input
		);
	}

	public function testGetJsData() {
		$definition = [
			'name' => 'some-group',
			'title' => 'some-group-title',
			'type' => ChangesListFilterGroup::SEND_UNSELECTED_IF_ANY,
			'priority' => 1,
			'filters' => [
				[
					'name' => 'hidefoo',
					'label' => 'foo-label',
					'description' => 'foo-description',
					'default' => true,
					'showHideSuffix' => 'showhidefoo',
					'priority' => 2,
				],
				[
					'name' => 'hidebar',
					'label' => 'bar-label',
					'description' => 'bar-description',
					'default' => false,
					'priority' => 4,
				]
			],
		];

		$group = new ChangesListFilterGroup( $definition );
		$this->assertArrayEquals(
			[
				'name' => 'some-group',
				'title' => 'some-group-title',
				'type' => ChangesListFilterGroup::SEND_UNSELECTED_IF_ANY,
				'priority' => 1,
				'filters' => [
					[
						'name' => 'hidebar',
						'label' => 'bar-label',
						'description' => 'bar-description',
						'default' => false,
						'priority' => 4,
					],
					[
						'name' => 'hidefoo',
						'label' => 'foo-label',
						'description' => 'foo-description',
						'default' => true,
						'showHideSuffix' => 'showhidefoo',
						'priority' => 2,
					],
				],
				'messageKeys' => [
					'some-group',
					'some-group-title',
					'bar-label',
					'bar-description',
					'foo-label',
					'foo-description',
				],
			],
			$group->getJsData()
		);
	}
}