<?php
/**
 * Adapter for turning a DifferenceEngine into a SlotDiffRenderer.
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
 * @file
 * @ingroup DifferenceEngine
 */
use MediaWiki\MediaWikiServices;

/**
 * B/C adapter for turning a DifferenceEngine into a SlotDiffRenderer.
 * Before SlotDiffRenderer was introduced, getDiff() functionality was provided by DifferenceEngine
 * subclasses. Convert such a subclass into a SlotDiffRenderer.
 * @deprecated
 * @ingroup DifferenceEngine
 */
class DifferenceEngineSlotDiffRenderer extends SlotDiffRenderer {

	/** @var DifferenceEngine */
	private $differenceEngine;

	public function __construct( DifferenceEngine $differenceEngine ) {
		$this->differenceEngine = clone $differenceEngine;

		// Set state to loaded. This should not matter to any of the methods invoked by
		// the adapter, but just in case a load does get triggered somehow, make sure it's a no-op.
		$fakeContent = MediaWikiServices::getInstance()
				->getContentHandlerFactory()
				->getContentHandler( CONTENT_MODEL_WIKITEXT )
				->makeEmptyContent();
		$this->differenceEngine->setContent( $fakeContent, $fakeContent );

		$this->differenceEngine->markAsSlotDiffRenderer();
	}

	/** @inheritDoc */
	public function getDiff( Content $oldContent = null, Content $newContent = null ) {
		$this->normalizeContents( $oldContent, $newContent );
		return $this->differenceEngine->generateContentDiffBody( $oldContent, $newContent );
	}

	public function addModules( OutputPage $output ) {
		$oldContext = null;
		if ( $output !== $this->differenceEngine->getOutput() ) {
			$oldContext = $this->differenceEngine->getContext();
			$newContext = new DerivativeContext( $oldContext );
			$newContext->setOutput( $output );
			$this->differenceEngine->setContext( $newContext );
		}
		$this->differenceEngine->showDiffStyle();
		if ( $oldContext ) {
			$this->differenceEngine->setContext( $oldContext );
		}
	}

	public function getExtraCacheKeys() {
		return $this->differenceEngine->getExtraCacheKeys();
	}

}
