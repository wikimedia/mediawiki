<?php
/** Tatar (Latin) (Татарча/Tatarça (Latin))
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Albert Fazlî
 * @author Don Alessandro
 * @author KhayR
 * @author Urhixidur
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Maxsus',
	NS_TALK             => 'Bäxäs',
	NS_USER             => 'Qullanuçı',
	NS_USER_TALK        => 'Qullanuçı_bäxäse',
	NS_PROJECT_TALK     => '$1_bäxäse',
	NS_FILE             => 'Fayl',
	NS_FILE_TALK        => 'Fayl_bäxäse',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_bäxäse',
	NS_TEMPLATE         => 'Ürnäk',
	NS_TEMPLATE_TALK    => 'Ürnäk_bäxäse',
	NS_HELP             => 'Yärdäm',
	NS_HELP_TALK        => 'Yärdäm_bäxäse',
	NS_CATEGORY         => 'Törkem',
	NS_CATEGORY_TALK    => 'Törkem_bäxäse',
);

$namespaceAliases = array(
	'Äğzä'             => NS_USER,
	'Äğzä_bäxäse'      => NS_USER_TALK,
	'Räsem'            => NS_FILE,
	'Räsem_bäxäse'     => NS_FILE_TALK,
);

$datePreferences = false;

$defaultDateFormat = 'dmy';

$dateFormats = array(
        'mdy time' => 'H:i',
        'mdy date' => 'M j, Y',
        'mdy both' => 'H:i, M j, Y',
        'dmy time' => 'H:i',
        'dmy date' => 'j M Y',
        'dmy both' => 'j M Y, H:i',
        'ymd time' => 'H:i',
        'ymd date' => 'Y M j',
        'ymd both' => 'H:i, Y M j',
        'ISO 8601 time' => 'xnH:xni:xns',
        'ISO 8601 date' => 'xnY-xnm-xnd',
        'ISO 8601 both' => 'xnY-xnm-xnd"T"xnH:xni:xns',
);

$magicWords = array(
	'redirect'              => array( '0', '#yünältü', '#REDIRECT' ),
	'notoc'                 => array( '0', '__ETYUQ__', '__NOTOC__' ),
	'forcetoc'              => array( '0', '__ETTIQ__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__ET__', '__TOC__' ),
	'noeditsection'         => array( '0', '__BÜLEMTÖZÄTÜYUQ__', '__NOEDITSECTION__' ),
	'currentmonth'          => array( '1', 'AĞIMDAĞI_AY', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'      => array( '1', 'AĞIMDAĞI_AY_İSEME', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'   => array( '1', 'AĞIMDAĞI_AY_İSEME_GEN', 'CURRENTMONTHNAMEGEN' ),
	'currentday'            => array( '1', 'AĞIMDAĞI_KÖN', 'CURRENTDAY' ),
	'currentdayname'        => array( '1', 'AĞIMDAĞI_KÖN_İSEME', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'AĞIMDAĞI_YIL', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'AĞIMDAĞI_WAQIT', 'CURRENTTIME' ),
	'numberofarticles'      => array( '1', 'MÄQÄLÄ_SANI', 'NUMBEROFARTICLES' ),
	'pagename'              => array( '1', 'BİTİSEME', 'PAGENAME' ),
	'namespace'             => array( '1', 'İSEMARA', 'NAMESPACE' ),
	'subst'                 => array( '0', 'TÖPÇEK:', 'SUBST:' ),
	'img_right'             => array( '1', 'uñda', 'right' ),
	'img_left'              => array( '1', 'sulda', 'left' ),
	'img_none'              => array( '1', 'yuq', 'none' ),
	'int'                   => array( '0', 'EÇKE:', 'INT:' ),
	'sitename'              => array( '1', 'SÄXİFÄİSEME', 'SITENAME' ),
	'ns'                    => array( '0', 'İA:', 'NS:' ),
	'localurl'              => array( '0', 'URINLIURL:', 'LOCALURL:' ),
	'localurle'             => array( '0', 'URINLIURLE:', 'LOCALURLE:' ),
);

$fallback8bitEncoding = "windows-1254";

$linkTrail = '/^([a-zäçğıñöşü“»]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline'               => 'Sıltamalarnıñ astına sızu:',
'tog-highlightbroken'         => 'Tözelmägän sıltamalarnı <a href="" class="new">şuşılay</a> (yuqsa <a href="" class="internal">bolay</a>) kürsätelsen',
'tog-justify'                 => 'Tekst kiñlek buyınça tigezlänsen',
'tog-hideminor'               => 'Soñğı üzgärtülär isemlegendä keçe üzgärtülär yäşerelsen',
'tog-hidepatrolled'           => 'Tikşerelgän üzgärtülär yaña üzgärtülär isemlegennän yäşerelsen.',
'tog-newpageshidepatrolled'   => 'Tikşerelgän bitlär yaña bitlär isemlegennän yäşerelsen',
'tog-extendwatchlist'         => 'Soñğıların ğına tügel, ä barlıq üzgärtülärne eçenä alğan, kiñäytelgän küzätü isemlege',
'tog-usenewrc'                => 'Yaxşırtılğan soñğı üzgärtülär isemlege qullanılsın (JavaScript kiräk)',
'tog-numberheadings'          => 'Atamalar avtomat räweştä nomerlansın',
'tog-showtoolbar'             => 'Üzgärtü waqıtında qorallarnıñ öske panele kürsätelsen (JavaScript kiräk)',
'tog-editondblclick'          => 'Bitlärgä ike çirtterü belän üzgärtü bite açılsın (JavaScript kiräk)',
'tog-editsection'             => 'Här bülektä «üzgärtü» sıltaması kürsätelsen',
'tog-editsectiononrightclick' => 'Bülek isemenä tıçqannıñ uñ çirttermäse belän törtkäç üzgärtü bite açılsın (JavaScript kiräk)',
'tog-showtoc'                 => 'Eçtälek kürsätelsen (3 tän kübräk başlamlı bitlärdä)',
'tog-rememberpassword'        => 'Xisap yazmamnı bu brauzerda saqlansın (iñ küp $1 {{PLURAL:$1|kön|kön|kön}}gä qädär)',
'tog-watchcreations'          => 'Tözegän bitlärem küzätü isemlegemä östälsen',
'tog-watchdefault'            => 'Üzgärtkän bitlärem küzätü isemlegemä östälsen',
'tog-watchmoves'              => 'Küçergän bitlärem küzätü isemlegemä östälsen',
'tog-watchdeletion'           => 'Beterelgän bitläremne küzätü isemlegemgä östäw',
'tog-minordefault'            => 'Barlıq üzgärtülärne kileşü buyınça keçe dip bilgelänsen',
'tog-previewontop'            => 'Üzgärtü täräzäsennän östäräk bitne aldan qaraw ölkäsen kürsätelsen',
'tog-previewonfirst'          => 'Üzgärtü bitenä küçkändä başta aldan qaraw bite kürsätelsen',
'tog-nocache'                 => 'Bitlär keşlawnı tıyılsın',
'tog-enotifwatchlistpages'    => 'Küzätü isemlegemdäge bit üzgärtelü turında elektron poçtağa xäbär cibärelsen',
'tog-enotifusertalkpages'     => 'Bäxäs bitem üzgärtelü turında elektron poçtağa xäbär cibärelsen',
'tog-enotifminoredits'        => 'Keçe üzgärtülär turında da elektron poçtağa xäbär cibärelsen',
'tog-enotifrevealaddr'        => 'Xäbärlärdä e-mail adresım kürsätelsen',
'tog-shownumberswatching'     => 'Bitne küzätü isemleklärenä östägän qullanuçılar sanın kürsätelsen',
'tog-oldsig'                  => 'Xäzerge imzanı aldan qaraw:',
'tog-fancysig'                => 'İmzanıñ şäxsi wiki-bilgelämäse (avtomatik sıltamasız)',
'tog-externaleditor'          => 'Tışqı redaqtor qullanu (kompyuter maxsus köylängän bulu zarur)',
'tog-externaldiff'            => 'Tışqı versiä çağıştıru programmasın qullanu (kompyuter maxsus köylängän bulu zarur)',
'tog-showjumplinks'           => '«Küçü» yärdämçe sıltamaları yalğansın',
'tog-uselivepreview'          => 'Tiz qarap alu qullanılsın (JavaScript, eksperimental)',
'tog-forceeditsummary'        => 'Üzgärtülärne taswirlaw yulı tutırılmağan bulsa, kisätü',
'tog-watchlisthideown'        => 'Minem üzgärtülärem küzätü isemlegennän yäşerelsen',
'tog-watchlisthidebots'       => 'Bot üzgärtüläre küzätü isemlegennän yäşerelsen',
'tog-watchlisthideminor'      => 'Keçe üzgärtülär küzätü isemlegennän yäşerelsen',
'tog-watchlisthideliu'        => 'Avtorizatsiäne uzğan qullanuçılarnıñ üzgärtüläre küzätü isemlegennän yäşerelsen',
'tog-watchlisthideanons'      => 'Anonim qullanuçılarnıñ üzgärtüläre küzätü isemlegennän yäşerelsen',
'tog-watchlisthidepatrolled'  => 'Tikşerelgän üzgärtülär küzätü isemlegennän yäşerelsen',
'tog-nolangconversion'        => 'Yazu sistemalarınıñ üzgärtüen sünderü',
'tog-ccmeonemails'            => 'Başqa qullanuçılarğa cibärgän xatlarımnıñ kopiäläre miña da cibärelsen',
'tog-diffonly'                => 'Yurama çağıştıru astında bit eçtälege kürsätelmäsen',
'tog-showhiddencats'          => 'Yäşeren törkemnär kürsätelsen',
'tog-norollbackdiff'          => 'Kire qaytaru yasağaç yuramalar ayırması kürsätelmäsen',

'underline-always'  => 'Härwaqıt',
'underline-never'   => 'Berwaqıtta da',
'underline-default' => 'Brauzer köylänmäläre qullanılsın',

# Font style option in Special:Preferences
'editfont-style'     => 'Üzgärtü ölkäsendäge şrift tibı:',
'editfont-default'   => 'Brauzer köylänmälärennän bulsın',
'editfont-monospace' => 'Kiñäytelgän şrift',
'editfont-sansserif' => 'Kirteksez şrift',
'editfont-serif'     => 'Kirtekle şrift',

# Dates
'sunday'        => 'Yäkşämbe',
'monday'        => 'Düşämbe',
'tuesday'       => 'Sişämbe',
'wednesday'     => 'Çärşämbe',
'thursday'      => 'Pänceşämbe',
'friday'        => 'Comğa',
'saturday'      => 'Şimbä',
'sun'           => 'Yäk',
'mon'           => 'Düş',
'tue'           => 'Siş',
'wed'           => 'Çär',
'thu'           => 'Pän',
'fri'           => 'Com',
'sat'           => 'Şim',
'january'       => 'ğıynvar',
'february'      => 'fevral',
'march'         => 'mart',
'april'         => 'aprel',
'may_long'      => 'may',
'june'          => 'iün',
'july'          => 'iül',
'august'        => 'avgust',
'september'     => 'sentyabr',
'october'       => 'oktyabr',
'november'      => 'noyabr',
'december'      => 'dekabr',
'january-gen'   => 'ğıynvar',
'february-gen'  => 'fevral',
'march-gen'     => 'mart',
'april-gen'     => 'aprel',
'may-gen'       => 'may',
'june-gen'      => 'iün',
'july-gen'      => 'iül',
'august-gen'    => 'avgust',
'september-gen' => 'sentyabr',
'october-gen'   => 'oktyabr',
'november-gen'  => 'noyabr',
'december-gen'  => 'dekabr',
'jan'           => 'ğıy',
'feb'           => 'fev',
'mar'           => 'mar',
'apr'           => 'apr',
'may'           => 'may',
'jun'           => 'iün',
'jul'           => 'iül',
'aug'           => 'avg',
'sep'           => 'sen',
'oct'           => 'okt',
'nov'           => 'noy',
'dec'           => 'dek',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Törkem|Törkemnär}}',
'category_header'                => '«$1» törkemendäge bitlär',
'subcategories'                  => 'Törkemçälär',
'category-media-header'          => '«$1» törkemendäge fayllar',
'category-empty'                 => "''Bu törkem älegä buş.''",
'hidden-categories'              => '{{PLURAL:$1|Yäşeren törkem|Yäşeren törkemnär}}',
'hidden-category-category'       => 'Yäşeren törkemnär',
'category-subcat-count'          => '{{PLURAL:$2|Bu törkemdä tübändäge törkemçä genä bar.|$2 törkemçädän {{PLURAL:$1|$1 törkemçä kürsätelgän}}.}}',
'category-subcat-count-limited'  => 'Bu törkemdä {{PLURAL:$1|$1 törkemçä}} bar.',
'category-article-count'         => '{{PLURAL:$2|Bu törkemdä ber genä bit bar.|Törkemdäge $2 bitneñ {{PLURAL:$1|$1 bite kürsätelgän}}.}}',
'category-article-count-limited' => 'Bu törkemdä {{PLURAL:$1|$1 bit}} bar.',
'category-file-count'            => '{{PLURAL:$2|Bu törkemdä ber genä fayl bar.|Törkemdäge $2 faylnıñ {{PLURAL:$1|$1 faylı kürsätelgän}}.}}',
'category-file-count-limited'    => 'Bu törkemdä {{PLURAL:$1|$1 fayl}} bar.',
'listingcontinuesabbrev'         => 'däwamı',
'index-category'                 => 'İndekslanğan bitlär',
'noindex-category'               => 'İndekslanmağan bitlär',

'linkprefix'        => '/^(.*?)([a-zA-Z\\x80-\\xff]+)$/sD',
'mainpagetext'      => '«MediaWiki» uñışlı quyıldı.',
'mainpagedocfooter' => "Bu wiki turında mäğlümatnı [http://meta.wikimedia.org/wiki/Yärdäm:Eçtälek biredä] tabıp bula.

== Qayber faydalı resurslar ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Köylänmälär isemlege (ing.)];
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki turında yış birelgän sorawlar häm cawaplar (ing.)];
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki'nıñ yaña versiäläre turında xäbärlär yazdırıp alu].",

'about'         => 'Taswirlama',
'article'       => 'Mäqälä',
'newwindow'     => '(yaña täräzädä açıla)',
'cancel'        => 'Baş tartu',
'moredotdotdot' => 'Däwamı…',
'mypage'        => 'Şäxsi bitem',
'mytalk'        => 'Bäxäsem',
'anontalk'      => 'Bu IP adresı öçen bäxäs bite',
'navigation'    => 'Küçü',
'and'           => ' häm',

# Cologne Blue skin
'qbfind'         => 'Ezläw',
'qbbrowse'       => 'Qaraw',
'qbedit'         => 'Üzgärtü',
'qbpageoptions'  => 'Bu bit',
'qbpageinfo'     => 'Bit turında mäğlümatlar',
'qbmyoptions'    => 'Bitlärem',
'qbspecialpages' => 'Maxsus bitlär',
'faq'            => 'YBS',
'faqpage'        => 'Project:YBS',

# Vector skin
'vector-action-addsection'       => 'Yaña tema östäw',
'vector-action-delete'           => 'Beterü',
'vector-action-move'             => 'Küçerü',
'vector-action-protect'          => 'Yaqlaw',
'vector-action-undelete'         => 'Qaytaru',
'vector-action-unprotect'        => 'Yaqlawnı beterü',
'vector-simplesearch-preference' => 'Ezläw öçen kiñäytelgän yärdäm xäbärlären kürsätü («Vektorlı» bizäleşe öçen genä qullanılıa)',
'vector-view-create'             => 'Tözü',
'vector-view-edit'               => 'Üzgärtü',
'vector-view-history'            => 'Tarixın qaraw',
'vector-view-view'               => 'Uqu',
'vector-view-viewsource'         => 'Çığanaqnı qaraw',
'actions'                        => 'Xäräkät',
'namespaces'                     => 'İsemnär mäydanı',
'variants'                       => 'Törlär',

'errorpagetitle'    => 'Xata',
'returnto'          => '$1 bitenä qaytu.',
'tagline'           => '{{SITENAME}} proyektınnan',
'help'              => 'Yärdäm',
'search'            => 'Ezläw',
'searchbutton'      => 'Ezläw',
'go'                => 'Küçü',
'searcharticle'     => 'Küçü',
'history'           => 'Bitneñ tarixı',
'history_short'     => 'Tarix',
'updatedmarker'     => 'soñğı kerüemnän soñ yañartılğan',
'info_short'        => 'Mäğlümat',
'printableversion'  => 'Bastıru versiäse',
'permalink'         => 'Daimi sıltama',
'print'             => 'Bastıru',
'edit'              => 'Üzgärtü',
'create'            => 'Tözü',
'editthispage'      => 'Bu bitne üzgärtü',
'create-this-page'  => 'Bu bitne tözü',
'delete'            => 'Beterü',
'deletethispage'    => 'Bu bitne beterü',
'undelete_short'    => '$1 {{PLURAL:$1|üzgärtmäne}} torğızu',
'protect'           => 'Yaqlaw',
'protect_change'    => 'üzgärtü',
'protectthispage'   => 'Bu bitne yaqlaw',
'unprotect'         => 'Yaqlawnı beterü',
'unprotectthispage' => 'Bu bitneñ yaqlawın beterü',
'newpage'           => 'Yaña bit',
'talkpage'          => 'Bit turında fiker alışu',
'talkpagelinktext'  => 'Bäxäs',
'specialpage'       => 'Maxsus bit',
'personaltools'     => 'Şäxsi qorallar',
'postcomment'       => 'Yaña bülek',
'articlepage'       => 'Mäqäläne qaraw',
'talk'              => 'Bäxäs',
'views'             => 'Qarawlar',
'toolbox'           => 'Qorallar',
'userpage'          => 'Qullanuçı biten qaraw',
'projectpage'       => 'Proyekt biten qaraw',
'imagepage'         => 'Fayl biten qaraw',
'mediawikipage'     => 'Xäbär biten qaraw',
'templatepage'      => 'Ürnäk biten qaraw',
'viewhelppage'      => 'Yärdäm biten qaraw',
'categorypage'      => 'Törkem biten qaraw',
'viewtalkpage'      => 'Bäxäs biten qaraw',
'otherlanguages'    => 'Başqa tellärdä',
'redirectedfrom'    => '($1 bitennän yünältelde)',
'redirectpagesub'   => 'Başqa bitkä yünältü bite',
'lastmodifiedat'    => 'Bu bitne soñğı üzgärtü: $2, $1.',
'viewcount'         => 'Bu bitkä $1 {{PLURAL:$1|tapqır}} möräcäğät ittelär.',
'protectedpage'     => 'Yaqlanğan bit',
'jumpto'            => 'Moña küçü:',
'jumptonavigation'  => 'navigatsiä',
'jumptosearch'      => 'ezläw',
'view-pool-error'   => 'Ğafu itegez, xäzerge waqıtta serverlar buş tügel.
Bu bitne qararğa teläwçelär artıq küp.
Bu bitkä soñaraq kerüegez sorala.

$1',
'pool-timeout'      => 'Qısılunıñ  waqıtı uzdı',
'pool-queuefull'    => 'Sorawlarnı saqlaw  bite tulı',
'pool-errorunknown' => 'Bilgesez  xata',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} turında',
'aboutpage'            => 'Project:Taswirlama',
'copyright'            => 'Mäğlümat $1 buyınça taratıla.',
'copyrightpage'        => '{{ns:project}}:Avtorlıq xoquqları',
'currentevents'        => 'Xäzerge waqıyğalar',
'currentevents-url'    => 'Project:Xäzerge waqıyğalar',
'disclaimers'          => 'Cawaplılıqtan baş tartu',
'disclaimerpage'       => 'Project:Cawaplılıqtan baş tartu',
'edithelp'             => 'Üzgärtü buyınça yärdäm',
'edithelppage'         => 'Help:Üzgärtü',
'helppage'             => 'Help:Eçtälek',
'mainpage'             => 'Baş bit',
'mainpage-description' => 'Baş bit',
'policy-url'           => 'Project:Qäğidälär',
'portal'               => 'Cämğiät üzäge',
'portal-url'           => 'Project:Cämğiät üzäge',
'privacy'              => 'Yäşerenlek säyäsäte',
'privacypage'          => 'Project:Yäşerenlek säyäsäte',

'badaccess'        => 'Kerü xatası',
'badaccess-group0' => 'Sez sorağan ğämälne başqara almıysız.',
'badaccess-groups' => 'Soralğan ğämälne $1 {{PLURAL:$2|törkemeneñ|törkemeneñ}} qullanuçıları ğına başqara ala.',

'versionrequired'     => 'MediaWikinıñ $1 versiäse taläp itelä',
'versionrequiredtext' => 'Bu bit belän eşläw öçen MediaWikinıñ $1 versiäse kiräk. [[Special:Version|Qullanıluçı programma versiäse turında mäğlümat biten]] qara.',

'ok'                      => 'OK',
'pagetitle'               => '$1 — {{SITENAME}}',
'pagetitle-view-mainpage' => '{{SITENAME}}',
'retrievedfrom'           => 'Çığanağı — "$1"',
'youhavenewmessages'      => 'Sezdä $1 bar ($2).',
'newmessageslink'         => 'yaña xäbärlär',
'newmessagesdifflink'     => 'bäxäs bitegezneñ soñğı üzgärtüe',
'youhavenewmessagesmulti' => 'Sezgä monda yaña xäbärlär bar: $1',
'editsection'             => 'üzgärtü',
'editsection-brackets'    => '[$1]',
'editold'                 => 'üzgärtü',
'viewsourceold'           => 'başlanğıç kodnı qaraw',
'editlink'                => 'üzgärtü',
'viewsourcelink'          => 'başlanğıç kodnı qaraw',
'editsectionhint'         => '$1 bülegen üzgärtü',
'toc'                     => 'Eçtälek',
'showtoc'                 => 'kürsätü',
'hidetoc'                 => 'yäşerü',
'thisisdeleted'           => '$1 qararğa yäki torğızırğa telisezme?',
'viewdeleted'             => '$1 qararğa telisezme?',
'restorelink'             => '{{PLURAL:$1|1 beterelgän üzgärtüne|$1 beterelgän üzgärtüne}}',
'feedlinks'               => 'Şuşılay:',
'feed-invalid'            => 'Yazılu qanalı tibı yalğış',
'feed-unavailable'        => 'Sindikatsiä tasması yabıq',
'site-rss-feed'           => '$1 — RSS tasması',
'site-atom-feed'          => '$1 — Atom tasması',
'page-rss-feed'           => '«$1» — RSS tasması',
'page-atom-feed'          => '«$1» — Atom tasması',
'feed-atom'               => 'Atom-tasması',
'feed-rss'                => 'RSS-tasması',
'red-link-title'          => '$1 (mondıy bit yuq)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Bit',
'nstab-user'      => 'Qullanuçı bite',
'nstab-media'     => 'Multimedia',
'nstab-special'   => 'Maxsus bit',
'nstab-project'   => 'Proyekt turında',
'nstab-image'     => 'Fayl',
'nstab-mediawiki' => 'Xäbär',
'nstab-template'  => 'Ürnäk',
'nstab-help'      => 'Yärdäm',
'nstab-category'  => 'Törkem',

# Main script and global functions
'nosuchaction'      => 'Mondıy ğämäl yuq',
'nosuchactiontext'  => 'URLda kürsätelgän ğämäl xatalı.
Sez URLnı xatalı cıyğan yäisä xatalı sıltamadan küçkän bulırğa mömkinsez.
Bu şulay uq {{SITENAME}} proyektındağı xata säbäple dä bulırğa mömkin.',
'nosuchspecialpage' => 'Mondıy maxsus bit yuq',
'nospecialpagetext' => '<strong>Sez sorıy torğan maxsus bit yuq.</strong>

Maxsus bitlär isemlegen qarağız: [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'                => 'Xata',
'databaseerror'        => 'Mäğlümatlar bazasında xata',
'dberrortext'          => 'Mäğlümatlar bazasına cibärelgän sorawda sintaksik xata tabıldı.
Programmada xata bulırğa mömkin.
Mäğlümatlar bazasına cibärelgän soñğı soraw:
<blockquote><tt>$1</tt></blockquote>
<tt>«$2»</tt> funksiäsennän.
Baza <tt>«$3: $4»</tt> xatasın qaytardı.',
'dberrortextcl'        => 'Mäğlümatlar bazasına cibärelgän sorawda sintaksik xata tabıldı.
Mäğlümatlar bazasına cibärelgän soñğı soraw:
"$1"
«$2» funksiäsennän.
Baza «$3: $4» xatasın qaytardı.',
'laggedslavemode'      => 'İğtibar: bittä soñğı yañartular kürsätelmägän bulırğa mömkin.',
'readonly'             => 'Mäğlümatlar bazasına yazu yabılğan',
'enterlockreason'      => 'Yabılu säbäben häm waqıtın kürsätegez.',
'readonlytext'         => 'Mäğlümat bazası xäzerge waqıtta yaña bitlär yasawdan häm başqa üzgärtülärdän yabılğan. Bu planlaştırılğan xezmät kürsätü säbäple bulırğa mömkin.
Yabuçı operator tübändäge añlatmanı qaldırğan:
$1',
'missing-article'      => 'Mäğlümatlar bazasında «$1» $2 biteneñ soralğan tekstı tabılmadı.

Bu, ğädättä, iskergän sıltama buyınça beterelgän bitneñ üzgärtü tarixına küçkändä kilep çığa.

Ägär xata monda tügel ikän, sez programmada xata tapqan bulırğa mömkinsez.
Zinhar öçen, URLnı kürsätep, bu turıda [[Special:ListUsers/sysop|idaräçegä]] xäbär itegez.',
'missingarticle-rev'   => '(yurama № $1)',
'missingarticle-diff'  => '(ayırma: $1, $2)',
'readonly_lag'         => 'Mäğlümatlar bazası, östämä server töp server belän sinxronizatsiäläşkänçe, üzgärülärdän avtomat räweştä yabıldı.',
'internalerror'        => 'Eçke xata',
'internalerror_info'   => 'Eçke xata: $1',
'fileappenderrorread'  => 'Quşu waqıtında «$1» uqıp bulmadı.',
'fileappenderror'      => '"$1" häm "$2" ne quşıp bulmadı.',
'filecopyerror'        => '«$2» faylına «$1» faylınıñ kopiäsen yasap bulmıy.',
'filerenameerror'      => '«$1» faylınıñ isemen «$2» isemenä alıştırıp bulmıy.',
'filedeleteerror'      => '«$1» faylın beterep bulmıy.',
'directorycreateerror' => '«$1» direktoriäsen yasap bulmıy.',
'filenotfound'         => '«$1» faylın tabıp bulmıy.',
'fileexistserror'      => '«$1» faylına yazdırıp bulmıy: ul inde bar.',
'unexpected'           => 'Kötelmägän qimät: «$1»=«$2».',
'formerror'            => 'Xata: forma mäğlümatların tapşırıp bulmıy',
'badarticleerror'      => 'Bu bittä mondıy ğämäl başqarıp bulmıy.',
'cannotdelete'         => '«$1» isemle bitne yäki faylnı beterep bulmıy. Anı bütän qullanuçı betergän bulırğa mömkin.',
'badtitle'             => 'Yaraqsız isem',
'badtitletext'         => 'Bitneñ soralğan iseme döres tügel, buş yäisä telara yäki interwiki iseme döres kürsätelmägän. İsemdä tıyılğan simvollar qullanılğan bulırğa mömkin.',
'perfcached'           => 'Bu mäğlümatlar keştan alınğan, alarda soñğı üzgärtülär bulmasqa mömkin.',
'perfcachedts'         => 'Bu mäğlümatlar keştan alınğan, ul soñğı tapqır $1 yañartıldı.',
'querypage-no-updates' => 'Xäzer bu bitne yañartıp bulmıy. Monda kürsätelgän mäğlümatlar qabul itelmäyäçäk.',
'wrong_wfQuery_params' => 'wfQuery() funksiä öçen yaramağan parametrlar<br />
Funksiä: $1<br />
Soraw: $2',
'viewsource'           => 'Qaraw',
'viewsourcefor'        => '«$1» bite',
'actionthrottled'      => 'Tizlek kimetelgän',
'actionthrottledtext'  => 'Spamğa qarşı köräş öçen az waqıt eçendä bu ğämälne yış qullanu tıyılğan. Zinhar, soñaraq qabatlağız.',
'protectedpagetext'    => 'Bu bit üzgärtü öçen yabıq.',
'viewsourcetext'       => 'Sez bu bitneñ başlanğıç tekstın qarıy häm küçerä alasız:',
'protectedinterface'   => 'Bu bittä programma interfeysı xäbärläre bar. Vandalizmğa qarşı köräş säbäple, bu bitne üzgärtü tıyıla.',
'editinginterface'     => "'''İğtibar:''' Sez MediaWiki sistemasınıñ interfeys biten üzgärtäsez. Bu başqa qullanuçılarğa da tä'sir itäçäk. Tärcemä öçen [http://translatewiki.net/wiki/Main_Page?setlang=tt-latn translatewiki.net] lokalizatsiäläw proyektın qullanığız.",
'sqlhidden'            => '(SQL-soraw yäşerelgän)',
'cascadeprotected'     => 'Bu bit üzgärtülärdän saqlanğan, çönki ul kaskadlı saqlaw qabul itelgän {{PLURAL:$1|bitkä|bitlärgä}} östälgän:
$2',
'namespaceprotected'   => "'''$1''' isem kiñlegendäge bitlärne üzgärtü öçen sezneñ röxsätegez yuq.",
'customcssjsprotected' => 'Sez bu bitne üzgärtä almıysız, çönki anda başqa qullanıçınıñ köylänmäläre bar.',
'ns-specialprotected'  => 'Maxsus bitlärne üzgärtep bulmıy.',
'titleprotected'       => "Bu isem belän bit yasaw [[User:$1|$1]] tarafınnan tıyılğan.
Ul kürsätkän säbäp: ''$2''.",

# Virus scanner
'virus-badscanner'     => "Köyläw xatası. Bilgesez viruslar skanerı: ''$1''",
'virus-scanfailed'     => 'skanerlaw xatası ($1 kodı)',
'virus-unknownscanner' => 'bilgesez antivirus:',

# Login and logout pages
'logouttext'                 => "'''Sez xisap yazmağızdan çıqtığız.'''

Sez {{SITENAME}} proyektında anonim räweştä qala yäisä şul uq yäki başqa isem belän yañadan [[Special:UserLogin|kerä]] alasız.
Qayber bitlär Sez kergän kebek kürsätelergä mömkin. Monı beterü öçen brauzer keşın çistartığız.",
'welcomecreation'            => '== Räxim itegez, $1! ==
Sez terkäldegez.
Saytnıñ şäxsi [[Special:Preferences|köylänmälären]] qararğa onıtmağız.',
'yourname'                   => 'Qullanuçı iseme:',
'yourpassword'               => 'Sersüz:',
'yourpasswordagain'          => 'Sersüzne qabat kertü:',
'remembermypassword'         => 'Xisap yazmamnı bu brauzerda saqlansın (iñ küp $1 {{PLURAL:$1|kön|kön|kön}}gä qädär)',
'securelogin-stick-https'    => 'Kerüdän soñ HTTPS buyınça totaştırunı däwam itärgä',
'yourdomainname'             => 'Sezneñ domenığız:',
'externaldberror'            => 'Tışqı mäğlümat bazası yärdämendä awtentifikatsiä ütkändä xata çıqtı, yäisä tışqı xisap yazmağızğa üzgäreşlär kertü xoquqığız yuq.',
'login'                      => 'Kerü',
'nav-login-createaccount'    => 'Kerü / terkälü',
'loginprompt'                => '{{SITENAME}} proyektına kerü öçen «cookies» röxsät itelgän bulırğa tieş.',
'userlogin'                  => 'Kerü / terkälü',
'userloginnocreate'          => 'Kerü',
'logout'                     => 'Çığu',
'userlogout'                 => 'Çığu',
'notloggedin'                => 'Sez xisap yazmağızğa kermägänsez',
'nologin'                    => "Qullanuçı isemeñ yuqmı? '''$1'''",
'nologinlink'                => 'Xisap yazması tözegez',
'createaccount'              => 'Yaña qullanuçı terkäw',
'gotaccount'                 => "Sez inde terkäldegezme? '''$1'''.",
'gotaccountlink'             => 'Kerü',
'createaccountmail'          => 'elektron poçta aşa',
'createaccountreason'        => 'Säbäp:',
'badretype'                  => 'Kertelgän sersüzlär ber ük tügel.',
'userexists'                 => 'Kertelgän isem qullanıla.
Zinhar, başqa isem saylağız.',
'loginerror'                 => 'Kerü xatası',
'createaccounterror'         => 'Xisap yazmasın tözep bulmıy: $1',
'nocookiesnew'               => 'Qullanuçı terkälgän, läkin üz xisap yazması belän kermägän. {{SITENAME}} qullanuçını tanu öçen «cookies» qullana. Sezdä «cookies» tıyılğan. Zinhar, başta alarnı röxsät itegez, annan isem häm sersüz belän keregez.',
'nocookieslogin'             => '{{SITENAME}} qullanuçını tanu öçen «cookies» qullana. Sez alarnı sündergänsez. Zinhar, alarnı qabızıp, yañadan keregez.',
'noname'                     => 'Sez qullanuçı isemegezne kürsätergä tieşsez.',
'loginsuccesstitle'          => 'Kerü uñışlı ütte',
'loginsuccess'               => "'''Sez {{SITENAME}} proyektına $1 iseme belän kerdegez.'''",
'nosuchuser'                 => '$1 isemle qullanuçı yuq.
Qullanuçı isemeneñ döreslege registrğa bäyle.
Yazılışığıznı tikşeregez yäki [[Special:UserLogin/signup|yaña xisap yazması tözegez]].',
'nosuchusershort'            => '<nowiki>$1</nowiki> isemle qullanuçı yuq. Yazılışığıznı tikşeregez.',
'nouserspecified'            => 'Sez terkäw ismegezne kürsätergä tieşsez.',
'login-userblocked'          => 'Bu qullanuçı tıyıldı. Kerü tıyılğan.',
'wrongpassword'              => 'Yazılğan sersüz döres tügel. Tağın ber tapqır sınağız.',
'wrongpasswordempty'         => 'Sersüz yulı buş bulırğa tieş tügel.',
'passwordtooshort'           => 'Sezsüz $1 {{PLURAL:$1|simvoldan}} torırğa tieş.',
'password-name-match'        => 'Kertelgän sersüz qullanuçı isemennän ayırılırğa tieş.',
'password-too-weak'          => 'Kürsätelgän sersüz bik ciñel häm qullanuğa yaramıy.',
'mailmypassword'             => 'Elektron poçtağa yaña sersüz cibärü',
'passwordremindertitle'      => '{{SITENAME}} qullanuçısına waqıtlı sersüz tapşıru',
'passwordremindertext'       => 'Kemder (bälki, sezder, IP adresı: $1) {{SITENAME}} ($4) öçen yaña sersüz sorattı. $2 öçen yaña sersüz: $3. Ägär bu sez bulsağız, sistemağa keregez häm sersüzne almaştırığız. Yaña sersüz $5 {{PLURAL:$5|kön}} ğämäldä bulaçaq.

Ägär sez sersüzne almaştırunı soramağan bulsağız yäki, onıtqan oçraqta, isegezgä töşergän bulsağız, bu xäbärgä iğtibar birmiçä, iske sersüzegezne qullanunı däwam itegez.',
'noemail'                    => '$1 isemle qullanuçı öçen elektron poçta adresı yazılmağan.',
'noemailcreate'              => 'Sez döres e-mail adresı kürsätergä tieş',
'passwordsent'               => 'Yaña sersüz $1 isemle qullanuçınıñ elektron poçta adresına cibärelde.

Zinhar, sersüzne alğaç, sistemağa yañadan keregez.',
'blocked-mailpassword'       => 'Sezneñ IP adresığız belän bitlär üzgärtep häm sersüzne yañartıp bulmıy.',
'eauthentsent'               => 'Adres üzgärtüne dälilläw öçen aña maxsus xat cibärelde. Xatta yazılğannarnı ütäwegez sorala.',
'throttled-mailpassword'     => 'Sersüzne elektron poçtağa cibärü ğämälen sez {{PLURAL:$1|soñğı $1 säğät}} eçendä qullandığız inde. Bu ğämälne yawızlarça qullanunı kisätü maqsatınnan anı $1 {{PLURAL:$1|säğät}} aralığında ber genä tapqır başqarıp bula.',
'mailerror'                  => 'Xat cibärü xatası: $1',
'acct_creation_throttle_hit' => 'Sezneñ IP adresınnan bu täwlek eçendä {{PLURAL:$1|$1 xisap yazması}} tözelde inde. Şunlıqtan bu ğämäl sezneñ öçen waqıtlıça yabıq.',
'emailauthenticated'         => 'Elektron poçta adresığız raslandı: $3, $2.',
'emailnotauthenticated'      => 'Elektron poçta adresığız äle dälillänmägän, şuña wikinıñ elektron poçta belän eşläw ğämälläre sünderelde.',
'noemailprefs'               => 'Elektron poçta adresığız kürsätelmägän, şuña wikinıñ elektron poçta belän eşläw ğämälläre sünderelgän.',
'emailconfirmlink'           => 'Elektron poçta adresığıznı dälillägez.',
'invalidemailaddress'        => 'Elektron poçta adresı qabul itelä almıy, çönki ul döres formatqa turı kilmi. Zinhar, döres adres kertegez yäki yulnı buş qaldırığız.',
'accountcreated'             => 'Xisap yazması tözelde',
'accountcreatedtext'         => '$1 isemle qullanuçı öçen xisap yazması tözelde.',
'createaccount-title'        => '{{SITENAME}}: terkälü',
'createaccount-text'         => 'Kemder, elektron poçta adresığıznı kürsätep, {{SITENAME}} ($4) proyektında «$3» sersüze belän «$2» isemle xisap yazması terkäde. Sez kerergä häm sersüzegezne üzgärtergä tieş.

Xisap yazması tözü xata bulsa, bu xatnı onıtığız.',
'usernamehasherror'          => 'Qullanuçı isemendä "#" simvolı bula almıy',
'login-throttled'            => 'Sez artıq küp tapqır kerergä tırıştığız.
Yañadan qabatlağançı beraz kötüegez sorala.',
'loginlanguagelabel'         => 'Tel: $1',
'suspicious-userlogout'      => 'Sezneñ eşçänlekne beterü sorawığız kire qağıldı, çönki ul yalğış brauzer yäisä keşlawçı proksi aşa cibärelerge mömkin.',
'ratelimit-excluded-ips'     => ' #<!-- leave this line exactly as it is --> <pre>
# Sintaksis taswirlaması:
#   *  "#" tamğısınnan başlanuçı yullar, bäxäsleklär dip sanalalar
#   * Härber buş bulmağan yul IP-yullama dip sanala
 #</pre> <!-- leave this line exactly as it is -->',

# JavaScript password checks
'password-strength'            => 'Sersüzneñ sıyfatın tikşerü: $1',
'password-strength-bad'        => 'NAÇAR',
'password-strength-mediocre'   => 'urtaça',
'password-strength-acceptable' => 'qänäğätlänerlek',
'password-strength-good'       => 'yaxşı',
'password-retype'              => 'Sersüzne yañadan kertegez',
'password-retype-mismatch'     => 'Sersüzlär turı kilmi',

# Password reset dialog
'resetpass'                 => 'Sersüzne üzgärtü',
'resetpass_announce'        => 'Sez elektron poçta aşa waqıtlıça birelgän sersüz yärdämendä kerdegez. Sistemağa kerüne tögälläw öçen yaña sersüz tözegez.',
'resetpass_text'            => '<!-- Monda tekst östägez -->',
'resetpass_header'          => 'Xisap yazması sersüzen üzgärtü',
'oldpassword'               => 'İske sersüz:',
'newpassword'               => 'Yaña sersüz:',
'retypenew'                 => 'Yaña sersüzne qabatlağız:',
'resetpass_submit'          => 'Sersüz quyıp kerü',
'resetpass_success'         => 'Sezneñ sersüz uñışlı üzgärtelde! Sistemağa kerü başqarıla...',
'resetpass_forbidden'       => 'Sersüz üzgärtelä almıy',
'resetpass-no-info'         => 'Bu bitne qaraw öçen sez sistemağa üz xisap yazmağız yärdämendä kerergä tieş.',
'resetpass-submit-loggedin' => 'Sersüzne üzgärtü',
'resetpass-submit-cancel'   => 'Kire qağu',
'resetpass-wrong-oldpass'   => 'Yalğış sersüz.
Sez sersüzegezne üzgärtkän yäisä yaña waqıtlı sersüz soratqan bulırğa mömkinsez.',
'resetpass-temp-password'   => 'Waqıtlı sersüz:',

# Edit page toolbar
'bold_sample'     => 'Qalın yazılış',
'bold_tip'        => 'Qalın yazılış',
'italic_sample'   => 'Kursiv yazılış',
'italic_tip'      => 'Kursiv yazılış',
'link_sample'     => 'Sıltama iseme',
'link_tip'        => 'Eçke sıltama',
'extlink_sample'  => 'http://www.example.com sıltama iseme',
'extlink_tip'     => 'Tışqı sıltama (http:// alquşımçası turında onıtmağız)',
'headline_sample' => 'Başisem',
'headline_tip'    => '2 nçe däräcäle isem',
'math_sample'     => 'Formulanı monda östägez',
'math_tip'        => 'Matematik formula (LaTeX formatı)',
'nowiki_sample'   => 'Formatlanmağan tekstnı monda östägez',
'nowiki_tip'      => 'Wiki-formatlawnı isäpkä almaw',
'image_sample'    => 'Misal.jpg',
'image_tip'       => 'Quyılğan fayl',
'media_sample'    => 'Mísal.ogg',
'media_tip'       => 'Media-faylğa sıltama',
'sig_tip'         => 'İmza häm waqıt',
'hr_tip'          => 'Gorizontal sızıq (yış qullanmağız)',

# Edit pages
'summary'                          => 'Üzgärtülär taswirlaması:',
'subject'                          => 'Tema/başisem:',
'minoredit'                        => 'Bu keçe üzgärtü',
'watchthis'                        => 'Bu bitne küzätü',
'savearticle'                      => 'Bitne saqlaw',
'preview'                          => 'Aldan qaraw',
'showpreview'                      => 'Aldan qaraw',
'showlivepreview'                  => 'Tiz aldan qaraw',
'showdiff'                         => 'Kertelgän üzgärtülär',
'anoneditwarning'                  => "'''İğtibar''': Sez sistemağa kermägänsez. IP adresığız bu bitneñ tarixına yazılaçaq.",
'anonpreviewwarning'               => "''Sez sistemada terkälmädegez.Sezneñ taraftan eşlängän barlıq üzgärtülär dä sezneñ IP-yullamağıznı saqlawğa kiterä.''",
'missingsummary'                   => "'''İskärtü.''' Sez üzgärtügä qısqaça taswirlaw yazmadığız. Sez «Bitne saqlaw» töymäsenä tağın ber tapqır bassağız, üzgärtülär taswirlamasız saqlanaçaq.",
'missingcommenttext'               => 'Asqa taswirlama yazuığız sorala.',
'missingcommentheader'             => "''İskärtü:''' Sez taswirlamağa isem birmädegez.
«{{int:savearticle}}» töymäsenä qabat bassağız, üzgärtülär isemsez yazılaçaq.",
'summary-preview'                  => 'Taswirlamanı aldan qaraw:',
'subject-preview'                  => 'Başisemne aldan qaraw:',
'blockedtitle'                     => 'Qullanuçı tıyıldı',
'blockedtext'                      => "'''Sezneñ xisap yazmağız yäki IP adresığız tıyılğan.'''

Tıyuçı idaräçe: $1.
Kürsätelgän säbäp: ''$2''.

* Tıyu başlanğan waqıt: $8
* Tıyu axırı: $6
* Tıyılular sanı: $7

Sez $1 yäki başqa [[{{MediaWiki:Grouppage-sysop}}|idaräçegä]] tıyu buyınça sorawlarığıznı cibärä alasız.
İsegezdä totığız: ägär sez terkälmägän häm elektron poçta adresığıznı dälillämägän bulsağız ([[Special:Preferences|dälilläw öçen şäxsi köyläwlär monda]]), idaräçegä xat cibärä almıysız. Şulay uq tıyu waqıtında sezneñ xat cibärü mömkinlegegezne çiklägän bulırğa da mömkinnär.
Sezneñ IP adresı — $3, tıyu identifikatorı — #$5.
Xatlarda bu mäğlümatnı kürsätergä onıtmağız.",
'autoblockedtext'                  => "Sezneñ IP adresığız, anıñ tıyılğan qullanuçı tarafınnan qullanıluı säbäple, avtomat räweştä tıyıldı.
Ul qullanuçını tıyuçı idaräçe: $1. Kürsätelgän säbäp:

:''$2''

* Tıyu başlanğan waqıt: $8
* Tıyu axırı: $6
* Tıyılular sanı: $7

Sez $1 yäki başqa [[{{MediaWiki:Grouppage-sysop}}|idaräçegä]] tıyu buyınça sorawlarığıznı cibärä alasız.
İsegezdä totığız: ägär sez terkälmägän häm elektron poçta adresığıznı dälillämägän bulsağız ([[Special:Preferences|dälilläw öçen şäxsi köyläwlär monda]]), idaräçegä xat cibärä almıysız. Şulay uq tıyu waqıtında sezneñ xat cibärü mömkinlegegezne çiklägän bulırğa da mömkinnär.
Sezneñ IP adresı — $3, tıyu identifikatorı — #$5.
Xatlarda bu mäğlümatnı kürsätergä onıtmağız.",
'blockednoreason'                  => 'säbäp kürsätelmägän',
'blockedoriginalsource'            => "Asta '''$1''' biteneñ tekstı kürsätelgän.",
'blockededitsource'                => "Asta '''$1''' biteneñ '''sez üzgärtkän''' tekstı kürsätelgän.",
'whitelistedittitle'               => 'Üzgärtü öçen üz isemegez belän kerergä kiräk',
'whitelistedittext'                => 'Sez bitlärne üzgärtü öçen $1 tieş.',
'confirmedittext'                  => 'Bitlärne üzgärtü aldınnan sez elektron poçta adresığıznı dälillärgä tieş.
Sez monı [[Special:Preferences|köyläwlär bitendä]] başqara alasız.',
'nosuchsectiontitle'               => 'Mondıy bülekne tabıp bulmıy.',
'nosuchsectiontext'                => 'Sez bulmağan bülekne tözätergä telisez.
Sez bu säxifäne qarağanda ul beterelä aldı.',
'loginreqtitle'                    => 'Kerü kiräk',
'loginreqlink'                     => 'kerü',
'loginreqpagetext'                 => 'Sez başqa bitlär qaraw öçen $1 tieş.',
'accmailtitle'                     => 'Sersüz cibärelde.',
'accmailtext'                      => "[[User talk:$1|$1]] qullanuçısı öçen tözelgän sersüz $2 adresına cibärelde.

Saytqa kergäç sez ''[[Special:ChangePassword|sersüzegezne üzgärtä alasız]]''.",
'newarticle'                       => '(Yaña)',
'newarticletext'                   => "Sez älegä yazılmağan bitkä kerdegez.
Yaña bit yasaw öçen astağı täräzädä mäqälä tekstın cıyığız ([[{{MediaWiki:Helppage}}|yärdäm biten]] qarıy alasız).
Ägär sez bu bitkä yalğışlıq belän eläkkän bulsağız, brauzerığıznıñ '''artqa''' töymäsenä basığız.",
'anontalkpagetext'                 => "----''Bu bäxäs bite sistemada terkälmägän yäisä üz iseme belän kermägän qullanuçınıqı.
Anı tanu öçen IP adresı faydalanıla.
Ägär sez anonim qullanuçı häm sezgä yullanmağan xäbärlär aldım dip sanıysız ikän (ber IP adresı küp qullanuçı öçen bulırğa mömkin), başqa mondıy añlaşılmawçanlıqlar kilep çıqmasın öçen [[Special:UserLogin|sistemağa keregez]] yäisä [[Special:UserLogin/signup|terkälegez]].''",
'noarticletext'                    => "Xäzerge waqıtta bu bittä tekst yuq.
Sez [[Special:Search/{{PAGENAME}}|bu isem kergän başqa mäqälälärne]],
<span class=\"plainlinks\">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} köndäleklärdäge yazmalarnı] taba
yäki '''[{{fullurl:{{FULLPAGENAME}}|action=edit}} şuşındıy isemle yaña bit tözi]''' alasız.",
'noarticletext-nopermission'       => 'Xäzerge waqıtta bu bittä tekst yuq.
Sez [[Special:Search/{{PAGENAME}}|bu isem kergän başqa mäqälälärne]],
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} köndäleklärdäge yazmalarnı] taba alasız.</span>',
'userpage-userdoesnotexist'        => '«$1» isemle xisap yazması yuq. Sez çınlap ta bu bitne yasarğa yäisä üzgärtergä telisezme?',
'userpage-userdoesnotexist-view'   => '"$1" isemle xisap yazması yuq.',
'blocked-notice-logextract'        => 'Bu qullanuçı xäzergä tıyıldı.
Tübändä tıyu köndälegeneñ soñğı yazu birelgän:',
'clearyourcache'                   => "'''İskärmä:''' Bitne saqlağannan soñ üzgärtülär kürensen öçen brauzerığıznıñ keşın çistartığız.
Monı '''Mozilla / Firefox''': ''Ctrl+Shift+R'', '''Safari''': ''Cmd+Shift+R'', '''IE:''' ''Ctrl+F5'', '''Konqueror''': ''F5'', '''Opera''': ''Tools→Preferences'' aşa eşläp bula.",
'usercssyoucanpreview'             => "'''Yärdäm:''' \"{{int:showpreview}} töymäsenä basıp, yaña CSS-faylnı tikşerep bula.",
'userjsyoucanpreview'              => "'''Yärdäm:''' \"{{int:showpreview}}\" töymäsenä basıp, yaña JS-faylnı tikşerep bula.",
'usercsspreview'                   => "'''Bu barı tik CSS-faylnı aldan qaraw ğına, ul äle saqlanmağan!'''",
'userjspreview'                    => "'''Bu barı tik JavaScript faylın aldan qaraw ğına, ul äle saqlanmağan!'''",
'userinvalidcssjstitle'            => "'''İğtibar:''' \"\$1\" bizäw teması tabılmadı. Qullanuçınıñ .css häm .js bitläre isemnäre barı tik keçkenä (yul) xäreflärdän genä torırğa tieş ikänen onıtmağız. Misalğa: {{ns:user}}:Foo/monobook.css, ä {{ns:user}}:Foo/Monobook.css tügel!",
'updated'                          => '(Yañartıldı)',
'note'                             => "'''İskärmä:'''",
'previewnote'                      => "'''Bu fäqät aldan qaraw ğına, üzgärtüläregez äle saqlanmağan!'''",
'previewconflict'                  => 'Älege aldan qaraw bitendä saqlanaçaq tekstnıñ niçek kürenäçäge kürsätelä.',
'session_fail_preview'             => "'''Qızğanıçqa, sezneñ sessiä identifikatorığız yuğaldı. Näticädä server üzgärtüläregezne qabul itä almıy.
Tağın ber tapqır qabatlawığız sorala.
Bu xata tağın qabatlansa, [[Special:UserLogout|çığığız]] häm yañadan keregez.'''",
'session_fail_preview_html'        => "'''Qızğanıçqa, sezneñ sessiä turında mäğlümatlar yuğaldı. Näticädä server üzgärtüläregezne qabul itä almıy.'''

''{{SITENAME}} çista HTML qullanırğa röxsät itä, ä bu üz çiratında JavaScript-ataqalar oyıştıru öçen qullanılırğa mömkin. Şul säbäple sezneñ öçen aldan qaraw mömkinlege yabıq.''

'''Ägär sez üzgärtüne yaxşı niät belän başqarasız ikän, tağın ber tapqır qabatlap qarağız. Xata qabatlansa, sayttan [[Special:UserLogout|çığığız]] häm yañadan keregez.'''",
'token_suffix_mismatch'            => "'''Sezneñ üzgärtü qabul itelmäde.'''
Säbäbe: brauzerığız üzgärtü ölkäsendäge punktuatsiäne döres kürsätmi, näticädä tekst bozılırğa mömkin.
Mondıy xatalar anonim web-proksilar qullanğanda kilep çığarğa mömkin.",
'editing'                          => '«$1» biten üzgärtü',
'editingsection'                   => '«$1» bitendä bülek üzgärtüe',
'editingcomment'                   => '«$1» biten üzgärtü (yaña bülek)',
'editconflict'                     => 'Üzgärtü konfliktı: $1',
'explainconflict'                  => 'Sez bu bitne tözätkän waqıtta kemder aña üzgäreşlär kertte.
Östäge täräzädä Sez xäzerge tekstnı küräsez.
Astağı täräzädä Sezneñ wariant urnaşqan.
Eşlägän üzgärtüläregezne astağı täräzädän östägenä küçeregez.
«{{int:savearticle}}» töymäsenä basqaç östäge bitneñ tekstı saqlanayaçaq.',
'yourtext'                         => 'Sezneñ tekst',
'storedversion'                    => 'Saqlanğan yurama',
'nonunicodebrowser'                => "'''Kisätü: Sezneñ brauzer Yünikod kodlawın tanımıy.'''
Üzgärtü waqıtında ASCII bulmağan simvollar maxsus unaltılı kodlarğa alıştırılaçaq.",
'editingold'                       => "'''Kisätü: Sez bitneñ iskergän yuramasın üzgärtäsez.'''
Saqlaw töymäsenä basqan oçraqta yaña yuramalardağı üzgärtülär yuğalaçaq.",
'yourdiff'                         => 'Aermalar',
'copyrightwarning'                 => "Böten östämälär häm üzgärtülär $2 (qarağız: $1) litsenziäse şartlarında başqarıla dip sanala.
Ägär alarnıñ irekle taratıluın häm üzgärtelüen telämäsägez, monda östämäwegez sorala.<br />
Sez östämälärneñ avtorı bulırğa yäisä mäğlümatnıñ irekle çığanaqlardan alınuın kürsätergä tieş.<br />
'''MAXSUS RÖXSÄTTÄN BAŞQA AVTORLIQ XOQUQI BUYINÇA SAQLANUÇI MÄĞLÜMATLAR URNAŞTIRMAĞIZ!'''",
'copyrightwarning2'                => "Sezneñ üzgärtülär başqa qullanuçılar tarafınnan üzgärtelä yäisä beterelä ala.
Ägär alarnıñ üzgärtelüen telämäsägez, monda östämäwegez sorala.<br />
Sez östämälärneñ avtorı bulırğa yäisä mäğlümatnıñ irekle çığanaqlardan alınuın kürsätergä tieş (qarağız: $1).
'''MAXSUS RÖXSÄTTÄN BAŞQA AVTORLIQ XOQUQI BUYINÇA SAQLANUÇI MÄĞLÜMATLAR URNAŞTIRMAĞIZ!'''",
'longpagewarning'                  => "'''Kisätü:''' Bu bitneñ zurlığı - $1 kilobayt.
32 Kb yäisä annan zurraq bitlär qayber brauzerlarda yalğış kürenergä mömkin.
Tekstnı berniçä öleşkä bülärgä täğdim itelä.",
'longpageerror'                    => "'''XATA: saqlanuçı tekst zurlığı - $1 kilobayt, bu $2 kilobayt çigennän kübräk. Bit saqlana almıy.'''",
'readonlywarning'                  => "'''Kisätü: mäğlümatlar bazasında texnik eşlär başqarıla, sezneñ üzgärtülär xäzer ük saqlana almıy.
Tekst yuğalmasın öçen anı kompyuterığızğa saqlap tora alasız.'''

İdaräçe kürsätkän säbäp: $1",
'protectedpagewarning'             => "'''Kisätü: sez bu bitne üzgärtä almıysız, bu xoquqqa idaräçelär ğına iä.'''
Tübändä köndälekneñ  soñğı yazuı birelgän:",
'semiprotectedpagewarning'         => "'''Kisätü:''' bu bit yaqlanğan. Anı terkälgän qullanuçılar ğına üzgärtä ala.
Asta bu bitne küzätü köndälege birelgän:",
'cascadeprotectedwarning'          => "'''Kisätü:''' Bu bitne idaräçelär ğına üzgärtä ala. Säbäbe: ul {{PLURAL:$1|kaskadlı yaqlaw isemlegenä kertelgän}}:",
'titleprotectedwarning'            => "'''Kisätü: Mondıy isemle bit yaqlanğan, anı üzgärtü öçen [[Special:ListGroupRights|tieşle xoquqqa]] iä bulu zarur.'''
Asta küzätü köndälegendäge soñğı yazma birelgän:",
'templatesused'                    => 'Bu bittä qullanılğan {{PLURAL:$1|şablon|şablonnar}}:',
'templatesusedpreview'             => 'Aldan qaraluçı bittä qullanılğan {{PLURAL:$1|ürnäk|ürnäklär}}:',
'templatesusedsection'             => 'Bu bülektä qullanılğan {{PLURAL:$1|ürnäk|ürnäklär}}:',
'template-protected'               => '(yaqlanğan)',
'template-semiprotected'           => '(öleşçä yaqlanğan)',
'hiddencategories'                 => 'Bu bit $1 {{PLURAL:$1|yäşeren törkemgä}} kerä:',
'nocreatetitle'                    => 'Bitlär tözü çiklängän',
'nocreatetext'                     => '{{SITENAME}}: saytta yaña bitlär tözü çiklängän.
Sez artqa qaytıp, tözelgän bitne üzgärtä alasız. [[Special:UserLogin|Kerergä yäisä terkälergä]] täğdim itelä.',
'nocreate-loggedin'                => 'Sezgä yaña bitlär tözü xoquqı birelmägän.',
'sectioneditnotsupported-title'    => 'Büleklärne üzgärtü röxsät itelmi.',
'sectioneditnotsupported-text'     => 'Bu bittä büleklärne üzgärtü röxsät itelmi.',
'permissionserrors'                => 'Kerü xoquqı xataları',
'permissionserrorstext'            => 'Tübändäge {{PLURAL:$1|säbäp|säbäplär}} arqasında sez bu ğämälne başqara almıysız:',
'permissionserrorstext-withaction' => '$2 ğämälen başqara almıysız. {{PLURAL:$1|Säbäbe|Säbäpläre}}:',
'recreate-moveddeleted-warn'       => "'''İğtibar: Sez beterelgän bit urınına yaña bit yasamaqçı bulasız.'''

Sezgä çınnan da bu bitne yañadan yasaw kiräkme?
Tübändä bitneñ beterü häm küçerü köndälege kiterelä:",
'moveddeleted-notice'              => 'Bu bit beterelgän ide.
Tübändä beterelü häm küçerelü köndälekne kiterelä.',
'log-fulllog'                      => 'Köndälekne tulısınça qaraw',
'edit-hook-aborted'                => 'Üzgärtü maxsus protsedura tarafınnan kire qağıla.
Säbäpläre kiterelmi.',
'edit-gone-missing'                => 'Bitne yañartıp bulmıy.
Ul beterelgän bulırğa mömkin.',
'edit-conflict'                    => 'Üzgärtülär konfliktı.',
'edit-no-change'                   => 'Tekstta üzgäeşlär yasalmaw säbäple, sezneñ üzgärtü kire qağıla.',
'edit-already-exists'              => 'Yaña bit tözep bulmıy.
Ul inde bar.',

# Parser/template warnings
'expensive-parserfunction-warning'        => "'''İğtibar:''' bu bittä xäterne yış qullanuçı funksiälär artıq küp.

Çikläw: $2 {{PLURAL:$2|qullanu}}, bu oçraqta {{PLURAL:$1|$1 tapqır}} başqarırğa röxsät itelä.",
'expensive-parserfunction-category'       => 'Xäterne yış qullanuçı funksiälär küp bulğan bitlär',
'post-expand-template-inclusion-warning'  => "'''İğtibar:''' Qullanıluçı ürnäklär artıq zur.
Qayberläre qabızılmayaçaq.",
'post-expand-template-inclusion-category' => 'Röxsät itelgän külämnän artıq bulğan ürnäkle bitlär',
'post-expand-template-argument-warning'   => "'''İğtibar:''' Bu bit açu öçen zur bulğan kimendä ber ürnäk argumentına iä.
Mondıy argumentlar töşerep qaldırıldı.",
'post-expand-template-argument-category'  => 'Töşerep qaldırılğan ürnäk argumentlı bitlär',
'parser-template-loop-warning'            => 'Ürnäklärdä yomıq sıltama tabıldı: [[$1]]',
'parser-template-recursion-depth-warning' => '($1) ürnägen rekursiä itep qullanu çige röxsät itelgännän artıp kitkän',
'language-converter-depth-warning'        => 'Tellärne üzgärtüläre artıq yuğarığa kitkän ($1)',

# "Undo" feature
'undo-success' => 'Üzgärtüdän baş tartıp bula.
Yuramalaranı çağıştırunı qarağız häm, üzgärtülär Sez telägänçä bulsa, bitne saqlağız.',
'undo-failure' => 'Aralıqtağı üzgärtülär turı kilmäw säbäple, üzgärtüdän baş tartıp bulmıy.',
'undo-norev'   => 'Üzgärtü yuq yäisä ul beterelgän, şuña annan baş tartıp bulmıy.',
'undo-summary' => '[[Special:Contributions/$2|$2]] qullanuçısınıñ ([[User talk:$2|bäxäs]]) $1 üzgärtüennän baş tartu',

# Account creation failure
'cantcreateaccounttitle' => 'Xisap yazmasın tözep bulmıy',
'cantcreateaccount-text' => "Bu IP adresınnan (<b>$1</b>) xisap yazmaları tözü tıyıla. Tıyuçı: [[User:$3|$3]].

$3 kürsätkän säbäp: ''$2''",

# History pages
'viewpagelogs'           => 'Bu bitneñ köndäleklären qaraw',
'nohistory'              => 'Bu bitneñ üzgärtülär tarixı yuq.',
'currentrev'             => 'Xäzerge yurama',
'currentrev-asof'        => 'Xäzerge yurama, $1',
'revisionasof'           => '$1 yuraması',
'revision-info'          => 'Yurama: $1; $2',
'previousrevision'       => '← Aldağı yurama',
'nextrevision'           => 'Çirattağı yurama →',
'currentrevisionlink'    => 'Xäzerge yurama',
'cur'                    => 'xäzerge',
'next'                   => 'kiläse',
'last'                   => 'baya.',
'page_first'             => 'berençe',
'page_last'              => 'soñğı',
'histlegend'             => "Añlatmalar: '''({{int:cur}})''' = xäzerge yuramadan ayırımlıqlar, '''({{int:last}})''' = bayağı yuramadan ayırımlıqlar, '''{{int:minoreditletter}}''' = keçe üzgärtülär.",
'history-fieldset-title' => 'Tarixın qaraw',
'history-show-deleted'   => 'Barı tik beterü',
'histfirst'              => 'Elekkege',
'histlast'               => 'Soñğı',
'historysize'            => '($1 {{PLURAL:$1|bayt}})',
'historyempty'           => '(buş)',

# Revision feed
'history-feed-title'          => 'Üzgärtülär tarixı',
'history-feed-description'    => 'Bu bitneñ wikidağı üzgärtülär tarixı',
'history-feed-item-nocomment' => '$1, $2',
'history-feed-empty'          => 'Soratılğan bit yuq.
Ul beterelgän yäisä bütän urınğa küçerelgän (başqa isem alğan) bulırğa mömkin.
[[Special:Search|Ezlätep]] qarağız.',

# Revision deletion
'rev-deleted-comment'         => '(fiker beterelgän)',
'rev-deleted-user'            => '(avtor iseme beterelgän)',
'rev-deleted-event'           => '(yazma beterelgän)',
'rev-deleted-user-contribs'   => '[qullanuçınıñ iseme yäki  IP-yullaması beterelgän  — üzgärtü kertem bitennän yäşerelgän]',
'rev-deleted-text-permission' => "Bitneñ bu yuraması '''beterelgän'''.
[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} Beterülär köndälegendä] añlatmalar qaldırılğan bulırğa mömkin.",
'rev-deleted-text-unhide'     => "Bitneñ bu yuraması '''beterelgän'''.
[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} Beterülär köndälegendä]  añlatmalar qaldırılğan bulırğa mömkin.
Sez idaräçe bulu säbäple, [$1 birelgän yuramanı qarıy alasız].",
'rev-suppressed-text-unhide'  => "Bitneñ bu yuraması '''yäşerelgän'''.
[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Yäşerülär köndälegendä] añlatmalar birelgän bulırğa mömkin.
Sez idaräçe bulu säbäple, [$1 birelgän yuramanı qarıy alasız].",
'rev-deleted-text-view'       => "Bitneñ bu yuraması '''beterelgän'''.
Sez idaräçe bulu säbäple, anı qarıy alasız. [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} Beterülär köndälegendä] añlatmalar birelgän bulırğa mömkin.",
'rev-suppressed-text-view'    => "Bitneñ bu yuraması '''yäşerelgän'''.
Sez idaräçe bulu säbäple, anı qarıy alasız. [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Yäşerülär köndälegendä] añlatmalar birelgän bulırğa mömkin.",
'rev-deleted-no-diff'         => "Sez yuramalar arasındağı ayırmalarnı qarıy almıysız. Säbäbe: qaysıdır yurama '''beterelgän'''.
[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} Beterülär köndälegendä] tulıraq mäğlümat tabıp bula.",
'rev-suppressed-no-diff'      => "Sez yuramalar  arasındağı üzgärtülärne qarıy almıysız, çönki alarnıñ berse '''beterelgän'''.",
'rev-deleted-unhide-diff'     => "Bitneñ qaysıdır yuraması '''beterelgän'''.
[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} Beterülär köndälegendä] tulıraq mäğlümat tabıp bula.
Sez idaräçe bulu säbäple, [$1 birelgän yuramanı qarıy alasız]",
'rev-suppressed-unhide-diff'  => "Bitneñ qaysıdır yuraması '''yäşerelgän'''.
[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Yäşerülär köndälegendä] tulıraq mäğlümat tabıp bula.
Sez idaräçe bulu säbäple, [$1 yäşerelgän yuramanı qarıy alasız]",
'rev-delundel'                => 'kürsätü/yäşerü',
'rev-showdeleted'             => 'kürsätü',
'revisiondelete'              => 'Bitneñ yuramasın beterü / qaytaru',
'revdelete-nooldid-title'     => 'Axırğı yurama bilgelänmägän',
'revdelete-nooldid-text'      => 'Bu funksiäne başqaru öçen sez axırğı yuramanı (yäki yuramalarnı) bilgelämädegez.',
'revdelete-nologtype-title'   => 'Köndälek tibı bilgelänmägän',
'revdelete-nologtype-text'    => 'Ğämäl başqarılırğa tieşle köndälek tören bilgelärgä onıttığız.',
'revdelete-nologid-title'     => 'Köndälektäge yazma xatalı',
'revdelete-show-file-submit'  => 'Äye',
'revdelete-legend'            => 'Çikläwlär urnaştır:',
'revdelete-hide-text'         => 'Bitneñ bu yuraması tekstın yäşer',
'revdelete-hide-image'        => 'Fayl eçendägelärne qaçır',
'revdelete-radio-same'        => '(üzgärtmäw)',
'revdelete-radio-set'         => 'Äye',
'revdelete-radio-unset'       => 'Yuq',
'revdel-restore'              => 'kürenüçänlekne üzgärtü',
'revdel-restore-deleted'      => 'beterelgän yuramalar',
'revdel-restore-visible'      => 'kürsätelgän yuramalar',
'pagehist'                    => 'bitneñ tarixı',
'deletedhist'                 => 'Beterülär tarixı',
'revdelete-content'           => 'eçtälek',
'revdelete-summary'           => 'üzgärtülär taswirlaması',
'revdelete-uname'             => 'qullanuçı iseme',
'revdelete-restricted'        => 'çikläwlär idaräçelärgä dä qullanıla',
'revdelete-hid'               => ' $1 yäşerelgän',
'revdelete-unhid'             => '$1 açılğan',
'revdelete-reasonotherlist'   => 'Başqa säbäp',
'revdelete-edit-reasonlist'   => 'Säbäplär isemlegen üzgärtü',
'revdelete-offender'          => 'Älege yuramalı bitneñ avtorı:',

# Suppression log
'suppressionlog' => 'Yäşerü köndälege',

# Revision move
'revmove-reasonfield'    => 'Säbäp:',
'revmove-titlefield'     => 'Töp bit:',
'revmove-badparam-title' => 'Yaraqsız parametrlar',
'revmove-nullmove-title' => 'Yaraqsız isem',

# History merging
'mergehistory'        => 'Üzgärtülär tarixın berläşterü',
'mergehistory-box'    => 'İke bitneñ üzgärtülär tarixın berläşterergä:',
'mergehistory-from'   => 'Çığanaq:',
'mergehistory-into'   => 'Töp bit:',
'mergehistory-reason' => 'Säbäp:',

# Merge log
'mergelog'    => 'Berläşterülär köndälege',
'revertmerge' => 'Bülü',

# Diffs
'history-title'            => '$1 biteneñ üzgärtü tarixı',
'difference'               => '(Yuramalar arasında ayırma)',
'lineno'                   => '$1 yul:',
'compareselectedversions'  => 'Saylanğan yuramalarnı çağıştıru',
'showhideselectedversions' => 'Saylanğan yuramalarnı kürsätü/yäşerü',
'editundo'                 => 'ütkärmäw',

# Search results
'searchresults'                    => 'Ezläw näticäläre',
'searchresults-title'              => '«$1» öçen ezläw näticäläre',
'searchresulttext'                 => 'Proyektnıñ säxifälärendä ezläw turında tulıraq mäğlumat alır öçen [[{{MediaWiki:Helppage}}|östämä mäğlumat]] bitenä keregez.',
'searchsubtitle'                   => '«[[:$1]]» öçen ezläw ([[Special:Prefixindex/$1|«$1» dan başlıy barlıq bitlär]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|«$1» ğa sıltıy barlıq bitlär]])',
'searchsubtitleinvalid'            => '"$1" taläbe buyınça',
'titlematches'                     => 'Mäqälä başlığı kileşä',
'notitlematches'                   => 'Bitneñ isemnärendä turı kilülär yuq',
'notextmatches'                    => 'Tiñdäş tekstlı bitlär yuq',
'prevn'                            => 'aldağı {{PLURAL:$1|$1}}',
'nextn'                            => 'çirattağı {{PLURAL:$1|$1}}',
'viewprevnext'                     => 'Kürsätelüe: ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'Ezläw köylänmäläre',
'searchmenu-new'                   => "'''«[[:$1]]»  isemle yaña bit yasaw'''",
'searchhelp-url'                   => 'Help:Eçtälek',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Bu prefikslı bitlärne kürsätü]]',
'searchprofile-articles'           => 'Töp bitlär',
'searchprofile-project'            => 'Yärdäm häm proyektlar bite',
'searchprofile-images'             => 'Multimedia',
'searchprofile-everything'         => 'Härqayda',
'searchprofile-advanced'           => 'Kiñäytelgän',
'searchprofile-articles-tooltip'   => '$1 dä ezläw',
'searchprofile-project-tooltip'    => '$1 dä ezläw',
'searchprofile-images-tooltip'     => 'Fayllar ezläw',
'searchprofile-everything-tooltip' => 'Barlıq bitlärdä dä ezläw',
'searchprofile-advanced-tooltip'   => 'Birelgän isemnär mäydanında ezläw',
'search-result-size'               => '$1 ({{PLURAL:$2|$2 süz}})',
'search-result-score'              => 'Relevantlığı: $1 %',
'search-redirect'                  => '(yünältü $1)',
'search-section'                   => '($1 bülege)',
'search-suggest'                   => 'Bälki, sez monı ezlisez: $1',
'search-interwiki-caption'         => 'Tuğandaş proyektlar',
'search-interwiki-default'         => '$1 näticä:',
'search-interwiki-more'            => '(tağın)',
'search-mwsuggest-enabled'         => 'kiñäşlär belän',
'search-mwsuggest-disabled'        => 'kiñäşsez',
'search-relatedarticle'            => 'Bäylängän',
'mwsuggest-disable'                => 'AJAX-yärdämne yabu',
'searcheverything-enable'          => 'Barlıq isemnär mäydanında ezläw',
'searchrelated'                    => 'bäylängän',
'searchall'                        => 'barlıq',
'showingresults'                   => "Asta № '''$2''' {{PLURAL:$1|başlap}} '''$1''' {{PLURAL:$1|rezultat}} kürsätelgän.",
'showingresultsnum'                => "Asta № '''$2''' {{PLURAL:$3| başlap}} '''$3''' {{PLURAL:$3|rezultat}} kürsätelgän.",
'showingresultsheader'             => "'''$4''' öçen {{PLURAL:$5|Rezultat '''$1''' sennän '''$3'''|Rezultatlar '''$1 — $2''' sennän  '''$3'''}}",
'nonefound'                        => "'''İskärmä'''. Kileşü buyınça ezläw qayber isem alannarında ğına eşli.
Barlıq alannarda (bäxäs bitläre, ürnäklär, h.b.) ezläw öçen ''all'' süzen saylağız, yäisä kiräkle isem alanın saylağız.",
'search-nonefound'                 => 'Sorawğa turı kilgän cawaplar tabılmadı.',
'powersearch'                      => 'Östämä ezläw',
'powersearch-legend'               => 'Östämä ezläw',
'powersearch-ns'                   => 'isemnärendä ezläw',
'powersearch-redir'                => 'Yünältülär kürsätelsen',
'powersearch-field'                => 'Ezläw',
'powersearch-togglelabel'          => 'Kire qağıw:',
'powersearch-toggleall'            => 'Barısı',
'powersearch-togglenone'           => 'Birni dä yuq',
'search-external'                  => 'Tışqı ezläw',

# Quickbar
'qbsettings'               => 'Küçeşlär aslığı',
'qbsettings-none'          => 'Kürsätmäw',
'qbsettings-fixedleft'     => 'Sulda küçerelmäs',
'qbsettings-fixedright'    => 'Uñda küçerelmäs',
'qbsettings-floatingleft'  => 'Sulda yözmä',
'qbsettings-floatingright' => 'Uñda yözmä',

# Preferences page
'preferences'                   => 'Köylänmälär',
'mypreferences'                 => 'Köylänmälärem',
'prefs-edits'                   => 'Üzgärtülär isäbe:',
'prefsnologin'                  => 'Kermägänsez',
'prefsnologintext'              => 'Qullanuçı köylänmälärene üzgärtü öçen, sez <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} kerergä]</span> tieşsez.',
'changepassword'                => 'Sersüzne üzgärtü',
'prefs-skin'                    => 'Küreneş',
'skin-preview'                  => 'Aldan qaraw',
'prefs-math'                    => 'Formulalar',
'datedefault'                   => 'Baştağı köylänmälär',
'prefs-datetime'                => 'Data häm waqıt',
'prefs-personal'                => 'Şäxsi mäğlümatlar',
'prefs-rc'                      => 'Soñğı üzgärtülär',
'prefs-watchlist'               => 'Küzätü isemlege',
'prefs-watchlist-days'          => 'Küzätü isemlegendä kürsätelgän kön sanı:',
'prefs-watchlist-days-max'      => '7 könnän artıq tügel',
'prefs-watchlist-edits'         => 'Kiñäytelgän küzätü isemlegendä üzgärtülärneñ iñ yuğarı isäbe:',
'prefs-watchlist-edits-max'     => 'Maksimum san: 1000',
'prefs-watchlist-token'         => 'Küzätü isemlege toqenı:',
'prefs-misc'                    => 'Başqa köylänmälär',
'prefs-resetpass'               => 'Sersüzne üzgärtü',
'prefs-email'                   => 'E-mail köyläwläre',
'prefs-rendering'               => 'Küreneş',
'saveprefs'                     => 'Saqlaw',
'resetprefs'                    => 'Saqlanmağan üzgärtülärne beterü',
'restoreprefs'                  => 'Baştağı köylänmälärne kire qaytaru',
'prefs-editing'                 => 'Üzgärtü',
'prefs-edit-boxsize'            => 'Üzgärtü täräzäseneñ zurlığı',
'rows'                          => 'Yullar:',
'columns'                       => 'Bağanalar:',
'searchresultshead'             => 'Ezläw',
'resultsperpage'                => 'Ber bitkä turı kilgän tabıldıqlar:',
'contextlines'                  => 'Härber tabıldıqta kürsätelüçe yullar sanı:',
'contextchars'                  => 'Ber yulğa turı kilgän tamğalar:',
'stub-threshold'                => '<a href="#" class="stub">Yasalma sıltamalarnıñ</a> bizäleşe buyınça çikläwlär (baytlarda):',
'stub-threshold-disabled'       => 'Yabılğan',
'recentchangesdays'             => 'Soñğı üzgärtülärne kürsätüçe könnär sanı:',
'recentchangesdays-max'         => '( $1 {{PLURAL:$1|könnän}} dä artıq bulmasqa tieş)',
'recentchangescount'            => 'Töp bularaq qullanuçı üzgärtülär sanı:',
'prefs-help-recentchangescount' => 'Üz öçenä üzgärtülärne, bitlärneñ tarixın häm yazlu köndälegen dä kertä.',
'prefs-help-watchlist-token'    => 'Älege yulnı sersüz belän tutıru sezneñ küzätü isemlegegezneñ RSS-tasmasın barlıqqa kiteräçäk. Mondağı sersüzne belüçe härber keşe sezneñ küzätü isemlegegezne qarıy ala, şuña kürä avtomatik räweştä yasalğan sersüzne qullanığız: $1',
'savedprefs'                    => 'Köylänmäläregez saqlandı.',
'timezonelegend'                => 'Säğät poyası:',
'localtime'                     => 'Cirle waqıt',
'timezoneuseserverdefault'      => 'Serverneñ köylänmäläre qullanılsın',
'timezoneuseoffset'             => 'Başqa (küçerelüne kürsätegez)',
'timezoneoffset'                => 'Küçerelü¹:',
'servertime'                    => 'Servernıñ waqıtı:',
'guesstimezone'                 => 'Brauzerdan tutıru',
'timezoneregion-africa'         => 'Afrika',
'timezoneregion-america'        => 'Amerika',
'timezoneregion-antarctica'     => 'Antarktika',
'timezoneregion-arctic'         => 'Arktika',
'timezoneregion-asia'           => 'Aziä',
'timezoneregion-atlantic'       => 'Atlantik okean',
'timezoneregion-australia'      => 'Awstraliä',
'timezoneregion-europe'         => 'Awrupa',
'timezoneregion-indian'         => 'Hind okeanı',
'timezoneregion-pacific'        => 'Tın okean',
'allowemail'                    => 'Başqa qullanuçılardan xatlar alırğa röxsät itü',
'prefs-searchoptions'           => 'Ezläw köylänmäläre',
'prefs-namespaces'              => 'İsemnär mäydanı',
'defaultns'                     => 'Alaysa menä bu isemnär mäydanında ezläw',
'default'                       => 'kileşü buyınça',
'prefs-files'                   => 'Fayllar',
'prefs-custom-css'              => 'Üzemneñ CSS',
'prefs-custom-js'               => 'Üzemneñ JS',
'prefs-common-css-js'           => 'Barlıq bizäleşlär öçen ğomumi CSS/JS:',
'prefs-reset-intro'             => 'Bu bit sezneñ köylänmäläregezne beterü öçen qullanıla. Bu eşne başqaru näticäsendä sez yañadan üz köylänmälärne yañadan qaytara almıysız.',
'prefs-emailconfirm-label'      => 'E-mail raslaw',
'prefs-textboxsize'             => 'Üzgärtü täräzäseneñ zurlığı',
'youremail'                     => 'Elektron poçta:',
'username'                      => 'Qullanuçı iseme:',
'uid'                           => 'Qullanuçınıñ identifikatorı:',
'prefs-memberingroups'          => 'Törkem {{PLURAL:$1|äğzası}}:',
'prefs-memberingroups-type'     => '$1',
'prefs-registration'            => 'Terkälü waqıtı:',
'prefs-registration-date-time'  => '$1',
'yourrealname'                  => 'Çın isem:',
'yourlanguage'                  => 'Tel:',
'yourvariant'                   => 'Telneñ törläre:',
'yournick'                      => 'Yaña imzağız:',
'prefs-help-signature'          => 'Bäxäslek bitlärendä sezneñ yazmalarığıznı qaldıru «<nowiki>~~~~</nowiki>» tamğaları quyılu näticäsendä bulırğa tieş.',
'badsig'                        => 'İmza döres tügel. HTML tegları tikşeregez.',
'badsiglength'                  => 'İmzağız bigräk ozın.
Ul $1 {{PLURAL:$1|xäreftän}} kübräk bulırğa tieş tügel.',
'yourgender'                    => 'Cenes:',
'gender-unknown'                => 'bilgesez',
'gender-male'                   => 'İr',
'gender-female'                 => 'Xatın',
'prefs-help-gender'             => 'Qätği tügel: Ul barı tik qayber xatlarda ğına kürenäçäk häm bu mäğlümat barlıq qullanuçılarğa da bilgele bulaçaq.',
'email'                         => 'Elektron poçta',
'prefs-help-realname'           => 'Çın isemegez (kiräkmi): anı kürsätsägez, ul bitne üzgärtüçe kürsätü öçen faydalayaçaq.',
'prefs-help-email'              => 'Elektron poçta adresın kürsätü qätği tügel, läkin ägärdä sez üzegezneñ sersüzne onıtsağız bu sezgä anı yañadan qaytarırğa yärdäm itäçäk.',
'prefs-help-email-required'     => 'Elektron poçta adresı kiräk.',
'prefs-info'                    => 'Ğomumi mäğlümat',
'prefs-i18n'                    => 'İnternatsionalizatsiä',
'prefs-signature'               => 'İmza',
'prefs-dateformat'              => 'Waqıtıñ formatı',
'prefs-timeoffset'              => 'Waqıt bilgeläneşe',
'prefs-advancedediting'         => 'Kiñäytelgän köyläwlär',
'prefs-advancedrc'              => 'Kiñäytelgän köyläwlär',
'prefs-advancedrendering'       => 'Kiñäytelgän köyläwlär',
'prefs-advancedsearchoptions'   => 'Kiñäytelgän köyläwlär',
'prefs-advancedwatchlist'       => 'Kiñäytelgän köyläwlär',
'prefs-displayrc'               => 'Kürsätü köylänmäläre',
'prefs-displaysearchoptions'    => 'Kürsätü köylänmäläre',
'prefs-displaywatchlist'        => 'Kürsätü köylänmäläre',
'prefs-diffs'                   => 'Yuramalar ayırması',

# User rights
'userrights'                     => 'Qullanuçı xoquqları belän idarä itü',
'userrights-lookup-user'         => 'Qullanuçı törkemnäre belän idarä itü',
'userrights-user-editname'       => 'Qullanuçınıñ isemen kertegez:',
'editusergroup'                  => 'Qullanuçınıñ törkemnären almaştıru',
'editinguser'                    => "'''[[User:$1|$1]]''' qullanuçısınıñ xoquqların üzgärtü ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'       => 'Qullanuçınıñ törkemnären almaştıru',
'saveusergroups'                 => 'Qullanuçı törkemnären saqlaw',
'userrights-groupsmember'        => 'Äğza:',
'userrights-groupsmember-auto'   => 'Bilgesez äğza:',
'userrights-groups-help'         => 'Sez bu qullanuçınıñ xoquqların üzgärtä almıysız.
*Ägär dä qullanuçı iseme yanda tamğa torsa, dimäk bu qullanuçı birelgän törkemneñ äğzası.
*Ägär dä qullanuçı iseme yanda tamğa tormasa, dimäk bu qullanuçı birelgän törkemneñ äğzası tügel.
*"*" tamğası torsa sez bu qullanuçını bu törkemnän beterä almıysız.',
'userrights-reason'              => 'Säbäp:',
'userrights-no-interwiki'        => 'Sezneñ başqa wikilarda qullanuçılarnıñ xoquqların üzgärtergä xoquqlarığız yuq.',
'userrights-nodatabase'          => 'Birelgän $1 bazası yuq yäisä  lokal bulıp tormıy.',
'userrights-changeable-col'      => 'Sezneñ taraftan üzgärtä ala torğan törkemnär',
'userrights-unchangeable-col'    => 'Sezneñ taraftan üzgärtä almıy torğan törkemnär',
'userrights-irreversible-marker' => '$1*',

# Groups
'group'               => 'Törkem:',
'group-user'          => 'Qullanuçılar',
'group-autoconfirmed' => 'Avtoraslanğan qullanuçı',
'group-bot'           => 'Botlar',
'group-sysop'         => 'İdaräçelär',
'group-bureaucrat'    => 'Byurokratlar',
'group-suppress'      => 'Tikşerüçelär',
'group-all'           => '(barlıq)',

'group-user-member'          => 'Qullanuçı',
'group-autoconfirmed-member' => 'Avtoraslanğan qullanuçı',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'İdaräçe',
'group-bureaucrat-member'    => 'Byurokrat',
'group-suppress-member'      => 'Tikşerüçe',

'grouppage-user'          => '{{ns:project}}:Qullanuçılar',
'grouppage-autoconfirmed' => '{{ns:project}}:Avtoraslanğan qullanuçılar',
'grouppage-bot'           => '{{ns:project}}:Botlar',
'grouppage-sysop'         => '{{ns:project}}:İdaräçelär',
'grouppage-bureaucrat'    => '{{ns:project}}:Byurokratlar',
'grouppage-suppress'      => '{{ns:project}}:Tikşerüçelär',

# Rights
'right-read'          => 'Bitlärne qaraw',
'right-edit'          => 'Bitlärne üzgärtü',
'right-createpage'    => 'bitlär yasaw (bäxäs bulmağannarın)',
'right-createtalk'    => 'bäxäs biten yasaw',
'right-createaccount' => 'yaña qullanuçı biten yasaw',
'right-move'          => 'Bitlärne küçerü',
'right-movefile'      => 'fayllarnıñ isemen almaştıru',
'right-upload'        => 'fayllarnı yökläw',
'right-delete'        => 'bitlärne beterü',
'right-editinterface' => 'Qullanuçı interfeysın üzgärtü',

# User rights log
'rightslog'      => 'Qullanuçınıñ xoquqları köndälege',
'rightslogentry' => '$1 qullanuçısın $2 gruppasınnan $3 gruppasına küçerde',
'rightsnone'     => '(yuq)',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit'       => 'bu bitne üzgärtergä',
'action-createpage' => 'bitlärne yazırğa',
'action-createtalk' => 'bäxäs biten yasarğa',
'action-move'       => 'bu bitne küçererge',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|üzgärtü|üzgärtü}}',
'recentchanges'                     => 'Soñğı üzgärtülär',
'recentchanges-legend'              => 'Soñğı üzgärtülär köyläwläre',
'recentchangestext'                 => 'Bu bittä {{grammar:genitive|{{SITENAME}}}} proyektınıñ soñğı üzgärtüläre kürsätelä.',
'recentchanges-feed-description'    => 'Bu ağımda soñğı üzgärtülärne küzätü.',
'recentchanges-label-newpage'       => 'Bu üzgärtü belän yaña bit tözelde',
'recentchanges-label-minor'         => 'Bu keçe üzgärtü',
'rcnote'                            => 'Asta $4 $5 waqıtınna soñğı {{PLURAL:$2|1|$2}} kön eçendä bulğan soñğı {{PLURAL:$1|1|$1}} üzgärtmä kürsätelä:',
'rcnotefrom'                        => "Astaraq '''$2''' başlap ('''$1''' qädär) üzgärtülär kürsätelgän.",
'rclistfrom'                        => '$1 başlap yaña üzgärtülärne kürsät',
'rcshowhideminor'                   => 'keçe üzgärtülärne $1',
'rcshowhidebots'                    => 'botlarnı $1',
'rcshowhideliu'                     => 'kergän qullanuçılarnı $1',
'rcshowhideanons'                   => 'kermägän qullanuçılarnı $1',
'rcshowhidepatr'                    => 'tikşerergän üzgärtülärne $1',
'rcshowhidemine'                    => 'minem üzgärtüläremne $1',
'rclinks'                           => 'Soñğı $2 kön eçendä soñğı $1 üzgärtüne kürsät<br />$3',
'diff'                              => 'ayırma',
'hist'                              => 'tarix',
'hide'                              => 'yäşer',
'show'                              => 'kürsät',
'minoreditletter'                   => 'k',
'newpageletter'                     => 'Y',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|küzätep tora qullanuçı}}]',
'rc_categories'                     => 'Törkemnärdä genä tora («|» bülüçe)',
'rc_categories_any'                 => 'Härber',
'newsectionsummary'                 => '/* $1 */ yaña bülek',
'rc-enhanced-expand'                => 'Waqlıqlarnı kürsätü (JavaScript kiräk)',
'rc-enhanced-hide'                  => 'Waqlıqlarnı yäşerü',

