<?php
/** Ripoarisch (Ripoarisch)
 *
 * @ingroup Language
 * @file
 *
 * @author Caesius noh en Idee vum Manes
 * @author Purodha
 * @author Siebrand
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
 * @ingroup Language
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
        'monobook'      => 'MonoBoch',
        'myskin'        => 'Ming Skin',
        'chick'         => 'Höhnche'
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Dun de Links ungerstriche:',
'tog-highlightbroken'         => 'Zeich de Links op Sigge, die et noch nit jitt, esu met: „<a href="" class="new">Lemma</a>“ aan.<br />Wann De dat nit wells, weed et esu: „Lemma<a href="" class="internal">?</a>“ jezeich.',
'tog-justify'                 => 'Dun de Avschnedde em Blocksatz aanzeije',
'tog-hideminor'               => 'Dun de klein Mini-Änderunge (<strong>M</strong>) en de Liss  met „{{int:Recentchanges}}“ <strong>nit</strong> aanzeije',
'tog-extendwatchlist'         => 'Verjrößer de Oppassliss för jede Aat vun möchliche Änderunge ze zeije',
'tog-usenewrc'                => 'Dun de opgemotzte Liss met „{{int:Recentchanges}}“ aanzeije (bruch Java_Skripp)',
'tog-numberheadings'          => 'Dun de Üvverschrefte automatisch nummereere',
'tog-showtoolbar'             => 'Zeich de Werkzeuchliss zom Ändere aan (bruch Java_Skripp)',
'tog-editondblclick'          => 'Sigge met Dubbel-Klicke ändere (bruch Java_Skripp)',
'tog-editsection'             => 'Maach [{{int:Editsection}}]-Links aan de Avschnedde dran',
'tog-editsectiononrightclick' => 'Avschnedde met Räächs-Klicke op de Üvverschrefte ändere (bruch Java_Skripp)',
'tog-showtoc'                 => 'Zeich en Enhaldsüvversich bei Sigge met mieh wie drei Üvverschrefte dren',
'tog-rememberpassword'        => 'Op Duur aanmelde',
'tog-editwidth'               => 'Maach dat Feld zom Tex enjevve su breid wie et jeiht',
'tog-watchcreations'          => 'Dun de Sigge, die ich neu aanläje, för ming Oppassliss vürschlage',
'tog-watchdefault'            => 'Dun de Sigge för ming Oppassliss vürschlage, die ich aanpacke un ändere',
'tog-watchmoves'              => 'Dun ming selfs ömjenante Sigge automatisch för ming Oppassliss vürschlage',
'tog-watchdeletion'           => 'Dun Sigge, die ich fottjeschmesse han, för ming Oppassliss vürschlage',
'tog-minordefault'            => 'Dun all ming Änderunge jedes Mol als klein Mini-Änderunge vürschlage',
'tog-previewontop'            => 'Zeich de Vör-Aansich üvver däm Feld för dä Tex enzejevve aan.',
'tog-previewonfirst'          => 'Zeich de Vör-Aansich tirek för et eetste Mol beim Bearbeide aan',
'tog-nocache'                 => 'Dun et Sigge Zweschespeichere - et Caching - avschalte',
'tog-enotifwatchlistpages'    => 'Scheck en E-Mail, wann en Sigg us ming Oppassliss jeändert wood',
'tog-enotifusertalkpages'     => 'Scheck mer en E-Mail, wann ming Klaaf Sigg jeändert weed',
'tog-enotifminoredits'        => 'Scheck mer och en E-Mail för de klein Mini-Änderunge',
'tog-enotifrevealaddr'        => 'Zeich dä Andere ming E-Mail Adress aan, en de Benohrichtijunge per E-Mail',
'tog-shownumberswatching'     => 'Zeich de Aanzahl Metmaacher, die op die Sigg am oppasse sin',
'tog-fancysig'                => 'Ungerschreff ohne automatische Link',
'tog-externaleditor'          => 'Nemm jedes Mol en extern Editor-Projramm (Doför bruchs de spezjell Enstellunge op Dingem Kompjutor)',
'tog-externaldiff'            => 'Nemm jedes Mol en extern Diff-Projramm (Doför bruchs de spezjell Enstellunge op Dingem Kompjutor)',
'tog-showjumplinks'           => '„Jangk-noh“-Links usjevve, die bei em „Zojang ohne Barrikad“ helfe dun',
'tog-uselivepreview'          => 'Zeich de „Lebendije Vör-Aansich zeije“ (bruch Java_Skripp) (em Usprobierstadium)',
'tog-forceeditsummary'        => 'Froch noh, wann en däm Feld „Koot zosammejefass, Quell“ beim Avspeichere nix dren steiht',
'tog-watchlisthideown'        => 'Dun ming eije Änderunge <strong>nit</strong> en minger Oppassliss aanzeije',
'tog-watchlisthidebots'       => 'Dun jedes Mol dä Bots ehr Änderunge <strong>nit</strong> en minger Oppassliss zeije',
'tog-watchlisthideminor'      => 'Dun jedes Mol de klein Mini-Änderunge <strong>nit</strong> en minger Oppassliss zeije',
'tog-nolangconversion'        => 'Sprochevariante nit ömwandele',
'tog-ccmeonemails'            => 'Scheck mer en Kopie, wann ich en E-mail an ene andere Metmaacher scheck',
'tog-diffonly'                => 'Zeich beim Versione Verjliche nur de Ungerscheed aan (ävver pack nit noch de janze Sigg dodronger)',
'tog-showhiddencats'          => 'Verstoche Saachjroppe aanzeije',

'underline-always'  => 'jo, ongershtriishe',
'underline-never'   => 'nä',
'underline-default' => 'nemm dem Brauser sing Enstellung',

'skinpreview' => '(Vör-Ansich)',

# Dates
'sunday'        => 'Sonndaach',
'monday'        => 'Mondaach',
'tuesday'       => 'Dingsdaach',
'wednesday'     => 'Meddwoch',
'thursday'      => 'Donnersdaach',
'friday'        => 'Friedaach',
'saturday'      => 'Samsdaach',
'sun'           => 'So.',
'mon'           => 'Mo.',
'tue'           => 'Di.',
'wed'           => 'Me.',
'thu'           => 'Do.',
'fri'           => 'Fr.',
'sat'           => 'Sa.',
'january'       => 'Janewar',
'february'      => 'Febrewar',
'march'         => 'Määz',
'april'         => 'Aprel',
'may_long'      => 'Mai',
'june'          => 'Juni',
'july'          => 'Juli',
'august'        => 'Aujuss',
'september'     => 'September',
'october'       => 'Oktober',
'november'      => 'November',
'december'      => 'Dezember',
'january-gen'   => 'Janewar',
'february-gen'  => 'Febrewar',
'march-gen'     => 'Määz',
'april-gen'     => 'Aprel',
'may-gen'       => 'Mei',
'june-gen'      => 'Juni',
'july-gen'      => 'Juli',
'august-gen'    => 'Aujuss',
'september-gen' => 'September',
'october-gen'   => 'Oktober',
'november-gen'  => 'November',
'december-gen'  => 'Dezember',
'jan'           => 'Jan',
'feb'           => 'Feb',
'mar'           => 'Mäz',
'apr'           => 'Apr',
'may'           => 'Mai',
'jun'           => 'Jun',
'jul'           => 'Jul',
'aug'           => 'Auj',
'sep'           => 'Sep',
'oct'           => 'Okt',
'nov'           => 'Nov',
'dec'           => 'Dez',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Saachjrupp|Saachjruppe}}',
'category_header'                => 'Atikkele in de Saachjrupp „$1“',
'subcategories'                  => 'Ungerjruppe',
'category-media-header'          => 'Medie en de Saachjrupp "$1"',
'category-empty'                 => "''En dä Saachjrupp hee sin kein Sigge un kein Dateie.''",
'hidden-categories'              => 'Verstoche Saachjrupp{{PLURAL:$1||e|e}}',
'hidden-category-category'       => 'Verstoche Saachjruppe', # Name of the category where hidden categories will be listed
'category-subcat-count'          => 'En dä Saachrupp hee {{PLURAL:$2|es ein Ungerjrupp dren:|sin $2 Ungerjruppe dren, {{PLURAL:$1|un dovun weed hee nur ein|un dovun weede $1 hee|ävver dovun weed hee keine}} aanjezeich:|sin kein Ungerjruppe dren.}}',
'category-subcat-count-limited'  => 'En dä Saachrupp hee {{PLURAL:$1|es ein Ungerjrupp dren:|sin $1 Ungerjruppe dren:|sin kein Ungerjruppe dren.}}',
'category-article-count'         => 'En dä Saachrupp hee {{PLURAL:$2|es ein Sigg dren:|sin $2 Sigge dren, {{PLURAL:$1|un dovun weed hee nur ein|un dovun weede $1 hee|ävver dovun weed hee keine}} aanjezeich:|sin kein Sigge dren.}}',
'category-article-count-limited' => 'En dä Saachrupp hee {{PLURAL:$1|es ein Sigg dren:|sin $1 Sigge dren:|es kein Sigg dren:}}',
'category-file-count'            => 'En dä Saachrupp hee {{PLURAL:$2|es ein Datei dren:|sin $2 Dateie dren, {{PLURAL:$1|un dovun weed hee nur ein|un dovun weede $1 hee|ävver dovun weed hee kein}} aanjezeich:|es kein Datei dren.}}',
'category-file-count-limited'    => 'En dä Saachrupp hee {{PLURAL:$1|es ein Datei dren:|sin $1 Dateie dren:|es kein Datei dren.}}',
'listingcontinuesabbrev'         => '… (wigger)',

'mainpagetext'      => "<big>'''MediaWiki es jetz enstalleet.'''</big>",
'mainpagedocfooter' => 'Luur en et (änglesche) [http://meta.wikimedia.org/wiki/Help:Contents Handboch] wann De wesse wells wie de Wiki-Soffwär jebruch un bedeent wääde muss.

== För dä Aanfang ==
Dat es och all op Änglesch:
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Configuration settings list]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]',

'about'          => 'Üvver',
'article'        => 'Atikkel',
'newwindow'      => '(Mäht e neu Finster op, wann Dinge Brauser dat kann)',
'cancel'         => 'Stopp! Avbreche!',
'qbfind'         => 'Fingk',
'qbbrowse'       => 'Aanluure',
'qbedit'         => 'Ändere',
'qbpageoptions'  => 'Sigge Enstellunge',
'qbpageinfo'     => 'Üvver de Sigg',
'qbmyoptions'    => 'Ming Sigge',
'qbspecialpages' => 'Spezial Sigge',
'moredotdotdot'  => 'Mieh&nbsp;…',
'mypage'         => 'Ming Metmaacher-Sigg',
'mytalk'         => 'ming Klaafsigg',
'anontalk'       => 'Klaaf för de IP-Adress',
'navigation'     => 'Jangk noh',
'and'            => ', un',

# Metadata in edit box
'metadata_help' => 'Däm Beld sing Meta-Daate:',

'errorpagetitle'    => 'Fähler',
'returnto'          => 'Jangk widder noh: „$1“.',
'tagline'           => 'Us de {{SITENAME}}',
'help'              => 'Hölp',
'search'            => 'Söke',
'searchbutton'      => 'em Tex',
'go'                => 'Loss Jonn',
'searcharticle'     => 'Atikkel',
'history'           => 'Versione',
'history_short'     => 'Versione',
'updatedmarker'     => '(verändert)',
'info_short'        => 'Infomation',
'printableversion'  => 'För ze Drocke',
'permalink'         => 'Als Permalink',
'print'             => 'Drocke',
'edit'              => 'Ändere',
'create'            => 'Aanläje',
'editthispage'      => 'De Sigg ändere',
'create-this-page'  => 'Neu aanläje',
'delete'            => 'Fottschmieße',
'deletethispage'    => 'De Sigg fottschmieße',
'undelete_short'    => '{{PLURAL:$1|ein Änderung|$1 Änderunge}} zeröckholle',
'protect'           => 'Schötze',
'protect_change'    => 'der Schotz ändere',
'protectthispage'   => 'De Sigg schötze',
'unprotect'         => 'Schotz ophevve',
'unprotectthispage' => 'Dä Schotz för de Sigg ophevve',
'newpage'           => 'Neu Sigg',
'talkpage'          => 'Üvver die Sigg hee schwaade',
'talkpagelinktext'  => 'Klaaf',
'specialpage'       => 'Sondersigg',
'personaltools'     => 'Metmaacher Werkzeuch',
'postcomment'       => 'Neu Avschnedd op de Klaafsigg',
'articlepage'       => 'Aanluure wat op dä Sigg drop steiht',
'talk'              => 'Klaaf',
'views'             => 'Aansichte',
'toolbox'           => 'Werkzeuch',
'userpage'          => 'Däm Metmaacher sing Sigg aanluure',
'projectpage'       => 'De Projeksigg aanluure',
'imagepage'         => 'Beldsigg aanluure',
'mediawikipage'     => 'De Mediesigg aanluure',
'templatepage'      => 'De Schablon ehr Sigg aanluure',
'viewhelppage'      => 'De Hölpsigg aanluure',
'categorypage'      => 'De Saachjruppesigg aanluure',
'viewtalkpage'      => 'Klaaf aanluure',
'otherlanguages'    => 'En ander Sproche',
'redirectedfrom'    => '(Ömjeleit vun $1)',
'redirectpagesub'   => 'Ömleitungssigg',
'lastmodifiedat'    => 'Die Sigg hee wood et letz jeändert aam $1 öm $2 Uhr.', # $1 date, $2 time
'viewcount'         => 'De Sigg es bes jetz {{PLURAL:$1|eimol|$1 Mol|keijmol}} avjerofe woode.',
'protectedpage'     => 'Jeschötzte Sigg',
'jumpto'            => 'Jangk noh:',
'jumptonavigation'  => 'Noh de Navigation',
'jumptosearch'      => 'Jangk Söke!',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Üvver de {{SITENAME}}',
'aboutpage'            => 'Project:Üvver de {{SITENAME}}',
'bugreports'           => 'Fähler melde',
'bugreportspage'       => 'Project:Kontak',
'copyright'            => 'Dä Enhald steiht unger de $1.',
'copyrightpagename'    => 'Lizenz',
'copyrightpage'        => '{{ns:project}}:Lizenz',
'currentevents'        => 'Et Neuste',
'currentevents-url'    => 'Project:Et Neuste',
'disclaimers'          => 'Hinwies',
'disclaimerpage'       => 'Project:Impressum',
'edithelp'             => 'Hölp för et Bearbeide',
'edithelppage'         => 'Help:Hölp',
'faq'                  => 'FAQ',
'faqpage'              => 'Project:FAQ',
'helppage'             => 'Help:Hölp',
'mainpage'             => 'Haupsigg',
'mainpage-description' => 'Haupsigg',
'policy-url'           => 'Project:Rejelle',
'portal'               => 'Üvver {{SITENAME}} för Metmaacher',
'portal-url'           => 'Project:Metmaacher Pooz',
'privacy'              => 'Daateschotz un Jeheimhaldung',
'privacypage'          => 'Project:Daateschotz un Jeheimhaldung',
'sitesupport'          => 'Spende',
'sitesupport-url'      => 'Project:Spende',

'badaccess'        => 'Nit jenoch Räächde',
'badaccess-group0' => 'Do häs nit jenoch Räächde.',
'badaccess-group1' => 'Wat Do wells, dat dürfe nor Metmaacher, die $1 sin.',
'badaccess-group2' => 'Wat Do wells, dat dürfe nor de Metmaacher us dä Jruppe: $1.',
'badaccess-groups' => 'Wat Do wells, dat dürfe nor de Metmaacher us eine vun dä Jruppe: $1.',

'versionrequired'     => 'De Version $1 vun MediaWiki Soffwär es nüdich',
'versionrequiredtext' => 'De Version $1 vun MediaWiki Soffwär es nüdich, öm die Sigg hee bruche ze künne. Süch op [[Special:Version|de Versionssigg]], wat mer hee för ene Soffwärstand han.',

'retrievedfrom'           => 'Die Sigg hee stamp us „$1“.',
'youhavenewmessages'      => 'Do häs $1 ($2).',
'newmessageslink'         => 'neu Metdeilunge op Dinger Klaafsigg',
'newmessagesdifflink'     => 'Ungerscheed zor vürletzte Version',
'youhavenewmessagesmulti' => 'Do häs neu Nachrichte op $1',
'editsection'             => 'Ändere',
'editold'                 => 'Hee die Version ändere',
'viewsourceold'           => 'Wikitex zeije',
'editsectionhint'         => 'Avschnedd $1 ändere',
'toc'                     => 'Enhaldsüvversich',
'showtoc'                 => 'enblende',
'hidetoc'                 => 'usblende',
'thisisdeleted'           => '$1 - aanluure oder widder zeröckholle?',
'viewdeleted'             => '$1 aanzeije?',
'restorelink'             => '{{PLURAL:$1|eijn fottjeschmesse Änderung|$1 fottjeschmesse Änderunge|keij fottjeschmesse Änderunge}}',
'feed-invalid'            => 'Esu en Zoot Abonnemang jitt et nit.',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Atikkel',
'nstab-user'      => 'Metmaachersigg',
'nstab-media'     => 'Medijesigg',
'nstab-special'   => 'Spezial',
'nstab-project'   => 'Projeksigg',
'nstab-image'     => 'Datei',
'nstab-mediawiki' => 'Tex',
'nstab-template'  => 'Schablon',
'nstab-help'      => 'Hölp',
'nstab-category'  => 'Saachjrupp',

# Main script and global functions
'nosuchaction'      => 'Die Aufgab (action) kenne mer nit',
'nosuchactiontext'  => '<strong>Na su jet:</strong> De Aufgab us dä URL, die do hinger „<code>action=</code>“ dren steiht, jo die kennt hee dat Wiki jar nit.',
'nosuchspecialpage' => "Esu en Sondersigg ha'mer nit",
'nospecialpagetext' => 'De aanjefrochte Sondersigg jitt et nit, de [[Special:Specialpages|Liss met de Sondersigge]] helf Der wigger.',

# General errors
'error'                => 'Fähler',
'databaseerror'        => 'Fähler en de Daatebank',
'dberrortext'          => 'Enne Fääler es_opjefalle en dä Süntax fun_ennem Befääl fö_de_Date_Bank.
Dat künnd_enne Fääler en de ßoffwäer fum Wikki sinn.
De läzde Date_Bank_Befääl eß jewääse:
<blockquote><code>$1</code></blockquote>
uß däm Projramm singe Funkzjohn: „<code>$2</code>“.<br />
MySQL mälldt dä Fääler: „<code>$3: $4</code>“.',
'dberrortextcl'        => 'En dä Syntax vun enem Befähl för de Daatebank es
ene Fähler es opjefalle.
Dä letzte Befähl för de Daatebank es jewäse:
<blockquote><code>$1</code></blockquote>
un kohm us däm Projramm singe Funktion: „<code>$2</code>“.<br />
MySQL meld dä Fähler: „<code>$3: $4</code>“.',
'noconnect'            => 'Schad! Mer kunnte kein Verbindung met däm Daatebanksörver op „$1“ krije.',
'nodb'                 => 'Kunnt de Daatebank „$1“ nit uswähle',
'cachederror'          => 'Dat hee es en Kopie vun dä Sigg us em Cache. Möchlich, se es nit aktuell.',
'laggedslavemode'      => '<strong>Opjepass:</strong> Künnt sin, dat hee nit dä neuste Stand vun dä Sigg aanjezeich weed.',
'readonly'             => 'De Daatebank es jesperrt',
'enterlockreason'      => 'Jevv aan, woröm un för wie lang dat de Daatebank jesperrt wääde soll',
'readonlytext'         => 'De Daatebank es jesperrt. Neu Saache dren avspeichere jeiht jrad nit, un ändere och nit. Dä Jrund: „$1“',
'missing-article'      => 'Dä Tex för de Sigg „$1“ $2 kunnte mer nit en de Daatebank finge.
De Sigg es villeich fottjeschmesse oder ömjenannt woode.
Wann dat esu nit sin sollt, dann hadder villeich ene Fähler en de Soffwär jefunge.
Verzällt et enem [[{{MediaWiki:Grouppage-sysop}}|Wiki_Köbes]],
un doht em och de URL vun dä Sigg hee sage.',
'missingarticle-rev'   => '(Version Numero: $1)',
'readonly_lag'         => 'De Daatebank es för en koote Zigg jesperrt, för de Daate avzejliche.',
'internalerror'        => 'De Wiki-Soffwär hät ene Fähler jefunge',
'filecopyerror'        => 'Kunnt de Datei „$1“ nit noh „$2“ kopeere.',
'filerenameerror'      => 'Kunnt de Datei „$1“ nit op „$2“ ömdäufe.',
'filedeleteerror'      => 'Kunnt de Datei „$1“ nit fottschmieße.',
'filenotfound'         => 'Kunnt de Datei „$1“ nit finge.',
'unexpected'           => 'Domet hät keiner jerechnet: „$1“=„$2“',
'formerror'            => 'Dat es donevve jejange: Wor nix, met däm Fomular.',
'badarticleerror'      => 'Dat jeiht met hee dä Sigg nit ze maache.',
'cannotdelete'         => 'De Sigg oder de Datei hee fottzeschmieße es nit möchlich. Möchlich, dat ene andere Metmaacher flöcker wor, hät et vürher hee jo ald jedon, un jetz es die Sigg ald fott.',
'badtitle'             => 'Verkihrte Üvverschreff',
'badtitletext'         => 'De Üvverschreff es esu nit en Odenung. Et muss jet dren stonn.
Et künnt sin, dat ein vun de speziell Zeiche dren steiht,
wat en Üvverschrefte nit erlaub es.
Et künnt ussinn, wie ene InterWikiLink,
dat jeiht ävver nit.
Muss De repareere.',
'perfdisabled'         => "<strong>'''Opjepass:'''</strong> Dat maache mer jetz nit - dä Sörver hät jrad zovill Lass - do si'mer jet vürsichtich.",
'perfcached'           => 'De Daate heenoh kumme usem Zweschespeicher (Cache) un künnte nit mieh janz de allerneuste sin.',
'perfcachedts'         => 'De Daate heenoh kumme usem Zweschespeicher (Cache) un woodte $1 opjenumme. Se künnte nit janz de allerneuste sin.',
'querypage-no-updates' => "'''Hee die Sigg weed nit mieh op ene neue Stand jebraat.'''",
'wrong_wfQuery_params' => 'Verkihrte Parameter för: <strong><code>wfQuery()</code></strong><br />
De Funktion es: „<code>$1</code>“<br />
De Aanfroch es: „<code>$2</code>“<br />',
'viewsource'           => 'Wikitex aanluure',
'viewsourcefor'        => 'för de Sigg: „$1“',
'actionthrottled'      => "Dat ka'mer nit esu öff maache",
'actionthrottledtext'  => 'Dat darf mer nor en jeweße Zahl Mole hengerenander maache. Do bes jrad aan de Jrenz jekumme. Kannze jo en e paar Menutte widder probeere.',
'viewsourcetext'       => 'Hee es dä Sigg ier Wikitex zum Belooere un Koppeere:',
'protectedinterface'   => 'Op dä Sigg hee steiht Tex usem Interface vun de Wiki-Soffwär. Dröm es die jäje Änderunge jeschötz, domet keine Mess domet aanjestallt weed.',
'editinginterface'     => '<strong>Opjepass:</strong> 
Op dä Sigg hee steiht Tex usem Interface vun de Wiki-Soffwär. Dröm es die jäje Änderunge jeschötz, domet keine Mess domet aanjestallt weed. Nor de Wiki-Köbese künne 
se ändere. Denk dran, hee ändere deit et Ussinn un de Wööt ändere met dänne et Wiki op de Metmaacher un de Besöker drop aankütt!',
'sqlhidden'            => "(Dä SQL_Befähl du'mer nit zeije)",
'namespaceprotected'   => 'Do darfs Sigge em Appachtemang „$1“ nit ändere.',
'customcssjsprotected' => 'Do darfs di Sigg hee nit ändere. Se jehööt enem andere Metmacher un es e Stöck funn dämm sing eije Enstellunge.',
'ns-specialprotected'  => 'Söndersigge künne mer nit ändere.',

# Login and logout pages
'logouttitle'                => 'Uslogge',
'logouttext'                 => 'Jetz bes de usjelogg.

* Do künnts op de {{SITENAME}} wigger maache, als ene namelose Metmaacher.

* Do kanns De ävver och widder [[Special:Userlogin|enlogge]], als däselve oder och ene andere Metmaacher.

* Un Do kanns met <span class="plainlinks">[{{FULLURL:Special:Userlogin|type=signup}} ene neue Metmaacher 
aanmelde]</span>.

<strong>Opjepass:</strong>

Es möchlich, dat De de ein oder ander Sigg immer wigger aanjezeich kriss, wie wann de noch enjelogg wörs. Dun Dingem Brauser singe Cache fottschmieße oder leddich maache, öm us dä Nummer erus ze kumme!<br />',
'welcomecreation'            => '== Dach, $1! ==

Dinge Zojang för hee es do. Do bes jetz aanjemeldt. Denk dran, Do künnts der Ding [[Special:Preferences|Enstellunge]] hee op de {{SITENAME}} zeräächmaache.',
'loginpagetitle'             => 'Enlogge',
'yourname'                   => 'Metmaacher Name:',
'yourpassword'               => 'Passwood',
'yourpasswordagain'          => 'Noch ens dat Passwood',
'remembermypassword'         => 'Op Duur Aanmelde',
'yourdomainname'             => 'Ding Domain',
'externaldberror'            => 'Do wor ene Fähler en de externe Daatebank, oder Do darfs Ding extern Daate nit ändere. Dat Aanmelde jingk donevve.',
'loginproblem'               => '<strong>Met däm Enlogge es jet scheiv jelaufe.</strong><br />Bes esu jod, un dun et noch ens versöke!',
'login'                      => 'Enlogge',
'nav-login-createaccount'    => 'Enlogge, Aanmälde',
'loginprompt'                => 'Öm op de {{SITENAME}} [[Special:Userlogin|enlogge]] ze künne, muss De de Cookies en Dingem Brauser enjeschalt han.',
'userlogin'                  => 'Enlogge / Metmaacher wääde',
'logout'                     => 'Uslogge',
'userlogout'                 => 'Uslogge',
'notloggedin'                => 'Nit enjelogg',
'nologin'                    => 'Wann De Dich noch nit aanjemeldt häs, dann dun Dich $1.',
'nologinlink'                => 'neu aanmelde',
'createaccount'              => 'Aanmelde als ene neue Metmaacher',
'gotaccount'                 => 'Do häs ald en Aanmeldung op de {{SITENAME}}? Dann jangk nohm $1.',
'gotaccountlink'             => 'Enlogge',
'createaccountmail'          => 'Scheck mer en E-Mail met Passwood',
'badretype'                  => 'Ding zwei enjejovve Passwööder sin ungerscheedlich. Do muss De Dich för ein entscheide.',
'userexists'                 => 'Ene Metmaacher met däm Name: „<strong>$1</strong>“ jitt et ald. Schad. Do muss De Der ene andere Name usdenke.',
'youremail'                  => 'E-Mail *',
'username'                   => 'Metmaacher Name:',
'uid'                        => 'Metmaacher ID:',
'prefs-memberingroups'       => 'Bes en {{PLURAL:$1|de Metmaacherjrupp|<strong>$1</strong> Metmaacherjruppe|keijn Metmaacherjruppe}}',
'yourrealname'               => 'Dinge richtije Name *',
'yourlanguage'               => 'Die Sproch, die et Wiki kalle soll:',
'yourvariant'                => 'Ding Variant',
'yournick'                   => 'Name för en Ding Ungerschreff:',
'badsig'                     => 'De Ungeschreff jeiht esu nit - luur noh dem HTML dodren un maach et richtich.',
'email'                      => 'E-Mail',
'prefs-help-realname'        => '* Dinge richtije Name - kanns De fott looße - wann De en nenne wells, dann weed hee jebruch, öm Ding Beidräch domet ze schmöcke.',
'loginerror'                 => 'Fähler beim Enlogge',
'prefs-help-email'           => '* E-mail - kanns De fottlooße, un es för Andre nit ze sinn - mäht et ävver möchlich, dat mer met Dir en Kontak kumme kann, ohne dat mer Dinge Name oder Ding E-Mail Adress kenne dät.',
'prefs-help-email-required'  => 'Do moß en jöltije E-Mail-Adress aanjevve.',
'nocookiesnew'               => 'Dinge neue Metmaacher Name es enjerich, ävver dat automatisch Enlogge wor dann nix. 
Schad.
De {{SITENAME}} bruch Cookies, öm ze merke, wä enjelogg es.
Wann De Cookies avjeschald häs en Dingem Brauser, dann kann dat nit laufe.
Sök Der ene Brauser, dä et kann, dun se enschalte, un dann log Dich noch ens neu en, met Dingem neue Metmaacher Name un Passwood.',
'nocookieslogin'             => 'De {{SITENAME}} bruch Cookies för et Enlogge. Et süht esu us, als hätts de Cookies avjeschalt. Dun se aanschalte un dann versök et noch ens. Odder söök Der ene Brauser, dä et kann.',
'noname'                     => 'Dat jeiht nit als ene Metmaacher Name. Jetz muss De et noch ens versöke.',
'loginsuccesstitle'          => 'Dat Enlogge hät jeflupp.',
'loginsuccess'               => '<br />Do bes jetz enjelogg bei de <strong>{{SITENAME}}</strong>, un Dinge Metmaacher Name es „<strong>$1</strong>“.<br />',
'nosuchuser'                 => 'Dat Passwood oder dä Metmaacher Name „$1“ wor verkihrt.
Jetz muss De et noch ens versöke.
Oder_<span class="plainlinks">[{{FULLURL:Special:Userlogin|type=signup}} ene neue Metmaacher aanmelde]</span>.',
'nosuchusershort'            => 'Dä Metmaacher Name „<nowiki>$1</nowiki>“ wor verkihrt. Jetz muss De et noch ens versöke.',
'nouserspecified'            => 'Dat jeiht nit als ene Metmaacher Name',
'wrongpassword'              => 'Dat Passwood oder dä Metmaacher Name wor verkihrt. Jetz muss De et noch ens versöke.',
'wrongpasswordempty'         => "Dat Passwood ka'mer nit fottlooße. Jetz muss De et noch ens versöke.",
'passwordtooshort'           => 'Dat Passwood es jet koot - et mööte ald winnichstens <strong>$1</strong> Zeiche, Zeffer{{PLURAL:$1||e|e}}, un Buchstave do dren sin.',
'mailmypassword'             => 'Passwood verjesse?',
'passwordremindertitle'      => 'Enlogge op {{SITENAME}}',
'passwordremindertext'       => 'Jod möchlich, Do wors et selver,
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
'noemail'                    => 'Dä Metmaacher „[[User:$1|$1]]“ hät en dämm sing Enstellunge kein E-Mail Adress aanjejovve.',
'passwordsent'               => 'E neu Passwood es aan de E-Mail Adress vun däm Metmaacher „$1“ ungerwähs. Meld dich domet aan, wann De et häs. Dat ahle Passwood bliev erhalde un kann och noch jebruch wääde, bes dat De Dich et eetste Mol met däm Neue enjelogg häs.',
'blocked-mailpassword'       => 'Ding IP Adress es blockeet.',
'eauthentsent'               => 'En E-Mail es jetz ungerwähs aan de Adress, die en de Enstellunge vum Metmaacher $1 steiht.
Ih dat E-Mails üvver de {{SITENAME}} ehre E-Mail-Knopp verscheck wääde künne, muss de E-Mail Adress 
eets  ens bestätich woode sin. Wat mer doför maache muss, steiht en dä E-Mail dren, die jrad avjescheck woode es. 

Alsu luur do eren, un dun et.',
'throttled-mailpassword'     => 'En Erennerung för di Passwood es ungerwähs. Domet ene fiese Möpp keine Dress fabrizeet, passeet dat hüchstens eimol en {{PLURAL:$1|der Stund|$1 Stunde|nidd ens eine Stund}}.',
'mailerror'                  => 'Fähler beim E-Mail Verschecke: $1.',
'acct_creation_throttle_hit' => '<b>Schad.</b> Do häs ald {{PLURAL:$1|eine|$1|keine}} Metmaacher Name aanjelaht. Mieh sin nit möchlich.',
'emailauthenticated'         => 'Ding E-Mail Adress wood bestätich om: <strong>$1</strong>.',
'emailnotauthenticated'      => 'Ding E-Mail Adress es <strong>nit</strong> bestätich. Dröm kann kein E-Mail aan Dich jescheck wääde för:',
'noemailprefs'               => 'Dun en E-Mail Adress endrage, domet dat et all fluppe kann.',
'emailconfirmlink'           => 'Dun Ding E-Mail Adress bestätije looße',
'invalidemailaddress'        => 'Wat De do als en E-Mail Adress aanjejovve häs, süht noh Dress us. En E-Mail Adress en däm Format, dat jitt et nit. Muss De repareere - oder Do mähs dat Feld leddich un schrievs nix eren. Un dann versök  et noch ens.',
'accountcreated'             => 'Aanjemeldt',
'accountcreatedtext'         => 'De Aanmeldung för dä Metmaacher „<strong>$1</strong>“ es durch, kann jetz enlogge.',
'loginlanguagelabel'         => 'Sproch: $1',

# Password reset dialog
'resetpass_text' => '<!-- Donn der Täx hee dobei -->',

# Edit page toolbar
'bold_sample'     => 'Fett Schreff',
'bold_tip'        => 'Fett Schreff',
'italic_sample'   => 'Scheive Schreff',
'italic_tip'      => 'Scheive Schreff',
'link_sample'     => 'Anker Tex',
'link_tip'        => 'Ene Link en de {{SITENAME}}',
'extlink_sample'  => 'http://www.example.com/ Dä Anker Tex',
'extlink_tip'     => 'Ene Link noh drusse (denk dran, http:// aan dr Aanfang!)',
'headline_sample' => 'Üvverschreff',
'headline_tip'    => 'Üvverschreff op de bövverschte Ebene',
'math_sample'     => 'Hee schriev de Formel eren',
'math_tip'        => 'För mathematisch Formele nemm „LaTeX“',
'nowiki_sample'   => 'Hee kütt dä Tex hen, dä vun de Wiki-Soffwär nit bearbeid, un en Rauh jelooße wääde soll',
'nowiki_tip'      => 'Der Wiki-Code för et Fommatteere üvverjonn',
'image_sample'    => 'Beispill.jpg',
'image_tip'       => 'E Beldche enbaue',
'media_sample'    => 'Beispill.ogg',
'media_tip'       => 'Ene Link op en Tondatei, e Filmche, oder esu jet',
'sig_tip'         => 'Dinge Name, met de Uhrzigg un em Datum',
'hr_tip'          => 'En Querlinnich',

# Edit pages
'summary'                   => 'Koot Zosammejefass, Quell',
'subject'                   => 'Üvverschreff - wodröm jeiht et?',
'minoredit'                 => 'Dat es en klein Änderung (mini)',
'watchthis'                 => 'Op die Sigg hee oppasse',
'savearticle'               => 'De Sigg Avspeichere',
'preview'                   => 'Vör-Ansich',
'showpreview'               => 'Vör-Aansich zeije',
'showlivepreview'           => 'Lebendije Vör-Aansich zeije',
'showdiff'                  => 'De Ungerscheed zeije',
'anoneditwarning'           => 'Weil De nit aanjemeldt bes, weed Ding IP-Adress opjezeichnet wääde.',
'missingsummary'            => '<strong>Opjepass:</strong> Do häs nix bei „Koot Zosammejefass, Quell“ enjejovve. Dun noch ens op „<b style="padding:2px; background-color:#ddd; color:black">De Sigg Avspeichere</b>“ klicke, öm Ding Änderunge ohne de Zosammefassung ze Speicheree. Ävver besser jiss De do jetz tirek ens jet en!',
'missingcommenttext'        => 'Jevv en „Koot Zosammejefass, Quell“ aan!',
'missingcommentheader'      => "'''Opjepass:''' Do häs kein Üvverschreff för Dinge Beidrach enjejovve. Wann De noch ens op „De Sigg Avspeichere“ dröcks, weed dä Beidrach ohne Üvverschreff avjespeichert.",
'summary-preview'           => 'Vör-Aansich vun „Koot Zosammejefass, Quell“',
'subject-preview'           => 'Vör-Aansich vun de Üvverschreff',
'blockedtitle'              => 'Dä Metmaacher es jesperrt',
'blockedtext'               => '<big><b>Dinge Metmaacher-Name oder IP Adress es vun „$1“ jesperrt woode.</b></big> Als Jrund es enjedrage: „<i>$2</i>“

Do kanns hee em Wiki immer noch lesse. Do sühß ävver di Sigg hee, wann De op rude Links klicks, neu Sigge aanlääje, odder Sigge ändere wells, denn doför bes De jetz jesperrt.

Do kanns met $1 oder enem andere [[{{MediaWiki:Grouppage-sysop}}|Wiki-Köbes]] üvver dat Sperre schwaade, wann De wells.
Do kanns ävver nor dann „<i>E-Mail aan dä Metmaacher</i>“ aanwende, wann De ald en E-Mail Adress en Dinge [[Special:Preferences|Enstellunge]] enjedrage un freijejovve häs.

Dun en Ding Aanfroge nenne:
* Dä Wiki-Köbeß, dä jesperrt hät: $1
* Der Jrond för et Sperre: $2
* Da wood jesperrt: $8
* De Sperr soll loufe bes: $6
* De Nommer vun dä Sperr: #$5
* Ding IP-Adress is jetz: $3
* Di Sperr es wäje odde jäje: $7
* Ußerdämm: #$4

Do kanns och noch en et <span class="plainlinks">[{{fullurl:Special:Ipblocklist|&action=search&limit=&ip=%23}}$5 Logbooch met de Sperre ]</span> loore.',
'blockednoreason'           => 'Keine Aanlass aanjejovve',
'blockedoriginalsource'     => 'Dä orjenal Wiki Tex vun dä Sigg „<strong>$1</strong>“ steiht hee drunger:',
'blockededitsource'         => 'Dä Wiki Tex vun <strong>Dinge Änderunge</strong> aan dä Sigg „<strong>$1</strong>“ steiht hee drunger:',
'whitelistedittitle'        => 'Enlogge es nüdich för Sigge ze Ändere',
'whitelistedittext'         => 'Do mööts ald $1, öm hee em Wiki Sigge ändere ze dürfe.',
'whitelistreadtitle'        => 'Enlogge es nüdich för ze Lese',
'whitelistreadtext'         => 'Do mööts ald_[[Special:Userlogin|enjelogg sin]], öm hee Sigge lese ze dürfe.',
'whitelistacctitle'         => 'Kei Rääch för Metmaacher aanzeläje.',
'whitelistacctext'          => 'Do mööts ald [[Special:Userlogin|enjelogg sin]] un speziell et Rääch doför han, öm hee en däm Wiki Metmaacher enrichte un aanläje ze dürfe.',
'confirmedittitle'          => 'För et Sigge Ändere muss De Ding E-Mail Adress ald bestätich han.',
'confirmedittext'           => 'Do muss Ding E-Mail Adress ald bestätich han, ih dat De hee Sigge ändere darfs. Drag Ding E-Mail Adress en Ding [[{{ns:special}}:Preferences|ming Enstellunge]] en, un dun „<span style="padding:2px; background-color:#ddd; color:black">Dun Ding E-Mail Adress bestätije looße</span>“ klicke.',
'nosuchsectiontitle'        => "Dä Afschnitt ham'mer nit",
'loginreqtitle'             => 'Enlogge es nüdich',
'loginreqlink'              => 'enjelogg sin',
'loginreqpagetext'          => 'Do mööts eets ens $1, öm ander Sigge aanzeluure.',
'accmailtitle'              => 'Passwood verscheck.',
'accmailtext'               => 'Dat Passwood för dä Metmaacher „$1“ es aan „$2“ jescheck woode.',
'newarticle'                => '(Neu)',
'newarticletext'            => 'Ene Link op en Sigg, wo noch nix drop steiht, weil et se noch jar nit jitt, hät Dich noh hee jebraht.<br /><small>Öm die Sigg aanzeläje, schriev hee unge en dat Feld eren, un dun dat dann avspeichere. Luur op de [[int:MediaWiki:Helppage|Sigge met Hölp]] noh, wann De mieh dodrüvver wesse wells. Wann De jar nit hee hen kumme wollts, dann jangk zeröck op die Sigg, wo De herjekumme bes, Dinge Brauser hät ene Knopp doför.</small>',
'anontalkpagetext'          => '----
<i>Dat hee es de Klaaf Sigg för ene namenlose Metmaacher. Dä hät sich noch keine Metmaacher Name jejovve un 
enjerich, ov deit keine bruche. Dröm bruche mer sing IP Adress öm It oder In en uns Lisste fasszehalde. 
Su en IP Adress kann vun janz vill Metmaacher jebruch wääde, un eine Metmaacher kann janz flöck 
zwesche de ungerscheedlichste IP Adresse wähßele, womöchlich ohne dat hä et merk. Wann Do jetz ene namenlose 
Metmaacher bes, un fings, dat hee Saache an Dich jeschrevve wääde, wo Do jar nix met am Hot häs, dann bes Do 
wahrscheinlich och nit jemeint. Denk villeich ens drüvver noh, datte Dich [[Special:Userlogin|anmelde]] deis, 
domet De dann donoh nit mieh met esu en Ömständ ze dun häs, wie de andere namenlose Metmaacher hee.</i>',
'noarticletext'             => '<span class="plainlinks">Em Momang es keine Tex op dä Sigg. Jangk en de Texte vun ander Sigge [[Special:Search/{{PAGENAME}}|noh däm Titel söke]], oder [{{FULLURL:{{FULLPAGENAME}}|action=edit}} fang die Sigg aan] ze schrieve, oder jangk zeröck wo de her koms. Dinge Brauser hät ene Knopp doför.</span>',
'userpage-userdoesnotexist' => 'Enne Metmaacher „$1“ hammer nit, beß De secher, dat De die Metmaachersigg ändere oder aanläje wellss?.',
'clearyourcache'            => "<br clear=\"all\" style=\"clear:both\">
'''Opjepass:'''
Noh em Speichere, künnt et sin, datte Dingem Brauser singe Cache Speicher 
üvverlisste muss, ih datte de Änderunge och ze sinn kriss.
Beim '''Mozilla''' un  '''Firefox''' un '''Safari''', dröck de ''Jroß Schreff Knopp'' un 
Klick op ''Refresh'' / ''Aktualisieren'', oder dröck ''Ctrl-Shift-R'' / ''Strg+Jroß Schreff+R'', oder 
dröck ''Ctrl-F5'' / ''Strg/F5'' / ''Cmd+Shift+R'' / ''Cmd+Jroß Schreff+R'', je noh Ding Tastatur 
un Dingem Kompjuter.
Beim '''Internet Explorer''' dröck op ''Ctrl'' / ''Strg'' un Klick op ''Refresh'', oder dröck 
''Ctrl-F5'' / ''Strg+F5''.
Beim '''Konqueror:''' klick dä ''Reload''-Knopp oder dröck dä ''F5''-Knopp.
Beim  '''Opera''' kanns De üvver et Menue jonn un 
däm janze Cache singe Enhald üvver ''Tools?Preferences'' fottschmieße.",
'usercssjsyoucanpreview'    => '<b>Tipp:</b> Dun met däm <b style="padding:2px; background-color:#ddd; 
color:black">Vör-Aansich Zeije</b>-Knopp usprobeere, wat Ding neu 
Metmaacher_CSS/Java_Skripp mäht, ih dat et avspeichere deis!',
'usercsspreview'            => '<b>Opjepass: Do bes hee nor am Usprobeere, wat Ding 
Metmaacher_CSS mäht, et es noch nit jesechert!</b>',
'userjspreview'             => "<strong>Opjepass:</strong> Do bes hee nor am Usprobeere, wat Ding 
Metmaacher_Java_Skripp mäht, et es noch nit jesechert!

<strong>Opjepass:</strong> Noh dem Avspeichere moß de Dingem Brauser noch singe Cache fottschmiiße.
Dat jeit je noh Bauser met ongerscheedleje Knöpp —
beim '''Mozilla''' un em '''Firefox''': ''Strg-Shift-R'' —
em '''Internet Explorer''': ''Strg-F5'' —
för der '''Opera''': ''F5'' —
mem '''Safari''': ''Cmd-Shift-R'' —
un em '''Konqueror''': ''F5'' —
et ess en bunte Welt!",
'userinvalidcssjstitle'     => '<strong>Opjepass:</strong> Et jitt kein Ussinn met däm Name: „<strong>$1</strong>“ - 
denk dran, dat ene Metmaacher eije Dateie för et Ussinn han kann, un dat die met kleine Buchstave 
aanfange dun, alsu etwa: {{ns:user}}:Name/monobook.css, un {{ns:user}}:Name/monobook.js heiße.',
'updated'                   => '(Aanjepack)',
'note'                      => '<strong>Opjepass:</strong>',
'previewnote'               => '<strong>Hee kütt nor de Vör-Aansich - Ding Änderunge sin noch nit jesechert!</strong>',
'previewconflict'           => 'Hee die Vör-Aansich zeich dä Enhald vum bovvere Texfeld.
Esu wööd dä Atikkel ussinn, wann De n jetz avspeichere däts.',
'session_fail_preview'      => '<strong>Schad: Ding Änderunge kunnte mer su nix met aanfange.

De Daate vun Dinge Login-Säschen sin nit öntlich erüvver jekumme, oder einfach ze alt.
Versök et jrad noch ens. Wann dat widder nit flupp, dann versök et ens met [[Special:Userlogout|Uslogge]] 
un widder Enlogge. Ävver pass op, datte Ding Änderunge dobei behälds! Zor Nud dun se eets ens bei Dir om Rechner 
avspeichere.</strong>',
'session_fail_preview_html' => '<strong>Schad: Ding Änderunge kunnte mer su nix met aanfange.<br />De Daate vun 
Dinge Login-Säschen sin nit öntlich erüvver jekumme, oder einfach ze alt.</strong>
Dat Wiki hee hät <i>rüh HTML</i> zojelooße, dröm weed de Vör-Aansich nit jezeich. Domet solls De jeschötz wääde - 
hoffe mer - un Aanjreffe met Java_Skripp jäje Dinge Kompjuter künne Der nix aandun.
<strong>Falls för Dich söns alles jod ussüht, versök et jrad noch ens. Wann dat widder nit flupp, dann versök et 
ens met [[Special:Userlogout|Uslogge]] un widder Enlogge. Ävver pass op, datte Ding Änderunge dobei behälds! 
Zor Nud dun se eets ens bei Dir om Rechner avspeichere.</strong>',
'editing'                   => 'De Sigg „$1“ ändere',
'editingsection'            => 'Ne Avschnedd vun dä Sigg: „$1“ ändere',
'editingcomment'            => '„$1“ Ändere (ene neue Avschnedd schrieve)',
'editconflict'              => 'Problemche: „$1“ dubbelt bearbeidt.',
'explainconflict'           => '<br />Ene andere Metmaacher hät aan dä Sigg och jet jeändert, un zwar nohdäm Do et Ändere aanjefange häs. Jetz ha\'mer dr Dress am Jang, un Do darfs et widder uszoteere.
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
'yourtext'                  => 'Dinge Tex',
'storedversion'             => 'De jespeicherte Version',
'nonunicodebrowser'         => '<strong>Opjepass:</strong>
Dinge Brauser kann nit öntlich met däm Unicode un singe Buchstave ömjonn.
Bes esu jod un nemm ene andere Brauser för hee die Sigg!',
'editingold'                => '<strong>Opjepass!<br />
Do bes en ahle, üvverhollte Version vun dä Sigg hee am Ändere.
Wann De die avspeichere deis,
wie se es,
dann jonn all die Änderunge fleute,
die zickdäm aan dä Sigg jemaht woode sin.
Alsu:
Bes De secher, watte mähs?
</strong>',
'yourdiff'                  => 'Ungerscheede',
'copyrightwarning'          => 'Ding Beidräch stonn unger de [[$2]], süch $1. Wann De nit han wells, dat Dinge Tex ömjemodelt weed, un söns wohin verdeilt, dun en hee nit speichere. Mem Avspeichere sähs De och zo, dat et vun Dir selvs es, un/oder Do dat Rääch häs, en hee zo verbreide. Wann et nit stemmp, oder Do kanns et nit nohwiese, kann Dich dat en dr Bau bränge!',
'copyrightwarning2'         => 'De Beidräch en de {{SITENAME}} künne vun andere Metmaacher ömjemodelt 
oder fottjeschmesse wääde. Wann Der dat nit rääch es, schriev nix. Et es och nüdich, dat et vun Dir selvs es, oder dat Do dat Rääch häs, et hee öffentlich wigger ze jevve. Süch $1. Wann et nit stemmp, oder Do kanns et nit nohwiese, künnt Dich dat en dr Bau bränge!',
'longpagewarning'           => '<strong>Oppjepass:</strong> Dä Tex, dä De hee jescheck häs, dä es <strong>$1</strong> 
Kilobyte jroß. Manch Brauser kütt nit domet klor, wann et mieh wie <strong>32</strong> Kilobyte sin. Do künnts De drüvver nohdenke, dat Dinge en kleiner Stöckche ze zerkloppe.',
'longpageerror'             => '<big><strong>Janz schlemme Fähler:</strong></big>
Dä Tex, dä De hee jescheck häs, dä es <strong>$1</strong> Kilobyte jroß. 
Dat sin mieh wie <strong>$2</strong> Kilobyte. Dat künne mer nit speichere!
<strong>Maach kleiner Stöcke drus.</strong><br />',
'readonlywarning'           => '<strong>Opjepass:</strong>
De Daatebank es jesperrt woode, wo Do ald am Ändere wors. 
Dä.
Jetz kanns De Ding Änderunge nit mieh avspeichere.
Dun se bei Dir om Rechner fasshalde un versök et späder noch ens.',
'protectedpagewarning'      => '<strong>Opjepass:</strong>
Die Sigg hee es jäje Veränderunge jeschötz - wieso weed em <span class="plainlinks">[{{FULLURL:Special:Log/protect|page={{FULLPAGENAMEE}}}} Logboch]</span> stonn.
Nor de Wiki-Köbese künne se ändere.
Bes esu jod un hald Dich aan de [[{{int:Policy-url}}|Rejelle]] för esu en Fäll!',
'semiprotectedpagewarning'  => '<strong>Opjepass:</strong> Die Sigg hee es halv jesperrt, wie mer sage, dat heiß, Do muss [[Special:Userlogin|aanjemeldt un enjelogg]] sin, wann De dran ändere wells.',
'titleprotectedwarning'     => '<strong> <span style="text-transform:uppercase"> Opjepaß! </span> Di Sigg hee is jesperrt woode. Bloß bestemmpte Metmaacher dörve di Sigg neu aanläje.</strong>',
'templatesused'             => 'De Schablone, die vun dä Sigg hee jebruch wääde, sinn:',
'templatesusedpreview'      => 'Schablone en dä Vör-Aansich hee:',
'templatesusedsection'      => 'Schablone en däm Avschnedd hee:',
'template-protected'        => '(jeschöz)',
'template-semiprotected'    => '(halfjeschöz - tabu för neu Metmaacher un ohne Enlogge)',
'edittools'                 => '<!-- Dä Tex hee zeich et Wiki unger däm Texfeld zom „Ändere/Bearbeide“ un beim Texfeld vum „Huhlade“. -->',
'nocreatetitle'             => 'Neu Sigge Aanläje eß nit einfach esu mööschlesch.',
'nocreatetext'              => 'Sigge neu aanläje es nor möchlich, wann de [[Special:Userlogin|enjelogg]] bes. Der ohne kanns De ävver Sigge ändere, die ald do sin.',

# "Undo" feature
'undo-success' => 'De Änderung könnte mer zeröck nämme. Beloor Der de Ungerscheed un dann donn di Sigg avspeichere, wann De dengks, et es en Oodenung esu.',
'undo-failure' => '<span class="error">Dat kunnt mer nit zeröck nämme, dä Afschnedd wood enzwesche ald widder beärbeidt.</span>',
'undo-norev'   => '<span class="error">Do ka\'mer nix zeröck nämme. Di Version jidd_et nit, odder se es verstoche odder fottjeschmesse woode.</span>',
'undo-summary' => 'De Änderung $1 fum [[{{#special:Contributions}}/$2|$2]] ([[{{ns:User talk}}:$2|Klaaf]]) zeröck jenomme.',

# Account creation failure
'cantcreateaccounttitle' => 'Kann keine Zojang enrichte',
'cantcreateaccount-text' => "Dä [[User:$3|$3]] hät verbodde, dat mer sich vun dä IP-Adress '''$1''' uß als ene neue Metmaacher aanmelde könne soll.

Als Jrund för et Sperre es enjedraare: ''$2''",

# History pages
'viewpagelogs'        => 'De Logböcher för hee die Sigg beloore',
'nohistory'           => 'Et jitt kei fottjeschmesse, zeröckhollba Versione vun dä Sigg.',
'revnotfound'         => "Die Version ha'mer nit jefunge.",
'revnotfoundtext'     => '<b>Dä.</b> Die ählere Version vun dä Sigg, wo De noh frochs, es nit do. Schad. Luur ens 
op die URL, die Dich herjebraht hät, die weed verkihrt sin, oder se es villeich üvverhollt, weil einer die Sigg 
fottjeschmesse hät?',
'currentrev'          => 'Neuste Version',
'revisionasof'        => 'Version vum $1',
'revision-info'       => 'Dat es de Version vum $1 vum $2.',
'previousrevision'    => '← De Version dovör zeije',
'nextrevision'        => 'De Version donoh zeije →',
'currentrevisionlink' => 'De neuste Version',
'cur'                 => 'jetz',
'next'                => 'wigger',
'last'                => 'zerök',
'page_first'          => 'Aanfang',
'page_last'           => 'Engk',
'histlegend'          => 'Hee kanns De Versione för et Verjliche ussöke: Dun met dä Knöpp die zweij markiere, 
zwesche dänne De de Ungerscheed jezeich krije wells, dann dröck „<b style="padding:2px; background-color:#ddd; 
color:black">Dun de markeete Versione verjliche</b>“ bei Dinge Taste, oder klick op ein vun dä Knöpp üvver oder 
unger de Liss.
Erklärung: (neu) = Verjliche met de neuste Version, (letz) = Verjliche met de Version ein doför, <b>M</b> = en 
kleine <b>M</b>ini-Änderung.',
'deletedrev'          => '[fott]',
'histfirst'           => 'Ählste',
'histlast'            => 'Neuste',
'historysize'         => '({{PLURAL:$1|1 Byte|$1 Bytes|0 Byte}})',
'historyempty'        => '(leddich)',

# Revision feed
'history-feed-title'          => 'Versione',
'history-feed-description'    => 'Ählere Versione vun dä Sigg en de {{SITENAME}}',
'history-feed-item-nocomment' => '$1 öm $2', # user at time
'history-feed-empty'          => 'De aanjefrochte Sigg jitt et nit. Künnt sin, dat se enzwesche fottjeschmesse oder ömjenannt woode es. Kanns jo ens [[Special:Search|em Wiki söke looße]], öm zopass neu Sigge ze finge.',

# Revision deletion
'rev-deleted-comment'         => '(„Koot Zosammejefass, Quell“ usjeblendt)',
'rev-deleted-user'            => '(Metmaacher Name usjeblendt)',
'rev-deleted-event'           => '(Logbooch-Enndraach fottjenomme)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">Die Version es fottjeschmesse woode.
Jetz ka\'mer se nit mieh beluure.
Ene Wiki Köbes künnt se ävver zeröck holle.
Mieh drüvver, wat met däm Fottschmieße vun dä Sigg jewäse es, künnt Ehr em [{{FULLURL:Spezial:Log/delete|page={{PAGENAMEE}}}} Logboch] nohlese.</div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks">Die Version es fottjeschmesse woode.
Jetz ka\'mer se nit mieh beluure. Als ene Wiki-Köbes kriss De se ävver doch ze sinn, un künnts se och zeröck holle. Mieh drüvver, wat met däm Fottschmieße vun dä Sigg jewäse es, künnt Ehr em [{{FULLURL:Spezial:Log/delete|page={{PAGENAMEE}}}} Logboch] nohlese.</div>',
'rev-delundel'                => 'zeije/usblende',
'revisiondelete'              => 'Versione fottschmieße un widder zeröck holle',
'revdelete-nooldid-title'     => 'Kein Version aanjejovve',
'revdelete-nooldid-text'      => 'Do häs kein Version aanjejovve.',
'revdelete-selected'          => '{{PLURAL:$2|Ein usjewählte Version|$2 usjewählte Versione|Kein Version usjewählt}} vun [[:$1]]:',
'revdelete-text'              => 'Dä fottjeschmesse Sigge ehre Enhald kanns De nit mieh aanluure. Se blieve ävver en de Liss met de Versione dren.

Ene Wiki Köbes kann de fottjeschmessene Krom immer noch aanluere un kann en och widder herholle, usser wann bei 
dem Wiki singe Installation dat anders fassjelaht woode es.',
'revdelete-legend'            => 'Dä öffentlije Zojang enschränke, för die Version:',
'revdelete-hide-text'         => 'Dä Tex vun dä Version usblende',
'revdelete-hide-name'         => 'Der Förjang un der Enndraach em Logbooch versteiche',
'revdelete-hide-comment'      => 'Dä Enhald vun „Koot Zosammejefass, Quell“ usblende',
'revdelete-hide-user'         => 'Däm Bearbeider sing IP Adress oder Metmaacher Name usblende',
'revdelete-hide-restricted'   => 'Dun dat och för de Wiki Köbese esu maache wie för jede Andere',
'revdelete-hide-image'        => 'De Enhallt vun däm Beld versteiche',
'revdelete-log'               => 'Bemerkung för et LogBoch:',
'revdelete-submit'            => 'Op de aanjekrützte Version aanwende',
'revdelete-logentry'          => 'Zojang zo de Versione verändert för „[[$1]]“',
'deletedhist'                 => 'Fottjeschmesse Versione',
'revdelete-content'           => 'dä Enhalt fun dä Sigg',
'revdelete-summary'           => 'dä Täx en „{{int:summary}}“',
'revdelete-uname'             => 'dä Metmaachername',
'revdelete-restricted'        => ', och för de Wiki-Köbesse',
'revdelete-unrestricted'      => ', och för de Wiki-Köbesse',
'revdelete-hid'               => '$1 verstoche',
'revdelete-unhid'             => '$1 weder seeschbaa jemaat',
'revdelete-log-message'       => 'hät för {{PLURAL:$2|eij Version|$2 Versione|nix}} $1 — $3',

# Diffs
'history-title'           => 'Liss met Versione vun „$1“',
'difference'              => '(Ungerscheed zwesche de Versione)',
'lineno'                  => 'Reih $1:',
'compareselectedversions' => 'Dun de markeete Version verjliche',
'editundo'                => 'zeröck nämme',

# Search results
'searchresults'         => 'Wat beim Söke eruskom',
'searchresulttext'      => 'Luur en de [[{{MediaWiki:Helppage}}|{{int:help}}]]-Sigge noh, wann de mieh drüvver wesse wells, wie mer en de {{SITENAME}} jet fingk.',
'searchsubtitle'        => 'För Ding Froch noh „[[:$1]]“.',
'searchsubtitleinvalid' => 'För Ding Froch noh „$1“.',
'noexactmatch'          => 'Mer han kein Sigg met jenau däm Name „<strong>$1</strong>“ jefunge.
Do kanns se [[:$1|aanläje]], wann De wells.',
'noexactmatch-nocreate' => "'''Et jitt kei Sigg met däm Titel „$1“.'''",
'titlematches'          => 'Zopass Üvverschrefte',
'notitlematches'        => 'Kein zopass Üvverschrefte',
'textmatches'           => 'Sigge met däm Täx',
'notextmatches'         => 'Kein Sigg met däm Tex',
'prevn'                 => 'de $1 doför zeije',
'nextn'                 => 'de nächste $1 zeije',
'viewprevnext'          => 'Bläddere: ($1) ($2) ($3).',
'search-result-size'    => '$1 ({{PLURAL:$2|Eij Woot|$2 Wööter|Keij Woot}})',
'search-section'        => '(Avschnett $1)',
'mwsuggest-disable'     => 'Kein automatische Hölp-Liss per Ajax beim Tippe em Feld för et Söke',
'searchall'             => 'all',
'showingresults'        => 'Unge {{PLURAL:$1|weed <strong>eine</strong>|wääde bes <strong>$1</strong>|weed <strong>keine</strong>}} vun de jefunge Endräch jezeich, vun de Nummer <strong>$2</strong> av.',
'showingresultsnum'     => 'Unge {{PLURAL:$3|es ein|sin <strong>$3</strong>|sin <strong>kein</strong>}} vun de jefunge Endräch opjeliss, vun de Nummer <strong>$2</strong> av.',
'nonefound'             => '<strong>Opjepass:</strong>
Wann beim Söke nix erus kütt, do kann dat dran lije, dat mer esu janz jewöhnliche Wööd, wie „hät“, „alsu“, „wääde“, un „sin“, uew. jar nit esu en de Daatebank dren han, dat se jefonge wääde künnte.',
'powersearch'           => 'Söke',
'powersearch-legend'    => 'Extra Sööke',
'powersearch-ns'        => 'Söök en de Apachtemangs:',
'powersearch-redir'     => 'Ömleidunge aanzeije',
'powersearch-field'     => 'Söök noh:',
'searchdisabled'        => 'Dat Söke hee op de {{SITENAME}} es em Momang avjeschalt.
Dat weed vun dänne Sörver ad ens jemaat, domet de Lass op inne nit ze jroß weed,
un winnichstens dat normale Sigge Oprofe flöck jenoch jeiht.

Ehr künnt esu lang üvver en Sökmaschin vun usserhalv immer noch
Sigge op de {{SITENAME}} finge.
Et es nit jesaht,
dat dänne ihr Daate topaktuell sin,
ävver et es besser wie jar nix.',

# Preferences page
'preferences'              => 'ming Enstellunge',
'preferences-summary'      => 'Op dä Sondersigg hee kanns De Ding Zojangsdate ändere un bestemmpte Aandeile vum Wiki noh Dingem Jeschmack ennrechte un för Dich parat maache.',
'mypreferences'            => 'Ming Enstellunge',
'prefs-edits'              => 'Aanzahl Änderunge am Wiki:',
'prefsnologin'             => 'Nit Enjelogg',
'prefsnologintext'         => 'Do mööts ald [[Special:Userlogin|enjelogg]] sin, öm Ding Enstellunge ze ändere.',
'prefsreset'               => 'De Enstellunge woodte jetz op Standard zeröck jesatz.',
'qbsettings'               => '„Flöcke Links“',
'qbsettings-none'          => 'Fottlooße, dat well ich nit sinn',
'qbsettings-fixedleft'     => 'Am linke Rand fass aanjepapp',
'qbsettings-fixedright'    => 'Am rächte Rand fass aanjepapp',
'qbsettings-floatingleft'  => 'Am linke Rand am Schwevve',
'qbsettings-floatingright' => 'Am rächte Rand am Schwevve',
'changepassword'           => 'Passwood ändere',
'skin'                     => 'Et Ussinn',
'math'                     => 'Mathematisch Formele',
'dateformat'               => 'Em Datum sing Fomat',
'datedefault'              => 'Ejaal - kein Vörliebe',
'datetime'                 => 'Datum un Uhrzigge',
'math_failure'             => 'Fähler vum Parser',
'math_unknown_error'       => 'Fähler, dä mer nit kenne',
'math_unknown_function'    => 'en Funktion, die mer nit kenne',
'math_lexing_error'        => 'Fähler beim Lexing',
'math_syntax_error'        => 'Fähler en de Syntax',
'math_image_error'         => 'De Ömwandlung noh PNG es donevve jejange. Dun ens noh de richtije Enstallation luure bei <i>latex</i>, <i>dvips</i>, <i>gs</i>, un <i>convert</i>. Oder sag et enem Sörver-Admin, oder enem Wiki Köbes.',
'math_bad_tmpdir'          => 'Dat Zwescheverzeichnis för de mathematische Formele lööt sich nit aanläje oder nix eren schrieve. Dat es Dress. Sag et enem Wiki-Köbes oder enem Sörver-Minsch.',
'math_bad_output'          => 'Dat Verzeichnis för de mathematische Formele lööt sich nit aanläje oder mer kann nix eren schrieve. Dat es Dress. Sag et enem Wiki-Köbes oder enem Sörver-Minsch.',
'math_notexvc'             => "Dat Projamm <code>texvc</code> ha'mer nit jefunge. Sag et enem 
Wiki-Köbes, enem Sörver-Minsch, oder luur ens en de 
<code>math/README</code>.",
'prefs-personal'           => 'De Enstellunge',
'prefs-rc'                 => 'Neuste Änderunge',
'prefs-watchlist'          => 'De Oppassliss',
'prefs-watchlist-days'     => 'Aanzahl Dage för en ming Oppassliss aanzezeije:',
'prefs-watchlist-edits'    => 'Aanzahl Änderunge för en ming verjrößerte Oppassliss aanzezeije:',
'prefs-misc'               => 'Söns',
'saveprefs'                => 'Fasshalde',
'resetprefs'               => 'Zeröck setze',
'oldpassword'              => 'Et ahle Passwood:',
'newpassword'              => 'Et neue Passwood:',
'retypenew'                => 'Noch ens dat neue Passwood:',
'textboxsize'              => 'Beim Bearbeide',
'rows'                     => 'Reihe:',
'columns'                  => 'Spalte:',
'searchresultshead'        => 'Beim Söke',
'resultsperpage'           => 'Zeich Treffer pro Sigg:',
'contextlines'             => 'Reihe för jede Treffer:',
'contextchars'             => 'Zeiche us de Ömjevvung, pro Reih:',
'stub-threshold'           => 'Links passend för <a href="#" class="stub">klein Sigge</a> fomateere av esu vill Bytes:',
'recentchangesdays'        => 'Aanzahl Dage en de Liss met de „Neuste Änderunge“ — als Standad:',
'recentchangescount'       => 'Aanzahl Endräch en de Liss met de „Neuste Änderunge“ — als Standad:',
'savedprefs'               => 'Ding Enstellunge sin jetz jesechert.',
'timezonelegend'           => 'Ziggzone Ungerscheed',
'timezonetext'             => '<!-- Â¹ -->Dat sin de Stunde un Minutte zwesche de Zigg op de Uhre bei Dir am Oot un däm Sörver, dä met UTC läuf.',
'localtime'                => 'De Zigg op Dingem Kompjuter:',
'timezoneoffset'           => 'Dä Ungerscheed Â¹ es:',
'servertime'               => 'De Uhrzigg om Sörver es jetz:',
'guesstimezone'            => 'Fing et erus üvver dä Brauser',
'allowemail'               => 'E-Mail vun andere Metmaacher zolooße',
'prefs-searchoptions'      => 'Enstellunge för et Sööke',
'prefs-namespaces'         => 'Appachtemangs',
'defaultns'                => 'Dun standaadmäßich en hee dä Appachtemengs söke:',
'default'                  => 'Standaad',
'files'                    => 'Dateie',

# User rights
'userrights'                       => 'Metmaacher ehr Räächde verwalte', # Not used as normal message but as header for the special page itself
'userrights-summary'               => 'He kanns De ennem bestemmpte Metmaacher sing Rääschte ändere, indemm dat De en en einzel Medmaacher-Jroppe erin deihß odder uß esu en Medmaacher-Jroppe eruß nimmbs.',
'userrights-lookup-user'           => 'Metmaacher Jruppe verwalte',
'userrights-user-editname'         => 'Metmaacher Name:',
'editusergroup'                    => 'Däm Metmaacher sing Jruppe Räächde bearbeide',
'editinguser'                      => "Däm '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]] | [[Special:Contributions/$1|{{int:contribslink}}]]) sing Metmaachersigg ändere",
'userrights-editusergroup'         => 'Metmaacher Jruppe aanpasse',
'saveusergroups'                   => 'Metmaacher Jruppe avspeichere',
'userrights-groupsmember'          => 'Es en de Metmaacher Jruppe:',
'userrights-groupsremovable'       => 'Nemm eruß us de Jruppe:',
'userrights-groupsavailable'       => 'Es nit en de Metmaacher Jruppe:',
'userrights-groups-help'           => 'Do kanns de Jruppe för dä Metmaacher hee ändere.
* E Käßje met Höksche bedüg, dat dä Metmaacher en dä Jrupp is.
* E Stähnsche bedüg, dat De dat Rääsch zwa ändere, ävver de Änderung nit mieh zeröck nämme kanns.',
'userrights-reason'                => 'Aanlaß odder Jrund:',
'userrights-available-none'        => 'Du darfs der Metmaacher ier Rääschte nit verändere.',
'userrights-available-add'         => 'Do kanns ander Metmaacher {{PLURAL:$2|nur en ein Jrupp erin donn: $1.|en $2 Jruppe erin donn: $1.|en kein Jrupp erin donn. $1}}',
'userrights-available-remove'      => 'Do kanns jede Metmaacher {{PLURAL:$2|nur us ein Jrupp eruß nemme: $1.|us beß zo $2 Jruppe eruß nemme: $1.|uß kein Jrupp eruß nemme. $1}}',
'userrights-available-add-self'    => 'Do kanns Dich sellver in {{PLURAL:$2|die Jrupp erin donn: $1.|die Jruppe erin donn: $1.|kein Jrupp erin donn. $1}}',
'userrights-available-remove-self' => 'Do kanns Dich sellver uß {{PLURAL:$2|dä Jrupp eruß nemme: $1.|dä Jruppe eruß nemme: $1.|kein Jrupp eruß nemme. $1}}',
'userrights-no-interwiki'          => 'Do häs nit dat Rääsch, Metmaacher ier Rääschte in ander Wikis ze ändere.',
'userrights-nodatabase'            => 'De Datebank „<strong>$1</strong>“ is nit doh, oder se litt op enem andere Söver.',
'userrights-nologin'               => 'Do moss als ene Wiki-Köbes [[{{#special:Userlogin}}|enjelog sin]], för dat De Metmaacher ier Rääschte ändere kanns.',
'userrights-notallowed'            => 'Do häs nit dat Rääsch, Rääschte aan Metmaacher ze verdeile.',
'userrights-changeable-col'        => 'Jruppe, die De ändere kanns',
'userrights-unchangeable-col'      => 'Jruppe, die De nit ändere kanns',

# Groups
'group'               => 'Jrupp:',
'group-user'          => 'Metmaacher',
'group-autoconfirmed' => 'Bestätichte Metmaacher',
'group-bot'           => 'Bots',
'group-sysop'         => 'Wiki Köbese',
'group-bureaucrat'    => 'Bürrokrade',
'group-suppress'      => 'Kontrollettis',
'group-all'           => '(all)',

'group-user-member'          => 'Metmaacher',
'group-autoconfirmed-member' => 'Bestätichte Metmaacher',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Wiki Köbes',
'group-bureaucrat-member'    => 'Bürrokrad',
'group-suppress-member'      => 'Kontrolletti',

'grouppage-user'          => '{{ns:project}}:Metmaacher',
'grouppage-autoconfirmed' => '{{ns:project}}:Bestätichte Metmaacher',
'grouppage-bot'           => '{{ns:project}}:Bots',
'grouppage-sysop'         => '{{ns:project}}:Wiki Köbes',
'grouppage-bureaucrat'    => '{{ns:project}}:Bürrokrad',
'grouppage-suppress'      => '{{ns:project}}:Kontrolletti',

# Rights
'right-read'                 => 'Sigge lesse',
'right-edit'                 => 'Sigge ändere',
'right-createpage'           => 'Neu Sigge, ävver kein Klaafsigge, aanlääje',
'right-createtalk'           => 'Neu Klaafsigge, ävver kein nomaale Sigge, aanlääje',
'right-createaccount'        => 'Ene neue Metmaacher endraage lohße',
'right-minoredit'            => 'Eije Änderung als klein Mini-Änderung makeere',
'right-move'                 => 'Sigge ömnenne',
'right-suppressredirect'     => 'Kein automatesche Ömleidung aanlääje beim Ömnenne',
'right-upload'               => 'Dateie huhlade',
'right-reupload'             => 'En Datei ußtuusche, di ussem Wiki kütt',
'right-reupload-own'         => 'En selvs huhjelade Datei ußtuusche',
'right-reupload-shared'      => 'En Datei hee em Wiki huhlade, di en Datei ussem zentraale Wiki ersetz, odder „verstich“',
'right-upload_by_url'        => 'Datei vun enne URL ent Wiki huhlade',
'right-purge'                => 'Ohne nohzefroge der Enhalt vum Cache för en Sigg fottschmiiße',
'right-autoconfirmed'        => 'Halfjeschözte Sigge ändere',
'right-bot'                  => 'Als enne automatesche Prozeß oddr a Projramm behandelle',
'right-nominornewtalk'       => 'Klein Mini-Änderunge aan anderlücks Klaafsigge brenge dänne nit „{{int:newmessageslink}}“',
'right-apihighlimits'        => 'Hütere Jrenze em API',
'right-writeapi'             => 'Darf de <tt>writeAPI</tt> bruche',
'right-delete'               => 'Sigge fottschmieße, die nit besönders vill ahle Versione han',
'right-bigdelete'            => 'Sigge fottschmiiße, och wann se ahle Versione ze baasch han',
'right-deleterevision'       => 'Einzel Versione fun Sigge fottschmiiße un zeröck holle',
'right-deletedhistory'       => 'Fottjeschmeße Versione vun Sigge opleßte lohße — dat zeich ävver nit der Tex aan',
'right-browsearchive'        => 'Noh fottjeschmesse Sigge söke',
'right-undelete'             => 'Fottjeschmeße Sigge widder zeröck holle',
'right-suppressrevision'     => 'Versione vun Sigge beloore un zeröck holle, di sujaa för de Wiki-Köbesse verstoche sin',
'right-suppressionlog'       => 'De private Logböcher aanloore',
'right-block'                => 'Medmaacher Sperre, un domet am Schrive hindere',
'right-blockemail'           => 'Metmaacher för et E-Mail Verschecke sperre',
'right-hideuser'             => 'Ene Metmaacher sperre un em singe Name versteiche',
'right-ipblock-exempt'       => 'Es ußjenomme vun automatesche Sperre, vun Sperre fun IP-Adresse, un vun Sperre vun Bereiche vun IP-Adresse',
'right-proxyunbannable'      => 'Es ußjenomme fun automatische Sperre fun Proxy-Servere',
'right-protect'              => 'Sigge schöze, jeschözde Sigge änndere, un der iere Schoz widder ophevve',
'right-editprotected'        => 'Jeschötzte Sigge ändere, ohne Kaskadeschoz',
'right-editinterface'        => 'Sigge met de Texte ändere, die et Wiki kallt',
'right-editusercssjs'        => 'Anderlücks CSS- un JS-Dateie ändere',
'right-rollback'             => 'All de letzte Änderunge fom letzte Metmaacher aan ene Sigg retur maache',
'right-markbotedits'         => 'Retur jemaate Änderonge als Bot-Änderung makeere',
'right-import'               => 'Sigge uß ander Wikis Empochteere',
'right-importupload'         => 'Sigge övver et XML-Datei-Huhlade empochteere',
'right-patrol'               => 'Anderlücks Änderunge aan Sigge als „nohjeloort“ makeere',
'right-autopatrol'           => 'De eije Änderunge automattesch als „Nohjeloohrt“ makeere',
'right-patrolmarks'          => 'De „noch nit Nohjeloohrt“ Zeiche en de „{{int:recentchanges}}“ jezeich krijje',
'right-unwatchedpages'       => 'De Liss med Sigge beloore, die ein keine Oppasliss dren sin',
'right-trackback'            => 'Trackback övvermedelle',
'right-mergehistory'         => 'Ahle Versione vun ongerscheedlijje Sigge zosammedonn',
'right-userrights'           => 'Metmaacher ier Rääschte ändere',
'right-userrights-interwiki' => 'Metmaacher ier Rääschte in ander Wikis ändere',
'right-siteadmin'            => 'De Datebank deeschmaache un opmaache för Änderunge',

# User rights log
'rightslog'      => 'Logboch för Änderunge aan Metmaacher-Räächde',
'rightslogtext'  => 'Hee sin de Änderunge an Metmaacher ehre Räächde opjeliss. Op de Sigge üvver Metmaacher, Wiki_Köbese, Bürrokrade, Stewards, un esu, kanns De nohlese, wat domet es.',
'rightslogentry' => 'hät däm Metmaacher „$1“ sing Räächde vun „$2“ op „$3“ ömjestallt.',
'rightsnone'     => '(nix)',

# Recent changes
'nchanges'                          => '{{PLURAL:$1|Ein Änderung|$1 Änderunge|Kein Änderung}}',
'recentchanges'                     => 'Neuste Änderunge',
'recentchangestext'                 => 'Op dä Sigg hee sin de neuste Änderunge am Wiki opjeliss.',
'rcnote'                            => '{{PLURAL:$1|Hee is de letzte Änderung us|Hee sin de letzte <strong>$1</strong> Änderunge us|Et jit <strong>kei</strong> Änderunge en}} {{PLURAL:$2|däm letzte Dag|de letzte <strong>$2</strong> Dage|dä Zick}} vun $3 aff.',
'rcnotefrom'                        => 'Hee sin bes <strong>$1</strong> fun de Änderunge zick <strong>$2</strong> opjeliss.',
'rclistfrom'                        => 'Zeich de neu Änderunge vum $1 av',
'rcshowhideminor'                   => '$1 klein Mini-Änderunge',
'rcshowhidebots'                    => '$1 de Bots ehr Änderunge',
'rcshowhideliu'                     => '$1 de aanjemeldte Metmaacher ehr Änderunge',
'rcshowhideanons'                   => '$1 de namenlose Metmaacher ehr Änderunge',
'rcshowhidepatr'                    => '$1 de aanjeluurte Änderunge',
'rcshowhidemine'                    => '$1 ming eije Änderunge',
'rclinks'                           => 'Zeich de letzte | $1 | Änderunge us de letzte | $2 | Dage, un dun | $3 |',
'diff'                              => 'Ungerscheed',
'hist'                              => 'Versione',
'hide'                              => 'Usblende:',
'show'                              => 'Zeije:',
'minoreditletter'                   => 'M',
'newpageletter'                     => 'N',
'boteditletter'                     => 'B',
'number_of_watching_users_pageview' => '[{{PLURAL:$1|eine|$1|kein}} Oppasser]',
'rc_categories'                     => 'Nor de Saachjruppe (met „|“ dozwesche):',
'rc_categories_any'                 => 'All, wat mer han',
'rc-change-size'                    => '$1 {{PLURAL:$1|Byte|Bytes}}',
'newsectionsummary'                 => 'Neu Avschnet /* $1 */',

