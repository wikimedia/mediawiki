<?php
/** Quechua (Runa Simi)
 *
 * @addtogroup Language
 *
 * @author AlimanRuna
 * @author G - ג
 * @author Siebrand
 * @author Nike
 * @author SPQRobin
 */

$fallback = 'es';

$namespaceNames = array(
	NS_MEDIA          => 'Midya',
	NS_SPECIAL        => 'Sapaq',
	NS_MAIN           => '',
	NS_TALK           => 'Rimanakuy',
	NS_USER           => 'Ruraq',
	NS_USER_TALK      => 'Ruraq_rimanakuy',
	# NS_PROJECT set by \$wgMetaNamespace
	NS_PROJECT_TALK   => '$1_rimanakuy',
	NS_IMAGE          => 'Rikcha',
	NS_IMAGE_TALK     => 'Rikcha_rimanakuy',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'MediaWiki_rimanakuy',
	NS_TEMPLATE       => 'Plantilla',
	NS_TEMPLATE_TALK  => 'Plantilla_rimanakuy',
	NS_HELP           => 'Yanapa',
	NS_HELP_TALK      => 'Yanapa_rimanakuy',
	NS_CATEGORY       => 'Katiguriya',
	NS_CATEGORY_TALK  => 'Katiguriya_rimanakuy',
);

$messages = array(
# User preference toggles
'tog-underline'               => "T'inkikunata uranpi sikwiy",
'tog-highlightbroken'         => 'Ch\'usaq p\'anqaman t\'inkimuqkunata sananchay <a href="" class="new">kay hinam</a> (icha kay hinam<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Rakirikunata paqtachiy',
'tog-hideminor'               => '«Ñaqha hukchasqa» nisqapi aslla hukchasqakunata pakay',
'tog-extendwatchlist'         => "Watiqana sutisuyuta tukuy rurachinalla hukchaykunaman mast'ay",
'tog-usenewrc'                => "Sananchasqa ñaqha hukchasqakuna (JavaScript: manam tukuy wamp'unakunapichu llamk'an)",
'tog-numberheadings'          => "Uma siq'ikunata kikinmanta yupay",
'tog-showtoolbar'             => "Llamk'apuna sillwita rikuchiy",
'tog-editondblclick'          => "P'anqakunata llamk'apuy iskaylla ñit'iywan (JavaScript)",
'tog-editsection'             => "Rakirilla llamk'apuyta saqillay [qillqay] t'inkiwan",
'tog-editsectiononrightclick' => "Rakirilla llamk'apuyta saqillay paña butunta rakirip sutinpi ñit'ispa (JavaScript)",
'tog-showtoc'                 => "Yuyarinata rikuchiy (kimsamanta aswan uma siq'iyuq p'anqakunapaq)",
'tog-rememberpassword'        => "Yaykuna rimata yuyaykuy llamk'ay tiyaypura",
'tog-editwidth'               => "Llamk'apuna k'itiqa lliwmanta aswan sunim",
'tog-watchcreations'          => "Qallarisqay p'anqakunata watiqay.",
'tog-watchdefault'            => "Hukchasqay p'anqakunata watiqay",
'tog-watchmoves'              => "Astasqay p'anqakunata watiqay",
'tog-watchdeletion'           => "Qullusqay p'anqakunata watiqay",
'tog-minordefault'            => 'Tukuy hukchasqakunata kikinmanta aslla nispa sananchay',
'tog-previewontop'            => "Rikch'ay qhawana ñawpaqman, ama qhipanpi kachunchu",
'tog-previewonfirst'          => "Manaraq llamk'apuspa rikch'ayta qhaway",
'tog-nocache'                 => "P'anqakunap ''cache'' nisqa paki hallch'anman ama niy",
'tog-enotifwatchlistpages'    => "Watiqasqay p'anqa hukchasqa kaptinqa, e-chaskita kachamuway",
'tog-enotifusertalkpages'     => "Rimachinay p'anqa hukchasqa kaptinqa, e-chaskita kachamuway",
'tog-enotifminoredits'        => "P'anqapi uchuy hukchasqamantapas willawaspa e-chaskita kachamuway",
'tog-enotifrevealaddr'        => 'E-chaski imamaytayta rikuchiy willamuwanayki e-chaskikunapi',
'tog-shownumberswatching'     => "Rikuchiy hayk'a watiqaq ruraqkuna",
'tog-fancysig'                => "Mana kikinmanta t'inkichaq silq'uy",
'tog-externaleditor'          => "Kikinmanta hawa llamk'apunata llamk'achiy",
'tog-externaldiff'            => "Kikinmanta hawa ''diff'' nisqata llamk'achiy",
'tog-showjumplinks'           => "«Chayman phinkiy» aypanalla t'inkikunata saqillay",
'tog-uselivepreview'          => "''Live preview'' nisqa ñawpaq qhawayta llamk'achiy (JavaScript) (llamiy aknaraq)",
'tog-forceeditsummary'        => "Ch'usaq llamk'apuy waqaychasqa kachkaptinqa ch'itiyay.",
'tog-watchlisthideown'        => "Watiqasqaykunapiqa ñuqap llamk'apusqaykunata pakay",
'tog-watchlisthidebots'       => "Watiqasqaykunapiqa rurana antachakunap llamk'apusqankunata pakay",
'tog-watchlisthideminor'      => "Watiqasqaykunapiqa uchuylla llamk'apusqakunata pakay",
'tog-nolangconversion'        => 'Simi kutiyman ama niy',
'tog-ccmeonemails'            => 'Huk ruraqkunaman kachasqay e-chaskikunamanta iskaychasqakunata kachamuway',
'tog-diffonly'                => "Huk kaykunap uranpi kaq p'anqap samiqninta ama rikuchiychu",

'underline-always'  => "Hayk'appas",
'underline-never'   => "Mana hayk'appas",
'underline-default' => "Wamp'unap kikinmanta chanin",

'skinpreview' => '(Ñawpaqta qhaway)',

# Dates
'sunday'        => 'Intichaw',
'monday'        => 'Killachaw',
'tuesday'       => 'Atipachaw',
'wednesday'     => 'Quyllurchaw',
'thursday'      => 'Illapachaw',
'friday'        => "Ch'askachaw",
'saturday'      => "K'uychichaw",
'sun'           => 'Int',
'mon'           => 'Kil',
'tue'           => 'Ati',
'wed'           => 'Quy',
'thu'           => 'Ilp',
'fri'           => 'Cha',
'sat'           => 'Kuy',
'january'       => 'iniru',
'february'      => 'phiwriru',
'march'         => 'marsu',
'april'         => 'awril',
'may_long'      => 'mayukilla',
'june'          => 'hunyu',
'july'          => 'hulyu',
'august'        => 'awustu',
'september'     => 'sitimri',
'october'       => 'uktuwri',
'november'      => 'nuwimri',
'december'      => 'disimri',
'january-gen'   => 'iniru',
'february-gen'  => 'phiwriru',
'march-gen'     => 'marsu',
'april-gen'     => 'awril',
'may-gen'       => 'mayukilla',
'june-gen'      => 'hunyu',
'july-gen'      => 'hulyu',
'august-gen'    => 'awustu',
'september-gen' => 'sitimri',
'october-gen'   => 'uktuwri',
'november-gen'  => 'nuwimri',
'december-gen'  => 'disimri',
'jan'           => 'ini',
'feb'           => 'phi',
'mar'           => 'mar',
'apr'           => 'awr',
'may'           => 'may',
'jun'           => 'hun',
'jul'           => 'hul',
'aug'           => 'awu',
'sep'           => 'sit',
'oct'           => 'ukt',
'nov'           => 'nuw',
'dec'           => 'dis',

# Bits of text used by many pages
'categories'            => 'Katiguriyakuna',
'pagecategories'        => '{{PLURAL:$1|Katiguriya|Katiguriyakuna}}',
'category_header'       => '"$1" sutiyuq katiguriyapi qillqakuna',
'subcategories'         => 'Urin katiguriyakuna',
'category-media-header' => '"$1" sutiyuq katiguriyapi multimidya willañiqikuna',
'category-empty'        => "''Kay katiguriyaqa ch'usaqmi.''",

'mainpagetext'      => "''MediaWiki'' nisqa llamp'u kaqqa aypaylla takyachisqañam.",
'mainpagedocfooter' => "Wiki llamp'u kaqmanta willasunaykipaqqa [http://meta.wikimedia.org/wiki/Help:Contents Ruraqpaq yanapana] ''(User's Guide)'' sutiyuq p'anqata qhaway.

== Qallarichkaspa ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Configuration settings list]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]",

'about'          => "P'anqamanta",
'article'        => 'Qillqa',
'newwindow'      => '(Musuq wintanam kichakun)',
'cancel'         => 'Ama niy',
'qbfind'         => 'Maskay',
'qbbrowse'       => 'Maskapuy',
'qbedit'         => "Llamk'apuy",
'qbpageoptions'  => "P'anqap akllanankuna",
'qbpageinfo'     => "P'anqamanta willay",
'qbmyoptions'    => 'Akllanaykuna',
'qbspecialpages' => "Sapaq p'anqakuna",
'moredotdotdot'  => 'Aswan...',
'mypage'         => "P'anqay",
'mytalk'         => 'Rimachinay',
'anontalk'       => 'Kay IP huchhapaq rimanakuy',
'navigation'     => "Wamp'una",

'errorpagetitle'    => 'Pantasqa',
'returnto'          => '$1-man kutimuy.',
'tagline'           => '{{SITENAME}}manta',
'help'              => 'Yanapa',
'search'            => 'Maskay',
'searchbutton'      => 'Maskay',
'go'                => 'Riy',
'searcharticle'     => 'Riy',
'history'           => "Wiñay kawsay p'anqa",
'history_short'     => 'Wiñay kawsay',
'updatedmarker'     => 'qayna watukamusqaymantapacha musuqchasqa',
'info_short'        => 'Willay',
'printableversion'  => "Ch'ipachinapaq",
'permalink'         => "Kakuq t'inki",
'print'             => "Ch'ipachiy",
'edit'              => 'qillqay',
'editthispage'      => "Kay p'anqata llamk'apuy",
'delete'            => 'Qulluy',
'deletethispage'    => "Kay p'anqata qulluy",
'undelete_short'    => "Paqarichiy {{PLURAL:$1|huk llamk'apusqa|$1 llamk'apusqa}}",
'protect'           => 'Amachay',
'protect_change'    => 'amachayta hukchay',
'protectthispage'   => "Kay p'anqata amachay",
'unprotect'         => 'Amaña amachaychu',
'unprotectthispage' => "Kay p'anqata amaña amachaychu",
'newpage'           => "Musuq p'anqa",
'talkpage'          => "Kay p'anqamanta rimanakuy",
'talkpagelinktext'  => 'rimanakuy',
'specialpage'       => "Sapaq p'anqa",
'personaltools'     => "Kikin ruraqpa llamk'anankuna",
'postcomment'       => 'Willamuy',
'articlepage'       => 'Qillqata qhaway',
'talk'              => 'Rimachina',
'views'             => 'Rikunakuna',
'toolbox'           => "Llamk'anakuna",
'userpage'          => "Ruraqpa p'anqanta qhaway",
'projectpage'       => "Meta p'anqata qhaway",
'imagepage'         => "Rikch'amanta p'anqata qhaway",
'mediawikipage'     => "Willay p'anqata qhaway",
'templatepage'      => "Plantilla p'anqata qhaway",
'viewhelppage'      => "Yanapana p'anqata qhaway",
'categorypage'      => "Katiguriya p'anqata qhaway",
'viewtalkpage'      => 'Rimachinata qhaway',
'otherlanguages'    => 'Huk simikunapi',
'redirectedfrom'    => '($1-manta pusampusqa)',
'redirectpagesub'   => "Pusampusqa p'anqa",
'lastmodifiedat'    => "Kay p'anqaqa $2, $1 qhipaq kutitam hukchasqa karqan.", # $1 date, $2 time
'viewcount'         => "Kay p'anqaqa {{PLURAL:$1|huk kuti|$1 kuti}} watukusqañam.",
'protectedpage'     => "Amachasqa p'anqa",
'jumpto'            => 'Kayman riy:',
'jumptonavigation'  => "wamp'una",
'jumptosearch'      => 'maskana',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => '{{SITENAME}}manta',
'aboutpage'         => 'Project:manta',
'bugreports'        => "Llamp'u kaqpi pantasqamanta willaykuna",
'bugreportspage'    => 'Project:Pantasqamanta willaykuna',
'copyright'         => "Ch'aqtasqakunataqa llamk'achinkiman <i>$1</i> nisqap ruraq hayñinkama",
'copyrightpagename' => "{{SITENAME}} p'anqayuq ruraqpa iskaychay hayñin",
'copyrightpage'     => '{{ns:project}}:Ruraqpa hayñin',
'currentevents'     => 'Kunan pacha',
'currentevents-url' => 'Kunan pacha',
'disclaimers'       => 'Chiqakunamanta rikuchiy',
'disclaimerpage'    => 'Project:Sapsilla saywachasqa paqtachiy',
'edithelp'          => "Llamk'ana yanapay",
'edithelppage'      => 'Help:Qillqa yanapay',
'faq'               => 'Pasaq tapuykuna',
'faqpage'           => 'Project:Pasaq tapuykuna',
'helppage'          => 'Help:Yanapana',
'mainpage'          => "Qhapaq p'anqa",
'policy-url'        => 'Project:Kawpay',
'portal'            => "Ayllupaq p'anqa",
'portal-url'        => "Project:Ayllupaq p'anqa",
'privacy'           => 'Willakunata amachaynin',
'privacypage'       => 'Project:Willakunata amachay',
'sitesupport'       => 'Qarana',
'sitesupport-url'   => 'Project:Ruraykamayman qarana',

'badaccess'        => 'Saqillay pantasqa',
'badaccess-group0' => 'Manam saqillasunkichu munasqayta rurayta.',
'badaccess-group1' => 'Munasqay ruranaqa kay huñupi kachkaq ruraqkunallatam rurayta saqillan: $1.',
'badaccess-group2' => 'Munasqay ruranaqa kay huñupi kachkaq ruraqkunallatam rurayta saqillan: $1.',
'badaccess-groups' => 'Munasqay ruranaqa kay huñupi kachkaq ruraqkunallatam rurayta saqillan: $1.',

'versionrequired'     => "$1 nisqa MediaWiki llamk'apusqatam muchunki kay p'anqata llamk'achinaykipaq",
'versionrequiredtext' => "$1 nisqa MediaWiki llamk'apusqatam muchunki kay p'anqata llamk'achinaykipaq. Astawan willasunaykipaqqa, [[Special:Version]] nisqapi qhaway",

'pagetitle'               => '$1 - Wikipidiya',
'retrievedfrom'           => '"$1" p\'anqamanta chaskisqa (Qhichwa / Quechua)',
'youhavenewmessages'      => '$1(ni)ykim kachkan ($2).',
'newmessageslink'         => 'Musuq willay',
'newmessagesdifflink'     => 'qayna hukchasqapi huk kaynin',
'youhavenewmessagesmulti' => 'Musuq willayniykikunam kachkan $1-pi',
'editsection'             => 'allichay',
'editold'                 => "llamk'apuy",
'editsectionhint'         => 'Allichay rakita: $1',
'toc'                     => 'Yuyarina',
'showtoc'                 => 'rikuchiy',
'hidetoc'                 => 'pakay',
'thisisdeleted'           => '$1-ta rikuy icha paqarichiy?',
'viewdeleted'             => "$1 p'anqata rikuyta munankichu?",
'restorelink'             => '{{PLURAL:$1|qullusqa hukchasqa|$1 qullusqa hukchasqa}}',
'feedlinks'               => 'Mikhuchiy:',
'feed-invalid'            => 'Willaykuna mikhuchina layaqa manam allinchu.',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Qillqa',
'nstab-user'      => "Ruraqpa p'anqan",
'nstab-media'     => 'Midya',
'nstab-special'   => 'Sapaq',
'nstab-project'   => "Ruraykamaypa p'anqan",
'nstab-image'     => "Rikch'a",
'nstab-mediawiki' => 'Willay',
'nstab-template'  => 'Plantilla',
'nstab-help'      => 'Yanapa',
'nstab-category'  => 'Katiguriya',

# Main script and global functions
'nosuchaction'      => 'Kay hina rurayqa manam kanchu',
'nosuchactiontext'  => "URL tiyaypi sut'ichasqa rurayqa manam kanchu {{SITENAME}} sutiyuq wikipi",
'nosuchspecialpage' => "Kay hina sapaq p'anqaqa manam kanchu",
'nospecialpagetext' => "<big>'''Mana kaq sapaq p'anqatam munanki.'''</big>

Allin sapaq p'anqakunataqa tarinki [[Special:Specialpages|Sapaq p'anqakuna]] nisqa p'anqapim.",

# General errors
'error'                => 'Pantasqa',
'databaseerror'        => 'Willañiqintin pantasqa',
'dberrortext'          => 'Willañiqimanta mañakuptiyki sintaksis pantasqam tukurqan.
Llamp\'u kaq wakichipi pantasqachá.
Qayna willañiqimanta mañakusqaqa karqan kaypacham: <blockquote><tt>$1</tt></blockquote> kay ruraypim: "<tt>$2</tt>". MySQL-pa kutichisqan pantasqaqa karqan "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Willañiqimanta mañakuptiyki sintaksis pantasqam tukurqan.
Qayna willañiqimanta mañakusqaqa karqan kaymi:
"$1"
kay ruraymantam: "$2".
MySQL-pa kutichisqan pantasqaqa karqan "$3: $4".',
'noconnect'            => "Achachaw, $1-pi willañiqintinwanqa manam t'inkiyta atinichu. <br />
$1",
'nodb'                 => 'Manam atinchu $1 willañiqintinta akllayqa',
'cachederror'          => "Kayqa mañakusqayki p'anqamanta iskaychasqam, manachá kunan kachkaq p'anqa hinachu.",
'laggedslavemode'      => "'''Paqtataq''': Kay p'anqapiqa manaraqchá kachkanchu aswan qayna musuqchasqakuna.",
'readonly'             => "Willañiqintinqa hark'asqam",
'enterlockreason'      => "Qillqamuy imarayku hark'asqa karqan, hayk'appas manañachá hark'asqachu kanqa",
'readonlytext'         => "Kay {{SITENAME}} nisqap willañiqintintaqa manam hukchayta, manam chayman qillqamuyta saqillanchu, mit'awa kakuchiyraykuchá, chaymantataqchá allin kanqa.
Hark'aq kamachiqqa umallirqan kaytam nispa:
<p>$1",
'missingarticle'       => "Willañiqintinqa huk p'anqapi qillqasqataqa, imata tarinanchus atinman, manam tarirqanchu, kay hinam nisqa: \"\$1\".

Qullusqañachá p'anqap mawk'a llamk'apusqanta icha wiñay kawsasqanta mañakurqanki.

