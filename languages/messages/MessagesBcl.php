<?php
/** Bikol Central (Bikol Central)
 *
 * @addtogroup Language
 *
 * @author Filipinayzd
 * @author Steven*fung
 * @author Siebrand Mazeland
 * @author Niklas Laxström
 * @author Siebrand
 * @author Nike
 */

$namespaceNames = array(
	NS_MEDIA          => 'Medio',
	NS_SPECIAL        => 'Espesyal',
	NS_MAIN           => '',
	NS_TALK           => 'Olay',
	NS_USER           => 'Paragamit',
	NS_USER_TALK      => 'Olay_kan_paragamit',
	# NS_PROJECT set by \$wgMetaNamespace
	NS_PROJECT_TALK   => 'Olay_sa_$1',
	NS_IMAGE          => 'Ladawan',
	NS_IMAGE_TALK     => 'Olay_sa_ladawan',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'Olay_sa_MediaWiki',
	NS_TEMPLATE       => 'Plantilya',
	NS_TEMPLATE_TALK  => 'Olay_sa_plantilya',
	NS_HELP           => 'Tabang',
	NS_HELP_TALK      => 'Olay_sa_tabang',
	NS_CATEGORY       => 'Kategorya',
	NS_CATEGORY_TALK  => 'Olay_sa_kategorya',
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Kurítan an mga takod:',
'tog-highlightbroken'         => 'I-format an mga raot na takod <a href="" klase="bâgo">arog kaini</a> (barayán: arog kaini<a href="" klase="panlaog">?</a>).',
'tog-justify'                 => 'Pantayon an mga talodtod',
'tog-hideminor'               => 'Tagôon an mga menor na hirá sa kaaagi pa sanang pagbabâgo',
'tog-extendwatchlist'         => 'Padaklón an pigbabantayan para mahiling an gabos na angay na pagriribay.',
'tog-usenewrc'                => 'Pinakarháy na kaaging pagriribay (JavaScript)',
'tog-numberheadings'          => 'Enseguidang número sa pamayohan.',
'tog-showtoolbar'             => 'Ipahiling an barragamit nin paghihirâ (JavaScript)',
'tog-editondblclick'          => 'Hirahón an mga páhina sa pagdobleng pindót(JavaScript)',
'tog-editsection'             => 'Makahirá kan seksyón sa mga [hirá] na takód',
'tog-editsectiononrightclick' => "Makahirá nin seksyón sa pag-''right click'' sa mga título nin seksyón (JavaScript)",
'tog-showtoc'                 => 'Ipahiling an indise kan mga laog (kan mga páhina na igwang sobra sa 3 pamayohan)',
'tog-rememberpassword'        => 'Giromdomon an mga paglaog ko sa kompyuter na ini',
'tog-editwidth'               => 'Nasa pinakamahiwas na an kahón',
'tog-watchcreations'          => 'Idugang an mga páhinang ginigibo ko sa pigbabantayan ko',
'tog-watchdefault'            => 'Idugang an mga páhinang pighihira ko sa pigbabantayan ko',
'tog-watchmoves'              => 'Idugang an mga páhinang piglilipat ko sa pigbabantayan ko',
'tog-watchdeletion'           => 'Idugang an mga páhinang pigparâ ko sa pigbabantayan ko',
'tog-minordefault'            => 'Markahán mûna na menor an gabós na paghirâ',
'tog-previewontop'            => 'Ipahiling an patânaw bâgo magabót sa kahón nin paghirâ',
'tog-previewonfirst'          => 'Ipahiling an patânaw sa enot na hirá',
'tog-nocache'                 => 'Pogolon an pagabang nin mga páhina',
'tog-enotifwatchlistpages'    => "''E''-suratan ako pag pigribayan an páhinang pigbabantayan ko",
'tog-enotifusertalkpages'     => "''E''-suratan ako pag pigribayan an páhina kan sakong olay",
'tog-enotifminoredits'        => "''E''-suratan man ako para sa mga menor na hira kan mga páhina",
'tog-enotifrevealaddr'        => "Ipahiling an sakuyang ''e''-surat sa mga surat na pag-abiso.",
'tog-shownumberswatching'     => 'Ipahiling an bilang kan nagbabantay na mga parágamit',
'tog-fancysig'                => 'Bâgong pirma (mayô nin enseguidang takod)',
'tog-externaleditor'          => 'Gamiton mûna an panluwás na editor',
'tog-externaldiff'            => 'Gamiton mûna an diff na panluwás',
'tog-showjumplinks'           => 'Maka-"luksó sa" mga takod na pangabót',
'tog-uselivepreview'          => 'Gamiton an patânaw na direkto (JavaScript) (Experimental)',
'tog-forceeditsummary'        => 'Ipaarám sakuyâ kun malaog sa sumáriong blanko nin paghirá',
'tog-watchlisthideown'        => 'Tagôon an mga menor na hirá sa pigbabantayan',
'tog-watchlisthidebots'       => "Tagôon an mga hirá kan ''bot'' sa pigbabantayan",
'tog-watchlisthideminor'      => 'Tagôon an mga menor na hirá sa pigbabantayan',
'tog-nolangconversion'        => 'Pogolon an pagríbay nin mga lain-lain',
'tog-ccmeonemails'            => "Padarahan ako nin mga kopya kan ''e''-surat na pigpadara ko sa ibang mga parágamit",
'tog-diffonly'                => 'Dai ipahiling an mga laog nin páhina sa babâ kan kaib',

'underline-always'  => 'Pirmi',
'underline-never'   => 'Nungka',
'underline-default' => "''Browser'' na normal",

'skinpreview' => '(Tânawon)',

# Dates
'sunday'        => 'Domingo',
'monday'        => 'Lunes',
'tuesday'       => 'Martes',
'wednesday'     => 'Miyerkoles',
'thursday'      => 'Huwebes',
'friday'        => 'Biyernes',
'saturday'      => 'Sabado',
'sun'           => 'Dom',
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
'october'       => 'Oktobre',
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
'october-gen'   => 'Oktobre',
'november-gen'  => 'Nobyembre',
'december-gen'  => 'Disyembre',
'jan'           => 'Ene',
'feb'           => 'Peb',
'apr'           => 'Abr',
'jun'           => 'Hun',
'jul'           => 'Hul',
'aug'           => 'Ago',
'sep'           => 'Set',
'oct'           => 'Okt',
'nov'           => 'Nob',
'dec'           => 'Dis',

# Bits of text used by many pages
'categories'            => 'Mga Kategorya',
'pagecategories'        => '{{PLURAL:$1|Kategorya|Mga kategorya}}',
'category_header'       => 'Mga artikulo sa kategoryang "$1"',
'subcategories'         => 'Mga sub-kategorya',
'category-media-header' => 'Media sa kategoryang "$1"',
'category-empty'        => "''An kategoryang ini mayong laog na mga artikulo o media sa ngonyan.''",

'mainpagetext'      => "<big>'''Instalado na an MediaWiki.'''</big>",
'mainpagedocfooter' => "Konsultarón tabî an [http://meta.wikimedia.org/wiki/Help:Contents User's Guide] para sa impormasyon sa paggamit nin progama kaining wiki.

== Pagpopoon ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Configuration settings list]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]",

'about'          => 'Manonongod',
'article'        => 'Laog na páhina',
'newwindow'      => '(minabukas sa bâgong bintanâ)',
'cancel'         => 'Pondohón',
'qbfind'         => 'Hanápon',
'qbbrowse'       => 'Magrinsay',
'qbedit'         => 'Hirahón',
'qbpageoptions'  => 'Ining páhina',
'qbpageinfo'     => 'Konteksto',
'qbmyoptions'    => 'Mga páhina ko',
'qbspecialpages' => 'Espesyal na mga páhina',
'moredotdotdot'  => 'Dakol pa...',
'mypage'         => 'Páhina ko',
'mytalk'         => 'Olay ko',
'anontalk'       => 'Olay para sa IP na ini',
'navigation'     => 'Nabigasyón',

# Metadata in edit box
'metadata_help' => 'Mga Metadatos:',

'errorpagetitle'    => 'Salâ',
'returnto'          => 'Magbwelta sa $1.',
'tagline'           => 'Halî sa {{SITENAME}}',
'help'              => 'Tabang',
'search'            => 'Hanápon',
'searchbutton'      => 'Hanápon',
'go'                => 'Dumanán',
'searcharticle'     => 'Dumanán',
'history'           => 'Historya kan páhina',
'history_short'     => 'Historya',
'updatedmarker'     => 'bâgo poon kan ultimo kong pagbisita',
'info_short'        => 'Impormasyon',
'printableversion'  => 'Naipiprentang bersyón',
'permalink'         => 'Permanenteng takod',
'print'             => 'Iprénta',
'edit'              => 'Hirahón',
'editthispage'      => 'Hirahón ining páhina',
'delete'            => 'Parâon',
'deletethispage'    => 'Parâon ining páhina',
'undelete_short'    => 'Bawîon an pagparâ {{PLURAL:$1|hinirá|$1 mga hinirá}}',
'protect'           => 'Protehirán',
'protect_change'    => 'ribáyan an proteksyón',
'protectthispage'   => 'Protehirán ining páhina',
'unprotect'         => 'bawîon an pagprotehir',
'unprotectthispage' => 'Bawîon an proteksyón kaining páhina',
'newpage'           => 'Bâgong páhina',
'talkpage'          => 'Pag-olayan ining páhina',
'talkpagelinktext'  => 'Mag-olay',
'specialpage'       => 'Espesyal na Páhina',
'personaltools'     => 'Mga gamit na personal',
'postcomment'       => 'Magkomento',
'articlepage'       => 'Hilingón an páhina kan laog',
'talk'              => 'Orólay',
'views'             => 'Mga hilíng',
'toolbox'           => 'Kagamitan',
'userpage'          => 'Hilingón an páhina kan parágamit',
'projectpage'       => 'Hilingón an páhina kan proyekto',
'imagepage'         => 'Hilingón an páhina kan ladawan',
'mediawikipage'     => 'Hilingón an páhina kan mensahe',
'templatepage'      => 'Hilingón an páhina kan templato',
'viewhelppage'      => 'Hilingón an páhina kan tabang',
'categorypage'      => 'Hilingón an páhina kan kategorya',
'viewtalkpage'      => 'Hilingón an orólay',
'otherlanguages'    => 'Sa ibang mga tataramon',
'redirectedfrom'    => '(Pigredirekta halî sa $1)',
'redirectpagesub'   => 'Iredirekta an páhina',
'lastmodifiedat'    => 'Huring pigmodipikar an páhinang ini $2, $1.', # $1 date, $2 time
'viewcount'         => 'Ining páhina binukasan
{{PLURAL:$1|sarong beses|nin $1 beses}}.',
'protectedpage'     => 'Protektadong páhina',
'jumpto'            => 'Lukso sa:',
'jumptonavigation'  => 'nabigasyón',
'jumptosearch'      => 'hanápon',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Manonongod sa {{SITENAME}}',
'aboutpage'         => '{{ns:project}}:Manonongod',
'bugreports'        => 'Mga reportahe nin mga salâ',
'bugreportspage'    => '{{ns:project}}:Mga reportahe nin salâ',
'copyright'         => 'Makukua an laog sa $1.',
'copyrightpagename' => '{{SITENAME}} derechos nin parásurat',
'copyrightpage'     => '{{ns:project}}:Mga derechos nin parásurat',
'currentevents'     => 'Mga panyayari sa ngonyan',
'currentevents-url' => 'Mga panyayari sa ngonyan',
'disclaimers'       => 'Mga renunsya',
'disclaimerpage'    => '{{ns:project}}:Heneral na renunsya',
'edithelp'          => 'Paghirá kan pagtabang',
'edithelppage'      => '{{ns:help}}:Paghirá',
'faq'               => 'HD',
'faqpage'           => '{{ns:project}}:HD',
'helppage'          => '{{ns:help}}:Mga laog',
'mainpage'          => 'Pangenot na Pahina',
'policy-url'        => '{{ns:project}}:Palakaw',
'portal'            => 'Portal kan komunidad',
'portal-url'        => '{{ns:project}}:Portal kan Komunidad',
'privacy'           => 'Palakaw nin pribasidad',
'privacypage'       => '{{ns:project}}:Palakaw nin pribasidad',
'sitesupport'       => 'Mga donasyon',
'sitesupport-url'   => "{{ns:project}}:Suporta sa ''site''",

'badaccess'        => 'Salang permiso',
'badaccess-group0' => 'Dai ka tinotogotan na gibohon an aksyon na saimong hinahagad.',
'badaccess-group1' => 'An aksyon na saimong hinahagad limitado sa mga parágamit sa grupong $1.',
'badaccess-group2' => 'An aksyon na saimong hinahagad limitado sa mga parágamit sa sarô sa mga grupong $1.',
'badaccess-groups' => 'An aksyon na saimong hinahagad limitado sa mga parágamit sa sarô sa mga grupong $1.',

'versionrequired'     => 'Kaipuhan an bersyon $1 kan MediaWiki',
'versionrequiredtext' => 'Kaipuhan an bersyon $1 kan MediaWiki sa paggamit kan páhinang ini. Hilíngón an [[Special:Version|Bersyon kan páhina]].',

'ok'                      => 'Sige',
'retrievedfrom'           => 'Pigkua sa "$1"',
'youhavenewmessages'      => 'Igwa ka nin $1 ($2).',
'newmessageslink'         => 'mga bâgong mensahe',
'newmessagesdifflink'     => 'huring pagbâgo',
'youhavenewmessagesmulti' => 'Igwa ka nin mga bâgong mensahe sa $1',
'editsection'             => 'hirahón',
'editold'                 => 'hirahón',
'editsectionhint'         => 'Hirahón an seksyon: $1',
'toc'                     => 'Mga laog',
'showtoc'                 => 'ipahiling',
'hidetoc'                 => 'tagôon',
'thisisdeleted'           => 'Hilingón o ibalik an $1?',
'viewdeleted'             => 'Hilingón an$1?',
'restorelink'             => '{{PLURAL:$1|sarong pinarang hirá|$1 na pinarang hirá}}',
'feedlinks'               => 'Hungit:',
'feed-invalid'            => 'Bawal na tipo nin hungit na subkripsyon.',
'site-rss-feed'           => '$1 Hungit nin RSS',
'site-atom-feed'          => '$1 Hungit nin Atomo',
'page-rss-feed'           => '"$1" Hungit na RSS',
'page-atom-feed'          => '"$1" Hungit na Atomo',
'feed-atom'               => 'Atomo',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'Artikulo',
'nstab-user'      => 'Páhina kan parágamit',
'nstab-media'     => 'Páhina kan media',
'nstab-special'   => 'Espesyal',
'nstab-project'   => 'Páhina kan proyekto',
'nstab-image'     => "''File''",
'nstab-mediawiki' => 'Mensahe',
'nstab-template'  => 'Templato',
'nstab-help'      => 'Páhina kan tabang',
'nstab-category'  => 'Kategorya',

# Main script and global functions
'nosuchaction'      => 'Mayong siring na aksyón',
'nosuchactiontext'  => 'An gibo na pinilî nin URL dai bisto kan wiki',
'nosuchspecialpage' => 'Mayong siring na espesyal na páhina',
'nospecialpagetext' => "<big>'''Dai puede an páhinang especial na pinilî mo.'''</big>

Pwede mong mahiling an lista nin mga marhay na páhina sa [[Special:Specialpages]].",

# General errors
'error'                => 'Salâ',
'databaseerror'        => 'Salâ sa base nin datos',
'dberrortext'          => 'May salâ sa hapot na sintaksis kan base nin datos.
Pwedeng may salâ digdi.
An huring probar na hapót iyo:
<blockquote><tt>$1</tt></blockquote>
hale sa aksyón "<tt>$2</tt>".
AnSQL ko nagbalik nin salâ na"<tt>$3: $4</tt>".',
'dberrortextcl'        => 'May salâ sa hapót nin sintaksis kan base nin datos.
Ini an huring probar na hapót:
"$1"
sa aksyón na "$2".
AnSQL ko nagbalik nin salâ na"$3: $4"',
'noconnect'            => "Despensa! May mga problemang teknikal ining wiki, asin dai makaapód sa ''server'' kan base nin datos. <br />
$1",
'nodb'                 => 'Dae napilî an base nin datos.  $1',
'cachederror'          => 'An minasunod sarong kopyang nakaabang kan hinagad na páhina, asin pwede ser na lumâ na.',
'laggedslavemode'      => 'Patanid: An pahina pwedeng dai nin pagbabâgo sa ngonyan.',
'readonly'             => 'Kandado an base nin datos',
'enterlockreason'      => 'Magkaag tabî nin rasón sa pagkandado, asin ikalkulo kun nuarin bubukasón an kandado',
'readonlytext'         => 'Sarado mûna an base nin datos sa mga bâgong entrada asin iba pang mga pagribay, pwede gayod sa rutinang pagmantenir kan base nin datos, después, mabalik na ini sa normal. 

Ini an eksplikasyón kan administrador na nagkandado kaini: $1',
'missingarticle'       => "Dai nahanap kan base nin datos an teksto kan páhina na dapat kaining nahanap, an pangaran \"\$1\".

Puro-pirmeng an rasón kaini an pagsunód sa lumâ nang kaib o takód na historial sa sarong páhina na pigparâ na.