# Recent changes linked
'recentchangeslinked' => 'Verlinkte Änderunge',

# Upload
'upload'                      => 'Daate huhlade',
'uploadbtn'                   => 'Huhlade!',
'reupload'                    => 'Noch ens huhlade',
'reuploaddesc'                => 'Zeröck noh de Sigg zem Huhlade.',
'uploadnologin'               => 'Nit Enjelogg',
'uploadnologintext'           => 'Do mööts ald [[Special:Userlogin|enjelogg]] sin, öm Daate huhzelade.',
'upload_directory_read_only'  => '<b>Doof:</b> En dat Verzeichnis <code>$1</code> för Dateie dren huhzelade, do kann dat Websörver Projramm nix erenschrieve.',
'uploaderror'                 => 'Fähler beim Huhlade',
'uploadtext'                  => "<div dir=\"ltr\">Met däm Formular unge kanns de Belder oder ander Daate huhlade. Do 
kanns dann Ding Werk tirek enbinge, en dä Aate:<ul style=\"list-style:none outside none; 
list-style-position:outside; list-style-image:none; list-style-type:none\"><li style=\"list-style:none outside none; 
list-style-position:outside; list-style-image:none; 
list-style-type:none\"><code>'''[[{{ns:image}}:'''''Beldche'''''.jpg]]'''</code></li><li
style=\"list-style:none outside none; list-style-position:outside; list-style-image:none; 
list-style-type:none\"><code>'''[[{{ns:image}}:'''''Esu süht dat us'''''.png | '''''ene Tex, dä die
Brausere zeije, die kein Belder künne''''']]'''</code></li><li style=\"list-style:none outside none; 
list-style-position:outside; list-style-image:none; 
list-style-type:none\"><code>'''[[{{ns:media}}:'''''Su hürt sich dat aan'''''.ogg]]'''</code></li></ul>
Usführlich met alle Möchlichkeite fings de dat bei de Hölp.
Wann De jetz entschlosse bes, dat De et hee huhlade wells:
* Aanluure, wat mer hee en de {{SITENAME}} ald han, kanns De en uns [[Special:Imagelist|Belder Liss]].
* Wenn De jet söke wells, eets ens nohluure wells, wat ald huhjelade, oder villeich widder fottjeschmesse wood, 
dat steiht em [[Special:Log/upload|Logboch vum Huhlade]].
Esu, un jetz loss jonn:</div>
== <span dir=\"ltr\">Daate en de {{SITENAME}} lade</span> ==",
'upload-permitted'            => 'Nor de Dateitüpe <code>$1</code> sin zojelohße.',
'upload-preferred'            => 'De bevörzochte Zoote Dateie: $1.',
'upload-prohibited'           => 'Verbodde Zoote Dateie: $1.',
'uploadlog'                   => 'LogBoch vum Dateie Huhlade',
'uploadlogpage'               => 'Logboch met de huhjelade Dateie',
'uploadlogpagetext'           => 'Hee sin de Neuste huhjelade Dateie opjeliss un wä dat jedon hät.',
'filename'                    => 'Name vun dä Datei',
'filedesc'                    => 'Beschrievungstex un Zosammefassung',
'fileuploadsummary'           => 'Beschrievungstex un Zosammefassung:',
'filestatus'                  => 'Urhevver Räächsstatus:',
'filesource'                  => 'Quell:',
'uploadedfiles'               => 'Huhjelade Dateie',
'ignorewarning'               => 'Warnung üvverjonn, un Datei trotzdäm avspeichere.',
'ignorewarnings'              => 'Alle Warnunge üvverjonn',
'illegalfilename'             => 'Schad:
<br />
En däm Name vun dä Datei sin Zeiche enthallde,
die mer en Titele vun Sigge nit bruche kann.
<br />
Sök Der statt „$1“ jet anders us,
un dann muss de dat Dinge noch ens huhlade.',
'badfilename'                 => 'De Datei es en „$1“ ömjedäuf.',
'largefileserver'             => 'De Datei es ze jroß. Jrößer wie däm Sörver sing Enstellung erlaub.',
'emptyfile'                   => 'Wat De hee jetz huhjelade häs, hät kein Daate dren jehatt. Künnt sin, dat De Dich 
verdon häs, un dä Name wo verkihrt jeschrevve. Luur ens ov De wirklich <strong>die</strong> Dateie hee 
huhlade wells.',
'fileexists'                  => 'Et jitt ald en Datei met däm Name. Wann De op „Datei avspeichere“ klicks, weed se ersetz. Bes esu jod  un luur Der <strong><tt>$1</tt></strong> aan, wann De nit 100% secher bes.',
'fileexists-forbidden'        => 'Et jitt ald en Datei met däm Name. Jangk zeröck un lad se unger enem andere Name huh. [[{{ns:image}}:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Et jitt ald en Datei met däm Name em jemeinsame Speicher. Jangk zeröck un lad se unger enem andere Name huh. [[{{ns:image}}:$1|thumb|center|$1]]',
'successfulupload'            => 'Et Huhlade hät jeflupp',
'uploadwarning'               => 'Warnung beim Huhlade',
'savefile'                    => 'Datei avspeichere',
'uploadedimage'               => 'hät huhjelade: „[[$1]]“',
'uploaddisabled'              => 'Huhlade jesperrt',
'uploaddisabledtext'          => 'Et Huhlade es jesperrt hee en däm Wiki.',
'uploadscripted'              => 'En dä Datei es HTML dren oder Code vun enem 
Skripp, dä künnt Dinge Brauser en do verkihrte Hals krije un usführe.',
'uploadcorrupt'               => 'Schad.
<br />
De Datei es kapott, hät en verkihrte File Name Extention, oder irjends ene andere Dress es passeet.
<br />
<br />
Luur ens noh dä Datei, un dann muss de et noch ens versöke.',
'uploadvirus'                 => 'Esu ene Dress:
<br />
En dä Datei stich e Kompjutervirus!
<br />
De Einzelheite: $1',
'sourcefilename'              => 'Datei zem huhlade:',
'destfilename'                => 'Unger däm Dateiname avspeichere:',
'watchthisupload'             => 'Op die Datei hee oppasse',
'filewasdeleted'              => 'Unger däm Name wood ald ens en Datei huhjelade. Die es enzwesche ävver widder fottjeschmesse woode. Luur leever eets ens en et $1 ih dat De se dann avspeichere deis.',

