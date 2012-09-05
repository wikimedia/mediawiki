<?php

/**
 * Exception representing a failure to serialize or unserialize a content object.
 */
class MWContentSerializationException extends MWException {

}

/**
 * A content handler knows how do deal with a specific type of content on a wiki
 * page. Content is stored in the database in a serialized form (using a
 * serialization format a.k.a. MIME type) and is unserialized into its native
 * PHP representation (the content model), which is wrapped in an instance of
 * the appropriate subclass of Content.
 *
 * ContentHandler instances are stateless singletons that serve, among other
 * things, as a factory for Content objects. Generally, there is one subclass
 * of ContentHandler and one subclass of Content for every type of content model.
 *
 * Some content types have a flat model, that is, their native representation
 * is the same as their serialized form. Examples would be JavaScript and CSS
 * code. As of now, this also applies to wikitext (MediaWiki's default content
 * type), but wikitext content may be represented by a DOM or AST structure in
 * the future.
 *
 * @since 1.WD
 */
abstract class ContentHandler {

	/**
	 * Convenience function for getting flat text from a Content object. This
	 * should only be used in the context of backwards compatibility with code
	 * that is not yet able to handle Content objects!
	 *
	 * If $content is null, this method returns the empty string.
	 *
	 * If $content is an instance of TextContent, this method returns the flat
	 * text as returned by $content->getNativeData().
	 *
	 * If $content is not a TextContent object, the behavior of this method
	 * depends on the global $wgContentHandlerTextFallback:
	 * - If $wgContentHandlerTextFallback is 'fail' and $content is not a
	 *   TextContent object, an MWException is thrown.
	 * - If $wgContentHandlerTextFallback is 'serialize' and $content is not a
	 *   TextContent object, $content->serialize() is called to get a string
	 *   form of the content.
	 * - If $wgContentHandlerTextFallback is 'ignore' and $content is not a
	 *   TextContent object, this method returns null.
	 * - otherwise, the behaviour is undefined.
	 *
	 * @since WD.1
	 * @deprecated since WD.1. Always try to use the content object.
	 *
	 * @static
	 * @param $content Content|null
	 * @return null|string the textual form of $content, if available
	 * @throws MWException if $content is not an instance of TextContent and
	 *   $wgContentHandlerTextFallback was set to 'fail'.
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
			throw new MWException(
				"Attempt to get text from Content with model " .
				$content->getModel()
			);
		}

		if ( $wgContentHandlerTextFallback == 'serialize' ) {
			return $content->serialize();
		}

		return null;
	}

	/**
	 * Convenience function for creating a Content object from a given textual
	 * representation.
	 *
	 * $text will be deserialized into a Content object of the model specified
	 * by $modelId (or, if that is not given, $title->getContentModel()) using
	 * the given format.
	 *
	 * @since WD.1
	 *
	 * @static
	 *
	 * @param $text string the textual representation, will be
	 *    unserialized to create the Content object
	 * @param $title null|Title the title of the page this text belongs to.
	 *    Required if $modelId is not provided.
	 * @param $modelId null|string the model to deserialize to. If not provided,
	 *    $title->getContentModel() is used.
	 * @param $format null|string the format to use for deserialization. If not
	 *    given, the model's default format is used.
	 *
	 * @return Content a Content object representing $text
	 *
	 * @throw MWException if $model or $format is not supported or if $text can
	 *    not be unserialized using $format.
	 */
	public static function makeContent( $text, Title $title = null,
		$modelId = null, $format = null )
	{
		if ( is_null( $modelId ) ) {
			if ( is_null( $title ) ) {
				throw new MWException( "Must provide a Title object or a content model ID." );
			}

			$modelId = $title->getContentModel();
		}

		$handler = ContentHandler::getForModelID( $modelId );
		return $handler->unserializeContent( $text, $format );
	}

