<?php

class MWContentSerializationException extends MWException {

}


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

    public static function getContentText( Content $content = null ) {
        global $wgContentHandlerTextFallback;

        if ( !$content ) return '';

        if ( $content instanceof TextContent ) {
            return $content->getNativeData();
        }

        if ( $wgContentHandlerTextFallback == 'fail' ) throw new MWException( "Attempt to get text from Content with model " . $content->getModelName() );
        if ( $wgContentHandlerTextFallback == 'serialize' ) return $content->serialize();

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
        $isCssOrJsPage = ( NS_MEDIAWIKI == $ns && preg_match( '!\.(css|js)$!u', $title->getText(), $m ) );
        if ( $isCssOrJsPage ) $ext = $m[1];

        # hook can force js/css
        wfRunHooks( 'TitleIsCssOrJsPage', array( $title, &$isCssOrJsPage ) );

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

    /**
     * @static
     * @param $modelName String the name of the content model for which to get a handler. Use CONTENT_MODEL_XXX constants.
     * @return ContentHandler
     * @throws MWException
     */
    public static function getForModelName( $modelName ) {
        global $wgContentHandlers;

        if ( empty( $wgContentHandlers[$modelName] ) ) {
            $handler = null;
            wfRunHooks( "ContentHandlerForModelName", array( $modelName, &$handler ) );  #FIXME: document new hook

            if ( $handler ) { # NOTE: may be a string or an object, either is fine!
                $wgContentHandlers[$modelName] = $handler;
            } else {
                throw new MWException( "No handler for model $modelName registered in \$wgContentHandlers" );
            }
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

    protected function checkModelName( $modelName ) {
        if ( $modelName !== $this->mModelName ) {
            throw new MWException( "Bad content model: expected " . $this->mModelName . " but got found " . $modelName );
        }
    }

    public function getSupportedFormats() {
        # for wikitext: "text/x-mediawiki-1", "text/x-mediawiki-2", etc
        # for wikidata: "application/json", "application/x-php", etc
        return $this->mSupportedFormats;
    }

    public function getDefaultFormat() {
        return $this->mSupportedFormats[0];
    }

    public function isSupportedFormat( $format ) {
        if ( !$format ) return true; # this means "use the default"

        return in_array( $format, $this->mSupportedFormats );
    }

    protected function checkFormat( $format ) {
        if ( !$this->isSupportedFormat( $format ) ) {
            throw new MWException( "Format $format is not supported for content model " . $this->getModelName() );
        }
    }

    /**
     * @abstract
     * @param Content $content
     * @param null $format
     * @return String
     */
    public abstract function serialize( Content $content, $format = null );

    /**
     * @abstract
     * @param $blob String
     * @param null $format
     * @return Content
     */
    public abstract function unserialize( $blob, $format = null );

    public abstract function emptyContent();

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
        $this->checkModelName( $title->getContentModelName() );

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
        $this->checkModelName( $article->getContentObject()->getModelName() );

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
        $this->checkModelName( $context->getTitle()->getModelName() );

        $externalEdit = new ExternalEdit( $context );
        return $externalEdit;
    }

    /**
     * Factory
     * @param $context IContextSource context to use, anything else will be ignored
     * @param $old Integer old ID we want to show and diff with.
     * @param $new String either 'prev' or 'next'.
     * @param $rcid Integer ??? FIXME (default 0)
     * @param $refreshCache boolean If set, refreshes the diff cache
     * @param $unhide boolean If set, allow viewing deleted revs
     */
    public function getDifferenceEngine( IContextSource $context, $old = 0, $new = 0, $rcid = 0, #FIMXE: use everywhere!
                                         $refreshCache = false, $unhide = false ) {

        $this->checkModelName( $context->getTitle()->getModelName() );

        $de = new DifferenceEngine( $context, $old, $new, $rcid, $refreshCache, $unhide );

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

    /**
     * Return an applicable autosummary if one exists for the given edit.
     *
     * @param $oldContent Content: the previous text of the page.
     * @param $newContent Content: The submitted text of the page.
     * @param $flags Int bitmask: a bitmask of flags submitted for the edit.
     *
     * @return string An appropriate autosummary, or an empty string.
     */
    public function getAutosummary( Content $oldContent, Content $newContent, $flags ) {
        global $wgContLang;

        # Decide what kind of autosummary is needed.

        # Redirect autosummaries
        $ot = $oldContent->getRedirectTarget();
        $rt = $newContent->getRedirectTarget();

        if ( is_object( $rt ) && ( !is_object( $ot ) || !$rt->equals( $ot ) || $ot->getFragment() != $rt->getFragment() ) ) {

            $truncatedtext = $newContent->getTextForSummary(
                250
                    - strlen( wfMsgForContent( 'autoredircomment' ) )
                    - strlen( $rt->getFullText() ) );

            return wfMsgForContent( 'autoredircomment', $rt->getFullText(), $truncatedtext );
        }

        # New page autosummaries
        if ( $flags & EDIT_NEW && $newContent->getSize() > 0 ) {
            # If they're making a new article, give its text, truncated, in the summary.

            $truncatedtext = $newContent->getTextForSummary(
                200 - strlen( wfMsgForContent( 'autosumm-new' ) ) );

            return wfMsgForContent( 'autosumm-new', $truncatedtext );
        }

        # Blanking autosummaries
        if ( $oldContent->getSize() > 0 && $newContent->getSize() == 0 ) {
            return wfMsgForContent( 'autosumm-blank' );
        } elseif ( $oldContent->getSize() > 10 * $newContent->getSize() && $newContent->getSize() < 500 ) {
            # Removing more than 90% of the article

            $truncatedtext = $newContent->getTextForSummary(
                200 - strlen( wfMsgForContent( 'autosumm-replace' ) ) );

            return wfMsgForContent( 'autosumm-replace', $truncatedtext );
        }

        # If we reach this point, there's no applicable autosummary for our case, so our
        # autosummary is empty.
        return '';
    }

    /**
     * Auto-generates a deletion reason
     *
     * @param $title Title: the page's title
     * @param &$hasHistory Boolean: whether the page has a history
     * @return mixed String containing deletion reason or empty string, or boolean false
     *    if no revision occurred
     */
    public function getAutoDeleteReason( Title $title, &$hasHistory ) {
        global $wgContLang;

        $dbw = wfGetDB( DB_MASTER );

        // Get the last revision
        $rev = Revision::newFromTitle( $title );

        if ( is_null( $rev ) ) {
            return false;
        }

        // Get the article's contents
        $content = $rev->getContent();
        $blank = false;

        // If the page is blank, use the text from the previous revision,
        // which can only be blank if there's a move/import/protect dummy revision involved
        if ( $content->getSize() == 0 ) {
            $prev = $rev->getPrevious();

            if ( $prev )	{
                $content = $rev->getContent();
                $blank = true;
            }
        }

        // Find out if there was only one contributor
        // Only scan the last 20 revisions
        $res = $dbw->select( 'revision', 'rev_user_text',
            array( 'rev_page' => $title->getArticleID(), $dbw->bitAnd( 'rev_deleted', Revision::DELETED_USER ) . ' = 0' ),
            __METHOD__,
            array( 'LIMIT' => 20 )
        );

        if ( $res === false ) {
            // This page has no revisions, which is very weird
            return false;
        }

        $hasHistory = ( $res->numRows() > 1 );
        $row = $dbw->fetchObject( $res );

        if ( $row ) { // $row is false if the only contributor is hidden
            $onlyAuthor = $row->rev_user_text;
            // Try to find a second contributor
            foreach ( $res as $row ) {
                if ( $row->rev_user_text != $onlyAuthor ) { // Bug 22999
                    $onlyAuthor = false;
                    break;
                }
            }
        } else {
            $onlyAuthor = false;
        }

        // Generate the summary with a '$1' placeholder
        if ( $blank ) {
            // The current revision is blank and the one before is also
            // blank. It's just not our lucky day
            $reason = wfMsgForContent( 'exbeforeblank', '$1' );
        } else {
            if ( $onlyAuthor ) {
                $reason = wfMsgForContent( 'excontentauthor', '$1', $onlyAuthor );
            } else {
                $reason = wfMsgForContent( 'excontent', '$1' );
            }
        }

        if ( $reason == '-' ) {
            // Allow these UI messages to be blanked out cleanly
            return '';
        }

        // Max content length = max comment length - length of the comment (excl. $1)
        $text = $content->getTextForSummary( 255 - ( strlen( $reason ) - 2 ) );

        // Now replace the '$1' placeholder
        $reason = str_replace( '$1', $text, $reason );

        return $reason;
    }

    /**
     * Get the Content object that needs to be saved in order to undo all revisions
     * between $undo and $undoafter. Revisions must belong to the same page,
     * must exist and must not be deleted
     * @param $undo Revision
     * @param $undoafter null|Revision Must be an earlier revision than $undo
     * @return mixed string on success, false on failure
     */
    public function getUndoContent( Revision $current, Revision $undo, Revision $undoafter = null ) {
        $cur_content = $current->getContent();

        if ( empty( $cur_content ) ) {
            return false; // no page
        }

        $undo_content = $undo->getContent();
        $undoafter_content = $undoafter->getContent();

        if ( $cur_content->equals( $undo_content ) ) {
            # No use doing a merge if it's just a straight revert.
            return $undoafter_content;
        }

        $undone_content = $this->merge3( $undo_content, $undoafter_content, $cur_content );

        return $undone_content;
    }
}


abstract class TextContentHandler extends ContentHandler {

    public function __construct( $modelName, $formats ) {
        parent::__construct( $modelName, $formats );
    }

    public function serialize( Content $content, $format = null ) {
        $this->checkFormat( $format );
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
        $this->checkModelName( $oldContent->getModelName() );
        #TODO: check that all Content objects have the same content model! #XXX: what to do if they don't?

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
        $this->checkFormat( $format );

        return new WikitextContent( $text );
    }

    public function emptyContent() {
        return new WikitextContent( "" );
    }


}

#TODO: make ScriptContentHandler base class with plugin interface for syntax highlighting!

class JavaScriptContentHandler extends TextContentHandler {

    public function __construct( $modelName = CONTENT_MODEL_WIKITEXT ) {
        parent::__construct( $modelName, array( 'text/javascript' ) ); #XXX: or use $wgJsMimeType? this is for internal storage, not HTTP...
    }

    public function unserialize( $text, $format = null ) {
        return new JavaScriptContent( $text );
    }

    public function emptyContent() {
        return new JavaScriptContent( "" );
    }
}

class CssContentHandler extends TextContentHandler {

    public function __construct( $modelName = CONTENT_MODEL_WIKITEXT ) {
        parent::__construct( $modelName, array( 'text/css' ) );
    }

    public function unserialize( $text, $format = null ) {
        return new CssContent( $text );
    }

    public function emptyContent() {
        return new CssContent( "" );
    }

}
