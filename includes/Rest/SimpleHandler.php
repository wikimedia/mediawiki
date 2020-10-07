<?php

namespace MediaWiki\Rest;

/**
 * A handler base class which unpacks parameters from the path template and
 * passes them as formal parameters to run().
 *
 * run() must be declared in the subclass. It cannot be declared as abstract
 * here because it has a variable parameter list.
 *
 * @stable to extend
 * @package MediaWiki\Rest
 */
abstract class SimpleHandler extends Handler {
	public function execute() {
		$paramSettings = $this->getParamSettings();
		$validatedParams = $this->getValidatedParams();
		$unvalidatedParams = [];
		$params = [];
		foreach ( $this->getRequest()->getPathParams() as $name => $value ) {
			$source = $paramSettings[$name][self::PARAM_SOURCE] ?? 'unknown';
			if ( $source !== 'path' ) {
				$unvalidatedParams[] = $name;
				$params[] = $value;
			} else {
				$params[] = $validatedParams[$name];
			}
		}

		if ( $unvalidatedParams ) {
			throw new \LogicException(
				'Path parameters were not validated: ' . implode( ', ', $unvalidatedParams )
			);
		}

		// @phan-suppress-next-line PhanUndeclaredMethod
		return $this->run( ...$params );
	}
}
