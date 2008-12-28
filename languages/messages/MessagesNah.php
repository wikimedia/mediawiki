<?php
/** Nahuatl (Nāhuatl)
 *
 * @ingroup Language
 * @file
 *
 * @author Fluence
 * @author Rob Church <robchur@gmail.com>
 */

# Per conversation with a user in IRC, we inherit from Spanish and work from there
# Nahuatl was the language of the Aztecs, and a modern speaker is most likely to
# understand Spanish if a Nah translation is not available
$fallback = 'es';

$messages = array(
# User preference toggles
'tog-underline'            => 'Tiquimpāntlanīz tzonhuiliztli:',
'tog-highlightbroken'      => 'Tiquinttāz tzomoc tzonhuiliztli <a href="" class="new">zan iuhquin inīn</a> (ahnozo: zan iuhquin inīn<a href="" class="internal">?</a>).',
'tog-hideminor'            => 'Tiquintlātīz tlapatlatzintli yancuīc tlapatlalizpan',
'tog-extendwatchlist'      => 'Ticpiyāz tlachiyaliztli ic mochīntīn tlapatlaliztli mohuelītih',
'tog-usenewrc'             => 'Huēyi yancuīc tlapatlaliztli (ahmo motlattah mochi ācalco)',
'tog-showtoolbar'          => 'Tiquittāz in tlein motequitiltia tlapatlaliztechcopa (JavaScript)',
'tog-editondblclick'       => 'Tiquimpatlāz zāzaniltin ōme clicca (JavaScript)',
'tog-showtoc'              => 'Tiquittāz in tlein cah zāzotlahcuilōlco',
'tog-rememberpassword'     => 'Ticpiyāz motlacalaquiliz inīn chīuhpōhualhuazco',
'tog-watchcreations'       => 'Tiquintlachiyāz zāzaniltin tiquinchīhua',
'tog-watchdefault'         => 'Tiquintlachiyāz zāzaniltin tiquimpatla',
'tog-watchmoves'           => 'Tiquintlachiyāz zāzaniltin tiquinzaca',
'tog-watchdeletion'        => 'Tiquintlachiyāz zāzaniltin tiquimpoloa',
'tog-minordefault'         => 'Ticmachiyōtīz mochīntīn tlapatlalitzintli ic default',
'tog-previewontop'         => 'Tiquittāz achtochīhualiztli achtopa tlapatlaliztli caxitl',
'tog-previewonfirst'       => 'Xiquitta achtochīhualiztli inic cē tlapatlalizpan',
'tog-enotifwatchlistpages' => 'Timitz-e-mailīzqueh ihcuāc mopatla cē zāzanilli tictlachiya.',
'tog-enotifusertalkpages'  => 'Nēchihtoa ihcuāc tlecpatla motēixnāmiquiliz',
'tog-enotifminoredits'     => 'Timitz-e-mailīzqueh nō zāzanilpatlatzintli ītechcopa',
'tog-enotifrevealaddr'     => 'Ticnēxtīz mo e-mailcān āxcāncayōtechcopa āmatlacuilizpan',
'tog-shownumberswatching'  => 'Tiquinttāz tlatequitiltilīlli tlein tlachiyacateh',
'tog-forceeditsummary'     => 'Xinēchnōtzāz ihcuāc ahmo niquihtōz inōn ōnitlapatlac',
'tog-watchlisthideown'     => 'Tiquintlātīz mopatlaliz motlachiyalizpan',
'tog-watchlisthidebots'    => 'Tiquintlātīz tepozpatlaliztli motlachiyalizpan',
'tog-watchlisthideminor'   => 'Tiquintlātīz tlapatlalitzintli motlachiyalizpan',
'tog-nolangconversion'     => 'Ahmo tictēquitiltia tlahtōlcuepaliztli',
'tog-ccmeonemails'         => 'Nō xinēch-mailīz ihcuāc nitē-mailīz tlatequitiltilīlli',
'tog-diffonly'             => 'Ahmo tiquittāz zāzanilli ītlapiyaliz ahneneuhquilitzīntlan',

'underline-always' => 'Mochipa',
'underline-never'  => 'Aīcmah',

'skinpreview' => '(Xiquitta quemeh yez)',

# Dates
'sunday'        => 'Tōnatiuhtōnal',
'monday'        => 'Mētztlitōnal',
'tuesday'       => 'Huītzilōpōchtōnal',
'wednesday'     => 'Yacatlipotōnal',
'thursday'      => 'Tezcatlipotōnal',
'friday'        => 'Quetzalcōātōnal',
'saturday'      => 'Tlāloctitōnal',
'sun'           => 'Tōn',
'mon'           => 'Mētz',
'tue'           => 'Huītz',
'wed'           => 'Yac',
'thu'           => 'Tez',
'fri'           => 'Quetz',
'sat'           => 'Tlāl',
'january'       => 'Tlacēnti',
'february'      => 'Tlaōnti',
'march'         => 'Tlayēti',
'april'         => 'Tlanāuhti',
'may_long'      => 'Tlamācuīlti',
'june'          => 'Tlachicuazti',
'july'          => 'Tlachicōnti',
'august'        => 'Tlachicuēiti',
'september'     => 'Tlachiucnāuhti',
'october'       => 'Tlamahtlācti',
'november'      => 'Tlamahtlāccēti',
'december'      => 'Tlamahtlācōnti',
'january-gen'   => 'Tlacēnti',
'february-gen'  => 'Tlaōnti',
'march-gen'     => 'Tlayēti',
'april-gen'     => 'Tlanāuhti',
'may-gen'       => 'Tlamācuīlti',
'june-gen'      => 'Tlachicuazti',
'july-gen'      => 'Tlachicōnti',
'august-gen'    => 'Tlachicuēiti',
'september-gen' => 'Tlachiucnāuhti',
'october-gen'   => 'Tlamahtlācti',
'november-gen'  => 'Tlamahtlāccēti',
'december-gen'  => 'Tlamahtlācōnti',
'jan'           => 'Cēn',
'feb'           => 'Ōnt',
'mar'           => 'Yēt',
'apr'           => 'Nāuh',
'may'           => 'Mācuīl',
'jun'           => 'Chicuacē',
'jul'           => 'Chicōn',
'aug'           => 'Chicuēi',
'sep'           => 'Chiucnāuh',
'oct'           => 'Mahtlāc',
'nov'           => 'Īhuāncē',
'dec'           => 'Īhuānōme',

# Bits of text used by many pages
'categories'            => 'Neneuhcāyōtl',
'pagecategories'        => '{{PLURAL:$1|Neneuhcāyōtl|Neneuhcāyōtl}}',
'category_header'       => 'Tlahcuilōlli "$1" neneuhcāyōc',
'subcategories'         => 'Neneuhcāyōtzintli',
'category-media-header' => 'Media "$1" neneuhcāyōc',
'category-empty'        => "''Cah ahtlein inīn neneuhcāyōc.''",

'mainpagetext' => "<big>'''MediaHuiqui cualli ōmotlahtlāli.'''</big>",

'about'          => 'Ītechcopa',
'article'        => 'tlahcuilōlli',
'newwindow'      => '(Motlapoāz cē yancuīc tlanexillōtl)',
'cancel'         => 'Ticcuepāz',
'qbfind'         => 'Tlatēmōz',
'qbbrowse'       => 'Titlatēmōz',
'qbedit'         => 'Ticpatlāz',
'qbpageoptions'  => 'Inīn zāzanilli',
'qbpageinfo'     => 'Tlahcuilōltechcopa',
'qbmyoptions'    => 'Nozāzanil',
'qbspecialpages' => 'Nōncuahquīzqui āmatl',
'moredotdotdot'  => 'Huehca ōmpa...',
'mypage'         => 'Nozāzanil',
'mytalk'         => 'Notēixnāmiquiliz',
'anontalk'       => 'Inīn IP ītēixnāmiquiliz',
'navigation'     => 'Ācalpapanōliztli',
'and'            => 'īhuān',

# Metadata in edit box
'metadata_help' => 'Metatlahcuilōlli:',

'errorpagetitle'    => 'Ahcuallōtl',
'returnto'          => 'Timocuepāz īhuīc $1.',
'tagline'           => 'Īhuīcpa {{SITENAME}}',
'help'              => 'Tēpalēhuiliztli',
'search'            => 'Tlatēmōz',
'searchbutton'      => 'Tlatēmōz',
'go'                => 'Yāuh',
'searcharticle'     => 'Yāuh',
'history'           => 'tlahcuilōlloh',
'history_short'     => 'Tlahcuilōlloh',
'updatedmarker'     => 'ōmoyancuīx īhuīcpa xōcoyōc notlahpololiz',
'info_short'        => 'Tlanōnōtzaliztli',
'printableversion'  => 'Tepoztlahcuilōlli',
'permalink'         => 'Mochipa tzonhuiliztli',
'print'             => 'Tictepoztlahcuilōz',
'edit'              => 'Ticpatlāz',
'editthispage'      => 'Ticpatlāz inīn zāzanilli',
'delete'            => 'Ticpolōz',
'deletethispage'    => 'Ticpolōz inīn zāzanilli',
'undelete_short'    => 'Ahticpolōz {{PLURAL:$1|cē tlapatlaliztli|$1 tlapatlaliztli}}',
'protect'           => 'Ticquīxtīz',
'protect_change'    => 'ticpatlāz',
'protectthispage'   => 'Ticquīxtiāz inīn zāzanilli',
'unprotect'         => 'Ahticquīxtīz',
'unprotectthispage' => 'Ahticquīxtīz inīn zāzanilli',
'newpage'           => 'Yancuīc zāzanilli',
'talkpage'          => 'Tictlahtōz inīn zāzaniltechcopa',
'talkpagelinktext'  => 'Tēixnāmiquiliztli',
'specialpage'       => 'Nōncuahquīzqui āmatl',
'personaltools'     => 'In tlein nitēquitiltilia',
'postcomment'       => 'Xiquihcuiloa cē tlanehnemiliztli',
'articlepage'       => 'Xiquittaz in tlahcuilōlli',
'talk'              => 'tēixnāmiquiliztli',
'views'             => 'Tlachiyaliztli',
'toolbox'           => 'In tlein motequitiltia',
'userpage'          => 'Xiquitta tlatequitiltilīlli zāzanilli',
'projectpage'       => 'Xiquitta tlachīhualiztli zāzanilli',
'imagepage'         => 'Xiquitta īxiptli zāzanilli',
'mediawikipage'     => 'Xiquitta tlahcuilōltzin zāzanilli',
'templatepage'      => 'Tiquittāz nemachiyōtīlli zāzanilli',
'viewhelppage'      => 'Xiquitta tēpalēhuiliztli zāzanilli',
'categorypage'      => 'Xiquitta neneuhcāyōtl zāzanilli',
'viewtalkpage'      => 'Xiquitta tēixnāmiquiliztli zāzanilli',
'otherlanguages'    => 'Occequīntīn tlahtōlcopa',
'redirectedfrom'    => '(Ōmotlacuep īhuīcpa $1)',
'redirectpagesub'   => 'Ōmotlacuep zāzanilli',
'lastmodifiedat'    => 'Inīn zāzanilli ōtlapatlac catca īpan $2, $1.', # $1 date, $2 time
'viewcount'         => 'Inīn zāzanilli quintlapōhua {{PLURAL:$1|cē tlahpololiztli|$1 tlahpololiztli}}.',
'protectedpage'     => 'Ōmoquīxtix zāzanilli',
'jumpto'            => 'Yāuh īhuīc:',
'jumptonavigation'  => 'ācalpapanōliztli',
'jumptosearch'      => 'tlatēmoliztli',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Ītechcopa {{SITENAME}}',
'aboutpage'         => 'Project:Ītechcopa',
'copyright'         => 'Tlahcuilōltzin cah yōllōxoxouhqui īpan $1',
'copyrightpagename' => '{{SITENAME}} copyright',
'copyrightpage'     => '{{ns:project}}:Tlachīhualōni ītlapiyaliz',
'currentevents'     => 'Āxcāncāyōtl',
'currentevents-url' => 'Project:Āxcāncāyōtl',
'disclaimers'       => 'Nahuatīllahtōl',
'edithelp'          => 'Tlapatlaliztechcopa tēpalēhuiliztli',
'edithelppage'      => 'Help:¿Quēn motlahcuiloa cē zāzanilli?',
'faq'               => 'FAQ',
'faqpage'           => 'Project:FAQ',
'helppage'          => 'Help:Tlapiyaliztli',
'mainpage'          => 'Calīxatl',
'policy-url'        => 'Project:Nahuatīltōn',
'portal'            => 'Calīxcuātl tocalpōl',
'portal-url'        => 'Project:Calīxcuātl tocalpōl',
'privacy'           => 'Tlahcuilōlli piyaliznahuatīlli',
'privacypage'       => 'Project:Tlahcuilōlpiyaliztechcopa nahuatīltōn',
'sitesupport'       => 'Tēyocatiliztli',

'badaccess'        => 'Tlahuelītiliztechcopa ahcuallōtl',
'badaccess-group0' => 'Tehhuātl ahmo tiquichīhua inōn tiquiēlēhuia.',
'badaccess-group1' => 'Inōn tiquiēlēhuia zan quichīhuah tlatequitiltilīlli oncān: $1.',
'badaccess-group2' => 'Inōn tiquiēlēhuia zan quichīhuah tlatequitiltilīlli oncān: $1.',
'badaccess-groups' => 'Inōn tiquiēlēhuia zan quichīhuah tlatequitiltilīlli {{PLURAL:$2|oncān|oncān}}: $1.',

'ok'                      => 'Cualli',
'retrievedfrom'           => 'Īhuīcpa "$1"',
'youhavenewmessages'      => 'Tiquimpiya $1 ($2).',
'newmessageslink'         => 'yancuīc tlahcuilōltzintli',
'newmessagesdifflink'     => 'achto tlapatlaliztli',
'youhavenewmessagesmulti' => 'Tiquimpiya yancuīc tlahcuilōlli īpan $1',
'editsection'             => 'ticpatlāz',
'editold'                 => 'ticpatlāz',
'editsectionhint'         => 'Ticpatlacah: $1',
'toc'                     => 'Inīn tlahcuilōlco',
'showtoc'                 => 'xiquitta',
'hidetoc'                 => 'tictlātīz',
'thisisdeleted'           => '¿Tiquittāz nozo ahticpolōz $1?',
'viewdeleted'             => '¿Tiquiēlēhuia tiquitta $1?',
'restorelink'             => '{{PLURAL:$1|cē tlapatlaliztli polotic|$1 tlapatlaliztli polotic}}',
'feedlinks'               => 'Olōlpōl:',
'site-rss-feed'           => '$1 RSS huelītiliztli',
'site-atom-feed'          => '$1 Atom huelītiliztli',
'page-rss-feed'           => '"$1" RSS huelītiliztli',
'page-atom-feed'          => '"$1" RSS huelītiliztli',
'red-link-title'          => '$1 (ticchīhuāz)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Tlahcuilōlli',
'nstab-user'      => 'Tlatequitiltilīlli',
'nstab-media'     => 'Mēdiatl',
'nstab-special'   => 'Nōncuahquīzqui',
'nstab-project'   => 'Tlachīhualiztli zāzanilli',
'nstab-image'     => 'Īxiptli',
'nstab-mediawiki' => 'Tlahcuilōltzintli',
'nstab-template'  => 'Nemachiyōtīlli',
'nstab-help'      => 'Tēpalēhuiliztli',
'nstab-category'  => 'Neneuhcāyōtl',

# Main script and global functions
'nosuchaction'      => 'Ahmo ia tlachīhualiztli',
'nosuchspecialpage' => 'Ahmelāhuac nōncuahquīzqui zāzanilli',
'nospecialpagetext' => "<big>'''Ahmo ia nōncuahquīzqui āmatl ticnequi.'''</big>

Tihuelīti tiquitta mochi nōncuahquīzqui āmatl īpan [[Special:SpecialPages|{{int:specialpages}}]].",

# General errors
'error'               => 'Ahcuallōtl',
'databaseerror'       => 'Tlahcuilōltzintlān īahcuallo',
'laggedslavemode'     => 'Xiquitta: huel ahmo ia achi yancuīc in tlapatlaliztli inīn zāzanilco.',
'internalerror'       => 'Ahcuallōtl tlahtic',
'internalerror_info'  => 'Ahcuallōtl tlahtic: $1',
'filecopyerror'       => 'Ahmo ōmohuelītic tlacopīna "$1" īhuīc "$2".',
'filerenameerror'     => 'Ahmo ōmohuelītic tlazaca "$1" īhuīc "$2".',
'filedeleteerror'     => 'Ahmo ōmohuelītic tlapoloa "$1".',
'filenotfound'        => 'Ahmo ōmohuelītic tlanāmiqui "$1".',
'fileexistserror'     => 'Ahmo ōmohuelītih tlahcuiloa "$1" tlahcuilōlhuīc: tlahcuilōlli ia',
'cannotdelete'        => 'Ahmo huelīti mopoloa inōn zāzanilli.
Hueliz āquin ōquipolo achtopa.',
'badtitle'            => 'Ahcualli tōcāitl',
'badtitletext'        => 'Zāzanilli ticnequi in ītōca cah ahcualli, ahtlein quipiya nozo ahcualtzonhuiliztli interwiki tōcāhuicpa.
Hueliz quimpiya tlahtōl tlein ahmo mohuelītih motequitiltia tōcāpan.',
'viewsource'          => 'Tiquittāz tlahtōlcaquiliztilōni',
'viewsourcefor'       => '$1 ītechcopa',
'actionthrottled'     => 'Tlachīhualiztli ōmotzacuili',
'viewsourcetext'      => 'Tihuelīti tiquitta auh ticcopīna inīn zāzanilli ītlahtōlcaquiliztilōni:',
'sqlhidden'           => '(Tlatēmoliztli SQL omotlāti)',
'namespaceprotected'  => "Ahmo tiquihuelīti tiquimpatla zāzaniltin īpan '''$1'''.",
'ns-specialprotected' => 'Ahmohuelīti quimpatla nōncuahquīzqui zāzaniltin.',
'titleprotected'      => "Inīn zāzanilli ōmoquīxti ic tlachīhualiztli ic [[User:$1|$1]].
Ōquihto: ''$2''",

# Login and logout pages
'logouttitle'               => 'Ōtiquīz',
'welcomecreation'           => '== ¡Ximopanōlti, $1! ==

Mocuentah ōmochīuh. 

Ye tihuelīti titēchihtoa [[Special:Preferences|motlaēlēhuiliz]].',
'loginpagetitle'            => 'Ximomachiyōmaca/Ximocalaqui',
'yourname'                  => 'Motlatequitiltilīltōca:',
'yourpassword'              => 'Motlahtōlichtacāyo',
'yourpasswordagain'         => 'Motlahtōlichtacāyo occeppa',
'remembermypassword'        => 'Ticpiyāz motlacalaquiliz inīn chīuhpōhualhuazco',
'yourdomainname'            => 'Moāxcāyō',
'login'                     => 'Ximomachiyōmaca/Ximocalaqui',
'loginprompt'               => 'Tihuīquilia tiquimpiyāz cookies ic ticalaquīz {{SITENAME}}.',
'userlogin'                 => 'Ximomachiyōmaca/Ximocalaqui',
'logout'                    => 'Tiquīzāz',
'userlogout'                => 'Tiquīzāz',
'notloggedin'               => 'Ahmo ōtimocalac',
'nologin'                   => '¿Ahmo ticpiya cuentah? $1.',
'nologinlink'               => 'Ticchīhuāz cē cuentah',
'createaccount'             => 'Ticchīhuāz cē cuentah',
'gotaccount'                => '¿Ye ticpiya cē cuentah? $1.',
'gotaccountlink'            => 'Ximocalaqui',
'createaccountmail'         => 'e-mailcopa',
'badretype'                 => 'Ahneneuhqui motlahtōlichtacāyo.',
'userexists'                => 'Ye ia in tōcāitl ōquihcuilo.
Timitztlātlauhtiah xitlahcuiloa occē.',
'youremail'                 => 'E-mail:',
'username'                  => 'Tlatequitiltilīltōcāitl:',
'uid'                       => 'Tlatequitiltilīlli ID:',
'yourrealname'              => 'Melāhuac motōcā:',
'yourlanguage'              => 'Motlahtōl:',
'yournick'                  => 'Motōcātlaliz:',
'email'                     => 'E-mail',
'prefs-help-realname'       => 'Melāhuac motōca.
Intlā ticnequi, tlācah quimatīzqueh motequi.',
'loginerror'                => 'Ahcuallōtl tlacalaquiliztechcopa',
'prefs-help-email-required' => 'Tihuīquilia quihcuiloa mo e-mailcān.',
'noname'                    => 'Ahmo ōtiquihto cualli tlatequitiltilīlli tōcāitl.',
'loginsuccesstitle'         => 'Cualli calaquiliztli',
'loginsuccess'              => "'''Ōticalac {{SITENAME}} quemeh \"\$1\".'''",
'nosuchuser'                => 'Ahmo ia tlatequitiltilīlli ītōca "$1".
Xiquimpiya motlahtōl, nozo [[Special:UserLogin/signup|xicchīhua yancuīc cuentah]].',
'nosuchusershort'           => 'Ahmo cah tlatequitiltilīlli ītōcā "<nowiki>$1</nowiki>". 
Xiquimpiya motlahtōl.',
'nouserspecified'           => 'Mohuīquilia tiquihtoa cualli tlatequitiltilīltōcāitl.',
'wrongpassword'             => 'Ahcualli motlahtōlichtacāyo.
Timitztlātlauhtia xicchīhua occeppa.',
'wrongpasswordempty'        => 'Ayāc motlahtōlichtacāyo.
Timitztlātlauhtia xicchīhua occeppa.',
'mailmypassword'            => 'E-mailīz yancuīc motlahtōlichtacāyo',
'noemail'                   => '"$1" ahmo quipiya īe-mailcān.',
'passwordsent'              => 'Ōmoihuah yancuīc motlahtōlichtacāyo īhuīc mo e-mail ("$1").
Occeppa xicalaqui niman ticmatīz.',
'mailerror'                 => 'Ahcuallōtl e-mailcopa: $1',
'emailconfirmlink'          => 'Ticchicāhua mo e-mail',
'accountcreated'            => 'Cuentah ōmochīuh',
'accountcreatedtext'        => 'Tlatequitiltilīlcuentah ic $1 ōmochīuh.',
'createaccount-title'       => 'Cuentah ītlachīhualiz ic {{SITENAME}}',
'loginlanguagelabel'        => 'Tlahtōlli: $1',

# Password reset dialog
'resetpass_header'    => 'Xicpatlāz motlahtōlichtacāyo',
'resetpass_submit'    => 'Xicpatlāz motlahtōlichtacāyo auh xicalaquīz',
'resetpass_success'   => '¡Cualli ōmopatlac motlahtōlichtacāyo! Āxcān ticalaquicah...',
'resetpass_forbidden' => 'Tlahtōlichtacayōtl ahmo mohuelītih mopatlah',

# Edit page toolbar
'bold_sample'     => 'Tlīltic tlahcuilōlli',
'bold_tip'        => 'Tlīltic tlahcuilōlli',
'italic_sample'   => 'Italic tlahcuilōlli',
'italic_tip'      => 'Italic tlahcuilōlli',
'link_sample'     => 'Tzonhuiliztli ītōcā',
'link_tip'        => 'Tzonhuiliztli tlahtic',
'extlink_sample'  => 'http://www.tlamantli.com Tōcāitl',
'extlink_tip'     => 'Tzonhuilizcallān (xitequitiltia http://)',
'headline_sample' => 'Cuātlahcuilōlli',
'headline_tip'    => 'Iuhcāyōtl 2 tōcāyōtl',
'math_sample'     => 'Xihcuiloa nicān',
'math_tip'        => 'Tlapōhualmatiliztlahtōl (LaTeX)',
'media_tip'       => 'Mēdiahuīc tzonhuiliztli',
'sig_tip'         => 'Motōcā īca cāhuitl',
'hr_tip'          => 'Pāntli',

# Edit pages
'summary'                   => 'Mopatlaliz',
'minoredit'                 => 'Inīn cah tlapatlalitzintli',
'watchthis'                 => 'Tictlachiyāz inīn zāzanilli',
'savearticle'               => 'Ticpiyāz',
'preview'                   => 'Xiquitta achtochīhualiztli',
'showpreview'               => 'Xiquitta achtochīhualiztli',
'showlivepreview'           => 'Niman achtochīhualiztli',
'showdiff'                  => 'Tiquinttāz tlapatlaliztli',
'missingcommenttext'        => 'Timitztlātlauhtiah xitlanitlahcuiloa.',
'summary-preview'           => 'Tlahcuilōltōn achtochīhualiztli',
'blockedtitle'              => 'Ōmotzacuili tlatequitiltilīlli',
'blockednoreason'           => 'ahmo cah īxtlamatiliztli',
'blockedoriginalsource'     => "Nicān motta '''$1''' ītlahtōlcaquiliztilōni:",
'blockededitsource'         => "'''Mopatlaliz''' ītlahtōl īpan '''$1''' motta nicān:",
'whitelistedittitle'        => 'Tihuīquilia timocalaqui ic patla',
'whitelistedittext'         => 'Tihuīquilia $1 ic ticpatla zāzaniltin.',
'whitelistreadtitle'        => 'Tihuīquilia timocalaqui ic āmaxpōhua',
'whitelistreadtext'         => 'Tihuīquilia [[Special:UserLogin|timocalaqui]] ic tiquimpōhua zāzaniltin.',
'confirmedittitle'          => 'E-mail chicāhualiztli moēlēhuia ic ticpatla',
'nosuchsectiontitle'        => 'Ahmo ia inōn tlahtōltzintli',
'loginreqtitle'             => 'Ximocalaqui',
'loginreqlink'              => 'ximocalaqui',
'loginreqpagetext'          => 'Tihuīquilia $1 ic tiquintta occequīntīn zāzaniltin.',
'accmailtitle'              => 'Tlahtōlichtacāyōtl ōmoihuah.',
'accmailtext'               => '"$1" ītlahtōlichtacāyo ōmoihuah īhuīc $2.',
'newarticle'                => '(Yancuīc)',
'newarticletext'            => 'Ōtictocac cētiliztli cē zāzanilhuīc oc ahmo ia. Intlā quiēlēhuia quichīhua, xitlahcuiloa niman (nō xiquitta [[{{MediaWiki:Helppage}}|tēpalēhuiliztli zāzanilli]] huehca ōmpa tlapatlaliztli). Intlā ahmo, yāuh achtopa zāzanilli.',
'noarticletext'             => 'Āxcān, in ahmō cateh tlahtōl inīn zāzanilpan. Tihuelīti tictēmoa [[Special:Search/{{PAGENAME}}|inīn zāzaniltōcācopa]] occequīntīn zāzanilpan nozo [{{fullurl:{{FULLPAGENAME}}|action=edit}} quipatla inīn zāzanilli].',
'userpage-userdoesnotexist' => 'Ahmo ia cuentah "$1" ītōca. Timitztlātlauhtiah xitēchquinōtza intlā ticchīhuāz intlā nozo ticpatlāz inīn zāzanilli.',
'usercsspreview'            => "'''Ca inīn moachtochīhualiz ītechcopa moCSS.'''
'''¡Ahmo ōmochīuh nozan!'''",
'userjspreview'             => "'''Ca inīn moachtochīhualiz ītechcopa moJavaScript.'''
'''¡Ahmo ōmochīuh nozan!'''",
'updated'                   => '(Ōmoyancuīli)',
'note'                      => '<strong>Tlahtōlcaquiliztilōni:</strong>',
'previewnote'               => '<strong>¡Ca inīn moachtochīhualiz, auh mopatlaliz ahmō cateh ōmochīhuah nozan!</strong>',
'editing'                   => 'Ticpatlacah $1',
'editingsection'            => 'Ticpatlacah $1 (tlahtōltzintli)',
'editingcomment'            => 'Ticpatlacah $1 (tlahtōltzintli)',
'editconflict'              => 'Tlapatlaliztli yāōyōtōn: $1',
'yourtext'                  => 'Motlahcuilōl',
'yourdiff'                  => 'Ahneneuhquiliztli',
'copyrightwarning'          => '<small>Timitztlātlauhtiah xiquitta mochi mopatlaliz cana {{SITENAME}} tlatzayāna īpan $2 (huēhca ōmpa xiquitta $1). Āqueh tlācah quipatlazqueh in motlahcuilōl auh tlatzayāna occeppa; intlā ahmō ticnequi, zātēpan ahmō titlahcuilōz nicān. Nō mitzihtoah in ōtitlahcuiloh ahmō quipiya in copyright nozo in yōllōxoxouhqui tlahcuilōlli. <strong>¡AHMŌ XITĒQUITILTIA AHYŌLLŌXOXOUHQUI TLAHCUILŌLLI!</strong></small>',
'copyrightwarning2'         => '<small>Āqueh tlācah quipatlazqueh in motlahcuilōl auh tlatzayāna occeppa; intlā ahmō ticnequi, zātēpan ahmō titlahcuilōz nicān {{SITENAME}}. Nō mitzihtoah in ōtitlahcuiloh ahmō quipiya in copyright nozo in yōllōxoxouhqui tlahcuilōlli (huēhca ōmpa xiquitta $1). <strong>¡AHMŌ TIQUINTEQUITILTIA AHYŌLLŌXOXOUHQUI TLAHCUILŌLLI!</strong></small>',
'longpageerror'             => '<strong>AHCUALLŌTL: Motlahcuilōl cah huēiyac $1 KB, huehca ōmpa $2 KB. Ahmo mopiyāz.</strong>',
'templatesused'             => 'Nemachiyōtīlli inīn zāzanilpan:',
'templatesusedpreview'      => 'Nemachiyōtīlli motequitiltia inīn achtochīhualizpan:',
'templatesusedsection'      => 'Nemachiyōtīlli motequitiltia nicān:',
'template-protected'        => '(ōmoquīxti)',
'nocreatetext'              => 'Inīn huiqui ōquitzacuili tlahuelītiliztli ic tlachīhua yancuīc zāzaniltin. Tichuelīti ticcuepa auh ticpatla cē zāzanilli, [[Special:UserLogin|xicalaqui nozo xicchīhua cē cuentah]].',
'nocreate-loggedin'         => 'Ahmo tihuelīti tiquinchīhua yancuīc zāzaniltin.',
'permissionserrors'         => 'Huelītiliztechcopa ahcuallōtl',
'permissionserrorstext'     => 'Ahmo tihuelīti quichīhua inōn, inīn {{PLURAL:$1|īxtlamatilizpampa|īxtlamatilizpampa}}:',

# Account creation failure
'cantcreateaccounttitle' => 'Ahmo huelītih mochīhua cuentah',
'cantcreateaccount-text' => "[[User:$3|$3]] ōcquīxti cuentah tlachīhualiztli īpal inīn IP ('''$1''').

Īxtlamatiliztli īpal $3 cah ''$2''",

# History pages
'viewpagelogs'        => 'Tiquinttāz tlahcuilōlloh inīn zāzaniltechcopa',
'nohistory'           => 'Ahmo cah tlapatlaliztechcopa tlahcuilōlloh inīn zāzaniltechcopa.',
'revnotfound'         => 'Ahmo ōmonēxti tlachiyaliztli',
'currentrev'          => 'Āxcān tlapatlaliztli',
'revisionasof'        => 'Tlachiyaliztli īpan $1',
'revision-info'       => 'Tlachiyaliztli īpan $1; $2',
'previousrevision'    => '← Huēhueh tlapatlaliztli',
'nextrevision'        => 'Yancuīc tlapatlaliztli →',
'currentrevisionlink' => 'Āxcān tlapatlaliztli',
'cur'                 => 'āxcān',
'next'                => 'niman',
'last'                => 'xōcoyōc',
'page_first'          => 'achto',
'page_last'           => 'xōcoyōc',
'deletedrev'          => '[ōmopolo]',
'histfirst'           => 'Achto',
'histlast'            => 'Yancuīc',
'historysize'         => '({{PLURAL:$1|1 byte|$1 bytes}})',
'historyempty'        => '(iztāc)',

# Revision feed
'history-feed-title'          => 'Tlachiyaliztli tlahcuilōlloh',
'history-feed-description'    => 'Tlachiyaliztli tlahcuilōlloh inīn zāzaniltechcopa huiquipan',
'history-feed-item-nocomment' => '$1 īpan $2', # user at time
'history-feed-empty'          => 'In zāzanilli tiquiēlēhuia ahmo ia.
Hueliz ōmopolo huiqui nozo ōmozacac.
[[Special:Search|Xitēmoa huiquipan]] yancuīc huēyi zāzaniltin.',

# Revision deletion
'rev-delundel'         => 'tiquittāz/tictlātīz',
'revisiondelete'       => 'Tiquimpolōz/ahtiquimpolōz tlachiyaliztli',
'revdelete-selected'   => "'''{{PLURAL:$2|Tlachiyaliztli ōmoēlēhui|Tlachiyaliztli ōmoēlēhuih}} [[:$1]] ītechcopa:'''",
'revdelete-hide-text'  => 'Tictlātīz tlachiyaliztli ītlahcuilōl',
'revdelete-hide-image' => 'Tictlātīz tlahcuilōlli ītlapiyaliz',

# History merging
'mergehistory-from'           => 'Zāzanilhuīcpa:',
'mergehistory-into'           => 'Zāzanilhuīc:',
'mergehistory-no-source'      => 'Zāzanilhuīcpa $1 ahmo ia.',
'mergehistory-no-destination' => 'Zāzanilhuīc $1 ahmo ia.',

# Diffs
'history-title' => '"$1" ītlachiyaliz tlahcuilōlloh',
'difference'    => '(Ahneneuhquiliztli tlapatlaliznepantlah)',
'lineno'        => 'Pāntli $1:',
'editundo'      => 'ahticchīhuāz',
'diff-multi'    => '({{PLURAL:$1|Cē tlapatlaliztli nepantlah ahmo motta|$1 tlapatlaliztli nepantlah ahmo mottah}}.)',

# Search results
'searchresults'         => 'Tlatēmoliztli',
'searchsubtitle'        => 'Ōtictēmōz \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|mochīntīn zāzaniltin mopēhua īca "$1"]] | [[Special:WhatLinksHere/$1|mochīntīn zāzaniltin tzonhuilia "$1" īhuīc]])',
'searchsubtitleinvalid' => "Ōtictēmōz '''$1'''",
'noexactmatch'          => "'''Ahmo ia zāzanilli ītōcā \"\$1\".''' Tihuelīti [[:\$1|ticchīhua]].",
'noexactmatch-nocreate' => "'''Ahmo ia \"\$1\" zāzanilli.'''",
'prevn'                 => '$1 achtopa',
'nextn'                 => 'niman $1',
'viewprevnext'          => 'Xiquintta ($1) ($2) ($3).',
'powersearch'           => 'Chicāhuac tlatēmoliztli',

# Preferences page
'preferences'           => 'Tlaēlēhuiliztli',
'mypreferences'         => 'Notlaēlēhuiliz',
'prefs-edits'           => 'Tlapatlaliztli tlapōhualli:',
'prefsnologin'          => 'Ahmo ōtimocalac',
'qbsettings-none'       => 'Ahtlein',
'changepassword'        => 'Ticpatlāz motlahtōlichtacāyo',
'math'                  => 'Tlapōhualmatiliztli',
'dateformat'            => 'Cāuhtiliztli iuhcāyōtl',
'datedefault'           => 'Ayāc tlanequiliztli',
'datetime'              => 'Cāuhtiliztli īhuān cāhuitl',
'prefs-personal'        => 'Motlācatlanōnōtzaliz',
'prefs-rc'              => 'Yancuīc tlapatlaliztli',
'prefs-watchlist'       => 'Tlachiyaliztli',
'prefs-watchlist-days'  => 'Tōnaltin tiquinttāz tlachiyalizpan:',
'prefs-watchlist-edits' => 'Tlapatlaliztli tiquintta tlachiyalizpan:',
'prefs-misc'            => 'Zāzo',
'saveprefs'             => 'Ticpiyāz',
'oldpassword'           => 'Huēhueh motlahtōlichtacayo:',
'newpassword'           => 'Yancuīc motlahtōlichtacayo:',
'retypenew'             => 'Occeppa xiquihcuiloa yancuīc motlahtōlichtacayo:',
'textboxsize'           => 'Tlapatlaliztli',
'rows'                  => 'Pāntli:',
'searchresultshead'     => 'Tlatēmoliztli',
'contextlines'          => 'Pāntli tlahtōltechcopa:',
'contextchars'          => 'Tlahtōltechcopa ic pāntli:',
'recentchangesdays'     => 'Tōnaltin tiquinttāz yancuīc tlapatlalizpan:',
'localtime'             => 'Cāhuitl nicān',
'defaultns'             => 'Tlatēmōz inīn tōcātzimpan ic default:',
'default'               => 'ic default',
'files'                 => 'Tlahcuilōlli',

# User rights
'userrights-user-editname' => 'Xihcuiloa cē tlatequitiltilīltōcāitl:',
'editusergroup'            => 'Tiquimpatlāz tlatequitiltilīlli olōlli',
'userrights-editusergroup' => 'Tiquimpatlāz tlatequitiltilīlli olōlli',
'saveusergroups'           => 'Tiquimpiyāz tlatequitiltilīlli olōlli',
'userrights-groupsmember'  => 'Olōlco:',
'userrights-reason'        => 'Tlapatlaliztli īxtlamatiliztli:',
'userrights-no-interwiki'  => 'Ahmo tihuelīti ticpatla tlatequitiltilīlli huelītiliztli occequīntīn huiquipan.',

# Groups
'group'       => 'Olōlli:',
'group-bot'   => 'Tepoztlācah',
'group-sysop' => 'Tlahcuilōlpixqueh',
'group-all'   => '(mochīntīn)',

'group-bot-member'   => 'Tepoztlācatl',
'group-sysop-member' => 'Tlahcuilōlpixqui',

'grouppage-bot'   => '{{ns:project}}:Tepoztlācah',
'grouppage-sysop' => '{{ns:project}}:Tlahcuilōlpixqueh',

# User rights log
'rightslog'  => 'Tlatequitiltilīlli huelītiliztli tlahcuilōlloh',
'rightsnone' => 'ahtlein',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|tlapatlaliztli|tlapatlaliztli}}',
'recentchanges'                     => 'Yancuīc tlapatlaliztli',
'recentchangestext'                 => 'Xiquinttāz in achi yancuīc ahmo occequīntīn tlapatlaliztli huiquipan inīn zāzanilpan.',
'rcnote'                            => 'Nicān in xōcoyōc <b>$1</b> patlaliztli īpan in xōcoyōc <b>$2</b> tōnalli cah, ōāxcānic $3',
'rclistfrom'                        => 'Xiquinttāz yancuīc tlapatlaliztli īhuīcpa $1',
'rcshowhideminor'                   => '$1 tlapatlalitzintli',
'rcshowhidebots'                    => '$1 tepoztlācah',
'rcshowhideliu'                     => '$1 tlatequitiltilīlli ōmocalacqueh',
'rcshowhideanons'                   => '$1 ahtōcā tlatequitiltilīlli',
'rcshowhidepatr'                    => '$1 tlapatlaliztli mochiyahua',
'rcshowhidemine'                    => '$1 notlahcuilōl',
'rclinks'                           => 'Xiquintta xōcoyōc $1 tlapatlaliztli xōcoyōc $2 tōnalpan.<br />$3',
'diff'                              => 'ahneneuh',
'hist'                              => 'tlahcuil',
'hide'                              => 'Tiquintlātīz',
'show'                              => 'Tiquinttāz',
'minoreditletter'                   => 'p',
'newpageletter'                     => 'Y',
'boteditletter'                     => 'T',
'number_of_watching_users_pageview' => '[$1 tlatequitiltilīlli {{PLURAL:$1|tlachiya|tlachiyah}}]',
'rc_categories_any'                 => 'Zāzo',
'newsectionsummary'                 => 'Yancuīc tlahtōltzintli: /* $1 */',

