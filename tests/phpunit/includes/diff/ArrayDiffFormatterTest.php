<?php

/**
 * @author Adam Shorland
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
		$diff = $this->getMockBuilder( 'Diff' )
			->disableOriginalConstructor()
			->getMock();
		$diff->expects( $this->any() )
			->method( 'getEdits' )
			->will( $this->returnValue( $edits ) );
		return $diff;
	}

	private function getMockDiffOp( $type = null, $orig = array(), $closing = array() ) {
		$diffOp = $this->getMockBuilder( 'DiffOp' )
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
		$emptyArrayTestCases = array(
			$this->getMockDiff( array() ),
			$this->getMockDiff( array( $this->getMockDiffOp( 'add' ) ) ),
			$this->getMockDiff( array( $this->getMockDiffOp( 'delete' ) ) ),
			$this->getMockDiff( array( $this->getMockDiffOp( 'change' ) ) ),
			$this->getMockDiff( array( $this->getMockDiffOp( 'copy' ) ) ),
			$this->getMockDiff( array( $this->getMockDiffOp( 'FOOBARBAZ' ) ) ),
			$this->getMockDiff( array( $this->getMockDiffOp( 'add', 'line' ) ) ),
			$this->getMockDiff( array( $this->getMockDiffOp( 'delete', array(), array( 'line' ) ) ) ),
			$this->getMockDiff( array( $this->getMockDiffOp( 'copy', array(), array( 'line' ) ) ) ),
		);

		$otherTestCases = array();
		$otherTestCases[] = array(
			$this->getMockDiff( array( $this->getMockDiffOp( 'add', array( ), array( 'a1' ) ) ) ),
			array( array( 'action' => 'add', 'new' => 'a1', 'newline' => 1 ) ),
		);
		$otherTestCases[] = array(
			$this->getMockDiff( array( $this->getMockDiffOp( 'add', array( ), array( 'a1', 'a2' ) ) ) ),
			array(
				array( 'action' => 'add', 'new' => 'a1', 'newline' => 1 ),
				array( 'action' => 'add', 'new' => 'a2', 'newline' => 2 ),
			),
		);
		$otherTestCases[] = array(
			$this->getMockDiff( array( $this->getMockDiffOp( 'delete', array( 'd1' ) ) ) ),
			array( array( 'action' => 'delete', 'old' => 'd1', 'oldline' => 1 ) ),
		);
		$otherTestCases[] = array(
			$this->getMockDiff( array( $this->getMockDiffOp( 'delete', array( 'd1', 'd2' ) ) ) ),
			array(
				array( 'action' => 'delete', 'old' => 'd1', 'oldline' => 1 ),
				array( 'action' => 'delete', 'old' => 'd2', 'oldline' => 2 ),
			),
		);
		$otherTestCases[] = array(
			$this->getMockDiff( array( $this->getMockDiffOp( 'change', array( 'd1' ), array( 'a1' ) ) ) ),
			array( array(
				'action' => 'change',
				'old' => 'd1',
				'new' => 'mockLine',
				'newline' => 1, 'oldline' => 1
			) ),
		);
		$otherTestCases[] = array(
			$this->getMockDiff( array( $this->getMockDiffOp(
				'change',
				array( 'd1', 'd2' ),
				array( 'a1', 'a2' )
			) ) ),
			array(
				array(
					'action' => 'change',
					'old' => 'd1',
					'new' => 'mockLine',
					'newline' => 1, 'oldline' => 1
				),
				array(
					'action' => 'change',
					'old' => 'd2',
					'new' => 'mockLine',
					'newline' => 2, 'oldline' => 2
				),
			),
		);

		$testCases = array();
		foreach ( $emptyArrayTestCases as $testCase ) {
			$testCases[] = array( $testCase, array() );
		}
		foreach ( $otherTestCases as $testCase ) {
			$testCases[] = array( $testCase[0], $testCase[1] );
		}
		return $testCases;
	}

}
