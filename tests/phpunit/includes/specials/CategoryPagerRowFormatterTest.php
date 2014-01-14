<?php

/**
 * @covers CategoryPagerRowFormatter
 *
 * @author Daniel Kinzler
 */
class CategoryPagerRowFormatterTest extends MediaWikiTestCase {

	/**
	 * Creates a CategoryPagerRowFormatter based on mock services,
	 * providing easy-to-verify output.
	 *
	 * @return CategoryPagerRowFormatter
	 */
	protected function newCategoryPagerRowFormatter() {
		// make a canonical context
		$context = new RequestContext( new FauxRequest() );
		$context->setLanguage( 'en' );

		// mock formatter providing an easy to validate title format
		$titleFormatter = $this->getMock( 'TitleFormatter' );
		$titleFormatter->expects( $this->any() )
			->method( 'format' )
			->will( $this->returnCallback( function( TitleValue $title ) {
				return 'Category:' . $title->getText() . '#' . $title->getSection();
			}) );

		// mock link renderer providing an easy to validate output
		$linkRenderer = $this->getMock( 'PageLinkRenderer' );
		$linkRenderer->expects( $this->any() )
			->method( 'renderLink' )
			->will( $this->returnCallback( function( TitleValue $title ) use ( $titleFormatter ) {
				return "Link:" . $titleFormatter->format( $title );
			}) );

		$formatter = new CategoryPagerRowFormatter( $context, $titleFormatter, $linkRenderer );
		return $formatter;
	}

	/**
	 * Test Formatting.
	 */
	public function testFormatRow( ) {
		$row = new stdClass();
		$row->cat_title = 'Test';
		$row->cat_pages = 25;

		$formatter = $this->newCategoryPagerRowFormatter();
		$html = $formatter->formatRow( $row );

		//$this->assertValidHtml( $html ); //TODO: use this once Idb98af785ca is merged
		$this->assertRegExp( '!<li.*Link:Category:Test.*25 members.*</li>!s', $html );
	}
}
