/**
 * cldrpluralparser.js
 * A parser engine for CLDR plural rules.
 *
 * Copyright 2012-2026 Santhosh Thottingal and other contributors
 * Released under the MIT license
 * http://opensource.org/licenses/MIT
 *
 * @source https://github.com/santhoshtr/CLDRPluralRuleParser
 * @author Santhosh Thottingal <santhosh.thottingal@gmail.com>
 * @author Timo Tijhof
 * @author Amir Aharoni
 */

/**
 * Shifts the decimal point of a base number string right by exp places.
 * Used to expand compact decimal notation per the CLDR spec.
 * e.g. shiftDecimal("1.2005", 3) => "1200.5"
 *      shiftDecimal("1", 6)      => "1000000"
 * @param {string} base  The number before the 'c'/'e' suffix (e.g. "1.2005")
 * @param {number} exp   The exponent (number of places to shift right)
 * @return {string}
 */
function shiftDecimal(base, exp) {
	let intPart, fracPart;
	const dotIndex = base.indexOf(".");
	if (dotIndex === -1) {
		intPart = base;
		fracPart = "";
	} else {
		intPart = base.slice(0, dotIndex);
		fracPart = base.slice(dotIndex + 1);
	}
	// Pad fracPart with zeros if exp exceeds its length
	while (fracPart.length < exp) {
		fracPart += "0";
	}
	const newIntPart = intPart + fracPart.slice(0, exp);
	const newFracPart = fracPart.slice(exp);
	// Remove leading zeros from integer part (but keep at least one digit)
	const trimmedInt = newIntPart.replace(/^0+/, "") || "0";
	return newFracPart.length > 0 ? `${trimmedInt}.${newFracPart}` : trimmedInt;
}

/**
 * Evaluates a plural rule in CLDR syntax for a number
 * @param {string} rule
 * @param {integer} number
 * @return {boolean} true if evaluation passed, false if evaluation failed.
 */
