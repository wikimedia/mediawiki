<?php
namespace MediaWiki\Tests\Unit\Installer\Task;

use MediaWiki\Installer\Task\CallbackTask;
use MediaWiki\Installer\Task\TaskList;
use MediaWikiUnitTestCase;
use RuntimeException;

/**
 * @covers \MediaWiki\Installer\Task\TaskList
 */
class TaskListTest extends MediaWikiUnitTestCase {
	public static function provideGetIterator() {
		return [
			'empty' => [
				[],
				[]
			],
			'independent tasks' => [
				[
					[ 'name' => 'a' ],
					[ 'name' => 'b' ],
				],
				[ 'a', 'b' ]
			],
			'dependent tasks' => [
				[
					[ 'name' => 'a', 'after' => 'b' ],
					[ 'name' => 'b', 'after' => 'c' ],
					[ 'name' => 'c' ]
				],
				[ 'c', 'b', 'a' ]
			],
			'circular reference' => [
				[
					[ 'name' => 'a', 'after' => 'b' ],
					[ 'name' => 'b', 'after' => 'c' ],
					[ 'name' => 'c', 'after' => 'a' ],
				],
				null
			],
			'non-existent dependency' => [
				[
					[ 'name' => 'a', 'after' => 'b' ],
					[ 'name' => 'c' ]
				],
				null
			],
			'simple aliases' => [
				[
					[ 'name' => 'a', 'after' => 'b' ],
					[ 'name' => 'real b', 'aliases' => [ 'b' ], 'after' => 'c' ],
					[ 'name' => 'c' ]
				],
				[ 'c', 'real b', 'a' ]
			],
			'non-unique aliases' => [
				[
					[ 'name' => 'a', 'after' => 'c' ],
					[ 'name' => 'b' ],
					[ 'name' => 'c1', 'aliases' => [ 'c' ] ],
					[ 'name' => 'c2', 'aliases' => [ 'c' ] ],
				],
				[ 'c1', 'c2', 'a', 'b' ],
			],
			'non-unique names also work although probably aren\'t a great idea' => [
				[
					[ 'name' => 'a', 'after' => 'b' ],
					[ 'name' => 'b' ],
					[ 'name' => 'b' ],
				],
				[ 'b', 'b', 'a' ]
			]
		];
	}

	/**
	 * @dataProvider provideGetIterator
	 * @param array $specs
	 * @param array $expected
	 */
	public function testGetIterator( $specs, $expected ) {
		$taskList = new TaskList;
		foreach ( $specs as $spec ) {
			$spec += [ 'callback' => null ];
			$taskList->add( new CallbackTask( $spec ) );
		}
		if ( $expected === null ) {
			$this->expectException( RuntimeException::class );
		}
		$result = [];
		foreach ( $taskList as $task ) {
			$result[] = $task->getName();
		}
		$this->assertSame( $expected, $result );
	}
}
