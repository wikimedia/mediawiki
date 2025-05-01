<?php

namespace Wikimedia\Tests\Unit;

use MediaWiki\Message\Message;
use MediaWikiUnitTestCase;
use StatusValue;

/**
 * @covers \StatusValue
 */
class StatusValueTest extends MediaWikiUnitTestCase {

	public static function provideToString() {
		yield [
			true, null, null,
			'<OK, no errors detected, no value set>',
			'Empty, good state'
		];
		yield [
			false, null, null,
			'<Error, no errors detected, no value set>',
			'Empty, error state'
		];

		yield [
			true, 'TestValue', null,
			'<OK, no errors detected, string value set>',
			'Simple string, good state'
		];
		yield [
			false, 'TestValue', null,
			'<Error, no errors detected, string value set>',
			'Simple string, error state'
		];

		yield [
			true, 42, null,
			'<OK, no errors detected, int value set>',
			'Simple int, good state'
		];
		yield [
			false, 42, null,
			'<Error, no errors detected, int value set>',
			'Simple int, error state'
		];

		yield [
			true, [ 'TestValue' => false ], null,
			'<OK, no errors detected, array value set>',
			'Simple array, good state'
		];
		yield [
			false, [ 'TestValue' => false ], null,
			'<Error, no errors detected, array value set>',
			'Simple array, error state'
		];

		$basicErrorReport = "\n"
			. "+----------+---------------------------+--------------------------------------+\n"
			. "| error    | This is the error         |                                      |\n"
			. "+----------+---------------------------+--------------------------------------+\n";

		yield [
			true, null, [ 'This is the error' ],
			'<OK, collected 1 message(s) on the way, no value set>' . $basicErrorReport,
			'Empty, string error, good state'
		];
		yield [
			false, null, [ 'This is the error' ],
			'<Error, collected 1 message(s) on the way, no value set>' . $basicErrorReport,
			'Empty, string error, error state'
		];

		yield [
			false, 'TestValue', [ 'This is the error' ],
			'<Error, collected 1 message(s) on the way, string value set>' . $basicErrorReport,
			'Simple string, string error, error state'
		];

		yield [
			false, 42, [ 'This is the error' ],
			'<Error, collected 1 message(s) on the way, int value set>' . $basicErrorReport,
			'Simple int, string error, error state'
		];

		yield [
			false, [ 'TestValue' => false ], [ 'This is the error' ],
			'<Error, collected 1 message(s) on the way, array value set>' . $basicErrorReport,
			'Simple array, string error, error state'
		];

		yield [
			false, null, [ [ 'message' => 'This is the error', 'params' => false ] ],
			'<Error, collected 1 message(s) on the way, no value set>' . $basicErrorReport,
			'Error with false value shows as no parameter'
		];

		$specialErrorReport = "\n"
		. "+----------+---------------------------+--------------------------------------+\n"
		. "| error    | This is the error         | 1                                    |\n"
		. "+----------+---------------------------+--------------------------------------+\n";
		yield [
			false, null, [ [ 'message' => 'This is the error', 'params' => true ] ],
			'<Error, collected 1 message(s) on the way, no value set>' . $specialErrorReport,
			'Error with true value shows as 1 int'
		];

		$specialErrorReport = "\n"
		. "+----------+---------------------------+--------------------------------------+\n"
		. "| error    | This is the error         | 0                                    |\n"
		. "+----------+---------------------------+--------------------------------------+\n";
		yield [
			false, null, [ [ 'message' => 'This is the error', 'params' => 0 ] ],
			'<Error, collected 1 message(s) on the way, no value set>' . $specialErrorReport,
			'Error with 0 int value'
		];

		$specialErrorReport = "\n"
		. "+----------+---------------------------+--------------------------------------+\n"
		. "| error    | This is the error         | 42                                   |\n"
		. "+----------+---------------------------+--------------------------------------+\n";
		yield [
			false, null, [ [ 'message' => 'This is the error', 'params' => 42 ] ],
			'<Error, collected 1 message(s) on the way, no value set>' . $specialErrorReport,
			'Error with 42 int value'
		];

		$specialErrorReport = "\n"
		. "+----------+---------------------------+--------------------------------------+\n"
		. "| error    | This is the error         | TestValue                            |\n"
		. "+----------+---------------------------+--------------------------------------+\n";
		yield [
			false, null, [ [ 'message' => 'This is the error', 'params' => 'TestValue' ] ],
			'<Error, collected 1 message(s) on the way, no value set>' . $specialErrorReport,
			'Error with a string parameter'
		];

		$specialErrorReport = "\n"
		. "+----------+---------------------------+--------------------------------------+\n"
		. "| error    | This is the error         | [ TestValue, 42, 1, [ foo, baz ] ]   |\n"
		. "+----------+---------------------------+--------------------------------------+\n";
		yield [
			false, null, [
				[ 'message' => 'This is the error', 'params' => [ 'TestValue', 42, true, [ 'foo', 'bar' => 'baz' ] ] ]
			],
			'<Error, collected 1 message(s) on the way, no value set>' . $specialErrorReport,
			'Error with an array of parameters'
		];

		$multiErrorReport = "\n"
			. "+----------+---------------------------+--------------------------------------+\n"
			. "| error    | Basic string parsing      | Naïve string parsing                |\n"
			. "| error    | Wrapped string            | This is a longer input parameter and |\n"
			. "|          |                           |  thus will wrap                      |\n"
			. "| error    | Multi-byte string         | 캐나다∂는 북미에 있는 나라로 면적이 매우 넓습니다. |\n"
			. "| error    | Multi-byte wrapped string | 캐나다는 태평양에서 대서양까지, 북쪽으로는 북극과 접해 있는 북미 |\n"
			. "|          |                           | 의 큰 나라입니다.             |\n"
			. "+----------+---------------------------+--------------------------------------+\n";
		yield [
			false, null, [
				[ 'message' => 'Basic string parsing', 'params' => 'Naïve string parsing' ],
				[ 'message' => 'Wrapped string', 'params' => 'This is a longer input parameter and thus will wrap' ],
				[ 'message' => 'Multi-byte string', 'params' => '캐나다∂는 북미에 있는 나라로 면적이 매우 넓습니다.' ],
				[ 'message' => 'Multi-byte wrapped string', 'params' => '캐나다는 태평양에서 대서양까지, 북쪽으로는 북극과 접해 있는 북미의 큰 나라입니다.' ]
			],
			'<Error, collected 4 message(s) on the way, no value set>' . $multiErrorReport,
			'Three errors with different kinds of string parameters including long strings that are split when simple'
		];
	}