# Recent changes linked
'recentchangeslinked'       => 'Tlapatlaliztli tzonhuilizpan',
'recentchangeslinked-title' => 'Tlapatlaliztli "$1" ītechcopa',

# Upload
'upload'               => 'Tlahcuilōlquetza',
'uploadbtn'            => 'Tlahcuilōlquetza',
'reupload'             => 'Tiquiquetzāz occeppa',
'uploadnologin'        => 'Ahmo ōtimocalac',
'uploaderror'          => 'Tlaquetzaliztli ahcuallōtl',
'uploadlog'            => 'tlaquetzaliztli tlahcuilōlloh',
'uploadlogpage'        => 'Tlaquetzaliztli tlahcuilōlloh',
'filename'             => 'Tlahcuilōlli ītōcā',
'filedesc'             => 'Tlahcuilōltōn',
'fileuploadsummary'    => 'Tlahcuilōltōn:',
'filestatus'           => 'Copyright:',
'filesource'           => 'Īhuīcpa:',
'uploadedfiles'        => 'Tlahcuilōlli ōmoquetz',
'minlength1'           => 'Tlahcuilōltōcāitl quihuīlquilia huehca ōmpa cē tlahtōl.',
'badfilename'          => 'Īxiptli ītōcā ōmopatlac īhuīc "$1".',
'filetype-missing'     => 'Tlahcuilōlli ahmo quipiya huēiyaquiliztli (quemeh ".jpg").',
'large-file'           => 'Mā tlahcuilōlli ahmo achi huēiyac $1; inīn cah $2.',
'fileexists-extension' => 'Tlahcuilōlli zan iuh tōcātica ia:<br />
Tlahcuilōlli moquetzacah: <strong><tt>$1</tt></strong><br />
Tlahcuilōlli tlein ia ītōca: <strong><tt>$2</tt></strong><br />
Timitztlātlauhtiah, xitlahcuiloa occē tōcāitl.',
'fileexists-thumb'     => "<center>'''Tlahcuilōlli ia'''</center>",
'successfulupload'     => 'Cualli quetzaliztli',
'savefile'             => 'Quipiyāz tlahcuilōlli',
'uploadedimage'        => 'ōmoquetz "[[$1]]"',
'overwroteimage'       => 'ōmoquetz yancuīc "[[$1]]" iuhcāyōtl',
'uploaddisabled'       => 'Ahmo mohuelīti tlahcuilōlquetzā',
'uploaddisabledtext'   => 'Ahmo huelīti moquetzazqueh tlahcuilōlli.',
'sourcefilename'       => 'Tōcāhuīcpa:',
'destfilename'         => 'Tōcāhuīc:',
'watchthisupload'      => 'Tictlachiyāz inīn zāzanilli',

