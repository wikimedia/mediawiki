<?php
/**
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
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

use MediaWiki\MediaWikiServices;

/**
 * This is the abstract base class for API formatters.
 *
 * @ingroup API
 */
abstract class ApiFormatBase extends ApiBase {
	private $mIsHtml, $mFormat;
	private $mBuffer, $mDisabled = false;
	private $mIsWrappedHtml = false;
	private $mHttpStatus = false;
	protected $mForceDefaultParams = false;

	/**
	 * If $format ends with 'fm', pretty-print the output in HTML.
	 * @param ApiMain $main
	 * @param string $format Format name
	 */
	public function __construct( ApiMain $main, $format ) {
		parent::__construct( $main, $format );

		$this->mIsHtml = ( substr( $format, -2, 2 ) === 'fm' ); // ends with 'fm'
		if ( $this->mIsHtml ) {
			$this->mFormat = substr( $format, 0, -2 ); // remove ending 'fm'
			$this->mIsWrappedHtml = $this->getMain()->getCheck( 'wrappedhtml' );
		} else {
			$this->mFormat = $format;
		}
		$this->mFormat = strtoupper( $this->mFormat );
	}

	/**
	 * Overriding class returns the MIME type that should be sent to the client.
	 *
	 * When getIsHtml() returns true, the return value here is used for syntax
	 * highlighting but the client sees text/html.
	 *
	 * @return string
	 */
	abstract public function getMimeType();

	/**
	 * Return a filename for this module's output.
	 * @note If $this->getIsWrappedHtml() || $this->getIsHtml(), you'll very
	 *  likely want to fall back to this class's version.
	 * @since 1.27
	 * @return string Generally this should be "api-result.$ext"
	 */
	public function getFilename() {
		if ( $this->getIsWrappedHtml() ) {
			return 'api-result-wrapped.json';
		} elseif ( $this->getIsHtml() ) {
			return 'api-result.html';
		} else {
			$mimeAnalyzer = MediaWikiServices::getInstance()->getMimeAnalyzer();
			$ext = $mimeAnalyzer->getExtensionFromMimeTypeOrNull( $this->getMimeType() )
				?? strtolower( $this->mFormat );
			return "api-result.$ext";
		}
	}

	/**
	 * Get the internal format name
	 * @return string
	 */
	public function getFormat() {
		return $this->mFormat;
	}

	/**
	 * Returns true when the HTML pretty-printer should be used.
	 * The default implementation assumes that formats ending with 'fm'
	 * should be formatted in HTML.
	 * @return bool
	 */
	public function getIsHtml() {
		return $this->mIsHtml;
	}

	/**
	 * Returns true when the special wrapped mode is enabled.
	 * @since 1.27
	 * @return bool
	 */
	protected function getIsWrappedHtml() {
		return $this->mIsWrappedHtml;
	}

	/**
	 * Disable the formatter.
	 *
	 * This causes calls to initPrinter() and closePrinter() to be ignored.
	 */
	public function disable() {
		$this->mDisabled = true;
	}

	/**
	 * Whether the printer is disabled
	 * @return bool
	 */
	public function isDisabled() {
		return $this->mDisabled;
	}

	/**
	 * Whether this formatter can handle printing API errors.
	 *
	 * If this returns false, then on API errors the default printer will be
	 * instantiated.
	 * @since 1.23
	 * @return bool
	 */
	public function canPrintErrors() {
		return true;
	}

	/**
	 * Ignore request parameters, force a default.
	 *
	 * Used as a fallback if errors are being thrown.
	 * @since 1.26
	 */
	public function forceDefaultParams() {
		$this->mForceDefaultParams = true;
	}

	/**
	 * Overridden to honor $this->forceDefaultParams(), if applicable
	 * @inheritDoc
	 * @since 1.26
	 */
	protected function getParameterFromSettings( $paramName, $paramSettings, $parseLimit ) {
		if ( !$this->mForceDefaultParams ) {
			return parent::getParameterFromSettings( $paramName, $paramSettings, $parseLimit );
		}

		if ( !is_array( $paramSettings ) ) {
			return $paramSettings;
		}

		return $paramSettings[self::PARAM_DFLT] ?? null;
	}

