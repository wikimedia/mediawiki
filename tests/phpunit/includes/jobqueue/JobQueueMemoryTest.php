<?php

/**
 * @covers JobQueueMemory
 *
 * @group JobQueue
 *
 * @licence GNU GPL v2+
 * @author Thiemo Mättig
 */
class JobQueueMemoryTest extends PHPUnit_Framework_TestCase {

	public function testGetAllQueuedJobs() {
		$instance = JobQueueMemoryDouble::newInstance( array(
			'wiki' => null,
			'type' => null,
		) );
		$actual = $instance->getAllQueuedJobs();
		$this->assertEquals( new ArrayIterator(), $actual );
	}

}

class JobQueueMemoryDouble extends JobQueueMemory {

	public static function newInstance( array $params ) {
		return new self( $params );
	}

}
