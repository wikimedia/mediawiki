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
		'а' => 'a', // 'А' => 'A',// capital letters are handled outside for now
		'ә' => 'ə', // 'Ә' => 'Ə',
		'б' => 'b', // 'Б' => 'B',
		'в' => 'w', // 'В' => 'W',
		'г' => 'g', // 'Г' => 'G',
		'д' => 'd', // 'Д' => 'D',
		'е' => 'e', // 'Е' => 'E',
		'ё' => 'yo', // 'Ё' => 'Yo',
		'ж' => 'j', // 'Ж' => 'J',
		'җ' => 'c', // 'Җ' => 'C',
		'з' => 'z', // 'З' => 'Z',
		'и' => 'i', // 'И' => 'İ',
		'й' => 'y', // 'Й' => 'Y',
		'к' => 'k', // 'К' => 'K',
		'л' => 'l', // 'Л' => 'L',
		'м' => 'm', // 'М' => 'M',
		'н' => 'n', // 'Н' => 'N',
		'ң' => 'ñ', // 'Ң' => 'Ñ',
		'о' => 'o', // 'О' => 'O',
		'ө' => 'ɵ', // 'Ө' => 'Ɵ',
		'п' => 'p', // 'П' => 'P',
		'р' => 'r', // 'Р' => 'R',
		'с' => 's', // 'С' => 'S',
		'т' => 't', // 'Т' => 'T',
		'у' => 'u', // 'У' => 'U',
		'ү' => 'ü', // 'Ү' => 'Ü',
		'ф' => 'f', // 'Ф' => 'F',
		'х' => 'x', // 'Х' => 'X',
		'һ' => 'h', // 'Һ' => 'H',
		'ц' => 'ts', // 'Ц' => 'Ts',
		'ч' => 'ç', // 'Ч' => 'Ç',
		'ш' => 'ş', // 'Ш' => 'Ş',
		'щ' => 'şç', // 'Щ' => 'Şç',
		'ъ' => '', // 'Ъ' => '',
		'ы' => 'ı', // 'Ы' => 'I',
		'ь' => '', // 'Ь' => '',
		'э' => 'e', // 'Э' => 'E',
		'ю' => 'yu', // 'Ю' => 'Yu',
		'я' => 'ya', // 'Я' => 'Ya',
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

	function convertTatarFromCyrillicToLatin( $text ) {
		// some lines are commented out because capital letters are handled outside for now
		$text = preg_replace( '/онкурс/u', 'onkurs', $text );
		$text = preg_replace( '/едакц/u', 'edakts', $text );
		$text = preg_replace( '/втор/u', 'vtor', $text );
		$text = preg_replace( '/ов/u', 'ov', $text );
		$text = preg_replace( '/иев/u', 'iev', $text );
		$text = preg_replace( '/уев/u', 'uyev', $text );
		$text = preg_replace( '/ив/u', 'iv', $text );
		$text = preg_replace( '/ссылка/u', 'ssılka', $text );
		$text = preg_replace( '/музыка/u', 'muzıka', $text );
		$text = preg_replace( '/ичка/u', 'içka', $text );
		$text = preg_replace( '/оллектив/u', 'ollektiv', $text );
		$text = preg_replace( '/(.+)ева/u', '$1eva', $text );
		$text = preg_replace( '/екабр/u', 'ekaber', $text );// декабрь-dekaber-!deqaber
		$text = preg_replace( '/укмар/u', 'ukmar', $text );
		$text = preg_replace( '/кушке/u', 'kuşke', $text );
		$text = preg_replace( '/куркин/u', 'kurkin', $text );
		$text = preg_replace( '/кузмесь/u', 'kuzmes\'', $text );
		$text = preg_replace( '/ерь/u', 'er\'', $text );// синерь-siner'
		$text = preg_replace( '/аль/u', 'al\'', $text );// коммуналь-kommunal'
		$text = preg_replace( '/арьер/u', 'aryer', $text );
		$text = preg_replace( '/омпьютер/u', 'ompyuter', $text );
		$text = preg_replace( '/оммуна/u', 'ommuna', $text );
		$text = preg_replace( '/ика/u', 'ika', $text );
		$text = preg_replace( '/онструк/u', 'onstruk', $text );
		// $text = preg_replace( '/АКШ/u', 'AQŞ', $text );// capital letters are handled outside for now
		$text = preg_replace( '/юхиди/u', 'yuxidi', $text );
		// $text = preg_replace( '/мәгариф/u', 'məğərif', $text );
		// i think maybe this one should be left as it is, məğarif,
		// because of rule of setting "a" in place of Arabic "alif".
		$text = preg_replace( '/музее/u', 'muzeyı', $text );
		$text = preg_replace( '/боул/u', 'bowl', $text );
		$text = preg_replace( '/волга/u', 'volga', $text );
		$text = preg_replace( '/коммун/u', 'kommun', $text );
		$text = preg_replace( '/юбилей/u', 'yubiley', $text );
		$text = preg_replace( '/юбилее/u', 'yubileyı', $text );
		$text = preg_replace( '/суфиян/u', 'sufiyan', $text );
		// $text = preg_replace( '/([бвгджҗзклмнңпрстфхһцчшщ])ю(?=\w+[әе])/ui', '$1ü', $text );// эволюциягә
		$text = preg_replace( '/([бвгджҗзклмнңпрстфхһцчшщ])ю(?=\w+[аыо])/ui', '$1\'u', $text );// эволюцияга,люгдон
		$text = preg_replace( '/([бвгджҗзклмнңпрстфхһцчшщ])ю/ui', '$1ü', $text );// эволюция
		// $text = preg_replace( '/волю/ui', 'volü', $text );
		$text = preg_replace( '/вол/ui', 'vol', $text );
		$text = preg_replace( '/тасвир/ui', 'taswir', $text );
		$text = preg_replace( '/вир/ui', 'vir', $text );
		// $text = preg_replace( '/ива/ui', 'iva', $text );// see ив
		$text = preg_replace( '/аватар/u', 'avatar', $text );
		$text = preg_replace( '/Аватар/u', 'Avatar', $text );
		$text = preg_replace( '/никях/u', 'nikax', $text );
		$text = preg_replace( '/авари/ui', 'avari', $text );
		$text = preg_replace( '/вам/u', 'vam', $text );// вигвам
		$text = preg_replace( '/мья/u', 'm\'ya', $text );// юмья
		$text = preg_replace( '/аэмай/u', 'a\'may', $text );
		$text = preg_replace( '/әэсир/u', 'ə\'sir', $text );
		$text = preg_replace( '/әсьәлә/u', 'əs\'ələ', $text );
		$text = preg_replace( '/коэн/u', 'koen', $text );
		$text = preg_replace( '/әэссорат/u', 'ə\'essorat', $text );
		// $text = preg_replace( '/гата/u', 'ğata\'', $text );// гатауллин-ğata'ullin
		// the above rule was for a more correct representation of arabic orginal
		// but this is not pronounced so nowadays, so i comment it out,
		// so it will be spelled like in cyrillic
		$text = preg_replace( '/ризаэтдин/u', 'riza\'etdin', $text );
		$text = preg_replace( '/ельвиж/u', 'el\'vij', $text );
		$text = preg_replace( '/эль/u', 'el\'', $text );//
		$text = preg_replace( '/(\w)ель/u', '$1el\'', $text );//
		$text = preg_replace( '/камил/ui', 'kamil', $text );
		$text = preg_replace( '/гаять/ui', 'ğayət', $text );
		$text = preg_replace( '/янил/ui', 'yanil', $text );
		// $text = preg_replace( '/Янил/ui', 'Yanil', $text );
		$text = preg_replace( '/тагил/ui', 'tagil', $text );
		$text = preg_replace( '/лаксиха/ui', 'laksixa', $text );
		$text = preg_replace( '/юлия(?=[бвгджҗзклмнңпрстфхһцчшщ]+[аы])/ui', 'yuliya', $text );
		$text = preg_replace( '/юлия(?=[бвгджҗзклмнңпрстфхһцчшщ]+[әе])/ui', 'yuliə', $text );
		$text = preg_replace( '/юлия\b/ui', 'yuliə', $text );
		$text = preg_replace( '/екатерина/u', 'yekaterina', $text );
		// $text = preg_replace( '/юеш/ui', 'yüeş', $text );
		$text = preg_replace( '/\bгае/u', 'ğəye', $text );
		// remember not to change сагаеп
		$text = preg_replace( '/\bГае/u', 'Ğəye', $text );
		$text = preg_replace( '/гая([тр])(?=е)/ui', 'ğəyə$1', $text );
		$text = preg_replace( '/гая([тр])ь/ui', 'ğəyə$1', $text );
		$text = preg_replace( '/га(?=[дҗтр][ьәе])/ui', 'ğə$1', $text );
		// $text = preg_replace( '/га([])ә/ui', 'ğə$1ə', $text );
		$text = preg_replace( '/\bка([бвгджҗзклмнңпрстфхһцчшщ])([еә])/u', 'qə$1$2', $text );
		// кабер, калеб
		$text = preg_replace( '/эко/u', 'eko', $text );
		// $text = preg_replace( '/Эко/u', 'Eko', $text );
		$text = preg_replace( '/эво/u', 'evo', $text );
		// $text = preg_replace( '/Эво/u', 'Evo', $text );
		$text = preg_replace( '/езд/u', 'yezd', $text );// поезд-poyezd
		$text = preg_replace( '/брь/u', 'ber', $text );// ноябрь-noyaber
		$text = preg_replace( '/\bе([бвгджҗзклмнңпрстфхһцчшщ]+(?=([уаы]|\b)))/u', 'yı$1', $text );
		// егу-yığu
		// $text = preg_replace( '/\bЕ([бвгджҗзклмнңпрстфхһцчшщ]+(?=([уаы]|\b)))/u', 'Yı$1', $text );
		$text = preg_replace( '/канәг/u', 'qənəğ', $text );
		// $text = preg_replace( '/Канәг/u', 'Qənəğ', $text );
		$text = preg_replace( '/әккы/u', 'əqqi', $text );
		$text = preg_replace( '/карават/u', 'karawat', $text );
		$text = preg_replace( '/коряга/u', 'kor\'aga', $text );
		$text = preg_replace( '/зигзаг/u', 'zigzag', $text );
		$text = preg_replace( '/тюк/u', 't\'uk', $text );
		// $text = preg_replace( '/жив/u', 'jiv', $text );// see ив
		$text = preg_replace( '/дөнья/u', 'dɵnya', $text );
		$text = preg_replace( '/([бвгджҗзклмнңпрстфхһцчшщ])я/u', '$1\'a', $text );// варяг
		// make exceptions (васыять, вәкил, вәзгыять, вәли, веб, вак)
		// then all first в to v (вагон, виза, версия, вариант, визит)
		$text = preg_replace( '/\bвеб/u', 'web', $text );
		// $text = preg_replace( '/\bВеб/u', 'Web', $text );
		$text = preg_replace( '/\bвак/u', 'waq', $text );
		// $text = preg_replace( '/\bВак/u', 'Waq', $text );
		$text = preg_replace( '/\bвасыять?/u', 'wasiyət', $text );
		// $text = preg_replace( '/\bВасыять?/u', 'Wasiyət', $text );
		$text = preg_replace( '/\bвә/u', 'wə', $text );
		// $text = preg_replace( '/\bВә/u', 'Wə', $text );
		$text = preg_replace( '/\bв/u', 'v', $text );
		// $text = preg_replace( '/\bВ/u', 'V', $text );
		$text = preg_replace( '/(.[өә].а)е/ui', '$1ye', $text );// мөлаем, мөгаен, тәгаен
		$text = preg_replace( '/гомео/u', 'gomeo', $text );
		// $text = preg_replace( '/Гомео/u', 'Gomeo', $text );
		$text = preg_replace( '/аугли/u', 'awgli', $text );// маугли-mawgli
		$text = preg_replace( '/ияз/u', 'iyaz', $text );
		$text = preg_replace( '/рия/ui', 'riya', $text );// рия-рийа
		$text = preg_replace( '/указ/u', 'ukaz', $text );
		$text = preg_replace( '/мв/u', 'mv', $text );
		$text = preg_replace( '/([аыоу])е/ui', '$1yı', $text );
		$text = preg_replace( '/([әеө])е/ui', '$1ye', $text );
		$text = preg_replace( '/ие/ui', 'ie', $text );
		$text = preg_replace( '/үе/u', 'üe', $text );
		// $text = preg_replace( '/ҮЕ/u', 'ÜE', $text );// KİENÜE
		// $text = preg_replace( '/Үе/u', 'Üe', $text );// KİENÜendə
		$text = preg_replace( '/юе/ui', 'yüe', $text );
		$text = preg_replace( '/([юу])ы/ui', '$1ı', $text );// укуы-uquı
		$text = preg_replace( '/(?<!и)ю([бвгджҗзклмнңпрстфхһцчшщ][әие])/ui', 'yü$1', $text );
		$text = preg_replace( '/(?<!и)ю([бвгджҗзклмнңпрстфхһцчшщ])ь/u', 'yü$1', $text );// юнь-yün
		$text = preg_replace( '/(?<!и)Ю([бвгджҗзклмнңпрстфхһцчшщ])ь/u', 'Yü$1', $text );// Юнь-Yün
		$text = preg_replace( '/(?<!и)я([бвгджҗзклмнңпрстфхһцчшщ][әие])/ui', 'yə$1', $text );
		$text = preg_replace( '/(?<!и)я([бвгджҗзклмнңпрстфхһцчшщ])ь/ui', 'yə$1', $text );
		$text = preg_replace( '/пауза/u', 'pauza', $text );
		$text = preg_replace( '/гатау/u', 'ğatau', $text );
		$text = preg_replace( '/казбек/u', 'kazbek', $text );
		$text = preg_replace( '/заказ/u', 'zakaz', $text );
		$text = preg_replace( '/лига/u', 'liga', $text );
		$text = preg_replace( '/гагауз/u', 'ğağauz', $text );// ğağawız?
		$text = preg_replace( '/обелен/u', 'obelen', $text );// гобелен-gobelen
		$text = preg_replace( '/оген/u', 'ogen', $text );// гоген-gogen
		$text = preg_replace( '/([аәя])[уү](?![бвгджҗзклмнңпрстфхһцчшщ]{2,2})/ui', '$1w', $text );
		$text = preg_replace( '/кама(?=га)/u', 'kama', $text );
		$text = preg_replace( '/каток/u', 'katok', $text );
		$text = preg_replace( '/төбәкара/u', 'tɵbəkara', $text );
		$text = preg_replace( '/бәкара/u', 'bəqara', $text );
		$text = preg_replace( '/щ[её]тка/u', 'şçotka', $text );
		$text = preg_replace( '/сч[её]т/u', 'sçot', $text );
		$text = preg_replace( '/[её]лка/u', 'yolka', $text );
		$text = preg_replace( '/к([аыоуъ])/u', 'q$1', $text );
		// $text = preg_replace( '/К([аыоуАЫОУ])/u', 'Q$1', $text );
		$text = preg_replace( '/газ/u', 'gaz', $text );
		$text = preg_replace( '/шпага/u', 'şpaga', $text );
		$text = preg_replace( '/багира/u', 'bagira', $text );
		$text = preg_replace( '/сть/u', 'st\'', $text );// власть-vlast'
		$text = preg_replace( '/ия(?=[бвгджҗзклмнңпрстфхһцчшщ]+[аы])/ui', 'iya', $text );
		$text = preg_replace( '/([өәү])я/ui', '$1yə', $text );// көя, җәя, гүя
		$text = preg_replace( '/ия/ui', 'iə', $text );// ия, революция
		$text = preg_replace( '/([өә])ю/ui', '$1yü', $text );
		$text = preg_replace( '/ию/ui', 'iü', $text );
		$text = preg_replace( '/гы(?=[бвгджҗзклмнңпрстфхһцчшщ]+[еә])/u', 'ğe', $text );// җәмгысе-cəmğese
		$text = preg_replace( '/г([аыуъ])/u', 'ğ$1', $text );
		// $text = preg_replace( '/Г([аыуАЫУ])/u', 'Ğ$1', $text );
		// $text = preg_replace( '/\bе([шл])/ui', 'yı$1', $text );
		// $text = preg_replace( '/\bЕ([шл])/ui', 'Yı$1', $text );
		$text = preg_replace( '/(\b|[ъь])е/u', 'ye', $text );
		$text = preg_replace( '/\bЕ/u', 'Ye', $text );// ЕРЭ
		// $text = preg_replace( '/([А-ЯӘӨҮҖҢҺ])Я\b/u', '$1YA', $text );// ТАКЫЯ-TAQIYA
		// $text = preg_replace( '/([А-ЯӘӨҮҖҢҺ])Е([А-ЯӘӨҮҖҢҺ])/u', '$1YE$2', $text );// ИМАМИЕВА-İMAMİYEVA
		// $text = preg_replace( '/([А-ЯӘӨҮҖҢҺ])Я([А-ЯӘӨҮҖҢҺ])/u', '$1YA$2', $text );// КЫЯМОВА-QIYAMOVA
		// these about capital chars are not needed if case is handled separately,
		// for that these are commented out
		$text = preg_replace( '/ый(?=[бвгджҗзклмнңпрстфхһцчшщ]+[ьә])/u', 'i$1'
			, $text );// мөстәкыйль-mɵstəqil,гыйффәт-ğiffət
		$text = preg_replace( '/ы(?=[бвгджҗзклмнңпрстфхһцчшщ][ьә])/u', 'e$1', $text );// бәгырь-bəğer
		// $text = preg_replace( '/([бвгджҗзклмнңпрстфхһцчшщ])ё/u', '$1\'o', $text );// шофёр-şof'or
		// $text = preg_replace( '/([бвгджҗзклмнңпрстфхһцчшщ])ё/u', '$1\yo', $text );// шофёр-şofyor
		// the second of the above is spelling by law/rules/plans, but this regex is not needed
		$text = preg_replace( '/тугрик/u', 'tugrik', $text );
		$text = preg_replace( '/друг/u', 'drug', $text );
		$text = preg_replace( '/([аыıоуяюАЫIОУЯЮ]й?)к(?!и)/u', '$1q', $text );// нократ-noqrat,барыйк-barıyq
		$text = preg_replace( '/([аыıуАЫIУ])г(?!о)/u', '$1ğ', $text );// дог-dog,геолог-geolog
		$text = preg_replace( '/([аыоуАЫОУ]\w*[яЯ])к/u', '$1q', $text );
		$text = preg_replace( '/([ао])у/u', '$1w', $text );// шоу-şow,ау-aw
		$text = preg_replace( '/ыять/u', 'iyət', $text );
		$text = preg_replace( '/ать/u', 'ət', $text );
		$text = preg_replace( '/оръән/ui', 'ör\'ən', $text );
		$text = preg_replace( '/го([бвгджҗзклмнңпрстфхһцчшщ]+)(?=[еә])/ui', 'ğö$1', $text );
		$text = preg_replace( '/гомум/ui', 'ğömum', $text );
		$text = preg_replace( '/авиа/ui', 'avia', $text );
		// $text='!'.$text;
		return parent::translate( $text, 'tt-latn' ) ;
	}
	function toupper( $text ) {
		$text=str_replace( 'i', 'İ', $text );
		$text=mb_strtoupper( $text );
		// $text=str_replace(array('I','ı'),array('İ','I'),$text);// i think
		// this would work without mb_internal_encoding('utf-8');
		return $text;
	}
	function tolower( $text ) {
		$text=str_replace( 'I', 'ı', $text );
		$text=mb_strtolower( $text );
		// $text=str_replace(array('i','İ'),array('ı','i'),$text);
		return $text;
	}
	function translate( $text, $toVariant ) {
		if ( $toVariant == 'tt-cyrl' ) {
			$text = preg_replace( '/I(?=\S*\.(ru|com|org|RU|COM|ORG))/', 'И', $text );
			// INTIRTAT.RU test.com/INTIRTIT
			// ->
			// ИНТИРТАТ.РУ тест.җом/ЫНТЫРТЫТ
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
			$text = preg_replace( '/([oaıu])y[ıIeE]/u', '$1е', $text );// боек,проект,аек
			$text = preg_replace( '/([öäeü])y[eE]/u', '$1е', $text );// бөек
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
			return parent::translate( $text, $toVariant );
		}elseif( $toVariant == 'tt-latn' ){
			// separating -{XINHUA}-s is not needed, they are already replaced with 00 bytes.
			$words = preg_split( '/\b/u', $text );
			$wordsCount = count($words);
			for ( $i=0; $i<$wordsCount; $i++ ){
				if ( 0===preg_match( '/[А-ЯЁӘӨҮҖҢҺа-яёәөүҗңһ]/u', $words[$i] ) ){
					continue;// no cyrillic
				}elseif ( preg_match( '/^[А-ЯЁӘӨҮҖҢҺ][а-яёәөүҗңһ]+$/u', $words[$i] ) ){
					$capitalisationType = 'FC';// first capital
				}elseif ( preg_match( '/^[а-яёәөүҗңһ\']+$/u', $words[$i] ) ){
					$capitalisationType = 'AL';// all low
				}elseif ( preg_match( '/^[А-ЯЁӘӨҮҖҢҺ]$/u', $words[$i] ) ){
					$capitalisationType = 'OC';// 1 upper/capital and nothing else
				}elseif ( preg_match( '/^[А-ЯЁӘӨҮҖҢҺ]{2,}$/u', $words[$i] ) ){
					$capitalisationType = 'AC';// all upper/capital
				}else {
					$capitalisationType = '';
				}
				$words[$i] = $this -> tolower( $words[$i] );
				$words[$i] = $this -> convertTatarFromCyrillicToLatin( $words[$i] );
				if ( $capitalisationType == 'FC' ){
					$words[$i] = $this -> toupper( mb_substr( $words[$i], 0, 1 ) ).mb_substr( $words[$i], 1 );
				}elseif( $capitalisationType == 'OC' ){
					if ( mb_strlen( $words[$i] ) == 1 ){
						$words[$i] = $this -> toupper( $words[$i] );
					}else{
						$words[$i] = $this -> toupper( mb_substr( $words[$i], 0, 1 ) ).mb_substr( $words[$i], 1 );
					}
				}elseif( $capitalisationType == 'AC' ){
					$words[$i] = $this -> toupper( $words[$i] );
				}
				// $words[$i]=$capitalisationType.$words[$i];
			}
			$text=join( $words );
		}
		// $text=str_replace('\'','0',$text);
		// $text=htmlspecialchars($text);
		// $text='***'.$text.'***';
		// file_put_contents('x.txt',$text);
		// $logfileqdb=fopen('x.txt','a');
		// fwrite($logfileqdb,$text);
		return $text;
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
