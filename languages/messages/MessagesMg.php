<?php
/** Malagasy (Malagasy)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Alno
 * @author Jagwar
 * @author Urhixidur
 * @author לערי ריינהארט
 */

$fallback = 'fr';

$namespaceNames = array(
	NS_SPECIAL          => 'Manokana',
	NS_TALK             => 'Dinika',
	NS_USER             => 'Mpikambana',
	NS_USER_TALK        => 'Dinika_amin\'ny_mpikambana',
	NS_PROJECT_TALK     => 'Dinika_amin\'ny_$1',
	NS_FILE             => 'Sary',
	NS_FILE_TALK        => 'Dinika_amin\'ny_sary',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Dinika_amin\'ny_MediaWiki',
	NS_TEMPLATE         => 'Endrika',
	NS_TEMPLATE_TALK    => 'Dinika_amin\'ny_endrika',
	NS_HELP             => 'Fanampiana',
	NS_HELP_TALK        => 'Dinika_amin\'ny_fanampiana',
	NS_CATEGORY         => 'Sokajy',
	NS_CATEGORY_TALK    => 'Dinika_amin\'ny_sokajy',
);

$namespaceAliases = array(
	'Discuter' => NS_TALK,
	'Utilisateur' => NS_USER,
	'Discussion_Utilisateur' => NS_USER_TALK,
	'Discussion_$1' => NS_PROJECT_TALK,
	'Discussion_Image' => NS_IMAGE_TALK,
	'Discussion_MediaWiki' => NS_MEDIAWIKI_TALK,
	'Modèle' => NS_TEMPLATE,
	'Discussion_Modèle' => NS_TEMPLATE_TALK,
	'Aide' => NS_HELP,
	'Discussion_Aide' => NS_HELP_TALK,
	'Catégorie' => NS_CATEGORY,
	'Discussion_Catégorie' => NS_CATEGORY_TALK,
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Tsipiho ny rohy:',
'tog-highlightbroken'         => 'Aseho <a href="" class="new">toy izao</a> ny rohy tapaka (na koa: toy izao<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Ahitsio ny paragrafy',
'tog-hideminor'               => "Aza aseho amin'ny lisitry ny vao niova ny fanovana madinika",
'tog-hidepatrolled'           => "Aza ampiseo ny fanovana voaara-maso ao anatin'ny fanovana farany",
'tog-newpageshidepatrolled'   => "Asitro amin'ny lisitra ny pejy vaovao ny pejy misy manara-maso",
'tog-extendwatchlist'         => 'Ampiasa ny fanaram-pejy tsaratsara',
'tog-usenewrc'                => 'Lisitry ny vao niova nohatsaraina (mila JavaScript)',
'tog-numberheadings'          => 'Asio laharany ny lohateny',
'tog-showtoolbar'             => 'Asehoy ny edit toolbar (mila JavaScript)',
'tog-editondblclick'          => 'Ovay ny pejy rehefa voatsindry indroa misesy ny totozy (mila JavaScript)',
'tog-editsection'             => "Ataovy mety ny fanovana fizaràna amin'ny alalan'ny rohy [ovao]",
'tog-editsectiononrightclick' => "Ovay ny fizaràna rehefa manindry ny bokotra havanana amin'ny totozy eo amin'ny lohateny hoe fizaràna (mila JavaScript)",
'tog-showtoc'                 => "Asehoy ny fanoroan-takila (ho an'ny pejy misy lohateny mihoatra ny 3)",
'tog-rememberpassword'        => 'Tadidio ny tenimiafiko',
'tog-editwidth'               => "Farany lehibe ny velaran'ny boaty fanovana",
'tog-watchcreations'          => "Ampina ao anarin'ny pejy fanaraha-maso ny pejy amboariko",
'tog-watchdefault'            => "Atsofohy ao amin'ny lisitry ny pejy arahinao maso ny pejy izay ovainao na foroninao",
'tog-watchmoves'              => "Ampina ao anatin'ny pejiko fanaraha-maso ny pejy soloiko anarana",
'tog-watchdeletion'           => "Ampina anatin'ny pejy fanaraha-maso ny pejy nofafako",
'tog-minordefault'            => 'Mariho ho madinika foana aloha ny fanovana rehetra',
'tog-previewontop'            => "Asehoy alohan'ny boaty fanovana ny tsipalotra",
'tog-previewonfirst'          => "Asehoy ny tsipalotra amin'ny fanovana voalohany",
'tog-nocache'                 => 'Tsy ampiasaina ny cache',
'tog-enotifwatchlistpages'    => 'Andefaso imailaka aho rehefa misy miova ny pejy',
'tog-enotifusertalkpages'     => 'Andefaso imailaka aho rehefa miova ny pejin-dresako',
'tog-enotifminoredits'        => 'Andefaso imailaka aho na dia fanovana madinika ihany aza',
'tog-enotifrevealaddr'        => "Asehoy ny adiresy imailako any amin'ny imailaka fampilazana",
'tog-shownumberswatching'     => "Asehoy ny isan'ny mpikambana manara-maso ny pejy",
'tog-fancysig'                => 'Sonia tsotra (tsy misy rohy)',
'tog-externaleditor'          => 'Fitaovana hafa no hanaovana ny fanovana pejy',
'tog-externaldiff'            => 'Fitaovana hafa no hanaovana ny fampitahana',
'tog-showjumplinks'           => 'Ampiasao ny rohy "handeha eto"',
'tog-uselivepreview'          => 'Ampesao ny topi-maso maikamaika (mila Javascript) (mbola am-panandramana)',
'tog-forceeditsummary'        => 'Teneno ahy ra tsy nametraka ny ambangovangony',
'tog-watchlisthideown'        => "Tsy ampiseho anatin'ny pejy fanaraha-maso ny zavatra nosoratako",
'tog-watchlisthidebots'       => "Asitrio amin'ny lisitro ny fanovàna nataon'ny Rindrankajy",
'tog-watchlisthideminor'      => "Tsy aseho ny fisoloina kely anatin'ny pejy fanaraha-maso",
'tog-watchlisthideliu'        => "Asitrio amin'ny lisitro ny fanovàna nataon'ny mpikambana afa",
'tog-watchlisthideanons'      => "Asitrio amin'ny lisitro ny fanovana nataon'ny IP",
'tog-watchlisthidepatrolled'  => "Asitrio amin'ny lisitro ny fanovàna nojerena",
'tog-ccmeonemails'            => "Andefaso tahaka ny imailaka alefako amin'ny mpikambana afa",
'tog-diffonly'                => "Aza ampiseo ny voatonin'ny pejy eo amban'ny diff",
'tog-showhiddencats'          => 'Asehoy ny sokajy misitrika',
'tog-norollbackdiff'          => 'Aza aseho ny diff rehefa avy namafa fanàvana iray',

'underline-always'  => 'Foana foana',
'underline-never'   => 'Tsy tsipihina mihitsy',
'underline-default' => "Izay itiavan'ny navigateur azy",

# Dates
'sunday'        => 'Alahady',
'monday'        => 'Alatsinainy',
'tuesday'       => 'Talata',
'wednesday'     => 'Alarobia',
'thursday'      => 'Alakamisy',
'friday'        => 'Zoma',
'saturday'      => 'Sabotsy',
'sun'           => 'Lah',
'mon'           => 'Lat',
'tue'           => 'Tal',
'wed'           => 'Ala',
'thu'           => 'Lak',
'fri'           => 'Zom',
'sat'           => 'Sab',
'january'       => 'Janoary',
'february'      => 'Febroary',
'march'         => 'Martsa',
'april'         => 'Aprily',
'may_long'      => 'Mey',
'june'          => 'Jiona',
'july'          => 'Jolay',
'august'        => 'Aogositra',
'september'     => 'Septambra',
'october'       => 'Oktobra',
'november'      => 'Novambra',
'december'      => 'Desambra',
'january-gen'   => 'janoary',
'february-gen'  => 'Febroary',
'march-gen'     => 'Martsa',
'april-gen'     => 'Aprily',
'may-gen'       => 'Mey',
'june-gen'      => 'Jiona',
'july-gen'      => 'Jolay',
'august-gen'    => 'Aogositra',
'september-gen' => 'Septambra',
'october-gen'   => 'Oktobra',
'november-gen'  => 'Novambra',
'december-gen'  => 'Desambra',
'jan'           => 'Jan',
'feb'           => 'Feb',
'mar'           => 'Mar',
'apr'           => 'Apr',
'may'           => 'Mey',
'jun'           => 'Jiona',
'jul'           => 'Jol',
'aug'           => 'Aog',
'sep'           => 'Sep',
'oct'           => 'Okt',
'nov'           => 'Nov',
'dec'           => 'Des',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Sokajy|Sokajy}}',
'category_header'                => 'Ireo lahatsoratra ao amin\'ny sokajy "$1"',
'subcategories'                  => 'Zana-tsokajy',
'category-media-header'          => "Fisy multimedia anatin'ny sokajy « $1 »",
'category-empty'                 => "''Tsy misy pejy, sokajy ambany na sary ao anatin'io sokajy io''",
'hidden-categories'              => 'Sokajy misitrika',
'hidden-category-category'       => 'Sokajy misitrika', # Name of the category where hidden categories will be listed
'category-subcat-count'          => 'Ity sokajy manana $1 sokajy ambany. Ny taotaliny dia $2',
'category-subcat-count-limited'  => "Misy an'ireo zana-tsokajy ireo ity sokajy ity.",
'category-article-count'         => "Misy an'ireo pejy ireo pejy ireo ity sokajy ity",
'category-article-count-limited' => "Anatin'ity sokajy ity ireo pejy ireo pejy ireo",
'category-file-count'            => "Misy an'ireo rakitra ireo ity sokajy ity",
'category-file-count-limited'    => "Anatin'ity sokajy ity ireo sokajy ireo",
'listingcontinuesabbrev'         => ' manaraka.',

'mainpagetext'      => "<big>'''Tafajoro soa aman-tsara ny rindrankajy Wiki.'''</big>",
'mainpagedocfooter' => "Vangio ny [http://meta.wikimedia.org/wiki/Aide:Contenu Fanoroana hoan'ny mpampiasa] ra te hitady fanoroana momba ny fampiasan'ity rindrankajy ity.

