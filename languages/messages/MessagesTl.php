<?php
/** Tagalog (Tagalog)
 *
 * @ingroup Language
 * @file
 *
 * @author AnakngAraw
 * @author Felipe Aira
 * @author Sky Harbor
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'Midya',
	NS_SPECIAL          => 'Natatangi',
	NS_TALK             => 'Usapan',
	NS_USER             => 'Tagagamit',
	NS_USER_TALK        => 'Usapang tagagamit',
	NS_PROJECT_TALK     => 'Usapang $1',
	NS_FILE             => 'Larawan',
	NS_FILE_TALK        => 'Usapang larawan',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Usapang MediaWiki',
	NS_TEMPLATE         => 'Suleras',
	NS_TEMPLATE_TALK    => 'Usapang suleras',
	NS_HELP             => 'Tulong',
	NS_HELP_TALK        => 'Usapang tulong',
	NS_CATEGORY         => 'Kaurian',
	NS_CATEGORY_TALK    => 'Usapang kaurian',
);

$namespaceAliases = array(
	'Kategorya'         => NS_CATEGORY,
	'Usapang kategorya' => NS_CATEGORY_TALK,
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Salungguhitan ang mga kawing:',
'tog-highlightbroken'         => 'Ayusin ang mga sirang kawing <a href="" class="new">nang ganito</a> (alternatibo: nang ganito<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Pantayin ang mga talata',
'tog-hideminor'               => 'Itago ang mga maliliit na pagbabago sa mga huling binago',
'tog-extendwatchlist'         => 'Palawigin ang bantayan upang mapakita ang lahat na magagawang pagbabago.',
'tog-usenewrc'                => 'Pinadagdagang huling binago (JavaScript)',
'tog-numberheadings'          => 'Automatikong bilangin ang mga pamagat',
'tog-showtoolbar'             => "Ipakita ang ''toolbar'' ng pagbabago (JavaScript)",
'tog-editondblclick'          => 'Magbago ng mga pahina sa dalawahang pagpindot (JavaScript)',
'tog-editsection'             => 'Payagan ang mga pagbabagong panseksyon sa mga [baguhin] na kawing',
'tog-editsectiononrightclick' => 'Payagan ang mga pagbabagong panseksyon sa pakanang pagpindot ng mga panseksyong pamagat (JavaScript)',
'tog-showtoc'                 => 'Ipakita ang talaan ng mga nilalaman (sa mga pahinang may higit sa 3 punong pamagat)',
'tog-rememberpassword'        => 'Tandaan ang paglagda ko sa kompyuter na ito',
'tog-editwidth'               => 'May buong kalaparan ang kahon ng pagbabago',
'tog-watchcreations'          => 'Idagdag ang mga pahinang ginawa ko sa aking bantayan',
'tog-watchdefault'            => 'Idagdag ang mga pahinang binago ko sa aking bantayan',
'tog-watchmoves'              => 'Idagdag ang mga pahinang inilipat ko sa aking bantayan',
'tog-watchdeletion'           => 'Idagdag mga pahinang binura ko sa aking bantayan',
'tog-minordefault'            => 'Markahan ang lahat ng pagbabago bilang maliit nang nakatakda',
'tog-previewontop'            => 'Ipakita ang pribyu bago ang kahon ng pagbabago',
'tog-previewonfirst'          => 'Ipakita ang pribyu sa unang pagbabago',
'tog-nocache'                 => 'Salantain ang pagbaon ng pahina',
'tog-enotifwatchlistpages'    => 'Mag-e-liham sa akin kapag binago ang isa sa mga pahinang binabantayan ko',
'tog-enotifusertalkpages'     => 'Mag-e-liham sa akin kapag binago ang aking pahinang usapan',
'tog-enotifminoredits'        => 'Mag-e-liham din sa akin para sa mga maliliit na pagbabago ng mga pahina',
'tog-enotifrevealaddr'        => 'Ipakita ang direksyong e-liham ko sa mga liham ng pagpapahayag',
'tog-shownumberswatching'     => 'Ipakita ang bilang ng mga nagbabantay na manggagamit',
'tog-fancysig'                => 'Hilaw na lagda (walang automatikong pagkawing)',
'tog-externaleditor'          => 'Gumamit ng nakatakdang panlabas na pambago (para sa mga dalubhasa lamang, kailangan ng natatanging mga pagtatakda sa iyong kompyuter)',
'tog-externaldiff'            => 'Gumamit ng nakatakdang ibang panlabas (para sa mga dalubhasa lamang, kailangan ng natatanging pagtatakda sa iyong kompyuter)',
'tog-showjumplinks'           => 'Payagan ang mga "tumalon sa" na kawing pampagamit',
'tog-uselivepreview'          => 'Gamitin ang buhay na pribyu (JavaScript) (Eksperimental)',
'tog-forceeditsummary'        => 'Pagsabihan ako kapag nagpapasok ng walang-lamang buod ng pagbabago',
'tog-watchlisthideown'        => 'Itago ang aking mga pagbabago mula sa bantayan',
'tog-watchlisthidebots'       => 'Itago ang mga pagbabago ng mga bot mula sa bantayan',
'tog-watchlisthideminor'      => 'Itago ang mga maliliit na pagbabago mula sa bantayan',
'tog-watchlisthideliu'        => 'Itago ang mga pagbabago ng mga lumagdang tagagamit mula sa bantayan',
'tog-watchlisthideanons'      => "Itago ang mga pagbabago ng 'di-kilalang mga tagagamit mula sa bantayan",
'tog-nolangconversion'        => 'Huwag paganahin ang pagpapalit ng mga halagang nagkakaibaiba (baryante)',
'tog-ccmeonemails'            => 'Padalahan ako ng mga kopya ng mga ipinadala kong e-liham sa ibang mga manggagamit',
'tog-diffonly'                => 'Huwag ipakita ang nilalaman ng pahinang nasa ilalim ng mga pagkakaiba',
'tog-showhiddencats'          => 'Ipakita ang mga nakatagong kategorya (kaurian)',
'tog-noconvertlink'           => 'Huwag paganahin ang pagpapalit ng pamagat na pangkawing',
'tog-norollbackdiff'          => "Alisin ang mga pagkakaiba pagkatapos isagawa ang ''ibalik-sa-dati''",

'underline-always'  => 'Palagi',
'underline-never'   => 'Hindi magpakailanman',
'underline-default' => 'Tinakda ng pambasa-basa',

# Dates
'sunday'        => 'Linggo',
'monday'        => 'Lunes',
'tuesday'       => 'Martes',
'wednesday'     => 'Miyerkules',
'thursday'      => 'Huwebes',
'friday'        => 'Biyernes',
'saturday'      => 'Sabado',
'sun'           => 'Lin',
'mon'           => 'Lun',
'tue'           => 'Mar',
'wed'           => 'Miy',
'thu'           => 'Huw',
'fri'           => 'Biy',
'sat'           => 'Sab',
'january'       => 'Enero',
'february'      => 'Pebrero',
'march'         => 'Marso',
'april'         => 'Abril',
'may_long'      => 'Mayo',
'june'          => 'Hunyo',
'july'          => 'Hulyo',
'august'        => 'Agosto',
'september'     => 'Setyembre',
'october'       => 'Oktubre',
'november'      => 'Nobyembre',
'december'      => 'Disyembre',
'january-gen'   => 'Enero',
'february-gen'  => 'Pebrero',
'march-gen'     => 'Marso',
'april-gen'     => 'Abril',
'may-gen'       => 'Mayo',
'june-gen'      => 'Hunyo',
'july-gen'      => 'Hulyo',
'august-gen'    => 'Agosto',
'september-gen' => 'Setyembre',
'october-gen'   => 'Oktubre',
'november-gen'  => 'Nobyembre',
'december-gen'  => 'Disyembre',
'jan'           => 'Ene',
'feb'           => 'Peb',
'mar'           => 'Mar',
'apr'           => 'Abr',
'may'           => 'May',
'jun'           => 'Hun',
'jul'           => 'Hul',
'aug'           => 'Ago',
'sep'           => 'Set',
'oct'           => 'Okt',
'nov'           => 'Nob',
'dec'           => 'Dis',

# Categories related messages
'pagecategories'              => '{{PLURAL:$1|Kaurian|Mga kaurian}}',
'category_header'             => 'Mga pahina sa kategoryang "$1"',
'subcategories'               => 'Mga subkategorya',
'category-media-header'       => 'Mga midya sa kategoryang "$1"',
'category-empty'              => "''Kasalukuyang walang artikulo o midya ang kategoryang ito.''",
'hidden-categories'           => '{{PLURAL:$1|Nakatagong kategorya|Mga nakatagong kategorya}}',
'hidden-category-category'    => 'Mga nakatagong kategorya', # Name of the category where hidden categories will be listed
'category-file-count-limited' => 'Ang sumusunod na {{PLURAL:$1|talaksan ay|$1 mga talaksan}} ay nasa kasalukuyang kategorya.',

'mainpagetext'      => "<big>'''Matagumpay na ininstala ang MediaWiki.'''</big>",
'mainpagedocfooter' => "Silipin ang [http://meta.wikimedia.org/wiki/Help:Contents User's Guide] para sa kaalaman sa paggamit ng wiking ''software''.

== Pagsisimula ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Configuration settings list]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]",

'about'          => 'Patungkol',
'article'        => 'Pahina ng nilalaman',
'newwindow'      => '(magbubukas sa bagong bintana)',
'cancel'         => 'Ikansela',
'qbfind'         => 'Hanapin',
'qbbrowse'       => 'Basa-basahin',
'qbedit'         => 'Baguhin',
'qbpageoptions'  => 'Itong pahina',
'qbpageinfo'     => 'Konteksto',
'qbmyoptions'    => 'Mga pahina ko',
'qbspecialpages' => 'Mga natatanging pahina',
'moredotdotdot'  => 'Damihan pa...',
'mypage'         => 'Pahina ko',
'mytalk'         => 'Usapan ko',
'anontalk'       => 'Usapan para sa IP na ito',
'navigation'     => 'Nabigasyon',

# Metadata in edit box
'metadata_help' => 'Iba-ibang datos:',

'errorpagetitle'    => 'Pagkakamali',
'returnto'          => 'Bumalik sa $1.',
'tagline'           => 'Mula sa {{SITENAME}}',
'help'              => 'Tulong',
'search'            => 'Paghahanap',
'searchbutton'      => 'Hanapin',
'go'                => 'Puntahan',
'searcharticle'     => 'Puntahan',
'history'           => 'Kasaysayan ng pahina',
'history_short'     => 'Kasaysayan',
'updatedmarker'     => 'dinagdagan mula noong huli kong pagdalaw',
'info_short'        => 'Kaalaman',
'printableversion'  => 'Bersyong maaaring ilimbag',
'permalink'         => 'Palagiang kawing',
'print'             => 'Ilimbag',
'edit'              => 'Baguhin',
'editthispage'      => 'Baguhin itong pahina',
'create-this-page'  => 'Likhain itong pahina',
'delete'            => 'Burahin',
'deletethispage'    => 'Burahin itong pahina',
'undelete_short'    => 'Baligtarin ang pagbura ng {{PLURAL:$1|isang pagbabago|$1 mga pagbabago}}',
'protect'           => 'Ipagsanggalang',
'protect_change'    => 'palitan ang pagsanggalang',
'protectthispage'   => 'Ipagsanggalang itong pahina',
'unprotect'         => 'huwag ipagsanggalang',
'unprotectthispage' => 'Huwag ipagsanggalang itong pahina',
'newpage'           => 'Bagong pahina',
'talkpage'          => 'Pag-usapan itong pahina',
'talkpagelinktext'  => 'Usapan',
'specialpage'       => 'Natatanging pahina',
'personaltools'     => 'Mga kagamitang pansarili',
'postcomment'       => 'Magbigay-komento',
'articlepage'       => 'Tingnan ang pahina ng nilalaman',
'talk'              => 'Usapan',
'toolbox'           => 'Mga kagamitan',
'userpage'          => 'Tingnan ang pahina ng manggagamit',
'projectpage'       => 'Tingnan ang pahina ng proyekto',
'imagepage'         => 'Tingnan ang pahina ng midya',
'mediawikipage'     => 'Tingnan ang pahina ng mensahe',
'templatepage'      => 'Tingnan ang pahina ng suleras',
'viewhelppage'      => 'Tingnan ang pahina ng tulong',
'categorypage'      => 'Tingnan ang pahina ng kategorya',
'viewtalkpage'      => 'Tingnan ang usapan',
'otherlanguages'    => 'Sa ibang wika',
'redirectedfrom'    => '(Ikinarga mula sa $1)',
'redirectpagesub'   => 'Pahina ng pagkarga',
'lastmodifiedat'    => 'Huling binago ang pahinang ito noong $2, $1.', # $1 date, $2 time
'viewcount'         => 'Namataan na pahinang ito nang {{PLURAL:$1|isang|$1}} ulit.',
'protectedpage'     => 'Pahinang nakasanggalang',
'jumpto'            => 'Tumalon sa:',
'jumptonavigation'  => 'nabigasyon',
'jumptosearch'      => 'Paghahanap',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Tungkol sa {{SITENAME}}',
'aboutpage'            => 'Project:Patungkol',
'bugreports'           => 'Mga ulat pampagkakamali',
'copyright'            => 'Maaring gamitin ang nilalaman sa ilalim ng $1.',
'copyrightpagename'    => 'Karapatang-ari sa {{SITENAME}}',
'copyrightpage'        => '{{ns:project}}:Karapatang-ari',
'currentevents'        => 'Kasalukuyang pangyayari',
'currentevents-url'    => 'Project:Kasalukuyang pangyayari',
'disclaimers'          => 'Mga pagtatanggi',
'disclaimerpage'       => 'Project:Pangkalahatang pagtatanggi',
'edithelp'             => 'Tulong sa pagbabago',
'edithelppage'         => 'Help:Pagbabago',
'helppage'             => 'Help:Nilalaman',
'mainpage'             => 'Unang Pahina',
'mainpage-description' => 'Unang Pahina',
'policy-url'           => 'Project:Patakaran',
'portal'               => 'Puntahan ng pamayanan',
'portal-url'           => 'Project:Puntahan ng pamayanan',
'privacy'              => 'Patakaran sa paglilihim',
'privacypage'          => 'Project:Patakaran sa paglilihim',

'badaccess'        => 'Kamalian sa pahintulot',
'badaccess-group0' => 'Hindi ka pinahintulutang isagawa hiniling mo.',
'badaccess-groups' => 'Nakatakda lamang sa mga manggamit ng isa sa mga pangkat $1 hinihiling mo.',

'versionrequired'     => 'Kinakailangan ang bersyong $1 ng MediaWiki',
'versionrequiredtext' => 'Kinakailangan ang bersyong $1 ng MediaWiki upang magamit ang pahinang ito. Tingnan ang [[Special:Version|pahina ng bersyon]].',

'ok'                      => 'Sige',
'retrievedfrom'           => 'Ikinuha mula sa "$1"',
'youhavenewmessages'      => 'Mayroon kang $1 ($2).',
'newmessageslink'         => 'mga bagong mensahe',
'newmessagesdifflink'     => 'huling pagbabago',
'youhavenewmessagesmulti' => 'Mayroon kang mga bagong mensahe sa $1',
'editsection'             => 'baguhin',
'editold'                 => 'baguhin',
'editsectionhint'         => 'Baguhin ang seksyon: $1',
'toc'                     => 'Mga nilalaman',
'showtoc'                 => 'ipakita',
'hidetoc'                 => 'itago',
'thisisdeleted'           => 'Tingnan o ibalik ang $1?',
'viewdeleted'             => 'Tingnan ang $1?',
'restorelink'             => '{{PLURAL:$1|isang|$1}} binurang pagbabago',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Artikulo',
'nstab-user'      => 'Pahina ng manggagamit',
'nstab-media'     => 'Pahina ng midya',
'nstab-special'   => 'Natatangi',
'nstab-project'   => 'Pahina ng proyekto',
'nstab-image'     => 'Talaksan',
'nstab-mediawiki' => 'Mensahe',
'nstab-template'  => 'Suleras',
'nstab-help'      => 'Pahina ng tulong',
'nstab-category'  => 'Kategorya',

# Main script and global functions
'nosuchaction'      => 'Walang ganoong gawa',
'nosuchactiontext'  => 'Hindi kinikilala ng wiki
ang gawang itinakda ng URL',
'nosuchspecialpage' => 'Walang ganoong natatanging pahina',
'nospecialpagetext' => "<big>'''Humiling ka ng isang natatanging pahina na walang saysay.'''</big>

Isang tala ng mga natatanging pahina na may saysay ay matatagpuan sa [[Special:SpecialPages|{{int:specialpages}}]].",

# General errors
'error'                => 'Kamalian',
'databaseerror'        => 'Kamalian sa kalipunan ng datos',
'dberrortext'          => 'Naganap isang pagkakamali sa usisang palaugnayan sa kalipunan ng datos.
Maaaring magpakita ito ng kakamaian sa \'\'software\'\'.
Ang huling sinubukang usisa sa kalipunan ng datos ay:
<blockquote><tt>$1</tt></blockquote>
mula sa gawaing "<tt>$2</tt>".
Nagbigay ng pagkakamaling "<tt>$3: $4</tt>" MySQL.',
'dberrortextcl'        => 'Naganap isang pagkakamali sa usisang palaugnayang sa kalipunan ng datos.
Ang huling sinubukang usisa sa kalipunan ng datos ay:
"$1"
mula sa gawaing "$2".
Nagbigay ng pagkakamaling "$3: $4" MySQL.',
'noconnect'            => 'Paumanhin! Dumaranas ang wiki ng kahirapang teknikal, at hindi makapag-ugnayan sa serbidor ng kalipunan ng datos. <br />
$1',
'nodb'                 => 'Hindi mapili kalipunan ng datos na $1',
'cachederror'          => 'Ang sumusunod ay isang ibinaong kopya ng hinihiling na pahina, at maaaring hindi ito bago.',
'laggedslavemode'      => 'Babala: Maaaring hindi maglaman ang pahina ng mga huling dagdag.',
'readonly'             => 'Nakakandado kalipunan ng datos',
'enterlockreason'      => 'Magbigay ng dahilan sa pagkandado, kasama na ang taya
kung kailan magtatapos ang kandado',
'readonlytext'         => 'Kasalukuyang nakakandado ang kalipunan ng datos sa mga bagong entrada at ibang mga pagbabago, marahil para sa kalakarang pagpapanatili ng kalipunan ng datos, kung saan pagkatapos babalik sa normal ito.

Nagbigay ng sumusunod na dahilan ang tagapangasiwang nangandado nito: $1',
'readonly_lag'         => 'Automatikong kinandado ang kalipunan ng datos habang humahabol ang mga aliping serbidor ng kalipunan ng datos sa pinuno',
'internalerror'        => 'Kamaliang panloob',
'internalerror_info'   => 'Kamaliang panloob: $1',
'filecopyerror'        => 'Hindi makopya ang talaksang "$1" sa "$2".',
'filerenameerror'      => 'Hindi mapalitan ang pangalan ng talaksang "$1" sa "$2".',
'filedeleteerror'      => 'Hindi mabura ang talaksang "$1".',
'directorycreateerror' => 'Hindi malikha ang direktoryong "$1".',
'filenotfound'         => 'Hindi mahanap ang talaksang "$1".',
'fileexistserror'      => 'Hindi makapagsulat sa talaksang "$1": umiiral ang talaksan',
'formerror'            => 'Kamalian: hindi maipadala ang pormularyo',
'badarticleerror'      => 'Hindi maisasagawa ang gawaing ito sa pahinang ito.',
'viewsource'           => 'Tingnan ang pinagmulan',
'viewsourcefor'        => 'para sa $1',
'actionthrottled'      => 'Hinadlangan ang gawain',
'protectedpagetext'    => 'Kinandado ang pahinang ito upang maihadlang ang pagbabago',
'viewsourcetext'       => 'Maaari mong tingnan at kopyahin ang pinagmulan ng pahinang ito:',
'namespaceprotected'   => "Wala kang pahintulot na magbago ng mga pahinang nasa ngalan-espasyong '''$1'''.",
'customcssjsprotected' => 'Wala kang pahintulot na baguhin ang pahinang ito, dahil naglalaman ito ng mga kagustuhang pansarili ng ibang manggagamit.',
'ns-specialprotected'  => 'Hindi pwedeng baguhin ang mga natatanging pahina.',

# Login and logout pages
'welcomecreation'           => '== Maligayang pagdating, $1! ==

Nilikha na ang iyong kuwenta. Huwag kalimutang baguhin ang iyong mga kagustuhan sa {{SITENAME}}.',
'loginpagetitle'            => 'Paglagda ng manggagamit',
'yourname'                  => 'Bansag:',
'yourpassword'              => 'Hudyat:',
'yourpasswordagain'         => 'Iyong hudyat muli:',
'remembermypassword'        => 'Tandaan ang hudyat sa kompyuter na ito',
'yourdomainname'            => 'Iyong dominyo:',
'login'                     => 'Lumagda',
'loginprompt'               => 'Dapat pinapahintulot ang mga kuki upang makapaglagda sa {{SITENAME}}.',
'userlogin'                 => 'Lumagda / lumikha ng kuwenta',
'logout'                    => 'Umalis sa paglagda',
'userlogout'                => 'Umalis sa paglagda',
'notloggedin'               => 'Hindi nakalagda',
'nologin'                   => 'Wala kang panlagda? $1.',
'nologinlink'               => 'Lumikha ng kuwenta',
'createaccount'             => 'Lumikha ng kuwenta',
'gotaccount'                => 'May kuwenta ka na? $1.',
'gotaccountlink'            => 'Lumagda',
'badretype'                 => 'Hindi magkatugma ang ipinasok mong mga hudyat.',
'youremail'                 => 'E-liham:',
'username'                  => 'Bansag:',
'uid'                       => 'Bilang ng manggagamit:',
'yourrealname'              => 'Tunay na pangalan:',
'yourlanguage'              => 'Wika:',
'yournick'                  => 'Palayaw:',
'badsiglength'              => 'Masyadong mahaba ang bansag; kailangan ito ay hindi hihigit sa $1 karakter.',
'email'                     => 'E-liham',
'loginerror'                => 'Kamalian sa paglagda',
'prefs-help-email-required' => 'Kinakailangan ang direksyong e-liham.',
'loginsuccesstitle'         => 'Matagumpay ang paglagda',
'loginsuccess'              => "'''Nakalagda ka na sa {{SITENAME}} bilang si \"\$1\".'''",
'nosuchusershort'           => 'Walang manggagamit na may pangalang "<nowiki>$1</nowiki>". Pakitingnan ang iyong pagbaybay.',
'passwordtooshort'          => 'Walang saysay o masyadong maigsi ang iyong hudyat. Dapat ito ay hindi bababa sa $1 karakter at ay magkaiba sa iyong bansag.',
'mailmypassword'            => 'I-e-liham ang bagong hudyat',
'passwordremindertitle'     => 'Bagong pansamantalang hudyat para sa {{SITENAME}}',
'mailerror'                 => 'Kamalian sa pagpapadala ng liham: $1',
'loginlanguagelabel'        => 'Wika: $1',

# Password reset dialog
'resetpass_success'       => 'Matagumpay na nabago ang iyong hudyat!  Inilalagda ka na ngayon...',
'resetpass_bad_temporary' => 'Hindi tanggap na pansamantalang hudyat.
Maaaring matagumpay mo nang nabago ang iyong hudyat o nakahiling na ng isang bagong pansamantalang hudyat.',

# Edit page toolbar
'bold_sample'   => 'Tekstong maitim',
'bold_tip'      => 'Tekstong maitim',
'italic_sample' => 'Tekstong italika',
'italic_tip'    => 'Tekstong italika',

# Edit pages
'summary'                => 'Buod',
'subject'                => 'Paksa/punong pamagat',
'minoredit'              => 'Ito ay isang munting pagbabago',
'watchthis'              => 'Bantayan itong pahina',
'savearticle'            => 'Itala ang pahina',
'preview'                => 'Pribyu',
'blockedtitle'           => 'Nakaharang ang tagagamit',
'blockedoriginalsource'  => "Ang pinagmulan ng '''$1''' ay pinapakita sa ibaba:",
'editing'                => 'Binabago ang $1',
'editingsection'         => 'Binabago ang $1 (bahagi)',
'editingcomment'         => 'Binabago ang $1 (komento)',
'editconflict'           => 'Alitan sa pagbabago: $1',
'templatesused'          => 'Mga suleras na ginagamit sa pahinang ito:',
'templatesusedsection'   => 'Mga suleras na ginagamit sa bahaging ito:',
'template-protected'     => '(nakasanggalang)',
'template-semiprotected' => '(bahagyang nakasanggalang)',

# Search results
'searchhelp-url' => 'Help:Nilalaman',

# Preferences page
'preferences'   => 'Mga kagustuhan',
'mypreferences' => 'Aking mga kagustuhan',
'skin-preview'  => 'Pribyu',

# Recent changes
'recentchanges'     => 'Mga huling binago',
'recentchangestext' => 'Subaybayan ang mga pinakahuling pagbabago sa wiki sa pahinang ito.',

# Recent changes linked
'recentchangeslinked'       => 'Mga kaugnay na binago',
'recentchangeslinked-title' => 'Mga pagbabagong magkaugnay sa "$1"',

# Upload
'upload'            => 'Magkarga ng talaksan',
'uploadbtn'         => 'Magkarga ng talaksan',
'reupload'          => 'Magkarga muli',
'reuploaddesc'      => 'Bumalik sa pormularyo ng pagkarga',
'uploadnologin'     => 'Hindi nakalagda',
'uploadnologintext' => 'Dapat ikaw ay [[Special:UserLogin|nakalagda]]
upang makapagkarga ng talaksan.',
'uploaderror'       => 'Kamalian sa pagkarga',
'watchthisupload'   => 'Bantayan itong pahina',

# File description page
'filehist'          => 'Kasaysayan ng talaksan',
'filehist-datetime' => 'Petsa/Oras',
'filehist-user'     => 'Tagagamit',
'filehist-filesize' => 'Laki ng talaksan',
'filehist-comment'  => 'Komento',
'nolinkstoimage'    => 'Walang pahing tumuturo sa talaksang ito.',

# File reversion
'filerevert-comment' => 'Komento:',

# File deletion
'filedelete'         => 'Burahin ang $1',
'filedelete-legend'  => 'Burahin ang talaksan',
'filedelete-intro'   => "Binubura mo ang '''[[Media:$1|$1]]'''.",
'filedelete-comment' => 'Dahilan sa pagkabura:',
'filedelete-submit'  => 'Burahin',
'filedelete-success' => "Binura na ang '''$1'''.",
'filedelete-nofile'  => "Hindi umiiral ang '''$1'''.",

# Random page
'randompage'         => 'Pahinang walang-pili',
'randompage-nopages' => 'Walang mga pahina sa ngalan-espasyong ito.',

# Random redirect
'randomredirect' => 'Pagkargang walang-pili',

# Statistics
'statistics'              => 'Mga estadistika',
'statistics-header-users' => 'Mga estadistika sa mga manggagamit',

'disambiguations' => 'Mga pahina ng paglilinaw',

'brokenredirects' => 'Mga sirang pangkarga',

# Miscellaneous special pages
'lonelypages'       => 'Mga inulilang pahina',
'shortpages'        => 'Mga maiikling pahina',
'longpages'         => 'Mga mahahabang pahina',
'newpages'          => 'Mga bagong pahina',
'newpages-username' => 'Bansag:',
'ancientpages'      => 'Mga pinakalumang pahina',
'move'              => 'Ilipat',
'movethispage'      => 'Ilipat itong pahina',

# Special:AllPages
'allpages'        => 'Lahat ng pahina',
'alphaindexline'  => '$1 hanggang $2',
'allpages-bad-ns' => 'Wala sa {{SITENAME}} ang ngalan-espasyong "$1".',

# Special:Categories
'categories'         => 'Mga kategorya',
'categoriespagetext' => 'Ang mga sumusunod na kategorya ay naglalaman ng mga pahina o midya.',

# E-mail user
'emailfrom'    => 'Mula',
'emailto'      => 'Kay',
'emailsubject' => 'Paksa',
'emailmessage' => 'Mensahe',
'emailsend'    => 'Ipadala',

# Watchlist
'watchlist'     => 'Bantayan Ko',
'watch'         => 'Bantayan',
'watchthispage' => 'Bantayan itong pahina',

# Delete
'deletepage'     => 'Burahin ang pahina',
'deletedarticle' => 'ibinura ang "[[$1]]"',
'deletecomment'  => 'Dahilan sa pagkabura:',

# Protect
'prot_1movedto2'              => 'Ang [[$1]] ay inilipat sa [[$2]]',
'protect-default'             => '(tinakda)',
'protect-level-autoconfirmed' => "Harangin ang mga 'di-rehistradong manggagamit",
'protect-summary-cascade'     => 'kaskada',
'protect-expiring'            => 'magwawalang-bisa sa $1 (UTC)',
'protect-cascade'             => 'Ipagsanggalang ang mga pahinang kasama sa pahinang ito (kaskadang pagsanggalang)',
'protect-expiry-options'      => '2 oras:2 hours,1 araw:1 day,3 araw:3 days,1 linggo:1 week,2 linggo:2 weeks,1 buwan:1 month,3 buwan:3 months,6 buwan:6 months,1 taon:1 year,walang hanggan:infinite', # display1:time1,display2:time2,...
'restriction-type'            => 'Pahintulot:',
'restriction-level'           => 'Antas ng kabawalan:',

# Restriction levels
'restriction-level-sysop'         => 'buong nakasanggalang',
'restriction-level-autoconfirmed' => 'bahagyang nakasanggalang',
'restriction-level-all'           => 'anumang antas',

# Undelete
'undelete-error-short' => 'Kamalian sa pagbaligtad ng pagbura ng talaksan: $1',
'undelete-error-long'  => 'Nagkaroon ng mga kamalian habang binabaligtad ang pagbura ng talaksan:

$1',

# Namespace form on various pages
'namespace'      => 'Ngalan-espasyo:',
'invert'         => 'Baligtarin and pinili',
'blanknamespace' => '(Pangunahin)',

# Contributions
'contributions' => 'Mga ambag ng manggagamit',
'mycontris'     => 'Aking mga ginawa',
'contribsub2'   => 'Para kay $1 ($2)',

'sp-contributions-newbies'     => 'Ipakita ang mga ambag ng mga bagong kuwenta lamang',
'sp-contributions-newbies-sub' => 'Para sa mga bagong kuwenta',
'sp-contributions-blocklog'    => 'Tala ng paglipat',

# What links here
'whatlinkshere'       => 'Mga nakaturo dito',
'whatlinkshere-title' => 'Mga pahinang kumakawing sa $1',
'whatlinkshere-page'  => 'Pahina:',

# Block/unblock
'blockip'            => 'Harangin ang manggagamit',
'ipaddress'          => 'Direksyong IP:',
'ipadressorusername' => 'Direksyong IP o bansag:',
'ipbexpiry'          => 'Pagkawalang-bisa:',
'ipbreason'          => 'Dahilan:',
'ipbreasonotherlist' => 'Ibang dahilan',
'ipbreason-dropdown' => '*Mga karaniwang dahilan sa paghaharang
** Pagpasok ng hindi totoong impormasyon
** Pag-alis ng nilalaman mula sa mga pahina
** Walang-itinatanging paglalagay ng mga kawing panlabas
** Pagpasok ng impormasyong walang kabuluhan/satsat sa mga pahina
** Ugaling nananakot/pagligalig
** Pagmamalabis ng maramihang kuwenta
** Hindi kanais-nais na bansag',
'ipbanononly'        => "Harangin ang mga 'di-kilalang manggagamit lamang",
'ipbcreateaccount'   => 'Hadlangan ang paglikha ng kuwenta',
'ipbemailban'        => 'Hadlangan ang manggagamit sa pagpapadala ng e-liham',
'ipbenableautoblock' => 'Automatikong harangin and huling direksyong IP na ginamit ng manggagamit na ito, at anumang sumusunod pang mga IP na masusubukan nilang bago mula roon',
'ipbsubmit'          => 'Harangin itong manggagamit',
'ipbother'           => 'Ibang oras:',
'ipboptions'         => '2 oras:2 hours,1 araw:1 day,3 araw:3 days,1 linggo:1 week,2 linggo:2 weeks,1 buwan:1 month,3 buwan:3 months,6 buwan:6 months,1 taon:1 year,walang hanggan:infinite', # display1:time1,display2:time2,...
'ipbotheroption'     => 'iba',
'ipbotherreason'     => 'Iba/karagdagang dahilan:',
'blockipsuccesssub'  => 'Matagumpay ang pagharang',

# Developer tools
'lockdb'   => 'Kandaduhan ang kalipunan ng datos',
'unlockdb' => 'Buksan ang kalipunan ng datos',

# Move page
'movearticle'             => 'Ilipat ang pahina:',
'movenologin'             => 'Hindi nakalagda',
'movenologintext'         => 'Kailangang ikaw ay isang naka-rehistrong manggagamit at ay [[Special:UserLogin|nakalagda]] upang makapaglipat ng pahina.',
'movenotallowed'          => 'Wala kang permisong maglipat ng pahina.',
'newtitle'                => 'Sa bagong pamagat:',
'move-watch'              => 'Bantayan itong pahina',
'movepagebtn'             => 'Ilipat ang pahina',
'pagemovedsub'            => 'Matagumpay ang paglipat',
'movepage-moved'          => '<big>\'\'\'Inilipat ang "$1" sa "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => 'May umiiral nang pahinang may ganitong pangalan, o ang
pangalang pinili mo ay hindi mabisa.
Pumili muli ng ibang pangalan.',
'cantmove-titleprotected' => 'Hindi mo malilipatan ang isang pahina sa lokasyong ito, dahil nakasanggalang sa paglikha ang baong pamagat',
'movedto'                 => 'inilipat sa',
'movetalk'                => 'Ilipat ang kaugnay na pahinang usapan',
'1movedto2'               => 'Ang [[$1]] ay inilipat sa [[$2]]',
'1movedto2_redir'         => 'Ang [[$1]] ay inilipat sa [[$2]] sa ibabaw ng pangkarga',
'movereason'              => 'Dahilan:',
'delete_and_move'         => 'Burahin at ilipat',
'delete_and_move_confirm' => 'Oo, burahin ang pahina',

# Export
'export'          => 'Magluwas ng pahina',
'exportcuronly'   => 'Isama lamang ang kasalukuyang rebisyon, hindi ang buong kasaysayan',
'export-submit'   => 'Magluwas',
'export-addcat'   => 'Magdagdag',
'export-download' => 'Itala bilang talaksan',

# Namespace 8 related
'allmessages'               => 'Mga mensaheng pansistema',
'allmessagesname'           => 'Pangalan',
'allmessagesdefault'        => 'Tinakdang teksto',
'allmessagescurrent'        => 'Kasalukuyang teksto',
'allmessagestext'           => 'Ito ay isang tala ng mga mensaheng pansistema na matatagpuan sa ngalan-espasyong MediaWiki.
Please visit [http://www.mediawiki.org/wiki/Localisation MediaWiki Localisation] and [http://translatewiki.net Betawiki] if you wish to contribute to the generic MediaWiki localisation.',
'allmessagesnotsupportedDB' => "Hindi magamit ang '''{{ns:special}}:Allmessages''' dahil nakapatay ang '''\$wgUseDatabaseMessages'''.",

# Thumbnails
'thumbnail-more' => 'Palakihin',
'filemissing'    => 'Nawawala ang talaksan',

# Special:Import
'import'                  => 'Mag-angkat ng pahina',
'import-interwiki-submit' => 'Mag-angkat',
'import-comment'          => 'Komento:',
'importstart'             => 'Inaangkat ang mga pahina...',
'importsuccess'           => 'Tapos na ang pag-angkat!',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Aking pahina ng manggagamit',
'tooltip-pt-mytalk'               => 'Aking pahinang usapan',
'tooltip-pt-preferences'          => 'Aking mga kagustuhan',
'tooltip-pt-mycontris'            => 'Tala ng aking mga ambag',
'tooltip-pt-logout'               => 'Umalis sa pagkalagda',
'tooltip-n-mainpage'              => 'Dalawin ang Unang Pahina',
'tooltip-n-portal'                => 'Hinggil sa proyekto, ano ang magagawa mo, saan matatagpuan ang mga bagay-bagay',
'tooltip-n-currentevents'         => 'Maghanap ng sanligang impormasyon hinggil sa mga kasalukuyang kaganapan',
'tooltip-n-recentchanges'         => 'Ang tala ng mga kamakailang pagbabago sa loob ng wiki.',
'tooltip-n-randompage'            => 'Magkarga ng anumang pahina',
'tooltip-n-help'                  => 'Pook kung saan ito matutuklasan.',
'tooltip-t-whatlinkshere'         => 'Tala ng lahat ng pahina ng mga wiking nakakawing dito',
'tooltip-t-recentchangeslinked'   => 'Kamakailang mga pagbabago na nakakawing mula sa pahinang ito',
'tooltip-feed-rss'                => 'Subo/Kargang RSS para sa pahinang ito',
'tooltip-feed-atom'               => 'Subo/kargang Atom para sa pahinang ito',
'tooltip-t-contributions'         => 'Tunghayan ang tala ng mga ambag ng tagagamit na ito',
'tooltip-t-emailuser'             => 'Magpadala ng isang e-liham sa tagagamit na ito',
'tooltip-t-upload'                => 'Magkarga ng mga talaksan',
'tooltip-t-specialpages'          => 'Tala ng lahat ng mga natatanging pahina',
'tooltip-t-print'                 => 'Nalilimbag na bersyon ng pahinang ito',
'tooltip-t-permalink'             => 'Permanenteng kawing sa bersyong ito ng pahina',
'tooltip-ca-nstab-main'           => 'Tingnan ang pahina ng nilalaman',
'tooltip-ca-nstab-user'           => 'Tingnan ang pahina ng tagagamit',
'tooltip-ca-nstab-media'          => 'Tingnan ang pahina ng midya',
'tooltip-ca-nstab-special'        => 'Isa itong natatanging pahina, hindi mo mababago ang mismong pahina',
'tooltip-ca-nstab-project'        => 'Tingnan ang pahina ng proyekto',
'tooltip-ca-nstab-image'          => 'Tingnan ang pahina ng talaksan',
'tooltip-ca-nstab-mediawiki'      => 'Tingnan ang mensahe ng sistema',
'tooltip-ca-nstab-template'       => 'Tingnan ang suleras',
'tooltip-ca-nstab-help'           => 'Tingnan ang pahina ng tulong',
'tooltip-ca-nstab-category'       => 'Tingnan ang pahina ng kaurian/kategorya',
'tooltip-minoredit'               => 'Tandaan ito bilang isang maliit na pagbabago',
'tooltip-save'                    => 'Sagipin ang iyong mga pagbabago',
'tooltip-preview'                 => 'Paunang-tingnan ang mga pagbabago mo, pakigamit muna ito bago sagipin o magtala!',
'tooltip-diff'                    => 'Ipakita ang mga pagbabagong ginawa mo sa teksto.',
'tooltip-compareselectedversions' => 'Tingnan ang pagkakaiba sa pagitan ng dalawang napiling bersyon ng pahinang ito.',
'tooltip-watch'                   => 'Idagdag ang pahinang ito sa iyong tala ng mga binabantayan',
'tooltip-recreate'                => 'Muling likhain ang pahina kahit na nabura na ito',
'tooltip-upload'                  => 'Simulan ang pagkarga',
'tooltip-rollback'                => 'Ibinabalik ng "Ibalik sa dati" ang (mga) pagbabago sa pahinang ito patungo sa huling bersyon ng huling tagapagambag sa pamamagitan ng isang pindot lamang.',
'tooltip-undo'                    => 'Ibinabalit ng "Ibalik" ang pagbabagong ito at binubuksan ang pahinang gawaan ng pagbabago sa anyong paunang-tingin muna.  Nagpapahintulot na makapagdagdag ng dahilan sa buod.',

# Stylesheets
'common.css'      => '/* Ang inilagay na CSS dito ay gagamitin para sa lahat ng mga pabalat */',
'standard.css'    => '/* Ang inilagay na CSS dito ay makakaapekto sa mga tagagamit ng Karaniwang pabalat */',
'nostalgia.css'   => '/* Ang CSS na inilagay dito ay makakaapekto sa mga tagagamit ng pabalat na Nostalgia */',
'cologneblue.css' => "/* Ang CSS na inilagay dito ay makakaapekto sa mga tagagamit ng pabalat na Bugkaw na Kolon (''Cologne Blue'') */",
'monobook.css'    => '/* Ang CSS na inilagay dito ay makakaapekto sa mga tagagamit ng pabalat na Monobook */',
'myskin.css'      => "/* Ang CSS na inilagay dito ay makakaapekto sa lahat ng mga tagagamit ng pabalat na Balatko (''Myskin'') */",
'chick.css'       => "/* Ang CSS na inilagay dito ay makakaapekto sa mga tagagamit ng pabalat na ''Chick'' */",
'simple.css'      => "/* Ang CSS na iniligay dito ay makakaapekto sa mga tagagamit ng Payak (''Simple'') na pabalat */",
'modern.css'      => "/* Ang CSS na iniligay dito ay makakaapekto sa tagagamit ng Makabagong (''Modern'') pabalat */",
'print.css'       => '/* Ang CSS na inilagay dito ay makakaapekto sa kalalabasan o resulta ng paglilimbag */',
'handheld.css'    => "/* Ang CSS na inilagay dito ay makakaapekto sa mga aparatong nahahawakan (''handheld device'') batay sa itinakdang pabalat sa ''\$wgHandheldStyle'' */",

