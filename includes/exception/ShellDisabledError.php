<?php

namespace MediaWiki;

use Exception;

class ShellDisabledError extends Exception {
	public function __construct() {
		parent::__construct( 'Unable to run external programs, proc_open() is disabled' );
	}
}
