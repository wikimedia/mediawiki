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

		// mock link renderer providing an easy to validate output
		$linkRenderer = $this->getMock( 'PageLinkRenderer' );
		$linkRenderer->expects( $this->any() )
			->method( 'renderHtmlLink' )
			->will( $this->returnCallback( function( TitleValue $title ) {
				return '<a>Link:' . htmlspecialchars( "$title" ) . '</a>';
			}) );

		$formatter = new CategoryPagerRowFormatter( $context, $linkRenderer );
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

		$this->assertRegExp( '!<li.*<a>Link:14:Test</a>.*25 members.*</li>!s', $html );
		$this->assertValidHtmlSnippet( '<ul>' . $html . '</ul>' );
	}
}
