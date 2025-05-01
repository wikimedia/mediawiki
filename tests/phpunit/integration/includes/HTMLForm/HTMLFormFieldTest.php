<?php

namespace MediaWiki\Tests\Integration\HTMLForm;

use DomainException;
use InvalidArgumentException;
use MediaWiki\Context\DerivativeContext;
use MediaWiki\Context\RequestContext;
use MediaWiki\HTMLForm\Field\HTMLFormFieldCloner;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\HTMLForm\HTMLFormField;
use MediaWiki\Message\Message;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;
use MediaWikiCoversValidator;
use MediaWikiIntegrationTestCase;
use PHPUnit\Framework\Assert;
use StatusValue;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\HTMLForm\HTMLFormField
 */
class HTMLFormFieldTest extends MediaWikiIntegrationTestCase {
	use MediaWikiCoversValidator;

	public function getNewForm( $descriptor, $requestData = [] ) {
		$requestData += [ 'wpEditToken' => 'ABC123' ];
		$request = new FauxRequest( $requestData, true );
		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setRequest( $request );
		$form = HTMLForm::factory( 'ooui', $descriptor, $context );
		$form->setTitle( Title::makeTitle( NS_MAIN, 'Main Page' ) )->setSubmitCallback( static function () {
			return true;
		} )->prepareForm();
		$status = $form->trySubmit();
		$this->assertTrue( $status );
		return $form;
	}

	/**
	 * @dataProvider provideCondState
	 */
	public function testCondState( $fieldInfo, $requestData, $callback, $exception = null ) {
		if ( $exception ) {
			$this->expectException( $exception[0] );
			$this->expectExceptionMessageMatches( $exception[1] );
		}
		$form = $this->getNewForm( array_merge_recursive( $fieldInfo, [
			'check1' => [ 'type' => 'check' ],
			'check2' => [ 'type' => 'check', 'invert' => true ],
			'check3' => [ 'type' => 'check', 'name' => 'foo' ],
			'select1' => [ 'type' => 'select', 'options' => [ 'a' => 'a', 'b' => 'b', 'c' => 'c' ], 'default' => 'b' ],
			'text1' => [ 'type' => 'text' ],
			'cloner' => [
				'class' => HTMLFormFieldCloner::class,
				'fields' => [
					'check1' => [ 'type' => 'check' ],
					'check2' => [ 'type' => 'check', 'invert' => true ],
					'check3' => [ 'type' => 'check', 'name' => 'foo' ],
				]
			]
		] ), $requestData );
		$callback( $form, $form->mFieldData );
	}

