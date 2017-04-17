<?php
/**
 * Internationalisation code.
 * See https://www.mediawiki.org/wiki/Special:MyLanguage/Localisation for more information.
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
 * @ingroup Language
 */

/**
 * @defgroup Language Language
 */

use CLDRPluralRuleParser\Evaluator;

/**
 * Internationalisation code
 * @ingroup Language
 */
class Language {
	/**
	 * @var LanguageConverter
	 */
	public $mConverter;

	public $mVariants, $mCode, $mLoaded = false;
	public $mMagicExtensions = [], $mMagicHookDone = false;
	private $mHtmlCode = null, $mParentLanguage = false;

	public $dateFormatStrings = [];
	public $mExtendedSpecialPageAliases;

	/** @var array|null */
	protected $namespaceNames;
	protected $mNamespaceIds, $namespaceAliases;

	/**
	 * ReplacementArray object caches
	 */
	public $transformData = [];

	/**
	 * @var LocalisationCache
	 */
	static public $dataCache;

	static public $mLangObjCache = [];

	static public $mWeekdayMsgs = [
		'sunday', 'monday', 'tuesday', 'wednesday', 'thursday',
		'friday', 'saturday'
	];

	static public $mWeekdayAbbrevMsgs = [
		'sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'
	];

	static public $mMonthMsgs = [
		'january', 'february', 'march', 'april', 'may_long', 'june',
		'july', 'august', 'september', 'october', 'november',
		'december'
	];
	static public $mMonthGenMsgs = [
		'january-gen', 'february-gen', 'march-gen', 'april-gen', 'may-gen', 'june-gen',
		'july-gen', 'august-gen', 'september-gen', 'october-gen', 'november-gen',
		'december-gen'
	];
	static public $mMonthAbbrevMsgs = [
		'jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug',
		'sep', 'oct', 'nov', 'dec'
	];

	static public $mIranianCalendarMonthMsgs = [
		'iranian-calendar-m1', 'iranian-calendar-m2', 'iranian-calendar-m3',
		'iranian-calendar-m4', 'iranian-calendar-m5', 'iranian-calendar-m6',
		'iranian-calendar-m7', 'iranian-calendar-m8', 'iranian-calendar-m9',
		'iranian-calendar-m10', 'iranian-calendar-m11', 'iranian-calendar-m12'
	];

	static public $mHebrewCalendarMonthMsgs = [
		'hebrew-calendar-m1', 'hebrew-calendar-m2', 'hebrew-calendar-m3',
		'hebrew-calendar-m4', 'hebrew-calendar-m5', 'hebrew-calendar-m6',
		'hebrew-calendar-m7', 'hebrew-calendar-m8', 'hebrew-calendar-m9',
		'hebrew-calendar-m10', 'hebrew-calendar-m11', 'hebrew-calendar-m12',
		'hebrew-calendar-m6a', 'hebrew-calendar-m6b'
	];

	static public $mHebrewCalendarMonthGenMsgs = [
		'hebrew-calendar-m1-gen', 'hebrew-calendar-m2-gen', 'hebrew-calendar-m3-gen',
		'hebrew-calendar-m4-gen', 'hebrew-calendar-m5-gen', 'hebrew-calendar-m6-gen',
		'hebrew-calendar-m7-gen', 'hebrew-calendar-m8-gen', 'hebrew-calendar-m9-gen',
		'hebrew-calendar-m10-gen', 'hebrew-calendar-m11-gen', 'hebrew-calendar-m12-gen',
		'hebrew-calendar-m6a-gen', 'hebrew-calendar-m6b-gen'
	];

	static public $mHijriCalendarMonthMsgs = [
		'hijri-calendar-m1', 'hijri-calendar-m2', 'hijri-calendar-m3',
		'hijri-calendar-m4', 'hijri-calendar-m5', 'hijri-calendar-m6',
		'hijri-calendar-m7', 'hijri-calendar-m8', 'hijri-calendar-m9',
		'hijri-calendar-m10', 'hijri-calendar-m11', 'hijri-calendar-m12'
	];

	/**
	 * @since 1.20
	 * @var array
	 */
	static public $durationIntervals = [
		'millennia' => 31556952000,
		'centuries' => 3155695200,
		'decades' => 315569520,
		'years' => 31556952, // 86400 * ( 365 + ( 24 * 3 + 25 ) / 400 )
		'weeks' => 604800,
		'days' => 86400,
		'hours' => 3600,
		'minutes' => 60,
		'seconds' => 1,
	];

	/**
	 * Cache for language fallbacks.
	 * @see Language::getFallbacksIncludingSiteLanguage
	 * @since 1.21
	 * @var array
	 */
	static private $fallbackLanguageCache = [];

	/**
	 * Cache for grammar rules data
	 * @var MapCacheLRU|null
	 */
	static private $grammarTransformations;

	/**
	 * Cache for language names
	 * @var HashBagOStuff|null
	 */
	static private $languageNameCache;

	/**
	 * Unicode directional formatting characters, for embedBidi()
	 */
	static private $lre = "\xE2\x80\xAA"; // U+202A LEFT-TO-RIGHT EMBEDDING
	static private $rle = "\xE2\x80\xAB"; // U+202B RIGHT-TO-LEFT EMBEDDING
	static private $pdf = "\xE2\x80\xAC"; // U+202C POP DIRECTIONAL FORMATTING

	/**
	 * Directionality test regex for embedBidi(). Matches the first strong directionality codepoint:
	 * - in group 1 if it is LTR
	 * - in group 2 if it is RTL
	 * Does not match if there is no strong directionality codepoint.
	 *
	 * The form is '/(?:([strong ltr codepoint])|([strong rtl codepoint]))/u' .
	 *
	 * Generated by UnicodeJS (see tools/strongDir) from the UCD; see
	 * https://git.wikimedia.org/summary/unicodejs.git .
	 */
	// @codingStandardsIgnoreStart
	// @codeCoverageIgnoreStart
	static private $strongDirRegex = '/(?:([\x{41}-\x{5a}\x{61}-\x{7a}\x{aa}\x{b5}\x{ba}\x{c0}-\x{d6}\x{d8}-\x{f6}\x{f8}-\x{2b8}\x{2bb}-\x{2c1}\x{2d0}\x{2d1}\x{2e0}-\x{2e4}\x{2ee}\x{370}-\x{373}\x{376}\x{377}\x{37a}-\x{37d}\x{37f}\x{386}\x{388}-\x{38a}\x{38c}\x{38e}-\x{3a1}\x{3a3}-\x{3f5}\x{3f7}-\x{482}\x{48a}-\x{52f}\x{531}-\x{556}\x{559}-\x{55f}\x{561}-\x{587}\x{589}\x{903}-\x{939}\x{93b}\x{93d}-\x{940}\x{949}-\x{94c}\x{94e}-\x{950}\x{958}-\x{961}\x{964}-\x{980}\x{982}\x{983}\x{985}-\x{98c}\x{98f}\x{990}\x{993}-\x{9a8}\x{9aa}-\x{9b0}\x{9b2}\x{9b6}-\x{9b9}\x{9bd}-\x{9c0}\x{9c7}\x{9c8}\x{9cb}\x{9cc}\x{9ce}\x{9d7}\x{9dc}\x{9dd}\x{9df}-\x{9e1}\x{9e6}-\x{9f1}\x{9f4}-\x{9fa}\x{a03}\x{a05}-\x{a0a}\x{a0f}\x{a10}\x{a13}-\x{a28}\x{a2a}-\x{a30}\x{a32}\x{a33}\x{a35}\x{a36}\x{a38}\x{a39}\x{a3e}-\x{a40}\x{a59}-\x{a5c}\x{a5e}\x{a66}-\x{a6f}\x{a72}-\x{a74}\x{a83}\x{a85}-\x{a8d}\x{a8f}-\x{a91}\x{a93}-\x{aa8}\x{aaa}-\x{ab0}\x{ab2}\x{ab3}\x{ab5}-\x{ab9}\x{abd}-\x{ac0}\x{ac9}\x{acb}\x{acc}\x{ad0}\x{ae0}\x{ae1}\x{ae6}-\x{af0}\x{af9}\x{b02}\x{b03}\x{b05}-\x{b0c}\x{b0f}\x{b10}\x{b13}-\x{b28}\x{b2a}-\x{b30}\x{b32}\x{b33}\x{b35}-\x{b39}\x{b3d}\x{b3e}\x{b40}\x{b47}\x{b48}\x{b4b}\x{b4c}\x{b57}\x{b5c}\x{b5d}\x{b5f}-\x{b61}\x{b66}-\x{b77}\x{b83}\x{b85}-\x{b8a}\x{b8e}-\x{b90}\x{b92}-\x{b95}\x{b99}\x{b9a}\x{b9c}\x{b9e}\x{b9f}\x{ba3}\x{ba4}\x{ba8}-\x{baa}\x{bae}-\x{bb9}\x{bbe}\x{bbf}\x{bc1}\x{bc2}\x{bc6}-\x{bc8}\x{bca}-\x{bcc}\x{bd0}\x{bd7}\x{be6}-\x{bf2}\x{c01}-\x{c03}\x{c05}-\x{c0c}\x{c0e}-\x{c10}\x{c12}-\x{c28}\x{c2a}-\x{c39}\x{c3d}\x{c41}-\x{c44}\x{c58}-\x{c5a}\x{c60}\x{c61}\x{c66}-\x{c6f}\x{c7f}\x{c82}\x{c83}\x{c85}-\x{c8c}\x{c8e}-\x{c90}\x{c92}-\x{ca8}\x{caa}-\x{cb3}\x{cb5}-\x{cb9}\x{cbd}-\x{cc4}\x{cc6}-\x{cc8}\x{cca}\x{ccb}\x{cd5}\x{cd6}\x{cde}\x{ce0}\x{ce1}\x{ce6}-\x{cef}\x{cf1}\x{cf2}\x{d02}\x{d03}\x{d05}-\x{d0c}\x{d0e}-\x{d10}\x{d12}-\x{d3a}\x{d3d}-\x{d40}\x{d46}-\x{d48}\x{d4a}-\x{d4c}\x{d4e}\x{d57}\x{d5f}-\x{d61}\x{d66}-\x{d75}\x{d79}-\x{d7f}\x{d82}\x{d83}\x{d85}-\x{d96}\x{d9a}-\x{db1}\x{db3}-\x{dbb}\x{dbd}\x{dc0}-\x{dc6}\x{dcf}-\x{dd1}\x{dd8}-\x{ddf}\x{de6}-\x{def}\x{df2}-\x{df4}\x{e01}-\x{e30}\x{e32}\x{e33}\x{e40}-\x{e46}\x{e4f}-\x{e5b}\x{e81}\x{e82}\x{e84}\x{e87}\x{e88}\x{e8a}\x{e8d}\x{e94}-\x{e97}\x{e99}-\x{e9f}\x{ea1}-\x{ea3}\x{ea5}\x{ea7}\x{eaa}\x{eab}\x{ead}-\x{eb0}\x{eb2}\x{eb3}\x{ebd}\x{ec0}-\x{ec4}\x{ec6}\x{ed0}-\x{ed9}\x{edc}-\x{edf}\x{f00}-\x{f17}\x{f1a}-\x{f34}\x{f36}\x{f38}\x{f3e}-\x{f47}\x{f49}-\x{f6c}\x{f7f}\x{f85}\x{f88}-\x{f8c}\x{fbe}-\x{fc5}\x{fc7}-\x{fcc}\x{fce}-\x{fda}\x{1000}-\x{102c}\x{1031}\x{1038}\x{103b}\x{103c}\x{103f}-\x{1057}\x{105a}-\x{105d}\x{1061}-\x{1070}\x{1075}-\x{1081}\x{1083}\x{1084}\x{1087}-\x{108c}\x{108e}-\x{109c}\x{109e}-\x{10c5}\x{10c7}\x{10cd}\x{10d0}-\x{1248}\x{124a}-\x{124d}\x{1250}-\x{1256}\x{1258}\x{125a}-\x{125d}\x{1260}-\x{1288}\x{128a}-\x{128d}\x{1290}-\x{12b0}\x{12b2}-\x{12b5}\x{12b8}-\x{12be}\x{12c0}\x{12c2}-\x{12c5}\x{12c8}-\x{12d6}\x{12d8}-\x{1310}\x{1312}-\x{1315}\x{1318}-\x{135a}\x{1360}-\x{137c}\x{1380}-\x{138f}\x{13a0}-\x{13f5}\x{13f8}-\x{13fd}\x{1401}-\x{167f}\x{1681}-\x{169a}\x{16a0}-\x{16f8}\x{1700}-\x{170c}\x{170e}-\x{1711}\x{1720}-\x{1731}\x{1735}\x{1736}\x{1740}-\x{1751}\x{1760}-\x{176c}\x{176e}-\x{1770}\x{1780}-\x{17b3}\x{17b6}\x{17be}-\x{17c5}\x{17c7}\x{17c8}\x{17d4}-\x{17da}\x{17dc}\x{17e0}-\x{17e9}\x{1810}-\x{1819}\x{1820}-\x{1877}\x{1880}-\x{18a8}\x{18aa}\x{18b0}-\x{18f5}\x{1900}-\x{191e}\x{1923}-\x{1926}\x{1929}-\x{192b}\x{1930}\x{1931}\x{1933}-\x{1938}\x{1946}-\x{196d}\x{1970}-\x{1974}\x{1980}-\x{19ab}\x{19b0}-\x{19c9}\x{19d0}-\x{19da}\x{1a00}-\x{1a16}\x{1a19}\x{1a1a}\x{1a1e}-\x{1a55}\x{1a57}\x{1a61}\x{1a63}\x{1a64}\x{1a6d}-\x{1a72}\x{1a80}-\x{1a89}\x{1a90}-\x{1a99}\x{1aa0}-\x{1aad}\x{1b04}-\x{1b33}\x{1b35}\x{1b3b}\x{1b3d}-\x{1b41}\x{1b43}-\x{1b4b}\x{1b50}-\x{1b6a}\x{1b74}-\x{1b7c}\x{1b82}-\x{1ba1}\x{1ba6}\x{1ba7}\x{1baa}\x{1bae}-\x{1be5}\x{1be7}\x{1bea}-\x{1bec}\x{1bee}\x{1bf2}\x{1bf3}\x{1bfc}-\x{1c2b}\x{1c34}\x{1c35}\x{1c3b}-\x{1c49}\x{1c4d}-\x{1c7f}\x{1cc0}-\x{1cc7}\x{1cd3}\x{1ce1}\x{1ce9}-\x{1cec}\x{1cee}-\x{1cf3}\x{1cf5}\x{1cf6}\x{1d00}-\x{1dbf}\x{1e00}-\x{1f15}\x{1f18}-\x{1f1d}\x{1f20}-\x{1f45}\x{1f48}-\x{1f4d}\x{1f50}-\x{1f57}\x{1f59}\x{1f5b}\x{1f5d}\x{1f5f}-\x{1f7d}\x{1f80}-\x{1fb4}\x{1fb6}-\x{1fbc}\x{1fbe}\x{1fc2}-\x{1fc4}\x{1fc6}-\x{1fcc}\x{1fd0}-\x{1fd3}\x{1fd6}-\x{1fdb}\x{1fe0}-\x{1fec}\x{1ff2}-\x{1ff4}\x{1ff6}-\x{1ffc}\x{200e}\x{2071}\x{207f}\x{2090}-\x{209c}\x{2102}\x{2107}\x{210a}-\x{2113}\x{2115}\x{2119}-\x{211d}\x{2124}\x{2126}\x{2128}\x{212a}-\x{212d}\x{212f}-\x{2139}\x{213c}-\x{213f}\x{2145}-\x{2149}\x{214e}\x{214f}\x{2160}-\x{2188}\x{2336}-\x{237a}\x{2395}\x{249c}-\x{24e9}\x{26ac}\x{2800}-\x{28ff}\x{2c00}-\x{2c2e}\x{2c30}-\x{2c5e}\x{2c60}-\x{2ce4}\x{2ceb}-\x{2cee}\x{2cf2}\x{2cf3}\x{2d00}-\x{2d25}\x{2d27}\x{2d2d}\x{2d30}-\x{2d67}\x{2d6f}\x{2d70}\x{2d80}-\x{2d96}\x{2da0}-\x{2da6}\x{2da8}-\x{2dae}\x{2db0}-\x{2db6}\x{2db8}-\x{2dbe}\x{2dc0}-\x{2dc6}\x{2dc8}-\x{2dce}\x{2dd0}-\x{2dd6}\x{2dd8}-\x{2dde}\x{3005}-\x{3007}\x{3021}-\x{3029}\x{302e}\x{302f}\x{3031}-\x{3035}\x{3038}-\x{303c}\x{3041}-\x{3096}\x{309d}-\x{309f}\x{30a1}-\x{30fa}\x{30fc}-\x{30ff}\x{3105}-\x{312d}\x{3131}-\x{318e}\x{3190}-\x{31ba}\x{31f0}-\x{321c}\x{3220}-\x{324f}\x{3260}-\x{327b}\x{327f}-\x{32b0}\x{32c0}-\x{32cb}\x{32d0}-\x{32fe}\x{3300}-\x{3376}\x{337b}-\x{33dd}\x{33e0}-\x{33fe}\x{3400}-\x{4db5}\x{4e00}-\x{9fd5}\x{a000}-\x{a48c}\x{a4d0}-\x{a60c}\x{a610}-\x{a62b}\x{a640}-\x{a66e}\x{a680}-\x{a69d}\x{a6a0}-\x{a6ef}\x{a6f2}-\x{a6f7}\x{a722}-\x{a787}\x{a789}-\x{a7ad}\x{a7b0}-\x{a7b7}\x{a7f7}-\x{a801}\x{a803}-\x{a805}\x{a807}-\x{a80a}\x{a80c}-\x{a824}\x{a827}\x{a830}-\x{a837}\x{a840}-\x{a873}\x{a880}-\x{a8c3}\x{a8ce}-\x{a8d9}\x{a8f2}-\x{a8fd}\x{a900}-\x{a925}\x{a92e}-\x{a946}\x{a952}\x{a953}\x{a95f}-\x{a97c}\x{a983}-\x{a9b2}\x{a9b4}\x{a9b5}\x{a9ba}\x{a9bb}\x{a9bd}-\x{a9cd}\x{a9cf}-\x{a9d9}\x{a9de}-\x{a9e4}\x{a9e6}-\x{a9fe}\x{aa00}-\x{aa28}\x{aa2f}\x{aa30}\x{aa33}\x{aa34}\x{aa40}-\x{aa42}\x{aa44}-\x{aa4b}\x{aa4d}\x{aa50}-\x{aa59}\x{aa5c}-\x{aa7b}\x{aa7d}-\x{aaaf}\x{aab1}\x{aab5}\x{aab6}\x{aab9}-\x{aabd}\x{aac0}\x{aac2}\x{aadb}-\x{aaeb}\x{aaee}-\x{aaf5}\x{ab01}-\x{ab06}\x{ab09}-\x{ab0e}\x{ab11}-\x{ab16}\x{ab20}-\x{ab26}\x{ab28}-\x{ab2e}\x{ab30}-\x{ab65}\x{ab70}-\x{abe4}\x{abe6}\x{abe7}\x{abe9}-\x{abec}\x{abf0}-\x{abf9}\x{ac00}-\x{d7a3}\x{d7b0}-\x{d7c6}\x{d7cb}-\x{d7fb}\x{e000}-\x{fa6d}\x{fa70}-\x{fad9}\x{fb00}-\x{fb06}\x{fb13}-\x{fb17}\x{ff21}-\x{ff3a}\x{ff41}-\x{ff5a}\x{ff66}-\x{ffbe}\x{ffc2}-\x{ffc7}\x{ffca}-\x{ffcf}\x{ffd2}-\x{ffd7}\x{ffda}-\x{ffdc}\x{10000}-\x{1000b}\x{1000d}-\x{10026}\x{10028}-\x{1003a}\x{1003c}\x{1003d}\x{1003f}-\x{1004d}\x{10050}-\x{1005d}\x{10080}-\x{100fa}\x{10100}\x{10102}\x{10107}-\x{10133}\x{10137}-\x{1013f}\x{101d0}-\x{101fc}\x{10280}-\x{1029c}\x{102a0}-\x{102d0}\x{10300}-\x{10323}\x{10330}-\x{1034a}\x{10350}-\x{10375}\x{10380}-\x{1039d}\x{1039f}-\x{103c3}\x{103c8}-\x{103d5}\x{10400}-\x{1049d}\x{104a0}-\x{104a9}\x{10500}-\x{10527}\x{10530}-\x{10563}\x{1056f}\x{10600}-\x{10736}\x{10740}-\x{10755}\x{10760}-\x{10767}\x{11000}\x{11002}-\x{11037}\x{11047}-\x{1104d}\x{11066}-\x{1106f}\x{11082}-\x{110b2}\x{110b7}\x{110b8}\x{110bb}-\x{110c1}\x{110d0}-\x{110e8}\x{110f0}-\x{110f9}\x{11103}-\x{11126}\x{1112c}\x{11136}-\x{11143}\x{11150}-\x{11172}\x{11174}-\x{11176}\x{11182}-\x{111b5}\x{111bf}-\x{111c9}\x{111cd}\x{111d0}-\x{111df}\x{111e1}-\x{111f4}\x{11200}-\x{11211}\x{11213}-\x{1122e}\x{11232}\x{11233}\x{11235}\x{11238}-\x{1123d}\x{11280}-\x{11286}\x{11288}\x{1128a}-\x{1128d}\x{1128f}-\x{1129d}\x{1129f}-\x{112a9}\x{112b0}-\x{112de}\x{112e0}-\x{112e2}\x{112f0}-\x{112f9}\x{11302}\x{11303}\x{11305}-\x{1130c}\x{1130f}\x{11310}\x{11313}-\x{11328}\x{1132a}-\x{11330}\x{11332}\x{11333}\x{11335}-\x{11339}\x{1133d}-\x{1133f}\x{11341}-\x{11344}\x{11347}\x{11348}\x{1134b}-\x{1134d}\x{11350}\x{11357}\x{1135d}-\x{11363}\x{11480}-\x{114b2}\x{114b9}\x{114bb}-\x{114be}\x{114c1}\x{114c4}-\x{114c7}\x{114d0}-\x{114d9}\x{11580}-\x{115b1}\x{115b8}-\x{115bb}\x{115be}\x{115c1}-\x{115db}\x{11600}-\x{11632}\x{1163b}\x{1163c}\x{1163e}\x{11641}-\x{11644}\x{11650}-\x{11659}\x{11680}-\x{116aa}\x{116ac}\x{116ae}\x{116af}\x{116b6}\x{116c0}-\x{116c9}\x{11700}-\x{11719}\x{11720}\x{11721}\x{11726}\x{11730}-\x{1173f}\x{118a0}-\x{118f2}\x{118ff}\x{11ac0}-\x{11af8}\x{12000}-\x{12399}\x{12400}-\x{1246e}\x{12470}-\x{12474}\x{12480}-\x{12543}\x{13000}-\x{1342e}\x{14400}-\x{14646}\x{16800}-\x{16a38}\x{16a40}-\x{16a5e}\x{16a60}-\x{16a69}\x{16a6e}\x{16a6f}\x{16ad0}-\x{16aed}\x{16af5}\x{16b00}-\x{16b2f}\x{16b37}-\x{16b45}\x{16b50}-\x{16b59}\x{16b5b}-\x{16b61}\x{16b63}-\x{16b77}\x{16b7d}-\x{16b8f}\x{16f00}-\x{16f44}\x{16f50}-\x{16f7e}\x{16f93}-\x{16f9f}\x{1b000}\x{1b001}\x{1bc00}-\x{1bc6a}\x{1bc70}-\x{1bc7c}\x{1bc80}-\x{1bc88}\x{1bc90}-\x{1bc99}\x{1bc9c}\x{1bc9f}\x{1d000}-\x{1d0f5}\x{1d100}-\x{1d126}\x{1d129}-\x{1d166}\x{1d16a}-\x{1d172}\x{1d183}\x{1d184}\x{1d18c}-\x{1d1a9}\x{1d1ae}-\x{1d1e8}\x{1d360}-\x{1d371}\x{1d400}-\x{1d454}\x{1d456}-\x{1d49c}\x{1d49e}\x{1d49f}\x{1d4a2}\x{1d4a5}\x{1d4a6}\x{1d4a9}-\x{1d4ac}\x{1d4ae}-\x{1d4b9}\x{1d4bb}\x{1d4bd}-\x{1d4c3}\x{1d4c5}-\x{1d505}\x{1d507}-\x{1d50a}\x{1d50d}-\x{1d514}\x{1d516}-\x{1d51c}\x{1d51e}-\x{1d539}\x{1d53b}-\x{1d53e}\x{1d540}-\x{1d544}\x{1d546}\x{1d54a}-\x{1d550}\x{1d552}-\x{1d6a5}\x{1d6a8}-\x{1d6da}\x{1d6dc}-\x{1d714}\x{1d716}-\x{1d74e}\x{1d750}-\x{1d788}\x{1d78a}-\x{1d7c2}\x{1d7c4}-\x{1d7cb}\x{1d800}-\x{1d9ff}\x{1da37}-\x{1da3a}\x{1da6d}-\x{1da74}\x{1da76}-\x{1da83}\x{1da85}-\x{1da8b}\x{1f110}-\x{1f12e}\x{1f130}-\x{1f169}\x{1f170}-\x{1f19a}\x{1f1e6}-\x{1f202}\x{1f210}-\x{1f23a}\x{1f240}-\x{1f248}\x{1f250}\x{1f251}\x{20000}-\x{2a6d6}\x{2a700}-\x{2b734}\x{2b740}-\x{2b81d}\x{2b820}-\x{2cea1}\x{2f800}-\x{2fa1d}\x{f0000}-\x{ffffd}\x{100000}-\x{10fffd}])|([\x{590}\x{5be}\x{5c0}\x{5c3}\x{5c6}\x{5c8}-\x{5ff}\x{7c0}-\x{7ea}\x{7f4}\x{7f5}\x{7fa}-\x{815}\x{81a}\x{824}\x{828}\x{82e}-\x{858}\x{85c}-\x{89f}\x{200f}\x{fb1d}\x{fb1f}-\x{fb28}\x{fb2a}-\x{fb4f}\x{10800}-\x{1091e}\x{10920}-\x{10a00}\x{10a04}\x{10a07}-\x{10a0b}\x{10a10}-\x{10a37}\x{10a3b}-\x{10a3e}\x{10a40}-\x{10ae4}\x{10ae7}-\x{10b38}\x{10b40}-\x{10e5f}\x{10e7f}-\x{10fff}\x{1e800}-\x{1e8cf}\x{1e8d7}-\x{1edff}\x{1ef00}-\x{1efff}\x{608}\x{60b}\x{60d}\x{61b}-\x{64a}\x{66d}-\x{66f}\x{671}-\x{6d5}\x{6e5}\x{6e6}\x{6ee}\x{6ef}\x{6fa}-\x{710}\x{712}-\x{72f}\x{74b}-\x{7a5}\x{7b1}-\x{7bf}\x{8a0}-\x{8e2}\x{fb50}-\x{fd3d}\x{fd40}-\x{fdcf}\x{fdf0}-\x{fdfc}\x{fdfe}\x{fdff}\x{fe70}-\x{fefe}\x{1ee00}-\x{1eeef}\x{1eef2}-\x{1eeff}]))/u';
	// @codeCoverageIgnoreEnd
	// @codingStandardsIgnoreEnd

