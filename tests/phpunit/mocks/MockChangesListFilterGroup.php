<?php

class MockChangesListFilterGroup extends ChangesListFilterGroup {
	public function createFilter( array $filterDefinition ) {
		return new MockChangesListFilter( $filterDefinition );
	}

	public function registerFilter( MockChangesListFilter $filter ) {
		$this->filters[$filter->getName()] = $filter;
	}

	public function isPerGroupRequestParameter() {
		throw new MWException(
			'Not implemented: If the test relies on this, put it one of the ' .
			'subclasses\' tests (e.g. ChangesListBooleanFilterGroupTest) ' .
			'instead of testing the abstract class'
		);
	}
}