	/**
	 * Returns the name of the default content model to be used for the page
	 * with the given title.
	 *
	 * Note: There should rarely be need to call this method directly.
	 * To determine the actual content model for a given page, use
	 * Title::getContentModel().
	 *
	 * Which model is to be used by default for the page is determined based
	 * on several factors:
	 * - The global setting $wgNamespaceContentModels specifies a content model
	 *   per namespace.
	 * - The hook DefaultModelFor may be used to override the page's default
	 *   model.
	 * - Pages in NS_MEDIAWIKI and NS_USER default to the CSS or JavaScript
	 *   model if they end in .js or .css, respectively.
	 * - Pages in NS_MEDIAWIKI default to the wikitext model otherwise.
	 * - The hook TitleIsCssOrJsPage may be used to force a page to use the CSS
	 *   or JavaScript model if they end in .js or .css, respectively.
	 * - The hook TitleIsWikitextPage may be used to force a page to use the
	 *   wikitext model.
	 *
	 * If none of the above applies, the wikitext model is used.
	 *
	 * Note: this is used by, and may thus not use, Title::getContentModel()
	 *
	 * @since WD.1
	 *
	 * @static
	 * @param $title Title
	 * @return null|string default model name for the page given by $title
	 */
	public static function getDefaultModelFor( Title $title ) {
		global $wgNamespaceContentModels;

		// NOTE: this method must not rely on $title->getContentModel() directly or indirectly,
		//       because it is used to initialize the mContentModel member.

		$ns = $title->getNamespace();

		$ext = false;
		$m = null;
		$model = null;

		if ( !empty( $wgNamespaceContentModels[ $ns ] ) ) {
			$model = $wgNamespaceContentModels[ $ns ];
		}

		// Hook can determine default model
		if ( !wfRunHooks( 'ContentHandlerDefaultModelFor', array( $title, &$model ) ) ) {
			if ( !is_null( $model ) ) {
				return $model;
			}
		}

		// Could this page contain custom CSS or JavaScript, based on the title?
		$isCssOrJsPage = NS_MEDIAWIKI == $ns && preg_match( '!\.(css|js)$!u', $title->getText(), $m );
		if ( $isCssOrJsPage ) {
			$ext = $m[1];
		}

		// Hook can force JS/CSS
		wfRunHooks( 'TitleIsCssOrJsPage', array( $title, &$isCssOrJsPage ) );

		// Is this a .css subpage of a user page?
		$isJsCssSubpage = NS_USER == $ns
			&& !$isCssOrJsPage
			&& preg_match( "/\\/.*\\.(js|css)$/", $title->getText(), $m );
		if ( $isJsCssSubpage ) {
			$ext = $m[1];
		}

		// Is this wikitext, according to $wgNamespaceContentModels or the DefaultModelFor hook?
		$isWikitext = is_null( $model ) || $model == CONTENT_MODEL_WIKITEXT;
		$isWikitext = $isWikitext && !$isCssOrJsPage && !$isJsCssSubpage;

		// Hook can override $isWikitext
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

		// We established that it must be wikitext

		return CONTENT_MODEL_WIKITEXT;
	}

	/**
	 * Returns the appropriate ContentHandler singleton for the given title.
	 *
	 * @since WD.1
	 *
	 * @static
	 * @param $title Title
	 * @return ContentHandler
	 */
	public static function getForTitle( Title $title ) {
		$modelId = $title->getContentModel();
		return ContentHandler::getForModelID( $modelId );
	}

	/**
	 * Returns the appropriate ContentHandler singleton for the given Content
	 * object.
	 *
	 * @since WD.1
	 *
	 * @static
	 * @param $content Content
	 * @return ContentHandler
	 */
	public static function getForContent( Content $content ) {
		$modelId = $content->getModel();
		return ContentHandler::getForModelID( $modelId );
	}

	/**
	 * @var Array A Cache of ContentHandler instances by model id
	 */
	static $handlers;