	/**
	 * Get a cached or new language object for a given language code
	 * @param string $code
	 * @return Language
	 */
	static function factory( $code ) {
		global $wgDummyLanguageCodes, $wgLangObjCacheSize;

		if ( isset( $wgDummyLanguageCodes[$code] ) ) {
			$code = $wgDummyLanguageCodes[$code];
		}

		// get the language object to process
		$langObj = isset( self::$mLangObjCache[$code] )
			? self::$mLangObjCache[$code]
			: self::newFromCode( $code );

		// merge the language object in to get it up front in the cache
		self::$mLangObjCache = array_merge( [ $code => $langObj ], self::$mLangObjCache );
		// get rid of the oldest ones in case we have an overflow
		self::$mLangObjCache = array_slice( self::$mLangObjCache, 0, $wgLangObjCacheSize, true );

		return $langObj;
	}

	/**
	 * Create a language object for a given language code
	 * @param string $code
	 * @throws MWException
	 * @return Language
	 */
	protected static function newFromCode( $code ) {
		if ( !Language::isValidCode( $code ) ) {
			throw new MWException( "Invalid language code \"$code\"" );
		}

		if ( !Language::isValidBuiltInCode( $code ) ) {
			// It's not possible to customise this code with class files, so
			// just return a Language object. This is to support uselang= hacks.
			$lang = new Language;
			$lang->setCode( $code );
			return $lang;
		}

		// Check if there is a language class for the code
		$class = self::classFromCode( $code );
		if ( class_exists( $class ) ) {
			$lang = new $class;
			return $lang;
		}

		// Keep trying the fallback list until we find an existing class
		$fallbacks = Language::getFallbacksFor( $code );
		foreach ( $fallbacks as $fallbackCode ) {
			if ( !Language::isValidBuiltInCode( $fallbackCode ) ) {
				throw new MWException( "Invalid fallback '$fallbackCode' in fallback sequence for '$code'" );
			}

			$class = self::classFromCode( $fallbackCode );
			if ( class_exists( $class ) ) {
				$lang = new $class;
				$lang->setCode( $code );
				return $lang;
			}
		}

		throw new MWException( "Invalid fallback sequence for language '$code'" );
	}

	/**
	 * Checks whether any localisation is available for that language tag
	 * in MediaWiki (MessagesXx.php exists).
	 *
	 * @param string $code Language tag (in lower case)
	 * @return bool Whether language is supported
	 * @since 1.21
	 */
	public static function isSupportedLanguage( $code ) {
		if ( !self::isValidBuiltInCode( $code ) ) {
			return false;
		}

		if ( $code === 'qqq' ) {
			return false;
		}

		return is_readable( self::getMessagesFileName( $code ) ) ||
			is_readable( self::getJsonMessagesFileName( $code ) );
	}

	/**
	 * Returns true if a language code string is a well-formed language tag
	 * according to RFC 5646.
	 * This function only checks well-formedness; it doesn't check that
	 * language, script or variant codes actually exist in the repositories.
	 *
	 * Based on regexes by Mark Davis of the Unicode Consortium:
	 * http://unicode.org/repos/cldr/trunk/tools/java/org/unicode/cldr/util/data/langtagRegex.txt
	 *
	 * @param string $code
	 * @param bool $lenient Whether to allow '_' as separator. The default is only '-'.
	 *
	 * @return bool
	 * @since 1.21
	 */
	public static function isWellFormedLanguageTag( $code, $lenient = false ) {
		$alpha = '[a-z]';
		$digit = '[0-9]';
		$alphanum = '[a-z0-9]';
		$x = 'x'; # private use singleton
		$singleton = '[a-wy-z]'; # other singleton
		$s = $lenient ? '[-_]' : '-';

		$language = "$alpha{2,8}|$alpha{2,3}$s$alpha{3}";
		$script = "$alpha{4}"; # ISO 15924
		$region = "(?:$alpha{2}|$digit{3})"; # ISO 3166-1 alpha-2 or UN M.49
		$variant = "(?:$alphanum{5,8}|$digit$alphanum{3})";
		$extension = "$singleton(?:$s$alphanum{2,8})+";
		$privateUse = "$x(?:$s$alphanum{1,8})+";

		# Define certain grandfathered codes, since otherwise the regex is pretty useless.
		# Since these are limited, this is safe even later changes to the registry --
		# the only oddity is that it might change the type of the tag, and thus
		# the results from the capturing groups.
		# https://www.iana.org/assignments/language-subtag-registry

		$grandfathered = "en{$s}GB{$s}oed"
			. "|i{$s}(?:ami|bnn|default|enochian|hak|klingon|lux|mingo|navajo|pwn|tao|tay|tsu)"
			. "|no{$s}(?:bok|nyn)"
			. "|sgn{$s}(?:BE{$s}(?:fr|nl)|CH{$s}de)"
			. "|zh{$s}min{$s}nan";

		$variantList = "$variant(?:$s$variant)*";
		$extensionList = "$extension(?:$s$extension)*";

		$langtag = "(?:($language)"
			. "(?:$s$script)?"
			. "(?:$s$region)?"
			. "(?:$s$variantList)?"
			. "(?:$s$extensionList)?"
			. "(?:$s$privateUse)?)";

		# The final breakdown, with capturing groups for each of these components
		# The variants, extensions, grandfathered, and private-use may have interior '-'

		$root = "^(?:$langtag|$privateUse|$grandfathered)$";

		return (bool)preg_match( "/$root/", strtolower( $code ) );
	}

	/**
	 * Returns true if a language code string is of a valid form, whether or
	 * not it exists. This includes codes which are used solely for
	 * customisation via the MediaWiki namespace.
	 *
	 * @param string $code
	 *
	 * @return bool
	 */
	public static function isValidCode( $code ) {
		static $cache = [];
		if ( !isset( $cache[$code] ) ) {
			// People think language codes are html safe, so enforce it.
			// Ideally we should only allow a-zA-Z0-9-
			// but, .+ and other chars are often used for {{int:}} hacks
			// see bugs T39564, T39587, T38938
			$cache[$code] =
				// Protect against path traversal
				strcspn( $code, ":/\\\000&<>'\"" ) === strlen( $code )
				&& !preg_match( MediaWikiTitleCodec::getTitleInvalidRegex(), $code );
		}
		return $cache[$code];
	}

	/**
	 * Returns true if a language code is of a valid form for the purposes of
	 * internal customisation of MediaWiki, via Messages*.php or *.json.
	 *
	 * @param string $code
	 *
	 * @throws MWException
	 * @since 1.18
	 * @return bool
	 */
	public static function isValidBuiltInCode( $code ) {

		if ( !is_string( $code ) ) {
			if ( is_object( $code ) ) {
				$addmsg = " of class " . get_class( $code );
			} else {
				$addmsg = '';
			}
			$type = gettype( $code );
			throw new MWException( __METHOD__ . " must be passed a string, $type given$addmsg" );
		}

		return (bool)preg_match( '/^[a-z0-9-]{2,}$/', $code );
	}

	/**
	 * Returns true if a language code is an IETF tag known to MediaWiki.
	 *
	 * @param string $tag
	 *
	 * @since 1.21
	 * @return bool
	 */
	public static function isKnownLanguageTag( $tag ) {
		// Quick escape for invalid input to avoid exceptions down the line
		// when code tries to process tags which are not valid at all.
		if ( !self::isValidBuiltInCode( $tag ) ) {
			return false;
		}

		if ( isset( MediaWiki\Languages\Data\Names::$names[$tag] )
			|| self::fetchLanguageName( $tag, $tag ) !== ''
		) {
			return true;
		}

		return false;
	}

	/**
	 * Get the LocalisationCache instance
	 *
	 * @return LocalisationCache
	 */
	public static function getLocalisationCache() {
		if ( is_null( self::$dataCache ) ) {
			global $wgLocalisationCacheConf;
			$class = $wgLocalisationCacheConf['class'];
			self::$dataCache = new $class( $wgLocalisationCacheConf );
		}
		return self::$dataCache;
	}

	function __construct() {
		$this->mConverter = new FakeConverter( $this );
		// Set the code to the name of the descendant
		if ( static::class === 'Language' ) {
			$this->mCode = 'en';
		} else {
			$this->mCode = str_replace( '_', '-', strtolower( substr( static::class, 8 ) ) );
		}
		self::getLocalisationCache();
	}

	/**
	 * Reduce memory usage
	 */
	function __destruct() {
		foreach ( $this as $name => $value ) {
			unset( $this->$name );
		}
	}

	/**
	 * Hook which will be called if this is the content language.
	 * Descendants can use this to register hook functions or modify globals
	 */
	function initContLang() {
	}

	/**
	 * @return array
	 * @since 1.19
	 */
	public function getFallbackLanguages() {
		return self::getFallbacksFor( $this->mCode );
	}

	/**
	 * Exports $wgBookstoreListEn
	 * @return array
	 */
	public function getBookstoreList() {
		return self::$dataCache->getItem( $this->mCode, 'bookstoreList' );
	}

	/**
	 * Returns an array of localised namespaces indexed by their numbers. If the namespace is not
	 * available in localised form, it will be included in English.
	 *
	 * @return array
	 */
	public function getNamespaces() {
		if ( is_null( $this->namespaceNames ) ) {
			global $wgMetaNamespace, $wgMetaNamespaceTalk, $wgExtraNamespaces;

			$validNamespaces = MWNamespace::getCanonicalNamespaces();

			$this->namespaceNames = $wgExtraNamespaces +
				self::$dataCache->getItem( $this->mCode, 'namespaceNames' );
			$this->namespaceNames += $validNamespaces;

			$this->namespaceNames[NS_PROJECT] = $wgMetaNamespace;
			if ( $wgMetaNamespaceTalk ) {
				$this->namespaceNames[NS_PROJECT_TALK] = $wgMetaNamespaceTalk;
			} else {
				$talk = $this->namespaceNames[NS_PROJECT_TALK];
				$this->namespaceNames[NS_PROJECT_TALK] =
					$this->fixVariableInNamespace( $talk );
			}

			# Sometimes a language will be localised but not actually exist on this wiki.
			foreach ( $this->namespaceNames as $key => $text ) {
				if ( !isset( $validNamespaces[$key] ) ) {
					unset( $this->namespaceNames[$key] );
				}
			}

			# The above mixing may leave namespaces out of canonical order.
			# Re-order by namespace ID number...
			ksort( $this->namespaceNames );

			Hooks::run( 'LanguageGetNamespaces', [ &$this->namespaceNames ] );
		}

		return $this->namespaceNames;
	}

	/**
	 * Arbitrarily set all of the namespace names at once. Mainly used for testing
	 * @param array $namespaces Array of namespaces (id => name)
	 */
	public function setNamespaces( array $namespaces ) {
		$this->namespaceNames = $namespaces;
		$this->mNamespaceIds = null;
	}

	/**
	 * Resets all of the namespace caches. Mainly used for testing
	 */
	public function resetNamespaces() {
		$this->namespaceNames = null;
		$this->mNamespaceIds = null;
		$this->namespaceAliases = null;
	}

	/**
	 * A convenience function that returns getNamespaces() with spaces instead of underscores
	 * in values. Useful for producing output to be displayed e.g. in `<select>` forms.
	 *
	 * @return array
	 */
	public function getFormattedNamespaces() {
		$ns = $this->getNamespaces();
		foreach ( $ns as $k => $v ) {
			$ns[$k] = strtr( $v, '_', ' ' );
		}
		return $ns;
	}

	/**
	 * Get a namespace value by key
	 *
	 * <code>
	 * $mw_ns = $wgContLang->getNsText( NS_MEDIAWIKI );
	 * echo $mw_ns; // prints 'MediaWiki'
	 * </code>
	 *
	 * @param int $index The array key of the namespace to return
	 * @return string|bool String if the namespace value exists, otherwise false
	 */
	public function getNsText( $index ) {
		$ns = $this->getNamespaces();
		return isset( $ns[$index] ) ? $ns[$index] : false;
	}

	/**
	 * A convenience function that returns the same thing as
	 * getNsText() except with '_' changed to ' ', useful for
	 * producing output.
	 *
	 * <code>
	 * $mw_ns = $wgContLang->getFormattedNsText( NS_MEDIAWIKI_TALK );
	 * echo $mw_ns; // prints 'MediaWiki talk'
	 * </code>
	 *
	 * @param int $index The array key of the namespace to return
	 * @return string Namespace name without underscores (empty string if namespace does not exist)
	 */
	public function getFormattedNsText( $index ) {
		$ns = $this->getNsText( $index );
		return strtr( $ns, '_', ' ' );
	}

	/**
	 * Returns gender-dependent namespace alias if available.
	 * See https://www.mediawiki.org/wiki/Manual:$wgExtraGenderNamespaces
	 * @param int $index Namespace index
	 * @param string $gender Gender key (male, female... )
	 * @return string
	 * @since 1.18
	 */
	public function getGenderNsText( $index, $gender ) {
		global $wgExtraGenderNamespaces;

		$ns = $wgExtraGenderNamespaces +
			(array)self::$dataCache->getItem( $this->mCode, 'namespaceGenderAliases' );

		return isset( $ns[$index][$gender] ) ? $ns[$index][$gender] : $this->getNsText( $index );
	}

	/**
	 * Whether this language uses gender-dependent namespace aliases.
	 * See https://www.mediawiki.org/wiki/Manual:$wgExtraGenderNamespaces
	 * @return bool
	 * @since 1.18
	 */
	public function needsGenderDistinction() {
		global $wgExtraGenderNamespaces, $wgExtraNamespaces;
		if ( count( $wgExtraGenderNamespaces ) > 0 ) {
			// $wgExtraGenderNamespaces overrides everything
			return true;
		} elseif ( isset( $wgExtraNamespaces[NS_USER] ) && isset( $wgExtraNamespaces[NS_USER_TALK] ) ) {
			/// @todo There may be other gender namespace than NS_USER & NS_USER_TALK in the future
			// $wgExtraNamespaces overrides any gender aliases specified in i18n files
			return false;
		} else {
			// Check what is in i18n files
			$aliases = self::$dataCache->getItem( $this->mCode, 'namespaceGenderAliases' );
			return count( $aliases ) > 0;
		}
	}

	/**
	 * Get a namespace key by value, case insensitive.
	 * Only matches namespace names for the current language, not the
	 * canonical ones defined in Namespace.php.
	 *
	 * @param string $text
	 * @return int|bool An integer if $text is a valid value otherwise false
	 */
	function getLocalNsIndex( $text ) {
		$lctext = $this->lc( $text );
		$ids = $this->getNamespaceIds();
		return isset( $ids[$lctext] ) ? $ids[$lctext] : false;
	}

	/**
	 * @return array
	 */
	public function getNamespaceAliases() {
		if ( is_null( $this->namespaceAliases ) ) {
			$aliases = self::$dataCache->getItem( $this->mCode, 'namespaceAliases' );
			if ( !$aliases ) {
				$aliases = [];
			} else {
				foreach ( $aliases as $name => $index ) {
					if ( $index === NS_PROJECT_TALK ) {
						unset( $aliases[$name] );
						$name = $this->fixVariableInNamespace( $name );
						$aliases[$name] = $index;
					}
				}
			}

			global $wgExtraGenderNamespaces;
			$genders = $wgExtraGenderNamespaces +
				(array)self::$dataCache->getItem( $this->mCode, 'namespaceGenderAliases' );
			foreach ( $genders as $index => $forms ) {
				foreach ( $forms as $alias ) {
					$aliases[$alias] = $index;
				}
			}

			# Also add converted namespace names as aliases, to avoid confusion.
			$convertedNames = [];
			foreach ( $this->getVariants() as $variant ) {
				if ( $variant === $this->mCode ) {
					continue;
				}
				foreach ( $this->getNamespaces() as $ns => $_ ) {
					$convertedNames[$this->getConverter()->convertNamespace( $ns, $variant )] = $ns;
				}
			}

			$this->namespaceAliases = $aliases + $convertedNames;
		}

		return $this->namespaceAliases;
	}

	/**
	 * @return array
	 */
	public function getNamespaceIds() {
		if ( is_null( $this->mNamespaceIds ) ) {
			global $wgNamespaceAliases;
			# Put namespace names and aliases into a hashtable.
			# If this is too slow, then we should arrange it so that it is done
			# before caching. The catch is that at pre-cache time, the above
			# class-specific fixup hasn't been done.
			$this->mNamespaceIds = [];
			foreach ( $this->getNamespaces() as $index => $name ) {
				$this->mNamespaceIds[$this->lc( $name )] = $index;
			}
			foreach ( $this->getNamespaceAliases() as $name => $index ) {
				$this->mNamespaceIds[$this->lc( $name )] = $index;
			}
			if ( $wgNamespaceAliases ) {
				foreach ( $wgNamespaceAliases as $name => $index ) {
					$this->mNamespaceIds[$this->lc( $name )] = $index;
				}
			}
		}
		return $this->mNamespaceIds;
	}

	/**
	 * Get a namespace key by value, case insensitive.  Canonical namespace
	 * names override custom ones defined for the current language.
	 *
	 * @param string $text
	 * @return int|bool An integer if $text is a valid value otherwise false
	 */
	public function getNsIndex( $text ) {
		$lctext = $this->lc( $text );
		$ns = MWNamespace::getCanonicalIndex( $lctext );
		if ( $ns !== null ) {
			return $ns;
		}
		$ids = $this->getNamespaceIds();
		return isset( $ids[$lctext] ) ? $ids[$lctext] : false;
	}

	/**
	 * short names for language variants used for language conversion links.
	 *
	 * @param string $code
	 * @param bool $usemsg Use the "variantname-xyz" message if it exists
	 * @return string
	 */
	public function getVariantname( $code, $usemsg = true ) {
		$msg = "variantname-$code";
		if ( $usemsg && wfMessage( $msg )->exists() ) {
			return $this->getMessageFromDB( $msg );
		}
		$name = self::fetchLanguageName( $code );
		if ( $name ) {
			return $name; # if it's defined as a language name, show that
		} else {
			# otherwise, output the language code
			return $code;
		}
	}

	/**
	 * @return array
	 */
	public function getDatePreferences() {
		return self::$dataCache->getItem( $this->mCode, 'datePreferences' );
	}

	/**
	 * @return array
	 */
	function getDateFormats() {
		return self::$dataCache->getItem( $this->mCode, 'dateFormats' );
	}

