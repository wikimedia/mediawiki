<?php

/**
 * Modelled on Sebastian Bergmann's PHPUnit_Extensions_PhptTestCase class.
 *
 * @see https://github.com/sebastianbergmann/phpunit/blob/master/src/Extensions/PhptTestCase.php
 * @author Sam Smith <samsmith@wikimedia.org>
 */
class LessFileCompilationTest extends ResourceLoaderTestCase {

	/**
	 * @var string $file
	 */
	protected $file;

	/**
	 * @var ResourceLoaderModule The ResourceLoader module that contains
	 *   the file
	 */
	protected $module;

	/**
	 * @param string $file
	 * @param ResourceLoaderModule $module The ResourceLoader module that
	 *   contains the file
	 */
	public function __construct( $file, ResourceLoaderModule $module ) {
		parent::__construct( 'testLessFileCompilation' );

		$this->file = $file;
		$this->module = $module;
	}

	public function testLessFileCompilation() {
		$thisString = $this->toString();
		$this->assertTrue(
			is_string( $this->file ) && is_file( $this->file ) && is_readable( $this->file ),
			"$thisString must refer to a readable file"
		);

		$rlContext = $this->getResourceLoaderContext();

		// Bleh
		$method = new ReflectionMethod( $this->module, 'getLessCompiler' );
		$method->setAccessible( true );
		$compiler = $method->invoke( $this->module, $rlContext );

		$this->assertNotNull( $compiler->parseFile( $this->file )->getCss() );
	}

	public function toString() {
		$moduleName = $this->module->getName();

		return "{$this->file} in the \"{$moduleName}\" module";
	}
}
