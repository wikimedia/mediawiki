<?php
/**
 * Handles compiling Mustache templates into PHP rendering functions
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
 */
class TemplateParser {
	/**
	 * @var string The path to the Mustache templates
	 */
	protected $templateDir;

	/**
	 * @var callable[] Array of cached rendering functions
	 */
	protected $renderers;

	/**
	 * @var bool Always compile template files
	 */
	protected $forceRecompile = false;

	/**
	 * @param string $templateDir
	 * @param boolean $forceRecompile
	 */
	public function __construct( $templateDir = null, $forceRecompile = false ) {
		$this->templateDir = $templateDir ? $templateDir : __DIR__.'/templates';
		$this->forceRecompile = $forceRecompile;
	}

	/**
	 * Constructs the location of the the source Mustache template
	 * @param string $templateName The name of the template
	 * @return string
	 * @throws Exception Disallows upwards directory traversal via $templateName
	 */
	public function getTemplateFilename( $templateName ) {
		// Prevent upwards directory traversal using same methods as Title::secureAndSplit
		if (
			strpos( $templateName, '.' ) !== false &&
			(
				$templateName === '.' || $templateName === '..' ||
				strpos( $templateName, './' ) === 0 ||
				strpos( $templateName, '../' ) === 0 ||
				strpos( $templateName, '/./' ) !== false ||
				strpos( $templateName, '/../' ) !== false ||
				substr( $templateName, -2 ) === '/.' ||
				substr( $templateName, -3 ) === '/..'
			)
		) {
			throw new Exception( "Malformed \$templateName: $templateName" );
		}

		return "{$this->templateDir}/{$templateName}.mustache";
	}

	/**
	 * Returns a given template function if found, otherwise throws an exception.
	 * @param string $templateName The name of the template (without file suffix)
	 * @return Function
	 * @throws Exception
	 */
	public function getTemplate( $templateName ) {
		global $wgSecretKey;

		// If a renderer has already been defined for this template, reuse it
		if ( isset( $this->renderers[$templateName] ) ) {
			return $this->renderers[$templateName];
		}

		$filename = $this->getTemplateFilename( $templateName );

		if ( !file_exists( $filename ) ) {
			throw new Exception( "Could not locate template: {$filename}" );
		}

		// Read the template file
		$fileContents = file_get_contents( $filename );

		// Generate a quick hash for cache invalidation
		$fastHash = md5( $fileContents );

		// See if the compiled PHP code is stored in cache.
		// CACHE_ACCEL throws an exception if no suitable object cache is present, so fall
		// back to CACHE_ANYTHING.
		try {
			$cache = wfGetCache( CACHE_ACCEL );
		} catch ( Exception $e ) {
			$cache = wfGetCache( CACHE_ANYTHING );
		}
		$key = wfMemcKey( 'template', $templateName, $fastHash );
		$code = $this->forceRecompile ? null : $cache->get( $key );

		if ( !$code ) {
			// Compile the template into PHP code
			$code = self::compile( $fileContents );

			if ( !$code ) {
				throw new Exception( "Could not compile template: {$filename}" );
			}

			// Strip the "<?php" added by lightncandy so that it can be eval()ed
			if ( substr( $code, 0, 5 ) === '<?php' ) {
				$code = substr( $code, 5 );
			}

			$renderer = eval( $code );

			// Prefix the code with a keyed hash (64 hex chars) as an integrity check
			$code = hash_hmac( 'sha256', $code, $wgSecretKey ) . $code;

			// Cache the compiled PHP code
			$cache->set( $key, $code );
		} else {
			// Verify the integrity of the cached PHP code
			$keyedHash = substr( $code, 0, 64 );
			$code = substr( $code, 64 );
			if ( $keyedHash === hash_hmac( 'sha256', $code, $wgSecretKey ) ) {
				$renderer = eval( $code );
			} else {
				throw new Exception( "Template failed integrity check: {$filename}" );
			}
		}

		return $this->renderers[$templateName] = $renderer;
	}

	/**
	 * Compile the Mustache code into PHP code using LightnCandy
	 * @param string $code Mustache code
	 * @return string PHP code
	 * @throws Exception
	 */
	public static function compile( $code ) {
		if ( !class_exists( 'LightnCandy' ) ) {
			throw new Exception( 'LightnCandy class not defined' );
		}
		return LightnCandy::compile(
			$code,
			array(
				// Do not add more flags here without discussion.
				// If you do add more flags, be sure to update unit tests as well.
				'flags' => LightnCandy::FLAG_ERROR_EXCEPTION
			)
		);
	}

	/**
	 * Returns HTML for a given template by calling the template function with the given args
	 * @param string $templateName The name of the template
	 * @param mixed $args
	 * @param array $scopes
	 * @return string
	 */
	public function processTemplate( $templateName, $args, array $scopes = array() ) {
		$template = $this->getTemplate( $templateName );
		return call_user_func( $template, $args, $scopes );
	}
}
