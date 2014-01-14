<?php

/**
 * @covers CategoryPager
 *
 * @author Daniel Kinzler
 *
 * @group Database
 */
class CategoryPagerTest extends MediaWikiTestCase {

	public function setUp() {
		$this->tablesUsed[] = 'category';

		parent::setUp();

		$this->insertRows(
			'category',
			array( 'cat_title', 'cat_pages' ),
			array(
				array( 'Foo', 3 ),
				array( 'Bar', 7 ),
				array( 'Xyzzy', 11 ),
				array( 'Quux', 2 ),
			) );
	}

	protected function insertRows( $table, $fields, $rows ) {
		$dbw = wfGetDB( DB_MASTER );

		$dbw->delete( $table, '1=1' );

		foreach ( $rows as $row ) {
			$dbw->insert( $table, array_combine( $fields, $row ), __METHOD__ );
		}
	}

	/**
	 * Make a mock pager
	 *
	 * @param $from
	 *
	 * @return PHPUnit_Framework_MockObject_MockObject
	 */
	public function buildPager( $from ) {
		$pager = $this->getMockBuilder( 'CategoryPager' )->disableOriginalConstructor()->getMock();

		return $pager;
	}

	/**
	 * Make a CategoryPager page for the given request, using a mock pager
	 * to cut out any database access.
	 *
	 * @param array $params request parameters
	 *
	 * @return CategoryPager
	 */
	protected function newCategoryPager( array $params ) {
		// make a canonical context
		$context = new RequestContext( new FauxRequest( $params ) );
		$context->setLanguage( 'en' );

		// mock formatter providing an easy to validate format
		$rowFormatter = $this->getMockBuilder( 'CategoryPagerRowFormatter' )
			->disableOriginalConstructor()
			->getMock();

		$rowFormatter->expects( $this->any() )
			->method( 'formatRow' )
			->will( $this->returnCallback( function( $row ) {
				return "\n%%" . $row->cat_title . '|' . $row->cat_pages . "%%;\n";
			}) );

		$from = isset( $params['from'] ) ? $params['from'] : '';

		$pager = new CategoryPager( $context, $from, $rowFormatter );
		return $pager;
	}

	public function provideExpectedRows() {
		//See setUp() for the rows present in the database

		return array(
			'all' => array(
				array(),
				array(
					'Bar|7',
					'Foo|3',
					'Quux|2',
					'Xyzzy|11',
				)
			),
			'from' => array(
				array( 'from' => 'Quux' ),
				array(
					'Quux|2',
					'Xyzzy|11',
				)
			),
		);
	}

	/**
	 * Test pager query execution.
	 *
	 * @dataProvider provideExpectedRows
	 */
	public function testDoQuery( $params, $expectedRows ) {
		$pager = $this->newCategoryPager( $params );

		$pager->doQuery();

		$html = $pager->getBody();
		$this->assertValidHtmlSnippet( $html );
		//TODO: validate any top or bottom structures, like forms

		// Since we are using a mock row formatter, the HTML will be trivial,
		// and can easily be split.
		preg_match_all( '/%%(.*?)%%/', $html, $matches, PREG_PATTERN_ORDER );
		$actualRows = $matches[1];

		$this->assertArrayEquals( $expectedRows, $actualRows );
	}
}