# Recent changes linked
'recentchangeslinked'          => 'Bäyläneşle üzgärtülär',
'recentchangeslinked-feed'     => 'Bäyläneşle üzgärtülär',
'recentchangeslinked-toolbox'  => 'Bäyläneşle üzgärtülär',
'recentchangeslinked-title'    => '"$1" bitenä bäyläneşle üzgärtülär',
'recentchangeslinked-backlink' => '← $1',
'recentchangeslinked-noresult' => 'Kürsätelgän waqıtta sıltaşqan bitlärneñ üzgärtelmäläre yuq ide.',
'recentchangeslinked-summary'  => "Bu kürsätelgän bit belän sıltalğan (yä kürsätelgän törkemgä kertkän) bitlärneñ üzgärtelmäläre isemlege.
[[Special:Watchlist|Küzätü isemlegegezgä]] kerä torğan bitlär '''qalın'''.",
'recentchangeslinked-page'     => 'Bitneñ iseme:',
'recentchangeslinked-to'       => 'Monıñ urınına bu bitkä bäyle bulğan bitlärdäge üzgärtülärne kürsätü',

# Upload
'upload'                     => 'Faylnı yökläw',
'uploadbtn'                  => 'Faylnı yökläw',
'reuploaddesc'               => 'Faylnı yökläwgä kire qatu',
'upload-tryagain'            => 'Yañartılğan faylnı cibärü',
'uploadnologin'              => 'Sez xisap yazmağızğa kermägänsez',
'uploadnologintext'          => 'Faylnı yökläw öçen sez bu bitkä [[Special:UserLogin|kerergä]] tieşsez.',
'upload_directory_missing'   => '$1 Yöklänü direktoriäse yuq',
'upload_directory_read_only' => 'Moña Sezneñ xoquqlarığız yuq häm web-server $1 papqasını yökli almıy.',
'uploaderror'                => 'Faylnı yökläwdä xata',
'upload-recreate-warning'    => "'''İğtibar: Mondıy isemle fayl beterelgän yäki iseme almaştırılğan '''",
'uploadtext'                 => "Bu formanı qullanıp serverğa fayllar yökli alasız. Elegräk yöklänelgän fayllarnı qaraw öçen [[Special:FileList|Yöklänelgän fayllar isemlegenä]] märäcäğät itegez. Şulay uq ul [[Special:Log/upload|yöklänmälär isemlegenä]] häm [[Special:Log/delete|beterelgän fayllar]] isemlegenä dä yazıla.