# Scripts
'common.js'      => '/* Anumang JavaScript dito ay ikakarga para sa lahat ng mga tagagamit ng bawat pahinang ikinarga. */',
'standard.js'    => '/* Anumang JavaScript dito ay ikakarga para lahat ng mga tagagamit na gumagamit ng Karaniwang pabalat */',
'nostalgia.js'   => '/* Anumang JavaScript dito ay ikakarga para lahat ng mga tagagamit na gumagamit ng pabalat na Nostalgia */',
'cologneblue.js' => '/* Anumang JavaScript dito ay ikakarga para sa tagagamit ng pabalat na Bughaw na Kolon */',
'monobook.js'    => '/* Anumang JavaScript dito ay ikakarga para sa mga tagagamit na gumagamit ng pabalat na MonoBook */',
'myskin.js'      => '/* Anumang JavaScript dito ay ikakarga para sa tagagamit na gumagamit ng pabalat na Balatko */',
'chick.js'       => "/* Anumang JavaScript dito ay ikakarga para sa mga tagagamit na gumagamit ng pabalat na ''Chick'' */",
'simple.js'      => '/* Anumang JavaScript dito ay ikakarga para sa tagagamit na gumagamit ng Payak na pabalat */',
'modern.js'      => '/* Anumang JavaScript dito ay ikakarga para sa mga tagagamit na gumagamit ng Makabagong pabalat */',

