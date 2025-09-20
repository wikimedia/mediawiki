<?php
namespace MediaWiki\Upload;

use MediaWiki\Parser\Sanitizer;
use Wikimedia\CSS\Objects\AtRule;
use Wikimedia\CSS\Objects\Token;
use Wikimedia\CSS\Parser\Parser as CSSParser;

/**
 * Ensure SVG files cannot load external resources via URLs in CSS.
 *
 * Beyond that restriction, it aims to be relaxed in the CSS it allows.
 *
 * Data: urls are also banned except in @font-face. The rationale behind
 * this is unclear. The restriction was copied over from the predecessor to
 * this class.
 */
class SVGCSSChecker {

	/**
	 * List of \@rules banned.
	 * \@import for obvious reasons.
	 * \@charset just in case although i expect it does nothing inside an svg.
	 */
	private const BANNED_AT_RULE = [
		'charset',
		'import'
	];

	/**
	 * image() and image-set() can use bare strings as url
	 * src() is not supported by real browsers but is an alias for url() in spec
	 */
	private const BANNED_FUNCS = [
		'src',
		'image',
		'image-set'
	];

	/**
	 * entrypoint to check style="..." attributes
	 *
	 * @param string $value
	 * @return array|bool True if good or array containing error details
	 */
	public function checkStyleAttribute( string $value ) {
		if ( preg_match( '/[\000-\010\013\016-\037\177]/', $value ) ) {
			return [ 'invalid-control-character', 0, 0 ];
		}
		$cssParser = CSSParser::newFromString( $value );
		$decList = $cssParser->parseDeclarationList();
		$errors = $cssParser->getParseErrors();
		if ( $errors ) {
			// For style attributes with syntax errors, as a fallback
			// we see if MW's wikitext sanitizer would alter the
			// style attribute in any way. If no, then we assume it
			// is safe. There are enough files with errors in style
			// attributes that don't use any risky features like
			// css comments or url(), that this is worth it.
			$alteredStyle = Sanitizer::checkCss( $value );
			if ( $alteredStyle === $value ) {
				// No sketchy CSS features used, its ok despite errors
				return true;
			}
			return [ $errors[0][0], $errors[0][1], $errors[0][2] ];
		}

		$res = $this->validateTokens( $decList->toTokenArray() );
		if ( $res !== true ) {
			return $res;
		}
		return true;
	}

	/**
	 * entrypoint to check presentational attributes like fill
	 *
	 * Presentational attributes can contain CSS like values such as url()
	 *
	 * @param string $value
	 * @return array|bool True if good or array containing error details
	 */
	public function checkPresentationalAttribute( $value ) {
		if ( preg_match( '/[\000-\010\013\016-\037\177]/', $value ) ) {
			return [ 'invalid-control-character', 0, 0 ];
		}
		$cssParser = CSSParser::newFromString( $value );
		$cvList = $cssParser->parseComponentValueList();
		$errors = $cssParser->getParseErrors();
		if ( $errors ) {
			return [ $errors[0][0], $errors[0][1], $errors[0][2] ];
		}

		$res = $this->validateTokens( $cvList->toTokenArray() );
		if ( $res !== true ) {
			return $res;
		}
		return true;
	}

