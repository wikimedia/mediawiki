<?php

/**
 * Reads an installed.json file and provides accessors to get what is
 * installed
 *
 * @since 1.27
 */
class ComposerInstalled {

	/**
	 * @param string $location
	 */
	public function __construct( $location ) {
		$this->contents = json_decode( file_get_contents( $location ), true );
	}

	/**
	 * Dependencies currently installed according to installed.json
	 *
	 * @return array
	 */
	public function getInstalledDependencies() {
		$deps = [];
		foreach ( $this->contents as $installed ) {
			$deps[$installed['name']] = [
				'version' => ComposerJson::normalizeVersion( $installed['version'] ),
				'type' => $installed['type'],
				'licenses' => isset( $installed['license'] ) ? $installed['license'] : [],
				'authors' => isset( $installed['authors'] ) ? $installed['authors'] : [],
				'description' => isset( $installed['description'] ) ? $installed['description']: '',
			];
		}

		ksort( $deps );
		return $deps;
	}
}