'upload_source_file' => ' (cē tlahcuilōlli mochīuhpōhualhuazco)',

# Image list
'imagelist'                 => 'Mochīntīn īxiptli',
'imagelisttext'             => "Nicān {{PLURAL:$1|mopiya|mopiyah}} '''$1''' īxiptli $2 iuhcopa.",
'ilsubmit'                  => 'Tlatēmōz',
'bydate'                    => 'tōnalcopa',
'imgfile'                   => 'īxiptli',
'filehist'                  => 'Tlahcuilōlli tlahcuilōlloh',
'filehist-deleteall'        => 'tiquimpolōz mochīntīn',
'filehist-deleteone'        => 'ticpolōz',
'filehist-revert'           => 'tlacuepāz',
'filehist-current'          => 'āxcān',
'filehist-user'             => 'Tlatequitiltilīlli',
'imagelinks'                => 'Īxiphuīc tzonhuiliztli',
'nolinkstoimage'            => 'Ahmo cateh zāzaniltin tlein tzonhuiliah inīn tlahcuilōlhuīc.',
'sharedupload'              => 'Inīn īxiptli huelīti motequitiltia zāzocāmpa',
'noimage'                   => 'Ahmo ia inōn tlahcuilōlli; $1',
'noimage-linktext'          => 'ticquetzāz cē',
'uploadnewversion-linktext' => 'Ticquetzāz yancuīc tlahcuilōlli',
'imagelist_name'            => 'Tōcāitl',
'imagelist_user'            => 'Tlatequitiltilīlli',
'imagelist_search_for'      => 'Tlatēmōz mēdiatl tōcācopa:',

