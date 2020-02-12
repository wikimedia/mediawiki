<?php

/**
 * Test converter (from Tajiki to latin orthography)
 */
class DummyConverter extends LanguageConverter {

	private $table = [
		'б' => 'b',
		'в' => 'v',
		'г' => 'g',
	];

	public function loadDefaultTables() {
		$this->mTables = [
			'sgs' => new ReplacementArray(),
			'simple' => new ReplacementArray(),
			'tg-latn' => new ReplacementArray( $this->table ),
			'tg' => new ReplacementArray()
		];
	}
}
