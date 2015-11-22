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
 * @author Dinar Qurbanov
 * @ingroup Language
 * this file is created by copying LanguageUzTest.php
 * some history of this file is in https://gerrit.wikimedia.org/r/#/c/164049/
 *
 */

require_once __DIR__ . '/../LanguageConverter.php';

/**
 * @ingroup Language
 */
class TtConverter extends LanguageConverter {
	public $toLatin = array(
		// capital letters are handled outside
		// ( outside of place this array was planned to use,
		// and is used , ie outside of parent::translate)
		'а' => 'a', // 'А' => 'A',
		'ә' => 'ä', // 'Ә' => 'Ä',
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
		'ө' => 'ö', // 'Ө' => 'Ö',
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
		// this letter was used in previous, 1999's, official latin alphabet
		// 'ə' => 'ә', 'Ə' => 'Ә',
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
		// '\'' => 'э', '’' => 'э',
		'\'' => 'ь', '’' => 'ь',
		// i have changed this, i think ь is more frequent
		// so, some regexes should be changed
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
		// ( outside of this function, outside during the process/ run of
		// code, it is handled in the function that calls this function)
		$text = preg_replace( '/онкурс/u', 'onkurs', $text );
		$text = preg_replace( '/едакц/u', 'edakts', $text );
		$text = preg_replace( '/втор/u', 'vtor', $text );
		$text = preg_replace( '/актив/u', 'aktiv', $text );
		$text = preg_replace( '/акт(?=\b|[бвгджҗзклмнңпрстфхһцчшщ])/u', 'akt', $text );
		$text = preg_replace( '/перманганат/u', 'permanganat', $text );
			$text = preg_replace( '/(?<=[уа])ев/u', 'yev', $text ); // uyev,ayev not uyıv,ayıv
		$text = preg_replace( '/(?<=е)в/u', 'v', $text ); // мочевина
		$text = preg_replace( '/ов/u', 'ov', $text );
		$text = preg_replace( '/ное/u', 'noye', $text );
		// $text = preg_replace( '/иев/u', 'iev', $text );
			$text = preg_replace( '/диван/u', 'diwan', $text );
			// additional indendation shows/ means that this rule
			// fixes disadvantages (would be) made by the next rule
		$text = preg_replace( '/ив/u', 'iv', $text );
		$text = preg_replace( '/вод/u', 'vod', $text );
		$text = preg_replace( '/ссылка/u', 'ssılka', $text );
		// $text = preg_replace( '/ассимиляция/u', 'assimilätsiä', $text );
		$text = preg_replace( '/музыка/u', 'muzıka', $text );
		$text = preg_replace( '/станок/u', 'stanok', $text );
		$text = preg_replace( '/самол[её]т/u', 'samolyot', $text );
		$text = preg_replace( '/ичка/u', 'içka', $text );
		$text = preg_replace( '/оллектив/u', 'ollektiv', $text );
		$text = preg_replace( '/(.+)ева/u', '$1eva', $text );
		$text = preg_replace( '/екабр/u', 'ekaber', $text ); // декабрь-dekaber-!deqaber
		$text = preg_replace( '/кукмара/u', 'kukmara', $text );
		$text = preg_replace( '/кушке/u', 'kuşke', $text );
		$text = preg_replace( '/куркин/u', 'kurkin', $text );
		$text = preg_replace( '/аргу/u', 'argu', $text );
		$text = preg_replace( '/курс/u', 'kurs', $text );
		$text = preg_replace( '/став/u', 'stav', $text );
		$text = preg_replace( '/ква/u', 'kva', $text ); // кварц
		$text = preg_replace( '/характер/u', 'xarakter', $text );
		$text = preg_replace( '/магнит/u', 'magnit', $text );
		$text = preg_replace( '/инвест/u', 'invest', $text );
		$text = preg_replace( '/проект/u', 'proyekt', $text );
		$text = preg_replace( '/гамет/u', 'gamet', $text );
		$text = preg_replace( '/вьет/u', 'vyet', $text );
		$text = preg_replace( '/роль/u', 'rol\'', $text );
		$text = preg_replace( '/вакуоль/u', 'vakuol\'', $text );
		$text = preg_replace( '/канал/u', 'kanal', $text );
		$text = preg_replace( '/\bорган/u', 'organ', $text ); // but maybe also orğan; торган
		$text = preg_replace( '/гольджи/u', 'gol\'dji', $text );
		$text = preg_replace( '/угле/u', 'ugle', $text );
		$text = preg_replace( '/молекул/u', 'molekul', $text );
		$text = preg_replace( '/кузмесь/u', 'kuzmes\'', $text );
		$text = preg_replace( '/известь/u', 'izvest\'', $text );
		$text = preg_replace( '/ерь/u', 'er\'', $text ); // синерь-siner'
		$text = preg_replace( '/аль/u', 'al\'', $text ); // коммуналь-kommunal'
		$text = preg_replace( '/арьер/u', 'aryer', $text );
		$text = preg_replace( '/омпьютер/u', 'ompyuter', $text );
		$text = preg_replace( '/оммуна/u', 'ommuna', $text );
			$text = preg_replace( '/никадәр/u', 'niqädär', $text );
		$text = preg_replace( '/ика/u', 'ika', $text ); // физика, математика, республика
		$text = preg_replace( '/онструк/u', 'onstruk', $text );
		// $text = preg_replace( '/АКШ/u', 'AQŞ', $text ); // capital
		// letters are handled outside for now
		$text = preg_replace( '/юхиди/u', 'yuxidi', $text );
		// $text = preg_replace( '/мәгариф/u', 'mäğärif', $text );
		// i think maybe this one should be left as it is, mäğarif,
		// because of rule of setting "a" in place of Arabic "alif".
		$text = preg_replace( '/музее/u', 'muzeyı', $text );
		$text = preg_replace( '/боул/u', 'bowl', $text );
		$text = preg_replace( '/волга/u', 'volga', $text );
		$text = preg_replace( '/коммун/u', 'kommun', $text );
		$text = preg_replace( '/юбилей/u', 'yubiley', $text );
		$text = preg_replace( '/юбилее/u', 'yubileyı', $text );
		$text = preg_replace( '/суфиян/u', 'sufiyan', $text );
		// $text = preg_replace( '/([бвгджҗзклмнңпрстфхһцчшщ])ю(?=\w+[әе])/ui',
		// '$1ü', $text ); // эволюциягә
		// $text = preg_replace( '/([бвгджҗзклмнңпрстфхһцчшщ])ю(?=\w+[аыо])/u',
			// '$1yu', $text ); // эволюцияга,люгдон
		$text = preg_replace( '/([бвгджҗзклмнңпрстфхһцчшщ])ю/u',
			'$1yu', $text ); // эволюцияга,люгдон
		// $text = preg_replace( '/([бвгджҗзклмнңпрстфхһцчшщ])ю/u', '$1ü', $text ); // эволюция
		// $text = preg_replace( '/волю/u', 'volü', $text );
		$text = preg_replace( '/вол/u', 'vol', $text );
			$text = preg_replace( '/тасвир/u', 'taswir', $text );
		$text = preg_replace( '/вир/u', 'vir', $text );
		// $text = preg_replace( '/ива/u', 'iva', $text ); // see ив
		$text = preg_replace( '/аватар/u', 'avatar', $text );
		// $text = preg_replace( '/Аватар/u', 'Avatar', $text );
		$text = preg_replace( '/никях/u', 'nikax', $text );
		$text = preg_replace( '/авари/u', 'avari', $text );
		$text = preg_replace( '/авто/u', 'avto', $text );
		$text = preg_replace( '/агент/u', 'agent', $text );
		$text = preg_replace( '/копия/u', 'kopiya', $text );
		$text = preg_replace( '/корея/u', 'koreya', $text );
		$text = preg_replace( '/кандидат/u', 'kandidat', $text );
		//$text = preg_replace( '/премьер/u', 'avto', $text );
		$text = preg_replace( '/калий/u', 'kaliy', $text );
		$text = preg_replace( '/акция/u', 'aktsiä', $text );
		$text = preg_replace( '/вигвам/u', 'vigvam', $text ); // вигвам
		$text = preg_replace( '/мья/u', 'm\'ya', $text ); // юмья
		$text = preg_replace( '/аэмай/u', 'a\'may', $text );
		$text = preg_replace( '/әэссорат/u', 'ä\'essorat', $text );
		// $text = preg_replace( '/әэсир/u', 'ä\'sir', $text );
		$text = preg_replace( '/әэ/u', 'ä\'', $text ); // тәэсир,тәэмин
		$text = preg_replace( '/әсьәлә/u', 'äs\'älä', $text );
		$text = preg_replace( '/коэ/u', 'koe', $text );
		// $text = preg_replace( '/гата/u', 'ğata\'', $text ); // гатауллин-ğata'ullin
		// the above rule was for a more correct representation of arabic orginal
		// but this is not pronounced so nowadays, so i comment it out,
		// so it will be spelled like in cyrillic
		$text = preg_replace( '/ризаэтдин/u', 'riza\'etdin', $text );
		$text = preg_replace( '/ельвиж/u', 'el\'vij', $text );
		$text = preg_replace( '/эль/u', 'el\'', $text );
			$text = preg_replace( '/дизель/u', 'dizel', $text ); // may be changed
			$text = preg_replace( '/(\w)ель(?=[бвгджҗзклмнңпрстфхһцчшщ]е)/u',
				'$1el', $text ); // двигательне // may be changed
			$text = preg_replace( '/гомель/u', 'gomel\'', $text );
		$text = preg_replace( '/(\w)ель/u', '$1el\'', $text );
		$text = preg_replace( '/камил/u', 'kamil', $text );
		$text = preg_replace( '/янил/u', 'yanil', $text );
		// $text = preg_replace( '/Янил/u', 'Yanil', $text );
		$text = preg_replace( '/тагил/u', 'tagil', $text );
		$text = preg_replace( '/лаксиха/u', 'laksixa', $text );
		$text = preg_replace( '/юлия(?=[бвгджҗзклмнңпрстфхһцчшщ]+[аы])/u', 'yuliya', $text );
		$text = preg_replace( '/юлия(?=[бвгджҗзклмнңпрстфхһцчшщ]+[әе])/u', 'yuliä', $text );
		$text = preg_replace( '/юлия\b/u', 'yuliä', $text );
		$text = preg_replace( '/екатерина/u', 'yekaterina', $text );
		// $text = preg_replace( '/юеш/u', 'yüeş', $text );
		$text = preg_replace( '/\bгае/u', 'ğäye', $text );
		$text = preg_replace( '/(\b|[ьъ])ягъ(?=[бвгджҗзклмнңпрстфхһцчшщ]и)/u',
			'yäğ', $text ); // ягъни
		$text = preg_replace( '/(?<=өкъ)га/u',
			'ğä', $text ); // рөкъга - رقعة‎‎
		// 2015 nov 16 : i made arab alifs with a, but that was wrong, in the latin
		// orthography that rule is/was removed.
		// remember not to change сагаеп
		// $text = preg_replace( '/\bГае/u', 'Ğäye', $text );
			// $text = preg_replace( '/гаять/u', 'ğayät', $text );
		$text = preg_replace( '/гая(?=[бвгджҗзклмнңпрстфхһцчшщ][еь])/u', 'ğäyä', $text );
			$text = preg_replace( '/гадәләт/u', 'ğadälät', $text );
			$text = preg_replace( '/гали/u', 'ğali', $text );
			$text = preg_replace( '/гади/u', 'ğadi', $text );
		$text = preg_replace(
			'/га(?=[бвгджҗзклмнңпрстфхһцчшщ][ьәеи])/u', 'ğä$1', $text ); // газиз
		// $text = preg_replace( '/га([])ә/u', 'ğä$1ä', $text );
		// may change: кадәр:
		// belderü yasadı: ” İlemä häm millätemä xezmät
		// itü yulında , bügenge köngä qädär bulğanı kebek
		// may change: кабер, калеб
		$text = preg_replace( '/\bка([бвгджҗзклмнңпрстфхһцчшщ])([еә])/u', 'qä$1$2', $text );
		// $text = preg_replace( '/эко/u', 'eko', $text );
		// $text = preg_replace( '/Эко/u', 'Eko', $text );
		$text = preg_replace( '/эво/u', 'evo', $text );
		// $text = preg_replace( '/Эво/u', 'Evo', $text );
		$text = preg_replace( '/(?<=[оауыи])езд/u', 'yezd', $text ); // поезд-poyezd
		$text = preg_replace( '/брь/u', 'ber', $text ); // ноябрь-noyaber
			$text = preg_replace( '/\bефрат/u', 'yefrat', $text );
			$text = preg_replace( '/премьер/u', 'premyer', $text );
		$text = preg_replace( '/(?<=\b|ь)е([бвгджҗзклмнңпрстфхһцчшщ]+)(?=[уаы]|\b)/u',
			'yı$1', $text ); // егу-yığu, күпьеллык
		// (?<=\b|ь)е
		// $text = preg_replace( '/\bЕ([бвгджҗзклмнңпрстфхһцчшщ]+(?=([уаы]|\b)))/u',
		// 'Yı$1', $text );
		$text = preg_replace( '/канәг/u', 'qänäğ', $text );
		// $text = preg_replace( '/Канәг/u', 'Qänäğ', $text );
		$text = preg_replace( '/әккыя/u', 'äqqiä', $text );
		$text = preg_replace( '/әккы/u', 'äqqi', $text );
		$text = preg_replace( '/карават/u', 'karawat', $text );
		$text = preg_replace( '/камаз/u', 'kamaz', $text );
		// $text = preg_replace( '/коряга/u', 'kor\'aga', $text );
		$text = preg_replace( '/зигзаг/u', 'zigzag', $text );
		$text = preg_replace( '/тюк/u', 'tyuk', $text );
		// $text = preg_replace( '/жив/u', 'jiv', $text ); // see ив
		$text = preg_replace( '/дөнья/u', 'dönya', $text );
		$text = preg_replace( '/([бвгджҗзклмнңпрстфхһцчшщ])я/u', '$1ya', $text ); // варяг
		$text = preg_replace( '/вагон/u', 'vagon', $text ); // see line 370 preg_replace(
			// '/г([аыуъо])/u', 'ğ$1', $text ); // госман
		// make exceptions (васыять, вәкил, вәзгыять, вәли, веб, вак)
		// then all first в to v (вагон, виза, версия, вариант, визит)
			$text = preg_replace( '/\bвеб/u', 'web', $text );
			$text = preg_replace( '/\bвак/u', 'waq', $text );
			$text = preg_replace( '/\bвасыя/u', 'wasiä', $text );
			$text = preg_replace( '/\bвазыйфа/u', 'wazıyfa', $text );
			$text = preg_replace( '/\bвә/u', 'wä', $text );
			$text = preg_replace( '/\bватан/u', 'watan', $text );
		$text = preg_replace( '/\bв/u', 'v', $text );
		$text = preg_replace( '/двигат/u', 'dvigat', $text ); // двигатель
		$text = preg_replace( '/(.[өә].а)е/u', '$1ye', $text ); // мөлаем, мөгаен, тәгаен
		$text = preg_replace( '/гомео/u', 'gomeo', $text );
		// $text = preg_replace( '/Гомео/u', 'Gomeo', $text );
		$text = preg_replace( '/аугли/u', 'awgli', $text ); // маугли-mawgli
		$text = preg_replace( '/ияз/u', 'iyaz', $text );
		$text = preg_replace( '/\bрия/u', 'riya', $text ); // рия-рийа; метохондрия
		$text = preg_replace( '/указ/u', 'ukaz', $text );
		$text = preg_replace( '/мв/u', 'mv', $text );
		$text = preg_replace( '/([аыоу])е/u', '$1yı', $text );
		$text = preg_replace( '/([әеө])е/u', '$1ye', $text );
		$text = preg_replace( '/ие/u', 'ie', $text );
		$text = preg_replace( '/үе/u', 'üe', $text );
		$text = preg_replace( '/юе/u', 'yüe', $text );
		$text = preg_replace( '/([юу])ы/u', '$1ı', $text ); // укуы-uquı
		$text = preg_replace( '/(?<!и)ю([бвгджҗзклмнңпрстфхһцчшщ][әие])/u', 'yü$1', $text );
		$text = preg_replace( '/(?<!и)ю([бвгджҗзклмнңпрстфхһцчшщ])ь/u', 'yü$1', $text ); // юнь-yün
		$text = preg_replace( '/(?<!и)Ю([бвгджҗзклмнңпрстфхһцчшщ])ь/u', 'Yü$1', $text ); // Юнь-Yün
		$text = preg_replace( '/(?<!и)я([бвгджҗзклмнңпрстфхһцчшщ]+[әиеь])/u',
			'yä$1', $text ); // яшь, ярдәм
		// $text = preg_replace( '/(?<!и)я([бвгджҗзклмнңпрстфхһцчшщ])ь/u', 'yä$1', $text ); // яшь
		$text = preg_replace( '/пауза/u', 'pauza', $text );
		$text = preg_replace( '/гатау/u', 'ğatau', $text );
		$text = preg_replace( '/казбек/u', 'kazbek', $text );
		$text = preg_replace( '/заказ/u', 'zakaz', $text );
		$text = preg_replace( '/лига/u', 'liga', $text );
		$text = preg_replace( '/пакет/u', 'paket', $text );
		$text = preg_replace( '/гагауз/u', 'ğağauz', $text ); // ğağawız?
		$text = preg_replace( '/обелен/u', 'obelen', $text ); // гобелен-gobelen
		$text = preg_replace( '/оген/u', 'ogen', $text ); // гоген-gogen
		$text = preg_replace( '/([аәя])[уү](?![бвгджҗзклмнңпрстфхһцчшщ]{2,2})/u', '$1w', $text );
		$text = preg_replace( '/кама(?=га)/u', 'kama', $text );
		$text = preg_replace( '/каток/u', 'katok', $text );
		$text = preg_replace( '/төбәкара/u', 'töbäkara', $text );
		$text = preg_replace( '/бәкара/u', 'bäqara', $text );
		$text = preg_replace( '/щ[её]тка/u', 'şçotka', $text );
		$text = preg_replace( '/сч[её]т/u', 'sçot', $text );
		$text = preg_replace( '/[её]лка/u', 'yolka', $text );
		$text = preg_replace( '/эукариот/u', 'eukariot', $text );
			$text = preg_replace( '/куә/u', 'qüä', $text ); // ка,ко,кы,ку,къ
				$text = preg_replace( '/башкорт/u', 'başqort', $text );
				$text = preg_replace( '/аккош/u', 'aqqoş', $text );
				$text = preg_replace( '/аскорма/u', 'asqorma', $text );
			$text = preg_replace( '/(?<=\w)ко/u', 'ko', $text ); // микология, экология
			$text = preg_replace( '/кабина/u', 'kabina', $text ); // ка,ко,кы,ку,къ
			// $text = preg_replace( '/\bка\b/u', 'ka', $text ); // Ka-22
			// fixed ka-22 with -{}- , qa is needed as tatar suffix after digits
			$text = preg_replace( '/куфи/u', 'kufi', $text );
		$text = preg_replace( '/к([аыоуъ])/u', 'q$1', $text ); // ка,ко,кы,ку,къ
		$text = preg_replace( '/газ/u', 'gaz', $text );
		$text = preg_replace( '/шпага/u', 'şpaga', $text );
		$text = preg_replace( '/багира/u', 'bagira', $text );
		$text = preg_replace( '/сть/u', 'st\'', $text ); // власть-vlast'
		$text = preg_replace( '/ия(?=[бвгджҗзклмнңпрстфхһцчшщ]+[аы])/u', 'iya', $text );
		$text = preg_replace( '/([өәү])я/u', '$1yä', $text ); // көя, җәя, гүя
		$text = preg_replace( '/ия/u', 'iä', $text ); // ия, революция
		$text = preg_replace( '/([өә])ю/u', '$1yü', $text );
		$text = preg_replace( '/ию/u', 'iü', $text );
		$text = preg_replace( '/гы(?=[бвгджҗзклмнңпрстфхһцчшщ]+[еә])/u',
			'ğe', $text ); // җәмгысе-cämğese
		$text = preg_replace( '/гомум/u', 'ğömum', $text ); // may change
		$text = preg_replace( '/го(?=[бвгджҗзклмнңпрстфхһцчшщ]+[еә])/u', 'ğö$1', $text ); // гомер
			// $text = preg_replace( '/гый/u', 'ği', $text ); // катгый, гыйффәт, иҗтимагый
			$text = preg_replace( '/иҗтимагый/u', 'ictimaği', $text );
// ictimaği : see
// https://tt.wikipedia.org/wiki/%D0%A4%D0%B0%D0%B9%D0%BB:Tatar_telenen_orfografiase_10.jpg
			$text = preg_replace( '/тангаж/u', 'tangaj', $text );
			$text = preg_replace( '/горизонт/u', 'gorizont', $text );
			$text = preg_replace( '/гоа/u', 'goa', $text );
			$text = preg_replace( '/гоби/u', 'gobi', $text );
			$text = preg_replace( '/го(?=га|да|$)/u', 'go', $text ); // need also fix гога etc
			$text = preg_replace( '/горилла/u', 'gorilla', $text );
			$text = preg_replace( '/гол/u', 'gol', $text );
			$text = preg_replace( '/штутгард/u', 'ştutgard', $text );
			$text = preg_replace( '/ыять/u', 'iät', $text );
		$text = preg_replace( '/г([аыуъо])/u', 'ğ$1', $text ); // госман
		// $text = preg_replace( '/\bе([шл])/u', 'yı$1', $text );
		$text = preg_replace( '/(\b|[ъь])е/u', 'ye', $text );
		$text = preg_replace( '/ый(?=[бвгджҗзклмнңпрстфхһцчшщ]+[ьәе])/u', 'i$1',
			$text ); // мөстәкыйль-möstäqil,гыйффәт-ğiffät,гыйлем
		$text = preg_replace( '/ы(?=[бвгджҗзклмнңпрстфхһцчшщ][ьә])/u',
			'e$1', $text ); // бәгырь-bäğer
		// $text = preg_replace( '/([бвгджҗзклмнңпрстфхһцчшщ])ё/u',
		// '$1\'o', $text ); // шофёр-şof'or
		// $text = preg_replace( '/([бвгджҗзклмнңпрстфхһцчшщ])ё/u',
		// '$1\yo', $text ); // шофёр-şofyor
		// the second of the above is spelling by law/rules/plans, but this regex is not needed
			$text = preg_replace( '/продукт/u', 'produkt', $text );
			$text = preg_replace( '/крыл/u', 'krıl', $text ); // винтокрыл
		$text = preg_replace( '/([аыıоуяю]й?)к(?![иь])/u',
			'$1q', $text ); // нократ-noqrat,барыйк-barıyq,пакьстан-pakstan
			$text = preg_replace( '/тугрик/u', 'tugrik', $text );
			$text = preg_replace( '/друг/u', 'drug', $text );
			$text = preg_replace( '/оглу/u', 'oğlu', $text );
		// $text = preg_replace( '/([аыıу])г(?!о)/u', '$1ğ', $text ); // дог-dog,геолог-geolog
		$text = preg_replace( '/([аыу])г/u', '$1ğ', $text ); // туглан, баглан
		$text = preg_replace( '/([аыоу]\w*[яЯ])к/u', '$1q', $text );
			// $text = preg_replace( '/ау-30/u', 'au-30', $text ); // does not work because
			// hyphen is not included in words. workaround for now: -{Au-30}-
			$text = preg_replace( '/грави/u', 'gravi', $text );
		$text = preg_replace( '/([ао])у/u', '$1w', $text ); // шоу-şow,ау-aw
		$text = preg_replace( '/ать/u', 'ät', $text );
		$text = preg_replace( '/оръән/u', 'ör\'än', $text );
		$text = preg_replace( '/гомум/u', 'ğömum', $text );
		$text = preg_replace( '/авиа/u', 'avia', $text );
		// $text='!'.$text;
		// $text = '('. $text. ')';
		return parent::translate( $text, 'tt-latn' );
	}
	function convertTatarFromLatinToCyrillic( $text ) {
		$text = preg_replace( '/I(?=\S*\.(ru|com|org|RU|COM|ORG))/', 'И', $text );
		// INTIRTAT.RU test.com/INTIRTIT
		// ->
		// ИНТИРТАТ.РУ тест.җом/ЫНТЫРТЫТ
		$text = preg_replace( '/iyül/u', 'июль', $text );
		// $text = preg_replace( '/yere/u', 'ерэ', $text ); // see line
			// 590 if( $letter=='е' && $j > 0 ){ ...
		$text = preg_replace( '/yabr/u', 'ябрь', $text );
		$text = preg_replace( '/fäqät/u', 'фәкать', $text );
		$text = preg_replace( '/kopia/u', 'копия', $text );
		$text = preg_replace( '/tsiä/u', 'ция', $text ); // ассимиляция, инвестиция
		$text = preg_replace( '/abl(?!e)/u', 'абль', $text ); // дирижабль
		$text = preg_replace( '/dizel/u', 'дизель', $text );
		$text = preg_replace( '/frants/u', 'франц', $text );
		$text = preg_replace( '/gravitats/u', 'гравитац', $text );
		// $text = preg_replace( '/koe/u', 'коэ', $text );
		// $text = preg_replace( '/aero/u', 'аэро', $text );
		$text = preg_replace( '/([ao])e/u', '$1э', $text );
		$text = preg_replace( '/breyk/u', 'брэйк', $text );
		$text = preg_replace( '/krek/u', 'крэк', $text );
		$text = preg_replace( '/planyor/u', 'планёр', $text );
		$text = preg_replace( '/konstrukts/u', 'конструкц', $text );
		$text = preg_replace( '/dvigatel/u', 'двигатель', $text );
		// $text = preg_replace( '/dönya/u', 'дөнья', $text );
			$text = preg_replace( '/hakan/u', 'хакан', $text ); // Hakan Fidan
		$text = preg_replace(
			'/(?<=a)k(?![bcçdfghjklmnñpqrsştvwxyz]*[ei])/u',
			'кь',
			$text ); // пакьстан, пакеты, актив
		$text = preg_replace( '/nyaq/u', 'ньяк', $text );
		$text = preg_replace( '/aqyeget/u', 'акъегет', $text );
		// $text = preg_replace( '/ателье/u', 'акъегет', $text );
		$text = preg_replace( '/material/u', 'материал', $text );
			// $text = preg_replace( '/xalıq/u', 'халык', $text );
		$text = preg_replace( '/natural\'/u', 'натураль', $text );
		$text = preg_replace( '/gorizontal\'/u', 'горизонталь', $text );
		$text = preg_replace( '/vertikal\'/u', 'вертикаль', $text );
		$text = preg_replace( '/(?<=[bcçdfghjklmnñpqrsştvwxyz])el\'/u', 'ель', $text ); // ателье
		$text = preg_replace( '/proportsional\'/u', 'пропорциональ', $text );
		$text = preg_replace( '/aği/u', 'агый', $text );
		$text = preg_replace( '/revolyu/u', 'револю', $text );
		$text = preg_replace( '/evolyu/u', 'эволю', $text );
		$text = preg_replace( '/yudjet/u', 'юджет', $text );
		$text = preg_replace( '/yolka/u', 'ёлка', $text );
		$text = preg_replace( '/şçotka/u', 'щётка', $text );
		$text = preg_replace( '/sçot/u', 'счёт', $text );
		$text = preg_replace( '/qör\'än/u', 'коръән', $text ); // was: корэән
		$text = preg_replace( '/mäs\'älän/u', 'мәсьәлән', $text );
		$text = preg_replace( '/alyuta/u', 'алюта', $text );
		$text = preg_replace( '/assimilya/u', 'ассимиля', $text );
		$text = preg_replace( '/tsiya/u', 'ция', $text );
		$text = preg_replace( '/ğtibar/u', 'гътибар', $text );
		$text = preg_replace( '/tä\'/u', 'тәэ', $text ); // тәэмин, тәэсир
		$text = preg_replace( '/ma\'/u', 'маэ', $text ); // маэмай
		$text = preg_replace( '/qiyät/u', 'кыять', $text );
		$text = preg_replace( '/\bts/u', 'ц', $text ); // цетнер
		$text = preg_replace( '/ğiä/u', 'гыя', $text );
		// $text = preg_replace( '/ği/u', 'гый', $text );
		// $text = preg_replace( '/Ği/u', 'Гый', $text );
		$text = preg_replace(
			'/(?<=[aıouei][bcçdfghjklmnñpqrsştvwxyz])\'(?=[bcçdfghjklmnñpqrsştvwxyz][aıouei])/u',
			'ь', $text ); // yum'ya, el'vira
		// $text = preg_replace(
			// '/([aıouei][bcçdfghjklmnñpqrsştvwxyz])\'([bcçdfghjklmnñpqrsştvwxyz][aıouei])/u',
			// '$1ь$2', $text ); // yum'ya, el'vira
			// just an alternative regex instead of previous
		// $text = preg_replace( '/yum\'ya/u', 'юмья', $text );
		// $text = preg_replace( '/el\'vira/u', 'эльвира', $text );
		// ^ alternative regexes instead of previous
		$text = preg_replace( '/\be/u', 'э', $text );
		$text = preg_replace( '/\bE/u', 'Э', $text );
		// $text = str_replace( 'ye', 'е', $text );
		// $text = str_replace( 'Ye', 'Е', $text );
		// $text = str_replace( 'YE', 'Е', $text );
		// $text = preg_replace( '/([bvgdjzyklmnprstfxcwqh])e/u', '$1е', $text );
		$text = preg_replace( '/([bvgdjzklmnprstfx])y[eE]/u', '$1ье', $text );
		$text = preg_replace( '/([oaıu])y[ıIeE]/u', '$1е', $text ); // боек,проект,аек
		$text = preg_replace( '/([äeiöü])y[eE]/u', '$1е', $text ); // бөек
		$text = preg_replace( '/\by[ıIeE]/u', 'е', $text );
		$text = preg_replace( '/\bY[ıIeE]/u', 'Е', $text );
		$text = preg_replace( '/(?<=[ğ][äeiöü])y[äә]([bcçdfghjklmnñpqrsştvwxyz])$/u',
			'я$1ь', $text ); // гаять бәян
		$text = preg_replace( '/([äöüeiaouı])y[aä]/u', '$1я', $text ); // куярга
		$text = preg_replace( '/y[äa]ğ/u', 'ягъ', $text ); // ягъни
			$text = preg_replace( '/yärdäm/u', 'ярдәм', $text );
		// $text = preg_replace( '/\byä([bcçdfghjklmnñpqrsştvwxyz])(?![äöüeiaouı])/u',
			// 'я$1ь', $text ); // яшь
		// $text = preg_replace( '/\byü([bcçdfghjklmnñpqrsştvwxyz])(?![äöüeiaouı])/u',
			// 'я$1ь', $text ); // юнь
		$text = preg_replace( '/(?<=\by[äü][bcçdfghjklmnñpqrsştvwxyz])(?![äöüeiaouı])/u',
			'ь', $text ); // яшь, юнь
		$text = preg_replace( '/\by[äәa]/u', 'я', $text );
		$text = preg_replace( '/aw\b/u', 'ау', $text );
		$text = preg_replace( '/aw(?=[bcçdfghjklmnñpqrsştvwxyz])/u', 'ау', $text );
		$text = preg_replace(
			'/äw(?=([bcçdfghjklmnñpqrsştvwxyz]|\b))/u', 'әү', $text ); // яшәүче, тәвәккәл
		$text = preg_replace(
			'/([äeiöü][bcçdfghjklmnñpqrsştvwxyz])y[äәa]/u', '$1ья', $text ); // дөнья
			$text = preg_replace( '/varyag/u', 'варяг', $text );
			// $text = preg_replace( '/yum\'ya/u', 'юмья', $text );
			// $text = preg_replace( '/el\'vira/u', 'эльвира', $text );
		$text = preg_replace(
			'/([aıou][bcçdfghjklmnñpqrsştvwxyz])y[äәa]/u', '$1ъя', $text ); // ташъязма
		$text = preg_replace( '/\by[uü]/u', 'ю', $text );
		$text = preg_replace( '/(?<=[äöüeiaouı])y[uü]/u', 'ю', $text ); // каюм
		// $text = preg_replace( '/\bY[uü]/u', 'Ю', $text );
		$text = preg_replace(
			'/ğ[äә]([bcçdfghjklmnñpqrsştvwxyz])(?![äәei])/u',
			'га$1ь',
			$text ); // кәгазь, мөрәҗәгатен, гамәл, мәгариф
		$text = preg_replace( '/ğ[äә]/u', 'га', $text );
		$text = preg_replace( '/ğö/u', 'го', $text ); // гомер
			$text = preg_replace( '/tuğla/u', 'тугла', $text );
		$text = preg_replace( '/uğ(?=[bcçdfghjklmnñpqrsştvwxyz])/u', 'угъ', $text ); // тугъры
		// $text = preg_replace( '/Ğ[äÄәÄ]/u', 'Га', $text );
		$text = preg_replace( '/q[äә]/u', 'ка', $text );
		$text = preg_replace( '/qü/u', 'ку', $text );
		// $text = preg_replace( '/Q[äÄәÄ]/u', 'Ка', $text );
			$text = preg_replace( '/ğe([bcçdfghjklmnñpqrsştvwxyz])$/u', 'гы$1ь', $text ); // шөгыль
		// $text = preg_replace( '/(?<=[äeiöü])ğ(?![äeöü])/u', 'гъ', $text ); // нәстәгъликъ, шөгыль
		$text = preg_replace( '/(?<=[äeiöü])ğ/u', 'гъ', $text ); // нәстәгъликъ
			$text = preg_replace( '/qa/u', 'ка', $text ); // wäqalät-вәкаләт
			$text = preg_replace( '/qqiät(?!ei)/u', 'ккыять', $text ); // тәрәккыять
			$text = preg_replace(
				'/(?<=[äeiöü])q(?=qi)/u', 'к', $text ); //тәрәккыяви
		$text = preg_replace(
			'/(?<=[äeiöü])q/u', 'къ', $text ); // икътисадый, wäqalät-вәкаләт, тәрәккыять
		// $text = preg_replace(
			// '/(?<=[äeiöü])q(?!([bcçdfghjklmnñpqrsştvwxyz]+(a|iyä)))/u',
			// 'къ', $text ); // икътисадый, wäqalät-вәкаләт, тәрәккыять
		$text = preg_replace( '/i[äә]/u', 'ия', $text );
		$text = preg_replace( '/iü/u', 'ию', $text );
		$text = preg_replace( '/y[aä]/u', 'я', $text );
		$text = preg_replace( '/ye/u', 'е', $text );
		$text = parent::translate( $text, 'tt-cyrl' );
		// $text = '('. $text. ')';
		return $text;
	}
	function toUpper( $text ) {
		$text = str_replace( 'i', 'İ', $text );
		$text = mb_strtoupper( $text );
		// $text=str_replace(array('I','ı'),array('İ','I'),$text); // i think
		// this would work without mb_internal_encoding('utf-8');
		return $text;
	}
	function toLower( $text ) {
		$text = str_replace( 'I', 'ı', $text );
		$text = mb_strtolower( $text );
		// $text=str_replace(array('i','İ'),array('ı','i'),$text);
		return $text;
	}
	function replaceIntoArray( $pattern, $replacement, $subject, &$target ) {
		$patternCount = preg_match_all(
			$pattern, $subject, $matches, PREG_OFFSET_CAPTURE
		);
		if ( $patternCount > 0 ) {
			for ( $i = 0; $i < $patternCount; $i++ ){
				$target[$matches[0][$i][1]] = $replacement;
			}
		}
	}
	function translate( $text, $toVariant ) {
		if ( $toVariant == 'tt-cyrl' ) {
			// separating -{XINHUA}-s is not needed, they are already replaced with 00 bytes.
			$nw = '[^0-9a-zA-ZäəÄƏöɵÖƟüÜıİşŞçÇñÑğĞ\']';
			$w = '[0-9a-zA-ZäəÄƏöɵÖƟüÜıİşŞçÇñÑğĞ\']';
			$words = preg_split( "/(?<=$w)(?=$nw)|(?<=$nw)(?=$w)/u", $text );
			// $words = preg_split( '/(?<=[aryn])(?= )|(?<= )(?=[tmq])/u', $text );
			// $words = preg_split( '/\b/u', $text );
			// $words = preg_split( '/(\b(?!\'))|((?<=\')(?=\W))/u', $text );
			// $words = preg_split( '/(?=[^0-9a-zA-ZäəÄƏöɵÖƟüÜıİşŞçÇñÑğĞ\'])(?<=[0-9a-zA-ZäəÄƏöɵÖƟüÜıİşŞçÇñÑğĞ\'])/u', $text );
			// $words = preg_split( '/\b(?!\')|(?<=[\w\'])/u', $text );
			// $words = preg_split( '/(?<!\w)(?=\w)|(?<=[\w\'])(?![\w^\'])/u', $text );
			// $words = preg_split( '/((?<=\W)(?=\w))|((?<=[\w\'])(?!\w))/u', $text );
			// $words = preg_split( '/(?<=[\w\'])(?!\w)/u', $text );
			$wordsCount = count( $words );
			for ( $i = 0; $i < $wordsCount; $i++ ) {
				$sourceLetterCase = array();
				if ( 0 === preg_match( '/[a-zA-ZäəÄƏöɵÖƟüÜıİşŞçÇñÑğĞ\']/u', $words[$i] ) ) {
					continue; // no latin
				} elseif ( preg_match( '/^[A-ZÄƏÖƟÜİŞÇÑĞ][a-zäəöɵüışçñğ\']+$/u', $words[$i] ) ){
					$capitalisationType = 'FC'; // first capital
				} elseif ( preg_match( '/^[a-zäəöɵüışçñğ\']+$/u', $words[$i] ) ){
					$capitalisationType = 'AL'; // all low
				} elseif ( preg_match( '/^[A-ZÄƏÖƟÜİŞÇÑĞ\']$/u', $words[$i] ) ){
					$capitalisationType = 'OC'; // only 1 letter and it is upper/capital
				// } elseif (
					// i comment this out because style of writing all with upper case while
					// it is not an abbreviation is probably not used in wikipedia
					// preg_match( '/^[А-ЯЁӘӨҮҖҢҺ]{2,}$/u', $words[$i] )
					// && 0===preg_match( '/^ЕРЭ$/u', $words[$i] )
				// ){
					// $capitalisationType = 'AC'; // all upper/capital
				} else {
					$tmp=$words[$i];
					$tmp=preg_replace('/Ye/u','Y',$tmp);
					$capitalisationType = '';
					// $sourceLetterCase = array();
					$this->replaceIntoArray(
						'/[a-zäəöɵüışçñğ\'0-9]/u', 'l', $tmp, $sourceLetterCase );
					$this->replaceIntoArray(
						'/[A-ZÄƏÖƟÜİŞÇÑĞ]/u', 'u', $tmp, $sourceLetterCase
					);
					// $this -> replaceIntoArray(
						// '/[еёяюцщ]/u', 'll', $words[$i], $sourceLetterCase);
					// $this -> replaceIntoArray(
						// '/[ЕЁЯЮЦЩ]/u', 'ul', $words[$i], $sourceLetterCase);
					// $this -> replaceIntoArray(
						// '/[ьЬъЪ]/u', '', $words[$i], $sourceLetterCase);
					ksort( $sourceLetterCase );
					$targetLetterCase = implode( $sourceLetterCase );
				}
				$words[$i] = $this->toLower( $words[$i] );
				$words[$i] = $this->convertTatarFromLatinToCyrillic( $words[$i] );
				// $words[$i] = implode( $sourceLetterCase ); // uncomment to see target cases
				if ( $capitalisationType == 'FC' ) {
					$words[$i] =
						mb_strtoupper( mb_substr( $words[$i], 0, 1 ) )
						. mb_substr( $words[$i], 1 );
				} elseif ( $capitalisationType == 'OC' ) {
					$words[$i] = mb_strtoupper( $words[$i] );
					// if ( mb_strlen( $words[$i] ) == 1 ){
						// $words[$i] = mb_strtoupper( $words[$i] );
					// }else{
						// $words[$i] =
							// mb_strtoupper( mb_substr( $words[$i], 0, 1 ) )
							// . mb_substr( $words[$i], 1 );
					// }
				// } elseif( $capitalisationType == 'AC' ){
					// $words[$i] = $this -> toUpper( $words[$i] );
				} elseif ( $capitalisationType == '' ) {
					$targetLength = mb_strlen( $targetLetterCase );
					$targetWord = '';
					for ( $j = 0; $j < $targetLength; $j++ ) {
						$letter = mb_substr( $words[$i], $j, 1 );
						if ( mb_substr( $targetLetterCase, $j, 1 ) == 'u' ) {
							if( $letter=='е' && $j > 0 ){
								$letter='э'; // ЕРЭ
							}
							$targetWord .= mb_strtoupper( $letter );
						} else {
							$targetWord .= $letter;
						}
					}
					$words[$i] = $targetWord;
				}
				// $words[$i]=$capitalisationType.$words[$i];
			}// i
			$text = join( $words );
		} elseif ( $toVariant == 'tt-latn' ) {
			// separating -{XINHUA}-s is not needed, they are already replaced with 00 bytes.
			// $words = preg_split( '/\b/u', $text );
			$words = preg_split(
				'/(?<![А-ЯЁӘӨҮҖҢҺа-яёәөүҗңһ])(?=[А-ЯЁӘӨҮҖҢҺа-яёәөүҗңһ])|'.
				'(?<=[А-ЯЁӘӨҮҖҢҺа-яёәөүҗңһ])(?![А-ЯЁӘӨҮҖҢҺа-яёәөүҗңһ])/u',
				$text );
			$wordsCount = count( $words );
			for ( $i = 0; $i < $wordsCount; $i++ ) {
				$sourceLetterCase = array();
				if ( 0 === preg_match( '/[А-ЯЁӘӨҮҖҢҺа-яёәөүҗңһ]/u', $words[$i] ) ){
					continue; // no cyrillic
				} elseif ( preg_match( '/^[А-ЯЁӘӨҮҖҢҺ][а-яёәөүҗңһ]+$/u', $words[$i] ) ){
					$capitalisationType = 'FC'; // first capital
				} elseif ( preg_match( '/^[а-яёәөүҗңһ\']+$/u', $words[$i] ) ){
					$capitalisationType = 'AL'; // all low
				} elseif ( preg_match( '/^[А-ЯЁӘӨҮҖҢҺ]$/u', $words[$i] ) ){
					$capitalisationType = 'OC'; // only 1 letter and it is upper/capital
				// }elseif (
					// i comment this out because style of writing all with upper case while
					// it is not an abbreviation is probably not used in wikipedia
					// preg_match( '/^[А-ЯЁӘӨҮҖҢҺ]{2,}$/u', $words[$i] )
					// && 0===preg_match( '/^ЕРЭ$/u', $words[$i] )
				// ){
					// $capitalisationType = 'AC';// all upper/capital
				} else {
					$capitalisationType = '';
					// $sourceLetterCase = array();
					$this->replaceIntoArray(
						'/[а-хчшыэәөүҗңһ]/u', 'l', $words[$i], $sourceLetterCase );
						// digits also need not to be capitalised
					$this->replaceIntoArray(
						'/[А-ХЧШЫЭӘӨҮҖҢҺ]/u', 'u', $words[$i], $sourceLetterCase
					);
					$this->replaceIntoArray(
						'/[еёяюцщ]/u', 'll', $words[$i], $sourceLetterCase );
					$this->replaceIntoArray(
						'/[ЕЁЯЮЦЩ]/u', 'ul', $words[$i], $sourceLetterCase );
					$this->replaceIntoArray(
						'/[ьЬъЪ]/u', '', $words[$i], $sourceLetterCase );
					// $this->replaceIntoArray(
						// '/[0-9]/u', '-', $words[$i], $sourceLetterCase );
					/*
					// i comment these out because style of writing all with upper case while
					// it is not an abbreviation is probably not used in wikipedia
					$this -> replaceIntoArray(
						'/(?<=[уУ])е(?=[вВ])/u', 'll', $words[$i], $sourceLetterCase);
					$lcCons='бвгджҗзклмнңпрстфхһцчшщ';
					$ucCons=mb_strtoupper('бвгджҗзклмнңпрстфхһцчшщ');
					$cons=$lcCons.$ucCons;
					$this -> replaceIntoArray(
						'/(?<=['. $cons. '])ю(?=\w+[аыоАЫО])/u',
						'-l', $words[$i], $sourceLetterCase
					);
					$this -> replaceIntoArray(
						'/(?<=['. $cons. '])Ю(?=\w+[аыоАЫО])/u',
						'-u', $words[$i], $sourceLetterCase
					);
					$this -> replaceIntoArray( '/(?<=ы)й(?=[бвгджҗзклмнңпрстфхһцчшщ]+[ьә])/ui', ''
						, $words[$i], $sourceLetterCase ); // мөстәкыйль-möstäqil,гыйффәт-ğiffät
					$this -> replaceIntoArray( '/(?<=\b)Е(?=РЭ)/u', 'ul'
						, $words[$i], $sourceLetterCase ); // ЕРЭ
					$this -> replaceIntoArray( '/(?<=\b|[ъь])е/u', 'll'
						, $words[$i], $sourceLetterCase );
					$this -> replaceIntoArray( '/(?<=\b|[ъь])Е/u', 'uu'
						, $words[$i], $sourceLetterCase );*/
					ksort( $sourceLetterCase );
					$targetLetterCase = implode( $sourceLetterCase );
				}
				$words[$i] = $this->toLower( $words[$i] );
				$words[$i] = $this->convertTatarFromCyrillicToLatin( $words[$i] );
				// $words[$i] = implode( $sourceLetterCase ); // uncomment to see target cases
				if ( $capitalisationType == 'FC' ) {
					$words[$i] =
						$this->toUpper( mb_substr( $words[$i], 0, 1 ) )
						. mb_substr( $words[$i], 1 );
				} elseif ( $capitalisationType == 'OC' ) {
					if ( mb_strlen( $words[$i] ) == 1 ) {
						$words[$i] = $this->toUpper( $words[$i] );
					} else {
						$words[$i] =
							$this->toUpper( mb_substr( $words[$i], 0, 1 ) )
							. mb_substr( $words[$i], 1 );
					}
				// }elseif( $capitalisationType == 'AC' ){
					// $words[$i] = $this -> toUpper( $words[$i] );
				} elseif ( $capitalisationType == '' ){
					$targetLength = mb_strlen( $targetLetterCase );
					$targetWord = '';
					for ( $j = 0; $j < $targetLength; $j++ ) {
						$letter = mb_substr( $words[$i], $j, 1 );
						if ( mb_substr( $targetLetterCase, $j, 1 ) == 'u' ){
							$targetWord .= $this->toUpper( $letter );
						} else {
							$targetWord .= $letter;
						}
					}
					$words[$i] = $targetWord;
				}
				// $words[$i]=$capitalisationType.$words[$i];
			}// i
			$text = join( $words );
			/* // alternative way with using "preg_replace_callback"s
			// in "convertTatarFromCyrillicToLatin" function
			// like this:
			// $text = preg_replace_callback(
				// '/к(?=арав|амаз)/ui',
				// function($m){return $m[0]=='к'?'k':'K';},
				// $text
			// );
			// $text = preg_replace_callback(
				// '/г(?=[аыуъ])/ui',
				// function($m){return $m[0]=='г'?'ğ':'Ğ';},
				// $text
			// );
			$words = preg_split( '/\b/u', $text );
			$wordsCount = count($words);
			for ( $i=0; $i<$wordsCount; $i++ ){
				if ( 0===preg_match( '/[А-ЯЁӘӨҮҖҢҺ]/ui', $words[$i] ) ){
					continue; // no cyrillic
				}
				$words[$i] = $this -> convertTatarFromCyrillicToLatin2( $words[$i] );
			}
			$text=join( $words );*/
			// return parent::translate( $text, $toVariant );
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