Faylnı mäqälägä yökläw öçen Sez menä bu ürnäklärne qullana alasız:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Räsem.jpg]]</nowiki></tt>''' faylnıñ tulı yuramasın quyu öçen;
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Räsem.png|200px|thumb|left|taswirlaması]]</nowiki></tt>'''  200 pikselğa qädär kiñlektäge  häm tekstnıñ sul yağında, taswirlaması belän;
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></tt>'''bittä faylnı sürätlämiçä, barı tik sıltamasın ğına quyu.",
'upload-permitted'           => 'Röxsät itelgän fayl törläre:$1',
'upload-preferred'           => 'Mömkin bulğan fayl törläre:$1',
'upload-prohibited'          => 'Tıyılğan fayl törläre:$1',
'uploadlog'                  => 'Yökläw köndälege',
'uploadlogpage'              => 'Yökläw köndälege',
'uploadlogpagetext'          => 'Asta yaña yöklänelgän fayllar isemlege birelä.
Şulay uq [[Special:NewFiles|yaña fayllar ğällereyäsın]] qarağız',
'filename'                   => 'Fayl iseme',
'filedesc'                   => 'Qısqa taswirlama',
'fileuploadsummary'          => 'Üzgärtülär taswirlaması:',
'filereuploadsummary'        => 'Fayldağı üzgärtülär:',
'filestatus'                 => 'Taratu xoquqları:',
'filesource'                 => 'Çığanağı:',
'uploadedfiles'              => 'Yöklänelgän fayllar',
'ignorewarning'              => 'Belderüne kire qağu häm faylnı saqlaw',
'ignorewarnings'             => 'Belderüne kire qağu',
'minlength1'                 => 'Faylnıñ iseme ber genä xäreftän bulsa da torırğa tieş.',
'illegalfilename'            => 'faylnıñ iseme  «$1»  qullanuğa yaramağan simvollardan tora. Zinhar, faylnıñ isemen alıştırığız häm yañadan quyıp qarağız.',
'badfilename'                => 'Faylnıñ iseme $1 isemenä üzgärtelde.',
'filetype-mime-mismatch'     => 'Faylnıñ kinäytelmäse anıñ MIME-törenä turı kilmi.',
'filetype-badmime'           => 'MIME-töre «$1» bulğan fayllar, yöklänmäyäçäk.',
'filetype-bad-ie-mime'       => 'Faylnı yöklärgä mömkin tügel, çönki Internet Explorer anı «$1» dip qabul itäçäk.',
'filetype-unwanted-type'     => "'''\".\$1\"''' — tıyılğan fayl töre.
{{PLURAL:\$3|Mömkin bulğan fayl töre bulıp|Mömkin bulğan fayl töre:}} \$2.",
'filetype-banned-type'       => "'''\".\$1\"''' — tıyılğan fayl töre.
{{PLURAL:\$3|Kiñäytelgän fayl töre bulıp|Kiñäytelgän  fayl töre:}} \$2.",
'filetype-missing'           => "Faylnıñ kiñäytelmäse yuq ''(mäsälän,«.jpg»)''.",
'empty-file'                 => 'Sezneñ taraftan cibärelgän fayl buş.',
'file-too-large'             => 'Sezneñ taraftan cibärelgän fayl artıq zur.',
'filename-tooshort'          => 'Faylnıñ iseme artıq qısqa.',
'filetype-banned'            => 'Bu fayl töre tıyılğan.',
'verification-error'         => 'Bu fayl älegä tikşerü uzmağan.',
'uploadwarning'              => 'Yökläw kisätmäse',
'savefile'                   => 'Faylnı saqlaw',
'uploadedimage'              => '«[[$1]]» yöklängän',
'overwroteimage'             => '«[[$1]]» faylınıñ yaña yuraması yöklänelde',
'uploaddisabled'             => 'Yökläw tıyılğan',
'copyuploaddisabled'         => 'URL adresı buyınça yökläw yabılğan.',
'uploadfromurl-queued'       => 'Sezneñ yökläwegez çiratqa quyıldı.',
'uploaddisabledtext'         => 'Fayllarnı yökläw yabılğan.',
'upload-source'              => 'Faylnıñ çığanağı',
'sourcefilename'             => 'Faylnıñ çığanağı:',
'sourceurl'                  => 'Çığanaqnıñ URL adresı:',
'destfilename'               => 'Faylnıñ yaña iseme:',
'upload-maxfilesize'         => 'Faylnıñ maksimal zurlığı: $1',
'upload-description'         => 'Faylnıñ taswirlaması',
'upload-options'             => 'Yökläw parametrları',
'watchthisupload'            => 'Bu faylnı küzätü',
'filewasdeleted'             => 'Mondıy isemle fayl beterelgän bulğan inde. Zinhar,yañadan yökläw aldınnan $1 qarağız',
'upload-wasdeleted'          => "'''İğtibar: Sez beterelgän fayl urınına yañasın yöklämäkçe bulasız.'''

