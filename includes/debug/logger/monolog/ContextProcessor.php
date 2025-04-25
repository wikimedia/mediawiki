<?php

namespace MediaWiki\Logger\Monolog;

use MediaWiki\Logger\LoggerFactory;

/**
 * Annotate log records with the context added via LoggerFactory::getContext().
 * @since 1.44
 */
class ContextProcessor {

	/**
	 * @param array $record
	 * @return array
	 */
	public function __invoke( array $record ) {
		$record['context'] += LoggerFactory::getContext()->get();
		return $record;
	}

}