== Hanomboka amin'ny MediaWiki ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Lisitra ny paramètre de configuration]
* [http://www.mediawiki.org/wiki/Manual:FAQ/fr FAQ momba ny MediaWiki]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Resaka momba ny fizaràn'ny MediaWiki]",

'about'          => 'Mombamomba',
'article'        => "Votoatin'ny pejy",
'newwindow'      => '(sokafy anaty takila hafa)',
'cancel'         => 'Aoka ihany',
'qbfind'         => 'Tadiavo',
'qbbrowse'       => 'Tadiavo',
'qbedit'         => 'Ovay',
'qbpageoptions'  => 'Ity pejy ity',
'qbpageinfo'     => 'Pejy fanoroana',
'qbmyoptions'    => 'Pejiko',
'qbspecialpages' => 'Pejy manokana',
'moredotdotdot'  => 'Tohiny...',
'mypage'         => 'Pejiko',
'mytalk'         => 'Ny diniko',
'anontalk'       => "Dinika amin'io adiresy IP io",
'navigation'     => 'Fikarohana',
'and'            => '&#32;sy',

# Metadata in edit box
'metadata_help' => 'Metadata :',

'errorpagetitle'    => 'Tsy fetezana',
'returnto'          => "Hiverina any amin'ny $1.",
'tagline'           => "Avy amin'i {{SITENAME}}",
'help'              => 'Fanoroana',
'search'            => 'Tadiavo',
'searchbutton'      => 'Tadiavo',
'go'                => 'Ndao',
'searcharticle'     => 'Tsidiho',
'history'           => "Tantaran'ny pejy",
'history_short'     => 'Tantara',
'updatedmarker'     => "niova hatramin'ny nidirako farany",
'info_short'        => 'Fampahalalana',
'printableversion'  => 'Version afaka avoaka taratasy',
'permalink'         => 'Rohy maharitra',
'print'             => 'Avoaka an-taratasy',
'edit'              => 'Ovao',
'create'            => 'Amboary',
'editthispage'      => 'Ovay ity pejy ity',
'create-this-page'  => 'Amboary ity pejy ity',
'delete'            => 'Fafao',
'deletethispage'    => 'Fafao ity pejy ity',
'undelete_short'    => 'Avereno ny fanovana {{PLURAL:$1|$1|$1}}',
'protect'           => 'Arovy',
'protect_change'    => 'ovay',
'protectthispage'   => 'Arovy (hidio) ity pejy ity',
'unprotect'         => 'Esory ny fiarovana',
'unprotectthispage' => "Esory ny fiarovana an'ity pejy ity",
'newpage'           => 'Pejy vaovao',
'talkpage'          => 'Dinidinika momba ity pejy ity',
'talkpagelinktext'  => 'Dinika',
'specialpage'       => 'Pejy manokana',
'personaltools'     => 'Fitaovana manokana',
'postcomment'       => 'Hametraka fanamarihana',
'articlepage'       => "Hijery ny votoatin'ny pejy",
'talk'              => 'dinika',
'views'             => 'Fijerena',
'toolbox'           => 'Fitaovana',
'userpage'          => "Hijery ny pejy manokan'ny mpikambana",
'projectpage'       => 'Pejy meta',
'imagepage'         => "Jereo ny pejin'ny sary",
'mediawikipage'     => "Jereo ny peji n'ilay afatra",
'templatepage'      => "Jereo ny pejin'ny endrika",
'viewhelppage'      => "Jereo ny pejin'ny fanampiana",
'categorypage'      => "Jereo ny pejin'ny sokajy",
'viewtalkpage'      => 'Hijery pejin-dresaka',
'otherlanguages'    => "Amin'ny tenim-pirenena hafa",
'redirectedfrom'    => "(tonga teto avy amin'ny $1)",
'redirectpagesub'   => 'Pejy fihodinana',
'lastmodifiedat'    => "Voaova farany tamin'ny $1 amin'ny $2 ity pejy ity<br />", # $1 date, $2 time
'viewcount'         => 'in-$1 ity pejy ity no nisy nijery.',
'protectedpage'     => 'Pejy voaaro',
'jumpto'            => 'Hanketo:',
'jumptonavigation'  => 'Fikarohana',
'jumptosearch'      => 'karohy',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Mombamomba ny {{SITENAME}}',
'aboutpage'            => 'Project:Mombamomba',
'copyright'            => '$1 no mifehy ny fampiasana ny votoatin-kevitra eto.',
'copyrightpagename'    => 'lisansa {{SITENAME}}',
'copyrightpage'        => '{{ns:project}}:Copyright',
'currentevents'        => 'Ny vaovao',
'currentevents-url'    => 'Project:Vaovao',
'disclaimers'          => 'Fampitandremana',
'disclaimerpage'       => 'Project:General disclaimer',
'edithelp'             => 'Fanoroana',
'edithelppage'         => 'Help:Endritsoratra',
'faq'                  => 'FAQ (fanontaniana)',
'faqpage'              => 'Project:Fanontaniana',
'helppage'             => 'Help:Fanoroana',
'mainpage'             => 'Fandraisana',
'mainpage-description' => 'Fandraisana',
'policy-url'           => 'Project:Fepetra',
'portal'               => 'Toerana iraisana',
'portal-url'           => 'Project:Fikambanana',
'privacy'              => 'Fepetra momba ny zavatra privé',

'badaccess'        => 'Tsy manana alàlana',
'badaccess-group0' => 'Tsy afaka manantontosa ny asa nangatahinao ianao tompoko',
'badaccess-groups' => "Ny mpikambana manana sata « $1 » ihany no afaka manao an'io zavatra tadiavinao atao io.",

'versionrequired'     => "
Mitaky version $1-n'i MediaWiki",
'versionrequiredtext' => "Mitaky version $1-n'i MediaWiki ny fampiasana ity pejy ity. Jereo [[Special:Version]].",

'ok'                      => 'Eka',
'retrievedfrom'           => 'Hita tao amin\'ny "$1"',
'youhavenewmessages'      => 'Manana $1 ($2).',
'newmessageslink'         => 'hafatra vaovao',
'newmessagesdifflink'     => 'fanovana farany',
'youhavenewmessagesmulti' => "Manana hafatra vaovao ianao eo amin'ny $1.",
'editsection'             => 'ovay',
'editold'                 => 'ovay',
'viewsourceold'           => 'hijery fango',
'editlink'                => 'ovay',
'viewsourcelink'          => 'hijery ny fàngony',
'editsectionhint'         => 'Manova ny fizaràna : $1',
'toc'                     => 'Votoatiny',
'showtoc'                 => 'aseho',
'hidetoc'                 => 'afeno',
'thisisdeleted'           => 'Hojerena sa haverina i $1?',
'viewdeleted'             => "Hijery an'i $1?",
'restorelink'             => 'ny fanovàna voafafa',
'feedlinks'               => 'Topaka',
'feed-invalid'            => 'Endri-topaka tsy izy',
'feed-unavailable'        => 'Mbola tsy vonona ny topa ny syndication',
'site-rss-feed'           => 'Topaka RSS ny $1',
'site-atom-feed'          => 'Topa Atom ny $1',
'page-rss-feed'           => 'Topa RSS ny « $1 »',
'page-atom-feed'          => 'Topa Atom ny « $1 »',
'red-link-title'          => '$1 (mbola tsy misy)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Lahatsoratra',
'nstab-user'      => 'Pejy ny mpikambana',
'nstab-media'     => 'Pejy sary sy/na feo',
'nstab-special'   => 'Pejy Manokana',
'nstab-project'   => 'Tetikasa',
'nstab-image'     => 'Rakitra',
'nstab-mediawiki' => 'Hafatra',
'nstab-template'  => 'Endrika',
'nstab-help'      => 'Fanoroana',
'nstab-category'  => 'Sokajy',

# Main script and global functions
'nosuchaction'      => 'Asa tsy fantatra',
'nosuchactiontext'  => "Ny asa voafaritra tao amin'ny URL dia tsy fantatr'ity wiki ity",
'nosuchspecialpage' => 'Tsy misy io pejy manokana io',
'nospecialpagetext' => 'Nangataka pejy manokana tsy misy ianao, azonao jerena eto [[Special:SpecialPages|{{int:specialpages}}]] ny lisitry ny pejy manokana.',

# General errors
'error'                => 'Tsy mety',
'databaseerror'        => "Tsy fetezana eo amin'ny toby",
'dberrortext'          => 'Nisy tsi-fetezana teo amin\'ny requête base de données
Inoana fa ny rindrankajy no misy olana (bug).
Ny requête farany dia:
<blockquote><tt>$1</tt></blockquote>
tao amin\'ny fonction "<tt>$2</tt>".
Toy izao no navalin\'ny MySQL "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Nisy tsi-fetezana teo amin\'ny requête base de données
Ny requête farany dia:
"$1"
tao amin\'ny fonction "$2".
Toy izao no navalin\'ny MySQL "$3: $4"',
'noconnect'            => "Miala tsiny! Misedra olana kely ny wiki ankehitriny, ary tsy afaka mifandray amin'ny serveur banky angona <br />
$1",
'nodb'                 => "Tsy afaka misafidy/mijery ny ''base de données'' $1",
'cachederror'          => "Ity manaraka ity no dika caché an'io pejy ilainao io, nefa mety ho efa mialin'andro.",
'laggedslavemode'      => 'Fampitandremana: Mety ho tsy nisy fanovana vao haingana angamba io pejy io',
'readonly'             => 'Mihidy ny banky angona',
'enterlockreason'      => 'Manomeza antony hanidiana ny pejy, ahitana ny fotoana tokony hamahana izay fihidiana izay',
'readonlytext'         => "
Mihidy vonjimaika aloha ny banky angona ka tsy afaka anaovana fanovana na fanampiana vaovao. Azo inoana fa asa fikolokoloana mahazatra ihany io ka rehefa vita izay asa izay dia hverina amin'ny laoniny izy.

Ny mpitantana nanidy azy dia nametraka ito fanazavana ito: $1",
'missing-article'      => "Tsy hita ny ''database'' ilay lahatsoratra nà pejy iray tokony hitany, manana lohateny  « $1 » $2.

Misy io tsy feterana io rehefa manaraka rohy mitondra any amin'ny diff efa lany daty na any amin'ny tantaran'asa nà pejy voafafa.

Ra tsy izany, mety misy ''bug'' anatin'ny rindrankajy MediaWiki.
Andana teneno ny [[Special:ListUsers/sysop|mpandrindra]]n-ity wiki ity. Aza adino no manome ny URL ny rohy.",
'missingarticle-rev'   => '(famerenana faha : $1)',
'missingarticle-diff'  => '(diff : $1 ; $2)',
'readonly_lag'         => "
Mihidy ho azy aloha ny banky angona mandra-pahatratran'ny serveur andevo ny tompony",
'internalerror'        => "Tsy fetezana anatin'ny rindrankajy",
'internalerror_info'   => 'Tsy fetezana ety anatiny : $1',
'filecopyerror'        => 'Tsy voadika ho "$2" ilay rakitra"$1".',
'filerenameerror'      => 'Tsy voaova ho "$2" ny anaran\'ilay rakitra "$1".',
'filedeleteerror'      => 'Tsy voafafa ilay rakitra "$1".',
'directorycreateerror' => "Tsy afaka amboarina ny petra-drakitra (''dossier, directory'') « $1 ».",
'filenotfound'         => 'Tsy hita ilay rakitra "$1".',
'fileexistserror'      => "Tsy afaka manoratra ao anatin'ilay dossier « $1 » : efa misy ilay fisy",
'unexpected'           => 'Tsy nandrasana: "$1"="$2".',
'formerror'            => 'Tsy mety: tsy lasa ny fisy',
'badarticleerror'      => "Tsy azo atao eto amin'ity pejy ity io asa io.",
'cannotdelete'         => 'Tsy voafafa ny pejy na rakitra nomenao. (Angamba efa nisy olon-kafa namafa ilay izy.)',
'badtitle'             => 'Tsy mety ny lohateny',
'badtitletext'         => "Tsy mety io anaram-pejy nangatahinao io na tsy misy n'inon'inona na rohy dikan-teny vahiny misy diso tsipelina.",
'perfcached'           => 'Ireto angona ireto dia nalaina tao anaty cache koa mety ho efa lany daty.:',
'perfcachedts'         => "Aminy ''cache'' daholo ny ''data'' misy ato, tamin'ny $1 ny data tato no natao ''mise à jour''.",
'querypage-no-updates' => "Tsy nalefa ny ''mise à jour'' (update) hoan'ity pejy ity.
Mety tsy misy fifandraisana amin'ny zavamisy ankehitriny ny zavamisy ao anatin'ity pejy ity..",
'wrong_wfQuery_params' => "Misy tsy fetezana amin'ny wfQuery()<br />
Asa : $1<br />
fangatahana : $2",
'viewsource'           => 'Hijery fango',
'viewsourcefor'        => "ho an'ny $1",
'actionthrottled'      => 'Tao voafetra',
'viewsourcetext'       => "Azonao atao no mijery sy mandrika ny votoatin'ity pejy ity :",
'editinginterface'     => "'''Tandremo :''' manova pejy ampiasan'ny lôjisialy wiki ianao. Mety hita ny mpikambana sàsany izy io. Rehefa tia mandika teny ianao, jereo ny volavola MediaWiki ho an'ny internationalisation ny hafatra [http://translatewiki.net/wiki/Main_Page?setlang=fr translatewiki.net].",
'sqlhidden'            => '(nafenina ny requête SQL)',
'namespaceprotected'   => "Tsy manana alalàna manova ny toeran'anarana « '''$1''' » ianao.",
'customcssjsprotected' => "Tsy azonao atao no manova n'ity pejy ity, satria manana ny safidy nà mpikambana ny votoatiny.",
'ns-specialprotected'  => "Tsy afaka ovaina ny pejy anatin'ny toeran'anarana « {{ns:special}} » .",
'titleprotected'       => "Voaaro mba tsy ho amboarina ilay lohateny, i [[User:$1|$1]] no nanao an'io.
Io ny antony : « ''$2'' ».",

# Virus scanner
'virus-badscanner'     => "Diso : Tsy fantatray ny mpitady virus ''$1''",
'virus-scanfailed'     => 'Tsy mety alefa ny fitadiavana (kaody $1)',
'virus-unknownscanner' => 'Tsy fantatra io Antivirus io :',

# Login and logout pages
'logouttitle'                => 'Fivoahana',
'logouttext'                 => "
'''Tafavoaka ianao ankehitriny.'''<br />
Afaka manohy ny fampiasana ny {{SITENAME}} ianao ka tsy mitonona anarana, ary afaka
miditra amin'ny alalan'ilay solonanarana teo na solonanarana hafa koa. 
Mariho fa misy pejy sasantsasany mety mbola hiseho foana mandra-pamafanao ny 
cache.",
'welcomecreation'            => "== Tongasoa, $1! ==

Voaforona ny kaontinao. Aza adinoina ny manova ny mombamomba anao ao amin'ny {{SITENAME}}.",
'loginpagetitle'             => 'Fidirana',
'yourname'                   => 'Solonanarana',
'yourpassword'               => 'Tenimiafina',
'yourpasswordagain'          => 'Avereno ampidirina eto ny tenimiafina',
'remembermypassword'         => 'Tadidio ny tenimiafiko',
'yourdomainname'             => 'faritra (domaine) misy anao',
'externaldberror'            => "Nisy tsy fetezana angamba teo amin'ny fanamarinana anao tamin'ny sehatra ivelan'ity wiki ity, na tsy manana alalana hanova ny kaontinao ivelany ianao.",
'login'                      => 'Midira',
'nav-login-createaccount'    => 'Ampidiro ny solonanarana',
'loginprompt'                => "
Mila manaiky cookies ianao raha te hiditra amin'ny {{SITENAME}}.",
'userlogin'                  => 'Ampidiro ny solonanarana',
'logout'                     => 'Hiala',
'userlogout'                 => 'Hiala',
'notloggedin'                => 'Tsy tafiditra',
'nologin'                    => 'Tsy manana solonanarana? $1.',
'nologinlink'                => 'Manokafa kaonty',
'createaccount'              => 'Hamorona kaonty',
'gotaccount'                 => 'Efa manana kaonty? $1.',
'gotaccountlink'             => 'Midira',
'createaccountmail'          => "amin'ny imailaka",
'badretype'                  => 'Tsy mitovy ny tenimiafina nampidirinao.',
'userexists'                 => 'Efa misy mampiasa io solonanarana io. Mifidiana solonanarana hafa azafady.',
'youremail'                  => 'Imailaka:',
'username'                   => 'Solonanarana:',
'uid'                        => "Laharan'ny mpikambana:",
'prefs-memberingroups'       => "mpikamban'ny gropy :",
'yourrealname'               => 'Tena anarana marina:',
'yourlanguage'               => 'Tenim-pirenena:',
'yournick'                   => 'Anaram-bositra:',
'badsig'                     => 'Tsy mety io sonia io; hamarino ny kialo HTML.',
'badsiglength'               => "Lava laotra ny sonianao.
Tokony mba manana lohavy ambanimbany kokoa non'ny $1",
'yourgender'                 => 'lahy/vavy :',
'gender-unknown'             => 'Tsy voalaza',
'gender-male'                => 'Lehilahy',
'gender-female'              => 'Vehivavy',
'email'                      => 'Imailaka',
'prefs-help-realname'        => "Anarana marina (afaka tsy fenoina): raha fenoinao ity dia hampiasaina hanomezana anao tambin'ny asa izay efainao eto.",
'loginerror'                 => "Tsy fetezana teo amin'ny fidirana",
'prefs-help-email'           => "Imailaka (afaka tsy asiana): Hahafahan'ny olona mifandray aminao amin'ny alalan'ny pejinao manokana na ny pejin-dresakao, nefa tsy aseho azy ny anaranao.",
'prefs-help-email-required'  => 'Ilaina ny adiresy imailaka',
'nocookieslogin'             => 'Mampiasa cookies i {{SITENAME}} nefa ny navigateur-nao tsy manaiky cookies. Ovay hanaiky cookies aloha izy vao afaka manandrana miditra indray ianao.',
'noname'                     => 'Tsy nanome solonanarana mety ianao.',
'loginsuccesstitle'          => 'Tafiditra soa aman-tsara',
'loginsuccess'               => "'''Tafiditra amin'ny {{SITENAME}} ianao ry \"\$1\".'''",
'nosuchuser'                 => 'Tsy misy mpikambana manana izany solonanarana "$1" izany. Hamarino ny tsipelina na manokafa kaonty vaovao.',
'nosuchusershort'            => 'Tsy misy mpikambana hoe "<nowiki>$1</nowiki>". Hamarino ny tsipelina.',
'nouserspecified'            => 'Tsy maintsy mampiditra solonanarana ianao.',
'wrongpassword'              => 'Diso ny tenimiafina. Manandrama tenimiafina hafa azafady.',
'wrongpasswordempty'         => 'Tsy nampiditra tenimiafina ianao, azafady mba avereno indray.',
'passwordtooshort'           => 'Fohy loatra io tenimiafina io.
Farafahakeliny tokony hisy litera $1 ny tenimiafina.',
'mailmypassword'             => 'Alefaso imailaka ny tenimiafiko',
'passwordremindertitle'      => "Fampatsiahivana tenimiafina avy amin'i {{SITENAME}}",
'passwordremindertext'       => 'Nisy olona, izay ianao ihany angamba, avy tamin\'ny adiresy IP $1, nangataka
ny handefasanay tenimiafina vaovao ho an\'ny sehatra {{SITENAME}} ao amin\'ny
$4.
Lasa "$3" ankehitriny ny tenimiafin\'i "$2"
Afaka miditra ary ianao ankehitriny ary manova ny tenimiafinao.

Raha olon-kafa io nangataka io, na tadidinao ihany ny tenimiafinao taloha ka
tsy irinao hovana intsony, dia fafao fotsiny ity hafatra ity dia ilay 
tenimiafina taloha ihany no ampiasao.',
'noemail'                    => 'Tsy nanome adiresy imailaka i "$1".',
'passwordsent'               => 'Nandefasana tenimiafina vaovao any amin\'ny adiresy imailak\'i "$1".
Azafady midira rehefa voarainao io imailaka io.',
'eauthentsent'               => "
Efa nandefasana imailaka fanamarinana ilay adiresy nomenao.
Alohan'ny handraisanao imailaka hafa, dia araho ny torolalana ao anatin'io imailaka io,
mba hanaporofoana fa anao io kaonty io.",
'mailerror'                  => "Nisy olana tamin'ny fandefasana imailaka: $1",
'acct_creation_throttle_hit' => 'Miala tsiny, efa nanokatra kaonty miisa $1 ianao, ka tsy afaka mamorona hafa intsony.',
'emailauthenticated'         => "Voamarina tamin'ny $2 $3 ny adiresy imailakao.",
'emailnotauthenticated'      => "Tsy mbola voamarina ny adiresinao. Tsy mbola afaka mandefa hafatra ianao amin'ireto zavatra azo atao manaraka ireto.",
'noemailprefs'               => 'Manomeza adiresy imailaka raha hampiasa ireo fitaovana ireo ianao.',
'emailconfirmlink'           => 'Hamarino ny adiresy imailakao',
'invalidemailaddress'        => 'Tsy mety io imailaka nalefanao io satria tsy manaraka ny firafitra tokony ho izy.
Azafady manomeza adiresy voasoratra tsara na avelao ho banga io toerana io.',
'accountcreated'             => 'Kaonty voaforona',
'accountcreatedtext'         => "Voasokatra ilay kaonty hoan'i $1.",
'createaccount-title'        => "Fanokafana kaonty hoan'ny/i {{SITENAME}}",
'createaccount-text'         => "Nisy olona nanokatra kaonty hoan'ny adiresy imailakao eo amin'ny {{SITENAME}} ($4) mitondra anarana « $2 » miarak'amin'ny tenimiafina « $3 ».<br />
Tokony miditra na manokatra kaonty ianao ary manova ny tenimiafinao dian-izao.

Aza mijery an'ity afatra ity ianao ra voaforona an-tsipetezana ilay kaonty io.",
'loginlanguagelabel'         => 'fiteny : $1',

# Password reset dialog
'resetpass'                 => 'Hanova ny tenimiafina',
'resetpass_header'          => "Hanova ny tenimiafin'ny kaonty",
'oldpassword'               => 'Tenimiafina taloha:',
'newpassword'               => 'Tenimiafina vaovao:',
'retypenew'                 => 'Avereno ampidirina ny tenimiafina vaovao:',
'resetpass_submit'          => 'Ovay ny tenimiafina ary midira',
'resetpass_success'         => 'Voasolo soa aman-tsara ny tenimiafinao ! Mampiditranao izao ankehitriny...',
'resetpass_forbidden'       => 'Tsy afaka ovaina ny tenimiafina',
'resetpass-no-info'         => "Tsy maintsy miditra/nanokatra kaonty ato ianao mba hafaka hitsidika n'ity pejy ity.",
'resetpass-submit-loggedin' => 'Ovay ny tenimiafina',
'resetpass-wrong-oldpass'   => 'Tsy izy ny tenimiafinao (tsotra na miserana)
Mety efa nanova tenimiafina na nanontany tenimiafina miserana angamba ianao.',
'resetpass-temp-password'   => 'Tenimiafina miserana :',
'resetpass-log'             => "Tantaran'asa ny famerenana tenimiafina",
'resetpass-logtext'         => "Ity ny lisitra ny mpikambana nosoloin'ny mpandrindra tenimiafina.",
'resetpass-logentry'        => "nanova ny tenimiafin'i $1",
'resetpass-comment'         => 'Antony momba ny famerenana tenimiafina :',

# Edit page toolbar
'bold_sample'     => 'Soratra matavy',
'bold_tip'        => 'Soratra matavy',
'italic_sample'   => 'Sora-mandry',
'italic_tip'      => 'Sora-mandry',
'link_sample'     => "Soratra eo amin'ny rohy",
'link_tip'        => 'Rohy anatiny',
'extlink_sample'  => 'http://www.example.com rohy lohateny',
'extlink_tip'     => 'Rohy ivelany (tadidio ny tovana http://)',
'headline_sample' => 'Lohateny anankiray',
'headline_tip'    => 'Lohatena ambaratonga faha 2',
'math_sample'     => 'Atsofohy eto ny raikipohy',
'math_tip'        => 'Raikipohy matematika (LaTeX)',
'nowiki_sample'   => 'Apetraho eto ny lahatsoratra tsy manaraka format',
'nowiki_tip'      => 'Aza ampiasaina ny formatage wiki',
'image_sample'    => 'ohatra.jpg',
'image_tip'       => 'sary',
'media_sample'    => 'Ohatra.ogg',
'media_tip'       => 'Rohy rakitra sary sy/na feo',
'sig_tip'         => "Ny sonianao miaraka amin'ny daty",
'hr_tip'          => 'Tsipika mitsivalana (aza anaranam-po loatra)',

# Edit pages
'summary'                          => 'Ambangovangony:',
'subject'                          => 'Lohateny:',
'minoredit'                        => 'Fanovàna kely',
'watchthis'                        => 'Araho maso ity pejy ity',
'savearticle'                      => 'Tehirizo',
'preview'                          => 'Topi-maso',
'showpreview'                      => 'Asehoy aloha',
'showlivepreview'                  => 'Topi-maso maikamaika',
'showdiff'                         => 'Asehoy ny fiovana',
'anoneditwarning'                  => "'''Tandremo :''' tsy niditra/nanokatra kaonty tato ianao. Voasoratra anatin'ny tantaran'asa n'ity pejy ity ny adiresy IP-nao",
'missingcommenttext'               => 'Ampidiro ny ambangovangony azafady.',
'summary-preview'                  => "Topi-maso n'ilay ambangovangony :",
'subject-preview'                  => 'Topi maso ny lazaina :',
'blockedtitle'                     => 'Mpikambana voasakana',
'blockedtext'                      => "<big>'''Voasakana ny solonanaranao na ny adiresy IP anao.'''</big>

Nataon'i $1 ny fisakanana.
Ny antony : ''$2''.

* Fanombohan'ilay fisakanana : $8
* Farany : $6
* Kaonty voasakana : $7.

Afaka antsoinao i $1 na [[{{MediaWiki:Grouppage-sysop}}|ny mpandrindra]] mba hiresaka mombamomba n'izany.
Afaka andefasanao imailaka ra nampiditra ny adiresy imailakanao ianao ao anatin'ny [[Special:Preferences|mombamombanao]].
'''$3''' ny adiresy IP-nao ary ny ''identifiant de blocage''-nao dia #$5.
Asio ao anaty ny fangatahanao io adiresy io.",
'autoblockedtext'                  => "Voasakana ny adiresy IP anareo satria nampiasain'ny olon-kafa io adiresy ampiasainao io. Ary voasakan'i $1 ilay olona nampiasa ny adiresinao.<br />
Ity ny antony navoakany

:''$2''

* nanomboka tamin'ny $8 ilay fisakanana
* Amin'ny $6 ilay fisakanana no mijanona
* $7 no anaran'ilay kaonty voasakana

Afaka antsoinao i $1 na miantso ny [[{{MediaWiki:Grouppage-sysop}}|mpandrindra]] mba hiresaka momba ny fanakananao.

Jereo koa fa tsy afaka mampiasa ny asa ''emailuser'' ianao ra tsy nanometraka ny adiresy imailakao anatin'ny [[special:Preferences|zavatra tianao]]. Jereo koa ra tsy nesorinao ny asa ''emailuser''.

$3 izao ny adiresinao, ary ny isa ny fisakananai dia $5.
Soraty ireo fanoroana ireo anatin'ny fangatahana ataonao.",
'blockednoreason'                  => 'tsy nisy antony nomeny',
'blockedoriginalsource'            => "Eto amban'ny kaody fango ny '''$1''' :",
'blockededitsource'                => "Eo amban'ny votoatin'ny '''nosoratanao''' nataonao tamin'ny '''$1'''",
'whitelistedittitle'               => 'Midira/Misorata anarana',
'whitelistedittext'                => "Mila $1 aloha ianao vao afaka manova/mamorona pejy eto amin'ity wiki ity.",
'confirmedittitle'                 => 'Ilaina no mi-valide ny adiresy imailaka ra te hanova pejy',
'nosuchsectiontitle'               => 'Ts ampy fitapahana',
'loginreqtitle'                    => 'Mila fidirana',
'loginreqlink'                     => 'hiditra',
'loginreqpagetext'                 => 'Tokony $1 ianao raha te hijery pejy hafa.',
'accmailtitle'                     => 'Lasa ny tenimiafina.',
'accmailtext'                      => 'Lasa any amin\'i $2 ny tenimiafin\'i "$1".',
'newarticle'                       => '(Vaovao)',
'newarticletext'                   => "Mbola tsy misy ity takelaka ity koa azonao atao ny mamorona azy eto ambany. Jereo ny [[{{MediaWiki:Helppage}}|Fanoroana]] raha misy fanazavana ilainao.

Raha toa moa ka tsy nieritreritra ny hamorona ity takelaka ity ianao dia miverena etsy amin'ny fandraisana.",
'anontalkpagetext'                 => "----''Eto no sehatra iresahana ho an'ny mpikambana tsy mitonona anarana, izany hoe tsy nisoratra anarana na tsy mampiasa ny solonanarany. Voatery noho izany isika mampiasa ny adiresy IP ho marika hanondroana azy. Io adiresy IP io dia mety hikambanana amin'ny olona maro hafa. Raha tsy mitonona anarana ianao ary mahatsapa fa misy fanamarihana tsy miantefa aminao loatra voarainao, dia iangaviana ianao mba [[Special:UserLogin|hisoratra anarana ho mpikambana na hiditra]] mba tsy hifangaroanao amin'ny mpikambana tsy mitonona anarana hafa intsony.''",
'noarticletext'                    => "'''Tsy mbola nisy namorona io lahatsoratra io... azonao atao ny mamorona azy.'''
* '''[{{SERVER}}{{localurl:{{NAMESPACE}}:{{PAGENAME}}|action=edit}} Forony eto ny lahatsoratra momba ny {{PAGENAME}}]'''.
* [[{{ns:special}}:Search/{{PAGENAME}}|Tadiavo ny momba ny {{PAGENAME}}]] ato.",
'clearyourcache'                   => "'''Fanamarihana:''' Aorian'ny fanovana, dia mila mamafa ny cache ianao vao mahita ny fiovana.
'''Mozilla / Firefox / Safari:''' Tsindrio ny ''Shift'' rehefa manindry ''Reload'', na tsindrio ''Ctrl-Shift-R'' (''Cmd-Shift-R'' ho an'ny Apple Mac); '''IE:''' tsindrio ''Ctrl'' rehefa manindry ''Refresh'', na tsindrio ''Ctrl-F5''; '''Konqueror:''': tsindrio fotsiny ny bokotra ''Reload'' na ''F5''; ny mpampiasa '''Opera''' angamba dia tokony hamafa ny cache-ny ao amin'ny ''Tools&rarr;Preferences''.",
'usercssjsyoucanpreview'           => "'''Fika:''' Ampiasao ny bokotra 'Tsipalotra' mialoha ny hitehirizanao ny CSS/JS-nao vaovao.",
'usercsspreview'                   => "'''Tadidio fa mijery tsipalotra ny fivoakan'ny takilan'angalinao (CSS) vaovao fotsiny ihany ianao fa tsy mbola voatahiry akory izy io!'''",
'userjspreview'                    => "
'''Tadidio fa manandrana/mijery tsipalotra ny fivoakan'ny JavaScript-nao fotsiny ihany ianao fa tsy mbola voatahiry akory izy io!'''",
'updated'                          => '(Voaova)',
'note'                             => "'''Fanamarihana:'''",
'previewnote'                      => "'''Topi-maso ihany ity hitanao ity, tsy mbola voatahiry ny fanovana nataonao!'''",
'previewconflict'                  => "
Ity topi-maso ity no mifanaraka amin'ny lahatsoratra ao amin'ny faritra eo ambony,
ary toy izao no ho fisehon'ny pejy raha misafidy ny hitahiry azy ianao.",
'session_fail_preview'             => "'''Miala tsiny. Tsy afaka atao ny asa ilainao satria misy very ny fampahalalana ilaina momba ny session. Azafady mba andramo fanindroany. Raha tena mbola tsy mety dia manandrama mivoaka mihitsy aloha dia miditra indray avy eo!'''",
'editing'                          => 'Manova ny $1',
'editingsection'                   => 'Fanovana $1 (fizaràna)',
'editingcomment'                   => 'Fanovana $1 (fanamarihana)',
'editconflict'                     => 'Fanovana mifandona: $1',
'explainconflict'                  => "
Nisy olon-kafa koa nanova ity pejy ity taorian'ny nanombohanao nanova azy.
Ireto ny votoatin'ny pejy, ilay eo ambony ny votoatiny araka izay endriny ankehitriny,
ilay eo ambany no misy ny fanovana saika hataonao.
Mila mampiditra ny fanovana nataonao ao anatin'ny votoatiny ankehitriny ianao.
Ny lahatsoratra ao amin'ilay faritra ambony <b>ihany</b> no ho voatahiry rehefa manidry
ilay bokotra \"Tehirizo\" ianao. <br />",
'yourtext'                         => 'Lahatsoratrao',
'storedversion'                    => 'Votoatiny voatahiry',
'nonunicodebrowser'                => "'''FAMPITANDREMANA: Tsy mifanaraka tanteraka amin'ny unicode ny navigateur-nao. Misy ihany anefa fika napetraka hahafahanao manova ny lahatsoratra: Ny litera tsy ASCII dia hiseho amin'ny fango isa ta-enina ambin'ny folo.'''",
'editingold'                       => "'''FAMPITANDREMANA: Ity pejy ity dia efa lany daty io votoatiny ovainao io.
Raha io no tahirizinao, dia ho very ny fanovana ity pejy ity rehetra taorian'io fanovana io.'''",
'yourdiff'                         => 'Fampitahana',
'copyrightwarning'                 => "Ny zavatra rehetra apetraka amin'ny {{SITENAME}} dia raisina ho azo adika malalaka araka ny fahazoan-dalana $2 (Jereo $1 ny fanazavana fanampiny). Raha toa ka tianao ho anao manokana ny tahirin-kevitra dia aleo tsy apetraka ato.

<b>AZA MAMPIASA TAHIRINKEVITRA TSY NAHAZOAN-DALANA</b>",
'longpagewarning'                  => "'''FAMPITANDREMANA: Mahatratra $1 kilooktety ny hangezan'ity pejy ity; 
Ny navigateur sasantsasany dia mety hanana olana
amin'ny fanovana ny pejy manakaiky na mihoatra ny 32 ko.
Tsara raha saratsarahinao ho fizarana maromaro ity pejy ity.'''",
'readonlywarning'                  => "'''FAMPITANDREMANA: Nohidiana noho ny antony fikolokoloana aloha ny banky angona,
koa tsy afaka mitahiry ny fanovana nataonao aloha ianao izao. Angamba tokony hanao Couper coller aloha 
ianao dia tehirizo anaty rakitra ny fanovanao mandra-paha.'''",
'protectedpagewarning'             => "'''FAMPITANDREMANA:  Voaaro ity pejy ity ka ny mpikambana manana ny fahazoan-dàlana sysop ihany no afaka manova azy.'''",
'templatesused'                    => "endrika miasa eto amin'ity pejy ity:",
'templatesusedpreview'             => "endrika miasa anatin'ity topi-maso ity :",
'templatesusedsection'             => "Endrika miasa anatin'ity fizaràna ity :",
'template-protected'               => '(voaaro)',
'template-semiprotected'           => '(voaaro an-tàpany)',
'hiddencategories'                 => 'anaty sokajy nasitrika $1 ity pejy ity',
'nocreatetitle'                    => 'Voafetra ny famoronana pejy',
'nocreatetext'                     => " Voafetra ihany ny fahafahana mamorona pejy eto amin'ity sehatra ity.  Ny pejy efa misy no azonao ovaina, na [[Special:UserLogin|midira na mamoròna kaonty]].",
'nocreate-loggedin'                => 'Tsy mahazo ataonao no manamboatra pejy vao.',
'permissionserrors'                => 'Tsy azonao atao',
'permissionserrorstext-withaction' => 'Tsy manana alalàna ianao $2. Io ny antony :',
'edit-conflict'                    => 'Adi-panovàna.',

# Parser/template warnings
'post-expand-template-inclusion-category' => 'Pejy be be endrika',
'parser-template-loop-warning'            => 'endrika vono hita tao : [[$1]]',

# History pages
'viewpagelogs'           => "Hijery ny fanovan'ity pejy ity",
'nohistory'              => 'Tsy manana tantaram-panovana io pejy io.',
'currentrev-asof'        => "Endrika tamin'ity $1 ity",
'revisionasof'           => "Èndriny tamin'ny $1",
'previousrevision'       => '←Votoatiny antitra kokoa',
'nextrevision'           => 'Fanovana vao haingana→',
'currentrevisionlink'    => 'Endrika-ny ankehitriny',
'cur'                    => 'ank',
'next'                   => 'manaraka',
'last'                   => 'farany',
'page_first'             => 'voalohany',
'page_last'              => 'farany',
'histlegend'             => "
Fifidianana ny votoatiny hampitahaina: mariho eo anilan'ny versions hampitahaina dia tsindrio ny bokotra Entrée na ny bokotra etsy ambany.<br />
Tadidio: (ank) = fampitahana amin'ny votoatin'ny pejy ankehitriny,
(farany) = fampitahana amin'ny version talohan'ity, M = fanovana madinika",
'history-fieldset-title' => 'Karohy ny tantara',
'deletedrev'             => '[voafafa]',
'histfirst'              => 'Ny vao indrindra',
'histlast'               => 'Antintra indrindra',
'historysize'            => '$1 oktety',
'historyempty'           => '(tsy misy)',

# Revision feed
'history-feed-title' => 'Tantara ny fanovàna',

# Revision deletion
'rev-delundel'           => 'jereo/asitrio',
'revdelete-legend'       => 'Ampetraho ny restriction nà fijerena :',
'revdelete-hide-text'    => "Asitrio ny lahatsoratr'ity version ity",
'revdelete-hide-name'    => 'Asitrio ny asa sy ny tarigehitra',
'revdelete-hide-comment' => 'asitrio ny ambangovangony',
'revdelete-hide-user'    => "Asitrio ny solonanaran'ny mpikambana/adiresy IP",
'revdelete-hide-image'   => "asitrio ny votoatin'ilay rakitra",
'revdelete-log'          => "Anbangovangon'ny tantaran'asa :",
'revdelete-submit'       => "Ataovy amin'ny version nosafidiako",
'revdelete-logentry'     => "nanova ny fahitan'ny/i [[$1]]",
'logdelete-logentry'     => "nanova ny fahitan'ny zava-mitranga ny/n'i [[$1]]",
'revdelete-success'      => "'''Voaova soa aman-tsara ny fahitàn'ny version.'''",
'revdel-restore'         => 'Ovay ny fahitàna',

# Merge log
'revertmerge' => 'Sarao',

# Diffs
'history-title'           => "Tantara ny endrik'i « $1 »",
'difference'              => "(Fahasamihafan'ny pejy)",
'lineno'                  => 'Andalana $1:',
'compareselectedversions' => 'Ampitahao ireo version voafidy',
'editundo'                => 'esory',

# Search results
'searchresults'             => 'Valim-pikarohana',
'searchresults-title'       => "Valim-pikarohana hoan'ny « $1 »",
'searchresulttext'          => "Jereo ny [[{{MediaWiki:Helppage}}|fanazavana fanampiny momba ny fikarohana eto amin'ny {{SITENAME}}]].",
'searchsubtitle'            => "nitady lohatsoratra « '''[[:$1]]''' » ianao ([[Special:Prefixindex/$1|ny pejy rehetra manomboka amin'ny « $1 »]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|ny pejy rehetra manana rohy amin'ny « $1 »]])",
'searchsubtitleinvalid'     => "Nitady « '''$1''' » ianao",
'noexactmatch'              => "'''Tsy misy pejy manana lohateny « $1 » ato.''' Afaka [[:$1|Amboarinao io pejy io]].",
'noexactmatch-nocreate'     => "''Tsy misy pejy milohateny « $1 ».'''",
'titlematches'              => "Mifanitsy amin'ny lohatenin'ny lahatsoratra",
'notitlematches'            => 'Tsy nahitana lohateny mifanaraka',
'textmatches'               => "Mifanitsy amin'ny votoatin'ny pejy",
'notextmatches'             => 'Tsy nahitana votoatim-pejy mifanaraka',
'prevn'                     => '$1 taloha',
'nextn'                     => '$1 manaraka',
'viewprevnext'              => 'Hijery ($1) ($2) ($3).',
'searchhelp-url'            => 'Help:Fanoroana',
'search-result-size'        => '$1 (teny $2)',
'search-redirect'           => "(redirect avy amin'ny/amin'i $1)",
'search-section'            => '(fizaràna $1)',
'search-suggest'            => 'Andramo : $1',
'search-interwiki-caption'  => 'zandri-tetikasa',
'search-interwiki-default'  => "Valiny amin'ny $1 :",
'search-interwiki-more'     => '(be kokoa)',
'search-mwsuggest-enabled'  => 'misy hevitra',
'search-mwsuggest-disabled' => 'tsy misy hevitra',
'showingresults'            => "Omeo ny valiny miisa hatramin'ny <b>$1</b> manomboka ny #<b>$2</b>.",
'showingresultsnum'         => 'Omeo ny valiny miisa <b>$3</b> manomboka ny #<b>$2</b>.',
'showingresultstotal'       => "Fampisehon'ny vokany '''$1''' – $2''' amin'ny '''$3'''",
'nonefound'                 => "'''Fanamarihana''': ny mahatonga ny fikarohana tsy hahita vokany matetika dia ny 
fampiasanao teny miasa matetika toy ny \"izay\" sy ny \"tsy\",
na ny fanomezanao teny mihoatra ny iray (ny pejy ahitana ny teny rehetra hokarohina
ihany no miseho amin'ny vokatry ny karoka).",
'powersearch'               => 'Fitadiavana',
'powersearch-legend'        => 'Fikarohana havanana',
'powersearch-ns'            => "Hitady anatin'ny toeran-anarana :",
'powersearch-redir'         => 'Ampiseho ny redirect',
'powersearch-field'         => 'Hitady',
'searchdisabled'            => "Tsy nalefa ny karoka eto amin'i {{SITENAME}}. Afaka mampiasa an'i Google aloha ianao mandra-paha. Nefa fantaro fa mety ho efa lany daty ny valiny omeny.",

# Preferences page
'preferences'           => 'Ny momba anao',
'mypreferences'         => 'Ny safidiko',
'prefs-edits'           => 'isa ny fanovàna :',
'prefsnologin'          => 'Tsy tafiditra',
'prefsnologintext'      => '[[Special:UserLogin|Midira]] aloha izay vao manova ny mombamomba anao.',
'changepassword'        => 'Hanova tenimiafina',
'skin'                  => 'Endrika',
'skin-preview'          => 'Tsipalotra',
'dateformat'            => 'endriky ny daty',
'datetime'              => 'Daty sy ora',
'math_failure'          => 'Tsy nety ny fanodinana ny raikipohy',
'math_unknown_error'    => 'tsy fahatomombanana tsy hay antony',
'math_unknown_function' => 'fonction tsy fantatra',
'math_syntax_error'     => 'Misy diso ny raikipohy',
'math_image_error'      => 'Tsy voavadika ho PNG; hamarino fa mety voapetraka tsara ny rindrankajy latex, dvips, gs ary convert',
'math_bad_tmpdir'       => "Tsy afaka namorona na nanoratra répertoire vonjimaika ho an'ny matematika",
'math_bad_output'       => "Tsy afaka namorona na nanoratra tao amin'ny répertoire hampiasain'ny asa matematika",
'math_notexvc'          => 'Tsy hita ny rindrankajy texvc; azafady jereo math/README hanamboarana azy.',
'prefs-personal'        => 'Mombamomba anao',
'prefs-rc'              => 'Vao niova',
'prefs-misc'            => 'Hafa',
'saveprefs'             => 'Tehirizo',
'resetprefs'            => 'Avereno',
'textboxsize'           => 'Fanovana',
'searchresultshead'     => 'Fikarohana',
'recentchangescount'    => "Lohateny amin'ny vao niova:",
'savedprefs'            => 'Voatahiry ny mombamomba anao.',
'timezonetext'          => "
Elanelan'ny ora any aminao sy aty amin'ny seveur (GMT).",
'servertime'            => "Fahelan'ny lohamilina",
'guesstimezone'         => "
Fenoy araka ny datin'ny solosainan'ny mpitsidika",
'allowemail'            => "Ekeo ny handraisana imailaka avy amin'ny mpikambana hafa",
'files'                 => 'Rakitra',

# User rights
'userrights'               => 'Fandrindràna ny fahazoan-dàlana', # Not used as normal message but as header for the special page itself
'userrights-lookup-user'   => 'Handrindra vondrom-pikambana',
'userrights-user-editname' => 'Manomeza solonanarana:',
'userrights-editusergroup' => 'Hanova vondrom-pikambana',
'saveusergroups'           => 'Tehirizo ny vondrom-pikambana',
'userrights-groupsmember'  => "Mpikambana amin'ny vondrona:",

# Groups
'group-user'          => 'Mpikambana',
'group-autoconfirmed' => 'Mpikambana voasoratra',
'group-bot'           => 'Mpikambana rôbô',
'group-sysop'         => 'Mpandrindra',
'group-bureaucrat'    => 'Borōkraty',
'group-all'           => '(izy rehetra)',

'group-user-member'          => 'Mpikambana',
'group-autoconfirmed-member' => 'Mpikambana voasoratra',
'group-bot-member'           => 'Mpikambana rôbô',
'group-sysop-member'         => 'Mpandrindra',
'group-bureaucrat-member'    => 'Borôkraty',
'group-suppress-member'      => 'Mpitondra',

'grouppage-user'          => '{{ns:project}}:Mpikambana',
'grouppage-autoconfirmed' => '{{ns:project}}:Mpikambana Voamafy',
'grouppage-bot'           => '{{ns:project}}:Mpikambana rôbô',
'grouppage-sysop'         => '{{ns:project}}:Mpandrindra',
'grouppage-bureaucrat'    => '{{ns:project}}:Borōkraty',

# Rights
'right-autoconfirmed'  => 'Manova pejy voaaro an-tapaka',
'right-bot'            => 'Atao hita otra ny fizorana mande hoazy',
'right-nominornewtalk' => "Tsy alefa ny fampandrenesana ''hafatra vaovao'' rehefa manao fanovana kely ao anatin'ny pejin-dresan'ny mpikambana.",

# User rights log
'rightslog'     => "Tantaran'asa ny fanovàna sata ny mpikambana",
'rightslogtext' => "Tantaran'asa momba ny fahazoan-dàlan'ny mpikambana.",

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'ovay ity pejy ity',

# Recent changes
'nchanges'                          => '{{PLURAL:$1|fanovana|fanovana}} $1',
'recentchanges'                     => 'Fanovana farany',
'recentchanges-legend'              => 'Safidy ny fanovàna farany',
'recentchangestext'                 => "Jereo eto amin'ity pejy ity izay vao niova vao haingana teto amin'ity wiki ity.",
'recentchanges-feed-description'    => "Arao ny fanovàna farany amin'ity wiki ity anaty topa",
'rcnotefrom'                        => "Ity eto ambany ity ny lisitry ny vao niova manomboka ny <b>$2</b> (hatramin'ny <b>$1</b> no miseho).",
'rclistfrom'                        => 'Asehoy izay vao niova manomboka ny $1',
'rcshowhideminor'                   => '$1 ny fanovàna kely',
'rcshowhidebots'                    => '$1 ny mpikambana rôbô',
'rcshowhideliu'                     => "$1 ny mpikambana nanoratr'anarana",
'rcshowhideanons'                   => "$1 ny mpikambana tsy nanoratr'anarana",
'rcshowhidemine'                    => '$1 ny fanovàko',
'rclinks'                           => "Asehoy ny $1 niova farany tato anatin'ny $2 andro<br />$3",
'diff'                              => 'Fampitahana',
'hist'                              => 'tant.',
'hide'                              => 'Afeno',
'show'                              => 'Asehoy',
'minoreditletter'                   => 'k',
'newpageletter'                     => 'V',
'boteditletter'                     => 'r',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|mpikambana|mpikambana}} manara-maso]',
'rc_categories'                     => 'Ferana amin\'ireto sokajy ireto ihany (saraho amin\'ny "|")',
'rc_categories_any'                 => 'Tsy misy fetrany',
'rc-enhanced-expand'                => 'Jereo ny detail (mila JavaScript)',
'rc-enhanced-hide'                  => 'Asitrio ny adidiny',