Sezgä çınnan da bu faylnı yökläw kiräkme?
Tübändä faylnıñ beterü köndälege kiterelä:",
'filename-bad-prefix'        => "Faylnıñ iseme '''«$1»''' dip başlana. Zinhar, faylnı taswirlawçı isem biregez.",
'filename-prefix-blacklist'  => ' #<!-- niçek bar şulay qaldırığız --> <pre>
# Sintaksis töbändägeçä:
#   *  «#» dip başlanğan barlıq närsä dä qömmentariy dip atalaçaq
#   * Härber buş rät — faylnıñ isemeneñ prefiksı, sifrlı kamera birüçe isem
CIMG # Casio
DSC_ # Nikon
DSCF # Fuji
DSCN # Nikon
DUW # qaysıber käräzle telefonnar
IMG # barlıq
JD # Jenoptik
MGP # Pentax
PICT # törle
 #</pre> <!-- niçek bar şulay qaldırığız -->',
'upload-success-subj'        => 'Yökläw äybät ütte',

'license'            => 'Litsenziäse:',
'license-header'     => 'Litsenziäse',
'nolicense'          => 'Yuq',
'license-nopreview'  => '(Aldan qaraw mömkin tügel)',
'upload_source_file' => '(sezneñ sanaqtağı fayl)',

