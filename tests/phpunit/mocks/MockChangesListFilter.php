<?php

class MockChangesListFilter extends ChangesListFilter {
	public function displaysOnUnstructuredUi() {
		throw new MWException(
			'Not implemented: If the test relies on this, put it one of the ' .
			'subclasses\' tests (e.g. ChangesListBooleanFilterTest) ' .
			'instead of testing the abstract class'
		);
	}

	public function isSelected( FormOptions $opts ) {
		return false;
	}
}