# Recent changes linked
'recentchangeslinked'       => 'Novaina',
'recentchangeslinked-title' => "Fanaraha-maso ny pejy miarak'amin'ny « $1 »",
'recentchangeslinked-page'  => 'anaram-pejy :',
'recentchangeslinked-to'    => "Ampisehoy ny fanovàn'ny pejy misy rohy makany amin'ny pejy fa tsy atao mivadika",

# Upload
'upload'                      => 'Handefa rakitra',
'uploadbtn'                   => 'Alefaso ny rakitra',
'reupload'                    => 'Alefaso indray',
'reuploaddesc'                => "Miverena any amin'ny fisy fandefasan-drakitra.",
'uploadnologin'               => 'Tsy niditra',
'uploadnologintext'           => 'Mila [[Special:UserLogin|misoratra anarana]] aloha vao afaka mandefa rakitra.',
'upload_directory_read_only'  => "Ny répertoire ($1) handraisana ny rakitra alefan'ny mpikambana dia tsy afaka anoratana.",
'uploaderror'                 => 'Nisy tsy fetezana ny fandefasana rakitra',
'uploadtext'                  => "Ampiasao ity fisy ity handefasana rakitra. Jereo eto ny [[Special:FileList|lisitry ny rakitra]] nalefan'ny mpikambana, na koa azonao ampiasaina ny [[Special:Log/upload|tantaran'asan'ny fandefasana sy famonoana rakitra]].

