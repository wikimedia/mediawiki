<?php

/**
 * Modelled on Sebastian Bergmann's PHPUnit_Extensions_PhptTestCase class.
 *
 * @see https://github.com/sebastianbergmann/phpunit/blob/master/src/Extensions/PhptTestCase.php
 * @author Sam Smith <samsmith@wikimedia.org>
 */
class LessFileCompilationTest extends MediaWikiTestCase {

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
	 * @throws PHPUnit_Framework_Exception When the file parameter isn't a
	 *   string or readable file
	 */
	public function __construct( $file, ResourceLoaderModule $module ) {
		if ( !is_string( $file ) || !is_file( $file ) || !is_readable( $file ) ) {
			throw PHPUnit_Util_InvalidArgumentHelper::factory( 1, 'readable file' );
		}

		parent::__construct( 'testLessFileCompilation' );

		$this->file = $file;
		$this->module = $module;
	}

	public function testLessFileCompilation() {
		$compiler = ResourceLoader::getLessCompiler();
		$this->assertNotNull( $compiler->compileFile( $this->file ) );
	}

	public function getName( $withDataSet = true ) {
		return $this->toString();
	}

	public function toString() {
		$moduleName = $this->module->getName();

		return "{$this->file} in the \"{$moduleName}\" module";
	}
}
