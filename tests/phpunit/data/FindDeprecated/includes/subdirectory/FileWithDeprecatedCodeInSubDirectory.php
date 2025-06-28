<?php

namespace MediaWiki\Tests\Data\FindDeprecated;

class FileWithDeprecatedCodeInSubDirectory {
	/** @deprecated Since 1.42 */
	public function testMethodOne() {
		wfDeprecated( __METHOD__, '1.42' );
		return 'deprecated';
	}

	public function testMethodTwo(): string {
		return 'not deprecated';
	}
}
