<?php

/**
 * A content object represents page content, e.g. the text to show on a page.
 *
 */
abstract class Content {
    
    public function __construct( Title $title, $revId, $modelName ) { #FIXME: really need revId? annoying! #FIXME: really $title? or just when parsing, every time?
        $this->mModelName = $modelName;
        $this->mTitle = $title;
        $this->mRevId = $revId;
    }

    public function getModelName() {
        return $this->mModelName;
    }

    public function getTitle() {
        return $this->mTitle;
    }

    public function getRevId() {
        return $this->mRevId;
    }

    public abstract function getSearchText( $obj );

    public abstract function getWikitextForTransclusion( $obj );

    public abstract function getParserOutput( ParserOptions $options = NULL );

    public abstract function getRawData( );

    public function getHtml( ParserOptions $options ) {
        $po = $this->getParserOutput( $options );
        return $po->getText();
    }

    public function getIndexUpdateJobs( ParserOptions $options , $recursive = true ) {
        $po = $this->getParserOutput( $options );
        $update = new LinksUpdate( $this->mTitle, $po, $recursive );
        return $update;
    }

    public function getRedirectChain() {
        return null;
    }

    public function getSection( $section ) { #FIXME: should this return text? or a Content object? or what??
        return null;
    }

    #XXX: is the native model for wikitext a string or the parser output? parse early or parse late?
}

class TextContent extends Content {
    public function __construct( $text, Title $title, $revId, $modelName ) {
        parent::__construct($title, $revId, $modelName);

        $this->mText = $text;
    }

    public function getSearchText( $obj ) {
        return $this->getRawData();
    }

    public function getWikitextForTransclusion( $obj ) {
        return $this->getRawData();
    }


    public function getParserOutput( ParserOptions $options = null ) {
        # generic implementation, relying on $this->getHtml()

        $html = $this->getHtml( $options );
        $po = new ParserOutput( $html );

        if ( $this->mTitle ) $po->setTitleText( $this->mTitle->getText() );

        #TODO: cache settings, etc?

        return $po;
    }

    public function getHtml( ParserOptions $options ) {
        $html = "";
        $html .= "<pre class=\"mw-code\" dir=\"ltr\">\n";
        $html .= htmlspecialchars( $this->getRawData() );
        $html .= "\n</pre>\n";

        return $html;
    }


    public function getRawData( ) {
        $text = $this->mText;
        return $text;
    }

    public function getRedirectChain() {
        #XXX: really do this for all text, or just in WikitextContent
        $text = $this->getRawData();
        return Title::newFromRedirectArray( $text );
    }
}

class WikitextContent extends TextContent {
    public function __construct( $text, Title $title, $revId = null) {
        parent::__construct($text, $title, $revId, CONTENT_MODEL_WIKITEXT);

        $this->mDefaultParserOptions = null;
    }

    public function getDefaultParserOptions() {
        global $wgUser, $wgContLang;

        if ( !$this->mDefaultParserOptions ) {
            #TODO: use static member?!
            $this->mDefaultParserOptions = ParserOptions::newFromUserAndLang( $wgUser, $wgContLang );
        }

        return $this->mDefaultParserOptions;
    }

    public function getParserOutput( ParserOptions $options = null ) {
        global $wgParser;

        #TODO: quick local cache: if $options is NULL, use ->mParserOutput!
        #FIXME: need setParserOutput, so we can use stuff from the parser cache??
        #FIXME: ...or we somehow need to know the parser cache key??

        if ( !$options ) {
            $options = $this->getDefaultParserOptions();
        }

        $po = $wgParser->parse( $this->mText, $this->getTitle(), $options, true, true, $this->mRevId );

        return $po;
    }

    public function getSection( $section ) {
        global $wgParser;

        $text = $this->getRawData();
        return $wgParser->getSection( $text, $section, false );
    }

}

class MessageContent extends TextContent {
    public function __construct( $msg_key, $params = null, $options = null ) {
        parent::__construct(null, null, null, CONTENT_MODEL_WIKITEXT);

        $this->mMessageKey = $msg_key;

        $this->mParameters = $params;

        if ( !$options ) $options = array();
        $this->mOptions = $options;

        $this->mHtmlOptions = null;
    }


    public function getHtml( ParserOptions $options ) {
        $opt = array_merge( $this->mOptions, array('parse') );
        return wfMsgExt( $this->mMessageKey, $this->mParameters, $opt );
    }


    public function getRawData( ) {
        $opt = array_diff( $this->mOptions, array('parse', 'parseinline') );

        return wfMsgExt( $this->mMessageKey, $this->mParameters, $opt );
    }

}


class JavaScriptContent extends TextContent {
    public function __construct( $text, Title $title, $revId = null ) {
        parent::__construct($text, $title, $revId, CONTENT_MODEL_JAVASCRIPT);
    }

    public function getHtml( ParserOptions $options ) {
        $html = "";
        $html .= "<pre class=\"mw-code mw-js\" dir=\"ltr\">\n";
        $html .= htmlspecialchars( $this->getRawData() );
        $html .= "\n</pre>\n";

        return $html;
    }

}

class CssContent extends TextContent {
    public function __construct( $text, Title $title, $revId = null ) {
        parent::__construct($text, $title, $revId, CONTENT_MODEL_CSS);
    }

    public function getHtml( ParserOptions $options ) {
        $html = "";
        $html .= "<pre class=\"mw-code mw-css\" dir=\"ltr\">\n";
        $html .= htmlspecialchars( $this->getRawData() );
        $html .= "\n</pre>\n";

        return $html;
    }
}

#FIXME: special type for redirects?!
#FIXME: special type for message-based pseudo-content? with raw html?

#TODO: MultipartMultipart < WikipageContent (Main + Links + X)
#TODO: LinksContent < LanguageLinksContent, CategoriesContent
#EXAMPLE: CoordinatesContent
#EXAMPLE: WikidataContent