	/**
	 * @return array|string
	 */
	public function getDefaultDateFormat() {
		$df = self::$dataCache->getItem( $this->mCode, 'defaultDateFormat' );
		if ( $df === 'dmy or mdy' ) {
			global $wgAmericanDates;
			return $wgAmericanDates ? 'mdy' : 'dmy';
		} else {
			return $df;
		}
	}

	/**
	 * @return array
	 */
	public function getDatePreferenceMigrationMap() {
		return self::$dataCache->getItem( $this->mCode, 'datePreferenceMigrationMap' );
	}

	/**
	 * @param string $image
	 * @return array|null
	 */
	function getImageFile( $image ) {
		return self::$dataCache->getSubitem( $this->mCode, 'imageFiles', $image );
	}

	/**
	 * @return array
	 * @since 1.24
	 */
	public function getImageFiles() {
		return self::$dataCache->getItem( $this->mCode, 'imageFiles' );
	}

	/**
	 * @return array
	 */
	public function getExtraUserToggles() {
		return (array)self::$dataCache->getItem( $this->mCode, 'extraUserToggles' );
	}

	/**
	 * @param string $tog
	 * @return string
	 */
	function getUserToggle( $tog ) {
		return $this->getMessageFromDB( "tog-$tog" );
	}

	/**
	 * Get an array of language names, indexed by code.
	 * @param null|string $inLanguage Code of language in which to return the names
	 *		Use null for autonyms (native names)
	 * @param string $include One of:
	 *		'all' all available languages
	 *		'mw' only if the language is defined in MediaWiki or wgExtraLanguageNames (default)
	 *		'mwfile' only if the language is in 'mw' *and* has a message file
	 * @return array Language code => language name
	 * @since 1.20
	 */
	public static function fetchLanguageNames( $inLanguage = null, $include = 'mw' ) {
		$cacheKey = $inLanguage === null ? 'null' : $inLanguage;
		$cacheKey .= ":$include";
		if ( self::$languageNameCache === null ) {
			self::$languageNameCache = new HashBagOStuff( [ 'maxKeys' => 20 ] );
		}

		$ret = self::$languageNameCache->get( $cacheKey );
		if ( !$ret ) {
			$ret = self::fetchLanguageNamesUncached( $inLanguage, $include );
			self::$languageNameCache->set( $cacheKey, $ret );
		}
		return $ret;
	}

	/**
	 * Uncached helper for fetchLanguageNames
	 * @param null|string $inLanguage Code of language in which to return the names
	 *		Use null for autonyms (native names)
	 * @param string $include One of:
	 *		'all' all available languages
	 *		'mw' only if the language is defined in MediaWiki or wgExtraLanguageNames (default)
	 *		'mwfile' only if the language is in 'mw' *and* has a message file
	 * @return array Language code => language name
	 */
	private static function fetchLanguageNamesUncached( $inLanguage = null, $include = 'mw' ) {
		global $wgExtraLanguageNames;

		// If passed an invalid language code to use, fallback to en
		if ( $inLanguage !== null && !Language::isValidCode( $inLanguage ) ) {
			$inLanguage = 'en';
		}

		$names = [];

		if ( $inLanguage ) {
			# TODO: also include when $inLanguage is null, when this code is more efficient
			Hooks::run( 'LanguageGetTranslatedLanguageNames', [ &$names, $inLanguage ] );
		}

		$mwNames = $wgExtraLanguageNames + MediaWiki\Languages\Data\Names::$names;
		foreach ( $mwNames as $mwCode => $mwName ) {
			# - Prefer own MediaWiki native name when not using the hook
			# - For other names just add if not added through the hook
			if ( $mwCode === $inLanguage || !isset( $names[$mwCode] ) ) {
				$names[$mwCode] = $mwName;
			}
		}

		if ( $include === 'all' ) {
			ksort( $names );
			return $names;
		}

		$returnMw = [];
		$coreCodes = array_keys( $mwNames );
		foreach ( $coreCodes as $coreCode ) {
			$returnMw[$coreCode] = $names[$coreCode];
		}

		if ( $include === 'mwfile' ) {
			$namesMwFile = [];
			# We do this using a foreach over the codes instead of a directory
			# loop so that messages files in extensions will work correctly.
			foreach ( $returnMw as $code => $value ) {
				if ( is_readable( self::getMessagesFileName( $code ) )
					|| is_readable( self::getJsonMessagesFileName( $code ) )
				) {
					$namesMwFile[$code] = $names[$code];
				}
			}

			ksort( $namesMwFile );
			return $namesMwFile;
		}

		ksort( $returnMw );
		# 'mw' option; default if it's not one of the other two options (all/mwfile)
		return $returnMw;
	}

	/**
	 * @param string $code The code of the language for which to get the name
	 * @param null|string $inLanguage Code of language in which to return the name (null for autonyms)
	 * @param string $include 'all', 'mw' or 'mwfile'; see fetchLanguageNames()
	 * @return string Language name or empty
	 * @since 1.20
	 */
	public static function fetchLanguageName( $code, $inLanguage = null, $include = 'all' ) {
		$code = strtolower( $code );
		$array = self::fetchLanguageNames( $inLanguage, $include );
		return !array_key_exists( $code, $array ) ? '' : $array[$code];
	}

	/**
	 * Get a message from the MediaWiki namespace.
	 *
	 * @param string $msg Message name
	 * @return string
	 */
	public function getMessageFromDB( $msg ) {
		return $this->msg( $msg )->text();
	}

	/**
	 * Get message object in this language. Only for use inside this class.
	 *
	 * @param string $msg Message name
	 * @return Message
	 */
	protected function msg( $msg ) {
		return wfMessage( $msg )->inLanguage( $this );
	}

	/**
	 * @param string $key
	 * @return string
	 */
	public function getMonthName( $key ) {
		return $this->getMessageFromDB( self::$mMonthMsgs[$key - 1] );
	}

	/**
	 * @return array
	 */
	public function getMonthNamesArray() {
		$monthNames = [ '' ];
		for ( $i = 1; $i < 13; $i++ ) {
			$monthNames[] = $this->getMonthName( $i );
		}
		return $monthNames;
	}

	/**
	 * @param string $key
	 * @return string
	 */
	public function getMonthNameGen( $key ) {
		return $this->getMessageFromDB( self::$mMonthGenMsgs[$key - 1] );
	}

	/**
	 * @param string $key
	 * @return string
	 */
	public function getMonthAbbreviation( $key ) {
		return $this->getMessageFromDB( self::$mMonthAbbrevMsgs[$key - 1] );
	}

	/**
	 * @return array
	 */
	public function getMonthAbbreviationsArray() {
		$monthNames = [ '' ];
		for ( $i = 1; $i < 13; $i++ ) {
			$monthNames[] = $this->getMonthAbbreviation( $i );
		}
		return $monthNames;
	}

	/**
	 * @param string $key
	 * @return string
	 */
	public function getWeekdayName( $key ) {
		return $this->getMessageFromDB( self::$mWeekdayMsgs[$key - 1] );
	}

	/**
	 * @param string $key
	 * @return string
	 */
	function getWeekdayAbbreviation( $key ) {
		return $this->getMessageFromDB( self::$mWeekdayAbbrevMsgs[$key - 1] );
	}

	/**
	 * @param string $key
	 * @return string
	 */
	function getIranianCalendarMonthName( $key ) {
		return $this->getMessageFromDB( self::$mIranianCalendarMonthMsgs[$key - 1] );
	}

	/**
	 * @param string $key
	 * @return string
	 */
	function getHebrewCalendarMonthName( $key ) {
		return $this->getMessageFromDB( self::$mHebrewCalendarMonthMsgs[$key - 1] );
	}

	/**
	 * @param string $key
	 * @return string
	 */
	function getHebrewCalendarMonthNameGen( $key ) {
		return $this->getMessageFromDB( self::$mHebrewCalendarMonthGenMsgs[$key - 1] );
	}

	/**
	 * @param string $key
	 * @return string
	 */
	function getHijriCalendarMonthName( $key ) {
		return $this->getMessageFromDB( self::$mHijriCalendarMonthMsgs[$key - 1] );
	}

	/**
	 * Pass through result from $dateTimeObj->format()
	 * @param DateTime|bool|null &$dateTimeObj
	 * @param string $ts
	 * @param DateTimeZone|bool|null $zone
	 * @param string $code
	 * @return string
	 */
	private static function dateTimeObjFormat( &$dateTimeObj, $ts, $zone, $code ) {
		if ( !$dateTimeObj ) {
			$dateTimeObj = DateTime::createFromFormat(
				'YmdHis', $ts, $zone ?: new DateTimeZone( 'UTC' )
			);
		}
		return $dateTimeObj->format( $code );
	}

	/**
	 * This is a workalike of PHP's date() function, but with better
	 * internationalisation, a reduced set of format characters, and a better
	 * escaping format.
	 *
	 * Supported format characters are dDjlNwzWFmMntLoYyaAgGhHiscrUeIOPTZ. See
	 * the PHP manual for definitions. There are a number of extensions, which
	 * start with "x":
	 *
	 *    xn   Do not translate digits of the next numeric format character
	 *    xN   Toggle raw digit (xn) flag, stays set until explicitly unset
	 *    xr   Use roman numerals for the next numeric format character
	 *    xh   Use hebrew numerals for the next numeric format character
	 *    xx   Literal x
	 *    xg   Genitive month name
	 *
	 *    xij  j (day number) in Iranian calendar
	 *    xiF  F (month name) in Iranian calendar
	 *    xin  n (month number) in Iranian calendar
	 *    xiy  y (two digit year) in Iranian calendar
	 *    xiY  Y (full year) in Iranian calendar
	 *    xit  t (days in month) in Iranian calendar
	 *    xiz  z (day of the year) in Iranian calendar
	 *
	 *    xjj  j (day number) in Hebrew calendar
	 *    xjF  F (month name) in Hebrew calendar
	 *    xjt  t (days in month) in Hebrew calendar
	 *    xjx  xg (genitive month name) in Hebrew calendar
	 *    xjn  n (month number) in Hebrew calendar
	 *    xjY  Y (full year) in Hebrew calendar
	 *
	 *    xmj  j (day number) in Hijri calendar
	 *    xmF  F (month name) in Hijri calendar
	 *    xmn  n (month number) in Hijri calendar
	 *    xmY  Y (full year) in Hijri calendar
	 *
	 *    xkY  Y (full year) in Thai solar calendar. Months and days are
	 *                       identical to the Gregorian calendar
	 *    xoY  Y (full year) in Minguo calendar or Juche year.
	 *                       Months and days are identical to the
	 *                       Gregorian calendar
	 *    xtY  Y (full year) in Japanese nengo. Months and days are
	 *                       identical to the Gregorian calendar
	 *
	 * Characters enclosed in double quotes will be considered literal (with
	 * the quotes themselves removed). Unmatched quotes will be considered
	 * literal quotes. Example:
	 *
	 * "The month is" F       => The month is January
	 * i's"                   => 20'11"
	 *
	 * Backslash escaping is also supported.
	 *
	 * Input timestamp is assumed to be pre-normalized to the desired local
	 * time zone, if any. Note that the format characters crUeIOPTZ will assume
	 * $ts is UTC if $zone is not given.
	 *
	 * @param string $format
	 * @param string $ts 14-character timestamp
	 *      YYYYMMDDHHMMSS
	 *      01234567890123
	 * @param DateTimeZone $zone Timezone of $ts
	 * @param[out] int $ttl The amount of time (in seconds) the output may be cached for.
	 * Only makes sense if $ts is the current time.
	 * @todo handling of "o" format character for Iranian, Hebrew, Hijri & Thai?
	 *
	 * @throws MWException
	 * @return string
	 */
	public function sprintfDate( $format, $ts, DateTimeZone $zone = null, &$ttl = 'unused' ) {
		$s = '';
		$raw = false;
		$roman = false;
		$hebrewNum = false;
		$dateTimeObj = false;
		$rawToggle = false;
		$iranian = false;
		$hebrew = false;
		$hijri = false;
		$thai = false;
		$minguo = false;
		$tenno = false;

		$usedSecond = false;
		$usedMinute = false;
		$usedHour = false;
		$usedAMPM = false;
		$usedDay = false;
		$usedWeek = false;
		$usedMonth = false;
		$usedYear = false;
		$usedISOYear = false;
		$usedIsLeapYear = false;

		$usedHebrewMonth = false;
		$usedIranianMonth = false;
		$usedHijriMonth = false;
		$usedHebrewYear = false;
		$usedIranianYear = false;
		$usedHijriYear = false;
		$usedTennoYear = false;

		if ( strlen( $ts ) !== 14 ) {
			throw new MWException( __METHOD__ . ": The timestamp $ts should have 14 characters" );
		}

		if ( !ctype_digit( $ts ) ) {
			throw new MWException( __METHOD__ . ": The timestamp $ts should be a number" );
		}

		$formatLength = strlen( $format );
		for ( $p = 0; $p < $formatLength; $p++ ) {
			$num = false;
			$code = $format[$p];
			if ( $code == 'x' && $p < $formatLength - 1 ) {
				$code .= $format[++$p];
			}

			if ( ( $code === 'xi'
					|| $code === 'xj'
					|| $code === 'xk'
					|| $code === 'xm'
					|| $code === 'xo'
					|| $code === 'xt' )
				&& $p < $formatLength - 1 ) {
				$code .= $format[++$p];
			}

			switch ( $code ) {
				case 'xx':
					$s .= 'x';
					break;
				case 'xn':
					$raw = true;
					break;
				case 'xN':
					$rawToggle = !$rawToggle;
					break;
				case 'xr':
					$roman = true;
					break;
				case 'xh':
					$hebrewNum = true;
					break;
				case 'xg':
					$usedMonth = true;
					$s .= $this->getMonthNameGen( substr( $ts, 4, 2 ) );
					break;
				case 'xjx':
					$usedHebrewMonth = true;
					if ( !$hebrew ) {
						$hebrew = self::tsToHebrew( $ts );
					}
					$s .= $this->getHebrewCalendarMonthNameGen( $hebrew[1] );
					break;
				case 'd':
					$usedDay = true;
					$num = substr( $ts, 6, 2 );
					break;
				case 'D':
					$usedDay = true;
					$s .= $this->getWeekdayAbbreviation(
						Language::dateTimeObjFormat( $dateTimeObj, $ts, $zone, 'w' ) + 1
					);
					break;
				case 'j':
					$usedDay = true;
					$num = intval( substr( $ts, 6, 2 ) );
					break;
				case 'xij':
					$usedDay = true;
					if ( !$iranian ) {
						$iranian = self::tsToIranian( $ts );
					}
					$num = $iranian[2];
					break;
				case 'xmj':
					$usedDay = true;
					if ( !$hijri ) {
						$hijri = self::tsToHijri( $ts );
					}
					$num = $hijri[2];
					break;
				case 'xjj':
					$usedDay = true;
					if ( !$hebrew ) {
						$hebrew = self::tsToHebrew( $ts );
					}
					$num = $hebrew[2];
					break;
				case 'l':
					$usedDay = true;
					$s .= $this->getWeekdayName(
						Language::dateTimeObjFormat( $dateTimeObj, $ts, $zone, 'w' ) + 1
					);
					break;
				case 'F':
					$usedMonth = true;
					$s .= $this->getMonthName( substr( $ts, 4, 2 ) );
					break;
				case 'xiF':
					$usedIranianMonth = true;
					if ( !$iranian ) {
						$iranian = self::tsToIranian( $ts );
					}
					$s .= $this->getIranianCalendarMonthName( $iranian[1] );
					break;
				case 'xmF':
					$usedHijriMonth = true;
					if ( !$hijri ) {
						$hijri = self::tsToHijri( $ts );
					}
					$s .= $this->getHijriCalendarMonthName( $hijri[1] );
					break;
				case 'xjF':
					$usedHebrewMonth = true;
					if ( !$hebrew ) {
						$hebrew = self::tsToHebrew( $ts );
					}
					$s .= $this->getHebrewCalendarMonthName( $hebrew[1] );
					break;
				case 'm':
					$usedMonth = true;
					$num = substr( $ts, 4, 2 );
					break;
				case 'M':
					$usedMonth = true;
					$s .= $this->getMonthAbbreviation( substr( $ts, 4, 2 ) );
					break;
				case 'n':
					$usedMonth = true;
					$num = intval( substr( $ts, 4, 2 ) );
					break;
				case 'xin':
					$usedIranianMonth = true;
					if ( !$iranian ) {
						$iranian = self::tsToIranian( $ts );
					}
					$num = $iranian[1];
					break;
				case 'xmn':
					$usedHijriMonth = true;
					if ( !$hijri ) {
						$hijri = self::tsToHijri( $ts );
					}
					$num = $hijri[1];
					break;
				case 'xjn':
					$usedHebrewMonth = true;
					if ( !$hebrew ) {
						$hebrew = self::tsToHebrew( $ts );
					}
					$num = $hebrew[1];
					break;
				case 'xjt':
					$usedHebrewMonth = true;
					if ( !$hebrew ) {
						$hebrew = self::tsToHebrew( $ts );
					}
					$num = $hebrew[3];
					break;
				case 'Y':
					$usedYear = true;
					$num = substr( $ts, 0, 4 );
					break;
				case 'xiY':
					$usedIranianYear = true;
					if ( !$iranian ) {
						$iranian = self::tsToIranian( $ts );
					}
					$num = $iranian[0];
					break;
				case 'xmY':
					$usedHijriYear = true;
					if ( !$hijri ) {
						$hijri = self::tsToHijri( $ts );
					}
					$num = $hijri[0];
					break;
				case 'xjY':
					$usedHebrewYear = true;
					if ( !$hebrew ) {
						$hebrew = self::tsToHebrew( $ts );
					}
					$num = $hebrew[0];
					break;
				case 'xkY':
					$usedYear = true;
					if ( !$thai ) {
						$thai = self::tsToYear( $ts, 'thai' );
					}
					$num = $thai[0];
					break;
				case 'xoY':
					$usedYear = true;
					if ( !$minguo ) {
						$minguo = self::tsToYear( $ts, 'minguo' );
					}
					$num = $minguo[0];
					break;
				case 'xtY':
					$usedTennoYear = true;
					if ( !$tenno ) {
						$tenno = self::tsToYear( $ts, 'tenno' );
					}
					$num = $tenno[0];
					break;
				case 'y':
					$usedYear = true;
					$num = substr( $ts, 2, 2 );
					break;
				case 'xiy':
					$usedIranianYear = true;
					if ( !$iranian ) {
						$iranian = self::tsToIranian( $ts );
					}
					$num = substr( $iranian[0], -2 );
					break;
				case 'xit':
					$usedIranianYear = true;
					if ( !$iranian ) {
						$iranian = self::tsToIranian( $ts );
					}
					$num = self::$IRANIAN_DAYS[$iranian[1] - 1];
					break;
				case 'xiz':
					$usedIranianYear = true;
					if ( !$iranian ) {
						$iranian = self::tsToIranian( $ts );
					}
					$num = $iranian[3];
					break;
				case 'a':
					$usedAMPM = true;
					$s .= intval( substr( $ts, 8, 2 ) ) < 12 ? 'am' : 'pm';
					break;
				case 'A':
					$usedAMPM = true;
					$s .= intval( substr( $ts, 8, 2 ) ) < 12 ? 'AM' : 'PM';
					break;
				case 'g':
					$usedHour = true;
					$h = substr( $ts, 8, 2 );
					$num = $h % 12 ? $h % 12 : 12;
					break;
				case 'G':
					$usedHour = true;
					$num = intval( substr( $ts, 8, 2 ) );
					break;
				case 'h':
					$usedHour = true;
					$h = substr( $ts, 8, 2 );
					$num = sprintf( '%02d', $h % 12 ? $h % 12 : 12 );
					break;
				case 'H':
					$usedHour = true;
					$num = substr( $ts, 8, 2 );
					break;
				case 'i':
					$usedMinute = true;
					$num = substr( $ts, 10, 2 );
					break;
				case 's':
					$usedSecond = true;
					$num = substr( $ts, 12, 2 );
					break;
				case 'c':
				case 'r':
					$usedSecond = true;
					// fall through
				case 'e':
				case 'O':
				case 'P':
				case 'T':
					$s .= Language::dateTimeObjFormat( $dateTimeObj, $ts, $zone, $code );
					break;
				case 'w':
				case 'N':
				case 'z':
					$usedDay = true;
					$num = Language::dateTimeObjFormat( $dateTimeObj, $ts, $zone, $code );
					break;
				case 'W':
					$usedWeek = true;
					$num = Language::dateTimeObjFormat( $dateTimeObj, $ts, $zone, $code );
					break;
				case 't':
					$usedMonth = true;
					$num = Language::dateTimeObjFormat( $dateTimeObj, $ts, $zone, $code );
					break;
				case 'L':
					$usedIsLeapYear = true;
					$num = Language::dateTimeObjFormat( $dateTimeObj, $ts, $zone, $code );
					break;
				case 'o':
					$usedISOYear = true;
					$num = Language::dateTimeObjFormat( $dateTimeObj, $ts, $zone, $code );
					break;
				case 'U':
					$usedSecond = true;
					// fall through
				case 'I':
				case 'Z':
					$num = Language::dateTimeObjFormat( $dateTimeObj, $ts, $zone, $code );
					break;
				case '\\':
					# Backslash escaping
					if ( $p < $formatLength - 1 ) {
						$s .= $format[++$p];
					} else {
						$s .= '\\';
					}
					break;
				case '"':
					# Quoted literal
					if ( $p < $formatLength - 1 ) {
						$endQuote = strpos( $format, '"', $p + 1 );
						if ( $endQuote === false ) {
							# No terminating quote, assume literal "
							$s .= '"';
						} else {
							$s .= substr( $format, $p + 1, $endQuote - $p - 1 );
							$p = $endQuote;
						}
					} else {
						# Quote at end of string, assume literal "
						$s .= '"';
					}
					break;
				default:
					$s .= $format[$p];
			}
			if ( $num !== false ) {
				if ( $rawToggle || $raw ) {
					$s .= $num;
					$raw = false;
				} elseif ( $roman ) {
					$s .= Language::romanNumeral( $num );
					$roman = false;
				} elseif ( $hebrewNum ) {
					$s .= self::hebrewNumeral( $num );
					$hebrewNum = false;
				} else {
					$s .= $this->formatNum( $num, true );
				}
			}
		}

		if ( $ttl === 'unused' ) {
			// No need to calculate the TTL, the caller wont use it anyway.
		} elseif ( $usedSecond ) {
			$ttl = 1;
		} elseif ( $usedMinute ) {
			$ttl = 60 - substr( $ts, 12, 2 );
		} elseif ( $usedHour ) {
			$ttl = 3600 - substr( $ts, 10, 2 ) * 60 - substr( $ts, 12, 2 );
		} elseif ( $usedAMPM ) {
			$ttl = 43200 - ( substr( $ts, 8, 2 ) % 12 ) * 3600 -
				substr( $ts, 10, 2 ) * 60 - substr( $ts, 12, 2 );
		} elseif (
			$usedDay ||
			$usedHebrewMonth ||
			$usedIranianMonth ||
			$usedHijriMonth ||
			$usedHebrewYear ||
			$usedIranianYear ||
			$usedHijriYear ||
			$usedTennoYear
		) {
			// @todo Someone who understands the non-Gregorian calendars
			// should write proper logic for them so that they don't need purged every day.
			$ttl = 86400 - substr( $ts, 8, 2 ) * 3600 -
				substr( $ts, 10, 2 ) * 60 - substr( $ts, 12, 2 );
		} else {
			$possibleTtls = [];
			$timeRemainingInDay = 86400 - substr( $ts, 8, 2 ) * 3600 -
				substr( $ts, 10, 2 ) * 60 - substr( $ts, 12, 2 );
			if ( $usedWeek ) {
				$possibleTtls[] =
					( 7 - Language::dateTimeObjFormat( $dateTimeObj, $ts, $zone, 'N' ) ) * 86400 +
					$timeRemainingInDay;
			} elseif ( $usedISOYear ) {
				// December 28th falls on the last ISO week of the year, every year.
				// The last ISO week of a year can be 52 or 53.
				$lastWeekOfISOYear = DateTime::createFromFormat(
					'Ymd',
					substr( $ts, 0, 4 ) . '1228',
					$zone ?: new DateTimeZone( 'UTC' )
				)->format( 'W' );
				$currentISOWeek = Language::dateTimeObjFormat( $dateTimeObj, $ts, $zone, 'W' );
				$weeksRemaining = $lastWeekOfISOYear - $currentISOWeek;
				$timeRemainingInWeek =
					( 7 - Language::dateTimeObjFormat( $dateTimeObj, $ts, $zone, 'N' ) ) * 86400
					+ $timeRemainingInDay;
				$possibleTtls[] = $weeksRemaining * 604800 + $timeRemainingInWeek;
			}

			if ( $usedMonth ) {
				$possibleTtls[] =
					( Language::dateTimeObjFormat( $dateTimeObj, $ts, $zone, 't' ) -
						substr( $ts, 6, 2 ) ) * 86400
					+ $timeRemainingInDay;
			} elseif ( $usedYear ) {
				$possibleTtls[] =
					( Language::dateTimeObjFormat( $dateTimeObj, $ts, $zone, 'L' ) + 364 -
						Language::dateTimeObjFormat( $dateTimeObj, $ts, $zone, 'z' ) ) * 86400
					+ $timeRemainingInDay;
			} elseif ( $usedIsLeapYear ) {
				$year = substr( $ts, 0, 4 );
				$timeRemainingInYear =
					( Language::dateTimeObjFormat( $dateTimeObj, $ts, $zone, 'L' ) + 364 -
						Language::dateTimeObjFormat( $dateTimeObj, $ts, $zone, 'z' ) ) * 86400
					+ $timeRemainingInDay;
				$mod = $year % 4;
				if ( $mod || ( !( $year % 100 ) && $year % 400 ) ) {
					// this isn't a leap year. see when the next one starts
					$nextCandidate = $year - $mod + 4;
					if ( $nextCandidate % 100 || !( $nextCandidate % 400 ) ) {
						$possibleTtls[] = ( $nextCandidate - $year - 1 ) * 365 * 86400 +
							$timeRemainingInYear;
					} else {
						$possibleTtls[] = ( $nextCandidate - $year + 3 ) * 365 * 86400 +
							$timeRemainingInYear;
					}
				} else {
					// this is a leap year, so the next year isn't
					$possibleTtls[] = $timeRemainingInYear;
				}
			}

			if ( $possibleTtls ) {
				$ttl = min( $possibleTtls );
			}
		}

		return $s;
	}