# Metadata
'nodublincore'      => 'Hindi pinagana ang metadatang Dublin Core RDF para sa serbidor na ito.',
'nocreativecommons' => 'Hindi pinagana ang metadatang Creative Commons RDF para sa serbidor na ito.',
'notacceptable'     => 'Hindi makapagbigay ng dato ang serbidor ng wiki sa anyong mababasa ng iyong kliyente.',

# Attribution
'anonymous'        => 'Hindi kilalang {{PLURAL:$1|tagagamit|mga tagagamit}} ng {{SITENAME}}',
'siteuser'         => 'Tagagamit $1 ng {{SITENAME}}',
'lastmodifiedatby' => 'Huling binago ang pahinang ito noong $2, $1 ni $3.', # $1 date, $2 time, $3 user
'othercontribs'    => 'Batay sa gawa ni/nina $1.',
'others'           => 'iba pa',
'siteusers'        => '{{PLURAL:$2|tagagamit|mga tagagamit}} $1 ng {{SITENAME}}',
'creditspage'      => 'Pahina ng pagkilala sa gumawa (mga kredito)',
'nocredits'        => 'Walang mga kredito/pagkilala sa gumawa na makuha para sa pahinang ito.',

# Spam protection
'spamprotectiontitle' => "Pansala na pananggalang laban sa ''Spam''",
'spamprotectiontext'  => "Ang pahinang ibig mong sagipin ay naharang ng pansala ng ''spam''.
Maaaring dahil ito sa isang kawing sa isang nakatalang hinarang dahil di-kinaisnais na panlabas na sityo/sayt.",
'spamprotectionmatch' => "Ang sumusunod na teksto ang bumuhay sa aming panala ng ''spam'': $1",
'spambot_username'    => "Paglilinis ng ''spam'' ng MediaWiki",
'spam_reverting'      => "Ibinabalik sa huling bersyon na 'di-naglalaman ng mga kawing sa $1",
'spam_blanking'       => 'Lahat ng mga pagbabago ay naglalaman ng mga kawing sa $1, pagpapatlang',

