<?php

/**
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
 * @file
 * @ingroup Content
 *
 * @author Art Baltai
 */

namespace MediaWiki\Content;

use ContentHandler;
use FatalError;
use InvalidArgumentException;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MWException;
use MWUnknownContentModelException;
use Psr\Log\LoggerInterface;
use UnexpectedValueException;
use Wikimedia\ObjectFactory;

/**
 * Class ContentHandlerFactory
 * @package MediaWiki\Content
 * @ingroup Content
 * @since 1.35
 */
final class ContentHandlerFactory implements IContentHandlerFactory {

	/**
	 * @var string[]|callable[]
	 */
	private $handlerSpecs = [];

	/**
	 * @var ContentHandler[] Registry of ContentHandler instances by model id
	 */
	private $handlersByModel = [];

	/** @var ObjectFactory */
	private $objectFactory;

	/** @var HookRunner */
	private $hookRunner;

	/** @var LoggerInterface */
	private $logger;

	/**
	 * @since 1.35
	 * @internal Use @see MediaWikiServices::getContentHandlerFactory
	 *
	 * @param string[]|callable[] $handlerSpecs An associative array mapping each known
	 *   content model to the ObjectFactory spec used to construct its ContentHandler.
	 *   This array typically comes from $wgContentHandlers.
	 * @param ObjectFactory $objectFactory
	 * @param HookContainer $hookContainer
	 * @param LoggerInterface $logger
	 */
	public function __construct(
		array $handlerSpecs,
		ObjectFactory $objectFactory,
		HookContainer $hookContainer,
		LoggerInterface $logger
	) {
		$this->handlerSpecs = $handlerSpecs;
		$this->objectFactory = $objectFactory;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->logger = $logger;
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

			$this->logger->info(
				"Registered handler for {$modelID}: " . get_class( $contentHandler )
			);
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
		$modelsFromHook = [];
		$this->hookRunner->onGetContentModels( $modelsFromHook );
		$models = array_merge( // auto-registered from config and MediaServiceWiki or manual
			array_keys( $this->handlerSpecs ),

			// incorrect registered and called: without HOOK_NAME_GET_CONTENT_MODELS
			array_keys( $this->handlersByModel ),

			// correct registered: as HOOK_NAME_GET_CONTENT_MODELS
			$modelsFromHook );

		return array_unique( $models );
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
		try {
			/**
			 * @var ContentHandler $contentHandler
			 */
			$contentHandler = $this->objectFactory->createObject( $handlerSpec,
				[
					'assertClass' => ContentHandler::class,
					'allowCallable' => true,
					'allowClassName' => true,
					'extraArgs' => [ $modelID ],
				] );
		}
		catch ( InvalidArgumentException $e ) {
			// legacy support
			throw new MWException( "Wrong Argument HandlerSpec for ModelID: {$modelID}. " .
				"Error: {$e->getMessage()}" );
		}
		catch ( UnexpectedValueException $e ) {
			// legacy support
			throw new MWException( "Wrong HandlerSpec class for ModelID: {$modelID}. " .
				"Error: {$e->getMessage()}" );
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
		$this->hookRunner->onContentHandlerForModelID( $modelID, $contentHandler );
		$this->validateContentHandler( $modelID, $contentHandler );

		'@phan-var ContentHandler $contentHandler';

		return $contentHandler;
	}
}