	public static function provideCondState() {
		yield 'Field hidden if "check" field is checked' => [
			'fieldInfo' => [
				'text1' => [ 'hide-if' => [ '===', 'check1', '1' ] ],
			],
			'requestData' => [
				'wpcheck1' => '1',
			],
			'callback' => static function ( $form, $fieldData ) {
				Assert::assertTrue( $form->getField( 'text1' )->isHidden( $fieldData ) );
			}
		];
		yield 'Field hidden if "check" field is not checked' => [
			'fieldInfo' => [
				'text1' => [ 'hide-if' => [ '===', 'check1', '' ] ],
			],
			'requestData' => [],
			'callback' => static function ( $form, $fieldData ) {
				Assert::assertTrue( $form->getField( 'text1' )->isHidden( $fieldData ) );
			}
		];
		yield 'Field not hidden if "check" field is not checked' => [
			'fieldInfo' => [
				'text1' => [ 'hide-if' => [ '===', 'check1', '1' ] ],
			],
			'requestData' => [],
			'callback' => static function ( $form, $fieldData ) {
				Assert::assertFalse( $form->getField( 'text1' )->isHidden( $fieldData ) );
			}
		];
		yield 'Field hidden if "check" field (invert) is checked' => [
			'fieldInfo' => [
				'text1' => [ 'hide-if' => [ '===', 'check2', '1' ] ],
			],
			'requestData' => [
				'wpcheck2' => '1',
			],
			'callback' => static function ( $form, $fieldData ) {
				Assert::assertTrue( $form->getField( 'text1' )->isHidden( $fieldData ) );
			}
		];
		yield 'Field hidden if "check" field (invert) is not checked' => [
			'fieldInfo' => [
				'text1' => [ 'hide-if' => [ '!==', 'check2', '1' ] ],
			],
			'requestData' => [],
			'callback' => static function ( $form, $fieldData ) {
				Assert::assertTrue( $form->getField( 'text1' )->isHidden( $fieldData ) );
			}
		];
		yield 'Field not hidden if "check" field (invert) is checked' => [
			'fieldInfo' => [
				'text1' => [ 'hide-if' => [ '!==', 'check2', '1' ] ],
			],
			'requestData' => [
				'wpcheck2' => '1',
			],
			'callback' => static function ( $form, $fieldData ) {
				Assert::assertFalse( $form->getField( 'text1' )->isHidden( $fieldData ) );
			}
		];
		yield 'Field hidden if "select" field has value' => [
			'fieldInfo' => [
				'text1' => [ 'hide-if' => [ '===', 'select1', 'a' ] ],
			],
			'requestData' => [
				'wpselect1' => 'a',
			],
			'callback' => static function ( $form, $fieldData ) {
				Assert::assertTrue( $form->getField( 'text1' )->isHidden( $fieldData ) );
			}
		];
		yield 'Field hidden if "text" field has value' => [
			'fieldInfo' => [
				'select1' => [ 'hide-if' => [ '===', 'text1', 'hello' ] ],
			],
			'requestData' => [
				'wptext1' => 'hello',
			],
			'callback' => static function ( $form, $fieldData ) {
				Assert::assertTrue( $form->getField( 'select1' )->isHidden( $fieldData ) );
			}
		];

		yield 'Field hidden using AND conditions' => [
			'fieldInfo' => [
				'text1' => [ 'hide-if' => [ 'AND',
					[ '===', 'check1', '1' ],
					[ '===', 'select1', 'a' ]
				] ],
			],
			'requestData' => [
				'wpcheck1' => '1',
				'wpselect1' => 'a',
			],
			'callback' => static function ( $form, $fieldData ) {
				Assert::assertTrue( $form->getField( 'text1' )->isHidden( $fieldData ) );
			}
		];
		yield 'Field hidden using OR conditions' => [
			'fieldInfo' => [
				'text1' => [ 'hide-if' => [ 'OR',
					[ '===', 'check1', '1' ],
					[ '===', 'select1', 'a' ]
				] ],
			],
			'requestData' => [
				'wpcheck1' => '1',
			],
			'callback' => static function ( $form, $fieldData ) {
				Assert::assertTrue( $form->getField( 'text1' )->isHidden( $fieldData ) );
			}
		];
		yield 'Field hidden using NAND conditions' => [
			'fieldInfo' => [
				'text1' => [ 'hide-if' => [ 'NAND',
					[ '===', 'check1', '1' ],
					[ '===', 'select1', 'a' ]
				] ],
			],
			'requestData' => [
				'wpcheck1' => '1',
			],
			'callback' => static function ( $form, $fieldData ) {
				Assert::assertTrue( $form->getField( 'text1' )->isHidden( $fieldData ) );
			}
		];
		yield 'Field hidden using NOR conditions' => [
			'fieldInfo' => [
				'text1' => [ 'hide-if' => [ 'NOR',
					[ '===', 'check1', '1' ],
					[ '===', 'select1', 'a' ]
				] ],
			],
			'requestData' => [],
			'callback' => static function ( $form, $fieldData ) {
				Assert::assertTrue( $form->getField( 'text1' )->isHidden( $fieldData ) );
			}
		];
		yield 'Field hidden using complex conditions' => [
			'fieldInfo' => [
				'text1' => [ 'hide-if' => [ 'OR',
					[ 'NOT', [ 'AND',
						[ '===', 'check1', '1' ],
						[ '===', 'check2', '1' ]
					] ],
					[ '===', 'select1', 'a' ]
				] ],
			],
			'requestData' => [],
			'callback' => static function ( $form, $fieldData ) {
				Assert::assertTrue( $form->getField( 'text1' )->isHidden( $fieldData ) );
			}
		];

		yield 'Invalid conditional specification (unsupported)' => [
			'fieldInfo' => [
				'text1' => [ 'hide-if' => [ '>', 'test1', '10' ] ],
			],
			'requestData' => [],
			'callback' => null,
			'exception' => [ InvalidArgumentException::class, '/Unknown operation/' ],
		];
		yield 'Invalid conditional specification (NOT)' => [
			'fieldInfo' => [
				'text1' => [ 'hide-if' => [ 'NOT', '===', 'check1', '1' ] ],
			],
			'requestData' => [],
			'callback' => null,
			'exception' => [ InvalidArgumentException::class, '/NOT takes exactly one parameter/' ],
		];
		yield 'Invalid conditional specification (AND/OR/NAND/NOR)' => [
			'fieldInfo' => [
				'text1' => [ 'hide-if' => [ 'AND', '===', 'check1', '1' ] ],
			],
			'requestData' => [],
			'callback' => null,
			'exception' => [ InvalidArgumentException::class, '/Expected array, found string/' ],
		];
		yield 'Invalid conditional specification (===/!==) 1' => [
			'fieldInfo' => [
				'text1' => [ 'hide-if' => [ '===', 'check1' ] ],
			],
			'requestData' => [],
			'callback' => null,
			'exception' => [ InvalidArgumentException::class, '/=== takes exactly two parameters/' ],
		];
		yield 'Invalid conditional specification (===/!==) 2' => [
			'fieldInfo' => [
				'text1' => [ 'hide-if' => [ '===', [ '===', 'check1', '1' ], '1' ] ],
			],
			'requestData' => [],
			'callback' => null,
			'exception' => [ InvalidArgumentException::class, '/Parameters for === must be strings/' ],
		];

		yield 'Field disabled if "check" field is checked' => [
			'fieldInfo' => [
				'text1' => [ 'disable-if' => [ '===', 'check1', '1' ] ],
			],
			'requestData' => [
				'wpcheck1' => '1',
			],
			'callback' => static function ( $form, $fieldData ) {
				Assert::assertTrue( $form->getField( 'text1' )->isDisabled( $fieldData ) );
			}
		];
		yield 'Field disabled if hidden' => [
			'fieldInfo' => [
				'text1' => [ 'hide-if' => [ '===', 'check1', '1' ] ],
			],
			'requestData' => [
				'wpcheck1' => '1',
			],
			'callback' => static function ( $form, $fieldData ) {
				Assert::assertTrue( $form->getField( 'text1' )->isDisabled( $fieldData ) );
			}
		];

		yield 'Field disabled even the field it relied on is named' => [
			'fieldInfo' => [
				'text1' => [ 'disable-if' => [ '===', 'check3', '1' ] ],
			],
			'requestData' => [
				'foo' => '1',
			],
			'callback' => static function ( $form, $fieldData ) {
				Assert::assertTrue( $form->getField( 'text1' )->isDisabled( $fieldData ) );
			}
		];
		yield 'Field disabled even the \'wp\' prefix is used (back-compat)' => [
			'fieldInfo' => [
				'text1' => [ 'disable-if' => [ '===', 'wpcheck1', '1' ] ],
			],
			'requestData' => [
				'wpcheck1' => '1',
			],
			'callback' => static function ( $form, $fieldData ) {
				Assert::assertTrue( $form->getField( 'text1' )->isDisabled( $fieldData ) );
			}
		];
		yield 'Field name does not exist' => [
			'fieldInfo' => [
				'text1' => [ 'disable-if' => [ '===', 'foo', '1' ] ],
			],
			'requestData' => [],
			'callback' => null,
			'exception' => [ DomainException::class, '/no field named foo/' ],
		];

		yield 'Field disabled in cloner if "check" field is checked' => [
			'fieldInfo' => [
				'cloner' => [ 'fields' => [
					'check2' => [ 'disable-if' => [ '===', 'check1', '1' ] ],
				] ]
			],
			'requestData' => [
				'wpcloner' => [ 0 => [ 'check1' => '1' ] ],
			],
			'callback' => static function ( $form, $fieldData ) {
				Assert::assertTrue( self::getFieldInCloner( $form, 'cloner', 0, 'check2' )
					->isDisabled( $fieldData ) );
			}
		];
		yield 'Field disabled in cloner if "check" (invert) field is checked' => [
			'fieldInfo' => [
				'cloner' => [ 'fields' => [
					'check1' => [ 'disable-if' => [ '===', 'check2', '1' ] ],
				] ]
			],
			'requestData' => [
				'wpcloner' => [ 0 => [ 'check2' => '1' ] ],
			],
			'callback' => static function ( $form, $fieldData ) {
				Assert::assertTrue( self::getFieldInCloner( $form, 'cloner', 0, 'check1' )
					->isDisabled( $fieldData ) );
			}
		];
		yield 'Field disabled in cloner if "check" (named) field is checked' => [
			'fieldInfo' => [
				'cloner' => [ 'fields' => [
					'check1' => [ 'disable-if' => [ '===', 'check3', '1' ] ],
				] ]
			],
			'requestData' => [
				'wpcloner' => [ 0 => [ 'foo' => '1' ] ],
			],
			'callback' => static function ( $form, $fieldData ) {
				Assert::assertTrue( self::getFieldInCloner( $form, 'cloner', 0, 'check1' )
					->isDisabled( $fieldData ) );
			}
		];
		yield 'Field disabled in cloner if "select" (outside) field has value' => [
			'fieldInfo' => [
				'cloner' => [ 'fields' => [
					'check1' => [ 'disable-if' => [ '===', 'select1', 'a' ] ],
				] ]
			],
			'requestData' => [
				'wpselect1' => 'a',
			],
			'callback' => static function ( $form, $fieldData ) {
				Assert::assertTrue( self::getFieldInCloner( $form, 'cloner', 0, 'check1' )
					->isDisabled( $fieldData ) );
			}
		];
	}