Kun bakô ini an rasón, pwede ser na nakahanap ka nin salâ sa ''software''.
Ireportar tabî ini sa sarong administrador, asin ikaag tabî an URL.",
'readonly_lag'         => 'Enseguidang nakandado an base nin datos mientras makaabot an base nin datos na esklabo saiyang amo.',
'internalerror'        => 'Panlaog na salâ',
'internalerror_info'   => 'Panlaog na salâ: $1',
'filecopyerror'        => 'Dai nakopya an mga \'\'file\'\' na "$1" hasta "$2".',
'filerenameerror'      => 'Dai natâwan nin bâgong ngaran an \'\'file\'\' "$1" to "$2".',
'filedeleteerror'      => "Dai naparâ an ''file'' na \"\$1\".",
'directorycreateerror' => 'Dai nagibo an direktorya na "$1".',
'filenotfound'         => "Dai nahanap an ''file'' na \"\$1\".",
'fileexistserror'      => "Dai maisurat sa ''file'' na \"\$1\": igwa nang ''file'' na arog kaini",
'unexpected'           => 'Dai pighuhunâ na balór: "$1"="$2".',
'formerror'            => 'Salâ: dai pwedeng isumitir an porma',
'badarticleerror'      => 'Dai pwedeng gibohon ini sa ining páhina.',
'cannotdelete'         => "Dai naparâ an páhina o ''file'' na napilî. (Pwede ser naparâ na ini kan ibang paragamit.)",
'badtitle'             => 'Salâ an titulo',
'badtitletext'         => 'Dai pwede an hinagad na título nin páhina, o mayong laog, o sarong títulong pan-ibang tataramón o pan-ibang wiki na sala an pagkatakód. Pwede ser na igwa ining saro o iba pang mga kárakter na dai pwedeng gamiton sa mga título.',
'perfdisabled'         => 'Daspensa! Bawal mûna an ining aksyón huli ta nagpapaluway ini sa base nin datos hasta dai na magamit kan iba an wiki.',
'perfcached'           => 'Nakaabang an minasunod na mga datos, asin pwede ser na mga lumâ na.',
'perfcachedts'         => 'Nakaabang an nagsusunod na mga datos, asin huring nabâgo sa $1.',
'querypage-no-updates' => 'Pigpopogol mûna an mga pagbabâgo sa páhinang ini. Dai mûna mababâgo an mga datos digdi.',
'wrong_wfQuery_params' => 'Salâ na parámetro sa wfQuery()<br />
Acción: $1<br />
Hapót: $2',
'viewsource'           => 'Hilingón an ginikanan',
'viewsourcefor'        => 'para sa $1',
'protectedpagetext'    => 'An páhinang ini pigsará tangarig pogolon an paghirá.',
'viewsourcetext'       => 'Pwede mong hilingón asin arógon an ginikanan kan páhinang ini:',
'protectedinterface'   => "An páhinang ini nagtatao nin ''interface'' para sa ''software'', asin sarado tangarig mapondo an pag-abuso.",
'editinginterface'     => "'''Patanid:''' An pighihira mong páhina piggagamit para sa tekstong ''interface'' kan ''software''. An mga pagbabâgo sa páhinang ini makakaapektar sa hitsura kan ''interface'' nin parágamit kan mga ibang parágamit.",
'sqlhidden'            => '(nakatagô an hapót nin SQL)',
'cascadeprotected'     => 'Pinoprotehirán ining páhina sa mga paghirá, ta sarô ini sa mga minasunod na {{PLURAL:$1|páhina|mga páhina}} na pinoprotehiran kan opsyón na "katarata" na nakabuká:
$2',
'namespaceprotected'   => "Mayô kang permisong maghirá kan mga páhina sa '''$1''' ngaran-espacio.",
'customcssjsprotected' => 'Mayô kang permiso na maghirâ kaining páhina, hulî ta igwa ining mga puesta na personal kan ibang paragamit.',
'ns-specialprotected'  => 'An mga páhinang nasa {{ns:special}} na ngaran-espacio dai pwedeng hirahón.',

# Login and logout pages
'logouttitle'                => 'Magluwas an paragamit',
'logouttext'                 => "<strong>Nakaluwas ka na.</strong><br />
Pwede mo pang gamiton an {{SITENAME}} na dai nagpapabisto, o pwede ka giraray lumaog
bilang pareho o ibang parágamit. Giromdomon tabî na an ibang mga páhina pwedeng mahiling pa na garo nakalaog ka pa, hasta limpyarón mo an abang kan ''browser'' mo.",
'welcomecreation'            => "== Maogmang Pagdagos, $1! ==

Nagibo na an ''account'' mo. Giromdomon tabi na ribayán an saimong mga kabôtan sa {{SITENAME}}.",
'loginpagetitle'             => 'Maglaog an paragamit',
'yourname'                   => 'Pangaran kan parágamit:',
'yourpassword'               => 'Sekretong panlaog:',
'yourpasswordagain'          => 'Itaták giraray an sekretong panlaog:',
'remembermypassword'         => 'Giromdomon an paglaog ko sa kompyuter na ini',
'yourdomainname'             => "An saimong ''domain'':",
'externaldberror'            => "Igwang nin salang panluwas pantunay kan base nin datos o dai ka pigtotogotan na bâgohon an saimong panluwas na ''account''.",
'loginproblem'               => '<b>May problema sa paglaog mo.</b><br />Probaran giraray!',
'login'                      => 'Maglaog',
'loginprompt'                => "Kaipuhan may ''cookies'' ka para makalaog sa {{SITENAME}}.",
'userlogin'                  => "Maglaog / maggibo nin ''account''",
'logout'                     => 'Magluwas',
'userlogout'                 => 'Magluwas',
'notloggedin'                => 'Mayô sa laog',
'nologin'                    => 'Igwa ka nin entrada? $1.',
'nologinlink'                => "Maggibo nin ''account''",
'createaccount'              => "Maggibo nin ''account''",
'gotaccount'                 => "Igwa nang ''account''? $1.",
'gotaccountlink'             => 'Maglaog',
'createaccountmail'          => "sa ''e''-surat",
'badretype'                  => 'Dai parehas an pigtaták mong mga sekretong panlaog.',
'userexists'                 => 'Piggagamit na kan iba an pangaran. Magpili tabî nin iba.',
'youremail'                  => "''E''-surat:",
'username'                   => 'Pangaran kan parágamit:',
'uid'                        => 'ID kan parágamit:',
'yourrealname'               => 'Totoong pangaran:',
'yourlanguage'               => 'Tataramon:',
'yourvariant'                => 'Bariante',
'yournick'                   => 'Gahâ:',
'badsig'                     => 'Dai pwede an bâgong pirmang ini; isúsog an mga HTML na takód.',
'badsiglength'               => "Halabâon an gahâ; kaipuhan dai mababâ sa $1 na mga ''character''.",
'email'                      => "''E''-surat",
'prefs-help-realname'        => 'Opsyonal an totoong pangaran asin kun itatao mo ini, gagamiton ini yangarig an mga sinurat mo maatribuir saimo.',
'loginerror'                 => 'Salâ an paglaog',
'prefs-help-email'           => "Opsyonal an ''e''-surat, pero pwede kan masosog kan iba sa paagi kan saimong páhina o páhina nin olay na dai kinakaipuhan na ipabisto an identidad mo.",
'prefs-help-email-required'  => "Kaipuhan an ''e''-surat.",
'nocookiesnew'               => "Nagibo na an ''account'' kan parágamit, alagad dai ka pa nakalaog. Naggagamit nin ''cookies'' an {{SITENAME}} para magpalaog sa mga parágamit. Nakapondo an ''cookies'' mo. Paandaron tabî ini, dangan, maglaog gamit an bâgo mong pangaran asin sekretong panlaog.",
'nocookieslogin'             => 'Naggagamit nin mga cookies an {{SITENAME}} para magpalaog nin mga paragamit. Nakapondo an mga cookies mo. Paandaron tabi ini asin probaran giraray.',
'noname'                     => 'Dai ka pa nagkaag nin pwedeng gamiton na pangaran.',
'loginsuccesstitle'          => 'Matriumpo an paglaog',
'loginsuccess'               => "'''Nakalaog ka na sa {{SITENAME}} bilang si \"\$1\".'''",
'nosuchuser'                 => "Mayong parágamit sa pangaran na \"\$1\". Reparohon an pigsurat mo, o maggibo nin bâgong ''account''.",
'nosuchusershort'            => 'Mayong parágamit sa nagngangaran na "$1". Reparohon an pigsurat mo.',
'nouserspecified'            => 'Kaipuhan mong kaagan nin pangaran.',
'wrongpassword'              => 'Salâ an pigtaták na sekretong panlaog. Probaran giraray tabî.',
'wrongpasswordempty'         => 'Mayong pigkaag na sekretong panlaog. Probaran giraray tabî.',
'passwordtooshort'           => "Salâ an sekretong panlaog mo o halîpoton ini. Kaipuhan na igwang dai mababâ sa $1 na ''characters'' asin iba man sa pinilî mong pangaran.",
'mailmypassword'             => "Ipadara sa ''e''-surat an sekretong panlaog",
'passwordremindertitle'      => 'Panpaísi nin sekretong panlaog halî sa {{SITENAME}}',
'passwordremindertext'       => 'Sarong parágamit (pwede ser na ika, haî sa IP na $1)
an naghagad nin bâgong sekretong panlaog para sa {{SITENAME}} ($4).
"$3" na an bâgong sekretong panlaog ni "$2".
Kaipuhan maglaog ka na asin ibalyó an saimong sekretong panlaog.

Kun ibang tawo an naghagad kaini o kun nagiromdóman mo na an saimong sekretong panlaog asin habô mo nang ribayan ini, dai mo na pagintiendehon ining mensahe. Ipadagos na an paggamit kan dating sekretong panlaog.',
'noemail'                    => "Mayong ''e''-surat na nakarehistro sa parágamit na \"\$1\".",
'passwordsent'               => "May bâgong sekretong panlaog na pigpadará ''e''-surat na nakarehistro ki \"\$1\".
Maglaog tabî giraray pagnakua mo na ini.",
'blocked-mailpassword'       => 'Pigbagatan sa paghirá an saimong IP, asin pigpopogolan na magamit an pagbawi kan sekretong panlaog tangarig maibitaran an pagabuso.',
'eauthentsent'               => "May ipinadarang ''e''-surat na kompirmasyón sa piniling ''e''-surat.
Bâgo pa magpadara nin iba pang ''e''-surat sa ''account'' na ini, kaipuhan tabing sunodon an mga instruksyon na nasa ''e''-surat,
para makompirmar na talagang saimo ining ''account''.",
'throttled-mailpassword'     => 'May pinadara nang paisi nin sekretong panlaog, sa huring
$1 na oras. Para pogolan an mga pagabuso, sarong paisi sana an ipapadara sa laog nin
$1 na oras.',
'mailerror'                  => "Salâ an pagpadará nin ''e''-surat: $1",
'acct_creation_throttle_hit' => "Pasensya, nakagibo ka na nin $1 ''accounts''. Dai ka na makakagibo pa.",
'emailauthenticated'         => "An saimong ''e''-surat pinatunayan sa $1.",
'emailnotauthenticated'      => "Dai pa napatunayan an saimong ''e''-surat. Mayong ipapadarang ''e''-surat para sa ano man na minasunod na gamit.",
'noemailprefs'               => "Magpilî tabî nin ''e''-surat tangarig magandar ining mga gamit.",
'emailconfirmlink'           => "Kompirmaron tabî an saimong ''e''-surat",
'invalidemailaddress'        => "Dai matogotan ining ''e''-surat ta garo salâ an ''format'' kaini. Magkaag tabî nin tamâ o dai pagkaagan.",
'accountcreated'             => "Nagibo na an ''account''.",
'accountcreatedtext'         => "Ginibo na an ''account'' para ki $1.",
'loginlanguagelabel'         => 'Tataramon: $1',

# Password reset dialog
'resetpass'               => "Ipwesto giraray an sekretong panlaog kan ''account''",
'resetpass_announce'      => "Nakalaog ka na may kodang temporaryong ''e''-sinurat. Para matapos an paglaog, kaipuhan mong magpwesto nin bâgong sekretong panlaog digdi:",
'resetpass_text'          => '<!-- Magdugang nin teksto digdi -->',
'resetpass_header'        => 'Ibalyó an sekretong panlaog',
'resetpass_submit'        => 'Ipwesto an sekretong panlaog dangan maglaog',
'resetpass_success'       => 'Naribayan na an saimong sekretong panlaog! Pigpapadagos ka na...',
'resetpass_bad_temporary' => 'Dai pwede ining temporariong sekretong panlaog. Pwede ser na binâgo mo na an saimong sekretong panlaog o naghagad ka na nin bâgong temporariong sekretong panlaog.',
'resetpass_forbidden'     => 'Dai pwedeng ribayan an mga sekretong panlaog sa ining wiki',
'resetpass_missing'       => 'Mayong datos an pormulário.',

# Edit page toolbar
'bold_sample'     => 'Mahîbog na teksto',
'bold_tip'        => 'Mahîbog na teksto',
'italic_sample'   => 'Tekstong Itáliko',
'italic_tip'      => 'Tekstong Itáliko',
'link_sample'     => 'Título nin takód',
'link_tip'        => 'Takód sa laog',
'extlink_sample'  => 'http://www.ehemplo.com títulong nakatakod',
'extlink_tip'     => 'Panluwas na takod (giromdomon an http:// na prefiho)',
'headline_sample' => 'Tekstong pamayohan',
'headline_tip'    => 'Tangga ika-2 na pamayohan',
'math_sample'     => 'Isaliôt an pormula digdi',
'math_tip'        => 'Pórmulang matemátika (LaTeX)',
'nowiki_sample'   => "Isaliot digdi an tekstong dai na-''format''",
'nowiki_tip'      => "Dai pagindiendehon pag-''format'' kan wiki",
'image_sample'    => 'Halimbawa.jpg',
'image_tip'       => 'Nakaturay na ladawan',
'media_sample'    => 'Halimbawa.ogg',
'media_tip'       => "Takód sa ''media file''",
'sig_tip'         => 'Pirma mo na may taták nin oras',
'hr_tip'          => 'Pabalagbag na linya (use sparingly)',

# Edit pages
'summary'                   => 'Sumáda',
'subject'                   => 'Tema/pamayohan',
'minoredit'                 => 'Menor na hirá ini',
'watchthis'                 => 'Bantayan an páhinang ini',
'savearticle'               => 'Itagama an páhina',
'preview'                   => 'Tânawon',
'showpreview'               => 'Hilingón an patânaw',
'showlivepreview'           => 'Patânaw na direkto',
'showdiff'                  => 'Hilingón an mga pagbabâgo',
'anoneditwarning'           => "'''Patanid:''' Dai ka nakalaog. An saimong IP masusurat sa historya kan paghira kan páhinang ini.",
'missingsummary'            => "'''Paisi:''' Dai ka nagkaag nin sumád kan paghirâ. Kun pindotón mo giraray an Itagama, maitatagama an hirá mo na mayô kaini.",
'missingcommenttext'        => 'Paki lâgan nin komento sa ibabâ.',
'missingcommentheader'      => "'''Paisi:''' Dai ka nagkaag nin tema/pamayohan para sa ining komentaryo. Kun pindoton mo giraray an Itagama, maitatagama an hira mo na mayô ini.",
'summary-preview'           => 'Patânaw kan sumáda',
'subject-preview'           => 'Patânaw nin tema/pamayohan',
'blockedtitle'              => 'Pigbágat an parágamit',
'blockedtext'               => "<big>'''Pigbagat an pangaran o IP mo.'''</big>

Si $1 an nagbagat. Ini an itinaong rasón, ''$2''.

* Pagpoon kan pagbagat: $8
* Pagpasó kan pagbagat: $6
* Piniling bagaton: $7

Pwede mong suratan si $1 o an iba pang [[{{MediaWiki:Grouppage-sysop}}|administrador]] para pagoralayan an manonongod sa pagbagat.
Dai mo pwedeng gamiton an ' ''e''-suratan an parágamit ' kun mayong tamang ''e''-surat sa  [[Special:Preferences|mga kabôtan kan ''account'']] mo asin dai ka pigbagat sa paggamit kaini.
$3 an presente mong IP, asin #$5 an ID nin pigbagat. Ikaag tabî an arin man o pareho sain man na hapót.",
'autoblockedtext'           => "Enseguidang pigbagat an IP mo ta ginamit ini kan ibang parágamit, na binagat ni \$1.
Ini an rasón:

:''\$2''

* Pagpoon kan pagbagat: \$8
* Pagpasó kan pagbagat: \$6

Pwedeng mong suratan si \$1 o an iba pang mga
[[{{MediaWiki:Grouppage-sysop}}|administrador]] para pagolayan an manonongod sa pagbagat.

Giromdomon tabî na pwede mo sanang gamiton an \"''e''-suratan ining parágamit\" na gamit kun igwa kang tamang ''e''-surat na nakarehistro saimong [[Special:Preferences|mga kabôtan nin parágamit]] asin dai ka pigbabagat sa paggamit kaini.

\$5 an pigbagat na ID. Ikaag tabî ining ID sa gabos na paghapot mo.",
'blockedoriginalsource'     => "An ginikanan kan '''$1''' mahihiling sa ibabâ:",
'blockededitsource'         => "An teksto kan '''mga hira mo''' sa '''$1''' mahihiling sa babâ:",
'whitelistedittitle'        => 'Kaipuhan an paglaog tangarig makahirá',
'whitelistedittext'         => 'Kaipuhan mong $1 tangarig makahirá nin mga páhina.',
'whitelistreadtitle'        => 'Kaipuhan maglaog tangarig makabasa',
'whitelistreadtext'         => 'Kaipuhan an [[Special:Userlogin|entrada]] para mabasa mo ining mga páhina.',
'whitelistacctitle'         => 'Dai ka tinotogotan na maggibo nin account',
'whitelistacctext'          => "Tangarig togotan na maggibo nin ''account'' sa ining wiki kaipuhan mong [[Special:Userlogin|maglaog]] asin dapat igwa kang mga hustong permiso.",
'confirmedittitle'          => "Kaipuhan nin kompirmasyón nin ''e''-surat para makahirá",
'confirmedittext'           => "Kaipuhan mong kompirmaron an saimong ''e''-surat. Ipwesto tabî asin patunayan an saimong ''e''-surat sa [[Special:Preferences|mga kabôtan kan parágamit]].",
'nosuchsectiontitle'        => 'Mayong siring na seksyón',
'nosuchsectiontext'         => 'Mayo man an seksyón an pighihira mo. Nin huli ta mayô nin seksyón na $1, mayong lugar na pwedeng tagamahan kan hirá mo.',
'loginreqtitle'             => 'Kaipuhan Maglaog',
'loginreqlink'              => 'maglaog',
'loginreqpagetext'          => 'Kaipuhan kang $1 tangarig makahilíng nin ibang páhina.',
'accmailtitle'              => 'Napadará na an panlaog.',
'accmailtext'               => 'An sekretong panlaog ni "$1" naipadará na sa $2.',
'newarticle'                => '(Bâgo)',
'newarticletext'            => "Sinunod mo an takod sa pahinang mayô pa man.
Para magibo an páhina, magpoon pagsurat sa kahon sa babâ
(hilingón an [[{{MediaWiki:Helppage}}|páhina nin tabang]] para sa iba pang impormasyon).
Kun dai tinuyong nakaabot ka digdi, pindoton sana an '''back''' sa ''browser'' mo.",
'anontalkpagetext'          => "----''Ini an páhina kan olay kan sarong parágamit na dai bisto na dai pa naggibo nin'' account ''o dai naggagamit kaini. Entonces, piggagamit mi an numero nin IP para mabisto siya. Ining IP pwede gamiton kan manlain-lain na mga parágamit. Kun ika sarong páragamit na dai bisto asin konbensido ka na pigsasabi ka ining mga komento bakô man dapit saimo,  [[Special:Userlogin|maggibo nin'' account ''o maglaog]] tabî para maibitaran an pagkaribong saimo asin sa ibang mga parágamit na dai bisto.''",
'noarticletext'             => 'Mayo man na teksto sa páhinang ini, pwede mong [[Special:Search/{{PAGENAME}}|hanápon ining titulo nin páhina]] sa ibang mga páhina o [{{fullurl:{{FULLPAGENAME}}|action=edit}} hirahon ining páhina].',
'clearyourcache'            => "'''Pagiromdom:''' Pagkatapos kan pagtagama, pwede ser na kaipuhan mong lawigawan an abang kan ''browser'' para mahiling mo an mga pagbabâgo. '''Mozilla / Firefox / Safari:''' doonan an ''shift'' an ''Shift'' sabay an pagpindot sa ''Reload'', o pindoton an ''Ctrl-Shift-R'' (''Cmd-Shift-R'' sa Apple Mac); '''IE:''' doonan (dai halion an muro) an ''Ctrl'' mientras sabay an pagpindot sa  ''Refresh'', o pindoton an ''Ctrl-F5''; '''Konqueror:''': pindoton sana ''Reload'', o pindoton  an ''F5''; '''Opera''' pwede ser na kaipuhan na halîon an gabos na laog kan abang sa ''Tools→Preferences''.",
'usercssjsyoucanpreview'    => "<strong>Tip:</strong> Gamiton an 'Show preview' para testingon an bâgong CSS/JS bago magtagama.",
'usercsspreview'            => "'''Giromdomon tabî na pigpapatânaw sana saimo an CSS nin parágamit, dai pa ini nakatagama!'''",
'userjspreview'             => "'''Giromdomon tabi na pigtetest/pighihiling mo sana an patanaw kan saimong JavaScript nin paragamit, dai pa ini naitagama!'''",
'userinvalidcssjstitle'     => "'''Patanid:''' Mayong ''skin'' na \"\$1\". Giromdomon tabî na an .css asin .js na mga páhina naggagamit nin titulong nakasurat sa sadit na letras, halimbawa {{ns:user}}:Foo/monobook.css bakong {{ns:user}}:Foo/Monobook.css.",
'updated'                   => '(Binâgo)',
'note'                      => '<strong>Paisi:</strong>',
'previewnote'               => '<strong>Patânaw sana ini; dai pa naitagama an mga pagbabâgo!</strong>',
'previewconflict'           => 'Mahihiling sa patânaw na ini an tekstong nasa itaas na lugar nin paghirá arog sa maipapahiling kun ini an itatagama mo.',
'session_fail_preview'      => '<strong>Despensa! Dai mi napadagos an paghirá mo huli sa pagkawara nin datos kan sesyón.
Probaran tabi giraray. Kun dai man giraray magibo, probaran na magluwas dangan maglaog giraray.</strong>',
'session_fail_preview_html' => "<strong>Despensa! Dai mi napadagos an paghirá mo nin huli sa kawâran kan datos kan sesyón.</strong>

