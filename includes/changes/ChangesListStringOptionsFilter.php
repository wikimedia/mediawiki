<?php

/**
 * An individual filter in a ChangesListStringOptionsFilterGroup.
 *
 * This filter type will only be displayed on the structured UI currently.
 *
 * @since 1.29
 */
class ChangesListStringOptionsFilter extends ChangesListFilter {
	/**
	 * @inheritdoc
	 */
	public function displaysOnUnstructuredUi() {
		return false;
	}

	/**
	 * @inheritdoc
	 */
	public function isSelected( FormOptions $opts ) {
		$values = explode(
			ChangesListStringOptionsFilterGroup::SEPARATOR,
			$opts[ $this->getGroup()->getName() ]
		);
		return in_array( $this->getName(), $values );
	}
}
