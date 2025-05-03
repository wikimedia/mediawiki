<?php

namespace Wikimedia\Tests\Diff;

use MediaWikiUnitTestCase;
use Wikimedia\Diff\ArrayDiffFormatter;
use Wikimedia\Diff\Diff;
use Wikimedia\Diff\DiffOp;

/**
 * @author Addshore
 *
 * @group Diff
 */
class ArrayDiffFormatterTest extends MediaWikiUnitTestCase {

	/**
	 * @dataProvider provideTestFormat
	 * @covers \Wikimedia\Diff\ArrayDiffFormatter::format
	 */
	public function testFormat( $inputSpec, array $expectedOutput ) {
		$input = $this->getMockDiff( $inputSpec );

		$instance = new ArrayDiffFormatter();
		$output = $instance->format( $input );
		$this->assertSame( $expectedOutput, $output );
	}

	private function getMockDiff( array $editsSpec ) {
		$edits = [];
		foreach ( $editsSpec as $edit ) {
			$edits[] = $this->getMockDiffOp( ...$edit );
		}
		$diff = $this->createMock( Diff::class );
		$diff->method( 'getEdits' )
			->willReturn( $edits );
		return $diff;
	}

	private function getMockDiffOp( string $type, $orig = [], array $closing = [] ) {
		$diffOp = $this->createMock( DiffOp::class );
		$diffOp->method( 'getType' )
			->willReturn( $type );
		$diffOp->method( 'getOrig' )
			->willReturn( $orig );
		if ( $type === 'change' ) {
			$diffOp->method( 'getClosing' )
				->with( $this->isType( 'integer' ) )
				->willReturn( 'mockLine' );
		} else {
			$diffOp->method( 'getClosing' )
				->willReturn( $closing );
		}
		return $diffOp;
	}

	public static function provideTestFormat() {
		$emptyArrayTestCases = [
			[],
			[ [ 'add' ] ],
			[ [ 'delete' ] ],
			[ [ 'change' ] ],
			[ [ 'copy' ] ],
			[ [ 'FOOBARBAZ' ] ],
			[ [ 'add', 'line' ] ],
			[ [ 'delete', [], [ 'line' ] ] ],
			[ [ 'copy', [], [ 'line' ] ] ],
		];
		foreach ( $emptyArrayTestCases as $testCase ) {
			yield [ $testCase, [] ];
		}

		yield [
			[ [ 'add', [], [ 'a1' ] ] ],
			[ [ 'action' => 'add', 'new' => 'a1', 'newline' => 1 ] ],
		];
		yield [
			[ [ 'add', [], [ 'a1', 'a2' ] ] ],
			[
				[ 'action' => 'add', 'new' => 'a1', 'newline' => 1 ],
				[ 'action' => 'add', 'new' => 'a2', 'newline' => 2 ],
			],
		];
		yield [
			[ [ 'delete', [ 'd1' ] ] ],
			[ [ 'action' => 'delete', 'old' => 'd1', 'oldline' => 1 ] ],
		];
		yield [
			[ [ 'delete', [ 'd1', 'd2' ] ] ],
			[
				[ 'action' => 'delete', 'old' => 'd1', 'oldline' => 1 ],
				[ 'action' => 'delete', 'old' => 'd2', 'oldline' => 2 ],
			],
		];
		yield [
			[ [ 'change', [ 'd1' ], [ 'a1' ] ] ],
			[ [
				'action' => 'change',
				'old' => 'd1',
				'new' => 'mockLine',
				'oldline' => 1,
				'newline' => 1,
			] ],
		];
		yield [
			[ [
				'change',
				[ 'd1', 'd2' ],
				[ 'a1', 'a2' ]
			] ],
			[
				[
					'action' => 'change',
					'old' => 'd1',
					'new' => 'mockLine',
					'oldline' => 1,
					'newline' => 1,
				],
				[
					'action' => 'change',
					'old' => 'd2',
					'new' => 'mockLine',
					'oldline' => 2,
					'newline' => 2,
				],
			],
		];
	}

}
