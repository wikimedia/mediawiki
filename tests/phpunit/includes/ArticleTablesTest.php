<?php

/**
 * @group Database
 */
class ArticleTablesTest extends MediaWikiLangTestCase {

	function testbug14404() {
		global $wgContLang, $wgLanguageCode, $wgLang;

		$title = Title::newFromText( 'Bug 14404' );
		$page = WikiPage::factory( $title );
		$user = new User();
		$user->mRights = array( 'createpage', 'edit', 'purge' );
		$wgLanguageCode = 'es';
		$wgContLang = Language::factory( 'es' );

		$wgLang = Language::factory( 'fr' );
		$status = $page->doEdit( '{{:{{int:history}}}}', 'Test code for bug 14404', 0, false, $user );
		$templates1 = $title->getTemplateLinksFrom();

		$wgLang = Language::factory( 'de' );
		$page->mPreparedEdit = false; // In order to force the rerendering of the same wikitext

		// We need an edit, a purge is not enough to regenerate the tables
		$status = $page->doEdit( '{{:{{int:history}}}}', 'Test code for bug 14404', EDIT_UPDATE, false, $user );
		$templates2 = $title->getTemplateLinksFrom();

		$this->assertEquals( $templates1, $templates2 );
		$this->assertEquals( $templates1[0]->getFullText(), 'Historial' );
	}

}
