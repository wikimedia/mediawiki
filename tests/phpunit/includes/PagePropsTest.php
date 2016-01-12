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
	 * @var WikiPage $the_page
	 */
	private $the_page;

	/**
	 * @var int $the_page_id
	 */
	private $the_page_id;

	protected function setUp() {
		global $wgExtraNamespaces, $wgNamespaceContentModels, $wgContentHandlers, $wgContLang;

		parent::setUp();

		$wgExtraNamespaces[12312] = 'Dummy';
		$wgExtraNamespaces[12313] = 'Dummy_talk';

		$wgNamespaceContentModels[12312] = 'DUMMY';
		$wgContentHandlers['DUMMY'] = 'DummyContentHandlerForTesting';

		MWNamespace::getCanonicalNamespaces( true ); # reset namespace cache
		$wgContLang->resetNamespaces(); # reset namespace cache
		if ( !$this->the_page ) {
			$this->the_page = $this->createPage(
				'PagePropsTest_the_page',
				"just a dummy page",
				CONTENT_MODEL_WIKITEXT
			);

			$this->the_page_id = $this->the_page->getTitle()->getArticleID();

			$properties = array(
				"property1" => "value1",
				"property2" => "value2",
				"property3" => "value3",
				"property4" => "value4"
			);
			$this->setProperties( $properties );
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

	public function testGetSingleProperty() {
		$title = $this->the_page->getTitle();
		$props = new PageProps( $title );
		$value = $props->getProperty( "property1" );
		$this->assertEquals( $value, "value1", "Get property" );
	}

	public function testGetAllProperties() {
		$title = $this->the_page->getTitle();
		$props = new PageProps( $title );
		$this->assertEquals( $this->getProperties(), $props->getProperties(), "Get all properties" );
	}

	public function testSingleCache() {
		$title = $this->the_page->getTitle();
		$props = new PageProps( $title );
		$value1 = $props->getProperty( "property1" );
		$this->setProperty( "property1", "another value" );
		$value2 = $props->getProperty( "property1" );
		$this->assertEquals( $value1, $value2, "Single cache" );
	}

	public function testMultiCache() {
		$title = $this->the_page->getTitle();
		$props = new PageProps( $title );
		$properties1 = $props->getProperties();
		$this->setProperty( "property1", "another value" );
		$properties2 = $props->getProperties();
		$this->assertEquals( $properties1, $properties2, "Multi Cache" );
	}

	public function testClearCache() {
		$title = $this->the_page->getTitle();
		$props = new PageProps( $title );
		$value1 = $props->getProperty( "property1" );
		$this->setProperty( "property1", "another value" );
		$props->getProperties();
		$value2 = $props->getProperty( "property1" );
		$this->assertNotEquals( $value1, $value2, "Clear cache" );
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

	protected function setProperties( $properties ) {

		$rows = array();

		foreach ( $properties as $propertyName => $propertyValue ) {

			$row = array(
				'pp_page' => $this->the_page_id,
				'pp_propname' => $propertyName,
				'pp_value' => $propertyValue
			);

			$rows[] = $row;
		}

		$dbw = wfGetDB( DB_MASTER );
		$dbw->replace(
			'page_props',
			array(
				array(
					'pp_page',
					'pp_propname'
				)
			),
			$rows,
			__METHOD__
		);
	}

	protected function getProperties() {

		$dbr = wfGetDB( DB_SLAVE );
		$result = $dbr->select(
			'page_props',
			array(
				'pp_propname',
				'pp_value'
			),
			array(
				'pp_page' => $this->the_page_id,
			),
			__METHOD__
		);

		$pageProperties = array();

		foreach ( $result as $row ) {
			$pageProperties[$row->pp_propname] = $row->pp_value;
		}

		return $pageProperties;
	}
	protected function setProperty( $propertyName, $propertyValue ) {

		$properties = array();
		$properties[$propertyName] = $propertyValue;

		$this->setProperties( $properties );

	}
}