# File reversion
'filerevert'        => 'Ticcuepāz $1',
'filerevert-legend' => 'Tlahcuilōlli tlacuepaliztli',
'filerevert-submit' => 'Tlacuepāz',

# File deletion
'filedelete'                  => 'Ticpolōz $1',
'filedelete-legend'           => 'Ticpolōz tlahcuilōlli',
'filedelete-comment'          => 'Tlapololiztli īxtlamatiliztli:',
'filedelete-submit'           => 'Ticpolōz',
'filedelete-success'          => "Ōmopolo '''$1'''.",
'filedelete-nofile'           => "'''$1''' ahmo ia.",
'filedelete-otherreason'      => 'Occē īxtlamatiliztli:',
'filedelete-reason-otherlist' => 'Occē īxtlamatiliztli',

# MIME search
'mimesearch' => 'MIME tlatēmoliztli',
'mimetype'   => 'MIME iuhcāyōtl:',
'download'   => 'tictemōz',

# Unwatched pages
'unwatchedpages' => 'Zāzaniltin ahmo motlachiya',

# List redirects
'listredirects' => 'Tlacuepaliztli',

# Unused templates
'unusedtemplates'    => 'Nemachiyōtīlli ahmotequitiltiah',
'unusedtemplateswlh' => 'occequīntīn tzonhuiliztli',

