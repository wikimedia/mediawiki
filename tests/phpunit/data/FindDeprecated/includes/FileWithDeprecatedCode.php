<?php

namespace MediaWiki\Tests\Data\FindDeprecated;

class FileWithDeprecatedCode {
	/** @deprecated Since 1.43 */
	public function testMethodOne() {
		return 'abc';
	}

	/** @deprecated 1.44 */
	public static function testMethodTwo() {
		wfDeprecated( __METHOD__, '1.44' );
		return 'def';
	}

	public function testMethodThree(): string {
		return 'not deprecated';
	}
}
