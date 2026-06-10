<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace Wikimedia\Tests\Leximorph\Handler;

use Generator;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Wikimedia\Leximorph\Handler\Grammar;
use Wikimedia\Leximorph\Handler\Overrides\GrammarFallbackRegistry;
use Wikimedia\Leximorph\Provider;

/**
 * This test class verifies the functionality of the {@see Grammar} handler.
 * It tests that the class correctly processes grammatical transformations
 * based on language-specific rules and transformation mappings.
 *
 * Covered tests include:
 *   - Correct transformation of words based on grammatical cases.
 *   - Handling of cases where no transformation is found.
 *   - Loading grammar rules from JSON files.
 *
 * @covers \Wikimedia\Leximorph\Handler\Grammar
 * @author Doğu Abaris (abaris@null.net)
 */
class GrammarTest extends TestCase {

	/**
	 * Each test case provides:
	 *  - A language code.
	 *  - A word to transform.
	 *  - A grammar case.
	 *  - The expected output.
	 *
	 * @return Generator<array{string, string, string, string}>
	 */
	public static function provideGrammarCases(): Generator {
		yield 'Bosnian Instrumental' => [ 'bs', 'word', 'instrumental', 's word' ];
		yield 'Bosnian Lokativ' => [ 'bs', 'word', 'lokativ', 'o word' ];
		yield 'Bosnian Nominativ' => [ 'bs', 'word', 'nominativ', 'word' ];
		yield 'Old Church Slavonic Genitive Wikipedia' => [ 'cu', 'Википї', 'genitive', 'Википї' ];
		yield 'Lower Sorbian (Dolnoserbski) Nominative' => [ 'dsb', 'word', 'nominatiw', 'word' ];
		yield 'Lower Sorbian (Dolnoserbski) Instrumental' => [ 'dsb', 'word', 'instrumental', 'wo z word' ];
		yield 'Lower Sorbian (Dolnoserbski) Locative' => [ 'dsb', 'word', 'lokatiw', 'wo word' ];
		yield 'Finnish (Suomi) Genitive' => [ 'fi', 'talo', 'genitive', 'talon' ];
		yield 'Finnish (Suomi) Elative' => [ 'fi', 'talo', 'elative', 'talosta' ];
		yield 'Finnish (Suomi) Partitive' => [ 'fi', 'talo', 'partitive', 'taloa' ];
		yield 'Finnish (Suomi) Illative' => [ 'fi', 'talo', 'illative', 'taloon' ];
		yield 'Finnish (Suomi) Inessive' => [ 'fi', 'talo', 'inessive', 'talossa' ];
		yield 'Finnish (Suomi) Partitive pastöroitu' => [ 'fi', 'pastöroitu', 'partitive', 'pastöroitua' ];
		yield 'Finnish (Suomi) Elative Wikipedia' => [ 'fi', 'Wikipedia', 'elative', 'Wikipediasta' ];
		yield 'Finnish (Suomi) Partitive Wikipedia' => [ 'fi', 'Wikipedia', 'partitive', 'Wikipediaa' ];
		yield 'Irish (Gaeilge) Ainmlae Domhnach' => [ 'ga', 'an Domhnach', 'ainmlae', 'Dé Domhnaigh' ];
		yield 'Irish (Gaeilge) Ainmlae Luan' => [ 'ga', 'an Luan', 'ainmlae', 'Dé Luain' ];
		yield 'Irish (Gaeilge) Ainmlae Máirt' => [ 'ga', 'an Mháirt', 'ainmlae', 'Dé Mháirt' ];
		yield 'Irish (Gaeilge) Ainmlae Céadaoin' => [ 'ga', 'an Chéadaoin', 'ainmlae', 'Dé Chéadaoin' ];
		yield 'Irish (Gaeilge) Ainmlae Déardaoin' => [ 'ga', 'an Déardaoin', 'ainmlae', 'Déardaoin' ];
		yield 'Irish (Gaeilge) Ainmlae Aoine' => [ 'ga', 'an Aoine', 'ainmlae', 'Dé hAoine' ];
		yield 'Irish (Gaeilge) Ainmlae Satharn' => [ 'ga', 'an Satharn', 'ainmlae', 'Dé Sathairn' ];
		yield 'Irish (Gaeilge) Other Domhnach' => [ 'ga', 'an Domhnach', 'other', 'an Domhnach' ];
		yield 'Hebrew (עברית) Prefixed Wikipedia' => [ 'he', 'ויקיפדיה', 'תחילית', 'וויקיפדיה' ];
		yield 'Hebrew (עברית) Prefixed Wolfgang' => [ 'he', 'וולפגנג', 'prefixed', 'וולפגנג' ];
		yield 'Hebrew (עברית) Prefixed File' => [ 'he', 'הקובץ', 'תחילית', 'קובץ' ];
		yield 'Hebrew (עברית) Prefixed Wikipedia Latin' => [ 'he', 'Wikipedia', 'תחילית', '־Wikipedia' ];
		yield 'Hebrew (עברית) Prefixed Year' => [ 'he', '1995', 'תחילית', '־1995' ];
		yield 'Upper Sorbian (Hornjoserbšćina) Nominative' => [ 'hsb', 'word', 'nominatiw', 'word' ];
		yield 'Upper Sorbian (Hornjoserbšćina) Instrumental' => [ 'hsb', 'word', 'instrumental', 'z word' ];
		yield 'Upper Sorbian (Hornjoserbšćina) Locative' => [ 'hsb', 'word', 'lokatiw', 'wo word' ];
		yield 'Hungarian (Magyar) Delative' => [ 'hu', 'kocsmafal', 'rol', 'kocsmafalról' ];
		yield 'Hungarian (Magyar) Illative' => [ 'hu', 'kocsmafal', 'ba', 'kocsmafalba' ];
		yield 'Hungarian (Magyar) Plural' => [ 'hu', 'Bevezető', 'k', 'Bevezetők' ];
		yield 'Armenian (Հայերեն) Genitive Mauna' => [ 'hy', 'Մաունա', 'genitive', 'Մաունայի' ];
		yield 'Armenian (Հայերեն) Genitive Heto' => [ 'hy', 'հետո', 'genitive', 'հետոյի' ];
		yield 'Armenian (Հայերեն) Genitive Book' => [ 'hy', 'գիրք', 'genitive', 'գրքի' ];
		yield 'Armenian (Հայերեն) Genitive Time' => [ 'hy', 'ժամանակի', 'genitive', 'ժամանակիի' ];
		yield 'Armenian (Հայերեն) Dative Mauna' => [ 'hy', 'Մաունա', 'dative', 'Մաունա' ];
		yield 'Georgian (ქართული) Genitive Wikipedia' => [ 'ka', 'ვიკიპედია', 'ნათესაობითი', 'ვიკიპედიის' ];
		yield 'Georgian (ქართული) Genitive Wiktionary' => [ 'ka', 'ვიქსიკონი', 'ნათესაობითი', 'ვიქსიკონის' ];
		yield 'Georgian (ქართული) Genitive Wikibooks' => [ 'ka', 'ვიკიწიგნები', 'ნათესაობითი', 'ვიკიწიგნების' ];
		yield 'Georgian (ქართული) Genitive Wikiquote' => [ 'ka', 'ვიკიციტატა', 'ნათესაობითი', 'ვიკიციტატის' ];
		yield 'Georgian (ქართული) Genitive Wikinews' => [ 'ka', 'ვიკისიახლეები', 'ნათესაობითი', 'ვიკისიახლეების' ];
		yield 'Georgian (ქართული) Genitive Wikispecies' => [ 'ka', 'ვიკისახეობები', 'ნათესაობითი', 'ვიკისახეობების' ];
		yield 'Georgian (ქართული) Genitive Wikidata' => [ 'ka', 'ვიკიმონაცემები', 'ნათესაობითი', 'ვიკიმონაცემების' ];
		yield 'Georgian (ქართული) Genitive Commons' => [ 'ka', 'ვიკისაწყობი', 'ნათესაობითი', 'ვიკისაწყობის' ];
		yield 'Georgian (ქართული) Genitive Wikivoyage' => [ 'ka', 'ვიკივოიაჟი', 'ნათესაობითი', 'ვიკივოიაჟის' ];
		yield 'Georgian (ქართული) Genitive Meta-Wiki' => [ 'ka', 'მეტა-ვიკი', 'ნათესაობითი', 'მეტა-ვიკის' ];
		yield 'Georgian (ქართული) Genitive MediaWiki' => [ 'ka', 'მედიავიკი', 'ნათესაობითი', 'მედიავიკის' ];
		yield 'Georgian (ქართული) Genitive Wikiversity' => [ 'ka', 'ვიკივერსიტეტი', 'ნათესაობითი', 'ვიკივერსიტეტის' ];
		yield 'Georgian (ქართული) Genitive Freedom' => [ 'ka', 'თავისუფლება', 'ნათესაობითი', 'თავისუფლების' ];
		yield 'Kazakh (Қазақша) Ablative Wikipedia' => [ 'kk-cyrl', 'Уикипедия', 'ablative', 'Уикипедияден' ];
		yield 'Kazakh (Қазақша) Ablative Wiktionary' => [ 'kk-cyrl', 'Уикисөздік', 'ablative', 'Уикисөздіктен' ];
		yield 'Kazakh (Қазақша) Ablative Wikibooks' => [ 'kk-cyrl', 'Уикикітап', 'ablative', 'Уикикітаптан' ];
		yield 'Latin (Lingua Latina) Genitive translatio' => [ 'la', 'translatio', 'genitive', 'translationis' ];
		yield 'Latin (Lingua Latina) Accusative translatio' => [ 'la', 'translatio', 'accusative', 'translationem' ];
		yield 'Latin (Lingua Latina) Ablative translatio' => [ 'la', 'translatio', 'ablative', 'translatione' ];
		yield 'Latin (Lingua Latina) Genitive ursus' => [ 'la', 'ursus', 'genitive', 'ursi' ];
		yield 'Latin (Lingua Latina) Accusative ursus' => [ 'la', 'ursus', 'accusative', 'ursum' ];
		yield 'Latin (Lingua Latina) Ablative ursus' => [ 'la', 'ursus', 'ablative', 'urso' ];
		yield 'Latin (Lingua Latina) Genitive gens' => [ 'la', 'gens', 'genitive', 'gentis' ];
		yield 'Latin (Lingua Latina) Accusative gens' => [ 'la', 'gens', 'accusative', 'gentem' ];
		yield 'Latin (Lingua Latina) Ablative gens' => [ 'la', 'gens', 'ablative', 'gente' ];
		yield 'Latin (Lingua Latina) Genitive bellum' => [ 'la', 'bellum', 'genitive', 'belli' ];
		yield 'Latin (Lingua Latina) Accusative bellum' => [ 'la', 'bellum', 'accusative', 'bellum' ];
		yield 'Latin (Lingua Latina) Ablative bellum' => [ 'la', 'bellum', 'ablative', 'bello' ];
		yield 'Latin (Lingua Latina) Genitive communia' => [ 'la', 'communia', 'genitive', 'communium' ];
		yield 'Latin (Lingua Latina) Accusative communia' => [ 'la', 'communia', 'accusative', 'communia' ];
		yield 'Latin (Lingua Latina) Ablative communia' => [ 'la', 'communia', 'ablative', 'communibus' ];
		yield 'Latin (Lingua Latina) Genitive libri' => [ 'la', 'libri', 'genitive', 'librorum' ];
		yield 'Latin (Lingua Latina) Accusative libri' => [ 'la', 'libri', 'accusative', 'libros' ];
		yield 'Latin (Lingua Latina) Ablative libri' => [ 'la', 'libri', 'ablative', 'libris' ];
		yield 'Latin (Lingua Latina) Genitive dies' => [ 'la', 'dies', 'genitive', 'diei' ];
		yield 'Latin (Lingua Latina) Accusative dies' => [ 'la', 'dies', 'accusative', 'diem' ];
		yield 'Latin (Lingua Latina) Ablative dies' => [ 'la', 'dies', 'ablative', 'die' ];
		yield 'Latin (Lingua Latina) Genitive declinatio' => [ 'la', 'declinatio', 'genitive', 'declinationis' ];
		yield 'Latin (Lingua Latina) Accusative declinatio' => [ 'la', 'declinatio', 'accusative', 'declinationem' ];
		yield 'Latin (Lingua Latina) Ablative declinatio' => [ 'la', 'declinatio', 'ablative', 'declinatione' ];
		yield 'Latin (Lingua Latina) Genitive vanitas' => [ 'la', 'vanitas', 'genitive', 'vanitatis' ];
		yield 'Latin (Lingua Latina) Accusative vanitas' => [ 'la', 'vanitas', 'accusative', 'vanitatem' ];
		yield 'Latin (Lingua Latina) Ablative vanitas' => [ 'la', 'vanitas', 'ablative', 'vanitate' ];
		yield 'Mongolian (Монгол) Genitive Wikipedia' => [ 'mn', 'Википедиа', 'genitive', 'Википедиагийн' ];
		yield 'Mongolian (Монгол) Genitive Wiktionary' => [ 'mn', 'Викитоль', 'genitive', 'Викитолийн' ];
		yield 'Ossetian (Ирон) Genitive бæстæ' => [ 'os', 'бæстæ', 'genitive', 'бæсты' ];
		yield 'Ossetian (Ирон) Allative бæстæ' => [ 'os', 'бæстæ', 'allative', 'бæстæм' ];
		yield 'Ossetian (Ирон) Dative бæстæ' => [ 'os', 'бæстæ', 'dative', 'бæстæн' ];
		yield 'Ossetian (Ирон) Ablative бæстæ' => [ 'os', 'бæстæ', 'ablative', 'бæстæй' ];
		yield 'Ossetian (Ирон) Inessive бæстæ' => [ 'os', 'бæстæ', 'inessive', 'бæст' ];
		yield 'Ossetian (Ирон) Superessive бæстæ' => [ 'os', 'бæстæ', 'superessive', 'бæстыл' ];
		yield 'Ossetian (Ирон) Genitive лæппу' => [ 'os', 'лæппу', 'genitive', 'лæппуйы' ];
		yield 'Ossetian (Ирон) Allative лæппу' => [ 'os', 'лæппу', 'allative', 'лæппумæ' ];
		yield 'Ossetian (Ирон) Dative лæппу' => [ 'os', 'лæппу', 'dative', 'лæппуйæн' ];
		yield 'Ossetian (Ирон) Ablative лæппу' => [ 'os', 'лæппу', 'ablative', 'лæппуйæ' ];
		yield 'Ossetian (Ирон) Inessive лæппу' => [ 'os', 'лæппу', 'inessive', 'лæппу' ];
		yield 'Ossetian (Ирон) Superessive лæппу' => [ 'os', 'лæппу', 'superessive', 'лæппуйыл' ];
		yield 'Ossetian (Ирон) Equative 2011' => [ 'os', '2011', 'equative', '2011-ау' ];
		yield 'Russian Wikipedia Genitive' => [ 'ru', 'Википедия', 'genitive', 'Википедии' ];
		yield 'Russian Wikisource Genitive' => [ 'ru', 'Викитека', 'genitive', 'Викитеки' ];
		yield 'Russian Wikipedia Accusative' => [ 'ru', 'Википедия', 'accusative', 'Википедию' ];
		yield 'Russian Wiktionary Accusative' => [ 'ru', 'Викисловарь', 'accusative', 'Викисловарь' ];
		yield 'Russian Wikiquote Accusative' => [ 'ru', 'Викицитатник', 'accusative', 'Викицитатник' ];
		yield 'Russian Wikibooks Accusative' => [ 'ru', 'Викиучебник', 'accusative', 'Викиучебник' ];
		yield 'Russian Wikisource Accusative' => [ 'ru', 'Викитека', 'accusative', 'Викитеку' ];
		yield 'Russian Wikinews Accusative' => [ 'ru', 'Викиновости', 'accusative', 'Викиновости' ];
		yield 'Russian Wikiversity Accusative' => [ 'ru', 'Викиверситет', 'accusative', 'Викиверситет' ];
		yield 'Russian Wikispecies Accusative' => [ 'ru', 'Викивиды', 'accusative', 'Викивиды' ];
		yield 'Russian Wikidata Accusative' => [ 'ru', 'Викиданные', 'accusative', 'Викиданные' ];
		yield 'Russian Commons Accusative' => [ 'ru', 'Викисклад', 'accusative', 'Викисклад' ];
		yield 'Russian Wikivoyage Accusative' => [ 'ru', 'Викигид', 'accusative', 'Викигид' ];
		yield 'Russian Meta Accusative' => [ 'ru', 'Мета', 'accusative', 'Мету' ];
		yield 'Russian Incubator Accusative' => [ 'ru', 'Инкубатор', 'accusative', 'Инкубатор' ];
		yield 'Russian Wikisource Prepositional' => [ 'ru', 'Викитека', 'prepositional', 'Викитеке' ];
		yield 'Russian Commons Genitive' => [ 'ru', 'Викисклад', 'genitive', 'Викисклада' ];
		yield 'Russian Wikiversity Genitive' => [ 'ru', 'Викиверситет', 'genitive', 'Викиверситета' ];
		yield 'Russian Commons Prepositional' => [ 'ru', 'Викисклад', 'prepositional', 'Викискладе' ];
		yield 'Russian Wikidata Prepositional' => [ 'ru', 'Викиданные', 'prepositional', 'Викиданных' ];
		yield 'Russian Wikiversity Prepositional' => [ 'ru', 'Викиверситет', 'prepositional', 'Викиверситете' ];
		yield 'Russian Language Genitive' => [ 'ru', 'русский', 'languagegen', 'русского' ];
		yield 'Russian Language Prepositional' => [ 'ru', 'русский', 'languageprep', 'русском' ];
		yield 'German Language Genitive' => [ 'ru', 'немецкий', 'languagegen', 'немецкого' ];
		yield 'German Language Prepositional' => [ 'ru', 'немецкий', 'languageprep', 'немецком' ];
		yield 'Yiddish Language Prepositional' => [ 'ru', 'идиш', 'languageprep', 'идише' ];
		yield 'Esperanto Language Genitive' => [ 'ru', 'эсперанто', 'languagegen', 'эсперанто' ];
		yield 'Esperanto Language Prepositional' => [ 'ru', 'эсперанто', 'languageprep', 'эсперанто' ];
		yield 'Russian Language Adverb' => [ 'ru', 'русский', 'languageadverb', 'по-русски' ];
		yield 'German Language Adverb' => [ 'ru', 'немецкий', 'languageadverb', 'по-немецки' ];
		yield 'Hebrew Language Adverb' => [ 'ru', 'иврит', 'languageadverb', 'на иврите' ];
		yield 'Esperanto Language Adverb' => [ 'ru', 'эсперанто', 'languageadverb', 'на эсперанто' ];
		yield 'Guarani Language Adverb' => [ 'ru', 'гуарани', 'languageadverb', 'на языке гуарани' ];
		yield 'Slovenian Imenovalnik' => [ 'sl', 'word', 'imenovalnik', 'word' ];
		yield 'Slovenian Mestnik' => [ 'sl', 'word', 'mestnik', 'o word' ];
		yield 'Slovenian Orodnik' => [ 'sl', 'word', 'orodnik', 'z word' ];
		yield 'Tyvan Wikipedia Genitive' => [ 'tyv', 'Википедия', 'genitive', 'Википедияның' ];
		yield 'Ukrainian Wikipedia Genitive' => [ 'uk', 'Вікіпедія', 'genitive', 'Вікіпедії' ];
		yield 'Ukrainian Wikispecies Genitive' => [ 'uk', 'Віківиди', 'genitive', 'Віківидів' ];
		yield 'Ukrainian Wikiquote Genitive' => [ 'uk', 'Вікіцитати', 'genitive', 'Вікіцитат' ];
		yield 'Ukrainian Wikibooks Genitive' => [ 'uk', 'Вікіпідручник', 'genitive', 'Вікіпідручника' ];
		yield 'Ukrainian Wikipedia Accusative' => [ 'uk', 'Вікіпедія', 'accusative', 'Вікіпедію' ];
		yield 'Ukrainian MediaWiki Locative' => [ 'uk', 'MediaWiki', 'locative', 'у MediaWiki' ];
		yield 'Azerbaijani ordinal 0 → cı'         => [ 'az', '0', 'ordinal', '0-cı' ];
		yield 'Azerbaijani ordinal 1 → ci'         => [ 'az', '1', 'ordinal', '1-ci' ];
		yield 'Azerbaijani ordinal 2 → ci'         => [ 'az', '2', 'ordinal', '2-ci' ];
		yield 'Azerbaijani ordinal 3 → cü'         => [ 'az', '3', 'ordinal', '3-cü' ];
		yield 'Azerbaijani ordinal 4 → cü'         => [ 'az', '4', 'ordinal', '4-cü' ];
		yield 'Azerbaijani ordinal 5 → ci'         => [ 'az', '5', 'ordinal', '5-ci' ];
		yield 'Azerbaijani ordinal 6 → cı'         => [ 'az', '6', 'ordinal', '6-cı' ];
		yield 'Azerbaijani ordinal 7 → ci'         => [ 'az', '7', 'ordinal', '7-ci' ];
		yield 'Azerbaijani ordinal 8 → ci'         => [ 'az', '8', 'ordinal', '8-ci' ];
		yield 'Azerbaijani ordinal 9 → cu'         => [ 'az', '9', 'ordinal', '9-cu' ];
		yield 'Azerbaijani ordinal 10 → cu'        => [ 'az', '10', 'ordinal', '10-cu' ];
		yield 'Azerbaijani ordinal 20 → ci'        => [ 'az', '20', 'ordinal', '20-ci' ];
		yield 'Azerbaijani ordinal 30 → cu'        => [ 'az', '30', 'ordinal', '30-cu' ];
		yield 'Azerbaijani ordinal 40 → cı'        => [ 'az', '40', 'ordinal', '40-cı' ];
		yield 'Azerbaijani ordinal 50 → ci'        => [ 'az', '50', 'ordinal', '50-ci' ];
		yield 'Azerbaijani ordinal 60 → cı'        => [ 'az', '60', 'ordinal', '60-cı' ];
		yield 'Azerbaijani ordinal 70 → ci'        => [ 'az', '70', 'ordinal', '70-ci' ];
		yield 'Azerbaijani ordinal 80 → ci'        => [ 'az', '80', 'ordinal', '80-ci' ];
		yield 'Azerbaijani ordinal 90 → cı'        => [ 'az', '90', 'ordinal', '90-cı' ];
		yield 'Azerbaijani ordinal 100 → cü'       => [ 'az', '100', 'ordinal', '100-cü' ];
		yield 'Azerbaijani ordinal 110 → cu'       => [ 'az', '110', 'ordinal', '110-cu' ];
		yield 'Azerbaijani ordinal 190 → cı'       => [ 'az', '190', 'ordinal', '190-cı' ];
		yield 'Azerbaijani ordinal 23 → cü'        => [ 'az', '23', 'ordinal', '23-cü' ];
		yield 'Azerbaijani ordinal 1000 → ci'      => [ 'az', '1000', 'ordinal', '1000-ci' ];
		yield 'Azerbaijani ordinal 1000000 → cu'   => [ 'az', '1000000', 'ordinal', '1000000-cu' ];
		yield 'Azerbaijani ordinal 1000000000 → cı' => [ 'az', '1000000000', 'ordinal', '1000000000-cı' ];
		yield 'Azerbaijani genitive su → suyun'         => [ 'az', 'su', 'genitive', 'suyun' ];
		yield 'Azerbaijani genitive çörək → çörəyin'    => [ 'az', 'çörək', 'genitive', 'çörəyin' ];
		yield 'Azerbaijani genitive ayaq → ayağın'     => [ 'az', 'ayaq', 'genitive', 'ayağın' ];
		yield 'Azerbaijani genitive ilik → iliyin'      => [ 'az', 'ilik', 'genitive', 'iliyin' ];
		yield 'Azerbaijani genitive ürək → ürəyin'      => [ 'az', 'ürək', 'genitive', 'ürəyin' ];
		yield 'Azerbaijani genitive qulaq → qulağın'    => [ 'az', 'qulaq', 'genitive', 'qulağın' ];
		yield 'Azerbaijani genitive buruq → buruğun'    => [ 'az', 'buruq', 'genitive', 'buruğun' ];
		yield 'Azerbaijani genitive çörük → çörüyün'    => [ 'az', 'çörük', 'genitive', 'çörüyün' ];
		yield 'Azerbaijani genitive ana → ananın'       => [ 'az', 'ana', 'genitive', 'ananın' ];
		yield 'Azerbaijani genitive dolu → dolunun'     => [ 'az', 'dolu', 'genitive', 'dolunun' ];
		yield 'Azerbaijani genitive hücrə → hücrənin'   => [ 'az', 'hücrə', 'genitive', 'hücrənin' ];
		yield 'Azerbaijani genitive ölkə → ölkənin'     => [ 'az', 'ölkə', 'genitive', 'ölkənin' ];
		yield 'Azerbaijani genitive Azərbaycan → Azərbaycanın' => [ 'az', 'Azərbaycan', 'genitive', 'Azərbaycanın' ];
		yield 'Azerbaijani genitive qonur → qonurun'    => [ 'az', 'qonur', 'genitive', 'qonurun' ];
		yield 'Azerbaijani genitive dəmir → dəmirin'    => [ 'az', 'dəmir', 'genitive', 'dəmirin' ];
		yield 'Azerbaijani genitive gül → gülün'        => [ 'az', 'gül', 'genitive', 'gülün' ];
		yield 'Azerbaijani genitive göy → göyün'        => [ 'az', 'göy', 'genitive', 'göyün' ];
		yield 'Azerbaijani accusative su → suyu'         => [ 'az', 'su', 'accusative', 'suyu' ];
		yield 'Azerbaijani accusative alma → almanı'     => [ 'az', 'alma', 'accusative', 'almanı' ];
		yield 'Azerbaijani accusative qapı → qapını'     => [ 'az', 'qapı', 'accusative', 'qapını' ];
		yield 'Azerbaijani accusative dolu → dolunu'     => [ 'az', 'dolu', 'accusative', 'dolunu' ];
		yield 'Azerbaijani accusative kişi → kişini'     => [ 'az', 'kişi', 'accusative', 'kişini' ];
		yield 'Azerbaijani accusative ev → evi'          => [ 'az', 'ev', 'accusative', 'evi' ];
		yield 'Azerbaijani accusative at → atı'          => [ 'az', 'at', 'accusative', 'atı' ];
		yield 'Azerbaijani accusative qız → qızı'        => [ 'az', 'qız', 'accusative', 'qızı' ];
		yield 'Azerbaijani accusative gül → gülü'        => [ 'az', 'gül', 'accusative', 'gülü' ];
		yield 'Azerbaijani accusative göy → göyü'        => [ 'az', 'göy', 'accusative', 'göyü' ];
		yield 'Azerbaijani accusative dost → dostu'      => [ 'az', 'dost', 'accusative', 'dostu' ];
		yield 'Azerbaijani accusative dəmir → dəmiri'    => [ 'az', 'dəmir', 'accusative', 'dəmiri' ];
		yield 'Azerbaijani accusative saat → saatı'      => [ 'az', 'saat', 'accusative', 'saatı' ];
		yield 'Azerbaijani accusative çörək → çörəyi'    => [ 'az', 'çörək', 'accusative', 'çörəyi' ];
		yield 'Azerbaijani accusative ayaq → ayağı'      => [ 'az', 'ayaq', 'accusative', 'ayağı' ];
		yield 'Azerbaijani accusative qulaq → qulağı'    => [ 'az', 'qulaq', 'accusative', 'qulağı' ];
		yield 'Azerbaijani accusative buruq → buruğu'    => [ 'az', 'buruq', 'accusative', 'buruğu' ];
		yield 'Azerbaijani accusative ürək → ürəyi'      => [ 'az', 'ürək', 'accusative', 'ürəyi' ];
		yield 'Azerbaijani təsirlik su → suyu'           => [ 'az', 'su', 'təsirlik', 'suyu' ];
	}

	/**
	 * @dataProvider provideGrammarCases
	 *
	 * Tests that grammatical transformations are correctly applied.
	 *
	 * @param string $lang BCP 47 language code (e.g., "ru", "fi", "la")
	 * @param string $word Word to transform
	 * @param string $case Grammar case to apply (e.g., "genitive", "ablative")
	 * @param string $expected Expected transformed output
	 */
	public function testProcess( string $lang, string $word, string $case, string $expected ): void {
		$provider = new Provider( $lang, new NullLogger() );
		$grammarHandler = new Grammar( $provider, new GrammarFallbackRegistry(), new NullLogger() );
		$result = $grammarHandler->process( $word, $case );
		$this->assertSame( $expected, $result );
	}
}
