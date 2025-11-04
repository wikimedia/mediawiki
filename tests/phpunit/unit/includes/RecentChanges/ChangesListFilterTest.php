<?php

use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\RecentChanges\ChangesListFilter
 */
class ChangesListFilterTest extends MediaWikiUnitTestCase {
	/** @var MockChangesListFilterGroup */
	private $group;

	protected function setUp(): void {
		parent::setUp();
		$this->group = $this->getGroup( [ 'name' => 'group' ] );
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
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage(
			"Filter names may not contain '_'.  Use the naming convention: 'lowercase'"
		);
		new MockChangesListFilter(
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

		$this->expectException( InvalidArgumentException::class );
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

		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( "Supersets can only be defined for filters in the same group" );
		$foo->setAsSupersetOf( $baz );
	}
}
