<?php

namespace Wikimedia\Rdbms;

/**
 * Wrapper allowing us to distinguish a blob from a normal string and an array of strings
 * @ingroup Database
 */
interface IBlob {
	/**
	 * @return string
	 */
	public function fetch();
}
