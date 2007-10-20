<?php
/** Quechua (Runa Simi)
  *
  * @addtogroup Language
  *
  * @author Gangleri
  * @author AlimanRuna
  */

$fallback = 'es';

$messages = array(
# User preference toggles
'tog-underline'        => "T'inkikunata uranpi sikwiy",
'tog-highlightbroken'  => 'Ch\'usaq p\'anqaman t\'inkimuqkunata sananchay <a href="" class="new">kay hinam</a> (icha kay hinam<a href="" class="internal">?</a>).',
'tog-justify'          => 'Rakirikunata paqtachiy',
'tog-hideminor'        => '«Ñaqha hukchasqa» nisqapi aslla hukchasqakunata pakay',
'tog-extendwatchlist'  => "Watiqana sutisuyuta tukuy rurachinalla hukchaykunaman mast'ay",
'tog-usenewrc'         => "Sananchasqa ñaqha hukchasqakuna (JavaScript: manam tukuy wamp'unakunapichu llamk'an)",
'tog-numberheadings'   => "Uma siq'ikunata kikinmanta yupay",
'tog-rememberpassword' => "Yaykuna rimata yuyaykuy llamk'ay tiyaypura",
'tog-watchcreations'   => "Qallarisqay p'anqakunata watiqay.",
'tog-watchdefault'     => "Hukchasqay p'anqakunata watiqay",
'tog-watchmoves'       => "Astasqay p'anqakunata watiqay",
'tog-watchdeletion'    => "Qullusqay p'anqakunata watiqay",
'tog-minordefault'     => 'Tukuy hukchasqakunata kikinmanta aslla nispa sananchay',
'tog-previewontop'     => "Rikch'ay qhawana ñawpaqman, ama qhipanpi kachunchu",
'tog-previewonfirst'   => "Manaraq llamk'apuspa rikch'ayta qhaway",

'underline-always' => "Hayk'appas",
'underline-never'  => "Mana hayk'appas",

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
'jan'           => 'I',
'feb'           => 'II',
'mar'           => 'III',
'apr'           => 'IV',
'may'           => 'V',
'jun'           => 'VI',
'jul'           => 'VII',
'aug'           => 'VIII',
'sep'           => 'IX',
'oct'           => 'X',
'nov'           => 'XI',
'dec'           => 'XII',

# Bits of text used by many pages
'categories'     => 'Katiguriyakuna',
'category-empty' => "''Kay katiguriyaqa ch'usaqmi.''",

'about'          => "P'anqamanta",
'newwindow'      => '(Musuq wintanam kichakun)',
'cancel'         => 'Ama niy',
'qbfind'         => 'Maskay',
'qbbrowse'       => 'Maskapuy',
'qbedit'         => "Llamk'apuy",
'qbspecialpages' => "Sapaq p'anqakuna",
'moredotdotdot'  => 'Aswan...',
'mypage'         => "P'anqay",
'mytalk'         => 'Rimachinay',
'anontalk'       => 'Kay IP huchhapaq rimanakuy',
'navigation'     => "Wanp'una",

'returnto'          => '$1-man kutimuy.',
'tagline'           => 'Wikipidiyaman',
'help'              => 'Yanapa',
'search'            => 'Maskhay',
'searchbutton'      => 'Maskay',
'go'                => 'Riy',
'searcharticle'     => 'Riy',
'history'           => 'Wiñay kawsay panka',
'history_short'     => 'Wiñay kawsay',
'printableversion'  => "Ch'ipachinapaq",
'permalink'         => "Kakuq t'inki",
'print'             => "Ch'ipachiy",
'edit'              => 'qillqay',
'editthispage'      => "Kay p'anqata llamk'apuy",
'delete'            => 'Qulluy',
'deletethispage'    => "Kay p'anqata qulluy",
'undelete_short'    => "Paqarichiy {{PLURAL:$1|huk llamk'apusqa|$1 llamk'apusqa}}",
'protect'           => 'Amachay',
'protectthispage'   => "Kay p'anqata amachay",
'unprotect'         => 'Amaña amachaychu',
'unprotectthispage' => "Kay p'anqata amaña amachaychu",
'talkpage'          => "Kay p'anqamanta rimanakuy",
'talkpagelinktext'  => 'rimanakuy',
'specialpage'       => "Sapaq p'anqa",
'personaltools'     => 'Kikin ruraqpa khillaykuna',
'articlepage'       => 'Qillqata qhaway',
'talk'              => 'Rimachina',
'views'             => 'Rikunakuna',
'toolbox'           => "Llank'anakuna",
'userpage'          => "Ruraqpa p'anqanta rikuy",
'projectpage'       => "Meta p'anqata qhaway",
'imagepage'         => "Rikch'amanta p'anqata qhaway",
'mediawikipage'     => "Willay p'anqata qhaway",
'templatepage'      => "Plantilla p'anqata qhaway",
'viewhelppage'      => "Yanapana p'anqata qhaway",
'categorypage'      => "Katiguriya p'anqata qhaway",
'viewtalkpage'      => 'Rimachinata rikuy',
'otherlanguages'    => 'Huq simikunapi',
'lastmodifiedat'    => "Kay p'anqaqa $2, $1 qhipaq kutitam hukchasqa karqan.", # $1 date, $2 time
'viewcount'         => "Kay p'anqaqa {{PLURAL:$1|huk kuti|$1 kuti}} watukusqañam.",
'protectedpage'     => "Amachasqa p'anqa",
'jumpto'            => 'Kayman riy:',
'jumptonavigation'  => "wamp'una",
'jumptosearch'      => 'maskana',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => '{{SITENAME}}manta',
'aboutpage'         => 'Wikipidiya:Wikipidiyamanta',
'copyright'         => "Ch'aqtasqakunataqa llamk'achinkiman <i>$1</i> nisqap ruraq hayñinkama",
'copyrightpagename' => "{{SITENAME}} p'anqayuq ruraqpa iskaychay hayñin",
'copyrightpage'     => 'Wikipidiya:Ruraqpa hayñin',
'currentevents'     => 'Kunan pacha',
'currentevents-url' => 'Project:Kunan pacha',
'disclaimers'       => 'Chiqakunamanta rikuchiy',
'disclaimerpage'    => 'Wikipidiya:Sapsilla saywachasqa paqtachiy',
'edithelp'          => "Llamk'ana yanapay",
'edithelppage'      => 'Yanapa:Qillqa yanapay',
'helppage'          => 'Wikipidiya:Yanapana',
'mainpage'          => 'Qhapaq panka',
'portal'            => 'Ayllupaq panka',
'portal-url'        => 'Project:Ayllupaq panka',
'privacy'           => 'Wikipidiyap willakunata amachaynin',
'privacypage'       => 'Wikipidiya:Willakunata amachay',
'sitesupport'       => 'Qarana',
'sitesupport-url'   => 'Wikipidiya:Ruraykamayman qarana',

'pagetitle'               => '$1 - Wikipidiya',
'retrievedfrom'           => '"$1" p\'anqamanta chaskisqa (Wikipedia, Qhichwa / Quechua)',
'youhavenewmessages'      => '$1(ni)ykim kachkan ($2).',
'newmessageslink'         => 'Musuq willay',
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

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'Qillqasqa',
'nstab-user'      => 'Ruwaqpa panka',
'nstab-special'   => 'Sapaq',
'nstab-project'   => "Ruraykamaypa p'anqan",
'nstab-image'     => "Rikch'a",
'nstab-mediawiki' => 'Willay',
'nstab-help'      => 'Yanapa',

# General errors
'noconnect'          => "Manam $1-pi willañiqintinwan t'inkiyta atinichu",
'readonly'           => "Willañiqintinqa hark'asqam",
'readonly_lag'       => "Willañiqintinqa mit'alla hark'asqam, sirwiqkuna kikinpachachastin.",
'internalerror'      => 'Ukhu pantasqa',
'internalerror_info' => 'Ukhu pantasqa: $1',
'badtitle'           => "P'anqap sutinqa manam allinchu",
'badtitletext'       => "Kay p'anpaq sutinqa manam allinchu, mana allin interwiki t'inkichá kanman.",
'perfcachedts'       => 'Kay willakunaqa waqaychasqam. Qhipaq musuqchasqaqa $1 karqan.',
'viewsource'         => 'Pukyu qillqata qhaway',
'viewsourcefor'      => '$1-paq',
'protectedpagetext'  => "Kay p'anqaqa llamk'apuymanta amachasqam.",
'viewsourcetext'     => "Kay p'anqatam qhawayta iskaychaytapas atinki:",
'protectedinterface' => "Kay p'anqapiqa wakichintinpa uyapuranpaq qillqam. Wandalismu nisqamanta amachasqam kachkan. Kay qillqata allinchayta munaspaykiqa, [[{{MediaWiki:grouppage-sysop}}|kamachiqta]] tapuy.",
'editinginterface'   => "'''Yuyallay:''' {{SITENAME}} nisqap uyapuranmanta p'anqatam llamk'apuchkanki. Hukchaptiykiqa, chay uyapurap rikch'ayninqa hukyan huk ruraqkunapaqpas.",
'cascadeprotected'   => "Kay p'anqaqa amachasqam kachkan, ''phaqcha'' nisqa kamachiwan amachasqa kay {{PLURAL:$1|p'anqapi|p'anqakunapi}} ch'aqtasqa kaspanmi:",
'namespaceprotected' => "'''$1''' nisqa suti k'ititaqa llamk'apuyta manam saqillasunkichu.",

# Login and logout pages
'logouttitle'                => "Llamk'apuy tiyaypa puchukaynin",
'welcomecreation'            => '== Allin hamusqayki, $1! ==

Rakiqunaykiqa kichasqañam. Wikipidiyapaq [[Special:Preferences|allinkachinaykita]] kutikuytaqa ama qunqaychu.',
'loginpagetitle'             => 'Yaykuy',
'yourname'                   => 'Ruraq sutiyki',
'yourpassword'               => 'Yaykuna rimayki',
'yourpasswordagain'          => 'Yaykuna rimaykita kutipayay',
'remembermypassword'         => "Llamk'apuy tiyayniykunapura yuyaykuway.",
'yourdomainname'             => 'Duminyuykip sutin',
'loginproblem'               => '<b>Manam yaykuytachu atirqunki.</b><br />Huk kutitam ruraykachay!',
'login'                      => 'Yaykuy',
'loginprompt'                => "{{SITENAME}}man yaykunaykipaqqa wamp'unaykipi <i>cookies</i> nisqakunaman ari ninaykim atin.",
'userlogin'                  => 'Yaykuy',
'logout'                     => 'Yarquy',
'userlogout'                 => 'Yarquy',
'nologin'                    => 'Manaraqchu rakiqunaykichu kachkan? $1.',
'nologinlink'                => 'Kichariy',
'createaccount'              => 'Musuq rakiqunata kichariy',
'gotaccount'                 => 'Rakiqunaykiñachu kachkan? $1.',
'gotaccountlink'             => 'Rakiqunaykita willaway',
'createaccountmail'          => 'chaskipaq',
'youremail'                  => 'E-chaski imamaytayki',
'username'                   => 'Ruraqpa sutin:',
'uid'                        => 'Ruraqpa ID-nin:',
'yourrealname'               => 'Chiqap sutiyki*',
'yourlanguage'               => 'Rimay',
'yourvariant'                => "Rimaypa rikch'aynin",
'yournick'                   => 'Chutu sutiyki (ruruchinapaq)',
'email'                      => 'E-chaski',
'loginerror'                 => "Pantasqa llamk'apuy tiyaypa qallarisqan",
'nocookieslogin'             => "{{SITENAME}} <em>kuki</em> nisqakunata llamk'achin ruraqkunata kikinyachinapaq. Antañiqiqniykipiqa manam <em>kuki</em> nisqakuna atinchu. Ama hina kaspa, atichispa huk kutita ruraykachay.",
'loginsuccesstitle'          => "Llamk'apuy tiyayqa qallarisqañam",
'loginsuccess'               => 'Llamk\'apuy tiyayniykiqa qallarisqam {{SITENAME}}-pi "$1" sutiyuq kaspa.',
'wrongpassword'              => 'Qillqamusqayki yaykuna rimaqa manam allinchu. Huk kutita ruraykachay.',
'wrongpasswordempty'         => 'Yaykuna rimaykita qillqamuyta qunqarqunkim, huk kutita ruraykachay.',
'mailmypassword'             => 'Musuq yaykuna rimatam e-chaskiwan kachamuway',
'acct_creation_throttle_hit' => '$1 sutiyuq rakiqunaqa kachkañam. Manam atinkichu kaqllata kichayta.',
'emailauthenticated'         => 'E-chaski imamaytaykiqa $1 nisqapi chiqapchasqañam.',
'emailnotauthenticated'      => 'E-chaski imamaytaykitaqa manaraqmi sinchicharqunkichu. Mana sinchicharquptiykiqa, kay qatiq rurachinakunataqa manam atinkichu.',
'emailconfirmlink'           => 'E-chaski imamaytaykita sinchichariy',
'invalidemailaddress'        => "E-chaski imamaytaykiqa manam allinchu. Ama hina kaspa, musuq allin sananchayuq imamaytaykita qillqamuy icha k'itichata ch'usaqchay.",
'accountcreated'             => 'Rakiqunaqa kichasqañam',
'accountcreatedtext'         => '$1 sutiyuq ruraqpa rakiqunanqa kichasqañam.',
'loginlanguagelabel'         => 'Rimay: $1',

# Edit page toolbar
'bold_sample'     => 'Yanasapa qillqa',
'bold_tip'        => 'Yanasapa qillqa',
'italic_sample'   => 'Wiksu qillqa',
'italic_tip'      => 'Wiksu qillqa',
'link_sample'     => "T'inkip sutin",
'link_tip'        => "Ukhu t'inki",
'headline_sample' => "Uma siq'i qillqa",
'headline_tip'    => "Iskay ñiqi hanaq siq'i qillqa",
'image_tip'       => "Ch'aqtasqa rikch'a",

# Edit pages
'summary'                 => 'Pisichay',
'minoredit'               => 'Kayqa uchuylla hukchaymi',
'watchthis'               => 'Kay qillqata watiqay',
'savearticle'             => "P'anqata waqaychay",
'preview'                 => 'Manaraq waqaychaspa qhawariy',
'showpreview'             => 'Ñawpaqta qhawallay',
'showdiff'                => 'Hukchasqakunata rikuchiy',
'anoneditwarning'         => "''Paqtataq:'' Manaraqmi ruraqpa sutiykita qumurqunkichu. IP huchhaykim kay p'anqap hukchay hallch'ayninpi waqaychasqa kanqa.",
'summary-preview'         => 'Pisichayta ñawpaqta qhawarillay',
'blockedtitle'            => "Ruraqqa hark'asqam",
'whitelistacctitle'       => 'Rakiqunata kichariyqa manam saqillasqachu',
'loginreqtitle'           => 'Yaykunaykim atin',
'loginreqlink'            => 'yaykuna',
'loginreqpagetext'        => "Huk p'anqakunata rikunaykipaqqa $1ykim atin.",
'accmailtitle'            => 'Yaykuna rimaqa kachasqañam.',
'accmailtext'             => '«$1»-paq yaykuna rimaqa $2-manmi kachasqa.',
'newarticle'              => '(Musuq)',
'newarticletext'          => "Manaraq kachkaq p'anqatam llamk'apuchkanki. Musuq p'anqata kamariyta munaspaykiqa, qillqarillay. Astawan ñawiriyta munaspaykiqa, [[{{MediaWiki:helppage}}|yanapana p'anqata]] qhaway. Mana munaspaykitaq, ñawpaq p'anqaman ripuy.",
'anontalkpagetext'        => "---- ''Kayqa huk sutinnaq icha mana sutinta llamk'achiq ruraqpa rimanakuyninmi. IP huchhantam hallch'asunchik payta sutinchanapaq. Achka ruraqkunam huklla IP huchhanta llamk'achiyta atin. Sutinnaq ruraq kaspaykiqa, mana qampa rurasqaykimanta willamusqakunata rikuspaykiqa, ama hina kaspa [[Special:Userlogin|ruraqpa sutiykita kamariy icha yaykuy]] huk sutinnaq ruraqkunawan ama pantasqa kanaykipaq.''",
'noarticletext'           => "Kay p'anqaqa ch'usaqmi.
*{{PAGENAME}} nisqata [[Special:Search/{{PAGENAME}}|huk qillqakunapi]] maskay
*{{PAGENAME}} nisqaman [[Special:Allpages/{{PAGENAME}}|kaqlla qillqa sutikunata]] maskay
*{{PAGENAME}} nisqamanta [http://qu.wikipedia.org/w/index.php?title={{PAGENAME}}&action=edit musuq qillqata] qallariy
Manañam kachkanchu?
*[http://qu.wikipedia.org/wiki/{{PAGENAME}} Huk kutita chaqnamuy]
*[http://qu.wikipedia.org/w/index.php?title={{PAGENAME}}&action=purge Paka qullqa ''(cache)'' nisqata ch'usaqchay]",
'usercssjsyoucanpreview'  => "<strong>Kunay:</strong> «Ñawpaqta qhawallay» nisqa ñit'inata llamk'achiy musuq css/js qhawanaykipaq, manaraq waqaychaspa.",
'updated'                 => '(Musuqchasqa)',
'previewnote'             => 'Yuyaykuy: Kayqa manaraq waqaychaspa qhawariymi!',
'previewconflict'         => "Rikuchkanki kay p'anqataqa, ima hinachus waqaychasqa kanqa.",
'editing'                 => "$1-ta llamk'apuspa",
'editinguser'             => "$1-ta llamk'apuspa",
'editingsection'          => "$1-ta llamk'apuspa (raki)",
'editingcomment'          => "$1-ta llamk'apuspa (rimapay)",
'editconflict'            => 'Ruray taripanakuy: $1',
'explainconflict'         => "Ruray taripanakuy: Huk runam kay p'anqata llamk'apurqun, qamtaq manaraq waqaychaptiyki.
Umapi kaq qillqana k'itipi kunan kachkaq qillqam. Qampa hukchasqaykikunataq sikipi kaq qillqana k'itipim.
Kunanqa rurasqaykikunata musuq qillqaman ch'aqtanaykim atin.
<b>Umapi kaq qillqallam</b> waqaychasqa kanqa.<br />",
'yourtext'                => 'Qillqasqayki',
'editingold'              => "<strong>Paqtataq: Kay p'anqap mawk'a hukchasqantam llamk'apuchkanki. Waqaychaptiykiqa, chaymanta aswan musuq hukchasqankuna chinkanqam.</strong>",
'yourdiff'                => 'Hukchasqaykikuna',
'copyrightwarning'        => '<div class="plainlinks" style="margin-top:1px;border-width:1px;border-style:solid;border-color:#aaaaaa;padding:2px;">
<center>
<small>
<charinsert>á é í ó ú ý Á É Í Ó Ú Ý ü Ü ñ Ñ</charinsert> ·
<charinsert>¡ ¿ «+»  - † º ª </charinsert> ·
<charinsert>Â â Ê ê Î î Ô ô Û û</charinsert> ·
<charinsert>Ä ä Ë ë Ö ö Ü ü </charinsert> ·
<charinsert>Ç ç </charinsert> ·
<charinsert>Ā ā Ē ē Ī ī Ō ō Ū ū </charinsert> ·
<charinsert>ß </charinsert> ·
<charinsert>Ð ð Þ þ </charinsert> ·
<charinsert>Æ æ Œ œ </charinsert> ·
<charinsert>&ndash; &mdash; </charinsert> ·
<charinsert>[+] [[+]] {{+}} </charinsert> ·
<charinsert>~ | °</charinsert>
</small></center>
</div>

{|
| colspan="3" |
Lliw \'\'\'Wikipidiya\'\'\'paq llamk\'apuykunaqa $2 nisqawanmi uyaychasqa kanku ($1 p\'anqata qhaway). Huk runakunap llamk\'asqaykikunata allinchayninta qispilla mast\'ariyninta mana munaptiykiqa, ama chayman qillqamuychu. Qammi paqtachiq tukuy qillqamusqaykikunapaq.
|-
| colspan="3" | <p style="background: red; color: white; font-weight: bold; text-align: center; padding: 2px;">Ama qillqarimuychu iskaychay hayñi \'\'(copyright)\'\' nisqayuq qillqakunata iskaychamuspa!</p>
|-
| <p style="background: red; color: white; font-weight: bold; text-align: center; padding: 2px;">¡No uses escritos con copyright sin permiso!</p>
| <p style="background: red; color: white; font-weight: bold; text-align: center; padding: 2px;">Do not submit copyrighted work without permission!</p>
|}',
'protectedpagewarning'    => "<strong>PAQTATAQ: Kay p'anqaqa llamk'apuymanta amachasqam kamachiqkunallap hukchananpaq. Sinchita qhawakuy [[Wikipidiya:Amachay|Wikipidiyap amachaymanta kamachinkunata]] qatiyniykita.</strong>
__NOEDITSECTION__<h3>Kay p'anqaqa [[Wikipidiya:Amachay|amachasqam]].</h3>
* Kay amachaymanta ama niyta munaspaykiqa [[{{TALKPAGENAME}}|rimachina p'anqaman]] qillqamuy.<br />",
'cascadeprotectedwarning' => "'''Yuyallay:''' Kay p'anqaqa amachasqam, kamachiqkunallam llamk'apuyta atin, ''phaqcha'' nisqa kamachiwan amachasqa kay p'anqakunapim ch'aqtasqa kaspanmi:",
'templatesused'           => "Kay p'anqapi llamk'achisqa plantillakuna:",
'templatesusedpreview'    => "Kay qhawariypi llamk'achisqa plantillakuna:",
'templatesusedsection'    => "Kay p'anqa rakipi llamk'achisqa plantillakuna:",
'template-protected'      => '(amachasqa)',
'template-semiprotected'  => '(rakilla amachasqa)',
'edittools'               => '<!-- Este texto aparecerá bajo los formularios de edición y subida. -->
<div id="specialchars" class="plainlinks" style="margin-top:3px; border-style:solid; border-width:1px; border-color:#aaaaaa; padding:1px; text-align:left; background-color:white;" title="Click on the wanted special character.">

<p class="specialbasic" id="Standard">
<charinsert>[+]</charinsert> ·
<charinsert>[[+]]</charinsert> ·
<charinsert>{{+}}</charinsert> ·
<charinsert>[[Category:+]]</charinsert> ·
<charinsert>[[:Image:+]]</charinsert> ·
<charinsert>[[Media:+]]</charinsert> ·
<charinsert><gallery>+</gallery></charinsert> ·
<charinsert>#REDIRECT[[+]]</charinsert></small> ·
<charinsert>– —</charinsert> ·
<charinsert>“+” ‘+’ «+» ‹+› „+“ ‚+‘</charinsert> ·
<charinsert>~ | °</charinsert> ·
<charinsert> · × ² ³ ½ € †</charinsert>
</p>

<p class="specialbasic" id="Latin" style="display:none">
<charinsert>Á á Ć ć É é Í í Ó ó Ś ś Ú ú Ý ý Ǿ ǿ </charinsert> ·
<charinsert>À à È è Ì ì Ò ò Ù ù </charinsert> ·
<charinsert>Â â Ĉ ĉ Ê ê Ĝ ĝ Ĥ ĥ Î î Ĵ ĵ Ô ô ŝ Ŝ Û û </charinsert> ·
<charinsert>Ä ä Ë ë Ï ï Ö ö Ü ü ÿ </charinsert> ·
<charinsert>Ã ã Ñ ñ Õ õ </charinsert> ·
<charinsert>Å å </charinsert> ·
<charinsert>Ç ç </charinsert> ·
<charinsert>Č č Š š ŭ </charinsert> ·
<charinsert>Ł ł </charinsert> ·
<charinsert>Ő ő Ű ű </charinsert> ·
<charinsert>Ø ø </charinsert> ·
<charinsert>Ā ā Ē ē Ī ī Ō ō Ū ū </charinsert> ·
<charinsert>ß </charinsert> ·
<charinsert>Æ æ Œ œ </charinsert> · 
<charinsert>Ð ð Þ þ |</charinsert>
</p>

<p class="specialbasic" id="Greek" style="display:none" >
<charinsert>Α Ά Β Γ Δ Ε Έ Ζ Η Ή Θ Ι Ί Κ Λ Μ Ν Ξ Ο Ό Π Ρ Σ Τ Υ Ύ Φ Χ Ψ Ω Ώ</charinsert> · 
<charinsert>α ά β γ δ ε έ ζ η ή θ ι ί κ λ μ ν ξ ο ό π ρ σ ς τ υ ύ φ χ ψ ω ώ</charinsert>
</p>

<p class="specialbasic" id="Cyrillic" style="display:none">
<charinsert>А Б В Г Д Ђ Е Ё Ж З Ѕ И Й Ј К Л Љ М Н Њ О П Р С Т Ћ У Ф Х Ц Ч Џ Ш Щ Ъ Ы Ь Э Ю Я </charinsert> · 
<charinsert>а б в г д ђ е ё ж з ѕ и й ј к л љ м н њ о п р с т ћ у ф х ц ч џ ш щ ъ ы ь э ю я </charinsert>
</p>

<p class="specialbasic" id="IPA" style="display:none">
<charinsert> ʈ ɖ ɟ ɡ ɢ ʡ ʔ </charinsert> · 
<charinsert> ɸ ʃ ʒ ɕ ʑ ʂ ʐ ʝ ɣ ʁ ʕ ʜ ʢ ɦ </charinsert> · 
<charinsert> ɱ ɳ ɲ ŋ ɴ </charinsert> · 
<charinsert> ʋ ɹ ɻ ɰ </charinsert> · 
<charinsert> ʙ ʀ ɾ ɽ </charinsert> · 
<charinsert> ɫ ɬ ɮ ɺ ɭ ʎ ʟ </charinsert> · 
<charinsert> ɥ ʍ ɧ </charinsert> · 
<charinsert> ɓ ɗ ʄ ɠ ʛ </charinsert> · 
<charinsert> ʘ ǀ ǃ ǂ ǁ </charinsert> · 
<charinsert> ɨ ʉ ɯ </charinsert> · 
<charinsert> ɪ ʏ ʊ </charinsert> · 
<charinsert> ɘ ɵ ɤ </charinsert> · 
<charinsert> ɚ </charinsert> · 
<charinsert> ɛ ɜ ɝ ɞ ʌ ɔ </charinsert> · 
<charinsert> ɐ ɶ ɑ ɒ </charinsert> · 
<charinsert> ʰ ʷ ʲ ˠ ˤ ⁿ ˡ </charinsert> · 
<charinsert> ˈ ˌ ː ˑ </charinsert>
</p>

<p class="specialbasic" id="Arabic" style="display:none">
<span dir="rtl" style="font-size:120%;">
<charinsert>ا ب ت ث ج ح خ د ذ ر ز س ش ص ض ط ظ ع غ ف ق ك ل م ن ه و ي</charinsert> · 
<charinsert>ﺍ ﺑ ﺗ ﺛ ﺟ ﺣ ﺧ ﺩ ﺫ ﺭ ﺯ ﺳ ﺷ ﺻ ﺿ ﻃ ﻇ ﻋ ﻏ ﻓ ﻗ ﻛ ﻟ ﻣ ﻧ ﻫ ﻭ ﻳ</charinsert> ·
<charinsert>ﺍ ﺒ ﺘ ﺜ ﺠ ﺤ ﺨ ﺪ ﺬ ﺮ ﺰ ﺴ ﺸ ﺼ ﻀ ﻄ ﻈ ﻌ ﻐ ﻔ ﻘ ﻜ ﻠ ﻤ ﻨ ﻬ ﻮ ﻴ</charinsert> ·
<charinsert>ﺎ ﺐ ﺖ ﺚ ﺞ ﺢ ﺦ ﺪ ﺬ ﺮ ﺰ ﺲ ﺶ ﺺ ﺾ ﻂ ﻆ ﻊ ﻎ ﻒ ﻖ ﻚ ﻞ ﻢ ﻦ ﻪ ﻮ ﻲ</charinsert> ·
<charinsert>ء- ّ- ْ- ً- ِ- آ أ إ ة ؤ ئ ى</charinsert> ·
<charinsert>پ چ ژ گ ﭪ &#1696; ۰ ۱ ۲ ۳ ٤ ٥ ٦ ٧ ۸ ۹</charinsert>
</span>
</p>

<p class="specialbasic" id="Catalan" style="display:none">
<charinsert>Á á À à Ç ç É é È è Ë ë Í í Ï ï Ó ó Ò ò Ö ö Ú ú Ù ù</charinsert>
</p>

<p class="specialbasic" id="Czech" style="display:none">
<charinsert>Á á Č č Ď ď É é Ě ě Í í Ň ň Ó ó Ř ř Š š Ť ť Ú ú Ů ů Ý ý Ž ž</charinsert>
</p>

<p class="specialbasic" id="Devanāgarī" style="display:none">
<charinsert>ँ ं ः अ आ इ ई उ ऊ ऋ ऌ ऍ ऎ ए ऐ ऑ ऒ ओ औ क क़ ख ख़ ग ग़ घ ङ च छ ज ज़ झ ञ ट ठ ड ड़ द ढ ढ़ ण त थ ध न ऩ प फ फ़ ब भ म य य़ र ऱ ल ळ ऴ व श ष स ह ़ ऽ ा ि ॊ ो ौ ् ी ु ू ृ ॄ ॅ ॆ े ै ॉ ॐ ॑ ॒ ॓ ॔ ॠ ॡ ॢ ॣ । ॥ ॰</charinsert>
</p>

<p class="specialbasic" id="Esperanto" style="display:none">
<charinsert>Ĉ ĉ Ĝ ĝ Ĥ ĥ Ĵ ĵ Ŝ ŝ Ŭ ŭ</charinsert>
</p>

<p class="specialbasic" id="Estonian" style="display:none">
<charinsert>Č č Š š Ž ž Õ õ Ä ä Ö ö Ü ü</charinsert>
</p>

<p class="specialbasic" id="French" style="display:none">
<charinsert>À à Â â Ç ç É é È è Ê ê Ë ë Î î Ï ï Ô ô Œ œ Ù ù Û û Ü ü Ÿ ÿ</charinsert>
</p>

<p class="specialbasic" id="German" style="display:none">
<charinsert>Ä ä Ö ö Ü ü ß</charinsert>
</p>

<p class="specialbasic" id="Hawaiian" style="display:none">
<charinsert>Ā ā Ē ē Ī ī Ō ō Ū ū ʻ</charinsert>
</p>

<p class="specialbasic" id="Hebrew" style="display:none">
<charinsert>א ב ג ד ה ו ז ח ט י כ ך ל מ ם נ ן ס ע פ ף צ ץ ק ר ש ת ־ ״ ׳</charinsert>
</p>

<p class="specialbasic" id="Hungarian" style="display:none">
<charinsert>Ő ő Ű ű</charinsert>
</p>

<p class="specialbasic" id="Icelandic" style="display:none">
<charinsert>Á á Ð ð É é Í í Ó ó Ú ú Ý ý Þ þ Æ æ Ö ö</charinsert>
</p>

<p class="specialbasic" id="Italian" style="display:none">
<charinsert>Á á À à É é È è Í í Ì ì Ó ó Ò ò Ú ú Ù ù</charinsert>
</p>

<p class="specialbasic" id="Latvian" style="display:none">
<charinsert>Ā ā Č č Ē ē Ģ ģ Ī ī Ķ ķ Ļ ļ Ņ ņ Š š Ū ū Ž ž</charinsert> 
</p>

<p class="specialbasic" id="Maltese" style="display:none">
<charinsert>Ċ ċ Ġ ġ Ħ ħ Ż ż</charinsert>
</p>

<p class="specialbasic" id="Old English" style="display:none">
<charinsert>Ā ā Æ æ Ǣ ǣ Ǽ ǽ Ċ ċ Ð ð Ē ē Ġ ġ Ī ī Ō ō Ū ū Ƿ ƿ Ȳ ȳ Þ þ Ȝ ȝ</charinsert>
</p>

<p class="specialbasic" id="Pinyin" style="display:none">
<charinsert>Á á À à Ǎ ǎ Ā ā É é È è Ě ě Ē ē Í í Ì ì Ǐ ǐ Ī ī Ó ó Ò ò Ǒ ǒ Ō ō Ú ú Ù ù Ü ü Ǔ ǔ Ū ū Ǘ ǘ Ǜ ǜ Ǚ ǚ Ǖ ǖ</charinsert>
</p>

<p class="specialbasic" id="Polish" style="display:none">
<charinsert>ą Ą ć Ć ę Ę ł Ł ń Ń ó Ó ś Ś ź Ź ż Ż</charinsert>
</p>

<p class="specialbasic" id="Portuguese" style="display:none">
<charinsert>Á á À à Â â Ã ã Ç ç É é Ê ê Í í Ó ó Ô ô Õ õ Ú ú Ü ü</charinsert>
</p>

<p class="specialbasic" id="Romaji" style="display:none">
<charinsert>Ā ā Ē ē Ī ī Ō ō Ū ū</charinsert>
</p>

<p class="specialbasic" id="Romanian" style="display:none">
<charinsert>Ă ă Â â Î î Ş ş Ţ ţ</charinsert>
</p>

<p class="specialbasic" id="Scandinavian" style="display:none">
<charinsert>À à É é Å å Æ æ Ä ä Ø ø Ö ö</charinsert>
</p>

<p class="specialbasic" id="Serbian" style="display:none">
<charinsert>А а Б б В в Г г Д д Ђ ђ Е е Ж ж З з И и Ј ј К к Л л Љ љ М м Н н Њ њ О о П п Р р С с Т т Ћ ћ У у Ф ф Х х Ц ц Ч ч Џ џ Ш ш</charinsert>
</p>

<p class="specialbasic" id="Spanish" style="display:none">
<charinsert>Á á É é Í í Ñ ñ Ó ó Ú ú Ü ü ¡ ¿</charinsert>
</p>

<p class="specialbasic" id="Turkish" style="display:none">
<charinsert>Ç ç ğ İ ı Ş ş</charinsert>
</p>

<p class="specialbasic" id="Vietnamese" style="display:none">
<charinsert>À à Ả ả Á á Ạ ạ Ã ã Ă ă Ằ ằ Ẳ ẳ Ẵ ẵ Ắ ắ Ặ ặ Â â Ầ ầ Ẩ ẩ Ẫ ẫ Ấ ấ Ậ ậ Đ đ È è Ẻ ẻ Ẽ ẽ É é Ẹ ẹ Ê ê Ề ề Ể ể Ễ ễ Ế ế Ệ ệ Ỉ ỉ Ĩ ĩ Í í Ị ị Ì ì Ỏ ỏ Ó ó Ọ ọ Ò ò Õ õ Ô ô Ồ ồ Ổ ổ Ỗ ỗ Ố ố Ộ ộ Ơ ơ Ờ ờ Ở ở Ỡ ỡ Ớ ớ Ợ ợ Ù ù Ủ ủ Ũ ũ Ú ú Ụ ụ Ư ư Ừ ừ Ử ử Ữ ữ Ứ ứ Ự ự Ỳ ỳ Ỷ ỷ Ỹ ỹ Ỵ ỵ Ý ý</charinsert>
</p>

<p class="specialbasic" id="Welsh" style="display:none">
<charinsert>Á á À à Â â Ä ä É é È è Ê ê Ë ë Ì ì Î î Ï ï Ó ó Ò ò Ô ô Ö ö Ù ù Û û Ẁ ẁ Ŵ ŵ Ẅ ẅ Ý ý Ỳ ỳ Ŷ ŷ Ÿ ÿ</charinsert>
</p>

<p class="specialbasic" id="Yiddish" style="display:none">
<charinsert> א אַ אָ ב בֿ ג ד ה ו וּ װ ױ ז זש ח ט י יִ ײ ײַ כ ך כּ ל ל+ מ ם נ ן ס ע ע+ פ פּ פֿ ף צ ץ ק ר ש שׂ תּ ת ׳ ״ ־ </charinsert>
</p>
</div>',

# "Undo" feature
'undo-summary' => '[[Special:Contributions/$2|$2]]-pa $1 hukchasqanta kutichisqa ([[User talk:$2|rimay]])',

# Account creation failure
'cantcreateaccounttitle' => 'Manam atinichu rakiqunata kichayta',

# History pages
'revhistory'          => "Mawk'a llamk'apusqakuna",
'revnotfound'         => "Llamk'apusqaqa manam tarisqachu",
'revnotfoundtext'     => "Mañakusqayki llamk'apusqaqa manam tarisqachu.
Ama hina kaspa, kay p'anqap URL nisqa tiyayninta k'uskiriy.",
'currentrev'          => 'Kunan hukchasqa',
'revisionasof'        => "$1-pa llamk'apusqan",
'revision-info'       => "Kayqa p'anqap mawk'a llamk'apusqa kasqanmi, $1 p'unchawpi $2-pa rurasqan",
'previousrevision'    => '← ñawpaq hukchasqa',
'nextrevision'        => 'Qatiq hukchasqa →',
'currentrevisionlink' => 'Kunan hukchasqata qhaway',
'cur'                 => 'kunan',
'next'                => 'qat',
'last'                => 'ñawpaq',
'page_first'          => 'ñawpaqkuna',
'page_last'           => 'qhipaqkuna',
'histlegend'          => "Sut'ichana: (kunan) = p'anqap kunan kachkayninwan huk kaykuna,
(ñawpaq) = ñawpaq kachkasqanwan huk kaykuna, M = aslla hukchasqa",
'deletedrev'          => '[qullusqa]',
'histfirst'           => 'Ñawpaqkuna',
'histlast'            => 'Qhipaqkuna',

# Revision feed
'history-feed-title'       => 'Hukchasqakunap wiñay kawsaynin',
'history-feed-description' => "Kay p'anqata hukchasqakunap wiñay kawsaynin",

# Revision deletion
'rev-deleted-comment' => '(qullusqa rimapuy)',
'rev-deleted-user'    => '(qullusqa ruraqpa sutin)',
'rev-deleted-event'   => "(qullusqa hallch'a)",
'rev-delundel'        => 'rikuchiy/pakay',
'revisiondelete'      => "Mawk'a llamk'apusqakunata qulluy/paqarichiy",

# Diffs
'history-title'           => '"$1" p\'anqata hukchasqakunap wiñay kawsaynin',
'lineno'                  => "Siq'i $1:",
'editcurrent'             => "Kunan kachkaq p'anqata llamk'apuy",
'compareselectedversions' => "Pallasqa llamk'apusqakunata wakichay",
'editundo'                => 'kutichiy',

# Search results
'prevn'             => '$1 ñawpaq',
'nextn'             => '$1 qatiq',
'viewprevnext'      => 'Qhaway ($1) ($2) ($3).',
'showingresults'    => 'Qhipanpiqa rikuchkanki <b>$1</b>-kama tarisqakunatam, <b>$2</b> huchhawan qallarispa.',
'showingresultsnum' => 'Qhipanpiqa rikuchkanki <b>$3</b> tarisqam, <b>$2</b> huchhawan qallarispa.',
'powersearch'       => 'Maskay',

# Preferences page
'mypreferences'      => 'Allinkachinaykuna',
'changepassword'     => 'Yaykuna rimata hukchay',
'prefs-rc'           => 'Ñaqha hukchasqakuna',
'saveprefs'          => 'Allinkachinakunata waqaychay',
'retypenew'          => 'Musuq yaykuna rimaykita sinchichay:',
'textboxsize'        => "Llamk'apusqa",
'rows'               => "Siq'ikuna:",
'columns'            => 'Tunukuna:',
'resultsperpage'     => "Huk p'anqapi hayk'a tarinakuna:",
'recentchangesdays'  => "Ñaqha hukchasqakunapi rikuchina p'unchawkuna:",
'recentchangescount' => "Ñaqha hukchasqakunapi p'anqa sutikuna",
'savedprefs'         => "Allinkachinaykikunaqa hallch'asqañam.",
'timezonelegend'     => "Pacha t'urpi",
'guesstimezone'      => 'Pacha suyuta chaskimuy',
'allowemail'         => 'Huk ruraqkunamanta e-chaskita saqillay',
'default'            => 'kikinmanta',

# User rights
'editusergroup'  => 'Ruraqkunap huñunkunata hukchay',
'saveusergroups' => 'Ruraq huñukunata waqaychay',

# Groups
'group'               => 'Huñu:',
'group-autoconfirmed' => 'Rakiqunayuq ruraqkuna',
'group-sysop'         => 'Kamachiqkuna',
'group-all'           => '(tukuy)',

'group-autoconfirmed-member' => 'Rakiqunayuq ruraq',
'group-sysop-member'         => 'Kamachiq',

'grouppage-sysop' => '{{ns:project}}:Kamachiq',

# User rights log
'rightslog'      => 'Ruraqpa hayñinkunap hukyasqankuna',
'rightslogtext'  => "Kayqa hayñi hukchasqa hallch'aymi.",
'rightslogentry' => 'hukchan $1-pa hayñinkunata $2-manta $3-man',
'rightsnone'     => '(-)',

# Recent changes
'recentchanges'                  => 'Ñaqha hukchasqa',
'recentchangestext'              => "Kay p'anqapiqa aswan qhipaq ñaqha hukchasqakunam.",
'recentchanges-feed-description' => 'Kay mikhuchinapi wikipi qhipaq ñaqha hukchasqakunata qatiy.',
'rcnote'                         => "Kay qatiqpiqa qhipaq <b>$1</b> hukchasqakunam qhipaq <b>$2</b> p'unchawpi, musuqchasqa $3",
'rclistfrom'                     => '$1-manta musuq hukchasqakunata rikuchiy',
'rcshowhideminor'                => "$1 uchuylla llamk'apusqakunata",
'rcshowhidebots'                 => '$1 rurana antachakunata',
'rcshowhideliu'                  => "$1 hallch'asqa ruraqkunata",
'rcshowhideanons'                => '$1 IP-niyuq ruraqkunata',
'rcshowhidepatr'                 => "$1 patrullachasqa llamk'apusqakunata",
'rcshowhidemine'                 => "$1 llamk'apusqaykunata",
'rclinks'                        => "Qhipaq $1 hukchasqata qhipaq $2 p'unchawmanta qhaway.<br />$3",
'hide'                           => 'pakay',
'show'                           => 'rikuchiy',

# Recent changes linked
'recentchangeslinked'          => "Hukchasqa t'inkimuq",
'recentchangeslinked-noresult' => "Nisqa mit'apiqa manam hukchasqa t'inkimuqkuna kanchu.",
'recentchangeslinked-summary'  => "Kay sapaq p'anqaqa t'inkisqa p'anqakunapi ñaqha hukchasqakunatam rikuchin. Watiqasqayki p'anqakunaqa '''yanasapa qillqasqam'''.",

# Upload
'upload'           => 'Willañiqita churkuy',
'reupload'         => 'Huk kutita churkuy',
'reuploaddesc'     => "Churkuna hunt'ana p'anqaman kutimuy.",
'badfilename'      => 'Rikch\'ap sutinqa "$1"-man hukchasqam.',
'fileexists-thumb' => "'''<center>Kachkaq rikch'a</center>'''",
'savefile'         => 'Willañiqita waqaychay',
'watchthisupload'  => "Kay p'anqata watiqay",

'license'           => 'Saqillay',
'license-nopreview' => '(Ama qhawarichunkuchu)',

# Image list
'imagelist'            => "Rikch'akuna",
'ilsubmit'             => 'Maskhay',
'byname'               => 'sutikama',
'bydate'               => "p'unchawkama",
'bysize'               => 'hatun kaykama',
'imagelinks'           => "Rikch'aman t'inkimuq",
'linkstoimage'         => "Kay rikch'amanqa qatiq p'anqakunam t'inkimun:",
'imagelist_user'       => 'Ruraq',
'imagelist_size'       => 'Hatun kay',
'imagelist_search_for' => "Rikch'ap sutinta maskay:",

# File deletion
'filedelete-legend'  => 'Willañiqita qulluy',
'filedelete-submit'  => 'Qulluy',
'filedelete-success' => "'''$1''' qullusqañam.",

# MIME search
'download' => 'chaqnamuy',

# Unwatched pages
'unwatchedpages' => "Mana watiqasqa p'anqakuna",

# List redirects
'listredirects' => 'Tukuy pusapuykuna',

# Unused templates
'unusedtemplates'    => "Mana llamk'achisqa plantillakuna",
'unusedtemplateswlh' => "huk t'inkikuna",

'disambiguationspage'  => 'Template:Disambig',
'disambiguations-text' => "Kay qatiq p'anqakunam t'inkimun sut'ichana qillqaman. Chiqap, hukchanasqa p'anqaman t'inkichunman.<br />Tukuy [[:Plantilla:Disambig]] plantillayuq p'anqakunaqa sut'ichana qillqam.",

'doubleredirects' => 'Iskaylla pusapunakuna',

'brokenredirects'        => 'Panta pusapunakuna',
'brokenredirectstext'    => "Kay pusapuna p'anqakunaqa mana kachkaq p'anqamanmi pusapuchkan.",
'brokenredirects-edit'   => "(llamk'apuy)",
'brokenredirects-delete' => '(qulluy)',

'withoutinterwiki' => "Interwiki t'inkinnaq p'anqakuna",

# Miscellaneous special pages
'nlinks'                  => "$1 {{PLURAL:$1|t'inki|t'inkikuna}}",
'nmembers'                => '$1 {{PLURAL:$1|qillqa|qillqakuna}}',
'specialpage-empty'       => "Kay p'anqaqa ch'usaqmi.",
'lonelypages'             => "Wakcha p'anqakuna",
'uncategorizedpages'      => "Katiguriyannaq p'anqakuna",
'uncategorizedcategories' => 'Katiguriyannaq katiguriyakuna',
'uncategorizedimages'     => "Katiguriyannaq rikch'akuna",
'uncategorizedtemplates'  => 'Katiguriyannaq plantillakuna',
'unusedcategories'        => "Mana llamk'achisqa katiguriyakuna",
'unusedimages'            => "Mana llamk'achisqa rikch'akuna",
'mostlinked'              => "Lliwmanta aswan t'inkimuqniyuq qillqakuna",
'mostlinkedcategories'    => "Lliwmanta aswan t'inkimuqniyuq katiguriyakuna",
'mostlinkedtemplates'     => "Lliwmanta aswan t'inkimuqniyuq plantillakuna",
'mostcategories'          => "Lliwmanta aswan katiguriyayuq p'anqakuna",
'mostimages'              => "Lliwmanta astawan llamk'achisqa rikch'akuna",
'mostrevisions'           => 'Lliwmanta aswan hukchasqayuq qillqakuna',
'allpages'                => "Tukuy p'anqakuna",
'randompage'              => "Mayninpi p'anqa",
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
'newpages'                => "Musuq p'anqakuna",
'ancientpages'            => "Ñawpaqta qallarisqa p'anqakuna",
'intl'                    => "Interwiki t'inkikuna",
'move'                    => 'Astay',
'movethispage'            => "Kay p'anqata astay",

# Book sources
'booksources'               => 'Liwrukunapi pukyukuna',
'booksources-search-legend' => 'Liwrukunapi pukyukunata maskay',
'booksources-go'            => 'Riy',

'data'       => 'Willakuna',
'userrights' => 'Ruraqkunata saqillanap allinkachinan',
'groups'     => 'Ruraq huñukuna',

# Special:Log
'specialloguserlabel'  => 'Ruraq:',
'speciallogtitlelabel' => 'Sutichay:',
'log'                  => "Hallch'asqakuna",
'all-logs-page'        => "Tukuy hallch'akuna",
'log-search-legend'    => "Hallch'asqakunata maskay",
'log-search-submit'    => 'Riy',
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
'allpages-bad-ns'   => '{{SITENAME}} tiyaypiqa "$1" suti k\'iti manam kanchu.',

# Special:Listusers
'listusersfrom'      => 'Kaywan qallariq ruraqkunata rikuchiy:',
'listusers-submit'   => 'Rikuchiy',
'listusers-noresult' => 'Ruraqqa manam tarisqachu.',

# E-mail user
'emailuser'       => 'Kay ruraqman e-chaskita kachay',
'emailpage'       => 'E-chaski kay ruraqman:',
'emailpagetext'   => "Kay ruraq e-chaski imamaytanta allinkachinankunapi qillqakamachiptinqa, kay simihunt'anatam llamk'achiyta atinki e-chaskita kachanaykipaq.
Qampa qillqakamachisqayki imamaytaqa paqarinqa kachasqayki e-chaskipi chaskiqpa kutichisunaykita atinanpaq.",
'defemailsubject' => "{{SITENAME}} p'anqamanta chaski",
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
'watchlist'        => "Watiqasqa p'anqakuna",
'mywatchlist'      => 'Watiqasqaykuna',
'nowatchlist'      => 'Manam watiqasqakunachu kachkan.',
'addedwatch'       => 'Watiqasqaykunaman yapasqa',
'addedwatchtext'   => "Kunanqa «[[:\$1]]» sutiyuq p'anqa [[Special:Watchlist|watiqanykipim]] kachkañam. Chay p'anqapi rimachinanpipas hukchanakunaqa kay watiqana p'anqapim rikunki. Watiqasqayki p'anqaqa [[Special:Recentchanges|ñaqha hukchasqakunapi]] '''yanasapa''' qillqasqa rikuchisqa kanqa aswan sikllalla tarinaykipaq. <p>Manaña watiqayta munaptiykiqa, uma siq'ipi \"amaña watiqaychu\" ñit'iy.",
'removedwatch'     => 'Watiqasqakunamanta qullusqa',
'removedwatchtext' => '"[[:$1]]" sutiyuq p\'anqaqa watiqasqakunamanta qullusqam.',
'watch'            => 'Watiqay',
'watchthispage'    => "Kay p'anqata watiqay",
'unwatch'          => 'Amaña watiqaychu',
'unwatchthispage'  => 'Amaña watiqaychu',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Watiqasqakunaman yapaspa...',
'unwatching' => 'Watiqasqakunamanta qulluspa...',

'enotif_newpagetext' => "Musuq p'anqam.",
'changed'            => 'hukchasqa',
'created'            => 'kamarirqan',
'enotif_anon_editor' => 'sutinnaq ruraq $1',

# Delete/protect/revert
'deletepage'                  => "Kay p'anqata qulluy",
'confirm'                     => 'Sinchichay',
'excontent'                   => "Samiqnin karqan kay hinam: '$1'",
'excontentauthor'             => "Samiqnin karqan kay hinam: '$1' (huklla ruraqnin: '$2')",
'exbeforeblank'               => "manaraq qullusqa kaptin, samiqnin kay hinam karqan: '$1'",
'confirmdelete'               => 'Qullunata sinchichay',
'deletesub'                   => '(Qulluspa "$1")',
'historywarning'              => "Paqtataq: Kay qulluna p'anqaqa wiñay kawsasqayuqmi:",
'confirmdeletetext'           => "Qullunayachkanki willañiqintinmanta p'anqatam icha rikch'atam, wiñay kawsasqantapas.
Ama hina kaspa, sinchichallay munayniykita, qatiqninkunata riqsiyniykita, [[{{MediaWiki:policy-url}}]] nisqakama rurayniykitapas.",
'actioncomplete'              => 'Rurasqañam',
'deletedtext'                 => '"$1" qullusqañam.
$2 nisqa p\'anqata qhaway ñaqha qullusqakunata rikunaykipaq.',
'deletedarticle'              => 'qullusqa "$1"',
'dellogpage'                  => 'Qullusqakuna',
'deletionlog'                 => 'qullusqakuna',
'reverted'                    => 'Ñawpaq hukchasqata kutichiy',
'deletecomment'               => 'Imarayku qullusqa',
'rollback'                    => 'Hukchasqakunata kutichiy',
'rollback_short'              => 'Kutichiy',
'rollbacklink'                => 'Kutichiy',
'rollbackfailed'              => 'Manam kutichiyta atinchu',
'revertpage'                  => '[[Special:Contributions/$2|$2]] ([[User talk:$2|rimachina]]) sutiyuq ruraqpa hukchasqankunaqa kutichisqam [[User:$1|$1]]-pa ñawpaq hukchasqanman',
'rollback-success'            => "$1-pa hukchasqankunaqa kutichisqañam $2-pa ñawpaq llamk'apusqanta paqarichispa.",
'protectlogpage'              => "P'anqa amachasqakuna",
'protectedarticle'            => 'amachan [[$1]]-ta',
'unprotectedarticle'          => 'paskan amachasqa [[$1]]-ta',
'protectcomment'              => 'Imarayku amachasqa',
'protectexpiry'               => 'Amachaypa puchukaynin',
'unprotectsub'                => '(Amachasqa "$1"-ta paskaspa)',
'protect-cascadeon'           => "Kay p'anqaqa amachasqam kachkan, kay phaqchalla amachasqa {{PLURAL:$1|p'anqapi|p'anqakunapi}} ch'aqtasqa kaspanmi. Kay p'anqap amachay hanan kayninta hukchaytam atinki, chaywantaq manam p'anqap amachasqa kaynintachu hukchanki.",
'protect-level-autoconfirmed' => "Sutinnaq ruraqkunata hark'ay",
'protect-level-sysop'         => 'Kamachiqkunallapaq',
'protect-summary-cascade'     => "''phaqcha'' nisqapi",
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
'undelete'               => "Qullusqa p'anqata paqarichiy",
'undeletepage'           => "Qullusqa p'anqakunata qhawaspa paqarichiy",
'viewdeletedpage'        => "Qullusqa p'anqakunata qhaway",
'undeletebtn'            => 'Paqarichiy!',
'undeletereset'          => 'Mana imapas',
'undeletecomment'        => 'Imarayku paqarichisqa:',
'undeletedarticle'       => 'qullurqasqa "$1" paqarisqa',
'undelete-search-box'    => "Qullusqa p'anqakunata maskay",
'undelete-search-prefix' => "Rikuchiy kaywan qallariq p'anqakunata:",
'undelete-search-submit' => 'Maskay',

# Namespace form on various pages
'namespace' => "Suti k'iti:",
'invert'    => "Pallasqantinta t'ikrachiy",

# Contributions
'contributions' => "Ruraqpa llamk'apusqankuna",
'mycontris'     => "Llamk'apusqaykuna",
'nocontribs'    => 'Manam kay hina hukchasqakuna kanchu.',
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

# What links here
'whatlinkshere'       => "Kayman t'inkimuq",
'whatlinkshere-title' => "$1 sutiyuq p'anqaman t'inkimuqkuna",
'linklistsub'         => "(T'inkikuna)",
'linkshere'           => "'''[[:$1]]''' sutiyuq p'anqamanqa kay qatiq p'anqakunam t'inkimun:",
'nolinkshere'         => "Manam kachkanchu '''[[:$1]]'''-man t'inkiq p'anqa:",
'nolinkshere-ns'      => "Manam kachkanchu '''[[:$1]]'''-man t'inkiq p'anqa pallasqay suti k'itipi.",
'isredirect'          => "pusapusqa p'anqa",
'istemplate'          => "ch'aqtasqa",
'whatlinkshere-prev'  => '{{PLURAL:$1|ñawpaq|$1 ñawpaq}}',
'whatlinkshere-next'  => '{{PLURAL:$1|qatiq|$1 qatiq}}',
'whatlinkshere-links' => "← t'inkikuna",

# Block/unblock
'blockip'                => "IP-niyuq ruraqta hark'ay",
'blockiptext'            => "Kay qatiq hunt'ana p'anqata llamk'achiy huk IP-niyuqmanta icha ruraqpa sutinmanta llamk'apuyta hark'anapaq.
Chayqa [[Wikipidiya:Wandalismu|wandalismu]]ta hark'anapaq chaylla, [[Wikipidiya:Kawpay|{{SITENAME}}p kawpaykunallakamam]].
Hark'asqaykip hamuntapas sut'ichay (ahinataq, sapaq p'anqapi wandaluchaspa hukchasqakunamanta willaspa).",
'ipaddress'              => 'IP huchha',
'ipadressorusername'     => 'IP huchha icha ruraqpa sutin',
'ipbexpiry'              => "Hark'ay kaykama:",
'ipbreason'              => 'Hamu',
'ipbreasonotherlist'     => 'Huk hamu',
'ipbsubmit'              => "Kay tiyayta hark'ay",
'ipbotheroption'         => 'huk',
'ipbotherreason'         => 'Huk imarayku:',
'ipbhidename'            => "Ruraqta/IP-ta pakay hark'ay hallch'asqapi, kachkaq hark'asqakunapi ruraqkunapipas",
'blockipsuccesssub'      => "IP-niyuq ruraqqa hark'asqañam",
'blockipsuccesstext'     => 'IP "$1"-niyuqqa hark\'asqañam. <br />[[Special:Ipblocklist|Hark\'asqa IP-niyuqkuna]]ta qhaway hark\'akunata hukchanaykipaq.',
'ipb-edit-dropdown'      => "Hark'aypa hamunta llamk'apuy",
'ipb-unblock-addr'       => "Hark'asqa $1-ta qispichiy",
'ipb-unblock'            => "Hark'asqa ruraqta icha IP-niyuqta qispichiy",
'ipb-blocklist-addr'     => "Kachkaq hark'asqakunata qhaway $1-paq",
'ipb-blocklist'          => "Kachkaq hark'asqakunata qhaway",
'unblockip'              => "Hark'asqa IP-niyuq ruraqta qispichiy",
'ipusubmit'              => "Kay hark'asqa tiyayta qispichiy",
'unblocked'              => "Hark'asqa [[User:$1|$1]] qispisqañam",
'unblocked-id'           => "Hark'asqa $1-qa qispisqañam",
'ipblocklist'            => "Hark'asqa IP-niyuqkuna",
'ipblocklist-legend'     => "Hark'asqa ruraqta tariy",
'ipblocklist-username'   => 'Ruraqpa sutin icha IP huchha:',
'ipblocklist-submit'     => 'Maskay',
'blocklistline'          => "$1, $2 hark'an $3 ($4)-tam",
'anononlyblock'          => 'sutinnaqlla.',
'noautoblockblock'       => "Kikinmanta hark'ayqa ama kachunchu",
'createaccountblock'     => "Rakiqunata kichariyqa hark'asqam.",
'emailblock'             => "hark'asqa e-chaski",
'ipblocklist-empty'      => "Mana pipas hark'asqachu kachkan.",
'ipblocklist-no-results' => "Kay ruraqqa/IP-niyuqqa manam hark'asqachu kachkan.",
'blocklink'              => "hark'ay",
'unblocklink'            => "qispichiy hark'asqa",
'contribslink'           => "llamk'apusqakuna",
'blocklogpage'           => "Ruraq hark'asqakuna",
'unblocklogentry'        => 'paskan "$1"-ta hark\'asqa kaymanta',
'ipb_already_blocked'    => '"$1" sutiyuqqa hark\'asqañam kachkan.',
'ip_range_invalid'       => "IP huchha k'itiqa manam chanichkanchu.",
'proxyblocksuccess'      => 'Rurasqañam.',

# Developer tools
'lockdb'           => "Willañiqintinta hark'ay",
'lockbtn'          => "Willañiqintinta hark'ay",
'lockdbsuccesssub' => "Willañiqintinqa hark'asqañam",

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
'movepage-moved'          => "<big>'''«$1» kaymanñam astasqa: «$2»'''</big>", # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
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

# Namespace 8 related
'allmessages'               => 'MediaWiki-p tukuy willayninkuna',
'allmessagesname'           => 'Suti',
'allmessagesdefault'        => 'Ñawpaq qillqa',
'allmessagescurrent'        => 'Kunan kachkaq qillqa',
'allmessagestext'           => "Kayqa MediaWiki suti k'itipi tukuy llamk'achinalla willaykunam:",
'allmessagesnotsupportedDB' => "Special:AllMessages manam llamk'achinallachu, wgUseDatabaseMessages nisqaman ama nisqa kaptinmi.",
'allmessagesfilter'         => "Willaypa sutinkama ch'illchiy:",
'allmessagesmodified'       => 'Hukchasqallata rikuchiy',

# Thumbnails
'missingimage' => "<b>Manam rikch'a kachkanchu</b><br /><i>$1</i>",

# Tooltip help for the actions
'tooltip-pt-preferences'          => 'Allinkachinaykuna',
'tooltip-pt-mycontris'            => "Llamk'apusqaykuna",
'tooltip-ca-protect'              => "Kay p'anqata amachay",
'tooltip-ca-delete'               => "Kay p'anqata qulluy",
'tooltip-ca-watch'                => "Kay p'anqata watiqay",
'tooltip-ca-unwatch'              => "Amaña watiqaychu kay p'anqata",
'tooltip-t-whatlinkshere'         => "Kay p'anqaman tukuy t'inkimuqkuna",
'tooltip-t-recentchangeslinked'   => "Kay p'anqaman t'inkimuqkunapi ñaqha hukchasqakuna",
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
'tooltip-compareselectedversions' => "P'anqap iskay pallasqa hukchasqanpura hukchasqa kayta qhaway.",
'tooltip-watch'                   => "Kay p'anqata watiqay",

# Attribution
'anonymous'        => 'Wikipidiyap sutinnaq ruraqninkuna',
'siteuser'         => '{{SITENAME}}-pa $1 sutiyuq ruraqnin',
'lastmodifiedatby' => "Kay p'anqaqa $2, $1 qhipaq kutitam $3-pa hukchasqan karqan.", # $1 date, $2 time, $3 user
'and'              => '-wan',
'others'           => 'hukkuna',
'siteusers'        => '{{SITENAME}}-pa $1 sutiyuq ruraqnin(kuna)',

# Spam protection
'spamprotectiontitle' => "Spam nisqamanta amachanapaq ch'illchina",
'spambot_username'    => 'MediaWiki-ta spam nisqamanta pichay',

# Math options
'mw_math_png'    => "Hayk'appas PNG-ta ruray",
'mw_math_simple' => 'Ancha sikllalla kaptinqa HTML, mana hinaptinqa PNG',
'mw_math_html'   => 'Paqtanayaptinqa HTML, mana hinaptinqa PNG',
'mw_math_source' => "TeX hinatam saqiy (qillqa wamp'unapaq)",

# Browsing diffs
'previousdiff' => '← ñawpaq hukchasqa kaykuna',
'nextdiff'     => 'Qatiq hukchasqa kaykunaman riy →',

# EXIF tags
'exif-imagewidth'             => 'Suni kay',
'exif-imagelength'            => 'Hanaq kay',
'exif-imagedescription'       => "Rikch'ap sut'ichaynin",
'exif-compressedbitsperpixel' => "Rikch'ap ñit'isqa kaynin laya",
'exif-pixelydimension'        => "Chaniyuq rikch'ap suni kaynin",
'exif-pixelxdimension'        => "Chaniyuq rikch'ap hanaq kaynin",
'exif-imageuniqueid'          => "Rikch'ap ch'ulla ID-nin",

# EXIF attributes
'exif-compression-1' => "Mana ñit'isqa",

'exif-componentsconfiguration-0' => 'manam kachkanchu',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'tukuy',
'imagelistall'     => 'tukuy',
'watchlistall2'    => 'lliw',
'namespacesall'    => 'tukuy',
'monthsall'        => '(tukuy)',

# E-mail address confirmation
'confirmemail'           => 'E-chaski imamaytaykita sinchichay',
'confirmemail_needlogin' => '$1-llawanmi e-chaski imamaytaykita sinchichayta atinki.',
'confirmemail_loggedin'  => 'E-chaski imamaytaykiqa sinchichasqañam.',

# Scary transclusion
'scarytranscludedisabled' => "[Interwiki ch'aqtayman ama nisqa]",
'scarytranscludefailed'   => '[$1-paq plantillataqa manam chaskiyta atinchu; achachallaw]',
'scarytranscludetoolong'  => '[URL tiyayqa nisyu hatunmi; achachaw]',

# Trackbacks
'trackbackremove' => ' ([$1 Qulluy])',

# Delete conflict
'recreate' => 'Musuqta paqarichiy',

# action=purge
'confirm_purge'        => "Kay p'anqap ''cache'' nisqa paki hallch'an ch'usaqchasqa kachunchu?

$1",
'confirm_purge_button' => 'Arí niy',

# AJAX search
'searchcontaining' => "''$1'' nisqa samiqniyuq p'anqakunata maskay.",
'hideresults'      => 'Lluqsiykunata pakay',

# Multipage image navigation
'imgmultipageprev' => "← ñawpaq p'anqa",
'imgmultipagenext' => "qatiq p'anqa →",
'imgmultigo'       => 'Riy!',
'imgmultigotopre'  => "Riy p'anqaman",

# Table pager
'table_pager_next'         => "Qatiq p'anqa",
'table_pager_prev'         => "ñawpaq p'anqa",
'table_pager_limit_submit' => 'Riy',

# Auto-summaries
'autosumm-blank'   => "P'anqata tukuy samiqninmanta ch'usaqchasqa",
'autosumm-replace' => "P'anqap tukuy samiqnin '$1'-wan huknachasqa",
'autoredircomment' => '[[$1]]-man pusapusqa',
'autosumm-new'     => "Musuq p'anqa: $1",

);
