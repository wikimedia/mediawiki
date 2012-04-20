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

    public function testGetContentText_Null( ) {
        global $wgContentHandlerTextFallback;

        $content = null;

        $wgContentHandlerTextFallback = 'fail';
        $text = ContentHandler::getContentText( $content );
        $this->assertEquals( '', $text );

        $wgContentHandlerTextFallback = 'serialize';
        $text = ContentHandler::getContentText( $content );
        $this->assertEquals( '', $text );

        $wgContentHandlerTextFallback = 'ignore';
        $text = ContentHandler::getContentText( $content );
        $this->assertEquals( '', $text );
    }

    public function testGetContentText_TextContent( ) {
        global $wgContentHandlerTextFallback;

        $content = new WikitextContent( "hello world" );

        $wgContentHandlerTextFallback = 'fail';
        $text = ContentHandler::getContentText( $content );
        $this->assertEquals( $content->getNativeData(), $text );

        $wgContentHandlerTextFallback = 'serialize';
        $text = ContentHandler::getContentText( $content );
        $this->assertEquals( $content->serialize(), $text );

        $wgContentHandlerTextFallback = 'ignore';
        $text = ContentHandler::getContentText( $content );
        $this->assertEquals( $content->getNativeData(), $text );
    }

    public function testGetContentText_NonTextContent( ) {
        global $wgContentHandlerTextFallback;

        $content = new DummyContentForTesting( "hello world" );

        $wgContentHandlerTextFallback = 'fail';

        try {
            $text = ContentHandler::getContentText( $content );

            $this->fail( "ContentHandler::getContentText should have thrown an exception for non-text Content object" );
        } catch (MWException $ex) {
            // as expected
        }

        $wgContentHandlerTextFallback = 'serialize';
        $text = ContentHandler::getContentText( $content );
        $this->assertEquals( $content->serialize(), $text );

        $wgContentHandlerTextFallback = 'ignore';
        $text = ContentHandler::getContentText( $content );
        $this->assertNull( $text );
    }

}

class DummyContentForTesting extends Content {

    public function __construct( $data ) {
        parent::__construct( "DUMMY" );

        $this->data = $data;
    }

    public function serialize() {
        return serialize( $this->data );
    }

    /**
     * @return String a string representing the content in a way useful for building a full text search index.
     *         If no useful representation exists, this method returns an empty string.
     */
    public function getTextForSearchIndex()
    {
        return '';
    }

    /**
     * @return String the wikitext to include when another page includes this  content, or false if the content is not
     *         includable in a wikitext page.
     */
    public function getWikitextForTransclusion()
    {
        return false;
    }

    /**
     * Returns a textual representation of the content suitable for use in edit summaries and log messages.
     *
     * @param int $maxlength maximum length of the summary text
     * @return String the summary text
     */
    public function getTextForSummary($maxlength = 250)
    {
        return '';
    }

    /**
     * Returns native represenation of the data. Interpretation depends on the data model used,
     * as given by getDataModel().
     *
     * @return mixed the native representation of the content. Could be a string, a nested array
     *         structure, an object, a binary blob... anything, really.
     */
    public function getNativeData()
    {
        return $this->data;
    }

    /**
     * returns the content's nominal size in bogo-bytes.
     *
     * @return int
     */
    public function getSize()
    {
        return 23;
    }

    /**
     * Return a copy of this Content object. The following must be true for the object returned
     * if $copy = $original->copy()
     *
     * * get_class($original) === get_class($copy)
     * * $original->getModelName() === $copy->getModelName()
     * * $original->equals( $copy )
     *
     * If and only if the Content object is imutable, the copy() method can and should
     * return $this. That is,  $copy === $original may be true, but only for imutable content
     * objects.
     *
     * @return Content. A copy of this object
     */
    public function copy()
    {
        return $this;
    }

    /**
     * Returns true if this content is countable as a "real" wiki page, provided
     * that it's also in a countable location (e.g. a current revision in the main namespace).
     *
     * @param $hasLinks Bool: if it is known whether this content contains links, provide this information here,
     *                        to avoid redundant parsing to find out.
     * @return boolean
     */
    public function isCountable($hasLinks = null)
    {
        return false;
    }

    /**
     * @param IContextSource $context
     * @param null $revId
     * @param null|ParserOptions $options
     * @param Boolean $generateHtml whether to generate Html (default: true). If false,
     *        the result of calling getText() on the ParserOutput object returned by
     *        this method is undefined.
     *
     * @return ParserOutput
     */
    public function getParserOutput(IContextSource $context, $revId = null, ParserOptions $options = NULL, $generateHtml = true)
    {
        return new ParserOutput( $this->data );
    }
}

