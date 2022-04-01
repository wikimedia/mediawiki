<?php
/**
 * @defgroup Benchmark Benchmark
 * @ingroup  Maintenance
 */

/**
 * Trait for scripts generating files based on the config schema.
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
 * @ingroup Config
 */

use MediaWiki\MainConfigSchema;
use MediaWiki\Settings\Source\ReflectionSchemaSource;

/**
 * Trait for scripts generating files based on the config schema.
 * @ingroup Config
 * @since 1.39
 */
trait ConfigSchemaDerivativeTrait {

	/**
	 * Loads a template and injects the generated content.
	 *
	 * @param string $templatePath
	 * @param string $generatedContent
	 *
	 * @return string The template's content with the generated content injected.
	 */
	private function processTemplate( $templatePath, $generatedContent ): string {
		$oldContent = file_get_contents( $templatePath );

		// avoid extra line breaks, indentation, etc.
		$generatedContent = trim( $generatedContent );

		return preg_replace_callback(
			'/\{\{[-\w]+\}\}/',
			static function ( $match ) use ( $generatedContent ) {
				return $generatedContent;
			},
			$oldContent
		);
	}

	/**
	 * Loads the config schema from the MainConfigSchema class.
	 *
	 * @return array An associative array with a single key, 'config-schema',
	 *         containing the config schema definition.
	 */
	private function loadSettingsSource(): array {
		$source = new ReflectionSchemaSource( MainConfigSchema::class, true );
		$settings = $source->load();
		return $settings;
	}

	/**
	 * Loads the config schema from the MainConfigSchema class.
	 *
	 * @return array the config schema definition.
	 */
	private function loadSchema(): array {
		return $this->loadSettingsSource()['config-schema'];
	}

	/**
	 * @param string $defaultPath
	 * @param string $content
	 */
	private function writeOutput( $defaultPath, $content ) {
		$path = $this->getOutputPath( $defaultPath );

		// ensure a single line break at the end of the file
		$content = trim( $content ) . "\n";

		file_put_contents( $path, $content );
	}

	/**
	 * @param string $default The default output path
	 *
	 * @return string
	 */
	private function getOutputPath( $default ): string {
		$outputPath = $this->getOption( 'output', $default );
		if ( $outputPath === '-' ) {
			$outputPath = 'php://stdout';
		}
		return $outputPath;
	}

	/**
	 * Stub for method supplied by the Maintenance base class.
	 *
	 * @param string $name The name of the param
	 * @param mixed|null $default Anything you want, default null
	 * @return mixed
	 * @return-taint none
	 */
	abstract protected function getOption( $name, $default = null );

}
