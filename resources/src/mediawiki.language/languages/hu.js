/*!
 * Hungarian language functions
 * @author Tisza Gergő
 */

mediaWiki.language.vowelsBack = [ 'a', 'á', 'o', 'ó', 'u', 'ú' ];
mediaWiki.language.vowelsFrontIllabial = [ 'e', 'é', 'i', 'í' ];
mediaWiki.language.vowelsFrontLabial = [ 'ö', 'ő', 'ü', 'ű' ];
mediaWiki.language.vowels = mediaWiki.language.vowelsBack
	.concat( mediaWiki.language.vowelsFrontIllabial )
	.concat( mediaWiki.language.vowelsFrontLabial );
mediaWiki.language.digraphs = [ 'cs', 'dz', 'gy', 'ly', 'ny', 'sz', 'zs' ];

/**
 * Callback for {{GRAMMAR:<type>|<param>|...}}
 * For detailed documentation see the PHP function:
 * @see LanguageHu::convertGrammar()
 * @param {String} type
 * @param {String} param1
 * @param {String} [param2]
 * @param {String} [param3]
 * @param {String} [param4]
 * @return {String}
 */
mediaWiki.language.convertGrammar = function (
	type, param1, param2, param3, param4
) {
	switch ( type ) {
		case 'suffix':
			return this.addSuffix( param1, param2, param3, param4 );
		case 'article':
			return this.getArticle( param1 );
		default:
			return this.addSuffixBC( param1, param2 );
	}
}

/**
 * PHP-like substr() that handles negative arguments (most browsers do but IE doesn't).
 * Makes comparing the JS and PHP implementation a little less tedious.
 * @private
 * @param {String} word
 * @param {number} start Position of first character (from end of string if negative)
 * @oaram {number} [length] Number of characters
 */
mediaWiki.language.substr = function ( word, start, length ) {
	if ( start < 0 ) {
		start = word.length - start;
	}
	return word.substr( start, length );
}

/**
 * Combine word (presumably a noun) with suffix according to Hungarian grammar.
 * For detailed documentation see the PHP function:
 * @see LanguageHu::addSuffix()
 * @param {String} word
 * @param {String} backSuffix
 * @param {String} [frontSuffix]
 * @param {String} [labialSuffix]
 */
mediaWiki.language.addSuffix = function ( word, backSuffix, frontSuffix, labialSuffix ) {
	var i, vowelHarmony, lastVowel, hasBackVowel, hasFrontVowel, suffix,
		wordEndVowelReplacements, lastCharacter, lastTwoCharacters, digraph;

	// calculate vowel harmony + get last vowel
	if ( this.substr( word, -4 ).toLowerCase() === 'wiki' ) {
		lastVowel = 'i';
		vowelHarmony = 'front';
	} else {
		for ( i = 0; i < word.length; i++ ) {
			if ( word[i] === ' ' || word[i] === '-' || word[i] === '–' ) {
				hasBackVowel = hasFrontVowel = lastVowel = undefined;
				continue;
			} else if ( !$.inArray( word[i], this.vowels ) ) {
				continue;
			}
			lastVowel = word[i];
			if ( $.inArray( lastVowel, this.vowelsBack ) ) {
				hasBackVowel = true;
			} else {
				hasFrontVowel = true;
			}
		}

		if ( !lastVowel ) {
			return '';
		}

		if ( hasBackVowel && hasFrontVowel ) {
			vowelHarmony = 'mixed';
		} else if ( hasBackVowel ) {
			vowelHarmony = 'back';
		} else {
			vowelHarmony = 'front';
		}
	}

	// select suffix that matches vowel harmony
	if ( !frontSuffix ) {
		suffix = backSuffix;
	} else if ( vowelHarmony === 'back' ) {
		suffix = backSuffix;
	} else if ( vowelHarmony === 'front' ) {
		if ( labialSuffix && $.inArray( lastVowel, this.vowelsFrontLabial ) ) {
			suffix = labialSuffix;
		} else {
			suffix = frontSuffix;
		}
	} else { // $vowelHarmony === 'mixed'
		if ( $.inArray( lastVowel, this.vowelsBack ) ) {
			suffix = backSuffix;
		} else if ( $.inArray( lastVowel, this.vowelsFrontIllabial ) ) {
			suffix = backSuffix;
		} else { // lastVowel in vowelsFrontLabial
			suffix = labialSuffix || frontSuffix;
		}
	}

	// change word-ending vowel
	lastCharacter = this.substr( word, -1 );
	wordEndVowelReplacements = { 'a': 'á', 'e': 'é', 'o': 'ó' };
	if ( lastCharacter in wordEndVowelReplacements ) {
		word = this.substr( word, 0, -1 ) + wordEndVowelReplacements[lastCharacter];
	}

	lastCharacter = this.substr( word, -1 );
	lastTwoCharacters = this.substr( word, -2 );

	// change start of suffix: v assimilates if the word ends with a consonant
	if ( this.substr( suffix, 0, 1 ) === 'v' && !$.inArray( lastCharacter, this.vowels ) ) {
		if ( lastTwoCharacters === lastCharacter + lastCharacter ) {
			suffix = this.substr( suffix, 1 );
		} else if ( $.inArray( lastTwoCharacters, this.digraphs ) ) {
			if ( this.substr( word, -2, 1 ) === this.substr( word, -3, 1 ) ) {
				suffix = this.substr( suffix, 1 );
			} else {
				digraph = this.substr( word, -2, 1 ) + this.substr( word, -2 );
				suffix = this.substr( suffix, 1 );
				word = this.substr( word, 0, -2 ) + digraph;
			}
		} else {
			suffix = this.substr( word, -1 ) . this.substr( suffix, 1 );
		}
	// leave out first character of the suffix if its a vowel and the word also ends with a vowel
	} else if (
		$.inArray( lastCharacter, this.vowels )
		&& $.inArray( this.substr( suffix, 0, 1), this.vowels )
	) {
		suffix = this.substr( suffix, 1 );
	}

	return word + suffix;
}

/**
 * B/C wrapper for the old suffix syntax.
 * @param {String} word
 * @param {String} form
 */
mediaWiki.language.addSuffixBC = function ( word, form ) {
	var grammarForms = mediaWiki.language.getData( 'hu', 'grammarForms' );
	if ( grammarForms && grammarForms[ form ] ) {
		return grammarForms[ form ][ word ];
	}
	switch ( form ) {
		case 'rol':
			return this.addSuffix( word, 'ról', 'ről' );
		case 'ba':
			return this.addSuffix( word, 'ba', 'be' );
		case 'k':
			return this.addSuffix( word, 'k' );
	}
	return word;
};

/**
 * Returns the definite article "a"/"az" in the form that's appropriate for this word.
 * @param {String} word
 */
mediaWiki.language.getArticle = function ( word ) {
	return $.inArray( word, mediaWiki.language.vowels ) ? 'az' : 'a';
}

