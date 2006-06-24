<?php
/** Russian (русский язык)
  *
  * You can contact Alexander Sigachov (alexander.sigachov at Googgle Mail)
  *
  * @package MediaWiki
  * @subpackage Language
  */

require_once( 'LanguageUtf8.php' );


/* private */ $wgNamespaceNamesRu = array(
	NS_MEDIA            => 'Медиа',
	NS_SPECIAL          => 'Служебная',
	NS_MAIN             => '',
	NS_TALK             => 'Обсуждение',
	NS_USER             => 'Участник',
	NS_USER_TALK        => 'Обсуждение_участника',
	NS_PROJECT          => $wgMetaNamespace,
	NS_PROJECT_TALK     => FALSE,  #Set in constructor
	NS_IMAGE            => 'Изображение',
	NS_IMAGE_TALK       => 'Обсуждение_изображения',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Обсуждение_MediaWiki',
	NS_TEMPLATE         => 'Шаблон',
	NS_TEMPLATE_TALK    => 'Обсуждение_шаблона',
	NS_HELP             => 'Справка',
	NS_HELP_TALK        => 'Обсуждение_справки',
	NS_CATEGORY         => 'Категория',
	NS_CATEGORY_TALK    => 'Обсуждение_категории',
) + $wgNamespaceNamesEn;


/* private */ $wgQuickbarSettingsRu = array(
	'Не показывать', 'Неподвижная слева', 'Неподвижная справа', 'Плавающая слева', 'Плавающая справа'
);

/* private */ $wgSkinNamesRu = array(
	'standard' => 'Стандартный',
	'nostalgia' => 'Ностальгия',
	'cologneblue' => 'Кёльнская тоска',
	'davinci' => 'Да Винчи',
	'mono' => 'Моно',
	'monobook' => 'Моно-книга',
	'myskin' => 'Своё',
	'chick' => 'Цыпа'
);


/* private */ $wgBookstoreListRu = array(
	'ОЗОН' => 'http://www.ozon.ru/?context=advsearch_book&isbn=$1',
	'Books.Ru' => 'http://www.books.ru/shop/search/advanced?as%5Btype%5D=books&as%5Bname%5D=&as%5Bisbn%5D=$1&as%5Bauthor%5D=&as%5Bmaker%5D=&as%5Bcontents%5D=&as%5Binfo%5D=&as%5Bdate_after%5D=&as%5Bdate_before%5D=&as%5Bprice_less%5D=&as%5Bprice_more%5D=&as%5Bstrict%5D=%E4%E0&as%5Bsub%5D=%E8%F1%EA%E0%F2%FC&x=22&y=8',
	'Яндекс.Маркет' => 'http://market.yandex.ru/search.xml?text=$1',
	'Amazon.com' => 'http://www.amazon.com/exec/obidos/ISBN=$1'
);


