<?php

/**
 * @group Database
 */
require dirname( __FILE__ ) . "/../../../maintenance/runJobs.php";

class TemplateCategoriesTest extends MediaWikiLangTestCase {

	function testTemplateCategories() {
		global $wgUser;

		$title = Title::newFromText( "Categorized from template" );
		$article = new Article( $title );
		$wgUser = new User();
		$wgUser->mRights['*'] = array( 'createpage', 'edit', 'purge' );

		$status = $article->doEdit( '{{Categorising template}}', 'Create a page with a template', 0 );
		$this->assertEquals(
			array()
			, $title->getParentCategories()
		);

		$template = new Article( Title::newFromText( 'Template:Categorising template' ) );
		$status = $template->doEdit( '[[Category:Solved bugs]]', 'Add a category through a template', 0 );

		// Run the job queue
		$jobs = new RunJobs;
		$jobs->loadParamsAndArgs( null, array( 'quiet' => true ), null );
		$jobs->execute();

		$this->assertEquals(
			array( 'Category:Solved_bugs' => $title->getPrefixedText() )
			, $title->getParentCategories()
		);
	}

}