	/**
	 * Entrypoint to check <style> tags
	 *
	 * Note that data urls are allowed in @font-face
	 *
	 * @param string $value
	 * @return array|bool True if good or array containing error details
	 */
	public function checkStyleTag( $value ) {
		if ( preg_match( '/[\000-\010\013\016-\037\177]/', $value ) ) {
			return [ 'invalid-control-character', 0, 0 ];
		}
		$cssParser = CSSParser::newFromString( $value );
		$stylesheet = $cssParser->parseStylesheet();

		$errors = $cssParser->getParseErrors();
		if ( $errors ) {
			return [ $errors[0][0], $errors[0][1], $errors[0][2] ];
		}

		$topLevelRules = $stylesheet->getRuleList();
		foreach ( $topLevelRules as $rule ) {
			if ( $rule instanceof AtRule ) {
				$res = $this->validateAtRule( $rule );
				if ( $res !== true ) {
					return $res;
				}
				if ( $rule->getName() === 'font-face' ) {
					// @font-face has laxer rules
					$res = $this->validateTokens( $rule->toTokenArray(), true );
					if ( $res !== true ) {
						return $res;
					}
					continue;
				}
			}
			// Note, this incidentally @namespace foo url( 'https://example.com' );
			// We don't care about that but its super obscure so doesn't matter.
			$res = $this->validateTokens( $rule->toTokenArray() );
			if ( $res !== true ) {
				return $res;
			}
		}
		return true;
	}

	/**
	 * Validate that an at-rule is not on the ban list
	 *
	 * @param AtRule $rule
	 * @return array|bool
	 */
	private function validateAtRule( AtRule $rule ) {
		$name = strtolower( $rule->getName() );
		if (
			in_array( $name, self::BANNED_AT_RULE ) ||
			preg_match( '/[^-a-z]/', $name )
		) {
			return [ "banned-at-rule-$name", $rule->getPosition()[0], $rule->getPosition()[1] ];
		}
		return true;
	}

	/**
	 * Validate a list of tokens making sure none of them are url-like
	 *
	 * @param Token[] $tokens List of tokens
	 * @param bool $allowDataFonts Allow data: urls with a font type
	 * @return array|bool
	 */
	private function validateTokens( array $tokens, $allowDataFonts = false ) {
		// Go through all the tokens, and make sure none of them
		// are url(). Except we allow urls that reference the current
		// document. data: urls are not allowed because the predecessor
		// to this class banned them. It is unclear why, perhaps the worry
		// is embedding an SVG inside the data url to bypass sanitizer.
		// We also ban the image and image-set() functions because they
		// allow setting a url without the url function inside.
		// We also ban src() for forwards-compatibility.
		for ( $i = 0; $i < count( $tokens ); $i++ ) {
			$token = $tokens[$i];
			// unquoted urls are a T_URL where quoted urls are T_FUNCTION.
			if ( $token->type() === Token::T_URL ) {
				if (
					!str_starts_with( $token->value(), '#' ) &&
					!( $allowDataFonts &&
						( str_starts_with( $token->value(), 'data:font/' )
						|| str_starts_with( $token->value(), 'data:;base64,' ) ) /* T71008#717580 */
					)
				) {
					return [ 'banned-url', $token->getPosition()[0], $token->getPosition()[1] ];
				}
			} elseif ( $token->type() === Token::T_BAD_URL ) {
				// In theory browsers should ignore this, but
				// better to err on the side of failing when something
				// weird is going on.
				return [ 'banned-url', $token->getPosition()[0], $token->getPosition()[1] ];
			} elseif ( $token->type() === Token::T_FUNCTION && strtolower( $token->value() ) === 'url' ) {
				for ( $j = $i + 1; $j < count( $tokens ) && $tokens[$j]->type() === Token::T_WHITESPACE; $j++ );
				if ( $j < count( $tokens ) && $tokens[$j]->type() === Token::T_STRING ) {
					if (
						str_starts_with( $tokens[$j]->value(), '#' ) ||
						( $allowDataFonts &&
							( str_starts_with( $tokens[$j]->value(), 'data:font/' )
							|| str_starts_with( $tokens[$j]->value(), 'data:;base64,' ) ) /* T71008#717580 */
						)
					) {
						continue;
					}
				}
				return [ 'banned-url', $token->getPosition()[0], $token->getPosition()[1] ];
			} elseif (
				$token->type() === Token::T_FUNCTION &&
				in_array( strtolower( $token->value() ), self::BANNED_FUNCS )
			) {
				return [ 'banned-function-' . $token->value(), $token->getPosition()[0], $token->getPosition()[1] ];
			}
		}
		return true;
	}
}
