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
		'а' => 'a', // 'А' => 'A',// capital letters are handled outside
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
		'\'' => 'э', '’' => 'э',
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
		$text = preg_replace( '/актив/u', 'aktiv', $text );
		$text = preg_replace( '/перманганат/u', 'permanganat', $text );
			$text = preg_replace( '/(?<=[уа])ев/u', 'yev', $text ); // uyev,ayev not uyıv,ayıv
		$text = preg_replace( '/(?<=е)в/u', 'v', $text ); // мочевина
		$text = preg_replace( '/ов/u', 'ov', $text );
		$text = preg_replace( '/ное/u', 'noye', $text );
		// $text = preg_replace( '/иев/u', 'iev', $text );
		$text = preg_replace( '/ив/u', 'iv', $text );
		$text = preg_replace( '/вод/u', 'vod', $text );
		$text = preg_replace( '/ссылка/u', 'ssılka', $text );
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
		$text = preg_replace( '/([бвгджҗзклмнңпрстфхһцчшщ])ю(?=\w+[аыо])/u',
			'$1yu', $text ); // эволюцияга,люгдон
		$text = preg_replace( '/([бвгджҗзклмнңпрстфхһцчшщ])ю/u', '$1ü', $text ); // эволюция
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
		$text = preg_replace( '/(\w)ель/u', '$1el\'', $text );
		$text = preg_replace( '/камил/u', 'kamil', $text );
		$text = preg_replace( '/гаять/u', 'ğayät', $text );
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
		// remember not to change сагаеп
		// $text = preg_replace( '/\bГае/u', 'Ğäye', $text );
		$text = preg_replace( '/гая([тр])(?=е)/u', 'ğäyä$1', $text );
		$text = preg_replace( '/гая([тр])ь/u', 'ğäyä$1', $text );
		$text = preg_replace( '/га(?=[дҗтр][ьәе])/u', 'ğä$1', $text );
		// $text = preg_replace( '/га([])ә/u', 'ğä$1ä', $text );
		$text = preg_replace( '/\bка([бвгджҗзклмнңпрстфхһцчшщ])([еә])/u', 'qä$1$2', $text );
		// кабер, калеб
		// $text = preg_replace( '/эко/u', 'eko', $text );
		// $text = preg_replace( '/Эко/u', 'Eko', $text );
		$text = preg_replace( '/эво/u', 'evo', $text );
		// $text = preg_replace( '/Эво/u', 'Evo', $text );
		$text = preg_replace( '/(?<=[оауыи])езд/u', 'yezd', $text ); // поезд-poyezd
		$text = preg_replace( '/брь/u', 'ber', $text ); // ноябрь-noyaber
		$text = preg_replace( '/(?<=\b|ь)е([бвгджҗзклмнңпрстфхһцчшщ]+(?=([уаы]|\b)))/u',
			'yı$1', $text ); // егу-yığu, күпьеллык
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
		$text = preg_replace( '/тюк/u', 't\'uk', $text );
		// $text = preg_replace( '/жив/u', 'jiv', $text ); // see ив
		$text = preg_replace( '/дөнья/u', 'dönya', $text );
		$text = preg_replace( '/([бвгджҗзклмнңпрстфхһцчшщ])я/u', '$1ya', $text ); // варяг
		// make exceptions (васыять, вәкил, вәзгыять, вәли, веб, вак)
		// then all first в to v (вагон, виза, версия, вариант, визит)
		$text = preg_replace( '/\bвеб/u', 'web', $text );
		// $text = preg_replace( '/\bВеб/u', 'Web', $text );
		$text = preg_replace( '/\bвак/u', 'waq', $text );
		// $text = preg_replace( '/\bВак/u', 'Waq', $text );
		$text = preg_replace( '/\bвасыя/u', 'wasiä', $text );
		// $text = preg_replace( '/\bВасыять?/u', 'Wasiyät', $text );
		$text = preg_replace( '/\bвә/u', 'wä', $text );
		// $text = preg_replace( '/\bВә/u', 'Wä', $text );
		$text = preg_replace( '/\bв/u', 'v', $text );
		// $text = preg_replace( '/\bВ/u', 'V', $text );
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
		// $text = preg_replace( '/ҮЕ/u', 'ÜE', $text ); // KİENÜE
		// $text = preg_replace( '/Үе/u', 'Üe', $text ); // KİENÜendä
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
			$text = preg_replace( '/башкорт/u', 'başqort', $text );
			$text = preg_replace( '/аккош/u', 'aqqoş', $text );
			$text = preg_replace( '/(?<=\w)ко/u', 'ko', $text ); // микология, экология
		$text = preg_replace( '/к([аыоуъ])/u', 'q$1', $text ); // ка,ко,кы,ку,къ
		// $text = preg_replace( '/К([аыоуАЫОУ])/u', 'Q$1', $text );
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
		$text = preg_replace( '/г([аыуъ])/u', 'ğ$1', $text );
		// $text = preg_replace( '/Г([аыуАЫУ])/u', 'Ğ$1', $text );
		// $text = preg_replace( '/\bе([шл])/u', 'yı$1', $text );
		// $text = preg_replace( '/\bЕ([шл])/u', 'Yı$1', $text );
		$text = preg_replace( '/(\b|[ъь])е/u', 'ye', $text );
		$text = preg_replace( '/\bЕ/u', 'Ye', $text ); // ЕРЭ
		// $text = preg_replace( '/([А-ЯӘӨҮҖҢҺ])Я\b/u', '$1YA', $text ); // ТАКЫЯ-TAQIYA
		// $text = preg_replace( '/([А-ЯӘӨҮҖҢҺ])Е([А-ЯӘӨҮҖҢҺ])/u',
		// '$1YE$2', $text ); // ИМАМИЕВА-İMAMİYEVA
		// $text = preg_replace( '/([А-ЯӘӨҮҖҢҺ])Я([А-ЯӘӨҮҖҢҺ])/u',
		// '$1YA$2', $text ); // КЫЯМОВА-QIYAMOVA
		// these about capital chars are not needed if case is handled separately,
		// for that these are commented out
		$text = preg_replace( '/ый(?=[бвгджҗзклмнңпрстфхһцчшщ]+[ьә])/u', 'i$1',
			$text ); // мөстәкыйль-möstäqil,гыйффәт-ğiffät
		$text = preg_replace( '/ы(?=[бвгджҗзклмнңпрстфхһцчшщ][ьә])/u',
			'e$1', $text ); // бәгырь-bäğer
		// $text = preg_replace( '/([бвгджҗзклмнңпрстфхһцчшщ])ё/u',
		// '$1\'o', $text ); // шофёр-şof'or
		// $text = preg_replace( '/([бвгджҗзклмнңпрстфхһцчшщ])ё/u',
		// '$1\yo', $text ); // шофёр-şofyor
		// the second of the above is spelling by law/rules/plans, but this regex is not needed
			$text = preg_replace( '/продукт/u', 'produkt', $text );
		$text = preg_replace( '/([аыıоуяюАЫIОУЯЮ]й?)к(?!и)/u',
			'$1q', $text ); // нократ-noqrat,барыйк-barıyq
			$text = preg_replace( '/тугрик/u', 'tugrik', $text );
			$text = preg_replace( '/друг/u', 'drug', $text );
		$text = preg_replace( '/([аыıуАЫIУ])г(?!о)/u', '$1ğ', $text ); // дог-dog,геолог-geolog
		$text = preg_replace( '/([аыоуАЫОУ]\w*[яЯ])к/u', '$1q', $text );
		$text = preg_replace( '/([ао])у/u', '$1w', $text ); // шоу-şow,ау-aw
		$text = preg_replace( '/ыять/u', 'iät', $text );
		$text = preg_replace( '/ать/u', 'ät', $text );
		$text = preg_replace( '/оръән/u', 'ör\'än', $text );
		$text = preg_replace( '/го([бвгджҗзклмнңпрстфхһцчшщ]+)(?=[еә])/u', 'ğö$1', $text );
		$text = preg_replace( '/гомум/u', 'ğömum', $text );
		$text = preg_replace( '/авиа/u', 'avia', $text );
		// $text='!'.$text;
		return parent::translate( $text, 'tt-latn' );
	}
	function convertTatarFromLatinToCyrillic( $text ) {
		$text = preg_replace( '/I(?=\S*\.(ru|com|org|RU|COM|ORG))/', 'И', $text );
		// INTIRTAT.RU test.com/INTIRTIT
		// ->
		// ИНТИРТАТ.РУ тест.җом/ЫНТЫРТЫТ
		$text = preg_replace( '/iyül/u', 'июль', $text );
		$text = preg_replace( '/yabr/u', 'ябрь', $text );
		$text = preg_replace( '/fäqät/u', 'фәкать', $text );
		$text = preg_replace( '/kopia/u', 'копия', $text );
		$text = preg_replace( '/ätsiä/u', 'яция', $text ); // ассимиляция
		$text = preg_replace( '/abl(?!e)/u', 'абль', $text ); // дирижабль
		$text = preg_replace( '/dizel/u', 'дизель', $text );
		$text = preg_replace( '/frants/u', 'франц', $text );
		$text = preg_replace( '/gravitats/u', 'гравитац', $text );
		$text = preg_replace( '/aero/u', 'аэро', $text );
		$text = preg_replace( '/planyor/u', 'планёр', $text );
		$text = preg_replace( '/konstrukts/u', 'конструкц', $text );
		$text = preg_replace( '/dvigatel/u', 'двигатель', $text );
		// $text = preg_replace( '/dönya/u', 'дөнья', $text );
			$text = preg_replace( '/hakan/u', 'хакан', $text ); // Hakan Fidan
		$text = preg_replace( '/(?<=a)k(?![bcçdfghjklmnñpqrsştvwxyz]*[ei])/u', 'кь', $text ); // пакьстан, пакеты, актив
		$text = preg_replace( '/nyaq/u', 'ньяк', $text );
		$text = preg_replace( '/material/u', 'материал', $text );
			// $text = preg_replace( '/xalıq/u', 'халык', $text );
		$text = preg_replace( '/natural/u', 'натураль', $text );
		$text = preg_replace( '/gorizontal\'/u', 'горизонталь', $text );
		$text = preg_replace( '/vertikal\'/u', 'вертикаль', $text );
		$text = preg_replace( '/proportsional\'/u', 'пропорциональ', $text );
		$text = preg_replace( '/aw\b/u', 'ау', $text );
		$text = preg_replace( '/aw(?=[bcçdfghjklmnñpqrsştvwxyz])/u', 'ау', $text );
		$text = preg_replace(
			'/äw(?=([bcçdfghjklmnñpqrsştvwxyz]|\b))/u', 'әү', $text ); // яшәүче, тәвәккәл
		$text = preg_replace( '/aği/u', 'агый', $text );
		$text = preg_replace( '/yudjet/u', 'юджет', $text );
		$text = preg_replace( '/alyuta/u', 'алюта', $text );
		$text = preg_replace( '/tsiya/u', 'ция', $text );
		$text = preg_replace( '/ğtibar/u', 'гътибар', $text );
		$text = preg_replace( '/qiyät/u', 'кыять', $text );
		$text = preg_replace( '/ği/u', 'гый', $text );
		// $text = preg_replace( '/Ği/u', 'Гый', $text );
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
		$text = preg_replace( '/([ieäÄәÄaA])y[äÄäÄaA]/u', '$1я', $text );
		$text = preg_replace( '/y[äa]ğ/u', 'ягъ', $text ); // ягъни
		$text = preg_replace( '/\by[äÄәÄaA]/u', '$1я', $text );
		// $text = preg_replace( '/\bY[äÄәÄaA]/u', 'Я', $text );
		$text = preg_replace(
			'/([äeiöü][bcçdfghjklmnñpqrsştvwxyz])y[äәa]/u', '$1ья', $text ); // дөнья
		$text = preg_replace(
			'/([aıou][bcçdfghjklmnñpqrsştvwxyz])y[äәa]/u', '$1ъя', $text ); // ташъязма
		$text = preg_replace( '/\by[uü]/u', 'ю', $text );
		// $text = preg_replace( '/\bY[uü]/u', 'Ю', $text );
		$text = preg_replace(
			'/ğ[äә]([bcçdfghjklmnñpqrsştvwxyz])(?![äәei])/u',
			'га$1ь',
			$text ); // кәгазь, мөрәҗәгатен, гамәл, мәгариф
		$text = preg_replace( '/ğ[äә]/u', 'га', $text );
		// $text = preg_replace( '/Ğ[äÄәÄ]/u', 'Га', $text );
		$text = preg_replace( '/q[äә]/u', 'ка', $text );
		$text = preg_replace( '/qü/u', 'ку', $text );
		// $text = preg_replace( '/Q[äÄәÄ]/u', 'Ка', $text );
			$text = preg_replace( '/ğe([bcçdfghjklmnñpqrsştvwxyz])/u', 'гы$1ь', $text ); // шөгыль
		// $text = preg_replace( '/(?<=[äeiöü])ğ(?![äeöü])/u', 'гъ', $text ); // нәстәгъликъ, шөгыль
		$text = preg_replace( '/(?<=[äeiöü])ğ/u', 'гъ', $text ); // нәстәгъликъ
			$text = preg_replace( '/qa/u', 'ка', $text ); // wäqalät-вәкаләт
			$text = preg_replace( '/qqiät/u', 'ккыять', $text ); // тәрәккыять
		$text = preg_replace(
			'/(?<=[äeiöü])q/u', 'къ', $text ); // икътисадый, wäqalät-вәкаләт, тәрәккыять
		// $text = preg_replace(
			// '/(?<=[äeiöü])q(?!([bcçdfghjklmnñpqrsştvwxyz]+(a|iyä)))/u',
			// 'къ', $text ); // икътисадый, wäqalät-вәкаләт, тәрәккыять
		$text = preg_replace( '/i[äә]/u', 'ия', $text );
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
			// $words = preg_split( '/\b/u', $text );
			$words = preg_split( '/\b(?!\')|(?<=\w\')/u', $text );
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
					$capitalisationType = '';
					// $sourceLetterCase = array();
					$this->replaceIntoArray(
						'/[a-zäəöɵüışçñğ\'0-9]/u', 'l', $words[$i], $sourceLetterCase );
					$this->replaceIntoArray(
						'/[A-ZÄƏÖƟÜİŞÇÑĞ]/u', 'u', $words[$i], $sourceLetterCase
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
			$words = preg_split( '/\b/u', $text );
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
					$this->replaceIntoArray(
						'/[А-ХЧШЫЭӘӨҮҖҢҺ]/u', 'u', $words[$i], $sourceLetterCase
					);
					$this->replaceIntoArray(
						'/[еёяюцщ]/u', 'll', $words[$i], $sourceLetterCase );
					$this->replaceIntoArray(
						'/[ЕЁЯЮЦЩ]/u', 'ul', $words[$i], $sourceLetterCase );
					$this->replaceIntoArray(
						'/[ьЬъЪ]/u', '', $words[$i], $sourceLetterCase );
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