Raha hanisy sary ao anaty pejy, dia mampiasà rohy toy ny iray amin'ireto
'''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:file.jpg]]</nowiki>''', na
'''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:file.png|alt text]]</nowiki>''' na
'''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:file.ogg]]</nowiki>''' raha hirohy mivantana amin'ny rakitra.",
'uploadlog'                   => "Tantaran'asan'ny fandefasana rakitra",
'uploadlogpage'               => "Tantaran'asa ny fampidirana rakitra",
'uploadlogpagetext'           => "Ity ny lisitr'ireo rakitra nalefa farany indrindra.",
'filename'                    => 'Anarana',
'filedesc'                    => 'Ambangovangony',
'fileuploadsummary'           => 'Ambangovangony:',
'filesource'                  => 'Loharano:',
'uploadedfiles'               => 'Rakitra voaray',
'ignorewarning'               => 'Aza mihaino fampitandremana fa tehirizo foana ny rakitra.',
'ignorewarnings'              => 'Aza mihaino fampitandremana',
'illegalfilename'             => 'Misy litera tsy mety amin\'ny lohateny ny anaran\'ilay rakita "$1". Azafady soloy ny anaran\'ny rakitra dia andramo alefa indray.',
'badfilename'                 => 'Novana ho "$1" ny anaran\'ny rakitra.',
'largefileserver'             => "
Ngeza noho izay zakan'ny serveur io rakitra io.",
'emptyfile'                   => "Ohatry ny tsy misy na inona na inona ilay rakitra nalefanao teo.
Sao dia misy diso tsipelina ny anaran'ny rakitra? Azafady mba hamarino fa tena naniry handefa io rakitra io tokoa ianao.",
'fileexists'                  => "Efa misy rakitra iray mitondra an'io anarana io, azafady jereo aloha '''<tt>$1</tt>''' raha tsy matoky tanteraka ianao fa te-hanova io rakitra io.",
'fileexists-forbidden'        => "Efa misy rakitra iray mitondra an'io anarana io, azafady miverena amin'ny pejy teo aloha dia avereno alefa ilay rakitra ary omeo anarana hafa. [[File:$1|thumb|center|$1]]",
'fileexists-shared-forbidden' => "
Efa misy rakitra iray mitondra an'io anarana io ao amin'ny file repository, azafady miverena amin'ny pejy teo aloha dia avereno alefa ilay rakitra ary omeo anarana hafa. [[File:$1|thumb|center|$1]]",
'successfulupload'            => 'Voaray soa aman-tsara ny rakitra',
'uploadwarning'               => 'Fampitandremana',
'savefile'                    => 'Tehirizo ny rakitra',
'uploadedimage'               => 'tonga ny rakitra"[[$1]]"',
'uploaddisabled'              => 'Miala tsiny! Tsy azo atao ny mandefa rakitra.',
'uploaddisabledtext'          => "Tsy afaka andefasana rakitra aloha eto amin'ity wiki ity.",
'uploadscripted'              => "
Misy kialo HTML na fango script mety tsy ho hain'ny navigateur sasany haseho ity rakitra ity.",
'uploadcorrupt'               => 'Misy tailana ny rakitra na diso ny extension-ny. 
Hamarino tsara aloha dia avereno alefa indray.',
'uploadvirus'                 => 'Misy viriosy io rakitra io! Toy izao ny antsipirihany: $1',
'sourcefilename'              => "Anaran'ny rakitra:",
'destfilename'                => "Anaran'ny rakitra:",

