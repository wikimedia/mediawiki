<?php

class MWContentSerializationException extends MWException {

}


/**
 * A content handler knows how do deal with a specific type of content on a wiki page.
 * Content is stored in the database in a serialized form (using a serialization format aka mime type)
 * and is be unserialized into it's native PHP represenation (the content model), which is wrappe in
 * an instance of the appropriate subclass of Content.
 *
 * ContentHandler instances are stateless singletons that serve, among other things, as a factory for
 * Content objects. Generally, there is one subclass of ContentHandler and one subclass of Content
 * for every type of content model.
 *
 * Some content types have a flat model, that is, their native represenation is the
 * same as their serialized form. Examples would be JavaScript and CSS code. As of now,
 * this also applies to wikitext (mediawiki's default content type), but wikitext
 * content may be represented by a DOM or AST structure in the future.
 */
abstract class ContentHandler {

    /**
     * Conveniance function for getting flat text from a Content object. This should only
     * be used in the context of backwards compatibility with code that is not yet able
     * to handle Content objects!
     *
     * If $content is null, this method returns the empty string.
     *
     * If $content is an instance of TextContent, this method returns the flat text as returned by $content->getNativeData().
     *
     * If $content is not a TextContent object, the bahaviour of this method depends on the global $wgContentHandlerTextFallback:
     * * If $wgContentHandlerTextFallback is 'fail' and $content is not a TextContent object, an MWException is thrown.
     * * If $wgContentHandlerTextFallback is 'serialize' and $content is not a TextContent object, $content->serialize()
     * is called to get a string form of the content.
     * * If $wgContentHandlerTextFallback is 'ignore' and $content is not a TextContent object, this method returns null.
     * * otherwise, the behaviour is undefined.
     *
     * @static
     * @param Content|null $content
     * @return null|string the textual form of $content, if available
     * @throws MWException if $content is not an instance of TextContent and $wgContentHandlerTextFallback was set to 'fail'.
     */
    public static function getContentText( Content $content = null ) {
        global $wgContentHandlerTextFallback;

        if ( is_null( $content ) ) {
			return '';
		}

        if ( $content instanceof TextContent ) {
            return $content->getNativeData();
        }

        if ( $wgContentHandlerTextFallback == 'fail' ) {
			throw new MWException( "Attempt to get text from Content with model " . $content->getModelName() );
		}

        if ( $wgContentHandlerTextFallback == 'serialize' ) {
			return $content->serialize();
		}

        return null;
    }

    /**
     * Conveniance function for creating a Content object from a given textual representation.
     *
     * $text will be deserialized into a Content object of the model specified by $modelName (or,
     * if that is not given, $title->getContentModelName()) using the given format.
     *
     * @static
     * @param string $text the textual represenation, will be unserialized to create the Content object
     * @param Title $title the title of the page this text belongs to, required as a context for deserialization
     * @param null|String $modelName the model to deserialize to. If not provided, $title->getContentModelName() is used.
     * @param null|String $format the format to use for deserialization. If not given, the model's default format is used.
     *
     * @return Content a Content object representing $text
     * @throw MWException if $model or $format is not supported or if $text can not be unserialized using $format.
     */
    public static function makeContent( $text, Title $title, $modelName = null, $format = null ) {

        if ( is_null( $modelName ) ) {
            $modelName = $title->getContentModelName();
        }

        $handler = ContentHandler::getForModelName( $modelName );
        return $handler->unserializeContent( $text, $format );
    }

