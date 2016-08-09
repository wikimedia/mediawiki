<?php

use MediaWiki\Search\Field\SearchIndexFieldFactory;

/**
 * @group Search
 * @covers MediaWiki\Search\Field\SearchIndexFieldFactory
 */
class SearchIndexFieldFactoryTest extends PHPUnit_Framework_TestCase {

	public function testNewCategoryField() {
		$searchEngine = $this->newSearchEngine();

		$searchIndexFieldFactory = new SearchIndexFieldFactory( $searchEngine );

		$field = $searchIndexFieldFactory->newCategoryField();

		$expected = [
			'name' => 'category',
			'type' => SearchIndexField::INDEX_TYPE_TEXT,
			'flags'=> 1,
			'subfields' => []
		];

		$this->assertEquals( $expected, $field->getMapping( $searchEngine ) );
	}

	public function testNewKeywordField() {
		$searchEngine = $this->newSearchEngine();

		$searchIndexFieldFactory = new SearchIndexFieldFactory( $searchEngine );

		$field = $searchIndexFieldFactory->newKeywordField( 'keyword_field' );

		$expected = [
			'name' => 'keyword_field',
			'type' => SearchIndexField::INDEX_TYPE_KEYWORD,
			'flags'=> 0,
			'subfields' => []
		];

		$this->assertEquals( $expected, $field->getMapping( $searchEngine ) );
	}

	public function testNewTemplateField() {
		$searchEngine = $this->newSearchEngine();

		$searchIndexFieldFactory = new SearchIndexFieldFactory( $searchEngine );

		$field = $searchIndexFieldFactory->newTemplateField();

		$expected = [
			'name' => 'template',
			'type' => SearchIndexField::INDEX_TYPE_KEYWORD,
			'flags' => 1,
			'subfields' => []
		];

		$this->assertEquals( $expected, $field->getMapping( $searchEngine ) );
	}

	private function newSearchEngine() {
		$searchEngine = $this->getMockBuilder( 'SearchEngine' )
			->getMock();

		$searchEngine->expects( $this->any() )
			->method( 'makeSearchFieldMapping' )
			->will( $this->returnCallback( function( $name, $type ) {
					return new SearchIndexFieldDefinition( $name, $type );
			} ) );

		return $searchEngine;
	}

}
