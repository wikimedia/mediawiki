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
}
