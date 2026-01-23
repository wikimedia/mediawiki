<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials\Forms;

use MediaWiki\HTMLForm\OOUIHTMLForm;

/**
 * Extend OOUIHTMLForm purely so we can have a more sensible way of getting the section headers
 */
class EditWatchlistNormalHTMLForm extends OOUIHTMLForm {
	/** @inheritDoc */
	public function getLegend( $namespace ) {
		$namespace = (int)substr( $namespace, 2 );
		return $namespace == NS_MAIN
			? $this->msg( 'blanknamespace' )->text()
			: $this->getContext()->getLanguage()->getFormattedNsText( $namespace );
	}

	/** @inheritDoc */
	public function displaySection(
		$fields, $sectionName = '', $fieldsetIDPrefix = '', &$hasUserVisibleFields = false
	) {
		return parent::displaySection( $fields, $sectionName, 'editwatchlist-', $hasUserVisibleFields );
	}
}

/** @deprecated class alias since 1.46 */
class_alias( EditWatchlistNormalHTMLForm::class, 'EditWatchlistNormalHTMLForm' );
