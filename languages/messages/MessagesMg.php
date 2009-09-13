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
	NS_MEDIA            => 'Rakitra',
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
	NS_HELP             => 'Fanoroana',
	NS_HELP_TALK        => 'Dinika_amin\'ny_fanoroana',
	NS_CATEGORY         => 'Sokajy',
	NS_CATEGORY_TALK    => 'Dinika_amin\'ny_sokajy',
);

$namespaceAliases = array(
	'Média' => NS_MEDIA,
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
	'Fanampiana' => NS_HELP,
	'Dinika_amin\'ny_fanampiana' => NS_HELP_TALK,
	'Catégorie' => NS_CATEGORY,
	'Discussion_Catégorie' => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'Recentchanges'             => array( 'Fanovàna farany' ),
	'Randompage'                => array( 'Kisendra' ),
	'Uncategorizedpages'        => array( 'Pejy tsy misy sokajy' ),
	'Uncategorizedcategories'   => array( 'Sokajy tsy misy sokajy' ),
	'Uncategorizedimages'       => array( 'Sary tsy misy sokajy', 'Rakitra tsy misy sokajy' ),
	'Uncategorizedtemplates'    => array( 'Endrika tsy misy sokajy' ),
	'Unusedcategories'          => array( 'Sokajy tsy miasa' ),
	'Unusedimages'              => array( 'Rakitra tsy miasa' ),
	'Wantedpages'               => array( 'Pejy tadiavina' ),
	'Wantedcategories'          => array( 'Sokajy tadiavina' ),
	'Wantedfiles'               => array( 'Rakitra tadiavina' ),
	'Wantedtemplates'           => array( 'Endrika tadiavina' ),
	'Mostcategories'            => array( 'Pejy be sokajy indrindra' ),
	'Mostrevisions'             => array( 'Pejy be mpanova indrindra' ),
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
'tog-oldsig'                  => 'Topi-maso ny sonia :',
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

# Font style option in Special:Preferences
'editfont-style'     => "soratra ampiasain'ny toerana isoratana :",
'editfont-default'   => "Izay itiavan'ny navigateur azy",
'editfont-monospace' => 'soratra monospacé',
'editfont-sansserif' => 'soratra tsy misy tongony (sans-serif)',
'editfont-serif'     => 'soratra misy tongony (serif)',

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
'hidden-categories'              => '{{PLURAL:$1|Sokajy misitrika|Sokajy misitrika}} $1',
'hidden-category-category'       => 'Sokajy misitrika',
'category-subcat-count'          => 'Ity sokajy manana zana-tsokajy $1 . Ny taotaliny dia $2',
'category-subcat-count-limited'  => 'Misy zana-tsokajy $1 ity sokajy ity.',
'category-article-count'         => "Misy pejy $1 ireo pejy ireo. Pejy $2 no anatin'ity sokajy ity",
'category-article-count-limited' => "Anatin'ity sokajy ity ireo pejy ireo pejy ireo ($1 ny tontaliny)",
'category-file-count'            => 'Misy rakitra $1 (tontaliny : rakitra $2) ireo ity sokajy ity',
'category-file-count-limited'    => "Anatin'irei sokajy ireto  rakitra $1 ireto",
'listingcontinuesabbrev'         => ' manaraka.',

'mainpagetext'      => "<big>'''Tafajoro soa aman-tsara ny rindrankajy Wiki.'''</big>",
'mainpagedocfooter' => "Vangio ny [http://meta.wikimedia.org/wiki/Aide:Contenu Fanoroana hoan'ny mpampiasa] ra te hitady fanoroana momba ny fampiasan'ity rindrankajy ity.

== Hanomboka amin'ny MediaWiki ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Lisitra ny paramètre de configuration]
* [http://www.mediawiki.org/wiki/Manual:FAQ/fr FAQ momba ny MediaWiki]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Resaka momba ny fizaràn'ny MediaWiki]",

'about'         => 'Mombamomba',
'article'       => "Votoatin'ny pejy",
'newwindow'     => '(sokafy anaty takila hafa)',
'cancel'        => 'Aoka ihany',
'moredotdotdot' => 'Tohiny...',
'mypage'        => 'Pejiko',
'mytalk'        => 'Ny diniko',
'anontalk'      => "Dinika amin'io adiresy IP io",
'navigation'    => 'Fikarohana',
'and'           => '&#32;sy',

# Cologne Blue skin
'qbfind'         => 'Tadiavo',
'qbbrowse'       => 'Tadiavo',
'qbedit'         => 'Ovay',
'qbpageoptions'  => 'Ity pejy ity',
'qbpageinfo'     => 'Pejy fanoroana',
'qbmyoptions'    => 'Pejiko',
'qbspecialpages' => 'Pejy manokana',
'faq'            => 'FAQ (fanontaniana)',
'faqpage'        => 'Project:Fanontaniana',

# Vector skin
'vector-action-addsection'   => 'hanampy foto-kevitra (topic)',
'vector-action-delete'       => 'fafao',
'vector-action-move'         => 'afindrao',
'vector-action-protect'      => 'arovy',
'vector-action-undelete'     => 'avereno',
'vector-action-unprotect'    => 'esory ny fiarovana',
'vector-namespace-category'  => 'sokajy',
'vector-namespace-help'      => 'fanoroana',
'vector-namespace-image'     => 'rakitra',
'vector-namespace-main'      => 'pejy',
'vector-namespace-media'     => 'Pejy Media',
'vector-namespace-mediawiki' => 'hafatra',
'vector-namespace-project'   => 'tetikasa',
'vector-namespace-special'   => 'pejy manokana',
'vector-namespace-talk'      => 'resaka',
'vector-namespace-template'  => 'endrika',
'vector-namespace-user'      => 'pejy ny mpikambana',
'vector-view-create'         => 'amboary',
'vector-view-edit'           => 'ovao',
'vector-view-history'        => 'ampiseho ny tantarany',
'vector-view-view'           => 'vakio',
'vector-view-viewsource'     => 'hijery fango',
'actions'                    => 'Tao',
'namespaces'                 => 'Namespace',
'variants'                   => "Ny ''skin'' Voasintona",

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
'lastmodifiedat'    => "Voaova farany tamin'ny $1 amin'ny $2 ity pejy ity<br />",
'viewcount'         => 'in-$1 ity pejy ity no nisy nijery.',
'protectedpage'     => 'Pejy voaaro',
'jumpto'            => 'Hanketo:',
'jumptonavigation'  => 'Fikarohana',
'jumptosearch'      => 'karohy',
'view-pool-error'   => "Azafady fa somary be asa ny serveur . 
Betsaka laotra ny mpitsidika te-hijery ity pejy ity.
Andraso kely vetivety alohan'ny mamerina.

$1",

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Mombamomba ny {{SITENAME}}',
'aboutpage'            => 'Project:Mombamomba',
'copyright'            => '$1 no mifehy ny fampiasana ny votoatin-kevitra eto.',
'copyrightpage'        => '{{ns:project}}:Copyright',
'currentevents'        => 'Ny vaovao',
'currentevents-url'    => 'Project:Vaovao',
'disclaimers'          => 'Fampitandremana',
'disclaimerpage'       => 'Project:General disclaimer',
'edithelp'             => 'Fanoroana',
'edithelppage'         => 'Help:Endritsoratra',
'helppage'             => 'Help:Fanoroana',
'mainpage'             => 'Fandraisana',
'mainpage-description' => 'Fandraisana',
'policy-url'           => 'Project:Fepetra',
'portal'               => 'Toerana iraisana',
'portal-url'           => 'Project:Fikambanana',
'privacy'              => 'Fitsipika momba ny zavatra tsy sarababem-bahoaka',
'privacypage'          => 'Project:Konfidansialite',

'badaccess'        => 'Tsy manana alàlana',
'badaccess-group0' => 'Tsy afaka manantontosa ny asa nangatahinao ianao tompoko',
'badaccess-groups' => "Ny mpikambana manana sata « $1 » ihany no afaka manao an'io zavatra tadiavinao atao io.",

'versionrequired'     => "
Mitaky version $1-n'i MediaWiki",
'versionrequiredtext' => "Mitaky version $1-n'i MediaWiki ny fampiasana ity pejy ity. Jereo [[Special:Version]].",

'ok'                      => 'Eka',
'pagetitle'               => '$1 - {{SITENAME}}',
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
'fileappenderror'      => "Tsy afaka ampiana amin'ny « $2 »  « $1 ».",
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
'actionthrottledtext'  => "Mba hiady amin'ny spam, ny hatetika momba ny fanaovana io otao io dia ferana ho foifoy, ary niaotra io fetra io ianao.
Andramo indray afaka minitra vitsivitsy.",
'protectedpagetext'    => 'Voaaro mba tsy hisy hanova ity pejy ity.',
'viewsourcetext'       => "Azonao atao no mijery sy mandrika ny votoatin'ity pejy ity :",
'protectedinterface'   => "Manome lahatsoratra hoan'ny rindrankajy ity pejy ity ary voaaro izy ity mba tsy hisy hanararaotra",
'editinginterface'     => "'''Tandremo :''' manova pejy ampiasan'ny lôjisialy wiki ianao. Mety hita ny mpikambana sàsany izy io. Rehefa tia mandika teny ianao, jereo ny volavola MediaWiki ho an'ny internationalisation ny hafatra [http://translatewiki.net/wiki/Main_Page?setlang=fr translatewiki.net].",
'sqlhidden'            => '(nafenina ny requête SQL)',
'cascadeprotected'     => 'Ankehitriny dia voaaro ity pejy ity satria misy pejy voaaro {{PLURAL:$1||$1}}1 mampiasa ity pejy ity. Io pejy io dia mampiasa ny fiarovana "en cascade" :

$2',
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
'logouttext'                 => "
'''Tafavoaka ianao ankehitriny.'''<br />
Afaka manohy ny fampiasana ny {{SITENAME}} ianao ka tsy mitonona anarana, ary afaka
miditra amin'ny alalan'ilay solonanarana teo na solonanarana hafa koa. 
Mariho fa misy pejy sasantsasany mety mbola hiseho foana mandra-pamafanao ny 
cache.",
'welcomecreation'            => "== Tongasoa, $1! ==

Voaforona ny kaontinao. Aza adinoina ny manova ny mombamomba anao ao amin'ny {{SITENAME}}.",
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
'loginerror'                 => "Tsy fetezana teo amin'ny fidirana",
'nocookiesnew'               => "Voaforona ny kaonty-mpikambana, fa tsy niditra ianao. Mampiasa ny cookies ny {{SITENAME}} hoan'ny fampidirana fa tsy navelanao ampiasaina ny cokkies. Ataovy mande izy, mitovy anarana sy tenimiafina.",
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
'password-name-match'        => 'Tsy maintsy samihafa ny solonanaranao sy ny tenimiafinao tompoko.',
'mailmypassword'             => 'Alefaso imailaka ny tenimiafiko',
'passwordremindertitle'      => "Fampatsiahivana tenimiafina avy amin'i {{SITENAME}}",
'passwordremindertext'       => 'Nisy olona, izay ianao ihany angamba, avy tamin\'ny adiresy IP $1, nangataka
ny handefasanay tenimiafina vaovao ho an\'ny sehatra {{SITENAME}} ao amin\'ny
$4.
Lasa "$3" ankehitriny ny tenimiafin\'i "$2"
Afaka miditra ary ianao ankehitriny ary manova ny tenimiafinao.
Lany andro anatin\'ny $5 andro ny tenimiafinao

Raha olon-kafa io nangataka io, na tadidinao ihany ny tenimiafinao taloha ka
tsy irinao hovana intsony, dia fafao fotsiny ity hafatra ity dia ilay 
tenimiafina taloha ihany no ampiasao.',
'noemail'                    => 'Tsy nanome adiresy imailaka i "$1".',
'passwordsent'               => 'Nandefasana tenimiafina vaovao any amin\'ny adiresy imailak\'i "$1".
Azafady midira rehefa voarainao io imailaka io.',
'blocked-mailpassword'       => "Voasakana ny adiresy IP-nao, nesorina aminao ny asa ''password recovery'' mba tsy hanararaotra.",
'eauthentsent'               => "
Efa nandefasana imailaka fanamarinana ilay adiresy nomenao.
Alohan'ny handraisanao imailaka hafa, dia araho ny torolalana ao anatin'io imailaka io,
mba hanaporofoana fa anao io kaonty io.",
'throttled-mailpassword'     => "Nandefa imailaka mety mampatadidy anao ny tenimiafinao izahay nandrintra ny $1 ora farany. Mba tsy hanararaotra, imailaka iray ihany no azo alefa isakin'ny ady ny $1",
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
'login-throttled'            => "In-betsaka laotra ianao no nanandrana tenimiafina teo amin'io kaonty io.
Andraso kely ary andramo indray.",
'loginlanguagelabel'         => 'fiteny : $1',

# Password reset dialog
'resetpass'                 => 'Hanova ny tenimiafina',
'resetpass_announce'        => "Nihiditra tenimiafina mailaka nalefanay tamin'ny imailaka ianao. Ampidiro ity tenimiafina ity mba hanapitra ny fampidirana.",
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
'missingsummary'                   => "'''Fampandrenesana''' : Tsy nameno ny ambangovangony ianao.
ra tonga dia tsindrinao ilay bokotra « tehirizo », alefa avy hatrany ny fanovànao",
'missingcommenttext'               => 'Ampidiro ny ambangovangony azafady.',
'missingcommentheader'             => "'''Fampahatsiahivana :''' Tsy nampiditra lohateny amin'ity resaka ity ianao.
Raha tsindrianao indray eo amin'ny « {{MediaWiki:Savearticle}} » ho voatahiry tsy misy lohateny ny fanovananao.",
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
'confirmedittext'                  => "Tsy maintsy Checke-na ny adiresy imailakao aloha no manova pejy.
Ampidiro sy Checkeo ny adiresy imailakao amin'ny [[special:preferences|safidinao]].",
'nosuchsectiontitle'               => 'Ts ampy fitapahana',
'nosuchsectiontext'                => 'Nanandrana nanova fizaràna tsy misy ianao.
Rehefa tsy misy fizaràna $1, tsy misy toerana hitahiry ny fanovànao.',
'loginreqtitle'                    => 'Mila fidirana',
'loginreqlink'                     => 'hiditra',
'loginreqpagetext'                 => 'Tokony $1 ianao raha te hijery pejy hafa.',
'accmailtitle'                     => 'Lasa ny tenimiafina.',
'accmailtext'                      => 'Lasa any amin\'i $2 ny tenimiafin\'i "$1".',
'newarticle'                       => '(Vaovao)',
'newarticletext'                   => "Mbola tsy misy ity takelaka ity koa azonao atao ny mamorona azy eto ambany. Jereo ny [[{{MediaWiki:Helppage}}|Fanoroana]] raha misy fanazavana ilainao.

Raha toa moa ka tsy nieritreritra ny hamorona ity takelaka ity ianao dia miverena etsy amin'ny fandraisana.",
'anontalkpagetext'                 => "----''Eto no sehatra iresahana ho an'ny mpikambana tsy mitonona anarana, izany hoe tsy nisoratra anarana na tsy mampiasa ny solonanarany. Voatery noho izany isika mampiasa ny adiresy IP ho marika hanondroana azy. Io adiresy IP io dia mety hikambanana amin'ny olona maro hafa. Raha tsy mitonona anarana ianao ary mahatsapa fa misy fanamarihana tsy miantefa aminao loatra voarainao, dia iangaviana ianao mba [[Special:UserLogin|hisoratra anarana ho mpikambana na hiditra]] mba tsy hifangaroanao amin'ny mpikambana tsy mitonona anarana hafa intsony.''",
'noarticletext'                    => "'''Tsy mbola nisy namorona io lahatsoratra io.
Azonao atao ny [[Special:Search/{{PAGENAME}}||Tadiavo ny momba ny {{PAGENAME}}]].'''
* '''[{{SERVER}}{{localurl:{{NAMESPACE}}:{{PAGENAME}}|action=edit}} Na forony eto ny lahatsoratra momba ny {{PAGENAME}}]'''.",
'clearyourcache'                   => "'''Fanamarihana:''' Aorian'ny fanovana, dia mila mamafa ny cache ianao vao mahita ny fiovana.
'''Mozilla / Firefox / Safari:''' Tsindrio ny ''Shift'' rehefa manindry ''Reload'', na tsindrio ''Ctrl-Shift-R'' (''Cmd-Shift-R'' ho an'ny Apple Mac); '''IE:''' tsindrio ''Ctrl'' rehefa manindry ''Refresh'', na tsindrio ''Ctrl-F5''; '''Konqueror:''': tsindrio fotsiny ny bokotra ''Reload'' na ''F5''; ny mpampiasa '''Opera''' angamba dia tokony hamafa ny cache-ny ao amin'ny ''Tools&rarr;Preferences''.",
'usercssyoucanpreview'             => "'''Fika:''' Ampiasao ny bokotra 'Tsipalotra' mialoha ny hitehirizanao ny CSS-nao vaovao.",
'userjsyoucanpreview'              => "'''Fika:''' Ampiasao ny bokotra 'Tsipalotra' mialoha ny hitehirizanao ny JS-nao vaovao.",
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
'semiprotectedpagewarning'         => "'''Naoty''' : Voaaro ity pejy ity, ny mpikambana nanokatra kaonty tato ihany no afaka manova azy.",
'titleprotectedwarning'            => "'''TANDREMO''' : Ny mpikambana manana [[Special:ListGroupRights|alàlana manokana]] ihany no afaka manova ity pejy ity.",
'templatesused'                    => "endrika miasa eto amin'ity pejy ity:",
'templatesusedpreview'             => "endrika miasa anatin'ity topi-maso ity :",
'templatesusedsection'             => "Endrika miasa anatin'ity fizaràna ity :",
'template-protected'               => '(voaaro)',
'template-semiprotected'           => '(voaaro an-tàpany)',
'hiddencategories'                 => '{{PLURAL:$1|anaty sokajy|anaty sokajy}} nasitrika $1 ity pejy ity',
'nocreatetitle'                    => 'Voafetra ny famoronana pejy',
'nocreatetext'                     => " Voafetra ihany ny fahafahana mamorona pejy eto amin'ity sehatra ity.  Ny pejy efa misy no azonao ovaina, na [[Special:UserLogin|midira na mamoròna kaonty]].",
'nocreate-loggedin'                => 'Tsy mahazo ataonao no manamboatra pejy vao.',
'permissionserrors'                => 'Tsy azonao atao',
'permissionserrorstext'            => 'Tsy afaka manao ilay asa nanontanianao ianao satria :',
'permissionserrorstext-withaction' => '{{PLURAL:$1|Tsy manana alalàna ianao|Tsy manana alalàna ianao}} $2. Io ny antony ($2):',
'log-fulllog'                      => "Hijery ny tantaran'asa (log)",
'edit-hook-aborted'                => 'Tsy nety ny fanovàna
Tsy nanome antony',
'edit-conflict'                    => 'Adi-panovàna.',

# Parser/template warnings
'post-expand-template-inclusion-category' => 'Pejy be be endrika',
'parser-template-loop-warning'            => 'endrika vono hita tao : [[$1]]',

# "Undo" feature
'undo-success' => 'Ho voafafa io fanovana io. Marino tsara ny fanovana eo ambany, ary tehirizo rehefa vita.',
'undo-failure' => "Tsy afaka esorina io fanovàna io : mety tsy miraikitra amin'ny fanovàna misy eo ampivoaniny ra esorina",
'undo-norev'   => 'Tsy afaka nesorina ilay fanovàna satria tsy misy na efa voafafa izy.',
'undo-summary' => "Niala ny fanovàna $1 nataon'i [[Special:Contributions/$2|$2]] ([[User talk:$2|resaho]])",

# Account creation failure
'cantcreateaccounttitle' => 'Tsy afaka manokatra kaonty ianao.',
'cantcreateaccount-text' => "Voasakan'i [[User:$3|$3]] ny fanokafana kaonty avy amin'ity adiresy IP (<b>$1</b>)

''$2'' ny antony.",

# History pages
'viewpagelogs'           => "Hijery ny fanovan'ity pejy ity",
'nohistory'              => 'Tsy manana tantaram-panovana io pejy io.',
'currentrev'             => 'Votoatiny ankehitriny',
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
'histfirst'              => 'Ny vao indrindra',
'histlast'               => 'Antintra indrindra',
'historysize'            => '($1 {{PLURAL:$1|oktety|oktety}})',
'historyempty'           => '(tsy misy)',

# Revision feed
'history-feed-title'       => 'Tantara ny fanovàna',
'history-feed-description' => "Tantaran'ity pejy ity teto amin'ity wiki ity.",
'history-feed-empty'       => "Tsy misy ny pejy notadiavina.
Mety efa voafafa na voafindra angamba izy.
Mitadiava amin'ny '''[[Special:Search|fiasàna fitadiavina]]''' mba hitady ny pejy misy fifandraisana.",

# Revision deletion
'rev-deleted-comment'         => '(hafatra nesorina)',
'rev-deleted-user'            => '(solonanarana nesorina)',
'rev-deleted-event'           => '(nesorina ny fampidirana)',
'rev-deleted-text-permission' => "'''Voafafa''' ny version ny ity pejy ity.
Mety any amin'ny [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} tantaran'asa ny famafàna pejy] ny antsipirihany.",
'rev-deleted-no-diff'         => "Tsy afaka mijery anio diff io ianao satria misy version '''voafafa''' ao.
Mety any amin'ny [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} tantaran'asa ny famafàna pejy] ny antsipirihany.",
'rev-delundel'                => 'jereo/asitrio',
'revisiondelete'              => 'Hamafa/hamerina revision',
'revdelete-nooldid-title'     => 'tsy izy ny version tanjona',
'revdelete-nologtype-title'   => "Tsy misy tantaran'asa nampidirana/nomena",
'revdelete-nologtype-text'    => "Tsy nosafidianao akory ny tantaran'asa anaovana io tao io ianao",
'revdelete-nologid-title'     => "Misy diso ny tantaran'asa nampidiranao",
'revdelete-nologid-text'      => "Tsy nosafidianao akory ny tantaran'asa anaovana io tao io ianao, na tsy misy ilay zavatra notenenina",
'revdelete-no-file'           => 'Tsy misy ilay rakitra hofafàna.',
'revdelete-show-file-confirm' => "Tapa-kevitra hamafa ny ''revision''-n'i rakitra <nowiki>$1</nowiki> tamin'ny $2 tamin'ny $3 ve ianao ?",
'revdelete-show-file-submit'  => 'Eny',
'revdelete-selected'          => "'''{{PLURAL:$2|Votoatiny nosafidiana|Votoatiny nosafidiana}}n'i '''[[:$1]]''' :'''",
'revdelete-text'              => "'''Mbola ao amin'ny tantaran'asa ny version voafafa, fa tsy afaka jeren'ny vahoaka ny lahatsoratra ao aminy.'''

Afaka mijery ny lahatsoratra nasitrika sy mamerina azy ny mpandrindra hafa ny {{SITENAME}} amin'ny alalan'ity interface ity, raha tsy misy restriction hafa koa. Marino tsara fa io no zavatra tianao hatao, raha fantatrao ny vokany ary ra ataonao [[{{MediaWiki:Policy-url}}|ara-pitsipika]] io.",
'revdelete-suppress-text'     => "Ny famafàna pejy dia ampiasaina rehefa :
* Misy information tsy sarababem-bahoaka tsy metimety
*: ''Misy adiresy nomeraona antso an-tariby, nomeraona sécurité sociale, sns.''",
'revdelete-legend'            => 'Ampetraho ny restriction nà fijerena :',
'revdelete-hide-text'         => "Asitrio ny lahatsoratr'ity version ity",
'revdelete-hide-name'         => 'Asitrio ny asa sy ny tarigehitra',
'revdelete-hide-comment'      => 'asitrio ny ambangovangony',
'revdelete-hide-user'         => "Asitrio ny solonanaran'ny mpikambana/adiresy IP",
'revdelete-hide-restricted'   => "Fafao ireo votoatiny ireo amin'ny mpiandrindra sy amin'ny mpikambana afa",
'revdelete-suppress'          => "Asitrio ny votoatiny hoan'ny mpandrindra",
'revdelete-hide-image'        => "asitrio ny votoatin'ilay rakitra",
'revdelete-log'               => "Anbangovangon'ny tantaran'asa :",
'revdelete-submit'            => "Ataovy amin'ny version nosafidiako",
'revdelete-logentry'          => "nanova ny fahitan'ny/i [[$1]]",
'logdelete-logentry'          => "nanova ny fahitan'ny zava-mitranga ny/n'i [[$1]]",
'revdelete-success'           => "'''Voaova soa aman-tsara ny fahitàn'ny version.'''",
'logdelete-success'           => "'''Voaova soa aman-tsara ny fahitan-ny tantaran'asa.'''",
'revdel-restore'              => 'Ovay ny fahitàna',
'pagehist'                    => "Tantaran'ilay pejy",
'deletedhist'                 => 'Tantara voafafa',
'revdelete-content'           => 'votoatiny',
'revdelete-summary'           => "ambangovangon'ny fanovàna",
'revdelete-uname'             => 'anarana mpikambana',
'revdelete-restricted'        => "nanometraka fanerena hoan'ny mpandrindra",
'revdelete-unrestricted'      => "fanerena nesorina tamin'ny mpandrindra",
'revdelete-hid'               => 'nanitrika ny $1',
'revdelete-unhid'             => "nanala fanitriana (hoan'(ny)) $1",
'revdelete-log-message'       => "$1 hoan'ny $2",
'logdelete-log-message'       => "zava-miseho $1 amin'ny $2",
'revdelete-otherreason'       => 'Antony hafa / antony miampy :',
'revdelete-reasonotherlist'   => 'Antony hafa',
'revdelete-edit-reasonlist'   => "Hanova ny anton'ny famafàna",

# Suppression log
'suppressionlog'     => "tantaran'asa ny famafàna pejy",
'suppressionlogtext' => "Ity ny lisitra ny famafàna sy ny fanakanana asitrika amin'ny mpandrindra.
Hijery ny [[Special:IPBlockList|lisitra ny adiresy IP sy mpikambana voasakana]] ho an'ny lisitra ny voaraoka sy ny fanakanana mbola miasa.",

# History merging
'mergehistory'                     => 'Atsonika ny tantara ny pejy',
'mergehistory-box'                 => 'Atsonika ny version ny pejy roa :',
'mergehistory-from'                => 'Pejy fiavina :',
'mergehistory-into'                => 'pejin-dresaka :',
'mergehistory-list'                => "tantaran'asa ny fanovàna mety hatambatra",
'mergehistory-go'                  => 'Hijery ny fanovàna mety hatsonika',
'mergehistory-submit'              => 'atsonika ny version',
'mergehistory-empty'               => 'tsy misy version azo hatambarana',
'mergehistory-fail'                => "Tsy afaka manatambatra ny tantara(n'asa). Avereno checheo ny pejy sy ny daty.",
'mergehistory-no-source'           => "Tsy misy ny pejy avy amin'ny $1.",
'mergehistory-no-destination'      => 'Tsy misy ilay pejy tanjona $1.',
'mergehistory-invalid-source'      => 'Tokony manana lohateny azo ampiasaina ny pejy fiavina',
'mergehistory-invalid-destination' => 'Tsy maintsy manana lohateny azo ampiasaina ny pejy tanjona.',
'mergehistory-autocomment'         => "natambatra miarak'amin'ny [[:$2]]  [[:$1]]",
'mergehistory-comment'             => "natambatra miarak'amin'ny [[:$2]] ny/i [[:$1]] : $3",
'mergehistory-reason'              => 'Antony :',

# Merge log
'mergelog'           => "tantaran'asa ny fitambarana",
'pagemerge-logentry' => "voatambatra tamin'ny [[$2]] [[$1]] (fanovàna hatramin'ny $3)",
'revertmerge'        => 'Saraho',

# Diffs
'history-title'           => "Tantara ny endrik'i « $1 »",
'difference'              => "(Fahasamihafan'ny pejy)",
'lineno'                  => 'Andalana $1:',
'compareselectedversions' => 'Ampitahao ireo version voafidy',
'visualcomparison'        => 'fampitahana ara-maso',
'wikicodecomparison'      => "fampitahan'ny Wikitext",
'editundo'                => 'esory',
'diff-movedto'            => "novàna toerana tany amin'ny $1",
'diff-styleadded'         => 'fomba $1 nasiana',
'diff-added'              => 'nasiana $1',
'diff-changedto'          => 'novàna ho $1',
'diff-movedoutof'         => "nakisaka any ivelan'i $1",
'diff-styleremoved'       => 'nesorina ny fomba $1',
'diff-removed'            => 'nesorina/niala $1',
'diff-changedfrom'        => "novaina hatramin'ny $1",
'diff-src'                => 'fango/fiavina',
'diff-width'              => 'halalaka',
'diff-height'             => 'haabo',
'diff-blockquote'         => "'''fitanisàna'''",
'diff-h1'                 => "'''lohateny (sokajy faha 1)'''",
'diff-h2'                 => "'''lohateny (sokajy faha 2)'''",
'diff-h3'                 => "'''lohateny (sokajy faha 3)'''",
'diff-h4'                 => "'''lohateny (sokajy faha 4)'''",
'diff-h5'                 => "'''lohateny (sokajy faha 5)'''",
'diff-div'                => "'''fizaràna'''",
'diff-ul'                 => "'''lisitra tsy nalaminana'''",
'diff-ol'                 => "'''lisitra milamina'''",
'diff-table'              => "'''tabilao'''",
'diff-tr'                 => "'''andalan-dohavy'''",
'diff-td'                 => "'''sela'''",
'diff-th'                 => "'''andoha'''",
'diff-br'                 => "'''fanapahana'''",
'diff-input'              => "'''fampidiran-tsoratra'''",
'diff-form'               => "'''fomulaire'''",
'diff-img'                => "'''sary'''",
'diff-a'                  => "'''rohy'''",
'diff-i'                  => "'''mandry'''",
'diff-b'                  => "'''matavy'''",

# Search results
'searchresults'                    => 'Valim-pikarohana',
'searchresults-title'              => "Valim-pikarohana hoan'ny « $1 »",
'searchresulttext'                 => "Jereo ny [[{{MediaWiki:Helppage}}|fanazavana fanampiny momba ny fikarohana eto amin'ny {{SITENAME}}]].",
'searchsubtitle'                   => "nitady lohatsoratra « '''[[:$1]]''' » ianao ([[Special:Prefixindex/$1|ny pejy rehetra manomboka amin'ny « $1 »]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|ny pejy rehetra manana rohy amin'ny « $1 »]])",
'searchsubtitleinvalid'            => "Nitady « '''$1''' » ianao",
'noexactmatch'                     => "'''Tsy misy pejy manana lohateny « $1 » ato.''' Afaka [[:$1|Amboarinao io pejy io]].",
'noexactmatch-nocreate'            => "''Tsy misy pejy milohateny « $1 ».'''",
'titlematches'                     => "Mifanitsy amin'ny lohatenin'ny lahatsoratra",
'notitlematches'                   => 'Tsy nahitana lohateny mifanaraka',
'textmatches'                      => "Mifanitsy amin'ny votoatin'ny pejy",
'notextmatches'                    => 'Tsy nahitana votoatim-pejy mifanaraka',
'prevn'                            => '{{PLURAL:$1|$1}} taloha',
'nextn'                            => '{{PLURAL:$1|$1}} manaraka',
'prevn-title'                      => 'Valim-pikarohana taloha $1',
'nextn-title'                      => 'Valim-pikarohana manaraka $1',
'shown-title'                      => 'Aseho valiny $1 isaky ny pejy iray',
'viewprevnext'                     => 'Hijery ($1) ($2) ($3).',
'searchmenu-legend'                => 'Safidy mikasika ny fitadiavana',
'searchhelp-url'                   => 'Help:Fanoroana',
'searchprofile-articles'           => 'Pejy misy votoatiny',
'searchprofile-project'            => 'Pejy fanampiana sy pejy tetikasa',
'searchprofile-images'             => 'Multimedia',
'searchprofile-everything'         => 'Izy Rehetra',
'searchprofile-advanced'           => 'Fikarohana antsipirihany',
'searchprofile-articles-tooltip'   => "Hikaroka ao amin'ny $1",
'searchprofile-project-tooltip'    => "Hikaroka ao amin'ny $1",
'searchprofile-images-tooltip'     => 'Hikaroka rakitra multimedia',
'searchprofile-everything-tooltip' => "Hitady eraky ny tranonkala (miaraka amin'ny pejin-dresaka)",
'searchprofile-advanced-tooltip'   => "Hitady ny toeran-anarana ho an'ny fikarohana",
'search-result-size'               => '$1 ({{PLURAL:$2|teny|teny}} $2)',
'search-redirect'                  => "(redirect avy amin'ny/amin'i $1)",
'search-section'                   => '(fizaràna $1)',
'search-suggest'                   => 'Andramo : $1',
'search-interwiki-caption'         => 'zandri-tetikasa',
'search-interwiki-default'         => "Valiny amin'ny $1 :",
'search-interwiki-more'            => '(be kokoa)',
'search-mwsuggest-enabled'         => 'misy hevitra',
'search-mwsuggest-disabled'        => 'tsy misy hevitra',
'search-relatedarticle'            => 'voadinika',
'mwsuggest-disable'                => 'Aza atao ny toro-hevitra AJAX',
'searchrelated'                    => 'voadinika',
'searchall'                        => 'rehetra',
'showingresults'                   => "Omeo ny valiny miisa hatramin'ny <b>$1</b> manomboka ny #<b>$2</b>.",
'showingresultsnum'                => 'Omeo ny valiny miisa <b>$3</b> manomboka ny #<b>$2</b>.',
'nonefound'                        => "'''Fanamarihana''': ny mahatonga ny fikarohana tsy hahita vokany matetika dia ny 
fampiasanao teny miasa matetika toy ny \"izay\" sy ny \"tsy\",
na ny fanomezanao teny mihoatra ny iray (ny pejy ahitana ny teny rehetra hokarohina
ihany no miseho amin'ny vokatry ny karoka).",
'powersearch'                      => 'Fitadiavana',
'powersearch-legend'               => 'Fikarohana havanana',
'powersearch-ns'                   => "Hitady anatin'ny toeran-anarana :",
'powersearch-redir'                => 'Ampiseho ny redirect',
'powersearch-field'                => 'Hitady',
'search-external'                  => 'Hikaroka any ivelany',
'searchdisabled'                   => "Tsy nalefa ny karoka eto amin'i {{SITENAME}}. Afaka mampiasa an'i Google aloha ianao mandra-paha. Nefa fantaro fa mety ho efa lany daty ny valiny omeny.",

# Quickbar
'qbsettings'               => 'Tsipika fiasàna',
'qbsettings-none'          => 'Tsy misy',
'qbsettings-fixedleft'     => 'Ankavia',
'qbsettings-fixedright'    => 'Ankavanana',
'qbsettings-floatingleft'  => 'Mitsingevaheva any ankavanana',
'qbsettings-floatingright' => 'Mitsigevaheva any ankavanana',

# Preferences page
'preferences'               => 'Ny momba anao',
'mypreferences'             => 'Ny safidiko',
'prefs-edits'               => 'isa ny fanovàna :',
'prefsnologin'              => 'Tsy tafiditra',
'prefsnologintext'          => '[[Special:UserLogin|Midira]] aloha izay vao manova ny mombamomba anao.',
'changepassword'            => 'Hanova tenimiafina',
'prefs-skin'                => 'Endrika',
'skin-preview'              => 'Tsipalotra',
'datedefault'               => 'Tsy misy safidy',
'prefs-datetime'            => 'Daty sy ora',
'prefs-personal'            => 'Mombamomba anao',
'prefs-rc'                  => 'Vao niova',
'prefs-watchlist'           => 'Lisitry ny pejy arahana-maso',
'prefs-watchlist-days'      => "Isa ny andro haseho anatin'ny lisitra ny pejy arahana-maso",
'prefs-watchlist-days-max'  => '(7 andro fara-fahabetsany)',
'prefs-watchlist-edits'     => "Isa ny fanovana aseho eo amin'ny fanaraha-maso navelatra:",
'prefs-watchlist-edits-max' => '(isa fara-fahabetsany : 1000)',
'prefs-misc'                => 'Hafa',
'prefs-resetpass'           => 'Hanova tenimiafina',
'prefs-email'               => 'Option ny imailaka',
'prefs-rendering'           => 'Fampisehoana',
'saveprefs'                 => 'Tehirizo',
'resetprefs'                => 'Avereno',
'restoreprefs'              => 'Averina ny reglages taloha',
'prefs-editing'             => 'Fanovana',
'prefs-edit-boxsize'        => 'Hangeza ny varavarankely fanovàna',
'columns'                   => 'Tsanganana/Tioba :',
'searchresultshead'         => 'Fikarohana',
'resultsperpage'            => "Isa ny valiny isakin'ny pejy :",
'contextlines'              => "Isa ny andalana isakin'ny valiny :",
'contextchars'              => 'Isa ny litera isaky ny andalana',
'recentchangesdays'         => "Isa ny andro ho ampiseho eo amin'ny fanovàna farany",
'recentchangescount'        => "Lohateny amin'ny vao niova:",
'savedprefs'                => 'Voatahiry ny mombamomba anao.',
'timezonelegend'            => "Faritr'ora :",
'localtime'                 => 'Ora an-toerana',
'timezoneuseserverdefault'  => "Ampiasaina ny tenenin'i Mpikajy",
'servertime'                => "Fahelan'ny lohamilina",
'guesstimezone'             => "
Fenoy araka ny datin'ny solosainan'ny mpitsidika",
'timezoneregion-africa'     => 'Afrika',
'timezoneregion-america'    => 'Amerika',
'timezoneregion-antarctica' => 'Antarktika',
'timezoneregion-arctic'     => 'Arktika',
'timezoneregion-asia'       => 'Azia',
'timezoneregion-atlantic'   => 'Ranomasimbe Atlantika',
'timezoneregion-australia'  => 'Aostralia',
'timezoneregion-europe'     => 'Eoropa',
'timezoneregion-indian'     => 'Ranomasimbe Indianina',
'timezoneregion-pacific'    => 'Ranomasimbe Pasifika',
'allowemail'                => "Ekeo ny handraisana imailaka avy amin'ny mpikambana hafa",
'prefs-searchoptions'       => 'Option ny fikarohana',
'prefs-namespaces'          => "Toeran'anarana",
'default'                   => 'tsipalotra',
'prefs-files'               => 'Rakitra',
'prefs-custom-css'          => 'CSS manokana',
'prefs-custom-js'           => 'Javascript manokana',
'youremail'                 => 'Imailaka:',
'username'                  => 'Solonanarana:',
'uid'                       => "Laharan'ny mpikambana:",
'prefs-memberingroups'      => "mpikamban'ny gropy :",
'yourrealname'              => 'Tena anarana marina:',
'yourlanguage'              => 'Tenim-pirenena:',
'yournick'                  => 'Anaram-bositra:',
'badsig'                    => 'Tsy mety io sonia io; hamarino ny kialo HTML.',
'badsiglength'              => "Lava laotra ny sonianao.
Tokony mba manana lohavy ambanimbany kokoa non'ny $1",
'yourgender'                => 'lahy/vavy :',
'gender-unknown'            => 'Tsy voalaza',
'gender-male'               => 'Lehilahy',
'gender-female'             => 'Vehivavy',
'prefs-help-gender'         => "Ankifidy : Ampiasaina mba hifanaraka amin'ny lahi-vavy. Ho sarababem-bahoaka io fampandrenesana io.",
'email'                     => 'Imailaka',
'prefs-help-realname'       => "Anarana marina (afaka tsy fenoina): raha fenoinao ity dia hampiasaina hanomezana anao tambin'ny asa izay efainao eto.",
'prefs-help-email'          => "Imailaka (afaka tsy asiana): Hahafahan'ny olona mifandray aminao amin'ny alalan'ny pejinao manokana na ny pejin-dresakao, nefa tsy aseho azy ny anaranao.",
'prefs-help-email-required' => 'Ilaina ny adiresy imailaka',
'prefs-signature'           => 'Sonia',
'prefs-dateformat'          => 'Endriky ny daty',
'prefs-diffs'               => 'Diff',

# User rights
'userrights'                  => 'Fandrindràna ny fahazoan-dàlana',
'userrights-lookup-user'      => 'Handrindra vondrom-pikambana',
'userrights-user-editname'    => 'Manomeza solonanarana:',
'userrights-editusergroup'    => 'Hanova vondrom-pikambana',
'saveusergroups'              => 'Tehirizo ny vondrom-pikambana',
'userrights-groupsmember'     => "Mpikambana amin'ny vondrona:",
'userrights-changeable-col'   => 'Ny gropy azonao ovaina',
'userrights-unchangeable-col' => 'Ny gropy tsy azonao ovaina',

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
'right-read'                 => 'Mamaky ny pejy',
'right-edit'                 => 'Manova ny pejy',
'right-createpage'           => 'Manamboatra pejy (tsy pejin-dresaka)',
'right-createtalk'           => 'manamboatra pejin-dresaka',
'right-createaccount'        => 'Manamboatra kaonty',
'right-minoredit'            => 'Marihana ho fanovana madinika',
'right-move'                 => 'Manakisaka pejy',
'right-move-subpages'        => "Manakisaka pejy miarak'amin'ny zana-pejiny",
'right-move-rootuserpages'   => 'Hamindra ny renipejina mpikambana iray',
'right-movefile'             => 'Hanova anarana rakitra',
'right-suppressredirect'     => "Afaka tsy manometraka redirect avy amin'ny lohateny fiavina",
'right-upload'               => 'Hampiditra rakitra',
'right-reupload'             => '"Hanitsaka" rakitra efa misy',
'right-reupload-own'         => '"Hanitsaka" rakitra nampidiriko',
'right-upload_by_url'        => 'Hampiditra rakitra avy amina adiresy URL',
'right-autoconfirmed'        => 'Manova pejy voaaro an-tapaka',
'right-bot'                  => 'Atao hita otra ny fizorana mande hoazy',
'right-nominornewtalk'       => "Tsy alefa ny fampandrenesana ''hafatra vaovao'' rehefa manao fanovana kely ao anatin'ny pejin-dresan'ny mpikambana.",
'right-delete'               => 'Mamafa pejy',
'right-bigdelete'            => 'Hamafa ny pejy manana tantara be',
'right-deleterevision'       => 'Mamafa ny version manokana-na pejy iray',
'right-deletedhistory'       => 'Mijery ny tantaram-pejy voafafa fa tsy lahatsorany',
'right-browsearchive'        => 'Mitady pejy voafafa',
'right-undelete'             => 'Mamerina pejy voafafa',
'right-suppressionlog'       => 'Mijery ny tao tsy sarababem-bahoaka',
'right-block'                => 'Manakana ny mpikambana mba tsy hanova',
'right-blockemail'           => 'Manakana ny mpikambana mba tsy handefa imailaka',
'right-hideuser'             => "Manakana ny mpikambana amin'ny alàlan'ny fanitrihana ny anarana",
'right-ipblock-exempt'       => 'Tsy afaka sakanana IP voasakana ny IP-ny',
'right-protect'              => "Manova ny fiarovan'ny pejy sy manova ny pejy voaaro",
'right-editprotected'        => 'Manova ny pejy voaaro (tsy misy fiarovana en cascade)',
'right-editinterface'        => 'Manova ny interface ny mpikambana',
'right-editusercssjs'        => 'Manova ny rakitra CSS sy JS ny mpikambana hafa',
'right-editusercss'          => 'Manova ny rakitra CSS ny mpikambana hafa',
'right-edituserjs'           => "Manova ny rakitra JS an'ny mpikambana hafa",
'right-noratelimit'          => 'Tsy voafetra ny isa',
'right-import'               => "Mampiditra na manafatra pejy avy amin'ny wiki hafa",
'right-importupload'         => 'mampiditra na manafatra pejy avy amina rakitra iray',
'right-patrol'               => "Manamarina ny fanovan'ny hafa",
'right-trackback'            => 'Manampy trackback',
'right-mergehistory'         => 'Manatsonika ny tantaram-pejy',
'right-userrights'           => "Manova ny fahefan'ny mpikambana",
'right-userrights-interwiki' => "Manova ny fahefan'ny mpikambana any amin'ny wiki hafa",
'right-siteadmin'            => 'Manidy sy manokatra ny database',
'right-reset-passwords'      => "Manova ny tenimiafin'ny mpikambana hafa",
'right-versiondetail'        => "Mijery ny fampahalalàna momba ny version'ny rindrankajy",

# User rights log
'rightslog'      => "Tantaran'asa ny fanovàna sata ny mpikambana",
'rightslogtext'  => "Tantaran'asa momba ny fahazoan-dàlan'ny mpikambana.",
'rightslogentry' => "nanova ny fahefan'ny mpikambana « $1 », avy amin'ny $2 izy lasa $3",
'rightsnone'     => '(tsy misy)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'               => 'hamaky io pejy io',
'action-edit'               => 'ovay ity pejy ity',
'action-createpage'         => 'hanao pejy',
'action-createtalk'         => 'hanao pejin-dresaka',
'action-createaccount'      => 'amboary io kaontim-pikambana io',
'action-move'               => 'hamindra io pejy io',
'action-move-subpages'      => 'hamindra io pejy io sy ny zanapejiny',
'action-move-rootuserpages' => "hanolo anaran'ny pejin'ny mpikambana",
'action-movefile'           => "manova anaran'ny rakitra iray",
'action-upload'             => 'hampiditra io rakitra io',
'action-reupload'           => 'hanitsaka io rakitra efa misy io',
'action-upload_by_url'      => 'hampiditra io rakitra io avy amina adiresy URL',
'action-writeapi'           => 'hanova ny API fanoratana',
'action-delete'             => 'hamafa io pejy io',
'action-deleterevision'     => 'hamafa io version io',
'action-browsearchive'      => 'hitady pejy efa voafafa',
'action-undelete'           => 'hamerina io pejy io',
'action-suppressrevision'   => 'hijery sy hamerina io version nofafàna io',
'action-suppressionlog'     => 'hijery io tao tsy sarababem-bahoaka',

# Recent changes
'nchanges'                          => '{{PLURAL:$1|fanovana|fanovana}} $1',
'recentchanges'                     => 'Fanovana farany',
'recentchanges-legend'              => 'Safidy ny fanovàna farany',
'recentchangestext'                 => "Jereo eto amin'ity pejy ity izay vao niova vao haingana teto amin'ity wiki ity.",
'recentchanges-feed-description'    => "Arao ny fanovàna farany amin'ity wiki ity anaty topa",
'rcnote'                            => "!Ity ny {{PLURAL:$1|fanovàna farany|fanovàna farany}} $1 natao nandritra ny <b>$2</b> andro, hatramin'ny $4 tamin'ny ora faha $5.",
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
'recentchangeslinked'         => 'Novaina',
'recentchangeslinked-feed'    => 'Novaina',
'recentchangeslinked-toolbox' => 'Novaina',
'recentchangeslinked-title'   => "Fanaraha-maso ny pejy miarak'amin'ny « $1 »",
'recentchangeslinked-summary' => "Mampiseho ny fanovàna vao haingana ity pejy manokana ity. Voasoratra amin'ny '''sora-matavy''' ny lohaten'ny [[Special:Watchlist|pejy arahinao-maso]].",
'recentchangeslinked-page'    => 'anaram-pejy :',
'recentchangeslinked-to'      => "Ampisehoy ny fanovàn'ny pejy misy rohy makany amin'ny pejy fa tsy atao mivadika",

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

'upload-proto-error'  => 'Protokolina diso',
'upload-file-error'   => 'Tsy fetezana anatiny',
'upload-unknown-size' => 'tsy fantatra ny hangeza',

'nolicense' => 'Tsy misy safidy',

# Special:ListFiles
'listfiles'             => 'Lisitry ny rakitra',
'listfiles_date'        => 'Daty',
'listfiles_name'        => 'Anarana',
'listfiles_user'        => 'Mpikambana',
'listfiles_size'        => 'Hangeza',
'listfiles_description' => 'Fanoritana',
'listfiles_count'       => 'Version',

# File description page
'file-anchor-link'          => 'Rakitra',
'filehist'                  => 'Tantara ny rakitra',
'filehist-help'             => "Tsindrio eo amin'ny daty/ora iray mba hijery ny toetra n'ilay rakitra tamin'io fotoana io.",
'filehist-deleteall'        => 'fafao daholo',
'filehist-deleteone'        => 'hamafa',
'filehist-revert'           => 'hamerina',
'filehist-current'          => 'ankehitriny',
'filehist-datetime'         => 'Daty sy ora',
'filehist-thumb'            => 'saritapaka',
'filehist-thumbtext'        => "Vignette hoan'ny $1",
'filehist-user'             => 'Mpikambana',
'filehist-dimensions'       => 'Hangeza',
'filehist-comment'          => 'resaka',
'filehist-missing'          => 'Tsy ampy rakitra',
'imagelinks'                => "Fampiasan'io rakitra io",
'linkstoimage'              => "Ireto avy no {{PLURAL:$1|pejy mirohy|pejy mirohy}} ($1) amin'io rakitra io:",
'nolinkstoimage'            => "Tsy misy pejy mirohy amin'ity sary ity.",
'sharedupload'              => "Mety ho rakitra itambarana amin'ny tetikasa hafa ny rakitra $1.",
'uploadnewversion-linktext' => "Andefa version vaovao n'ity rakitra ity",
'shared-repo-from'          => "avy amin'ny $1",

# File reversion
'filerevert'        => "Hamerinan'i $1",
'filerevert-legend' => 'Hamerina ny $1',

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
'brokenredirects-edit'   => 'ovao',
'brokenredirects-delete' => 'fafao',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|oktety|oktety}}',
'ncategories'             => '{{PLURAL:$1|vondrona|vondrona}} $1',
'nlinks'                  => '{{PLURAL:$1|rohy|rohy}} $1',
'nmembers'                => '{{PLURAL:$1|mpikambana|mpikambana}} $1',
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
'pager-newer-n'           => '$1 {{PLURAL:$1|vao haingana|vao haingana}}',
'pager-older-n'           => '$1 {{PLURAL:$1|taloha|taloha}}',

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
{{fullurl:{{#special:Watchlist}}/edit}}

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
'editcomment'    => "Toy izao no fanamarihana momba io fanovana io: \"''\$1''\".",
'revertpage'     => "Voafafa ny fanovana ny [[Special:Contributions/$2|$2]] ([[User talk:$2|Dinika]]); voaverina amin'ny votoatiny teo aloha nataon'i [[User:$1|$1]]",

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
'protect-expiry-options'      => '2 ora:2 hours,1 andro:1 day,3 andro:3 days,1 herinandro:1 week,2 herinandro:2 weeks,1 volana:1 month,3 volana:3 months,6 volana:6 months,1 taona:1 year,mandrakizay:infinite',
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
'nocontribs'          => "Tsy misy fanovana mifanaraka amin'ireo critères ireo.",
'uctop'               => ' (loha)',
'month'               => "Tamin'ny volana (sy teo aloha) :",
'year'                => "Tamin'ny taona (sy taloha-ny) :",

'sp-contributions-newbies'    => "Ny fandraisan'anjara ny mpikambana vaovao ihany no ampiseho eto",
'sp-contributions-blocklog'   => "tantaran'asa ny fanakanana",
'sp-contributions-talk'       => 'Dinika',
'sp-contributions-userrights' => 'Fandrindràna ny fahazoan-dàlana',
'sp-contributions-search'     => "Hikaroka fandraisan'anjara",
'sp-contributions-username'   => 'Adiresy IP na anaram-pikambana :',
'sp-contributions-submit'     => 'Hikaroka',

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
'ipboptions'                  => '2 ora:2 hours,1 andro:1 day,3 andro:3 days,1 herinandro:1 week,2 herinandro:2 weeks,1 volana:1 month,3 volana:3 months,6 volana:6 months,1 taona:1 year,mandrakizay:infinite',
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
'expiringblock'               => "tapitra amin'ny $1 $2",
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
'movepagetext'            => "Ampiasao ilay formulaire eo ambany eo mba hamindra azy toerana, voakisaka any amin'ny anarany ankehitriny ny tantarany. Lasa pejy-na redirection ilay pejy taloha, (manondro makany amin'ny anarany ankehitriny ilay pejy).
Afaka manao ''update'' ny redirect ianao.

Jereo koa fa '''tsy afaka''' akisaka ilay pejy ra mitovy anarana amin'ny pejy efa misy ny anarana ny anarana vaovaon'ilay pejy tianao akisaka, fa mety atao ihany io asa io ra tsy misy nininona ilay pejy. Afaka manolo anarana pejy efa manondro ny fihisiny taloha ianao ra diso ianao, fa tsy afaka ataonao no manitsaka pejy efa misy.

'''TANDREMO'''

Mety fanom-panona ihany ny mpitsidika ra be mpitsidika io pejy ovainao anarana io ;
Tsy maintsy fantatrao tsara ny vokany aloha no mitohy.",
'movepagetalktext'        => "Voasikaka koa ny pejin-dresak'ity pejy ity '''ra''' :

* Efa misy pejin-dresaka efa misy votoatiny amin'ilay anarana vaovao, na 
* Ra ny ''décocher''-nao ilay kazy eo ambany.

Tokony ataonao rery io asa io (fusion)",
'movearticle'             => 'Afindrao toerana ny pejy',
'movenologin'             => 'Tsy mbola tafiditra ianao',
'movenologintext'         => 'Ny mpikambana nisoratra anarana sy [[Special:UserLogin|tafiditra]] ihany no afaka mamindra toerana takelaka.',
'newtitle'                => 'Lohateny vaovao',
'move-watch'              => 'araho-maso ity pejy ity',
'movepagebtn'             => 'Afindrao',
'pagemovedsub'            => 'Voafindra ny pejy',
'movepage-moved'          => "<big>voafindra tany amin'ny '''$2''' i '''$1'''</big>",
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

# Thumbnails
'thumbnail-more' => 'Angedazina',
'filemissing'    => 'Tsy hita ny rakitra',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Ny pejinao',
'tooltip-pt-mytalk'               => 'Pejin-dresakao',
'tooltip-pt-preferences'          => 'Ny safidinao',
'tooltip-pt-watchlist'            => 'Ny lisitra ny pejy arahanao-maso',
'tooltip-pt-mycontris'            => "Lisitra ny fandraisan'anjaranao",
'tooltip-pt-login'                => 'Tsara aminao no miditra na manoratra anarana, fa tsy voatery manoratra anarana ianao.',
'tooltip-pt-logout'               => 'Hidio',
'tooltip-ca-talk'                 => 'resaka momba io takelaka io',
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
'tooltip-n-mainpage-description'  => 'hitsidika ny renipejy',
'tooltip-n-portal'                => 'Ny mombamomba ny tetikasa',
'tooltip-n-currentevents'         => "Hidady ny rohy momban'ny vaovao ankehitriny",
'tooltip-n-recentchanges'         => "Lisitra ny fanovàna farany efa vita eto amin'ity wiki ity",
'tooltip-n-randompage'            => 'Hjery pejy aki-sendra',
'tooltip-n-help'                  => 'fanoroana',
'tooltip-t-whatlinkshere'         => 'Lisitra ny pejy wiki mirohy eto',
'tooltip-t-recentchangeslinked'   => "Lisitry ny fanovàna faran'ny pejy manana rohy amin'ity zavatra ity",
'tooltip-feed-rss'                => "Topaka RSS ho an'ity pejy ity",
'tooltip-feed-atom'               => "Topaka atom ho an'ity pejy ity",
'tooltip-t-contributions'         => "Hijery ny lisitry ny fandraisan'anjara an'io mpikambana io",
'tooltip-t-emailuser'             => "Handefa imailaka any amin'io mpikambana io",
'tooltip-t-upload'                => 'Handefa sary na rakitra',
'tooltip-t-specialpages'          => 'Listry ny pejy manokana rehetra',
'tooltip-t-print'                 => 'Ny Version ity pejy azo avoaka taratasy',
'tooltip-t-permalink'             => "Rohy n'ity version ity",
'tooltip-ca-nstab-main'           => 'Jereo ny takelaka',
'tooltip-ca-nstab-user'           => "Jereo ny pejin'ny mpikambana",
'tooltip-ca-nstab-media'          => "Hijery ny pejin'ny Media",
'tooltip-ca-nstab-special'        => 'Pejy manokana ity pejy ity, ny rindrankajy wiki no mitantana ity pejy ity',
'tooltip-ca-nstab-project'        => "Jereo ny pejy momban'ny tetikasa",
'tooltip-ca-nstab-image'          => "jereo ny pejy an'io rakitra io",
'tooltip-ca-nstab-mediawiki'      => "Hijery ny hafatra ampiasain'ny rindrankajy",
'tooltip-ca-nstab-template'       => 'Jereo ny endrika  (môdely)',
'tooltip-ca-nstab-help'           => 'Hijery ny pejy fanoroana',
'tooltip-ca-nstab-category'       => "Hijery ny pejy momban'ilay sokajy",
'tooltip-minoredit'               => 'Mariho ho fanovana madinika ihany',
'tooltip-save'                    => 'Tehirizo ny fanovana',
'tooltip-preview'                 => 'Topazy maso ny fanovana nataonao, iangaviana ianao mba hijery tsipalotra mialoha ny fitahirizana ny fanovana!',
'tooltip-diff'                    => "Asehoy izay novainao tamin'ny lahatsoratra.",
'tooltip-compareselectedversions' => "Jereo ny fahasamihafana amin'ireo votoatin'ny pejy anankiroa ireo.",
'tooltip-watch'                   => "Ampidiro amin'ny lisitry ny pejy arahinao maso ity pejy ity",
'tooltip-rollback'                => "Manala ny fanovan'ny mpikambana farany nanova azy ilay asa « foano » (Rollback) .",
'tooltip-undo'                    => "Manala n'io fanovàna io ilay rohy « esory ».
Mamerina ny version taloha io asa io ary afaka manometraka ny antony anatin'ny ambangovangony.",

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

# Math errors
'math_failure'          => 'Tsy nety ny fanodinana ny raikipohy',
'math_unknown_error'    => 'tsy fahatomombanana tsy hay antony',
'math_unknown_function' => 'fonction tsy fantatra',
'math_lexing_error'     => 'fahadisoana ara-teny',
'math_syntax_error'     => 'Misy diso ny raikipohy',
'math_image_error'      => 'Tsy voavadika ho PNG; hamarino fa mety voapetraka tsara ny rindrankajy latex, dvips, gs ary convert',
'math_bad_tmpdir'       => "Tsy afaka namorona na nanoratra répertoire vonjimaika ho an'ny matematika",
'math_bad_output'       => "Tsy afaka namorona na nanoratra tao amin'ny répertoire hampiasain'ny asa matematika",
'math_notexvc'          => 'Tsy hita ny rindrankajy texvc; azafady jereo math/README hanamboarana azy.',

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

# Bad image list
'bad_image_list' => "Ity ny andrefiny :

Ny lisitra ny takelaka (andalana manomboka amin'ny *) ihany no mandanja.
Tokony sary tsy misy na sary tsy izy ny rohy voalohany anaty andalana iray .
''Exception'' ny rohy afa anatin'ilay andalana iray, oatra ny pejy mety mampiasa n'ilay sary.",

# Metadata
'metadata'          => 'Metadata',
'metadata-help'     => "Mirakitra fampahalalana fanampiny, izay inoana ho napetraky ny fakan-tsary na scanner nampiasaina nanaovana ny numérisation-ny ity rakitra ity. Raha kitihina na ovana izy ity dia mety tsy hifanitsy amin'ny sary voaova ireo antsipirihany sasany ireo.",
'metadata-expand'   => 'Asehoy ny antsipirihany',
'metadata-collapse' => 'Aza aseho ny antsipirihany',
'metadata-fields'   => "Hisy anatin'ny pejin-ambangovangon'ilay sary ny métadonnées ny EXIF rehefa nasitrika ny tabilao ny metafonnées, asitrika ny champ afa.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength",

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
