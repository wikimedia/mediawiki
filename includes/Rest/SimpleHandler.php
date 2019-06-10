<?php

namespace MediaWiki\Rest;

/**
 * A handler base class which unpacks parameters from the path template and
 * passes them as formal parameters to run().
 *
 * run() must be declared in the subclass. It cannot be declared as abstract
 * here because it has a variable parameter list.
 *
 * @package MediaWiki\Rest
 */
class SimpleHandler extends Handler {
	public function execute() {
		$params = array_values( $this->getRequest()->getPathParams() );
		return $this->run( ...$params );
	}
}
