<?php
/**
 * This is the default Ripuarian localisation file for ksh
 * Version: 17. January 2007
 * The majority of users are bilingual in Kölsch plus German, so use German as fallback.
 */
/**
 * Sources:
 * The following expressions are based on the Kölsch dictionaries: 
 * Das Kölsche Wörterbuch, written by Christa Bhatt and Alice Herrwegen, 
 * published by: Akademie för uns kölsche Sproch, Cologne 2005,
 * ISBN 3-7616-1942-1
 * and
 * Neuer Kölnischer Sprachschatz in 3 Bänden, written by Adam Wrede, Cologne 1958, 
 * ISBN 3-7743-0155-7  ISBN 3-7743-0156-5  ISBN 3-7743-0157-3
 *
 * The grammar (especially: conjugation) is taken from: 
 * De kölsche Sproch - Kurzgrammatik Kölsch / Deutsch, written by Alice Tiling-Herrwegen, 
 * published by: Akademie för uns kölsche Sproch, Cologne 2002,
 * ISBN 3-7616-1604-X
 *
 * Special feature: Because of utilization in modern ripuarian literature
 * (for example: Asterix op kölsch - Däm Asterix singe Jung, ISBN 3-7704-0468-8) the rules for the letters G and J 
 * are taken from Adam Wrede (for example: Jedöns, jeeße, jejovve, adich, iggelich, nüdich ) 
 * and not from the Akademie (for example: Gedöns, geeße, gegovve, aadig, iggelig, nüdig)
 * Otherwise most part of the following expressions are taken from the Akademie.
 *
 * @addtogroup Language
 *
 * @author Caesius noh en Idee vum Manes
 */
/** 
 * Hints for editing
 * Avoid Ã¤ and other special codings because of legibility for those users, 
 * who will take this as a basis for further ripuarian message interfaces
 * Ã¤ => ä, Ã¶ => ö, Ã¼ => ü, Ã„ => Ä, Ã– => Ö, Ãœ => Ü, ÃŸ => ß
 * â€ž => „, â€œ => “
 */
/**
 * Fallback language, used for all unspecified messages and behaviour. This
 * is English by default, for all files other than this one.
 */
$fallback = 'de';

$namespaceNames = array(
        NS_MEDIA            => 'Medie',
        NS_SPECIAL          => 'Spezial',
        NS_MAIN             => '',
        NS_TALK             => 'Klaaf',
        NS_USER             => 'Metmaacher',
        NS_USER_TALK        => 'Metmaacher_Klaaf',
        # NS_PROJECT set by $wgMetaNamespace
        NS_PROJECT_TALK     => '$1_Klaaf',
        NS_IMAGE            => 'Beld',
        NS_IMAGE_TALK       => 'Belder_Klaaf',
        NS_MEDIAWIKI        => 'MediaWiki',
        NS_MEDIAWIKI_TALK   => 'MediaWiki_Klaaf',
        NS_TEMPLATE         => 'Schablon',
        NS_TEMPLATE_TALK    => 'Schablone_Klaaf',
        NS_HELP             => 'Hölp',
        NS_HELP_TALK        => 'Hölp_Klaaf',
        NS_CATEGORY         => 'Saachjrupp',
        NS_CATEGORY_TALK    => 'Saachjrupp_Klaaf',
);

/**
 * Array of namespace aliases, mapping from name to NS_xxx index
 */
$namespaceAliases = array(
	'Meedije'           => NS_MEDIA,
	'Shpezjal'          => NS_SPECIAL,
	'Medmaacher'        => NS_USER,
	'Medmaacher_Klaaf'  => NS_USER_TALK,
	'Belld'             => NS_IMAGE,
	'Bellder_Klaaf'     => NS_IMAGE_TALK,
	'MedijaWikki'       => NS_MEDIAWIKI,
	'MedijaWikki_Klaaf' => NS_MEDIAWIKI_TALK,
	'Hülp'              => NS_HELP,
	'Hülp_Klaaf'        => NS_HELP_TALK,
	'Sachjrop'          => NS_CATEGORY,
	'Sachjrop_Klaaf'    => NS_CATEGORY_TALK,
	'Saachjropp'        => NS_CATEGORY,
	'Saachjroppe_Klaaf' => NS_CATEGORY_TALK,
	'Kattejori'         => NS_CATEGORY,
	'Kattejori_Klaaf'   => NS_CATEGORY_TALK,
	'Kategorie'         => NS_CATEGORY,
	'Kategorie_Klaaf'   => NS_CATEGORY_TALK,
	'Katejori'          => NS_CATEGORY,
	'Katejorije_Klaaf'  => NS_CATEGORY_TALK,
);

$separatorTransformTable = array(',' => "\xc2\xa0", '.' => ',' );

/**
 * Skin names. If any key is not specified, the English one will be used.
 */
$skinNames = array(
        'standard'      => 'Standaad',
        'nostalgia'     => 'Nostaljesch',
        'cologneblue'   => 'Kölsch Blau',
        'smarty'        => 'Päddington',
        'montparnasse'  => 'Mont Panass',
        'davinci'       => 'Da_Vintschi',
        'mono'          => 'Mono',
        'monobook'      => 'MonoBoch',
        'myskin'        => 'Ming Skin',
        'chick'         => 'Höhnche'
);

