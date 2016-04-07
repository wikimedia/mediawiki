<?php

namespace MediaWiki\Tidy;

class Html5Internal extends TidyDriverBase {
	private $balancer;
	public function __construct( array $config ) {
		parent::__construct( $config + [
			'strict' => true,
		] );
		$this->balancer = new Balancer( $this->config['strict'] );
	}

	public function tidy( $text ) {
		return $this->balancer->balance( $text );
	}
}
