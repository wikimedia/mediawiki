<?php

use MediaWiki\Html\FormOptions;
use MediaWiki\RecentChanges\ChangesListFilterGroup;

class MockChangesListFilterGroup extends ChangesListFilterGroup {
	/** @inheritDoc */
	public function createFilter( array $filterDefinition ) {
		return new MockChangesListFilter( $filterDefinition );
	}

	/** @inheritDoc */
	public function registerFilter( MockChangesListFilter $filter ) {
		$this->filters[$filter->getName()] = $filter;
	}

	/** @inheritDoc */
	public function addOptions( FormOptions $opts, $allowDefaults, $isStructuredFiltersEnabled ) {
	}

	/** @inheritDoc */
	public function setDefault( $defaultValue ) {
	}
}