'nolicense' => 'Tsy misy safidy',

# Special:ListFiles
'listfiles' => 'Lisitry ny rakitra',

# File description page
'filehist'                  => 'Tantara ny rakitra',
'filehist-current'          => 'ankehitriny',
'filehist-datetime'         => 'Daty sy ora',
'filehist-thumb'            => 'sari-tapaka',
'filehist-thumbtext'        => "Vignette hoan'ny $1",
'filehist-user'             => 'Mpikambana',
'filehist-dimensions'       => 'Hangeza',
'filehist-comment'          => 'resaka',
'imagelinks'                => "Fampiasan'io rakitra io",
'linkstoimage'              => "Ireto avy no pejy mirohy amin'io rakitra io:",
'nolinkstoimage'            => "Tsy misy pejy mirohy amin'ity sary ity.",
'sharedupload'              => "Mety ho rakitra itambarana amin'ny tetikasa hafa ny rakitra $1.", # $1 is the repo name, $2 is shareduploadwiki(-desc)
'shareduploadwiki'          => 'Fanazavana fanampiny, jereo $1.',
'shareduploadwiki-linktext' => 'pejy famaritana ny rakitra',
'noimage'                   => 'Tsy misy rakitra mitondra io anarana io, afaka $1 ianao.',
'noimage-linktext'          => 'alefaso',
'uploadnewversion-linktext' => "Andefa version vaovao n'ity rakitra ity",

# MIME search
'mimesearch' => 'Fikarohana MIME',

# Unwatched pages
'unwatchedpages' => 'Pejy voaaisotra ny fanaraha-maso azy',

# Random page
'randompage' => 'Takelaka kisendra',

# Statistics
'statistics'              => 'Fanao pejy',
'statistics-header-users' => "Statistikan'ny mpikambana",

'brokenredirects'        => 'Tapaka ny redirection',
'brokenredirectstext'    => "Mirohy any amin'ny pejy tsy misy ireto redirection manaraka ireto",
'brokenredirects-edit'   => '(ovao)',
'brokenredirects-delete' => '(fafao)',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|oktety|oktety}}',
'ncategories'             => '{{PLURAL:$1|vondrona|vondrona}} $1',
'nlinks'                  => '{{PLURAL:$1|rohy|rohy}} $1',
'nmembers'                => 'mpikambana $1',
'nrevisions'              => '{{PLURAL:$1|fanovana|fanovana}} $1',
'lonelypages'             => 'Pejy manirery',
'uncategorizedpages'      => 'Pejy tsy voasokajy',
'uncategorizedcategories' => 'Sokajy tsy voasokajy',
'uncategorizedimages'     => 'Rakitra tsy voasokajy',
'uncategorizedtemplates'  => 'Endrika tsy voasokajy',
'unusedcategories'        => 'Sokajy tsy miasa',
'unusedimages'            => 'Rakitra tsy miasa',
'popularpages'            => 'Pejy maresaka',
'wantedcategories'        => 'Vondrona tokony hoforonina',
'wantedpages'             => 'Pejy tokony hoforonina',
'mostlinked'              => "Misy firohizana betsaka amin'ny pejy hafa",
'mostlinkedcategories'    => "Misy firohizana betsaka amin'ny sokajy",
'mostlinkedtemplates'     => "Misy firohizana betsaka amin'ny endrika",
'mostcategories'          => 'Lahatsoratra misy sokajy betsaka indrindra',
'mostimages'              => "Misy firohizana betsaka amin'ny sary",
'mostrevisions'           => 'Lahatsoratra niova im-betsaka indrindra',
'prefixindex'             => "Pejy manombok'amin'ny...",
'shortpages'              => 'Pejy fohy',
'longpages'               => 'Pejy lavabe',
'listusers'               => 'Lisitry ny mpikambana',
'newpages'                => 'pejy Vaovao',
'ancientpages'            => 'Ireo pejy tranainy indrindra',
'move'                    => 'Afindrao',
'movethispage'            => 'Afindrao ity pejy ity',
'unusedimagestext'        => "
<p>Mariho tsara aloha fa mety misy sehatra hafa mampiasa ireto sary ireto
ka mety ho antony tokony hamelana azy eto izany na dia tsy miasa ato anatin'ity
wiki ity aza izy.</p>",
'unusedcategoriestext'    => 'Ireto sokajy manaraka ireto dia noforonina kanefa tsy misy pejy na dia iray aza mampiasa azy akory.',
'notargettext'            => 'Tsy nofaritanao ny pejy na solonanarana mpikambana hanaovana io asa io.',
'pager-newer-n'           => '$1 vao haingana',
'pager-older-n'           => '$1 taloha',

