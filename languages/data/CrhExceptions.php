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

	const WB = '\b'; # default word boundary; may be updated in the future

	function __construct() {
		$this->loadRegs();
	}

	public $Cyrl2LatnExceptions = [];
	public $Latn2CyrlExceptions = [];

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
			if ( ! $exactCase ) {
				$ucA = $this->myUc( $WordA );
				$ucWordA = $this->myUcWord( $WordA );
				$ucB = $this->myUc( $WordB );
				$ucWordB = $this->myUcWord( $WordB );
			}

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

		# no regex prefix/suffix needed
		$this->addMappings( $this->ManyToOneC2LMappings,
			// reverse exception mapping order to handle many-to-one C2L mappings
			$this->Latn2CyrlExceptions, $this->Cyrl2LatnExceptions );
		$this->addMappings( $this->multiCaseMappings,
			$this->Cyrl2LatnExceptions, $this->Latn2CyrlExceptions );
		$this->addMappings( $this->exactCaseMappings,
			$this->Cyrl2LatnExceptions, $this->Latn2CyrlExceptions, true );

		# load C2L and L2C bidirectional affix mappings
		$this->addMappings( $this->prefixMapping,
			$this->Cyrl2LatnPatterns, $this->Latn2CyrlPatterns, false, '/' . self::WB, '/u' );
		$this->addMappings( $this->suffixMapping,
			$this->Cyrl2LatnPatterns, $this->Latn2CyrlPatterns, false, '/', self::WB . '/u' );

		# tack on one-way mappings to the ends of the prefix and suffix patterns
		$this->Cyrl2LatnPatterns += $this->Cyrl2LatnRegexes;
		$this->Latn2CyrlPatterns += $this->Latn2CyrlRegexes;

		return [ $this->Cyrl2LatnExceptions, $this->Latn2CyrlExceptions, $this->Cyrl2LatnPatterns,
			$this->Latn2CyrlPatterns, $this->CyrlCleanUpRegexes ];
	}

	# map Latin to Cyrillic and back, simple string match only (no regex)
	# variants: all lowercase, all uppercase, first letter capitalized
	private $ManyToOneC2LMappings = [
		# Carefully ordered many-to-one mappings
		# these are ordered so C2L is correct (the later Latin one)
		# see also L2C mappings below
		'fevqülade' => 'февкъульаде', 'fevqulade' => 'февкъульаде',
		'beyude' => 'бейуде', 'beyüde' => 'бейуде',
		'curat' => 'джурьат', 'cürat' => 'джурьат',
		'mesul' => 'месуль', 'mesül' => 'месуль',
	];

	# map Cyrillic to Latin and back, simple string match only (no regex)
	# variants: all lowercase, all uppercase, first letter capitalized
	private $multiCaseMappings = [

		#### Cyrillic to Latin
		'аджыумер' => 'acıümer', 'аджыусеин' => 'acıüsein', 'алейкум' => 'aleyküm',
		'бозтюс' => 'boztüs', 'боливия' => 'boliviya', 'большевик' => 'bolşevik', 'борис' => 'boris',
		'борнен' => 'bornen', 'бублик' => 'bublik', 'буддизм' => 'buddizm', 'буддист' => 'buddist',
		'буженина' => 'bujenina', 'бузкесен' => 'buzkesen', 'букинист' => 'bukinist',
		'буксир' => 'buksir', 'бульбул' => 'bülbül', 'бульвар' => 'bulvar', 'бульдог' => 'buldog',
		'бульдозер' => 'buldozer', 'бульон' => 'bulyon', 'бумеранг' => 'bumerang',
		'бунен' => 'bunen', 'буннен' => 'bunnen', 'бус-бутюн' => 'büs-bütün',
		'бутерброд' => 'buterbrod', 'бутилен' => 'butilen', 'бутилир' => 'butilir',
		'буфер' => 'bufer', 'буфет' => 'bufet', 'гобелен' => 'gobelen', 'гомео' => 'gomeo',
		'горизонт' => 'gorizont', 'госпитал' => 'gospital', 'готтентот' => 'gottentot',
		'гофрир' => 'gofrir', 'губерн' => 'gubern', 'гуверн' => 'guvern', 'гугенот' => 'gugenot',
		'гуливер' => 'guliver', 'гуна' => 'güna', 'гунях' => 'günâh', 'гургуль' => 'gürgül',
		'гуя' => 'güya', 'дёрткуль' => 'dörtkül', 'джуньджу' => 'cüncü', 'ёлнен' => 'yolnen',
		'зумбуль' => 'zümbül', 'ильи' => 'ilyi', 'ишунь' => 'işün', 'ковер' => 'kover', 'код' => 'kod',
		'койлю' => 'köylü', 'кокагъач' => 'kökağaç', 'кокбаштанкъара' => 'kökbaştanqara',
		'кокгогерджин' => 'kökgögercin', 'кокдогъан' => 'kökdoğan', 'коккозю' => 'kökközü',
		'коккъузгъун' => 'kökquzğun', 'коклюш' => 'koklüş', 'кокташ' => 'köktaş',
		'коктогъан' => 'köktoğan', 'коктотай' => 'köktotay', 'коллег' => 'kolleg',
		'коллект' => 'kollekt', 'коллекц' => 'kollekts', 'колье' => 'kolye', 'кольраби' => 'kolrabi',
		'кольцов' => 'koltsov', 'комби' => 'kombi', 'комеди' => 'komedi', 'коменда' => 'komenda',
		'комета' => 'kometa', 'комив' => 'komiv', 'комис' => 'komis', 'комит' => 'komit',
		'комм' => 'komm', 'коммент' => 'komment', 'коммерс' => 'kommers', 'коммерц' => 'kommerts',
		'комп' => 'komp', 'конве' => 'konve', 'конгени' => 'kongeni', 'конденс' => 'kondens',
		'кондил' => 'kondil', 'кондитер' => 'konditer', 'кондиц' => 'kondits', 'коник' => 'konik',
		'конкис' => 'konkis', 'консерв' => 'konserv', 'конси' => 'konsi', 'контейнер' => 'konteyner',
		'конти' => 'konti', 'конфе' => 'konfe', 'конфи' => 'konfi', 'конце' => 'kontse',
		'конъю' => 'konyu', 'коньки' => 'konki', 'коньяк' => 'konyak', 'копирле' => 'kopirle',
		'копия' => 'kopiya', 'корде' => 'korde', 'кореиз' => 'koreiz', 'коренн' => 'korenn',
		'корея' => 'koreya', 'кориа' => 'koria', 'коридор' => 'koridor', 'корне' => 'korne',
		'корнеев' => 'korneyev', 'корни' => 'korni', 'корре' => 'korre', 'косме' => 'kosme',
		'космик' => 'kosmik', 'костюм' => 'kostüm', 'котельн' => 'koteln', 'котир' => 'kotir',
		'котлет' => 'kotlet', 'кочерг' => 'koçerg', 'коше' => 'köşe', 'куби' => 'kubi',
		'кудрин' => 'kudrin', 'кузнец' => 'kuznets', 'кулинар' => 'kulinar', 'кулич' => 'kuliç',
		'кульмин' => 'kulmin', 'культаш' => 'kültaş', 'культе' => 'külte', 'культ' => 'kult',
		'куркулет' => 'kürkület', 'курсив' => 'kursiv', 'кушет' => 'kuşet', 'кушку' => 'küşkü',
		'куюк' => 'küyük', 'къолязма' => 'qolyazma', 'къуртумер' => 'qurtümer',
		'къуртусеин' => 'qurtüsein', 'медьюн' => 'medyun', 'месули' => 'mesüli',
		'мефкуре' => 'mefküre', 'могедек' => 'mögedek', 'мумиё' => 'mumiyo', 'мумиф' => 'mumif',
		'муче' => 'müçe', 'муюз' => 'müyüz', 'нумюне' => 'nümüne', 'обел' => 'obel', 'обер' => 'ober',
		'обли' => 'obli', 'обсе' => 'obse', 'обт' => 'obt', 'огне' => 'ogne', 'одеколон' => 'odekolon',
		'одеса' => 'odesa', 'одесса' => 'odessa', 'озерки' => 'ozerki', 'озерн' => 'ozern',
		'озёрн' => 'ozörn', 'озюя' => 'özüya', 'океан' => 'okean', 'окси' => 'oksi',
		'октет' => 'oktet', 'олеа' => 'olea', 'олеи' => 'olei', 'оленев' => 'olenev', 'олив' => 'oliv',
		'олиг' => 'olig', 'олимп' => 'olimp', 'олиф' => 'olif', 'ольчер' => 'ölçer', 'омле' => 'omle',
		'онен' => 'onen', 'оннен' => 'onnen', 'опера' => 'opera', 'опере' => 'opere',
		'оптим' => 'optim', 'опци' => 'optsi', 'орби' => 'orbi', 'орден' => 'orden',
		'ордер' => 'order', 'ордин' => 'ordin', 'ореа' => 'orea', 'орех' => 'oreh',
		'ориент' => 'oriyent', 'оркестр' => 'orkestr', 'орлин' => 'orlin', 'орни' => 'orni',
		'орхи' => 'orhi', 'осци' => 'ostsi', 'офис' => 'ofis', 'офиц' => 'ofits', 'офсет' => 'ofset',
		'очерк' => 'oçerk', 'оюннен' => 'oyunnen', 'побед' => 'pobed', 'полево' => 'polevo',
		'поли' => 'poli', 'полюшко' => 'polüşko', 'помидор' => 'pomidor', 'пониз' => 'poniz',
		'порфир' => 'porfir', 'потелов' => 'potelov', 'потюк' => 'pötük', 'почетн' => 'poçetn',
		'почётн' => 'poçötn', 'пукле' => 'pükle', 'пуркю' => 'pürkü', 'пурумют' => 'purümüt',
		'пускул' => 'püskül', 'пускур' => 'püskür', 'пусюр' => 'püsür', 'пуфле' => 'püfle',
		'сейитумер' => 'seyitümer', 'сейитусеин' => 'seyitüsein', 'сейитягъя' => 'seyityağya',
		'сейитягья' => 'seyityagya', 'сейитяхья' => 'seyityahya', 'сейитяя' => 'seyityaya',
		'сеитумер' => 'seitümer', 'сеитусеин' => 'seitüsein', 'сеитягъя' => 'seityağya',
		'сеитягья' => 'seityagya', 'сеитяхья' => 'seityahya', 'сеитяя' => 'seityaya',
		'сурет' => 'süret', 'увертюра' => 'uvertüra', 'угле' => 'ugle', 'узвий' => 'uzviy',
		'улица' => 'ulitsa', 'ультимат' => 'ultimat', 'ультра' => 'ultra', 'ульянов' => 'ulyanov',
		'универ' => 'univer', 'уник' => 'unik', 'унис' => 'unis', 'унит' => 'unit', 'униф' => 'unif',
		'унтер' => 'unter', 'урьян' => 'uryan', 'утил' => 'util', 'уткин' => 'utkin',
		'учебн' => 'uçebn', 'шовини' => 'şovini', 'шоссе' => 'şosse', 'шубин' => 'şubin',
		'шунен' => 'şunen', 'шуннен' => 'şunnen', 'шунчюн' => 'şunçün', 'щёлкино' => 'şçolkino',
		'эмирусеин' => 'emirüsein', 'юзбашы' => 'yüzbaşı', 'юзйыл' => 'yüzyıl', 'юртер' => 'yurter',
		'ющенко' => 'yuşçenko',

		### Carefully ordered many-to-one mappings
		# these are ordered so L2C is correct (the later Cyrillic one)
		# see also $ManyToOneC2LMappings above for C2L
		'шофер' => 'şoför', 'шофёр' => 'şoför',
		'бугун' => 'bugün', 'бугунь' => 'bugün',
		'демирёл' => 'demiryol', 'демиръёл' => 'demiryol',
		'гонъюл' => 'göñül', 'гонъюль' => 'göñül',
		'коккоз' => 'kökköz', 'коккозь' => 'kökköz',
		'корбекул' => 'körbekül', 'корьбекул' => 'körbekül', 'корьбекуль' => 'körbekül',
		'муур' => 'müür', 'муурь' => 'müür',
		'оригинал' => 'original', 'оригиналь' => 'original',
		'пускю' => 'püskü', 'пуськю' => 'püskü',
		'къарагоз' => 'qaragöz', 'къарагозь' => 'qaragöz',

		#### Latin to Cyrillic (deduped from above)

		# слова на -аль
		# words in -аль
		'актуаль' => 'aktual', 'диагональ' => 'diagonal', 'документаль' => 'dokumental',
		'эмсаль' => 'emsal', 'фааль' => 'faal', 'феодаль' => 'feodal', 'фестиваль' => 'festival',
		'горизонталь' => 'gorizontal', 'хроникаль' => 'hronikal', 'идеаль' => 'ideal',
		'инструменталь' => 'instrumental', 'икъмаль' => 'iqmal', 'икъбаль' => 'iqbal',
		'истикъбаль' => 'istiqbal', 'истикъляль' => 'istiqlâl', 'италия' => 'italiya',
		'италья' => 'italya', 'ишгъаль' => 'işğal', 'кафедраль' => 'kafedral', 'казуаль' => 'kazual',
		'коллегиаль' => 'kollegial', 'колоссаль' => 'kolossal', 'коммуналь' => 'kommunal',
		'кординаль' => 'kordinal', 'криминаль' => 'kriminal', 'легаль' => 'legal',
		'леталь' => 'letal', 'либераль' => 'liberal', 'локаль' => 'lokal',
		'магистраль' => 'magistral', 'материаль' => 'material', 'машиналь' => 'maşinal',
		'меаль' => 'meal', 'медальон' => 'medalyon', 'медаль' => 'medal',
		'меридиональ' => 'meridional', 'мешъаль' => 'meşal', 'минераль' => 'mineral',
		'минималь' => 'minimal', 'мисаль' => 'misal', 'модаль' => 'modal', 'музыкаль' => 'muzıkal',
		'номиналь' => 'nominal', 'нормаль' => 'normal', 'оптималь' => 'optimal',
		'орбиталь' => 'orbital', 'педаль' => 'pedal', 'пропорциональ' => 'proportsional',
		'профессиональ' => 'professional', 'радикаль' => 'radikal', 'рациональ' => 'ratsional',
		'реаль' => 'real', 'региональ' => 'regional', 'суаль' => 'sual', 'шималь' => 'şimal',
		'территориаль' => 'territorial', 'тимсаль' => 'timsal', 'тоталь' => 'total',
		'уникаль' => 'unikal', 'универсаль' => 'universal', 'вертикаль' => 'vertikal',
		'виртуаль' => 'virtual', 'визуаль' => 'vizual', 'вуаль' => 'vual', 'зональ' => 'zonal',
		'зуаль' => 'zual', 'италь' => 'ital',

		# слова с мягким знаком перед а, о, у, э
		# Words with a soft sign before а, о, у, э
		'бильакис' => 'bilakis', 'маальэсеф' => 'maalesef', 'мельун' => 'melun', 'озьара' => 'özara',
		'вельасыл' => 'velasıl', 'ельаякъ' => 'yelayaq',

		# другие слова с мягким знаком
		# Other words with a soft sign
		'альбатрос' => 'albatros', 'альбинос' => 'albinos', 'альбом' => 'albom',
		'альбумин' => 'albumin', 'алфавит' => 'alfavit', 'альфа' => 'alfa', 'альманах' => 'almanah',
		'альпинист' => 'alpinist', 'альтерн' => 'altern', 'альтру' => 'altru',
		'альвеола' => 'alveola', 'ансамбль' => 'ansambl', 'аньане' => 'anane', 'асфальт' => 'asfalt',
		'бальнео' => 'balneo', 'баарь' => 'baar', 'базальт' => 'bazalt', 'бинокль' => 'binokl',
		'девальв' => 'devalv', 'факульт' => 'fakult', 'фальсиф' => 'falsif', 'фольклор' => 'folklor',
		'гальван' => 'galvan', 'геральд' => 'gerald', 'женьшень' => 'jenşen',
		'инвентарь' => 'inventar', 'кальк' => 'kalk', 'кальмар' => 'kalmar', 'консульт' => 'konsult',
		'контроль' => 'kontrol', 'культур' => 'kultur', 'лагерь' => 'lager', 'макъбуль' => 'maqbul',
		'макъуль' => 'maqul', 'мальт' => 'malt', 'мальземе' => 'malzeme', 'меджуль' => 'mecul',
		'мешгуль' => 'meşgül', 'мешгъуль' => 'meşğul', 'мульти' => 'multi',
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
		'алъянакъ' => 'alyanaq', 'инъекц' => 'inyekts', 'мефъум' => 'mefum', 'мешъум' => 'meşum',
		'объект' => 'obyekt', 'разъезд' => 'razyezd', 'субъект' => 'subyekt', 'хавъяр' => 'havyar',
		'ямъям' => 'yamyam',

		# слова с буквой щ
		# words with щ
		'ящик' => 'yaşçik', 'мещан' => 'meşçan',

		# слова с ц
		# words with ц
		'акциз' => 'aktsiz', 'ацет' => 'atset', 'блиц' => 'blits', 'бруцеллёз' => 'brutsellöz',
		'доцент' => 'dotsent', 'фармацевт' => 'farmatsevt', 'глицер' => 'glitser',
		'люцерна' => 'lütserna', 'лицей' => 'litsey', 'меццо' => 'metstso', 'наци' => 'natsi',
		'проце' => 'protse', 'рецеп' => 'retsep', 'реценз' => 'retsenz', 'теплица' => 'teplitsa',
		'вице' => 'vitse', 'швейцар' => 'şveytsar', 'богородиц' => 'bogorodits',
		'бруцел' => 'brutsel', 'дацюк' => 'datsük', 'доницетти' => 'donitsetti',
		'драцена' => 'dratsena', 'контрацеп' => 'kontratsep', 'коцюб' => 'kotsüb',
		'меценат' => 'metsenat', 'мицел' => 'mitsel', 'моцарт' => 'motsart', 'плац' => 'plats',
		'плацен' => 'platsen', 'прецедент' => 'pretsedent', 'прецес' => 'pretses',
		'прицеп' => 'pritsep', 'спец' => 'spets', 'троиц' => 'troits', 'шприц' => 'şprits',
		'эпицентр' => 'epitsentr', 'яценюк' => 'yatsenük',

		# слова с тс
		# words with тс
		'агъартс' => 'ağarts', 'агъыртс' => 'ağırts', 'бильдиртс' => 'bildirts', 'битсин' => 'bitsin',
		'буюльтс' => 'büyülts', 'буютс' => 'büyüts', 'гебертс' => 'geberts', 'делиртс' => 'delirts',
		'эгрильтс' => 'egrilts', 'эксильтс' => 'eksilts', 'эшитс' => 'eşits', 'иритс' => 'irits',
		'иситс' => 'isits', 'ичиртс' => 'içirts', 'кертсин' => 'kertsin', 'кенишлетс' => 'kenişlets',
		'кийсетс' => 'kiysets', 'копюртс' => 'köpürts', 'косьтертс' => 'kösterts',
		'кучертс' => 'küçerts', 'кучюльтс' => 'küçülts', 'пертсин' => 'pertsin', 'къайтс' => 'qayts',
		'къутсуз' => 'qutsuz', 'орьтс' => 'örts', 'отьс' => 'öts', 'тартс' => 'tarts',
		'тутсун' => 'tutsun', 'тюнъюльтс' => 'tüñülts', 'тюртс' => 'türts', 'янъартс' => 'yañarts',
		'ебертс' => 'yeberts', 'ешертс' => 'yeşerts', 'йиритс' => 'yirits',

		# разные исключения
		# different exceptions
		'бюджет' => 'bücet', 'бюллет' => 'büllet', 'бюро' => 'büro', 'бюст' => 'büst',
		'диалог' => 'dialog', 'ханымэфенди' => 'hanımefendi', 'каньон' => 'kanyon',
		'кирил' => 'kiril', 'кирилл' => 'kirill', 'кёрджа' => 'körca', 'коy' => 'köy',
		'кулеръюзь' => 'küleryüz', 'маалле' => 'маальle', 'майор' => 'mayor', 'маниал' => 'manиаль',
		'нормала' => 'нормальa', 'проект' => 'proekt', 'район' => 'rayon', 'сойады' => 'soyadı',
		'спортсмен' => 'sportsmen', 'услюп' => 'üslüp', 'услюб' => 'üslüb', 'вакъиал' => 'vaqиаль',
		'юзйыллыкъ' => 'yüzyıllıq', 'койот' => 'koyot',

		# имена собственные
		# proper names
		'адольф' => 'adolf', 'альберт' => 'albert', 'бешуй' => 'beşüy', 'флотск' => 'flotsk',
		'гайана' => 'gayana', 'грэсовский' => 'gresovskiy', 'гриц' => 'grits', 'гурджи' => 'gürci',
		'игорь' => 'igor', 'ильич' => 'ilyiç', 'ильин' => 'ilyin', 'исмаил' => 'ismail',
		'киттс' => 'kitts', 'комсомольск' => 'komsomolsk', 'корьбекулю' => 'körbekülü',
		'куницын' => 'kunitsın', 'львив' => 'lviv', 'львов' => 'lvov', 'марьино' => 'maryino',
		'махульдюр' => 'mahuldür', 'павел' => 'pavel', 'пантикапейон' => 'pantikapeyon',
		'къуртсейит' => 'qurtseyit', 'къуртсеит' => 'qurtseit', 'смаил' => 'smail',
		'советск' => 'sovetsk', 'шемьи-заде' => 'şemi-zade', 'тсвана' => 'tsvana',
		'учьэвли' => 'üçevli', 'йохан' => 'yohan', 'йорк' => 'york', 'винныця' => 'vinnıtsâ',
		'винница' => 'vinnitsa', 'хмельницк' => 'hmelnitsk', 'хмельныцк' => 'hmelnıtsk',
		'зайце' => 'zaytse', 'чистеньк' => 'çistenk', 'кольчуг' => 'kolçug', 'ручьи' => 'ruçyi',
		'ботсвана' => 'botsvana', 'большой' => 'bolşoy', 'большое' => 'bolşoye',
		'большая' => 'bolşaya', 'ущелье' => 'uşçelye', 'ущельное' => 'uşçelnoye',
		'предущельное' => 'preduşçelnoye', 'новенькое' => 'novenkoye', 'новосельц' => 'novoselts',
		'мелко' => 'melko', 'овощ' => 'ovoşç', 'перепёлк' => 'perepölk', 'рощин' => 'roşçin',
		'братск' => 'bratsk', 'краснофлотск' => 'krasnoflotsk', 'синицин' => 'sinitsin',
		'синицын' => 'sinitsın', 'льгов' => 'lgov', 'желто' => 'jelto', 'жёлт' => 'jölt',
		'пермь' => 'perm', 'солдатск' => 'soldatsk', 'кольцо' => 'koltso', 'шелко' => 'şelko',
		'охотск' => 'ohotsk', 'марий эл' => 'mariy el', 'мариуполь' => 'mariupol',
		'белгород' => 'belgorod', 'иркутск' => 'irkutsk', 'Иркутск' => 'İrkutsk', 'орёл' => 'oröl',
		'рязанск' => 'râzansk', 'рязань' => 'râzan', 'тверск' => 'tversk', 'тверь' => 'tver',
		'ярославль' => 'yaroslavl', 'благовеще' => 'blagoveşçe', 'мальдив' => 'maldiv',
		'бальбек' => 'balbek', 'альчик' => 'alçik', 'харьков' => 'harkov', 'волынск' => 'volınsk',
		'волынь' => 'volın',

	];

	# map Cyrillic to Latin and back, simple string match only (no regex)
	# no variants: map exactly as is
	private $exactCaseMappings = [
		# аббревиатуры
		# abbreviations
		'ОБСЕ' => 'OBSE', 'КъМДж' => 'QMC', 'КъДж' => 'QC', 'КъАЭ' => 'QAE', 'ГъСМК' => 'ĞSMK',
		'ШСДжБ' => 'ŞSCB', 'КъМШСДж' => 'QMŞSC', 'КъАССР' => 'QASSR', 'КъДМПУ' => 'QDMPU',
		'КъМПУ' => 'QMPU',
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
		'козь' => 'köz', '-юнджи' => '-ünci', '-юнджиде' => '-üncide', '-юнджиден' => '-ünciden',

		# originally L2C, here swapped
		'льная' => 'lnaya', 'льное' => 'lnoye', 'льный' => 'lnıy', 'льний' => 'lniy',
		'льская' => 'lskaya', 'льский' => 'lskiy', 'льское' => 'lskoye', 'ополь' => 'opol',
		'щее' => 'şçeye', 'щий' => 'şçiy', 'щая' => 'şçaya', 'цепс' => 'tseps',

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
		'куркте' => 'kürkte', 'куркчю' => 'kürkçü', 'кою' => 'köyü',
		'жизнь' => 'jizn',

		# арабизмы на муи- муэ- / Arabic муи- муэ-
		'му([иэИЭ])' => 'mü$1',

		# originally L2C, here swapped
		'роль$1' => 'rol([^ü]|' . self::WB . ')',
		'усть$1' => 'üst([^ü]|' . self::WB . ')',

		# more prefixes
		'ком-кок' => 'köm-kök',

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

			// TODO: refactor upper/lower/first capital whole words without
			// regexes into simpler list

			'/' . self::WB . 'КъЮШ' . self::WB . '/u' => 'QYŞ',
			'/' . self::WB . 'ЮШ' . self::WB . '/u' => 'YŞ',

			'/' . self::WB . 'кок' . self::WB . '/u' => 'kök',
			'/' . self::WB . 'Кок' . self::WB . '/u' => 'Kök',
			'/' . self::WB . 'КОК' . self::WB . '/u' => 'KÖK',
			'/' . self::WB . 'ком-кок' . self::WB . '/u' => 'köm-kök',
			'/' . self::WB . 'Ком-кок' . self::WB . '/u' => 'Köm-kök',
			'/' . self::WB . 'КОМ-КОК' . self::WB . '/u' => 'KÖM-KÖK',

			'/' . self::WB . 'коп' . self::WB . '/u' => 'köp',
			'/' . self::WB . 'Коп' . self::WB . '/u' => 'Köp',
			'/' . self::WB . 'КОП' . self::WB . '/u' => 'KÖP',

			'/' . self::WB . 'курк' . self::WB . '/u' => 'kürk',
			'/' . self::WB . 'Курк' . self::WB . '/u' => 'Kürk',
			'/' . self::WB . 'КУРК' . self::WB . '/u' => 'KÜRK',

			'/' . self::WB . 'ог' . self::WB . '/u' => 'ög',
			'/' . self::WB . 'Ог' . self::WB . '/u' => 'Ög',
			'/' . self::WB . 'ОГ' . self::WB . '/u' => 'ÖG',

			'/' . self::WB . 'юрип' . self::WB . '/u' => 'yürip',
			'/' . self::WB . 'Юрип' . self::WB . '/u' => 'Yürip',
			'/' . self::WB . 'ЮРИП' . self::WB . '/u' => 'YÜRİP',

			'/' . self::WB . 'юз' . self::WB . '/u' => 'yüz',
			'/' . self::WB . 'Юз' . self::WB . '/u' => 'Yüz',
			'/' . self::WB . 'ЮЗ' . self::WB . '/u' => 'YÜZ',

			'/' . self::WB . 'юк' . self::WB . '/u' => 'yük',
			'/' . self::WB . 'Юк' . self::WB . '/u' => 'Yük',
			'/' . self::WB . 'ЮК' . self::WB . '/u' => 'YÜK',

			'/' . self::WB . 'буюп' . self::WB . '/u' => 'büyüp',
			'/' . self::WB . 'Буюп' . self::WB . '/u' => 'Büyüp',
			'/' . self::WB . 'БУЮП' . self::WB . '/u' => 'BÜYÜP',

			'/' . self::WB . 'буюк' . self::WB . '/u' => 'büyük',
			'/' . self::WB . 'Буюк' . self::WB . '/u' => 'Büyük',
			'/' . self::WB . 'БУЮК' . self::WB . '/u' => 'BÜYÜK',

			'/' . self::WB . 'джонк' . self::WB . '/u' => 'cönk',
			'/' . self::WB . 'Джонк' . self::WB . '/u' => 'Cönk',
			'/' . self::WB . 'ДЖОНК' . self::WB . '/u' => 'CÖNK',
			'/' . self::WB . 'джонкю' . self::WB . '/u' => 'cönkü',
			'/' . self::WB . 'Джонкю' . self::WB . '/u' => 'Cönkü',
			'/' . self::WB . 'ДЖОНКЮ' . self::WB . '/u' => 'CÖNKÜ',

			'/' . self::WB . 'куркчи/u' => 'kürkçi',
			'/' . self::WB . 'Куркчи/u' => 'Kürkçi',
			'/' . self::WB . 'КУРКЧИ/u' => 'KÜRKÇI',

			'/' . self::WB . 'устке' . self::WB . '/u' => 'üstke',
			'/' . self::WB . 'Устке' . self::WB . '/u' => 'Üstke',
			'/' . self::WB . 'УСТКЕ' . self::WB . '/u' => 'ÜSTKE',
			'/' . self::WB . 'устте' . self::WB . '/u' => 'üstte',
			'/' . self::WB . 'Устте' . self::WB . '/u' => 'Üstte',
			'/' . self::WB . 'УСТТЕ' . self::WB . '/u' => 'ÜSTTE',
			'/' . self::WB . 'усттен' . self::WB . '/u' => 'üstten',
			'/' . self::WB . 'Усттен' . self::WB . '/u' => 'Üstten',
			'/' . self::WB . 'УСТТЕН' . self::WB . '/u' => 'ÜSTTEN',

			# отдельно стоящие Ё и Я
			# stand-alone Ё and Я
			'/' . self::WB . 'Я' . self::WB . '/u' => 'Ya',
			'/' . self::WB . 'Ё' . self::WB . '/u' => 'Yo',

			############################
			# относятся к началу слова #
			# word prefixes            #
			############################
			'/' . self::WB . 'КъЮШн/u' => 'QYŞn',
			'/' . self::WB . 'ЮШн/u' => 'YŞn',

			# need to convert digraphs (гъ, къ, нъ, дж) now to match patterns
			'/гъ/u' => 'ğ',
			'/Г[ъЪ]/u' => 'Ğ',
			'/къ/u' => 'q',
			'/К[ъЪ]/u' => 'Q',
			'/нъ/u' => 'ñ',
			'/Н[ъЪ]/u' => 'Ñ',
			'/дж/u' => 'c',
			'/Д[жЖ]/u' => 'C',

			# о => ö
			'/' . self::WB . '([' . Crh::C_M_CONS . '])о([' . Crh::C_CONS . '])([' . Crh::C_CONS .
				'])([еиэюьü])/u' => '$1ö$2$3$4',
			'/' . self::WB . 'о([' . Crh::C_CONS . '])([' . Crh::C_CONS . '])([еиэюьü])/u' => 'ö$1$2$3',
			'/' . self::WB . '([' . Crh::C_M_CONS . '])О([' . Crh::C_CONS . '])([' . Crh::C_CONS .
				'])([еиэюьüЕИЭЮЬÜ])/u' => '$1Ö$2$3$4',
			'/' . self::WB . 'О([' . Crh::C_CONS . '])([' . Crh::C_CONS . '])([еиэюьüЕИЭЮЬÜ])/u'
				=> 'Ö$1$2$3',

			'/' . self::WB . '([' . Crh::C_M_CONS . '])о([' . Crh::C_CONS . '])([еиэюьü])/u' => '$1ö$2$3',
			'/' . self::WB . 'о([' . Crh::C_CONS . '])([еиэюьü])/u' => 'ö$1$2',
			'/' . self::WB . '([' . Crh::C_M_CONS . '])О([' . Crh::C_CONS . '])([еиэюьüЕИЭЮЬÜ])/u'
				=> '$1Ö$2$3',
			'/' . self::WB . 'О([' . Crh::C_CONS . '])([еиэюьüЕИЭЮЬÜ])/u' => 'Ö$1$2',

			# ё => yö
			'/' . self::WB . 'ё([' . Crh::C_CONS . '])([' . Crh::C_CONS . '])([ьеюü])/u' => 'yö$1$2$3',
			'/' . self::WB . 'Ё([' . Crh::C_CONS_LC . '])([' . Crh::C_CONS_LC . '])([ьеюü])/u' => 'Yö$1$2$3',
			'/' . self::WB . 'Ё([' . Crh::C_CONS_UC . '])([' . Crh::C_CONS_UC . '])([ЬЕЮÜ])/u' => 'YÖ$1$2$3',
			'/' . self::WB . 'ё([' . Crh::C_CONS . '])([ьеюü])/u' => 'yö$1$2',
			'/' . self::WB . 'Ё([' . Crh::C_CONS_LC . '])([ьеюü])/u' => 'Yö$1$2',
			'/' . self::WB . 'Ё([' . Crh::C_CONS_UC . '])([ЬЕЮÜ])/u' => 'YÖ$1$2',

			# у => ü, ую => üyü
			'/' . self::WB . '([' . Crh::C_M_CONS . '])у([' . Crh::C_CONS . '])([' . Crh::C_CONS .
				'])([еиэюьü])/u' => '$1ü$2$3$4',
			'/' . self::WB . 'у([' . Crh::C_CONS . '])([' . Crh::C_CONS . '])([еиэюьü])/u' => 'ü$1$2$3',
			'/' . self::WB . 'ую([' . Crh::C_CONS . '])([' . Crh::C_CONS . '])([еиэюьü])/u' => 'üyü$1$2$3',
			'/' . self::WB . '([' . Crh::C_M_CONS . '])У([' . Crh::C_CONS . '])([' . Crh::C_CONS .
				'])([еиэюьüЕИЭЮЬÜ])/u' => '$1Ü$2$3$4',
			'/' . self::WB . 'У([' . Crh::C_CONS . '])([' . Crh::C_CONS . '])([еиэюьüЕИЭЮЬÜ])/u'
				=> 'Ü$1$2$3',
			'/' . self::WB . 'Ую([' . Crh::C_CONS . '])([' . Crh::C_CONS . '])([еиэюьü])/u' => 'Üyü$1$2$3',
			'/' . self::WB . 'УЮ([' . Crh::C_CONS . '])([' . Crh::C_CONS . '])([еиэюьü])/u' => 'ÜYÜ$1$2$3',

			'/' . self::WB . '([' . Crh::C_M_CONS . '])у([' . Crh::C_CONS . '])([еиэюьü])/u' => '$1ü$2$3',
			'/' . self::WB . 'у([' . Crh::C_CONS . '])([еиэюьü])/u' => 'ü$1$2',
			'/' . self::WB . 'ую([' . Crh::C_CONS . '])([еиэюьü])/u' => 'üyü$1$2',
			'/' . self::WB . '([' . Crh::C_M_CONS . '])У([' . Crh::C_CONS . '])([еиэюьüЕИЭЮЬÜ])/u'
				=> '$1Ü$2$3',
			'/' . self::WB . 'У([' . Crh::C_CONS . '])([еиэюьüЕИЭЮЬÜ])/u' => 'Ü$1$2',
			'/' . self::WB . 'Ую([' . Crh::C_CONS . '])([еиэюьü])/u' => 'Üyü$1$2',
			'/' . self::WB . 'УЮ([' . Crh::C_CONS . '])([еиэюьü])/u' => 'ÜYÜ$1$2',

			# ю => yü
			'/' . self::WB . '([аыоуеиёюАЫОУЕИЁЮ]?)ю([' . Crh::C_CONS . '])([' . Crh::C_CONS . '])([ьеюü])/u'
				=> '$1yü$2$3$4',
			'/' . self::WB . '([АЫОУЕИЁЮ]?)Ю([' . Crh::C_CONS_LC . '])([' . Crh::C_CONS_LC . '])([ьеюü])/u'
				=> '$1Yü$2$3$4',
			'/' . self::WB . '([АЫОУЕИЁЮ]?)Ю([' . Crh::C_CONS_UC . '])([' . Crh::C_CONS_UC . '])([ЬЕЮÜ])/u'
				=> '$1YÜ$2$3$4',
			'/' . self::WB . '([аыоуеиёюАЫОУЕИЁЮ]?)ю([' . Crh::C_CONS . '])([ьеюü])/u' => '$1yü$2$3',
			'/' . self::WB . '([АЫОУЕИЁЮ]?)Ю([' . Crh::C_CONS_LC . '])([ьеюü])/u' => '$1Yü$2$3',
			'/' . self::WB . '([АЫОУЕИЁЮ]?)Ю([' . Crh::C_CONS_UC . '])([ЬЕЮÜ])/u' => '$1YÜ$2$3',

			# e => ye, я => ya
			'/' . self::WB . 'е/u' => 'ye',
			'/' . self::WB . 'Е([' . Crh::C_LC . 'cğñqöü])/u' => 'Ye$1',
			'/' . self::WB . 'Е([' . Crh::C_UC . 'CĞÑQÖÜ])/u' => 'YE$1',
			'/' . self::WB . 'я/u' => 'ya',
			'/' . self::WB . 'Я([' . Crh::C_LC . 'cğñqöü])/u' => 'Ya$1',
			'/' . self::WB . 'Я([' . Crh::C_UC . 'CĞÑQÖÜ])/u' => 'YA$1',
			'/([аеёиоуыэюяйьъaeöüАЕЁИОУЫЭЮЯЙЬЪAEÖÜ])е/u' => '$1ye',
			'/([аеёиоуыэюяйьъaeöüАЕЁИОУЫЭЮЯЙЬЪAEÖÜ])Е([' . Crh::C_LC . 'cğñqöü])/u' => '$1Ye$2',
			'/([аеёиоуыэюяйьъaeöüАЕЁИОУЫЭЮЯЙЬЪAEÖÜ])Е([' . Crh::C_UC . 'CĞÑQÖÜ])/u' => '$1YE$2',
			'/([аеёиоуыэюяйьъaeöüğqАЕЁИОУЫЭЮЯЙЬЪAEÖÜĞQ])я/u' => '$1ya',
			'/([аеёиоуыэюяйьъaeöüğqАЕЁИОУЫЭЮЯЙЬЪAEÖÜĞQ])Я([' . Crh::C_LC . 'cğñqöü])/u' => '$1Ya$2',
			'/([аеёиоуыэюяйьъaeöüğqАЕЁИОУЫЭЮЯЙЬЪAEÖÜĞQ])Я([' . Crh::C_UC . 'CĞÑQÖÜ])/u' => '$1YA$2',

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
			'/([' . Crh::C_CONS . '])ю/u' => '$1ü',
			'/([' . Crh::C_CONS . '])Ю/u' => '$1Ü',
			'/([' . Crh::C_CONS . '])ё/u' => '$1ö',
			'/([' . Crh::C_CONS . '])Ё/u' => '$1Ö',

			# остальные вхождения о, у, ё, ю
			# other occurences of о, у, ё, ю
			'/Ё([' . Crh::C_UC . 'CĞÑQÖÜ])/u' => 'YO$1',
			'/Ю([' . Crh::C_UC . 'CĞÑQÖÜ])/u' => 'YU$1',

			# Ц & Щ
			'/Ц([' . Crh::C_UC . 'CĞÑQÖÜ])/u' => 'TS$1',
			'/Щ([' . Crh::C_UC . 'CĞÑQÖÜ])/u' => 'ŞÇ$1',
		];

		$this->Latn2CyrlRegexes = [

			// TODO: refactor upper/lower/first capital whole words without
			// regexes into simpler list

			'/' . self::WB . 'an' . self::WB . '/u' => 'ань',
			'/' . self::WB . 'An' . self::WB . '/u' => 'Ань',
			'/' . self::WB . 'AN' . self::WB . '/u' => 'АНЬ',
			'/' . self::WB . 'ange' . self::WB . '/u' => 'аньге',
			'/' . self::WB . 'Ange' . self::WB . '/u' => 'Аньге',
			'/' . self::WB . 'ANGE' . self::WB . '/u' => 'АНЬГЕ',
			'/' . self::WB . 'ande' . self::WB . '/u' => 'аньде',
			'/' . self::WB . 'Ande' . self::WB . '/u' => 'Аньде',
			'/' . self::WB . 'ANDE' . self::WB . '/u' => 'АНЬДЕ',
			'/' . self::WB . 'anki' . self::WB . '/u' => 'аньки',
			'/' . self::WB . 'Anki' . self::WB . '/u' => 'Аньки',
			'/' . self::WB . 'ANKİ' . self::WB . '/u' => 'АНЬКИ',
			'/' . self::WB . 'deral' . self::WB . '/u' => 'деръал',
			'/' . self::WB . 'Deral' . self::WB . '/u' => 'Деръал',
			'/' . self::WB . 'DERAL' . self::WB . '/u' => 'ДЕРЪАЛ',
			'/' . self::WB . 'kör' . self::WB . '/u' => 'кёр',
			'/' . self::WB . 'Kör' . self::WB . '/u' => 'Кёр',
			'/' . self::WB . 'KÖR' . self::WB . '/u' => 'КЁР',
			'/' . self::WB . 'mer' . self::WB . '/u' => 'мэр',
			'/' . self::WB . 'Mer' . self::WB . '/u' => 'Мэр',
			'/' . self::WB . 'MER' . self::WB . '/u' => 'МЭР',

			'/' . self::WB . 'cönk/u' => 'джонк',
			'/' . self::WB . 'Cönk/u' => 'Джонк',
			'/' . self::WB . 'CÖNK/u' => 'ДЖОНК',

			# (y)etsin -> етсин/этсин
			# note that target starts with CYRILLIC е/Е!
			'/yetsin/u' => 'етсин',
			'/Yetsin/u' => 'Етсин',
			'/YETSİN/u' => 'ЕТСИН',

			# note that target starts with LATIN e/E!
			# (other transformations will determine CYRILLIC е/э as needed)
			'/etsin/u' => 'eтсин',
			'/Etsin/u' => 'Eтсин',
			'/ETSİN/u' => 'EТСИН',

			# буква Ё - первый заход
			# расставляем Ь после согласных
			'/' . self::WB . '([yY])ö([' . Crh::L_N_CONS . '])([aAuU' . Crh::L_CONS . ']|' . self::WB . ')/u'
				=> '$1ö$2ь$3',
			'/' . self::WB . '([yY])Ö([' . Crh::L_N_CONS . '])([aAuU' . Crh::L_CONS . ']|' . self::WB . ')/u'
				=> '$1Ö$2Ь$3',
			'/' . self::WB . 'AQŞ([^AEI]|' . self::WB . ')/u' => 'АКъШ$1',

			# буква Ю - первый заход
			# расставляем Ь после согласных
			'/' . self::WB . '([yY])ü([' . Crh::L_N_CONS . '])([aAuU' . Crh::L_CONS . ']|' . self::WB . ')/u'
				=> '$1ü$2ь$3',
			'/' . self::WB . '([yY])Ü([' . Crh::L_N_CONS . '])([aAuU' . Crh::L_CONS . ']|' . self::WB . ')/u'
				=> '$1Ü$2Ь$3',

			'/' . self::WB . '([bcgkpşBCGKPŞ])ö([' . Crh::L_N_CONS . '])([' . Crh::L_CONS . ']|' .
				self::WB . ')/u' => '$1ö$2ь$3',
			'/' . self::WB . '([bcgkpşBCGKPŞ])Ö([' . Crh::L_N_CONS . '])([' . Crh::L_CONS . ']|' .
				self::WB . ')/u' => '$1Ö$2Ь$3',
			'/' . self::WB . '([bcgkpşBCGKPŞ])Ö([' . Crh::L_N_CONS . '])([' . Crh::L_CONS . ']|' .
				self::WB . ')/u' => '$1Ö$2Ь$3',
			'/' . self::WB . '([bcgkpşBCGKPŞ])ü([' . Crh::L_N_CONS . '])([' . Crh::L_CONS . ']|' .
				self::WB . ')/u' => '$1ü$2ь$3',
			'/' . self::WB . '([bcgkpşBCGKPŞ])Ü([' . Crh::L_N_CONS . '])([' . Crh::L_CONS . ']|' .
				self::WB . ')/u' => '$1Ü$2Ь$3',
			'/' . self::WB . '([bcgkpşBCGKPŞ])Ü([' . Crh::L_N_CONS . '])([' . Crh::L_CONS . ']|' .
				self::WB . ')/u' => '$1Ü$2Ь$3',

			 # ö и ü в начале слова
			 # случаи, когда нужен Ь
			'/' . self::WB . 'ö([' . Crh::L_N_CONS . 'pP])([' . Crh::L_CONS . ']|' . self::WB . ')/u'
				=> 'ö$1ь$2',
			'/' . self::WB . 'Ö([' . Crh::L_N_CONS_LC . 'p])([' . Crh::L_CONS . ']|' . self::WB . ')/u'
				=> 'Ö$1ь$2',
			'/' . self::WB . 'Ö([' . Crh::L_N_CONS_UC . 'P])([' . Crh::L_CONS . ']|' . self::WB . ')/u'
				=> 'Ö$1Ь$2',
			'/' . self::WB . 'ü([' . Crh::L_N_CONS . '])([' . Crh::L_CONS . ']|' . self::WB . ')/u'
				=> 'ü$1ь$2',
			'/' . self::WB . 'Ü([' . Crh::L_N_CONS_LC . '])([' . Crh::L_CONS . ']|' . self::WB . ')/u'
				=> 'Ü$1ь$2',
			'/' . self::WB . 'Ü([' . Crh::L_N_CONS_UC . '])([' . Crh::L_CONS . ']|' . self::WB . ')/u'
				=> 'Ü$1Ь$2',

			'/ts' . self::WB . '/u' => 'ц',
			'/şç' . self::WB . '/u' => 'щ',
			'/Ş[çÇ]' . self::WB . '/u' => 'Щ',
			'/T[sS]' . self::WB . '/u' => 'Ц',

			# Ь после Л
			# add Ь after Л
			'/([' . Crh::L_F . '])l([' . Crh::L_CONS_LC . ']|' . self::WB . ')/u' => '$1ль$2',
			'/([' . Crh::L_F_UC . '])L([' . Crh::L_CONS . ']|' . self::WB . ')/u' => '$1ЛЬ$2',

			# относятся к началу слова
			'/' . self::WB . 'ts/u' => 'ц',
			'/' . self::WB . 'T[sS]/u' => 'Ц',

			'/' . self::WB . 'şç/u' => 'щ',
			'/' . self::WB . 'Ş[çÇ]/u' => 'Щ',

			# Э
			'/(' . self::WB . '|[' . Crh::L_VOW . 'аеэяАЕЭЯ])e/u' => '$1э',
			'/(' . self::WB . '|[' . Crh::L_VOW_UC . 'АЕЭЯ])E/u' => '$1Э',

			'/' . self::WB . '([' . Crh::L_M_CONS . '])ö/u' => '$1о',
			'/' . self::WB . '([' . Crh::L_M_CONS . '])Ö/u' => '$1О',
			'/' . self::WB . '([' . Crh::L_M_CONS . '])ü/u' => '$1у',
			'/' . self::WB . '([' . Crh::L_M_CONS . '])Ü/u' => '$1У',

			'/' . self::WB . 'ö/u' => 'о',
			'/' . self::WB . 'Ö/u' => 'О',
			'/' . self::WB . 'ü/u' => 'у',
			'/' . self::WB . 'Ü/u' => 'У',

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
			'/#|No\./u' => '№',

			# некоторые случаи употребления Ц
			'/tsi([^zñ])/u' => 'ци$1',
			'/T[sS][iİ]([^zZñÑ])/u' => 'ЦИ$1',
			'/ts([ou])/u' => 'ц$1',
			'/T[sS]([oOuU])/u' => 'Ц$1',
			'/ts([' . Crh::L_CONS . '])/u' => 'ц$1',
			'/T[sS]([' . Crh::L_CONS . '])/u' => 'Ц$1',
			'/([' . Crh::L_CONS . '])ts/u' => '$1ц',
			'/([' . Crh::L_CONS . '])T[sS]/u' => '$1Ц',
			'/tsиал/u' => 'циал',
			'/TSИАЛ/u' => 'ЦИАЛ',

			# убираем ьi
			# remove ьi (note Cyrillic ь and Latin i)
			'/[ьЬ]([iİ])/u' => '$1',

			# ya & ye
			'/([' . Crh::L_CONS . '])ya/u' => '$1ья',
			'/([' . Crh::L_CONS . '])Y[aA]/u' => '$1ЬЯ',
			'/([' . Crh::L_CONS . '])ye/u' => '$1ье',
			'/([' . Crh::L_CONS . '])Y[eE]/u' => '$1ЬЕ',

			 # расставляем Ь перед Ё
			 # place Ь in front of Ё
			'/([' . Crh::L_CONS . '])y[oö]/u' => '$1ьё',
			'/([' . Crh::L_CONS . '])Y[oOöÖ]/u' => '$1ЬЁ',
			 # оставшиеся вхождения yo и yö
			 # remaining occurrences of yo and yö
			'/y[oö]/u' => 'ё',
			'/[yY][oOöÖ]/u' => 'Ё',

			 # расставляем Ь перед Ю
			 # place Ь in front of Ю
			'/([' . Crh::L_CONS . '])y[uü]/u' => '$1ью',
			'/([' . Crh::L_CONS . '])Y[uUüÜ]/u' => '$1ЬЮ',
			 # оставшиеся вхождения yu и yü
			 # remaining occurrences of yu and yü
			'/y[uü]/u' => 'ю',
			'/[yY][uUüÜ]/u' => 'Ю',

			# убираем ьa
			# remove ьa (note Cyrillic ь and Latin a)
			'/[ьЬ]([aA])/u' => '$1',

			# дж
			'/C([' . Crh::L_UC . Crh::C_UC . 'АЕЁЙОУЭЮЯ])/u' => 'ДЖ$1',
			'/([' . Crh::L_UC . Crh::C_UC . 'АЕЁЙОУЭЮЯ])C/u' => '$1ДЖ',

			# гъ, къ, нъ
			'/Ğ([' . Crh::L_UC . Crh::C_UC . '])/u' => 'ГЪ$1',
			'/([' . Crh::L_UC . Crh::C_UC . 'Ъ])Ğ/u' => '$1ГЪ',

			'/Q([' . Crh::L_UC . Crh::C_UC . '])/u' => 'КЪ$1',
			'/([' . Crh::L_UC . Crh::C_UC . 'Ъ])Q/u' => '$1КЪ',

			'/Ñ([' . Crh::L_UC . Crh::C_UC . '])/u' => 'НЪ$1',
			'/([' . Crh::L_UC . Crh::C_UC . 'Ъ])Ñ/u' => '$1НЪ',

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
