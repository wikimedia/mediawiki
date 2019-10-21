<?php

use MediaWiki\MediaWikiServices;

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
	 * @var int Compilation flags passed to LightnCandy
	 */
	protected $compileFlags;

	/**
	 * @param string|null $templateDir
	 * @param bool $forceRecompile
	 */
	public function __construct( $templateDir = null, $forceRecompile = false ) {
		$this->templateDir = $templateDir ?: __DIR__ . '/templates';
		$this->forceRecompile = $forceRecompile;

		// Do not add more flags here without discussion.
		// If you do add more flags, be sure to update unit tests as well.
		$this->compileFlags = LightnCandy::FLAG_ERROR_EXCEPTION | LightnCandy::FLAG_MUSTACHELOOKUP;
	}

	/**
	 * Enable/disable the use of recursive partials.
	 * @param bool $enable
	 */
	public function enableRecursivePartials( $enable ) {
		if ( $enable ) {
			$this->compileFlags |= LightnCandy::FLAG_RUNTIMEPARTIAL;
		} else {
			$this->compileFlags &= ~LightnCandy::FLAG_RUNTIMEPARTIAL;
		}
	}

	/**
	 * Constructs the location of the source Mustache template
	 * @param string $templateName The name of the template
	 * @return string
	 * @throws UnexpectedValueException If $templateName attempts upwards directory traversal
	 */
	protected function getTemplateFilename( $templateName ) {
		// Prevent path traversal. Based on Language::isValidCode().
		// This is for paranoia. The $templateName should never come from
		// untrusted input.
		if (
			strcspn( $templateName, ":/\\\000&<>'\"%" ) !== strlen( $templateName )
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
		$templateKey = $templateName . '|' . $this->compileFlags;

		// If a renderer has already been defined for this template, reuse it
		if ( isset( $this->renderers[$templateKey] ) &&
			is_callable( $this->renderers[$templateKey] )
		) {
			return $this->renderers[$templateKey];
		}

		$filename = $this->getTemplateFilename( $templateName );

		if ( !file_exists( $filename ) ) {
			throw new RuntimeException( "Could not locate template: {$filename}" );
		}

		// Read the template file
		$fileContents = file_get_contents( $filename );

		// Generate a quick hash for cache invalidation
		$fastHash = md5( $this->compileFlags . '|' . $fileContents );

		// Fetch a secret key for building a keyed hash of the PHP code
		$config = MediaWikiServices::getInstance()->getMainConfig();
		$secretKey = $config->get( 'SecretKey' );

		if ( $secretKey ) {
			// See if the compiled PHP code is stored in cache.
			$cache = ObjectCache::getLocalServerInstance( CACHE_ANYTHING );
			$key = $cache->makeKey( 'template', $templateName, $fastHash );
			$code = $this->forceRecompile ? null : $cache->get( $key );

			if ( $code ) {
				// Verify the integrity of the cached PHP code
				$keyedHash = substr( $code, 0, 64 );
				$code = substr( $code, 64 );
				if ( $keyedHash !== hash_hmac( 'sha256', $code, $secretKey ) ) {
					// If the integrity check fails, don't use the cached code
					// We'll update the invalid cache below
					$code = null;
				}
			}
			if ( !$code ) {
				$code = $this->compileForEval( $fileContents, $filename );

				// Prefix the cached code with a keyed hash (64 hex chars) as an integrity check
				$cache->set( $key, hash_hmac( 'sha256', $code, $secretKey ) . $code );
			}
		// If there is no secret key available, don't use cache
		} else {
			$code = $this->compileForEval( $fileContents, $filename );
		}

		$renderer = eval( $code );
		if ( !is_callable( $renderer ) ) {
			throw new RuntimeException( "Requested template, {$templateName}, is not callable" );
		}
		$this->renderers[$templateKey] = $renderer;
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
			[
				'flags' => $this->compileFlags,
				'basedir' => $this->templateDir,
				'fileext' => '.mustache',
			]
		);
	}

	/**
	 * Returns HTML for a given template by calling the template function with the given args
	 *
	 * @code
	 *     echo $templateParser->processTemplate(
	 *         'ExampleTemplate',
	 *         [
	 *             'username' => $user->getName(),
	 *             'message' => 'Hello!'
	 *         ]
	 *     );
	 * @endcode
	 * @param string $templateName The name of the template
	 * @param-taint $templateName exec_misc
	 * @param mixed $args
	 * @param-taint $args none
	 * @param array $scopes
	 * @param-taint $scopes none
	 * @return string
	 */
	public function processTemplate( $templateName, $args, array $scopes = [] ) {
		$template = $this->getTemplate( $templateName );
		return $template( $args, $scopes );
	}
}
