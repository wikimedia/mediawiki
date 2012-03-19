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
            #XXX: or define $content->getDefaultWikiText()?
            return $content->getNativeData();
        }

        #XXX: this must not be used for editing, otherwise we may loose data:
        #XXX:      e.g. if this returns the "main" text from a multipart page, all attachments would be lost

        return null;
    }

    public static function makeContent( $text, Title $title, $modelName = null, $format = null ) {
        if ( !$modelName ) {
            $modelName = $title->getContentModelName();
        }

        $handler = ContentHandler::getForModelName( $modelName );
        return $handler->unserialize( $text, $format );
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
        return ContentHandler::getForModelName( $modelName );
    }

    public static function getForContent( Content $content ) {
        $modelName = $content->getModelName();
        return ContentHandler::getForModelName( $modelName );
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

    public abstract function serialize( Content $content, $format = null );

    public abstract function unserialize( $blob, $format = null );

    public abstract function emptyContent();

    # public abstract function doPreSaveTransform( $title, $obj ); #TODO...

    /**
     * Return an Article object suitable for viewing the given object
     *
     * NOTE: does *not* do special handling for Image and Category pages!
     *       Use Article::newFromTitle() for that!
     *
     * @param type $title
     * @return \Article
     * @todo Article is being refactored into an action class, keep track of that
     */
    public function createArticle( Title $title ) {
        #XXX: assert that $title->getContentModelName() == $this->getModelname()?
        $article = new Article($title);
        return $article;
    }

    /**
     * Return an EditPage object suitable for editing the given object
     * 
     * @param type $article
     * @return \EditPage 
     */
    public function createEditPage( Article $article ) {
        #XXX: assert that $article->getContentObject()->getModelName() == $this->getModelname()?
        $editPage = new EditPage( $article );
        return $editPage;
    }

    /**
     * Return an ExternalEdit object suitable for editing the given object
     *
     * @param type $article
     * @return \ExternalEdit
     */
    public function createExternalEdit( IContextSource $context ) {
        #XXX: assert that $article->getContentObject()->getModelName() == $this->getModelname()?
        $externalEdit = new ExternalEdit( $context );
        return $externalEdit;
    }

    /**
    public function updatePage( $title, $obj ) {
    }
    **/
    
    public function getDiffEngine( Article $article ) {
        $de = new DifferenceEngine( $article->getContext() );
        return $de;
    }

    /**
     * attempts to merge differences between three versions.
     * Returns a new Content object for a clean merge and false for failure or a conflict.
     *
     * This default implementation always returns false.
     *
     * @param $oldContent String
     * @param $myContent String
     * @param $yourContent String
     * @return Content|Bool
     */
    public function merge3( Content $oldContent, Content $myContent, Content $yourContent ) {
        return false;
    }

    #TODO: cover patch/undo just like merge3.

    #TODO: how to handle extra message for JS/CSS previews??
    #TODO: Article::showCssOrJsPage ---> specialized classes!

    #XXX: ImagePage and CategoryPage... wrappers that use ContentHandler? or ContentHandler creates wrappers?
}


abstract class TextContentHandler extends ContentHandler {

    public function __construct( $modelName, $formats ) {
        parent::__construct( $modelName, $formats );
    }

    public function serialize( Content $content, $format = null ) {
        #FIXME: assert format
        return $content->getNativeData();
    }

    /**
     * attempts to merge differences between three versions.
     * Returns a new Content object for a clean merge and false for failure or a conflict.
     *
     * This text-based implementation uses wfMerge().
     *
     * @param $oldContent String
     * @param $myContent String
     * @param $yourContent String
     * @return Content|Bool
     */
    public function merge3( Content $oldContent, Content $myContent, Content $yourContent ) {
        $format = $this->getDefaultFormat();

        $old = $this->serialize( $oldContent, $format );
        $mine = $this->serialize( $myContent, $format );
        $yours = $this->serialize( $yourContent, $format );

        $ok = wfMerge( $old, $mine, $yours, $result );

        if ( !$ok ) return false;
        if ( !$result ) return $this->emptyContent();

        $mergedContent = $this->unserialize( $result, $format );
        return $mergedContent;
    }


}
class WikitextContentHandler extends TextContentHandler {

    public function __construct( $modelName = CONTENT_MODEL_WIKITEXT ) {
        parent::__construct( $modelName, array( 'application/x-wikitext' ) ); #FIXME: mime
    }

    public function unserialize( $text, $format = null ) {
        #FIXME: assert format
        return new WikitextContent($text);
    }

    public function emptyContent() {
        return new WikitextContent("");
    }

}

class JavaScriptContentHandler extends TextContentHandler {

    public function __construct( $modelName = CONTENT_MODEL_WIKITEXT ) {
        parent::__construct( $modelName, array( 'text/javascript' ) );
    }

    public function unserialize( $text, $format = null ) {
        return new JavaScriptContent($text);
    }

    public function emptyContent() {
        return new JavaScriptContent("");
    }
}

class CssContentHandler extends TextContentHandler {

    public function __construct( $modelName = CONTENT_MODEL_WIKITEXT ) {
        parent::__construct( $modelName, array( 'text/css' ) );
    }

    public function unserialize( $text, $format = null ) {
        return new CssContent($text);
    }

    public function emptyContent() {
        return new CssContent("");
    }

}