'upload-proto-error'     => 'Verkihrt Protokoll',
'upload-file-error'      => 'Fääler em Wiki beim Huhlade',
'upload-file-error-text' => 'Ene internal error es passeet beim Aanläje vun en Datei om Server.  Verzäll et enem system administrator.',
'upload-misc-error'      => 'Dat Huhlaade jing donevve',
'upload-misc-error-text' => 'Dat Huhlaade jing donevve. Mer wesse nit woröm.  Pröf de URL un versök et noch ens.  Wann et nit flupp, verzäll et enem system administrator.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => "Couldn't reach URL",
'upload-curl-error6-text'  => 'The URL provided could not be reached.  Please double-check that the URL is correct and the site is up.',
'upload-curl-error28'      => "Dat Huhlade hät zo lang jedooert, do ha'mer't jestopp",
'upload-curl-error28-text' => 'The site took too long to respond. Please check the site is up, wait a short while and try again. You may want to try at a less busy time.',

'license'            => 'Lizenz:',
'nolicense'          => 'Nix usjesök',
'upload_source_url'  => ' (richtije öffentlije URL)',
'upload_source_file' => ' (en Datei op Dingem Kompjuter)',

# Special:Imagelist
'imagelist-summary'     => "Hee sin de huhjeladene Dateie opjelis. Et eetz wäde de zoletz huhjeladene Dateie aanjezeich. Wa'mer op de Övverschreff von ene Spalt klick, weed die Spalt sotteet, wa'mer norrens klick, weed de Reiejfolg ömjedrieht.",
'imagelist_search_for'  => 'Sök noh däm Name vun däm Beld:',
'imgfile'               => 'Datei',
'imagelist'             => 'Belder, Tön, uew. (all)',
'imagelist_date'        => 'Datum',
'imagelist_name'        => 'Name',
'imagelist_user'        => 'Metmaacher',
'imagelist_size'        => 'Byte',
'imagelist_description' => 'Wat es op däm Beld drop?',

