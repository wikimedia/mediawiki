<?php

/**
 * @covers ChangesListBooleanFilterGroup
 */
class ChangesListBooleanFilterGroupTest extends MediaWikiTestCase {
	public function testIsFullCoverage() {
		$hideGroupDefault = TestingAccessWrapper::newFromObject(
			new ChangesListBooleanFilterGroup( [
				'name' => 'groupName',
				'priority' => 1,
				'filters' => [],
			] )
		);

		$this->assertSame(
			true,
			$hideGroupDefault->isFullCoverage
		);
	}

	public function testAutoPriorities() {
		$group = new ChangesListBooleanFilterGroup( [
			'name' => 'groupName',
			'priority' => 1,
			'filters' => [
				[ 'name' => 'hidefoo', 'default' => false, ],
				[ 'name' => 'hidebar', 'default' => false, ],
				[ 'name' => 'hidebaz', 'default' => false, ],
			],
		] );

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

	public function testGetJsData() {
		$definition = [
			'name' => 'some-group',
			'title' => 'some-group-title',
			'priority' => 1,
			'filters' => [
				[
					'name' => 'hidefoo',
					'label' => 'foo-label',
					'description' => 'foo-description',
					'default' => true,
					'showHide' => 'showhidefoo',
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

		$group = new ChangesListBooleanFilterGroup( $definition );

		$specialPage = $this->getMockBuilder( 'ChangesListSpecialPage' )
			->setConstructorArgs( [
				'ChangesListSpecialPage',
				'',
			] )
			->getMockForAbstractClass();

		$this->assertArrayEquals(
			[
				'name' => 'some-group',
				'title' => 'some-group-title',
				'type' => ChangesListBooleanFilterGroup::TYPE,
				'priority' => 1,
				'filters' => [
					[
						'name' => 'hidebar',
						'label' => 'bar-label',
						'description' => 'bar-description',
						'default' => false,
						'priority' => 4,
						'cssClass' => null,
						'conflicts' => [],
						'subset' => [],
					],
					[
						'name' => 'hidefoo',
						'label' => 'foo-label',
						'description' => 'foo-description',
						'default' => true,
						'priority' => 2,
						'cssClass' => null,
						'conflicts' => [],
						'subset' => [],
					],
				],
				'conflicts' => [],
				'fullCoverage' => true,
				'messageKeys' => [
					'some-group-title',
					'bar-label',
					'bar-description',
					'foo-label',
					'foo-description',
				],
			],

			$group->getJsData( $specialPage ),
			/** ordered= */ false,
			/** named= */ true
		);
	}
}