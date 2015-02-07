<?php
/**
 * Uzbek specific code.
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
class UzConverter extends LanguageConverter {
	/**
	 * @var array Cyrillic to Latin
	 *
	 * Rules:
	 * 1. ц = ts after a vowel, otherwise 's'.
	 */
	public $toLatin = array(
		'а' => 'a', 'А' => 'A',
		'б' => 'b', 'Б' => 'B',
		'в' => 'v', 'В' => 'V',
		'г' => 'g', 'Г' => 'G',
		'д' => 'd', 'Д' => 'D',
		'е' => 'e', 'Е' => 'E',
		'ё' => 'yo', 'Ё' => 'Yo',
		'ж' => 'j', 'Ж' => 'J',
		'з' => 'z', 'З' => 'Z',
		'и' => 'i', 'И' => 'I',
		'й' => 'y', 'Й' => 'Y',
		'к' => 'k', 'К' => 'K',
		'л' => 'l', 'Л' => 'L',
		'м' => 'm', 'М' => 'M',
		'н' => 'n', 'Н' => 'N',
		'о' => 'o', 'О' => 'O',
		'п' => 'p', 'П' => 'P',
		'р' => 'r', 'Р' => 'R',
		'с' => 's', 'С' => 'S',
		'т' => 't', 'Т' => 'T',
		'у' => 'u', 'У' => 'U',
		'ф' => 'f', 'Ф' => 'F',
		'х' => 'x', 'Х' => 'X',
		// rule 1
		'ц' => 's', 'Ц' => 'S',
		'ч' => 'ch', 'Ч' => 'Ch',
		'ш' => 'sh', 'Ш' => 'Sh',
		'ъ' => 'ʼ', 'Ъ' => 'ʼ',
		'ь' => '', 'Ь' => '',
		'э' => 'e', 'Э' => 'E',
		'ю' => 'yu', 'Ю' => 'Yu',
		'я' => 'ya', 'Я' => 'Ya',
		'ў' => 'oʻ', 'Ў' => 'Oʻ',
		'қ' => 'q', 'Қ' => 'Q',
		'ғ' => 'gʻ', 'Ғ' => 'Gʻ',
		'ҳ' => 'h', 'Ҳ' => 'H',
	);

	public $cyrillicVowels = array(
		'а', 'А', 'е', 'Е', 'ё', 'Ё', 'и', 'И', 'о', 'О', 'у', 'У', 'э', 'Э',
		'ю', 'Ю', 'я', 'Я', 'ў', 'Ў'
	);

	/**
	 * @var array Latin to Cyrillic
	 */
	public $toCyrillic = array(
		'a' => 'а', 'A' => 'А',
		'b' => 'б', 'B' => 'Б',
		'd' => 'д', 'D' => 'Д',
		'e' => 'е', 'E' => 'Е',
		'f' => 'ф', 'F' => 'Ф',
		'g' => 'г', 'G' => 'Г',
		'h' => 'ҳ', 'H' => 'Ҳ',
		'i' => 'и', 'I' => 'И',
		'j' => 'ж', 'J' => 'Ж',
		'k' => 'к', 'K' => 'К',
		'l' => 'л', 'L' => 'Л',
		'm' => 'м', 'M' => 'М',
		'n' => 'н', 'N' => 'Н',
		'o' => 'о', 'O' => 'О',
		'p' => 'п', 'P' => 'П',
		'q' => 'қ', 'Q' => 'Қ',
		'r' => 'р', 'R' => 'Р',
		's' => 'с', 'S' => 'С',
		't' => 'т', 'T' => 'Т',
		'u' => 'у', 'U' => 'У',
		'v' => 'в', 'V' => 'В',
		'x' => 'х', 'X' => 'Х',
		'y' => 'й', 'Y' => 'Й',
		'z' => 'з', 'Z' => 'З',
		'ʼ' => 'ъ',
		'„' => '«', '“' => '»',
	);
	public $latinVowels = array(
		'a', 'A', 'e', 'E', 'i', 'I', 'o', 'O', 'u', 'U', 'o‘', 'O‘'
	);

	/**
	 * 'ts' and 's' letters in these words need to be converted to 'ц'. No rule here.
	 * @var array
	 */
	public $tsWords = array(
		'aberratsion' => 'аберрацион',
		'aberratsiya' => 'аберрация',
		'abzats' => 'абзац',
		'abolitsiya' => 'аболиция',
		'absorbsiya' => 'абсорбция',
		'abstraksionizm' => 'абстракционизм',
		'abstraksionist' => 'абстракционист',
		'abstraksiya' => 'абстракция',
		'abssess' => 'абсцесс',
		'avianosets' => 'авианосец',
		'aviatsiya' => 'авиация',
		'avtoinspeksiya' => 'автоинспекция',
		'avtoprsep' => 'автопрцеп',
		'avtostansiya' => 'автостанция',
		'agglyutinatsiya' => 'агглютинация',
		'agitatsion' => 'агитацион',
		'agitatsiya' => 'агитация',
		'aglomeratsiya' => 'агломерация',
		'agnostitsizm' => 'агностицизм',
		'agromelioratsiya' => 'агромелиорация',
		'adaptatsiya' => 'адаптация',
		'administratsiya' => 'администрация',
		'adsorbsiya' => 'адсорбция',
		'akatsiya' => 'акация',
		'akklimatizatsiya' => 'акклиматизация',
		'akkomodatsiya' => 'аккомодация',
		'akkreditatsiya' => 'аккредитация',
		'aksent' => 'акцент',
		'aksiz' => 'акциз',
		'aksioner' => 'акционер',
		'aksionerlik' => 'акционерлик',
		'aksiya' => 'акция',
		'aksiyadorlik' => 'акциядорлик',
		'alliteratsiya' => 'аллитерация',
		'amortizatsiya' => 'амортизация',
		'amputatsiya' => 'ампутация',
		'annotatsiya' => 'аннотация',
		'annulyatsiya' => 'аннуляция',
		'antitsiklon' => 'антициклон',
		'antratsit' => 'антрацит',
		'apellyatsiya' => 'апелляция',
		'appenditsit' => 'аппендицит',
		'applikatsiya' => 'аппликация',
		'aprobatsiya' => 'апробация',
		'argumentatsiya' => 'аргументация',
		'assimilyatsiya' => 'ассимиляция',
		'assotsiatsiya' => 'ассоциация',
		'attestatsion' => 'аттестацион',
		'attestatsiya' => 'аттестация',
		'attraksion' => 'аттракцион',
		'auksion' => 'аукцион',
		'atsetilen' => 'ацетилен',
		'atseton' => 'ацетон',
		'aeronavigatsiya' => 'аэронавигация',
		'bakteritsid' => 'бактерицид',
		'batsillar' => 'бациллар',
		'biolokatsiya' => 'биолокация',
		'biolyuminessensiya' => 'биолюминесценция',
		'botsman' => 'боцман',
		'bronenosets' => 'броненосец',
		'brutsellyoz' => 'бруцеллёз',
		'vaksina' => 'вакцина',
		'valvatsiya' => 'вальвация',
		'vegetatsion' => 'вегетацион',
		'vegetatsiya' => 'вегетация',
		'venepunksiya' => 'венепункция',
		'ventilyatsion' => 'вентиляцион',
		'ventilyatsiya' => 'вентиляция',
		'vibratsiya' => 'вибрация',
		'vibroizolyatsiya' => 'виброизоляция',
		'vitse-' => 'вице-',
		'vitse-admiral' => 'вице-адмирал',
		'vitse-prezident' => 'вице-президент',
		'vulkanizatsiya' => 'вулканизация',
		'gallitsizm' => 'галлицизм',
		'gallyutsinatsiya' => 'галлюцинация',
		'galvanizatsiya' => 'гальванизация',
		'gastrol-konsert' => 'гастроль-концерт',
		'gaubitsa' => 'гаубица',
		'geliotsentrik' => 'гелиоцентрик',
		'genotsid' => 'геноцид',
		'geotsentrik' => 'геоцентрик',
		'gerbitsidlar' => 'гербицидлар',
		'gers' => 'герц',
		'gersog' => 'герцог',
		'giatsint' => 'гиацинт',
		'gidromelioratsiya' => 'гидромелиорация',
		'gidromexanizatsiya' => 'гидромеханизация',
		'gidrostansiya' => 'гидростанция',
		'gidroelektrostansiya' => 'гидроэлектростанция',
		'giperinflyatsiya' => 'гиперинфляция',
		'gipotsentr' => 'гипоцентр',
		'glitserin' => 'глицерин',
		'glyatsiolog' => 'гляциолог',
		'glyatsiologiya' => 'гляциология',
		'gorchitsa' => 'горчица',
		'gravitatsiya' => 'гравитация',
		'gradatsiya' => 'градация',
		'gusenitsa' => 'гусеница',
		'devalvatsiya' => 'девальвация',
		'degazatsiya' => 'дегазация',
		'degeneratsiya' => 'дегенерация',
		'degustatsiya' => 'дегустатция',
		'deduksiya' => 'дедукция',
		'dezaktivatsiya' => 'дезактивация',
		'dezinseksiya' => 'дезинсекция',
		'dezinfeksiya' => 'дезинфекция',
		'dezinfeksiyalamoq' => 'дезинфекцияламоқ',
		'deklamatsiya' => 'декламация',
		'deklamatsiyachi' => 'декламациячи',
		'deklaratsiya' => 'декларация',
		'dekoratsiya' => 'декорация',
		'delegatsiya' => 'делегация',
		'delimitatsiya' => 'делимитация',
		'demarkatsiya' => 'демаркация',
		'demilitarizatsiya' => 'демилитаризация',
		'demobilizatsiya' => 'демобилизация',
		'denaturalizatsiya' => 'денатурализация',
		'denominatsiya' => 'деноминация',
		'denonsatsiya' => 'денонсация',
		'depilyatsiya' => 'депиляция',
		'deportatsiya' => 'депортация',
		'deratizatsiya' => 'дератизация',
		'derivatsion' => 'деривацион',
		'derivatsiya' => 'деривация',
		'desikatsiya' => 'десикация',
		'detonatsiya' => 'детонация',
		'definitsiya' => 'дефиниция',
		'defitsit' => 'дефицит',
		'deflyatsiya' => 'дефляция',
		'defoliatsiya' => 'дефолиация',
		'deformatsiya' => 'деформация',
		'detsigramm' => 'дециграмм',
		'detsilitr' => 'децилитр',
		'detsimetr' => 'дециметр',
		'diksiya' => 'дикция',
		'direksiya' => 'дирекция',
		'diskvalifikatsiya' => 'дисквалификация',
		'diskriminatsiya' => 'дискриминация',
		'dislokatsiya' => 'дислокация',
		'disproporsiya' => 'диспропорция',
		'dissertatsiya' => 'диссертация',
		'dissimilyatsiya' => 'диссимиляция',
		'dissotsiatsiya' => 'диссоциация',
		'distansion' => 'дистанцион',
		'distansiya' => 'дистанция',
		'distillyatsiya' => 'дистилляция',
		'differensial' => 'дифференциал',
		'differensiatsiya' => 'дифференциация',
		'differensiyalamoq' => 'дифференцияламоқ',
		'dotatsiya' => 'дотация',
		'dotsent' => 'доцент',
		'jinoiy-protsessual' => 'жиноий-процессуал',
		'identifikatsiya' => 'идентификация',
		'izolyatsion' => 'изоляцион',
		'izolyatsiya' => 'изоляция',
		'izolyatsiyalamoq' => 'изоляцияламоқ',
		'illyuminatsiya' => 'иллюминация',
		'illyustratsiya' => 'иллюстрация',
		'immigratsiya' => 'иммиграция',
		'immobilizatsiya' => 'иммобилизация',
		'impotensiya' => 'импотенция',
		'improvizatsiya' => 'импровизация',
		'inauguratsiya' => 'инаугурация',
		'inventarizatsiya' => 'инвентаризация',
		'investitsiya' => 'инвестиция',
		'ingalyatsiya' => 'ингаляция',
		'indeksatsiya' => 'индексация',
		'induksion' => 'индукцион',
		'induksiya' => 'индукция',
		'inersiya' => 'инерция',
		'inersiyali' => 'инерцияли',
		'inkvizitsiya' => 'инквизиция',
		'inkorporatsiya' => 'инкорпорация',
		'inkubatsiya' => 'инкубация',
		'innovatsiya' => 'инновация',
		'inspeksiya' => 'инспекция',
		'instarsiya' => 'инстарция',
		'instruksiya' => 'инструкция',
		'inssenirovka' => 'инсценировка',
		'integratsiya' => 'интеграция',
		'intelligensiya' => 'интеллигенция',
		'intervensiya' => 'интервенция',
		'intervensiyachi' => 'интервенциячи',
		'internatsional' => 'интернационал',
		'internatsionalizm' => 'интернационализм',
		'internatsionalist' => 'интернационалист',
		'intoksikatsiya' => 'интоксикация',
		'intonatsion' => 'интонацион',
		'intonatsiya' => 'интонация',
		'intuitsiya' => 'интуиция',
		'infeksion' => 'инфекцион',
		'infeksiya' => 'инфекция',
		'inflyatsiya' => 'инфляция',
		'informatsion' => 'информацион',
		'informatsiya' => 'информация',
		'inʼeksiya' => 'инъекция',
		'irratsional' => 'иррационал',
		'irrigatsion' => 'ирригацион',
		'irrigatsiya' => 'ирригация',
		'kalkulyatsiya' => 'калькуляция',
		'kalsiy' => 'кальций',
		'kanalizatsiya' => 'канализация',
		'kanseliyariya' => 'канцелиярия',
		'kanserogen' => 'канцероген',
		'kansler' => 'канцлер',
		'kapitalizatsiya' => 'капитализация',
		'kapitulyatsiya' => 'капитуляция',
		'kassatsiya' => 'кассация',
		'katolsizm' => 'католцизм',
		'kvalifikatsiya' => 'квалификация',
		'kvars' => 'кварц',
		'kvarsit' => 'кварцит',
		'kvitansiya' => 'квитанция',
		'kinokonsert' => 'киноконцерт',
		'kinossenariy' => 'киносценарий',
		'klassifikatsiya' => 'классификация',
		'klassitsizm' => 'классицизм',
		'koalitsion' => 'коалицион',
		'koalitsiya' => 'коалиция',
		'kodifikatsiya' => 'кодификация',
		'kolleksioner' => 'коллекционер',
		'kolleksiya' => 'коллекция',
		'kolleksiyachchi' => 'коллекцияччи',
		'kolonsifra' => 'колонцифра',
		'kombinatsiya' => 'комбинация',
		'kommersiya' => 'коммерция',
		'kommunikatsiya' => 'коммуникация',
		'kommutatsiya' => 'коммутация',
		'kompensatsiya' => 'компенсация',
		'kompetensiya' => 'компетенция',
		'kompilyatsiya' => 'компиляция',
		'kompozitsion' => 'композицион',
		'kompozitsiya' => 'композиция',
		'konveksiya' => 'конвекция',
		'konvensiya' => 'конвенция',
		'konvertatsiya' => 'конвертация',
		'kondensatsiya' => 'конденсация',
		'konditsiya' => 'кондиция',
		'konditsioner' => 'кондиционер',
		'konkurensiya' => 'конкуренция',
		'konservatsiya' => 'консервация',
		'konsignatsiya' => 'консигнация',
		'konsolidatsiya' => 'консолидация',
		'konsorsium' => 'консорциум',
		'konspiratsiya' => 'конспирация',
		'konstitutsion' => 'конституцион',
		'konstitutsiya' => 'конституция',
		'konstitutsiyaviy' => 'конституциявий',
		'konstruksiya' => 'конструкция',
		'konsultatsiya' => 'консультация',
		'kontraktatsiya' => 'контрактация',
		'kontributsiya' => 'контрибуция',
		'kontrrevolyutsion' => 'контрреволюцион',
		'kontrrevolyutsioner' => 'контрреволюционер',
		'kontrrevolyutsiya' => 'контрреволюция',
		'konfederatsiya' => 'конфедерация',
		'konferens-zal' => 'конференц-зал',
		'konferensiya' => 'конференция',
		'konfiskatsiya' => 'конфискация',
		'konfrontatsiya' => 'конфронтация',
		'konfutsiylik' => 'конфуцийлик',
		'konfutsiychilik' => 'конфуцийчилик',
		'konsentrat' => 'концентрат',
		'konsentratli' => 'концентратли',
		'konsentratsion' => 'концентрацион',
		'konsentratsiya' => 'концентрация',
		'konsentratsiyalashmoq' => 'концентрациялашмоқ',
		'konsentrik' => 'концентрик',
		'konsepsiya' => 'концепция',
		'konsern' => 'концерн',
		'konsert' => 'концерт',
		'konsertmeyster' => 'концертмейстер',
		'konsessiya' => 'концессия',
		'konslager' => 'концлагерь',
		'kooperatsiya' => 'кооперация',
		'kooptatsiya' => 'кооптация',
		'koordinatsion' => 'координацион',
		'koordinatsiya' => 'координация',
		'korporatsiya' => 'корпорация',
		'korrelyatsiya' => 'корреляция',
		'korrespondensiya' => 'корреспонденция',
		'korrupsiya' => 'коррупция',
		'koeffitsiyent' => 'коэффициент',
		'krematsiya' => 'кремация',
		'kristallizatsiya' => 'кристаллизация',
		'kulminatsion' => 'кульминацион',
		'kulminatsiya' => 'кульминация',
		'kultivatsiya' => 'культивация',
		'laktatsiya' => 'лактация',
		'laminatsiya' => 'ламинация',
		'lanset' => 'ланцет',
		'levomitsetin' => 'левомицетин',
		'legitimatsiya' => 'легитимация',
		'leykotsitlar' => 'лейкоцитлар',
		'leykotsitoz' => 'лейкоцитоз',
		'leksiya' => 'лекция',
		'liberalizatsiya' => 'либерализация',
		'litsey' => 'лицей',
		'litsenziya' => 'лицензия',
		'lokalizatsiya' => 'локализация',
		'lokatsiya' => 'локация',
		'lotsman' => 'лоцман',
		'lyumenissensiya' => 'люменисценция',
		'lyutetsiy' => 'лютеций',
		'manipulyatsiya' => 'манипуляция',
		'marganets' => 'марганец',
		'matritsa' => 'матрица',
		'meditsina' => 'медицина',
		'melioratsiya' => 'мелиорация',
		'menstruatsiya' => 'менструация',
		'metallizatsiya' => 'металлизация',
		'metizatsiya' => 'метизация',
		'mexanizatsiya' => 'механизация',
		'mexanizatsiyalash' => 'механизациялаш',
		'mexanizatsiyalashmoq' => 'механизациялашмоқ',
		'mexanitsizm' => 'механицизм',
		'migratsiya' => 'миграция',
		'mizanssena' => 'мизансцена',
		'militarizatsiya' => 'милитаризация',
		'militsioner' => 'милиционер',
		'militsiya' => 'милиция',
		'militsiyaxona' => 'милицияхона',
		'mineralizatsiya' => 'минерализация',
		'minonosets' => 'миноносец',
		'mistitsizm' => 'мистицизм',
		'mobilizatsiya' => 'мобилизация',
		'modernizatsiya' => 'модернизация',
		'modernizatsiyalamoq' => 'модернизацияламоқ',
		'modifikatsiya' => 'модификация',
		'mototsikl' => 'мотоцикл',
		'mototsiklet' => 'мотоциклет',
		'mototsikletchi' => 'мотоциклетчи',
		'mototsiklli' => 'мотоциклли',
		'mototsiklchi' => 'мотоциклчи',
		'multiplikatsion' => 'мультипликацион',
		'multiplikatsiya' => 'мультипликация',
		'munitsipalizatsiya' => 'муниципализация',
		'munitsipalitet' => 'муниципалитет',
		'navigatsiya' => 'навигация',
		'naturalizatsiya' => 'натурализация',
		'natsionalizatsiya' => 'национализация',
		'nenets' => 'ненец',
		'nenetslar' => 'ненецлар',
		'nitroglitserin' => 'нитроглицерин',
		'nominatsiya' => 'номинация',
		'nostrifikatsiya' => 'нострификация',
		'nullifikatsiya' => 'нуллификация',
		'obligatsiya' => 'облигация',
		'obrogatsiya' => 'оброгация',
		'observatsiya' => 'обсервация',
		'okkupatsion' => 'оккупацион',
		'okkupatsiya' => 'оккупация',
		'okkupatsiyachi' => 'оккупациячи',
		'operatsiya' => 'операция',
		'operatsiyaviy' => 'операциявий',
		'oppozotsion' => 'оппозоцион',
		'oppozitsiya' => 'оппозиция',
		'oppozitsiyachi' => 'оппозициячи',
		'opsion' => 'опцион',
		'ordinarets' => 'ординарец',
		'oriyentatsiya' => 'ориентация',
		'osteomalyatsiya' => 'остеомаляция',
		'ofitser' => 'офицер',
		'ofitsiant' => 'официант',
		'ofitsiantka' => 'официантка',
		'palpatsiya' => 'пальпация',
		'patsiyent' => 'пациент',
		'patsifizm' => 'пацифизм',
		'patsifist' => 'пацифист',
		'penitssilin' => 'пениццилин',
		'pestitsidlar' => 'пестицидлар',
		'petitsiya' => 'петиция',
		'petlitsa' => 'петлица',
		'pigmentatsiya' => 'пигментация',
		'pinset' => 'пинцет',
		'pitssa' => 'пицца',
		'plantatsiya' => 'плантация',
		'platsdarm' => 'плацдарм',
		'platskart' => 'плацкарт',
		'platskarta' => 'плацкарта',
		'platskartali' => 'плацкартали',
		'plebissit' => 'плебисцит',
		'podstansiya' => 'подстанция',
		'pozitsion' => 'позицион',
		'pozitsiya' => 'позиция',
		'politsiya' => 'полиция',
		'politsiyachi' => 'полициячи',
		'politsmeyster' => 'полицмейстер',
		'pollyutsiya' => 'поллюция',
		'populyatsiya' => 'популяция',
		'porsiya' => 'порция',
		'potensial' => 'потенциал',
		'prezentatsiya' => 'презентация',
		'press-konferensiya' => 'пресс-конференция',
		'preferensiya' => 'преференция',
		'privatizatsiya' => 'приватизация',
		'prinsip' => 'принцип',
		'prinsipial' => 'принципиал',
		'prinsipiallik' => 'принципиаллик',
		'prinsipli' => 'принципли',
		'prinsipsiz' => 'принципсиз',
		'pritsep' => 'прицеп',
		'provinsializm' => 'провинциализм',
		'provinsiya' => 'провинция',
		'provokatsiya' => 'провокация',
		'proyeksiya' => 'проекция',
		'proyeksiyalamoq' => 'проекцияламоқ',
		'proklamatsiya' => 'прокламация',
		'prolongatsiya' => 'пролонгация',
		'proporsional' => 'пропорционал',
		'proporsionallik' => 'пропорционаллик',
		'proporsiya' => 'пропорция',
		'proteksionizm' => 'протекционизм',
		'protsent' => 'процент',
		'protsentli' => 'процентли',
		'protsentchi' => 'процентчи',
		'protsess' => 'процесс',
		'protsessor' => 'процессор',
		'protsessual' => 'процессуал',
		'publitsist' => 'публицист',
		'publitsistik' => 'публицистик',
		'publitsistika' => 'публицистика',
		'punktuatsion' => 'пунктуацион',
		'punktuatsiya' => 'пунктуация',
		'punksiya' => 'пункция',
		'radiatsion' => 'радиацион',
		'radiatsiya' => 'радиация',
		'radiolokatsiya' => 'радиолокация',
		'radionavigatsiya' => 'радионавигация',
		'radiostansiya' => 'радиостанция',
		'ranets' => 'ранец',
		'ratifikatsiya' => 'ратификация',
		'rafinatsiya' => 'рафинация',
		'rafinatsiyalash' => 'рафинациялаш',
		'ratsion' => 'рацион',
		'ratsional' => 'рационал',
		'ratsionalizator' => 'рационализатор',
		'ratsionalizatorlik' => 'рационализаторлик',
		'ratsionalizatsiya' => 'рационализация',
		'ratsionalizm' => 'рационализм',
		'ratsionalist' => 'рационалист',
		'ratsionlallashmoq' => 'рационлаллашмоқ',
		'ratsiya' => 'рация',
		'reabilitatsiya' => 'реабилитация',
		'reaksion' => 'реакцион',
		'reaksioner' => 'реакционер',
		'reaksiya' => 'реакция',
		'reaksiyachi' => 'реакциячи',
		'realizatsiya' => 'реализация',
		'reanimatsiya' => 'реанимация',
		'revalvatsiya' => 'ревальвация',
		'revolyutsion' => 'революцион',
		'revolyutsioner' => 'революционер',
		'revolyutsiya' => 'революция',
		'regeneratsiya' => 'регенерация',
		'registratsiya' => 'регистрация',
		'redaksion' => 'редакцион',
		'redaksiya' => 'редакция',
		'reduksiya' => 'редукция',
		'reduplikatsiya' => 'редупликация',
		'rezeksiya' => 'резекция',
		'rezidensiya' => 'резиденция',
		'rezolyutsiya' => 'резолюция',
		'reinvestitsiya' => 'реинвестиция',
		'rekvizitsiya' => 'реквизиция',
		'reklamatsiya' => 'рекламация',
		'rekognossirovka' => 'рекогносцировка',
		'rekomendatsiya' => 'рекомендация',
		'rekonstruksiya' => 'реконструкция',
		'rekonstruksiyalamoq' => 'реконструкцияламоқ',
		'remilitarizatsiya' => 'ремилитаризация',
		'reparatsiya' => 'репарация',
		'repatritsiya' => 'репатриция',
		'repetitsiya' => 'репетиция',
		'reprivatizatsiya' => 'реприватизация',
		'reproduksiya' => 'репродукция',
		'restavratsiya' => 'реставрация',
		'retranslyatsiya' => 'ретрансляция',
		'reformatsiya' => 'реформация',
		'refraksiya' => 'рефракция',
		'retsenzent' => 'рецензент',
		'retsenziya' => 'рецензия',
		'retsept' => 'рецепт',
		'retseptorlar' => 'рецепторлар',
		'retsidiv' => 'рецидив',
		'retsidivist' => 'рецидивист',
		'retsipiyent' => 'реципиент',
		'reevakuatsiya' => 'реэвакуация',
		'reemigratsiya' => 'реэмиграция',
		'ritsarlik' => 'рицарлик',
		'ritsar' => 'рицарь',
		'rotatsion' => 'ротацион',
		'sanatsiya' => 'санация',
		'sanatsiyalash' => 'санациялаш',
		'sanksiya' => 'санкция',
		'sekretsiya' => 'секреция',
		'seksiya' => 'секция',
		'seleksion' => 'селекцион',
		'seleksiya' => 'селекция',
		'seleksiyachi' => 'селекциячи',
		'seleksiyachilik' => 'селекциячилик',
		'sensatsion' => 'сенсацион',
		'sensatsiya' => 'сенсация',
		'signalizatsiya' => 'сигнализация',
		'silitsiy' => 'силиций',
		'situatsiya' => 'ситуация',
		'skeptitsizm' => 'скептицизм',
		'slanets' => 'сланец',
		'sotsial' => 'социал',
		'sotsial-demokrat' => 'социал-демократ',
		'sotsial-demokratik' => 'социал-демократик',
		'sotsial-demokratiya' => 'социал-демократия',
		'sotsializatsiya' => 'социализация',
		'sotsializm' => 'социализм',
		'sotsialist' => 'социалист',
		'sotsialistik' => 'социалистик',
		'sotsiolingvistika' => 'социолингвистика',
		'sotsiolog' => 'социолог',
		'sotsiologik' => 'социологик',
		'sotsiologiya' => 'социология',
		'spekulyatsiya' => 'спекуляция',
		'spetsifik' => 'специфик',
		'spetsifika' => 'специфика',
		'spetsifikatsiya' => 'спецификация',
		'stabilizatsiya' => 'стабилизация',
		'stansiya' => 'станция',
		'statsionar' => 'стационар',
		'sterilizatsiya' => 'стерилизация',
		'stoitsizm' => 'стоицизм',
		'stronsiy' => 'стронций',
		'substansiya' => 'субстанция',
		'ssenariy' => 'сценарий',
		'ssenariychi' => 'сценарийчи',
		'ssenarist' => 'сценарист',
		'tablitsa' => 'таблица',
		'tansa' => 'танца',
		'teleinssenirovka' => 'телеинсценировка',
		'telekommunikatsiya' => 'телекоммуникация',
		'telemexanizatsiya' => 'телемеханизация',
		'tendensioz' => 'тенденциоз',
		'tendensiozlik' => 'тенденциозлик',
		'tendensiya' => 'тенденция',
		'teplitsa' => 'теплица',
		'teploizolyatsiya' => 'теплоизоляция',
		'termoizolyatsiya' => 'термоизоляция',
		'terset' => 'терцет',
		'tersiya' => 'терция',
		'texnetsiy' => 'технеций',
		'traditsion' => 'традицион',
		'traditsiya' => 'традиция',
		'transkripsion' => 'транскрипцион',
		'transkripsiya' => 'транскрипция',
		'transkripsiyalamoq' => 'транскрипцияламоқ',
		'transliteratsiya' => 'транслитерация',
		'translyatsion' => 'трансляцион',
		'translyatsiya' => 'трансляция',
		'transplantatsiya' => 'трансплантация',
		'transformatsiya' => 'трансформация',
		'transformatsiyalamoq' => 'трансформацияламоқ',
		'trapetsiya' => 'трапеция',
		'trepanatsiya' => 'трепанация',
		'uborshitsa' => 'уборшица',
		'uzurpatsiya' => 'узурпация',
		'unifikatsiya' => 'унификация',
		'unifikatsiyalashtirmoq' => 'унификациялаштирмоқ',
		'unter-ofitser' => 'унтер-офицер',
		'urbanizatsiya' => 'урбанизация',
		'fagotsit' => 'фагоцит',
		'falsifikatsiya' => 'фальсификация',
		'farmatsevt' => 'фармацевт',
		'farmatsevtika' => 'фармацевтика',
		'farmatsiya' => 'фармация',
		'federatsiya' => 'федерация',
		'fermentatsiya' => 'ферментация',
		'film-konsert' => 'фильм-концерт',
		'filtratsiya' => 'фильтрация',
		'fitonsid' => 'фитонцид',
		'formatsiya' => 'формация',
		'fraksion' => 'фракцион',
		'fraksiooner' => 'фракциоонер',
		'fraksiya' => 'фракция',
		'fransiya' => 'франция',
		'fransuz' => 'француз',
		'fransuzlar' => 'французлар',
		'fransuzcha' => 'французча',
		'frits' => 'фриц',
		'funksional' => 'функционал',
		'funksiya' => 'функция',
		'xemosorbsiya' => 'хемосорбция',
		'xoletsistit' => 'холецистит',
		'sanga' => 'цанга',
		'sapfa' => 'цапфа',
		'sedra' => 'цедра',
		'seziy' => 'цезий',
		'seytnot' => 'цейтнот',
		'sellofan' => 'целлофан',
		'selluloid' => 'целлулоид',
		'sellyuloza' => 'целлюлоза',
		'selsiy' => 'цельсий',
		'sement' => 'цемент',
		'sementlamoq' => 'цементламоқ',
		'senz' => 'ценз',
		'senzor' => 'цензор',
		'senzura' => 'цензура',
		'sent' => 'цент',
		'sentner' => 'центнер',
		'sentnerli' => 'центнерли',
		'sentnerchi' => 'центнерчи',
		'sentralizm' => 'централизм',
		'sentrizm' => 'центризм',
		'sentrist' => 'центрист',
		'sentrifuga' => 'центрифуга',
		'seriy' => 'церий',
		'sesarka' => 'цесарка',
		'sex' => 'цех',
		'sian' => 'циан',
		'sianli' => 'цианли',
		'sivilizatsiya' => 'цивилизация',
		'sigara' => 'цигара',
		'sikl' => 'цикл',
		'siklik' => 'циклик',
		'sikllashtirmoq' => 'цикллаштирмоқ',
		'siklli' => 'циклли',
		'siklon' => 'циклон',
		'siklotron' => 'циклотрон',
		'silindr' => 'цилиндр',
		'silindrik' => 'цилиндрик',
		'silindrli' => 'цилиндрли',
		'singa' => 'цинга',
		'sink' => 'цинк',
		'sinkograf' => 'цинкограф',
		'sinkografiya' => 'цинкография',
		'sirk' => 'цирк',
		'sirkoniy' => 'цирконий',
		'sirkul' => 'циркуль',
		'sirkulyar' => 'циркуляр',
		'sirkchi' => 'циркчи',
		'sirroz' => 'цирроз',
		'sisterna' => 'цистерна',
		'sisternali' => 'цистернали',
		'sistit' => 'цистит',
		'sitata' => 'цитата',
		'sitatabozlik' => 'цитатабозлик',
		'sito-' => 'цито-',
		'sitodiagnostika' => 'цитодиагностика',
		'sitokimyo' => 'цитокимё',
		'sitoliz' => 'цитолиз',
		'sitologiya' => 'цитология',
		'sitrus' => 'цитрус',
		'siferblat' => 'циферблат',
		'siferblatli' => 'циферблатли',
		'sokol' => 'цоколь',
		'sunami' => 'цунами',
		'cherepitsa' => 'черепица',
		'shveysar' => 'швейцар',
		'shmutstitul' => 'шмуцтитул',
		'shnitsel' => 'шницель',
		'shprits' => 'шприц',
		'shtangensirkul' => 'штангенциркуль',
		'evakuatsiya' => 'эвакуация',
		'evolyutsion' => 'эволюцион',
		'evolyutsiya' => 'эволюция',
		'egotsentrizm' => 'эгоцентризм',
		'eksgumatsiya' => 'эксгумация',
		'ekspeditsion' => 'экспедицион',
		'ekspeditsiya' => 'экспедиция',
		'ekspeditsiyachi' => 'экспедициячи',
		'ekspluatatsiya' => 'эксплуатация',
		'ekspluatatsiyachi' => 'эксплуатациячи',
		'ekspozitsiya' => 'экспозиция',
		'ekspropriatsiya' => 'экспроприация',
		'ekstraditsiya' => 'экстрадиция',
		'ekstraksiya' => 'экстракция',
		'elektrifikatsiya' => 'электрификация',
		'elektrostansiya' => 'электростанция',
		'emansipatsiya' => 'эмансипация',
		'emigratsiya' => 'эмиграция',
		'emotsional' => 'эмоционал',
		'emotsionallik' => 'эмоционаллик',
		'emotsiya' => 'эмоция',
		'empiriokrititsizm' => 'эмпириокритицизм',
		'ensefalit' => 'энцефалит',
		'ensefalogramma' => 'энцефалограмма',
		'ensiklopedik' => 'энциклопедик',
		'ensiklopedist' => 'энциклопедист',
		'ensiklopediya' => 'энциклопедия',
		'ensiklopediyachi' => 'энциклопедиячи',
		'epitsentr' => 'эпицентр',
		'eritrotsitlar' => 'эритроцитлар',
		'eruditsiya' => 'эрудиция',
		'eskalatsiya' => 'эскалация',
		'esminets' => 'эсминец',
		'essensiya' => 'эссенция',
		'yurisdiksiya' => 'юрисдикция',
		'yurisprudensiya' => 'юриспруденция',
		'yustitsiya' => 'юстиция',
	);

	/**
	 * 'sh' in the following words need to be converted to 'сҳ' and not to 'ш'.
	 * @var array
	 */
	public $shWords = array(
		'ashob' => 'асҳоб',
		'mushaf' => 'мусҳаф'
	);

	/**
	 * 'e' in these words need to be converted to 'э' and not 'е'.
	 * @var array
	 */
	public $eWords = array(
		'beletaj' => 'бельэтаж',
		'bugun-erta' => 'бугун-эрта',
		'diqqat-eʼtibor' => 'диққат-эътибор',
		'ich-et' => 'ич-эт',
		'karate' => 'каратэ',
		'mer' => 'мэр',
		'obroʻ-eʼtiborli' => 'обрў-эътиборли',
		'omon-eson' => 'омон-эсон',
		'reket' => 'рэкет',
		'sutemizuvchilar' => 'сутэмизувчилар',
		'upa-elik' => 'упа-элик',
		'xayr-ehson' => 'хайр-эҳсон',
		'qaynegachi' => 'қайнэгачи',
	);

	/**
	 * 'yo' in these words need to be converted to 'йо' and not to 'ё'.
	 * @var array
	 */
	public $yoWords = array(
		'general-mayor' => 'генерал-майор',
		'yog' => 'йог',
		'yoga' => 'йога',
		'yogurt' => 'йогурт',
		'yod' => 'йод',
		'yodlamoq' => 'йодламоқ',
		'yodli' => 'йодли',
		'mayonez' => 'майонез',
		'mikrorayon' => 'микрорайон',
		'mayor' => 'майор',
		'rayon' => 'район',
	);

	/**
	 * 'yu' in these words need to be converted to 'йу' and not to 'ю'.
	 * @var array
	 */
	public $yuWords = array(
		'moyupa' => 'мойупа',
		'poyustun' => 'пойустун'
	);

	/**
	 * 'ya' in these words need to be converted to 'йа' and not to 'я'.
	 * @var array
	 */
	public $yaWords = array(
		'poyabzal' => 'пойабзал',
		'poyandoz' => 'пойандоз',
		'poyafzal' => 'пойафзал'
	);

	/**
	 * 'ye' in these words need to be converted to 'йе' and not to 'е'.
	 * @var array
	 */
	public $yeWords = array(
		'iye' => 'ийе',
		'konveyer' => 'конвейер',
		'pleyer' => 'плейер',
		'stayer' => 'стайер',
		'foye' => 'фойе'
	);

	/**
	 * Words in which a soft sign character need to be added when transliterated to cyrillic
	 * @var array
	 */
	public $softSignWords = array(
		'aviamodel' => 'авиамодель',
		'avtomagistralavtomat' => 'автомагистральавтомат',
		'avtomobil' => 'автомобиль',
		'akvarel' => 'акварель',
		'alkogol' => 'алкоголь',
		'albatros' => 'альбатрос',
		'albom' => 'альбом',
		'alpinizm' => 'альпинизм',
		'alpinist' => 'альпинист',
		'alt' => 'альт',
		'alternativ' => 'альтернатив',
		'alternativa' => 'альтернатива',
		'altimetr' => 'альтиметр',
		'altchi' => 'альтчи',
		'alfa' => 'альфа',
		'alfa-zarralar' => 'альфа-зарралар',
		'alma-terapiya' => 'альма-терапия',
		'alyans' => 'альянс',
		'amalgama' => 'амальгама',
		'ansambl' => 'ансамбль',
		'apelsin' => 'апельсин',
		'aprel' => 'апрель',
		'artel' => 'артель',
		'artikl' => 'артикль',
		'arergard' => 'арьергард',
		'asfalt' => 'асфальт',
		'asfaltlamoq' => 'асфальтламоқ',
		'asfaltli' => 'асфальтли',
		'atele' => 'ателье',
		'bazalt' => 'базальт',
		'balzam' => 'бальзам',
		'balzamlash' => 'бальзамлаш',
		'balneolog' => 'бальнеолог',
		'balneologik' => 'бальнеологик',
		'balneologiya' => 'бальнеология',
		'balneoterapiya' => 'бальнеотерапия',
		'balneotexnika' => 'бальнеотехника',
		'banderol' => 'бандероль',
		'barelef' => 'барельеф',
		'barrel' => 'баррель',
		'barer' => 'барьер',
		'batalon' => 'батальон',
		'belveder' => 'бельведер',
		'belgiyalik' => 'бельгиялик',
		'belting' => 'бельтинг',
		'beletaj' => 'бельэтаж',
		'bilyard' => 'бильярд',
		'binokl' => 'бинокль',
		'biofiltr' => 'биофильтр',
		'bolonya' => 'болонья',
		'bolshevizm' => 'большевизм',
		'bolshevik' => 'большевик',
		'brakonerlik' => 'браконьерлик',
		'broneavtomobil' => 'бронеавтомобиль',
		'bron' => 'бронь',
		'budilnik' => 'будильник',
		'bulvar' => 'бульвар',
		'buldenej' => 'бульденеж',
		'buldog' => 'бульдог',
		'buldozer' => 'бульдозер',
		'buldozerchi' => 'бульдозерчи',
		'bulon' => 'бульон',
		'byulleten' => 'бюллетень',
		'valeryanka' => 'валерьянка',
		'valvatsiya' => 'вальвация',
		'vals' => 'вальс',
		'vanil' => 'ваниль',
		'varete' => 'варьете',
		'vedomost' => 'ведомость',
		'veksel' => 'вексель',
		'ventil' => 'вентиль',
		'vermishel' => 'вермишель',
		'verner' => 'верньер',
		'verf' => 'верфь',
		'vestibyul' => 'вестибюль',
		'videofilm' => 'видеофильм',
		'viklyuchatel' => 'виключатель',
		'vinetka' => 'виньетка',
		'violonchel' => 'виолончель',
		'vklyuchatel' => 'включатель',
		'vodevil' => 'водевиль',
		'volost' => 'волость',
		'volt' => 'вольт',
		'volta' => 'вольта',
		'voltli' => 'вольтли',
		'voltmetr' => 'вольтметр',
		'volfram' => 'вольфрам',
		'vulgar' => 'вульгар',
		'vulgarizm' => 'вульгаризм',
		'vulgarlashtirmoq' => 'вульгарлаштирмоқ',
		'gavan' => 'гавань',
		'galvanizatsiya' => 'гальванизация',
		'galvanik' => 'гальваник',
		'galvanometr' => 'гальванометр',
		'gantel' => 'гантель',
		'garmon' => 'гармонь',
		'gastrol' => 'гастроль',
		'gastrol-konsert' => 'гастроль-концерт',
		'gelmint' => 'гельминт',
		'gelmintoz' => 'гельминтоз',
		'gelmintologiya' => 'гельминтология',
		'geraldika' => 'геральдика',
		'gilza' => 'гильза',
		'giposulfit' => 'гипосульфит',
		'golf' => 'гольф',
		'gorelef' => 'горельеф',
		'gorizontal' => 'горизонталь',
		'gospital' => 'госпиталь',
		'grifel' => 'грифель',
		'guash' => 'гуашь',
		'daltonizm' => 'дальтонизм',
		'dvigatel' => 'двигатель',
		'devalvatsiya' => 'девальвация',
		'dekabr' => 'декабрь',
		'delta' => 'дельта',
		'delfin' => 'дельфин',
		'delfinariy' => 'дельфинарий',
		'delfinsimonlar' => 'дельфинсимонлар',
		'detal' => 'деталь',
		'diagonal' => 'диагональ',
		'diafilm' => 'диафильм',
		'dizel' => 'дизель',
		'dizel-motor' => 'дизель-мотор',
		'dirijabl' => 'дирижабль',
		'drel' => 'дрель',
		'duel' => 'дуэль',
		'jenshen' => 'женьшень',
		'impuls' => 'импульс',
		'inventar' => 'инвентарь',
		'insult' => 'инсульт',
		'intervyu' => 'интервью',
		'interer' => 'интерьер',
		'italyan' => 'итальян',
		'italyanlar' => 'итальянлар',
		'italyancha' => 'итальянча',
		'iyul' => 'июль',
		'iyun' => 'июнь',
		'kabel' => 'кабель',
		'kalendar' => 'календарь',
		'kalka' => 'калька',
		'kalkalamoq' => 'калькаламоқ',
		'kalkulyator' => 'калькулятор',
		'kalkulyatsiya' => 'калькуляция',
		'kalsiy' => 'кальций',
		'kanifol' => 'канифоль',
		'kapelmeyster' => 'капельмейстер',
		'kapsyul' => 'капсюль',
		'karamel' => 'карамель',
		'kartel' => 'картель',
		'kartech' => 'картечь',
		'karusel' => 'карусель',
		'karer' => 'карьер',
		'kastryul' => 'кастрюль',
		'kastryulka' => 'кастрюлька',
		'katapulta' => 'катапульта',
		'kafel' => 'кафель',
		'kinofestival' => 'кинофестиваль',
		'kinofilm' => 'кинофильм',
		'kisel' => 'кисель',
		'kitel' => 'китель',
		'knyaz' => 'князь',
		'kobalt' => 'кобальт',
		'kokil' => 'кокиль',
		'kokteyl' => 'коктейль',
		'kompyuter' => 'компьютер',
		'kompyuterlashtirmoq' => 'компьютерлаштирмоқ',
		'konsultant' => 'консультант',
		'konsultativ' => 'консультатив',
		'konsultatsiya' => 'консультация',
		'kontrol' => 'контроль',
		'konferanse' => 'конферансье',
		'konslager' => 'концлагерь',
		'kon' => 'конь',
		'konki' => 'коньки',
		'konkichi' => 'конькичи',
		'konyunktiva' => 'коньюнктива',
		'konyunktivit' => 'коньюнктивит',
		'konyunktura' => 'коньюнктура',
		'konyak' => 'коньяк',
		'korol' => 'король',
		'kreml' => 'кремль',
		'krovat' => 'кровать',
		'kulminatsion' => 'кульминацион',
		'kulminatsiya' => 'кульминация',
		'kultivator' => 'культиватор',
		'kultivatsiya' => 'культивация',
		'kulturizm' => 'культуризм',
		'kurer' => 'курьер',
		'kyat' => 'кьят',
		'lager' => 'лагерь',
		'latun' => 'латунь',
		'losos' => 'лосось',
		'loson' => 'лосьон',
		'magistral' => 'магистраль',
		'marseleza' => 'марсельеза',
		'mebel' => 'мебель',
		'medal' => 'медаль',
		'medalon' => 'медальон',
		'melxior' => 'мельхиор',
		'menshevizm' => 'меньшевизм',
		'menshevik' => 'меньшевик',
		'migren' => 'мигрень',
		'mikroinsult' => 'микроинсульт',
		'mikrofilm' => 'микрофильм',
		'model' => 'модель',
		'modeler' => 'модельер',
		'molbert' => 'мольберт',
		'monastir' => 'монастирь',
		'monokultoura' => 'монокультоура',
		'motel' => 'мотель',
		'multi-' => 'мульти-',
		'multimediya' => 'мультимедия',
		'multimillioner' => 'мультимиллионер',
		'multiplikatsion' => 'мультипликацион',
		'multiplikator' => 'мультипликатор',
		'multiplikatsiya' => 'мультипликация',
		'neft' => 'нефть',
		'nikel' => 'никель',
		'nimpalto' => 'нимпальто',
		'nippel' => 'ниппель',
		'nol' => 'ноль',
		'normal' => 'нормаль',
		'noyabr' => 'ноябрь',
		'oblast' => 'область',
		'okkultizm' => 'оккультизм',
		'oktabr' => 'октябрь',
		'otel' => 'отель',
		'oftalmologiya' => 'офтальмология',
		'ochered' => 'очередь',
		'pavilon' => 'павильон',
		'palma' => 'пальма',
		'palmazor' => 'пальмазор',
		'palpatsiya' => 'пальпация',
		'palto' => 'пальто',
		'paltobop' => 'пальтобоп',
		'paltolik' => 'пальтолик',
		'panel' => 'панель',
		'parallel' => 'параллель',
		'parol' => 'пароль',
		'patrul' => 'патруль',
		'pedal' => 'педаль',
		'penalti' => 'пенальти',
		'pechat' => 'печать',
		'pechene' => 'печенье',
		'pech' => 'печь',
		'plastir' => 'пластирь',
		'povest' => 'повесть',
		'polka' => 'полька',
		'portfel' => 'портфель',
		'porshen' => 'поршень',
		'pochtalon' => 'почтальон',
		'predoxranitel' => 'предохранитель',
		'premera' => 'премьера',
		'premer-ministr' => 'премьер-министр',
		'press-pape' => 'пресс-папье',
		'press-sekretar' => 'пресс-секретарь',
		'pristan' => 'пристань',
		'profil' => 'профиль',
		'pulverizator' => 'пульверизатор',
		'pulmonologiya' => 'пульмонология',
		'pulpa' => 'пульпа',
		'pulpit' => 'пульпит',
		'puls' => 'пульс',
		'pult' => 'пульт',
		'pesa' => 'пьеса',
		'radiospektakl' => 'радиоспектакль',
		'rante' => 'рантье',
		'revalvatsiya' => 'ревальвация',
		'revolver' => 'револьвер',
		'rezba' => 'резьба',
		'rezbali' => 'резьбали',
		'relef' => 'рельеф',
		'rels' => 'рельс',
		'relsli' => 'рельсли',
		'relssiz' => 'рельссиз',
		'retush' => 'ретушь',
		'riyel' => 'риель',
		'ritsar' => 'рицарь',
		'rol' => 'роль',
		'royal' => 'рояль',
		'rubilnik' => 'рубильник',
		'rubl' => 'рубль',
		'rul' => 'руль',
		'saldo' => 'сальдо',
		'salto' => 'сальто',
		'sekretar' => 'секретарь',
		'selderey' => 'сельдерей',
		'seld' => 'сельдь',
		'sentabr' => 'сентябрь',
		'senor' => 'сеньор',
		'senora' => 'сеньора',
		'sinka' => 'синька',
		'sinkalamoq' => 'синькаламоқ',
		'siren' => 'сирень',
		'skalpel' => 'скальпель',
		'slesar' => 'слесарь',
		'sobol' => 'соболь',
		'sol' => 'соль',
		'spektakl' => 'спектакль',
		'spiral' => 'спираль',
		'statya' => 'статья',
		'stelka' => 'стелька',
		'sterjen' => 'стержень',
		'stil' => 'стиль',
		'sudya' => 'судья',
		'sudyalik' => 'судьялик',
		'sulfat' => 'сульфат',
		'sulfatlar' => 'сульфатлар',
		'tabel' => 'табель',
		'talk' => 'тальк',
		'tekstil' => 'текстиль',
		'telefilm' => 'телефильм',
		'tigel' => 'тигель',
		'tokar' => 'токарь',
		'tol' => 'толь',
		'tonnel' => 'тоннель',
		'tunnel' => 'туннель',
		'tush' => 'тушь',
		'tyulen' => 'тюлень',
		'tyul' => 'тюль',
		'ultimatum' => 'ультиматум',
		'ultra-' => 'ультра-',
		'ultrabinafsha' => 'ультрабинафша',
		'ultramikroskop' => 'ультрамикроскоп',
		'ultratovush' => 'ультратовуш',
		'ultraqisqa' => 'ультрақисқа',
		'umivalnik' => 'умивальник',
		'util' => 'утиль',
		'fakultativ' => 'факультатив',
		'fakultet' => 'факультет',
		'fakultetlalaro' => 'факультетлаларо',
		'falsifikator' => 'фальсификатор',
		'falsifikatsiya' => 'фальсификация',
		'fevral' => 'февраль',
		'feldmarshal' => 'фельдмаршал',
		'feldsher' => 'фельдшер',
		'feldʼeger' => 'фельдъегерь',
		'feleton' => 'фельетон',
		'feletonchi' => 'фельетончи',
		'festival' => 'фестиваль',
		'fizkultura' => 'физкультура',
		'fizkulturachi' => 'физкультурачи',
		'film' => 'фильм',
		'film-konsert' => 'фильм-концерт',
		'filmoskop' => 'фильмоскоп',
		'filmoteka' => 'фильмотека',
		'filtr' => 'фильтр',
		'filtratsiya' => 'фильтрация',
		'filtrlamoq' => 'фильтрламоқ',
		'filtrli' => 'фильтрли',
		'folga' => 'фольга',
		'folklor' => 'фольклор',
		'folklorist' => 'фольклорист',
		'folkloristika' => 'фольклористика',
		'folklorchi' => 'фольклорчи',
		'folklorshunos' => 'фольклоршунос',
		'folklorshunoslik' => 'фольклоршунослик',
		'fonar' => 'фонарь',
		'fortepyano' => 'фортепьяно',
		'xolodilnik' => 'холодильник',
		'xrustal' => 'хрусталь',
		'selsiy' => 'цельсий',
		'sirkul' => 'циркуль',
		'sokol' => 'цоколь',
		'chizel' => 'чизель',
		'shagren' => 'шагрень',
		'shampun' => 'шампунь',
		'sherst' => 'шерсть',
		'shinel' => 'шинель',
		'shifoner' => 'шифоньер',
		'shnitsel' => 'шницель',
		'shpatel' => 'шпатель',
		'shpilka' => 'шпилька',
		'shpindel' => 'шпиндель',
		'shtangensirkul' => 'штангенциркуль',
		'shtapel' => 'штапель',
		'shtempel' => 'штемпель',
		'emal' => 'эмаль',
		'emulsiya' => 'эмульсия',
		'endshpil' => 'эндшпиль',
		'eskadrilya' => 'эскадрилья',
		'yuan' => 'юань',
		'yuriskonsult' => 'юрисконсульт',
		'yakor' => 'якорь',
		'yanvar' => 'январь',
	);

	function loadDefaultTables() {
		$this->mTables = array(
			'uz-cyrl' => new ReplacementArray( $this->toCyrillic ),
			'uz-latn' => new ReplacementArray( $this->toLatin ),
			'uz' => new ReplacementArray()
		);
	}

	/**
	 * Translate Cyrillic to Latin
	 * 1. ц = s at the beginning of a word.
	 *    ц = ts in the middle of a word after a vowel.
	 *    ц = s in the middle of a word after consonant (DEFAULT in CYRILLIC_TO_LATIN)
	 *          цирк = sirk
	 * 			цех = sex
	 * 			федерация = federatsiya
	 * 			функция = funksiya
	 * 2. е = ye at the beginning of a word or after a vowel.
	 * 	  е = e in the middle of a word after a consonant (DEFAULT).
	 * 3. Сентябр = Sentabr, Октябр = Oktabr
	 * @param $text
	 * @return string
	 */
	function translateToLatn ( $text ) {
		$beginningRules = array(
			'ц' => 's', 'Ц' => 'S',
			'е' => 'ye', 'Е' => 'Ye'
		);
		$afterVowelRules = array(
			'ц' => 'ts', 'Ц' => 'Ts',
			'е' => 'ye', 'Е' => 'Ye'
		);

		$text = preg_replace_callback(
			'/(сент|окт)([яЯ])(бр)/ui',
			function( $matches ) {
				if ( $matches[2] == 'я' ) {
					$a = 'a';
				} else {
					$a = 'A';
				}
				return $matches[1] . $a . $matches[3];
			},
			$text
		);

		// beginning rules
		$text = preg_replace_callback(
			'/\b(' . implode( '|', array_keys( $beginningRules ) ) . ')/ui',
			function( $matches ) use ( &$beginningRules ) {
				return $beginningRules[ $matches[1] ];
			},
			$text
		);

		// after a vowel
		$text = preg_replace_callback(
			'/(' . implode( '|', $this->cyrillicVowels ) . ')(' . implode( '|', array_keys( $afterVowelRules ) ) . ')/ui',
			function( $matches ) use ( &$afterVowelRules ) {
				return $matches[1] . $afterVowelRules[ $matches[2] ];
			},
			$text
		);
		return $text;
	}

	/**
	 * Transliterate latin text to cyrillic  using the following rules:
	 * 1. ts = ц after a vowel, otherwise ts = тс
	 * 2. ye = е in the beginning of a word or after a vowel
	 * 3. e = э in the beginning of a word or after a vowel
	 * 4. Cyrillic to latin is lossy, so we need a bunch of words to convert back from latin to
	 *    cyrillic: tsWords, shWords, eWords, yoWords, yuWords, yaWords, yeWords, softSignWords
	 * @param $text
	 * @return string
	 */
	function translateToCyrl ( $text ) {
		// These compounds must be converted before other letters
		$compoundsFirst = array(
			'ch' => 'ч', 'Ch' => 'Ч', 'CH' => 'Ч',
			# this line must come before 's' because it has an 'h'
			'sh' => 'ш', 'Sh' => 'Ш', 'SH' => 'Ш',
			# This line must come before 'yo' because of it's apostrophe
			'yo‘' => 'йў', 'Yo‘' => 'Йў', 'YO‘' => 'ЙЎ',
		);
		$compoundsSecond = array(
			'yo' => 'ё', 'Yo' => 'Ё', 'YO' => 'Ё',
			// 'ts' => 'ц', 'Ts' => 'Ц', 'TS' => 'Ц',  # No need for this, see $tsWords
			'yu' => 'ю', 'Yu' => 'Ю', 'YU' => 'Ю',
			'ya' => 'я', 'Ya' => 'Я', 'YA' => 'Я',
			'ye' => 'е', 'Ye' => 'Е', 'YE' => 'Е',
			// different kinds of apostrophes
			'o‘' => 'ў', 'O‘' => 'Ў', 'oʻ' => 'ў', 'Oʻ' => 'Ў',
			'g‘' => 'ғ', 'G‘' => 'Ғ', 'gʻ' => 'ғ', 'Gʻ' => 'Ғ',
		);
		// beginning of word
		$beginningRules = array(
			'ye' => 'е', 'Ye' => 'Е', 'YE' => 'Е',
			'e' => 'э', 'E' => 'Э',
		);
		$afterVowelRules = array(
			'ye' => 'е', 'Ye' => 'Е', 'YE' => 'Е',
			'e' => 'э', 'E' => 'Э',
		);

		// these are used to convert words from tsWords, shWords, eWords, yoWords, yuWords, yaWords, yeWords, softSignWords
		$exceptionWordRules = array(
			's' => 'ц', 'S' => 'Ц',
			'ts' => 'ц', 'Ts' => 'Ц', 'TS' => 'Ц',  // but not tS
			'e' => 'э', 'E' => 'э',
			'sh' => 'сҳ', 'Sh' => 'Сҳ', 'SH' => 'СҲ',
			'yo' => 'йо', 'Yo' => 'Йо', 'YO' => 'ЙО',
			'yu' => 'йу', 'Yu' => 'Йу', 'YU' => 'ЙУ',
			'ya' => 'йа', 'Ya' => 'Йа', 'YA' => 'ЙА',
		);

		$getExceptionWords = function ( $match, $table ) {
			$lower = mb_convert_case( $match, MB_CASE_LOWER, 'UTF-8' );
			if ( ctype_upper( $match ) ) {
				// capitalize the whole word
				$result = mb_convert_case( $table[$lower], MB_CASE_UPPER, 'UTF-8');
			} else {
				$result = $table[$lower];
				// capitalize the first letter only
				if ( ctype_upper( $match[0] ) ) {
					$result = mb_convert_case( mb_substr( $result, 0, 1, 'UTF-8' ), MB_CASE_UPPER, 'UTF-8' ) . mb_substr( $result, 1, NULL,'UTF-8' );
				}
			}
			return $result;
		};

		// FIXME: qo'shimchalar qo'shilganda ham almashtir
		// replace soft sign words
		$text = preg_replace_callback(
			'/\b('. implode( '|', array_keys( $this->softSignWords ) ) .')\b/ui',
			function( $matches ) use ( $getExceptionWords ) {
				return $getExceptionWords( $matches[1], $this->softSignWords);
			},
			$text
		);

		// replace ц
		$text = preg_replace_callback(
			'/\b('. implode( '|', array_keys( $this->tsWords ) ) .')\b/ui',
			function( $matches ) use ( $getExceptionWords ) {
				return $getExceptionWords( $matches[1], $this->tsWords);
			},
			$text
		);

		$text = preg_replace_callback(
			'/\b('. implode( '|', array_keys( $this->shWords ) ) .')\b/ui',
			function( $matches ) use ( $getExceptionWords ) {
				return $getExceptionWords( $matches[1], $this->shWords);
			},
			$text
		);

		// replace э
		$text = preg_replace_callback(
			'/\b('. implode( '|', array_keys( $this->eWords ) ) .')\b/ui',
			function( $matches ) use ( $getExceptionWords ) {
				return $getExceptionWords( $matches[1], $this->eWords);
			},
			$text
		);

		$text = preg_replace_callback(
			'/\b('. implode( '|', array_keys( $this->yoWords ) ) .')\b/ui',
			function( $matches ) use ( $getExceptionWords ) {
				return $getExceptionWords( $matches[1], $this->yoWords);
			},
			$text
		);

		$text = preg_replace_callback(
			'/\b('. implode( '|', array_keys( $this->yuWords ) ) .')\b/ui',
			function( $matches ) use ( $getExceptionWords ) {
				return $getExceptionWords( $matches[1], $this->yuWords);
			},
			$text
		);

		$text = preg_replace_callback(
			'/\b('. implode( '|', array_keys( $this->yaWords ) ) .')\b/ui',
			function( $matches ) use ( $getExceptionWords ) {
				return $getExceptionWords( $matches[1], $this->yaWords);
			},
			$text
		);

		$text = preg_replace_callback(
			'/\b('. implode( '|', array_keys( $this->yeWords ) ) .')\b/ui',
			function( $matches ) use ( $getExceptionWords ) {
				return $getExceptionWords( $matches[1], $this->yeWords);
			},
			$text
		);

		// Replace COMPOUNDS next
		$text = preg_replace_callback(
			'/(' . implode( '|', array_keys( $compoundsFirst ) ) . ')/ui',
			function( $matches ) use ( &$compoundsFirst ) {
				return $compoundsFirst[ $matches[1] ];
			},
			$text
		);
		$text = preg_replace_callback(
			'/(' . implode( '|', array_keys( $compoundsSecond ) ) . ')/ui',
			function( $matches ) use ( &$compoundsSecond ) {
				return $compoundsSecond[ $matches[1] ];
			},
			$text
		);

		// beginning rules
		$text = preg_replace_callback(
			'/\b(' . implode( '|', array_keys( $beginningRules ) ) . ')/ui',
			function( $matches ) use ( &$beginningRules ) {
				return $beginningRules[ $matches[1] ];
			},
			$text
		);

		// after a vowel
		$text = preg_replace_callback(
			'/(' . implode( '|', $this->latinVowels ) . ')(' . implode( '|', array_keys( $afterVowelRules ) ) . ')/ui',
			function( $matches ) use ( &$afterVowelRules ) {
				return $matches[1] . $afterVowelRules[ $matches[2] ];
			},
			$text
		);

		return $text;
	}

	function translate( $text, $toVariant ) {
		if ( $toVariant == 'uz-cyrl' ) {
			$text = $this->translateToCyrl( $text );
		} else if ( $toVariant == 'uz-latn' ) {
			$text = $this->translateToLatn( $text );
		}
		return parent::translate( $text, $toVariant );
	}
}

/**
 * Uzbek
 *
 * @ingroup Language
 */
class LanguageUz extends Language {
	function __construct() {
		global $wgHooks;
		parent::__construct();

		$variants = array( 'uz', 'uz-latn', 'uz-cyrl' );
		$variantFallbacks = array(
			'uz' => 'uz-latn',
			'uz-cyrl' => 'uz',
			'uz-latn' => 'uz',
		);

		$this->mConverter = new UzConverter( $this, 'uz', $variants, $variantFallbacks );
		$wgHooks['PageContentSaveComplete'][] = $this->mConverter;
	}
}