	private static $GREG_DAYS = [ 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 ];
	private static $IRANIAN_DAYS = [ 31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29 ];

	/**
	 * Algorithm by Roozbeh Pournader and Mohammad Toossi to convert
	 * Gregorian dates to Iranian dates. Originally written in C, it
	 * is released under the terms of GNU Lesser General Public
	 * License. Conversion to PHP was performed by Niklas Laxström.
	 *
	 * Link: http://www.farsiweb.info/jalali/jalali.c
	 *
	 * @param string $ts
	 *
	 * @return int[]
	 */
	private static function tsToIranian( $ts ) {
		$gy = substr( $ts, 0, 4 ) -1600;
		$gm = substr( $ts, 4, 2 ) -1;
		$gd = substr( $ts, 6, 2 ) -1;

		# Days passed from the beginning (including leap years)
		$gDayNo = 365 * $gy
			+ floor( ( $gy + 3 ) / 4 )
			- floor( ( $gy + 99 ) / 100 )
			+ floor( ( $gy + 399 ) / 400 );

		// Add days of the past months of this year
		for ( $i = 0; $i < $gm; $i++ ) {
			$gDayNo += self::$GREG_DAYS[$i];
		}

		// Leap years
		if ( $gm > 1 && ( ( $gy % 4 === 0 && $gy % 100 !== 0 || ( $gy % 400 == 0 ) ) ) ) {
			$gDayNo++;
		}

		// Days passed in current month
		$gDayNo += (int)$gd;

		$jDayNo = $gDayNo - 79;

		$jNp = floor( $jDayNo / 12053 );
		$jDayNo %= 12053;

		$jy = 979 + 33 * $jNp + 4 * floor( $jDayNo / 1461 );
		$jDayNo %= 1461;

		if ( $jDayNo >= 366 ) {
			$jy += floor( ( $jDayNo - 1 ) / 365 );
			$jDayNo = floor( ( $jDayNo - 1 ) % 365 );
		}

		$jz = $jDayNo;

		for ( $i = 0; $i < 11 && $jDayNo >= self::$IRANIAN_DAYS[$i]; $i++ ) {
			$jDayNo -= self::$IRANIAN_DAYS[$i];
		}

		$jm = $i + 1;
		$jd = $jDayNo + 1;

		return [ $jy, $jm, $jd, $jz ];
	}

	/**
	 * Converting Gregorian dates to Hijri dates.
	 *
	 * Based on a PHP-Nuke block by Sharjeel which is released under GNU/GPL license
	 *
	 * @see https://phpnuke.org/modules.php?name=News&file=article&sid=8234&mode=thread&order=0&thold=0
	 *
	 * @param string $ts
	 *
	 * @return int[]
	 */
	private static function tsToHijri( $ts ) {
		$year = substr( $ts, 0, 4 );
		$month = substr( $ts, 4, 2 );
		$day = substr( $ts, 6, 2 );

		$zyr = $year;
		$zd = $day;
		$zm = $month;
		$zy = $zyr;

		if (
			( $zy > 1582 ) || ( ( $zy == 1582 ) && ( $zm > 10 ) ) ||
			( ( $zy == 1582 ) && ( $zm == 10 ) && ( $zd > 14 ) )
		) {
			$zjd = (int)( ( 1461 * ( $zy + 4800 + (int)( ( $zm - 14 ) / 12 ) ) ) / 4 ) +
					(int)( ( 367 * ( $zm - 2 - 12 * ( (int)( ( $zm - 14 ) / 12 ) ) ) ) / 12 ) -
					(int)( ( 3 * (int)( ( ( $zy + 4900 + (int)( ( $zm - 14 ) / 12 ) ) / 100 ) ) ) / 4 ) +
					$zd - 32075;
		} else {
			$zjd = 367 * $zy - (int)( ( 7 * ( $zy + 5001 + (int)( ( $zm - 9 ) / 7 ) ) ) / 4 ) +
								(int)( ( 275 * $zm ) / 9 ) + $zd + 1729777;
		}

		$zl = $zjd -1948440 + 10632;
		$zn = (int)( ( $zl - 1 ) / 10631 );
		$zl = $zl - 10631 * $zn + 354;
		$zj = ( (int)( ( 10985 - $zl ) / 5316 ) ) * ( (int)( ( 50 * $zl ) / 17719 ) ) +
			( (int)( $zl / 5670 ) ) * ( (int)( ( 43 * $zl ) / 15238 ) );
		$zl = $zl - ( (int)( ( 30 - $zj ) / 15 ) ) * ( (int)( ( 17719 * $zj ) / 50 ) ) -
			( (int)( $zj / 16 ) ) * ( (int)( ( 15238 * $zj ) / 43 ) ) + 29;
		$zm = (int)( ( 24 * $zl ) / 709 );
		$zd = $zl - (int)( ( 709 * $zm ) / 24 );
		$zy = 30 * $zn + $zj - 30;

		return [ $zy, $zm, $zd ];
	}

	/**
	 * Converting Gregorian dates to Hebrew dates.
	 *
	 * Based on a JavaScript code by Abu Mami and Yisrael Hersch
	 * (abu-mami@kaluach.net, http://www.kaluach.net), who permitted
	 * to translate the relevant functions into PHP and release them under
	 * GNU GPL.
	 *
	 * The months are counted from Tishrei = 1. In a leap year, Adar I is 13
	 * and Adar II is 14. In a non-leap year, Adar is 6.
	 *
	 * @param string $ts
	 *
	 * @return int[]
	 */
	private static function tsToHebrew( $ts ) {
		# Parse date
		$year = substr( $ts, 0, 4 );
		$month = substr( $ts, 4, 2 );
		$day = substr( $ts, 6, 2 );

		# Calculate Hebrew year
		$hebrewYear = $year + 3760;

		# Month number when September = 1, August = 12
		$month += 4;
		if ( $month > 12 ) {
			# Next year
			$month -= 12;
			$year++;
			$hebrewYear++;
		}

		# Calculate day of year from 1 September
		$dayOfYear = $day;
		for ( $i = 1; $i < $month; $i++ ) {
			if ( $i == 6 ) {
				# February
				$dayOfYear += 28;
				# Check if the year is leap
				if ( $year % 400 == 0 || ( $year % 4 == 0 && $year % 100 > 0 ) ) {
					$dayOfYear++;
				}
			} elseif ( $i == 8 || $i == 10 || $i == 1 || $i == 3 ) {
				$dayOfYear += 30;
			} else {
				$dayOfYear += 31;
			}
		}

		# Calculate the start of the Hebrew year
		$start = self::hebrewYearStart( $hebrewYear );

		# Calculate next year's start
		if ( $dayOfYear <= $start ) {
			# Day is before the start of the year - it is the previous year
			# Next year's start
			$nextStart = $start;
			# Previous year
			$year--;
			$hebrewYear--;
			# Add days since previous year's 1 September
			$dayOfYear += 365;
			if ( ( $year % 400 == 0 ) || ( $year % 100 != 0 && $year % 4 == 0 ) ) {
				# Leap year
				$dayOfYear++;
			}
			# Start of the new (previous) year
			$start = self::hebrewYearStart( $hebrewYear );
		} else {
			# Next year's start
			$nextStart = self::hebrewYearStart( $hebrewYear + 1 );
		}

		# Calculate Hebrew day of year
		$hebrewDayOfYear = $dayOfYear - $start;

		# Difference between year's days
		$diff = $nextStart - $start;
		# Add 12 (or 13 for leap years) days to ignore the difference between
		# Hebrew and Gregorian year (353 at least vs. 365/6) - now the
		# difference is only about the year type
		if ( ( $year % 400 == 0 ) || ( $year % 100 != 0 && $year % 4 == 0 ) ) {
			$diff += 13;
		} else {
			$diff += 12;
		}

		# Check the year pattern, and is leap year
		# 0 means an incomplete year, 1 means a regular year, 2 means a complete year
		# This is mod 30, to work on both leap years (which add 30 days of Adar I)
		# and non-leap years
		$yearPattern = $diff % 30;
		# Check if leap year
		$isLeap = $diff >= 30;

		# Calculate day in the month from number of day in the Hebrew year
		# Don't check Adar - if the day is not in Adar, we will stop before;
		# if it is in Adar, we will use it to check if it is Adar I or Adar II
		$hebrewDay = $hebrewDayOfYear;
		$hebrewMonth = 1;
		$days = 0;
		while ( $hebrewMonth <= 12 ) {
			# Calculate days in this month
			if ( $isLeap && $hebrewMonth == 6 ) {
				# Adar in a leap year
				if ( $isLeap ) {
					# Leap year - has Adar I, with 30 days, and Adar II, with 29 days
					$days = 30;
					if ( $hebrewDay <= $days ) {
						# Day in Adar I
						$hebrewMonth = 13;
					} else {
						# Subtract the days of Adar I
						$hebrewDay -= $days;
						# Try Adar II
						$days = 29;
						if ( $hebrewDay <= $days ) {
							# Day in Adar II
							$hebrewMonth = 14;
						}
					}
				}
			} elseif ( $hebrewMonth == 2 && $yearPattern == 2 ) {
				# Cheshvan in a complete year (otherwise as the rule below)
				$days = 30;
			} elseif ( $hebrewMonth == 3 && $yearPattern == 0 ) {
				# Kislev in an incomplete year (otherwise as the rule below)
				$days = 29;
			} else {
				# Odd months have 30 days, even have 29
				$days = 30 - ( $hebrewMonth - 1 ) % 2;
			}
			if ( $hebrewDay <= $days ) {
				# In the current month
				break;
			} else {
				# Subtract the days of the current month
				$hebrewDay -= $days;
				# Try in the next month
				$hebrewMonth++;
			}
		}

		return [ $hebrewYear, $hebrewMonth, $hebrewDay, $days ];
	}

	/**
	 * This calculates the Hebrew year start, as days since 1 September.
	 * Based on Carl Friedrich Gauss algorithm for finding Easter date.
	 * Used for Hebrew date.
	 *
	 * @param int $year
	 *
	 * @return string
	 */
	private static function hebrewYearStart( $year ) {
		$a = intval( ( 12 * ( $year - 1 ) + 17 ) % 19 );
		$b = intval( ( $year - 1 ) % 4 );
		$m = 32.044093161144 + 1.5542417966212 * $a + $b / 4.0 - 0.0031777940220923 * ( $year - 1 );
		if ( $m < 0 ) {
			$m--;
		}
		$Mar = intval( $m );
		if ( $m < 0 ) {
			$m++;
		}
		$m -= $Mar;

		$c = intval( ( $Mar + 3 * ( $year - 1 ) + 5 * $b + 5 ) % 7 );
		if ( $c == 0 && $a > 11 && $m >= 0.89772376543210 ) {
			$Mar++;
		} elseif ( $c == 1 && $a > 6 && $m >= 0.63287037037037 ) {
			$Mar += 2;
		} elseif ( $c == 2 || $c == 4 || $c == 6 ) {
			$Mar++;
		}

		$Mar += intval( ( $year - 3761 ) / 100 ) - intval( ( $year - 3761 ) / 400 ) - 24;
		return $Mar;
	}

	/**
	 * Algorithm to convert Gregorian dates to Thai solar dates,
	 * Minguo dates or Minguo dates.
	 *
	 * Link: https://en.wikipedia.org/wiki/Thai_solar_calendar
	 *       https://en.wikipedia.org/wiki/Minguo_calendar
	 *       https://en.wikipedia.org/wiki/Japanese_era_name
	 *
	 * @param string $ts 14-character timestamp
	 * @param string $cName Calender name
	 * @return array Converted year, month, day
	 */
	private static function tsToYear( $ts, $cName ) {
		$gy = substr( $ts, 0, 4 );
		$gm = substr( $ts, 4, 2 );
		$gd = substr( $ts, 6, 2 );

		if ( !strcmp( $cName, 'thai' ) ) {
			# Thai solar dates
			# Add 543 years to the Gregorian calendar
			# Months and days are identical
			$gy_offset = $gy + 543;
		} elseif ( ( !strcmp( $cName, 'minguo' ) ) || !strcmp( $cName, 'juche' ) ) {
			# Minguo dates
			# Deduct 1911 years from the Gregorian calendar
			# Months and days are identical
			$gy_offset = $gy - 1911;
		} elseif ( !strcmp( $cName, 'tenno' ) ) {
			# Nengō dates up to Meiji period
			# Deduct years from the Gregorian calendar
			# depending on the nengo periods
			# Months and days are identical
			if ( ( $gy < 1912 )
				|| ( ( $gy == 1912 ) && ( $gm < 7 ) )
				|| ( ( $gy == 1912 ) && ( $gm == 7 ) && ( $gd < 31 ) )
			) {
				# Meiji period
				$gy_gannen = $gy - 1868 + 1;
				$gy_offset = $gy_gannen;
				if ( $gy_gannen == 1 ) {
					$gy_offset = '元';
				}
				$gy_offset = '明治' . $gy_offset;
			} elseif (
				( ( $gy == 1912 ) && ( $gm == 7 ) && ( $gd == 31 ) ) ||
				( ( $gy == 1912 ) && ( $gm >= 8 ) ) ||
				( ( $gy > 1912 ) && ( $gy < 1926 ) ) ||
				( ( $gy == 1926 ) && ( $gm < 12 ) ) ||
				( ( $gy == 1926 ) && ( $gm == 12 ) && ( $gd < 26 ) )
			) {
				# Taishō period
				$gy_gannen = $gy - 1912 + 1;
				$gy_offset = $gy_gannen;
				if ( $gy_gannen == 1 ) {
					$gy_offset = '元';
				}
				$gy_offset = '大正' . $gy_offset;
			} elseif (
				( ( $gy == 1926 ) && ( $gm == 12 ) && ( $gd >= 26 ) ) ||
				( ( $gy > 1926 ) && ( $gy < 1989 ) ) ||
				( ( $gy == 1989 ) && ( $gm == 1 ) && ( $gd < 8 ) )
			) {
				# Shōwa period
				$gy_gannen = $gy - 1926 + 1;
				$gy_offset = $gy_gannen;
				if ( $gy_gannen == 1 ) {
					$gy_offset = '元';
				}
				$gy_offset = '昭和' . $gy_offset;
			} else {
				# Heisei period
				$gy_gannen = $gy - 1989 + 1;
				$gy_offset = $gy_gannen;
				if ( $gy_gannen == 1 ) {
					$gy_offset = '元';
				}
				$gy_offset = '平成' . $gy_offset;
			}
		} else {
			$gy_offset = $gy;
		}

		return [ $gy_offset, $gm, $gd ];
	}

	/**
	 * Gets directionality of the first strongly directional codepoint, for embedBidi()
	 *
	 * This is the rule the BIDI algorithm uses to determine the directionality of
	 * paragraphs ( http://unicode.org/reports/tr9/#The_Paragraph_Level ) and
	 * FSI isolates ( http://unicode.org/reports/tr9/#Explicit_Directional_Isolates ).
	 *
	 * TODO: Does not handle BIDI control characters inside the text.
	 * TODO: Does not handle unallocated characters.
	 *
	 * @param string $text Text to test
	 * @return null|string Directionality ('ltr' or 'rtl') or null
	 */
	private static function strongDirFromContent( $text = '' ) {
		if ( !preg_match( self::$strongDirRegex, $text, $matches ) ) {
			return null;
		}
		if ( $matches[1] === '' ) {
			return 'rtl';
		}
		return 'ltr';
	}

