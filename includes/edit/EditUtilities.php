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

/**
 * Static utility class for editing
 */
class EditUtilities {
	/**
	 * A number of web browsers are known to corrupt non-ASCII characters
	 * in a UTF-8 text editing environment. To protect against this,
	 * detected browsers will be served an armored version of the text,
	 * with non-ASCII chars converted to numeric HTML character references.
	 *
	 * Preexisting such character references will have a 0 added to them
	 * to ensure that round-trips do not alter the original data.
	 *
	 * @param string $invalue
	 * @return string
	 */
	public static function makeSafe( $invalue ) {
		// Armor existing references for reversibility.
		$invalue = strtr( $invalue, [ "&#x" => "&#x0" ] );

		$bytesleft = 0;
		$result = "";
		$working = 0;
		$valueLength = strlen( $invalue );
		for ( $i = 0; $i < $valueLength; $i++ ) {
			$bytevalue = ord( $invalue[$i] );
			if ( $bytevalue <= 0x7F ) { // 0xxx xxxx
				$result .= chr( $bytevalue );
				$bytesleft = 0;
			} elseif ( $bytevalue <= 0xBF ) { // 10xx xxxx
				$working = $working << 6;
				$working += ( $bytevalue & 0x3F );
				$bytesleft--;
				if ( $bytesleft <= 0 ) {
					$result .= "&#x" . strtoupper( dechex( $working ) ) . ";";
				}
			} elseif ( $bytevalue <= 0xDF ) { // 110x xxxx
				$working = $bytevalue & 0x1F;
				$bytesleft = 1;
			} elseif ( $bytevalue <= 0xEF ) { // 1110 xxxx
				$working = $bytevalue & 0x0F;
				$bytesleft = 2;
			} else { // 1111 0xxx
				$working = $bytevalue & 0x07;
				$bytesleft = 3;
			}
		}
		return $result;
	}

	/**
	 * Reverse the previously applied transliteration of non-ASCII characters
	 * back to UTF-8. Used to protect data from corruption by broken web browsers
	 * as listed in $wgBrowserBlackList.
	 *
	 * @param string $invalue
	 * @return string
	 */
	public static function unmakeSafe( $invalue ) {
		$result = "";
		$valueLength = strlen( $invalue );
		for ( $i = 0; $i < $valueLength; $i++ ) {
			if ( ( substr( $invalue, $i, 3 ) === "&#x" ) && ( $invalue[$i + 3] !== '0' ) ) {
				$i += 3;
				$hexstring = "";
				do {
					$hexstring .= $invalue[$i];
					$i++;
				} while ( ctype_xdigit( $invalue[$i] ) && ( $i < strlen( $invalue ) ) );

				// Do some sanity checks. These aren't needed for reversibility,
				// but should help keep the breakage down if the editor
				// breaks one of the entities whilst editing.
				if ( ( substr( $invalue, $i, 1 ) === ";" ) && ( strlen( $hexstring ) <= 6 ) ) {
					$codepoint = hexdec( $hexstring );
					$result .= UtfNormal\Utils::codepointToUtf8( $codepoint );
				} else {
					$result .= "&#x" . $hexstring . substr( $invalue, $i, 1 );
				}
			} else {
				$result .= substr( $invalue, $i, 1 );
			}
		}
		// reverse the transform that we made for reversibility reasons.
		return strtr( $result, [ "&#x0" => "&#x" ] );
	}

	/**
	 * @param string $wikitext
	 * @return string
	 * @since 1.29
	 */
	public static function addNewLineAtEnd( $wikitext ) {
		if ( strval( $wikitext ) !== '' ) {
			// Ensure there's a newline at the end, otherwise adding lines
			// is awkward.
			// But don't add a newline if the text is empty, or Firefox in XHTML
			// mode will show an extra newline. A bit annoying.
			$wikitext .= "\n";
			return $wikitext;
		}
		return $wikitext;
	}

