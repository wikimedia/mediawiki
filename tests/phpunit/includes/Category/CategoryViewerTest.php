<?php
namespace MediaWiki\Tests\Category;

use MediaWiki\Category\Category;
use MediaWiki\Category\CategoryViewer;
use MediaWiki\Context\IContextSource;
use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageReference;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Title\Title;
use MediaWikiIntegrationTestCase;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \MediaWiki\Category\CategoryViewer
 * @group Database
 * @group Category
 */
class CategoryViewerTest extends MediaWikiIntegrationTestCase {

	private function newCategoryViewer(
		?Title $category = null,
		array $requestValues = [],
		array $from = [],
		array $until = []
	): CategoryViewer {
		$category ??= Title::makeTitle( NS_CATEGORY, 'Example' );
		$context = new RequestContext();
		$context->setRequest( new FauxRequest( $requestValues ) );
		$context->setTitle( $category );
		return new CategoryViewer( $category, $context, $from, $until );
	}

	/**
	 * Populate a category with pages added one day apart, oldest first.
	 *
	 * @param string $name The category name
	 * @param string[] $pageNames
	 * @return Title The category
	 */
	private function makeCategoryWithPages( string $name, array $pageNames ): Title {
		$category = Title::makeTitle( NS_CATEGORY, $name );
		$this->editPage( $category, 'Category description' );

		foreach ( $pageNames as $i => $pageName ) {
			ConvertibleTimestamp::setFakeTime( '2020-01-0' . ( $i + 1 ) . 'T00:00:00Z' );
			$this->editPage(
				Title::makeTitle( NS_MAIN, $pageName ),
				"[[Category:{$category->getText()}]]"
			);
			$this->runDeferredUpdates();
		}
		ConvertibleTimestamp::setFakeTime( false );

		return $category;
	}

	public static function provideSortOrder() {
		yield 'default' => [ [], false, false ];
		yield 'by timestamp' => [ [ 'cldsort' => 'timestamp' ], true, false ];
		yield 'by timestamp, oldest first' => [
			[ 'cldsort' => 'timestamp', 'cldorder' => 'asc' ], true, false
		];
		yield 'by timestamp, newest first' => [
			[ 'cldsort' => 'timestamp', 'cldorder' => 'desc' ], true, true
		];
		yield 'unknown sort' => [ [ 'cldsort' => 'bogus' ], false, false ];
		// Reversing only applies to the timestamp ordering.
		yield 'reversed sortkey' => [ [ 'cldorder' => 'desc' ], false, false ];
	}

	/**
	 * @dataProvider provideSortOrder
	 */
	public function testSortOrderFromRequest(
		array $requestValues,
		bool $expectedByTimestamp,
		bool $expectedDescending
	) {
		$viewer = $this->newCategoryViewer( null, $requestValues );

		$this->assertSame( $expectedByTimestamp, $viewer->sortByTimestamp );
		$this->assertSame( $expectedDescending, $viewer->sortDescending );
	}

	public static function provideCategorysortProperty() {
		yield 'no magic word' => [ '', [], false, false ];
		yield 'TIMESTAMP' => [ '{{CATEGORYSORT:TIMESTAMP}}', [], true, false ];
		yield 'RTIMESTAMP' => [ '{{CATEGORYSORT:RTIMESTAMP}}', [], true, true ];
		yield 'RTIMESTAMP, order overridden by request' => [
			'{{CATEGORYSORT:RTIMESTAMP}}', [ 'cldorder' => 'asc' ], true, false
		];
		yield 'TIMESTAMP, sort overridden by request' => [
			'{{CATEGORYSORT:TIMESTAMP}}', [ 'cldsort' => 'sortkey' ], false, false
		];
	}

	/**
	 * The category page itself can declare the default ordering, which request
	 * parameters then override.
	 *
	 * @dataProvider provideCategorysortProperty
	 */
	public function testSortOrderFromPageProperty(
		string $wikitext,
		array $requestValues,
		bool $expectedByTimestamp,
		bool $expectedDescending
	) {
		$category = Title::makeTitle( NS_CATEGORY, 'CategoryViewerTest sort ' . md5( $wikitext ) );
		$this->editPage( $category, $wikitext . 'Category description' );
		$this->runDeferredUpdates();

		$viewer = $this->newCategoryViewer( $category, $requestValues );

		$this->assertSame( $expectedByTimestamp, $viewer->sortByTimestamp );
		$this->assertSame( $expectedDescending, $viewer->sortDescending );
	}

	/**
	 * Members are listed by the time they were added to the category, in either
	 * direction.
	 */
	public function testTimestampOrderedListing() {
		$pages = [ 'CategoryViewerTest first', 'CategoryViewerTest second', 'CategoryViewerTest third' ];
		$category = $this->makeCategoryWithPages( 'CategoryViewerTest timestamps', $pages );

		$viewer = $this->newCategoryViewer( $category, [ 'cldsort' => 'timestamp' ] );
		$viewer->getHTML();
		$this->assertSame( $pages, $this->extractPageNames( $viewer->articles ) );

		$viewer = $this->newCategoryViewer(
			$category,
			[ 'cldsort' => 'timestamp', 'cldorder' => 'desc' ]
		);
		$viewer->getHTML();
		$this->assertSame(
			array_reverse( $pages ),
			$this->extractPageNames( $viewer->articles )
		);
	}

