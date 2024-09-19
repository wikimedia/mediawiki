<?php

namespace Wikimedia\Tests\DebugInfo;

use PHPUnit\Framework\TestCase;
use stdClass;
use Wikimedia\DebugInfo\DebugInfoTrait;

/**
 * @covers \Wikimedia\DebugInfo\DebugInfoTrait
 * @covers \Wikimedia\DebugInfo\DumpUtils
 */
class DebugInfoTraitTest extends TestCase {
	public function setUp(): void {
		if ( extension_loaded( 'xdebug' ) ) {
			if ( version_compare( phpversion( 'xdebug' ), '3.0.0', '>=' ) ) {
				if ( str_contains( ini_get( 'xdebug.mode' ), 'develop' ) ) {
					$this->markTestSkipped( 'Can\'t run this test with xdebug.mode=develop. ' .
					'Use xdebug.mode=coverage to do test coverage without overloading var_dump.' );
				}
			} else {
				$this->iniSet( 'xdebug.overload_var_dump', 0 );
			}
		}
	}

	public function testVarDump1() {
		$test1 = new Test1;
		ob_start();
		var_dump( $test1 );
		$result = ob_get_clean();
		$format = <<<TEXT
object(Wikimedia\Tests\DebugInfo\Test1)#%d (5) {
  ["privateDump":"Wikimedia\Tests\DebugInfo\Test1":private]=>
  object(stdClass)#%d (0) {
  }
  ["protectedDump":protected]=>
  object(stdClass)#%d (0) {
  }
  ["privateNoDump":"Wikimedia\Tests\DebugInfo\Test1":private]=>
  object(Wikimedia\DebugInfo\Placeholder)#%d (1) {
    ["desc"]=>
    string(%d) "stdClass#%d"
  }
  ["protectedNoDump":protected]=>
  object(Wikimedia\DebugInfo\Placeholder)#%d (1) {
    ["desc"]=>
    string(%d) "stdClass#%d"
  }
  ["scalarDumpAllowed":"Wikimedia\Tests\DebugInfo\Test1":private]=>
  int(1)
}
TEXT;
		$this->assertStringMatchesFormat( trim( $format ), trim( $result ) );
	}

	public function testVarDump2() {
		$test1 = new Test2;
		ob_start();
		var_dump( $test1 );
		$result = trim( ob_get_clean() );

		$oldFormat = <<<TEXT
object(Wikimedia\Tests\DebugInfo\Test2)#%d (9) {
  ["privateDump":"Wikimedia\Tests\DebugInfo\Test2":private]=>
  object(stdClass)#%d (0) {
  }
  ["protected2Dump":protected]=>
  object(stdClass)#%d (0) {
  }
  ["privateNoDump":"Wikimedia\Tests\DebugInfo\Test2":private]=>
  object(Wikimedia\DebugInfo\Placeholder)#%d (1) {
    ["desc"]=>
    string(%d) "stdClass#%d"
  }
  ["protected2NoDump":protected]=>
  object(Wikimedia\DebugInfo\Placeholder)#%d (1) {
    ["desc"]=>
    string(%d) "stdClass#%d"
  }
  ["protectedDump":protected]=>
  object(stdClass)#%d (0) {
  }
  ["protectedNoDump":protected]=>
  object(Wikimedia\DebugInfo\Placeholder)#%d (1) {
    ["desc"]=>
    string(%d) "Wikimedia\DebugInfo\Placeholder#%d"
  }
  ["scalarDumpAllowed":"Wikimedia\Tests\DebugInfo\Test1":private]=>
  int(1)
  ["privateDump":"Wikimedia\Tests\DebugInfo\Test1":private]=>
  object(stdClass)#%d (0) {
  }
  ["privateNoDump":"Wikimedia\Tests\DebugInfo\Test1":private]=>
  object(Wikimedia\DebugInfo\Placeholder)#%d (1) {
    ["desc"]=>
    string(%d) "stdClass#%d"
  }
}
TEXT;

		// Newer versions of PHP have a different order of fields
		$newFormat = <<<TEXT
object(Wikimedia\Tests\DebugInfo\Test2)#%d (9) {
  ["privateDump":"Wikimedia\Tests\DebugInfo\Test1":private]=>
  object(stdClass)#%d (0) {
  }
  ["protectedDump":protected]=>
  object(stdClass)#%d (0) {
  }
  ["privateNoDump":"Wikimedia\Tests\DebugInfo\Test1":private]=>
  object(Wikimedia\DebugInfo\Placeholder)#%d (1) {
    ["desc"]=>
    string(%d) "stdClass#%d"
  }
  ["protectedNoDump":protected]=>
  object(Wikimedia\DebugInfo\Placeholder)#%d (1) {
    ["desc"]=>
    string(%d) "Wikimedia\DebugInfo\Placeholder#%d"
  }
  ["scalarDumpAllowed":"Wikimedia\Tests\DebugInfo\Test1":private]=>
  int(1)
  ["privateDump":"Wikimedia\Tests\DebugInfo\Test2":private]=>
  object(stdClass)#%d (0) {
  }
  ["protected2Dump":protected]=>
  object(stdClass)#%d (0) {
  }
  ["privateNoDump":"Wikimedia\Tests\DebugInfo\Test2":private]=>
  object(Wikimedia\DebugInfo\Placeholder)#%d (1) {
    ["desc"]=>
    string(%d) "stdClass#%d"
  }
  ["protected2NoDump":protected]=>
  object(Wikimedia\DebugInfo\Placeholder)#%d (1) {
    ["desc"]=>
    string(%d) "stdClass#%d"
  }
}
TEXT;

		$this->assertThat(
			trim( $result ),
			$this->logicalOr(
				$this->matches( trim( $oldFormat ) ),
				$this->matches( trim( $newFormat ) )
			)
		);
	}
}

// phpcs:disable MediaWiki.Commenting.PropertyDocumentation
class Test1 {
	use DebugInfoTrait;

	private $privateDump;
	protected $protectedDump;
	/** @noVarDump */
	private $privateNoDump;
	/**
	 * @noVarDump
	 */
	protected $protectedNoDump;
	/** @noVarDump */
	private $scalarDumpAllowed = 1;

	public function __construct() {
		$this->privateDump = new stdClass;
		$this->protectedDump = new stdClass;
		$this->privateNoDump = new stdClass;
		$this->protectedNoDump = new stdClass;
	}
}

class Test2 extends Test1 {
	use DebugInfoTrait;

	private $privateDump;
	protected $protected2Dump;
	/** @noVarDump */
	private $privateNoDump;
	/**
	 * @noVarDump
	 */
	protected $protected2NoDump;

	public function __construct() {
		parent::__construct();
		$this->privateDump = new stdClass;
		$this->protected2Dump = new stdClass;
		$this->privateNoDump = new stdClass;
		$this->protected2NoDump = new stdClass;
	}
}
// phpcs:enable MediaWiki.Commenting.PropertyDocumentation