$messages = array(
/*
 * The sidebar for MonoBook is generated from this message, lines that do not
 * begin with * or ** are discarded, furthermore lines that do begin with ** and
 * do not contain | are also discarded, but don't depend on this behaviour for
 * future releases. Also note that since each list value is wrapped in a unique
 * XHTML id it should only appear once and include characters that are legal
 * XHTML id names.
 */
# User preference toggles
        'tog-underline'         => 'Dun de Links ungerstriche:',
        'tog-highlightbroken'   => 'Zeich de Links op Sigge, die et noch nit jitt, esu met: „<a href="" class="new">Lemma</a>“ aan.<br />Wann De dat nit wells, weed et esu: „Lemma<a href="" class="internal">?</a>“ jezeich.',
        'tog-justify'           => 'Dun de Avschnedde em Blocksatz aanzeije',
        'tog-hideminor'         => 'Dun de klein Mini-Änderunge (<strong>M</strong>) en de Liss  met „Neuste Änderunge“ <strong>nit</strong> aanzeije',
        'tog-extendwatchlist'   => 'Verjrößer de Oppassliss för jede Aat vun möchliche Änderunge ze zeije',
        'tog-usenewrc'          => 'Dun de opgemotzte Liss met „Neuste Änderunge“ aanzeije (bruch Java_Skripp)',
        'tog-numberheadings'    => 'Dun de Üvverschrefte automatisch nummereere',
        'tog-showtoolbar'       => 'Zeich de Werkzeuchliss zom Ändere aan (bruch Java_Skripp)',
        'tog-editondblclick'    => 'Sigge met Dubbel-Klicke ändere (bruch Java_Skripp)',
        'tog-editsection'       => 'Maach [Ändere]-Links aan de Avschnedde dran',
        'tog-editsectiononrightclick'=> 'Avschnedde met Räächs-Klicke op de Üvverschrefte ändere (bruch Java_Skripp)',
        'tog-showtoc'           => 'Zeich en Enhaldsüvversich bei Sigge met mieh wie drei Üvverschrefte dren',
        'tog-rememberpassword'  => 'Op Duur aanmelde',
        'tog-editwidth'         => 'Maach dat Feld zom Tex enjevve su breid wie et jeiht',
        'tog-watchcreations'    => 'Dun de Sigge, die ich neu aanläje, för ming Oppassliss vürschlage',
        'tog-watchdefault'      => 'Dun de Sigge för ming Oppassliss vürschlage, die ich aanpacke un ändere',
        'tog-minordefault'      => 'Dun all ming Änderunge jedes Mol als klein Mini-Änderunge vürschlage',
        'tog-previewontop'      => 'Zeich de Vör-Aansich üvver däm Feld för dä Tex enzejevve aan.',
        'tog-previewonfirst'    => 'Zeich de Vör-Aansich tirek för et eetste Mol beim Bearbeide aan',
        'tog-nocache'           => 'Dun et Sigge Zweschespeichere - et Caching - avschalte',
        'tog-enotifwatchlistpages'=> 'Scheck en E-Mail, wann en Sigg us ming Oppassliss jeändert wood',
        'tog-enotifusertalkpages'=> 'Scheck mer en E-Mail, wann ming Klaaf Sigg jeändert weed',
        'tog-enotifminoredits'  => 'Scheck mer och en E-Mail för klein Mini-Änderunge',
        'tog-enotifrevealaddr'  => 'Zeich ming E-Mail Adress aan, en de Benohrichtijunge per E-Mail',
        'tog-shownumberswatching'=> 'Zeich de Aanzahl Metmaacher, die op die Sigg am oppasse sin',
        'tog-fancysig'          => 'Ungerschreff ohne automatische Link',
        'tog-externaleditor'    => 'Nemm jedes Mol en extern Editor-Projramm',
        'tog-externaldiff'      => 'Nemm jedes Mol en extern Diff-Projramm',
        'tog-showjumplinks'     => 'Links usjevve, die däm „Zojang ohne Barrikad“ helfe dun',
        'tog-uselivepreview'    => 'Zeich de „Lebendije Vör-Aansich zeije“ (bruch Java_Skripp) (em Usprobierstadium)',
        'tog-autopatrol'        => 'Wann ich jet änder, dann jild die Sigg als kontrolleet.',
        'tog-forceeditsummary'  => 'Froch noh, wann en däm Feld „Koot zosammejefass, Quell“ beim Avspeichere nix dren steiht',
        'tog-watchlisthideown'  => 'Dun ming eije Änderunge <strong>nit</strong> en minger Oppassliss aanzeije',
        'tog-watchlisthidebots' => 'Dun jedes Mol dä Bots ehr Änderunge <strong>nit</strong> en minger Oppassliss zeije',
        'tog-nolangconversion'          => 'Disable variants conversion',

        'underline-always' => 'jo, jedes Mol',
        'underline-never' => 'nä',
        'underline-default' => 'nemm dem Brauser sing Enstellung',

        'skinpreview' => '(Preview)',

# dates
'sunday'                => 'Sonndaach',
'monday'                => 'Mondaach',
'tuesday'               => 'Dingsdaach',
'wednesday'             => 'Meddwoch',
'thursday'              => 'Donnersdaach',
'friday'                => 'Friedaach',
'saturday'              => 'Samsdaach',
'sun'                   => 'So.',
'mon'                   => 'Mo.',
'tue'                   => 'Di.',
'wed'                   => 'Me.',
'thu'                   => 'Do.',
'fri'                   => 'Fr.',
'sat'                   => 'Sa.',
'january'               => 'Janewar',
'february'              => 'Febrewar',
'march'                 => 'Määz',
'april'                 => 'Aprel',
'may_long'              => 'Mai',
'june'                  => 'Juni',
'july'                  => 'Juli',
'august'                => 'Aujuss',
'september'             => 'September',
'october'               => 'Oktober',
'november'              => 'November',
'december'              => 'Dezember',
'january-gen'           => 'Janewar',
'february-gen'          => 'Febrewar',
'march-gen'             => 'Määz',
'april-gen'             => 'Aprel',
'may_long-gen'          => 'Mai',
'june-gen'              => 'Juni',
'july-gen'              => 'Juli',
'august-gen'            => 'Aujuss',
'september-gen'         => 'September',
'october-gen'           => 'Oktober',
'november-gen'          => 'November',
'december-gen'          => 'Dezember',
'jan'                   => 'Jan',
'feb'                   => 'Feb',
'mar'                   => 'Mäz',
'apr'                   => 'Apr',
'may'                   => 'Mai',
'jun'                   => 'Jun',
'jul'                   => 'Jul',
'aug'                   => 'Auj',
'sep'                   => 'Sep',
'oct'                   => 'Okt',
'nov'                   => 'Nov',
'dec'                   => 'Dez',
# Bits of text used by many pages:
#
'categories'            => 'Saachjruppe',
'pagecategories'        => '{{PLURAL:$1|Saachjrupp|Saachjruppe}}',
'pagecategorieslink'    => 'Special:Saachjruppe',
'category_header'       => 'Atikkele in de Saachjrupp „$1“',
'subcategories'         => 'Ungerjruppe',
'category-media-header' => 'Medie en de Saachjrupp "$1"',


'linkprefix'            => '/^(.*?)([a-zA-Z\x80-\xff]+)$/sD',
'mainpage'              => 'Haupsigg',
'mainpagetext'          => "<big>'''MediaWiki es jetz enstalleet.'''</big>",
'mainpagedocfooter'     => "Luur en dä [http://meta.wikimedia.org/wiki/Help:Contents Benutzerhandbuch] wann De wesse wells wie de Wiki-Soffwär jebruch un bedeent wääde muss.

== Getting started ==

* [http://www.mediawiki.org/wiki/Help:Configuration_settings Configuration settings list]
* [http://www.mediawiki.org/wiki/Help:FAQ MediaWiki FAQ]
* [http://mail.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]",

'portal'                => 'Üvver {{SITENAME}}',
'portal-url'            => '{{ns:project}}:Metmaacher Pooz',
'about'                 => 'Üvver {{SITENAME}}',
'aboutsite'             => 'Üvver de {{SITENAME}}',
'aboutpage'             => '{{ns:project}}:Üvver de {{SITENAME}}',
'article'               => 'Atikkel',
'help'                  => 'Hölp',
'helppage'              => '{{ns:project}}:Hölp',
'bugreports'            => 'Fähler melde',
'bugreportspage'        => '{{ns:project}}:Kontak',
'sitesupport'           => 'Spende',
'sitesupport-url'       => '{{ns:project}}:Spende',
'faq'                   => 'FAQ',
'faqpage'               => '{{ns:project}}:FAQ',
'edithelp'              => 'Hölp för et Bearbeide',
'newwindow'             => '(Mäht e neu Finster op, wann Dinge Brauser dat kann)',
'edithelppage'          => '{{ns:project}}:Hölp',
'cancel'                => 'Stopp! Avbreche!',
'qbfind'                => 'Fingk',
'qbbrowse'              => 'Aanluure',
'qbedit'                => 'Ändere',
'qbpageoptions'         => 'Sigge Enstellunge',
'qbpageinfo'            => 'Zosammehang',
'qbmyoptions'           => 'Ming Sigge',
'qbspecialpages'        => 'Spezial Sigge',
'moredotdotdot'         => 'Miehâ€¦',
'mypage'                => 'Ming Sigg',
'mytalk'                => 'ming Klaafsigg',
'anontalk'              => 'Klaaf för de IP-Adress',
'navigation'            => 'Jangk noh',

# Metadata in edit box
'metadata_help'         => 'Däm Beld sing Meta-Daate ([[{{ns:project}}:Meta-Daate vun Belder|hee sin se usenanderposementeet]])',
'currentevents'         => 'Et Neuste',
'currentevents-url'     => '{{ns:project}}:Et Neuste',
'disclaimers'           => 'Hinwies',
'disclaimerpage'        => '{{ns:project}}:Impressum',
'privacy'               => 'Daateschotz un Jeheimhaldung',
'privacypage'           => '{{ns:project}}:Daateschotz un Jeheimhaldung',
'errorpagetitle'        => 'Fähler',
'returnto'              => 'Jangk widder noh: „$1“.',
'tagline'               => 'Us de {{SITENAME}}',

'search'                => 'Söke',
'searchbutton'          => 'em Tex',
'go'                    => 'Loss Jonn',
'searcharticle'         => 'Atikkel',
'history'               => 'Versione',
'history_short'         => 'Versione',
'updatedmarker'         => '(verändert)',
'info_short'            => 'Information',
'printableversion'      => 'För ze Drocke',
'permalink'             => 'Als Permalink',
'print'                 => 'För ze Drocke',
'edit'                  => 'Ändere',
'editthispage'          => 'De Sigg ändere',
'delete'                => 'Fottschmieße',
'deletethispage'        => 'De Sigg fottschmieße',
'undelete_short'        => '{{PLURAL:$1|ein Änderung|$1 Änderunge}} zeröckholle',
'protect'               => 'Schötze',
'protectthispage'       => 'De Sigg schötze',
'unprotect'             => 'Schotz ophevve',
'unprotectthispage'     => 'Dä Schotz för de Sigg ophevve',
'newpage'               => 'Neu Sigg',
'talkpage'              => 'Üvver die Sigg hee schwaade',
'specialpage'           => 'Sondersigg',
'personaltools'         => 'Metmaacher Werkzeuch',
'postcomment'           => 'Neu Avschnedd op de Klaafsigg',
'addsection'            => '+',
'articlepage'           => 'Aanluure wat op dä Sigg drop steiht',
'talk'                  => 'Klaaf',
'views'                 => 'Aansichte',
'toolbox'               => 'Werkzeuch',
'userpage'              => 'Däm Metmaacher sing Sigg aanluure',
'projectpage'           => 'De Projeksigg aanluure',
'imagepage'             => 'Beldsigg aanluure',
'mediawikipage'         => 'De Mediasigg aanluure',
'templatepage'          => 'De Schablon ehr Sigg aanluure',
'viewhelppage'          => 'De Hölpsigg aanluure',
'categorypage'          => 'De Saachjruppesigg aanluure',
'viewtalkpage'          => 'Klaaf aanluure',
'otherlanguages'        => 'En ander Sproche',
'redirectedfrom'        => '(Ömjeleit vun $1)',
'redirectpagesub'       => 'Ömleitungssigg',
'lastmodified'          => 'Stand vum $1',
'viewcount'             => 'De Sigg es bes jetz {{PLURAL:$1|eimol|$1 Mol}} avjerofe woode.',
'copyright'             => 'Dä Enhald steiht unger de $1.',
'protectedpage'         => 'Jeschötzte Sigg',
'jumpto'                => 'Jangk noh:',
'jumptonavigation'      => 'Noh de Navigation',
'jumptosearch'          => 'Jangk Söke!',

'badaccess'             => 'Nit jenoch Räächde',
'badaccess-group0'      => 'Do häs nit jenoch Räächde.',
'badaccess-group1'      => 'Wat Do wells, dat dürfe nor Metmaacher, die $1 sin.',
'badaccess-group2'      => 'Wat Do wells, dat dürfe nor de Metmaacher us dä Jruppe: $1.',
'badaccess-groups'      => 'Wat Do wells, dat dürfe nor de Metmaacher us dä Jruppe: $1.',

'versionrequired'       => 'De Version $1 vun MediaWiki Soffwär es nüdich',
'versionrequiredtext'   => 'De Version $1 vun MediaWiki Soffwär es nüdich, öm die Sigg hee bruche ze künne. Süch op [[Special:Version|de Versionssigg]], wat mer hee för ene Soffwärstand han.',

'widthheight'           => '$1Ã—$2',
'ok'                    => 'OK',
'sitetitle'             => '{{SITENAME}}',
'pagetitle'             => '$1 - {{SITENAME}}',
#'sitesubtitle' => '',
'retrievedfrom'         => 'Die Sigg hee stamp us „$1“.',
'youhavenewmessages'    => 'Do häs $1 ($2).',
'newmessageslink'       => 'neu Metdeilunge op Dinger Klaafsigg',
'newmessagesdifflink'   => 'Ungerscheed zor vürletzte Version',
'editsection'           => 'Ändere',
'editold'               => 'Hee die Version ändere',
'editsectionhint'       => 'Avschnedd ändere: $1',
'toc'                   => 'Enhaldsüvversich',
'showtoc'               => 'enblende',
'hidetoc'               => 'usblende',
'thisisdeleted'         => '$1 - aanluure oder widder zeröckholle?',
'viewdeleted'           => '$1 aanzeije?',
'restorelink'           => '{{PLURAL:$1|eije fottjeschmesse Änderung|$1 fottjeschmesse Änderunge}}',
'feedlinks'             => 'Feed:',
'feed-invalid'          => 'Esu en Zoot Abonnemang jitt et nit.',

'feed-atom'             => 'Atom',
'feed-rss'              => 'RSS',
'sitenotice'            => '-', # the equivalent to wgSiteNotice
'anonnotice'            => '-',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'            => 'Atikkel',
'nstab-user'            => 'Metmaachersigg',
'nstab-media'           => 'Mediasigg',
'nstab-special'         => 'Spezial',
'nstab-project'         => 'Projeksigg',
'nstab-image'           => 'Beld',
'nstab-mediawiki'       => 'Tex',
'nstab-template'        => 'Schablon',
'nstab-help'            => 'Hölp',
'nstab-category'        => 'Saachjrupp',


# Main script and global functions
#
'nosuchaction'          => 'Die Aufgab (action) kenne mer nit',
'nosuchactiontext'      => '<strong>Na su jet:</strong> De Aufgab us dä URL, die do hinger „<code>action=</code>“ dren steiht, jo die kennt hee dat Wiki jar nit.',
'nosuchspecialpage'     => 'Esu en Sondersigg ha\'mer nit',
'nospecialpagetext'     => 'De aanjefrochte Sondersigg jitt et nit, de [[Special:Specialpages|Liss met de Sondersigge]] helfe dir wigger.',

# General errors
#
'error'                 => 'Fähler',
'databaseerror'         => 'Fähler en de Daatebank',
'dberrortext'           => 'Ene Fähler es opjefalle en dä Syntax vun enem Befähl för de Daatebank. Dat künnt ene Fähler en de Soffwär vum Wiki sin.
De letzte Daatebankbefähl es jewäse:
<blockquote><code>$1</code></blockquote>
us däm Projramm singe Funktion: „<code>$2</code>“.<br />
MySQL meld dä Fähler: „<code>$3: $4</code>“.',
'dberrortextcl'         => 'Ene Fähler es opjefalle en dä Syntax vun enem Befähl för de Daatebank.
Dä letzte Befähl för de Daatebank es jewäse:
<blockquote><code>$1</code></blockquote>
us däm Projramm sing Funktion: „<code>$2</code>“.<br />
MySQL meld dä Fähler: „<code>$3: $4</code>“.',
'noconnect'             => 'Schad! Mer kunnte kein Verbindung met däm Daatebanksörver op „$1“ krije.',
'nodb'                  => 'Kunnt de Daatebank „$1“ nit uswähle',
'cachederror'           => 'Dat hee es en Kopie vun dä Sigg us em Cache. Möchlich, se es nit aktuell.',
'laggedslavemode'       => '<strong>Opjepass:</strong> Künnt sin, dat hee nit dä neuste Stand vun dä Sigg aanjezeich weed.',
'readonly'              => 'De Daatebank es jesperrt',
'enterlockreason'       => 'Jevv aan, woröm un för wie lang dat de Daatebank jesperrt wääde soll',
'readonlytext'          => 'De Daatebank es jesperrt. Neu Saache dren avspeichere jeiht jrad nit, un ändere och nit. Dä Jrund: „$1“',
#Et weed wall öm de normale Waadung jonn. Dun et einfach en e paar  Minutte widder versöke.
#The administrator who locked it offered this explanation: $1',
'missingarticle'        => 'Dä Tex för de Sigg „$1“ kunnte mer nit en de Daatebank finge.
De Sigg es villeich fottjeschmesse oder ömjenannt woode.
Wann dat esu nit sin sollt, dann hadder villeich ene Fähler en de Soffwär jefunge.
Verzällt et enem Wiki_Köbes,
un doht em och de URL vun dä Sigg hee sage.',
'readonly_lag'          => 'De Daatebank es för en koote Zigg jesperrt, för de Daate avzejliche.',
'internalerror'         => 'De Wiki-Soffwär hät ene Fähler jefunge',
'filecopyerror'         => 'Kunnt de Datei „$1“ nit noh „$2“ kopeere.',
'filerenameerror'       => 'Kunnt de Datei „$1“ nit op „$2“ ömdäufe.',
'filedeleteerror'       => 'Kunnt de Datei „$1“ nit fottschmieße.',
'filenotfound'          => 'Kunnt de Datei „$1“ nit finge.',
'unexpected'            => 'Domet hät keiner jerechnet: „$1“=„$2“',
'formerror'             => 'Dat es donevve jejange: Wor nix, met däm Fomular.',
'badarticleerror'       => 'Dat jeiht met hee dä Sigg nit ze maache.',
'cannotdelete'          => 'De Sigg oder de Datei hee fottzeschmieße es nit möchlich. Möchlich, dat ene andere Metmaacher flöcker wor, hät et vürher hee jo ald jedon, un jetz es die Sigg ald fott.',
'badtitle'              => 'Verkihrte Üvverschreff',
'badtitletext'          => 'De Üvverschreff es esu nit en Odenung. Et muss jet dren stonn.
Et künnt sin, dat ein vun de speziell Zeiche dren steiht,
wat en Üvverschrefte nit erlaub es.
Et künnt ussinn, wie ene InterWikiLink,
dat jeiht ävver nit.
Muss De repareere.',
'perfdisabled'          => '<strong>\'\'\'Opjepass:\'\'\'</strong> Dat maache mer jetz nit - dä Sörver hät jrad zovill Lass - do si\'mer jet vürsichtich.',
'perfdisabledsub'       => 'Hee kütt en jespeicherte Kopie vun $1:',
'perfcached'            => 'De Daate heenoh kumme usem Zweschespeicher (Cache) un künnte nit mieh janz de allerneuste sin.',
'perfcachedts'          => 'De Daate heenoh kumme usem Zweschespeicher (Cache) un woodte $1 opjenumme. Se künnte nit janz de allerneuste sin.',
'wrong_wfQuery_params'  => 'Verkihrte Parameter för: <strong><code>wfQuery()</code></strong><br />
De Funktion es: „<code>$1</code>“<br />
De Aanfroch es: „<code>$2</code>“<br />',
'viewsource'            => 'Wikitex aanluure',
'viewsourcefor'         => 'för de Sigg: „$1“',
'protectedtext'         => 'Die Sigg hee es jäje Veränderunge jeschötz.
Do künnt et en Aanzahl vun Ursaache för jevve. Villeich fingk mer jet em <span 
class="plainlinks">[{{FULLURL:Special:Log/protect|page={{FULLPAGENAMEE}}}} Logboch]</span> dodrüvver.
Jeder kann sich ävver dä Wikitex vun dä Sigg aanluure un och kopeere. Hee kütt e:',
'protectedinterface'    => 'Op dä Sigg hee steiht Tex usem Interface vun de Wiki-Soffwär. Dröm es die jäje Änderunge jeschötz, domet keine Mess domet aanjestallt weed.',
'editinginterface'      => '<strong>Opjepass:</strong> 
Op dä Sigg hee steiht Tex usem Interface vun de Wiki-Soffwär. Dröm es die jäje Änderunge jeschötz, domet keine Mess domet aanjestallt weed. Nor de Wiki-Köbese künne 
se ändere. Denk dran, hee ändere deit et Ussinn un de Wööt ändere met dänne et Wiki op de Metmaacher un de Besöker drop aankütt!',
'sqlhidden'             => '(Dä SQL_Befähl du\'mer nit zeije)',

# Login and logout pages
#
'logouttitle'           => 'Uslogge',
'logouttext'            => 'Jetz bes de usjelogg.

* Do künnts op de {{SITENAME}} wigger maache, als ene namelose Metmaacher.

* Do kanns De ävver och widder [[Special:Userlogin|enlogge]], als däselve oder och ene andere Metmaacher.

* Un Do kanns met <span class="plainlinks">[{{FULLURL:Special:Userlogin|type=signup}} ene neue Metmaacher 
aanmelde]</span>.

<strong>Opjepass:</strong>

Es möchlich, dat De de ein oder ander Sigg immer wigger aanjezeich kriss, wie wann de noch enjelogg wörs. Dun Dingem Brauser singe Cache fottschmieße oder leddich maache, öm us dä Nummer erus ze kumme!<br />',

'welcomecreation' => "== Dach, $1! ==

Dinge Zojang för hee es do. Do bes jetz aanjemeldt. Denk dran, Do künnts der Ding [[Special:Preferences|Enstellunge]] hee op de {{SITENAME}} zeräächmaache.",
'loginpagetitle'        => 'Enlogge',
'yourname'              => 'Metmaacher Name',
'yourpassword'          => 'Passwood',
'yourpasswordagain'     => 'Noch ens dat Passwood',
'remembermypassword'    => 'Op Duur Aanmelde',
'yourdomainname'        => 'Ding Domain',
'externaldberror'       => 'Do wor ene Fähler en de externe Daatebank, oder Do darfs Ding extern Daate nit ändere. Dat Aanmelde jingk donevve.',
'loginproblem'          => '<strong>Met däm Enlogge es jet scheiv jelaufe.</strong><br />Bes esu jod, un dun et noch ens versöke!',
'alreadyloggedin'       => 'Do bes ald enjelogg, als dä Metmaacher „<strong>$1</strong>“.',

'login'                 => 'Enlogge',
'loginprompt'           => 'Öm op de {{SITENAME}} [[Special:Userlogin|enlogge]] ze künne, muss De de Cookies en Dingem Brauser enjeschalt han.',
'userlogin'             => 'Enlogge / Metmaacher wääde',
'logout'                => 'Uslogge',
'userlogout'            => 'Uslogge',
'notloggedin'           => 'Nit enjelogg',
'nologin'               => 'Wann De Dich noch nit aanjemeldt häs, dann dun Dich $1.',
'nologinlink'           => 'Neu Aanmelde',
'createaccount'         => 'Aanmelde als ene neue Metmaacher',
'gotaccount'            => 'Do häs ald en Aanmeldung op de {{SITENAME}}? Dann jangk nohm $1.',
'gotaccountlink'        => 'Enlogge',
'createaccountmail'     => 'Passwood met E-Mail Schecke',
'badretype'             => 'Ding zwei enjejovve Passwööder sin ungerscheedlich. Do muss De Dich för ein entscheide.',
'userexists'            => 'Ene Metmaacher met däm Name: „<strong>$1</strong>“ jitt et ald. Schad. Do muss De Der ene andere Name usdenke.',
'youremail'             => 'E-Mail *',
'username'              => 'Metmaacher_Name:',
'uid'                   => 'Metmaacher ID:',
'yourrealname'          => 'Dinge richtije Name *',
'yourlanguage'          => '<span title="Sök de Sproch us, die et Wiki kalle soll!">Sproch:<span>',
'yourvariant'           => 'Ding Variant',
'yournick'              => 'Name för en Ding Ungerschreff:',
'badsig'                => 'De Ungeschreff jeiht esu nit - luur noh dem HTML dodren un maach et richtich.',
'email'                 => 'E-Mail',
'prefs-help-email-enotif'=> 'De E-Mail Adress weed och jebruch, öm Der üvver Änderunge bescheid ze sage, wann De dat usjewählt häs, en Ding Enstellunge.',
'prefs-help-realname'   => '* Dinge richtije Name - kanns De fott looße - wann De en nenne wells, dann weed hee jebruch, öm Ding Beidräch domet ze schmöcke.',
'loginerror'            => 'Fähler beim Enlogge',
'prefs-help-email'      => '* E-mail - kanns De fottlooße, un es för Andre nit ze sinn - mäht et ävver möchlich, dat mer met Dir en Kontak kumme kann, ohne dat mer Dinge Name oder Ding E-Mail Adress kenne dät.',
'nocookiesnew'          => 'Dinge neue Metmaacher Name es enjerich, ävver dat automatisch Enlogge wor dann nix. 
Schad. De {{SITENAME}} bruch Cookies, öm ze merke, wä 
enjelogg es. Wann De Cookies avjeschald häs en Dingem Brauser, dann kann 
dat nit laufe. Sök Der ene Brauser, dä et kann, dun se enschalte, un dann log Dich noch ens neu en, met Dingem neue Metmaacher Name un Passwood.',
'nocookieslogin'        => 'De {{SITENAME}} bruch Cookies för et Enlogge. Et süht esu us, als hätts de Cookies avjeschalt. Dun se aanschalte un dann versök et noch ens.',
'noname'                => 'Dat jeiht nit als ene Metmaacher Name. Jetz muss De et noch ens versöke.',
'loginsuccesstitle'     => 'Dat Enlogge hät jeflupp.',
'loginsuccess'          => '<br />Do bes jetz enjelogg bei de <strong>{{SITENAME}}</strong>, un Dinge Metmaacher Name es „<strong>$1</strong>“.<br />',
'nosuchuser'            => 'Dat Passwood oder dä Metmaacher Name „$1“ wor verkihrt. Jetz muss De et noch ens versöke. Oder_<span class="plainlinks">[{{FULLURL:Special:Userlogin|type=signup}} ene neue Metmaacher aanmelde]</span>.',
'nosuchusershort'       => 'Dä Metmaacher Name „$1“ wor verkihrt. Jetz muss De et noch ens versöke.',
'nouserspecified'       => 'Dat jeiht nit als ene Metmaacher Name',
'wrongpassword'         => 'Dat Passwood oder dä Metmaacher Name wor verkihrt. Jetz muss De et noch ens versöke.',
'wrongpasswordempty'    => 'Dat Passwood ka\'mer nit fottlooße. Jetz muss De et noch ens versöke.',
'mailmypassword'        => 'Passwood verjesse?',
'passwordremindertitle' => 'Login op {{SITENAME}}',
'passwordremindertext'  => 'Jod möchlich, Do wors et selver,
vun de IP Adress $1,
jedenfalls hät eine aanjefroch, dat
mer Dir e neu Passwood zoschecke soll,
för et Enlogge en de {{SITENAME}} op
{{FULLURL:{{MediaWiki:Mainpage}}}}
($4)

Alsu, e neu Passwood för "$2"
es jetz vürjemerk: "$3".
Do solls De tirek jlich enlogge,
un dat Passwood widder ändere.
Dä Transport üvver et Netz met E-Mail
es unsecher, do künne Fremde metlese,
un winnichstens de Jeheimdeenste dun
dat och. Usserdäm es "$3" 
villeich nit esu jod ze merke?

Wann nit Do, söndern söns wä noh däm
neue Passwood verlank hät, wann De 
Dich jetz doch widder aan Ding ahl Passwood
entsenne kanns, jo do bruchs de jar nix
ze dun, do kanns De Ding ahl Passwood wigger 
bruche, un die E-Mail hee, die kanns De 
jlatt verjesse.

Ene schöne Jroß vun de {{SITENAME}}.

-- 
{{SITENAME}}: {{fullurl:{{Mediawiki:mainpage}}}}',
'noemail'               => 'Dä Metmaacher hät en de $1 kein E-Mail Adress aanjejovve.',
'passwordsent'          => 'E neu Passwood es aan de E-Mail Adress vun däm Metmaacher „$1“ ungerwähs. Meld dich domet aan, wann De et häs. Dat ahle Passwood bliev erhalde un kann och noch jebruch wääde, bes dat De Dich et eetste Mol met däm Neue enjelogg häs.',
'blocked-mailpassword' => 'Ding IP Adress es blockeet.',
'eauthentsent'          => 'En E-Mail es jetz ungerwähs aan de Adress, die en de Enstellunge vum Metmaacher $1 steiht.
Ih dat E-Mails üvver de {{SITENAME}} ehre E-Mail-Knopp verscheck wääde künne, muss de E-Mail Adress 
eets  ens bestätich woode sin. Wat mer doför maache muss, steiht en dä E-Mail dren, die jrad avjescheck woode es. 

Alsu luur do eren, un dun et.',
'throttled-mailpassword' => 'En Erennerung för di Passwood es ungerwähs. Domet ene fiese Möpp keine Dress fabrizeet, passeet dat hüchstens eimol en $1 Stunde.',
#'loginend'                         => '',
'signupend'                         => '{{int:loginend}}',
'mailerror'             => 'Fähler beim E-Mail Verschecke: $1.',
'acct_creation_throttle_hit'=> '<b>Schad.</b> Do häs ald {{PLURAL:$1|eine|$1}} Metmaacher Name aanjelaht. Mieh sin nit möchlich.',
'emailauthenticated'    => 'Ding E-Mail Adress wood bestätich om: <strong>$1</strong>.',
'emailnotauthenticated' => 'Ding E-Mail Adress es <strong>nit</strong> bestätich. Dröm kann kein E-Mail aan Dich jescheck wääde för:',
'noemailprefs'          => 'Dun en E-Mail Adress endrage, domet dat et all fluppe kann.',
'emailconfirmlink'      => 'Dun Ding E-Mail Adress bestätije looße',
'invalidemailaddress'   => 'Wat De do als en E-Mail Adress aanjejovve häs, süht noh Dress us. En E-Mail Adress en däm Format, dat jitt et nit. Muss De repareere - oder Do mähs dat Feld leddich un schrievs nix eren. Un dann versök  et noch ens.',
'accountcreated'        => 'Aanjemeldt',
'accountcreatedtext'    => 'De Aanmeldung för dä Metmaacher „<strong>$1</strong>“ es durch, kann jetz enlogge.',

# Edit page toolbar
'bold_sample'           => 'Fett Schreff',
'bold_tip'              => 'Fett Schreff',
'italic_sample'         => 'Scheive Schreff',
'italic_tip'            => 'Scheive Schreff',
'link_sample'           => 'Anker Tex',
'link_tip'              => 'Ene Link en de {{SITENAME}}',
'extlink_sample'        => 'http://www.example.com/ Dä Anker Tex',
'extlink_tip'           => 'Ene Link noh drusse (denk dran, http:// aan dr Aanfang!)',
'headline_sample'       => 'Üvverschreff',
'headline_tip'          => 'Üvverschreff op de bövverschte Ebene',
'math_sample'           => 'Hee schriev de Formel eren',
'math_tip'              => 'För mathematisch Formele nemm „LaTeX“',
'nowiki_sample'         => 'Hee kütt dä Tex hen, dä vun de Wiki-Soffwär nit bearbeid, un en Rauh jelooße wääde soll',
'nowiki_tip'            => 'De Wiki Code üvverjonn',
'image_sample'          => 'Beispill.jpg',
'image_tip'             => 'E Beldche enbaue',
'media_sample'          => 'Beispill.ogg',
'media_tip'             => 'Ene Link op en Tondatei, e Filmche, oder esu jet',
'sig_tip'               => 'Dinge Name, met de Uhrzigg un em Datum',
'hr_tip'                => 'En Querlinnich',

# Edit pages
#
'summary'               => 'Koot Zosammejefass, Quell',
'subject'               => 'Üvverschreff - wodröm jeiht et?',
'minoredit'             => 'Dat es en klein Änderung (mini)',
'watchthis'             => 'Op die Sigg hee oppasse',
'savearticle'           => 'De Sigg Avspeichere',
'preview'               => 'Vör-Aansich',
'showpreview'           => 'Vör-Aansich zeije',
'showlivepreview'       => 'Lebendije Vör-Aansich zeije',
'showdiff'              => 'De Ungerscheed zeije',
'anoneditwarning'       => 'Weil De nit aanjemeldt bes, weed Ding IP-Adress opjezeichnet wääde.',
'missingsummary'        => '<strong>Opjepass:</strong> Do häs nix bei „Koot Zosammejefass, Quell“ enjejovve. Dun noch ens op „<b style="padding:2px; background-color:#ddd; color:black">De Sigg Avspeichere</b>“ klicke, öm Ding Änderunge ohne de Zosammefassung ze Speicheree. Ävver besser jiss De do jetz tirek ens jet en!',
'missingcommenttext'    => 'Jevv en „Koot Zosammejefass, Quell“ aan!',
'missingcommentheader' => '\'\'\'Opjepass:\'\'\' Do häs kein Üvverschreff för Dinge Beidrach enjejovve. Wann De noch ens op „De Sigg Avspeichere“ dröcks, weed dä Beidrach ohne Üvverschreff avjespeichert.',
'summary-preview' => 'Vör-Aansich vun „Koot Zosammejefass, Quell“',
'subject-preview' => 'Vör-Aansich vun de Üvverschreff', 
'blockedtitle'          => 'Dä Metmaacher es jesperrt',
'blockedtext'           => '<big><b>Dinge Metmaacher Name oder IP Adress es vun „$1“ jesperrt woode.</b></big>
Als Jrund es enjedrage: „<i>$2</i>“
Do kanns met „$1“ oder ene andere Wiki-Köbes üvver dat Sperre schwaade, wann 
de wells.
Do kanns ävver nor dann dat „<i>E-Mail aan dä Metmaacher</i>“ aanwende, wann de ald en E-Mail Adress en Ding 
[[Special:Preferences|ming Enstellunge]] enjedrage un freijejovve häs.

Ding IP Adress es de „$3“. Dun se en Ding Aanfroge nenne.',
'blockedoriginalsource' => 'Dä orjenal Wiki Tex vun dä Sigg „<strong>$1</strong>“ steiht hee drunger:',
'blockededitsource'     => 'Dä Wiki Tex vun <strong>Dinge Änderunge</strong> aan dä Sigg „<strong>$1</strong>“ steiht hee drunger:',
'whitelistedittitle'    => 'Enlogge nüdich för Sigge ze Ändere',
'whitelistedittext'     => 'Do mööts ald $1, öm hee em Wiki Sigge ändere ze dürfe.',
'whitelistreadtitle'    => 'Enlogge nüdich för ze Lese',
'whitelistreadtext'     => 'Do mööts ald_[[Special:Userlogin|enjelogg sin]], öm hee Sigge lese ze dürfe.',
'whitelistacctitle'     => 'Kei Rääch för Metmaacher aanzeläje.',
'whitelistacctext'      => 'Do mööts ald [[Special:Userlogin|enjelogg sin]] un speziell et Rääch doför han, öm hee en däm Wiki Metmaacher enrichte un aanläje ze dürfe.',
'confirmedittitle'      => 'För et Sigge Ändere muss De Ding E-Mail Adress ald bestätich han.',
'confirmedittext'       => 'Do muss Ding E-Mail Adress ald bestätich han, ih dat De hee Sigge ändere darfs. Drag Ding E-Mail Adress en Ding [[{{ns:special}}:Preferences|ming Enstellunge]] en, un dun „<span style="padding:2px; background-color:#ddd; color:black">Dun Ding E-Mail Adress bestätije looße</span>“ klicke.',
'loginreqtitle'         => 'Enlogge es nüdich',
'loginreqlink'          => 'enjelogg sin',
'loginreqpagetext'      => 'Do mööts eets ens $1, öm ander Sigge aanzeluure.',
'accmailtitle'          => 'Passwood verscheck',
'accmailtext'           => 'Dat Passwood för dä Metmaacher „$1“ es aan „$2“ jescheck woode.',
'newarticle'            => '(Neu)',

'newarticletext'        => 'Ene Link op en Sigg, wo noch nix drop steiht, weil et se noch jar nit jitt, hät Dich 
noh hee jebraht.<br />
<small>Öm die Sigg aanzeläje, schriev hee unge en dat Feld eren, un dun et dann avspeichere. (Luur op de 
[[int:MediaWiki:Helppage|Sigge met Hölp]] noh, wann De mieh dodrüvver wesse wells)<br />Wann De jar nit hee hen 
kumme wollts, dann jangk zeröck op die Sigg, wo De herjekumme bes, Dinge Brauser hät ene Knopp doför.</small>',
'newarticletextanon' => '{{int:newarticletext}}',
'talkpagetext' => '<!-- MediaWiki:talkpagetext -->',
'anontalkpagetext'      => '----
<i>Dat hee es de Klaaf Sigg för ene namenlose Metmaacher. Dä hät sich noch keine Metmaacher Name jejovve un 
enjerich, ov deit keine bruche. Dröm bruche mer sing IP Adress öm It oder In en uns Lisste fasszehalde. 
Su en IP Adress kann vun janz vill Metmaacher jebruch wääde, un eine Metmaacher kann janz flöck 
zwesche de ungerscheedlichste IP Adresse wähßele, womöchlich ohne dat hä et merk. Wann Do jetz ene namenlose 
Metmaacher bes, un fings, dat hee Saache an Dich jeschrevve wääde, wo Do jar nix met am Hot häs, dann bes Do 
wahrscheinlich och nit jemeint. Denk villeich ens drüvver noh, datte Dich [[Special:Userlogin|anmelde]] deis, 
domet De dann donoh nit mieh met esu en Ömständ ze dun häs, wie de andere namenlose Metmaacher hee.</i>',
'noarticletext'         => 'Hee es jetz em Momang keine Tex op dä Sigg.<br />Jangk en de Texte vun ander Sigge 
[[Special:Search/{{PAGENAME}}|noh däm Titel söke]], oder jangk, un <span 
class="plainlinks">[{{FULLURL:{{FULLPAGENAME}}|action=edit}} fang die Sigg aan]</span> ze schrieve.<br 
/><small>Oder jangk zeröck wo de her koms. Dinge Brauser hät ene Knopp doför.</small>',
'noarticletextanon' => '{{int:noarticletext}}',
'clearyourcache'        => '<br clear="all" style="clear:both">
\'\'\'Opjepass:\'\'\'
Noh em Speichere, künnt et sin, datte Dingem Brauser singe Cache Speicher 
üvverlisste muss, ih datte de Änderunge och ze sinn kriss.
Beim \'\'\'Mozilla\'\'\' un  \'\'\'Firefox\'\'\' un \'\'\'Safari\'\'\', dröck de \'\'Jroß Schreff Knopp\'\' un 
Klick op \'\'Refresh\'\' / \'\'Aktualisieren\'\', oder dröck \'\'Ctrl-Shift-R\'\' / \'\'Strg+Jroß Schreff+R\'\', oder 
dröck \'\'Ctrl-F5\'\' / \'\'Strg/F5\'\' / \'\'Cmd+Shift+R\'\' / \'\'Cmd+Jroß Schreff+R\'\', je noh Ding Tastatur 
un Dingem Kompjuter.
Beim \'\'\'Internet Explorer\'\'\' dröck op \'\'Ctrl\'\' / \'\'Strg\'\' un Klick op \'\'Refresh\'\', oder dröck 
\'\'Ctrl-F5\'\' / \'\'Strg+F5\'\'.
Beim \'\'\'Konqueror:\'\'\' klick dä \'\'Reload\'\'-Knopp oder dröck dä \'\'F5\'\'-Knopp.
Beim  \'\'\'Opera\'\'\' kanns De üvver et Menue jonn un 
däm janze Cache singe Enhald üvver \'\'Tools?Preferences\'\' fottschmieße.',
'usercssjsyoucanpreview'=> '<b>Tipp:</b> Dun met däm <b style="padding:2px; background-color:#ddd; 
color:black">Vör-Aansich Zeije</b>-Knopp usprobeere, wat Ding neu 
Metmaacher_CSS/Java_Skripp mäht, ih dat et avspeichere deis!',
'usercsspreview'        => '<b>Opjepass: Do bes hee nor am Usprobeere, wat Ding 
Metmaacher_CSS mäht, et es noch nit jesechert!</b>',
'userjspreview'         => '<b>Opjepass: Do bes hee nor am Usprobeere, wat Ding 
Metmaacher_Java_Skripp mäht, et es noch nit jesechert!</b>',
'userinvalidcssjstitle' => '<strong>Opjepass:</strong> Et jitt kein Ussinn met däm Name: „<strong>$1</strong>“ - 
denk dran, dat ene Metmaacher eije Dateie för et Ussinn han kann, un dat die met kleine Buchstave 
aanfange dun, alsu etwa: {{ns:User}}:Name/monobook.css, un {{ns:User}}:Name/monobook.js heiße.',
'updated'               => '(Aanjepack)',
'note'                  => '<strong>Opjepass:</strong>',
'previewnote'           => '<strong>Hee kütt nor de Vör-Aansich - Ding Änderunge sin noch nit jesechert!</strong>',
'session_fail_preview'  => '<strong>Schad: Ding Änderunge kunnte mer su nix met aanfange.

De Daate vun Dinge Login-Säschen sin nit öntlich erüvver jekumme, oder einfach ze alt.
Versök et jrad noch ens. Wann dat widder nit flupp, dann versök et ens met [[Special:Userlogout|Uslogge]] 
un widder Enlogge. Ävver pass op, datte Ding Änderunge dobei behälds! Zor Nud dun se eets ens bei Dir om Rechner 
avspeichere.</strong>',
'previewconflict'       => 'Hee die Vör-Aansich zeich dä Enhald vum bovvere Texfeld. Esu wööd dä Atikkel 
ussinn, wann De n jetz avspeichere däts.',
'session_fail_preview_html'=> '<strong>Schad: Ding Änderunge kunnte mer su nix met aanfange.<br />De Daate vun 
Dinge Login-Säschen sin nit öntlich erüvver jekumme, oder einfach ze alt.</strong>
Dat Wiki hee hät <i>rüh HTML</i> zojelooße, dröm weed de Vör-Aansich nit jezeich. Domet solls De jeschötz wääde - 
hoffe mer - un Aanjreffe met Java_Skripp jäje Dinge Kompjuter künne Der nix aandun.
<strong>Falls för Dich söns alles jod ussüht, versök et jrad noch ens. Wann dat widder nit flupp, dann versök et 
ens met [[Special:Userlogout|Uslogge]] un widder Enlogge. Ävver pass op, datte Ding Änderunge dobei behälds! 
Zor Nud dun se eets ens bei Dir om Rechner avspeichere.</strong>',
'importing'             => '„$1“ am Importeere',
'editing'               => 'De Sigg „$1“ ändere',
'editinguser' => 'Metmaacher <b>$1</b> ändere',
'editingsection'        => 'Ne Avschnedd vun dä Sigg: „$1“ ändere',
'editingcomment'        => '„$1“ Ändere (ene neue Avschnedd schrieve)',
'editconflict'          => 'Problemche: „$1“ dubbelt bearbeidt.',
'explainconflict'       => '<br />Ene andere Metmaacher hät aan dä Sigg och jet jeändert, un zwar nohdäm Do et Ändere aanjefange häs. Jetz ha\'mer dr Dress am Jang, un Do darfs et widder uszoteere.
<strong>Opjepass:</strong>
<ul><li>Dat bovvere Texfeld zeich die Sigg esu, wie se jetz em Momang jespeichert es, alsu met de Änderunge vun alle andere Metmaacher, die flöcker wie Do jespeichert han.</li><li>Dat ungere Texfeld zeich die Sigg esu, wie De se selver zoletz zerääch jebraselt häs.</li></ul>
Do muss jetz Ding Änderunge och in dat <strong>bovvere</strong> Texxfeld eren bränge. Natörlich ohne dä 
Andere ihr Saache kapott ze maache.
<strong>Nor wat em bovvere Texfeld steiht,</strong> dat weed üvvernomme un avjespeichert, wann De „<b 
style="padding:2px; background-color:#ddd; color:black">De Sigg Avspeichere</b>“ klicks. Bes dohin kanns De esu off 
wie De wells op „<b style="padding:2px; background-color:#ddd; color:black">Vör-Aansich zeije</b>“ un „<b 
style="padding:2px; background-color:#ddd; color:black">De Ungerscheed zeije</b>“ klicke, öm ze pröfe, watte ald 
jods jemaat häs.

Alles Klor?<br /><br />',
'yourtext'              => 'Dinge Tex',
'storedversion'         => 'De jespeicherte Version',
'nonunicodebrowser'     => '<strong>Opjepass:</strong> Dinge Brauser kann nit 
öntlich met däm Unicode un singe Buchstave ömjonn. Bes esu jod un 
nemm ene andere Brauser för hee die Sigg!',
'editingold'            => '<strong>Opjepass!<br />
Do bes en ahle, üvverhollte Version vun dä Sigg hee am Ändere.
Wann De die avspeichere deis,
wie se es,
dann jonn all die Änderunge fleute,
die zickdäm aan dä Sigg jemaht woode sin.
Alsu:
Bes De secher, watte mähs?
</strong>',
'yourdiff'              => 'Ungerscheede',
'copyrightwarning'      => 'Ding Beidräch stonn unger de [[$2]], süch $1. Wann De nit han wells, dat Dinge Tex ömjemodelt weed, un söns wohin verdeilt, dun en hee nit speichere. Mem Avspeichere sähs De och zo, dat et vun Dir selvs es, un/oder Do dat Rääch häs, en hee zo verbreide. Wann et nit stemmp, oder Do kanns et nit nohwiese, kann Dich dat en dr Bau bränge!',
'copyrightwarning2'     => 'De Beidräch en de {{SITENAME}} künne vun andere Metmaacher ömjemodelt 
oder fottjeschmesse wääde. Wann Der dat nit rääch es, schriev nix. Et es och nüdich, dat et vun Dir selvs es, oder dat Do dat Rääch häs, et hee öffentlich wigger ze jevve. Süch $1. Wann et nit stemmp, oder Do kanns et nit nohwiese, künnt Dich dat en dr Bau bränge!',
'longpagewarning'       => '<strong>Oppjepass:</strong> Dä Tex, dä De hee jescheck häs, dä es <strong>$1</strong> 
Kilobyte jroß. Manch Brauser kütt nit domet klor, wann et mieh wie <strong>32</strong> Kilobyte sin. Do künnts De drüvver nohdenke, dat Dinge en kleiner Stöckche ze zerkloppe.',
'longpageerror'         => '<big><strong>Janz schlemme Fähler:</strong></big>
Dä Tex, dä De hee jescheck häs, dä es <strong>$1</strong> Kilobyte jroß. 
Dat sin mieh wie <strong>$2</strong> Kilobyte. Dat künne mer nit speichere!
<strong>Maach kleiner Stöcke drus.</strong><br />',
'readonlywarning'       => '<strong>Opjepass:</strong> De Daatebank es jesperrt woode, wo Do ald am Ändere wors. 
Dä. Jetz kanns De Ding Änderunge nit mieh avspeichere. Dun se bei Dir om Rechner fasshalde un versök et späder 
noch ens.',
'protectedpagewarning'  => '<strong>Opjepass:</strong> Die Sigg hee es jäje Veränderunge jeschötz - wieso weed em <span 
class="plainlinks">[{{FULLURL:Special:Log/protect|page={{FULLPAGENAMEE}}}} Logboch]</span> stonn. Nor de  
Wiki-Köbese künne se ändere. Bes esu jod un hald Dich aan de Rejele för 
dä Fall!',
'semiprotectedpagewarning'=> '<strong>Opjepass:</strong> Die Sigg hee es halv jesperrt, wie mer sage, dat heiß, Do muss [[Special:Userlogin|aanjemeldt un enjelogg]] sin, wann De dran ändere wells.',
'templatesused'         => 'De Schablone, die vun dä Sigg hee jebruch wääde, sinn:',
'templatesusedpreview' => 'Schablone en dä Vör-Aansich hee: ',
'templatesusedsection' => 'Schablone en däm Avschnedd hee: ',
'edittools'             => '<!-- Dä Tex hee zeich et Wiki unger däm Texfeld zom „Ändere/Bearbeide“ un beim Texfeld vum „Huhlade“. -->',
'nocreatetitle'         => 'Enlogge es nüdich',
'nocreatetext'          => 'Sigge neu aanläje es nor möchlich, wann de [[Special:Userlogin|enjelogg]] bes. Der ohne kanns De ävver Sigge ändere, die ald do sin.',
'undofailed' => 'Undo donevve jejange',
'explainundofailed' => 'Dat Undo hät nit jeflupp. Enzwesche han andere dä Tex bearbeid. Bes esu jod un maach dat Undo vun Hand.',

# Account creation failure
'cantcreateaccounttitle'=> 'Kann keine Zojang enrichte',
'cantcreateaccounttext' => 'Aanmeldunge vun Ding IP-Adress [<strong>$1</strong>] sin jesperrt. Dat hät för jewöhnlich ene Jrund. Zom Beispill künnt sin, dat 
vill ze vill SPAM vun däm Bereich vun dä Adresse jekumme es.',

# History pages
#
'revhistory'            => 'De Versione',
'viewpagelogs'          => 'De LogBöcher för hee die Sigg',
'nohistory'             => 'Et jitt kein Versione vun dä Sigg.',
'revnotfound'           => 'Die Version ha\'mer nit jefunge.',
'revnotfoundtext'       => '<b>Dä.</b> Die ählere Version vun dä Sigg, wo De noh frochs, es nit do. Schad. Luur ens 
op die URL, die Dich herjebraht hät, die weed verkihrt sin, oder se es villeich üvverhollt, weil einer die Sigg 
fottjeschmesse hät?',
'loadhist'              => 'Dun de Liss met ahl Versione lade',
'currentrev'            => 'Neuste Version',
'revisionasof'          => 'Version vum $1',
'revision-info' => 'Revision as of $1 by $2',
'revision-nav' => '($1) $2 | $3 ($4) | $5 ($6)',
'previousrevision'      => 'â† De Revision dovör zeije',
'nextrevision'          => 'De Version donoh zeije â†’',
'currentrevisionlink'   => 'De neuste Version',
'cur'                   => 'neu',
'next'                  => 'wigger',
'last'                  => 'letz',
'orig'                  => 'Orjenal',
'histlegend'            => 'Hee kanns De Versione för et Verjliche ussöke: Dun met dä Knöpp die zweij markiere, 
zwesche dänne De de Ungerscheed jezeich krije wells, dann dröck „<b style="padding:2px; background-color:#ddd; 
color:black">Dun de markeete Versione verjliche</b>“ bei Dinge Taste, oder klick op ein vun dä Knöpp üvver oder 
unger de Liss.
Erklärung: (neu) = Verjliche met de neuste Version, (letz) = Verjliche met de Version ein doför, <b>M</b> = en 
kleine <b>M</b>ini-Änderung.',
'history_copyright'    => '-',
'deletedrev'            => '[fott]',
'histfirst'             => 'Ählste',
'histlast'              => 'Neuste',
'rev-deleted-comment'   => '(„Koot Zosammejefass, Quell“ usjeblendt)',
'rev-deleted-user'      => '(Metmaacher Name usjeblendt)',
'rev-deleted-text-permission'=> '<div class="mw-warning plainlinks">Die Version es fottjeschmesse woode. Jetz ka\'mer 
se nit mieh beluure. Ene Wiki Köbes künnt se ävver zeröck holle. Mieh drüvver, wat met däm Fottschmieße vun dä Sigg 
jewäse es, künnt Ehr em [{{FULLURL:Spezial:Log/delete|page={{PAGENAMEE}}}} Logboch] nohlese.</div>',
'rev-deleted-text-view' => '<div class="mw-warning plainlinks">Die Version es fottjeschmesse woode. Jetz ka\'mer se nit 
mieh beluure. Als ene Wiki-Köbes kriss De se ävver doch ze sinn, un künnts se 
och zeröck holle. Mieh drüvver, wat met däm Fottschmieße vun dä Sigg jewäse es, künnt Ehr em 
[{{FULLURL:Spezial:Log/delete|page={{PAGENAMEE}}}} Logboch] nohlese.</div>',
'rev-delundel'          => 'zeije/usblende',
'history-feed-title'    => 'Versione',
'history-feed-description'=> 'Ählere Versione vun dä Sigg en de {{SITENAME}}',
'history-feed-item-nocomment'=> '$1 öm $2',
'history-feed-empty'    => 'De aanjefrochte Sigg jitt et nit. Künnt sin, dat se enzwesche fottjeschmesse oder ömjenannt woode es. Kanns jo ens [[Special:Search|em Wiki söke looße]], öm zopass neu Sigge ze finge.',

# Revision deletion
#
'revisiondelete'        => 'Versione fottschmieße un widder zeröck holle',
'revdelete-nooldid-title' => 'Kein Version aanjejovve',
'revdelete-nooldid-text' => 'Do häs kein Version aanjejovve.',
'revdelete-selected'    => 'Usjewählte Version vun [[:$1]]:',
'revdelete-text'        => 'Dä fottjeschmesse Sigge ehre Enhald kanns De nit mieh aanluure. Se blieve ävver en de Liss met de Versione dren.

Ene Wiki Köbes kann de fottjeschmessene Krom immer noch aanluere un kann en och widder herholle, usser wann bei 
dem Wiki singe Installation dat anders fassjelaht woode es.',
'revdelete-legend'      => 'Dä öffentlije Zojang enschränke, för die Version:',
'revdelete-hide-text'   => 'Dä Tex vun dä Version usblende',
'revdelete-hide-comment'=> 'Dä Enhald vun „Koot Zosammejefass, Quell“ usblende',
'revdelete-hide-user'   => 'Däm Bearbeider sing IP Adress oder Metmaacher Name usblende',
'revdelete-hide-restricted'=> 'Dun dat och för de Wiki Köbese esu maache wie  för jede Andere',
'revdelete-log'         => 'Bemerkung för et LogBoch:',
'revdelete-submit'      => 'Op de aanjekrützte Version aanwende',
'revdelete-logentry'    => 'Zojang zo de Version verändert för [[$1]]',

# Diffs
#
'difference'            => '(Ungerscheed zwesche de Versione)',
'loadingrev'            => 'ben en Version för et Verjliche am lade',
'lineno'                => 'Reih $1:',
'editcurrent'           => 'Dun de neuste Version vun däm Atikkel ändere',
'selectnewerversionfordiff'=> 'Dun en neuere Version för et Verjliche ussöke',
'selectolderversionfordiff'=> 'Dun en ählere Version för et Verjliche ussöke',
'compareselectedversions'=> 'Dun de markeete Version verjliche',
'editundo' => 'undo',

# Search results
#
'searchresults'         => 'Wat beim Söke eruskom',
'searchresulttext'      => 'Luur op de Sigg üvver et [[{{ns:project}}:Söke en de {{SITENAME}}|Söke en de {{SITENAME}}]] noh, wann de mieh drüvver wesse wells, wie mer en de {{SITENAME}} jet fingk.',
'searchsubtitle'        => 'För Ding Froch noh „[[:$1]]“.',
'searchsubtitleinvalid' => 'För Ding Froch noh „$1“.',
'badquery'              => 'Verkihrte Aanfroch för et Söke',
'badquerytext'          => 'För Ding Froch för et Söke hät dat nix jebraht.
Zem Beispill künnt et sin, dat De noh enem janz koote Wood jefroch häs - kööter wie vier Buchstave künne mer 
einfach nit. Oder Do häs Dich vertipp, un noh „Kölle am am Rhing“ söke looße. Un et künnt sin, dat mer Ding 
Schrievwies nit en de Daatebank han. Wann et jeiht, dann dun doför jlich en Ömleitung enjevve!',
'matchtotals'           => '„$1“ kütt en <strong>$2</strong> Üvverschrefte un em Tex vun <strong>$3</strong> Atikkele för.',
'noexactmatch'          => 'Mer han kein Sigg met jenau däm Name „<strong>$1</strong>“ jefunge. Do kanns  se [[:$1|aanläje]], wann De wells.',
'titlematches'          => 'Zopass Üvverschrefte',
'notitlematches'        => 'Kein zopass Üvverschrefte',
'textmatches'           => 'Sigge met däm Täx',
'notextmatches'         => 'Kein Sigg met däm Tex',
'prevn'                 => 'de $1 doför zeije',
'nextn'                 => 'de nächste $1 zeije',
'viewprevnext'          => 'Bläddere: ($1) ($2) ($3).',
'showingresults'        => 'Unge wääde bes <strong>$1</strong> vun de jefunge Endräch jezeich, vun de Nummer <strong>$2</strong> av.',
'showingresultsnum'     => 'Unge sin <strong>$3</strong> vun de jefunge Endräch opjeliss, vun de Nummer <strong>$2</strong> av.',
'nonefound'             => '<strong>Opjepass:</strong> Wann beim Söke nix erus kütt, do kann dat dran lije, dat 
mer esu janz jewöhnliche Wööd, wie „hät“, „alsu“, „wääde“, un „sin“, uew. jar nit esu en de Daatebank dren han, 
dat se jefonge wääde künnte.',
'powersearch'           => 'Söke',
'powersearchtext'       => 'Sök en de Appachtemengs:<br />$1<br />$2 Zeich Ömleitunge<br />Sök noh $3 $9',
'searchdisabled'        => 'Dat Söke hee op de {{SITENAME}} es em Momang avjeschalt.
Dat weed vun dänne Sörver ad ens jemaat, domet de Lass op inne nit ze jroß weed,
un winnichstens dat normale Sigge Oprofe flöck jenoch jeiht.

Ehr künnt esu lang üvver en Sökmaschin vun usserhalv immer noch
Sigge op de {{SITENAME}} finge.
Et es nit jesaht,
dat dänne ihr Daate topaktuell sin,
ävver et es besser wie jar nix.',

'googlesearch' => '
<form method="get" action="http://www.google.com/search" id="googlesearch">
    <input type="hidden" name="domains" value="{{SERVER}}" />
    <input type="hidden" name="num" value="50" />
    <input type="hidden" name="ie" value="$2" />
    <input type="hidden" name="oe" value="$2" />

    <input type="text" name="q" size="31" maxlength="255" value="$1" />
    <input type="submit" name="btnG" value="$3" />
  <div>
    <input type="radio" name="sitesearch" id="gwiki" value="{{SERVER}}" checked="checked" /><label for="gwiki">{{SITENAME}}</label>
    <input type="radio" name="sitesearch" id="gWWW" value="" /><label for="gWWW">WWW</label>
  </div>
</form>',
'blanknamespace'        => '(Atikkele)',

# Preferences page
#
'preferences'           => 'ming Enstellunge',
#'preferences-summary'  => '',
'mypreferences' => 'My preferences',
'prefsnologin'          => 'Nit Enjelogg',
'prefsnologintext'      => 'Do mööts ald [[Special:Userlogin|enjelogg]] sin, öm Ding Enstellunge ze ändere.',
'prefsreset'            => 'De Enstellunge woodte jetz op Standard zeröck jesatz.',
'qbsettings'            => '„Flöcke Links“',
'qbsettings-none'	=> 'Fottlooße, dat well ich nit sinn',
'qbsettings-fixedleft'	=> 'Am linke Rand fass aanjepapp',
'qbsettings-fixedright'	=> 'Am rächte Rand fass aanjepapp',
'qbsettings-floatingleft'	=> 'Am linke Rand am Schwevve',
'qbsettings-floatingright'	=> 'Am rächte Rand am Schwevve',
'changepassword'        => 'Passwood ändere',
'skin'                  => 'Et Ussinn',
'math'                  => 'Mathematisch Formele',
'dateformat'            => 'Em Datum sing Fomat',
'datedefault'           => 'Ejaal - kein Vörliebe',
'datetime'              => 'Datum un Uhrzigge',
'math_failure'          => 'Fähler vum Parser',
'math_unknown_error'    => 'Fähler, dä mer nit kenne',
'math_unknown_function' => 'en Funktion, die mer nit kenne',
'math_lexing_error'     => 'Fähler beim Lexing',
'math_syntax_error'     => 'Fähler en de Syntax',
'math_image_error'      => 'De Ömwandlung noh PNG es donevve jejange. Dun ens noh de richtije Enstallation 
luure bei <i>latex</i>, <i>dvips</i>, <i>gs</i>, un <i>convert</i>. Oder sag et enem Sörver-Admin, oder enem 
Wiki Köbes.',
'math_bad_tmpdir'       => 'Dat Zwescheverzeichnis för de mathematische Formele lööt sich nit aanläje oder nix 
eren schrieve. Dat es Dress. Sag et enem Wiki-Köbes]] oder enem 
Sörver-Minsch.',
'math_bad_output'       => 'Dat Verzeichnis för de mathematische Formele lööt sich nit aanläje oder nix 
eren schrieve. Dat es Dress. Sag et enem Wiki-Köbes oder enem 
Sörver-Minsch.',
'math_notexvc'          => 'Dat Projamm <code>texvc</code> ha\'mer nit jefunge. Sag et enem 
Wiki-Köbes, enem Sörver-Minsch, oder luur ens en de 
<code>math/README</code>.',
'prefs-personal'        => 'De Enstellunge',
'prefs-rc'              => 'Neuste Änderunge',
'prefs-watchlist'       => 'De Oppassliss',
'prefs-watchlist-days'  => 'Aanzahl Dage för en ming Oppassliss aanzezeije:',
'prefs-watchlist-edits' => 'Aanzahl Änderunge för en ming verjrößerte Oppassliss aanzezeije:',
'prefs-misc'            => 'Söns',
'saveprefs'             => 'Fasshalde',
'resetprefs'            => 'Zeröck setze',
'oldpassword'           => 'Et ahle Passwood:',
'newpassword'           => 'Neu Passwood:',
'retypenew'             => 'Noch ens dat neue Passwood:',
'textboxsize'           => 'Beim Bearbeide',
'rows'                  => 'Reihe:',
'columns'               => 'Spalte:',
'searchresultshead'     => 'Beim Söke',
'resultsperpage'        => 'Zeich Treffer pro Sigg:',
'contextlines'          => 'Reihe för jede Treffer:',
'contextchars'          => 'Zeiche us de Ömjevvung, pro Reih:',
'stubthreshold'         => 'Aanzahl Zeiche vun wo av en Sigg als ene Atikkel zällt:',
'recentchangescount'    => 'Endräch en de Liss met de „Neuste Änderunge“:',
'savedprefs'            => 'Ding Enstellunge sin jetz jesechert.',
'timezonelegend'        => 'Ziggzone Ungerscheed',
'timezonetext'          => '<!-- Â¹ -->Dat sin de Stunde un Minutte zwesche de Zigg op de Uhre bei Dir am Oot un däm Sörver, dä met UTC läuf.',
'localtime'             => 'De Zigg op Dingem Kompjuter:',
'timezoneoffset'        => 'Dä Ungerscheed Â¹ es:',
'servertime'            => 'De Uhrzigg om Sörver es jetz:',
'guesstimezone'         => 'Fing et erus üvver dä Brauser',
'allowemail'            => 'E-Mail vun andere Metmaacher zolooße',
'defaultns'             => 'Dun standaadmäßich en hee dä 
[[{{ns:project}}:Appachtemeng_%E2%80%94_Wat_es_dat%3F|Appachtemengs]] söke:',
'default'               => 'Standaad',
'files'                 => 'Dateie',

# User rights
'userrights-lookup-user'=> 'Metmaacher Jruppe verwalte',
'userrights-user-editname'=> 'Metmaacher Name: <!-- -->',
'editusergroup'         => 'Däm Metmaacher sing Jruppe Räächde bearbeide',

'userrights-editusergroup'=> 'Metmaacher Jruppe aanpasse',
'saveusergroups'        => 'Metmaacher Jruppe avspeichere',
'userrights-groupsmember'=> 'Es en de Metmaacher Jruppe:<br />',
'userrights-groupsavailable'=> 'Es nit en de Metmaacher Jruppe:<br />',
'userrights-groupshelp' => 'Sök de Jruppe us, wo dä Metmaacher bei kumme soll oder druss erus soll. Jruppe, die De 
hee nit ussöks, blieve, wie se sin. Dat Ussöke kanns De bei de miehste Brausere met \'\'\'Ctrl + Links Klicke\'\'\' / \'\'\'Strg + Links Klicke\'\'\' maache.',

# Groups
'group'                 => 'Jrupp:',
'group-bot'             => 'Bots',
'group-sysop'           => 'Wiki Köbese',
'group-bureaucrat'      => 'Bürrokrade',
'group-all'             => '(all)',
'group-bot-member'      => 'Bot',
'group-sysop-member'    => 'Wiki Köbes',
'group-bureaucrat-member'=> 'Bürrokrad',
'grouppage-bot'         => '{{ns:project}}:Bots',
'grouppage-sysop'       => '{{ns:project}}:Wiki Köbes',
'grouppage-bureaucrat'  => '{{ns:project}}:Bürrokrad',

# Recent changes
#
'changes'               => 'Änderunge',
'recentchanges'         => 'Neuste Änderunge',
'recentchanges-url' => 'Special:Recentchanges',
'recentchangestext'     => 'Op dä Sigg hee sin de neuste Änderunge am Wiki opjeliss.',
'rcnote'                => 'Hee sin de letzte <strong>$1</strong> Änderunge us de letzte <strong>$2</strong> Dage vum $3 aan.',
'rcnotefrom'            => 'Hee sin bes op <strong>$1</strong> Änderunge zick <strong>$2</strong> opjeliss.',
'rclistfrom'            => 'Zeich de neu Änderunge vum $1 av',
'rcshowhideminor'       => '$1 klein Mini-Änderunge',
'rcshowhidebots'        => '$1 de Bots ehr Änderunge',
'rcshowhideliu'         => '$1 de aanjemeldte Metmaacher ehr Änderunge',
'rcshowhideanons'       => '$1 de namenlose Metmaacher ehr Änderunge',
'rcshowhidepatr'        => '$1 de aanjeluurte Änderunge',
'rcshowhidemine'        => '$1 ming eije Änderunge',
'rclinks'               => 'Zeich de letzte | $1 | Änderunge us de letzte | $2 | Dage, un dun | $3 |',
'diff'                  => 'Ungerscheed',
'hist'                  => 'Versione',
'hide'                  => 'Usblende:',
'show'                  => 'Zeije:',
'minoreditletter'       => 'M',
'newpageletter'         => 'N',
'boteditletter'         => 'B',
'sectionlink'           => '?',
'number_of_watching_users_RCview'       => '[$1]',
'number_of_watching_users_pageview'=> '[$1 Oppasser]',
'rc_categories'         => 'Nor de Saachjruppe (met „|“ dozwesche):',
'rc_categories_any'     => 'All, wat mer han',

# Upload
#
'upload'                => 'Daate huhlade',
'uploadbtn'             => 'Huhlade!',
'reupload'              => 'Noch ens huhlade',
'reuploaddesc'          => 'Zeröck noh de Sigg zem Huhlade.',
'uploadnologin'         => 'Nit Enjelogg',
'uploadnologintext'     => 'Do mööts ald [[Special:Userlogin|enjelogg]] sin, öm Daate huhzelade.',
'upload_directory_read_only'=> '<b>Doof:</b> En dat Verzeichnis <code>$1</code> för Dateie dren huhzelade, do kann dat Websörver Projramm nix erenschrieve.',
'uploaderror'           => 'Fähler beim Huhlade',
'uploadtext'            => '<div dir="ltr">Met däm Formular unge kanns de Belder oder ander Daate huhlade. Do 
kanns dann Ding Werk tirek enbinge, en dä Aate:<ul style="list-style:none outside none; 
list-style-position:outside; list-style-image:none; list-style-type:none"><li style="list-style:none outside none; 
list-style-position:outside; list-style-image:none; 
list-style-type:none"><code>\'\'\'[[{{ns:Image}}:\'\'\'\'\'Beldche\'\'\'\'\'.jpg]]\'\'\'</code></li><li
style="list-style:none outside none; list-style-position:outside; list-style-image:none; 
list-style-type:none"><code>\'\'\'[[{{ns:Image}}:\'\'\'\'\'Esu süht dat us\'\'\'\'\'.png | \'\'\'\'\'ene Tex, dä die
Brausere zeije, die kein Belder künne\'\'\'\'\']]\'\'\'</code></li><li style="list-style:none outside none; 
list-style-position:outside; list-style-image:none; 
list-style-type:none"><code>\'\'\'[[{{ns:Media}}:\'\'\'\'\'Su hürt sich dat aan\'\'\'\'\'.ogg]]\'\'\'</code></li></ul>
Usführlich met alle Möchlichkeite fings de dat bei de Hölp.
Wann De jetz entschlosse bes, dat De et hee huhlade wells:
* Aanluure, wat mer hee en de {{SITENAME}} ald han, kanns De en uns [[Special:Imagelist|Belder Liss]].
* Wenn De jet söke wells, eets ens nohluure wells, wat ald huhjelade, oder villeich widder fottjeschmesse wood, 
dat steiht em [[Special:Log/upload|Logboch vum Huhlade]].
Esu, un jetz loss jonn:</div>
== <span dir="ltr">Daate en de {{SITENAME}} lade</span> ==',
'uploadlog'             => 'LogBoch vum Dateie Huhlade',
'uploadlogpage'         => 'Logboch met de huhjelade Dateie',
'uploadlogpagetext'     => 'Hee sin de Neuste huhjelade Dateie opjeliss un wä dat jedon hät.',
'filename'              => 'Name vun dä Datei',
'filedesc'              => 'Beschrievungstex un Zosammefassung',
'fileuploadsummary'     => 'Beschrievungstex un Zosammefassung:',
'filestatus'            => 'Urhevver Räächsstatus',
'filesource'            => 'Quell',
'copyrightpage'         => '{{ns:project}}:Lizenz',
'copyrightpagename'     => 'Lizenz',
'uploadedfiles'         => 'Huhjelade Dateie',
'ignorewarning'         => 'Warnung üvverjonn, un Datei trotzdäm avspeichere.',
'ignorewarnings'        => 'Alle Warnunge üvverjonn',
'minlength'             => 'De Name vun de Dateie künne nit kööter wie drei Buchstave sin.',
'illegalfilename'       => 'Schad:
<br />
En däm Name vun dä Datei sin Zeiche enthallde,
die mer en Titele vun Sigge nit bruche kann.
<br />
Sök Der statt „$1“ jet anders us,
un dann muss de dat Dinge noch ens huhlade.',
'badfilename'           => 'De Datei es en „$1“ ömjedäuf.',
'badfiletype'           => '„.$1“ es kein vun de Fomate vun Belder, wo mer jetz jet met aanfange künnte.',
'largefile'             => 'De Datei es <strong>$2</strong> Byte jroß. Dateie huhzelade, die jrößer wie <strong>$1</strong> Byte sin, doför du\'mer avrode.',
'largefileserver'       => 'De Datei es ze jroß. Jrößer wie däm Sörver sing Enstellung erlaub.',
'emptyfile'             => 'Wat De hee jetz huhjelade häs, hät kein Daate dren jehatt. Künnt sin, dat De Dich 
verdon häs, un dä Name wo verkihrt jeschrevve. Luur ens ov De wirklich <strong>die</strong> Dateie hee 
huhlade wells.',
'fileexists'            => 'Et jitt ald en Datei met däm Name. Wann De op „<span style="padding:2px; 
background-color:#ddd; color:black">Datei avspeichere</span>“ klicks, weed se ersetz. Bes esu jod  un luur Der $1 
aan, wann De nit 100% secher bes.',
'fileexists-forbidden'  => 'Et jitt ald en Datei met däm Name. Jangk zeröck un lad se unger enem andere Name huh. [[{{ns:Image}}:$1|thumb|center|$1]]',
'fileexists-shared-forbidden'=> 'Et jitt ald en Datei met däm Name em jemeinsame Speicher. Jangk zeröck un lad se unger enem andere Name huh. [[{{ns:Image}}:$1|thumb|center|$1]]',
'successfulupload'      => 'Et Huhlade hät jeflupp',
'fileuploaded'          => 'De Datei „$1“ es jetz huhjelade.
Jangk op die Sigg met dä Datei ehr Beschrievung un do drach alles en wat De üvver se weiß.
Wo se her kom, Wä se jemaht hät un wann, un wat De Dich söns noch dran entsenne kanns.
Do küss De hen üvver dä Link: $2

Wann dat e Beld wor, do kanns De met:
:<code><nowiki>[[{{NS:Image}}:$1|thumb|Tex för unger dat Beld ze dun]]</code>
e Breefmarkebeldche op dä Sigg mole looße.',
'uploadwarning'         => 'Warnung beim Huhlade',
'savefile'              => 'Datei avspeichere',
'uploadedimage'         => 'hät huhjelade: „[[$1]]“',
'uploaddisabled'        => 'Huhlade jesperrt',
'uploaddisabledtext'    => 'Et Huhlade es jesperrt hee en däm Wiki.',
'uploadscripted'        => 'En dä Datei es HTML dren oder Code vun enem 
Skripp, dä künnt Dinge Brauser en do verkihrte Hals krije un usführe.',
'uploadcorrupt'         => 'Schad.
<br />
De Datei es kapott, hät en verkihrte File Name Extention, oder irjends ene andere Dress es passeet.
<br />
<br />
Luur ens noh dä Datei, un dann muss de et noch ens versöke.',
'uploadvirus'           => 'Esu ene Dress:
<br />
En dä Datei stich e Kompjutervirus!
<br />
De Einzelheite: $1',
'sourcefilename'        => 'Datei zem huhlade',
'destfilename'          => 'Unger däm Dateiname avspeichere',
'watchthisupload'       => 'Watch this page',
'filewasdeleted'        => 'Unger däm Name wood ald ens en Datei huhjelade. Die es enzwesche ävver widder fottjeschmesse woode. Luur leever eets ens en et $1 ih dat De se dann avspeichere deis.',

'upload-proto-error' => 'Verkihrt Protokoll',
'upload-proto-error-text' => 'Remote upload requires URLs beginning with <code>http://</code> or <code>ftp://</code>.',
'upload-file-error' => 'Internal error',
'upload-file-error-text' => 'Ene internal error es passeet beim Aanläje vun en Datei om Server.  Verzäll et enem system administrator.',
'upload-misc-error' => 'Dat Huhlaade jing donevve',
'upload-misc-error-text' => 'Dat Huhlaade jing donevve. Mer wesse nit woröm.  Pröf de URL un versök et noch ens.  Wann et nit flupp, verzäll et enem system administrator.',

# Some likely curl errors.  More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => "Couldn't reach URL",
'upload-curl-error6-text' => 'The URL provided could not be reached.  Please double-check that the URL is correct and the site is up.',
'upload-curl-error28' => 'Upload timeout',
'upload-curl-error28-text' => 'The site took too long to respond. Please check the site is up, wait a short while and try again. You may want to try at a less busy time.',

'license'               => 'Lizenz',
'nolicense'             => 'Nix usjesök',

'upload_source_url'     => ' (richtije öffentlije URL)',
'upload_source_file' => ' (en Datei op Dingem Kompjuter)',

# Image list
#
'imagelist'             => 'Belder, Tön, uew. (all)',
#'imagelist-summary' => '',
'imagelisttext'         => 'Hee küt en Liss vun <strong>$1</strong> Datei{{PLURAL:$1||e}}, zoteet $2.',
'imagelistforuser'      => 'Hee sühs De nor de Belder, die dä Metmaacher „$1“ huhjelade hät.',
'getimagelist'          => 'ben de Liss met de Dateiname am lade',
'ilsubmit'              => 'Sök',
'showlast'              => 'Zeich de letzte | $1 | Dateie, zoteet $2.',
'byname'                => 'nohm Name',
'bydate'                => 'nohm Datum',
'bysize'                => 'noh de Dateijröße',
'imgdelete'             => 'fott!',
'imgdesc'               => 'tex',
'imgfile'               => 'Datei',
'imglegend'             => 'Legende: (tex) = änder oder zeich de Beschrievungstex för die Datei.',
'imghistory'            => 'Versione',
'revertimg'             => 'retour',
'deleteimg'             => 'Fottschmieße',
'deleteimgcompletely'   => 'All Versione vun dä Datei fottschmieße',
'imghistlegend'         => 'Legende:
(neu) = dat es de Neuste Version -
(fott!) = schmieß de ahl Version fott! -
(retour) = jangk zeröck op de ahl Version -
Op et Datum klicke = Zeich de Version vun domols aan.',
'imagelinks'            => 'Links',
'linkstoimage'          => 'Hee kumme de Sigge, die op die Datei linke dun:',
'nolinkstoimage'        => 'Nix link op hee die Datei.',
'sharedupload'          => 'De Datei es esu parat jelaht, dat se en diverse, ungerscheedlije Projekte jebruch wääde kann.',
'shareduploadwiki'      => 'Mieh Informatione fings De hee: $1.',
'shareduploadwiki-linktext'=> 'Hee es en Datei beschrevve',
'shareddescriptionfollows' => '-',
'noimage'               => 'Mer han kein Datei met däm Name, kanns De ävver $1.',
'noimage-linktext'      => 'Kanns De huhlade!',
'uploadnewversion-linktext'=> 'Dun en neu Version vun dä Datei huhlade',
'imagelist_date'        => 'Datum',
'imagelist_name'        => 'Name',
'imagelist_user'        => 'Metmaacher',
'imagelist_size'        => 'Byte',
'imagelist_description' => 'Wat es op däm Beld drop?',
'imagelist_search_for'  => 'Sök noh däm Name vun däm Beld:',

# Mime search
#
'mimesearch'            => 'Belder, Tön, uew. üvver ehr MIME-Typ söke',
#'mimesearch-summary' => '',
'mimetype'              => 'MIME-Typ:',
'download'              => 'Erungerlade',

# Unwatchedpages
#
'unwatchedpages'        => 'Sigge, wo keiner drop oppass',
#'unwatchedpages-summary' => '',

# List redirects
'listredirects'         => 'Ömleitunge',
#'listredirects-summary' => '',

# Unused templates
'unusedtemplates'       => 'Schablone oder Baustein, die nit jebruch wääde',
#'unusedtemplates-summary' => '',
'unusedtemplatestext'   => 'Hee sin all de Schablone opjeliss, die em Appachtemeng „Schablon“ sin, die nit en 
ander Sigge enjefüg wääde. Ih De jet dovun fottschmieß, denk dran, se künnte och op en ander Aat jebruch 
wääde, un luur Der die ander Links aan!',
'unusedtemplateswlh'    => 'ander Links',

# Random redirect
'randomredirect'        => 'Zofällije Ömleitung',

# Statistics
#
'statistics'            => 'Statistike',
'sitestats'             => 'Statistike üvver de {{SITENAME}}',
'userstats'             => 'Statistike üvver de Metmaacher',
'sitestatstext'         => '* Et jitt en etwa <strong>$2</strong> richtije Atikkele hee.
* En de Daatebank sinner ävver <strong>$1</strong> Sigge, aan dänne bes jetz zosamme <strong>$4</strong> Mol jet 
jeändert woode es.  Em Schnedd woodte alsu <strong>$5</strong> Änderunge pro Sigg jemaht. <br /><small> (Do sin 
ävver de Klaafsigge metjezallt, de Sigge üvver de {{SITENAME}}, un usserdäm jede kleine Futz un Stümpchenssigg, 
Ömleitunge, Schablone, Saachjruppe, un ander Zeuch, wat mer nit jod als ene Atikkel zälle kann)</small>

* <strong>$8</strong> Belder, Tön, un esun ähnlije Daate woodte ald huhjelade.

* Et {{PLURAL:$7|es noch <strong>ein</strong> Aufgab|sin noch <strong>$7</strong> Aufgabe|es <strong>kein</strong> 
Aufgab mieh}} en de Liss.

* <strong>$3</strong> mol wood en Sigg hee avjerofe, dat sin <strong>$6</strong> Avrofe pro Sigg.',
'userstatstext'         => '* <strong>$1</strong> Metmaacher han sich bes jetz aanjemeldt.
* <strong>$2</strong> dovun sin $5, dat sinner <strong>$4%</strong>.',
'statistics-mostpopular'=> 'De miets beluurte Sigge',

'disambiguations'       => '„(Wat es dat?)“-Sigge',
#'disambiguations-summary'      => '',
'disambiguationspage'   => 'Template:Disambig',
'disambiguationstext'   => 'De Sigge,
die heenoh oppjeliss wääde, han Links op „(Wat es dat?)“-Sigge.

Als „(Wat es dat?)“-Sigge wääde all die jezallt, die de Schablon <strong>„$1“</strong> bruche.

Vill vun dänne sollte wall besser op en Sigg linke,
wo tirek de richtije Enhalde drop stonn.
Ävver nit unbedingk jede, et kütt op dä Link drop  aan.

Links us ander Appachtemengs wääde hee nit jezeich.',
'doubleredirects'       => 'Ömleitunge op Ömleitunge (Dubbel Ömleitunge sin verkihrt)',
#'doubleredirects-summary'      => '',
'doubleredirectstext'   => 'Dubbel Ömleitunge sin immer verkihrt, weil däm Wiki sing Soffwär de eetse Ömleitung 
folg, de zweite Ömleitung ävver dann aanzeije deit - un dat well mer jo normal nit han.
Hee fings De en jede Reih ene Link op de iertste un de zweite Ömleitung, donoh ene Link op de Sigg, wo de 
zweite Ömleitung hin jeiht. För jewöhnlich es dat dann och de richtije Sigg, wo de iertste Ömleitung ald hin 
jonn sollt.
Met däm „(Ändere)“-link kanns De de eetste Sigg tirek aanpacke. Tipp: Merk Der dat Lemma - de Üvverschreff - 
vun dä Sigg dovör.',
'brokenredirects'       => 'Ömleitunge, die en et Leere jonn (kapott oder op Vörrod aanjelaht)',
#'brokenredirects-summary'      => '',
'brokenredirectstext'   => 'Die Ömleitunge hee jonn op Sigge, die mer
[[#ast|<small>noch\'\'\'<sup>*</sup>?\'\'\'</small>]]
jar nit han.
<small id="ast">\'\'\'<sup>*</sup>?\'\'\' Die künnte op Vörrod aanjelaht sin.
Die alsu jod ussinn,
un wo die Sigge wo se drop zeije,
späder wall noch kumme wääde,
die sollt mer behalde.</small>',

# Miscellaneous special pages
#
'nbytes'                => '$1 Byte',
'ncategories'           => '{{PLURAL:$1| ein Saachjrupp | $1 Saachjruppe }}',
'nlinks'                => '{{PLURAL:$1|eine Link|$1 Links}}',
'nmembers'              => 'met {{PLURAL:$1|ein Sigg|$1 Sigge}} dren',
'nrevisions'            => '{{PLURAL:$1|ein Änderung|$1 Änderunge}}',
'nviews'                => '{{PLURAL:$1|1 Avrof|$1 Avrofe}}',

'lonelypages'           => 'Sigge, wo nix drop link',
#'lonelypages-summary'  => '',
'lonelypagestext'       => 'The following pages are not linked from other pages in this wiki.',
'uncategorizedpages'    => 'Sigge, die en kein Saachjrupp sin',
#'uncategorizedpages-summary' => '',
'uncategorizedcategories'=> 'Saachjruppe, die selvs en kein Saachjruppe sin',
#'uncategorizedcategories-summary' => '',
'uncategorizedimages'   => 'Belder, Tön, uew., die en kein Saachjruppe dren sin',
#'uncategorizedimages-summary' => '',
'unusedcategories'      => 'Saachjruppe met nix dren',
'unusedimages'          => 'Belder, Tön, uew., die nit en Sigge dren stäche',
'popularpages'          => 'Sigge, die off avjerofe wääde',
#'popularpages-summary' => '',
'wantedcategories'      => 'Saachjruppe, die mer noch nit han, die noch jebruch wääde',
#'wantedcategories-summary' => '',
'wantedpages'           => 'Sigge, die mer noch nit han, die noch jebruch wääde',
#'wantedpages-summary' => '',
'mostlinked'            => 'Sigge met de miehste Links drop',
#'mostlinked-summary' => '',
'mostlinkedcategories'  => 'Saachjruppe met de miehste Links drop',
#'mostlinkedcategories-summary' => '',
'mostcategories'        => 'Atikkele met de miehste Saachjruppe',
#'mostcategories-summary' => '',
'mostimages'            => 'Belder, Tön, uew. met de miehste Links drop',
#'mostimages-summary' => '',
'mostrevisions'         => 'Atikkele met de miehste Änderunge',
#'mostrevisions-summary' => '',
'allpages'              => 'All Sigge',
#'allpages-summary'     => '',
'prefixindex'           => 'All Sigge, die dänne ehr Name met enem bestemmte Wood oder Tex aanfange deit',
#'prefixindex-summary' => '',
'randompage'            => 'Zofällije Sigg',
'randompage-url'=> 'Special:Random',
'shortpages'            => 'Sigge zoteet vun koot noh lang',
#'shortpages-summary'     => '',
'longpages'             => 'Sigge zoteet vun lang noh koot',
#'longpages-summary'    => '',
'deadendpages'          => 'Sigge ohne Links dren',
#'deadendpages-summary' => '',
'deadendpagestext'      => 'The following pages do not link to other pages in this wiki.',
'listusers'             => 'Metmaacher',
#'listusers-summary'    => '',
'specialpages'          => 'Sondersigge',
#'specialpages-summary' => '',
'spheading'             => 'Sondersigge för all Metmaacher',
'restrictedpheading'    => 'Sondersigge met beschränkte Zojangsräächde',
'recentchangeslinked'   => 'Verlinkte Änderunge',
'rclsub'                => '(aan Sigge, noh dänne de Sigg: „$1“ hin link)',
'newpages'              => 'Neu Sigge',
#'newpages-summary'     => '',
'newpages-username'     => 'Metmaacher Name:',
'ancientpages'          => 'Sigge zoteet vun Ahl noh Neu',
#'ancientpages-summary' => '',
'intl'                  => 'Interwiki Links',
'move'                  => 'Ömnenne',
'movethispage'          => 'De Sigg ömnenne',
'unusedimagestext'      => '<p><strong>Opjepass:</strong> Ander Websigge künnte immer noch de Dateie hee tirek 
per URL aanspreche. Su künnt et sin, dat en 
Datei hee en de Liss steiht, ävver doch jebruch weed. Usserdäm, winnichstens bei neue Dateie, künnt sin, 
dat se noch nit en enem Atikkel enjebaut sin, weil noch Einer dran am brasselle es.</p>',
'unusedcategoriestext'  => 'De Saachjruppe hee sin enjerich, ävver jetz em Momang, es keine Atikkel un 
kein Saachjrupp dren ze finge.',

'booksources'           => 'Böcher',
#'booksources-summary'  => '',
'categoriespagetext'    => 'Dat sin de Saachjruppe vun däm Wiki hee.',
'data'                  => 'Daate',
'userrights'            => 'Metmaacher ehr Räächde verwalte',
#'userrights-summary' => '',
'groups' => 'User groups',

'booksourcetext'        => 'Hee noh kütt en Liss met Websigge,
wo mer vun de {{SITENAME}} nix wigger met ze dun han,
wo mer jet üvver Böcher erfahre
un zom Deil och Böcher kaufe kann.
Doför muss De Dich mänchmol allerdings eets ens aanmelde,
wat Koste und andere Jefahre met sich bränge künnt.
Wo et jeiht, jonn de
Links hee tirek op dat Boch,
wadder am Söke sid.',
'isbn'                  => 'ISBN',
'rfcurl' =>  'http://www.ietf.org/rfc/rfc$1.txt',
'pubmedurl' =>  'http://www.ncbi.nlm.nih.gov/entrez/query.fcgi?cmd=Retrieve&db=pubmed&dopt=Abstract&list_uids=$1',
'alphaindexline'        => '$1 â€¦ $2',
'version'               => 'Version vun de Wiki Soffwär zeije',
'log'                   => 'Logböcher ehr Opzeichnunge (all)',
'alllogstext'           => 'Dat hee es en jesamte Liss us all dä Logböcher för et [[Special:Log/block|Metmaacher 
oder IP Adress Sperre]], et [[Special:Log/protect|Sigge Sperre]], [[Special:Log/delete|et Sigge Fottschmieße]], et 
[[Special:Log/move|Sigge Ömnenne]], et [[Special:Log/renameuser|Metmaacher Ömnenne]], oder 
[[Special:Log/newusers|neue Metmaacher ehr Aanmeldunge]], et [[Special:Log/upload|Daate Huhlade]], 
[[Special:Log/rights|de Bürrokrade ehre Krom]], un de [[Special:Log/makebot|Bots ehr Status Änderunge]].
Dä Logböcher ehre Enhald ka\'mer all noh de Aat, de Metmaacher, oder de Sigge ehr Name, un esu, einzel zoteet 
aanluure.',
'logempty'              => '<i>Mer han kein passende Endräch en däm Logboch.</i>',

# Special:Allpages
'nextpage'              => 'De nächste Sigg: „$1“',
'allpagesfrom'          => 'Sigge aanzeije av däm Name:',
'allarticles'           => 'All Atikkele',
'allinnamespace'        => 'All Sigge (Em Appachtemeng „$1“)',
'allnotinnamespace'     => 'All Sigge (usser em Appachtemeng "$1")',
'allpagesprev'          => 'Zeröck',
'allpagesnext'          => 'Nächste',
'allpagessubmit'        => 'Loss Jonn!',
'allpagesprefix'        => 'Sigge zeije, wo dä Name aanfängk met:',
'allpagesbadtitle'      => 'Dä Siggename es nit ze jebruche. Dä hät e Köözel för en Sproch oder för ne 
Interwiki Link am Aanfang, oder et kütt e Zeiche dren för, wat en Siggename nit jeiht, villeich och mieh wie 
eins vun all däm op eimol.',

# Special:Listusers
'listusersfrom'         => 'Zeich de Metmaacher vun:',

# Email this user
#
'mailnologin'           => 'Do bes nit enjelogg.',
'mailnologintext'       => 'Do mööts ald aanjemeldt un [[Special:Userlogin|enjelogg]] sin, un en jode E-Mail 
Adress en Dinge [[Special:Preferences|ming Enstellunge]] stonn han, öm en E-Mail aan andere Metmaacher ze 
schecke.',
'emailuser'             => 'E-mail aan dä Metmaacher',
'emailpage'             => 'E-mail aan ene Metmaacher',
'emailpagetext'         => 'Wann dä Metmaacher en E-mail Adress aanjejovve hätt en singe Enstellunge, un die 
deit et och, dann kanns De met däm Fomular hee unge, en einzelne E-Mail aan dä Metmaacher schecke. Ding E-mail 
Adress, die De en Ding eije Enstellunge aanjejovve häs, die weed als de Avsender Adress en die E-Mail 
enjedrage. Domet kann, wä die E-Mail kritt, drop antwoode, un die Antwood jeiht tirek aan Dich.
Alles klor?',
'usermailererror'       => 'Dat E-Mail-Objek jov ene Fähler us:',
'defemailsubject'       => 'E-Mail üvver de {{SITENAME}}.',
'noemailtitle'          => 'Kein E-Mail Adress',
'noemailtext'           => 'Dä Metmaacher hät kein E-Mail Adress enjedrage, oder hä well kein E-Mail krije.',
'emailfrom'             => 'Vun',
'emailto'               => 'Aan',
'emailsubject'          => 'Üvver',
'emailmessage'          => 'Dä Tex',
'emailsend'             => 'Avschecke',
'emailccme' => 'Scheck mer en Kopie vun dä E-Mail. ',
'emailccsubject' => 'En Kopie vun Dinger E-Mail aan $1: $2',
'emailsent'             => 'E-Mail es ungerwähs',
'emailsenttext'         => 'Ding E-Mail es jetz lossjescheck woode.',

# Watchlist
'watchlist'             => 'ming Oppassliss',
'my-watchlist'             => 'ming Oppassliss',
'watchlistfor'          => '(för <strong>$1</strong>)',
'nowatchlist'           => 'En Ding Oppassliss es nix dren.',
'watchlistanontext'     => 'Do muss $1, domet de en Ding Oppassliss erenluure kanns, oder jet dran ändere.',
'watchlistcount'        => '<strong>En Ding Oppassliss {{PLURAL:$1|es eine Endrach|sinner $1 Endräch|es keine Endrach}} dren, de Klaafsigge metjezallt.</strong>',
'clearwatchlist'        => 'De Oppassliss fottschmieße',
'watchlistcleartext'    => 'Bes De secher, dat De Ding janze Oppassliss fottschmieße wells?',
'watchlistclearbutton'  => 'De janze Oppassliss fottschmieße',
'watchlistcleardone'    => 'Ding Oppassliss wood fottjeschmesse. {{PLURAL:$1|Dä Endrach es|De <strong>$1</strong> Endräch sin}} beim Düüvel.',
'watchnologin'          => 'Nit enjelogg',
'watchnologintext'      => 'Öm Ding Oppassliss ze ändere, mööts de ald [[Special:Userlogin|enjelogg]] sin.',
'addedwatch'            => 'En de Oppassliss jedon',
'addedwatchtext'        => 'Die Sigg „[[$1]]“ es jetz en Ding [[Special:Watchlist|Oppassliss]]. Av jetz, wann die Sigg 
verändert weed, oder ehr Klaafsigg, dann weed dat en de Oppassliss jezeich. Dä Endrach för die Sigg kütt en 
Fettschreff en de „[[Special:Recentchanges|Neuste Änderunge]]“, domet De dä do och flöck fings.
Wann de dä widder loss wääde wells us Dinger Oppassliss, dann klick op „Nimieh drop oppasse“ wann De die Sigg om 
Schirm häs.',
'removedwatch'          => 'Us de Oppassliss jenomme',
'removedwatchtext'      => 'Die Sigg „[[$1]]“ es jetz us de Oppassliss erusjenomme.',
'watch'                 => 'Drop Oppasse',
'watchthispage'         => 'Op die Sigg oppasse',
'unwatch'               => 'Nimieh drop Oppasse',
'unwatchthispage'       => 'Nimieh op die Sigg oppasse',
'notanarticle'          => 'Keine Atikkel',
'watchnochange'         => 'Keine Atikkel en Dinger Oppassliss es en dä aanjezeichte Zick verändert woode.',
'watchdetails'          => '*  <strong>$1</strong> Sigge sin en dä Oppassliss, ohne de Klaafsigge
* [[Special:Watchlist/edit|Zeich de janze Oppassliss aan, kanns De och ändere]]
* [[Special:Watchlist/clear|Schmieß dä janze Krom fott, un pass op nix mieh op]]',
'wlheader-enotif'       => '* Et E-mail Schecke es enjeschalt.',
'wlheader-showupdated'  => '* Wann se Einer jeändert hätt, zickdäm De se et letzte Mol aanjeluurt häs, sin die Sigge <strong>extra markeet</strong>.',
'watchmethod-recent'    => 'Ben de letzte Änderunge jäje de Oppassliss am pröfe',
'watchmethod-list'      => 'Ben de Oppassliss am pröfe, noh de letzte Änderung',
'removechecked'         => 'Schmieß de Sigge met Hökche us de Oppassliss erus',
'watchlistcontains'     => 'En de Oppassliss sinner <strong>$1</strong> Sigge.',
'watcheditlist'         => 'Hee en dä Liss met dä Sigge en Dinger Oppassliss, do dun e Hökche maache bei dänne 
Sigge, wo De nimieh drop oppasse wells. Wann De fäädich bes, dun unge op dä Knopp „<span style="padding:2px; 
background-color:#ddd; color:black">Schmieß de Sigge met Hökche us de Oppassliss erus</span>“ klicke, öm Ding Liss 
dann wirklich esu avzespeichere. Wann De hee en Sigg fottlööß, dann deit dä ehr Klaafsigg och erusfleeje, 
un ömjedriht.<br /><br /><hr />',
'removingchecked'       => 'Ben de ussjewählte Sigge us dä Oppassliss erus am schmieße',
'couldntremove'         => 'Kunnt „$1“ nit fottschmieße',
'iteminvalidname'       => 'Dä Endrach „$1“ hät ene kapodde Name.',
'wlnote'                => 'Hee sin de letzte <strong>$1</strong> Änderunge us de letzte <strong>$2</strong> Stund.',
'wlshowlast'            => 'Zeich de letzte | $1 | Stunde | $2 | Dage | $3 | aan, dun',
'wlsaved'               => 'Dat es en jesecherte Version vun Dinger Oppassliss.',
'wlhideshowown'         => '$1 ming eije Änderunge',
'wlhideshowbots'        => '$1 de Bots ehr Änderunge',
'wldone'                => 'Fäädich.',

'enotif_mailer'         => 'Dä {{SITENAME}} Nachrichte Versand',
'enotif_reset'          => 'Setz all Änderunge op „Aanjeluurt“ un Erledich.',
'enotif_newpagetext'    => 'Dat es en neu aanjelahte Sigg.',
'changed'               => 'jeändert',
'created'               => 'neu aanjelaht',
'enotif_subject'        => '{{SITENAME}}: Sigg "$PAGETITLE" vun "$PAGEEDITOR" $CHANGEDORCREATED.',
'enotif_lastvisited'    => 'Luur unger „$1“ - do fings de all die Änderunge zick Dingem letzte Besoch hee.',
'enotif_body'           => 'Leeven $WATCHINGUSERNAME,
en de {{SITENAME}} wood die Sigg „$PAGETITLE“ am $PAGEEDITDATE vun „$PAGEEDITOR“ $CHANGEDORCREATED, unger 
$PAGETITLE_URL fings Do de Neuste Version.
$NEWPAGE
Koot Zosammejefass, Quell: „$PAGESUMMARY“ $PAGEMINOREDIT
Do kanns dä Metmaacher „$PAGEEDITOR“ aanspreche:
* E-Mail: $PAGEEDITOR_EMAIL
* wiki: $PAGEEDITOR_WIKI
Do kriss vun jetz aan kein E-Mail mieh, bes dat Do Der die Sigg aanjeluurt häs. Do kanns ävver och all die E-Mail 

Merker för die Sigge en Dinger Oppassliss op eimol ändere.

Ene schöne Jroß vun de {{SITENAME}}.

--
Do kanns hee Ding Oppassliss ändere:
{{FULLURL:Special:Watchlist/edit}}

Do kanns hee noh Hölp luure:
{{FULLURL:int:MediaWiki:Helppage}}',

# Delete/protect/revert
#
'deletepage'            => 'Schmieß die Sigg jetz fott',
'confirm'               => 'Dä Schotz för die Sigg ändere',
'excontent'             => 'drop stundt: „$1“',
'excontentauthor'       => 'drop stundt: „$1“ un dä einzije Schriever woh: „$2“',
'exbeforeblank'         => 'drop stundt vörher: „$1“',
'exblank'               => 'drop stundt nix',
'confirmdelete'         => 'Dat Fottschmieße muss bestätich wääde:',
'deletesub'             => '(De Sigg „$1“ soll fottjeschmesse wääde)',
'historywarning'        => '<strong>Opjepass:</strong> Die Sigg hät ene janze Püngel Versione',
'confirmdeletetext'     => 'Do bes koot dovör, en Sigg för iwich fottzeschmieße. Dobei verschwind och de janze Verjangenheit vun dä Sigg us de Daatebank, met all ehr Änderunge un Metmaacher Name, un all dä Opwand, dä do dren stich. Do muss hee jetz bestätije, dat de versteihs, wat dat bedügg, un dat De weiß, wat Do do mähs.
<strong>Dun et nor, wann De met de [[{{ns:project}}:Üvver et Sigge Fottschmieße|Rejele doför]] wirklich zosamme 
jeihs!</strong>',
'actioncomplete'        => 'Erledich',
'deletedtext'           => 'De Sigg „$1“ es jetz fottjeschmesse woode. Luur Der „$2“ aan, do häs De en Liss met de Neuste fottjeschmesse Sigge.',
'deletedarticle'        => 'hät fottjeschmesse: „[[$1]]“',
'dellogpage'            => 'Logboch met de fottjeschmesse Sigge',
'dellogpagetext'        => 'Hee sin de Sigge oppjeliss, die et neus fottjeschmesse woodte.',
'deletionlog'           => 'Dat Logboch met de fottjeschmesse Sigge dren',
'reverted'              => 'Han de ählere Version vun dä Sigg zoröck jehollt.',
'deletecomment'         => 'Aanlass för et Fottschmieße',
'imagereverted'         => 'Dat Beld es jetz op de Version vun fröher zeröckjesatz.',
'rollback'              => 'Änderunge Zeröcknemme',
'rollback_short'        => 'Zeröcknemme',
'rollbacklink'          => 'Zeröcknemme',
'rollbackfailed'        => 'Dat Zeröcknemme jingk sheiv',
'cantrollback'          => 'De letzte Änderung zeröckzenemme es nit möchlich. Dä letzte Schriever es dä einzije, dä aan dä Sigg hee jet jedon hät!',
'alreadyrolled'         => '<strong>Dat wor nix!</strong>
Mer künne de letzte Änderunge vun dä Sigg „[[$1]]“ vum Metmaacher „[[User:$2|$2]]“ (?[[User talk:$2|däm sing Klaafs]]) nimieh zeröcknemme, dat hät ene Andere enzwesche ald jedon.
De Neuste letzte Änderung es jetz vun däm Metmaacher „[[User:$3|$3]]“ (?[[User talk:$3|däm sing Klaafs]]).',
'editcomment'           => 'Bei dä Änderung stundt: „<i>$1</i>“.',
'revertpage'            => 'Änderunge vun däm Metmaacher „[[User:$2|$2]]“ (?[[User talk:$2|däm sing Klaafs]]) fottjeschmesse, un doför de letzte Version vum „[[User:$1|$1]]“ widder zeröckjehollt',
'sessionfailure'        => 'Et jov wall e technisch Problem met Dingem Login. Dröm ha\'mer dat us Vörsich jetz nit jemaht, domet mer nit villeich Ding Änderung däm verkihrte Metmaacher ungerjubele. Jangk zeröck un versök et noch ens.',
'protectlogpage'        => 'Logboch vum Sigge Schötze',
'protectlogtext'        => 'Hee es de Liss vun Sigge, die jeschötz oder frei jejovve woode sin.',
'protectedarticle'      => 'hät jeschötz: „[[$1]]“',
'unprotectedarticle'    => 'Schotz för „[[$1]]“ opjehovve',
'protectsub'            => '(Sigge Schotz för „$1“ ändere)',
'confirmprotecttext'    => 'Wells De die Sigg schötze?',
'confirmprotect'        => 'Sigg schötze',
'protectmoveonly'       => 'Nor jäje et Ömnenne schötze',
'protectcomment'        => 'Dä Jrund oder Aanlass för et Schötze',
'unprotectsub'          => '(Schotz för „$1“ ophevve)',
'confirmunprotecttext'  => 'Wells De die Sigg frei jevve un dä ehre Schotz ophevve?',
'confirmunprotect'      => 'Sigg frei jevve',
'unprotectcomment'      => 'Dä Aanlass för dä Schotz opzehevve',
'protect-unchain'       => 'Et Schötze jäje Ömnenne extra enstelle looße',
'protect-text'          => 'Hee kanns De dä Schotz jäje Veränderunge för de Sigg „$1“ aanluure un ändere. Em <span class="plainlinks">[{{FULLURL:Special:Log/protect|page={{FULLPAGENAMEE}}}} Logboch]</span> fings De ählere Änderunge vun däm Schotz, wann et se jitt. Bes esu jod un hald Dich aan de Rejele för esu Fäll!',
'protect-viewtext'      => 'Ding Berechtijung als ene Metmaacher es nit jenoch, öm dä Siggeschotz ze ändere.

Hee de aktuell Enstellunge för die Sigg „<strong>$1</strong>“:',
'protect-default'       => '-(Standaad)-',
'protect-level-autoconfirmed'=> 'nor Metmaacher dranlooße, die sich aanjemeldt han',
'protect-level-sysop'   => 'Nor de Wiki Köbese dranlooße',

# restrictions (nouns)
'restriction-edit'      => 'An et Ändere â€¦',
'restriction-move'      => 'An et Ömnenne â€¦',

# Undelete
'undelete'              => 'Fottjeschmessene Krom aanluure/zeröckholle',
'undeletepage'          => 'Fottjeschmesse Sigge aanluure un widder zeröckholle',
'viewdeletedpage'       => 'Fottjeschmesse Sigge aanzeije',
'undeletepagetext'      => 'De Sigge heenoh sin fottjeschmesse, mer künne se ävver immer noch usem Müllemmer eruskrose.',
'undeleteextrahelp'     => 'Öm de janze Sigg met all ehre Versione widder ze holle, looß all de Versione ohne Hökche, un klick op „<b style="padding:2px; background-color:#ddd; color:black">Zeröckholle!</b>“.
Öm bloß einzel Versione zeröckzeholle, maach Hökche aan die Versione, die De widder han wells, un dann dun „<b style="padding:2px; background-color:#ddd; color:black">Zeröckholle!</b>“ klicke.
Op „<b style="padding:2px; background-color:#ddd; color:black">De Felder usleere</b>“
klick, wann De all Ding Hökche un Ding „Erklärung (för en et Logboch):“ widder fott han wells.',
'undeletearticle'       => 'Ene fottjeschmessene Atikkel widder zeröckholle',
'undeleterevisions'     => '<strong>$1</strong> Versione en et Archiv jedon',
'undeletehistory'       => 'Wann De die Sigg widder zeröckholls,
dann kriss De all fottjeschmesse Versione widder.
Wann enzwesche en neu Sigg unger däm ahle Name enjerich woode es,
dann wääde de zeröckjehollte Versione einfach als zosätzlije äldere Versione för die neu Sigg enjerich.
Die neu Sigg weed nit ersetz.',
'undeletehistorynoadmin'=> 'Die Sigg es fottjeschmesse woode. Dä Jrund döför es en de Liss unge ze finge, jenau esu wie de Metmaacher, wo de Sigg verändert han, ih dat se fottjeschmesse wood. Wat op dä Sigg ehre fottjeschmesse ahle Versione stundt, dat künne nor de Wiki Köbese noch aansinn (un och widder zeröckholle)',
'undeleterevision'      => 'Fottjeschmesse Versione nohm Stand vum $1',
'undeleterevision-missing' => 'De Version stemmp nit. Dat wor ene verkihrte Link, oder de Version wood usem Archiv zeröck jehollt, oder fottjeschmesse.',
'undeletebtn'           => 'Zeröckholle!',
'undeletereset'         => 'De Felder usleere',
'undeletecomment'       => 'Erklärung (för en et Logboch):',
'undeletedarticle'      => '„$1“ zeröckjehollt',
'undeletedrevisions'    => '{{PLURAL:$1|ein Version|$1 Versione}} zeröckjehollt',
'undeletedrevisions-files'=> 'Zesammejenomme <strong>$1</strong> Versione vun <strong>$2</strong> Dateie zeröckjehollt',
'undeletedfiles'        => '<strong>$1</strong> Dateie zeröckjehollt',
'cannotundelete'        => '<strong>Dä.</strong> Dat Zeröckholle jing donevve. Möchlich, dat ene andere Metmaacher flöcker wor, un et ald et eets jedon hät, un jetz es die Sigg ald widder do jewäse.',
'undeletedpage'         => '<big><strong>De Sigg „$1“ es jetz widder do</strong></big>
Luur Der et [[Special:Log/delete|Logboch met de fottjeschmesse Sigge]] aan, do häs De de Neuste fottjeschmesse 
un widder herjehollte Sigge.',

# Namespace form on various pages
'namespace'             => 'Appachtemeng:',
'invert'                => 'dun de Uswahl ömdrije',

# Contributions
#
'contributions'         => 'Däm Metmaacher sing Beidräch',
'mycontris'             => 'ming Beidräch',
'contribsub'            => 'För dä Metmaacher: $1',
'nocontribs'            => 'Mer han kein Änderunge jefonge, en de Logböcher, die do passe däte.',
'ucnote'                => 'Hee sin däm Metmaacher sing letzte <strong>$1</strong> Änderunge vun de letzte <strong>$2</strong> Dage.',
'uclinks'               => 'Zeich de letzte <strong>$1</strong> Beidräch, Zeich de letzte <strong>$2</strong> Dage.',
'uctop'                 => ' (Neuste)',
'newbies'               => 'Neu Metmaacher',

'sp-newimages-showfrom' => 'Zeich de neu Belder av däm $1',

'sp-newimages-showfrom' => 'Zeich de neu Belder av däm $1',
'sp-contributions-newest'=> 'Neuste',
'sp-contributions-oldest'=> 'Ählste',
'sp-contributions-newer'=> 'Neuste $1',
'sp-contributions-older'=> 'Ähler $1',
'sp-contributions-newbies-sub'=> 'För neu Metmaacher',

# What links here
#
'whatlinkshere'         => 'Wat noh hee link',
'notargettitle'         => 'Keine Bezoch op e Ziel',
'notargettext'          => 'Et fählt ene Metmaacher oder en Sigg, wo mer jet zo erusfinge oder oplisste solle.',
'linklistsub'           => '(Liss met de Links)',
'linkshere'             => 'Dat sin de Sigge, die op <strong>„[[:$1]]“</strong> linke dun:',
'nolinkshere'           => 'Kein Sigg link noh <strong>„[[:$1]]“</strong>.',
'isredirect'            => 'Ömleitungssigg',
'istemplate'            => 'weed enjeföch',

# Block/unblock IP
#
'blockip'               => 'Block user',
'blockiptext'   => "Use the form below to block write access
from a specific IP address or username.
This should be done only only to prevent vandalism, and in
accordance with [[{{ns:project}}:Policy|policy]].
Fill in a specific reason below (for example, citing particular
pages that were vandalized).",
'blockip'               => 'Metmaacher sperre',
'blockiptext'           => 'Hee kanns De bestemmte Metmaacher oder 
IP-Adresse sperre, su dat se hee em Wiki nit mieh 
schrieve und Sigge ändere künne. Dat sollt nor jedon wääde om sujenannte 
Vandaale ze bremse. Un mer müsse uns dobei natörlich aan uns 
[[{{ns:project}}:Policy|Rejele]] för sun Fäll halde.
Drag bei „Aanlass“ ene möchlichs jenaue Jrund en, wöröm dat Sperre passeet. Nenn un Link op de Sigge wo Einer kapott jemaat hät, zem Beispill.
Luur op [[Special:Ipblocklist|de Liss met jesperrte IP_Adresse]] wann de ne Üvverbleck üvver de Sperrunge han wells, un och wann De se ändere wells.',
'ipaddress'             => 'IP-Adress',
'ipadressorusername'    => 'IP Adress oder Metmaacher Name',
'ipbexpiry'             => 'Duur för wie lang',
'ipbreason'             => 'Aanlass',
'ipbanononly'           => 'Nor de namelose Metmaacher sperre',
'ipbcreateaccount'      => 'Neu aanmelde verbeede',
'ipbenableautoblock'    => 'Dun automatisch de letzte IP-Adress sperre, die dä Metmaacher jehatt hät, un och all die IP-Adresse, vun wo dä versök, jet ze ändere.',
'ipbsubmit'             => 'Dun dä Metmaacher sperre',
'ipbother'              => 'En ander Zigg',
'ipboptions'            => '1 Stund:1 hour,2 Stund:2 hours,3 Stund:3 hours,6 Stund:6 hours,12 Stund:12 
hours,1 Dach:1 day,3 Däch:3 days,1 Woch:1 week,2 Woche:2 weeks,3 Woche:3 weeks,1 Mond:1 month,3 Mond:3 
months,6 Mond:6 months,9 Mond:9 months,1 Johr:1 year,2 Johre:2 years,3 Johre:3 years,Unbejrenz:infinite',
'ipbotheroption'        => 'Söns wie lang',
'badipaddress'          => 'Wat De do jeschrevve häs, dat es kein öntlije 
IP-Adress.',
'blockipsuccesssub'     => 'De IP-Adress es jesperrt',
'blockipsuccesstext'    => '[[Special:Contributions/$1|$1]] es jetz jesperrt.
Luur op [[Special:Ipblocklist|de Liss met jesperrte IP_Adresse]] wann de ne Üvverbleck üvver de Sperrunge han wells, 
un och wann De se ändere wells.',
'unblockip'             => 'Dä Medmacher widder maache looße',
'unblockiptext'         => 'Hee kanns De vörher jesperrte IP_Adresse oder Metmaacher widder freijevve, un dänne esu dat Rääch för ze Schrieve hee em Wiki widder jevve.
Luur op [[Special:Ipblocklist|de Liss met jesperrte IP_Adresse]] wann de ne Üvverbleck üvver de Sperrunge han wells, 
un och wann De se ändere wells.',
'ipusubmit'             => 'Dun de Sperr för die Adress widder ophevve',
'unblocked'             => '[[User:$1|$1]] wood widder zojelooße',
'ipblocklist'           => 'Liss met jesperrte IP-Adresse un Metmaacher Name',
#'ipblocklist-summary'  => '',
'blocklistline'         => '$1, $2 hät „$3“ jesperrt ($4)',
'infiniteblock'         => 'för iwich',
'expiringblock'         => 'endt am $1',
'anononlyblock'         => 'nor anonyme',
'noautoblockblock' => 'automatisch Sperre avjeschalt',
'createaccountblock'    => 'Aanmelde nit möchlich',
'ipblocklistempty'      => 'Et es nix en de Liss met  jesperrte IP-Adresse un Metmaacher Name.',
'blocklink'             => 'Sperre',
'unblocklink'           => 'widder freijevve',
'contribslink'          => 'Beidräch',
'autoblocker'           => 'Automatich jesperrt. Ding IP_Adress wood vör kootem vun däm Metmaacher „[[User:$1|$1]]“ jebruch. Dä es jesperrt woode wäje: „<i>$2</i>“',
'blocklogpage'          => 'Logboch met Metmaacher-Sperre',
'blocklogentry'         => '„[[$1]]“ jesperrt, för $2',
'blocklogtext'          => 'Hee es dat Logboch för et Metmaacher Sperre un Freijevve. Automatich jesperrte 
IP-Adresse sin nit hee, ävver em 
[[Special:Ipblocklist|Logboch met jesperrte IP-Adresse]] ze finge.',
'unblocklogentry'       => 'Metmaacher „[[User:$1|$1]]“ freijejovve',
'range_block_disabled'  => 'Adresse Jebeede ze sperre, es nit erlaub.',
'ipb_expiry_invalid'    => 'De Duur es Dress. Jevv se richtich aan.',
'ipb_already_blocked'   => '„$1“ es ald jesperrt',
'ip_range_invalid'      => 'Dä Bereich vun IP_Adresse es nit en Oodnung.',
'proxyblocker'          => 'Proxy_Blocker',
'ipb_cant_unblock'      => 'Ene Fähler: De Sperr Nummer $1 es nit ze finge. Se künnt ald widder freijejovve woode sin.',
'proxyblockreason'      => 'Unger Ding IP_Adress 
läuf ene offe Proxy. Dröm kanns De hee em Wiki nix maache. Schwaad met Dingem System-Minsch oder Netzwerk-Techniker (ISP Internet Service Provider) un 
verzäll dänne vun däm Risiko för ehr Secherheit!',
'proxyblocksuccess'     => 'Fäädich',
'sorbs'                 => 'SORBS DNSbl',
'sorbsreason'           => 'Ding IP-Adress weed en de 
[http://www.sorbs.net SORBS] DNSbl als ene offe 
Proxy jeliss. Schwaad met Dingem System-Minsch oder Netzwerk-Techniker 
(ISP Internet Service Provider) drüvver, un verzäll dänne vun däm Risiko för ehr Secherheit!',
'sorbs_create_account_reason'=> 'Ding IP-Adress weed en 
[http://www.sorbs.net SORBS] DNSbl als ene offe 
Proxy jeliss. Dröm kanns De Dich hee em Wiki nit als ene neue Metmaacher aanmelde. Schwaad met Dingem System-Minsch oder Netzwerk-Techniker oder (ISP Internet Service Provider) drüvver, un verzäll dänne vun däm Risiko för ehr Secherheit!',

# Developer tools
#
'lockdb'                => 'Daatebank sperre',
'unlockdb'              => 'Daatebank freijevve',
'lockdbtext'            => 'Nohm Sperre kann keiner mieh Änderunge maache an sing Oppassliss, aan Enstellunge, Atikelle, uew. un neu Metmaacher jitt et och nit. Bes de secher, datte dat wells?',
'unlockdbtext'          => 'Nohm Freijevve es de Daatebank nit mieh jesperrt, un all de normale Änderunge wääde widder möchlich. Bes de secher, datte dat wells?',
'lockconfirm'           => 'Jo, ich well de Daatebank jesperrt han.',
'unlockconfirm'         => 'Jo, ich well de Daatebank freijevve.',
'lockbtn'               => 'Daatebank sperre',
'unlockbtn'             => 'Daatebank freijevve',
'locknoconfirm'         => 'Do häs kei Hökche en däm Feld zem Bestätije jemaht.',
'lockdbsuccesssub'      => 'De Daatebank es jetz jesperrt',
'unlockdbsuccesssub'    => 'De Daatebank es jetz freijejovve',
'lockdbsuccesstext'     => 'De Daatebank vun de {{SITENAME}} jetz jesperrt.<br /> Dun se widder freijevve, wann Ding Waadung durch es.',
'unlockdbsuccesstext'   => 'De Daatebank es jetz freijejovve.',
'lockfilenotwritable'   => 'De Datei, wo de Daatebank met jesperrt wääde wööd, künne mer nit aanläje, oder nit dren schrieve. Esu ene Dress! Dat mööt dä Websörver ävver künne! Verzäll dat enem Verantwortliche för de Installation vun däm Sörver oder repareer et selvs, wann De et kanns.',
'databasenotlocked'     => '<strong>Opjepass:</strong> De Daatebank es <strong>nit</strong> jesperrt.',

# Rights log
'rightslog'             => 'Logboch för Änderunge aan Metmaacher-Räächde',
'rightslogtext'         => 'Hee sin de Änderunge an Metmaacher ehre Räächde opjeliss. Op de Sigge üvver 
Metmaacher, Wiki_Köbese, 
Bürrokrade, Stewards, â€¦ kanns De nohlese, wat domet es.',
'rightslogentry'        => 'hät däm Metmaacher „$1“ sing Räächde vun „$2“ op „$3“ ömjestallt',
'rightsnone'            => '(nix)',

# Move page
#
'movepage'              => 'Sigg Ömnenne',
'movepagetext'          => 'Hee kanns De en Sigg en de {{SITENAME}} ömnenne. Domet kritt die Sigg ene neue Name, un 
all vörherije Versione vun dä Sigg och. Unger däm ahle Name weed automatisch en 
Ömleitung op dä neue Name enjedrage. Links op dä 
ahle Name blieve ävver wie se wore. Dat heiß, Do muss selver nohluure, ov do jetz 
[[Special:Doubleredireects|dubbelde]] oder [[Special:Doubleredireects|kapodde]] Ömleitunge bei eruskumme. 
Wann De en Sigg ömnenne deis, häs Do och doför ze sorje, dat de betroffene Links do henjonn, wo se hen jonn solle. 
Alsu holl Der de Liss „Wat noh hee link“ un jangk se durch!
De Sigg weed <strong>nit</strong> ömjenannt, wann et met däm neue Name ald en Sigg jitt, <strong>usser</strong> do 
es nix drop, oder et es en Ömleitung un se es noch nie jeändert woode. Esu ka\'mer en Sigg jlich widder zeröck 
ömnenne, wa\'mer sich mem Ömnenne verdonn hät, un mer kann och kein Sigge kapottmaache, wo ald jet drop steiht.
<strong>Oppjepass!</strong> Wat beim Ömnenne erus kütt, künnt en opfällije un villeich stürende Änderung am Wiki 
sin, besonders bei off jebruchte Sigge. Alsu bes secher, datte versteihs, watte hee am maache bes, ih dattet mähs!',
'movepagetalktext'      => 'Dä Sigg ehr Klaafsigg, wann se ein hät, weed automatisch met  ömjenannt, 

\'\'\'usser\'\'\' wann:
* de Sigg en en ander Appachtemeng kütt,
* en Klaafsigg met däm neue Name ald do es, un et steiht och jet drop,
* De unge en däm Kääsje \'\'\'kei\'\'\' Hökche aan häs.

En dänne Fäll, muss De Der dä Enhald vun dä Klaafsigge selvs vörnemme, un eröm kopeere
watte bruchs.',
'movearticle'           => 'Sigg Ömnenne',
'movenologin'           => 'Nit Enjelogg',
'movenologintext'       => 'Do mööts ald aanjemeldt un [[Special:Userlogin|enjelogg]] sin, öm en Sigg ömzenenne.',
'newtitle'              => 'op dä neue Name',
'movepagebtn'           => 'Ömnenne',
'pagemovedsub'          => 'Dat Ömnenne hät jeflupp',
'pagemovedtext'         => 'De Sigg „[[$1]]“ es jetz ömjenannt en „[[$2]]“.',
'articleexists'         => 'De Sigg met däm Name jitt et ald, oder dä Name ka\'mer oder darf mer nit bruche.<br />Do muss Der ene andere Name ussöke.',
'talkexists'            => '<strong>Opjepass:</strong> De Sigg selver woodt jetz ömjenannt, ävver dä ehr Klaafsigg kunnte mer nit met ömnenne. Et jitt ald ein met däm neue Name. Bes esu jod un dun die zwei vun Hand zosamme läje!',
'movedto'               => 'ömjenannt en',
'movetalk'              => 'dä ehr Klaafsigg met ömnenne',
'talkpagemoved'         => 'De Klaafsigg dozo wood met ömjenannt.',
'talkpagenotmoved'      => 'De Klaafsigg dozo wood <strong>nit</strong> ömjenannt.',
'1movedto2'             => 'hät de Sigg vun „[[$1]]“ en „[[$2]]“ ömjenannt.',
'1movedto2_redir'       => 'hät de Sigg vun „[[$1]]“ en „[[$2]]“ ömjenannt un doför de ahl Ömleitungs-Sigg fottjeschmesse.',
'movelogpage'           => 'Logboch met de ömjenannte Sigge',
'movelogpagetext'       => 'Hee sin de Neuste ömjenannte Sigge opjeliss, un wä et jedon hät.',
'movereason'            => 'Aanlass',
'revertmove'            => 'Et Ömnenne zeröcknemme',
'delete_and_move'       => 'Fottschmieße un Ömnenne',
'delete_and_move_text'  => '== Dä! Dubbelte Name ==
Dä Atikkel „[[$1]]“ jitt et ald. Wollts De en fottschmieße, öm hee dä Atikkel ömnenne ze künne?',
'delete_and_move_confirm'=> 'Jo, dun dä Atikkel fottschmieße.',
'delete_and_move_reason'=> 'Fottjeschmesse, öm Platz för et Ömnenne ze maache',
'selfmove'              => 'Du Doof! - dä ahle Name un dä neue Name es däselve - do hät et Ömnenne winnich Senn.',
'immobile_namespace'    => 'Do künne mer Sigge nit hen ömnenne, dat Appachtemeng es speziell, un dä neue Name för de Sigg jeiht deswäje nit.',

# Export

'export'                => 'Sigge Exporteere',
'exporttext'            => 'Hee exportees De dä Tex un de Eijeschaffte vun ener Sigg, oder vun enem Knubbel Sigge, de aktuelle Version, met oder ohne ehr ählere Versione.
Dat Janze es enjepack en XML.
Dat ka\'mer en en ander Wiki
- wann et och met dä MediaWiki-Soffwär läuf -
üvver de Sigg „[[Special:Import|Import]]“ do widder importeere.

* Schriev de Titele vun dä Sigge en dat Feld för Tex enzejevve, unge, eine Titel en jede Reih.
* Dann dun onoch ussöke, ov De all de vörherije Versione vun dä Sigge han wells, oder nor de aktuelle met dä 
Informatione vun de letzte Änderung. (En däm Fall künns De, för en einzelne Sigg, och ene tirekte Link bruche, 
zom Beispill „[[{{ns:Special}}:Export/{{int:mainpage}}]]“ för de Sigg „[[{{int:mainpage}}]]“ ze exporteere)

Denk dran, datte dat Zeuch em Unicode Format avspeichere muss,
wann De jet domet aanfange künne wells.',
'exportcuronly'         => 'Bloß de aktuelle Version usjevve (un <strong>nit</strong> de janze ahle Versione onoch met dobei dun)',
'exportnohistory'       => '----
<strong>Opjepass:</strong> de janze Versione Exporteere es hee em Wiki avjeschalt. Schad, ävver et wör en 
zo jroße Lass för dä Sörver.',
'export-submit'         => 'Loss_Jonn!',

# Namespace 8 related

'allmessages'           => 'All Tex, Baustein un Aanzeije vum Wiki-System',
'allmessagesname'       => 'Name',
'allmessagesdefault'    => 'Dä standaadmäßije Tex',
'allmessagescurrent'    => 'Esu es dä Tex jetz',
'allmessagestext'       => 'Hee kütt en Liss met Texte, Texstöck, un Nachrichte em Appachtemeng „MediaWiki:“',
'allmessagesnotsupportedUI'=> 'Mer künne „Special:Allmessages“ nit met dä Interface Sproch <strong>$1</strong> zosamme, die De jrad enjestallt häs. Sök Der en ander Sproch us, wo et met jonn künnt!',
'allmessagesnotsupportedDB'=> '<strong>Dat wor nix!</strong> Mer künne „Special:Allmessages“ nit zeije, <code>wgUseDatabaseMessages</code> es usjeschalt!',
'allmessagesfilter'     => 'Fingk dat Stöck hee em Name:',
'allmessagesmodified'   => 'Dun nor de Veränderte aanzeije',

# Thumbnails

'thumbnail-more'        => 'Jrößer aanzeije',
'missingimage'          => '<b>Dat Beld es nit do:</b><br />„$1“',
'filemissing'           => 'Datei es nit do',
'thumbnail_error'       => 'Ene Fähler es opjetauch beim Maache vun enem Breefmarke/Thumbnail-Beldche: „$1“',

# Special:Import
'import'                => 'Sigge Emporteere',
'importinterwiki'       => 'Trans Wiki Emport',
'import-interwiki-text' => 'Wähl en Wiki un en Sigg zem Emporteere us. Et Datum vun de Versione un de 
Metmaacher Name vun de Schriever wääde dobei metjenomme. All de Trans Wiki Emporte wääde em 
[[{{ns:special}}:Log/import|Emport_Logboch]] fassjehallde.',
'import-interwiki-history'=> 'All de Versione vun dä Sigg hee kopeere',
'import-interwiki-submit'=> 'Huhlade!',
'import-interwiki-namespace'=> 'Dun de Sigge emporteere em Appachtemeng:',
'importtext'            => 'Dun de Daate met däm „[[Special:Export|Export]]“ vun do vun enem Wiki Exporteere, dobei dun et - etwa bei Dir om Rechner - avspeichere, un dann hee huhlade.',
'importstart'           => 'Ben Sigge am emporteere â€¦',
'import-revision-count' => '({{PLURAL:$1|ein Version|$1 Versione|kein Version}})',
'importnopages'         => 'Kein Sigg för ze Emporteere jefunge.',
'importfailed'          => 'Dat Importeere es donevve jejange: $1',
'importunknownsource'   => 'Die Zoot Quell för et Emporteere kenne mer nit',
'importcantopen'        => 'Kunnt op de Datei för dä Emport nit zojriefe',
'importbadinterwiki'    => 'Verkihrte Interwiki Link',
'importnotext'          => 'En dä Datei wor nix dren enthallde, oder winnichstens keine Tex',
'importsuccess'         => 'Dat Emporteere hät jeflupp!',
'importhistoryconflict' => 'Mer han zwei ahle Versione jefunge, die dun sich bieße - die ein wor ald do - de ander en dä Emport Datei. möchlich, Ehr hatt die Daate ald ens emporteet.',
'importnosources'       => 'Hee sin kein Quell för dä Trans Wiki Emport enjerich. Dat ahle Versione Huhlade es avjeschalt  un nit möchlich.',
'importnofile'          => 'Et wood kein Datei huhjelade för ze Emporteere.',
'importuploaderror'     => 'Dat Huhlade es donevve jejange. möchlich, dat de Datei ze jroß wor, jrößer wie mer huhlade darf.',

# import log
'importlogpage'         => 'Logboch met emporteerte Sigge',
'importlogpagetext'     => 'Sigge met ehre Versione vun ander Wikis emporteere.',
'import-logentry-upload'=> '„[[$1]]“ emporteet',
'import-logentry-upload-detail'=> '{{PLURAL:$1|ein Version|$1 Versione|kein Version}} emporteet',
'import-logentry-interwiki'=> 'trans_wiki_emporteet: „$1“',
'import-logentry-interwiki-detail'=> '{{PLURAL:$1|ein Version|$1 Versione|kein Version}} vun „$2“',

# Keyboard access keys for power users
'accesskey-search'      => 'f',
'accesskey-minoredit'   => 'm',
'accesskey-save'        => 's',
'accesskey-preview'     => 'p',
'accesskey-diff'        => 'd',
'accesskey-compareselectedversions'=> 'v',
'accesskey-watch'       => 'w',

# tooltip help for some actions, most are in Monobook.js
'tooltip-search'        => 'En de {{SITENAME}} söke [alt-f]',
'tooltip-minoredit'     => 'Deit Ding Änderunge als klein Mini-Änderunge markeere. [alt-m]',
'tooltip-save'          => 'Deit Ding Änderunge avspeichere. [alt-s]',
'tooltip-preview'       => 'Liss de Vör-Aansich vun dä Sigg un vun Dinge Änderunge ih datte en Avspeichere deis! [alt-p]',
'tooltip-diff'          => 'Zeich Ding Änderunge am Tex aan. [alt-d]',
'tooltip-compareselectedversions'=> 'Dun de Ungerscheed zwesche dä beids usjewählde Versione zeije. [alt-v]',
'tooltip-watch'         => 'Op die Sigg hee oppasse. [alt-w]',

# stylesheets
'common.css'            => '/** CSS hee aan dä Stell hät Uswirkunge op alle Skins */',
'monobook.css'          => '/** CSS hee aan dä Stell hät Uswirkunge op alle Monobook Skins vun de janze Site */',
 
# Metadata
'nodublincore'          => 'De RDF_Meta_Daate vun de „Dublin Core“ Aat sin avjeschalt.',
'nocreativecommons'     => 'De RDF_Meta_Daate vun de „Creative Commons“ Aat sin avjeschalt.',
'notacceptable'         => '<strong>Blöd:</strong> Dä Wiki_Sörver kann de Daate nit en einem Format erüvverjevve, 
wat Dinge Client oder Brauser verstonn künnt.',

# Attribution

'anonymous'             => 'Namelose Metmaacher vun de {{SITENAME}}',
'siteuser'              => '{{SITENAME}}-Metmaacher $1',
'lastmodifiedby'        => 'Hee die Sigg wood et letz jeändert vun $2 om $1',
'and'                   => 'un',
'othercontribs'         => 'Baut op de Arbeid vun „<strong>$1</strong>“ op.',
'others'                => 'andere',
'siteusers'             => '{{SITENAME}}-Metmaacher $1',
'creditspage'           => 'Üvver de Metmaacher un ehre Beidräch för die Sigg',
'nocredits'             => 'För die Sigg ha\'mer nix en de Liss.',

# Spam protection

'spamprotectiontitle'   => 'SPAM_Schotz',
'spamprotectiontext'    => 'De Sigg, die de avspeichere wells, die weed vun unsem SPAM_Schotz nit durchjelooße. Dat kütt miehts vun enem Link op en fremde Sigg.',
'spamprotectionmatch'   => 'Hee dä Tex hät dä SPAM_Schotz op de Plan jerofe: „<code>$1</code>“',
'subcategorycount'      => 'Hee {{PLURAL:$1|weed ein Ungerjrupp|wääde $1 Ungerjruppe}} jezeich <small>  (Et künnt mieh op de vörije un nächste Sigge jevve)</small>',
'categoryarticlecount'  => 'Hee {{PLURAL:$1|weed eine Atikkel|wääde $1 Atikkele}} jezeich <small>  (Et künnt mieh op de vörije un nächste Sigge jevve)</small>',
'category-media-count' => "There {{PLURAL:$1|is one file|are $1 files}} in this category.",
'listingcontinuesabbrev'=> ' wigger',
'spambot_username'      => 'SPAM fottschmieße',
'spam_reverting'        => 'De letzte Version es ohne de Links op  „$1“ widder zerröckjehollt.',
'spam_blanking'         => 'All die Versione hatte Links op „$1“, die sin jetz erus jemaht.',

# Info page
'infosubtitle'          => 'Üvver de Sigg',
'numedits'              => 'Aanzahl Änderunge aan däm Atikkel: <strong>$1</strong>',
'numtalkedits'          => 'Aanzahl Änderunge aan de Klaafsigg: <strong>$1</strong>',
'numwatchers'           => 'Aanzahl Oppasser: <strong>$1</strong>',
'numauthors'            => 'Aanzahl Metmaacher, die aan däm Atikkel jeschrevve han: <strong>$1</strong>',
'numtalkauthors'        => 'Aanzahl Metmaacher beim Klaaf: <strong>$1</strong>',

# Math options
'mw_math_png'           => 'Immer nor PNG aanzeije',
'mw_math_simple'        => 'En einfache Fäll maach HTML, söns PNG',
'mw_math_html'          => 'Maach HTML wann möchlich, un söns PNG',
'mw_math_source'        => 'Luur et als TeX (jod för de Tex-Brausere)',
'mw_math_modern'        => 'De bess Enstellung för de Brauser vun hück',
'mw_math_mathml'        => 'Nemm „MathML“ wann möchlich (em Probierstadium)',

# Patrolling
'markaspatrolleddiff'   => 'Nohjeluurt. Dun dat fasshallde',
'markaspatrolledlink'   => "[$1]",
'markaspatrolledtext'   => 'De Änderung es nohjeluert, dun dat fasshallde',
'markedaspatrolled'     => 'Et Kennzeiche „Nohjeluurt“ speichere',
'markedaspatrolledtext' => 'Et es jetz fassjehallde, datte usjewählte Änderunge nohjeluurt woode sin.',
'rcpatroldisabled'      => 'Et Nohluure vun de letzte Änderunge es avjeschalt',
'rcpatroldisabledtext'  => 'Et Nohluure fun de letzte Änderunge es em Momang nit möchlich.',
'markedaspatrollederror'=> 'Kann dat Kennzeiche „Nohjeluurt“ nit avspeichere.',
'markedaspatrollederrortext'=> 'Do muss en bestemmte Version ussöke.',

# Monobook.js: tooltips and access keys for monobook
'monobook.js' => '',

# Common.js: contains nothing but a placeholder comment
'common.js' => '/* Any JavaScript here will be loaded for all users on every page load. */',

# image deletion
'deletedrevision'       => 'De ahl Version „$1“ es fottjeschmesse.',

# browsing diffs
'previousdiff'          => '? De Ungerscheede dovör zeije',
'nextdiff'              => 'De Ungerscheede donoh zeije ?',

'imagemaxsize'          => 'Belder op de Sigge, wo se beschrevve wääde, nit jrößer maache wie:',
'thumbsize'             => 'Esu breid solle de klein Beldche (Thumbnails/Breefmarke) sin:',
'showbigimage'          => 'Dun de Version met de hüchste Oplösung eravlade, dat es <strong>$1</strong> x <strong>$2</strong> Pixele, un die es <strong>$3</strong> Kilobyte jroß.',

'newimages'             => 'Belder, Tön, uew. als Jalerie',
#'newimages-summary' => '',
'showhidebots'          => '(Bots $1)',
'noimages'              => 'Kein Dateie jefunge.',

# short names for language variants used for language conversion links.
# to disable showing a particular link, set it to 'disable', e.g.
# 'variantname-zh-sg' => 'disable',
'variantname-zh-cn' => 'cn',
'variantname-zh-tw' => 'tw',
'variantname-zh-hk' => 'hk',
'variantname-zh-sg' => 'sg',
'variantname-zh' => 'zh',
# variants for Serbian language
'variantname-sr-ec' => 'sr-ec',
'variantname-sr-el' => 'sr-el',
'variantname-sr-jc' => 'sr-jc',
'variantname-sr-jl' => 'sr-jl',
'variantname-sr' => 'sr',
# variants for Kazakh language
'variantname-kk-tr' => 'kk-tr',
'variantname-kk-kz' => 'kk-kz',
'variantname-kk-cn' => 'kk-cn',
'variantname-kk' => 'kk',

# labels for User: and Title: on Special:Log pages
'specialloguserlabel'   => 'Metmaacher:',
'speciallogtitlelabel'  => ' Siggename:',

'passwordtooshort'      => 'Dat Passwood es jet koot - et mööte ald winnichstens <strong>$1</strong> Zeiche, Zeffere, un Buchstave dodren sin.',

# Media Warning
'mediawarning'          => '<strong>Opjepass</strong>: En dä Datei künnt en <b>jefährlich Projrammstöck</b> dren stecke. Wa\'mer et laufe looße dät, do künnt dä Sörver met för de Cracker opjemaht wääde. <hr />',
'fileinfo'              => '<strong>$1</strong> Kilobyte, MIME-Typ: <code>$2</code>',

# Metadata
'metadata'              => 'Metadaate',
'metadata-help'         => 'En dä Datei stich noh mieh an Daate dren. Dat sin Metadaate, die normal vum Opnahmejerät 
kumme. Wat en Kamera, ne Scanner, un esu, do fassjehallde han, dat kann ävver späder met enem Projramm 
bearbeidt un usjetuusch woode sin.',
'metadata-expand'       => 'Mieh zeije',
'metadata-collapse'     => 'Daate Versteche',
'metadata-fields'       => 'EXIF metadata fields listed in this message will
be included on image page display when the metadata table
is collapsed. Others will be hidden by default.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength',
 
# external editor support
'edit-externally'       => 'Dun de Datei met enem externe Projramm bei Dr om Rechner bearbeide',
'edit-externally-help'  => 'Luur op [http://meta.wikimedia.org/wiki/Help:External_editors Installationsanweisungen] noh Hinwies, wie mer esu en extern Projramm opsetz un installeere deit.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall'      => 'all',
'imagelistall'          => 'all',
'watchlistall1'         => 'all',
'watchlistall2'         => 'Alles',
'namespacesall'         => 'all',

# E-mail address confirmation
'confirmemail'          => 'E-Mail Adress bestätije',
'confirmemail_noemail'  => 'En [[Special:Preferences||Ding Enstellunge]] es kein öntlich E-Mail Adress.',
'confirmemail_text'     => 'Ih datte en däm Wiki hee de E-Mail bruche kanns, muss De Ding E-Mail Adress bestätich 
han, dat se en Oodnung es un dat se och Ding eijene es. Klick op dä Knopp un Do kriss en E-Mail jescheck. Do 
steiht ene Link met enem Code dren. Wann De met Dingem Brauser op dä Link jeihs, dann deis De domet 
bestätije, dat et wirklich Ding E-Mail Adress es. Dat es nit allzo secher, alsu wör nix för Die 
Bankkonto oder bei de Sparkass, ävver et sorg doför, dat nit jede Peijaß met Dinger E-Mail oder Dingem 
Metmaachername eröm maache kann.',
'confirmemail_send'     => 'Scheck en E-Mail zem Bestätije',
'confirmemail_sent'     => 'En E-Mail zem Bestätije es ungerwähs.',
'confirmemail_sendfailed'=> 'Beim E-Mail Adress Bestätije es jet donevve jejange, dä Sörver hatt e Problem met 
sing Konfijuration, oder en Dinger E-Mail Adress es e Zeiche verkihrt, oder esu jet.',

'confirmemail_invalid'  => 'Beim E-Mail Adress Bestätije es jet donevve jejange, dä Code es verkihrt, künnt 
avjelaufe jewäse sin.',
'confirmemail_needlogin'=> 'Do muss Dich $1, för de E-Mail Adress ze bestätije.',
'confirmemail_success'  => 'Ding E-Mail Adress es jetz bestätich. Jetz künns De och noch 

[[Special:Userlogin|enlogge]]. Vill Spass!',
'confirmemail_loggedin' => 'Ding E-Mail Adress es jetz bestätich!',
'confirmemail_error'    => 'Beim E-Mail Adress Bestätije es jet donevve jejange, de Bestätijung kunnt nit 
avjespeichert wääde.',
'confirmemail_subject'  => 'Dun Ding E-Mail Adress bestätije för de {{SITENAME}}.',
'confirmemail_body'     => 'Jod möchlich, Do wors et selver,
vun de IP_Adress $1,
hät sich jedenfalls einer aanjemeldt,
un well dä Metmaacher „$2“ op de {{SITENAME}}
wääde, un hät en E-Mail Adress aanjejovve.
Öm jetz klor ze krije, dat die E-Mail
Adress un dä neue Metmaacher och beienander
jehüre, muss dä Neue en singem Brauser
dä Link:

$3

opmaache. Noch för em $4. 
Alsu dun dat, wann de et selver bes.

Wann nit Do, sondern söns wer Ding E-Mail
Adress aanjejovve hät, do bruchs de jar nix
ze dun. De E-Mail Adress kann nit jebruch
wääde, ih dat se nit bestätich es.

Wann de jetz neujeerich jewoode bes un wells
wesse, wat met de {{SITENAME}} loss es,
do  jang met Dingem Brauser noh:
{{FULLURL:{{MediaWiki:Mainpage}}}}
un luur Der et aan.

Ene schöne Jroß vun de {{SITENAME}}.

-- 
{{SITENAME}}: {{fullurl:{{Mediawiki:mainpage}}}}',

# Inputbox extension, may be useful in other contexts as well
'tryexact'              => 'Versök en akkurate Üvvereinstimmung:',
'searchfulltext'        => 'Sök durch dä janze Tex',
'createarticle'         => 'Atikkel enrichte',

# Scary transclusion
'scarytranscludedisabled'=> '[Et Enbinge per Interwiki es avjeschalt]',
'scarytranscludefailed' => '[De Schablon „$1“ enzebinge hät nit jeflupp]',
'scarytranscludetoolong'=> '[Schad, de URL es ze lang]',

# Trackbacks
'trackbackbox'          => '<div id="mw_trackbacks">
Trackbacks för dä Atikkel hee:<br />
„<strong>$1</strong>“
</div>',
'trackback' => '; $4$5 : [$2 $1]',
'trackbackexcerpt' => '; $4$5 : [$2 $1]: <nowiki>$3',
'trackbackremove'       => ' ([$1 Fottschmieße])',
'trackbacklink'         => 'Trackback',
'trackbackdeleteok'     => 'Trackback es fottjeschmesse.',


# delete conflict
'deletedwhileediting'   => '<strong>Opjepass:</strong> De Sigg wood fottjeschmesse, nohdäm Do ald aanjefange häs, dran ze Ändere.',
'confirmrecreate'       => 'Dä Metmaacher [[User:$1|$1]] (?[[User talk:$1|däm sing Klaafs]]) hät die Sigg 
fottjeschmesse, nohdäm Do do dran et Ändere aanjefange häs. Dä Jrund:
: „<i>$2</i>“
Wells Do jetz met en neu Version die Sigg neu aanläje?',
'recreate'              => 'Zeröckholle',
'tooltip-recreate'      => 'En fottjeschmesse Sigg widder zeröckholle',
'unit-pixel'            => 'px',

# HTML dump
'redirectingto'         => 'Leit öm op „[[$1]]“...',

# action=purge
'confirm_purge'         => 'Dä Zweschespeicher för die Sigg fottschmieße?

$1',
'confirm_purge_button'  => 'Jo - loss jonn!',
'youhavenewmessagesmulti'=> 'Do häs neu Nachrichte op $1',
'newtalkseperator'      => ',_',
'searchcontaining'      => 'Sök noh Atikkele, wo „$1“ em Tex vörkütt.',
'searchnamed'           => 'Sök noh Atikkele, wo „$1“ em Name vörkütt.',
'articletitles'         => 'Atikkele, die met „$1“ aanfange',
'hideresults'           => 'Dat Resultat versteche',

# DISPLAYTITLE
'displaytitle'          => '(Links op die Sigg als [[$1]])',

'loginlanguagelabel'    => 'Sproch: $1',

# Multipage image navigation
'imgmultipageprev'      => 'â† de Sigg dovör',
'imgmultipagenext'      => 'de Sigg donoh â†’',
'imgmultigo'            => 'Loss jonn!',
'imgmultigotopre'       => 'Jangk op de Sigg',
#'imgmultigotopost'      => '',

# Table pager
'ascending_abbrev'      => 'opwääts zoteet',
'descending_abbrev'     => 'raffkaz zoteet',
'table_pager_next'      => 'De nächste Sigg',
'table_pager_prev'      => 'De Sigg dovör',
'table_pager_first'     => 'De eetste Sigg',
'table_pager_last'      => 'De letzte Sigg',
'table_pager_limit'     => 'Zeich $1 pro Sigg',
'table_pager_limit_submit' => 'Loss jonn!',
'table_pager_empty'     => 'Nix erus jekumme',

# Auto-summaries
'autosumm-blank'    => 'Dä janze Enhald vun dä Sigg fottjemaht',
'autosumm-replace'  => 'De Sigg met „$1“ jetuusch',
'autosumm-replace'  => 'Replacing page with \'$1\'',
'autoredircomment'  => 'Leit öm op „[[$1]]“', # This should be changed to the new naming convention, but existed beforehand.
'autosumm-new'      => 'Neu Sigg: $1',
'autosumm-shortnew' => 'Neu Sigg: „$1“',

);

?>
