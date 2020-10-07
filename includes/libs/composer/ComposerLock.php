<?php

/**
 * Reads a composer.lock file and provides accessors to get
 * its hash and what is installed
 *
 * @since 1.25
 */
class ComposerLock {
	/**
	 * @var array[]
	 * @phan-var array{packages:array{name:string,version:string,type:string,license?:string,authors?:mixed,description?:string}}
	 */
	private $contents;

	/**
	 * @param string $location
	 */
	public function __construct( $location ) {
		$this->contents = json_decode( file_get_contents( $location ), true );
	}

	/**
	 * Dependencies currently installed according to composer.lock
	 *
	 * @return array[]
	 */
	public function getInstalledDependencies() {
		$deps = [];
		foreach ( $this->contents['packages'] as $installed ) {
			$deps[$installed['name']] = [
				'version' => ComposerJson::normalizeVersion( $installed['version'] ),
				'type' => $installed['type'],
				'licenses' => $installed['license'] ?? [],
				'authors' => $installed['authors'] ?? [],
				'description' => $installed['description'] ?? '',
			];
		}

		return $deps;
	}
}
