<?php

class TestThrowerDummy {
	public function main(): never {
		$this->doFoo();
	}

	private function doFoo(): never {
		$this->getBar();
	}

	private function getBar(): never {
		$this->getQuux();
	}

	private function getQuux(): never {
		throw new Exception( 'Quux failed' );
	}

	public static function getFile(): string {
		return __FILE__;
	}
}