	/**
	 * Returns the ContentHandler singleton for the given model ID. Use the
	 * CONTENT_MODEL_XXX constants to identify the desired content model.
	 *
	 * ContentHandler singletons are taken from the global $wgContentHandlers
	 * array. Keys in that array are model names, the values are either
	 * ContentHandler singleton objects, or strings specifying the appropriate
	 * subclass of ContentHandler.
	 *
	 * If a class name is encountered when looking up the singleton for a given
	 * model name, the class is instantiated and the class name is replaced by
	 * the resulting singleton in $wgContentHandlers.
	 *
	 * If no ContentHandler is defined for the desired $modelId, the
	 * ContentHandler may be provided by the ContentHandlerForModelID hook.
	 * If no ContentHandler can be determined, an MWException is raised.
	 *
	 * @since WD.1
	 *
	 * @static
	 * @param $modelId String The ID of the content model for which to get a
	 *    handler. Use CONTENT_MODEL_XXX constants.
	 * @return ContentHandler The ContentHandler singleton for handling the
	 *    model given by $modelId
	 * @throws MWException if no handler is known for $modelId.
	 */
	public static function getForModelID( $modelId ) {
		global $wgContentHandlers;

		if ( isset( ContentHandler::$handlers[$modelId] ) ) {
			return ContentHandler::$handlers[$modelId];
		}

		if ( empty( $wgContentHandlers[$modelId] ) ) {
			$handler = null;

			wfRunHooks( 'ContentHandlerForModelID', array( $modelId, &$handler ) );

			if ( $handler === null ) {
				throw new MWException( "No handler for model #$modelId registered in \$wgContentHandlers" );
			}

			if ( !( $handler instanceof ContentHandler ) ) {
				throw new MWException( "ContentHandlerForModelID must supply a ContentHandler instance" );
			}
		} else {
			$class = $wgContentHandlers[$modelId];
			$handler = new $class( $modelId );

			if ( !( $handler instanceof ContentHandler ) ) {
				throw new MWException( "$class from \$wgContentHandlers is not compatible with ContentHandler" );
			}
		}

		ContentHandler::$handlers[$modelId] = $handler;
		return ContentHandler::$handlers[$modelId];
	}

	/**
	 * Returns the localized name for a given content model.
	 *
	 * Model names are localized using system messages. Message keys
	 * have the form content-model-$name, where $name is getContentModelName( $id ).
	 *
	 * @static
	 * @param $name String The content model ID, as given by a CONTENT_MODEL_XXX
	 *    constant or returned by Revision::getContentModel().
	 *
	 * @return string The content format's localized name.
	 * @throws MWException if the model id isn't known.
	 */
	public static function getLocalizedName( $name ) {
		$key = "content-model-$name";

		if ( wfEmptyMsg( $key ) ) return $name;
		else return wfMsg( $key );
	}

	public static function getContentModels() {
		global $wgContentHandlers;

		return array_keys( $wgContentHandlers );
	}

	public static function getAllContentFormats() {
		global $wgContentHandlers;

		$formats = array();

		foreach ( $wgContentHandlers as $model => $class ) {
			$handler = ContentHandler::getForModelID( $model );
			$formats = array_merge( $formats, $handler->getSupportedFormats() );
		}

		$formats = array_unique( $formats );
		return $formats;
	}

	// ------------------------------------------------------------------------

	protected $mModelID;
	protected $mSupportedFormats;

	/**
	 * Constructor, initializing the ContentHandler instance with its model ID
	 * and a list of supported formats. Values for the parameters are typically
	 * provided as literals by subclass's constructors.
	 *
	 * @param $modelId String (use CONTENT_MODEL_XXX constants).
	 * @param $formats array List for supported serialization formats
	 *    (typically as MIME types)
	 */
	public function __construct( $modelId, $formats ) {
		$this->mModelID = $modelId;
		$this->mSupportedFormats = $formats;

		$this->mModelName = preg_replace( '/(Content)?Handler$/', '', get_class( $this ) );
		$this->mModelName = preg_replace( '/[_\\\\]/', '', $this->mModelName );
		$this->mModelName = strtolower( $this->mModelName );
	}

