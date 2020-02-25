<?php

namespace MediaWiki\Content;

use ContentHandler;
use FatalError;
use MWException;
use MWUnknownContentModelException;

interface IContentHandlerFactory {

	/**
	 * Returns a ContentHandler instance for the given $modelID.
	 *
	 * @param string $modelID
	 *
	 * @return ContentHandler
	 * @throws MWException For internal errors and problems in the configuration.
	 * @throws MWUnknownContentModelException If no handler is known for the model ID.
	 */
	public function getContentHandler( string $modelID ): ContentHandler;

	/**
	 * Returns a list of defined content models.
	 * getContentHandler() can be expected to return a ContentHandler for the models returned
	 * by this method.
	 *
	 * @return string[]
	 * @throws MWException
	 * @throws FatalError
	 */
	public function getContentModels(): array;

	/**
	 * Returns a list of all serialization formats supported for any of the defined content models.
	 * @see ContentHandler::getSupportedFormats()
	 * @return string[]
	 * @throws MWException
	 */
	public function getAllContentFormats(): array;

	/**
	 * Returns true if $modelID is a defined content model for which getContentHandler() can be
	 * expected to return a ContentHandler instance.
	 * @param string $modelID
	 *
	 * @return bool
	 */
	public function isDefinedModel( string $modelID ): bool;
}
