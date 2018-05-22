<?php

namespace Wikimedia\ParamValidator;

/**
 * @covers Wikimedia\ParamValidator\TypeDef
 */
class TypeDefTest extends \PHPUnit\Framework\TestCase {

	public function testMisc() {
		$typeDef = $this->getMockBuilder( TypeDef::class )
			->setConstructorArgs( [ new SimpleCallbacks( [] ) ] )
			->getMockForAbstractClass();

		$this->assertSame( [ 'foobar' ], $typeDef->normalizeSettings( [ 'foobar' ] ) );
		$this->assertNull( $typeDef->getEnumValues( 'foobar', [], [] ) );
		$this->assertSame( '123', $typeDef->stringifyValue( 'foobar', 123, [], [] ) );
	}

	public function testGetValue() {
		$options = [ (object)[] ];

		$callbacks = $this->getMockBuilder( Callbacks::class )->getMockForAbstractClass();
		$callbacks->expects( $this->once() )->method( 'getValue' )
			->with(
				$this->identicalTo( 'foobar' ),
				$this->identicalTo( null ),
				$this->identicalTo( $options )
			)
			->willReturn( 'zyx' );

		$typeDef = $this->getMockBuilder( TypeDef::class )
			->setConstructorArgs( [ $callbacks ] )
			->getMockForAbstractClass();

		$this->assertSame(
			'zyx',
			$typeDef->getValue( 'foobar', [ ParamValidator::PARAM_DEFAULT => 'foo' ], $options )
		);
	}

	public function testDescribeSettings() {
		$typeDef = $this->getMockBuilder( TypeDef::class )
			->setConstructorArgs( [ new SimpleCallbacks( [] ) ] )
			->getMockForAbstractClass();

		$this->assertSame(
			[],
			$typeDef->describeSettings(
				'foobar',
				[ ParamValidator::PARAM_TYPE => 'xxx' ],
				[]
			)
		);

		$this->assertSame(
			[
				'default' => '123',
			],
			$typeDef->describeSettings(
				'foobar',
				[ ParamValidator::PARAM_DEFAULT => 123 ],
				[]
			)
		);

		$this->assertSame(
			[
				'default' => [ 'value' => '123' ],
			],
			$typeDef->describeSettings(
				'foobar',
				[ ParamValidator::PARAM_DEFAULT => 123 ],
				[ 'compact' => true ]
			)
		);
	}

}
