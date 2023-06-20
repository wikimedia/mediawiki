<?php

namespace Wikimedia\Diff;

/**
 * Class FakeDiffOp used to test abstract class DiffOp
 */
class FakeDiffOp extends DiffOp {

	public function reverse() {
		return null;
	}
}