# Image description page
'filehist-deleteall'        => 'All Versione fottschmieße',
'filehist-deleteone'        => 'Schmieß die Version fott',
'filehist-revert'           => 'Zeröck nemme',
'filehist-datetime'         => 'Version vom',
'filehist-user'             => 'Metmaacher',
'imagelinks'                => 'Jebruch en',
'linkstoimage'              => 'Hee {{PLURAL:$1|kütt di Sigg|kumme de Sigge|sin keij Sigge}}, die op die Datei linke dun:',
'nolinkstoimage'            => 'Nix link op hee die Datei.',
'duplicatesoffile'          => 'De Datei{{PLURAL:$1||e|e}} hee noh {{PLURAL:$1|is&x20;|s}}en dubbelte fon he dä Datei, un {{PLURAL:$1|hät|han|}} dersellve Enhalldt:',
'sharedupload'              => 'De Datei es esu parat jelaht, dat se en diverse, ungerscheedlije Projekte jebruch wääde kann.',
'shareduploadwiki'          => 'Mieh Informatione fings De hee: $1.',
'shareduploadwiki-linktext' => 'Hee es en Datei beschrevve',
'noimage'                   => 'Mer han kein Datei met däm Name, kanns De ävver $1.',
'noimage-linktext'          => 'Kanns De huhlade!',
'uploadnewversion-linktext' => 'Dun en neu Version vun dä Datei huhlade',

