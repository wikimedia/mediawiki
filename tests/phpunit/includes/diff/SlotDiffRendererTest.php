<?php
use Wikimedia\Assert\ParameterTypeException;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers SlotDiffRenderer
 */
class SlotDiffRendererTest extends \PHPUnit\Framework\TestCase {

	/**
	 * @dataProvider provideNormalizeContents
	 */
	public function testNormalizeContents(
		$oldContent, $newContent, $allowedClasses,
		$expectedOldContent, $expectedNewContent, $expectedExceptionClass
	) {
		$slotDiffRenderer = $this->getMockBuilder( SlotDiffRenderer::class )
			->getMock();
		try {
			// __call needs help deciding which parameter to take by reference
			call_user_func_array( [ TestingAccessWrapper::newFromObject( $slotDiffRenderer ),
				'normalizeContents' ], [ &$oldContent, &$newContent, $allowedClasses ] );
			$this->assertEquals( $expectedOldContent, $oldContent );
			$this->assertEquals( $expectedNewContent, $newContent );
		} catch ( Exception $e ) {
			if ( !$expectedExceptionClass ) {
				throw $e;
			}
			$this->assertInstanceOf( $expectedExceptionClass, $e );
		}
	}

	public function provideNormalizeContents() {
		return [
			'both null' => [ null, null, null, null, null, InvalidArgumentException::class ],
			'left null' => [
				null, new WikitextContent( 'abc' ), null,
				new WikitextContent( '' ), new WikitextContent( 'abc' ), null,
			],
			'right null' => [
				new WikitextContent( 'def' ), null, null,
				new WikitextContent( 'def' ), new WikitextContent( '' ), null,
			],
			'type filter' => [
				new WikitextContent( 'abc' ), new WikitextContent( 'def' ), WikitextContent::class,
				new WikitextContent( 'abc' ), new WikitextContent( 'def' ), null,
			],
			'type filter (subclass)' => [
				new WikitextContent( 'abc' ), new WikitextContent( 'def' ), TextContent::class,
				new WikitextContent( 'abc' ), new WikitextContent( 'def' ), null,
			],
			'type filter (null)' => [
				new WikitextContent( 'abc' ), null, TextContent::class,
				new WikitextContent( 'abc' ), new WikitextContent( '' ), null,
			],
			'type filter failure (left)' => [
				new TextContent( 'abc' ), new WikitextContent( 'def' ), WikitextContent::class,
				null, null, ParameterTypeException::class,
			],
			'type filter failure (right)' => [
				new WikitextContent( 'abc' ), new TextContent( 'def' ), WikitextContent::class,
				null, null, ParameterTypeException::class,
			],
			'type filter (array syntax)' => [
				new WikitextContent( 'abc' ), new JsonContent( 'def' ),
				[ JsonContent::class, WikitextContent::class ],
				new WikitextContent( 'abc' ), new JsonContent( 'def' ), null,
			],
			'type filter failure (array syntax)' => [
				new WikitextContent( 'abc' ), new CssContent( 'def' ),
				[ JsonContent::class, WikitextContent::class ],
				null, null, ParameterTypeException::class,
			],
		];
	}

}
