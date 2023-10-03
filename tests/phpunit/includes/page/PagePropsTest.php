<?php

use MediaWiki\Title\Title;
use MediaWiki\Title\TitleArrayFromResult;
use Wikimedia\Rdbms\FakeResultWrapper;

/**
 * @covers PageProps
 * @group Database
 * @group medium
 */
class PagePropsTest extends MediaWikiLangTestCase {

	private ?array $expectedProperties = null;
	private Title $title1;
	private Title $title2;

	protected function setUp(): void {
		parent::setUp();

		if ( !$this->expectedProperties ) {
			$this->expectedProperties = [
				"property1" => "value1",
				"property2" => "value2",
				"property3" => "value3",
				"property4" => "value4"
			];

			$page = $this->getExistingTestPage( 'PagePropsTest_page_1' );
			$this->title1 = $page->getTitle();
			$page1ID = $this->title1->getArticleID();
			$this->setProperties( $page1ID, $this->expectedProperties );

			$page = $this->getExistingTestPage( 'PagePropsTest_page_2' );
			$this->title2 = $page->getTitle();
			$page2ID = $this->title2->getArticleID();
			$this->setProperties( $page2ID, $this->expectedProperties );
		}
	}

	/**
	 * Test getting a single property from a single page. The property was
	 * set in setUp().
	 */
	public function testGetSingleProperty() {
		$pageProps = $this->getServiceContainer()->getPageProps();
		$page1ID = $this->title1->getArticleID();
		$result = $pageProps->getProperties( $this->title1, "property1" );
		$this->assertArrayHasKey( $page1ID, $result, "Found property" );
		$this->assertSame( "value1", $result[$page1ID], "Get property" );
	}

	/**
	 * Test getting a single property from multiple pages. The property was
	 * set in setUp(). Using Title[].
	 */
	public function testGetSinglePropertyMultiplePages() {
		$pageProps = $this->getServiceContainer()->getPageProps();
		$page1ID = $this->title1->getArticleID();
		$page2ID = $this->title2->getArticleID();
		$titles = [
			$this->title1,
			$this->title2
		];
		$result = $pageProps->getProperties( $titles, "property1" );
		$this->assertArrayHasKey( $page1ID, $result, "Found page 1 property" );
		$this->assertArrayHasKey( $page2ID, $result, "Found page 2 property" );
		$this->assertSame( "value1", $result[$page1ID], "Get property page 1" );
		$this->assertSame( "value1", $result[$page2ID], "Get property page 2" );
	}

	/**
	 * Test getting a single property from multiple pages. The property was
	 * set in setUp(). Using TitleArray.
	 */
	public function testGetSinglePropertyMultiplePagesTitleArray() {
		$pageProps = $this->getServiceContainer()->getPageProps();
		$page1ID = $this->title1->getArticleID();
		$page2ID = $this->title2->getArticleID();
		$rows = [
			$this->createRowFromTitle( $this->title1 ),
			$this->createRowFromTitle( $this->title2 )
		];
		$resultWrapper = new FakeResultWrapper( $rows );
		$titles = new TitleArrayFromResult( $resultWrapper );
		$result = $pageProps->getProperties( $titles, "property1" );
		$this->assertArrayHasKey( $page1ID, $result, "Found page 1 property" );
		$this->assertArrayHasKey( $page2ID, $result, "Found page 2 property" );
		$this->assertSame( "value1", $result[$page1ID], "Get property page 1" );
		$this->assertSame( "value1", $result[$page2ID], "Get property page 2" );
	}

	/**
	 * Test getting multiple properties from multiple pages. The properties
	 * were set in setUp().
	 */
	public function testGetMultiplePropertiesMultiplePages() {
		$pageProps = $this->getServiceContainer()->getPageProps();
		$page1ID = $this->title1->getArticleID();
		$page2ID = $this->title2->getArticleID();
		$titles = [
			$this->title1->toPageIdentity(),
			$this->title2->toPageIdentity()
		];
		$properties = [
			"property1",
			"property2"
		];
		$result = $pageProps->getProperties( $titles, $properties );
		$this->assertArrayHasKey( $page1ID, $result, "Found page 1 property" );
		$this->assertArrayHasKey( "property1", $result[$page1ID], "Found page 1 property 1" );
		$this->assertArrayHasKey( "property2", $result[$page1ID], "Found page 1 property 2" );
		$this->assertArrayHasKey( $page2ID, $result, "Found page 2 property" );
		$this->assertArrayHasKey( "property1", $result[$page2ID], "Found page 2 property 1" );
		$this->assertArrayHasKey( "property2", $result[$page2ID], "Found page 2 property 2" );
		$this->assertSame( "value1", $result[$page1ID]["property1"], "Get page 1 property 1" );
		$this->assertSame( "value2", $result[$page1ID]["property2"], "Get page 1 property 2" );
		$this->assertSame( "value1", $result[$page2ID]["property1"], "Get page 2 property 1" );
		$this->assertSame( "value2", $result[$page2ID]["property2"], "Get page 2 property 2" );
	}

