<?php
/** Colognian (Ripoarisch)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Caesius noh en Idee vum Manes
 * @author Purodha
 * @author Reedy
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
 */
/**
 * Hints for editing
 * Avoid Ã¤ and other special codings because of legibility for those users,
 * who will take this as a basis for further ripuarian message interfaces
 * Ã¤ => ä, Ã¶ => ö, Ã¼ => ü, Ã„ => Ä, Ã– => Ö, Ãœ => Ü, ÃŸ => ß
 * â€ž => „, â€œ => “
 */

$fallback = 'de';

$namespaceNames = array(
	NS_MEDIA            => 'Medie',
	NS_SPECIAL          => 'Spezial',
	NS_TALK             => 'Klaaf',
	NS_USER             => 'Metmaacher',
	NS_USER_TALK        => 'Metmaacher_Klaaf',
	NS_PROJECT_TALK     => '$1_Klaaf',
	NS_FILE             => 'Datei',
	NS_FILE_TALK        => 'Dateie_Klaaf',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_Klaaf',
	NS_TEMPLATE         => 'Schablon',
	NS_TEMPLATE_TALK    => 'Schablone_Klaaf',
	NS_HELP             => 'Hölp',
	NS_HELP_TALK        => 'Hölp_Klaaf',
	NS_CATEGORY         => 'Saachjrupp',
	NS_CATEGORY_TALK    => 'Saachjruppe_Klaaf',
);

$namespaceAliases = array(
	'Meedije'           => NS_MEDIA,
	'Shpezjal'          => NS_SPECIAL,
	'Medmaacher'        => NS_USER,
	'Medmaacher_Klaaf'  => NS_USER_TALK,
	'Beld'              => NS_FILE,
	'Belld'             => NS_FILE,
	'Belder_Klaaf'      => NS_FILE_TALK,
	'Bellder_Klaaf'     => NS_FILE_TALK,
	'MedijaWikki'       => NS_MEDIAWIKI,
	'MedijaWikki_Klaaf' => NS_MEDIAWIKI_TALK,
	'Hülp'              => NS_HELP,
	'Hülp_Klaaf'        => NS_HELP_TALK,
	'Sachjrop'          => NS_CATEGORY,
	'Saachjrop'         => NS_CATEGORY,
	'Saachjropp'        => NS_CATEGORY,
	'Kattejori'         => NS_CATEGORY,
	'Kategorie'         => NS_CATEGORY,
	'Katejori'          => NS_CATEGORY,
	'Sachjrop_Klaaf'    => NS_CATEGORY_TALK,
	'Saachjroppe_Klaaf' => NS_CATEGORY_TALK,
        'Saachjrupp_Klaaf'  => NS_CATEGORY_TALK,
	'Kattejori_Klaaf'   => NS_CATEGORY_TALK,
	'Kattejorije_Klaaf' => NS_CATEGORY_TALK,
	'Kategorie_Klaaf'   => NS_CATEGORY_TALK,
	'Katejorije_Klaaf'  => NS_CATEGORY_TALK,
);

$separatorTransformTable = array( ',' => "\xc2\xa0", '.' => ',' );
$linkTrail = '/^([äöüėëĳßəğåůæœça-z]+)(.*)$/sDu';

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'Ömleitunge_op_Ömleitunge' ),
	'BrokenRedirects'           => array( 'Ömleitunge_en_et_Leere' ),
	'Disambiguations'           => array( 'Wat-es-dat-Sigge', 'Watt_ėßß_datt?' ),
	'Userlogin'                 => array( 'Enlogge' ),
	'Userlogout'                => array( 'Ußlogge' ),
	'CreateAccount'             => array( 'Aanmelde', 'Medmaacher_wääde', 'Metmaacher_wääde' ),
	'Preferences'               => array( 'Ming_Enstellunge', 'Enstellunge' ),
	'Watchlist'                 => array( 'Ming_Oppassliss', 'Oppassliss' ),
	'Recentchanges'             => array( 'Neuste_Änderunge', 'Änderunge' ),
	'Upload'                    => array( 'Daate_huhlade', 'Huhlade' ),
	'Listfiles'                 => array( 'Datei', 'Dateie' ),
	'Newimages'                 => array( 'Neu_Dateie' ),
	'Listusers'                 => array( 'Medmaacher', 'Metmaacher' ),
	'Listgrouprights'           => array( 'Jrupperääschte', 'Jropperrääschte' ),
	'Statistics'                => array( 'Statistik', 'Shtatißtike' ),
	'Randompage'                => array( 'Zofällije_Sigg' ),
	'Lonelypages'               => array( 'Sigge_ohne_Links_drop' ),
	'Uncategorizedpages'        => array( 'Sigge_ohne_Saachjruppe' ),
	'Uncategorizedcategories'   => array( 'Saachjruppe_ohne_Saachjruppe' ),
	'Uncategorizedimages'       => array( 'Dateie_ohne_Saachjruppe' ),
	'Uncategorizedtemplates'    => array( 'Schablone_ohne_Saachjruppe' ),
	'Unusedcategories'          => array( 'Schablone_ohne_Links_drop' ),
	'Unusedimages'              => array( 'Dateie_ohne_Links_drop' ),
	'Wantedpages'               => array( 'Sigge_fähle' ),
	'Wantedcategories'          => array( 'Saachjruppe_fähle', 'Saachjroppe_fähle' ),
	'Wantedfiles'               => array( 'Dateie_fähle' ),
	'Wantedtemplates'           => array( 'Schablone_fähle' ),
	'Mostlinked'                => array( 'Sigge_met_de_mieste_Links_drop' ),
	'Mostlinkedcategories'      => array( 'Et_miehts_jebruchte_Saachjruppe', 'Et_miehts_jebruchte_Saachjroppe' ),
	'Mostlinkedtemplates'       => array( 'Et_miehts_jebruchte_Schablone' ),
	'Mostimages'                => array( 'Dateie_met_de_mieste_Links_drop' ),
	'Mostcategories'            => array( 'Sigge_met_de_mieste_Saachjroppe', 'Sigge_met_de_mieste_Saachjruppe' ),
	'Mostrevisions'             => array( 'Öff_beärbeit', 'Öff_beärbeidt', 'Off_bearbeit' ),
	'Fewestrevisions'           => array( 'Winnig_beärbeit', 'Winnish_beärbeidt', 'Winnich_bearbeit' ),
	'Shortpages'                => array( 'Koote_Atikelle' ),
	'Longpages'                 => array( 'Lang_Atikelle' ),
	'Newpages'                  => array( 'Neu_Atikelle' ),
	'Ancientpages'              => array( 'Ahl_Atikelle' ),
	'Deadendpages'              => array( 'Sigge_ohne_Links_dren' ),
	'Protectedpages'            => array( 'Siggeschotz' ),
	'Protectedtitles'           => array( 'Tittelschotz' ),
	'Allpages'                  => array( 'All_Sigge' ),
	'Prefixindex'               => array( 'Sigge_met_Aanfang' ),
	'Ipblocklist'               => array( 'Jesperrt', 'Jeshpächt' ),
	'Unblock'                   => array( 'Freijävve', 'Frei_jävve', 'Freijevve', 'Frei_jevve' ),
	'Specialpages'              => array( 'Sondersigge', 'Söndersigge' ),
	'Contributions'             => array( 'Beidräch', 'Beidrääsh' ),
	'Emailuser'                 => array( 'Email', 'E-mail' ),
	'Confirmemail'              => array( 'Email_Bestätije', 'E-mail_Bestätije', 'EmailBestätije', 'E-mailBestätije' ),
	'Whatlinkshere'             => array( 'Wat_noh_hee_link' ),
	'Recentchangeslinked'       => array( 'Änderungen_an_verlinkte_Sigge' ),
	'Movepage'                  => array( 'Ömnenne', 'Ömdäufe' ),
	'Blockme'                   => array( 'Proxy-Sperre' ),
	'Booksources'               => array( 'ISBN', 'Böcher', 'Böösher' ),
	'Categories'                => array( 'Saachjruppe' ),
	'Export'                    => array( 'Expocht' ),
	'Version'                   => array( 'Väsjohn' ),
	'Allmessages'               => array( 'MediaWiki-Appachtemang' ),
	'Log'                       => array( 'Logböcher', 'Logböösher' ),
	'Blockip'                   => array( 'IP-Sperre' ),
	'Undelete'                  => array( 'Zeröckholle' ),
	'Import'                    => array( 'Emport', 'Empocht' ),
	'Lockdb'                    => array( 'Datebank-deeschmaache' ),
	'Unlockdb'                  => array( 'Datebank-opmaache' ),
	'Userrights'                => array( 'Medmaacherrääschte', 'Metmaacherrääschte' ),
	'MIMEsearch'                => array( 'MIME-Typ', 'MIMEtüp' ),
	'FileDuplicateSearch'       => array( 'Dubbel_Dateie' ),
	'Unwatchedpages'            => array( 'Sigge_oohne_Oppasser' ),
	'Listredirects'             => array( 'Ömleitunge' ),
	'Revisiondelete'            => array( 'Version_fottschmieße' ),
	'Unusedtemplates'           => array( 'Nit_jebruchte_Schablone' ),
	'Randomredirect'            => array( 'Zofällije_Ömleitung' ),
	'Mypage'                    => array( 'Ming_Medmaachersigg', 'Ming_Metmaachersigg', 'Medmaachersigg', 'Metmaachersigg' ),
	'Mytalk'                    => array( 'Ming_Klaafsigg', 'Klaaf' ),
	'Mycontributions'           => array( 'Ming_Beidräch', 'Ming_Beidrääsh' ),
	'Listadmins'                => array( 'Köbese', 'Köbeße', 'Wiki-Köbesse' ),
	'Listbots'                  => array( 'Bots' ),
	'Popularpages'              => array( 'Miehts_affjeroofe_Sigge' ),
	'Search'                    => array( 'Sök', 'Söök', 'Söke', 'Sööke' ),
	'Resetpass'                 => array( 'Neu_Passwood' ),
	'Withoutinterwiki'          => array( 'Ohne_Shproche_Lenks' ),
	'MergeHistory'              => array( 'Versione_zosammeschmieße' ),
	'Filepath'                  => array( 'Dateipaad' ),
	'Invalidateemail'           => array( 'Onjöltije_e-mail_Addräß', 'Onjöltije_E-Mail_Adress' ),
	'Blankpage'                 => array( 'Leddijje_Sigge' ),
	'LinkSearch'                => array( 'Websigge_Söke' ),
	'DeletedContributions'      => array( 'Fotjeschmeße' ),
	'Tags'                      => array( 'Makeerunge' ),
	'Activeusers'               => array( 'Aktive', 'AktiveMetmaacher', 'Aktive_Metmaacher', 'AktiveMedmaacher', 'Aktive_Medmaacher' ),
	'RevisionMove'              => array( 'VersioneÖmnänne', 'Versione_Ömnänne', 'VersioneÖmnenne', 'Versione_Ömnenne' ),
	'ComparePages'              => array( 'SiggeVerjliesche', 'Sigge_verjliesche', 'SiggeVerjlieche', 'Sigge_verjieche' ),
	'Badtitle'                  => array( 'UnjewönschSiggetittelle', 'Unjewönsch_Siggetittelle' ),
);

$magicWords = array(
	'redirect'              => array( '0', '#ÖMLEIDE_OP', '#ÖMLEIDE', '#LEIDT_ÖM_OP', '#ÖMLEIDUNG', '#WEITERLEITUNG', '#REDIRECT' ),
	'nogallery'             => array( '0', '__KEIN_JALLERIE__', '__KEINE_GALERIE__', '__NOGALLERY__' ),
	'toc'                   => array( '0', '__ENHALLT__', '__INHALTSVERZEICHNIS__', '__TOC__' ),
	'img_right'             => array( '1', 'rähß', 'räts', 'rechts', 'right' ),
	'img_left'              => array( '1', 'lengks', 'lenks', 'links', 'left' ),
	'language'              => array( '0', '#SHPROOCH:', '#SPROCH:', '#SPRACHE:', '#LANGUAGE:' ),
	'hiddencat'             => array( '1', '__VERSHTOCHE_SAACHJRUPP__', '__VERSTECKTE_KATEGORIE__', '__WARTUNGSKATEGORIE__', '__HIDDENCAT__' ),
);

$imageFiles = array(
    'button-italic'   => 'ksh/button_S_italic.png',
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Dun de Links ungerstriche:',
'tog-highlightbroken'         => 'Zeich de Links op Sigge, die et noch nit jitt, esu met: „<a href="" class="new">Lemma</a>“ aan.<br />Wann De dat nit wells, weed et esu: „Lemma<a href="" class="internal">?</a>“ jezeich.',
'tog-justify'                 => 'Dun de Avschnedde em Blocksatz aanzeije',
'tog-hideminor'               => 'Dun de klein Mini-Änderunge (<strong>M</strong>) en de Liss  met „{{int:Recentchanges}}“ <strong>nit</strong> aanzeije',
'tog-hidepatrolled'           => 'Dun de nohjeloorte Änderunge en de „{{int:recentchanges}}“ eez ens <strong>nit</strong> aanzeije',
'tog-newpageshidepatrolled'   => 'Dun de nohjeloorte Änderunge en de Leß „{{int:newpages}}“ eez ens <strong>nit</strong> aanzeije',
'tog-extendwatchlist'         => 'Verjrößer de Oppassliss för jede Aat vun müjjeliche Änderunge ze zeije, nit nor de neuste',
'tog-usenewrc'                => 'Don de opgemotzte „{{int:Recentchanges}}“ aanzeije (bruch Java_Skripp)',
'tog-numberheadings'          => 'Dun de Üvverschrefte automatisch nummereere',
'tog-showtoolbar'             => 'Zeich de Werkzüchliss zom Ändere aan (bruch Java_Skripp)',
'tog-editondblclick'          => 'Sigge met Dubbel-Klicke ändere (bruch Java_Skripp)',
'tog-editsection'             => 'Maach [{{int:Editsection}}]-Links aan de Avschnedde dran',
'tog-editsectiononrightclick' => 'Avschnedde met Räächs-Klicke op de Üvverschrefte ändere (bruch Java_Skripp)',
'tog-showtoc'                 => 'Zeich en Enhaldsüvversich bei Sigge met mieh wie drei Üvverschrefte dren',
'tog-rememberpassword'        => 'Op Duur enlogge op dämm Kompjuter un för dää Brauser (hält {{PLURAL:$1|för eine Daach|bes op $1 Dääsch|bloß för hück}})',
'tog-watchcreations'          => 'Dun de Sigge, die ich neu aanläje, för ming Oppassliss vürschlage',
'tog-watchdefault'            => 'Dun de Sigge för ming Oppassliss vürschlage, die ich aanpacken un änder',
'tog-watchmoves'              => 'Dun ming selfs ömjenante Sigge automatisch för ming Oppassliss vürschlage',
'tog-watchdeletion'           => 'Dun Sigge, die ich fottjeschmesse han, för ming Oppassliss vürschlage',
'tog-minordefault'            => 'Dun all ming Änderunge jedes Mol als klein Mini-Änderunge vürschlage',
'tog-previewontop'            => 'Zeich de Vör-Aansich üvver däm Feld för dä Tex enzejevve aan.',
'tog-previewonfirst'          => 'Zeich de Vör-Aansich tirek för et eetste Mol beim Bearbeide aan',
'tog-nocache'                 => 'Dun et Sigge Zweschespeichere en Dingem Brauser avschalte',
'tog-enotifwatchlistpages'    => 'Scheck en E-Mail, wann en Sigg us ming Oppassliss jeändert wood',
'tog-enotifusertalkpages'     => 'Scheck mer en E-Mail, wann ming Klaaf Sigg jeändert weed',
'tog-enotifminoredits'        => 'Scheck mer och en E-Mail för de klein Mini-Änderunge',
'tog-enotifrevealaddr'        => 'Zeich dä Andere ming E-Mail Adress aan, en de Benohrichtijunge per E-Mail',
'tog-shownumberswatching'     => 'Zeich de Aanzahl Metmaacher, die op die Sigg am oppasse sin',
'tog-oldsig'                  => 'Esu&nbsp;süht&nbsp;Ding „Ongerschreff“&nbsp;us:',
'tog-fancysig'                => 'Donn de „Ungerschreff“ als Wiki-Tex behandelle (ohne enne automattesche Lengk)',
'tog-externaleditor'          => 'Nemm jedes Mol en extern Editor-Projramm (Doför bruchs de extra Enstellunge op Dingem Kompjutor. Dat es jet för Fachlück. Doh kanns De [http://www.mediawiki.org/wiki/Manual:External_editors mieh drövver lässe])',
'tog-externaldiff'            => 'Nemm jedes Mol en extern Diff-Projramm (Doför bruchs de extra Enstellunge op Dingem Kompjutor. Dat es jet för Fachlück. Doh kanns De [http://www.mediawiki.org/wiki/Manual:External_editors mieh drövver lässe])',
'tog-showjumplinks'           => '„Jangk-noh“-Links usjevve, die bei em „Zojang ohne Barrikad“ helfe dun',
'tog-uselivepreview'          => 'Dun de „Lebendije Vör-Aansich“ zeije (em Usprobierstadium, un bruch Java_Skripp)',
'tog-forceeditsummary'        => 'Froch noh, wann en däm Feld „Koot zosammejefass, Quell“ beim Avspeichere nix dren steiht',
'tog-watchlisthideown'        => 'Dun ming eije Änderunge <strong>nit</strong> en minger Oppassliss aanzeije',
'tog-watchlisthidebots'       => 'Dun jedes Mol dä Bots ehr Änderunge <strong>nit</strong> en minger Oppassliss zeije',
'tog-watchlisthideminor'      => 'Dun jedes Mol de klein Mini-Änderunge <strong>nit</strong> en minger Oppassliss zeije',
'tog-watchlisthideliu'        => 'Enjeloggte Metmaacher ier Änderunge jedesmol <strong>nit</strong> en minger Oppassliss aanzeije',
'tog-watchlisthideanons'      => 'Namelose Metmaacher ier Änderunge jedesmol <strong>nit</strong> en minger Oppassliss aanzeije',
'tog-watchlisthidepatrolled'  => 'Dun de nohjeloorte Änderunge et eez ens <strong>nit</strong> en minger Oppassliss aanzeije',
'tog-nolangconversion'        => 'Sprochevariante nit ömwandele',
'tog-ccmeonemails'            => 'Scheck mer en Kopie, wann ich en <i lang="en">e-mail</i> an ene andere Metmaacher scheck',
'tog-diffonly'                => 'Zeich beim Versione Verjliche nur de Ungerscheide aan (ävver pack nit noch de janze Sigg dodronger)',
'tog-showhiddencats'          => 'Donn de verstoche Saachjroppe aanzeije',
'tog-noconvertlink'           => 'Don de Tittele nit ümwandelle',
'tog-norollbackdiff'          => 'Donn noh „{{int:Rollback}}“ de Ungerscheide nit aanzeije',

'underline-always'  => 'jo, ongershtriishe',
'underline-never'   => 'nä',
'underline-default' => 'nemm dem Brauser sing Enstellung',

# Font style option in Special:Preferences
'editfont-style'     => 'De Zoot Schreff en däm Feld för der Sigge iere Täx erin ze schriive:',
'editfont-default'   => 'Em Brauser sing Ennschtällung',
'editfont-monospace' => 'En SchievMaschineSchreff',
'editfont-sansserif' => 'En Jrotesk-Schreff',
'editfont-serif'     => 'En Schreff met Serife',

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
'category_header'                => 'Atikkele en dä Saachjrupp „$1“',
'subcategories'                  => 'Ungerjruppe',
'category-media-header'          => 'Dateie en de Saachjrupp "$1"',
'category-empty'                 => "''En dä Saachjrupp heh sin kein Sigge un kein Dateie.''",
'hidden-categories'              => 'Verstoche Saachjrupp{{PLURAL:$1||e|e}}',
'hidden-category-category'       => 'Verstoche Saachjruppe',
'category-subcat-count'          => 'En dä Saachrupp heh {{PLURAL:$2|es ein Ungerjrupp dren:|sin $2 Ungerjruppe dren, {{PLURAL:$1|un dovun weed heh nur ein|un dovun weede $1 heh|ävver dovun weed heh keine}} aanjezeich:|sinn_er kein Ungerjruppe dren.}}',
'category-subcat-count-limited'  => 'En dä Saachrupp heh {{PLURAL:$1|es ein Ungerjrupp dren:|sin $1 Ungerjruppe dren:|sin kein Ungerjruppe dren.}}',
'category-article-count'         => 'En dä Saachjrupp heh {{PLURAL:$2|es ein Sigg dren:|sin $2 Sigge dren, {{PLURAL:$1|un dovun weed heh nur ein|un dovun weede $1 heh|ävver dovun weed heh keine}} aanjezeich:|sin kein Sigge dren.}}',
'category-article-count-limited' => 'En dä Saachrupp heh {{PLURAL:$1|es ein Sigg dren:|sin $1 Sigge dren:|es kein Sigg dren.}}',
'category-file-count'            => 'En dä Saachrupp heh {{PLURAL:$2|es ein Datei dren:|sin $2 Dateie dren, {{PLURAL:$1|un dovun weed heh nur ein|un dovun weede $1 heh|ävver dovun weed heh kein}} aanjezeich:|es kein Datei dren.}}',
'category-file-count-limited'    => 'En dä Saachrupp heh {{PLURAL:$1|es ein Datei dren:|sin $1 Dateie dren:|es kein Datei dren.}}',
'listingcontinuesabbrev'         => '… (wigger)',
'index-category'                 => 'Sigge, di de Söhkmaschine opnämme sulle',
'noindex-category'               => 'Sigge, di de Söhkmaschine nit opnämme sulle',

'mainpagetext'      => "'''MediaWiki es jetz enstalleet.'''",
'mainpagedocfooter' => 'Luur en et (änglesche) [http://meta.wikimedia.org/wiki/Help:Contents Handboch] wann De wesse wells wie de Wiki-Soffwär jebruch un bedeent wääde muss.

== För dä Aanfang ==
Dat es och all op Änglesch:
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Configuration settings list]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]',

'about'         => 'Üvver',
'article'       => 'Atikkel',
'newwindow'     => '(Mäht e neu Finster op, wann Dinge Brauser dat kann)',
'cancel'        => 'Stopp! Avbreche!',
'moredotdotdot' => 'Mieh&nbsp;…',
'mypage'        => 'ming Metmaacher-Sigg',
'mytalk'        => 'ming Klaafsigg',
'anontalk'      => 'Klaaf för de IP-Adress',
'navigation'    => 'Jangk noh',
'and'           => ', un',

# Cologne Blue skin
'qbfind'         => 'Fingk',
'qbbrowse'       => 'Aanluure',
'qbedit'         => 'Ändere',
'qbpageoptions'  => 'Sigge Enstellunge',
'qbpageinfo'     => 'Üvver de Sigg',
'qbmyoptions'    => 'Ming Sigge',
'qbspecialpages' => 'Spezial Sigge',
'faq'            => 'FAQ',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-addsection'       => 'Ne neue Afschnet onge draan!',
'vector-action-delete'           => 'Fottschmiiße!',
'vector-action-move'             => 'Ömnänne!',
'vector-action-protect'          => 'Schöze!',
'vector-action-undelete'         => 'Zerökholle!',
'vector-action-unprotect'        => 'Schoz ophävve!',
'vector-simplesearch-preference' => 'Donn de verbäßerte Vörschlääsch beim Söke aanschallde (bloß mem Ußsinn „Vektor“ zesamme ze hann)',
'vector-view-create'             => 'Neu Schriive!',
'vector-view-edit'               => 'Ändere!',
'vector-view-history'            => 'Versione zeije!',
'vector-view-view'               => 'Lesse!',
'vector-view-viewsource'         => 'Wikitex aanlooere!',
'actions'                        => 'Akßjuhne',
'namespaces'                     => 'Appachtemangs',
'variants'                       => 'Variante',

'errorpagetitle'    => 'Fähler',
'returnto'          => 'Jangk widder noh: „$1“.',
'tagline'           => 'Us {{GRAMMAR:Dative|{{SITENAME}}}}',
'help'              => 'Hölp',
'search'            => 'Söhke',
'searchbutton'      => 'em Tex',
'go'                => 'Loss Jonn',
'searcharticle'     => 'Sigg',
'history'           => 'Versione',
'history_short'     => 'Versione',
'updatedmarker'     => '(jeändert)',
'info_short'        => 'Infomation',
'printableversion'  => 'För ze Drocke',
'permalink'         => 'Ne Permalink noh heh',
'print'             => 'Drocke',
'view'              => 'Beloore',
'edit'              => 'Ändere',
'create'            => 'Aanläje',
'editthispage'      => 'De Sigg ändere',
'create-this-page'  => 'Neu aanläje',
'delete'            => 'Fottschmieße',
'deletethispage'    => 'De Sigg fottschmieße',
'undelete_short'    => '{{PLURAL:$1|ein Änderung|$1 Änderunge}} zeröckholle',
'viewdeleted_short' => '{{PLURAL:$1|eijn fottjeschmesse Änderung|$1 fottjeschmesse Änderunge|keij fottjeschmesse Änderunge}} beloore',
'protect'           => 'Schötze',
'protect_change'    => 'der Schotz ändere',
'protectthispage'   => 'De Sigg schötze',
'unprotect'         => 'Schotz ophevve',
'unprotectthispage' => 'Dä Schotz för de Sigg ophevve',
'newpage'           => 'Neu Sigg',
'talkpage'          => 'Üvver die Sigg heh schwaade',
'talkpagelinktext'  => 'Klaaf',
'specialpage'       => '{{int:nstab-special}}',
'personaltools'     => 'Metmaacher Werkzüch',
'postcomment'       => 'Neu Avschnedd op de Klaafsigg donn',
'articlepage'       => 'Aanluure wat op dä Sigg drop steiht',
'talk'              => 'Klaafe',
'views'             => 'Aansichte',
'toolbox'           => 'Werkzüch',
'userpage'          => 'Däm Metmaacher sing Sigg aanluure',
'projectpage'       => 'De Projeksigg aanluure',
'imagepage'         => 'De Sigg övver die Dattei aanluure',
'mediawikipage'     => 'Di Sigg med enem Tex uss em Ingerfäjß vum Wiki aanluure',
'templatepage'      => 'De Schablohn ier Sigk aanluere',
'viewhelppage'      => 'De Hölpsigg aanluure',
'categorypage'      => 'De Saachjruppesigg aanluure',
'viewtalkpage'      => 'Klaaf aanluure',
'otherlanguages'    => 'En ander Sproche',
'redirectedfrom'    => '(Ömjeleit vun $1)',
'redirectpagesub'   => 'Ömleitungssigg',
'lastmodifiedat'    => 'Heh di Sigg es et letz aam $1 öm $2 Uhr jeändert woode.',
'viewcount'         => 'De Sigg es bes jetz {{PLURAL:$1|eimol|$1 Mol|keijmol}} avjerofe woode.',
'protectedpage'     => 'Jeschötzte Sigg',
'jumpto'            => 'Jangk noh:',
'jumptonavigation'  => 'Noh de Navigation',
'jumptosearch'      => 'Jangk Söke!',
'view-pool-error'   => 'Deiht uns leid, de ßöörvere han em Momang ze vill ze donn.
Zoh vill Metmaacher versöhke di Sigg heh aanzelohre.
Bes esu joot un waat e Weilsche, ih dat de versöhks, di Sigg noch ens opzeroofe.

$1',
'pool-timeout'      => 'Zick zem Waade affjeloufe, diweil mer op en Sperr am Waade wohre',
'pool-queuefull'    => 'De Schlang zom Waade op der <i lang="en">pool</i> es vull',
'pool-errorunknown' => 'Dä Fähler kenne mer nit',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Üvver {{GRAMMAR:Akkusativ|{{SITENAME}}}}',
'aboutpage'            => 'Project:Üvver {{GRAMMAR:Nom|{{SITENAME}}}}',
'copyright'            => 'Dä Enhald steiht unger de $1.',
'copyrightpage'        => '{{ns:project}}:Lizenz',
'currentevents'        => 'Et Neuste',
'currentevents-url'    => 'Project:Et Neuste',
'disclaimers'          => 'Hinwies',
'disclaimerpage'       => 'Project:Impressum',
'edithelp'             => 'Hölp för et Bearbeide',
'edithelppage'         => 'Help:Hölp',
'helppage'             => 'Help:Hölp',
'mainpage'             => 'Haupsigg',
'mainpage-description' => 'Haupsigg',
'policy-url'           => 'Project:Rejelle',
'portal'               => 'Üvver {{GRAMMAR:Acc|{{SITENAME}}}} för Metmaacher',
'portal-url'           => 'Project:Metmaacher Pooz',
'privacy'              => 'Daateschotz un Jeheimhaldung',
'privacypage'          => 'Project:Daateschotz un Jeheimhaldung',

'badaccess'        => 'Nit jenoch Räächde',
'badaccess-group0' => 'Do häs nit jenoch Räächde.',
'badaccess-groups' => 'Wat Do wells, dat dürfe nor de Metmaacher us {{PLURAL:$2|dä Jrupp „$1“.|eine vun dä Jruppe: $1.|jaa keine Jrupp.}}',

'versionrequired'     => 'De Version $1 vun MediaWiki Soffwär es nüdich',
'versionrequiredtext' => 'De Version $1 vun MediaWiki Soffwär es nüdich, öm die Sigg heh bruche ze künne. Süch op [[Special:Version|de Versionssigg]], wat mer heh för ene Soffwärstand han.',

'ok'                      => 'Jot!',
'pagetitle'               => '$1 — {{SITENAME}}',
'retrievedfrom'           => 'Di Sigg heh stamp vun „$1“.',
'youhavenewmessages'      => 'Do häs $1 ($2).',
'newmessageslink'         => 'neu Metdeilunge op Dinger Klaafsigg',
'newmessagesdifflink'     => 'Ungerscheid zor vürletzte Version',
'youhavenewmessagesmulti' => 'Do häs neu Nachrichte op $1',
'editsection'             => 'Ändere',
'editsection-brackets'    => '[$1]',
'editold'                 => 'Heh die Version ändere',
'viewsourceold'           => 'Wikitex zeije',
'editlink'                => 'ändere',
'viewsourcelink'          => 'aanloore',
'editsectionhint'         => 'Avschnedd $1 ändere',
'toc'                     => 'Enhaldsüvversich',
'showtoc'                 => 'enblende',
'hidetoc'                 => 'usblende',
'collapsible-collapse'    => 'Zohklappe',
'collapsible-expand'      => 'Opklappe',
'thisisdeleted'           => '$1 - aanluure oder widder zeröckholle?',
'viewdeleted'             => '$1 aanzeije?',
'restorelink'             => '{{PLURAL:$1|eijn fottjeschmesse Änderung|$1 fottjeschmesse Änderunge|keij fottjeschmesse Änderunge}}',
'feedlinks'               => 'Abonnomang-Kannal (<i lang="en">Feed</i>):',
'feed-invalid'            => 'Esu en Zoot Abonnomang-Kannal (<i lang="en">Feed</i>) jitt et nit.',
'feed-unavailable'        => 'Mer han kein esu en Abonnomangs-Kannäl (<i lang="en">Feeds</i>) aam Loufe.',
'site-rss-feed'           => 'RSS-Abonnomang-Kannal (Feed) för de „$1“',
'site-atom-feed'          => 'Atom-Abonnomang-Kannal (Feed) för de „$1“',
'page-rss-feed'           => 'RSS-Abonnomang-Kannal (<i lang="en">Feed</i>) för de Sigg „$1“',
'page-atom-feed'          => 'Atom-Abonnomang-Kannal (<i lang="en">Feed</i>) för de Sigg „$1“',
'feed-atom'               => 'Atom',
'feed-rss'                => 'RSS',
'red-link-title'          => '$1 — en Sigg, die et noch nit jitt',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Atikkel',
'nstab-user'      => 'Metmaachersigg',
'nstab-media'     => 'Medijesigg',
'nstab-special'   => 'Extrasigg',
'nstab-project'   => 'Projeksigg',
'nstab-image'     => 'Datei',
'nstab-mediawiki' => 'Tex/Nohreesch',
'nstab-template'  => 'Schablon',
'nstab-help'      => 'Hölp',
'nstab-category'  => 'Saachjrupp',

# Main script and global functions
'nosuchaction'      => 'Die Aufgab (action) kenne mer nit',
'nosuchactiontext'  => '<strong>Na su jet:</strong> De Aufgab us dä URL, die do hinger „<code>action=</code>“ dren steiht, jo die kennt heh dat Wiki jar nit.
Do künns Desch vertipp han, udder ene verkeehte Lengk hät Desch noh heh jebraat.
Et künnt sesch och öm ene Fäähler en dä Sofware fum Wiki handelle.',
'nosuchspecialpage' => "Esu en {{int:nstab-special}} ha'mer nit",
'nospecialpagetext' => 'De aanjefrochte {{int:nstab-special}} jitt et nit, de [[Special:SpecialPages|Leß met de {{int:nstab-special}}e]] helf Der wigger.',

# General errors
'error'                => 'Fähler',
'databaseerror'        => 'Fähler en de Daatebank',
'dberrortext'          => 'Enne Fääler es opjefalle en dä Süntax vun ennem Befääl för de Datebank.
Dat künnd_enne Fääler em Wikki-Projamm sin.
De läzde Date_Bank_Befääl eß jewääse:
<blockquote><code>$1</code></blockquote>
uß dä Funkzjohn: „<code>$2</code>“.
De Datebank mälldt dä Fääler: „<code>$3: $4</code>“.',
'dberrortextcl'        => 'En dä Syntax vun enem Befähl för de Daatebank es
ene Fähler es opjefalle.
Dä letzte Befähl för de Daatebank es jewäse:
<blockquote><code>$1</code></blockquote>
un kohm us däm Projramm singe Funktion: „<code>$2</code>“.
De Datebank meld dä Fähler: „<code>$3: $4</code>“.',
'laggedslavemode'      => '<strong>Opjepass:</strong> Künnt sin, dat heh nit dä neuste Stand vun dä Sigg aanjezeich weed.',
'readonly'             => 'De Daatebank es jesperrt',
'enterlockreason'      => 'Jevv aan, woröm un för wie lang dat de Daatebank jesperrt wääde soll',
'readonlytext'         => 'De Daatebank es jesperrt. Neu Saache dren avspeichere jeiht jrad nit, un ändere och nit. Dä Jrund: „$1“',
'missing-article'      => 'Dä Tex för de Sigg „$1“ $2 kunnte mer nit en de Daatebank finge.
De Sigg es villeich fottjeschmesse oder ömjenannt woode.
Wann dat nidd esu sin sollt, dann hadder villeich ene Fähler en de Soffwär jefunge.
Verzällt et enem [[Special:ListUsers/sysop|Wiki_Köbes]],
un doht em och de URL vun dä Sigg heh sage.',
'missingarticle-rev'   => '(Version Numero: $1)',
'missingarticle-diff'  => '(Ongerscheed zwesche de Versione $1 un $2)',
'readonly_lag'         => 'De Daatebank es för en koote Zigg automattesch jesperrt, för de Daate vun de ongerjeodente Rääschner mem Houprääschner avzejliiche.',
'internalerror'        => 'De Wiki-Soffwär hät ene Fähler jefunge',
'internalerror_info'   => 'Enne ennere Fäähler en de ẞoffwäer es opjetrodde: $1',
'fileappenderrorread'  => 'Mer kunnte „$1“ nit lässe, beim Aanhänge.',
'fileappenderror'      => 'Mer kunnte „$1“ nit aan „$2“ aanhange.',
'filecopyerror'        => 'Kunnt de Datei „$1“ nit noh „$2“ kopeere.',
'filerenameerror'      => 'Kunnt de Datei „$1“ nit op „$2“ ömdäufe.',
'filedeleteerror'      => 'Kunnt de Datei „$1“ nit fottschmieße.',
'directorycreateerror' => 'Dat Verzeichnis „$1“ kunnte mer nit aanläje.',
'filenotfound'         => 'Kunnt de Datei „$1“ nit finge.',
'fileexistserror'      => 'Die Datei „$1“ kunnt mer nit neu schrieve. Se eß ald doh.',
'unexpected'           => 'Domet hät keiner jerechnet: „$1“=„$2“',
'formerror'            => 'Dat es donevve jejange: Wor nix, met däm Fomular.',
'badarticleerror'      => 'Dat jeiht met heh dä Sigg nit ze maache.',
'cannotdelete'         => 'De Sigg oder de Datei „$1“ fottzeschmieße es nit müjjelich. Maach sin, dat ene andere Metmaacher flöcker wor, hät et vürher jedon, un jetz es se ald fott.',
'badtitle'             => 'Verkihrte Üvverschreff',
'badtitletext'         => 'De Üvverschreff es esu nit en Odenung. Et muss jet dren stonn.
Et künnt sin, dat ein vun de speziell Zeiche dren steiht,
wat en Üvverschrefte nit erlaub es.
Et künnt ussinn, wie ene InterWikiLink,
dat jeiht ävver nit.
Muss De repareere.',
'perfcached'           => 'De Daate heenoh kumme usem Zweschespeicher (Cache) un künnte nit mieh janz de allerneuste sin.',
'perfcachedts'         => 'De Daate heenoh kumme usem Zweschespeicher (Cache) un woodte aam $2 öm $3 opjenumme. Se künnte nit janz de allerneuste sin.',
'querypage-no-updates' => "'''Heh die Sigg weed nit mieh op ene neue Stand jebraat.'''",
'wrong_wfQuery_params' => 'Verkihrte Parameter för: <strong><code>wfQuery()</code></strong><br />
De Funktion es: „<code>$1</code>“<br />
De Aanfroch es: „<code>$2</code>“<br />',
'viewsource'           => 'Wikitex aanluure',
'viewsourcefor'        => 'för de Sigg: „$1“',
'actionthrottled'      => "Dat ka'mer nit esu öff maache",
'actionthrottledtext'  => 'Dat darf mer nor en jeweße Zahl Mole hengerenander maache. Do bes jrad aan de Jrenz jekumme. Kannze jo en e paar Menutte widder probeere.',
'protectedpagetext'    => 'Die Sigg es jeschöz, un mer kann se nit ändere.',
'viewsourcetext'       => 'Heh es dä Sigg ier Wikitex zom Belooere un Koppeere:',
'protectedinterface'   => 'Op dä Sigg heh steiht Tex usem Interface vun de Wiki-Soffwär. Dröm es die jäje Änderunge jeschötz, domet keine Mess domet aanjestallt weed.',
'editinginterface'     => '<strong>Opjepass:</strong>
Op dä Sigg heh steiht Tex uß em Ingerfäiß vun de Wiki-Soffwär. Dröm es
die jäje Änderunge jeschötz, domet keine Mess domet aanjestallt weed.
Nor de Wiki-Köbesse künne se ändere. Denk dran, heh Ändere deit et
Ussinn un de Wööt ändere met dänne et Wiki op de Metmaacher un de
Besöker drop aankütt!

