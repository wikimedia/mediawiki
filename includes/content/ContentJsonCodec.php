<?php
declare( strict_types = 1 );
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
 */

namespace MediaWiki\Content;

use Wikimedia\JsonCodec\JsonClassCodec;

/**
 * ContentJsonCodec handles serialization of Content objects to/from
 * JSON using methods of the appropriate ContentHandler.
 *
 * @template-implements JsonClassCodec<Content>
 * @internal
 */
class ContentJsonCodec implements JsonClassCodec {

	public function __construct(
		private IContentHandlerFactory $contentHandlerFactory
	) {
	}

	/** @inheritDoc */
	public function toJsonArray( $content ): array {
		'@phan-var Content $content'; /** @var Content $content */
		// To serialize content we need a handler.
		$model = $content->getModel();
		$handler = $this->contentHandlerFactory->getContentHandler(
			$model
		);
		return [ 'model' => $model ] +
			$handler->serializeContentToJsonArray( $content );
	}

	/** @inheritDoc */
	public function newFromJsonArray( string $className, array $json ) {
		// To deserialize content we need a handler.
		$model = $json['model'];
		$handler = $this->contentHandlerFactory->getContentHandler(
			$model
		);
		$content = $handler->deserializeContentFromJsonArray( $json );
		// phan's support for generics is broken :(
		// @phan-suppress-next-line PhanTypeMismatchReturn
		return $content;
	}

	/** @inheritDoc */
	public function jsonClassHintFor( string $className, string $keyName ) {
		return null;
	}
}
