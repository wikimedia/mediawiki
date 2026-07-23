<?php

namespace Wikimedia\Tests\Unit;

use MediaWiki\Json\JsonCodec;
use MediaWiki\Message\Message;
use MediaWikiUnitTestCase;
use PHPUnit\Framework\TestCase;
use StatusValue;
use Wikimedia\Assert\Assert;
use Wikimedia\Message\MessageSpecifier;
use Wikimedia\Message\MessageValue;
use Wikimedia\Message\ParamType;
use Wikimedia\Message\ScalarParam;
use Wikimedia\Tests\JsonSerializationTestTrait;

/**
 * @covers \StatusValue
 */
class StatusValueTest extends MediaWikiUnitTestCase {
	use JsonSerializationTestTrait;

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
	public function testToString( bool $success, $message, $errors, string $expected, string $testExplanation ) {
		$status = StatusValue::newGood();

		$status->setResult( $success, $message );

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

	public function testCast(): void {
		$testStatusValue = TestStatusValue::newIntAndString( 1, 'string' );
		$this->assertInstanceOf( TestStatusValue::class, $testStatusValue );
		$this->assertSame( 1, $testStatusValue->getAnInt() );
		$this->assertSame( 'string', $testStatusValue->getAString() );

		$testGenericStatusValue = TestGenericStatusValue::cast( $testStatusValue );
		$this->assertInstanceOf( TestGenericStatusValue::class, $testGenericStatusValue );
		$this->assertStringStartsWith( '{"', $testGenericStatusValue->getValueJson() );

		$testStatusValue2 = TestStatusValue::cast( $testGenericStatusValue );
		$this->assertInstanceOf( TestStatusValue::class, $testStatusValue2 );
		$this->assertSame( 1, $testStatusValue2->getAnInt() );
		$this->assertSame( 'string', $testStatusValue2->getAString() );
	}

	public function testMergeWithStatusData(): void {
		$status1 = new StatusValue();
		$status2 = new StatusValue();
		$status1->statusData = 'foo';
		$status2->statusData = 'foo';
		$status1->merge( $status2 );
		$this->assertSame( 'foo', $status1->statusData );
	}

	// region   JsonSerializationTestTrait methods

	public static function getClassToTest(): string {
		return StatusValue::class;
	}

	public static function getSerializedDataPath(): string {
		return __DIR__ . '/../../../data/StatusValue';
	}

	public static function getTestInstancesAndAssertions(): array {
		$cases = [];
		$cases['good'] = [
			'instance' => StatusValue::newGood(),
			'assertions' => static function ( TestCase $testCase, StatusValue $status ) {
				$testCase->assertTrue( $status->isGood() );
			}
		];
		$cases['fatal-string-no-params'] = [
			'instance' => StatusValue::newFatal( 'message1' ),
			'assertions' => static function ( TestCase $testCase, StatusValue $status ) {
				$testCase->assertFalse( $status->isOK() );
				$testCase->assertSame(
					'<message key="message1"></message>',
					$status->getMessages()[0]->dump()
				);
			}
		];
		$cases['fatal-string-string-param'] = [
			'instance' => StatusValue::newFatal( 'message1', 'param1' ),
			'assertions' => static function ( TestCase $testCase, StatusValue $status ) {
				$testCase->assertFalse( $status->isOK() );
				$testCase->assertSame(
					'<message key="message1"><text>param1</text></message>',
					$status->getMessages()[0]->dump()
				);
			}
		];
		$cases['fatal-string-scalar-param'] = [
			'instance' => StatusValue::newFatal(
				'message1',
				new ScalarParam( ParamType::TEXT, 'param1' )
			),
			'assertions' => static function ( TestCase $testCase, StatusValue $status ) {
				$testCase->assertFalse( $status->isOK() );
				$testCase->assertSame(
					'<message key="message1"><text>param1</text></message>',
					$status->getMessages()[0]->dump()
				);
			}
		];

		$cases['fatal-MessageValue'] = [
			'instance' => StatusValue::newFatal( new MessageValue(
				'message1',
				[ new ScalarParam( ParamType::SIZE, 1234 ) ]
			) ),
			'assertions' => static function ( TestCase $testCase, StatusValue $status ) {
				$testCase->assertFalse( $status->isOK() );
				$testCase->assertSame(
					'<message key="message1"><size>1234</size></message>',
					$status->getMessages()[0]->dump()
				);
			}
		];

		$status = new StatusValue();
		$status->warning( 'message1' );
		$status->error( 'message2' );
		$cases['warning-and-error'] = [
			'instance' => $status,
			'assertions' => static function ( TestCase $testCase, StatusValue $status ) {
				$testCase->assertTrue( $status->isOK() );
				$testCase->assertSame(
					'<message key="message1"></message>',
					$status->getMessages( 'warning' )[0]->dump()
				);
				$testCase->assertSame(
					'<message key="message2"></message>',
					$status->getMessages( 'error' )[0]->dump()
				);
			}
		];

		$status = new StatusValue;
		$status->value = 1;
		$status->success = [ 'a' => 1 ];
		$status->successCount++;
		$status->failCount += 2;
		$status->statusData = 3;
		$cases['misc-props'] = [
			'instance' => $status,
			'assertions' => static function ( TestCase $testCase, StatusValue $status ) {
				$testCase->assertSame( 1, $status->getValue() );
				$testCase->assertSame( [ 'a' => 1 ], $status->success );
				$testCase->assertSame( 1, $status->successCount );
				$testCase->assertSame( 2, $status->failCount );
				$testCase->assertSame( 3, $status->statusData );
			}
		];

		return $cases;
	}

	// endregion -- end of JsonSerializationTestTrait methods

	public function testMessageSpecifierLooseRoundTrip() {
		$message = new class implements MessageSpecifier {
			public function getKey(): string {
				return 'key';
			}

			public function getParams(): array {
				return [ 'param1' ];
			}
		};
		$status = StatusValue::newFatal( $message );
		$codec = new JsonCodec();
		$json = $codec->serialize( $status );
		$newStatus = $codec->deserialize( $json );
		$newMessage = $newStatus->getMessages()[0];
		$this->assertInstanceOf( MessageValue::class, $newMessage );
		$this->assertSame(
			'<message key="key"><text>param1</text></message>',
			$newMessage->dump()
		);
	}

}

class TestStatusValue extends StatusValue {

	public static function newIntAndString( int $anInt, string $aString ) {
		return parent::newGood( [
			'anInt' => $anInt,
			'aString' => $aString,
		] );
	}

	public function getAnInt(): int {
		Assert::precondition( $this->isOK(), '$this->isOK()' );
		return $this->getValue()['anInt'];
	}

	public function getAString(): string {
		Assert::precondition( $this->isOK(), '$this->isOK()' );
		return $this->getValue()['aString'];
	}

}

class TestGenericStatusValue extends StatusValue {

	public function getValueJson(): string {
		return json_encode( $this->getValue() );
	}

}
