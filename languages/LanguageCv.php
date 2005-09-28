<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */
# Chuvash stub localization; default to Russian instead of English.

# Cyrillic chars:   Ӑӑ Ӗӗ Ҫҫ Ӳӳ
# Latin substitute: Ăă Ĕĕ Çç Ÿÿ
# Where are latin substitute in this file because of font problems.


require_once( "LanguageRu.php" );

if($wgMetaNamespace === FALSE)
	$wgMetaNamespace = str_replace( ' ', '_', $wgSitename );

/* private */ $wgNamespaceNamesCv = array(
	NS_MEDIA            => 'Медиа',
	NS_SPECIAL          => 'Ятарлă',
	NS_MAIN             => '',
	NS_TALK             => 'Сӳтсе явасси',
	NS_USER             => 'Хутшăнакан',
	NS_USER_TALK        => 'Хутшăнаканăн_канашлу_страници',
	NS_PROJECT          => $wgMetaNamespace,
	NS_PROJECT_TALK     => $wgMetaNamespace+'_сӳтсе_явмалли',
	NS_IMAGE            => 'Ӳкерчĕк',
	NS_IMAGE_TALK       => 'Ӳкерчĕке_сӳтсе_явмалли',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_сӳтсе_явмалли',
	NS_TEMPLATE         => 'Шаблон',
	NS_TEMPLATE_TALK    => 'Шаблона_сӳтсе_явмалли',
	NS_HELP             => 'Пулăшу',
	NS_HELP_TALK        => 'Пулăшăва_сӳтсе_явмалли',
	NS_CATEGORY         => 'Категори',
	NS_CATEGORY_TALK    => 'Категорине_сӳтсе_явмалли',
) + $wgNamespaceNamesEn;

