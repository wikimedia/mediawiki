<?php

namespace MediaWiki\Tests\Unit\Settings\Source;

class ExampleDefinitionsClass {
	public const SOME_SCHEMA = [
		'type' => 'string'
	];

	public const REFERENCING_SCHEMA = [
		'$ref' => [
			'class' => self::class, 'field' => 'SOME_SCHEMA'
		]
	];

	public const CYCLED_SCHEMA = [
		'$ref' => [
			'class' => self::class, 'field' => 'CYCLED_SCHEMA_LAST'
		]
	];

	public const CYCLED_SCHEMA_LAST = [
		'$ref' => [
			'class' => self::class, 'field' => 'CYCLED_SCHEMA'
		]
	];
}
