<?php
/**
 * Kazakh (Qazaqşa)
 *
 * @package MediaWiki
 * @subpackage Language
 *
 */

$fallback = 'kk-kz';

$separatorTransformTable = array(
	',' => "\xc2\xa0",
	'.' => ',',
);

$extraUserToggles = array(
	'nolangconversion'
);

$fallback8bitEncoding = 'windows-1254';

$linkPrefixExtension = true;

$namespaceNames = array(
	NS_MEDIA            => 'Taspa',
	NS_SPECIAL          => 'Arnaýı',
	# NS_MAIN	            => '',
	NS_TALK	            => 'Talqılaw',
	NS_USER             => 'Qatıswşı',
	NS_USER_TALK        => 'Qatıswşı_talqılawı',
	# NS_PROJECT set by $wgMetaNamespace
  NS_PROJECT_TALK     => '$1_talqılawı',
	NS_IMAGE            => 'Swret',
	NS_IMAGE_TALK       => 'Swret_talqılawı',
	NS_MEDIAWIKI        => 'MedïaWïkï',
	NS_MEDIAWIKI_TALK   => 'MedïaWïkï_talqılawı',
	NS_TEMPLATE         => 'Ülgi',
	NS_TEMPLATE_TALK    => 'Ülgi_talqılawı',
	NS_HELP             => 'Anıqtama',
	NS_HELP_TALK        => 'Anıqtama_talqılawı',
	NS_CATEGORY         => 'Sanat',
	NS_CATEGORY_TALK    => 'Sanat_talqılawı'
);

$namespaceAliases = array(
	# Aliases to kk-kz namespaces
	'Таспа'               => NS_MEDIA,
	'Арнайы'              => NS_SPECIAL,
	'Талқылау'            => NS_TALK,
	'Қатысушы'            => NS_USER,
	'Қатысушы_талқылауы'  => NS_USER_TALK,
	'$1_талқылауы'        => NS_PROJECT_TALK,
	'Сурет'               => NS_IMAGE,
	'Сурет_талқылауы'     => NS_IMAGE_TALK,
	'МедиаУики'           => NS_MEDIAWIKI,
	'МедиаУики_талқылауы' => NS_MEDIAWIKI_TALK,
	'Үлгі'                => NS_TEMPLATE,
	'Үлгі_талқылауы'      => NS_TEMPLATE_TALK,
	'Анықтама'            => NS_HELP,
	'Анықтама_талқылауы'  => NS_HELP_TALK,
	'Санат'               => NS_CATEGORY,
	'Санат_талқылауы'     => NS_CATEGORY_TALK,
	# Aliases to kk-cn namespaces
	'تاسپا'              => NS_MEDIA,
	'ارنايى'              => NS_SPECIAL,
	'تالقىلاۋ'            => NS_TALK,
	'قاتىسۋشى'          => NS_USER,
	'قاتىسۋشى_تالقىلاۋى'=> NS_USER_TALK,
	'$1_تالقىلاۋى'        => NS_PROJECT_TALK,
	'سۋرەت'              => NS_IMAGE,
	'سۋرەت_تالقىلاۋى'    => NS_IMAGE_TALK,
	'مەدياۋيكي'           => NS_MEDIAWIKI,
	'مەدياۋيكي_تالقىلاۋى' => NS_MEDIAWIKI_TALK,
	'ٴۇلگٴى'              => NS_TEMPLATE,
	'ٴۇلگٴى_تالقىلاۋى'    => NS_TEMPLATE_TALK,
	'انىقتاما'            => NS_HELP,
	'انىقتاما_تالقىلاۋى'  => NS_HELP_TALK,
	'سانات'              => NS_CATEGORY,
	'سانات_تالقىلاۋى'    => NS_CATEGORY_TALK,
);

$quickbarSettings = array(
	'Eşqandaý', 'Solğa bekitilgen', 'Oñğa bekitilgen', 'Solğa qalqığan', 'Oñğa qalqığan'
);

$skinNames = array(
	'standard'    => 'Dağdılı',
	'nostalgia'   => 'Añsaw',
	'cologneblue' => 'Köln zeñgirligi',
	'davinci'     => 'Da Vïnçï',
	'mono'        => 'Dara',
	'monobook'    => 'Dara kitap',
	'myskin'      => 'Öz mänerim',
	'chick'       => 'Balapan',
	'simple'      => 'Kädimgi'
);

$defaultDateFormat = 'ymd';

$dateFormats = array(
	'mdy time' => 'H:i',
	'mdy date' => 'xg j, Y',
	'mdy both' => 'H:i, xg j, Y',

	'dmy time' => 'H:i',
	'dmy date' => 'j F, Y',
	'dmy both' => 'H:i, j F, Y',

	'ymd time' => 'H:i',
	'ymd date' => 'Y" j." xg j',
	'ymd both' => 'H:i, Y" j." xg j',

	'ISO 8601 time' => 'xnH:xni:xns',
	'ISO 8601 date' => 'xnY-xnm-xnd',
	'ISO 8601 both' => 'xnY-xnm-xnd"T"xnH:xni:xns',
);

#-------------------------------------------------------------------
# Default messages
#-------------------------------------------------------------------

