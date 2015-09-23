<?php
/**
 * PHP port of CSSJanus.
 * https://github.com/cssjanus/php-cssjanus
 *
 * Copyright 2008 Google Inc.
 * Copyright 2010 Roan Kattouw
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @file
 */

/**
 * This is a PHP port of CSSJanus, a utility that transforms CSS style sheets
 * written for LTR to RTL.
 *
 * Original code: http://code.google.com/p/cssjanus/source/browse/trunk/cssjanus.py
 *
 * @author Lindsey Simon <elsigh@google.com>
 * @author Roan Kattouw
 */
class CSSJanus {
	// Patterns defined as null are built dynamically by buildPatterns()
	private static $patterns = array(
		'tmpToken' => '`TMP`',
		'nonAscii' => '[\200-\377]',
		'unicode' => '(?:(?:\\[0-9a-f]{1,6})(?:\r\n|\s)?)',
		'num' => '(?:[0-9]*\.[0-9]+|[0-9]+)',
		'unit' => '(?:em|ex|px|cm|mm|in|pt|pc|deg|rad|grad|ms|s|hz|khz|%)',
		'body_selector' => 'body\s*{\s*',
		'direction' => 'direction\s*:\s*',
		'escape' => null,
		'nmstart' => null,
		'nmchar' => null,
		'ident' => null,
		'quantity' => null,
		'possibly_negative_quantity' => null,
		'color' => null,
		'url_special_chars' => '[!#$%&*-~]',
		'valid_after_uri_chars' => '[\'\"]?\s*',
		'url_chars' => null,
		'lookahead_not_open_brace' => null,
		'lookahead_not_closing_paren' => null,
		'lookahead_for_closing_paren' => null,
		'lookahead_not_letter' => '(?![a-zA-Z])',
		'lookbehind_not_letter' => '(?<![a-zA-Z])',
		'chars_within_selector' => '[^\}]*?',
		'noflip_annotation' => '\/\*\!?\s*@noflip\s*\*\/',
		'noflip_single' => null,
		'noflip_class' => null,
		'comment' => '/\/\*[^*]*\*+([^\/*][^*]*\*+)*\//',
		'direction_ltr' => null,
		'direction_rtl' => null,
		'left' => null,
		'right' => null,
		'left_in_url' => null,
		'right_in_url' => null,
		'ltr_in_url' => null,
		'rtl_in_url' => null,
		'cursor_east' => null,
		'cursor_west' => null,
		'four_notation_quantity' => null,
		'four_notation_color' => null,
		'border_radius' => null,
		'box_shadow' => null,
		'text_shadow1' => null,
		'text_shadow2' => null,
		'bg_horizontal_percentage' => null,
		'bg_horizontal_percentage_x' => null,
	);

