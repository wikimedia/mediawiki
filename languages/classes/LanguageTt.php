<?php
/**
 * Tatar specific code.
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

require_once __DIR__ . '/../LanguageConverter.php';

/**
 * @ingroup Language
 */
class TtConverter extends LanguageConverter {
	public $toLatin = array(
		'а' => 'a', 'А' => 'A',
		'ә' => 'ə', 'Ә' => 'Ə',
		'б' => 'b', 'Б' => 'B',
		'в' => 'w', 'В' => 'W',
		'г' => 'g', 'Г' => 'G',
		'д' => 'd', 'Д' => 'D',
		'е' => 'e', 'Е' => 'E',
		'ё' => 'yo', 'Ё' => 'Yo',
		'ж' => 'j', 'Ж' => 'J',
		'җ' => 'c', 'Җ' => 'C',
		'з' => 'z', 'З' => 'Z',
		'и' => 'i', 'И' => 'İ',
		'й' => 'y', 'Й' => 'Y',
		'к' => 'k', 'К' => 'K',
		'л' => 'l', 'Л' => 'L',
		'м' => 'm', 'М' => 'M',
		'н' => 'n', 'Н' => 'N',
		'ң' => 'ñ', 'Ң' => 'Ñ',
		'о' => 'o', 'О' => 'O',
		'ө' => 'ɵ', 'Ө' => 'Ɵ',
		'п' => 'p', 'П' => 'P',
		'р' => 'r', 'Р' => 'R',
		'с' => 's', 'С' => 'S',
		'т' => 't', 'Т' => 'T',
		'у' => 'u', 'У' => 'U',
		'ү' => 'ü', 'Ү' => 'Ü',
		'ф' => 'f', 'Ф' => 'F',
		'х' => 'x', 'Х' => 'X',
		'һ' => 'h', 'Һ' => 'H',
		'ц' => 'ts', 'Ц' => 'Ts',
		'ч' => 'ç', 'Ч' => 'Ç',
		'ш' => 'ş', 'Ш' => 'Ş',
		'щ' => 'şç', 'Щ' => 'Şç',
		'ъ' => '', 'Ъ' => '',
		'ы' => 'ı', 'Ы' => 'I',
		'ь' => '\'', 'Ь' => '\'',
		'э' => 'e', 'Э' => 'E',
		'ю' => 'yu', 'Ю' => 'Yu',
		'я' => 'ya', 'Я' => 'Ya',
	);

	public $toCyrillic = array(
		'a' => 'а', 'A' => 'А',
		'ə' => 'ә', 'Ə' => 'Ә',
		'ä' => 'ә', 'Ä' => 'Ә',
		'b' => 'б', 'B' => 'Б',
		'c' => 'җ', 'C' => 'Җ',
		'ç' => 'ч', 'Ç' => 'Ч',
		'd' => 'д', 'D' => 'Д',
		'e' => 'е', 'E' => 'Е',
		'f' => 'ф', 'F' => 'Ф',
		'g' => 'г', 'G' => 'Г',
		'ğ' => 'г', 'Ğ' => 'Г',
		'h' => 'һ', 'H' => 'Һ',
		'i' => 'и', 'İ' => 'И',
		'ı' => 'ы', 'I' => 'Ы',
		'j' => 'ж', 'J' => 'Ж',
		'k' => 'к', 'K' => 'К',
		'l' => 'л', 'L' => 'Л',
		'm' => 'м', 'M' => 'М',
		'n' => 'н', 'N' => 'Н',
		'ñ' => 'ң', 'Ñ' => 'Ң',
		'o' => 'о', 'O' => 'О',
		'ɵ' => 'ө', 'Ɵ' => 'Ө',
		'ö' => 'ө', 'Ö' => 'Ө',
		'p' => 'п', 'P' => 'П',
		'q' => 'к', 'Q' => 'К',
		'r' => 'р', 'R' => 'Р',
		's' => 'с', 'S' => 'С',
		'ş' => 'ш', 'Ş' => 'Ш',
		't' => 'т', 'T' => 'Т',
		'u' => 'у', 'U' => 'У',
		'ü' => 'ү', 'Ü' => 'Ү',
		'v' => 'в', 'V' => 'В',
		'w' => 'в', 'W' => 'В',
		'x' => 'х', 'X' => 'Х',
		'y' => 'й', 'Y' => 'Й',
		'z' => 'з', 'Z' => 'З',
		'\'' => 'э','’' => 'э',
	);

