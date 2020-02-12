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
use MediaWiki\Linker\LinkTarget;

/**
 * The shared interface for all language converters.
 *
 * @ingroup Language
 * @internal
 */
interface ILanguageConverter {

	/**
	 * Get all valid variants.
	 * Call this instead of using $this->mVariants directly.
	 * @return string[] Contains all valid variants
	 */
	public function getVariants();

	/**
	 * In case some variant is not defined in the markup, we need
	 * to have some fallback. For example, in zh, normally people
	 * will define zh-hans and zh-hant, but less so for zh-sg or zh-hk.
	 * when zh-sg is preferred but not defined, we will pick zh-hans
	 * in this case. Right now this is only used by zh.
	 *
	 * @param string $variant The language code of the variant
	 * @return string|array The code of the fallback language or the
	 *   main code if there is no fallback
	 */
	public function getVariantFallbacks( $variant );

	/**
	 * Get the title produced by the conversion rule.
	 * @return string The converted title text
	 */
	public function getConvRuleTitle();

	/**
	 * Get preferred language variant.
	 * @return string The preferred language code
	 */
	public function getPreferredVariant();

	/**
	 * Get default variant.
	 * This function would not be affected by user's settings
	 * @return string The default variant code
	 */
	public function getDefaultVariant();

	/**
	 * Validate the variant and return an appropriate strict internal
	 * variant code if one exists.  Compare to Language::hasVariant()
	 * which does a strict test.
	 *
	 * @param string|null $variant The variant to validate
	 * @return mixed Returns an equivalent valid variant code if possible,
	 *   null otherwise
	 */
	public function validateVariant( $variant = null );

	/**
	 * Get the variant specified in the URL
	 *
	 * @return mixed Variant if one found, null otherwise
	 */
	public function getURLVariant();

	/**
	 * Dictionary-based conversion.
	 * This function would not parse the conversion rules.
	 * If you want to parse rules, try to use convert() or
	 * convertTo().
	 *
	 * @param string $text The text to be converted
	 * @param bool|string $toVariant The target language code
	 * @return string The converted text
	 */
	public function autoConvert( $text, $toVariant = false );

	/**
	 * Translate a string to a variant.
	 * Doesn't parse rules or do any of that other stuff, for that use
	 * convert() or convertTo().
	 *
	 * @param string $text Text to convert
	 * @param string $variant Variant language code
	 * @return string Translated text
	 */
	public function translate( $text, $variant );

	/**
	 * Call translate() to convert text to all valid variants.
	 *
	 * @param string $text The text to be converted
	 * @return array Variant => converted text
	 */
	public function autoConvertToAllVariants( $text );

	/**
	 * Auto convert a LinkTarget object to a readable string in the
	 * preferred variant.
	 *
	 * @param LinkTarget $linkTarget
	 * @return string Converted title text
	 */
	public function convertTitle( LinkTarget $linkTarget );

	/**
	 * Get the namespace display name in the preferred variant.
	 *
	 * @param int $index Namespace id
	 * @param string|null $variant Variant code or null for preferred variant
	 * @return string Namespace name for display
	 */
	public function convertNamespace( $index, $variant = null );

	/**
	 * Convert text to different variants of a language. The automatic
	 * conversion is done in autoConvert(). Here we parse the text
	 * marked with -{}-, which specifies special conversions of the
	 * text that can not be accomplished in autoConvert().
	 *
	 * Syntax of the markup:
	 * -{code1:text1;code2:text2;...}-  or
	 * -{flags|code1:text1;code2:text2;...}-  or
	 * -{text}- in which case no conversion should take place for text
	 *
	 * @warning Glossary state is maintained between calls. Never feed this
	 *   method input that hasn't properly been escaped as it may result in
	 *   an XSS in subsequent calls, even if those subsequent calls properly
	 *   escape things.
	 * @param string $text Text to be converted, already html escaped.
	 * @return string Converted text (html)
	 */
	public function convert( $text );

	/**
	 * Same as convert() except a extra parameter to custom variant.
	 *
	 * @param string $text Text to be converted, already html escaped
	 * @param-taint $text exec_html
	 * @param string $variant The target variant code
	 * @return string Converted text
	 * @return-taint escaped
	 */
	public function convertTo( $text, $variant );

	/**
	 * If a language supports multiple variants, it is possible that
	 * non-existing link in one variant actually exists in another variant.
	 * This function tries to find it. See e.g. LanguageZh.php
	 * The input parameters may be modified upon return
	 *
	 * @param string &$link The name of the link
	 * @param Title &$nt The title object of the link
	 * @param bool $ignoreOtherCond To disable other conditions when
	 *   we need to transclude a template or update a category's link
	 */
	public function findVariantLink( &$link, &$nt, $ignoreOtherCond = false );

	/**
	 * Returns language specific hash options.
	 *
	 * @return string
	 */
	public function getExtraHashOptions();

	/**
	 * Guess if a text is written in a variant. This should be implemented in subclasses.
	 *
	 * @param string $text The text to be checked
	 * @param string $variant Language code of the variant to be checked for
	 * @return bool True if $text appears to be written in $variant, false if not
	 *
	 * @author Nikola Smolenski <smolensk@eunet.rs>
	 * @since 1.19
	 */
	public function guessVariant( $text, $variant );

	/**
	 * Enclose a string with the "no conversion" tag. This is used by
	 * various functions in the Parser.
	 *
	 * @param string $text Text to be tagged for no conversion
	 * @param bool $noParse Unused
	 * @return string The tagged text
	 */
	public function markNoConversion( $text, $noParse = false );

	/**
	 * Convert the sorting key for category links. This should make different
	 * keys that are variants of each other map to the same key.
	 *
	 * @param string $key
	 *
	 * @return string
	 */
	public function convertCategoryKey( $key );

	/**
	 * Refresh the cache of conversion tables when
	 * MediaWiki:Conversiontable* is updated.
	 *
	 * @param LinkTarget $linkTarget The LinkTarget of the page being updated
	 */
	public function updateConversionTable( LinkTarget $linkTarget );

	/**
	 * Check if this is a language with variants
	 *
	 * @since 1.35
	 *
	 * @return bool
	 */
	public function hasVariants();

	/**
	 * Strict check if the language has the specific variant.
	 *
	 * Compare to LanguageConverter::validateVariant() which does a more
	 * lenient check and attempts to coerce the given code to a valid one.
	 *
	 * @since 1.35
	 * @param string $variant
	 * @return bool
	 */
	public function hasVariant( $variant );

	/**
	 * Perform output conversion on a string, and encode for safe HTML output.
	 *
	 * @since 1.35
	 *
	 * @param string $text Text to be converted
	 * @return string string converted to be safely used in HTML
	 */
	public function convertHtml( $text );
}
