<?php

use MediaWiki\Category\TrackingCategories;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Parser\ParserOutput;

/**
 * @covers \MediaWiki\Parser\ParserOutput
 * @covers \MediaWiki\Parser\CacheTime
 * @group Database
 *        ^--- trigger DB shadowing because we are using Title magic
 */
class TrackingCategoriesTest extends MediaWikiLangTestCase {
	/**
	 * @covers \MediaWiki\Category\TrackingCategories::addTrackingCategory
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
		$po->setUnsortedPageProperty( 'defaultsort', 'foobar' );

		$page = PageReferenceValue::localReference( NS_USER, 'Testing' );

		$tc->addTrackingCategory( $po, 'index-category', $page ); // from CORE_TRACKING_CATEGORIES
		$tc->addTrackingCategory( $po, 'sitenotice', $page ); // should be "-", which is ignored
		$tc->addTrackingCategory( $po, 'brackets-start', $page ); // invalid text
		// TODO: assert proper handling of non-existing messages

		$expected = wfMessage( 'index-category' )
			->page( $page )
			->inContentLanguage()
			->text();

		// Note that the DEFAULTSORT is applied when the category links table
		// is updated, so 'foobar' does not appear in the CategoryMap here.
		$expected = strtr( $expected, ' ', '_' );
		$this->assertSame( [ $expected => '' ], $po->getCategoryMap() );
	}
}
