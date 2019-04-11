<?php

class DateFormatterFactory {
	/** @var DateFormatter[] */
	private $instances;

	/**
	 * @param Language $lang
	 * @return DateFormatter
	 */
	public function get( Language $lang ) {
		$code = $lang->getCode();
		if ( !isset( $this->instances[$code] ) ) {
			$this->instances[$code] = new DateFormatter( $lang );
		}
		return $this->instances[$code];
	}
}
