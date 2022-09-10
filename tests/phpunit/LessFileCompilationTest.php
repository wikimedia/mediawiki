<?php

use MediaWiki\ResourceLoader\Module;

/**
 * Modelled on Sebastian Bergmann's PHPUnit_Extensions_PhptTestCase class.
 *
 * @see https://github.com/sebastianbergmann/phpunit/blob/master/src/Extensions/PhptTestCase.php
 * @author Sam Smith <samsmith@wikimedia.org>
 * @coversNothing
 */
class LessFileCompilationTest extends ResourceLoaderTestCase {

	/**
	 * @var string
	 */
	protected $file;

	/**
	 * @var Module The ResourceLoader module that contains the file
	 */
	protected $module;

	/**
	 * @param string $file
	 * @param Module $module The ResourceLoader module that
	 *   contains the file
	 */
	public function __construct( $file, Module $module ) {
		parent::__construct( 'testLessFileCompilation' );

		$this->file = $file;
		$this->module = $module;
	}

	public function testLessFileCompilation() {
		$thisString = $this->toString();
		$this->assertIsReadable( $this->file, "$thisString must refer to a readable file" );

		$rlContext = $this->getResourceLoaderContext();

		// Bleh
		$method = new ReflectionMethod( $this->module, 'compileLessString' );
		$method->setAccessible( true );
		$fileContents = file_get_contents( $this->file );
		$this->assertNotNull( $method->invoke( $this->module, $fileContents, $this->file, $rlContext ) );
	}

	public function toString(): string {
		$moduleName = $this->module->getName();

		return "{$this->file} in the \"{$moduleName}\" module";
	}
}