''Huli ta ining wiki may HTML na nakaandar, pigtago an patânaw bilang paglikay kontra sa mga atake sa JavaScript.''

<strong>Kun talagang boot mong hirahón ini, probaran giraray. Kun dai pa giraray magibo, magluwas dangan maglaog giraray. </strong>",
'token_suffix_mismatch'     => "<strong>Dai pigtogotan an hirá mo ta sinabrit kan ''client'' mo an mga ''punctuation characters''. Dai pigtogotan ining hirá para maibitaran na maraot an teksto kan páhina. 
Nanyayari nanggad ini kun naggagamit ka nin bakong marhay asin dai bistong ''web-based proxy service''.</strong>",
'editing'                   => 'Pighihira $1',
'editinguser'               => 'Pighihira an parágamit na <b>$1</b>',
'editingsection'            => 'Pighihira an $1 (seksyon)',
'editingcomment'            => 'Pighihira an $1 (komento)',
'editconflict'              => 'Komplikto sa paghihira: $1',
'explainconflict'           => 'May ibang parágamit na nagbâgo kaining páhina kan pagpoon mong paghirá kaini.
Nahihiling ang páhina kan teksto sa parteng itaas kan teksto.
An mga pagbabâgo mo nahihiling sa parteng ibabâ kan teksto.
Kaipuhan mong isalak an mga pagbabâgo mo sa presenteng teksto.
An teksto na nasa parteng itaas <b>sana</b> an maitatagama sa pagpindot mo kan "Itagaman an páhina".<br />',
'yourtext'                  => 'Saimong teksto',
'storedversion'             => 'Itinagamang bersyón',
'nonunicodebrowser'         => "<strong>PATANID: An ''browser'' mo bakong ''unicode complaint''. Igwang temporariong sistema na nakaandar para makahirá ka kan mga pahina: mahihiling an mga ''character'' na non-ASCII sa kahon nin paghirá bilang mga kodang hexadecimal.</strong>",
'editingold'                => '<strong>PATANID: Pighihira mo an pasó nang pagpakarhay kaining páhina.
Kun itatagama mo ini, mawawarâ an mga pagbabâgong nagibo poon kan pagpakarhay kaini.</strong>',
'yourdiff'                  => 'Mga pagkakaiba',
'copyrightwarning'          => 'Giromdomon tabî na an gabos na kontribusyon sa {{SITENAME}} pigkokonsiderar na $2 (hilingon an $1 para sa mga detalye). Kun habô mong mahirá an saimomg sinurat na mayong pakimâno, pues, dai tabî isumitir iyan digdi.<br /> 
Pigpapangakô mo man samuyâ na ika an nagsurat kaini, o kinopya mo ini sa dominiong panpubliko o sarong parehong libreng rekurso (hilingon an $1 para sa mga detalye). <strong>DAI TABI MAGSUMITIR NIN MGA GIBONG IPINAPANGALAD NA KOPYAHON NA MAYONG PERMISO!</strong>',
'copyrightwarning2'         => 'Giromdomon tabî na an gabos na kontribusyon sa Betawiki pwedeng hirahón, bâgohon o halîon kan ibang mga parágamit. Kun habô mong mahirá an saimomg sinurat na mayong pakimâno, pues, dai tabî isumitir iyan digdi.<br />
Pigpapangakô mo man samuyâ na ika an nagsurat kaini, o kinopya mo ini sa dominiong panpubliko o sarong parehong libreng rekurso (hilingon an $1 para sa mga detalye). <strong>DAI TABI MAGSUMITIR NIN MGA GIBONG IPINAPANGALAD NA KOPYAHON KUN MAYONG PERMISO!</strong>',
'longpagewarning'           => "<strong>PATANID: $1 na kilobytes na ka-halabâ ining páhina; an ibang mga ''browser'' pwedeng magkaproblema sa paghirá nin mga páhinang haros o sobra sa 32kb.
Isipon tabî na bangaon ini para magin mga mas sadit na seksyón.</strong>",
'longpageerror'             => "<strong>SALA': $1 na kilobytes na ka-halabâ an páhinang isinumitir mo, na mas halabâ sa hanggan nin $2 na kilobytes. Dai pwede ining itagama.</strong>",
'readonlywarning'           => "<strong>PATANID: Nakakandado an base nin datos para sa pagmantinir, pues, dai mo muna pwede itagama an mga hira mo. Pwede mo pa man na i-''cut-n-paste''
an teksto sa sarong ''text file'' asin itagama ini para sa atyan.</strong>",
'protectedpagewarning'      => "<strong>PATANID:  Nakakandado ining pahina tangarig an mga parágamit na may priblehiyo nin ''sysop'' sana an pwedeng maghira kaini.</strong>",
'semiprotectedpagewarning'  => "'''Paisi:''' An pahinang ini isinara tangarig mga rehistradong parágamit sana an makahira kaini.",
'cascadeprotectedwarning'   => "'''Tanid:''' Nakakandado an pahinang ini para an mga paragamit na igwang pribilehio nin sysop sana an puedeng maghira kaini, huli ta bilang ini sa mga kataratang protektado na {{PLURAL:$1|pahina|mga pahina}}:",
'templatesused'             => 'Mga templato na piggamit sa páhinang ini:',
'templatesusedpreview'      => 'Mga templato na piggamit sa patânaw na ini:',
'templatesusedsection'      => 'Mga templato n piggamit sa seksyón na ini:',
'template-protected'        => '(protektado)',
'template-semiprotected'    => '(semi-protektado)',
'edittools'                 => '<!-- An teksto digdi mahihiling sa babâ kan mga pormang pighihira asin pigkakarga. -->',
'nocreatetitle'             => 'Limitado an pagigibo nin páhina',
'nocreatetext'              => "Igwang restriksyón sa paggibo nin bagong páhina sa ''site'' na ini.
Pwede kang bumalik dangan maghirá nin presenteng páhina, o [[Special:Userlogin|maglaog o magbukas nin ''account'']].",
'nocreate-loggedin'         => 'Mayô ka nin permiso na maggibo nin mga bâgong pahina sa wiki na ini.',
'permissionserrors'         => 'Mga salang mga permiso',
'permissionserrorstext'     => 'Mayô kang permiso na gibohon yan, sa mga minasunod na{{PLURAL:$1|rason|mga rason}}:',
'recreate-deleted-warn'     => "'''Patanid: Piggigibo mo giraray an pahina na dating pigparâ.'''",

# "Undo" feature
'undo-success' => 'Pwedeng bawîon an paghirâ. Susogon tabî an komparasyón sa babâ para maberipikár kon ini an boot mong gibohon, tapos, itagama an mga pagbabâgo sa babâ tangarig tapuson an pagbawî sa hirâ.',
'undo-failure' => 'Dai napondó an paghihirâ tâ igua pang ibang paghihirâ sa tahaw na nagkokomplikto.',
'undo-summary' => 'Bawîon an pagpakarhay na $1 ni [[Special:Contributions/$2|$2]] ([[User talk:$2|olay]])',

# Account creation failure
'cantcreateaccounttitle' => "Dai makagibo nin ''account''",
'cantcreateaccount-text' => "An pagbukas nin ''account'' halî sa IP na (<b>$1</b>) binagat ni [[User:$3|$3]].

''$2'' an rasón na pigtao ni $3",

# History pages
'revhistory'          => 'Historya kan pagpakarhay',
'viewpagelogs'        => 'Hilingón an mga historial kaining páhina',
'nohistory'           => 'Mayong paghirá nin historya sa páhinang ini.',
'revnotfound'         => 'Dai nahanap an pagpakarhay',
'revnotfoundtext'     => 'Dai nahanap an lumang pagpakarhay kan páhina na hinagad mo. Sosogon tabî an URL na ginamit mo sa pagabót sa páhinang ini.',
'loadhist'            => 'Pigkakarga an historya kan páhina.',
'currentrev'          => 'Presenteng pagpakarhay',
'revisionasof'        => 'An pagpakarhay sa $1',
'revision-info'       => 'An pagpakarhay sa $1 ni $2',
'previousrevision'    => '←Mas lumang pagpakarhay',
'nextrevision'        => 'Mas bâgong pagpakarhay→',
'currentrevisionlink' => 'Presenteng pagpakarhay',
'cur'                 => 'pre',
'next'                => 'sunod',
'last'                => 'huri',
'orig'                => 'orih',
'page_first'          => 'enot',
'page_last'           => 'huri',
'histlegend'          => "Kaib na pinili: markahan an mga ''radio box'' kan mga bersyón para makomparar asin pindoton an ''enter'' o butones ibabâ.<br />
Legend: (pre) = kaibhan sa presenteng bersyón,
(huri) = kaibhan sa huring bersyón, M = menor na hirá.",
'deletedrev'          => '[pigparâ]',
'histfirst'           => 'Pinakaenot',
'histlast'            => 'Pinakahuri',
'historysize'         => '($1 bytes)',
'historyempty'        => '(mayong laog)',

# Revision feed
'history-feed-title'          => 'Histoya kan pagpakarhay',
'history-feed-description'    => 'Historya kan pagpakarhay para sa páhinang ini sa wiki',
'history-feed-item-nocomment' => '$1 sa $2', # user at time
'history-feed-empty'          => 'Mayô man an hinagad na páhina.
Pwede ser na pigparâ na ini sa wiki, o tinâwan nin bâgong pangaran.
Probaran tabî an [[Special:Search|pighahanap sa wiki]] para sa mga páhinang dapít.',

# Revision deletion
'rev-deleted-comment'         => '(pigtanggal an komento)',
'rev-deleted-user'            => '(pigtanggal an parágamit)',
'rev-deleted-event'           => '(hinalî an ingreso)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">
Ining pagpakarhay nin páhina pighalî na sa mga archibong panpubliko.
Pwede ser na may mga detalye sa [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} historial nin pagparâ].
</div>',
'rev-deleted-text-view'       => "<div class=\"mw-warning plainlinks\">
Ining pagpakarhay nin páhina pighalî na sa mga archibong panpubliko.
Bilang sarong administrador kaining ''site'' pwede mo ining hilingón;
pwede ser na igwang mga detalye sa [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} historial kan pagparâ].
</div>",
'rev-delundel'                => 'Ipahiling/tagôon',
'revisiondelete'              => 'Parâon/bawîon an mga pagpakarhay.',
'revdelete-nooldid-title'     => 'Mayong target na pagpakarhay',
'revdelete-nooldid-text'      => 'Dai ka nagpili nin target na pagpakarhay o mga pagpakarhay tangarig magamit ini.',
'revdelete-selected'          => "{{PLURAL:$2|Selected revision|Selected revisions}} kan '''$1:'''",
'logdelete-selected'          => "{{PLURAL:$2|Selected log event|Selected log events}} para sa '''$1:'''",
'revdelete-text'              => "An mga pagpakarhay asin mga panyayari na pigparâ mahihiling pa sa historya asin mga historial kan páhina, pero an ibang parte kan mga laog kaini dai na ipapahiling sa publiko.

An ibang mga administrador sa ining wiki pwede pang maghiling kan mga nakatagong laog asin pwede pa nindang bawîon an pagparâ kaini sa paggamit kan parehong ''interface'', kun mayô pang mga ibang restriksyón.",
'revdelete-legend'            => 'Ipwesto an mga restriksyón:',
'revdelete-hide-text'         => 'Tagôon an teksto kan pagpakarhay',
'revdelete-hide-name'         => 'Tagôon an aksyon asin target',
'revdelete-hide-comment'      => 'Tagôon an komento sa paghirá',
'revdelete-hide-user'         => 'Tagôon an pangaran kan editor/IP',
'revdelete-hide-restricted'   => 'Ibali sa mga restriksyón na ini an mga sysops asin iba pa',
'revdelete-suppress'          => 'Dai ipahilíng an mga datos sa mga sysops asin sa mga iba pa',
'revdelete-hide-image'        => "Tagôon an laog kan ''file''",
'revdelete-unsuppress'        => 'Halîon an mga restriksyón sa mga ibinalík na pagpakarhay',
'revdelete-log'               => 'Historial na komento:',
'revdelete-submit'            => 'Ibílang sa piniling pagpakarhay',
'revdelete-logentry'          => 'pigribayan an bisibilidad nin panyayari kan $1 [[$1]]',
'logdelete-logentry'          => 'Pigribayan an bisibilidad nin panyayari kan [[$1]]',
'revdelete-logaction'         => '$1 {{PLURAL:$1|revision|revisions}} pigpwesto sa modang $2',
'logdelete-logaction'         => '$1 {{PLURAL$1|panyayari|mga panyayari}} sa [[$3]] pigpwesto sa modang $2',
'revdelete-success'           => 'Nakapwesto na an bisibilidad kan pagpakarhay.',
'logdelete-success'           => 'Nakapuesto na an katalâan kan nangyari.',

# Oversight log
'oversightlog'    => 'Historial nin pagmanaw',
'overlogpagetext' => 'Mahihiling sa babâ an lista kan kaaagi pa sanang mga pagparâ asin mga binagat na may mga laog na pigtatago sa mga Sysops.
Hilingon tabî an [[Special:Ipblocklist|lista nin binagat na IP]] para sa lista kan mga presenteng pagbawal asin pagbagat.',

# Diffs
'history-title'             => 'Historial kan pagriribay kan "$1"',
'difference'                => '(Kaibhán kan mga pagpakarhay)',
'loadingrev'                => 'Pigkakargá an pagkakaráy para sa diff',
'lineno'                    => 'Linya $1:',
'editcurrent'               => 'Hirahón an presenteng bersión kaining páhina',
'selectnewerversionfordiff' => 'Pilîon an mas bâgong bersyon para sa pagkokomparar',
'selectolderversionfordiff' => 'Pilîon an mas lumang bersyon para sa pagkokomparar',
'compareselectedversions'   => 'Ikomparar an mga piniling bersyon',
'editundo'                  => 'ibalik',
'diff-multi'                => '({{PLURAL:$1|One intermediate revision|$1 intermediate revisions}} dai ipinahihiling.)',

# Search results
'searchresults'         => 'Hanapon an mga resulta',
'searchresulttext'      => 'Para sa iba pang impormasyon manonongod sa paghanap sa {{SITENAME}}, hilingon tabî an [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'        => "Hinanap mo an '''[[:$1]]'''",
'searchsubtitleinvalid' => "Hinanap mo an '''$1'''",
'noexactmatch'          => "'''Mayong siring na páhinang may títulong \"\$1\".''' Pwede mong [[:\$1|gibohon ining páhina]].",
'titlematches'          => 'Angay an título kan artíkulo',
'notitlematches'        => 'Mayong ángay na título nin páhina',
'textmatches'           => 'Angay an teksto nin páhina',
'notextmatches'         => 'Mayong ángay na teksto nin páhina',
'prevn'                 => 'dati $1',
'nextn'                 => 'sunod $1',
'viewprevnext'          => 'Hilingón ($1) ($2) ($3)',
'showingresults'        => "Pigpapahiling sa babâ sagkod sa {{PLURAL:$1|'''1''' resulta|'''$1''' mga resulta}} poon sa #'''$2'''.",
'showingresultsnum'     => "Pigpapahiling sa babâ {{KADKLAN:$3|'''1''' resulta|'''$3''' mga resulta}} poon sa #'''$2'''.",
'nonefound'             => "'''Pagiromdom''': An mga prakasong paghanap pirmeng kawsa kan paghanap kan mga tataramon na komún arog kan \"may\" asin \"sa\", huli ta an mga ini dai nakaíndise, o sa pagpili kan sobra sa sarong tataramon (an mga páhina sana na igwá kan gabos na pighahanap na tataramon an maipapahiling sa resulta).",
'powersearch'           => 'Hanápon',
'powersearchtext'       => 'Hanápon sa mga ngaran-espacio:<br />$1<br />$2 Ilista an mga redirekta<br />Hanápon an $3 $9',
'searchdisabled'        => 'Pigpopogolan mûna an paghanap sa {{SITENAME}}. Mientras tanto, pwede ka man maghanap sa Google. Giromdomon tabî na an mga indise kan laog ninda sa {{SITENAME}} pwede ser na lumâ na.',

