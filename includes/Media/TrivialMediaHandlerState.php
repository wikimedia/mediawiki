<?php

/**
 * Trivial implementation of MediaHandlerState.
 *
 * @since 1.37
 */
class TrivialMediaHandlerState implements MediaHandlerState {
	/** @var array */
	private $state = [];

	/** @inheritDoc */
	public function getHandlerState( string $key ) {
		return $this->state[$key] ?? null;
	}

	/** @inheritDoc */
	public function setHandlerState( string $key, $value ) {
		$this->state[$key] = $value;
	}
}
