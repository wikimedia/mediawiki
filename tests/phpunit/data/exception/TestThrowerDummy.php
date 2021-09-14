<?php

class TestThrowerDummy {
	public function main() {
		$this->doFoo();
	}

	private function doFoo() {
		$this->getBar();
	}

	private function getBar() {
		$this->getQuux();
	}

	private function getQuux() {
		throw new Exception( 'Quux failed' );
	}

	public static function getFile(): string {
		return __FILE__;
	}
}
