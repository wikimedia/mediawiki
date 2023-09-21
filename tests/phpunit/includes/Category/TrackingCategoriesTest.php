<?php

use MediaWiki\Category\TrackingCategories;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Page\PageReferenceValue;

/**
 * @covers ParserOutput
 * @covers CacheTime
 * @group Database
 *        ^--- trigger DB shadowing because we are using Title magic
 */
class TrackingCategoriesTest extends MediaWikiLangTestCase {
	/**
	 * @covers TrackingCategories::addTrackingCategory
	 */
	public function testAddTrackingCategory() {
		$services = $this->getServiceContainer();
		$tc = new TrackingCategories(
			new ServiceOptions(
				TrackingCategories::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig()
			),
			$services->getNamespaceInfo(),
			$services->getTitleParser(),
			LoggerFactory::getInstance( 'TrackingCategories' )
		);

		$po = new ParserOutput;
		$po->setPageProperty( 'defaultsort', 'foobar' );

		$page = PageReferenceValue::localReference( NS_USER, 'Testing' );

		$tc->addTrackingCategory( $po, 'index-category', $page ); // from CORE_TRACKING_CATEGORIES
		$tc->addTrackingCategory( $po, 'sitenotice', $page ); // should be "-", which is ignored
		$tc->addTrackingCategory( $po, 'brackets-start', $page ); // invalid text
		// TODO: assert proper handling of non-existing messages

		$expected = wfMessage( 'index-category' )
			->page( $page )
			->inContentLanguage()
			->text();

		$expected = strtr( $expected, ' ', '_' );
		$this->assertSame( [ $expected => 'foobar' ], $po->getCategoryMap() );
	}
}
