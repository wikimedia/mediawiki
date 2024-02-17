<?php

namespace Wikimedia\Tests\Diff;

use Wikimedia\Diff\DiffOp;

/**
 * Class FakeDiffOp used to test abstract class DiffOp
 */
class FakeDiffOp extends DiffOp {

	public function reverse() {
		return null;
	}
}
