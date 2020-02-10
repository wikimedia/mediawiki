<?php

namespace MediaWiki\Content;

use ContentHandler;
use FatalError;
use Hooks;
use MWException;
use MWUnknownContentModelException;

final class ContentHandlerFactory implements IContentHandlerFactory {

	/**
	 * @var string[]|callable[]
	 */
	private $handlerSpecs = [];

	/**
	 * @var ContentHandler[] Registry of ContentHandler instances by model id
	 */
	private $handlersByModel = [];

	/**
	 * ContentHandlerFactory constructor.
	 *
	 * @param string[]|callable[] $handlerSpecs ClassName for resolve or Callable resolver
	 *
	 * @see \$wgContentHandlers
	 */
	public function __construct( array $handlerSpecs ) {
		$this->handlerSpecs = $handlerSpecs;
	}

	/**
	 * @param string $modelID
	 *
	 * @return ContentHandler
	 * @throws MWException For internal errors and problems in the configuration.
	 * @throws MWUnknownContentModelException If no handler is known for the model ID.
	 */
	public function getContentHandler( string $modelID ): ContentHandler {
		if ( empty( $this->handlersByModel[$modelID] ) ) {
			$contentHandler = $this->createForModelID( $modelID );

			wfDebugLog( __METHOD__,
				"Registered handler for {$modelID}: " . get_class( $contentHandler ) );
			$this->handlersByModel[$modelID] = $contentHandler;
		}

		return $this->handlersByModel[$modelID];
	}

	/**
	 * Define HandlerSpec for ModelID.
	 * @param string $modelID
	 * @param callable|string $handlerSpec
	 *
	 * @throws MWException
	 * @internal
	 *
	 */
	public function defineContentHandler( string $modelID, $handlerSpec ): void {
		if ( !is_callable( $handlerSpec ) && !is_string( $handlerSpec ) ) {
			throw new MWException(
				"ContentHandler Spec for modelID '{$modelID}' must be callable or class name"
			);
		}
		unset( $this->handlersByModel[$modelID] );
		$this->handlerSpecs[$modelID] = $handlerSpec;
	}

	/**
	 * Get defined ModelIDs
	 *
	 * @return string[]
	 * @throws MWException
	 * @throws FatalError
	 */
	public function getContentModels(): array {
		$models = array_keys( $this->handlerSpecs );
		Hooks::run( self::HOOK_NAME_GET_CONTENT_MODELS, [ &$models ] );

		return $models;
	}

	/**
	 * @return string[]
	 * @throws MWException
	 */
	public function getAllContentFormats(): array {
		$formats = [];
		foreach ( $this->handlerSpecs as $model => $class ) {
			$formats += array_flip( $this->getContentHandler( $model )->getSupportedFormats() );
		}

		return array_keys( $formats );
	}

	/**
	 * @param string $modelID
	 *
	 * @return bool
	 * @throws MWException
	 */
	public function isDefinedModel( string $modelID ): bool {
		return in_array( $modelID, $this->getContentModels(), true );
	}

	/**
	 * Register ContentHandler for ModelID
	 *
	 * @param string $modelID
	 * @param ContentHandler $contentHandler
	 */
	private function registerForModelID( string $modelID, ContentHandler $contentHandler ): void {
		wfDebugLog(
			__METHOD__,
			"Registered handler for {$modelID}: " . get_class( $contentHandler )
			. ( !empty( $this->handlersByModel[$modelID] ) ? ' (replace old)' : null )
		);
		$this->handlersByModel[$modelID] = $contentHandler;
	}

	/**
	 * Create ContentHandler for ModelID
	 *
	 * @param string $modelID The ID of the content model for which to get a handler.
	 * Use CONTENT_MODEL_XXX constants.
	 *
	 * @return ContentHandler The ContentHandler singleton for handling the model given by the ID.
	 *
	 * @throws MWUnknownContentModelException If no handler is known for the model ID.
	 * @throws MWException For internal errors and problems in the configuration.
	 */
	private function createForModelID( string $modelID ): ContentHandler {
		$handlerSpec = $this->handlerSpecs[$modelID] ?? null;
		if ( $handlerSpec !== null ) {
			return $this->createContentHandlerFromHandlerSpec( $modelID, $handlerSpec );
		}

		return $this->createContentHandlerFromHook( $modelID );
	}

	/**
	 * @param string $modelID
	 * @param ContentHandler $contentHandler
	 *
	 * @throws MWException
	 * @throws MWUnknownContentModelException
	 */
	private function validateContentHandler( string $modelID, $contentHandler ): void {
		if ( $contentHandler === null ) {
			throw new MWUnknownContentModelException( $modelID );
		}

		if ( !is_object( $contentHandler ) ) {
			throw new MWException(
				"ContentHandler for model {$modelID} wrong: non-object given."
			);
		}

		if ( !$contentHandler instanceof ContentHandler ) {
			throw new MWException(
				"ContentHandler for model {$modelID} must supply a ContentHandler instance, "
				 . get_class( $contentHandler ) . 'given.'
			);
		}
	}

	/**
	 * @param string $modelID
	 * @param callable|string $handlerSpec
	 *
	 * @return ContentHandler
	 * @throws MWException
	 * @throws MWUnknownContentModelException
	 */
	private function createContentHandlerFromHandlerSpec(
		string $modelID, $handlerSpec
	): ContentHandler {
		$contentHandler = null;

		if ( is_string( $handlerSpec ) ) {
			$contentHandler = new $handlerSpec( $modelID );
		} elseif ( is_callable( $handlerSpec ) ) {
			$contentHandler = call_user_func( $handlerSpec, $modelID );
		} else {
			throw new MWException( "Wrong Argument HandlerSpec for ModelID: {$modelID}." );
		}

		$this->validateContentHandler( $modelID, $contentHandler );

		return $contentHandler;
	}

	/**
	 * @param string $modelID
	 *
	 * @return ContentHandler
	 * @throws MWException
	 * @throws MWUnknownContentModelException
	 */
	private function createContentHandlerFromHook( string $modelID ): ContentHandler {
		$contentHandler = null;
		Hooks::run( self::HOOK_NAME_BY_MODEL_NAME, [ $modelID, &$contentHandler ] );
		$this->validateContentHandler( $modelID, $contentHandler );

		'@phan-var ContentHandler $contentHandler';

		return $contentHandler;
	}
}