Wann De die en Ding Shprooch övversäze wellß, do jangk op
<code lang="en">[http://translatewiki.net/wiki/Main_Page?setlang=ksh translatewiki.net]</code>,
woh et MediaWiki Ingerfäiß en alle Shprooche översaz weedt.

Wann De weße wells, wat dä Täx heh bedügg, do häß De en Schangß, dat De op
<code lang="en">http://www.mediawiki.org/wiki/Manual:Interface/{{BASEPAGENAMEE}}?setlang=ksh</code>
jet doh drövver fenge kanns, udder op
<code lang="en">http://translatewiki.net/wiki/MediaWiki:{{BASEPAGENAMEE}}/qqq?setlang=ksh</code>',
'sqlhidden'            => "(Dä SQL_Befähl du'mer nit zeije)",
'cascadeprotected'     => 'Die Sigg es jeschöz, un mer kann se nit ändere. Se es en en Schotz-Kaskad enjebonge, zosamme met dä {{PLURAL:$1|Sigg|Sigge}}:
$2',
'namespaceprotected'   => 'Do darfs Sigge em Appachtemang „$1“ nit ändere.',
'customcssjsprotected' => 'Do darfs di Sigg heh nit ändere. Se jehööt enem andere Metmacher un es e Stöck funn dämm sing eije Enstellunge.',
'ns-specialprotected'  => '{{int:nstab-special}}e künne mer nit ändere.',
'titleprotected'       => "Dä Tittel för en Sigg eß verbodde, fum [[User:$1]], un dr Jrond wohr: ''„$2“''",

# Virus scanner
'virus-badscanner'     => "Fääler en de Enstellunge: Dat Projramm ''$1'' fö noh Kompjuterwiere ze söke, dat kenne mer nit.",
'virus-scanfailed'     => 'Dat Söhke eß donevve jejange, dä Kood för dä Fähler es „$1“.',
'virus-unknownscanner' => 'Dat Projamm fö noh Komjuterviere ze sööke kenne mer nit:',

# Login and logout pages
'logouttext'                 => "'''Jetz bes de usjelogg'''

Do künnts heh em Wiki wigger maache, als ene namelose Metmaacher. Do kanns De ävver och [[Special:UserLogin|widder enlogge]], als däselve oder och ene andere Metmaacher.
Künnt sin, dat De de ein oder ander Sigg immer wigger aanjezeich kriss, wie wann de noch enjelogg wörs. Dun Dingem Brauser singe <i lang=\"en\">Cache</i> fottschmieße oder leddich maache, öm us dä Nummer erus ze kumme!",
'welcomecreation'            => '== Dach, $1! ==
Dinge Zojang för heh es do.
Do bes jetz aanjemeldt.
Denk dran, Do künnts Der [[Special:Preferences|Ding Enstellunge heh för {{GRAMMAR:Akk|{{SITENAME}}}} zeräächmaache]].',
'yourname'                   => 'Metmaacher Name:',
'yourpassword'               => 'Passwood',
'yourpasswordagain'          => 'Noch ens dat Passwood',
'remembermypassword'         => 'Op Duur aanmelde (hält {{PLURAL:$1|för eine Daach|bes op $1 Dääsch|bloß för hück}})',
'securelogin-stick-https'    => 'Noh em Enlogge övver HTTPS verbonge blieve.',
'yourdomainname'             => 'Ding Domain',
'externaldberror'            => 'Do wor ene Fähler en de externe Daatebank, oder Do darfs Ding extern Daate nit ändere. Dat Aanmelde jingk jedenfalls donevve.',
'login'                      => 'Enlogge',
'nav-login-createaccount'    => 'Enlogge, Aanmälde',
'loginprompt'                => 'Öm heh enlogge ze künne, muss De de <i lang="en">Cookies</i> en Dingem Brauser enjeschalt han.',
'userlogin'                  => 'Enlogge odder Metmaacher wääde',
'userloginnocreate'          => 'Enlogge',
'logout'                     => 'Uslogge',
'userlogout'                 => 'Uslogge',
'notloggedin'                => 'Nit enjelogg',
'nologin'                    => "Wann De Dich noch nit aanjemeldt häs, dann dun Dich '''$1'''.",
'nologinlink'                => 'neu aanmelde',
'createaccount'              => 'Aanmelde als ene neue Metmaacher',
'gotaccount'                 => "Do bes ald aanjemeldt {{GRAMMAR:en|{{SITENAME}}}}? Dann jangk nohm '''$1'''.",
'gotaccountlink'             => 'Enlogge',
'createaccountmail'          => 'Scheck mer en E-Mail met Passwood',
'createaccountreason'        => 'Jrond:',
'badretype'                  => 'Ding zwëij ennjejovve Paßßwööter sinn nit ejaal. Do muss De Dich för ein entscheide.',
'userexists'                 => 'Ene Metmaacher met däm Name jitt et ald. Schad. Do muss De Der ene andere Name usdenke.',
'loginerror'                 => 'Fähler beim Enlogge',
'createaccounterror'         => 'Kunnt keine Zohjang för der Metmaacher-Name „$1“ aanlääje.',
'nocookiesnew'               => 'Dinge neue Metmaacher Name es enjerich, ävver dat automatisch Enlogge wor dann nix.
Schad.
{{ucfirst:{{GRAMMAR:Nom|{{SITENAME}}}}}} bruch Cookies, öm ze merke, wä enjelogg es.
Wann De Cookies avjeschald häs en Dingem Brauser, dann kann dat nit laufe.
Sök Der ene Brauser, dä et kann, dun se enschalte, un dann log Dich noch ens neu en, met Dingem neue Metmaacher Name un Passwood.',
'nocookieslogin'             => '{{ucfirst:{{GRAMMAR:Nominativ|{{SITENAME}}}}}} bruch <i lang="en">cookies</i> för et Enlogge. Et süht esu us, als hätts De de <i lang="en">cookies</i> avjeschalt. Dun se aanschalte un dann versök et noch ens. Odder söök Der ene Brauser, dä et kann.',
'nocookiesfornew'            => 'Et wood keine Zohjang opjemaat, weil mer nit jeweß sin künne, woh de Daate her kohme.
Dinge Brauser moß <i lang="en">cookies</i> enjeschalldt han.
Donn dat prööfe, donn heh di Sigg norr_ens neu laade, un dann versöhk et norr_ens.',
'noname'                     => 'Dat jeiht nit als ene Metmaacher Name. Jetz muss De et noch ens versöke.',
'loginsuccesstitle'          => 'Dat Enlogge hät jeflupp.',
'loginsuccess'               => "'''Do bes jetz enjelogg {{GRAMMAR:en|{{SITENAME}}}}, un Dinge Name als ene Metmaacher es „$1“.'''",
'nosuchuser'                 => 'Dä Metmaacher Name „$1“ wor verkihrt.
Jroß- un Kleinboochshtabe maache ene Ungerscheid!
<br />
Jetz muss De et noch ens versöke.
Udder donn_[[Special:UserLogin/signup|ene neue Metmaacher aanmelde]].',
'nosuchusershort'            => 'Dä Metmaacher Name „<nowiki>$1</nowiki>“ wor verkihrt. Jetz muss De et noch ens versöke.',
'nouserspecified'            => 'Dat jeiht nit als ene Metmaacher Name',
'login-userblocked'          => 'Heh {{GENDER:$1|dä Kääl|dat Weesch|dä Metmaacher|die Frou|dat}} es jesperrt. Enlogge verbodde.',
'wrongpassword'              => 'Dat Passwood oder dä Metmaacher Name wor verkihrt. Jetz muss De et noch ens versöke.',
'wrongpasswordempty'         => "Dat Passwood ka'mer nit fottlooße. Jetz muss De et noch ens versöke.",
'passwordtooshort'           => 'En Paßwööter {{PLURAL:$1|moß|möße|moß}} winnichstens {{PLURAL:ei|$1|kei}} Zeiche, Zeffer{{PLURAL:$1||e|e}}, un Bochstave dren sin.',
'password-name-match'        => 'Ding Poßwoot moß anders wi Dinge Name als ene Metmaacher sin.',
'password-login-forbidden'   => 'Dä Zohjang met däm Metmaacher-Name un däm Paßwoot es verbodde.',
'mailmypassword'             => 'Passwood verjesse?',
'passwordremindertitle'      => 'Neu Paßwoot för {{GRAMMAR:Dat|{{SITENAME}}}}',
'passwordremindertext'       => 'Jod müjjelich, Do wors et selver,
vun de IP Adress $1,
jedenfalls hät eine aanjefroch, dat
mer Dir e neu Passwood zoschecke soll,
för et Enlogge en {{GRAMMAR:Akk|{{SITENAME}}}} op
{{FULLURL:{{MediaWiki:Mainpage}}}}
($4)

Alsu, e neu Passwood för "$2"
es jetz vürjemerk: "$3".
Do solls De tirek jlich enlogge,
un dat Passwood widder ändere,
wann dat esu Dinge Wonsch wor.
Dat neu Passwood leuf noh {{PLURAL:$5|einem Daach|$5 Dääch|noch hück}} us.
Dä Transport üvver et Netz met e-mail
es unsecher, do künne Fremde metlese,
un winnichstens de Jeheimdeenste dun
dat och. Usserdäm es "$3"
villeich nit esu jod ze merke?

Wann nit Do, söndern söns wä noh däm
neue Passwood verlangk hät, wann De
Dich jetz doch widder aan Ding ahl Passwood
entsenne kanns, jo do bruchs de jar nix
ze don, do kanns De Ding ahl Passwood wigger
bruche, un die e-mail heh, die kanns De
jlatt verjesse.

Ene schöne Jroß vun {{GRAMMAR:Dat|{{SITENAME}}}}.

--
{{SITENAME}}: {{fullurl:{{Mediawiki:mainpage}}}}',
'noemail'                    => 'Dä Metmaacher „$1“ hät en dämm sing Enstellunge kein E-Mail Adress aanjejovve.',
'noemailcreate'              => 'Do moß en jöltijje Adräß för Ding <i lang="en">e-mail</i> aanjävve',
'passwordsent'               => 'E neu Passwood es aan de E-Mail Adress vun däm Metmaacher „$1“ ungerwähs. Meld dich domet aan, wann De et häs. Dat ahle Passwood bliev erhalde un kann och noch jebruch wääde, bes dat De Dich et eetste Mol met däm Neue enjelogg häs.',
'blocked-mailpassword'       => 'Ding IP Adress es blockeet.',
'eauthentsent'               => 'En <i lang="en">e-mail</i> es jetz ungerwähs aan di Adress, die en de Enstellunge steiht. Ih dat <i lang="en">e-mails</i> üvver {{GRAMMAR:Genitiv iere male|{{SITENAME}}}} <i lang="en">e-mail</i>-Knopp verscheck wääde künne, muss de <i lang="en">e-mail</i>-Adress eets ens bestätich woode sin. Wat mer doför maache moß, steiht en dä <i lang="en">e-mail</i> dren, die jrad avjescheck woode es.',
'throttled-mailpassword'     => 'En Erennerung för di Passwood es ungerwähs. Domet ene fiese Möpp keine Dress fabrizeet, passeet dat hüchstens eimol en {{PLURAL:$1|der Stund|$1 Stunde|nidd ens eine Stund}}.',
'mailerror'                  => 'Fähler beim E-Mail Verschecke: $1.',
'acct_creation_throttle_hit' => '<b>Schad.</b>
Besöker fun däm Wiki heh han övver de IP-Addräß, övver di De jraad aam
Netz aam hange bes, övver der letzte Daach (24 Stunde) zosamme jenumme ald
{{PLURAL:$1|eine|$1|keine}} mol enen neuen Metmaacher aanjelaht.
Mieh sin nit müjjelich. Dröm künne Lück, die jraad die IP-Addräß han,
för der Momang nit noch mit Metmaacher neu aanmellde.',
'emailauthenticated'         => 'Ding E-Mail Adress wood aam <strong>$2</strong> öm <strong>$3</strong> Uhr bestätich.',
'emailnotauthenticated'      => 'Ding E-Mail Adress es <strong>nit</strong> bestätich. Dröm kann kein E-Mail aan Dich jescheck wääde för:',
'noemailprefs'               => 'Dun en E-Mail Adress endrage, domet dat et all fluppe kann.',
'emailconfirmlink'           => 'Dun Ding E-Mail Adress bestätije looße',
'invalidemailaddress'        => 'Wat De do als en E-Mail Adress aanjejovve häs, süht noh Dress us. En E-Mail Adress en däm Format, dat jitt et nit. Muss De repareere - oder Do mähs dat Feld leddich un schrievs nix eren. Un dann versök et noch ens.',
'accountcreated'             => 'Aanjemeldt',
'accountcreatedtext'         => 'De Aanmeldung för dä Metmaacher „<strong>$1</strong>“ es durch, kann jetz enlogge.',
'createaccount-title'        => 'Enne neue Metmaacher aanmelde för {{GRAMMAR:Akkusativ|{{SITENAME}}}}',
'createaccount-text'         => 'Einer hät Desch als Medmaacher „$2“ {{GRAMMAR:em|{{SITENAME}}}} aanjemelldt.
Dat es e Wiki, un De fengks et onger däm URL:
 $4
Dat Passwoot „$3“ hät sesch dat Wiki för Disch usjewörfelt.
Don jlisch enlogge un donn et ändere.

Wann Dat all böömesch Dörver för Desch sin, da fojeß heh di
e-mail eijfach. Wann De en däm Wikki nit metmaache wells, och.',
'usernamehasherror'          => 'En Metmaacher iere Name darf dat Zeijche „#“ nit dren vürkumme.',
'login-throttled'            => 'Do häs zo öff, zo vill, un zo lang en de letzde Zick probeet, ennzelogge.
Waat e Wielsche, ih dat De et widder versöhks.',
'loginlanguagelabel'         => 'Sproch: $1',
'suspicious-userlogout'      => "Do bes '''nit''' ußjelogg.
Et süht us, wi wann ene kappodde Brauser udder <i lang=\"en\">proxy</i>ẞööver met Zwescheschpeischer noh däm Ußlogge jefrooch hät.",

# E-mail sending
'php-mail-error-unknown' => 'Nit bekannte Fähler met dä Funxjohn <code lang="en">mail()</code> vum PHP',

# JavaScript password checks
'password-strength'            => 'Dat Passwoot $1 (jeschäz)',
'password-strength-bad'        => 'es <span style="text-transform:uppercase">schlääsch</span>',
'password-strength-mediocre'   => 'es jet schlapp',
'password-strength-acceptable' => 'kammer bruche',
'password-strength-good'       => 'es joot',
'password-retype'              => 'Noch ens dat Passwood',
'password-retype-mismatch'     => 'De Paßwööter sin unejaal',

# Password reset dialog
'resetpass'                 => 'Passwood tuusche udder neu ußjävve',
'resetpass_announce'        => 'De beß jez enjelogg med ennem Zweschepasswoot, wat De övver e-mail krääje häs. Dat kanns De nit einfar_esu behallde. Alsu donn jetz e neu Passwoot för op Duur aanjevve.',
'resetpass_text'            => '<!-- Donn der Täx hee dobei -->',
'resetpass_header'          => 'Neu Passwood faßlääje',
'oldpassword'               => 'Et ahle Passwood:',
'newpassword'               => 'Et neue Passwood:',
'retypenew'                 => 'Noch ens dat neue Passwood:',
'resetpass_submit'          => 'E neu Zweschepasswood övvermeddele un aanmellde',
'resetpass_success'         => 'Passwood jeändert. Jetz küdd_et Enlogge&nbsp;…',
'resetpass_forbidden'       => 'E Passwoot kann nit jeändert wääde.',
'resetpass-no-info'         => 'Do mööts ad enjelogg sin, öm tiräk op di Sigg jonn ze dörve',
'resetpass-submit-loggedin' => 'Passwood tuusche',
'resetpass-submit-cancel'   => 'Nix donn!',
'resetpass-wrong-oldpass'   => 'Dat Zweschepasswood udder dat aktoälle Passwood stemmp nit.
Müjjelesch, Do häs Ding Passwood ald jetuusch, künnt och sin,
Do häs Der enzwesche e neuZweschepasswood jehollt.',
'resetpass-temp-password'   => 'Zweschepasswood:',

# Edit page toolbar
'bold_sample'     => 'Fätte Schreff',
'bold_tip'        => 'Fätte Schreff',
'italic_sample'   => 'Scheive Schreff',
'italic_tip'      => 'Scheive Schreff',
'link_sample'     => 'Anker Tex',
'link_tip'        => 'Ene Link en {{GRAMMAR:Akkusativ|{{SITENAME}}}}',
'extlink_sample'  => 'http://www.example.com/dat_he_oohne_Zwescheräum Donoh dä Anker Tex',
'extlink_tip'     => 'Ene Link noh drusse (denk dran, http:// aan dr Aanfang!)',
'headline_sample' => 'Üvverschreff',
'headline_tip'    => 'Övverschreff om bövverschte Nivvo',
'math_sample'     => 'Heh schriev de Formel en „LaTeX“ Forrem eren',
'math_tip'        => 'En mathematisch Formel',
'nowiki_sample'   => 'Heh kütt dä Tex hen, dä vun de Wiki-Soffwär nit bearbeid, un en Rauh jelooße wääde soll',
'nowiki_tip'      => 'Der Wiki-Code för et Fommatteere üvverjonn',
'image_sample'    => 'Beispill.jpg',
'image_tip'       => 'E Beldche enbaue',
'media_sample'    => 'Beispill.ogg',
'media_tip'       => 'Ene Link op en Tondatei, e Filmche, oder esu jet',
'sig_tip'         => 'Dinge Naame, med de Uhrzigk unn_em Dattum',
'hr_tip'          => 'En Querlinnich',

# Edit pages
'summary'                          => 'Koot Zosammejefass, Quell:',
'subject'                          => 'Üvverschreff - wodröm jeiht et?',
'minoredit'                        => 'Dat es en klein Änderung (mini)',
'watchthis'                        => 'Op die Sigg heh oppasse',
'savearticle'                      => 'De Sigg Avspeichere',
'preview'                          => 'Vör-Ansich',
'showpreview'                      => 'Vör-Aansich zeije',
'showlivepreview'                  => 'Lebendije Vör-Aansich zeije',
'showdiff'                         => 'De Ungerscheide zeije',
'anoneditwarning'                  => 'Weil De nit aanjemeldt bes, weed Ding IP-Adress opjezeichnet wääde.',
'anonpreviewwarning'               => "''Weil De nit enjlogg bes, weed Ding <code lang=\"en\">IP</code>-Addräß zoamme met dä neue Version faßjehallde, wann de heh di Sigg avspeichere deihß.''",
'missingsummary'                   => '<strong>Opjepass:</strong> Do häs nix bei „{{int:summary}}“ enjejovve. Dun noch ens op „{{int:savearticle}}“ klicke, öm Ding Änderunge ohne de Zosammefassung ze Speichere. Ävver besser jiss De do jetz tirek ens jet en!',
'missingcommenttext'               => 'Jevv en „Koot Zosammejefass, Quell“ aan!',
'missingcommentheader'             => "'''Opjepass:''' Do häs kein Üvverschreff för Dinge Beidrach enjejovve. Wann De noch ens op „{{int:savearticle}}“ dröcks, weed Dinge Beidrach der ohne avjespeichert.",
'summary-preview'                  => 'Vör-Aansich vun „Koot Zosammejefass, Quell“:',
'subject-preview'                  => 'Vör-Aansich vun de Üvverschreff:',
'blockedtitle'                     => 'Dä Metmaacher es jesperrt',
'blockedtext'                      => "'''Dinge Metmaacher-Name oder IP Adress es vun „\$1“ jesperrt woode.'''

Als Jrund es enjedrage: „''\$2''“

Do kanns heh em Wiki immer noch lesse. Do sühß ävver di Sigg heh, wann De op rude Links klicks, neu Sigge aanlääje, odder Sigge ändere wells, denn doför bes De jetz jesperrt.

Do kanns met \$1 oder enem andere [[{{MediaWiki:Grouppage-sysop}}|Wiki-Köbes]] üvver dat Sperre schwaade, wann De wells.
Do kanns ävver nor dann „''E-Mail aan dä Metmaacher''“ aanwende, wann De ald en E-Mail Adress en Dinge [[Special:Preferences|Enstellunge]] enjedrage un freijejovve häs un wann et E-mail schecke nit metjesperrt es.

Dun en Ding Aanfroge nenne:
* Dä Wiki-Köbeß, dä jesperrt hät: \$1
* Der Jrond för et Sperre: \$2
* Da wood jesperrt: \$8
* De Sperr soll loufe bes: \$6
* De Nommer vun dä Sperr: #\$5
* Ding IP-Adress is jetz: \$3
* Di Sperr es wäje odde jäje: \$7

Do kanns och noch en et <span class=\"plainlinks\">[{{fullurl:Special:IPBlockList|&action=search&limit=&ip=%23}}\$5 Logboch met de Sperre]</span> loore.",
'autoblockedtext'                  => "'''Ding IP Adress es automattesch jesperrt woode.'''
<br />
'''Se wor vun enem Metmaacher jebruch woode, dä vun „\$1“ jesperrt woode es.'''
<br />
Als Jrund es enjedrage: „''\$2''“

Do kanns heh em Wiki immer noch lesse. Do sühß ävver di Sigg heh, wann De op rude Links klicks, neu Sigge aanlääje, odder Sigge ändere wells, denn doför bes De jetz jesperrt.

Do kanns met \$1 oder enem andere [[{{MediaWiki:Grouppage-sysop}}|Wiki-Köbes]] üvver dat Sperre schwaade, wann De wells.
Do kanns ävver nor dann „''E-Mail aan dä Metmaacher''“ aanwende, wann De ald en E-Mail Adress en Dinge [[Special:Preferences|Enstellunge]] enjedrage un freijejovve häs un wann et E-mail schecke nit metjesperrt es.

Dun en Ding Aanfroge nenne:
* Dä Wiki-Köbeß, dä jesperrt hät: \$1
* Der Jrond för et Sperre: \$2
* Da wood jesperrt: \$8
* De Sperr soll loufe bes: \$6
* De Nommer vun dä Sperr: #\$5
* Ding IP-Adress is jetz: \$3
* Di Sperr es wäje odde jäje: \$7

Do kanns och noch en et <span class=\"plainlinks\">[{{fullurl:Special:IPBlockList|&action=search&limit=&ip=%23}}\$5 {{int:ipblocklist}}]</span> loore.",
'blockednoreason'                  => 'Keine Aanlass aanjejovve',
'blockedoriginalsource'            => 'Dä orjenal Wiki Tex vun dä Sigg „<strong>$1</strong>“ steiht heh drunger:',
'blockededitsource'                => 'Dä Wiki Tex vun <strong>Dinge Änderunge</strong> aan dä Sigg „<strong>$1</strong>“ steiht heh drunger:',
'whitelistedittitle'               => 'Enlogge es nüdich för Sigge ze Ändere',
'whitelistedittext'                => 'Do mööts ald $1, öm heh em Wiki Sigge ändere ze dürfe.',
'confirmedittext'                  => 'Do muss Ding E-Mail Adress ald bestätich han, ih dat De heh Sigge ändere darfs.
Drag Ding E-Mail Adress en Ding [[Special:Preferences|ming Enstellunge]] en, un dun „Dun Ding E-Mail Adress bestätije looße“ klicke.',
'nosuchsectiontitle'               => 'Dä Afschnitt ham_mer nit jefonge',
'nosuchsectiontext'                => 'Do häß versooht, ene Avschnet ze ändere, dä mer janit han.
Et künnt noh woh anders hen ömjetrockwe woode sin, udder eruß jenumme, zig däm Do di Sigg heh aam beloore wohß.',
'loginreqtitle'                    => 'Enlogge es nüdich',
'loginreqlink'                     => 'enjelogg sin',
'loginreqpagetext'                 => 'Do mööts eets ens $1, öm ander Sigge aanzeluure.',
'accmailtitle'                     => 'Passwood verscheck.',
'accmailtext'                      => 'En automattesch un zofällesch neu ußjewörfelt Passwood för dä
Metmaacher „$1“ es aan „$2“ jescheck woode.

Dat Passwoot för dä neue Zojang kanns De op dä {{int:Specialpage}} zom
„[[Special:ChangePassword|{{int:resetpass}}]]“ ändere,
wann De wider enjelogg bes.',
'newarticle'                       => '(Neu)',
'newarticletext'                   => 'Ene Link op en Sigg, wo noch nix drop steiht, weil et se noch jar nit jitt, hät Dich noh heh jebraht.
Öm die Sigg aanzeläje, schriev heh unge en dat Feld eren, un dun dat dann avspeichere.
Luur op de [[{{MediaWiki:Helppage}}|Sigge met Hölp]] noh, wann De mieh dodrüvver wesse wells.
Wann De jar nit heh hen kumme wollts, dann jangk zeröck op die Sigg, wo De herjekumme bes, Dinge Brauser hät ene Knopp doför.',
'anontalkpagetext'                 => '----
<i>Dat heh es de Klaaf Sigg för ene namenlose Metmaacher. Dä hät sich noch keine Metmaacher Name jejovve un
enjerich, ov deit keine bruche. Dröm bruche mer sing IP Adress öm It oder In en uns Lisste fasszehalde.
Su en IP Adress kann vun janz vill Metmaacher jebruch wääde, un eine Metmaacher kann janz flöck
zwesche de ungerscheidlichste IP Adresse wähßele, womöchlich ohne dat hä et merk. Wann Do jetz ene namenlose
Metmaacher bes, un fings, dat heh Saache an Dich jeschrevve wääde, wo Do jar nix met am Hot häs, dann bes Do
wahrscheinlich och nit jemeint. Denk villeich ens drüvver noh, datte Dich [[Special:UserLogin/signup|anmelde]] deis,
domet De dann donoh nit mieh met esu en Ömständ ze dun häs, wie de andere namenlose Metmaacher heh. Wann de aanjemelldt bes un deis [[Special:UserLogin|enlogge]], dann kam_mer Desch och fun alle andere Metmaacher ongerschejde.</i>',
'noarticletext'                    => '<span class="plainlinks">Em Momang es keine Tex op dä Sigg. Jangk en de Texte vun ander Sigge [[Special:Search/{{PAGENAME}}|noh däm Titel söke]], odder [{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} donn en de Logböcher donoh loore], oder [{{FULLURL:{{FULLPAGENAME}}|action=edit}} fang die Sigg aan] ze schrieve, oder jangk zeröck wo de her koms. Do hät Dinge Brauser ene Knopp för.</span>',
'noarticletext-nopermission'       => 'Op dä Sigg es em Momang nix drop.
Do kanns noh däm Tittel vun heh dä Sigg [[Special:Search/{{PAGENAME}}|em Tex op ander Sigge söhke]],
udder en dä zopaß <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} Logbööscher nohloore]</span>.',
'userpage-userdoesnotexist'        => 'Enne Metmaacher „$1“ hammer nit, beß De secher, dat De die Metmaachersigg ändere oder aanläje wellss?.',
'userpage-userdoesnotexist-view'   => 'Ene Metmaacher mem Naame „$1“ hät sesch noch nih aanjemeldt',
'blocked-notice-logextract'        => '{{GENDER:$1|Dä Metmaacher|Dat|Dä Metmaacher|Die Metmaacheren|Et}} es jraad jesperrt.
Heh kütt der neuste Enndraach drövver uss_em Logbooch övver et Metmaacher_Sperre:',
'clearyourcache'                   => "<br clear=\"all\" style=\"clear:both\">
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
däm janze Cache singe Enhald üvver ''Tools?Preferences'' fottschmieße, neuerdings jeiht et och met ''Alt+F5''.",
'usercssyoucanpreview'             => '<b>Tipp:</b> Dun met däm <b style="padding:2px; background-color:#ddd;
color:black">Vör-Aansich Zeije</b>-Knopp usprobeere, wat Ding neu
Metmaacher_CSS/Java_Skripp mäht, ih dat et avspeichere deis!',
'userjsyoucanpreview'              => '<b>Tipp:</b> Dun met däm <b style="padding:2px; background-color:#ddd;
color:black">Vör-Aansich Zeije</b>-Knopp usprobeere, wat Ding neu
Metmaacher_Java_Skripp mäht, ih dat et avspeichere deis!',
'usercsspreview'                   => '<b>Opjepass: Do bes heh nor am Usprobeere, wat Ding
Metmaacher_CSS mäht, et es noch nit jesechert!</b>',
'userjspreview'                    => "<strong>Opjepass:</strong> Do bes heh nor am Usprobeere, wat Ding
Metmaacher_Java_Skripp mäht, et es noch nit jesechert!

<strong>Opjepass:</strong> Noh dem Avspeichere moß de Dingem Brauser noch singe Cache fottschmiiße.
Dat jeit je noh Bauser met ongerscheidleje Knöpp —
beim '''Mozilla''' un em '''Firefox''': ''Strg-Shift-R'' —
em '''Internet Explorer''': ''Strg-F5'' —
för der '''Opera''': ''F5'' —
mem '''Safari''': ''Cmd-Shift-R'' —
un em '''Konqueror''': ''F5'' —
et ess en bunte Welt!",
'sitecsspreview'                   => "'''Opjepass:''' Do bes heh nor am Usprobeere, wat Ding CSS mäht,
et es noch nit jesechert!",
'sitejspreview'                    => '<strong>Opjepass:</strong> Do bes heh nor am Usprobeere, wat Ding
Java_Skripp mäht, et es noch nit jesechert!',
'userinvalidcssjstitle'            => '<strong>Opjepass:</strong> Et jitt kein Ussinn met däm Name: „<strong>$1</strong>“ -
denk dran, dat ene Metmaacher eije Dateie för et Ussinn han kann, un dat die met kleine Buchstave
aanfange dun, alsu etwa: {{ns:user}}:Name/vector.css, un {{ns:user}}:Name/vector.js heiße.',
'updated'                          => '(Aanjepack)',
'note'                             => "'''Opjepass:'''",
'previewnote'                      => "'''Heh kütt nor de Vör-Aansich - Ding Änderunge sin noch nit jesechert!'''",
'previewconflict'                  => 'Heh die Vör-Aansich zeich dä Enhald vum bovvere Texfeld.
Esu wööd dä Atikkel ussinn, wann De n jetz avspeichere däts.',
'session_fail_preview'             => "'''Schad: Ding Änderunge kunnte mer su nix met aanfange.
Versök et jrad noch ens.
Wann dat widder nit flupp, dann versök et ens met [[Special:UserLogout|Uslogge]] un widder Enlogge.'''",
'session_fail_preview_html'        => "'''Schad: Ding Änderunge kunnte mer su nix met aanfange. De Daate vun Dinge Login-Säschen sin nit öntlich erüvver jekumme, oder einfach ze alt.'''

''Dat Wiki heh hät rüh HTML zojelooße, dröm weed de Vör-Aansich nit jezeich. Domet solls De jeschötz wääde - hoffe mer - un Aanjreffe met Java_Skripp jäje Dinge Kompjuter künne Der nix aandun.''

'''Falls för Dich söns alles jod ussüht, versök et jrad noch ens. Wann dat widder nit flupp, dann versök et ens met [[Special:UserLogout|Uslogge]] un widder Enlogge.'''",
'token_suffix_mismatch'            => "'''Ding Änderung ham_mer nit övvernomme. Dinge Brauser hät Sazzeijche em verstoche <i lang=\"en\">Token</i> för et Ändere versout. Dat paßeet och ens, wann enne <i lang=\"en\">Proxy</i> nit fungkßjeneet. Et Affspeichere wör do jefährlesch, do künt dä Sigge_Enhaldt kapott bei jon.'''",
'editing'                          => 'De Sigg „$1“ ändere',
'editingsection'                   => 'Ne Avschnedd vun dä Sigg: „$1“ ändere',
'editingcomment'                   => '„$1“ ändere (ene neue Avschnedd schrieve)',
'editconflict'                     => 'Problemche: „$1“ dubbelt bearbeidt.',
'explainconflict'                  => 'Ene andere Metmaacher hät aan dä Sigg och jet jeändert, un zwar nohdäm Do et Ändere aanjefange häs. Jetz ha\'mer dr Dress am Jang, un Do darfs et widder uszoteere.
<strong>Opjepass:</strong><ul><li>Dat bovvere Texfeld zeich die Sigg esu, wie se jetz em Momang jespeichert es, alsu met de Änderunge vun alle andere Metmaacher, die flöcker wie Do jespeichert han.</li><li>Dat ungere Texfeld zeich die Sigg esu, wie De se selver zoletz zerääch jebrasselt häs.</li></ul>
Do muss jetz Ding Änderunge och in dat <strong>bovvere</strong> Texxfeld eren bränge. Natörlich ohne dä Andere ihr Saache kapott ze maache.
<strong>Nor wat em bovvere Texfeld steiht,</strong> dat weed üvvernomme un avjespeichert, wann De „<b
style="padding:2px; background-color:#ddd; color:black">{{int:savearticle}}</b>“ klicks. Bes dohin kanns De esu off
wie De wells op „<b style="padding:2px; background-color:#ddd; color:black">{{int:showdiff}}</b>“ un „<b
style="padding:2px; background-color:#ddd; color:black">{{int:showpreview}}</b>“ klicke, öm ze pröfe, watte ald   jods jemaat häs.

Alles Klor?<br /><br />',
'yourtext'                         => 'Dinge Tex',
'storedversion'                    => 'De jespeicherte Version',
'nonunicodebrowser'                => "'''Opjepass:'''
Dinge Brauser kann nit öntlich met däm Unicode un singe Buchstave ömjonn.
Bes esu jod un nemm ene andere Brauser för heh die Sigg!",
'editingold'                       => "'''Opjepass!<br />
Do bes en ahle, üvverhollte Version vun dä Sigg heh am Ändere.
Wann De die avspeichere deis,
wie se es,
dann jonn all die Änderunge fleute,
die zickdäm aan dä Sigg jemaht woode sin.
Alsu:
Bes De secher, watte mähs?
'''",
'yourdiff'                         => 'Ungerscheide',
'copyrightwarning'                 => 'Ding Beidräch stonn unger de [[$2]], süch $1. Wann De nit han wells, dat Dinge Tex ömjemodelt weed, un söns wohin verdeilt, dun en hee nit speichere. Mem Avspeichere sähs De och zo, dat et vun Dir selvs es, un/oder Do dat Rääch häs, en hee zo verbreide. Wann et nit stemmp, oder Do kanns et nit nohwiese, kann Dich dat en dr Bau bränge!',
'copyrightwarning2'                => 'De Beidräch {{GRAMMAR:en|{{SITENAME}}}} künne vun andere Metmaacher ömjemodelt
oder fottjeschmesse wääde. Wann Der dat nit rääch es, schriev nix. Et es och nüdich, dat et vun Dir selvs es, oder dat Do dat Rääch häs, et hee öffentlich wigger ze jevve. Süch $1. Wann et nit stemmp, oder Do kanns et nit nohwiese, künnt Dich dat en dr Bau bränge!',
'longpageerror'                    => "'''Janz schlemme Fähler:'''
Dä Tex, dä De heh jescheck häs, dä es '''$1''' Kilobyte jroß.
Dat sin mieh wie '''$2''' Kilobyte. Dat künne mer nit speichere!
'''Maach kleiner Stöcke drus.'''<br />",
'readonlywarning'                  => "'''Opjepass:'''
De Daatebank es jesperrt woode, wo Do ald am Ändere wors.
Dä.
Jetz kanns De Ding Änderunge nit mieh avspeichere.
Dun se bei Dir om Rechner fasshalde un versök et späder noch ens.

Nävvebei, dä Datenbank-Köbes hät för et Sperre och ene Jrund aanjejovve: $1",
'protectedpagewarning'             => "'''Opjepass: Die Sigg heh es jäje Veränderunge jeschötz. Nor de Wiki-Köbesse künne se ändere.'''
Heh kütt der neuste Enndraach em Logbooch för di Sigg:",
'semiprotectedpagewarning'         => "'''Opjepass:''' Die Sigg heh es halv jesperrt, wie mer sage, dat heiß, Do muss aanjemeldt un enjelogg sin, wann De dran ändere wells.
Heh kütt der neuste Enndrach em Logbooch doh drövver:",
'cascadeprotectedwarning'          => "'''Opjepaß:''' Die Sigg es jeschöz, un nur de Wiki-Köbesse künne se ändere. Se es en en Schotz-Kaskad enjebonge, zosamme met dä {{PLURAL:$1|Sigg|Sigge}}:",
'titleprotectedwarning'            => "<span style=\"text-transform:uppercase\"> Opjepaß! </span> Di Sigg heh is jesperrt woode. Bloß [[Special:ListGroupRights|bestemmpte]] Metmaacher dörve di Sigg neu aanläje.'''
Heh kütt der neuste Enndrach em Logbooch doh drövver:",
'templatesused'                    => '{{PLURAL:$1|De Schablon|De $1 Schablone|Kein Schablone}}, die en dä Sigg heh jebruch {{PLURAL:$1|weed|wääde|wääde}}, sinn:',
'templatesusedpreview'             => '{{PLURAL:$1|De Schablon|Schablone|-nix-}} en dä Vör-Aansich heh:',
'templatesusedsection'             => '{{PLURAL:$1|De Schablon|Schablone|-nix-}} en däm Avschnedd heh:',
'template-protected'               => '(jeschöz)',
'template-semiprotected'           => '(halfjeschöz - tabu för neu Metmaacher un ohne Enlogge)',
'hiddencategories'                 => 'Die Sigg heh is en {{PLURAL:$1|de verstoche Saachjrupp: |$1 verstoche Saachjruppe: |keij verstoche Saachjruppe dren.}}',
'edittools'                        => '<!-- Dä Tex hee zeich et Wiki unger däm Texfeld zom „Ändere/Bearbeide“ un beim Texfeld vum „Huhlade“. -->',
'nocreatetitle'                    => 'Neu Sigge Aanläje eß nit einfach esu müjjelesch.',
'nocreatetext'                     => 'Sigge neu aanläje es nor müjjelich, wann de [[Special:UserLogin|enjelogg]] bes. Der ohne kanns De ävver Sigge ändere, die ald do sin.',
'nocreate-loggedin'                => 'Do häs nit dat Rääch, neu Sigge aanzelääje.',
'sectioneditnotsupported-title'    => 'Afschnedde Ändere is nit zohjelohße',
'sectioneditnotsupported-text'     => 'Afschnedde Ändere is en heh dä Sigg nit zohjelohße.',
'permissionserrors'                => 'Dat jeit nit, dat darfs de nit.',
'permissionserrorstext'            => 'Do häs nit dat Rääch, dat ze maache, {{PLURAL:$1|dä Jrund es:|de Jründe sin:|oohne Jrund.}}',
'permissionserrorstext-withaction' => 'Do häs nit dat Rääch $2, {{PLURAL:$1|dä Jrund es:|de Jründe sin:|oohne Jrund.}}',
'recreate-moveddeleted-warn'       => "'''Opjepaß:''' Do bes om bäste Wääsh, en Sigg neu aanzelääje, di doför ald ens fottjeschmeße woode wohr.