Mana hinaptinqa, llamp'u kaq wakichipi pantasqatachá tarirqanki. Ama hina kaspa, kamachiqman chaymanta willariy,
URL tiyaytapas.",
'readonly_lag'         => "Willañiqintinqa mit'alla hark'asqam, sirwiqkuna kikinpachachastin.",
'internalerror'        => 'Ukhu pantasqa',
'internalerror_info'   => 'Ukhu pantasqa: $1',
'filecopyerror'        => 'Manam atinichu willañiqita "$1"-manta "$2"-man iskaychayta.',
'filerenameerror'      => 'Manam atinichu willañiqip sutinta "$1"-manta "$2"-man hukchayta.',
'filedeleteerror'      => 'Manam atinichu "$1" sutiyuq willañiqita qulluyta.',
'directorycreateerror' => 'Manam atinichu "$1" sutiyuq willañiqi churanata kamayta.',
'filenotfound'         => 'Manam tarinichu "$1" sutiyuq willañiqita.',
'fileexistserror'      => 'Manam atinichu "$1" sutiyuq willañiqiman qillqamuyta: willañiqiqa kachkanñam',
'unexpected'           => 'Mana suyaykusqa chani: "$1"="$2".',
'formerror'            => "Pantasqa: manam atinichu hunt'ana p'anqata kachayta",
'badarticleerror'      => "Kay p'anqapiqa manam saqillanchu kay hina rurayta.",
'cannotdelete'         => "Manam atinichu sananchasqay p'anqata icha willañiqita qulluyta. (P'anqaqa qullusqañachá)",
'badtitle'             => "P'anqap sutinqa manam allinchu",
'badtitletext'         => "Kay p'anpaq sutinqa manam allinchu, mana allin interwiki t'inkichá kanman.",
'perfdisabled'         => "Achachaw, kay ruranaqa mit'alla manam atinchu, willañiqintinta hank'achiptinmi mana ruranalla kayninkama.",
'perfcached'           => "Kay willakunaqa ''cache'' nisqa pakasqa hallch'apim kachkan, chayrayku manañachá musuqchasqachu:",
'perfcachedts'         => 'Kay willakunaqa waqaychasqam. Qhipaq musuqchasqaqa $1 karqan.',
'querypage-no-updates' => "Kay p'anqata musuqchayqa manam atichkanchu. Kunanqa kaypi willakuna manam musuqchasqachu kanqa.",
'wrong_wfQuery_params' => 'Kaypa pantasqa kuskanachina tupunkuna: wfQuery()<br />
Ruray paqtachi: $1<br />
Tapuna: $2',
'viewsource'           => 'Pukyu qillqata qhaway',
'viewsourcefor'        => '$1-paq',
'actionthrottled'      => "Rurayniykiqa hark'asqam",
'actionthrottledtext'  => "Spam nisqa millay rurayta hark'anapaq, manam saqillasunkichu kayta nisyu kutikunata rurayta ratulla mit'api. Nisyutam ruraykachanki. Ama hina kaspa, huk minutukunamanta musuqmanta ruraykachay.",
'protectedpagetext'    => "Kay p'anqaqa llamk'apuymanta amachasqam.",
'viewsourcetext'       => "Kay p'anqatam qhawayta iskaychaytapas atinki:",
'protectedinterface'   => "Kay p'anqapiqa wakichintinpa uyapuranpaq qillqam. Wandalismu nisqamanta amachasqam kachkan. Kay qillqata allinchayta munaspaykiqa, [[{{MediaWiki:Grouppage-sysop}}|kamachiqta]] tapuy.",
'editinginterface'     => "'''Paqtataq:''' {{SITENAME}} nisqap uyapuranmanta p'anqatam llamk'apuchkanki. Hukchaptiykiqa, chay uyapurap rikch'ayninqa hukyan huk ruraqkunapaqpas.",
'sqlhidden'            => '(SQL tapunaqa pakasqam)',
'cascadeprotected'     => "Kay p'anqaqa amachasqam kachkan, ''phaqcha'' nisqa kamachiwan amachasqa kay {{PLURAL:$1|p'anqapi|p'anqakunapi}} ch'aqtasqa kaspanmi:
$2",
'namespaceprotected'   => "'''$1''' nisqa suti k'ititaqa llamk'apuyta manam saqillasunkichu.",
'customcssjsprotected' => "Manam saqillasunkichu kay p'anqata llamk'apuyta, huk ruraqpa kikin tiyachisqankunayuq kaptinmi.",
'ns-specialprotected'  => "{{ns:special}} suti k'itipi p'anqakunaqa manam llamk'apunallachu.",

# Login and logout pages
'logouttitle'                => "Llamk'apuy tiyaypa puchukaynin",
'logouttext'                 => "<strong>Llamk'apuy tiyayniykiqa puchukasqañam.</strong><br />
Sutinnaq kaspaykipas {{SITENAME}}pi wamp'uytam atinki. Mana hinataq munaspaykiqa, musuqmanta yaykuy ñawpaq icha huk sutiwan. Huk p'anqakunaqa kaqllam rikch'akunqa, ''cache'' nisqa pakasqa hallch'ata mana ch'usaqchaptiykiqa.",
'welcomecreation'            => '== Allin hamusqayki, $1! ==

Rakiqunaykiqa kichasqañam. Wikipidiyapaq [[Special:Preferences|allinkachinaykita]] kutikuytaqa ama qunqaychu.',
'loginpagetitle'             => 'Yaykuy',
'yourname'                   => 'Ruraq sutiyki',
'yourpassword'               => 'Yaykuna rimayki',
'yourpasswordagain'          => 'Yaykuna rimaykita kutipayay',
'remembermypassword'         => "Llamk'apuy tiyayniykunapura yuyaykuway.",
'yourdomainname'             => 'Duminyuykip sutin',
'externaldberror'            => 'Hawa yaykuna pantasqam karqan, ichataq manam saqillasunkichu hawa rakiqunaykita musuqchayta.',
'loginproblem'               => '<b>Manam yaykuytachu atirqunki.</b><br />Huk kutitam ruraykachay!',
'login'                      => 'Yaykuy',
'loginprompt'                => "{{SITENAME}}man yaykunaykipaqqa wamp'unaykipi <i>cookies</i> nisqakunaman ari ninaykim atin.",
'userlogin'                  => 'Yaykuy',
'logout'                     => 'Lluqsiy',
'userlogout'                 => 'Lluqsiy',
'notloggedin'                => 'Manam yaykurqankichu',
'nologin'                    => 'Manaraqchu rakiqunaykichu kachkan? $1.',
'nologinlink'                => 'Kichariy',
'createaccount'              => 'Musuq rakiqunata kichariy',
'gotaccount'                 => 'Rakiqunaykiñachu kachkan? $1.',
'gotaccountlink'             => 'Rakiqunaykita willaway',
'createaccountmail'          => 'chaskipaq',
'badretype'                  => 'Qusqayki yaykuna rimakunaqa manam kaqllachu.',
'userexists'                 => 'Munasqayki ruraqpa sutiykiqa kachkanñam. Ama hina kaspa, huk ruraqpa sutiykita qillqamuy.',
'youremail'                  => 'E-chaski imamaytayki',
'username'                   => 'Ruraqpa sutin:',
'uid'                        => 'Ruraqpa ID-nin:',
'yourrealname'               => 'Chiqap sutiyki*',
'yourlanguage'               => 'Rimay',
'yourvariant'                => "Rimaypa rikch'aynin",
'yournick'                   => 'Chutu sutiyki (ruruchinapaq)',
'badsig'                     => "Chawa silq'usqaykiqa manam allinchu; HTML sananchakunata llanchiy.",
'badsiglength'               => 'Chutu sutiykiqa nisyu sunim; $1 sanampamanta aswan pisi kananmi.',
'email'                      => 'E-chaski',
'prefs-help-realname'        => "* Chiqap sutiyki (munaspaqa): quwaptiykiqa, llamk'apusqaykikunam paywan sananchasqa kanqa.",
'loginerror'                 => "Pantasqa llamk'apuy tiyaypa qallarisqan",
'prefs-help-email'           => "* Chaski (munaspayki): Huk ruraqkunata ruraqpa p'anqaykimanta icha rimachinaykimanta qamman qillqamusunaykiwan atichin qampa sutiykita mana rikuchispa.",
'prefs-help-email-required'  => 'E-chaskiykillawanmi atin.',
'nocookiesnew'               => "Ruraqpa rakiqunaykiqa kichasqañam, ichataq manaraqmi yaykurqankichu. {{SITENAME}}qa <em>kuki</em> nisqakunatam llamk'achin ruraqkunata kikinyachinapaq. Antañiqiqniykipiqa manam <em>kuki</em> nisqakuna atinchu. Ama hina kaspa, atichispa huk kutita yaykuykachay.",
'nocookieslogin'             => "{{SITENAME}} <em>kuki</em> nisqakunata llamk'achin ruraqkunata kikinyachinapaq. Antañiqiqniykipiqa manam <em>kuki</em> nisqakuna atinchu. Ama hina kaspa, atichispa huk kutita ruraykachay.",
'noname'                     => 'Manam niwarqankichu ruraqpa allin sutinta.',
'loginsuccesstitle'          => "Llamk'apuy tiyayqa qallarisqañam",
'loginsuccess'               => 'Llamk\'apuy tiyayniykiqa qallarisqam {{SITENAME}}-pi "$1" sutiyuq kaspa.',
'nosuchuser'                 => 'Nisqayki "$1" sutiyuq ruraqqa manam kanchu.
Allin qillqasqaykita llanchiriy, ichataq urapi kaq hunt\'ana p\'anqata llamk\'achiy musuq rakiqunata kicharinaykipaq.',
'nosuchusershort'            => 'Nisqayki "$1" sutiyuq ruraqqa manam kanchu.
Allin qillqasqaykita llanchiriy.',
'nouserspecified'            => 'Ruraqpa sutiykitam qunayki.',
'wrongpassword'              => 'Qillqamusqayki yaykuna rimaqa manam allinchu. Huk kutita ruraykachay.',
'wrongpasswordempty'         => 'Yaykuna rimaykita qillqamuyta qunqarqunkim, huk kutita ruraykachay.',
'passwordtooshort'           => 'Yaykuna rimaykiqa nisyu pisillam. $1 sanampayuq icha chaymanta aswan kananmi.',
'mailmypassword'             => 'Musuq yaykuna rimatam e-chaskiwan kachamuway',
'passwordremindertitle'      => '{{SITENAME}}manta yaykuna rima yuyachina',
'passwordremindertext'       => 'Pipas (qamchiki, $1 IP huchhayuq tiyaymanta) mañakuwarqan {{SITENAME}}paq musuq yaykuna rimatam e-chaski imamaytaykiman kachayta ($4). 
"$2" sutiyuq ruraqpa yaykuna rimanqa kunan "$3" kachkan.
Kunanqa yaykunaykim atinman yaykuna rimaykita hukchanaykipaq.

Huk runa kay willayta mañakurqaptinqa icha yaykuna rimaykita hukchayta manaña munaspayki, kay willayta qhawarparispa ñawpaq yaykuna rimaykita llamk\'arayachiytam atinki.',
'noemail'                    => 'Manam kanchu "$1" sutiyuq ruraqpa e-chaski imamaytan.',
'passwordsent'               => 'Musuq yaykuna rimaqa kachasqañam "$1" sutiyuq ruraqpa e-chaski imamaytanman.
Ama hina kaspa, chaskispaykiqa ruraqpa sutiykita nispa musuqmanta yaykuy.',
'blocked-mailpassword'       => "IP tiyayniykiqa hark'asqam, chayrayku manam saqillanchu yaykuna rimata musuqmanta chaskiyta, millay rurayta hark'anapaq.",
'eauthentsent'               => 'Takyachina e-chaskiqa qusqayki e-chaski imamaytaman kachamusqam. Manaraq huk e-chaskikuna kachamusqa kaptinqa, ñawpaqta e-chaskipi kamachisqakunata qatinaykim atin, chiqap e-chaski imamaytaykita takyachinaykipaq.',
'throttled-mailpassword'     => "Huk yaykuna rima yuyachinañam qayna $1 ura mit'api kachamusqam. $1 ura mit'apiqa hukllam yaykuna rima yuyachina kachasqa kachun millay rurayta hark'anapaq.",
'mailerror'                  => 'E-chaskita kachaspa pantasqa: $1',
'acct_creation_throttle_hit' => '$1 sutiyuq rakiqunaqa kachkañam. Manam atinkichu kaqllata kichayta.',
'emailauthenticated'         => 'E-chaski imamaytaykiqa $1 nisqapi chiqapchasqañam.',
'emailnotauthenticated'      => 'E-chaski imamaytaykitaqa manaraqmi sinchicharqunkichu. Mana sinchicharquptiykiqa, kay qatiq rurachinakunataqa manam atinkichu.',
'noemailprefs'               => "E-chaski imamaytaykita willaway kay rurachinakunata llamk'achinapaq.",
'emailconfirmlink'           => 'E-chaski imamaytaykita sinchichariy',
'invalidemailaddress'        => "E-chaski imamaytaykiqa manam allinchu. Ama hina kaspa, musuq allin sananchayuq imamaytaykita qillqamuy icha k'itichata ch'usaqchay.",
'accountcreated'             => 'Rakiqunaqa kichasqañam',
'accountcreatedtext'         => '$1 sutiyuq ruraqpa rakiqunanqa kichasqañam.',
'createaccount-title'        => '{{SITENAME}}paq musuq rakiqunata kichariy',
'createaccount-text'         => 'Pipas ($1) $2-paq rakiqunatam kicharqan {{SITENAME}}pi
($4). "$2"-paq yaykuna rimaqa "$3" nisqam. Yaykuspayki yaykuna rimaykita hukchanaykim atinman.

Kay willay pantasqa kaptinqa, qhawarparillay.',
'loginlanguagelabel'         => 'Rimay: $1',

# Password reset dialog
'resetpass'               => 'Ruraqpa yaykuna rimanta kutichiy',
'resetpass_announce'      => "E-chaskiwan kachasqa mit'alla yaykuna rimawanmi yaykurqunki. Ama hina kaspa, musuq yaykuna rimaykita qillqamuy:",
'resetpass_text'          => '<!-- Añada texto aquí -->',
'resetpass_header'        => 'Yaykuna rimata kutichiy',
'resetpass_submit'        => 'Yaykuna rimata hukchaspa yaykuy',
'resetpass_success'       => 'Yaykuna rimaykiqa hukchasqañam. Yaykamuchkankim...',
'resetpass_bad_temporary' => "Mit'alla yaykuna rimaqa manam allinchu. Yaykuna rimaykiqa hukchasqañachá ichataq musuqtach mañakurqanki.",
'resetpass_forbidden'     => 'Kay wikipiqa manam saqillanchu yaykuna rimata hukchayta',
'resetpass_missing'       => "Kay hunt'ana p'anqapiqa manam willakunachu kachkan.",

# Edit page toolbar
'bold_sample'     => 'Yanasapa qillqa',
'bold_tip'        => 'Yanasapa qillqa',
'italic_sample'   => 'Wiksu qillqa',
'italic_tip'      => 'Wiksu qillqa',
'link_sample'     => "T'inkip sutin",
'link_tip'        => "Ukhu t'inki",
'extlink_sample'  => "http://www.example.com t'inkip umallin",
'extlink_tip'     => "Hawa t'inki (ñawpaqta http:// nisqata yapariy)",
'headline_sample' => "Uma siq'i qillqa",
'headline_tip'    => "Iskay ñiqi hanaq siq'i qillqa",
'math_sample'     => 'Kayman minuywata qillqamuy',
'math_tip'        => 'Yupana minuywa (LaTeX)',
'nowiki_sample'   => 'Kayman mana sumaqchasqa qillqata yapamuy',
'nowiki_tip'      => 'Wiki sumaqchayta qhawarpariy',
'image_sample'    => 'Qhawarichiy.jpg',
'image_tip'       => "Ch'aqtasqa rikch'a",
'media_sample'    => 'Example.ogg',
'media_tip'       => "Multimidya willañiqiman t'inki",
'sig_tip'         => "Sutiykita, p'unchawta, pachatapas silq'umuy",
'hr_tip'          => "Siriq siq'i (ama nisyutachu llamk'apuy)",

# Edit pages
'summary'                   => 'Pisichay',
'subject'                   => 'Yachaywa/umalli',
'minoredit'                 => 'Kayqa uchuylla hukchaymi',
'watchthis'                 => 'Kay qillqata watiqay',
'savearticle'               => "P'anqata waqaychay",
'preview'                   => 'Manaraq waqaychaspa qhawariy',
'showpreview'               => 'Ñawpaqta qhawallay',
'showlivepreview'           => 'Kawsaqlla qhawariy',
'showdiff'                  => 'Hukchasqakunata rikuchiy',
'anoneditwarning'           => "''Paqtataq:'' Manaraqmi ruraqpa sutiykita qumurqunkichu. IP huchhaykim kay p'anqap hukchay hallch'ayninpi waqaychasqa kanqa.",
'missingsummary'            => "'''Paqtataq:''' Manaraqmi llamk'apusqaykimanta pisichaytachu qillqamurqunki. Musuqmanta «{{MediaWiki:Savearticle}}» nisqapi ñit'iptiykiqa, llamk'apusqayki waqaychasqam kanqa mana pisichay kaptinpas.",
'missingcommenttext'        => 'Ama hina kaspa, kay qatiqpi willaspa qillqamuy.',
'missingcommentheader'      => "'''Paqtataq:''' Manaraqmi kay willaypa umallintachu qillqamurqunki. Musuqmanta «waqaychay» nisqapi ñit'iptiykiqa, llamk'apusqayki waqaychasqam kanqa mana willaypa umallin kaptinpas.",
'summary-preview'           => 'Pisichayta ñawpaqta qhawarillay',
'subject-preview'           => 'Yachaywata/umallita ñawpaqta qhawarillay',
'blockedtitle'              => "Ruraqqa hark'asqam",
'blockedtext'               => "<big>'''Ruraqpa sutiykiqa icha IP huchhaykiqa hark'asqam.'''</big>

$1 sutiyuqmi hark'asurqunki ''$2'' nisqarayku.

* Hark'aypa qallarisqan: $8
* Hark'aypa puchukanan: $6
* Awaytiyasqa hark'ana ruraq: $7

Hark'aymanta rimanakunapaqqa $1-man icha huk [[{{MediaWiki:Grouppage-sysop}}|kamachiqman]] willariy.
Manam saqillasunkichu 'Kay ruraqman e-chaskita kachay' nisqata llamk'achiyta manaraq allin e-chaski imamaytaykita [[Special:Preferences|allinkachinaykikunaman]] quptiyki manaraqpas chaymanta hark'asqa kaptiyki.
Kunan IP huchhaykiqa $3 nisqam, hark'ay huchhataq #$5 nisqam. Mañakuspaykiqa chay huchhakunata willay.",
'autoblockedtext'           => "IP huchhaykiqa kikinmanta hark'asqam, $1-pa hark'asqan ruraqpa llamk'achisqan kaptinmi. Hark'asqaqa kayraykum:

:''$2''

* Hark'aypa qallarisqan: $8
* Hark'aypa puchukanan: $6

Hark'aymanta rimanakunapaqqa $1-man icha huk [[{{MediaWiki:Grouppage-sysop}}|kamachiqman]] willariy.
Manam saqillasunkichu 'Kay ruraqman e-chaskita kachay' nisqata llamk'achiyta manaraq allin e-chaski imamaytaykita [[Special:Preferences|allinkachinaykikunaman]] quptiyki manaraqpas chaymanta hark'asqa kaptiyki.
Hark'ay huchhaykiqa $5 nisqam. Mañakuspaykiqa chay huchhata willay.",
'blockednoreason'           => "hark'aqqa manam ninchu imarayku",
'blockedoriginalsource'     => "'''$1'''-pa pukyu qillqanqa kaymi:",
'blockededitsource'         => "'''$1'''-pi '''llamk'apusqaykikuna''' nisqapi qillqasqaqa kaymi:",
'whitelistedittitle'        => "Yaykuspallaykim llamk'apuyta atinki.",
'whitelistedittext'         => "$1ta ruranaykim atin qillqakunata llamk'apunaykipaq.",
'whitelistreadtitle'        => 'Yaykuspallaykim ñawiriyta atinki',
'whitelistreadtext'         => '[[Special:Userlogin|Yaykuspallaykim]] qillqakunata ñawiriyta atinki.',
'whitelistacctitle'         => 'Rakiqunata kichariyqa manam saqillasqachu',
'whitelistacctext'          => 'Kay wikipi rakiqunakunata kicharinaykipaqqa sapaqta saqillasqa kaspayki [[Special:Userlogin|yaykunaykim]] atin.',
'confirmedittitle'          => "E-chaski imamaytaykita takyachiy llamk'apunaykipaq",
'confirmedittext'           => "P'anqakunata llamk'apunaykipaqqa e-chaski imamaytaykita takyachinaykim atin. Ama hina kaspa, e-chaski imamaytata kicharispa takyachiy [[Special:Preferences|allinkachinaykikunapi]].",
'nosuchsectiontitle'        => 'Manam kanchu chay raki',
'nosuchsectiontext'         => "Allichaykacharqunki mana kachkaq rakitam. $1 raki mana kachkaptinmi, manam kanchu llamk'apusqaykita waqachana.",
'loginreqtitle'             => 'Yaykunaykim atin',
'loginreqlink'              => 'yaykuna',
'loginreqpagetext'          => "Huk p'anqakunata rikunaykipaqqa $1ykim atin.",
'accmailtitle'              => 'Yaykuna rimaqa kachasqañam.',
'accmailtext'               => '«$1»-paq yaykuna rimaqa $2-manmi kachasqa.',
'newarticle'                => '(Musuq)',
'newarticletext'            => "Manaraq kachkaq p'anqatam llamk'apuchkanki. Musuq p'anqata kamariyta munaspaykiqa, qillqarillay. Astawan ñawiriyta munaspaykiqa, [[{{MediaWiki:Helppage}}|yanapana p'anqata]] qhaway. Mana munaspaykitaq, ñawpaq p'anqaman ripuy.",
'anontalkpagetext'          => "---- ''Kayqa huk sutinnaq icha mana sutinta llamk'achiq ruraqpa rimanakuyninmi. IP huchhantam hallch'asunchik payta sutinchanapaq. Achka ruraqkunam huklla IP huchhanta llamk'achiyta atin. Sutinnaq ruraq kaspaykiqa, mana qampa rurasqaykimanta willamusqakunata rikuspaykiqa, ama hina kaspa [[Special:Userlogin|ruraqpa sutiykita kamariy icha yaykuy]] huk sutinnaq ruraqkunawan ama pantasqa kanaykipaq.''",
'noarticletext'             => "Kay p'anqaqa ch'usaqmi. Kaytam rurayta atinkiman: {{PAGENAME}} nisqata [[Special:Search/{{PAGENAME}}|huk qillqakunapi maskay]] icha [{{fullurl:{{FULLPAGENAME}}|action=edit}} musuq qillqata qallariy].",
'userpage-userdoesnotexist' => '"$1" sutiyuq ruraqpa rakiqunanqa manam kanchu. Ama hina kaspa, llanchikuy kay p\'anqata kamarinaykimanta.',
'clearyourcache'            => "'''Paqtataq:''' Willañiqita waqaycharquspaykiqa, wamp'unaykip ''cache'' nisqa pakasqa waqaychananta ch'usaqchanaykichá atinman hukchasqaykikunata rikunaykipaq:
*'''Mozilla:'''  ''ctrl-shift-r'',
*'''Internet Explorer:''' ''ctrl-f5'',
*'''Safari:''' ''cmd-shift-r'',
*'''Konqueror''' ''f5''.",
'usercssjsyoucanpreview'    => "<strong>Kunay:</strong> «Ñawpaqta qhawallay» nisqa ñit'inata llamk'achiy musuq css/js qhawanaykipaq, manaraq waqaychaspa.",
'usercsspreview'            => "'''Yuyariy, qhawarillachkankim ruraqpa css-niykita, manaraqmi waqaychasqachu!'''",
'userjspreview'             => "'''Yuyariy, qhawarillachkankim ruraqpa JavaScript-niykita, manaraqmi waqaychasqachu!'''",
'userinvalidcssjstitle'     => "'''Paqtataq:''' Manam kanchu \"\$1\" qara. Yuyariy, kikinpa .css, .js p'anqankunaqa uchuy sanampa umalliyuqmi, ahinataq {{ns:user}}:Foo/monobook.css manataq  {{ns:user}}:Foo/Monobook.css nisqachu.",
'updated'                   => '(Musuqchasqa)',
'note'                      => '<strong>Musyay:</strong>',
'previewnote'               => 'Yuyaykuy: Kayqa manaraq waqaychaspa qhawariymi!',
'previewconflict'           => "Rikuchkanki kay p'anqataqa, ima hinachus waqaychasqa kanqa.",
'session_fail_preview'      => "<strong>Achachaw! Llamk'apusqaykiqa manam waqaychasqachu, llamk'ana tiyaypa willankuna chinkaptinmi. Ama hina kaspa, musuqmanta ruraykachay. Mana atispaykiqa, lluqsispa musuqmanta yaykuy.</strong>",
'session_fail_preview_html' => "<strong>Achachaw! Llamk'apusqaykiqa manam waqaychasqachu, llamk'ana tiyaypa willankuna chinkaptinmi.</strong>