	/**
	 * Serializes a Content object of the type supported by this ContentHandler.
	 *
	 * @since WD.1
	 *
	 * @abstract
	 * @param $content Content The Content object to serialize
	 * @param $format null|String The desired serialization format
	 * @return string Serialized form of the content
	 */
	public abstract function serializeContent( Content $content, $format = null );

	/**
	 * Unserializes a Content object of the type supported by this ContentHandler.
	 *
	 * @since WD.1
	 *
	 * @abstract
	 * @param $blob string serialized form of the content
	 * @param $format null|String the format used for serialization
	 * @return Content the Content object created by deserializing $blob
	 */
	public abstract function unserializeContent( $blob, $format = null );

	/**
	 * Creates an empty Content object of the type supported by this
	 * ContentHandler.
	 *
	 * @since WD.1
	 *
	 * @return Content
	 */
	public abstract function makeEmptyContent();

	/**
	 * Returns the model id that identifies the content model this
	 * ContentHandler can handle. Use with the CONTENT_MODEL_XXX constants.
	 *
	 * @since WD.1
	 *
	 * @return String The model ID
	 */
	public function getModelID() {
		return $this->mModelID;
	}

	/**
	 * Throws an MWException if $model_id is not the ID of the content model
	 * supported by this ContentHandler.
	 *
	 * @since WD.1
	 *
	 * @param String $model_id The model to check
	 *
	 * @throws MWException
	 */
	protected function checkModelID( $model_id ) {
		if ( $model_id !== $this->mModelID ) {
			throw new MWException( "Bad content model: " .
				"expected {$this->mModelID} " .
				"but got $model_id." );
		}
	}

	/**
	 * Returns a list of serialization formats supported by the
	 * serializeContent() and unserializeContent() methods of this
	 * ContentHandler.
	 *
	 * @since WD.1
	 *
	 * @return array of serialization formats as MIME type like strings
	 */
	public function getSupportedFormats() {
		return $this->mSupportedFormats;
	}

	/**
	 * The format used for serialization/deserialization by default by this
	 * ContentHandler.
	 *
	 * This default implementation will return the first element of the array
	 * of formats that was passed to the constructor.
	 *
	 * @since WD.1
	 *
	 * @return string the name of the default serialization format as a MIME type
	 */
	public function getDefaultFormat() {
		return $this->mSupportedFormats[0];
	}

	/**
	 * Returns true if $format is a serialization format supported by this
	 * ContentHandler, and false otherwise.
	 *
	 * Note that if $format is null, this method always returns true, because
	 * null means "use the default format".
	 *
	 * @since WD.1
	 *
	 * @param $format string the serialization format to check
	 * @return bool
	 */
	public function isSupportedFormat( $format ) {

		if ( !$format ) {
			return true; // this means "use the default"
		}

		return in_array( $format, $this->mSupportedFormats );
	}

	/**
	 * Throws an MWException if isSupportedFormat( $format ) is not true.
	 * Convenient for checking whether a format provided as a parameter is
	 * actually supported.
	 *
	 * @param $format string the serialization format to check
	 *
	 * @throws MWException
	 */
	protected function checkFormat( $format ) {
		if ( !$this->isSupportedFormat( $format ) ) {
			throw new MWException(
				"Format $format is not supported for content model "
				. $this->getModelID()
			);
		}
	}

	/**
	 * Returns overrides for action handlers.
	 * Classes listed here will be used instead of the default one when
	 * (and only when) $wgActions[$action] === true. This allows subclasses
	 * to override the default action handlers.
	 *
	 * @since WD.1
	 *
	 * @return Array
	 */
	public function getActionOverrides() {
		return array();
	}

