<?php

use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\FakeResultWrapper;

/**
 * @covers PageProps
 *
 * @group Database
 *	^--- tell jenkins this test needs the database
 *
 * @group medium
 *	^--- tell phpunit that these test cases may take longer than 2 seconds.
 */
class PagePropsTest extends MediaWikiLangTestCase {

	/**
	 * @var Title
	 */
	private $title1;

	/**
	 * @var Title
	 */
	private $title2;

	/**
	 * @var array
	 */
	private $expectedProperties;

	protected function setUp() : void {
		parent::setUp();

		$this->setMwGlobals( [
			'wgExtraNamespaces' => [
				12312 => 'Dummy',
				12313 => 'Dummy_talk',
			],
			'wgNamespaceContentModels' => [ 12312 => 'DUMMY' ],
		] );

		$this->mergeMwGlobalArrayValue(
			'wgContentHandlers',
			[ 'DUMMY' => 'DummyContentHandlerForTesting' ]
		);

		if ( !$this->expectedProperties ) {
			$this->expectedProperties = [
				"property1" => "value1",
				"property2" => "value2",
				"property3" => "value3",
				"property4" => "value4"
			];

			$page = $this->createPage(
				'PagePropsTest_page_1',
				"just a dummy page",
				CONTENT_MODEL_WIKITEXT
			);
			$this->title1 = $page->getTitle();
			$page1ID = $this->title1->getArticleID();
			$this->setProperties( $page1ID, $this->expectedProperties );

			$page = $this->createPage(
				'PagePropsTest_page_2',
				"just a dummy page",
				CONTENT_MODEL_WIKITEXT
			);
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
		$pageProps = PageProps::getInstance();
		$page1ID = $this->title1->getArticleID();
		$result = $pageProps->getProperties( $this->title1, "property1" );
		$this->assertArrayHasKey( $page1ID, $result, "Found property" );
		$this->assertEquals( $result[$page1ID], "value1", "Get property" );
	}

	/**
	 * Test getting a single property from multiple pages. The property was
	 * set in setUp(). Using Title[].
	 */
	public function testGetSinglePropertyMultiplePages() {
		$pageProps = PageProps::getInstance();
		$page1ID = $this->title1->getArticleID();
		$page2ID = $this->title2->getArticleID();
		$titles = [
			$this->title1,
			$this->title2
		];
		$result = $pageProps->getProperties( $titles, "property1" );
		$this->assertArrayHasKey( $page1ID, $result, "Found page 1 property" );
		$this->assertArrayHasKey( $page2ID, $result, "Found page 2 property" );
		$this->assertEquals( $result[$page1ID], "value1", "Get property page 1" );
		$this->assertEquals( $result[$page2ID], "value1", "Get property page 2" );
	}

	/**
	 * Test getting a single property from multiple pages. The property was
	 * set in setUp(). Using TitleArray.
	 */
	public function testGetSinglePropertyMultiplePagesTitleArray() {
		$pageProps = PageProps::getInstance();
		$page1ID = $this->title1->getArticleID();
		$page2ID = $this->title2->getArticleID();
		$rows = [
			$this->createRowFromTitle( $this->title1 ),
			$this->createRowFromTitle( $this->title2 )
		];
		$resultWrapper = new FakeResultWrapper( $rows );
		$titles = TitleArray::newFromResult( $resultWrapper );
		$result = $pageProps->getProperties( $titles, "property1" );
		$this->assertArrayHasKey( $page1ID, $result, "Found page 1 property" );
		$this->assertArrayHasKey( $page2ID, $result, "Found page 2 property" );
		$this->assertEquals( $result[$page1ID], "value1", "Get property page 1" );
		$this->assertEquals( $result[$page2ID], "value1", "Get property page 2" );
	}

	/**
	 * Test getting multiple properties from multiple pages. The properties
	 * were set in setUp().
	 */
	public function testGetMultiplePropertiesMultiplePages() {
		$pageProps = PageProps::getInstance();
		$page1ID = $this->title1->getArticleID();
		$page2ID = $this->title2->getArticleID();
		$titles = [
			$this->title1,
			$this->title2
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
		$this->assertEquals( $result[$page1ID]["property1"], "value1", "Get page 1 property 1" );
		$this->assertEquals( $result[$page1ID]["property2"], "value2", "Get page 1 property 2" );
		$this->assertEquals( $result[$page2ID]["property1"], "value1", "Get page 2 property 1" );
		$this->assertEquals( $result[$page2ID]["property2"], "value2", "Get page 2 property 2" );
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
		$pageProps = PageProps::getInstance();
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
		$pageProps = PageProps::getInstance();
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
		$pageProps = PageProps::getInstance();
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
		$pageProps = PageProps::getInstance();
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
		$pageProps = PageProps::getInstance();
		$page1ID = $this->title1->getArticleID();
		$pageProps->getProperties( $this->title1, "property1" );
		$new_value = "another value";
		$this->setProperty( $page1ID, "property1", $new_value );
		$pageProps->getAllProperties( $this->title1 );
		$result = $pageProps->getProperties( $this->title1, "property1" );
		$this->assertArrayHasKey( $page1ID, $result, "Found property" );
		$this->assertEquals( $result[$page1ID], "another value", "Clear cache" );
	}

	protected function createPage( $page, $text, $model = null ) {
		if ( is_string( $page ) ) {
			if ( !preg_match( '/:/', $page ) &&
				( $model === null || $model === CONTENT_MODEL_WIKITEXT )
			) {
				$ns = $this->getDefaultWikitextNS();
				$page = MediaWikiServices::getInstance()->getNamespaceInfo()->
					getCanonicalName( $ns ) . ':' . $page;
			}

			$page = Title::newFromText( $page );
		}

		if ( $page instanceof Title ) {
			$page = new WikiPage( $page );
		}

		if ( $page->exists() ) {
			$page->doDeleteArticleReal( "done", $this->getTestSysop()->getUser() );
		}

		$content = ContentHandler::makeContent( $text, $page->getTitle(), $model );
		$page->doEditContent( $content, "testing", EDIT_NEW );

		return $page;
	}

	protected function setProperties( $pageID, $properties ) {
		$rows = [];
		foreach ( $properties as $propertyName => $propertyValue ) {
			$rows[] = [
				'pp_page' => $pageID,
				'pp_propname' => $propertyName,
				'pp_value' => $propertyValue
			];
		}

		$dbw = wfGetDB( DB_MASTER );
		$dbw->replace(
			'page_props',
			[
				[
					'pp_page',
					'pp_propname'
				]
			],
			$rows,
			__METHOD__
		);
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