# Random page
'randompage'         => 'Zāzozāzanilli',
'randompage-nopages' => 'Ahmo cateh zāzaniltin īpan inīn tōcātzin.',

# Random redirect
'randomredirect' => 'Zāzotlacuepaliztli',

# Statistics
'statistics'    => 'Tlapōhualiztli',
'sitestats'     => '{{SITENAME}} ītlapōhualiz',
'userstats'     => 'Tlatequitiltilīlli ītlapōhualiz',
'sitestatstext' => "{{PLURAL:$1|Cah '''1''' zāzanilli|Cateh '''$1''' zāzaniltin}} nicān.
Inīn quimpiya tēixnāmiquiliztli zāzanilli, {{SITENAME}} ītechcopa zāzanilli, machiyōtōn, tlacuepaliztli auh occequīntīn hueliz ahmo cualli.
Ahtle, in {{PLURAL:$2|cah '''1''' cualli zāzanilli|cateh '''$2''' cualli zāzaniltin}}.

{{PLURAL:$8|Nō cah '''$8''' tlahcuilōlli|Nō cateh '''$8''' tlahcuilōlli}} inīn huēychīuhpōhualhuazco.

In īhuīcpa huiqui īpēhualiz {{PLURAL:$3|ōcatca|ōcatcah}} '''$3''' tlahpaloliztli auh '''$4''' tlapatlaliztli.
Inīn quicētilia huehca '''$5''' tlapatlaliztli cēcem zāzanilli auh '''$6''' tlahpaloliztli cēcem tlapatlaliztli.

Huēiyacaliztli [http://www.mediawiki.org/wiki/Manual:Job_queue tequilcān] cah '''$7'''.",

'disambiguations' => 'Ōmetōcāitl zāzaniltin',

'doubleredirects' => 'Ōntetl tlacuepaliztli',

'brokenredirects'        => 'Tzomoc tlacuepaliztli',
'brokenredirects-edit'   => '(ticpatlāz)',
'brokenredirects-delete' => '(ticpolōz)',

'withoutinterwiki'        => 'Zāzaniltin ahtle tzonhuiliztli',
'withoutinterwiki-submit' => 'Tiquittāz',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|bytes}}',
'ncategories'             => '$1 {{PLURAL:$1|neneuhcāyōtl|neneuhcāyōtl}}',
'nlinks'                  => '$1 {{PLURAL:$1|tzonhuiliztli|tzonhuiliztli}}',
'nmembers'                => '$1 {{PLURAL:$1|tlahcuilōlli|tlahcuilōlli}}',
'nrevisions'              => '$1 {{PLURAL:$1|tlapiyaliztli|tlapiyaliztli}}',
'uncategorizedpages'      => 'Zāzaniltin ahmoneneuhcāyōtiah',
'uncategorizedcategories' => 'Neneuhcāyōtl ahmoneneuhcāyōtiah',
'uncategorizedimages'     => 'Īxiptli ahmoneneuhcāyōtiah',
'uncategorizedtemplates'  => 'Nemachiyōtīlli ahmoneneuhcāyōtiah',
'unusedcategories'        => 'Neneuhcāyōtl ahmotequitiltiah',
'unusedimages'            => 'Īxiptli ahmotequitiltiah',
'wantedcategories'        => 'Neneuhcāyōtl moēlēhuiah',
'wantedpages'             => 'Zāzaniltin moēlēhuiah',
'mostlinked'              => 'Tlahcuilōlli achi motzonhuilia',
'mostlinkedcategories'    => 'Neneuhcāyōtl achi motzonhuilia',
'mostlinkedtemplates'     => 'Nemachiyōtīlli achi motzonhuilia',
'mostimages'              => 'Īxiptli tlein in achi motzonhuilia',
'allpages'                => 'Mochīntīn zāzanilli',
'shortpages'              => 'Ahhuēiyac zāzaniltin',
'longpages'               => 'Huēiyac zāzaniltin',
'deadendpages'            => 'Ahtlaquīzaliztli zāzaniltin',
'protectedpages'          => 'Zāzaniltin ōmoquīxti',
'protectedpagestext'      => 'Inīn zāzaniltin ōmoquīxtih, auh ahmo mohuelītih mozacah nozo mopatlah',
'protectedtitles'         => 'Tōcāitl ōmoquīxtih',
'protectedtitlestext'     => 'Inīn tōcāitl ōmoquīxtih, auh ahmo mohuelītih mochīhuah',
'listusers'               => 'Tlatequitiltilīlli',
'specialpages'            => 'Nōncuahquīzqui āmatl',
'newpages'                => 'Yancuīc zāzaniltin',
'newpages-username'       => 'Tlatequitiltilīltōcāitl:',
'ancientpages'            => 'Huēhuehzāzanilli',
'move'                    => 'Ticzacāz',
'movethispage'            => 'Ticzacāz inīn zāzanilli',
'pager-newer-n'           => '{{PLURAL:$1|1 yancuīc|$1 yancuīc}}',
'pager-older-n'           => '{{PLURAL:$1|1 huēhuetl|$1 huēhueh}}',

