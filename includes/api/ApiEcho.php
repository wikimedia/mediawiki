<?php

/**
 * Dummy API module for testing templated parameters
 * @ingroup API
 */
class ApiEcho extends ApiBase {

	public function __construct( $main, $name ) {
		parent::__construct( $main, $name, $name === 'xxecho' ? 'xx' : '' );
	}

	public function execute() {
		$this->getResult()->addValue( null, $this->getModuleName(),
			$this->extractRequestParams()
		);
	}

	public function getAllowedParams() {
		return [
			'normal' => null,

			'abcs' => [
				self::PARAM_TYPE => [ 'a', 'b', 'c' ],
				self::PARAM_ISMULTI => true,
				self::PARAM_REQUIRED => true,
			],

			'bar-{abc}' => [
				self::PARAM_TEMPLATE_VARS => [ 'abc' => 'abcs' ],
				self::PARAM_ISMULTI => true,
				self::PARAM_REQUIRED => true,
			],
			'xyz' => [
				self::PARAM_TYPE => [ 'x', 'y', 'z' ],
				self::PARAM_ISMULTI => true,
			],
			'baz-{abc}{bar}' => [
				self::PARAM_TEMPLATE_VARS => [ 'abc' => 'abcs', 'bar' => 'bar-{abc}' ],
			],
			'baz2-{abc}{bar}{xyz}' => [
				self::PARAM_TEMPLATE_VARS => [ 'abc' => 'abcs', 'bar' => 'bar-{abc}', 'xyz' => 'xyz' ],
			],
			'deprecated-{xyz}' => [
				self::PARAM_TEMPLATE_VARS => [ 'xyz' => 'xyz' ],
				self::PARAM_DEPRECATED => true,
			],

			'dflt' => [
				self::PARAM_TYPE => 'string',
				self::PARAM_ISMULTI => true,
				self::PARAM_DFLT => 'a|c',
			],
			'dflt-{d}' => [
				self::PARAM_TEMPLATE_VARS => [ 'd' => 'dflt' ],
				self::PARAM_ISMULTI => true,
				self::PARAM_DFLT => 'D1|D2',
			],
			'dflt-{d}-{d2}' => [
				self::PARAM_TEMPLATE_VARS => [ 'd' => 'dflt', 'd2' => 'dflt-{d}' ],
			],
		];
	}

}
