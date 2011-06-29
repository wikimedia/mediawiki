<?php

class ArticleTest extends MediaWikiTestCase {
	function testBCMagic() {
		$title = Title::makeTitle( NS_MAIN, 'somePage' );
		$article = new Article( $title );
		
		$this->assertEquals( -1, $article->mCounter, "Article __get magic" );

		$article->mCounter = 2;
		$this->assertEquals( 2, $article->mCounter, "Article __set magic" );

		$this->assertEquals( 2, $article->getCount(), "Article __call magic" );

		$this->assertEquals( WikiPage::selectFields(), Article::selectFields(),
			"Article static functions" );
		$this->assertEquals( null, Article::onArticleCreate( $title ),
			"Article static functions" );
		$this->assertEquals( null, Article::onArticleDelete( $title ),
			"Article static functions" );
		$this->assertEquals( null, ImagePage::onArticleEdit( $title ),
			"Article static functions" );
		$this->assertTrue( is_string( CategoryPage::getAutosummary( '', '', 0 ) ),
			"Article static functions" );

		$article->ext_someNewProperty = 12;
		$this->assertEquals( 12, $article->ext_someNewProperty,
			"Article get/set magic on new field" );
		
		$article->ext_someNewProperty = -8;
		$this->assertEquals( -8, $article->ext_someNewProperty,
			"Article get/set magic on update to new field" );
	}
}
