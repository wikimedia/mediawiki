<?php

namespace MediaWiki\Parser;

use MediaWiki\Language\Language;

class DateFormatterFactory {

	/** @var array<string,DateFormatter> */
	private array $instances;

	public function get( Language $lang ): DateFormatter {
		$code = $lang->getCode();
		$this->instances[$code] ??= new DateFormatter( $lang );
		return $this->instances[$code];
	}
}

/** @deprecated class alias since 1.43 */
class_alias( DateFormatterFactory::class, 'DateFormatterFactory' );
