<?php

namespace MediaWiki\Logger;

class ConsoleSpi implements Spi {
	public function __construct( $config = [] ) {
	}

	public function getLogger( $channel ) {
		return new ConsoleLogger( $channel );
	}
}
