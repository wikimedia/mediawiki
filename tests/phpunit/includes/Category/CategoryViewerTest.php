<?php
namespace MediaWiki\Tests\Category;

use MediaWiki\Category\Category;
use MediaWiki\Category\CategoryViewer;
use MediaWiki\Context\IContextSource;
use MediaWiki\Context\RequestContext;
use MediaWiki\Page\PageReference;
use MediaWiki\Title\Title;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Category\CategoryViewer
 * @group Database
 * @group Category
 */
class CategoryViewerTest extends MediaWikiIntegrationTestCase {

	private function newCategoryViewer(): CategoryViewer {
		return new CategoryViewer(
			Title::makeTitle( NS_CATEGORY, 'Example' ),
			RequestContext::getMain()
		);
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
