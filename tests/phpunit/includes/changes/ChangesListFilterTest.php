<?php

/**
 * @covers ChangesListFilter
 */
class ChangesListFilterTest extends MediaWikiTestCase {
	public function testGetJsData() {
		$definition = [
			'label' => 'main-label',
			'description' => 'main-description',
			'default' => 1,
		];

		$fooFilter = new ChangesListFilter(
			$definition + [ 'name' => 'hidefoo' ]
		);

		$barFilter = new ChangesListFilter(
			$definition + [ 'name' => 'hidebar' ]
		);

		$fooFilter->conflictsWith(
			$barFilter,
			'foo-bar-global-conflict',
			'foo-conflicts-bar',
			'bar-conflicts-foo'
		);

		$fooData = $fooFilter->getJsData();
		$this->assertArrayEquals(
			[
				'name' => 'hidefoo',
				'description' => 'main-description',
				'default' => 1,
				'conflicts' => [
					[
						'filter' => 'hidebar',
						'globalDescription' => 'foo-bar-global-conflict',
						'contextDescription' => 'bar-conflicts-foo',
					]
				]
			],
			$fooData
		);

		$barData = $barFilter->getJsData();
		$this->assertArrayEquals(
			[
				'name' => 'hidebar',
				'description' => 'main-description',
				'default' => 1,
				'conflicts' => [
					[
						'filter' => 'hidefoo',
						'globalDescription' => 'foo-bar-global-conflict',
						'contextDescription' => 'foo-conflicts-bar',
					]
				],
			],
			$barData
		);
	}
}