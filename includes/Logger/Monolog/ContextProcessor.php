<?php

namespace MediaWiki\Logger\Monolog;

use MediaWiki\Logger\LoggerFactory;
use Monolog\LogRecord;

/**
 * Annotate log records with the context added via LoggerFactory::getContext().
 * @since 1.44
 */
class ContextProcessor {

	public function __invoke( LogRecord $record ): LogRecord {
		// LogRecord::$context is readonly, so return a copy with the merged
		// context. The union keeps keys already on the record, matching the
		// prior precedence where per-call context beat the diagnostic context.
		return $record->with(
			context: $record->context + LoggerFactory::getContext()->get()
		);
	}

}
