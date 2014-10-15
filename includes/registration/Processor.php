<?php

abstract class Processor {

	abstract public function processInfo( $path, $info );

	public function callback( $info ) {
		if ( isset( $info['callback'] ) ) {
			call_user_func( $info['callback'] );
		}
	}
}
