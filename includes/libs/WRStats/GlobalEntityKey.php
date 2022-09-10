<?php

namespace Wikimedia\WRStats;

/**
 * Entity key with global=true
 *
 * @newable
 * @since 1.39
 */
class GlobalEntityKey extends EntityKey {
	public function isGlobal() {
		return true;
	}
}