	private static function getFieldInCloner( $form, $clonerName, $index, $fieldName ) {
		$cloner = TestingAccessWrapper::newFromObject( $form->getField( $clonerName ) );
		return $cloner->getFieldsForKey( $index )[$fieldName];
	}

	/**
	 * @dataProvider provideParseCondState
	 */
	public function testParseCondState( $fieldName, $condState, $excepted ) {
		$form = $this->getNewForm( [
			'normal' => [ 'type' => 'check' ],
			'named' => [ 'type' => 'check', 'name' => 'foo' ],
			'test' => [ 'type' => 'text' ],
			'cloner' => [
				'class' => HTMLFormFieldCloner::class,
				'fields' => [
					'normal' => [ 'type' => 'check' ],
					'named' => [ 'type' => 'check', 'name' => 'foo' ],
					'test' => [ 'type' => 'text' ],
				]
			]
		], [] );
		$field = $form->getField( $fieldName ?? 'test' );
		$wrapped = TestingAccessWrapper::newFromObject( $field );
		if ( $field instanceof HTMLFormFieldCloner ) {
			$field = $wrapped->getFieldsForKey( 0 )['test'];
			$wrapped = TestingAccessWrapper::newFromObject( $field );
		}
		$parsed = $wrapped->parseCondState( $condState );
		$this->assertSame( $excepted, $parsed );
	}

