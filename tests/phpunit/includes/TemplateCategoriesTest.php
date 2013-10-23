<?php

/**
 * @group Database
 */
require __DIR__ . "/../../../maintenance/runJobs.php";

class TemplateCategoriesTest extends MediaWikiLangTestCase {

	/**
	 * @covers Title::getParentCategories
	 */
	public function testTemplateCategories() {
		$title = Title::newFromText( "Categorized from template" );
		$page = WikiPage::factory( $title );
		$user = new User();
		$user->mRights = array( 'createpage', 'edit', 'purge' );

		$page->doEditContent(
			new WikitextContent( '{{Categorising template}}' ),
			'Create a page with a template',
			0,
			false,
			$user
		);

		$this->assertEquals(
			array()
			, $title->getParentCategories()
		);

		$template = WikiPage::factory( Title::newFromText( 'Template:Categorising template' ) );

		$template->doEditContent(
			new WikitextContent( '[[Category:Solved bugs]]' ),
			'Add a category through a template',
			0,
			false,
			$user
		);

		// Run the job queue
		JobQueueGroup::destroySingletons();
		$jobs = new RunJobs;
		$jobs->loadParamsAndArgs( null, array( 'quiet' => true ), null );
		$jobs->execute();

		$this->assertEquals(
			array( 'Category:Solved_bugs' => $title->getPrefixedText() )
			, $title->getParentCategories()
		);
	}
}