	/**
	 * Factory for creating an appropriate DifferenceEngine for this content model.
	 *
	 * @since WD.1
	 *
	 * @param $context IContextSource context to use, anything else will be
	 *    ignored
	 * @param $old Integer Old ID we want to show and diff with.
	 * @param $new int|string String either 'prev' or 'next'.
	 * @param $rcid Integer ??? FIXME (default 0)
	 * @param $refreshCache boolean If set, refreshes the diff cache
	 * @param $unhide boolean If set, allow viewing deleted revs
	 *
	 * @return DifferenceEngine
	 */
	public function createDifferenceEngine( IContextSource $context,
		$old = 0, $new = 0,
		$rcid = 0, # FIXME: use everywhere!
		$refreshCache = false, $unhide = false
	) {
		$this->checkModelID( $context->getTitle()->getContentModel() );

		$diffEngineClass = $this->getDiffEngineClass();

		return new $diffEngineClass( $context, $old, $new, $rcid, $refreshCache, $unhide );
	}

	/**
	 * Get the language in which the content of the given page is written.
	 *
	 * This default implementation just returns $wgContLang (except for pages in the MediaWiki namespace)
	 *
	 * Note that a page's language must be permanent and cacheable, that is, it must not depend
	 * on user preferences, request parameters or session state. The only exception is pages in the
	 * MediaWiki namespace.
	 *
	 * Also note that the page language may or may not depend on the actual content of the page,
	 * that is, this method may load the content in order to determine the language.
	 *
	 * @since 1.WD
	 *
	 * @param Title        $title the page to determine the language for.
	 * @param Content|null $content the page's content, if you have it handy, to avoid reloading it.
	 *
	 * @return Language the page's language code
	 */
	public function getPageLanguage( Title $title, Content $content = null ) {
		global $wgContLang;

		if ( $title->getNamespace() == NS_MEDIAWIKI ) {
			// Parse mediawiki messages with correct target language
			list( /* $unused */, $lang ) = MessageCache::singleton()->figureMessage( $title->getText() );
			return wfGetLangObj( $lang );
		}

		return $wgContLang;
	}

	/**
	 * Get the language in which the content of this page is written when
	 * viewed by user. Defaults to $this->getPageLanguage(), but if the user
	 * specified a preferred variant, the variant will be used.
	 *
	 * This default implementation just returns $this->getPageLanguage( $title, $content ) unless
	 * the user specified a preferred variant.
	 *
	 * Note that the pages view language is not cacheable, since it depends on user settings.
	 *
	 * Also note that the page language may or may not depend on the actual content of the page,
	 * that is, this method may load the content in order to determine the language.
	 *
	 * @since 1.WD
	 *
	 * @param Title        $title the page to determine the language for.
	 * @param Content|null $content the page's content, if you have it handy, to avoid reloading it.
	 *
	 * @return Language the page's language code for viewing
	 */
	public function getPageViewLanguage( Title $title, Content $content = null ) {
		$pageLang = $this->getPageLanguage( $title, $content );

		if ( $title->getNamespace() !== NS_MEDIAWIKI ) {
			// If the user chooses a variant, the content is actually
			// in a language whose code is the variant code.
			$variant = $pageLang->getPreferredVariant();
			if ( $pageLang->getCode() !== $variant ) {
				$pageLang = Language::factory( $variant );
			}
		}

		return $pageLang;
	}

	/**
	 * Returns the name of the diff engine to use.
	 *
	 * @since WD.1
	 *
	 * @return string
	 */
	protected function getDiffEngineClass() {
		return 'DifferenceEngine';
	}

	/**
	 * Attempts to merge differences between three versions.
	 * Returns a new Content object for a clean merge and false for failure or
	 * a conflict.
	 *
	 * This default implementation always returns false.
	 *
	 * @since WD.1
	 *
	 * @param $oldContent Content|string  String
	 * @param $myContent Content|string   String
	 * @param $yourContent Content|string String
	 *
	 * @return Content|Bool
	 */
	public function merge3( Content $oldContent, Content $myContent, Content $yourContent ) {
		return false;
	}

