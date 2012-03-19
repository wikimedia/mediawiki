<?php

/**
 * A content object represents page content, e.g. the text to show on a page.
 * Content objects have no knowledge about how they relate to Wiki pages.
 * Content objects are imutable.
 *
 */
abstract class Content {
    
    public function __construct( $modelName = null ) { #FIXME: really need revId? annoying! #FIXME: really $title? or just when parsing, every time?
        $this->mModelName = $modelName;
    }

    public function getModelName() {
        return $this->mModelName;
    }

    public abstract function getSearchText( );

    public abstract function getWikitextForTransclusion( );

    /**
     * Returns native represenation of the data. Interpretation depends on the data model used,
     * as given by getDataModel().
     *
     */
    public abstract function getRawData( );

    public abstract function getParserOutput( Title $title = null, $revId = null, ParserOptions $options = NULL );

    public function getRedirectChain() {
        return null;
    }

    /**
     * Returns the section with the given id.
     *
     * The default implementation returns null.
     *
     * @param String $sectionId the section's id
     * @return Content|Boolean|null the section, or false if no such section exist, or null if sections are not supported
     */
    public function getSection( $sectionId ) {
        return null;
    }

    /**
     * Replaces a section of the content.
     *
     * @param $section empty/null/false or a section number (0, 1, 2, T1, T2...), or "new"
     * @param $with Content: new content of the section
     * @param $sectionTitle String: new section's subject, only if $section is 'new'
     * @return string Complete article text, or null if error
     */
    public function replaceSection( $section, Content $with, $sectionTitle = ''  ) {
        return $this;
    }

    #TODO: implement specialized ParserOutput for Wikidata model
    #TODO: provide addToParserOutput fule Multipart... somehow.

    # TODO: EditPage::mergeChanges( Content $a, Content $b )
    # TODO: Wikipage::isCountable(Content $a)
    # TODO: Title::newFromRedirectRecurse( $this->getRawText() );

    # TODO: isCacheable( )
    # TODO: getSize( )

    # TODO: WikiPage::getUndoText( Revision $undo, Revision $undoafter = null )
    # TODO: WikiPage::getAutosummary( $oldtext, $text, $flags )

    # TODO: EditPage::getPreloadedText( $preload ) // $wgParser->getPreloadText


    # TODO: tie into API to provide contentModel for Revisions
    # TODO: tie into API to provide serialized version and contentFormat for Revisions
    # TODO: tie into API edit interface

}

/**
 * Content object implementation for representing flat text. The
 */
abstract class TextContent extends Content {
    public function __construct( $text, $modelName = null ) {
        parent::__construct($modelName);

        $this->mText = $text;
    }

    /**
     * Returns the text represented by this Content object, as a string.
     *
     * @return String the raw text
     */
    public function getRawData( ) {
        $text = $this->mText;
        return $text;
    }

    /**
     * Returns the text represented by this Content object, as a string.
     *
     * @return String the raw text
     */
    public function getSearchText( ) { #FIXME: use!
        return $this->getRawData();
    }

    /**
     * Returns the text represented by this Content object, as a string.
     *
     * @return String the raw text
     */
    public function getWikitextForTransclusion( ) { #FIXME: use!
        return $this->getRawData();
    }

    /**
     * Returns a generic ParserOutput object, wrapping the HTML returned by getHtml().
     *
     * @return ParserOutput representing the HTML form of the text
     */
    public function getParserOutput( Title $title = null, $revId = null, ParserOptions $options = null ) {
        # generic implementation, relying on $this->getHtml()

        $html = $this->getHtml( $options );
        $po = new ParserOutput( $html );

        if ( $this->mTitle ) $po->setTitleText( $this->mTitle->getText() );

        #TODO: cache settings, etc?

        return $po;
    }

    protected abstract function getHtml( );

}

class WikitextContent extends TextContent {
    public function __construct( $text ) {
        parent::__construct($text, CONTENT_MODEL_WIKITEXT);

        $this->mDefaultParserOptions = null; #TODO: use per-class static member?!
    }

    protected function getHtml( ) {
        throw new MWException( "getHtml() not implemented for wikitext. Use getParserOutput()->getText()." );
    }

    public function getDefaultParserOptions() {
        global $wgUser, $wgContLang;

        if ( !$this->mDefaultParserOptions ) { #TODO: use per-class static member?!
            $this->mDefaultParserOptions = ParserOptions::newFromUserAndLang( $wgUser, $wgContLang );
        }

        return $this->mDefaultParserOptions;
    }

