<?php
/**
 * License selector for use on Special:Upload.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup SpecialPage
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @copyright Copyright © 2005, Ævar Arnfjörð Bjarmason
 */

use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLFormField;
use MediaWiki\MediaWikiServices;

/**
 * A License class for use on Special:Upload
 */
class Licenses extends HTMLFormField {
	protected string $msg;
	protected array $lines = [];
	protected string $html;
	protected ?string $selected;

	/**
	 * @param array $params
	 */
	public function __construct( $params ) {
		parent::__construct( $params );

		$this->msg = static::getMessageFromParams( $params );
		$this->selected = null;

		$this->makeLines();
	}

	/**
	 * @param array $params
	 * @return string
	 */
	protected static function getMessageFromParams( $params ) {
		if ( !empty( $params['licenses'] ) ) {
			return $params['licenses'];
		}

		// If the licenses page is in $wgForceUIMsgAsContentMsg (which is the case
		// on Commons), translations will be in the database, in subpages of this
		// message (e.g. MediaWiki:Licenses/<lang>)
		// If there is no such translation, the result will be '-' (the empty default
		// in the i18n files), so we'll need to force it to look up the actual licenses
		// in the default site language (= get the translation from MediaWiki:Licenses)
		// Also see https://phabricator.wikimedia.org/T3495
		$defaultMsg = wfMessage( 'licenses' )->inContentLanguage();
		if ( $defaultMsg->isDisabled() ) {
			$defaultMsg = wfMessage( 'licenses' )->inLanguage(
				MediaWikiServices::getInstance()->getContentLanguage() );
		}

		return $defaultMsg->plain();
	}

	/**
	 * @param string $line
	 * @return License
	 */
	protected function buildLine( $line ) {
		return new License( $line );
	}

	/**
	 * @internal
	 */
	protected function makeLines() {
		$levels = [];
		$lines = explode( "\n", $this->msg );

		foreach ( $lines as $line ) {
			if ( !str_starts_with( $line, '*' ) ) {
				continue;
			}
			[ $level, $line ] = $this->trimStars( $line );

			if ( str_contains( $line, '|' ) ) {
				$obj = $this->buildLine( $line );
				$this->stackItem( $this->lines, $levels, $obj );
			} else {
				if ( $level < count( $levels ) ) {
					$levels = array_slice( $levels, 0, $level );
				}
				if ( $level == count( $levels ) ) {
					$levels[$level - 1] = $line;
				} elseif ( $level > count( $levels ) ) {
					$levels[] = $line;
				}
			}
		}
	}

	/**
	 * @param string $str
	 * @return array
	 */
	protected function trimStars( $str ) {
		$numStars = strspn( $str, '*' );
		return [ $numStars, ltrim( substr( $str, $numStars ), ' ' ) ];
	}

	/**
	 * @param array &$list
	 * @param array $path
	 * @param mixed $item
	 */
	protected function stackItem( &$list, $path, $item ) {
		$position =& $list;
		if ( $path ) {
			foreach ( $path as $key ) {
				$position =& $position[$key];
			}
		}
		$position[] = $item;
	}

	/**
	 * @param array $tagset
	 * @param int $depth
	 * @return string
	 */
	protected function makeHtml( $tagset, $depth = 0 ) {
		$html = '';

		foreach ( $tagset as $key => $val ) {
			if ( is_array( $val ) ) {
				$html .= $this->outputOption(
					$key, '',
					[
						'disabled' => 'disabled'
					],
					$depth
				);
				$html .= $this->makeHtml( $val, $depth + 1 );
			} else {
				$html .= $this->outputOption(
					$val->text, $val->template,
					[ 'title' => '{{' . $val->template . '}}' ],
					$depth
				);
			}
		}

		return $html;
	}

	/**
	 * @param string $message
	 * @param string $value
	 * @param null|array $attribs
	 * @param int $depth
	 * @return string
	 */
	protected function outputOption( $message, $value, $attribs = null, $depth = 0 ) {
		$msgObj = $this->msg( $message );
		$text = $msgObj->exists() ? $msgObj->text() : $message;
		$attribs['value'] = $value;
		if ( $value === $this->selected && !isset( $attribs['disabled'] ) ) {
			$attribs['selected'] = 'selected';
		}

		$val = str_repeat( /* &nbsp */ "\u{00A0}", $depth * 2 ) . $text;
		return str_repeat( "\t", $depth ) . Html::element( 'option', $attribs, $val ) . "\n";
	}

	/**
	 * Accessor for $this->lines
	 *
	 * @return array
	 */
	public function getLines() {
		return $this->lines;
	}

	/**
	 * Accessor for $this->lines
	 *
	 * @return array
	 *
	 * @deprecated since 1.31 Use getLines() instead
	 */
	public function getLicenses() {
		return $this->getLines();
	}

	/**
	 * @inheritDoc
	 */
	public function getInputHTML( $value ) {
		$this->selected = $value;

		// add a default "no license selected" option
		$default = $this->buildLine( '|nolicense' );
		array_unshift( $this->lines, $default );

		$html = $this->makeHtml( $this->getLines() );

		$attribs = [
			'name' => $this->mName,
			'id' => $this->mID
		];
		if ( !empty( $this->mParams['disabled'] ) ) {
			$attribs['disabled'] = 'disabled';
		}

		$html = Html::rawElement( 'select', $attribs, $html );

		// remove default "no license selected" from lines again
		array_shift( $this->lines );

		return $html;
	}
}
