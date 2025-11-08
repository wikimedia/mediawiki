<?php

use MediaWiki\Content\WikitextContent;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Title\Title;

/**
 * @group Database
 */
class TemplateCategoriesTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();

		// Don't let PageStore hit WANObjectCache process cache for revision metadata
		$this->setMainCache( CACHE_NONE );
	}

	/**
	 * @covers \MediaWiki\Title\Title::getParentCategories
	 */
	public function testTemplateCategories() {
		$user = $this->getTestUser()->getUser();
		$this->overrideUserPermissions( $user, [ 'createpage', 'edit', 'purge', 'delete' ] );
		$wikiPageFactory = $this->getServiceContainer()->getWikiPageFactory();

		$title = Title::makeTitle( NS_MAIN, "Categorized from template" );
		$page = $wikiPageFactory->newFromTitle( $title );
		$page->doUserEditContent(
			new WikitextContent( '{{Categorising template}}' ),
			$user,
			'Create a page with a template'
		);

		$this->assertEquals(
			[],
			$title->getParentCategories(),
			'Verify that the category doesn\'t contain the page before the template is created'
		);

		// Create template
		$template = $wikiPageFactory->newFromTitle( Title::makeTitle( NS_TEMPLATE, 'Categorising template' ) );
		$template->doUserEditContent(
			new WikitextContent( '[[Category:Solved bugs]]' ),
			$user,
			'Add a category through a template'
		);

		$this->runJobs();
		DeferredUpdates::doUpdates();

		// Make sure page is in the category
		$this->assertEquals(
			[ 'Category:Solved_bugs' => $title->getPrefixedText() ],
			$title->getParentCategories(),
			'Verify that the page is in the category after the template is created'
		);

		// Edit the template
		$template->doUserEditContent(
			new WikitextContent( '[[Category:Solved bugs 2]]' ),
			$user,
			'Change the category added by the template'
		);

		$this->runJobs();
		DeferredUpdates::doUpdates();

		// Make sure page is in the right category
		$this->assertEquals(
			[ 'Category:Solved_bugs_2' => $title->getPrefixedText() ],
			$title->getParentCategories(),
			'Verify that the page is in the right category after the template is edited'
		);

		// Now delete the template
		$this->deletePage( $template, 'Delete the template', $user );

		$this->runJobs();
		DeferredUpdates::doUpdates();

		// Make sure the page is no longer in the category
		$this->assertEquals(
			[],
			$title->getParentCategories(),
			'Verify that the page is no longer in the category after template deletion'
		);
	}
}