	/**
	 * Build patterns we can't define above because they depend on other patterns.
	 */
	private static function buildPatterns() {
		if (!is_null(self::$patterns['escape'])) {
			// Patterns have already been built
			return;
		}

		// @codingStandardsIgnoreStart Generic.Files.LineLength.TooLong
		$patterns =& self::$patterns;
		$patterns['escape'] = "(?:{$patterns['unicode']}|\\[^\r\n\f0-9a-f])";
		$patterns['nmstart'] = "(?:[_a-z]|{$patterns['nonAscii']}|{$patterns['escape']})";
		$patterns['nmchar'] = "(?:[_a-z0-9-]|{$patterns['nonAscii']}|{$patterns['escape']})";
		$patterns['ident'] = "-?{$patterns['nmstart']}{$patterns['nmchar']}*";
		$patterns['quantity'] = "{$patterns['num']}(?:\s*{$patterns['unit']}|{$patterns['ident']})?";
		$patterns['possibly_negative_quantity'] = "((?:-?{$patterns['quantity']})|(?:inherit|auto))";
		$patterns['color'] = "(#?{$patterns['nmchar']}+|(?:rgba?|hsla?)\([ \d.,%-]+\))";
		$patterns['url_chars'] = "(?:{$patterns['url_special_chars']}|{$patterns['nonAscii']}|{$patterns['escape']})*";
		$patterns['lookahead_not_open_brace'] = "(?!({$patterns['nmchar']}|\r?\n|\s|#|\:|\.|\,|\+|>|\(|\)|\[|\]|=|\*=|~=|\^=|'[^']*'])*?{)";
		$patterns['lookahead_not_closing_paren'] = "(?!{$patterns['url_chars']}?{$patterns['valid_after_uri_chars']}\))";
		$patterns['lookahead_for_closing_paren'] = "(?={$patterns['url_chars']}?{$patterns['valid_after_uri_chars']}\))";
		$patterns['noflip_single'] = "/({$patterns['noflip_annotation']}{$patterns['lookahead_not_open_brace']}[^;}]+;?)/i";
		$patterns['noflip_class'] = "/({$patterns['noflip_annotation']}{$patterns['chars_within_selector']}})/i";
		$patterns['direction_ltr'] = "/({$patterns['direction']})ltr/i";
		$patterns['direction_rtl'] = "/({$patterns['direction']})rtl/i";
		$patterns['left'] = "/{$patterns['lookbehind_not_letter']}(left){$patterns['lookahead_not_letter']}{$patterns['lookahead_not_closing_paren']}{$patterns['lookahead_not_open_brace']}/i";
		$patterns['right'] = "/{$patterns['lookbehind_not_letter']}(right){$patterns['lookahead_not_letter']}{$patterns['lookahead_not_closing_paren']}{$patterns['lookahead_not_open_brace']}/i";
		$patterns['left_in_url'] = "/{$patterns['lookbehind_not_letter']}(left){$patterns['lookahead_for_closing_paren']}/i";
		$patterns['right_in_url'] = "/{$patterns['lookbehind_not_letter']}(right){$patterns['lookahead_for_closing_paren']}/i";
		$patterns['ltr_in_url'] = "/{$patterns['lookbehind_not_letter']}(ltr){$patterns['lookahead_for_closing_paren']}/i";
		$patterns['rtl_in_url'] = "/{$patterns['lookbehind_not_letter']}(rtl){$patterns['lookahead_for_closing_paren']}/i";
		$patterns['cursor_east'] = "/{$patterns['lookbehind_not_letter']}([ns]?)e-resize/";
		$patterns['cursor_west'] = "/{$patterns['lookbehind_not_letter']}([ns]?)w-resize/";
		$patterns['four_notation_quantity_props'] = "((?:margin|padding|border-width)\s*:\s*)";
		$patterns['four_notation_quantity'] = "/{$patterns['four_notation_quantity_props']}{$patterns['possibly_negative_quantity']}(\s+){$patterns['possibly_negative_quantity']}(\s+){$patterns['possibly_negative_quantity']}(\s+){$patterns['possibly_negative_quantity']}(\s*(?:!important\s*)?[;}])/i";
		$patterns['four_notation_color'] = "/((?:-color|border-style)\s*:\s*){$patterns['color']}(\s+){$patterns['color']}(\s+){$patterns['color']}(\s+){$patterns['color']}(\s*(?:!important\s*)?[;}])/i";
		$patterns['border_radius'] = "/(border-radius\s*:\s*)([^;}]*)/";
		$patterns['box_shadow'] = "/(box-shadow\s*:\s*(?:inset\s*)?){$patterns['possibly_negative_quantity']}/i";
		$patterns['text_shadow1'] = "/(text-shadow\s*:\s*){$patterns['color']}(\s*){$patterns['possibly_negative_quantity']}/i";
		$patterns['text_shadow2'] = "/(text-shadow\s*:\s*){$patterns['possibly_negative_quantity']}/i";
		$patterns['bg_horizontal_percentage'] = "/(background(?:-position)?\s*:\s*(?:[^:;}\s]+\s+)*?)({$patterns['quantity']})/i";
		$patterns['bg_horizontal_percentage_x'] = "/(background-position-x\s*:\s*)(-?{$patterns['num']}%)/i";
		// @codingStandardsIgnoreEnd

	}

	/**
	 * Transform an LTR stylesheet to RTL
	 * @param string $css stylesheet to transform
	 * @param $swapLtrRtlInURL Boolean: If true, swap 'ltr' and 'rtl' in URLs
	 * @param $swapLeftRightInURL Boolean: If true, swap 'left' and 'right' in URLs
	 * @return string Transformed stylesheet
	 */
	public static function transform($css, $swapLtrRtlInURL = false, $swapLeftRightInURL = false) {
		// We wrap tokens in ` , not ~ like the original implementation does.
		// This was done because ` is not a legal character in CSS and can only
		// occur in URLs, where we escape it to %60 before inserting our tokens.
		$css = str_replace('`', '%60', $css);

		self::buildPatterns();

		// Tokenize single line rules with /* @noflip */
		$noFlipSingle = new CSSJanusTokenizer(self::$patterns['noflip_single'], '`NOFLIP_SINGLE`');
		$css = $noFlipSingle->tokenize($css);

		// Tokenize class rules with /* @noflip */
		$noFlipClass = new CSSJanusTokenizer(self::$patterns['noflip_class'], '`NOFLIP_CLASS`');
		$css = $noFlipClass->tokenize($css);

		// Tokenize comments
		$comments = new CSSJanusTokenizer(self::$patterns['comment'], '`C`');
		$css = $comments->tokenize($css);

		// LTR->RTL fixes start here
		$css = self::fixDirection($css);
		if ($swapLtrRtlInURL) {
			$css = self::fixLtrRtlInURL($css);
		}

		if ($swapLeftRightInURL) {
			$css = self::fixLeftRightInURL($css);
		}
		$css = self::fixLeftAndRight($css);
		$css = self::fixCursorProperties($css);
		$css = self::fixFourPartNotation($css);
		$css = self::fixBorderRadius($css);
		$css = self::fixBackgroundPosition($css);
		$css = self::fixShadows($css);

		// Detokenize stuff we tokenized before
		$css = $comments->detokenize($css);
		$css = $noFlipClass->detokenize($css);
		$css = $noFlipSingle->detokenize($css);

		return $css;
	}