# Info page
'infosubtitle'   => 'Kabatiran para sa pahina',
'numedits'       => 'Bilang ng mga pagbabago (pahina): $1',
'numtalkedits'   => 'Bilang ng mga pagbabago (pahinang usapan): $1',
'numwatchers'    => 'Bilang ng mga tagatingin: $1',
'numauthors'     => 'Bilang ng mga bukdo-tanging mga may-akda (pahina): $1',
'numtalkauthors' => 'Bilang ng bukod-tanging mga may-akda (pahinang usapan): $1',

# Math options
'mw_math_png'    => 'Palaging ilarawan sa anyong PNG',
'mw_math_simple' => 'HTML kung napakapayak o kaya PNG kung iba',
'mw_math_html'   => 'HTML kung maaari o kaya PNG kapag iba',
'mw_math_source' => "Iwanan bilang TeX (para sa mga panghanap na pangteksto o ''text browser'')",
'mw_math_modern' => 'Irekomenda para sa makabagong mga panghanap',
'mw_math_mathml' => 'MathML kung maaari (sinusubok pa)',

# Patrolling
'markaspatrolleddiff'                 => 'Tatakan bilang napatrolya na',
'markaspatrolledtext'                 => 'Tatakan ang pahinang ito bilang napatrolya na',
'markedaspatrolled'                   => 'Tatakan bilang napatrolya na',
'markedaspatrolledtext'               => 'Ang napiling pagbabago ay natatakan na bilang napatrolya.',
'rcpatroldisabled'                    => 'Hindi pinagana ang Patrolyang Pangkamailan-Lamang na Pagbabago',
'rcpatroldisabledtext'                => 'Kasalukuyang hindi pinagagana ang kasangkapang Patrolyang Pangkamakailang-lamang na Pagbabago.',
'markedaspatrollederror'              => 'Hindi matatakan bilang napatrolya na',
'markedaspatrollederrortext'          => 'Kailangang tukuyin mo ang isang pagbabago para matatakan ito bilang napatrolya na.',
'markedaspatrollederror-noautopatrol' => 'Wala kang pahintulot para tatakan ang ginawa mong mga pagbabago bilang napatrolya na.',

