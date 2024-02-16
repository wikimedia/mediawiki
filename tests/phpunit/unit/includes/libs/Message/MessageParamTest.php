<?php

namespace Wikimedia\Tests\Message;

use PHPUnit\Framework\TestCase;
use stdClass;
use Wikimedia\Message\MessageParam;
use Wikimedia\Message\ParamType;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \Wikimedia\Message\MessageParam
 */
class MessageParamTest extends TestCase {

	public function testGetType() {
		$mp = $this->getMockForAbstractClass( MessageParam::class );
		TestingAccessWrapper::newFromObject( $mp )->type = ParamType::RAW;

		$this->assertSame( ParamType::RAW, $mp->getType() );
	}

	public function testGetValue() {
		$dummy = new stdClass;

		$mp = $this->getMockForAbstractClass( MessageParam::class );
		TestingAccessWrapper::newFromObject( $mp )->value = $dummy;

		$this->assertSame( $dummy, $mp->getValue() );
	}

}