''Kay wiki llump'aq HTML nisqawan llamk'achkaptinmi, ñawpaq qhawariyqa pakasqam kachkan JavaScript nisqawan wankhayta hark'anapaq.''

<strong>Allin sunquwan kamarirqaspaykiqa, musuqmanta ruraykachay. Mana atispaykiqa, lluqsispa musuqmanta yaykuspa ruraykachay.</strong>",
'token_suffix_mismatch'     => "<strong>Llamk'apusqaykimanqa ama nisqam, mink'akuqniyki llamk'apuy willaypi sapaq sananchakunata arwiptinmi. Ama nisqa karqanqa qillqata waqlliymantam amachanapaq. 
Kayqa maykunapi tukukun, mana allin wakichisqa proxy sirwiytam llamk'achiptiyki.</strong>",
'editing'                   => "$1-ta llamk'apuspa",
'editinguser'               => "$1-ta llamk'apuspa",
'editingsection'            => "$1-ta llamk'apuspa (raki)",
'editingcomment'            => "$1-ta llamk'apuspa (rimapay)",
'editconflict'              => 'Ruray taripanakuy: $1',
'explainconflict'           => "Ruray taripanakuy: Huk runam kay p'anqata llamk'apurqun, qamtaq manaraq waqaychaptiyki.
Umapi kaq qillqana k'itipi kunan kachkaq qillqam. Qampa hukchasqaykikunataq sikipi kaq qillqana k'itipim.
Kunanqa rurasqaykikunata musuq qillqaman ch'aqtanaykim atin.
<b>Umapi kaq qillqallam</b> waqaychasqa kanqa.<br />",
'yourtext'                  => 'Qillqasqayki',
'storedversion'             => "Hallch'asqa musuqchasqa",
'nonunicodebrowser'         => "<strong>Paqtataq: Wamp'unaykiqa manam Unicode nisqawan llamk'anchu. Huk llamk'apuna llikam llamk'achkan p'anqakunata takyasqalla llamk'apunaykipaq: mana ASCII kaq sananchakunaqa chunka suqtayuqnintin huchha llikapim kanqa.</strong>",
'editingold'                => "<strong>Paqtataq: Kay p'anqap mawk'a hukchasqantam llamk'apuchkanki. Waqaychaptiykiqa, chaymanta aswan musuq hukchasqankuna chinkanqam.</strong>",
'yourdiff'                  => 'Hukchasqaykikuna',
'copyrightwarning'          => "Lliw {{SITENAME}}paq llamk'apuykunaqa $2 nisqawanmi uyaychasqa kanqa ($1 p'anqata qhaway). Llamk'asqaykikunata huk runakunap allinchayninta qispilla mast'ariyninta mana munaptiykiqa, ama kayman qillqamuychu.<br />
Takyachiwachkankim: Kayqa ñuqap qillqasqaymi icha qispi pukyumanta iskaychamusqaymi, nispa.
<br /><strong>Mana saqillasqa kaspaykiqa, ama qillqarimuychu iskaychay hayñi ''(copyright)'' nisqayuq qillqakunata iskaychamuspa!</strong>",
'copyrightwarning2'         => "Lliw {{SITENAME}}paq llamk'apuykunaqa huk ruraqkunap llamk'apunallanmi, hukchanallanmi icha qullunallanmi. Llamk'asqaykikunata huk runakunap allinchayninta qispilla mast'ariyninta mana munaptiykiqa, ama kayman qillqamuychu.<br />
Takyachiwachkankim: Kayqa ñuqap qillqasqaymi, ñuqamanmi kapuwan icha qispi pukyumanta iskaychamusqaymi, nispa ($1 p'anqata qhaway).
<br /><strong>Mana saqillasqa kaspaykiqa, ama qillqarimuychu iskaychay hayñi ''(copyright)'' nisqayuq qillqakunata iskaychamuspa!</strong>",
'longpagewarning'           => "<strong>Paqtataq: Kay p'anqaqa $1 kB hatunmi; huk wamp'unakunaqa sasachakunmanchá 32 kB-manta aswan hatun willañiqita llamk'apuspa.
Ama hina kaspa, hamut'ariy kay p'anqata rakiyta.</strong>",
'longpageerror'             => '<strong>PANTASQA: Kachasqayki qillqaqa $1 kB hatunmi, $2 kB-manta aswan hatunmi. Manam waqaychasqa kayta atinchu.</strong>',
'readonlywarning'           => "<strong>PAQTATAQ: Willañiqintinqa hark'asqam mit'awa kakuchinapaq. Chayrayku kunanqa manam atichkankichu llamk'apusqaykikunata waqaychayta.
Qillqasqaykita iskaychaspa antañiqiqniykipi willañiqiman llut'amuspa chaypi waqaychariy. Kunanmanta huk pachallapi musuqmanta waqaychaykachay.</strong>",
'protectedpagewarning'      => "<strong>PAQTATAQ: Kay p'anqaqa llamk'apuymanta amachasqam kamachiqkunallap hukchananpaq.</strong>",
'semiprotectedpagewarning'  => "'''Musyay:''' Kay p'anqaqa amachasqam rakiqunayuq ruraqkunallap hukchananpaq.",
'cascadeprotectedwarning'   => "'''Paqtataq:''' Kay p'anqaqa amachasqam, kamachiqkunallam llamk'apuyta atin, ''phaqcha'' nisqa kamachiwan amachasqa kay {{PLURAL:$1|p'anqapim|p'anqakunapim}} ch'aqtasqa kaspanmi:",
'templatesused'             => "Kay p'anqapi llamk'achisqa plantillakuna:",
'templatesusedpreview'      => "Kay qhawariypi llamk'achisqa plantillakuna:",
'templatesusedsection'      => "Kay p'anqa rakipi llamk'achisqa plantillakuna:",
'template-protected'        => '(amachasqa)',
'template-semiprotected'    => '(rakilla amachasqa)',
'nocreatetitle'             => "P'anqa kamariyqa saywachasqam",
'nocreatetext'              => "Kay wikipiqa saywachasqam musuq p'anqakunata kamariy. Ñawpaqman kutiytam atinkiman kachkaqña p'anqata llamk'apuspa. Astawantaq, [[Special:Userlogin|yaykuy icha musuq rakiqunata kichariy]].",
'nocreate-loggedin'         => "Manam saqillasunkichu kay wikipi musuq p'anqakunata kamariyta.",
'permissionserrors'         => 'Saqillay pantasqakuna',
'permissionserrorstext'     => 'Manam saqillasunkichu, {{PLURAL:$1|kayraykum|kayraykum}}:',
'recreate-deleted-warn'     => "'''Paqtataq: Ñawpaqta qullusqaña p'anqatam musuqmanta kamarichkanki.'''

Hamut'arillay, chayaqillachu manallachu kay p'anqata kamariy.
Kaymi kay p'anqamanta qulluy hallch'a:",

# "Undo" feature
'undo-success' => 'Rurasqata kutichiyta atinkim. Manaraq kutichispaykiqa, kay qatiq wakichayta qhawariy rikunaykipaq chiqapta munasqaykichu manallachu, chaymantataq waqaychay kutichinapaq.',
'undo-failure' => "Manam atinichu llamk'apusqata kutichiyta, huk ruraqtaq musuqta llamk'apurquptinñam.",
'undo-summary' => '[[Special:Contributions/$2|$2]]-pa $1 hukchasqanta kutichisqa ([[User talk:$2|rimay]])',

# Account creation failure
'cantcreateaccounttitle' => 'Manam atinichu rakiqunata kichayta',
'cantcreateaccount-text' => "Kay IP tiyaymanta (<b>$1</b>) rakiquna kichariyqa [[User:$3|$3]]-pa hark'asqanmi.

$3-qa nirqan kayraykum: ''$2''",

# History pages
'revhistory'          => "Mawk'a llamk'apusqakuna",
'viewpagelogs'        => "Kay p'anqamanta hallch'akunata qhaway",
'nohistory'           => "Kay p'anqamantaqa manam llamk'apuy wiñay kawsay kanchu.",
'revnotfound'         => "Llamk'apusqaqa manam tarisqachu",
'revnotfoundtext'     => "Mañakusqayki llamk'apusqaqa manam tarisqachu.
Ama hina kaspa, kay p'anqap URL nisqa tiyayninta k'uskiriy.",
'loadhist'            => "P'anqamanta wiñay kawsayta chaskichkaspa",
'currentrev'          => 'Kunan hukchasqa',
'revisionasof'        => "$1-pa llamk'apusqan",
'revision-info'       => "Kayqa p'anqap mawk'a llamk'apusqa kasqanmi, $1 p'unchawpi $2-pa rurasqan",
'previousrevision'    => '← ñawpaq hukchasqa',
'nextrevision'        => 'Qatiq hukchasqa →',
'currentrevisionlink' => 'Kunan hukchasqata qhaway',
'cur'                 => 'kunan',
'next'                => 'qat',
'last'                => 'ñawpaq',
'orig'                => 'qall',
'page_first'          => 'ñawpaqkuna',
'page_last'           => 'qhipaqkuna',
'histlegend'          => "Sut'ichana: (kunan) = p'anqap kunan kachkayninwan huk kaykuna,
(ñawpaq) = ñawpaq kachkasqanwan huk kaykuna, M = aslla hukchasqa",
'deletedrev'          => '[qullusqa]',
'histfirst'           => 'Ñawpaqkuna',
'histlast'            => 'Qhipaqkuna',
'historyempty'        => "(ch'usaq)",

# Revision feed
'history-feed-title'          => 'Hukchasqakunap wiñay kawsaynin',
'history-feed-description'    => "Kay p'anqata hukchasqakunap wiñay kawsaynin",
'history-feed-item-nocomment' => '$1, $2-pi', # user at time
'history-feed-empty'          => "Mañakusqayki p'anqaqa manam kanchu.
Wikimanta qullusqachá icha astasqachá.
Musuq chaniyuq p'anqakunata [[Special:Search|wikipi maskaykachay]].",

# Revision deletion
'rev-deleted-comment'         => '(qullusqa rimapuy)',
'rev-deleted-user'            => '(qullusqa ruraqpa sutin)',
'rev-deleted-event'           => "(qullusqa hallch'a)",
'rev-deleted-text-permission' => "<div class=\"mw-warning plainlinks\">
P'anqamanta kay llamk'apusqaqa uyana hallch'akunamanta qullusqam.
Astawan rikunki [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} qulluy hallch'apichá].
</div>",
'rev-deleted-text-view'       => "<div class=\"mw-warning plainlinks\">
P'anqamanta kay llamk'apusqaqa uyana hallch'akunamanta qullusqam.
Kay wikipi kamachiq kaspaykim rikuyta atinkim;
astawan rikunki [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} qulluy hallch'apichá].
</div>",
'rev-delundel'                => 'rikuchiy/pakay',
'revisiondelete'              => "Mawk'a llamk'apusqakunata qulluy/paqarichiy",
'revdelete-nooldid-title'     => "Taripana llamk'apusqaqa manam kanchu",
'revdelete-nooldid-text'      => "Manam taripana llamk'apusqata akllarqunkichu imawanchus kay ruranata aknachanaykipaq.",
'revdelete-selected'          => "{{PLURAL:$2|Akllasqa llamk'apusqa|Akllasqa llamk'apusqakuna}} [[:$1]]-manta:",
'logdelete-selected'          => "{{PLURAL:$2|Huk akllasqa tukusqa|$2 akllasqa tukusqakuna}} hallch'api '''$1'''-paq:",
'revdelete-text'              => "Qullusqa llamk'apusqakunaqa p'anqap wiñay kawsayninpi paqarinqaraqmi, samiqnintaq manam uyanalla qhawanapaqchu.

Kay wikipi huk kamachiqkunaqa p'anqap pakasqa samiqninta qhawaspa qullusqa kaymanta kutichiyta atinkuraqmi kay kaqlla uyapuratam llamk'achispa, kay wikip kamariqninkuna mana huk saywachanakunata tiyachiptinqa.",
'revdelete-legend'            => "Llamk'apuypaq saywachanakunata tiyachiy:",
'revdelete-hide-text'         => 'Qhawana qillqata pakay',
'revdelete-hide-name'         => 'Rurayta paqtaytapas pakay',
'revdelete-hide-comment'      => "Llamk'apuymanta willapuyta pakay",
'revdelete-hide-user'         => 'Ruraqpa sutinta/IP huchhanta pakay',
'revdelete-hide-restricted'   => 'Aplicar estas restricciones a los administradores tal como al resto',
'revdelete-suppress'          => 'Eliminar datos de los administradores tal como al resto',
'revdelete-hide-image'        => 'Ocultar el contenido del archivo',
'revdelete-unsuppress'        => 'Eliminar restricciones de revisiones restauradas',
'revdelete-log'               => 'Comentario de registro:',
'revdelete-submit'            => 'Aplicar a la revisión seleccionada',
'revdelete-logentry'          => 'cambiada la visibilidad de la revisión para [[$1]]',
'logdelete-logentry'          => 'cambiada la visibilidad de eventos de [[$1]]',
'revdelete-logaction'         => '$1 {{PLURAL:$1|revisión|revisiones}} en modo $2',
'logdelete-logaction'         => '$1 {{PLURAL:$1|evento|eventos}} a [[$3]] en modo $2',
'revdelete-success'           => 'Visibilidad de revisiones cambiada correctamente.',
'logdelete-success'           => 'Visibilidad de eventos cambiada correctamente.',

# Oversight log
'oversightlog'    => 'Registro de descuidos',
'overlogpagetext' => 'A continuación se muestra una lista de los borrados y bloqueos más recientes relacionados con contenidos ocultos de los operadores del sistema. Consulte la [[Special:Ipblocklist|lista de IPs bloqueadas]] para ver una lista de los bloqueos actuales.',

# Diffs
'history-title'             => '"$1" p\'anqata hukchasqakunap wiñay kawsaynin',
'difference'                => '(Hukchasqapura wak kaynin)',
'loadingrev'                => "diff nisqapaq llamk'apusqata chaskichkaspa",
'lineno'                    => "Siq'i $1:",
'editcurrent'               => "Kunan kachkaq p'anqata llamk'apuy",
'selectnewerversionfordiff' => 'Aswan ñaqha musuqchasqata akllay wakichanapaq',
'selectolderversionfordiff' => 'Aswan ñawpa musuqchasqata akllay wakichanapaq',
'compareselectedversions'   => "Pallasqa llamk'apusqakunata wakichay",
'editundo'                  => 'kutichiy',
'diff-multi'                => "({{plural:$1|Chawpipi huk llamk'apusqaqa manam rikuchisqachu|Chawpipi $1 llamk'apusqaqa manam rikuchisqachu}}.)",

# Search results
'searchresults'         => 'Maskaymanta tarisqakuna',
'searchresulttext'      => 'Para más información acerca de las búsquedas en {{SITENAME}}, consulte la [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'        => "'''[[:$1]]''' nisqatam maskanki",
'searchsubtitleinvalid' => '"$1" nisqatam maskanki',
'noexactmatch'          => "'''Manam kanchu \"\$1\" sutiyuq p'anqa.''' Munaspaykiqa [[:\$1|kamarillay]].",
'titlematches'          => "P'anqakunap sutinkunapi tarisqa",
'notitlematches'        => "Manam ima p'anqakunap sutinkunapipas tarisqachu",
'textmatches'           => "P'anqakunap qillqankunapi tarisqa",
'notextmatches'         => "Manam ima p'anqakunap qillqankunapipas tarisqachu",
'prevn'                 => '$1 ñawpaq',
'nextn'                 => '$1 qatiq',
'viewprevnext'          => 'Qhaway ($1) ($2) ($3).',
'showingresults'        => 'Qhipanpiqa rikuchkanki <b>$1</b>-kama tarisqakunatam, <b>$2</b> huchhawan qallarispa.',
'showingresultsnum'     => 'Qhipanpiqa rikuchkanki <b>$3</b> tarisqam, <b>$2</b> huchhawan qallarispa.',
'nonefound'             => '<strong>Musyay</strong>: Mana aypalla maskasqaqa paqarin ancha pasaq, mana hallch\'api kaq rimakunata ("kay", "chay", "huk") maskaptiykim, ichataq hukmanta aswan maskana rimakunata quptiykim (tukuy maskana rimayuq p\'anqakunallam paqarinqa).',
'powersearch'           => 'Maskay',
'powersearchtext'       => "Kay suti k'itikunapi maskay:<br />
$1<br />
$2 Pusapunakunata rikuchiy<br />Maskay: $3 $9",
'searchdisabled'        => "{{SITENAME}} nisqapi maskaymanqa ama nisqam. Hinachkaptinqa, maskariy google nisqawan icha huk hawa maskanakunawan, ichataq yuyariy, {{SITENAME}}manta hallch'asqankunaqa manañachá musuqllachu.",

# Preferences page
'preferences'              => 'Allinkachinakuna',
'mypreferences'            => 'Allinkachinaykuna',
'prefs-edits'              => 'Hukchasqakunap yupaynin:',
'prefsnologin'             => 'No está identificado',
'prefsnologintext'         => 'Debes [[Special:Userlogin|entrar]] para cambiar las preferencias de usuario.',
'prefsreset'               => 'Las preferencias han sido restauradas a los valores por defecto.',
'qbsettings'               => 'Preferencias de "Quickbar"',
'qbsettings-none'          => 'Mana imapas',
'qbsettings-fixedleft'     => 'Fija a la izquierda',
'qbsettings-fixedright'    => 'Fija a la derecha',
'qbsettings-floatingleft'  => 'Flotante a la izquierda',
'qbsettings-floatingright' => 'Flotante a la derecha',
'changepassword'           => 'Yaykuna rimata hukchay',
'skin'                     => 'Qara',
'math'                     => 'Minuywa',
'dateformat'               => "P'unchaw pacha chanta",
'datedefault'              => 'Kikinmanta allinkachina',
'datetime'                 => "P'unchaw, pacha",
'math_failure'             => "Manam hap'inichu",
'math_unknown_error'       => 'error desconocido',
'math_unknown_function'    => 'función desconocida',
'math_lexing_error'        => 'error léxico',
'math_syntax_error'        => 'error de sintaxis',
'math_image_error'         => 'La conversión a PNG ha fallado; comprueba que latex, dvips, gs, y convert estén instalados correctamente',
'math_bad_tmpdir'          => 'No se puede escribir o crear el directorio temporal de <em>math</em>',
'math_bad_output'          => 'No se puede escribir o crear el directorio de salida de <em>math</em>',
'math_notexvc'             => 'Falta el ejecutalbe de <strong>texvc</strong>. Por favor, lea <em>math/README</em> para configurarlo.',
'prefs-personal'           => 'Kikinpa willankuna',
'prefs-rc'                 => 'Ñaqha hukchasqakuna',
'prefs-watchlist'          => "Watiqasqa p'anqakuna",
'prefs-watchlist-days'     => "Hayk'a p'unchawta watiqana sutisuyupi rikuchiy:",
'prefs-watchlist-edits'    => "Hayk'a hukchasqakunata hatunchasqa watiqana sutisuyupi rikuchiy:",
'prefs-misc'               => 'Ñawra',
'saveprefs'                => 'Allinkachinakunata waqaychay',
'resetprefs'               => 'Ñawpaq allinkachinakunaman kutichiy',
'oldpassword'              => "Mawk'a yaykuna rima:",
'newpassword'              => 'Musuq yaykuna rima:',
'retypenew'                => 'Musuq yaykuna rimaykita sinchichay:',
'textboxsize'              => "Llamk'apusqa",
'rows'                     => "Siq'ikuna:",
'columns'                  => 'Tunukuna:',
'searchresultshead'        => 'Maskay',
'resultsperpage'           => "Huk p'anqapi hayk'a tarinakuna:",
'contextlines'             => "Siq'ikuna taripasqaman:",
'contextchars'             => "Ukhunpuray sananchakuna siq'iman:",
'recentchangesdays'        => "Ñaqha hukchasqakunapi rikuchina p'unchawkuna:",
'recentchangescount'       => "Ñaqha hukchasqakunapi p'anqa sutikuna",
'savedprefs'               => "Allinkachinaykikunaqa hallch'asqañam.",
'timezonelegend'           => "Pacha t'urpi",
'timezonetext'             => 'Indique el número de horas de diferencia entre su hora local y la hora del servidor (UTC).',
'localtime'                => 'Hora local',
'timezoneoffset'           => 'Diferencia¹',
'servertime'               => 'La hora en el servidor es',
'guesstimezone'            => 'Pacha suyuta chaskimuy',
'allowemail'               => 'Huk ruraqkunamanta e-chaskita saqillay',
'defaultns'                => "Kay suti k'itikunapi kikinmanta maskay:",
'default'                  => 'kikinmanta',
'files'                    => 'Willañiqikuna',

# User rights
'userrights-lookup-user'     => 'Ruraqkunap huñunkunata allinkachiy',
'userrights-user-editname'   => 'Ruraqpa sutinta qillqamuy:',
'editusergroup'              => 'Ruraqkunap huñunkunata hukchay',
'userrights-editusergroup'   => 'Ruraqkunap huñunkunata hukchay',
'saveusergroups'             => 'Ruraq huñukunata waqaychay',
'userrights-groupsmember'    => 'Kayman kapuq:',
'userrights-groupsavailable' => 'Makihawa huñukuna:',
'userrights-groupshelp'      => "Ruraqkunap huñunkunata akllay ruraqta qichunaykipaq icha yapanaykipaq. Mana akllasqa huñukunaqa manam hukchasqachu kanqa. Ukuchapi lluq'i ñit'inata CTRL ñit'inatapas t'uyllata ñit'ispa akllasqata puchukachinkim.",
'userrights-reason'          => 'Imarayku hukchasqa:',

# Groups
'group'               => 'Huñu:',
'group-autoconfirmed' => 'Rakiqunayuq ruraqkuna',
'group-bot'           => 'Rurana antachakuna',
'group-sysop'         => 'Kamachiqkuna',
'group-bureaucrat'    => 'Burukratakuna',
'group-all'           => '(tukuy)',

'group-autoconfirmed-member' => 'Rakiqunayuq ruraq',
'group-bot-member'           => 'Rurana antacha',
'group-sysop-member'         => 'Kamachiq',
'group-bureaucrat-member'    => 'Burócrata',

'grouppage-bot'        => '{{ns:project}}:Bot',
'grouppage-sysop'      => '{{ns:project}}:Kamachiq',
'grouppage-bureaucrat' => '{{ns:project}}:Burukrata',

# User rights log
'rightslog'      => 'Ruraqpa hayñinkunap hukyasqankuna',
'rightslogtext'  => "Kayqa hayñi hukchasqa hallch'aymi.",
'rightslogentry' => 'hukchan $1-pa hayñinkunata $2-manta $3-man',
'rightsnone'     => '(-)',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|hukchasqa|hukchasqakuna}}',
'recentchanges'                     => 'Ñaqha hukchasqa',
'recentchangestext'                 => "Kay p'anqapiqa aswan qhipaq ñaqha hukchasqakunam.",
'recentchanges-feed-description'    => 'Kay mikhuchinapi wikipi qhipaq ñaqha hukchasqakunata qatiy.',
'rcnote'                            => "Kay qatiqpiqa qhipaq <b>$1</b> hukchasqakunam qhipaq <b>$2</b> p'unchawpi, musuqchasqa $3",
'rcnotefrom'                        => 'Kay qatiqpiqa <b>$2</b>-mantapacha (<b>$1</b>-kama) hukchasqakunatam rikunki.',
'rclistfrom'                        => '$1-manta musuq hukchasqakunata rikuchiy',
'rcshowhideminor'                   => "$1 uchuylla llamk'apusqakunata",
'rcshowhidebots'                    => '$1 rurana antachakunata',
'rcshowhideliu'                     => "$1 hallch'asqa ruraqkunata",
'rcshowhideanons'                   => '$1 IP-niyuq ruraqkunata',
'rcshowhidepatr'                    => "$1 patrullachasqa llamk'apusqakunata",
'rcshowhidemine'                    => "$1 llamk'apusqaykunata",
'rclinks'                           => "Qhipaq $1 hukchasqata qhipaq $2 p'unchawmanta qhaway.<br />$3",
'diff'                              => 'dif',
'hist'                              => 'wñka',
'hide'                              => 'pakay',
'show'                              => 'rikuchiy',
'minoreditletter'                   => 'a',
'newpageletter'                     => 'M',
'boteditletter'                     => 'r',
'number_of_watching_users_pageview' => '[$1 watiqachkaq {{PLURAL:$1|ruraq|ruraqkuna}}]',
'rc_categories'                     => 'Kay katiguriyakunaman saywachay ("|" nisqawan rakisqa)',
'rc_categories_any'                 => 'Imapas',
'newsectionsummary'                 => 'Musuq raki: /* $1 */',

