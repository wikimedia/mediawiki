<?php

/**
 * @license GPL-2.0-or-later
 */

namespace MediaWiki\Html;

use Exception;
use FileContentsHasher;
use LightnCandy\LightnCandy;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use RuntimeException;
use UnexpectedValueException;
use Wikimedia\ObjectCache\BagOStuff;

/**
 * Handles compiling Mustache templates into PHP rendering functions
 *
 * @since 1.25
 */
class TemplateParser {

	private const CACHE_VERSION = '2.2.0';
	private const CACHE_TTL = BagOStuff::TTL_WEEK;

	/**
	 * @var BagOStuff
	 */
	private $cache;

	/**
	 * @var string The path to the Mustache templates
	 */
	protected $templateDir;

	/**
	 * @var callable[] Array of cached rendering functions
	 */
	protected $renderers;

	/**
	 * @var int Compilation flags passed to LightnCandy
	 */
	protected $compileFlags;

	/**
	 * @param string|null $templateDir
	 * @param BagOStuff|null $cache Read-write cache
	 */
	public function __construct( $templateDir = null, ?BagOStuff $cache = null ) {
		$this->templateDir = $templateDir ?: __DIR__ . '/../../resources/templates';
		$this->cache = $cache ?: MediaWikiServices::getInstance()->getObjectCacheFactory()
			->getLocalServerInstance( CACHE_ANYTHING );

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
		// Prevent path traversal. Based on LanguageNameUtils::isValidCode().
		// This is for paranoia. The $templateName should never come from
		// untrusted input.
		if ( strcspn( $templateName, ":/\\\000&<>'\"%" ) !== strlen( $templateName ) ) {
			throw new UnexpectedValueException( "Malformed \$templateName: $templateName" );
		}

		return "{$this->templateDir}/{$templateName}.mustache";
	}

	/**
	 * Returns a given template function if found, otherwise throws an exception.
	 * @param string $templateName The name of the template (without file suffix)
	 * @return callable
	 * @throws RuntimeException When the template file cannot be found
	 * @throws RuntimeException When the compiled template isn't callable. This is indicative of a
	 *  bug in LightnCandy
	 */
	protected function getTemplate( $templateName ) {
		$templateKey = $templateName . '|' . $this->compileFlags;

		// If a renderer has already been defined for this template, reuse it
		if ( isset( $this->renderers[$templateKey] ) &&
			is_callable( $this->renderers[$templateKey] )
		) {
			return $this->renderers[$templateKey];
		}

		// Fetch a secret key for building a keyed hash of the PHP code.
		// Note that this may be called before MediaWiki is fully initialized.
		$secretKey = MediaWikiServices::hasInstance()
			? MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::SecretKey )
			: null;

		if ( $secretKey ) {
			// See if the compiled PHP code is stored in the server-local cache.
			$key = $this->cache->makeKey(
				'lightncandy-compiled',
				self::CACHE_VERSION,
				$this->compileFlags,
				$this->templateDir,
				$templateName
			);
			$compiledTemplate = $this->cache->get( $key );

			// 1. Has the template changed since the compiled template was cached? If so, don't use
			// the cached code.
			if ( $compiledTemplate ) {
				$filesHash = FileContentsHasher::getFileContentsHash( $compiledTemplate['files'] );

				if ( $filesHash !== $compiledTemplate['filesHash'] ) {
					$compiledTemplate = null;
				}
			}

			// 2. Is the integrity of the cached PHP code compromised? If so, don't use the cached
			// code.
			if ( $compiledTemplate ) {
				$integrityHash = hash_hmac( 'sha256', $compiledTemplate['phpCode'], $secretKey );

				if ( $integrityHash !== $compiledTemplate['integrityHash'] ) {
					$compiledTemplate = null;
				}
			}

			// We're not using the cached code for whatever reason. Recompile the template and
			// cache it.
			if ( !$compiledTemplate ) {
				$compiledTemplate = $this->compile( $templateName );

				$compiledTemplate['integrityHash'] = hash_hmac(
					'sha256',
					$compiledTemplate['phpCode'],
					$secretKey
				);

				$this->cache->set( $key, $compiledTemplate, self::CACHE_TTL );
			}

		// If there is no secret key available, don't use cache
		} else {
			$compiledTemplate = $this->compile( $templateName );
		}

