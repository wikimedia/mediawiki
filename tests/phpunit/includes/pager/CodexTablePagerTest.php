<?php

use HtmlFormatter\HtmlFormatter;
use MediaWiki\Pager\CodexTablePager;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\TestingAccessWrapper;
use Wikimedia\Zest\Zest;

/**
 * @covers \MediaWiki\Pager\CodexTablePager
 * @group Database
 */
class CodexTablePagerTest extends MediaWikiIntegrationTestCase {
	/**
	 * @var TestingAccessWrapper
	 */
	private $pager;

	protected function setUp(): void {
		parent::setUp();

		// Create a concrete implementation of the abstract CodexTablePager.
		// The parent class requires a constructor argument, hence the parentheses.
		$pager = new class( "Dummy Caption" ) extends CodexTablePager {
			public function getFieldNames() {
				return [
					'id' => 'ID',
					'name' => 'Name'
				];
			}

			public function getQueryInfo() {
				return [
					'tables' => 'dummy_table',
					'fields' => [ 'id', 'name' ],
					'conds' => [],
				];
			}

			public function getIndexField() {
				return 'id';
			}

			public function formatValue( $name, $value ) {
				return htmlspecialchars( $value );
			}

			protected function isFieldSortable( $field ) {
				return false;
			}

			public function getDefaultSort() {
				return '';
			}

			public function reallyDoQuery( $offset, $limit, $order ) {
				$rows = [
					[ 'id' => 1, 'name' => 'foo' ],
					[ 'id' => 2, 'name' => 'bar' ],
				];

				return new FakeResultWrapper( $rows );
			}
		};

		// Wrap our instance with TestingAccessWrapper so that private/protected
		// methods are accessible in our tests
		$this->pager = TestingAccessWrapper::newFromObject( $pager );
	}

	/**
	 * Convenience function to convert strings of markup into a parse-able document
	 * that we can query with tools like Zest.
	 *
	 * @param string $markup
	 * @return DOMDocument
	 */
	private static function getOutputHtml( string $markup ) {
		$html = HtmlFormatter::wrapHTML( $markup );
		return ( new HtmlFormatter( $html ) )->getDoc();
	}

	public function testGetEmptyBody() {
		$doc = self::getOutputHtml( $this->pager->getEmptyBody() );

		// Find the <td> element inside that document with Zest
		$td = Zest::find( 'td', $doc )[ 0 ];

		// Check the colspan
		$this->assertEquals(
			count( $this->pager->getFieldNames() ),
			$td->getAttribute( 'colspan' )
		);
	}

	public function testGetCellAttrs() {
		$field1 = $this->pager->getFieldNames()[ 'id' ];
		$expected1 = [
			'class' => [
				'cdx-table-pager__col--' . $field1,
				'cdx-table__table__cell--align-' . $this->pager->getCellAlignment( $field1 )
			]
		];

		$field2 = $this->pager->getFieldNames()[ 'name' ];
		$expected2 = [
			'class' => [
				'cdx-table-pager__col--' . $field2,
				'cdx-table__table__cell--align-' . $this->pager->getCellAlignment( $field2 )
			]
		];

		// Test that cells have expected class names
		$this->assertEquals( $expected1, $this->pager->getCellAttrs( $field1, '' ) );
		$this->assertEquals( $expected2, $this->pager->getCellAttrs( $field2, '' ) );
	}

	public function testGetBodyOutput() {
		$doc = self::getOutputHtml( $this->pager->getBodyOutput()->getRawText() );
		$table = Zest::find( 'table', $doc )[ 0 ];
		$rows = Zest::find( 'tr', $doc );
		$caption = Zest::find( 'caption', $doc );
		$captionText = $caption[ 0 ]->textContent;
		// Test that the <table> has the correct class name
		$this->assertEquals( 'cdx-table__table', $table->getAttribute( 'class' ) );
		// Test that there are 3 rows: 2 for content plus the header
		$this->assertCount( 3, $rows );
		// Test that the first row contains table header elements
		$this->assertCount( 2, Zest::find( 'th', $rows[ 0 ] ) );
		// Test that the row has the correct number of columns
		$this->assertCount( 2, Zest::find( 'td', $rows[ 1 ] ) );
		// Test that rows don't have any CSS class by default
		$this->assertCount( 0, $rows[ 1 ]->attributes );
		// Test that the table includes a <caption> element
		$this->assertCount( 1, $caption );
		$this->assertEquals( "Dummy Caption", $captionText );
	}
}
