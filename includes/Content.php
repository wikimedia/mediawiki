<?php

/**
 * A content object represents page content, e.g. the text to show on a page.
 *
 */
abstract class Content {
    
    public function __construct( Title $title, $revId, $modelName ) {
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
        global $wgParser, $wgUser;

        $text = $this->mText;
        return $text;
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

        $po = $wgParser->parse( $this->mText, $this->getTitle(), $options );

        return $po;
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

#TODO: MultipartMultipart < WikipageContent (Main + Links + X)
#TODO: LinksContent < LanguageLinksContent, CategoriesContent
#EXAMPLE: CoordinatesContent
#EXAMPLE: WikidataContent
