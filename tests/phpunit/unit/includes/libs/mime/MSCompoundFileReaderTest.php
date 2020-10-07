<?php
/*
 * Copyright 2019 Wikimedia Foundation
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software distributed
 * under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS
 * OF ANY KIND, either express or implied. See the License for the
 * specific language governing permissions and limitations under the License.
 */

/**
 * @group Media
 * @covers MSCompoundFileReader
 */
class MSCompoundFileReaderTest extends PHPUnit\Framework\TestCase {
	public static function provideValid() {
		return [
			[ 'calc.xls', 'application/vnd.ms-excel' ],
			[ 'excel2016-compat97.xls', 'application/vnd.ms-excel' ],
			[ 'gnumeric.xls', 'application/vnd.ms-excel' ],
			[ 'impress.ppt', 'application/vnd.ms-powerpoint' ],
			[ 'powerpoint2016-compat97.ppt', 'application/vnd.ms-powerpoint' ],
			[ 'word2016-compat97.doc', 'application/msword' ],
			[ 'writer.doc', 'application/msword' ],
		];
	}

	/** @dataProvider provideValid */
	public function testReadFile( $fileName, $expectedMime ) {
		global $IP;

		$info = MSCompoundFileReader::readFile( "$IP/tests/phpunit/data/MSCompoundFileReader/$fileName" );
		$this->assertTrue( $info['valid'] );
		$this->assertSame( $expectedMime, $info['mime'] );
	}

	public static function provideInvalid() {
		return [
			[ 'dir-beyond-end.xls', MSCompoundFileReader::ERROR_READ_PAST_END ],
			[ 'fat-loop.xls', MSCompoundFileReader::ERROR_INVALID_FORMAT ],
			[ 'invalid-signature.xls', MSCompoundFileReader::ERROR_INVALID_SIGNATURE ],
		];
	}

	/** @dataProvider provideInvalid */
	public function testReadFileInvalid( $fileName, $expectedError ) {
		global $IP;

		$info = MSCompoundFileReader::readFile( "$IP/tests/phpunit/data/MSCompoundFileReader/$fileName" );
		$this->assertFalse( $info['valid'] );
		$this->assertSame( $expectedError, $info['errorCode'] );
	}
}
