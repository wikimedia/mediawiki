<?php

/**
 * @since 1.18
 */
class DummyLinker {

	/**
	 * Use PHP's magic __call handler to transform instance calls to a dummy instance
	 * into static calls to the new Linker for backwards compatibility.
	 *
	 * @param string $fname Name of called method
	 * @param array $args Arguments to the method
	 * @return mixed
	 */
	public function __call( $fname, $args ) {
		return call_user_func_array( [ 'Linker', $fname ], $args );
	}

}
