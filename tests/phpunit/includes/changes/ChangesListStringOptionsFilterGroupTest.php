<?php

use Wikimedia\TestingAccessWrapper;

/**
 * @covers ChangesListStringOptionsFilterGroup
 */
class ChangesListStringOptionsFilterGroupTest extends MediaWikiTestCase {
	/**
	 * @expectedException MWException
	 */
	public function testIsFullCoverage() {
		$falseGroup = TestingAccessWrapper::newFromObject(
			new ChangesListStringOptionsFilterGroup( [
				'name' => 'group',
				'filters' => [],
				'isFullCoverage' => false,
				'queryCallable' => function () {
				}
			] )
		);

		$this->assertSame(
			false,
			$falseGroup->isFullCoverage
		);

		// Should throw due to missing isFullCoverage
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
		$self = $this;

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
		) use ( $self, $expectedValues ) {
			$self->assertSame(
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
		return $this->getMockBuilder( 'ChangesListSpecialPage' )
			->setConstructorArgs( [
					'ChangesListSpecialPage',
					'',
				] )
			->getMockForAbstractClass();
	}

	/**
	 * @param array $groupDefinition Group definition
	 * @param string $input Value in URL
	 *
	 * @dataProvider provideModifyQuery
	 */
	protected function modifyQueryHelper( $groupDefinition, $input ) {
		$ctx = $this->createMock( 'IContextSource' );
		$dbr = $this->createMock( 'IDatabase' );
		$tables = $fields = $conds = $query_options = $join_conds = [];

		$group = new ChangesListStringOptionsFilterGroup( $groupDefinition );

		$specialPage = $this->getSpecialPage();

		$group->modifyQuery(
			$dbr,
			$specialPage,
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
					],
					[
						'name' => 'foo',
						'label' => 'foo-label',
						'description' => 'foo-description',
						'priority' => 2,
						'cssClass' => null,
						'conflicts' => [],
						'subset' => [],
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
