<?php

class HTMLTextAreaField extends HTMLFormField {
	const DEFAULT_COLS = 80;
	const DEFAULT_ROWS = 25;

	function getCols() {
		return isset( $this->mParams['cols'] )
			? $this->mParams['cols']
			: static::DEFAULT_COLS;
	}

	function getRows() {
		return isset( $this->mParams['rows'] )
			? $this->mParams['rows']
			: static::DEFAULT_ROWS;
	}

	function getInputHTML( $value ) {
		$attribs = array(
				'id' => $this->mID,
				'name' => $this->mName,
				'cols' => $this->getCols(),
				'rows' => $this->getRows(),
			) + $this->getTooltipAndAccessKey();

		if ( $this->mClass !== '' ) {
			$attribs['class'] = $this->mClass;
		}

		if ( !empty( $this->mParams['disabled'] ) ) {
			$attribs['disabled'] = 'disabled';
		}

		if ( !empty( $this->mParams['readonly'] ) ) {
			$attribs['readonly'] = 'readonly';
		}

		if ( isset( $this->mParams['placeholder'] ) ) {
			$attribs['placeholder'] = $this->mParams['placeholder'];
		}

		foreach ( array( 'required', 'autofocus' ) as $param ) {
			if ( isset( $this->mParams[$param] ) ) {
				$attribs[$param] = '';
			}
		}

		return Html::element( 'textarea', $attribs, $value );
	}
}