# Patrol log
'patrol-log-page'      => 'Tala ng Pagpapatrolya',
'patrol-log-header'    => 'Tala ito ng mga pagbabagong napatrolya na.',
'patrol-log-line'      => 'tinatakang $1 ng $2 napatrolya $3',
'patrol-log-auto'      => '(awtomatiko)',
'patrol-log-diff'      => 'r$1',
'log-show-hide-patrol' => '$1 tala ng pagpatrolya',

# Image deletion
'deletedrevision'                 => 'Binurang lumang pagbabago $1',
'filedeleteerror-short'           => 'Kamalian sa pagbubura ng talaksan: $1',
'filedeleteerror-long'            => 'Nakaranas ng mga kamalian habang binubura ang talaksan:

$1',
'filedelete-missing'              => 'Hindi mabura ang talaksang "$1", dahil wala namang ganito.',
'filedelete-old-unregistered'     => 'Wala sa himpilan ng dato ang tinutukoy na pagbabago sa talaksan "$1".',
'filedelete-current-unregistered' => 'Wala sa himpilan ng dato ang tinukoy na talaksang "$1".',
'filedelete-archive-read-only'    => 'Hindi maisulat ng serbidor ng sapot (web) ang direktoryo ng sinupang "$1".',

# Browsing diffs
'previousdiff' => '← Mas lumang pagbabago',
'nextdiff'     => 'Mas bagong pagbabago →',

