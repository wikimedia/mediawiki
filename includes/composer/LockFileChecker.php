<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
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
