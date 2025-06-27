<?php

namespace MediaWiki\Tests\Data\FindDeprecated;

/**
 * Class without any deprecated code
 */
class FileWithoutDeprecatedCode {
	/**
	 * This method is not deprecated :D
	 *
	 * @param string $param
	 * @return string
	 */
	public function testMethodOne( string $param ) {
		return trim( $param );
	}

	public function testMethodTwo(): string {
		// Call a wf* method that is not wfDeprecated.
		wfDebug( "test" );
		return "test";
	}
}
