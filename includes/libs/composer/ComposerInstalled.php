<?php

/**
 * Reads an installed.json file and provides accessors to get what is
 * installed
 *
 * @since 1.27
 */
class ComposerInstalled {
	/**
	 * @var array[]
	 */
	private $contents;

	/**
	 * @param string $location
	 */
	public function __construct( $location ) {
		$this->contents = json_decode( file_get_contents( $location ), true );
	}

	/**
	 * Dependencies currently installed according to installed.json
	 *
	 * @return array[]
	 */
	public function getInstalledDependencies() {
		// Composer version 2 provides the list of installed packages under the 'packages' key.
		$contents = $this->contents['packages'] ?? $this->contents;

		$deps = [];
		foreach ( $contents as $installed ) {
			$deps[$installed['name']] = [
				'version' => ComposerJson::normalizeVersion( $installed['version'] ),
				'type' => $installed['type'],
				'licenses' => $installed['license'] ?? [],
				'authors' => $installed['authors'] ?? [],
				'description' => $installed['description'] ?? '',
			];
		}

		ksort( $deps );
		return $deps;
	}
}
