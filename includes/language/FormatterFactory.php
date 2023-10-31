<?php

namespace MediaWiki\Language;

use MediaWiki\Status\StatusFormatter;
use MessageCache;
use MessageLocalizer;

/**
 * Factory for formatters of common complex objects
 *
 * @since 1.42
 */
class FormatterFactory {

	private MessageCache $messageCache;

	/**
	 * @param MessageCache $messageCache
	 */
	public function __construct( MessageCache $messageCache ) {
		$this->messageCache = $messageCache;
	}

	public function getStatusFormatter( MessageLocalizer $messageLocalizer ): StatusFormatter {
		return new StatusFormatter( $messageLocalizer, $this->messageCache );
	}

}
