<?php

/**
 * The phpstorm stubs package includes the Memcached class with two parameters and docs saying
 * that they are optional. Phan can not detect this and thus throws an error for a usage with
 * no params. So we have this small stub just for the constructor to allow no params.
 * @see https://secure.php.net/manual/en/memcached.construct.php
 * phpcs:ignoreFile
 */

class Memcached {

	public function __construct() {
	}

}
