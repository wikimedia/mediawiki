<?php

use MediaWiki\Title\Title;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\LBFactorySingle;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Page\PageProps
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

	public function testGetProperties_singlePropForSinglePage() {
		$pageProps = $this->getServiceContainer()->getPageProps();
		$page1ID = $this->title1->getArticleID();
		$result = $pageProps->getProperties( $this->title1, "property1" );
		$this->assertSame( $result, [ $page1ID => 'value1' ], 'Fresh value' );

		// Test caching when retrieving single properties by getting a property,
		// saving a new value for the property, then getting the property
		// again. The cached value for the property rather than the new value
		// of the property should be returned.

		$this->setProperty( $page1ID, 'property1', 'ANewHopeee' );
		$result = $pageProps->getProperties( $this->title1, 'property1' );
		$this->assertSame( $result, [ $page1ID => 'value1' ], 'Cached value' );
	}

	public function testGetProperties_singlePropAbsent() {
		$pageProps = $this->getServiceContainer()->getPageProps();
		$page1ID = $this->title1->getArticleID();
		$result = $pageProps->getProperties( $this->title1, 'numberNine' );
		$this->assertSame( [], $result, 'Fresh result' );

		// Support caching absence (T297300), disable database to confirm no repeat queries
		TestingAccessWrapper::newFromObject( $pageProps )->dbProvider = LBFactorySingle::newDisabled();
		$result = $pageProps->getProperties( $this->title1, 'numberNine' );
		$this->assertSame( [], $result, 'Cached result' );

		// Confirm database disabling actually worked,
		// and confirm that we still try to read data for other pages
		$this->expectException( RuntimeException::class );
		$this->expectExceptionMessage( 'Database backend disabled' );
		$pageProps->getProperties( $this->title2, 'numberNine' );
	}

	public function testGetProperties_singlePropForMultipleTitleObjects() {
		$pageProps = $this->getServiceContainer()->getPageProps();
		$page1ID = $this->title1->getArticleID();
		$page2ID = $this->title2->getArticleID();
		$titles = [
			$this->title1,
			$this->title2
		];
		$result = $pageProps->getProperties( $titles, "property1" );
		$this->assertSame( $result, [
			$page1ID => 'value1',
			$page2ID => 'value1'
		] );
	}

	public function testGetProperties_singlePropForTitleArrayFromResult() {
		$services = $this->getServiceContainer();
		$pageProps = $services->getPageProps();
		$page1ID = $this->title1->getArticleID();
		$page2ID = $this->title2->getArticleID();
		$rows = [
			$this->createRowFromTitle( $this->title1 ),
			$this->createRowFromTitle( $this->title2 )
		];
		$resultWrapper = new FakeResultWrapper( $rows );
		$titles = $services->getTitleFactory()->newTitleArrayFromResult( $resultWrapper );
		$result = $pageProps->getProperties( $titles, "property1" );
		$this->assertSame( $result, [
			$page1ID => 'value1',
			$page2ID => 'value1'
		] );
	}

	public function testGetProperties_singlePropForMultiplePages() {
		$pageProps = $this->getServiceContainer()->getPageProps();
		$page1ID = $this->title1->getArticleID();
		$page2ID = $this->title2->getArticleID();
		$titles = [
			$this->title1->toPageIdentity(),
			$this->title2->toPageIdentity()
		];
		$result = $pageProps->getProperties( $titles, [ 'property1', 'property2' ] );
		$this->assertSame( $result, [
			$page1ID => [ 'property1' => 'value1', 'property2' => 'value2' ],
			$page2ID => [ 'property1' => 'value1', 'property2' => 'value2' ],
		] );
	}

	public function testGetAllProperties_singleTitle() {
		$pageProps = $this->getServiceContainer()->getPageProps();
		$page1ID = $this->title1->getArticleID();
		$result = $pageProps->getAllProperties( $this->title1 );
		$this->assertSame( $result, [
			$page1ID => [
				'property1' => 'value1',
				'property2' => 'value2',
				'property3' => 'value3',
				'property4' => 'value4',
			]
		], 'Fresh values' );

		// Save a new value for a property, then get all properties again.
		// The cached value for the properties rather than the new value of
		// the properties should be returned.
		$this->setProperty( $page1ID, 'property1', 'different value' );
		$result2 = $pageProps->getAllProperties( $this->title1 );
		$this->assertSame( $result, $result2, 'Cached values unchanged' );
	}

	public function testGetAllProperties_multiplePages() {
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

	public function testGetAllProperties_ignoreAndReplaceSinglePropCache() {
		$pageProps = $this->getServiceContainer()->getPageProps();
		$page1ID = $this->title1->getArticleID();

		// Warm up cache for a single property
		$pageProps->getProperties( $this->title1, 'property1' );

		// Save a new value for this property
		$this->setProperty( $page1ID, 'property1', 'ANewHopeee' );

		// This should ignore the above cache
		$result = $pageProps->getAllProperties( $this->title1 );
		$this->assertSame( $result, [
			$page1ID => [
				'property1' => 'ANewHopeee',
				'property2' => 'value2',
				'property3' => 'value3',
				'property4' => 'value4',
			]
		], 'Fresh values' );

		// .. and have replaced the single prop cache
		$result = $pageProps->getProperties( $this->title1, 'property1' );
		$this->assertSame( $result, [ $page1ID => 'ANewHopeee' ], 'Updated cache' );
	}

	protected function setProperties( $pageID, array $properties ) {
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

	protected function setProperty( $pageID, string $propertyName, $propertyValue ) {
		$this->setProperties( $pageID, [
			$propertyName => $propertyValue
		] );
	}

	protected function createRowFromTitle( $title ) {
		return (object)[
			'page_namespace' => $title->getNamespace(),
			'page_title' => $title->getText()
		];
	}
}
