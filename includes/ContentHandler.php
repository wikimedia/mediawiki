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

    public abstract function serialize( $obj, $format = null );
            # for wikitext, do nothing (in the future: serialise ast/dom)
            # for wikidata: serialize arrays to json

    public abstract function unserialize( $blob, $format = null );
            # for wikitext, do nothing (in the future: parse into ast/dom)
            # for wikidata: serialize arrays to json


    public function getSearchText( $obj ) {
            # for wikitext, return wikitext
            # for wikidata, return pseudo-wikitext composed of property values (or some such)
        $text = $this->serialize( $obj ); 
        return $text; # return the default serialization.
    }

    public function getWikitextForTransclusion( $obj ) {
        # for wikitext, return text
        # for wikidata, return false, or some generated wikitext
        $text = $this->serialize( $obj ); 
        return '<pre>' . $text . '</pre>'; # return a pre-formatted block containing the default serialization.
    }

    public abstract function render( $obj, Title $title, ParserOptions $options, $revid = null );
            # returns a ParserOutput instance!
            # are parser options, generic?!

    public abstract function doPreSaveTransform( $title, $obj );

    # TODO: getPreloadText()
    # TODO: preprocess()

    /** 
     * Return an Article object suitable for viewing the given object
     * 
     * @param type $title
     * @param type $obj
     * @return \Article 
     * @todo Article is being refactored into an action class, keep track of that
     */
    public function createArticle( $title, $obj ) {
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
    public function createEditPage( $title, $obj, $article ) {
        $editPage = new EditPage($article);
        return $editPage;
    }

    /**
    public function updatePage( $title, $obj ) {
    }
    **/
    
    public function getDiffEngine( $article ) {
        $de = new DifferenceEngine( $article->getContext() );
        return $de;
    }

    public function getIndexUpdateJobs( $title, $parserOutput, $recursive = true ) {
            # for wikitext, create a LinksUpdate object
            # for wikidata: serialize arrays to json
        $update = new LinksUpdate( $title, $parserOutput, $recursive );
        return $update;
    }

    #XXX: is the native model for wikitext a string or the parser output? parse early or parse late?
}

abstract class WikitextContentHandler extends ContentHandler {
}

abstract class JavaScriptContentHandler extends WikitextHandler {
}

abstract class CssContentHandler extends WikitextHandler {
}
