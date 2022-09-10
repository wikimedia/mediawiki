<?php

namespace Wikimedia\WRStats;

/**
 * Entity key with global=false
 *
 * @newable
 * @since 1.39
 */
class LocalEntityKey extends EntityKey {
	public function isGlobal() {
		return false;
	}
}
