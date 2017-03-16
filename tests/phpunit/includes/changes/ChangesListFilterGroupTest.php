<?php

/**
 * @covers ChangesListFilterGroup
 */
class ChangesListFilterGroupTest extends MediaWikiTestCase {
	public function testAutoPriorities() {
		$group = new MockChangesListFilterGroup(
			[
				'type' => 'some_type',
				'name' => 'groupName',
				'isFullCoverage' => true,
				'priority' => 1,
				'filters' => [
					[ 'name' => 'hidefoo' ],
					[ 'name' => 'hidebar' ],
					[ 'name' => 'hidebaz' ],
				],
			]
		);

		$filters = $group->getFilters();
		$this->assertEquals(
			[
				-2,
				-3,
				-4,
			],
			array_map(
				function ( $f ) {
					return $f->getPriority();
				},
				array_values( $filters )
			)
		);
	}
}
