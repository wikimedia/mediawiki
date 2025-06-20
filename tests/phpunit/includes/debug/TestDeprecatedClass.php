<?php

use MediaWiki\Debug\DeprecationHelper;

#[\AllowDynamicProperties]
class TestDeprecatedClass {

	use DeprecationHelper;

	/** @var int */
	protected $protectedDeprecated = 1;
	/** @var int */
	protected $protectedNonDeprecated = 1;
	/** @var int */
	private $privateDeprecated = 1;
	/** @var int */
	private $privateNonDeprecated = 1;
	/** @var int */
	private $fallbackDeprecated = 1;

	/** @var string */
	private $foo = 'FOO';

	public function __construct() {
		$this->deprecatePublicProperty( 'protectedDeprecated', '1.23' );
		$this->deprecatePublicProperty( 'privateDeprecated', '1.24' );

		$this->deprecatePublicPropertyFallback( 'fallbackDeprecated', '1.25',
			function () {
				return $this->fallbackDeprecated;
			},
			function ( $value ) {
				$this->fallbackDeprecated = $value;
			}
		);
		$this->deprecatePublicPropertyFallback( 'fallbackDeprecatedMethodName', '1.26',
			'getFoo',
			'setFoo'
		);
		$this->deprecatePublicPropertyFallback( 'fallbackGetterOnly', '1.25',
			static function () {
				return 1;
			}
		);
	}

	/**
	 * @param mixed $prod
	 * @param mixed $prond
	 * @param mixed $prid
	 * @param mixed $prind
	 */
	public function setThings( $prod, $prond, $prid, $prind ) {
		$this->protectedDeprecated = $prod;
		$this->protectedNonDeprecated = $prond;
		$this->privateDeprecated = $prid;
		$this->privateNonDeprecated = $prind;
	}

	/**
	 * @return array
	 */
	public function getThings() {
		return [
			'prod' => $this->protectedDeprecated,
			'prond' => $this->protectedNonDeprecated,
			'prid' => $this->privateDeprecated,
			'prind' => $this->privateNonDeprecated,
		];
	}

	/**
	 * @return mixed
	 */
	public function getFoo() {
		return $this->foo;
	}

	/**
	 * @param mixed $foo
	 */
	public function setFoo( $foo ) {
		$this->foo = $foo;
	}
}