# Preferences page
'preferences'              => 'Mga kabôtan',
'mypreferences'            => 'Mga kabôtan ko',
'prefs-edits'              => 'Bilang kan mga hirá:',
'prefsnologin'             => 'Dai nakalaog',
'prefsnologintext'         => 'Ika dapat si [[Special:Userlogin|nakalaog]] para makapwesto nin mga kabôtan nin parágamit.',
'prefsreset'               => 'Napwesto giraray an mga kabôtan hali sa bodega.',
'qbsettings'               => "''Quickbar''",
'qbsettings-none'          => 'Mayô',
'qbsettings-fixedleft'     => 'Nakatakód sa walá',
'qbsettings-fixedright'    => 'Nakatakód sa tûo',
'qbsettings-floatingleft'  => 'Naglálatáw sa walá',
'qbsettings-floatingright' => 'Naglálatáw sa tûo',
'changepassword'           => 'Ribayan an sekretong panlaog',
'skin'                     => "''Skin''",
'math'                     => 'Mat',
'dateformat'               => "''Format'' kan petsa",
'datedefault'              => 'Mayong kabôtan',
'datetime'                 => 'Petsa asin oras',
'math_failure'             => 'Nagprakaso an pagatíd-atíd',
'math_unknown_error'       => 'dai aram an salâ',
'math_unknown_function'    => 'Dai aram an gamit',
'math_lexing_error'        => 'may salâ sa analisador léxico',
'math_syntax_error'        => 'may salâ sa analisador nin sintaksis',
'math_image_error'         => 'Nagprakaso an konbersyon kan PNG; sosogon tabî an pagkaag nin latex, dvips, gs, asin ikonbertir',
'math_bad_tmpdir'          => 'Dai masuratan o magibo an direktoryo nin mat temp',
'math_bad_output'          => 'Dai masuratan o magibo an direktoryo kan salida nin math',
'math_notexvc'             => 'May nawawarang texvc na ehekutable; hilingón tabî an mat/README para makonpigurar.',
'prefs-personal'           => 'Pambisto nin parágamit',
'prefs-rc'                 => 'Mga kaaagi pa sanang pagribay',
'prefs-watchlist'          => 'Pigbabantayan',
'prefs-watchlist-days'     => 'Máximong número nin mga aldaw na ipapahiling sa lista nin mga pigbabantayan:',
'prefs-watchlist-edits'    => 'Máximong número nin pagbabâgo na ipapahiling sa pinadakulang lista nin pigbabantayan:',
'prefs-misc'               => 'Lain',
'saveprefs'                => 'Itagama',
'resetprefs'               => 'Ipwesto giraray',
'oldpassword'              => 'Lumang sekretong panlaog:',
'newpassword'              => 'Bâgong sekretong panlaog:',
'retypenew'                => 'Itaták giraray an bâgong panlaog:',
'textboxsize'              => 'Pighihira',
'rows'                     => 'Mga hilera:',
'columns'                  => 'Mga taytay:',
'searchresultshead'        => 'Hanápon',
'resultsperpage'           => 'Mga tamâ kada páhina:',
'contextlines'             => 'Mga linya kada tamâ:',
'contextchars'             => 'Konteksto kada linya:',
'stub-threshold'           => 'Kasagkoran kan <a href="#" class="stub">takod kan tambô</a> pigpopormato:',
'recentchangesdays'        => 'Mga aldáw na ipapahiling sa mga kaaaging pa sanang pagbabâgo:',
'recentchangescount'       => 'Número nin mga paghirá na ipapahiling sa mga kaaaging pa sanang pagbabâgo:',
'savedprefs'               => 'Itinagama na an mga kabôtan mo.',
'timezonelegend'           => 'Zona nin oras',
'timezonetext'             => "Bilang nin mga oras na ibá an saimong oras lokal sa oras kan ''server'' (UTC).",
'localtime'                => 'Lokal na oras',
'servertime'               => "Oras kan ''server''",
'guesstimezone'            => "Bugtakan an ''browser''",
'allowemail'               => "Togotan an mga ''e''-surat halî sa ibang mga parágamit",
'defaultns'                => 'Maghilíng mûna sa ining mga ngaran-espacio:',
'default'                  => 'pwestong normal',
'files'                    => 'Mga dokumento',

# User rights
'userrights-lookup-user'      => 'Magmaného kan mga grupo nin parágamit',
'userrights-user-editname'    => 'Ilaog an pangaran kan parágamit:',
'editusergroup'               => 'Hirahón an mga Grupo kan Parágamit',
'userrights-editusergroup'    => 'Hirahón an mga grupo kan parágamit',
'saveusergroups'              => 'Itagama an mga Grupo nin Páragamit',
'userrights-groupsmember'     => 'Myembro kan:',
'userrights-groupsavailable'  => 'Mga grupong dispuesto:',
'userrights-groupshelp'       => 'Pilion tabî an mga grupong boot mong halîon o idugang ining parágamit.
Dai babâgohon an mga grupong dai pigpili. Pwede mong bawîon an pagpili sa pagpindot kan CTRL + Left',
'userrights-reason'           => 'Rasón kan pagribay:',
'userrights-available-none'   => 'Dai mo puedeng hirâon an grupo nin mga miembro.',
'userrights-available-add'    => 'Pwede kang magdugang nin mga parágamit sa $1.',
'userrights-available-remove' => 'Puwede kang magtanggal nin mga parágamit sa $1.',

# Groups
'group'               => 'Grupo:',
'group-autoconfirmed' => 'Paragamit na sadiring nagkonpirma',
'group-bot'           => 'Mga bots',
'group-sysop'         => 'Mga sysop',
'group-bureaucrat'    => 'Mga bureaucrat',
'group-all'           => '(gabos)',

'group-autoconfirmed-member' => 'Enseguidang nakonpirmar na parágamit',
'group-sysop-member'         => 'Opsys',
'group-bureaucrat-member'    => 'Bureaucrat',

'grouppage-autoconfirmed' => '{{ns:project}}:Mga enseguidang nakonpirmar na parágamit',
'grouppage-bot'           => '{{ns:project}}:Mga bot',
'grouppage-sysop'         => '{{ns:project}}:Mga administrador',
'grouppage-bureaucrat'    => '{{ns:project}}:Mga bureaucrat',

# User rights log
'rightslog'      => 'Historial kan mga derecho nin parágamit',
'rightslogtext'  => 'Ini an historial kan mga pagbabâgo sa mga derecho nin parágamit.',
'rightslogentry' => 'Rinibayab an pagkamyembro ni $1 sa $2 sagkod sa $3',
'rightsnone'     => '(mayô)',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|pagbabâgo|mga pagbabâgo}}',
'recentchanges'                     => 'Mga kaaaging pa sanang pagbabâgo',
'recentchangestext'                 => 'Hanapon an mga pinahuring pagbabâgo sa wiki digdi sa páhinang ini.',
'recentchanges-feed-description'    => 'Hanapon an mga pinakahuring pagbabâgo sa wiki sa ining hungit.',
'rcnote'                            => "Mahihiling sa baba an {{PLURAL:$1| '''1''' pagbabago|mga '''$1''' na pagbabago}} sa huring {{PLURAL:$2|na aldaw|'''$2''' mga aldaw}}, sa $3.",
'rcnotefrom'                        => 'Sa babâ mahihiling an mga pagbabâgo desde <b>$2</b> (hasta <b>$1</b> ipinahihiling).',
'rclistfrom'                        => 'Ipahiling an mga pagbabâgo poon sa $1',
'rcshowhideminor'                   => '$1 mga menor na hirá',
'rcshowhidebots'                    => '$1 mga bot',
'rcshowhideliu'                     => '$1 mga nakadágos na parágamit',
'rcshowhideanons'                   => '$1 Mga paragamit na anónimo',
'rcshowhidepatr'                    => '$1 pigbabantayan na mga hirá',
'rcshowhidemine'                    => '$1 mga hirá ko',
'rclinks'                           => 'Ipahiling an mga huring $1 na pagbabâgo sa ultimong $2 na aldáw<br />$3',
'diff'                              => 'ibá',
'hide'                              => 'Tagôon',
'show'                              => 'Ipahiling',
'newpageletter'                     => 'B',
'number_of_watching_users_pageview' => '[$1 nagbabantay na parágamit]',
'rc_categories'                     => 'Limitado sa mga kategorya (suhayon nin "|")',
'rc_categories_any'                 => 'Maski arin',
'newsectionsummary'                 => '/* $1 */ bâgong sección',

# Recent changes linked
'recentchangeslinked'          => 'Mga angay na pagbabâgo',
'recentchangeslinked-title'    => 'Mga pagbabâgong angay sa $1',
'recentchangeslinked-noresult' => 'Warang mga pagbabago sa mga pahinang nakatakod sa itinaong pagkalawig.',
'recentchangeslinked-summary'  => "Linista kaining especial na páhina an mga huring pagbabago sa mga páhinang nakatakod. An mga páhinang pigbabantayan mo nakasurat na  '''maitom'''.",

# Upload
'upload'                      => "Ikargá an ''file''",
'uploadbtn'                   => "Ikargá an ''file''",
'reupload'                    => 'Ikargá giraray',
'reuploaddesc'                => 'Magbalik sa pormulario kan pagkarga.',
'uploadnologin'               => 'Dai nakalaog',
'uploadnologintext'           => "Kaipuhan ika si [[Special:Userlogin|nakadagos]]
para makakarga nin mga ''file''.",
'upload_directory_read_only'  => 'An directoriong pagkarga na ($1) dai puedeng suratan kan serbidor nin web.',
'uploaderror'                 => 'Salâ an pagkarga',
'uploadtext'                  => "Gamiton tabî an pormulario sa babâ para magkarga nin mga ''file'', para maghiling o maghanap kan mga ladawan na dating kinarga magduman tabi sa [[Special:Imagelist|lista nin mga pigkargang ''file'']], an mga kinarga asin mga pinarâ nakalista man sa [[Special:Log/upload|historial nin pagkarga]].

Kun boot mong ikaag an ladawan sa páhina, gamiton tabî an takod arog kan
'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:File.jpg]]</nowiki>''',
'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:File.png|alt text]]</nowiki>''' o
'''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki>''' para sa direktong pagtakod sa ''file''.",
'uploadlog'                   => 'historial nin pagkarga',
'uploadlogpage'               => 'Ikarga an historial',
'uploadlogpagetext'           => "Mahihiling sa babâ an lista kan mga pinakahuring ''file'' na kinarga.",
'filename'                    => 'Pangaran kan dokumento',
'filedesc'                    => 'Kagabsan',
'fileuploadsummary'           => 'Kagabsan:',
'filestatus'                  => 'Estatutong derechos nin paragamit',
'filesource'                  => 'Ginikanan',
'uploadedfiles'               => "Mga ''file'' na ikinargá",
'ignorewarning'               => "Dai pagintiendehon an mga patanid asin itagama pa man an ''file''.",
'ignorewarnings'              => 'Paliman-limanon an mga tanid',
'minlength1'                  => "An pangaran kan mga ''file'' dapat na dai mababâ sa sarong letra.",
'illegalfilename'             => "An ''filename'' na \"\$1\" igwang mga ''character'' na dai pwede sa mga titulo nin páhina. Tâwan tabî nin bâgong pangaran an ''file'' asin probaran na ikarga giraray.",
'badfilename'                 => "Rinibayan an ''filename'' nin \"\$1\".",
'filetype-badmime'            => "Dai pigtotogotan na ikarga an mga ''file'' na MIME na \"\$1\" tipo.",
'filetype-badtype'            => "An'''\".\$1\"''' sarong bawal na tipo nin ''file''
: Lista kan mga pigtotogotan na tipo nin ''file'': \$2",
'filetype-missing'            => "Mayong ekstensyón an ''file'' (arog kan \".jpg\").",
'large-file'                  => "Pigrerekomendár na dapat an mga ''file'' bakong mas dakula sa $1; $2 an sokol kaining ''file''.",
'largefileserver'             => "Mas dakula an ''file'' sa pigtotogotan na sokol kan ''server''.",
'emptyfile'                   => "Garo mayong laog an ''file'' na kinarga mo. Pwede ser na salâ ining tipo nin ''filename''. Isegurado tabî kun talagang boot mong ikarga ining ''file''.",
'fileexists'                  => "Igwa nang ''file'' na may parehong pangaran sa ini, sosogon tabî an <strong><tt>$1</tt></strong> kun dai ka seguradong ribayan ini.",
'fileexists-extension'        => "May ''file'' na may parehong pangaran:<br />
Pangaran kan pigkakargang ''file'': <strong><tt>$1</tt></strong><br />
Pangaran kan yaon nang ''file'': <strong><tt>$2</tt></strong><br />
Magpili tabî nin ibang pangaran.",
'fileexists-thumb'            => "<center>'''Presenteng ladawan'''</center>",
'fileexists-thumbnail-yes'    => "An ''file'' garo ladawan kan pinasadit <i>(thumbnail)</i>. Sosogon tabî an ''file'' <strong><tt>$1</tt></strong>.<br />
Kun an sinosog na ''file'' iyo an parehong ladawan na nasa dating sokol, dai na kaipuhan magkarga nin iba pang retratito.",
'file-thumbnail-no'           => "An ''filename'' nagpopoon sa <strong><tt>$1</tt></strong>. Garo ladawan na pinasadit ini <i>(thumbnail)</i>.
Kun igwa ka nin ladawan na may resolusyón na maximo ikarga tabî ini, kun dai, bâgohon tabî an pangaran nin ''file''.",
'fileexists-forbidden'        => "Igwa nang ''file'' na may parehong pangaran; bumalik tabi asin ikarga an ''file'' sa bâgong pangaran [[Image:$1|thumb|center|$1]]",
'fileexists-shared-forbidden' => "Igwa nang ''file'' na may parehong pangaran sa repositoryo nin mga bakas na ''file''; bumalik tabî asin ikarga an ''file'' sa bâgong pangaran. [[Image:$1|thumb|center|$1]]",
'successfulupload'            => 'Nakarga na',
'uploadwarning'               => 'Patanid sa pagkarga',
'savefile'                    => "Itagama an ''file''",
'uploadedimage'               => 'Ikinarga "[[$1]]"',
'overwroteimage'              => 'kinarga an bagong bersión kan "[[$1]]"',
'uploaddisabled'              => 'Pigpopondó an mga pagkargá',
'uploaddisabledtext'          => "Pigpopogolan an pagkarga nin mga ''file'' o sa ining wiki.",
'uploadscripted'              => "Ining ''file'' igwang HTML o kodang eskritura na pwede ser na salang mainterpretar kan ''browser''.",
'uploadcorrupt'               => "Raot ining ''file'' o igwang ekstensyón na salâ. Sosogon tabî an ''file'' asin ikarga giraray.",
'uploadvirus'                 => "May virus an ''file''! Mga detalye: $1",
'sourcefilename'              => 'Ginikanan kan pangaran kan dokumento',
'destfilename'                => "''Filename'' kan destinasyón",
'watchthisupload'             => 'Bantayan ining pahina',
'filewasdeleted'              => "May sarong ''file'' na kapangaran kaini na dating pigkarga tapos pigparâ man sana. Sosogon muna tabî an $1 bago ikarga giraray ini.",
'upload-wasdeleted'           => "'''Patanid: Pigkakarga mo an ''file'' na dati nang pigparâ.'''

Isipon tabi kun maninigo an pagkarga giraray kaini.
An historial nin pagparâ kan ''file'' nakakaag digdi para sa konbenyensya:",
'filename-bad-prefix'         => "An pangaran nin ''file'' na pigkakarga mo nagpopoon sa <strong>\"\$1\"</strong>, sarong pangaran na dai makapaladawan na normalmente enseguidang pigtatao kan mga kamerang digital. Magpili tabî nin pangaran nin ''file'' na mas makapaladawan.",

'upload-proto-error'      => 'Salang protocolo',
'upload-proto-error-text' => 'An pagkargang panharayo kaipuhan nin mga URLs na nagpopoon sa  <code>http://</code> o <code>ftp://</code>.',
'upload-file-error'       => 'Panlaog na salâ',
'upload-file-error-text'  => "May panlaog na salâ kan pagprobar na maggibo nin temporaryong ''file'' sa ''server''.  Apodon tabî an administrador nin sistema.",
'upload-misc-error'       => 'Dai naaaram na error sa pagkarga',
'upload-misc-error-text'  => 'May salang panyayari na dai aram kan pagkarga.  Sosogon tabî kun tamâ an URL asin probaran giraray.  Kun an problema nagpeperseguir, apodon tabî an sarong administrador nin sistema.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Dai naabot an URL',
'upload-curl-error6-text'  => 'Dai nabukas an URL na tinao.  Susugon tabi giraray na an  URL tama asin an sitio bakong raot.',
'upload-curl-error28'      => 'sobra na an pagkalawig kan pagkarga',
'upload-curl-error28-text' => 'Sobrang haloy an pagsimbag kan sitio. Susugon tabi na nagaandar an sitio, maghalat nin muna asin iprobar giraray. Tibaad moot mong magprobar sa panahon na bako masiadong okupado.',

'license'            => 'Paglilisensia',
'nolicense'          => 'Mayong pigpilî',
'license-nopreview'  => '(Mayong patânaw)',
'upload_source_url'  => ' (sarong tama, na bukas sa publikong URL)',
'upload_source_file' => " (sarong ''file'' sa kompyuter mo)",

# Image list
'imagelist'                 => 'Lista kan dokumento',
'imagelisttext'             => "Mahihiling sa baba an lista nin mga  '''$1''' {{PLURAL:$1|file|files}} na linain $2.",
'getimagelist'              => 'pigkukûa an lista kan dokumento',
'ilsubmit'                  => 'Hanápon',
'showlast'                  => "Ipahiling an mga huring $1 ''file'' na linain $2.",
'byname'                    => 'sa ngaran',
'bydate'                    => 'sa petsa',
'bysize'                    => 'sa sukol',
'imgdelete'                 => 'parâ',
'imgfile'                   => 'dokumento',
'filehist'                  => "Historya kan ''file''",
'filehist-help'             => "Magpindot kan petsa/oras para mahiling an hitsura kan ''file'' sa piniling oras.",
'filehist-deleteall'        => 'parâon gabos',
'filehist-deleteone'        => 'parâon ini',
'filehist-revert'           => 'ibalik',
'filehist-current'          => 'sa presente',
'filehist-datetime'         => 'Petsa/Oras',
'filehist-user'             => 'Parágamit',
'filehist-dimensions'       => 'Mga dimensyón',
'filehist-filesize'         => "Sokol nin ''file''",
'filehist-comment'          => 'Komento',
'imagelinks'                => 'Mga takód',
'linkstoimage'              => 'An mga minasunod na pahina nakatakod sa dokumentong ini:',
'nolinkstoimage'            => 'Mayong mga pahinang nakatakod sa dokumentong ini.',
'sharedupload'              => "Ining ''file'' sarong bakas na pagkarga asin pwede ser na gamiton kan ibang mga proyekto.",
'shareduploadwiki'          => 'Hilingon tabi an $1 para sa iba pang talastas.',
'shareduploadwiki-linktext' => "páhina nin descripsyón kan ''file''",
'noimage'                   => "Mayong ''file'' na may pangaran na arog kaini, pwede kang $1.",
'noimage-linktext'          => 'ikarga ini',
'uploadnewversion-linktext' => "Magkarga nin bagong bersyón kaining ''file''",
'imagelist_date'            => 'Petsa',
'imagelist_name'            => 'Pangaran',
'imagelist_user'            => 'Parágamit',
'imagelist_size'            => 'Sukol',
'imagelist_description'     => 'Deskripsión',
'imagelist_search_for'      => 'Hanápon an pangaran kan retrato:',

