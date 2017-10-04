<?php

use Wikimedia\TestingAccessWrapper;

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
						'defaultHighlightColor' => null,
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
						'defaultHighlightColor' => null,
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

			$group->getJsData(),
			/** ordered= */ false,
			/** named= */ true
		);
	}
}