# Visual comparison
'visual-comparison' => 'Paghahambing na matatanaw',

# Media information
'mediawarning'         => "'''Babala''': Maaaring naglalaman ang talaksang ito ng kodigong malisyoso, maaaring manganib ang iyong sistema kapag isinagawa mo ito .<hr />",
'imagemaxsize'         => 'Itakda lamang ang hangganan ng mga larawan sa ibabaw ng pahina ng paglalarawang pangtalaksan sa:',
'thumbsize'            => 'Maliit na sukat (parang "kuko sa hinlalaki" lamang):',
'widthheight'          => '$1×$2',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|pahina|mga pahina}}',
'file-info'            => '(sukat ng talaksan: $1, tipo ng MIME: $2)',
'file-info-size'       => '($1 × $2 piksel, sukat ng talaksan: $3, tipo ng MIME: $4)',
'file-nohires'         => '<small>Walang makuhang mas mataas na resolusyon.</small>',
'svg-long-desc'        => '(Talaksang SVG, nasa mga bilang na $1 × $2 mga piksel, sukat ng talaksan: $3)',
'show-big-image'       => 'Buong resolusyon',
'show-big-image-thumb' => '<small>Laki ng paunang tinging ganito: $1 × $2 mga piksel</small>',

# Special:NewFiles
'newimages'             => 'Galerya ng mga bagong talaksan',
'imagelisttext'         => "Nasa ibaba ang isang tala ng '''$1''' {{PLURAL:$1|talaksan|mga talakasang}} nauri na $2.",
'newimages-summary'     => 'Nagpapakita ang natatanging pahinang ito ng huling naikargang mga talaksan.',
'newimages-legend'      => 'Pansala',
'newimages-label'       => 'Pangalan ng talaksan (o bahagi nito):',
'showhidebots'          => "($1 mga ''bot'')",
'noimages'              => 'Walang makikita dito.',
'ilsubmit'              => 'Hanapin',
'bydate'                => 'ayon sa petsa',
'sp-newimages-showfrom' => 'Ipakita ang mga bagong talaksang nagsisimula mula $2, $1',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'video-dims'     => '$1, $2×$3',
'seconds-abbrev' => 's',
'minutes-abbrev' => 'm',
'hours-abbrev'   => 'o',

