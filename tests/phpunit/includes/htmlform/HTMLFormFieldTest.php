<?php

use MediaWiki\Request\FauxRequest;
use MediaWiki\Title\Title;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers HTMLFormField
 */
class HTMLFormFieldTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	public function getNewForm( $descriptor, $requestData ) {
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
	 * @covers HTMLFormField::isHidden
	 * @covers HTMLFormField::isDisabled
	 * @covers HTMLFormField::checkStateRecurse
	 * @covers HTMLFormField::validateCondState
	 * @covers HTMLFormField::getNearestField
	 * @covers HTMLFormField::getNearestFieldValue
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

	public function provideCondState() {
		yield 'Field hidden if "check" field is checked' => [
			'fieldInfo' => [
				'text1' => [ 'hide-if' => [ '===', 'check1', '1' ] ],
			],
			'requestData' => [
				'wpcheck1' => '1',
			],
			'callback' => function ( $form, $fieldData ) {
				$this->assertTrue( $form->getField( 'text1' )->isHidden( $fieldData ) );
			}
		];
		yield 'Field hidden if "check" field is not checked' => [
			'fieldInfo' => [
				'text1' => [ 'hide-if' => [ '===', 'check1', '' ] ],
			],
			'requestData' => [],
			'callback' => function ( $form, $fieldData ) {
				$this->assertTrue( $form->getField( 'text1' )->isHidden( $fieldData ) );
			}
		];
		yield 'Field not hidden if "check" field is not checked' => [
			'fieldInfo' => [
				'text1' => [ 'hide-if' => [ '===', 'check1', '1' ] ],
			],
			'requestData' => [],
			'callback' => function ( $form, $fieldData ) {
				$this->assertFalse( $form->getField( 'text1' )->isHidden( $fieldData ) );
			}
		];
		yield 'Field hidden if "check" field (invert) is checked' => [
			'fieldInfo' => [
				'text1' => [ 'hide-if' => [ '===', 'check2', '1' ] ],
			],
			'requestData' => [
				'wpcheck2' => '1',
			],
			'callback' => function ( $form, $fieldData ) {
				$this->assertTrue( $form->getField( 'text1' )->isHidden( $fieldData ) );
			}
		];
		yield 'Field hidden if "check" field (invert) is not checked' => [
			'fieldInfo' => [
				'text1' => [ 'hide-if' => [ '!==', 'check2', '1' ] ],
			],
			'requestData' => [],
			'callback' => function ( $form, $fieldData ) {
				$this->assertTrue( $form->getField( 'text1' )->isHidden( $fieldData ) );
			}
		];
		yield 'Field not hidden if "check" field (invert) is checked' => [
			'fieldInfo' => [
				'text1' => [ 'hide-if' => [ '!==', 'check2', '1' ] ],
			],
			'requestData' => [
				'wpcheck2' => '1',
			],
			'callback' => function ( $form, $fieldData ) {
				$this->assertFalse( $form->getField( 'text1' )->isHidden( $fieldData ) );
			}
		];
		yield 'Field hidden if "select" field has value' => [
			'fieldInfo' => [
				'text1' => [ 'hide-if' => [ '===', 'select1', 'a' ] ],
			],
			'requestData' => [
				'wpselect1' => 'a',
			],
			'callback' => function ( $form, $fieldData ) {
				$this->assertTrue( $form->getField( 'text1' )->isHidden( $fieldData ) );
			}
		];
		yield 'Field hidden if "text" field has value' => [
			'fieldInfo' => [
				'select1' => [ 'hide-if' => [ '===', 'text1', 'hello' ] ],
			],
			'requestData' => [
				'wptext1' => 'hello',
			],
			'callback' => function ( $form, $fieldData ) {
				$this->assertTrue( $form->getField( 'select1' )->isHidden( $fieldData ) );
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
			'callback' => function ( $form, $fieldData ) {
				$this->assertTrue( $form->getField( 'text1' )->isHidden( $fieldData ) );
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
			'callback' => function ( $form, $fieldData ) {
				$this->assertTrue( $form->getField( 'text1' )->isHidden( $fieldData ) );
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
			'callback' => function ( $form, $fieldData ) {
				$this->assertTrue( $form->getField( 'text1' )->isHidden( $fieldData ) );
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
			'callback' => function ( $form, $fieldData ) {
				$this->assertTrue( $form->getField( 'text1' )->isHidden( $fieldData ) );
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
			'callback' => function ( $form, $fieldData ) {
				$this->assertTrue( $form->getField( 'text1' )->isHidden( $fieldData ) );
			}
		];

		yield 'Invalid conditional specification (unsupported)' => [
			'fieldInfo' => [
				'text1' => [ 'hide-if' => [ '>', 'test1', '10' ] ],
			],
			'requestData' => [],
			'callback' => null,
			'exception' => [ MWException::class, '/Unknown operation/' ],
		];
		yield 'Invalid conditional specification (NOT)' => [
			'fieldInfo' => [
				'text1' => [ 'hide-if' => [ 'NOT', '===', 'check1', '1' ] ],
			],
			'requestData' => [],
			'callback' => null,
			'exception' => [ MWException::class, '/NOT takes exactly one parameter/' ],
		];
		yield 'Invalid conditional specification (AND/OR/NAND/NOR)' => [
			'fieldInfo' => [
				'text1' => [ 'hide-if' => [ 'AND', '===', 'check1', '1' ] ],
			],
			'requestData' => [],
			'callback' => null,
			'exception' => [ MWException::class, '/Expected array, found string/' ],
		];
		yield 'Invalid conditional specification (===/!==) 1' => [
			'fieldInfo' => [
				'text1' => [ 'hide-if' => [ '===', 'check1' ] ],
			],
			'requestData' => [],
			'callback' => null,
			'exception' => [ MWException::class, '/=== takes exactly two parameters/' ],
		];
		yield 'Invalid conditional specification (===/!==) 2' => [
			'fieldInfo' => [
				'text1' => [ 'hide-if' => [ '===', [ '===', 'check1', '1' ], '1' ] ],
			],
			'requestData' => [],
			'callback' => null,
			'exception' => [ MWException::class, '/Parameters for === must be strings/' ],
		];

		yield 'Field disabled if "check" field is checked' => [
			'fieldInfo' => [
				'text1' => [ 'disable-if' => [ '===', 'check1', '1' ] ],
			],
			'requestData' => [
				'wpcheck1' => '1',
			],
			'callback' => function ( $form, $fieldData ) {
				$this->assertTrue( $form->getField( 'text1' )->isDisabled( $fieldData ) );
			}
		];
		yield 'Field disabled if hidden' => [
			'fieldInfo' => [
				'text1' => [ 'hide-if' => [ '===', 'check1', '1' ] ],
			],
			'requestData' => [
				'wpcheck1' => '1',
			],
			'callback' => function ( $form, $fieldData ) {
				$this->assertTrue( $form->getField( 'text1' )->isDisabled( $fieldData ) );
			}
		];

		yield 'Field disabled even the field it relied on is named' => [
			'fieldInfo' => [
				'text1' => [ 'disable-if' => [ '===', 'check3', '1' ] ],
			],
			'requestData' => [
				'foo' => '1',
			],
			'callback' => function ( $form, $fieldData ) {
				$this->assertTrue( $form->getField( 'text1' )->isDisabled( $fieldData ) );
			}
		];
		yield 'Field disabled even the \'wp\' prefix is used (back-compat)' => [
			'fieldInfo' => [
				'text1' => [ 'disable-if' => [ '===', 'wpcheck1', '1' ] ],
			],
			'requestData' => [
				'wpcheck1' => '1',
			],
			'callback' => function ( $form, $fieldData ) {
				$this->assertTrue( $form->getField( 'text1' )->isDisabled( $fieldData ) );
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
			'callback' => function ( $form, $fieldData ) {
				$this->assertTrue( $this->getFieldInCloner( $form, 'cloner', 0, 'check2' )
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
			'callback' => function ( $form, $fieldData ) {
				$this->assertTrue( $this->getFieldInCloner( $form, 'cloner', 0, 'check1' )
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
			'callback' => function ( $form, $fieldData ) {
				$this->assertTrue( $this->getFieldInCloner( $form, 'cloner', 0, 'check1' )
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
			'callback' => function ( $form, $fieldData ) {
				$this->assertTrue( $this->getFieldInCloner( $form, 'cloner', 0, 'check1' )
					->isDisabled( $fieldData ) );
			}
		];
	}

	private function getFieldInCloner( $form, $clonerName, $index, $fieldName ) {
		$cloner = TestingAccessWrapper::newFromObject( $form->getField( $clonerName ) );
		return $cloner->getFieldsForKey( $index )[$fieldName];
	}

	/**
	 * @covers HTMLFormField::parseCondState
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

	public function provideParseCondState() {
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
}