	/**
	 * Test getting all properties from a single page. The properties were
	 * set in setUp(). The properties retrieved from the page may include
	 * additional properties not set in the test case that are added by
	 * other extensions. Therefore, rather than checking to see if the
	 * properties that were set in the test case exactly match the
	 * retrieved properties, we need to check to see if they are a
	 * subset of the retrieved properties.
	 */
	public function testGetAllProperties() {
		$pageProps = $this->getServiceContainer()->getPageProps();
		$page1ID = $this->title1->getArticleID();
		$result = $pageProps->getAllProperties( $this->title1 );
		$this->assertArrayHasKey( $page1ID, $result, "Found properties" );

		$properties = $result[$page1ID];
		$subset = array_intersect_key( $properties, $this->expectedProperties );
		$this->assertEquals( $this->expectedProperties, $subset, "Get all properties" );
	}

	/**
	 * Test getting all properties from multiple pages. The properties were
	 * set in setUp(). See getAllProperties() above for more information.
	 */
	public function testGetAllPropertiesMultiplePages() {
		$pageProps = $this->getServiceContainer()->getPageProps();
		$page1ID = $this->title1->getArticleID();
		$page2ID = $this->title2->getArticleID();
		$titles = [
			$this->title1,
			$this->title2
		];
		$result = $pageProps->getAllProperties( $titles );
		$this->assertArrayHasKey( $page1ID, $result, "Found page 1 properties" );
		$this->assertArrayHasKey( $page2ID, $result, "Found page 2 properties" );

		$properties = $result[$page1ID];
		$subset = array_intersect_key( $properties, $this->expectedProperties );
		$this->assertEquals( $this->expectedProperties, $subset, "Properties of page 1" );

		$properties = $result[$page2ID];
		$subset = array_intersect_key( $properties, $this->expectedProperties );
		$this->assertEquals( $this->expectedProperties, $subset, "Properties of page 2" );
	}

	/**
	 * Test caching when retrieving single properties by getting a property,
	 * saving a new value for the property, then getting the property
	 * again. The cached value for the property rather than the new value
	 * of the property should be returned.
	 */
	public function testSingleCache() {
		$pageProps = $this->getServiceContainer()->getPageProps();
		$page1ID = $this->title1->getArticleID();
		$value1 = $pageProps->getProperties( $this->title1, "property1" );
		$this->setProperty( $page1ID, "property1", "another value" );
		$value2 = $pageProps->getProperties( $this->title1, "property1" );

		$this->assertEquals( $value1, $value2, "Single cache" );
	}

	/**
	 * Test caching when retrieving all properties by getting all
	 * properties, saving a new value for a property, then getting all
	 * properties again. The cached value for the properties rather than the
	 * new value of the properties should be returned.
	 */
	public function testMultiCache() {
		$pageProps = $this->getServiceContainer()->getPageProps();
		$page1ID = $this->title1->getArticleID();
		$properties1 = $pageProps->getAllProperties( $this->title1 );
		$this->setProperty( $page1ID, "property1", "another value" );
		$properties2 = $pageProps->getAllProperties( $this->title1 );

		$this->assertEquals( $properties1, $properties2, "Multi Cache" );
	}

	/**
	 * Test that getting all properties clears the single properties
	 * that have been cached by getting a property, saving a new value for
	 * the property, getting all properties (which clears the cached single
	 * properties), then getting the property again. The new value for the
	 * property rather than the cached value of the property should be
	 * returned.
	 */
	public function testClearCache() {
		$pageProps = $this->getServiceContainer()->getPageProps();
		$page1ID = $this->title1->getArticleID();
		$pageProps->getProperties( $this->title1, "property1" );
		$new_value = "another value";
		$this->setProperty( $page1ID, "property1", $new_value );
		$pageProps->getAllProperties( $this->title1 );
		$result = $pageProps->getProperties( $this->title1, "property1" );
		$this->assertArrayHasKey( $page1ID, $result, "Found property" );
		$this->assertSame( "another value", $result[$page1ID], "Clear cache" );
	}

	protected function setProperties( $pageID, $properties ) {
		$queryBuilder = $this->getDb()->newReplaceQueryBuilder()
			->replaceInto( 'page_props' )
			->uniqueIndexFields( [ 'pp_page', 'pp_propname' ] );
		foreach ( $properties as $propertyName => $propertyValue ) {
			$queryBuilder->row( [
				'pp_page' => $pageID,
				'pp_propname' => $propertyName,
				'pp_value' => $propertyValue
			] );
		}
		$queryBuilder->caller( __METHOD__ )->execute();
	}

	protected function setProperty( $pageID, $propertyName, $propertyValue ) {
		$properties = [
			$propertyName => $propertyValue
		];
		$this->setProperties( $pageID, $properties );
	}

	protected function createRowFromTitle( $title ) {
		return (object)[
			'page_namespace' => $title->getNamespace(),
			'page_title' => $title->getText()
		];
	}
}