    /**
     * Returns a ParserOutput object reesulting from parsing the content's text using $wgParser
     *
     * @return ParserOutput representing the HTML form of the text
     */
    public function getParserOutput( Title $title = null, $revId = null, ParserOptions $options = null ) {
        global $wgParser;

        if ( !$options ) {
            $options = $this->getDefaultParserOptions();
        }

        $po = $wgParser->parse( $this->mText, $this->getTitle(), $options, true, true, $this->mRevId );

        return $po;
    }

    /**
     * Returns the section with the given id.
     *
     * @param String $sectionId the section's id
     * @return Content|false|null the section, or false if no such section exist, or null if sections are not supported
     */
    public function getSection( $section ) {
        global $wgParser;

        $text = $this->getRawData();
        $sect = $wgParser->getSection( $text, $section, false );

        return  new WikitextContent( $sect );
    }

    /**
     * Replaces a section in the wikitext
     *
     * @param $section empty/null/false or a section number (0, 1, 2, T1, T2...), or "new"
     * @param $with Content: new content of the section
     * @param $sectionTitle String: new section's subject, only if $section is 'new'
     * @return string Complete article text, or null if error
     */
    public function replaceSection( $section, Content $with, $sectionTitle = '' ) {
        global $wgParser;

        wfProfileIn( __METHOD__ );

        $myModelName = $this->getModelName();
        $sectionModelName = $with->getModelName();

        if ( $sectionModelName != $myModelName  ) {
            throw new MWException( "Incompatible content model for section: document uses $myModelName, section uses $sectionModelName." );
        }

        $oldtext = $this->getRawData();
        $text = $with->getRawData();

        if ( $section == 'new' ) {
            # Inserting a new section
            $subject = $sectionTitle ? wfMsgForContent( 'newsectionheaderdefaultlevel', $sectionTitle ) . "\n\n" : '';
            if ( wfRunHooks( 'PlaceNewSection', array( $this, $oldtext, $subject, &$text ) ) ) {
                $text = strlen( trim( $oldtext ) ) > 0
                    ? "{$oldtext}\n\n{$subject}{$text}"
                    : "{$subject}{$text}";
            }
        } else {
            # Replacing an existing section; roll out the big guns
            global $wgParser;

            $text = $wgParser->replaceSection( $oldtext, $section, $text );
        }

        $newContent = new WikitextContent( $text );

        wfProfileOut( __METHOD__ );
        return $newContent;
    }

    public function getRedirectChain() {
        $text = $this->getRawData();
        return Title::newFromRedirectArray( $text );
    }

}

class MessageContent extends TextContent {
    public function __construct( $msg_key, $params = null, $options = null ) {
        parent::__construct(null, CONTENT_MODEL_WIKITEXT);

        $this->mMessageKey = $msg_key;

        $this->mParameters = $params;

        if ( !$options ) $options = array();
        $this->mOptions = $options;

        $this->mHtmlOptions = null;
    }

    /**
     * Returns the message as rendered HTML, using the options supplied to the constructor plus "parse".
     */
    protected function getHtml(  ) {
        $opt = array_merge( $this->mOptions, array('parse') );

        return wfMsgExt( $this->mMessageKey, $this->mParameters, $opt );
    }


    /**
     * Returns the message as raw text, using the options supplied to the constructor minus "parse" and "parseinline".
     */
    public function getRawData( ) {
        $opt = array_diff( $this->mOptions, array('parse', 'parseinline') );

        return wfMsgExt( $this->mMessageKey, $this->mParameters, $opt );
    }

}


class JavaScriptContent extends TextContent {
    public function __construct( $text ) {
        parent::__construct($text, CONTENT_MODEL_JAVASCRIPT);
    }

    protected function getHtml( ) {
        $html = "";
        $html .= "<pre class=\"mw-code mw-js\" dir=\"ltr\">\n";
        $html .= htmlspecialchars( $this->getRawData() );
        $html .= "\n</pre>\n";

        return $html;
    }

}

class CssContent extends TextContent {
    public function __construct( $text ) {
        parent::__construct($text, CONTENT_MODEL_CSS);
    }

    protected function getHtml( ) {
        $html = "";
        $html .= "<pre class=\"mw-code mw-css\" dir=\"ltr\">\n";
        $html .= htmlspecialchars( $this->getRawData() );
        $html .= "\n</pre>\n";

        return $html;
    }
}

#FUTURE: special type for redirects?!
#FUTURE: MultipartMultipart < WikipageContent (Main + Links + X)
#FUTURE: LinksContent < LanguageLinksContent, CategoriesContent
#EXAMPLE: CoordinatesContent
#EXAMPLE: WikidataContent