# File reversion
'filerevert'                => 'Ibalik an $1',
'filerevert-legend'         => 'Ibalik an dokumento',
'filerevert-intro'          => '<span class="plainlinks">Pigbabalik mo an \'\'\'[[Media:$1|$1]]\'\'\' sa [$4 version as of $3, $2].</span>',
'filerevert-comment'        => 'Komento:',
'filerevert-defaultcomment' => 'Pigbalik sa bersyon sa ngonyan $2, $1',
'filerevert-submit'         => 'Ibalik',
'filerevert-success'        => '<span class="plainlinks">\'\'\'[[Media:$1|$1]]\'\'\' binalik sa [$4 version as of $3, $2].</span>',
'filerevert-badversion'     => "Mayong dating bersyón na lokal kaining ''file'' na may taták nin oras na arog sa tinao.",

# File deletion
'filedelete'             => 'Parâon an $1',
'filedelete-legend'      => 'Parâon an dokumento',
'filedelete-intro'       => "Pigpaparâ mo an '''[[Media:$1|$1]]'''.",
'filedelete-intro-old'   => '<span class="plainlinks">Pigpaparâ mo an bersyon kan \'\'\'[[Media:$1|$1]]\'\'\' sa ngonyan [$4 $3, $2].</span>',
'filedelete-comment'     => 'Komento:',
'filedelete-submit'      => 'Parâon',
'filedelete-success'     => "An '''$1''' pinarâ na.",
'filedelete-success-old' => '<span class="plainlinks">An bersyón kan \'\'\'[[Media:$1|$1]]\'\'\' na ngonyan na $3, pigparâ na an $2.</span>',
'filedelete-nofile'      => "Mayo man an '''$1''' sa ining sitio.",
'filedelete-nofile-old'  => "Mayong bersyón na nakaarchibo kan '''$1''' na igwang kan mga piniling ''character''.",
'filedelete-iscurrent'   => "Pigpoprobaran mong parâon an pinahuring bersyón kaining ''file''. Ibalik tabî muna sa bersyón na mas lumâ.",

# MIME search
'mimesearch'         => 'Paghanap kan MIME',
'mimesearch-summary' => "An gamit kaining páhina sa pagsasarâ kan mga ''file'' segun sa mga tipo nin MIME. Input: contenttype/subtype, e.g. <tt>image/jpeg</tt>.",
'mimetype'           => 'tipo nin MIME:',
'download'           => 'ideskarga',

# Unwatched pages
'unwatchedpages' => 'Dai pigbabantayan na mga pahina',

# List redirects
'listredirects' => 'Lista kan mga redirekta',

# Unused templates
'unusedtemplates'     => 'Mga templatong dai ginamit',
'unusedtemplatestext' => 'Piglilista kaining páhina an gabos na mga páhina sa templatong ngaran-espacio na dai nakakaag sa ibang páhina. Giromdomon tabî na sosogon an ibang mga takod sa mga templato bâgo parâon iyan.',
'unusedtemplateswlh'  => 'ibang mga takod',

# Random redirect
'randomredirect'         => 'Random na pagredirekta',
'randomredirect-nopages' => 'Mayong paglikay (redirects) didgi sa ngaran-espacio.',

# Statistics
'statistics'             => 'Mga Estadistiko',
'sitestats'              => 'Mga estadistiko kan {{SITENAME}}',
'userstats'              => 'Mga estadistiko nin parágamit',
'sitestatstext'          => "Igwang {{PLURAL:\$1|nin '''1''' páhina|nin '''\$1''' mga páhina}} sa enterong base nin datos.
Kabali digdi an mga páhinang \"olay\", mga páhinang manonongod sa {{SITENAME}}, mga \"tamboan\"
na páhina, mga redirekta, asin mga iba pang dai pigbibilang na mga páhinang may laog.
Kun dai bibilangon an mga ini, igwang {{PLURAL:\$2|nin '''1''' páhina|nin '''\$2''' na mga páhina}} gayod na talagang {{PLURAL:\$2|páhinang|mga páhinang}} may laog .

'''\$8''' {{PLURAL:\$8|''file''|mga ''file''}} an kinarga.

'''\$3''' {{PLURAL:\$3|na paghiling|mga paghiling}} an mga total na paghiling, asin '''\$4''' {{PLURAL:\$4|na hirá kan páhina|mga hirá kan páhina}}
despues sa pagbukas kan {{SITENAME}}.
Maabot sa '''\$5''' na hira kada páhina sa medio, asin '''\$6''' na paghiling kada hirá.

'''\$7''' an labâ kan [http://meta.wikimedia.org/wiki/Help:Job_queue job queue].",
'userstatstext'          => "{{PLURAL:$1|is '''1''' registered [[Special:mga paragamit|paragamit]]| '''$1''' an nakarehistrong [[Special:Listusers|users]]}}, 
'''$2''' (or '''$4%''') kaini {{PLURAL:$2|has|may}} $5 na derechos.",
'statistics-mostpopular' => 'mga pinaka pighiling na pahina',

'disambiguations'      => 'Mga páhinang klaripikasyón',
'disambiguationspage'  => 'Template:clarip',
'disambiguations-text' => "An mga nasunod na páhina nakatakod sa sarong '''páhina nin klaripikasyon'''. Imbis, kaipuhan na nakatakod sinda sa maninigong tema.<br />An páhina pigkokonsiderar na páhina nin klaripikasyon kun naggagamit ini nin templatong nakatakod sa [[MediaWiki:disambiguationspage]]",

'doubleredirects'     => 'Dobleng mga redirekta',
'doubleredirectstext' => 'Piglilista kaining pahina an mga pahinang minalikay sa ibang pahinang paralikay. Kada raya may mga takod sa primero asin segundang likay, buda an destino kan segundong likay, na puro-pirme sarong "tunay " na pahinang destino, na dapat duman nakaturo an primerong likay.',

'brokenredirects'        => 'Putol na mga redirekta',
'brokenredirectstext'    => 'An nagsusunod naglilikay kan takod sa mga pahinang mayo man:',
'brokenredirects-edit'   => '(hirahón)',
'brokenredirects-delete' => '(parâon)',

'withoutinterwiki'        => 'Mga páhinang warang takód sa mga ibang tataramón',
'withoutinterwiki-header' => 'An mga nagsusunod na páhina dai nakatakód sa mga bersión na ibang tataramón:',

'fewestrevisions' => 'Mga artikulong may pinakadikit na pagpakarhay',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|mga byte}}',
'ncategories'             => '$1 {{PLURAL:$1|kategorya|mga kategorya}}',
'nlinks'                  => '$1 {{PLURAL:$1|takod|mga takod}}',
'nmembers'                => '$1 {{PLURAL:$1|myembro|mga myembro}}',
'nrevisions'              => '$1 {{PLURAL:$1|pagpakarhay|mga pagpakarhay}}',
'nviews'                  => '$1 {{PLURAL:$1|hiling|mga hiling}}',
'specialpage-empty'       => 'Mayong mga resulta para sa report na ini.',
'lonelypages'             => 'Mga solong pahina',
'lonelypagestext'         => 'An mga minasunod na mga páhina dai nakatakod sa ibang mga páhina sa wiki na ini.',
'uncategorizedpages'      => 'Mga dai nakakategoryang páhina',
'uncategorizedcategories' => 'Mga dai nakakategoryang kategorya',
'uncategorizedimages'     => 'Mga dai nakakategoryang retrato',
'uncategorizedtemplates'  => 'Mga templatong mayong kategorya',
'unusedcategories'        => 'Dai gamit na mga kategorya',
'unusedimages'            => 'Mga dokumentong dai nagamit',
'popularpages'            => 'Mga popular na páhina',
'wantedcategories'        => 'Mga hinahanap na kategorya',
'wantedpages'             => 'Mga hinahanap na páhina',
'mostlinked'              => 'Pinaka pigtatakodan kan mga pahina',
'mostlinkedcategories'    => 'Pinaka pigtatakodan kan mga kategorya',
'mostlinkedtemplates'     => 'An mga pinakanatakodan na templato',
'mostcategories'          => 'Mga artikulong may pinaka dakol na kategorya',
'mostimages'              => 'Pinaka pigtatakodan kan mga retrato',
'mostrevisions'           => 'Mga atrikulo na igwang pinakadakol na pagpakarhay',
'allpages'                => 'Gabos na pahina',
'prefixindex'             => 'Indice nin prefiho',
'randompage'              => 'Random na páhina',
'randompage-nopages'      => 'Mayong páhina an ngaran-espacio.',
'shortpages'              => 'Mga halîpot na pahina',
'longpages'               => 'Mga halabang pahina',
'deadendpages'            => 'Mga páhinang mayong paluwasan',
'deadendpagestext'        => 'An mga nagsusunod na pahina dai nakatakod sa mga ibang pahina sa ining wiki.',
'protectedpages'          => 'Mga protektadong pahina',
'protectedpagestext'      => 'An mga minasunod na pahina protektado na ibalyó o hirahón',
'protectedpagesempty'     => 'Mayong pang páhina an napoprotehiran kaining mga parametros.',
'listusers'               => 'Lista kan parágamit',
'specialpages'            => 'Mga espesyal na páhina',
'spheading'               => 'Mga espesyal na páhina para sa gabos na parágamit',
'restrictedpheading'      => 'Mga espesyal na páhinang may restriksyón',
'rclsub'                  => '(sa mga páhina poon sa "$1")',
'newpages'                => 'Mga bâgong páhina',
'newpages-username'       => 'Pangaran kan parágamit:',
'ancientpages'            => 'Mga pinakalumang páhina',
'intl'                    => 'Mga takod sa ibang tataramon',
'move'                    => 'Ibalyó',
'movethispage'            => 'Ibalyó ining páhina',
'unusedimagestext'        => "<p>Giromdomon tabî na an mga ibang ''site'' pwedeng nakatakod sa ladawan na may direktong URL, pues pwede ser na nakalista pa digdi a pesar na ini piggagamit pa.</p>",
'unusedcategoriestext'    => 'Igwa ining mga pahinang kategoria maski mayo man na iba pang pahina o kategoria an naggagamit kaiyan.',
'notargettitle'           => 'Mayong target',
'notargettext'            => 'Dai ka pa nagpili nin pahina o paragamit na muya mong gibohon an accion na ini.',

# Book sources
'booksources'               => 'Ginikanang libro',
'booksources-search-legend' => 'Maghanap nin mga ginikanang libro',
'booksources-go'            => 'Dumanán',
'booksources-text'          => "Mahihiling sa babâ an lista kan mga takod sa ibang ''site'' na nagbenbenta nin mga bâgo asin nagamit nang libro, asin pwede ser na igwa pang mga ibang impormasyon manonongod sa mga librong pighahanap mo:",

'categoriespagetext' => 'Igwa an mga minasunod na kategorya sa ining wiki.',
'data'               => 'Datos',
'userrights'         => 'Pagmaneho kan mga derecho nin paragamit',
'groups'             => 'Mga grupo nin parágamit',
'alphaindexline'     => '$1 sagkod sa $2',
'version'            => 'Bersyón',

# Special:Log
'specialloguserlabel'  => 'Parágamit:',
'speciallogtitlelabel' => 'Titulo:',
'log'                  => 'Mga historial',
'all-logs-page'        => 'Gabos na historial',
'log-search-legend'    => 'Hanapon an  mga historial',
'log-search-submit'    => 'Dumanán',
'alllogstext'          => 'Sinalak na hihilngon kan gabos na historial na igwa sa {{SITENAME}}. Kun boot mong pasaditon an seleksyon magpili tabî nin klase kan historial, ngaran nin parágamit, o páhinang naapektaran.',
'logempty'             => 'Mayong angay na bagay sa historial.',
'log-title-wildcard'   => 'Hanapon an mga titulong napopoon sa tekstong ini',

# Special:Allpages
'nextpage'          => 'Sunod na páhina ($1)',
'prevpage'          => 'Nakaaging páhina ($1)',
'allpagesfrom'      => 'Ipahiling an mga páhina poon sa:',
'allarticles'       => 'Gabos na mga artikulo',
'allinnamespace'    => 'Gabos na mga páhina ($1 ngaran-espacio)',
'allnotinnamespace' => 'Gabos na mga páhina (na wara sa $1 ngaran-espacio)',
'allpagesprev'      => 'Nakaagi',
'allpagesnext'      => 'Sunod',
'allpagessubmit'    => 'Dumanán',
'allpagesprefix'    => 'Ipahiling an mga páhinang may prepiho:',
'allpagesbadtitle'  => "Dai pwede an tinaong titulo kan páhina o may prefihong para sa ibang tataramon o ibang wiki. Pwede ser na igwa ining sarô o iba pang mga ''character'' na dai pwedeng gamiton sa mga titulo.",
'allpages-bad-ns'   => 'An {{SITENAME}} mayo man na ngaran-espacio na "$1".',

# Special:Listusers
'listusersfrom'      => 'Ipahiling an mga paragamit poon sa:',
'listusers-submit'   => 'Ipahiling',
'listusers-noresult' => 'Mayong nakuang parágamit.',

# E-mail user
'mailnologin'     => 'Mayong direksyón nin destino',
'mailnologintext' => "Kaipuhan ika si [[Special:Userlogin|nakalaog]]
asin may marhay na ''e''-surat sa saimong [[Special:Preferences|Mga kabôtan]]
para makapadara nin ''e''-surat sa ibang parágamit.",
'emailuser'       => "''e''-suratan ining parágamit",
'emailpage'       => 'E-suratan an parágamit',
'emailpagetext'   => "Kun ining páragamit nagkaag nin marhay ''e''-surat sa saiyang mga kabôtan, an pormulario sa babâ mapadara nin sarong mensahe.
An kinaag mong ''e''-surat sa saimong mga kabôtan nin paragamit mahihiling bilang na \"Hali ki\" kan ''e''-surat, para an recipiente pwedeng makasimbag.",
'usermailererror' => 'Error manonongod sa korreong binalik:',
'defemailsubject' => '{{SITENAME}} e-surat',
'noemailtitle'    => "Mayô nin ''e''-surat",
'noemailtext'     => 'Dai nagpili nin tama na direccion nin e-surat an paragamit,
o habo magresibo nin e-surat sa ibang paragamit.',
'emailfrom'       => 'Poon',
'emailto'         => 'Hasta',
'emailsubject'    => 'Tema',
'emailmessage'    => 'Mensahe',
'emailsend'       => 'Ipadara',
'emailccme'       => 'E-suratan ako nin kopya kan mga mensahe ko.',
'emailccsubject'  => 'Kopya kan saimong mensahe sa $1: $2',
'emailsent'       => 'Naipadará na an e-surat',
'emailsenttext'   => 'Naipadará na su e-surat mo.',

# Watchlist
'watchlist'            => 'Pigbabantayan ko',
'mywatchlist'          => 'Pigbabantayan ko',
'watchlistfor'         => "(para sa '''$1''')",
'nowatchlist'          => 'Mayo ka man na mga bagay saimong lista nin pigbabantayan.',
'watchlistanontext'    => 'Mag $1 tabi para mahiling o maghira nin mga bagay saimong lista nin mga pigbabantayan.',
'watchnologin'         => 'Mayô sa laog',
'watchnologintext'     => 'Dapat ika si [[Special:Userlogin|nakalaog]] para puede kang magribay kan saimong lista nin mga pigbabantayán.',
'addedwatch'           => 'Idinugang sa pigbabantayan',
'addedwatchtext'       => "Ining pahina \"[[:\$1]]\" dinugang sa saimong mga [[Special:Watchlist|Pigbabantayan]].
An mga pagbabâgo sa páhinang ini asin sa mga páhinang olay na kapadis kaini ililista digdi,
asin an páhina isusurat nin '''mahîbog''' sa [[Special:Recentchanges|lista nin mga kaaagi pa sanang pagbabâgo]] para madalî ining mahiling.

Kun boot mong halîon an páhina sa pigbabantayan mo sa maabot na panahon, pindoton an \"Pabayaan\" ''side bar''.",
'removedwatch'         => 'Pigtanggal sa pigbabantayan',
'removedwatchtext'     => 'An pahinang "[[:$1]]" pigtanggal sa saimong pigbabantayan.',
'watch'                => 'Bantayan',
'watchthispage'        => 'Bantayan ining pahina',
'unwatch'              => 'Dai bantayan',
'unwatchthispage'      => 'Pondohon an pagbantay',
'notanarticle'         => 'Bakong páhina nin laog',
'watchnochange'        => 'Mayo sa saimong mga pigbabantayan an nahira sa laog nin pinahiling na pagkalawig.',
'watchlist-details'    => '{{PLURAL:$1|$1 páhina|$1 mga páhina}} an pigbabantayan dai kabali an mga olay na páhina.',
'wlheader-enotif'      => "* Nakaandar an paising ''e''-surat.",
'wlheader-showupdated' => "* An mga páhinang pigbâgo poon kan huri mong bisita nakasurat nin '''mahîbog'''",
'watchmethod-recent'   => 'Pigsososog an mga kaaagi pa sanang hirá sa mga pigbabantayan na páhina',
'watchmethod-list'     => 'Pigsososog an mga pigbabantayan na páhina para mahiling an mga kaaagi pa sanan paghirá',
'watchlistcontains'    => 'An saimong lista nin pigbabantayan igwang $1 na {{PLURAL:$1|páhina|mga páhina}}.',
'iteminvalidname'      => "May problema sa bagay na '$1', salâ an pangaran...",
'wlnote'               => "Mahihiling sa babâ an {{PLURAL:$1|huring pagriribay|mga huring'''$1''' pagriribay}} sa ultimong {{PLURAL:$2|oras|'''$2''' mga oras}}.",
'wlshowlast'           => 'Ipahiling an ultimong $1 na oras $2 na aldaw $3',
'watchlist-show-bots'  => 'Ipahiling an mga paghirá kan mga bot',
'watchlist-hide-bots'  => 'Tagôon mga paghirá kan mga bot',
'watchlist-show-own'   => 'Ipahiling an mga hira ko',
'watchlist-hide-own'   => 'Tagôon an mga hira ko',
'watchlist-show-minor' => 'Ipahiling an mga menor na hirá',
'watchlist-hide-minor' => 'Tagôon an mga menor na hirá',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Pigbabantayan...',
'unwatching' => 'Dai pigbabantayan...',

