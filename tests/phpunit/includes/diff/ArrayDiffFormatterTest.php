<?php

/**
 * @author Addshore
 *
 * @group Diff
 */
class ArrayDiffFormatterTest extends MediaWikiTestCase {

	/**
	 * @param Diff $input
	 * @param array $expectedOutput
	 * @dataProvider provideTestFormat
	 * @covers ArrayDiffFormatter::format
	 */
	public function testFormat( $input, $expectedOutput ) {
		$instance = new ArrayDiffFormatter();
		$output = $instance->format( $input );
		$this->assertEquals( $expectedOutput, $output );
	}

	private function getMockDiff( $edits ) {
		$diff = $this->getMockBuilder( Diff::class )
			->disableOriginalConstructor()
			->getMock();
		$diff->expects( $this->any() )
			->method( 'getEdits' )
			->will( $this->returnValue( $edits ) );
		return $diff;
	}

	private function getMockDiffOp( $type = null, $orig = [], $closing = [] ) {
		$diffOp = $this->getMockBuilder( DiffOp::class )
			->disableOriginalConstructor()
			->getMock();
		$diffOp->expects( $this->any() )
			->method( 'getType' )
			->will( $this->returnValue( $type ) );
		$diffOp->expects( $this->any() )
			->method( 'getOrig' )
			->will( $this->returnValue( $orig ) );
		if ( $type === 'change' ) {
			$diffOp->expects( $this->any() )
				->method( 'getClosing' )
				->with( $this->isType( 'integer' ) )
				->will( $this->returnCallback( function () {
					return 'mockLine';
				} ) );
		} else {
			$diffOp->expects( $this->any() )
				->method( 'getClosing' )
				->will( $this->returnValue( $closing ) );
		}
		return $diffOp;
	}

	public function provideTestFormat() {
		$emptyArrayTestCases = [
			$this->getMockDiff( [] ),
			$this->getMockDiff( [ $this->getMockDiffOp( 'add' ) ] ),
			$this->getMockDiff( [ $this->getMockDiffOp( 'delete' ) ] ),
			$this->getMockDiff( [ $this->getMockDiffOp( 'change' ) ] ),
			$this->getMockDiff( [ $this->getMockDiffOp( 'copy' ) ] ),
			$this->getMockDiff( [ $this->getMockDiffOp( 'FOOBARBAZ' ) ] ),
			$this->getMockDiff( [ $this->getMockDiffOp( 'add', 'line' ) ] ),
			$this->getMockDiff( [ $this->getMockDiffOp( 'delete', [], [ 'line' ] ) ] ),
			$this->getMockDiff( [ $this->getMockDiffOp( 'copy', [], [ 'line' ] ) ] ),
		];

		$otherTestCases = [];
		$otherTestCases[] = [
			$this->getMockDiff( [ $this->getMockDiffOp( 'add', [], [ 'a1' ] ) ] ),
			[ [ 'action' => 'add', 'new' => 'a1', 'newline' => 1 ] ],
		];
		$otherTestCases[] = [
			$this->getMockDiff( [ $this->getMockDiffOp( 'add', [], [ 'a1', 'a2' ] ) ] ),
			[
				[ 'action' => 'add', 'new' => 'a1', 'newline' => 1 ],
				[ 'action' => 'add', 'new' => 'a2', 'newline' => 2 ],
			],
		];
		$otherTestCases[] = [
			$this->getMockDiff( [ $this->getMockDiffOp( 'delete', [ 'd1' ] ) ] ),
			[ [ 'action' => 'delete', 'old' => 'd1', 'oldline' => 1 ] ],
		];
		$otherTestCases[] = [
			$this->getMockDiff( [ $this->getMockDiffOp( 'delete', [ 'd1', 'd2' ] ) ] ),
			[
				[ 'action' => 'delete', 'old' => 'd1', 'oldline' => 1 ],
				[ 'action' => 'delete', 'old' => 'd2', 'oldline' => 2 ],
			],
		];
		$otherTestCases[] = [
			$this->getMockDiff( [ $this->getMockDiffOp( 'change', [ 'd1' ], [ 'a1' ] ) ] ),
			[ [
				'action' => 'change',
				'old' => 'd1',
				'new' => 'mockLine',
				'newline' => 1, 'oldline' => 1
			] ],
		];
		$otherTestCases[] = [
			$this->getMockDiff( [ $this->getMockDiffOp(
				'change',
				[ 'd1', 'd2' ],
				[ 'a1', 'a2' ]
			) ] ),
			[
				[
					'action' => 'change',
					'old' => 'd1',
					'new' => 'mockLine',
					'newline' => 1, 'oldline' => 1
				],
				[
					'action' => 'change',
					'old' => 'd2',
					'new' => 'mockLine',
					'newline' => 2, 'oldline' => 2
				],
			],
		];

		$testCases = [];
		foreach ( $emptyArrayTestCases as $testCase ) {
			$testCases[] = [ $testCase, [] ];
		}
		foreach ( $otherTestCases as $testCase ) {
			$testCases[] = [ $testCase[0], $testCase[1] ];
		}
		return $testCases;
	}

}
