<?php
/**
 * This file was copied from CakePhps codesniffer tests before being modified
 * File: http://git.io/vkirb
 * From repository: https://github.com/cakephp/cakephp-codesniffer
 *
 * @license MIT
 * CakePHP(tm) : The Rapid Development PHP Framework (http://cakephp.org)
 * Copyright (c) 2005-2013, Cake Software Foundation, Inc.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * @author Adam Shorland
 * Modifications
 *  - Rename appropriatly
 *  - Adapt $this->helper->runPhpCs call to pass second parameter $standard
 */
class MediaWikiStandardTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var TestHelper
	 */
	private $helper;

	public function setUp() {
		parent::setUp();
		if ( empty( $this->helper ) ) {
			$this->helper = new TestHelper();
		}
	}

	/**
	 * testFiles
	 *
	 * Run simple syntax checks, if the filename ends with pass.php - expect it to pass
	 */
	public static function testProvider() {
		$tests = array();

		$standard = dirname( dirname( __FILE__ ) );
		$directoryIterator = new RecursiveDirectoryIterator( dirname( __FILE__ ) . '/files' );
		$iterator = new RecursiveIteratorIterator( $directoryIterator );
		foreach ( $iterator as $dir ) {
			if ( $dir->isDir() ) {
				continue;
			}

			$file = $dir->getPathname();
			$expectPass = ( substr( $file, -8 ) === 'pass.php' );
			$tests[] = array(
				$file,
				$standard,
				$expectPass
			);
		}
		return $tests;
	}

	/**
	 * _testFile
	 *
	 * @dataProvider testProvider
	 *
	 * @param string $file
	 * @param string $standard
	 * @param boolean $expectPass
	 */
	public function testFile( $file, $standard, $expectPass ) {
		$outputStr = $this->helper->runPhpCs( $file, $standard );
		if ( $expectPass ) {
			$this->assertNotRegExp(
				"/FOUND \d+ ERROR/",
				$outputStr,
				basename( $file ) . ' - expected to pass with no errors, some were reported. '
			);
		} else {
			$this->assertRegExp(
				"/FOUND \d+ ERROR/",
				$outputStr,
				basename( $file ) . ' - expected failures, none reported. '
			);
		}
	}

}