'enotif_mailer'                => '{{SITENAME}} Kartero nin isi',
'enotif_reset'                 => 'Markahan an gabos na mga binisitang pahina',
'enotif_newpagetext'           => 'Bâgo ining pahina.',
'enotif_impersonal_salutation' => '{{SITENAME}} parágamit',
'changed'                      => 'pigbâgo',
'created'                      => 'piggibo',
'enotif_subject'               => 'An pahinang {{SITENAME}} na $PAGETITLE binago $CHANGEDORCREATED ni $PAGEEDITOR',
'enotif_lastvisited'           => 'Hilingón an $1 para sa gabos na mga pagbâgo poon kan huring bisita.',
'enotif_lastdiff'              => 'Hilingón an $1 tangarig mahiling an pagbâgong ini.',
'enotif_anon_editor'           => 'dai bistong parágamit $1',
'enotif_body'                  => 'Namómòtan na $WATCHINGUSERNAME,
	

An páhinang {{SITENAME}} na $PAGETITLE binâgo $CHANGEDORCREATED sa $PAGEEDITDATE ni $PAGEEDITOR, hilingón an $PAGETITLE_URL para sa presenteng bersyón.

$NEWPAGE

Sumáda kan editor: $PAGESUMMARY $PAGEMINOREDIT

Apodon an editor:
\'\'e\'\'-surat: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Mayô nang iba pang paisi na ipapadara dapit sa iba pang mga pagbabâgo kun dai mo bibisitahon giraray ining páhina. Pwede mo man na ipwesto giraray an mga patanid para sa saimong mga páhinang pigbabantayan duman sa saimong lista nin pigbabantayan.

             An maboot na sistema nin paisi kan {{SITENAME}} 

--
Para bâgohon an pagpwesto kan saimong mga pigbabantayan, bisitahon an
{{fullurl:{{ns:special}}:Watchlist/edit}}

Komentaryo asin iba pang tabang:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete/protect/revert
'deletepage'                  => 'Parâon an pahina',
'confirm'                     => 'Kompermaron',
'excontent'                   => "Ini an dating laog: '$1'",
'excontentauthor'             => "ini an dating laog: '$1' (asin an unikong kontribuidor si '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'               => "Ini an dating laog bagô blinankohán: '$1'",
'exblank'                     => 'mayong laog an páhina',
'confirmdelete'               => 'Konpirmaron an pagparâ',
'deletesub'                   => '(Pigpaparâ an "$1")',
'historywarning'              => 'Patanid: An pahinang paparâon mo igwa nin historya.',
'confirmdeletetext'           => 'Paparâon mo sa base nin datos ining páhina
o ladawan kasabay an gabos na mga historya kaini.
Konpirmaron tabî na talagang boot mong gibohon ini, nasasabotan mo an mga resulta, asin an piggigibo mo ini konporme sa
[[{{MediaWiki:Policy-url}}]].',
'actioncomplete'              => 'Nagibo na',
'deletedtext'                 => 'Pigparâ na an "$1" .
Hilingón tabî an $2 para mahiling an lista nin mga kaaagi pa sanang pagparâ.',
'deletedarticle'              => 'pigparâ "[[$1]]"',
'dellogpage'                  => 'Historial nin pagparâ',
'dellogpagetext'              => 'Mahihiling sa babâ an lista kan mga pinakahuring pagparâ.',
'deletionlog'                 => 'Historial nin pagparâ',
'reverted'                    => 'Ibinalik sa mas naenot na pagpakarhay',
'deletecomment'               => 'Rasón sa pagparâ',
'rollback'                    => 'Mga paghihira na pabalík',
'rollback_short'              => 'pabalík',
'rollbacklink'                => 'pabalík',
'rollbackfailed'              => 'Prakaso an pagbalík',
'cantrollback'                => 'Dai pwedeng bawîon an hirá; an huring kontribuidor iyo an unikong parásurat kan páhina.',
'alreadyrolled'               => 'Dai pwedeng ibalik an huring hirá kan [[:$1]]
ni [[User:$2|$2]] ([[User talk:$2|Olay]]); may ibang parágamit na naghirá na o nagbalik na kaini.

Huring hirá ni [[User:$3|$3]] ([[User talk:$3|Olay]]).',
'editcomment'                 => 'Ini an nakakaag na komentaryo sa paghirá: "<i>$1</i>".', # only shown if there is an edit comment
'revertpage'                  => 'Binawî na mga paghirá kan [[Special:Contributions/$2|$2]] ([[User talk:$2|Magtaram]]); pigbalik sa dating bersyón ni [[User:$1|$1]]',
'rollback-success'            => 'Binawî na mga paghirá ni $1; pigbalik sa dating bersyón ni $2.',
'sessionfailure'              => "Garo may problema sa paglaog mo;
kinanselár ining aksyón bilang sarong paglikay kontra sa ''session hijacking''.
Pindotón tabî an \"back\" asin ikarga giraray an páhinang ginikanan mo, dangan probarán giraray.",
'protectlogpage'              => 'Historial nin proteksyon',
'protectlogtext'              => 'May lista sa baba nin mga kandado asin panbawi kan kandado kan mga páhina. Hilingon an [[Special:Protectedpages|lista kan mga pigproprotektarán na mga páhina]] para mahiling an lista kan mga proteksión nin mga páhina sa ngunyan na nakabuká.',
'protectedarticle'            => 'protektado "[[$1]]"',
'modifiedarticleprotection'   => 'binago an nibel nin proteksión para sa "[[$1]]"',
'unprotectedarticle'          => 'Warang proteksión an "[[$1]]"',
'protectsub'                  => '(Pigpupuesta an nibel nin proteksión sa "$1")',
'confirmprotect'              => 'Kompermaron an proteksyon',
'protectcomment'              => 'Komento:',
'protectexpiry'               => 'Minápasó:',
'protect_expiry_invalid'      => 'Dai puede ining pahanón kan pagpasó.',
'protect_expiry_old'          => 'Pasado na an panahón nin pagpasó.',
'unprotectsub'                => '(Binabawi an proteksión kan "$1")',
'protect-unchain'             => 'Bawion an kandado sa mga permiso sa pagbalyó',
'protect-text'                => 'Pwede mong hilingón asin bâgohon an tangga nin proteksyón digdi para sa páhina <strong>$1</strong>.',
'protect-locked-blocked'      => 'Dai mo pwedeng bâgohon an mga tangga kan proteksyon mientras na ika nababágat. Ini an mga presenteng pwesto kan páhina <strong>$1</strong>:',
'protect-locked-dblock'       => 'Dai puedeng ibalyo an mga nibel kan proteksión ta may actibong kandado sa base nin datos.
Ini an mga puesta sa ngunyan kaining páhina <strong>$1</strong>:',
'protect-locked-access'       => "An ''account'' mo mayong permiso na magbâgo kan tangga nin proteksyon.
Ini an mga presenteng pwesto kan páhina <strong>$1</strong>:",
'protect-cascadeon'           => 'Ining páhina pigproprotektaran sa ngunyan ta sabay ini sa mga nasunod na {{PLURAL:$1|páhina, na may|mga páhina, na may}} proteksyong katarata na nakaandar. Pwede mong bâgohon an tangga nin proteksyon kaining páhina, pero mayô ning epekto sa proteksyong katarata.',
'protect-default'             => '(normal)',
'protect-fallback'            => 'Mangipo kan "$1" na permiso',
'protect-level-autoconfirmed' => 'Bagatón an mga paragamit na dae nakarehistro',
'protect-level-sysop'         => 'Para sa mga sysop sana',
'protect-summary-cascade'     => 'katarata',
'protect-expiring'            => 'minapasó sa $1 (UTC)',
'protect-cascade'             => 'Protektarán an mga páhinang nakaiba sa páhinang ini (proteksión katarata)',
'restriction-type'            => 'Permiso:',
'restriction-level'           => 'Tángga kan restriksyón:',
'minimum-size'                => 'Pinaka sadit na sukol',
'maximum-size'                => 'Pinaka dakula na sukol',
'pagesize'                    => '(oktetos)',

# Restrictions (nouns)
'restriction-edit' => 'Hirahón',
'restriction-move' => 'Ibalyó',

# Restriction levels
'restriction-level-sysop'         => 'protektado',
'restriction-level-autoconfirmed' => 'semi-protektado',
'restriction-level-all'           => 'maski anong nibel',

# Undelete
'undelete'                     => 'Hilingón ang mga pinarang pahina',
'undeletepage'                 => 'Hilingón asin ibalik an mga pinarang pahina',
'viewdeletedpage'              => 'Hilingón an mga pinarang pahina',
'undeletepagetext'             => 'An mga minasunod na páhina pigparâ na alagad yaon pa sa archibo asin pwedeng ibalik. Dapat limpiahan an archibo kada periodo.',
'undeleteextrahelp'            => "Kun boot mong ibalik an enterong páhina, dai markahan an gabos na mga kahon asin pindoton an '''''Restore'''''. Para magpili nin ibábalik, markahan an mga kahon na boot mong ibalik, asin pindoton an '''''Restore'''''. An pagpindot kan '''''Reset''''' makakalimpya nin kampo kan mga kommento
asin an gabos na mga kahon-marka.",
'undeleterevisions'            => '$1 {{PLURAL:$1|na pagriribay|na mga pagriribay}} na nakaarchibo',
'undeletehistory'              => "Kun ibabalik mo an páhinang ini, an gabos na mga pagribay mabalik sa historial.
Kun igwang piggibong sarong bâgong páhinang may parehong pangaran antes ka pagparâ, an presenteng pagribay maluwas sa historial, asin an presenteng pagribay kan tunay na páhina dai enseguidang mariribayan. Giromdomon man tabî na an mga restriksyon sa mga pagriribay nin ''file'' mawawarâ sa pagbalik.",
'undeleterevdel'               => "Dai madadagos an pagbalik kan pagparâ kun an resulta kaini mapaparâ kan pagribay an nasa páhinang pinaka itaas.
Sa mga kasong ini, dapat halîon an mga marka o dai itâgo an mga pinaka bâgong pigparâ na mga pagribay. Dai ibabalik an mga pagribay kan mga ''file'' na mayo kan permisong hilingon.",
'undeletehistorynoadmin'       => 'Pigparâ na ining péhina. Mahihiling an rason sa epitome sa babâ, kasabay sa mga detalye kan mga parágamit na naghira kaining páhina bago pigparâ. Sa mga administrador sana maipapahiling an mga pagribay sa mismong tekstong ini.',
'undelete-revision'            => 'Pigparâng pagribay ni $3 kan $1 (sa $2):',
'undeleterevision-missing'     => 'Dai pwede o nawawarang pagribay. Pwede ser na salâ an takod, o
binalik an na pagribay o hinalî sa archibo.',
'undeletebtn'                  => 'Ibalik',
'undeletereset'                => 'Ipwesto giraray',
'undeletecomment'              => 'Komento:',
'undeletedarticle'             => 'Ibinalik "[[$1]]"',
'undeletedrevisions'           => '$1 na (mga) pagriribay an binalík',
'undeletedrevisions-files'     => "$1 na (mga) pagribay asin $2 na (mga) binalik na ''file''",
'undeletedfiles'               => "$1 (mga) ''file'' an binalik",
'cannotundelete'               => 'Naprakaso an pagbalik kan pigparâ; pwede ser an binawi an pagparâ kan páhina kan ibang parágamit.',
'undeletedpage'                => "<big>'''binalik na an $1 '''</big>

Ikonsultar an [[Special:Log/delete|historial nin pagparâ]] para mahiling an lista nin mga kaaaging pagparâ asin pagbalik.",
'undelete-header'              => 'Hilingon an [[Special:Log/delete|historial kan pagparâ]] kan mga kaaagi pa sanang pinarang páhina.',
'undelete-search-box'          => 'Hanapón an mga pinarang pahina',
'undelete-search-prefix'       => 'Hilingón an mga pahinang nagpopoon sa:',
'undelete-search-submit'       => 'Hanápon',
'undelete-no-results'          => 'Mayong nahanap na páhinang angay sa archibo kan mga pigparâ.',
'undelete-filename-mismatch'   => "Dai pwedeng bawîon an pagparâ sa ''file'' sa pagkarhay na may tatâk nin oras na $1: dai kapadis an ''filename''",
'undelete-bad-store-key'       => "Dai pwedeng bawîon an pagparâ nin ''file'' na pagpakarhay na may taták nin oras na $1: nawara an ''file'' bago an pagparâ.",
'undelete-cleanup-error'       => "May salâ pagparâ kan ''file'' na archibong \"\$1\".",
'undelete-missing-filearchive' => "Dai maibalik an archibo kan ''file'' may na  ID $1 ta mayô ini sa base nin datos. Pwede ser na pigparâ na ini.",
'undelete-error-short'         => "May salâ sa pagbalik kan pigparang ''file'': $1",
'undelete-error-long'          => "May mga salâ na nasabat mientras sa pigbabalik an pigparang ''file'':

$1",

# Namespace form on various pages
'namespace'      => 'ngaran-espacio:',
'invert'         => 'Ibaliktad an pinili',
'blanknamespace' => '(Principal)',

# Contributions
'contributions' => 'Mga kontribusyon kan parágamit',
'mycontris'     => 'Mga kontribusyon ko',
'contribsub2'   => 'Para sa $1 ($2)',
'nocontribs'    => 'Mayong mga pagbabago na nahanap na kapadis sa ining mga criteria.',
'ucnote'        => 'Mahihiling sa baba an mga huring <b>$1</b> binagao kaining paragamit sa  <b>$2</b> na aldaw.',
'uclinks'       => 'Hilingon an mga huring $1 na pagbabago; hilingon an mga huring $2 na aldaw.',
'uctop'         => ' (alituktok)',
'month'         => 'Poon bulan (asin mas amay):',
'year'          => 'Poon taon (asin mas amay):',

'sp-contributions-newest'      => 'Pinakabâgo',
'sp-contributions-oldest'      => 'Pinakalumà',
'sp-contributions-newer'       => 'Mas bâgo $1',
'sp-contributions-older'       => 'Mas lumâ $1',
'sp-contributions-newbies'     => 'Ipahiling an mga kontribusión kan mga bagong kuenta sana',
'sp-contributions-newbies-sub' => 'Para sa mga bâgong accounts',
'sp-contributions-blocklog'    => 'Bagáton an historial',
'sp-contributions-search'      => 'Maghanap nin mga kontribusyon',
'sp-contributions-username'    => 'IP o ngaran kan parágamit:',
'sp-contributions-submit'      => 'Hanápon',

'sp-newimages-showfrom' => 'Hilingón an mga retratong nagpopoon sa $1',

# What links here
'whatlinkshere'       => 'An nakatakod digdi',
'whatlinkshere-title' => 'Mga pahinang nakatakod sa $1',
'linklistsub'         => '(Lista kan mga takod)',
'linkshere'           => "An mga minasunod na pahina nakatakod sa '''[[:$1]]''':",
'nolinkshere'         => "Mayong pahinang nakatakod sa '''[[:$1]]'''.",
'nolinkshere-ns'      => "Mayong pahina na nakatakod sa '''[[:$1]]''' sa piniling ngaran-espacio.",
'isredirect'          => 'iredirekta an pahina',
'istemplate'          => 'inklusion',
'whatlinkshere-prev'  => '{{PLURAL:$1|nakaagi|nakaaging $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|sunod|sunod na $1}}',
'whatlinkshere-links' => '← Mga takod',

