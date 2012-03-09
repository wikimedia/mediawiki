<?php

/**
 * A content handler knows how do deal with a specific type of content on a wiki page.
 * Content is stored in the database in a serialized form (using a serialization format aka mime type)
 * and is be unserialized into it's native PHP represenation (the content model).
 * 
 * Some content types have a flat model, that is, their native represenation is the
 * same as their serialized form. Examples would be JavaScript and CSS code. As of now,
 * this also applies to wikitext (mediawiki's default content type), but wikitext
 * content may be represented by a DOM or AST structure in the future.
 *  
 */
abstract class ContentHandler {

    public static function getContentText( Content $content ) {
        if ( !$content ) return '';

        if ( $content instanceof TextContent ) {
            #XXX: or check by model name?
            #XXX: or define $content->allowRawData()?
            return $content->getRawData();
        }

        return null;
    }

    public static function makeContent( $text, Title $title, $format = null, $revId = null ) {
        $handler = ContentHandler::getForTitle( $title );

        #FIXME: pass revid?
        return $handler->unserialize( $text, $title, $format );
    }

    public static function getDefaultModelFor( Title $title ) {
        global $wgNamespaceContentModels;

        # NOTE: this method must not rely on $title->getContentModelName() directly or indirectly,
        #       because it is used to initialized the mContentModelName memebr.

        $ns = $title->getNamespace();

        $ext = false;
        $m = null;
        $model = null;

        if ( !empty( $wgNamespaceContentModels[ $ns ] ) ) {
            $model = $wgNamespaceContentModels[ $ns ];
        }

        # hook can determin default model
        if ( !wfRunHooks( 'DefaultModelFor', array( $title, &$model ) ) ) { #FIXME: document new hook!
            if ( $model ) return $model;
        }

        # Could this page contain custom CSS or JavaScript, based on the title?
        $isCssOrJsPage = ( NS_MEDIAWIKI == $ns && preg_match( "!\.(css|js)$!u", $title->getText(), $m ) );
        if ( $isCssOrJsPage ) $ext = $m[1];

        # hook can force js/css
        wfRunHooks( 'TitleIsCssOrJsPage', array( $title, &$isCssOrJsPage, &$ext ) ); #FIXME: add $ext to hook interface spec

        # Is this a .css subpage of a user page?
        $isJsCssSubpage = ( NS_USER == $ns && !$isCssOrJsPage && preg_match( "/\\/.*\\.(js|css)$/", $title->getText(), $m ) );
        if ( $isJsCssSubpage ) $ext = $m[1];

        # is this wikitext, according to $wgNamespaceContentModels or the DefaultModelFor hook?
        $isWikitext = ( $model == CONTENT_MODEL_WIKITEXT || $model === null );
        $isWikitext = ( $isWikitext && !$isCssOrJsPage && !$isJsCssSubpage );

        # hook can override $isWikitext
        wfRunHooks( 'TitleIsWikitextPage', array( $title, &$isWikitext ) );

        if ( !$isWikitext ) {

            if ( $ext == 'js' )
                return CONTENT_MODEL_JAVASCRIPT;
            else if ( $ext == 'css' )
                return CONTENT_MODEL_CSS;

            if ( $model )
                return $model;
            else
                return CONTENT_MODEL_TEXT;
        }

        # we established that is must be wikitext
        return CONTENT_MODEL_WIKITEXT;
    }

    public static function getForTitle( Title $title ) {
        $modelName = $title->getContentModelName();
        return ContenteHandler::getForModelName( $modelName );
    }

    public static function getForContent( Content $content ) {
        $modelName = $content->getModelName();
        return ContenteHandler::getForModelName( $modelName );
    }

    public static function getForModelName( $modelName ) {
        global $wgContentHandlers;

        if ( empty( $wgContentHandlers[$modelName] ) ) {
            #FIXME: hook here!
            throw new MWException( "No handler for model $modelName registered in \$wgContentHandlers" );
        }

        if ( is_string( $wgContentHandlers[$modelName] ) ) {
            $class = $wgContentHandlers[$modelName];
            $wgContentHandlers[$modelName] = new $class( $modelName );
        }

        return $wgContentHandlers[$modelName];
    }

    # ----------------------------------------------------------------------------------------------------------
    public function __construct( $modelName, $formats ) {
        $this->mModelName = $modelName;
        $this->mSupportedFormats = $formats;
    }

    public function getModelName() {
        # for wikitext: wikitext; in the future: wikiast, wikidom?
        # for wikidata: wikidata
        return $this->mModelName;
    }


    public function getSupportedFormats() {
        # for wikitext: "text/x-mediawiki-1", "text/x-mediawiki-2", etc
        # for wikidata: "application/json", "application/x-php", etc
        return $this->mSupportedFormats;
    }

    public function getDefaultFormat() {
        return $this->mSupportedFormats[0];
    }

    public abstract function serialize( Content $content, Title $title, $format = null );

    public abstract function unserialize( $blob, Title $title, $format = null ); #FIXME: ...and revId?

    # public abstract function doPreSaveTransform( $title, $obj ); #TODO...

    /**
     * Return an Article object suitable for viewing the given object
     * 
     * @param type $title
     * @param type $obj
     * @return \Article 
     * @todo Article is being refactored into an action class, keep track of that
     */
    public function createArticle( Title $title, $obj ) { #TODO: use this!
        $article = new Article($title);
        return $article;
    }

    /**
     * Return an EditPage object suitable for editing the given object
     * 
     * @param type $title
     * @param type $obj
     * @param type $article
     * @return \EditPage 
     */
    public function createEditPage( Title $title, $obj, Article $article ) { #TODO: use this!
        $editPage = new EditPage($article);
        return $editPage;
    }

    /**
    public function updatePage( $title, $obj ) {
    }
    **/
    
    public function getDiffEngine( Article $article ) {
        $de = new DifferenceEngine( $article->getContext() );
        return $de;
    }

    public function getIndexUpdateJobs( Title $title, ParserOutput $parserOutput, $recursive = true ) {
            # for wikitext, create a LinksUpdate object
            # for wikidata: serialize arrays to json
        $update = new LinksUpdate( $title, $parserOutput, $recursive );
        return $update;
    }

    #XXX: is the native model for wikitext a string or the parser output? parse early or parse late?
}


abstract class TextContentHandler extends ContentHandler {

    public function __construct( $modelName, $formats ) {
        parent::__construct( $modelName, $formats );
    }

    public function serialize( Content $content, Title $title, $format = null ) {
        return $content->getRawData();
    }

}
class WikitextContentHandler extends TextContentHandler {

    public function __construct( $modelName = CONTENT_MODEL_WIKITEXT ) {
        parent::__construct( $modelName, array( 'application/x-wikitext' ) ); #FIXME: mime
    }

    public function unserialize( $text, Title $title, $format = null ) {
        return new WikitextContent($text, $title);
    }

}

class JavaScriptContentHandler extends TextContentHandler {

    public function __construct( $modelName = CONTENT_MODEL_WIKITEXT ) {
        parent::__construct( $modelName, array( 'text/javascript' ) );
    }

    public function unserialize( $text, Title $title, $format = null ) {
        return new JavaScriptContent($text, $title);
    }

}

class CssContentHandler extends TextContentHandler {

    public function __construct( $modelName = CONTENT_MODEL_WIKITEXT ) {
        parent::__construct( $modelName, array( 'text/css' ) );
    }

    public function unserialize( $text, Title $title, $format = null ) {
        return new CssContent($text, $title);
    }

}
