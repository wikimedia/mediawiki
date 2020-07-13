<?php

/**
 * A limit dropdown, which accepts any valid number
 *
 * @stable to extend
 */
class HTMLSelectLimitField extends HTMLSelectField {
	/**
	 * Basically don't do any validation. If it's a number that's fine. Also,
	 * add it to the list if it's not there already
	 *
	 * @param string $value
	 * @param array $alldata
	 * @return bool
	 */
	public function validate( $value, $alldata ) {
		if ( $value == '' ) {
			return true;
		}

		// Let folks pick an explicit limit not from our list, as long as it's a real numbr.
		if ( !in_array( $value, $this->mParams['options'] )
			&& $value == intval( $value )
			&& $value > 0
		) {
			// This adds the explicitly requested limit value to the drop-down,
			// then makes sure it's sorted correctly so when we output the list
			// later, the custom option doesn't just show up last.
			$this->mParams['options'][$this->mParent->getLanguage()->formatNum( $value )] =
				intval( $value );
			asort( $this->mParams['options'] );
		}

		return true;
	}
}