# File reversion
'filerevert-submit' => 'Zeröcknemme',

# File deletion
'filedelete'                  => 'Schmieß „$1“ fott',
'filedelete-legend'           => 'Schmieß de Datei fott',
'filedelete-comment'          => 'Der Jrund för et Fottschmieße:',
'filedelete-submit'           => 'Fottschmieße',
'filedelete-otherreason'      => 'Ander Jrund oder Zosätzlich:',
'filedelete-reason-otherlist' => 'Ne andere Jrund',
'filedelete-reason-dropdown'  => '* Alljemein Jrönde
** dä Uploader wollt et esu
** wohr jäje et Urhävverrääsch
** Dubbelt',
'filedelete-edit-reasonlist'  => 'De Jrönde för et Fottschmieße beärbeide',

# MIME search
'mimesearch'         => 'Belder, Tön, uew. üvver ehr MIME-Typ söke',
'mimesearch-summary' => 'Op hee dä Sondersigg könne de Dateie noh em MIME-Tüpp ußjesöök wäde.
Mer moß immer der Medietüp un der Ongertüp aanjevve.
Zem Beispell: <tt>image/jpeg</tt>
— kannß donoh op dä Beschrievungssigge von de Belder loore.',
'mimetype'           => 'MIME-Typ:',
'download'           => 'Erungerlade',

# Unwatched pages
'unwatchedpages'         => 'Sigge, wo keiner drop oppass',
'unwatchedpages-summary' => 'Die Sondersigg hee zeich Sigge, die keine Metmaacher op singe Oppassliss ston hät.',

# List redirects
'listredirects'         => 'Ömleitunge',
'listredirects-summary' => 'Die Sondersigg hee liss Ömleidunge op.',

# Unused templates
'unusedtemplates'         => 'Schablone oder Baustein, die nit jebruch wääde',
'unusedtemplates-summary' => "Die Sondersigg hee zeich Schablone aan, die nit en ander Sigge enjebonge sin.
Beß ävver försichtich me'm Fottschmieße, Schablone könne och jenoz un jebruch wääde, ohne se diräk enzebenge.",
'unusedtemplatestext'     => 'Hee sin all de Schablone opjeliss, die em Appachtemeng „Schablon“ sin, die nit en 
ander Sigge enjefüg wääde. Ih De jet dovun fottschmieß, denk dran, se künnte och op en ander Aat jebruch 
wääde, un luur Der die ander Links aan!',
'unusedtemplateswlh'      => 'ander Links',

# Random page
'randompage'         => 'Zofällije Sigg',
'randompage-nopages' => 'En däm Appachtemang hee sin ja kein Sigge dren.',

# Random redirect
'randomredirect'         => 'Zofällije Ömleitung',
'randomredirect-nopages' => 'En däm Appachtemang hee sin ja kein Ömleidunge dren.',

# Statistics
'statistics'             => 'Statistike',
'sitestats'              => 'Statistike üvver de {{SITENAME}}',
'userstats'              => 'Statistike üvver de Metmaacher',
'sitestatstext'          => '* Et jitt en etwa <strong>$2</strong> richtije Atikkele hee.
* En de Daatebank sinner ävver <strong>$1</strong> Sigge, aan dänne bes jetz zosamme <strong>$4</strong> Mol jet jeändert woode es. Em Schnedd woodte alsu <strong>$5</strong> Änderunge pro Sigg jemaht. <br /><small> (Do sin ävver de Klaafsigge metjezallt, de Sigge üvver de {{SITENAME}}, un usserdäm jede kleine Futz un Stümpchenssigg, Ömleitunge, Schablone, Saachjruppe, un ander Zeuch, wat mer nit jod als ene Atikkel zälle kann)</small>

* <strong>$8</strong> Belder, Tön, un esun ähnlije Daate woodte ald huhjelade.

* Et {{PLURAL:$7|es noch <strong>ein</strong> Opjav|sin noch <strong>$7</strong> Opjave|es <strong>kein</strong> Opjav mieh}} en de Liss.
{{PLURAL:$3|
* <strong>Ein</strong> mol wood en Sigg hee avjerofe, dat sin <strong>$6</strong> Avrofe pro Sigg.|
* <strong>$3</strong> mol wood en Sigg hee avjerofe, dat sin <strong>$6</strong> Avrofe pro Sigg.|<!-- -->}}',
'userstatstext'          => '* {{PLURAL:$1|<strong>Eine</strong> Metmaacher hät|<strong>$1</strong> Metmaacher han|<strong>Keine</strong> Metmaacher hät}} sich bes jetz aanjemeldt.
* {{PLURAL:$2|<strong>Eine</strong> dovun es|<strong>$2</strong> dovun sin|<strong>Keine</strong> es}} $5, dat {{PLURAL:$4|es|sinner|sinner}} <strong>$4%</strong>.',
'statistics-mostpopular' => 'De miets beluurte Sigge',

'disambiguations'     => '„(Wat es dat?)“-Sigge',
'disambiguationspage' => 'Template:Disambig',

'doubleredirects'         => 'Ömleitunge op Ömleitunge (Dubbel Ömleitunge sin verkihrt)',
'doubleredirects-summary' => 'Die Sondersigg hee zeich Ömleitunge op Ömleitungssigge. Domet kann de Wiki-Sofwäer nit ömjon. Die sollte alsu opjerühmp weede. Jede Reih he zeich de eetste un zweite Ömleidung, un et Ziel fun de zweite Ömleidung, wat för jewöhnlech och et Ziel vun de eetste Ömleidung sin sullt.',
'doubleredirectstext'     => 'Dubbel Ömleitunge sin immer verkihrt, weil däm Wiki sing Soffwär de eetse Ömleitung 
folg, de zweite Ömleitung ävver dann aanzeije deit - un dat well mer jo normal nit han.
Hee fings De en jede Reih ene Link op de iertste un de zweite Ömleitung, donoh ene Link op de Sigg, wo de 
zweite Ömleitung hin jeiht. För jewöhnlich es dat dann och de richtije Sigg, wo de iertste Ömleitung ald hin 
jonn sollt.
Met däm „(Ändere)“-link kanns De de eetste Sigg tirek aanpacke. Tipp: Merk Der dat Lemma - de Üvverschreff - 
vun dä Sigg dovör.',

'brokenredirects'         => 'Ömleitunge, die en et Leere jonn (kapott oder op Vörrod aanjelaht)',
'brokenredirects-summary' => 'Die Sondersigg hee zeich Ömleitunge op Sigge, die et (noch) nit jit. Die könne op Vüraat aanjelaat sin, odder sin verkihrt.',
'brokenredirectstext'     => "Die Ömleitunge hee jonn op Sigge, die mer
[[#ast|<small>noch'''<sup>*</sup>?'''</small>]]
jar nit han.
<small id=\"ast\">'''<sup>*</sup>?''' Die künnte op Vörrod aanjelaht sin.
Die alsu jod ussinn,
un wo die Sigge wo se drop zeije,
späder wall noch kumme wääde,
die sollt mer behalde.</small>",
'brokenredirects-delete'  => '(fottschmieße)',

'withoutinterwiki'         => 'Atikele ohne Links op annder Shprooche',
'withoutinterwiki-summary' => 'He sin Sigge jeliß, di nit op annder Shprooche jelingk sin.',
'withoutinterwiki-submit'  => 'Zeije',

'fewestrevisions'         => 'Sigge met de winnischste Versione',
'fewestrevisions-summary' => 'Hee sin Atikele opjelis, die et winnigs beärbeit wode sin.',

# Miscellaneous special pages
'nbytes'                          => '$1 Byte{{PLURAL:$1||s|}}',
'ncategories'                     => '{{PLURAL:$1| ein Saachjrupp | $1 Saachjruppe | keij Saachjruppe }}',
'nlinks'                          => '{{PLURAL:$1|eine Link|$1 Links}}',
'nmembers'                        => 'met {{PLURAL:$1|ein Sigg|$1 Sigge}} dren',
'nrevisions'                      => '{{PLURAL:$1|Ein Änderung|$1 Änderunge|Keij Änderung}}',
'nviews'                          => '{{PLURAL:$1|Eine Avrof|$1 Avrofe|Keine Avrof}}',
'lonelypages'                     => 'Sigge, wo nix drop link',
'lonelypages-summary'             => 'Die Sondersigg hee zeich Atikkele op die kein Links uss em Tex vun ander Sigge jon.',
'lonelypagestext'                 => 'The following pages are not linked from other pages in this wiki.',
'uncategorizedpages'              => 'Sigge, die en kein Saachjrupp sin',
'uncategorizedpages-summary'      => 'Hee sin Sigge jeliss, di en kein Saachjrup dren sin.',
'uncategorizedcategories'         => 'Saachjruppe, die selvs en kein Saachjruppe sin',
'uncategorizedcategories-summary' => 'Hee sin Saachjruppe jezeig, di sellver in kein Saachjrup dren sin.',
'uncategorizedimages'             => 'Belder, Tön, uew., die en kein Saachjruppe dren sin',
'uncategorizedimages-summary'     => 'Die Sondersigg hee zeich Dateie, die in kein Saachjruppe enzoteet sin.',
'uncategorizedtemplates'          => 'Schablone, die en kein Saachjruppe sen',
'uncategorizedtemplates-summary'  => 'Hee sin Schablone jezeig, die en kein Saachrup dren sin.',
'unusedcategories'                => 'Saachjruppe met nix dren',
'unusedimages'                    => 'Belder, Tön, uew., die nit en Sigge dren stäche',
'popularpages'                    => 'Sigge, die off avjerofe wääde',
'popularpages-summary'            => 'Hee sin di Sigge opjeliss, di et miehts avjeroofe wäde.',
'wantedcategories'                => 'Saachjruppe, die mer noch nit han, die noch jebruch wääde',
'wantedcategories-summary'        => 'He sin Saachjruppe opjeliss, die zwa en Sigge aanjejove sin un villeich och jebruch wäde, ävver noch nit aanjelaat sin.',
'wantedpages'                     => 'Sigge, die mer noch nit han, die noch jebruch wääde',
'wantedpages-summary'             => 'He sin Sigge opjeliss, die et noch nit jitt, wo ävver Hyperlinks vun andere Sigge drop jon.',
'missingfiles-summary'            => 'He sin de Name (Titelle) fun Dateie opjeliss, di en ander Sigge aanjesproche weede, wo di Dateie sellver ävver fähle.',
'mostlinked'                      => 'Atikele met de miehste Links drop',
'mostlinked-summary'              => 'He kumme för alle Appachtemangs de Sigge, wo de miihßte Lingks drop zëije.',
'mostlinkedcategories'            => 'Saachjruppe met de miehste Links drop',
'mostlinkedcategories-summary'    => 'Die Sondersigg hee zeich Saachjruppe, un zwa de am miehtß jebruchte et eets.',
'mostlinkedtemplates'             => 'Schablone met de miehßte Lenks drop',
'mostlinkedtemplates-summary'     => 'Hee kumme de am miißte jebruchte Schabloone.',
'mostcategories'                  => 'Atikkele met de miehste Saachjruppe',
'mostcategories-summary'          => 'He kumme Sigge, di en besönders ville Saachjruppe dren sin.',
'mostimages'                      => 'Belder, Tön, uew. met de miehste Links drop',
'mostimages-summary'              => 'Die Sondersigg hee zeich Dateie, un zwa de am miehtß jebruchte et eets.',
'mostrevisions'                   => 'Atikkele met de miehste Änderunge',
'mostrevisions-summary'           => 'Hee kumme de am miihtßte beärrbëijte un jeännderte Sigge.',
'prefixindex'                     => 'All Sigge, die dänne ehr Name met enem bestemmte Wood oder Tex aanfange deit',
'prefixindex-summary'             => 'Die Sondersigg hee zeich all di Sigge, dä ier Titele met dä aanjejovve Bochstabe oder Zeiche aanfange.
Die Liss kann op ein Appachtemang bejrenz wäde.',
'shortpages'                      => 'Sigge zoteet vun koot noh lang',
'shortpages-summary'              => 'Die Sondersigg hee zeich Atikkele, un zwa de koote et eets. Wie lang ene Atikel es, weed am Wikikood in Byte ußjezallt — also de Zeiche, di mer beim „{{int:Edit}}“ un „{{int:viewsource}}“ op der Belddscherrem krit. Opjepaß! Ömluute un esu brenge zwei Byte pro Zeiche, de miehßte andere nor ein.',
'longpages'                       => 'Sigge zoteet vun lang noh koot',
'longpages-summary'               => 'Die Sondersigg hee zeich Atikkele, un zwa de lange et eets. Wie lang ene Atikel es, weed am Wikikood in Byte ußjezallt — also de Zeiche, di mer beim „{{int:Edit}}“ un „{{int:viewsource}}“ op der Belddscherrem krit. Opjepaß! Ömluute un esu brenge zwei Byte pro Zeiche, de miehßte andere nor ein.',
'deadendpages'                    => 'Sigge ohne Links dren',
'deadendpages-summary'            => 'Die Sondersigg hee zeich en Liss met Sigge, en dänne kein Links op Sigge dren sin, die et ald jitt.',
'deadendpagestext'                => 'De Atikelle hee han kein Links op ander Atikelle em Wiki.',
'protectedpages'                  => 'Jeschötzte Sigge',
'protectedpages-summary'          => 'He sin Sigge opjeliss, wo jäje Veränderunge un/odde jäje et Ömnenne jeschötz sin, su dat nit einfach Jede jet draan don kann.',
'protectedpagestext'              => '<!-- -->',
'protectedtitles'                 => 'Verbodde Titele för Sigge',
'protectedtitles-summary'         => "Hee sin verbodde Sigge-Tittele opjelis, unger dä Name ka'mer kein neu Sigge aanläje.",
'protectedtitlestext'             => 'Sigge met hee dä Tittele lohße mer nit zo, un di künne dröm nit aanjelääsch wäde:',
'listusers'                       => 'Metmaacherliss',
'listusers-summary'               => "Die Sondersigg hee zeich aanjemelldte Metmaacher. En de [[Special:Statistics|Sondersigg met de Statistike]] ka'mer dozo och Zahle nohloore.",
'newpages'                        => 'Neu Sigge',
'newpages-summary'                => "Die Sondersigg hee zeich de neu aanjelaate Sigge uß de letzte Zigg.
De Liss kam'mer op eij Appachtemang odder op einem Metmaacher sing Beidrääch bejrenz wääde.",
'newpages-username'               => 'Metmaacher Name:',
'ancientpages'                    => 'Atikele zoteet vun Ahl noh Neu',
'ancientpages-summary'            => 'Die Sondersigg zeich en Liss med Sigge, die et längs nit mieh jeändert wode sin.',
'move'                            => 'Ömnenne',
'movethispage'                    => 'De Sigg ömnenne',
'unusedimagestext'                => '<p><strong>Opjepass:</strong> Ander Websigge künnte immer noch de Dateie hee tirek 
per URL aanspreche. Su künnt et sin, dat en 
Datei hee en de Liss steiht, ävver doch jebruch weed. Usserdäm, winnichstens bei neue Dateie, künnt sin, 
dat se noch nit en enem Atikkel enjebaut sin, weil noch Einer dran am brasselle es.</p>',
'unusedcategoriestext'            => 'De Saachjruppe hee sin enjerich, ävver jetz em Momang, es keine Atikkel un 
kein Saachjrupp dren ze finge.',
'notargettitle'                   => 'Keine Bezoch op e Ziel',
'notargettext'                    => 'Et fählt ene Metmaacher oder en Sigg, wo mer jet zo erusfinge oder oplisste solle.',
'nopagetitle'                     => "Esu en Sigg ham'mer nit",
'nopagetext'                      => 'Do häss en Sigg aanjovve, di jidd et jaa nit.',

# Book sources
'booksources'               => 'Böcher',
'booksources-summary'       => 'Hee kanns De en ISBN enjevve un kriss dann en Less met Kattaloge un Quelle, wo De dat Booch krijje kanns.

Zwesche de Zeffere sen Zweschrühm un Streche erlaub, ävver nit nüdig.',
'booksources-search-legend' => 'Söök noh Bezochsquelle för Bööcher',
'booksources-go'            => 'Loß Jonn!',
'booksources-text'          => 'Hee noh küdd_en Leßß met Websigge,
wo mir fun de {{SITENAME}} nix wigger med ze donn hänn,
wo mer jät övver Böösher erfaare
un zom Dëijl och Böösher koufe kann.
Doför moßß De Desh mannshmool allodengs eetß ennß aanmällde,
wat Koßte un Jefaare met sesh brenge künndt.
Wo_t jëijdt,
jonn di Lengkß hee tirrägg_op dat Booch,
wadd_Er am Sööke sidt.',

# Special:Log
'specialloguserlabel'  => 'Metmaacher:',
'speciallogtitlelabel' => ' Siggename:',
'log'                  => 'Logböcher ehr Opzeichnunge (all)',
'all-logs-page'        => 'All Logböcher',
'log-search-submit'    => 'Loß Jonn!',
'alllogstext'          => "Dat hee es en jesamte Liss us all dä Logböcher för et [[Special:Log/block|Metmaacher 
oder IP Adress Sperre]], et [[Special:Log/protect|Sigge Sperre]], [[Special:Log/delete|et Sigge Fottschmieße]], et 
[[Special:Log/move|Sigge Ömnenne]], et [[Special:Log/renameuser|Metmaacher Ömnenne]], oder 
[[Special:Log/newusers|neue Metmaacher ehr Aanmeldunge]], et [[Special:Log/upload|Daate Huhlade]], 
[[Special:Log/rights|de Bürrokrade ehre Krom]], un de [[Special:Log/makebot|Bots ehr Status Änderunge]].
Dä Logböcher ehre Enhald ka'mer all noh de Aat, de Metmaacher, oder de Sigge ehr Name, un esu, einzel zoteet 
aanluure.",
'logempty'             => '<i>Mer han kein passende Endräch en däm Logboch.</i>',

# Special:Allpages
'allpages'          => 'All Sigge',
'allpages-summary'  => "Die Sondersigg hee zeich der Siggebestand en etwa noh'm Alphabett, ävver de Zahle stonn am Aanfang för der Jroßbochstabe, dann de Kleine Bochstave, un de jruße un kleine Ömluute un de Sönderzeiche stonn am Eng.",
'alphaindexline'    => '$1 … $2',
'nextpage'          => 'De nächste Sigg: „$1“',
'allpagesfrom'      => 'Sigge aanzeije av däm Name:',
'allarticles'       => 'All Atikkele',
'allinnamespace'    => 'All Sigge (Em Appachtemeng „$1“)',
'allnotinnamespace' => 'All Sigge (usser em Appachtemeng „$1“)',
'allpagesprev'      => 'Zeröck',
'allpagesnext'      => 'Nächste',
'allpagessubmit'    => 'Loss Jonn!',
'allpagesprefix'    => 'Sigge zeije, wo dä Name aanfängk met:',
'allpagesbadtitle'  => 'Dä Siggename es nit ze jebruche. Dä hät e Köözel för en Sproch oder för ne Interwiki Link am Aanfang, oder et kütt e Zeiche dren för, wat en Siggename nit jeiht, villeich och mieh wie 
eins vun all däm op eimol.',
'allpages-bad-ns'   => "Dat Appachtemeng „$1“ ha'mer nit.",