	/**
	 * Return an applicable auto-summary if one exists for the given edit.
	 *
	 * @since WD.1
	 *
	 * @param $oldContent Content|null: the previous text of the page.
	 * @param $newContent Content|null: The submitted text of the page.
	 * @param $flags int Bit mask: a bit mask of flags submitted for the edit.
	 *
	 * @return string An appropriate auto-summary, or an empty string.
	 */
	public function getAutosummary( Content $oldContent = null, Content $newContent = null, $flags ) {
		global $wgContLang;

		// Decide what kind of auto-summary is needed.

		// Redirect auto-summaries

		/**
		 * @var $ot Title
		 * @var $rt Title
		 */

		$ot = !is_null( $oldContent ) ? $oldContent->getRedirectTarget() : null;
		$rt = !is_null( $newContent ) ? $newContent->getRedirectTarget() : null;

		if ( is_object( $rt ) ) {
			if ( !is_object( $ot )
				|| !$rt->equals( $ot )
				|| $ot->getFragment() != $rt->getFragment() )
			{
				$truncatedtext = $newContent->getTextForSummary(
					250
						- strlen( wfMessage( 'autoredircomment' )->inContentLanguage()->text() )
						- strlen( $rt->getFullText() ) );

				return wfMessage( 'autoredircomment', $rt->getFullText() )
						->rawParams( $truncatedtext )->inContentLanguage()->text();
			}
		}

		// New page auto-summaries
		if ( $flags & EDIT_NEW && $newContent->getSize() > 0 ) {
			// If they're making a new article, give its text, truncated, in
			// the summary.

			$truncatedtext = $newContent->getTextForSummary(
				200 - strlen( wfMessage( 'autosumm-new' )->inContentLanguage()->text() ) );

			return wfMessage( 'autosumm-new' )->rawParams( $truncatedtext )
					->inContentLanguage()->text();
		}

		// Blanking auto-summaries
		if ( !empty( $oldContent ) && $oldContent->getSize() > 0 && $newContent->getSize() == 0 ) {
			return wfMessage( 'autosumm-blank' )->inContentLanguage()->text();
		} elseif ( !empty( $oldContent )
			&& $oldContent->getSize() > 10 * $newContent->getSize()
			&& $newContent->getSize() < 500 )
		{
			// Removing more than 90% of the article

			$truncatedtext = $newContent->getTextForSummary(
				200 - strlen( wfMessage( 'autosumm-replace' )->inContentLanguage()->text() ) );

			return wfMessage( 'autosumm-replace' )->rawParams( $truncatedtext )
					->inContentLanguage()->text();
		}

		// If we reach this point, there's no applicable auto-summary for our
		// case, so our auto-summary is empty.
		return '';
	}

