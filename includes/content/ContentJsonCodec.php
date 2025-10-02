<?php
declare( strict_types = 1 );
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Content;

use Wikimedia\JsonCodec\JsonClassCodec;

/**
 * ContentJsonCodec handles serialization of Content objects to/from
 * JSON using methods of the appropriate ContentHandler.
 *
 * @implements JsonClassCodec<Content>
 * @internal
 */
class ContentJsonCodec implements JsonClassCodec {

	public function __construct(
		private IContentHandlerFactory $contentHandlerFactory
	) {
	}

	/** @inheritDoc */
	public function toJsonArray( $obj ): array {
		// To serialize content we need a handler.
		$model = $obj->getModel();
		$handler = $this->contentHandlerFactory->getContentHandler(
			$model
		);
		// @phan-suppress-next-line PhanTypeMismatchArgument -- Phan doesn't support generic interfaces
		return [ 'model' => $model ] + $handler->serializeContentToJsonArray( $obj );
	}

	/** @inheritDoc */
	public function newFromJsonArray( string $className, array $json ) {
		// To deserialize content we need a handler.
		$model = $json['model'];
		$handler = $this->contentHandlerFactory->getContentHandler(
			$model
		);
		$content = $handler->deserializeContentFromJsonArray( $json );
		// @phan-suppress-next-line PhanTypeMismatchReturn -- Phan doesn't support generic interfaces
		return $content;
	}

	/** @inheritDoc */
	public function jsonClassHintFor( string $className, string $keyName ) {
		return null;
	}
}