    /**
     * Returns the name of the default content model to be used for the page with the given title.
     *
     * Note: There should rarely be need to call this method directly.
     * To determine the actual content model for a given page, use Title::getContentModelName().
     *
     * Which model is to be used per default for the page is determined based on several factors:
     * * The global setting $wgNamespaceContentModels specifies a content model per namespace.
     * * The hook DefaultModelFor may be used to override the page's default model.
     * * Pages in NS_MEDIAWIKI and NS_USER default to the CSS or JavaScript model if they end in .js or .css, respectively.
     * * Pages in NS_MEDIAWIKI default to the wikitext model otherwise.
     * * The hook TitleIsCssOrJsPage may be used to force a page to use the CSS or JavaScript model if they end in .js or .css, respectively.
     * * The hook TitleIsWikitextPage may be used to force a page to use the wikitext model.
     *
     * If none of the above applies, the wikitext model is used.
     *
     * Note: this is used by, and may thus not use, Title::getContentModelName()
     *
     * @static
     * @param Title $title
     * @return null|string default model name for the page given by $title
     */
    public static function getDefaultModelFor( Title $title ) {
        global $wgNamespaceContentModels;

        // NOTE: this method must not rely on $title->getContentModelName() directly or indirectly,
        //       because it is used to initialized the mContentModelName memebr.

        $ns = $title->getNamespace();

        $ext = false;
        $m = null;
        $model = null;

        if ( !empty( $wgNamespaceContentModels[ $ns ] ) ) {
            $model = $wgNamespaceContentModels[ $ns ];
        }

        // hook can determin default model
        if ( !wfRunHooks( 'DefaultModelFor', array( $title, &$model ) ) ) { #FIXME: document new hook!
            if ( !is_null( $model ) ) {
				return $model;
			}
        }

        // Could this page contain custom CSS or JavaScript, based on the title?
        $isCssOrJsPage = NS_MEDIAWIKI == $ns && preg_match( '!\.(css|js)$!u', $title->getText(), $m );
        if ( $isCssOrJsPage ) {
			$ext = $m[1];
		}

        // hook can force js/css
        wfRunHooks( 'TitleIsCssOrJsPage', array( $title, &$isCssOrJsPage ) );

        // Is this a .css subpage of a user page?
        $isJsCssSubpage = NS_USER == $ns && !$isCssOrJsPage && preg_match( "/\\/.*\\.(js|css)$/", $title->getText(), $m );
        if ( $isJsCssSubpage ) {
			$ext = $m[1];
		}

        // is this wikitext, according to $wgNamespaceContentModels or the DefaultModelFor hook?
        $isWikitext = is_null( $model ) || $model == CONTENT_MODEL_WIKITEXT;
        $isWikitext = $isWikitext && !$isCssOrJsPage && !$isJsCssSubpage;

        // hook can override $isWikitext
        wfRunHooks( 'TitleIsWikitextPage', array( $title, &$isWikitext ) );

        if ( !$isWikitext ) {
			switch ( $ext ) {
				case 'js':
					return CONTENT_MODEL_JAVASCRIPT;
				case 'css':
					return CONTENT_MODEL_CSS;
				default:
					return is_null( $model ) ? CONTENT_MODEL_TEXT : $model;
			}
        }

        // we established that is must be wikitext

        return CONTENT_MODEL_WIKITEXT;
    }

    /**
     * returns the appropriate ContentHandler singleton for the given title
     *
     * @static
     * @param Title $title
     * @return ContentHandler
     */
    public static function getForTitle( Title $title ) {
        $modelName = $title->getContentModelName();
        return ContentHandler::getForModelName( $modelName );
    }

    /**
     * returns the appropriate ContentHandler singleton for the given Content object
     *
     * @static
     * @param Content $content
     * @return ContentHandler
     */
    public static function getForContent( Content $content ) {
        $modelName = $content->getModelName();
        return ContentHandler::getForModelName( $modelName );
    }

