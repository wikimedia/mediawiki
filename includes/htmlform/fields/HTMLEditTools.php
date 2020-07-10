<?php

/*
 * @stable to extend
 */
class HTMLEditTools extends HTMLFormField {
	public function getInputHTML( $value ) {
		return '';
	}

	public function getTableRow( $value ) {
		$msg = $this->formatMsg();

		return '<tr><td></td><td class="mw-input">' .
			'<div class="mw-editTools">' .
			$msg->parseAsBlock() .
			"</div></td></tr>\n";
	}

	/**
	 * @param string $value
	 * @return string
	 * @since 1.20
	 */
	public function getDiv( $value ) {
		$msg = $this->formatMsg();

		return '<div class="mw-editTools">' . $msg->parseAsBlock() . '</div>';
	}

	/**
	 * @param string $value
	 * @return string
	 * @since 1.20
	 */
	public function getRaw( $value ) {
		return $this->getDiv( $value );
	}

	protected function formatMsg() {
		if ( empty( $this->mParams['message'] ) ) {
			$msg = $this->msg( 'edittools' );
		} else {
			$msg = $this->getMessage( $this->mParams['message'] );
			if ( $msg->isDisabled() ) {
				$msg = $this->msg( 'edittools' );
			}
		}
		$msg->inContentLanguage();

		return $msg;
	}
}
