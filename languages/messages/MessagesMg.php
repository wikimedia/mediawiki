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
 * @author The Evil IP address
 * @author Urhixidur
 * @author לערי ריינהארט
 */

$magicWords = array(
	'redirect'              => array( '0', '#FIHODINANA', '#REDIRECTION', '#REDIRECT' ),
	'notoc'                 => array( '0', '__TSYASIANALAHATRA__', '__AUCUNSOMMAIRE__', '__AUCUNETDM__', '__NOTOC__' ),
	'nogallery'             => array( '0', '__TSYASIANAGALLERY__', '__AUCUNEGALERIE__', '__NOGALLERY__' ),
	'forcetoc'              => array( '0', '__TEREONYLAHATRA__', '__FORCERSOMMAIRE__', '__FORCERTDM__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__LAHATRA__', '__LAHAT__', '__SOMMAIRE__', '__TDM__', '__TOC__' ),
	'noeditsection'         => array( '0', '__TSYAZOOVAINA__', '__SECTIONNONEDITABLE__', '__NOEDITSECTION__' ),
	'currentmonth'          => array( '1', 'VOLANA', 'MOISACTUEL', 'MOIS2ACTUEL', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'         => array( '1', 'VOLANA1', 'MOIS1ACTUEL', 'CURRENTMONTH1' ),
	'currentmonthname'      => array( '1', 'ANARAMBOLANA', 'NOMMOISACTUEL', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'   => array( '1', 'ANARAMBOLANAGEN', 'ANARANAVOLANA', 'CURRENTMONTHNAME', 'NOMGENMOISACTUEL', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'    => array( '1', 'ANARAMBOLANAFOHY', 'ANARANAVOLANAFOHY', 'ABREVMOISACTUEL', 'CURRENTMONTHABBREV' ),
	'currentday'            => array( '1', 'ANDRO', 'JOURACTUEL', 'JOUR1ACTUEL', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'ANDRO2', 'JOUR2ACTUEL', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'ANARANANDRO', 'ANARANAANDRO', 'NOMJOURACTUEL', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'TAONA', 'ANNEEACTUELLE', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'LERA', 'HORAIREACTUEL', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'ORA', 'HEUREACTUELLE', 'CURRENTHOUR' ),
	'localmonth'            => array( '1', 'VOLANAANTOERANA', 'MOISLOCAL', 'MOIS2LOCAL', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'           => array( '1', 'VOLANAANTOERANA1', 'MOIS1LOCAL', 'LOCALMONTH1' ),
	'localmonthname'        => array( '1', 'ANARAMBOLANAANTOERANA', 'NOMMOISLOCAL', 'LOCALMONTHNAME' ),
	'localmonthnamegen'     => array( '1', 'ANARAMBOLANAANTOERANAGEN', 'NOMGENMOISLOCAL', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'      => array( '1', 'ANARAMBOLANAANTOERANAFOHY', 'ABREVMOISLOCAL', 'LOCALMONTHABBREV' ),
	'localday'              => array( '1', 'ANDROANTOERANA', 'JOURLOCAL', 'JOUR1LOCAL', 'LOCALDAY' ),
	'localday2'             => array( '1', 'ANDROANTOERANA2', 'JOUR2LOCAL', 'LOCALDAY2' ),
	'localdayname'          => array( '1', 'ANARANANDROANTOERANA', 'NOMJOURLOCAL', 'LOCALDAYNAME' ),
	'localyear'             => array( '1', 'TAONAANTOERANA', 'ANNEELOCALE', 'LOCALYEAR' ),
	'localtime'             => array( '1', 'LERAANTOERANA', 'HORAIRELOCAL', 'LOCALTIME' ),
	'localhour'             => array( '1', 'ORAANTOERANA', 'HEURELOCALE', 'LOCALHOUR' ),
	'numberofpages'         => array( '1', 'ISAPEJY', 'NOMBREPAGES', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'ISALAHATSORATRA', 'NOMBREARTICLES', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'ISARAKITRA', 'NOMBREFICHIERS', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'ISAMPIKAMBANA', 'NOMBREUTILISATEURS', 'NUMBEROFUSERS' ),
	'numberofactiveusers'   => array( '1', 'ISAMPIKAMBANAMANOVA', 'NOMBREUTILISATEURSACTIFS', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'         => array( '1', 'ISAFANOVANA', 'NOMBREMODIFS', 'NUMBEROFEDITS' ),
	'numberofviews'         => array( '1', 'ISATOPIMASO', 'NOMBREVUES', 'NUMBEROFVIEWS' ),
	'pagename'              => array( '1', 'ANARAMPEJY', 'ANARANAPEJY', 'NOMPAGE', 'PAGENAME' ),
	'pagenamee'             => array( '1', 'ANARAMPEJYX', 'ANARANAPEJYX', 'NOMPAGEX', 'PAGENAMEE' ),
	'namespace'             => array( '1', 'ANARANTSEHATRA', 'ANARANASEHATRA', 'ESPACENOMMAGE', 'NAMESPACE' ),
	'namespacee'            => array( '1', 'ANARANTSEHATRAX', 'ANARANASEHATRAX', 'ESPACENOMMAGEX', 'NAMESPACEE' ),
	'talkspace'             => array( '1', 'PEJINDRESAKA', 'PEJYRESAKA', 'DINIKA', 'ESPACEDISCUSSION', 'TALKSPACE' ),
	'talkspacee'            => array( '1', 'PEJINDRESAKAX', 'PEJYRESAKAX', 'DINIKAX', 'ESPACEDISCUSSIONX', 'TALKSPACEE' ),
	'subjectspace'          => array( '1', 'TOERANALAHATSORATRA', 'ESPACESUJET', 'ESPACEARTICLE', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'         => array( '1', 'TOERANNYLAHATSORATRA', 'ESPACESUJETX', 'ESPACEARTICLEX', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'          => array( '1', 'ANARAMPEJYFENO', 'ANARANAPEJYFENO', 'NOMPAGECOMPLET', 'FULLPAGENAME' ),
	'fullpagenamee'         => array( '1', 'ANARAMPEJYFENOX', 'ANARANAPEJYFENOX', 'NOMPAGECOMPLETX', 'FULLPAGENAMEE' ),
	'subpagename'           => array( '1', 'ANARANAZANAPEJY', 'ANARANJANAPEJY', 'NOMSOUSPAGE', 'SUBPAGENAME' ),
	'subpagenamee'          => array( '1', 'ANARANJANAPEJYX', 'ANARANAZANAPEJYX', 'NOMSOUSPAGEX', 'SUBPAGENAMEE' ),
	'basepagename'          => array( '1', 'ANARANAFOTOPEJY', 'ANARAMPOTOPEJY', 'NOMBASEDEPAGE', 'BASEPAGENAME' ),
	'basepagenamee'         => array( '1', 'ANARANAFOTOPEJYE', 'ANARAMPOTOPEJYE', 'NOMBASEDEPAGEX', 'BASEPAGENAMEE' ),
	'talkpagename'          => array( '1', 'ANARAMPEJINDRESAKA', 'ANARANAPEJINDRESAKA', 'NOMPAGEDISCUSSION', 'TALKPAGENAME' ),
	'img_right'             => array( '1', 'ankavanana', 'droite', 'right' ),
	'img_left'              => array( '1', 'ankavia', 'gauche', 'left' ),
	'img_none'              => array( '1', 'tsymisy', 'néant', 'neant', 'none' ),
	'img_center'            => array( '1', 'ampivoany', 'anivony', 'centré', 'center', 'centre' ),
	'img_page'              => array( '1', 'pejy $1', 'page=$1', 'page $1' ),
	'img_border'            => array( '1', 'sisiny', 'bordure', 'border' ),
	'img_top'               => array( '1', 'ambony', 'haut', 'top' ),
	'img_middle'            => array( '1', 'anivo', 'milieu', 'middle' ),
	'img_bottom'            => array( '1', 'ambany', 'bas', 'bottom' ),
	'currentweek'           => array( '1', 'HERINANDRO', 'SEMAINEACTUELLE', 'CURRENTWEEK' ),
	'currentdow'            => array( '1', 'ALAHADY', 'JDSACTUEL', 'CURRENTDOW' ),
	'localweek'             => array( '1', 'HERINANDROANTOERANA', 'SEMAINELOCALE', 'LOCALWEEK' ),
	'localdow'              => array( '1', 'ALAHADYANTOERANA', 'JDSLOCAL', 'LOCALDOW' ),
	'fullurl'               => array( '0', 'URLREHETRA:', 'URLCOMPLETE:', 'FULLURL:' ),
	'fullurle'              => array( '0', 'URLREHETRAX:', 'URLCOMPLETEX:', 'FULLURLE:' ),
	'displaytitle'          => array( '1', 'ASEHOLOHATENY', 'AFFICHERTITRE', 'DISPLAYTITLE' ),
);

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
'tog-editsection'             => "Ataovy mety ny fanovana fizaràna amin'ny alalan'ny rohy [hanova]",
'tog-editsectiononrightclick' => "Ovay ny fizaràna rehefa manindry ny bokotra havanana amin'ny totozy eo amin'ny lohateny hoe fizaràna (mila JavaScript)",
'tog-showtoc'                 => "Asehoy ny fanoroan-takila (ho an'ny pejy misy lohateny mihoatra ny 3)",
'tog-rememberpassword'        => "Tadidio ny tenimiafiko eto amin'ity ordinatera ity",
'tog-watchcreations'          => "Ampina ao anarin'ny pejy fanaraha-maso ny pejy amboariko",
'tog-watchdefault'            => "Atsofohy ao amin'ny lisitry ny pejy arahinao maso ny pejy izay ovainao na foroninao",
'tog-watchmoves'              => "Ampina ao anatin'ny pejiko fanaraha-maso ny pejy soloiko anarana",
'tog-watchdeletion'           => "Ampina anatin'ny pejy fanaraha-maso ny pejy nofafako",
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
'tog-watchlisthidebots'       => "Asitriho amin'ny lisitro ny fanovàna nataon'ny rôbô",
'tog-watchlisthideminor'      => "Tsy aseho ny fisoloina kely anatin'ny pejy fanaraha-maso",
'tog-watchlisthideliu'        => "Asitriho amin'ny lisitro ny fanovàna nataon'ny mpikambana hafa",
'tog-watchlisthideanons'      => "Asitriho amin'ny lisitro ny fanovana nataon'ny IP",
'tog-watchlisthidepatrolled'  => "Asitriho amin'ny lisitro ny fanovàna efa nojerena",
'tog-ccmeonemails'            => "Andefaso tahaka ny imailaka alefako amin'ny mpikambana hafa",
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
'category-subcat-count'          => '{{PLURAL:$2|Ity sokajy ity|Ireo sokajy ireo}} dia manana {{PLURAL:$1|zana-tsokajy|zana-tsokajy}} $1 . Ny taotaliny dia $2',
'category-subcat-count-limited'  => 'Misy zana-tsokajy $1 ity sokajy ity.{{PLURAL:}}',
'category-article-count'         => "{{PLURAL:$2|Misy pejy $1 ity sokajy ity|Misy pejy $1 ity sokajy ity}}. Pejy $2 no anatin'ity sokajy ity",
'category-article-count-limited' => "Anatin'ity sokajy ity ireo pejy ireo pejy ireo ($1 ny tontaliny){{PLURAL:}}",
'category-file-count'            => 'Misy rakitra $1 (tontaliny : rakitra $2) ireo ity sokajy ity{{PLURAL:}}',
'category-file-count-limited'    => "Anatin'ity sokajy ity ireo rakitra ireo. ($1 no aseho) {{PLURAL:}}",
'listingcontinuesabbrev'         => ' manaraka.',
'index-category'                 => 'pejy voasokajy',
'noindex-category'               => 'Pejy tsy voasikajy',

'mainpagetext'      => "'''Tafajoro soa aman-tsara ny rindrankajy Wiki.'''",
'mainpagedocfooter' => "Vangio ny [http://meta.wikimedia.org/wiki/Aide:Contenu Fanoroana ho an'ny mpampiasa] ra te hitady fanoroana momba ny fampiasan'ity rindrankajy ity.

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
'qbedit'         => 'Hanova',
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
'vector-view-edit'           => 'Hanova',
'vector-view-history'        => 'Hijery ny tantara',
'vector-view-view'           => 'Hamaky',
'vector-view-viewsource'     => 'hijery fango',
'actions'                    => 'Tao',
'namespaces'                 => 'Namespace',
'variants'                   => "Ny ''skin'' Voasintona",

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
'updatedmarker'     => 'niova hatry ny tsidiko farany',
'info_short'        => 'Fampahalalana',
'printableversion'  => 'Version afaka avoaka taratasy',
'permalink'         => 'Rohy maharitra',
'print'             => 'Avoaka an-taratasy',
'edit'              => 'hanova',
'create'            => 'Amboary',
'editthispage'      => 'Hanova ity pejy ity',
'create-this-page'  => 'Amboary ity pejy ity',
'delete'            => 'Hamafa',
'deletethispage'    => 'Fafao ity pejy ity',
'undelete_short'    => 'Avereno ny fanovana {{PLURAL:$1|$1|$1}}',
'protect'           => 'Arovy',
'protect_change'    => 'ovay',
'protectthispage'   => 'Arovy (hidio) ity pejy ity',
'unprotect'         => 'Esory ny fiarovana',
'unprotectthispage' => "Esory ny fiarovan'ity pejy ity",
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
'mediawikipage'     => 'Hijery ny pejy misy io hafatra io',
'templatepage'      => "Jereo ny pejin'ny endrika",
'viewhelppage'      => "Jereo ny pejin'ny fanampiana",
'categorypage'      => "Jereo ny pejin'ny sokajy",
'viewtalkpage'      => 'Hijery pejin-dresaka',
'otherlanguages'    => "Amin'ny tenim-pirenena hafa",
'redirectedfrom'    => "(tonga teto avy amin'ny $1)",
'redirectpagesub'   => 'Pejy fihodinana',
'lastmodifiedat'    => "Voaova farany tamin'ny $1 amin'ny $2 ity pejy ity<br />",
'viewcount'         => 'voastsidika in-$1 ity pejy ity.{{PLURAL:}}',
'protectedpage'     => 'Pejy voaaro',
'jumpto'            => 'Hanketo:',
'jumptonavigation'  => 'Fikarohana',
'jumptosearch'      => 'karohy',
'view-pool-error'   => 'Azafady, be asa ny lohamilina ankehitriny.
Betsaka loatra ny mpikambana mitady hijery ity pejy ity.
Miandrasa kely, dia avereno.

$1',

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
'badaccess-groups' => "Ny asa andramanao atao io dia voafetra amin'ny mpikambana ao amin'ny vondrona $1.{{PLURAL:$2||}}",

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
'editsection'             => 'hanova',
'editold'                 => 'hanova',
'viewsourceold'           => 'hijery fango',
'editlink'                => 'hanova',
'viewsourcelink'          => 'hijery ny fango',
'editsectionhint'         => 'Manova ny fizaràna : $1',
'toc'                     => 'Votoatiny',
'showtoc'                 => 'aseho',
'hidetoc'                 => 'afeno',
'thisisdeleted'           => 'Hojerena sa haverina i $1?',
'viewdeleted'             => "Hijery an'i $1?",
'restorelink'             => 'ny fanovàna voafafa $1{{PLURAL:}}',
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
'dberrortext'          => 'Nisy tsy fetezana ao amin\'ny fangatahana tany amin\'ny database.
Inoana fa ny rindrankajy no misy olana (bug).
Ny fangatahana farany dia:
<blockquote><tt>$1</tt></blockquote>
tao amin\'ny tao "<tt>$2</tt>".
Toy izao no navalin\'ny MySQL "<tt>$3: $4</tt>".',
'dberrortextcl'        => "Ao amin'ny fangatahana tao amin'ny banky angona dia misy tsi-fetezana ara-pehezanteny.
Ny fangatahana farany nalefa dia :
« $1 »
tao amin'ny asa « $2 ».
Ny banky angona dia namerina ny tsi-fetezana « $3 : $4 »",
'laggedslavemode'      => 'Fampitandremana: Mety ho tsy nisy fanovana vao haingana angamba io pejy io',
'readonly'             => 'Mihidy ny banky angona',
'enterlockreason'      => 'Manomeza antony hanidiana ny pejy, ahitana ny fotoana tokony hamahana izay fihidiana izay',
'readonlytext'         => "
Mihidy vonjimaika aloha ny banky angona ka tsy afaka anaovana fanovana na fanampiana vaovao. Azo inoana fa asa fikolokoloana mahazatra ihany io ka rehefa vita izay asa izay dia hverina amin'ny laoniny izy.

Ny mpitantana nanidy azy dia nametraka ito fanazavana ito: $1",
'missing-article'      => "Tsy hitan'ny banky angona ilay lahatsoratra pejy iray tokony ho hitany, mitondra ny lohateny  « $1 » $2.

Matetika, izany no mitranga rehefa manaraka rohy makany amina diff efa lany andro na efa makany amin'ny pejy tantaran'ny pejy voafafa iray.

Raha tsy izany, mety misy olana ao amin'ny rindrankajin'ny lohamilina.
Lazao any amin'ny  [[Special:ListUsers/sysop|mpandrindra]] io olana io ary aza adino no manome azy ny URL an'ilay rohy.",
'missingarticle-rev'   => '(famerenana faha : $1)',
'missingarticle-diff'  => '(diff : $1 ; $2)',
'readonly_lag'         => "
Mihidy ho azy aloha ny banky angona mandra-pahatratran'ny serveur andevo ny tompony",
'internalerror'        => "Tsy fetezana anatin'ny rindrankajy",
'internalerror_info'   => 'Tsy fetezana ety anatiny : $1',
'fileappenderrorread'  => 'Tsy afaka mamaky « $1 » nandritry ny fampidirana.',
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
'cannotdelete'         => "Tsy afaka fafàna ny pejy na ny rakitra « $1 ».
Mety efa nataon'ny hafa angamba ny famafàna.",
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
'protectedinterface'   => "Manome lahatsoratra ho an'ny rindrankajy ity pejy ity ary voaaro izy ity mba tsy hisy hanararaotra",
'editinginterface'     => "'''Tandremo :''' manova pejy ampiasan'ny lôjisialy wiki ianao. Mety hita ny mpikambana sàsany izy io. Rehefa tia mandika teny ianao, jereo ny volavola MediaWiki ho an'ny internationalisation ny hafatra [http://translatewiki.net/wiki/Main_Page?setlang=fr translatewiki.net].",
'sqlhidden'            => '(nafenina ny requête SQL)',
'cascadeprotected'     => 'Ankehitriny dia voaaro ity pejy ity satria misy pejy voaaro {{PLURAL:$1||$1}}1 mampiasa ity pejy ity. Io pejy io dia mampiasa ny fiarovana "en cascade" :

$2',
'namespaceprotected'   => "Tsy manana alalàna manova ny toeran'anarana « '''$1''' » ianao.",
'customcssjsprotected' => "Tsy azonao atao ny manova ity pejy ity, satria misy ny safidin'ny mpikambana hafa ity pejy ity.",
'ns-specialprotected'  => "Tsy afaka ovaina ny pejy anatin'ny toeran'anarana « {{ns:special}} » .",
'titleprotected'       => "Voaaron'i [[User:$1|$1]] ity lohateny ity mba tsy hamorona pejy mitondra ity anarana ity.
Ny antony napetraka dia : « ''$2'' ».",

# Virus scanner
'virus-badscanner'     => "Diso : Tsy fantatray ny mpitady virus ''$1''",
'virus-scanfailed'     => 'Tsy mety alefa ny fitadiavana (kaody $1)',
'virus-unknownscanner' => 'Tsy fantatra io Antivirus io :',

# Login and logout pages
'logouttext'                 => "'''Tafavoaka ianao ankehitriny.'''

Mbola afaka mampiasa ny {{SITENAME}} ianao na dia ef anivoaka aza, na afaka [[Special:UserLogin|miverina miditra]] ianao ambanin'ny anaranao na anaram-pikambana hafa.
Fantaro fa ny endriky ny pejy sasany dia mety mitovy amin'ny endrika nahitanao azy tamin' ianao mbola niditra tato, ho toy izany ny endri-pejy raha tsy nofafanao ny cache.",
'welcomecreation'            => '== Tonga soa, $1! ==

Voaforona soa aman-tsara ny kaontinao.
Aza hadino ny manova ny [[Special:Preferences|safidinao]]',
'yourname'                   => 'Solonanarana',
'yourpassword'               => 'Tenimiafina',
'yourpasswordagain'          => 'Avereno ampidirina eto ny tenimiafina',
'remembermypassword'         => '{{PLURAL:}}Tadidio ny tenimiafiko (mandritry ny $1 andro fara-fahabetsany)',
'yourdomainname'             => 'faritra (domaine) misy anao',
'externaldberror'            => "Nisy tsy fetezana angamba teo amin'ny fanamarinana anao tamin'ny sehatra ivelan'ity wiki ity, na tsy manana alalana hanova ny kaontinao ivelany ianao.",
'login'                      => 'Midira',
'nav-login-createaccount'    => 'Ampidiro ny solonanarana',
'loginprompt'                => "
Mila manaiky cookies ianao raha te hiditra amin'ny {{SITENAME}}.",
'userlogin'                  => 'Hiditra na hanokatra kaonty',
'userloginnocreate'          => 'hiditra',
'logout'                     => 'Hiala',
'userlogout'                 => 'Hiala',
'notloggedin'                => 'Tsy tafiditra',
'nologin'                    => "Tsy manana solonanarana? '''$1'''.",
'nologinlink'                => 'Manokafa kaonty',
'createaccount'              => 'Hamorona kaonty',
'gotaccount'                 => "Efa manana kaonty? '''$1'''.",
'gotaccountlink'             => 'Midira',
'createaccountmail'          => "amin'ny imailaka",
'badretype'                  => 'Tsy mitovy ny tenimiafina nampidirinao.',
'userexists'                 => 'Efa misy mampiasa io solonanarana io. Mifidiana solonanarana hafa azafady.',
'loginerror'                 => "Tsy fetezana teo amin'ny fidirana",
'createaccounterror'         => 'Tsy afaka mamorona kaonty : $1',
'nocookiesnew'               => "Voaforona ny kaonty-mpikambana, fa tsy niditra ianao. Mampiasa ny cookies ny {{SITENAME}} ho an'ny fampidirana fa tsy navelanao ampiasaina ny cookies. Omeo alalana miditra eo amin'ny mpikajinao ny cookies, ary midira ato miaraka amin'ny solonanarana sy ny tenimiafina.",
'nocookieslogin'             => 'Mampiasa cookies i {{SITENAME}} nefa ny navigateur-nao tsy manaiky cookies. Ovay mba hanaiky cookies aloha ny navigateur-nao vao afaka manandrana miditra ato indray.',
'noname'                     => 'Tsy nanome solonanarana mety ianao.',
'loginsuccesstitle'          => 'Tafiditra soa aman-tsara',
'loginsuccess'               => "'''Tafiditra amin'ny {{SITENAME}} ianao ry \"\$1\".'''",
'nosuchuser'                 => 'Tsy misy mpikambana manana izany solonanarana "$1" izany. Hamarino ny tsipelina na manokafa kaonty vaovao.',
'nosuchusershort'            => 'Tsy misy mpikambana hoe "<nowiki>$1</nowiki>". Hamarino ny tsipelina.',
'nouserspecified'            => 'Tsy maintsy mampiditra solonanarana ianao.',
'login-userblocked'          => 'Voasakana io mpikambana io. Fidirana tsy nahazoan-dalana.',
'wrongpassword'              => 'Diso ny tenimiafina. Manandrama tenimiafina hafa azafady.',
'wrongpasswordempty'         => 'Tsy nampiditra tenimiafina ianao, azafady mba avereno indray.',
'passwordtooshort'           => '{{PLURAL:}}Fohy loatra io tenimiafina io.
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
tenimiafina taloha ihany no ampiasao.{{PLURAL:}}',
'noemail'                    => 'Tsy nanome adiresy imailaka i "$1".',
'noemailcreate'              => 'Tsy maintsy misy ny adiresy imailaka ho atsofokao',
'passwordsent'               => 'Nandefasana tenimiafina vaovao any amin\'ny adiresy imailak\'i "$1".
Azafady midira rehefa voarainao io imailaka io.',
'blocked-mailpassword'       => "Voasakana ny adiresy IP-nao, nesorina aminao ny asa ''password recovery'' mba tsy hanararaotra.",
'eauthentsent'               => "
Efa nandefasana imailaka fanamarinana ilay adiresy nomenao.
Alohan'ny handraisanao imailaka hafa, dia araho ny torolalana ao anatin'io imailaka io,
mba hanaporofoana fa anao io kaonty io.",
'throttled-mailpassword'     => "Nandefa imailaka mety mampatadidy anao ny tenimiafinao izahay nandrintra ny $1 ora farany. Mba tsy hanararaotra, imailaka iray ihany no azo alefa isakin'ny ady ny $1{{PLURAL:}}",
'mailerror'                  => "Nisy olana tamin'ny fandefasana imailaka: $1",
'acct_creation_throttle_hit' => 'Miala tsiny, efa nanokatra kaonty miisa $1 ianao, ka tsy afaka mamorona hafa intsony.{{PLURAL:}}',
'emailauthenticated'         => "Voamarina tamin'ny $2 $3 ny adiresy imailakao.",
'emailnotauthenticated'      => "Tsy mbola voamarina ny adiresinao. Tsy mbola afaka mandefa hafatra ianao amin'ireto zavatra azo atao manaraka ireto.",
'noemailprefs'               => 'Manomeza adiresy imailaka raha hampiasa ireo fitaovana ireo ianao.',
'emailconfirmlink'           => 'Hamarino ny adiresy imailakao',
'invalidemailaddress'        => 'Tsy mety io imailaka nalefanao io satria tsy manaraka ny firafitra tokony ho izy.
Azafady manomeza adiresy voasoratra tsara na avelao ho banga io toerana io.',
'accountcreated'             => 'Kaonty voaforona',
'accountcreatedtext'         => "Voasokatra ilay kaonty hoan'i $1.",
'createaccount-title'        => "Fanokafana kaonty ho an'ny/i {{SITENAME}}",
'createaccount-text'         => "Nisy olona nanokatra kaonty ho an'ny adiresy imailakao eo amin'ny {{SITENAME}} ($4) mitondra anarana « $2 » miarak'amin'ny tenimiafina « $3 ».<br />
Tokony miditra na manokatra kaonty ianao ary manova ny tenimiafinao dian-izao.

Aza mijery ity hafatra ity ianao raha voaforona an-tsipetezana ilay kaonty io.",
'usernamehasherror'          => 'Ny anaram-pikambana dia tsy afaka manana soratra fanaovana hash.',
'login-throttled'            => "Im-betsaka loatra ianao no nanandrana tenimiafina teo amin'io kaonty io.
Andraso kely ary andramo indray.",
'loginlanguagelabel'         => 'fiteny : $1',
'suspicious-userlogout'      => "Ny fangataham-pialanao dia tsy nekena satria ohatry ny nalfan'ny mpizahan-tsehatra simba izy na kasy ny proxy.",

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
'resetpass-submit-cancel'   => 'Aoka ihany',
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
'anoneditwarning'                  => "'''Tandremo :''' tsy niditra/nanokatra kaonty tato ianao. Ho voasoratra ao amin'ny tatitr'asan'ity pejy ity ny adiresy IP-nao.",
'missingsummary'                   => "'''Fampandrenesana''' : Tsy nameno ny ambangovangony ianao.
ra tonga dia tsindrinao ilay bokotra « tehirizo », alefa avy hatrany ny fanovànao",
'missingcommenttext'               => 'Ampidiro ny ambangovangony azafady.',
'missingcommentheader'             => "'''Fampahatsiahivana :''' Tsy nampiditra lohateny amin'ity resaka ity ianao.
Raha tsindrianao indray eo amin'ny « {{MediaWiki:Savearticle}} » ho voatahiry tsy misy lohateny ny fanovananao.",
'summary-preview'                  => "Topi-maso n'ilay ambangovangony :",
'subject-preview'                  => 'Topi maso ny lazaina :',
'blockedtitle'                     => 'Mpikambana voasakana',
'blockedtext'                      => "'''Voasakana ny solonanaranao na ny adiresy IP anao.'''

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

Jereo koa fa tsy afaka mampiasa ny asa ''emailuser'' ianao ra tsy nanometraka ny adiresy imailakao anatin'ny [[Special:Preferences|safidinao]]. Jereo koa ra tsy nesorinao ny asa ''emailuser''.

$3 izao ny adiresinao, ary ny isa ny fisakananai dia $5.
Soraty ireo fanoroana ireo anatin'ny fangatahana ataonao.",
'blockednoreason'                  => 'tsy nisy antony nomeny',
'blockedoriginalsource'            => "Eto amban'ny kaody fango ny '''$1''' :",
'blockededitsource'                => "Eo amban'ny votoatin'ny '''nosoratanao''' nataonao tamin'ny '''$1'''",
'whitelistedittitle'               => 'Midira/Misorata anarana',
'whitelistedittext'                => "Mila $1 aloha ianao vao afaka manova/mamorona pejy eto amin'ity wiki ity.",
'confirmedittext'                  => "Tsy maintsy marihina ny adiresy imailakao aloha no manova pejy.
Ampidiro sy Checkeo ny adiresy imailakao amin'ny [[Special:Preferences|safidinao]].",
'nosuchsectiontitle'               => 'Tsy nahita ilay fizarana',
'nosuchsectiontext'                => "Nanandrana nanova fizarana tsy nisy ianao.
Mety efa nakisaka angamba izy, na voafafa tamin' ianareo namaky ity pejy ity farany.",
'loginreqtitle'                    => 'Mila fidirana',
'loginreqlink'                     => 'miditra',
'loginreqpagetext'                 => 'Tokony $1 ianao raha te hijery pejy hafa.',
'accmailtitle'                     => 'Lasa ny tenimiafina.',
'accmailtext'                      => 'Lasa any amin\'i $2 ny tenimiafin\'i "$1".',
'newarticle'                       => '(Vaovao)',
'newarticletext'                   => "Mbola tsy misy ity takelaka ity koa azonao atao ny mamorona azy eto ambany. Jereo ny [[{{MediaWiki:Helppage}}|Fanoroana]] raha misy fanazavana ilainao.

Raha toa moa ka tsy nieritreritra ny hamorona ity takelaka ity ianao dia miverena etsy amin'ny fandraisana.",
'anontalkpagetext'                 => "----''Eto no sehatra iresahana ho an'ny mpikambana tsy mitonona anarana, izany hoe tsy nisoratra anarana na tsy mampiasa ny solonanarany. Voatery noho izany isika mampiasa ny adiresy IP ho marika hanondroana azy. Io adiresy IP io dia mety hikambanana amin'ny olona maro hafa. Raha tsy mitonona anarana ianao ary mahatsapa fa misy fanamarihana tsy miantefa aminao loatra voarainao, dia iangaviana ianao mba [[Special:UserLogin/signup|hisoratra anarana ho mpikambana na hiditra]] mba tsy hifangaroanao amin'ny mpikambana tsy mitonona anarana hafa intsony.''",
'noarticletext'                    => "'''Tsy mbola nisy namorona io lahatsoratra io.
Azonao atao ny [[Special:Search/{{PAGENAME}}||Tadiavo ny momba ny {{PAGENAME}}]].'''
* '''[{{SERVER}}{{localurl:{{NAMESPACE}}:{{PAGENAME}}|action=edit}} Na forony eto ny lahatsoratra momba ny {{PAGENAME}}]'''.",
'noarticletext-nopermission'       => "Mbola tsy misy lahatsoratra ao amin'io pejy io.

Azonao atao ny [[Special:Search/{{PAGENAME}}|Mikaroka momba ny lohatenin'io pejy io]] ao amin'ny pejy hafa, mitady <span class=\"plainlinks\">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} anatin'ny tatitr'asa mikasika azy]</span>",
'userpage-userdoesnotexist'        => 'Mbola tsy nisoratra anarana ato i « $1 ». Marino raha tena hamorona ity pejy ity ianao.',
'userpage-userdoesnotexist-view'   => 'Tsy nisoratra anarana ato i « $1 ».',
'blocked-notice-logextract'        => "Ankehitriny ity mpikambana ity dia voasakana.
Ny fampidirana faran'ny tatitr'asa ny fanakanam-pikambana dia naseho teo ambany ho an'ny fampahalalàna :",
'clearyourcache'                   => "'''Fanamarihana:''' Aorian'ny fanovana, dia mila mamafa ny cache ianao vao mahita ny fiovana.
'''Mozilla / Firefox / Safari:''' Tsindrio ny ''Shift'' rehefa manindry ''Reload'', na tsindrio ''Ctrl-Shift-R'' (''Cmd-Shift-R'' ho an'ny Apple Mac); '''IE:''' tsindrio ''Ctrl'' rehefa manindry ''Refresh'', na tsindrio ''Ctrl-F5''; '''Konqueror:''': tsindrio fotsiny ny bokotra ''Reload'' na ''F5''; ny mpampiasa '''Opera''' angamba dia tokony hamafa ny cache-ny ao amin'ny ''Tools&rarr;Preferences''.",
'usercssyoucanpreview'             => "'''Fika:''' Ampiasao ny bokotra 'Tsipalotra' mialoha ny hitehirizanao ny CSS-nao vaovao.",
'userjsyoucanpreview'              => "'''Fika:''' Ampiasao ny bokotra 'Tsipalotra' mialoha ny hitehirizanao ny JS-nao vaovao.",
'usercsspreview'                   => "'''Tadidio fa mijery tsipalotra ny fivoakan'ny takilan'angalinao (CSS) vaovao fotsiny ihany ianao fa tsy mbola voatahiry akory izy io!'''",
'userjspreview'                    => "
'''Tadidio fa manandrana/mijery tsipalotra ny fivoakan'ny JavaScript-nao fotsiny ihany ianao fa tsy mbola voatahiry akory izy io!'''",
'userinvalidcssjstitle'            => "'''Fampitandremana''' : Tsy misy skin « $1 ».
Tadidio fa mampiasa soramadinika ny lohatenin'ny pejinao manan-tovana *.css sy *.js, ohatra {{ns:user}}:Foo/monobook.css fa tsy {{ns:user}}:Foo/Monobook.css.",
'updated'                          => '(Voaova)',
'note'                             => "'''Fanamarihana:'''",
'previewnote'                      => "'''Topi-maso ihany ity hitanao ity, tsy mbola voatahiry ny fanovana nataonao!'''",
'previewconflict'                  => "
Ity topi-maso ity no mifanaraka amin'ny lahatsoratra ao amin'ny faritra eo ambony,
ary toy izao no ho fisehon'ny pejy raha misafidy ny hitahiry azy ianao.",
'session_fail_preview'             => "'''Miala tsiny. Tsy afaka atao ny asa ilainao satria misy very ny fampahalalana ilaina momba ny session. Azafady mba andramo fanindroany. Raha tena mbola tsy mety dia manandrama mivoaka mihitsy aloha dia miditra indray avy eo!'''",
'session_fail_preview_html'        => "'''Tsy afaka tehirizinay ny fanovanao noho ny haverezan'ny fampahalalàna momba ny session-nao.

'''Satria nalefan'i {{SITENAME}} HTML tsotra, nasitrika ny topi-paso mba tsy hisy fanafihana atao amin'ny Javascript.

'''Raha ara-dalàna ny fanovanao, avereno.'''
Raha mbola tsy mandeha foana ilay izy, [[Special:UserLogout|mivoaha]] ary midira",
'token_suffix_mismatch'            => "'''Tsy nekena ny fanovanao satria tsy voakaodin'ny rindrankajinao tsara ny soratra tao anatin'ny identifiant de modification.'''
Nilaina io tsy fanekena io mba tsy hikatso ilay pejy.
Misy io olana io rehefa mamppiasa serveur mandataire tsy manana anarana sy manan-olana eo amin'ny tranonkala ianao.",
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
ianao dia tehirizo anaty rakitra ny fanovanao mandra-paha.'''

Ny mpandrindra nanidy ny banky angona dia nanome ny antony : <br />$1",
'protectedpagewarning'             => "'''FAMPITANDREMANA:  Voaaro ity pejy ity ka ny mpikambana manana ny fahazoan-dàlana sysop ihany no afaka manova azy.'''",
'semiprotectedpagewarning'         => "'''Naoty''' : Voaaro ity pejy ity, ny mpikambana nanokatra kaonty tato ihany no afaka manova azy.",
'cascadeprotectedwarning'          => "'''Tandremo : ''' Voaaro ity pejy ity ary ny mpandrindra ihany no afaka manova azy. Natao ny fiarovana satria ao anatina pejy voaaro mampiasa ny « fiarovana an-driana (protection en cascade) » {{PLURAL:$1}}",
'titleprotectedwarning'            => "'''TANDREMO''' : Ny mpikambana manana [[Special:ListGroupRights|alàlana manokana]] ihany no afaka manova ity pejy ity.",
'templatesused'                    => "endrika{{PLURAL:$1||}} miasa eto amin'ity pejy ity:",
'templatesusedpreview'             => "endrika{{PLURAL:$1||}} ampiasaina anatin'ity topi-maso ity :",
'templatesusedsection'             => "Endrika miasa anatin'ity{{PLURAL:$1||}} fizaràna ity :",
'template-protected'               => '(voaaro)',
'template-semiprotected'           => '(voaaro an-tàpany)',
'hiddencategories'                 => '{{PLURAL:$1|anaty sokajy|anaty sokajy}} nasitrika $1 ity pejy ity',
'nocreatetitle'                    => 'Voafetra ny famoronana pejy',
'nocreatetext'                     => " Voafetra ihany ny fahafahana mamorona pejy eto amin'ity sehatra ity.  Ny pejy efa misy no azonao ovaina, na [[Special:UserLogin|midira na mamoròna kaonty]].",
'nocreate-loggedin'                => 'Tsy mahazo ataonao no manamboatra pejy vao.',
'sectioneditnotsupported-title'    => 'Fanovana fizarana tsy zaka',
'sectioneditnotsupported-text'     => "Ny fanovana fizarana iray dia tsy zaka ao anatin'ity pejy fanovana ity.",
'permissionserrors'                => 'Tsy azonao atao',
'permissionserrorstext'            => 'Tsy afaka manao ilay aza nangatahanao ianao noho ny antony {{PLURAL:$1||maro}} manaraka :',
'permissionserrorstext-withaction' => '{{PLURAL:$1|Tsy manana alalàna ianao|Tsy manana alalàna ianao}} $2. Io ny antony ($2):',
'recreate-moveddeleted-warn'       => "'''Tandremo''' : Mamerina pejy efa voafafa ianareo.''''

Marino raha tsara tohizana ny fanovana eto amin'ity pejy ity. Ny tatitr'asa momban'ny famafana pejy sy ny fanovan-toerana dia eo ambany :",
'moveddeleted-notice'              => "Voafafa ity pejy ity.
Eo ambany eo any tatitr'asa momban'ny famindran-toerana sy ny famafana ho an'ny antsipirihany.",
'log-fulllog'                      => 'Hijery ny tatitr’asa (log)',
'edit-hook-aborted'                => 'Tsy nety ny fanovàna
Tsy nanome antony',
'edit-gone-missing'                => 'Tsy afaka natao update ilay pejy.
Mety voafafa angamba izy.',
'edit-conflict'                    => 'Adi-panovàna.',
'edit-no-change'                   => "Tsy norarahian'ny rindrankajy ny fanovanao satria tsy nanova ny lahatsoratra ianao.",
'edit-already-exists'              => 'Tsy afaka amboarina ilay pejy vaovao.
Efa misy izy.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Tandremo : Betsaka loatra ny fanantsoana ny tao parser.

Tsy maintsy latsaky ny $2 ny tao, kanefa misy $1. {{PLURAL:$2||}}',
'expensive-parserfunction-category'       => 'Pejy mampiasa be loatra ny tao parser',
'post-expand-template-inclusion-warning'  => "'''Tandremo''' : be loatra ny endrika ampiasain'ity pejy ity, misy endrika tsy ho ampiasaina.",
'post-expand-template-inclusion-category' => 'Pejy be be endrika',
'post-expand-template-argument-warning'   => "Tandremo : Manana mpihazaka endrika tsy afaka ampidirina ity pejy ity.
Ao aorian'ny fivelarana, mety namoaka valy lava loatra angamba izy, ary tsy nampidirina tato noho izany antony izany.",
'post-expand-template-argument-category'  => 'Pejy misy parametatra endrika hadino',
'parser-template-loop-warning'            => 'endrika vono hita tao : [[$1]]',
'parser-template-recursion-depth-warning' => "Fetran'ny halalin'ny fiantsoana endrika voahoatra ($1).",
'language-converter-depth-warning'        => "Mihoatra ny fetran-kalalin'ny mpamadika teny ($1)",

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
'revisionasof'           => "Endrik'io pejy io tamin'ny $1",
'revision-info'          => "Santiônan'i $1 nataon'i $2",
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
'history-show-deleted'   => 'Voafafa ihany',
'histfirst'              => 'Ny vao indrindra',
'histlast'               => 'Antintra indrindra',
'historysize'            => '($1 {{PLURAL:$1|oktety|oktety}})',
'historyempty'           => '(tsy misy)',

# Revision feed
'history-feed-title'          => 'Tantara ny fanovàna',
'history-feed-description'    => "Tantaran'ity pejy ity teto amin'ity wiki ity.",
'history-feed-item-nocomment' => "$1 tamin'ny $2",
'history-feed-empty'          => "Tsy misy ny pejy notadiavina.
Mety efa voafafa na voafindra angamba izy.
Mitadiava amin'ny '''[[Special:Search|fiasàna fitadiavina]]''' mba hitady ny pejy misy fifandraisana.",

# Revision deletion
'rev-deleted-comment'         => '(hafatra nesorina)',
'rev-deleted-user'            => '(solonanarana nesorina)',
'rev-deleted-event'           => '(nesorina ny fampidirana)',
'rev-deleted-user-contribs'   => "[anaram-pikambana na adiresy IP voafafa - fanovana nasitria teo amin'ny fandraisan'anjara modification]",
'rev-deleted-text-permission' => "'''Voafafa''' ny santiônan'ity pejy ity.
Mety misy ny antsipirihany angamba ny [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAME}}}} tatitr'asa momban'ny famafàna pejy].",
'rev-deleted-text-unhide'     => "Ity santiônan'ity pejy ity dia '''voafafa'''.
Hita ao amin'ny [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} tatitr'asa momban'ny famafana] ny antsipirihany.
Noho ianao mpandrindra mbola afaka [$1 mijery ilay santiôna] ianao raha tianao.",
'rev-deleted-no-diff'         => "Tsy afaka mijery anio diff io ianao satria misy santôna '''voafafa''' ao aminy.
Mety any amin'ny [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAME}}}} tatitr'asa momban'ny famafàna pejy] ny antsipirihany.",
'rev-suppressed-no-diff'      => "Tsy azo jerenao io diff io satria '''voafafa''' ny iraika amin'ny reviziônany.",
'rev-deleted-unhide-diff'     => "Nisy '''voafafa''' ny iraika amin'ny reviziôna an'ity diff ity.
Ny antsipirihany dia mety hita ao amin'ny [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} tatitr'asa momban'ny famafana].
Noho ianao mpandrindra,  [$1 azonao jerena foana ilay diff] raha tianao.",
'rev-suppressed-unhide-diff'  => "Nisy '''voafafa''' ny iraika amin'ny reviziôna an'ity diff ity.
Ny antsipirihany dia mety hita ao amin'ny [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} tatitr'asa momban'ny famafana].
Noho ianao mpandrindra,  [$1 azonao jerena foana ilay diff] raha tianao.",
'rev-deleted-diff-view'       => "Nisy '''voafafa''' ny iraika amin'ny reviziôna an'ity diff ity.
Ny antsipirihany dia mety hita ao amin'ny
Noho ianao mpandrindra,  azonao jerena foana ny [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} tatitr'asa momban'ny famafana]. raha tianao.",
'rev-delundel'                => 'jereo/asitriho',
'rev-showdeleted'             => 'aseho',
'revisiondelete'              => 'Hamafa/hamerina revision',
'revdelete-nooldid-title'     => 'tsy izy ny version tanjona',
'revdelete-nooldid-text'      => 'Tsy nilaza ny santiôna hokendrena ianao, tsy misy izy, na santiôna ankehitriny io.',
'revdelete-nologtype-title'   => "Tsy misy tatitr'asa nampidirana/nomena",
'revdelete-nologtype-text'    => "Tsy nosafidianao akory ny tatitr'asa anaovana io tao io ianao",
'revdelete-nologid-title'     => "Misy diso ny tatitr'asa nampidiranao",
'revdelete-nologid-text'      => "Tsy nosafidianao akory ny tatitr'asa anaovana io tao io ianao, na tsy misy ilay zavatra notenenina",
'revdelete-no-file'           => 'Tsy misy ilay rakitra hofafàna.',
'revdelete-show-file-confirm' => "Tapa-kevitra hamafa ny ''revision''-n'i rakitra <nowiki>$1</nowiki> tamin'ny $2 tamin'ny $3 ve ianao ?",
'revdelete-show-file-submit'  => 'Eny',
'revdelete-selected'          => "'''{{PLURAL:$2|Votoatiny nosafidiana|Votoatiny nosafidiana}}n'i '''[[:$1]]''' :'''",
'logdelete-selected'          => "'''{{PLURAL:$1||}}Tatitr'asa voafidy :'''",
'revdelete-text'              => "'''Mbola ao amin'ny tatitr'asa ny santiôna voafafa, fa tsy afaka jeren'ny vahoaka ny lahatsoratra ao aminy.'''
Afaka mijery ny lahatsoratra nasitrika sy mamerina azy ny mpandrindra hafa ny {{SITENAME}} amin'ny alalan'ity interface ity, raha tsy misy restriction hafa koa.",
'revdelete-suppress-text'     => "Ny famafàna pejy dia ampiasaina rehefa :
* Misy information tsy sarababem-bahoaka tsy metimety
*: ''Misy adiresy nomeraona antso an-tariby, nomeraona sécurité sociale, sns.''",
'revdelete-legend'            => "Ampetraho ny fepetra momban'ny fahitana :",
'revdelete-hide-text'         => "Asitriho ny lahatsoratr'ity version ity",
'revdelete-hide-image'        => "asitriho ny votoatin'ilay rakitra",
'revdelete-hide-name'         => 'Asitriho ny asa sy ny tanjona',
'revdelete-hide-comment'      => 'asitriho ny ambangovangony',
'revdelete-hide-user'         => "Asitriho ny solonanaran'ny mpikambana/adiresy IP",
'revdelete-hide-restricted'   => "Fafao ireo votoatiny ireo amin'ny mpiandrindra sy amin'ny mpikambana hafa",
'revdelete-radio-same'        => '(aza ovaina)',
'revdelete-radio-set'         => 'Eny',
'revdelete-radio-unset'       => 'Tsia',
'revdelete-suppress'          => "Manitrika ny votoatiny ho an'ny mpandrindra",
'revdelete-unsuppress'        => "Hanala ny fepetra eo amin'ny santiôna naverina",
'revdelete-log'               => 'Antony :',
'revdelete-submit'            => "Ataovy amin'ny version nosafidiako",
'revdelete-logentry'          => "nanova ny fahitan'ny/i [[$1]]",
'logdelete-logentry'          => "nanova ny fahitan'ny zava-mitranga ny/n'i [[$1]]",
'revdelete-success'           => "'''Voaova soa aman-tsara ny fahitana ny santiôna.'''",
'revdelete-failure'           => "'''Ny fisehon'ity santiôna ity dia tsy afaka natao update'''
$1",
'logdelete-success'           => "'''Voaova soa aman-tsara ny fisehon'ny tatitr’asa.'''",
'logdelete-failure'           => "'''Tsy afaka novaina ny fisehon'ny tatitr’asa'''
$1",
'revdel-restore'              => 'Ovay ny fahitàna',
'revdel-restore-deleted'      => 'santiôna voafafa',
'revdel-restore-visible'      => 'santiôna hita',
'pagehist'                    => "Tantaran'ilay pejy",
'deletedhist'                 => 'Tantara voafafa',
'revdelete-content'           => 'votoatiny',
'revdelete-summary'           => "ambangovangon'ny fanovàna",
'revdelete-uname'             => 'anarana mpikambana',
'revdelete-restricted'        => "nametraka fanerena ho an'ny mpandrindra",
'revdelete-unrestricted'      => "fanerena nesorina tamin'ny mpandrindra",
'revdelete-hid'               => 'nanitrika ny $1',
'revdelete-unhid'             => "nanala fanitriana (hoan'(ny)) $1",
'revdelete-log-message'       => "$1 ho an'ny{{PLURAL:$2||}} $2",
'logdelete-log-message'       => "zava-miseho $1 amin'ny $2{{PLURAL:}}",
'revdelete-hide-current'      => "Tsi-fetezana tamin'ny zavatra voadaty tamin'ny $1 tamin'ny $2 : io ny reviziôna ankehitriny.
Tsy azo fafana izy.",
'revdelete-show-no-access'    => "Tsi-fetazana teo am-panehoana ny zavatra voadaty tamin'ny $1 tamin'ny $2 : izy io dia mitondra ny marika « voafetra ».
Tsy azo jerenao io.",
'revdelete-modify-no-access'  => "Tsi-fetezana teo am-panovana ny zavatra voadaty tamin'ny $1 tamin'ny $2 : izy io dia mitondra ny marika « voafetra ». Tsy azonao jerena io.",
'revdelete-modify-missing'    => "Tsi-fetezana teo am-panovana ny zavatra miaraka amin'ny marika ID $1 : tsy ao amin'ny banky angona izy !",
'revdelete-no-change'         => "'''Tandremo :''' ny zavatra voadaty tamin'ny $1 tamin'ny $2 dia efa manana ny parametatry ny fisehoana nangatahana.",
'revdelete-concurrent-change' => "Tsi-fetezana teo am-panovana ny zavatra voadaty tamin'ny $1 tamin'ny $2 : ny satany dia voaovan' olon-kafa tamin'ianao nanova azy.
Jereo ny tatitr'asa.",
'revdelete-only-restricted'   => "Tsi-fetezana teo am-panitrihana ny zavatra voadaty tamin'ny $1 tamin'ny $2 : tsy azonao fafana ireo zavatra ireo amin'ny mpandrindra raha tsy misafidy option famafana.",
'revdelete-reason-dropdown'   => '* Antom-pamafana matetika miasa
** Tsi-fanajana ny Copyright
** Fampahalalana tsy tokony apetraka eo',
'revdelete-otherreason'       => 'Antony hafa / antony miampy :',
'revdelete-reasonotherlist'   => 'Antony hafa',
'revdelete-edit-reasonlist'   => "Hanova ny anton'ny famafàna",
'revdelete-offender'          => 'Mpanao ilay reviziôna :',

# Suppression log
'suppressionlog'     => 'tatitr’asa momban’ny famafana pejy',
'suppressionlogtext' => "Ity ny lisitra ny famafàna sy ny fanakanana asitrika amin'ny mpandrindra.
Hijery ny [[Special:IPBlockList|lisitra ny adiresy IP sy mpikambana voasakana]] ho an'ny lisitra ny voaraoka sy ny fanakanana mbola miasa.",

# Revision move
'revisionmove'                 => "Hanisaka ny santiôna avy amin'ny « $1 »",
'revmove-legend'               => 'Atsofohy ny pejy tanjona sy ny antony',
'revmove-submit'               => "Akisaka ny revision mankany amin'ilay pejy nofidiana",
'revisionmoveselectedversions' => 'Ahisaka ny revision nofidiana',
'revmove-reasonfield'          => 'Antony :',
'revmove-titlefield'           => 'Pejy tanjona :',
'revmove-badparam-title'       => 'Diso ny parametatra',
'revmove-badparam'             => "Ny hatakao dia misy parametatra tsy ampy na tsy ara-dalàna. Mankanesa any amin'ny pejy taloha ary andramo fanindroany.",
'revmove-norevisions-title'    => 'Tsy mety ny revision tanjona',
'revmove-norevisions'          => "Tsy nisafidy revision iray na maro ianao mba hanao an'io tao io, na tsy misy koa ilay revision nambaranao ho tanjona.",
'revmove-nullmove-title'       => 'Diso ny lohateny',

# History merging
'mergehistory'                     => 'Atsonika ny tantara ny pejy',
'mergehistory-header'              => "Amin'ity pejy ity, afaka manonika santiônan'ny tantara pejy iaviana makany amina pejy vaovao ianao.
Marino raha manohy ny tantaram-pejy ity asa ity.",
'mergehistory-box'                 => 'Atsonika ny version ny pejy roa :',
'mergehistory-from'                => 'Pejy fiavina :',
'mergehistory-into'                => 'pejin-dresaka :',
'mergehistory-list'                => "Tatitr'asa momban'ny fanovana azo akambana",
'mergehistory-merge'               => "Ny santiona manaraka an'ny [[:$1]] dia afaka atambatra miaraka amin'ny [[:$2]]. Ampiasao ny tsanganan'ny bokotra radiô mba hanatambatra ny santiôna namboarina hatramin'ny daty natoro. Fantaharo tsara fa hamerina ity tsanganana ity ny fampiasana ny rohy fizorana.",
'mergehistory-go'                  => 'Hijery ny fanovàna mety hatsonika',
'mergehistory-submit'              => 'atsonika ny version',
'mergehistory-empty'               => 'tsy misy version azo hatambarana',
'mergehistory-success'             => "$3 version{{PLURAL:$3||s}} de [[:$1]] fusionnée{{PLURAL:$3||s}} dans [[:$2]].

$3 santiôna{{PLURAL:}} natsonika tamin'ny [[:$2]]",
'mergehistory-fail'                => "Tsy afaka manatambatra ny tantara(n'asa). Avereno checheo ny pejy sy ny daty.",
'mergehistory-no-source'           => "Tsy misy ny pejy avy amin'ny $1.",
'mergehistory-no-destination'      => 'Tsy misy ilay pejy tanjona $1.',
'mergehistory-invalid-source'      => 'Tokony manana lohateny azo ampiasaina ny pejy fiavina',
'mergehistory-invalid-destination' => 'Tsy maintsy manana lohateny azo ampiasaina ny pejy tanjona.',
'mergehistory-autocomment'         => "natambatra miarak'amin'ny [[:$2]]  [[:$1]]",
'mergehistory-comment'             => "natambatra miarak'amin'ny [[:$2]] ny/i [[:$1]] : $3",
'mergehistory-same-destination'    => 'Ny pejy iaviana sy ny pejy tanjona dia tsy mahazo mitovy',
'mergehistory-reason'              => 'Antony :',

# Merge log
'mergelog'           => 'Tatitr’asa momban’ny fitambarana',
'pagemerge-logentry' => "voatambatra tamin'ny [[$2]] [[$1]] (fanovàna hatramin'ny $3)",
'revertmerge'        => 'Saraho',
'mergelogpagetext'   => 'Ity ny lisitry ny fanambarana ny tantaram-pejy vao haingana',

# Diffs
'history-title'            => "Tantara ny endrik'i « $1 »",
'difference'               => "(Fahasamihafan'ny pejy)",
'lineno'                   => 'Andalana $1:',
'compareselectedversions'  => 'Ampitahao ireo version voafidy',
'showhideselectedversions' => 'Asehoy/asitriho ny santiôna nofidiana',
'editundo'                 => 'esory',
'diff-multi'               => '{{PLURAL:}}(Reviziôna anelanelany nasitrika $1)',

# Search results
'searchresults'                    => 'Valim-pikarohana',
'searchresults-title'              => "Valim-pikarohana ho an'ny « $1 »",
'searchresulttext'                 => "Jereo ny [[{{MediaWiki:Helppage}}|fanazavana fanampiny momba ny fikarohana eto amin'ny {{SITENAME}}]].",
'searchsubtitle'                   => "nitady lohatsoratra « '''[[:$1]]''' » ianao ([[Special:Prefixindex/$1|ny pejy rehetra manomboka amin'ny « $1 »]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|ny pejy rehetra manana rohy amin'ny « $1 »]])",
'searchsubtitleinvalid'            => "Nitady « '''$1''' » ianao",
'toomanymatches'                   => "Betsaka loatra ny isan'ny mitovy naverina, mametraha fangatahana hafa.",
'titlematches'                     => "Mifanitsy amin'ny lohatenin'ny lahatsoratra",
'notitlematches'                   => 'Tsy nahitana lohateny mifanaraka',
'textmatches'                      => "Mifanitsy amin'ny votoatin'ny pejy",
'notextmatches'                    => 'Tsy nahitana votoatim-pejy mifanaraka',
'prevn'                            => '{{PLURAL:$1|$1}} taloha',
'nextn'                            => '{{PLURAL:$1|$1}} manaraka',
'prevn-title'                      => 'Valim-pikarohana taloha $1{{PLURAL:}}',
'nextn-title'                      => 'Valim-pikarohana manaraka $1{{PLURAL:}}',
'shown-title'                      => 'Aseho valiny $1 isaky ny pejy iray{{PLURAL:}}',
'viewprevnext'                     => 'Hijery ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'                => 'Safidy mikasika ny fitadiavana',
'searchmenu-exists'                => "'''Misy pejy mitondra anarana « [[:$1]] » eto amin'ity wiki ity'''",
'searchmenu-new'                   => "'''Hanamboatra ny pejy « [[:$1|$1]] » eto amin'ity wiki ity !'''",
'searchhelp-url'                   => 'Help:Fanoroana',
'searchmenu-prefix'                => "[[Special:PrefixIndex/$1|Hitady pejy manomboka amin'io tovona io]]",
'searchprofile-articles'           => 'Pejy misy votoatiny',
'searchprofile-project'            => 'Pejy fanampiana sy pejy tetikasa',
'searchprofile-images'             => 'Multimedia',
'searchprofile-everything'         => 'Izy Rehetra',
'searchprofile-advanced'           => 'Fikarohana antsipirihany',
'searchprofile-articles-tooltip'   => "Hikaroka ao amin'ny $1",
'searchprofile-project-tooltip'    => "Hikaroka ao amin'ny $1",
'searchprofile-images-tooltip'     => 'Hikaroka rakitra multimedia',
'searchprofile-everything-tooltip' => "Hitady eraky ny tranonkala (miaraka amin'ny pejin-dresaka)",
'searchprofile-advanced-tooltip'   => "Hitady ny anaran-tsehatra ho an'ny fikarohana",
'search-result-size'               => '$1 ({{PLURAL:$2|teny|teny}} $2)',
'search-result-score'              => 'Fifanarahana : $1%',
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
'searcheverything-enable'          => "Hitady anatin'ny anaran-tsehatra rehetra:",
'searchrelated'                    => 'voadinika',
'searchall'                        => 'rehetra',
'showingresults'                   => "Omeo ny valiny{{PLURAL:$1||}} miisa hatramin'ny <b>$1</b> manomboka ny #<b>$2</b>.",
'showingresultsnum'                => 'Omeo ny valiny miisa <b>$3</b> manomboka ny #<b>$2</b>.{{PLURAL:||}}',
'nonefound'                        => "'''Fanamarihana''': ny mahatonga ny fikarohana tsy hahita vokany matetika dia ny
fampiasanao teny miasa matetika toy ny \"izay\" sy ny \"tsy\",
na ny fanomezanao teny mihoatra ny iray (ny pejy ahitana ny teny rehetra hokarohina
ihany no miseho amin'ny vokatry ny karoka).",
'search-nonefound'                 => 'Tsy nahitana valiny ilay fanontaniana.',
'powersearch'                      => 'Fitadiavana',
'powersearch-legend'               => 'Fikarohana havanana',
'powersearch-ns'                   => "Hitady anatin'ny anaran-tsehatra :",
'powersearch-redir'                => 'Ampiseho ny redirect',
'powersearch-field'                => 'Hitady',
'powersearch-togglelabel'          => 'Marihana:',
'powersearch-toggleall'            => 'Rehetra',
'powersearch-togglenone'           => 'Tsy misy',
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
'preferences'                   => 'Ny momba anao',
'mypreferences'                 => 'Ny safidiko',
'prefs-edits'                   => 'isa ny fanovàna :',
'prefsnologin'                  => 'Tsy tafiditra',
'prefsnologintext'              => '[[Special:UserLogin|Midira]] aloha izay vao manova ny mombamomba anao.',
'changepassword'                => 'Hanova tenimiafina',
'prefs-skin'                    => 'Endrika',
'skin-preview'                  => 'Tsipalotra',
'prefs-math'                    => 'Math',
'datedefault'                   => 'Tsy misy safidy',
'prefs-datetime'                => 'Daty sy ora',
'prefs-personal'                => 'Mombamomba anao',
'prefs-rc'                      => 'Vao niova',
'prefs-watchlist'               => 'Lisitry ny pejy arahana-maso',
'prefs-watchlist-days'          => "Isa ny andro haseho anatin'ny lisitra ny pejy arahana-maso",
'prefs-watchlist-days-max'      => '(7 andro fara-fahabetsany)',
'prefs-watchlist-edits'         => "Isa ny fanovana aseho eo amin'ny fanaraha-maso navelatra:",
'prefs-watchlist-edits-max'     => '(isa fara-fahabetsany : 1000)',
'prefs-watchlist-token'         => "token ho an'ny lisitry ny pejy arahi-maso:",
'prefs-misc'                    => 'Hafa',
'prefs-resetpass'               => 'Hanova tenimiafina',
'prefs-email'                   => 'Option ny imailaka',
'prefs-rendering'               => 'Fampisehoana',
'saveprefs'                     => 'Tehirizo',
'resetprefs'                    => 'Avereno',
'restoreprefs'                  => 'Averina ny reglages taloha',
'prefs-editing'                 => 'Fanovana',
'prefs-edit-boxsize'            => 'Hangeza ny varavarankely fanovàna',
'rows'                          => 'Filaharana :',
'columns'                       => 'Tsanganana/Tioba :',
'searchresultshead'             => 'Fikarohana',
'resultsperpage'                => "Isa ny valiny isakin'ny pejy :",
'contextlines'                  => "Isa ny andalana isakin'ny valiny :",
'contextchars'                  => 'Isa ny litera isaky ny andalana',
'recentchangesdays'             => "Isa ny andro ho ampiseho eo amin'ny fanovàna farany",
'recentchangesdays-max'         => '($1 andro{{PLURAL:$1||}} fara-faha betsany)',
'recentchangescount'            => "Isan'ny fanovana haseho (tsipalotra) :",
'prefs-help-recentchangescount' => "Misy ny fanovana farany, ny tantaram-pejy ary ny tatitr'asa.",
'prefs-help-watchlist-token'    => "Ny famenoana ity fampidiran-teny ity amina soramiafina iray dia hamoaka topaka RSS ho an'ny pejy arahinao.
Afaka mamaky ny lisitry ny pejy arahinao izay olona mahalala io soramiafina io, mifidiana sanda sarotra hita ianao noho izany.
Ity misy soramiafina navoaka amin'ny fomba kisendra afaka ampiasainao :
$1",
'savedprefs'                    => 'Voatahiry ny mombamomba anao.',
'timezonelegend'                => "Faritr'ora :",
'localtime'                     => 'Ora an-toerana',
'timezoneuseserverdefault'      => "Ampiasaina ny tenenin'i Mpikajy",
'timezoneuseoffset'             => 'Hafa (safidio ny faritra)',
'servertime'                    => "Oran'ny lohamilina",
'guesstimezone'                 => "
Fenoy araka ny datin'ny solosainan'ny mpitsidika",
'timezoneregion-africa'         => 'Afrika',
'timezoneregion-america'        => 'Amerika',
'timezoneregion-antarctica'     => 'Antarktika',
'timezoneregion-arctic'         => 'Arktika',
'timezoneregion-asia'           => 'Azia',
'timezoneregion-atlantic'       => 'Ranomasimbe Atlantika',
'timezoneregion-australia'      => 'Aostralia',
'timezoneregion-europe'         => 'Eoropa',
'timezoneregion-indian'         => 'Ranomasimbe Indianina',
'timezoneregion-pacific'        => 'Ranomasimbe Pasifika',
'allowemail'                    => "Ekeo ny handraisana imailaka avy amin'ny mpikambana hafa",
'prefs-searchoptions'           => 'Option ny fikarohana',
'prefs-namespaces'              => "Toeran'anarana",
'defaultns'                     => "Fikarohana tsipalotra anatin'ireo anaran-tsehatra ireo :",
'default'                       => 'tsipalotra',
'prefs-files'                   => 'Rakitra',
'prefs-custom-css'              => 'CSS manokana',
'prefs-custom-js'               => 'Javascript manokana',
'prefs-reset-intro'             => "Azonao ampiasaina ity pejy ity mba hamerina ny safidinao amin'izay safidy tsipalotr'ilay sehatra. Tsy azo averina io.",
'prefs-emailconfirm-label'      => 'Famarinana ny imailaka :',
'prefs-textboxsize'             => "Hangezan'ny varavarankely fanovana",
'youremail'                     => 'Imailaka:',
'username'                      => 'Solonanarana:',
'uid'                           => "Laharan'ny mpikambana:",
'prefs-memberingroups'          => "mpikamban'ny gropy{{PLURAL:}} $1 :",
'prefs-registration'            => 'Daty fidirana :',
'yourrealname'                  => 'Tena anarana marina:',
'yourlanguage'                  => 'Tenim-pirenena:',
'yournick'                      => 'Anaram-bositra:',
'badsig'                        => 'Tsy mety io sonia io; hamarino ny kialo HTML.',
'badsiglength'                  => "Lava loatra ny sonianao. {{PLURAL:$1||}}
Tokony mba manana lohavy ambanimbany kokoa non'ny $1",
'yourgender'                    => 'lahy/vavy :',
'gender-unknown'                => 'Tsy voalaza',
'gender-male'                   => 'Lehilahy',
'gender-female'                 => 'Vehivavy',
'prefs-help-gender'             => "Ankifidy : Ampiasaina mba hifanaraka amin'ny lahi-vavy. Ho sarababem-bahoaka io fampahalalàna io.",
'email'                         => 'Imailaka',
'prefs-help-realname'           => "Anarana marina (afaka tsy fenoina): raha fenoinao ity dia hampiasaina hanomezana anao tambin'ny asa izay efainao eto.",
'prefs-help-email'              => "Imailaka (afaka tsy asiana): Hahafahan'ny olona mifandray aminao amin'ny alalan'ny pejinao manokana na ny pejin-dresakao, nefa tsy aseho azy ny anaranao.",
'prefs-help-email-required'     => 'Ilaina ny adiresy imailaka',
'prefs-info'                    => 'Fampahalalàna fototra',
'prefs-i18n'                    => 'Internasiônalizasiôna',
'prefs-signature'               => 'Sonia',
'prefs-dateformat'              => 'Endriky ny daty',
'prefs-timeoffset'              => 'Elanelana ora',
'prefs-advancedediting'         => 'Antsipirihan-tsafidy',
'prefs-advancedrc'              => 'Antsipirihan-tsafidy',
'prefs-advancedrendering'       => 'Antsipirihan-tsafidy',
'prefs-advancedsearchoptions'   => 'Antsipirihan-tsafidy',
'prefs-advancedwatchlist'       => 'Antsipirihan-tsafidy',
'prefs-displayrc'               => 'safidim-tseho',
'prefs-diffs'                   => 'Diff',

# User rights
'userrights'                  => 'Fandrindràna ny fahazoan-dàlana',
'userrights-lookup-user'      => 'Handrindra vondrom-pikambana',
'userrights-user-editname'    => 'Manomeza solonanarana:',
'editusergroup'               => "Hanova satan'ny mpikambana",
'userrights-editusergroup'    => 'Hanova vondrom-pikambana',
'saveusergroups'              => 'Tehirizo ny vondrom-pikambana',
'userrights-groupsmember'     => "Mpikambana amin'ny vondrona:",
'userrights-reason'           => 'Antony :',
'userrights-no-interwiki'     => "Tsy manana alalana manova ny alalan'ny mpikambana eny amin'ny wiki hafa ianao.",
'userrights-nodatabase'       => 'Tsy eto akaiky na tsy misy ny banky angona « $1 ».',
'userrights-nologin'          => "Tsy maintsy [[Special:UserLogin|miditra]] ary manana kaontim-pandrindra ianao raha hanova ny alalan'ny mpikambana.",
'userrights-notallowed'       => "Tsy manana alalana manova ny alalan'ny mpikambana ny kaontinao.",
'userrights-changeable-col'   => 'Ny gropy azonao ovaina',
'userrights-unchangeable-col' => 'Ny gropy tsy azonao ovaina',

# Groups
'group'               => 'Gropy :',
'group-user'          => 'Mpikambana',
'group-autoconfirmed' => 'Mpikambana voamarina',
'group-bot'           => 'Mpikambana rôbô',
'group-sysop'         => 'Mpandrindra',
'group-bureaucrat'    => 'Borōkraty',
'group-suppress'      => 'Mpitondra',
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
'grouppage-suppress'      => '{{ns:project}}:Oversight',

# Rights
'right-read'                  => 'Mamaky ny pejy',
'right-edit'                  => 'Manova ny pejy',
'right-createpage'            => 'Manamboatra pejy (tsy pejin-dresaka)',
'right-createtalk'            => 'Mamorona pejin-dresaka',
'right-createaccount'         => 'Manamboatra kaonty',
'right-minoredit'             => 'Marihana ho fanovana madinika',
'right-move'                  => 'Manakisaka pejy',
'right-move-subpages'         => "Manakisaka pejy miarak'amin'ny zana-pejiny",
'right-move-rootuserpages'    => "Mamindra ny renipejin'ny mpikambana",
'right-movefile'              => 'Manova anarana rakitra',
'right-suppressredirect'      => "Afaka tsy manometraka redirect avy amin'ny lohateny fiavina",
'right-upload'                => 'Mampidi-drakitra',
'right-reupload'              => 'Manolo rakitra efa misy',
'right-reupload-own'          => "Manolo rakitra nampidirin'ny tena",
'right-reupload-shared'       => 'Manolo eo an-toerana rakitra misy eo amina petra-drakitra iraisana',
'right-upload_by_url'         => "Mampidi-drakitra avy amin'ny adiresy URL",
'right-purge'                 => 'Fafàna ny cache ny pejy, tsy mila marihana',
'right-autoconfirmed'         => 'Manova pejy voaaro an-tapaka',
'right-bot'                   => 'Atao hita otra ny fizorana mande hoazy',
'right-nominornewtalk'        => "Tsy alefa ny fampandrenesana ''hafatra vaovao'' rehefa manao fanovana kely ao anatin'ny pejin-dresan'ny mpikambana.",
'right-apihighlimits'         => "Mampiasa fepetra ambonimbony kokoa amin'ny fangatahana API",
'right-writeapi'              => 'Mampiasa ny API fifanovana ny wiki',
'right-delete'                => 'Mamafa pejy',
'right-bigdelete'             => 'Mamafa pejy manana tantara be',
'right-deleterevision'        => 'Mamafa ny version manokana-na pejy iray',
'right-deletedhistory'        => 'Mijery ny tantaram-pejy voafafa fa tsy lahatsorany',
'right-deletedtext'           => "Mijery ny lahatsoratra voafafa sy ny fampitahana anelanelan'ny santiôna voafafa",
'right-browsearchive'         => 'Mitady pejy voafafa',
'right-undelete'              => 'Mamerina pejy voafafa',
'right-suppressrevision'      => "Mandinika sy mamerina ny version asitrika amin'ny mpandrindra",
'right-suppressionlog'        => 'Mijery ny tao tsy sarababem-bahoaka',
'right-block'                 => 'Manakana ny mpikambana mba tsy hanova',
'right-blockemail'            => 'Manakana ny mpikambana mba tsy handefa imailaka',
'right-hideuser'              => "Manakana ny mpikambana amin'ny alàlan'ny fanitrihana ny anarana",
'right-ipblock-exempt'        => 'Tsy afaka sakanana IP voasakana ny IP-ny',
'right-proxyunbannable'       => "Tsy voakasiky ny fanakana mande hoazy avy amin'ny proxy",
'right-unblockself'           => 'Miala hidy ho azy',
'right-protect'               => "Manova ny fiarovan'ny pejy sy manova ny pejy voaaro",
'right-editprotected'         => 'Manova ny pejy voaaro (tsy misy fiarovana en cascade)',
'right-editinterface'         => 'Manova ny interface ny mpikambana',
'right-editusercssjs'         => 'Manova ny rakitra CSS sy JS ny mpikambana hafa',
'right-editusercss'           => 'Manova ny rakitra CSS ny mpikambana hafa',
'right-edituserjs'            => "Manova ny rakitra JS an'ny mpikambana hafa",
'right-rollback'              => "Mamafa haingankaingana ny fanovan'ny mpandray anjara farany amina pejy manokana",
'right-markbotedits'          => "Manamarika ny fanovana voafafa hoatry ny nataon'ny rôbô.",
'right-noratelimit'           => 'Tsy voafetra ny isa',
'right-import'                => "Mampiditra na manafatra pejy avy amin'ny wiki hafa",
'right-importupload'          => 'mampiditra na manafatra pejy avy amina rakitra iray',
'right-patrol'                => "Manamarina ny fanovan'ny hafa",
'right-autopatrol'            => 'Manamarika ny fanovany efa nomarihana',
'right-patrolmarks'           => "Mijery ny mariky ny fanamarihana any amin'ny fanovana farany",
'right-unwatchedpages'        => 'Mijery ny lisitry ny pejy tsy arahina',
'right-trackback'             => 'Manampy trackback',
'right-mergehistory'          => 'Manatsonika ny tantaram-pejy',
'right-userrights'            => "Manova ny fahefan'ny mpikambana",
'right-userrights-interwiki'  => "Manova ny fahefan'ny mpikambana any amin'ny wiki hafa",
'right-siteadmin'             => 'Manidy sy manokatra ny banky angona',
'right-reset-passwords'       => "Manova ny tenimiafin'ny mpikambana hafa",
'right-override-export-depth' => "Mamoaka ny pejy miaraka amin'ny zana-pejy hatramin'ny ambaratonga fahadimy",
'right-sendemail'             => "Mandefa imailaka any amin'ny mpikambana hafa",
'right-revisionmove'          => 'Afindra ny revision',
'right-selenium'              => 'Hanao fanandramana Selenium',

# User rights log
'rightslog'      => 'Tatitr’asa momban’ny fanovana satam-pikambana',
'rightslogtext'  => "Ity ny tatitr'asa momban'ny fanovana zom-pikambana",
'rightslogentry' => "nanova ny fahefan'ny mpikambana « $1 », avy amin'ny $2 izy lasa $3",
'rightsnone'     => '(tsy misy)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'hamaky io pejy io',
'action-edit'                 => 'ovay ity pejy ity',
'action-createpage'           => 'hanao pejy',
'action-createtalk'           => 'hanao pejin-dresaka',
'action-createaccount'        => 'amboary io kaontim-pikambana io',
'action-minoredit'            => 'Mariho ho kely ity fanovana ity',
'action-move'                 => 'hamindra io pejy io',
'action-move-subpages'        => 'hamindra io pejy io sy ny zanapejiny',
'action-move-rootuserpages'   => "hanolo anaran'ny pejin'ny mpikambana",
'action-movefile'             => "manova anaran'ny rakitra iray",
'action-upload'               => 'hampiditra io rakitra io',
'action-reupload'             => 'Hanolo io rakitra efa misy io',
'action-reupload-shared'      => "manitsaka an-toerana rakitra misy any amin'ny petra-drakitra iraisana",
'action-upload_by_url'        => 'hampiditra io rakitra io avy amina adiresy URL',
'action-writeapi'             => 'hanova ny API fanoratana',
'action-delete'               => 'hamafa io pejy io',
'action-deleterevision'       => 'hamafa io version io',
'action-deletedhistory'       => "mijery ny tantara voafafa n'ity pejy ity",
'action-browsearchive'        => 'hitady pejy efa voafafa',
'action-undelete'             => 'hamerina io pejy io',
'action-suppressrevision'     => 'hijery sy hamerina io version nofafàna io',
'action-suppressionlog'       => 'hijery io tao tsy sarababem-bahoaka',
'action-block'                => 'manakana am-panoratana ny mpikambana iray',
'action-protect'              => "manova ny fanovàn'ity pejy ity",
'action-import'               => 'mampiditra ity pejy ity avy amina wiki hafa',
'action-importupload'         => 'hampiditra ity pejy ity avy amina rakitra nampidirina',
'action-patrol'               => 'marihana ho hita ity version ity',
'action-autopatrol'           => 'manana ny fanovanao voamarina',
'action-unwatchedpages'       => 'hijery ny lisitry ny pejy tsy arahina',
'action-trackback'            => 'mandefa trackback',
'action-mergehistory'         => 'Manatambatra ny tantaram-pejy',
'action-userrights'           => "hanova ny fahefan'ny mpikambana rehetra",
'action-userrights-interwiki' => "hanova ny fahefan'ny mpikambana any amin'ny wiki hafa",
'action-siteadmin'            => 'Manidy sy manokatra ny banky angona',
'action-revisionmove'         => 'hanisaka ny revision',

# Recent changes
'nchanges'                          => '{{PLURAL:$1|fanovana|fanovana}} $1',
'recentchanges'                     => 'Fanovana farany',
'recentchanges-legend'              => 'Safidy ny fanovàna farany',
'recentchangestext'                 => "Jereo eto amin'ity pejy ity izay vao niova vao haingana teto amin'ity wiki ity.",
'recentchanges-feed-description'    => "Arao ny fanovàna farany amin'ity wiki ity anaty topa",
'recentchanges-label-legend'        => 'Maribolana : $1.',
'recentchanges-legend-newpage'      => '$1 - pejy vaovao',
'recentchanges-label-newpage'       => 'Namorona pejy vaovao io fanovana io',
'recentchanges-legend-minor'        => '$1 - fanovana kely',
'recentchanges-label-minor'         => 'Kely fotsiny ity fanovana ity',
'recentchanges-legend-bot'          => "$1 - fanovana nataon'ny rôbô",
'recentchanges-label-bot'           => "Nataon'ny rôbô ity fanovana ity.",
'recentchanges-legend-unpatrolled'  => '$1 - fanovana mbola tsy nojerena',
'recentchanges-label-unpatrolled'   => 'Ity fanovana ity dia mbola tsy voamarina',
'rcnote'                            => "!Ity ny {{PLURAL:$1|fanovàna farany|fanovàna farany}} $1 natao nandritra ny <b>$2</b> andro, hatramin'ny $4 tamin'ny ora faha $5.",
'rcnotefrom'                        => "Ity eto ambany ity ny lisitry ny vao niova manomboka ny <b>$2</b> (hatramin'ny <b>$1</b> no miseho).",
'rclistfrom'                        => 'Asehoy izay vao niova manomboka ny $1',
'rcshowhideminor'                   => '$1 ny fanovàna kely',
'rcshowhidebots'                    => '$1 ny mpikambana rôbô',
'rcshowhideliu'                     => '$1 ny mpikambana nisoratra anarana',
'rcshowhideanons'                   => '$1 ny mpikambana tsy nisoratra anarana',
'rcshowhidepatr'                    => '$1 ny fanovana voaambina',
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
'newsectionsummary'                 => '/* $1 */ fizarana vaovao',
'rc-enhanced-expand'                => 'Jereo ny detail (mila JavaScript)',
'rc-enhanced-hide'                  => 'Asitriho ny adidiny sy ny antsipiriany',

# Recent changes linked
'recentchangeslinked'          => 'Novaina',
'recentchangeslinked-feed'     => 'Novaina',
'recentchangeslinked-toolbox'  => 'Novaina',
'recentchangeslinked-title'    => "Fanaraha-maso ny pejy miarak'amin'ny « $1 »",
'recentchangeslinked-noresult' => "Tsy nisy fanovana teo amin'ny pejy voarohy mandritry ny fotoana voafidy.",
'recentchangeslinked-summary'  => "Mampiseho ny fanovàna vao haingana ity pejy manokana ity. Voasoratra amin'ny '''sora-matavy''' ny lohaten'ny [[Special:Watchlist|pejy arahinao-maso]].",
'recentchangeslinked-page'     => 'anaram-pejy :',
'recentchangeslinked-to'       => "Ampisehoy ny fanovàn'ny pejy misy rohy makany amin'ny pejy fa tsy atao mivadika",

# Upload
'upload'                      => 'Handefa rakitra',
'uploadbtn'                   => 'Alefaso ny rakitra',
'reuploaddesc'                => "Miverena any amin'ny fisy fandefasan-drakitra.",
'upload-tryagain'             => "Hanfefa ny fanoritan'ny rakitra novaina",
'uploadnologin'               => 'Tsy niditra',
'uploadnologintext'           => 'Mila [[Special:UserLogin|misoratra anarana]] aloha vao afaka mandefa rakitra.',
'upload_directory_missing'    => "Ny petra-drakitra ampidiran-drakitra ($1) dia tsy misy ary tsy afaka namboarin'ny lohamilin-tranonkala.",
'upload_directory_read_only'  => "Ny répertoire ($1) handraisana ny rakitra alefan'ny mpikambana dia tsy afaka anoratana.",
'uploaderror'                 => 'Nisy tsy fetezana ny fandefasana rakitra',
'uploadtext'                  => "Ampiasao ity fisy ity handefasana rakitra. Jereo eto ny [[Special:FileList|lisitry ny rakitra]] nalefan'ny mpikambana, na koa azonao ampiasaina ny [[Special:Log/delete|tantaran'asan'ny fandefasana sy famonoana rakitra]].

Raha hanisy sary ao anaty pejy, dia mampiasà rohy toy ny iray amin'ireto
'''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:file.jpg]]</nowiki>''', na
'''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:file.png|alt text]]</nowiki>''' na
'''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:file.ogg]]</nowiki>''' raha hirohy mivantana amin'ny rakitra.",
'upload-permitted'            => 'Endriky ny rakitra manan-alalana : $1.',
'upload-preferred'            => 'Endriky ny rakitra nampidirina : $1',
'upload-prohibited'           => 'Endriky ny rakitra tsy manan-alalana : $1',
'uploadlog'                   => "Tatitr'asa momban'ny fandefasana rakitra",
'uploadlogpage'               => 'Fampidiran-drakitra',
'uploadlogpagetext'           => "Ity ny lisitr'ireo rakitra nalefa farany indrindra.",
'filename'                    => 'Anarana',
'filedesc'                    => 'Ambangovangony',
'fileuploadsummary'           => 'Ambangovangony:',
'filereuploadsummary'         => "Fanovan'ilay rakitra :",
'filestatus'                  => 'Sata ny opyright :',
'filesource'                  => 'Loharano:',
'uploadedfiles'               => 'Rakitra voaray',
'ignorewarning'               => 'Aza mihaino fampitandremana fa tehirizo foana ny rakitra.',
'ignorewarnings'              => 'Aza mihaino fampitandremana',
'minlength1'                  => 'Ny anaran-drakitra dia tokony manana litera iray fara-fahakeliny',
'illegalfilename'             => 'Misy litera tsy mety amin\'ny lohateny ny anaran\'ilay rakita "$1". Azafady soloy ny anaran\'ny rakitra dia andramo alefa indray.',
'badfilename'                 => 'Novana ho "$1" ny anaran\'ny rakitra.',
'filetype-mime-mismatch'      => "Ny karazan-drakitra dia tsy miady amin'ny karazana MIME.",
'filetype-badmime'            => 'Ny karazan-drakitra MIME « $1 » dia tsy afaka ampidirina.',
'filetype-bad-ie-mime'        => "Tsy afaka ampidirina ilay rakitra satria hitan'i Internet Explorer faha « $1 » izy, midika rakitra voarara satria mety mampidi-doza",
'filetype-unwanted-type'      => "Karazan-drakitra tsy tiana ny karazan-drakitra '''« .$1 »'''.
{{PLURAL:$3||}}Ny karazan-drakitra fidiana dia $2.",
'filetype-banned-type'        => "Rarana ato ny karazan-drakitra '''« .$1 »'''
{{PLURAL:$3||}}Ny karazan-drakitra ekena dia $2.",
'filetype-missing'            => 'Tsy manan-karazan-drakitra ilay rakitra (hoatry ny « .jpg » ohatra).',
'empty-file'                  => 'Tsy manam-botoatiny ny rakitra nalefanao.',
'file-too-large'              => 'Ngeza loatra ny rakitra nalefanao.',
'filename-tooshort'           => 'Fohy loatra ny anaran-drakitra.',
'filetype-banned'             => 'Voarara ato io karazan-drakitra io.',
'verification-error'          => "Tsy afaka amin'ny fanamarinana rakitra ity rakitra ity.",
'hookaborted'                 => "Najanon'ny faraingon'itatra ny fanovana nandramanao natao.",
'illegal-filename'            => "Tsy nahazoan-dàlana ny anaran'io rakitra io.",
'overwrite'                   => 'Tsy azo itsahina ny rakitra efa misy.',
'unknown-error'               => 'Nisy tsi-fetezana nitranga.',
'tmp-create-error'            => 'Tsy afaka mamorona rakitra miserana.',
'tmp-write-error'             => "Tsi-fetezana teo am-panoratana an'ilay rakitra miserana",
'large-file'                  => "Ny hangeza ny rakitra ampidirina dia tsy mahazo mihoatra ny $1 ; $2 ny lanjan'ilay rakitra tianao ho ampidirina.",
'largefileserver'             => "
Ngeza noho izay zakan'ny serveur io rakitra io.",
'emptyfile'                   => "Ohatry ny tsy misy na inona na inona ilay rakitra nalefanao teo.
Sao dia misy diso tsipelina ny anaran'ny rakitra? Azafady mba hamarino fa tena naniry handefa io rakitra io tokoa ianao.",
'fileexists'                  => "Efa misy rakitra manana io anarana io ato.
Mariho '''<tt>[[:$1]]</tt>''' raha mbola tsy te-hanova azy ianao.
[[$1|thumb]]",
'filepageexists'              => "Efa namboarina teto ny pejy mamisavisa ity rakitra ity '''<tt>[[:$1]]</tt>''', fa tsy misy rakitra mitondra io anarana io.
Ny ambangovangony ho ataonareo dia tsy hiseho eo amin'ny pejy famisavisana.
Mba hanao azy, tsy maintsy ovainao manokana ilay pejy [[$1|thumb]]",
'fileexists-extension'        => "Misy rakitra manana anarana mitovitovy : [[$2|thumb]]
* Anaran-drakitra ho ampidirina : '''<tt>[[:$1]]</tt>'''
* Anaran-drakitra misy : '''<tt>[[:$2]]</tt>'''
Misafidia anarana hafa.",
'fileexists-thumbnail-yes'    => "
Hoatry ny saritapaka ''(vignette)'' ilay sary. [[$1|thumb]]
Marino ilay rakitra '''<tt>[[:$1]]</tt>'''.
Raha mitovy amin'ny sary voalohany ny sarin'ilay rakitra marinina, tsy ilaina ny mampiditra santiôna nakelezina.",
'file-thumbnail-no'           => "Manomboka amin'ny '''<tt>$1</tt>''' ny anaran'ilay rakitra.
Mety saritapaka ''(vignette)'' io sary io.
Raha manana santiôna ilay rakitra ngezangeza noho io ianao, ampidiro ato ilay izy, raha tsy izany ovay ny anarany.",
'fileexists-forbidden'        => 'EEfa misy rakitra iray mitondra io anarana io ary tsy afaka itsahina ilay rakitra.
Raha mbola te-hampiditra ny rakitrao foana ianao, misaotra anao miverina any aoriana sy mampiasa anarana vaovao.
[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => "Efa misy rakitra mitondra io anarana io ao amin'ny petra-drakitra iraisana.
Raha mbola te-hampiditra io rakitra io foana ianao, miverena any aoriana ary mampiasà anarana hafa.
. [[File:$1|thumb|center|$1]]",
'file-exists-duplicate'       => "Ity rakitra ity dia mitovy amin'ny rakitra {{PLURAL:$1|||}} :",
'file-deleted-duplicate'      => "Efa voafafa ny rakitra mitovy amin'ity rakitra ity ([[$1]]). Tokony jerena any amin'ny tantaran'asan'ny famafana io pejy io alohan'ny mampiditra azy indray.",
'uploadwarning'               => 'Fampitandremana',
'uploadwarning-text'          => "Ovay ny fanoritan' ilay rakitra ary andrao fanindroany.",
'savefile'                    => 'Tehirizo ny rakitra',
'uploadedimage'               => 'tonga ny rakitra"[[$1]]"',
'overwroteimage'              => "nampiditra santiôna vaovao an'ny « [[$1]] »",
'uploaddisabled'              => 'Miala tsiny! Tsy azo atao ny mandefa rakitra.',
'copyuploaddisabled'          => "Tsy ampiasaina ny fandefasan-drakitra amin'ny alàlan'ny URL.",
'uploadfromurl-queued'        => 'Ao am-piandrasana ny fandefasan-drakitrao.',
'uploaddisabledtext'          => "Tsy afaka andefasana rakitra aloha eto amin'ity wiki ity.",
'php-uploaddisabledtext'      => "Ny fampidiran-drakitra dia tsy ampiasaina amin'ny PHP.
Marino ny option configuration file_uploads.",
'uploadscripted'              => "
Misy kialo HTML na fango script mety tsy ho hain'ny navigateur sasany haseho ity rakitra ity.",
'uploadvirus'                 => 'Misy viriosy io rakitra io! Toy izao ny antsipirihany: $1',
'upload-source'               => 'Rakitra fango',
'sourcefilename'              => "Anaran'ny rakitra:",
'sourceurl'                   => 'Loharano URL :',
'destfilename'                => "Anaran'ny rakitra:",
'upload-maxfilesize'          => 'Fetran-danja avo indrindra  : $1',
'upload-description'          => "Visavisa momban' ilay rakitra",
'upload-options'              => 'Safidim-pampidirana',
'watchthisupload'             => 'Araho maso ity rakitra ity',
'filewasdeleted'              => 'Efa nampidirina tato ary efa voafafa ny rakitra manana io anarana io.
Tokony marina ny $1 aloha ny manao fampidirana vaovao.',
'upload-wasdeleted'           => "'''Tandremo''' : Mamerina pejy efa voafafa ianao.''''

Marino raha tsara tohizana ny fanovana eto amin'ity pejy ity. Ny tantaran'asan'ny famafana pejy sy ny fanovan-toerana dia eo ambany :",
'filename-bad-prefix'         => "Ny anaran-drakitra ho ampidirinareo dia manomboka amin'ny '''« $1 »''', anarana omena an'ny fakan-tsary elektirônika.
Misafidia anaran-drakitra mambangovango.",
'upload-success-subj'         => 'Voaray soa aman-tsara ny rakitra',
'upload-success-msg'          => 'Eto ny rakitra nalefanao : [[:{{ns:file}}:$1]]',
'upload-failure-subj'         => 'Olana nitranga teo am-pandefasana',
'upload-failure-msg'          => "Nisy olana tamin'ny fampidiranao ($2) :

$1",
'upload-warning-subj'         => 'Fampitandremana rehefa mampiditra',

'upload-proto-error'        => 'Protokolina diso',
'upload-proto-error-text'   => "Mila URL manomboka amin'ny <code>http://</code> na <code>ftp://</code> ny fampidiran-drakitra.",
'upload-file-error'         => 'Tsy fetezana anatiny',
'upload-file-error-text'    => "Nisy tsi-fetezana anaty nitranga teo am-panamboarana rakitra miserana teo amin'ny lohamilina. Manorata any amin'ny [[Special:ListUsers/sysop|mpandrindra]].",
'upload-misc-error'         => 'Tsi-fetezana tsy fantatra teo am-pampidiran-drakitra',
'upload-misc-error-text'    => "Nisy tsi-fetezana tsy fantatra nitranga nandritry ny fampidirana.
Marino raha azo andehanana na misy ny URL ary manandrama indray.
Raha mbola misy foana ilay  olana, manorata any amin'ny [[Special:ListUsers/sysop|mpandrindra]].",
'upload-too-many-redirects' => "Be loatra ny fihodinan'ny URL.",
'upload-unknown-size'       => 'tsy fantatra ny hangeza',
'upload-http-error'         => 'Nisy tsy fetezana HTTP nitranga : $1',

# img_auth script messages
'img-auth-accessdenied' => 'Tsy azo aleha',
'img-auth-notindir'     => 'Ny lalana nangatahana dia tsy ny petra-drakitra nokaonfigiorena.',
'img-auth-badtitle'     => "Tsy afaka mamorona lohateny azo ampiasaina avy amin'ny « $1 ».",
'img-auth-nologinnWL'   => "Tsy mbola niditra ianao ary tsy ao amin'ny lisitra fotsy « $1 ».",
'img-auth-nofile'       => 'Tsy misy ny rakitra « $1 ».',
'img-auth-isdir'        => "Nanandrana nakao amin'ny petra-drakitra « $1 » ianao.
Ny petra-drakitra misy rakitra ihany no azo aleha.",
'img-auth-streaming'    => 'Vaky streaming « $1 ».',
'img-auth-public'       => "Ny asa ataon'i img_auth.php dia maneho ny rakitry ny wiki an'olona.
ity wiki ity dia no-regler-na ho sarababem-bahoaka.",
'img-auth-noread'       => "Tsy manana ny alalam-pamakiana ilay mpikambana eo amin'ny « $1 ».",

# HTTP errors
'http-invalid-url'      => 'URL diso : $1',
'http-invalid-scheme'   => "Tsy zaka ny URL miaraka amin'ny sema « $1 »",
'http-request-error'    => 'Tsi-fetezana tsy fantam-piaviana teo ampandefasana ilay hataka.',
'http-read-error'       => "Tsy fetezana momban'ny famakiana HTTP.",
'http-timed-out'        => 'Ny fangatahana HTTP dia efa lany daty.',
'http-curl-error'       => 'Tsi-fetezana teo am-pangalana ny URL : $1',
'http-host-unreachable' => 'URL tsy afaka andehanana',
'http-bad-status'       => 'Nisy tsi-fetezana teo ampandefasana ny hataka HTTP: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'URL tsy afaka andehanana',
'upload-curl-error6-text'  => 'Tsy afaka takarina ny URL nomena. Marino raha voasoratra tsara ny URL ary raha an-tranonkala ilay sehatra.',
'upload-curl-error28'      => 'Nihoatra ny fotoana fampidiran-drakitra',
'upload-curl-error28-text' => "Ela loatra ilay sehatra vao mamaly. Marino raha an-tranonkala ilay sehatra, miandraza kely ary avereno indray. Afaka mamerina amin'ny ora tsy itsidihana azy matetika ianao.",

'license'            => 'Lisansy:',
'license-header'     => "Navoaka tambanin'ny lisansy",
'nolicense'          => 'Tsy misy safidy',
'license-nopreview'  => '(Topi-maso tsy misy)',
'upload_source_url'  => " (URL misy ary azo vangian'ny daholobe)",
'upload_source_file' => " (rakitra eo amin'ny milinao)",

# Special:ListFiles
'listfiles-summary'     => "Ahitana ny rakitra rehetra nampidirina ity pejy manokana ity.
Napetraka eny amin'ny voalohandohany ny rakitra vao nampidirina.
Tsindrio eo amin'ny lohan-tsanganana raha hanova ny laharam-pisehoana.",
'listfiles_search_for'  => 'Hitady anarana media :',
'imgfile'               => 'rakitra',
'listfiles'             => 'Lisitry ny rakitra',
'listfiles_date'        => 'Daty',
'listfiles_name'        => 'Anarana',
'listfiles_user'        => 'Mpikambana',
'listfiles_size'        => 'Hangeza',
'listfiles_description' => 'Visavisa',
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
'filehist-thumbtext'        => "Vignette ho an'ny $1",
'filehist-nothumb'          => 'Tsy misy saritapaka',
'filehist-user'             => 'Mpikambana',
'filehist-dimensions'       => 'Hangeza',
'filehist-filesize'         => "Hangezan'ilay rakitra",
'filehist-comment'          => 'resaka',
'filehist-missing'          => 'Tsy ampy rakitra',
'imagelinks'                => "Fampiasan'io rakitra io",
'linkstoimage'              => "Ireto avy no {{PLURAL:$1|pejy mirohy|pejy mirohy}} ($1) amin'io rakitra io:",
'nolinkstoimage'            => "Tsy misy pejy mirohy amin'ity sary ity.",
'morelinkstoimage'          => "Hijery [[Special:WhatLinksHere/$1|rohy fanampiny]] makany amin'io rakitra io.",
'redirectstofile'           => "Ny rakitra fihodinana manaraka dia mitondra any amin'ity rakitra ity {{PLURAL:$1||}}:",
'sharedupload'              => "Mety ho rakitra itambarana amin'ny tetikasa hafa ny rakitra $1.",
'filepage-nofile'           => 'Tsy nahitana rakitra mitondra io anarana io.',
'filepage-nofile-link'      => 'Tsy misy rakitra mitondra io anarana io, fa afaka [$1 mampiditra azy ianao].',
'uploadnewversion-linktext' => "Andefa version vaovao n'ity rakitra ity",
'shared-repo-from'          => "avy amin'ny $1",
'shared-repo'               => 'petra-drakitra iraisana',

# File reversion
'filerevert'                => "Hamerinan'i $1",
'filerevert-legend'         => 'Hamerina ny $1',
'filerevert-intro'          => "Eo am-pamerenana ilay rakitra '''[[Media:$1|$1]]''' any amin'ny [$4 santiona tamin'ny $2 tamin'ny $3].",
'filerevert-comment'        => 'Dinika :',
'filerevert-defaultcomment' => "Voaverina ny santiônan'ny $1 tamin'ny $2",
'filerevert-submit'         => 'Hamerina',
'filerevert-badversion'     => "An-toerana, tsy misy santiôna nialoha io rakitra io miankina amin'ny daty voatoro.",

# File deletion
'filedelete'                  => 'Hamafa $1',
'filedelete-legend'           => 'Fafao ilay rakitra',
'filedelete-intro'            => "Ampamafana ny rakitra '''[[Media:$1|$1]]''' ianao miaraka amin'ny tantarany rehetra.",
'filedelete-intro-old'        => "Am-pamafana ny santiôna '''[[Media:$1|$1]]''' tamin'ny [$4 $2 tamin'ny $3].",
'filedelete-comment'          => 'Antony :',
'filedelete-submit'           => 'Hamafa',
'filedelete-success'          => "voafafa '''$1'''.",
'filedelete-success-old'      => "Voafafa ny santiônan'ny '''[[Media:$1|$1]]''' tamin'ny $2 tamin'ny $3.",
'filedelete-nofile'           => "Tsy misy '''$1'''.",
'filedelete-nofile-old'       => "Tsy nisy santiôna voatahirin'i '''$1''' miaraka amin'ny mahasamihafa naseho.",
'filedelete-otherreason'      => 'Antony fanampiny :',
'filedelete-reason-otherlist' => 'Antony hafa',
'filedelete-edit-reasonlist'  => 'Hanova ny antom-pamafàna',
'filedelete-maintenance'      => 'Ny famafana sy ny famerenan-drakitra dia tsy alefa mandritra ny fikojakojana.',

# MIME search
'mimesearch'         => 'Fikarohana MIME',
'mimesearch-summary' => "Ity pejy ity dia afahanao manalisitra ny rakitra azo jerena amin'ny alàlan' ity wiki ity arakaraka ny karazana votoatiny MIME ananany

Fampidirana : ''karazambotoatiny''/''zanakarazana'', ohatra par exemple <tt>sary/jpeg</tt>",
'mimetype'           => 'Karazana MIME :',
'download'           => 'Hampidina',

# Unwatched pages
'unwatchedpages' => 'Pejy voaaisotra ny fanaraha-maso azy',

# List redirects
'listredirects' => 'Lisitra ny fihodinana',

# Unused templates
'unusedtemplates'     => 'Endrika tsy miasa',
'unusedtemplatestext' => "Ity pejy ity dia manalisitra ny pejy rehetra ao amin'ny anaran-tsehatra « {{ns:template}} » ao tsy anaty pejy hafa.
Aza manadino manamarina raha tsy misy rohy makany amin'ny endrika hafa alohan'ny mamafa azy.",
'unusedtemplateswlh'  => 'rohy hafa',

# Random page
'randompage'         => 'Takelaka kisendra',
'randompage-nopages' => "Tsy misy pejy ao amin'ny anarant-tsehatra {{PLURAL:$1}} : $1.",

# Random redirect
'randomredirect'         => 'Pejy fihodinana kisendra',
'randomredirect-nopages' => "Tsy misy pejy fihodinana eo amin'ny anaran-tsehatra «$1»",

# Statistics
'statistics'                   => 'Statistika',
'statistics-header-pages'      => "Statistikan'ny pejy",
'statistics-header-edits'      => "Statistikan'ny fanovana",
'statistics-header-views'      => "Statistikan'ny tsidika",
'statistics-header-users'      => "Statistikan'ny mpikambana",
'statistics-header-hooks'      => 'statistika hafa',
'statistics-articles'          => 'Lahatsoratra',
'statistics-pages'             => 'Pejy rehetra',
'statistics-pages-desc'        => "Pejy rehetra eto amin'ity wiki ity: pejin-dresaka, redirect, sns.",
'statistics-files'             => 'Rakitra voaray',
'statistics-edits'             => 'Isan’ny fanovana hatry ny fisian’i {{SITENAME}}',
'statistics-edits-average'     => "Isan'ny fanovana isaky ny pejy",
'statistics-views-total'       => 'Tsidika',
'statistics-views-peredit'     => 'Tsidika isaky ny fanovana',
'statistics-users'             => '[[Special:ListUsers|Mpikambana]] nisoratra anarana',
'statistics-users-active'      => 'Mpikambana manova matetika',
'statistics-users-active-desc' => "Mpikambana nanao zavatra teto tanatin'ny $1 andro{{PLURAL:}}.",
'statistics-mostpopular'       => 'Pejy voatsidika',

'disambiguations'     => 'pejina homonimia',
'disambiguationspage' => 'Template:homonimia',

'doubleredirects'            => 'Fihodinana roa',
'double-redirect-fixed-move' => "Ity fihodinana ity, nanana ny tanjona [[$1]] novaina anarana, dia mitondra mankany amin'ny [[$2]].",
'double-redirect-fixer'      => 'Mpanitsy fihodinana',

'brokenredirects'        => 'Tapaka ny redirection',
'brokenredirectstext'    => "Mitondra makany amin'ny pejy tsy misy ireo fihodinana ireo :",
'brokenredirects-edit'   => 'ovao',
'brokenredirects-delete' => 'fafao',

'withoutinterwiki'         => 'Pejy tsy manan-drohi-piteny',
'withoutinterwiki-summary' => "Ireo pejy ireo dia tsy manan-drohy makany amin'ny fiteny hafa :",
'withoutinterwiki-legend'  => 'Tovona',
'withoutinterwiki-submit'  => 'Aseho',

'fewestrevisions' => 'Pejy vitsy mpanova',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|oktety|oktety}}',
'ncategories'             => '{{PLURAL:$1|vondrona|vondrona}} $1',
'nlinks'                  => '{{PLURAL:$1|rohy|rohy}} $1',
'nmembers'                => '{{PLURAL:$1|mpikambana|mpikambana}} $1',
'nrevisions'              => '{{PLURAL:$1|fanovana|fanovana}} $1',
'nviews'                  => 'Tsidika $1',
'specialpage-empty'       => 'Tsy misy valiny ho aseho.',
'lonelypages'             => 'Pejy manirery',
'lonelypagestext'         => "Ireo pejy ireo dia tsy voarohy sy tsy ampiasain'ny pejin' ity wiki ity.",
'uncategorizedpages'      => 'Pejy tsy voasokajy',
'uncategorizedcategories' => 'Sokajy tsy voasokajy',
'uncategorizedimages'     => 'Rakitra tsy voasokajy',
'uncategorizedtemplates'  => 'Endrika tsy voasokajy',
'unusedcategories'        => 'Sokajy tsy miasa',
'unusedimages'            => 'Rakitra tsy miasa',
'popularpages'            => 'Pejy maresaka',
'wantedcategories'        => 'Vondrona tokony hoforonina',
'wantedpages'             => 'Pejy tokony hoforonina',
'wantedpages-badtitle'    => "Lohateny tsy ekena amin'ny valiny : $1",
'wantedfiles'             => 'Rakitra tadiavina',
'wantedtemplates'         => 'Endrika tadiavina',
'mostlinked'              => "Misy firohizana betsaka amin'ny pejy hafa",
'mostlinkedcategories'    => "Misy firohizana betsaka amin'ny sokajy",
'mostlinkedtemplates'     => "Misy firohizana betsaka amin'ny endrika",
'mostcategories'          => 'Lahatsoratra misy sokajy betsaka indrindra',
'mostimages'              => "Misy firohizana betsaka amin'ny sary",
'mostrevisions'           => 'Lahatsoratra niova im-betsaka indrindra',
'prefixindex'             => "Pejy manomboka amin'ny...",
'shortpages'              => 'Pejy fohy',
'longpages'               => 'Pejy lavabe',
'deadendpages'            => 'Pejy tsy mirohy',
'deadendpagestext'        => "Tsy misy rohy mitondra makany amin'ny pejin'ny wiki hafa ireo pejy ireo.",
'protectedpages'          => 'Pejy voaaro',
'protectedpages-indef'    => 'Ny fiarovana maharitra ihany',
'protectedpages-cascade'  => 'Ny fanovana an-driana ihany',
'protectedpagestext'      => "Ny pejy manaraka dia voaaro amin'ny fanovana sy ny famindrana.",
'protectedpagesempty'     => 'Tsy misy pejy voaaro ankehitriny.',
'protectedtitles'         => 'Lohateny voaaro',
'protectedtitlestext'     => "Ny lohateny manaraka dia voaaro amin'ny famoronana",
'protectedtitlesempty'    => "Tsy misy lohateny voaaro miaraka amin'ireo mpihazaka ireo.",
'listusers'               => 'Lisitry ny mpikambana',
'listusers-editsonly'     => "Ny mpikambana manam-pandraisan'anjara ihany no aseho",
'listusers-creationsort'  => "Afantina amin'ny daty fanokafana",
'usereditcount'           => 'fanovana $1 {{PLURAL:}}',
'usercreated'             => "Voasokatra tamin'ny $1 tamin'ny $2",
'newpages'                => 'pejy Vaovao',
'newpages-username'       => 'Solonanarana:',
'ancientpages'            => 'Ireo pejy tranainy indrindra',
'move'                    => 'Hamindra azy toerana',
'movethispage'            => 'Afindrao ity pejy ity',
'unusedimagestext'        => "<p>Mariho tsara aloha fa mety misy sehatra hafa mampiasa ireto sary ireto
ka mety ho antony tokony hamelana azy eto izany na dia tsy miasa ato anatin'ity
wiki ity aza izy.</p>",
'unusedcategoriestext'    => 'Ireto sokajy manaraka ireto dia noforonina kanefa tsy misy pejy na dia iray aza mampiasa azy akory.',
'notargettitle'           => 'Tsy misy tanjona',
'notargettext'            => 'Tsy nofaritanao ny pejy na solonanarana mpikambana hanaovana io asa io.',
'nopagetitle'             => "Tsy misy pejy tanjona tahak' izany",
'nopagetext'              => 'Tsy misy ny pejy tanjona nolazainareo.',
'pager-newer-n'           => '$1 {{PLURAL:$1|vao haingana|vao haingana}}',
'pager-older-n'           => '$1 {{PLURAL:$1|taloha|taloha}}',
'suppress'                => 'Hitondra',

# Book sources
'booksources'               => 'boky tsiahy',
'booksources-search-legend' => "hikaroka anatin'ny boky todika",
'booksources-go'            => 'Ataovy lisitra',
'booksources-text'          => "Ity misy lisitra maneho ny rohy makany amin'ny sehatra mivarotra boky vaovao sy efa vaky ary mety ahitanao fampahalalàna momban'ny boky sy soratra notadiavinao :",
'booksources-invalid-isbn'  => 'Ny ISBN nomena dia mety diso ; marino raha diso ianao teo am-pandikanana ny loharano fotony.',

# Special:Log
'specialloguserlabel'  => 'Mpikambana:',
'speciallogtitlelabel' => 'Lohateny:',
'log'                  => 'Tatitr’asa',
'all-logs-page'        => 'Ny tatitr’asa',
'alllogstext'          => "Seho nakambana an'ny tatitr'asa rehetra azo jerena eo amin'ny {{SITENAME}}.
Azonao akelezina ny fahitana azy amin'ny alàlan'ny fisafidianana tatitr'asa iray manokana, anaram-pikambana iray na pejy iray (manasamihafa ny sorabaventy sy soramadinika ny rindrankajy).",
'logempty'             => 'Tsy nahitana.',
'log-title-wildcard'   => "Hitady amin'ny lohateny manomboka amin'io soratra io",

# Special:AllPages
'allpages'          => 'Pejy rehetra',
'alphaindexline'    => "$1 hatramin'ny $2",
'nextpage'          => 'Pejy manaraka ($1)',
'prevpage'          => 'Pejy taloha ($1)',
'allpagesfrom'      => 'Asehoy ny pejy manomboka ny:',
'allpagesto'        => "Asehoy ny pejy manomboka amin'ny :",
'allarticles'       => 'Lahatsoratra rehetra',
'allinnamespace'    => 'Pejy rehetra ($1 namespace)',
'allnotinnamespace' => "Ny pejy rehetra (tsy ao amin'ny $1 namespace)",
'allpagesprev'      => 'Aloha',
'allpagesnext'      => 'Manaraka',
'allpagessubmit'    => 'Alefa',
'allpagesprefix'    => "Asehoy ny pejy miantomboka amin'ny:",
'allpagesbadtitle'  => 'Tsy mety ny anaram-pejy : misy tovona iraisam-piteny na interwiki natokana, na misy soratra iray na maro tsy azo ampiasaina anaty anaram-pejy.',
'allpages-bad-ns'   => '{{SITENAME}} dia tsy manana anaran-tsehatra mitondra anarana « $1 ».',

# Special:Categories
'categories'                    => 'Sokajy',
'categoriespagetext'            => "Ireto no sokajy misy eto amin'ity wiki ity.",
'categoriesfrom'                => "Haneho ny sokajy manomboka amin'ny :",
'special-categories-sort-count' => "afantina amin'ny isan-javatra",
'special-categories-sort-abc'   => 'famantinana ara-abidy',

# Special:DeletedContributions
'deletedcontributions'             => "Fandraisan'anjara voafafa",
'deletedcontributions-title'       => "fandraisan'anjara voafafa",
'sp-deletedcontributions-contribs' => "fandraisan'anjara",

# Special:LinkSearch
'linksearch'       => 'Rohy ivelany',
'linksearch-pat'   => 'Volana tadiavina :',
'linksearch-ns'    => 'Anaran-tsehatra :',
'linksearch-ok'    => 'Fikarohana',
'linksearch-text'  => 'Ny soratra « joker » dia azo soratana ohatra <code>*.wikipedia.org</code>. br />
Prôtôkôly zaka : <tt>$1</tt>.',
'linksearch-line'  => "$1 dia voarohy amin'ny $2",
'linksearch-error' => "Ny soratra joker dia ampiasaina anatin'ny fanombohan'ny anaran-tsehatry ny milina hôte ihany.",

# Special:ListUsers
'listusersfrom'      => "Haneho ny mpikambana manomboka amin'ny :",
'listusers-submit'   => 'Aseho',
'listusers-noresult' => 'Tsy nahitana mpikambana.',
'listusers-blocked'  => '(voasakana)',

# Special:ActiveUsers
'activeusers'            => 'lisitra ny mpikambana miasa',
'activeusers-intro'      => "Ity ny lisitra ny mpikambana niasa teto tanatin'ny $1 andro farany {{PLURAL:}}",
'activeusers-from'       => 'Aseho ny mpikambana hatry ny :',
'activeusers-hidebots'   => 'Asitriho ny robo',
'activeusers-hidesysops' => 'Asitriho ny mpandrindra',
'activeusers-noresult'   => 'Tsy nahitana mpikambana.',

# Special:Log/newusers
'newuserlogpage'              => 'Tatitr’asan’ny fanokafana kaontim-pikambana',
'newuserlogpagetext'          => "Ity pejy ity dia maneho ny tantaran'asan'ny fampidirana mpikambana vaovao.",
'newuserlog-byemail'          => 'tenimiafina nalefa imailaka',
'newuserlog-create-entry'     => 'Mpikambana vaovao',
'newuserlog-create2-entry'    => 'namorona ny kaonty vaovao $1',
'newuserlog-autocreate-entry' => 'Kaonty namboarina ho azy',

# Special:ListGroupRights
'listgrouprights'                      => "Fahefan'ny vondrom-pikambana",
'listgrouprights-group'                => 'Vondrona/Gropy',
'listgrouprights-rights'               => 'Fahefana miaraka aminy',
'listgrouprights-helppage'             => "Help:Fahefan'ny vondrona",
'listgrouprights-members'              => '(lisitra ny mpikambana)',
'listgrouprights-addgroup'             => '{{PLURAL:$2}}Manampy ny mpikambana : $1',
'listgrouprights-removegroup'          => "Manala ny mpikambana {{PLURAL:$2}}amin'ny gropy : $1",
'listgrouprights-addgroup-all'         => 'Manampy mpikambana anaty vondrona rehetra',
'listgrouprights-removegroup-all'      => 'Manala mpikambana anaty gropy rehetra',
'listgrouprights-addgroup-self-all'    => "Manampy ny vondrom-pikambana rehetra amin'ny kaontiny",
'listgrouprights-removegroup-self-all' => "Manala ny vondrom-pikambana rehetra amin'ny kaontiny",

# E-mail user
'mailnologin'      => 'Tsy misy adiresy handefasana ny tenimiafina',
'mailnologintext'  => "Mila [[Special:UserLogin|miditra]] ianao sady manana imailaka mandeha sy voamarina ao amin'ny [[Special:Preferences|mombamomba anao]] vao afaka mandefa imailaka amin'ny mpikambana hafa.",
'emailuser'        => 'Andefaso imailaka io mpikambana io',
'emailpage'        => 'Andefaso imailaka io mpikambana io',
'emailpagetext'    => "Raha nametraka adiresy tena miasa tao amin'ny [[Special:Preferences|mombamomba azy io mpikambana io]],
dia ahafahana mandefa hafatra tokana ho any aminy ity fisy eto ambany ity.
Ny adiresy imailakao napetrakao tao amin'ny mombamomba anao no hiseho hoe
adiresin'ny mpandefa izany imailaka izany, koa afaka hovaliany izay hafatra alefanao.",
'usermailererror'  => "Misy tsy mety amin'ny lohatenin'ny imailaka:",
'defemailsubject'  => "imailaka avy amin'ny sehatra {{SITENAME}}",
'noemailtitle'     => 'Tsy misy adiresy imailaka',
'noemailtext'      => "Na tsy nanome adiresy imailaka voamarina io mpikambana io,
na tsy maniry handray imailaka avy amin'ny mpikambana hafa izy.",
'nowikiemailtitle' => 'Tsy manaiky imailaka alefa ho azy',
'nowikiemailtext'  => "Ity mpikambana ity dia te-hahazo imailaka avy amin'ny mpikambana hafa.",
'email-legend'     => "Handefa imailaka any amin'ny mpikambana hafa an'i {{SITENAME}}",
'emailfrom'        => "Avy tamin'i",
'emailto'          => "Ho an'i",
'emailsubject'     => 'Lohateny :',
'emailmessage'     => 'Hafatra',
'emailsend'        => 'Alefaso',
'emailccme'        => "Andefaso tahak' ity hafatra ity ahy.",
'emailccsubject'   => "Tahaka ny hafatrao nalefa tany amin'i $1 : $2",
'emailsent'        => 'Lasa',
'emailsenttext'    => 'Lasa soa aman-tsara ny imailaka nalefanao.',
'emailuserfooter'  => "Ity imailaka ity dia nalefan'i « $1 » tany amin'i « $2 » tamin'ny alalan'ny « Handefa Imailaka » an'i {{SITENAME}}.",

# Watchlist
'watchlist'            => 'Narahiko maso',
'mywatchlist'          => 'Pejy arahako-maso',
'watchlistfor'         => "(ho an'ny/i '''$1''')",
'nowatchlist'          => "Tsy misy n'inon'inona ao amin'ny lisitry ny pejy arahinao maso.",
'watchlistanontext'    => "Andana $1 ianao mba hahita na hanova zavatra ao amin'ny pejy arahanao.",
'watchnologin'         => 'Tsy tafiditra',
'watchnologintext'     => 'Mila [[Special:UserLogin|miditra/misoratra anarana]] aloha ianao vao afaka manova ny lisitry ny pejy arahinao maso.',
'addedwatch'           => "Tafiditra amin'ny lisitry ny pejy arahi-maso akaiky",
'addedwatchtext'       => "Tafiditra anatin'ny lisitry ny [[Special:Watchlist|Pejy arahanao maso]] ny pejy \"[[:\$1]]\".
Ny fanovana hisy amin'io pejy io sy ny pejin-dresaka miaraka aminy dia hiseho ao,
ary rehefa miseho ao amin'ny [[Special:RecentChanges|lisitry ny pejy vao niova]] io pejy io dia hatao ''matavy'' mba hahamora ny fahitana azy.

Aoriana, raha irinao ny hanaisotra azy ao amin'ny pejy arahanao maso, dia tsindrio ilay hoe \"aza arahi-maso intsony\" etsy amin'ny sisiny etsy.",
'removedwatch'         => "Voaaisotra tao amin'ny lisitry ny pejy arahi-maso",
'removedwatchtext'     => 'Voaaisotra tao amin\'ny lisitry ny [[Special:Watchlist|pejy arahinao maso]] ny pejy{{PLURAL:$1||}} "[[:$1]]".',
'watch'                => 'Hanara-maso',
'watchthispage'        => 'Araho maso ity pejy ity',
'unwatch'              => 'Aza arahi-maso intsony',
'unwatchthispage'      => 'Aza arahi-maso intsony',
'notanarticle'         => 'Tsy votoatim-pejy',
'notvisiblerev'        => 'Voafafa ilay santiôna',
'watchnochange'        => 'Tsy misy niova nandritra ny fotoana voafaritra ny pejy arahinao maso.',
'watchlist-details'    => 'Lisitra ny pejy{{PLURAL:$1||}} $1 arahanao-maso, tsy miisa eto ny pejin-dresaka.',
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

'enotif_mailer'                => "Fomba fampandrenesana an-imailak'i {{SITENAME}}",
'enotif_reset'                 => 'Mariho efa voavaky ny pejy rehetra',
'enotif_newpagetext'           => 'Pejy vaovao ity pejy ity.',
'enotif_impersonal_salutation' => "Mpikamban'i {{SITENAME}}",
'changed'                      => 'niova',
'created'                      => 'voaforona',
'enotif_subject'               => '$CHANGEDORCREATED $PAGEEDITOR ny pejy $PAGETITLE tao amin\'ny {{SITENAME}}',
'enotif_lastvisited'           => "Jereo eto $1 ny niova rehetra hatramin'ny fitsidihanao farany.",
'enotif_lastdiff'              => 'Jereo $1 mba ahitana ireo fanovana ireo.',
'enotif_anon_editor'           => 'mpikambana tsy nisoratra anarana $1',
'enotif_body'                  => '$WATCHINGUSERNAME,

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
'deletepage'             => 'Hamafa ny pejy',
'confirm'                => 'Antero',
'excontent'              => "votoatiny: '$1'",
'excontentauthor'        => "votoatiny: '$1' (ary i '[[Special:Contributions/$2|$2]]' irery ihany no nikitika azy)",
'exbeforeblank'          => "Talohan'ny namafana ny votoatiny : « $1 »",
'exblank'                => 'tsy nisy na inona na inona ilay pejy',
'delete-confirm'         => 'Hamafa ny « $1 »',
'delete-legend'          => 'Fafao',
'historywarning'         => 'Fampitandremana: Manana tantara io pejy hofafanao io (izany hoe farafaharatsiny indray mandeha niova):',
'confirmdeletetext'      => "Handeha hamafa tanteraka ny pejy na sary miaraka amin'ny tantarany rehetra
ao anatin'ny toby ianao. Azafady mba hamafiso fa irinao tokoa izany,
fantatrao ny vokany ary mahalala ianao fa tsy mifanipaka amin'ny
[[{{MediaWiki:Policy-url}}|fepetra]] izao ataonao izany.",
'actioncomplete'         => 'Vita ny asa',
'actionfailed'           => 'Tsy nandeha ny tao',
'deletedtext'            => 'Voafafa i "<nowiki>$1</nowiki>".
Jereo amin\'ny $2 ny lisitry ny famafana pejy faramparany.',
'deletedarticle'         => 'voafafa "[[$1]]"',
'suppressedarticle'      => 'namafa « [[$1]] »',
'dellogpage'             => 'Tatitr’asa momban’ ny famafàna pejy',
'dellogpagetext'         => 'Eto ambany eto ny lisitry ny famafana pejy/sary faramparany.',
'deletionlog'            => "famafana tatitr'asa",
'reverted'               => "Voaverina amin'ny votoatiny teo aloha",
'deletecomment'          => 'Antony :',
'deleteotherreason'      => 'antony hafa miampyy:',
'deletereasonotherlist'  => 'antony',
'delete-edit-reasonlist' => 'Hanova ny antony amafana pejy',
'delete-toobig'          => 'Ity pejy  ity dia manana tantaram-panovana be, mihoatra ny santiôna {{PLURAL:$1}} $1.
Ny famafana ireo pejy ireto dia voafetra mba tsy hikorontana {{SITENAME}}.',

# Rollback
'rollback'          => 'Foano indray ilay fanovana',
'rollback_short'    => 'Aza ovaina indray',
'rollbacklink'      => 'foano',
'rollbackfailed'    => "Tsy voaverina amin'ny teo aloha",
'cantrollback'      => "Tsy afaka iverenana ny fanovana; ny mpanova farany ihany no tompon'ny pejy.",
'alreadyrolled'     => "Tsy afaka foanana ny fanovana ny pejy « [[:$1]] » nataon'i [[User:$2|$2]] ([[User talk:$2|Dinika]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]])

Efa nataon'i [[User:$3|$3]] ([[User talk:$3|dinika]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]) ny fanovana farany.",
'editcomment'       => "Toy izao no fanamarihana momba io fanovana io: \"''\$1''\".",
'revertpage'        => "Voafafa ny fanovana ny [[Special:Contributions/$2|$2]] ([[User talk:$2|Dinika]]); voaverina amin'ny votoatiny teo aloha nataon'i [[User:$1|$1]]",
'revertpage-nouser' => "Manala ny fanovana (nataon'ny anaram-pikambana nesorina), miverina any amin'ny santiona farany nataon'i  [[User:$1|$1]]",

# Edit tokens
'sessionfailure-title' => 'Tsi-fetezaka mikasika ny kaonty idirana',

# Protect
'protectlogpage'              => 'Tatitr’asa momban’ny fiarovana',
'protectlogtext'              => 'Eto ambany ny lisitry ny fiarovana/fanalana hidy ny pejy. Fanazavana fanampiny: jereo [[Special:ProtectedPages]].',
'protectedarticle'            => 'voaaro ny pejy "[[$1]]"',
'modifiedarticleprotection'   => "nanova ny haabo ny fiarovana ho an'ny « [[$1]] »",
'unprotectedarticle'          => 'voaala ny fiarovana an\'i "[[$1]]"',
'movedarticleprotection'      => "dia nanetsika ny parametatry ny fiarovana avy any amin'ny « [[$2]] » makany amin'ny « [[$1]] »",
'protect-title'               => 'Fiarovana an\'i "$1"',
'prot_1movedto2'              => 'Novana ho [[$2]] ilay takelaka [[$1]]',
'protect-legend'              => 'Fanekena ny fiarovana pejy',
'protectcomment'              => 'Antony :',
'protectexpiry'               => 'Daty fialàna :',
'protect_expiry_invalid'      => 'Tsy mety ilay daty fialàna.',
'protect_expiry_old'          => 'Efa lasa ilay daty fialàna.',
'protect-unchain-permissions' => 'Aitatra ny fomba fiarovana',
'protect-text'                => "Afaka jerenao na ovainao eto ny politikam-piarovana ny pejy '''<nowiki>$1</nowiki>'''.",
'protect-locked-blocked'      => "Tsy afaka ovanao ny sokajy ny fiarovana raha tsy mahazo manoratra ianao.
Ity ny sokajy ny pejy '''$1''' :",
'protect-locked-dblock'       => "Tsy afaka solona ny sokajy ny fiarovana satria ny voatohana ny fotom-pandraisana.
Ity ny reglajy ny pejy  '''$1'''",
'protect-locked-access'       => "Tsy manana alalana manova ny fiarovana ny pejy ianao.
Ity ny réglage ny pejy '''$1''' :",
'protect-cascadeon'           => "Voaaro ity pejy ity ankehitriny noho ny fisiany anatin'{{PLURAL:$1|ity pejy voaaro ity|ireo pejy voaaro ireo}} miaraka amin'ny « fiarovana an-driana » (protection en cascade). Azonareo ovaina ny fiarovan'ity pejy ity fa tsy ho voakasika ny fiarovana an-driana.",
'protect-default'             => 'Avela daholo ny mpikambana',
'protect-fallback'            => 'Mila manana sata « $1 »',
'protect-level-autoconfirmed' => 'Sakano ny mpikambana vaovao sy ny mpikambana tsy nisoratra anarana',
'protect-level-sysop'         => 'Sysops ihany',
'protect-summary-cascade'     => 'Fiarovana an-driana',
'protect-expiring'            => "Miala amin'ny $1",
'protect-expiry-indefinite'   => 'tsiefa',
'protect-cascade'             => "Miaro ny pejy ao anatin'ity pejy ity (cascading protection)",
'protect-cantedit'            => "Tsy afaka manolo ny sokaji-piarovan'ity pejy ity ianao satria tsy manana ny sata ilaina",
'protect-othertime'           => 'Daty hafa :',
'protect-othertime-op'        => 'daty hafa',
'protect-existing-expiry'     => "Datin'ny fanalana ilay sazy : $2 amin'ny $3",
'protect-otherreason'         => 'Antony hafa miampy :',
'protect-otherreason-op'      => 'Antony hafa na fanampiny',
'protect-dropdown'            => "*Anton'ny fiarovana
** Misy be mpanimba
** Misy be mpametraka spam
** Misy adim-panontana
** Misy olona maro no mandalo eo",
'protect-edit-reasonlist'     => 'Hanova ny antony famafana',
'protect-expiry-options'      => '1 ora:1 hour,1 andro:1 day,1 herinandro:1 week,tapa-bolana:2 weeks,1 volana:1 month,3 volana:3 months,6 volana:6 months,1 taona:1 year,mandrakizay:infinite',
'restriction-type'            => 'Sata ilaina :',
'restriction-level'           => 'Sokajy ny fanerena :',
'minimum-size'                => 'Hnageza fara-fahakeliny',
'maximum-size'                => 'Hangeza farany',
'pagesize'                    => '(oktety)',

# Restrictions (nouns)
'restriction-edit'   => 'Hanova',
'restriction-move'   => 'Hamindra',
'restriction-create' => 'Mamorona',
'restriction-upload' => 'Mampiditra',

# Restriction levels
'restriction-level-sysop'         => 'voaaro manontolo',
'restriction-level-autoconfirmed' => 'fiarovana an-tàpany',
'restriction-level-all'           => 'ambaratonga rehetra',

# Undelete
'undelete'                   => 'Jereo ny pejy voafafa',
'undeletepage'               => 'Hijery sy hamerina ny pejy efa voafafa',
'viewdeletedpage'            => 'Hijery ny pejy efa nofafana',
'undeletepagetext'           => "Ireto pejy ireto dia efa voafafa nefa mbola voatahiry ao amin'ny tahiry ihany,
ary mbola afaka averina, mandra-pifafan'ny tahiry. Mety ho voafafa matetitetika
ihany ny tahiry {{PLURAL:$1}}.",
'undelete-fieldset-title'    => 'Hamerina ny santiôna',
'undeleterevisions'          => "{{PLURAL:$1|fanovana|fanovana}} $1 voatahiry any amin'ny arsiva",
'undeletehistory'            => "
Raha averinao ity pejy ity dia hiverina hiaraka aminy koa ny tantaran'ny
fanovana rehetra natao taminy. Raha efa misy pejy mitondra io anarana io
noforonina taorian'ny namafana azy, dia hitambatra amin'ny tantaran'io
pejy vaovao io ny tantaran'ity pejy voafafa ity, fa tsy ho voafafa akory.",
'undeletehistorynoadmin'     => "Efa voafafa io lahatsoratra io. Ny antony namafana azy dia io miseho ambangovangony eo ambany eo io, miaraka amin'ny fampahalalana antsipirihany momba ny mpikambana nikitika io pejy io talohan'ny namafana azy. Ny votoatin'ny pejy izay efa nofafana ireo dia ny mpitantana ihany no afaka mahita azy ankehitriny.",
'undelete-revision'          => "Santiôna voafafa an'i $1 (santiôna tamin'ny $4 tamin'ny $5) nataon'i $3 :",
'undelete-nodiff'            => 'Tsy nahitana santiôna nialoha.',
'undeletebtn'                => 'Avereno!',
'undeletelink'               => 'Topi-maso/averina',
'undeleteviewlink'           => 'hijery',
'undeletereset'              => 'Hamerina',
'undeleteinvert'             => 'Hampifamaidika ny safidy',
'undeletecomment'            => 'Dinika :',
'undeletedarticle'           => 'namerina ny « [[$1]] »',
'undeletedrevisions'         => 'voaverina ny {{PLURAL:$1|fanovana|fanovana}} $1',
'undeletedfiles'             => 'rakitra voaverina $1 {{PLURAL:$1}}',
'cannotundelete'             => 'Tsy nandeha soa aman-tsara ilay famerenana ;
efa nisy mpikambana iray hafa angamba no namerina ilay pejy.',
'undelete-header'            => "Jereo ny [[Special:Log/delete|tatitr'asa mikasika ny famafàna]] rehefa hanalisitra ny pejy vao voafafa.",
'undelete-search-box'        => 'Hitady pejy voafafa',
'undelete-search-prefix'     => "Asehoy ny pejy manomboka amin'ny :",
'undelete-search-submit'     => 'Fikarohana',
'undelete-no-results'        => "Tsy nahitana pejy mitovy tanatin'ny tahirin'ny fafa.",
'undelete-filename-mismatch' => "Tsy afaka averina ny santiônan'ilay rakitra tamin'ny $1 : tsy mifanaraka ny anaran-drakitra.",
'undelete-bad-store-key'     => "Tsy mety averina ny santiônan'ilay rakitra tamin'ny $1 : mbola tsy tao ilay rakitra talohan'ny famafana.",
'undelete-cleanup-error'     => 'Tsy fetezana teo am-pamafana ilay rakitra an-tahiry tsy miasa « $1 ».',
'undelete-error-short'       => 'Tsi-fetezana teo am-pamerenana ilay rakitra : $1',
'undelete-error-long'        => 'Nisy tsi-fetezana nitranga teo am-pamerenana ilay rakitra :

$1',
'undelete-show-file-submit'  => 'Eny',

# Namespace form on various pages
'namespace'      => 'Anaran-tsehatra :',
'invert'         => 'Ampifamadiho ny safidy',
'blanknamespace' => '(fotony)',

# Contributions
'contributions'       => "Fandraisan'anjaran'ny mpikambana",
'contributions-title' => "Fandraisan'anjaran'i $1",
'mycontris'           => 'Ny nosoratako',
'contribsub2'         => "ho an'ny $1 ($2)",
'nocontribs'          => "Tsy misy fanovana mifanaraka amin'ireo critères ireo.",
'uctop'               => ' (loha)',
'month'               => "Tamin'ny volana (sy teo aloha) :",
'year'                => "Tamin'ny taona (sy teo aloha) :",

'sp-contributions-newbies'       => "Ny fandraisan'anjara ny mpikambana vaovao ihany no ampiseho eto",
'sp-contributions-newbies-sub'   => "Ao amin'ny kaonty vaovao",
'sp-contributions-newbies-title' => "Fandraisan'anjara ao amin'ny kaonty vaovao",
'sp-contributions-blocklog'      => 'Tatitr’asa momban’ny fanakanana',
'sp-contributions-deleted'       => "fandraisan'anjara voafafa",
'sp-contributions-logs'          => 'tatitr’asa',
'sp-contributions-talk'          => 'Dinika',
'sp-contributions-userrights'    => 'Fandrindràna ny fahazoan-dàlana',
'sp-contributions-search'        => "Hikaroka fandraisan'anjara",
'sp-contributions-username'      => 'Adiresy IP na anaram-pikambana :',
'sp-contributions-submit'        => 'Hikaroka',

# What links here
'whatlinkshere'            => 'Iza avy no mirohy eto',
'whatlinkshere-title'      => "Pejy makany amin'ny « $1 »",
'whatlinkshere-page'       => 'Pejy :',
'linkshere'                => "Ireto avy no pejy mirohy eto: '''[[:$1]]'''",
'nolinkshere'              => "Tsy misy pejy mirohy any amin'ny '''[[:$1]]'''.",
'isredirect'               => 'pejina redirekta',
'istemplate'               => 'fanometrahany',
'isimage'                  => 'rakitra mifatotra',
'whatlinkshere-prev'       => '$1 taloha{{PLURAL:$1||}}',
'whatlinkshere-next'       => '$1 manaraka{{PLURAL:$1||}}',
'whatlinkshere-links'      => '← rohy',
'whatlinkshere-hideredirs' => '$1 ny fihodinana',
'whatlinkshere-hidetrans'  => '$1 ny nampidirana',
'whatlinkshere-hidelinks'  => '$1 ny rohy',
'whatlinkshere-hideimages' => '$1 rakitra mirohy',
'whatlinkshere-filters'    => 'sivana',

# Block/unblock
'blockip'                         => 'Sakano ny mpikambana',
'blockip-title'                   => 'Hanakana ilay mpikambana',
'blockip-legend'                  => 'Sakano ny mpikambana',
'blockiptext'                     => "Ampiasao ity formulaire ity hisakanana ny fahazoan-dàlana hanoratra
ananan'ny adiresy IP iray na solonanarana iray.
Tokony ho antony fisorohana ny fisomparana ihany, ary mifanaraka amin'ny [[{{MediaWiki:Policy-url}}|fepetra]]
ihany no hanaovana ny fisakanana.
Fenoy etsy ambany ny antony manokana (ohatra, mitanisà pejy nosomparana).",
'ipaddress'                       => 'Adiresy IP',
'ipadressorusername'              => 'Adiresy IP na solonanarana',
'ipbexpiry'                       => 'Fahataperana',
'ipbreason'                       => 'Antony :',
'ipbreasonotherlist'              => 'Antony hafa',
'ipbanononly'                     => 'Hanakana ny mpikambana tsy nisoratra anarana ihany',
'ipbcreateaccount'                => 'Hanakana ny fanokafana kaonty',
'ipbemailban'                     => 'Hanakana ny fandefasana imailaka',
'ipbenableautoblock'              => "Manakana ny IP farany ampiasain'ity mpikambana ity, ary ny IP-ny taloha mety ho andramnay",
'ipbsubmit'                       => 'Sakano',
'ipbother'                        => 'Hafa',
'ipboptions'                      => '2 ora:2 hours,1 andro:1 day,3 andro:3 days,1 herinandro:1 week,2 herinandro:2 weeks,1 volana:1 month,3 volana:3 months,6 volana:6 months,1 taona:1 year,mandrakizay:infinite',
'ipbotheroption'                  => 'hafa',
'ipbotherreason'                  => 'Antony hafa na fanampiny :',
'ipbhidename'                     => "Hanitrika ny anaram-pikambana anatin'ny fanovana sy anaty lisitra",
'ipbwatchuser'                    => "Hanaraka ny pejim-pikambana sy pejin-dresak'ity mpikambana ity",
'ipballowusertalk'                => "Afahan'ity mpikambana ity manova ny pejin-dresany mandritry ny sakany",
'ipb-change-block'                => "Hanakana io mpikambana io amin'ireto parametatra ireto.",
'badipaddress'                    => 'Tsy mety ny adiresy IP (invalid)',
'blockipsuccesssub'               => 'Vita soa aman-tsara ny sakana',
'blockipsuccesstext'              => 'Voasakana i [[Special:Contributions/$1|$1]].
<br />Jereo ny [[Special:IPBlockList|lisitry ny IP voasakana]] raha hanala ny sakana efa misy.',
'ipb-edit-dropdown'               => 'Hanova ny antony fanakanana tsipalotra',
'ipb-unblock-addr'                => "Hanala ny sakan' i $1",
'ipb-unblock'                     => "Hanala ny sakan'ny mpikambana na adiresy IP",
'ipb-blocklist-addr'              => "Sakana efa misy ho an'i $1",
'ipb-blocklist'                   => 'Hijery ny sakana efa misy',
'ipb-blocklist-contribs'          => "Fandraisan'anjaran'i $1",
'unblockip'                       => "Esory ny sakana amin'io mpikambana io",
'unblockiptext'                   => "
Ampiasao ity fisy eto ambany ity hanalana ny sakana
mihatra amin'ny adiresy IP na solonanarana iray.",
'ipusubmit'                       => 'Esory ny sakana',
'unblocked'                       => "voaala ny sakan'i [[User:$1|$1]]",
'unblocked-id'                    => "Niala ny sakan'i $1",
'ipblocklist'                     => 'Lisitry ny adiresy IP sy mpikambana voasakana',
'ipblocklist-legend'              => 'Hitady mpikambana voasakana',
'ipblocklist-username'            => 'Anaram-pikambana na adiresy IP :',
'ipblocklist-sh-userblocks'       => '$1 ny fanakanan-kaonty',
'ipblocklist-sh-tempblocks'       => '$1 ny fanakanana miserana',
'ipblocklist-sh-addressblocks'    => '$1 ny fanakanana adiresy IP mitokana',
'ipblocklist-submit'              => 'Fikarohana',
'ipblocklist-localblock'          => 'Fanakanana eo an-toerana',
'blocklistline'                   => '$1, $2 nisakana $3 ($4)',
'infiniteblock'                   => 'mandrakizay',
'expiringblock'                   => "tapitra amin'ny $1 $2",
'anononlyblock'                   => 'mpikambana tsy nisoratra anarana ihany',
'noautoblockblock'                => 'fanakanana mande ho azy nesorina',
'createaccountblock'              => 'tsy mahazo manokatra kaonty',
'emailblock'                      => 'imailaka voasakana',
'blocklist-nousertalk'            => 'Tsy afaka manova ny pejin-dresany',
'ipblocklist-empty'               => 'Ny lisitra ny IP voasakana dia tsy misy votoatiny ankehitriny.',
'ipblocklist-no-results'          => 'Ilay adiresy IP na ilay mpikambana dia mbola tsy voasakana.',
'blocklink'                       => 'sakano',
'unblocklink'                     => 'esory ny sakana',
'change-blocklink'                => 'ovay ny fanakanana',
'contribslink'                    => "fandraisan'anjara",
'autoblocker'                     => "Voasakana satria ny adiresy IP-nao dia vao avy nampiasain'i \"[[User:\$1|\$1]]\". Ny anton'ny fanakanana dia: \"'''\$2'''\"",
'blocklogpage'                    => "Tantaran'ny sakana",
'blocklog-showlog'                => "Efa voasakana ity mpikambana ity taloha.
Eo ambany ny tatitr'asa momban'ny fanakanana.",
'blocklog-showsuppresslog'        => "Efa voasakana sy voasitrika ity mpikambana ity.
Eo ambany ny tatitr'asa momban'ny famafana.",
'blocklogentry'                   => 'voasakana i "[[$1]]" mandritra ny $2 ; antony : $3',
'reblock-logentry'                => "dia nanova ny parametatry ny sakan'i $1, tapitra amin'ny $2 ilay sakana ary ''$3'' ny antony",
'blocklogtext'                    => "Eto no ahitana ny tantaran'ny hetsika momba ny fisakanana sy ny fanafoanana fisakanana mpandray anjara.
Ireo adiresy IP voasakana ho azy dia tsy miseho eto. Jereo ao amin'ny [[Special:IPBlockList|lisitry ny IP voasakana]]
ny lisitry ny fisakanana sy fandrarana na tanteraka misy ankehitriny.",
'unblocklogentry'                 => "voaaisotra ny sakana an'i $1",
'block-log-flags-anononly'        => 'mpikambana tsy nisoratra anarana ihany',
'block-log-flags-nocreate'        => 'tsy mahazo manokatra kaonty',
'block-log-flags-noautoblock'     => 'fanakanana ny IP nesorina',
'block-log-flags-noemail'         => 'voarara ny fandefasana imailaka',
'block-log-flags-nousertalk'      => 'tsy azo ovainy ny pejin-dresany',
'block-log-flags-angry-autoblock' => 'fanakanan-tena notsaraina efa mandeha',
'block-log-flags-hiddenname'      => 'anaram-pikambana nasitrika',
'range_block_disabled'            => 'Tsy mandeha ny zo-mpandrindra mamorona fanakanana vondrona IP.',
'ipb_expiry_invalid'              => "Tsy mety ilay fotoana hahataperan'ny sakana.",
'ipb_expiry_temp'                 => 'tsy maintsy lalandava ny fanakanana anaram-pikambana nasitrika.',
'ipb_hide_invalid'                => 'Tsy afaka fafana io kaonty io ; hoatra ny manana fanovana maro loatra izy.',
'ipb_already_blocked'             => 'Efa voasakana « $1 »',
'ipb-needreblock'                 => '== Déjà bloqué ==
Efa voasakana i $1. Tianao ovaina ve ny parametatra ?',
'ipb-otherblocks-header'          => '{{PLURAL:$1}}sakana hafa',
'ipb_cant_unblock'                => 'Tsy fetezana : Marik ny fanakanana $1 tsy hita.
Mety efa natao angamba ny fanalana sakana.',
'ip_range_invalid'                => 'Tsy mety io IP io.',
'ip_range_toolarge'               => 'Ny fanidiana laharana IP ngeza nohonny /$1 dia tsy azo atao.',
'blockme'                         => 'Sakano ahy',
'proxyblocker'                    => 'Mpisakana proxy',
'proxyblocker-disabled'           => 'Tsy mandeha io asa io.',
'proxyblockreason'                => "Voasakana ny adiresy IP-nao satria adiresy proxy malalaka izy io. Azafady mba lazao amin'ny mpanome internet anao io olana io.",
'proxyblocksuccess'               => 'Vita.',
'sorbsreason'                     => "Voasokokajin'ny DNSBL ho ao anatin'ny proxy midanadana ny adiresy IP-nao.",
'sorbs_create_account_reason'     => "Voasokajy ho isan'ny proxy midanadana ao amin'ny DNSBL ny adiresy IP-nao. Ireo IP ireo dia ahiana ho fitaovana azon'ny mpandefa spam ampiasaina. Tsy afaka manokatra kaonty ianao.",
'cant-block-while-blocked'        => 'Tsy azo sakananao ny mpikambana hafa raha mbola voasakana ianao.',
'ipbblocked'                      => "Tsy afaka manala ny sakan'ny mpikambana hafa ianao, satria voasakana koa ianao",
'ipbnounblockself'                => 'Tsy afaka manala ny sakanao ianao',

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
'databasenotlocked'   => 'Tsy voaidy ny banky angona.',

# Move page
'move-page'                    => "Hanova anarana an'i $1",
'move-page-legend'             => 'Afindrao toerana ny pejy',
'movepagetext'                 => "Ampiasao ilay formulaire eo ambany eo mba hamindra azy toerana, voakisaka any amin'ny anarany ankehitriny ny tantarany. Lasa pejy-na redirection ilay pejy taloha, (manondro makany amin'ny anarany ankehitriny ilay pejy).
Afaka manao ''update'' ny redirect ianao.

Jereo koa fa '''tsy afaka''' akisaka ilay pejy ra mitovy anarana amin'ny pejy efa misy ny anarana ny anarana vaovaon'ilay pejy tianao akisaka, fa mety atao ihany io asa io ra tsy misy nininona ilay pejy. Afaka manolo anarana pejy efa manondro ny fihisiny taloha ianao ra diso ianao, fa tsy afaka ataonao no manitsaka pejy efa misy.

'''TANDREMO'''

Mety fanom-panona ihany ny mpitsidika ra be mpitsidika io pejy ovainao anarana io ;
Tsy maintsy fantatrao tsara ny vokany aloha no mitohy.",
'movepagetalktext'             => "Voasikaka koa ny pejin-dresak'ity pejy ity '''ra''' :

* Efa misy pejin-dresaka efa misy votoatiny amin'ilay anarana vaovao, na
* Ra ny ''décocher''-nao ilay kazy eo ambany.

Tokony ataonao rery io asa io (fusion)",
'movearticle'                  => 'Afindrao toerana ny pejy',
'movenologin'                  => 'Tsy mbola tafiditra ianao',
'movenologintext'              => 'Ny mpikambana nisoratra anarana sy [[Special:UserLogin|tafiditra]] ihany no afaka mamindra toerana takelaka.',
'movenotallowed'               => 'Tsy azo ovainao anarana ny pejy.',
'movenotallowedfile'           => 'Tsy mahazo ovainao anarana ny rakitra.',
'cant-move-user-page'          => "Tsy azo ovainao anarana ny renipejim-pikambana (any ivelan'ny zana-pejiny).",
'cant-move-to-user-page'       => 'Tsy azo ovainao ny manova anarana pejy makany amina pejim-pikambana (afatsy zana-pejy iray).',
'newtitle'                     => 'Lohateny vaovao',
'move-watch'                   => 'araho-maso ity pejy ity',
'movepagebtn'                  => 'Afindrao',
'pagemovedsub'                 => 'Voafindra ny pejy',
'movepage-moved'               => "voafindra tany amin'ny '''$2''' i '''$1'''",
'movepage-moved-redirect'      => "Voaforona ny fihodinana manketo any amin'ny anarany taloha.",
'movepage-moved-noredirect'    => "Tsy nasiana fihodinana (redirect) avy any amin'ny anarana taloha.",
'articleexists'                => 'Efa misy ny lahatsoratra mitondra io anarana io, na
tsy mety ny anarana nosafidianao.
Azafady mba misafidiana anarana hafa.',
'cantmove-titleprotected'      => "Tsy azonao afindra any amin'io anarana io ny rakitra satria ny famoronana pejy mitondra io lohateny io dia voaaro.",
'talkexists'                   => "
'''Tafafindra soa aman-tsara ny pejy, fa ny pejin-dresaka
miaraka aminy no tsy afaka nakisaka satria efa misy pejin-dresaka
mifanaraka amin'ilay anarana vaovao. Azafady mba atambaro izay pejin-dresaka izay.'''",
'movedto'                      => "voafindra any amin'ny",
'movetalk'                     => 'Afindrao any koa ny "pejin-dresaka", raha mety.',
'move-subpages'                => "Hanova ny anaranan'ny zana-pejy (hatramin'ny pejy miisa $1)",
'move-talk-subpages'           => "Hanova ny anaranan'ny zana-pejin'ny pejin-dresaka (hatramin'ny pejy miisa $1).",
'movepage-page-exists'         => 'Efa misy ny pejy $1 ary tsy afaka soloina ho azy.',
'movepage-page-moved'          => 'Voaova anarana lasa $2 ilay pejy $1.',
'movepage-page-unmoved'        => 'Tsy afaka novaina anarana $2 ilay pejy $1.',
'1movedto2'                    => 'Novana ho [[$2]] ilay takelaka [[$1]]',
'1movedto2_redir'              => 'Redirection: Novaina ho [[$2]] i [[$1]]',
'move-redirect-suppressed'     => 'fihodinana voafafa',
'movelogpage'                  => "Tatitr'asa momban'ny famindran-toerana",
'movelogpagetext'              => 'Lisitry ny pejy nafindra toerana.',
'movesubpage'                  => 'Zana-pejy{{PLURAL:$1||}} $1',
'movesubpagetext'              => 'Ity pejy ity dia manana zanapejy $1 miseho eo ambany. {{PLURAL:$1||}}',
'movenosubpage'                => 'Tsy manana zana-pejy ity pejy ity.',
'movereason'                   => 'Antony :',
'revertmove'                   => 'averina',
'delete_and_move'              => 'Ovay toerana dia fafao',
'delete_and_move_text'         => '==Mila fafàna==

Efa misy ny lahatsoratra hoe "[[:$1]]". Irinao ve ny hamafana azy mba hahafahana mamindra toerana ity lahatsoratra ity?',
'delete_and_move_confirm'      => 'Eny, fafao io pejy io',
'delete_and_move_reason'       => 'Fafao mba hamindrana toerana ny anankiray',
'selfmove'                     => 'Mitovy ny anarana taloha sy anarana vaovao; tsy afaka afindra ny pejy.',
'immobile-source-namespace'    => "Tsy afaka ovaina anarana ny pejy ao amin'ny anaran-tsehatra « $1 »",
'immobile-target-namespace'    => "Tsy afaka ovainao ny pejy makany amin'ny anaran-sehatra « $1 »",
'immobile-target-namespace-iw' => "Ny rohy interwiki dia tanjona tsy mety ho an'ny fanetsehana pejy.",
'immobile-source-page'         => 'Tsy azo ovaina anarana ity pejy ity.',
'immobile-target-page'         => "Tsy afaka ovaina anarana makany amin'io lohateny io ilay pejy.",
'imagenocrossnamespace'        => 'Tsy mety ovaina anarana makany amina anaran-tsehatra hafa afatsy rakitra ihany ny rakitra.',
'imagetypemismatch'            => "Tsy mifanaraka amin'ny karazany ny fanitaran'ity rakitra ity.",
'imageinvalidfilename'         => 'Diso ny anaran-drakitra tanjona',
'fix-double-redirects'         => "Hanao update ny fihodinana makany amin'ny lohateny fotony",
'move-leave-redirect'          => "Hamela fihodinana makany amin'ny lohateny vaovao",
'move-over-sharedrepo'         => "== Efa misy ilay rakitra ==
Efa misy ao amina petra-drakitra zaraina ny rakitra [[:$1]]. Raha ovaina anarana ity rakitra ity, dia tsy ho hita eto intsony ny rakitra eo amin'ny petra-drakitra zaraina.",
'file-exists-sharedrepo'       => "Efa ampaisain'ny rakitra iray ao amin'ny petra-drakitra iraisana io anarana io.
Anarana hafa omena.",

# Export
'export'            => 'Hanondrana pejy',
'exporttext'        => "Afaka manondrana ny lahatsoratra miaraka amin'ny tantaram-panovana ny pejy na vondrom-pejy maromaro ianao.
Aoriana dia mety hafaran'ny wiki iray mandeha amin'ny rindrankajy MediaWiki izany, na dia mbola tsy afaka
atao aza izany amin'izao fotoana izao.

Ny fomba fanondranana pejy dia, manomeza lohateny izay na maromaro eto amin'ny boaty ety ambany eto, lohateny iray isaky ny andalana,
ary safidio na ny votoatiny ankehitriny ihany no ilainao na miaraka amin'ny endriky ny pejy rehetra taloha, sy hoe ny votoatiny ankehitriny
miampy fampahalalana momba ny fanovana farany fotsiny ve sa miaraka amin'ny tantaran'ny fanovana rehetra.

Etsy amin'ny toerana farany dia afaka mampiasa rohy ihany koa ianao, ohatra [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] ho an'ny [[{{MediaWiki:Mainpage}}]].",
'exportcuronly'     => "Ny votoatiny ankehitriny ihany no haondrana fa tsy miaraka amin'ny tantarany iray manontolo",
'exportnohistory'   => "
----
'''Fanamarihana:''' nosakanana aloha ny fanondranana pejy miaraka amin'ny tantarany iray manontolo amin'ny alalan'ity fisy ity noho ny antony performance.",
'export-submit'     => 'Hamoaka',
'export-addcattext' => "Hanampy pejy ao amin'ny sokajy :",
'export-addcat'     => 'Hanampy',
'export-addnstext'  => "Hanampy pejy ao amin'ny anaran-tsehatra :",
'export-addns'      => 'Hanampy',
'export-download'   => 'Hitahiry azy anaty rakitra',
'export-templates'  => 'Ataovy ao ny endrika',
'export-pagelinks'  => "Ataovy ao any pejy mmirohy amin'y halalina :",

# Namespace 8 related
'allmessages'                   => 'Hafatry ny rindrankajy',
'allmessagesname'               => 'Anarana',
'allmessagesdefault'            => 'Dikan-teny tany am-boalohany',
'allmessagescurrent'            => 'Dikan-teny miasa ankehitriny',
'allmessagestext'               => "Ity no lisitry ny system messages misy eto amin'ity MediaWiki: namespace ity.",
'allmessagesnotsupportedDB'     => "Tsy mbola mandeha ny '''{{ns:special}}:Allmessages''' satria tsy mandeha koa ny '''\$wgUseDatabaseMessages'''.",
'allmessages-filter-unmodified' => 'Mbola tsy voaova',
'allmessages-filter-all'        => 'Rehetra',
'allmessages-filter-modified'   => 'Voaova',
'allmessages-language'          => 'Tenim-pirenena/fiteny :',
'allmessages-filter-submit'     => 'Alefa',

# Thumbnails
'thumbnail-more'           => 'Angedazina',
'filemissing'              => 'Tsy hita ny rakitra',
'thumbnail_error'          => 'Tsy fetezana eo am-panamboarana ilay saritapaka : $1',
'djvu_page_error'          => "Pejy DjVu any ivelan'ny fetra",
'djvu_no_xml'              => "Tsy afaka alaina ny XML ho an'ny rakitra DjVu",
'thumbnail_invalid_params' => 'Parametatry ny saritapaka tsy mety',
'thumbnail_dest_directory' => 'Tsy mety amboarina ilay petra-drakitra tanjona',
'thumbnail_image-type'     => 'Karazan-drakitra tsy zaka',
'thumbnail_image-missing'  => "Rakitra ohatran'ny tsy ao : $1",

# Special:Import
'import'                     => 'Hampidi-pejy',
'importinterwiki'            => 'fampidirana interwiki',
'import-interwiki-text'      => "Safidio ny wiki loharano ary ny lohatenin'ilay pejy tianao ampidirina eto.
Ho voatazona ao amin'ny tantara ny datin'ny santiôna sy ny anaran'ny mpandray anjara.
Ho voasoratra ao amin'ny [[Special:Log/import|tatitr'asa momban'ny fampidirana]] ny tao rehetra mikasika ny fampidirana pejy interwiki",
'import-interwiki-source'    => 'Wiki sy pejy fango :',
'import-interwiki-history'   => "Handika ny santiônan'ny tantaran'ity pejy ity",
'import-interwiki-templates' => 'Ataovy ao ny endrika rehetra',
'import-interwiki-submit'    => 'Hampiditra',
'import-interwiki-namespace' => 'Anaran-tsehatra tanjona :',
'import-upload-filename'     => 'Anaran-drakitra :',
'import-comment'             => 'Resaka :',
'importstart'                => 'Am-pampidirana ny pejy…',
'import-revision-count'      => '$1 santiôna{{PLURAL:$1||}}',
'importnopages'              => 'Tsy misy pejy ho ampidirana.',
'importfailed'               => "Tsy fetezan' ilay fampidirana : <nowiki>$1</nowiki>",
'importunknownsource'        => "Karazana tsy fantatra an'ilay fango ho ampidirina",
'importcantopen'             => 'Tsy mety sokafana ilay rakitra ho ampidirina',
'importbadinterwiki'         => 'Rohy interwiki tsy izy',
'importnotext'               => 'Tsy misy votoatiny',
'importsuccess'              => 'Tafiditra soa aman-tsara !',
'importhistoryconflict'      => "Misy ady hita ao amin'y tantaran-tsantiôna (nety nalefa io pejy io taloha).",
'importnosources'            => 'Mbola tsy voatono ny loharano interwiki fampidiram-pejy ary mbola tsy mandeha ny fandefasana tantaram-pejy.',
'importnofile'               => 'Tsy nisy rakitra fandefasana nalefa.',
'importuploaderrorsize'      => "Tsy tafiditra soa aman-tsara ilay rakitra.
Ambony nohon'ny lanja ahazoan-dalana ny lanjany.",
'importuploaderrorpartial'   => 'Tsy tafiditra tato ilay rakitra.
Singam-botoatiny fotsiny no nalefa.',
'importuploaderrortemp'      => 'Ny fandefasana ilay rakitra dia tsy nety.
Tsy hita ny rakitra miserana.',
'import-parse-failure'       => 'Tsy fetezana teo am-pandinihana ny XML ho ampidirina',
'import-noarticle'           => 'Tsy misy pejy ho ampidirina !',
'import-nonewrevisions'      => 'Efa nampidirina taloha daholo ny santiôna rehetra.',
'import-upload'              => 'Fandrefasana data XML',
'import-token-mismatch'      => "Very ny fampahalalàna momban'ny kaonty.
Avereno fanindroany.",
'import-invalid-interwiki'   => "Tsy afaka mampiditra avy any amin'ilay wiki nofidiana.",

# Import log
'importlogpage'             => "Tatitr'asa momban'ny fampidirana",
'importlogpagetext'         => "Fampidirana ara-pandraharahana ny pejy miaraka amin'ny tantaram-panvany avy any amin'ny wiki hafa.",
'import-logentry-upload'    => "nampiditra [[$1]] tamin'ny fampidiran-drakitra",
'import-logentry-interwiki' => "nampiditra $1 tamin'ny transwiki",

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Ny pejinao',
'tooltip-pt-anonuserpage'         => "Ny pejim-bikamban'ny IP andraisanao anjara",
'tooltip-pt-mytalk'               => 'Pejin-dresakao',
'tooltip-pt-anontalk'             => "Ny pejin-dresaka ho an'ny fandraisan' anjara tamin'ny alànan'ity IP ity",
'tooltip-pt-preferences'          => 'Ny safidinao',
'tooltip-pt-watchlist'            => 'Ny lisitra ny pejy arahanao-maso',
'tooltip-pt-mycontris'            => "Lisitra ny fandraisan'anjaranao",
'tooltip-pt-login'                => 'Tsara aminao no miditra na manoratra anarana, fa tsy voatery manoratra anarana ianao.',
'tooltip-pt-anonlogin'            => 'Tsara aminao no miditra na manoratra anarana, fa tsy voatery manoratra anarana ianao.',
'tooltip-pt-logout'               => 'Hidio',
'tooltip-ca-talk'                 => 'resaka momba io takelaka io',
'tooltip-ca-edit'                 => "Azonao atao no manova n'ity pejy ity.
Ampesao ny topi-maso aloha no mihatiry.",
'tooltip-ca-addsection'           => 'hanomboka fizaràna vaovao',
'tooltip-ca-viewsource'           => 'Voaaro ilay pejy. Fa afaka itanao ny voatotiny.',
'tooltip-ca-history'              => "Ny revision natao tamin'ity pejy ity",
'tooltip-ca-protect'              => 'Arovy ity pejy ity',
'tooltip-ca-unprotect'            => "Hanala ny fiarovan'ity pejy ity",
'tooltip-ca-delete'               => 'Fafao ity pejy ity',
'tooltip-ca-undelete'             => "Hamerina ny fanovana natao tamin'ity pejy ity talohan'ny famafany",
'tooltip-ca-move'                 => 'Ovay anarana ilay pejy',
'tooltip-ca-watch'                => "Ampio amin'ny lisitra ny pejy arahinao-maso ity pejy ity",
'tooltip-ca-unwatch'              => "Esory amin'ny pejy arahinao ity pejy ity",
'tooltip-search'                  => "Karoka amin'ny {{SITENAME}}",
'tooltip-search-go'               => "Mandana any amina pejy mitondra n'io anarana io ra misy.",
'tooltip-search-fulltext'         => "Tadiavo ny pejy misy an'io lahatsoratra io.",
'tooltip-p-logo'                  => 'Renpejy',
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
'tooltip-t-contributions'         => "Hijery ny lisitry ny fandraisan'anjara n'io mpikambana io",
'tooltip-t-emailuser'             => "Handefa imailaka any amin'io mpikambana io",
'tooltip-t-upload'                => 'Handefa sary na rakitra',
'tooltip-t-specialpages'          => 'Listry ny pejy manokana rehetra',
'tooltip-t-print'                 => 'Takelaka azo atonta printy',
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
'tooltip-recreate'                => 'Hamorona ilay pejy fanindroany raha efa voafafa izy',
'tooltip-upload'                  => 'Hanomboka ny fampidirana',
'tooltip-rollback'                => "Manala ny fanovan'ny mpikambana farany nanova azy ilay asa « foano » (Rollback) .",
'tooltip-undo'                    => "Manala n'io fanovàna io ilay rohy « esory ».
Mamerina ny version taloha io asa io ary afaka manometraka ny antony anatin'ny ambangovangony.",
'tooltip-preferences-save'        => 'Tehirizina ny safidy',
'tooltip-summary'                 => 'Atsofohy eo ambangovangony fohifohy',

# Stylesheets
'monobook.css' => "/* Ovay ity rakitra ity raha hampiasa takilan'angaly (stylesheet) anao manokana amin'ny wiki iray manontolo */",

# Metadata
'notacceptable' => "Tsy afaka manome données amin'ny format zakan'ny navigateur-nao ny serveur wiki.",

# Attribution
'anonymous'        => "Mpikambana {{PLURAL:}} tsy mitonona anarana eto amin'ny {{SITENAME}}",
'siteuser'         => '{{SITENAME}} mpikambana $1',
'anonuser'         => "ny mpikambana tsy nisoratra anarana $1 an'i {{SITENAME}}",
'lastmodifiedatby' => "Novain'i $3 tamin'ny $1 tamin'ny $2 ity pejy ity.",
'othercontribs'    => "Mifototra amin'ny asan'i $1.",
'others'           => 'hafa',
'siteusers'        => '{{SITENAME}} mpikambana $1',

# Spam protection
'spamprotectiontitle' => "Sivana mpiaro amin'ny spam",
'spamprotectiontext'  => "Voasakan'ny sivana mpiaro amin'ny spam ny pejy saika hotahirizinao. Mety ho anton'izany ny fisian'ny rohy mankany amin'ny sehatra ivelan'ity wiki ity.",
'spamprotectionmatch' => "Izao teny izao: $1 ; no nanaitra ny sivana mpiaro amin'ny spam",
'spambot_username'    => "Fanadiovana spam amin'ny alàlan'ny Mediawiki",
'spam_reverting'      => "Famerenana an'ilay santiôna farany tsy misy ny rohy mankany amin'ny $1",
'spam_blanking'       => "Voafotsy ny santiôna misy ny rohy mankany amin'ny $1",

# Info page
'infosubtitle'   => "Fampahalalàna ho an'ilay pejy",
'numedits'       => "Isan'ny fanovana (lahatsoratra): $1",
'numtalkedits'   => "Isan'ny resaka (pejin-dresaka): $1",
'numwatchers'    => "Isan'ny mpikambana manaraka ity pejy ity : $1",
'numauthors'     => "Isan'ny mpanova samihafa : $1",
'numtalkauthors' => "Isan'ny mpiresaka (fa tsy ny resaka) (pejin-dresaka): $1",

# Math options
'mw_math_png'    => 'Anamboary sary PNG foana',
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

# Patrolling
'markaspatrolleddiff'                 => 'Marihana ho voamarina',
'markaspatrolledtext'                 => 'Marihana ho hita sy voatsara',
'markedaspatrolled'                   => 'Voamarina',
'markedaspatrolledtext'               => "Ny santiôna voafidy an'ny [[:$1]] dia voamarika ho voamarina.",
'rcpatroldisabled'                    => "Tsy nalefa ny fanamarinana ao amin'ny fanovana farany.",
'rcpatroldisabledtext'                => 'Tsy atao ankehitriny ny fanamarinana ny pejy novaina farany.',
'markedaspatrollederror'              => 'Tsy afaka marihana ho voamarina',
'markedaspatrollederrortext'          => 'Tsy maintsy misafidy santiôna iray ianao mba hahafahanao manamarika azy ho voamarina.',
'markedaspatrollederror-noautopatrol' => 'Tsy azonao marihana ho voamarina ny fanovanao.',

# Patrol log
'patrol-log-page'      => "Tatitr'asa momban'ny fanovana voamarina",
'patrol-log-header'    => "Ity dia tatitr'asa momban'ny fanovana voamarina.",
'patrol-log-line'      => "nanamarika ny $1 an'i $2 ho voamarina $3",
'patrol-log-auto'      => '(mandeha ho azy)',
'patrol-log-diff'      => 'fanovana faha $1',
'log-show-hide-patrol' => "$1 tatitr'asa momban'ny santiôna voamarina",

# Image deletion
'deletedrevision'                 => "Fanovana an'i $1 taloha voafafa.",
'filedeleteerror-short'           => 'Tsi-fetezana teo am-pamafàna ilay rakitra : $1',
'filedeleteerror-long'            => 'Nisy tsi-fetezana nitranga teo am-pamafàna ilay rakitra :

$1',
'filedelete-missing'              => 'Ny rakitra « $1 » dia tsy afaka fafàna satria tsy misy izy.',
'filedelete-old-unregistered'     => "Ny santiôn'ilay rakitra voafidy « $1 » dia tsy ao amin'ny banky angona.",
'filedelete-current-unregistered' => "Ny rakitra voafidy « $1 » dia tsy ao amin'ny banky angona.",
'filedelete-archive-read-only'    => "Ny petra-drakitra fitehirizana « $1 » dia tsy afaka ovain'ny lohamilina.",

# Browsing diffs
'previousdiff' => '← Ilay fampitahana teo arina',
'nextdiff'     => 'fampitahana manaraka →',

# Media information
'mediawarning'         => "'''Fampitandremana''': Tsy azo antoka ho tsy misy viriosy ity rakitra ity, ahiana hanimba ny solosainao ny fandefasana azy.",
'imagemaxsize'         => "Ferana ny haben'ny sary ao amin'ny pejy famaritana ho:",
'thumbsize'            => "Haben'ny thumbnail",
'file-info-size'       => "($1 × $2 piksely, hangeza n'ilay rakitra : $3, endrika MIME : $4)",
'file-nohires'         => "<small>Tsy misy sary ngeza non'io</small>",
'svg-long-desc'        => '(rakitra SVG, hangeza $1 × $2 piksely, hangeza : $3)',
'show-big-image'       => 'Sary ngeza hangeza',
'show-big-image-thumb' => "<small>Hangezan'ity topi-maso ity : $1 × $2 piksely</small>",
'file-info-gif-looped' => 'miverimberina',

# Special:NewFiles
'newimages'       => 'Tahala misy ny rakitra vaovao',
'imagelisttext'   => 'Eto ambany ny lisitry ny rakitra $1 milahatra araka ny $2.',
'newimages-label' => "Anaran-drakitra (na singan'izy io) :",
'showhidebots'    => '(rôbô $1)',
'noimages'        => 'Tsy misy sary ato.',
'ilsubmit'        => 'Karohy',
'bydate'          => 'araka ny daty',

# Bad image list
'bad_image_list' => "Ity ny andrefiny :

Ny lisitra ny takelaka (andalana manomboka amin'ny *) ihany no mandanja.
Tokony sary tsy misy na sary tsy izy ny rohy voalohany anaty andalana iray .
''Exception'' ny rohy hafa anatin'ilay andalana iray, ohatra ny pejy mety mampiasa ilay sary.",

# Metadata
'metadata'          => 'Metadata',
'metadata-help'     => "Mirakitra fampahalalana fanampiny, izay inoana ho napetraky ny fakan-tsary na scanner nampiasaina nanaovana ny numérisation-ny ity rakitra ity. Raha kitihina na ovana izy ity dia mety tsy hifanitsy amin'ny sary voaova ireo antsipirihany sasany ireo.",
'metadata-expand'   => 'Asehoy ny antsipirihany',
'metadata-collapse' => 'Aza aseho ny antsipirihany',
'metadata-fields'   => "Hisy anatin'ny pejin-ambangovangon'ilay sary ny métadonnées ny EXIF rehefa nasitrika ny tabilao ny metafonnées, asitrika ny champ hafa.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength",

# EXIF tags
'exif-imagewidth'                => 'Halalaka',
'exif-imagelength'               => 'Haavo',
'exif-bitspersample'             => 'Bit isaky ny singa',
'exif-compression'               => 'Karazana fanakelezana',
'exif-photometricinterpretation' => 'Endrika kôlôrimetrika',
'exif-orientation'               => 'Todika',
'exif-samplesperpixel'           => 'Mpandahatra isaky ny piksely',
'exif-planarconfiguration'       => 'Fandaminana ny data',
'exif-ycbcrpositioning'          => 'Fipetraky ny Y sy C',
'exif-stripoffsets'              => "Toerana isian'ny datan'ny sary",
'exif-ycbcrcoefficients'         => 'Fatra YCbCr',
'exif-datetime'                  => 'Daty fanovana',
'exif-imagedescription'          => "Visavisa momban' ilay sary",
'exif-make'                      => 'Mpanamboatra ilay fakan-tsary',
'exif-model'                     => "Karazan'ilay fakan-tsary",
'exif-software'                  => 'Rindrankajy nampiasaina',
'exif-artist'                    => 'Mpaka azy',
'exif-copyright'                 => 'Mpanana ilay copyright',
'exif-exifversion'               => 'Santiôna EXIF',
'exif-flashpixversion'           => 'Santiôna FlashPix',
'exif-pixelydimension'           => 'Haavon-tsary ekena',
'exif-pixelxdimension'           => 'Halalan-tsary ekena',
'exif-makernote'                 => 'Diniky ny mpanamboatra',
'exif-usercomment'               => 'Diniky ny mpikambana',
'exif-relatedsoundfile'          => 'Rakitra audio miaraka',
'exif-exposuretime-format'       => '$1 s ($2 s)',
'exif-fnumber'                   => 'Isa F',
'exif-isospeedratings'           => 'ISO',
'exif-aperturevalue'             => 'Sanasana',

'exif-subjectdistance-value' => '$1 metatra',

'exif-meteringmode-0' => 'Tsy fantatra',
'exif-meteringmode-1' => 'Elanelana',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Avaratra',
'exif-gpslatitude-s' => 'Atsimo',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Atsinanana',
'exif-gpslongitude-w' => 'Andrefana',

'exif-gpsstatus-a' => 'Am-pandrefesana',

'exif-gpsmeasuremode-2' => 'Fandrefesana 2D',
'exif-gpsmeasuremode-3' => 'Fandrefesana 3D',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => "Kilometatra isak'ora",
'exif-gpsspeed-m' => "Maily isak'ora",
'exif-gpsspeed-n' => 'Knot',

# External editor support
'edit-externally'      => "Ovao amin'ny alalan'ny fampiasana fitaovana ivelan'ity Wiki ity io rakitra io",
'edit-externally-help' => "jereo any amin'[http://www.mediawiki.org/wiki/Manual:External_editors ny torolalana] ny fanazavana fanampiny,.",

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'rehetra',
'imagelistall'     => 'rehetra',
'watchlistall2'    => 'rehetra',
'namespacesall'    => 'rehetra',
'monthsall'        => 'rehetra',
'limitall'         => 'rehetra',

# E-mail address confirmation
'confirmemail'             => 'Fanamarinana adiresy imailaka.',
'confirmemail_noemail'     => "Tsy nilaza adiresy imailaka azo ampiasaina ianao tao amin'ny [[Special:Preferences|safidinao]].",
'confirmemail_text'        => "
Ity wiki ity dia mitaky anao hanamarina ny adiresy imailaka nomenao
vao mahazo mampiasa ny momba ny imailaka ianao. Ampiasao ity bokotra eto ambany ity
mba handefasana fango fanamarinana any amin'ny adiresinao. Ny hafatra voaray dia ahitana
rohy misy fango. Sokafy amin'ny navigateur-nao
io rohy io mba hanamarinana fa misy ny adiresy imailaka nomenao.",
'confirmemail_pending'     => 'Efa nandefasana imailaka misy ny tenimiafina fanamarinana ianao ;
raha vao nanokatra kaonty ianao, dia miandrasa minitra vitsivitsy mba ho tonga ilay imailaka aloha ny manontany tenimiafina vaovao.',
'confirmemail_send'        => 'Alefaso ny fanamarinana ny imailaka',
'confirmemail_sent'        => 'Lasa ny fanamarinana ny imailaka.',
'confirmemail_oncreate'    => "Nalefa tany amin'ny adiresy imailakao ny kaody fanamarinana.
Tsy ilaina ampaisaina io tenimiafina io rehefa hiditra eto amin'ity wiki ity ianao, fa tsy maintsy omenao ilay izy rehefa mampiasa tao mifototra amin'ny imailaka.",
'confirmemail_sendfailed'  => 'Tsy lasa ny fanamarinana ny imailaka. Hamarino ny adiresy fandrao misy litera tsy mety.',
'confirmemail_invalid'     => 'Tsy mety ilay fango fanamarinana. Angamba efa lany daty?',
'confirmemail_needlogin'   => 'Mila $1 ianao raha hanamarina ny adiresy imailakao.',
'confirmemail_success'     => 'Voamarina ny adiresy imailakao. Afaka miditra ianao ankehitriny dia mankafiza ny wiki.',
'confirmemail_loggedin'    => 'Voamarina ny adiresy imailakao ankehitriny.',
'confirmemail_error'       => 'Nisy tsy fetezana nandritra ny fanamarinana adiresy imailaka.',
'confirmemail_subject'     => "Fanamarinana adiresy imailaka avy amin'ny sehatra {{SITENAME}}",
'confirmemail_body'        => 'Nisy olona, izay ianao ihany angamba, avy tamin\'ny adiresy IP $1, nanokatra kaonty
"$2" tamin\'ity adiresy imailaka ity tao amin\'ny sehatra {{SITENAME}}.

Mba hanamarinana fa anao tokoa io adiresy io ary mba hahafahana mampiasa
imailaka ao amin\'ny {{SITENAME}}, dia mba sokafy ity rohy eto ambany ity:

$3

Raha *tsy* ianao no nanokatra io kaonty io, dia aza sokafana ilay rohy.
Io fango fanamarinana io dia miasa hatramin\'ny $4.',
'confirmemail_invalidated' => 'Fanamarinana ny adiresy imailaka najanona',
'invalidateemail'          => 'Manajanona ny fanamarinana ny adiresy imailaka',

# Scary transclusion
'scarytranscludedisabled' => '[Najanona ny atipetraka (transclusion) interwiki]',
'scarytranscludefailed'   => "[Ny voaaka soa aman-tsara ilay endrika ho an'i $1]",
'scarytranscludetoolong'  => '[Lava loatra ny URL]',

# Trackbacks
'trackbackbox'    => "Tirakibaky mankany amin'ity pejy ity :<br />
$1",
'trackbackremove' => '([$1 esorina])',
'trackbacklink'   => 'Tirakibaky',

# Delete conflict
'deletedwhileediting' => 'Fampitandremana: Nisy namafa ity pejy ity raha mbola teo am-panovana azy ianao!',
'confirmrecreate'     => "Nofafan'i [[User:$1|$1]] ([[User talk:$1|dinika]]) ity lahatsoratra ity taorian'ny nanombohanao nanova azy. Ny antony dia:
: ''$2''
Azafady hamafiso fa tena irinao averina hoforonina tokoa ity lahatsoratra ity.",
'recreate'            => 'Jereo indray',

# action=purge
'confirm_purge_button' => 'Eka',
'confirm-purge-top'    => "Fafana ve ny cache-n'ity pejy ity?",

# Multipage image navigation
'imgmultipageprev' => '← pejy nialoha',
'imgmultipagenext' => 'pejy manaraka →',
'imgmultigo'       => 'Andao !',
'imgmultigoto'     => "Handeha any amin'ny pejy $1",

# Table pager
'ascending_abbrev'         => 'mihak.',
'descending_abbrev'        => 'mihid.',
'table_pager_next'         => 'Pejy manaraka',
'table_pager_prev'         => 'Pejy nialoha',
'table_pager_first'        => 'Pejy voalohany',
'table_pager_last'         => 'Pejy farany',
'table_pager_limit'        => 'Haneho zavatra $1 isaky ny pejy',
'table_pager_limit_submit' => 'Hitsidika',
'table_pager_empty'        => 'Tsy nahitana valiny',

# Auto-summaries
'autosumm-blank'   => 'Pejy nofotsiana',
'autosumm-replace' => 'Votoatiny novaina ho « $1 »',
'autoredircomment' => 'Pejy fihodinana mankany [[$1]]',
'autosumm-new'     => "Pejy voaforona amin'ny « $1 »",

# Live preview
'livepreview-loading' => 'Am-pakàna…',
'livepreview-ready'   => 'Am-pakàna … vita !',
'livepreview-failed'  => 'Tsy nandeha soa aman-tsara ny topi-maso haingankaingana !
Andrano ny topi-maso tsotra.',
'livepreview-error'   => 'Tsy afaka mifandray : $1 « $2 ».
Andramo ny topi-maso tsotra',

# Friendlier slave lag warnings
'lag-warn-normal' => "Ny fanovana vaovao nohon'ny $1 segondra {{PLURAL:}} dia tsy hiseho eo amin'ity lisitra ity.",
'lag-warn-high'   => "Noho ny hataraiky ny lohamilin'ny banky angona, tsy hiseho eto ny fanovana natao tao anatin'ny fotoana latsaky ny $1 segondra{{PLURAL:}}.",

# Watchlist editor
'watchlistedit-numitems'      => 'Ny lisitry ny pejy arahanao maso dia misy {{PLURAL:$1|lohateny iray|lohateny $1}}, raha tsy kaontiana ny pejin-dresaka.',
'watchlistedit-noitems'       => 'Tsy misy lohateny ny lisitrao.',
'watchlistedit-normal-title'  => 'Hanova ny lisitra ny pejy arahako maso',
'watchlistedit-normal-legend' => "Hanala lohateny ao amin'ny lisitra",
'watchlistedit-normal-submit' => 'Hanala ireo lohateny nosafidiana ireo',
'watchlistedit-raw-title'     => "Hanova ny lisitra ny pejy arahako maso amin'ny fomba akorany",
'watchlistedit-raw-legend'    => "Fanovana ilay lisitry ny pejy arahina maso amin'ny fomba akorany",
'watchlistedit-raw-titles'    => 'Lohateny :',
'watchlistedit-raw-submit'    => 'Havaozina ny lisitra',
'watchlistedit-raw-done'      => 'Voavao ny lisitrao.',

# Watchlist editing tools
'watchlisttools-view' => 'pejy arahako maso',
'watchlisttools-edit' => 'Jereo sy ovao ny lisitra ny pejy fanaraha-maso',
'watchlisttools-raw'  => 'Ovay ilay pejy arahako maso amizao',

# Core parser functions
'unknown_extension_tag' => 'Balizy mitondra itatra « $1 » tsy fantatra',

# Special:Version
'version'                   => 'Santiôna',
'version-extensions'        => 'Fanitarana nampidirina',
'version-specialpages'      => 'Pejy manokana',
'version-variables'         => 'Miova',
'version-other'             => 'Samihafa',
'version-hook-subscribedby' => "Nalefan'i",
'version-version'           => '(Santiôna $1)',
'version-license'           => 'Lisansy',
'version-software'          => 'Rindrankahy voapetraka',
'version-software-product'  => 'Vokatra',
'version-software-version'  => 'Santiôna',

# Special:FilePath
'filepath-page'    => 'Rakitra',
'filepath-submit'  => 'Handeha',
'filepath-summary' => "Mamerina ny lalam-pandehanana any amin'ilay rakitra ity pejy ity.
Aseho amin'ny tena habeny ny sary aseho, ny hafa dia alefa miaraka amin'ny rindrankajy miaraka aminy avy hatrany.

Ampidiro ny anaran-drakitra tsy misy ny tovona « {{ns:file}}: »",

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Hitady rakitra mitovy endrika',
'fileduplicatesearch-legend'   => 'Hitady mitovy endrika',
'fileduplicatesearch-filename' => 'Anaran-drakitra :',
'fileduplicatesearch-submit'   => 'Hikaroka',
'fileduplicatesearch-info'     => "piksely $1 × $2<br />Haben'ilay rakitra : $3 <br />Karazana MIME : $4",
'fileduplicatesearch-result-1' => "Tsy misy rakitra mitovy amin'ny « $1 ».",
'fileduplicatesearch-result-n' => "Misy rakitra {{PLURAL:}}$2 mitovy amin'i « $1 ».",

# Special:SpecialPages
'specialpages'                   => 'Pejy manokana',
'specialpages-note'              => '* Pejy manokana tsotra
* <strong class="mw-specialpagerestricted">Pejy manokana voafetra.</strong>',
'specialpages-group-maintenance' => "Tatitr'asa momban'ny fikojakojana",
'specialpages-group-other'       => 'Pejy manokana hafa',
'specialpages-group-login'       => 'Hiditra / hisoratra anarana',
'specialpages-group-changes'     => "Fanovana farany sy tatitr'asa",
'specialpages-group-media'       => "Tatitr'asa sy fampidirana rakitra media.",
'specialpages-group-users'       => 'Mpikambana sy satany',
'specialpages-group-highuse'     => 'Pejy ampiasaina mafy',
'specialpages-group-pages'       => 'Lisitra ny pejy',
'specialpages-group-pagetools'   => "Fitaovna ho an'ny pejy",
'specialpages-group-wiki'        => "Datan'ny wiki sy fitaovana",
'specialpages-group-redirects'   => 'Pejy manokana voaodina',
'specialpages-group-spam'        => 'Fitaovana fanalana spam',

# Special:BlankPage
'blankpage'              => 'Pejy fotsy',
'intentionallyblankpage' => 'Avela fananiana ho fotsy ity pejy ity.',

# Special:Tags
'tags'                    => "Balizin'ny fanovana mety",
'tag-filter'              => 'manasongadina [[Special:Tags|balizy]] :',
'tag-filter-submit'       => 'Manasongadina',
'tags-title'              => 'Balizy',
'tags-intro'              => "Ity pejy ity dia manalisitra ny balizy azon'ny rindrankajy ampiasaina mba hanamarika fanovana iray sy ny dikany.",
'tags-tag'                => "Anaran'ny balizy",
'tags-display-header'     => "Seho anatin'ny lisitry ny fanovana",
'tags-description-header' => "Famisavisana tanteraka an'ilay balizy",
'tags-hitcount-header'    => 'Fanovana voabalizy',
'tags-edit'               => 'hanova',
'tags-hitcount'           => '{{PLURAL:$1|fanovana|fanovana}} $1',

# Database error messages
'dberr-header'      => 'Misy olana io wiki io',
'dberr-problems'    => 'Azafady Tompoko ! Manana olana ara-teknika ny sehatra.',
'dberr-again'       => 'Miandrasa minitra vitsivitsy ary alefaso fanindroany',
'dberr-info'        => "(Tsy afaka mifandray amin'ny lohamilin'ny database : $1)",
'dberr-usegoogle'   => "Afaka manandrana mikaroka eo amin'ny Google ianao mandritra izay.",
'dberr-cachederror' => 'Izy io dia dika nasitriky ny pejy nangatahana ary mety efa tola.',

# HTML forms
'htmlform-invalid-input'       => "Nisy olana nitranga tamin'ny sanda sasany",
'htmlform-select-badoption'    => 'Tsy azo ekena ny sanda nambaranao.',
'htmlform-int-invalid'         => 'Tsy isa manontolo ny sanda nambaranao.',
'htmlform-float-invalid'       => 'Tsy isa ny sanda nambaranao.',
'htmlform-int-toolow'          => "Ny sanda nambaranao dia kely nohon'ny fetra iva indrindra $1",
'htmlform-int-toohigh'         => "Ny sanda nambaranao dia ngeza nohon'ny fetra avo indrindra $1",
'htmlform-submit'              => 'Alefa',
'htmlform-reset'               => 'Hanala ny fanovana',
'htmlform-selectorother-other' => 'Hafa',

);