# Book sources
'booksources-go' => 'Yāuh',

'categoriespagetext' => 'Inīn neneuhcāyōtl quimpiyah zāzanilli nozo mēdiah.
[[Special:UnusedCategories|Neneuhcāyōtl ahmo motequitiltia]] ahmo mottah nicān.
Nō xiquitta [[Special:WantedCategories|neneuhcāyōtl monequi]].',
'alphaindexline'     => '$1 oc $2',
'version'            => 'Machiyōtzin',

# Special:Log
'specialloguserlabel'  => 'Tlatequitiltilīlli:',
'speciallogtitlelabel' => 'Tōcāitl:',
'log'                  => 'Tlahcuilōlloh',
'all-logs-page'        => 'Mochīntīn tlahcuilōlloh',
'log-search-legend'    => 'Tiquintēmōz tlahcuilōlloh',
'log-search-submit'    => 'Yāuh',

# Special:Allpages
'nextpage'          => 'Niman zāzanilli ($1)',
'prevpage'          => 'Achto zāzanilli ($1)',
'allarticles'       => 'Mochīntīn tlahcuilōlli',
'allinnamespace'    => 'Mochīntīn zāzanilli (īpan $1)',
'allnotinnamespace' => 'Mochīntīn zāzanilli (quihcuāc $1)',
'allpagesprev'      => 'Achtopa',
'allpagesnext'      => 'Niman',
'allpagessubmit'    => 'Tiquittāz',

# Special:Listusers
'listusers-submit' => 'Tiquittāz',

# E-mail user
'emailuser'       => 'Tique-mailīz inīn tlatequitiltilīlli',
'defemailsubject' => '{{SITENAME}} e-mail',
'emailfrom'       => 'Īhuīcpa:',
'emailto'         => 'Īhuīc:',
'emailmessage'    => 'Tlahcuilōltzintli:',
'emailsend'       => 'Ticquihuāz',
'emailsent'       => 'E-mail ōmoihuah',

# Watchlist
'watchlist'            => 'Notlachiyaliz',
'mywatchlist'          => 'Notlachiyaliz',
'watchlistfor'         => "('''$1''' ītechcopa)",
'watchnologin'         => 'Ahmo ōtimocalac',
'addedwatch'           => 'Ōmocētili tlachiyalizpan',
'removedwatch'         => 'Ōmopolo īpan motlachiyaliz',
'removedwatchtext'     => 'Zāzanilli "[[:$1]]" ōmopolo [[Special:Watchlist|motlachiyalizco]].',
'watch'                => 'Tictlachiyāz',
'watchthispage'        => 'Tictlachiyāz inīn zāzanilli',
'unwatch'              => 'Ahtictlachiyāz',
'watchlist-details'    => '{{PLURAL:$1|$1 zāzanilli|$1 zāzaniltin}} motlachiyaliz, ahmo mopōhua tēixnāmiquiliztli.',
'wlshowlast'           => 'Tiquinttāz tlapatlaliztli īhuīcpa achto $1 yēmpohualminuhtli, $2 tōnaltin $3',
'watchlist-hide-bots'  => 'Tiquintlātīz tepoztlācah īntlapatlaliz',
'watchlist-hide-own'   => 'Tiquintlātīz notlahcuilōl',
'watchlist-hide-minor' => 'Tiquintlātīz tlapatlalitzintli',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Tlachiyacah...',
'unwatching' => 'Ahtlachiyacah...',

'enotif_newpagetext'           => 'Inīn cah yancuīc zāzanilli.',
'enotif_impersonal_salutation' => 'tlatequitiltilīlli īpan {{SITENAME}}',
'changed'                      => 'ōmotlacuep',
'created'                      => 'ōmochīuh',
'enotif_anon_editor'           => 'ahtōcātlatequitiltilīlli $1',
'enotif_body'                  => 'Mahuizoh $WATCHINGUSERNAME,

{{SITENAME}} zāzanilli "$PAGETITLE" $CHANGEDORCREATED īpal tlatequitiltilīlli $PAGEEDITOR īpan $PAGEEDITDATE.
Āxcān tlachiyaliztli mopiya īpan {{fullurl:$PAGETITLE}}

$NEWPAGE

Tlapatlaliztli īxtlamatiliztli cah: $PAGESUMMARY $PAGEMINOREDIT

Ic ticnotzāz tlatequitiltilīlli:
e-mail: {{fullurl:Special:Emailuser|target=$PAGEEDITOR}}
huiquipan: {{fullurl:User:$PAGEEDITOR}}

Ic ticpiyāz yancuīc tlanōnōtzaliztli tlapatlalizcopa inīn zāzanilpan, tihuīquilīz tictlahpolōz occeppa.
Nō tihuelīti, motlachiyalizpan, ticpatlāz motlanequiliz tlanōnōtzaliztechcopa in zāzanilli tiquinchiya.

             Mocnīuh {{SITENAME}} ītlanōnōtzaliz.

--
Ticpatlāz motlanequiliz:
{{fullurl:Special:Watchlist|edit=yes}}',

# Delete/protect/revert
'deletepage'                  => 'Ticpolōz inīn zāzanilli',
'excontent'                   => "Tlapiyaliztli ōcatca: '$1'",
'excontentauthor'             => "Tlapiyaliztli ōcatca: '$1' (auh zancē ōquipatlac ōcatca '[[Special:Contributions/$2|$2]]')",
'exblank'                     => 'zāzanilli ōcatca iztāc',
'delete-confirm'              => 'Ticpolōz "$1"',
'delete-legend'               => 'Ticpolōz',
'actioncomplete'              => 'Cēntetl',
'deletedtext'                 => '"<nowiki>$1</nowiki>" ōmopolo.
Xiquitta $2 ic yancuīc tlapololiztli.',
'deletedarticle'              => 'ōmopolo "$1"',
'dellogpage'                  => 'Tlapololiztli tlahcuilōlloh',
'deletionlog'                 => 'tlapololiztli tlahcuilōlloh',
'deletecomment'               => 'Tlapololiztli īxtlamatiliztli:',
'deleteotherreason'           => 'Occē īxtlamatiliztli:',
'deletereasonotherlist'       => 'Occē īxtlamatiliztli',
'rollback_short'              => 'Tlacuepāz',
'rollbacklink'                => 'tlacuepāz',
'rollback-success'            => 'Ōmotlacuep $1 ītlahcuilōl; āxcān achto $2 ītlahcuilōl.',
'protectedarticle'            => 'ōmoquīxti "[[$1]]"',
'unprotectedarticle'          => 'ōahmoquīxti "[[$1]]"',
'protectexpiry'               => 'Tlamiliztli:',
'protect_expiry_invalid'      => 'Ahcualli tlamiliztli cāhuitl.',
'protect-default'             => '(ic default)',
'protect-fallback'            => 'Tiquihuīquilia tlahuelītiliztli "$1"',
'protect-level-autoconfirmed' => 'Tiquinquīxtīz tlatequitiltilīlli tlein ahmo ōmocalac',
'protect-expiring'            => 'motlamīz $1 (UTC)',

# Restrictions (nouns)
'restriction-edit'   => 'Ticpatlāz',
'restriction-move'   => 'Ticzacāz',
'restriction-create' => 'Ticchīhuāz',

# Undelete
'undelete'                  => 'Tiquinttāz zāzaniltin ōmopolōzqueh',
'viewdeletedpage'           => 'Tiquinttāz zāzaniltin ōmopolōzqueh',
'undeletebtn'               => 'Ahticpolōz',
'undeletelink'              => 'ahticpolōz',
'undelete-search-box'       => 'Tiquintlatēmōz zāzaniltin ōmopolōz',
'undelete-search-prefix'    => 'Tiquittāz zāzaniltin mopēhua īca:',
'undelete-search-submit'    => 'Tlatēmōz',
'undelete-error-short'      => 'Ahcuallōtl ihcuāc momāquīxtiya: $1',
'undelete-show-file-submit' => 'Quemah',

# Namespace form on various pages
'namespace'      => 'Tōcātzin:',
'invert'         => 'Tlacuepāz motlahtōl',
'blanknamespace' => '(Huēyi)',

# Contributions
'contributions' => 'Ītlahcuilōl',
'mycontris'     => 'Notlahcuilōl',
'contribsub2'   => '$1 ($2)',
'uctop'         => ' (ahco)',
'month'         => 'Īhuīcpa mētztli (auh achtopa):',
'year'          => 'Xiuhhuīcpa (auh achtopa):',

'sp-contributions-newbies'     => 'Tiquinttāz zan yancuīc tlatequitiltilīlli īntlapatlaliz',
'sp-contributions-newbies-sub' => 'Ic yancuīc',
'sp-contributions-blocklog'    => 'Tlatzacuiliztli tlahcuilōlloh',
'sp-contributions-search'      => 'Tiquintlatēmōz tlapatlaliztli',
'sp-contributions-username'    => 'IP nozo tlatequitiltilīlli ītōcā:',
'sp-contributions-submit'      => 'Tlatēmōz',

# What links here
'whatlinkshere'       => 'In tlein quitzonhuilia nicān',
'whatlinkshere-title' => 'Zāzaniltin quitzonhuiliah $1',
'whatlinkshere-page'  => 'Zāzanilli:',
'linklistsub'         => '(Tzonhuiliztli)',
'linkshere'           => "Inīn zāzaniltin quitzonhuiliah '''[[:$1]]''' īhuīc:",
'nolinkshere'         => "Ahtle quitzonhuilia '''[[:$1]]''' īhuīc.",
'isredirect'          => 'ōmotlacuep zāzanilli',
'whatlinkshere-prev'  => '{{PLURAL:$1|achtopa|$1 achtopa}}',
'whatlinkshere-next'  => '{{PLURAL:$1|niman|$1 niman}}',
'whatlinkshere-links' => '← tzonhuiliztli',