	/**
	 * @dataProvider provideToString
	 */
	public function testToString( bool $sucess, $message, $errors, string $expected, string $testExplanation ) {
		$status = StatusValue::newGood();

		$status->setResult( $sucess, $message );

		if ( isset( $errors ) ) {
			foreach ( $errors as $key => $error ) {
				if ( is_string( $error ) ) {
					$status->error( $error );
				} else {
					$status->error( $error['message'], $error['params'] );

				}
			}
		}

		$this->assertEquals( $expected, $status->__toString(), $testExplanation );
	}

	public function testGetErrorsByType() {
		$status = new StatusValue();
		$warning = new Message( 'warning111' );
		$error = new Message( 'error111' );
		$status->warning( $warning );
		$status->error( $error );

		$this->assertCount( 2, $status->getErrors() );
		$this->assertCount( 1, $status->getErrorsByType( 'warning' ) );
		$this->assertCount( 1, $status->getErrorsByType( 'error' ) );
		$this->assertEquals( $warning, $status->getErrorsByType( 'warning' )[0]['message'] );
		$this->assertEquals( $error, $status->getErrorsByType( 'error' )[0]['message'] );

		$this->assertCount( 2, $status->getMessages() );
		$this->assertCount( 1, $status->getMessages( 'warning' ) );
		$this->assertCount( 1, $status->getMessages( 'error' ) );
		$this->assertEquals( 'warning111', $status->getMessages( 'warning' )[0]->getKey() );
		$this->assertEquals( 'error111', $status->getMessages( 'error' )[0]->getKey() );
	}
}
