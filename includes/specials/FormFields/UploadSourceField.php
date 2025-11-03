<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\Field\HTMLTextField;

/**
 * A form field that contains a radio box in the label
 */
class UploadSourceField extends HTMLTextField {

	/**
	 * @param array $cellAttributes
	 * @return string
	 */
	public function getLabelHtml( $cellAttributes = [] ) {
		$id = $this->mParams['id'];
		$label = Html::rawElement( 'label', [ 'for' => $id ], $this->mLabel );

		if ( !empty( $this->mParams['radio'] ) ) {
			$radioId = $this->mParams['radio-id'] ??
				// Old way. For the benefit of extensions that do not define
				// the 'radio-id' key.
				'wpSourceType' . $this->mParams['upload-type'];

			$attribs = [
				'name' => 'wpSourceType',
				'type' => 'radio',
				'id' => $radioId,
				'value' => $this->mParams['upload-type'],
			];

			if ( !empty( $this->mParams['checked'] ) ) {
				$attribs['checked'] = 'checked';
			}

			$label .= Html::element( 'input', $attribs );
		}

		return Html::rawElement( 'td', [ 'class' => 'mw-label' ] + $cellAttributes, $label );
	}

	/**
	 * @return int
	 */
	public function getSize() {
		return $this->mParams['size'] ?? 60;
	}
}
