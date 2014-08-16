<?php

class HTMLTextAreaField extends HTMLFormField {
	const DEFAULT_COLS = 80;
	const DEFAULT_ROWS = 25;

	function getCols() {
		return isset( $this->mParams['cols'] ) ? $this->mParams['cols'] : static::DEFAULT_COLS;
	}

	function getRows() {
		return isset( $this->mParams['rows'] ) ? $this->mParams['rows'] : static::DEFAULT_ROWS;
	}

	function getInputHTML( $value ) {
		$attribs = array(
				'id' => $this->mID,
				'cols' => $this->getCols(),
				'rows' => $this->getRows(),
			) + $this->getTooltipAndAccessKey();

		if ( $this->mClass !== '' ) {
			$attribs['class'] = $this->mClass;
		}

		$allowedParams = array(
			'placeholder',
			'tabindex',
			'disabled',
			'readonly',
			'required',
			'autofocus'
		);

		$attribs += $this->getAttributes( $allowedParams );
		return Html::textarea( $this->mName, $value, $attribs );
	}
}
