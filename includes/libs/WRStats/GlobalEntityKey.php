<?php

namespace Wikimedia\WRStats;

/**
 * Entity key with isGlobal=true
 *
 * @newable
 * @since 1.39
 */
class GlobalEntityKey extends EntityKey {
	/** @inheritDoc */
	public function isGlobal() {
		return true;
	}
}