	function loadDefaultTables() {
		$this->mTables = array(
			'tt-cyrl' => new ReplacementArray( $this->toCyrillic ),
			'tt-latn' => new ReplacementArray( $this->toLatin ),
			'tt' => new ReplacementArray()
		);
	}

	function translate( $text, $toVariant ) {
		if ( $toVariant == 'tt-cyrl' ) {
			$text = preg_replace( '/I(?=\S*\.(ru|com|org|RU|COM|ORG))/', 'И', $text );
			//INTIRTAT.RU test.com/INTIRTIT
			//->
			//ИНТИРТАТ.РУ тест.җом/ЫНТЫРТЫТ
			$text = preg_replace( '/iyül/ui', 'июль', $text );
			$text = preg_replace( '/yabr/ui', 'ябрь', $text );
			$text = preg_replace( '/fäqät/ui', 'фәкать', $text );
			$text = preg_replace( '/kopia/ui', 'копия', $text );
			$text = preg_replace( '/nyaq/ui', 'ньяк', $text );
			$text = preg_replace( '/aw\b/ui', 'ау', $text );
			$text = preg_replace( '/aw(?=[çmc])/ui', 'ау', $text );
			$text = preg_replace( '/äw\b/ui', 'әү', $text );
			$text = preg_replace( '/aği/ui', 'агый', $text );
			$text = preg_replace( '/yudjet/ui', 'юджет', $text );
			$text = preg_replace( '/alyuta/ui', 'алюта', $text );
			$text = preg_replace( '/tsiya/ui', 'ция', $text );
			$text = preg_replace( '/ğtibar/ui', 'гътибар', $text );
			$text = preg_replace( '/qiyät/ui', 'кыять', $text );
			$text = preg_replace( '/ği/u', 'гый', $text );
			$text = preg_replace( '/Ği/u', 'Гый', $text );
			$text = preg_replace( '/\be/u', 'э', $text );
			$text = preg_replace( '/\bE/u', 'Э', $text );
			// $text = str_replace( 'ye', 'е', $text );
			// $text = str_replace( 'Ye', 'Е', $text );
			// $text = str_replace( 'YE', 'Е', $text );
			// $text = preg_replace( '/([bvgdjzyklmnprstfxcwqh])e/ui', '$1е', $text );
			$text = preg_replace( '/([bvgdjzklmnprstfx])y[eE]/u', '$1ье', $text );
			$text = preg_replace( '/([oaıu])y[ıIeE]/u', '$1е', $text );
			$text = preg_replace( '/\by[ıIeE]/u', 'е', $text );
			$text = preg_replace( '/\bY[ıIeE]/u', 'Е', $text );
			$text = preg_replace( '/([ieäÄəƏaA])y[äÄəƏaA]/u', '$1я', $text );
			$text = preg_replace( '/\by[äÄəƏaA]/u', '$1я', $text );
			$text = preg_replace( '/\bY[äÄəƏaA]/u', 'Я', $text );
			$text = preg_replace( '/\by[uUüÜ]/u', 'ю', $text );
			$text = preg_replace( '/\bY[uUüÜ]/u', 'Ю', $text );
			$text = preg_replace( '/ğ[äÄəƏ]/u', 'га', $text );
			$text = preg_replace( '/Ğ[äÄəƏ]/u', 'Га', $text );
			$text = preg_replace( '/q[äÄəƏ]/u', 'ка', $text );
			$text = preg_replace( '/Q[äÄəƏ]/u', 'Ка', $text );
			$text = preg_replace( '/i[äÄəƏ]/u', 'ия', $text );
		}elseif( $toVariant == 'tt-latn' ){
			$text = preg_replace( '/онкурс/ui', 'onkurs', $text );
			$text = preg_replace( '/едакц/ui', 'edakts', $text );
			$text = preg_replace( '/втор/ui', 'vtor', $text );
			$text = preg_replace( '/ов/ui', 'ov', $text );
			$text = preg_replace( '/иев/ui', 'iyev', $text );
			$text = preg_replace( '/оллектив/ui', 'ollektiv', $text );
			$text = preg_replace( '/(.+)ева/ui', '$1eva', $text );
			$text = preg_replace( '/екабр/ui', 'ekabr', $text );
			$text = preg_replace( '/укмар/ui', 'ukmar', $text );
			$text = preg_replace( '/арьер/ui', 'aryer', $text );
			$text = preg_replace( '/омпьютер/ui', 'ompyuter', $text );
			$text = preg_replace( '/оммуна/ui', 'ommuna', $text );
			$text = preg_replace( '/екабр/ui', 'ekabr', $text );
			$text = preg_replace( '/ика/ui', 'ika', $text );
			$text = preg_replace( '/онструк/ui', 'onstrukt', $text );
			$text = preg_replace( '/АКШ/u', 'AQŞ', $text );
			//$text = preg_replace( '/мәгариф/u', 'məğərif', $text );
			//i think maybe this one should be left as it is, məğarif,
			//because of rule of setting "a" in place of Arabic "alif".
			$text = preg_replace( '/музее/u', 'muzeyı', $text );
			$text = preg_replace( '/боул/u', 'bowl', $text );
			$text = preg_replace( '/евол/ui', 'evol', $text );
			$text = preg_replace( '/тасвир/ui', 'taswir', $text );
			$text = preg_replace( '/вир/ui', 'vir', $text );
			$text = preg_replace( '/ива/ui', 'iva', $text );
			$text = preg_replace( '/аватар/u', 'avatar', $text );
			$text = preg_replace( '/Аватар/u', 'Avatar', $text );
			$text = preg_replace( '/камил/ui', 'kamil', $text );
			//$text = preg_replace( '/юеш/ui', 'yüeş', $text );
			$text = preg_replace( '/\bгае/u', 'ğəye', $text );
			//remember not to change сагаеп
			$text = preg_replace( '/\bГае/u', 'Ğəye', $text );
			$text = preg_replace( '/гая([тр])(?=е)/ui', 'ğəyə$1', $text );
			$text = preg_replace( '/гая([тр])ь/ui', 'ğəyə$1', $text );
			$text = preg_replace( '/га([тр])ь/ui', 'ğə$1', $text );
			$text = preg_replace( '/га([дҗ])ә/ui', 'ğə$1ə', $text );
			$text = preg_replace( '/\bка([бвгджҗзклмнңпрстфхһцчшщ])([еә])/u', 'qə$1$2', $text );
			//кабер, калеб
			$text = preg_replace( '/эко/u', 'eko', $text );
			$text = preg_replace( '/Эко/u', 'Eko', $text );
			$text = preg_replace( '/эво/u', 'evo', $text );
			$text = preg_replace( '/Эво/u', 'Evo', $text );
			$text = preg_replace( '/\bе([бвгджҗзклмнңпрстфхһцчшщ]+(?=([уаы]|\b)))/u', 'yı$1', $text );
			//егу-yığu
			$text = preg_replace( '/\bЕ([бвгджҗзклмнңпрстфхһцчшщ]+(?=([уаы]|\b)))/u', 'Yı$1', $text );
			$text = preg_replace( '/канәг/u', 'qənəğ', $text );
			$text = preg_replace( '/Канәг/u', 'Qənəğ', $text );
			$text = preg_replace( '/әккы/u', 'əqqi', $text );
			$text = preg_replace( '/карават/u', 'karawat', $text );
			$text = preg_replace( '/коря/u', 'kor\'a', $text );
			$text = preg_replace( '/([бвгджҗзклмнңпрстфхһцчшщ])я/u', '$1\'a', $text );//варяг
			//make exceptions (васыять, вәкил, вәзгыять, вәли, веб, вак)
			//then all first в to v (вагон, виза, версия, вариант, визит)
			$text = preg_replace( '/\bвеб/u', 'web', $text );
			$text = preg_replace( '/\bВеб/u', 'Web', $text );
			$text = preg_replace( '/\bвак/u', 'waq', $text );
			$text = preg_replace( '/\bВак/u', 'Waq', $text );
			$text = preg_replace( '/\bвасыять?/u', 'wasiyət', $text );
			$text = preg_replace( '/\bВасыять?/u', 'Wasiyət', $text );
			$text = preg_replace( '/\bвә/u', 'wə', $text );
			$text = preg_replace( '/\bВә/u', 'Wə', $text );
			$text = preg_replace( '/волга(?=га)/u', 'volga', $text );
			$text = preg_replace( '/\bв/u', 'v', $text );
			$text = preg_replace( '/\bВ/u', 'V', $text );
			$text = preg_replace( '/(.[өә].а)е/ui', '$1ye', $text );//мөлаем, мөгаен, тәгаен
			// $text = preg_replace( '/\bпауза/u', 'pauza', $text );
			//rare
			// $text = preg_replace( '/\bПауза/u', 'Pauza', $text );
			$text = preg_replace( '/гомео/u', 'gomeo', $text );
			$text = preg_replace( '/Гомео/u', 'Gomeo', $text );
			$text = preg_replace( '/ияз/u', 'iyaz', $text );
			$text = preg_replace( '/указ/u', 'ukaz', $text );
			$text = preg_replace( '/мв/u', 'mv', $text );
			$text = preg_replace( '/([аыоу])е/ui', '$1yı', $text );
			$text = preg_replace( '/([әеө])е/ui', '$1ye', $text );
			$text = preg_replace( '/үе/ui', 'üe', $text );
			$text = preg_replace( '/юе/ui', 'yüe', $text );
			$text = preg_replace( '/([юу])ы/ui', '$1wı', $text );
			$text = preg_replace( '/ю([бвгджҗзклмнңпрстфхһцчшщ][әие])/ui', 'yü$1', $text );
			$text = preg_replace( '/ю([бвгджҗзклмнңпрстфхһцчшщ])ь/ui', 'yü$1', $text );
			$text = preg_replace( '/я([бвгджҗзклмнңпрстфхһцчшщ][әие])/ui', 'yə$1', $text );
			$text = preg_replace( '/я([бвгджҗзклмнңпрстфхһцчшщ])ь/ui', 'yə$1', $text );
			$text = preg_replace( '/пауза/u', 'pauza', $text );
			$text = preg_replace( '/([аәя])[уү](?![бвгджҗзклмнңпрстфхһцчшщ]{2,2})/ui', '$1w', $text );
			$text = preg_replace( '/([иөә])ю/ui', '$1yü', $text );
			$text = preg_replace( '/кама(?=га)/u', 'kama', $text );
			$text = preg_replace( '/каток/u', 'katok', $text );
			$text = preg_replace( '/бәкара/u', 'bəkara', $text );
			$text = preg_replace( '/щ[её]тка/u', 'şçotka', $text );
			$text = preg_replace( '/сч[её]т/u', 'sçot', $text );
			$text = preg_replace( '/[её]лка/u', 'yolka', $text );
			$text = preg_replace( '/к([аыоуъ])/u', 'q$1', $text );
			$text = preg_replace( '/К([аыоуАЫОУ])/u', 'Q$1', $text );
			$text = preg_replace( '/газ/u', 'gaz', $text );
			$text = preg_replace( '/шпага/u', 'şpaga', $text );
			$text = preg_replace( '/г([аыуъ])/u', 'ğ$1', $text );
			$text = preg_replace( '/Г([аыуАЫУ])/u', 'Ğ$1', $text );
			// $text = preg_replace( '/\bе([шл])/ui', 'yı$1', $text );
			// $text = preg_replace( '/\bЕ([шл])/ui', 'Yı$1', $text );
			$text = preg_replace( '/(\b|[ъь])е/ui', 'ye', $text );
			$text = preg_replace( '/тугрик/u', 'tugrik', $text );
			$text = preg_replace( '/друг/u', 'drug', $text );
			$text = preg_replace( '/([аыıуяюАЫIОУЯЮ])к(?!и)/u', '$1q', $text );
			$text = preg_replace( '/([аыıуАЫIОУ])г(?!о)/u', '$1ğ', $text );
			$text = preg_replace( '/([аыоуАЫОУ]\w*[яЯ])к/u', '$1q', $text );
			$text = preg_replace( '/авари/ui', 'avari', $text );
			$text = preg_replace( '/рия/ui', 'riya', $text );//рия-рийа
			$text = preg_replace( '/([өиәү])я/ui', '$1yə', $text );//көя, җәя, гүя, ия
			$text = preg_replace( '/ыять/u', 'iyət', $text );
			$text = preg_replace( '/ать/u', 'ət', $text );
			$text = preg_replace( '/оръән/ui', 'ör\'ən', $text );
			$text = preg_replace( '/го([бвгджҗзклмнңпрстфхһцчшщ]+)(?=[еә])/ui', 'ğö$1', $text );
			$text = preg_replace( '/гомум/ui', 'ğömum', $text );
			$text = preg_replace( '/авиа/ui', 'avia', $text );
		}
		return parent::translate( $text, $toVariant );
	}
}

/**
 * Tatar
 *
 * @ingroup Language
 */
class LanguageTt extends Language {
	function __construct() {
		global $wgHooks;
		parent::__construct();

		$variants = array( 'tt', 'tt-latn', 'tt-cyrl' );
		$variantfallbacks = array(
			'tt' => 'tt-latn',
			'tt-cyrl' => 'tt',
			'tt-latn' => 'tt',
		);

		$this->mConverter = new TtConverter( $this, 'tt', $variants, $variantfallbacks );
		$wgHooks['PageContentSaveComplete'][] = $this->mConverter;
	}
}