		// phpcs:ignore MediaWiki.Usage.ForbiddenFunctions.eval
		$renderer = eval( $compiledTemplate['phpCode'] );
		if ( !is_callable( $renderer ) ) {
			throw new RuntimeException( "Compiled template `{$templateName}` is not callable" );
		}
		$this->renderers[$templateKey] = $renderer;
		return $renderer;
	}

	/**
	 * Compile the Mustache template into PHP code using LightnCandy.
	 *
	 * The compilation step generates both PHP code and metadata, which is also returned in the
	 * result. An example result looks as follows:
	 *
	 *  ```php
	 *  [
	 *    'phpCode' => '...',
	 *    'files' => [
	 *      '/path/to/template.mustache',
	 *      '/path/to/partial1.mustache',
	 *      '/path/to/partial2.mustache',
	 *    'filesHash' => '...'
	 *  ]
	 *  ```
	 *
	 * The `files` entry is a list of the files read during the compilation of the template. Each
	 * entry is the fully-qualified filename, i.e. it includes path information.
	 *
	 * The `filesHash` entry can be used to determine whether the template has changed since it was
	 * last compiled without compiling the template again. Currently, the `filesHash` entry is
	 * generated with FileContentsHasher::getFileContentsHash.
	 *
	 * @param string $templateName The name of the template
	 * @return array An associative array containing the PHP code and metadata about its compilation
	 * @throws Exception Thrown by LightnCandy if it could not compile the Mustache code
	 * @throws RuntimeException If LightnCandy could not compile the Mustache code but did not throw
	 *  an exception. This exception is indicative of a bug in LightnCandy
	 * @suppress PhanTypeMismatchArgument
	 */
	protected function compile( $templateName ) {
		$filename = $this->getTemplateFilename( $templateName );

		if ( !file_exists( $filename ) ) {
			throw new RuntimeException( "Could not find template `{$templateName}` at {$filename}" );
		}

		$files = [ $filename ];
		$contents = file_get_contents( $filename );
		$compiled = LightnCandy::compile(
			$contents,
			[
				'flags' => $this->compileFlags,
				'basedir' => $this->templateDir,
				'fileext' => '.mustache',
				'partialresolver' => function ( $cx, $partialName ) use ( $templateName, &$files ) {
					$filename = "{$this->templateDir}/{$partialName}.mustache";
					if ( !file_exists( $filename ) ) {
						throw new RuntimeException( sprintf(
							'Could not compile template `%s`: Could not find partial `%s` at %s',
							$templateName,
							$partialName,
							$filename
						) );
					}

					$fileContents = file_get_contents( $filename );

					if ( $fileContents === false ) {
						throw new RuntimeException( sprintf(
							'Could not compile template `%s`: Could not find partial `%s` at %s',
							$templateName,
							$partialName,
							$filename
						) );
					}

					$files[] = $filename;

					return $fileContents;
				}
			]
		);
		if ( !$compiled ) {
			// This shouldn't happen because LightnCandy::FLAG_ERROR_EXCEPTION is set
			// Errors should throw exceptions instead of returning false
			// Check anyway for paranoia
			throw new RuntimeException( "Could not compile template `{$filename}`" );
		}

		$files = array_values( array_unique( $files ) );

		return [
			'phpCode' => $compiled,
			'files' => $files,
			'filesHash' => FileContentsHasher::getFileContentsHash( $files ),
		];
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
	 * @param-taint $templateName exec_path
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