function pluralRuleParser(rule, number) {
	/*
    Syntax: see http://unicode.org/reports/tr35/#Language_Plural_Rules
    -----------------------------------------------------------------
    condition     = and_condition ('or' and_condition)*
        ('@integer' samples)?
        ('@decimal' samples)?
    and_condition = relation ('and' relation)*
    relation      = is_relation | in_relation | within_relation
    is_relation   = expr 'is' ('not')? value
    in_relation   = expr (('not')? 'in' | '=' | '!=') range_list
    within_relation = expr ('not')? 'within' range_list
    expr          = operand (('mod' | '%') value)?
    operand       = 'n' | 'i' | 'f' | 't' | 'v' | 'w' | 'e' | 'c'
    range_list    = (range | value) (',' range_list)*
    value         = digit+
    digit         = 0|1|2|3|4|5|6|7|8|9
    range         = value'..'value
    samples       = sampleRange (',' sampleRange)* (',' ('…'|'...'))?
    sampleRange   = decimalValue '~' decimalValue
    decimalValue  = value ('.' value)?
    */

	// We don't evaluate the samples section of the rule. Ignore it.
	rule = rule.split("@")[0].replace(/^\s*/, "").replace(/\s*$/, "");

	if (!rule.length) {
		// Empty rule or 'other' rule.
		return true;
	}

	// Parse compact decimal notation (e.g. "1.2005c3" or "1c6").
	// Per CLDR spec, n/i/f/t/v/w are computed after shifting the decimal point
	// by the exponent; c/e return the exponent itself.
	const compactMatch = /^(\d+(?:\.\d+)?)[ce](\d+)$/.exec(number);
	const compactExponent = compactMatch ? parseInt(compactMatch[2], 10) : 0;
	// expandedNumber is the base with the decimal shifted right by compactExponent places.
	const expandedNumber = compactMatch
		? shiftDecimal(compactMatch[1], compactExponent)
		: String(number);

	// Indicates the current position in the rule as we parse through it.
	// Shared among all parsing functions below.
	let pos = 0;

	const whitespace = makeRegexParser(/^\s+/);
	const value = makeRegexParser(/^\d+/);
	const _n_ = makeStringParser("n");
	const _i_ = makeStringParser("i");
	const _f_ = makeStringParser("f");
	const _t_ = makeStringParser("t");
	const _v_ = makeStringParser("v");
	const _w_ = makeStringParser("w");
	const _e_ = makeStringParser("e");
	const _c_ = makeStringParser("c");
	const _is_ = makeStringParser("is");
	const _isnot_ = makeStringParser("is not");
	const _isnot_sign_ = makeStringParser("!=");
	const _equal_ = makeStringParser("=");
	const _mod_ = makeStringParser("mod");
	const _percent_ = makeStringParser("%");
	const _not_ = makeStringParser("not");
	const _in_ = makeStringParser("in");
	const _within_ = makeStringParser("within");
	const _range_ = makeStringParser("..");
	const _comma_ = makeStringParser(",");
	const _or_ = makeStringParser("or");
	const _and_ = makeStringParser("and");

	function debug() {
		// console.log.apply(console, arguments);
	}

	debug("pluralRuleParser", rule, number);

	// Try parsers until one works, if none work return null
	function choice(parserSyntax) {
		return () => {
			let i, result;

			for (i = 0; i < parserSyntax.length; i++) {
				result = parserSyntax[i]();

				if (result !== null) {
					return result;
				}
			}

			return null;
		};
	}

	// Try several parserSyntax-es in a row.
	// All must succeed; otherwise, return null.
	// This is the only eager one.
	function sequence(parserSyntax) {
		let i;
		let parserRes;
		const originalPos = pos;
		const result = [];

		for (i = 0; i < parserSyntax.length; i++) {
			parserRes = parserSyntax[i]();

			if (parserRes === null) {
				pos = originalPos;

				return null;
			}

			result.push(parserRes);
		}

		return result;
	}

	// Run the same parser over and over until it fails.
	// Must succeed a minimum of n times; otherwise, return null.
	function nOrMore(n, p) {
		return () => {
			const originalPos = pos;
			const result = [];
			let parsed = p();

			while (parsed !== null) {
				result.push(parsed);
				parsed = p();
			}

			if (result.length < n) {
				pos = originalPos;

				return null;
			}

			return result;
		};
	}

	// Helpers - just make parserSyntax out of simpler JS builtin types
	function makeStringParser(s) {
		const len = s.length;

		return () => {
			let result = null;

			if (rule.substr(pos, len) === s) {
				result = s;
				pos += len;
			}

			return result;
		};
	}

	function makeRegexParser(regex) {
		return () => {
			const matches = rule.substr(pos).match(regex);

			if (matches === null) {
				return null;
			}

			pos += matches[0].length;

			return matches[0];
		};
	}

	/**
	 * Integer digits of n.
	 */
	function i() {
		let result = _i_();

		if (result === null) {
			debug(" -- failed i");

			return result;
		}

		result = parseInt(expandedNumber, 10);
		debug(" -- passed i ", result);

		return result;
	}

	/**
	 * Absolute value of the source number (integer and decimals).
	 */
	function n() {
		let result = _n_();

		if (result === null) {
			debug(" -- failed n");

			return result;
		}

		result = parseFloat(expandedNumber);
		debug(" -- passed n ", result);

		return result;
	}

	/**
	 * Visible fractional digits in n, with trailing zeros.
	 */
	function f() {
		let result = _f_();

		if (result === null) {
			debug(" -- failed f");

			return result;
		}

		result = `${expandedNumber}.`.split(".")[1] || 0;
		debug(" -- passed f ", result);

		return result;
	}

	/**
	 * Visible fractional digits in n, without trailing zeros.
	 */
	function t() {
		let result = _t_();

		if (result === null) {
			debug(" -- failed t");

			return result;
		}

		result = `${expandedNumber}.`.split(".")[1].replace(/0+$/, "") || 0;
		debug(" -- passed t ", result);

		return result;
	}

	/**
	 * Number of visible fraction digits in n, with trailing zeros.
	 */
	function v() {
		let result = _v_();

		if (result === null) {
			debug(" -- failed v");

			return result;
		}

		result = `${expandedNumber}.`.split(".")[1].length || 0;
		debug(" -- passed v ", result);

		return result;
	}

	/**
	 * Number of visible fraction digits in n, without trailing zeros.
	 */
	function w() {
		let result = _w_();

		if (result === null) {
			debug(" -- failed w");

			return result;
		}

		result = `${expandedNumber}.`.split(".")[1].replace(/0+$/, "").length || 0;
		debug(" -- passed w ", result);

		return result;
	}

	/**
	 * Compact decimal exponent value (e), 0 for plain numbers.
	 * 'e' is a deprecated synonym for 'c'.
	 */
	function e() {
		let result = _e_();

		if (result === null) {
			debug(" -- failed e");

			return result;
		}

		result = compactExponent;
		debug(" -- passed e ", result);

		return result;
	}

	/**
	 * Compact decimal exponent value (c), 0 for plain numbers.
	 */
	function c() {
		let result = _c_();

		if (result === null) {
			debug(" -- failed c");

			return result;
		}

		result = compactExponent;
		debug(" -- passed c ", result);

		return result;
	}

	// operand       = 'n' | 'i' | 'f' | 't' | 'v' | 'w' | 'e' | 'c'
	const operand = choice([n, i, f, t, v, w, e, c]);

	// expr          = operand (('mod' | '%') value)?
	const expression = choice([mod, operand]);

	function mod() {
		const result = sequence([
			operand,
			whitespace,
			choice([_mod_, _percent_]),
			whitespace,
			value,
		]);

		if (result === null) {
			debug(" -- failed mod");

			return null;
		}

		debug(
			" -- passed ",
			parseInt(result[0], 10),
			result[2],
			parseInt(result[4], 10),
		);

		return parseFloat(result[0]) % parseInt(result[4], 10);
	}

	function not() {
		const result = sequence([whitespace, _not_]);

		if (result === null) {
			debug(" -- failed not");

			return null;
		}

		return result[1];
	}

	// is_relation   = expr 'is' ('not')? value
	function is() {
		const result = sequence([
			expression,
			whitespace,
			choice([_is_]),
			whitespace,
			value,
		]);

		if (result !== null) {
			debug(" -- passed is :", result[0], " == ", parseInt(result[4], 10));

			return result[0] === parseInt(result[4], 10);
		}

		debug(" -- failed is");

		return null;
	}

	// is_relation   = expr 'is' ('not')? value
	function isnot() {
		const result = sequence([
			expression,
			whitespace,
			choice([_isnot_, _isnot_sign_]),
			whitespace,
			value,
		]);

		if (result !== null) {
			debug(" -- passed isnot: ", result[0], " != ", parseInt(result[4], 10));

			return result[0] !== parseInt(result[4], 10);
		}

		debug(" -- failed isnot");

		return null;
	}

	function not_in() {
		let i;
		let range_list;
		const result = sequence([
			expression,
			whitespace,
			_isnot_sign_,
			whitespace,
			rangeList,
		]);

		if (result !== null) {
			debug(" -- passed not_in: ", result[0], " != ", result[4]);
			range_list = result[4];

			for (i = 0; i < range_list.length; i++) {
				if (parseInt(range_list[i], 10) === parseFloat(result[0])) {
					return false;
				}
			}

			return true;
		}

		debug(" -- failed not_in");

		return null;
	}

	// range_list    = (range | value) (',' range_list)*
	function rangeList() {
		const result = sequence([choice([range, value]), nOrMore(0, rangeTail)]);
		let resultList = [];

		if (result !== null) {
			resultList = resultList.concat(result[0]);

			if (result[1][0]) {
				resultList = resultList.concat(result[1][0]);
			}

			return resultList;
		}

		debug(" -- failed rangeList");

		return null;
	}

	function rangeTail() {
		// ',' range_list
		const result = sequence([_comma_, rangeList]);

		if (result !== null) {
			return result[1];
		}

		debug(" -- failed rangeTail");

		return null;
	}

	// range         = value'..'value
	function range() {
		let i;
		let array;
		let left;
		let right;
		const result = sequence([value, _range_, value]);

		if (result !== null) {
			debug(" -- passed range");

			array = [];
			left = parseInt(result[0], 10);
			right = parseInt(result[2], 10);

			for (i = left; i <= right; i++) {
				array.push(i);
			}

			return array;
		}

		debug(" -- failed range");

		return null;
	}

	function _in() {
		// in_relation   = expr ('not')? 'in' range_list
		const result = sequence([
			expression,
			nOrMore(0, not),
			whitespace,
			choice([_in_, _equal_]),
			whitespace,
			rangeList,
		]);

		if (result !== null) {
			debug(" -- passed _in:", result);

			const rangeList = result[5];

			for (let i = 0; i < rangeList.length; i++) {
				if (parseInt(rangeList[i], 10) === parseFloat(result[0])) {
					return result[1][0] !== "not";
				}
			}

			return result[1][0] === "not";
		}

		debug(" -- failed _in ");

		return null;
	}

	/**
	 * The difference between "in" and "within" is that
	 * "in" only includes integers in the specified range,
	 * while "within" includes all values.
	 */
	function within() {
		// within_relation = expr ('not')? 'within' range_list
		const result = sequence([
			expression,
			nOrMore(0, not),
			whitespace,
			_within_,
			whitespace,
			rangeList,
		]);

		if (result !== null) {
			debug(" -- passed within");

			const range_list = result[5];

			if (
				result[0] >= parseInt(range_list[0], 10) &&
				result[0] < parseInt(range_list[range_list.length - 1], 10)
			) {
				return result[1][0] !== "not";
			}

			return result[1][0] === "not";
		}

		debug(" -- failed within ");

		return null;
	}

	// relation      = is_relation | in_relation | within_relation
	const relation = choice([is, not_in, isnot, _in, within]);

	// and_condition = relation ('and' relation)*
	function and() {
		let i;
		const result = sequence([relation, nOrMore(0, andTail)]);

		if (result) {
			if (!result[0]) {
				return false;
			}

			for (i = 0; i < result[1].length; i++) {
				if (!result[1][i]) {
					return false;
				}
			}

			return true;
		}

		debug(" -- failed and");

		return null;
	}

	// ('and' relation)*
	function andTail() {
		const result = sequence([whitespace, _and_, whitespace, relation]);

		if (result !== null) {
			debug(" -- passed andTail", result);

			return result[3];
		}

		debug(" -- failed andTail");

		return null;
	}
	//  ('or' and_condition)*
	function orTail() {
		const result = sequence([whitespace, _or_, whitespace, and]);

		if (result !== null) {
			debug(" -- passed orTail: ", result[3]);

			return result[3];
		}

		debug(" -- failed orTail");

		return null;
	}

	// condition     = and_condition ('or' and_condition)*
	function condition() {
		let i;
		const result = sequence([and, nOrMore(0, orTail)]);

		if (result) {
			for (i = 0; i < result[1].length; i++) {
				if (result[1][i]) {
					return true;
				}
			}

			return result[0];
		}

		return false;
	}

	const result = condition();

	/**
	 * For success, the pos must have gotten to the end of the rule
	 * and returned a non-null.
	 * n.b. This is part of language infrastructure,
	 * so we do not throw an internationalizable message.
	 */
	if (result === null) {
		throw new Error(
			`Parse error at position ${pos.toString()} for rule: ${rule}`,
		);
	}

	if (pos !== rule.length) {
		debug(
			"Warning: Rule not parsed completely. Parser stopped at ",
			rule.substr(0, pos),
			" for rule: ",
			rule,
		);
	}

	return result;
}

export default pluralRuleParser;
