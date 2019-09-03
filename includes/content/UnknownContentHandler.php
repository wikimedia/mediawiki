<?php
/**
 * Base content handler class for flat text contents.
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
 * @since 1.34
 *
 * @file
 * @ingroup Content
 */

/**
 * Content handler implementation for unknown content.
 *
 * This can be used to handle content for which no ContentHandler exists on the system,
 * perhaps because the extension that provided it has been removed.
 *
 * @ingroup Content
 */
class UnknownContentHandler extends ContentHandler {

	/**
	 * Constructs an UnknownContentHandler. Since UnknownContentHandler can be registered
	 * for multiple model IDs on a system, multiple instances of UnknownContentHandler may
	 * coexist.
	 *
	 * To preserve the serialization format of the original content model, it must be supplied
	 * to the constructor via the $formats parameter. If not given, the default format is
	 * reported as 'application/octet-stream'.
	 *
	 * @param string $modelId
	 * @param string[]|null $formats
	 */
	public function __construct( $modelId, $formats = null ) {
		parent::__construct(
			$modelId,
			$formats ?? [
				'application/octet-stream',
				'application/unknown',
				'application/x-binary',
				'text/unknown',
				'unknown/unknown',
			]
		);
	}

	/**
	 * Returns the content's data as-is.
	 *
	 * @param Content $content
	 * @param string|null $format The serialization format to check
	 *
	 * @return mixed
	 */
	public function serializeContent( Content $content, $format = null ) {
		/** @var UnknownContent $content */
		'@phan-var UnknownContent $content';
		return $content->getData();
	}

	/**
	 * Constructs an UnknownContent instance wrapping the given data.
	 *
	 * @since 1.21
	 *
	 * @param string $blob serialized content in an unknown format
	 * @param string|null $format ignored
	 *
	 * @return Content The UnknownContent object wrapping $data
	 */
	public function unserializeContent( $blob, $format = null ) {
		return new UnknownContent( $blob, $this->getModelID() );
	}

	/**
	 * Creates an empty UnknownContent object.
	 *
	 * @since 1.21
	 *
	 * @return Content A new UnknownContent object with empty text.
	 */
	public function makeEmptyContent() {
		return $this->unserializeContent( '' );
	}

	/**
	 * @return false
	 */
	public function supportsDirectEditing() {
		return false;
	}

	/**
	 * @param IContextSource $context
	 *
	 * @return SlotDiffRenderer
	 */
	protected function getSlotDiffRendererInternal( IContextSource $context ) {
		return new UnsupportedSlotDiffRenderer( $context );
	}
}
