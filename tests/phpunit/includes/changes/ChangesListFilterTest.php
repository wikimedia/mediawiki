<?php

use Wikimedia\TestingAccessWrapper;

/**
 * @covers ChangesListFilter
 */
class ChangesListFilterTest extends MediaWikiTestCase {
	protected $group;

	public function setUp() {
		$this->group = $this->getGroup( [ 'name' => 'group' ] );

		parent::setUp();
	}

	protected function getGroup( $groupDefinition ) {
		return new MockChangesListFilterGroup(
			$groupDefinition + [
				'isFullCoverage' => true,
				'type' => 'some_type',
				'name' => 'group',
				'filters' => [],
			]
		);
	}

	/**
	 * phpcs:disable Generic.Files.LineLength
	 * @expectedException MWException
	 * @expectedExceptionMessage Filter names may not contain '_'.  Use the naming convention: 'lowercase'
	 * phpcs:enable
	 */
	public function testReservedCharacter() {
		$filter = new MockChangesListFilter(
			[
				'group' => $this->group,
				'name' => 'some_name',
				'priority' => 1,
			]
		);
	}

	/**
	 * @expectedException MWException
	 * @expectedExceptionMessage Two filters in a group cannot have the same name: 'somename'
	 */
	public function testDuplicateName() {
		new MockChangesListFilter(
			[
				'group' => $this->group,
				'name' => 'somename',
				'priority' => 1,
			]
		);

		new MockChangesListFilter(
			[
				'group' => $this->group,
				'name' => 'somename',
				'priority' => 2,
			]
		);
	}

	/**
	 * @expectedException MWException
	 * @expectedExceptionMessage Supersets can only be defined for filters in the same group
	 */
	public function testSetAsSupersetOf() {
		$groupA = $this->getGroup(
			[
				'name' => 'groupA',
				'filters' => [
					[
						'name' => 'foo',
					],
					[
						'name' => 'bar',
					]
				],
			]
		);

		$groupB = $this->getGroup(
			[
				'name' => 'groupB',
				'filters' => [
					[
						'name' => 'baz',
					],
				],
			]
		);

		$foo = TestingAccessWrapper::newFromObject( $groupA->getFilter( 'foo' ) );

		$bar = $groupA->getFilter( 'bar' );

		$baz = $groupB->getFilter( 'baz' );

		$foo->setAsSupersetOf( $bar );
		$this->assertArrayEquals( [
				[
					'group' => 'groupA',
					'filter' => 'bar',
				],
			],
			$foo->subsetFilters,
			/** ordered= */ false,
			/** named= */ true
		);

		$foo->setAsSupersetOf( $baz );
	}
}
