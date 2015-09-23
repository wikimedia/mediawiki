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
 * @since 1.25
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
		$this->templateDir = $templateDir ? $templateDir : __DIR__ . '/templates';
		$this->forceRecompile = $forceRecompile;
	}

	/**
	 * Constructs the location of the the source Mustache template
	 * @param string $templateName The name of the template
	 * @return string
	 * @throws UnexpectedValueException Disallows upwards directory traversal via $templateName
	 */
	protected function getTemplateFilename( $templateName ) {
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
			throw new UnexpectedValueException( "Malformed \$templateName: $templateName" );
		}

		return "{$this->templateDir}/{$templateName}.mustache";
	}

	/**
	 * Returns a given template function if found, otherwise throws an exception.
	 * @param string $templateName The name of the template (without file suffix)
	 * @return callable
	 * @throws RuntimeException
	 */
	protected function getTemplate( $templateName ) {
		// If a renderer has already been defined for this template, reuse it
		if ( isset( $this->renderers[$templateName] ) && is_callable( $this->renderers[$templateName] ) ) {
			return $this->renderers[$templateName];
		}

		$filename = $this->getTemplateFilename( $templateName );

		if ( !file_exists( $filename ) ) {
			throw new RuntimeException( "Could not locate template: {$filename}" );
		}

		// Read the template file
		$fileContents = file_get_contents( $filename );

		// Generate a quick hash for cache invalidation
		$fastHash = md5( $fileContents );

		// Fetch a secret key for building a keyed hash of the PHP code
		$config = ConfigFactory::getDefaultInstance()->makeConfig( 'main' );
		$secretKey = $config->get( 'SecretKey' );

		if ( $secretKey ) {
			// See if the compiled PHP code is stored in cache.
			// CACHE_ACCEL throws an exception if no suitable object cache is present, so fall
			// back to CACHE_ANYTHING.
			$cache = ObjectCache::newAccelerator( CACHE_ANYTHING );
			$key = wfMemcKey( 'template', $templateName, $fastHash );
			$code = $this->forceRecompile ? null : $cache->get( $key );

			if ( !$code ) {
				$code = $this->compileForEval( $fileContents, $filename );

				// Prefix the cached code with a keyed hash (64 hex chars) as an integrity check
				$cache->set( $key, hash_hmac( 'sha256', $code, $secretKey ) . $code );
			} else {
				// Verify the integrity of the cached PHP code
				$keyedHash = substr( $code, 0, 64 );
				$code = substr( $code, 64 );
				if ( $keyedHash !== hash_hmac( 'sha256', $code, $secretKey ) ) {
					// Generate a notice if integrity check fails
					trigger_error( "Template failed integrity check: {$filename}" );
				}
			}
		// If there is no secret key available, don't use cache
		} else {
			$code = $this->compileForEval( $fileContents, $filename );
		}

		$renderer = eval( $code );
		if ( !is_callable( $renderer ) ) {
			throw new RuntimeException( "Requested template, {$templateName}, is not callable" );
		}
		$this->renderers[$templateName] = $renderer;
		return $renderer;
	}

	/**
	 * Wrapper for compile() function that verifies successful compilation and strips
	 * out the '<?php' part so that the code is ready for eval()
	 * @param string $fileContents Mustache code
	 * @param string $filename Name of the template
	 * @return string PHP code (without '<?php')
	 * @throws RuntimeException
	 */
	protected function compileForEval( $fileContents, $filename ) {
		// Compile the template into PHP code
		$code = $this->compile( $fileContents );

		if ( !$code ) {
			throw new RuntimeException( "Could not compile template: {$filename}" );
		}

		// Strip the "<?php" added by lightncandy so that it can be eval()ed
		if ( substr( $code, 0, 5 ) === '<?php' ) {
			$code = substr( $code, 5 );
		}

		return $code;
	}

	/**
	 * Compile the Mustache code into PHP code using LightnCandy
	 * @param string $code Mustache code
	 * @return string PHP code (with '<?php')
	 * @throws RuntimeException
	 */
	protected function compile( $code ) {
		if ( !class_exists( 'LightnCandy' ) ) {
			throw new RuntimeException( 'LightnCandy class not defined' );
		}
		return LightnCandy::compile(
			$code,
			array(
				// Do not add more flags here without discussion.
				// If you do add more flags, be sure to update unit tests as well.
				'flags' => LightnCandy::FLAG_ERROR_EXCEPTION,
				'basedir' => $this->templateDir,
				'fileext' => '.mustache',
			)
		);
	}

	/**
	 * Returns HTML for a given template by calling the template function with the given args
	 *
	 * @code
	 *     echo $templateParser->processTemplate(
	 *         'ExampleTemplate',
	 *         array(
	 *             'username' => $user->getName(),
	 *             'message' => 'Hello!'
	 *         )
	 *     );
	 * @endcode
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