# Recent changes linked
'recentchangeslinked'          => "Hukchasqa t'inkimuq",
'recentchangeslinked-title'    => "$1-wan t'inkisqa hukchasqa",
'recentchangeslinked-noresult' => "Nisqa mit'apiqa manam hukchasqa t'inkimuqkuna kanchu.",
'recentchangeslinked-summary'  => "Kay sapaq p'anqaqa t'inkisqa p'anqakunapi ñaqha hukchasqakunatam rikuchin. Watiqasqayki p'anqakunaqa '''yanasapa qillqasqam'''.",

# Upload
'upload'                      => 'Willañiqita churkuy',
'uploadbtn'                   => 'Willañiqita churkuy',
'reupload'                    => 'Huk kutita churkuy',
'reuploaddesc'                => "Churkuna hunt'ana p'anqaman kutimuy.",
'uploadnologin'               => 'Manaraqmi yaykurqunkichu',
'uploadnologintext'           => '[[Special:Userlogin|Yaykunaykim]] atin willañiqikunata churkunaykipaq.',
'upload_directory_read_only'  => "Llika sirwiqqa manam atinchu churkuna hallch'aman ($1) qillqayta.",
'uploaderror'                 => 'Willañiqita churkunayaptiyki pantasqam tukurqan',
'uploadtext'                  => "Willañiqita churkunaykipaqqa kay qatiqpi kaq hunt'ana p'anqata llamk'achiy. Churkusqaña rikchakunatataq qhawanaykipaq icha maskanaykipaqqa [[Special:Imagelist|rikchakuna p'anqaman]] riy. Churkusqakunata qullusqakunatapas [[Special:Log/upload|churkuy hallch'apim]] rikunki.