	/**
	 * Standard summary input and label (wgSummary), abstracted so EditPage
	 * subclasses may reorganize the form.
	 * Note that you do not need to worry about the label's for=, it will be
	 * inferred by the id given to the input. You can remove them both by
	 * passing [ 'id' => false ] to $userInputAttrs.
	 *
	 * @param string $summary The value of the summary input
	 * @param string $labelText The html to place inside the label
	 * @param array $inputAttrs Array of attrs to use on the input
	 * @param array $spanLabelAttrs Array of attrs to use on the span inside the label
	 *
	 * @return array An array in the format [ $label, $input ]
	 */
	public static function getSummaryInput( $summary = "", $labelText = null,
		$inputAttrs = null, $spanLabelAttrs = null, $missingSummary = false
	) {
		// Note: the maxlength is overridden in JS to 255 and to make it use UTF-8 bytes, not characters.
		$inputAttrs = ( is_array( $inputAttrs ) ? $inputAttrs : [] ) + [
			'id' => 'wpSummary',
			'maxlength' => '200',
			'tabindex' => '1',
			'size' => 60,
			'spellcheck' => 'true',
		] + Linker::tooltipAndAccesskeyAttribs( 'summary' );

		$spanLabelAttrs = ( is_array( $spanLabelAttrs ) ? $spanLabelAttrs : [] ) + [
			'class' => $missingSummary ? 'mw-summarymissed' : 'mw-summary',
			'id' => "wpSummaryLabel"
		];

		$label = null;
		if ( $labelText ) {
			$label = Xml::tags(
				'label',
				$inputAttrs['id'] ? [ 'for' => $inputAttrs['id'] ] : null,
				$labelText
			);
			$label = Xml::tags( 'span', $spanLabelAttrs, $label );
		}

		$input = Html::input( 'wpSummary', $summary, 'text', $inputAttrs );

		return [ $label, $input ];
	}

	/**
	 * Shows a bulletin board style toolbar for common editing functions.
	 * It can be disabled in the user preferences.
	 *
	 * @param Title $title Title object for the page being edited (optional)
	 * @return string
	 */
	public static function getEditToolbar( $output, $title = null ) {
		global $wgContLang;
		global $wgEnableUploads, $wgForeignFileRepos;

		$imagesAvailable = $wgEnableUploads || count( $wgForeignFileRepos );
		$showSignature = true;
		if ( $title ) {
			$showSignature = MWNamespace::wantSignatures( $title->getNamespace() );
		}

		/**
		 * $toolarray is an array of arrays each of which includes the
		 * opening tag, the closing tag, optionally a sample text that is
		 * inserted between the two when no selection is highlighted
		 * and.  The tip text is shown when the user moves the mouse
		 * over the button.
		 *
		 * Images are defined in ResourceLoaderEditToolbarModule.
		 */
		$toolarray = [
			[
				'id'     => 'mw-editbutton-bold',
				'open'   => '\'\'\'',
				'close'  => '\'\'\'',
				'sample' => wfMessage( 'bold_sample' )->text(),
				'tip'    => wfMessage( 'bold_tip' )->text(),
			],
			[
				'id'     => 'mw-editbutton-italic',
				'open'   => '\'\'',
				'close'  => '\'\'',
				'sample' => wfMessage( 'italic_sample' )->text(),
				'tip'    => wfMessage( 'italic_tip' )->text(),
			],
			[
				'id'     => 'mw-editbutton-link',
				'open'   => '[[',
				'close'  => ']]',
				'sample' => wfMessage( 'link_sample' )->text(),
				'tip'    => wfMessage( 'link_tip' )->text(),
			],
			[
				'id'     => 'mw-editbutton-extlink',
				'open'   => '[',
				'close'  => ']',
				'sample' => wfMessage( 'extlink_sample' )->text(),
				'tip'    => wfMessage( 'extlink_tip' )->text(),
			],
			[
				'id'     => 'mw-editbutton-headline',
				'open'   => "\n== ",
				'close'  => " ==\n",
				'sample' => wfMessage( 'headline_sample' )->text(),
				'tip'    => wfMessage( 'headline_tip' )->text(),
			],
			$imagesAvailable ? [
				'id'     => 'mw-editbutton-image',
				'open'   => '[[' . $wgContLang->getNsText( NS_FILE ) . ':',
				'close'  => ']]',
				'sample' => wfMessage( 'image_sample' )->text(),
				'tip'    => wfMessage( 'image_tip' )->text(),
			] : false,
			$imagesAvailable ? [
				'id'     => 'mw-editbutton-media',
				'open'   => '[[' . $wgContLang->getNsText( NS_MEDIA ) . ':',
				'close'  => ']]',
				'sample' => wfMessage( 'media_sample' )->text(),
				'tip'    => wfMessage( 'media_tip' )->text(),
			] : false,
			[
				'id'     => 'mw-editbutton-nowiki',
				'open'   => "<nowiki>",
				'close'  => "</nowiki>",
				'sample' => wfMessage( 'nowiki_sample' )->text(),
				'tip'    => wfMessage( 'nowiki_tip' )->text(),
			],
			$showSignature ? [
				'id'     => 'mw-editbutton-signature',
				'open'   => wfMessage( 'sig-text', '~~~~' )->inContentLanguage()->text(),
				'close'  => '',
				'sample' => '',
				'tip'    => wfMessage( 'sig_tip' )->text(),
			] : false,
			[
				'id'     => 'mw-editbutton-hr',
				'open'   => "\n----\n",
				'close'  => '',
				'sample' => '',
				'tip'    => wfMessage( 'hr_tip' )->text(),
			]
		];

		$script = 'mw.loader.using("mediawiki.toolbar", function () {';
		foreach ( $toolarray as $tool ) {
			if ( !$tool ) {
				continue;
			}

			$params = [
				// Images are defined in ResourceLoaderEditToolbarModule
				false,
				// Note that we use the tip both for the ALT tag and the TITLE tag of the image.
				// Older browsers show a "speedtip" type message only for ALT.
				// Ideally these should be different, realistically they
				// probably don't need to be.
				$tool['tip'],
				$tool['open'],
				$tool['close'],
				$tool['sample'],
				$tool['id'],
			];

			$script .= Xml::encodeJsCall(
				'mw.toolbar.addButton',
				$params,
				ResourceLoader::inDebugMode()
			);
		}

		$script .= '});';
		$output->addScript( ResourceLoader::makeInlineScript( $script ) );

		$toolbar = '<div id="toolbar"></div>';

		Hooks::run( 'EditPageBeforeEditToolbar', [ &$toolbar ] );

		return $toolbar;
	}

