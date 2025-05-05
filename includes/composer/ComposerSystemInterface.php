<?php

declare( strict_types = 1 );

namespace MediaWiki\Composer;

/**
 * Wrapper around low-level system functions, so that we can inject mocks
 * when testing the MediaWiki\Composer\ComposerLaunchParallel class.
 *
 * @license GPL-2.0-or-later
 */
class ComposerSystemInterface {

	public function print( string $message ) {
		print( $message );
	}

	public function exit( int $resultCode ) {
		exit( $resultCode );
	}

	public function putFileContents( string $path, string $data ) {
		file_put_contents( $path, $data );
	}

	public function getcwd(): string {
		return getcwd();
	}
}
