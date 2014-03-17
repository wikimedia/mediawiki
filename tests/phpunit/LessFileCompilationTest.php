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
	protected $module;

	/**
	 * @param string $file
	 * @param ResourceLoaderModule $module The ResourceLoader module that
	 *   contains the file
	 * @throws PHPUnit_Framework_Exception When the file parameter isn't a
	 *   string or readable file
	 */
	public function __construct( $file, ResourceLoaderModule $module ) {
		if ( ! is_string( $file ) || ! is_file( $file ) || ! is_readable( $file ) ) {
			throw PHPUnit_Util_InvalidArgumentHelper::factory( 1, 'readable file' );
		}

		$this->file = $file;
		$this->module = $module;
	}

	public function run( PHPUnit_Framework_TestResult $result = null ) {
		if ( ! $result ) {
			$result = new PHPUnit_Framework_TestResult();
		}

		// XXX (phuedx, 2014-03-14) #startTest and #endTest should time
		// tests automatically.
		$result->startTest( $this );
		PHP_Timer::start();

		$compiler = ResourceLoader::getLessCompiler();
		$exception = null;

		try {
			$compiler->compileFile( $this->file );
		} catch ( Exception $e ) {
			$exception = $e;
		}

		$time = PHP_Timer::stop();

		if ( $exception ) {
			$result->addError( $this, $exception, $time);
		}

		$result->endTest( $this, $time );
	}

	public function getName( $withDataSet = true ) {
		return $this->toString();
	}

	public function toString() {
		$moduleName = $this->module->getName();

		return "{$this->file} in the \"{$moduleName}\" module";
	}
}