	/**
	 * Get the copyright warning, by default returns wikitext
	 *
	 * @param Title $title
	 * @param string $format Output format, valid values are any function of a Message object
	 * @param Language|string|null $langcode Language code or Language object.
	 * @return string
	 */
	public static function getCopyrightWarning( $title, $format = 'plain', $langcode = null ) {
		global $wgRightsText;
		if ( $wgRightsText ) {
			$copywarnMsg = [ 'copyrightwarning',
				'[[' . wfMessage( 'copyrightpage' )->inContentLanguage()->text() . ']]',
				$wgRightsText ];
		} else {
			$copywarnMsg = [ 'copyrightwarning2',
				'[[' . wfMessage( 'copyrightpage' )->inContentLanguage()->text() . ']]' ];
		}
		// Allow for site and per-namespace customization of contribution/copyright notice.
		Hooks::run( 'EditPageCopyrightWarning', [ $title, &$copywarnMsg ] );

		$msg = call_user_func_array( 'wfMessage', $copywarnMsg )->title( $title );
		if ( $langcode ) {
			$msg->inLanguage( $langcode );
		}
		return "<div id=\"editpage-copywarn\">\n" .
			$msg->$format() . "\n</div>";
	}

	/**
	 * Get the Limit report for page previews
	 *
	 * @since 1.22
	 * @param ParserOutput $output ParserOutput object from the parse
	 * @return string HTML
	 */
	public static function getPreviewLimitReport( $output ) {
		if ( !$output || !$output->getLimitReportData() ) {
			return '';
		}

		$limitReport = Html::rawElement( 'div', [ 'class' => 'mw-limitReportExplanation' ],
			wfMessage( 'limitreport-title' )->parseAsBlock()
		);

		// Show/hide animation doesn't work correctly on a table, so wrap it in a div.
		$limitReport .= Html::openElement( 'div', [ 'class' => 'preview-limit-report-wrapper' ] );

		$limitReport .= Html::openElement( 'table', [
			'class' => 'preview-limit-report wikitable'
		] ) .
			Html::openElement( 'tbody' );

		foreach ( $output->getLimitReportData() as $key => $value ) {
			if ( Hooks::run( 'ParserLimitReportFormat',
				[ $key, &$value, &$limitReport, true, true ]
			) ) {
				$keyMsg = wfMessage( $key );
				$valueMsg = wfMessage( [ "$key-value-html", "$key-value" ] );
				if ( !$valueMsg->exists() ) {
					$valueMsg = new RawMessage( '$1' );
				}
				if ( !$keyMsg->isDisabled() && !$valueMsg->isDisabled() ) {
					$limitReport .= Html::openElement( 'tr' ) .
						Html::rawElement( 'th', null, $keyMsg->parse() ) .
						Html::rawElement( 'td', null, $valueMsg->params( $value )->parse() ) .
						Html::closeElement( 'tr' );
				}
			}
		}

		$limitReport .= Html::closeElement( 'tbody' ) .
			Html::closeElement( 'table' ) .
			Html::closeElement( 'div' );

		return $limitReport;
	}