	/**
	 * Auto-generates a deletion reason
	 *
	 * @since WD.1
	 *
	 * @param $title Title: the page's title
	 * @param &$hasHistory Boolean: whether the page has a history
	 * @return mixed String containing deletion reason or empty string, or
	 *    boolean false if no revision occurred
	 *
	 * @XXX &$hasHistory is extremely ugly, it's here because
	 * WikiPage::getAutoDeleteReason() and Article::getReason()
	 * have it / want it.
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

		$this->checkModelID( $content->getModel() );

		// If the page is blank, use the text from the previous revision,
		// which can only be blank if there's a move/import/protect dummy
		// revision involved
		if ( $content->getSize() == 0 ) {
			$prev = $rev->getPrevious();

			if ( $prev )	{
				$content = $prev->getContent();
				$blank = true;
			}
		}

		// Find out if there was only one contributor
		// Only scan the last 20 revisions
		$res = $dbw->select( 'revision', 'rev_user_text',
			array(
				'rev_page' => $title->getArticleID(),
				$dbw->bitAnd( 'rev_deleted', Revision::DELETED_USER ) . ' = 0'
			),
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
			$reason = wfMessage( 'exbeforeblank', '$1' )->inContentLanguage()->text();
		} else {
			if ( $onlyAuthor ) {
				$reason = wfMessage(
					'excontentauthor',
					'$1',
					$onlyAuthor
				)->inContentLanguage()->text();
			} else {
				$reason = wfMessage( 'excontent', '$1' )->inContentLanguage()->text();
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
	 * must exist and must not be deleted.
	 *
	 * @since WD.1
	 *
	 * @param $current Revision The current text
	 * @param $undo Revision The revision to undo
	 * @param $undoafter Revision Must be an earlier revision than $undo
	 *
	 * @return mixed String on success, false on failure
	 */
	public function getUndoContent( Revision $current, Revision $undo, Revision $undoafter ) {
		$cur_content = $current->getContent();

		if ( empty( $cur_content ) ) {
			return false; // no page
		}

		$undo_content = $undo->getContent();
		$undoafter_content = $undoafter->getContent();

		$this->checkModelID( $cur_content->getModel() );
		$this->checkModelID( $undo_content->getModel() );
		$this->checkModelID( $undoafter_content->getModel() );

		if ( $cur_content->equals( $undo_content ) ) {
			// No use doing a merge if it's just a straight revert.
			return $undoafter_content;
		}

		$undone_content = $this->merge3( $undo_content, $undoafter_content, $cur_content );

		return $undone_content;
	}

	/**
	 * Returns true for content models that support caching using the
	 * ParserCache mechanism. See WikiPage::isParserCacheUser().
	 *
	 * @since WD.1
	 *
	 * @return bool
	 */
	public function isParserCacheSupported() {
		return true;
	}

	/**
	 * Returns true if this content model supports sections.
	 *
	 * This default implementation returns false.
	 *
	 * @return boolean whether sections are supported.
	 */
	public function supportsSections() {
		return false;
	}

	/**
	 * Call a legacy hook that uses text instead of Content objects.
	 * Will log a warning when a matching hook function is registered.
	 * If the textual representation of the content is changed by the
	 * hook function, a new Content object is constructed from the new
	 * text.
	 *
	 * @param $event String: event name
	 * @param $args Array: parameters passed to hook functions
	 * @param $warn bool: whether to log a warning (default: true). Should generally be true,
	 *                    may be set to false for testing.
	 *
	 * @return Boolean True if no handler aborted the hook
	 */
	public static function runLegacyHooks( $event, $args = array(), $warn = true ) {
		if ( !Hooks::isRegistered( $event ) ) {
			return true; // nothing to do here
		}

		if ( $warn ) {
			wfWarn( "Using obsolete hook $event" );
		}

		// convert Content objects to text
		$contentObjects = array();
		$contentTexts = array();

		foreach ( $args as $k => $v ) {
			if ( $v instanceof Content ) {
				/* @var Content $v */

				$contentObjects[$k] = $v;

				$v = $v->serialize();
				$contentTexts[ $k ] = $v;
				$args[ $k ] = $v;
			}
		}

		// call the hook functions
		$ok = wfRunHooks( $event, $args );

		// see if the hook changed the text
		foreach ( $contentTexts as $k => $orig ) {
			/* @var Content $content */

			$modified = $args[ $k ];
			$content = $contentObjects[$k];

			if ( $modified !== $orig ) {
				// text was changed, create updated Content object
				$content = $content->getContentHandler()->unserializeContent( $modified );
			}

			$args[ $k ] = $content;
		}

		return $ok;
	}
}

/**
 * @since WD.1
 */
abstract class TextContentHandler extends ContentHandler {

	public function __construct( $modelId, $formats ) {
		parent::__construct( $modelId, $formats );
	}