# Block/unblock
'blockip'            => 'Tiquitzacuilīz tlatequitiltilīlli',
'ipaddress'          => 'IP:',
'ipadressorusername' => 'IP nozo tlatequitiltilīlli ītōcā:',
'ipbexpiry'          => 'Motlamia:',
'ipbreason'          => 'Īīxtlamatiliztli:',
'ipbreasonotherlist' => 'Occē īxtlamatiliztli',
'ipbsubmit'          => 'Tiquitzacuilīz inīn tlatequitiltilīlli',
'ipbother'           => 'Occē cāuhpan:',
'ipboptions'         => '2 yēmpōhualminutl:2 hours,1 tōnalli:1 day,3 tōnaltin:3 days,7 tōnaltin:1 week,14 tōnaltin:2 weeks,1 mētztli:1 month,3 mētztli:3 months,6 mētztli:6 months,1 xihuitl:1 year,Mochipa:infinite', # display1:time1,display2:time2,...
'ipbotheroption'     => 'occē',
'ipbotherreason'     => 'Occē īxtlamatiliztli:',
'badipaddress'       => 'Ahcualli IP',
'blockipsuccesssub'  => 'Cualli tlatzacuiliztli',
'ipb-unblock-addr'   => 'Ahtiquitzacuilīz $1',
'ipb-unblock'        => 'Ahtiquitzacuilīz IP nozo tlatequitiltilīlli',
'unblockip'          => 'Ahtiquitzacuilīz tlatequitiltilīlli',
'ipblocklist-submit' => 'Tlatēmōz',
'blocklistline'      => '$1, $2 ōquitzacuili $3 ($4)',
'infiniteblock'      => 'ahtlamic',
'expiringblock'      => 'motlamia $1',
'anononlyblock'      => 'zan ahtōcā',
'blocklink'          => 'tiquitzacuilīz',
'unblocklink'        => 'ahtiquitzacuilīz',
'contribslink'       => 'tlapatlaliztli',
'blocklogpage'       => 'Tlatequitiltilīlli ōmotzacuili',
'blockme'            => 'Timitzcuilīz',
'proxyblocksuccess'  => 'Ōmochīuh.',

# Move page
'movepagetext'            => "Nicān mohcuiloa quemeh ticzacāz cē zāzanilli auh mochi in ītlahcuillōloh īhuīc occē yancuīc ītōca.
Huēhuehtōcāitl yez tlacuepaliztli yancuīc tōcāhuīc.
Tzonhuiliztli huēhuehzāzanilhuīc ahmo mopatlāz.
Xiquitta ic māca xicchīhua [[Special:DoubleRedirects|ōntlacuepaliztli]] ahnozo [[Special:BrokenRedirects|tzomoc]].
Titzonhuilizpiyāz.

Xicmati in zāzanilli ahmo mozacāz intlā ye ia cē zāzanilli tōcātica, zan cah iztāc zāzanilli ahnozo tlacuepaliztli īca ahmo tlahcuilōlloh.
Quihtōznequi tihuelītīz ticuepāz cē zāzanilli īhuīc ītlācatōca intlā ahcuallōtl ticchīhuāz, tēl ahmo tihuelītīz occeppa tihcuilōz īpan zāzanilli tlein ia.

'''¡XICPŌHUA!'''
Hueliz cah inīn huēyi tlapatlaliztli. Timitztlātlauhtia ticmatīz cuallōtl auh ahcuallōtl achtopa ticzacāz.",
'movearticle'             => 'Ticzacāz tlahcuilōlli',
'movenologin'             => 'Ahmo ōtimocalac',
'movenotallowed'          => 'Ahmo tihuelīti tiquinzaca zāzaniltin.',
'newtitle'                => 'Yancuīc tōcāhuīc',
'move-watch'              => 'Tictlachiyāz inīn zāzanilli',
'movepagebtn'             => 'Ticzacāz zāzanilli',
'pagemovedsub'            => 'Cualli ōmozacac',
'movepage-moved'          => '<big>\'\'\'"$1" ōmotlacuep īhuīc "$2".\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'movedto'                 => 'ōmozacac īhuīc',
'movetalk'                => 'Ticzacāz nō tēixnāmiquiliztli tlahcuilōltechcopa.',
'1movedto2'               => '[[$1]] ōmozacac īhuīc [[$2]]',
'1movedto2_redir'         => '[[$1]] ōmozacac īhuīc [[$2]] tlacuepalpampa',
'movelogpage'             => 'Tlazacaliztli tlahcuilōlloh',
'movereason'              => 'Īxtlamatiliztli:',
'revertmove'              => 'tlacuepāz',
'delete_and_move'         => 'Ticpolōz auh ticzacāz',
'delete_and_move_confirm' => 'Quēmah, ticpolōz in zāzanilli',

# Export
'export'            => 'Tiquinnamacāz zāzaniltin',
'export-submit'     => 'Ticnamacāz',
'export-addcattext' => 'Ticcēntilīz zāzanilli īhuīcpa neneuhcāyōtl:',
'export-addcat'     => 'Ticcētilīz',
'export-download'   => 'Ticpiyāz quemeh tlahcuilōlli',
'export-templates'  => 'Tiquimpiyāz nemachiyōtīlli',

# Namespace 8 related
'allmessages'         => 'Mochīntīn Huiquimedia tlahcuilōltzintli',
'allmessagesname'     => 'Tōcāitl',
'allmessagescurrent'  => 'Āxcān tlahcuilōlli',
'allmessagesmodified' => 'Zan tiquinttāz inōn ōmopatlac',

# Thumbnails
'thumbnail-more'  => 'Tiquihuēyiyāz',
'thumbnail_error' => 'Ahcuallōtl ihcuāc mochīhuaya tepitōntli: $1',

# Special:Import
'import'                  => 'Tiquincōhuāz zāzaniltin',
'import-interwiki-submit' => 'Tiquicōhuāz',
'importstart'             => 'Motlacōhua zāzaniltin...',
'import-revision-count'   => '$1 {{PLURAL:$1|tlachiyaliztli|tlachiyaliztli}}',
'importbadinterwiki'      => 'Ahcualli interhuiqui tzonhuiliztli',
'importnotext'            => 'Ahtlein ahnozo ahtlahtōl',

# Import log
'importlogpage' => 'Tiquincōhuāz tlahcuilōlloh',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Notlatequitiltilīlzāzanil',
'tooltip-pt-mytalk'               => 'Notēixnāmiquiliz',
'tooltip-pt-preferences'          => 'Notlaēlēhuiliz',
'tooltip-pt-watchlist'            => 'Zāzaniltin tiquintlachiya ic tlapatlaliztli',
'tooltip-pt-mycontris'            => 'Notlahcuilōl',
'tooltip-pt-login'                => 'Tihuelīti timocalaqui, tēl ahmo tihuīquilia.',
'tooltip-pt-anonlogin'            => 'Tihuelīti timocalaqui, tēl ahmo tihuīquilia.',
'tooltip-pt-logout'               => 'Tiquīzāz',
'tooltip-ca-talk'                 => 'Inīn tlahcuilōlli ītēixnāmiquiliz',
'tooltip-ca-edit'                 => 'Tihuelīti ticpatla inīn zāzanilli. Timitztlātlauhtiah, tiquiclica achtochīhualizpan achtopa ticpiya.',
'tooltip-ca-addsection'           => 'Tiquihcuilōz itlah inīn tēixnāmiquilizhuīc.',
'tooltip-ca-viewsource'           => 'Inīn zāzanilli ōmoquīxti. Tihuelīti tiquitta ītlahtōlcaquiliztilōni.',
'tooltip-ca-history'              => 'Achtopa āxcān zāzanilli īhuān in tlatequitiltilīlli ōquinchīuhqueh',
'tooltip-ca-protect'              => 'Ticquīxtiāz inīn zāzanilli',
'tooltip-ca-delete'               => 'Ticpolōz inīn zāzanilli',
'tooltip-ca-undelete'             => 'Ahticpolōz inīn zāzanilli',
'tooltip-ca-move'                 => 'Ticzacāz inīn zāzanilli',
'tooltip-ca-watch'                => 'Ticcēntilīz inīn zāzanilli motlachiyalizhuīc',
'tooltip-ca-unwatch'              => 'Ahtictlachiyāz inīn zāzanilli',
'tooltip-search'                  => 'Tlatēmōz īpan {{SITENAME}}',
'tooltip-p-logo'                  => 'Calīxatl',
'tooltip-n-mainpage'              => 'Tictlahpolōz in Calīxatl',
'tooltip-n-portal'                => 'Tlachīhualiztechcopa, inōn tihuelīti titlachīhua, tlatēmoyān',
'tooltip-n-recentchanges'         => 'Yancuīc tlapatlaliztli huiquipan',
'tooltip-n-randompage'            => 'Tiquittāz cē zāzotlein zāzanilli',
'tooltip-n-help'                  => 'Tlamachtiyān.',
'tooltip-n-sitesupport'           => 'Xitēchtēpalēhuia',
'tooltip-t-whatlinkshere'         => 'Mochīntīn zāzaniltin huiquipan quitzonhuiliah nicān',
'tooltip-t-recentchangeslinked'   => 'Yancuīc tlapatlaliztli inīn zāzanilhuīcpa moquintzonhuilia',
'tooltip-feed-rss'                => 'RSS tlachicāhualiztli inīn zāzaniltechcopa',
'tooltip-feed-atom'               => 'Atom tlachicāhualiztli inīn zāzaniltechcopa',
'tooltip-t-contributions'         => 'Xiquitta inīn tlatequitiltilīlli ītlahcuilōl',
'tooltip-t-emailuser'             => 'Tiquihcuilōz inīn tlatequitiltililhuīc',
'tooltip-t-upload'                => 'Tiquinquetzāz tlahcuilōlli',
'tooltip-t-specialpages'          => 'Mochīntīn nōncuahquīzqui zāzaniltin',
'tooltip-ca-nstab-main'           => 'Xiquitta in tlahcuilōlli',
'tooltip-ca-nstab-user'           => 'Xiquitta tlatequitiltilīlli īzāzanil',
'tooltip-ca-nstab-special'        => 'Cah inīn cē nōncuahquīzqui zāzanilli; ahmo tihuelīti ticpatla.',
'tooltip-ca-nstab-project'        => 'Xiquitta tlachīhualiztli īzāzanil',
'tooltip-ca-nstab-image'          => 'Xiquittāz īxipzāzanilli',
'tooltip-ca-nstab-mediawiki'      => 'Xiquitta in tlahcuilōltzin',
'tooltip-ca-nstab-template'       => 'Xiquitta in nemachiyōtīlli',
'tooltip-ca-nstab-help'           => 'Xiquitta in tēpalēhuiliztli zāzanilli',
'tooltip-ca-nstab-category'       => 'Xiquitta in neneuhcāyōtl zāzanilli',
'tooltip-minoredit'               => 'Ticmachiyōz quemeh tlapatlalitzintli',
'tooltip-save'                    => 'Ticpiyāz mopatlaliz',
'tooltip-preview'                 => 'Xiquitta achtopa mopatlaliz, ¡timitztlātlauhtiah quitēquitiltilia achto ticpiya!',
'tooltip-diff'                    => 'Xiquitta in tlein ōticpatlāz tlahcuilōlco.',
'tooltip-compareselectedversions' => 'Tiquinttāz ahneneuhquiliztli ōme zāzanilli tlapatlaliznepantlah.',
'tooltip-watch'                   => 'Ticcēntilīz inīn zāzanilli motlachiyalizhuīc',
'tooltip-upload'                  => 'Ticpēhua quetzaliztli',

