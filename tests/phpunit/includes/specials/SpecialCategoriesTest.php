<?php

/**
 * @covers SpecialCategories
 *
 * @author Daniel Kinzler
 *
 * @group Database
 *        ^--- even though we try to cut out any database access for this test,
 *             we can't be totally sure yet that *no* database access will be done,
 *             mainly due to Title objects being used somewhere deep down.
 */
class SpecialCategoriesTest extends MediaWikiTestCase {

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
	 * Make a SpecialCategories page for the given request, using a mock pager
	 * to cut out any database access.
	 *
	 * @param WebRequest $request
	 *
	 * @return SpecialCategories
	 */
	protected function newSpecialCategoriesPage( WebRequest $request = null ) {
		if ( $request === null ) {
			$request = new FauxRequest();
		}

		// make a canonical context
		$context = new RequestContext( $request );
		$context->setLanguage( 'en' );

		$page = new SpecialCategories();
		$page->setContext( $context );

		$page->setPagerBuilder( array( $this, 'buildPager' ) );
		return $page;
	}

	/**
	 * Test special page execution. Note that this does not do much, since we
	 * use a mock pager. If a full stack integration test is desired, that should
	 * be done in a separate function and/or class.
	 */
	public function testExecute() {
		$page = $this->newSpecialCategoriesPage();

		$page->execute( null );

		// Just check the HTML structure
		// Since we mocked otu the pager, execute() isn't doing anything interesting really.

		$html = $page->getOutput()->getHTML();
		$this->assertValidHtmlSnippet( $html );
	}
}
