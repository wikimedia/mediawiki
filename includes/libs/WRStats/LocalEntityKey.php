<?php

namespace Wikimedia\WRStats;

/**
 * Entity key with isGlobal=false
 *
 * @newable
 * @since 1.39
 */
class LocalEntityKey extends EntityKey {
	/** @inheritDoc */
	public function isGlobal() {
		return false;
	}
}
