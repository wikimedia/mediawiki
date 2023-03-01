<?php

use MediaWiki\Title\Title;

/**
 * @group Database
 */
class ArticleTablesTest extends MediaWikiLangTestCase {

	/**
	 * Make sure that T16404 doesn't strike again. We don't want
	 * templatelinks based on the user language when {{int:}} is used, only the
	 * content language.
	 *
	 * @covers Title::getTemplateLinksFrom
	 * @covers Title::getLinksFrom
	 */
	public function testTemplatelinksUsesContentLanguage() {
		$title = Title::makeTitle( NS_MAIN, 'T16404' );
		$wikiPageFactory = $this->getServiceContainer()->getWikiPageFactory();
		$page = $wikiPageFactory->newFromTitle( $title );
		$user = new User();
		$this->overrideUserPermissions( $user, [ 'createpage', 'edit', 'purge' ] );
		$this->setContentLang( 'es' );
		$this->setUserLang( 'fr' );

		$page->doUserEditContent(
			new WikitextContent( '{{:{{int:history}}}}' ),
			$user,
			'Test code for T16404'
		);
		$templates1 = $title->getTemplateLinksFrom();

		$this->setUserLang( 'de' );
		$page = $wikiPageFactory->newFromTitle( $title ); // In order to force the re-rendering of the same wikitext

		// We need an edit, a purge is not enough to regenerate the tables
		$page->doUserEditContent(
			new WikitextContent( '{{:{{int:history}}}}' ),
			$user,
			'Test code for T16404',
			EDIT_UPDATE
		);
		$templates2 = $title->getTemplateLinksFrom();

		/**
		 * @var Title[] $templates1
		 * @var Title[] $templates2
		 */
		$this->assertEquals( $templates1, $templates2 );
		$this->assertSame( 'Historial', $templates1[0]->getFullText() );
	}
}
