<?php
/**
 * Exceptions Tables for Crimean Tatar (crh / Qırımtatarca)
 *
 * Adapted from https://crh.wikipedia.org/wiki/Qullan%C4%B1c%C4%B1:Don_Alessandro/Translit
 *
 * @file
 */

namespace MediaWiki\Languages\Data;

use CrhConverter as Crh;

class CrhExceptions {

	function __construct() {
		$this->loadRegs();
	}

	public $exceptionMap = [];
	public $Cyrl2LatnPatterns = [];
	public $Latn2CyrlPatterns = [];

	private $lc2uc;
	private $uc2lc;

	private function initLcUc( $lcChars, $ucChars, $reinit = false ) {
		# bail if we've already done this, unless we are re-initializing
		if ( !$reinit && $this->lc2uc && $this->uc2lc ) {
			return;
		}

		# split up the lc and uc lists in a unicode-friendly way
		$myLc = [];
		preg_match_all( '/./u', $lcChars, $myLc );
		$myLc = $myLc[0];

		$myUc = [];
		preg_match_all( '/./u', $ucChars, $myUc );
		$myUc = $myUc[0];

		# map lc to uc and vice versa
		$this->lc2uc = array_combine( array_values( $myLc ), array_values( $myUc ) );
		$this->uc2lc = array_combine( array_values( $myUc ), array_values( $myLc ) );
	}

	private function myLc( $string ) {
		return strtr( $string, $this->uc2lc );
	}

	private function myUc( $string ) {
		return strtr( $string, $this->lc2uc );
	}

	private function myUcWord( $string ) {
		return $this->myUc( mb_substr( $string, 0, 1 ) ) . $this->myLc( mb_substr( $string, 1 ) );
	}

	private function addMappings( $mapArray, &$A2B, &$B2A, $exactCase = false,
			$prePat = '', $postPat = '' ) {
		foreach ( $mapArray as $WordA => $WordB ) {
			$ucA = $this->myUc( $WordA );
			$ucWordA = $this->myUcWord( $WordA );
			$ucB = $this->myUc( $WordB );
			$ucWordB = $this->myUcWord( $WordB );

			# if there are regexes, only map toward backregs
			if ( ! preg_match( '/\$[1-9]/', $WordA ) ) {
				$A2B[ $prePat . $WordA . $postPat ] = $WordB;
				if ( ! $exactCase ) {
					$A2B[ $prePat . $ucWordA . $postPat ] = $ucWordB;
					$A2B[ $prePat . $ucA . $postPat ] = $ucB;
				}
			}

			if ( ! preg_match( '/\$[1-9]/', $WordB ) ) {
				$B2A[ $prePat . $WordB . $postPat ] = $WordA;
				if ( ! $exactCase ) {
					$B2A[ $prePat . $ucWordB . $postPat ] = $ucWordA;
					$B2A[ $prePat . $ucB . $postPat ] = $ucA;
				}
			}
		}
	}

	function loadExceptions( $lcChars, $ucChars ) {
		# init lc and uc, as needed
		$this->initLcUc( $lcChars, $ucChars );
		# load C2L and L2C whole-word exceptions into the same array, since it's just a look up
		# no regex prefix/suffix needed
		$this->addMappings( $this->wordMappings, $this->exceptionMap, $this->exceptionMap );
		$this->addMappings( $this->exactCaseMappings, $this->exceptionMap, $this->exceptionMap, true );

		# load C2L and L2C bidirectional prefix mappings
		$this->addMappings( $this->prefixMapping,
			$this->Cyrl2LatnPatterns, $this->Latn2CyrlPatterns, false, '/^', '/u' );
		$this->addMappings( $this->suffixMapping,
			$this->Cyrl2LatnPatterns, $this->Latn2CyrlPatterns, false, '/', '$/u' );

		# tack on one-way mappings to the ends of the prefix and suffix patterns
		$this->Cyrl2LatnPatterns += $this->Cyrl2LatnRegexes;
		$this->Latn2CyrlPatterns += $this->Latn2CyrlRegexes;

		return [ $this->exceptionMap, $this->Cyrl2LatnPatterns,
			$this->Latn2CyrlPatterns, $this->CyrlCleanUpRegexes ];
	}

