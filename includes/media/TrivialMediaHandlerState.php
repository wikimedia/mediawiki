<?php

/**
 * Trivial implementation of MediaHandlerState.
 *
 * @since 1.37
 */
class TrivialMediaHandlerState implements MediaHandlerState {
	/** @var array */
	private $state = [];

	public function getHandlerState( string $key ) {
		return $this->state[$key] ?? null;
	}

	public function setHandlerState( string $key, $value ) {
		$this->state[$key] = $value;
	}
}