	public static function provideParseCondState() {
		yield 'Normal' => [
			null,
			[ '===', 'normal', '1' ],
			[ '===', 'wpnormal', '1' ],
		];
		yield 'With the \'wp\' prefix' => [
			null,
			[ '===', 'wpnormal', '1' ],
			[ '===', 'wpnormal', '1' ],
		];
		yield 'Named' => [
			null,
			[ '===', 'named', '1' ],
			[ '===', 'foo', '1' ],
		];

		yield 'Normal in cloner' => [
			'cloner',
			[ '===', 'normal', '1' ],
			[ '===', 'wpcloner[0][normal]', '1' ],
		];
		yield 'Named in cloner' => [
			'cloner',
			[ '===', 'named', '1' ],
			[ '===', 'wpcloner[0][foo]', '1' ],
		];
	}

	public function testNoticeInfo() {
		$form = $this->getNewForm( [
			'withNotice' => [ 'type' => 'check', 'notices' => [ 'a notice' ] ],
			'withoutNotice' => [ 'type' => 'check' ],
		], [] );

		$configWithNotice = $configWithoutNotice = [];
		$form->getField( 'withNotice' )->getOOUI( '' )->getConfig( $configWithNotice );
		$form->getField( 'withoutNotice' )->getOOUI( '' )->getConfig( $configWithoutNotice );

		$this->assertArrayHasKey( 'notices', $configWithNotice );
		$this->assertSame(
			[ 'a notice' ],
			$configWithNotice['notices']
		);
		$this->assertArrayNotHasKey( 'notices', $configWithoutNotice );
	}