# Note to translators:
#   Please include the English words as synonyms.  This allows people
#   from other wikis to contribute more easily.
#
/* private */ $wgMagicWordsRu = array(
#   ID                                 CASE  SYNONYMS
	MAG_REDIRECT             => array( 0,    '#REDIRECT', '#ПЕРЕНАПРАВЛЕНИЕ', '#ПЕРЕНАПР'),
	MAG_NOTOC                => array( 0,    '__NOTOC__', '__БЕЗСОДЕРЖАНИЯ__'),
	MAG_FORCETOC             => array( 0,    '__FORCETOC__'),
	MAG_TOC                  => array( 0,    '__TOC__', '__СОДЕРЖАНИЕ__'),
	MAG_NOEDITSECTION        => array( 0,    '__NOEDITSECTION__', '__БЕЗРЕДАКТИРОВАНИЯРАЗДЕЛА__'),
	MAG_START                => array( 0,    '__START__', '__НАЧАЛО__'),
	MAG_CURRENTMONTH         => array( 1,    'CURRENTMONTH', 'ТЕКУЩИЙМЕСЯЦ'),
	MAG_CURRENTMONTHNAME     => array( 1,    'CURRENTMONTHNAME','НАЗВАНИЕТЕКУЩЕГОМЕСЯЦА'),
	MAG_CURRENTMONTHNAMEGEN  => array( 1,    'CURRENTMONTHNAMEGEN','НАЗВАНИЕТЕКУЩЕГОМЕСЯЦАРОД'),
	MAG_CURRENTMONTHABBREV   => array( 1,    'CURRENTMONTHABBREV', 'НАЗВАНИЕТЕКУЩЕГОМЕСЯЦААБР'),
	MAG_CURRENTDAY           => array( 1,    'CURRENTDAY','ТЕКУЩИЙДЕНЬ'),
	MAG_CURRENTDAY2          => array( 1,    'CURRENTDAY2','ТЕКУЩИЙДЕНЬ2'),
	MAG_CURRENTDAYNAME       => array( 1,    'CURRENTDAYNAME','НАЗВАНИЕТЕКУЩЕГОДНЯ'),
	MAG_CURRENTYEAR          => array( 1,    'CURRENTYEAR','ТЕКУЩИЙГОД'),
	MAG_CURRENTTIME          => array( 1,    'CURRENTTIME','ТЕКУЩЕЕВРЕМЯ'),
	MAG_NUMBEROFARTICLES     => array( 1,    'NUMBEROFARTICLES','КОЛИЧЕСТВОСТАТЕЙ'),
	MAG_NUMBEROFFILES        => array( 1,    'NUMBEROFFILES', 'КОЛИЧЕСТВОФАЛОВ'),
	MAG_PAGENAME             => array( 1,    'PAGENAME','НАЗВАНИЕСТРАНИЦЫ'),
	MAG_PAGENAMEE            => array( 1,    'PAGENAMEE','НАЗВАНИЕСТРАНИЦЫ2'),
	MAG_NAMESPACE            => array( 1,    'NAMESPACE','ПРОСТРАНСТВОИМЁН'),
	MAG_MSG                  => array( 0,    'MSG:'),
	MAG_SUBST                => array( 0,    'SUBST:','ПОДСТ:'),
	MAG_MSGNW                => array( 0,    'MSGNW:'),
	MAG_END                  => array( 0,    '__END__','__КОНЕЦ__'),
	MAG_IMG_THUMBNAIL        => array( 1,    'thumbnail', 'thumb', 'мини'),
	MAG_IMG_MANUALTHUMB      => array( 1,    'thumbnail=$1', 'thumb=$1', 'мини=$1'),
	MAG_IMG_RIGHT            => array( 1,    'right','справа'),
	MAG_IMG_LEFT             => array( 1,    'left','слева'),
	MAG_IMG_NONE             => array( 1,    'none'),
	MAG_IMG_WIDTH            => array( 1,    '$1px','$1пкс'),
	MAG_IMG_CENTER           => array( 1,    'center', 'centre','центр'),
	MAG_IMG_FRAMED           => array( 1,    'framed', 'enframed', 'frame','обрамить'),
	MAG_INT                  => array( 0,    'INT:'),
	MAG_SITENAME             => array( 1,    'SITENAME','НАЗВАНИЕСАЙТА'),
	MAG_NS                   => array( 0,    'NS:','ПИ:'),
	MAG_LOCALURL             => array( 0,    'LOCALURL:'),
	MAG_LOCALURLE            => array( 0,    'LOCALURLE:'),
	MAG_SERVER               => array( 0,    'SERVER','СЕРВЕР'),
	MAG_SERVERNAME           => array( 0,    'SERVERNAME', 'НАЗВАНИЕСЕРВЕРА'),
	MAG_SCRIPTPATH           => array( 0,    'SCRIPTPATH', 'ПУТЬКСКРИПТУ'),
	MAG_GRAMMAR              => array( 0,    'GRAMMAR:'),
	MAG_NOTITLECONVERT       => array( 0,    '__NOTITLECONVERT__', '__NOTC__', '__БЕЗПРЕОБРАЗОВАНИЯЗАГОЛОВКА__'),
	MAG_NOCONTENTCONVERT     => array( 0,    '__NOCONTENTCONVERT__', '__NOCC__', '__БЕЗПРЕОБРАЗОВАНИЯТЕКСТА__'),
	MAG_CURRENTWEEK          => array( 1,    'CURRENTWEEK','ТЕКУЩАЯНЕДЕЛЯ'),
	MAG_CURRENTDOW           => array( 1,    'CURRENTDOW','ТЕКУЩИЙДЕНЬНЕДЕЛИ'),
	MAG_REVISIONID           => array( 1,    'REVISIONID', 'ИДВЕРСИИ'),
);

