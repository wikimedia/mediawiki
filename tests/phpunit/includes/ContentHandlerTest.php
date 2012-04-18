<?php

class ContentHandlerTest extends MediaWikiTestCase {

    public function dataGetDefaultModelFor() {
        return array(
            array( 'Foo', CONTENT_MODEL_WIKITEXT ),
            array( 'Foo.js', CONTENT_MODEL_WIKITEXT ),
            array( 'Foo/bar.js', CONTENT_MODEL_WIKITEXT ),
            array( 'User:Foo', CONTENT_MODEL_WIKITEXT ),
            array( 'User:Foo.js', CONTENT_MODEL_WIKITEXT ),
            array( 'User:Foo/bar.js', CONTENT_MODEL_JAVASCRIPT ),
            array( 'User:Foo/bar.css', CONTENT_MODEL_CSS ),
            array( 'User talk:Foo/bar.css', CONTENT_MODEL_WIKITEXT ),
            array( 'User:Foo/bar.js.xxx', CONTENT_MODEL_WIKITEXT ),
            array( 'User:Foo/bar.xxx', CONTENT_MODEL_WIKITEXT ),
            array( 'MediaWiki:Foo.js', CONTENT_MODEL_JAVASCRIPT ),
            array( 'MediaWiki:Foo.css', CONTENT_MODEL_CSS ),
            array( 'MediaWiki:Foo.JS', CONTENT_MODEL_WIKITEXT ),
            array( 'MediaWiki:Foo.CSS', CONTENT_MODEL_WIKITEXT ),
            array( 'MediaWiki:Foo.css.xxx', CONTENT_MODEL_WIKITEXT ),
        );
    }

    /**
     * @dataProvider dataGetDefaultModelFor
     */
    public function testGetDefaultModelFor( $title, $expectedModelName ) {
        $title = Title::newFromText( $title );
        $this->assertEquals( $expectedModelName, ContentHandler::getDefaultModelFor( $title ) );
    }

}
