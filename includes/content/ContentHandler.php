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

use MediaWiki\HookContainer\ProtectedHookAccessorTrait;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Revision\SlotRenderingProvider;
use MediaWiki\Search\ParserOutputSearchDataExtractor;
use Wikimedia\Assert\Assert;

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
 * @stable to extend
 *
 * @ingroup Content
 */
abstract class ContentHandler {
	use ProtectedHookAccessorTrait;

	/**
	 * Convenience function for getting flat text from a Content object. This
	 * should only be used in the context of backwards compatibility with code
	 * that is not yet able to handle Content objects!
	 *
	 * If $content is null, this method returns the empty string.
	 *
	 * If $content is an instance of TextContent, this method returns the flat
	 * text as returned by $content->getText().
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
	 * @param Content|null $content
	 *
	 * @throws MWException If the content is not an instance of TextContent and
	 * wgContentHandlerTextFallback was set to 'fail'.
	 * @return string|null Textual form of the content, if available.
	 */
	public static function getContentText( Content $content = null ) {
		global $wgContentHandlerTextFallback;

		if ( $content === null ) {
			return '';
		}

		if ( $content instanceof TextContent ) {
			return $content->getText();
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
	 * @param Title|null $title The title of the page this text belongs to.
	 *    Required if $modelId is not provided.
	 * @param string|null $modelId The model to deserialize to. If not provided,
	 *    $title->getContentModel() is used.
	 * @param string|null $format The format to use for deserialization. If not
	 *    given, the model's default format is used.
	 *
	 * @throws MWException If model ID or format is not supported or if the text can not be
	 * unserialized using the format.
	 * @throws MWContentSerializationException
	 * @return Content A Content object representing the text.	 *
	 */
	public static function makeContent( $text, Title $title = null,
		$modelId = null, $format = null ) {
		if ( $modelId === null ) {
			if ( $title === null ) {
				throw new MWException( "Must provide a Title object or a content model ID." );
			}

			$modelId = $title->getContentModel();
		}

		return MediaWikiServices::getInstance()
			->getContentHandlerFactory()
			->getContentHandler( $modelId )
			->unserializeContent( $text, $format );
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
	 * @deprecated since 1.33, use SlotRoleHandler::getDefaultModel() together with
	 * SlotRoleRegistry::getRoleHandler().
	 *
	 * @param Title $title
	 *
	 * @return string Default model name for the page given by $title
	 */
	public static function getDefaultModelFor( Title $title ) {
		$slotRoleregistry = MediaWikiServices::getInstance()->getSlotRoleRegistry();
		$mainSlotHandler = $slotRoleregistry->getRoleHandler( 'main' );
		return $mainSlotHandler->getDefaultModel( $title );
	}

	/**
	 * Returns the appropriate ContentHandler singleton for the given title.
	 *
	 * @since 1.21
	 * @deprecated since 1.35, instead use
	 *  ContentHandlerFactory::getContentHandler( $title->getContentModel() ).
	 *
	 * @param Title $title
	 *
	 * @return ContentHandler
	 * @throws MWException
	 * @throws MWUnknownContentModelException
	 */
	public static function getForTitle( Title $title ) {
		return MediaWikiServices::getInstance()
			->getContentHandlerFactory()
			->getContentHandler( $title->getContentModel() );
	}

	/**
	 * Returns the appropriate ContentHandler singleton for the given Content
	 * object.
	 *
	 * @deprecated since 1.35, instead use
	 *  ContentHandlerFactory::getContentHandler( $content->getModel() ).
	 *
	 * @since 1.21
	 *
	 * @param Content $content
	 *
	 * @return ContentHandler
	 * @throws MWException
	 * @throws MWUnknownContentModelException
	 */
	public static function getForContent( Content $content ) {
		return MediaWikiServices::getInstance()
			->getContentHandlerFactory()
			->getContentHandler( $content->getModel() );
	}

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
	 * @deprecated since 1.35, use ContentHandlerFactory::getContentHandler
	 * @see  ContentHandlerFactory::getContentHandler()
	 *
	 * @param string $modelId The ID of the content model for which to get a
	 *    handler. Use CONTENT_MODEL_XXX constants.
	 *
	 * @throws MWException For internal errors and problems in the configuration.
	 * @throws MWUnknownContentModelException If no handler is known for the model ID.
	 * @return ContentHandler The ContentHandler singleton for handling the model given by the ID.
	 */
	public static function getForModelID( $modelId ) {
		return MediaWikiServices::getInstance()
			->getContentHandlerFactory()
			->getContentHandler( $modelId );
	}

	/**
	 * @deprecated since 1.35 Please, use ContentHandlerFactory. Cleanup is not needed
	 * @see ContentHandlerFactory
	 *
	 * Clean up handlers cache.
	 */
	public static function cleanupHandlersCache() {
		// No-op: no longer needed, since the instance cache is in the
		// ContentHandlerFactory service, and services get reset between tests
	}

	/**
	 * Returns the localized name for a given content model.
	 *
	 * Model names are localized using system messages. Message keys
	 * have the form content-model-$name, where $name is getContentModelName( $id ).
	 *
	 * @param string $name The content model ID, as given by a CONTENT_MODEL_XXX
	 *    constant or returned by Revision::getContentModel().
	 * @param Language|null $lang The language to parse the message in (since 1.26)
	 *
	 * @throws MWException If the model ID isn't known.
	 * @return string The content model's localized name.
	 */
	public static function getLocalizedName( $name, Language $lang = null ) {
		// Messages: content-model-wikitext, content-model-text,
		// content-model-javascript, content-model-css
		$key = "content-model-$name";

		$msg = wfMessage( $key );
		if ( $lang ) {
			$msg->inLanguage( $lang );
		}

		return $msg->exists() ? $msg->plain() : $name;
	}

	/**
	 * @deprecated since 1.35, use ContentHandlerFactory::getContentModels
	 * @see ContentHandlerFactory::getContentModels
	 *
	 * @return string[]
	 * @throws MWException
	 * @throws MWUnknownContentModelException
	 */
	public static function getContentModels() {
		return MediaWikiServices::getInstance()->getContentHandlerFactory()->getContentModels();
	}

	/**
	 * @return string[]
	 * @throws MWException
	 * @throws MWUnknownContentModelException
	 *
	 * @deprecated since 1.35, use ContentHandlerFactory::getAllContentFormats
	 * @see ContentHandlerFactory::getAllContentFormats
	 */
	public static function getAllContentFormats() {
		return MediaWikiServices::getInstance()->getContentHandlerFactory()->getAllContentFormats();
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
	 * @stable to call
	 *
	 * @param string $modelId (use CONTENT_MODEL_XXX constants).
	 * @param string[] $formats List for supported serialization formats
	 *    (typically as MIME types)
	 */
	public function __construct( $modelId, $formats ) {
		$this->mModelID = $modelId;
		$this->mSupportedFormats = $formats;
	}

	/**
	 * Serializes a Content object of the type supported by this ContentHandler.
	 *
	 * @stable to override
	 *
	 * @since 1.21
	 *
	 * @param Content $content The Content object to serialize
	 * @param string|null $format The desired serialization format
	 *
	 * @return string Serialized form of the content
	 */
	abstract public function serializeContent( Content $content, $format = null );

	/**
	 * Applies transformations on export (returns the blob unchanged per default).
	 * Subclasses may override this to perform transformations such as conversion
	 * of legacy formats or filtering of internal meta-data.
	 *
	 * @stable to override
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
	 * @stable to override
	 * @since 1.21
	 *
	 * @param string $blob Serialized form of the content
	 * @param string|null $format The format used for serialization
	 *
	 * @return Content The Content object created by deserializing $blob
	 * @throws MWContentSerializationException
	 */
	abstract public function unserializeContent( $blob, $format = null );

	/**
	 * Apply import transformation (per default, returns $blob unchanged).
	 * This gives subclasses an opportunity to transform data blobs on import.
	 *
	 * @stable to override
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
	 * @stable to override
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
	 * @stable to override
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
	 * @stable to override
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
	 * @stable to override
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
	 * @stable to override
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
	 * @stable to override
	 * @since 1.21
	 *
	 * @return array An array mapping action names (typically "view", "edit", "history" etc.) to
	 *  either the full qualified class name of an Action class, a callable taking ( Page $page,
	 *  IContextSource $context = null ) as parameters and returning an Action object, or an actual
	 *  Action object. An empty array in this default implementation.
	 *
	 * @see Action::factory
	 */
	public function getActionOverrides() {
		return [];
	}

	/**
	 * Factory for creating an appropriate DifferenceEngine for this content model.
	 * Since 1.32, this is only used for page-level diffs; to diff two content objects,
	 * use getSlotDiffRenderer.
	 *
	 * The DifferenceEngine subclass to use is selected in getDiffEngineClass(). The
	 * GetDifferenceEngine hook will receive the DifferenceEngine object and can replace or
	 * wrap it.
	 * (Note that in older versions of MediaWiki the hook documentation instructed extensions
	 * to return false from the hook; you should not rely on always being able to decorate
	 * the DifferenceEngine instance from the hook. If the owner of the content type wants to
	 * decorare the instance, overriding this method is a safer approach.)
	 *
	 * @todo This is page-level functionality so it should not belong to ContentHandler.
	 *   Move it to a better place once one exists (e.g. PageTypeHandler).
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
		$rcid = 0, // FIXME: Deprecated, no longer used
		$refreshCache = false, $unhide = false
	) {
		$diffEngineClass = $this->getDiffEngineClass();
		$differenceEngine = new $diffEngineClass( $context, $old, $new, $rcid, $refreshCache, $unhide );
		$this->getHookRunner()->onGetDifferenceEngine(
			$context, $old, $new, $refreshCache, $unhide, $differenceEngine );
		return $differenceEngine;
	}

	/**
	 * Get an appropriate SlotDiffRenderer for this content model.
	 *
	 * @stable to override
	 * @since 1.32
	 *
	 * @param IContextSource $context
	 * @param array $options of the slot diff renderer (optional)
	 * @return SlotDiffRenderer
	 */
	final public function getSlotDiffRenderer( IContextSource $context, array $options = [] ) {
		$slotDiffRenderer = $this->getSlotDiffRendererWithOptions( $context, $options );
		if ( get_class( $slotDiffRenderer ) === TextSlotDiffRenderer::class ) {
			// To keep B/C, when SlotDiffRenderer is not overridden for a given content type
			// but DifferenceEngine is, use that instead.
			$differenceEngine = $this->createDifferenceEngine( $context );
			if ( get_class( $differenceEngine ) !== DifferenceEngine::class ) {
				// TODO turn this into a deprecation warning in a later release
				LoggerFactory::getInstance( 'diff' )->info(
					'Falling back to DifferenceEngineSlotDiffRenderer', [
						'modelID' => $this->getModelID(),
						'DifferenceEngine' => get_class( $differenceEngine ),
					] );
				$slotDiffRenderer = new DifferenceEngineSlotDiffRenderer( $differenceEngine );
			}
		}
		$this->getHookRunner()->onGetSlotDiffRenderer( $this, $slotDiffRenderer, $context );
		return $slotDiffRenderer;
	}

	/**
	 * Return the SlotDiffRenderer appropriate for this content handler.
	 * @deprecated use getSlotDiffRendererWithOptions instead
	 * @param IContextSource $context
	 * @return SlotDiffRenderer|null
	 */
	protected function getSlotDiffRendererInternal( IContextSource $context ) {
		return null;
	}

	/**
	 * Return the SlotDiffRenderer appropriate for this content handler.
	 * @stable to override
	 *
	 * @param IContextSource $context
	 * @param array $options
	 *
	 * @return SlotDiffRenderer
	 */
	protected function getSlotDiffRendererWithOptions( IContextSource $context, $options = [] ) {
		$internalRenderer = $this->getSlotDiffRendererInternal( $context );
		// `getSlotDiffRendererInternal` has been overriden by a class using the deprecated method.
		// Options will not work so exit early!
		if ( $internalRenderer !== null ) {
			return $internalRenderer;
		}

		$contentLanguage = MediaWikiServices::getInstance()->getContentLanguage();
		$statsdDataFactory = MediaWikiServices::getInstance()->getStatsdDataFactory();
		$slotDiffRenderer = new TextSlotDiffRenderer();
		$slotDiffRenderer->setStatsdDataFactory( $statsdDataFactory );
		// XXX using the page language would be better, but it's unclear how that should be injected
		$slotDiffRenderer->setLanguage( $contentLanguage );

		$inline = ( $options['diff-type'] ?? '' ) === 'inline';
		$engine = 'wikidiff2';
		$engine = DifferenceEngine::getEngine();

		if ( $engine === 'php' ) {
			$slotDiffRenderer->setEngine( TextSlotDiffRenderer::ENGINE_PHP );
		} elseif ( $engine === 'wikidiff2' ) {
			if ( $inline ) {
				$slotDiffRenderer->setEngine( TextSlotDiffRenderer::ENGINE_WIKIDIFF2_INLINE );
			} else {
				$slotDiffRenderer->setEngine( TextSlotDiffRenderer::ENGINE_WIKIDIFF2 );
			}
		} else {
			$slotDiffRenderer->setEngine( TextSlotDiffRenderer::ENGINE_EXTERNAL, $engine );
		}

		return $slotDiffRenderer;
	}

	/**
	 * Get the language in which the content of the given page is written.
	 *
	 * This default implementation just returns the content language (except for pages
	 * in the MediaWiki namespace)
	 *
	 * Note that the page's language is not cacheable, since it may in some
	 * cases depend on user settings.
	 *
	 * Also note that the page language may or may not depend on the actual content of the page,
	 * that is, this method may load the content in order to determine the language.
	 *
	 * @stable to override
	 * @since 1.21
	 *
	 * @param Title $title The page to determine the language for.
	 * @param Content|null $content The page's content, if you have it handy, to avoid reloading it.
	 *
	 * @return Language The page's language
	 */
	public function getPageLanguage( Title $title, Content $content = null ) {
		global $wgLang;
		$services = MediaWikiServices::getInstance();
		$pageLang = $services->getContentLanguage();

		if ( $title->inNamespace( NS_MEDIAWIKI ) ) {
			// Parse mediawiki messages with correct target language
			list( /* $unused */, $lang ) = $services->getMessageCache()->figureMessage( $title->getText() );
			$pageLang = $services->getLanguageFactory()->getLanguage( $lang );
		}

		// Simplify hook handlers by only passing objects of one type, in case nothing
		// else has unstubbed the StubUserLang object by now.
		StubObject::unstub( $wgLang );

		$this->getHookRunner()->onPageContentLanguage( $title, $pageLang, $wgLang );

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
	 * @stable to override
	 * @since 1.21
	 *
	 * @param Title $title The page to determine the language for.
	 * @param Content|null $content The page's content, if you have it handy, to avoid reloading it.
	 *
	 * @return Language The page's language for viewing
	 */
	public function getPageViewLanguage( Title $title, Content $content = null ) {
		$pageLang = $this->getPageLanguage( $title, $content );

		if ( $title->getNamespace() !== NS_MEDIAWIKI ) {
			// If the user chooses a variant, the content is actually
			// in a language whose code is the variant code.
			$variant = $this->getLanguageConverter( $pageLang )->getPreferredVariant();
			if ( $pageLang->getCode() !== $variant ) {
				$pageLang = MediaWikiServices::getInstance()->getLanguageFactory()
					->getLanguage( $variant );
			}
		}

		return $pageLang;
	}

	/**
	 * Determines whether the content type handled by this ContentHandler
	 * can be used for the main slot of the given page.
	 *
	 * This default implementation always returns true.
	 * Subclasses may override this to restrict the use of this content model to specific locations,
	 * typically based on the namespace or some other aspect of the title, such as a special suffix
	 * (e.g. ".svg" for SVG content).
	 *
	 * @note this calls the ContentHandlerCanBeUsedOn hook which may be used to override which
	 * content model can be used where.
	 *
	 * @stable to override
	 *
	 * @see SlotRoleHandler::isAllowedModel
	 *
	 * @param Title $title The page's title.
	 *
	 * @return bool True if content of this kind can be used on the given page, false otherwise.
	 */
	public function canBeUsedOn( Title $title ) {
		$ok = true;

		$this->getHookRunner()->onContentModelCanBeUsedOn( $this->getModelID(), $title, $ok );

		return $ok;
	}

	/**
	 * Returns the name of the diff engine to use.
	 *
	 * @stable to override
	 * @since 1.21
	 *
	 * @return string
	 */
	protected function getDiffEngineClass() {
		return DifferenceEngine::class;
	}

	/**
	 * Attempts to merge differences between three versions. Returns a new
	 * Content object for a clean merge and false for failure or a conflict.
	 *
	 * This default implementation always returns false.
	 *
	 * @stable to override
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
	 * Shorthand for getting a Language Converter for specific language
	 * @param Language $language Language of converter
	 * @return ILanguageConverter
	 */
	private function getLanguageConverter( $language ) : ILanguageConverter {
		return MediaWikiServices::getInstance()->getLanguageConverterFactory()
			->getLanguageConverter( $language );
	}

	/**
	 * Return type of change if one exists for the given edit.
	 *
	 * @stable to override
	 * @since 1.31
	 *
	 * @param Content|null $oldContent The previous text of the page.
	 * @param Content|null $newContent The submitted text of the page.
	 * @param int $flags Bit mask: a bit mask of flags submitted for the edit.
	 *
	 * @return string|null String key representing type of change, or null.
	 */
	private function getChangeType(
		Content $oldContent = null,
		Content $newContent = null,
		$flags = 0
	) {
		$oldTarget = $oldContent !== null ? $oldContent->getRedirectTarget() : null;
		$newTarget = $newContent !== null ? $newContent->getRedirectTarget() : null;

		// We check for the type of change in the given edit, and return string key accordingly

		// Blanking of a page
		if ( $oldContent && $oldContent->getSize() > 0 &&
			$newContent && $newContent->getSize() === 0
		) {
			return 'blank';
		}

		// Redirects
		if ( $newTarget ) {
			if ( !$oldTarget ) {
				// New redirect page (by creating new page or by changing content page)
				return 'new-redirect';
			} elseif ( !$newTarget->equals( $oldTarget ) ||
				$oldTarget->getFragment() !== $newTarget->getFragment()
			) {
				// Redirect target changed
				return 'changed-redirect-target';
			}
		} elseif ( $oldTarget ) {
			// Changing an existing redirect into a non-redirect
			return 'removed-redirect';
		}

		// New page created
		if ( $flags & EDIT_NEW && $newContent ) {
			if ( $newContent->getSize() === 0 ) {
				// New blank page
				return 'newblank';
			} else {
				return 'newpage';
			}
		}

		// Removing more than 90% of the page
		if ( $oldContent && $newContent && $oldContent->getSize() > 10 * $newContent->getSize() ) {
			return 'replace';
		}

		// Content model changed
		if ( $oldContent && $newContent && $oldContent->getModel() !== $newContent->getModel() ) {
			return 'contentmodelchange';
		}

		return null;
	}

	/**
	 * Return an applicable auto-summary if one exists for the given edit.
	 *
	 * @stable to override
	 * @since 1.21
	 *
	 * @param Content|null $oldContent The previous text of the page.
	 * @param Content|null $newContent The submitted text of the page.
	 * @param int $flags Bit mask: a bit mask of flags submitted for the edit.
	 *
	 * @return string An appropriate auto-summary, or an empty string.
	 */
	public function getAutosummary(
		Content $oldContent = null,
		Content $newContent = null,
		$flags = 0
	) {
		$changeType = $this->getChangeType( $oldContent, $newContent, $flags );

		// There's no applicable auto-summary for our case, so our auto-summary is empty.
		if ( !$changeType ) {
			return '';
		}

		// Decide what kind of auto-summary is needed.
		switch ( $changeType ) {
			case 'new-redirect':
				$newTarget = $newContent->getRedirectTarget();
				$truncatedtext = $newContent->getTextForSummary(
					250
					- strlen( wfMessage( 'autoredircomment' )->inContentLanguage()->text() )
					- strlen( $newTarget->getFullText() )
				);

				return wfMessage( 'autoredircomment', $newTarget->getFullText() )
					->plaintextParams( $truncatedtext )->inContentLanguage()->text();
			case 'changed-redirect-target':
				$oldTarget = $oldContent->getRedirectTarget();
				$newTarget = $newContent->getRedirectTarget();

				$truncatedtext = $newContent->getTextForSummary(
					250
					- strlen( wfMessage( 'autosumm-changed-redirect-target' )
						->inContentLanguage()->text() )
					- strlen( $oldTarget->getFullText() )
					- strlen( $newTarget->getFullText() )
				);

				return wfMessage( 'autosumm-changed-redirect-target',
						$oldTarget->getFullText(),
						$newTarget->getFullText() )
					->rawParams( $truncatedtext )->inContentLanguage()->text();
			case 'removed-redirect':
				$oldTarget = $oldContent->getRedirectTarget();
				$truncatedtext = $newContent->getTextForSummary(
					250
					- strlen( wfMessage( 'autosumm-removed-redirect' )
						->inContentLanguage()->text() )
					- strlen( $oldTarget->getFullText() ) );

				return wfMessage( 'autosumm-removed-redirect', $oldTarget->getFullText() )
					->rawParams( $truncatedtext )->inContentLanguage()->text();
			case 'newpage':
				// If they're making a new article, give its text, truncated, in the summary.
				$truncatedtext = $newContent->getTextForSummary(
					200 - strlen( wfMessage( 'autosumm-new' )->inContentLanguage()->text() ) );

				return wfMessage( 'autosumm-new' )->rawParams( $truncatedtext )
					->inContentLanguage()->text();
			case 'blank':
				return wfMessage( 'autosumm-blank' )->inContentLanguage()->text();
			case 'replace':
				$truncatedtext = $newContent->getTextForSummary(
					200 - strlen( wfMessage( 'autosumm-replace' )->inContentLanguage()->text() ) );

				return wfMessage( 'autosumm-replace' )->rawParams( $truncatedtext )
					->inContentLanguage()->text();
			case 'newblank':
				return wfMessage( 'autosumm-newblank' )->inContentLanguage()->text();
			default:
				return '';
		}
	}

	/**
	 * Return an applicable tag if one exists for the given edit or return null.
	 *
	 * @stable to override
	 * @since 1.31
	 *
	 * @param Content|null $oldContent The previous text of the page.
	 * @param Content|null $newContent The submitted text of the page.
	 * @param int $flags Bit mask: a bit mask of flags submitted for the edit.
	 *
	 * @return string|null An appropriate tag, or null.
	 */
	public function getChangeTag(
		Content $oldContent = null,
		Content $newContent = null,
		$flags = 0
	) {
		$changeType = $this->getChangeType( $oldContent, $newContent, $flags );

		// There's no applicable tag for this change.
		if ( !$changeType ) {
			return null;
		}

		// Core tags use the same keys as ones returned from $this->getChangeType()
		// but prefixed with pseudo namespace 'mw-', so we add the prefix before checking
		// if this type of change should be tagged
		$tag = 'mw-' . $changeType;

		// Not all change types are tagged, so we check against the list of defined tags.
		if ( in_array( $tag, ChangeTags::getSoftwareTags() ) ) {
			return $tag;
		}

		return null;
	}

	/**
	 * Auto-generates a deletion reason
	 *
	 * @stable to override
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
		$dbr = wfGetDB( DB_REPLICA );
		$revLookup = MediaWikiServices::getInstance()->getRevisionLookup();

		// Get the last revision
		$revRecord = $revLookup->getRevisionByTitle( $title );

		if ( $revRecord === null ) {
			return false;
		}

		// Get the article's contents
		$content = $revRecord->getContent( SlotRecord::MAIN );
		$blank = false;

		// If the page is blank, use the text from the previous revision,
		// which can only be blank if there's a move/import/protect dummy
		// revision involved
		if ( !$content || $content->isEmpty() ) {
			$prev = $revLookup->getPreviousRevision( $revRecord );

			if ( $prev ) {
				$revRecord = $prev;
				$content = $prev->getContent( SlotRecord::MAIN );
				$blank = true;
			}
		}

		$this->checkModelID( $revRecord->getSlot( SlotRecord::MAIN )->getModel() );

		// Find out if there was only one contributor
		// Only scan the last 20 revisions
		$revQuery = MediaWikiServices::getInstance()->getRevisionStore()->getQueryInfo();
		$res = $dbr->select(
			$revQuery['tables'],
			[ 'rev_user_text' => $revQuery['fields']['rev_user_text'] ],
			[
				'rev_page' => $title->getArticleID(),
				$dbr->bitAnd( 'rev_deleted', RevisionRecord::DELETED_USER ) . ' = 0'
			],
			__METHOD__,
			[ 'LIMIT' => 20 ],
			$revQuery['joins']
		);

		if ( $res === false ) {
			// This page has no revisions, which is very weird
			return false;
		}

		$hasHistory = ( $res->numRows() > 1 );
		$row = $dbr->fetchObject( $res );

		if ( $row ) { // $row is false if the only contributor is hidden
			$onlyAuthor = $row->rev_user_text;
			// Try to find a second contributor
			foreach ( $res as $row ) {
				if ( $row->rev_user_text != $onlyAuthor ) { // T24999
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
		$maxLength = CommentStore::COMMENT_CHARACTER_LIMIT - ( strlen( $reason ) - 2 );
		$text = $content ? $content->getTextForSummary( $maxLength ) : '';

		// Now replace the '$1' placeholder
		$reason = str_replace( '$1', $text, $reason );

		return $reason;
	}

	/**
	 * Get the Content object that needs to be saved in order to undo all revisions
	 * between $undo and $undoafter. Revisions must belong to the same page,
	 * must exist and must not be deleted.
	 *
	 * @stable to override
	 * @since 1.21
	 * @since 1.32 accepts Content objects for all parameters instead of Revision objects.
	 *  Passing Revision objects is deprecated.
	 *
	 * @param Revision|Content $current The current text (passing a Revision is hard
	 *    deprecated since 1.35)
	 * @param Revision|Content $undo The content of the revision to undo (passing a Revision is
	 *    hard deprecated since 1.35)
	 * @param Revision|Content $undoafter Must be from an earlier revision than $undo (passing a
	 *     Revision is hard deprecated since 1.35)
	 * @param bool $undoIsLatest Set true if $undo is from the current revision (since 1.32)
	 *
	 * @return Content|false Content on success, false on failure
	 */
	public function getUndoContent( $current, $undo, $undoafter, $undoIsLatest = false ) {
		Assert::parameterType( Revision::class . '|' . Content::class, $current, '$current' );
		if ( $current instanceof Content ) {
			Assert::parameter( $undo instanceof Content, '$undo',
				'Must be Content when $current is Content' );
			Assert::parameter( $undoafter instanceof Content, '$undoafter',
				'Must be Content when $current is Content' );
			$cur_content = $current;
			$undo_content = $undo;
			$undoafter_content = $undoafter;
		} else {
			Assert::parameter( $undo instanceof Revision, '$undo',
				'Must be Revision when $current is Revision' );
			Assert::parameter( $undoafter instanceof Revision, '$undoafter',
				'Must be Revision when $current is Revision' );

			wfDeprecated( __METHOD__ . ' with Revision objects', '1.32' );

			$cur_content = $current->getContent();

			if ( empty( $cur_content ) ) {
				return false; // no page
			}

			$undo_content = $undo->getContent();
			$undoafter_content = $undoafter->getContent();

			if ( !$undo_content || !$undoafter_content ) {
				return false; // no content to undo
			}

			$undoIsLatest = $current->getId() === $undo->getId();
		}

		try {
			$this->checkModelID( $cur_content->getModel() );
			$this->checkModelID( $undo_content->getModel() );
			if ( !$undoIsLatest ) {
				// If we are undoing the most recent revision,
				// its ok to revert content model changes. However
				// if we are undoing a revision in the middle, then
				// doing that will be confusing.
				$this->checkModelID( $undoafter_content->getModel() );
			}
		} catch ( MWException $e ) {
			// If the revisions have different content models
			// just return false
			return false;
		}

		if ( $cur_content->equals( $undo_content ) ) {
			// No use doing a merge if it's just a straight revert.
			return $undoafter_content;
		}

		$undone_content = $this->merge3( $undo_content, $undoafter_content, $cur_content );

		return $undone_content;
	}

	/**
	 * Returns true for content models that support caching using the
	 * ParserCache mechanism. See WikiPage::shouldCheckParserCache().
	 *
	 * @stable to override
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
	 * @stable to override
	 *
	 * @return bool Always false.
	 */
	public function supportsSections() {
		return false;
	}

	/**
	 * Returns true if this content model supports categories.
	 * The default implementation returns true.
	 *
	 * @stable to override
	 *
	 * @return bool Always true.
	 */
	public function supportsCategories() {
		return true;
	}

	/**
	 * Returns true if this content model supports redirects.
	 * This default implementation returns false.
	 *
	 * Content models that return true here should also implement
	 * ContentHandler::makeRedirectContent to return a Content object.
	 *
	 * @stable to override
	 *
	 * @return bool Always false.
	 */
	public function supportsRedirects() {
		return false;
	}

	/**
	 * Return true if this content model supports direct editing, such as via EditPage.
	 *
	 * @stable to override
	 *
	 * @return bool Default is false, and true for TextContent and it's derivatives.
	 */
	public function supportsDirectEditing() {
		return false;
	}

	/**
	 * Whether or not this content model supports direct editing via ApiEditPage
	 *
	 * @stable to override
	 *
	 * @return bool Default is false, and true for TextContent and derivatives.
	 */
	public function supportsDirectApiEditing() {
		return $this->supportsDirectEditing();
	}

	/**
	 * Get fields definition for search index
	 *
	 * @todo Expose title, redirect, namespace, text, source_text, text_bytes
	 *       field mappings here. (see T142670 and T143409)
	 *
	 * @stable to override
	 *
	 * @param SearchEngine $engine
	 * @return SearchIndexField[] List of fields this content handler can provide.
	 * @since 1.28
	 */
	public function getFieldsForSearchIndex( SearchEngine $engine ) {
		$fields = [];
		$fields['category'] = $engine->makeSearchFieldMapping(
			'category',
			SearchIndexField::INDEX_TYPE_TEXT
		);
		$fields['category']->setFlag( SearchIndexField::FLAG_CASEFOLD );

		$fields['external_link'] = $engine->makeSearchFieldMapping(
			'external_link',
			SearchIndexField::INDEX_TYPE_KEYWORD
		);

		$fields['outgoing_link'] = $engine->makeSearchFieldMapping(
			'outgoing_link',
			SearchIndexField::INDEX_TYPE_KEYWORD
		);

		$fields['template'] = $engine->makeSearchFieldMapping(
			'template',
			SearchIndexField::INDEX_TYPE_KEYWORD
		);
		$fields['template']->setFlag( SearchIndexField::FLAG_CASEFOLD );

		$fields['content_model'] = $engine->makeSearchFieldMapping(
			'content_model',
			SearchIndexField::INDEX_TYPE_KEYWORD
		);

		return $fields;
	}

	/**
	 * Add new field definition to array.
	 * @param SearchIndexField[] &$fields
	 * @param SearchEngine $engine
	 * @param string $name
	 * @param int $type
	 * @return SearchIndexField[] new field defs
	 * @since 1.28
	 */
	protected function addSearchField( &$fields, SearchEngine $engine, $name, $type ) {
		$fields[$name] = $engine->makeSearchFieldMapping( $name, $type );
		return $fields;
	}

	/**
	 * Return fields to be indexed by search engine
	 * as representation of this document.
	 * Overriding class should call parent function or take care of calling
	 * the SearchDataForIndex hook.
	 * @stable to override
	 *
	 * @param WikiPage $page Page to index
	 * @param ParserOutput $output
	 * @param SearchEngine $engine Search engine for which we are indexing
	 * @return array Map of name=>value for fields
	 * @since 1.28
	 */
	public function getDataForSearchIndex(
		WikiPage $page,
		ParserOutput $output,
		SearchEngine $engine
	) {
		$fieldData = [];
		$content = $page->getContent();

		if ( $content ) {
			$searchDataExtractor = new ParserOutputSearchDataExtractor();

			$fieldData['category'] = $searchDataExtractor->getCategories( $output );
			$fieldData['external_link'] = $searchDataExtractor->getExternalLinks( $output );
			$fieldData['outgoing_link'] = $searchDataExtractor->getOutgoingLinks( $output );
			$fieldData['template'] = $searchDataExtractor->getTemplates( $output );

			$text = $content->getTextForSearchIndex();

			$fieldData['text'] = $text;
			$fieldData['source_text'] = $text;
			$fieldData['text_bytes'] = $content->getSize();
			$fieldData['content_model'] = $content->getModel();
		}

		$this->getHookRunner()->onSearchDataForIndex( $fieldData, $this, $page, $output, $engine );
		return $fieldData;
	}

	/**
	 * Produce page output suitable for indexing.
	 *
	 * Specific content handlers may override it if they need different content handling.
	 *
	 * @stable to override
	 *
	 * @param WikiPage $page
	 * @param ParserCache|null $cache
	 * @return ParserOutput
	 */
	public function getParserOutputForIndexing( WikiPage $page, ParserCache $cache = null ) {
		// TODO: MCR: ContentHandler should be called per slot, not for the whole page.
		// See T190066.
		$parserOptions = $page->makeParserOptions( 'canonical' );
		if ( $cache ) {
			$parserOutput = $cache->get( $page, $parserOptions );
		}

		if ( empty( $parserOutput ) ) {
			$renderer = MediaWikiServices::getInstance()->getRevisionRenderer();
			$revisionRecord = $this->latestRevision( $page );
			$parserOutput =
				$renderer->getRenderedRevision(
					$revisionRecord,
					$parserOptions
				)->getRevisionParserOutput();
			if ( $cache ) {
				$cache->save( $parserOutput, $page, $parserOptions );
			}
		}
		return $parserOutput;
	}

	private function latestRevision( WikiPage $page ): RevisionRecord {
		$revRecord = $page->getRevisionRecord();
		if ( $revRecord == null ) {
			// If the content represents a brand new page it's possible
			// we need to fetch it from the master.
			$page->loadPageData( WikiPage::READ_LATEST );
			$revRecord = $page->getRevisionRecord();
			if ( $revRecord == null ) {
				$text = $page->getTitle()->getPrefixedText();
				throw new MWException(
					"No revision could be loaded for page: $text" );
			}
		}

		return $revRecord;
	}

	/**
	 * Returns a list of DeferrableUpdate objects for recording information about the
	 * given Content in some secondary data store.
	 *
	 * Application logic should not call this method directly. Instead, it should call
	 * DerivedPageDataUpdater::getSecondaryDataUpdates().
	 *
	 * @note Implementations must not return a LinksUpdate instance. Instead, a LinksUpdate
	 * is created by the calling code in DerivedPageDataUpdater, on the combined ParserOutput
	 * of all slots, not for each slot individually. This is in contrast to the old
	 * getSecondaryDataUpdates method defined by AbstractContent, which returned a LinksUpdate.
	 *
	 * @note Implementations should not call $content->getParserOutput, they should call
	 * $slotOutput->getSlotRendering( $role, false ) instead if they need to access a ParserOutput
	 * of $content. This allows existing ParserOutput objects to be re-used, while avoiding
	 * creating a ParserOutput when none is needed.
	 *
	 * @stable to override
	 *
	 * @param Title $title The title of the page to supply the updates for
	 * @param Content $content The content to generate data updates for.
	 * @param string $role The role (slot) in which the content is being used. Which updates
	 *        are performed should generally not depend on the role the content has, but the
	 *        DeferrableUpdates themselves may need to know the role, to track to which slot the
	 *        data refers, and to avoid overwriting data of the same kind from another slot.
	 * @param SlotRenderingProvider $slotOutput A provider that can be used to gain access to
	 *        a ParserOutput of $content by calling $slotOutput->getSlotParserOutput( $role, false ).
	 * @return DeferrableUpdate[] A list of DeferrableUpdate objects for putting information
	 *        about this content object somewhere. The default implementation returns an empty
	 *        array.
	 * @since 1.32
	 */
	public function getSecondaryDataUpdates(
		Title $title,
		Content $content,
		$role,
		SlotRenderingProvider $slotOutput
	) {
		return [];
	}

	/**
	 * Returns a list of DeferrableUpdate objects for removing information about content
	 * in some secondary data store. This is used when a page is deleted, and also when
	 * a slot is removed from a page.
	 *
	 * Application logic should not call this method directly. Instead, it should call
	 * WikiPage::getSecondaryDataUpdates().
	 *
	 * @note Implementations must not return a LinksDeletionUpdate instance. Instead, a
	 * LinksDeletionUpdate is created by the calling code in WikiPage.
	 * This is in contrast to the old getDeletionUpdates method defined by AbstractContent,
	 * which returned a LinksUpdate.
	 *
	 * @note Implementations should not rely on the page's current content, but rather the current
	 * state of the secondary data store.
	 *
	 * @stable to override
	 *
	 * @param Title $title The title of the page to supply the updates for
	 * @param string $role The role (slot) in which the content is being used. Which updates
	 *        are performed should generally not depend on the role the content has, but the
	 *        DeferrableUpdates themselves may need to know the role, to track to which slot the
	 *        data refers, and to avoid overwriting data of the same kind from another slot.
	 *
	 * @return DeferrableUpdate[] A list of DeferrableUpdate objects for putting information
	 *        about this content object somewhere. The default implementation returns an empty
	 *        array.
	 *
	 * @since 1.32
	 */
	public function getDeletionUpdates( Title $title, $role ) {
		return [];
	}

}