	/**
	 * Roman number formatting up to 10000
	 *
	 * @param int $num
	 *
	 * @return string
	 */
	static function romanNumeral( $num ) {
		static $table = [
			[ '', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X' ],
			[ '', 'X', 'XX', 'XXX', 'XL', 'L', 'LX', 'LXX', 'LXXX', 'XC', 'C' ],
			[ '', 'C', 'CC', 'CCC', 'CD', 'D', 'DC', 'DCC', 'DCCC', 'CM', 'M' ],
			[ '', 'M', 'MM', 'MMM', 'MMMM', 'MMMMM', 'MMMMMM', 'MMMMMMM',
				'MMMMMMMM', 'MMMMMMMMM', 'MMMMMMMMMM' ]
		];

		$num = intval( $num );
		if ( $num > 10000 || $num <= 0 ) {
			return $num;
		}

		$s = '';
		for ( $pow10 = 1000, $i = 3; $i >= 0; $pow10 /= 10, $i-- ) {
			if ( $num >= $pow10 ) {
				$s .= $table[$i][(int)floor( $num / $pow10 )];
			}
			$num = $num % $pow10;
		}
		return $s;
	}

	/**
	 * Hebrew Gematria number formatting up to 9999
	 *
	 * @param int $num
	 *
	 * @return string
	 */
	static function hebrewNumeral( $num ) {
		static $table = [
			[ '', 'א', 'ב', 'ג', 'ד', 'ה', 'ו', 'ז', 'ח', 'ט', 'י' ],
			[ '', 'י', 'כ', 'ל', 'מ', 'נ', 'ס', 'ע', 'פ', 'צ', 'ק' ],
			[ '',
				[ 'ק' ],
				[ 'ר' ],
				[ 'ש' ],
				[ 'ת' ],
				[ 'ת', 'ק' ],
				[ 'ת', 'ר' ],
				[ 'ת', 'ש' ],
				[ 'ת', 'ת' ],
				[ 'ת', 'ת', 'ק' ],
				[ 'ת', 'ת', 'ר' ],
			],
			[ '', 'א', 'ב', 'ג', 'ד', 'ה', 'ו', 'ז', 'ח', 'ט', 'י' ]
		];

		$num = intval( $num );
		if ( $num > 9999 || $num <= 0 ) {
			return $num;
		}

		// Round thousands have special notations
		if ( $num === 1000 ) {
			return "א' אלף";
		} elseif ( $num % 1000 === 0 ) {
			return $table[0][$num / 1000] . "' אלפים";
		}

		$letters = [];

		for ( $pow10 = 1000, $i = 3; $i >= 0; $pow10 /= 10, $i-- ) {
			if ( $num >= $pow10 ) {
				if ( $num === 15 || $num === 16 ) {
					$letters[] = $table[0][9];
					$letters[] = $table[0][$num - 9];
					$num = 0;
				} else {
					$letters = array_merge(
						$letters,
						(array)$table[$i][intval( $num / $pow10 )]
					);

					if ( $pow10 === 1000 ) {
						$letters[] = "'";
					}
				}
			}

			$num = $num % $pow10;
		}

		$preTransformLength = count( $letters );
		if ( $preTransformLength === 1 ) {
			// Add geresh (single quote) to one-letter numbers
			$letters[] = "'";
		} else {
			$lastIndex = $preTransformLength - 1;
			$letters[$lastIndex] = str_replace(
				[ 'כ', 'מ', 'נ', 'פ', 'צ' ],
				[ 'ך', 'ם', 'ן', 'ף', 'ץ' ],
				$letters[$lastIndex]
			);

			// Add gershayim (double quote) to multiple-letter numbers,
			// but exclude numbers with only one letter after the thousands
			// (1001-1009, 1020, 1030, 2001-2009, etc.)
			if ( $letters[1] === "'" && $preTransformLength === 3 ) {
				$letters[] = "'";
			} else {
				array_splice( $letters, -1, 0, '"' );
			}
		}

		return implode( $letters );
	}

	/**
	 * Used by date() and time() to adjust the time output.
	 *
	 * @param string $ts The time in date('YmdHis') format
	 * @param mixed $tz Adjust the time by this amount (default false, mean we
	 *   get user timecorrection setting)
	 * @return int
	 */
	public function userAdjust( $ts, $tz = false ) {
		global $wgUser, $wgLocalTZoffset;

		if ( $tz === false ) {
			$tz = $wgUser->getOption( 'timecorrection' );
		}

		$data = explode( '|', $tz, 3 );

		if ( $data[0] == 'ZoneInfo' ) {
			try {
				$userTZ = new DateTimeZone( $data[2] );
				$date = new DateTime( $ts, new DateTimeZone( 'UTC' ) );
				$date->setTimezone( $userTZ );
				return $date->format( 'YmdHis' );
			} catch ( Exception $e ) {
				// Unrecognized timezone, default to 'Offset' with the stored offset.
				$data[0] = 'Offset';
			}
		}

		if ( $data[0] == 'System' || $tz == '' ) {
			# Global offset in minutes.
			$minDiff = $wgLocalTZoffset;
		} elseif ( $data[0] == 'Offset' ) {
			$minDiff = intval( $data[1] );
		} else {
			$data = explode( ':', $tz );
			if ( count( $data ) == 2 ) {
				$data[0] = intval( $data[0] );
				$data[1] = intval( $data[1] );
				$minDiff = abs( $data[0] ) * 60 + $data[1];
				if ( $data[0] < 0 ) {
					$minDiff = -$minDiff;
				}
			} else {
				$minDiff = intval( $data[0] ) * 60;
			}
		}

		# No difference ? Return time unchanged
		if ( 0 == $minDiff ) {
			return $ts;
		}

		MediaWiki\suppressWarnings(); // E_STRICT system time bitching
		# Generate an adjusted date; take advantage of the fact that mktime
		# will normalize out-of-range values so we don't have to split $minDiff
		# into hours and minutes.
		$t = mktime( (
			(int)substr( $ts, 8, 2 ) ), # Hours
			(int)substr( $ts, 10, 2 ) + $minDiff, # Minutes
			(int)substr( $ts, 12, 2 ), # Seconds
			(int)substr( $ts, 4, 2 ), # Month
			(int)substr( $ts, 6, 2 ), # Day
			(int)substr( $ts, 0, 4 ) ); # Year

		$date = date( 'YmdHis', $t );
		MediaWiki\restoreWarnings();

		return $date;
	}

	/**
	 * This is meant to be used by time(), date(), and timeanddate() to get
	 * the date preference they're supposed to use, it should be used in
	 * all children.
	 *
	 *     function timeanddate([...], $format = true) {
	 *       $datePreference = $this->dateFormat($format);
	 *       [...]
	 *     }
	 *
	 * @param int|string|bool $usePrefs If true, the user's preference is used
	 *   if false, the site/language default is used
	 *   if int/string, assumed to be a format.
	 * @return string
	 */
	function dateFormat( $usePrefs = true ) {
		global $wgUser;

		if ( is_bool( $usePrefs ) ) {
			if ( $usePrefs ) {
				$datePreference = $wgUser->getDatePreference();
			} else {
				$datePreference = (string)User::getDefaultOption( 'date' );
			}
		} else {
			$datePreference = (string)$usePrefs;
		}

		// return int
		if ( $datePreference == '' ) {
			return 'default';
		}

		return $datePreference;
	}

	/**
	 * Get a format string for a given type and preference
	 * @param string $type May be 'date', 'time', 'both', or 'pretty'.
	 * @param string $pref The format name as it appears in Messages*.php under
	 *  $datePreferences.
	 *
	 * @since 1.22 New type 'pretty' that provides a more readable timestamp format
	 *
	 * @return string
	 */
	function getDateFormatString( $type, $pref ) {
		$wasDefault = false;
		if ( $pref == 'default' ) {
			$wasDefault = true;
			$pref = $this->getDefaultDateFormat();
		}

		if ( !isset( $this->dateFormatStrings[$type][$pref] ) ) {
			$df = self::$dataCache->getSubitem( $this->mCode, 'dateFormats', "$pref $type" );

			if ( $type === 'pretty' && $df === null ) {
				$df = $this->getDateFormatString( 'date', $pref );
			}

			if ( !$wasDefault && $df === null ) {
				$pref = $this->getDefaultDateFormat();
				$df = self::$dataCache->getSubitem( $this->mCode, 'dateFormats', "$pref $type" );
			}

			$this->dateFormatStrings[$type][$pref] = $df;
		}
		return $this->dateFormatStrings[$type][$pref];
	}

	/**
	 * @param string $ts The time format which needs to be turned into a
	 *   date('YmdHis') format with wfTimestamp(TS_MW,$ts)
	 * @param bool $adj Whether to adjust the time output according to the
	 *   user configured offset ($timecorrection)
	 * @param mixed $format True to use user's date format preference
	 * @param string|bool $timecorrection The time offset as returned by
	 *   validateTimeZone() in Special:Preferences
	 * @return string
	 */
	public function date( $ts, $adj = false, $format = true, $timecorrection = false ) {
		$ts = wfTimestamp( TS_MW, $ts );
		if ( $adj ) {
			$ts = $this->userAdjust( $ts, $timecorrection );
		}
		$df = $this->getDateFormatString( 'date', $this->dateFormat( $format ) );
		return $this->sprintfDate( $df, $ts );
	}

	/**
	 * @param string $ts The time format which needs to be turned into a
	 *   date('YmdHis') format with wfTimestamp(TS_MW,$ts)
	 * @param bool $adj Whether to adjust the time output according to the
	 *   user configured offset ($timecorrection)
	 * @param mixed $format True to use user's date format preference
	 * @param string|bool $timecorrection The time offset as returned by
	 *   validateTimeZone() in Special:Preferences
	 * @return string
	 */
	public function time( $ts, $adj = false, $format = true, $timecorrection = false ) {
		$ts = wfTimestamp( TS_MW, $ts );
		if ( $adj ) {
			$ts = $this->userAdjust( $ts, $timecorrection );
		}
		$df = $this->getDateFormatString( 'time', $this->dateFormat( $format ) );
		return $this->sprintfDate( $df, $ts );
	}

	/**
	 * @param string $ts The time format which needs to be turned into a
	 *   date('YmdHis') format with wfTimestamp(TS_MW,$ts)
	 * @param bool $adj Whether to adjust the time output according to the
	 *   user configured offset ($timecorrection)
	 * @param mixed $format What format to return, if it's false output the
	 *   default one (default true)
	 * @param string|bool $timecorrection The time offset as returned by
	 *   validateTimeZone() in Special:Preferences
	 * @return string
	 */
	public function timeanddate( $ts, $adj = false, $format = true, $timecorrection = false ) {
		$ts = wfTimestamp( TS_MW, $ts );
		if ( $adj ) {
			$ts = $this->userAdjust( $ts, $timecorrection );
		}
		$df = $this->getDateFormatString( 'both', $this->dateFormat( $format ) );
		return $this->sprintfDate( $df, $ts );
	}

	/**
	 * Takes a number of seconds and turns it into a text using values such as hours and minutes.
	 *
	 * @since 1.20
	 *
	 * @param int $seconds The amount of seconds.
	 * @param array $chosenIntervals The intervals to enable.
	 *
	 * @return string
	 */
	public function formatDuration( $seconds, array $chosenIntervals = [] ) {
		$intervals = $this->getDurationIntervals( $seconds, $chosenIntervals );

		$segments = [];

		foreach ( $intervals as $intervalName => $intervalValue ) {
			// Messages: duration-seconds, duration-minutes, duration-hours, duration-days, duration-weeks,
			// duration-years, duration-decades, duration-centuries, duration-millennia
			$message = wfMessage( 'duration-' . $intervalName )->numParams( $intervalValue );
			$segments[] = $message->inLanguage( $this )->escaped();
		}

		return $this->listToText( $segments );
	}

	/**
	 * Takes a number of seconds and returns an array with a set of corresponding intervals.
	 * For example 65 will be turned into [ minutes => 1, seconds => 5 ].
	 *
	 * @since 1.20
	 *
	 * @param int $seconds The amount of seconds.
	 * @param array $chosenIntervals The intervals to enable.
	 *
	 * @return array
	 */
	public function getDurationIntervals( $seconds, array $chosenIntervals = [] ) {
		if ( empty( $chosenIntervals ) ) {
			$chosenIntervals = [
				'millennia',
				'centuries',
				'decades',
				'years',
				'days',
				'hours',
				'minutes',
				'seconds'
			];
		}

		$intervals = array_intersect_key( self::$durationIntervals, array_flip( $chosenIntervals ) );
		$sortedNames = array_keys( $intervals );
		$smallestInterval = array_pop( $sortedNames );

		$segments = [];

		foreach ( $intervals as $name => $length ) {
			$value = floor( $seconds / $length );

			if ( $value > 0 || ( $name == $smallestInterval && empty( $segments ) ) ) {
				$seconds -= $value * $length;
				$segments[$name] = $value;
			}
		}

		return $segments;
	}

	/**
	 * Internal helper function for userDate(), userTime() and userTimeAndDate()
	 *
	 * @param string $type Can be 'date', 'time' or 'both'
	 * @param string $ts The time format which needs to be turned into a
	 *   date('YmdHis') format with wfTimestamp(TS_MW,$ts)
	 * @param User $user User object used to get preferences for timezone and format
	 * @param array $options Array, can contain the following keys:
	 *   - 'timecorrection': time correction, can have the following values:
	 *     - true: use user's preference
	 *     - false: don't use time correction
	 *     - int: value of time correction in minutes
	 *   - 'format': format to use, can have the following values:
	 *     - true: use user's preference
	 *     - false: use default preference
	 *     - string: format to use
	 * @since 1.19
	 * @return string
	 */
	private function internalUserTimeAndDate( $type, $ts, User $user, array $options ) {
		$ts = wfTimestamp( TS_MW, $ts );
		$options += [ 'timecorrection' => true, 'format' => true ];
		if ( $options['timecorrection'] !== false ) {
			if ( $options['timecorrection'] === true ) {
				$offset = $user->getOption( 'timecorrection' );
			} else {
				$offset = $options['timecorrection'];
			}
			$ts = $this->userAdjust( $ts, $offset );
		}
		if ( $options['format'] === true ) {
			$format = $user->getDatePreference();
		} else {
			$format = $options['format'];
		}
		$df = $this->getDateFormatString( $type, $this->dateFormat( $format ) );
		return $this->sprintfDate( $df, $ts );
	}

	/**
	 * Get the formatted date for the given timestamp and formatted for
	 * the given user.
	 *
	 * @param mixed $ts Mixed: the time format which needs to be turned into a
	 *   date('YmdHis') format with wfTimestamp(TS_MW,$ts)
	 * @param User $user User object used to get preferences for timezone and format
	 * @param array $options Array, can contain the following keys:
	 *   - 'timecorrection': time correction, can have the following values:
	 *     - true: use user's preference
	 *     - false: don't use time correction
	 *     - int: value of time correction in minutes
	 *   - 'format': format to use, can have the following values:
	 *     - true: use user's preference
	 *     - false: use default preference
	 *     - string: format to use
	 * @since 1.19
	 * @return string
	 */
	public function userDate( $ts, User $user, array $options = [] ) {
		return $this->internalUserTimeAndDate( 'date', $ts, $user, $options );
	}

	/**
	 * Get the formatted time for the given timestamp and formatted for
	 * the given user.
	 *
	 * @param mixed $ts The time format which needs to be turned into a
	 *   date('YmdHis') format with wfTimestamp(TS_MW,$ts)
	 * @param User $user User object used to get preferences for timezone and format
	 * @param array $options Array, can contain the following keys:
	 *   - 'timecorrection': time correction, can have the following values:
	 *     - true: use user's preference
	 *     - false: don't use time correction
	 *     - int: value of time correction in minutes
	 *   - 'format': format to use, can have the following values:
	 *     - true: use user's preference
	 *     - false: use default preference
	 *     - string: format to use
	 * @since 1.19
	 * @return string
	 */
	public function userTime( $ts, User $user, array $options = [] ) {
		return $this->internalUserTimeAndDate( 'time', $ts, $user, $options );
	}

	/**
	 * Get the formatted date and time for the given timestamp and formatted for
	 * the given user.
	 *
	 * @param mixed $ts The time format which needs to be turned into a
	 *   date('YmdHis') format with wfTimestamp(TS_MW,$ts)
	 * @param User $user User object used to get preferences for timezone and format
	 * @param array $options Array, can contain the following keys:
	 *   - 'timecorrection': time correction, can have the following values:
	 *     - true: use user's preference
	 *     - false: don't use time correction
	 *     - int: value of time correction in minutes
	 *   - 'format': format to use, can have the following values:
	 *     - true: use user's preference
	 *     - false: use default preference
	 *     - string: format to use
	 * @since 1.19
	 * @return string
	 */
	public function userTimeAndDate( $ts, User $user, array $options = [] ) {
		return $this->internalUserTimeAndDate( 'both', $ts, $user, $options );
	}

	/**
	 * Get the timestamp in a human-friendly relative format, e.g., "3 days ago".
	 *
	 * Determine the difference between the timestamp and the current time, and
	 * generate a readable timestamp by returning "<N> <units> ago", where the
	 * largest possible unit is used.
	 *
	 * @since 1.26 (Prior to 1.26 method existed but was not meant to be used directly)
	 *
	 * @param MWTimestamp $time
	 * @param MWTimestamp|null $relativeTo The base timestamp to compare to (defaults to now)
	 * @param User|null $user User the timestamp is being generated for
	 *  (or null to use main context's user)
	 * @return string Formatted timestamp
	 */
	public function getHumanTimestamp(
		MWTimestamp $time, MWTimestamp $relativeTo = null, User $user = null
	) {
		if ( $relativeTo === null ) {
			$relativeTo = new MWTimestamp();
		}
		if ( $user === null ) {
			$user = RequestContext::getMain()->getUser();
		}

		// Adjust for the user's timezone.
		$offsetThis = $time->offsetForUser( $user );
		$offsetRel = $relativeTo->offsetForUser( $user );

		$ts = '';
		if ( Hooks::run( 'GetHumanTimestamp', [ &$ts, $time, $relativeTo, $user, $this ] ) ) {
			$ts = $this->getHumanTimestampInternal( $time, $relativeTo, $user );
		}

		// Reset the timezone on the objects.
		$time->timestamp->sub( $offsetThis );
		$relativeTo->timestamp->sub( $offsetRel );

		return $ts;
	}

	/**
	 * Convert an MWTimestamp into a pretty human-readable timestamp using
	 * the given user preferences and relative base time.
	 *
	 * @see Language::getHumanTimestamp
	 * @param MWTimestamp $ts Timestamp to prettify
	 * @param MWTimestamp $relativeTo Base timestamp
	 * @param User $user User preferences to use
	 * @return string Human timestamp
	 * @since 1.26
	 */
	private function getHumanTimestampInternal(
		MWTimestamp $ts, MWTimestamp $relativeTo, User $user
	) {
		$diff = $ts->diff( $relativeTo );
		$diffDay = (bool)( (int)$ts->timestamp->format( 'w' ) -
			(int)$relativeTo->timestamp->format( 'w' ) );
		$days = $diff->days ?: (int)$diffDay;
		if ( $diff->invert || $days > 5
			&& $ts->timestamp->format( 'Y' ) !== $relativeTo->timestamp->format( 'Y' )
		) {
			// Timestamps are in different years: use full timestamp
			// Also do full timestamp for future dates
			/**
			 * @todo FIXME: Add better handling of future timestamps.
			 */
			$format = $this->getDateFormatString( 'both', $user->getDatePreference() ?: 'default' );
			$ts = $this->sprintfDate( $format, $ts->getTimestamp( TS_MW ) );
		} elseif ( $days > 5 ) {
			// Timestamps are in same year,  but more than 5 days ago: show day and month only.
			$format = $this->getDateFormatString( 'pretty', $user->getDatePreference() ?: 'default' );
			$ts = $this->sprintfDate( $format, $ts->getTimestamp( TS_MW ) );
		} elseif ( $days > 1 ) {
			// Timestamp within the past week: show the day of the week and time
			$format = $this->getDateFormatString( 'time', $user->getDatePreference() ?: 'default' );
			$weekday = self::$mWeekdayMsgs[$ts->timestamp->format( 'w' )];
			// Messages:
			// sunday-at, monday-at, tuesday-at, wednesday-at, thursday-at, friday-at, saturday-at
			$ts = wfMessage( "$weekday-at" )
				->inLanguage( $this )
				->params( $this->sprintfDate( $format, $ts->getTimestamp( TS_MW ) ) )
				->text();
		} elseif ( $days == 1 ) {
			// Timestamp was yesterday: say 'yesterday' and the time.
			$format = $this->getDateFormatString( 'time', $user->getDatePreference() ?: 'default' );
			$ts = wfMessage( 'yesterday-at' )
				->inLanguage( $this )
				->params( $this->sprintfDate( $format, $ts->getTimestamp( TS_MW ) ) )
				->text();
		} elseif ( $diff->h > 1 || $diff->h == 1 && $diff->i > 30 ) {
			// Timestamp was today, but more than 90 minutes ago: say 'today' and the time.
			$format = $this->getDateFormatString( 'time', $user->getDatePreference() ?: 'default' );
			$ts = wfMessage( 'today-at' )
				->inLanguage( $this )
				->params( $this->sprintfDate( $format, $ts->getTimestamp( TS_MW ) ) )
				->text();

		// From here on in, the timestamp was soon enough ago so that we can simply say
		// XX units ago, e.g., "2 hours ago" or "5 minutes ago"
		} elseif ( $diff->h == 1 ) {
			// Less than 90 minutes, but more than an hour ago.
			$ts = wfMessage( 'hours-ago' )->inLanguage( $this )->numParams( 1 )->text();
		} elseif ( $diff->i >= 1 ) {
			// A few minutes ago.
			$ts = wfMessage( 'minutes-ago' )->inLanguage( $this )->numParams( $diff->i )->text();
		} elseif ( $diff->s >= 30 ) {
			// Less than a minute, but more than 30 sec ago.
			$ts = wfMessage( 'seconds-ago' )->inLanguage( $this )->numParams( $diff->s )->text();
		} else {
			// Less than 30 seconds ago.
			$ts = wfMessage( 'just-now' )->text();
		}

		return $ts;
	}

	/**
	 * @param string $key
	 * @return string|null
	 */
	public function getMessage( $key ) {
		return self::$dataCache->getSubitem( $this->mCode, 'messages', $key );
	}

	/**
	 * @return array
	 */
	function getAllMessages() {
		return self::$dataCache->getItem( $this->mCode, 'messages' );
	}

	/**
	 * @param string $in
	 * @param string $out
	 * @param string $string
	 * @return string
	 */
	public function iconv( $in, $out, $string ) {
		# Even with //IGNORE iconv can whine about illegal characters in
		# *input* string. We just ignore those too.
		# REF: https://bugs.php.net/bug.php?id=37166
		# REF: https://phabricator.wikimedia.org/T18885
		MediaWiki\suppressWarnings();
		$text = iconv( $in, $out . '//IGNORE', $string );
		MediaWiki\restoreWarnings();
		return $text;
	}

	// callback functions for ucwords(), ucwordbreaks()

	/**
	 * @param array $matches
	 * @return mixed|string
	 */
	function ucwordbreaksCallbackAscii( $matches ) {
		return $this->ucfirst( $matches[1] );
	}

	/**
	 * @param array $matches
	 * @return string
	 */
	function ucwordbreaksCallbackMB( $matches ) {
		return mb_strtoupper( $matches[0] );
	}

	/**
	 * @param array $matches
	 * @return string
	 */
	function ucwordsCallbackMB( $matches ) {
		return mb_strtoupper( $matches[0] );
	}

	/**
	 * Make a string's first character uppercase
	 *
	 * @param string $str
	 *
	 * @return string
	 */
	public function ucfirst( $str ) {
		$o = ord( $str );
		if ( $o < 96 ) { // if already uppercase...
			return $str;
		} elseif ( $o < 128 ) {
			return ucfirst( $str ); // use PHP's ucfirst()
		} else {
			// fall back to more complex logic in case of multibyte strings
			return $this->uc( $str, true );
		}
	}

	/**
	 * Convert a string to uppercase
	 *
	 * @param string $str
	 * @param bool $first
	 *
	 * @return string
	 */
	public function uc( $str, $first = false ) {
		if ( $first ) {
			if ( $this->isMultibyte( $str ) ) {
				return mb_strtoupper( mb_substr( $str, 0, 1 ) ) . mb_substr( $str, 1 );
			} else {
				return ucfirst( $str );
			}
		} else {
			return $this->isMultibyte( $str ) ? mb_strtoupper( $str ) : strtoupper( $str );
		}
	}

	/**
	 * @param string $str
	 * @return mixed|string
	 */
	function lcfirst( $str ) {
		$o = ord( $str );
		if ( !$o ) {
			return strval( $str );
		} elseif ( $o >= 128 ) {
			return $this->lc( $str, true );
		} elseif ( $o > 96 ) {
			return $str;
		} else {
			$str[0] = strtolower( $str[0] );
			return $str;
		}
	}

	/**
	 * @param string $str
	 * @param bool $first
	 * @return mixed|string
	 */
	function lc( $str, $first = false ) {
		if ( $first ) {
			if ( $this->isMultibyte( $str ) ) {
				return mb_strtolower( mb_substr( $str, 0, 1 ) ) . mb_substr( $str, 1 );
			} else {
				return strtolower( substr( $str, 0, 1 ) ) . substr( $str, 1 );
			}
		} else {
			return $this->isMultibyte( $str ) ? mb_strtolower( $str ) : strtolower( $str );
		}
	}

	/**
	 * @param string $str
	 * @return bool
	 */
	function isMultibyte( $str ) {
		return strlen( $str ) !== mb_strlen( $str );
	}

	/**
	 * @param string $str
	 * @return mixed|string
	 */
	function ucwords( $str ) {
		if ( $this->isMultibyte( $str ) ) {
			$str = $this->lc( $str );

			// regexp to find first letter in each word (i.e. after each space)
			$replaceRegexp = "/^([a-z]|[\\xc0-\\xff][\\x80-\\xbf]*)| ([a-z]|[\\xc0-\\xff][\\x80-\\xbf]*)/";

			// function to use to capitalize a single char
			return preg_replace_callback(
				$replaceRegexp,
				[ $this, 'ucwordsCallbackMB' ],
				$str
			);
		} else {
			return ucwords( strtolower( $str ) );
		}
	}

	/**
	 * capitalize words at word breaks
	 *
	 * @param string $str
	 * @return mixed
	 */
	function ucwordbreaks( $str ) {
		if ( $this->isMultibyte( $str ) ) {
			$str = $this->lc( $str );

			// since \b doesn't work for UTF-8, we explicitely define word break chars
			$breaks = "[ \-\(\)\}\{\.,\?!]";

			// find first letter after word break
			$replaceRegexp = "/^([a-z]|[\\xc0-\\xff][\\x80-\\xbf]*)|" .
				"$breaks([a-z]|[\\xc0-\\xff][\\x80-\\xbf]*)/";

			return preg_replace_callback(
				$replaceRegexp,
				[ $this, 'ucwordbreaksCallbackMB' ],
				$str
			);
		} else {
			return preg_replace_callback(
				'/\b([\w\x80-\xff]+)\b/',
				[ $this, 'ucwordbreaksCallbackAscii' ],
				$str
			);
		}
	}

	/**
	 * Return a case-folded representation of $s
	 *
	 * This is a representation such that caseFold($s1)==caseFold($s2) if $s1
	 * and $s2 are the same except for the case of their characters. It is not
	 * necessary for the value returned to make sense when displayed.
	 *
	 * Do *not* perform any other normalisation in this function. If a caller
	 * uses this function when it should be using a more general normalisation
	 * function, then fix the caller.
	 *
	 * @param string $s
	 *
	 * @return string
	 */
	function caseFold( $s ) {
		return $this->uc( $s );
	}

	/**
	 * @param string $s
	 * @return string
	 * @throws MWException
	 */
	function checkTitleEncoding( $s ) {
		if ( is_array( $s ) ) {
			throw new MWException( 'Given array to checkTitleEncoding.' );
		}
		if ( StringUtils::isUtf8( $s ) ) {
			return $s;
		}

		return $this->iconv( $this->fallback8bitEncoding(), 'utf-8', $s );
	}

	/**
	 * @return array
	 */
	function fallback8bitEncoding() {
		return self::$dataCache->getItem( $this->mCode, 'fallback8bitEncoding' );
	}

	/**
	 * Most writing systems use whitespace to break up words.
	 * Some languages such as Chinese don't conventionally do this,
	 * which requires special handling when breaking up words for
	 * searching etc.
	 *
	 * @return bool
	 */
	function hasWordBreaks() {
		return true;
	}

	/**
	 * Some languages such as Chinese require word segmentation,
	 * Specify such segmentation when overridden in derived class.
	 *
	 * @param string $string
	 * @return string
	 */
	function segmentByWord( $string ) {
		return $string;
	}

	/**
	 * Some languages have special punctuation need to be normalized.
	 * Make such changes here.
	 *
	 * @param string $string
	 * @return string
	 */
	function normalizeForSearch( $string ) {
		return self::convertDoubleWidth( $string );
	}

	/**
	 * convert double-width roman characters to single-width.
	 * range: ff00-ff5f ~= 0020-007f
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	protected static function convertDoubleWidth( $string ) {
		static $full = null;
		static $half = null;

		if ( $full === null ) {
			$fullWidth = "０１２３４５６７８９ＡＢＣＤＥＦＧＨＩＪＫＬＭＮＯＰＱＲＳＴＵＶＷＸＹＺａｂｃｄｅｆｇｈｉｊｋｌｍｎｏｐｑｒｓｔｕｖｗｘｙｚ";
			$halfWidth = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
			$full = str_split( $fullWidth, 3 );
			$half = str_split( $halfWidth );
		}

		$string = str_replace( $full, $half, $string );
		return $string;
	}

	/**
	 * @param string $string
	 * @param string $pattern
	 * @return string
	 */
	protected static function insertSpace( $string, $pattern ) {
		$string = preg_replace( $pattern, " $1 ", $string );
		$string = preg_replace( '/ +/', ' ', $string );
		return $string;
	}

	/**
	 * @param array $termsArray
	 * @return array
	 */
	function convertForSearchResult( $termsArray ) {
		# some languages, e.g. Chinese, need to do a conversion
		# in order for search results to be displayed correctly
		return $termsArray;
	}

	/**
	 * Get the first character of a string.
	 *
	 * @param string $s
	 * @return string
	 */
	function firstChar( $s ) {
		$matches = [];
		preg_match(
			'/^([\x00-\x7f]|[\xc0-\xdf][\x80-\xbf]|' .
				'[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xf7][\x80-\xbf]{3})/',
			$s,
			$matches
		);

		if ( isset( $matches[1] ) ) {
			if ( strlen( $matches[1] ) != 3 ) {
				return $matches[1];
			}

			// Break down Hangul syllables to grab the first jamo
			$code = UtfNormal\Utils::utf8ToCodepoint( $matches[1] );
			if ( $code < 0xac00 || 0xd7a4 <= $code ) {
				return $matches[1];
			} elseif ( $code < 0xb098 ) {
				return "\xe3\x84\xb1";
			} elseif ( $code < 0xb2e4 ) {
				return "\xe3\x84\xb4";
			} elseif ( $code < 0xb77c ) {
				return "\xe3\x84\xb7";
			} elseif ( $code < 0xb9c8 ) {
				return "\xe3\x84\xb9";
			} elseif ( $code < 0xbc14 ) {
				return "\xe3\x85\x81";
			} elseif ( $code < 0xc0ac ) {
				return "\xe3\x85\x82";
			} elseif ( $code < 0xc544 ) {
				return "\xe3\x85\x85";
			} elseif ( $code < 0xc790 ) {
				return "\xe3\x85\x87";
			} elseif ( $code < 0xcc28 ) {
				return "\xe3\x85\x88";
			} elseif ( $code < 0xce74 ) {
				return "\xe3\x85\x8a";
			} elseif ( $code < 0xd0c0 ) {
				return "\xe3\x85\x8b";
			} elseif ( $code < 0xd30c ) {
				return "\xe3\x85\x8c";
			} elseif ( $code < 0xd558 ) {
				return "\xe3\x85\x8d";
			} else {
				return "\xe3\x85\x8e";
			}
		} else {
			return '';
		}
	}

	/**
	 * @deprecated No-op since 1.28
	 */
	function initEncoding() {
		// No-op.
	}

	/**
	 * @param string $s
	 * @return string
	 * @deprecated No-op since 1.28
	 */
	function recodeForEdit( $s ) {
		return $s;
	}

	/**
	 * @param string $s
	 * @return string
	 * @deprecated No-op since 1.28
	 */
	function recodeInput( $s ) {
		return $s;
	}

	/**
	 * Convert a UTF-8 string to normal form C. In Malayalam and Arabic, this
	 * also cleans up certain backwards-compatible sequences, converting them
	 * to the modern Unicode equivalent.
	 *
	 * This is language-specific for performance reasons only.
	 *
	 * @param string $s
	 *
	 * @return string
	 */
	function normalize( $s ) {
		global $wgAllUnicodeFixes;
		$s = UtfNormal\Validator::cleanUp( $s );
		if ( $wgAllUnicodeFixes ) {
			$s = $this->transformUsingPairFile( 'normalize-ar.ser', $s );
			$s = $this->transformUsingPairFile( 'normalize-ml.ser', $s );
		}

		return $s;
	}

	/**
	 * Transform a string using serialized data stored in the given file (which
	 * must be in the serialized subdirectory of $IP). The file contains pairs
	 * mapping source characters to destination characters.
	 *
	 * The data is cached in process memory. This will go faster if you have the
	 * FastStringSearch extension.
	 *
	 * @param string $file
	 * @param string $string
	 *
	 * @throws MWException
	 * @return string
	 */
	function transformUsingPairFile( $file, $string ) {
		if ( !isset( $this->transformData[$file] ) ) {
			$data = wfGetPrecompiledData( $file );
			if ( $data === false ) {
				throw new MWException( __METHOD__ . ": The transformation file $file is missing" );
			}
			$this->transformData[$file] = new ReplacementArray( $data );
		}
		return $this->transformData[$file]->replace( $string );
	}

	/**
	 * For right-to-left language support
	 *
	 * @return bool
	 */
	function isRTL() {
		return self::$dataCache->getItem( $this->mCode, 'rtl' );
	}

	/**
	 * Return the correct HTML 'dir' attribute value for this language.
	 * @return string
	 */
	function getDir() {
		return $this->isRTL() ? 'rtl' : 'ltr';
	}

	/**
	 * Return 'left' or 'right' as appropriate alignment for line-start
	 * for this language's text direction.
	 *
	 * Should be equivalent to CSS3 'start' text-align value....
	 *
	 * @return string
	 */
	function alignStart() {
		return $this->isRTL() ? 'right' : 'left';
	}

	/**
	 * Return 'right' or 'left' as appropriate alignment for line-end
	 * for this language's text direction.
	 *
	 * Should be equivalent to CSS3 'end' text-align value....
	 *
	 * @return string
	 */
	function alignEnd() {
		return $this->isRTL() ? 'left' : 'right';
	}

	/**
	 * A hidden direction mark (LRM or RLM), depending on the language direction.
	 * Unlike getDirMark(), this function returns the character as an HTML entity.
	 * This function should be used when the output is guaranteed to be HTML,
	 * because it makes the output HTML source code more readable. When
	 * the output is plain text or can be escaped, getDirMark() should be used.
	 *
	 * @param bool $opposite Get the direction mark opposite to your language
	 * @return string
	 * @since 1.20
	 */
	function getDirMarkEntity( $opposite = false ) {
		if ( $opposite ) {
			return $this->isRTL() ? '&lrm;' : '&rlm;';
		}
		return $this->isRTL() ? '&rlm;' : '&lrm;';
	}

	/**
	 * A hidden direction mark (LRM or RLM), depending on the language direction.
	 * This function produces them as invisible Unicode characters and
	 * the output may be hard to read and debug, so it should only be used
	 * when the output is plain text or can be escaped. When the output is
	 * HTML, use getDirMarkEntity() instead.
	 *
	 * @param bool $opposite Get the direction mark opposite to your language
	 * @return string
	 */
	function getDirMark( $opposite = false ) {
		$lrm = "\xE2\x80\x8E"; # LEFT-TO-RIGHT MARK, commonly abbreviated LRM
		$rlm = "\xE2\x80\x8F"; # RIGHT-TO-LEFT MARK, commonly abbreviated RLM
		if ( $opposite ) {
			return $this->isRTL() ? $lrm : $rlm;
		}
		return $this->isRTL() ? $rlm : $lrm;
	}

	/**
	 * @return array
	 */
	function capitalizeAllNouns() {
		return self::$dataCache->getItem( $this->mCode, 'capitalizeAllNouns' );
	}

	/**
	 * An arrow, depending on the language direction.
	 *
	 * @param string $direction The direction of the arrow: forwards (default),
	 *   backwards, left, right, up, down.
	 * @return string
	 */
	function getArrow( $direction = 'forwards' ) {
		switch ( $direction ) {
		case 'forwards':
			return $this->isRTL() ? '←' : '→';
		case 'backwards':
			return $this->isRTL() ? '→' : '←';
		case 'left':
			return '←';
		case 'right':
			return '→';
		case 'up':
			return '↑';
		case 'down':
			return '↓';
		}
	}

	/**
	 * To allow "foo[[bar]]" to extend the link over the whole word "foobar"
	 *
	 * @return bool
	 */
	function linkPrefixExtension() {
		return self::$dataCache->getItem( $this->mCode, 'linkPrefixExtension' );
	}

	/**
	 * Get all magic words from cache.
	 * @return array
	 */
	function getMagicWords() {
		return self::$dataCache->getItem( $this->mCode, 'magicWords' );
	}

	/**
	 * Run the LanguageGetMagic hook once.
	 */
	protected function doMagicHook() {
		if ( $this->mMagicHookDone ) {
			return;
		}
		$this->mMagicHookDone = true;
		Hooks::run( 'LanguageGetMagic', [ &$this->mMagicExtensions, $this->getCode() ] );
	}

	/**
	 * Fill a MagicWord object with data from here
	 *
	 * @param MagicWord $mw
	 */
	function getMagic( $mw ) {
		// Saves a function call
		if ( !$this->mMagicHookDone ) {
			$this->doMagicHook();
		}

		if ( isset( $this->mMagicExtensions[$mw->mId] ) ) {
			$rawEntry = $this->mMagicExtensions[$mw->mId];
		} else {
			$rawEntry = self::$dataCache->getSubitem(
				$this->mCode, 'magicWords', $mw->mId );
		}

		if ( !is_array( $rawEntry ) ) {
			wfWarn( "\"$rawEntry\" is not a valid magic word for \"$mw->mId\"" );
		} else {
			$mw->mCaseSensitive = $rawEntry[0];
			$mw->mSynonyms = array_slice( $rawEntry, 1 );
		}
	}

	/**
	 * Add magic words to the extension array
	 *
	 * @param array $newWords
	 */
	function addMagicWordsByLang( $newWords ) {
		$fallbackChain = $this->getFallbackLanguages();
		$fallbackChain = array_reverse( $fallbackChain );
		foreach ( $fallbackChain as $code ) {
			if ( isset( $newWords[$code] ) ) {
				$this->mMagicExtensions = $newWords[$code] + $this->mMagicExtensions;
			}
		}
	}

	/**
	 * Get special page names, as an associative array
	 *   canonical name => array of valid names, including aliases
	 * @return array
	 */
	function getSpecialPageAliases() {
		// Cache aliases because it may be slow to load them
		if ( is_null( $this->mExtendedSpecialPageAliases ) ) {
			// Initialise array
			$this->mExtendedSpecialPageAliases =
				self::$dataCache->getItem( $this->mCode, 'specialPageAliases' );
			Hooks::run( 'LanguageGetSpecialPageAliases',
				[ &$this->mExtendedSpecialPageAliases, $this->getCode() ] );
		}

		return $this->mExtendedSpecialPageAliases;
	}

	/**
	 * Italic is unsuitable for some languages
	 *
	 * @param string $text The text to be emphasized.
	 * @return string
	 */
	function emphasize( $text ) {
		return "<em>$text</em>";
	}

	/**
	 * Normally we output all numbers in plain en_US style, that is
	 * 293,291.235 for twohundredninetythreethousand-twohundredninetyone
	 * point twohundredthirtyfive. However this is not suitable for all
	 * languages, some such as Bengali (bn) want ২,৯৩,২৯১.২৩৫ and others such as
	 * Icelandic just want to use commas instead of dots, and dots instead
	 * of commas like "293.291,235".
	 *
	 * An example of this function being called:
	 * <code>
	 * wfMessage( 'message' )->numParams( $num )->text()
	 * </code>
	 *
	 * See $separatorTransformTable on MessageIs.php for
	 * the , => . and . => , implementation.
	 *
	 * @todo check if it's viable to use localeconv() for the decimal separator thing.
	 * @param int|float $number The string to be formatted, should be an integer
	 *   or a floating point number.
	 * @param bool $nocommafy Set to true for special numbers like dates
	 * @return string
	 */
	public function formatNum( $number, $nocommafy = false ) {
		global $wgTranslateNumerals;
		if ( !$nocommafy ) {
			$number = $this->commafy( $number );
			$s = $this->separatorTransformTable();
			if ( $s ) {
				$number = strtr( $number, $s );
			}
		}

		if ( $wgTranslateNumerals ) {
			$s = $this->digitTransformTable();
			if ( $s ) {
				$number = strtr( $number, $s );
			}
		}

		return $number;
	}

	/**
	 * Front-end for non-commafied formatNum
	 *
	 * @param int|float $number The string to be formatted, should be an integer
	 *        or a floating point number.
	 * @since 1.21
	 * @return string
	 */
	public function formatNumNoSeparators( $number ) {
		return $this->formatNum( $number, true );
	}

	/**
	 * @param string $number
	 * @return string
	 */
	public function parseFormattedNumber( $number ) {
		$s = $this->digitTransformTable();
		if ( $s ) {
			// eliminate empty array values such as ''. (T66347)
			$s = array_filter( $s );
			$number = strtr( $number, array_flip( $s ) );
		}

		$s = $this->separatorTransformTable();
		if ( $s ) {
			// eliminate empty array values such as ''. (T66347)
			$s = array_filter( $s );
			$number = strtr( $number, array_flip( $s ) );
		}

		$number = strtr( $number, [ ',' => '' ] );
		return $number;
	}

	/**
	 * Adds commas to a given number
	 * @since 1.19
	 * @param mixed $number
	 * @return string
	 */
	function commafy( $number ) {
		$digitGroupingPattern = $this->digitGroupingPattern();
		if ( $number === null ) {
			return '';
		}

		if ( !$digitGroupingPattern || $digitGroupingPattern === "###,###,###" ) {
			// default grouping is at thousands,  use the same for ###,###,### pattern too.
			return strrev( (string)preg_replace( '/(\d{3})(?=\d)(?!\d*\.)/', '$1,', strrev( $number ) ) );
		} else {
			// Ref: http://cldr.unicode.org/translation/number-patterns
			$sign = "";
			if ( intval( $number ) < 0 ) {
				// For negative numbers apply the algorithm like positive number and add sign.
				$sign = "-";
				$number = substr( $number, 1 );
			}
			$integerPart = [];
			$decimalPart = [];
			$numMatches = preg_match_all( "/(#+)/", $digitGroupingPattern, $matches );
			preg_match( "/\d+/", $number, $integerPart );
			preg_match( "/\.\d*/", $number, $decimalPart );
			$groupedNumber = ( count( $decimalPart ) > 0 ) ? $decimalPart[0] : "";
			if ( $groupedNumber === $number ) {
				// the string does not have any number part. Eg: .12345
				return $sign . $groupedNumber;
			}
			$start = $end = ( $integerPart ) ? strlen( $integerPart[0] ) : 0;
			while ( $start > 0 ) {
				$match = $matches[0][$numMatches - 1];
				$matchLen = strlen( $match );
				$start = $end - $matchLen;
				if ( $start < 0 ) {
					$start = 0;
				}
				$groupedNumber = substr( $number, $start, $end -$start ) . $groupedNumber;
				$end = $start;
				if ( $numMatches > 1 ) {
					// use the last pattern for the rest of the number
					$numMatches--;
				}
				if ( $start > 0 ) {
					$groupedNumber = "," . $groupedNumber;
				}
			}
			return $sign . $groupedNumber;
		}
	}

	/**
	 * @return string
	 */
	function digitGroupingPattern() {
		return self::$dataCache->getItem( $this->mCode, 'digitGroupingPattern' );
	}

	/**
	 * @return array
	 */
	function digitTransformTable() {
		return self::$dataCache->getItem( $this->mCode, 'digitTransformTable' );
	}

	/**
	 * @return array
	 */
	function separatorTransformTable() {
		return self::$dataCache->getItem( $this->mCode, 'separatorTransformTable' );
	}

	/**
	 * Take a list of strings and build a locale-friendly comma-separated
	 * list, using the local comma-separator message.
	 * The last two strings are chained with an "and".
	 * NOTE: This function will only work with standard numeric array keys (0, 1, 2…)
	 *
	 * @param string[] $l
	 * @return string
	 */
	function listToText( array $l ) {
		$m = count( $l ) - 1;
		if ( $m < 0 ) {
			return '';
		}
		if ( $m > 0 ) {
			$and = $this->msg( 'and' )->escaped();
			$space = $this->msg( 'word-separator' )->escaped();
			if ( $m > 1 ) {
				$comma = $this->msg( 'comma-separator' )->escaped();
			}
		}
		$s = $l[$m];
		for ( $i = $m - 1; $i >= 0; $i-- ) {
			if ( $i == $m - 1 ) {
				$s = $l[$i] . $and . $space . $s;
			} else {
				$s = $l[$i] . $comma . $s;
			}
		}
		return $s;
	}

	/**
	 * Take a list of strings and build a locale-friendly comma-separated
	 * list, using the local comma-separator message.
	 * @param string[] $list Array of strings to put in a comma list
	 * @return string
	 */
	function commaList( array $list ) {
		return implode(
			wfMessage( 'comma-separator' )->inLanguage( $this )->escaped(),
			$list
		);
	}

	/**
	 * Take a list of strings and build a locale-friendly semicolon-separated
	 * list, using the local semicolon-separator message.
	 * @param string[] $list Array of strings to put in a semicolon list
	 * @return string
	 */
	function semicolonList( array $list ) {
		return implode(
			wfMessage( 'semicolon-separator' )->inLanguage( $this )->escaped(),
			$list
		);
	}

	/**
	 * Same as commaList, but separate it with the pipe instead.
	 * @param string[] $list Array of strings to put in a pipe list
	 * @return string
	 */
	function pipeList( array $list ) {
		return implode(
			wfMessage( 'pipe-separator' )->inLanguage( $this )->escaped(),
			$list
		);
	}

	/**
	 * Truncate a string to a specified length in bytes, appending an optional
	 * string (e.g. for ellipses)
	 *
	 * The database offers limited byte lengths for some columns in the database;
	 * multi-byte character sets mean we need to ensure that only whole characters
	 * are included, otherwise broken characters can be passed to the user
	 *
	 * If $length is negative, the string will be truncated from the beginning
	 *
	 * @param string $string String to truncate
	 * @param int $length Maximum length (including ellipses)
	 * @param string $ellipsis String to append to the truncated text
	 * @param bool $adjustLength Subtract length of ellipsis from $length.
	 *	$adjustLength was introduced in 1.18, before that behaved as if false.
	 * @return string
	 */
	function truncate( $string, $length, $ellipsis = '...', $adjustLength = true ) {
		# Use the localized ellipsis character
		if ( $ellipsis == '...' ) {
			$ellipsis = wfMessage( 'ellipsis' )->inLanguage( $this )->escaped();
		}
		# Check if there is no need to truncate
		if ( $length == 0 ) {
			return $ellipsis; // convention
		} elseif ( strlen( $string ) <= abs( $length ) ) {
			return $string; // no need to truncate
		}
		$stringOriginal = $string;
		# If ellipsis length is >= $length then we can't apply $adjustLength
		if ( $adjustLength && strlen( $ellipsis ) >= abs( $length ) ) {
			$string = $ellipsis; // this can be slightly unexpected
		# Otherwise, truncate and add ellipsis...
		} else {
			$eLength = $adjustLength ? strlen( $ellipsis ) : 0;
			if ( $length > 0 ) {
				$length -= $eLength;
				$string = substr( $string, 0, $length ); // xyz...
				$string = $this->removeBadCharLast( $string );
				$string = rtrim( $string );
				$string = $string . $ellipsis;
			} else {
				$length += $eLength;
				$string = substr( $string, $length ); // ...xyz
				$string = $this->removeBadCharFirst( $string );
				$string = ltrim( $string );
				$string = $ellipsis . $string;
			}
		}
		# Do not truncate if the ellipsis makes the string longer/equal (T24181).
		# This check is *not* redundant if $adjustLength, due to the single case where
		# LEN($ellipsis) > ABS($limit arg); $stringOriginal could be shorter than $string.
		if ( strlen( $string ) < strlen( $stringOriginal ) ) {
			return $string;
		} else {
			return $stringOriginal;
		}
	}

	/**
	 * Remove bytes that represent an incomplete Unicode character
	 * at the end of string (e.g. bytes of the char are missing)
	 *
	 * @param string $string
	 * @return string
	 */
	protected function removeBadCharLast( $string ) {
		if ( $string != '' ) {
			$char = ord( $string[strlen( $string ) - 1] );
			$m = [];
			if ( $char >= 0xc0 ) {
				# We got the first byte only of a multibyte char; remove it.
				$string = substr( $string, 0, -1 );
			} elseif ( $char >= 0x80 &&
				// Use the /s modifier (PCRE_DOTALL) so (.*) also matches newlines
				preg_match( '/^(.*)(?:[\xe0-\xef][\x80-\xbf]|' .
					'[\xf0-\xf7][\x80-\xbf]{1,2})$/s', $string, $m )
			) {
				# We chopped in the middle of a character; remove it
				$string = $m[1];
			}
		}
		return $string;
	}

	/**
	 * Remove bytes that represent an incomplete Unicode character
	 * at the start of string (e.g. bytes of the char are missing)
	 *
	 * @param string $string
	 * @return string
	 */
	protected function removeBadCharFirst( $string ) {
		if ( $string != '' ) {
			$char = ord( $string[0] );
			if ( $char >= 0x80 && $char < 0xc0 ) {
				# We chopped in the middle of a character; remove the whole thing
				$string = preg_replace( '/^[\x80-\xbf]+/', '', $string );
			}
		}
		return $string;
	}

	/**
	 * Truncate a string of valid HTML to a specified length in bytes,
	 * appending an optional string (e.g. for ellipses), and return valid HTML
	 *
	 * This is only intended for styled/linked text, such as HTML with
	 * tags like <span> and <a>, were the tags are self-contained (valid HTML).
	 * Also, this will not detect things like "display:none" CSS.
	 *
	 * Note: since 1.18 you do not need to leave extra room in $length for ellipses.
	 *
	 * @param string $text HTML string to truncate
	 * @param int $length (zero/positive) Maximum length (including ellipses)
	 * @param string $ellipsis String to append to the truncated text
	 * @return string
	 */
	function truncateHtml( $text, $length, $ellipsis = '...' ) {
		# Use the localized ellipsis character
		if ( $ellipsis == '...' ) {
			$ellipsis = wfMessage( 'ellipsis' )->inLanguage( $this )->escaped();
		}
		# Check if there is clearly no need to truncate
		if ( $length <= 0 ) {
			return $ellipsis; // no text shown, nothing to format (convention)
		} elseif ( strlen( $text ) <= $length ) {
			return $text; // string short enough even *with* HTML (short-circuit)
		}

		$dispLen = 0; // innerHTML legth so far
		$testingEllipsis = false; // checking if ellipses will make string longer/equal?
		$tagType = 0; // 0-open, 1-close
		$bracketState = 0; // 1-tag start, 2-tag name, 0-neither
		$entityState = 0; // 0-not entity, 1-entity
		$tag = $ret = ''; // accumulated tag name, accumulated result string
		$openTags = []; // open tag stack
		$maybeState = null; // possible truncation state

		$textLen = strlen( $text );
		$neLength = max( 0, $length - strlen( $ellipsis ) ); // non-ellipsis len if truncated
		for ( $pos = 0; true; ++$pos ) {
			# Consider truncation once the display length has reached the maximim.
			# We check if $dispLen > 0 to grab tags for the $neLength = 0 case.
			# Check that we're not in the middle of a bracket/entity...
			if ( $dispLen && $dispLen >= $neLength && $bracketState == 0 && !$entityState ) {
				if ( !$testingEllipsis ) {
					$testingEllipsis = true;
					# Save where we are; we will truncate here unless there turn out to
					# be so few remaining characters that truncation is not necessary.
					if ( !$maybeState ) { // already saved? ($neLength = 0 case)
						$maybeState = [ $ret, $openTags ]; // save state
					}
				} elseif ( $dispLen > $length && $dispLen > strlen( $ellipsis ) ) {
					# String in fact does need truncation, the truncation point was OK.
					list( $ret, $openTags ) = $maybeState; // reload state
					$ret = $this->removeBadCharLast( $ret ); // multi-byte char fix
					$ret .= $ellipsis; // add ellipsis
					break;
				}
			}
			if ( $pos >= $textLen ) {
				break; // extra iteration just for above checks
			}

			# Read the next char...
			$ch = $text[$pos];
			$lastCh = $pos ? $text[$pos - 1] : '';
			$ret .= $ch; // add to result string
			if ( $ch == '<' ) {
				$this->truncate_endBracket( $tag, $tagType, $lastCh, $openTags ); // for bad HTML
				$entityState = 0; // for bad HTML
				$bracketState = 1; // tag started (checking for backslash)
			} elseif ( $ch == '>' ) {
				$this->truncate_endBracket( $tag, $tagType, $lastCh, $openTags );
				$entityState = 0; // for bad HTML
				$bracketState = 0; // out of brackets
			} elseif ( $bracketState == 1 ) {
				if ( $ch == '/' ) {
					$tagType = 1; // close tag (e.g. "</span>")
				} else {
					$tagType = 0; // open tag (e.g. "<span>")
					$tag .= $ch;
				}
				$bracketState = 2; // building tag name
			} elseif ( $bracketState == 2 ) {
				if ( $ch != ' ' ) {
					$tag .= $ch;
				} else {
					// Name found (e.g. "<a href=..."), add on tag attributes...
					$pos += $this->truncate_skip( $ret, $text, "<>", $pos + 1 );
				}
			} elseif ( $bracketState == 0 ) {
				if ( $entityState ) {
					if ( $ch == ';' ) {
						$entityState = 0;
						$dispLen++; // entity is one displayed char
					}
				} else {
					if ( $neLength == 0 && !$maybeState ) {
						// Save state without $ch. We want to *hit* the first
						// display char (to get tags) but not *use* it if truncating.
						$maybeState = [ substr( $ret, 0, -1 ), $openTags ];
					}
					if ( $ch == '&' ) {
						$entityState = 1; // entity found, (e.g. "&#160;")
					} else {
						$dispLen++; // this char is displayed
						// Add the next $max display text chars after this in one swoop...
						$max = ( $testingEllipsis ? $length : $neLength ) - $dispLen;
						$skipped = $this->truncate_skip( $ret, $text, "<>&", $pos + 1, $max );
						$dispLen += $skipped;
						$pos += $skipped;
					}
				}
			}
		}
		// Close the last tag if left unclosed by bad HTML
		$this->truncate_endBracket( $tag, $text[$textLen - 1], $tagType, $openTags );
		while ( count( $openTags ) > 0 ) {
			$ret .= '</' . array_pop( $openTags ) . '>'; // close open tags
		}
		return $ret;
	}

	/**
	 * truncateHtml() helper function
	 * like strcspn() but adds the skipped chars to $ret
	 *
	 * @param string $ret
	 * @param string $text
	 * @param string $search
	 * @param int $start
	 * @param null|int $len
	 * @return int
	 */
	private function truncate_skip( &$ret, $text, $search, $start, $len = null ) {
		if ( $len === null ) {
			$len = -1; // -1 means "no limit" for strcspn
		} elseif ( $len < 0 ) {
			$len = 0; // sanity
		}
		$skipCount = 0;
		if ( $start < strlen( $text ) ) {
			$skipCount = strcspn( $text, $search, $start, $len );
			$ret .= substr( $text, $start, $skipCount );
		}
		return $skipCount;
	}

	/**
	 * truncateHtml() helper function
	 * (a) push or pop $tag from $openTags as needed
	 * (b) clear $tag value
	 * @param string &$tag Current HTML tag name we are looking at
	 * @param int $tagType (0-open tag, 1-close tag)
	 * @param string $lastCh Character before the '>' that ended this tag
	 * @param array &$openTags Open tag stack (not accounting for $tag)
	 */
	private function truncate_endBracket( &$tag, $tagType, $lastCh, &$openTags ) {
		$tag = ltrim( $tag );
		if ( $tag != '' ) {
			if ( $tagType == 0 && $lastCh != '/' ) {
				$openTags[] = $tag; // tag opened (didn't close itself)
			} elseif ( $tagType == 1 ) {
				if ( $openTags && $tag == $openTags[count( $openTags ) - 1] ) {
					array_pop( $openTags ); // tag closed
				}
			}
			$tag = '';
		}
	}

	/**
	 * Grammatical transformations, needed for inflected languages
	 * Invoked by putting {{grammar:case|word}} in a message
	 *
	 * @param string $word
	 * @param string $case
	 * @return string
	 */
	function convertGrammar( $word, $case ) {
		global $wgGrammarForms;
		if ( isset( $wgGrammarForms[$this->getCode()][$case][$word] ) ) {
			return $wgGrammarForms[$this->getCode()][$case][$word];
		}

		$grammarTransformations = $this->getGrammarTransformations();

		if ( isset( $grammarTransformations[$case] ) ) {
			$forms = $grammarTransformations[$case];

			// Some names of grammar rules are aliases for other rules.
			// In such cases the value is a string rather than object,
			// so load the actual rules.
			if ( is_string( $forms ) ) {
				$forms = $grammarTransformations[$forms];
			}

			foreach ( array_values( $forms ) as $rule ) {
				$form = $rule[0];

				if ( $form === '@metadata' ) {
					continue;
				}

				$replacement = $rule[1];

				$regex = '/' . addcslashes( $form, '/' ) . '/u';
				$patternMatches = preg_match( $regex, $word );

				if ( $patternMatches === false ) {
					wfLogWarning(
						'An error occurred while processing grammar. ' .
						"Word: '$word'. Regex: /$form/."
					);
				} elseif ( $patternMatches === 1 ) {
					$word = preg_replace( $regex, $replacement, $word );

					break;
				}
			}
		}

		return $word;
	}

	/**
	 * Get the grammar forms for the content language
	 * @return array Array of grammar forms
	 * @since 1.20
	 */
	function getGrammarForms() {
		global $wgGrammarForms;
		if ( isset( $wgGrammarForms[$this->getCode()] )
			&& is_array( $wgGrammarForms[$this->getCode()] )
		) {
			return $wgGrammarForms[$this->getCode()];
		}

		return [];
	}

	/**
	 * Get the grammar transformations data for the language.
	 * Used like grammar forms, with {{GRAMMAR}} and cases,
	 * but uses pairs of regexes and replacements instead of code.
	 *
	 * @return array[] Array of grammar transformations.
	 * @throws MWException
	 * @since 1.28
	 */
	public function getGrammarTransformations() {
		$languageCode = $this->getCode();

		if ( self::$grammarTransformations === null ) {
			self::$grammarTransformations = new MapCacheLRU( 10 );
		}

		if ( self::$grammarTransformations->has( $languageCode ) ) {
			return self::$grammarTransformations->get( $languageCode );
		}

		$data = [];

		$grammarDataFile = __DIR__ . "/data/grammarTransformations/$languageCode.json";
		if ( is_readable( $grammarDataFile ) ) {
			$data = FormatJson::decode(
				file_get_contents( $grammarDataFile ),
				true
			);

			if ( $data === null ) {
				throw new MWException( "Invalid grammar data for \"$languageCode\"." );
			}

			self::$grammarTransformations->set( $languageCode, $data );
		}

		return $data;
	}

	/**
	 * Provides an alternative text depending on specified gender.
	 * Usage {{gender:username|masculine|feminine|unknown}}.
	 * username is optional, in which case the gender of current user is used,
	 * but only in (some) interface messages; otherwise default gender is used.
	 *
	 * If no forms are given, an empty string is returned. If only one form is
	 * given, it will be returned unconditionally. These details are implied by
	 * the caller and cannot be overridden in subclasses.
	 *
	 * If three forms are given, the default is to use the third (unknown) form.
	 * If fewer than three forms are given, the default is to use the first (masculine) form.
	 * These details can be overridden in subclasses.
	 *
	 * @param string $gender
	 * @param array $forms
	 *
	 * @return string
	 */
	function gender( $gender, $forms ) {
		if ( !count( $forms ) ) {
			return '';
		}
		$forms = $this->preConvertPlural( $forms, 2 );
		if ( $gender === 'male' ) {
			return $forms[0];
		}
		if ( $gender === 'female' ) {
			return $forms[1];
		}
		return isset( $forms[2] ) ? $forms[2] : $forms[0];
	}

	/**
	 * Plural form transformations, needed for some languages.
	 * For example, there are 3 form of plural in Russian and Polish,
	 * depending on "count mod 10". See [[w:Plural]]
	 * For English it is pretty simple.
	 *
	 * Invoked by putting {{plural:count|wordform1|wordform2}}
	 * or {{plural:count|wordform1|wordform2|wordform3}}
	 *
	 * Example: {{plural:{{NUMBEROFARTICLES}}|article|articles}}
	 *
	 * @param int $count Non-localized number
	 * @param array $forms Different plural forms
	 * @return string Correct form of plural for $count in this language
	 */
	function convertPlural( $count, $forms ) {
		// Handle explicit n=pluralform cases
		$forms = $this->handleExplicitPluralForms( $count, $forms );
		if ( is_string( $forms ) ) {
			return $forms;
		}
		if ( !count( $forms ) ) {
			return '';
		}

		$pluralForm = $this->getPluralRuleIndexNumber( $count );
		$pluralForm = min( $pluralForm, count( $forms ) - 1 );
		return $forms[$pluralForm];
	}

	/**
	 * Handles explicit plural forms for Language::convertPlural()
	 *
	 * In {{PLURAL:$1|0=nothing|one|many}}, 0=nothing will be returned if $1 equals zero.
	 * If an explicitly defined plural form matches the $count, then
	 * string value returned, otherwise array returned for further consideration
	 * by CLDR rules or overridden convertPlural().
	 *
	 * @since 1.23
	 *
	 * @param int $count Non-localized number
	 * @param array $forms Different plural forms
	 *
	 * @return array|string
	 */
	protected function handleExplicitPluralForms( $count, array $forms ) {
		foreach ( $forms as $index => $form ) {
			if ( preg_match( '/\d+=/i', $form ) ) {
				$pos = strpos( $form, '=' );
				if ( substr( $form, 0, $pos ) === (string)$count ) {
					return substr( $form, $pos + 1 );
				}
				unset( $forms[$index] );
			}
		}
		return array_values( $forms );
	}

	/**
	 * Checks that convertPlural was given an array and pads it to requested
	 * amount of forms by copying the last one.
	 *
	 * @param array $forms Array of forms given to convertPlural
	 * @param int $count How many forms should there be at least
	 * @return array Padded array of forms or an exception if not an array
	 */
	protected function preConvertPlural( /* Array */ $forms, $count ) {
		while ( count( $forms ) < $count ) {
			$forms[] = $forms[count( $forms ) - 1];
		}
		return $forms;
	}

	/**
	 * Wraps argument with unicode control characters for directionality safety
	 *
	 * This solves the problem where directionality-neutral characters at the edge of
	 * the argument string get interpreted with the wrong directionality from the
	 * enclosing context, giving renderings that look corrupted like "(Ben_(WMF".
	 *
	 * The wrapping is LRE...PDF or RLE...PDF, depending on the detected
	 * directionality of the argument string, using the BIDI algorithm's own "First
	 * strong directional codepoint" rule. Essentially, this works round the fact that
	 * there is no embedding equivalent of U+2068 FSI (isolation with heuristic
	 * direction inference). The latter is cleaner but still not widely supported.
	 *
	 * @param string $text Text to wrap
	 * @return string Text, wrapped in LRE...PDF or RLE...PDF or nothing
	 */
	public function embedBidi( $text = '' ) {
		$dir = Language::strongDirFromContent( $text );
		if ( $dir === 'ltr' ) {
			// Wrap in LEFT-TO-RIGHT EMBEDDING ... POP DIRECTIONAL FORMATTING
			return self::$lre . $text . self::$pdf;
		}
		if ( $dir === 'rtl' ) {
			// Wrap in RIGHT-TO-LEFT EMBEDDING ... POP DIRECTIONAL FORMATTING
			return self::$rle . $text . self::$pdf;
		}
		// No strong directionality: do not wrap
		return $text;
	}

	/**
	 * @todo Maybe translate block durations.  Note that this function is somewhat misnamed: it
	 * deals with translating the *duration* ("1 week", "4 days", etc), not the expiry time
	 * (which is an absolute timestamp). Please note: do NOT add this blindly, as it is used
	 * on old expiry lengths recorded in log entries. You'd need to provide the start date to
	 * match up with it.
	 *
	 * @param string $str The validated block duration in English
	 * @param User $user User object to use timezone from or null for $wgUser
	 * @param int $now Current timestamp, for formatting relative block durations
	 * @return string Somehow translated block duration
	 * @see LanguageFi.php for example implementation
	 */
	function translateBlockExpiry( $str, User $user = null, $now = 0 ) {
		$duration = SpecialBlock::getSuggestedDurations( $this );
		foreach ( $duration as $show => $value ) {
			if ( strcmp( $str, $value ) == 0 ) {
				return htmlspecialchars( trim( $show ) );
			}
		}

		if ( wfIsInfinity( $str ) ) {
			foreach ( $duration as $show => $value ) {
				if ( wfIsInfinity( $value ) ) {
					return htmlspecialchars( trim( $show ) );
				}
			}
		}

		// If all else fails, return a standard duration or timestamp description.
		$time = strtotime( $str, $now );
		if ( $time === false ) { // Unknown format. Return it as-is in case.
			return $str;
		} elseif ( $time !== strtotime( $str, $now + 1 ) ) { // It's a relative timestamp.
			// The result differs based on current time, so the difference
			// is a fixed duration length.
			return $this->formatDuration( $time - $now );
		} else { // It's an absolute timestamp.
			if ( $time === 0 ) {
				// wfTimestamp() handles 0 as current time instead of epoch.
				$time = '19700101000000';
			}
			if ( $user ) {
				return $this->userTimeAndDate( $time, $user );
			}
			return $this->timeanddate( $time );
		}
	}

	/**
	 * languages like Chinese need to be segmented in order for the diff
	 * to be of any use
	 *
	 * @param string $text
	 * @return string
	 */
	public function segmentForDiff( $text ) {
		return $text;
	}

	/**
	 * and unsegment to show the result
	 *
	 * @param string $text
	 * @return string
	 */
	public function unsegmentForDiff( $text ) {
		return $text;
	}

	/**
	 * Return the LanguageConverter used in the Language
	 *
	 * @since 1.19
	 * @return LanguageConverter
	 */
	public function getConverter() {
		return $this->mConverter;
	}

	/**
	 * convert text to all supported variants
	 *
	 * @param string $text
	 * @return array
	 */
	public function autoConvertToAllVariants( $text ) {
		return $this->mConverter->autoConvertToAllVariants( $text );
	}

	/**
	 * convert text to different variants of a language.
	 *
	 * @param string $text
	 * @return string
	 */
	public function convert( $text ) {
		return $this->mConverter->convert( $text );
	}

	/**
	 * Convert a Title object to a string in the preferred variant
	 *
	 * @param Title $title
	 * @return string
	 */
	public function convertTitle( $title ) {
		return $this->mConverter->convertTitle( $title );
	}

	/**
	 * Convert a namespace index to a string in the preferred variant
	 *
	 * @param int $ns
	 * @return string
	 */
	public function convertNamespace( $ns ) {
		return $this->mConverter->convertNamespace( $ns );
	}

	/**
	 * Check if this is a language with variants
	 *
	 * @return bool
	 */
	public function hasVariants() {
		return count( $this->getVariants() ) > 1;
	}

	/**
	 * Check if the language has the specific variant
	 *
	 * @since 1.19
	 * @param string $variant
	 * @return bool
	 */
	public function hasVariant( $variant ) {
		return (bool)$this->mConverter->validateVariant( $variant );
	}

	/**
	 * Perform output conversion on a string, and encode for safe HTML output.
	 * @param string $text Text to be converted
	 * @param bool $isTitle Whether this conversion is for the article title
	 * @return string
	 * @todo this should get integrated somewhere sane
	 */
	public function convertHtml( $text, $isTitle = false ) {
		return htmlspecialchars( $this->convert( $text, $isTitle ) );
	}

	/**
	 * @param string $key
	 * @return string
	 */
	public function convertCategoryKey( $key ) {
		return $this->mConverter->convertCategoryKey( $key );
	}

	/**
	 * Get the list of variants supported by this language
	 * see sample implementation in LanguageZh.php
	 *
	 * @return string[] An array of language codes
	 */
	public function getVariants() {
		return $this->mConverter->getVariants();
	}

	/**
	 * @return string
	 */
	public function getPreferredVariant() {
		return $this->mConverter->getPreferredVariant();
	}

	/**
	 * @return string
	 */
	public function getDefaultVariant() {
		return $this->mConverter->getDefaultVariant();
	}

	/**
	 * @return string
	 */
	public function getURLVariant() {
		return $this->mConverter->getURLVariant();
	}

	/**
	 * If a language supports multiple variants, it is
	 * possible that non-existing link in one variant
	 * actually exists in another variant. this function
	 * tries to find it. See e.g. LanguageZh.php
	 * The input parameters may be modified upon return
	 *
	 * @param string &$link The name of the link
	 * @param Title &$nt The title object of the link
	 * @param bool $ignoreOtherCond To disable other conditions when
	 *   we need to transclude a template or update a category's link
	 */
	public function findVariantLink( &$link, &$nt, $ignoreOtherCond = false ) {
		$this->mConverter->findVariantLink( $link, $nt, $ignoreOtherCond );
	}

	/**
	 * returns language specific options used by User::getPageRenderHash()
	 * for example, the preferred language variant
	 *
	 * @return string
	 */
	function getExtraHashOptions() {
		return $this->mConverter->getExtraHashOptions();
	}

	/**
	 * For languages that support multiple variants, the title of an
	 * article may be displayed differently in different variants. this
	 * function returns the apporiate title defined in the body of the article.
	 *
	 * @return string
	 */
	public function getParsedTitle() {
		return $this->mConverter->getParsedTitle();
	}

	/**
	 * Refresh the cache of conversion tables when
	 * MediaWiki:Conversiontable* is updated.
	 *
	 * @param Title $title The Title of the page being updated
	 */
	public function updateConversionTable( Title $title ) {
		$this->mConverter->updateConversionTable( $title );
	}

	/**
	 * Prepare external link text for conversion. When the text is
	 * a URL, it shouldn't be converted, and it'll be wrapped in
	 * the "raw" tag (-{R| }-) to prevent conversion.
	 *
	 * This function is called "markNoConversion" for historical
	 * reasons.
	 *
	 * @param string $text Text to be used for external link
	 * @param bool $noParse Wrap it without confirming it's a real URL first
	 * @return string The tagged text
	 */
	public function markNoConversion( $text, $noParse = false ) {
		// Excluding protocal-relative URLs may avoid many false positives.
		if ( $noParse || preg_match( '/^(?:' . wfUrlProtocolsWithoutProtRel() . ')/', $text ) ) {
			return $this->mConverter->markNoConversion( $text );
		} else {
			return $text;
		}
	}

	/**
	 * A regular expression to match legal word-trailing characters
	 * which should be merged onto a link of the form [[foo]]bar.
	 *
	 * @return string
	 */
	public function linkTrail() {
		return self::$dataCache->getItem( $this->mCode, 'linkTrail' );
	}

	/**
	 * A regular expression character set to match legal word-prefixing
	 * characters which should be merged onto a link of the form foo[[bar]].
	 *
	 * @return string
	 */
	public function linkPrefixCharset() {
		return self::$dataCache->getItem( $this->mCode, 'linkPrefixCharset' );
	}

	/**
	 * Get the "parent" language which has a converter to convert a "compatible" language
	 * (in another variant) to this language (eg. zh for zh-cn, but not en for en-gb).
	 *
	 * @return Language|null
	 * @since 1.22
	 */
	public function getParentLanguage() {
		if ( $this->mParentLanguage !== false ) {
			return $this->mParentLanguage;
		}

		$code = explode( '-', $this->getCode() )[0];
		if ( !in_array( $code, LanguageConverter::$languagesWithVariants ) ) {
			$this->mParentLanguage = null;
			return null;
		}
		$lang = Language::factory( $code );
		if ( !$lang->hasVariant( $this->getCode() ) ) {
			$this->mParentLanguage = null;
			return null;
		}

		$this->mParentLanguage = $lang;
		return $lang;
	}

	/**
	 * Compare with an other language object
	 *
	 * @since 1.28
	 * @param Language $lang
	 * @return boolean
	 */
	public function equals( Language $lang ) {
		return $lang->getCode() === $this->mCode;
	}

	/**
	 * Get the internal language code for this language object
	 *
	 * NOTE: The return value of this function is NOT HTML-safe and must be escaped with
	 * htmlspecialchars() or similar
	 *
	 * @return string
	 */
	public function getCode() {
		return $this->mCode;
	}

	/**
	 * Get the code in BCP 47 format which we can use
	 * inside of html lang="" tags.
	 *
	 * NOTE: The return value of this function is NOT HTML-safe and must be escaped with
	 * htmlspecialchars() or similar.
	 *
	 * @since 1.19
	 * @return string
	 */
	public function getHtmlCode() {
		if ( is_null( $this->mHtmlCode ) ) {
			$this->mHtmlCode = wfBCP47( $this->getCode() );
		}
		return $this->mHtmlCode;
	}

	/**
	 * @param string $code
	 */
	public function setCode( $code ) {
		$this->mCode = $code;
		// Ensure we don't leave incorrect cached data lying around
		$this->mHtmlCode = null;
		$this->mParentLanguage = false;
	}

	/**
	 * Get the language code from a file name. Inverse of getFileName()
	 * @param string $filename $prefix . $languageCode . $suffix
	 * @param string $prefix Prefix before the language code
	 * @param string $suffix Suffix after the language code
	 * @return string Language code, or false if $prefix or $suffix isn't found
	 */
	public static function getCodeFromFileName( $filename, $prefix = 'Language', $suffix = '.php' ) {
		$m = null;
		preg_match( '/' . preg_quote( $prefix, '/' ) . '([A-Z][a-z_]+)' .
			preg_quote( $suffix, '/' ) . '/', $filename, $m );
		if ( !count( $m ) ) {
			return false;
		}
		return str_replace( '_', '-', strtolower( $m[1] ) );
	}

	/**
	 * @param string $code
	 * @return string Name of the language class
	 */
	public static function classFromCode( $code ) {
		if ( $code == 'en' ) {
			return 'Language';
		} else {
			return 'Language' . str_replace( '-', '_', ucfirst( $code ) );
		}
	}

	/**
	 * Get the name of a file for a certain language code
	 * @param string $prefix Prepend this to the filename
	 * @param string $code Language code
	 * @param string $suffix Append this to the filename
	 * @throws MWException
	 * @return string $prefix . $mangledCode . $suffix
	 */
	public static function getFileName( $prefix = 'Language', $code, $suffix = '.php' ) {
		if ( !self::isValidBuiltInCode( $code ) ) {
			throw new MWException( "Invalid language code \"$code\"" );
		}

		return $prefix . str_replace( '-', '_', ucfirst( $code ) ) . $suffix;
	}

	/**
	 * @param string $code
	 * @return string
	 */
	public static function getMessagesFileName( $code ) {
		global $IP;
		$file = self::getFileName( "$IP/languages/messages/Messages", $code, '.php' );
		Hooks::run( 'Language::getMessagesFileName', [ $code, &$file ] );
		return $file;
	}

	/**
	 * @param string $code
	 * @return string
	 * @throws MWException
	 * @since 1.23
	 */
	public static function getJsonMessagesFileName( $code ) {
		global $IP;

		if ( !self::isValidBuiltInCode( $code ) ) {
			throw new MWException( "Invalid language code \"$code\"" );
		}

		return "$IP/languages/i18n/$code.json";
	}

	/**
	 * Get the first fallback for a given language.
	 *
	 * @param string $code
	 *
	 * @return bool|string
	 */
	public static function getFallbackFor( $code ) {
		$fallbacks = self::getFallbacksFor( $code );
		if ( $fallbacks ) {
			return $fallbacks[0];
		}
		return false;
	}

	/**
	 * Get the ordered list of fallback languages.
	 *
	 * @since 1.19
	 * @param string $code Language code
	 * @return array Non-empty array, ending in "en"
	 */
	public static function getFallbacksFor( $code ) {
		if ( $code === 'en' || !Language::isValidBuiltInCode( $code ) ) {
			return [];
		}
		// For unknown languages, fallbackSequence returns an empty array,
		// hardcode fallback to 'en' in that case.
		return self::getLocalisationCache()->getItem( $code, 'fallbackSequence' ) ?: [ 'en' ];
	}

	/**
	 * Get the ordered list of fallback languages, ending with the fallback
	 * language chain for the site language.
	 *
	 * @since 1.22
	 * @param string $code Language code
	 * @return array Array( fallbacks, site fallbacks )
	 */
	public static function getFallbacksIncludingSiteLanguage( $code ) {
		global $wgLanguageCode;

		// Usually, we will only store a tiny number of fallback chains, so we
		// keep them in static memory.
		$cacheKey = "{$code}-{$wgLanguageCode}";

		if ( !array_key_exists( $cacheKey, self::$fallbackLanguageCache ) ) {
			$fallbacks = self::getFallbacksFor( $code );

			// Append the site's fallback chain, including the site language itself
			$siteFallbacks = self::getFallbacksFor( $wgLanguageCode );
			array_unshift( $siteFallbacks, $wgLanguageCode );

			// Eliminate any languages already included in the chain
			$siteFallbacks = array_diff( $siteFallbacks, $fallbacks );

			self::$fallbackLanguageCache[$cacheKey] = [ $fallbacks, $siteFallbacks ];
		}
		return self::$fallbackLanguageCache[$cacheKey];
	}

	/**
	 * Get all messages for a given language
	 * WARNING: this may take a long time. If you just need all message *keys*
	 * but need the *contents* of only a few messages, consider using getMessageKeysFor().
	 *
	 * @param string $code
	 *
	 * @return array
	 */
	public static function getMessagesFor( $code ) {
		return self::getLocalisationCache()->getItem( $code, 'messages' );
	}

	/**
	 * Get a message for a given language
	 *
	 * @param string $key
	 * @param string $code
	 *
	 * @return string
	 */
	public static function getMessageFor( $key, $code ) {
		return self::getLocalisationCache()->getSubitem( $code, 'messages', $key );
	}

	/**
	 * Get all message keys for a given language. This is a faster alternative to
	 * array_keys( Language::getMessagesFor( $code ) )
	 *
	 * @since 1.19
	 * @param string $code Language code
	 * @return array Array of message keys (strings)
	 */
	public static function getMessageKeysFor( $code ) {
		return self::getLocalisationCache()->getSubitemList( $code, 'messages' );
	}

	/**
	 * @param string $talk
	 * @return mixed
	 */
	function fixVariableInNamespace( $talk ) {
		if ( strpos( $talk, '$1' ) === false ) {
			return $talk;
		}

		global $wgMetaNamespace;
		$talk = str_replace( '$1', $wgMetaNamespace, $talk );

		# Allow grammar transformations
		# Allowing full message-style parsing would make simple requests
		# such as action=raw much more expensive than they need to be.
		# This will hopefully cover most cases.
		$talk = preg_replace_callback( '/{{grammar:(.*?)\|(.*?)}}/i',
			[ $this, 'replaceGrammarInNamespace' ], $talk );
		return str_replace( ' ', '_', $talk );
	}

	/**
	 * @param string $m
	 * @return string
	 */
	function replaceGrammarInNamespace( $m ) {
		return $this->convertGrammar( trim( $m[2] ), trim( $m[1] ) );
	}

	/**
	 * Decode an expiry (block, protection, etc) which has come from the DB
	 *
	 * @param string $expiry Database expiry String
	 * @param bool|int $format True to process using language functions, or TS_ constant
	 *     to return the expiry in a given timestamp
	 * @param string $infinity If $format is not true, use this string for infinite expiry
	 * @return string
	 * @since 1.18
	 */
	public function formatExpiry( $expiry, $format = true, $infinity = 'infinity' ) {
		static $dbInfinity;
		if ( $dbInfinity === null ) {
			$dbInfinity = wfGetDB( DB_SLAVE )->getInfinity();
		}

		if ( $expiry == '' || $expiry === 'infinity' || $expiry == $dbInfinity ) {
			return $format === true
				? $this->getMessageFromDB( 'infiniteblock' )
				: $infinity;
		} else {
			return $format === true
				? $this->timeanddate( $expiry, /* User preference timezone */ true )
				: wfTimestamp( $format, $expiry );
		}
	}

	/**
	 * Formats a time given in seconds into a string representation of that time.
	 *
	 * @param int|float $seconds
	 * @param array $format An optional argument that formats the returned string in different ways:
	 *   If $format['avoid'] === 'avoidseconds': don't show seconds if $seconds >= 1 hour,
	 *   If $format['avoid'] === 'avoidminutes': don't show seconds/minutes if $seconds > 48 hours,
	 *   If $format['noabbrevs'] is true: use 'seconds' and friends instead of 'seconds-abbrev'
	 *     and friends.
	 * @note For backwards compatibility, $format may also be one of the strings 'avoidseconds'
	 *     or 'avoidminutes'.
	 * @return string
	 */
	function formatTimePeriod( $seconds, $format = [] ) {
		if ( !is_array( $format ) ) {
			$format = [ 'avoid' => $format ]; // For backwards compatibility
		}
		if ( !isset( $format['avoid'] ) ) {
			$format['avoid'] = false;
		}
		if ( !isset( $format['noabbrevs'] ) ) {
			$format['noabbrevs'] = false;
		}
		$secondsMsg = wfMessage(
			$format['noabbrevs'] ? 'seconds' : 'seconds-abbrev' )->inLanguage( $this );
		$minutesMsg = wfMessage(
			$format['noabbrevs'] ? 'minutes' : 'minutes-abbrev' )->inLanguage( $this );
		$hoursMsg = wfMessage(
			$format['noabbrevs'] ? 'hours' : 'hours-abbrev' )->inLanguage( $this );
		$daysMsg = wfMessage(
			$format['noabbrevs'] ? 'days' : 'days-abbrev' )->inLanguage( $this );

		if ( round( $seconds * 10 ) < 100 ) {
			$s = $this->formatNum( sprintf( "%.1f", round( $seconds * 10 ) / 10 ) );
			$s = $secondsMsg->params( $s )->text();
		} elseif ( round( $seconds ) < 60 ) {
			$s = $this->formatNum( round( $seconds ) );
			$s = $secondsMsg->params( $s )->text();
		} elseif ( round( $seconds ) < 3600 ) {
			$minutes = floor( $seconds / 60 );
			$secondsPart = round( fmod( $seconds, 60 ) );
			if ( $secondsPart == 60 ) {
				$secondsPart = 0;
				$minutes++;
			}
			$s = $minutesMsg->params( $this->formatNum( $minutes ) )->text();
			$s .= ' ';
			$s .= $secondsMsg->params( $this->formatNum( $secondsPart ) )->text();
		} elseif ( round( $seconds ) <= 2 * 86400 ) {
			$hours = floor( $seconds / 3600 );
			$minutes = floor( ( $seconds - $hours * 3600 ) / 60 );
			$secondsPart = round( $seconds - $hours * 3600 - $minutes * 60 );
			if ( $secondsPart == 60 ) {
				$secondsPart = 0;
				$minutes++;
			}
			if ( $minutes == 60 ) {
				$minutes = 0;
				$hours++;
			}
			$s = $hoursMsg->params( $this->formatNum( $hours ) )->text();
			$s .= ' ';
			$s .= $minutesMsg->params( $this->formatNum( $minutes ) )->text();
			if ( !in_array( $format['avoid'], [ 'avoidseconds', 'avoidminutes' ] ) ) {
				$s .= ' ' . $secondsMsg->params( $this->formatNum( $secondsPart ) )->text();
			}
		} else {
			$days = floor( $seconds / 86400 );
			if ( $format['avoid'] === 'avoidminutes' ) {
				$hours = round( ( $seconds - $days * 86400 ) / 3600 );
				if ( $hours == 24 ) {
					$hours = 0;
					$days++;
				}
				$s = $daysMsg->params( $this->formatNum( $days ) )->text();
				$s .= ' ';
				$s .= $hoursMsg->params( $this->formatNum( $hours ) )->text();
			} elseif ( $format['avoid'] === 'avoidseconds' ) {
				$hours = floor( ( $seconds - $days * 86400 ) / 3600 );
				$minutes = round( ( $seconds - $days * 86400 - $hours * 3600 ) / 60 );
				if ( $minutes == 60 ) {
					$minutes = 0;
					$hours++;
				}
				if ( $hours == 24 ) {
					$hours = 0;
					$days++;
				}
				$s = $daysMsg->params( $this->formatNum( $days ) )->text();
				$s .= ' ';
				$s .= $hoursMsg->params( $this->formatNum( $hours ) )->text();
				$s .= ' ';
				$s .= $minutesMsg->params( $this->formatNum( $minutes ) )->text();
			} else {
				$s = $daysMsg->params( $this->formatNum( $days ) )->text();
				$s .= ' ';
				$s .= $this->formatTimePeriod( $seconds - $days * 86400, $format );
			}
		}
		return $s;
	}

	/**
	 * Format a bitrate for output, using an appropriate
	 * unit (bps, kbps, Mbps, Gbps, Tbps, Pbps, Ebps, Zbps or Ybps) according to
	 *   the magnitude in question.
	 *
	 * This use base 1000. For base 1024 use formatSize(), for another base
	 * see formatComputingNumbers().
	 *
	 * @param int $bps
	 * @return string
	 */
	function formatBitrate( $bps ) {
		return $this->formatComputingNumbers( $bps, 1000, "bitrate-$1bits" );
	}

	/**
	 * @param int $size Size of the unit
	 * @param int $boundary Size boundary (1000, or 1024 in most cases)
	 * @param string $messageKey Message key to be uesd
	 * @return string
	 */
	function formatComputingNumbers( $size, $boundary, $messageKey ) {
		if ( $size <= 0 ) {
			return str_replace( '$1', $this->formatNum( $size ),
				$this->getMessageFromDB( str_replace( '$1', '', $messageKey ) )
			);
		}
		$sizes = [ '', 'kilo', 'mega', 'giga', 'tera', 'peta', 'exa', 'zeta', 'yotta' ];
		$index = 0;

		$maxIndex = count( $sizes ) - 1;
		while ( $size >= $boundary && $index < $maxIndex ) {
			$index++;
			$size /= $boundary;
		}

		// For small sizes no decimal places necessary
		$round = 0;
		if ( $index > 1 ) {
			// For MB and bigger two decimal places are smarter
			$round = 2;
		}
		$msg = str_replace( '$1', $sizes[$index], $messageKey );

		$size = round( $size, $round );
		$text = $this->getMessageFromDB( $msg );
		return str_replace( '$1', $this->formatNum( $size ), $text );
	}

	/**
	 * Format a size in bytes for output, using an appropriate
	 * unit (B, KB, MB, GB, TB, PB, EB, ZB or YB) according to the magnitude in question
	 *
	 * This method use base 1024. For base 1000 use formatBitrate(), for
	 * another base see formatComputingNumbers()
	 *
	 * @param int $size Size to format
	 * @return string Plain text (not HTML)
	 */
	function formatSize( $size ) {
		return $this->formatComputingNumbers( $size, 1024, "size-$1bytes" );
	}

	/**
	 * Make a list item, used by various special pages
	 *
	 * @param string $page Page link
	 * @param string $details HTML safe text between brackets
	 * @param bool $oppositedm Add the direction mark opposite to your
	 *   language, to display text properly
	 * @return HTML escaped string
	 */
	function specialList( $page, $details, $oppositedm = true ) {
		if ( !$details ) {
			return $page;
		}

		$dirmark = ( $oppositedm ? $this->getDirMark( true ) : '' ) . $this->getDirMark();
		return
			$page .
			$dirmark .
			$this->msg( 'word-separator' )->escaped() .
			$this->msg( 'parentheses' )->rawParams( $details )->escaped();
	}

	/**
	 * Generate (prev x| next x) (20|50|100...) type links for paging
	 *
	 * @param Title $title Title object to link
	 * @param int $offset
	 * @param int $limit
	 * @param array $query Optional URL query parameter string
	 * @param bool $atend Optional param for specified if this is the last page
	 * @return string
	 */
	public function viewPrevNext( Title $title, $offset, $limit,
		array $query = [], $atend = false
	) {
		// @todo FIXME: Why on earth this needs one message for the text and another one for tooltip?

		# Make 'previous' link
		$prev = wfMessage( 'prevn' )->inLanguage( $this )->title( $title )->numParams( $limit )->text();
		if ( $offset > 0 ) {
			$plink = $this->numLink( $title, max( $offset - $limit, 0 ), $limit,
				$query, $prev, 'prevn-title', 'mw-prevlink' );
		} else {
			$plink = htmlspecialchars( $prev );
		}

		# Make 'next' link
		$next = wfMessage( 'nextn' )->inLanguage( $this )->title( $title )->numParams( $limit )->text();
		if ( $atend ) {
			$nlink = htmlspecialchars( $next );
		} else {
			$nlink = $this->numLink( $title, $offset + $limit, $limit,
				$query, $next, 'nextn-title', 'mw-nextlink' );
		}

		# Make links to set number of items per page
		$numLinks = [];
		foreach ( [ 20, 50, 100, 250, 500 ] as $num ) {
			$numLinks[] = $this->numLink( $title, $offset, $num,
				$query, $this->formatNum( $num ), 'shown-title', 'mw-numlink' );
		}

		return wfMessage( 'viewprevnext' )->inLanguage( $this )->title( $title
			)->rawParams( $plink, $nlink, $this->pipeList( $numLinks ) )->escaped();
	}

	/**
	 * Helper function for viewPrevNext() that generates links
	 *
	 * @param Title $title Title object to link
	 * @param int $offset
	 * @param int $limit
	 * @param array $query Extra query parameters
	 * @param string $link Text to use for the link; will be escaped
	 * @param string $tooltipMsg Name of the message to use as tooltip
	 * @param string $class Value of the "class" attribute of the link
	 * @return string HTML fragment
	 */
	private function numLink( Title $title, $offset, $limit, array $query, $link,
		$tooltipMsg, $class
	) {
		$query = [ 'limit' => $limit, 'offset' => $offset ] + $query;
		$tooltip = wfMessage( $tooltipMsg )->inLanguage( $this )->title( $title )
			->numParams( $limit )->text();

		return Html::element( 'a', [ 'href' => $title->getLocalURL( $query ),
			'title' => $tooltip, 'class' => $class ], $link );
	}

	/**
	 * Get the conversion rule title, if any.
	 *
	 * @return string
	 */
	public function getConvRuleTitle() {
		return $this->mConverter->getConvRuleTitle();
	}

	/**
	 * Get the compiled plural rules for the language
	 * @since 1.20
	 * @return array Associative array with plural form, and plural rule as key-value pairs
	 */
	public function getCompiledPluralRules() {
		$pluralRules = self::$dataCache->getItem( strtolower( $this->mCode ), 'compiledPluralRules' );
		$fallbacks = Language::getFallbacksFor( $this->mCode );
		if ( !$pluralRules ) {
			foreach ( $fallbacks as $fallbackCode ) {
				$pluralRules = self::$dataCache->getItem( strtolower( $fallbackCode ), 'compiledPluralRules' );
				if ( $pluralRules ) {
					break;
				}
			}
		}
		return $pluralRules;
	}

	/**
	 * Get the plural rules for the language
	 * @since 1.20
	 * @return array Associative array with plural form number and plural rule as key-value pairs
	 */
	public function getPluralRules() {
		$pluralRules = self::$dataCache->getItem( strtolower( $this->mCode ), 'pluralRules' );
		$fallbacks = Language::getFallbacksFor( $this->mCode );
		if ( !$pluralRules ) {
			foreach ( $fallbacks as $fallbackCode ) {
				$pluralRules = self::$dataCache->getItem( strtolower( $fallbackCode ), 'pluralRules' );
				if ( $pluralRules ) {
					break;
				}
			}
		}
		return $pluralRules;
	}

	/**
	 * Get the plural rule types for the language
	 * @since 1.22
	 * @return array Associative array with plural form number and plural rule type as key-value pairs
	 */
	public function getPluralRuleTypes() {
		$pluralRuleTypes = self::$dataCache->getItem( strtolower( $this->mCode ), 'pluralRuleTypes' );
		$fallbacks = Language::getFallbacksFor( $this->mCode );
		if ( !$pluralRuleTypes ) {
			foreach ( $fallbacks as $fallbackCode ) {
				$pluralRuleTypes = self::$dataCache->getItem( strtolower( $fallbackCode ), 'pluralRuleTypes' );
				if ( $pluralRuleTypes ) {
					break;
				}
			}
		}
		return $pluralRuleTypes;
	}

	/**
	 * Find the index number of the plural rule appropriate for the given number
	 * @param int $number
	 * @return int The index number of the plural rule
	 */
	public function getPluralRuleIndexNumber( $number ) {
		$pluralRules = $this->getCompiledPluralRules();
		$form = Evaluator::evaluateCompiled( $number, $pluralRules );
		return $form;
	}

	/**
	 * Find the plural rule type appropriate for the given number
	 * For example, if the language is set to Arabic, getPluralType(5) should
	 * return 'few'.
	 * @since 1.22
	 * @param int $number
	 * @return string The name of the plural rule type, e.g. one, two, few, many
	 */
	public function getPluralRuleType( $number ) {
		$index = $this->getPluralRuleIndexNumber( $number );
		$pluralRuleTypes = $this->getPluralRuleTypes();
		if ( isset( $pluralRuleTypes[$index] ) ) {
			return $pluralRuleTypes[$index];
		} else {
			return 'other';
		}
	}
}