# Special:Categories
'categories'                    => 'Saachjruppe',
'categoriespagetext'            => 'Hee sin nur Saachjruppe met jät dren jeliss,
* De nit anjelaate Saachjruppe met jät dren fingkd_Er norr_ens för sijj_allein op: [[{{#special:WantedCategories}}|{{int:wantedcategories}}]]
* De aanjelaate Saachruppe met nix dren fingkd_Er nur op: [[{{#special:UnusedCategories}}|{{int:unusedcategories}}]].


En hee dä Saachjrup{{PLURAL:$1||pe|}} en de {{SITENAME}} sin Saachjruppe, Sigge, Bellder, Tön uew. dren:',
'special-categories-sort-count' => 'Zoteere noh de Aanzahl',
'special-categories-sort-abc'   => 'Zoteere nohm Alphabett',

# Special:Listusers
'listusersfrom'      => 'Zeich de Metmaacher vun:',
'listusers-submit'   => 'Zeije',
'listusers-noresult' => 'Keine Metmaacher jefonge.',

# Special:Listgrouprights
'listgrouprights'          => 'Metmaacher-Jruppe-Rääschte',
'listgrouprights-summary'  => 'Hee kütt de Liss met dä Medmaacher-Jruppe, di dat Wiki hee kennt, un denne ier Rääschte.
Mieh övver de einzel Rääschte fenkt Er op de [[{{MediaWiki:Listgrouprights-helppage}}|Hölp-Sigg övver de Medmaacher ier Rääschte]].',
'listgrouprights-group'    => 'Jrupp',
'listgrouprights-rights'   => 'Räächte',
'listgrouprights-helppage' => 'Help:Jrupperäächte',
'listgrouprights-members'  => '(Metjlederliss)',

# E-mail user
'mailnologin'     => 'Keij E-Mail Adress',
'mailnologintext' => 'Do mööts ald aanjemeldt un [[Special:Userlogin|enjelogg]] sin, un en jode E-Mail 
Adress en Dinge [[Special:Preferences|ming Enstellunge]] stonn han, öm en E-Mail aan andere Metmaacher ze 
schecke.',
'emailuser'       => 'E-mail aan dä Metmaacher',
'emailpage'       => 'E-mail aan ene Metmaacher',
'emailpagetext'   => 'Wann dä Metmaacher en E-mail Adress aanjejovve hätt en singe Enstellunge, un die 
deit et och, dann kanns De met däm Fomular hee unge, en einzelne E-Mail aan dä Metmaacher schecke. Ding E-mail 
Adress, die De en Ding eije Enstellunge aanjejovve häs, die weed als de Avsender Adress en die E-Mail 
enjedrage. Domet kann, wä die E-Mail kritt, drop antwoode, un die Antwood jeiht tirek aan Dich.
Alles klor?',
'usermailererror' => 'Dat E-Mail-Objek jov ene Fähler us:',
'defemailsubject' => 'E-Mail üvver de {{SITENAME}}.',
'noemailtitle'    => 'Kein E-Mail Adress',
'noemailtext'     => 'Dä Metmaacher hät kein E-Mail Adress enjedrage, oder hä well kein E-Mail krije.',
'emailfrom'       => 'Vun',
'emailto'         => 'Aan',
'emailsubject'    => 'Üvver',
'emailmessage'    => 'Dä Tex',
'emailsend'       => 'Avschecke',
'emailccme'       => 'Scheck mer en Kopie vun dä E-Mail.',
'emailccsubject'  => 'En Kopie vun Dinger E-Mail aan $1: $2',
'emailsent'       => 'E-Mail es ungerwähs',
'emailsenttext'   => 'Ding E-Mail es jetz lossjescheck woode.',

# Watchlist
'watchlist'            => 'ming Oppassliss',
'mywatchlist'          => 'ming Oppassliss',
'watchlistfor'         => '(för <strong>$1</strong>)',
'nowatchlist'          => 'En Ding Oppassliss es nix dren.',
'watchlistanontext'    => 'Do muss $1, domet de en Ding Oppassliss erenluure kanns, oder jet dran ändere.',
'watchnologin'         => 'Nit enjelogg',
'watchnologintext'     => 'Öm Ding Oppassliss ze ändere, mööts de ald [[Special:Userlogin|enjelogg]] sin.',
'addedwatch'           => 'En de Oppassliss jedon',
'addedwatchtext'       => 'Die Sigg „[[$1]]“ es jetz en Ding [[Special:Watchlist|Oppassliss]]. Av jetz, wann die Sigg 
verändert weed, oder ehr Klaafsigg, dann weed dat en de Oppassliss jezeich. Dä Endrach för die Sigg kütt en 
Fettschreff en de „[[Special:Recentchanges|Neuste Änderunge]]“, domet De dä do och flöck fings.
Wann de dä widder loss wääde wells us Dinger Oppassliss, dann klick op „Nimieh drop oppasse“ wann De die Sigg om 
Schirm häs.',
'removedwatch'         => 'Us de Oppassliss jenomme',
'removedwatchtext'     => 'Die Sigg „[[$1]]“ es jetz us de Oppassliss erusjenomme.',
'watch'                => 'Drop Oppasse',
'watchthispage'        => 'Op die Sigg oppasse',
'unwatch'              => 'Nimieh drop Oppasse',
'unwatchthispage'      => 'Nimieh op die Sigg oppasse',
'notanarticle'         => 'Keine Atikkel',
'notvisiblerev'        => 'Di Version es fottjeschmesse',
'watchnochange'        => 'Keine Atikkel en Dinger Oppassliss es en dä aanjezeichte Zick verändert woode.',
'watchlist-details'    => 'Do häs {{PLURAL:$1|<strong>ein<strong> Sigg|<strong>$1</strong> Sigge|<strong>kein</strong> Sigg}} en dä Oppassliss{{PLURAL:$1|, un di Klaafsigg dozo|, un de Klaafsigge dozo|}}.',
'wlheader-enotif'      => '* Et E-mail Schecke es enjeschalt.',
'wlheader-showupdated' => '* Wann se Einer jeändert hätt, zickdäm De se et letzte Mol aanjeluurt häs, sin die Sigge <strong>extra markeet</strong>.',
'watchmethod-recent'   => 'Ben de letzte Änderunge jäje de Oppassliss am pröfe',
'watchmethod-list'     => 'Ben de Oppassliss am pröfe, noh de letzte Änderung',
'watchlistcontains'    => 'En dä Oppassliss {{PLURAL:$1|es ein Sigg|sinner <strong>$1</strong> Sigge|sinner <strong>kein</strong> Sigge}}.',
'iteminvalidname'      => 'Dä Endrach „$1“ hät ene kapodde Name.',
'wlnote'               => '{{PLURAL:$1|Hee es de letzte Änderung us|Hee sin de letzte <strong>$1</strong> Änderunge us|Mer han kein Äbderunge en}} de letzte {{PLURAL:$2|Stund|<strong>$2</strong> Stunde|<strong>noll</strong> Stunde}}.',
'wlshowlast'           => 'Zeich de letzte | $1 | Stunde | $2 | Dage | $3 | aan, dun',
'watchlist-show-bots'  => 'de Bots ier Änderunge zeije',
'watchlist-hide-bots'  => 'de Bots ier Änderunge fottlohße',
'watchlist-show-own'   => 'de eije Änderunge zeije',
'watchlist-hide-own'   => 'de eije Änderunge fottlohße',
'watchlist-show-minor' => 'klein Mini-Änderunge zeije',
'watchlist-hide-minor' => 'klein Mini-Änderunge fottlohße',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Drop oppasse…',
'unwatching' => 'Nimmieh drop oppasse',

'enotif_mailer'      => 'Dä {{SITENAME}} Nachrichte Versand',
'enotif_reset'       => 'Setz all Änderunge op „Aanjeluurt“ un Erledich.',
'enotif_newpagetext' => 'Dat es en neu aanjelahte Sigg.',
'changed'            => 'jeändert',
'created'            => 'neu aanjelaht',
'enotif_subject'     => '{{SITENAME}}: Sigg "$PAGETITLE" vun "$PAGEEDITOR" $CHANGEDORCREATED.',
'enotif_lastvisited' => 'Luur unger „$1“ - do fings de all die Änderunge zick Dingem letzte Besoch hee.',
'enotif_body'        => 'Leeven $WATCHINGUSERNAME,
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
'deletepage'                  => 'Schmieß die Sigg jetz fott',
'confirm'                     => 'Dä Schotz för die Sigg ändere',
'excontent'                   => 'drop stundt: „$1“',
'excontentauthor'             => 'drop stundt: „$1“ un dä einzije Schriever woh: „$2“',
'exbeforeblank'               => 'drop stundt vörher: „$1“',
'exblank'                     => 'drop stundt nix',
'delete-legend'               => 'Fottschmieße',
'historywarning'              => '<strong>Opjepass:</strong> Die Sigg hät ene janze Püngel Versione',
'confirmdeletetext'           => 'Do bes koot dovör, en Sigg för iwich fottzeschmieße. Dobei verschwind och de janze Verjangenheit vun dä Sigg us de Daatebank, met all ehr Änderunge un Metmaacher Name, un all dä Opwand, dä do dren stich. Do muss hee jetz bestätije, dat de versteihs, wat dat bedügg, un dat De weiß, wat Do do mähs.
<strong>Dun et nor, wann dat met de [[{{MediaWiki:Policy-url}}|Rejelle]] wirklich zosamme jeiht!</strong>',
'actioncomplete'              => 'Erledich',
'deletedtext'                 => 'De Sigg „<nowiki>$1</nowiki>“ es jetz fottjeschmesse woode. Luur Der „$2“ aan, do häs De en Liss met de Neuste fottjeschmesse Sigge.',
'deletedarticle'              => 'hät fottjeschmesse: „[[$1]]“',
'dellogpage'                  => 'Logboch met de fottjeschmesse Sigge',
'dellogpagetext'              => 'Hee sin de Sigge oppjeliss, die et neus fottjeschmesse woodte.',
'deletionlog'                 => 'Dat Logboch fum Sigge-Fottschmieße',
'reverted'                    => 'Han de ählere Version vun dä Sigg zoröck jehollt.',
'deletecomment'               => 'Aanlass för et Fottschmieße',
'deleteotherreason'           => 'Ander Jrund oder Zosätzlich:',
'deletereasonotherlist'       => 'Ander Jrund',
'deletereason-dropdown'       => '* Alljemein Jrönde
** dä Schriever wollt et esu
** wohr jäje et Urhävverrääsch
** et wohd jet kapott jemaat
** et wohr bloß Keu
** mem Name verdonn bemm Aanläje',
'delete-edit-reasonlist'      => 'De Jrönde för et Fottschmieße beärbeide',
'rollback'                    => 'Änderunge Zeröcknemme',
'rollback_short'              => 'Zeröcknemme',
'rollbacklink'                => 'Zeröcknemme',
'rollbackfailed'              => 'Dat Zeröcknemme jingk scheiv',
'cantrollback'                => 'De letzte Änderung zeröckzenemme es nit möchlich. Dä letzte Schriever es dä einzije, dä aan dä Sigg hee jet jedon hät!',
'alreadyrolled'               => '<strong>Dat wor nix!</strong>
Mer künne de letzte Änderunge vun dä Sigg „[[$1]]“ vum Metmaacher „[[User:$2|$2]]“ ([[User talk:$2|däm sing Klaafsigg]], un [[Special:Contributoins/$2|Beidrääsch]]) nimieh zeröcknemme, dat hät ene Andere enzwesche ald jedon.
De Neuste letzte Änderung es jetz vun däm Metmaacher „[[User:$3|$3]]“ ([[User talk:$3|däm sing Klaafsigg]], un [[Special:Contributoins/$3|Beidrääsch]]).',
'editcomment'                 => 'Bei dä Änderung stundt: „<i>$1</i>“.', # only shown if there is an edit comment
'revertpage'                  => 'Änderunge (R$5, vom $6) vun däm Metmaacher „[[User:$2|$2]]“ ([[User talk:$2|däm sing Klaafsigg]]) fottjeschmesse, un doför de letzte Version (R$3, vom $4) vum „[[User:$1|$1]]“ widder zeröckjehollt', # Additional available: $3: revid of the revision reverted to, $4: timestamp of the revision reverted to, $5: revid of the revision reverted from, $6: timestamp of the revision reverted from
'sessionfailure'              => "Et jov wall e technisch Problem met Dingem Login. Dröm ha'mer dat us Vörsich jetz nit jemaht, domet mer nit villeich Ding Änderung däm verkihrte Metmaacher ungerjubele. Jangk zeröck un versök et noch ens.",
'protectlogpage'              => 'Logboch vum Sigge Schötze',
'protectlogtext'              => 'Hee es de Liss vun de Sigge, die jeschötz oder frei jejovve woode sin.',
'protectedarticle'            => 'hät de Sigg „[[$1]]“ jeschötz',
'unprotectedarticle'          => 'Schotz för „[[$1]]“ opjehovve',
'protect-title'               => 'Sigge Schotz för „$1“ ändere',
'protect-legend'              => 'Sigg schötze',
'protectcomment'              => 'Dä Jrund oder Aanlass för et Schötze',
'protect-unchain'             => 'Et Schötze jäje Ömnenne extra enstelle looße',
'protect-text'                => 'Hee kanns De dä Schotz jäje Veränderunge för de Sigg „<nowiki>$1</nowiki>“ aanluure un ändere.
Bes esu jod un hald Dich aan de [[{{MediaWiki:policy-url}}|Rejelle]] för esu Fäll!
Em <span class="plainlinks">[{{FULLURL:Special:Log/protect|page={{FULLPAGENAMEE}}}} Logboch]</span> fings De ählere Änderunge vun däm Schotz, wann et se jitt.',
'protect-default'             => '-(Standaad)-',
'protect-level-autoconfirmed' => 'nor Metmaacher dranlooße, die sich aanjemeldt han',
'protect-level-sysop'         => 'Nor de Wiki Köbese dranlooße',
'protect-cantedit'            => 'Do kanns dä Siggeschotz hee nit ändere, esu lang wie De di Sigg nit ändere darfs.',
'pagesize'                    => '(Bytes)',

# Restrictions (nouns)
'restriction-edit'   => 'Et Ändere',
'restriction-move'   => 'Et Ömnenne',
'restriction-create' => 'Aanläje',
'restriction-upload' => 'Huhlade',

# Undelete
'undelete'                 => 'Fottjeschmessene Krom aanluure odder zeröck holle',
'undeletepage'             => 'Fottjeschmesse Sigge aanluure un widder zeröckholle',
'viewdeletedpage'          => 'Fottjeschmesse Sigge aanzeije',
'undeletepagetext'         => 'De Sigge heenoh sin fottjeschmesse, mer künne se ävver immer noch usem Müllemmer eruskrose.',
'undeleteextrahelp'        => 'Öm de janze Sigg met all ehre Versione widder ze holle, looß all de Versione ohne Hökche, un klick op „<b style="padding:2px; background-color:#ddd; color:black">Zeröckholle!</b>“.
Öm bloß einzel Versione zeröckzeholle, maach Hökche aan die Versione, die De widder han wells, un dann dun „<b style="padding:2px; background-color:#ddd; color:black">Zeröckholle!</b>“ klicke.
Op „<b style="padding:2px; background-color:#ddd; color:black">De Felder usleere</b>“
klick, wann De all Ding Hökche un Ding „Erklärung (för en et Logboch):“ widder fott han wells.',
'undeleterevisions'        => '{{PLURAL:$1|Ein Version|<strong>$1</strong> Versione|<strong>Kein</strong> Version}} en et Archiv jedon',
'undeletehistory'          => 'Wann De die Sigg widder zeröckholls,
dann kriss De all fottjeschmesse Versione widder.
Wann enzwesche en neu Sigg unger däm ahle Name enjerich woode es,
dann wääde de zeröckjehollte Versione einfach als zosätzlije äldere Versione för die neu Sigg enjerich.
Die neu Sigg weed nit ersetz.',
'undeletehistorynoadmin'   => 'Die Sigg es fottjeschmesse woode. Dä Jrund döför es en de Liss unge ze finge, jenau esu wie de Metmaacher, wo de Sigg verändert han, ih dat se fottjeschmesse wood. Wat op dä Sigg ehre fottjeschmesse ahle Versione stundt, dat künne nor de Wiki Köbese noch aansinn (un och widder zeröckholle)',
'undeleterevision-missing' => 'De Version stemmp nit. Dat wor ene verkihrte Link, oder de Version wood usem Archiv zeröck jehollt, oder fottjeschmesse.',
'undeletebtn'              => 'Zeröckholle!',
'undeletelink'             => 'widder zeröckholle',
'undeletereset'            => 'De Felder usleere',
'undeletecomment'          => 'Erklärung (för en et Logboch):',
'undeletedarticle'         => '„$1“ zeröckjehollt',
'undeletedrevisions'       => '{{PLURAL:$1|ein Version|$1 Versione}} zeröckjehollt',
'undeletedrevisions-files' => 'Zesammejenomme {{PLURAL:$1|Ein Version|<strong>$1</strong> Versione|<strong>Kein</strong> Version}} vun {{PLURAL:$2|eine Datei|<strong>$2</strong> Dateie|<strong>nix</strong>}} zeröckjehollt',
'undeletedfiles'           => '{{PLURAL:$1|Ein Datei|<strong>$1</strong> Dateie|<strong>Kein</strong> Dateie}} zeröckjehollt',
'cannotundelete'           => '<strong>Dä.</strong> Dat Zeröckholle jing donevve. Möchlich, dat ene andere Metmaacher flöcker wor, un et ald et eets jedon hät, un jetz es die Sigg ald widder do jewäse.',
'undeletedpage'            => '<big><strong>De Sigg „$1“ es jetz widder do</strong></big>
Luur Der et [[Special:Log/delete|Logboch met de fottjeschmesse Sigge]] aan, do häs De de Neuste fottjeschmesse 
un widder herjehollte Sigge.',
'undelete-search-submit'   => 'Sööke',
'undelete-cleanup-error'   => 'Fähler beim Fottschmieße vun de Archiv-Version „$1“, die nit jebruch wood.',

# Namespace form on various pages
'namespace'      => 'Appachtemeng:',
'invert'         => 'dun de Uswahl ömdrije',
'blanknamespace' => '(Atikkele)',

# Contributions
'contributions' => 'Däm Metmaacher sing Beidräch',
'mycontris'     => 'ming Beidräch',
'contribsub2'   => 'För dä Metmaacher: $1 ($2)',
'nocontribs'    => 'Mer han kein Änderunge jefonge, en de Logböcher, die do passe däte.',
'uctop'         => ' (Neuste)',
'month'         => 'un Moohnt:',
'year'          => 'Beß Johr:',

'sp-contributions-newbies'     => 'Nor neu Metmaacher ier Beidräg zeije',
'sp-contributions-newbies-sub' => 'För neu Metmaacher',
'sp-contributions-search'      => 'Söök noh Metmaacher ier Beidräg',
'sp-contributions-username'    => 'Metmaachername odder IP-Address:',
'sp-contributions-submit'      => 'Sööke',

# What links here
'whatlinkshere'         => 'Wat noh hee link',
'whatlinkshere-title'   => 'Sigge, woh Links op „$1“ dren sen',
'whatlinkshere-summary' => 'Die Sondersigg zeich Links innerhallef vom Wiki op en bestemmpte Sigg aa.
En Klammere shteit, op wat för en Aat un Wies dä Link jemat woode es.',
'linklistsub'           => '(Liss met de Links)',
'linkshere'             => 'Dat sin de Sigge, die op <strong>„[[:$1]]“</strong> linke dun:',
'nolinkshere'           => 'Kein Sigg link noh <strong>„[[:$1]]“</strong>.',
'nolinkshere-ns'        => 'Nix link op <strong>„[[:$1]]“</strong> en dämm Appachtemang.',
'isredirect'            => 'Ömleitungssigg',
'istemplate'            => 'weed enjeföch',
'whatlinkshere-links'   => '← Links',

# Block/unblock
'blockip'                     => 'Metmaacher sperre',
'blockiptext'                 => 'Hee kanns De bestemmte Metmaacher oder IP-Adresse sperre, su dat se hee em Wiki nit mieh schrieve und Sigge ändere künne. Dat sollt nor jedon wääde om sujenannte Vandaale ze bremse. Un mer müsse uns dobei natörlich aan uns [[{{MediaWiki:Policy-url}}|Rejelle]] för esu en Fäll halde. Drag bei „Aanlass“ ene möchlichs jenaue Jrund en, wöröm dat Sperre passeet. Nenn un Link op de Sigge wo Einer kapott jemaat hät, zem Beispill. Luur op [[Special:Ipblocklist|de Liss met jesperrte IP_Adresse]] wann de ne Üvverbleck üvver de Sperrunge han wells, un och wann De se ändere wells.',
'ipaddress'                   => 'IP-Adress',
'ipadressorusername'          => 'IP Adress oder Metmaacher Name',
'ipbexpiry'                   => 'Duur för wie lang',
'ipbreason'                   => 'Aanlass:',
'ipbreasonotherlist'          => 'Ne andere Bejründung',
'ipbanononly'                 => 'Nor de namelose Metmaacher sperre',
'ipbcreateaccount'            => 'Neu aanmelde verbeede',
'ipbenableautoblock'          => 'Dun automatisch de letzte IP-Adress sperre, die dä Metmaacher jehatt hät, un och all die IP-Adresse, vun wo dä versök, jet ze ändere.',
'ipbsubmit'                   => 'Dun dä Metmaacher sperre',
'ipbother'                    => 'En ander Zigg',
'ipboptions'                  => '1 Stund:1 hour,2 Stund:2 hours,3 Stund:3 hours,6 Stund:6 hours,12 Stund:12 hours,1 Dach:1 day,3 Däch:3 days,1 Woch:1 week,2 Woche:2 weeks,3 Woche:3 weeks,1 Mond:1 month,3 Mond:3 months,6 Mond:6 months,9 Mond:9 months,1 Johr:1 year,2 Johre:2 years,3 Johre:3 years,Unbejrenz:infinite', # display1:time1,display2:time2,...
'ipbotheroption'              => 'Söns wie lang',
'ipbotherreason'              => 'Ander Jrund oder Zosätzlich:',
'badipaddress'                => 'Wat De do jeschrevve häs, dat es kein öntlije 
IP-Adress.',
'blockipsuccesssub'           => 'De IP-Adress es jesperrt',
'blockipsuccesstext'          => '[[Special:Contributions/$1|$1]] es jetz jesperrt.
Luur op [[Special:Ipblocklist|de Liss met jesperrte IP_Adresse]] wann de ne Üvverbleck üvver de Sperrunge han wells, 
un och wann De se ändere wells.',
'unblockip'                   => 'Dä Medmacher widder maache looße',
'unblockiptext'               => 'Hee kanns De vörher jesperrte IP_Adresse oder Metmaacher widder freijevve, un dänne esu dat Rääch för ze Schrieve hee em Wiki widder jevve.
Luur op [[Special:Ipblocklist|de Liss met jesperrte IP_Adresse]] wann de ne Üvverbleck üvver de Sperrunge han wells, 
un och wann De se ändere wells.',
'ipusubmit'                   => 'Dun de Sperr för die Adress widder ophevve',
'unblocked'                   => '[[User:$1|$1]] wood widder zojelooße',
'ipblocklist'                 => 'Liss met jesperrte IP-Adresse un Metmaacher Name',
'ipblocklist-summary'         => "Em [[Special:Log/block|{{int:blocklogpage}}]] fingk mer de Metmaacher-Sperre un -Entsperrunge, die von ennem Wiki-Köbes vun Hand jemaat woode.
Hee sin die Sperre opjeliss, die noch bestonn. Dat sin de Metmaacher, un de namelose IP-Adresse, die em Momang noch jesperrt sin, och de automatisch jesperrte IP-Adresse, die em Logbooch nit opdauche. Bei de automatisch Jesperrte ha'mer kein Name dobei.",
'ipblocklist-submit'          => 'Sööke',
'blocklistline'               => '$1, $2 hät „$3“ jesperrt ($4)',
'infiniteblock'               => 'för iwich',
'expiringblock'               => 'endt am $1',
'anononlyblock'               => 'nor namelose',
'noautoblockblock'            => 'automatisch Sperre avjeschalt',
'createaccountblock'          => 'Aanmelde nit möchlich',
'blocklink'                   => 'Sperre',
'unblocklink'                 => 'widder freijevve',
'contribslink'                => 'Beidräch',
'autoblocker'                 => 'Automatich jesperrt. Ding IP_Adress wood vör kootem vun däm Metmaacher „[[User:$1|$1]]“ jebruch. Dä es jesperrt woode wäje: „<i>$2</i>“',
'blocklogpage'                => 'Logboch met Metmaacher-Sperre',
'blocklogentry'               => 'hät „[[$1]]“ fö de Zick vun $2 un $3 jesperrt.',
'blocklogtext'                => 'Hee es dat Logboch för et Metmaacher Sperre un Freijevve. Automatich jesperrte 
IP-Adresse sin nit hee, ävver em 
[[Special:Ipblocklist|Logboch met jesperrte IP-Adresse]] ze finge.',
'unblocklogentry'             => 'Metmaacher „[[User:$1|$1]]“ freijejovve',
'range_block_disabled'        => 'Adresse Jebeede ze sperre, es nit erlaub.',
'ipb_expiry_invalid'          => 'De Duur es Dress. Jevv se richtich aan.',
'ipb_already_blocked'         => '„$1“ es ald jesperrt',
'ipb_cant_unblock'            => 'Ene Fähler: De Sperr Nummer $1 es nit ze finge. Se künnt ald widder freijejovve woode sin.',
'ip_range_invalid'            => 'Dä Bereich vun IP_Adresse es nit en Oodnung.',
'proxyblocker'                => 'Proxy_Blocker',
'proxyblockreason'            => 'Unger Ding IP_Adress läuf ene offe Proxy.
Dröm kanns De hee em Wiki nix maache.
Schwaad met Dingem System-Minsch oder Netzwerk-Techniker (ISP Internet Service Provider) un verzäll dänne vun däm Risiko för ehr Secherheit!',
'proxyblocksuccess'           => 'Fäädich',
'sorbsreason'                 => 'Ding IP-Adress weed en de DNSbl als ene offe Proxy jeliss. Schwaad met Dingem System-Minsch oder Netzwerk-Techniker (ISP Internet Service Provider) drüvver, un verzäll dänne vun däm Risiko för ehr Secherheit!',
'sorbs_create_account_reason' => 'Ding IP-Adress weed en de DNSbl als ene offe Proxy jeliss. Dröm kanns De Dich hee em Wiki nit als ene neue Metmaacher aanmelde. Schwaad met Dingem System-Minsch oder Netzwerk-Techniker oder (ISP Internet Service Provider) drüvver, un verzäll dänne vun däm Risiko för ehr Secherheit!',

# Developer tools
'lockdb'              => 'Daatebank sperre',
'unlockdb'            => 'Daatebank freijevve',
'lockdbtext'          => 'Nohm Sperre kann keiner mieh Änderunge maache an sing Oppassliss, aan Enstellunge, Atikelle, uew. un neu Metmaacher jitt et och nit. Bes de secher, datte dat wells?',
'unlockdbtext'        => 'Nohm Freijevve es de Daatebank nit mieh jesperrt, un all de normale Änderunge wääde widder möchlich. Bes de secher, datte dat wells?',
'lockconfirm'         => 'Jo, ich well de Daatebank jesperrt han.',
'unlockconfirm'       => 'Jo, ich well de Daatebank freijevve.',
'lockbtn'             => 'Daatebank sperre',
'unlockbtn'           => 'Daatebank freijevve',
'locknoconfirm'       => 'Do häs kei Hökche en däm Feld zem Bestätije jemaht.',
'lockdbsuccesssub'    => 'De Daatebank es jetz jesperrt',
'unlockdbsuccesssub'  => 'De Daatebank es jetz freijejovve',
'lockdbsuccesstext'   => 'De Daatebank vun de {{SITENAME}} jetz jesperrt.<br /> Dun se widder freijevve, wann Ding Waadung durch es.',
'unlockdbsuccesstext' => 'De Daatebank es jetz freijejovve.',
'lockfilenotwritable' => 'De Datei, wo de Daatebank met jesperrt wääde wööd, künne mer nit aanläje, oder nit dren schrieve. Esu ene Dress! Dat mööt dä Websörver ävver künne! Verzäll dat enem Verantwortliche för de Installation vun däm Sörver oder repareer et selvs, wann De et kanns.',
'databasenotlocked'   => '<strong>Opjepass:</strong> De Daatebank es <strong>nit</strong> jesperrt.',

# Move page
'move-page'               => 'De Sigg „$1“ ömnenne',
'move-page-legend'        => 'Sigg Ömnenne',
'movepagetext'            => "Hee kanns De en Sigg en de {{SITENAME}} ömnenne.
Domet kritt die Sigg ene neue Name, un all vörherije Versione vun dä Sigg och.
Unger däm ahle Name weed automatisch en Ömleitung op dä neue Name enjedrage.
Links op dä ahle Name blieve ävver wie se wore.
Dat heiß, Do muss selver nohluure, ov do jetz [[Special:doubleredirects|dubbelde]] oder [[Special:brokenredirects|kapodde]] Ömleitunge bei eruskumme.
Wann De en Sigg ömnenne deis, häs Do och doför ze sorje, dat de betroffene Links do henjonn, wo se hen jonn solle. 
Alsu holl Der de Liss „Wat noh hee link“ un jangk se durch!
De Sigg weed <strong>nit</strong> ömjenannt, wann et met däm neue Name ald en Sigg jitt, <strong>usser</strong> do es nix drop, oder et es en Ömleitung un se es noch nie jeändert woode.
Esu ka'mer en Sigg jlich widder zeröck ömnenne, wa'mer sich mem Ömnenne verdonn hät, un mer kann och kein Sigge kapottmaache, wo ald jet drop steiht.

<strong>Oppjepass!</strong> Wat beim Ömnenne erus kütt, künnt en opfällije un villeich stürende Änderung am Wiki sin, besonders bei off jebruchte Sigge.
Alsu bes secher, datte versteihs, watte hee am maache bes, ih dattet mähs!",
'movepagetalktext'        => "Dä Sigg ehr Klaafsigg, wann se ein hät, weed automatisch met  ömjenannt, '''usser''' wann:
* de Sigg en en ander Appachtemeng kütt,
* en Klaafsigg met däm neue Name ald do es, un et steiht och jet drop,
* De unge en däm Kääsje '''kei''' Hökche aan häs.
En dänne Fäll, muss De Der dä Enhald vun dä Klaafsigge selvs vörnemme, un eröm kopeere watte bruchs.",
'movearticle'             => 'Sigg zem Ömnenne:',
'movenologin'             => 'Nit Enjelogg',
'movenologintext'         => 'Do mööts ald aanjemeldt un [[Special:Userlogin|enjelogg]] sin, öm en Sigg ömzenenne.',
'movenotallowed'          => 'Do kriss nit erlaub, en däm Wiki hee de Sigge ömzenenne.',
'newtitle'                => 'op dä neue Name',
'move-watch'              => 'Op die Sigg hee oppasse',
'movepagebtn'             => 'Ömnenne',
'pagemovedsub'            => 'Dat Ömnenne hät jeflupp',
'movepage-moved'          => "<big>'''De Sigg „$1“ es jez en „$2“ ömjenannt.'''</big>", # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => "De Sigg met däm Name jitt et ald, oder dä Name ka'mer oder darf mer nit bruche.<br />Do muss Der ene andere Name ussöke.",
'talkexists'              => '<strong>Opjepass:</strong> De Sigg selver woodt jetz ömjenannt, ävver dä ehr Klaafsigg kunnte mer nit met ömnenne. Et jitt ald ein met däm neue Name. Bes esu jod un dun die zwei vun Hand zosamme läje!',
'movedto'                 => 'ömjenannt en',
'movetalk'                => 'dä ehr Klaafsigg met ömnenne, wat et jeiht',
'move-subpages'           => 'Don de Ongersigge met_ömnënne',
'move-talk-subpages'      => 'Don de Ongersigge von de Klaafsigge met_ömnënne',
'movepage-page-exists'    => 'En Sigg „$1“ ham_mer ald, un di bliif och beshtonn, mer don se nit ottomatėsch ußtuusche.',
'movepage-page-moved'     => 'Di eejemoolijje Sigg „$1“ es jëz op „$2“ ömjenannt.',
'movepage-page-unmoved'   => 'Mer kůnnte di Sigg „$1“ nit op „$2“ ömnënne.',
'movepage-max-pages'      => 'Mer han jëtz {{PLURAL:$1|ëijn Sigg|$1 Sigge|kein Sigg}} ömjenanndt. Mieh jeiht nit automatėsch.',
'1movedto2'               => 'hät de Sigg vun „[[$1]]“ en „[[$2]]“ ömjenannt.',
'1movedto2_redir'         => 'hät de Sigg vun „[[$1]]“ en „[[$2]]“ ömjenannt un doför de ahl Ömleitungs-Sigg fottjeschmesse.',
'movelogpage'             => 'Logboch vum Sigge Ömnenne',
'movelogpagetext'         => 'Hee sin de Neuste ömjenannte Sigge opjeliss, un wä et jedon hät.',
'movereason'              => 'Aanlass:',
'revertmove'              => 'Et Ömnänne zerök_nämme',
'delete_and_move'         => 'Fottschmieße un Ömnenne',
'delete_and_move_text'    => '== Dä! Dubbelte Name ==
Dä Atikkel „[[$1]]“ jitt et ald. Wollts De en fottschmieße, öm hee dä Atikkel ömnenne ze künne?',
'delete_and_move_confirm' => 'Jo, dun dä Atikkel fottschmieße.',
'delete_and_move_reason'  => 'Fottjeschmesse, öm Platz för et Ömnenne ze maache',
'selfmove'                => 'Du Doof! - dä ahle Name un dä neue Name es däselve - do hät et Ömnenne winnich Senn.',
'immobile_namespace'      => 'Do künne mer Sigge nit hen ömnenne, dat Appachtemeng es speziell, un dä neue Name för de Sigg jeiht deswäje nit.',

# Export
'export'          => 'Sigge Exporteere',
'exporttext'      => "Hee exportees De dä Tex un de Eijeschaffte vun ener Sigg, oder vun enem Knubbel Sigge, de aktuelle Version, met oder ohne ehr ählere Versione.
Dat Janze es enjepack en XML.
Dat ka'mer en en ander Wiki — wann et och met dä MediaWiki-Soffwär läuf — üvver de Sigg „[[Special:Import|Import]]“ do widder importeere.

* Schriev de Titele vun dä Sigge en dat Feld för Tex enzejevve, unge, eine Titel en jede Reih.
* Dann dun onoch ussöke, ov De all de vörherije Versione vun dä Sigge han wells, oder nor de aktuelle met dä Informatione vun de letzte Änderung. (En däm Fall künns De, för en einzelne Sigg, och ene tirekte Link bruche, zom Beispill „[[{{ns:special}}:Export/{{int:mainpage}}]]“ för de Sigg „[[{{int:mainpage}}]]“ ze exporteere)

Denk dran, dat'te dat Zeuch em Unicode Format avspeichere muss, wann De jet domet aanfange künne wells.",
'exportcuronly'   => 'Bloß de aktuelle Version usjevve (un <strong>nit</strong> de janze ahle Versione onoch met dobei dun)',
'exportnohistory' => '----
<strong>Opjepass:</strong> de janze Versione Exporteere es hee em Wiki avjeschalt. Schad, ävver et wör en 
zo jroße Lass för dä Sörver.',
'export-submit'   => 'Loss_Jonn!',
'export-addcat'   => 'Dobei donn',

# Namespace 8 related
'allmessages'               => 'All Tex, Baustein un Aanzeije vum Wiki-System',
'allmessagesname'           => 'Name',
'allmessagesdefault'        => 'Dä standaadmäßije Tex',
'allmessagescurrent'        => 'Esu es dä Tex jetz',
'allmessagestext'           => 'Hee kütt en Liss met Texte, Texstöck, un Nachrichte em Appachtemeng „MediaWiki“ — Do draan Ändere löht et Wiki anders ußsin, dat darf dröm nit jede maache. Wenn De jenerell aan 
[http://www.mediawiki.org/wiki/Localisation MediaWiki singe Översezung] jet anders han wells, do jangk noh [http://translatewiki.net Betawiki], odder, jenou wie wenn de hee em Wiki jet anders han wells, frooch ene [[{{#special:Listadmins}}|Wiki-Köbes]] donoh, odder schriev op die Klaafsigg vun däm Textstöck, wo et Der dröm jeiht.',
'allmessagesnotsupportedDB' => '<strong>Dat wor nix!</strong> Mer künne „{{ns:special}}:Allmessages“ nit zeije, <code>$wgUseDatabaseMessages</code> es usjeschalt!',
'allmessagesfilter'         => 'Fingk dat Stöck hee em Name:',
'allmessagesmodified'       => 'Dun nor de Veränderte aanzeije',

# Thumbnails
'thumbnail-more'  => 'Jrößer aanzeije',
'filemissing'     => 'Datei es nit do',
'thumbnail_error' => 'Ene Fähler es opjetauch beim Maache vun enem Breefmarke/Thumbnail-Beldche: „$1“',

# Special:Import
'import'                     => 'Sigge Emporteere',
'importinterwiki'            => 'Trans Wiki Emport',
'import-interwiki-text'      => 'Wähl en Wiki un en Sigg zem Emporteere us. Et Datum vun de Versione un de 
Metmaacher Name vun de Schriever wääde dobei metjenomme. All de Trans Wiki Emporte wääde em 
[[{{ns:special}}:Log/import|Emport_Logboch]] fassjehallde.',
'import-interwiki-history'   => 'All de Versione vun dä Sigg hee kopeere',
'import-interwiki-submit'    => 'Huhlade!',
'import-interwiki-namespace' => 'Dun de Sigge emporteere em Appachtemeng:',
'importtext'                 => 'Dun de Daate met däm „[[Special:Export|Export]]“ vun do vun enem Wiki Exporteere, dobei dun et - etwa bei Dir om Rechner - avspeichere, un dann hee huhlade.',
'importstart'                => 'Ben Sigge am emporteere â€¦',
'import-revision-count'      => '({{PLURAL:$1|ein Version|$1 Versione|kein Version}})',
'importnopages'              => 'Kein Sigg för ze Emporteere jefunge.',
'importfailed'               => 'Dat Importeere es donevve jejange: $1',
'importunknownsource'        => 'Die Zoot Quell för et Emporteere kenne mer nit',
'importcantopen'             => 'Kunnt op de Datei för dä Emport nit zojriefe',
'importbadinterwiki'         => 'Verkihrte Interwiki Link',
'importnotext'               => 'En dä Datei wor nix dren enthallde, oder winnichstens keine Tex',
'importsuccess'              => 'Dat Emporteere hät jeflupp!',
'importhistoryconflict'      => 'Mer han zwei ahle Versione jefunge, die dun sich bieße - die ein wor ald do - de ander en dä Emport Datei. möchlich, Ehr hatt die Daate ald ens emporteet.',
'importnosources'            => 'Hee sin kein Quell för dä Trans Wiki Emport enjerich. Dat ahle Versione Huhlade es avjeschalt  un nit möchlich.',
'importnofile'               => 'Et wood kein Datei huhjelade för ze Emporteere.',
'xml-error-string'           => '$1 — en {{PLURAL:$2|eetz|$2-}}te Reih en de {{PLURAL:$3|eetz|$3-}}te Spalde, dat ess_et {{PLURAL:$4|eetz|$4-}}te Byte: $5',
'import-upload'              => 'En XML-Datei impochteere',

# Import log
'importlogpage'                    => 'Logboch met emporteerte Sigge',
'importlogpagetext'                => 'Sigge met ehre Versione vun ander Wikis emporteere.',
'import-logentry-upload'           => '„[[$1]]“ emporteet',
'import-logentry-upload-detail'    => '{{PLURAL:$1|ein Version|$1 Versione|kein Version}} emporteet',
'import-logentry-interwiki'        => 'trans_wiki_emporteet: „$1“',
'import-logentry-interwiki-detail' => '{{PLURAL:$1|ein Version|$1 Versione|kein Version}} vun „$2“',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Ming eije Metmaachersigg',
'tooltip-pt-mytalk'               => 'Dun ming eije Klaafsigg aanzeije',
'tooltip-pt-preferences'          => 'De eije Enstellunge',
'tooltip-pt-watchlist'            => 'De Liss met de Sigge en Dinge eije Oppassliss',
'tooltip-pt-mycontris'            => 'en Liss met Dinge eije Beidräch',
'tooltip-pt-logout'               => 'Uslogge',
'tooltip-ca-talk'                 => 'Dun die Sigg met däm Klaaf övver hee de Sigg aanzeije',
'tooltip-ca-edit'                 => 'De kanns die Sigg hee ändere — für em Avspeichere, donn eetß ens enen Bleck op de Vör-Aansich',
'tooltip-ca-protect'              => 'Dun die Sigg schötze',
'tooltip-ca-delete'               => 'Dun die Sigg fottschmieße',
'tooltip-ca-move'                 => 'Dun die Sigg ömbenenne',
'tooltip-ca-watch'                => 'Dun die Sigg en Ding Oppassliss opnemme',
'tooltip-ca-unwatch'              => 'Schmieß die Sigg us Dinge eije Oppassliss erus',
'tooltip-search'                  => 'En de {{SITENAME}} söke',
'tooltip-p-logo'                  => 'Houpsigg',
'tooltip-n-mainpage'              => 'Houpsigk aanzeije',
'tooltip-n-portal'                => 'Övver dat Projek hee, wat De donn un wie de metmaache kanns, wat wo ze fenge es',
'tooltip-n-recentchanges'         => 'En Liss met de neuste Änderunge hee aam Wiki.',
'tooltip-n-randompage'            => 'Dun en janz zofällije Sigg ußßem Wikki trecke un aanzeije',
'tooltip-n-help'                  => 'Do kriss De jehollfe',
'tooltip-n-sitesupport'           => 'Donn uns Ungerstötze!',
'tooltip-t-whatlinkshere'         => 'En Liss met all de Sigge, die ene Link noh hee han',
'tooltip-t-emailuser'             => 'Scheck en E-Mail aan dä Metmaacher',
'tooltip-t-upload'                => 'Dateie huhlade',
'tooltip-t-specialpages'          => 'Liss met Sondersigge',
'tooltip-ca-nstab-user'           => 'Dun die Metmaachersig aanzeije',
'tooltip-ca-nstab-project'        => 'Dun die Projeksigg aanzeije',
'tooltip-ca-nstab-image'          => 'Dun die Sigg üvver hee die Datei aanzeije',
'tooltip-ca-nstab-template'       => 'Dun die Schabloon aanzeije',
'tooltip-ca-nstab-category'       => 'Dun die Saachjrupp aanzeije',
'tooltip-minoredit'               => 'Deit Ding Änderunge als klein Mini-Änderunge markeere.',
'tooltip-save'                    => 'Deit Ding Änderunge avspeichere.',
'tooltip-preview'                 => 'Liss de Vör-Aansich vun dä Sigg un vun Dinge Änderunge ih datte en Avspeichere deis!',
'tooltip-diff'                    => 'Zeich Ding Änderunge am Tex aan.',
'tooltip-compareselectedversions' => 'Dun de Ungerscheed zwesche dä beids usjewählde Versione zeije.',
'tooltip-watch'                   => 'Op die Sigg hee oppasse.',
'tooltip-recreate'                => 'En fottjeschmesse Sigg widder zeröckholle',

# Stylesheets
'common.css'      => '/* CSS hee aan dä Stell hät Uswirkunge op all Ovverflääsche */',
'standard.css'    => '/* CSS hee aan dä Stell wirrek nur op de Ovverflääsch "Klassesch" */',
'nostalgia.css'   => '/* CSS hee aan dä Stell wirrek nur op de Ovverflääsch "Nostaljesch" */',
'cologneblue.css' => '/* CSS hee aan dä Stell wirrek nur op de Ovverflääsch "Kölsch Blau" */',
'monobook.css'    => '/* CSS hee aan dä Stell wirrek nur op de Ovverflääsch "Monobook" */

/* Mer welle do kein Kleinboochshtave han */
.portlet h5,
.portlet h6,
#p-personal ul,
#p-cactions li a
{
  text-transform: none;
}',
'myskin.css'      => '/* CSS hee aan dä Stell wirrek nur op de Ovverflääsch "Ming Skin" */',
'chick.css'       => '/* CSS hee aan dä Stell wirrek nur op de Ovverflääsch "Höhnsche" */',
'simple.css'      => '/* CSS hee aan dä Stell wirrek nur op de Ovverflääsch "Eijfach" */',
'modern.css'      => '/* CSS hee aan dä Stell wirrek nur op de Ovverflääsch "Modern" */',

# Scripts
'common.js'      => '/* Jedes JavaScrit hee küt für jede Metmaacher in jede Sigg erinn */',
'standard.js'    => '/* De JavaSkrippte fun hee krijje alle Sigge met de Ovverflääsch "Klassesch" jescheck */',
'nostalgia.js'   => '/* De JavaSkrippte fun hee krijje alle Sigge met de Ovverflääsch "Nostaljesch" jescheck */',
'cologneblue.js' => '/* De JavaSkrippte fun hee krijje alle Sigge met de Ovverflääsch "Kölsch Blou" jescheck */',
'monobook.js'    => '/* De JavaSkrippte fun hee krijje alle Sigge met de Ovverflääsch "Monnobooch" jescheck */',
'myskin.js'      => '/* De JavaSkrippte fun hee krijje alle Sigge met de Ovverflääsch "Ming Skin" jescheck */',
'chick.js'       => '/* De JavaSkrippte fun hee krijje alle Sigge met de Ovverflääsch "Höhnsche" jescheck */',
'simple.js'      => '/* De JavaSkrippte fun hee krijje alle Sigge met de Ovverflääsch "Eijfach" jescheck */',
'modern.js'      => '/* De JavaSkrippte fun hee krijje alle Sigge met de Ovverflääsch "Modern" jescheck */',

# Metadata
'nodublincore'      => 'De RDF_Meta_Daate vun de „Dublin Core“ Aat sin avjeschalt.',
'nocreativecommons' => 'De RDF_Meta_Daate vun de „Creative Commons“ Aat sin avjeschalt.',
'notacceptable'     => '<strong>Blöd:</strong> Dä Wiki_Sörver kann de Daate nit en einem Format erüvverjevve, wat Dinge Client oder Brauser verstonn künnt.',

# Attribution
'anonymous'        => 'Namelose Metmaacher vun de {{SITENAME}}',
'siteuser'         => '{{SITENAME}}-Metmaacher $1',
'lastmodifiedatby' => 'Die Sigg hee wood et letz am $1 öm $2 Uhr vum [[User:$3|$3]] jeändert.', # $1 date, $2 time, $3 user
'othercontribs'    => 'Baut op de Arbeid vun „<strong>$1</strong>“ op.',
'others'           => 'ander',
'siteusers'        => '{{SITENAME}}-Metmaacher $1',
'creditspage'      => 'Üvver de Metmaacher un ehre Beidräch för heh die Sigg',
'nocredits'        => "För die Sigg ha'mer nix en de Liss.",

# Spam protection
'spamprotectiontitle' => 'SPAM_Schotz',
'spamprotectiontext'  => 'De Sigg, die de avspeichere wells, die weed vun unsem SPAM_Schotz nit durchjelooße. Dat kütt miehts vun enem Link op en fremde Sigg.',
'spamprotectionmatch' => 'Hee dä Tex hät dä SPAM_Schotz op de Plan jerofe: „<code>$1</code>“',
'spambot_username'    => 'SPAM fottschmieße',
'spam_reverting'      => 'De letzte Version es ohne de Links op  „$1“ widder zerröckjehollt.',
'spam_blanking'       => 'All die Versione hatte Links op „$1“, die sin jetz erus jemaht.',

# Info page
'infosubtitle'   => 'Üvver de Sigg',
'numedits'       => 'Aanzahl Änderunge aan däm Atikkel: <strong>$1</strong>',
'numtalkedits'   => 'Aanzahl Änderunge aan de Klaafsigg: <strong>$1</strong>',
'numwatchers'    => 'Aanzahl Oppasser: <strong>$1</strong>',
'numauthors'     => 'Aanzahl Metmaacher, die aan däm Atikkel met jeschrevve han: <strong>$1</strong>',
'numtalkauthors' => 'Aanzahl Metmaacher beim Klaaf: <strong>$1</strong>',

# Math options
'mw_math_png'    => 'Immer nor PNG aanzeije',
'mw_math_simple' => 'En einfache Fäll maach HTML, söns PNG',
'mw_math_html'   => 'Maach HTML wann möchlich, un söns PNG',
'mw_math_source' => 'Lohß et als TeX (jod för de Tex-Brausere)',
'mw_math_modern' => 'De bess Enstellung för de Brauser vun hück',
'mw_math_mathml' => 'Nemm „MathML“ wann möchlich (em Probierstadium)',

# Patrolling
'markaspatrolleddiff'        => 'Nohjeluurt. Dun dat fasshallde.',
'markaspatrolledtext'        => 'De Änderung es nohjeluert, dun dat fasshallde',
'markedaspatrolled'          => 'Et Kennzeiche „Nohjeluurt“ speichere',
'markedaspatrolledtext'      => 'Et es jetz fassjehallde, datte usjewählte Änderunge nohjeluurt sin.',
'rcpatroldisabled'           => 'Et Nohluure vun de letzte Änderunge es avjeschalt',
'rcpatroldisabledtext'       => 'Et Nohluure fun de letzte Änderunge es em Momang nit möchlich.',
'markedaspatrollederror'     => 'Dat Kennzeiche „Nohjeluurt“ kunnt ich nit avspeichere.',
'markedaspatrollederrortext' => 'Do muss en bestemmte Version ussöke.',

# Patrol log
'patrol-log-page' => 'Logboch vun de nohjeloorte Änderunge',
'patrol-log-line' => 'hät $1 von „$2“ $3 nohjeloort.',
'patrol-log-auto' => '(automatisch)',
'patrol-log-diff' => 'de Version $1',

# Image deletion
'deletedrevision' => 'De ahl Version „$1“ es fottjeschmesse',

# Browsing diffs
'previousdiff' => '← De Ungerscheede dovör zeije',
'nextdiff'     => 'De Ungerscheede donoh zeije →',

# Media information
'mediawarning'    => "<strong>Opjepass</strong>: En dä Datei künnt en <b>jefährlich Projrammstöck</b> dren stecke. Wa'mer et laufe looße dät, do künnt dä Sörver met för de Cracker opjemaht wääde. <hr />",
'imagemaxsize'    => 'Belder op de Sigge, wo se beschrevve wääde, nit jrößer maache wie:',
'thumbsize'       => 'Esu breid solle de klein Beldche (Thumbnails/Breefmarke) sin:',
'widthheightpage' => '$1×$2, {{PLURAL:$3|eij Sigg|$3 Sigge|keij Sigge}}',

# Special:Newimages
'newimages'             => 'Belder, Tön, uew. als Jalerie',
'imagelisttext'         => 'Hee küt en Liss vun <strong>$1</strong> Datei{{PLURAL:$1||e}}, zoteet $2.',
'newimages-summary'     => 'Hee die Sigg zeig die zoletz huhjeladene Belder un Dateie aan.',
'showhidebots'          => '(Bots $1)',
'noimages'              => 'Kein Dateie jefunge.',
'ilsubmit'              => 'Sök',
'bydate'                => 'nohm Datum',
'sp-newimages-showfrom' => 'Zeich de neu Belder av däm $1 öm $2 Uhr',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds-abbrev' => 'Sek.',
'minutes-abbrev' => 'Min.',
'hours-abbrev'   => 'Std.',

# Metadata
'metadata'          => 'Metadaate',
'metadata-help'     => 'En dä Datei stich noh mieh an Daate dren. Dat sin Metadaate, die normal vum Opnahmejerät kumme. Wat en Kamera, ne Scanner, un esu, do fassjehallde han, dat kann ävver späder met enem Projramm bearbeidt un usjetuusch woode sin.',
'metadata-expand'   => 'Mieh zeije',
'metadata-collapse' => 'Daate Versteche',
'metadata-fields'   => 'Felder us de EXIF Metadate, di hee opjeliss sen, zeich et Wiki op Beldersigge aan, wan de Metadate kleinjeklick sin. Di andere weede esu lang verstoche. Dat Versteiche is och der Standat.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength', # Do not translate list items

# EXIF tags
'exif-imagewidth'  => 'Breejd',
'exif-imagelength' => 'Läng',
'exif-make'        => 'dä Kammera ier Heershtäller',
'exif-model'       => 'dat Kammerra-Modäll',
'exif-flash'       => 'Bletz',
'exif-contrast'    => 'der Kontraß',
'exif-saturation'  => 'de Färfsättijung',
'exif-sharpness'   => 'de Beldschärf',

'exif-orientation-1' => 'Nomal', # 0th row: top; 0th column: left

'exif-meteringmode-255' => 'Ander',

'exif-lightsource-4' => 'Bletz',

'exif-contrast-0' => 'Nomal',

'exif-saturation-0' => 'Nomal',

'exif-sharpness-0' => 'Nomal',

# External editor support
'edit-externally'      => 'Dun de Datei met enem externe Projramm bei Dr om Rechner bearbeide',
'edit-externally-help' => 'Luur en de [http://meta.wikimedia.org/wiki/Help:External_editors Installationsaanweisunge] noh Hinwies, wie mer esu en extern Projramm ennrechte un installeere deit.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'all',
'imagelistall'     => 'all',
'watchlistall2'    => 'all',
'namespacesall'    => 'all',
'monthsall'        => 'all',

# E-mail address confirmation
'confirmemail'            => 'E-Mail Adress bestätije',
'confirmemail_noemail'    => 'En [[Special:Preferences|Ding Enstellunge]] es kein öntlich E-Mail Adress.',
'confirmemail_text'       => 'Ih datte en däm Wiki hee de E-Mail bruche kanns, muss De Ding E-Mail Adress bestätich han, dat se en Oodnung es un dat se och Ding eijene es. Klick op dä Knopp un Do kriss en E-Mail jescheck. Do steiht ene Link met enem Code dren. Wann De met Dingem Brauser op dä Link jeihs, dann deis De domet bestätije, dat et wirklich Ding E-Mail Adress es. Dat es nit allzo secher, alsu wör nix för Die Bankkonto oder bei de Sparkass, ävver et sorg doför, dat nit jede Peijaß met Dinger E-Mail oder Dingem Metmaachername eröm maache kann.',
'confirmemail_send'       => 'Scheck en E-Mail zem Bestätije',
'confirmemail_sent'       => 'En E-Mail, för Ding E-Mail Adress ze bestätije, es ungerwähs.',
'confirmemail_sendfailed' => "Beim E-Mail Adress Bestätije es jet donevve jejange, dä Sörver hatt e Problem met 
sing Konfijuration, oder en Dinger E-Mail Adress es e Zeiche verkihrt, oder esu jet.

Dä E-Mail-Sörver saat: ''$1''",
'confirmemail_invalid'    => 'Et es jet donevve jejange, Ding E-Mail Adress es un bliev nit bestätich. Mööchlech, dä Code wohr verkihrt, hä künnt avjelaufe jewäse sin, oder esu jet. Versöök et noch ens.',
'confirmemail_needlogin'  => 'Do muss Dich $1, för de E-Mail Adress ze bestätije.',
'confirmemail_success'    => 'Ding E-Mail Adress es jetz bestätich. Jetz künns De och noch [[Special:Userlogin|enlogge]]. Vill Spass!',
'confirmemail_loggedin'   => 'Ding E-Mail Adress es jetz bestätich!',
'confirmemail_error'      => 'Beim E-Mail Adress Bestätije es jet donevve jejange, de Bestätijung kunnt nit avjespeichert wääde.',
'confirmemail_subject'    => 'Dun Ding E-Mail Adress bestätije för de {{SITENAME}}.',
'confirmemail_body'       => 'Jod möchlich, Do wors et selver,
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
Do kanns ävver och op he dä Link jon:

$5

Domet deiß De tirek sare, dat De Adress vun
Ding E-Mail nit bestätije wells.

Wann de jetz neujeerich jewoode bes un wells
wesse, wat met de {{SITENAME}} loss es,
do  jang met Dingem Brauser noh:
{{FULLURL:{{MediaWiki:Mainpage}}}}
un luur Der et aan.

Ene schöne Jroß vun de {{SITENAME}}.

-- 
{{SITENAME}}: {{fullurl:{{Mediawiki:mainpage}}}}',

# Scary transclusion
'scarytranscludedisabled' => '[Et Enbinge per Interwiki es avjeschalt]',
'scarytranscludefailed'   => '[De Schablon „$1“ enzebinge hät nit jeflupp]',
'scarytranscludetoolong'  => '[Schad, de URL es ze lang]',

# Trackbacks
'trackbackbox'      => '<div id="mw_trackbacks">
Trackbacks för hee di Sigg:<br />
„<strong>$1</strong>“
</div>',
'trackbackremove'   => ' ([$1 Fottschmieße])',
'trackbacklink'     => 'Trackback',
'trackbackdeleteok' => 'Trackback es fottjeschmesse.',

# Delete conflict
'deletedwhileediting' => '<strong>Opjepass:</strong> De Sigg wood fottjeschmesse, nohdäm Do ald aanjefange häs, dran ze Ändere.',
'confirmrecreate'     => 'Dä Metmaacher [[User:$1|$1]] ([[User talk:$1|Klaaf]]) hät die Sigg fottjeschmesse, nohdäm Do do dran et Ändere aanjefange häs. Dä Jrund:
: „<i>$2</i>“
Wells Do jetz met en neu Version die Sigg widder neu aanläje?',
'recreate'            => 'Widder neu aanlääje',

# HTML dump
'redirectingto' => 'Leit öm op: „[[$1]]“',

# action=purge
'confirm_purge'        => 'Dä Zweschespeicher för die Sigg fottschmieße?

$1',
'confirm_purge_button' => 'Jo — loss jonn!',

# AJAX search
'searchcontaining' => 'Sök noh Atikkele, wo „$1“ em Tex vörkütt.',
'searchnamed'      => 'Sök noh Atikkele, wo „$1“ em Name vörkütt.',
'articletitles'    => 'Atikkele, die met „$1“ aanfange',
'hideresults'      => 'Versteiche wat erus küt',
'useajaxsearch'    => 'AJAX-Hölp beim Sööke bruche',

# Separators for various lists, etc.
'autocomment-prefix' => '-',

# Multipage image navigation
'imgmultipageprev' => '← de Sigg dovör',
'imgmultipagenext' => 'de Sigg donoh →',
'imgmultigo'       => 'Loss jonn!',

# Table pager
'ascending_abbrev'         => 'opwääts zoteet',
'descending_abbrev'        => 'raffkaz zoteet',
'table_pager_next'         => 'De nächste Sigg',
'table_pager_prev'         => 'De Sigg dovör',
'table_pager_first'        => 'De eetste Sigg',
'table_pager_last'         => 'De letzte Sigg',
'table_pager_limit'        => 'Zeich $1 pro Sigg',
'table_pager_limit_submit' => 'Loss jonn!',
'table_pager_empty'        => 'Nix erus jekumme',

# Auto-summaries
'autosumm-blank'   => 'Dä janze Enhald vun dä Sigg fottjemaht',
'autosumm-replace' => "Dä jannze Enhallt fon dä Sigk ußjetuusch: '$1'",
'autoredircomment' => 'Leit öm op „[[$1]]“',
'autosumm-new'     => 'Neu Sigg: $1',

# Size units
'size-bytes' => '$1 Bytes',

# Watchlist editor
'watchlistedit-numitems'       => 'En Dinge Oppassliss {{PLURAL:$1|es eine Endrach|sen $1 Endräsch|es keine Endrach}} — Klaafsigge dozoh zälle nit ëxtra.',
'watchlistedit-noitems'        => 'Ding Oppassliss es leddisch.',
'watchlistedit-normal-title'   => 'Oppassliss beärbeijde',
'watchlistedit-normal-legend'  => 'Titell uß de Oppassliss eruß lohße',
'watchlistedit-normal-explain' => 'Dat sin de Endräch in Dinge Oppassliss.
Om einzel Titelle loss ze wääde, don Hökche en de Kässjer nevve inne maache, un dann deuß De dä Knopp „{{int:watchlistedit-normal-submit}}“.
De kanns Ding Oppassliss och [[Special:Watchlist/raw|en rüh beärbeide]].',
'watchlistedit-normal-submit'  => 'Jangk de Titele met Hökche eruß schmieße',
'watchlistedit-normal-done'    => '{{PLURAL:$1|Eine Sigge-Tittel es|<strong>$1</strong> Sigge-Tittele sin|Keine Sigge-Tittel es}} us Dinge Opassliss erus jefloore:',
'watchlistedit-raw-title'      => 'Rüh Oppassliss beärbeide',
'watchlistedit-raw-legend'     => 'Rüh Oppassliss beärbeide',
'watchlistedit-raw-explain'    => "Dat sin de Endräch in Dinge Oppassliss en rüh.
Öm einzel Titelle loss ze wääde, kanns de de Reije met inne eruß schmieße, ov leddich maache.
Öm neu Titelle  dobei ze don, schriev neu Reije dobei. Jede Titel moß en en Reih för sijj_allein shtonn.
Wanns De fädig bes, dann deuß De dä Knopp „{{int:watchlistedit-raw-submit}}“.
Natörlech kanns De di Liss och — met Dingem Brauser singe ''<span lang=\"\"en\">Copy&amp;Paste</span>''-Funkßjohn — komplett kopeere odder ußtuusche.
De könnts Ding Oppassliss ävver och [[Special:Watchlist/edit|övver e Fomulaa met Kässjer un Hökscher beärbeide]].",
'watchlistedit-raw-titles'     => 'Endräch:',
'watchlistedit-raw-submit'     => 'Oppassliss neu fasshallde',
'watchlistedit-raw-done'       => 'Ding Oppassliss es fassjehallde.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|Eine Sigge-Tittel wood|<strong>$1</strong> Sigge-Tittele woodte|Keine Sigge-Tittel}} dobeijedonn:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|Eine Endrach es eruß jefloore:|<strong>$1</strong> Endräsh es eruß jefloore:|Keine Endrach es eruß jefloore.}}',

# Watchlist editing tools
'watchlisttools-view' => 'Oppaßliß — Änderunge zeije',
'watchlisttools-edit' => 'beloore un beärbede',
'watchlisttools-raw'  => 'rüh beärbeijde | expochteere | empochteere',

# Special:Version
'version'                          => 'Version vun de Wiki Soffwär zeije', # Not used as normal message but as header for the special page itself
'version-extensions'               => 'Installeete Erjänzunge un Zohsätz',
'version-specialpages'             => 'Sondersigge',
'version-parserhooks'              => 'De Parser-Hooke',
'version-variables'                => 'Variable',
'version-other'                    => 'Söns',
'version-mediahandlers'            => 'Medije-Handler',
'version-hooks'                    => 'Schnettstelle oder Hooke',
'version-extension-functions'      => 'Funktione för Zosätz',
'version-parser-extensiontags'     => 'Erjänzunge zom Parser',
'version-parser-function-hooks'    => 'Parserfunktione',
'version-skin-extension-functions' => 'Funktione för de Skins ze erjänze',
'version-hook-name'                => 'De Schnettstelle ier Name',
'version-hook-subscribedby'        => 'Opjeroofe vun',
'version-version'                  => 'Version',
'version-license'                  => 'Lizenz',
'version-software'                 => 'Installeete Soffwäer',
'version-software-product'         => 'Produk',
'version-software-version'         => 'Version',

# Special:Filepath
'filepath'         => 'Bellder, Tööhn, uew. zëije, med ier URL',
'filepath-page'    => 'Dattëij_Name:',
'filepath-submit'  => 'Zëĳsh dä Pahdt',
'filepath-summary' => "Med dä Söndersigg hee künnd'Er dä kompläte Paad vun de neuste Version vun ene Datei direk erusfenge. Die Datei weed jlich aanjezeig, odder med däm paßende Projramm op jemaat.

Doht der Name ohne „{{ns:image}}:“ doför ennjävve.",

# Special:FileDuplicateSearch
'fileduplicatesearch-summary'  => 'Söök noh dubbelte Dateie övver dänne iere Häsh-Zahl.

Der Name moß ohne der Försatz „{{ns:image}}:“ aanjejovve wääde.',
'fileduplicatesearch-submit'   => 'Sööke',
'fileduplicatesearch-result-n' => "Vun dä Datei „$1“ ham'mer '''{{PLURAL:$2|ein|$2|kein}}''' dubbelte mem selve Enhalt jefonge.",

# Special:SpecialPages
'specialpages'                   => 'Sondersigge',
'specialpages-summary'           => 'Die Sondersigg hee zeich ene Övverbleck över de Sondersigge, die vum Wiki automatesch opjebout wäde un wo mer direk nix draan ändere kann.',
'specialpages-note'              => "<h4 class='mw-specialpagesgroup'>Lejänd (Äklierong):</h4><table style='width:100%;' class='mw-specialpages-table'><tr><td valign='top'><ul><li> Sondersigge för jede Metmaacher
</li><li class='mw-specialpages-page mw-specialpagerestricted'>Sondersigge för Metmaacher met besönder Räächte
</li></ul></td></tr></table>",
'specialpages-group-maintenance' => 'Waadungsleste',
'specialpages-group-other'       => 'Ander Sondersigge',
'specialpages-group-login'       => 'Aamelde',
'specialpages-group-changes'     => 'Letzte Änderunge un Logböcher',
'specialpages-group-media'       => 'Medie',
'specialpages-group-users'       => 'Metmaacher un denne ier Rääschte',
'specialpages-group-highuse'     => 'Öff jebruchte Sigge',

);