# Bad image list
'bad_image_list' => 'Ang anyo ay ang mga sumusunod:

Tanging mga nakatalang bagay lamang (mga linyang nagsisimula sa *) ang pinaguukulan ng pansin.
Ang unang kawing sa isang linya ay dapat na nakakawing sa isang talaksang may masamang kalagayan.
Anumang susunod na mga kawing sa pinanggalingang linya ay tinuturing na mga eksepsyon o bukod-tangi, iyong mga pahina kung saan ang mga talaksan ay maaaring lumitaw sa loob ng linya.',

/*
Short names for language variants used for language conversion links.
To disable showing a particular link, set it to 'disable', e.g.
'variantname-zh-sg' => 'disable',
Variants for Chinese language
*/
'variantname-zh-hans' => 'hans',
'variantname-zh-hant' => 'hant',
'variantname-zh-cn'   => 'cn',
'variantname-zh-tw'   => 'tw',
'variantname-zh-hk'   => 'hk',
'variantname-zh-mo'   => 'mo',
'variantname-zh-sg'   => 'sg',
'variantname-zh-my'   => 'my',
'variantname-zh'      => 'zh',

# Variants for Serbian language
'variantname-sr-ec' => 'sr-ec',
'variantname-sr-el' => 'sr-el',
'variantname-sr'    => 'sr',

# Variants for Kazakh language
'variantname-kk-kz'   => 'kk-kz',
'variantname-kk-tr'   => 'kk-tr',
'variantname-kk-cn'   => 'kk-cn',
'variantname-kk-cyrl' => 'kk-cyrl',
'variantname-kk-latn' => 'kk-latn',
'variantname-kk-arab' => 'kk-arabe',
'variantname-kk'      => 'kk',

# Variants for Kurdish language
'variantname-ku-arab' => 'ku-Arabe',
'variantname-ku-latn' => 'ku-Latn',
'variantname-ku'      => 'ku',

# Variants for Tajiki language
'variantname-tg-cyrl' => 'tg-Cyrl',
'variantname-tg-latn' => 'tg-Latn',
'variantname-tg'      => 'tg',

# Metadata
'metadata'          => 'Metadata',
'metadata-help'     => 'Naglalaman ang talaksang ito ng karagdagang kabatiran na maaaring idinagdag mula sa isang kamerang dihital o iskaner na ginamit para likhain o para maging dihital ito.
Kapag nabago ang talaksan mula sa anyong orihinal nito, may ilang detalyeng hindi ganap na maipapakita ang nabagong talaksan.',
'metadata-expand'   => 'Ipakita ang karugtong na mga detalye',
'metadata-collapse' => 'Itago ang karugtong na mga detalye',
'metadata-fields'   => 'Ang mga pook ng metadatang EXIF na nakatala sa mensaheng ito ay masasama sa ipinapakitang pahina ng larawan kapag tumiklop ang tabla ng metadata.
Nakatakdang itago ang iba pa.
* kayarian
* modelo
* petsaorasorihinal
* lantadoras
* pbilang
* pokalhaba', # Do not translate list items

