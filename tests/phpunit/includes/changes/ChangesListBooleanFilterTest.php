<?php

/**
 * @covers ChangesListBooleanFilter
 */
class ChangesListBooleanFilterTest extends MediaWikiIntegrationTestCase {
	public function testGetJsData() {
		$group = new ChangesListBooleanFilterGroup( [
			'name' => 'group',
			'priority' => 2,
			'filters' => [],
		] );

		$definition = [
			'group' => $group,
			'label' => 'main-label',
			'description' => 'main-description',
			'default' => 1,
			'priority' => 1,
		];

		$fooFilter = new ChangesListBooleanFilter(
			$definition + [ 'name' => 'hidefoo' ]
		);

		$barFilter = new ChangesListBooleanFilter(
			$definition + [ 'name' => 'hidebar' ]
		);

		$bazFilter = new ChangesListBooleanFilter(
			$definition + [ 'name' => 'hidebaz' ]
		);

		$fooFilter->conflictsWith(
			$barFilter,
			'foo-bar-global-conflict',
			'foo-conflicts-bar',
			'bar-conflicts-foo'
		);

		$fooFilter->setAsSupersetOf( $bazFilter, 'foo-superset-of-baz' );

		$fooData = $fooFilter->getJsData();
		$this->assertArrayEquals(
			[
				'name' => 'hidefoo',
				'label' => 'main-label',
				'description' => 'main-description',
				'default' => 1,
				'priority' => 1,
				'cssClass' => null,
				'defaultHighlightColor' => null,
				'conflicts' => [
					[
						'group' => 'group',
						'filter' => 'hidebar',
						'globalDescription' => 'foo-bar-global-conflict',
						'contextDescription' => 'foo-conflicts-bar',
					]
				],
				'subset' => [
					[
						'group' => 'group',
						'filter' => 'hidebaz',
					],

				],
				'messageKeys' => [
					'main-label',
					'main-description',
					'foo-bar-global-conflict',
					'foo-conflicts-bar',
				],
			],
			$fooData,
			/** ordered= */ false,
			/** named= */ true
		);

		$barData = $barFilter->getJsData();
		$this->assertArrayEquals(
			[
				'name' => 'hidebar',
				'label' => 'main-label',
				'description' => 'main-description',
				'default' => 1,
				'priority' => 1,
				'cssClass' => null,
				'defaultHighlightColor' => null,
				'conflicts' => [
					[
						'group' => 'group',
						'filter' => 'hidefoo',
						'globalDescription' => 'foo-bar-global-conflict',
						'contextDescription' => 'bar-conflicts-foo',
					]
				],
				'subset' => [],
				'messageKeys' => [
					'main-label',
					'main-description',
					'foo-bar-global-conflict',
					'bar-conflicts-foo',
				],
			],
			$barData,
			/** ordered= */ false,
			/** named= */ true
		);
	}

	public function testIsFeatureAvailableOnStructuredUi() {
		$groupA = new ChangesListBooleanFilterGroup( [
			'name' => 'groupA',
			'priority' => 1,
			'filters' => [],
		] );

		$foo = new ChangesListBooleanFilter( [
			'name' => 'hidefoo',
			'group' => $groupA,
			'label' => 'foo-label',
			'description' => 'foo-description',
			'default' => true,
			'showHide' => 'showhidefoo',
			'priority' => 2,
		] );

		$this->assertTrue(

			$foo->isFeatureAvailableOnStructuredUi(),
			'Same filter appears on both'
		);

		// Should only be legacy ones that haven't been ported yet
		$bar = new ChangesListBooleanFilter( [
			'name' => 'hidebar',
			'default' => true,
			'group' => $groupA,
			'showHide' => 'showhidebar',
			'priority' => 2,
		] );

		$this->assertFalse(
			$bar->isFeatureAvailableOnStructuredUi(),
			'Only on unstructured UI'
		);

		$baz = new ChangesListBooleanFilter( [
			'name' => 'hidebaz',
			'default' => true,
			'group' => $groupA,
			'showHide' => 'showhidebaz',
			'isReplacedInStructuredUi' => true,
			'priority' => 2,
		] );

		$this->assertTrue(

			$baz->isFeatureAvailableOnStructuredUi(),
			'Legacy filter does not appear directly in new UI, but equivalent ' .
				'does and is marked with isReplacedInStructuredUi'
		);
	}
}