# Block/unblock
'blockip'                     => 'Bagáton an parágamit',
'blockiptext'                 => 'Gamiton an pormularyo sa babâ para bagaton an pagsurat kan sarong espesipikong IP o ngaran nin parágamit.
Dapat gibohon sana ini para maibitaran vandalismo, asin kompirmi sa [[{{MediaWiki:Policy-url}}|palakaw]].
Magkaag nin espisipikong rason (halimbawa, magtao nin ehemplo kan mga páhinang rinaot).',
'ipaddress'                   => 'Direksyón nin IP:',
'ipadressorusername'          => 'dirección nin IP o gaha:',
'ipbexpiry'                   => 'Pasó:',
'ipbreason'                   => 'Rason:',
'ipbreasonotherlist'          => 'Ibang rason',
'ipbreason-dropdown'          => "*Mga komon na rason sa pagbagat
** Nagkakaag nin salang impormasyon
** Naghahalî nin mga laog kan páhina
** Nagkakaag nin mga takod na ''spam'' kan mga panluwas na ''site''
** Nagkakaag nin kalokohan/ringaw sa mga pahina
** Gawî-gawing makatakót/makauyám
** Nag-aabuso nin mga lain-lain na ''account''
** Dai akong ngaran nin parágamit",
'ipbanononly'                 => 'Bagaton an mga paragamit na anonimo sana',
'ipbcreateaccount'            => 'Pugulon an pagibo nin kuenta.',
'ipbemailban'                 => 'Pugolan ining paragamit na magpadara nin e-surat',
'ipbenableautoblock'          => 'Enseguidang bagaton an huring direccion nin  IP na ginamit kaining paragamit, asin kon ano pang ibang IP na proprobaran nindang gamiton',
'ipbsubmit'                   => 'Bagáton ining parágamit',
'ipbother'                    => 'Ibang oras:',
'ipboptions'                  => '2ng oras:2 hours,1ng aldaw:1 day,3ng aldaw:3 days,1ng semana:1 week,2ng semana:2 weeks,1ng bulan:1 month,3ng bulan:3 months,6 na bulan:6 months,1ng taon:1 year,daing kasagkoran:infinite',
'ipbotheroption'              => 'iba',
'ipbotherreason'              => 'Iba/dugang na rasón:',
'ipbhidename'                 => 'Itago an ngaran in paragamit/IP para dai mahiling sa historial nin pagbagat, nakaandar na lista nin binagat asin lista nin paragamit',
'badipaddress'                => 'Dai pwede ining IP',
'blockipsuccesssub'           => 'Nagibo na an pagbagát',
'blockipsuccesstext'          => 'Binagat si [[Special:Contributions/$1|$1]].
<br />Hilingon an [[Special:Ipblocklist|lista nin mga binagat na IP]] para marepaso an mga binagat.',
'ipb-edit-dropdown'           => 'Hirahón an mga rasón sa pagbabagát',
'ipb-unblock-addr'            => 'Paagihon $1',
'ipb-unblock'                 => 'Bawion an pagbagat nin ngaran nin paragamit o direccion nin IP',
'ipb-blocklist-addr'          => 'Hilingón an mga presenteng pagbagat ki $1',
'ipb-blocklist'               => 'Hilingon an mga presenteng binagat',
'unblockip'                   => 'Paagihon an parâgamit',
'unblockiptext'               => 'Gamiton an pormulario sa baba para puede giraray suratan an dating binagat na direccion nin IP address o ngaran nin paragamit.',
'ipusubmit'                   => 'Bawion an pagbagat kaining direccíón',
'unblocked'                   => 'Binawi na an pagbagat ki [[User:$1|$1]]',
'unblocked-id'                => 'Hinali na an bagat na $1',
'ipblocklist'                 => 'Lista nin mga direccion nin IP asin ngaran nin paragamit na binagat',
'ipblocklist-legend'          => 'Hanapon an sarong binagát na paragamit',
'ipblocklist-username'        => 'Gahâ o dirección nin IP:',
'ipblocklist-submit'          => 'Hanápon',
'blocklistline'               => '$1, $2 binagat $3 ($4)',
'infiniteblock'               => 'daing siring',
'expiringblock'               => 'minapasó $1',
'anononlyblock'               => 'anon. sana',
'noautoblockblock'            => 'pigpopondo an enseguidang pagbagat',
'createaccountblock'          => 'binagat an paggibo nin kuenta',
'emailblock'                  => 'binagát an e-surat',
'ipblocklist-empty'           => 'Mayong laog an lista nin mga binagat.',
'ipblocklist-no-results'      => 'Dai nabagat an hinagad na direccion nin IP o ngaran nin paragamit.',
'blocklink'                   => 'bagáton',
'unblocklink'                 => 'paagihon',
'contribslink'                => 'mga kontrib',
'autoblocker'                 => 'Enseguidang binagat an saimong direccion nin IP ta kaaaging ginamit ini ni "[[User:$1|$1]]". An rason nin pagbagat ni $1: "$2"',
'blocklogpage'                => 'Historial nin pagbagat',
'blocklogentry'               => 'binagat na [[$1]] na may oras nin pagpaso na $2 $3',
'blocklogtext'                => 'Ini an historial kan pagbagat asin pagbawi sa pagbagat nin mga paragamit. An mga enseguidang binagat na direccion nin
IP dai nakalista digdi. Hilingon an [[Special:Ipblocklist|IP lista nin mga binagat]] para sa lista nin mga nakaandar na mga pagpangalad buda mga pagbagat.',
'unblocklogentry'             => 'binawi an pagbagat $1',
'block-log-flags-anononly'    => 'Mga paragamit na anónimo sana',
'block-log-flags-nocreate'    => "pigpopondohán an paggibo nin ''account'",
'block-log-flags-noautoblock' => 'pigpopondo an enseguidang pagbagat',
'block-log-flags-noemail'     => 'binagát an e-surat',
'range_block_disabled'        => 'Pigpopondo an abilidad kan sysop na maggibo nin bagat na hilera.',
'ipb_expiry_invalid'          => 'Dai pwede ini bilang oras kan pagpasó.',
'ipb_already_blocked'         => 'Dating binagat na si "$1"',
'ipb_cant_unblock'            => 'Error: Dai nahanap an ID nin binagat na $1. Puede ser na dati nang binawi an pagbagat kaini.',
'ip_range_invalid'            => 'Dai pwede ining serye nin IP.',
'proxyblocker'                => 'Parabagát na karibay',
'proxyblockreason'            => 'Binagat an saimong direccion nin IP ta ini sarong bukas na proxy. Apodon tabi an saimong Internet service provider o tech support asin ipaaram sainda ining seriosong problema nin seguridad.',
'proxyblocksuccess'           => 'Tapos.',
'sorbsreason'                 => 'An saimong direccion in IP nakalista na bukas na proxy sa DNSBL na piggagamit kaining sitio.',
'sorbs_create_account_reason' => "An IP mo nakalista bilang bukás ''proxy'' sa DNSBL na piggagamit kaining ''site''. Dai ka pwedeng maggibo ''account''",

# Developer tools
'lockdb'              => 'Ikandado an base nin datos',
'unlockdb'            => 'Ibukás an base nin datos',
'lockconfirm'         => 'Iyo, boot kong ikandado an base kan datos.',
'unlockconfirm'       => 'Iyo, boot kong bukasan an base kan datos.',
'lockbtn'             => 'Isará an base nin datos',
'unlockbtn'           => 'Ibukás an base nin datos',
'locknoconfirm'       => 'Dai mo pigtsekan an kahon para sa kompirmasyon.',
'lockdbsuccesssub'    => 'Kinandado na an base nin datos',
'unlockdbsuccesssub'  => 'Hinalî an kandado nin base nin datos',
'lockdbsuccesstext'   => 'Pigkandado na an base kan datos.
<br />Giromdomon na [[Special:Unlockdb|halîon an kandado]] pagkatapos kan pagmantenir.',
'unlockdbsuccesstext' => 'Pigbukasan na an base nin datos.',
'lockfilenotwritable' => "An ''file'' na kandado kan base nin datos dai nasusuratan. Para makandado o mabukasan an bse nin datos, kaipuhan na nasusuratan ini kan web server.",
'databasenotlocked'   => 'Dai nakakandado an base nin datos.',

# Move page
'movepage'                => 'Ibalyó an páhina',
'movepagetext'            => "Matatâwan nin bâgong pangaran an sarong páhina na pigbabalyo an gabos na historya kaini gamit an pormularyo sa babâ.
An dating titulo magigin redirektang páhina sa bâgong titulo.
Dai babâgohon an mga takod sa dating titulo kan páhina; seguradohon tabî na mayong doble o raot na mga redirekta.
Ika an responsable sa pagpaseguro na an mga takod nakatokdô kun sain dapat.

Giromdomon tabî na an páhina '''dai''' ibabalyó kun igwa nang páhina sa bagong titulo, apwera kun mayô ining laog o sarong redirekta asin historya nin mga dating paghirá. An boot sabihon kaini, pwede mong ibalik an dating pangaran kan páhina kun sain ini pigribayan nin pangaran kun napasalâ ka, asin dai mo man sosoknongan an presenteng páhina.

<b>PATANID!</b>
Pwede ser na dakulâ asin dai seguradong pagbâgo ini kan sarong popular na páhina;
seguradohon tabî na aram mo an konsekwensya kaini bago magdagos.",
'movepagetalktext'        => "An kapadis na olay na páhina enseguidang ibabalyo kasabay kaini '''kun:'''
*Igwa nang may laog na olay na páhina na may parehong pangaran, o
*Halîon mo an marka sa kahon sa babâ.

Sa mga kasong iyan, kaipuhan mong ibalyo o isalak an páhina nin mano-mano kun boot mo.",
'movearticle'             => 'Ibalyó an pahina:',
'movenologin'             => 'Mayô sa laog',
'movenologintext'         => 'Kaipuhan na rehistradong parágamit ka asin si [[Special:Userlogin|nakalaog]] tangarig makabalyó ka nin páhina.',
'movenotallowed'          => 'Mayô kang permiso na ibalyó an mga pahina sa wiki na ini.',
'newtitle'                => 'Sa bâgong titulong:',
'move-watch'              => 'Bantayan ining pahina',
'movepagebtn'             => 'Ilipat an páhina',
'pagemovedsub'            => 'Nabalyó na',
'movepage-moved'          => 'An <big>\'\'\'"$1" binalyo na sa "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => 'Igwa nang páhina na may parehong pangaran, o dai pwede an pangaran na pigpili mo.
Magpili tabî nin ibang pangaran.',
'talkexists'              => "'''An mismong páhina binalyo na, pero su páhinang orolayan dai nabalyó ta igwa na kaini sa bâgong titulo. Pagsaroon tabî ining duwa nin mano-mano.'''",
'movedto'                 => 'piglipat sa',
'movetalk'                => 'Ibalyo an pahinang orolayan na nakaasociar',
'talkpagemoved'           => 'Binalyo an kapadis na páhinang panorolayan.',
'talkpagenotmoved'        => '<strong>Dai</strong> nabalyo an kapadis na pahinang orolayan.',
'1movedto2'               => '[[$1]] piglipat sa [[$2]]',
'1movedto2_redir'         => '[[$1]] pigbalyó sa [[$2]] sa paagi kan pagredirekta',
'movelogpage'             => 'Ibalyó an historial',
'movelogpagetext'         => 'Nasa ibaba an lista kan pahinang pigbalyó.',
'movereason'              => 'Rason:',
'revertmove'              => 'ibalik',
'delete_and_move'         => 'Parâon asin ibalyó',
'delete_and_move_text'    => '==Kaipuhan na parâon==

Igwa nang páhina na "[[$1]]". Gusto mong parâon ini tangarig maibalyó?',
'delete_and_move_confirm' => 'Iyo, parâon an pahina',
'delete_and_move_reason'  => 'Pinarâ tangarig maibalyó',
'selfmove'                => 'Pareho an páhinang ginikanan asin destinasyon; dai pwedeng ibalyó an sarong páhina sa sadiri.',
'immobile_namespace'      => 'An titulo kan ginikanan o destinasyon sarong espesyal na tipo; dai pwedeng ibalyó an mga pahina hali or paduman sa ngaran-espacio na iyan.',

# Export
'export'            => 'Iluwas an mga páhina',
'exporttext'        => 'Pwede mong ipadara an teksto asin historya nin paghirá kan sarong partikular na páhina o grupo nin mga páhina na nakapatos sa ibang XML. Pwede ining ipadara sa ibang wiki gamit an MediaWiki sa paagi kan [[Special:Import|pagpadara nin páhina]].

Para makapadara nin mga páhina, ilaag an mga titulo sa kahon para sa teksto sa babâ, sarong titulo kada linya, dangan pilîon kun boot mo presenteng bersyón asin dating bersyón, na may mga linya kan historya, o an presenteng bersyón sana na may impormasyon manonongod sa huring hirá.

Sa kaso kan huri, pwede ka man na maggamit nin takod, arog kan [[{{ns:Special}}:Export/{{MediaWiki:Mainpage}}]] para sa páhinang "[[{{MediaWiki:Mainpage}}]]".',
'exportcuronly'     => 'Mga presenteng pagpakarhay sana an ibali, bakong an enterong historya',
'exportnohistory'   => "----
'''Paisi:''' Dai pigpatogotan an pagpadara kan enterong historya kan mga páhina sa paagi kaining forma huli sa mga rasón dapit sa pagsagibo kaini.",
'export-submit'     => 'Ipaluwás',
'export-addcattext' => 'Magdugang nin mga pahina sa kategoryang ini:',
'export-addcat'     => 'Magdugang',
'export-download'   => "Hapotón ku gustong itagama bilang sarong ''file''",

# Namespace 8 related
'allmessages'               => 'Mga mensahe sa sistema',
'allmessagesname'           => 'Pangaran',
'allmessagesdefault'        => 'Tekstong normal',
'allmessagescurrent'        => 'Presenteng teksto',
'allmessagestext'           => 'Ini an lista kan mga mensahe sa sistema sa ngaran-espacio na MediaWiki.',
'allmessagesnotsupportedDB' => "Dai pwedeng gamiton an '''{{ns:special}}:Allmessages''' ta sarado an '''\$wgUseDatabaseMessages'''.",
'allmessagesfilter'         => 'Pansara nin pangaran kan mensahe:',
'allmessagesmodified'       => 'An mga pigmodikar sana an ipahiling',

# Thumbnails
'thumbnail-more'           => 'Padakulâon',
'missingimage'             => '<b>Nawawarang ladawan</b><br /><i>$1</i>',
'filemissing'              => "Nawawarâ an ''file''",
'thumbnail_error'          => 'Error sa paggigibo kan retratito: $1',
'djvu_page_error'          => 'luwas sa serye an páhina kan DjVu',
'djvu_no_xml'              => 'Dai makua an XML para sa DjVu file',
'thumbnail_invalid_params' => 'Dai pwede an mga parámetro kaining retratito',
'thumbnail_dest_directory' => 'Dai makagibo kan destinasyon kan direktoryo',

# Special:Import
'import'                     => 'Ilaog an mga páhina',
'importinterwiki'            => 'Ipadara an Transwiki',
'import-interwiki-history'   => 'Kopyahon an gabos na mga bersyón para sa páhinang ini',
'import-interwiki-submit'    => 'Ipalaog',
'import-interwiki-namespace' => 'Ibalyó an mga páhina sa ngaran-espacio:',
'importtext'                 => "Ipadara tabì an ''file'' hali sa ginikanan na wiki gamit an Special:Export utility, itagama ini sa saimong disk dangan ikarga iyan digdi.",
'importstart'                => 'Piglalaog an mga páhina...',
'import-revision-count'      => '$1 {{PLURAL:$1|pagpakarhay|mga pagpakarhay}}',
'importnopages'              => 'Mayong mga páhinang ipapadara.',
'importfailed'               => 'Bakong matriumpo an pagpadara: $1',
'importunknownsource'        => 'Dai aram an tipo kan gigikanan kan ipapadara',
'importcantopen'             => "Dai mabukasan an pigpadarang ''file''",
'importbadinterwiki'         => 'Salâ an takod na interwiki',
'importnotext'               => 'Mayong laog o mayong teksto',
'importsuccess'              => 'Matriumpo an pagpadara!',
'importnofile'               => "Mayong ipinadarang ''file'' an naikarga.",

# Import log
'importlogpage'                    => 'Historial nin ipinalaog',
'import-logentry-upload'           => "pigpadara an [[$1]] kan pagkarga nin ''file''",
'import-logentry-upload-detail'    => '$1 mga pagpakarháy',
'import-logentry-interwiki'        => 'na-transwiki an $1',
'import-logentry-interwiki-detail' => '$1 mga pagpakarháy halì sa $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'An sakóng páhina',
'tooltip-pt-anonuserpage'         => 'An páhina nin páragamit para sa ip na pighihira mo bilang',
'tooltip-pt-mytalk'               => 'Páhina kan taram ko',
'tooltip-pt-anontalk'             => 'Mga olay manonongod sa mga hira halî sa ip na ini',
'tooltip-pt-preferences'          => 'Mga kabôtan ko',
'tooltip-pt-watchlist'            => 'Lista nin mga páhina na pigbabantayan mo para sa mga pagribay',
'tooltip-pt-mycontris'            => 'Lista kan mga kabôtan ko',
'tooltip-pt-login'                => 'Pig-aagda kang maglaog, alagad, bako man ining piriritan.',
'tooltip-pt-anonlogin'            => 'Pig-aagda kang maglaog, alagad, bakô man ining piriritan.',
'tooltip-pt-logout'               => 'Magluwas',
'tooltip-ca-talk'                 => 'Olay sa páhina kan laog',
'tooltip-ca-edit'                 => 'Pwede mong hirahón ining páhina. Gamiton tabi an patânaw na butones bago an pagtagama.',
'tooltip-ca-addsection'           => 'Magdugang nin komento sa orólay na ini.',
'tooltip-ca-viewsource'           => 'Protektado an páhinang ini. Pwede mong hilingon an ginikanan.',
'tooltip-ca-history'              => 'Mga nakaaging bersyon kaining páhina.',
'tooltip-ca-protect'              => 'Protekatahón ining páhina',
'tooltip-ca-delete'               => 'Parâon an páhinang ini',
'tooltip-ca-undelete'             => 'Bawîon an mga hirá na piggibo sa páhinang ini bâgo ini pigparâ',
'tooltip-ca-move'                 => 'Ilipat an páhinang ini',
'tooltip-ca-watch'                => 'Idugang ining páhina sa pigbabantayan mo',
'tooltip-ca-unwatch'              => 'Halîon ining páhina sa lista kan mga pigbabantayan mo',
'tooltip-search'                  => 'Hanápon an {{SITENAME}}',
'tooltip-p-logo'                  => 'Pangenot na Pahina',
'tooltip-n-mainpage'              => 'Bisitahon an Pangenot na Pahina',
'tooltip-n-portal'                => 'Manonongod sa proyekto, an pwede mong gibohon, kun sain mo pwedeng hanapon an mga bagay',
'tooltip-n-currentevents'         => 'Hanapon an mga impormasyon na ginikanan sa mga presenteng panyayari',
'tooltip-n-recentchanges'         => 'An lista nin mga bâgong ribayan sa ining wiki.',
'tooltip-n-randompage'            => 'Magkarga nin bâgong pahina',
'tooltip-n-help'                  => 'An lugar para makatalastas.',
'tooltip-n-sitesupport'           => 'Suportaran kami',
'tooltip-t-whatlinkshere'         => 'Lista nin gabos na páhinang wiki na nakatakód digdi',
'tooltip-t-recentchangeslinked'   => 'Mga kaaaging pagbabâgo sa mga páhinang nakatakod digdi',
'tooltip-feed-rss'                => 'Hungit na RSS sa pahinang ini',
'tooltip-feed-atom'               => 'Hungit na atomo sa pahinang ini',
'tooltip-t-contributions'         => 'Hilingón an lista kan mga kontribusyon kaining parágamit',
'tooltip-t-emailuser'             => 'Padarahan nin surat an parágamit na ini',
'tooltip-t-upload'                => "Ikargá an mga ladawan o ''media files''",
'tooltip-t-specialpages'          => 'Lista kan gabos na mga espesyal na páhina',
'tooltip-t-print'                 => 'Naipiprint na bersyon kaining pahina',
'tooltip-t-permalink'             => 'Permanenteng takod sa bersyon kaining páhina',
'tooltip-ca-nstab-main'           => 'Hilingón an laog kan páhina',
'tooltip-ca-nstab-user'           => 'Hilingón páhina kan parágamit',
'tooltip-ca-nstab-media'          => "Hilingón an pahina kan ''media''",
'tooltip-ca-nstab-special'        => 'Páhinang espesyal ini, dai mo pwedeng hiraon an páhina ini',
'tooltip-ca-nstab-project'        => 'Hilingón an pahina kan proyekto',
'tooltip-ca-nstab-image'          => 'Hilingón an pahina kan retrato',
'tooltip-ca-nstab-mediawiki'      => "Hilingón an ''system message''",
'tooltip-ca-nstab-template'       => 'Hilingón an templato',
'tooltip-ca-nstab-help'           => 'Hilingón an páhinang tabang',
'tooltip-ca-nstab-category'       => 'Hilingón an pahina kan kategorya',
'tooltip-minoredit'               => 'Markahán ini bilang menor na hirâ',
'tooltip-save'                    => 'Itagama an mga pagriribay mo',
'tooltip-preview'                 => 'Hilingon an patanaw kan saimong mga pagbabago, gamiton tabi ini bago magtagama!',
'tooltip-diff'                    => 'Ipahiling kon ano an mga pagbabagong ginibo mo sa teksto.',
'tooltip-compareselectedversions' => 'Hilingon an mga kaibhán sa duwang piniling bersyón kaining páhina.',
'tooltip-watch'                   => 'Idugang ining pahina sa pigbabantayan mo',
'tooltip-recreate'                => 'Gibohon giraray an páhina maski na naparâ na ini',
'tooltip-upload'                  => 'Pônan an pagkarga',