Rikchata huk p'anqaman ch'aqtanaykipaqqa kay hunt'ana p'anqapi t'inkita llamk'achiy: '''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:Willañiqi.jpg]]</nowiki>''', '''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:Willañiqi.png|huk qillqa]]</nowiki>''' icha
'''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:Willañiqi.ogg]]</nowiki>''' (willañiqiman chiqalla t'inkinapaq).",
'uploadlog'                   => "churkuy hallch'a",
'uploadlogpage'               => 'Churkusqa willañiqikuna',
'uploadlogpagetext'           => 'Kay qatiqpiqa ñaqha willañiqi churkusqakunam. Pachakunaqa sirwiqpa rurasqanmi.',
'filename'                    => 'Willañiqip sutin',
'filedesc'                    => 'Pisichay',
'fileuploadsummary'           => "T'iktu:",
'filestatus'                  => 'Ima hayñiyuq',
'filesource'                  => 'Pukyu',
'uploadedfiles'               => 'Churkusqa willañiqikuna',
'ignorewarning'               => 'Paqtataq waqyayta qhawarparispa waqaychay.',
'ignorewarnings'              => 'Ima paqtataq waqyaytapas qhawarpariy',
'minlength1'                  => 'Willañiqip sutinqa huk icha aswan sanampayuq kachun.',
'illegalfilename'             => "«$1» nisqa williñiqip sutinqa p'anqa umallipaq mana allin sananchayuqmi. Ama hina kaspa, williñiqita sutincharaspa musuqmanta churkuykachay.",
'badfilename'                 => 'Rikch\'ap sutinqa "$1"-man hukchasqam.',
'filetype-badmime'            => '"$1" MIME layayuq willañiqikunata churkuyqa manam saqillasqachu.',
'filetype-badtype'            => "'''\".\$1\"''' nisqaqa mana munasqa willañiqi layam. Kaymi munasqa willañiqi layakuna: \$2",
'filetype-missing'            => 'Manam kachkanchu willañiqip k\'askaqnin (".jpg" hina).',
'large-file'                  => 'Se recomienda que los archivos no sean mayores de de $1; este archivo ocupa $2.',
'largefileserver'             => 'El tamaño de este archivo es mayor del que este servidor admite por configuración.',
'emptyfile'                   => 'El archivo que has intentado subir parece estar vacío; por favor, verifica que realmente se trate del archivo que intentabas subir.',
'fileexists'                  => "Ya existe un archivo con este nombre. Por favor compruebe el existente $1 si no está seguro de querer reemplazarlo.


'''Nota:''' Si finalmente sustituye el archivo, debe refrescar la caché de su navegador para ver los cambios:
*'''Mozilla''' / '''Firefox''': Pulsa el botón '''Recargar''' (o '''ctrl-r''')
*'''Internet Explorer''' / '''Opera''': '''ctrl-f5'''
*'''Safari''': '''cmd-r'''
*'''Konqueror''': '''ctrl-r''",
'fileexists-extension'        => 'Existe un archivo con un nombre similar:<br />
Nombre del archivo que se está subiendo: <strong><tt>$1</tt></strong><br />
Nombre del archivo ya existente: <strong><tt>$2</tt></strong><br />
Por favor, elige un nombre diferente.',
'fileexists-thumb'            => "<center>'''Kachkaq rikch'a'''</center>",
'fileexists-thumbnail-yes'    => 'El archivo parece ser una imagen de tamaño reducido <i>(thumbnail)</i>. Por favor comprueba el archivo <strong><tt>$1</tt></strong>.<br />
Si el archivo comprobado es la misma imagen a tamaño original no es necesario subir un thumbnail más.',
'file-thumbnail-no'           => 'El nombre del archivo comienza con <strong><tt>$1</tt></strong>. Parece ser una imagen de tamaño reducido <i>(thumbnail)</i>.
Si tienes esta imagen a toda resolución súbela, si no, por favor cambia el nombre del archivo.',
'fileexists-forbidden'        => 'Ya existe un archivo con este nombre. Por favor, cambie el nombre del archivo y vuelva a subirlo. [[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => "Kay sutiyuq willañiqiqa kachkañam rakinakusqa willañiqi qullqapi; ama hina kaspa, ñawpaq p'anqaman kutispa willañiqiykita huk sutiwan churkuy. [[Image:$1|thumb|center|$1]]",
'successfulupload'            => 'Aypalla churkusqañam',
'uploadwarning'               => 'Willañiqi churkuymanta paqtataq niy',
'savefile'                    => 'Willañiqita waqaychay',
'uploadedimage'               => '«[[$1]]» churkusqa.',
'overwroteimage'              => '"[[$1]]" musuqmanta churkusqa',
'uploaddisabled'              => 'Willañiqi churkuyman ama nisqa',
'uploaddisabledtext'          => 'Kay wikipiqa willañiqita churkuy manam saqillasqachu.',
'uploadscripted'              => 'Este archivo contiene script o código HTML que puede ser interpretado erróneamente por un navegador.',
'uploadcorrupt'               => 'Este archivo está corrupto o la extensión indicada no se corresponde con el tipo de archivo. Por favor, comprueba el archivo y vuelve a subirlo.',
'uploadvirus'                 => 'Willañiqipiqa añawmi! Yuyay: $1',
'sourcefilename'              => 'Qallariy willañiqip sutin',
'destfilename'                => 'Tukuna willañiqip sutin',
'watchthisupload'             => "Kay p'anqata watiqay",
'filewasdeleted'              => 'Un archivo con este nombre se subió con anterioridad y posteriormente ha sido borrado. Deberías revisar el $1 antes de subirlo de nuevo.',

'upload-proto-error'      => 'Protocolo incorrecto',
'upload-proto-error-text' => 'Para subir archivos desde otra página la URL debe comenzar por <code>http://</code> o <code>ftp://</code>.',
'upload-file-error'       => 'Error interno',
'upload-file-error-text'  => 'Ha ocurrido un error interno mientras se intentaba crear un fichero temporal en el servidor. Por favor, contacta con un administrador del sistema.',
'upload-misc-error'       => 'Error desconocido en la subida',
'upload-misc-error-text'  => 'Ha ocurrido un error durante la subida. Por favor verifica que la URL es válida y accesible e inténtalo de nuevo. Si el problema persiste, contacta con un administrador del sistema.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'No se pudo alcanzar la URL',
'upload-curl-error6-text'  => 'La URL no pudo ser alcanzada. Por favor comprueba que la URL es correcta y el sitio web está funcionando.',
'upload-curl-error28'      => 'Tiempo de espera excedido',
'upload-curl-error28-text' => 'La página tardó demasiado en responder. Por favor, compruebe que el servidor está funcionando, espere un poco y vuelva a intentarlo. Quizás desee intentarlo en otro momento de menos carga.',

'license'            => 'Saqillay',
'nolicense'          => 'Manam imapas akllasqachu',
'license-nopreview'  => '(Ama qhawarichunkuchu)',
'upload_source_url'  => ' (una URL válida y accesible públicamente)',
'upload_source_file' => ' (un archivo en su ordenador)',

# Image list
'imagelist'                 => "Rikch'akuna",
'imagelisttext'             => 'Abajo hay una lista de $1 imágenes ordenadas $2.',
'getimagelist'              => ' obteniendo la lista de imágenes',
'ilsubmit'                  => 'Maskhay',
'showlast'                  => 'Mostrar las últimas $1 imágenes ordenadas  $2.',
'byname'                    => 'sutikama',
'bydate'                    => "p'unchawkama",
'bysize'                    => 'hatun kaykama',
'imgdelete'                 => 'qull',
'imgfile'                   => 'willañiqi',
'filehist'                  => 'Willañiqip wiñay kawsaynin',
'filehist-help'             => "P'unchaw/pacha nisqapi ñit'iy chaypacha willañiqi kachkasqata qhawanaykipaq.",
'filehist-deleteall'        => 'tukuyta qulluy',
'filehist-deleteone'        => 'kayta qulluy',
'filehist-revert'           => 'kutichiy',
'filehist-current'          => 'kunan',
'filehist-datetime'         => "P'unchaw/Pacha",
'filehist-user'             => 'Ruraq',
'filehist-dimensions'       => 'Chhikanyachikuqkuna',
'filehist-filesize'         => 'Willañiqip chhikan kaynin',
'filehist-comment'          => 'Willapuy',
'imagelinks'                => "Rikch'aman t'inkimuq",
'linkstoimage'              => "Kay rikch'amanqa qatiq p'anqakunam t'inkimun:",
'nolinkstoimage'            => "Kay rikchamanqa manam ima p'anqakunachu t'inkimun.",
'sharedupload'              => "Kay p'anqaqa rakinakusqallam churkusqa huk ruraykamaykunapipas llamk'achinapaq.",
'shareduploadwiki'          => 'Puede consultar $1 para más información.',
'shareduploadwiki-linktext' => "willañiqimanta t'iktuna p'anqa",
'noimage'                   => 'Manam kanchu kay sutiyuq willañiqi, $1ta atinki.',
'noimage-linktext'          => 'churkuy',
'uploadnewversion-linktext' => 'Kay willañiqi ñaqha musuqchasqata churkuy',
'imagelist_date'            => "P'unchaw",
'imagelist_name'            => 'Suti',
'imagelist_user'            => 'Ruraq',
'imagelist_size'            => 'Hatun kay',
'imagelist_description'     => "T'iktuna",
'imagelist_search_for'      => "Rikch'ap sutinta maskay:",

# File reversion
'filerevert'                => '$1-ta kutichiy',
'filerevert-legend'         => 'Willañiqita kutichiy',
'filerevert-defaultcomment' => '$2, $1 nisqa musuqchasqaman kutichisqa',
'filerevert-submit'         => 'Kutichiy',

# File deletion
'filedelete'            => '$1-ta qulluy',
'filedelete-legend'     => 'Willañiqita qulluy',
'filedelete-intro'      => "'''[[Media:$1|$1]]'''-tam qulluchkanki.",
'filedelete-intro-old'  => '<span class="plainlinks">\'\'\'[[Media:$1|$1]]\'\'\' musuqchasqatam qulluchkanki [$4 $3, $2] nisqamanta.</span>',
'filedelete-comment'    => 'Willapuy:',
'filedelete-submit'     => 'Qulluy',
'filedelete-success'    => "'''$1''' qullusqañam.",
'filedelete-nofile-old' => "No existe una versión guardada de '''$1''' con los atributos especificados.",

# MIME search
'mimesearch'         => 'MIME maskay',
'mimesearch-summary' => "Kay p'anqawanqa willañiqikunata MIME layankamam ch'illchiyta atinki. Qunapaq: contenttype/subtype, ahinataq <tt>image/jpeg</tt>.",
'mimetype'           => 'MIME laya:',
'download'           => 'chaqnamuy',

# Unwatched pages
'unwatchedpages' => "Mana watiqasqa p'anqakuna",

# List redirects
'listredirects' => 'Tukuy pusapuykuna',

# Unused templates
'unusedtemplates'     => "Mana llamk'achisqa plantillakuna",
'unusedtemplatestext' => "Kay p'anqapi tukuy plantilla suti k'itipi kaq, manataq huk p'anqapi ch'aqtasqa p'anqakunap sutinkunam. Yuyariy, manaraq qulluspayki chay p'anqakunaman t'inkikunata qhaway.",
'unusedtemplateswlh'  => "huk t'inkikuna",

# Random page
'randompage'         => "Mayninpi p'anqa",
'randompage-nopages' => "Manam kanchu kay suti k'itipi p'anqakuna.",

# Random redirect
'randomredirect'         => "Mayninpi pusapuna p'anqa",
'randomredirect-nopages' => "Manam kanchu kay suti k'itipi pusapuna p'anqakuna.",

# Statistics
'statistics'             => 'Ranuy (kanchachani)',
'sitestats'              => '{{SITENAME}} tiyaymanta ranuy',
'userstats'              => 'Ruraqmanta ranuy',
'sitestatstext'          => "Willañiqintinpiqa {{PLURAL:$1|'''1''' p'anqam|'''$1''' p'anqakunam}} kachkan.
Kaypi ch'aqtasqaqa rimanakuymi, {{SITENAME}}manta p'anqakunam, ch'iñicha tuna qillqakunam, pusapuna p'anqakunam, huk manachá samiqniyuqchu kaq p'anqakunapas.
Chaykunata mana yupaptinchikqa, {{PLURAL:$2|huklla samiqniyuq qillqa p'anqachá|'''$2''' samiqniyuq qillqa p'anqachá}} kachkan.

Sirwiqpiqa '''$8''' {{PLURAL:$8|churkusqa willañiqim|churkusqa willañiqikunam}} kachkan.

Kay wikip qallarisqanmantaqa '''$3''' kutiñam {{PLURAL:$3|watukusqa|watukusqa}}, '''$4''' kutitaqmi {{PLURAL:$4|p'anqa llamk'apusqa|p'anqakuna llamk'apusqa}} karqan.
Chaymantaqa yurinmi: kuskanchaku '''$5''' {{PLURAL:$5|llamk'apusqa|llamk'apusqa}} p'anqaman, '''$6''' {{PLURAL:$6|watukusqa|watukusqa}} llamk'apusqaman.

[http://meta.wikimedia.org/wiki/Help:Job_queue Llamk'ana chupaqa] '''$7''' sunim.",
'userstatstext'          => "{{PLURAL:$1|'''1''' rakiqunayuq ruraqmi|'''$1''' rakiqunayuq ruraqkunam}} kachkan,
paypurataq '''$2''' ('''$4%'''-nin) $5 hayñiyuqmi.",
'statistics-mostpopular' => "Lliwmanta astawan rikusqa p'anqakuna",

'disambiguations'      => "Sut'ichana qillqakuna",
'disambiguationspage'  => 'Template:Disambig',
'disambiguations-text' => "Kay qatiq p'anqakunam t'inkimun sut'ichana qillqaman. Chiqap, hukchanasqa p'anqaman t'inkichunman.<br />Tukuy [[:Plantilla:Disambig]] plantillayuq p'anqakunaqa sut'ichana qillqam.",

'doubleredirects'     => 'Iskaylla pusapunakuna',
'doubleredirectstext' => "<b>Paqtataq:</b> Kay p'anqapiqa pantasqalla p'anqa sutikunachá rikuchisqa kayta atinman, ñawpaq kaq #REDIRECT nisqap qhipanpi t'inkiyuq p'anqakuna.<br />
Kay p'anqapiqa huk pusapuna p'anqaman pusapuq p'anqakunap sutinkunatam rikunki. Sapa siq'ipiqa ñawpaq ñiqin, iskay ñiqinpas pusapunaman t'inkikunam, iskay ñiqin pusapunap taripananpa qallariyninpas, sapsilla chiqap allin qillqam.",

'brokenredirects'        => 'Panta pusapunakuna',
'brokenredirectstext'    => "Kay pusapuna p'anqakunaqa mana kachkaq p'anqamanmi pusapuchkan.",
'brokenredirects-edit'   => "(llamk'apuy)",
'brokenredirects-delete' => '(qulluy)',

'withoutinterwiki'        => "Interwiki t'inkinnaq p'anqakuna",
'withoutinterwiki-header' => 'Las siguientes páginas no enlazan a versiones en otros idiomas:',

'fewestrevisions' => "Aslla kuti llamk'apusqa p'anqakuna",

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|byte}}',
'ncategories'             => '$1 {{PLURAL:$1|categoría|categorías}}',
'nlinks'                  => "$1 {{PLURAL:$1|t'inki|t'inkikuna}}",
'nmembers'                => '$1 {{PLURAL:$1|qillqa|qillqakuna}}',
'nrevisions'              => '$1 {{PLURAL:$1|revisión|revisiones}}',
'nviews'                  => '$1 {{PLURAL:$1|vista|vistas}}',
'specialpage-empty'       => "Kay p'anqaqa ch'usaqmi.",
'lonelypages'             => "Wakcha p'anqakuna",
'lonelypagestext'         => 'Ninguna página de este wiki enlaza a las listadas aquí.',
'uncategorizedpages'      => "Katiguriyannaq p'anqakuna",
'uncategorizedcategories' => 'Katiguriyannaq katiguriyakuna',
'uncategorizedimages'     => "Katiguriyannaq rikch'akuna",
'uncategorizedtemplates'  => 'Katiguriyannaq plantillakuna',
'unusedcategories'        => "Mana llamk'achisqa katiguriyakuna",
'unusedimages'            => "Mana llamk'achisqa rikch'akuna",
'popularpages'            => "Munasqa p'anqakuna",
'wantedcategories'        => 'Muchusqa katiguriyakuna',
'wantedpages'             => "Muchusqa p'anqakuna",
'mostlinked'              => "Lliwmanta aswan t'inkimuqniyuq qillqakuna",
'mostlinkedcategories'    => "Lliwmanta aswan t'inkimuqniyuq katiguriyakuna",
'mostlinkedtemplates'     => "Lliwmanta aswan t'inkimuqniyuq plantillakuna",
'mostcategories'          => "Lliwmanta aswan katiguriyayuq p'anqakuna",
'mostimages'              => "Lliwmanta astawan llamk'achisqa rikch'akuna",
'mostrevisions'           => 'Lliwmanta aswan hukchasqayuq qillqakuna',
'allpages'                => "Tukuy p'anqakuna",
'prefixindex'             => "P'anqakuna, ñawpa k'askaqchakama",
'shortpages'              => "Uchuylla p'anqakuna",
'longpages'               => "Hatun p'anqakuna",
'deadendpages'            => "Lluqsinannaq p'anqakuna",
'deadendpagestext'        => "Kay p'anqakunaqa mana ima p'anqakunamanpas t'inkimunchu.",
'protectedpages'          => "Amachasqa p'anqakuna",
'protectedpagestext'      => "Kay p'anqakunaqa llamk'apuymanta icha astaymanta amachasqam",
'protectedpagesempty'     => "Kay [[kuskanachina tupu]]kunawan amachasqa p'anqakunaqa manam kachkanchu.",
'listusers'               => 'Tukuy ruraqkuna',
'specialpages'            => "Sapaq p'anqakuna",
'spheading'               => "Sapaq p'anqakuna",
'restrictedpheading'      => "Kamachiqkunallapaq sapaq p'anqakuna",
'rclsub'                  => '("$1"-manta t\'inkisqa p\'anqakunaman)',
'newpages'                => "Musuq p'anqakuna",
'newpages-username'       => 'Nombre de usuario',
'ancientpages'            => "Ñawpaqta qallarisqa p'anqakuna",
'intl'                    => "Interwiki t'inkikuna",
'move'                    => 'Astay',
'movethispage'            => "Kay p'anqata astay",
'unusedimagestext'        => '<p>Por favor, ten en cuenta que otros sitios web pueden enlazar a una imagen directamente con su URL, y de esa manera no aparecer listados aquí pese a estar en uso.</p>',
'unusedcategoriestext'    => 'Las siguientes categorías han sido creadas, pero ningún artículo o categoría las utiliza.',
'notargettitle'           => 'No hay página objetivo',
'notargettext'            => 'Especifique sobre qué página desea llevar a cabo esta acción.',

# Book sources
'booksources'               => 'Liwrukunapi pukyukuna',
'booksources-search-legend' => 'Liwrukunapi pukyukunata maskay',
'booksources-go'            => 'Riy',
'booksources-text'          => 'Abajo hay una lista de enlaces a otros sitios que venden libros nuevos y usados, puede que contengan más información sobre los libros que estás buscando.',

'categoriespagetext' => 'Existen las siguientes categorías en este wiki.',
'data'               => 'Willakuna',
'userrights'         => 'Ruraqkunata saqillanap allinkachinan',
'groups'             => 'Ruraq huñukuna',
'alphaindexline'     => '$1-ta $2-man',
'version'            => 'Musuqchasqa',

# Special:Log
'specialloguserlabel'  => 'Ruraq:',
'speciallogtitlelabel' => 'Sutichay:',
'log'                  => "Hallch'asqakuna",
'all-logs-page'        => "Tukuy hallch'akuna",
'log-search-legend'    => "Hallch'asqakunata maskay",
'log-search-submit'    => 'Riy',
'alllogstext'          => 'Vista combinada de todos los registros de {{SITENAME}}.
Puedes filtrar la vista seleccionando un tipo de registro, el nombre del usuario o la página afectada.',
'logempty'             => "Manam hallch'asqakuna kachkanchu.",
'log-title-wildcard'   => "Kaywan qallariq p'anqa sutikunata maskay",

# Special:Allpages
'nextpage'          => "Qatiq p'anqa ($1)",
'prevpage'          => "Ñawpaq p'anqa ($1)",
'allpagesfrom'      => "Rikuchiy kaywan qallariq p'anqakunata:",
'allarticles'       => 'Tukuy qillqasqakuna',
'allinnamespace'    => "Tukuy p'anqakuna ($1 suti k'itipi)",
'allnotinnamespace' => "Tukuy p'anqakuna (manataq $1 suti k'itipi)",
'allpagesprev'      => 'ñawpaq',
'allpagesnext'      => 'qatiq',
'allpagessubmit'    => 'Riy',
'allpagesprefix'    => "Rikuchiy kay k'askaqwan qallariq p'anqakunata:",
'allpagesbadtitle'  => 'El título dado era inválido o tenía un prefijo de enlace inter-idioma o inter-wiki. Puede contener uno o más caracteres que no se pueden usar en títulos.',
'allpages-bad-ns'   => '{{SITENAME}} tiyaypiqa "$1" suti k\'iti manam kanchu.',

# Special:Listusers
'listusersfrom'      => 'Kaywan qallariq ruraqkunata rikuchiy:',
'listusers-submit'   => 'Rikuchiy',
'listusers-noresult' => 'Ruraqqa manam tarisqachu.',

# E-mail user
'mailnologin'     => 'No enviar dirección',
'mailnologintext' => 'Debes [[Special:Userlogin|iniciar sesión]] y tener una dirección electrónica válida en tus [[Special:Preferences|preferencias]] para enviar un correo electrónico a otros usuarios.',
'emailuser'       => 'Kay ruraqman e-chaskita kachay',
'emailpage'       => 'E-chaski kay ruraqman:',
'emailpagetext'   => "Kay ruraq e-chaski imamaytanta allinkachinankunapi qillqakamachiptinqa, kay simihunt'anatam llamk'achiyta atinki e-chaskita kachanaykipaq.
Qampa qillqakamachisqayki imamaytaqa paqarinqa kachasqayki e-chaskipi chaskiqpa kutichisunaykita atinanpaq.",
'usermailererror' => 'El sistema de correo devolvió un error:',
'defemailsubject' => "{{SITENAME}} p'anqamanta chaski",
'noemailtitle'    => 'No hay dirección de correo electrónico',
'noemailtext'     => 'Este usuario no ha especificado una dirección de correo electrónico válida, o ha elegido no recibir correo electrónico de otros usuarios.',
'emailfrom'       => 'Kachaq',
'emailto'         => 'Chaskiq',
'emailsubject'    => 'Yuyancha',
'emailmessage'    => 'Willay',
'emailsend'       => 'Kachay',
'emailccme'       => 'Willaypa iskaychasqan kacharimuway.',
'emailccsubject'  => 'Willaypa iskaychasqan $1: $2-man',
'emailsent'       => 'Chaskiqa kachasqañam',
'emailsenttext'   => 'Chaskiykiqa kachasqañam.',

# Watchlist
'watchlist'            => "Watiqasqa p'anqakuna",
'mywatchlist'          => 'Watiqasqaykuna',
'watchlistfor'         => "('''$1'''-paq)",
'nowatchlist'          => 'Manam watiqasqakunachu kachkan.',
'watchlistanontext'    => 'Para ver o editar las entradas de tu lista de seguimiento es necesario $1.',
'watchnologin'         => 'No ha iniciado sesión',
'watchnologintext'     => 'Debes [[Special:Userlogin|iniciar sesión]] para modificar tu lista de seguimiento.',
'addedwatch'           => 'Watiqasqaykunaman yapasqa',
'addedwatchtext'       => "Kunanqa «[[:\$1]]» sutiyuq p'anqa [[Special:Watchlist|watiqanykipim]] kachkañam. Chay p'anqapi rimachinanpipas hukchanakunaqa kay watiqana p'anqapim rikunki. Watiqasqayki p'anqaqa [[Special:Recentchanges|ñaqha hukchasqakunapi]] '''yanasapa''' qillqasqa rikuchisqa kanqa aswan sikllalla tarinaykipaq. <p>Manaña watiqayta munaptiykiqa, uma siq'ipi \"amaña watiqaychu\" ñit'iy.",
'removedwatch'         => 'Watiqasqakunamanta qullusqa',
'removedwatchtext'     => '"[[:$1]]" sutiyuq p\'anqaqa watiqasqakunamanta qullusqam.',
'watch'                => 'Watiqay',
'watchthispage'        => "Kay p'anqata watiqay",
'unwatch'              => 'Amaña watiqaychu',
'unwatchthispage'      => 'Amaña watiqaychu',
'notanarticle'         => 'No es un artículo',
'watchnochange'        => 'Ninguno de los artículos de tu lista de seguimiento fue editado en el periodo de tiempo mostrado.',
'watchlist-details'    => "{{PLURAL:$1|Huk watiqasqa p'anqa|$1 watiqasqa p'anqakuna}}, rimanakuna p'anqakunata mana yupaspa.",
'wlheader-enotif'      => '* La notificación por correo electrónico está habilitada',
'wlheader-showupdated' => "* Las páginas modificadas desde su última visita aparecen en '''negrita'''",
'watchmethod-recent'   => 'Revisando cambios recientes en busca de páginas vigiladas',
'watchmethod-list'     => 'Revisando las páginas vigiladas en busca de cambios recientes',
'watchlistcontains'    => 'Su lista de seguimiento posee $1 páginas.',
'iteminvalidname'      => "Problema con el artículo '$1', nombre inválido...",
'wlnote'               => 'A continuación se muestran los últimos $1 cambios en las últimas <b>$2</b> horas.',
'wlshowlast'           => "$1 ura, $2 p'unchaw $3-mantapacha hukchasqakunata rikuchiy",
'watchlist-show-bots'  => 'Mostrar ediciones de bots',
'watchlist-hide-bots'  => "Rurana antachakunap llamk'apusqankunata pakay",
'watchlist-show-own'   => 'Mostrar mis ediciones',
'watchlist-hide-own'   => "Ñuqap llamk'apusqaykunata pakay",
'watchlist-show-minor' => 'Mostrar ediciones menores',
'watchlist-hide-minor' => 'Aslla hukchasqakunata pakay',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Watiqasqakunaman yapaspa...',
'unwatching' => 'Watiqasqakunamanta qulluspa...',

'enotif_mailer'                => 'Notificación por correo de {{SITENAME}}',
'enotif_reset'                 => 'Marcar todas las páginas visitadas',
'enotif_newpagetext'           => "Musuq p'anqam.",
'enotif_impersonal_salutation' => 'usuario de {{SITENAME}}',
'changed'                      => 'hukchasqa',
'created'                      => 'kamarirqan',
'enotif_subject'               => 'La página $PAGETITLE de {{SITENAME}} ha sido $CHANGEDORCREATED por $PAGEEDITOR',
'enotif_lastvisited'           => 'Vaya a $1 para ver todos los cambios desde su última visita.',
'enotif_lastdiff'              => 'Vaya a $1 para ver este cambio.',
'enotif_anon_editor'           => 'sutinnaq ruraq $1',
'enotif_body'                  => 'Estimado/a $WATCHINGUSERNAME,

La página de {{SITENAME}} «$PAGETITLE»
ha sido $CHANGEDORCREATED por el usuario $PAGEEDITOR el $PAGEEDITDATE.
La versión actual se encuentra en {{fullurl:$PAGETITLE_RAWURL}}

$NEWPAGE

El resumen de edición es: $PAGESUMMARY $PAGEMINOREDIT

Para comunicarse con el usuario:
por correo electrónico: {{fullurl:Special:Emailuser|target=$PAGEEDITOR_RAWURL}}
en el wiki: {{fullurl:User:$PAGEEDITOR_RAWURL}}

Para recibir nuevas notificaciones de cambios de esta página, deberá visitarla nuevamente.
También puede, en su lista de seguimiento, modificar las opciones de notificación de sus
páginas vigiladas.

             El sistema de notificación de {{SITENAME}}.

--
Cambie las opciones de su lista de seguimiento en:
{{fullurl:Special:Watchlist|edit=yes}}',

# Delete/protect/revert
'deletepage'                  => "Kay p'anqata qulluy",
'confirm'                     => 'Sinchichay',
'excontent'                   => "Samiqnin karqan kay hinam: '$1'",
'excontentauthor'             => "Samiqnin karqan kay hinam: '$1' (huklla ruraqnin: '$2')",
'exbeforeblank'               => "manaraq qullusqa kaptin, samiqnin kay hinam karqan: '$1'",
'exblank'                     => 'página estaba vacía',
'confirmdelete'               => 'Qullunata sinchichay',
'deletesub'                   => '(Qulluspa "$1")',
'historywarning'              => "Paqtataq: Kay qulluna p'anqaqa wiñay kawsasqayuqmi:",
'confirmdeletetext'           => "Qullunayachkanki willañiqintinmanta p'anqatam icha rikch'atam, wiñay kawsasqantapas.
Ama hina kaspa, sinchichallay munayniykita, qatiqninkunata riqsiyniykita, [[{{MediaWiki:Policy-url}}]] nisqakama rurayniykitapas.",
'actioncomplete'              => 'Rurasqañam',
'deletedtext'                 => '"$1" qullusqañam.
$2 nisqa p\'anqata qhaway ñaqha qullusqakunata rikunaykipaq.',
'deletedarticle'              => 'qullusqa "$1"',
'dellogpage'                  => 'Qullusqakuna',
'dellogpagetext'              => 'A continuación se muestra una lista de los borrados más recientes. Todos los tiempos se muestran en hora del servidor (UTC).',
'deletionlog'                 => 'qullusqakuna',
'reverted'                    => 'Ñawpaq hukchasqata kutichiy',
'deletecomment'               => 'Imarayku qullusqa',
'rollback'                    => 'Hukchasqakunata kutichiy',
'rollback_short'              => 'Kutichiy',
'rollbacklink'                => 'Kutichiy',
'rollbackfailed'              => 'Manam kutichiyta atinchu',
'cantrollback'                => 'No se pueden revertir las ediciones; el último colaborador es el único autor de este artículo.',
'alreadyrolled'               => 'No se puede revertir la última edición de [[$1]] por [[User:$2|$2]] ([[User talk:$2|discusión]]); alguien más ya ha editado o revertido esa página. La última edición fue hecha por [[User:$3|$3]] ([[User talk:$3|discusión]]).',
'editcomment'                 => 'El resumen de la edición es: "<i>$1</i>".', # only shown if there is an edit comment
'revertpage'                  => '[[Special:Contributions/$2|$2]] ([[User talk:$2|rimachina]]) sutiyuq ruraqpa hukchasqankunaqa kutichisqam [[User:$1|$1]]-pa ñawpaq hukchasqanman',
'rollback-success'            => "$1-pa hukchasqankunaqa kutichisqañam $2-pa ñawpaq llamk'apusqanta paqarichispa.",
'sessionfailure'              => 'Parece que hay un problema con tu sesión;
esta acción ha sido cancelada como medida de precaución contra secuestros de sesión.
Por favor, pulsa "Atrás", recarga la página de la que viniste e inténtalo de nuevo.',
'protectlogpage'              => "P'anqa amachasqakuna",
'protectlogtext'              => 'Abajo se presenta una lista de protección y desprotección de página. Véase [[Special:Protectedpages|Esta página está protegida]] para más información.',
'protectedarticle'            => 'amachan [[$1]]-ta',
'modifiedarticleprotection'   => 'Cambiado el nivel de protección de "[[$1]]"',
'unprotectedarticle'          => 'paskan amachasqa [[$1]]-ta',
'protectsub'                  => '(Protegiendo "$1")',
'confirmprotect'              => 'Confirmar protección',
'protectcomment'              => 'Imarayku amachasqa',
'protectexpiry'               => 'Amachaypa puchukaynin',
'protect_expiry_invalid'      => 'Tiempo de caducidad incorrecto.',
'protect_expiry_old'          => 'El tiempo de expiración está en el pasado.',
'unprotectsub'                => '(Amachasqa "$1"-ta paskaspa)',
'protect-unchain'             => 'Configurar permisos para traslados',
'protect-text'                => 'Puedes ver y modificar el nivel de protección de la página <strong>$1</strong>.',
'protect-locked-blocked'      => 'No puede cambiar los niveles de protección estando bloqueado. A continuación se muestran las opciones actuales de la página <strong>$1</strong>:',
'protect-locked-dblock'       => 'Los niveles de protección no se pueden cambiar debido a un bloqueo activo de la base de datos.
A continuación se muestran las opciones actuales de la página <strong>$1</strong>:',
'protect-locked-access'       => 'Su cuenta no tiene permiso para cambiar los niveles de protección de una página.
A continuación se muestran las opciones actuales de la página <strong>$1</strong>:',
'protect-cascadeon'           => "Kay p'anqaqa amachasqam kachkan, kay phaqchalla amachasqa {{PLURAL:$1|p'anqapi|p'anqakunapi}} ch'aqtasqa kaspanmi. Kay p'anqap amachay hanan kayninta hukchaytam atinki, chaywantaq manam p'anqap amachasqa kaynintachu hukchanki.",
'protect-default'             => '(por defecto)',
'protect-level-autoconfirmed' => "Sutinnaq ruraqkunata hark'ay",
'protect-level-sysop'         => 'Kamachiqkunallapaq',
'protect-summary-cascade'     => "''phaqcha'' nisqapi",
'protect-expiring'            => 'caduca el $1 (UTC)',
'protect-cascade'             => "Phaqchalla amachay - kay p'anqapi ch'aqtasqa p'anqakunatapaq amachay.",
'restriction-type'            => 'Saqillay:',
'restriction-level'           => 'Amachay hanan kay:',
'minimum-size'                => 'Kaymanta aswan hatun',
'maximum-size'                => 'Kaykama hatun',

# Restrictions (nouns)
'restriction-edit' => "Llamk'apunapaq",
'restriction-move' => 'Astanapaq',

# Restriction levels
'restriction-level-sysop'         => "hunt'a amachasqa",
'restriction-level-autoconfirmed' => 'kuskan amachasqa',
'restriction-level-all'           => 'ima hanan kayninpas',

# Undelete
'undelete'                 => "Qullusqa p'anqata paqarichiy",
'undeletepage'             => "Qullusqa p'anqakunata qhawaspa paqarichiy",
'viewdeletedpage'          => "Qullusqa p'anqakunata qhaway",
'undeletepagetext'         => "Kay p'anqakunaqa qullusqam, ichataq hallch'apiraqmi kachkan, chayrayku paqarichiytam atinki. Mit'a-mit'allaqa hallch'ata ch'usaqchankuchá.",
'undeleteextrahelp'        => "Tukuy llamk'apusqakunata paqarichinaykipaqqa, mana imatapas akllaspa '''Paqarichiy!''' ñit'iy. Huklla llamk'apusqakunata paqarichinaykipaqqa, munasqayki llamk'apusqakunata akllaspa '''Paqarichiy!''' ñit'iy. '''Mana imapas''' nisqapi ñit'iptiykiqa, tukuy akllasqaqa willana k'itichapas ch'usaqchasqam kanqa.",
'undeleterevisions'        => "$1 hallch'asqa {{PLURAL:$1|llamk'apusqa|llamk'apusqakuna}}",
'undeletehistory'          => "Qullusqaña p'anqata paqarichiptiykiqa, tukuy llamk'apusqakunam paqarinqa wiñay kawsaypi. Kaqlla sutiyuq musuq p'anqaña kachkaptinqa, paqarichisqa llamk'apusqakunaqa chay musuq p'anqap wiñay kawsaypim, ñawpaq kaq llamk'apusqakuna hinam paqarinqa, musuq p'anqapaq kachkaq llamk'apusqaqa kakunqataqmi.",
'undeleterevdel'           => "Qullusqaqa manam paqarichisqachu kanqa qhipaq llamk'apusqa rakilla qullusqa kaptinmanqa. Hinaptinqa, akllasqa nisqaykita qichuy icha lliwmanta aswan ñaqha qullusqa llamk'apusqakunata rikuchiy. Mana saqillasusqayki llamk'apusqakunaqa manam paqarichisqachu kanqa.",
'undeletehistorynoadmin'   => "Kay qillqaqa qullusqam. Kay qatiq pisichaypim rikunki imarayku qullusqa karqan, qhipaq llamk'apuqkunamanta willasqakunatapas. Tukuy qillqantintaqa kamachiqkunallam ñawiriyta atin.",
'undelete-revision'        => "$2-pi $1-pa llamk'apusqan, $3-pa qullusqan:",
'undeleterevision-missing' => "Llamk'apusqaqa manam allinchu icha chinkasqam. Mana allin t'inkichá, icha llamk'apusqaqa hallch'amanta qullusqachá icha musuqmanta paqarichisqachá.",
'undelete-nodiff'          => "Manam tarinichu ñawpaq llamk'apusqata.",
'undeletebtn'              => 'Paqarichiy!',
'undeletereset'            => 'Mana imapas',
'undeletecomment'          => 'Imarayku paqarichisqa:',
'undeletedarticle'         => 'qullurqasqa "$1" paqarisqa',
'undeletedrevisions'       => "{{PLURAL:$1|Huk paqarichisqa llamk'apusqa|$1 paqarichisqa llamk'apusqakuna}}",
'undeletedrevisions-files' => "{{PLURAL:$1|1 llamk'apusqaqa|$1 llamk'apusqakunaqa}} {{PLURAL:$2|1 willañiqipas|$2 willañiqikunapas}} paqarichisqam",
'undeletedfiles'           => '{{PLURAL:$1|1 willañiqiqa|$1 willañiqikunaqa}} paqarichisqam',
'cannotundelete'           => 'Manam atinichu qullusqata paqarichiyta; huk runachá ñawpaqtaña qullusqata paqarichirqan.',
'undeletedpage'            => "<big>'''$1 nisqaqa paqarichisqañam'''</big>

[[Special:Log/delete|Qulluy hallch'api]] qhaway ñaqha qullusqakunata paqarichisqakunatapas rikunaykipaq.",
'undelete-header'          => "[[Special:Log/delete|Qulluy hallch'apiqa]] qullusqa p'anqakunap sutinkunatam rikunki.",
'undelete-search-box'      => "Qullusqa p'anqakunata maskay",
'undelete-search-prefix'   => "Rikuchiy kaywan qallariq p'anqakunata:",
'undelete-search-submit'   => 'Maskay',
'undelete-no-results'      => 'No se encontraron páginas borradas para ese criterio de búsqueda.',
'undelete-error-short'     => 'Error restaurando archivo: $1',

# Namespace form on various pages
'namespace'      => "Suti k'iti:",
'invert'         => "Pallasqantinta t'ikrachiy",
'blanknamespace' => '(Uma)',

# Contributions
'contributions' => "Ruraqpa llamk'apusqankuna",
'mycontris'     => "Llamk'apusqaykuna",
'contribsub2'   => '$1 ($2)',
'nocontribs'    => 'Manam kay hina hukchasqakuna kanchu.',
'ucnote'        => 'A continuación se muestran los últimos <b>$1</b> cambios de este usuario en los últimos <b>$2</b> días.',
'uclinks'       => "Qhipaq $1 hukchasqata qhaway; qhipaq $2 p'unchawta qhaway.",
'uctop'         => ' (qhipaq hukchasqa)',
'month'         => 'Kay killamanta (ñawpaqmantapas):',
'year'          => 'Kay watamanta (ñawpaqmantapas):',

'sp-contributions-newest'      => 'Qhipaqkuna',
'sp-contributions-oldest'      => 'Ñawpaqkuna',
'sp-contributions-newer'       => '← $1 qatiqninkuna',
'sp-contributions-older'       => '$1 ñawpaqninkuna →',
'sp-contributions-newbies'     => "Musuq ruraqkunallap llamk'apusqankunata rikuchiy",
'sp-contributions-newbies-sub' => 'Musuqkunapaq',
'sp-contributions-blocklog'    => "Hark'ay hallch'asqakuna",
'sp-contributions-search'      => "Llamk'apusqakunata maskay",
'sp-contributions-username'    => 'IP huchha icha ruraqpa sutin:',
'sp-contributions-submit'      => 'Maskay',

'sp-newimages-showfrom' => 'Musuq rikchakunata rikuchiy, $1-wan qallarispa',

# What links here
'whatlinkshere'       => "Kayman t'inkimuq",
'whatlinkshere-title' => "$1 sutiyuq p'anqaman t'inkimuqkuna",
'linklistsub'         => "(T'inkikuna)",
'linkshere'           => "'''[[:$1]]''' sutiyuq p'anqamanqa kay qatiq p'anqakunam t'inkimun:",
'nolinkshere'         => "Manam kachkanchu '''[[:$1]]'''-man t'inkiq p'anqa.",
'nolinkshere-ns'      => "Manam kachkanchu '''[[:$1]]'''-man t'inkiq p'anqa pallasqay suti k'itipi.",
'isredirect'          => "pusapusqa p'anqa",
'istemplate'          => "ch'aqtasqa",
'whatlinkshere-prev'  => '{{PLURAL:$1|ñawpaq|$1 ñawpaq}}',
'whatlinkshere-next'  => '{{PLURAL:$1|qatiq|$1 qatiq}}',
'whatlinkshere-links' => "← t'inkikuna",

# Block/unblock
'blockip'                     => "Ruraqta hark'ay",
'blockiptext'                 => "Kay qatiq hunt'ana p'anqata llamk'achiy huk IP-niyuqmanta icha ruraqpa sutinmanta llamk'apuyta hark'anapaq.
Chayqa [[Wikipidiya:Wandalismu|wandalismu]]ta hark'anapaq chaylla, [[Wikipidiya:Kawpay|{{SITENAME}}p kawpaykunallakamam]].
Hark'asqaykip hamuntapas sut'ichay (ahinataq, sapaq p'anqapi wandaluchaspa hukchasqakunamanta willaspa).",
'ipaddress'                   => 'IP huchha',
'ipadressorusername'          => 'IP huchha icha ruraqpa sutin',
'ipbexpiry'                   => "Hark'ay kaykama:",
'ipbreason'                   => 'Hamu',
'ipbreasonotherlist'          => 'Huk hamu',
'ipbreason-dropdown'          => '
*Motivos comunes de bloqueo
** Añadir información falsa
** Eliminar contenido de las páginas
** Publicitar enlaces a otras páginas web
** Añadir basura a las páginas
** Comportamiento intimidatorio/acoso sexual
** Abusar de múltiples cuentas
** Nombre de usuario inaceptable',
'ipbanononly'                 => 'Bloquear usuarios anónimos solamente',
'ipbcreateaccount'            => 'Prevenir creación de cuenta de usuario.',
'ipbemailban'                 => 'Prevenir que los usuarios envien correo electrónico',
'ipbenableautoblock'          => 'Bloquear automáticamente la dirección IP usada por este usuario, y cualquier IP posterior desde la cual intente editar',
'ipbsubmit'                   => "Kay tiyayta hark'ay",
'ipbother'                    => 'Especificar caducidad',
'ipboptions'                  => "2 ura:2 hours,1 p'unchaw:1 day,3 p'unchaw:3 days,1 simana:1 week,2 simana:2 weeks,1 killa:1 month,3 killa:3 months,6 killa:6 months,1 wata:1 year,Wiña-wiñaypaq:infinite",
'ipbotheroption'              => 'huk',
'ipbotherreason'              => 'Huk imarayku:',
'ipbhidename'                 => "Ruraqta/IP-ta pakay hark'ay hallch'asqapi, kachkaq hark'asqakunapi ruraqkunapipas",
'badipaddress'                => 'La dirección IP no tiene el formato correcto.',
'blockipsuccesssub'           => "IP-niyuq ruraqqa hark'asqañam",
'blockipsuccesstext'          => 'IP "$1"-niyuqqa hark\'asqañam. <br />[[Special:Ipblocklist|Hark\'asqa IP-niyuqkuna]]ta qhaway hark\'akunata hukchanaykipaq.',
'ipb-edit-dropdown'           => "Hark'aypa hamunta llamk'apuy",
'ipb-unblock-addr'            => "Hark'asqa $1-ta qispichiy",
'ipb-unblock'                 => "Hark'asqa ruraqta icha IP-niyuqta qispichiy",
'ipb-blocklist-addr'          => "Kachkaq hark'asqakunata qhaway $1-paq",
'ipb-blocklist'               => "Kachkaq hark'asqakunata qhaway",
'unblockip'                   => "Hark'asqa IP-niyuq ruraqta qispichiy",
'unblockiptext'               => 'Use el formulario a continuación para devolver los permisos de escritura a una dirección IP que ha sido bloqueada.',
'ipusubmit'                   => "Kay hark'asqa tiyayta qispichiy",
'unblocked'                   => "Hark'asqa [[User:$1|$1]] qispisqañam",
'unblocked-id'                => "Hark'asqa $1-qa qispisqañam",
'ipblocklist'                 => "Hark'asqa ruraqkuna IP tiyaykunapas",
'ipblocklist-legend'          => "Hark'asqa ruraqta tariy",
'ipblocklist-username'        => 'Ruraqpa sutin icha IP huchha:',
'ipblocklist-submit'          => 'Maskay',
'blocklistline'               => "$1, $2 hark'an $3 ($4)-tam",
'infiniteblock'               => 'infinito',
'expiringblock'               => 'expira $1',
'anononlyblock'               => 'sutinnaqlla.',
'noautoblockblock'            => "Kikinmanta hark'ayqa ama kachunchu",
'createaccountblock'          => "Rakiqunata kichariyqa hark'asqam.",
'emailblock'                  => "hark'asqa e-chaski",
'ipblocklist-empty'           => "Mana pipas hark'asqachu kachkan.",
'ipblocklist-no-results'      => "Kay ruraqqa/IP-niyuqqa manam hark'asqachu kachkan.",
'blocklink'                   => "hark'ay",
'unblocklink'                 => "qispichiy hark'asqa",
'contribslink'                => "llamk'apusqakuna",
'autoblocker'                 => 'Has sido bloqueado automáticamente porque tu dirección IP ha sido usada recientemente por "[[User:$1|$1]]". La razón esgrimida para bloquear a "[[User:$1|$1]]" fue "$2".',
'blocklogpage'                => "Ruraq hark'asqakuna",
'blocklogentry'               => "hark'an [[$1]]-ta kay pachakama: $2 $3",
'blocklogtext'                => 'Esto es un registro de bloqueos y desbloqueos de usuarios. Las direcciones bloqueadas automáticamente no aparecen aquí. Consulte la [[Special:Ipblocklist|lista de direcciones IP bloqueadas]] para ver la lista de prohibiciones y bloqueos actualmente vigente.',
'unblocklogentry'             => 'paskan "$1"-ta hark\'asqa kaymanta',
'block-log-flags-anononly'    => 'sólo anónimos',
'block-log-flags-nocreate'    => 'desactivada la creación de cuentas',
'block-log-flags-noautoblock' => 'bloqueo automático desactivado',
'block-log-flags-noemail'     => 'correo electrónico deshabilitado',
'range_block_disabled'        => 'La facultad de administrador de crear bloqueos por rangos está deshabilitada.',
'ipb_expiry_invalid'          => 'El tiempo de caducidad no es válido.',
'ipb_already_blocked'         => '"$1" sutiyuqqa hark\'asqañam kachkan.',
'ipb_cant_unblock'            => "'''Error''': Número ID $1 de bloqueo no encontrado. Pudo haber sido desbloqueado ya.",
'ip_range_invalid'            => "IP huchha k'itiqa manam chanichkanchu.",
'proxyblocker'                => 'Bloqueador de proxies',
'proxyblockreason'            => 'Su dirección IP ha sido bloqueada porque es un proxy abierto. Por favor, contacte con su proveedor de servicios de Internet o con su servicio de asistencia técnica e infórmeles de este grave problema de seguridad.',
'proxyblocksuccess'           => 'Rurasqañam.',
'sorbsreason'                 => 'Su dirección IP está listada como proxy abierto en DNSBL.',
'sorbs_create_account_reason' => 'Su dirección IP está listada como proxy abierto en DNSBL. No puede crear una cuenta',

# Developer tools
'lockdb'              => "Willañiqintinta hark'ay",
'unlockdb'            => 'Desbloquear la base de datos',
'lockdbtext'          => 'El bloqueo de la base de datos impedirá a todos los usuarios editar páginas, cambiar sus preferencias, modificar sus listas de seguimiento y cualquier otra función que requiera realizar cambios en la base de datos. Por favor, confirme que ésto es precisamente lo que quiere hacer y que desbloqueará la base de datos tan pronto haya finalizado las operaciones de mantenimiento.',
'unlockdbtext'        => 'El desbloqueo de la base de datos permitirá a todos los usuarios editar páginas, cambiar sus preferencias, modificar sus listas de seguimiento y cualesquiera otras funciones que impliquen modificar la base de datos. Por favor, confirme que esto es precisamente lo que quiere hacer.',
'lockconfirm'         => 'Sí, realmente quiero bloquear la base de datos.',
'unlockconfirm'       => 'Sí, realmente quiero desbloquear la base de datos.',
'lockbtn'             => "Willañiqintinta hark'ay",
'unlockbtn'           => 'Desbloquear la base de datos',
'locknoconfirm'       => 'No ha confirmado lo que desea hacer.',
'lockdbsuccesssub'    => "Willañiqintinqa hark'asqañam",
'unlockdbsuccesssub'  => 'El desbloqueo se ha realizado con éxito',
'lockdbsuccesstext'   => 'La base de datos de {{SITENAME}} ha sido bloqueada.
<br />Recuerde retirar el bloqueo después de completar las tareas de mantenimiento.',
'unlockdbsuccesstext' => 'La base de datos de {{SITENAME}} ha sido desbloqueada.',
'lockfilenotwritable' => 'El archivo-cerrojo de la base de datos no tiene permiso de escritura. Para bloquear o desbloquear la base de datos, este archivo tiene que ser escribible por el sesrvidor web.',
'databasenotlocked'   => 'La base de datos no está bloqueada.',

# Move page
'movepage'                => "P'anqata astay",
'movepagetext'            => "Kay hunt'ana p'anqawanqa huk p'anqam tukuy wiñay kawsasqanpas astasqa kanqa. Mawk'a sutinqa musuq sutiman pusapuq p'anqam tukunqa. Mawk'a sutiman t'inkimuq p'anqakunaqa manam hukyanqachu. Paqtataq iskaylla pusapuna p'anqakunata allinchallay. Ama panta t'inkimuqkunata saqiychu.


Nisqayki musuq sutiyuq wiñay kawsasqayuq p'anqaña kachkaptinqa, kay p'anqa '''manam''' astasqa kanqachu.

Huklla kuti astasqa p'anqataqa mawk'a sutinman astayta atinkim, manataq huk mawk'a kachkaqña p'anqamanchu.

<b>PAQTATAQ!</b>
Kay astayqa ancha riqsisqa p'anqata hatun mana suyapusqa hukchaymi kayta atinman;
ama hina kaspa, yuyarillay imachus kay astanaykita saqispa tukunata atinman.",
'movepagetalktext'        => "P'anqaman kapuq rimachina p'anqaqa - kachkaspaqa - kikinmanta astasqam kanqa. '''Manallam astasqachu kanqa,'''
*p'anqa huk suti huñumanta huk suti huñuman astasqa kachkaptinqa;
*huk wiñay kawsasqayuq musuq sutiyuq rimachina p'anqa kachkaptinqa;
*\"Rimachinapas, atikuq hinaptin\" nisqa pallanaman ama niptiykiqa.

Hinaptinqa, kay rimachina p'anqap samiqninta makiykiwan astanaykim atinqa.",
'movearticle'             => "P'anqata astay",
'movenologin'             => "Manam qallarisqachu llamk'apuy tiyayniyki",
'movenologintext'         => "P'anqata astanaykipaqqa hallch'asqa ruraqmi kanayki [[Special:Userlogin|llamk'apuy tiyay qallarinaykipas]] atin.",
'movenotallowed'          => "Kay wikipi p'anqata astayniykiqa manam saqillasqachu.",
'newtitle'                => 'Kay musuq sutiman',
'move-watch'              => "Kay p'anqata watiqay",
'movepagebtn'             => "P'anqata astay",
'pagemovedsub'            => 'Renombrado realizado con éxito',
'movepage-moved'          => "<big>'''«$1» kaymanñam astasqa: «$2»'''</big>", # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => 'Ya existe una página con ese nombre o el nombre que ha elegido no es válido. Por favor, elija otro nombre.',
'talkexists'              => 'La página fue renombrada con éxito, pero la discusión no se pudo mover porque ya existe una en el título nuevo. Por favor incorpore su contenido manualmente.',
'movedto'                 => 'kayman astasqa:',
'movetalk'                => 'Rimachinapas, atikuq hinaptin.',
'talkpagemoved'           => "Rimachina p'anqapas astasqam.",
'talkpagenotmoved'        => "Rimachina p'anqaqa <strong>manam</strong> astasqachu.",
'1movedto2'               => '«[[$1]]» «[[$2]]»-man astasqa',
'1movedto2_redir'         => '[[$1]] [[$2]]-man astasqa pusana qillqata huknachaspa',
'movelogpage'             => "Astay hallch'asqa",
'movelogpagetext'         => "Kay qatiqpiqa astasqa p'anqakunam.",
'movereason'              => 'Imarayku astasqa',
'revertmove'              => 'kutichiy',
'delete_and_move'         => 'Qulluspa astay',
'delete_and_move_text'    => '==Qullunam atin==

Tukuna p\'anqaqa ("[[$1]]") kachkañam. Astanapaq qulluyta munankichu?',
'delete_and_move_confirm' => "Arí, kay p'anqata qulluy",
'delete_and_move_reason'  => 'Astanapaq qullusqa',
'selfmove'                => 'Los títulos de origen y destino son los mismos. No se puede trasladar un página sobre sí misma.',
'immobile_namespace'      => 'El título de destino es de un tipo especial. No se pueden trasladar páginas a ese espacio de nombres.',

# Export
'export'            => "P'anqakunata hawaman quy",
'exporttext'        => "Huk sapaq p'anqap icha aswan p'anqakunap qillqanta wiñay kawsaynintapas hawaman quyta atinki XML qillqaman. Chaytaqa huk MediaWikita llak'achiq wikiman hawamanta chaskiyta atinku [[Special:Import|hawamanta chaskiy p'anqa]] nisqawan.

P'anqakunata hawaman qunaykipaqqa, sutinkunata kay qatiqppi qillqana k'itichaman qillqay, sapa siq'ipi huk suti, akllaspa kunan p'anqata wiñay kawsaynintapas munankichu ichataq kunan p'anqatallachu qhipaq hukchasqallamanta willayllawan.

Qhipaqta munaspaykiqa, t'inkitapas llamk'achiyta atinki, ahinataq [[{{ns:Special}}:Export/{{MediaWiki:Mainpage}}]], \"[[{{MediaWiki:Mainpage}}]]\" p'anqapaq.",
'exportcuronly'     => "Kunan llamk'apusqatam ch'aqtay, manataqmi wiñay kawsaynintinchu.",
'exportnohistory'   => "----
'''Musyay:''' Wiñay kawsaynintinta kay hunt'ana p'anqawan hawan quymanqa ama nisqam, sirwiq mana atiptinmi.",
'export-submit'     => 'Hawaman quy',
'export-addcattext' => "P'anqakunata yapay kay katiguriyamanta:",
'export-addcat'     => 'Yapay',
'export-download'   => 'Willañiqi hina waqaychay niy',

# Namespace 8 related
'allmessages'               => 'MediaWiki-p tukuy willayninkuna',
'allmessagesname'           => 'Suti',
'allmessagesdefault'        => 'Ñawpaq qillqa',
'allmessagescurrent'        => 'Kunan kachkaq qillqa',
'allmessagestext'           => "Kayqa MediaWiki suti k'itipi tukuy llamk'achinalla willaykunam:",
'allmessagesnotsupportedDB' => "'''{{ns:special}}:AllMessages''' manam llamk'achinallachu, '''wgUseDatabaseMessages''' nisqaman ama nisqa kaptinmi.",
'allmessagesfilter'         => "Willaypa sutinkama ch'illchiy:",
'allmessagesmodified'       => 'Hukchasqallata rikuchiy',

# Thumbnails
'thumbnail-more'           => 'Hatunchay',
'missingimage'             => "<b>Manam rikch'a kachkanchu</b><br /><i>$1</i>",
'filemissing'              => 'Falta archivo',
'thumbnail_error'          => "Manam atinichu rikch'achata kamayta: $1",
'djvu_page_error'          => 'Página DjVu fuera de rango',
'djvu_no_xml'              => 'Imposible obtener XML para el archivo DjVu',
'thumbnail_invalid_params' => 'Parámetros del thumbnail no válidos',
'thumbnail_dest_directory' => 'Incapaz de crear el directorio de destino',

# Special:Import
'import'                     => "P'anqakunata hawamanta chaskiy",
'importinterwiki'            => "Huk wikimanta p'anqakunata chaskiy",
'import-interwiki-text'      => "Huk wikita p'anqap sutintapas akllay hawamanta chaskinapaq.
Llamk'apusqap pachankunaqa ruraqpa sutinkunapas kakuspa hallch'asqam kanqa.
Tukuy hawa wikimanta chaskisqakunaqa [[Special:Log/import|hawamanta chaskiy hallch'api]] hallch'asqam kanqa.",
'import-interwiki-history'   => "Kay p'anqapaq tukuy wiñay kawsaynintinta iskaychay",
'import-interwiki-submit'    => 'Hawamanta chaskiy',
'import-interwiki-namespace' => "P'anqakunata kay suti k'itiman churay:",
'importtext'                 => "Ama hina kaspa, willañiqita qallariy wikimanta Special:Export nisqa llamk'anawan hawaman quy antañiqiqniykipi waqaychaspa, chaymantataq kaypi churkuy.",
'importstart'                => "P'anqakunatam hawamanta chaskichkani...",
'import-revision-count'      => "$1 {{PLURAL:$1|llamk'apusqa|llamk'apusqakuna}}",
'importnopages'              => "Manam kanchu hawamanta chaskina p'anqakuna.",
'importfailed'               => 'Manam atinichu hawamanta chaskiy: $1',
'importunknownsource'        => 'Hawamanta chaskina pukyu layaqa manam riqsisqachu',
'importcantopen'             => 'Manam atinichu kay willañiqita hawamanta chaskiyta',
'importbadinterwiki'         => "Interwiki t'inkiqa manam allinchu",
'importnotext'               => "Ch'usaqmi",
'importsuccess'              => 'Aypalla hawamanta chaskisqañam!',
'importhistoryconflict'      => "Wiñay kawsaypiqa hayunakuqmi llamk'apusqakuna (ñawpaqtaña hawamanta chaskisqachá karqan)",
'importnosources'            => 'Manam qusqachu hawamanta chaskina pukyukuna, wiñay kawsayta chiqalla churkuymantaq ama nisqam.',
'importnofile'               => 'Manam ima chaskina willañiqi churkusqachu.',
'importuploaderror'          => 'Manam atinichu hawamanta chaskina willañiqita churkuyta. Nisyu hatunchá kachkan, saqillasqa chhikan kaymanta aswan.',

# Import log
'importlogpage'                    => "Hawamanta chaskiy hallch'a",
'importlogpagetext'                => "Huk wikikunamanta wiñay kawsayniyuq p'anqakunata kamachina chaskiykuna.",
'import-logentry-upload'           => 'hawamanta chaskisqa [[$1]] willañiqita churkuspa',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|hukchasqa|hukchasqakuna}}',
'import-logentry-interwiki'        => 'huk wikimanta chaskisqa $1',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|hukchasqa|hukchasqakuna}} $2-manta',

# Tooltip help for the actions
'tooltip-pt-userpage'             => "Ñuqap ruraqpa p'anqay",
'tooltip-pt-anonuserpage'         => 'La página de usuario de la IP desde la que edita',
'tooltip-pt-mytalk'               => "Rimanakuy p'anqay",
'tooltip-pt-anontalk'             => 'Discusión sobre ediciones hechas desde esta dirección IP',
'tooltip-pt-preferences'          => 'Allinkachinaykuna',
'tooltip-pt-watchlist'            => "Ruraqpa hukchasqakunakama watiqasqan p'anqakuna",
'tooltip-pt-mycontris'            => "Llamk'apusqaykuna",
'tooltip-pt-login'                => 'Kallpachaykiku yaykunaykiqa allinmi nispa, mana manu kanayki kaptinpas',
'tooltip-pt-anonlogin'            => 'Le animamos a registrarse, aunque no es obligatorio',
'tooltip-pt-logout'               => "Llamk'apuy tiyaymanta lluqsiy",
'tooltip-ca-talk'                 => "Qillqasqap samiqninmanta rimanakuna p'anqa",
'tooltip-ca-edit'                 => "Kay p'anqata llamk'apuytam atinki. Ama hina kaspa, manaraq waqaychaspa ñawpaqta qhawarillay.",
'tooltip-ca-addsection'           => 'Kay rimanakuyman willayniykita yapay.',
'tooltip-ca-viewsource'           => "Kay p'anqaqa amachasqam. Qallariy qillqataqa qhawallaytam atinki, mana hukchaspa.",
'tooltip-ca-history'              => 'Versiones anteriores de esta página y sus autores',
'tooltip-ca-protect'              => "Kay p'anqata amachay",
'tooltip-ca-delete'               => "Kay p'anqata qulluy",
'tooltip-ca-undelete'             => 'Restaurar las ediciones hechas a esta página antes de que fuese borrada',
'tooltip-ca-move'                 => "Kay p'anqata astay musuq sutinta quspa",
'tooltip-ca-watch'                => "Kay p'anqata watiqay",
'tooltip-ca-unwatch'              => "Amaña watiqaychu kay p'anqata",
'tooltip-search'                  => 'Kay wikipi maskay',
'tooltip-p-logo'                  => 'Portada',
'tooltip-n-mainpage'              => "Qhapaq p'anqata watukuy",
'tooltip-n-portal'                => 'Ruraykamaymanta, imatachus rurayta atinki, maypichus imatapas tariy',
'tooltip-n-currentevents'         => 'Kunan pacha tukusqakunamanta ukhunpuray willaykuna',
'tooltip-n-recentchanges'         => 'Kay wikipi ñaqha hukchasqakuna',
'tooltip-n-randompage'            => "Mayninpi kaq p'anqaman riy",
'tooltip-n-help'                  => 'Yachaqanapaq tiyana',
'tooltip-n-sitesupport'           => 'Yanapawayku',
'tooltip-t-whatlinkshere'         => "Kay p'anqaman tukuy t'inkimuqkuna",
'tooltip-t-recentchangeslinked'   => "Kay p'anqaman t'inkimuqkunapi ñaqha hukchasqakuna",
'tooltip-feed-rss'                => 'Sindicación RSS de esta página',
'tooltip-feed-atom'               => 'Sindicación Atom de esta página',
'tooltip-t-contributions'         => "Kay ruraqpa llamk'apusqankunata qhaway",
'tooltip-t-emailuser'             => 'Kay ruraqman chaskita kachay',
'tooltip-t-upload'                => "Rikch'akunata, multimidyata churkuy",
'tooltip-t-specialpages'          => "Tukuy sapaq p'anqakuna",
'tooltip-t-print'                 => "Kay p'anqata ch'ipachinapaq",
'tooltip-t-permalink'             => "ch'ipachinapaq p'anqaman kakuq t'inki",
'tooltip-ca-nstab-main'           => 'Qillqata qhaway',
'tooltip-ca-nstab-user'           => "Ruraqpa p'anqanta qhaway",
'tooltip-ca-nstab-media'          => "Multimidyamanta p'anqata qhaway",
'tooltip-ca-nstab-special'        => "Kayqa sapaq p'anqam, manam hukchanallachu",
'tooltip-ca-nstab-project'        => "Ruraykamay p'anqata qhaway",
'tooltip-ca-nstab-image'          => "Rikch'amanta p'anqata qhaway",
'tooltip-ca-nstab-mediawiki'      => 'Llikap willayninta qhaway',
'tooltip-ca-nstab-template'       => 'Plantillata qhaway',
'tooltip-ca-nstab-help'           => "Yanapana p'anqata qhaway",
'tooltip-ca-nstab-category'       => "Katiguriyamanta p'anqata qhaway",
'tooltip-minoredit'               => 'Kayta aslla hukchay nispa sananchay',
'tooltip-save'                    => 'Hukchasqakunata waqaychay',
'tooltip-preview'                 => 'Ñawpaqtaqa hukchasqaykikunata qhawarillay, manaraq waqaychaspa!',
'tooltip-diff'                    => 'Qillqapi hukchasqaykikunata rikuchiy.',
'tooltip-compareselectedversions' => "P'anqap iskay pallasqa hukchasqanpura hukchasqa kayta qhaway.",
'tooltip-watch'                   => "Kay p'anqata watiqay",
'tooltip-recreate'                => 'Recupera una página que ha sido borrada',

# Stylesheets
'common.css'   => '/* Los estilos CSS definidos aquí aplicarán a todas las pieles (skins) */',
'monobook.css' => '/* Kayman churasqa CSS nisqaqa Monobook qaratam hukchanqa tukuy internet tiyanapaq */',

# Scripts
'common.js' => '/* Cualquier código JavaScript escrito aquí se cargará para todos los usuarios en cada carga de página. */',

# Metadata
'nodublincore'      => 'Metadatos Dublin Core RDF deshabilitados en este servidor.',
'nocreativecommons' => 'Metadatos Creative Commons RDF deshabilitados en este servidor.',
'notacceptable'     => 'El servidor wiki no puede proveer los datos en un formato que su cliente (navegador) pueda entender.',

# Attribution
'anonymous'        => 'Wikipidiyap sutinnaq ruraqninkuna',
'siteuser'         => '{{SITENAME}}-pa $1 sutiyuq ruraqnin',
'lastmodifiedatby' => "Kay p'anqaqa $2, $1 qhipaq kutitam $3-pa hukchasqan karqan.", # $1 date, $2 time, $3 user
'and'              => '-wan',
'othercontribs'    => 'Basado en el trabajo de $1.',
'others'           => 'hukkuna',
'siteusers'        => '{{SITENAME}}-pa $1 sutiyuq ruraqnin(kuna)',
'creditspage'      => 'Créditos de la página',
'nocredits'        => 'Hay información de créditos para esta página.',

# Spam protection
'spamprotectiontitle'    => "Spam nisqamanta amachanapaq ch'illchina",
'spamprotectiontext'     => 'La página que intentas guardar ha sido bloqueada por el filtro de spam. Esto se debe probablemente a alguno de los un enlaces externos incluidos en ella.',
'spamprotectionmatch'    => "El siguiente texto es el que activó nuestro filtro ''anti-spam'' (contra la publicidad no solicitada): $1",
'subcategorycount'       => 'Hay {{PLURAL:$1|una subcategoría|$1 subcategorías}} en esta categoría.',
'categoryarticlecount'   => "Kay katiguriyapiqa {{PLURAL:$1|huk p'anqam|$1 p'anqakunam}} kachkan.",
'category-media-count'   => 'Kay katiguriyapiqa {{PLURAL:$1|huk willañiqim|$1 willañiqikunam}} kachkan.',
'listingcontinuesabbrev' => 'qatiy',
'spambot_username'       => 'MediaWiki-ta spam nisqamanta pichay',
'spam_reverting'         => 'Revirtiendo a la última versión que no contenga enlaces a $1',
'spam_blanking'          => 'Todas las revisiones contienen enlaces a $1, blanqueando',

# Info page
'infosubtitle'   => 'Información de la página',
'numedits'       => 'Número de ediciones (artículo): $1',
'numtalkedits'   => 'Número de ediciones (página de discusión): $1',
'numwatchers'    => 'Número de usuarios vigilándola: $1',
'numauthors'     => 'Número de autores distintos (artículo): $1',
'numtalkauthors' => 'Número de autores distintos (página de discusión): $1',

# Math options
'mw_math_png'    => "Hayk'appas PNG-ta ruray",
'mw_math_simple' => 'Ancha sikllalla kaptinqa HTML, mana hinaptinqa PNG',
'mw_math_html'   => 'Paqtanayaptinqa HTML, mana hinaptinqa PNG',
'mw_math_source' => "TeX hinatam saqiy (qillqa wamp'unapaq)",
'mw_math_modern' => 'Recomendado para navegadores modernos',
'mw_math_mathml' => 'MathML',

# Patrolling
'markaspatrolleddiff'                 => 'Marcar como revisado',
'markaspatrolledtext'                 => 'Marcar este artículo como revisado',
'markedaspatrolled'                   => 'Marcado como revisado',
'markedaspatrolledtext'               => 'La versión seleccionada ha sido marcada como revisada.',
'rcpatroldisabled'                    => 'Revisión de los Cambios Recientes deshabilitada',
'rcpatroldisabledtext'                => 'La capacidad de revisar los Cambios Recientes está deshabilitada en este momento.',
'markedaspatrollederror'              => 'No se puede marcar como patrullada',
'markedaspatrollederrortext'          => 'Debes especificar una revisión para marcarla como patrullada.',
'markedaspatrollederror-noautopatrol' => 'No tienes permisos para marcar tus propios cambios como revisados.',

# Patrol log
'patrol-log-page' => 'Registro de revisiones',
'patrol-log-line' => 'revisado $1 de $2 $3',
'patrol-log-auto' => '(automático)',

# Image deletion
'deletedrevision' => 'Borrada revisión antigua $1',

# Browsing diffs
'previousdiff' => '← ñawpaq hukchasqa kaykuna',
'nextdiff'     => 'Qatiq hukchasqa kaykunaman riy →',

# Media information
'mediawarning'         => "'''Atención''': Este fichero puede contener código malicioso, ejecutarlo podría comprometer la seguridad de tu equipo.<hr />",
'imagemaxsize'         => 'Limitar imágenes en las páginas de descripción a:',
'thumbsize'            => 'Tamaño de las vistas en miniatura:',
'file-info'            => '(willañiqip chhikan kaynin: $1; laya MIME: $2)',
'file-info-size'       => '($1 × $2 iñu; willañiqip chhikan kaynin: $3; laya MIME: $4)',
'file-nohires'         => '<small>Manam kanchu aswan huyakuyuq rikcha.</small>',
'svg-long-desc'        => '(SVG willañiqi, rimasqakama $1 × $2 iñuyuq, willañiqip chhikan kaynin: $3)',
'show-big-image'       => 'Qallariy huyaku',
'show-big-image-thumb' => '<small>Kay ñawpaq qhawariypa chhikan kaynin: $1 × $2 iñu</small>',

# Special:Newimages
'newimages'    => 'Musuq rikchakunap suyu-suyun',
'showhidebots' => '($1 rurana antacha)',
'noimages'     => 'Manam ima rikunallapas kanchu.',

# Bad image list
'bad_image_list' => "Chantaqa kay hinam:

Sutisuyu imakunallam (* sananchawan qallariq siq'ikunapi) hamut'arisqa. Siq'ipi ñawpaq ñiqin t'inkipqa mana allin rikchaman t'inkimunanmi.
Kikin siq'ipi ima qatiq t'inkillapas sapaqllatam hamut'arisqa, ahinataq siq'ipi rikchayuq p'anqakunam.",

# Metadata
'metadata'          => 'Metadata',
'metadata-help'     => "Kay willañiqipiqa aswan sapaqlla willaymi, iliktruniku rikch'ahap'inawanchá, iskanirwanchá icha rikch'arurana wakichiwanchá yapasqa. Willañiqi chaymantapacha hukchasqa kaptinqa, huk sapaq samiqninkuna chinkasqachá.",
'metadata-expand'   => 'Aswan sapaqlla willakunata rikuchiy',
'metadata-collapse' => 'Sapaqlla willakunata pakay',
'metadata-fields'   => "Kay willaypi rikuchisqa EXIF metadatapaq k'itichakunaqa rikcha p'anqapim ch'aqtasqa kanqa, metadata tawla mana rikch'akuptinpas. Huk k'itikunaqa kikinmantam pakasqa kanqa. 
* Ruraq
* Kayma
* Paqarisqap p'unchawnin pachanpas
* Kachasqap pachan
* f huchha
* Nina chawpip karu kaynin",

# EXIF tags
'exif-imagewidth'                  => 'Suni kay',
'exif-imagelength'                 => 'Hanaq kay',
'exif-bitspersample'               => 'Bits por componente',
'exif-compression'                 => 'Esquema de compresión',
'exif-photometricinterpretation'   => "Iñu ch'antay",
'exif-orientation'                 => 'Puririchiy',
'exif-samplesperpixel'             => 'Ñawpariq rakinkunap yupaynin',
'exif-planarconfiguration'         => "Willa mast'ariy",
'exif-ycbcrsubsampling'            => 'Razón de submuestreo de Y a C',
'exif-ycbcrpositioning'            => 'Posicionamientos Y y C',
'exif-xresolution'                 => 'Resolución horizontal',
'exif-yresolution'                 => 'Resolución vertical',
'exif-resolutionunit'              => 'Unidad de resolución X e Y',
'exif-stripoffsets'                => 'Localización de datos de imagen',
'exif-rowsperstrip'                => 'Número de filas por banda',
'exif-stripbytecounts'             => 'Bytes por banda comprimida',
'exif-jpeginterchangeformat'       => 'Desplazamiento al JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Bytes de datos JPEG',
'exif-transferfunction'            => 'Función de transferencia',
'exif-whitepoint'                  => 'Cromacidad de punto blanco',
'exif-primarychromaticities'       => 'Cromacidades primarias',
'exif-ycbcrcoefficients'           => 'Coeficientes de la matriz de transformación de espacio de color',
'exif-referenceblackwhite'         => 'Pareja de valores blanco y negro de referencia',
'exif-datetime'                    => 'Fecha y hora de modificación del archivo',
'exif-imagedescription'            => "Rikch'ap sut'ichaynin",
'exif-make'                        => 'Fabricante de la cámara',
'exif-model'                       => 'Modelo de cámara',
'exif-software'                    => 'Software usado',
'exif-artist'                      => 'Autor',
'exif-copyright'                   => 'Titular de los derechos de autor',
'exif-exifversion'                 => 'Versión Exif',
'exif-flashpixversion'             => 'Versión admitida de Flashpix',
'exif-colorspace'                  => 'Espacio de color',
'exif-componentsconfiguration'     => 'Significado de cada componente',
'exif-compressedbitsperpixel'      => "Rikch'ap ñit'isqa kaynin laya",
'exif-pixelydimension'             => "Chaniyuq rikch'ap suni kaynin",
'exif-pixelxdimension'             => "Chaniyuq rikch'ap hanaq kaynin",
'exif-makernote'                   => 'Notas del fabricante',
'exif-usercomment'                 => 'Comentarios de usuario',
'exif-relatedsoundfile'            => 'Archivo de audio relacionado',
'exif-datetimeoriginal'            => 'Fecha y hora de la generación de los datos',
'exif-datetimedigitized'           => 'Fecha y hora de la digitalización',
'exif-subsectime'                  => 'Fecha y hora (precisión por debajo del segundo)',
'exif-subsectimeoriginal'          => 'Fecha y hora de la generación de los datos (precisión por debajo del segundo)',
'exif-subsectimedigitized'         => 'Fecha y hora de la digitalización (precisón por debajo del segundo)',
'exif-exposuretime'                => 'Tiempo de exposición',
'exif-exposuretime-format'         => '$1 seg ($2)',
'exif-fnumber'                     => 'Número F',
'exif-exposureprogram'             => 'Programa de exposición',
'exif-spectralsensitivity'         => 'Sensibilidad espectral',
'exif-isospeedratings'             => 'Calificación de velocidad ISO',
'exif-oecf'                        => 'Factor de conversión optoelectrónica',
'exif-shutterspeedvalue'           => 'Velocidad de obturador',
'exif-aperturevalue'               => 'Apertura',
'exif-brightnessvalue'             => 'Luminosidad',
'exif-exposurebiasvalue'           => 'Sesgo de exposición',
'exif-maxaperturevalue'            => 'Valor máximo de apertura',
'exif-subjectdistance'             => 'Distancia al sujeto',
'exif-meteringmode'                => 'Modo de medición',
'exif-lightsource'                 => 'Fuente de luz',
'exif-focallength'                 => 'Longitud de la lente focal',
'exif-subjectarea'                 => 'Área del sujeto',
'exif-flashenergy'                 => 'Energía del flash',
'exif-spatialfrequencyresponse'    => 'Respuesta de frecuencia espacial',
'exif-focalplanexresolution'       => 'Resolución X plano focal',
'exif-focalplaneyresolution'       => 'Resolución Y plano focal',
'exif-focalplaneresolutionunit'    => 'Unidad de resolución del plano focal',
'exif-subjectlocation'             => 'Localización del sujeto',
'exif-exposureindex'               => 'Índice de exposición',
'exif-sensingmethod'               => 'Método de sensor',
'exif-filesource'                  => 'Fuente de archivo',
'exif-scenetype'                   => 'Tipo de escena',
'exif-cfapattern'                  => 'Patrón CFA',
'exif-customrendered'              => 'Procesador personalizado de imagen',
'exif-exposuremode'                => 'Modo de exposición',
'exif-whitebalance'                => 'Balance de blanco',
'exif-digitalzoomratio'            => 'Razón de zoom digital',
'exif-focallengthin35mmfilm'       => 'Longitud focal en película de 35 mm',
'exif-scenecapturetype'            => 'Tipo de captura de escena',
'exif-gaincontrol'                 => 'Control de escena',
'exif-contrast'                    => 'Contraste',
'exif-saturation'                  => 'Saturación',
'exif-sharpness'                   => 'Agudeza',
'exif-devicesettingdescription'    => 'Descripción de los ajustes del dispositivo',
'exif-subjectdistancerange'        => 'Rango de distancia al sujeto',
'exif-imageuniqueid'               => "Rikch'ap ch'ulla ID-nin",
'exif-gpsversionid'                => 'Versión de la etiqueta GPS',
'exif-gpslatituderef'              => 'Latitud norte o sur',
'exif-gpslatitude'                 => 'Latitud',
'exif-gpslongituderef'             => 'Longitud este u oeste',
'exif-gpslongitude'                => 'Longitud',
'exif-gpsaltituderef'              => 'Refencia de altitud',
'exif-gpsaltitude'                 => 'Altitud',
'exif-gpstimestamp'                => 'Tiempo GPS (reloj atómico)',
'exif-gpssatellites'               => 'Satélites usados para la medición',
'exif-gpsstatus'                   => 'Estado del receptor',
'exif-gpsmeasuremode'              => 'Modo de medición',
'exif-gpsdop'                      => 'Precisión de medición',
'exif-gpsspeedref'                 => 'Unidad de velocidad',
'exif-gpsspeed'                    => 'Velocidad del receptor GPS',
'exif-gpstrackref'                 => 'Referencia para la dirección del movimiento',
'exif-gpstrack'                    => 'Dirección del movimiento',
'exif-gpsimgdirectionref'          => 'Referencia de la dirección de imágen',
'exif-gpsimgdirection'             => 'Dirección de imágen',
'exif-gpsmapdatum'                 => 'Utilizados datos de medición geodésica',
'exif-gpsdestlatituderef'          => 'Referencia para la latitud del destino',
'exif-gpsdestlatitude'             => 'Destino de latitud',
'exif-gpsdestlongituderef'         => 'Referencia para la longitud del destino',
'exif-gpsdestlongitude'            => 'Longitud del destino',
'exif-gpsdestbearingref'           => 'Referencia para la orientación al destino',
'exif-gpsdestbearing'              => 'Orientación del destino',
'exif-gpsdestdistanceref'          => 'Referencia para la distancia al destino',
'exif-gpsdestdistance'             => 'Distancia al destino',
'exif-gpsprocessingmethod'         => 'Nombre del método de procesado GPS',
'exif-gpsareainformation'          => 'Nombre de la área GPS',
'exif-gpsdatestamp'                => 'Fecha GPS',
'exif-gpsdifferential'             => 'Corrección diferencial de GPS',

# EXIF attributes
'exif-compression-1' => "Mana ñit'isqa",

'exif-unknowndate' => 'Fecha desconocida',

'exif-orientation-2' => 'Volteada horizontalmente', # 0th row: top; 0th column: right
'exif-orientation-3' => 'Rotada 180°', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Volteada verticalmente', # 0th row: bottom; 0th column: left
'exif-orientation-5' => 'Rotada 90° CCW y volteada verticalmente', # 0th row: left; 0th column: top
'exif-orientation-6' => 'Rotada 90° CW', # 0th row: right; 0th column: top
'exif-orientation-7' => 'Rotada 90° CW y volteada verticalmente', # 0th row: right; 0th column: bottom
'exif-orientation-8' => 'Rotada 90° CCW', # 0th row: left; 0th column: bottom

'exif-planarconfiguration-1' => 'formato panorámico',
'exif-planarconfiguration-2' => 'formato plano',

'exif-componentsconfiguration-0' => 'manam kachkanchu',

'exif-exposureprogram-0' => 'No definido',
'exif-exposureprogram-2' => 'Programa normal',
'exif-exposureprogram-3' => 'Prioridad de apertura',
'exif-exposureprogram-4' => 'Prioridad de obturador',
'exif-exposureprogram-5' => 'Programa creativo (con prioridad a la profundidad de campo)',
'exif-exposureprogram-6' => 'Programa de acción (alta velocidad de obturador)',
'exif-exposureprogram-7' => 'Modo retrato (para primeros planos con el fondo desenfocado)',
'exif-exposureprogram-8' => 'Modo panorama (para fotos panorámicas con el fondo enfocado)',

'exif-subjectdistance-value' => '$1 metros',

'exif-meteringmode-0'   => 'Desconocido',
'exif-meteringmode-1'   => 'Media',
'exif-meteringmode-2'   => 'Promedio centrado',
'exif-meteringmode-3'   => 'Puntual',
'exif-meteringmode-4'   => 'Multipunto',
'exif-meteringmode-5'   => 'Patrón',
'exif-meteringmode-6'   => 'Parcial',
'exif-meteringmode-255' => 'Otro',

'exif-lightsource-0'   => 'Desconocido',
'exif-lightsource-1'   => 'Luz diurna',
'exif-lightsource-2'   => 'Fluorescente',
'exif-lightsource-3'   => 'Tungsteno (luz incandescente)',
'exif-lightsource-9'   => 'Buen tiempo',
'exif-lightsource-10'  => 'Tiempo nublado',
'exif-lightsource-11'  => 'Penumbra',
'exif-lightsource-12'  => 'Fluorescente de luz diurna (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Fluorescente de día soleado (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Fluorescente blanco frío (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Fluroescente blanco (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Luz estándar A',
'exif-lightsource-18'  => 'Luz estándar B',
'exif-lightsource-19'  => 'Luz estándar C',
'exif-lightsource-24'  => 'Tungsteno de estudio ISO',
'exif-lightsource-255' => 'Otra fuente de luz',

'exif-focalplaneresolutionunit-2' => 'pulgadas',

'exif-sensingmethod-1' => 'No definido',
'exif-sensingmethod-2' => 'Sensor de área de color de un chip',
'exif-sensingmethod-3' => 'Sensor de área de color de dos chips',
'exif-sensingmethod-4' => 'Sensor de área de color de tres chips',
'exif-sensingmethod-5' => 'Sensor de área secuencial de color',
'exif-sensingmethod-7' => 'Sensor trilineal',
'exif-sensingmethod-8' => 'Sensor lineal secuencial de color',

'exif-scenetype-1' => 'Una imagen directamente fotografiada',

'exif-customrendered-0' => 'Proceso normal',
'exif-customrendered-1' => 'Proceso personalizado',

'exif-exposuremode-0' => 'Exposición automática',
'exif-exposuremode-1' => 'Exposición manual',

'exif-whitebalance-0' => 'Balance de blanco automático',
'exif-whitebalance-1' => 'Balance de blanco manual',

'exif-scenecapturetype-0' => 'Estándar',
'exif-scenecapturetype-1' => 'Paisaje',
'exif-scenecapturetype-2' => 'Retrato',
'exif-scenecapturetype-3' => 'Escena nocturna',

'exif-gaincontrol-0' => 'Ninguna',
'exif-gaincontrol-1' => 'Bajo aumento de ganancia',
'exif-gaincontrol-2' => 'Alto aumento de ganancia',
'exif-gaincontrol-3' => 'Baja disminución de ganancia',
'exif-gaincontrol-4' => 'Alta disminución de ganancia',

'exif-contrast-1' => 'Suave',
'exif-contrast-2' => 'Duro',

'exif-saturation-1' => 'Baja saturación',
'exif-saturation-2' => 'Alta saturación',

'exif-sharpness-1' => 'Suave',
'exif-sharpness-2' => 'Dura',

'exif-subjectdistancerange-0' => 'Desconocida',
'exif-subjectdistancerange-2' => 'Vista cercana',
'exif-subjectdistancerange-3' => 'Vista lejana',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Latitud norte',
'exif-gpslatitude-s' => 'Latitud sur',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Longitud este',
'exif-gpslongitude-w' => 'Longitud oeste',

'exif-gpsstatus-a' => 'Medida en progreso',
'exif-gpsstatus-v' => 'Interoperabilidad de medida',

'exif-gpsmeasuremode-2' => 'Medición bidimensional',
'exif-gpsmeasuremode-3' => 'Medición tridimensional',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'Kilumitru uraman',
'exif-gpsspeed-m' => 'Milla uraman',
'exif-gpsspeed-n' => 'Muqukuna',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Chiqap puririy',
'exif-gpsdirection-m' => 'Maqnitiku puririy',

# External editor support
'edit-externally'      => "Kay willañiqita hawa rurana wakichiwan llamk'apuy",
'edit-externally-help' => 'Astawan willasunaykipaqqa [http://meta.wikimedia.org/wiki/Help:External_editors tiyachina yanapata] (inlish simipi) ñawiriy.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'tukuy',
'imagelistall'     => 'tukuy',
'watchlistall2'    => 'lliw',
'namespacesall'    => 'tukuy',
'monthsall'        => '(tukuy)',

# E-mail address confirmation
'confirmemail'            => 'E-chaski imamaytaykita sinchichay',
'confirmemail_noemail'    => 'No tienes una dirección de correo electrónico válida en tus [[Special:Preferences|preferencias de usuario]].',
'confirmemail_text'       => 'Este wiki requiere que valide su dirección de correo antes de usarlo. Pulse el botón de abajo para enviar la confirmación.
El correo incluirá un enlace con un código. Introdúzcalo para confirmar la validez de su dirección.',
'confirmemail_pending'    => '<div class="error">
Ya se te ha enviado un código de confirmación; si creaste una cuenta recientemente, puede que tengas que esperar unos minutos para que te llegue antes de intentar pedir un nuevo código.
</div>',
'confirmemail_send'       => 'Envíar el código de confimación.',
'confirmemail_sent'       => 'Confirmación de correo enviada.',
'confirmemail_oncreate'   => 'Se ha enviado un código de confirmación a tu dirección de correo electrónico.
Este código no es necesario para entrar, pero necesitarás darlo antes de activar cualquier función basada en correo electrónico en el wiki.',
'confirmemail_sendfailed' => 'No fue posible enviar el correo de confirmación. Por favor, compruebe que no haya caracteres inválidos en la dirección de correo indicada.

Correo devuelto: $1',
'confirmemail_invalid'    => 'Código de confirmación incorrecto. El código debe haber expirado.',
'confirmemail_needlogin'  => '$1-llawanmi e-chaski imamaytaykita sinchichayta atinki.',
'confirmemail_success'    => 'Su dirección de correo ha sido confirmada. Ahora puedes registrarse y colaborar en el wiki.',
'confirmemail_loggedin'   => 'E-chaski imamaytaykiqa sinchichasqañam.',
'confirmemail_error'      => 'Algo salió mal al guardar su confirmación.',
'confirmemail_subject'    => 'confirmación de la dirección de correo de {{SITENAME}}',
'confirmemail_body'       => 'Alguien, probablemente usted mismo, ha registrado una cuenta "$2" con esta dirección de correo en {{SITENAME}}, desde la dirección IP $1.

Para confirmar que esta cuenta realmente le pertenece y activar el correo en {{SITENAME}}, siga este enlace:

$3

Si la cuenta no es suya, no siga el enlace. El código de confirmación expirará en $4.',

# Scary transclusion
'scarytranscludedisabled' => "[Interwiki ch'aqtayman ama nisqa]",
'scarytranscludefailed'   => '[$1-paq plantillataqa manam chaskiyta atinchu; achachallaw]',
'scarytranscludetoolong'  => '[URL tiyayqa nisyu hatunmi; achachaw]',

# Trackbacks
'trackbackbox'      => '<div id="mw_trackbacks">
Trackbacks para este artículo:<br />
$1
</div>',
'trackbackremove'   => ' ([$1 Qulluy])',
'trackbackdeleteok' => 'El trackback se borró correctamente.',

# Delete conflict
'deletedwhileediting' => 'Aviso: ¡Esta página ha sido borrada después de que iniciase la edición!',
'confirmrecreate'     => "El usuario [[User:$1|$1]] ([[User talk:$1|discusión]]) borró este artículo después de que tú empezaces a editarlo y dio esta razón: ''$2'' Por favor, confirma que realmente deseas crear de nuevo el artículo.",
'recreate'            => 'Musuqta paqarichiy',

# HTML dump
'redirectingto' => 'Redirigiendo a [[$1]]...',

# action=purge
'confirm_purge'        => "Kay p'anqap ''cache'' nisqa pakasqa hallch'an ch'usaqchasqa kachunchu?

$1",
'confirm_purge_button' => 'Arí niy',

# AJAX search
'searchcontaining' => "''$1'' nisqa samiqniyuq p'anqakunata maskay.",
'searchnamed'      => "Buscar artículos con este nombre ''$1''.",
'articletitles'    => "Artículos que comienzan por ''$1''",
'hideresults'      => 'Lluqsiykunata pakay',

# Multipage image navigation
'imgmultipageprev'   => "← ñawpaq p'anqa",
'imgmultipagenext'   => "qatiq p'anqa →",
'imgmultigo'         => 'Riy!',
'imgmultigotopre'    => "Riy p'anqaman",
'imgmultiparseerror' => 'La imagen parece corrupta o incorrecta, de modo que {{SITENAME}} no puede obtener una lista de páginas.',

# Table pager
'table_pager_next'         => "Qatiq p'anqa",
'table_pager_prev'         => "ñawpaq p'anqa",
'table_pager_first'        => 'Primera página',
'table_pager_last'         => 'Última página',
'table_pager_limit'        => 'Mostrar $1 elementos por página',
'table_pager_limit_submit' => 'Riy',
'table_pager_empty'        => 'No hay resultados',

# Auto-summaries
'autosumm-blank'   => "P'anqata tukuy samiqninmanta ch'usaqchasqa",
'autosumm-replace' => "P'anqap tukuy samiqnin '$1'-wan huknachasqa",
'autoredircomment' => '[[$1]]-man pusapusqa',
'autosumm-new'     => "Musuq p'anqa: $1",

# Live preview
'livepreview-loading' => 'Cargando…',
'livepreview-ready'   => 'Cargando… ¡Listo!',
'livepreview-failed'  => '¡La previsualización al vuelo falló!
Prueba la previsualización normal.',
'livepreview-error'   => 'La conexión no ha sido posible: $1 "$2"
Intenta la previsualización normal.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Los cambios realizados en los últimos $1 segundos pueden no ser mostrados en esta lista.',
'lag-warn-high'   => 'Debido a una alta latencia el servidor de base de datos, los cambios realizados en los últimos $1 segundos pueden no ser mostrados en esta lista.',

# Watchlist editor
'watchlistedit-numitems'       => 'Tu lista de seguimiento tiene {{PLURAL:$1|una página |$1 páginas}}, excluyendo las páginas de discusión.',
'watchlistedit-noitems'        => 'Tu lista de seguimiento está vacía.',
'watchlistedit-normal-title'   => 'Editar lista de seguimiento',
'watchlistedit-normal-legend'  => 'Borrar títulos de la lista de seguimiento',
'watchlistedit-normal-explain' => "Las páginas de tu lista de seguimiento se muestran debajo. Para eliminar una página, marca la casilla junto a la página, y haz clic en ''Borrar páginas''. También puedes [[Special:Watchlist/raw|editar la lista en crudo]] o [[Special:Watchlist/clear|borrarlo todo]].",
'watchlistedit-normal-submit'  => "P'anqa sutikunamanta ch'usaqchay",
'watchlistedit-normal-done'    => '{{PLURAL:$1|1 página ha sido borrada|$1 páginas han sido borradas}} de tu lista de seguimiento:',
'watchlistedit-raw-title'      => 'Editar lista de seguimiento en crudo',
'watchlistedit-raw-legend'     => 'Editar tu lista de seguimiento en modo texto',
'watchlistedit-raw-explain'    => 'Las páginas de tu lista de seguimiento se muestran debajo. Esta lista puede ser editada añadiendo o eliminando líneas de la lista; una página por línea. Cuando acabes, haz clic en Actualizar lista de seguimiento. También puedes utilizar el [[Especial:Watchlist/edit|editor estándar]].',
'watchlistedit-raw-titles'     => "P'anqakuna:",
'watchlistedit-raw-submit'     => 'Actualizar lista de seguimiento',
'watchlistedit-raw-done'       => 'Tu lista de seguimiento se ha actualizado.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|Se ha añadido una página|Se han añadido $1 páginas}}:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|Una página ha sido borrada|$1 páginas han sido borradas}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Hukchasqakunata qhaway',
'watchlisttools-edit' => "Watiqana sutisuyuta qhawaspa llamk'apuy",
'watchlisttools-raw'  => "Chawa watiqana sutisuyuta llamk'apuy",

);