# Special:ListFiles
'imgfile'               => 'fayl',
'listfiles'             => 'Sürätlär isemlege',
'listfiles_date'        => 'Waqıt',
'listfiles_name'        => 'At',
'listfiles_user'        => 'Qullanuçı',
'listfiles_size'        => 'Ülçäm',
'listfiles_description' => 'Taswir',
'listfiles_count'       => 'Yuramalar',

# File description page
'file-anchor-link'          => 'Fayl',
'filehist'                  => 'Faylnıñ tarixı',
'filehist-help'             => 'Datağa/säğätkä, şul waqıtta bitneñ nindi bulğanlığın kürü öçen basığız.',
'filehist-deleteall'        => 'Barısın da yuq it',
'filehist-deleteone'        => 'beterü',
'filehist-revert'           => 'qaytaru',
'filehist-current'          => 'xäzerge',
'filehist-datetime'         => 'Data/waqıt',
'filehist-thumb'            => 'Eskiz',
'filehist-thumbtext'        => '$1 könne bulğan versiäneñ eskizı',
'filehist-nothumb'          => 'Miniatyurası yuq',
'filehist-user'             => 'Qullanuçı',
'filehist-dimensions'       => 'Zurlıq',
'filehist-filesize'         => 'Faylnıñ zurlığı',
'filehist-comment'          => 'İskärmä',
'filehist-missing'          => 'Fayl tabılmadı',
'imagelinks'                => 'Faylğa sıltamalar',
'linkstoimage'              => 'Bu faylğa älege {{PLURAL:$1|bit|$1 bit}} sıltıy:',
'nolinkstoimage'            => 'Bu faylğa sıltağan bitlär yuq.',
'duplicatesoffile'          => '{{PLURAL:$1|Älege $1 fayl }} astağı faylnıñ küçerelmäse bulıp tora ([[Special:FileDuplicateSearch/$2|tulıraq]]):',
'sharedupload'              => "Bu fayl $1'dan häm başqa proyektlarda qullanırğa mömkin.",
'sharedupload-desc-here'    => "Bu fayl $1'dan häm başqa proyektlarda qullanırğa mömkin. Fayl turında [$2 mäğlümat ] asta birelgän.",
'filepage-nofile'           => 'Mondıy isemle fayl yuq.',
'filepage-nofile-link'      => 'Mondıy isemle fayl  yuq. Sez anı [$1 yökli alasız].',
'uploadnewversion-linktext' => 'Bu faylnıñ yaña yuramasın yökläw',
'shared-repo-from'          => '$1 dän',