# Book sources
'booksources'               => 'boky tsiahy',
'booksources-search-legend' => "hikaroka anatin'ny boky todika",
'booksources-go'            => 'Ataovy lisitra',

# Special:Log
'specialloguserlabel'  => 'Mpikambana:',
'speciallogtitlelabel' => 'Lohateny:',
'log'                  => "Tantaran'asa",
'alllogstext'          => "
Fisehoan'ny tantaran'asa (logs) momba ny upload, famafana, fiarovana, fisakanana, ary sysop.
Mifidiana karazana tantaran'asa (log type), Solonanarana mpikambana, na ny pejy voakasik'izany mba hahafahanao
mampihena ny tantaran'asa miseho eto.",
'logempty'             => 'Tsy nahitana.',

# Special:AllPages
'allpages'          => 'Pejy rehetra',
'alphaindexline'    => "$1 hatramin'ny $2",
'nextpage'          => 'Pejy manaraka ($1)',
'prevpage'          => 'Pejy taloha ($1)',
'allpagesfrom'      => 'Asehoy ny pejy manomboka ny:',
'allpagesto'        => "Ampiseo ny pejy hatramin'ny :",
'allarticles'       => 'Lahatsoratra rehetra',
'allinnamespace'    => 'Pejy rehetra ($1 namespace)',
'allnotinnamespace' => "Ny pejy rehetra (tsy ao amin'ny $1 namespace)",
'allpagesprev'      => 'Aloha',
'allpagesnext'      => 'Manaraka',
'allpagessubmit'    => 'Alefa',
'allpagesprefix'    => "Asehoy ny pejy miantomboka amin'ny:",

# Special:Categories
'categories'         => 'Sokajy',
'categoriespagetext' => "Ireto no sokajy misy eto amin'ity wiki ity.",

# Special:LinkSearch
'linksearch' => 'Rohy ivelany',

# Special:Log/newusers
'newuserlogpage'          => "Tantaran'asa ny fampidiran'ny mpikambana vaovao",
'newuserlog-create-entry' => 'Mpikambana vaovao',

# Special:ListGroupRights
'listgrouprights-members' => '(lisitra ny mpikambana)',

# E-mail user
'mailnologin'     => 'Tsy misy adiresy handefasana ny tenimiafina',
'mailnologintext' => "Mila [[Special:UserLogin|miditra]] ianao sady manana imailaka mandeha sy voamarina ao amin'ny [[Special:Preferences|mombamomba anao]] vao afaka mandefa imailaka amin'ny mpikambana hafa.",
'emailuser'       => 'Andefaso imailaka io mpikambana io',
'emailpage'       => 'Andefaso imailaka io mpikambana io',
'emailpagetext'   => "
Raha nametraka adiresy tena miasa tao amin'ny mombamomba azy io mpikambana io,
dia ahafahana mandefa hafatra tokana ho any aminy ity fisy eto ambany ity.
Ny adiresy imailakao napetrakao tao amin'ny mombamomba anao no hiseho hoe 
adiresin'ny mpandefa izany imailaka izany, koa afaka hovaliany izay hafatra alefanao.",
'usermailererror' => "Misy tsy mety amin'ny lohatenin'ny imailaka:",
'defemailsubject' => "imailaka avy amin'ny sehatra {{SITENAME}}",
'noemailtext'     => "
Na tsy nanome adiresy imailaka voamarina io mpikambana io, 
na tsy maniry handray imailaka avy amin'ny mpikambana hafa izy.",
'emailfrom'       => "Avy tamin'i",
'emailto'         => "Ho an'i",
'emailmessage'    => 'Hafatra',
'emailsend'       => 'Alefaso',
'emailsent'       => 'Lasa',
'emailsenttext'   => 'Lasa soa aman-tsara ny imailaka nalefanao.',

# Watchlist
'watchlist'            => 'Narahiko maso',
'mywatchlist'          => 'Pejy arahako-maso',
'watchlistfor'         => "(hoan'ny/i '''$1''')",
'nowatchlist'          => "Tsy misy n'inon'inona ao amin'ny lisitry ny pejy arahinao maso.",
'watchnologin'         => 'Tsy tafiditra',
'watchnologintext'     => 'Mila [[Special:UserLogin|miditra/misoratra anarana]] aloha ianao vao afaka manova ny lisitry ny pejy arahinao maso.',
'addedwatch'           => "Tafiditra amin'ny lisitry ny pejy arahi-maso akaiky",
'addedwatchtext'       => "Tafiditra anatin'ny lisitry ny [[Special:Watchlist|PejyArahiMaso]]-nao ny pejy \"<nowiki>\$1</nowiki>\".
Ny fanovana hisy amin'io pejy io sy ny pejin-dresaka miaraka aminy dia hiseho ao,
ary rehefa miseho ao amin'ny [[Special:RecentChanges|lisitry ny takelaka vao niova]] io pejy io dia hatao ''matavy'' mba
hahamora ny fahitana azy.

Aoriana, raha irinao ny hanaisotra azy ao amin'ny PejyArahiMaso-nao, dia tsindrio ilay hoe \"Unwatch\" etsy amin'ny sisiny esty.",
'removedwatch'         => "Voaaisotra tao amin'ny lisitry ny pejy arahi-maso",
'removedwatchtext'     => 'Voaaisotra tao amin\'ny lisitry ny pejy arahi-maso-nao ny pejy "[[:$1]]".',
'watch'                => 'Fanaraha-maso',
'watchthispage'        => 'Araho maso ity pejy ity',
'unwatch'              => 'Aza arahi-maso intsony',
'unwatchthispage'      => 'Aza arahi-maso intsony',
'notanarticle'         => 'Tsy votoatim-pejy',
'watchnochange'        => 'Tsy misy niova nandritra ny fotoana voafaritra ny pejy arahinao maso.',
'watchlist-details'    => 'Lisitra ny pejy $1 arahanao-mason, tsy miisa eto ny pejin-dresaka.',
'wlheader-enotif'      => "* Mandeha ny fampilazana amin'ny imailaka.",
'wlheader-showupdated' => "* Ny pejy niova hatramin'ny namakiana azy farany dia aseho amin'ny '''tarehin-tsoratra matavy'''",
'watchmethod-recent'   => "fitanisana ny pejy arahi-maso amin'ny isan'izay vao niova",
'watchmethod-list'     => "fitanisana ny vao novaina tamin'ny pejy arahi-maso",
'watchlistcontains'    => 'Misy {{PLURAL:$1|pejy|pejy}} $1 ny lisitry ny pejy arahinao maso.',
'iteminvalidname'      => "Misy olana eo amin'ny raha '$1', tsy mety ny anarana...",
'wlshowlast'           => "Aseho ny hatramin'ny $1 ora $2 andro $3",
'watchlist-options'    => "Ny safidiko amin'ny pejy fanaraha-maso",

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Manara-maso...',
'unwatching' => 'Manapitra ny fanaraha-maso ...',

'enotif_reset'       => 'Mariho efa voavaky ny pejy rehetra',
'enotif_newpagetext' => 'Pejy vaovao ity pejy ity.',
'changed'            => 'niova',
'created'            => 'voaforona',
'enotif_subject'     => '$CHANGEDORCREATED $PAGEEDITOR ny pejy $PAGETITLE tao amin\'ny {{SITENAME}}',
'enotif_lastvisited' => "Jereo eto $1 ny niova rehetra hatramin'ny fitsidihanao farany.",
'enotif_body'        => '$WATCHINGUSERNAME,

$CHANGEDORCREATED $PAGEEDITOR tamin\'ny $PAGEEDITDATE ny pejy $PAGETITLE tao amin\'ny sehatra {{SITENAME}}. Jereo eto $PAGETITLE_URL ny votoatiny ankehitriny.

$NEWPAGE

Fanazavana tsotsotra: $PAGESUMMARY $PAGEMINOREDIT

Fifandraisana amin\'ny nanova ny pejy:
imailaka: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Tsy handefasana fampahafantarana intsony aloha ianao momba io pejy io na dia misy manova aza izy mandra-pitsidikao azy. 
Azonao atao koa ny manajanona ny fampahafantarana anao aloha na misy manova aza ny pejy iray ao amin\'ny lisitry ny pejy arahinao maso.

             Ny fitaovana fampahafantarana eto amin\'ny {{SITENAME}}

--
Raha hanova ny fandehan\'ny momba ny lisitry ny pejy arahi-maso, jereo
{{fullurl:{{ns:special}}:Watchlist/edit}}

Hevitrao sy fanampiana:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'            => 'Fafao ny pejy',
'confirm'               => 'Antero',
'excontent'             => "votoatiny: '$1'",
'excontentauthor'       => "votoatiny: '$1' (ary i '[[Special:Contributions/$2|$2]]' irery ihany no nikitika azy)",
'historywarning'        => 'Fampitandremana: Manana tantara io pejy hofafanao io (izany hoe farafaharatsiny indray mandeha niova):',
'confirmdeletetext'     => "Handeha hamafa tanteraka ny pejy na sary miaraka amin'ny tantarany rehetra 
ao anatin'ny toby ianao. Azafady mba hamafiso fa irinao tokoa izany, 
fantatrao ny vokany ary mahalala ianao fa tsy mifanipaka amin'ny 
[[{{MediaWiki:Policy-url}}|fepetra]] izao ataonao izany.",
'actioncomplete'        => 'Vita ny asa',
'deletedtext'           => 'Voafafa i "<nowiki>$1</nowiki>".
Jereo amin\'ny $2 ny lisitry ny famafana pejy faramparany.',
'deletedarticle'        => 'voafafa "[[$1]]"',
'dellogpage'            => "Tantaran'asa ny famafàna pejy",
'dellogpagetext'        => 'Eto ambany eto ny lisitry ny famafana pejy/sary faramparany.',
'deletionlog'           => "famafana tatitr'asa",
'reverted'              => "Voaverina amin'ny votoatiny teo aloha",
'deletecomment'         => 'Antony hamonoana pejy',
'deleteotherreason'     => 'antony afa :',
'deletereasonotherlist' => 'antony',

# Rollback
'rollback'       => 'Foano indray ilay fanovana',
'rollback_short' => 'Aza ovaina indray',
'rollbacklink'   => 'foano',
'rollbackfailed' => "Tsy voaverina amin'ny teo aloha",
'cantrollback'   => "Tsy afaka iverenana ny fanovana; ny mpanova farany ihany no tompon'ny pejy.",
'alreadyrolled'  => "Tsy mety foanana ny fanovana farany ny pejy [[:$1]]
nataon'i [[User:$2|$2]] ([[User talk:$2|Dinika]]); satria efa nisy nanova ny pejy na nisy nanafoana io fanovana io.

Ny fanovana farany dia nataon'i [[User:$3|$3]] ([[User talk:$3|Dinika]]).",
'editcomment'    => "Toy izao no fanamarihana momba io fanovana io: \"''\$1''\".", # only shown if there is an edit comment
'revertpage'     => "Voafafa ny fanovana ny [[Special:Contributions/$2|$2]] ([[User talk:$2|Dinika]]); voaverina amin'ny votoatiny teo aloha nataon'i [[User:$1|$1]]", # Additionally available: $3: revid of the revision reverted to, $4: timestamp of the revision reverted to, $5: revid of the revision reverted from, $6: timestamp of the revision reverted from

# Protect
'protectlogpage'              => "Tantaran'asa ny fiarovana",
'protectlogtext'              => 'Eto ambany ny lisitry ny fiarovana/fanalana hidy ny pejy. Fanazavana fanampiny: jereo [[Special:ProtectedPages]].',
'protectedarticle'            => 'voaaro ny pejy "[[$1]]"',
'modifiedarticleprotection'   => "nanova ny haabo ny fiarovana hoan'ny « [[$1]] »",
'unprotectedarticle'          => 'voaala ny fiarovana an\'i "[[$1]]"',
'protect-title'               => 'Fiarovana an\'i "$1"',
'prot_1movedto2'              => 'Novana ho [[$2]] ilay takelaka [[$1]]',
'protect-legend'              => 'Fanekena ny fiarovana pejy',
'protectcomment'              => "Anton'ny fiarovana ny pejy",
'protectexpiry'               => 'Daty fialàna :',
'protect_expiry_invalid'      => 'Tsy mety ilay daty fialàna.',
'protect_expiry_old'          => 'Efa lasa ilay daty fialàna.',
'protect-unchain'             => 'Avela ny fahefahana manova anarana',
'protect-text'                => "Afaka jerenao na ovainao eto ny politikam-piarovana ny pejy '''<nowiki>$1</nowiki>'''.",
'protect-locked-blocked'      => "Tsy afaka ovanao ny sokajy ny fiarovana raha tsy mahazo manoratra ianao.
Ity ny sokajy ny pejy '''$1''' :",
'protect-locked-dblock'       => "Tsy afaka solona ny sokajy ny fiarovana satria ny voatohana ny fotom-pandraisana.
Ity ny reglajy ny pejy  '''$1'''",
'protect-locked-access'       => "Tsy manan'ny alalàna ilaina ianao momba ny fanovàn'ny fiarovan'ny pejy.
Ity ny réglage ny pejy '''$1''' :",
'protect-cascadeon'           => "Voaaro ity pejy ity satria ao anatin'ny ireto pejy izy, voaaro izy io miarak'amin'ny « fiarovana an-driana ». Afaka solonao ny sokaji-piarovana an'ity pejy fa tsy ho voakitika ny fiarovana an-driana.",
'protect-default'             => 'Avela daholo ny mpikambana',
'protect-fallback'            => 'Mila manana sata « $1 »',
'protect-level-autoconfirmed' => 'Sakano ny mpikambana vaovao sy ny mpikambana tsy nisoratra anarana',
'protect-level-sysop'         => 'Sysops ihany',
'protect-summary-cascade'     => 'Fiarovana an-driana',
'protect-expiring'            => "Miala amin'ny $1",
'protect-expiry-indefinite'   => 'tsiefa',
'protect-cascade'             => "Miaro ny pejy ao anatin'ity pejy ity (cascading protection)",
'protect-cantedit'            => "Tsy afaka manolo ny sokaji-piarovan'ity pejy ity ianao satria tsy manana ny sata ilaina",
'protect-otherreason'         => 'Antony afa miampy :',
'protect-dropdown'            => "*Anton'ny fiarovana
** Misy be mpanimba
** Misy be mpametraka spam
** Misy adim-panontana
** Misy olona maro no mandalo eo",
'protect-expiry-options'      => '2 ora:2 hours,1 andro:1 day,3 andro:3 days,1 herinandro:1 week,2 herinandro:2 weeks,1 volana:1 month,3 volana:3 months,6 volana:6 months,1 taona:1 year,mandrakizay:infinite', # display1:time1,display2:time2,...
'restriction-type'            => 'Sata ilaina :',
'restriction-level'           => 'Sokajy ny fanerena :',

