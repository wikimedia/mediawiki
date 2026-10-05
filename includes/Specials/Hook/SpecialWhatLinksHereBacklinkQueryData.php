<?php

namespace MediaWiki\Specials\Hook;

use Wikimedia\Rdbms\IReadableDatabase;

/**
 * Context for filtering the backlink query used by Special:WhatLinksHere.
 *
 * @since 1.48
 */
final class SpecialWhatLinksHereBacklinkQueryData {
	/**
	 * @param IReadableDatabase $db Database connection used to build and execute the query
	 * @param string $table Backlink table name
	 * @param string $sourcePageIdField Field containing the source page ID
	 * @param array $formData Data submitted from the form, as processed by HTMLForm
	 */
	public function __construct(
		public readonly IReadableDatabase $db,
		public readonly string $table,
		public readonly string $sourcePageIdField,
		public readonly array $formData,
	) {
	}
}
