<?php

/**
 * @group Database
 */
class TemplateCategoriesTest extends MediaWikiLangTestCase {

	/**
	 * Broken per T165099.
	 *
	 * @group Broken
	 * @covers Title::getParentCategories
	 */
	public function testTemplateCategories() {
		$user = new User();
		$this->overrideUserPermissions( $user, [ 'createpage', 'edit', 'purge', 'delete' ] );

		$title = Title::newFromText( "Categorized from template" );
		$page = WikiPage::factory( $title );
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
		$template = WikiPage::factory( Title::newFromText( 'Template:Categorising template' ) );
		$template->doUserEditContent(
			new WikitextContent( '[[Category:Solved bugs]]' ),
			$user,
			'Add a category through a template'
		);

		// Run the job queue
		$this->runJobs();

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

		// Run the job queue
		$this->runJobs();

		// Make sure page is in the right category
		$this->assertEquals(
			[ 'Category:Solved_bugs_2' => $title->getPrefixedText() ],
			$title->getParentCategories(),
			'Verify that the page is in the right category after the template is edited'
		);

		// Now delete the template
		$this->deletePage( $template, 'Delete the template', $user );

		// Run the job queue
		$this->runJobs();

		// Make sure the page is no longer in the category
		$this->assertEquals(
			[],
			$title->getParentCategories(),
			'Verify that the page is no longer in the category after template deletion'
		);
	}
}
