<?php

/**
 * Modelled on Sebastian Bergmann's PHPUnit_Extensions_PhptTestSuite class.
 *
 * @see https://github.com/sebastianbergmann/phpunit/blob/master/src/Extensions/PhptTestSuite.php
 * @author Sam Smith <samsmith@wikimedia.org>
 */
class LessTestSuite extends PHPUnit_Framework_TestSuite {
	/**
	 * @param string $paths
	 * @throws PHPUnit_Framework_Exception When any of the elements of the
	 *   paths parameter isn't a string or a directory
	 */
	public function __construct( $paths ) {
		$paths = (array)$paths;

		$this->setName( implode( ',', $paths ) );

		foreach ( $paths as $path ) {
			if ( ! is_string( $path ) || ! is_dir( $path ) ) {
				throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'array of directories');
			}
		}

		$fileIterator = new File_Iterator_Facade();
		$files = $fileIterator->getFilesAsArray( $paths, 'less' );

		foreach ( $files as $file ) {
			$this->addTest( new LessTestCase( $file ) );
		}
	}
}
