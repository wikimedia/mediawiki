<?php

namespace MediaWiki\Tests\Integration\HTMLForm\Field;

use MediaWiki\ChangeTags\ChangeTags;
use MediaWiki\HTMLForm\Field\HTMLTagFilter;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Tests\Integration\HTMLForm\HTMLFormFieldTestCase;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Database
 * @covers MediaWiki\HTMLForm\Field\HTMLTagFilter
 */
class HTMLTagFilterTest extends HTMLFormFieldTestCase {
	/** @inheritDoc */
	protected $className = HTMLTagFilter::class;

	/** @dataProvider provideConstruct */
	public function testConstruct( $config, $expectedPropertyValues ) {
		$htmlForm = $this->createMock( HTMLForm::class );
		$tagFilter = new HTMLTagFilter( $config + [ 'fieldname' => 'foo', 'parent' => $htmlForm ] );
		$tagFilter = TestingAccessWrapper::newFromObject( $tagFilter );
		foreach ( $expectedPropertyValues as $property => $expectedValue ) {
			$this->assertSame( $expectedValue, $tagFilter->$property );
		}
	}

	public static function provideConstruct() {
		return [
			'Empty config provided gives defaults' => [
				[],
				[
					'activeOnly' => ChangeTags::TAG_SET_ACTIVE_ONLY,
					'useAllTags' => ChangeTags::USE_ALL_TAGS,
				],
			],
			'Custom values for config override defaults' => [
				[
					'activeOnly' => ChangeTags::TAG_SET_ALL,
				],
				[
					'activeOnly' => ChangeTags::TAG_SET_ALL,
					'useAllTags' => ChangeTags::USE_ALL_TAGS,
				],
			],
			'Set both custom values' => [
				[
					'activeOnly' => ChangeTags::TAG_SET_ALL,
					'useAllTags' => ChangeTags::USE_SOFTWARE_TAGS_ONLY,
				],
				[
					'activeOnly' => ChangeTags::TAG_SET_ALL,
					'useAllTags' => ChangeTags::USE_SOFTWARE_TAGS_ONLY,
				],
			],
		];
	}
}
