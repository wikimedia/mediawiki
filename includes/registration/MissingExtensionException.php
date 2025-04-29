<?php

namespace MediaWiki\Registration;

use Exception;
use MediaWiki\Html\TemplateParser;
use Wikimedia\Http\HttpStatus;
use Wikimedia\ObjectCache\EmptyBagOStuff;

/**
 * Thrown when ExtensionRegistry cannot open the extension.json or skin.json file.
 *
 * We handle this case specially, because it is one of the more
 * common errors a new MW sysadmin is likely to encounter and we
 * want their initial experience to be good. wfLoadExtension()
 * generally happens before MWExceptionRenderer gets installed
 * so we cannot use that.
 *
 * @ingroup ExtensionRegistry
 * @internal
 */
class MissingExtensionException extends Exception {
	private bool $isSkin;
	private string $extName = 'unknown';
	private string $path;
	private string $error;

	/**
	 * @param string $path Path of file that cannot be read
	 * @param string $error Text of error mtime gave
	 */
	public function __construct( string $path, string $error ) {
		$this->isSkin = str_ends_with( $path, "/skin.json" );
		$m = [];
		preg_match( "!/([^/]*)/[^/]*.json$!", $path, $m );
		if ( $m ) {
			$this->extName = $m[1];
		}
		$this->path = $path;
		$this->error = $error;

		parent::__construct( "Error Loading extension. Unable to open file $path: $error" );
	}

	/**
	 * Output error message as html.
	 *
	 * Avoid relying on MW stuff, as it might not be setup yet.
	 * We don't bother translating, as the user may not have even set lang yet.
	 *
	 */
	private function renderHtml() {
		if ( !headers_sent() ) {
			HttpStatus::header( 500 );
			header( 'Content-Type: text/html; charset=UTF-8' );
		}

		$templateParser = new TemplateParser( null, new EmptyBagOStuff() );

		try {
			echo $templateParser->processTemplate(
				'ExtensionConfigError',
				[
					'version' => MW_VERSION,
					'path' => $this->path,
					'type' => $this->isSkin ? 'skin' : 'extension',
					'error' => $this->error,
					'extName' => $this->extName,
					'trace' => $this->getTraceAsString(),
					'mwLogo' => $this->getMWLogo(),
				]
			);
		} catch ( Exception $e ) {
			echo 'Error: ' . htmlspecialchars( $e->getMessage() );
		}
	}

	/**
	 * Render the error for CLI
	 */
	private function renderText() {
		$type = $this->isSkin ? 'skin' : 'extension';
		echo "Error: The $this->extName $type cannot be loaded. "
			. "Check that all of its files are installed properly.\n\n";
		echo $this->getTraceAsString();
		echo "\n";
	}

	/**
	 * Output an error response and exit.
	 *
	 * @return never
	 */
	public function render() {
		if ( wfIsCli() ) {
			$this->renderText();
		} else {
			$this->renderHtml();
		}
		// Make sure that the error gets into logs.
		// This will also stop execution.
		trigger_error( $this->getMessage(), E_USER_ERROR );
	}

	/**
	 * Get the url for the MW logo
	 *
	 * @return string
	 */
	private function getMWLogo() {
		global $wgResourceBasePath;
		$suffix = "/resources/assets/mediawiki.png";
		if ( $wgResourceBasePath !== null ) {
			// We are early in setup, so we can't rely on this.
			return $wgResourceBasePath . $suffix;
		}
		$path = '/';
		foreach ( array_filter( explode( '/', $_SERVER['PHP_SELF'] ) ) as $part ) {
			if ( !preg_match( '/\.php$/', $part ) ) {
				$path .= "$part/";
			} else {
				break;
			}
		}

		return $path . $suffix;
	}
}

/** @deprecated class alias since 1.43 */
class_alias( MissingExtensionException::class, 'MissingExtensionException' );
