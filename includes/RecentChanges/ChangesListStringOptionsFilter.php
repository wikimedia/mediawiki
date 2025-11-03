<?php

namespace MediaWiki\RecentChanges;

use MediaWiki\Html\FormOptions;

/**
 * An individual filter in a ChangesListStringOptionsFilterGroup.
 *
 * This filter type will only be displayed on the structured UI currently.
 *
 * @since 1.29
 * @ingroup RecentChanges
 */
class ChangesListStringOptionsFilter extends ChangesListFilter {
	/**
	 * @inheritDoc
	 */
	public function displaysOnUnstructuredUi() {
		return false;
	}

	/**
	 * @inheritDoc
	 */
	public function isSelected( FormOptions $opts ) {
		$option = $opts[ $this->getGroup()->getName() ];
		if ( $option === ChangesListStringOptionsFilterGroup::ALL ) {
			return true;
		}

		$values = explode( ChangesListStringOptionsFilterGroup::SEPARATOR, $option );
		return in_array( $this->getName(), $values );
	}

	/** @inheritDoc */
	public function isActive( FormOptions $opts, $isStructuredUI ) {
		// STRING_OPTIONS filter groups are exclusively active on Structured UI
		return $isStructuredUI && $this->isSelected( $opts );
	}
}

/** @deprecated class alias since 1.44 */
class_alias( ChangesListStringOptionsFilter::class, 'ChangesListStringOptionsFilter' );