	# map Cyrillic to Latin and back, whole word match only
	# variants: all lowercase, all uppercase, first letter capitalized
	# items with capture group refs (e.g., $1) are only mapped from the
	# regex to the reference
	private $wordMappings = [

		#### originally Cyrillic to Latin
		'аджыумер' => 'acıümer', 'аджыусеин' => 'acıüsein', 'алейкум' => 'aleyküm',
		'бейуде' => 'beyüde', 'боливия' => 'boliviya', 'большевик' => 'bolşevik', 'борис' => 'boris',
		'борнен' => 'bornen', 'бугун' => 'bugün', 'бузкесен' => 'buzkesen', 'буксир' => 'buksir',
		'бульбуль' => 'bülbül', 'бульвар' => 'bulvar', 'бульдозер' => 'buldozer', 'бульон' => 'bulyon',
		'бунен' => 'bunen', 'буннен' => 'bunnen', 'бус-бутюн' => 'büs-bütün',
		'бутерброд' => 'buterbrod', 'буфер' => 'bufer', 'буфет' => 'bufet', 'гонъюл' => 'göñül',
		'горизонт' => 'gorizont', 'госпиталь' => 'gospital', 'гуливер' => 'guliver', 'гуна' => 'güna',
		'гунях' => 'günâh', 'гургуль' => 'gürgül', 'гуя' => 'güya', 'демирёл' => 'demiryol',
		'джуньджу' => 'cüncü', 'ёлнен' => 'yolnen', 'зумбуль' => 'zümbül', 'ильи' => 'ilyi', 'ишунь' =>
		'işün', 'кодекс' => 'kodeks', 'кодифик' => 'kodifik', 'койлю' => 'köylü', 'коккоз' =>
		'kökköz', 'коккозь' => 'kökköz', 'коккозю' => 'kökközü', 'кокос' => 'kokos',
		'коллег' => 'kolleg', 'коллект' => 'kollekt', 'коллекц' => 'kollekts', 'кольцов' => 'koltsov',
		'комбин' => 'kombin', 'комедия' => 'komediya', 'коменда' => 'komenda', 'комета' => 'kometa',
		'комис' => 'komis', 'комит' => 'komit', 'комите' => 'komite', 'коммент' => 'komment',
		'коммерс' => 'kommers', 'коммерц' => 'kommerts', 'компенс' => 'kompens', 'компил' => 'kompil',
		'компьютер' => 'kompyuter', 'конвейер' => 'konveyer', 'конвен' => 'konven',
		'конверт' => 'konvert', 'конденс' => 'kondens', 'кондитер' => 'konditer',
		'кондиц' => 'kondits', 'коник' => 'konik', 'консерв' => 'konserv', 'контейнер' => 'konteyner',
		'континент' => 'kontinent', 'конфе' => 'konfe', 'конфискац' => 'konfiskats',
		'концен' => 'kontsen', 'концерт' => 'kontsert', 'конъюктур' => 'konyuktur',
		'коньки' => 'konki', 'коньяк' => 'konyak', 'копирле' => 'kopirle', 'копия' => 'kopiya',
		'корбекул' => 'körbekül', 'кореиз' => 'koreiz', 'коренн' => 'korenn', 'корея' => 'koreya',
		'коридор' => 'koridor', 'корнеев' => 'korneyev', 'корре' => 'korre', 'корьбекул' =>
		'körbekül', 'косме' => 'kosme', 'космик' => 'kosmik', 'костюм' => 'kostüm', 'котельн' =>
		'koteln', 'котировка' => 'kotirovka', 'котлет' => 'kotlet', 'кочергин' => 'koçergin',
		'коше' => 'köşe', 'кудрин' => 'kudrin', 'кузнец' => 'kuznets', 'кулинар' => 'kulinar',
		'кулич' => 'kuliç', 'кульминац' => 'kulminats', 'культив' => 'kultiv',
		'культура' => 'kultura', 'куркулет' => 'kürkület', 'курсив' => 'kursiv', 'кушку' => 'küşkü',
		'куюк' => 'küyük', 'къарагоз' => 'qaragöz', 'къолязма' => 'qolyazma', 'къуртумер' =>
		'qurtümer', 'къуртусеин' => 'qurtüsein', 'марьино' => 'maryino', 'медьюн' => 'medyun',
		'месули' => 'mesüli', 'месуль' => 'mesül', 'мефкуре' => 'mefküre', 'могедек' => 'mögedek',
		'муур' => 'müür', 'муче' => 'müçe', 'муюз' => 'müyüz', 'огнево' => 'ognevo',
		'одеколон' => 'odekolon', 'одеса' => 'odesa', 'одесса' => 'odessa', 'озерки' => 'ozerki',
		'озерн' => 'ozern', 'озёрн' => 'ozörn', 'океан' => 'okean', 'оленев' => 'olenev',
		'олимп' => 'olimp', 'ольчер' => 'ölçer', 'онен' => 'onen', 'оннен' => 'onnen',
		'опера' => 'opera', 'оптим' => 'optim', 'опци' => 'optsi', 'опция' => 'optsiya',
		'орден' => 'orden', 'ордер' => 'order', 'ореанда' => 'oreanda', 'орех' => 'oreh',
		'оригинал' => 'original', 'ориент' => 'oriyent', 'оркестр' => 'orkestr', 'орлин' => 'orlin',
		'офис' => 'ofis', 'офицер' => 'ofitser', 'офсет' => 'ofset', 'оюннен' => 'oyunnen', 'побед' =>
		'pobed', 'полево' => 'polevo', 'поли' => 'poli', 'полюшко' => 'polüşko',
		'помидор' => 'pomidor', 'пониз' => 'poniz', 'порфир' => 'porfir', 'потелов' => 'potelov',
		'почетн' => 'poçetn', 'почётн' => 'poçötn', 'публик' => 'publik', 'публиц' => 'publits',
		'пушкин' => 'puşkin', 'сеитумер' => 'seitümer', 'сеитусеин' => 'seitüsein', 'сеитягъя' =>
		'seityağya', 'сеитягья' => 'seityagya', 'сеитяхья' => 'seityahya', 'сеитяя' => 'seityaya',
		'сейитумер' => 'seyitümer', 'сейитусеин' => 'seyitüsein', 'сейитягъя' => 'seyityağya',
		'сейитягья' => 'seyityagya', 'сейитяхья' => 'seyityahya', 'сейитяя' => 'seyityaya',
		'ультимат' => 'ultimat', 'ультра' => 'ultra', 'ульянов' => 'ulyanov', 'универ' => 'univer',
		'уника' => 'unika', 'унтер' => 'unter', 'урьян' => 'uryan', 'уткин' => 'utkin', 'учебн' =>
		'uçebn', 'шовини' => 'şovini', 'шоссе' => 'şosse', 'шубин' => 'şubin', 'шунен' => 'şunen',
		'шуннен' => 'şunnen', 'щёлкино' => 'şçolkino', 'эмирусеин' => 'emirüsein',
		'юзбашы' => 'yüzbaşı', 'юзйыл' => 'yüzyıl', 'юртер' => 'yurter', 'ющенко' => 'yuşçenko',

		'кою' => 'köyü', 'кок' => 'kök', 'ком-кок' => 'köm-kök', 'коп' => 'köp', 'ог' => 'ög',
		'юрип' => 'yürip', 'юз' => 'yüz', 'юк' => 'yük', 'буюп' => 'büyüp', 'буюк' => 'büyük',
		'джонк' => 'cönk', 'джонкю' => 'cönkü', 'устке' => 'üstke', 'устте' => 'üstte',
		'усттен' => 'üstten',

		# шофёр needs to come after шофер to override it in the Latin-to-Cyrillic direction
		'шофер' => 'şoför',
		'шофёр' => 'şoför',

		#### originally Latin to Cyrillic (deduped from above)

		# слова на -аль
		# words in -аль
		'актуаль' => 'aktual', 'диагональ' => 'diagonal', 'документаль' => 'dokumental',
		'эмсаль' => 'emsal', 'фааль' => 'faal', 'феодаль' => 'feodal', 'фестиваль' => 'festival',
		'горизонталь' => 'gorizontal', 'хроникаль' => 'hronikal', 'идеаль' => 'ideal',
		'инструменталь' => 'instrumental', 'икъмаль' => 'iqmal', 'икъбаль' => 'iqbal',
		'истикъбаль' => 'istiqbal', 'истикъляль' => 'istiqlâl', 'италия' => 'italiya',
		'италья' => 'italya', 'ишгъаль' => 'işğal', 'кафедраль' => 'kafedral', 'казуаль' => 'kazual',
		'коллегиаль' => 'kollegial', 'колоссаль' => 'kolossal', 'коммуналь' => 'kommunal',
		'кординаль' => 'kordinal', 'криминаль' => 'kriminal', 'легаль' => 'legal', 'леталь' => 'letal',
		'либераль' => 'liberal', 'локаль' => 'lokal', 'магистраль' => 'magistral',
		'материаль' => 'material', 'машиналь' => 'maşinal', 'меаль' => 'meal',
		'медальон' => 'medalyon', 'медаль' => 'medal', 'меридиональ' => 'meridional',
		'мешъаль' => 'meşal', 'минераль' => 'mineral', 'минималь' => 'minimal', 'мисаль' => 'misal',
		'модаль' => 'modal', 'музыкаль' => 'muzıkal', 'номиналь' => 'nominal', 'нормаль' => 'normal',
		'оптималь' => 'optimal', 'орбиталь' => 'orbital', 'оригиналь' => 'original',
		'педаль' => 'pedal', 'пропорциональ' => 'proportsional', 'профессиональ' => 'professional',
		'радикаль' => 'radikal', 'рациональ' => 'ratsional', 'реаль' => 'real',
		'региональ' => 'regional', 'суаль' => 'sual', 'шималь' => 'şimal',
		'территориаль' => 'territorial', 'тимсаль' => 'timsal', 'тоталь' => 'total',
		'уникаль' => 'unikal', 'универсаль' => 'universal', 'вертикаль' => 'vertikal',
		'виртуаль' => 'virtual', 'визуаль' => 'vizual', 'вуаль' => 'vual', 'зональ' => 'zonal',
		'зуаль' => 'zual',

		# слова с мягким знаком перед а, о, у, э
		# Words with a soft sign before а, о, у, э
		'бильакис' => 'bilakis', 'маальэсеф' => 'maalesef',
		'мельун' => 'melun', 'озьара' => 'özara', 'вельасыл' => 'velasıl',
		'ельаякъ' => 'yelayaq',
		# these are ordered so C2L is correct (the later Latin one)
		'февкъульаде' => 'fevqülade','февкъульаде' => 'fevqulade',

		# другие слова с мягким знаком
		# Other words with a soft sign
		'альбатрос' => 'albatros', 'альбинос' => 'albinos', 'альбом' => 'albom',
		'альбумин' => 'albumin', 'алфавит' => 'alfavit', 'альфа' => 'alfa', 'альманах' => 'almanah',
		'альпинист' => 'alpinist', 'альтерн' => 'altern', 'альтру' => 'altru', 'альвеола' => 'alveola',
		'ансамбль' => 'ansambl', 'аньане' => 'anane', 'асфальт' => 'asfalt', 'бальнео' => 'balneo',
		'баарь' => 'baar', 'базальт' => 'bazalt', 'бинокль' => 'binokl', 'джурьат' => 'curat',
		'джурьат' => 'cürat', 'девальв' => 'devalv', 'факульт' => 'fakult', 'фальсиф' => 'falsif',
		'фольклор' => 'folklor', 'гальван' => 'galvan', 'геральд' => 'gerald', 'женьшень' => 'jenşen',
		'инвентарь' => 'inventar', 'кальк' => 'kalk', 'кальмар' => 'kalmar', 'консульт' => 'konsult',
		'контроль' => 'kontrol', 'кульмин' => 'kulmin', 'культур' => 'kultur', 'лагерь' => 'lager',
		'макъбуль' => 'maqbul', 'макъуль' => 'maqul', 'мальт' => 'malt', 'мальземе' => 'malzeme',
		'меджуль' => 'mecul', 'мешгуль' => 'meşgül', 'мешгъуль' => 'meşğul', 'мульти' => 'multi',
		'мусульман' => 'musulman', 'нефть' => 'neft', 'пальто' => 'palto', 'пароль' => 'parol',
		'патруль' => 'patrul', 'пенальти' => 'penalti', 'къальби' => 'qalbi', 'къальпке' => 'qalpke',
		'къальплер' => 'qalpler', 'къальпни' => 'qalpni', 'къальпте' => 'qalpte', 'къаарь' => 'qaar',
		'ресуль' => 'resul', 'рыцарь' => 'rıtsar', 'рояль' => 'royal', 'саарь' => 'saar',
		'спираль' => 'spiral', 'сульх' => 'sulh', 'сумбуль' => 'sumbul', 'суньий' => 'suniy',
		'темаюль' => 'temayul', 'шампунь' => 'şampun', 'вальс' => 'vals', 'вальц' => 'valts',
		'ведомость' => 'vedomost', 'зулькъарнейн' => 'zulqarneyn', 'январь' => 'yanvar',
		'февраль' => 'fevral', 'июнь' => 'iyün', 'сентябрь' => 'sentâbr', 'октябрь' => 'oktâbr',
		'ноябрь' => 'noyabr', 'декабрь' => 'dekabr',

		# слова с твёрдым знаком
		# Words with a solid sign
		'бидъат' => 'bidat', 'бузъюрек' => 'buzyürek', 'атешъюрек' => 'ateşyürek',
		'алъянакъ' => 'alyanaq', 'демиръёл' => 'demiryol', 'деръал' => 'deral', 'инъекц' => 'inyekts',
		'мефъум' => 'mefum', 'мешъум' => 'meşum', 'объект' => 'obyekt', 'разъезд' => 'razyezd',
		'субъект' => 'subyekt', 'хавъяр' => 'havyar', 'ямъям' => 'yamyam',

		# слова с буквой щ
		# words with щ
		'ящик' => 'yaşçik', 'мещан' => 'meşçan',

		# слова с буквой ц
		# words with ц
		'акциз' => 'aktsiz', 'ацет' => 'atset', 'блиц' => 'blits', 'бруцеллёз' => 'brutsellöz',
		'доцент' => 'dotsent', 'фармацевт' => 'farmatsevt', 'глицер' => 'glitser',
		'люцерна' => 'lütserna', 'лицей' => 'litsey', 'меццо' => 'metstso', 'наци' => 'natsi',
		'проце' => 'protse', 'рецеп' => 'retsep', 'реценз' => 'retsenz', 'теплица' => 'teplitsa',
		'вице' => 'vitse', 'цепс' => 'tseps', 'швейцар' => 'şveytsar',

		# слова без буквы тс
		# words with тс
		'агъартс' => 'ağarts', 'агъыртс' => 'ağırts', 'бильдиртс' => 'bildirts', 'битсин' => 'bitsin',
		'буюльтс' => 'büyülts', 'буютс' => 'büyüts', 'гебертс' => 'geberts', 'делиртс' => 'delirts',
		'эгрильтс' => 'egrilts', 'эксильтс' => 'eksilts', 'эшитс' => 'eşits', 'иритс' => 'irits',
		'иситс' => 'isits', 'ичиртс' => 'içirts', 'кертсин' => 'kertsin', 'кенишлетс' => 'kenişlets',
		'кийсетс' => 'kiysets', 'копюртс' => 'köpürts', 'косьтертс' => 'kösterts',
		'кучертс' => 'küçerts', 'кучюльтс' => 'küçülts', 'пертсин' => 'pertsin', 'къайтс' => 'qayts',
		'къутсуз' => 'qutsuz', 'орьтс' => 'örts', 'отьс' => 'öts', 'тартс' => 'tarts',
		'тутсун' => 'tutsun', 'тюнъюльтс' => 'tüñülts', 'тюртс' => 'türts', 'янъартс' => 'yañarts',
		'ебертс' => 'yeberts', 'етсин' => 'yetsin', 'ешертс' => 'yeşerts', 'йиритс' => 'yirits',

		# разные исключения
		# different exceptions
		'бейуде' => 'beyude', 'бугунь' => 'bugün', 'бюджет' => 'bücet', 'бюллет' => 'büllet',
		'бюро' => 'büro', 'бюст' => 'büst', 'джонк' => 'cönk', 'диалог' => 'dialog',
		'гонъюль' => 'göñül', 'ханымэфенди' => 'hanımefendi', 'каньон' => 'kanyon', 'кирил' => 'kiril',
		'кирил' => 'kirill', 'кёрджа' => 'körca', 'кой' => 'köy', 'кулеръюзь' => 'küleryüz',
		'маалле' => 'маальle', 'майор' => 'mayor', 'маниал' => 'manиаль', 'мефкуре' => 'mefküre',
		'месуль' => 'mesul', 'месуль' => 'mesül', 'муурь' => 'müür',
		'нормала' => 'нормальa', 'нумюне' => 'nümüne', 'проект' => 'proekt', 'район' => 'rayon',
		'сойады' => 'soyadı', 'спортсмен' => 'sportsmen', 'услюп' => 'üslüp', 'услюб' => 'üslüb',
		'вакъиал' => 'vaqиаль', 'юзйыллыкъ' => 'yüzyıllıq',

		# имена собственные
		# proper names
		'адольф' => 'adolf', 'альберт' => 'albert', 'бешуй' => 'beşüy', 'эмирусеин' => 'emirüsein',
		'флотск' => 'flotsk', 'гайана' => 'gayana', 'грэсовский' => 'gresovskiy', 'гриц' => 'grits',
		'гурджи' => 'gürci', 'игорь' => 'igor', 'ильич' => 'ilyiç', 'ильин' => 'ilyin',
		'исмаил' => 'ismail', 'киттс' => 'kitts', 'комсомольск' => 'komsomolsk',
		'корьбекулю' => 'körbekülü', 'корьбекуль' => 'körbekül', 'куницын' => 'kunitsın',
		'львив' => 'lviv', 'львов' => 'lvov', 'марьино' => 'maryino', 'махульдюр' => 'mahuldür',
		'павел' => 'pavel', 'пантикапейон' => 'pantikapeyon', 'къарагозь' => 'qaragöz',
		'къуртсейит' => 'qurtseyit', 'къуртсеит' => 'qurtseit', 'къуртумер' => 'qurtümer',
		'сейитумер' => 'seyitümer', 'сеитумер' => 'seitümer', 'смаил' => 'smail',
		'советск' => 'sovetsk', 'шемьи-заде' => 'şemi-zade', 'щёлкино' => 'şçolkino',
		'тсвана' => 'tsvana', 'учьэвли' => 'üçevli', 'йохан' => 'yohan', 'йорк' => 'york',
		'ющенко' => 'yuşçenko', 'льная' => 'lnaya', 'льное' => 'lnoye', 'льный' => 'lnıy',
		'льская' => 'lskaya', 'льский' => 'lskiy', 'льское' => 'lskoye', 'ополь' => 'opol',

		# originally Latin to Cyrillic, deduped from above
		'ань' => 'an', 'аньге' => 'ange', 'аньде' => 'ande', 'аньки' => 'anki', 'кёр' => 'kör',
		'мэр' => 'mer', 'этсин' => 'etsin',

		# exceptions added after speaker review
		# see https://www.mediawiki.org/wiki/User:TJones_(WMF)/T23582
		'аджизленювинъиз' => 'acizlenüviñiz', 'акъшам' => 'aqşam', 'алчакъгонъюлли' => 'alçaqgöñülli',
		'аньанелер' => 'ananeler', 'аньанелеримиз' => 'ananelerimiz',
		'аньанелеримизден' => 'ananelerimizden', 'аньанелеримизни' => 'ananelerimizni',
		'аньанели' => 'ananeli', 'асфальтке' => 'asfaltke', 'баарьде' => 'baarde', 'бахтсыз' => 'bahtsız',
		'берилюви' => 'berilüvi', 'берювден' => 'berüvden', 'берювни' => 'berüvni',
		'большевиклер' => 'bolşevikler', 'большевиклерге' => 'bolşeviklerge', 'болюк' => 'bölük',
		'болюнген' => 'bölüngen', 'болюнгенини' => 'bölüngenini', 'болюшип' => 'bölüşip',
		'бугуннинъ' => 'bugünniñ', 'бугуньден' => 'bugünden', 'бугуньки' => 'bugünki',
		'букюльген' => 'bükülgen', 'букюльди' => 'büküldi', 'буллюр' => 'büllür',
		'бурюмчик' => 'bürümçik', 'бурюнген' => 'bürüngen', 'бутюн' => 'bütün', 'бутюнлей' => 'bütünley',
		'буюген' => 'büyügen', 'буюй' => 'büyüy', 'волость' => 'volost', 'волостьларгъа' => 'volostlarğa',
		'гонъюлини' => 'göñülini', 'гонъюлли' => 'göñülli', 'гонъюллилер' => 'göñülliler',
		'госпиталинде' => 'gospitalinde', 'госпитальге' => 'gospitalge', 'госпитальде' => 'gospitalde',
		'гренадёр' => 'grenadör', 'гугюм' => 'gügüm', 'гугюмлер' => 'gügümler',
		'гугюмлери' => 'gügümleri', 'гугюмлерини' => 'gügümlerini', 'гурьсюльди' => 'gürsüldi',
		'гурюльдештилер' => 'gürüldeştiler', 'гурюльти' => 'gürülti', 'гурюльтили' => 'gürültili',
		'гурюльтисидир' => 'gürültisidir', 'дарульмуаллиминде' => 'darülmualliminde',
		'дарульмуаллимининде' => 'darülmuallimininde', 'дарульмуаллиминнинъ' => 'darülmualliminniñ',
		'дёгюльген' => 'dögülgen', 'декабрьде' => 'dekabrde', 'дёндюрилип' => 'döndürilip',
		'дёнермиз' => 'dönermiz', 'дёнмектелер' => 'dönmekteler', 'денъишюв' => 'deñişüv',
		'дёрдю' => 'dördü', 'дёрдюмиз' => 'dördümiz', 'дёрдюнджи' => 'dördünci', 'дёрт' => 'dört',
		'дертлешювге' => 'dertleşüvge', 'джесюр' => 'cesür', 'джесюране' => 'cesürane',
		'джесюрликлерини' => 'cesürliklerini', 'джонегенлерини' => 'cönegenlerini',
		'джонедим' => 'cönedim', 'джонейлер' => 'cöneyler', 'джурьатсызлыгъына' => 'cüratsızlığına',
		'дюгюнлер' => 'dügünler', 'дюгюнлерле' => 'dügünlerle', 'дюдюк' => 'düdük', 'дюльбер' => 'dülber',
		'дюльбери' => 'dülberi', 'дюльберлер' => 'dülberler', 'дюльберлернинъ' => 'dülberlerniñ',
		'дюльгер' => 'dülger', 'дюльгерге' => 'dülgerge', 'дюльгерлернинъки' => 'dülgerlerniñki',
		'дюльгерни' => 'dülgerni', 'дюльгернинъ' => 'dülgerniñ', 'дюмбюрдетти' => 'dümbürdetti',
		'дюмен' => 'dümen', 'дюмени' => 'dümeni', 'дюнья' => 'dünya', 'дюньявий' => 'dünyaviy',
		'дюньяда' => 'dünyada', 'дюньяларгъа' => 'dünyalarğa', 'дюньяларда' => 'dünyalarda',
		'дюньяны' => 'dünyanı', 'дюньянынъ' => 'dünyanıñ', 'дюньясы' => 'dünyası',
		'ельаякълылар' => 'yelayaqlılar', 'елькъуваны' => 'yelquvanı', 'ильич' => 'i̇liç',
		'ичюн' => 'içün', 'ичюнми' => 'içünmi', 'келюви' => 'kelüvi', 'келювини' => 'kelüvini',
		'келювинъизде' => 'kelüviñizde', 'келювни' => 'kelüvni', 'кемирювлер' => 'kemirüvler',
		'кесювде' => 'kesüvde', 'кетюв' => 'ketüv', 'кетювге' => 'ketüvge', 'кетюви' => 'ketüvi',
		'кетювимни' => 'ketüvimni', 'кетювлер' => 'ketüvler', 'кетювлери' => 'ketüvleri',
		'кетювлеринънинъ' => 'ketüvleriñniñ', 'кетювнинъ' => 'ketüvniñ', 'кирюв' => 'kirüv',
		'князь' => 'knâz', 'козькъапакъларыны' => 'közqapaqlarını', 'козьлю' => 'közlü', 'козю' => 'közü',
		'козюме' => 'közüme', 'козюнде' => 'közünde', 'козюне' => 'közüne', 'козюнен' => 'közünen',
		'козюнинъ' => 'közüniñ', 'козюнъни' => 'közüñni', 'койлюде' => 'köylüde',
		'койлюлер' => 'köylüler', 'койлюлерде' => 'köylülerde', 'койлюлерни' => 'köylülerni',
		'койлюлернинъ' => 'köylülerniñ', 'койлюнинъ' => 'köylüniñ', 'коккозьге' => 'kökközge',
		'коккозьде' => 'kökközde', 'коккозьдеки' => 'kökközdeki', 'коккозьден' => 'kökközden',
		'кокюс' => 'köküs', 'кокюси' => 'köküsi', 'кокюсим' => 'köküsim', 'кокюсиме' => 'köküsime',
		'кокюсинъе' => 'köküsiñe', 'комиссарлар' => 'komissarlar', 'комиссарлары' => 'komissarları',
		'комитетининъ' => 'komitetiniñ', 'концлагерь' => 'kontslager', 'копьмеди' => 'köpmedi',
		'копьти' => 'köpti', 'копюр' => 'köpür', 'копюрге' => 'köpürge', 'копюрден' => 'köpürden',
		'копюри' => 'köpüri', 'копюрнинъ' => 'köpürniñ', 'коридорда' => 'koridorda',
		'корьсюн' => 'körsün', 'корюв' => 'körüv', 'корюльген' => 'körülgen', 'корюнди' => 'köründi',
		'корюндинъ' => 'köründiñ', 'корюне' => 'körüne', 'корюнип' => 'körünip',
		'корюнмеген' => 'körünmegen', 'корюнмеди' => 'körünmedi', 'корюнмедилер' => 'körünmediler',
		'корюнмей' => 'körünmey', 'корюнмейсинъиз' => 'körünmeysiñiz', 'корюнмекте' => 'körünmekte',
		'корюнмектелер' => 'körünmekteler', 'корюнъиз' => 'körüñiz', 'корюше' => 'körüşe',
		'корюшеджекмиз' => 'körüşecekmiz', 'корюшим' => 'körüşim', 'корюшип' => 'körüşip',
		'корюширмиз' => 'körüşirmiz', 'корюшкен' => 'körüşken', 'корюшкенде' => 'körüşkende',
		'корюшмеге' => 'körüşmege', 'корюшмегенимиз' => 'körüşmegenimiz', 'корюштик' => 'körüştik',
		'корюштим' => 'körüştim', 'корюшюв' => 'körüşüv', 'корюшювде' => 'körüşüvde',
		'корюшювден' => 'körüşüvden', 'корюшюви' => 'körüşüvi', 'корюшювимден' => 'körüşüvimden',
		'корюшювимизге' => 'körüşüvimizge', 'корюшювимизден' => 'körüşüvimizden',
		'костюми' => 'kostümi', 'кузю' => 'küzü', 'кулькюден' => 'külküden', 'кулькюнинъ' => 'külküniñ',
		'кулькюсининъ' => 'külküsiniñ', 'кулю' => 'külü', 'кулюмсиреген' => 'külümsiregen',
		'кулюмсиреди' => 'külümsiredi', 'кулюмсиредим' => 'külümsiredim', 'кулюмсирей' => 'külümsirey',
		'кулюмсирейим' => 'külümsireyim', 'кулюмсиреп' => 'külümsirep', 'кулюни' => 'külüni',
		'кулюнчли' => 'külünçli', 'кулюшинде' => 'külüşinde', 'кулюштилер' => 'külüştiler',
		'кумюш' => 'kümüş', 'куньдюз' => 'kündüz', 'куньдюзлери' => 'kündüzleri', 'куньлюк' => 'künlük',
		'куню' => 'künü', 'кунюмде' => 'künümde', 'кунюнде' => 'kününde', 'кунюндеми' => 'künündemi',
		'кунюнъ' => 'künüñ', 'курькчю' => 'kürkçü', 'курьсю' => 'kürsü', 'курьсюге' => 'kürsüge',
		'курьсюлер' => 'kürsüler', 'курючтен' => 'kürüçten', 'кутюклерни' => 'kütüklerni',
		'кутюкли' => 'kütükli', 'кучьлю' => 'küçlü', 'кучьлюклер' => 'küçlükler',
		'кучьсюнмезсинъ' => 'küçsünmezsiñ', 'кучюджик' => 'küçücik', 'кучюк' => 'küçük',
		'кучюм' => 'küçüm', 'кучюмле' => 'küçümle', 'кучюнден' => 'küçünden', 'кучюни' => 'küçüni',
		'къаарьлене' => 'qaarlene', 'къаарьли' => 'qaarli', 'къальбим' => 'qalbim',
		'къальбимни' => 'qalbimni', 'къальбинде' => 'qalbinde', 'къальпли' => 'qalpli',
		'къальптен' => 'qalpten', 'къалюбелядан' => 'qalübelâdan', 'къулюбенъде' => 'qulübeñde',
		'лёман' => 'löman', 'львованынъ' => 'lvovanıñ', 'лютфи' => 'lütfi', 'лютфиге' => 'lütfige',
		'лютфини' => 'lütfini', 'мазюн' => 'mazün', 'малюм' => 'malüm', 'малюмат' => 'malümat',
		'махлюкъаттан' => 'mahlüqattan', 'махлюкътан' => 'mahlüqtan', 'махульдюрге' => 'mahuldürge',
		'махульдюрде' => 'mahuldürde', 'махульдюрдеки' => 'mahuldürdeki',
		'махульдюрден' => 'mahuldürden', 'махульдюрли' => 'mahuldürli',
		'махульдюрлилер' => 'mahuldürliler', 'махульдюрлилермиз' => 'mahuldürlilermiz',
		'махульдюрми' => 'mahuldürmi', 'махульдюрни' => 'mahuldürni', 'мевджут' => 'mevcut',
		'мезкюр' => 'mezkür', 'мектюп' => 'mektüp', 'мектюпни' => 'mektüpni', 'мектюпте' => 'mektüpte',
		'мелитопольге' => 'melitopolge', 'мемнюн' => 'memnün', 'мемнюниетле' => 'memnüniyetle',
		'мемнюним' => 'memnünim', 'мемнюнмиз' => 'memnünmiz', 'менсюп' => 'mensüp',
		'мешгъульмиз' => 'meşğulmiz', 'мулькюни' => 'mülküni', 'мумкюн' => 'mümkün',
		'мумкюнми' => 'mümkünmi', 'мусульманлар' => 'musulmanlar', 'мусульманлармы' => 'musulmanlarmı',
		'мухкемлендирюв' => 'mühkemlendirüv', 'мушкюль' => 'müşkül', 'ничюн' => 'niçün',
		'ничюндир' => 'niçündir', 'нумюнеси' => 'nümünesi', 'огю' => 'ögü', 'огюз' => 'ögüz',
		'огюмде' => 'ögümde', 'огюмдеки' => 'ögümdeki', 'огюме' => 'ögüme', 'огюмизге' => 'ögümizge',
		'огюмизде' => 'ögümizde', 'огюмиздеки' => 'ögümizdeki', 'огюмни' => 'ögümni',
		'огюнде' => 'ögünde', 'огюндеки' => 'ögündeki', 'огюндекиси' => 'ögündekisi',
		'огюнден' => 'ögünden', 'огюне' => 'ögüne', 'огюнъизде' => 'ögüñizde', 'огютини' => 'ögütini',
		'огютлерини' => 'ögütlerini', 'озю' => 'özü', 'озюм' => 'özüm', 'озюмден' => 'özümden',
		'озюме' => 'özüme', 'озюмизни' => 'özümizni', 'озюмизнинъ' => 'özümizniñ',
		'озюмизнинъки' => 'özümizniñki', 'озюмнен' => 'özümnen', 'озюмни' => 'özümni',
		'озюмнинъ' => 'özümniñ', 'озюнде' => 'özünde', 'озюнден' => 'özünden', 'озюне' => 'özüne',
		'озюнен' => 'özünen', 'озюни' => 'özüni', 'озюнинъ' => 'özüniñ', 'озюнинъкими' => 'özüniñkimi',
		'озюнъ' => 'özüñ', 'озюнъе' => 'özüñe', 'озюнъиз' => 'özüñiz', 'озюнъиздеки' => 'özüñizdeki',
		'озюнъни' => 'özüñni', 'оксюз' => 'öksüz', 'окюндим' => 'ökündim', 'ольдюрип' => 'öldürip',
		'ольдюрмек' => 'öldürmek', 'ольдюрювде' => 'öldürüvde', 'ольчюде' => 'ölçüde', 'олюм' => 'ölüm',
		'олюмден' => 'ölümden', 'олюмлер' => 'ölümler', 'омюр' => 'ömür', 'омюрге' => 'ömürge',
		'омюри' => 'ömüri', 'опькеленюв' => 'öpkelenüv', 'орьтилюви' => 'örtilüvi', 'орьтюли' => 'örtüli',
		'орюли' => 'örüli', 'орюлип' => 'örülip', 'осюв' => 'ösüv', 'осюмлик' => 'ösümlik',
		'отькерювни' => 'ötkerüvni', 'отькюр' => 'ötkür', 'офицери' => 'ofitseri',
		'офицерим' => 'ofitserim', 'офицерлер' => 'ofitserler', 'пальтосыны' => 'paltosını',
		'пальтосынынъ' => 'paltosınıñ', 'пекинюв' => 'pekinüv', 'пекитювнинъ' => 'pekitüvniñ',
		'пиширюв' => 'pişirüv', 'повидло' => 'povidlo', 'полис' => 'polis', 'полициясы' => 'politsiyası',
		'помещик' => 'pomeşçik', 'потюк' => 'potük', 'потюклеринен' => 'potüklerinen',
		'пулемёт' => 'pülemöt', 'пулемётларны' => 'pülemötlarnı', 'режиссёр' => 'rejissör',
		'ролюнде' => 'rolünde', 'севастопольнинъ' => 'sevastopolniñ', 'сёгди' => 'sögdi', 'сёз' => 'söz',
		'сёзлер' => 'sözler', 'сёзлери' => 'sözleri', 'сёзлерим' => 'sözlerim',
		'сёзлеримден' => 'sözlerimden', 'сёзлериме' => 'sözlerime', 'сёзлеримни' => 'sözlerimni',
		'сёзлеримнинъ' => 'sözlerimniñ', 'сёзлеринде' => 'sözlerinde', 'сёзлерине' => 'sözlerine',
		'сёзлерини' => 'sözlerini', 'сёзлерининъ' => 'sözleriniñ', 'сёзлеринъиз' => 'sözleriñiz',
		'сёзлеринъизни' => 'sözleriñizni', 'сёзлернен' => 'sözlernen', 'сёзлерни' => 'sözlerni',
		'сёзлернинъ' => 'sözlerniñ', 'сёзнен' => 'söznen', 'сёзни' => 'sözni', 'сёзчиклер' => 'sözçikler',
		'сёзчиклерден' => 'sözçiklerden', 'сёзю' => 'sözü', 'сёзюмен' => 'sözümen',
		'сёзюмнинъ' => 'sözümniñ', 'сёзюне' => 'sözüne', 'сёзюни' => 'sözüni', 'сёзюнинъ' => 'sözüniñ',
		'сёйле' => 'söyle', 'сёйлегенде' => 'söylegende', 'сёйлегенлеринден' => 'söylegenlerinden',
		'сёйледи' => 'söyledi', 'сёйлей' => 'söyley', 'сёйленди' => 'söylendi',
		'сёйленмеге' => 'söylenmege', 'сёйленмекте' => 'söylenmekte', 'сёйленъиз' => 'söyleñiz',
		'сёнген' => 'söngen', 'сёнди' => 'söndi', 'сёндюрди' => 'söndürdi',
		'сёндюрильген' => 'söndürilgen', 'сёндюрип' => 'söndürip', 'сентябрьнинъ' => 'sentâbrniñ',
		'сергюзешт' => 'sergüzeşt', 'сергюзештлерни' => 'sergüzeştlerni',
		'ставропольге' => 'stavropolge', 'сулькевич' => 'sulkeviç', 'сурьат' => 'surat',
		'суфлёр' => 'suflör', 'сюеги' => 'süyegi', 'сюеклерге' => 'süyeklerge',
		'сюйрекледи' => 'süyrekledi', 'сюйреле' => 'süyrele', 'сюйрен' => 'süyren',
		'сюйренге' => 'süyrenge', 'сюйренде' => 'süyrende', 'сюйреп' => 'süyrep', 'сюйрю' => 'süyrü',
		'сюкюнет' => 'sükünet', 'сюкюнети' => 'süküneti', 'сюкюнетте' => 'sükünette', 'сюкют' => 'süküt',
		'сюляле' => 'sülâle', 'сюрген' => 'sürgen', 'сюрди' => 'sürdi', 'сюрмеди' => 'sürmedi',
		'сюрюльмеген' => 'sürülmegen', 'сют' => 'süt', 'тебессюм' => 'tebessüm', 'тёкип' => 'tökip',
		'тёкти' => 'tökti', 'тёкюльген' => 'tökülgen', 'тёкюльди' => 'töküldi',
		'тёкюндиси' => 'tökündisi', 'тёле' => 'töle', 'тёледим' => 'töledim', 'телюке' => 'telüke',
		'телюкели' => 'telükeli', 'тенеффюс' => 'teneffüs', 'тенеффюслер' => 'teneffüsler',
		'тёпеге' => 'töpege', 'тёпелери' => 'töpeleri', 'тёпелерине' => 'töpelerine',
		'тёпели' => 'töpeli', 'тёпеси' => 'töpesi', 'тёпесинден' => 'töpesinden',
		'тёпесини' => 'töpesini', 'тёрге' => 'törge', 'тёрде' => 'törde', 'тёрдеки' => 'tördeki',
		'тёрюне' => 'törüne', 'тешеббюсим' => 'teşebbüsim', 'тёшегинден' => 'töşeginden',
		'тёшегине' => 'töşegine', 'тёшек' => 'töşek', 'тешеккюр' => 'teşekkür',
		'тешеккюрлер' => 'teşekkürler', 'тёшекни' => 'töşekni', 'тёшектен' => 'töşekten',
		'тёшели' => 'töşeli', 'тёшемек' => 'töşemek', 'тёшеп' => 'töşep', 'теэссюф' => 'teessüf',
		'тюбю' => 'tübü', 'тюбюнде' => 'tübünde', 'тюбюндеки' => 'tübündeki', 'тюз' => 'tüz',
		'тюзельгенге' => 'tüzelgenge', 'тюзельтмек' => 'tüzeltmek', 'тюземликлер' => 'tüzemlikler',
		'тюзетип' => 'tüzetip', 'тюзетирим' => 'tüzetirim', 'тюзеткен' => 'tüzetken',
		'тюзетмеге' => 'tüzetmege', 'тюзетмесенъ' => 'tüzetmeseñ', 'тюзетти' => 'tüzetti',
		'тюзетюв' => 'tüzetüv', 'тюкенмез' => 'tükenmez', 'тюкюриктен' => 'tükürikten',
		'тюкян' => 'tükân', 'тюкяны' => 'tükânı', 'тюкянында' => 'tükânında', 'тюм' => 'tüm',
		'тюневин' => 'tünevin', 'тюневинки' => 'tünevinki', 'тюпсюз' => 'tüpsüz', 'тюрк' => 'türk',
		'тюрклернинъ' => 'türklerniñ', 'тюркнинъ' => 'türkniñ', 'тюркче' => 'türkçe', 'тюркю' => 'türkü',
		'тюркюлерини' => 'türkülerini', 'тюркюнинъ' => 'türküniñ', 'тюрлю' => 'türlü',
		'тюртип' => 'türtip', 'тюрттинъиз' => 'türttiñiz', 'тютемекте' => 'tütemekte', 'тютюн' => 'tütün',
		'тютюнджи' => 'tütünci', 'тюфеги' => 'tüfegi', 'тюфегини' => 'tüfegini', 'тюфек' => 'tüfek',
		'тюфеклеринен' => 'tüfeklerinen', 'тюфеклернен' => 'tüfeklernen', 'тюфеклерни' => 'tüfeklerni',
		'тюфекнен' => 'tüfeknen', 'тюфексиз' => 'tüfeksiz', 'тюш' => 'tüş', 'тюше' => 'tüşe',
		'тюшеджек' => 'tüşecek', 'тюшеджексинъми' => 'tüşeceksiñmi', 'тюшем' => 'tüşem',
		'тюшип' => 'tüşip', 'тюшкен' => 'tüşken', 'тюшкенде' => 'tüşkende', 'тюшкенлер' => 'tüşkenler',
		'тюшмеге' => 'tüşmege', 'тюшмейим' => 'tüşmeyim', 'тюшмейлер' => 'tüşmeyler',
		'тюшмек' => 'tüşmek', 'тюшмекте' => 'tüşmekte', 'тюшмеси' => 'tüşmesi', 'тюшсе' => 'tüşse',
		'тюшти' => 'tüşti', 'тюштик' => 'tüştik', 'тюштилер' => 'tüştiler', 'тюштими' => 'tüştimi',
		'тюштинъиз' => 'tüştiñiz', 'тюшювден' => 'tüşüvden', 'тюшюджек' => 'tüşücek',
		'тюшюнген' => 'tüşüngen', 'тюшюнгендже' => 'tüşüngence', 'тюшюндже' => 'tüşünce',
		'тюшюнджеге' => 'tüşüncege', 'тюшюнджелер' => 'tüşünceler', 'тюшюнджелери' => 'tüşünceleri',
		'тюшюнджелерим' => 'tüşüncelerim', 'тюшюнджели' => 'tüşünceli', 'тюшюнджеси' => 'tüşüncesi',
		'тюшюнди' => 'tüşündi', 'тюшюндим' => 'tüşündim', 'тюшюне' => 'tüşüne',
		'тюшюнелер' => 'tüşüneler', 'тюшюнесинъиз' => 'tüşünesiñiz', 'тюшюнип' => 'tüşünip',
		'тюшюнмеге' => 'tüşünmege', 'тюшюнмезсинъ' => 'tüşünmezsiñ', 'тюшюнмей' => 'tüşünmey',
		'тюшюнмемек' => 'tüşünmemek', 'тюшюргенлер' => 'tüşürgenler', 'тюшюрди' => 'tüşürdi',
		'тюшюрдик' => 'tüşürdik', 'тюшюре' => 'tüşüre', 'тюшюрип' => 'tüşürip', 'тюшюрмек' => 'tüşürmek',
		'уджюм' => 'ücüm', 'удюр' => 'üdür', 'узюле' => 'üzüle', 'узюлип' => 'üzülip',
		'узюльгенини' => 'üzülgenini', 'узюльди' => 'üzüldi', 'уйрюлип' => 'üyrülip',
		'укюмет' => 'ükümet', 'укюмети' => 'ükümeti', 'укюметими' => 'ükümetimi',
		'укюметимиз' => 'ükümetimiz', 'укюметини' => 'ükümetini', 'укюметининъ' => 'ükümetiniñ',
		'укюметке' => 'ükümetke', 'укюметкеми' => 'ükümetkemi', 'укюметми' => 'ükümetmi',
		'укюметнинъ' => 'ükümetniñ', 'укюметтен' => 'ükümetten', 'укюмран' => 'ükümran',
		'улькюн' => 'ülkün', 'умюдим' => 'ümüdim', 'умют' => 'ümüt', 'умютлери' => 'ümütleri',
		'умютсизден' => 'ümütsizden', 'усть' => 'üst', 'устьке' => 'üstke', 'устьлеринде' => 'üstlerinde',
		'устьлериндеки' => 'üstlerindeki', 'устьлерине' => 'üstlerine', 'устьлерини' => 'üstlerini',
		'устюрткъа' => 'üsturtqa', 'усьнюхаткъа' => 'üsnühatqa', 'усьнюхаты' => 'üsnühatı',
		'усьтю' => 'üstü', 'усьтюмде' => 'üstümde', 'усьтюмдеки' => 'üstümdeki', 'усьтюме' => 'üstüme',
		'усьтюнде' => 'üstünde', 'усьтюндеки' => 'üstündeki', 'усьтюндемиз' => 'üstündemiz',
		'усьтюне' => 'üstüne', 'усьтюни' => 'üstüni', 'усьтюнлик' => 'üstünlik',
		'усьтюнъизге' => 'üstüñizge', 'утёкунь' => 'ütökün', 'уфюрди' => 'üfürdi', 'учю' => 'üçü',
		'учюмиз' => 'üçümiz', 'учюн' => 'üçün', 'учюнджи' => 'üçünci', 'учюнджисининъ' => 'üçüncisiniñ',
		'ушюй' => 'üşüy', 'ушюмез' => 'üşümez', 'ушюмезсинъ' => 'üşümezsiñ',
		'факультетинде' => 'fakultetinde', 'факультетине' => 'fakultetine',
		'февральнинъ' => 'fevralniñ', 'харьковдаки' => 'harkovdaki', 'харьковдан' => 'harkovdan',
		'чёкти' => 'çökti', 'чёкюрли' => 'çökürli', 'чёкюч' => 'çöküç', 'чёллюкке' => 'çöllükke',
		'чёль' => 'çöl', 'чёльде' => 'çölde', 'чёльмек' => 'çölmek', 'чёткю' => 'çötkü',
		'чёчамийлер' => 'çöçamiyler', 'чюнки' => 'çünki', 'чюрюди' => 'çürüdi', 'чюрюк' => 'çürük',
		'шукюр' => 'şükür', 'шукюрлер' => 'şükürler', 'этюв' => 'etüv', 'этювден' => 'etüvden',
		'этюви' => 'etüvi', 'этюдлар' => 'etüdlar', 'юзден' => 'yüzden', 'юзлеп' => 'yüzlep',
		'юзлерини' => 'yüzlerini', 'юзлернен' => 'yüzlernen', 'юзлюги' => 'yüzlügi',
		'юзлюкке' => 'yüzlükke', 'юзю' => 'yüzü', 'юзюм' => 'yüzüm', 'юзюме' => 'yüzüme',
		'юзюмен' => 'yüzümen', 'юзюмни' => 'yüzümni', 'юзюнде' => 'yüzünde', 'юзюни' => 'yüzüni',
		'юзюнинъ' => 'yüzüniñ', 'юзюнъ' => 'yüzüñ', 'юзюнъизге' => 'yüzüñizge', 'юклю' => 'yüklü',
		'юксельтюв' => 'yükseltüv', 'юньлю' => 'yünlü', 'юньлюдже' => 'yünlüce',
		'юртсеверлик' => 'yurtseverlik', 'юртюде' => 'yürtüde', 'юрьтю' => 'yürtü',
		'юрьтюге' => 'yürtüge', 'юрьтюнинъ' => 'yürtüniñ', 'юрюльсе' => 'yürülse', 'юрюнъиз' => 'yürüñiz',
		'юрюш' => 'yürüş', 'юрюши' => 'yürüşi', 'юрюшим' => 'yürüşim', 'юрюшини' => 'yürüşini',
		'юрюшнен' => 'yürüşnen', 'юрюшни' => 'yürüşni',
	];