	/**
	 * Set the HTTP status code to be used for the response
	 * @since 1.29
	 * @param int $code
	 */
	public function setHttpStatus( $code ) {
		if ( $this->mDisabled ) {
			return;
		}

		if ( $this->getIsHtml() ) {
			$this->mHttpStatus = $code;
		} else {
			$this->getMain()->getRequest()->response()->statusHeader( $code );
		}
	}

	/**
	 * Initialize the printer function and prepare the output headers.
	 * @param bool $unused Always false since 1.25
	 */
	public function initPrinter( $unused = false ) {
		if ( $this->mDisabled ) {
			return;
		}

		$mime = $this->getIsWrappedHtml()
			? 'text/mediawiki-api-prettyprint-wrapped'
			: ( $this->getIsHtml() ? 'text/html' : $this->getMimeType() );

		// Some printers (ex. Feed) do their own header settings,
		// in which case $mime will be set to null
		if ( $mime === null ) {
			return; // skip any initialization
		}

		$this->getMain()->getRequest()->response()->header( "Content-Type: $mime; charset=utf-8" );

		// Set X-Frame-Options API results (T41180)
		$apiFrameOptions = $this->getConfig()->get( 'ApiFrameOptions' );
		if ( $apiFrameOptions ) {
			$this->getMain()->getRequest()->response()->header( "X-Frame-Options: $apiFrameOptions" );
		}

		// Set a Content-Disposition header so something downloading an API
		// response uses a halfway-sensible filename (T128209).
		$header = 'Content-Disposition: inline';
		$filename = $this->getFilename();
		$compatFilename = mb_convert_encoding( $filename, 'ISO-8859-1' );
		if ( preg_match( '/^[0-9a-zA-Z!#$%&\'*+\-.^_`|~]+$/', $compatFilename ) ) {
			$header .= '; filename=' . $compatFilename;
		} else {
			$header .= '; filename="'
				. preg_replace( '/([\0-\x1f"\x5c\x7f])/', '\\\\$1', $compatFilename ) . '"';
		}
		if ( $compatFilename !== $filename ) {
			$value = "UTF-8''" . rawurlencode( $filename );
			// rawurlencode() encodes more characters than RFC 5987 specifies. Unescape the ones it allows.
			$value = strtr( $value, [
				'%21' => '!', '%23' => '#', '%24' => '$', '%26' => '&', '%2B' => '+', '%5E' => '^',
				'%60' => '`', '%7C' => '|',
			] );
			$header .= '; filename*=' . $value;
		}
		$this->getMain()->getRequest()->response()->header( $header );
	}

