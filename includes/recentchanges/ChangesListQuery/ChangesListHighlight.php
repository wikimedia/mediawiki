<?php

namespace MediaWiki\RecentChanges\ChangesListQuery;

/**
 * @internal
 */
class ChangesListHighlight {
	public function __construct(
		public bool $sense,
		public string $moduleName,
		public mixed $value
	) {
	}
}