$messages = array(
# User preference toggles
'tog-underline'               => 'Siltemeni astınan sız:',
'tog-highlightbroken'         => 'Joqtalğan siltemelerdi <a href="" class="new">bılaý</a> pişimde (basqaşa: bılaý <a href="" class="internal">?</a> sïyaqtı).',
'tog-justify'                 => 'Ejelerdi eni boýınşa twralaw',
'tog-hideminor'               => 'Jwıqtağı özgeristerde şağın tüzetwdi jasır',
'tog-extendwatchlist'         => 'Baqılaw tizimdi ulğaýt (barlıq jaramdı özgeristerdi körset)',
'tog-usenewrc'                => 'Keñeýtilgen Jwıqtağı özgerister (JavaScript)',
'tog-numberheadings'          => 'Bölim taqırıptarın özdik türde nomirle',
'tog-showtoolbar'             => 'Öñdew qwraldar jolağın körset (JavaScript)',
'tog-editondblclick'          => 'Qos nuqımdap öñdew (JavaScript)',
'tog-editsection'             => 'Bölimderdi [öñdew] siltemesimen öñdewin endir',
'tog-editsectiononrightclick' => 'Bölim atawın oñ jaq nuqwmen<br />öñdewin endir (JavaScript)',
'tog-showtoc'                 => 'Mazmunın körset (3-ten artıq bölimi barılarğa)',
'tog-rememberpassword'        => 'Kirgenimdi bul komp′ywterde umıtpa',
'tog-editwidth'               => 'Öñdew awmağı tolıq enimen',
'tog-watchcreations'          => 'Men bastağan betterdi baqılaw tizimime qos',
'tog-watchdefault'            => 'Men öñdegen betterdi baqılaw tizimime qos',
'tog-minordefault'            => 'Barlıq tüzetwlerdi ädepkiden şağın dep belgile',
'tog-previewontop'            => 'Qarap şığwdı öñdew awmağınıñ üstine sal',
'tog-previewonfirst'          => 'Birinşi öñdegende qarap şığw',
'tog-nocache'                 => 'Bet qosalqı qaltasın öşir',
'tog-enotifwatchlistpages'    => 'Baqılanğan bet özgergende mağan xat jiber',
'tog-enotifusertalkpages'     => 'Talqılawım özgergende mağan xat jiber',
'tog-enotifminoredits'        => 'Şağın tüzetw twralı da mağan xat jiber',
'tog-enotifrevealaddr'        => 'E-poşta jaýımdı eskertw xatta aşıq körset',
'tog-shownumberswatching'     => 'Baqılap turğan qatıswşılardıñ sanın körset',
'tog-fancysig'                => 'Qam qoltañba (özdik siltemesiz;)',
'tog-externaleditor'          => 'Sırtqı öñdewişti ädepkiden qoldan',
'tog-externaldiff'            => 'Sırtqı aýırmağıştı ädepkiden qoldan',
'tog-showjumplinks'           => '«Ötip ketw» qatınaw siltemelerin endir',
'tog-uselivepreview'          => 'Twra qarap şığwdı qoldanw (JavaScript) (Sınaq türinde)',
'tog-autopatrol'              => 'Tüzetwimdi küzetke belgile',
'tog-forceeditsummary'        => 'Öñdew sïpattaması bos qalğanda mağan eskert',
'tog-watchlisthideown'        => 'Tüzetwimdi baqılaw tizimnen jasır',
'tog-watchlisthidebots'       => 'Bot tüzetwin baqılaw tizimnen jasır',
'tog-nolangconversion'        => 'Til türin awdarmaw',

'underline-always'  => 'Ärqaşan',
'underline-never'   => 'Eşqaşan',
'underline-default' => 'Şolğış boýınşa',

'skinpreview' => '(Qarap şığw)',

# Dates
'sunday'        => 'Jeksenbi',
'monday'        => 'Düýsenbi',
'tuesday'       => 'Seýsenbi',
'wednesday'     => 'Särsenbi',
'thursday'      => 'Beýsenbi',
'friday'        => 'Juma',
'saturday'      => 'Senbi',
'sun'           => 'Jek',
'mon'           => 'Düý',
'tue'           => 'Beý',
'wed'           => 'Sär',
'thu'           => 'Beý',
'fri'           => 'Jum',
'sat'           => 'Sen',
'january'       => 'qañtar',
'february'      => 'aqpan',
'march'         => 'nawrız',
'april'         => 'cäwir',
'may_long'      => 'mamır',
'june'          => 'mawsım',
'july'          => 'şilde',
'august'        => 'tamız',
'september'     => 'qırküýek',
'october'       => 'qazan',
'november'      => 'qaraşa',
'december'      => 'jeltoqsan',
'january-gen'   => 'qantardıñ',
'february-gen'  => 'aqpannıñ',
'march-gen'     => 'nawrızdıñ',
'april-gen'     => 'säwirdiñ',
'may-gen'       => 'mamırdıñ',
'june-gen'      => 'mawsımnıñ',
'july-gen'      => 'şildeniñ',
'august-gen'    => 'tamızdıñ',
'september-gen' => 'qırküýektiñ',
'october-gen'   => 'qazannıñ',
'november-gen'  => 'qaraşanıñ',
'december-gen'  => 'jeltoqsannıñ',
'jan'           => 'qan',
'feb'           => 'aqp',
'mar'           => 'naw',
'apr'           => 'cäw',
'may'           => 'mam',
'jun'           => 'maw',
'jul'           => 'şil',
'aug'           => 'tam',
'sep'           => 'qır',
'oct'           => 'qaz',
'nov'           => 'qar',
'dec'           => 'jel',

# Bits of text used by many pages
'categories'            => 'Barlıq sanat tizimi',
'pagecategories'        => '{{PLURAL:$1|Sanat|Sanattar}}',
'category_header'       => '«$1» sanatındağı better',
'subcategories'         => 'Tömengi sanattar',
'category-media-header' => '«$1» sanatındağı taspalar',

'linkprefix'        => '/^(.*?)([a-zäçéğıïñöşüýа-яёәіңғүұқөһA-ZÄÇÉĞİÏÑÖŞÜÝА-ЯЁӘІҢҒҮҰҚӨҺʺʹ«„]+)$/sDu',
'mainpage'          => 'Bastı bet',
'mainpagetext'      => "<big>'''MedïaWïkï bağdarlaması sätti ornatıldı.'''</big>",
'mainpagedocfooter' => 'Wïkï bağdarlamasın paýdalanw aqparatı üşin [http://meta.wikimedia.org/wiki/Help:Contents Paýdalanwşı nusqawlarımen] tanısıñız.

== Bastaw ==

* [http://www.mediawiki.org/wiki/Help:Configuration_settings Baptaw qalawları tizimi]
* [http://www.mediawiki.org/wiki/Help:FAQ MedïaWïkï JSJ]
* [http://mail.wikimedia.org/mailman/listinfo/mediawiki-announce MedïaWïkï xat taratw tizimi]',

'portal'          => 'Qawım portalı',
'portal-url'      => '{{ns:project}}:Qawım_portalı',
'about'           => 'Biz twralı',
'aboutsite'       => '{{SITENAME}} twralı',
'aboutpage'       => '{{ns:project}}:Biz_twralı',
'article'         => 'Mağlumat',
'help'            => 'Anıqtama',
'helppage'        => '{{ns:help}}:Mazmunı',
'bugreports'      => 'Qate eseptemeleri',
'bugreportspage'  => '{{ns:project}}:Qate_eseptemeleri',
'sitesupport'     => 'Demewşilik',
'sitesupport-url' => '{{ns:project}}:Järdem',
'faq'             => 'JSJ',
'faqpage'         => '{{ns:project}}:JSJ',
'edithelp'        => 'Öndew anıqtaması',
'newwindow'       => '(jaña terezede aşıladı)',
'edithelppage'    => '{{ns:help}}:Öñdew',
'cancel'          => 'Boldırmaw',
'qbfind'          => 'Tabw',
'qbbrowse'        => 'Şolw',
'qbedit'          => 'Öñdew',
'qbpageoptions'   => 'Osı bet',
'qbpageinfo'      => 'Mätin aralığı',
'qbmyoptions'     => 'Betterim',
'qbspecialpages'  => 'Arnaýı better',
'moredotdotdot'   => 'Köbirek…',
'mypage'          => 'Jeke betim',
'mytalk'          => 'Talqılawım',
'anontalk'        => 'IP talqılawı',
'navigation'      => 'Bağıttaw',

# Metadata in edit box
'metadata_help' => 'Meta-derekter (tüsindirmeler üşin [[{{ns:project}}:Meta-derekter]] betin qarañız):',

'currentevents'     => 'Ağımdağı oqïğalar',
'currentevents-url' => 'Ağımdağı_oqïğalar',

'disclaimers'       => 'Jawapkerşilikten bas tartw',
'disclaimerpage'    => '{{ns:project}}:Jawapkerşilikten_bas_tartw',
'privacy'           => 'Jeke qupïyasın saqtaw',
'privacypage'       => '{{ns:project}}:Jeke_qupïyasın_saqtaw',
'errorpagetitle'    => 'Qate',
'returnto'          => '$1 degenge oralw.',
'tagline'           => '{{GRAMMAR:ablative|{{SITENAME}}}}',
'search'            => 'İzdew',
'searchbutton'      => 'İzdew',
'go'                => 'Ötw',
'searcharticle'     => 'Ötw',
'history'           => 'Bet tarïxı',
'history_short'     => 'Tarïxı',
'updatedmarker'     => 'soñğı kirgennen beri jañartılğan',
'info_short'        => 'Aqparat',
'printableversion'  => 'Basıp şığarwğa',
'permalink'         => 'Turaqtı silteme',
'print'             => 'Basıp şığarw',
'edit'              => 'Öñdew',
'editthispage'      => 'Betti öñdew',
'delete'            => 'Joyw',
'deletethispage'    => 'Betti joyw',
'undelete_short'    => '{{PLURAL:$1|Bir|$1}} tüzetwdi qaýtarw',
'protect'           => 'Qorğaw',
'protectthispage'   => 'Betti qorğaw',
'unprotect'         => 'Qorğamaw',
'unprotectthispage' => 'Betti qorğamaw',
'newpage'           => 'Jaña bet',
'talkpage'          => 'Betti talqılaw',
'specialpage'       => 'Arnaýı bet',
'personaltools'     => 'Jeke quraldar',
'postcomment'       => 'Mändeme jiberw',
'articlepage'       => 'Mağlumat betin qaraw',
'talk'              => 'Talqılaw',
'views'             => 'Körinis',
'toolbox'           => 'Quraldar',
'userpage'          => 'Qatıswşınıñ betin qaraw',
'projectpage'       => 'Joba betin qaraw',
'imagepage'         => 'Swret betin qaraw',
'mediawikipage'     => 'Xabar betin qaraw',
'templatepage'      => 'Ülgi betin qaraw',
'viewhelppage'      => 'Anıqtama betin qaraw',
'categorypage'      => 'Sanat betin qaraw',
'viewtalkpage'      => 'Talqılaw betin qaraw',
'otherlanguages'    => 'Basqa tilderde',
'redirectedfrom'    => '($1 betinen aýdatılğan)',
'redirectpagesub'   => 'Aýdatw beti',
'lastmodifiedat'    => 'Bul bettiñ özgertilgen soñğı kezi: $2, $1.', # $1 date, $2 time
'viewcount'         => 'Bul bet {{plural:$1|bir|$1}} ret qaralğan.',
'copyright'         => 'Mağlumat $1 qujatı boýınşa qatınawlı.',
'protectedpage'     => 'Qorğawlı bet',
'jumpto'            => 'Mınağan ötip ketw:',
'jumptonavigation'  => 'bağıttaw',
'jumptosearch'      => 'izdew',

'badaccess'        => 'Ruqsat qatesi',
'badaccess-group0' => 'Suranısqan äreketiñizdi jegwiñizge ruqsat etilmeýdi.',
'badaccess-group1' => 'Suranısqan äreketiñiz $1 tobınıñ qatıswşılarına şekteledi.',
'badaccess-group2' => 'Suranısqan äreketiñiz $1 toptarı biriniñ qatwsışılarına şekteledi.',
'badaccess-groups' => 'Suranısqan äreketiñiz $1 toptarı biriniñ qatwsışılarına şekteledi.',

'versionrequired'     => 'MediaWiki $1 nusqası qajet',
'versionrequiredtext' => 'Osı betti qoldanw üşin MediaWiki $1 nusqası qajet. [[{{ns:special}}:Version]] betin qarañız.',

'ok'                  => 'Jaraýdı',
'pagetitle'           => '$1 — {{SITENAME}}',
'retrievedfrom'       => '«$1» degennen alınğan',
'youhavenewmessages'  => 'Sizde $1 bar ($2).',
'newmessageslink'     => 'jaña xabarlar',
'newmessagesdifflink' => 'soñğı özgerisine',
'editsection'         => 'öñdew',
'editold'             => 'öñdew',
'editsectionhint'     => 'Bölimdi öñdew: $1',
'toc'                 => 'Mazmunı',
'showtoc'             => 'körset',
'hidetoc'             => 'jasır',
'thisisdeleted'       => 'Qaraýmız ba, ne qaýtaramız ba?: $1',
'viewdeleted'         => 'Qaraýmız ba?: $1',
'restorelink'         => 'joýılğan {{PLURAL:$1|bir|$1}} tüzetw',
'feedlinks'           => 'Arna:',
'feed-invalid'        => 'Jaramsız jazılım arna türi.',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'Mağlumat',
'nstab-user'      => 'Jeke beti',
'nstab-media'     => 'Taspa beti',
'nstab-special'   => 'Arnaýı',
'nstab-project'   => 'Joba beti',
'nstab-image'     => 'Faýl',
'nstab-mediawiki' => 'Jüýe xabarı',
'nstab-template'  => 'Ülgi',
'nstab-help'      => 'Anıqtama',
'nstab-category'  => 'Sanat',

# Main script and global functions
'nosuchaction'      => 'Mundaý äreket joq',
'nosuchactiontext'  => 'Osı URL jaýımen engizilgen äreketti
osı wïkï joramaldap bilmedi.',
'nosuchspecialpage' => 'Bul arnaýı bet emes',
'nospecialpagetext' => 'Siz suranısqan arnaýı bet jaramsız. Barlıq jaramdı arnaýı better tizimin [[{{ns:special}}:Specialpages]] betinde taba alasız.',

# General errors
'error'                => 'Qate',
'databaseerror'        => 'Derekqordıñ qatesi',
'dberrortext'          => 'Derekqorğa suranıs jasalğanda sïntaksïs qatesi kezdesti.
Bul bağdarlamanıñ qatesin körsetw mümkin.
Derekqorğa soñğı bolğan suranıs:
<blockquote><tt>$1</tt></blockquote>
mına fwnkcïyasınan «<tt>$2</tt>».
MySQL qaýtarğan qatesi «<tt>$3: $4</tt>».',
'dberrortextcl'        => 'Derekqorğa suranıs jasalğanda sïntaksïs qatesi kezdesti.
Derekqorğa soñğı bolğan suranıs:
«$1»
mına fwnkcïyasınan: «$2».
MySQL qaýtarğan qatesi «$3: $4»',
'noconnect'            => 'Ğafw etiñiz! Bul wïkïde keýbir texnïkalıq qïınşılıqtar kezdesti, sondıqtan derekqor serverine qatınasw almaýdı. <br />
$1',
'nodb'                 => '$1 derekqorı talğanbadı',
'cachederror'          => 'Tömende suranğan bettiñ qosalqı qaltadağı köşirmesi, osı bet jañartılmağan bolwı mümkin.',
'laggedslavemode'      => 'Nazar salıñız: Bette jwıqtağı jañalawlar bolmawı mümkin.',
'readonly'             => 'Derekqorı qulıptalğan',
'enterlockreason'      => 'Qulıptaw sebebin engiziñiz, qaý waqıtqa deýin
qulıptalğanın qosa',
'readonlytext'         => 'Ağımda derekqor jaña jazba jäne tağı basqa özgerister jasawdan qulıptalınğan. Bul derekqordı jöndetw bağdarlamaların orındaw üşin bolwı mümkin, bunı bitirgennen soñ qalipti iske qaýtarıladı.

Qulıptağan äkimşi bunı bılaý tüsindiredi: $1',
'missingarticle'       => 'İzdestirilgen «$1» atawlı bet mätini derekqorda tabılmadı.

Bul dağdıda eskirgen aýırma siltemesine nemese joýılğan bet tarïxınıñ siltemesine
ergennen bolwı mümkin.

Eger bul boljam durıs sebep bolmasa, bağdarlamamızdağı qatege tap bolwıñız mümkin.
Bul twralı naqtı URL jaýın körsetip äkimşige esepteme jiberiñiz.',
'readonly_lag'         => 'Jetek derekqor serverler bastawışpen qadamlanğanda osı derekqor özdik türinde qulıptalınğan',
'internalerror'        => 'İşki qate',
'filecopyerror'        => '«$1» faýlı «$2» faýlına köşirilmedi.',
'filerenameerror'      => '«$1» faýl atı «$2» atına özgertilmedi.',
'filedeleteerror'      => '«$1» faýlı joýılmaýdı.',
'filenotfound'         => '«$1» faýlı tabılmadı.',
'unexpected'           => 'Kütilmegen mağına: «$1» = «$2».',
'formerror'            => 'Qate: jiberw ülgiti emes',
'badarticleerror'      => 'Osındaý äreket mına bette atqarılmaýdı.',
'cannotdelete'         => 'Aýtılmış bet ne swret joýılmaýdı. (Bunı basqa birew joýğan şığar.)',
'badtitle'             => 'Jaramsız ataw',
'badtitletext'         => 'Suranısqan bet atawı jaramsız, bos, tilara siltemesi ne wïkï-ara atawı mültik bolğan. Atawlarda süemeldemegen birqatar äripter bolwı mümkin.',
'perfdisabled'         => 'Ğafw etiñiz! Osı qasïet, derekqordıñ jıldamılığına äser etip, eşkimge wïkïdi paýdalanwğa bermegesin, waqıtşa öşirilgen.',
'perfdisabledsub'      => 'Mında $1 betiniñ saqtalğan köşirmesi:', # obsolete?
'perfcached'           => 'Kelesi derek qosalqı qaltasınan alınğan, sondıqtan tolıqtaý jañalanmağan bolwı mümkin.',
'perfcachedts'         => 'Kelesi derek qosalqı qaltasınan alınğan, soñğı jañalanlğan kezi: $1.',
'wrong_wfQuery_params' => 'wfQuery() fwnkcïyasında jaramsız baptar<br />
Fwnkcïya: $1<br />
Suranıs: $2',
'viewsource'           => 'Qaýnarın qaraw',
'viewsourcefor'        => '$1 qaýnarı',
'protectedtext'        => 'Bul bet öñdew boldırmaw üşin qulıptalınğan.

Bul bettiñ qaýnarın qarawıñızğa jäne köşirip alwñızğa boladı:',
'protectedinterface'   => 'Bul bet bağdarlamanıñ tildesw mätinin jetistiredi, sondıqtan qïyanat keltirmew üşin özgertwi qulıptalğan.',
'editinginterface'     => "'''Nazar salıñız:''' Bağdarlamağa tildesw mätinin jetistiretin MediaWiki betin öñdep jatırsız. Bul bettiñ özgertwi barlıq paýdalanwşılar tildeswine äser etedi.",
'sqlhidden'            => '(SQL suranısı jasırıldı)',

# Login and logout pages
'logouttitle'                => 'Qatıswşı şığwı',
'logouttext'                 => '<strong>Endi jüýeden şıqtıñız.</strong><br />
Bul komp′ywterden äli de jüýege kirmesten {{SITENAME}} jobasın
şolwıñız mümkin, nemese basqa paýdalanwşınıñ jüýege kirwi mümkin.
Keýbir betterde äli de jüýege kirgeniñizdeý körinwi mümkindigin
eskertemiz; bul şolğıştıñ qosalqı qaltasın bosatw arqılı şeşiledi.',
'welcomecreation'            => '== Qoş keldiñiz, $1! ==

Tirkelgiñiz jasaldı. {{SITENAME}} baptawıñızdı qalawıñızben özgertwdi umıtpañız.',
'loginpagetitle'             => 'Qatıswşı kirwi',
'yourname'                   => 'Qatıswşı atıñız',
'yourpassword'               => 'Qupïya söziñiz',
'yourpasswordagain'          => 'Qupïya sözdi qaýtalap engiziñiz',
'remembermypassword'         => 'Meniñ kirgenimdi bul komp′ywterde umıtpa',
'yourdomainname'             => 'Jeli üýşigiñiz',
'externaldberror'            => 'Osında sırtqı teñdestirw derekqorında qate boldı, nemese sırtqı tirkelgiñizdi jañalawğa ruqsat joq.',
'loginproblem'               => '<b>Kirwiñiz kezinde osında qïındıqqa tap boldıq.</b><br />Tağı da qaýtalap qarañız.',
'alreadyloggedin'            => '<strong>$1 degen qatıswşı, kiripsiz tüge!<strong><br />',
'login'                      => 'Kirw',
'loginprompt'                => '{{SITENAME}} torabına kirw üşin «cookies» qasïetin endirwiñiz qajet.',
'userlogin'                  => 'Kirw / Tirkelgi jasaw',
'logout'                     => 'Şığw',
'userlogout'                 => 'Şığw',
'notloggedin'                => 'Kirmegensiz',
'nologin'                    => 'Tirkelgiñiz joq pa? $1.',
'nologinlink'                => 'Jasañız',
'createaccount'              => 'Tirkelgi jasa',
'gotaccount'                 => 'Tirkelgiñiz bar ma?  $1.',
'gotaccountlink'             => 'Kiriñiz',
'createaccountmail'          => 'e-poştamen',
'badretype'                  => 'Engizgen qupïya sözderiñiz bir birine säýkes emes.',
'userexists'                 => 'Engizgen qatıswşı atıñızdı birew paýdalanıp jatır. Basqa ataw tandañız.',
'youremail'                  => 'E-poşta jaýıñız *:',
'username'                   => 'Qatıswşı atıñız:',
'uid'                        => 'Qatıswşı teñdestirwiñiz:',
'yourrealname'               => 'Şın atıñız *:',
'yourlanguage'               => 'Tiliñiz:',
'yourvariant'                => 'Türi',
'yournick'                   => 'Laqap atıñız:',
'badsig'                     => 'Qam qoltañbañız jaramsız; HTML belgişelerin tekseriñiz.',
'email'                      => 'E-poştañız',
'prefs-help-email-enotif'    => 'Eger sonı baptasañız, osı e-poşta jaýı sizge eskertw xat jiberwge qoldanıladı.',
'prefs-help-realname'        => '* Şın atıñız (mindetti emes): engizseñiz, şığarmañızdıñ awtorlığın belgilewi üşin qoldanıladı.',
'loginerror'                 => 'Kirw qatesi',
'prefs-help-email'           => '* E-poştañız (mindetti emes): «Qatıswşı» nemese «qatıswşı talqılaw» betiñizder arqılı basqalarğa baýlanısw mümkindik beredi. Öziñizdiñ kim ekeniñizdi bildirtpeýdi.',
'nocookiesnew'               => 'Qatıswşı tirkelgisi jasaldı, tek äli kirmegensiz. {{SITENAME}} jobasına qatıswşı kirw üşin «cookies» qasïeti qajet. Şolğışıñızda «cookies» qasïeti öşirilgen. Sonı endiriñiz de jaña qatıswşı atıñızdı jäne qupïya söziñizdi engizip kiriñiz.',
'nocookieslogin'             => 'Qatıswşı kirw üşin {{SITENAME}} jobası «cookies» qasïetin qoldanadı. Şolğışıñızda «cookies» qasïeti öşirilgen. Sonı endiriñiz de qaýtalap kiriñiz.',
'noname'                     => 'Qatıswşı atın durıs engizbediñiz.',
'loginsuccesstitle'          => 'Kirwiñiz sätti ötti',
'loginsuccess'               => "'''Siz endi {{SITENAME}} jobasına «$1» retinde kirip otırsız.'''",
'nosuchuser'                 => 'Mında «$1» atawlı qatıswşı joq. Emleñizdi tekseriñiz, nemese jaña tirkelgi jasañız.',
'nosuchusershort'            => 'Mında «$1» degen qatıswşı atawı joq. Emleñizdi tekseriñiz.',
'nouserspecified'            => 'Qatıswşı atın engiziwiñiz qajet.',
'wrongpassword'              => 'Engizgen qupïya söz jaramsız. Qaýtalap köriñiz.',
'wrongpasswordempty'         => 'Qupïya söz bostı boptı. Qaýtalap köriñiz.',
'mailmypassword'             => 'Qupïya sözimdi xatpen jiber',
'passwordremindertitle'      => 'Qupïya söz twralı {{SITENAME}} jobasınıñ eskertwi',
'passwordremindertext'       => 'Keýbirew (IP jaýı: $1, bälkim, öziñiz bolarsız)
{{SITENAME}} üşin bizden jaña qupïya sözin jiberwin suranısqan ($4).
«$2» qatıswşınıñ qupïya sözi «$3» boldı endi.
Qazir kirwiñiz jäne qupïya söziñizdi awıstrwıñız qajet.

Eger basqa birew bul suranıstı jasasa, nemese qupïya söziñizdi umıtsañız da,
jäne bunı özgertkiñiz kelmese de, osı xabarlamağa añğarmawıñızğa da boladı,
eski qupïya söziñizdi äriğaraý qoldanıp.',
'noemail'                    => 'Mında «$1» qatıswşınıñ e-poştası joq.',
'passwordsent'               => 'Jaña qupïya söz «$1» üşin
tirkelgen e-poşta jaýına jiberildi.
Qabıldağannan keýin kirgende sonı engiziñiz.',
'blocked-mailpassword'       => 'IP jaýıñızdan öñdew buğattalğan, sondıqtan
qïyanatşılıqtan saqtanw üşin qupïya söz jiberw qızmetiniñ äreketi ruqsat etilmeýdi.',
'eauthentsent'               => 'Kwälandırw xatı atalğan e-poşta jaýına jiberildi.
Basqa e-poşta xatın jiberwdiñ aldınan, tirkelgi şınınan sizdiki ekenin
kwälandırw üşin xattağı nusqawlarğa eriñiz.',
'throttled-mailpassword'     => 'Soñğı $1 sağatta qupïya söz eskertw xatı jiberildi tüge.
Qïyanatşılıqqa kedergi bolw üşin, $1 sağat saýın tek bir ğana qupïya söz eskertw
xatı jiberiledi.',
'mailerror'                  => 'Xat jiberw qatesi: $1',
'acct_creation_throttle_hit' => 'Ğafw etiñiz, siz $1 tirkelgi jasapsız tüge. Onan artıq isteý almaýsız.',
'emailauthenticated'         => 'E-poşta jaýıñız kwälandırılğan kezi: $1.',
'emailnotauthenticated'      => 'E-poşta jaýıñız äli kwälandırğan joq.
Tömendegi qasïettter üşin eşqandaý xat jiberilmeýdi.',
'noemailprefs'               => 'Osı qasïetter istewi üşin e-poşta jaýıñızdı engiziñiz.',
'emailconfirmlink'           => 'E-poşta jaýıñızdı kwälandırıñız',
'invalidemailaddress'        => 'Osı e-poşta jaýda jaramsız pişim bolğan, qabıl etilmeýdi.
Durıs pişimdelgen jaýdı engiziñiz, ne awmaqtı bos qaldırıñız.',
'accountcreated'             => 'Tirkelgi jasaldı',
'accountcreatedtext'         => '$1 üşin qatıswşı tirkelgisi jasaldı.',

# Edit page toolbar
'bold_sample'     => 'Jwan mätin',
'bold_tip'        => 'Jwan mätin',
'italic_sample'   => 'Qïğaş mätin',
'italic_tip'      => 'Qïğaş mätin',
'link_sample'     => 'Silteme atawı',
'link_tip'        => 'İşki silteme',
'extlink_sample'  => 'http://www.example.com silteme atawı',
'extlink_tip'     => 'Sırtqı silteme (aldınan http:// engizwin umıtpañız)',
'headline_sample' => 'Taqırıp mätini',
'headline_tip'    => '1-şi deñgeýli taqırıp',
'math_sample'     => 'Formwlanı mında engiziñiz',
'math_tip'        => 'Matematïka formwlası (LaTeX)',
'nowiki_sample'   => 'Pişimdelmeýtin mätindi osında engiziñiz',
'nowiki_tip'      => 'Wïkï pişimin elemew',
'image_sample'    => 'Example.jpg',
'image_tip'       => 'Kiriktirilgen swret',
'media_sample'    => 'Example.ogg',
'media_tip'       => 'Taspa faýlınıñ siltemesi',
'sig_tip'         => 'Qoltañbañız jäne waqıt belgisi',
'hr_tip'          => 'Dereleý sızıq (ünemdi qoldanıñız)',

# Edit pages
'summary'                   => 'Sïpattaması',
'subject'                   => 'Taqırıbı/bası',
'minoredit'                 => 'Bul şağın tüzetw',
'watchthis'                 => 'Betti baqılaw',
'savearticle'               => 'Betti saqta!',
'preview'                   => 'Qarap şığw',
'showpreview'               => 'Qarap şığw',
'showlivepreview'           => 'Twra qarap şığw',
'showdiff'                  => 'Özgeristerdi körset',
'anoneditwarning'           => "'''Nazar salıñız:''' Siz jüýege kirmegensiz. IP jaýıñız bul bettiñ öñdew tarïxında jazılıp alınadı.",
'missingsummary'            => "'''Eskertw:''' Tüzetw sïpattamasın engizbepsiz. «Saqtaw» tüýmesin tağı bassañız, tüzetwiñiz mändemesiz saqtaladı.",
'missingcommenttext'        => 'Tömende mändemeñizdi engiziñiz.',
'missingcommentheader'      => "'''Eskertw:''' Bul mändemege taqırıp/basjol jetistirmepsiz. Eger tağı da Saqtaw tüýmesin nuqısañız, tüzetwiñiz solsız saqtaladı.",
'summary-preview'           => 'Sïpattamasın qarap şığw',
'subject-preview'           => 'Taqırıbın/basın qarap şığw',
'blockedtitle'              => 'Paýdalanwşı buğattalğan',
'blockedtext'               => "<big>'''Qatıswşı atıñız ne IP jaýıñız buğattalğan.'''</big>

buğattawdı $1 istegen. Belgilengen sebebi: ''$2''.

Osı buğattawdı talqılaw üşin $1 degenmen ne basqa [[{{ns:project}}:Äkimşiler|äkimşimen]] qatınaswıñızğa boladı.
[[{{ns:special:Preferences|Tirkelgi baptawların]] qoldanıp jaramdı e-poşta jaýın engizgenşe deýin
«Qatıswşığa xat jazw» qasïetin paýdalanılmaýsız. Ağımdıq IP jaýıñız $3 bolğan. Bunı ärbir suranısıñızğa qosıñız.",
'blockedoriginalsource'     => "Tömende '''$1''' degenniñ qaýnarı körsetiledi:",
'blockededitsource'         => "Tömende '''$1''' degenge jasalğan '''tüzetwñizdiñ''' mätini körsetiledi:",
'whitelistedittitle'        => 'Öñdew üşin kirwiñiz jön.',
'whitelistedittext'         => 'Betterdi öñdew üşin $1 jön.',
'whitelistreadtitle'        => 'Oqw üşin kirwiñiz jön',
'whitelistreadtext'         => 'Betterdi oqw üşin [[{{ns:special}}:Userlogin|kirwiñiz]] jön.',
'whitelistacctitle'         => 'Sizge tirkelgi jasawğa ruqsat berilmegen',
'whitelistacctext'          => 'Osı wïkïde basqalarğa tirkelgi jasaw üşin [[{{ns:Special}}:Userlogin|kirwiñiz]] qajet jäne janasımdı ruqsattarın bïlew qajet.',
'confirmedittitle'          => 'E-poşta jaýın kwälandırw xatın qaýta öñdew qajet',
'confirmedittext'           => 'Betterdi öñdew üşin aldın ala E-poşta jaýıñızdı kwälandırwıñız qajet. Jaýıñızdı [[{{ns:Special}}:Preferences|qatıswşı baptawı]] arqılı engiziñiz jäne teksertkiñiz.',
'loginreqtitle'             => 'Kirwiñiz qajet',
'loginreqlink'              => 'kirw',
'loginreqpagetext'          => 'Basqa betterdi körw üşin siz $1 bolwıñız qajet.',
'accmailtitle'              => 'Qupïya söz jiberildi.',
'accmailtext'               => '$2 jaýına «$1» qupïya sözi jiberildi.',
'newarticle'                => '(Jaña)',
'newarticletext'            => 'Siltemege erip äli bastalmağan betke
kelipsiz. Betti bastaw üşin, tömendegi awmaqta mätiniñizdi
teriñiz (köbirek aqparat üşin [[{{ns:help}}:Mazmunı|kömek betin]]
qarañız).Eger jañılğannan osında kelgen bolsañız, şolğışıñız
«Artqa» degen tüýmesin nuqıñız.',
'anontalkpagetext'          => "----''Bul tirkelgisiz (nemese tirkelgisin qoldanbağan) paýdalanwşınıñ talqılaw beti. Osı paýdalanwşını biz tek sandıq IP jaýımen teñdestiremiz. Osındaý IP jaýlar birneşe paýdalanwşığa ortaq bolwı mümkin. Eger siz tirkelgisiz paýdalanwşı bolsañız jäne sizge qatıssız mändemeler jiberilgenin sezseñiz, basqa tirkelgisiz paýdalanwşılarmen aralastırmawı üşin [[{{ns:special}}:Userlogin|tirkelgi jasañız ne kiriñiz]].''",
'noarticletext'             => 'Bul bette ağımda eş mätin joq, basqa betterden osı bet atawın [[{{ns:special}}:Search/{{PAGENAME}}|izdep körwiñizge]] nemese osı betti [{{fullurl:{{FULLPAGENAME}}|action=edit}} tüzetwiñizge] boladı.',
'clearyourcache'            => "'''Añğartpa:''' Saqtağannan keýin özgeristerdi körw üşin şolğış qosalqı qaltasın bosatw keregi mümkin. '''Mozilla  / Safari:''' ''Shift'' pernesin basıp turıp ''Reload'' (''Qaýta jüktew'') tüýmesin nuqıñız (ne ''Ctrl-Shift-R'' basıñız); ''IE:'' ''Ctrl-F5'' basıñız; '''Opera / Konqueror''' ''F5'' pernesin basıñız.",
'usercssjsyoucanpreview'    => '<strong>Basalqı:</strong> Saqtaw aldında jaña CSS/JS faýlın tekserw üşin «Qarap şığw» tüýmesin qoldanıñız.',
'usercsspreview'            => "'''Mınaw CSS mätinin tek qarap şığw ekenin umıtpañız, ol äli saqtalğan joq!'''",
'userjspreview'             => "'''Mınaw JavaScript qatıswşı bağdarlamasın tekserw/qarap şığw ekenin umıtpañız, ol äli saqtalğan joq!'''",
'userinvalidcssjstitle'     => "'''Nazar salıñız:''' Mında «$1» atawlı bezendirw mäneri joq. Paýdalanwşınıñ .css jäne .js faýl atawı kişi äripppen jazılatının umıtpañız, mısalğa {{ns:user}}:Foo/monobook.css degendi {{ns:user}}:Foo/Monobook.css degenmen salıstırıñız.",
'updated'                   => '(Jañartılğan)',
'note'                      => '<strong>Añğartpa:</strong>',
'previewnote'               => '<strong>Mınaw tek qarap şığw ekenin umıtpañız; tüzetwler äli saqtalğan joq!</strong>',
'session_fail_preview'      => '<strong>Ğafw etiñiz! Sessïya derekteri ısırap qalğandıqtan öñdewiñizdi jöndeý almaýmız.
Mätiniñizdi saqtap qaýtalap köriñiz. Eger äli is ötpeýtin bolsa, şığıp jäne keri kirip köriñiz.</strong>',
'previewconflict'           => 'Bul qarap şığw joğarıdağı öñdew awmağındağı mätinge saqtağan kezindegi deý ıqpal etedi.',
'session_fail_preview_html' => "<strong>Ğafw etiñiz! Sessïya derekteri ısırap qalğandıqtan öñdewiñizdi jöndeý almaýmız.</strong>

''Osı wïkïde qam HTML endirilgen, JavaScript şabwıldardan qorğanw üşin aldın ala qarap şığw jasırılğan.''

<strong>Eger bul öñdew adal talap bolsa, qaýtarıp köriñiz. Eger äli de istemese, şığıp, sosın keri kirip köriñiz.</strong>",
'importing'                 => 'Sırttan alwda: $1',
'editing'                   => 'Öñdewde: $1',
'editinguser'               => 'Qatıswşını öñdewde: <b>$1</b>',
'editingsection'            => 'Öñdewde: $1 (bölimi)',
'editingcomment'            => 'Öñdewde: $1 (mändemesi)',
'editconflict'              => 'Öñdew egesi: $1',
'explainconflict'           => 'Osı betti siz öñdeý bastağanda basqa keýbirew betti özgertken.
Joğarğı awmaqta bettiñ ağımdıq mätini bar.
Tömengi awmaqta siz özgertken mätini körsetiledi.
Özgertwiñizdi ağımdıq mätinge üstewiñiz jön.
"Betti saqta!" tüýmesine basqanda
<b>tek</b> joğarğı awmaqtağı mätin saqtaladı.<br />',
'yourtext'                  => 'Mätiniñiz',
'storedversion'             => 'Saqtalğan nusqası',
'nonunicodebrowser'         => '<strong>AÑĞARTPA: Şolğışıñız Unicode belgilewine üýlesimdi emes, sondıqtan latın emes äripteri bar betterdi öñdew zil bolw mümkin. Jumıs istewge ıqtïmaldıq berw üşin, <strong>tömengi öñdew awmağında ASCII emes äripter onaltılıq sanımen körsetiledi</strong>.',
'editingold'                => '<strong>AÑĞARTPA: Osı bettiñ erterek nusqasın
öñdep jatırsız.
Bunı saqtasañız, osı nwsqadan soñğı barlıq tüzetwler joýıladı.</strong>',
'yourdiff'                  => 'Aýırmalar',
'copyrightwarning'          => '{{SITENAME}} jobasına qosılğan bükil üles $2 (köbirek aqparat üşin: $1) qujatına saý jiberilgen bolıp sanaladı. Eger jazwıñızdıñ erkin köşirilip tüzetilwin qalamasañız, mında usınbawıñız jön.<br />
Tağı, qosqan ülesiñiz - öziñizdiñ jazğanığız, ne aşıq aqparat közderinen alınğan mağlumat bolğanın wäde etesiz.<br />
<strong>AVTORLIQ QUQIQPEN QORĞAWLI AQPARATTI RUQSATSIZ QOSPAÑIZ!</strong>',
'copyrightwarning2'         => 'Este tursın: barlıq {{SITENAME}} jobasına berilgen ülester basqa wles berwşilermen tüzetwge, özgertwge, ne alastanwğa mümkin. Alğıssız tüzetwge enjarlan bolsañız, onda şığarmañızdı mında jarïyalamañız.<br />
Tağı, osını öziñiz jazğanıñızdı, ne barşa qazınasınan, nemese sondaý-aq aqısız aşıq qaýnarınan köşirgeniñizdi
däl osındaý bizge mindetteme beresiz (köbirek aqparat üşin $1 qwjatın qarañız).<br />
<strong>AWTORLIQ QUQIQPEN QORĞAWLI AQPARATTI RUQSATSIZ QOSPAÑIZ!</strong>',
'longpagewarning'           => '<strong>NAZAR SALIÑIZ: Bul bettiñ mölşeri — $1 kïlobaýt; keýbir
şolğıştarda bet mölşeri 32 kB jetse ne onı assa öñdew kürdeli bolwı mümkin.
Betti birneşe kişkin bölimderge bölip köriñiz.</strong>',
'longpageerror'             => '<strong>QATE: Jiberetin mätiniñizdin mölşeri — $1 kB, eñ köbi $2 kB
ruqsat etilgen mölşerinen asqan. Bul saqtaý alınbaýdı.</strong>',
'readonlywarning'           => '<strong>NAZAR SALIÑIZ: Derekqor jöndetw üşin qulıptalğan,
sondıqtan däl qazir tüzetwiñizdi saqtaý almaýsız. Sosın qoldanwğa üşin mätäniñizdi köşirip,
öz kompüteriñizde faýlğa saqtañız.</strong>',
'protectedpagewarning'      => '<strong>NAZAR SALIÑIZ: Bul bet qorğalğan. Tek äkimşi ruqsatı bar qatıswşılar öñdew jasaý aladı.</strong>',
'semiprotectedpagewarning'  => "'''Añğartpa:''' Bet [[{{ns:project}}:Jartılaý qorğaw sayasatı|qorğalğan]], sondıqtan osını tek ruqsatı bar qatıswşılar öñdeý aladı.",
'templatesused'             => 'Bul bette qoldanılğan ülgiler:',
'templatesusedpreview'      => 'Bunı qarap şığwğa qoldanılğan ülgiler:',
'templatesusedsection'      => 'Bul bölimde qoldanılğan ülgiler:',
'edittools'                 => '<!-- Mındağı mağlumat öñdew jäne qotarw ülgittriñiñ astında körsetiledi. -->',
'nocreatetitle'             => 'Betti bastaw şektelgen',
'nocreatetext'              => 'Bul torapta jaña bet bastawı şektelgen.
Keri qaýtıp bar betti öñdewiñizge boladı, nemese [[{{ns:special}}:Userlogin|kirwiñizge ne tirkelgi jasawğa]] boladı.',
'cantcreateaccounttitle'    => 'Tirkelgi jasalmadı',
'cantcreateaccounttext'     => 'Osı IP jaýdan (<b>$1</b>) tirkelgi jasawı buğattalğan.
Bälkim sebebi, oqw ornıñızdan, nemese Ïnternet jetkizwşiden
üzbeý buzaqılıq bolğanı.',

# History pages
'revhistory'                  => 'Nusqalar tarïxı',
'viewpagelogs'                => 'Osı betke qatıstı jwrnaldardı qaraw',
'nohistory'                   => 'Osı bettiniñ nusqalar tarïxı joq.',
'revnotfound'                 => 'Nusqa tabılmadı',
'revnotfoundtext'             => 'Osı suranısqan bettiñ eski nusqası tabılğan joq.
Osı betti aşwğa paýdalanğan URL jaýın qaýta tekserip şığıñız.',
'loadhist'                    => 'Bet tarïxın jüktewi',
'currentrev'                  => 'Ağımdıq nusqası',
'revisionasof'                => '$1 kezindegi nusqası',
'revision-info'               => '$1 kezindegi $2 jasağan nusqası',
'previousrevision'            => '← Eskilew nusqası',
'nextrevision'                => 'Jañalaw nusqası →',
'currentrevisionlink'         => 'Ağımdıq nusqası',
'cur'                         => 'ağım.',
'next'                        => 'kel.',
'last'                        => 'soñ.',
'orig'                        => 'tüp.',
'histlegend'                  => 'Aýırmasın körw: salıstıramın degen nusqalardı tañdap, ne <Enter> pernesin, ne tömendegi tüýmeni basıñız.<br />
Şarttı belgiler: (ağım.) = ağımdıq nusqamen aýırması,
(soñ.) = aldıñğı nusqamen aýırması, ş = şağın tüzetw',
'deletedrev'                  => '[joýılğan]',
'histfirst'                   => 'Eñ alğaşqısına',
'histlast'                    => 'Eñ soñğısına',
'rev-deleted-comment'         => '(mändeme alastatıldı)',
'rev-deleted-user'            => '(qatıswşı atı alastatıldı)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">
Osı bettiñ nusqası jarïya murağattarınan alastatılğan.
Bul jaýtqa [{{fullurl:{{ns:special}}:Log/delete|page={{PAGENAMEE}}}} joyw jwrnalında] egjeý-tegjeý mälimetter bolwı mümkin.
</div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks">
Osı bettiñ nusqası jarïya murağattarınan alastatılğan.
Sonı osı toraptıñ äkimşisi bop körwiñiz mümkin;
bul jaýtqa [{{fullurl:{{ns:special}}:Log/delete|page={{PAGENAMEE}}}} joyw jwrnalında] egjeý-tegjeý mälmetter bolwı mümkin.
</div>',
'rev-delundel'                => 'körset/jasır',

'history-feed-title'          => 'Nusqalar tarïxı',
'history-feed-description'    => 'Bul bettiñ wïkïdegi nusqalar tarïxı',
'history-feed-item-nocomment' => '$1 degen $2 kezinde', # user at time
'history-feed-empty'          => 'Suranğan bet joq.
Bul bet wïkïden joýılğan, nemese qaýta atalğan.
Säýkesi bar jaña betterdi [[{{ns:special}}:Search|wïkïden izdep]] qarañız.',

# Revision deletion
'revisiondelete'            => 'Nusqalardı joyw/qaýtarw',
'revdelete-nooldid-title'   => 'Nısana nusqası joq',
'revdelete-nooldid-text'    => 'Osı äreketti orındaw üşin aqırğı nusqasın
ne nusqaların engizbepsiz.',
'revdelete-selected'        => '[[:$1]] degenniñ talğanılğan nusqası:',
'revdelete-text'            => 'Joýılğan nusqalardı äli de bet tarïxında körwge boladı,
biraq onıñ mätin mağlumatı barşağa qatınalmaýdı.

Osı wïkïdiñ basqa äkimşileri jasırın mağlumatqa qatınaý aladı,
jäne torap operatorları qosımşa şektew endirgenşe deýin,
osı tildesw arqılı joýılğandı keri qaýtara aladı.',
'revdelete-legend'          => 'Nusqanınıñ şektewleri:',
'revdelete-hide-text'       => 'Nusqa mätinin jasır',
'revdelete-hide-comment'    => 'Tüzetw mändemesin jasır',
'revdelete-hide-user'       => 'Öñdewşi atın (IP jaýın) jasır',
'revdelete-hide-restricted' => 'Osı şektewlerdi barşağa sïyaqtı äkimşilerge de qoldanw',
'revdelete-log'             => 'Jwrnal mändemesi:',
'revdelete-submit'          => 'Talğanğan nusqağa qoldanw',
'revdelete-logentry'        => '[[$1]] degenge nusqa körinisin özgertti',

# Diffs
'difference'                => '(Nusqalar arasındağı aýırmaşılıq)',
'loadingrev'                => 'aýırma üşin nusqa jüktew',
'lineno'                    => 'Jol $1:',
'editcurrent'               => 'Osı bettiñ ağımdıq nusqasın öñdew',
'selectnewerversionfordiff' => 'Salıstırw üşin jañalaw nusqasın talğañız',
'selectolderversionfordiff' => 'Salıstırw üşin eskilew nusqasın talğañız',
'compareselectedversions'   => 'Tañdağan nusqalardı salıstırw',

# Search results
'searchresults'         => 'İzdestirw nätïjeleri',
'searchresulttext'      => 'Osı {{SITENAME}} jobasında izdestirw twralı köbirek aqparat üşin, [[{{ns:project}}:İzdew|{{SITENAME}} izdew nusqawların]] qarañız.',
'searchsubtitle'        => "İzdestirw suranısıñız: '''[[:$1]]'''",
'searchsubtitleinvalid' => "İzdestirw suranısıñız: '''$1'''",
'badquery'              => 'İzdestirw suranıs jaramsız pişimdelgen',
'badquerytext'          => 'Ğafw etiñiz, suranısıñızdı orındaý almadıq.
Bul üş äripten kem sözdi izdestirwge talaptanğanıñızdan
bolwğa mümkin, ol äli de süýemeldenbegen.
Tağı da bul söýlemdi durıs engizbegendikten de bolwğa mümkin,
mısalı, «balıq jäne jäne qabırşaq».
Basqa suranıs jasap köriñiz',
'matchtotals'           => '«$1» izdestirw suranısı $2 bettiñ atawına
jäne $3 bettiñ mätinine säýkes.',
'noexactmatch'          => "'''Osında «$1» atawlı bet joq.''' Bul betti öziñiz '''[[:$1|bastaý  alasız]].'''",
'titlematches'          => 'Bet atawı säýkesi',
'notitlematches'        => 'Eş bet atawı säýkes emes',
'textmatches'           => 'Bet mätiniñ säýkesi',
'notextmatches'         => 'Eş bet mätini säýkes emes',
'prevn'                 => 'aldıñğı $1',
'nextn'                 => 'kelesi $1',
'viewprevnext'          => 'Körsetilwi: ($1) ($2) ($3) jazba.',
'showingresults'        => 'Tömende nömir <b>$2</b> degennen bastap <b>$1</b> nätïjege deýin körsetilgen.',
'showingresultsnum'     => 'Tömende nömir <b>$2</b> degennen bastap <b>$3</b> nätïje körsetilgen.',
'nonefound'             => "'''Añğartpa''': Tabw sätsiz bitwi jïi «bolğan» jäne «degen» sïyaqtı
tizimdelmeýtin jalpı sözdermen izdestirwden bolwı mümkin,
nemese birden artıq izdestirw şart sözderin egizgennen (nätïjelerde tek
barlıq şart sözder kedesse körsetiledi) bolwı mümkin.",
'powersearch'           => 'İzdew',
'powersearchtext'       => 'Mına esim ayalarda izdew:<br />$1<br />$2 Aýdatwlardı tizimdew<br />İzdestirw suranısı: $3 $9',
'searchdisabled'        => '{{SITENAME}} jobasında işki izdewi öşirilgen. Äzirşe Google nemese Yahoo! arqılı izdewge boladı. Añğartpa: {{SITENAME}} mağlumat tizimidewleri olarda eskirgen bolwğa mümkin.',
'blanknamespace'        => '(Negizgi)',

# Preferences page
'preferences'           => 'Baptawlar',
'mypreferences'         => 'Baptawım',
'prefsnologin'          => 'Kirmegensiz',
'prefsnologintext'      => 'Baptawlardı qalaw üşin aldın ala [[{{ns:special}}:Userlogin|kirwiñiz]] qajet.',
'prefsreset'            => 'Baptawlar arqawdan qaýta ornatıldı.',
'qbsettings'            => 'Mäzir aýmağı',
'changepassword'        => 'Qupïya söz özgertw',
'skin'                  => 'Bezendirw',
'math'                  => 'Matematïka',
'dateformat'            => 'Kün-aý pişimi',
'datedefault'           => 'Eş qalawsız',
'datetime'              => 'Waqıt',
'math_failure'          => 'Öñdetw sätsiz bitti',
'math_unknown_error'    => 'belgisiz qate',
'math_unknown_function' => 'belgisiz fwnkcïya',
'math_lexing_error'     => 'leksïka qatesi',
'math_syntax_error'     => 'sïntaksïs qatesi',
'math_image_error'      => 'PNG awdarısı sätsiz bitti; latex, dvips, gs jäne convert bağdarlamalarınıñ mültiksiz ornatwın tekseriñiz',
'math_bad_tmpdir'       => 'Matematïkanıñ waqıtşa qaltasına jazılmadı, ne qalta jasalmadı',
'math_bad_output'       => 'Matematïkanıñ beris qaltasına jazılmadı, ne qalta jasalmadı',
'math_notexvc'          => 'texvc bağdarlaması joğaltılğan; baptaw üşin math/README qujatın qarañız.',
'prefs-personal'        => 'Jeke derekteri',
'prefs-rc'              => 'Jwıqtağı özgerister',
'prefs-watchlist'       => 'Baqılaw',
'prefs-watchlist-days'  => 'Baqılaw tiziminde körseterin kün sanı:',
'prefs-watchlist-edits' => 'Keñeýtilgen baqılaw tizimi tüzetw körseterin sanı:',
'prefs-misc'            => 'Qosımşa',
'saveprefs'             => 'Saqta',
'resetprefs'            => 'Tasta',
'oldpassword'           => 'Ağımdıq qupïya söz:',
'newpassword'           => 'Jaña qupïya söz:',
'retypenew'             => 'Jaña qupïya sözdi qaýtalañız:',
'textboxsize'           => 'Öñdew',
'rows'                  => 'Joldar:',
'columns'               => 'Bağandar:',
'searchresultshead'     => 'İzdew',
'resultsperpage'        => 'Bet saýın nätïje sanı:',
'contextlines'          => 'Nätïje saýın jol sanı:',
'contextchars'          => 'Jol saýın ärip sanı:',
'stubthreshold'         => 'Biteme körstetwin anıqtaw tabaldırığı:',
'recentchangescount'    => 'Jwıqtağı özgeristerdegi atawlar:',
'savedprefs'            => 'Baptawlarıñız saqtaldı.',
'timezonelegend'        => 'Waqıt beldewi',
'timezonetext'          => 'Jergilikti waqıtıñızben server waqıtınıñ (UTC) arasındağı sağat sanı.',
'localtime'             => 'Jergilikti waqıt',
'timezoneoffset'        => 'Iğıstırw¹',
'servertime'            => 'Server waqıtı',
'guesstimezone'         => 'Şolğıştan alıp toltırw',
'allowemail'            => 'Basqadan xat qabıldawın endirw',
'defaultns'             => 'Mına esim ayalarda ädepkiden izdew:',
'default'               => 'ädepki',
'files'                 => 'Faýldar',

# User rights
'userrights-lookup-user'     => 'Qatıswşı toptarın meñgerw',
'userrights-user-editname'   => 'Qatıswşı atın engiziñiz:',
'editusergroup'              => 'Qatıswşı toptarın öñdew',
'userrights-editusergroup'   => 'Qatıswşı toptarın öñdew',
'saveusergroups'             => 'Qatıswşı toptarın saqtaw',
'userrights-groupsmember'    => 'Müşeligi:',
'userrights-groupsavailable' => 'Qatınawlı toptar:',
'userrights-groupshelp'      => 'Qatıswşını üsteýtin ne alastatın toptardı talğañız.
Talğawı öşirilgen toptar özgertilimeýdi. Toptardıñ talğawın CTRL + Sol jaq nuqwmen öşirwiñizge boladı.',

# Groups
'group'            => 'Top:',
'group-bot'        => 'Bottar',
'group-sysop'      => 'Äkimşiler',
'group-bureaucrat' => 'Töreşiler',
'group-all'        => '(barlığı)',

'group-bot-member'        => 'bot',
'group-sysop-member'      => 'äkimşi',
'group-bureaucrat-member' => 'töreşi',

'grouppage-bot'        => '{{ns:project}}:Bottar',
'grouppage-sysop'      => '{{ns:project}}:Äkimşiler',
'grouppage-bureaucrat' => '{{ns:project}}:Töreşiler',

# Recent changes
'changes'                           => 'özgeris',
'recentchanges'                     => 'Jwıqtağı özgerister',
'recentchangestext'                 => 'Bul bette osı wïkïdegi bolğan jwıqtağı özgerister baýqaladı.',
'rcnote'                            => '$3 kezine deýin — tömende soñğı <strong>$2</strong> kündegi, soñğı <strong>$1</strong> özgeris körsetilgen.',
'rcnotefrom'                        => '<b>$2</b> kezinen beri — tömende özgerister <b>$1</b> deýin körsetilgen.',
'rclistfrom'                        => '$1 kezinen beri — jaña özgeristerdi körset.',
'rcshowhideminor'                   => 'Şağın tüzetwdi $1',
'rcshowhidebots'                    => 'Bottardı $1',
'rcshowhideliu'                     => 'Tirkelgendi $1',
'rcshowhideanons'                   => 'Tirkelgisizdi $1',
'rcshowhidepatr'                    => 'Küzettegi tüzetwlerdi $1',
'rcshowhidemine'                    => 'Tüzetwimdi $1',
'rclinks'                           => 'Soñğı $2 künde bolğan, soñğı $1 özgeristi körset<br />$3',
'diff'                              => 'aýırm.',
'hist'                              => 'tar.',
'hide'                              => 'jasır',
'show'                              => 'körset',
'minoreditletter'                   => 'ş',
'newpageletter'                     => 'J',
'boteditletter'                     => 'b',
'sectionlink'                       => '→',
'number_of_watching_users_pageview' => '[baqılağan $1 qatıswşı]',
'rc_categories'                     => 'Sanattarğa şektew ("|" belgisimen bölikteñiz)',
'rc_categories_any'                 => 'Qaýsıbir',

# Upload
'upload'                      => 'Faýl qotarw',
'uploadbtn'                   => 'Qotar!',
'reupload'                    => 'Qaýtalap qotarw',
'reuploaddesc'                => 'Qotarw ülgitine oralw.',
'uploadnologin'               => 'Kirmegensiz',
'uploadnologintext'           => 'Faýl qotarw üşin
[[{{ns:special}}:Userlogin|kirwiñiz]] qajet.',
'upload_directory_read_only'  => 'Qotarw qaltasına ($1) jazwğa veb-serverge ruqsat berilmegen.',
'uploaderror'                 => 'Qotarw qatesi',
'uploadtext'                  => "Tömendegi ülgit faýl qotarwğa qoldanıladı, aldındağı swretterdi qaraw üşin ne izdew üşin [[{{ns:special}}:Imagelist|qotarılğan faýldar tizimine]] barıñız, qotarw men joyw tağı da [[{{ns:special}}:Log/upload|qotarw jwrnalına]] jazılıp alınadı.

Swretterdi betke kirgizw üşin, faýlğa twra baýlanıstratın
'''<nowiki>[[{{ns:image}}:File.jpg]]</nowiki>''',
'''<nowiki>[[{{ns:image}}:File.png|balama mätin]]</nowiki>''' nemese
'''<nowiki>[[{{ns:media}}:File.ogg]]</nowiki>''' silteme pişimin qoldanıñız.",
'uploadlog'                   => 'qotarw jwrnalı',
'uploadlogpage'               => 'Qotarw jwrnalı',
'uploadlogpagetext'           => 'Tömende jwıqtağı qotarılğan faýl tizimi.',
'filename'                    => 'Faýl atı',
'filedesc'                    => 'Sïpattaması',
'fileuploadsummary'           => 'Sïpattaması:',
'filestatus'                  => 'Awtorlıq quqıqtarı küýi',
'filesource'                  => 'Faýl qaýnarı',
'copyrightpage'               => '{{ns:project}}:Awtorlıq quqıqtar',
'copyrightpagename'           => '{{SITENAME}} awtorlıq quqıqtarı',
'uploadedfiles'               => 'Qotarılğan faýldar',
'ignorewarning'               => 'Nazar salwdı elemew jäne faýldı ärdeqaşan saqtaw.',
'ignorewarnings'              => 'Ärqaýsı nazar salwlardı elemew',
'minlength'                   => 'Faýl atında eñ keminde üş ärip bolwı kerek.',
'illegalfilename'             => '«$1» faýl atawında bet atawlarında ruqsat etilmegen nışandar bar. Faýldı qaýta atañız, sosın qaýta jwktep köriñiz.',
'badfilename'                 => 'Faýldıñ atı «$1» bop özgertildi.',
'badfiletype'                 => '«.$1» usınılmağan swret faýlınıñ keñeýtimi.',
'largefile'                   => 'Faýl mölşerin $1 Baýttan asırmawğa tırısıñız, bul faýl mölşeri $2 Baýt',
'largefileserver'             => 'Osı faýldıñ mölşeri serverdiñ qalawınan asıp ketken.',
'emptyfile'                   => 'Qotarılğan faýlıñız bos sïyaqtı. Bul faýl atawı jansaq engizilgeninen bolwı mümkin. Qotarğıñız kelgen faýl şınında da osı faýl bolğanın tekserip alıñız.',
'fileexists'                  => 'Osındaý atawlı faýl bar tüge. Qaýta jazwdıñ aldınan $1 tekserip şığıñız.',
'fileexists-forbidden'        => 'Osındaý atawlı faýl bar tüge. Keri qaýtıñız da, jäne osı faýldı basqa atımen qotarıñız. [[{{ns:image}}:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Osındaý atawlı faýl ortaq faýl arqawında bar tüge. Keri qaýtıñız da, osı faýldı jaña atımen qotarıñız. [[{{ns:image}}:$1|thumb|center|$1]]',
'successfulupload'            => 'Qotarw sätti ötti',
'fileuploaded'                => '«$1» faýlı sätti qotarıldı!
Osı siltemege erip — $2, sïpattama betine barıñız da, jäne osı faýl twralı
aqparat toltırıñız: qaýdan alınğanın, qaşan jasalğanın, kim jasağanın,
tağı basqa biletiñizdi. Bul swret bolsa, mınadaý pişimimen kiristirwge boladı: <tt><nowiki>[[Swret:$1|thumb|Sïpattaması]]</nowiki></tt>',
'uploadwarning'               => 'Qotarw twralı nazar salw',
'savefile'                    => 'Faýldı saqtaw',
'uploadedimage'               => '«[[$1]]» faýlın qotardı',
'uploaddisabled'              => 'Faýl qotarwı öşirilgen',
'uploaddisabledtext'          => 'Osı wïkïde faýl qotarwı öşirilgen.',
'uploadscripted'              => 'Osı faýlda, veb şolğıştı ağat tüsindikke keltiretiñ HTML belgilew, ne skrïpt kodı bar.',
'uploadcorrupt'               => 'Osı faýl büldirilgen, ne ädepsiz keñeýtimi bar. Faýldı tekserip, qotarwın qaýtalañız.',
'uploadvirus'                 => 'Osı faýlda vïrws bolwı mümkin! Egjeý-tegjeý aqparatı: $1',
'sourcefilename'              => 'Qaýnardağı faýl atı',
'destfilename'                => 'Aqırğı faýl atı',
'watchthisupload'             => 'Osı betti baqılaw',
'filewasdeleted'              => 'Osı atawı bar faýl burın qotarılğan, sosın joýıldırılğan. Qaýta qotarw aldınan $1 degendi tekseriñiz.',

'upload-proto-error'      => 'Jaramsız xattamalıq',
'upload-proto-error-text' => 'Sırttan qotarw üşin URL jaýları <code>http://</code> nemese <code>ftp://</code> degenderden bastalw qajet.',
'upload-file-error'       => 'İşki qate',
'upload-file-error-text'  => 'Serverde waqıtşa faýl jasawı işki qatege uşırastı. Bul jüýeniñ äkimşimen qatınasıñız.',
'upload-misc-error'       => 'Belgisiz qotarw qatesi',
'upload-misc-error-text'  => 'Qotarw kezinde belgisiz qate uşırastı. Qaýsı URL jaýı jaramdı jäne qatınawlı ekenin tekserip şığıñız da qaýtalap köriñiz. Eger bul mäsele älde de qalsa, jüýe äkimşimen qatınasıñız.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'URL jaýı jetilmedi',
'upload-curl-error6-text'  => 'Berilgen URL jaýı jetilmedi. Qaýsı URL jaýı durıs ekenin jäne torap iste ekenin qaýtalap qatañ tekseriñiz.',
'upload-curl-error28'      => 'Qotarwğa berilgen waqıt bitti',
'upload-curl-error28-text' => 'Toraptıñ jawap berwi tım uzaq waqıtqa sozıldı. Bul torap iste ekenin tekserip şığıñız, az waqıt kidire turıñız da tağı qaýtalap köriñiz. Talabıñızdı jüktelwi azdaw kezinde qaýtalawğa bolmıs.',

'license'            => 'Lïcenzïyası',
'nolicense'          => 'Eşteñe talğanbağan',
'upload_source_url'  => ' (jaramdı, barşağa qatınawlı URL jaý)',
'upload_source_file' => ' (komp′ywteriñizdegi faýl)',

# Image list
'imagelist'                 => 'Faýl tizimi',
'imagelisttext'             => "Tömende ''$2'' surıptalğan '''$1''' faýl tizimi.",
'imagelistforuser'          => 'Mında tek $1 jüktegen swretter körsetiledi.',
'getimagelist'              => 'faýl tizimdewi',
'ilsubmit'                  => 'İzdew',
'showlast'                  => 'Soñğı $1 faýl $2 surıptap körset.',
'byname'                    => 'atımen',
'bydate'                    => 'kün-aýmen',
'bysize'                    => 'mölşerimen',
'imgdelete'                 => 'joyw',
'imgdesc'                   => 'sïpp.',
'imgfile'                   => 'faýl',
'imglegend'                 => 'Şarttı belgiler: (sïpp.) — faýl sïpattamasın körsetw/öñdew.',
'imghistory'                => 'Faýl tarïxı',
'revertimg'                 => 'qaýt.',
'deleteimg'                 => 'joyw',
'deleteimgcompletely'       => 'Osı faýldıñ barlıq nusqaların joý',
'imghistlegend'             => 'Şarttı belgiler: (ağım.) = ağımdıq faýl, (joyw) = eski nusqasın
joyw, (qaý.) = eski nusqasına qaýtarw.
<br /><i>Qotarılğan faýldı körw üşin kün-aýına nuqıñız</i>.',
'imagelinks'                => 'Siltemeleri',
'linkstoimage'              => 'Bul faýlğa kelesi better silteýdi:',
'nolinkstoimage'            => 'Bul faýlğa eş bet siltemeýdi.',
'sharedupload'              => 'Bul faýl ortaq arqawına qotarılğan sondıqtan basqa jobalarda qoldanwı mümkin.',
'shareduploadwiki'          => 'Bılaýğı aqparat üşin $1 degendi qarañız.',
'shareduploadwiki-linktext' => 'faýl sïpattaması beti',
'noimage'                   => 'Mınadaý atawlı faýl joq, $1 mümkindigiñiz bar.',
'noimage-linktext'          => 'osını qotarw',
'uploadnewversion-linktext' => 'Bul faýldıñ jaña nusqasın qotarw',
'imagelist_date'            => 'Kün-aýı',
'imagelist_name'            => 'Atawı',
'imagelist_user'            => 'Qatıswşı',
'imagelist_size'            => 'Mölşeri (baýt)',
'imagelist_description'     => 'Sïpattaması',
'imagelist_search_for'      => 'Swretti atımen izdew:',

# MIME search
'mimesearch' => 'Faýldı MIME türimen izdew',
'mimetype'   => 'MIME türi:',
'download'   => 'jüktew',

# Unwatched pages
'unwatchedpages' => 'Baqılanbağan better',

# List redirects
'listredirects' => 'Aýdatw bet tizimi',

# Unused templates
'unusedtemplates'     => 'Paýdalanılmağan ülgiler',
'unusedtemplatestext' => 'Bul bet basqa betke kirictirilmegen ülgi esim ayaısındağı barlıq betterdi tizimdeýdi. Ülgilerdi joyw aldınan bunıñ basqa siltemelerin tekserip şığwın umıtpañız',
'unusedtemplateswlh'  => 'basqa siltemeler',

# Random redirect
'randomredirect' => 'Kezdeýsoq aýdatw',

# Statistics
'statistics'             => 'Joba sanağı',
'sitestats'              => '{{SITENAME}} sanağı',
'userstats'              => 'Qatıswşı sanağı',
'sitestatstext'          => "Mındağı derekqorda bulaýşa '''$1''' bet bar.
Bunıñ işinde «talqılaw» betteri, {{SITENAME}} jobası twralı better, kişkene «biteme»
better, aýdatwlar, mağlumat bet dep sanalmaýtın, bälkim, tağı da basqalar.
Osını esepten şığarğanda, mında mağlumattı dep sanalatın
'''$2''' bet bar şığar.

Torapqa '''$8''' faýl qotarılğan.

Osı wïkï jobası ornatılğannan beri bulaýşa better '''$3''' ret qaralğan,
jäne better '''$4''' ret öñdelgen.
Bunıñ nätïjesinde orta eseppen bir bet saýın '''$5''' öñdew istelingen, jäne bir öñdew saýın '''$6''' ret qaraw kelgen.

Ağımdıq [http://meta.wikimedia.org/wiki/Help:Job_queue tapsırım kezegi] uzındılığı: '''$7'''.",
'userstatstext'          => "Mında '''$1''' tirkelgen qatıswşı bar, sonıñ işinde
'''$2''' (nemese '''$4 %''') $5 bar.",
'statistics-mostpopular' => 'Eñ köp qaralğan better',

'disambiguations'     => 'Aýrıqtı better',
'disambiguationspage' => '{{ns:template}}:Disambig',
'disambiguationstext' => 'Kelesi better <i>aýrıqtı betke</i> silteýdi. Bunıñ ornına belgili taqırıpqa siltewi qajet.<br />Betke $1 siltegen jağdaýda, bet aýrıqtı dep sanaladı.<br />Basqa esim ayasınan nusqaýtın siltemeler mında <i>tizimdelmeýdi</i>.',

'doubleredirects'     => 'Şınjırlı aýdatwlar',
'doubleredirectstext' => 'Ärbir joldağı birinşi men ekinşi aýdatw siltemeleri bar, sonımen birge ekinşi aýdatw mätinniñ birinşi jolı bar. Ädette birinşi silteme aýdaýtın «şın» aqırğı bettiñ atawı bolwı qajet.',

'brokenredirects'     => 'Eş betke keltirmeýtin aýdatwlar',
'brokenredirectstext' => 'Kelesi aýdatwlar joq betterge silteýdi:',

# Miscellaneous special pages
'nbytes'                  => '$1 Baýt',
'ncategories'             => '$1 sanat',
'nlinks'                  => '$1 silteme',
'nmembers'                => '$1 bwın',
'nrevisions'              => '$1 nusqa',
'nviews'                  => '$1 ret qaralğan',
'lonelypages'             => 'Eş bet siltemegen better',
'lonelypagestext'         => 'Kelesi betterge osı jobadağı basqa better siltemeýdi.',
'uncategorizedpages'      => 'Eş sanatqa kirmegen better',
'uncategorizedcategories' => 'Eş sanatqa kirmegen sanattar',
'uncategorizedimages'     => 'Eş sanatqa kirmegen swretter',
'unusedcategories'        => 'Paýdalanılmağan sanattar',
'unusedimages'            => 'Paýdalanılmağan faýldar',
'popularpages'            => 'Äýgili better',
'wantedcategories'        => 'Bastalmağan sanattar',
'wantedpages'             => 'Bastalmağan better',
'mostlinked'              => 'Eñ köp siltengen better',
'mostlinkedcategories'    => 'Eñ köp siltengen sanattar',
'mostcategories'          => 'Eñ köp sanattarğa kirgen better',
'mostimages'              => 'Eñ köp siltengen swretter',
'mostrevisions'           => 'Eñ köp tüzetilgen better',
'allpages'                => 'Barlıq bet tizimi',
'prefixindex'             => 'Bet bastaw tizimi',
'randompage'              => 'Kezdeýsoq bet',
'shortpages'              => 'Eñ qısqa better',
'longpages'               => 'Eñ ülken better',
'deadendpages'            => 'Eş betke siltemeýtin better',
'deadendpagestext'        => 'Kelesi better osı jobadağı basqa betterge siltemeýdi.',
'listusers'               => 'Barlıq qatıswşı tizimi',
'specialpages'            => 'Arnaýı better',
'spheading'               => 'Barşanıñ arnaýı betteri',
'restrictedpheading'      => 'Şektewli arnaýı better',
'recentchangeslinked'     => 'Qatıstı tüzetwler',
'rclsub'                  => '(«$1» betinen siltengen betterge)',
'newpages'                => 'Eñ jaña better',
'newpages-username'       => 'Qatıswşı atı:',
'ancientpages'            => 'Eñ eski better',
'intl'                    => 'Tilaralıq siltemeler',
'move'                    => 'Jıljıtw',
'movethispage'            => 'Betti jıljıtw',
'unusedimagestext'        => '<p>Eskertw: Basqa veb toraptar faýldıñ
URL jaýına tikeleý siltewi mümkin. Sondıqtan, belsendi paýdalanwına añğarmaý,
osı tizimde qalwı mümkin.</p>',
'unusedcategoriestext'    => 'Kelesi sanat better bar bolıp tur, biraq oğan eşqandaý bet, ne sanat kirmeýdi.',
'booksources'             => 'Kitap qaýnarları',
'categoriespagetext'      => 'Osında wïkïdegi barlıq sanattarınıñ tizimi berilip tur.',
'data'                    => 'Derekter',
'userrights'              => 'Qatıswşılar quqıqtarın meñgerw',
'groups'                  => 'Qatıswşı toptarı',
'booksourcetext'          => 'Tömende jaña jäne qoldanğan kitaptar satatın
toraptarınıñ siltemeleri tizimdelgen. Bul toraptarda izdelgen kitaptar
twralı bılaýğı aqparat bolwğa mümkin.',
'isbn'                    => 'ISBN belgisi',
'alphaindexline'          => '$1 — $2',
'version'                 => 'Jüýe nusqası',
'log'                     => 'Jwrnaldar',
'alllogstext'             => 'Birikken qotarw, joyw, qorğaw, buğattaw jäne äkimşilik jwrnaldarın körsetw.
Jwrnal türin, qatıswşı atın, tïisti betin talğap, tarıltıp qarawıñızğa boladı.',
'logempty'                => 'Jwrnalda säýkes danalar joq.',

# Special:Allpages
'nextpage'          => 'Kelesi betke ($1)',
'allpagesfrom'      => 'Mına betten bastap körsetw:',
'allarticles'       => 'Barlıq bet tizimi',
'allinnamespace'    => 'Barlıq bet ($1 esim ayası)',
'allnotinnamespace' => 'Barlıq bet ($1 esim ayasınan tıs)',
'allpagesprev'      => 'Aldıñğığa',
'allpagesnext'      => 'Kelesige',
'allpagessubmit'    => 'Ötw',
'allpagesprefix'    => 'Mınadan bastalğan betterdi körsetw:',
'allpagesbadtitle'  => 'Alınğan bet atawı jaramsız bolğan, nemese til-aralıq ne wïkï-aralıq bastawı bar boldı. Atawda qoldanwğa bolmaýtın nışandar bolwı mümkin.',

# Special:Listusers
'listusersfrom' => 'Mına qatıswşıdan bastap körsetw:',

# E-mail user
'mailnologin'     => 'E-poşta jaýı jiberilgen joq',
'mailnologintext' => 'Basqa qatıswşığa xat jiberw üşin
[[{{ns:special}}:Userlogin|kirwiñiz]] qajet, jäne [[{{ns:special}}:Preferences|baptawıñızda]]
kwälandırılğan e-poşta jaýı bolwı jön.',
'emailuser'       => 'Qatıswşığa xat jazw',
'emailpage'       => 'Qatıswşığa xat jiberw',
'emailpagetext'   => 'Eger bul qatıswşı baptawlarında kwälandırğan e-poşta
jaýın engizse, tömendegi ülgit arqılı buğan jalğız e-poşta xatın jiberwge boladı.
Qatıswşı baptawıñızda engizgen e-poşta jaýıñız
«Kimnen» degen bas jolağında körinedi, sondıqtan
xat alwşısı twra jawap bere aladı.',
'usermailererror' => 'Mail nısanı qate qaýtardı:',
'defemailsubject' => '{{SITENAME}} e-poştasınıñ xatı',
'noemailtitle'    => 'Bul e-poşta jaýı emes',
'noemailtext'     => 'Osı qatıswşı jaramdı E-poşta jaýın engizbegen,
nemese basqalardan xat qabıldawın öşirgen.',
'emailfrom'       => 'Kimnen',
'emailto'         => 'Kimge',
'emailsubject'    => 'Taqırıbı',
'emailmessage'    => 'Xat',
'emailsend'       => 'Jiberw',
'emailccme'       => 'Xatımdıñ köşirmesin mağan da jiber.',
'emailccsubject'  => '$1 degenge jiberilgen xatıñızdıñ köşirmesi: $2',
'emailsent'       => 'Xat jiberildi',
'emailsenttext'   => 'E-poşta xatıñız jiberildi.',

# Watchlist
'watchlist'            => 'Baqılawım',
'watchlistfor'         => "('''$1''' baqılawları)",
'nowatchlist'          => 'Baqılaw tizimiñizde eşbir dana joq',
'watchlistanontext'    => 'Baqılaw tizimiñizdegi danalardı qaraw, ne öñdew üşin $1 qajet.',
'watchlistcount'       => "'''Baqılaw tizimiñizde (talqılaw betterdi qosa) $1 dana bar.'''",
'clearwatchlist'       => 'Baqılaw tizimin tazalaw',
'watchlistcleartext'   => 'Solardı tolıq alastatwğa batılsız ba?',
'watchlistclearbutton' => 'Baqılaw tizimin tazalaw',
'watchlistcleardone'   => 'Baqılaw tizimiñiz tazartıldı. $1 dana alastatıldı.',
'watchnologin'         => 'Kirmegensiz',
'watchnologintext'     => 'Baqılaw tizimiñizdi özgertw üşin [[{{ns:special}}:Userlogin|kirwiñiz]] jön.',
'addedwatch'           => 'Baqılaw tizimine qosıldı',
'addedwatchtext'       => "«[[:$1]]» beti [[{{ns:special}}:Watchlist|baqılaw tizimiñizge]] qosıldı.
Osı bettiñ jäne sonıñ talqılaw betiniñ keleşektegi özgeristeri mında tizimdeledi.
Sonda bettiñ atawı tabwğa jeñildetip [[{{ns:special}}:Recentchanges|jwıqtağı özgerister tiziminde]]
'''jwan ärpimen''' körsetiledi.

Osı betti soñınan baqılaw tizimnen alastatıñız kelse «Baqılamaw» parağın nuqıñız.",
'removedwatch'         => 'Baqılaw tizimiñizden alastatıldı',
'removedwatchtext'     => '«[[:$1]]» beti baqılaw tizimiñizden alastatıldı.',
'watch'                => 'Baqılaw',
'watchthispage'        => 'Betti baqılaw',
'unwatch'              => 'Baqılamaw',
'unwatchthispage'      => 'Baqılawdı toqtatw',
'notanarticle'         => 'Mağlumat beti emes',
'watchnochange'        => 'Körsetilgen merzimde eşbir baqılanğan dana öñdelgen joq.',
'watchdetails'         => "* Baqılaw tiziminde (talqılaw betterisiz) '''$1''' bet bar.
* [[{{ns:special}}:Watchlist/edit|Bükil tizimdi qaraw jäne özgertw]].
* [[{{ns:special}}:Watchlist/clear|Tizimdegi barlıq dana alastatw]].",
'wlheader-enotif'      => '* Eskertw xat jiberwi endirilgen.',
'wlheader-showupdated' => "* Soñğı kirgenimnen beri tüzetilgen betterdi '''jwan''' mätinmen körset",
'watchmethod-recent'   => 'baqılawlı betterdiñ jwıqtağı özgeristerin tekserw',
'watchmethod-list'     => 'jwıqtağı özgeristerde baqılawlı betterdi tekserw',
'removechecked'        => 'Belgilengendi baqılaw tiziminen alastatw',
'watchlistcontains'    => 'Baqılaw tizimiñizde $1 bet bar.',
'watcheditlist'        => "Osında älippem surıptalğan baqılanğan mağlumat betteriñiz tizimdelingen.
Betterdi alastatw üşin onıñ qasındağı qabaşaqtardı belgilep, tömendegi ''Belgilengendi alastat'' tüýmesin nuqıñız
(mağlumat betin joýğanda talqılaw beti de birge joýıladı).",
'removingchecked'      => 'Suranğan danalardı baqılaw tizimnen alastawı…',
'couldntremove'        => '«$1» degen dana alastatılmadı…',
'iteminvalidname'      => '«$1» danasınıñ jaramsız atawınan şataq twdı…',
'wlnote'               => 'Tömende soñğı <b>$2</b> sağattağı, soñğı $1 özgeris körsetilgen.',
'wlshowlast'           => 'Soñğı $1 sağattağı, $2 kündegi, $3 bolğan özgeristi körsetw',
'wlsaved'              => 'Bul baqılw tizimiñizdiñ saqtalğan nusqası.',
'wlhideshowown'        => 'Tüzetwimdi $1',
'wlhideshowbots'       => 'Bottardı $1',
'wldone'               => 'İs bitti.',

'enotif_mailer'      => '{{SITENAME}} eskertw xat jiberw qızmeti',
'enotif_reset'       => 'Barlıq bet karaldi dep belgile',
'enotif_newpagetext' => 'Mınaw jaña bet.',
'changed'            => 'özgertti',
'created'            => 'jasadı',
'enotif_subject'     => '{{SITENAME}} jobasında $PAGEEDITOR $PAGETITLE atawlı betti $CHANGEDORCREATED',
'enotif_lastvisited' => 'Soñğı kirwiñizden beri bolğan özgerister üşin $1 degendi qarañız.',
'enotif_body'        => 'Qurmetti $WATCHINGUSERNAME,

{{SITENAME}} jobasıda $PAGEEDITDATE kezinde $PAGEEDITOR $PAGETITLE atawlı betti $CHANGEDORCREATED, ağımdıq nusqasın $PAGETITLE_URL jaýınan qarañız.

$NEWPAGE

Öñdewşi sïpattaması: $PAGESUMMARY $PAGEMINOREDIT

Öñdewşimen qatınasw:
e-poşta: $PAGEEDITOR_EMAIL
wïkï: $PAGEEDITOR_WIKI

Bılaýğı özgerister bolğanda da siz osı betke barğanşa deýin eşqandaý basqa eskertw xattar jiberilmeýdi. Sonımen qatar baqılaw tizimiñizdegi bet eskertpelik belgisin ädepke küýine keltiriñiz.

             Sizdiñ dostı {{SITENAME}} eskertw qızmeti

----
Baqılaw tizimiñizdi baptaw üşin, mında barıñız
{{fullurl:{{ns:special}}:Watchlist/edit}}

Sın-pikir berw jäne bılaýğı järdem alw üşin:
{{fullurl:{{ns:help}}:Mazmunı}}',

# Delete/protect/revert
'deletepage'                  => 'Betti joyw',
'confirm'                     => 'Rastaw',
'excontent'                   => 'bolğan mağlumatı: «$1»',
'excontentauthor'             => 'bolğan mağlumatı: «$1» (tek «[[Special:Contributions/$2|$2]]» ülesi)',
'exbeforeblank'               => 'tazartw aldındağı bolğan mağlumatı: «$1»',
'exblank'                     => 'bet bostı boldı',
'confirmdelete'               => 'Joywdı rastaw',
'deletesub'                   => '(«$1» joywı)',
'historywarning'              => 'Nazar salıñız: Joywğa arnalğan bette öz tarïxı bar:',
'confirmdeletetext'           => 'Betti nemese swretti barlıq tarïxımen
birge derekqordan ärdaýım joýığıñız keletin sïyaqtı.
Bunı joywdıñ zardabın tüsinip şın nïettengeniñizdi, jäne
[[{{ns:project}}:Sayasat]]qa laýıqtı dep
sengeniñizdi rastañız.',
'actioncomplete'              => 'Äreket bitti',
'deletedtext'                 => '«$1» joýıldı.
Jwıqtağı joywlar twralı jazbaların $2 degennen qarañız.',
'deletedarticle'              => '«[[$1]]» betin joýdı',
'dellogpage'                  => 'Joyw_jwrnalı',
'dellogpagetext'              => 'Tömende jwıqtağı joywlardıñ tizimi berilgen.',
'deletionlog'                 => 'joyw jwrnalı',
'reverted'                    => 'Erterek nusqasına qaýtarılğan',
'deletecomment'               => 'Joywdıñ sebebi',
'imagereverted'               => 'Erterek nusqasına qaýtarw sätti ötti.',
'rollback'                    => 'Tüzetwlerdi qaýtarw',
'rollback_short'              => 'Qaýtarw',
'rollbacklink'                => 'qaýtarw',
'rollbackfailed'              => 'Qaýtarw sätsiz ayaqtaldı',
'cantrollback'                => 'Tüzetw qaýtarılmaýdı. Bul bettiñ soñğı üleskeri tek bastawış awtorı.',
'alreadyrolled'               => '[[{{ns:user}}:$2|$2]] ([[{{ns:user_talk}}:$2|talqılawı]]) degendi jasağan [[$1]]
betiniñ soñğı öñdewinen qaýtarw ötpedi; keýbirew osı qazir betti öñdep ne qaýtarıp jatır tüge.

Soñğı öñdewdi [[{{ns:user}}:$3|$3]] ([[{{ns:user_talk}}:$3|talqılawı]]) degendi jasağan.',
'editcomment'                 => 'Tüzetwdiñ bolğan mändemesi: "<i>$1</i>".', # only shown if there is an edit comment
'revertpage'                  => '[[{{ns:special}}:Contributions/$2|$2]] ([[{{ns:user_talk}}:$2|talqılawı]]) tüzetwinen [[{{ns:user}}:$1|$1]] soñğı nusqasına qaýtardı',
'sessionfailure'              => 'Kirw sessïyasında şataq bolğan sïyaqtı;
sessïyağa şabwıldawdardan qorğanw üşin, osı äreket toqtatıldı.
«Artqa» tüýmesin basıñız, jäne betti keri jükteñiz, sosın qaýtalap köriñiz.',
'protectlogpage'              => 'Qorğaw_jwrnalı',
'protectlogtext'              => 'Tömende betterdiñ qorğaw/qorğamaw tizimi berilgen.',
'protectedarticle'            => '«$1» qorğaldı',
'unprotectedarticle'          => '«[[$1]]» qorğalmadı',
'protectsub'                  => '(«$1» qorğawda)',
'confirmprotecttext'          => 'Osı betti rasında da qorğaw qajet pe?',
'confirmprotect'              => 'Qorğawdı rastaw',
'protectmoveonly'             => 'Tek jıljıtwdan qorğaw',
'protectcomment'              => 'Qorğaw sebebi',
'unprotectsub'                => '(«$1» qorğamawda)',
'confirmunprotecttext'        => 'Osı betti rastan qorğamaw qajet pe?',
'confirmunprotect'            => 'Qorğamawdı rastaw',
'unprotectcomment'            => 'Qorğamaw sebebi',
'protect-unchain'             => 'Jıljıtwğa ruqsat berw',
'protect-text'                => '<strong>$1</strong> betiniñ qorğaw deñgeýin qaraý jäne özgerte alasız.',
'protect-viewtext'            => 'Tirkelgiñiz bet qorğanısı dengeýlerin özgertwge ruqsat bermeýdi.
Mına <strong>$1</strong> bettiñ ağımdıq baptawları:',
'protect-default'             => '(ädepki)',
'protect-level-autoconfirmed' => 'Tirkelgisiz paýdalanwşılarğa tïım',
'protect-level-sysop'         => 'Tek äkimşilerge ruqsat',

# Restrictions (nouns)
'restriction-edit' => 'Öñdew',
'restriction-move' => 'Jıljıtw',

# Undelete
'undelete'                 => 'Joýılğan betterdi qaraw',
'undeletepage'             => 'Joýılğan betterdi qaraw jäne qaýtarw',
'viewdeletedpage'          => 'Joýılğan betterdi qaraw',
'undeletepagetext'         => 'Kelesi better joýıldı dep belgilengen, biraq mağlumatı murağatta jatqan,
sondıqtan keri qaýtarwğa äzir. Murağat merzim boýınşa tazalanıp turwı mümkin.',
'undeleteextrahelp'        => "Bükil betti qaýtarw üşin, barlıq qabaşaqtardı bos qaldırıp
'''''Qaýtar!''''' tüýmesin nuqıñız. Bölekşe qaýtarw orındaw üşin, qaýtaraýın degen nusqalarına säýkes
qabaşaqtarın belgileñiz de, jäne '''''Qaýtar!''''' tüýmesin nuqıñız. '''''Tasta''''' tüýmesin
nuqığanda mändeme awmağı men barlıq qabaşaqtar tazalanadı.",
'undeletearticle'          => 'Joýılğan betti qaýtarw',
'undeleterevisions'        => '$1 nusqa murağattalğan',
'undeletehistory'          => 'Eger bet mağlumatın qaýtarsañız,tarïxında barlıq nusqalar da
qaýtarıladı. Eger joywdan soñ däl solaý atawımen jaña bet jasalsa, qaýtarılğan nusqalar
tarïxtıñ eñ adında körsetiledi, jäne körsetilip turğan bettiñ ağımdıq nusqası
özdik türde almastırılmaýdı.',
'undeletehistorynoadmin'   => 'Bul bet joýılğan. Joyw sebebi aldındağı öñdegen qatıswşılar
egjeý-tegjeýlerimen birge tömendegi sïpattamasında körsetilgen.
Osı joýılğan nusqalardıñ mätini tek äkimşilerge qatınawlı.',
'undeleterevision'         => '$1 kezindegi joýılğan nusqasın',
'undeleterevision-missing' => 'Jaramsız ne joğalğan nusqa. Siltemeñiz jaramsız bolwı mümkin, ne
nusqa qaýtarılğan tüge nemese murağattan alastatılğan.',
'undeletebtn'              => 'Qaýtar!',
'undeletereset'            => 'Tasta',
'undeletecomment'          => 'Mändemesi:',
'undeletedarticle'         => '«[[$1]]» qaýtardı',
'undeletedrevisions'       => '$1 nusqası qaýtarılğan',
'undeletedrevisions-files' => '$1 nusqa jäne $2 faýl qaýtarıldı',
'undeletedfiles'           => '$1 faýl qaýtarıldı',
'cannotundelete'           => 'Qaýtarw sätsiz bitti; tağı birew sizden burın sol betti qaýtarğan bolar.',
'undeletedpage'            => "<big>'''$1 qaýtarıldı'''</big>

Jwıqtağı joyw men qaýtarw jöninde [[{{ns:special}}:Log/delete|joyw jwrnalın]] qarañız.",

# Namespace form on various pages
'namespace' => 'Esim ayası:',
'invert'    => 'Talğawdı kerilew',

# Contributions
'contributions' => 'Qatıswşı ülesi',
'mycontris'     => 'Ülesim',
'contribsub'    => '$1 ülesi',
'nocontribs'    => 'Osı izdew şartına säýkes özgerister tabılğan joq.',
'ucnote'        => 'Tömende osı qatıswşınıñ soñğı <b>$2</b> kündegi, soñğı <b>$1</b> özgerisi körsetledi.',
'uclinks'       => 'Soñğı $2 kündegi, soñğı $1 özgerisin qaraw.',
'uctop'         => ' (üsti)',
'newbies'       => 'jaña qatıswşılar',

'sp-newimages-showfrom' => '$1 kezinen beri — jaña swretterdi körset',

'sp-contributions-newest'      => 'Eñ jañasına',
'sp-contributions-oldest'      => 'Eñ eskisine',
'sp-contributions-newer'       => 'Jañalaw $1',
'sp-contributions-older'       => 'Eskilew $1',
'sp-contributions-newbies-sub' => 'Jaña qatıswşılarğa',

# What links here
'whatlinkshere' => 'Siltegen better',
'notargettitle' => 'Aqırğı ataw joq',
'notargettext'  => 'Osı äreket orındalatın nısana bet,
ne qatıswşı körsetilmegen.',
'linklistsub'   => '(Siltemeler tizimi)',
'linkshere'     => "'''[[:$1]]''' degenge mına better silteýdi:",
'nolinkshere'   => "'''[[:$1]]''' degenge eş bet siltemeýdi.",
'isredirect'    => 'aýdatw beti',
'istemplate'    => 'kiriktirw',

# Block/unblock
'blockip'                     => 'Paýdalanwşını buğattaw',
'blockiptext'                 => 'Tömendegi ülgit paýdalanwşınıñ jazw ruqsatın
belgili IP jaýımen ne atawımen buğattaw üşin qoldanıladı.
Bunı tek buzaqılıqqa kedergi istew üşin jäne de
[[{{ns:project}}:Sayasat|sayasat]] boýınşa atqarwıñız jön.
Tömende tïisti sebebin toltırıp körsetiñiz (mısalı, däýekke buzaqılıqpen
özgertken betterdi keltirip).',
'ipaddress'                   => 'IP jaý',
'ipadressorusername'          => 'IP jaý ne qatıswşı atı',
'ipbexpiry'                   => 'Bitetin merzimi',
'ipbreason'                   => 'Sebebi',
'ipbanononly'                 => 'Tek tirkelgisizdi buğattaw',
'ipbcreateaccount'            => 'Tirkelgi jasawın kedergilew',
'ipbenableautoblock'          => 'Bul qatıswşınıñ qoldanğan soñğı IP jaýın, jäne ärqaýsı keýin tüzetw istewge ümiteligen jaýların özdik türde buğattaw',
'ipbsubmit'                   => 'Paýdalanwşını buğattaw',
'ipbother'                    => 'Basqa merzim',
'ipboptions'                  => '2 sağat:2 hours,1 kün:1 day,3 kün:3 days,1 apta:1 week,2 apta:2 weeks,1 aý:1 month,3 aý:3 months,6 aý:6 months,1 jıl:1 year,mängi:infinite',
'ipbotheroption'              => 'basqa',
'badipaddress'                => 'Jaramsız IP jaý',
'blockipsuccesssub'           => 'Buğattaw sätti ötti',
'blockipsuccesstext'          => '[[{{ns:special}}:Contributions/$1|$1]] degen buğattalğan.
<br />Buğattawlardı [[{{ns:special}}:Ipblocklist|IP buğattaw tiziminde]] qarap şığıñız.',
'unblockip'                   => 'Paýdalanwşını buğattamaw',
'unblockiptext'               => 'Tömendegi ülgit belgili IP jaýımen ne atawımen
burın buğattalğan paýdalanwşınıñ jazw ruqsatın qaýtarw üşin qoldanıladı.',
'ipusubmit'                   => 'Osı jaýdı buğattamaw',
'unblocked'                   => '[[{{ns:user}}:$1|$1]] buğattawı öşirildi',
'ipblocklist'                 => 'Buğattalğan paýdalanwşı / IP- jaý tizimi',
'blocklistline'               => '$1, $2 «$3» degendi buğattadı ($4)',
'infiniteblock'               => 'mängi',
'expiringblock'               => 'bitwi: $1',
'anononlyblock'               => 'tek tirkelgisizdi',
'noautoblockblock'            => 'özdik buğattaw öşirilengen',
'createaccountblock'          => 'tirkelgi jasawı buğattalğan',
'ipblocklistempty'            => 'Buğattaw tizimi bos.',
'blocklink'                   => 'buğattaw',
'unblocklink'                 => 'buğattamaw',
'contribslink'                => 'ülesi',
'autoblocker'                 => "IP jaýıñızdı jwıqta «[[{{ns:user}}:1|$1]]» paýdalanğan, sondıqtan özdik türde buğattalğan. $1 buğattaw sebebi: «'''$2'''».",
'blocklogpage'                => 'Buğattaw_jwrnalı',
'blocklogentry'               => '«[[$1]]» buğattadı, bitetin merzimi: $2',
'blocklogtext'                => 'Bul paýdalanwşılardı buğattaw/buğattamaw äreketteriniñ jwrnalı. Özdik türde
buğattalğan IP jaýlar osında tizimdelgemegen. Ağımdağı belsendi buğattawların
[[{{ns:special}}:Ipblocklist|IP buğattaw tiziminen]] qarawğa boladı.',
'unblocklogentry'             => '«$1» buğattawın öşirdi',
'range_block_disabled'        => 'Awqım buğattawın jasaw äkimşilik mümkindigi öşirilgen.',
'ipb_expiry_invalid'          => 'Bitetin waqıtı jaramsız.',
'ipb_already_blocked'         => '«$1» buğattalğan tüge',
'ip_range_invalid'            => 'IP jaý awqımı jaramsız.',
'proxyblocker'                => 'Proksï serverlerdi buğattawış',
'ipb_cant_unblock'            => 'Qate: IP $1 buğattawı tabılmadı. Onıñ buğattawı öşirlgen sïyaqtı.',
'proxyblockreason'            => 'IP jaýıñız aşıq proksï serverge jatatındıqtan buğattalğan. Ïnternet qızmetin jabdıqtawşıñızben, ne texnïkalıq medew qızmetimen qatınasıñız, jäne olarğa osı ote kürdeli qawıpsizdik şataq twralı aqparat beriñiz.',
'proxyblocksuccess'           => 'Bitti.',
'sorbs'                       => 'DNSBL qara tizimi',
'sorbsreason'                 => 'Sizdiñ IP jaýıñız osı torapta qoldanılğan DNSBL qara tizimindegi aşıq proksï-server dep tabıladı.',
'sorbs_create_account_reason' => 'Sizdiñ IP jaýıñız osı torapta qoldanılğan DNSBL qara tizimindegi aşıq proksï-server dep tabıladı. Tirkelgi jasaý almaýsız.',

# Developer tools
'lockdb'              => 'Derekqordı qulıptaw',
'unlockdb'            => 'Derekqordı qulıptamaw',
'lockdbtext'          => 'Derekqordın qulıptalwı barlıq paýdalanwşınıñ
bet öñdew, baptawın qalaw, baqılaw tizimin, tağı basqa
derekqordı özgertetin mümkindikterin toqtata turadı.
Osı maqsatıñızdı, jäne jöndewiñiz bitkende
derekqordı aşatıñızdı rastañız.',
'unlockdbtext'        => 'Derekqodın aşılwı barlıq paýdalanwşınıñ bet öñdew,
baptawın qalaw, baqılaw tizimin, tağı basqa derekqordı özgertetin
mümkindikterin qaýta aşadı.
Osı maqsatıñızdı rastañız.',
'lockconfirm'         => 'Ïä, men derekqordı rastan qulıptaýmın.',
'unlockconfirm'       => 'Ïä, men derekqordı rastan qulıptamaýmın.',
'lockbtn'             => 'Derekqordı qulıpta',
'unlockbtn'           => 'Derekqordı qulıptama',
'locknoconfirm'       => 'Rastaw belgisin qoýmapsız.',
'lockdbsuccesssub'    => 'Derekqordı qulıptaw sätti ötti',
'unlockdbsuccesssub'  => 'Qulıptalğan derekqor aşıldı',
'lockdbsuccesstext'   => 'Derekqor qulıptaldı.
<br />Jöndewiñiz bitkennen keýin [[{{ns:special}}:Unlockdb|qulıptawın öşirwge]] umıtpañız.',
'unlockdbsuccesstext' => 'Qulıptalğan derekqor sätti aşıldı.',
'lockfilenotwritable' => 'Derekqor qulıptaw faýlı jazılmaýdı. Derekqordı qulıptaw ne aşw üşin, veb-server faýlğa jazw ruqsatı bolw qajet.',
'databasenotlocked'   => 'Derekqor qulıptalğan joq.',

# Make sysop
'makesysoptitle'     => 'Qatıswşını äkimşi qılw',
'makesysoptext'      => 'Bul ülgitti qarapaýım qatıswşını äkimşi qılw üşin töreşiler qoldanadı.
Jolaqqa qatıswşı atın engiziñiz de, jäne bul qatıswşını äkimşi qılw üşin, tüýmeni basıñız.',
'makesysopname'      => 'Qatıswşı atı:',
'makesysopsubmit'    => 'Bul qatıswşını äkimşi qıl',
'makesysopok'        => '<b>«$1» degen qatıswşı endi äkimşi bop tağaýındaldı</b>',
'makesysopfail'      => '<b>«$1» degen qatıswşı äkimşi bop tağaýındalmadı. (Atın durıs engizdiñiz be?)</b>',
'setbureaucratflag'  => 'Qatıswşını töreşi qılw',
'rightslog'          => 'Qatıswşı_quqıqtarı_jwrnalı',
'rightslogtext'      => 'Bul paýdalanwşı quqıqtarın özgertw jwrnalı.',
'rightslogentry'     => ' $1 top müşelgin $2 degennen $3 degenge özgertti',
'rights'             => 'Quqıqtarı:',
'set_user_rights'    => 'Qatıswşı quqıqtarın tağaýındaw',
'user_rights_set'    => '<b>«$1» degen qatıswşınıñ quqıqtarı jañartıldı</b>',
'set_rights_fail'    => '<b>«$1» degen qatıswşınıñ quqıqtarı tağaýındalmadı. (Atın durıs engizdiñiz be?)</b>',
'makesysop'          => 'Qatıswşını äkimşi qılw',
'already_sysop'      => 'Bul qatıswşı äkimşi boptı tüge',
'already_bureaucrat' => 'Bul qatıswşı toreşi boptı tüge',
'rightsnone'         => '(eşqandaý)',

# Move page
'movepage'                => 'Betti jıljıtw',
'movepagetext'            => "Tömendegi ülgitti qoldanıp betterdi qaýta ataýdı,
barlıq tarïxın jaña atawğa jıljıtadı.
Burınğı bet atawı jaña atawğa aýdatatın bet boladı.
Eski atawına silteýtin  siltemeler özgertilmeýdi; jıljıtwdan soñ
şınjırlı aýdatwlar bar-joğın tekseriñiz.
Siltemeler burınğı joldawımen bılaýğı ötwin tekserwine
siz mindetti bolasız.

Eskeriñiz, eger jıljıtılatın atawda bet bolsa, sol eski betke aýdatw
bolğanşa jäne tarïxı bolsa, bet '''jıljıtılmaýdı'''.
Osınıñ mağınası: eger betti qatelik pen qaýta atalsa,
burınğı atawına qaýta atawğa boladı,
biraq bar bettiñ üstine jazwğa bolmaýdı.

<b>NAZAR SALIÑIZ!</b>
Bul däripti betke qatañ jäne kenet özgeris jasawğa mümkin;
ärekettiñ aldınan osınıñ zardaptarın tüsingeniñizge batıl
bolıñız.",
'movepagetalktext'        => "Kelesi sebepter '''bolğanşa''' deýin, talqılaw beti özdik türde birge jıljıtıladı:
* Bos emes talqılaw beti jaña atawda bolğanda, nemese
* Tömendegi qabışaqta belgini alastatqanda.

Osı oraýda, qalawıñız bolsa, betti qoldan jıljıta ne qosa alasız.",
'movearticle'             => 'Betti jıljıtw',
'movenologin'             => 'Jüýege kirmegensiz',
'movenologintext'         => 'Betti jıljıtw üşin tirkelgen bolwıñız jäne
 [[{{ns:special}}:Userlogin|kirwiñiz]] qajet.',
'newtitle'                => 'Jaña atawğa',
'movepagebtn'             => 'Betti jıljıt',
'pagemovedsub'            => 'Jıljıtw sätti ayaqtaldı',
'pagemovedtext'           => '«[[$1]]» beti «[[$2]]» betine jıljıtıldı.',
'articleexists'           => 'Bılaý atawlı bet bar boldı, ne tañdağan
atawıñız jaramdı emes.
Basqa ataw tandañız',
'talkexists'              => "'''Bettiñ özi sätti jıljıtıldı, biraq talqılaw beti birge jıljıtılmadı, onıñ sebebi jaña atawdıñ talqılaw beti bar tüge. Bunı qolmen qosıñız.'''",
'movedto'                 => 'mınağan jıljıtıldı:',
'movetalk'                => 'Qatıstı talqılaw betimen birge jıljıtw',
'talkpagemoved'           => 'Qatıstı talqılaw beti de jıljıtıldı.',
'talkpagenotmoved'        => 'Qatıstı talqılaw beti <strong>jıljıtılmadı</strong>.',
'1movedto2'               => '«[[$1]]» betinde aýdatw qaldırıp «[[$2]]» betine jıljıttı',
'1movedto2_redir'         => '«[[$1]]» betin «[[$2]]» aýdatw betiniñ üstine jıljıttı',
'movelogpage'             => 'Jıljıtw jwrnalı',
'movelogpagetext'         => 'Tömende jıljıtılğan betterdiñ tizimi berilip tur.',
'movereason'              => 'Sebebi',
'revertmove'              => 'qaýtarw',
'delete_and_move'         => 'Joyw jäne jıljıtw',
'delete_and_move_text'    => '==Joyw qajet==

Aqırğı «[[$1]]» bet atawı bar tüge.
Jıljıtwğa jol berw üşin joyamız ba?',
'delete_and_move_confirm' => 'Ïä, osı betti joý',
'delete_and_move_reason'  => 'Jıljıtwğa jol berw üşin joýılğan',
'selfmove'                => 'Qaýnar jäne aqırğı atawı birdeý; bet özine jıljıtılmaýdı.',
'immobile_namespace'      => 'Qaýnar nemese aqırğı atawı arnaýı türinde boldı; osındaý esim ayası jağına jäne jağınan better jıljıtılmaýdı.',

# Export
'export'          => 'Betterdi sırtqa berw',
'exporttext'      => 'XML pişimine qaptalğan bölek bet ne better bwması
mätiniñ jäne öñdew tarïxın sırtqa bere alasız. Osını, basqa wïkï-ge
{{ns:special}}:Import page MediaWiki quralı arqılı, sırttan alwğa boladı.

Betterdi sırtqa berw üşin, atawların tömendegi mätin awmağına engiziñiz,
bir jolda bir ataw, jäne tandañız: ne ağımdıq nusqasın, barlıq eski nusqaları men
jäne tarïxı joldarı men birge, ne däl ağımdıq nusqasın, soñğı öñdew twralı aqparatı men birge.

Soñğı jağdaýda siltemeni de qoldanwğa boladı, mısalı {{int:mainpage}} beti üşin [[{{ns:Special}}:Export/{{int:mainpage}}]].',
'exportcuronly'   => 'Tolıq tarïxın emes, tek ağımdıq nusqasın kiristiriñiz',
'exportnohistory' => "----
'''Añğartpa:''' Önimdilik äseri sebepterinen, better tolıq tarïxın sırtqa berwi öşirilgen.",
'export-submit'   => 'Sırtqa ber',

# Namespace 8 related
'allmessages'               => 'Jüýe xabarları',
'allmessagesname'           => 'Atawı',
'allmessagesdefault'        => 'Ädepki mätini',
'allmessagescurrent'        => 'Ağımdıq mätini',
'allmessagestext'           => 'Mında «MediaWiki:» esim ayasındağı barlıq qatınawlı jüýe xabar tizimi berilip tur.',
'allmessagesnotsupportedUI' => 'Your current interface language <b>$1</b> is not supported by Special:Allmessages at this site.',
'allmessagesnotsupportedDB' => "'''wgUseDatabaseMessages''' babı öşirilgen sebebinen '''{{ns:special}}:AllMessages''' sïpatı süemeldenbeýdi.",
'allmessagesfilter'         => 'Xabardı atawı boýınşa süzgilew:',
'allmessagesmodified'       => 'Tek özgertilgendi körset',

# Thumbnails
'thumbnail-more'  => 'Ülkeýtw',
'missingimage'    => '<b>Joğalğan swret </b><br /><i>$1</i>',
'filemissing'     => 'Joğalğan faýl',
'thumbnail_error' => 'Nobaý qurw qatesi: $1',

# Special:Import
'import'                     => 'Betterdi sırttan alw',
'importinterwiki'            => 'Wïkï-tasımaldap sırttan alw',
'import-interwiki-text'      => 'Sırttan alatın wïkï jobasın jäne bet atawın tandañız.
Nusqa kün-aýı jäne öñdewşi attarı saqtaladı.
Barlıq wïkï-tasımaldap sırttan alw äreketter [[{{ns:special}}:Log/import|sırttan alw jwrnalına]] jazılıp alınadı.',
'import-interwiki-history'   => 'Osı bettiñ barlıq tarïxï nusqaların köşirw',
'import-interwiki-submit'    => 'Sırttan alw',
'import-interwiki-namespace' => 'Mına esim ayasına betterdi tasımaldaw:',
'importtext'                 => 'Qaýnar wïkïden «Special:Export» qwralın qoldanıp, faýldı sırtqa beriñiz, dïskiñizge saqtañız, sosın mında qotarıñız.',
'importstart'                => 'Betterdi sırttan alwı…',
'import-revision-count'      => '$1 nusqa',
'importnopages'              => 'Sırttan alınatın better joq.',
'importfailed'               => 'Sırttan alw sätsiz bitti: $1',
'importunknownsource'        => 'Cırttan alw qaýnar türi tanımalsız',
'importcantopen'             => 'Sırttan alw faýlı aşılmaýdı',
'importbadinterwiki'         => 'Jaramsız wïkï-aralıq silteme',
'importnotext'               => 'Bostı, ne mätini joq',
'importsuccess'              => 'Sırttan alw sätti ayaqtaldı!',
'importhistoryconflict'      => 'Tarïxınıñ eges nusqaları bar (bul betti aldında sırttan alınğan sïyaqtı)',
'importnosources'            => 'Eşqandaý wïkï-tasımaldap sırttan alw qaýnarı belgilenmegen, jäne tarïxın tikeleý qotarwı öşirilgen.',
'importnofile'               => 'Sırttan alınatın faýl qotarılğan joq.',
'importuploaderror'          => 'Sırttan alw faýldıñ qotarwı sätsiz bitti; osı faýl mölşeri ruqsat etilgen mölşerden aswı mümkin.',

# Import log
'importlogpage'                    => 'Sırttan alw jwrnalı',
'importlogpagetext'                => 'Basqa wïkïlerden öñdew tarïxımen birge betterdi äkimşilik retinde sırttan alw.',
'import-logentry-upload'           => 'faýl qotarwımen sırttan «[[$1]]» beti alındı',
'import-logentry-upload-detail'    => '$1 nusqa',
'import-logentry-interwiki'        => 'wïkï-tasımaldanğan $1',
'import-logentry-interwiki-detail' => '$2 degennen $1 nusqa',

# Keyboard access keys for power users
'accesskey-search'                  => 'f',
'accesskey-minoredit'               => 'i',
'accesskey-save'                    => 's',
'accesskey-preview'                 => 'p',
'accesskey-diff'                    => 'v',
'accesskey-compareselectedversions' => 'v',
'accesskey-watch'                   => 'w',

# Tooltip help for some actions, most are in Monobook.js
'tooltip-search'                  => '{{SITENAME}} jobasınan izdestirw [alt-f]',
'tooltip-minoredit'               => 'Osını şağın tüzetw dep belgilew [alt-i]',
'tooltip-save'                    => 'Tüzetwiñizdi saqtaw [alt-s]',
'tooltip-preview'                 => 'Saqtawdıñ aldınan tüzetwiñizdi qarap şığıñız! [alt-p]',
'tooltip-diff'                    => 'Mätinge qandaý özgeristerdi jasağanıñızdı qaraw. [alt-v]',
'tooltip-compareselectedversions' => 'Bettiñ eki nusqasınıñ aýırmasın qaraw. [alt-v]',
'tooltip-watch'                   => 'Bul betti baqılaw tizimiñizge üstew [alt-w]',

# Stylesheets
'Common.css'   => '/** Mındağı CSS ämirleri barlıq bezendirw mänerinderde qoldanıladı */',
'Monobook.css' => '/* Mındağı CSS ämirleri «Dara kitap» bezendirw mänerin paýdalanwşılarğa äser etedi */',

# Metadata
'nodublincore'      => 'Osı serverge «Dublin Core RDF» meta-derekteri öşirilgen.',
'nocreativecommons' => 'Osı serverge «Creative Commons RDF» meta-derekteri öşirilgen.',
'notacceptable'     => 'Osı wïkï serveri sizdiñ «paýdalanwşı äreketkişi» oqï alatın pişimi bar derekterdi jibere almaýdı.',

# Attribution
'anonymous'        => '{{SITENAME}} tirkelgisiz paýdalanwşı(lar)',
'siteuser'         => '{{SITENAME}} qatıswşı $1',
'lastmodifiedatby' => 'Bul betti $3 qatıswşı soñğı özgertken kezi: $2, $1.', # $1 date, $2 time, $3 user
'and'              => 'jäne',
'othercontribs'    => 'Şığarma negizin $1 jazğan.',
'others'           => 'basqalar',
'siteusers'        => '{{SITENAME}} qatıswşı(lar) $1',
'creditspage'      => 'Betti jazğandar',
'nocredits'        => 'Bul betti jazğandar twralı aqparat joq.',

# Spam protection
'spamprotectiontitle'    => '«Spam»-nan qorğaýtın süzgi',
'spamprotectiontext'     => 'Bul bettiñ saqtawın «spam» süzgisi buğattadı. Bunıñ sebebi sırtqı torap siltemesinen bolwı mümkin.',
'spamprotectionmatch'    => 'Kelesi «spam» mätini süzgilingen: $1',
'subcategorycount'       => 'Bul sanatta {{PLURAL:$1|bir|$1}} tömengi sanat bar.',
'categoryarticlecount'   => 'Bul sanatta {{PLURAL:$1|bir|$1}} bet bar.',
'listingcontinuesabbrev' => ' (jalğ.)',
'spambot_username'       => 'MediaWiki spam cleanup',
'spam_reverting'         => '$1 degenge siltemesi joq soñğı nusqasına qaýtarıldı',
'spam_blanking'          => '$1 degenge siltemesi bar barlıq nusqalar tazartıldı',

# Info page
'infosubtitle'   => 'Bet twralı aqparat',
'numedits'       => 'Tüzetw sanı (negizgi beti): $1',
'numtalkedits'   => 'Tüzetw sanı (talqılaw beti): $1',
'numwatchers'    => 'Baqılawşı sanı: $1',
'numauthors'     => 'Ärtürli awtorlar sanı (negizgi beti): $1',
'numtalkauthors' => 'Ärtürli awtor sanı (talqılaw beti): $1',

# Math options
'mw_math_png'    => 'Ärqaşan PNG türimen körset',
'mw_math_simple' => 'Kädimgi bolsa HTML pişimimen, basqaşa PNG türimen',
'mw_math_html'   => 'Iqtïmal bolsa HTML pişimimen, basqaşa PNG türimen',
'mw_math_source' => 'TeX pişiminde qaldırw (mätindik şolğıştarına)',
'mw_math_modern' => 'Osı zamannıñ şolğıştarına usınılğan',
'mw_math_mathml' => 'Iqtïmal bolsa MathML pşimimen (sınaq türinde)',

# Patrolling
'markaspatrolleddiff'        => 'Küzette dep belgilew',
'markaspatrolledtext'        => 'Osı betti küzetwde dep belgilew',
'markedaspatrolled'          => 'Küzette dep belgilendi',
'markedaspatrolledtext'      => 'Talğanğan nusqa küzette dep belgilendi.',
'rcpatroldisabled'           => 'Jwıqtağı özgerister Küzeti öşirilgen',
'rcpatroldisabledtext'       => 'Jwıqtağı özgerister Küzeti qasïeti ağımda öşirilgen.',
'markedaspatrollederror'     => 'Küzette dep belgilenbeýdi',
'markedaspatrollederrortext' => 'Küzette dep belgilew üşin nusqasın engiziñiz.',

# Monobook.js: tooltips and access keys for monobook
'Monobook.js' => "/* tooltips and access keys */
var ta = new Object();
ta['pt-userpage'] = new Array('.','Jeke betim');
ta['pt-anonuserpage'] = new Array('.','Osı IP jaýdıñ jeke beti');
ta['pt-mytalk'] = new Array('n','Talqılaw betim');
ta['pt-anontalk'] = new Array('n','Osı IP jaý tüzetwlerin talqılaw');
ta['pt-preferences'] = new Array('','Baptawım');
ta['pt-watchlist'] = new Array('l','Özgeristerin baqılap turğan better tizimim.');
ta['pt-mycontris'] = new Array('y','Ülesterimdiñ tizimi');
ta['pt-login'] = new Array('o','Kirwiñizdi usınamız, ol mindetti emes.');
ta['pt-anonlogin'] = new Array('o','Kirwiñizdi usınamız, biraq, ol mindetti emes.');
ta['pt-logout'] = new Array('o','Şığw');
ta['ca-talk'] = new Array('t','Mağlumat betti talqılaw');
ta['ca-edit'] = new Array('e','Bul betti öñdeý alasız. Saqtawdıñ aldında «Qarap şığw» tüýmesin nuqıñız.');
ta['ca-addsection'] = new Array('+','Bul talqılaw betinde jaña taraw bastaw.');
ta['ca-viewsource'] = new Array('e','Bul bet qorğalğan, biraq, qaýnarın qarawğa boladı.');
ta['ca-history'] = new Array('h','Bul bettin jwıqtağı nusqaları.');
ta['ca-protect'] = new Array('=','Bul betti qorğaw');
ta['ca-unprotect'] = new Array('=','Bul betti qorğamaw');
ta['ca-delete'] = new Array('d','Bul betti joyw');
ta['ca-undelete'] = new Array('d','Bul bettiñ joywdıñ aldındağı bolğan tüzetwlerin qaýtarw');
ta['ca-move'] = new Array('m','Bul betti jıljıtw');
ta['ca-nomove'] = new Array('m','Bul betti jıljıtwğa ruqsatıñız joq');
ta['ca-watch'] = new Array('w','Bul betti baqılaw tizimiñizge üstew');
ta['ca-unwatch'] = new Array('w','Bul betti baqılaw tizimiñizden alastatw');
ta['ca-varlang-0'] = new Array('','Kïrïll jazwı');
ta['ca-varlang-1'] = new Array('','Latın jazwı');
ta['ca-varlang-2'] = new Array('','Arab jazwı');
ta['search'] = new Array('f','Osı wïkïden izdew');
ta['p-logo'] = new Array('','Bastı betke');
ta['n-mainpage'] = new Array('z','Bastı betke barıp ketiñiz');
ta['n-portal'] = new Array('','Joba twralı, ne istewiñizge bolatın, qaýdan tabwğa bolatın twralı');
ta['n-currentevents'] = new Array('','Ağımdağı oqïğalarğa qatıstı aqparat');
ta['n-recentchanges'] = new Array('r','Osı wïkïdegi jwıqtağı özgerister tizimi.');
ta['n-randompage'] = new Array('x','Kezdeýsoq betti jüktew');
ta['n-help'] = new Array('','Anıqtama tabw ornı.');
ta['n-sitesupport'] = new Array('','Bizge järdem etiñiz');
ta['t-whatlinkshere'] = new Array('j','Mında siltegen barlıq betterdiñ tizimi');
ta['t-recentchangeslinked'] = new Array('k','Mınnan siltengen betterdiñ jwıqtağı özgeristeri');
ta['feed-rss'] = new Array('','Bul bettiñ RSS arnası');
ta['feed-atom'] = new Array('','Bul bettiñ Atom arnası');
ta['t-contributions'] = new Array('','Osı qatıswşınıñ üles tizimin qaraw');
ta['t-emailuser'] = new Array('','Osı qatıswşığa email jiberw');
ta['t-upload'] = new Array('u','Swret ne medïa faýldarın qotarw');
ta['t-specialpages'] = new Array('q','Barlıq arnaýı better tizimi');
ta['t-print'] = new Array('','Osı bettiñ basıp şığarw nusqası');
ta['t-permalink'] = new Array('','Bettiñ osı nusqasınıñ turaqtı siltemesi');
ta['ca-nstab-main'] = new Array('c','Mağlumat betin qaraw');
ta['ca-nstab-user'] = new Array('c','Qatıswşı betin qaraw');
ta['ca-nstab-media'] = new Array('c','Taspa betin qaraw');
ta['ca-nstab-special'] = new Array('','Bul arnaýı bet, bettiñ özi öñdelinbeýdi.');
ta['ca-nstab-project'] = new Array('a','Joba betin qaraw');
ta['ca-nstab-image'] = new Array('c','Swret betin qaraw');
ta['ca-nstab-mediawiki'] = new Array('c','Jüýe xabarın qaraw');
ta['ca-nstab-template'] = new Array('c','Ülgini qaraw');
ta['ca-nstab-help'] = new Array('c','Anıqtıma betin qaraw');
ta['ca-nstab-category'] = new Array('c','Sanat betin qaraw');",

# Common.js: contains nothing but a placeholder comment
'Common.js' => '/* Mındağı kez kelgen JavaScript ämirleri ärqaýsı bet jüktelgende barlıq paýdalanwşılarğa jükteledi. */

// BEGIN workaround for RTL
if (wgUserLanguage == "kk-cn"){
  document.direction="rtl";
  document.write(\'<style type="text/css">html {direction: rtl;}</style>\');
  document.write(\'<link rel="stylesheet" type="text/css" href="\'+stylepath+\'/common/common_rtl.css">\');
  document.write(\'<link rel="stylesheet" type="text/css" href="\'+stylepath+\'/\'+skin+\'/rtl.css">\');
}
// END workaround for RTL',

# Image deletion
'deletedrevision' => 'Mına eski nusqasın joýdı: $1.',

# Browsing diffs
'previousdiff' => '← Aldıñğımen aýırması',
'nextdiff'     => 'Kelesimen aýırması →',

'imagemaxsize' => 'Swret tüýindeme betindegi swrettiñ mölşerin şektewi:',
'thumbsize'    => 'Nobaý mölşeri:',
'showbigimage' => 'Joğarı ajıratılımdı ($1×$2, $3 kB) nusqasın jüktew',

'newimages'    => 'Eñ jaña faýldar qoýması',
'showhidebots' => '(bottardı $1)',
'noimages'     => 'Köretin eşteñe joq.',

# Variants for Kazakh language
'variantname-kk-tr' => 'Qazaq',
'variantname-kk-kz' => 'Қазақ',
'variantname-kk-cn' => 'قازاق',
'variantname-kk'    => 'disable',

# Labels for User: and Title: on Special:Log pages
'specialloguserlabel'  => 'Qatıswşı:',
'speciallogtitlelabel' => 'Ataw:',

'passwordtooshort' => 'Qupïya söziñiz tım qısqa. Eñ keminde $1 ärip bolwı qajet.',

# Media Warning
'mediawarning' => "'''Nazar salıñız''': Bul faýl türinde qaskünemdi ämirdiñ bar bolwı ıqtïmal; faýldı jegip jüýeñizge zïyan keltirwiñiz mümkin.<hr />",

'fileinfo' => '$1 kB, MIME türi: <code>$2</code>',

# Metadata
'metadata'          => 'Meta-derekteri',
'metadata-help'     => 'Osı faýlda qosımşa aqparat bar. Bälkim, osı aqparat faýldı jasap şığarw, ne sandılaw üşin paýdalanğan sandıq kamera, ne mätinalğırdan alınğan. Eger osı faýl negizgi küýinen özgertilgen bolsa, keýbir ejeleleri özgertilgen fotoswretke laýıq bolmas.',
'metadata-expand'   => 'Egjeý-tegjeýin körset',
'metadata-collapse' => 'Egjeý-tegjeýin jasır',
'metadata-fields'   => 'Osı xabarda tizimdelgen EXIF meta-derek awmaqtarı,
swret beti körsetw kezinde meta-derek keste jasırılığanda kiristirledi.
Basqası ädepkiden jasırıladı.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Eni',
'exif-imagelength'                 => 'Bïiktigi',
'exif-bitspersample'               => 'Quraş saýın bït sanı',
'exif-compression'                 => 'Qısım sulbası',
'exif-photometricinterpretation'   => 'Pïksel qïıswı',
'exif-orientation'                 => 'Megzewi',
'exif-samplesperpixel'             => 'Quraş sanı',
'exif-planarconfiguration'         => 'Derek rettewi',
'exif-ycbcrsubsampling'            => 'Y quraşınıñ C quraşına jarnaqtawı',
'exif-ycbcrpositioning'            => 'Y quraşı jäne C quraşı mekendewi',
'exif-xresolution'                 => 'Dereleý ajıratılımdığı',
'exif-yresolution'                 => 'Tireleý ajıratılımdığı',
'exif-resolutionunit'              => 'X jäne Y ajıratılımdıqtarığınıñ ölşemi',
'exif-stripoffsets'                => 'Swret dererekteriniñ jaýğaswı',
'exif-rowsperstrip'                => 'Beldik saýın jol sanı',
'exif-stripbytecounts'             => 'Qısımdalğan beldik saýın baýt sanı',
'exif-jpeginterchangeformat'       => 'JPEG SOI degennen ığıswı',
'exif-jpeginterchangeformatlength' => 'JPEG derekteriniñ baýt sanı',
'exif-transferfunction'            => 'Tasımaldaw fwnkcïyası',
'exif-whitepoint'                  => 'Aq nükte tüstiligi',
'exif-primarychromaticities'       => 'Alğı şeptegi tüstilikteri',
'exif-ycbcrcoefficients'           => 'Tüs ayasın tasımaldaw matrïcalıq eselikteri',
'exif-referenceblackwhite'         => 'Qara jäne aq anıqtawış qos kolemderi',
'exif-datetime'                    => 'Faýldıñ özgertilgen kün-aýı',
'exif-imagedescription'            => 'Swret atawı',
'exif-make'                        => 'Kamera öndirwşisi',
'exif-model'                       => 'Kamera ülgisi',
'exif-software'                    => 'Qoldanılğan bağdarlama',
'exif-artist'                      => 'Jığarmaşısı',
'exif-copyright'                   => 'Jığarmaşılıq quqıqtar ïesi',
'exif-exifversion'                 => 'Exif nusqası',
'exif-flashpixversion'             => 'Süýemdelingen Flashpix nusqası',
'exif-colorspace'                  => 'Tüs ayası',
'exif-componentsconfiguration'     => 'Ärqaýsı quraş mäni',
'exif-compressedbitsperpixel'      => 'Swret qısımdaw tärtibi',
'exif-pixelydimension'             => 'Swrettiñ jaramdı eni',
'exif-pixelxdimension'             => 'Swrettiñ jaramdı bïiktigi',
'exif-makernote'                   => 'Öndirwşi eskertpeleri',
'exif-usercomment'                 => 'Paýdalanwşı mändemeleri',
'exif-relatedsoundfile'            => 'Qatıstı dıbıs faýlı',
'exif-datetimeoriginal'            => 'Jasalğan kezi',
'exif-datetimedigitized'           => 'Sandıqtaw kezi',
'exif-subsectime'                  => 'Jasalğan keziniñ sekwnd bölşekteri',
'exif-subsectimeoriginal'          => 'Tüpnusqa keziniñ sekwnd bölşekteri',
'exif-subsectimedigitized'         => 'Sandıqtaw keziniñ sekwnd bölşekteri',
'exif-exposuretime'                => 'Ustalım waqıtı',
'exif-exposuretime-format'         => '$1 s ($2)',
'exif-fnumber'                     => 'Sañılaw mölşeri',
'exif-fnumber-format'              => 'f/$1',
'exif-exposureprogram'             => 'Ustalım bağdarlaması',
'exif-spectralsensitivity'         => 'Spektr boýınşa sezgiştigi',
'exif-isospeedratings'             => 'ISO jıldamdıq jarnaqtawı (jarıq sezgiştigi)',
'exif-oecf'                        => 'Optoelektrondı türletw ıqpalı',
'exif-shutterspeedvalue'           => 'Japqış jıldamdılığı',
'exif-aperturevalue'               => 'Sañılawlıq',
'exif-brightnessvalue'             => 'Aşıqtıq',
'exif-exposurebiasvalue'           => 'Ustalım ötemi',
'exif-maxaperturevalue'            => 'Barınşa sañılaw aşwı',
'exif-subjectdistance'             => 'Nısana qaşıqtığı',
'exif-meteringmode'                => 'Ölşew tärtibi',
'exif-lightsource'                 => 'Jarıq közi',
'exif-flash'                       => 'Jarqıldağış',
'exif-focallength'                 => 'Şoğırlaw alşaqtığı',
'exif-focallength-format'          => '$1 mm',
'exif-subjectarea'                 => 'Nısana awqımı',
'exif-flashenergy'                 => 'Jarqıldağış qarqını',
'exif-spatialfrequencyresponse'    => 'Keñistik-jïilik äserşiligi',
'exif-focalplanexresolution'       => 'X boýınşa şoğırlaw jaýpaqtıqtıñ ajıratılımdığı',
'exif-focalplaneyresolution'       => 'Y boýınşa şoğırlaw jaýpaqtıqtıñ ajıratılımdığı',
'exif-focalplaneresolutionunit'    => 'Şoğırlaw jaýpaqtıqtıñ ajıratılımdıq ölşemi',
'exif-subjectlocation'             => 'Nısana mekendewi',
'exif-exposureindex'               => 'Ustalım aýqındawı',
'exif-sensingmethod'               => 'Sensordiñ ölşew ädisi',
'exif-filesource'                  => 'Faýl qaýnarı',
'exif-scenetype'                   => 'Saxna türi',
'exif-cfapattern'                  => 'CFA süzgi keýipi',
'exif-customrendered'              => 'Qosımşa swret öñdetwi',
'exif-exposuremode'                => 'Ustalım tärtibi',
'exif-whitebalance'                => 'Aq tüsiniñ tendestigi',
'exif-digitalzoomratio'            => 'Sandıq awqımdaw jarnaqtawı',
'exif-focallengthin35mmfilm'       => '35 mm taspasınıñ şoğırlaw alşaqtığı',
'exif-scenecapturetype'            => 'Tüsirgen saxna türi',
'exif-gaincontrol'                 => 'Saxnanı meñgerw',
'exif-contrast'                    => 'Qarama-qarsılıq',
'exif-saturation'                  => 'Qanıqtıq',
'exif-sharpness'                   => 'Aýqındıq',
'exif-devicesettingdescription'    => 'Jabdıq baptaw sïpattarı',
'exif-subjectdistancerange'        => 'Saxna qaşıqtığınıñ kölemi',
'exif-imageuniqueid'               => 'Swrettiñ biregeý nömiri (ID)',
'exif-gpsversionid'                => 'GPS belgişesiniñ nusqası',
'exif-gpslatituderef'              => 'Soltüstik nemese Oñtüstik boýlığı',
'exif-gpslatitude'                 => 'Boýlığı',
'exif-gpslongituderef'             => 'Şığıs nemese Batıs endigi',
'exif-gpslongitude'                => 'Endigi',
'exif-gpsaltituderef'              => 'Bïiktik körsetwi',
'exif-gpsaltitude'                 => 'Bïiktik',
'exif-gpstimestamp'                => 'GPS waqıtı (atom sağatı)',
'exif-gpssatellites'               => 'Ölşewge pýdalanılğan Jer serikteri',
'exif-gpsstatus'                   => 'Qabıldağış küýi',
'exif-gpsmeasuremode'              => 'Ölşew tärtibi',
'exif-gpsdop'                      => 'Ölşew däldigi',
'exif-gpsspeedref'                 => 'Jıldamdılıq ölşemi',
'exif-gpsspeed'                    => 'GPS qabıldağıştıñ jıldamdılığı',
'exif-gpstrackref'                 => 'Qozğalıs bağıtın körsetwi',
'exif-gpstrack'                    => 'Qozğalıs bağıtı',
'exif-gpsimgdirectionref'          => 'Swret bağıtın körsetwi',
'exif-gpsimgdirection'             => 'Swret bağıtı',
'exif-gpsmapdatum'                 => 'Paýdalanılğan geodezïyalıq tüsirme derekteri',
'exif-gpsdestlatituderef'          => 'Nısana boýlığın körsetwi',
'exif-gpsdestlatitude'             => 'Nısana boýlığı',
'exif-gpsdestlongituderef'         => 'Nısana endigin körsetwi',
'exif-gpsdestlongitude'            => 'Nısana endigi',
'exif-gpsdestbearingref'           => 'Nısana azïmwtın körsetwi',
'exif-gpsdestbearing'              => 'Nısana azïmwtı',
'exif-gpsdestdistanceref'          => 'Nısana qaşıqtığın körsetwi',
'exif-gpsdestdistance'             => 'Nısana qaşıqtığı',
'exif-gpsprocessingmethod'         => 'GPS öñdetw ädisiniñ atawı',
'exif-gpsareainformation'          => 'GPS awmağınıñ atawı',
'exif-gpsdatestamp'                => 'GPS kün-aýı',
'exif-gpsdifferential'             => 'GPS saralanğan tüzetw',

# EXIF attributes
'exif-compression-1' => 'Ulğaýtılğan',
'exif-compression-6' => 'JPEG',

'exif-photometricinterpretation-2' => 'RGB',
'exif-photometricinterpretation-6' => 'YCbCr',

'exif-orientation-1' => 'Qalıptı', # 0th row: top; 0th column: left
'exif-orientation-2' => 'Dereleý şağılısqan', # 0th row: top; 0th column: right
'exif-orientation-3' => '180° burışqa aýnalğan', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Tireleý şağılısqan', # 0th row: bottom; 0th column: left
'exif-orientation-5' => 'Sağat tilşesine qarsı 90° burışqa aýnalğan jäne tireleý şağılısqan', # 0th row: left; 0th column: top
'exif-orientation-6' => 'Sağat tilşe boýınşa 90° burışqa aýnalğan', # 0th row: right; 0th column: top
'exif-orientation-7' => 'Sağat tilşe boýınşa 90° burışqa aýnalğan jäne tireleý şağılısqan', # 0th row: right; 0th column: bottom
'exif-orientation-8' => 'Sağat tilşesine qarsı 90° burışqa aýnalğan', # 0th row: left; 0th column: bottom

'exif-planarconfiguration-1' => 'talpaq pişim',
'exif-planarconfiguration-2' => 'taýpaq pişim',

'exif-xyresolution-i' => '$1 dpi',
'exif-xyresolution-c' => '$1 dpc',

'exif-colorspace-1'      => 'sRGB',
'exif-colorspace-ffff.h' => 'FFFF.H',

'exif-componentsconfiguration-0' => 'bar bolmadı',
'exif-componentsconfiguration-1' => 'Y',
'exif-componentsconfiguration-2' => 'Cb',
'exif-componentsconfiguration-3' => 'Cr',
'exif-componentsconfiguration-4' => 'R',
'exif-componentsconfiguration-5' => 'G',
'exif-componentsconfiguration-6' => 'B',

'exif-exposureprogram-0' => 'Anıqtalmağan',
'exif-exposureprogram-1' => 'Qolmen',
'exif-exposureprogram-2' => 'Bağdarlamalı ädis (qalıptı)',
'exif-exposureprogram-3' => 'Sañılaw basıñqılığı',
'exif-exposureprogram-4' => 'Isırma basıñqılığı',
'exif-exposureprogram-5' => 'Öner bağdarlaması (anıqtıq terendigine sanasqan)',
'exif-exposureprogram-6' => 'Qïmıl bağdarlaması (japqış şapşandılığına sanasqan)',
'exif-exposureprogram-7' => 'Tireleý ädisi (artı şoğırlawsız tayaw tüsirmeler)',
'exif-exposureprogram-8' => 'Dereleý ädisi (artı şoğırlanğan dereleý tüsirmeler)',

'exif-subjectdistance-value' => '$1 m',

'exif-meteringmode-0'   => 'Belgisiz',
'exif-meteringmode-1'   => 'Birkelki',
'exif-meteringmode-2'   => 'Buldır daq',
'exif-meteringmode-3'   => 'BirDaqtı',
'exif-meteringmode-4'   => 'KöpDaqtı',
'exif-meteringmode-5'   => 'Örnekti',
'exif-meteringmode-6'   => 'Jırtındı',
'exif-meteringmode-255' => 'Basqa',

'exif-lightsource-0'   => 'Belgisiz',
'exif-lightsource-1'   => 'Kün jarığı',
'exif-lightsource-2'   => 'Künjarıqtı şam',
'exif-lightsource-3'   => 'Qızdırğıştı şam',
'exif-lightsource-4'   => 'Jarqıldağış',
'exif-lightsource-9'   => 'Aşıq kün',
'exif-lightsource-10'  => 'Bulınğır kün',
'exif-lightsource-11'  => 'Kölenkeli',
'exif-lightsource-12'  => 'Künjarıqtı şam (D 5700–7100 K)',
'exif-lightsource-13'  => 'Künjarıqtı şam (N 4600–5400 K)',
'exif-lightsource-14'  => 'Künjarıqtı şam (W 3900–4500 K)',
'exif-lightsource-15'  => 'Künjarıqtı şam (WW 3200–3700 K)',
'exif-lightsource-17'  => 'Qalıptı jarıq qaýnarı A',
'exif-lightsource-18'  => 'Qalıptı jarıq qaýnarı B',
'exif-lightsource-19'  => 'Qalıptı jarıq qaýnarı C',
'exif-lightsource-20'  => 'D55',
'exif-lightsource-21'  => 'D65',
'exif-lightsource-22'  => 'D75',
'exif-lightsource-23'  => 'D50',
'exif-lightsource-24'  => 'Stwdïyalıq ISO künjarıqtı şam',
'exif-lightsource-255' => 'Basqa jarıq qaýnarı',

'exif-focalplaneresolutionunit-2' => 'dywým',

'exif-sensingmethod-1' => 'Anıqtalmağan',
'exif-sensingmethod-2' => '1-çïpti awmaqtı tüssezgiş',
'exif-sensingmethod-3' => '2-çïpti awmaqtı tüssezgiş',
'exif-sensingmethod-4' => '3-çïpti awmaqtı tüssezgiş',
'exif-sensingmethod-5' => 'Kezekti awmaqtı tüssezgiş',
'exif-sensingmethod-7' => '3-sızıqtı tüssezgiş',
'exif-sensingmethod-8' => 'Kezekti sızıqtı tüssezgiş',

'exif-filesource-3' => 'DSC',

'exif-scenetype-1' => 'Tikeleý tüsirilgen fotoswret',

'exif-customrendered-0' => 'Qalıptı öñdetw',
'exif-customrendered-1' => 'Qosımşa öñdetw',

'exif-exposuremode-0' => 'Özdik ustalımdaw',
'exif-exposuremode-1' => 'Qolmen ustalımdaw',
'exif-exposuremode-2' => 'Özdik jarqıldaw',

'exif-whitebalance-0' => 'Aq tüsiniñ özdik tendestirw',
'exif-whitebalance-1' => 'Aq tüsiniñ qolmen tendestirw',

'exif-scenecapturetype-0' => 'Qalıptı',
'exif-scenecapturetype-1' => 'Dereleý',
'exif-scenecapturetype-2' => 'Tireleý',
'exif-scenecapturetype-3' => 'Tüngi saxna',

'exif-gaincontrol-0' => 'Joq',
'exif-gaincontrol-1' => 'Tömen zorayw',
'exif-gaincontrol-2' => 'Joğarı zorayw',
'exif-gaincontrol-3' => 'Tömen bayawlaw',
'exif-gaincontrol-4' => 'Joğarı bayawlaw',

'exif-contrast-0' => 'Qalıptı',
'exif-contrast-1' => 'Uyan',
'exif-contrast-2' => 'Turpaýı',

'exif-saturation-0' => 'Qalıptı',
'exif-saturation-1' => 'Tömen qanıqtı',
'exif-saturation-2' => 'Joğarı qanıqtı',

'exif-sharpness-0' => 'Qalıptı',
'exif-sharpness-1' => 'Uyan',
'exif-sharpness-2' => 'Turpaýı',

'exif-subjectdistancerange-0' => 'Belgisiz',
'exif-subjectdistancerange-1' => 'Tayaw tüsirilgen',
'exif-subjectdistancerange-2' => 'Jaqın tüsirilgen',
'exif-subjectdistancerange-3' => 'Alıs tüsirilgen',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Soltüstik boýlığı',
'exif-gpslatitude-s' => 'Oñtüstik boýlığı',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Şığıs endigi',
'exif-gpslongitude-w' => 'Batıs endigi',

'exif-gpsstatus-a' => 'Ölşew ulaswda',
'exif-gpsstatus-v' => 'Ölşew özara ärekette',

'exif-gpsmeasuremode-2' => '2-bağıttıq ölşem',
'exif-gpsmeasuremode-3' => '3-bağıttıq ölşem',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'km/h',
'exif-gpsspeed-m' => 'mil/h',
'exif-gpsspeed-n' => 'J. tüýin',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Şın bağıt',
'exif-gpsdirection-m' => 'Magnïttı bağıt',

# External editor support
'edit-externally'      => 'Bul faýldı sırtqı qural/bağdarlama arqılı öñdew',
'edit-externally-help' => 'Köbirek aqparat üşin [http://meta.wikimedia.org/wiki/Help:External_editors ornatw nusqawların] qarañız.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'barlığın',
'imagelistall'     => 'barlığı',
'watchlistall1'    => 'barlığı',
'watchlistall2'    => 'barlıq',
'namespacesall'    => 'barlığı',

# E-mail address confirmation
'confirmemail'            => 'E-poşta jaýın kwälandırw',
'confirmemail_noemail'    => '[[{{ns:special}}:Preferences|Qatıswşı baptawıñızda]] jaramdı e-poşta jaýın engizbepsiz.',
'confirmemail_text'       => 'Bul wïkïde e-poşta qasïetterin paýdalanwdıñ aldınan e-poşta jaýıñızdı
kwälandırw qajet. Öziñizdiñ jaýıñızğa kwälandırw xatın jiberw üşin tömendegi tüýmeni nuqıñız.
Xattıñ işinde arnaýı kodı bar silteme kiristirledi;	e-poşta jaýıñızdıñ jaramdığın kwälandırw üşin
siltemeni şolğıştıñ meken-jaý jolağına engizip aşıñız.',
'confirmemail_send'       => 'Kwälandırw kodın jiberw',
'confirmemail_sent'       => 'Kwälandırw E-poşta xatı jiberildi.',
'confirmemail_sendfailed' => 'Kwälandırw xatı jiberilmedi. Engizilgen jaýdı jaramsız äriterine tekserip şığıñız.

E-poşta qızmeti qaýtarğanı: $1',
'confirmemail_invalid'    => 'Kwälandırw kodı jaramsız. Kodtıñ merzimi bitken şığar.',
'confirmemail_needlogin'  => 'E-poşta jaýıñızdı kwälandırw üşin $1 qajet.',
'confirmemail_success'    => 'E-poşta jaýıñız kwälandırıldı. Endi Wïkïge kirip jumısqa kiriswge boladı',
'confirmemail_loggedin'   => 'E-poşta jaýıñız kwälandırıldı.',
'confirmemail_error'      => 'Kwälandırwıñızdı saqtağanda belgisiz qate boldı.',
'confirmemail_subject'    => '{{SITENAME}} torabınan e-poşta jaýıñızdı kwälandırw xatı',
'confirmemail_body'       => "Keýbirew, mına $1 IP jaýınan, öziñiz bolwı mümkin,
{{SITENAME}} jobasındağı E-poşta jaýın qoldanıp «$2» tirkelgi jasaptı.

Osı tirkelgi rastan sizdiki ekenin kwälandırw üşin, jäne {{SITENAME}} jobasınıñ
e-poşta qasïetterin belsendirw üşin, mına siltemeni şolğışpen aşıñız:

$3

Bul sizdiki '''emes''' bolsa, siltemege ermeñiz. Kwälandırw kodınıñ
merzimi $4 kezinde bitedi.",

# Inputbox extension, may be useful in other contexts as well
'tryexact'       => 'Däl säýkesin sınap köriñiz',
'searchfulltext' => 'Tolıq mätinimen izdew',
'createarticle'  => 'Betti bastaw',

# Scary transclusion
'scarytranscludedisabled' => '[Wïkï-ara kiregw öşirilgen]',
'scarytranscludefailed'   => '[$1 betine ülgi öñdetw sätsiz bitti; keşiriñiz]',
'scarytranscludetoolong'  => '[URL jaýı tım uzın; keşiriñiz]',

# Trackbacks
'trackbackbox'      => '<div id="mw_trackbacks">
Bul bettiñ añıstawları:<br />
$1
</div>',
'trackbackremove'   => '([$1 Joýıldı])',
'trackbacklink'     => 'Añıstaw',
'trackbackdeleteok' => 'Añıstaw joywı sätti ötti.',

# Delete conflict
'deletedwhileediting' => 'Nazar salıñız:Siz bul bettiñ öñdewin bastağanda, osı bet joýıldı!',
'confirmrecreate'     => "Siz bul bettiñ öndewin bastağanda [[{{ns:user}}:$1|$1]] ([[{{ns:user_talk}}:$1|talqılawı]]) osı betti joýdı, körsetken sebebi:
: ''$2''
Osı betti şınınan qaýta jasawın rastañız.",
'recreate'            => 'Qaýta jasaw',
'tooltip-recreate'    => 'Bul betti joýılwına qaramaý qaýta jasaw',

'unit-pixel' => ' px',

# HTML dump
'redirectingto' => '[[$1]] betine aýdatwda…',

# action=purge
'confirm_purge'        => 'Qosalqı qaltadağı osı betin tazalaýmız ba?<br /><br />$1',
'confirm_purge_button' => 'Jaraýdı',

'youhavenewmessagesmulti' => '$1 degenge jaña xabarlar tüsti',
'newtalkseperator'        => ',_',

'searchcontaining' => "Mına sözi bar bet arasınan izdew: ''$1''.",
'searchnamed'      => "Mına atawlı bet arasınan izdew: ''$1''.",
'articletitles'    => "Atawları mınadan bastalğan better: ''$1''",
'hideresults'      => 'Nätïjelerdi jasır',

# DISPLAYTITLE
'displaytitle' => '(Bul bettiñ siltemesi: [[$1]])',

'loginlanguagelabel' => 'Til: $1',

# Multipage image navigation
'imgmultipageprev' => '&larr; aldıñğı betke',
'imgmultipagenext' => 'kelesi betke &rarr;',
'imgmultigo'       => 'Ötw!',
'imgmultigotopre'  => 'Mına betke ötw',

# Table pager
'ascending_abbrev'         => 'ösw',
'descending_abbrev'        => 'kemw',
'table_pager_next'         => 'Kelesi betke',
'table_pager_prev'         => 'Aldıñğı betke',
'table_pager_first'        => 'Alğaşqı betke',
'table_pager_last'         => 'Soñğı betke',
'table_pager_limit'        => 'Bet saýın $1 dana körset',
'table_pager_limit_submit' => 'Ötw',
'table_pager_empty'        => 'Eş nätïje joq',

# Auto-summaries
'autosumm-blank'   => 'Bettiñ barlıq mağlumatın alastattı',
'autosumm-replace' => "Betti '$1' degenmen almastırdı",
'autoredircomment' => '[[$1]] degenge aýdadı', # This should be changed to the new naming convention, but existed beforehand
'autosumm-new'     => 'Jaña bet: $1',
);

?>