	/**
	 * Finish printing and output buffered data.
	 */
	public function closePrinter() {
		if ( $this->mDisabled ) {
			return;
		}

		$mime = $this->getMimeType();
		if ( $this->getIsHtml() && $mime !== null ) {
			$format = $this->getFormat();
			$lcformat = strtolower( $format );
			$result = $this->getBuffer();

			$context = new DerivativeContext( $this->getMain() );
			$skinFactory = MediaWikiServices::getInstance()->getSkinFactory();
			$context->setSkin( $skinFactory->makeSkin( 'apioutput' ) );
			$context->setTitle( SpecialPage::getTitleFor( 'ApiHelp' ) );
			$out = new OutputPage( $context );
			$context->setOutput( $out );

			$out->setRobotPolicy( 'noindex,nofollow' );
			$out->addModuleStyles( 'mediawiki.apipretty' );
			$out->setPageTitle( $context->msg( 'api-format-title' ) );

			if ( !$this->getIsWrappedHtml() ) {
				// When the format without suffix 'fm' is defined, there is a non-html version
				if ( $this->getMain()->getModuleManager()->isDefined( $lcformat, 'format' ) ) {
					if ( !$this->getRequest()->wasPosted() ) {
						$nonHtmlUrl = strtok( $this->getRequest()->getFullRequestURL(), '?' )
							. '?' . $this->getRequest()->appendQueryValue( 'format', $lcformat );
						$msg = $context->msg( 'api-format-prettyprint-header-hyperlinked' )
							->params( $format, $lcformat, $nonHtmlUrl );
					} else {
						$msg = $context->msg( 'api-format-prettyprint-header' )->params( $format, $lcformat );
					}
				} else {
					$msg = $context->msg( 'api-format-prettyprint-header-only-html' )->params( $format );
				}

				$header = $msg->parseAsBlock();
				$out->addHTML(
					Html::rawElement( 'div', [ 'class' => 'api-pretty-header' ],
						ApiHelp::fixHelpLinks( $header )
					)
				);

				if ( $this->mHttpStatus && $this->mHttpStatus !== 200 ) {
					$out->addHTML(
						Html::rawElement( 'div', [ 'class' => 'api-pretty-header api-pretty-status' ],
							$this->msg(
								'api-format-prettyprint-status',
								$this->mHttpStatus,
								HttpStatus::getMessage( $this->mHttpStatus )
							)->parse()
						)
					);
				}
			}

			if ( $this->getHookRunner()->onApiFormatHighlight( $context, $result, $mime, $format ) ) {
				$out->addHTML(
					Html::element( 'pre', [ 'class' => 'api-pretty-content' ], $result )
				);
			}

			if ( $this->getIsWrappedHtml() ) {
				// This is a special output mode mainly intended for ApiSandbox use
				$time = $this->getMain()->getRequest()->getElapsedTime();
				$json = FormatJson::encode(
					[
						'status' => (int)( $this->mHttpStatus ?: 200 ),
						'statustext' => HttpStatus::getMessage( $this->mHttpStatus ?: 200 ),
						'html' => $out->getHTML(),
						'modules' => array_values( array_unique( array_merge(
							$out->getModules(),
							$out->getModuleStyles()
						) ) ),
						'continue' => $this->getResult()->getResultData( 'continue' ),
						'time' => round( $time * 1000 ),
					],
					false, FormatJson::ALL_OK
				);

				// T68776: OutputHandler::mangleFlashPolicy() avoids a nasty bug in
				// Flash, but what it does isn't friendly for the API, so we need to
				// work around it.
				if ( preg_match( '/\<\s*cross-domain-policy\s*\>/i', $json ) ) {
					$json = preg_replace(
						'/\<(\s*cross-domain-policy\s*)\>/i', '\\u003C$1\\u003E', $json
					);
				}

				echo $json;
			} else {
				// API handles its own clickjacking protection.
				// Note, that $wgBreakFrames will still override $wgApiFrameOptions for format mode.
				$out->allowClickjacking();
				$out->output();
			}
		} else {
			// For non-HTML output, clear all errors that might have been
			// displayed if display_errors=On
			ob_clean();

			echo $this->getBuffer();
		}
	}

	/**
	 * Append text to the output buffer.
	 * @param string $text
	 */
	public function printText( $text ) {
		$this->mBuffer .= $text;
	}

	/**
	 * Get the contents of the buffer.
	 * @return string
	 */
	public function getBuffer() {
		return $this->mBuffer;
	}

	public function getAllowedParams() {
		$ret = [];
		if ( $this->getIsHtml() ) {
			$ret['wrappedhtml'] = [
				ApiBase::PARAM_DFLT => false,
				ApiBase::PARAM_HELP_MSG => 'apihelp-format-param-wrappedhtml',

			];
		}
		return $ret;
	}

	protected function getExamplesMessages() {
		return [
			'action=query&meta=siteinfo&siprop=namespaces&format=' . $this->getModuleName()
				=> [ 'apihelp-format-example-generic', $this->getFormat() ]
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Data_formats';
	}

}

/**
 * For really cool vim folding this needs to be at the end:
 * vim: foldmarker=@{,@} foldmethod=marker
 */