# Stylesheets
'common.css'   => '/** an CSS na pigbugtak digdi maiaaplikar sa gabos na mga skin */',
'monobook.css' => '/* an CSS na pigbugtak digdi makakaapektar sa mga parágamit kan Monobook skin */',

# Scripts
'common.js'   => '/* Arin man na JavaScript digdi maikakarga para sa gabos na mga parágamit sa kada karga kan páhina. */',
'monobook.js' => '/* Deprecado; gamiton an [[MediaWiki:common.js]] */',

# Metadata
'nodublincore'      => "Pigpopogolan an Dublin Core RDF na metadata para sa ''server'' na ini.",
'nocreativecommons' => "Pigpopogolan an Creative Commons RDF na metadata para sa ''server'' na ini.",
'notacceptable'     => "Dai pwedeng magtao nin datos an ''wiki server'' sa ''format'' na pwedeng basahon kan kompyuter mo.",

# Attribution
'anonymous'        => '(Mga)paragamit na anónimo kan {{SITENAME}}',
'siteuser'         => 'Paragamit kan {{SITENAME}} na si $1',
'lastmodifiedatby' => 'Ining páhina huring binago sa $2, $1 ni $3.', # $1 date, $2 time, $3 user
'and'              => 'asin',
'othercontribs'    => 'Binase ini sa trabaho ni $1.',
'others'           => 'iba pa',
'siteusers'        => '(Mga)paragamit kan {{SITENAME}} na si $1',
'creditspage'      => 'Mga krédito nin páhina',
'nocredits'        => 'Mayong talastas kan kredito para sa ining pahina.',

# Spam protection
'spamprotectiontitle'    => "Proteksyon kan ''spam filter''",
'spamprotectiontext'     => "An páhinang gusto mong itagama pigbagat kan ''spam filter''. Kawsa gayod ini kan sarong takod sa sarong panluwas na 'site'.",
'spamprotectionmatch'    => "An minasunod na teksto iyo an nagbukas kan ''spam filter'' mi: $1",
'subcategorycount'       => 'Igwang {{PLURAL:$1|na sub-kategorya|mga $1 sub-kategorya}} sa kategoryang ini.',
'categoryarticlecount'   => 'Igwang {{PLURAL:$1|is one page|mga $1 páhina}} sa kategoryang ini.',
'category-media-count'   => "Igwang {{PLURAL:$1|is one file|mga $1 ''file''}} sa kategoryang ini.",
'listingcontinuesabbrev' => 'sunod.',
'spambot_username'       => 'paglimpya nin spam sa MediaWiki',
'spam_reverting'         => 'Mabalik sa huring bersion na mayong takod sa $1',
'spam_blanking'          => 'An gabos na mga pahirá na may takod sa $1, pigblablanko',

# Info page
'infosubtitle'   => 'Impormasyón kan páhina',
'numedits'       => 'Bilang kan mga hira (artikulo): $1',
'numtalkedits'   => 'Bilang kan mga hirá (páhina kan orólay): $1',
'numwatchers'    => 'Bilang kan mga parábantay: $1',
'numauthors'     => 'Bilang kan mga parásurat na ibá (páhina): $1',
'numtalkauthors' => 'Bilang kan mga parásurat na ibá (páhina kan orólay): $1',

# Math options
'mw_math_png'    => 'Itaô pirmi an PNG',
'mw_math_simple' => 'HTML kun simple sana o PNG kun bakô',
'mw_math_html'   => 'HTML kun posible o PNG kun bakô',
'mw_math_source' => "Pabayaan na bilang TeX (para sa mga ''browser'' na teksto)",
'mw_math_modern' => "Pigrerekomendár para sa mga modernong ''browser''",
'mw_math_mathml' => 'MathML kun posible (experimental)',

# Patrolling
'markaspatrolleddiff'                 => 'Markahán na pigpapatrulya',
'markaspatrolledtext'                 => 'Markahan ining pahina na pigpapatrulya',
'markedaspatrolled'                   => 'Minarkahán na pigpapatrulyá',
'markedaspatrolledtext'               => 'Minarkahan bilang pigpapatrulya an piniling pagpakaray.',
'rcpatroldisabled'                    => 'Pigpopogolan an mga Pagpatrulya kan mga Kaaaging Pagbabâgo',
'rcpatroldisabledtext'                => 'Pigpopogulan muna an Pagpatrulya kan mga Kaaaging Pagbabago.',
'markedaspatrollederror'              => 'Dai pwedeng markahán na pigpapatrulya',
'markedaspatrollederrortext'          => 'Kaipuhan mong magpili nin pagpakaray na mamarkahon na pigpapatrulya.',
'markedaspatrollederror-noautopatrol' => 'Dai ka pigtotogotan na markahán an sadiri mong pababâgo na pigpapatrulya.',

# Patrol log
'patrol-log-page' => 'Bantayán an historial',
'patrol-log-line' => 'minarkahan an $1 kan $2 na pigpapatrulya $3',
'patrol-log-auto' => '(enseguida)',

# Image deletion
'deletedrevision'                 => 'Pigparâ an lumang pagribay na $1.',
'filedeleteerror-short'           => "Salâ sa pagparâ kan ''file'': $1",
'filedeleteerror-long'            => "May mga nasabat na salâ mientras na pigpaparâ an ''file'':

$1",
'filedelete-missing'              => "An ''file'' na \"\$1\" dai pwedeng paraon, ta mayô man ini.",
'filedelete-old-unregistered'     => 'An piniling pagpakaray na "$1" wara man sa base nin datos.',
'filedelete-current-unregistered' => "Mayô sa base nin datos an piniling ''file'' na \"\$1\".",
'filedelete-archive-read-only'    => 'An direktoryong archibo na "$1" dai nasusuratan kan webserver.',

# Browsing diffs
'previousdiff' => '← Nakaaging kaibhán',
'nextdiff'     => 'Sunod na kaibhán →',

# Media information
'mediawarning'         => "'''Patanid''': May ''malicious code'' sa ''file'' na ini, kun gigibohon ini pwede ser na maraot an saimong ''system''.<hr />",
'imagemaxsize'         => 'Limitaran an mga ladawan sa mga páhinang deskripsyon kan ladawan sa:',
'thumbsize'            => 'Sokol nin retratito:',
'widthheightpage'      => '$1×$2, $3 mga pahina',
'file-info'            => "(sokol kan ''file'': $1, tipo nin MIME: $2)",
'file-info-size'       => "($1 × $2 na pixel, sokol kan ''file'': $3, tipo nin MIME: $4)",
'file-nohires'         => '<small>Mayong mas halangkaw na resolusyón.</small>',
'svg-long-desc'        => "(''file'' na SVG, haros $1 × $2 pixels, sokol kan ''file'': $3)",
'show-big-image'       => 'Todong resolusyón',
'show-big-image-thumb' => '<small>Sokol kan patânaw: $1 × $2  na pixel</small>',

# Special:Newimages
'newimages'    => "Galeria nin mga bâgong ''file''",
'showhidebots' => '($1 na bots)',
'noimages'     => 'Mayong mahihiling.',

# Bad image list
'bad_image_list' => 'An pormato iyo an nasunod:

Mga bagay na linista sana (mga linyang nagpopoon sa *) an pigkokonsiderar. An enot na takod sa linya seguradong nakatakod sa sala na ladawan.
An mga iba pang takod sa linyang ini pigkokonsiderar na eksepsyon, i.e. mga páhina na may ladawan sa laog nin linya.',

# Metadata
'metadata'          => 'Metadatos',
'metadata-help'     => "Igwang dugang na impormasyon ining ''file'' na pwedeng idinugang hali sa digital camera o scanner na piggamit tangarig magibo ini. Kun namodipikar na ''file'' hali sa orihinal nyang kamogtakan, an ibang mga detalye pwedeng dai mahiling sa minodipikar na ladawan.",
'metadata-expand'   => 'Ipahiling an mga pinahalaba na detalye',
'metadata-collapse' => 'Itago an mga pinahalaba na detalye',

# EXIF tags
'exif-imagewidth'       => 'Lakbang',
'exif-imagelength'      => 'Langkaw',
'exif-imagedescription' => 'Titulo kan retrato',
'exif-make'             => 'Tagagibo nin kamera',
'exif-model'            => 'Modelo nin kamera',
'exif-artist'           => 'Parásurat',
'exif-usercomment'      => 'Mga komento kan parágamit',
'exif-aperturevalue'    => 'Pagkabukás',
'exif-brightnessvalue'  => 'Kaliwanagan',
'exif-lightsource'      => 'Ginikanan nin liwanag',
'exif-flash'            => 'Kikilát',
'exif-flashenergy'      => 'Kakusogan nin kikilát',
'exif-filesource'       => "Ginikanan nin ''file''",
'exif-contrast'         => 'Kontraste',
'exif-imageuniqueid'    => 'Unikong ID kan ladawan',
'exif-gpstrack'         => 'Direksyón nin paghirô',
'exif-gpsimgdirection'  => 'Direksyón nin ladawan',
'exif-gpsdestdistance'  => 'Distansya sa destinasyon',

'exif-unknowndate' => 'Dai aram an petsa',

'exif-componentsconfiguration-0' => 'mayô man ini',

'exif-subjectdistance-value' => '$1 metros',

'exif-meteringmode-0'   => 'Dai aram',
'exif-meteringmode-255' => 'Iba pa',

'exif-lightsource-4'   => 'Kitkilát',
'exif-lightsource-9'   => 'Magayón na panahón',
'exif-lightsource-255' => 'Mga ibang ginikanan nin ilaw',

'exif-focalplaneresolutionunit-2' => 'pulgada',

'exif-scenetype-1' => 'Direktong naretratong ladawan',

'exif-scenecapturetype-2' => 'Retrato',
'exif-scenecapturetype-3' => 'Eksenang banggi',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'Kilometros kada oras',
'exif-gpsspeed-m' => 'Milya kada oras',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Tunay na direksyon',
'exif-gpsdirection-m' => 'Direksyón nin batobalani',

# External editor support
'edit-externally'      => "Hirahón an ''file'' gamit an panluwas na aplikasyon",
'edit-externally-help' => 'Hilingón an  [http://meta.wikimedia.org/wiki/Help:External_editors setup instructions] para sa iba pang mga impormasyon.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'gabos',
'imagelistall'     => 'gabos',
'watchlistall2'    => 'gabos',
'namespacesall'    => 'gabos',
'monthsall'        => 'gabos',

# E-mail address confirmation
'confirmemail'            => "Kompirmaron an ''e''-surat",
'confirmemail_noemail'    => "Mayô kang pigkaag na marhay na ''e''-surat sa saimong [[Special:Preferences|mga kabôtan nin parágamit]].",
'confirmemail_text'       => "Kaipuhan an pag-''validate'' kan saimong ''e''-surat bago ka makagamit nin ''features'' na ''e''-surat. Pindoton an butones sa babâ tangarig magpadara nin kompirmasyon sa saimong ''e''-surat. An surat igwang takod na may ''code''; ikarga an takod sa ''browser'' para makompirmar na valido an saimong ''e''-surat.",
'confirmemail_pending'    => "<div class=\"error\">
May pigpadara nang kompirmasyon sa ''e''-surat mo; kun kagigibo mo pa sana kan saimong ''account'', maghalat ka nin mga dikit na minutos bago ka maghagad giraray nin bâgong ''code''.
</div>",
'confirmemail_send'       => 'Magpadará nin kompirmasyon',
'confirmemail_sent'       => "Napadará na an kompirmasyon sa ''e''-surat.",
'confirmemail_oncreate'   => "May pigpadara nang kompirmasyon sa saimong ''e''-surat.
Dai man kaipuhan ini para makalaog, pero kaipuhan mong itao ini bago
ka makagamit nin ''features'' na basado sa ''e''-surat sa wiki.",
'confirmemail_sendfailed' => "Dai napadará an kompirmasyon kan ''e''-surat. Seguradohon tabî kun igwang sala.

Pigbalik: $1",
'confirmemail_invalid'    => 'Salâ an kódigo nin konpirmasyon. Puede ser napasó na an kódigo.',
'confirmemail_needlogin'  => "Kaipuhan tabi $1 ikompirmar an saimong ''e''-surat.",
'confirmemail_success'    => "Nakompirmar na an saimong ''e''-surat. Pwede ka nang maglaog asin mag-ogma sa wiki.",
'confirmemail_loggedin'   => "Nakompirmar na an saimong ''e''-surat.",
'confirmemail_error'      => 'May nasalâ sa pagtagama kan saimong kompirmasyon.',
'confirmemail_subject'    => "kompirmasyón {{SITENAME}} kan direksyón nin ''e''-surat",
'confirmemail_body'       => "May páragamit, pwede ser na ika, halî sa IP na \$1, na nagrehistro nin ''account'' na
\"\$2\" na igwang ''e''-surat sa {{SITENAME}}.

Tangarig makompirmar na talagang saimo ining ''account'' asin makagamit nin ''e''-surat sa {{SITENAME}}, bukasán ining takod sa saimong ''browser'':

\$3

Kun *bakô* ka ini, dai sunodon an takod. Ining koda nin kompirmasyón mapasó sa \$4.",

# Scary transclusion
'scarytranscludedisabled' => '[Pigpopogolan an transcluding na Interwiki]',
'scarytranscludefailed'   => '[Nabigô an pagkua kan templato para sa $1; despensa]',
'scarytranscludetoolong'  => '[halabaon an URL; despensa]',

# Trackbacks
'trackbackbox'      => '<div id="mw_trackbacks">
Mga trackback sa pahinang ini:<br />
$1
</div>',
'trackbackremove'   => ' ([$1 Parâon])',
'trackbacklink'     => 'Solsogan',
'trackbackdeleteok' => 'Pigparâ na an solsogan.',

# Delete conflict
'deletedwhileediting' => 'Patanid: Pigparâ na an pahinang ini antes na nagpoon kan maghirá!',
'confirmrecreate'     => "Si [[User:$1|$1]] ([[User talk:$1|olay]]) pigparâ ining páhina pagkatapos mong magpoon kan paghira ta:
: ''$2''
Ikonpirmar tabi na talagang gusto mong gibohon giraray ining pahina.",
'recreate'            => 'Gibohón giraray',

# HTML dump
'redirectingto' => 'Piglilikay sa [[$1]]...',

# action=purge
'confirm_purge'        => 'Halîon an an aliho kaining páhina?

$1',
'confirm_purge_button' => 'Sige',

# AJAX search
'searchcontaining' => "Hanápon an mga artikulong may ''$1''.",
'searchnamed'      => "Hanápon an mga artikulong ''$1''.",
'articletitles'    => "Mga artikulong nagpopoon sa ''$1''",
'hideresults'      => 'Tagôon an mga resulta',

# Multipage image navigation
'imgmultipageprev'   => '← nakaaging pahina',
'imgmultipagenext'   => 'sunod na pahina →',
'imgmultigo'         => 'Dumanán!',
'imgmultigotopre'    => 'Magduman sa pahina',
'imgmultiparseerror' => "Minaluwas na raot o salâ an ''file'' na ladawan, kayâ an {{SITENAME}} dai makakua nin lista kan mga páhina.",

# Table pager
'ascending_abbrev'         => 'skt',
'descending_abbrev'        => 'ba',
'table_pager_next'         => 'Sunod na páhina',
'table_pager_prev'         => 'Nakaaging páhina',
'table_pager_first'        => 'Enot na páhina',
'table_pager_last'         => 'Huring páhina',
'table_pager_limit'        => 'Ipahiling an $1 na aytem kada páhina',
'table_pager_limit_submit' => 'Dumanán',
'table_pager_empty'        => 'Mayong resulta',

# Auto-summaries
'autosumm-blank'   => 'Pighahalî an gabos na laog sa páhina',
'autosumm-replace' => "Pigriribayan an páhina nin '$1'",
'autoredircomment' => 'Piglilikay sa [[$1]]',
'autosumm-new'     => 'Bâgong páhina: $1',

# Live preview
'livepreview-loading' => 'Pigkakarga…',
'livepreview-ready'   => 'Pigkakarga… Magpreparar!',
'livepreview-failed'  => 'Dae nakapoon an direktong patânaw! Probaran tabî an patânaw na normal.',
'livepreview-error'   => 'Dai nakakabit: $1 "$2". Hilingón tabî an normal na patânaw.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'An mga pagbalyó na mas bâgo sa $1 na segundo pwedeng dai pa mahiling sa listang ini.',
'lag-warn-high'   => "Nin huli sa ''high database server lag'', an mga pagbabâgo na mas bâgo sa $1 na segundo pwedeng dai pa ipahiling sa listang ini.",

# Watchlist editor
'watchlistedit-numitems'       => 'An saimong pigbabantayan igwang {{PLURAL:$1|1 titulo|$1 mga titulo}}, apwera kan mga páhina kan olay.',
'watchlistedit-noitems'        => 'Mayong mga titulo an pigbabantayan mo.',
'watchlistedit-normal-title'   => 'Hirahón an pigbabantayan',
'watchlistedit-normal-legend'  => 'Halîon an mga titulo sa pigbabantayan',
'watchlistedit-normal-explain' => 'Mahihiling sa babâ an mga titulo na nasa lista nin mga pigbabantayan mo. Tangarig maghalî nin titulo, markahan an kahon sa gilid kaini, buda pindoton an Tangkason an mga Titulo. Pwede mo man na [[Special:Watchlist/raw|hirahón an bâgong lista]],
	o [[Special:Watchlist/clear|halîon an gabos na mga titulo]].',
'watchlistedit-normal-submit'  => 'Tangkason an mga Titulo',
'watchlistedit-normal-done'    => 'Pigtangkas an {{PLURAL:$1|1 an titulo|$1 mga titulo}} sa saimong pigbabantayan:',
'watchlistedit-raw-title'      => 'Hirahón an bâgong pigbabantayan',
'watchlistedit-raw-legend'     => 'Hirahón an bâgong pigbabantayan',
'watchlistedit-raw-explain'    => "Mahihiling sa babâ an mga titulo na nasa lista nin mga pigbabantayan mo, asin pwede ining hirahón sa paagi nin pagdugang asin paghalî sa lista; sarong titulo kada linya. Pag tapos na, pindoton an Bâgohon an Pigbabantayan.
	Pwede mo man [[Special:Watchlist/edit|gamiton an ''standard editor'']].",
'watchlistedit-raw-titles'     => 'Mga titulo:',
'watchlistedit-raw-submit'     => 'Bâgohón an pigbabantayan',
'watchlistedit-raw-done'       => 'Binâgo na an saimong pigbabantayan.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 an titulong|$1 mga titulong}} idinugang:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 an titulong|$1 mga titulong}} hinalî:',

# Watchlist editing tools
'watchlisttools-view' => 'Hilingón an mga dapít na pagbabâgo',
'watchlisttools-edit' => 'Hilingón asin hirahón an pigbabantayan',
'watchlisttools-raw'  => 'Hirahón an bâgong pigbabantayan',

);