# File reversion
'filerevert'         => '$1 yuramasına kire qaytu',
'filerevert-legend'  => 'Faylnıñ iske yuramasın kire qaytaru',
'filerevert-comment' => 'Säbäp:',
'filerevert-submit'  => 'Kire qaytaru',

# File deletion
'filedelete'                  => '$1 —  beterü',
'filedelete-legend'           => 'Faylnı beterü',
'filedelete-comment'          => 'Säbäp:',
'filedelete-submit'           => 'Beterü',
'filedelete-reason-otherlist' => 'Başqa säbäp',
'filedelete-reason-dropdown'  => '*Beterergä tüp säbäp
** Qälämxaqq buzılışı
** Qabatlanğan birem',

# MIME search
'mimesearch' => 'MIME ezläw',
'download'   => 'yökläw',

# Unwatched pages
'unwatchedpages' => 'Berkemdä küzätmäwçe  bitlär',

# List redirects
'listredirects' => 'Yünältülär isemlege',

# Unused templates
'unusedtemplates' => 'Qullanılmağan ürnäklär',

# Random page
'randompage' => 'Oçraqlı bit',

# Random redirect
'randomredirect' => 'Oçraqlı bitkä küçü',

# Statistics
'statistics'                   => 'Xisapnamä',
'statistics-header-pages'      => 'Bitlär xisapnamäse',
'statistics-header-edits'      => 'Üzgärtülär xisapnamäse',
'statistics-header-views'      => 'Qarawlar xisapnamäse',
'statistics-header-users'      => 'Qullanuçılar buyınça xisapnamä',
'statistics-header-hooks'      => 'Başqa xisapnamälär',
'statistics-articles'          => 'Mäqälälär sanı',
'statistics-pages'             => 'Bitlär sanı',
'statistics-pages-desc'        => 'Barlıq wiki, bäxäs, küçerü häm başqa bitlärne dä istä totıp.',
'statistics-files'             => 'Yöklänelgän fayllar',
'statistics-edits'             => '{{grammar:genitive|{{SITENAME}}}} proyektı açılğannan birle bulğan barlıq üzgärtülär isäbe',
'statistics-edits-average'     => 'Ber bitkä urtaça üzgärtülär isäbe',
'statistics-views-total'       => 'Barlıq qaralğan bitlär',
'statistics-views-peredit'     => 'Üzgärtülärgä qaraw',
'statistics-users'             => 'Terkälgän [[Special:ListUsers|qullanuçılar]]',
'statistics-users-active'      => 'Aktiv qullanuçılar',
'statistics-users-active-desc' => '{{PLURAL:$1|$1 kön }} öçendä nindi dä bulsa üzgärtülär kertkän qullanuçılar',
'statistics-mostpopular'       => 'İñ küp qaraluçı bitlär',

'disambiguations' => 'Küp mäğnäle süzlär turında bitlär',

'doubleredirects' => 'İkelätä yünältülär',

'brokenredirects'        => 'Bäyläneşsez yünältülär',
'brokenredirectstext'    => 'Bu yünältülär bulmağan bitlärgä sıltıylar:',
'brokenredirects-edit'   => 'üzgärtü',
'brokenredirects-delete' => 'beterü',

'withoutinterwiki'        => 'Tellärara sıltamasız bitlär',
'withoutinterwiki-legend' => 'Östälmä',
'withoutinterwiki-submit' => 'Kürsätü',

'fewestrevisions' => 'Az üzgärtülär belän bitlär',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|bayt}}',
'ncategories'             => '$1 {{PLURAL:$1|törkem}}',
'nlinks'                  => '$1 {{PLURAL:$1|sıltama}}',
'nmembers'                => '$1 {{PLURAL:$1|äğza}}',
'lonelypages'             => 'Üksez bitlär',
'uncategorizedpages'      => 'Törkemlänmägän bitlär',
'uncategorizedcategories' => 'Törkemlänmägän törkemnär',
'uncategorizedimages'     => 'Törkemlänmägän sürätlär',
'uncategorizedtemplates'  => 'Törkemlänmägän ürnäklär',
'unusedcategories'        => 'Qullanmağan törkemnär',
'unusedimages'            => 'Qullanmağan sürätlär',
'popularpages'            => 'Populyar bitlär',
'wantedcategories'        => 'Zarur törkemnär',
'wantedpages'             => 'Zarur bitlär',
'wantedfiles'             => 'Kiräkle fayllar',
'wantedtemplates'         => 'Kiräkle ürnäklär',
'mostlinked'              => 'Küp üzenä sıltamalı bitlär',
'mostlinkedcategories'    => 'Küp üzenä sıltamalı törkemnär',
'mostlinkedtemplates'     => 'İñ küp qullanılğan ürnäklär',
'mostcategories'          => 'Küp törkemlärgä kertelgän bitlär',
'mostimages'              => 'İñ qullanğan sürätlär',
'mostrevisions'           => 'Küp üzgärtülär belän bitlär',
'prefixindex'             => 'Barlıq alquşımça belän bitlär',
'shortpages'              => 'Qısqa bitlär',
'longpages'               => 'Ozın bitlär',
'deadendpages'            => 'Tupik bitläre',
'protectedpages'          => 'Yaqlanğan bitlär',
'protectedtitles'         => 'Tıyılğan isemnär',
'listusers'               => 'Qullanuçılar isemlege',
'newpages'                => 'Yaña bitlär',
'newpages-username'       => 'Qullanuçı:',
'ancientpages'            => 'İñ iske bitlär',
'move'                    => 'Küçerü',
'movethispage'            => 'Bu bitne küçerü',
'notargettitle'           => 'Maqsatsız',
'nopagetitle'             => 'Mondıy bit yuq',
'nopagetext'              => 'Kürsätelgän bit yuq.',
'pager-newer-n'           => '{{PLURAL:$1|1 yañaraq|$1 yañaraq}}',
'pager-older-n'           => '{{PLURAL:$1|1 iskeräk|$1 iskeräk}}',
'suppress'                => 'Yäşerü',

# Book sources
'booksources'               => 'Kitap çığanaqları',
'booksources-search-legend' => 'Kitap çığanaqlarını ezläw',
'booksources-go'            => 'Başqaru',
'booksources-text'          => 'Älege bittä kürsätelgän sıltamalar yärämendä sezneñ qızıqsındırğan kitap buyınça östämä mäğlümatlar tabarğa mömkin. Bolar internet-kibetlär häm kitapxanä cıyıntığında ezläwçe sistemalar.',
'booksources-invalid-isbn'  => 'Birelgän ISBN sanı bälki xataldır. Zinhar, birelgän sannarnı yañadan tikşeregez.',

# Special:Log
'specialloguserlabel'  => 'Qullanuçı:',
'speciallogtitlelabel' => 'Başlam:',
'log'                  => 'Köndäleklär',
'all-logs-page'        => 'Barlıq köndäleklär',
'alllogstext'          => '{{SITENAME}} säxifäseneñ ğomumi köndälekläre isemlege.
Sez näticälärne köndälek töre, qullanuçı iseme (xäref zurlığın istä totığız) yäki quzzallağan bit (şulay uq xäref zurlığın istä totığız) buyınça tärtipkä salırğa mömkin.',
'logempty'             => 'Kiräkle yazmalar köndälektä yuq.',

# Special:AllPages
'allpages'       => 'Barlıq bitlär',
'alphaindexline' => '$1 bitennän $2 bitenä qädär',
'nextpage'       => 'Aldağı bit ($1)',
'prevpage'       => 'Aldağı bit ($1)',
'allpagesfrom'   => 'Moña başlanuçı bitlärne çığaru:',
'allpagesto'     => 'Monda çığarunı tuqtatu:',
'allarticles'    => 'Barlıq bitlär',
'allpagesprev'   => 'Elekke',
'allpagesnext'   => 'Kiläse',
'allpagessubmit' => 'Başqaru',
'allpagesprefix' => 'Alquşımçalı bitlärne kürsätü:',

# Special:Categories
'categories'                    => 'Törkemnär',
'categoriespagetext'            => '{{PLURAL:$1|Älege törkem üz öçenä|Älege törkemnär  üz öçenä}}   bitlärne häm media-fayllarnı ala.
Asta [[Special:UnusedCategories|qullanılmağan törkemnär]] kärsätelgän.
Şulay uq  [[Special:WantedCategories|kiräkle törkemnär isemlegendä]] qarağız.',
'special-categories-sort-count' => 'isäp buyınça tärtipläw',
'special-categories-sort-abc'   => 'älifba buyınça tärtipläw',

# Special:DeletedContributions
'sp-deletedcontributions-contribs' => 'kertem',

# Special:LinkSearch
'linksearch'     => 'Tışqı sıltamalar',
'linksearch-pat' => 'Ezläw öçen ürnäk:',
'linksearch-ns'  => 'İsemnär mäydanı:',
'linksearch-ok'  => 'Ezläw',

# Special:ListUsers
'listusers-submit'   => 'Kürsätü',
'listusers-noresult' => 'Qullanuçılarnı tabılmadı.',
'listusers-blocked'  => '(tıyılğan)',

# Special:ActiveUsers
'activeusers'            => 'Aktiv qullanuçılar isemlege',
'activeusers-hidebots'   => 'Botlarnı yäşer',
'activeusers-hidesysops' => 'İdaräçelärne yäşer',
'activeusers-noresult'   => 'Qullanuçılar tabılmadı.',

# Special:Log/newusers
'newuserlogpage'          => 'Qullanuçılarnı terkäw köndälege',
'newuserlogpagetext'      => 'Yaña terkälgän qullanuçılar isemlege',
'newuserlog-byemail'      => 'sersüz elektron poçta aşa cibärelde',
'newuserlog-create-entry' => 'Yaña qullanuçı',

# Special:ListGroupRights
'listgrouprights'          => 'Qullanuçı törkemnäreneñ xoquqları',
'listgrouprights-group'    => 'Törkem',
'listgrouprights-rights'   => 'Xoquqlar',
'listgrouprights-helppage' => 'Help:Törkemnärneñ xoquqları',
'listgrouprights-members'  => '(törkem isemlege)',

# E-mail user
'emailuser'       => 'Bu qullanuçığa xat',
'emailpage'       => 'Qullanuçığa xat cibärü',
'defemailsubject' => '{{SITENAME}}: xat',
'noemailtitle'    => 'Elektron poçta adresı yuq',
'emailfrom'       => 'Kemnän:',
'emailto'         => 'Kemgä:',
'emailsubject'    => 'Tema:',
'emailmessage'    => 'Xäbär:',
'emailsend'       => 'Cibärü',
'emailccme'       => 'Miña xäbärneñ küçermäsene cibärelsen.',
'emailccsubject'  => '$1 öçen xäbäregezneñ küçermäse: $2',
'emailsent'       => 'Xat cibärelgän',
'emailsenttext'   => 'E-mail xatığız ciberelde.',

# Watchlist
'watchlist'         => 'Küzätü isemlegem',
'mywatchlist'       => 'Küzätü isemlegem',
'nowatchlist'       => 'Küzätü isemlegegezdä bitlär yuq.',
'watchnologin'      => 'Kermädegez',
'watchnologintext'  => 'Küzätü isemlegegezne üzgärtü öçen, sez [[Special:UserLogin|kerergä]] tieşsez.',
'addedwatch'        => 'Küzätü isemlegenä östägän',
'addedwatchtext'    => "\"[[:\$1]]\" bite [[Special:Watchlist|küzätü isemlegegezgä]] östälde.
Bu bittä häm anıñ bäxäslegendä barlıq bulaçaq üzgärtülär şunda kürsäteler, häm, [[Special:RecentChanges|soñğı üzgärtülär]] isemlegendä bu bitne ciñelräk tabu öçen, ul '''qalın mäten''' belän kürsäteler.",
'removedwatch'      => 'Küzätü isemlegennän beterelgän',
'removedwatchtext'  => '«[[:$1]]» bite [[Special:Watchlist|sezneñ küzätü isemlegennän]] beterelde.',
'watch'             => 'Küzätü',
'watchthispage'     => 'Bu bitne küzätü',
'unwatch'           => 'Küzätmäw',
'notanarticle'      => 'Mäqälä tügel',
'watchlist-details' => 'Küzätü isemlegegezdä, bäxäs bitlären sanamıyça, {{PLURAL:$1|$1 bit|$1 bit}} bar.',
'wlshowlast'        => 'Bayağı $1 säğät $2 kön eçendä yäki $3nı kürsät',
'watchlist-options' => 'Küzätü isemlege köyläwläre',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Küzätü isemlegemä östäwe…',
'unwatching' => 'Küzätü isemlegemnän çığaruı…',

'enotif_newpagetext'           => 'Bu yaña bit.',
'enotif_impersonal_salutation' => '{{SITENAME}} qullanuçı',
'changed'                      => 'üzgärtelde',
'created'                      => 'tözergän',
'enotif_subject'               => '{{SITENAME}} proyektınıñ $PAGETITLE bite $PAGEEDITOR tarafınnan $CHANGEDORCREATED',
'enotif_lastvisited'           => 'Soñğı kerüegezdän soñ bulğan barlıq üzgärtülärne kürer öçen, bu sıltama aşa uzığız: $1',
'enotif_body'                  => 'Xörmätle $WATCHINGUSERNAME,

«{{SITENAME}}» proyektınıñ «$PAGETITLE» bite  $PAGEEDITOR  tarafınnan  $PAGEEDITDATE  könne  $CHANGEDORCREATED. Bitne qarar öçen $PAGETITLE_URL  buyınça uzığız.

$NEWPAGE

Üzgärtüneñ qısqa eçtälege: $PAGESUMMARY $PAGEMINOREDIT

Üzgärtüçegä yazu:
el. poçta $PAGEEDITOR_EMAIL
wiki $PAGEEDITOR_WIKI

Bu bitkä kermäsägez, anıñ başqa üzgärtüläre turında xat cibärelmäyäçäk. Şulay uq sez küzätü isemlegegezdä bulğan bitlär öçen xäbär birü flağın alıp quya alasız.

             {{SITENAME}} xäbär birü sisteması

