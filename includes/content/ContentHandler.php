<?php
/**
 * Base class for content handling.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @since 1.21
 *
 * @file
 * @ingroup Content
 *
 * @author Daniel Kinzler
 */

/**
 * Exception representing a failure to serialize or unserialize a content object.
 *
 * @ingroup Content
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
 * @ingroup Content
 */
abstract class ContentHandler {
	/**
	 * Switch for enabling deprecation warnings. Used by ContentHandler::deprecated()
	 * and ContentHandler::runLegacyHooks().
	 *
	 * Once the ContentHandler code has settled in a bit, this should be set to true to
	 * make extensions etc. show warnings when using deprecated functions and hooks.
	 */
	protected static $enableDeprecationWarnings = false;

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
	 * - otherwise, the behavior is undefined.
	 *
	 * @since 1.21
	 *
	 * @param Content $content
	 *
	 * @throws MWException If the content is not an instance of TextContent and
	 * wgContentHandlerTextFallback was set to 'fail'.
	 * @return string|null Textual form of the content, if available.
	 */
	public static function getContentText( Content $content = null ) {
		global $wgContentHandlerTextFallback;

		if ( is_null( $content ) ) {
			return '';
		}

		if ( $content instanceof TextContent ) {
			return $content->getNativeData();
		}

		wfDebugLog( 'ContentHandler', 'Accessing ' . $content->getModel() . ' content as text!' );

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
	 * @since 1.21
	 *
	 * @param string $text The textual representation, will be
	 *    unserialized to create the Content object
	 * @param Title $title The title of the page this text belongs to.
	 *    Required if $modelId is not provided.
	 * @param string $modelId The model to deserialize to. If not provided,
	 *    $title->getContentModel() is used.
	 * @param string $format The format to use for deserialization. If not
	 *    given, the model's default format is used.
	 *
	 * @throws MWException If model ID or format is not supported or if the text can not be
	 * unserialized using the format.
	 * @return Content A Content object representing the text.
	 */
	public static function makeContent( $text, Title $title = null,
		$modelId = null, $format = null ) {
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
	 * - The hook ContentHandlerDefaultModelFor may be used to override the page's default
	 *   model.
	 * - Pages in NS_MEDIAWIKI and NS_USER default to the CSS or JavaScript
	 *   model if they end in .js or .css, respectively.
	 * - Pages in NS_MEDIAWIKI default to the wikitext model otherwise.
	 * - The hook TitleIsCssOrJsPage may be used to force a page to use the CSS
	 *   or JavaScript model. This is a compatibility feature. The ContentHandlerDefaultModelFor
	 *   hook should be used instead if possible.
	 * - The hook TitleIsWikitextPage may be used to force a page to use the
	 *   wikitext model. This is a compatibility feature. The ContentHandlerDefaultModelFor
	 *   hook should be used instead if possible.
	 *
	 * If none of the above applies, the wikitext model is used.
	 *
	 * Note: this is used by, and may thus not use, Title::getContentModel()
	 *
	 * @since 1.21
	 *
	 * @param Title $title
	 *
	 * @return string Default model name for the page given by $title
	 */
	public static function getDefaultModelFor( Title $title ) {
		// NOTE: this method must not rely on $title->getContentModel() directly or indirectly,
		//       because it is used to initialize the mContentModel member.

		$ns = $title->getNamespace();

		$ext = false;
		$m = null;
		$model = MWNamespace::getNamespaceContentModel( $ns );

		// Hook can determine default model
		if ( !Hooks::run( 'ContentHandlerDefaultModelFor', array( $title, &$model ) ) ) {
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
		Hooks::run( 'TitleIsCssOrJsPage', array( $title, &$isCssOrJsPage ), '1.25' );

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
		Hooks::run( 'TitleIsWikitextPage', array( $title, &$isWikitext ), '1.25' );

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
	 * @since 1.21
	 *
	 * @param Title $title
	 *
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
	 * @since 1.21
	 *
	 * @param Content $content
	 *
	 * @return ContentHandler
	 */
	public static function getForContent( Content $content ) {
		$modelId = $content->getModel();

		return ContentHandler::getForModelID( $modelId );
	}

	/**
	 * @var array A Cache of ContentHandler instances by model id
	 */
	protected static $handlers;

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
	 * @since 1.21
	 *
	 * @param string $modelId The ID of the content model for which to get a
	 *    handler. Use CONTENT_MODEL_XXX constants.
	 *
	 * @throws MWException If no handler is known for the model ID.
	 * @return ContentHandler The ContentHandler singleton for handling the model given by the ID.
	 */
	public static function getForModelID( $modelId ) {
		global $wgContentHandlers;

		if ( isset( ContentHandler::$handlers[$modelId] ) ) {
			return ContentHandler::$handlers[$modelId];
		}

		if ( empty( $wgContentHandlers[$modelId] ) ) {
			$handler = null;

			Hooks::run( 'ContentHandlerForModelID', array( $modelId, &$handler ) );

			if ( $handler === null ) {
				throw new MWException( "No handler for model '$modelId' registered in \$wgContentHandlers" );
			}

			if ( !( $handler instanceof ContentHandler ) ) {
				throw new MWException( "ContentHandlerForModelID must supply a ContentHandler instance" );
			}
		} else {
			$class = $wgContentHandlers[$modelId];
			$handler = new $class( $modelId );

			if ( !( $handler instanceof ContentHandler ) ) {
				throw new MWException( "$class from \$wgContentHandlers is not " .
					"compatible with ContentHandler" );
			}
		}

		wfDebugLog( 'ContentHandler', 'Created handler for ' . $modelId
			. ': ' . get_class( $handler ) );

		ContentHandler::$handlers[$modelId] = $handler;

		return ContentHandler::$handlers[$modelId];
	}

	/**
	 * Returns the localized name for a given content model.
	 *
	 * Model names are localized using system messages. Message keys
	 * have the form content-model-$name, where $name is getContentModelName( $id ).
	 *
	 * @param string $name The content model ID, as given by a CONTENT_MODEL_XXX
	 *    constant or returned by Revision::getContentModel().
	 *
	 * @throws MWException If the model ID isn't known.
	 * @return string The content model's localized name.
	 */
	public static function getLocalizedName( $name ) {
		// Messages: content-model-wikitext, content-model-text,
		// content-model-javascript, content-model-css
		$key = "content-model-$name";

		$msg = wfMessage( $key );

		return $msg->exists() ? $msg->plain() : $name;
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

	/**
	 * @var string
	 */
	protected $mModelID;

	/**
	 * @var string[]
	 */
	protected $mSupportedFormats;

	/**
	 * Constructor, initializing the ContentHandler instance with its model ID
	 * and a list of supported formats. Values for the parameters are typically
	 * provided as literals by subclass's constructors.
	 *
	 * @param string $modelId (use CONTENT_MODEL_XXX constants).
	 * @param string[] $formats List for supported serialization formats
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
	 * @since 1.21
	 *
	 * @param Content $content The Content object to serialize
	 * @param string $format The desired serialization format
	 *
	 * @return string Serialized form of the content
	 */
	abstract public function serializeContent( Content $content, $format = null );

	/**
	 * Applies transformations on export (returns the blob unchanged per default).
	 * Subclasses may override this to perform transformations such as conversion
	 * of legacy formats or filtering of internal meta-data.
	 *
	 * @param string $blob The blob to be exported
	 * @param string|null $format The blob's serialization format
	 *
	 * @return string
	 */
	public function exportTransform( $blob, $format = null ) {
		return $blob;
	}

	/**
	 * Unserializes a Content object of the type supported by this ContentHandler.
	 *
	 * @since 1.21
	 *
	 * @param string $blob Serialized form of the content
	 * @param string $format The format used for serialization
	 *
	 * @return Content The Content object created by deserializing $blob
	 */
	abstract public function unserializeContent( $blob, $format = null );

	/**
	 * Apply import transformation (per default, returns $blob unchanged).
	 * This gives subclasses an opportunity to transform data blobs on import.
	 *
	 * @since 1.24
	 *
	 * @param string $blob
	 * @param string|null $format
	 *
	 * @return string
	 */
	public function importTransform( $blob, $format = null ) {
		return $blob;
	}

	/**
	 * Creates an empty Content object of the type supported by this
	 * ContentHandler.
	 *
	 * @since 1.21
	 *
	 * @return Content
	 */
	abstract public function makeEmptyContent();

	/**
	 * Creates a new Content object that acts as a redirect to the given page,
	 * or null if redirects are not supported by this content model.
	 *
	 * This default implementation always returns null. Subclasses supporting redirects
	 * must override this method.
	 *
	 * Note that subclasses that override this method to return a Content object
	 * should also override supportsRedirects() to return true.
	 *
	 * @since 1.21
	 *
	 * @param Title $destination The page to redirect to.
	 * @param string $text Text to include in the redirect, if possible.
	 *
	 * @return Content Always null.
	 */
	public function makeRedirectContent( Title $destination, $text = '' ) {
		return null;
	}

	/**
	 * Returns the model id that identifies the content model this
	 * ContentHandler can handle. Use with the CONTENT_MODEL_XXX constants.
	 *
	 * @since 1.21
	 *
	 * @return string The model ID
	 */
	public function getModelID() {
		return $this->mModelID;
	}

	/**
	 * @since 1.21
	 *
	 * @param string $model_id The model to check
	 *
	 * @throws MWException If the model ID is not the ID of the content model supported by this
	 * ContentHandler.
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
	 * @since 1.21
	 *
	 * @return string[] List of serialization formats as MIME type like strings
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
	 * @since 1.21
	 *
	 * @return string The name of the default serialization format as a MIME type
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
	 * @since 1.21
	 *
	 * @param string $format The serialization format to check
	 *
	 * @return bool
	 */
	public function isSupportedFormat( $format ) {
		if ( !$format ) {
			return true; // this means "use the default"
		}

		return in_array( $format, $this->mSupportedFormats );
	}

	/**
	 * Convenient for checking whether a format provided as a parameter is actually supported.
	 *
	 * @param string $format The serialization format to check
	 *
	 * @throws MWException If the format is not supported by this content handler.
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
	 * @since 1.21
	 *
	 * @return array Always an empty array.
	 */
	public function getActionOverrides() {
		return array();
	}

	/**
	 * Factory for creating an appropriate DifferenceEngine for this content model.
	 *
	 * @since 1.21
	 *
	 * @param IContextSource $context Context to use, anything else will be ignored.
	 * @param int $old Revision ID we want to show and diff with.
	 * @param int|string $new Either a revision ID or one of the strings 'cur', 'prev' or 'next'.
	 * @param int $rcid FIXME: Deprecated, no longer used. Defaults to 0.
	 * @param bool $refreshCache If set, refreshes the diff cache. Defaults to false.
	 * @param bool $unhide If set, allow viewing deleted revs. Defaults to false.
	 *
	 * @return DifferenceEngine
	 */
	public function createDifferenceEngine( IContextSource $context, $old = 0, $new = 0,
		$rcid = 0, //FIXME: Deprecated, no longer used
		$refreshCache = false, $unhide = false ) {

		// hook: get difference engine
		$differenceEngine = null;
		if ( !wfRunHooks( 'GetDifferenceEngine',
			array( $context, $old, $new, $refreshCache, $unhide, &$differenceEngine )
		) ) {
			return $differenceEngine;
		}
		$diffEngineClass = $this->getDiffEngineClass();
		return new $diffEngineClass( $context, $old, $new, $rcid, $refreshCache, $unhide );
	}

	/**
	 * Get the language in which the content of the given page is written.
	 *
	 * This default implementation just returns $wgContLang (except for pages
	 * in the MediaWiki namespace)
	 *
	 * Note that the pages language is not cacheable, since it may in some
	 * cases depend on user settings.
	 *
	 * Also note that the page language may or may not depend on the actual content of the page,
	 * that is, this method may load the content in order to determine the language.
	 *
	 * @since 1.21
	 *
	 * @param Title $title The page to determine the language for.
	 * @param Content $content The page's content, if you have it handy, to avoid reloading it.
	 *
	 * @return Language The page's language
	 */
	public function getPageLanguage( Title $title, Content $content = null ) {
		global $wgContLang, $wgLang;
		$pageLang = $wgContLang;

		if ( $title->getNamespace() == NS_MEDIAWIKI ) {
			// Parse mediawiki messages with correct target language
			list( /* $unused */, $lang ) = MessageCache::singleton()->figureMessage( $title->getText() );
			$pageLang = wfGetLangObj( $lang );
		}

		Hooks::run( 'PageContentLanguage', array( $title, &$pageLang, $wgLang ) );

		return wfGetLangObj( $pageLang );
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
	 * @since 1.21
	 *
	 * @param Title $title The page to determine the language for.
	 * @param Content $content The page's content, if you have it handy, to avoid reloading it.
	 *
	 * @return Language The page's language for viewing
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
	 * Determines whether the content type handled by this ContentHandler
	 * can be used on the given page.
	 *
	 * This default implementation always returns true.
	 * Subclasses may override this to restrict the use of this content model to specific locations,
	 * typically based on the namespace or some other aspect of the title, such as a special suffix
	 * (e.g. ".svg" for SVG content).
	 *
	 * @note this calls the ContentHandlerCanBeUsedOn hook which may be used to override which
	 * content model can be used where.
	 *
	 * @param Title $title The page's title.
	 *
	 * @return bool True if content of this kind can be used on the given page, false otherwise.
	 */
	public function canBeUsedOn( Title $title ) {
		$ok = true;

		Hooks::run( 'ContentModelCanBeUsedOn', array( $this->getModelID(), $title, &$ok ) );

		return $ok;
	}

	/**
	 * Returns the name of the diff engine to use.
	 *
	 * @since 1.21
	 *
	 * @return string
	 */
	protected function getDiffEngineClass() {
		return 'DifferenceEngine';
	}

	/**
	 * Attempts to merge differences between three versions. Returns a new
	 * Content object for a clean merge and false for failure or a conflict.
	 *
	 * This default implementation always returns false.
	 *
	 * @since 1.21
	 *
	 * @param Content $oldContent The page's previous content.
	 * @param Content $myContent One of the page's conflicting contents.
	 * @param Content $yourContent One of the page's conflicting contents.
	 *
	 * @return Content|bool Always false.
	 */
	public function merge3( Content $oldContent, Content $myContent, Content $yourContent ) {
		return false;
	}

	/**
	 * Return an applicable auto-summary if one exists for the given edit.
	 *
	 * @since 1.21
	 *
	 * @param Content $oldContent The previous text of the page.
	 * @param Content $newContent The submitted text of the page.
	 * @param int $flags Bit mask: a bit mask of flags submitted for the edit.
	 *
	 * @return string An appropriate auto-summary, or an empty string.
	 */
	public function getAutosummary( Content $oldContent = null, Content $newContent = null,
		$flags ) {
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
				|| $ot->getFragment() != $rt->getFragment()
			) {
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
			&& $newContent->getSize() < 500
		) {
			// Removing more than 90% of the article

			$truncatedtext = $newContent->getTextForSummary(
				200 - strlen( wfMessage( 'autosumm-replace' )->inContentLanguage()->text() ) );

			return wfMessage( 'autosumm-replace' )->rawParams( $truncatedtext )
				->inContentLanguage()->text();
		}

		// New blank article auto-summary
		if ( $flags & EDIT_NEW && $newContent->isEmpty() ) {
			return wfMessage( 'autosumm-newblank' )->inContentLanguage()->text();
		}

		// If we reach this point, there's no applicable auto-summary for our
		// case, so our auto-summary is empty.
		return '';
	}

	/**
	 * Auto-generates a deletion reason
	 *
	 * @since 1.21
	 *
	 * @param Title $title The page's title
	 * @param bool &$hasHistory Whether the page has a history
	 *
	 * @return mixed String containing deletion reason or empty string, or
	 *    boolean false if no revision occurred
	 *
	 * @todo &$hasHistory is extremely ugly, it's here because
	 * WikiPage::getAutoDeleteReason() and Article::generateReason()
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

		// If the page is blank, use the text from the previous revision,
		// which can only be blank if there's a move/import/protect dummy
		// revision involved
		if ( !$content || $content->isEmpty() ) {
			$prev = $rev->getPrevious();

			if ( $prev ) {
				$rev = $prev;
				$content = $rev->getContent();
				$blank = true;
			}
		}

		$this->checkModelID( $rev->getContentModel() );

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
		$text = $content ? $content->getTextForSummary( 255 - ( strlen( $reason ) - 2 ) ) : '';

		// Now replace the '$1' placeholder
		$reason = str_replace( '$1', $text, $reason );

		return $reason;
	}

	/**
	 * Get the Content object that needs to be saved in order to undo all revisions
	 * between $undo and $undoafter. Revisions must belong to the same page,
	 * must exist and must not be deleted.
	 *
	 * @since 1.21
	 *
	 * @param Revision $current The current text
	 * @param Revision $undo The revision to undo
	 * @param Revision $undoafter Must be an earlier revision than $undo
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

		if ( !$undo_content || !$undoafter_content ) {
			return false; // no content to undo
		}

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
	 * Get parser options suitable for rendering and caching the article
	 *
	 * @param IContextSource|User|string $context One of the following:
	 *        - IContextSource: Use the User and the Language of the provided
	 *                                            context
	 *        - User: Use the provided User object and $wgLang for the language,
	 *                                            so use an IContextSource object if possible.
	 *        - 'canonical': Canonical options (anonymous user with default
	 *                                            preferences and content language).
	 *
	 * @throws MWException
	 * @return ParserOptions
	 */
	public function makeParserOptions( $context ) {
		global $wgContLang, $wgEnableParserLimitReporting;

		if ( $context instanceof IContextSource ) {
			$options = ParserOptions::newFromContext( $context );
		} elseif ( $context instanceof User ) { // settings per user (even anons)
			$options = ParserOptions::newFromUser( $context );
		} elseif ( $context === 'canonical' ) { // canonical settings
			$options = ParserOptions::newFromUserAndLang( new User, $wgContLang );
		} else {
			throw new MWException( "Bad context for parser options: $context" );
		}

		$options->enableLimitReport( $wgEnableParserLimitReporting ); // show inclusion/loop reports
		$options->setTidy( true ); // fix bad HTML

		return $options;
	}

	/**
	 * Returns true for content models that support caching using the
	 * ParserCache mechanism. See WikiPage::isParserCacheUsed().
	 *
	 * @since 1.21
	 *
	 * @return bool Always false.
	 */
	public function isParserCacheSupported() {
		return false;
	}

	/**
	 * Returns true if this content model supports sections.
	 * This default implementation returns false.
	 *
	 * Content models that return true here should also implement
	 * Content::getSection, Content::replaceSection, etc. to handle sections..
	 *
	 * @return bool Always false.
	 */
	public function supportsSections() {
		return false;
	}

	/**
	 * Returns true if this content model supports redirects.
	 * This default implementation returns false.
	 *
	 * Content models that return true here should also implement
	 * ContentHandler::makeRedirectContent to return a Content object.
	 *
	 * @return bool Always false.
	 */
	public function supportsRedirects() {
		return false;
	}

	/**
	 * Logs a deprecation warning, visible if $wgDevelopmentWarnings, but only if
	 * self::$enableDeprecationWarnings is set to true.
	 *
	 * @param string $func The name of the deprecated function
	 * @param string $version The version since the method is deprecated. Usually 1.21
	 *   for ContentHandler related stuff.
	 * @param string|bool $component : Component to which the function belongs.
	 *   If false, it is assumed the function is in MediaWiki core.
	 *
	 * @see ContentHandler::$enableDeprecationWarnings
	 * @see wfDeprecated
	 */
	public static function deprecated( $func, $version, $component = false ) {
		if ( self::$enableDeprecationWarnings ) {
			wfDeprecated( $func, $version, $component, 3 );
		}
	}

	/**
	 * Call a legacy hook that uses text instead of Content objects.
	 * Will log a warning when a matching hook function is registered.
	 * If the textual representation of the content is changed by the
	 * hook function, a new Content object is constructed from the new
	 * text.
	 *
	 * @param string $event Event name
	 * @param array $args Parameters passed to hook functions
	 * @param bool $warn Whether to log a warning.
	 *                    Default to self::$enableDeprecationWarnings.
	 *                    May be set to false for testing.
	 *
	 * @return bool True if no handler aborted the hook
	 *
	 * @see ContentHandler::$enableDeprecationWarnings
	 */
	public static function runLegacyHooks( $event, $args = array(),
		$warn = null
	) {

		if ( $warn === null ) {
			$warn = self::$enableDeprecationWarnings;
		}

		if ( !Hooks::isRegistered( $event ) ) {
			return true; // nothing to do here
		}

		if ( $warn ) {
			// Log information about which handlers are registered for the legacy hook,
			// so we can find and fix them.

			$handlers = Hooks::getHandlers( $event );
			$handlerInfo = array();

			wfSuppressWarnings();

			foreach ( $handlers as $handler ) {
				if ( is_array( $handler ) ) {
					if ( is_object( $handler[0] ) ) {
						$info = get_class( $handler[0] );
					} else {
						$info = $handler[0];
					}

					if ( isset( $handler[1] ) ) {
						$info .= '::' . $handler[1];
					}
				} elseif ( is_object( $handler ) ) {
					$info = get_class( $handler[0] );
					$info .= '::on' . $event;
				} else {
					$info = $handler;
				}

				$handlerInfo[] = $info;
			}

			wfRestoreWarnings();

			wfWarn( "Using obsolete hook $event via ContentHandler::runLegacyHooks()! Handlers: " .
				implode( ', ', $handlerInfo ), 2 );
		}

		// convert Content objects to text
		$contentObjects = array();
		$contentTexts = array();

		foreach ( $args as $k => $v ) {
			if ( $v instanceof Content ) {
				/* @var Content $v */

				$contentObjects[$k] = $v;

				$v = $v->serialize();
				$contentTexts[$k] = $v;
				$args[$k] = $v;
			}
		}

		// call the hook functions
		$ok = Hooks::run( $event, $args );

		// see if the hook changed the text
		foreach ( $contentTexts as $k => $orig ) {
			/* @var Content $content */

			$modified = $args[$k];
			$content = $contentObjects[$k];

			if ( $modified !== $orig ) {
				// text was changed, create updated Content object
				$content = $content->getContentHandler()->unserializeContent( $modified );
			}

			$args[$k] = $content;
		}

		return $ok;
	}
}
