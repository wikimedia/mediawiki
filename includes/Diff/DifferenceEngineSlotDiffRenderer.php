<?php
/**
 * Adapter for turning a DifferenceEngine into a SlotDiffRenderer.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup DifferenceEngine
 */

namespace MediaWiki\Diff;

use MediaWiki\Content\Content;
use MediaWiki\Context\DerivativeContext;
use MediaWiki\MediaWikiServices;
use MediaWiki\Output\OutputPage;

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
	public function getDiff( ?Content $oldContent = null, ?Content $newContent = null ) {
		$this->normalizeContents( $oldContent, $newContent );
		return $this->differenceEngine->generateContentDiffBody( $oldContent, $newContent );
	}

	/** @inheritDoc */
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

	/** @inheritDoc */
	public function getExtraCacheKeys() {
		return $this->differenceEngine->getExtraCacheKeys();
	}

}

/** @deprecated class alias since 1.46 */
class_alias( DifferenceEngineSlotDiffRenderer::class, 'DifferenceEngineSlotDiffRenderer' );
