<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Composer;

use Composer\Semver\Semver;
use Wikimedia\Composer\ComposerJson;
use Wikimedia\Composer\ComposerLock;

/**
 * Used to check whether composer-installed dependencies (no-dev) are up-to-date
 *
 * @internal For use by CheckComposerLockUpToDate and Installer
 * @since 1.42
 */
class LockFileChecker {
	private ComposerJson $composerJson;
	private ComposerLock $composerLock;

	public function __construct( ComposerJson $composerJson, ComposerLock $composerLock ) {
		$this->composerJson = $composerJson;
		$this->composerLock = $composerLock;
	}

	/**
	 * @return string[]|null Array of error messages, or null when Composer-installed dependencies are up-to-date.
	 */
	public function check(): ?array {
		$errors = [];
		$requiredButOld = [];
		$requiredButMissing = [];

		$installed = $this->composerLock->getInstalledDependencies();
		foreach ( $this->composerJson->getRequiredDependencies() as $name => $version ) {
			// Not installed at all.
			if ( !isset( $installed[$name] ) ) {
				$requiredButMissing[] = [
					'name' => $name,
					'wantedVersion' => $version
				];
				continue;
			}

			// Installed; need to check it's the right version
			if ( !SemVer::satisfies( $installed[$name]['version'], $version ) ) {
				$requiredButOld[] = [
					'name' => $name,
					'wantedVersion' => $version,
					'suppliedVersion' => $installed[$name]['version']
				];
			}

			// We're happy; loop to the next dependency.
		}

		foreach ( $requiredButOld as [
			"name" => $name,
			"suppliedVersion" => $suppliedVersion,
			"wantedVersion" => $wantedVersion
		] ) {
			$errors[] = "$name: $suppliedVersion installed, $wantedVersion required.";
		}

		foreach ( $requiredButMissing as [
			"name" => $name,
			"wantedVersion" => $wantedVersion
		] ) {
			$errors[] = "$name: not installed, $wantedVersion required.";
		}

		return $errors ?: null;
	}
}