	# map Cyrillic to Latin and back, whole word match only
	# no variants: map exactly as is
	# items with capture group refs (e.g., $1) are only mapped from the
	# regex to the reference
	private $exactCaseMappings = [
		# аббревиатуры
		# abbreviations
		'ОБСЕ' => 'OBSE', 'КъМДж' => 'QMC', 'КъАЭ' => 'QAE', 'ГъСМК' => 'ĞSMK', 'ШСДжБ' => 'ŞSCB',
		'КъМШСДж' => 'QMŞSC', 'КъДМПУ' => 'QDMPU', 'КъМПУ' => 'QMPU', 'КъЮШ' => 'QYŞ', 'ЮШ' => 'YŞ',
	];

	# map Cyrillic to Latin and back, match end of word
	# variants: all lowercase, all uppercase, first letter capitalized
	# "first letter capitalized" variant was in the source
	# items with capture group refs (e.g., $1) are only mapped from the
	# regex to the reference
	private $suffixMapping = [
		# originally C2L
		'иаль' => 'ial', 'нуль' => 'nul', 'кой' => 'köy', 'койнинъ' => 'köyniñ', 'койни' => 'köyni',
		'койге' => 'köyge', 'койде' => 'köyde', 'койдеки' => 'köydeki', 'койден' => 'köyden',
		'козь' => 'köz',

		# originally L2C, here swapped
		'етсин' => 'etsin',

	];

