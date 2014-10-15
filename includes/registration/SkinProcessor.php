<?php

class SkinProcessor extends ExtensionProcessor {

	/**
	 * @var SkinFactory
	 */
	private $skinFactory;

	public function __construct( SkinFactory $skinFactory ) {
		$this->skinFactory = $skinFactory;
	}

	public function processInfo( $path, array $info ) {
		parent::processInfo( $path, $info );
		if ( isset( $info['skinClass'] ) ) {
			$class = $info['skinClass'];
			$this->skinFactory->register( $info['skinName'], $info['name'], function() use ( $class ) {
				return new $class;
			} );
		} elseif ( isset( $info['skinFactory'] ) ) {
			$this->skinFactory->register( $info['skinName'], $info['name'], $info['skinFactory'] );
		}
	}
}