	/**
	 * Check given input text against $wgSpamRegex, and return the text of the first match.
	 *
	 * @param string $text
	 *
	 * @return string|bool Matching string or false
	 */
	public static function matchSpamRegex( $text ) {
		global $wgSpamRegex;
		// For back compatibility, $wgSpamRegex may be a single string or an array of regexes.
		$regexes = (array)$wgSpamRegex;
		return self::matchSpamRegexInternal( $text, $regexes );
	}

	/**
	 * Check given input text against $wgSummarySpamRegex, and return the text of the first match.
	 *
	 * @param string $text
	 *
	 * @return string|bool Matching string or false
	 */
	public static function matchSummarySpamRegex( $text ) {
		global $wgSummarySpamRegex;
		$regexes = (array)$wgSummarySpamRegex;
		return self::matchSpamRegexInternal( $text, $regexes );
	}

	/**
	 * @param string $text
	 * @param array $regexes
	 * @return bool|string
	 */
	protected static function matchSpamRegexInternal( $text, $regexes ) {
		foreach ( $regexes as $regex ) {
			$matches = [];
			if ( preg_match( $regex, $text, $matches ) ) {
				return $matches[0];
			}
		}
		return false;
	}

	/**
	 * Extract the section title from current section text, if any.
	 *
	 * @param string $text
	 * @return string|bool String or false
	 */
	public static function extractSectionTitle( $text ) {
		preg_match( "/^(=+)(.+)\\1\\s*(\n|$)/i", $text, $matches );
		if ( !empty( $matches[2] ) ) {
			$parser = \MediaWiki\MediaWikiServices::getInstance()->getParser();
			return $parser->stripSectionName( trim( $matches[2] ) );
		} else {
			return false;
		}
	}

	/**
	 * Check if a page was deleted while the user was editing it, before submit.
	 * Note that we rely on the logging table, which hasn't been always there,
	 * but that doesn't matter, because this only applies to brand new
	 * deletes.
	 * @param bool|stdClass &$lastDelete Gives the raw db result for the last deletion
	 * @return bool
	 */
	public static function wasDeletedSince( $title, $startTime, &$lastDelete = null ) {
		$pageDeletedSinceStartTime = false;
		if ( !$title->exists() && $title->isDeletedQuick() ) {
			$lastDelete = self::getLastDelete( $title );
			if ( $lastDelete !== false ) {
				$deleteTime = wfTimestamp( TS_MW, $lastDelete->log_timestamp );
				if ( $deleteTime > $startTime ) {
					$pageDeletedSinceStartTime = true;
				}
			}
		}
		return $pageDeletedSinceStartTime;
	}

	/**
	 * @return false|stdClass
	 */
	private static function getLastDelete( Title $title ) {
		$dbr = wfGetDB( DB_REPLICA );
		return $dbr->selectRow(
			[ 'logging', 'user' ],
			[
				'log_type',
				'log_action',
				'log_timestamp',
				'log_user',
				'log_namespace',
				'log_title',
				'log_comment',
				'log_params',
				'log_deleted',
				'user_name'
			],
			[
				'log_namespace' => $title->getNamespace(),
				'log_title' => $title->getDBkey(),
				'log_type' => 'delete',
				'log_action' => 'delete',
				'user_id=log_user'
			],
			__METHOD__,
			[ 'LIMIT' => 1, 'ORDER BY' => 'log_timestamp DESC' ]
		);
	}
}