	/**
	 * Replace direction: ltr; with direction: rtl; and vice versa.
	 *
	 * The original implementation only does this inside body selectors
	 * and misses "body\n{\ndirection:ltr;\n}". This function does not have
	 * these problems.
	 *
	 * See https://code.google.com/p/cssjanus/issues/detail?id=15
	 *
	 * @param $css string
	 * @return string
	 */
	private static function fixDirection($css) {
		$css = preg_replace(
			self::$patterns['direction_ltr'],
			'$1' . self::$patterns['tmpToken'],
			$css
		);
		$css = preg_replace(self::$patterns['direction_rtl'], '$1ltr', $css);
		$css = str_replace(self::$patterns['tmpToken'], 'rtl', $css);

		return $css;
	}

	/**
	 * Replace 'ltr' with 'rtl' and vice versa in background URLs
	 * @param $css string
	 * @return string
	 */
	private static function fixLtrRtlInURL($css) {
		$css = preg_replace(self::$patterns['ltr_in_url'], self::$patterns['tmpToken'], $css);
		$css = preg_replace(self::$patterns['rtl_in_url'], 'ltr', $css);
		$css = str_replace(self::$patterns['tmpToken'], 'rtl', $css);

		return $css;
	}

	/**
	 * Replace 'left' with 'right' and vice versa in background URLs
	 * @param $css string
	 * @return string
	 */
	private static function fixLeftRightInURL($css) {
		$css = preg_replace(self::$patterns['left_in_url'], self::$patterns['tmpToken'], $css);
		$css = preg_replace(self::$patterns['right_in_url'], 'left', $css);
		$css = str_replace(self::$patterns['tmpToken'], 'right', $css);

		return $css;
	}

	/**
	 * Flip rules like left: , padding-right: , etc.
	 * @param $css string
	 * @return string
	 */
	private static function fixLeftAndRight($css) {
		$css = preg_replace(self::$patterns['left'], self::$patterns['tmpToken'], $css);
		$css = preg_replace(self::$patterns['right'], 'left', $css);
		$css = str_replace(self::$patterns['tmpToken'], 'right', $css);

		return $css;
	}

	/**
	 * Flip East and West in rules like cursor: nw-resize;
	 * @param $css string
	 * @return string
	 */
	private static function fixCursorProperties($css) {
		$css = preg_replace(
			self::$patterns['cursor_east'],
			'$1' . self::$patterns['tmpToken'],
			$css
		);
		$css = preg_replace(self::$patterns['cursor_west'], '$1e-resize', $css);
		$css = str_replace(self::$patterns['tmpToken'], 'w-resize', $css);

		return $css;
	}

	/**
	 * Swap the second and fourth parts in four-part notation rules like
	 * padding: 1px 2px 3px 4px;
	 *
	 * Unlike the original implementation, this function doesn't suffer from
	 * the bug where whitespace is not preserved when flipping four-part rules
	 * and four-part color rules with multiple whitespace characters between
	 * colors are not recognized.
	 * See https://code.google.com/p/cssjanus/issues/detail?id=16
	 * @param $css string
	 * @return string
	 */
	private static function fixFourPartNotation($css) {
		$css = preg_replace(self::$patterns['four_notation_quantity'], '$1$2$3$8$5$6$7$4$9', $css);
		$css = preg_replace(self::$patterns['four_notation_color'], '$1$2$3$8$5$6$7$4$9', $css);
		return $css;
	}

