<?php

/**
 * @group Database
 */
class ArticleTablesTest extends MediaWikiLangTestCase {

	/**
	 * @covers Title::getTemplateLinksFrom
	 * @covers Title::getLinksFrom
	 * @todo give this test a real name explaining what is being tested here
	 */
	public function testbug14404() {
		$title = Title::newFromText( 'Bug 14404' );
		$page = WikiPage::factory( $title );
		$user = new User();
		$user->mRights = array( 'createpage', 'edit', 'purge' );
		$this->setMwGlobals( 'wgLanguageCode', 'es' );
		$this->setMwGlobals( 'wgContLang', Language::factory( 'es' ) );
		$this->setMwGlobals( 'wgLang', Language::factory( 'fr' ) );

		$page->doEditContent(
			new WikitextContent( '{{:{{int:history}}}}' ),
			'Test code for bug 14404',
			0,
			false,
			$user
		);
		$templates1 = $title->getTemplateLinksFrom();

<<<<<<< HEAD   (304fd6 Merge remote-tracking branch 'origin/REL1_22' into fundraisi)
		$wgLang = Language::factory( 'de' );
		$page = WikiPage::factory( $title ); // In order to force the rerendering of the same wikitext
=======
		$this->setMwGlobals( 'wgLang', Language::factory( 'de' ) );
		$page = WikiPage::factory( $title ); // In order to force the re-rendering of the same wikitext
>>>>>>> BRANCH (f3d821 Updated release notes and version number to MediaWiki 1.23.3)

		// We need an edit, a purge is not enough to regenerate the tables
		$page->doEditContent(
			new WikitextContent( '{{:{{int:history}}}}' ),
			'Test code for bug 14404',
			EDIT_UPDATE,
			false,
			$user
		);
		$templates2 = $title->getTemplateLinksFrom();

		/**
		 * @var Title[] $templates1
		 * @var Title[] $templates2
		 */
		$this->assertEquals( $templates1, $templates2 );
		$this->assertEquals( $templates1[0]->getFullText(), 'Historial' );
	}
}