--
Küzätü isemlege köyläwlären üzgärtü:
{{fullurl:{{#special:Watchlist}}/edit}}

Bitne sezneñ küzätü isemlegezdän beterü:
$UNWATCHURL

Elemtä häm yärdäm:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Bitne beterü',
'confirm'                => 'Raslaw',
'excontent'              => 'eçtälek: «$1»',
'excontentauthor'        => 'eçtälege: "$1" (berdänber üzgärtüçe "[[Special:Contributions/$2|$2]]" ide)',
'exblank'                => 'bit buş ide',
'delete-confirm'         => '«$1» beterü',
'delete-legend'          => 'Beterü',
'historywarning'         => "'''Kisätü''': sez beterergä telägän bittä üzgärtü tarixı bar, ul $1dän {{PLURAL:$1|yuramalar}}:",
'confirmdeletetext'      => 'Sez bu bitneñ (yäki räsemneñ) tulısınça beterelüen soradığız.
Zinhar, monı çınnan da eşlärgä telägänegezne, monıñ näticälären añlağanığıznı häm [[{{MediaWiki:Policy-url}}]] bülegendäge qäğeydälär buyınça eşlägänegezne raslağız.',
'actioncomplete'         => 'Ğämäl başqarğan',
'deletedtext'            => '«<nowiki>$1</nowiki>» beterelgän inde.<br />
Soñğı beterelgän bitlärne kürer öçen, $2 qarağız.',
'deletedarticle'         => '«[[$1]]» beterelde',
'dellogpage'             => 'Beterü köndälege',
'deletionlog'            => 'beterü köndälege',
'reverted'               => 'Aldağı yuramanı qaytart',
'deletecomment'          => 'Säbäp:',
'deleteotherreason'      => 'Başqa/östämä säbäp:',
'deletereasonotherlist'  => 'Başqa säbäp',
'deletereason-dropdown'  => '* Beterüneñ säbäpläre
** vandallıq
** avtor sorawı buyınça
** avtor xoquqların bozu',
'delete-edit-reasonlist' => 'Säbäplär isemlegen üzgärtü',

# Rollback
'rollback_short' => 'Kire qaytaru',
'rollbacklink'   => 'kire qaytaru',
'editcomment'    => "Üzgärtü öçen taswir: \"''\$1''\".",
'revertpage'     => '[[Special:Contributions/$2|$2]] üzgärtüläre ([[User talk:$2|bäxäs]])  [[User:$1|$1]] yuramasına kire qaytarıldı',

# Protect
'protectlogpage'              => 'Yaqlanu köndälege',
'protectedarticle'            => '«[[$1]]» yaqlanğan',
'modifiedarticleprotection'   => '"[[$1]]" bite öçen yaqlaw däräcäse üzgärtelde',
'unprotectedarticle'          => '«[[$1]]» inde yaqlanmağan',
'movedarticleprotection'      => 'yaqlaw köylänmälären «[[$2]]» bitennän «[[$1]]» bitenä küçerde',
'protect-title'               => '«$1» öçen yaqlaw däräcäsen bilgeläw',
'prot_1movedto2'              => '«[[$1]]» bite «[[$2]]» bitenä küçerelde',
'protect-backlink'            => '← $1',
'protect-legend'              => 'Bitne yaqlaw turında raslağız',
'protectcomment'              => 'Säbäp:',
'protectexpiry'               => 'Betä:',
'protect_expiry_invalid'      => 'Yaqlaw betü waqıtı döres tügel.',
'protect_expiry_old'          => 'Yaqlaw betü köne uzğan köngä quyılğan.',
'protect-unchain-permissions' => 'Östämä yaqlaw çaraların açu',
'protect-text'                => "Biredä sez '''<nowiki>$1</nowiki>''' bite öçen yaqlaw däräcäsene qarıy häm üzgärä alasız.",
'protect-locked-access'       => "Xisap yazmağızğa bitlärneñ yaqlaw däräcäsen üzgärtü öçen xaq citmi. '''$1''' biteneñ xäzerge köyläwläre:",
'protect-cascadeon'           => 'Bu bit yaqlanğan, çönki ul älege kaskadlı yaqlawlı {{PLURAL:$1|bitkä|bitlärgä}} kerä. Sez bu bitneñ yaqlaw däräcäsen üzgärtä alasız, ämma kaskadlı yaqlaw üzgärmäyäçäk.',
'protect-default'             => 'Yaqlawsız',
'protect-fallback'            => '«$1»neñ röxsäte kiräk',
'protect-level-autoconfirmed' => 'Yaña häm terkälmägän qullanuçılarnı qısu',
'protect-level-sysop'         => 'İdaräçelär genä',
'protect-summary-cascade'     => 'kaskadlı',
'protect-expiring'            => '$1 ütä (UTC)',
'protect-expiry-indefinite'   => 'Waqıt çiklänmägän',
'protect-cascade'             => 'Bu bitkä kergän bitlärne yaqlaw (kaskadlı yaqlaw)',
'protect-cantedit'            => 'Sez bu bitneñ yaqlaw däräcäsene üzgärä almıysız, çönki sezdä anı üzgärtergä röxsätegez yuq.',
'protect-othertime'           => 'Başqa waqıt:',
'protect-othertime-op'        => 'başqa waqıt',
'protect-otherreason-op'      => 'Başqa säbäp',
'protect-expiry-options'      => '1 säğät:1 hour,1 kön:1 day,1 atna:1 week,2 atna:2 weeks,1 ay:1 month,3 ay:3 months,6 ay:6 months,1 yıl:1 year,waqıtsız:infinite',
'restriction-type'            => 'Röxsät:',
'restriction-level'           => 'Mömkinlek däräcäse:',
'minimum-size'                => 'İñ keçkenä zurlıq',
'maximum-size'                => 'İñ yuğarı zurlıq:',
'pagesize'                    => '(bayt)',

# Restrictions (nouns)
'restriction-edit'   => 'Üzgärtü',
'restriction-move'   => 'Küçerü',
'restriction-create' => 'Tözü',
'restriction-upload' => 'Yökläw',

# Restriction levels
'restriction-level-sysop'         => 'tulı yaqlaw',
'restriction-level-autoconfirmed' => 'öleşçä yaqlaw',
'restriction-level-all'           => 'barlıq däräcälär',

# Undelete
'undelete'                  => 'Beterelgän bitlärne qaraw',
'undeletepage'              => 'Beterelgän bitlärne qaraw häm torğızu',
'viewdeletedpage'           => 'Beterelgän bitlärne qaraw',
'undelete-fieldset-title'   => 'Yuramalarnı qaytaru',
'undeletehistory'           => 'Bu bitne torğızsağız, anıñ üzgärtü tarixı da tulısınça torğızılaçaq.
Beterelüdän soñ şundıy uq isemle bit tözelgän bulsa, torğızılğan üzgärtülär yaña üzgärtülär aldına quyılaçaq.',
'undeletebtn'               => 'Torğızu',
'undeletelink'              => 'qaraw/torğızu',
'undeleteviewlink'          => 'qaraw',
'undeletereset'             => 'Taşlatu',
'undeleteinvert'            => 'Kiresen saylaw',
'undeletecomment'           => 'Säbäp:',
'undeletedarticle'          => '«[[$1]]» torğızıldı',
'undelete-search-submit'    => 'Ezläw',
'undelete-error-long'       => 'Faylnı torğızu waqıtında xatalar çıqtı:

$1',
'undelete-show-file-submit' => 'Äye',

# Namespace form on various pages
'namespace'      => 'İsemnär mäydanı:',
'invert'         => 'Kiresen saylaw',
'blanknamespace' => '(Töp)',

# Contributions
'contributions'       => 'Qullanuçınıñ kerteme',
'contributions-title' => '$1 isemle qullanuçınıñ kerteme',
'mycontris'           => 'Kertemem',
'contribsub2'         => '$1 ($2) öçen',
'uctop'               => '(axırğı)',
'month'               => 'Aydan başlap (häm elegräk):',
'year'                => 'Yıldan başlap (häm elegräk):',

'sp-contributions-newbies'     => 'Yaña xisap yazmalarınnan yasalğan kertemne genä qaraw',
'sp-contributions-newbies-sub' => 'Yaña xisap yazmaları öçen',
'sp-contributions-blocklog'    => 'tıyu köndälege',
'sp-contributions-logs'        => 'köndäleklär',
'sp-contributions-talk'        => 'bäxäs',
'sp-contributions-search'      => 'Kertemne ezläw',
'sp-contributions-username'    => 'Qullanuçınıñ IP adresı yäki iseme:',
'sp-contributions-submit'      => 'Ezlärgä',

# What links here
'whatlinkshere'            => 'Biregä närsä sıltıy',
'whatlinkshere-title'      => '$1 bitenä sıltıy torğan bitlär',
'whatlinkshere-page'       => 'Bit:',
'linkshere'                => "'''[[:$1]]''' bitkä çirattağı bitlär sıltıy:",
'nolinkshere'              => "'''[[:$1]]''' bitenä başqa bitlär sıltamıylar.",
'isredirect'               => 'yünältü bite',
'istemplate'               => 'kertülär',
'isimage'                  => 'räsem öçen sıltama',
'whatlinkshere-prev'       => '{{PLURAL:$1|aldağı|aldağı $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|çirattağı|çirattağı $1}}',
'whatlinkshere-links'      => '← sıltamalar',
'whatlinkshere-hideredirs' => 'Yünältülärne $1',
'whatlinkshere-hidetrans'  => '$1 kertü',
'whatlinkshere-hidelinks'  => '$1 sıltamalar',
'whatlinkshere-filters'    => 'Filtrlar',

# Block/unblock
'blockip'                    => 'Qullanuçını tıyu',
'blockip-title'              => 'Qullanuçını tıyu',
'blockip-legend'             => 'Qullanuçını tıyu',
'ipaddress'                  => 'IP adresı:',
'ipadressorusername'         => 'IP adresı yäki qullanuçı iseme:',
'ipbexpiry'                  => 'Betä:',
'ipbreason'                  => 'Säbäp:',
'ipbreasonotherlist'         => 'Başqa säbäp',
'ipbreason-dropdown'         => '↓ * Qısunıñ ğädättäge säbäpläre
** Yalğan mäğlümat kertü
** Bitlärneñ eçtälegen sörtü
** Tışqı saytlarğa spam-sıltamalar
** Mäğnäsez tekst/çüp östäw
** Qullanuçılarnı ezärlekläw/yanawlar
** Berniçä xisap yazması belän isäpläşmäw
** Qullanuçı isemeneñ yaraqsız buluı',
'ipbenableautoblock'         => 'Qullanuçı qullanğan IP adresların avtomatik räweştä tıyu',
'ipbsubmit'                  => 'Bu qullanuçını tıyu',
'ipbother'                   => 'Başqa waqıt:',
'ipboptions'                 => '2 säğät:2 hours,1 kön:1 day,3 kön:3 days,1 atna:1 week,2 atna:2 weeks,1 ay:1 month,3ay:3 months,6 ay:6 months,1 yıl:1 year,çiklänmägän:infinite',
'ipbotheroption'             => 'başqa',
'badipaddress'               => 'Yalğış IP adresı',
'blockipsuccesssub'          => 'Tıyu başqarılğan',
'ipb-unblock-addr'           => '$1 qullanuçısın tıyudan azat itü',
'ipb-unblock'                => 'Qullanuçı yäki IP adresı tıyudan azat itü',
'unblockip'                  => 'Qullanuçını tıyudan azat itü',
'ipusubmit'                  => 'Bu tıyunı tuqtatu',
'ipblocklist'                => 'Tıyılğan IP adresları häm qullanuçı isemnäre',
'ipblocklist-username'       => 'qullanuçı iseme yäki IP adresı:',
'ipblocklist-submit'         => 'Ezläw',
'infiniteblock'              => 'bilgele ber waqıtsız',
'blocklink'                  => 'tıyu',
'unblocklink'                => 'tıyudan azat itü',
'change-blocklink'           => 'tıyunı üzgärtü',
'contribslink'               => 'kertem',
'blocklogpage'               => 'Tıyu köndälege',
'blocklogentry'              => '[[$1]] $2 waqıtqa tıyıldı $3',
'unblocklogentry'            => '$1 qullanuçısınıñ tıyılu waqıtı bette',
'block-log-flags-nocreate'   => 'yaña xisap yazması terkäw tıyılğan',
'block-log-flags-noemail'    => 'xat cibärü tıyılğan',
'block-log-flags-hiddenname' => 'qullanuçınıñ iseme yäşerelgän',
'ipb_expiry_invalid'         => 'İskärü waqıtı xatalı.',
'ip_range_invalid'           => 'Xatalı IP arası.',
'proxyblocker'               => 'Proksi tıyu',
'proxyblocksuccess'          => 'Eşlände',
'sorbsreason'                => 'Sezneñ IP adresığız DNSBLda açıq proksi dip sanala.',

# Developer tools
'lockdb'              => 'Biremlekne yozaqlaw',
'unlockdb'            => 'Biremlek irekläw',
'lockconfirm'         => 'Äye, min mäğlümatlar bazasın çınlap ta yozaqlarğa buldım.',
'lockbtn'             => 'Mäğlümatlar bazasın yozaqlaw',
'unlockbtn'           => 'Mäğlümatlar bazasına yazu mömkinlegen qaytaru',
'lockdbsuccesssub'    => 'Mäğlümatlar bazası yozaqlandı',
'unlockdbsuccesssub'  => 'Mäğlümatlar bazası yozağı salındı',
'unlockdbsuccesstext' => 'Bu mäğlümatlar bazası yozağı salındı.',

# Move page
'move-page'                 => '$1 — isemen almaştıru',
'move-page-legend'          => 'Bitne küçerü',
'movepagetext'              => "Astağı formanı qullanu bitneñ isemen alıştırıp, anıñ barlıq tarixın yaña isemle bitkä küçerer.
İske isemle bit yaña isemle bitkä yünältü bulıp qalır.
Sez iske isemgä yünältülärne avtomatik räweştä yaña isemgä küçerä alasız.
Ägär monı eşlämäsägez, [[Special:DoubleRedirects|ikele]] häm [[Special:BrokenRedirects|özelgän yünältülärne]] tikşeregez.
Sez barlıq sıltamalarnıñ kiräkle cirgä sıltawına cawaplı.

Küzdä totığız: ägär yaña isem urınında bit bulsa inde, häm ul buş yäki yünältü tügel isä, bit '''küçerelmäyäçäk'''.
Bu şunı añlata: sez yalğışıp küçersägez, bitne qaytara alasız, ämma inde bulğan bitne beterä almıysız.

'''İğtibar!'''
Populyar bitlärne küçerü zur häm kötelmägän näticälärgä kiterä ala.
Däwam itkänçe, barlıq näticälärne añlawığıznı tağın ber qat uylağız.",
'movepagetalktext'          => "Bu bitneñ bäxäs bite dä küçereläçäk, '''bu oçraqlardan tış''':
*Andıy isemle buş bulmağan bäxäs bite bar inde, yäisä
*Sez astağı flajoqnı quymağansız.

Bu oçraqlarda sezgä bitlärne üz qulığız belän küçerergä yäki quşarğa turı kiler.",
'movearticle'               => 'Bitne küçerü:',
'movenologin'               => 'Kermädegez',
'movenotallowed'            => 'Sezdä mäqälälärne küçerü xoquqları yuq.',
'newtitle'                  => 'Yaña başlam:',
'move-watch'                => 'Bu bitne küzätü',
'movepagebtn'               => 'Bitne küçerü',
'pagemovedsub'              => 'Bit küçerelde',
'movepage-moved'            => "'''«$1» bite «$2» bitenä küçerelde'''",
'movepage-moved-redirect'   => 'Yünältü yasaldı.',
'movepage-moved-noredirect' => 'Yünältüne yasaw tıyıldı',
'articleexists'             => 'Mondıy isemle bit bar inde, yäisä mondıy isem röxsät itelmi.
Zinhar başqa isem saylağız.',
'talkexists'                => "'''Bitneñ üze küçerelde, ämma bäxäs bite küçerelmi qaldı, çönki şundıy isemle bit bar inde. Zinhar, alarnı üzegez quşığız.'''",
'movedto'                   => 'küçerelgän:',
'movetalk'                  => 'Bäyläneşle bäxäs biten küçerü',
'1movedto2'                 => '«[[$1]]» bite «[[$2]]» bitenä küçerelde',
'1movedto2_redir'           => '[[$1]] bite [[$2]] bitenä yünältü östennän küçte',
'move-redirect-suppressed'  => 'yünältü tıyıldı',
'movelogpage'               => 'Küçerü köndälege',
'movereason'                => 'Säbäp:',
'revertmove'                => 'kire qaytu',
'delete_and_move'           => 'Beterü häm isemen almaştıru',
'delete_and_move_reason'    => 'Küçerüne mömkin itär öçen beterelde',
'move-leave-redirect'       => 'Yünältü qaldırılsın',

# Export
'export'            => 'Bitlärne çığaruı',
'export-submit'     => 'Eksportlaw',
'export-addcattext' => 'Bu törkemnän bitlär östäw:',
'export-addcat'     => 'Östäw',
'export-addns'      => 'Östäw',
'export-download'   => 'Fayl bularaq saqlaw',

# Namespace 8 related
'allmessages'                   => 'Sistema xäbärläre',
'allmessagesname'               => 'İsem',
'allmessagesdefault'            => 'Töpcay yazma',
'allmessagescurrent'            => 'Eligi yazma',
'allmessagestext'               => 'Bu isemlek MediaWiki isemnär mäydanında bulğan sistema xäbärläreneñ isemlege.
Ğomumi MediaWiki lokalizatsiäsendä qatnaşırğa teläsägez, zinhar [http://www.mediawiki.org/wiki/Localisation MediaWiki Lokalizatsiäse] häm [http://translatewiki.net translatewiki.net] säxifälärne qullanığız.',
'allmessages-filter-legend'     => 'Filtr',
'allmessages-filter-unmodified' => 'Üzgärtelmägän',
'allmessages-filter-all'        => 'Barısı',
'allmessages-filter-modified'   => 'Üzgärtelgän',
'allmessages-language'          => 'Tel:',
'allmessages-filter-submit'     => 'Küçü',

# Thumbnails
'thumbnail-more'  => 'Zuraytu',
'filemissing'     => 'Fayl tabılmadı',
'thumbnail_error' => 'Keçkenä sürät tözüe xatası: $1',

# Special:Import
'import'                  => 'Bitlärne yökläw',
'import-interwiki-submit' => 'İmportlaw',
'import-upload-filename'  => 'Fayl iseme:',
'import-comment'          => 'İskärmä:',
'importstart'             => 'Bitlärne importlaw...',
'importfailed'            => 'İmportlaw xatası: <nowiki>$1</nowiki>',
'importnotext'            => 'Buş yäki tekst yuq',
'importsuccess'           => 'İmportlaw uñışlı buldı!',

# Import log
'importlogpage'             => 'Kertü köndälege',
'import-logentry-interwiki' => '«$1» — wikiara  importlaw',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Qullanuçı bitegez',
'tooltip-pt-mytalk'               => 'Bäxäs bitegez',
'tooltip-pt-preferences'          => 'Köylänmäläregez',
'tooltip-pt-watchlist'            => 'Sez küzätelgän tözätmäle bitlär isemlege',
'tooltip-pt-mycontris'            => 'Sezneñ kertemengezne isemlege',
'tooltip-pt-login'                => 'Sez xisap yazması tözi alır idegez, ämma bu mäcbüri tügel.',
'tooltip-pt-logout'               => 'Çığu',
'tooltip-ca-talk'                 => 'Bitneñ eçtälege turında bäxäs',
'tooltip-ca-edit'                 => 'Sez bu bit üzgärtä alasız. Zinhar, saqlağançı qarap alunı qullanığız.',
'tooltip-ca-addsection'           => 'Yaña bülek başlaw',
'tooltip-ca-viewsource'           => 'Bu bit üzgärtüdän yaqlanğan. Sez anıñ çığanaq tekstın ğına qarıy alasız.',
'tooltip-ca-history'              => 'Bitneñ tözätmälär isemlege',
'tooltip-ca-protect'              => 'Bu bitne yaqlaw',
'tooltip-ca-delete'               => 'Bu bitne beterü',
'tooltip-ca-move'                 => 'Bu bitne küçerü',
'tooltip-ca-watch'                => 'Bu bitne sezneñ küzätü isemlegezgä östäw',
'tooltip-ca-unwatch'              => 'Bu bitne sezneñ küzätü isemlegezdä beterü',
'tooltip-search'                  => '{{SITENAME}} eçendä ezläw',
'tooltip-search-go'               => 'Näk şundıy iseme belän bitkä küçärü',
'tooltip-search-fulltext'         => 'Bu tekst belän bitlärne tabu',
'tooltip-p-logo'                  => 'Baş bit',
'tooltip-n-mainpage'              => 'Baş bitne kerep çığu',
'tooltip-n-mainpage-description'  => 'Baş bitkä küçü',
'tooltip-n-portal'                => 'Proyekt turında, sez närsä itä alasız häm närsä qayda bula dip turında.',
'tooltip-n-currentevents'         => 'Ağımdağı waqıyğalar turında mäğlümatnı tabu',
'tooltip-n-recentchanges'         => 'Soñğı üzgärtülär isemlege',
'tooltip-n-randompage'            => 'Oçraqlı bitne qaraw',
'tooltip-n-help'                  => '«{{SITENAME}}» proyektınıñ beleşmälek',
'tooltip-t-whatlinkshere'         => 'Biregä sıltağan barlıq bitlärneñ isemlege',
'tooltip-t-recentchangeslinked'   => 'Bu bittän sıltağan bitlärdä axırğı üzgärtülär',
'tooltip-feed-rss'                => 'Bu bit öçen RSS translyatsiäse',
'tooltip-feed-atom'               => 'Bu bit öçen Atom translyatsiäse',
'tooltip-t-contributions'         => 'Qullanuçı kertemeneñ isemlegene qaraw',
'tooltip-t-emailuser'             => 'Bu qullanuçığa xat cibärü',
'tooltip-t-upload'                => 'Fayllarnı yökläw',
'tooltip-t-specialpages'          => 'Barlıq maxsus bitlär isemlege',
'tooltip-t-print'                 => 'Bu bitneñ bastıru versiäse',
'tooltip-t-permalink'             => 'Bitneñ bu yuramasına daimi sıltama',
'tooltip-ca-nstab-main'           => 'Mäqäläneñ eçtälege',
'tooltip-ca-nstab-user'           => 'Qullanuçınıñ şäxsi bite',
'tooltip-ca-nstab-media'          => 'Media-fayl',
'tooltip-ca-nstab-special'        => 'Bu maxsus bit, sez anı üzgärtü almıysız',
'tooltip-ca-nstab-project'        => 'Proyektnıñ bite',
'tooltip-ca-nstab-image'          => 'Sürätneñ bite',
'tooltip-ca-nstab-mediawiki'      => 'MediaWiki - xat bite',
'tooltip-ca-nstab-template'       => 'Ürnäk bite',
'tooltip-ca-nstab-help'           => 'Yärdäm biten qaraw',
'tooltip-ca-nstab-category'       => 'Törkem biten qaraw',
'tooltip-minoredit'               => 'Bu üzgärtüne keçe dip bilgelü',
'tooltip-save'                    => 'Üzgärtüläregezne saqlaw',
'tooltip-preview'                 => 'Sezneñ üzgärtüläregezneñ aldan qarawı, saqlawdan qädär monı qullanığız äle!',
'tooltip-diff'                    => 'Sezneñ üzgärtüläregezne kürsätü.',
'tooltip-compareselectedversions' => 'Bu bitneñ saylanğan ike yuraması arasında ayırmanı qaraw',
'tooltip-watch'                   => 'Bu bitne küzätü isemlegemä östäw',
'tooltip-recreate'                => 'Bu bitne kire qaytaru',
'tooltip-upload'                  => 'Yökläwne başlaw',
'tooltip-rollback'                => "\"Kire qaytaru\" soñğı qullanuçınıñ bu bittä yasağan '''barlıq''' üzgärtülären beterä.",
'tooltip-undo'                    => 'Bu üzgärtüne aldan qarap ütkärmäw. Şulay uq ütkärmäwneñ säbäben yazıp bula.',
'tooltip-preferences-save'        => 'Köylänmäläregezne saqlaw',
'tooltip-summary'                 => 'Qısqa isemen kertü',

# Stylesheets
'common.css' => '/*  Monda urnaştırılğan CSS başqalarında da urnaşaçaq */',

# Attribution
'anonymous'     => '{{SITENAME}} saytınıñ anonim {{PLURAL:$1|qullanuçısı|qullanuçıları}}',
'siteuser'      => '{{SITENAME}} qullanuçısı $1',
'othercontribs' => '«$1» eşenä nigezlänä.',
'others'        => 'başqalar',
'siteusers'     => '{{SITENAME}} {{PLURAL:$2|qullanuçısı|qullanuçıları}} $1',
'creditspage'   => 'Räxmätlär',

# Spam protection
'spamprotectiontitle' => 'Spam filtrı',

# Info page
'infosubtitle' => 'Bit turında',

# Skin names
'skinname-standard'    => 'Klassik',
'skinname-nostalgia'   => 'İskä alu',
'skinname-cologneblue' => 'Zäñgär sağış',
'skinname-monobook'    => 'Kitap',
'skinname-myskin'      => 'Üzem',
'skinname-chick'       => 'Çebi',
'skinname-simple'      => 'Ğädi',
'skinname-modern'      => 'Zamana',
'skinname-vector'      => 'Sızımlı',

# Math options
'mw_math_png'    => 'Härwaqıt PNG belän bäyläw',
'mw_math_simple' => 'Ğädi oçraqlarda HTML, yäisä PNG qullanılsın',
'mw_math_html'   => 'Mömkin bulsa HTML, yäisä PNG qullanılsın',
'mw_math_source' => 'TeX bilgeläneşendä qaldırılsın (tekstlı brauzerlar öçen)',
'mw_math_modern' => 'Bügenge brauzerlar öçen qullanılsın',
'mw_math_mathml' => 'Mömkin bulsa MathML qullanılsın (eksperimental)',

# Math errors
'math_failure'          => 'Uqıy almadım',
'math_unknown_error'    => 'belenmägän xata',
'math_unknown_function' => 'bilgesez funksiä',
'math_lexing_error'     => 'leksik xata',
'math_syntax_error'     => 'sintaksik xata',

# Patrolling
'markaspatrolledtext'   => 'Bu mäqäläne tikşerelgän dip tamğalaw',
'markedaspatrolled'     => 'Tikşerelgän dip tamğalandı',
'markedaspatrolledtext' => 'Saylanğan [[:$1]] mäqäläseneñ älege yuraması tikşerelgän dip tamğalandı.',

# Patrol log
'patrol-log-page'      => 'Tikşerü köndälege',
'patrol-log-header'    => 'Bu tikşerelgän bitlärneñ köndälege.',
'patrol-log-line'      => '$2 $3 bitennän $1nı tikşerde',
'patrol-log-auto'      => '(avtomatik räweştä)',
'patrol-log-diff'      => '$1 yurama',
'log-show-hide-patrol' => '$1 tikşerü köndälege',

# Image deletion
'deletedrevision'       => '$1 biteneñ iske yuraması beterelde',
'filedeleteerror-short' => 'Faylnı beterü xatası: $1',
'filedeleteerror-long'  => 'Faylnı beterü waqıtında xatalar çıqtı:

$1',
'filedelete-missing'    => '«$1» isemle faylnı beterergä mömkin tügel, çönki ul yuq.',

# Browsing diffs
'previousdiff' => '← Aldağı üzgärtü',
'nextdiff'     => 'Çirattağı üzgärtü →',

# Media information
'imagemaxsize'         => "Räsemneñ zurlığına çikläwlär:<br />''(taswirlaw bite öçen)''",
'thumbsize'            => 'Räsemneñ keçeräytelgän yuraması öçen:',
'widthheight'          => '$1 × $2',
'widthheightpage'      => '$1 × $2, $3{{PLURAL:$1|bit|bitlär}}',
'file-info'            => '(fayl zurlığı: $1, MIME-tip: $2)',
'file-info-size'       => '($1 × $2 noqta, faylnıñ zurlığı: $3, MIME tibı: $4)',
'file-nohires'         => '<small>Yuğarı açıqlıq belän yurama yuq.</small>',
'svg-long-desc'        => '(SVG faylı, şartlı $1 × $2 noqta, faylnıñ zurlığı: $3)',
'show-big-image'       => 'Tulı açıqlıq',
'show-big-image-thumb' => '<small>Aldan qaraw zurlığı: $1 × $2 noqta</small>',

# Special:NewFiles
'newimages'        => 'Yaña sürätlär cıyılması',
'newimages-legend' => 'Filtr',
'showhidebots'     => '($1 bot)',
'ilsubmit'         => 'Ezläw',
'bydate'           => 'waqıt buyınça',

# Bad image list
'bad_image_list' => 'Kiläçäk räweş kiräk:

İsemlek kisäkläre genä (* simvolınnan başlanuçı yullar) sanalırlar.
Yulnıñ berençe sıltaması quyma öçen tıyılğan räsemgä sıltama bulırğa tieş.
Şul uq yulnıñ kiläçäk sıltamaları çığarmalar, räsemgä tıyılmağan bitläre, sanalırlar.',

# Metadata
'metadata'          => 'Meta mäğlümatlar',
'metadata-help'     => 'Bu faylda ğädättä sanlı kamera yäki skaner tarafınnan östälgän mäğlümat bar. Ägär bu fayl tözü waqıtınnan soñ üzgärtelgän bulsa, anıñ qayber parametrları döres bulmasqa mömkin.',
'metadata-expand'   => 'Östämä mäğlümatlarnı kürsätü',
'metadata-collapse' => 'Östämä mäğlümatlarnı yäşerü',
'metadata-fields'   => 'Bu isemlekkä kergän metabirelmälär qırları räsem bitendä kürsäteler, qalğannarı isä kileşü buyınça yäşereler.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'               => 'Kiñlek',
'exif-imagelength'              => 'Bieklek',
'exif-imagedescription'         => 'Räsemneñ iseme',
'exif-make'                     => 'Kameranıñ citeşterüçese',
'exif-model'                    => 'Kameranıñ töre',
'exif-software'                 => "Programmalı tä'min iteleş",
'exif-artist'                   => 'Avtor',
'exif-copyright'                => 'Avtor xoquqları xucası',
'exif-exifversion'              => 'Exif versiäse',
'exif-flashpixversion'          => "FlashPix yuramasın tä'min itü",
'exif-colorspace'               => 'Töslär tirälege',
'exif-componentsconfiguration'  => 'Töslär tözeleşeneñ konfiguratsiäse',
'exif-compressedbitsperpixel'   => 'Qısıludan soñ tösneñ tiränlege',
'exif-pixelydimension'          => 'Räsemneñ tulı bieklege',
'exif-pixelxdimension'          => 'Räsemneñ tulı kiñlege',
'exif-makernote'                => 'Citeşterüçe turında östämä mäğlümatlar',
'exif-usercomment'              => 'Östämä cawap',
'exif-relatedsoundfile'         => 'Tawış faylı cawabı',
'exif-datetimeoriginal'         => 'Çın waqıtı',
'exif-datetimedigitized'        => 'Sanlaştıru waqıtı',
'exif-subsectime'               => 'Faylnı üzgärtüneñ öleşle sekund waqıtı',
'exif-subsectimeoriginal'       => 'Çın yasalu waqıtınıñ öleş sekundı',
'exif-subsectimedigitized'      => 'Sanlaştıru waqıtınıñ öleş sekundı',
'exif-exposuretime'             => 'Ekspozitsiä waqıtı',
'exif-exposuretime-format'      => '$1 s ($2)',
'exif-fnumber'                  => 'Diafragmanıñ sanı',
'exif-fnumber-format'           => 'f/$1',
'exif-exposureprogram'          => 'Ekspozitsiä programması',
'exif-spectralsensitivity'      => 'Spektral sizüçänlek',
'exif-isospeedratings'          => 'ISO yaqtılıq sizüçänlege',
'exif-oecf'                     => 'OECF (optoelektrik küçerü koeffitsientı)',
'exif-shutterspeedvalue'        => 'Saqlaw',
'exif-aperturevalue'            => 'Diafragma',
'exif-brightnessvalue'          => 'Yaqtılıq',
'exif-exposurebiasvalue'        => 'Ekspozitsiä kompensatsiäse',
'exif-maxaperturevalue'         => 'Diafragmanıñ minimal sanı',
'exif-subjectdistance'          => 'Cisemgä qädär yıraqlıq',
'exif-meteringmode'             => 'Ekspozitsiäne ülçäw rejimı',
'exif-lightsource'              => 'Yaqtılıq çığanağı',
'exif-flash'                    => 'Yaqtılıq statusı',
'exif-focallength'              => 'Foqus yıraqlığı',
'exif-focallength-format'       => '$1 mm',
'exif-subjectarea'              => 'Töşerü cisemeneñ urnaşuı häm mäydanı',
'exif-flashenergy'              => 'Yaqtılıq energiäse',
'exif-spatialfrequencyresponse' => 'Tirälek tırışı',
'exif-focalplanexresolution'    => 'X foqäl yassılıq kiñäytelüe',
'exif-focalplaneyresolution'    => 'Y foqäl yassılıq kiñäytelüe',
'exif-focalplaneresolutionunit' => 'Foqäl yassılıq kiñäytelüen isäpläw berämlege',
'exif-subjectlocation'          => 'Cisemneñ sul yaqqa qarata torışı',
'exif-exposureindex'            => 'Ekspozitsiä sanı',
'exif-sensingmethod'            => 'Sensor töre',
'exif-filesource'               => 'Faylnıñ çığanağı',
'exif-scenetype'                => 'Tirälekneñ töre',
'exif-cfapattern'               => 'Tös filtrınıñ töre',
'exif-customrendered'           => 'Östämä üzgärtü',
'exif-exposuremode'             => 'Ekspozitsiä saylaw rejimı',
'exif-whitebalance'             => 'Aq tösneñ balansı',
'exif-digitalzoomratio'         => 'Sanlı zuraytu koeffitsientı',
'exif-focallengthin35mmfilm'    => 'Ekvivalentlı foqus yıraqlığı (35 mm tasma öçen)',
'exif-scenecapturetype'         => 'Töşerü waqıtındağı tirälek töre',
'exif-gaincontrol'              => 'Yaqtılıqnı arttıru',
'exif-contrast'                 => 'Qarañğılıq',
'exif-saturation'               => 'Törlelege',
'exif-sharpness'                => 'Açıqlığı',
'exif-devicesettingdescription' => 'Kameranıñ köyläwlär taswirlaması',
'exif-subjectdistancerange'     => 'Töşerü cisemenä qädär yıraqlıq',
'exif-imageuniqueid'            => 'Räsemneñ sanı (ID)',
'exif-gpsversionid'             => 'GPS mäğlümatı bloğınıñ versiäse',
'exif-gpslatituderef'           => 'Kiñlek indeksı',
'exif-gpslatitude'              => 'Kiñlek',
'exif-gpslongituderef'          => 'Ozınlıq indeksı',
'exif-gpslongitude'             => 'Ozınlıq',
'exif-gpsaltituderef'           => 'Bieklek indeksı',
'exif-gpsaltitude'              => 'Bieklek',
'exif-gpstimestamp'             => 'UTC buyınça waqıt',
'exif-gpssatellites'            => 'Qullanılğan iärçennär taswirlaması',
'exif-gpsstatus'                => 'Alğıçnıñ statusı häm töşerü waqıtı',
'exif-gpsmeasuremode'           => 'Urnaşunı bilgeläw ısulı',
'exif-gpsdop'                   => 'Bilgeläwneñ döreslege',
'exif-gpsspeedref'              => 'Tizlekne isäpläw berämlege',
'exif-gpsspeed'                 => 'Xäräkät tizlege',
'exif-gpsdatestamp'             => 'Data',

'exif-meteringmode-255' => 'Başqa',

'exif-lightsource-0' => 'Bilgesez',

'exif-gaincontrol-0' => 'Yuq',

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'Az ğına kütärü',
'exif-contrast-2' => 'Küp itterep kütärü',

'exif-saturation-0' => 'Normal',
'exif-saturation-1' => 'Az ğına tuyındırılu',
'exif-saturation-2' => 'Küp itterep tuyındırılu',

'exif-sharpness-0' => 'Normal',
'exif-sharpness-1' => 'Az ğına kütärü',
'exif-sharpness-2' => 'Küp itterep kütärü',

'exif-subjectdistancerange-0' => 'Bilgesez',
'exif-subjectdistancerange-1' => 'Makrotöşerü',
'exif-subjectdistancerange-2' => 'Yaqınnan töşerü',
'exif-subjectdistancerange-3' => 'Yıraqtan töşerü',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'km/säğ',
'exif-gpsspeed-m' => 'milya/säğ',

# External editor support
'edit-externally'      => 'Bu faylnı tışqı quşımtanı qullanıp üzgärtü',
'edit-externally-help' => '(tulıraq mäğlümat öçen [http://www.mediawiki.org/wiki/Manual:External_editors setup instructions] biten qarağız)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'Barlıq',
'imagelistall'     => 'barlıq',
'watchlistall2'    => 'barlıq',
'namespacesall'    => 'barlıq',
'monthsall'        => 'barlıq',
'limitall'         => 'barlıq',

# Delete conflict
'recreate' => 'Yañadan yasaw',

# action=purge
'confirm_purge_button' => 'OK',

# Multipage image navigation
'imgmultipageprev' => '← aldağı bit',
'imgmultipagenext' => 'aldağı bit →',
'imgmultigo'       => 'Küçü!',
'imgmultigoto'     => '$1 bitenä küçü',

# Table pager
'ascending_abbrev'         => 'üsü',
'descending_abbrev'        => 'kimü',
'table_pager_next'         => 'Kiläse bit',
'table_pager_prev'         => 'Aldağı bit',
'table_pager_first'        => 'Berençe bit',
'table_pager_last'         => 'Axırğı bit',
'table_pager_limit_submit' => 'Başqaru',
'table_pager_empty'        => 'Näticä yuq',

# Auto-summaries
'autoredircomment' => '[[$1]] bitenä yünältü',
'autosumm-new'     => 'Yaña bit: «$1»',

# Live preview
'livepreview-loading' => 'Yökläw...',
'livepreview-ready'   => 'Yökläw... Äzer!',

# Watchlist editor
'watchlistedit-raw-titles' => 'Yazmalar:',

# Watchlist editing tools
'watchlisttools-view' => 'Soñğı üzgärtülärne kürsätü',
'watchlisttools-edit' => 'Küzätü isemlegene qaraw häm üzgärtü',
'watchlisttools-raw'  => 'Tekst sıman üzgärtü',

# Hijri month names
'hijri-calendar-m1' => 'Möxärräm',
'hijri-calendar-m7' => 'Racäb',
'hijri-calendar-m9' => 'Ramazan',

# Special:Version
'version'                  => 'Yurama',
'version-other'            => 'Başqa',
'version-license'          => 'Litsenziä',
'version-software'         => "Urnaştırılğan programma belän tä'min iteleşne",
'version-software-product' => 'Produkt',
'version-software-version' => 'Versiä',

# Special:FilePath
'filepath'        => 'Faylğa yul',
'filepath-page'   => 'Fayl:',
'filepath-submit' => 'Küçü',

# Special:FileDuplicateSearch
'fileduplicatesearch-submit' => 'Ezläw',

# Special:SpecialPages
'specialpages'                   => 'Maxsus bitlär',
'specialpages-note'              => '----
* Ğädi maxsus bitlär.
* <strong class="mw-specialpagerestricted">Çiklänelgän ğädi maxsus bitlär</strong>',
'specialpages-group-maintenance' => 'Texnik qaraw xisapnamäse',
'specialpages-group-other'       => 'Başqa maxsus bitlär',
'specialpages-group-login'       => 'Kerü / terkälü',
'specialpages-group-changes'     => 'Soñğı üzgärtülär',
'specialpages-group-media'       => 'Yökläw häm media-fayllar xisapnamäse',
'specialpages-group-users'       => 'Qullanuçılar häm alarnıñ xoquqları',
'specialpages-group-highuse'     => 'Yış qullanıluçı bitlär',
'specialpages-group-pages'       => 'Bitlär isemlege',
'specialpages-group-pagetools'   => 'Bit öçen cihazlar',
'specialpages-group-wiki'        => 'Wiki-mäğlümat häm cihazlar',
'specialpages-group-redirects'   => 'Küçerelüçe maxsus bitlär',
'specialpages-group-spam'        => 'Spamğa qarşı qorallar',

# Special:BlankPage
'blankpage'              => 'Buş bit',
'intentionallyblankpage' => 'Bu bit maxsus buş qaldırılğan',

# Special:Tags
'tag-filter-submit' => 'Filtrlaw',
'tags-edit'         => 'üzgärtü',

# Special:ComparePages
'compare-submit' => 'Çağıştır',

# Database error messages
'dberr-header'   => 'Bu wiki awırlıq kiçerä',
'dberr-problems' => 'Ğafu itegez! Saytta texnik qıyınlıqlar çıqtı.',

# HTML forms
'htmlform-submit'              => 'Cibärü',
'htmlform-reset'               => 'Üzgärtülärne kire qaytaru',
'htmlform-selectorother-other' => 'Başqa',

);
