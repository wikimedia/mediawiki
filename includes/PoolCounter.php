<?php

abstract class PoolCounter {
	public static function factory( $type, $key ) {
		global $wgPoolCounterConf;
		if ( !isset( $wgPoolCounterConf[$type] ) ) {
			return new PoolCounter_Stub;
		}
		$conf = $wgPoolCounterConf[$type];
		$class = $conf['class'];
		return new $class( $conf, $type, $key );
	}

	abstract public function acquire();
	abstract public function release();
	abstract public function wait();

	public function executeProtected( $mainCallback, $dirtyCallback = false ) {
		$status = $this->acquire();
		if ( !$status->isOK() ) {
			return $status;
		}
		if ( !empty( $status->value['overload'] ) ) {
			# Overloaded. Try a dirty cache entry.
			if ( $dirtyCallback ) {
				if ( call_user_func( $dirtyCallback ) ) {
					$this->release();
					return Status::newGood();
				}
			}

			# Wait for a thread
			$status = $this->wait();
			if ( !$status->isOK() ) {
				$this->release();
				return $status;
			}
		}
		# Call the main callback
		call_user_func( $mainCallback );
		return $this->release();
	}
}

class PoolCounter_Stub extends PoolCounter {
	public function acquire() {
		return Status::newGood();
	}

	public function release() {
		return Status::newGood();
	}

	public function wait() {
		return Status::newGood();
	}

	public function executeProtected( $mainCallback, $dirtyCallback = false ) {
		call_user_func( $mainCallback );
		return Status::newGood();
	}
}