Bes förseschtesch un övverlääsch Der, of dat en joode Idee es, di Sigg widder opzemaache. Domet De Bescheid weiß, hee de Endrääsh em Logboch vum Sigge-Ömnänne, un em Logboch vum Sigge-Fottschmieße mem Jrond, woröm di Sigg dohmohls fottjeschmesse woode es:",
'moveddeleted-notice'              => 'Heh di Sigg es fottjeschmeße. E Shtöck uß dä Logböösher fum Sigge-Fottschmieße un fum Sigge-Ömnänne för di Sigg kütt jetz, en dä Hoffnung, dat dat Der hellef.',
'log-fulllog'                      => 'Donn dat janze Logboch aanloore',
'edit-hook-aborted'                => 'Et Ändere wood affjebroche övver ene sujenannte „Hoke“ en de ẞoffwäer.
Ene Jrond weße mer nit.',
'edit-gone-missing'                => 'Kunnt di Sigg nit änndere. Se schingk verschwunde un weed fottjeschemeße woode sin.',
'edit-conflict'                    => 'Dubbelt beärbeit.',
'edit-no-change'                   => 'Do häs ja nix aan dä Sigg jeändert, do dom_mer och nix domet.',
'edit-already-exists'              => 'Kunnt kei neu Sigg aanlääje. Di Sigg jidd_et ald.',

# Parser/template warnings
'expensive-parserfunction-warning'        => "'''Opjepaß:''' Die Sigg heh määt zovill Opwand met Paaser-Funkßjohne.

{{PLURAL:$2|Eine Oproof|Beß $2 Oproofe|Keine Oproof}} es älaup, {{PLURAL:$1|un eine Oproof|ävver $1 Oproofe|un keine Oproof}} määt di Sigg em Momang.",
'expensive-parserfunction-category'       => 'Sigge met zovill Opwand en Paaser-Funkßjohne',
'post-expand-template-inclusion-warning'  => 'Warnung: Heh in di Sigg wääde zo fill Bytes övver Schablone erin jebraat. Nit all di Schablone künne enjbonge wäde.',
'post-expand-template-inclusion-category' => 'Sigge met zoh jruuße Schablone enjebonge',
'post-expand-template-argument-warning'   => 'Opjepaß: Di Sigg heh hät winnischßdens eine Parrammeeter en ennem Schablone-Oprof wat ze jroß weed beim Enfölle. Esu en Parrameetere möße mer övverjonn.',
'post-expand-template-argument-category'  => 'Sigge met övverjange Parrammeeter fun Schablone',
'parser-template-loop-warning'            => 'Schablon roofe sesch em Kringel op: [[$1]]',
'parser-template-recursion-depth-warning' => 'Schablone refe sesch zo öff sellver op ($1)',
'language-converter-depth-warning'        => 'Zoh vill Verschachtelunge (övver $1) beim Täx-Ömwandelle vun ein Shprooch en andere.',

# "Undo" feature
'undo-success' => 'De Änderung könnte mer zeröck nämme. Beloor Der de Ungerscheid un dann donn di Sigg avspeichere, wann De dengks, et es en Oodenung esu.',
'undo-failure' => 'Dat kunnt mer nit zeröck nämme, dä Afschnedd wood enzwesche ald widder beärbeidt.',
'undo-norev'   => "Do ka'mer nix zeröck nämme. Di Version jidd_et nit, odder se es verstoche odder fottjeschmesse woode.",
'undo-summary' => 'De Änderung $1 fum [[Special:Contributions/$2|$2]] ([[User talk:$2|Klaaf]]) zeröck jenomme.',

# Account creation failure
'cantcreateaccounttitle' => 'Kann keine Zojang enrichte',
'cantcreateaccount-text' => "Dä [[User:$3|$3]] hät verbodde, dat mer sich vun dä IP-Adress '''$1''' uß als ene neue Metmaacher aanmelde könne soll.

Als Jrund för et Sperre es enjedraare: ''$2''",

# History pages
'viewpagelogs'           => 'De Logböcher för heh di Sigg beloore',
'nohistory'              => 'Et jitt kei fottjeschmesse, zeröckhollba Versione vun dä Sigg.',
'currentrev'             => 'Neuste Version',
'currentrev-asof'        => 'De neuste Version fum $2 öm $3 Uhr',
'revisionasof'           => 'De Version vum $2 öm $3 Uhr',
'revision-info'          => 'Dat heh es de övverhollte Version $3, {{GENDER:$6|vum|vum|vum Metmaacher|vum|vun dä}} $2 aam $4 öm $5 Uhr afjeshpeichert.',
'previousrevision'       => '← De Version dovör zeije',
'nextrevision'           => 'De Version donoh zeije →',
'currentrevisionlink'    => 'De neuste Version',
'cur'                    => 'met jetz',
'next'                   => 'wigger',
'last'                   => 'met dovör',
'page_first'             => 'Aanfang',
'page_last'              => 'Engk',
'histlegend'             => 'Heh kanns De Versione för et Verjliiche ußsöke: Dun met dä Knöpp die zweij markiere,
zwesche dänne De de Ungerscheid jezeich krije wells, dann dröck „<b style="padding:2px; background-color:#ddd;
color:black">{{int:compareselectedversions}}</b>“ udder „<b style="padding:2px; background-color:#ddd;
color:black">{{int:visualcomparison}}</b>“ udder „<b style="padding:2px; background-color:#ddd;
color:black">{{int:wikicodecomparison}}</b>“ met Dinge Taste, oder klick op ein vun dä Knöpp üvver oder unger de Liss.<br />
Verklierung:
({{int:cur}}) = donn met de neuste Version verjliche,
({{int:last}}) = donn met de Version ein doför verjliche,
<b>M</b> = en klein <b>M</b>ini-Änderung,
Dattum+Uhrzigg = don de Version fun dämm Daach un dä Zigg aanzeije.',
'history-fieldset-title' => 'Wat uß de Verjangeheit ußwähle?',
'history-show-deleted'   => 'blohß fottjeschmeße Versione',
'histfirst'              => 'Ählste',
'histlast'               => 'Neuste',
'historysize'            => '({{PLURAL:$1|1 Byte|$1 Bytes|0 Byte}})',
'historyempty'           => '(leddich)',

# Revision feed
'history-feed-title'          => 'De Versione',
'history-feed-description'    => 'Ähler Versione vun dä Wikisigg',
'history-feed-item-nocomment' => '$1 aam $3 öm $4 Uhr',
'history-feed-empty'          => 'De aanjefrochte Sigg jitt et nit. Künnt sin, dat se enzwesche fottjeschmesse udder ömjenannt woode es. Kanns jo ens [[Special:Search|em Wiki söke looße]], öm de zopass, neu Sigge ze finge.',