	# map Cyrillic to Latin and back, match beginning of word
	# variants: all lowercase, all uppercase, first letter capitalized
	# items with capture group refs (e.g., $1) are only mapped from the
	# regex to the reference
	private $prefixMapping = [
		# originally C2L
		'буюк([^ъ])' => 'büyük$1', 'бую([гдйлмнпрстчшc])(и)' => 'büyü$1$2',
		'буют([^ыа])' => 'büyüt$1', 'джонк([^ъ])' => 'cönk$1', 'коюм' => 'köyüm', 'коюнъ' => 'köyüñ',
		'коюн([ди])' => 'köyün$1', 'куе' => 'küye', 'куркке' => 'kürkke', 'куркни' => 'kürkni',
		'куркте' => 'kürkte', 'куркчи' => 'kürkçi', 'куркчю' => 'kürkçü',

		# арабизмы на муи- муэ- / Arabic муи- муэ-
		'му([иэИЭ])' => 'mü$1',

		# originally L2C, here swapped
		'итъаль' => 'ital',
		'роль$1' => 'rol([^ü])',
		'усть$1' => 'üst([knt])',

	];

	private $Cyrl2LatnRegexes = [];
	private $Latn2CyrlRegexes = [];

	function loadRegs() {
		// Regexes as keys need to be declared in a function.
		$this->Cyrl2LatnRegexes = [
			############################
			# относятся ко всему слову #
			# whole words              #
			############################
			'/\b([34])(\-)юнджи\b/u' => '$1$2ünci',
			'/\b([34])(\-)ЮНДЖИ\b/u' => '$1$2ÜNCİ',

			# отдельно стоящие Ё и Я
			# stand-alone Ё and Я
			'/\bЯ\b/u' => 'Ya',
			'/\bЁ\b/u' => 'Yo',

			############################
			# относятся к началу слова #
			# word prefixes            #
			############################
			'/\bКъЮШн/u' => 'QYŞn',
			'/\bЮШн/u' => 'YŞn',

			# о => ö
			'/\b(['.Crh::C_M_CONS.'])о(['.Crh::C_CONS.'])(['.Crh::C_CONS.'])([еиэюьü])/u' => '$1ö$2$3$4',
			'/\bо(['.Crh::C_CONS.'])(['.Crh::C_CONS.'])([еиэюьü])/u' => 'ö$1$2$3',
			'/\b(['.Crh::C_M_CONS.'])О(['.Crh::C_CONS.'])(['.Crh::C_CONS.'])([еиэюьüЕИЭЮЬÜ])/u' =>
				'$1Ö$2$3$4',
			'/\bО(['.Crh::C_CONS.'])(['.Crh::C_CONS.'])([еиэюьüЕИЭЮЬÜ])/u' => 'Ö$1$2$3',

			'/\b(['.Crh::C_M_CONS.'])о(['.Crh::C_CONS.'])([еиэюьü])/u' => '$1ö$2$3',
			'/\bо(['.Crh::C_CONS.'])([еиэюьü])/u' => 'ö$1$2',
			'/\b(['.Crh::C_M_CONS.'])О(['.Crh::C_CONS.'])([еиэюьüЕИЭЮЬÜ])/u' => '$1Ö$2$3',
			'/\bО(['.Crh::C_CONS.'])([еиэюьüЕИЭЮЬÜ])/u' => 'Ö$1$2',

			# ё => yö
			'/\bё(['.Crh::C_CONS.'])(['.Crh::C_CONS.'])([ьеюü])/u' => 'yö$1$2$3',
			'/\bЁ(['.Crh::C_CONS_LC.'])(['.Crh::C_CONS_LC.'])([ьеюü])/u' => 'Yö$1$2$3',
			'/\bЁ(['.Crh::C_CONS_UC.'])(['.Crh::C_CONS_UC.'])([ЬЕЮÜ])/u' => 'YÖ$1$2$3',
			'/\bё(['.Crh::C_CONS.'])([ьеюü])/u' => 'yö$1$2',
			'/\bЁ(['.Crh::C_CONS_LC.'])([ьеюü])/u' => 'Yö$1$2',
			'/\bЁ(['.Crh::C_CONS_UC.'])([ЬЕЮÜ])/u' => 'YÖ$1$2',

			# у => ü, ую => üyü
			'/\b(['.Crh::C_M_CONS.'])у(['.Crh::C_CONS.'])(['.Crh::C_CONS.'])([еиэюьü])/u' => '$1ü$2$3$4',
			'/\bу(['.Crh::C_CONS.'])(['.Crh::C_CONS.'])([еиэюьü])/u' => 'ü$1$2$3',
			'/\bую(['.Crh::C_CONS.'])(['.Crh::C_CONS.'])([еиэюьü])/u' => 'üyü$1$2$3',
			'/\b(['.Crh::C_M_CONS.'])У(['.Crh::C_CONS.'])(['.Crh::C_CONS.'])([еиэюьüЕИЭЮЬÜ])/u' =>
				'$1Ü$2$3$4',
			'/\bУ(['.Crh::C_CONS.'])(['.Crh::C_CONS.'])([еиэюьüЕИЭЮЬÜ])/u' => 'Ü$1$2$3',
			'/\bУю(['.Crh::C_CONS.'])(['.Crh::C_CONS.'])([еиэюьü])/u' => 'Üyü$1$2$2',
			'/\bУЮ(['.Crh::C_CONS.'])(['.Crh::C_CONS.'])([еиэюьü])/u' => 'ÜYÜ$1$2$3',

			'/\b(['.Crh::C_M_CONS.'])у(['.Crh::C_CONS.'])([еиэюьü])/u' => '$1ü$2$3',
			'/\bу(['.Crh::C_CONS.'])([еиэюьü])/u' => 'ü$1$2',
			'/\bую(['.Crh::C_CONS.'])([еиэюьü])/u' => 'üyü$1$2',
			'/\b(['.Crh::C_M_CONS.'])У(['.Crh::C_CONS.'])([еиэюьüЕИЭЮЬÜ])/u' => '$1Ü$2$3',
			'/\bУ(['.Crh::C_CONS.'])([еиэюьüЕИЭЮЬÜ])/u' => 'Ü$1$2',
			'/\bУю(['.Crh::C_CONS.'])([еиэюьü])/u' => 'Üyü$1$2',
			'/\bУЮ(['.Crh::C_CONS.'])([еиэюьü])/u' => 'ÜYÜ$1$2',

			# ю => yü
			'/\b([аыоуеиёюАЫОУЕИЁЮ]?)ю(['.Crh::C_CONS.'])(['.Crh::C_CONS.'])([ьеюü])/u' => '$1yü$2$3$4',
			'/\b([АЫОУЕИЁЮ]?)Ю(['.Crh::C_CONS_LC.'])(['.Crh::C_CONS_LC.'])([ьеюü])/u' => '$1Yü$2$3$4',
			'/\b([АЫОУЕИЁЮ]?)Ю(['.Crh::C_CONS_UC.'])(['.Crh::C_CONS_UC.'])([ЬЕЮÜ])/u' => '$1YÜ$2$3$4',
			'/\b([аыоуеиёюАЫОУЕИЁЮ]?)ю(['.Crh::C_CONS.'])([ьеюü])/u' => '$1yü$2$3',
			'/\b([АЫОУЕИЁЮ]?)Ю(['.Crh::C_CONS_LC.'])([ьеюü])/u' => '$1Yü$2$3',
			'/\b([АЫОУЕИЁЮ]?)Ю(['.Crh::C_CONS_UC.'])([ЬЕЮÜ])/u' => '$1YÜ$2$3',

			# e => ye, я => ya
			'/\bе/u' => 'ye',
			'/\bЕ(['.Crh::C_LC.'cğñqöü])/u' => 'Ye$1',
			'/\bЕ(['.Crh::C_UC.'CĞÑQÖÜ])/u' => 'YE$1',
			'/\bя/u' => 'ya',
			'/\bЯ(['.Crh::C_LC.'cğñqöü])/u' => 'Ya$1',
			'/\bЯ(['.Crh::C_UC.'CĞÑQÖÜ])/u' => 'YA$1',
			'/([аеёиоуыэюяйьъaeöüАЕЁИОУЫЭЮЯЙЬЪAEÖÜ])е/u' => '$1ye',
			'/([аеёиоуыэюяйьъaeöüАЕЁИОУЫЭЮЯЙЬЪAEÖÜ])Е(['.Crh::C_LC.'cğñqöü])/u' => '$1Ye$2',
			'/([аеёиоуыэюяйьъaeöüАЕЁИОУЫЭЮЯЙЬЪAEÖÜ])Е(['.Crh::C_UC.'CĞÑQÖÜ])/u' => '$1YE$2',
			'/([аеёиоуыэюяйьъaeöüğqАЕЁИОУЫЭЮЯЙЬЪAEÖÜĞQ])я/u' => '$1ya',
			'/([аеёиоуыэюяйьъaeöüğqАЕЁИОУЫЭЮЯЙЬЪAEÖÜĞQ])Я(['.Crh::C_LC.'cğñqöü])/u' => '$1Ya$2',
			'/([аеёиоуыэюяйьъaeöüğqАЕЁИОУЫЭЮЯЙЬЪAEÖÜĞQ])Я(['.Crh::C_UC.'CĞÑQÖÜ])/u' => '$1YA$2',

			###############################
			# не зависят от места в слове #
			# position independent        #
			###############################

			# слова на -льон
			# words with -льон
			'/льон/u' => 'lyon',
			'/ЛЬОН/u' => 'LYON',

			'/козь([^я])/u' => 'köz$1',
			'/Козь([^я])/u' => 'Köz$1',
			'/КОЗЬ([^Я])/u' => 'KÖZ$1',

			# Ö, Ü 1-й заход: ё, ю после согласных > ö, ü
			# Ö, Ü 1st instance: ё, ю after consonants > ö, ü
			'/(['.Crh::C_CONS.'])ю/u' => '$1ü',
			'/(['.Crh::C_CONS.'])Ю/u' => '$1Ü',
			'/(['.Crh::C_CONS.'])ё/u' => '$1ö',
			'/(['.Crh::C_CONS.'])Ё/u' => '$1Ö',

			# остальные вхождения о, у, ё, ю
			# other occurences of о, у, ё, ю
			'/Ё(['.Crh::C_UC.'CĞÑQÖÜ])/u' => 'YO$2',
			'/Ю(['.Crh::C_UC.'CĞÑQÖÜ])/u' => 'YU$2',

			# Ц & Щ
			'/Ц(['.Crh::C_UC.'CĞÑQÖÜ])/u' => 'TS$2',
			'/Щ(['.Crh::C_UC.'CĞÑQÖÜ])/u' => 'ŞÇ$2',
		];

		$this->Latn2CyrlRegexes = [
			# буква Ё - первый заход
			# расставляем Ь после согласных
			'/^([yY])ö(['.Crh::L_N_CONS.'])([aAuU'.Crh::L_CONS.']|$)/u' => '$1ö$2ь$3',
			'/^([yY])Ö(['.Crh::L_N_CONS.'])([aAuU'.Crh::L_CONS.']|$)/u' => '$1Ö$2Ь$3',
			'/^AQŞ(['.Crh::WORD_ENDS.'ngd])/u' => 'АКъШ$1',

			# буква Ю - первый заход
			# расставляем Ь после согласных
			'/^([yY])ü(['.Crh::L_N_CONS.'])([aAuU'.Crh::L_CONS.']|$)/u' => '$1ü$2ь$3',
			'/^([yY])Ü(['.Crh::L_N_CONS.'])([aAuU'.Crh::L_CONS.']|$)/u' => '$1Ü$2Ь$3',

			'/^([bcgkpşBCGKPŞ])ö(['.Crh::L_N_CONS.'])(['.Crh::L_CONS.']|$)/u' => '$1ö$2ь$3',
			'/^([bcgkpşBCGKPŞ])Ö(['.Crh::L_N_CONS.'])(['.Crh::L_CONS.']|$)/u' => '$1Ö$2Ь$3',
			'/^([bcgkpşBCGKPŞ])Ö(['.Crh::L_N_CONS.'])(['.Crh::L_CONS.']|$)/u' => '$1Ö$2Ь$3',
			'/^([bcgkpşBCGKPŞ])ü(['.Crh::L_N_CONS.'])(['.Crh::L_CONS.']|$)/u' => '$1ü$2ь$3',
			'/^([bcgkpşBCGKPŞ])Ü(['.Crh::L_N_CONS.'])(['.Crh::L_CONS.']|$)/u' => '$1Ü$2Ь$3',
			'/^([bcgkpşBCGKPŞ])Ü(['.Crh::L_N_CONS.'])(['.Crh::L_CONS.']|$)/u' => '$1Ü$2Ь$3',

			 # ö и ü в начале слова
			 # случаи, когда нужен Ь
			'/^ö(['.Crh::L_N_CONS.'pP])(['.Crh::L_CONS.']|$)/u' => 'ö$1ь$2',
			'/^Ö(['.Crh::L_N_CONS_LC.'p])(['.Crh::L_CONS.']|$)/u' => 'Ö$1ь$2',
			'/^Ö(['.Crh::L_N_CONS_UC.'P])(['.Crh::L_CONS.']|$)/u' => 'Ö$1Ь$2',
			'/^ü(['.Crh::L_N_CONS.'])(['.Crh::L_CONS.']|$)/u' => 'ü$1ь$2',
			'/^Ü(['.Crh::L_N_CONS_LC.'])(['.Crh::L_CONS.']|$)/u' => 'Ü$1ь$2',
			'/^Ü(['.Crh::L_N_CONS_UC.'])(['.Crh::L_CONS.']|$)/u' => 'Ü$1Ь$2',

			'/ts$/u' => 'ц',
			'/şç$/u' => 'щ',
			'/Ş[çÇ]$/u' => 'Щ',
			'/T[sS]$/u' => 'Ц',

			# Ь после Л
			# add Ь after Л
			'/(['.Crh::L_F.'])l(['.Crh::L_CONS_LC.']|$)/u' => '$1ль$2',
			'/(['.Crh::L_F_UC.'])L(['.Crh::L_CONS.']|$)/u' => '$1ЛЬ$2',

			# относятся к началу слова
			'/^ts/u' => 'ц',
			'/^T[sS]/u' => 'Ц',

			'/^şç/u' => 'щ',
			'/^Ş[çÇ]/u' => 'Щ',

			# Э
			'/(^|['.Crh::L_VOW.'аеэяАЕЭЯ])e/u' => '$1э',
			'/(^|['.Crh::L_VOW_UC.'АЕЭЯ])E/u' => '$1Э',

			'/^(['.Crh::L_M_CONS.'])ö/u' => '$1о',
			'/^(['.Crh::L_M_CONS.'])Ö/u' => '$1О',
			'/^(['.Crh::L_M_CONS.'])ü/u' => '$1у',
			'/^(['.Crh::L_M_CONS.'])Ü/u' => '$1У',

			'/^ö/u' => 'о',
			'/^Ö/u' => 'О',
			'/^ü/u' => 'у',
			'/^Ü/u' => 'У',

			# некоторые исключения
			# some exceptions
			'/maal([^e])/u' => 'мааль$1',
			'/Maal([^e])/u' => 'Мааль$1',
			'/MAAL([^E])/u' => 'МААЛЬ$1',
			'/küf([^eü])/u' => 'куфь$1',
			'/Küf([^eü])/u' => 'Куфь$1',
			'/KÜF([^EÜ])/u' => 'КУФЬ$1',
			'/köz([^eü])/u' => 'козь$1',
			'/Köz([^eü])/u' => 'Козь$1',
			'/KÖZ([^EÜ])/u' => 'КОЗЬ$1',

			# Punctuation
			'/#|No\./' => '№',

			# некоторые случаи употребления Ц
			'/tsi([^zñ])/u' => 'ци$1',
			'/T[sS][iİ]([^zZñÑ])/u' => 'ЦИ$1',
			'/ts([ou])/u' => 'ц$1',
			'/T[sS]([oOuU])/u' => 'Ц$1',
			'/ts(['.Crh::L_CONS.'])/u' => 'ц$1',
			'/T[sS](['.Crh::L_CONS.'])/u' => 'Ц$1',
			'/(['.Crh::L_CONS.'])ts/u' => '$1ц',
			'/(['.Crh::L_CONS.'])T[sS]/u' => '$1Ц',
			'/tsиал/u' => 'циал',
			'/TSИАЛ/u' => 'ЦИАЛ',

			# убираем ьi
			# remove ьi (note Cyrillic ь and Latin i)
			'/[ьЬ]([iİ])/u' => '$1',

			# ya & ye
			'/(['.Crh::L_CONS.'])ya/u' => '$1ья',
			'/(['.Crh::L_CONS.'])Y[aA]/u' => '$1ЬЯ',
			'/(['.Crh::L_CONS.'])ye/u' => '$1ье',
			'/(['.Crh::L_CONS.'])Y[eE]/u' => '$1ЬЕ',

			 # расставляем Ь перед Ё
			 # place Ь in front of Ё
			'/(['.Crh::L_CONS.'])y[oö]/u' => '$1ьё',
			'/(['.Crh::L_CONS.'])Y[oOöÖ]/u' => '$1ЬЁ',
			 # оставшиеся вхождения yo и yö
			 # remaining occurrences of yo and yö
			'/y[oö]/u' => 'ё',
			'/[yY][oOöÖ]/u' => 'Ё',

			 # расставляем Ь перед Ю
			 # place Ь in front of Ю
			'/(['.Crh::L_CONS.'])y[uü]/u' => '$1ью',
			'/(['.Crh::L_CONS.'])Y[uUüÜ]/u' => '$1ЬЮ',
			 # оставшиеся вхождения yu и yü
			 # remaining occurrences of yu and yü
			'/y[uü]/u' => 'ю',
			'/[yY][uUüÜ]/u' => 'Ю',

			# убираем ьa
			# remove ьa (note Cyrillic ь and Latin a)
			'/[ьЬ]([aA])/u' => '$1',

			# дж
			'/C(['.Crh::L_UC.Crh::C_UC.'Ъ])/u' => 'ДЖ$1',

			# гъ, къ, нъ
			# гъ, къ, нъ
			'/Ğ(['.Crh::L_UC.Crh::C_UC.'Ъ])/u' => 'ГЪ$1',
			'/Q(['.Crh::L_UC.Crh::C_UC.'Ъ])/u' => 'КЪ$1',
			'/Ñ(['.Crh::L_UC.Crh::C_UC.'Ъ])/u' => 'НЪ$1',

		];
	}