    /**
     * returns the ContentHandler singleton for the given model name. Use the CONTENT_MODEL_XXX constants to
     * identify the desired content model.
     *
     * ContentHandler singletons are take from the global $wgContentHandlers array. Keys in that array are
     * model names, the values are either ContentHandler singleton objects, or strings specifying the appropriate
     * subclass of ContentHandler.
     *
     * If a class name in encountered when looking up the singleton for a given model name, the class is
     * instantiated and the class name is replaced by te resulting singleton in $wgContentHandlers.
     *
     * If no ContentHandler is defined for the desired $modelName, the ContentHandler may be provided by the
     * a ContentHandlerForModelName hook. if no Contenthandler can be determined, an MWException is raised.
     *
     * @static
     * @param $modelName String the name of the content model for which to get a handler. Use CONTENT_MODEL_XXX constants.
     * @return ContentHandler the ContentHandler singleton for handling the model given by $modelName
     * @throws MWException if no handler is known for $modelName.
     */
    public static function getForModelName( $modelName ) {
        global $wgContentHandlers;

        if ( empty( $wgContentHandlers[$modelName] ) ) {
            $handler = null;

			// FIXME: document new hook
            wfRunHooks( 'ContentHandlerForModelName', array( $modelName, &$handler ) );

            if ( $handler ) { // NOTE: may be a string or an object, either is fine!
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

    // ----------------------------------------------------------------------------------------------------------

    /**
     * Constructor, initializing the ContentHandler instance with it's model name and a list of supported formats.
     * Values for the parameters are typically provided as literals by subclasses' constructors.
     *
     * @param String $modelName (use CONTENT_MODEL_XXX constants).
     * @param array $formats list for supported serialization formats (typically as MIME types)
     */
    public function __construct( $modelName, $formats ) {
        $this->mModelName = $modelName;
        $this->mSupportedFormats = $formats;
    }


    /**
     * Serializes Content object of the type supported by this ContentHandler.
     *
     * @abstract
     * @param Content $content the Content object to serialize
     * @param null $format the desired serialization format
     * @return String serialized form of the content
     */
    public abstract function serializeContent( Content $content, $format = null );

    /**
     * Unserializes a Content object of the type supported by this ContentHandler.
     *
     * @abstract
     * @param $blob String serialized form of the content
     * @param null $format the format used for serialization
     * @return Content the Content object created by deserializing $blob
     */
    public abstract function unserializeContent( $blob, $format = null );

    /**
     * Creates an empty Content object of the type supported by this ContentHandler.
     *
     */
    public abstract function makeEmptyContent();

    /**
     * Returns the model name that identifies the content model this ContentHandler can handle.
     * Use with the CONTENT_MODEL_XXX constants.
     *
     * @return String the model name
     */
    public function getModelName() {
        return $this->mModelName;
    }

    /**
     * Throws an MWException if $modelName is not the content model handeled by this ContentHandler.
     *
     * @param $modelName the model name to check
     */
    protected function checkModelName( $modelName ) {
        if ( $modelName !== $this->mModelName ) {
            throw new MWException( "Bad content model: expected " . $this->mModelName . " but got found " . $modelName );
        }
    }

    /**
     * Returns a list of serialization formats supported by the serializeContent() and unserializeContent() methods of
     * this ContentHandler.
     *
     * @return array of serialization formats as MIME type like strings
     */
    public function getSupportedFormats() {
        return $this->mSupportedFormats;
    }

    /**
     * The format used for serialization/deserialization per default by this ContentHandler.
     *
     * This default implementation will return the first element of the array of formats
     * that was passed to the constructor.
     *
     * @return String the name of the default serialiozation format as a MIME type
     */
    public function getDefaultFormat() {
        return $this->mSupportedFormats[0];
    }

    /**
     * Returns true if $format is a serialization format supported by this ContentHandler,
     * and false otherwise.
     *
     * Note that if $format is null, this method always returns true, because null
     * means "use the default format".
     *
     * @param $format the serialization format to check
     * @return bool
     */
    public function isSupportedFormat( $format ) {

        if ( !$format ) {
			return true; // this means "use the default"
		}

        return in_array( $format, $this->mSupportedFormats );
    }

    /**
     * Throws an MWException if isSupportedFormat( $format ) is not true. Convenient
     * for checking whether a format provided as a parameter is actually supported.
     *
     * @param $format the serialization format to check
     */
    protected function checkFormat( $format ) {
        if ( !$this->isSupportedFormat( $format ) ) {
            throw new MWException( "Format $format is not supported for content model " . $this->getModelName() );
        }
    }

    /**
     * Returns overrides for action handlers.
     * Classes listed here will be used instead of the default one when
     * (and only when) $wgActions[$action] === true. This allows subclasses
     * to override the default action handlers.
     *
     * @return Array
     */
    public function getActionOverrides() {
        return array();
    }

    /**
     * Return an Article object suitable for viewing the given object
     *
     * NOTE: does *not* do special handling for Image and Category pages!
     *       Use Article::newFromTitle() for that!
     *
     * @param Title $title
     * @return Article
     * @todo Article is being refactored into an action class, keep track of that
     * @todo Article really defines the view of the content... rename this method to createViewPage ?
     */
    public function createArticle( Title $title ) {
        $this->checkModelName( $title->getContentModelName() );

        $article = new Article($title);
        return $article;
    }

    /**
     * Return an EditPage object suitable for editing the given object
     *
     * @param Article $article
     * @return EditPage
     */
    public function createEditPage( Article $article ) {
        $this->checkModelName( $article->getContentModelName() );

        $editPage = new EditPage( $article );
        return $editPage;
    }

    /**
     * Return an ExternalEdit object suitable for editing the given object
     *
     * @param IContextSource $context
     * @return ExternalEdit
     * @todo does anyone or anythign actually use the external edit facility? Can we just deprecate and ignore it?
     */
    public function createExternalEdit( IContextSource $context ) {
        $this->checkModelName( $context->getTitle()->getContentModelName() );

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
	 *
	 * @return DifferenceEngine
     */
    public function createDifferenceEngine( IContextSource $context, $old = 0, $new = 0, $rcid = 0, #FIMXE: use everywhere!
                                         $refreshCache = false, $unhide = false ) {

        $this->checkModelName( $context->getTitle()->getContentModelName() );

		$diffEngineClass = $this->getDiffEngineClass();

        return new $diffEngineClass( $context, $old, $new, $rcid, $refreshCache, $unhide );
    }

	/**
	 * Returns the name of the diff engine to use.
	 *
	 * @since 0.1
	 *
	 * @return string
	 */
	protected function getDiffEngineClass() {
		return 'DifferenceEngine';
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
     * @param $oldContent Content|null: the previous text of the page.
     * @param $newContent Content|null: The submitted text of the page.
     * @param $flags Int bitmask: a bitmask of flags submitted for the edit.
     *
     * @return string An appropriate autosummary, or an empty string.
     */
    public function getAutosummary( Content $oldContent = null, Content $newContent = null, $flags ) {
        global $wgContLang;

        // Decide what kind of autosummary is needed.

        // Redirect autosummaries

        $ot = !is_null( $oldContent ) ? $oldContent->getRedirectTarget() : false;
        $rt = !is_null( $newContent ) ? $newContent->getRedirectTarget() : false;

        if ( is_object( $rt ) && ( !is_object( $ot ) || !$rt->equals( $ot ) || $ot->getFragment() != $rt->getFragment() ) ) {

            $truncatedtext = $newContent->getTextForSummary(
                250
                    - strlen( wfMsgForContent( 'autoredircomment' ) )
                    - strlen( $rt->getFullText() ) );

            return wfMsgForContent( 'autoredircomment', $rt->getFullText(), $truncatedtext );
        }

        // New page autosummaries
        if ( $flags & EDIT_NEW && $newContent->getSize() > 0 ) {
            // If they're making a new article, give its text, truncated, in the summary.

            $truncatedtext = $newContent->getTextForSummary(
                200 - strlen( wfMsgForContent( 'autosumm-new' ) ) );

            return wfMsgForContent( 'autosumm-new', $truncatedtext );
        }

        // Blanking autosummaries
        if ( !empty( $oldContent ) && $oldContent->getSize() > 0 && $newContent->getSize() == 0 ) {
            return wfMsgForContent( 'autosumm-blank' );
        } elseif ( !empty( $oldContent ) && $oldContent->getSize() > 10 * $newContent->getSize() && $newContent->getSize() < 500 ) {
            // Removing more than 90% of the article

            $truncatedtext = $newContent->getTextForSummary(
                200 - strlen( wfMsgForContent( 'autosumm-replace' ) ) );

            return wfMsgForContent( 'autosumm-replace', $truncatedtext );
        }

        // If we reach this point, there's no applicable autosummary for our case, so our
        // autosummary is empty.

        return '';
    }

    /**
     * Auto-generates a deletion reason
     *
     * @param $title Title: the page's title
     * @param &$hasHistory Boolean: whether the page has a history
     * @return mixed String containing deletion reason or empty string, or boolean false
     *    if no revision occurred
     *
     * @XXX &$hasHistory is extremely ugly, it's here because WikiPage::getAutoDeleteReason() and Article::getReason() have it / want it.
     */
    public function getAutoDeleteReason( Title $title, &$hasHistory ) {
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

    #@TODO: getSecondaryUpdatesForDeletion( Content ) returns an array of SecondaryDataUpdate objects
    #... or do that in the Content class?

    /**
     * Get the Content object that needs to be saved in order to undo all revisions
     * between $undo and $undoafter. Revisions must belong to the same page,
     * must exist and must not be deleted
     * @param $current Revision the current text
     * @param $undo Revision the revision to undo
     * @param $undoafter Revision Must be an earlier revision than $undo
     * @return mixed string on success, false on failure
     */
    public function getUndoContent( Revision $current, Revision $undo, Revision $undoafter ) {
        $cur_content = $current->getContent();

        if ( empty( $cur_content ) ) {
            return false; // no page
        }

        $undo_content = $undo->getContent();
        $undoafter_content = $undoafter->getContent();

        if ( $cur_content->equals( $undo_content ) ) {
            // No use doing a merge if it's just a straight revert.
            return $undoafter_content;
        }

        $undone_content = $this->merge3( $undo_content, $undoafter_content, $cur_content );

        return $undone_content;
    }

	/**
	 * Returns true for content models that support caching using the ParserCache mechanism.
	 * See WikiPage::isParserCacheUser().
	 *
	 * @return book
	 */
	public function isParserCacheSupported() {
		return true;
	}
}


abstract class TextContentHandler extends ContentHandler {

    public function __construct( $modelName, $formats ) {
        parent::__construct( $modelName, $formats );
    }

    public function serializeContent( Content $content, $format = null ) {
        $this->checkFormat( $format );
        return $content->getNativeData();
    }

    /**
     * attempts to merge differences between three versions.
     * Returns a new Content object for a clean merge and false for failure or a conflict.
     *
     * All three Content objects passed as parameters must have the same content model.
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
        $this->checkModelName( $myContent->getModelName() );
        $this->checkModelName( $yourContent->getModelName() );

        $format = $this->getDefaultFormat();

        $old = $this->serializeContent( $oldContent, $format );
        $mine = $this->serializeContent( $myContent, $format );
        $yours = $this->serializeContent( $yourContent, $format );

        $ok = wfMerge( $old, $mine, $yours, $result );

        if ( !$ok ) {
			return false;
		}

        if ( !$result ) {
			return $this->makeEmptyContent();
		}

        $mergedContent = $this->unserializeContent( $result, $format );
        return $mergedContent;
    }


}
class WikitextContentHandler extends TextContentHandler {

    public function __construct( $modelName = CONTENT_MODEL_WIKITEXT ) {
        parent::__construct( $modelName, array( 'application/x-wiki' ) ); 
    }

    public function unserializeContent( $text, $format = null ) {
        $this->checkFormat( $format );

        return new WikitextContent( $text );
    }

    public function makeEmptyContent() {
        return new WikitextContent( '' );
    }


}

#XXX: make ScriptContentHandler base class with plugin interface for syntax highlighting?

class JavaScriptContentHandler extends TextContentHandler {

    public function __construct( $modelName = CONTENT_MODEL_WIKITEXT ) {
        parent::__construct( $modelName, array( 'text/javascript' ) ); #XXX: or use $wgJsMimeType? this is for internal storage, not HTTP...
    }

    public function unserializeContent( $text, $format = null ) {
        $this->checkFormat( $format );

        return new JavaScriptContent( $text );
    }

    public function makeEmptyContent() {
        return new JavaScriptContent( '' );
    }
}

class CssContentHandler extends TextContentHandler {

    public function __construct( $modelName = CONTENT_MODEL_WIKITEXT ) {
        parent::__construct( $modelName, array( 'text/css' ) );
    }

    public function unserializeContent( $text, $format = null ) {
        $this->checkFormat( $format );

        return new CssContent( $text );
    }

    public function makeEmptyContent() {
        return new CssContent( '' );
    }

}
