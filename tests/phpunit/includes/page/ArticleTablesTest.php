<?php

/**
 * @group Database
 */
class ArticleTablesTest extends MediaWikiLangTestCase {
	/**
	 * Make sure that bug 14404 doesn't strike again. We don't want
	 * templatelinks based on the user language when {{int:}} is used, only the
	 * content language.
	 *
	 * @covers Title::getTemplateLinksFrom
	 * @covers Title::getLinksFrom
	 */
	public function testTemplatelinksUsesContentLanguage() {
		$title = Title::newFromText( 'Bug 14404' );
		$page = WikiPage::factory( $title );
		$user = new User();
		$user->mRights = [ 'createpage', 'edit', 'purge' ];
		$this->setContentLang( 'es' );
		$this->setUserLang( 'fr' );

		$page->doEditContent(
			new WikitextContent( '{{:{{int:history}}}}' ),
			'Test code for bug 14404',
			0,
			false,
			$user
		);
		$templates1 = $title->getTemplateLinksFrom();

		$this->setUserLang( 'de' );
		$page = WikiPage::factory( $title ); // In order to force the re-rendering of the same wikitext

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