	/**
	 * @dataProvider provideCallables
	 */
	public function testValidationCallbacks( callable $callable ) {
		$field = new class( [
			'parent' => $this->getNewForm( [] ),
			'fieldname' => __FUNCTION__,
			'validation-callback' => $callable
		] ) extends HTMLFormField {
			public function getInputHTML( $value ) {
				return '';
			}
		};

		$this->assertTrue( $field->validate( '', [] ) );
	}

	public static function provideCallables() {
		$callable = new class() {
			public function validate( $value, array $fields, HTMLForm $form ): bool {
				return $value || $fields || $form->wasSubmitted();
			}

			public static function validateStatic( $value, array $fields, HTMLForm $form ): bool {
				return $value || $fields || $form->wasSubmitted();
			}

			public function __invoke( ...$values ): bool {
				return self::validateStatic( ...$values );
			}
		};

		return [
			'Closure (short)' => [
				static fn ( $value, array $fields, HTMLForm $form ) => $value || $fields || $form->wasSubmitted()
			],
			'Closure (traditional)' => [
				static function ( $value, array $fields, HTMLForm $form ) {
					return $value || $fields || $form->wasSubmitted();
				}
			],
			'Array' => [ [ $callable, 'validate' ] ],
			'Array (static)' => [ [ get_class( $callable ), 'validateStatic' ] ],
			'String' => [ get_class( $callable ) . '::validateStatic' ],
			'Invokable' => [ $callable ]
		];
	}

	/**
	 * @dataProvider provideValidationResults
	 */
	public function testValidationCallbackResults( $callbackResult, $expected ) {
		$field = new class( [
			'parent' => $this->getNewForm( [] ),
			'fieldname' => __FUNCTION__,
			'validation-callback' => static fn () => $callbackResult
		] ) extends HTMLFormField {
			public function getInputHTML( $value ) {
				return '';
			}
		};

		$this->assertEquals( $expected, $field->validate( '', [] ) );
	}

	public static function provideValidationResults() {
		$ok = ( new Status() )
			->warning( 'test-warning' )
			->setOK( true );

		return [
			'Ok Status' => [ $ok, "<p>⧼test-warning⧽\n</p>" ],
			'Good Status' => [ Status::newGood(), true ],
			'Fatal Status' => [ Status::newFatal( 'test-fatal' ), "<p>⧼test-fatal⧽\n</p>" ],
			'Good StatusValue' => [ StatusValue::newGood(), true ],
			'Fatal StatusValue' => [ Status::newFatal( 'test-fatal' ), "<p>⧼test-fatal⧽\n</p>" ],
			'String' => [ '<strong>Invalid input</strong>', '<strong>Invalid input</strong>' ],
			'True' => [ true, true ],
			'False' => [ false, false ]
		];
	}

	public function testValidationCallbackResultMessage() {
		$message = $this->createMock( Message::class );

		$this->testValidationCallbackResults( $message, $message );
	}

	/**
	 * @dataProvider provideValues
	 */
	public function testValidateWithRequiredNotGiven( $value ) {
		$field = new class( [
			'parent' => $this->getNewForm( [] ),
			'fieldname' => __FUNCTION__,
			'required' => true
		] ) extends HTMLFormField {
			public function getInputHTML( $value ) {
				return '';
			}
		};

		$returnValue = $field->validate( $value, [ 'text' => $value ] );

		$this->assertInstanceOf( Message::class, $returnValue );
		$this->assertEquals( 'htmlform-required', $returnValue->getKey() );
	}

	public static function provideValues() {
		return [
			'Empty string' => [ '' ],
			'False' => [ false ],
			'Null' => [ null ]
		];
	}
}