# EXIF tags
'exif-imagewidth'                  => 'Lapad',
'exif-imagelength'                 => 'Taas',
'exif-bitspersample'               => 'Mga bit (piraso) ng bawat komponente (bahagi)',
'exif-compression'                 => 'Plano ng kumpresyon (pagkakasiksik)',
'exif-photometricinterpretation'   => 'Mga taglay (komposisyon) ng piksel',
'exif-orientation'                 => 'Oryentasyon',
'exif-samplesperpixel'             => 'Bilang ng mga komponente (sangkap)',
'exif-planarconfiguration'         => 'Pagkakaayos ng mga dato',
'exif-ycbcrsubsampling'            => "Halimbawang bahagi ng rata (''ratio'') ng Y sa C",
'exif-ycbcrpositioning'            => 'Pagkakaposisyon ng Y at C',
'exif-xresolution'                 => 'Pahalang na resolusyon',
'exif-yresolution'                 => 'Bertikal (patayo) na resolusyon',
'exif-resolutionunit'              => 'Yunit ng resolusyong X at Y',
'exif-stripoffsets'                => 'Lokasyon ng dato ng larawan',
'exif-rowsperstrip'                => 'Bilang ng pahalang na hanay bawat manipis na piraso',
'exif-stripbytecounts'             => 'Mga byte ng bawat siniksik na piraso',
'exif-jpeginterchangeformat'       => "Bawiin at ibalanse (i-''offset'') patungo sa JPEG SOI",
'exif-jpeginterchangeformatlength' => 'Mga byte ng datong JPEG',
'exif-transferfunction'            => 'Tungkuling panglipat',
'exif-whitepoint'                  => 'Kadalisayan (kromatisidad) ng punto o hangganan ng kaputian',
'exif-primarychromaticities'       => 'Mga kadalisayan (kromatisidad) ng mga pangunahing kulay (mga primarya)',
'exif-ycbcrcoefficients'           => 'Mga koepisyente (katuwang na bilang) ng matris na pambago ng espasyo ng kulay',
'exif-referenceblackwhite'         => 'Pares ng mga itim at puting sangguniang halaga',
'exif-datetime'                    => 'Petsa at oras ng pagbabago ng talaksan',
'exif-imagedescription'            => 'Pamagat ng larawan',
'exif-make'                        => 'Kumpanyang tagagawa ng kamera',
'exif-model'                       => 'Modelo ng kamera',
'exif-software'                    => 'Ginamit na sopwer',
'exif-artist'                      => 'May-akda',
'exif-copyright'                   => 'May-hawak ng karapatang-ari (kopirayt)',
'exif-exifversion'                 => 'Bersyong Exif',
'exif-flashpixversion'             => 'Bersyon ng sinusuportahang Flashpix',
'exif-colorspace'                  => 'Espasyo ng kulay',
'exif-componentsconfiguration'     => 'Kahulugan ng bawat komponente',
'exif-compressedbitsperpixel'      => 'Modalidad (paraan) ng pagsisiksik ng larawan',
'exif-pixelydimension'             => 'Tanggap na lapad ng larawan',
'exif-pixelxdimension'             => 'Tanggap na taas ng larawan',
'exif-makernote'                   => 'Mga tala mula sa kumpanyang tagagawa',
'exif-usercomment'                 => 'Mga kumento ng tagagamit',
'exif-relatedsoundfile'            => 'Kaugnay na talaksang nadidinig (audio)',
'exif-datetimeoriginal'            => 'Petsa at oras ng paglikha ng mga dato',
'exif-datetimedigitized'           => 'Petsa at oras ng pagsasadihital',
'exif-subsectime'                  => 'PetsaOras mga subsegundo (bahagi ng segundo)',
'exif-subsectimeoriginal'          => 'PetsaOrasOrihinal subsegundo (bahagi ng segundo)',
'exif-subsectimedigitized'         => 'PetsaOrasDihitalisasyon subsegundo (bahagi ng segundo)',
'exif-exposuretime'                => 'Oras ng pagkakalantad',
'exif-exposuretime-format'         => '$1 seg ($2)<!--seg = segundo (seconds)-->',
'exif-fnumber'                     => 'F Bilang',
'exif-fnumber-format'              => 'f/$1',
'exif-exposureprogram'             => 'Programa ng paglalantad',
'exif-spectralsensitivity'         => 'Sensitibidad sa ispektrum',
'exif-isospeedratings'             => 'Grado ng bilis ng ISO',
'exif-oecf'                        => 'Paktora ng optoelektronikong pagpapalit',
'exif-shutterspeedvalue'           => "Bilis ng pansara (''shutter'')",
'exif-aperturevalue'               => 'Apertura (butas na daanan ng liwanag)',
'exif-brightnessvalue'             => 'Kaningningan',
'exif-exposurebiasvalue'           => 'Panig ng kalantaran',
'exif-maxaperturevalue'            => 'Pinakamataas na aperturang (daanan ng liwanag) panglupa',
'exif-subjectdistance'             => 'Layo ng paksa',
'exif-meteringmode'                => 'Modalidad ng pagmemetro (pagsusukat)',
'exif-lightsource'                 => 'Pinagmumulan ng liwanag',
'exif-flash'                       => "Pangkisap (''flash'')",
'exif-focallength'                 => 'Haba ng lenteng pampokus (pantuon)',
'exif-focallength-format'          => '$1 mm',
'exif-subjectarea'                 => 'Saklaw na paksa',
'exif-flashenergy'                 => "Lakas ng kisap (''flash'')",
'exif-spatialfrequencyresponse'    => 'Tugon ng kalimitan na pangespasyo',
'exif-focalplanexresolution'       => 'Resolusyong X ng kalatagan o lapyang pampokus',
'exif-focalplaneyresolution'       => 'Resolusyong Y ng kalatagan o lapyang pampokus',
'exif-focalplaneresolutionunit'    => 'Yunit ng resolusyon ng kalatagan o lapyang pampokus',
'exif-subjectlocation'             => 'Lokasyon ng paksa',
'exif-exposureindex'               => 'Pang-antas o indeks ng pagkakalantad',
'exif-sensingmethod'               => 'Paraang pandama',
'exif-filesource'                  => 'Pinagmulang talaksan',
'exif-scenetype'                   => 'Uri ng tagpuan',
'exif-cfapattern'                  => 'Gawi ng CFA',
'exif-customrendered'              => 'Pagpoproseso ng pinasadyang larawan',
'exif-exposuremode'                => 'Modalidad ng paglalantad',
'exif-whitebalance'                => 'Balanse ng Kaputian',
'exif-digitalzoomratio'            => "Rata/Antas ng sukat ng dihital na paglapit (''zoom'')",
'exif-focallengthin35mmfilm'       => 'Haba ng pokus sa pilm na 35 mm',
'exif-scenecapturetype'            => 'Uri ng panghuli ng tagpuan',
'exif-gaincontrol'                 => 'Kontrol na pangtagpuan',
'exif-contrast'                    => "Pagkakaiba ng pagsasalungat (''contrast'')",
'exif-saturation'                  => 'Saturasyon (pagkakababad/pagkakapuno)',
'exif-sharpness'                   => 'Katalasan',
'exif-devicesettingdescription'    => 'Paglalarawan sa mga pagtatakdang pangaparato',
'exif-subjectdistancerange'        => 'Antas ng layo ng paksa',
'exif-imageuniqueid'               => 'Natatanging ID ng larawan',

# External editor support
'edit-externally' => 'Baguhin ang talaksang ito sa pamamagitan ng panlabas na aplikasyon',

# Delete conflict
'deletedwhileediting' => 'Babala: Nabura na ang pahinang ito pagkatapos mong magsimulang magbago!',
'recreate'            => 'Likhain muli',

# action=purge
'confirm-purge-top' => 'Linisin ang baunan ng pahinang ito?',

# Table pager
'table_pager_first' => 'Unang pahina',
'table_pager_last'  => 'Huling pahina',
'table_pager_empty' => 'Walang resulta',

# Auto-summaries
'autosumm-blank'   => 'Itinatanggal ang lahat ng nilalaman mula sa pahina',
'autosumm-replace' => "Ipinapalit ang pahina ng may nilalamang '$1'",
'autoredircomment' => 'Ikinakarga sa [[$1]]',
'autosumm-new'     => 'Bagong pahina: $1',

# Live preview
'livepreview-failed' => 'Nabigo ang buhay na pribyu! Subukan ang normal na pribyu.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Maaaring hindi mapakita sa talang ito ang mga pagbabagong mas bago sa $1 segundo.',

# Watchlist editing tools
'watchlisttools-view' => 'Tingnan ang mga magkaugnay na pagbabago',

# Special:Version
'version'                   => 'Bersyon', # Not used as normal message but as header for the special page itself
'version-hook-subscribedby' => 'Sinuskribi ng/ni/nina',
'version-version'           => 'Bersyon',
'version-license'           => 'Lisensiya',
'version-software'          => 'Inistalang software',
'version-software-product'  => 'Produkto',
'version-software-version'  => 'Bersyon',

# Special:FilePath
'filepath-page' => 'Talaksan:',

# Special:SpecialPages
'specialpages' => 'Mga natatanging pahina',

);