if (!$wgCachedMessageArrays) {
	require_once('MessagesRu.php');
}

/* Please, see Language.php for general function comments */
class LanguageRu extends LanguageUtf8 {
	function __construct() {
		global $wgNamespaceNamesRu, $wgMetaNamespace;
		parent::__construct();
		$wgNamespaceNamesRu[NS_PROJECT_TALK] = 'Обсуждение_' . $this->convertGrammar( $wgMetaNamespace, 'genitive' );
	}

	function getNamespaces() {
		global $wgNamespaceNamesRu;
		return $wgNamespaceNamesRu;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsRu;
		return $wgQuickbarSettingsRu;
	}

	function getSkinNames() {
		global $wgSkinNamesRu;
		return $wgSkinNamesRu;
	}

	function getDateFormats() {
		global $wgDateFormatsRu;
		return $wgDateFormatsRu;
	}

	function getMessage( $key ) {
		global $wgAllMessagesRu;
		return isset($wgAllMessagesRu[$key]) ? $wgAllMessagesRu[$key] : parent::getMessage($key);
	}

	function fallback8bitEncoding() {
		return "windows-1251";
	}

	//only for quotation mark
	function linkPrefixExtension() { return true; }

	function getMagicWords()  {
		global $wgMagicWordsRu;
		return $wgMagicWordsRu;
	}

	# Convert from the nominative form of a noun to some other case
	# Invoked with {{grammar:case|word}}
	function convertGrammar( $word, $case ) {
		global $wgGrammarForms;
		if ( isset($wgGrammarForms['ru'][$case][$word]) ) {
			return $wgGrammarForms['ru'][$case][$word];
		}

		# These rules are not perfect, but they are currently only used for site names so it doesn't
		# matter if they are wrong sometimes. Just add a special case for your site name if necessary.

		#join and array_slice instead mb_substr
		$ar = array();
		preg_match_all( '/./us', $word, $ar );
		if (!preg_match("/[a-zA-Z_]/us", $word))
			switch ( $case ) {
				case 'genitive': #родительный падеж
					if ((join('',array_slice($ar[0],-4))=='вики') || (join('',array_slice($ar[0],-4))=='Вики'))
						{}
					elseif (join('',array_slice($ar[0],-1))=='ь')
						$word = join('',array_slice($ar[0],0,-1)).'я';
					elseif (join('',array_slice($ar[0],-2))=='ия')
						$word=join('',array_slice($ar[0],0,-2)).'ии';
					elseif (join('',array_slice($ar[0],-2))=='ка')
						$word=join('',array_slice($ar[0],0,-2)).'ки';
					elseif (join('',array_slice($ar[0],-2))=='ти')
						$word=join('',array_slice($ar[0],0,-2)).'тей';
					elseif (join('',array_slice($ar[0],-2))=='ды')
						$word=join('',array_slice($ar[0],0,-2)).'дов';
					elseif (join('',array_slice($ar[0],-3))=='ник')
						$word=join('',array_slice($ar[0],0,-3)).'ника';
					break;
				case 'dative':  #дательный падеж
					#stub
					break;
				case 'accusative': #винительный падеж
					#stub
					break;
				case 'instrumental':  #творительный падеж
					#stub
					break;
				case 'prepositional': #предложный падеж
					#stub
					break;
			}
		return $word;
	}

	function convertPlural( $count, $wordform1, $wordform2, $wordform3) {
		$count = str_replace (' ', '', $count);
		if ($count > 10 && floor(($count % 100) / 10) == 1) {
			return $wordform3;
		} else {
			switch ($count % 10) {
				case 1: return $wordform1;
				case 2:
				case 3:
				case 4: return $wordform2;
				default: return $wordform3;
			}
		}
	}

	/*
	 * Russian numeric format is "12 345,67" but "1234,56"
	 */

	function commafy($_) {
		if (!preg_match('/^\d{1,4}$/',$_)) {
			return strrev((string)preg_replace('/(\d{3})(?=\d)(?!\d*\.)/','$1,',strrev($_)));
		} else {
			return $_;
		}
	}

	function separatorTransformTable() {
		return array(
			',' => "\xc2\xa0",
			'.' => ','
		);
	}

}
?>