/* private */ $wgAllMessagesCv = array(
'linktrail'             => '/^((?:[a-z]|а|б|в|г|д|е|ё|ж|з|и|й|к|л|м|н|о|п|р|с|т|у|ф|х|ц|ч|ш|щ|ъ|ы|ь|э|ю|я|ă|ĕ|ç|ÿ|ӑ|ӗ|ҫ|ӳ|“|»)+)(.*)$/sD',
'linkprefix'            => '/^(.*?)(„|«)$/sD',
'1movedto2' => '$1 $2 çине куçарнă',
'aboutpage' => '{{ns:project}}: {{SITENAME}} çинчен',
'aboutsite' => '{{SITENAME}} çинчен',
'allpages' => 'Пĕтĕм страницăсем',
'allpagesnext' => 'Тепĕр',
'apr' => 'Ака',
'april' => 'Ака',
'article' => 'Статья',
'aug' => 'Çур',
'august' => 'Çурла',
'category' => 'Категори',
'currentevents' => 'Хыпарсем',
'data' => 'Кун',
'deadendpages' => 'Ниăçта та урăх ертмен страницăсем',
'dec' => 'Раш',
'december' => 'Раштав',
'delete' => 'Кăларса пăрахасси',
'delete_and_move' => 'Кăларса пăрахса куçарасси',
'deletedrev' => '[кăларса пăрахнă]',
'deletepage' => 'Кăларса парахнă статьясем',
'edit' => 'Тӳрлетӳ',
'editing' => '$1 тӳрлетни',
'editingcomment' => '$1 тӳрлетни (кĕске анлантарăвĕ)',
'editingsection' => '$1 тӳрлетни (статья уйрăмĕ)',
'editthispage' => 'Страницăна тӳрлетмелли',
'friday' => 'Эрнекун',
'help' => 'Пулăшу',
'hide' => 'кăтартмалла мар',
'hidetoc' => 'кăтартмалла мар',
'history_short' => 'Истори',
'imagelist' => 'Ӳкерчĕксен списокĕ',
'jan' => 'Кăр',
'january' => 'Кăрлач',
'jul' => 'Утă',
'july' => 'Утă',
'jun' => 'Çĕр',
'june' => 'Çĕртме',
'mainpage' => 'Тĕп страницă',
'mar' => 'Пуш',
'march' => 'Пуш',
'may' => 'Çу',
'may_long' => 'Çу',
'minoredit' => 'Кунта пĕчĕк улшăнусем кăна кĕртнĕ',
'monday' => 'Тунтикун',
'movepage' => 'Страницăна куçарнă',
'navigation' => 'Меню',
'newpageletter' => 'Ç',
'newpages' => 'Çĕнĕ страницăсем',
'newpassword' => 'Çĕнĕ пароль',
'next' => 'тепěр',
'nextpage' => 'Тепěр страницă ($1)',
'nextrevision' => 'Çĕнĕрех версиĕ',
'nov' => 'Чӳк',
'november' => 'Чӳк',
'nstab-help' => 'пулăшу',
'nstab-special' => 'Ятарлă',
'nstab-user' => 'Хутшăнакан страници',
'oldpassword' => 'Кивĕ пароль',
'otherlanguages' => 'Урăх чĕлхесем',
'others' => 'ыттисем',
'pagemovedsub' => 'Тĕрĕсех куçартăмăр',
'permalink' => 'Яланхи вырăн',
'portal' => 'Портал',
'previewnote' => 'Ку страницăна халлĕхе çырса хуман. Эсир вăл мĕнле пулассине çеç куратăр!!',
'previousrevision' => '&larr;Малтанхи верси',
'printableversion' => 'Пичет версиĕ',
'qbspecialpages' => 'Ятарлă страницăсем',
'randompage' => 'Ăнсăртран илнě страницă',
'rclinks' => 'Юлашки $2 кун хушшинче тунă $1 улшăнусене кăтартмалла<br/>$3',
'rclistfrom' => 'Юлашки улшăнусене $1 вăхăтран пуçласа кăтартнă',
'rcnote' => 'Юлашки <strong>$2</strong> кун хушшинчи <strong>$1</strong> улшăнусем. Халĕ пĕтĕмпе  <strong>{{NUMBEROFARTICLES}}</strong> статья.',
'recentchanges' => 'Юлашки улшăнусем',
'recentchangeslinked' => 'Çыхăннă улшăнусем',
'recentchangestext' => 'Кунта эсир юлашки вăхăтра мĕнле улшăнусем кĕртнине курма пултаратăр.',
'rights' => 'Тума пултарать:',
'rightslogtext' => 'Ку хутшăнакансен прависене улшăнусем кĕртнĕ журналĕ',
'rollback' => 'Тӳрлетӳсене каялла куçармалли',
'rollback_short' => 'Каялла куçармалли',
'rollbackfailed' => 'Каялла куçарас вăхăтра йăнăш пулчĕ',
'rows' => 'Йĕркесем',
'search' => 'Шырасси',
'sep' => 'Авн',
'september' => 'Авăн',
'servertime' => 'Сервер вăхăчĕ',
'show' => 'кăтартмалла',
'showhideminor' => 'кăштах кăна тунă тӳрлетӳсене $1 | ботсене $2  | системăра палăртăннă пользовательсене $3 | тĕрĕсленĕ тӳрлетӳсене $4',
'sitesupport' => 'Пожертвованисем',
'specialpage' => 'Ятарлă страницă',
'specialpages' => 'Ятарлă страницăсем',
'spheading' => 'Пур хутшăнакансем валли ятарлă страницăсем',
'summary' => 'Улшăнусене кĕскен ăнлантарса пани:',
'sysoptitle' => 'Ку ĕç валли администратор прависем кирлĕ',
'talk' => 'Сӳтсе явмалли',
'thumbsize' => 'Thumbnail size:',
'toolbox' => 'Ĕç хатĕрĕсем',
'trackback' => '; $4$5 : [$2 $1]',
'uctop' => ' (пуçламăш)',
'val_total' => 'Пĕтĕмпе',
'val_version_of' => '$1 версиĕ',
'watchthis' => 'Ку страницăна кĕртекен  улшăнусем  хыççăн сăнамалла',
'whatlinkshere' => 'Кунта килекен ссылкăсем',
);

class LanguageCv extends LanguageRu {
	function LanguageCv() {
		global $wgNamespaceNamesCv, $wgMetaNamespace;
		LanguageUtf8::LanguageUtf8();
	}

	function getNamespaces() {
		global $wgNamespaceNamesCv;
		return $wgNamespaceNamesCv;
	}

	function getMessage( $key ) {
		global $wgAllMessagesCv;
		return isset($wgAllMessagesCv[$key]) ? $wgAllMessagesCv[$key] : parent::getMessage($key);
	}

	//only for quotation mark
	function linkPrefixExtension() { return true; }
}
?>