# Revision deletion
'rev-deleted-comment'         => '(„Koot Zosammejefass, Quell“ usjeblendt)',
'rev-deleted-user'            => '(Metmaacher Name usjeblendt)',
'rev-deleted-event'           => '(Logboch-Enndraach fottjenomme)',
'rev-deleted-user-contribs'   => '[Däm Metmaacher singe Name udder sing <i lang="en">IP</i>-Addräß wood veschtoche, un heh di Änderung douch nit mieh en de Leß met de Beidrääsch op]',
'rev-deleted-text-permission' => "Die Version fun dä Sigg es '''fottjeschmeße'''.
Wann Ehr en [{{FULLURL:{{#spezial:Log}}/delete|page={{FULLPAGENAMEE}}}} {{lcfirst:{{int:deletionlog}}}}] loore doht, künnt Ehr velleisch mieh do drövver lesse.",
'rev-deleted-text-unhide'     => '{{int:rev-deleted-text-permission}} Als ene Wiki-Köbes kanns De [$1 se ävver doch bekike], wann De wells.',
'rev-suppressed-text-unhide'  => "Die Version fun dä Sigg es '''verschtoche'''.
Wann Ehr en [{{FULLURL:{{#spezial:Log}}/suppress|page={{FULLPAGENAMEE}}}} {{lcfirst:{{int:suppressionlog}}}}] loore doht, künnt Ehr velleisch mieh do drövver lesse.
Als ene Wiki-Köbes kanns De [$1 se ävver doch bekike], wann De wells.",
'rev-deleted-text-view'       => '{{int:rev-deleted-text-permission}} Als ene Wiki-Köbes kanns De se ävver bekike.',
'rev-suppressed-text-view'    => "Die Version fun dä Sigg es '''verschtoche'''.
Als ene Wiki-Köbes kanns De se ävver doch bekike, wann De wells.
Wann Ehr en [{{FULLURL:{{#spezial:Log}}/suppress|page={{FULLPAGENAMEE}}}} {{lcfirst:{{int:suppressionlog}}}}] loore doht, künnt Ehr velleisch mieh do drövver lesse.",
'rev-deleted-no-diff'         => "De kanns de Ongerscheide nit beloore, ein vun de Versione es '''fottjeschmeße'''.
Mieh Einzelheite hät [{{fullurl:{{#special:Log}}/suppress|page={{FULLPAGENAMEE}}}} {{lcfirst:{{int:deletionlog}}}}].",
'rev-suppressed-no-diff'      => "Do kanns der keine Ungerscheid zwesche dä Versione beloore, weil ein dofun '''fottjeschmeße''' es.",
'rev-deleted-unhide-diff'     => "Ein vun de Versione es '''fottjeschmeße'''.
Mieh Einzelheite hät [{{fullurl:{{#special:Log}}/suppress|page={{FULLPAGENAMEE}}}} {{lcfirst:{{int:deletionlog}}}}].
Als ene Wiki_Köbes kanns De [$1 de Ungerscheide ävver aankike] wann De wells.",
'rev-suppressed-unhide-diff'  => "Ein vun de Versione heh es '''verschtoche'''.
Mieh Einzelheite hät [{{fullurl:{{#special:Log}}/suppress|page={{FULLPAGENAMEE}}}} {{lcfirst:{{int:suppressionlog}}}}].
Als ene Wiki_Köbes kanns De [$1 de Ungerscheide ävver aankike] wann De wells.",
'rev-deleted-diff-view'       => "Ein vun de Versione heh es '''fottjeschmeße'''.
Mieh Einzelheite hät [{{fullurl:{{#special:Log}}/delete|page={{FULLPAGENAMEE}}}} {{lcfirst:{{int:deletionlog}}}}].
Als ene Wiki_Köbes kanns De de Ungerscheide ävver aankike wann De wells.",
'rev-suppressed-diff-view'    => "Ein vun de Versione heh es '''verschtoche'''.
Mieh Einzelheite hät [{{fullurl:{{#special:Log}}/suppress|page={{FULLPAGENAMEE}}}} {{lcfirst:{{int:suppressionlog}}}}].
Als ene Wiki_Köbes kanns De de Ungerscheide ävver aankike wann De wells.",
'rev-delundel'                => 'zeije/usblende',
'rev-showdeleted'             => 'zeije',
'revisiondelete'              => 'Versione fottschmieße un widder zeröck holle',
'revdelete-nooldid-title'     => 'Kein Version aanjejovve, oddeer en Stuß-Nommer',
'revdelete-nooldid-text'      => 'Do häs kein Version aanjejovve, womet mer dat maache sulle. Odder de Nommer wohr Stuß, verkeeht, et jitt se nit, odder De wellß de neuste Version fott maache.',
'revdelete-nologtype-title'   => 'Do häs kein Zoot vun Logboch aanjejovve.',
'revdelete-nologtype-text'    => 'Do häs kein Zoot vun Enndrääsh em Logboch aanjejovve, woh mer dat met maache sulle.',
'revdelete-nologid-title'     => 'Dat es ene onjöltijje Enndraach em Logboch.',
'revdelete-nologid-text'      => 'Do häs keine Enndraach em Logboch aanjejovve, woh mer dat met maache sulle, udder dä Enndraach jidd_et jaa_nit.',
'revdelete-no-file'           => 'De aanjejovve Dattei jidd_et nit.',
'revdelete-show-file-confirm' => 'Beß De sescher, dat De de fottjeschmeße Version vun dä Dattei „<nowiki>$1</nowiki>“ vum $2 oö $3 Uhr aanloore wells?',
'revdelete-show-file-submit'  => 'Lohß Jonn!',
'revdelete-selected'          => "'''{{PLURAL:$2|Ein usjewählte Version|$2 usjewählte Versione|Kein Version usjewählt}} vun [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Dä ußjewählte Logboch-Endrach|De Ußjewählte Logboch-Endrähsch}}:'''",
'revdelete-text'              => "'''Dä fottjeschmesse Sigge ehre Enhald kanns De nit mieh aanluure. Se blieve ävver en de Liss met de Versione un en de Logböcher dren.'''
Ene Wiki Köbes kann de fottjeschmessene Krom immer noch aanluere un kann en och widder herholle, usser wann bei
dem Wiki singe Installation dat anders fassjelaht woode es.",
'revdelete-confirm'           => 'Bes esu joot un doon dat beschtääteje, un donn domet ongerschriive, dat De dat donn wells, dat De weiß, wat dobei eruß kütt, un dat De dat och noh de [[{{MediaWiki:Policy-url}}|Rääjelle]] deihß.',
'revdelete-suppress-text'     => "Dat sullt '''blooß''' jedonn wäde för:
* unjenehmesch persöönlesch Daate
*: ''Aanschreffte, Tellefoon- un ander Nummere, <span lang=\"en\">e-mail</span> Adräß, uew.''",
'revdelete-legend'            => 'Dä öffentlije Zojang enschränke',
'revdelete-hide-text'         => 'Dä Tex vun dä Version versteiche',
'revdelete-hide-image'        => 'De Enhallt vun däm Beld versteiche',
'revdelete-hide-name'         => 'Der Förjang, un och der Enndraach uss_em Logboch, versteiche',
'revdelete-hide-comment'      => 'Dä Enhald vun „Koot Zosammejefass, Quell“ usblende',
'revdelete-hide-user'         => 'Däm Bearbeider sing IP Adress oder Metmaacher Name versteiche',
'revdelete-hide-restricted'   => 'Dun dat och för de Wiki-Köbesse esu maache wie för jede Andere',
'revdelete-radio-same'        => '(lohß wi_t eß)',
'revdelete-radio-set'         => 'Jo',
'revdelete-radio-unset'       => 'Nä',
'revdelete-suppress'          => 'Donn dä Jrond och för de Wiki-Köbesse versteische',
'revdelete-unsuppress'        => 'De Beschrängkonge för der widderjehollte Versione ophevve',
'revdelete-log'               => 'Aanlaß odder Jrund:',
'revdelete-submit'            => 'Op de aanjekrützte {{PLURAL:$1|Version|Versione|-nix-}} aanwende',
'revdelete-logentry'          => 'Zojang zo de Versione verändert för „[[$1]]“',
'logdelete-logentry'          => '„[[$1]]“ verstoche udder widder seeschba jemaat',
'revdelete-success'           => "'''De Version woot verstoche odder seeschba jemaat.'''",
'revdelete-failure'           => "'''Dä Version ier Seeschbaakeit kunnte mer nit ändere:'''
$1",
'logdelete-success'           => "'''Dä Enndraach em Logboch woot verstoche odder seeschba jemaat.'''",
'logdelete-failure'           => "'''Däm Enndraach em Logboch sing Seeschbaakeit kunnte mer nit ändere:''' $1",
'revdel-restore'              => 'Versteische udder Seeschba maache',
'revdel-restore-deleted'      => 'fottjeschmeße Versione',
'revdel-restore-visible'      => 'seeshtba Versione',
'pagehist'                    => 'Älldere Versione',
'deletedhist'                 => 'Fottjeschmesse Versione',
'revdelete-content'           => 'dä Enhalt fun dä Sigg',
'revdelete-summary'           => 'dä Täx en „{{int:summary}}“',
'revdelete-uname'             => 'dä Metmaachername',
'revdelete-restricted'        => ', och för de Wiki-Köbesse',
'revdelete-unrestricted'      => ', och för de Wiki-Köbesse',
'revdelete-hid'               => '$1 verstoche',
'revdelete-unhid'             => '$1 weder seeschbaa jemaat',
'revdelete-log-message'       => 'hät för {{PLURAL:$1|eij Version|$2 Versione|nix}} $1',
'logdelete-log-message'       => '$1 för {{PLURAL:$2|eine Endraach|$2 Endräch|keine Endraach}} em Logboch',
'revdelete-hide-current'      => 'Ene Fähler es opjetodde beim Verschteische. De Version vum $1 öm $2 Uhr es de neuste Version, un kann dröm nit verschtoche wääde.',
'revdelete-show-no-access'    => 'Ene Fähler es opjetodde beim Aanloore. De Version vum $1 öm $2 Uhr es verschtoche, un De häß dröm keine Zohjang doh drop.',
'revdelete-modify-no-access'  => 'Ene Fähler es opjetodde beim Ändere. De Version vum $1 öm $2 Uhr es verschtoche, un De häß dröm keine Zohjang doh drop.',
'revdelete-modify-missing'    => 'Ene Fähler es opjetodde beim Ändere. En Version met dä Kennong $1 es nit en de Daatebangk.',
'revdelete-no-change'         => "'''Opjepaß:''' Dä Version vum $1 öm $2 Uhr ier Seeschbaakeit es ald esu, wi De se han wells.",
'revdelete-concurrent-change' => 'Ene Fähler es opjetodde beim Ändere. Dä Version vum $1 öm $2 Uhr ier Seeschbaakeit schingk ald esu ze sinn, wi De se han wullts. Looer Der de Logbööscher aan.',
'revdelete-only-restricted'   => 'Beim Verschteische vun däm Enndraach vum $1 öm $2 Uhr es ene Fähler opjefalle:
Do kanns kein Enndrääsch vör de Wiki_Köbeße verschteijsche, der oohne noch en Zoot Verschteijsche dobei ußzewähle.',
'revdelete-reason-dropdown'   => '*Jewöhnlijje Jrönd för et Fottschmiiße
** Vershtüß jääje et Uerhävverrääsch.
** Esu en päsöönlesche Enfomazjuhne sin nit aanjebraach, udder esujaa jääje der Dateschoz.',
'revdelete-otherreason'       => 'Ene andere ov zohsäzlejje Jrund:',
'revdelete-reasonotherlist'   => 'Ene andere Jrund',
'revdelete-edit-reasonlist'   => 'De Jrönde för et Fottschmieße beärbeide',
'revdelete-offender'          => 'Dä Väsion iere Schriever:',

# Suppression log
'suppressionlog'     => 'Et Logboch fum Versteiche',
'suppressionlogtext' => 'Heh noh kütt et Logboch fum Versteiche, woh Versione fun Sigge, Zosammefassunge, Quelle, Metmaachername un Metmaacher-Sperre ze fenge sin, di fun de Oure vun de Öffentleschkeit, un och fun de Wiki-Köbesse verstoche woodte, udder widder zeröck op nommaal jebraat woodte.
Loor en de [[Special:IPBlockList|{{int:ipblocklist}}]] öm ze sinn, wää un wat em Momang wie jesperrt es.',

# Revision move
'moverevlogentry'              => 'hät {{PLURAL:$3|ein Version|$3 Versione|kein Version}} vun „$1“ noh „$2“ ömjenannt.',
'revisionmove'                 => 'Versione vun „$1“ ömnänne',
'revisionmove-backlink'        => '←&nbsp;$1',
'revmove-explain'              => 'Heh di Versione wääde vun „$1“ noh de aanjejovve Sigg ömjetrocke. Wann di Sigg no_nit doh es, weet se aanjelaat. Söns wääde de Versione zwesche die entzotteet, di ald doh sin.',
'revmove-legend'               => 'Zielsigg un Jrond aanjävve',
'revmove-submit'               => 'Lohß jonn!',
'revisionmoveselectedversions' => 'Ußjesoht Versione ömnänne',
'revmove-reasonfield'          => 'Jrond:',
'revmove-titlefield'           => 'Zielsigg:',
'revmove-badparam-title'       => 'Kapodde Aanjabe',
'revmove-badparam'             => 'Dat sin zoh winnisch udder verkehte Parrameetere.
Jang retuur, un donn_et norr_ens probeere.',
'revmove-norevisions-title'    => 'Enem Ziel sing Version es nit jöltesch',
'revmove-norevisions'          => 'Do häs kein Version udder Versione ußjesoht, udder di aanjejovve Version udder Versione jidd_et nit.',
'revmove-nullmove-title'       => 'Dä Tittel es onjöltesch',
'revmove-nullmove'             => 'Mer kann kein Version vun ein Sigg op desellve Sigg ömträcke. Jangk retuur un söhk en annder Sigg uß, wi „$1“.',
'revmove-success-existing'     => '{{PLURAL:$1|Ein Version|$1 Versione|Kei Version}} vun dä Sigg „[[$2]]“ {{PLURAL:$1|es|sin|wood}} noh dä Sigg „[[$3]]“ ömjetrocke{{PLURAL:$1|&#32;woode|&#32;woode|}}, di et allerdengs alld joof.',
'revmove-success-created'      => '{{PLURAL:$1|Ein Version|$1 Versione|Kei Version}} vun dä Sigg „[[$2]]“ {{PLURAL:$1|es|sin|wood}} noh dä neu aanjelaate Sigg „[[$3]]“ ömjetrocke{{PLURAL:$1|&#32;woode.|&#32;woode.|.}}',

# History merging
'mergehistory'                     => 'Versione fun Sigge zosamme schmiiße',
'mergehistory-header'              => 'Met hee dä Sündersigge kanns Du de Versione fun en Urshprongssigg met de Versione fun en neuer Zielsigg zosamme läje. Donn drop aade, dat der Zosammehang fun dä Versione am Engk reschtesch es.',
'mergehistory-box'                 => 'Versione fun zwei Sigge zosamme läje',
'mergehistory-from'                => 'Ursprongssigg:',
'mergehistory-into'                => 'Zielsigg:',
'mergehistory-list'                => 'Versione, di zosamme jelaat wäde künne',
'mergehistory-merge'               => 'De Versione onge künne fun „[[:$1]]“ noh „[[:$2]]“ övverdraare wäde.
Donn de Version makeere bes wohen (inklusive) dat övverdraare wäde sull. Donn drop aachjevve, dat de Ußwahl fott es, wann De op eine fun dä Links klicks.',
'mergehistory-go'                  => 'Don Versione zeije, di mer zosamme läje künne',
'mergehistory-submit'              => 'Versione zosamme läje',
'mergehistory-empty'               => 'Mer han kei Versione för zesammezeläje',
'mergehistory-success'             => '{{PLURAL:$3|Ein Version es|$3 Versione sen|Kei Version wood}} fun „[[:$1]]“ noh „[[:$2]]“ övverdraare un domet zosamme jelaat.',
'mergehistory-fail'                => 'Dat Versione zesamme läje is nit müjjelisch. Don ens di Sigge un de Zigge pröfe!',
'mergehistory-no-source'           => 'En Ursprungssigg „$1“ jidd_et nit.',
'mergehistory-no-destination'      => 'En Zielsigg „$1“ jidd_et nit.',
'mergehistory-invalid-source'      => 'De Ursprungssigg ier Name moß och ene reschtijje Siggetittel sin.',
'mergehistory-invalid-destination' => 'De Zielsigg ier Name moß och ene reschtijje Siggetittel sin.',
'mergehistory-autocomment'         => '„[[:$1]]“ es jetz zosamme jelaat met „[[:$2]]“',
'mergehistory-comment'             => '„[[:$1]]“ zosamme jelaat met „[[:$2]]“ — $3',
'mergehistory-same-destination'    => 'De Quell-Sigg un de Ziel-Sigg dörve nit deselve Sigg sinn.',
'mergehistory-reason'              => 'Der Jrond:',

# Merge log
'mergelog'           => 'Logboch fum Sigge zesamme Läje',
'pagemerge-logentry' => 'Versione beß $3 fun „[[$1]]“ zosamme jelaat met „[[$2]]“',
'revertmerge'        => 'Dat Zosammelääje widder retuur maache',
'mergelogpagetext'   => 'Dat hee is dat Logboch fun de zesammejelaate Versione fun Sigge',

# Diffs
'history-title'            => 'Liss met Versione vun „$1“',
'difference'               => '(Ungerscheid zwesche de Versione)',
'difference-multipage'     => '(Ongerscheide zwesche Sigge)',
'lineno'                   => 'Reih $1:',
'compareselectedversions'  => 'Dun de markeete Version verjliche',
'showhideselectedversions' => 'De ußjewählte Versione aanzeije udder vershteiche',
'editundo'                 => 'De letzte Änderung zeröck nämme',
'diff-multi'               => '(Mer don hee {{PLURAL:$1|eij Version|$1 Versione|keij Version}} dozwesche beim Verjliesche översprenge. Di sin vun jesamp {{PLURAL:$2|einem Metmaacher|$2 Metmaachere|keinem Metmaacher}} jemaat woode)',
'diff-multi-manyusers'     => '({{PLURAL:$1|Ein Version|$1 Versione|kei Version}} dozwesche vun mieh wi {{PLURAL:$2|einem Metmaacher|$2 Metmaachere|keinem Metmaacher}} wääde nit jezeish)',

# Search results
'searchresults'                    => 'Wat beim Söke eruskom',
'searchresults-title'              => 'Noh „$1“ jesoht.',
'searchresulttext'                 => 'Luur en de [[{{MediaWiki:Helppage}}|{{int:help}}]]-Sigge noh, wann de mieh drüvver wesse wells, wie mer {{GRAMMAR:em|{{SITENAME}}}} jet fingk.',
'searchsubtitle'                   => 'För Ding Froch noh „[[:$1|$1]]“ — ([[Special:Prefixindex/$1|Sigge, di met „$1“ annfange]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|Sigge, di Links noh „$1“ han]])',
'searchsubtitleinvalid'            => 'För Ding Froch noh „$1“',
'toomanymatches'                   => 'Dat wore zo vill Treffer, beß esu joot, un donn en annder Ußwahl probeere!',
'titlematches'                     => 'Zopass Üvverschrefte',
'notitlematches'                   => 'Kein zopass Üvverschrefte',
'textmatches'                      => 'Sigge met däm Täx',
'notextmatches'                    => 'Kein Sigg met däm Tex',
'prevn'                            => 'de {{PLURAL:$1|ein|$1|0}} doför zeije',
'nextn'                            => 'de {{PLURAL:$1|läzte |nächste $1|nächste 0}} zeije',
'prevn-title'                      => '{{PLURAL:$1|Et vorijje|De $1 dovör|Es nix dovör}}',
'nextn-title'                      => '{{PLURAL:$1|Et näähßte|De nähßte $1|Kütt nix mieh}}',
'shown-title'                      => 'Zeisch {{PLURAL:$1|ein|$1|nix}} pro Sigg',
'viewprevnext'                     => 'Bläddere: ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'                => 'Enstellunge för et Söhke',
'searchmenu-exists'                => "*Sigg '''[[$1]]'''",
'searchmenu-new'                   => "'''Donn de Sigg „[[:$1|$1]]“ hee em Wiki aanlääje'''",
'searchmenu-new-nocreate'          => '„$1“ es keine jöltije Tittel för en Sigg, udder Do kanns di Sigg nit aanlääje.',
'searchhelp-url'                   => 'Help:Hölp',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Zeich all Sigge, di met däm Tex aanfange]]',
'searchprofile-articles'           => 'Sigge vum Enhalt',
'searchprofile-project'            => 'Hülp- ov Projäk-Sigge',
'searchprofile-images'             => 'Dateie met Medije',
'searchprofile-everything'         => 'Övverall noh',
'searchprofile-advanced'           => 'Extra',
'searchprofile-articles-tooltip'   => 'Söök en de $1',
'searchprofile-project-tooltip'    => 'Söök en de $1',
'searchprofile-images-tooltip'     => 'Söök noh Dateie',
'searchprofile-everything-tooltip' => 'Söök övverall dren, och op de Klaafsigge',
'searchprofile-advanced-tooltip'   => 'Donn en ußjesohte Appachtemangs sööke',
'search-result-size'               => '$1 ({{PLURAL:$2|Eij Woot|$2 Wööter|Keij Woot}})',
'search-result-category-size'      => '{{PLURAL:$1|1 Saach|$1 Saache|0 Saache}} ({{PLURAL:$2|1 Ongerjropp|$2 Ongerjroppe|0 Ongerjroppe}}, {{PLURAL:$3|1 Datei|$3 Dateie|0 Dateie}})',
'search-result-score'              => 'Jeweesch: $1%',
'search-redirect'                  => '(Ömleitung $1)',
'search-section'                   => '(Avschnett $1)',
'search-suggest'                   => 'Häß De „$1“ jemeint?',
'search-interwiki-caption'         => 'Schwesterprojekte',
'search-interwiki-default'         => '$1 hät heh di Träffer jefonge:',
'search-interwiki-more'            => '(mieh)',
'search-mwsuggest-enabled'         => 'met Vürschläsh',
'search-mwsuggest-disabled'        => 'ohne Vürschläsh',
'search-relatedarticle'            => 'Ähnlesch',
'mwsuggest-disable'                => 'Kein automatische Hölp-Liss per Ajax beim Tippe em Feld för et Söke',
'searcheverything-enable'          => 'En alle Appachtemangs söhke',
'searchrelated'                    => 'ähnlesch',
'searchall'                        => 'all',
'showingresults'                   => 'Unge {{PLURAL:$1|weed <strong>eine</strong>|wääde bes <strong>$1</strong>|weed <strong>keine</strong>}} vun de jefunge Endräch jezeich, vun de Nummer <strong>$2</strong> av.',
'showingresultsnum'                => 'Unge {{PLURAL:$3|es ein|sin <strong>$3</strong>|sin <strong>kein</strong>}} vun de jefunge Endräch opjeliss, vun de Nummer <strong>$2</strong> av.',
'showingresultsheader'             => "Jefonge un aanjezeisch: {{PLURAL:$5|'''$1''' vun '''$3'''|'''$1''' beß '''$2''' vun '''$3'''|nix}} för '''$4'''",
'nonefound'                        => '<strong>Opjepass:</strong>
Standatmääßesch don mer nur en bestemmpte Appachtemangs söke.
Donn „<code>all:</code>“ för Ding Wööt saze, wan de en alle Appachtemangs
söke wells, och Klaafsigge, Schabloone, un esu, udder nemm dä zopaß
Appachtemangs-Name.',
'search-nonefound'                 => 'Mer han nix zopaß jefonge för Ding Aanfrohch.',
'powersearch'                      => 'Söke',
'powersearch-legend'               => 'Extra Sööke',
'powersearch-ns'                   => 'Söök en de Apachtemangs:',
'powersearch-redir'                => 'Ömleidunge aanzeije',
'powersearch-field'                => 'Söök noh:',
'powersearch-togglelabel'          => '&nbsp;',
'powersearch-toggleall'            => 'Övverall Höhksche draan maache',
'powersearch-togglenone'           => 'All Höhksche fott nämme',
'search-external'                  => 'Söke fun Ußerhallef',
'searchdisabled'                   => 'Dat Söke hee {{GRAMMAR:en|{{SITENAME}}}} es em Momang avjeschalt.
Dat weed op dänne ẞööver ad ens jemaat, domet de Lass op inne nit ze jroß weed,
un winnichstens dat normale Sigge Oprofe flöck jenoch jeiht.

Ehr künnt esu lang üvver en Sökmaschin vun usserhalv immer noch
Sigge us {{GRAMMAR:Dative|{{SITENAME}}}} finge.
Et es nit jesaht,
dat dänne ihr Daate topaktuell sin,
ävver et es besser wie jaa_nix.',

# Quickbar
'qbsettings'               => '„Flöcke Links“',
'qbsettings-none'          => 'Fottlooße, dat well ich nit sinn',
'qbsettings-fixedleft'     => 'Am linke Rand fass aanjepapp',
'qbsettings-fixedright'    => 'Am rächte Rand fass aanjepapp',
'qbsettings-floatingleft'  => 'Am linke Rand am Schwevve',
'qbsettings-floatingright' => 'Am rächte Rand am Schwevve',

# Preferences page
'preferences'                   => 'ming Enstellunge',
'mypreferences'                 => 'ming Enstellunge',
'prefs-edits'                   => 'Aanzahl Änderunge am Wiki:',
'prefsnologin'                  => 'Nit Enjelogg',
'prefsnologintext'              => 'Do mööts ald <span class="plainlinks">[{{fullurl:{{#special:UserLogin}}|returnto=$1}} enjelogg]</span> sin, öm Ding Enstellunge ze ändere.',
'changepassword'                => 'Passwood *',
'prefs-skin'                    => 'Et Ussinn',
'skin-preview'                  => 'Vör-Ansich',
'prefs-math'                    => 'Mathematisch Formele',
'datedefault'                   => 'Ejaal - kein Vörliebe',
'prefs-datetime'                => 'Datum un Uhrzigge',
'prefs-personal'                => 'De Enstellunge',
'prefs-rc'                      => 'Neuste Änderunge',
'prefs-watchlist'               => 'De Oppassliss',
'prefs-watchlist-days'          => 'Aanzahl Dage för en ming Oppassliss aanzezeije:',
'prefs-watchlist-days-max'      => '(Nit mieh wie 7 Dääch)',
'prefs-watchlist-edits'         => 'Aanzahl Änderunge för en ming verjrößerte Oppassliss aanzezeije:',
'prefs-watchlist-edits-max'     => '(Nit mieh wie 1000)',
'prefs-watchlist-token'         => 'Oppassleß-Kennzeishe:',
'prefs-misc'                    => 'Söns',
'prefs-resetpass'               => 'Dat Passwood ändere',
'prefs-email'                   => '<i lang="en">e-mail</i>',
'prefs-rendering'               => 'Et Sigge-Aanzeije',
'saveprefs'                     => 'Fasshalde',
'resetprefs'                    => 'Zeröck setze',
'restoreprefs'                  => 'Alles op der Shtandatt retuur stelle',
'prefs-editing'                 => 'Beim Bearbeide',
'prefs-edit-boxsize'            => 'Dat Feld zöm Schrieve sull han:',
'rows'                          => 'Reihe:',
'columns'                       => 'Spalte:',
'searchresultshead'             => 'Beim Söke',
'resultsperpage'                => 'Zeich Treffer pro Sigg:',
'contextlines'                  => 'Reihe för jede Treffer:',
'contextchars'                  => 'Zëijshe uß de Ömjävung, pro Rëij:',
'stub-threshold'                => 'Links passend för <a href="#" class="stub">klein Sigge</a> fomateere av esu vill Bytes:',
'stub-threshold-disabled'       => 'Ußjeschalldt',
'recentchangesdays'             => 'Aanzahl Dage en de Liss met de „Neuste Änderunge“ — als Standad:',
'recentchangesdays-max'         => '(Nit mieh wie {{PLURAL:$1|eine Daach|$1 Dääsh|keine Daach}})',
'recentchangescount'            => 'Aanzahl Änderunge en de Leß, als Shtandad:',
'prefs-help-recentchangescount' => 'Dat ömfaß de „{{int:recentchanges}}“, de Versione uß de Fojangeheit, un de Logbööcher.',
'prefs-help-watchlist-token'    => 'Wann dat Feld met enem jeheime Schlößel ußjeföllt es, määt et Wiki ene <i lang="en">RSS</i>-Enspeisung en et Näz för Ding Oppaßleß op.
Wä dä Schlößel weiß, kann ding Oppaßleß lesse. Donn alsu ene seschere un jeheime Wäät doför nämme.
Ene zohfällesch ußjewörfelte Schlößel, dää De nämme künnß, wöhr: <code>$1</code>',
'savedprefs'                    => 'Ding Enstellunge sin jetz jesechert.',
'timezonelegend'                => 'Ziggzon:',
'localtime'                     => 'De Zigg op Dingem Kompjuter:',
'timezoneuseserverdefault'      => 'Nemm däm Server sing Zigg',
'timezoneuseoffset'             => 'Söns jet, jiff dä Ungerscheid aan',
'timezoneoffset'                => 'Dä Ungerscheid¹ es:',
'servertime'                    => 'De Uhrzigg om ẞööver es jetz:',
'guesstimezone'                 => 'Fingk et erus üvver dä Brauser',
'timezoneregion-africa'         => 'Affrikka',
'timezoneregion-america'        => 'Ammerrika',
'timezoneregion-antarctica'     => 'Der Södpool',
'timezoneregion-arctic'         => 'Der Noodpool',
'timezoneregion-asia'           => 'Aasije',
'timezoneregion-atlantic'       => 'De atlantesche Ozejan',
'timezoneregion-australia'      => 'Austraalije',
'timezoneregion-europe'         => 'Europpa',
'timezoneregion-indian'         => 'De indesche Ozejan',
'timezoneregion-pacific'        => 'De shtelle Ozejan',
'allowemail'                    => 'E-Mail vun andere Metmaacher zolooße',
'prefs-searchoptions'           => 'Enstellunge för et Sööke',
'prefs-namespaces'              => 'Appachtemangs',
'defaultns'                     => 'Söns don en hee dä Appachtemengs söhke:',
'default'                       => 'Standaad',
'prefs-files'                   => 'Dateie',
'prefs-custom-css'              => 'Selfsjemaat <i lang="en">Cascading Style Sheet</i>',
'prefs-custom-js'               => 'Selfsjemaat JavaSkripp',
'prefs-common-css-js'           => 'Gemeinsam CSS un JavaSkrepp för all de Bovverfläshe:',
'prefs-reset-intro'             => 'Op dä Sigg kanns De Ding Enstellunge op dämm Wiki singe Shandatt setze lohße. Ävver Opjepaß: Do jidd et keine „Retuur“-Knopp för!',
'prefs-emailconfirm-label'      => 'Beshtätejung övver <i lang="en">e-mail</i>:',
'prefs-textboxsize'             => 'Wi jruuß sull dat Feld för et Afschnedde un Sigge ändere sin',
'youremail'                     => 'E-Mail *',
'username'                      => 'Metmaacher Name:',
'uid'                           => 'Metmaacher Nommer:',
'prefs-memberingroups'          => 'Bes en {{PLURAL:$1|de Metmaacherjrupp:|<strong>$1</strong> Metmaacherjruppe:|keijn Metmaacherjruppe.}}',
'prefs-registration'            => 'Aanjemeldt zick',
'prefs-registration-date-time'  => 'dem $2 öm $3 Uhr',
'yourrealname'                  => 'Dinge richtije Name *',
'yourlanguage'                  => 'Die Sproch, die et Wiki kalle soll:',
'yourvariant'                   => 'Ding Variant',
'yournick'                      => 'Ding&nbsp;„Ongerschreff“&nbsp;*',
'prefs-help-signature'          => '* Beidrääsch op Klaafsigge sullte met „<nowiki>~~~~</nowiki>“ ophüere, dat weed beim Afshpeishere en Ding „Ongerschreff“ met de Uhrzig un em Dattum ömjewandelt.',
'badsig'                        => 'Di Ungeschreff jëijd_esu nit — luer noh dem HTML do_dren un maach et rėshtėsh.',
'badsiglength'                  => 'Ding „Ungerschref“ es zoo lang. Et dörve nit nieh wi {{PLURAL:$1|eij|$1|keij}} Zeische do dren sin.',
'yourgender'                    => 'Do bes *',
'gender-unknown'                => 'wesse mer nit',
'gender-male'                   => 'Kääl odder Jung',
'gender-female'                 => 'Möhn, Weech odder Mädche',
'prefs-help-gender'             => '* Moß mer nit aanjevve, un wann et aanjejovve eß, dann kallt et Wiki övver Desch als „dä Pitter“ udder „dat Tiina“, sönß uns „Metmaacher Pütz“. Dat kritt de janne Welt ze sinn, nit nur Do allein.',
'email'                         => 'E-mail',
'prefs-help-realname'           => '* Dinge richtije Name - kanns De fott looße - wann De en ävver nenne wells, dann weed dä jebruch, öm Ding Beidräch domet ze schmöcke.',
'prefs-help-email'              => 'Ding <i lang="en">e-mail</i> Adress - kanns De fottlooße, un se es för Andre nit ze sinn - mäht et ävver müjjelich, Der e neu Passwoot ze schecke, wann De et ens verjäße häß.',
'prefs-help-email-others'       => 'Do kannß och zohlohße, dat mer Der domet övver Ding Metmaacherklaafsigg en <i lang="en">e-mail</i> schecke kann. Esu künne ander Metmaacher met Der en Kontak kumme, ohne dat se Dinge Name oder Ding <i lang="en">e-Mail</i> Adress kenne mööte.',
'prefs-help-email-required'     => 'Do moß en jöltije E-Mail-Adress aanjevve.',
'prefs-info'                    => 'Jrundlare',
'prefs-i18n'                    => 'Shprooche-Enshtellunge',
'prefs-signature'               => 'Ongerschreff',
'prefs-dateformat'              => 'Dem Dattum sing Fommaat',
'prefs-timeoffset'              => 'Enshtellunge för de Uhrzigge',
'prefs-advancedediting'         => 'Extra Ußwahle',
'prefs-advancedrc'              => 'Extra Ußwahle',
'prefs-advancedrendering'       => 'Extra Ußwahle',
'prefs-advancedsearchoptions'   => 'Extra Ußwahle',
'prefs-advancedwatchlist'       => 'Extra Ußwahle',
'prefs-displayrc'               => 'Ußwahle för et Leßte Aanzeje',
'prefs-displaysearchoptions'    => 'Enstellunge för et Aanzeje',
'prefs-displaywatchlist'        => 'Enstellunge för et Aanzeje',
'prefs-diffs'                   => 'Ongerscheide un Verjliische',

# User preference: e-mail validation using jQuery
'email-address-validity-valid'   => 'De Addräß fö de <i lang="en">e-mail</i> schingk en Odenung',
'email-address-validity-invalid' => 'Jivv en jöltijje Addräß fö de <i lang="en">e-mail</i> en',

# User rights
'userrights'                     => 'Metmaacher ehr Räächde verwalde',
'userrights-lookup-user'         => 'Metmaacherjruppe verwalde',
'userrights-user-editname'       => 'Däm Metmaacher singe Name:',
'editusergroup'                  => 'Metmaacher ier Jruppe un Räächde ändere',
'editinguser'                    => "Heh deihs De {{GENDER:$1|däm|däm|däm Metmaacher|dä|däm}} '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]]) {{GENDER:$1|sing|sing|sing|ier|sing}} Rääschte ändere.",
'userrights-editusergroup'       => 'Metmaacher en Jruppe donn un uß Jruppe nämme',
'saveusergroups'                 => 'Metmaacherjruppe avspeichere',
'userrights-groupsmember'        => 'Dä Metmaacher es en dä Jruppe:',
'userrights-groupsmember-auto'   => 'Dä Metmaacher es automattesch, un der ohne jet ze saare, en:',
'userrights-groups-help'         => 'Do kanns de Jruppe för dä Metmaacher hee ändere, ävver opjepaß:
* E Käßje met Höksche bedüg, dat dä Metmaacher en dä Jrupp es.
* E Käßje ohne Höksche bedüg, dat dä Metmaacher nit en dä Jrupp es.
* E Käßje met Stähnsche donävve bedüg, dat De dat Rääsch zwa ändere, ävver de Änderung nit mieh zeröck nämme kanns.',
'userrights-reason'              => 'Aanlaß odder Jrund:',
'userrights-no-interwiki'        => 'Do häs nit dat Rääsch, Metmaacher ier Rääschte in ander Wikis ze ändere.',
'userrights-nodatabase'          => 'De Datebank „<strong>$1</strong>“ is nit doh, oder se litt op enem andere ẞööver.',
'userrights-nologin'             => 'Do moss als ene Wiki-Köbes [[Special:UserLogin|enjelog sin]], för dat De Metmaacher ier Rääschte ändere kanns.',
'userrights-notallowed'          => 'Do häs nit dat Rääsch, Rääschte aan Metmaacher ze verdeile.',
'userrights-changeable-col'      => 'Jruppe, die De ändere kanns',
'userrights-unchangeable-col'    => 'Jruppe, die De nit ändere kanns',
'userrights-irreversible-marker' => '$1 *',

# Groups
'group'               => 'Jrupp:',
'group-user'          => 'Metmaacher',
'group-autoconfirmed' => 'Bestätichte Metmaacher',
'group-bot'           => 'Bots',
'group-sysop'         => 'Wiki-Köbesse',
'group-bureaucrat'    => 'Bürrokrade',
'group-suppress'      => 'Kontrollettis',
'group-all'           => '(jeede)',

'group-user-member'          => 'Metmaacher',
'group-autoconfirmed-member' => 'Bestätichte Metmaacher',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Wiki-Köbes',
'group-bureaucrat-member'    => 'Bürrokrad',
'group-suppress-member'      => 'Kontrolletti',

'grouppage-user'          => '{{ns:project}}:Metmaacher',
'grouppage-autoconfirmed' => '{{ns:project}}:Bestätichte Metmaacher',
'grouppage-bot'           => '{{ns:project}}:Bots',
'grouppage-sysop'         => '{{ns:project}}:Wiki Köbes',
'grouppage-bureaucrat'    => '{{ns:project}}:Bürrokrad',
'grouppage-suppress'      => '{{ns:project}}:Kontrolletti',

# Rights
'right-read'                  => 'Sigge lesse',
'right-edit'                  => 'Sigge ändere',
'right-createpage'            => 'Neu Sigge, ävver kein Klaafsigge, aanlääje',
'right-createtalk'            => 'Neu Klaafsigge, ävver kein nomaale Sigge, aanlääje',
'right-createaccount'         => 'Ene neue Metmaacher endraage lohße',
'right-minoredit'             => 'Eije Änderung als klein Mini-Änderung makeere',
'right-move'                  => 'Sigge ömnenne',
'right-move-subpages'         => 'Sigge, un ier Ungersigge, zosamme ömnenne',
'right-move-rootuserpages'    => '(Houp)-Metmaacher-Sigg Ömnänne',
'right-movefile'              => 'Dateie ömnenne',
'right-suppressredirect'      => 'Kein automatesche Ömleidung aanlääje beim Ömnenne',
'right-upload'                => 'Dateie huhlade',
'right-reupload'              => 'En Datei ußtuusche, di ussem Wiki kütt',
'right-reupload-own'          => 'En selvs huhjelade Datei ußtuusche',
'right-reupload-shared'       => 'En Datei hee em Wiki huhlade, di en Datei ussem zentraale Wiki ersetz, odder „verstich“',
'right-upload_by_url'         => 'Datei vun enne URL ent Wiki huhlade',
'right-purge'                 => 'Ohne nohzefroge der Enhalt vum Cache för en Sigg fottschmiiße',
'right-autoconfirmed'         => 'Halfjeschözte Sigge ändere',
'right-bot'                   => 'Als enne automatesche Prozeß odder e Projramm behandelt wääde',
'right-nominornewtalk'        => 'Klein Mini-Änderunge aan anderlücks Klaafsigge brenge dänne nit „{{int:newmessageslink}}“',
'right-apihighlimits'         => 'Hütere Jrenze em API',
'right-writeapi'              => 'Darf de <tt>writeAPI</tt> bruche',
'right-delete'                => 'Sigge fottschmieße, die nit besönders vill ahle Versione han',
'right-bigdelete'             => 'Sigge fottschmiiße, och wann se ahle Versione ze baasch han',
'right-deleterevision'        => 'Einzel Versione fun Sigge fottschmiiße un zeröck holle',
'right-deletedhistory'        => 'Fottjeschmeße Versione vun Sigge opleßte lohße — dat zeich ävver nit der Tex aan',
'right-deletedtext'           => 'Fotjeschmeße Täx un Ungerscheid zwesche de verschtoche Versione aanloore',
'right-browsearchive'         => 'Noh fottjeschmesse Sigge söke',
'right-undelete'              => 'Fottjeschmeße Sigge widder zeröck holle',
'right-suppressrevision'      => 'Versione vun Sigge beloore un zeröck holle, di sujaa för de Wiki-Köbesse verstoche sin',
'right-suppressionlog'        => 'De private Logböcher aanloore',
'right-block'                 => 'Medmaacher Sperre, un domet am Schrive hindere',
'right-blockemail'            => 'Metmaacher för et E-Mail Verschecke sperre',
'right-hideuser'              => 'Ene Metmaacher sperre un em singe Name versteiche',
'right-ipblock-exempt'        => 'Es ußjenomme vun automatesche Sperre, vun Sperre fun IP-Adresse, un vun Sperre vun Bereiche vun IP-Adresse',
'right-proxyunbannable'       => 'Es ußjenomme fun automatische Sperre fun Proxy-Servere',
'right-unblockself'           => 'retuur nämme, wam_mer sellver jesperrt woode es',
'right-protect'               => 'Sigge schöze, jeschözde Sigge änndere, un der iere Schoz widder ophevve',
'right-editprotected'         => 'Jeschötzte Sigge ändere, ohne Kaskadeschoz',
'right-editinterface'         => 'Sigge met de Texte ändere, die et Wiki kallt',
'right-editusercssjs'         => 'Anderlücks CSS- un JS-Dateie ändere',
'right-editusercss'           => 'Anderlücks CSS-Dateie ändere',
'right-edituserjs'            => 'Anderlücks JS-Dateie ändere',
'right-rollback'              => 'All de letzte Änderunge fom letzte Metmaacher aan ene Sigg retur maache',
'right-markbotedits'          => 'Retur jemaate Änderonge als Bot-Änderung makeere',
'right-noratelimit'           => 'Kein Beschränkunge dorch Jrenze (<i lang="en">[http://www.mediawiki.org/wiki/Manual:%24wgRateLimits $wgRateLimits]</i>)',
'right-import'                => 'Sigge uß ander Wikis empochteere',
'right-importupload'          => 'Sigge övver et XML-Datei-Huhlade empochteere',
'right-patrol'                => 'Anderlücks Änderunge aan Sigge als „nohjeloort“ makeere',
'right-autopatrol'            => 'De eije Änderunge automattesch als „Nohjeloohrt“ makeere',
'right-patrolmarks'           => 'De „noch nit Nohjeloohrt“ Zeiche en de „{{int:recentchanges}}“ jezeich krijje',
'right-unwatchedpages'        => 'De Liss med Sigge beloore, die ein keine Oppasliss dren sin',
'right-trackback'             => 'Trackback övvermedelle',
'right-mergehistory'          => 'Ahle Versione vun ongerscheedlijje Sigge zosammedonn',
'right-userrights'            => 'Metmaacher ier Rääschte ändere',
'right-userrights-interwiki'  => 'Metmaacher ier Rääschte in ander Wikis ändere',
'right-siteadmin'             => 'De Datebank deeschmaache un opmaache för Änderunge',
'right-reset-passwords'       => 'Enem andere Metmaacher et Paßwoot zeröck setze',
'right-override-export-depth' => 'Beim Sigge Expoteere de Sigge metnämme, woh Lingks drop jon — beß fönef Schredde wigk',
'right-sendemail'             => '<i lang="en">e-mail</i> aan ander Metmaacher schecke',
'right-revisionmove'          => 'Versione ömnänne',
'right-disableaccount'        => 'Zohjäng för Metmaacher still lääje',

# User rights log
'rightslog'      => 'Logboch för Änderunge aan Metmaacher-Räächde',
'rightslogtext'  => 'Hee sin de Änderunge an Metmaacher ehre Räächde opjeliss. Op de Sigge üvver Metmaacher, Wiki-Köbesse, Bürrokrade, Stewards, un esu, kanns De nohlese, wat domet es.',
'rightslogentry' => 'hät däm Metmaacher „$1“ sing Räächde vun „$2“ op „$3“ ömjestallt.',
'rightsnone'     => '(nix)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'di Sigg ze lesse',
'action-edit'                 => 'di Sigg ze ändere',
'action-createpage'           => 'di Sigg aanzeläje',
'action-createtalk'           => 'Klaafsigge aanzeläje',
'action-createaccount'        => 'hee dä neue Metmaacher aanzemelde',
'action-minoredit'            => 'hee di Änderung als klein „mini“ ze makkeere',
'action-move'                 => 'di Sigg ömzebenänne',
'action-move-subpages'        => 'hee di Sigg un ier Ongersigge ömzebenänne',
'action-move-rootuserpages'   => 'enem Metmaacher sing (Houp)-Metmaacher-Sigg ömzenänne',
'action-movefile'             => 'Die Datei ömnenne',
'action-upload'               => 'hee di Datei huhzelade',
'action-reupload'             => 'hee di Datei, di et ald jitt, ußzetuusche',
'action-reupload-shared'      => 'hee di Datei „för“ di ze säze, di et en de jemeinsame Biblijoteek ald jitt',
'action-upload_by_url'        => 'hee di Datei fun en URL erövver trecke ze lohße',
'action-writeapi'             => 'dat API zom Schriive ze bruche',
'action-delete'               => 'hee di Sigg fottzeschmiiße',
'action-deleterevision'       => 'hee di Versijon fottzeschmiiße',
'action-deletedhistory'       => 'vun hee dä Sigg de Leß met de fottjeschmeße Versijone aanzeloore',
'action-browsearchive'        => 'noh fottjeschmeße Sigge ze söke',
'action-undelete'             => 'hee di fottjeschmeße Sigg widder zeröck ze holle',
'action-suppressrevision'     => 'hee di fottjeschmeße Versijon aanzeloore un womööschlesch widder zeröck ze holle',
'action-suppressionlog'       => 'hee dat jeheime Logboch aanzeloore',
'action-block'                => 'hee dämm Metmaacher et Sigge Ändere ze verbeede',
'action-protect'              => 'hee dä Sigg iere Sigge-Schotz ze ändere',
'action-import'               => 'hee di Sigg uss enem andere Wiki ze empotteere',
'action-importupload'         => 'hee di Sigg uss ene huhjelaade Datei ze impotteere',
'action-patrol'               => 'anderlüx Änderunge als „nohjeloort“ ze makeere',
'action-autopatrol'           => 'Ding eije Änderunge sälver als „nohjeloort“ ze makeere',
'action-unwatchedpages'       => 'de Leß met de Sigg en kei Oppassleß aanzeloore',
'action-trackback'            => 'e <i lang="en">trackback</i> enzedraare',
'action-mergehistory'         => 'hee dä Sigg ier Verjangeheit un Versijon met ene andere zosamme ze lääje',
'action-userrights'           => 'alle Metmaacher ier Rääschte ze ändere',
'action-userrights-interwiki' => 'dä Metmaacher fun ander Wikis ier Rääschte ze ändere',
'action-siteadmin'            => 'de Datebank ze sperre udder widder freizejävve',
'action-revisionmove'         => 'Versione ömzenänne',

# Recent changes
'nchanges'                          => '{{PLURAL:$1|Ein Änderung|$1 Änderunge|Kein Änderung}}',
'recentchanges'                     => 'Neuste Änderunge',
'recentchanges-legend'              => 'Enstellunge',
'recentchangestext'                 => 'Op dä Sigg hee sin de neuste Änderunge am Wiki opjeliss.',
'recentchanges-feed-description'    => 'Op dämm Abonnomang-Kannal (<i lang="en">Feed</i>) kannze de {{int:recentchanges}} aam Wiki en Laif un en Färve metloore.',
'recentchanges-label-newpage'       => 'Heh di Sigg es neu dobei jekumme met dä Änderung',
'recentchanges-label-minor'         => 'Heh dat es en Mini-Änderung',
'recentchanges-label-bot'           => 'Di Änderung es fun enem Bot jemaat woode',
'recentchanges-label-unpatrolled'   => 'Heh di Änderung es noch nit nohjeloort',
'rcnote'                            => '{{PLURAL:$1|Heh es de letzte Änderung us|Heh sin de letzte <strong>$1</strong> Änderunge us|Et jit <strong>kei</strong> Änderunge en}} {{PLURAL:$2|däm letzte Daach|de letzte <strong>$2</strong> Dääsch|dä Zick}} vum <strong>$4</strong> aff <strong>$5</strong> Uhr beß jetz.',
'rcnotefrom'                        => 'Hee sin bes <strong>$1</strong> fun de Änderunge zick däm <strong>$3</strong> öm <strong>$4</strong> Uhr opjeliss.',
'rclistfrom'                        => 'Zeich de Änderunge vum $1 aan',
'rcshowhideminor'                   => '$1 klein Mini-Änderunge',
'rcshowhidebots'                    => '$1 de Bots ehr Änderunge',
'rcshowhideliu'                     => '$1 de aanjemeldte Metmaacher ehr Änderunge',
'rcshowhideanons'                   => '$1 de namenlose Metmaacher ehr Änderunge',
'rcshowhidepatr'                    => '$1 de nohjeluurte Änderunge',
'rcshowhidemine'                    => '$1 ming eije Änderunge',
'rclinks'                           => 'Zeich de letzte {{int:pipe-separator}}$1{{int:pipe-separator}} Änderunge us de letzte {{int:pipe-separator}}$2{{int:pipe-separator}} Däch, un dun {{int:pipe-separator}}$3',
'diff'                              => 'Ungerscheid',
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
'rc-enhanced-expand'                => 'Einzelheite zeije (bruch JavaSkripp)',
'rc-enhanced-hide'                  => 'Einzelheite versteiche',

# Recent changes linked
'recentchangeslinked'          => 'Änderunge aan Sigge, wo heh drop jelink es',
'recentchangeslinked-feed'     => 'Änderunge aan Sigge, wo hee drop jelink es',
'recentchangeslinked-toolbox'  => 'Änderunge aan Sigge, wo hee drop jelink es',
'recentchangeslinked-title'    => 'Änderunge aan Sigge, die vun „$1“ uß verlink sin',
'recentchangeslinked-backlink' => '←&nbsp;$1',
'recentchangeslinked-noresult' => 'Et woodte kein Änderunge aan verlinkte Sigge jemaat en dä Zick.',
'recentchangeslinked-summary'  => "Heh di {{int:nstab-special}} hät en Leß met Änderunge aan Sigge, di vun dä aanjejovve Sigg uß verlink sin.
Bei Saachjruppe sen et de Sigge en dä Saachjrupp.
Sigge uß Dinge [[Special:Watchlist|Opaßleß]] sin '''fett''' jeschrevve.",
'recentchangeslinked-page'     => 'Dä Sigg iere Tittel:',
'recentchangeslinked-to'       => 'Zeish de Änderonge fun dä Sigge, wo Lengks noh heh drop sin',

# Upload
'upload'                      => 'Daate huhlade',
'uploadbtn'                   => 'Huhlade!',
'reuploaddesc'                => 'Zeröck noh de Sigg zem Huhlade.',
'upload-tryagain'             => 'Donn ene veränderte Täx övver di Dattei loßßschecke',
'uploadnologin'               => 'Nit Enjelogg',
'uploadnologintext'           => 'Do mööts ald [[Special:UserLogin|enjelogg]] sin, öm Daate huhzelade.',
'upload_directory_missing'    => "<b>Doof:</b>
Dat Fo'zeishnis <code>$1</code> för de huhjelaade Dateie es fott, un dat Websörver Projramm kunnd_et och nit aanlääje.",
'upload_directory_read_only'  => '<b>Doof:</b> En dat Verzeichnis <code>$1</code> för Dateie dren huhzelade, do kann dat Websörver Projramm nix erenschrieve.',
'uploaderror'                 => 'Fähler beim Huhlade',
'upload-recreate-warning'     => "'''Opjepaß: En Dattei met dämm Name es ömjenannt udder fottjeschmeße woode.'''

De Logböösher vum Datteie Ömnänne un Fottschmieße saare doh drövver:",
'uploadtext'                  => "Met däm Formular unge kanns de Belder oder ander Daate huhlade.
Jangk op de [[Special:FileList|Less met de huhjelaade Datteie]], öm esu en Datteie ze beloore udder noh inne ze söhke. De Logbööscher vum [[Special:Log/upload|Huhlaade]] un vum [[Special:Log/delete|Sigge fottschmiiße]] künnte Der och hellefe.

Do kanns dann Ding Werk en Sigge enbinge, met Lengks en dä Aate:
* <code>'''<nowiki>[[</nowiki>{{ns:file}}:'''''Beldche'''''.jpg]]'''</code> — för di janze Dattei ze zeije, wi se eß,
* <code>'''<nowiki>[[</nowiki>{{ns:file}}:'''''Beld'''''.svg | '''''200''''' px]]'''</code> — för e Mini-Beldsche met 200&nbsp;Pixelle Breedt ze zeije,
* <code>'''<nowiki>[[</nowiki>{{ns:file}}:'''''Su süht dat us'''''.png | left | thumb | '''''ene Tex''''' ]]'''</code> — deiht e 200-Pixel-Mini-Beldsche en ene Kaßte aan der lenke (<i lang=\"en\">left</i>) Rand vun dä Sigg un „ene Tex“ onger däm Beldsche,
* <code>'''<nowiki>[[</nowiki>{{ns:media}}:'''''Esu hürt sich dat aan'''''.ogg]]'''</code> — öm tiräk op en Dattei ze Lenke, ohne se aanzzeije.
Usführlich met alle Müjjelichkeite fings de dat bei de Hölp.",
'upload-permitted'            => 'Nor de Dateitüpe <code>$1</code> sin zojelohße.',
'upload-preferred'            => 'De bevörzochte Zoote Dateie: $1.',
'upload-prohibited'           => 'Verbodde Zoote Dateie: $1.',
'uploadlog'                   => 'LogBoch vum Dateie Huhlade',
'uploadlogpage'               => 'Logboch met de huhjelade Dateie',
'uploadlogpagetext'           => 'Hee sin de Neuste huhjelade Dateie opjeliss un wä dat jedon hät.
(En de [[Special:NewFiles|Jalleri met neu Dateie]] kriß De ene Övverbleck med Belldsche)',
'filename'                    => 'Dä Name vun dä Datei',
'filedesc'                    => 'Beschrievungstex un Zosammefassung',
'fileuploadsummary'           => 'Beschrievungstex un Zosammefassung:',
'filereuploadsummary'         => 'Änderunge aan Dateie:',
'filestatus'                  => 'Urhevver Räächsstatus:',
'filesource'                  => 'Quell:',
'uploadedfiles'               => 'Huhjelade Dateie',
'ignorewarning'               => 'Warnung üvverjonn, un Datei trotzdäm avspeichere.',
'ignorewarnings'              => 'Alle Warnunge üvverjonn',
'minlength1'                  => 'Dateiname mösse winnischßtens eij Zeijsche lang sin.',
'illegalfilename'             => 'Schad:
<br />
En däm Name vun dä Datei sin Zeiche enthallde,
die mer en Titele vun Sigge nit bruche kann.
<br />
Sök Der statt „$1“ jet anders us,
un dann muss de dat Dinge noch ens huhlade.',
'badfilename'                 => 'De Datei es en „$1“ ömjedäuf.',
'filetype-mime-mismatch'      => 'Dä Datei ier Ängk vum Name (<code lang="en">.$1</code>) paß nit zo dä <i lang="en">MIME</i>-Zoot (<code lang="en">$2</code>)',
'filetype-badmime'            => 'Dateie mem MIME-Typ „<code>$1</code>“ wulle mer nit huhjelade krijje.',
'filetype-bad-ie-mime'        => 'Di Datei kam_mer nit huhlade, weil der Internet Explorrer se för en „$1“
hallde deiht, wat nit erlaub, un müjjelelscherwies ene jefährlesche Dattei-Typp es.',
'filetype-unwanted-type'      => "Dat Dateifommaat '''„<code>.$1</code>“''' wulle mer nit esu jään huhjelaade krijje. Leever {{PLURAL:$3|ham_mer|ham_mer ein fun|ham_mer nix}}: $2.",
'filetype-banned-type'        => "{{PLURAL:$4|Dat Dateifommaat|De Dateifommaate|}} '''<code>$1</code>''' wulle mer nit huhjelaade krijje. Älaup {{PLURAL:$3|es|sin_er|}}: <code>$2</code>",
'filetype-missing'            => 'Di Datei, di De huhlaade wells, hät keij Fommaat em Name, wi zem Beijspöll „<code>.jpg</code>“, esu jet hätte mer ävver jähn.',
'empty-file'                  => 'Ding huhjelaade Dattei wohr läddesch.',
'file-too-large'              => 'Ding huhjelaade Dattei wohr ze jruß.',
'filename-tooshort'           => 'Dä Name vun dä Dattei es ze koot.',
'filetype-banned'             => 'Di Zoot Dattei es nit zohjelohße.',
'verification-error'          => 'Heh di Dattei es dorsch de Pröövung jefalle.',
'hookaborted'                 => 'Ding Änderung wood vun enem Zohsazprojramm nit zohjelohße.',
'illegal-filename'            => 'Esu ene Name för en Dattei es nit zohjelohße.',
'overwrite'                   => 'Et es nit zohjelohße, Datteie ze övverschrieve, di ald doh sin.',
'unknown-error'               => 'Ene Fähler es opjetrodde, dä mer nit kenne.',
'tmp-create-error'            => 'Mer kunnte kein Zweschedattei aanlääje.',
'tmp-write-error'             => 'Ene Fähler es opjetrodde bem Schrieve en de Zweschedattei.',
'large-file'                  => 'Dateie sullte nit jröößer wääde, wi $1, ävver Ding Datei es $2 jroß.',
'largefileserver'             => 'De Datei es ze jroß. Jrößer wie däm ẞööver sing Enstellung erlaub.',
'emptyfile'                   => 'Wat De hee jetz huhjelade häs, hät kein Daate dren jehatt. Künnt sin, dat De Dich verdon häs, un dä Name wo verkihrt jeschrevve. Luur ens ov De wirklich <strong>die</strong> Datei hee huhlade wells.',
'fileexists'                  => "Et jitt ald en Datei met däm Name.
Wann De op „Datei avspeichere“ klicks, weed se ersetz.
Bes esu jod  un luur Der '''<tt>[[:$1]]</tt>''' aan, wann De nit 100% secher bes.
[[$1|thumb]]",
'filepageexists'              => "En Sigg övver di Datei met däm Tittel '''<tt>[[:$1]]</tt>''' es ald doh, ävver en Datei met däm Name ham_mer nit. Dinge Tex kütt nit automattesch op di Sigg övver di Dattei. Di Sigg moß De wann nüüdesch noch ens extra ändere.
[[$1|thumb]]",
'fileexists-extension'        => "Mer han ald en Dattei, di bahl jenou esu heijß: [[$2|thumb]]
* Huh am laade sim_mer: '''<tt>[[:$1]]</tt>'''
* Ald om ßörve eß:</td><td>'''<tt>[[:$2]]</tt>'''
Bes esu joot, un söök Der ene ander Name fö di Datei us.",
'fileexists-thumbnail-yes'    => "Dat süühd uß, wi wann dat hee en Minni-Beldsche em Breefmarrke-Fommaat (''<span lang=\"en\">thumbnail</span>'') wöhr. [[\$1|thumb]]
Don ens di Dattei '''<tt>[[:\$1]]</tt>''' prööfe.
Wann dat de Orjinaaljrüß es, do moß keij för dat Beld keij extra Vör-Aansich huhjelade wäde.",
'file-thumbnail-no'           => "Dä Name fö di Datei fängk met '''<tt>\$1</tt>''' aan.
Dat süühd uß, wi wann dat en Minni-Beldsche em Breefmarrke-Fommaat
(''<span lang=\"en\">thumbnail</span>'') wöhr. Don ens di Dattei
'''<tt>\$1</tt>''' prööfe, of de nit e besser opjelööß Beld
dofun häß, un don dat met singe Orjinaaljrüß huhlade, wann müjjelesch.
Söns donn besser ene andere Dateiname ußsöke.",
'fileexists-forbidden'        => 'Et jitt ald en Dattei met däm Name, un mer kann se nit övverschriive.
Wann de Ding Dattei trozdämm huhlaade wells, da jangk zeröck un lad se
unger enem andere Name huh. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Et jitt ald en Datei met däm Name em jemeinsame Speicher:
[[File:$1|thumb|center|$1]]
Jangk zeröck un lad Ding Datei unger enem andere Name huh,
wann De se noch han wells.',
'file-exists-duplicate'       => 'Di Dattei hät dersellve Enhallt wi hee di {{PLURAL:$1|Datei|Dateie|}}:',
'file-deleted-duplicate'      => 'En Datei mem sellve Enhallt wi „[[:$1]]“ es ens fottjeschmeße woode. Donn dä Zosammehang em „{{int:dellogpage}}“ nokike, ih dat De se widder huhläds.',
'uploadwarning'               => 'Warnung beim Huhlade',
'uploadwarning-text'          => 'Donn onge dä Täx övver di Dattei ändere, un versöhg_et norr_ens.',
'savefile'                    => 'Datei avspeichere',
'uploadedimage'               => 'hät huhjelade: „[[$1]]“',
'overwroteimage'              => 'hät en neue Version huhjelade vun: „[[$1]]“',
'uploaddisabled'              => 'Huhlade jesperrt',
'copyuploaddisabled'          => 'Et Huhlaade us URLs es afjeschalldt',
'uploadfromurl-queued'        => 'Dat Huhlaade es jiz en de Waadeschlang.',
'uploaddisabledtext'          => 'Et Huhlade es jesperrt.',
'php-uploaddisabledtext'      => 'Et Dateie Huhlade es en PHP affjeschalldt.
Bes esu joot un donn noh de Enshtellung <i lang="en">file_uploads</i> loore.',
'uploadscripted'              => 'En dä Datei es HTML dren oder Code vun enem Skripp, dä künnt Dinge Brauser en do verkihrte Hals krije un usführe.',
'uploadvirus'                 => 'Esu ene Dress:
<br />
En dä Datei stich e Kompjutervirus!
<br />
De Einzelheite: $1',
'upload-source'               => 'Wo de Daate herkumme',
'sourcefilename'              => 'Datei zem huhlade:',
'sourceurl'                   => '<i lang="en">URL</i> för vun eronger ze laade',
'destfilename'                => 'Unger däm Dateiname avspeichere:',
'upload-maxfilesize'          => 'Der jrüütßte müjjelesche Ömfang för en Datei es $1.',
'upload-description'          => 'Övver di Datei',
'upload-options'              => 'Enstellunge för et Laade',
'watchthisupload'             => 'Op di Datei oppasse',
'filewasdeleted'              => 'Unger däm Name wood ald ens en Datei huhjelade. Die es enzwesche ävver widder fottjeschmesse woode. Luur leever eets ens en et $1 ih dat De se dann avspeichere deis.',
'upload-wasdeleted'           => "'''Opjepaß:''' Do bes en Datei huh am lade, di ald doför doh wohr un fottjeschmesse wohdt.

Bes esu joot un don Der övverlääje, of di Dattei mem sellve Name norr_ens huh ze lade en Odenung es.
Hee es dat Logboch met de fotjeschmesse Dateie, met däm Jrond, woröm di Dattei dohmohls fottjeschmesse woode es:",
'filename-bad-prefix'         => "Dä Datei ier Name fängk met '''„$1“''' aan. dat eß fä jewöhnlesch ene Name, dä en dijjitaale Kammerra iere Belder jitt. Esu en Name donn uns esu winnisch verzälle, dat mer se nit jän em Wiki han wulle.
Bes esu joot un jiff dä enne Name, wo mer mieh met aanfange, öm ze wesse, wat en dä Datei dren es.",
'filename-prefix-blacklist'   => ' #<!-- Lohß di Reih jenou esu wie se es! --> <pre>
#  Syntax:
#   * Alles zwesche em #-Zeiche bes nohm Engk vun de Reih es ene Kommäntaa
#   * Jede Reih met jet dren es ene typpesche Aanfang för ene Datteiname,
#   * dä automattesch vun ene Dijjitahlkammera kütt
CIMG # Casio
DSC_ # Nikon
DSCF # Fuji
DSCN # Nikon
DUW # - et ein udder andere mobile Tellefohn -
IMG # - alljemein üplesch -
JD # Jenoptik
MGP # Pentax
PICT # - diverse -
 #</pre> <!-- Lohß di Reih jenou esu wie se es! -->',
'upload-success-subj'         => 'Et Huhlade hät jeflupp',
'upload-success-msg'          => 'Ding vun [$2] huhjelaade Dattei es jäz och hee: [[:{{ns:file}}:$1]]',
'upload-failure-subj'         => 'Probleem bem Huhlaade',
'upload-failure-msg'          => 'Mer hatte e Probleem met Dinge huhjelaade Dattei vun [$2]:

$1',
'upload-warning-subj'         => 'Warnung beim Huhlade',
'upload-warning-msg'          => 'Met däm Huhlaade vun [$2] es jet donevve jejange, Do kanns retuur jon op et [[Special:Upload/stash/$1|Fommulaa zom Huhlaade]] öm dat ze repareere.',

'upload-proto-error'        => 'Verkihrt Protokoll',
'upload-proto-error-text'   => 'Ene URL för en Datei fun huhzelade moß met <code>http://</code> uder <code>ftp://</code> aafange.',
'upload-file-error'         => 'Fääler em Wiki beim Huhlade',
'upload-file-error-text'    => 'Ene ennere Fääler es opjekumme beim Aanläje vun en Datei om Server.
Verzäll et enem [[Special:ListUsers/sysop|Wiki-Köbes]].',
'upload-misc-error'         => 'Dat Huhlaade jing donevve',
'upload-misc-error-text'    => 'Dat Huhlaade jing donevve.
Mer wesse nit woröm.
Pröf de URL un versök et noch ens.
Wann et nit flupp, verzäll et enem [[Special:ListUsers/sysop|Wiki-Köbes]].',
'upload-too-many-redirects' => 'Zoh vill Ömleitunge en däm <i lang="en">URL</i>',
'upload-unknown-size'       => 'Mer weße nit, wi jruuß',
'upload-http-error'         => 'Ene <i lang="en">HTTP</i>-Fäähler es opjetrodde: $1',

# Special:UploadStash
'uploadstash'          => 'Zwescheschpeisscher vum Huhlaade',
'uploadstash-summary'  => 'Heh di Sigg määt et müjjelesch, op Dateie en däm Zwescheschpeisscher zohzejriefe, woh Ding fresch huhjelaade Dateie dren shteiche, och di Dateie, di mer noch huh aam laade sin. Ußer Der sellver un em Wikiprojramm di söns keine ze sinn.',
'uploadstash-clear'    => 'All de Dateie em Zwescheschpeisscher vum Huhlaade fottschmieße',
'uploadstash-nofiles'  => 'Do häs kein Datteije en Dingem Zwescheschpeisscher vum Huhlaade.',
'uploadstash-badtoken' => 'Dat ze donn hät nit jeflupp. Velleich wohre Ding Daate zom Deil verschött jejange udder afjeloufe. Versöhg et nor_rens.',
'uploadstash-errclear' => 'Mer kunnte de Dateie nit fottschmieße.',
'uploadstash-refresh'  => 'De Leß met de Dateie op ene neue Shtand bränge',

# img_auth script messages
'img-auth-accessdenied' => 'Keine Zohjang',
'img-auth-nopathinfo'   => 'De <code lang="en">PATH_INFO</code> fäählt.
Dä Webßööver es nit doför ennjerescht, di Ennfommazjuhn wigger ze jävve.
Hä künnd_op <code lang="en">CGI</code> opjebout sin, un dröm <code lang="en">img_auth</code> nit ongshtöze künne. Loor onger http://www.mediawiki.org/wiki/Manual:Image_Authorization noh, wat domet es.',
'img-auth-notindir'     => 'Dä aanjefroochte Pat is nit em enjeschtallte Verzeischneß för et Huhlaade.',
'img-auth-badtitle'     => 'Uß „$1“ lööt sesch keine jöltijje Tittel maache.',
'img-auth-nologinnWL'   => 'Do bes nit ennjelogg, un „$1“ es nit op dä Leß met de zohjelohße Datteiname.',
'img-auth-nofile'       => 'En Dattei „$1“ jidd_et nit.',
'img-auth-isdir'        => 'Do wells op et Verzeishneß „$1“ zohjriife, ävver mer darref bloß op Datteie zohjriife.',
'img-auth-streaming'    => 'Mer sin „$1“ aam schecke.',
'img-auth-public'       => 'Dat Projramm <code lang="en">img_auth.php</code> jitt Dateie ene ennem privaate Wiki uß.
Dat Wiki heh es ävver öffentlesch.
Dröm es <code lang="en">img_auth.php</code> zor Sisherheit heh affjeschalldt.',
'img-auth-noread'       => 'Dä Metmaacher hät keine Zohjang, öm „$1“ ze lässe.',

# HTTP errors
'http-invalid-url'      => 'Dat es en onjöltije URL: $1',
'http-invalid-scheme'   => 'Mer künne heh kein <code lang="en">URL</code>s met „<code lang="en">$1</code>“ aam Aanfang bruche.',
'http-request-error'    => 'Di <code lang="en">HTTP</code>-Aanforderung es donävve jejange, ävver woröm, dat wesse mer nit.',
'http-read-error'       => 'Et Lässe beim <code lang="en">HTTP</code> es donävve jeange.',
'http-timed-out'        => 'Di <code lang="en">HTTP</code>-Aanforderung hät zoh lang jebruch.',
'http-curl-error'       => 'Ene Fähler es opjetrodde beim Holle vun däm <code lang="en">URL</code>: $1',
'http-host-unreachable' => 'Mer sen nit noh dämm <i lang="en">URL</i> dorschjekumme.',
'http-bad-status'       => 'Bei dä <code lang="en">HTTP</code>-Aanforderung es e Problem opjetrodde: Fähler&nbsp;$1 — $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Keij Antwoot vun däm <code lang="en">URL</code>.',
'upload-curl-error6-text'  => 'Dä ẞööver för dä <i lang="en">URL</i> hät zo lang nit jeantwoot. Bes esu joot un fingk eruß, ov dä övverhoup aam Laufe es, un don_ens jenou pröfe, ov dä <i lang="en">URL</i> stemmp.',
'upload-curl-error28'      => "Dat Huhlade hät zo lang jedooert, do ha'mer't jestopp",
'upload-curl-error28-text' => 'Dä ẞööver för di URL hät zo lang nit jeantwoot.
Bes esu joot un fingk eruß, ov dä övverhoup aam Laufe es.
Waat ene Moment un versök et nor_ens.
Velleich probees De et och zo en Zick, wo winnijer loss es.',

'license'            => 'Lizenz:',
'license-header'     => 'Lizänz',
'nolicense'          => 'Nix usjesök',
'license-nopreview'  => '(Kein Vör-Aansich ze hann)',
'upload_source_url'  => ' (richtije öffentlije URL)',
'upload_source_file' => ' (en Datei op Dingem Kompjuter)',

# Special:ListFiles
'listfiles-summary'     => "Hee sin de huhjeladene Dateie opjelis. Et eetz wäde de zoletz huhjeladene Dateie aanjezeich. Wa'mer op de Övverschreff von ene Spalt klick, weed die Spalt sotteet, wa'mer norrens klick, weed de Reiejfolg ömjedrieht.",
'listfiles_search_for'  => 'Sök noh däm Name vun dä Datei:',
'imgfile'               => 'Datei',
'listfiles'             => 'Dateie opleste',
'listfiles_thumb'       => 'Minni-Belldsche',
'listfiles_date'        => 'Dattum',
'listfiles_name'        => 'Name',
'listfiles_user'        => 'Metmaacher',
'listfiles_size'        => 'Byte',
'listfiles_description' => 'Wat en dä Datei dren shtish',
'listfiles_count'       => 'Versione',

# File description page
'file-anchor-link'          => 'Datei',
'filehist'                  => 'De Versione vun dä Datei',
'filehist-help'             => 'Di domohlije Version kriß De jezeich övver dä Link op em Dattum.',
'filehist-deleteall'        => 'All Versione fottschmieße',
'filehist-deleteone'        => 'Schmieß die Version fott',
'filehist-revert'           => 'Zeröck nemme',
'filehist-current'          => 'Von jetz',
'filehist-datetime'         => 'Version vom',
'filehist-thumb'            => 'Minni-Belldsche',
'filehist-thumbtext'        => 'Mini-Beldsche för de Version fum $2 öm $3 Uhr',
'filehist-nothumb'          => 'Kei Mini-Beldsche',
'filehist-user'             => 'Metmaacher',
'filehist-dimensions'       => 'Pixelle Breed×Hühte (Dateiömfang)',
'filehist-filesize'         => 'Dateiömfang',
'filehist-comment'          => 'Aanmerkung',
'filehist-missing'          => 'Di Datei es nit doh',
'imagelinks'                => 'Lenks op heh die Datei',
'linkstoimage'              => 'Heh {{PLURAL:$1|kütt di Sigg|kumme di $1 Sigge|sin keij Sigge}}, die op heh di Dattei linke {{PLURAL:$1|deiht|dun|dun}}:',
'linkstoimage-more'         => 'Mieh wie {{PLURAL:$1|ein Sigg link|$1 Sigge linke|kein Sigg link}} op di Datei.
De Liß hee dronger zeisch nur {{PLURAL:$1|der eetse Link|de eetste $1 Links|keine Link}} op di Datei.
Mer ävver han och en [[Special:WhatLinksHere/$2|Komplätte Leß]].',
'nolinkstoimage'            => 'Nix link op hee die Datei.',
'morelinkstoimage'          => 'Belohr Der [[Special:WhatLinksHere/$1|de Links]] op di Datei.',
'redirectstofile'           => 'Di {{PLURAL:$1|Datei heenoh leid|$1 Dateie leide}} op he di Datei öm:',
'duplicatesoffile'          => 'Mer hann_er {{PLURAL:$1|en dubbelte Datei|$1 dubbelte Dateie|kei dubbelte Dateije}} fon he dä Datei, di {{PLURAL:$1|hät|han all|han}} dersellve Enhalldt ([[Special:FileDuplicateSearch/$2|mieh Einzelheite]]):',
'sharedupload'              => 'De Datei es för diverse ungerscheidlije Projekte parat jelaht. Se kütt fun $1.',
'sharedupload-desc-there'   => 'Di Datei kütt vun $1 un kann en andere Projekte jebruch wäde.
Mer han och [$2 jenouer Date övver se].',
'sharedupload-desc-here'    => 'Di Datei kütt vun $1 un kann en ander Projekte jebruch wäde.
Jenouer Date övver se fingk mer op dä [$2 Sigg övver se].
Dat sellve shteiht hee dronger.',
'filepage-nofile'           => 'Et jit kein Datei met dämm Nahme.',
'filepage-nofile-link'      => 'Et jit kein Datei met dämm Nahme, ävver De kanns se [$1 huhlaade].',
'uploadnewversion-linktext' => 'Dun en neu Version vun dä Datei huhlade',
'shared-repo-from'          => 'uß $1',
'shared-repo'               => 'ene jemeinsame Beshtand',
'filepage.css'              => '/* Das folgende CSS wird auf Dateibeschreibungsseiten, auch auf fremden Client-Wikis, geladen. */',

# File reversion
'filerevert'                => '„$1“ zerök holle',
'filerevert-backlink'       => '←&nbsp;$1',
'filerevert-legend'         => 'Datei zeröck holle',
'filerevert-intro'          => '<span class="plainlinks">Do bes di Datei \'\'\'[[Media:$1|$1]]\'\'\' op di [$4 Version fum $2 öm $3 Uhr] zeröck aam sätze.</span>',
'filerevert-comment'        => 'Jrond:',
'filerevert-defaultcomment' => 'Zerök jesaz op di Version fum $1 öm $2 Uhr',
'filerevert-submit'         => 'Zeröcknemme',
'filerevert-success'        => '<span class="plainlinks">Di Dattei \'\'\'[[Media:$1|$1]]\'\'\' es jäz op di [$4 Version fum $2 öm $3 Uhr] zerök jesatz.</span>',
'filerevert-badversion'     => 'Mer han kei Version fun dä Datei för dä aanjejovve Zickpunk.',

# File deletion
'filedelete'                  => 'Schmieß „$1“ fott',
'filedelete-backlink'         => '←&nbsp;$1',
'filedelete-legend'           => 'Schmieß de Datei fott',
'filedelete-intro'            => "Do beß di Datei '''„[[Media:$1|$1]]“''' am Fottschmieße, un och all ier vörrije Versione, der Text övver se, un all de Änderunge draan.",
'filedelete-intro-old'        => '<span class="plainlinks">Do schmiiß de Version [$4 fum $2 öm $3 Uhr] fun dä Datei „[[Media:$1|$1]]“ fott.</span>',
'filedelete-comment'          => 'Aanlaß odder Jrund:',
'filedelete-submit'           => 'Fottschmieße',
'filedelete-success'          => "'''„$1“''' es fottjeschmeße.",
'filedelete-success-old'      => "Fun dä Datei '''„[[Media:$1|$1]]“''' es jäz di Version fum $2 öm $3 Uhr fottjeschmeße woode.",
'filedelete-nofile'           => "„$1“''' jidd_et nit.",
'filedelete-nofile-old'       => "Fun '''„$1“''' ham_mer kein arschiveete Version met dä Eijeschaffte.",
'filedelete-otherreason'      => 'Ander Jrund oder Zosätzlich:',
'filedelete-reason-otherlist' => 'Ne andere Jrund',
'filedelete-reason-dropdown'  => '* Alljemein Jrönd
** Wä dat Denge huhjelade hät, wollt et esu
** Wohr jäje et Urhävverrääsch
** Dubbelt',
'filedelete-edit-reasonlist'  => 'De Jrönde för et Fottschmieße beärbeide',
'filedelete-maintenance'      => 'Datteie Fottschmiiße un widder zerök Holle jeiht jez jrad nit, mer hann Waadong.',

# MIME search
'mimesearch'         => 'Dateie üvver dänne ehre <span lang="en">MIME</span>-Typ söke',
'mimesearch-summary' => 'Op hee dä {{int:nstab-special}} könne de Dateie noh em <i lang="en">MIME</i>-Tüpp ußjesöök wäde.
Mer moß immer der Medietüp un der Ongertüp aanjevve.
Zem Beispell: <code lang="en">image/jpeg</code>
— kannß donoh op dä Beschrievungssigge von de Dateie loore.',
'mimetype'           => 'MIME-Typ:',
'download'           => 'Erungerlade',

# Unwatched pages
'unwatchedpages' => 'Sigge, wo keiner drop oppass',

# List redirects
'listredirects' => 'Ömleitunge',

# Unused templates
'unusedtemplates'     => 'Schablone oder Baustein, die nit jebruch wääde',
'unusedtemplatestext' => 'Hee sin all de Schablone opjeliss, die em Appachtemeng „{{ns:template}}“ sin, die nit en
ander Sigge enjefüg wääde. Ih De jet dovun fottschmieß, denk dran, se künnte och op en ander Aat jebruch
wääde, un luur Der der iehr ander Links aan!',
'unusedtemplateswlh'  => 'ander Links',

# Random page
'randompage'         => 'Zofällije Sigg',
'randompage-nopages' => 'En {{PLURAL:$2|dem Appachtemang|dä Appachtemangs|keinem Appachtemang}} „$1“ sin ja kein Sigge dren.',

# Random redirect
'randomredirect'         => 'Zofällije Ömleitung',
'randomredirect-nopages' => 'En däm Appachtemang „$1“ sin ja kein Ömleidunge dren.',

# Statistics
'statistics'                   => 'Statistike',
'statistics-header-pages'      => 'Zahle övver Sigge',
'statistics-header-edits'      => 'Zahle övver Änderunge',
'statistics-header-views'      => 'Zahle övver afjeroofe Sigge',
'statistics-header-users'      => 'Statistike üvver de Metmaacher',
'statistics-header-hooks'      => 'Ander Statistike',
'statistics-articles'          => 'Atikele',
'statistics-pages'             => 'Sigge jesamp',
'statistics-pages-desc'        => '
All de Sigge em Wiki, och Klaafsigge, Ömleitunge, un esu jet',
'statistics-files'             => 'Huhjelade Dateie',
'statistics-edits'             => 'Änderunge aan Sigge',
'statistics-edits-average'     => 'Aanzahl Änderunge pro Sigg em Dorschschnett',
'statistics-views-total'       => 'Sigge affjeroofe, ėnßjesamp',
'statistics-views-total-desc'  => 'Sigge, die et nit johv, un Extrasigge sin nit metjezallt',
'statistics-views-peredit'     => 'Sigge affjeroofe, pro Änderung',
'statistics-users'             => '[[Special:ListUsers|Metmaacher]] aajemelldt',
'statistics-users-active'      => 'Aktive Metmaacher',
'statistics-users-active-desc' => 'Metmaacher, die {{PLURAL:$1|hück un jesterre|en de läzte $1 Dääsh|hück}} jät jemaat han.',
'statistics-mostpopular'       => 'De miets affjeroofe Sigge',

'disambiguations'      => '„(Wat es dat?)“-Sigge',
'disambiguationspage'  => 'Template:Disambig',
'disambiguations-text' => 'En de Sigge hee noh sin Links dren op en „(Watt ėßß datt?)“-Sigg.
De Links sollte eijentlesch op en Sigg jon, di tirek jemeint es.

(En Atikel jellt als en „(Watt ėßß datt?)“-Sigg un weed hee jeliss, wann en dä Sigg [[MediaWiki:Disambiguationspage]] ene Link op en drop dren is. Alles wat keij Atikele sin, weed dobei jaa nit eez metjezallt)',

'doubleredirects'                   => 'Ömleitunge op Ömleitunge',
'doubleredirectstext'               => 'Hee fings De en jede Reih ene Link op de iertste un de zweite Ömleitung, donoh ene Link op de Sigg, wo de
zweite Ömleitung hin jeiht. För jewöhnlich es dat dann och de richtije Sigg, wo de iertste Ömleitung ald hen jonn sullt.
<del>Ußjeshtreshe</del> Reije sin ald äleedesh.
Met däm „(Ändere)“-Link kanns De de eetste Sigg tirek aanpacke.
Tipp: Merk Der dä Tittel vun dä Sigg dovör.',
'double-redirect-fixed-move'        => 'dubbel Ömleidung nohm Ömnenne automattesch opjelös: [[$1]] → [[$2]]',
'double-redirect-fixed-maintenance' => 'De dubbelte Ömleidung vun [[$1]] noh [[$2]] wood opjelühß.',
'double-redirect-fixer'             => '(Opjaveleß)',

'brokenredirects'        => 'Ömleitunge, die en et Leere jonn',
'brokenredirectstext'    => 'Die Ömleitunge hee jonn op Sigge, die mer jaa nit han:',
'brokenredirects-edit'   => 'ändere',
'brokenredirects-delete' => 'fottschmieße',

'withoutinterwiki'         => 'Atikele ohne Links op annder Shprooche',
'withoutinterwiki-summary' => 'He sin Sigge jeliß, di nit op annder Shprooche jelingk sin.',
'withoutinterwiki-legend'  => 'Aanfang fum Sigge-Tittel',
'withoutinterwiki-submit'  => 'Zeije',

'fewestrevisions' => 'Atikele met de winnischste Versione',

# Miscellaneous special pages
'nbytes'                  => '$1 Byte{{PLURAL:$1||s|}}',
'ncategories'             => '{{PLURAL:$1| ein Saachjrupp | $1 Saachjruppe | keij Saachjruppe }}',
'nlinks'                  => '{{PLURAL:$1|eine Link|$1 Links}}',
'nmembers'                => 'met {{PLURAL:$1|ein Sigg|$1 Sigge}} dren',
'nrevisions'              => '{{PLURAL:$1|Ein Änderung|$1 Änderunge|Keij Änderung}}',
'nviews'                  => '{{PLURAL:$1|Eine Avrof|$1 Avrofe|Keine Avrof}}',
'nimagelinks'             => 'Weed op {{PLURAL:$1|eine Sigg|$1 Sigge|keine Sigg}} jebruch',
'ntransclusions'          => 'weed op {{PLURAL:$1|eine Sigg|$1 Sigge|keine Sigg}} jebruch',
'specialpage-empty'       => 'Hee en dä Liss es nix dren.',
'lonelypages'             => 'Atikele, wo nix drop link',
'lonelypagestext'         => 'De Sigge hee noh sin nörjenzwoh ennjebonge un et jonn och kein Linkß drop.',
'uncategorizedpages'      => 'Atikele, die en kein Saachjrupp sin',
'uncategorizedcategories' => 'Saachjruppe, die selvs en kein Saachjruppe sin',
'uncategorizedimages'     => 'Dateie, die en kein Saachjruppe dren sin',
'uncategorizedtemplates'  => 'Schablone, die en kein Saachjruppe sen',
'unusedcategories'        => 'Saachjruppe met nix dren',
'unusedimages'            => 'Dateie, die nit en Sigge dren stäche',
'popularpages'            => 'Sigge, die off avjerofe wääde',
'wantedcategories'        => 'Saachjruppe, die mer noch nit han, die noch jebruch wääde',
'wantedpages'             => 'Sigge, die mer noch nit han, die noch jebruch wääde',
'wantedpages-badtitle'    => 'Ene onjöltijje Tittel för en Sigg: $1',
'wantedfiles'             => 'Dateie, di onß noch fähle',
'wantedtemplates'         => 'Schablone, die mer noch nit han, die noch jebruch wääde',
'mostlinked'              => 'Atikele met de miehste Links drop',
'mostlinkedcategories'    => 'Saachjruppe met de miehste Links drop',
'mostlinkedtemplates'     => 'Schablone met de miehßte Lenks drop',
'mostcategories'          => 'Atikkele met de miehste Saachjruppe',
'mostimages'              => 'Dateie met de miehste Links drop',
'mostrevisions'           => 'Atikkele met de miehste Änderunge',
'prefixindex'             => 'All Sigge, dänne ehr Name met enem bestemmte Wood oder Tex aanfängk',
'shortpages'              => 'Atikele zoteet vun koot noh lang',
'longpages'               => 'Atikele zoteet vun lang noh koot',
'deadendpages'            => 'Atikele ohne Links dren',
'deadendpagestext'        => 'De Atikele hee han kein Links op ander Atikele em Wiki.',
'protectedpages'          => 'Jeschötzte Sigge',
'protectedpages-indef'    => 'Nor de Sigge zeije, woh alleins de Wiki-Köbesse draan dörrve',
'protectedpages-cascade'  => 'Nur Sigge en ener Schotz-Kaskad',
'protectedpagestext'      => '<!-- -->',
'protectedpagesempty'     => 'Op di Aat sin jrad kein Sigge jeschötz.',
'protectedtitles'         => 'Verbodde Titele för Sigge',
'protectedtitlestext'     => 'Sigge met hee dä Tittele lohße mer nit zo, un di künne dröm nit aanjelääsch wäde:',
'protectedtitlesempty'    => 'Op di Aat sin jrad kein Sigge jäje et neu Aanlääje jeschötz.',
'listusers'               => 'Metmaacherliss',
'listusers-editsonly'     => 'Donn nor Metmaacher zeije, di och ens jät jeschrevve han.',
'listusers-creationsort'  => 'Noh em Dattum vum Aanmellde zoteere',
'usereditcount'           => '{{PLURAL:$1|Ein Änderung|$1 Änderunge|Nix jedonn}}',
'usercreated'             => 'Aanjemelldt aam $1 öm $2 Uhr',
'newpages'                => 'Neu Sigge',
'newpages-username'       => 'Metmaacher Name:',
'ancientpages'            => 'Atikele zoteet vun Ahl noh Neu',
'move'                    => 'Ömnenne',
'movethispage'            => 'De Sigg ömnenne',
'unusedimagestext'        => 'Di Dateije hee dronger jidd_et, äver se sin en keine Sigg em Wiki enjebonge.
<br /><strong>Opjepass:</strong> Ander Websigge künnte immer noch de Dateie hee tirek
per URL aanspreche. Su künnt et sin, dat en
Datei hee en de Liss steiht, ävver doch jebruch weed. Usserdäm, winnichstens bei neue Dateie, künnt sin,
dat se noch nit en enem Atikkel enjebaut sin, weil noch Einer dran am brasselle es.',
'unusedcategoriestext'    => 'De Saachjruppe hee sin enjerich, ävver jetz em Momang, es keine Atikkel un
kein Saachjrupp dren ze finge.',
'notargettitle'           => 'Keine Bezoch op e Ziel',
'notargettext'            => 'Et fählt ene Metmaacher oder en Sigg, wo mer jet zo erusfinge oder oplisste solle.',
'nopagetitle'             => "Esu en Sigg ham'mer nit",
'nopagetext'              => 'Do häss en Sigg aanjovve, di jidd et jaa nit.',
'pager-newer-n'           => '{{PLURAL:$1|aller neuerste|neuer $1}}',
'pager-older-n'           => '{{PLURAL:$1|vörrije|vörrije $1}}',
'suppress'                => 'Versteiche',
'querypage-disabled'      => 'Heh di Extrasigg es ußjeschalldt, domet dä Server jet winnijer ze brassele hät.',

# Book sources
'booksources'               => 'Böcher',
'booksources-search-legend' => 'Söök noh Bezochsquelle för Bööcher',
'booksources-isbn'          => 'ISBN:',
'booksources-go'            => 'Loß Jonn!',
'booksources-text'          => 'Hee noh küdd_en Leßß met Websigge,
wo mir {{GRAMMAR:Dative fun|{{SITENAME}}}} nix wigger med ze donn hänn,
wo mer jät övver Böösher erfaare
un zom Dëijl och Böösher koufe kann.
Doför moßß De Desh mannshmool allodengs eetß ennß aanmällde,
wat Koßte un Jefaare met sesh brenge künndt.
Wo_t jëijdt,
jonn di Lengkß hee tirrägg_op dat Booch,
wadd_Er am Sööke sidt.',
'booksources-invalid-isbn'  => 'De ISBNummer schingk verkeeht ze sin. Loohr ens donoh, woh se häe kütt.',

# Special:Log
'specialloguserlabel'  => 'Metmaacher:',
'speciallogtitlelabel' => 'Siggename:',
'log'                  => 'Logböcher ehr Opzeichnunge (all)',
'all-logs-page'        => 'All de öffentlich Logböcher',
'alllogstext'          => "Dat hee es en jesamte Liss us all dä Logböcher {{GRAMMAR:en|{{SITENAME}}}}.
Dä Logböcher ehre Enhald ka'mer all noh de Aat, de Metmaacher,
oder de Sigge ehr Name, un esu, einzel zoteet aanluure.
Bei de Name moß mer op Jruß- un Kleinschreff aachjävve.",
'logempty'             => '<i>Mer han kein zopass Endräch en däm Logboch.</i>',
'log-title-wildcard'   => 'Sök noh Titelle, di aanfange met …',

# Special:AllPages
'allpages'          => 'All Sigge',
'alphaindexline'    => '$1 … $2',
'nextpage'          => 'De nächste Sigg: „$1“',
'prevpage'          => 'Vörijje Sigg ($1)',
'allpagesfrom'      => 'Sigge aanzeije av däm Name:',
'allpagesto'        => 'Sigge aanzeije bes:',
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
'categoriespagetext'            => 'Hee {{PLURAL:$1|es nur en Saachjrupp|sin nur Saachjruppe|es kei Saachjrupp}} jeliss, woh jät dren {{PLURAL:$1|es|es|wöhr}}. Mer han_er eije Leßte för de
[[Special:UnusedCategories|Saachjruppe met nix dren]], un de
[[Special:WantedCategories|jewönschte un nit aanjelaate Saachjruppe]].',
'categoriesfrom'                => 'Zeich Saachjruppe vun hee af:',
'special-categories-sort-count' => 'Zoteere noh de Aanzahl',
'special-categories-sort-abc'   => 'Zoteere nohm Alphabett',

# Special:DeletedContributions
'deletedcontributions'             => 'Fottjeschmesse Versione',
'deletedcontributions-title'       => 'Fottjeschmesse Versione',
'sp-deletedcontributions-contribs' => 'Beijdrääsch',

# Special:LinkSearch
'linksearch'       => 'Lėngkß noh ußerhallef sööke',
'linksearch-pat'   => 'Sök noh:',
'linksearch-ns'    => 'Appachtemang:',
'linksearch-ok'    => 'Sööke',
'linksearch-text'  => 'Di {{int:nstab-special}} heh mäd_et müjjelesch noh Sigge ze söke, woh beshtemmpte Links op Websigge dren enthallde sin.

Beim Söke künnd_Er Shternshe aanjevve för e Shtöckshe fun ennem Name, wo mer nit jenou weiß, wi et heiß udder wat me nit kenne deit, zem Beishpöll esu: <tt>http://*.example.com</tt>

De Brauserprotokolle, di beim Söke aanjejovve wäde künne, sen: <tt>$1</tt>',
'linksearch-line'  => '„$2“ hät ene Link op $1',
'linksearch-error' => 'Shternshe kam_mer nor aam Aanfang fum Domain-Name bruche.',

# Special:ListUsers
'listusersfrom'      => 'Zeich de Metmaacher vun:',
'listusers-submit'   => 'Zeije',
'listusers-noresult' => 'Keine Metmaacher jefonge.',
'listusers-blocked'  => '(jespert)',

# Special:ActiveUsers
'activeusers'            => 'Leß met de aktiive Metmaacher',
'activeusers-intro'      => 'Dat heh es en Leß met dä Metmaacher, di {{PLURAL:$1|zick jäßtere|en de läzde $1 Dääsch|hück}} ööhnsjät jemaat han.',
'activeusers-count'      => '{{PLURAL:$1|ein Änderung|$1 Änderunge|kein Änderunge}} {{PLURAL:$3|aam lezde Daach|en de lezte $3 Dääsch|hück}}',
'activeusers-from'       => 'Donn de Metmaacher zeije aff:',
'activeusers-hidebots'   => 'De Bots fott lohße',
'activeusers-hidesysops' => 'De Wiki_Köbesse fott lohße',
'activeusers-noresult'   => 'Kein Metmaacher jefonge.',

# Special:Log/newusers
'newuserlogpage'              => 'Logboch för neu Metmaachere',
'newuserlogpagetext'          => 'He sin de Metmaacher opjelėßß, di sesh nöü aanjemäldt han.',
'newuserlog-byemail'          => 'dat Passwood wood med de e-mail loßjescheck',
'newuserlog-create-entry'     => 'eß enne nöüje Metmaacher',
'newuserlog-create2-entry'    => 'hät ene nöüje Zojang enjerėshdt för „$1“',
'newuserlog-autocreate-entry' => 'dä Metmaacher wood automattesch aanjemelldt',

# Special:ListGroupRights
'listgrouprights'                      => 'Metmaacher-Jruppe-Rääschte',
'listgrouprights-summary'              => 'Hee kütt de Liss met dä Medmaacher-Jruppe, di dat Wiki hee kennt, un denne ier Rääschte.
Mieh övver de einzel Rääschte fenkt Er op de [[{{MediaWiki:Listgrouprights-helppage}}|Hölp-Sigg övver de Medmaacher ier Rääschte]].',
'listgrouprights-key'                  => 'Lejend:
* Dat es e <span class="listgrouprights-granted">jejovve Rääsch</span>
* Dat es e <span class="listgrouprights-revoked">fottjenumme Rääsch</span>',
'listgrouprights-group'                => 'Jrupp',
'listgrouprights-rights'               => 'Räächte',
'listgrouprights-helppage'             => 'Help:Jrupperäächte',
'listgrouprights-members'              => '(opliste)',
'listgrouprights-addgroup'             => 'Metmaacher en {{PLURAL:$2|de Metmaacher-Jrupp|de Metmaacher-Jruppe|kein Metmaacher-Jrupp}} $1 erin dunn',
'listgrouprights-removegroup'          => 'Metmaacher us {{PLURAL:$2|dä Metmaacher-Jrupp|de Metmaacher-Jruppe|jaa kei Metmaacher-Jrupp}} $1 eruß nämme',
'listgrouprights-addgroup-all'         => 'Metmaacher en alle Metmaacher-Jruppe erin donn',
'listgrouprights-removegroup-all'      => 'Metmaacher us alle Metmaacher-Jruppe eruß nämme',
'listgrouprights-addgroup-self'        => 'Kann sesch sällver {{PLURAL:$2|erinn donn en de Metmaacherjropp:|en $2 Metmaacherjroppe erinn donn:|en kei Metmaacherjropp erenn donn.}} $1',
'listgrouprights-removegroup-self'     => 'Kann sesch sällver {{PLURAL:$2|eruß nämme uß dä Metmaacherjropp:|uß $2 Metmaacherjroppe eruß nämme:|uß kei Metmaacherjropp eruß nämme.}} $1',
'listgrouprights-addgroup-self-all'    => 'Kann sesch sällver en alle Metmaacherjroppe erenn donn',
'listgrouprights-removegroup-self-all' => 'Kann sesch sällver uß alle Metmaacherjroppe eruß nämme',

# E-mail user
'mailnologin'          => 'Keij E-Mail Adress',
'mailnologintext'      => 'Do mööts ald aanjemeldt un [[Special:UserLogin|enjelogg]] sin, un en jode E-Mail
Adress en Dinge [[Special:Preferences|ming Enstellunge]] stonn han, öm en E-Mail aan andere Metmaacher ze
schecke.',
'emailuser'            => 'E-mail aan dä Metmaacher',
'emailpage'            => 'E-mail aan ene Metmaacher',
'emailpagetext'        => 'Wann dä Metmaacher en E-mail Adress aanjejovve hätt en singe Enstellunge,
un die deit et och, dann kanns De met däm Fomular hee unge en einzelne E-Mail aan dä Metmaacher schecke.
Ding E-mail Adress, die De en [[Special:Preferences|Ding eije Enstellunge]] aanjejovve häs,
die weed als em Avsender sing Adress en die E-Mail enjedrage.
Domet kann, wä die E-Mail kritt, drop antwoote, un die Antwood jeiht tirek aan Dech.
Alles klor?',
'usermailererror'      => 'Dat E-Mail-Objek jov ene Fähler us:',
'defemailsubject'      => 'e-mail {{GRAMMAR:fun|{{SITENAME}}}}.',
'usermaildisabled'     => 'De <i lang="en">e-mail</i> zwesche Metmaachere es ußjeschalt',
'usermaildisabledtext' => 'Do kanns kein <i lang="en">e-mail</i> aan ander Metmaacher heh en dämm Wiki schecke',
'noemailtitle'         => 'Kein E-Mail Adress',
'noemailtext'          => 'Dä Metmaacher hät kein jöltijje Adreß för sing <i lang="en">e-mail</i> enjedrage.<!-- oder hä well kein E-Mail krije. -->',
'nowikiemailtitle'     => 'Kein <i lang="en">e-mail</i> zojelohße',
'nowikiemailtext'      => 'Hee dä Metmaacher well kein <i lang="en">e-mail</i> vun ander Metmaachere jescheck krijje.',
'email-legend'         => 'Scheck en<i lang="en"> e-mail</i> aan ene andere Metmaacher fum Wiki',
'emailfrom'            => 'Vun:',
'emailto'              => 'Aan:',
'emailsubject'         => 'Üvverschreff:',
'emailmessage'         => 'Dä Tex fun Dinge Nohresch:',
'emailsend'            => 'Avschecke',
'emailccme'            => 'Scheck mer en Kopie vun dä E-Mail.',
'emailccsubject'       => 'En Kopie vun Dinger E-Mail aan $1: $2',
'emailsent'            => 'E-Mail es ungerwähs',
'emailsenttext'        => 'Ding E-Mail es jetz lossjescheck woode.',
'emailuserfooter'      => 'Hee di e-mail hät {{GENDER:$1|dä|et|dä Metmaacher|di|dat}} „$1“ an {{GENDER:$2|dä|et|dä Metmaacher|di|dat}} „$2“ jescheck, un doför {{GRAMMAR:en dative|{{SITENAME}}}} dat „{{int:emailuser}}“ jebruch.',

# User Messenger
'usermessage-summary' => 'En Nohreesh vum Wiki afjelivvert.',
'usermessage-editor'  => 'Name vum Metmaacher för de Täxte un Nohreshte vum Wiki ze beärbeide',

# Watchlist
'watchlist'            => 'ming Oppassliss',
'mywatchlist'          => 'ming Oppassliss',
'watchlistfor2'        => 'För {{GENDER:$1|dä|dat|dä Metmaacher|de|dat}} $1 $2',
'nowatchlist'          => 'En Ding Oppassliss es nix dren.',
'watchlistanontext'    => 'Do muss $1, domet de en Ding Oppassliss erenluure kanns, oder jet dran ändere.',
'watchnologin'         => 'Nit enjelogg',
'watchnologintext'     => 'Öm Ding Oppassliss ze ändere, mööts de ald [[Special:UserLogin|enjelogg]] sin.',
'addedwatch'           => 'En de Oppassliss jedon',
'addedwatchtext'       => "Die Sigg „[[:$1]]“ es jetz en Ding [[Special:Watchlist|Oppassliss]].
Av jetz, wann die Sigg verändert weed, oder ehr Klaafsigg, dann weed dat en de
Oppassliss jezeich. Dä Endrach för die Sigg kütt en  '''Fettschreff''' en de
„[[Special:RecentChanges|Neuste Änderunge]]“, domet De dä do och flöck fings.
Wann de dä widder loss wääde wells us Dinger Oppassliss,
dann klick op „Nimieh drop oppasse“ wann De die Sigg om Schirm häs.",
'removedwatch'         => 'Us de Oppassliss jenomme',
'removedwatchtext'     => 'Die Sigg „[[:$1]]“ es jetz us de [[Special:Watchlist|Oppassliss]] erusjenomme.',
'watch'                => 'Drop Oppasse',
'watchthispage'        => 'Op die Sigg oppasse',
'unwatch'              => 'Nimieh drop Oppasse',
'unwatchthispage'      => 'Nit mieh op die Sigg oppasse',
'notanarticle'         => 'Keine Atikkel',
'notvisiblerev'        => 'Di Version es fottjeschmesse',
'watchnochange'        => 'Keine Atikkel en Dinger Oppassliss es en dä aanjezeichte Zick verändert woode.',
'watchlist-details'    => 'Do häs {{PLURAL:$1|<strong>ein</strong> Sigg|<strong>$1</strong> Sigge|<strong>kein</strong> Sigg}} en dä Oppassliss{{PLURAL:$1|, un di Klaafsigg dozo|, un de Klaafsigge dozo|}}.',
'wlheader-enotif'      => '* Et E-mail Schecke es enjeschalt.',
'wlheader-showupdated' => '* Wann se Einer jeändert hätt, zickdäm De se et letzte Mol aanjeluurt häs, sin die Sigge <strong>extra markeet</strong>.',
'watchmethod-recent'   => 'Ben de letzte Änderunge jäje de Oppassliss am pröfe',
'watchmethod-list'     => 'Ben de Oppassliss am pröfe, noh de letzte Änderung',
'watchlistcontains'    => 'En dä Oppassliss {{PLURAL:$1|es ein Sigg|sinner <strong>$1</strong> Sigge|sinner <strong>kein</strong> Sigge}}.',
'iteminvalidname'      => 'Dä Endrach „$1“ hät ene kapodde Name.',
'wlnote'               => '{{PLURAL:$1|Hee es de letzte Änderung us|Hee sin de letzte <strong>$1</strong> Änderunge us|Mer han kein Äbderunge en}} de letzte {{PLURAL:$2|Stund|<strong>$2</strong> Stunde|<strong>noll</strong> Stunde}}.',
'wlshowlast'           => 'Zeich de letzte | $1 | Stunde | $2 | Dage | $3 | aan, dun',
'watchlist-options'    => 'Eijeschaffte fun de Oppassless',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Drop oppasse…',
'unwatching' => 'Nimmieh drop oppasse',

'enotif_mailer'                => '{{ucfirst:{{GRAMMAR:Genitive singe male|{{SITENAME}}}}}} Nohreechte-Versand',
'enotif_reset'                 => 'Setz all Änderunge op „Aanjeluurt“ un Erledich.',
'enotif_newpagetext'           => 'Dat es en neu aanjelahte Sigg.',
'enotif_impersonal_salutation' => 'Metmaacher {{GRAMMAR:Genitiv vun|{{SITENAME}}}}',
'changed'                      => 'jeändert',
'created'                      => 'neu aanjelaht',
'enotif_subject'               => 'De Sigg "$PAGETITLE" wood $CHANGEDORCREATE vum "$PAGEEDITOR" {{GRAMMAR:em|{{SITENAME}}}}',
'enotif_lastvisited'           => 'Luur unger „$1“ - do fings de all die Änderunge zick Dingem letzte Besoch hee.',
'enotif_lastdiff'              => 'Loor op $1 för heh di Änderung aan_ze_loore.',
'enotif_anon_editor'           => 'Dä namelose Metmaacher $1',
'enotif_body'                  => 'Leeven $WATCHINGUSERNAME,

{{GRAMMAR:em|{{SITENAME}}}} wood die Sigg „$PAGETITLE“ am $PAGEEDITDATE vun „$PAGEEDITOR“ $CHANGEDORCREATED, unger $PAGETITLE_URL fings Do de neuste Version.

$NEWPAGE

{{int:summary}} „$PAGESUMMARY“ $PAGEMINOREDIT

Do kanns dä Metmaacher „$PAGEEDITOR“ aanspreche:
* E-mail: $PAGEEDITOR_EMAIL
* Em Wiki: $PAGEEDITOR_WIKI

Do kriss vun jetz aan kein e-mail mieh, bes dat Do Der di Sigg aanjeluurt häs,
och wann se norr_ens verändert weed.
Do kanns ävver och all die Merker för e-mail för die Sigge en Dinger Oppassliss op eimol ändere.

Ene schöne Jroß {{GRAMMAR:vun|{{SITENAME}}}}.

-- 
Do kanns hee Ding Oppassliss ändere:
{{fullurl:{{#special:Watchlist}}/edit}}

Öm di Sigg vun Dinger Oppassliss ze schmieße:
$UNWATCHURL

Do kanns hee noh Hölp luure:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Schmieß die Sigg jetz fott',
'confirm'                => 'Dä Schotz för die Sigg ändere',
'excontent'              => 'drop stundt: „$1“',
'excontentauthor'        => 'drop stundt: „$1“ un dä einzije Schriever woh: „$2“',
'exbeforeblank'          => 'drop stundt vörher: „$1“',
'exblank'                => 'drop stundt nix',
'delete-confirm'         => '„$1“ fottschmieße',
'delete-backlink'        => '←&nbsp;$1',
'delete-legend'          => 'Fottschmieße',
'historywarning'         => '<strong>Opjepass:</strong> Die Sigg, di De fott schmiiße wells, hät {{PLURAL:$1|ein ällder Version|ald Stöcker $1 ällder Versione|jaa kei ällder Versione}}.',
'confirmdeletetext'      => 'Do bes koot dovör, en Sigg för iwich fottzeschmieße. Dobei verschwind och de janze Verjangenheit vun dä Sigg us de Daatebank, met all ehr Änderunge un Metmaacher Name, un all dä Opwand, dä do dren stich. Do muss heh jetz bestätije, dat de versteihs, wat dat bedügg, un dat De weiß, wat Do do mähs.
<strong>Dun et nor, wann dat met de [[{{MediaWiki:Policy-url}}|Rejelle]] wirklich zosamme jeiht!</strong>',
'actioncomplete'         => 'Erledich',
'actionfailed'           => 'Dat es donevve jejange',
'deletedtext'            => 'De Sigg „<nowiki>$1</nowiki>“ es jetz fottjeschmesse woode. Luur Der „$2“ aan, do häs De en Liss met de Neuste fottjeschmesse Sigge.',
'deletedarticle'         => 'hät fottjeschmesse: „[[$1]]“',
'suppressedarticle'      => 'han „[[$1]]“ verstoche',
'dellogpage'             => 'Logboch met de fottjeschmesse Sigge',
'dellogpagetext'         => 'Hee sin de Sigge oppjeliss, die et neus fottjeschmesse woodte.',
'deletionlog'            => 'Dat Logboch fum Sigge-Fottschmieße',
'reverted'               => 'Han de ählere Version vun dä Sigg zoröck jehollt',
'deletecomment'          => 'Aanlaß odder Jrund:',
'deleteotherreason'      => 'Ander Jrund oder Zosätzlich:',
'deletereasonotherlist'  => 'Ander Jrund',
'deletereason-dropdown'  => '* Alljemein Jrönde
** dä Schriever wollt et esu
** wohr jäje et Urhävverrääsch
** et wohd jet kapott jemaat',
'delete-edit-reasonlist' => 'De Jrönde för et Fottschmieße beärbeide',
'delete-toobig'          => 'Di Sigg hät {{PLURAL:$1|ein Version|$1 Versione|jaa kein Version}}. Dat sinn_er ärsch fill. Domet unsere ẞööver do nit draan en de Kneen jeit, dom_mer esu en Sigg nit fottschmieße.',
'delete-warning-toobig'  => 'Di Sigg hät {{PLURAL:$1|ein Version|$1 Versione|jakein Version}}. Dat sinn_er ärsch fill. Wann De die all fottschmieße wells, dat kann dem Wiki sing Datenbangk schwer ußbremse.',

# Rollback
'rollback'          => 'Em Letzte sing Änderunge zeröcknemme',
'rollback_short'    => 'Zeröcknemme',
'rollbacklink'      => 'All dem Letzte sing Änderunge zeröckdriehe',
'rollbackfailed'    => 'Dat Zeröcknemme jingk scheiv',
'cantrollback'      => 'De letzte Änderung zeröckzenemme es nit müjjelich. Dä letzte Schriever es dä einzije, dä aan dä Sigg hee jet jedon hät!',
'alreadyrolled'     => 'Mer künne de letzte Änderunge vun dä Sigg „[[:$1]]“ vum Metmaacher „[[User:$2|$2]]“ ([[User talk:$2|Klaaf]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]) nimieh zeröcknemme, dat hät ene Andere enzwesche ald jedon, udder de Sigg ömjeändert.

De Neuste Änderung aan dä Sigg es jetz vun däm Metmaacher „[[User:$3|$3]]“ ([[User talk:$3|Klaaf]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'       => "Bei dä Änderung stundt: „''$1''“.",
'revertpage'        => 'Änderunge vun däm Metmaacher „[[Special:Contributions/$2|$2]]“ ([[User talk:$2|däm sing Klaafsigg]]) fottjeschmesse, un doför de letzte Version vum „[[User:$1|$1]]“ widder zeröckjehollt',
'revertpage-nouser' => 'Änderunge vun enem Metmaacher, däm singe Name vershtoche es, retuur jemaat op de letzte Version {{GENDER:$1|vum|vum|vum Metmaacher|vun dä|vum}} [[User:$1|$1]]',
'rollback-success'  => 'De Änderungen vum $1 zeröckjenumme, un dobei de letzte Version vum $2 widder jehollt.',

# Edit tokens
'sessionfailure-title' => 'Fähler met dä Daate vum Enlogge',
'sessionfailure'       => "Et jov wall e technisch Problem met Dingem Login. Dröm ha'mer dat us Vörsich jetz nit jemaht, domet mer nit villeich Ding Änderung däm verkihrte Metmaacher ungerjubele. Jangk zeröck un versök et noch ens.",

# Protect
'protectlogpage'              => 'Logboch vum Sigge Schötze',
'protectlogtext'              => 'Hee es de Liss vun de Sigge, die jeschötz oder frei jejovve woode sin.',
'protectedarticle'            => 'hät de Sigg „[[$1]]“ jeschötz',
'modifiedarticleprotection'   => 'hät dä Schoz för die Sigg „[[$1]]“ jeändert',
'unprotectedarticle'          => 'hät der Schotz för die Sigg „[[$1]]“ opjehovve',
'movedarticleprotection'      => 'hät de Enstellunge för der Sigge-Schotz fun „[[$2]]“ noh „[[$1]]“ övvernomme',
'protect-title'               => 'Sigge Schotz för „$1“ ändere',
'prot_1movedto2'              => 'hät de Sigg vun „[[$1]]“ en „[[$2]]“ ömjenannt.',
'protect-backlink'            => '←&nbsp;$1',
'protect-legend'              => 'Sigg schötze',
'protectcomment'              => 'Aanlaß odder Jrund:',
'protectexpiry'               => 'Duur, wi lang:',
'protect_expiry_invalid'      => 'Die Duur för ze Schötz es Kappes, di künne mer nit verstonn.',
'protect_expiry_old'          => 'Do häs De Desch verdonn. Die Zick för ze Schötze es doch ald eröm!',
'protect-unchain-permissions' => 'Donn de andere Ußwahle freij schallde',
'protect-text'                => "Heh kanns De dä Schotz jäje Veränderunge för de Sigg „'''<nowiki>$1</nowiki>'''“ aanluure un ändere.",
'protect-locked-blocked'      => "Do kanns nit der Siggeschotz ändere, esu lang wi Dinge Zojang zom Wiki jesperrt es. Hee es der aktuelle Stand fum Siggeschotz för di Sigg '''„$1“:'''",
'protect-locked-dblock'       => "De Datebank es jesperrt. Dröm künne mer der Siggeschotz nit ändere.
Hee es der aktuelle Stand fum Siggeschotz för di Sigg '''„$1“:'''",
'protect-locked-access'       => "Do häs nit dat Rääsch, heh em Wiki Sigge ze schötze udder dä Schotz widder opzehevve.
Di Sigg '''„$1“:''' es jetz jrad:",
'protect-cascadeon'           => 'Die Sigg es en enne Schotz-Kaskad. Se es enjebonge en {{PLURAL:$1|die Sigg|$1 Sigge|kein Sigg}}, die per Kaskade-Schotz jeschötz {{PLURAL:$1|es|sin|es}}. Do kanns dä Schotz för die Sigg heh ändere, ävver di Kaskad blief bestonn. Dat heh sin die Sigge en dä Kaskad:',
'protect-default'             => 'Jeede Metmaacher eraan lohße',
'protect-fallback'            => 'Do weet dat Rääsch „$1“ jebruch.',
'protect-level-autoconfirmed' => 'Donn neu Metmaacher un namelose Metmaacher nit dranlooße',
'protect-level-sysop'         => 'Nor de Wiki-Köbesse dranlooße',
'protect-summary-cascade'     => 'met Schotz-Kaskad',
'protect-expiring'            => 'bes öm $3 Uhr (UTC) aam $2',
'protect-expiry-indefinite'   => 'för iewich',
'protect-cascade'             => 'Maach en Schoz-Kaskaade — all de Schablone en dä Sigg krijje dersellve Schoz, wi die Sigg sellver en kritt.',
'protect-cantedit'            => 'Do kanns dä Siggeschotz heh nit ändere, esu lang wie De di Sigg nit ändere darfs.',
'protect-othertime'           => 'En ander Door:',
'protect-othertime-op'        => 'en ander Door',
'protect-existing-expiry'     => 'Beß am $2 öm $3 Uhr',
'protect-otherreason'         => 'En andere udder zosätzlijje Jrund:',
'protect-otherreason-op'      => 'Ene andere udder zosätzlijje Jrond',
'protect-dropdown'            => '* Jewöhnlijje Jrönd för dä Sigge-Schotz
** ußerjewöhnlesch fill Kapottmaacherei
** ußerjewöhnlesch fill SPAMlinks op ander Sigge wäde neu enjedraare
** Hen- un her-Änderei, woh mer süht, dat nix mieh joods erus kumme weed
** janz weschtejje Sigg, met ußerjewöhnlesch fill Afroofe',
'protect-edit-reasonlist'     => 'Don de Grönd för t Schöze beärrbeide',
'protect-expiry-options'      => '1 Stund:1 hour,1 Dach:1 day,1 Woch:1 week,2 Woche:2 weeks,1 Mond:1 month,3 Mond:3 months,6 Mond:6 months,1 Johr:1 year,Unbejrenz:infinite',
'restriction-type'            => 'jespecht es:',
'restriction-level'           => 'ändere darf:',
'minimum-size'                => 'met mieh wie',
'maximum-size'                => 'met winijjer wie',
'pagesize'                    => 'Bytes en dä Sigg dren',

# Restrictions (nouns)
'restriction-edit'   => 'et Ändere',
'restriction-move'   => 'et Ömnenne',
'restriction-create' => 'Aanläje',
'restriction-upload' => 'Huhlade',

# Restriction levels
'restriction-level-sysop'         => 'nur de Wiki-Köbesse',
'restriction-level-autoconfirmed' => 'nur aanjemeldte Metmaacher, di ald en Zick dobei sin',
'restriction-level-all'           => 'jeede',

# Undelete
'undelete'                     => 'Fottjeschmessene Krom aanluure odder zeröck holle',
'undeletepage'                 => 'Fottjeschmesse Sigge aanluure un widder zeröckholle',
'undeletepagetitle'            => "'''He dat sin de fottjeschmeße Versione fun [[:$1|$1]]'''",
'viewdeletedpage'              => 'Fottjeschmesse Sigge aanzeije',
'undeletepagetext'             => '{{PLURAL:$1|De Sigg heenoh es|De $1 Sigge heenoh sin|De 0 Sigge heenoh sin}} fottjeschmesse, mer künne se ävver immer noch usem Müllemmer eruskrose.',
'undelete-fieldset-title'      => 'Versione zeröck holle',
'undeleteextrahelp'            => 'Öm de janze Sigg met all ehre Versione widder ze holle, looß all de Versione ohne Hökche, un klick op „<b style="padding:2px; background-color:#ddd; color:black">{{int:Undeletebtn}}</b>“.<br />
Öm bloß einzel Versione zeröckzeholle, maach Hökche aan die Versione, die De widder han wells, un dann dun „<b style="padding:2px; background-color:#ddd; color:black">{{int:Undeletebtn}}</b>“ klicke.<br />
Op „<b style="padding:2px; background-color:#ddd; color:black">{{int:Undeletereset}}</b>“
klicks De, wann De all Ding Hökche un Ding „{{int:Undeletecomment}}“ widder fott han wells.',
'undeleterevisions'            => '{{PLURAL:$1|Ein Version|<strong>$1</strong> Versione|<strong>Kein</strong> Version}} en et Archiv jedon',
'undeletehistory'              => 'Wann De die Sigg widder zeröckhölls,
dann kriss De all de fottjeschmesse Versione widder.
Wann enzwesche en neu Sigg unger däm ahle Name enjerich woode es,
dann wääde de zeröckjehollte Versione einfach als zosätzlije äldere
Versione för die neu Sigg enjerich. Die neu Sigg weed nit ersetz.',
'undeleterevdel'               => 'Dat Zeröckholle flupp nit, wann de neuste Version verstoche es udder verstoche Aandeile do dren sin. En esu en Fäll darrf de neuste Version kei Höksche krijje, udder se moß eets ens en en nommaale Version ömjewandelt wääde, di nit mieh verstoche es.',
'undeletehistorynoadmin'       => 'Die Sigg es fottjeschmesse woode. Dä Jrund döför es en de Liss unge ze finge, jenau esu wie de Metmaacher, wo de Sigg verändert han, ih dat se fottjeschmesse wood. Wat op dä Sigg ehre fottjeschmesse ahle Versione stundt, dat künne nor de Wiki-Köbesse noch aansinn (un och widder zeröckholle)',
'undelete-revision'            => 'Fottjeschmeße Version fun dä Sigg „$1“ fum $4 öm $5 Uhr, et letz jändert fum $3:',
'undeleterevision-missing'     => 'De Version stemmp nit. Dat wor ene verkihrte Link, oder de Version wood usem Archiv zeröck jehollt, oder fottjeschmesse.',
'undelete-nodiff'              => 'Mer han kei ällder Version jefonge.',
'undeletebtn'                  => 'Zeröckholle!',
'undeletelink'                 => 'aanloore odder widder zeröckholle',
'undeleteviewlink'             => 'aanloore',
'undeletereset'                => 'De Felder usleere',
'undeleteinvert'               => 'De Ußwahl ömdrije',
'undeletecomment'              => 'Jrond (för en et Logboch):',
'undeletedarticle'             => '„$1“ zeröckjehollt',
'undeletedrevisions'           => '{{PLURAL:$1|ein Version|$1 Versione}} zeröckjehollt',
'undeletedrevisions-files'     => 'Zesammejenomme {{PLURAL:$1|Ein Version|<strong>$1</strong> Versione|<strong>Kein</strong> Version}} vun {{PLURAL:$2|eine Datei|<strong>$2</strong> Dateie|<strong>nix</strong>}} zeröckjehollt',
'undeletedfiles'               => '{{PLURAL:$1|Ein Datei|<strong>$1</strong> Dateie|<strong>Kein</strong> Dateie}} zeröckjehollt',
'cannotundelete'               => '<strong>Dä.</strong> Dat Zeröckholle jing donevve. Mach sinn, dat ene andere Metmaacher flöcker wor, un et ald et eets jedon hät, un jetz es die Sigg ald widder do jewäse.',
'undeletedpage'                => '<strong>De Sigg „$1“ es jetz widder do</strong>
Luur Der et [[Special:Log/delete|Logboch met de fottjeschmesse Sigge]] aan, do häs De de Neuste fottjeschmesse
un widder herjehollte Sigge.',
'undelete-header'              => 'Loor Der [[Special:Log/delete|{{LCFIRST:{{int:deletionlog}}}}]] aan, doh fengks De de och neulesch fottjeschmesse Sigge.',
'undelete-search-box'          => 'Noh fottjeschmesse Sigge söke',
'undelete-search-prefix'       => 'Zeisch Sigge, di aanfange met:',
'undelete-search-submit'       => 'Sööke',
'undelete-no-results'          => 'Mer han em Aschiif kei Sigg, wo dä Bejreff drop paß, womet De am Söke beß.',
'undelete-filename-mismatch'   => 'Dä Dattei ier Version fun dä Zick $1 kunnte mer nit zeröck holle: Di Datteiname paßße nit zersamme.',
'undelete-bad-store-key'       => 'Dä Dattei ier Version fun dä Zick $1 kunnte mer nit zeröck holle: Di Datei wohr ald beim Fottschmieße ja nimmieh do.',
'undelete-cleanup-error'       => 'Fähler beim Fottschmieße vun de Archiv-Version „$1“, die nit jebruch wood.',
'undelete-missing-filearchive' => 'De Datei met dä Archiv-Nommer $1 künne mer nit zerök holle. Di ham_mer nit in de Datebangk. Künnt sinn, di es ald zeröckjehollt.',
'undelete-error-short'         => 'Fähler beim Zerökholle fun de Datei $1',
'undelete-error-long'          => 'Mer wollte en Datei widder zeröckholle, ävver dobei sin_er Fääler opjefalle:

$1',
'undelete-show-file-confirm'   => 'Wells De dä Datei „<nowiki>$1</nowiki>“ ier fottjeschmesse Version vum $2 öm $3 Uhr werklesch sinn?',
'undelete-show-file-submit'    => 'Jo',

# Namespace form on various pages
'namespace'      => 'Appachtemeng:',
'invert'         => 'dun de Uswahl ömdrije',
'blanknamespace' => '(Atikkele)',

# Contributions
'contributions'       => 'Däm Metmaacher sing Beidräch',
'contributions-title' => 'Beidräsch fum  $1',
'mycontris'           => 'ming Beidräch',
'contribsub2'         => 'För dä Metmaacher: $1 ($2)',
'nocontribs'          => 'Mer han kein Änderunge jefonge, en de Logböcher, die do passe däte.',
'uctop'               => ' (Neuste)',
'month'               => 'un Moohnt:',
'year'                => 'Beß Johr:',

'sp-contributions-newbies'             => 'Nor neu Metmaacher ier Beidräg zeije',
'sp-contributions-newbies-sub'         => 'För neu Metmaacher',
'sp-contributions-newbies-title'       => 'Neu Metmaacher ier Beidräsch',
'sp-contributions-blocklog'            => 'Logboch met Metmaacher-Sperre',
'sp-contributions-deleted'             => 'Fottjeschmesse Beidrääsch',
'sp-contributions-uploads'             => 'huhjelaade Dateie',
'sp-contributions-logs'                => 'Logböcher',
'sp-contributions-talk'                => 'Klaaf',
'sp-contributions-userrights'          => 'Räächde verwalde',
'sp-contributions-blocked-notice'      => 'Heh dä Metmaacher es em Momang jespert, Dä letzte Enndraach em Logbooch doh drövver kütt jez als ene Henwiiß:',
'sp-contributions-blocked-notice-anon' => 'Heh di <i lang="en">IP</i>-Address es em Momang jesperrt.
De neuste Sperr ier Enndraach em Logbooch es:',
'sp-contributions-search'              => 'Söök noh Metmaacher ier Beidräg',
'sp-contributions-username'            => 'Metmaachername odder IP-Address:',
'sp-contributions-toponly'             => 'Bloß neuste Versione zeije',
'sp-contributions-submit'              => 'Sööke',

# What links here
'whatlinkshere'            => 'Wat noh heh link',
'whatlinkshere-title'      => 'Sigge, woh Links op „$1“ dren sen',
'whatlinkshere-page'       => 'Sigg:',
'whatlinkshere-backlink'   => '←&nbsp;$1',
'linkshere'                => 'Dat sin de Sigge, die op <strong>„[[:$1]]“</strong> linke dun:',
'nolinkshere'              => 'Kein Sigg link noh <strong>„[[:$1]]“</strong>.',
'nolinkshere-ns'           => 'Nix link op <strong>„[[:$1]]“</strong> en dämm Appachtemang.',
'isredirect'               => 'Ömleidungssigg',
'istemplate'               => 'weed enjeföch',
'isimage'                  => 'Link obb_en Datei',
'whatlinkshere-prev'       => 'de vörijje {{PLURAL:$1||$1|noll}} zeije',
'whatlinkshere-next'       => 'de nächste {{PLURAL:$1||$1|noll}} zeije',
'whatlinkshere-links'      => '← Links',
'whatlinkshere-hideredirs' => '$1 de Ömleidunge',
'whatlinkshere-hidetrans'  => '$1 de Oproofe',
'whatlinkshere-hidelinks'  => '$1 de nommale Links',
'whatlinkshere-hideimages' => '$1 de Links op Dateie',
'whatlinkshere-filters'    => 'Ußsööke',

# Block/unblock
'blockip'                         => 'Metmaacher sperre',
'blockip-title'                   => 'Metmaacher Schpärre',
'blockip-legend'                  => 'Metmaacher ov IP-Adresse Sperre',
'blockiptext'                     => 'Hee kanns De bestemmte Metmaacher oder IP-Adresse sperre, su dat se hee em Wiki nit mieh schrieve und Sigge ändere künne.
Dat sollt nor jedon wääde om sujenannte Vandaale ze bremse. Un mer müsse uns dobei natörlich aan uns [[{{MediaWiki:Policy-url}}|Rejelle]] för esu en Fäll halde.
Drag bei „Aanlass“ ene möchlichs jenaue Jrund en, wöröm dat Sperre passeet. Nenn un Link op de Sigge wo Einer kapott jemaat hät, zem Beispill.',
'ipaddress'                       => 'IP-Adress',
'ipadressorusername'              => 'IP Adress oder Metmaacher Name',
'ipbexpiry'                       => 'Duur, för wie lang',
'ipbreason'                       => 'Aanlass:',
'ipbreasonotherlist'              => 'Ne andere Bejründung',
'ipbreason-dropdown'              => '* Alljemein Jrönd för et Sperre
** hät fekeehte Behouptunge udder Leeje en Atikele jeschrevve
** hät Sigge fottjeschmesse udder leddig jemaat
** hält sesch iewesch nit aan de Rejelle övver de Links op anger Websigge un jäje der Link-SPAM
** hät Sigge met Shtuß udder Kauderwelsch drop aajelaat udder Keu en Sigge jedonn
** deit Medmaacher bedrohe, beleijije, udder schlääsch maache
** hät mieh wie eine Metmaachername un deit domet Schmuu maache
* Op der Name betrocke Jrönd
** esu ene Metmaacher-Name wolle mer nit
* Op en IP-Adräß betrocke Jrönd
** dat es en Proxy-ẞööver övver dänn de Lück zo vill Driß aanjestellt han',
'ipbanononly'                     => 'Nor de namelose Metmaacher sperre',
'ipbcreateaccount'                => 'Et Neu-Aanmelde verbeede',
'ipbemailban'                     => 'Et <i lang="en">e-mail</i>-Verschecke ongerbenge',
'ipbenableautoblock'              => 'Dun automatisch de letzte IP-Adress sperre, die dä Metmaacher jehatt hät, un och all die IP-Adresse, vun wo dä versök, jet ze ändere.',
'ipbsubmit'                       => 'Dun dä Metmaacher sperre',
'ipbother'                        => 'För en ander Duur:',
'ipboptions'                      => '2 Stund:2 hours,1 Dach:1 day,3 Däch:3 days,1 Woch:1 week,2 Woche:2 weeks,1 Mond:1 month,3 Mond:3 months,6 Mond:6 months,1 Johr:1 year,Unbejrenz:infinite',
'ipbotheroption'                  => 'Söns wie lang',
'ipbotherreason'                  => 'Ander Jrund oder Zosätzlich:',
'ipbhidename'                     => 'Don däm Metmaacher singe Name versteiche, en de Leste un däm sing Änderunge.',
'ipbwatchuser'                    => 'Op däm Metmaacher sing Metmaachersigg un Klaafsigg oppasse',
'ipballowusertalk'                => 'Lohß dä Metmaacher an sing eije Klaafsigg ändere, och su lang, wi sing Sperr dooht',
'ipb-change-block'                => 'Wigger sperre met dä neue Enstellunge',
'badipaddress'                    => 'Wat De do jeschrevve häs, dat es kein öntlije IP-Adress.',
'blockipsuccesssub'               => 'De IP-Adress es jetz jesperrt',
'blockipsuccesstext'              => '[[Special:Contributions/$1|$1]] es jetz jesperrt.
Luur op [[Special:IPBlockList|de Liss met jesperrte IP_Adresse]] wann de ne Üvverbleck üvver de Sperre han wells,
un och wann De se ändere wells.',
'ipb-edit-dropdown'               => 'De Jründ för et Sperre beärrbejde',
'ipb-unblock-addr'                => '„$1“ widder zohlohße',
'ipb-unblock'                     => 'En IP-Addräß ov ene Metmaacher widder zohlohße',
'ipb-blocklist'                   => 'All de Sperre för Metmaacher un IP-Adresse aanzeije, die jrad bestonn',
'ipb-blocklist-contribs'          => 'De Metmaacher ier Bäjdrähsch för „$1“',
'unblockip'                       => 'Dä Medmacher widder maache looße',
'unblockiptext'                   => 'Heh kanns De vörher jesperrte IP_Adresse oder Metmaacher widder freijevve, un dänne esu dat Rääch för ze Schrieve heh em Wiki widder jevve.',
'ipusubmit'                       => 'Sperr ophevve!',
'unblocked'                       => '[[User:$1|$1]] wood widder zojelooße',
'unblocked-id'                    => 'De Sperr met dä Nommer $1 es opjehovve',
'ipblocklist'                     => 'Liss met jesperrte IP-Adresse un Metmaacher Name',
'ipblocklist-legend'              => 'Ene jesperrte Metmaacher fenge',
'ipblocklist-username'            => 'Metmaacher-Name udder IP-Address:',
'ipblocklist-sh-userblocks'       => '$1 de einzel Metmaacher ier Sperre',
'ipblocklist-sh-tempblocks'       => '$1 de Sperre op Zick',
'ipblocklist-sh-addressblocks'    => '$1 de einzel IP-Addresse ier Sperre',
'ipblocklist-submit'              => 'Sööke',
'ipblocklist-localblock'          => 'Sperr heh em Wiki',
'ipblocklist-otherblocks'         => 'Ander {{PLURAL:$1|Sperr|Sperre|-nix-}}',
'blocklistline'                   => '$1, $2 hät „$3“ jesperrt ($4)',
'infiniteblock'                   => 'för iwich',
'expiringblock'                   => 'leuf aam $1 öm $2 Uhr uß',
'anononlyblock'                   => 'nor namelose',
'noautoblockblock'                => 'automatisch Sperre avjeschalt',
'createaccountblock'              => 'Aanmelde es nit müjjelich',
'emailblock'                      => 'Et E-Mail-Schecke es jrad jespert',
'blocklist-nousertalk'            => 'darref de eieje Klaafsigg nit änndere',
'ipblocklist-empty'               => 'Do es nix en de Sperrleß.',
'ipblocklist-no-results'          => 'Dä Metmaacher udder di IP-Adrress es janit jesperrt.',
'blocklink'                       => 'Sperre',
'unblocklink'                     => 'widder freijevve',
'change-blocklink'                => 'Sperr ändere',
'contribslink'                    => 'Beidräch',
'autoblocker'                     => 'Automattisch jesperrt. Ding IP_Adress wood vör kootem vun däm Metmaacher „[[User:$1|$1]]“ jebruch. Dä es jesperrt woode wäje: „$2“',
'blocklogpage'                    => 'Logboch met Metmaacher-Sperre',
'blocklog-showlog'                => 'Heh dä Metmaacher es ald fröjer jeshperrt woode. Dat Logbooch vum Metmaacher-Sperre onge künnt doh jät mieh zoh saare.',
'blocklog-showsuppresslog'        => 'Heh dä Metmaacher es ald fröjer jeshperrt un vershtoche woode. Dat Logbooch vum Metmaacher-Vershteishe onge künnt doh jät mieh zoh saare.',
'blocklogentry'                   => 'hät „[[$1]]“ fö de Zick vun $2 jesperrt. $3',
'reblock-logentry'                => 'hät di Sperr för dä „[[$1]]“ met dä Duuer fun $2 $3 jeändert',
'blocklogtext'                    => 'Heh es dat Logboch för et Metmaacher Sperre un Freijevve.
Automatich jesperrte IP-Adresse sin nit heh, ävver en de [[Special:IPBlockList|{{int:ipblocklist}}]] ze finge.',
'unblocklogentry'                 => 'Metmaacher „$1“ freijejovve',
'block-log-flags-anononly'        => 'nor de namelose Metmaacher sperre',
'block-log-flags-nocreate'        => 'neu Metmaacher aanlääje es verbodde',
'block-log-flags-noautoblock'     => 'nit automatesch all däm sing IP-Adresse sperre',
'block-log-flags-noemail'         => 'och et E-Mail Verschecke sperre',
'block-log-flags-nousertalk'      => 'kann de eije Klaafsigg nit ändere',
'block-log-flags-angry-autoblock' => 'automatesch all däm sing IP-Adresse sperre, un noch mieh',
'block-log-flags-hiddenname'      => 'Däm Metmaacher singe Name es för de Öffentleschkeit vershtoche',
'range_block_disabled'            => 'Adresse Jebeede ze sperre, es nit erlaub.',
'ipb_expiry_invalid'              => 'De Duur es Dress. Jevv se richtich aan.',
'ipb_expiry_temp'                 => 'Sperre för Metmaacher met verstoche Name mößße för iewish doore.',
'ipb_hide_invalid'                => 'Kann dä Metmaacher nit vershteishe. Müjjelesch, dat dä zoh vill Änderunge jemaat hät.',
'ipb_already_blocked'             => '„$1“ es ald jesperrt',
'ipb-needreblock'                 => '== Ald jespert ==
Dä Metmaacher „$1“ es ald jesperrt. Wellß De de Enstellunge för di Spär ändere?',
'ipb-otherblocks-header'          => 'Ander {{PLURAL:$1|Sperr|Sperre|-nix-}}',
'ipb_cant_unblock'                => '<strong>Ene Fähler:</strong> En Sperr met dä Nummer $1 es nit ze finge. Se künnt ald widder freijejovve woode sin.',
'ipb_blocked_as_range'            => 'Dat jeit nit. De IP-Adress „$1“ es nit tirek jesperrt. Se es ävver en däm jesperrte Bereich „$2“ dren. Die Sperr kam_mer ophevve. Donoh kam_mer och kleiner Aandeile fun däm Bereich widder neu sperre. Di Adress alleins kam_mer ävver nit freijevve.',
'ip_range_invalid'                => 'Dä Bereich vun IP_Adresse es nit en Oodnung.',
'ip_range_toolarge'               => 'Berette övver /$1 ze sperre is nit zohjelohße, bloß kleiner.',
'blockme'                         => 'Open_Proxy_Blocker',
'proxyblocker'                    => 'Open_Proxy_Blocker',
'proxyblocker-disabled'           => 'Di Funxjon es ußjeschalldt.',
'proxyblockreason'                => 'Unger Ding IP_Adress läuf ene offe Proxy.
Dröm kanns De heh em Wiki nix maache.
Schwaad met Dingem System-Minsch udder Netzwerk-Techniker udder ISP (<i lang="en">Internet Service Provider</i>)
un verzäll dänne vun däm ärrje Risiko för de Secherheit fun dänne ehr Rääschnere!',
'proxyblocksuccess'               => 'Jedonn.',
'sorbs'                           => '<i lang="en">DNSBL</i>',
'sorbsreason'                     => 'Ding IP-Adress weed en de DNSbl als ene offe Proxy jeliss. Schwaad met Dingem System-Minsch oder Netzwerk-Techniker (ISP Internet Service Provider) drüvver, un verzäll dänne vun däm Risiko för ehr Secherheit!',
'sorbs_create_account_reason'     => 'Ding IP-Adress weed en de DNSbl als ene offe Proxy jeliss. Dröm kanns De Dich heh em Wiki nit als ene neue Metmaacher aanmelde. Schwaad met Dingem System-Minsch oder Netzwerk-Techniker oder (ISP Internet Service Provider) drüvver, un verzäll dänne vun däm Risiko för ehr Secherheit!',
'cant-block-while-blocked'        => 'Do kanns ander Metmaacher nit sperre, esu lang wi De sellver jesperrt bes.',
'cant-see-hidden-user'            => 'Dä Metmaacher, dä De shperre wells, es al jeshperrt un verschtoche. Weil De nit dat Rääsch häs. Metmaacher ze vershteiche (<code>hideuser</code>), kanns De däm sing Sperr och nit ändere.',
'ipbblocked'                      => 'Do kanns kein ander Metmaachere sperrre, weil De sellver jesperrt bes',
'ipbnounblockself'                => 'Do kanns nit sellver ophävve, dat De jesperrt bes',

# Developer tools
'lockdb'              => 'Daatebank sperre',
'unlockdb'            => 'Daatebank freijevve',
'lockdbtext'          => 'Nohm Sperre kann keiner mieh Änderunge maache an sing Oppassliss, aan Enstellunge, Atikele, uew. un neu Metmaacher jitt et och nit. Bes de secher, datte dat wells?',
'unlockdbtext'        => 'Nohm Freijevve es de Daatebank nit mieh jesperrt, un all de normale Änderunge wääde widder müjjelich. Bes de secher, dat De dat wells?',
'lockconfirm'         => 'Jo, ich well de Daatebank jesperrt han.',
'unlockconfirm'       => 'Jo, ich well de Daatebank freijevve.',
'lockbtn'             => 'Daatebank sperre',
'unlockbtn'           => 'Daatebank freijevve',
'locknoconfirm'       => 'Do häs kei Hökche en däm Feld zem Bestätije jemaht.',
'lockdbsuccesssub'    => 'De Daatebank es jetz jesperrt',
'unlockdbsuccesssub'  => 'De Daatebank es jetz freijejovve',
'lockdbsuccesstext'   => '{{ucfirst:{{GRAMMAR:Genitive sing|{{SITENAME}}}}}} Daatebank jetz jesperrt.<br /> Dun se widder [[Special:UnlockDB|freijevve]], wann Ding Waadung eröm es.',
'unlockdbsuccesstext' => 'De Daatebank es jetz freijejovve.',
'lockfilenotwritable' => 'De Datei, wo de Daatebank met jesperrt wääde wööd, künne mer nit aanläje, oder nit dren schrieve. Esu ene Dress! Dat mööt dä Websörver ävver künne! Verzäll dat enem Verantwortliche för de Installation vun däm ẞööver oder repareer et selvs, wann De et kanns.',
'databasenotlocked'   => '<strong>Opjepass:</strong> De Daatebank es <strong>nit</strong> jesperrt.',

# Move page
'move-page'                    => 'De Sigg „$1“ ömnenne',
'move-page-legend'             => 'Sigg Ömnenne',
'movepagetext'                 => "Heh kanns De en Sigg ömnenne.
Domet kritt die Sigg ene neue Name, un all vörherije Versione vun dä Sigg och.
Unger däm ahle Tittel weed automatisch en Ömleitung op dä neue Tittel enjedrage.

Do kannß dat Höksche setze domet Ömleidonge automattesch aanjepaß wääde, di op dä ahle Tittel zeije — dat weet ävver nur allmählesch pö a pö hengerher jemaat.
Links op dä ahle Tittel blieve ävver wie se wore, wann De dat Höksche nit setz.
Dat heiß, dann moß De selver nohluure, ov do jetz [[Special:DoubleRedirects|dubbelde Ömleidunge]] udder [[Special:BrokenRedirects|kapodde Ömleidunge]] bei eruskumme.
Wann De en Sigg ömnenne deis, häs Do och doför ze sorje, dat de betroffene Links do henjonn, wo se hen jonn solle.
Alsu holl Der de Liss „Wat noh heh link“ fun dä Sigg heh un jangk se dorch!

De Sigg weed '''nit''' ömjenannt, wann et met däm neue Name ald en Sigg jitt, '''ußer''' et es nix drop, oder et es en Ömleitung un se es noch nie jeändert woode.
Esu kam_mer en Sigg jlich widder zeröck ömnenne, wam_mer sich bem Ömnenne verdonn hät, un mer kann och kein Sigge kapottmaache, wo ald jet drop steiht.

'''Oppjepass!'''
Wat beim Ömnenne erus kütt, künnt en opfällije un villeich stürende Änderung aam Wiki sin, besönders bei öff jebruchte Sigge.
Alsu bes secher, dat De versteihs, wat De heh am maache bes, ih dat De et mähs!",
'movepagetext-noredirectfixer' => "Heh kanns De en Sigg ömnenne.
Domet kritt die Sigg ene neue Name, un all vörherije Versione vun dä Sigg och.
Unger däm ahle Tittel weed automatisch en Ömleitung op dä neue Tittel enjedrage.

Links op dä ahle Tittel blieve ävver, wie se wore.
Dat heiß, Do muss selver nohluure, ov do jetz [[Special:DoubleRedirects|dubbelde]] oder [[Special:BrokenRedirects|kapodde Ömleidunge]] bei eruskumme.
Wann De en Sigg ömnenne deis, häs Do och doför ze sorje, dat de betroffe Links do henjonn, wo se hen jonn solle.
Alsu holl Der de Liss „Wat noh heh link“ fun dä Sigg heh un jangk se durch!

De Sigg weed '''nit''' ömjenannt, wann et met däm neue Tittel ald en Sigg jitt, '''ußer''' do es nix drop, oder et es en Ömleitung un se es noch nie jeändert woode.
Esu kam_mer en Sigg jlich widder retuur ömnenne, wam_mer sich mem Ömnenne verdonn hät, un mer kann och kein Sigge kapottmaache, wo ald jet drop steiht.

'''Oppjepass!'''
Wat beim Ömnenne erus kütt, künnt en opfällije un villeich stürende Änderung aam Wiki sin, besönders bei öff jebruchte Sigge.
Alsu bes secher, dat De versteihs, wat De heh am maache bes, ih dat De et mähs!",
'movepagetalktext'             => "Dä Sigg ehr Klaafsigg, wann se ein hät, weed automatisch met  ömjenannt, '''usser''' wann:
* de Sigg en en ander Appachtemeng kütt,
* en Klaafsigg met däm neue Name ald do es, un et steiht och jet drop,
* De unge en däm Kääsje '''kei''' Hökche aan häs.
En dänne Fäll, muss De Der dä Enhald vun dä Klaafsigge selvs vörnemme, un eröm kopeere watte bruchs.",
'movearticle'                  => 'Sigg zem Ömnenne:',
'moveuserpage-warning'         => "'''Opjepaß:''' Do wells en Metmaachersigg ömnänne, domet weed ävver dä Metmaacher sellver ''nit'' met ömjenannt.",
'movenologin'                  => 'Nit Enjelogg',
'movenologintext'              => 'Do mööts ald aanjemeldt un [[Special:UserLogin|enjelogg]] sin, öm en Sigg ömzenenne.',
'movenotallowed'               => 'Do kriss nit erlaub, en däm Wiki heh de Sigge ömzenenne.',
'movenotallowedfile'           => 'Do häs nit dat Rääsch, Dateie ömzenenne.',
'cant-move-user-page'          => 'Do häs nit dat Rääsch, öm enem Metmaacher sing eetzte Sigg ömzedeufe.',
'cant-move-to-user-page'       => 'Do häs nit dat Rääsch, en Sigg tirkäk op en Metmaacher-Sigg ömzenänne, Do kanns se ävver op en Ungersigg dofun ömnenne.',
'newtitle'                     => 'op dä neue Name',
'move-watch'                   => 'Op die Sigg heh oppasse',
'movepagebtn'                  => 'Ömnenne',
'pagemovedsub'                 => 'Dat Ömnenne hät jeflupp',
'movepage-moved'               => "'''De Sigg „$1“ es jez en „$2“ ömjenannt.'''",
'movepage-moved-redirect'      => 'En Ömleidung es aanjelaat woode.',
'movepage-moved-noredirect'    => 'Kein Ömleidung woodt aanjelaat.',
'articleexists'                => "De Sigg met däm Name jitt et ald, oder dä Name ka'mer oder darf mer nit bruche.<br />Do muss Der ene andere Name ussöke.",
'cantmove-titleprotected'      => 'Die Sigg ömzenänne es esu nit müjjelesch, dänn dä neu Name vun dä Sigg es jäje et Neu-Aanlääje jeschötz.',
'talkexists'                   => '<strong>Opjepass:</strong> De Sigg selver woodt jetz ömjenannt, ävver dä ehr Klaafsigg kunnte mer nit met ömnenne. Et jitt ald ein met däm neue Name. Bes esu jod un dun die zwei vun Hand zosamme läje!',
'movedto'                      => 'ömjenannt en',
'movetalk'                     => 'dä ehr Klaafsigg met ömnenne, wat et jeiht',
'move-subpages'                => 'Don de Ongersigge met_ömnënne, wann_er do sin.
{{PLURAL:$1|Bloß ein Sigg weed|Beß $1 Sigge weede|Kei einzel Sigg weed}} ömjenannt.',
'move-talk-subpages'           => 'Don de Ongersigge vun de Klaafsigge met_ömnënne, wann_er do sin.
{{PLURAL:$1|Bloß ein Sigg weed|Beß $1 Sigge weede|Kei einzel Sigg weed}} ömjenannt.',
'movepage-page-exists'         => 'En Sigg „$1“ ham_mer ald, un di bliif och beshtonn, mer don se nit ottomatėsch ußtuusche.',
'movepage-page-moved'          => 'Di eejemoolijje Sigg „$1“ es jëz op „$2“ ömjenannt.',
'movepage-page-unmoved'        => 'Mer kůnnte di Sigg „$1“ nit op „$2“ ömnënne.',
'movepage-max-pages'           => 'Mer han jëtz {{PLURAL:$1|ëijn Sigg|$1 Sigge|kein Sigg}} ömjenanndt. Mieh jeiht nit automatėsch.',
'1movedto2'                    => 'hät de Sigg vun „[[$1]]“ en „[[$2]]“ ömjenannt.',
'1movedto2_redir'              => 'hät de Sigg vun „[[$1]]“ en „[[$2]]“ ömjenannt un doför de ahl Ömleitungs-Sigg fottjeschmesse.',
'move-redirect-suppressed'     => 'Ömleidung ongerdrök',
'movelogpage'                  => 'Logboch vum Sigge Ömnenne',
'movelogpagetext'              => 'Heh sin de Neuste ömjenannte Sigge opjeliss, un wä et jedon hät.',
'movesubpage'                  => '{{PLURAL:$1|Ungersigg|Ungersigge|Ungersigge}}',
'movesubpagetext'              => 'Die Sigge hät {{PLURAL:$1|ein Ungersigg|$1 Ungersigge|kei Ungersigge}}.',
'movenosubpage'                => 'Die Sigg hät kei Ungersigge.',
'movereason'                   => 'Aanlass:',
'revertmove'                   => 'Et Ömnänne zerök_nämme',
'delete_and_move'              => 'Fottschmieße un Ömnenne',
'delete_and_move_text'         => '== Dä! Dubbelte Name ==
Di Sigg „[[:$1]]“ jitt et ald. Wollts De se fottschmieße, öm heh di Sigg ömnenne ze künne?',
'delete_and_move_confirm'      => 'Jo, dun di Sigg fottschmieße.',
'delete_and_move_reason'       => 'Fottjeschmesse, öm Platz för et Ömnenne ze maache',
'selfmove'                     => 'Du Doof! - dä ahle Name un dä neue Name es däselve - do hät et Ömnenne winnich Senn.',
'immobile-source-namespace'    => 'Sigge en dämm Appachtemang „$1“ künne nit ömjenannt wääde',
'immobile-target-namespace'    => 'Sigge künne nit en dat Appachtemang „$1“ erenn ömjenannt wääde',
'immobile-target-namespace-iw' => 'Ene Ingerwikilink es nix, woh mer en Sigg hen ömnenne künnt!',
'immobile-source-page'         => 'Di Sigg kann nit ömjenannt wääde.',
'immobile-target-page'         => 'Op dä Tittel kann kei Sigg ömjenannt wääde.',
'imagenocrossnamespace'        => 'Dateije kam_mer nor in et Appachtemang „{{ns:file}}“ donn, noh woanders hen kam_mer se och nit ömnemme!',
'nonfile-cannot-move-to-file'  => 'Mer kann nix uußer Datteije esu ömnänne, dat et em Appachtemang „{{ns:file}}“ landt',
'imagetypemismatch'            => 'De neu Datei-Endong moß met däm Datei-Tüp zesamme passe',
'imageinvalidfilename'         => 'Dä Ziel-Name för de Datei es verkeht',
'fix-double-redirects'         => 'Don noh em Ömnenne de Ömleidunge automattesch ändere, di noch op dä ahle Tittel zeije, also de neu entshtande dubbelte Ömleidunge oplöse.',
'move-leave-redirect'          => 'Donn en Ömleidung doför ennreschte',
'protectedpagemovewarning'     => "'''Opjepaß:''' Heh di Sigg es jespert su dat blooß de Wiki-Kööbeße se ömnänne künne.
Heh kütt der neuste Enndrach em Logbooch doh drövver:",
'semiprotectedpagemovewarning' => "'''Opjepaß:''' Heh di Sigg es jespert su dat blooß aanjemeldte Metmaacher se ömnänne künne.
Heh kütt der neuste Enndrach em Logbooch doh drövver:",
'move-over-sharedrepo'         => '==Di Dattei jidd_et ald==
En Dattei [[:$1]] jidd_et ald en enem jemeinsame Beschtand. En annder Dattei op dä Name ömzenänne sorresch doför, dat mer aan di Dattei em jemeinsame Beschtand vun heh uß donoh nit mieh draan kütt.',
'file-exists-sharedrepo'       => 'Dinge Name för die Dattei weed ald jebruch, un zwa en enem jemeinsame Bestand vun Dateije.
Dröm söhk ene andere Name uß.',

# Export
'export'            => 'Sigge Exporteere',
'exporttext'        => "Heh exportees De dä Tex un de Eijeschaffte vun ener Sigg, oder vun enem Knubbel Sigge, de aktuelle Version, met oder ohne ehr ählere Versione.
Dat Janze es enjepack en XML.
Dat ka'mer en en ander Wiki — wann et och met dä MediaWiki-Soffwär läuf — üvver de Sigg „[[Special:Import|Import]]“ do widder importeere.

Schriev de Titele vun dä Sigge en dat Feld för Tex enzejevve, unge, eine Titel en jede Reih.
Dann dun onoch ussöke, ov De all de vörherije Versione vun dä Sigge han wells, oder nor de aktuelle met dä Informatione vun de letzte Änderung.

En däm Fall künns De, för en einzelne Sigg, och ene tirekte Link bruche, zom Beispill „[[{{#Special:Export}}/{{MediaWiki:Mainpage}}]]“ för de Sigg „[[{{MediaWiki:Mainpage}}]]“ ze exporteere.",
'exportcuronly'     => 'Bloß de aktuelle Version usjevve (un <strong>nit</strong> de janze ahle Versione onoch met dobei dun)',
'exportnohistory'   => '----
<strong>Opjepass:</strong> de janze Versione Exporteere es heh em Wiki avjeschalt. Schad, ävver et wör en
zo jroße Lass för dä ẞööver.',
'export-submit'     => 'Loss_Jonn!',
'export-addcattext' => 'Sigge dobei donn us dä Saachjrupp:',
'export-addcat'     => 'Dobei donn',
'export-addnstext'  => 'Sigge dobei donn uß dämm Appachtemang:',
'export-addns'      => 'Dobei Donn!',
'export-download'   => 'Als en XML-Datei afspeichere',
'export-templates'  => 'De Schablone met expochteere, die die Sigge bruche',
'export-pagelinks'  => 'Donn de Sigge metnämme, wo vun heh Lengks drop jon, un vun do wigger, bes esu vill Schrette:',

# Namespace 8 related
'allmessages'                   => 'Aanzeije-Baustein, Täxte, un Nohreeschte vum Wiki-System',
'allmessagesname'               => 'Name',
'allmessagesdefault'            => 'Dä standaadmäßije Tex',
'allmessagescurrent'            => 'Esu es dä Tex jetz',
'allmessagestext'               => 'Heh kütt en Liss met Texte, Texstöck, un Nohreechte em Appachtemeng „MediaWiki“ — Do draan Ändere löht et Wiki anders ußsin, dat darf dröm nit Jede maache.
Wenn De jenerell aan [http://www.mediawiki.org/wiki/Localisation MediaWiki singe Översezung] jet anders han wells, do jangk noh [http://translatewiki.net translatewiki.net].',
'allmessagesnotsupportedDB'     => '<strong>Dat wor nix!</strong> Mer künne „{{#special:allmessages}}“ nit zeije, <code>$wgUseDatabaseMessages</code> es usjeschalt!',
'allmessages-filter-legend'     => 'Ußsöhke — wat för en Täxte o Nohreeshte aazeije?',
'allmessages-filter'            => 'Zohshtand:',
'allmessages-filter-unmodified' => 'nit jeändert',
'allmessages-filter-all'        => 'ejaal',
'allmessages-filter-modified'   => 'heh em Wiki jeändert',
'allmessages-prefix'            => 'Name fängk aan met:',
'allmessages-language'          => 'Shprooch:',
'allmessages-filter-submit'     => 'Lohß Jonn!',

# Thumbnails
'thumbnail-more'           => 'Jrößer aanzeije',
'filemissing'              => 'Datei es nit do',
'thumbnail_error'          => 'Ene Fähler es opjetauch beim Maache vun enem Breefmarke/Thumbnail-Beldche: „$1“',
'djvu_page_error'          => 'De DjVu-Sgg es ußerhallef',
'djvu_no_xml'              => 'De XML-Date för di DjVu-Datei kunnte mer nit afrofe',
'thumbnail_invalid_params' => 'Ene Parameter för et Breefmarke-Belldsche (<i lang="en">thumbnail</i>) Maache wohr nit en Odenung',
'thumbnail_dest_directory' => 'Dat Verzeichnis för dat erin ze donn kunte mer nit aanlääje.',
'thumbnail_image-type'     => 'Di Zoot Beld künne mer nit met ömjonn',
'thumbnail_gd-library'     => 'Vun dä <i lang="en">GD</i> Projramm_Biplijotheek fäählt en Funkßuhn: „$1“',
'thumbnail_image-missing'  => 'Di Datei schingk nit doh ze sin: <code>$1</code>',

# Special:Import
'import'                     => 'Sigge Emporteere',
'importinterwiki'            => 'Trans Wiki Emport',
'import-interwiki-text'      => 'Wähl en Wiki un en Sigg zem Emporteere us.
Et Datum vun de Versione un de Metmaacher Name vun de Schriever wääde dobei metjenomme.
All de Trans Wiki Emporte wääde em [[Special:Log/import|Emport_Logboch]] fassjehallde.',
'import-interwiki-source'    => 'Quelle-Wiki un -Sigg:',
'import-interwiki-history'   => 'All de Versione vun dä Sigg heh kopeere',
'import-interwiki-templates' => 'All Schablone metnämme',
'import-interwiki-submit'    => 'Huhlade!',
'import-interwiki-namespace' => 'Dun de Sigge emporteere en dat Appachtemeng:',
'import-upload-filename'     => 'Dä Name fun dä Datei:',
'import-comment'             => 'Jrond:',
'importtext'                 => 'Dun de Daate met däm „[[Special:Export|Export]]“ vun do vun enem Wiki Exporteere, dobei dun et - etwa bei Dir om Rechner - avspeichere, un dann heh huhlade.',
'importstart'                => 'Ben Sigge am emporteere …',
'import-revision-count'      => '({{PLURAL:$1|ein Version|$1 Versione|kein Version}})',
'importnopages'              => 'Kein Sigg för ze Emporteere jefunge.',
'imported-log-entries'       => '{{PLURAL:$1|Eine Enndraach woodt|$1 Enndrääsch woodte|Keine Enndraach wood}} en et Logbooch empotteert.',
'importfailed'               => 'Dat Importeere es donevve jejange: $1',
'importunknownsource'        => 'Die Zoot Quell för et Emporteere kenne mer nit',
'importcantopen'             => 'Kunnt op de Datei för dä Emport nit zojriefe',
'importbadinterwiki'         => 'Verkihrte Interwiki Link',
'importnotext'               => 'En dä Datei wor nix dren enthallde, oder winnichstens keine Tex',
'importsuccess'              => 'Dat Emporteere hät jeflupp!',
'importhistoryconflict'      => 'Mer han zwei ahle Versione jefunge, die dun sich bieße - die ein wor ald do - de ander en dä Emport Datei. Künnt sin, Ehr hatt die Daate ald ens emporteet.',
'importnosources'            => 'Heh es kein Quell för dä Tikek-Emport vun ander Wikis enjerich.
Dat ahle Versione Huhlade es avjeschalt, un es nit müjjelich.',
'importnofile'               => 'Et wood kein Datei huhjelade för ze Emporteere.',
'importuploaderrorsize'      => 'De Import-Datei huhzelade jingk scheif, weil dat Denge jrößer wi äloup es.',
'importuploaderrorpartial'   => 'De Import-Datei huhzelade jingk scheif, weil dat Denge nit komplett zo eng transpotteet woode es. Do fäählt jet.',
'importuploaderrortemp'      => 'De Import-Datei huhzelade jingk scheif, weil e Zwescheverzeichnis fäählt.',
'import-parse-failure'       => 'Fäähler bem Import per XML:',
'import-noarticle'           => 'Kein Sigge do, för ze Emporteere!',
'import-nonewrevisions'      => 'Et sin kein neue Versione do, för ze Importeere, weil all de Versione vun heh ald fröjer empotteet wodte.',
'xml-error-string'           => '$1 — en {{PLURAL:$2|eetz|$2-}}te Reih en de {{PLURAL:$3|eetz|$3-}}te Spalde, dat ess_et {{PLURAL:$4|eetz|$4-}}te Byte: $5',
'import-upload'              => 'En XML-Datei impochteere',
'import-token-mismatch'      => 'Schadt. Et senn nit alle Date heh aanjekumme.
Bes esu joot, un versök et noch ens.',
'import-invalid-interwiki'   => 'Us dämm jenannte Wiki künne mer nix Importeere.',

# Import log
'importlogpage'                    => 'Logboch met emporteerte Sigge',
'importlogpagetext'                => 'Sigge met ehre Versione vun ander Wikis emporteere.',
'import-logentry-upload'           => '„[[$1]]“ emporteet fun enne huhjelade Dattei',
'import-logentry-upload-detail'    => '{{PLURAL:$1|ein Version|$1 Versione|kein Version}} emporteet',
'import-logentry-interwiki'        => 'hät tirek vum ander Wiki emporteet: „$1“',
'import-logentry-interwiki-detail' => '{{PLURAL:$1|ein Version|$1 Versione|kein Version}} vun „$2“',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Don Ding eije Metmaachersigg aanzeije',
'tooltip-pt-anonuserpage'         => 'Metmaachersigg för die IP-Adress, vun wo uß De jraad Ding Änderunge un Äjänzunge aam Wiki am maache bes',
'tooltip-pt-mytalk'               => 'Dun Ding eije Klaafsigg aanzeije',
'tooltip-pt-anontalk'             => 'Klaaf övver Änderunge, di vun dä IP-Adress uß jemaat wodte',
'tooltip-pt-preferences'          => 'De eije Enstellunge',
'tooltip-pt-watchlist'            => 'De Liss met de Sigge en Dinge eije Oppassliss',
'tooltip-pt-mycontris'            => 'en Liss met Dinge eije Beidräch',
'tooltip-pt-login'                => 'Do moß Desch nit Enlogge, kannz_E ävver jähn maache!',
'tooltip-pt-anonlogin'            => 'Wöhr nett wann De enlogge dääts, moß ävver nit.',
'tooltip-pt-logout'               => 'Uslogge',
'tooltip-ca-talk'                 => 'Dun die Sigg met däm Klaaf övver heh de Sigg aanzeije',
'tooltip-ca-edit'                 => 'De kanns die Sigg heh ändere — für em Avspeichere, donn eetß ens enen Bleck op de Vör-Aansich',
'tooltip-ca-addsection'           => 'Donn heh enne neue Afschnett opmaache.',
'tooltip-ca-viewsource'           => "Die Sigg es jeschötz. Dä Wikitex kam'mer ävver beloore.",
'tooltip-ca-history'              => 'Ällder Versione vun dä Sigg',
'tooltip-ca-protect'              => 'Dun die Sigg schötze',
'tooltip-ca-unprotect'            => 'Donn dä Schoz vun dä Sigg heh ophävve.',
'tooltip-ca-delete'               => 'Dun die Sigg fottschmieße',
'tooltip-ca-undelete'             => 'Don de Änderunge widder zerök holle, di aan dä Sigg heh jemat woode wore, ih dat se fottjeschmesse wood',
'tooltip-ca-move'                 => 'Dun die Sigg ömbenenne',
'tooltip-ca-watch'                => 'Dun die Sigg en Ding Oppassliss opnemme',
'tooltip-ca-unwatch'              => 'Schmieß die Sigg us Dinge eije Oppassliss erus',
'tooltip-search'                  => '{{ucfirst:{{GRAMMAR:en|{{SITENAME}}}}}} söke',
'tooltip-search-go'               => 'Jank noh dä Sigg med jenou dämm Name',
'tooltip-search-fulltext'         => 'Sök noh Sigge, wo dä Tex dren enthallde es',
'tooltip-p-logo'                  => 'Houpsigg',
'tooltip-n-mainpage'              => 'Houpsigk aanzeije',
'tooltip-n-mainpage-description'  => 'Jangk op de {{int:Mainpage}}.',
'tooltip-n-portal'                => 'Övver dat Projek heh, wat De donn un wie de metmaache kanns, wat wo ze fenge es',
'tooltip-n-currentevents'         => 'Heh kreß De e beßje Enfommazjohn övver wat jraad am Jang eß',
'tooltip-n-recentchanges'         => 'En Leß met de neuste Änderunge heh aam Wiki.',
'tooltip-n-randompage'            => 'Dun en janz zofällije Sigg ußßem Wikki trecke un aanzeije',
'tooltip-n-help'                  => 'Do kriss De jehollfe',
'tooltip-t-whatlinkshere'         => 'En Liss met all de Sigge, die ene Link noh heh han',
'tooltip-t-recentchangeslinked'   => 'De neuste Änderunge aan Sigge, wo vun heh dä Sigg uß Links drop jon',
'tooltip-feed-rss'                => 'Dä RSS-Abonnomang-Kannal (Feed) för heh di Sigg',
'tooltip-feed-atom'               => 'Dä Atom-Abonnomang-Kannal (Feed) för heh di Sigg',
'tooltip-t-contributions'         => 'Donn de Liß met Bedträch vun däm Metmaacher beloore',
'tooltip-t-emailuser'             => 'Scheck en E-Mail aan dä Metmaacher',
'tooltip-t-upload'                => 'Dateie huhlade',
'tooltip-t-specialpages'          => 'Liss met de {{int:nstab-special}}e',
'tooltip-t-print'                 => 'De Drock-Aansich för heh die Sigg',
'tooltip-t-permalink'             => 'Ene iewich haltbare Lenk (Permalink) op jenou die Version vun heh dä Sigg, die de jrad süühß un am beloore bes',
'tooltip-ca-nstab-main'           => 'Don dä Enhallt vun dä Sigg aanzeije',
'tooltip-ca-nstab-user'           => 'Dun die Metmaachersig aanzeije',
'tooltip-ca-nstab-media'          => 'Don de Sigg övver en Mediendatei aanzeije',
'tooltip-ca-nstab-special'        => "Dat is en {{int:nstab-special}}. Do kam'mer nix draan verändere.",
'tooltip-ca-nstab-project'        => 'Dun die Projeksigg aanzeije',
'tooltip-ca-nstab-image'          => 'Dun die Sigg üvver heh di Dattei aanzeije',
'tooltip-ca-nstab-mediawiki'      => 'En Täx vum MediaWiki-System aanzeije',
'tooltip-ca-nstab-template'       => 'Dun die Schabloon aanzeije',
'tooltip-ca-nstab-help'           => 'Donn en Sigg met Hölp aanzeije',
'tooltip-ca-nstab-category'       => 'Dun die Saachjrupp aanzeije',
'tooltip-minoredit'               => 'Deit Ding Änderunge als klein Mini-Änderunge markeere.',
'tooltip-save'                    => 'Deit Ding Änderunge avspeichere.',
'tooltip-preview'                 => 'Liss de Vör-Aansich vun dä Sigg un vun Dinge Änderunge ih datte se Avspeichere deis!',
'tooltip-diff'                    => 'Zeich Ding Änderunge am Tex aan.',
'tooltip-compareselectedversions' => 'Dun de Ungerscheid zwesche dä beids usjewählde Versione zeije.',
'tooltip-watch'                   => 'Op die Sigg heh oppasse.',
'tooltip-recreate'                => 'En fottjeschmesse Sigg widder zeröckholle',
'tooltip-upload'                  => 'Mem Dattei-Huhlaade loßlääje',
'tooltip-rollback'                => 'Nemmp alle Änderunge zeröck, di dä Läzde jemaat hät, dä aan dä Sigg övverhoup jet jedonn hät. Deit nimmieh frore un määd ene automattesche Endraach en „{{int:Summary}}“',
'tooltip-undo'                    => '„{{UCfirst:{{int:editundo}}}}“ määt der förije Zostand
fun dä Sigg op, zom Beärbeide un widder Afspeichere.
Esu kam_mer noch en Aanmerkung en „{{int:summary}}“ maache.',
'tooltip-preferences-save'        => 'Enstellunge faßhallde',
'tooltip-summary'                 => 'Jif en koote Zesammefassung en',

# Stylesheets
'common.css'      => '/* CSS hee aan dä Stell hät Uswirkunge op all Ovverflääsche */',
'standard.css'    => '/* CSS hee aan dä Stell wirrek nur op de Ovverflääsch "Klassesch" */',
'nostalgia.css'   => '/* CSS hee aan dä Stell wirrek nur op de Ovverflääsch "Nostaljesch" */',
'cologneblue.css' => '/* CSS hee aan dä Stell wirrek nur op de Ovverflääsch "Kölsch Blau" */',
'monobook.css'    => '/* CSS hee aan dä Stell wirrek nur op de Ovverflääsch "Monobook" */',
'myskin.css'      => '/* CSS hee aan dä Stell wirrek nur op de Ovverflääsch "Ming Skin" */',
'chick.css'       => '/* CSS hee aan dä Stell wirrek nur op de Ovverflääsch "Höhnsche" */',
'simple.css'      => '/* CSS hee aan dä Stell wirrek nur op de Ovverflääsch "Eijfach" */',
'modern.css'      => '/* CSS hee aan dä Stell wirrek nur op de Ovverflääsch "Modern" */',
'vector.css'      => '/* CSS hee aan dä Stell wirrek nur op de Ovverflääsch "Vector" */',
'print.css'       => '/* CSS hee aan dä Stell wirrek nur op et Sigge Drokke */',
'handheld.css'    => '/* dat CSS hee wirrek sesch uß op su jeannte Handheld-Apparaate, opjebout op de Ovverflääsch uß $wgHandheldStyle */',

# Scripts
'common.js'      => '/* Jedes JavaScrip hee kütt für jede Metmaacher in jede Sigg erinn */',
'standard.js'    => '/* De JavaSkrippte fun hee krijje alle Sigge met de Ovverflääsch "Klassesch" jescheck */',
'nostalgia.js'   => '/* De JavaSkrippte fun hee krijje alle Sigge met de Ovverflääsch "Nostaljesch" jescheck */',
'cologneblue.js' => '/* De JavaSkrippte fun hee krijje alle Sigge met de Ovverflääsch "Kölsch Blou" jescheck */',
'monobook.js'    => '/* De JavaSkrippte fun hee krijje alle Sigge met de Ovverflääsch "Monnobooch" jescheck */',
'myskin.js'      => '/* De JavaSkrippte fun hee krijje alle Sigge met de Ovverflääsch "Ming Skin" jescheck */',
'chick.js'       => '/* De JavaSkrippte fun hee krijje alle Sigge met de Ovverflääsch "Höhnsche" jescheck */',
'simple.js'      => '/* De JavaSkrippte fun hee krijje alle Sigge met de Ovverflääsch "Eijfach" jescheck */',
'modern.js'      => '/* De JavaSkrippte fun hee krijje alle Sigge met de Ovverflääsch "Modern" jescheck */',
'vector.js'      => '/* De JavaSkrippte fun hee krijje alle Sigge met de Ovverflääsch "Vector" jescheck */',

# Metadata
'nodublincore'      => 'De RDF_Meta_Daate vun de „Dublin Core“ Aat sin avjeschalt.',
'nocreativecommons' => 'De RDF_Meta_Daate vun de „Creative Commons“ Aat sin avjeschalt.',
'notacceptable'     => '<strong>Blöd:</strong> Dä Wiki_Sörver kann de Daate nit en einem Format erüvverjevve, wat Dinge Client oder Brauser verstonn künnt.',

# Attribution
'anonymous'        => 'Namelose {{PLURAL:$1|Metmaacher|Metmaacher|Metmaacher}} vun {{GRAMMAR:Dat|{{SITENAME}}}}',
'siteuser'         => '{{SITENAME}}-{{GENDER:$2|Metmaacher|Metmaacheren|Metmaacher|Metmaacher|Metmaacheren}} $1',
'anonuser'         => 'dä nameloose Metmaacher $1 {{GRAMMAR:Genitive vum|{{SITENAME}}}}',
'lastmodifiedatby' => 'Die Sigg heh wood et letz am $1 öm $2 Uhr {{GENDER:$4|vum|vun dä|vum|vum|vun dä}} $3 jeändert.',
'othercontribs'    => 'Bout op et Werk vun $1 op.',
'others'           => 'ander',
'siteusers'        => '{{PLURAL:$2|däm|de|keine}} {{PLURAL:$2|Metmaacher|Metmaachere|Metmaacher}} $1 aan {{GRAMMAR:Dat|{{SITENAME}}}}',
'anonusers'        => '{{PLURAL:$2|dä|de|keine}} nameloose Metmaacher $1 vun de translatewiki.net',
'creditspage'      => 'Üvver de Metmaacher un ehre Beidräch för heh die Sigg',
'nocredits'        => "För die Sigg ha'mer nix en de Liss.",

# Spam protection
'spamprotectiontitle' => 'SPAM_Schotz',
'spamprotectiontext'  => 'De Sigg, die de avspeichere wells, die weed vun unsem SPAM_Schotz nit durchjelooße. Dat kütt miehts vun enem Link op en fremde Sigg, di op de Schwazze Leß shteiht.',
'spamprotectionmatch' => 'Heh dä Tex hät dä SPAM_Schotz op der Plan jerofe: „<code>$1</code>“',
'spambot_username'    => 'SPAM fottschmieße',
'spam_reverting'      => 'De letzte Version ohne de Links op „$1“ widder zerröckjehollt.',
'spam_blanking'       => 'All die Versione hatte Links op „$1“, die sin jetz erus jemaht.',

# Info page
'infosubtitle'   => 'Üvver de Sigg',
'numedits'       => 'Aanzahl Änderunge aan däm Atikkel: <strong>$1</strong>',
'numtalkedits'   => 'Aanzahl Änderunge aan de Klaafsigg: <strong>$1</strong>',
'numwatchers'    => 'Aanzahl Oppasser: <strong>$1</strong>',
'numauthors'     => 'Aanzahl Metmaacher, die aan däm Atikkel met jeschrevve han: <strong>$1</strong>',
'numtalkauthors' => 'Aanzahl Metmaacher beim Klaaf: <strong>$1</strong>',

# Skin names
'skinname-standard'    => 'Klassesch',
'skinname-nostalgia'   => 'Nostaljesch',
'skinname-cologneblue' => 'Kölsch Blau',
'skinname-monobook'    => 'MonoBoch',
'skinname-myskin'      => 'Ming Skin',
'skinname-chick'       => 'Höhnche',
'skinname-simple'      => 'Eifach',
'skinname-modern'      => 'Modern',
'skinname-vector'      => 'Vektor',

# Math options
'mw_math_png'    => 'Immer nor PNG aanzeije',
'mw_math_simple' => 'En einfache Fäll maach HTML, söns PNG',
'mw_math_html'   => 'Maach HTML wann müjjelich, un söns PNG',
'mw_math_source' => 'Lohß et als TeX (jod för de Tex-Brausere)',
'mw_math_modern' => 'De bess Enstellung för de Brauser vun hück',
'mw_math_mathml' => 'Nemm „MathML“ wann müjjelich (em Probierstadium)',

# Math errors
'math_failure'          => 'Fähler vum Parser',
'math_unknown_error'    => 'Fähler, dä mer nit kenne',
'math_unknown_function' => 'en Funktion, die mer nit kenne',
'math_lexing_error'     => 'Fähler beim Lexing',
'math_syntax_error'     => 'Fähler en de Syntax',
'math_image_error'      => 'Dat Ömwandele noh PNG es donevve jejange. Dun ens noh de richtije Enstallation luure bei <code lang="en">latex</code> un <code lang="en">dvips</code>, udder bei <code lang="en">dvips</code>, un <code lang="en">gs</code>, un <code lang="en">convert</code>.',
'math_bad_tmpdir'       => 'Dat Zwescheverzeichnis för de mathematische Formele lööt sich nit aanläje oder nix eren schrieve. Dat es Dress. Sag et enem Wiki-Köbes oder enem ẞööver-Minsch.',
'math_bad_output'       => 'Dat Verzeichnis för de mathematische Formele lööt sich nit aanläje oder mer kann nix eren schrieve. Dat es Dress. Sag et enem Wiki-Köbes oder enem ẞööver-Minsch.',
'math_notexvc'          => 'Dat Projamm <code>texvc</code> haM_mer nit jefunge.
Sag et enemWiki-Köbes, enem ẞööver-Minsch, oder luur ens en dä
<code>math/README</code>.',

# Patrolling
'markaspatrolleddiff'                 => 'Nohjeluurt. Dun dat fasshallde.',
'markaspatrolledtext'                 => 'De Änderung es nohjeluert, dun dat fasshallde',
'markedaspatrolled'                   => 'Et Kennzeiche „Nohjeluurt“ speichere',
'markedaspatrolledtext'               => 'Et es jetz fassjehallde, dat de usjewählte Version vun dä Sigg „[[:$1]]“ nohjeluurt sin.',
'rcpatroldisabled'                    => 'Et Nohluure vun de letzte Änderunge es avjeschalt',
'rcpatroldisabledtext'                => 'Et Nohluure fun de letzte Änderunge es em Momang nit müjjelich.',
'markedaspatrollederror'              => 'Dat Kennzeiche „Nohjeluurt“ kunnt ich nit avspeichere.',
'markedaspatrollederrortext'          => 'Do muss en bestemmte Version ussöke.',
'markedaspatrollederror-noautopatrol' => 'Do darrefs Ding eije Änderunge nit op „Nohjeloort“ setze!',

# Patrol log
'patrol-log-page'      => 'Logboch vun de nohjeloorte Änderunge',
'patrol-log-header'    => '<!-- -->',
'patrol-log-line'      => 'hät $1 von „$2“ $3 nohjeloort.',
'patrol-log-auto'      => '(automatisch)',
'patrol-log-diff'      => 'de Version $1',
'log-show-hide-patrol' => '$1 et Logbuch vum Sigge nohlooere',

# Image deletion
'deletedrevision'                 => 'De ahl Version „$1“ es fottjeschmesse',
'filedeleteerror-short'           => 'Fäähler bem Datei-Fottschmieße: $1',
'filedeleteerror-long'            => 'Bem fosooch, de Datei fottzeschmieße, hatte mer Fäähler:

$1',
'filedelete-missing'              => 'De Datei „$1“ künne mer nit fottschmieße, Leevje, di jidd_et janit.',
'filedelete-old-unregistered'     => 'En Version „$1“ fun dä Datei ham_mer nit in de Datebank.',
'filedelete-current-unregistered' => 'De aanjejovve Datei „$1“ ham_mer nit in de Datebank.',
'filedelete-archive-read-only'    => 'Unsere Webßöver kann udder darf nix en dat Aschif-Verzeichnis „$1“ eren schrieve.',

# Browsing diffs
'previousdiff' => '← De Änderung dovör zeije',
'nextdiff'     => 'De Änderung donoh zeije →',

# Media information
'mediawarning'         => '<strong>Opjepass</strong>: En dä Datei künnt en <b>jefährlich Projrammstöck</b> dren stecke. Wa\'mer et laufe looße dät, do künnt dä ẞööver, udder Dinge Rääschner, met för de <i lang="en">Cracker</i> opjemaht wääde.',
'imagemaxsize'         => "Belder nit jrößer maache wie:<br /> ''(op dä Sigge, wo se beschrevve wääde)''",
'thumbsize'            => 'Esu breid solle de klein Beldche (Thumbnails/Breefmarke) sin:',
'widthheightpage'      => '$1×$2, {{PLURAL:$3|eij Sigg|$3 Sigge|keij Sigge}}',
'file-info'            => 'Dateiömfang: $1, MIME-Tüp: <code>$2</code>',
'file-info-size'       => '{{PLURAL:$1|Ei Pixel|$1 Pixelle}} breed × {{PLURAL:$2|Ei Pixel|$2 Pixelle}} huh, de Datei hät $3, dä MIME-Typ es: <code>$4</code>',
'file-nohires'         => '<small>Mer han kein hüütere Oplösung vun däm Beld.</small>',
'svg-long-desc'        => 'SVG-Datei, de Basis es {{PLURAL:$1|ei Pixel|$1 Pixelle}} breed × {{PLURAL:$2|ei Pixel|$2 Pixelle}} huh, dä Dateiömfang es $3',
'show-big-image'       => 'Jröößer Oplöösung',
'show-big-image-thumb' => '<small>Di Vör-Aansich es $1 × $2 Pixelle jroß</small>',
'file-info-gif-looped' => 'läuf emmer widder vun vürre',
'file-info-gif-frames' => '{{PLURAL:$1|ei einzel Beld|$1 einzel Belder|kei einzel Beld}}',
'file-info-png-looped' => 'läuf emmer widder vun vürre',
'file-info-png-repeat' => 'weed {{PLURAL:$1|eijmohl|$1 Mohl|keimohl}} affjespellt',
'file-info-png-frames' => '{{PLURAL:$1|ei einzel Beld|$1 einzel Belder|kei einzel Beld}}',

# Special:NewFiles
'newimages'             => 'Neu Dateie als Jaleri',
'imagelisttext'         => 'Heh küt en Liss vun <strong>$1</strong> Datei{{PLURAL:$1||e}}, zoteet $2.',
'newimages-summary'     => 'Heh die Sigg zeig die zoletz huhjeladene Belder un Dateie aan.',
'newimages-legend'      => 'Ußwähle',
'newimages-label'       => 'Dä Dattei ier Name udder e Stöck dofun:',
'showhidebots'          => '(Bots $1)',
'noimages'              => 'Kein Dateie jefunge.',
'ilsubmit'              => 'Sök',
'bydate'                => 'nohm Datum',
'sp-newimages-showfrom' => 'Zeich de neu Dateie av däm $1 öm $2 Uhr',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds-abbrev' => 'Sek.',
'minutes-abbrev' => 'Min.',
'hours-abbrev'   => 'Std.',

# Bad image list
'bad_image_list' => '<strong>Fomat:</strong>
Nur Reije met ennem * am Aanfang don jet.
Tirek noh däm * moß ene Link op en Datei sin, die mer nit han welle.
Donoh kumme, en däsellve Reih, Links op Sigge wo die Datei trotz dämm jenehm eß.',

# Metadata
'metadata'          => 'Metadaate',
'metadata-help'     => 'En dä Datei stich noh mieh an Daate dren. Dat sin Metadaate, die normal vum Opnahmejerät kumme. Wat en Kamera, ne Scanner, un esu, do fassjehallde han, dat kann ävver späder met enem Projramm bearbeidt un usjetuusch woode sin.',
'metadata-expand'   => 'Mieh zeije',
'metadata-collapse' => 'Daate Versteche',
'metadata-fields'   => 'Felder us de EXIF Metadate, di heh opjeföhrt sen, zeich et Wiki op Beldersigge aan, wan de Metadate kleinjeklick sin. Di andere weede esu lang verstoche. Dat Versteiche is och der Standat.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Breejd',
'exif-imagelength'                 => 'Läng',
'exif-bitspersample'               => 'Bits per Färvaandeil',
'exif-compression'                 => 'Kompräßjonßtüp',
'exif-photometricinterpretation'   => 'Zosammesetzung fun Pixelle',
'exif-orientation'                 => 'Ußrechtung fun de Kammera',
'exif-samplesperpixel'             => 'Aanzahl Färvaandeile',
'exif-planarconfiguration'         => 'De Ußreschtung udder Zusammestellung fun de Date',
'exif-ycbcrsubsampling'            => 'Ongerafftastongsroht fun Y bes C',
'exif-ycbcrpositioning'            => 'Y un C Posizjioneerung',
'exif-xresolution'                 => 'Oplösung fun Lenks noh Räähß',
'exif-yresolution'                 => 'Oplösung fun Bovve noh Onge',
'exif-resolutionunit'              => 'De Moßeinheit för de Oplösung en X- un Y-Reschtong',
'exif-stripoffsets'                => 'Der Aanfang fun de Date fun däm Beld en dä Dattei',
'exif-rowsperstrip'                => 'De Aanzahl Reije en jedem Striefe',
'exif-stripbytecounts'             => 'De Aanzahl Bytes en jedem kompremierte Striefe',
'exif-jpeginterchangeformat'       => 'Bytes Affshtand zom JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Bytes aan JPEG-Date',
'exif-transferfunction'            => 'Övverdrarongsfungxjohn',
'exif-whitepoint'                  => 'Fun Hand met Messung',
'exif-primarychromaticities'       => 'De drei Houpfärve ier Färf-Intensität',
'exif-ycbcrcoefficients'           => 'YCbCr-Geweeschte',
'exif-referenceblackwhite'         => 'Schwazz-Wiiß-Bezochs-Punk-Paare',
'exif-datetime'                    => 'Zickpunk fum Affshpeischere',
'exif-imagedescription'            => 'Dem Beld singe Tittel',
'exif-make'                        => 'Dä Kammera ier Heershtäller',
'exif-model'                       => 'Dat Kammerra-Modäll',
'exif-software'                    => 'De enjesatz ẞoffwär',
'exif-artist'                      => 'Fotojraf odder Maacher',
'exif-copyright'                   => 'Wä et Urhävverrääsch hät',
'exif-exifversion'                 => 'Exif-Version',
'exif-flashpixversion'             => 'De ongershtözte <i lang="en">Flashpix</i>-Version',
'exif-colorspace'                  => 'Färveroum',
'exif-componentsconfiguration'     => 'Bedüggening fun all de enkele Komponente',
'exif-compressedbitsperpixel'      => 'Aat fun de Kompreßjohn fun däm Beld',
'exif-pixelydimension'             => 'Pixelle jöltije Beld-Breed',
'exif-pixelxdimension'             => 'Pixelle jöltije Beld-Hühde',
'exif-makernote'                   => 'Aanmerkong fum Hersteller',
'exif-usercomment'                 => 'Aanmerkong fum Aanwender',
'exif-relatedsoundfile'            => 'De Tondatei, di do bei jehööt',
'exif-datetimeoriginal'            => 'Zickpunk fun de Opzeischnong fun de Date',
'exif-datetimedigitized'           => 'Zickpunk fun de Dijjitalisierong',
'exif-subsectime'                  => 'Honderstel Sekonde fun däm Zickpunk',
'exif-subsectimeoriginal'          => 'Honderstel Sekonde fun däm Zickpunk fun de Opzeichnung fun de Date',
'exif-subsectimedigitized'         => 'Honderstel Sekonde fun däm Zickpunk fun de Dijjitalisierong fun de Date',
'exif-exposuretime'                => 'Beleeshtungsduur',
'exif-exposuretime-format'         => '$1 Sekund{{PLURAL:$1||e|Sekund}} ($2)',
'exif-fnumber'                     => 'Blende',
'exif-exposureprogram'             => 'Beleeshtungsprojramm',
'exif-spectralsensitivity'         => 'Emfendleschkeit för et Färvespäktrom',
'exif-isospeedratings'             => 'Dem Fillem odder Sensor sing Emfindlischkeit (als ISO Wäät)',
'exif-oecf'                        => 'Dä Leesch-Elletronesche Ömrechnungsfaktor',
'exif-shutterspeedvalue'           => 'Jeschwendieschkeit fum Verschoß bem Beleeschte',
'exif-aperturevalue'               => 'De Blend iere Wäät',
'exif-brightnessvalue'             => 'De Hellishkeit',
'exif-exposurebiasvalue'           => 'Förjejovve Beleeschtung',
'exif-maxaperturevalue'            => 'De Jrözte Blend ier Öffnong',
'exif-subjectdistance'             => 'Affshtand nohm Motif',
'exif-meteringmode'                => 'De Metood ze Messe',
'exif-lightsource'                 => 'Leechquell',
'exif-flash'                       => 'Bletz',
'exif-focallength'                 => 'De Brennwigde fun de Lenß',
'exif-subjectarea'                 => 'Em Motiv singe Bereich',
'exif-flashenergy'                 => 'Dem Bletz sing Ennäjii',
'exif-spatialfrequencyresponse'    => 'De Kamera ier Winkel-Oplösung fun de Oots-Frequenz',
'exif-focalplanexresolution'       => 'De Kammera ierem Sensor sing räächs-links-Oplösung',
'exif-focalplaneyresolution'       => 'De Kammera ierem Sensor sing bovve-unge-Oplösung',
'exif-focalplaneresolutionunit'    => 'De Oplösung fum Sensor ier Moß-Einheit',
'exif-subjectlocation'             => 'Dä Plaz fun dämm Motif',
'exif-exposureindex'               => 'Beleeschtungs-Index',
'exif-sensingmethod'               => 'De Metood, woh der Kammera ier Sensor met messe deit',
'exif-filesource'                  => 'Dä Datei ier Quell',
'exif-scenetype'                   => 'Dä Tüp för de Darstellung udder der Szenopbou',
'exif-cfapattern'                  => 'CFA-Muster',
'exif-customrendered'              => 'Däm Maacher sing eije Aat, et Beld ze beärrbeide',
'exif-exposuremode'                => 'Beleeschtungs-Aat',
'exif-whitebalance'                => 'Wießaffjleich',
'exif-digitalzoomratio'            => 'Dijitalzoom',
'exif-focallengthin35mmfilm'       => 'De Brennwigde op 35 Millimeeter Kleinbeldfillem betrocke',
'exif-scenecapturetype'            => 'De Aat Opnahm',
'exif-gaincontrol'                 => 'Aanpassung fun de Hällischkeit',
'exif-contrast'                    => 'der Kontraß',
'exif-saturation'                  => 'de Färfsättijung',
'exif-sharpness'                   => 'De Beldschärf',
'exif-devicesettingdescription'    => 'Dem Jerät sing Enstellong',
'exif-subjectdistancerange'        => 'Em Motif singe Affshtandsbereisch',
'exif-imageuniqueid'               => 'Eindeutije Kännong för dat Beld',
'exif-gpsversionid'                => 'De Version fum GPS singe Stempel',
'exif-gpslatituderef'              => 'nöödlesch udder södlesch Breed fum GPS',
'exif-gpslatitude'                 => 'De Breed om Äädball fum GPS',
'exif-gpslongituderef'             => 'ösßlesch udder weßlesch Läng fum GPS',
'exif-gpslongitude'                => 'Läng om Äädball fum GPS',
'exif-gpsaltituderef'              => 'Wo drop de Hühde nohm GPS betrocke es',
'exif-gpsaltitude'                 => 'Hühde nohm GPS',
'exif-gpstimestamp'                => 'Zick fun de Atom-Uhr nohm GPS',
'exif-gpssatellites'               => 'Dem GPS sing Sattelitte för disse Meßvörjang',
'exif-gpsstatus'                   => 'Dä Shtattus fum GPS Emfangsjeräät',
'exif-gpsmeasuremode'              => 'Dat GPS-Meßverfahre',
'exif-gpsdop'                      => 'De Jenoueschkeit beim Meßße vum GPS',
'exif-gpsspeedref'                 => 'De Moß_Einheit fun de Jeschwendeshkeit',
'exif-gpsspeed'                    => 'Dem GPS-Emfangsejeräät sing Tempo',
'exif-gpstrackref'                 => 'Der Bezoch för de Bewääjong nohm GPS  ier Reeschtong',
'exif-gpstrack'                    => 'De Bewäjong nohm GPS ier Reeshtong',
'exif-gpsimgdirectionref'          => 'Der Bezoch för de Ußreschtong fum Beld nohm GPS',
'exif-gpsimgdirection'             => 'Ußreschtong fum Beld nohm GPS',
'exif-gpsmapdatum'                 => 'Jeodätisches Beobachtongs-Dattum nohm GPS jebruch',
'exif-gpsdestlatituderef'          => 'Bezoch för de Breed fum Ziel nohm GPS',
'exif-gpsdestlatitude'             => 'De Breed fum Ziel nohm GPS',
'exif-gpsdestlongituderef'         => 'Bezoch för de Läng fum Ziel nohm GPS',
'exif-gpsdestlongitude'            => 'De Läng fum Ziel nohm GPS',
'exif-gpsdestbearingref'           => 'Bezoch för de Reschtong fum Mottif nohm GPS',
'exif-gpsdestbearing'              => 'De Reschtong fum Mottif nohm GPS',
'exif-gpsdestdistanceref'          => 'Bezoch för de Entfernong fum Mottif nohm GPS',
'exif-gpsdestdistance'             => 'De Entfernong fum Mottif nohm GPS',
'exif-gpsprocessingmethod'         => 'Dä Name fum GPS-Verfahre',
'exif-gpsareainformation'          => 'Dä Name fum GPS-Jebeet',
'exif-gpsdatestamp'                => 'GPS-Dattum',
'exif-gpsdifferential'             => 'De Differenzjahl-Bereschtijong fum GPS',

# EXIF attributes
'exif-compression-1' => 'Oohne Kompressjuhn',
'exif-compression-6' => '<i lang="en">JPEG</i>',

'exif-photometricinterpretation-6' => '<i lang="en">YCbCr</i>',

'exif-unknowndate' => 'Dattum onbikannt',

'exif-orientation-1' => 'Nommaal',
'exif-orientation-2' => 'Op der Kopp jespeejelt',
'exif-orientation-3' => 'Op der Kopp jedrieht',
'exif-orientation-4' => 'Links-Räähß jespeejelt',
'exif-orientation-5' => 'En Veedelsdriejong mem Uhrzeijer un dann links-räähß jespeejelt',
'exif-orientation-6' => 'En Veedelsdriejong mem Uhrzeijer',
'exif-orientation-7' => 'En Veedelsdriejong jääje der Uhrzeijer un dann links-räähß jespeejelt',
'exif-orientation-8' => 'En Veedelsdriejong jääje der Uhrzeijer',

'exif-planarconfiguration-1' => 'Dat Fomaat es en Stöckscher',
'exif-planarconfiguration-2' => 'Dat Fomaat es flaach',

'exif-xyresolution-i' => '{{PLURAL:$1|eine Punk|$1 Punkte|keine Punk}} pro Zoll',
'exif-xyresolution-c' => '{{PLURAL:$1|eine Punk|$1 Punkte|keine Punk}} pro Zenntimeeter',

'exif-colorspace-1' => '<i lang="en">sRGB</i>',

'exif-componentsconfiguration-0' => 'Jidd_et nit',

'exif-exposureprogram-0' => 'Nit faßjelaat',
'exif-exposureprogram-1' => 'Vun Hand',
'exif-exposureprogram-2' => 'Et Standat Projramm',
'exif-exposureprogram-3' => 'De Automatik noh Zick fun de Öffnung',
'exif-exposureprogram-4' => 'De Automattik för der Blende-Verschloß',
'exif-exposureprogram-5' => 'E kreativ Projramm, ußjerescht op en hue Schärfedeefe',
'exif-exposureprogram-6' => 'E Akßions-Projramm, ußjerescht op en koote Zick för de Beleeschtung',
'exif-exposureprogram-7' => 'Us de Nöhde en huhkant opjenomme, mem Bleck op Fürre',
'exif-exposureprogram-8' => 'Landschaff em Querfommaat opjenomme, mem Bleck op der Hengerjrond',

'exif-subjectdistance-value' => '{{PLURAL:$1|eine|$1|keine}} Meter',

'exif-meteringmode-0'   => 'Onbikannt',
'exif-meteringmode-1'   => 'Meddelmääßesch',
'exif-meteringmode-2'   => 'Op de Medde fum Beld betrocke',
'exif-meteringmode-3'   => 'Punkmessung',
'exif-meteringmode-4'   => 'Miehpunkmessung',
'exif-meteringmode-5'   => 'Muster',
'exif-meteringmode-6'   => 'Deijl fum Beld',
'exif-meteringmode-255' => 'Ander',

'exif-lightsource-0'   => 'Onbikannt',
'exif-lightsource-1'   => 'Daresleech',
'exif-lightsource-2'   => 'Leusch fun sellver',
'exif-lightsource-3'   => 'Jlöh-Lampe-Leesch',
'exif-lightsource-4'   => 'Bletz',
'exif-lightsource-9'   => 'Joodt Wedder',
'exif-lightsource-10'  => 'Wedder met Wolke',
'exif-lightsource-11'  => 'Schadde',
'exif-lightsource-12'  => 'Daresleesch — selfs aam leuschte (D 5700–7100 K)',
'exif-lightsource-13'  => 'Daresleechs-Wiiß — selfs aam leuschte (N 4600–5400 K)',
'exif-lightsource-14'  => 'Kaal Wieß Leesch — selfs aam leuschte (W 3900–4500 K)',
'exif-lightsource-15'  => 'Wieß Leesch — selfs aam leuschte (WW 3200–3700 K)',
'exif-lightsource-17'  => 'Standat Leech Tüp A',
'exif-lightsource-18'  => 'Standat Leech Tüp B',
'exif-lightsource-19'  => 'Standat Leech Tüp C',
'exif-lightsource-23'  => 'D50',
'exif-lightsource-24'  => 'Studio-Kunsleesch noh ISO-Norrem',
'exif-lightsource-255' => 'Söns en Leechquell',

# Flash modes
'exif-flash-fired-0'    => 'Bletz hät nit jedonn',
'exif-flash-fired-1'    => 'met Bletz',
'exif-flash-return-0'   => 'Dä Bletz säät nit, wat loß es',
'exif-flash-return-2'   => 'Däm Bletz sing Leesch schingk nit zeröck jekumme ze sin',
'exif-flash-return-3'   => 'Däm Bletz sing Leesch es zeröck jekumme',
'exif-flash-mode-1'     => 'Dä Bletz moot ußjelöß wääde',
'exif-flash-mode-2'     => 'Dä Bletz wohr afjeschalldt',
'exif-flash-mode-3'     => 'Automattesch',
'exif-flash-function-1' => 'Kammera ohne Bletz',
'exif-flash-redeye-1'   => 'Ruude Aure fott jemaat',

'exif-focalplaneresolutionunit-2' => 'Zoll',

'exif-sensingmethod-1' => 'Onbikannt',
'exif-sensingmethod-2' => 'Ene Sensor fö Färve op einem Bousteijn',
'exif-sensingmethod-3' => 'Ene Sensor fö Färve op zweij Bousteijn',
'exif-sensingmethod-4' => 'Ene Sensor fö Färve op dreij Bousteijn',
'exif-sensingmethod-5' => 'Ene sequenzjelle Bereijschs-Sensor fö Färve',
'exif-sensingmethod-7' => 'Ene trilinejare sequenzjelle Sensor fö Färve',
'exif-sensingmethod-8' => 'Ene linejare sequenzjelle Sensor fö Färve',

'exif-filesource-3' => 'DSC',

'exif-scenetype-1' => 'Normal — e tirek fotmafeet Beld',

'exif-customrendered-0' => 'Standat — der jewöhnlijje Aflouf',
'exif-customrendered-1' => 'Eijen — dem Maacher singe Aflouf',

'exif-exposuremode-0' => 'Automattesch Beleeschdt',
'exif-exposuremode-1' => 'Fun Hand Beleeschtd',
'exif-exposuremode-2' => 'Beleeshtungsreih',

'exif-whitebalance-0' => 'Automattesche Wiiß-Affjleich',
'exif-whitebalance-1' => 'Wieß-Affjleisch fun Hand jemaat',

'exif-scenecapturetype-0' => 'Nomaal',
'exif-scenecapturetype-1' => 'Queerfommaat',
'exif-scenecapturetype-2' => 'Huhkant',
'exif-scenecapturetype-3' => 'Et Naakß',

'exif-gaincontrol-0' => 'Nix',
'exif-gaincontrol-1' => 'E beßje heller',
'exif-gaincontrol-2' => 'Vill heller',
'exif-gaincontrol-3' => 'E beßje dungkeler',
'exif-gaincontrol-4' => 'Vill dungkeler',

'exif-contrast-0' => 'Nomal',
'exif-contrast-1' => 'Weijsch',
'exif-contrast-2' => 'Hatt',

'exif-saturation-0' => 'Nomal',
'exif-saturation-1' => 'Winnisch Sättejung',
'exif-saturation-2' => 'En hue Sättejung',

'exif-sharpness-0' => 'Nomal',
'exif-sharpness-1' => 'Nit esu scharf',
'exif-sharpness-2' => 'Scharf',

'exif-subjectdistancerange-0' => 'Onbikannt',
'exif-subjectdistancerange-1' => 'Janz deesch draan (Makro-Opnahm)',
'exif-subjectdistancerange-2' => 'Vun Noh',
'exif-subjectdistancerange-3' => 'Vun Fähn',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Breed nöödlesch noh_m GPS',
'exif-gpslatitude-s' => 'Breed södlesch noh_m GPS',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Läng ößlesch noh_m GPS',
'exif-gpslongitude-w' => 'Läng weßlesch noh_m GPS',

'exif-gpsstatus-a' => 'De Messung fum GPS es aam Loufe',
'exif-gpsstatus-v' => 'Engeropperabilität fun Messunge noh_m GPS',

'exif-gpsmeasuremode-2' => 'Zweidimensjonal Mohß fum GPS',
'exif-gpsmeasuremode-3' => 'Dreidimensjonal Mohß fum GPS',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Killomeeter en de Shtondt noh_m GPS',
'exif-gpsspeed-m' => 'Miehle en de Shtondt noh_m GPS',
'exif-gpsspeed-n' => 'Knote noh_m GPS',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Wohre Rechtung noh_m GPS',
'exif-gpsdirection-m' => 'Mangneetesche Rechtung noh_m GPS',

# External editor support
'edit-externally'      => 'Dun de Datei met enem externe Projramm bei Dr om Rechner bearbeide',
'edit-externally-help' => '(Luur en de [http://www.mediawiki.org/wiki/Manual:External_editors Installationsaanweisunge] noh mieh Hinwies)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'all',
'imagelistall'     => 'all',
'watchlistall2'    => 'all',
'namespacesall'    => 'all',
'monthsall'        => 'all',
'limitall'         => 'alle',

# E-mail address confirmation
'confirmemail'              => 'E-Mail Adress bestätije',
'confirmemail_noemail'      => 'En [[Special:Preferences|Ding Enstellunge]] es kein öntlich E-Mail Adress.',
'confirmemail_text'         => 'Ih datte en däm Wiki heh de E-Mail bruche kanns, muss De Ding E-Mail Adress bestätich han, dat se en Oodnung es un dat se och Ding eijene es. Klick op dä Knopp un Do kriss en E-Mail jescheck. Do steiht ene Link met enem Code dren. Wann De met Dingem Brauser op dä Link jeihs, dann deis De domet bestätije, dat et wirklich Ding E-Mail Adress es. Dat es nit allzo secher, alsu wör nix för Die Bankkonto oder bei de Sparkass, ävver et sorg doför, dat nit jede Peijaß met Dinger E-Mail oder Dingem Metmaachername eröm maache kann.',
'confirmemail_pending'      => 'Do häs ald ene Kood för de Bestätijung med ene E-Mail zojeschek bekumme. Wann De Ding Aanmeldung eez jraad jemaat häs, dann donn noch ene Moment waade, ih dat De Der ene neue Kood hölls.',
'confirmemail_send'         => 'Scheck en E-Mail zem Bestätije',
'confirmemail_sent'         => 'En E-Mail, för Ding E-Mail Adress ze bestätije, es ungerwähs.',
'confirmemail_oncreate'     => 'Do häs jetz ene Kood för de Bestätijung med ene E-Mail zojeschek bekumme. För em Wiki jet ze maache, un för et Enlogge, do bruchs De der Kode nit, ävver domet de e-Mail övver et Wiki schecke un krijje kanns, doför moß De en ejmool ens bestätijje, domet secher es, dat Ding E-Mail Adress och rechtich jetipp wohr.',
'confirmemail_sendfailed'   => "Beim E-Mail Adress Bestätije es jet donevve jejange, künnt sin, en Dinger E-Mail Adress es e Zeiche verkihrt, oder esu jet.

Dä E-Mail-ẞööver hät jesaat: ''$1''",
'confirmemail_invalid'      => 'Et es jet donevve jejange, Ding E-Mail Adress es un bliev nit bestätich. Mööchlech, dä Code wohr verkihrt, hä künnt avjelaufe jewäse sin, oder esu jet. Versöök et noch ens.',
'confirmemail_needlogin'    => 'Do muss Dich $1, för de E-Mail Adress ze bestätije.',
'confirmemail_success'      => 'Ding E-Mail Adress es jetz bestätich.
Jetz künns De och noch enlogge. Vill Spass!',
'confirmemail_loggedin'     => 'Ding E-Mail Adress es jetz bestätich!',
'confirmemail_error'        => 'Beim E-Mail Adress Bestätije es jet donevve jejange, de Bestätijung kunnt nit avjespeichert wääde.',
'confirmemail_subject'      => 'Dun Ding <i lang="en">e-mail</i> Adress för {{GRAMMAR:Akkusativ|{{SITENAME}}}} bestäteje.',
'confirmemail_body'         => 'Künnt jod sin, Do wors et selver, vun de IP_Adress $1 hät sich
jedenfalls einer jemeldt, un well dä Metmaacher "$2" {{GRAMMAR:vun|{{SITENAME}}}}
sin, un hät en E-Mail Adress aanjejovve.

Öm jetz klor ze krije, dat die E-Mail Adress un dä neue Metmaacher och
zosamme jehüre, muss dä Neue en singem Brauser dä Link:

$3

opmaache. Noch för em $6 öm $7 Uhr. Alsu dun dat, wann de et selver bes.

Wann nit Do, sondern söns wä Ding E-Mail Adress aanjejovve hät, do bruchs de
jar nix ze don. De E-Mail Adress kann nit jebruch wääde, ih dat se nit
bestätich es. Do kanns ävver och op he dä Link jon:

$5

Domet deiß De tirek sare, dat De di Adress nit bestätije wells.',
'confirmemail_body_changed' => 'Künnt jod sin, Do wors et selver. Vun de IP_Adress $1 hät sich
jedenfalls einer jemeldt, un well dä Metmaacher "$2" op {{GRAMMAR:Akk bet|{{SITENAME}}}}
sin, un hät en neu Adress för sing e-mail aanjejovve.

Öm jetz klor ze krije, dat die neu Adress un dä Metmaacher och
zosamme jehüre, un öm de e-mail op {{GRAMMAR:Akk bet|{{SITENAME}}}}
widder aanzschallde, moss dä Metmaacher en singem Brauser dä Link:

$3

opmaache. Noch för em $6 öm $7 Uhr. Alsu dun dat, wann de et selver bes.

Wann nit Do, sondern söns wä Ding E-Mail Adress aanjejovve hät, bruchs
De jar nix ze don. Di Adress weed nit jebruch, wann se nit bestätich es.
Do kanns ävver och op heh dä Link jon:

$5

Domet deiß De tirek sare, dat De di Adress nit bestätije wells.',
'confirmemail_body_set'     => 'Künnt jod sin, Do wors et selver. Vun de IP_Adress $1 hät op
jede Fall einer för dä Metmaacher "$2" op {{GRAMMAR:Akk bet|{{SITENAME}}}}
heh di Adress för däm sing e-mail aanjejovve.

Öm jetz klor ze krije, dat die neu Adress un dä Metmaacher och
zosamme jehüre, un öm de e-mail op {{GRAMMAR:Akk bet|{{SITENAME}}}}
widder aanzschallde, moss dä Metmaacher en singem Brauser dä Link:

$3

opmaache. Noch för em $6 öm $7 Uhr. Alsu dun dat, wann de et selver bes.

Wann nit Do, sondern söns wä Ding E-Mail Adress aanjejovve hät, bruchs
De jar nix ze don. Di Adress weed nit jebruch, wann se nit bestätich es.
Do kanns ävver och op heh dä Link jon:

$5

Domet deiß De tirek sare, dat De di Adress nit bestätije wells.',
'confirmemail_invalidated'  => "Et Bestätijje för die E-Mail-Adress es afjebroche woode, un die Adress is '''nit''' bestätich.",
'invalidateemail'           => 'E-Mail-Adress nit bestätich',

# Scary transclusion
'scarytranscludedisabled' => '[Et Enbinge per Interwiki es avjeschalt]',
'scarytranscludefailed'   => '[De Schablon „$1“ enzebinge hät nit jeflupp]',
'scarytranscludetoolong'  => '[Schad, de URL es ze lang]',

# Trackbacks
'trackbackbox'      => 'Trackbacks för heh di Sigg:<br />
$1',
'trackbackremove'   => '([$1 Fottschmieße])',
'trackbacklink'     => 'Trackback',
'trackbackdeleteok' => 'Trackback es fottjeschmesse.',

# Delete conflict
'deletedwhileediting' => '<strong>Opjepass:</strong> De Sigg wood fottjeschmesse, nohdäm Do ald aanjefange häs, dran ze Ändere.
Em <span class="plainlinks">[{{fullurl:Special:Log|type=delete&page=}}{{FULLPAGENAMEE}} Logboch vum Sigge-Fottschmieße]</span> künnt der Jrund shtonn.
Wann De de Sigg avspeichere deis, weed se widder aanjelaat.',
'confirmrecreate'     => 'Dä Metmaacher [[User:$1|$1]] ([[User talk:$1|Klaaf]]) hät die Sigg fottjeschmesse, nohdäm Do do dran et Ändere aanjefange häs. Dä Jrund:
: „<i>$2</i>“
Wells Do jetz met en neu Version die Sigg widder neu aanläje?',
'recreate'            => 'Widder neu aanlääje',

'unit-pixel' => 'px',

# action=purge
'confirm_purge_button' => 'Jo — loss jonn!',
'confirm-purge-top'    => 'Dä Zweschespeicher för die Sigg fottschmieße?',
'confirm-purge-bottom' => 'Dä Zweschespeicher för de Sigg fottzeschmieße sorresch doför, dat af dann de neuste Version vun dä Sigg (de Version vun jetz) aanjezeich weet.',

# Separators for various lists, etc.
'semicolon-separator' => ';',
'autocomment-prefix'  => '-',
'word-separator'      => '&#32;',
'ellipsis'            => '&nbsp;…',
'parentheses'         => '($1)',

# Multipage image navigation
'imgmultipageprev' => '← de Sigg dovör',
'imgmultipagenext' => 'de Sigg donoh →',
'imgmultigo'       => 'Loss jonn!',
'imgmultigoto'     => 'Jang noh de Sigg „$1“',

# Table pager
'ascending_abbrev'         => 'opwääts zoteet',
'descending_abbrev'        => 'raffkaz zoteet',
'table_pager_next'         => 'De nächste Sigg',
'table_pager_prev'         => 'De vörijje Sigg',
'table_pager_first'        => 'De eetste Sigg',
'table_pager_last'         => 'De letzte Sigg',
'table_pager_limit'        => 'Zeich $1 pro Sigg',
'table_pager_limit_label'  => 'Stöcker pro Sigg:',
'table_pager_limit_submit' => 'Loss jonn!',
'table_pager_empty'        => 'Nix erus jekumme',

# Auto-summaries
'autosumm-blank'   => 'Dä janze Enhald vun dä Sigg fottjemaht',
'autosumm-replace' => "Dä jannze Enhallt fon dä Sigk ußjetuusch: '$1'",
'autoredircomment' => 'Leit öm op „[[$1]]“',
'autosumm-new'     => 'De Sigg wood neu aanjelaat met däm Aanfang: $1',

# Size units
'size-bytes'     => '$1 Bytes',
'size-kilobytes' => '$1 KB',
'size-megabytes' => '$1 MB',
'size-gigabytes' => '$1 GB',

# Live preview
'livepreview-loading' => 'Ben am Lade …',
'livepreview-ready'   => 'Fädesch jelaade.',
'livepreview-failed'  => 'De lebendije Vör-Ansich klapp jrad nit.
Don de nomaale Vör-Ansich nemme.',
'livepreview-error'   => 'Kein Verbendung müjjelisch: $1 „$2“.
Don de nomaale Vör-Ansich nemme.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Änderunge us de letzte {{PLURAL:$1|Sekund|$1 Sekunde|knappe Sekund}} sin en dä Leß wall noch nit opjenomme.',
'lag-warn-high'   => 'Dä Datebankßööver hät jrad vill ze donn.
Änderunge us de letzte {{PLURAL:$1|Sekund|$1 Sekunde|knappe Sekund}} sin dröm en dä Leß heh wall noch nit opjenomme.',

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
Wanns De fädig bes, dann deuß De dä Knopp „{{int:Watchlistedit-raw-submit}}“.
Natörlech kanns De di Liss och — met Dingem Brauser singe ''<span lang=\"en\">Copy&amp;Paste</span>''-Funkßjohn — komplett kopeere odder ußtuusche.
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

# Hebrew month names
'hebrew-calendar-m1'  => 'Tishrei',
'hebrew-calendar-m2'  => 'Cheshvan',
'hebrew-calendar-m3'  => 'Kislev',
'hebrew-calendar-m4'  => 'Tevet',
'hebrew-calendar-m5'  => 'Shevat',
'hebrew-calendar-m6'  => 'Adar',
'hebrew-calendar-m6a' => 'Adar I',
'hebrew-calendar-m6b' => 'Adar II',
'hebrew-calendar-m7'  => 'Nisan',
'hebrew-calendar-m8'  => 'Iyar',
'hebrew-calendar-m9'  => 'Sivan',
'hebrew-calendar-m10' => 'Tamuz',
'hebrew-calendar-m11' => 'Av',
'hebrew-calendar-m12' => 'Elul',

# Signatures
'timezone-utc' => 'UTC',

# Core parser functions
'unknown_extension_tag' => '„<code>$1</code>“ es en zosäzlejje Kennzeichnung, die kenne mer nit.',
'duplicate-defaultsort' => "'''Opjepaß:'''
Dä Shtanndat-Zoot-Schlößel „$1“ övverschriif dä älldere Zoot-Schlößel „$2“.",

# Special:Version
'version'                          => 'Version vun de Wiki Soffwär zeije',
'version-extensions'               => 'Installeete Erjänzunge un Zohsätz',
'version-specialpages'             => '{{int:nstab-special}}e',
'version-parserhooks'              => 'De Parser-Hooke',
'version-variables'                => 'Variable',
'version-antispam'                 => 'SPAM verhendere',
'version-skins'                    => 'Ovverflääsche',
'version-other'                    => 'Söns',
'version-mediahandlers'            => 'Medije-Handler',
'version-hooks'                    => 'Schnettstelle oder Hooke',
'version-extension-functions'      => 'Funktione för Zosätz',
'version-parser-extensiontags'     => 'Erjänzunge zom Parser',
'version-parser-function-hooks'    => 'Parserfunktione',
'version-skin-extension-functions' => 'Funktione för de Skins ze erjänze',
'version-hook-name'                => 'De Schnettstelle ier Name',
'version-hook-subscribedby'        => 'Opjeroofe vun',
'version-version'                  => '(Version $1)',
'version-license'                  => 'Lizenz',
'version-poweredby-credits'        => "Dat Wiki heh löp met '''[http://www.mediawiki.org/ MediaWiki]''', copyright © 2001–$1 $2.",
'version-poweredby-others'         => 'sönß wää',
'version-license-info'             => 'MediaWiki es e frei Projramm. Mer kann et unmolesteet wigger verdeile, un mer kann et verändere, wi mer löstich es, wam_mer sesch dobei aan de <i lang="en">GNU General Public License</i> (jenerälle öffentlesche Lizänz noh GNU) hallde deiht, wi se vun der <i lang="en">Free Software Foundation</i> (Steftung för frei Soffwäer) veröffentlesch woode es. Dobei kam_mer sesch ußsöhke of mer sesch aan de Version 2 dovun hallde deiht, udder öhnz en späädere Fassung.

MediaWiki weed verdeilt met dä Hoffnung, dat et för jet jood es, ävver <span style="text-transform:uppercase">der ohne jeede Jarantie</span>, un esujaa ohne ene unjesaate Jedangke, et künnt <span style="text-transform:uppercase">ze verkoufe</span> sin udder <span style="text-transform:uppercase;">för öhndsene bestemmpte Zweck ze jebruche</span>. Loor Der de jenannte Lizänz aan, wann De mieh Einzelheite weße wells.

Do sullts en [{{SERVER}}{{SCRIPTPATH}}/COPYING Kopie vun dä <i lang="en">GNU General Public License</i>] zosamme met däm Projramm krääje han, un wann nit, schrief aan de: <i lang="en">Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA </i> udder [http://www.gnu.org/licenses/old-licenses/gpl-2.0.html liß se em Internet noh].',
'version-software'                 => 'Installeete Soffwäer',
'version-software-product'         => 'Produk',
'version-software-version'         => 'Version',

# Special:FilePath
'filepath'         => 'Medije-Dateie med ier URL zëije',
'filepath-page'    => 'Dattëij_Name:',
'filepath-submit'  => 'Lohß jonn!',
'filepath-summary' => "Med dä {{int:nstab-special}} hee künnd'Er dä kompläte Paad vun de neuste Version vun ene Datei direk erusfenge. Die Datei weed jlich aanjezeig, odder med däm paßende Projramm op jemaat.

Doht der Name ohne „{{ns:file}}:“ doför ennjävve.",

# Special:FileDuplicateSearch
'fileduplicatesearch'           => 'Sök noh dubbelte Dateie',
'fileduplicatesearch-summary'   => 'Söhhk noh dubbelte Dateie övver dänne iere Häsh-Zahl.',
'fileduplicatesearch-legend'    => 'Sök noh ene dubbelte Datei',
'fileduplicatesearch-filename'  => 'Dateiname:',
'fileduplicatesearch-submit'    => 'Sööke',
'fileduplicatesearch-info'      => '{{PLURAL:$1|Ei Pixel|$1 Pixelle|Nit}} breed × {{PLURAL:$2|Ei Pixel|$2 Pixelle|nix}} huh<br />Dateiömfang: $3<br />MIME-Tüp: <code>$4</code>',
'fileduplicatesearch-result-1'  => 'Mer han kein akoraat dubbelte Dateie för „$1“ jefonge.',
'fileduplicatesearch-result-n'  => "Vun dä Datei „$1“ ham'mer '''{{PLURAL:$2|ein|$2|kein}}''' dubbelte mem selve Enhalt jefonge.",
'fileduplicatesearch-noresults' => 'Mer han kein Dattei met däm Name „$1“ jefonge.',

# Special:SpecialPages
'specialpages'                   => '{{int:nstab-special}}e',
'specialpages-note'              => "<h4 class='mw-specialpagesgroup'>Lejänd (Äklierong):</h4><table style='width:100%;' class='mw-specialpages-table'><tr><td valign='top'><ul><li> {{int:nstab-special}}e för jede Metmaacher
</li><li class='mw-specialpagerestricted'>{{int:nstab-special}}e för Metmaacher met besönder Räächte
</li></ul></td></tr></table>",
'specialpages-group-maintenance' => 'Waadungsleste',
'specialpages-group-other'       => 'Ander {{int:nstab-special}}e',
'specialpages-group-login'       => 'Aamelde',
'specialpages-group-changes'     => 'Letzte Änderunge un Logböcher',
'specialpages-group-media'       => 'Dateie — Huhlaade un Opliste',
'specialpages-group-users'       => 'Metmaacher un denne ier Rääschte',
'specialpages-group-highuse'     => 'Öff jebruch…',
'specialpages-group-pages'       => 'Siggeliste',
'specialpages-group-pagetools'   => 'Werrekzüch för Sigge',
'specialpages-group-wiki'        => 'Werrekzüch un Date vum Systeem',
'specialpages-group-redirects'   => '{{int:nstab-special}}e, die ömleite, söke, un finge',
'specialpages-group-spam'        => 'Werrekzüch jäje SPÄM',

# Special:BlankPage
'blankpage'              => 'Vakat-Sigg',
'intentionallyblankpage' => 'Op dä Sigg es med Afseesh nix drop.',

# External image whitelist
'external_image_whitelist' => '# Donn aan dä Reih heh nix ändere<pre>
# Onge künne Brochstöck fun regular expressions aanjejovve wäde,
# alsu dä Deil zwesche / und /
# Noh em Verjliische met däm URL vun ene Datei fun ußerhallef:
# Treffer: De Datei weed jezeich odder enjebonge.
# Söns: ene Link weed aanjezeich.
# Wam_mer et nit ömschtällt, es Jruß- un Kleinschrevv_ejaal.
# Reije met # am Aanfang, sen bloß Kommenta
# Donn de Brochstöck heh noh endrare, un di Reihe bes hee nit ändere</pre>',

# Special:Tags
'tags'                    => 'De jöltijje Makeerunge för Änderunge',
'tag-filter'              => '[[Special:Tags|Makeerunge]] ußsöke:',
'tag-filter-submit'       => 'Beschränke!',
'tags-title'              => 'Makeerunge',
'tags-intro'              => 'Heh sin alle de Makeerunge opjeliß, die et Wiki för Änderunge verjevve kann, un wat se bedügge don.',
'tags-tag'                => 'Dä Makeerung iere Name',
'tags-display-header'     => 'Kennzeiche en de Leßte met Änderunge',
'tags-description-header' => 'Bedüggtening',
'tags-hitcount-header'    => 'Makeete Änderunge',
'tags-edit'               => 'ändere',
'tags-hitcount'           => '{{PLURAL:$1|Ein Änderung|$1 Änderunge|kein Änderunge}}',

# Special:ComparePages
'comparepages'     => 'Sigge verjliesche',
'compare-selector' => 'Versione vun Sigge verjlieshe',
'compare-page1'    => 'De ein Sigg',
'compare-page2'    => 'De ander Sigg',
'compare-rev1'     => 'de ein Version',
'compare-rev2'     => 'de ander Version',
'compare-submit'   => 'Verjlieshe!',

# Database error messages
'dberr-header'      => 'Dat Wiki heh häd en Schwierischkeit',
'dberr-problems'    => 'Deit uns leid, die Sigg heh häd för der Momang e teschnisch Problem.',
'dberr-again'       => 'Versök eijfach en e paa Menutte, norr_ens die Sigg afzeroofe.',
'dberr-info'        => '(Mer han kei Verbindung noh_m Datebank-ẞööver krijje künne: $1)',
'dberr-usegoogle'   => 'De künnß zweschedorsch ad met <i lang="en">Google</i> söke.',
'dberr-outofdate'   => 'Müjjelesch, dat dat Verzeichnes vun uns Sigge do nit janß om neuste Shtannd es.',
'dberr-cachederror' => 'Wat heh noh kütt es en Kopi vum Zwescheshpeisher vun dä Sigg,
die De häs han welle. Se künnt jet ällder un nit mieh aktoäll sin.',

# HTML forms
'htmlform-invalid-input'       => 'Mer han e Problem met jet wat De enjejovve häß',
'htmlform-select-badoption'    => 'Dinge aanjejovve Wäät es kein müjjelesche Ußwahl.',
'htmlform-int-invalid'         => 'Dinge aanjejovve Wäät eß kein janze Zahl.',
'htmlform-float-invalid'       => 'Wat De doh aanjejovve häs, dat es kein gewöhnlijje udder Komma-Zahl.',
'htmlform-int-toolow'          => 'Dinge aanjejovve Wäät litt onger dämm winnischßde, wat müjjelesch es, un dat es $1.',
'htmlform-int-toohigh'         => 'Dinge aanjejovve Wäät litt övver dämm hühßte, wat jeiht, un dat es $1.',
'htmlform-required'            => 'Heh dä Wäät es nüüdesch',
'htmlform-submit'              => 'Loß Jonn!',
'htmlform-reset'               => 'Änderunge retuur nämme',
'htmlform-selectorother-other' => 'Annder',

# SQLite database support
'sqlite-has-fts' => 'Version $1 (un kann en janze Täxte söhke)',
'sqlite-no-fts'  => 'Version $1 (kann ävver nit en janze Täxte söhke)',

# Special:DisableAccount
'disableaccount'             => 'Enem Metmaacher singe Zohjang stell lääje',
'disableaccount-user'        => 'Metmaacher Name:',
'disableaccount-reason'      => 'Woröm?',
'disableaccount-confirm'     => "Däm Metmaacher singe Zohjang op Duur stell lääje.
Dä Metmaacher kann dann nit mieh enlogge, sing Paßwoot ändere, udder <i lang=\"en\">e-mail</i> krijje.
Wann dä Metmaacher ööhnzwoh enjelogg es, flüsh hä tirag_eruß.
'''Opjepaß:''' ''Ene stell jelaate Zohjang kam_mer der ohne Hölp vun enem Administrator vum ẞööver vum Wiki nit widder aan et Loufe krijje.''",
'disableaccount-mustconfirm' => 'Do moß bestäätije, dat De däm Metmaacher singe Zohjang op Duur stell lääje wells.',
'disableaccount-nosuchuser'  => 'Ene Metmaacher „$1“ ham_mer nit.',
'disableaccount-success'     => 'Däm Metmaacher „$1“ singe Zohjang es op Duur stell jelaat.',
'disableaccount-logentry'    => 'hät däm Metmaacher [[$1]] singe Zohjang op Duur stell jelaat.',

);
