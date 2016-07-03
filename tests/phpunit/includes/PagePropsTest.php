<?php

/**
 * @group Database
 *	^--- tell jenkins this test needs the database
 *
 * @group medium
 *	^--- tell phpunit that these test cases may take longer than 2 seconds.
 */
class TestPageProps extends MediaWikiLangTestCase {

	/**
	 * @var Title $title1
	 */
	private $title1;

	/**
	 * @var Title $title2
	 */
	private $title2;

	/**
	 * @var array $the_properties
	 */
	private $the_properties;

	protected function setUp() {
		global $wgExtraNamespaces, $wgNamespaceContentModels, $wgContentHandlers, $wgContLang;

		parent::setUp();

		$wgExtraNamespaces[12312] = 'Dummy';
		$wgExtraNamespaces[12313] = 'Dummy_talk';

		$wgNamespaceContentModels[12312] = 'DUMMY';
		$wgContentHandlers['DUMMY'] = 'DummyContentHandlerForTesting';

		MWNamespace::getCanonicalNamespaces( true ); # reset namespace cache
		$wgContLang->resetNamespaces(); # reset namespace cache

		if ( !$this->the_properties ) {
			$this->the_properties = [
				"property1" => "value1",
				"property2" => "value2",
				"property3" => "value3",
				"property4" => "value4"
			];
		}

		if ( !$this->title1 ) {
			$page = $this->createPage(
				'PagePropsTest_page_1',
				"just a dummy page",
				CONTENT_MODEL_WIKITEXT
			);
			$this->title1 = $page->getTitle();
			$page1ID = $this->title1->getArticleID();
			$this->setProperties( $page1ID, $this->the_properties );
		}

		if ( !$this->title2 ) {
			$page = $this->createPage(
				'PagePropsTest_page_2',
				"just a dummy page",
				CONTENT_MODEL_WIKITEXT
			);
			$this->title2 = $page->getTitle();
			$page2ID = $this->title2->getArticleID();
			$this->setProperties( $page2ID, $this->the_properties );
		}
	}

	protected function tearDown() {
		global $wgExtraNamespaces, $wgNamespaceContentModels, $wgContentHandlers, $wgContLang;

		parent::tearDown();

		unset( $wgExtraNamespaces[12312] );
		unset( $wgExtraNamespaces[12313] );

		unset( $wgNamespaceContentModels[12312] );
		unset( $wgContentHandlers['DUMMY'] );

		MWNamespace::getCanonicalNamespaces( true ); # reset namespace cache
		$wgContLang->resetNamespaces(); # reset namespace cache
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
	 * set in setUp().
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
	 * subset of the retrieved properties. Since this version of PHPUnit
	 * does not yet include assertArraySubset(), we needed to code the
	 * equivalent functionality.
	 */
	public function testGetAllProperties() {
		$pageProps = PageProps::getInstance();
		$page1ID = $this->title1->getArticleID();
		$result = $pageProps->getAllProperties( $this->title1 );
		$this->assertArrayHasKey( $page1ID, $result, "Found properties" );
		$properties = $result[$page1ID];
		$patched = array_replace_recursive( $properties, $this->the_properties );
		$this->assertEquals( $patched, $properties, "Get all properties" );
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
		$properties1 = $result[$page1ID];
		$patched = array_replace_recursive( $properties1, $this->the_properties );
		$this->assertEquals( $patched, $properties1, "Get all properties page 1" );
		$properties2 = $result[$page2ID];
		$patched = array_replace_recursive( $properties2, $this->the_properties );
		$this->assertEquals( $patched, $properties2, "Get all properties page 2" );
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
				$page = MWNamespace::getCanonicalName( $ns ) . ':' . $page;
			}

			$page = Title::newFromText( $page );
		}

		if ( $page instanceof Title ) {
			$page = new WikiPage( $page );
		}

		if ( $page->exists() ) {
			$page->doDeleteArticle( "done" );
		}

		$content = ContentHandler::makeContent( $text, $page->getTitle(), $model );
		$page->doEditContent( $content, "testing", EDIT_NEW );

		return $page;
	}

	protected function setProperties( $pageID, $properties ) {

		$rows = [];

		foreach ( $properties as $propertyName => $propertyValue ) {

			$row = [
				'pp_page' => $pageID,
				'pp_propname' => $propertyName,
				'pp_value' => $propertyValue
			];

			$rows[] = $row;
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

		$properties = [];
		$properties[$propertyName] = $propertyValue;

		$this->setProperties( $pageID, $properties );

	}
}