	/**
	 * Swaps appropriate corners in border-radius values.
	 *
	 * @param $css string
	 * @return string
	 */
	private static function fixBorderRadius($css) {
		$css = preg_replace_callback(self::$patterns['border_radius'], function ($matches) {
			$pre = $matches[1];
			$values = $matches[2];
			$numValues = count(preg_split('/\s+/', trim($values)));
			switch ($numValues) {
				case 4:
					$values = preg_replace('/^(\S+)(\s*)(\S+)(\s*)(\S+)(\s*)(\S+)/', '$3$2$1$4$7$6$5', $values);
					break;
				case 3:
				case 2:
					$values = preg_replace('/^(\S+)(\s*)(\S+)/', '$3$2$1', $values);
					break;
			}
			return $pre . $values;
		}, $css);

		return $css;
	}

	/**
	 * Negates horizontal offset in box-shadow and text-shadow rules.
	 *
	 * @param $css string
	 * @return string
	 */
	private static function fixShadows($css) {
		// Flips the sign of a CSS value, possibly with a unit.
		// (We can't just negate the value with unary minus due to the units.)
		$flipSign = function ($cssValue) {
			// Don't mangle zeroes
			if (floatval($cssValue) === 0.0) {
				return $cssValue;
			} elseif ($cssValue[0] === '-') {
				return substr($cssValue, 1);
			} else {
				return "-" . $cssValue;
			}
		};

		$css = preg_replace_callback(self::$patterns['box_shadow'], function ($matches) use ($flipSign) {
			return $matches[1] . $flipSign($matches[2]);
		}, $css);

		$css = preg_replace_callback(self::$patterns['text_shadow1'], function ($matches) use ($flipSign) {
			return $matches[1] . $matches[2] . $matches[3] . $flipSign($matches[4]);
		}, $css);

		$css = preg_replace_callback(self::$patterns['text_shadow2'], function ($matches) use ($flipSign) {
			return $matches[1] . $flipSign($matches[2]);
		}, $css);

		return $css;
	}

	/**
	 * Flip horizontal background percentages.
	 * @param $css string
	 * @return string
	 */
	private static function fixBackgroundPosition($css) {
		$replaced = preg_replace_callback(
			self::$patterns['bg_horizontal_percentage'],
			array('self', 'calculateNewBackgroundPosition'),
			$css
		);
		if ($replaced !== null) {
			// preg_replace_callback() sometimes returns null
			$css = $replaced;
		}
		$replaced = preg_replace_callback(
			self::$patterns['bg_horizontal_percentage_x'],
			array('self', 'calculateNewBackgroundPosition'),
			$css
		);
		if ($replaced !== null) {
			$css = $replaced;
		}

		return $css;
	}

	/**
	 * Callback for fixBackgroundPosition()
	 * @param $matches array
	 * @return string
	 */
	private static function calculateNewBackgroundPosition($matches) {
		$value = $matches[2];
		if (substr($value, -1) === '%') {
			$idx = strpos($value, '.');
			if ($idx !== false) {
				$len = strlen($value) - $idx - 2;
				$value = number_format(100 - $value, $len) . '%';
			} else {
				$value = (100 - $value) . '%';
			}
		}
		return $matches[1] . $value;
	}
}

/**
 * Utility class used by CSSJanus that tokenizes and untokenizes things we want
 * to protect from being janused.
 * @author Roan Kattouw
 */
class CSSJanusTokenizer {
	private $regex;
	private $token;
	private $originals;

	/**
	 * Constructor
	 * @param string $regex Regular expression whose matches to replace by a token.
	 * @param string $token Token
	 */
	public function __construct($regex, $token) {
		$this->regex = $regex;
		$this->token = $token;
		$this->originals = array();
	}

	/**
	 * Replace all occurrences of $regex in $str with a token and remember
	 * the original strings.
	 * @param string $str to tokenize
	 * @return string Tokenized string
	 */
	public function tokenize($str) {
		return preg_replace_callback($this->regex, array($this, 'tokenizeCallback'), $str);
	}

	/**
	 * @param $matches array
	 * @return string
	 */
	private function tokenizeCallback($matches) {
		$this->originals[] = $matches[0];
		return $this->token;
	}

	/**
	 * Replace tokens with their originals. If multiple strings were tokenized, it's important they be
	 * detokenized in exactly the SAME ORDER.
	 * @param string $str previously run through tokenize()
	 * @return string Original string
	 */
	public function detokenize($str) {
		// PHP has no function to replace only the first occurrence or to
		// replace occurrences of the same string with different values,
		// so we use preg_replace_callback() even though we don't really need a regex
		return preg_replace_callback(
			'/' . preg_quote($this->token, '/') . '/',
			array($this, 'detokenizeCallback'),
			$str
		);
	}

	/**
	 * @param $matches
	 * @return mixed
	 */
	private function detokenizeCallback($matches) {
		$retval = current($this->originals);
		next($this->originals);

		return $retval;
	}
}
