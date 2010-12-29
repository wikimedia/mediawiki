<?php

/**
 * @group Database
 * @group Destructive
 */
class ArticleTablesTest extends MediaWikiTestCase {
	
	function testbug14404() {
		global $wgUser, $wgContLang, $wgLang;
		
		$title = Title::newFromText("Bug 14404");
		$article = new Article( $title );
		$wgUser = new User();
		$wgUser->mRights = array( 'createpage', 'edit', 'purge' );
		$wgContLang = Language::factory( 'es' );
		
		$wgLang = Language::factory( 'fr' );
		$status = $article->doEdit( '{{:{{int:history}}}}', 'Test code for bug 14404', 0 );
		$templates1 = $article->getUsedTemplates();

		$wgLang = Language::factory( 'de' );		
		$article->mParserOptions = null; // Let it pick the new user language
		$article->mPreparedEdit = false; // In order to force the rerendering of the same wikitext
		
		// We need an edit, a purge is not enough to regenerate the tables
		$status = $article->doEdit( '{{:{{int:history}}}}', 'Test code for bug 14404', EDIT_UPDATE );
		$templates2 = $article->getUsedTemplates();
		
		$this->assertEquals( $templates1, $templates2 );
		$this->assertEquals( $templates1[0]->getFullText(), 'Historial' );
	}
	
}
