<?php
/**
 * Renders a slot diff by doing a text diff on the native representation.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup DifferenceEngine
 */

namespace MediaWiki\Diff;

use MediaWiki\Content\Content;
use MediaWiki\Html\Html;
use MediaWiki\Language\MessageLocalizer;

/**
 * Produces a warning message about not being able to render a slot diff.
 *
 * @since 1.34
 *
 * @ingroup DifferenceEngine
 */
class UnsupportedSlotDiffRenderer extends SlotDiffRenderer {

	/**
	 * @var MessageLocalizer
	 */
	private $localizer;

	public function __construct( MessageLocalizer $localizer ) {
		$this->localizer = $localizer;
	}

	/** @inheritDoc */
	public function getDiff( ?Content $oldContent = null, ?Content $newContent = null ) {
		$this->normalizeContents( $oldContent, $newContent );

		$oldModel = $oldContent->getModel();
		$newModel = $newContent->getModel();

		if ( $oldModel !== $newModel ) {
			$msg = $this->localizer->msg( 'unsupported-content-diff2', $oldModel, $newModel );
		} else {
			$msg = $this->localizer->msg( 'unsupported-content-diff', $oldModel );
		}

		return Html::rawElement(
			'tr',
			[],
			Html::rawElement(
				'td',
				[ 'colspan' => 4, 'class' => 'error' ],
				$msg->parse()
			)
		);
	}

}

/** @deprecated class alias since 1.46 */
class_alias( UnsupportedSlotDiffRenderer::class, 'UnsupportedSlotDiffRenderer' );