	/**
	 * Returns the content's text as-is.
	 *
	 * @param $content Content
	 * @param $format string|null
	 * @return mixed
	 */
	public function serializeContent( Content $content, $format = null ) {
		$this->checkFormat( $format );
		return $content->getNativeData();
	}

	/**
	 * Attempts to merge differences between three versions. Returns a new
	 * Content object for a clean merge and false for failure or a conflict.
	 *
	 * All three Content objects passed as parameters must have the same
	 * content model.
	 *
	 * This text-based implementation uses wfMerge().
	 *
	 * @param $oldContent \Content|string  String
	 * @param $myContent \Content|string   String
	 * @param $yourContent \Content|string String
	 *
	 * @return Content|Bool
	 */
	public function merge3( Content $oldContent, Content $myContent, Content $yourContent ) {
		$this->checkModelID( $oldContent->getModel() );
		$this->checkModelID( $myContent->getModel() );
		$this->checkModelID( $yourContent->getModel() );

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

/**
 * @since WD.1
 */
class WikitextContentHandler extends TextContentHandler {

	public function __construct( $modelId = CONTENT_MODEL_WIKITEXT ) {
		parent::__construct( $modelId, array( CONTENT_FORMAT_WIKITEXT ) );
	}

	public function unserializeContent( $text, $format = null ) {
		$this->checkFormat( $format );

		return new WikitextContent( $text );
	}

	public function makeEmptyContent() {
		return new WikitextContent( '' );
	}

	/**
	 * Returns true because wikitext supports sections.
	 *
	 * @return boolean whether sections are supported.
	 */
	public function supportsSections() {
		return true;
	}
}

# XXX: make ScriptContentHandler base class, do highlighting stuff there?

/**
 * @since WD.1
 */
class JavaScriptContentHandler extends TextContentHandler {

	public function __construct( $modelId = CONTENT_MODEL_JAVASCRIPT ) {
		parent::__construct( $modelId, array( CONTENT_FORMAT_JAVASCRIPT ) );
	}

	public function unserializeContent( $text, $format = null ) {
		$this->checkFormat( $format );

		return new JavaScriptContent( $text );
	}

	public function makeEmptyContent() {
		return new JavaScriptContent( '' );
	}

	/**
	 * Returns the english language, because JS is english, and should be handled as such.
	 *
	 * @return Language wfGetLangObj( 'en' )
	 *
	 * @see ContentHandler::getPageLanguage()
	 */
	public function getPageLanguage( Title $title, Content $content = null ) {
		return wfGetLangObj( 'en' );
	}

	/**
	 * Returns the english language, because CSS is english, and should be handled as such.
	 *
	 * @return Language wfGetLangObj( 'en' )
	 *
	 * @see ContentHandler::getPageViewLanguage()
	 */
	public function getPageViewLanguage( Title $title, Content $content = null ) {
		return wfGetLangObj( 'en' );
	}
}

/**
 * @since WD.1
 */
class CssContentHandler extends TextContentHandler {

	public function __construct( $modelId = CONTENT_MODEL_CSS ) {
		parent::__construct( $modelId, array( CONTENT_FORMAT_CSS ) );
	}

	public function unserializeContent( $text, $format = null ) {
		$this->checkFormat( $format );

		return new CssContent( $text );
	}

	public function makeEmptyContent() {
		return new CssContent( '' );
	}

	/**
	 * Returns the english language, because CSS is english, and should be handled as such.
	 *
	 * @return Language wfGetLangObj( 'en' )
	 *
	 * @see ContentHandler::getPageLanguage()
	 */
	public function getPageLanguage( Title $title, Content $content = null ) {
		return wfGetLangObj( 'en' );
	}

	/**
	 * Returns the english language, because CSS is english, and should be handled as such.
	 *
	 * @return Language wfGetLangObj( 'en' )
	 *
	 * @see ContentHandler::getPageViewLanguage()
	 */
	public function getPageViewLanguage( Title $title, Content $content = null ) {
		return wfGetLangObj( 'en' );
	}
}
