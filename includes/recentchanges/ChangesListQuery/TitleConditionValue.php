<?php

namespace MediaWiki\RecentChanges\ChangesListQuery;

use MediaWiki\Linker\LinkTarget;
use MediaWiki\Page\PageReference;

/**
 * A simple value class and helpers for a namespace/dbkey pair
 *
 * @internal Helper for ChangesListQuery modules
 */
class TitleConditionValue implements \Stringable {
	/**
	 * @param LinkTarget|PageReference $title
	 * @return self
	 */
	public static function create( $title ) {
		if ( $title instanceof LinkTarget ) {
			$ns = $title->getNamespace();
			$dbk = $title->getDBkey();
		} elseif ( $title instanceof PageReference ) {
			$ns = $title->getNamespace();
			$dbk = $title->getDBkey();
		} else {
			throw new \InvalidArgumentException(
				"Unknown value type for title"
			);
		}
		return new self ( $ns, $dbk );
	}

	/**
	 * @param self[] $values
	 * @return array
	 */
	public static function makeSet( $values ) {
		$set = [];
		foreach ( $values as $value ) {
			$set[$value->namespace][$value->dbKey] = true;
		}
		return $set;
	}

	public function __construct(
		public int $namespace,
		public string $dbKey
	) {
	}

	public function __toString(): string {
		return "{$this->namespace}:{$this->dbKey}";
	}
}