# Undelete
'undelete'               => 'Jereo ny pejy voafafa',
'undeletepage'           => 'Hijery sy hamerina ny pejy efa voafafa',
'viewdeletedpage'        => 'Hijery ny pejy efa nofafana',
'undeletepagetext'       => "
Ireto pejy ireto dia efa voafafa nefa mbola voatahiry ao amin'ny arsiva ihany,
ary mbola afaka averina, mandra-pifafan'ny arsiva. Mety ho voafafa matetitetika
ihany ny arsiva.",
'undeleterevisions'      => "{{PLURAL:$1|fanovana|fanovana}} $1 voatahiry any amin'ny arsiva",
'undeletehistory'        => "
Raha averinao ity pejy ity dia hiverina hiaraka aminy koa ny tantaran'ny 
fanovana rehetra natao taminy. Raha efa misy pejy mitondra io anarana io
noforonina taorian'ny namafana azy, dia hitambatra amin'ny tantaran'io
pejy vaovao io ny tantaran'ity pejy voafafa ity, fa tsy ho voafafa akory.",
'undeletehistorynoadmin' => "Efa voafafa io lahatsoratra io. Ny antony namafana azy dia io miseho ambangovangony eo ambany eo io, miaraka amin'ny fampahalalana antsipirihany momba ny mpikambana nikitika io pejy io talohan'ny namafana azy. Ny votoatin'ny pejy izay efa nofafana ireo dia ny mpitantana ihany no afaka mahita azy ankehitriny.",
'undeletebtn'            => 'Avereno!',
'undeletelink'           => 'Topi-maso/averina',
'undeletedarticle'       => 'namerina ny « [[$1]] »',
'undeletedrevisions'     => 'voaverina ny {{PLURAL:$1|fanovana|fanovana}} $1',

# Namespace form on various pages
'namespace'      => 'Toeran-anarana :',
'invert'         => 'Ampifamadiho ny safidy',
'blanknamespace' => '(fotony)',

# Contributions
'contributions'       => "Fandraisan'anjaran'ny mpikambana",
'contributions-title' => "Fandraisan'anjara n'i $1",
'mycontris'           => 'Ny nosoratako',
'contribsub2'         => "hoan'ny $1 ($2)",
'nocontribs'          => "Tsy misy fanovana mifanaraka amin'ireo critères ireo.", # Optional parameter: $1 is the user name
'uctop'               => ' (loha)',
'month'               => "Tamin'ny volana (sy teo aloha) :",
'year'                => "Tamin'ny taona (sy taloha-ny) :",

'sp-contributions-newbies'  => "Ny fandraisan'anjara ny mpikambana vaovao ihany no ampiseho eto",
'sp-contributions-blocklog' => "tantaran'asa ny fanakanana",
'sp-contributions-search'   => "Hikaroka fandraisan'anjara",
'sp-contributions-username' => 'Adiresy IP na anaram-pikambana :',
'sp-contributions-submit'   => 'Hikaroka',

# What links here
'whatlinkshere'            => 'Iza avy no mirohy eto',
'whatlinkshere-title'      => "Pejy makany amin'ny « $1 »",
'whatlinkshere-page'       => 'Pejy :',
'linkshere'                => "Ireto avy no pejy mirohy eto: '''[[:$1]]'''",
'nolinkshere'              => 'Tsy misy pejy mirohy eto.',
'isredirect'               => 'pejina redirekta',
'istemplate'               => 'fanometrahany',
'isimage'                  => 'rakitra mifatotra',
'whatlinkshere-prev'       => '$1 taloha',
'whatlinkshere-next'       => '$1 manaraka',
'whatlinkshere-links'      => '← rohy',
'whatlinkshere-hideredirs' => '$1 ny redirekt',
'whatlinkshere-hidetrans'  => '$1 ny nampidirana',
'whatlinkshere-hidelinks'  => '$1 ny rohy',
'whatlinkshere-filters'    => 'sivana',

# Block/unblock
'blockip'                     => 'Sakano ny mpikambana',
'blockiptext'                 => "Ampiasao ity formulaire ity hisakanana ny fahazoan-dàlana hanoratra
ananan'ny adiresy IP iray na solonanarana iray.
Tokony ho antony fisorohana ny fisomparana ihany, ary mifanaraka amin'ny [[{{MediaWiki:Policy-url}}|fepetra]]
ihany no hanaovana ny fisakanana.
Fenoy etsy ambany ny antony manokana (ohatra, mitanisà pejy nosomparana).",
'ipaddress'                   => 'Adiresy IP',
'ipadressorusername'          => 'Adiresy IP na solonanarana',
'ipbexpiry'                   => 'Fahataperana',
'ipbreason'                   => 'Antony',
'ipbsubmit'                   => 'Sakano',
'ipbother'                    => 'Hafa',
'ipboptions'                  => '2 ora:2 hours,1 andro:1 day,3 andro:3 days,1 herinandro:1 week,2 herinandro:2 weeks,1 volana:1 month,3 volana:3 months,6 volana:6 months,1 taona:1 year,mandrakizay:infinite', # display1:time1,display2:time2,...
'ipbotheroption'              => 'hafa',
'badipaddress'                => 'Tsy mety ny adiresy IP (invalid)',
'blockipsuccesssub'           => 'Vita soa aman-tsara ny sakana',
'blockipsuccesstext'          => 'Voasakana i [[Special:Contributions/$1|$1]].
<br />Jereo ny [[Special:IPBlockList|lisitry ny IP voasakana]] raha hanala ny sakana efa misy.',
'unblockip'                   => "Esory ny sakana amin'io mpikambana io",
'unblockiptext'               => "
Ampiasao ity fisy eto ambany ity hanalana ny sakana 
mihatra amin'ny adiresy IP na solonanarana iray.",
'ipusubmit'                   => 'Esory ny sakana',
'ipblocklist'                 => 'Lisitry ny adiresy IP sy mpikambana voasakana',
'blocklistline'               => '$1, $2 nisakana $3 ($4)',
'infiniteblock'               => 'mandrakizay',
'expiringblock'               => "tapitra amin'ny $1",
'blocklink'                   => 'sakano',
'unblocklink'                 => 'esory ny sakana',
'change-blocklink'            => 'ovay ny fanakanana',
'contribslink'                => "fandraisan'anjara",
'autoblocker'                 => "Voasakana satria ny adiresy IP-nao dia vao avy nampiasain'i \"[[User:\$1|\$1]]\". Ny anton'ny fanakanana dia: \"'''\$2'''\"",
'blocklogpage'                => "Tantaran'ny sakana",
'blocklogentry'               => 'voasakana i "[[$1]]" mandritra ny $2 ; antony : $3',
'blocklogtext'                => "Eto no ahitana ny tantaran'ny hetsika momba ny fisakanana sy ny fanafoanana fisakanana mpandray anjara. 
Ireo adiresy IP voasakana ho azy dia tsy miseho eto. Jereo ao amin'ny [[Special:IPBlockList|lisitry ny IP voasakana]]
ny lisitry ny fisakanana sy fandrar� na tanteraka misy ankehitriny.",
'unblocklogentry'             => "voaaisotra ny sakana an'i $1",
'block-log-flags-nocreate'    => 'tsy mahazo manokatra kaonty',
'ipb_expiry_invalid'          => "Tsy mety ilay fotoana hahataperan'ny sakana.",
'ip_range_invalid'            => 'Tsy mety io IP io.',
'proxyblocker'                => 'Mpisakana proxy',
'proxyblockreason'            => "Voasakana ny adiresy IP-nao satria adiresy proxy malalaka izy io. Azafady mba lazao amin'ny mpanome internet anao io olana io.",
'proxyblocksuccess'           => 'Vita.',
'sorbsreason'                 => "Voakilasin'i DNSBL ho ao anatin'ny proxy midanadana ny adiresy IP-nao.",
'sorbs_create_account_reason' => "Voakilasy ho isan'ny proxy midanadana ao amin'ny DNSBL ny adiresy IP-nao. Ireo IP ireo dia ahiana ho fitaovana azon'ny mpandefa spam ampiasaina. Tsy afaka manokatra kaonty ianao",

# Developer tools
'lockdb'              => 'Fanidiana ny banky angona',
'unlockdb'            => "Fanala hidin'ny banky angona",
'lockdbtext'          => "
Rehefa mihidy ny banky angona dia mihantona koa ny 
asa rehetra azon'ny mpikambana atao toy ny manova pejy, manova ny mombamomba azy,
manova ny lisitry ny pejy arahiny maso sy ny zavatra hafa mila fifandraisana amin'ny 
banky angona.
Azafady antero fa tena ilainao izany, ary hoesorinao io hidy io rehefa vita izay ataonao.",
'unlockdbtext'        => "
Ny fanalana ny hidin'ny banky angona dia mameriny ny fahafahan'ny tsirairay manova
sy mamorona pejy, manova ny mombamomba azy na ny lisitry ny pejy arahiny maso, sy izay 
asa hafa mila fifandraisana amin'ny banky angona.
Azafady mba antero fa izay tokoa no tena irinao.",
'lockconfirm'         => 'Eny tompoko, tena tiako hidiana aloha ny banky angona',
'unlockconfirm'       => "Eny, tena tiako hovohaina amin'izay ny banky angona.",
'lockbtn'             => 'Hidio ny banky angona',
'unlockbtn'           => 'Vohay ny banky angona',
'locknoconfirm'       => 'Tsy nomarihinao ilay faritra natokana hananterana ny safidinao.',
'lockdbsuccesssub'    => 'Voahidy ny banky angona',
'unlockdbsuccesssub'  => "Voaala ny hidin'ny banky angona",
'lockdbsuccesstext'   => 'Voahidy ny banky angona
<br />Aza adino ny manala hidy rehefa vita izay ataonao.',
'unlockdbsuccesstext' => "Voaala soa aman-tsara ny hidin'ny banky angona.",

# Move page
'move-page-legend'        => 'Afindrao toerana ny pejy',
'movearticle'             => 'Afindrao toerana ny pejy',
'movenologin'             => 'Tsy mbola tafiditra ianao',
'movenologintext'         => 'Ny mpikambana nisoratra anarana sy [[Special:UserLogin|tafiditra]] ihany no afaka mamindra toerana takelaka.',
'newtitle'                => 'Lohateny vaovao',
'move-watch'              => 'araho-maso ity pejy ity',
'movepagebtn'             => 'Afindrao',
'pagemovedsub'            => 'Voafindra ny pejy',
'movepage-moved'          => "<big>voafindra tany amin'ny '''$2''' i '''$1'''</big>", # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => 'Efa misy ny lahatsoratra mitondra io anarana io, na 
tsy mety ny anarana nosafidianao.
Azafady mba misafidiana anarana hafa.',
'talkexists'              => "
'''Tafafindra soa aman-tsara ny pejy, fa ny pejin-dresaka
miaraka aminy no tsy afaka nakisaka satria efa misy pejin-dresaka
mifanaraka amin'ilay anarana vaovao. Azafady mba atambaro izay pejin-dresaka izay.'''",
'movedto'                 => "voafindra any amin'ny",
'movetalk'                => 'Afindrao any koa ny "pejin-dresaka", raha mety.',
'1movedto2'               => 'Novana ho [[$2]] ilay takelaka [[$1]]',
'1movedto2_redir'         => 'Redirection: Novaina ho [[$2]] i [[$1]]',
'movelogpage'             => "Tantaran'asan'ny famindran-toerana",
'movelogpagetext'         => 'Lisitry ny pejy nafindra toerana.',
'movereason'              => 'Antony',
'revertmove'              => 'averina',
'delete_and_move'         => 'Ovay toerana dia fafao',
'delete_and_move_text'    => '==Mila fafàna==

Efa misy ny lahatsoratra hoe "[[:$1]]". Irinao ve ny hamafana azy mba hahafahana mamindra toerana ity lahatsoratra ity?',
'delete_and_move_confirm' => 'Eny, fafao io pejy io',
'delete_and_move_reason'  => 'Fafao mba hamindrana toerana ny anankiray',
'selfmove'                => 'Mitovy ny anarana taloha sy anarana vaovao; tsy afaka afindra ny pejy.',

# Export
'export'          => 'Hanondrana pejy',
'exporttext'      => "Afaka manondrana ny lahatsoratra miaraka amin'ny tantaram-panovana ny pejy na vondrom-pejy maromaro ianao.
Aoriana dia mety hafaran'ny wiki iray mandeha amin'ny rindrankajy MediaWiki izany, na dia mbola tsy afaka 
atao aza izany amin'izao fotoana izao.

