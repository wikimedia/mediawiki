<?php

use Wikimedia\TestingAccessWrapper;

/**
 * @covers ChangesListFilter
 */
class ChangesListFilterTest extends MediaWikiIntegrationTestCase {
	protected $group;

	protected function setUp() : void {
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

	public function testReservedCharacter() {
		$this->expectException( MWException::class );
		$this->expectExceptionMessage(
			"Filter names may not contain '_'.  Use the naming convention: 'lowercase'"
		);
		$filter = new MockChangesListFilter(
			[
				'group' => $this->group,
				'name' => 'some_name',
				'priority' => 1,
			]
		);
	}

	public function testDuplicateName() {
		new MockChangesListFilter(
			[
				'group' => $this->group,
				'name' => 'somename',
				'priority' => 1,
			]
		);

		$this->expectException( MWException::class );
		$this->expectExceptionMessage( "Two filters in a group cannot have the same name: 'somename'" );
		new MockChangesListFilter(
			[
				'group' => $this->group,
				'name' => 'somename',
				'priority' => 2,
			]
		);
	}

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

		$this->expectException( MWException::class );
		$this->expectExceptionMessage( "Supersets can only be defined for filters in the same group" );
		$foo->setAsSupersetOf( $baz );
	}
}