	/**
	 * Paging forwards and backwards through a reversed timestamp listing.
	 */
	public function testReverseTimestampPaging() {
		$this->overrideConfigValue( MainConfigNames::CategoryPagingLimit, 2 );

		$pages = [ 'CategoryViewerTest one', 'CategoryViewerTest two', 'CategoryViewerTest three' ];
		$category = $this->makeCategoryWithPages( 'CategoryViewerTest paging', $pages );
		$request = [ 'cldsort' => 'timestamp', 'cldorder' => 'desc' ];

		// First page: the two most recently added members.
		$viewer = $this->newCategoryViewer( $category, $request );
		$viewer->getHTML();
		$this->assertSame(
			[ 'CategoryViewerTest three', 'CategoryViewerTest two' ],
			$this->extractPageNames( $viewer->articles )
		);
		$this->assertNotNull( $viewer->nextPage['page'] );

		// Following the "next" link shows the remaining, oldest member.
		$next = $this->newCategoryViewer(
			$category,
			$request,
			[ 'page' => $viewer->nextPage['page'] ]
		);
		$next->getHTML();
		$this->assertSame(
			[ 'CategoryViewerTest one' ],
			$this->extractPageNames( $next->articles )
		);
		$this->assertNull( $next->nextPage['page'] );

		// Going back from there returns to the first page, in the same order.
		$prev = $this->newCategoryViewer(
			$category,
			$request,
			[],
			[ 'page' => $viewer->nextPage['page'] ]
		);
		$prev->getHTML();
		$this->assertSame(
			[ 'CategoryViewerTest three', 'CategoryViewerTest two' ],
			$this->extractPageNames( $prev->articles )
		);
	}

	/**
	 * @param string[] $links HTML links as collected by CategoryViewer
	 * @return string[] The link titles, in order
	 */
	private function extractPageNames( array $links ): array {
		return array_map(
			static function ( string $link ): string {
				preg_match( '/title="([^"]*)"/', $link, $matches );
				return $matches[1] ?? $link;
			},
			$links
		);
	}

	/**
	 * An invalid 'from'/'until' offset must be ignored rather than causing fatal.
	 */
	public function testInvalidTimestampOffsetsAreIgnored() {
		$title = Title::makeTitle( NS_CATEGORY, 'Example' );
		$context = new RequestContext();
		$context->setTitle( $title );
		$context->setRequest( new FauxRequest( [ 'cldsort' => 'timestamp' ] ) );

		$viewer = new CategoryViewer(
			$title,
			$context,
			[ 'page' => '*', 'subcat' => '20240101000000', 'file' => null ],
			[ 'page' => '', 'subcat' => null, 'file' => 'nonsense' ]
		);

		$this->assertSame(
			[ 'page' => null, 'subcat' => '20240101000000', 'file' => null ],
			$viewer->from
		);
		$this->assertSame( [ 'page' => null, 'subcat' => null, 'file' => null ], $viewer->until );

		$this->assertIsString( $viewer->getHTML() );
	}

	/**
	 * Record the arguments that the CategoryViewerGenerateLink hook receives.
	 *
	 * @param array &$calls Filled with one entry per hook call
	 */
	private function recordGenerateLinkCalls( array &$calls ): void {
		$this->setTemporaryHook(
			'CategoryViewerGenerateLink',
			static function (
				IContextSource $context,
				string $type,
				PageReference $page,
				?string $html,
				?string &$link
			) use ( &$calls ) {
				$calls[] = [ 'type' => $type, 'html' => $html ];
			}
		);
	}

	/**
	 * An ordinary member page has no requested anchor text, so the hook must
	 * accept null. Regression test for T435161, where the parameter was
	 * declared as string and every category page with a member threw.
	 */
	public function testAddPagePassesNullHtmlToHook() {
		$calls = [];
		$this->recordGenerateLinkCalls( $calls );

		$viewer = $this->newCategoryViewer();
		$viewer->addPage( Title::makeTitle( NS_MAIN, 'CategoryViewerTest page' ), 'C', 100 );

		$this->assertSame( [ [ 'type' => 'page', 'html' => null ] ], $calls );
		$this->assertStringContainsString( 'CategoryViewerTest page', $viewer->articles[0] );
	}

	/**
	 * A subcategory strips the namespace prefix from the link text, so the hook
	 * receives that text as a string.
	 */
	public function testAddSubcategoryObjectPassesHtmlToHook() {
		$calls = [];
		$this->recordGenerateLinkCalls( $calls );

		$subcat = $this->getExistingTestPage(
			Title::makeTitle( NS_CATEGORY, 'CategoryViewerTest subcat' )
		);
		$viewer = $this->newCategoryViewer();
		$viewer->addSubcategoryObject( Category::newFromTitle( $subcat->getTitle() ), 'C', 100 );

		$this->assertSame(
			[ [ 'type' => 'subcat', 'html' => 'CategoryViewerTest subcat' ] ],
			$calls
		);
	}
}