Ny fomba fanondranana pejy dia, manomeza lohateny izay na maromaro eto amin'ny boaty ety ambany eto, lohateny iray isaky ny andalana,
ary safidio na ny votoatiny ankehitriny ihany no ilainao na miaraka amin'ny endriky ny pejy rehetra taloha, sy hoe ny votoatiny ankehitriny
miampy fampahalalana momba ny fanovana farany fotsiny ve sa miaraka amin'ny tantaran'ny fanovana rehetra.

Etsy amin'ny toerana farany dia afaka mampiasa rohy ihany koa ianao, ohatra [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] ho an'ny [[{{MediaWiki:Mainpage}}]].",
'exportcuronly'   => "Ny votoatiny ankehitriny ihany no haondrana fa tsy miaraka amin'ny tantarany iray manontolo",
'exportnohistory' => "
----
'''Fanamarihana:''' nosakanana aloha ny fanondranana pejy miaraka amin'ny tantarany iray manontolo amin'ny alalan'ity fisy ity noho ny antony performance.",

# Namespace 8 related
'allmessagesname'           => 'Anarana',
'allmessagesdefault'        => 'Dikan-teny tany am-boalohany',
'allmessagescurrent'        => 'Dikan-teny miasa ankehitriny',
'allmessagestext'           => "Ity no lisitry ny system messages misy eto amin'ity MediaWiki: namespace ity.",
'allmessagesnotsupportedDB' => "Tsy mbola mandeha ny '''{{ns:special}}:Allmessages''' satria tsy mandeha koa ny '''\$wgUseDatabaseMessages'''.",
'allmessagesmodified'       => 'Dikan-teny efa novaina ihany',

# Thumbnails
'thumbnail-more' => 'Angezao',
'filemissing'    => 'Tsy hita ny rakitra',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Ny pejinao',
'tooltip-pt-mytalk'               => 'Pejin-dresakao',
'tooltip-pt-preferences'          => 'Ny safidinao',
'tooltip-pt-watchlist'            => 'Ny lisitra ny pejy arahanao-maso',
'tooltip-pt-mycontris'            => "Lisitra ny fandraisan'anjaranao",
'tooltip-pt-login'                => 'Tsara aminao no miditra na manoratra anarana, fa tsy voatery ianao.',
'tooltip-pt-logout'               => 'Hidio',
'tooltip-ca-talk'                 => "resaka momba n'io takelaka io",
'tooltip-ca-edit'                 => "Azonao atao no manova n'ity pejy ity.
Ampesao ny topi-maso aloha no mihatiry.",
'tooltip-ca-addsection'           => 'hanomboka fizaràna vaovao',
'tooltip-ca-viewsource'           => 'Voaaro ilay pejy. Fa afaka itanao ny voatotiny.',
'tooltip-ca-history'              => "Ny revision natao tamin'ity pejy ity",
'tooltip-ca-protect'              => 'Arovy ity pejy ity',
'tooltip-ca-delete'               => 'Fafao ity pejy ity',
'tooltip-ca-move'                 => 'Ovay anarana ilay pejy',
'tooltip-ca-watch'                => "Ampio amin'ny lisitra ny pejy arahinao-maso ity pejy ity",
'tooltip-ca-unwatch'              => "Esory amin'ny pejy arahinao ity pejy ity",
'tooltip-search'                  => "Karoka amin'ny {{SITENAME}}",
'tooltip-search-go'               => "Mandana any amina pejy mitondra n'io anarana io ra misy.",
'tooltip-search-fulltext'         => "Tadiavo ny pejy misy an'io lahatsoratra io.",
'tooltip-n-mainpage'              => 'Jereo ny renipejy',
'tooltip-n-portal'                => 'Mombamomba ny tetikasa',
'tooltip-n-currentevents'         => "Tadiavo ny fampandrenesana momba amin'ny hetsika vao ankehitriny",
'tooltip-n-recentchanges'         => "Ny lisitra ny fanovàna eo amin'io wiki io",
'tooltip-n-randompage'            => 'Hjery pejy kisendra',
'tooltip-n-help'                  => 'fanoroana',
'tooltip-t-whatlinkshere'         => 'Lisitra ny pejy wiki mirohy eto',
'tooltip-t-recentchangeslinked'   => "Lisitra ny fanovana vao haingan'ny pejy ''miraikitra'' amin'ity pejy ity",
'tooltip-feed-rss'                => "topaka RSS hoan'ity pejy ity",
'tooltip-feed-atom'               => "Topaka atom an'ity pejy ity",
'tooltip-t-contributions'         => "Jereo ny lisitra ny fandraisan'anjara n'io mpikambana io",
'tooltip-t-emailuser'             => 'Andefaso imailaka io mpikambana io',
'tooltip-t-upload'                => 'Handefa sary na rakitra',
'tooltip-t-specialpages'          => 'Listry ny pejy manokana rehetra',
'tooltip-t-print'                 => "Version azo avoaka taratasy n'ity pejy",
'tooltip-t-permalink'             => "rohy maharitra amin'ity pejy ity manokana",
'tooltip-ca-nstab-main'           => 'Jereo ny takelaka',
'tooltip-ca-nstab-user'           => 'Jereo ilay peji-mpikambana',
'tooltip-ca-nstab-special'        => 'Pejy manokana ity pejy ity, tsy afaka ovainao.',
'tooltip-ca-nstab-project'        => 'Jereo ny pejy tetikasa',
'tooltip-ca-nstab-image'          => "jereo ny pejy n'io rakitra io",
'tooltip-ca-nstab-template'       => 'jereo ny endrika',
'tooltip-ca-nstab-category'       => "Jereo ny pej n'io sokajy io",
'tooltip-minoredit'               => 'Mariho ho fanovana madinika ihany',
'tooltip-save'                    => 'Tehirizo ny fanovana',
'tooltip-preview'                 => 'Topazy maso ny fanovana nataonao, iangaviana ianao mba hijery tsipalotra mialoha ny fitahirizana ny fanovana!',
'tooltip-diff'                    => "Asehoy izay novainao tamin'ny lahatsoratra.",
'tooltip-compareselectedversions' => "Jereo ny fahasamihafana amin'ireo votoatin'ny pejy anankiroa ireo.",
'tooltip-watch'                   => "Ampidiro amin'ny lisitry ny pejy arahinao maso ity pejy ity",
'tooltip-rollback'                => "Manala ny fanovan'ny mpikambana farany nanova azy ilay asa « foano » (Rollback) .",

# Stylesheets
'monobook.css' => "/* Ovay ity rakitra ity raha hampiasa takilan'angaly (stylesheet) anao manokana amin'ny wiki iray manontolo */",

# Metadata
'notacceptable' => "Tsy afaka manome données amin'ny format zakan'ny navigateur-nao ny serveur wiki.",

# Attribution
'anonymous' => "Mpikambana tsy mitonona anarana eto amin'ny {{SITENAME}}",
'siteuser'  => '{{SITENAME}} mpikambana $1',
'others'    => 'hafa',
'siteusers' => '{{SITENAME}} mpikambana $1',

# Spam protection
'spamprotectiontitle' => "Sivana mpiaro amin'ny spam",
'spamprotectiontext'  => "Voasakan'ny sivana mpiaro amin'ny spam ny pejy saika hotahirizinao. Mety ho anton'izany ny fisian'ny rohy mankany amin'ny sehatra ivelan'ity wiki ity.",
'spamprotectionmatch' => "Izao teny izao: $1 ; no nanaitra ny sivana mpiaro amin'ny spam",

# Info page
'numedits'       => "Isan'ny fanovana (lahatsoratra): $1",
'numtalkedits'   => "Isan'ny resaka (pejin-dresaka): $1",
'numtalkauthors' => "Isan'ny mpiresaka (fa tsy ny resaka) (pejin-dresaka): $1",

# Math options
'mw_math_simple' => 'Raha tena tsotra be dia HTML ampiasaina moa raha tsy izany dia PNG',
'mw_math_html'   => 'HTML raha mety na raha tsy izany dia PNG',
'mw_math_source' => "
Avelao ho TeX (ho an'ny navigateurs textes)",
'mw_math_modern' => "
Amporisihina ho an'ny navigateur moderna",
'mw_math_mathml' => 'MathML raha mety (andrana ihany)',

# Image deletion
'deletedrevision' => "Fanovana an'i $1 taloha voafafa.",

# Browsing diffs
'previousdiff' => '← Ilay fampitahana teo arina',
'nextdiff'     => 'fampitahana manaraka →',

# Media information
'mediawarning'         => "'''Fampitandremana''': Tsy azo antoka ho tsy misy viriosy ity rakitra ity, ahiana hanimba ny solosainao ny fandefasana azy.<hr />",
'imagemaxsize'         => "Ferana ny haben'ny sary ao amin'ny pejy famaritana ho:",
'thumbsize'            => "Haben'ny thumbnail",
'file-info-size'       => "($1 × $2 piksely, hangeza n'ilay rakitra : $3, endrika MIME : $4)",
'file-nohires'         => "<small>Tsy misy sary ngeza non'io</small>",
'svg-long-desc'        => '(rakitra SVG, hangeza $1 × $2 piksely, hangeza : $3)',
'show-big-image'       => 'Sary ngeza hangeza',
'show-big-image-thumb' => "<small>Hangezan'ity topi-maso ity : $1 × $2 piksely</small>",

# Special:NewFiles
'newimages'     => 'Tahala misy ny rakitra vaovao',
'imagelisttext' => 'Eto ambany ny lisitry ny rakitra $1 milahatra araka ny $2.',
'showhidebots'  => '(rôbô $1)',
'noimages'      => 'Tsy misy sary ato.',
'ilsubmit'      => 'Karohy',
'bydate'        => 'araka ny daty',

# Metadata
'metadata'          => 'Metadata',
'metadata-help'     => "Mirakitra fampahalalana fanampiny, izay inoana ho napetraky ny fakan-tsary na scanner nampiasaina nanaovana ny numérisation-ny ity rakitra ity. Raha kitihina na ovana izy ity dia mety tsy hifanitsy amin'ny sary voaova ireo antsipirihany sasany ireo.",
'metadata-expand'   => 'Asehoy ny antsipirihany',
'metadata-collapse' => 'Aza aseho ny antsipirihany',

# External editor support
'edit-externally'      => "Ovao amin'ny alalan'ny fampiasana fitaovana ivelan'ity Wiki ity io rakitra io",
'edit-externally-help' => "jereo any amin'[http://www.mediawiki.org/wiki/Manual:External_editors ny torolalana] ny fanazavana fanampiny,.",

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'rehetra',
'imagelistall'     => 'rehetra',
'watchlistall2'    => 'rehetra',
'namespacesall'    => 'rehetra',
'monthsall'        => 'rehetra',

# E-mail address confirmation
'confirmemail'            => 'Fanamarinana adiresy imailaka.',
'confirmemail_text'       => "
Ity wiki ity dia mitaky anao hanamarina ny adiresy imailaka nomenao
vao mahazo mampiasa ny momba ny imailaka ianao. Ampiasao ity bokotra eto ambany ity
mba handefasana fango fanamarinana any amin'ny adiresinao. Ny hafatra voaray dia ahitana
rohy misy fango. Sokafy amin'ny navigateur-nao 
io rohy io mba hanamarinana fa misy ny adiresy imailaka nomenao.",
'confirmemail_send'       => 'Alefaso ny fanamarinana ny imailaka',
'confirmemail_sent'       => 'Lasa ny fanamarinana ny imailaka.',
'confirmemail_sendfailed' => 'Tsy lasa ny fanamarinana ny imailaka. Hamarino ny adiresy fandrao misy litera tsy mety.',
'confirmemail_invalid'    => 'Tsy mety ilay fango fanamarinana. Angamba efa lany daty?',
'confirmemail_success'    => 'Voamarina ny adiresy imailakao. Afaka miditra ianao ankehitriny dia mankafiza ny wiki.',
'confirmemail_loggedin'   => 'Voamarina ny adiresy imailakao ankehitriny.',
'confirmemail_error'      => 'Nisy tsy fetezana nandritra ny fanamarinana adiresy imailaka.',
'confirmemail_subject'    => "Fanamarinana adiresy imailaka avy amin'ny sehatra {{SITENAME}}",
'confirmemail_body'       => 'Nisy olona, izay ianao ihany angamba, avy tamin\'ny adiresy IP $1, nanokatra kaonty
"$2" tamin\'ity adiresy imailaka ity tao amin\'ny sehatra {{SITENAME}}.

Mba hanamarinana fa anao tokoa io adiresy io ary mba hahafahana mampiasa 
imailaka ao amin\'ny {{SITENAME}}, dia mba sokafy ity rohy eto ambany ity:

$3

Raha *tsy* ianao no nanokatra io kaonty io, dia aza sokafana ilay rohy.
Io fango fanamarinana io dia miasa hatramin\'ny $4.',

# Delete conflict
'deletedwhileediting' => 'Fampitandremana: Nisy namafa ity pejy ity raha mbola teo am-panovana azy ianao!',
'confirmrecreate'     => "Nofafan'i [[User:$1|$1]] ([[User talk:$1|dinika]]) ity lahatsoratra ity taorian'ny nanombohanao nanova azy. Ny antony dia:
: ''$2''
Azafady hamafiso fa tena irinao averina hoforonina tokoa ity lahatsoratra ity.",
'recreate'            => 'Jereo indray',

# action=purge
'confirm_purge_button' => 'Eka',
'confirm-purge-top'    => "Fafana ve ny cache-n'ity pejy ity?",

# Watchlist editing tools
'watchlisttools-view' => 'pejy arahako maso',
'watchlisttools-edit' => 'Jereo sy ovao ny lisitra ny pejy fanaraha-maso',
'watchlisttools-raw'  => 'Ovay ilay pejy arahako maso amizao',

# Special:SpecialPages
'specialpages' => 'Pejy manokana',

);
