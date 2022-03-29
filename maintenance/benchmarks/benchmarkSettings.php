<?php
/**
 * Benchmark %MediaWiki hooks.
 *
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
 * @ingroup Benchmark
 */

use MediaWiki\MainConfigSchema;
use MediaWiki\Settings\Config\ArrayConfigBuilder;
use MediaWiki\Settings\Config\NullIniSink;
use MediaWiki\Settings\SettingsBuilder;
use MediaWiki\Settings\Source\PhpSettingsSource;
use MediaWiki\Settings\Source\ReflectionSchemaSource;

require_once __DIR__ . '/../includes/Benchmarker.php';

/**
 * Maintenance script that benchmarks loading of settings files.
 *
 * @ingroup Benchmark
 */
class BenchmarkSettings extends Benchmarker {
	public function __construct() {
		parent::__construct();
		$this->defaultCount = 100;
		$this->addDescription( 'Benchmark loading settings files.' );
	}

	private function newSettingsBuilder() {
		$extReg = new ExtensionRegistry();
		$configBuilder = new ArrayConfigBuilder();
		$phpIniSink = new NullIniSink();
		return new SettingsBuilder( MW_INSTALL_PATH, $extReg, $configBuilder, $phpIniSink, null );
	}

	public function execute() {
		$benches = [];

		$benches['DefaultSettings.php'] = [
			'setup' => static function () {
				// do this once beforehand
				include MW_INSTALL_PATH . '/includes/DefaultSettings.php';
			},
			'function' => static function () {
				include MW_INSTALL_PATH . '/includes/DefaultSettings.php';
			}
		];

		$benches['DefaultSettings.php + config-merge-strategies.php'] = [
			'setup' => static function () {
				// do this once beforehand
				include MW_INSTALL_PATH . '/includes/DefaultSettings.php';
			},
			'function' => function () {
				include MW_INSTALL_PATH . '/includes/DefaultSettings.php';
				$settingsBuilder = $this->newSettingsBuilder();
				$settingsBuilder->load(
					new PhpSettingsSource(
						MW_INSTALL_PATH . '/includes/config-merge-strategies.php'
					)
				);
				$settingsBuilder->apply();
			}
		];

		$benches['config-schema.php'] = [
			'function' => function () {
				$settingsBuilder = $this->newSettingsBuilder();
				$settingsBuilder->load(
					new PhpSettingsSource( MW_INSTALL_PATH . '/includes/config-schema.php' )
				);
				$settingsBuilder->apply();
			}
		];

		$benches['MainConfigSchema::class'] = [
			'function' => function () {
				$settingsBuilder = $this->newSettingsBuilder();
				$settingsBuilder->load( new ReflectionSchemaSource( MainConfigSchema::class ) );
				$settingsBuilder->apply();
			}
		];

		$this->bench( $benches );
	}
}

$maintClass = BenchmarkSettings::class;
require_once RUN_MAINTENANCE_IF_MAIN;