# Attribution
'anonymous'        => 'Ahtōcāitl {{PLURAL:$1|tlatequitiltilīlli|tlatequitiltilīlli}} īpan {{SITENAME}}',
'siteuser'         => '$1 tlatequitiltilīlli īpan {{SITENAME}}',
'lastmodifiedatby' => 'Inīn zāzanilli ōtlapatlac catca īpan $2, $1 īpal $3.', # $1 date, $2 time, $3 user
'others'           => 'occequīntīn',
'siteusers'        => '$1 {{PLURAL:$2|tlatequitiltilīlli|tlatequitiltilīlli}} īpan {{SITENAME}}',

# Spam protection
'listingcontinuesabbrev' => 'niman',
'spam_reverting'         => 'Mocuepacah īhuīc xōcoyōc tlapatlaliztli ahmo tzonhuilizca īhuīc $1',
'spam_blanking'          => 'Mochi tlapatlaliztli quimpiyah tzonhuiliztli īhuīc $1, iztāctiliacah',

# Info page
'infosubtitle'   => 'Zāzaniltechcopa',
'numedits'       => 'Tlapatlaliztli tlapōhualli (tlahcuilōlli): $1',
'numtalkedits'   => 'Tlapatlaliztli tlapōhualli (tēixnāmiquiliztli): $1',
'numwatchers'    => 'Tlachiyalōnih tlapōhualli: $1',
'numauthors'     => 'Ahneneuhqui tlapatlalōnih tlapōhualli (tlahcuilōlli): $1',
'numtalkauthors' => 'Ahneneuhqui tlapatlalōnih tlapōhualli (tēixnāmiquiliztli): $1',

# Browsing diffs
'previousdiff' => '← Achtopa',
'nextdiff'     => 'Niman →',

# Media information
'file-nohires'   => '<small>Ahmo ia achi cualli ahmo occē īxiptli.</small>',
'show-big-image' => 'Mochi cuallōtl',

# Special:Newimages
'newimages'    => 'Yancuīc īxipcān',
'showhidebots' => '($1 tepoztlācah)',
'noimages'     => 'Ahtlein ic tlatta.',

# Metadata
'metadata'          => 'Metadata',
'metadata-expand'   => 'Tiquittāz tlanōnōtzaliztli huehca ōmpa',
'metadata-collapse' => 'Tictlātīz tlanōnōtzaliztli huehca ōmpa',

# EXIF tags
'exif-photometricinterpretation' => 'Pixelli chīhualiztli',
'exif-imagedescription'          => 'Īxiptli ītōcā',
'exif-software'                  => 'Software ōmotēquitilti',
'exif-artist'                    => 'Chīhualōni',
'exif-usercomment'               => 'Quihtoa tlatequitiltilīlli',
'exif-exposuretime'              => 'Cāuhcāyōtl',
'exif-fnumber'                   => 'F Tlapōhualli',
'exif-isospeedratings'           => 'ISO iciuhquiliztli tlapōhualcāyōtl',
'exif-flash'                     => 'Flax',
'exif-flashenergy'               => 'Flax chicāhualiztli',
'exif-gpstimestamp'              => 'GPS cāhuitl (atomic tepozcāhuitl)',

'exif-orientation-1' => 'Yēctli', # 0th row: top; 0th column: left

'exif-meteringmode-255' => 'Occē',

'exif-lightsource-1'   => 'Tōnameyyōtl',
'exif-lightsource-2'   => 'Nāltic',
'exif-lightsource-4'   => 'Flax',
'exif-lightsource-10'  => 'Mixxoh',
'exif-lightsource-11'  => 'Ecahuīlli',
'exif-lightsource-12'  => 'Nāltic tōnallāhuīlli (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Nāltic iztāc tōnallāhuīlli (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Nāltic cecec iztāc (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Nāltic iztāc (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Yēctli tlāhuīlli A',
'exif-lightsource-18'  => 'Yēctli tlāhuīlli B',
'exif-lightsource-19'  => 'Yēctli tlāhuīlli C',
'exif-lightsource-255' => 'Occequīntīn tlāhuīlli',

'exif-scenecapturetype-3' => 'Yohualcopa',

'exif-gaincontrol-0' => 'Ahtlein',

'exif-contrast-0' => 'Yēctli',

'exif-saturation-0' => 'Yēctli',

'exif-sharpness-0' => 'Yēctli',

'exif-subjectdistancerange-0' => 'Ahmatic',
'exif-subjectdistancerange-1' => 'Huēyi',
'exif-subjectdistancerange-2' => 'Ahhuehca tlattaliztli',
'exif-subjectdistancerange-3' => 'Huehca tlattaliztli',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Ayamictlān',
'exif-gpslatitude-s' => 'Huiztlān',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'mochīntīn',
'imagelistall'     => 'mochīntīn',
'watchlistall2'    => 'mochīntīn',
'namespacesall'    => 'mochīntīn',
'monthsall'        => '(mochīntīn)',

# E-mail address confirmation
'confirmemail'           => 'Ticchicāhuāz e-mail',
'confirmemail_needlogin' => 'Tihuīquilia $1 ic ticchicāhua mo e-mail.',
'confirmemail_success'   => 'Mo e-mailcān ōmochicāuh. 
Niman tihuelīti timocalaqui auh quiyōlēhua huiqui.',
'confirmemail_loggedin'  => 'Mo e-mailcān ōmochicāuh.',
'confirmemail_error'     => 'Achi ōcatcah ahcualli mochicāhualiztechcopa.',
'confirmemail_subject'   => 'e-mailcān {{SITENAME}} ītlachicāhualiz',

# Scary transclusion
'scarytranscludetoolong' => '[Cah URL achi huēiyac; xitēchpohpolhuia]',

# Trackbacks
'trackbackremove' => ' ([$1 Ticpolōz])',

# Delete conflict
'recreate' => 'Ticchīhuāz occeppa',

# action=purge
'confirm_purge_button' => 'Cualli',

# AJAX search
'searchcontaining' => "Tiquintēmōz zāzaniltin quipiyah ''$1''.",
'searchnamed'      => "Tiquintēmōz zāzaniltin īntōcā ''$1''.",
'articletitles'    => "Tlahcuilōlli mopēhuah īca ''$1''",

# Multipage image navigation
'imgmultipageprev' => '← achto zāzanilli',
'imgmultipagenext' => 'niman zāzanilli →',
'imgmultigo'       => '¡Yāuh!',

# Table pager
'ascending_abbrev'         => 'quetza',
'descending_abbrev'        => 'temoa',
'table_pager_next'         => 'Niman zāzanilli',
'table_pager_prev'         => 'Achto zāzanilli',
'table_pager_first'        => 'Achtopa zāzanilli',
'table_pager_last'         => 'Xōcoyōc zāzanilli',
'table_pager_limit_submit' => 'Yāuh',
'table_pager_empty'        => 'Ahtlein',

# Auto-summaries
'autosumm-blank'   => 'Iztāc zāzanilli',
'autoredircomment' => 'Mocuepahua īhuīc [[$1]]',
'autosumm-new'     => 'Yancuīc zāzanilli: $1',

# Size units
'size-bytes'     => '$1 B',
'size-kilobytes' => '$1 KB',
'size-megabytes' => '$1 MB',
'size-gigabytes' => '$1 GB',

# Live preview
'livepreview-loading' => 'Tēmohua...',
'livepreview-ready'   => 'Motemocah... ¡Ye!',

# Watchlist editor
'watchlistedit-numitems'     => 'Motlachiyaliz {{PLURAL:$1|quipiya cē zāzanilli|quimpiya $1 zāzaniltin}}, ahtle tēixnāmiquiliztli.',
'watchlistedit-normal-title' => 'Ticpatlāz motlachiyaliz',
'watchlistedit-raw-added'    => '{{PLURAL:$1|Ōmocentili cē zāzanilli|Ōmocentilih $1 zāzaniltin}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Tiquinttāz huēyi tlapatlaliztli',
'watchlisttools-edit' => 'Tiquittāz auh ticpatlāz motlachiyaliz',

# Special:Version
'version-specialpages'     => 'Nōncuahquīzqui āmatl',
'version-other'            => 'Occē',
'version-version'          => 'Machiyōtzin',
'version-software-version' => 'Machiyōtzin',

# Special:Filepath
'filepath-page' => 'Tlahcuilōlli:',

);