	private $CyrlCleanUpRegexes = [
		'/([клнрст])ь\1/u' => '$1$1',
		'/([КЛНРСТ])Ь\1/u' => '$1$1',
		'/К[ьЬ]к/u' => 'Кк',
		'/Л[ьЬ]л/u' => 'Лл',
		'/Н[ьЬ]н/u' => 'Нн',
		'/Р[ьЬ]р/u' => 'Рр',
		'/С[ьЬ]с/u' => 'Сс',
		'/Т[ьЬ]т/u' => 'Тт',

		# убираем ьы и ь..ы
		# remove ьы и ь..ы
		'/[ьЬ]ы/u' => 'ы',
		'/ЬЫ/u' => 'Ы',
		'/[ьЬ]([гдклмнпрстчшГДКЛМНПРСТЧШ])ы/u' => '$1ы',
		'/Ь([гдклмнпрстчшГДКЛМНПРСТЧШ])Ы/u' => '$1Ы',
		'/[ьЬ]([гкнГКН])([ъЪ])ы/u' => '$1$2ы',
		'/Ь([ГКН])ЪЫ/u' => '$1ЪЫ',

		# убираем йь
		# remove йь
		'/йь/u' => 'й',
		'/ЙЬ/u' => 'Й',

		# частичное решение проблемы слова юз - 100
		# Partial solution of the problem of the word юз ("100")
		# notice that these are cross-word patterns
		'/эки юзь/u' => 'эки юз', '/Эки юзь/u' => 'Эки юз', '/ЭКИ ЮЗЬ/u' => 'ЭКИ ЮЗ',
		'/учь юзь/u' => 'учь юз', '/Учь юзь/u' => 'Учь юз', '/УЧЬ ЮЗЬ/u' => 'УЧЬ ЮЗ',
		'/дёрт юзь/u' => 'дёрт юз', '/Дёрт юзь/u' => 'Дёрт юз', '/ДЁРТ ЮЗЬ/u' => 'ДЁРТ ЮЗ',
		'/беш юзь/u' => 'беш юз', '/Беш юзь/u' => 'Беш юз', '/БЕШ ЮЗЬ/u' => 'БЕШ ЮЗ',
		'/алты юзь/u' => 'алты юз', '/Алты юзь/u' => 'Алты юз', '/АЛТЫ ЮЗЬ/u' => 'АЛТЫ ЮЗ',
		'/еди юзь/u' => 'еди юз', '/Еди юзь/u' => 'Еди юз', '/ЕДИ ЮЗЬ/u' => 'ЕДИ ЮЗ',
		'/секиз юзь/u' => 'секиз юз', '/Секиз юзь/u' => 'Секиз юз', '/СЕКИЗ ЮЗЬ/u' => 'СЕКИЗ ЮЗ',
		'/докъуз юзь/u' => 'докъуз юз', '/Докъуз юзь/u' => 'Докъуз юз', '/ДОКЪУЗ ЮЗЬ/u' => 'ДОКЪУЗ ЮЗ',
	];
}
