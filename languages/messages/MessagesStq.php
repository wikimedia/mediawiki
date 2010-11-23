<?php
/** Seeltersk (Seeltersk)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Maartenvdbent
 * @author Mucalexx
 * @author Purodha
 * @author Pyt
 * @author Urhixidur
 */

$fallback = 'de';

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Spezial',
	NS_TALK             => 'Diskussion',
	NS_USER             => 'Benutser',
	NS_USER_TALK        => 'Benutser_Diskussion',
	NS_PROJECT_TALK     => '$1_Diskussion',
	NS_FILE             => 'Bielde',
	NS_FILE_TALK        => 'Bielde_Diskussion',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_Diskussion',
	NS_TEMPLATE         => 'Foarloage',
	NS_TEMPLATE_TALK    => 'Foarloage_Diskussion',
	NS_HELP             => 'Hälpe',
	NS_HELP_TALK        => 'Hälpe_Diskussion',
	NS_CATEGORY         => 'Kategorie',
	NS_CATEGORY_TALK    => 'Kategorie_Diskussion',
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Ferwiese unnerstriekje:',
'tog-highlightbroken'         => 'Ätterdruk lääse ap Ferwiese ätter loose Themen',
'tog-justify'                 => 'Text as Bloksats',
'tog-hideminor'               => 'Litje Annerengen uutbländje',
'tog-hidepatrolled'           => 'Kontrollierde Annerengen in do "Lääste Annerengen" uutbländje',
'tog-newpageshidepatrolled'   => 'Kontrollierde Sieden ap ju Lieste „Näie Sieden“ ferbierge',
'tog-extendwatchlist'         => 'Uutgediende Beooboachtengslieste tou Anwiesenge fon aal Annerengen',
'tog-usenewrc'                => 'Fermeerde Deerstaalenge fon do "Lääste Annerengen" (bruukt Javascript)',
'tog-numberheadings'          => 'Uurschrifte automatisk nuumerierje',
'tog-showtoolbar'             => 'Beoarbaidengs-Reewen anwiese',
'tog-editondblclick'          => 'Sieden mäd Dubbeldklik beoarbaidje (JavaScript)',
'tog-editsection'             => 'Links toun Beoarbaidjen fon eenpelde Ousatse anwiese',
'tog-editsectiononrightclick' => 'Eenpelde Ousatse mäd Gjuchtsklik beoarbaidje (JavaScript)',
'tog-showtoc'                 => 'Anwiesen fon n Inhooldsferteeknis bie Artikkele mäd moor as 3 Uurschrifte',
'tog-rememberpassword'        => 'Ap dissen Komputer duurhaft anmälded blieuwe (Maksimoal foar $1 {{PLURAL:$1|Dai|Deege}})',
'tog-watchcreations'          => 'Aal do sälwen näi anlaide Sieden beooboachtje',
'tog-watchdefault'            => 'Aal do sälwen annerde Sieden beooboachtje',
'tog-watchmoves'              => 'Aal do sälwen ferschäuwede Sieden beooboachtje',
'tog-watchdeletion'           => 'Aal do sälwen läskede Sieden beooboachtje',
'tog-minordefault'            => 'Alle Annerengen as littek markierje',
'tog-previewontop'            => 'Foarschau buppe dät Beoarbaidengsfinster anwiese',
'tog-previewonfirst'          => 'Bie dät eerste Beoarbaidjen altied ju Foarschau anwiese',
'tog-nocache'                 => 'Siedencache fon dän Browser deaktivierje',
'tog-enotifwatchlistpages'    => 'Bie Annerengen an bekiekede Sieden E-Mails seende.',
'tog-enotifusertalkpages'     => 'Bie Annerengen an mien Benutser-Diskussionssiede E-Mails seende.',
'tog-enotifminoredits'        => 'Uk bie litje Annerengen an do Sieden E-Mails seende.',
'tog-enotifrevealaddr'        => 'Dien E-Mail-Adrässe wäd in Bescheed-Mails wiesed.',
'tog-shownumberswatching'     => 'Antaal fon do beooboachtjende Benutsere anwiese',
'tog-oldsig'                  => 'Foarschau fon ju aktuälle Signatuur:',
'tog-fancysig'                => 'Unnerschrift as Wikitext behonnelje (sunner automatiske Ferlinkenge)',
'tog-externaleditor'          => 'Externe Editor as Standoard benutsje (bloot foar Experte, der mouten spezielle Ienstaalengen ap dän oaine Computer moaked wäide)',
'tog-externaldiff'            => 'Extern Diff-Program as Standoard benutsje (bloot foar Experte, der mouten spezielle Ienstaalengen ap dän oaine Computer moaked wäide)',
'tog-showjumplinks'           => '"Wikselje tou"-Links muugelk moakje',
'tog-uselivepreview'          => 'Live-Foarschau nutsje (JavaScript) (experimentell)',
'tog-forceeditsummary'        => 'Woarschauje, wan bie dät Spiekerjen ju Touhoopefoatenge failt',
'tog-watchlisthideown'        => 'Oaine Biedraage in ju Beooboachtengslieste ferbierge',
'tog-watchlisthidebots'       => 'Bot-Biedraage in ju Beooboachtengslieste ferbierge',
'tog-watchlisthideminor'      => 'Litje Biedraage in ju Beooboachtengslieste ferbierge',
'tog-watchlisthideliu'        => 'Beoarbaidengen truch ounmäldede Benutsere in ju Beoboachtengslieste uutbländje',
'tog-watchlisthideanons'      => 'Beoarbaidengen truch anonyme Benutsere (IP) in ju Beoboachtengslieste uutbländje',
'tog-watchlisthidepatrolled'  => 'Kontrollierde Annerengen in ju Beooboachtengslieste "Lääste Annerengen" uutbländje',
'tog-nolangconversion'        => 'Konvertierenge fon Sproakvarianten deaktivierje',
'tog-ccmeonemails'            => 'Seend mie Kopien fon do E-Maile, do iek uur Benutsere seende.',
'tog-diffonly'                => 'Wies bie dän Versionsfergliek bloot do Unnerscheede, nit ju fulboodige Siede',
'tog-showhiddencats'          => 'Wies ferstatte Kategorien',
'tog-norollbackdiff'          => 'Unnerscheed ätter dät Touräächsätten unnerdrukke',

'underline-always'  => 'Altied',
'underline-never'   => 'sieläärge nit',
'underline-default' => 'honget ou fon Browser-Ienstaalenge',

# Font style option in Special:Preferences
'editfont-style'     => 'Schriftfamilie foar dän Text in dät Beoarbaidengsfinster:',
'editfont-default'   => 'Browserstandoard',
'editfont-monospace' => 'Schrift mäd fääste Teekenbratte',
'editfont-sansserif' => 'Serifenloose Groteskschrift',
'editfont-serif'     => 'Schrift mäd Serife',

# Dates
'sunday'        => 'Sundai',
'monday'        => 'Moundai',
'tuesday'       => 'Täisdai',
'wednesday'     => 'Midwiek',
'thursday'      => 'Tuunsdai',
'friday'        => 'Fräindai',
'saturday'      => 'Snäiwende',
'sun'           => 'Sun',
'mon'           => 'Mou',
'tue'           => 'Täi',
'wed'           => 'Mid',
'thu'           => 'Tuu',
'fri'           => 'Frä',
'sat'           => 'Snä',
'january'       => 'Januoar',
'february'      => 'Februoar',
'march'         => 'Meerte',
'april'         => 'April',
'may_long'      => 'Moai',
'june'          => 'Juni',
'july'          => 'Juli',
'august'        => 'August',
'september'     => 'September',
'october'       => 'Oktober',
'november'      => 'November',
'december'      => 'Dezember',
'january-gen'   => 'Januoar',
'february-gen'  => 'Februoar',
'march-gen'     => 'Meerte',
'april-gen'     => 'April',
'may-gen'       => 'Moai',
'june-gen'      => 'Juni',
'july-gen'      => 'Juli',
'august-gen'    => 'August',
'september-gen' => 'September',
'october-gen'   => 'Oktober',
'november-gen'  => 'November',
'december-gen'  => 'Dezember',
'jan'           => 'Jan',
'feb'           => 'Feb',
'mar'           => 'Mee',
'apr'           => 'Apr',
'may'           => 'Moa',
'jun'           => 'Jun',
'jul'           => 'Jul',
'aug'           => 'Aug',
'sep'           => 'Sep',
'oct'           => 'Okt',
'nov'           => 'Nov',
'dec'           => 'Dez',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategorie|Kategorien}}',
'category_header'                => 'Artikkel in ju Kategorie "$1"',
'subcategories'                  => 'Unnerkategorien',
'category-media-header'          => 'Media in Kategorie "$1"',
'category-empty'                 => "''Disse Kategorie is loos.''",
'hidden-categories'              => '{{PLURAL:$1|Ferstatte Kategorie|Ferstatte Kategorien}}',
'hidden-category-category'       => 'Ferstatte Kategorien',
'category-subcat-count'          => '{{PLURAL:$2|Disse Kategorie änthaalt foulgjende Unnerkategorie:|{{PLURAL:$1|Foulgjende Unnerkategorie is een fon mädnunner $2 Unnerkategorien in disse Kategorie:|Der wäide $1 fon mädnunner $2 Unnerkategorien in disse Kategorie anwiesd:}}}}',
'category-subcat-count-limited'  => 'Disse Kategorie änthaalt foulgjende {{PLURAL:$1|Unnerkategorie|$1 Unnerkategorien}}:',
'category-article-count'         => '{{PLURAL:$2|Disse Kategorie änthaalt foulgjende Siede:|{{PLURAL:$1|Foulgjende Siede is een fon mädnunner $2 Sieden in disse Kategorie:|Der wäide $1 fon mädnunner $2 Sieden in disse Kategorie anwiesd:}}}}',
'category-article-count-limited' => 'Foulgjende {{PLURAL:$1|Siede is|$1 Sieden sunt}} in disse Kategorie äntheelden:',
'category-file-count'            => '{{PLURAL:$2|Disse Kategorie änthaalt foulgjende Doatai:|{{PLURAL:$1|Foulgjende Doatäi is een fon madnunner $2 Doatäie in disse Kategorie:|Der wäide $1 fon mädnunner $2 Doatäie in disse Kategorie anwiesd:}}}}',
'category-file-count-limited'    => 'Foulgjende {{PLURAL:$1|Doatäi is|$1 Doatäie sunt}} in disse Kategorie äntheelden:',
'listingcontinuesabbrev'         => '(Foutsättenge)',
'index-category'                 => 'Indizierde Sieden',
'noindex-category'               => 'Nit indizierde Sieden',

'mainpagetext'      => "'''Ju MediaWiki Software wuude mäd Ärfoulch installierd.'''",
'mainpagedocfooter' => 'Sjuch ju [http://meta.wikimedia.org/wiki/MediaWiki_localization Dokumentation tou de Anpaasenge fon dän Benutseruurfläche] un dät [http://meta.wikimedia.org/wiki/Help:Contents Benutserhondbouk] foar Hälpe tou ju Benutsenge un Konfiguration.',

'about'         => 'Uur',
'article'       => 'Inhoold Siede',
'newwindow'     => '(eepent in näi Finster)',
'cancel'        => 'Oubreeke',
'moredotdotdot' => 'Moor …',
'mypage'        => 'Oaine Siede',
'mytalk'        => 'Oaine Diskussion',
'anontalk'      => 'Diskussionssiede foar dissen IP',
'navigation'    => 'Navigation',
'and'           => '&#32;un',

# Cologne Blue skin
'qbfind'         => 'Fiende',
'qbbrowse'       => 'Bleederje',
'qbedit'         => 'Annerje',
'qbpageoptions'  => 'Disse Siede',
'qbpageinfo'     => 'Siedendoatäie',
'qbmyoptions'    => 'Mien Sieden',
'qbspecialpages' => 'Spezialsieden',
'faq'            => 'Oafte stoalde Froagen',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-addsection'       => 'Ousnit bietouföigje',
'vector-action-delete'           => 'Läskje',
'vector-action-move'             => 'Ferschuuwe',
'vector-action-protect'          => 'Schutsje',
'vector-action-undelete'         => 'Wierhäärstaale',
'vector-action-unprotect'        => 'Fräireeke',
'vector-simplesearch-preference' => 'Uutwiedede Säikfoarsleeke aktivierje (bloot Vector)',
'vector-view-create'             => 'Moakje',
'vector-view-edit'               => 'Beoarbaidje',
'vector-view-history'            => 'Versionsgeschichte',
'vector-view-view'               => 'Leese',
'vector-view-viewsource'         => 'Wältext bekiekje',
'actions'                        => 'Aktione',
'namespaces'                     => 'Noomeruume',
'variants'                       => 'Variante',

'errorpagetitle'    => 'Failer',
'returnto'          => 'Tourääch tou Siede $1.',
'tagline'           => 'Uut {{SITENAME}}',
'help'              => 'Hälpe',
'search'            => 'Säike (0)',
'searchbutton'      => 'Säike (07)',
'go'                => 'Uutfiere',
'searcharticle'     => 'Siede',
'history'           => 'Versione',
'history_short'     => 'Geschichte',
'updatedmarker'     => '(annerd)',
'info_short'        => 'Information',
'printableversion'  => 'Drukversion',
'permalink'         => 'Permanentlink',
'print'             => 'drukke',
'edit'              => 'Siede beoarbaidje',
'create'            => 'Moakje',
'editthispage'      => 'Siede beoarbaidje',
'create-this-page'  => 'Siede moakje',
'delete'            => 'Läskje',
'deletethispage'    => 'Disse Siede läskje',
'undelete_short'    => '{{PLURAL:$1|1 Version|$1 Versione}} wier häärstaale',
'protect'           => 'schutsje',
'protect_change'    => 'annerje',
'protectthispage'   => 'Siede schutsje',
'unprotect'         => 'Fräiroat',
'unprotectthispage' => 'Schuts aphieuwje',
'newpage'           => 'Näie Siede',
'talkpage'          => 'Diskussion',
'talkpagelinktext'  => 'Diskussion',
'specialpage'       => 'Spezioalsiede',
'personaltools'     => 'Persöönelke Reewen',
'postcomment'       => 'Näi Stuk',
'articlepage'       => 'Siede',
'talk'              => 'Diskussion',
'views'             => 'Anwiesengen',
'toolbox'           => 'Reewen',
'userpage'          => 'Benutsersiede',
'projectpage'       => 'Meta-Text',
'imagepage'         => 'Doatäisiede',
'mediawikipage'     => 'Inhooldssiede anwiese',
'templatepage'      => 'Foarloagensiede anwiese',
'viewhelppage'      => 'Hälpesiede anwiese',
'categorypage'      => 'Kategoriesiede anwiese',
'viewtalkpage'      => 'Diskussion',
'otherlanguages'    => 'Uur Sproaken',
'redirectedfrom'    => '(Fäärelaited fon $1)',
'redirectpagesub'   => 'Fäärelaitenge',
'lastmodifiedat'    => 'Disse Siede wuude toulääst annerd uum $2, $1.',
'viewcount'         => 'Disse Siede wuude bit nu {{PLURAL:$1|eenmoal|$1 moal}} ouruupen.',
'protectedpage'     => 'Schutsede Siede',
'jumpto'            => 'Wikselje tou:',
'jumptonavigation'  => 'Navigation',
'jumptosearch'      => 'Säike (08)',
'view-pool-error'   => 'Äntscheeldigenge, do Servere sunt apstuuns uutlasted.
Tou fuul Benutsere fersäike, disse Siede tou besäiken.
Täif n poor Minuten, eer du et noch n Moal fersäkst.

$1',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Uur {{SITENAME}}',
'aboutpage'            => 'Project:Uur_{{SITENAME}}',
'copyright'            => 'Inhoold is ferföichboar unner de $1.',
'copyrightpage'        => '{{ns:project}}:Uurheebergjuchte',
'currentevents'        => 'Aktuälle Geböärnisse',
'currentevents-url'    => 'Project:Aktuälle Geböärnisse',
'disclaimers'          => 'Begriepskläärenge',
'disclaimerpage'       => 'Project:Siede tou Begriepskläärenge',
'edithelp'             => 'Beoarbaidengshälpe',
'edithelppage'         => 'Help:Beoarbaidengshälpe',
'helppage'             => 'Help:Hälpe',
'mainpage'             => 'Haudsiede',
'mainpage-description' => 'Haudsiede',
'policy-url'           => 'Project:Laitlienjen',
'portal'               => '{{SITENAME}}-Portoal',
'portal-url'           => 'Project:Portoal',
'privacy'              => 'Doatenschuts',
'privacypage'          => 'Project:Doatenschuts',

'badaccess'        => 'Neen uträkkende Gjuchte',
'badaccess-group0' => 'Du hääst nit ju ärfoarderelke Begjuchtigenge foar disse Aktion.',
'badaccess-groups' => 'Disse Aktion is bloot muugelk foar Benutsere, do der {{PLURAL:$2|ju Gruppe|een fon do Gruppen}} „$1“ anheere.',

'versionrequired'     => 'Version $1 fon MediaWiki is nöödich',
'versionrequiredtext' => 'Version $1 fon MediaWiki is nöödich uum disse Siede tou nutsjen. Sjuch ju [[Special:Version|Versionssiede]]',

'ok'                      => 'Säike (09)',
'retrievedfrom'           => 'Fon "$1"',
'youhavenewmessages'      => 'Du hääst $1 ($2).',
'newmessageslink'         => 'näie Ättergjuchte',
'newmessagesdifflink'     => 'Unnerscheed tou ju foarlääste Version',
'youhavenewmessagesmulti' => 'Du hääst näie Ättergjuchte: $1',
'editsection'             => 'Beoarbaidje',
'editold'                 => 'Beoarbaidje',
'viewsourceold'           => 'Wältext wiese',
'editlink'                => 'beoarbaidje',
'viewsourcelink'          => 'Wältext bekiekje',
'editsectionhint'         => 'Apsats beoarbaidje: $1',
'toc'                     => 'Inhooldsferteeknis',
'showtoc'                 => 'Anwiese',
'hidetoc'                 => 'ferbierge',
'thisisdeleted'           => '$1 ankiekje of wier häärstaale?',
'viewdeleted'             => '$1 anwiese?',
'restorelink'             => '{{PLURAL:$1|1 läskede Beoarbaidengsfoargang|$1 läskede Beoarbaidengsfoargange}}',
'feedlinks'               => 'Feed:',
'feed-invalid'            => 'Ungultigen Abonnement-Typ.',
'feed-unavailable'        => 'Der stounde neen Feeds tou Ferföigenge.',
'site-rss-feed'           => '$1 RSS-Feed',
'site-atom-feed'          => '$1 Atom-Feed',
'page-rss-feed'           => '"$1" RSS-Feed',
'page-atom-feed'          => '"$1" Atom-Feed',
'red-link-title'          => '$1 (Siede nit deer)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Siede',
'nstab-user'      => 'Benutsersiede',
'nstab-media'     => 'Media',
'nstab-special'   => 'Spezioalsiede',
'nstab-project'   => 'Projektsiede',
'nstab-image'     => 'Bielde',
'nstab-mediawiki' => 'Ättergjucht',
'nstab-template'  => 'Foarloage',
'nstab-help'      => 'Hälpe',
'nstab-category'  => 'Kategorie',

# Main script and global functions
'nosuchaction'      => 'Disse Aktion rakt et nit',
'nosuchactiontext'  => 'Ju in de URL anroate Aktion wäd fon MediaWiki nit unnerstöänd.
Der kon n Schrieuwfailer in de URL foarlääse of der wuud n wrakken Link anklikt.
Dät kon sik uk uum n Bug ap {{SITENAME}} honnelje.',
'nosuchspecialpage' => 'Disse Spezialsiede rakt et nit',
'nospecialpagetext' => '<strong>Ju apruupene Spezioalsiede is nit deer.</strong>

Aal ferföichboare Spezioalsieden sunt in ju [[Special:SpecialPages|Lieste fon do Spezioalsieden]] tou fienden.',

# General errors
'error'                => 'Failer',
'databaseerror'        => 'Failer in ju Doatenboank',
'dberrortext'          => 'Der is n Doatenboankfailer aptreeden.
Die Gruund kon n Programmierfailer weese.
Ju lääste Doatenboankoufroage lutte:
<blockquote><tt>$1</tt></blockquote>
uut de Funktion "<tt>$2</tt>".
Die Doatenboank mäldede dän Failer "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Dät roate n Syntaxfailer in ju Doatenboankoufroage.
Ju lääste Doatenboankoufroage lutte: „$1“ uut ju Funktion „<tt>$2</tt>“.
Die Doatenboank mäldede dän Failer: „<tt>$3: $4</tt>“.',
'laggedslavemode'      => 'Woarschauenge: Ju anwiesde Siede kon unner Umstande do jungste Beoarbaidengen noch nit be-ienhoolde.',
'readonly'             => 'Doatenboank is speerd',
'enterlockreason'      => 'Reeke jädden n Gruund ien, wieruum ju Doatenboank speerd wäide schuul un ne Ouschätsenge uur ju Duur fon ju Speerenge',
'readonlytext'         => 'Ju Doatenboank is apstuuns foar Annerengen un näie Iendraage speerd.

As Gruund wuude anroat: $1',
'missing-article'      => 'Die Text foar „$1“ $2 wuude nit in ju Doatenboank fuunen.

Ju Siede is muugelkerwiese läsked of ferschäuwen wuuden.

Fals dit nit die Fal is, hääst du eventuäl n Failer in ju Software fuunen.
Mäld dit n [[Special:ListUsers/sysop|Administrator]] unner Naamenge fon ju URL.',
'missingarticle-rev'   => '(Versionsnuumer: $1)',
'missingarticle-diff'  => '(Unnerscheed twiske Versione: $1, $2)',
'readonly_lag'         => 'Ju Doatenboank wuud automatisk foar Schrieuwtougriepe speerd, deermäd sik do ferdeelde Doatenboankservere (slaves) mäd dän Hauddoatenboankserver (master) ouglieke konnen.',
'internalerror'        => 'Interne Failer',
'internalerror_info'   => 'Interne Failer: $1',
'fileappenderrorread'  => '„$1“ kuud unner dät Touhoopeföigjen nit leesen wäide.',
'fileappenderror'      => 'Kuud „$1“ nit an „$2“ anhongje.',
'filecopyerror'        => 'Kuude Doatäi "$1" nit ätter "$2" kopierje.',
'filerenameerror'      => 'Kuude Doatäi "$1" nit ätter "$2" uumenaame.',
'filedeleteerror'      => 'Kuude Doatäi "$1" nit läskje.',
'directorycreateerror' => 'Dät Ferteeknis „$1“ kuude nit anlaid wäide.',
'filenotfound'         => 'Kuude Doatäi "$1" nit fiende.',
'fileexistserror'      => 'In ju Doatäi „$1“ kuude nit schrieuwen wäide, deer ju Doatäi al bestoant.',
'unexpected'           => 'Uunferwachteden Wäid: „$1“=„$2“.',
'formerror'            => '<b style="color: #cc0000;">Failer: Do Iengoawen konne nit feroarbaided wäide.</b>',
'badarticleerror'      => 'Disse Honnelenge kon ap disse Siede nit moaked wäide.',
'cannotdelete'         => 'Ju Siede od Doatäi "$1" kon nit läsked wäide.
Fielicht is ju al fon uurswál läsked wuuden.',
'badtitle'             => 'Uungultige Tittel.',
'badtitletext'         => 'Die anfräigede Tittel waas uungultich, loos, of n uungultigen Sproaklink fon n uur Wiki.',
'perfcached'           => 'Do foulgjende Doaten stamme uut dän Cache un sunt muugelkerwiese nit aktuäl:',
'perfcachedts'         => 'Disse Doaten stamme uut dän Cache, lääste Update: $1',
'querypage-no-updates' => "'''Ju Aktualisierengsfunktion foar disse Siede is apstuuns deaktivierd. Do Doaten wäide toueerst nit fernäierd.'''",
'wrong_wfQuery_params' => 'Falske Parameter foar wfQuery()<br />
Funktion: $1<br />
Oufroage: $2',
'viewsource'           => 'Wältext betrachtje',
'viewsourcefor'        => 'foar $1',
'actionthrottled'      => 'Aktionsantaal limitierd',
'actionthrottledtext'  => 'Ju Uutfierenge fon disse Aktion tou oafte in ne kuute Tiedoustand is limitierd. Du hääst dit Limit juust ieuwen beloanged. Fersäik et in eenige Minuten fon näien.',
'protectedpagetext'    => 'Disse Siede is foar dät Beoarbaidjen speerd.',
'viewsourcetext'       => 'Wältext fon disse Siede:',
'protectedinterface'   => 'Disse Siede änthaalt Text foar dät Sproak-Interface fon ju Software un is speerd, uum Misbruuk tou ferhinnerjen.',
'editinginterface'     => "'''Woarschauenge:''' Du beoarbaidest ne Siede ju der bruukt wäd, Interface-Text foar ju Software tou lääwerjen.
Annerengen ap disse Siede wirkje sik uut ap ju Benutseruurfläche foar uur Bruukere.
Foar Uursättengen koast du fielicht beeter [http://translatewiki.net/wiki/Main_Page?setlang=en translatewiki.net] bruuke, dät is dät MediaWiki Lokalisierengsprojekt.",
'sqlhidden'            => '(SQL-Oufroage ferbierged)',
'cascadeprotected'     => 'Disse Siede is tou Beoarbaidenge speerd. Ju is in do {{PLURAL:$1|foulgjende Siede|foulgjende Sieden}} ienbuunen, do der middels ju Kaskadenspeeroption schutsed {{PLURAL:$1|is|sunt}}:
$2',
'namespaceprotected'   => "Du hääst neen Begjuchtigenge, ju Siede in dän '''$1'''-Noomensruum tou beoarbaidjen.",
'customcssjsprotected' => 'Du bäst nit begjuchtiged disse Siede tou beoarbaidjen, deer ju tou do persöönelke Ienstaalengen fon n uur Benutser heert.',
'ns-specialprotected'  => 'Spezioalsieden konnen nit beoarbaided wäide.',
'titleprotected'       => "Ne Siede mäd dissen Noome kon nit moaked wäide.
Ju Speere wuude truch [[User:$1|$1]] mäd ju Begruundenge ''$2'' ienroat.",

# Virus scanner
'virus-badscanner'     => "Failerhafte Konfiguration: uunbekoanden Virenscanner: ''$1''",
'virus-scanfailed'     => 'Scan failsloain (code $1)',
'virus-unknownscanner' => 'Uunbekoanden Virenscanner:',

# Login and logout pages
'logouttext'                 => "'''Du bäst nu oumälded.'''

Du koast {{SITENAME}} nu anonym fääre benutsje, of die fonnäien unner dänsälge of n uur Benutsernoome wier [[Special:UserLogin|anmäldje]].
Beoachtje, dät eenige Sieden noch anwiese konnen, dät du oumälded bäst, soloange du nit din Browsercache loosmoaked hääst.",
'welcomecreation'            => '== Wäilkuumen, $1 ==

Dien Benutserkonto wuude iengjucht.
Ferjeet nit, dien [[Special:Preferences|{{SITENAME}}-Ienstaalengen]] antoupaasjen.',
'yourname'                   => 'Benutsernoome:',
'yourpassword'               => 'Paaswoud:',
'yourpasswordagain'          => 'Paaswoud wierhoalje:',
'remembermypassword'         => 'Ap dissen Computer duurhaft ounmälded blieuwe (Maximoal foar $1 {{PLURAL:$1|Dai|Deege}})',
'yourdomainname'             => 'Dien Domain:',
'externaldberror'            => 'Äntweeder deer lait n Failer bie ju externe Authentifizierenge foar, of du duurst din extern Benutzerkonto nit aktualisierje.',
'login'                      => 'Anmäldje',
'nav-login-createaccount'    => 'Anmäldje',
'loginprompt'                => 'Uum sik bie {{SITENAME}} anmäldje tou konnen, mouten Cookies aktivierd weese.',
'userlogin'                  => 'Anmäldje / Benutserkonto moakje',
'userloginnocreate'          => 'Anmäldje',
'logout'                     => 'Oumäldje',
'userlogout'                 => 'Oumäldje',
'notloggedin'                => 'Nit anmälded',
'nologin'                    => "Du hääst neen Benutserkonto? '''$1'''.",
'nologinlink'                => 'Hier laist du n Konto an.',
'createaccount'              => 'Benutserkonto anlääse',
'gotaccount'                 => "Du hääst al n Konto? '''$1'''.",
'gotaccountlink'             => 'Hier gungt dät ätter dän Login',
'createaccountmail'          => 'Uur Email',
'createaccountreason'        => 'Gruund:',
'badretype'                  => 'Do bee Paaswoude stimme nit uureen.',
'userexists'                 => 'Disse Benutsernoomen is al ferroat. Wääl jädden n uur.',
'loginerror'                 => 'Failer bie ju Anmäldenge',
'createaccounterror'         => 'Benutserkonto kuud nit moaked wäide: $1',
'nocookiesnew'               => 'Dien Benutsertougong wuude kloor moaked, man du bäst nit anmälded. {{SITENAME}} benutset Cookies toun Anmäldjen fon do Benutsere. Du hääst in dien Browser-Ienstaalengen Cookies deaktivierd. Uum dien näie Benutsertougong tou bruuken, läit jädden dien Browser Cookies foar {{SITENAME}} annieme un mäldje die dan mäd dien juust iengjuchten Benutsernoome un Paaswoud an.',
'nocookieslogin'             => '{{SITENAME}} benutset Cookies toun Anmäldjen fon dän Benutser. Du hääst in dien Browser-Ienstaalengen Cookies deaktivierd, jädden aktivierje do un fersäik et fonnäien.',
'noname'                     => 'Du moast n Benutsernoome anreeke.',
'loginsuccesstitle'          => 'Anmäldenge mäd Ärfoulch',
'loginsuccess'               => "'''Du bäst nu as \"\$1\" bie {{SITENAME}} anmälded.'''",
'nosuchuser'                 => 'Die Benutsernoome "$1" bestoant nit.
Uurpröif ju Schrieuwwiese (Groot-/Littekschrieuwenge beoachtje) of [[Special:UserLogin/signup|mäld die as näien Benutser an]].',
'nosuchusershort'            => 'Die Benutsernooome "<nowiki>$1</nowiki>" bestoant nit. Jädden uurpröiwe ju Schrieuwwiese.',
'nouserspecified'            => 'Reek jädden n Benutsernoome an.',
'login-userblocked'          => 'Dissen Benutser is speerd. Anmäldenge nit ferlööwed.',
'wrongpassword'              => 'Dät Paaswoud is falsk. Fersäik dät jädden fonnäien.',
'wrongpasswordempty'         => 'Du hääst ferjeeten, dien Paaswoud ientoureeken. Fersäk dät jädden fonnäien.',
'passwordtooshort'           => 'Paaswoude mouten mindestens {{PLURAL:$1|1 Teeken|$1 Teekene}} loang weese.',
'password-name-match'        => 'Dien Paaswoud mout sik fon din Benutsernoome unnerscheede.',
'mailmypassword'             => 'Näi Paaswoud touseende',
'passwordremindertitle'      => 'Näi Paaswoud foar n {{SITENAME}}-Benutserkonto',
'passwordremindertext'       => 'Wäl (woarschienelk du sälwen, fon ju IP-Adresse $1) häd n näi Paaswoud foar ju Anmäldenge bie {{SITENAME}} ($4) anfoarderd. Dät automatisk generierde tiedwiese Paaswoud foar Benutser "$2" lut nu: "$3". Is dät wät du foarhiest, dan schääst du die nu anmäldje un dät Paaswoud annerje. Dät tiedwiese Paaswoud lapt ou in {{PLURAL:$5|aan Dai|$5 Deege}}.

Ignorier disse E-Mail, in dän Fal du ju nit sälwen anfoarderd hääst of wan du dien oold Paaswoud wier betoanke kuust. Dät oolde Paaswoud blift dan wieders gultich.',
'noemail'                    => 'Benutser "$1" häd neen Email-Adrässe anroat of häd ju E-Mail-Funktion deaktivierd.',
'noemailcreate'              => 'Du moast ne gultige E-Mail-Adresse anreeke',
'passwordsent'               => 'N näi temporär Paaswoud wuude an ju Email-Adrässe fon Benutser "$1" soand. Mäldje die jädden deermäd, soo gau as du dät kriegen hääst. Dät oolde Paaswoud blift uk ätters gultich.',
'blocked-mailpassword'       => 'Ju fon die ferwoande IP-Adresse is foar dät Annerjen fon Sieden speerd. Uum n Misbruuk tou ferhinnerjen, wuude ju Muugelkhaid tou ju Anfoarderenge fon n näi Paaswoud ieuwenfals speerd.',
'eauthentsent'               => 'Ne Bestäätigengs-Email wuude an ju anroate Adrässe fersoand. Aleer n Email fon uur
Benutsere uur ju {{SITENAME}}-Mailfunktion ämpfangd wäide kon, mout ju Adrässe un hiere
wuddelke Touheeregaid tou dit Benutserkonto eerste bestäätiged wäide. Befoulgje jädden do
Waiwiese in ju Bestätigengs-E-Mail.',
'throttled-mailpassword'     => 'Der wuude binne do lääste {{PLURAL:$1|Uure|$1 Uuren}} al n näi Paaswoud anfoarderd. Uum n Misbruuk fon ju Funktion tou ferhinnerjen, kon bloot {{PLURAL:$1|insen in e Uure|alle $1 Uuren}} n näi Paaswoud anfoarderd wäide.',
'mailerror'                  => 'Failer bie dät Seenden fon dän Email: $1',
'acct_creation_throttle_hit' => 'Besäikere fon dissen Wiki, do dien IP-Adresse ferweende, hääbe dän lääste Dai {{PLURAL:$1|1 Benutserkonto|$1 Benutserkonten}} anlaid, wät ju maximoal ferlööwede Antaal in disse Tiedperiode is.

Besäikere, do disse IP-Adresse ferweende, konnen apstuuns neen Benutserkonten moor moakje.',
'emailauthenticated'         => 'Jou Email-Adrässe wuude ap n $2 uum $3 Uure bestäätiged.',
'emailnotauthenticated'      => 'Jou Email-Adrässe wuude <strong>noch nit bestäätiged</strong>. Deeruum is bit nu neen E-
Mail-Fersoand un Ämpfang foar do foulgjende Funktionen muugelk.',
'noemailprefs'               => 'Du hääst neen Email-Adrässe anroat, do foulgjende Funktione sunt deeruum apstuuns nit muugelk.',
'emailconfirmlink'           => 'Bestäätigje Jou Email-Adrässe',
'invalidemailaddress'        => 'Ju Email-Adresse wuude nit akzeptierd deeruum dät ju n ungultich Formoat tou hääben schient. Reek jädden ne Adrässe in n gultich Formoat ien of moakje dät Fäild loos.',
'accountcreated'             => 'Benutserkonto näi anlaid',
'accountcreatedtext'         => 'Dät Benutserkonto $1 wuude iengjucht.',
'createaccount-title'        => 'Benutserkonto anlääse foar {{SITENAME}}',
'createaccount-text'         => 'Wäl häd foar die n Benutserkonto "$2" ap {{SITENAME}} ($4) moaked. Dät Paaswoud foar "$2" is "$3". Du schuust die nu anmäldje un dien Paaswoud annerje.

In dän Fal dät Benutserkonto uut Fersjoon anlaid wuude, koast du disse Ättergjucht ignorierje.',
'usernamehasherror'          => 'Benutsernoomen duuren neen Ruuten-Teekene änthoolde',
'login-throttled'            => 'Du hääst tou oafte fersoacht, die antoumäldjen.
Täif, eer du fon näien fersäkst.',
'loginlanguagelabel'         => 'Sproake: $1',
'suspicious-userlogout'      => 'Dien Oumälde-Anfroage wuud ferwäigerd, deer ju fermoudelk fon n defekten Browser of n Cache-Proxy soand wuud.',

# JavaScript password checks
'password-strength'            => 'Schätsede Paaswoudstäärke: $1',
'password-strength-bad'        => 'LÄIP',
'password-strength-mediocre'   => 'middelmäitich',
'password-strength-acceptable' => 'akzeptoabel',
'password-strength-good'       => 'goud',
'password-retype'              => 'Paaswoud wierhoalje:',
'password-retype-mismatch'     => 'Paaswoude stimme nit uureen',

# Password reset dialog
'resetpass'                 => 'Paaswoud annerje',
'resetpass_announce'        => 'Anmäldenge mäd dän uur E-Mail tousoande Code. Uum ju Anmäldenge outousluuten, moast du nu n näi Paaswoud wääle.',
'resetpass_header'          => 'Paaswoud annerje',
'oldpassword'               => 'Oold Paaswoud:',
'newpassword'               => 'Näi Paaswoud:',
'retypenew'                 => 'Näi Paaswoud (nochmoal):',
'resetpass_submit'          => 'Paaswoud ienbrange un anmäldje',
'resetpass_success'         => 'Dien Paaswoud wuude mäd Ärfoulch annerd. Nu foulget ju Anmäldenge...',
'resetpass_forbidden'       => 'Dät Paaswoud kon nit annerd wäide.',
'resetpass-no-info'         => 'Du moast die anmäldje, uum ap disse Siede direkt toutougriepen.',
'resetpass-submit-loggedin' => 'Paaswoud annerje',
'resetpass-submit-cancel'   => 'Oubreeke',
'resetpass-wrong-oldpass'   => 'Uungultich tiedelk of aktuell Paaswoud.
Muugelkerwiese hääst du dien Paaswoud al mäd Ärfoulch annerd of n näi tiedelk Paaswoud fräiged.',
'resetpass-temp-password'   => 'Tiedelk Paaswoud:',

# Edit page toolbar
'bold_sample'     => 'Fatten Text',
'bold_tip'        => 'Fatten Text',
'italic_sample'   => 'Kursiven Text',
'italic_tip'      => 'Kursive Text',
'link_sample'     => 'Link-Text',
'link_tip'        => 'Internen Link',
'extlink_sample'  => 'http://www.example.com Link-Text',
'extlink_tip'     => 'Externen Link (http:// beoachtje)',
'headline_sample' => 'Ieuwene 2 Uurschrift',
'headline_tip'    => 'Ieuwene 2 Uurschrift',
'math_sample'     => 'Formel hier ienföigje',
'math_tip'        => 'Mathematiske Formel (LaTeX)',
'nowiki_sample'   => 'Uunformattierden Text hier ienföigje',
'nowiki_tip'      => 'Uunformattierden Text',
'image_sample'    => 'Biespil.jpg',
'image_tip'       => 'Doatäi-Ferbiendenge',
'media_sample'    => 'Biespil.ogg',
'media_tip'       => 'Mediendoatäi-Ferwies',
'sig_tip'         => 'Dien Signatur mäd Tiedstämpel',
'hr_tip'          => 'Horizontoale Lienje (spoarsoam ferweende)',

# Edit pages
'summary'                          => 'Touhoopefoatenge:',
'subject'                          => 'Themoa:',
'minoredit'                        => 'Bloot litje Seeken wuuden ferannerd',
'watchthis'                        => 'Disse Siede beooboachtje',
'savearticle'                      => 'Siede spiekerje',
'preview'                          => 'Foarschau',
'showpreview'                      => 'Foarschau wiese',
'showlivepreview'                  => 'Live-Foarschau',
'showdiff'                         => 'Annerengen wiese',
'anoneditwarning'                  => "'''Woarschauenge:''' Du beoarbaidest disse Siede, man du bäst nit anmälded. Wan du spiekerst, wäd dien aktuelle IP-Adresse in ju Versionsgeschichte apteekend un is deermäd  '''eepentelk''' ientousjoon.",
'anonpreviewwarning'               => '"Du bäst nit ounmälded. Bie dät Spiekerjen wäd dien IP-Adrässe in ju Versionsgeschichte apteekend."',
'missingsummary'                   => "'''Waiwiesenge:''' Du hääst neen Touhoopefoatenge anroat. Wan du fonnäien ap „Siede spiekerje“ klikst, wäd dien Annerenge sunner Touhoopefoatenge uurnuumen.",
'missingcommenttext'               => 'Reek jädden ne Touhoopefoatenge ien.',
'missingcommentheader'             => "'''OACHTENGE:''' Du hääst neen Uurschrift in dät Fäild „Beträft:“ ienroat. Wan du fonnäien ap „{{int:savearticle}}“ klikst, wäd dien Beoarbaidenge sunner Uurschrift spiekerd.",
'summary-preview'                  => 'Foarschau fon ju Touhoopefoatengsriege:',
'subject-preview'                  => 'Themoa bekiekje:',
'blockedtitle'                     => 'Benutser is blokkierd',
'blockedtext'                      => '\'\'\'Din Benutsernoome of dien IP-Adrässe wuud fon $1 speerd.\'\'\'

As Gruund wuud ounroat:
:\'\'$2\'\' (<span class="plainlinks">[{{fullurl:Special:IPBlockList|&action=search&limit=&ip=%23}}$5 Logbucheintrag]</span>)

<p style="border-style: solid; border-color: red; border-width: 1px; padding:5px;"><b>N Leesetougriep blift fäare muugelk,</b>
bloot ju Beoarbaidenge un dat Moakjen fon Sieden in {{SITENAME}} wuud speerd.
Schuul disse Ättergjucht ärschiene, wan uk bloot leesen tougriepen wuude, bast du n (rooden) Link ap ne noch nit existente Siede foulged.</p>

Du koast $1 of aan fon do uur [[{{MediaWiki:Grouppage-sysop}}|Administratore]] kontaktierje, uum uur ju Speere tou diskutierjen.

<div style="border-style: solid; border-color: red; border-width: 1px; padding:5px;">
\'\'\'Reek foulgjende Doaten in älke Anfroage an:\'\'\'
*Speerenden Administrator: $1
*Speergruund: $2
*Begin fon ju Speere: $8
*Speer-Eende: $6
*IP-Adresse: $3
*Speere betraft: $7
*Sperr-ID: #$5
</div>',
'autoblockedtext'                  => 'Dien IP-Adresse wuude automatisk speerd, deer ju fon n uur Benutser nutsed wuude, die truch $1 speerd wuude.
As Gruund wuude ounroat:

:\'\'$2\'\' (<span class="plainlinks">[{{fullurl:Special:IPBlockList|&action=search&limit=&ip=%23}}$5 Logboukiendraach]</span>)

<p style="border-style: solid; border-color: red; border-width: 1px; padding:5px;"><b>N Leesetougriep is fääre muugelk,</b>
bloot ju Beoarbaidenge un dät Moakjen fon Sieden in {{SITENAME}} wuude speerd.
Schuul disse Ättergjucht anwiesd wäide, ofwäil bloot leesend tougriepen wuude, bäst du n (rooden) Link ap ne noch nit existente Siede foulged.</p>

Du koast $1 of aan fon do uur [[{{MediaWiki:Grouppage-sysop}}|Administratore]] kontaktierje, uum uur ju Speere tou diskutierjen.

<div style="border-style: solid; border-color: red; border-width: 1px; padding:5px;">
\'\'\'Reek jädden foulgjene Doate in älke Anfroage oun:\'\'\'
*Speerenden Administrator: $1
*Speergruund: $2
*Begin fon ju Speere: $8
*Speer-Eende: $6
*IP-Adrässe: $3
*Speere beträft: $7
*Speer-ID: #$5
</div>',
'blockednoreason'                  => 'neen Begründenge ounroat',
'blockedoriginalsource'            => "Die Wältext fon '''$1''' wäd hier anwiesd:",
'blockededitsource'                => "Die Wältext '''fon dien Annerengen''' an '''$1''':",
'whitelistedittitle'               => 'Toun Beoarbaidjen is dät nöödich, anmälded tou weesen',
'whitelistedittext'                => 'Du moast die $1, uum Artikkele beoarbaidje tou konnen.',
'confirmedittext'                  => 'Du moast dien E-Mail-Adresse eerste anärkanne, eer du beoarbaidje koast. Fäl dien E-Mail uut un ärkanne ju an in do [[Special:Preferences|Ienstaalengen]].',
'nosuchsectiontitle'               => 'Kon ju Oudeelenge nit fiende',
'nosuchsectiontext'                => 'Du hääst fersoacht, ne Oudeelenge tou beoarbaidjen, ju dät nit rakt.
Ju kon ferschäuwen of läsked weese, ätterdät du ju Siede apruupen hääst.',
'loginreqtitle'                    => 'Anmäldenge ärfoarderelk',
'loginreqlink'                     => 'anmäldje',
'loginreqpagetext'                 => 'Du moast die $1, uum uur Sieden betrachtje tou konnen.',
'accmailtitle'                     => 'Paaswoud wuude fersoand.',
'accmailtext'                      => 'N toufällich generierd Paaswoud foar [[User talk:$1|$1]] wuud an $2 fersoand.

Dät Paaswoud foar dit näie Benutserkonto kon ap ju Spezioalsiede
„[[Special:ChangePassword|Paaswoud annerje]]“ annerd wäide.',
'newarticle'                       => '(Näi)',
'newarticletext'                   => "Du hääst n Link foulged ätter ne Siede, ju dät noch nit rakt.
Uum ju Siede tou moakjen, dien Text fon dän näie Artikkel iendreege in ju unnerstoundene Box (sjuch ju [[{{MediaWiki:Helppage}}|Hälpesiede]] foar moor Informatione).
Bäst du hier bie Fersjoon, klik ju '''Tourääch'''-Schaltfläche fon din Browser.",
'anontalkpagetext'                 => "----''Dit is ju Diskussionssiede fon n uunbekoanden Benutser, die sik nit anmälded häd.
Wail naan Noome deer is, wäd ju nuumeriske IP-Adrässe tou Identifizierenge ferwoand.
Man oafte wäd sunne Adrässe fon moorere Benutsere ferwoand.
Wan du n uunbekoanden Benutser bääst un du toankst dät du Kommentare krichst do nit foar die meend sunt, dan koast du ap bääste n [[Special:UserLogin/signup|Benutserkonto iengjuchte]] of die [[Special:UserLogin|anmäldje]], uum sukke Fertuusengen mäd uur anomyme Benutsere tou fermieden.''",
'noarticletext'                    => 'Deer is apstuuns naan Text ap disse Siede.
Du koast dissen Tittel ap do uur Sieden [[Special:Search/{{PAGENAME}}|säike]],
<span class="plainlinks"> in do touheerige [{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} Logbouke säike] of disse Siede [{{fullurl:{{FULLPAGENAME}}|action=edit}} beoarbaidje]</span>.',
'noarticletext-nopermission'       => 'Der is apstuuns noch naan Text ap disse Siede.
Du koast dissen Tittel ap do uur Sieden [[Special:Search/{{PAGENAME}}|säike]]
of <span class="plainlinks">in do touheerige [{{fullurl:{{#special:Log}}|page={{FULLPAGENAMEE}}}} Logbouke säike].</span>',
'userpage-userdoesnotexist'        => 'Dät Benutserkonto „$1“ is nit deer. Pröif, of du disse Siede wuddelk moakje/beoarbaidje wolt.',
'userpage-userdoesnotexist-view'   => 'Benutserkonto „$1“ bestoant nit.',
'blocked-notice-logextract'        => 'Dissen Benutser is apstuuns speerd.
Foar Information foulget n aktuellen Iendraach uut dät Benutser-Logbouk:',
'clearyourcache'                   => "'''Bemäärkenge: Ätter dät Fäästlääsen kon dät nöödich weese, dän Browser-Cache loostoumoakjen, uum do Annerengen sjo tou konnen.'''
'''Mozilla / Firefox / Safari:''' hoold ''Shift'' deel un klik ''Reload,'' of tai ''Ctrl-F5'' of ''Ctrl-R'' (''Command-R'' ap n Macintosh); '''Konqueror: '''klik ''Reload'' of tai ''F5;'' '''Opera:''' moak dän cache loos in ''Tools → Preferences;'' '''Internet Explorer:''' hoold ''Ctrl'' deel un klik ''Refresh,'' of tai ''Ctrl-F5.''",
'usercssyoucanpreview'             => "'''Tipp:''' Benutse dän  „{{int:showpreview}}“-Knoop, uum dien näi CSS foar dät Spiekerjen tou tästjen.",
'userjsyoucanpreview'              => "'''Tipp:''' Benutse dän „{{int:showpreview}}“-Knoop, uum dien näi JavaScript foar dät Spiekerjen tou tästjen.",
'usercsspreview'                   => "== Foarschau fon dien Benutser-CSS ==
'''Beoachtje:''' Ätter dät Spiekerjen moast du dien Browser anwiese, ju näie Version tou leeden: '''Mozilla/Firefox:''' ''Strg-Shift-R'', '''Internet Explorer:''' ''Strg-F5'', '''Opera:''' ''F5'', '''Safari:''' ''Cmd-Shift-R'', '''Konqueror:''' ''F5''.",
'userjspreview'                    => "== Foarschau fon dien Benutser-CSS ==
'''Beoachtje:''' Ätter dät Spiekerjen moast du dien Browser kweede, ju näie Version tou leeden: '''Mozilla/Firefox:''' ''Strg-Shift-R'', '''Internet Explorer:''' ''Strg-F5'', '''Opera:''' ''F5'', '''Safari:''' ''Cmd-Shift-R'', '''Konqueror:''' ''F5''.",
'userinvalidcssjstitle'            => "'''Woarschauenge:''' Deer existiert neen Skin \"\$1\". Betoank jädden, dät benutserspezifiske .css- un .js-Sieden män n Littek-Bouksteeuwe anfange mouten, also t.B. ''{{ns:user}}:Mustermann/monobook.css'', nit ''{{ns:user}}:Mustermann/Monobook.css''.",
'updated'                          => '(Annerd)',
'note'                             => "'''Waiwiesenge:'''",
'previewnote'                      => "'''Dit is man ne Foarschau, die Artikkel wuude noch nit spiekerd!'''",
'previewconflict'                  => 'Disse Foarschau rakt dän Inhoold fon dät buppere Täkstfäild wier; so wol die Artikkel uutsjo, wan du nu spiekerjen dääst.',
'session_fail_preview'             => "Dien Beoarbaidenge kuud nit spiekerd wäide, deer dien Sitsengsdoaten ferlädden geen sunt.
Fersäik dät jädden fonnäien, deertruch dät du unner ju foulgjende Foarschau nochmoal ap \"Siede spiekerje\" klikst.
Schuul dät Problem bestounden blieuwe, [[Special:UserLogout|mäldje die ou]] un deerätter wier an.'''",
'session_fail_preview_html'        => "'''Dien Beoarbaidenge kuud nit spiekerd wäide, deer dien Sitsengsdoaten ferlädden geen sunt.'''

''Deer in  {{SITENAME}} dät Spikerjen fon scheen HTML aktivierd is, wuude ju Foarschau uutblended uum JavaScript Angriepe tou ferhinnerjen.''

'''Fersäik et fonnäien, wan du unner ju foulgjende Textfoarschau noch moal ap „Siede spiekerje“ klikst. Schuul dät Problem bestounden blieuwe, [[Special:UserLogout|mäld die ou]] un deerätter wier an.'''",
'token_suffix_mismatch'            => "'''Dien Beoarbaidenge wuude touräächwiesd, deer dien Browser Teekene in dät Beoarbaidje-Token ferstummeld häd.
Ne Spiekerenge kon dän Siedeninhoold fernäile. Dit geböärt bietiede truch ju Benutsenge fon n anonymen Proxy-Tjoonst, die der failerhaft oarbaidet.'''",
'editing'                          => 'Beoarbaidjen fon $1',
'editingsection'                   => 'Beoarbaidje fon $1 (Apsats)',
'editingcomment'                   => 'Beoarbaidjen fon $1 (Näi Stuk)',
'editconflict'                     => 'Beoarbaidengs-Konflikt: "$1"',
'explainconflict'                  => "Uurswäl häd dissen Artikkel annerd, ätterdät du anfangd bäst, him tou beoarbaidjen.
Dät buppere Textfäild änthaalt dän aktuälle Artikkel.
Dät unnere Textfäild änthaalt dien Annerengen.
Föige jädden dien Annerengen in dät buppere Textfäild ien.
'''Bloot''' die Inhoold fon dät buppere Textfäild wäd spiekerd, wan du ap \"{{int:savearticle}}\" klikst.",
'yourtext'                         => 'Dien Text',
'storedversion'                    => 'Spiekerde Version',
'nonunicodebrowser'                => "<strong style=\"color: #330000; background: #f0e000;\">Oachtenge: Dien Browser kon Unicode-Teekene nit gjucht feroarbaidje. Benutse jädden n uur Browser uum Artikkele tou beoarbaidjen.'''",
'editingold'                       => "'''OACHTENGE: Jie beoarbaidje ne oolde Version fon disse Artikkel. Wan Jie spiekerje, wäide alle näiere Versione uurschrieuwen.'''",
'yourdiff'                         => 'Unnerscheede',
'copyrightwarning'                 => "'''Kopier neen Websieden, do nit dien oaine sunt, benuts neen uurhääbergjuchtelk schutsede Wierke sunner Ferlof fon dän Copyright-Inhääber!'''<br />
Du toukwäst uus hiermäd, dät du dän Text '''sälwen ferfoated''' hääst, dät dän Text Algemeengoud ('''public domain''') is, of dät die '''Copyright-Inhääber''' sien '''Toustämmenge''' roat häd. In dän Fal dät dissen Text al uurswain publizierd wuude, wies jädden ap ju Diskussionssiede deerap wai.
<i>Beoachtje, dät aal {{SITENAME}}-Biedraage automatisk unner ju „$2“ stounde (sjuch $1 foar Details). In dän Fal dät du nit moatest, dät dien Oarbaid hier fon Uurswäkken annerd un fersprat wäd, druk dan '''nit''' ap „Siede spiekerje“.</i>",
'copyrightwarning2'                => 'Aal Biedraage tou dän {{SITENAME}} konnen fon uur Ljuude ferannerd un fersprat wäide. Fals Jie nit moaten dät Jou Oarbaid hier fon uur Ljuude ferannerd un fersprat wäd, dan drukke Jie nit ap "Spiekerje".

Jie fersicherje hiermäd uk, dät Jie dän Biedraach sälwen ferfoated hääbe blw. dät hie neen froamd Gjucht ferlätset (sjuch fääre: $1).',
'longpageerror'                    => "'''FAILER: Die Text, dän du tou spiekerjen fersäkst, is $1 KB groot. Dät is gratter as dät ferlööwede Maximum fon $2 KB – Spiekerenge nit muugelk.'''",
'readonlywarning'                  => "'''WOARSCHAUENGE: Ju Doatenboank wuude foar Wartengsoarbaiden speerd, so dät dien Annerengen apstuuns nit spiekerd wäide konnen.
Sicherje dän Text jädden lokoal ap dien Computer un fersäik tou n leeteren Tiedpunkt, do Annerengen tou uurdreegen.'''

Gruund foar ju Speere: $1",
'protectedpagewarning'             => "'''WOARSCHAUENGE: Disse Siede wuude speerd. Bloot Benutsere mäd Administratorgjuchte konnen  ju Siede beoarbaidje.''' Foar Information foulget die aktuelle Logboukiendraach:",
'semiprotectedpagewarning'         => "'''Hoolfspeerenge:''' Ju Siede wuud so speerd, dät bloot registrierde Benutsere ju annerje konnen. Foar Information foulget ju aktuelle Iendraach:",
'cascadeprotectedwarning'          => "'''WOARSCHAUENGE: Disse Siede wuude speerd, so dät ju bloot truch Benutsere mäd Administratorgjuchte beoarbaided wäide kon. Ju is in do {{PLURAL:$1|foulgjende Siede|foulgjende Sieden}} ienbuunen, do der middels ju Kaskadenspeeroption schutsed {{PLURAL:$1|is|sunt}}:'''",
'titleprotectedwarning'            => "'''WOARSCHAUENGE: Dät Moakjen fon Sieden wuud speerd. Bloot Benutsere mäd  [[Special:ListGroupRights|spezielle Gjuchte]] konnen ju Siede moakje.''' Foar Information foulget ju aktuelle Logboukiendraach:",
'templatesused'                    => '{{PLURAL:$1|Ju foulgjende Foarloage wäd|Do foulgjende Foarloagen wäide}} fon disse Siede ferwoand:',
'templatesusedpreview'             => '{{PLURAL:$1|Ju foulgjende Foarloage wäd|Do foulgjende Foarloagen wäide}} fon dissen Siedefoarschau ferwoand:',
'templatesusedsection'             => '{{PLURAL:$1|Ju foulgjende Foarloage wäd|Do foulgjende Foarloagen wäide}} fon disse Oudeelenge ferwoand:',
'template-protected'               => '(schutsed)',
'template-semiprotected'           => '(Siedenschuts foar nit anmäldede un näie Benutsere)',
'hiddencategories'                 => 'Disse Siede is Meeglid fon {{PLURAL:$1|1 ferstatte Kategorie|$1 ferstatte Kategorien}}:',
'edittools'                        => '<!-- Text hier stoant unner Beoarbaidengsfäildere un Hoochleedefäildere. -->',
'nocreatetitle'                    => 'Dät Moakjen fon näie Sieden is begränsed',
'nocreatetext'                     => 'Ap {{SITENAME}} wuude dät Moakjen fon näie Sieden begränsed. Du koast al bestoundene Sieden beoarbaidje of die [[Special:UserLogin|anmäldje]].',
'nocreate-loggedin'                => 'Du hääst neen Begjuchtigenge, näie Sieden antoulääsen.',
'sectioneditnotsupported-title'    => 'Ju Beoarbaidenge fon Ousnitte wäd nit unnerstutsed',
'sectioneditnotsupported-text'     => 'Ju Beoarbaidenge fon Ousnitte wäd ap disse Beoarbaidengssiede nit unnerstutsed.',
'permissionserrors'                => 'Begjuchtigengs-Failere',
'permissionserrorstext'            => 'Du bäst nit begjuchtiged, ju Aktion uuttoufieren. {{PLURAL:$1|Gruund|Gruunde}}:',
'permissionserrorstext-withaction' => 'Du bäst nit begjuchtiged, ju Aktion „$2“ uuttoufieren, {{PLURAL:$1|Gruund|Gruunde}}:',
'recreate-moveddeleted-warn'       => "'''Oachtenge: Du moakest ne Siede, ju der al fröier läsked wuude.'''

Pröif mäd Suurge, of dät näi Moakjen fon ju Siede do Gjuchtlienjen äntspräkt.
Tou dien Information foulget dät Läsk- un Ferschäuwengs-Logbouk mäd ju Begründenge foar ju fröiere Läskenge:",
'moveddeleted-notice'              => 'Disse Siede wuud läsked. Der foulget n Uutsuch uut dät Läsk- un Ferschäuwengs-Logbouk foar disse Siede.',
'log-fulllog'                      => 'Aal Logboukiendraage bekiekje',
'edit-hook-aborted'                => 'Ju Beoarbaidenge wuud sunner Ärkläärenge truch ne Snitsteede oubreeken.',
'edit-gone-missing'                => 'Ju Siede kuud nit aktualisierd wäide.
Ju wuud anschienend läsked.',
'edit-conflict'                    => 'Beoarbaidengskonflikt.',
'edit-no-change'                   => 'Dien Beoarbaidenge wuude ignorierd, deer neen Annerenge an dän Text foarnuumen wuude.',
'edit-already-exists'              => 'Ju näie Siede kuud nit moaked wäide, deer ju al foarhounden is.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Woarschauenge: Disse Siede änthaalt tou fuul Aproupe fon stuure Parserfunktione.

Der {{PLURAL:$2|duur nit moor as 1 Aproup|duuren nit moor as $1 Aproupe}} weese.',
'expensive-parserfunction-category'       => 'Sieden, do tou oafte stuure Parserfunktione aproupe',
'post-expand-template-inclusion-warning'  => 'Woarschauenge: Ju Grööte fon do bietouföigede Foarloagen is tou groot, eenige Foarloagen konnen nit bietouföiged wäide.',
'post-expand-template-inclusion-category' => 'Sieden, in wäkke do bietouföigede Foarloagen buppe ju maximoale Grööte kuume',
'post-expand-template-argument-warning'   => 'Woarschauenge: Disse Siede änthaalt ap et minste een Argument in ne Foarloage, dät expandierd tou groot is. Disse Argumente wäide ignorierd.',
'post-expand-template-argument-category'  => 'Sieden, do der ignorierde Foarloagenargumente änthoolde',
'parser-template-loop-warning'            => 'Foarloagenstrik äntdäkt: [[$1]]',
'parser-template-recursion-depth-warning' => 'Foarloagenrekursionsdjuptenscheed uurtreeden ($1)',
'language-converter-depth-warning'        => 'Sproakkonverter-djüptelimit uurtreeden ($1)',

# "Undo" feature
'undo-success' => 'Ju Annerenge kuud mäd Ärfoulch tourääch annerd wäide. Jädden ju Beoarbaidenge in ju Ferglieksansicht kontrollierje un dan ap „Siede spiekerje“ klikke, uum ju tou spiekerjen.',
'undo-failure' => 'Ju Annerenge kuud nit tourääch annerd wäide, deer ju betroffene Oudeelenge intwisken ferannerd wuude.',
'undo-norev'   => 'Ju Beoarbaidenge kuud nit räägels troald wäide, deer ju nit foarhounden is of läsked wuude.',
'undo-summary' => 'Annerenge $1 fon [[Special:Contributions/$2|$2]] ([[User talk:$2|Diskussion]]) wuude tourääch annerd.',

# Account creation failure
'cantcreateaccounttitle' => 'Benutserkonto kon nit moaked wäide',
'cantcreateaccount-text' => "Dät Moakjen fon n Benutserkonto fon ju IP-Adresse '''$1''' uut wuude fon [[User:$3|$3]] speerd.

Gruund fon ju Speere: ''$2''",

# History pages
'viewpagelogs'           => 'Logbouke foar disse Siede anwiese',
'nohistory'              => 'Dät rakt neen fröiere Versione fon dissen Artikkel.',
'currentrev'             => 'Aktuälle Version',
'currentrev-asof'        => 'Aktuelle Version fon $1',
'revisionasof'           => 'Version fon $1',
'revision-info'          => 'Dit is ne oolde Version. Tiedpunkt fon ju Beoarbaidenge: $1 truch $2.',
'previousrevision'       => '← Naistallere Version',
'nextrevision'           => 'Naistjungere Version →',
'currentrevisionlink'    => 'Aktuälle Version',
'cur'                    => 'Aktuäl',
'next'                   => 'Naiste',
'last'                   => 'Foarige',
'page_first'             => 'Ounfang',
'page_last'              => 'Eend',
'histlegend'             => "Diff  Uutwoal: Do Boxen fon do wonskede Versione markierje un 'Enter' drukke of ap dän Knoop unner klikke.<br />
Legende: (Aktuäl) = Unnerscheed tou ju aktuälle Version,
(Lääste) = Unnerscheed tou ju foarige Version, L = Litje Annerenge",
'history-fieldset-title' => 'Säik in ju Versionsgeschichte',
'history-show-deleted'   => 'bloot läskede Versione',
'histfirst'              => 'Ooldste',
'histlast'               => 'Näiste',
'historysize'            => '({{PLURAL:$1|1 Byte|$1 Bytes}})',
'historyempty'           => '(loos)',

# Revision feed
'history-feed-title'          => 'Versionsgschicht',
'history-feed-description'    => 'Versionsgeschichte foar disse Siede in {{SITENAME}}',
'history-feed-item-nocomment' => '$1 uum $2',
'history-feed-empty'          => 'Ju anfoarderde Siede existiert nit. Fielicht wuud ju läsked of ferschäuwen. [[Special:Search|Truchsäik]] {{SITENAME}} foar paasjende näie Sieden.',

# Revision deletion
'rev-deleted-comment'         => '(Beoarbaidengskommentoar wächhoald)',
'rev-deleted-user'            => '(Benutsernoome wächhoald)',
'rev-deleted-event'           => '(Logbouk-Aktion wächhoald)',
'rev-deleted-user-contribs'   => '[Benutsernoome of IP-Adrässe wächhoald - Beoarbaidenge uut Biedreege ferstat.]',
'rev-deleted-text-permission' => "Disse Version wuude '''läsked'''.
Naiere Angoawen toun Läskfoargong as uk ne Begründenge fiende sik in dät [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Läsk-Logbouk].",
'rev-deleted-text-unhide'     => "Disse Version wuud '''läsked'''.
Details stounde in dät [{{fullurl:{{#special:Log}}/delete|page={{FULLPAGENAMEE}}}} Läsk-Logbouk].
As Administrator koast du noch [$1 ju Version bekiekje], wan du fääregunge moatest.",
'rev-suppressed-text-unhide'  => "Disse Version wuud '''unnerdrukt'''.
Details stounde in dät [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Unnerdrukkengs-Logbouk].
Du as Administrator koast [$1 disse Version bekiekje], wan du et wonskest.",
'rev-deleted-text-view'       => "Disse Version wuude '''läsked'''.
As Administrator koast du ju wieders ienkiekje.
Naiere Angoawen toun Läskfoargong as uk ne Begründenge fiende sik in dät [{{fullurl:{{#special:Log}}/delete|page={{FULLPAGENAMEE}}}} Läsk-Logbouk].",
'rev-suppressed-text-view'    => "Disse Version wuud '''unnerdrukt'''.
Administratore konnen ju ienkiekje; Details stounde in dät [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Unnerdrukkengs-Logbouk].",
'rev-deleted-no-diff'         => "Du koast dissen Unnerscheed nit betrachtje, deer een fon do Versione '''läsked''' wuude.
Details stounde in dät [{{fullurl:{{#special:Log}}/delete|page={{FULLPAGENAMEE}}}} Läsk-Logbouk].",
'rev-suppressed-no-diff'      => "Du koast dissen Versionsunnerscheed nit betrachtje, deer een fon do Versione '''läsked''' wuud.",
'rev-deleted-unhide-diff'     => "Een fon do Versione fon dissen Unnerscheed wuud '''läsked'''.
Details stounde in dät [{{fullurl:{{#special:Log}}/delete|page={{FULLPAGENAMEE}}}} Läsk-Logbouk].
As Adminstrator koast du noch [$1 dissen Versionsunnerscheed bekiekje] wan du fääregunge wolt.",
'rev-suppressed-unhide-diff'  => "Een fon do Versione fon dissen Unnerscheed wuud '''unnerdrukt'''.
Details stounde in dät [{{fullurl:{{#special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Unnerdrukkengs-Logbouk].
As Adminstrator koast du noch [$1 dissen Versionsunnerscheed bekiekje] wan du fääregunge wolt.",
'rev-deleted-diff-view'       => "Ne Version fon dissen Versionsunnerscheed wuud '''läsked'''.
As Administrator koast du dissen Versionsunnerscheed sjo. Details fiende sik in dät [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} Läsk-Logbouk].",
'rev-suppressed-diff-view'    => "Een fon do Versione fon dissen Versionsunnerscheed wuud '''unnerdrukt'''.
As Administrator koast du dissen Versionsunnerscheed sjo. Details fiende sik in dät [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Unnerdrukkengs-Logbouk].",
'rev-delundel'                => 'wiese/ferbierge',
'rev-showdeleted'             => 'wies',
'revisiondelete'              => 'Versione läskje/wier häärstaale',
'revdelete-nooldid-title'     => 'Uunjäildige Siel-Beoarbaidenge',
'revdelete-nooldid-text'      => 'Du hääst neen Version ounroat, wierap disse Aktion uutfierd wäide schäl, ju wäälde Version is nit deer of du fersäkst, ju aktuelle Version wächtouhoaljen.',
'revdelete-nologtype-title'   => 'Naan Logtyp anroat',
'revdelete-nologtype-text'    => 'Der wuud naan Logtyp foar disse Aktion anroat.',
'revdelete-nologid-title'     => 'Uungultigen Logiendraach',
'revdelete-nologid-text'      => 'Der wuud naan Logtyp uutwääld of die wäälde Logtyp existiert nit.',
'revdelete-no-file'           => 'Ju anroate Doatäi bestoant nit.',
'revdelete-show-file-confirm' => 'Bäst du sicher, dät du ju läskede Version fon ju Doatäi „<nowiki>$1</nowiki>“ fon dän $2 uum $3 Uure bekiekje wolt?',
'revdelete-show-file-submit'  => 'Jee',
'revdelete-selected'          => "'''{{PLURAL:$2|Uutwäälde Version|Uutwäälde Versione}} fon [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Uutwäälden Logboukiendraach|Uutwäälde Logboukiendraage}}:'''",
'revdelete-text'              => "'''Läskede Versione un Aktione ferblieuwe in ju Versionsgeschichte un do Logbouke, man Deele deerfon sunt nit eepentelk ientoukiekjen.'''
Uur Administratore ap {{SITENAME}} hääbe Tougriep ap dän ferstatte Inhoold un konnen him mäd ju glieke Siede wier moakje, insowied uurhoop neen Ientuunengen bestounde.",
'revdelete-confirm'           => 'Bestäätigje, dät du dit wuddelk dwo wolt, dät du do Konsequenze ferstoanst un dät in Uureenstimmenge mäd do [[{{MediaWiki:Policy-url}}|Gjuchtlienjen]] dääst.',
'revdelete-suppress-text'     => "Unnerdrukkengen schuulen '''bloot''' in do foulgjende Fälle foarnuumen waide:
* Uunpaasende persöönelke Informatione
*: ''Adressen, Telefonnummere, Sozialversicherungsnummere usw.''",
'revdelete-legend'            => 'Sät Ienschränkengen foar do Versione',
'revdelete-hide-text'         => 'Text fon ju Version ferstopje',
'revdelete-hide-image'        => 'Bielde-Inhoold ferstopje',
'revdelete-hide-name'         => 'Logbouk-Aktion ferstopje',
'revdelete-hide-comment'      => 'Beoarbaidengskommentoar ferstopje',
'revdelete-hide-user'         => 'Benutsernoome/ju IP fon dän Beoarbaider ferstopje',
'revdelete-hide-restricted'   => 'Doaten uk foar Administratore un uurswäkke unnerdrukke',
'revdelete-radio-same'        => '(nit annerje)',
'revdelete-radio-set'         => 'Jee',
'revdelete-radio-unset'       => 'Noa',
'revdelete-suppress'          => 'Gruund fon ju Läskenge uk foar Administratore ferstopped',
'revdelete-unsuppress'        => 'Ienschränkengen foar wier häärstoalde Versione aphieuwje',
'revdelete-log'               => 'Gruund:',
'revdelete-submit'            => 'Ap uutwäälde  {{PLURAL:$1|Version|Versione}} anweende',
'revdelete-logentry'          => 'Versionsansicht annerd foar [[$1]]',
'logdelete-logentry'          => 'annerde ju Sichtboarkaid foar [[$1]]',
'revdelete-success'           => "'''Ju Versionsansicht wuud aktualisierd.'''",
'revdelete-failure'           => "Ju Versionssichtboarkaid kon nit aktualisierd wäide:'''
$1",
'logdelete-success'           => "'''Logbouk-Aktion mäd Ärfoulch sät.'''",
'logdelete-failure'           => "'''Logbouksichtboarkaid kon nit annerd wäide:'''
$1",
'revdel-restore'              => 'Sichtboarhaid annerje',
'revdel-restore-deleted'      => 'läskede Versione',
'revdel-restore-visible'      => 'sichtboare Revisione',
'pagehist'                    => 'Siedegeschichte',
'deletedhist'                 => 'Läskede Versione',
'revdelete-content'           => 'Siedeninhoold',
'revdelete-summary'           => 'Touhoopefoatengskommentoar',
'revdelete-uname'             => 'Benutsernoome',
'revdelete-restricted'        => 'Einschränkengen jäilde uk foar Administratore',
'revdelete-unrestricted'      => 'Ienschränkengen foar Administratore wächhoald',
'revdelete-hid'               => 'ferstatte $1',
'revdelete-unhid'             => 'moakede $1 wier eepentelk',
'revdelete-log-message'       => '$1 foar $2 {{PLURAL:$2|Version|Versione}}',
'logdelete-log-message'       => '$1 foar $2 {{PLURAL:$2|Logboukiendraach|Logboukiendraage}}',
'revdelete-hide-current'      => 'Failer bie dät Fersteeten fon dän Iendraach fon $1, $2 Uure: Dit is ju aktuelle Version.
Ju kon nit ferstat wäide.',
'revdelete-show-no-access'    => 'Failer bie dät Anwiesen fon dän Iendraach fon $1, $2 Uure: Dissen Iendraach wuud as "betüümt" markierd.
Du hääst deer naan Tougriep ap.',
'revdelete-modify-no-access'  => 'Failer bie dät Beoarbaidjen fon dän Iendraach fon $1, $2 Uure: Dissen Iendraach wuud as "betüümt" markierd.
Du hääst deer naan Tougriep ap.',
'revdelete-modify-missing'    => 'Failer bie dät Beoarbaidjen fon ID $1: Dät failt in ju Doatenboank!',
'revdelete-no-change'         => "'''Woarschauenge:''' Die Iendraach fon $1, $2 Uure häd al do wonskede Sichtboarkaids-Ienstaalengen.",
'revdelete-concurrent-change' => 'Failer bie dät Beoarbaidjen fon dän Iendraach fon $1, $2 Uure: Dät schient, as wan die Stoatus fon wäl annerd wuude, eer du foarhiest, him tou beoarbaidjen.
Wröigje do Logbouke.',
'revdelete-only-restricted'   => 'Failer bie dät Fersteeten fon dän Iendraach fon $1, $2 Uure: Du koast naan Iendraach foar Administratore fersteete, sunner een fon do uur Ansichtssoptione wääld tou hääben.',
'revdelete-reason-dropdown'   => '*Algemeene Läskgruunde
** Uurheebergjuchtsferlätsenge
** Persöönelke Informatione, do sik nit so heere
** Information, ju wäl an de Eere kuume kon',
'revdelete-otherreason'       => 'Uur/bietoukuumende Begründenge:',
'revdelete-reasonotherlist'   => 'Uur Begründenge',
'revdelete-edit-reasonlist'   => 'Läskgruunde beoarbaidje',
'revdelete-offender'          => 'Autor fon ju Version:',

# Suppression log
'suppressionlog'     => 'Uursicht-Logbouk',
'suppressionlogtext' => 'Dit is dät Logbouk fon do Uursicht-Aktione (Annerengen fon ju Sichtboarhaid fon Versione, Beorbaidengskommentare, Benutsernoomen un Benutserspeeren).',

# Revision move
'moverevlogentry'              => 'ferschäuw {{PLURAL:$3|een Version|$3 Versione}} fon $1 ätter $2',
'revisionmove'                 => 'Moor Revisione fon "$1"',
'revmove-explain'              => 'Do foulgjende Versione wäide fon $1 tou ju anroate Sielsiede ferschäuwen. Fals ju Sielsiede nit bestoant, wäd ju moaked. Uurfals wäide disse Versione in ju Versionsgeschichte touhoopefierd.',
'revmove-legend'               => 'Sielsiede un Touhoopefpatenge fäästlääse',
'revmove-submit'               => 'Versione tou ju uutwäälde Siede ferschäuwen',
'revisionmoveselectedversions' => 'Uutwäälde Versione ferschuuwe',
'revmove-reasonfield'          => 'Gruund:',
'revmove-titlefield'           => 'Sielsiede:',
'revmove-badparam-title'       => 'Falske Parameetere',
'revmove-badparam'             => 'Dien Anfroage änthaalt nit-ferlööwede of gebräkkelke Parameetere. Klik ap "tourääch" un fersäik dät nochmoal.',
'revmove-norevisions-title'    => 'Uunjäildige Sielversion',
'revmove-norevisions'          => 'Du hääst neen Sielversion ounroat, uum disse Aktion truchtoufieren of ju ounroate Version bestoant nit.',
'revmove-nullmove-title'       => 'Uungultigen Tittel',
'revmove-nullmove'             => 'Wälle- un Sielsiede sunt identisk. Klik ap "tourääch" un reek n uur Siedennoome as "[[$1]]" ien.',
'revmove-success-existing'     => '{{PLURAL:$1|Ne Version fon [[$2]] wuud|$1 Versione fon [[$2]] wuuden}} tou ju existierende Siede [[$3]] ferschäuwen.',

# History merging
'mergehistory'                     => 'Versionsgeschichten fereenigje',
'mergehistory-header'              => 'Mäd disse Spezialsiede koast du ju Versionsgeschichte fon ne Uursproangssiede mäd ju Versionsgeschichte fon ne Sielsiede fereenigje.
Staal deertruch sicher, dät ju Versionsgeschichte fon n Artikkel historisk akroat is.',
'mergehistory-box'                 => 'Versionsgeschichten fon two Sieden fereenigje',
'mergehistory-from'                => 'Uursproangssiede:',
'mergehistory-into'                => 'Sielsiede:',
'mergehistory-list'                => 'Versione, do der fereeniged wäide konnen',
'mergehistory-merge'               => 'Do foulgjende Versione fon „[[:$1]]“ konnen ätter „[[:$2]]“ uurdrain wäide. Markier ju Version, bit tou ju (iensluutend) do Versione wäide schällen. Beoachte jädden, dät ju Nutsenge fon do Navigationslinke ju Uutwoal touräächsät.',
'mergehistory-go'                  => 'Wies Versione, do der fereeniged wäide konnen',
'mergehistory-submit'              => 'Fereenige Versione',
'mergehistory-empty'               => 'Der konnen neen Versione fereeniged wäide.',
'mergehistory-success'             => '{{PLURAL:$3|Beoarbaidenge|Beoarbaidengen}} fon [[:$1]] mäd Ärfoulch ätter [[:$2]] fereeniged.',
'mergehistory-fail'                => 'Versionsfereenigenge nit muugelk, pröif ju Siede un do Tiedangoawen.',
'mergehistory-no-source'           => 'Uursproangssiede „$1“ is nit deer.',
'mergehistory-no-destination'      => 'Sielsiede „$1“ is nit deer.',
'mergehistory-invalid-source'      => 'Uursproangssiede mout n gultigen Siedennoome weese.',
'mergehistory-invalid-destination' => 'Sielsiede mout n gultigen Siedennoome weese.',
'mergehistory-autocomment'         => '„[[:$1]]“ fereeniged ätter „[[:$2]]“',
'mergehistory-comment'             => '[[:$1]] fereeniged ätter [[:$2]]: $3',
'mergehistory-same-destination'    => 'Uutgongs- un Sielsiede duuren nit identisk weese',
'mergehistory-reason'              => 'Begruundenge:',

# Merge log
'mergelog'           => 'Fereenigengs-Logbouk',
'pagemerge-logentry' => 'fereenigede [[$1]] in [[$2]] (Versione bit $3)',
'revertmerge'        => 'tourääch annerd Fereenigenge',
'mergelogpagetext'   => 'Dit is dät Logbouk fon do fereenigede Versionsgeschichten.',

# Diffs
'history-title'            => 'Versionsgeschichte fon "$1"',
'difference'               => '(Unnerscheed twiske Versione)',
'lineno'                   => 'Riege $1:',
'compareselectedversions'  => 'Wäälde Versione ferglieke',
'showhideselectedversions' => 'Uutwäälde Versione wiese/fersteete',
'editundo'                 => 'tounichte moakje',
'diff-multi'               => '(Die Versionsfergliek belukt {{PLURAL:$1|ne deertwiske lääsende Version|$1 deertwiske lääsende Versione}} mee ien.)',

# Search results
'searchresults'                    => 'Säikresultoate',
'searchresults-title'              => 'Säikresultoate foar "$1"',
'searchresulttext'                 => 'Foar moor Informatione tou ju Säike sjuch ju [[{{MediaWiki:Helppage}}|Hälpesiede]].',
'searchsubtitle'                   => 'Dien Säikanfroage: „[[:$1|$1]]“ ([[Special:Prefixindex/$1|aal mäd „$1“ ounfangende Sieden]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|aal Sieden, do ätter „$1“ ferlinkje]])',
'searchsubtitleinvalid'            => 'Foar dien Säikanfroage „$1“.',
'toomanymatches'                   => 'Ju Antaal fon Säikresultoate is tou groot, fersäik ne näie Oufroage.',
'titlematches'                     => 'Uureenstämmengen mäd Uurschrifte',
'notitlematches'                   => 'Neen Uureenstimmengen',
'textmatches'                      => 'Uureenstämmengen mäd Texte',
'notextmatches'                    => 'Neen Uureenstimmengen',
'prevn'                            => 'foarige {{PLURAL:$1|$1}}',
'nextn'                            => 'naiste {{PLURAL:$1|$1}}',
'prevn-title'                      => '{{PLURAL:$1|Foarich Resultoat|Foarige $1 Resultoate}}',
'nextn-title'                      => '{{PLURAL:$1|Foulgjend Resultoat|Foulgjende $1 Resultoate}}',
'shown-title'                      => 'Wies $1 {{PLURAL:$1|Resultoat|Resultoate}} pro Siede',
'viewprevnext'                     => 'Wies ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'Säikoptione',
'searchmenu-exists'                => "'''Dät rakt n Siede mäd Noome \"[[:\$1]]\" ap dissen Wiki'''",
'searchmenu-new'                   => "'''Moak ju Siede „[[:$1]]“ in dissen Wiki.'''",
'searchhelp-url'                   => 'Help:Hälpe',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Wies aal Sieden, do mäd dän Säikbegriep ounfange]]',
'searchprofile-articles'           => 'Inhooldssieden',
'searchprofile-project'            => 'Hälpe un Projektsieden',
'searchprofile-images'             => 'Multimedia',
'searchprofile-everything'         => 'Aal',
'searchprofile-advanced'           => 'Fergratterd',
'searchprofile-articles-tooltip'   => 'Säike in $1',
'searchprofile-project-tooltip'    => 'Säike in $1',
'searchprofile-images-tooltip'     => 'Ätter Bielden säike',
'searchprofile-everything-tooltip' => 'Gansen Inhoold truchsäike (inklusive Diskussionssieden)',
'searchprofile-advanced-tooltip'   => 'Säik in wiedere Noomensruume',
'search-result-size'               => '$1 ({{PLURAL:$2|1 Woud|$2 Woude}})',
'search-result-score'              => 'Relevanz: $1 %',
'search-redirect'                  => '(Wiederlaitenge $1)',
'search-section'                   => '(Apsnit $1)',
'search-suggest'                   => 'Meendest du $1?',
'search-interwiki-caption'         => 'Susterprojekte',
'search-interwiki-default'         => '$1 Resultoate:',
'search-interwiki-more'            => '(wiedere)',
'search-mwsuggest-enabled'         => 'mäd Foarsleeke',
'search-mwsuggest-disabled'        => 'neen Foarsleeke',
'search-relatedarticle'            => 'Früünde',
'mwsuggest-disable'                => 'Foarsleeke truch Ajax deaktivierje',
'searcheverything-enable'          => 'Säik in aal Noomensruume',
'searchrelated'                    => 'früünd',
'searchall'                        => 'aal',
'showingresults'                   => "Hier {{PLURAL:$1|is '''1''' Resultoat|sunt '''$1''' Resultoate}}, ounfangend mäd Nuumer '''$2'''.",
'showingresultsnum'                => "Hier {{PLURAL:$3|is '''1''' Resultoat|sunt '''$3''' Resultoate}}, ounfangend mäd Nuumer '''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5|Resultoat '''$1''' fon '''$3'''|Resultoate '''$1–$2''' fon '''$3'''}} foar '''$4'''",
'nonefound'                        => "'''Waiwiesenge:''' Der wäide standoardmäitich man oankelde Noomensruume truchsoacht. Sät ''all:'' foar din Säikbegrip, uum aal Sieden (bietou Diskussionssieden, Foarloagen usw.) tou truchsäiken of sield dän Noome fon dän truchtousäikende Noomensruum.",
'search-nonefound'                 => 'Foar dien Säikanfroage wuuden neen Resultoate fuunen.',
'powersearch'                      => 'Fääre säike',
'powersearch-legend'               => 'Fääre säike',
'powersearch-ns'                   => 'Säik in Noomensruume:',
'powersearch-redir'                => 'Fäärelaitengen anwiese',
'powersearch-field'                => 'Säik ätter:',
'powersearch-togglelabel'          => 'Wääl uut:',
'powersearch-toggleall'            => 'Aal',
'powersearch-togglenone'           => 'Neen',
'search-external'                  => 'Externe Säike',
'searchdisabled'                   => 'Ju {{SITENAME}} Fultextsäike is weegen Uurläästenge apstuuns deaktivierd. Du koast insteede deerfon ne Google- of Yahoo-Säike startje. Do Resultoate foar {{SITENAME}} speegelje oawers nit uunbedingd dän aktuällen Stand wier.',

# Quickbar
'qbsettings'               => 'Siedenlieste',
'qbsettings-none'          => 'Naan',
'qbsettings-fixedleft'     => 'Links, fääst',
'qbsettings-fixedright'    => 'Gjuchts, fääst',
'qbsettings-floatingleft'  => 'Links, swieuwjend',
'qbsettings-floatingright' => 'Gjuchts, swieuwjend',

# Preferences page
'preferences'                   => 'Ienstaalengen',
'mypreferences'                 => 'Ienstaalengen',
'prefs-edits'                   => 'Antaal Beoarbaidengen:',
'prefsnologin'                  => 'Nit anmälded',
'prefsnologintext'              => 'Du moast <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} anmälded]</span> weese, uum dien Ienstaalengen annerje tou konnen.',
'changepassword'                => 'Paaswoud annerje',
'prefs-skin'                    => 'Skin',
'skin-preview'                  => 'Foarschau',
'prefs-math'                    => 'TeX',
'datedefault'                   => 'Neen Preferenz',
'prefs-datetime'                => 'Doatum un Tied',
'prefs-personal'                => 'Benutserdoaten',
'prefs-rc'                      => 'Bekoandreekenge fon "Lääste Annerengen"',
'prefs-watchlist'               => 'Beooboachtengslieste',
'prefs-watchlist-days'          => 'Antaal fon Deege, do ju Beooboachtengslieste standoardmäitich uumfoatje schäl:',
'prefs-watchlist-days-max'      => '(Maximoal 7 Deege)',
'prefs-watchlist-edits'         => 'Maximoale Antaal fon Iendraage in ju fergratterde Beooboachtengslieste:',
'prefs-watchlist-edits-max'     => '(Maximoale Antaal: 1000)',
'prefs-watchlist-token'         => 'Beooboachtengslieste-Token:',
'prefs-misc'                    => 'Ferscheedene Ienstaalengen',
'prefs-resetpass'               => 'Paaswoud annerje',
'prefs-email'                   => 'E-Mail-Optione',
'prefs-rendering'               => 'Wo dät uutsjucht',
'saveprefs'                     => 'Ienstaalengen spiekerje',
'resetprefs'                    => 'Nit spiekerde Annerengen fersmiete',
'restoreprefs'                  => 'Aal Standoard-Ienstaalengen wier häärstaale',
'prefs-editing'                 => 'Beoarbaidje',
'prefs-edit-boxsize'            => 'Grööte fon dät Beoarbaidengsfinster:',
'rows'                          => 'Riegen',
'columns'                       => 'Spalten',
'searchresultshead'             => 'Säike (010)',
'resultsperpage'                => 'Träffere pro Siede:',
'contextlines'                  => 'Teekene pro Träffer:',
'contextchars'                  => 'Teekene pro Riege:',
'stub-threshold'                => '<a href="#" class="stub">Kuute Artikkele</a> markierje bi (in Byte):',
'recentchangesdays'             => 'Antaal fon Deege, do ju Lieste fon „Lääste Annerengen“ standoardmäitich uumfoatje schäl:',
'recentchangesdays-max'         => '(Maximoal $1 {{PLURAL:$1|Dai|Deege}})',
'recentchangescount'            => 'Antaal fon do standoardmäitich anwiesde Beoarbaidengen:',
'prefs-help-recentchangescount' => 'Dit uumfoatet ju Lieste fon do lääste Annerengen, ju Versionsgeschichte un do Logbouke.',
'prefs-help-watchlist-token'    => 'Dät Uutfällen fon dit Fäild mäd ne stilkene Koaie generiert n RSS-Feed foar dien Beooboachtengslieste.
Älk, die disse Koaie koant, kon dien Beooboachtengslieste iensjo. Wääl also n sicheren Wäid.
Hier n toufällich generierden Wäid, dän du ferweende koast: $1',
'savedprefs'                    => 'Dien Ienstaalengen wuuden spiekerd.',
'timezonelegend'                => 'Tiedzone:',
'localtime'                     => 'Tied bie Jou:',
'timezoneuseserverdefault'      => 'Standoardtied fon dän Server',
'timezoneuseoffset'             => 'Uur (Unnerscheed anreeke)',
'timezoneoffset'                => 'Unnerscheed¹:',
'servertime'                    => 'Tied ap dän Server:',
'guesstimezone'                 => 'Ienföigje uut dän Browser',
'timezoneregion-africa'         => 'Afrikoa',
'timezoneregion-america'        => 'Amerikoa',
'timezoneregion-antarctica'     => 'Antarktis',
'timezoneregion-arctic'         => 'Arktis',
'timezoneregion-asia'           => 'Asien',
'timezoneregion-atlantic'       => 'Atlantisken Ozeoan',
'timezoneregion-australia'      => 'Australien',
'timezoneregion-europe'         => 'Europa',
'timezoneregion-indian'         => 'Indisken Ozeoan',
'timezoneregion-pacific'        => 'Pazifisken Ozeoan',
'allowemail'                    => 'Emails fon uur Benutsere kriegen',
'prefs-searchoptions'           => 'Säikoptione',
'prefs-namespaces'              => 'Noomensruume',
'defaultns'                     => 'Uursiede in disse Noomensruume säike:',
'default'                       => 'Standoardienstaalenge',
'prefs-files'                   => 'Doatäie',
'prefs-custom-css'              => 'Benutserdefinierde CSS',
'prefs-custom-js'               => 'Benutserdefinierd JS',
'prefs-common-css-js'           => 'Gemeensoam CSS/JS foar aal Skins:',
'prefs-reset-intro'             => 'Du koast disse Siede bruuke, uum do Ienstaalengen ap do Standoarde touräächtousätten.
Dät kon nit moor tourääch troald wäide.',
'prefs-emailconfirm-label'      => 'E-Mail-Bestäätigenge:',
'prefs-textboxsize'             => 'Grööte fon dät Beoarbaidengsfinster',
'youremail'                     => 'E-Mail-Adrässe:',
'username'                      => 'Benutsernoome:',
'uid'                           => 'Benutser-ID:',
'prefs-memberingroups'          => 'Meeglid fon {{PLURAL:$1|ju Benutzergruppe|do Benutzergruppen}}:',
'prefs-registration'            => 'Anmäldetiedpunkt:',
'yourrealname'                  => 'Dien ächte Noome:',
'yourlanguage'                  => 'Sproake fon ju Benutser-Uurfläche:',
'yourvariant'                   => 'Variante:',
'yournick'                      => 'Unnerschrift:',
'prefs-help-signature'          => 'Biedraage ap Diskussionssieden schuulen mäd „<nowiki>~~~~</nowiki>“ signierd wäide, wät dan in dien Signatuur mäd Tiedstämpel uumewondeld wäd.',
'badsig'                        => 'Signatursyntax is uungultich; HTML uurpröiwje.',
'badsiglength'                  => 'Ju Unnerschrift is tou loang.
Ju duur maximoal $1 {{PLURAL:$1|Teeken|Teekene}} loang weese.',
'yourgender'                    => 'Geslächt:',
'gender-unknown'                => 'Nit anroat',
'gender-male'                   => 'Monnelk',
'gender-female'                 => 'Wieuwelk',
'prefs-help-gender'             => 'Optionoal: Wäd foar ju geslächtskorrekte Anrääde fonsieden ju Software benutsed. Disse Information is eepentelk.',
'email'                         => 'E-Mail',
'prefs-help-realname'           => 'Optional. Foar dät anärkaanende Naamen fon dien Noome in Touhoopehong mäd dien Biedraagen.',
'prefs-help-email'              => 'Ju Angoawe fon ne E-Mail-Adresse is optionoal, moaket oawers je Touseendenge muugelk fon n Ärsats-Paaswoud, wan du dien Paaswoud ferjeeten hääst.
Mäd uur Benutsere koast du uk uur do Benutserdiskussionssieden Kontakt apnieme, sunner dät du dien Identität eepenlääse hougest.',
'prefs-help-email-required'     => 'N gultige Email-Adrässe is nöödich.',
'prefs-info'                    => 'Basisinformatione',
'prefs-i18n'                    => 'Internationalisierenge',
'prefs-signature'               => 'Unnerschrift:',
'prefs-dateformat'              => 'Doatumsformoat',
'prefs-timeoffset'              => 'Tiedunnerscheed',
'prefs-advancedediting'         => 'Uutwiedede Optione',
'prefs-advancedrc'              => 'Uutwiedede Optione',
'prefs-advancedrendering'       => 'Uutwiedede Optione',
'prefs-advancedsearchoptions'   => 'Uutwiedede Optione',
'prefs-advancedwatchlist'       => 'Uutwiedede Optione',
'prefs-displayrc'               => 'Anwies-Optione',
'prefs-diffs'                   => 'Versionsfergliek',

# User rights
'userrights'                  => 'Benutsergjuchteferwaltenge',
'userrights-lookup-user'      => 'Ferwaltede Gruppentouheeregaid',
'userrights-user-editname'    => 'Benutsernoome anreeke:',
'editusergroup'               => 'Beoarbaidede Benutsergjuchte',
'editinguser'                 => "Uur Benutsergjuchte fon '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'    => 'Beoarbaidje Gruppentouheeregaid fon dän Benutser',
'saveusergroups'              => 'Spiekerje Gruppentouheeregaid',
'userrights-groupsmember'     => 'Meeglid fon:',
'userrights-groups-help'      => 'Du koast ju Gruppentouheeregaid foar dissen Benutser annerje:
* Aan markierde Kasten betjut, dät die Benutser Meeglid fon disse Gruppe is
* Aan * betjut, dät du dät Benutsergjucht ätter Oureeken nit wier touräächnieme koast (of uumekierd).',
'userrights-reason'           => 'Gruund:',
'userrights-no-interwiki'     => 'Du hääst neen Begjuchtigenge, do Benutsergjuchte in uur Wikis tou annerjen.',
'userrights-nodatabase'       => 'Ju Doatenboank $1 is nit deer of nit lokoal.',
'userrights-nologin'          => 'Du moast die mäd n Administrator-Benutserkonto [[Special:UserLogin|anmäldje]], uum Benutsergjuchte tou annerjen.',
'userrights-notallowed'       => 'Du hääst neen Begjuchtigenge, uum Benutsergjuchte tou reeken.',
'userrights-changeable-col'   => 'Gruppentouheeregaid, ju du annerje koast',
'userrights-unchangeable-col' => 'Gruppentouheeregaid, ju du nit annerje koast',

# Groups
'group'               => 'Gruppe:',
'group-user'          => 'Benutsere',
'group-autoconfirmed' => 'Bestäätigede Benutsere',
'group-bot'           => 'Bots',
'group-sysop'         => 'Administratore',
'group-bureaucrat'    => 'Bürokraten',
'group-suppress'      => 'Uursichte',
'group-all'           => '(aal)',

'group-user-member'          => 'Benutser',
'group-autoconfirmed-member' => 'Bestäätigede Benutser',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Administrator',
'group-bureaucrat-member'    => 'Bürokrat',
'group-suppress-member'      => 'Uursicht',

'grouppage-user'          => '{{ns:project}}:Benutsere',
'grouppage-autoconfirmed' => '{{ns:project}}:Bestäätigede Benutser',
'grouppage-bot'           => '{{ns:project}}:Bots',
'grouppage-sysop'         => '{{ns:project}}:Administratore',
'grouppage-bureaucrat'    => '{{ns:project}}:Bürokraten',
'grouppage-suppress'      => '{{ns:project}}:Uursicht',

# Rights
'right-read'                  => 'Sieden leese',
'right-edit'                  => 'Sieden beoarbaidje',
'right-createpage'            => 'Sieden moakje (buute Diskussionssieden)',
'right-createtalk'            => 'Diskussionssieden moakje',
'right-createaccount'         => 'Benutserkonto moakje',
'right-minoredit'             => 'Beoarbaidengen as littik markierje',
'right-move'                  => 'Sieden ferschuuwe',
'right-move-subpages'         => 'Sieden touhoope mäd Unnersieden ferschuuwe',
'right-move-rootuserpages'    => 'Haud-Benutsersieden ferschuuwe',
'right-movefile'              => 'Doatäie ferschuuwe',
'right-suppressredirect'      => 'Bie dät Ferschuuwen dät Moakjen fon ne Fäärelaitenge unnerdrukke',
'right-upload'                => 'Doatäie hoochleede',
'right-reupload'              => 'Uurschrieuwen fon ne bestoundene Doatäi',
'right-reupload-own'          => 'Uurschrieuwen fon ne foartied sälwen hoochleedene Doatäi',
'right-reupload-shared'       => 'Lokoal Uurschrieuwen fon ne Doatäi in dät gemeensoam bruukte Repositorium',
'right-upload_by_url'         => 'Hoochleeden fon ne URL-Adresse',
'right-purge'                 => 'Siedencache loosmoakje sunner Fräigjen wierätter',
'right-autoconfirmed'         => 'Hoolichschutsede Sieden beoarbaidje',
'right-bot'                   => 'Behondlenge as automatisk Prozess',
'right-nominornewtalk'        => 'Litje Beoarbaidengen an Diskussionssieden fiere tou neen "Näie Ättergjuchten"-Anwiesenge',
'right-apihighlimits'         => 'Haagere Limite in API-Oufroagen',
'right-writeapi'              => 'Benutsenge fon ju writeAPI',
'right-delete'                => 'Sieden läskje',
'right-bigdelete'             => 'Sieden läskje mäd groote Versionsgeschichte',
'right-deleterevision'        => 'Läskjen un Wierhäärstaalen fon eenpelde Versione',
'right-deletedhistory'        => 'Ankiekjen fon läskede Versione in ju Versionsgeschichte (sunner touheerigen Text)',
'right-deletedtext'           => 'Läskede Texte un Versionsunnerscheede twiske läskede Versione bekiekje',
'right-browsearchive'         => 'Säik ätter läskede Sieden',
'right-undelete'              => 'Sieden wierhäärstaale',
'right-suppressrevision'      => 'Bekiekjen un wierhäärstaalen fon Versione, do uk foar Adminstratore ferbuurgen sunt',
'right-suppressionlog'        => 'Bekiekjen fon privoate Logbouke',
'right-block'                 => 'Benutsere speere (Schrieuwgjucht)',
'right-blockemail'            => 'Benutser an dät Ferseenden fon E-mails hinnerje',
'right-hideuser'              => 'Speer un ferbiergje n Benutsernoome',
'right-ipblock-exempt'        => 'Uutnoame fon IP-Speeren, Autoblocks un Rangespeeren',
'right-proxyunbannable'       => 'Uutnoame fon automatiske Proxyspeeren',
'right-protect'               => 'Siedenschutsstatus annerje',
'right-editprotected'         => 'Schutsede Sieden beoarbaidje (sunner Kaskadenschuts)',
'right-editinterface'         => 'Benutserinterface beoarbaidje',
'right-editusercssjs'         => 'Beoarbaidjen fon CSS- un JS-Doatäie fon uur Benutsere',
'right-editusercss'           => 'Beoarbaidjen fon CSS-Doatäie fon uur Benutsere',
'right-edituserjs'            => 'Beoarbaidjen fon JS-Doatäie fon uur Benutsere',
'right-rollback'              => 'Gau räägels Traalen',
'right-markbotedits'          => 'Gau räägels troalde Beoarbaidengen as Bot-Beoarbaidenge markierje',
'right-noratelimit'           => 'Neen Beschränkenge truch Limite',
'right-import'                => 'Import fon Sieden uut uur Wikis',
'right-importupload'          => 'Import fon Sieden uur Doatäihoochleeden',
'right-patrol'                => 'Markier froamde Beoarbaidengen as kontrollierd',
'right-autopatrol'            => 'Markier oaine Beoarbaidengen automatisk as kontrollierd',
'right-patrolmarks'           => 'Ankiekjen fon do Kontrolmarkierengen in do lääste Annerengen',
'right-unwatchedpages'        => 'Bekiekje ju Lieste fon nit beooboachtede Sieden',
'right-trackback'             => 'Trackback fermiddelje',
'right-mergehistory'          => 'Versionsgeschichten fon Sieden touhoopeföigje',
'right-userrights'            => 'Benutsergjuchte beoarbaidje',
'right-userrights-interwiki'  => 'Benutsergjuchte in uur Wikis beoarbaidje',
'right-siteadmin'             => 'Doatenboank speere un äntspeere',
'right-reset-passwords'       => 'Dät Paaswoud fon n uur Benutser touräächsätte',
'right-override-export-depth' => 'Exportier Sieden touhoope mäd ferlinkede Sieden bit tou ne Djüpte fon 5',
'right-sendemail'             => 'E-Mails an uur Benutsere seende',

# User rights log
'rightslog'      => 'Gjuchte-Logbouk',
'rightslogtext'  => 'Dit is dät Logbouk fon do Annerengen fon do Benutsergjuchte.',
'rightslogentry' => 'annerde ju Gruppentouheeregaid foar „$1“ fon „$2“ ap „$3“.',
'rightsnone'     => '(-)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'disse Siede tou leesen',
'action-edit'                 => 'ju Siede tou beoarbaidjen',
'action-createpage'           => 'Sieden tou moakjen',
'action-createtalk'           => 'Diskussionssieden tou moakjen',
'action-createaccount'        => 'n Benutserkonto tou moakjen',
'action-minoredit'            => 'disse Beoarbaidenge as littik tou markierjen',
'action-move'                 => 'ju Siede tou ferschuuwen',
'action-move-subpages'        => 'disse Siede un touheerige Unnersieden tou ferschuuwen',
'action-move-rootuserpages'   => 'Haud-Benutsersieden ferschuuwe',
'action-movefile'             => 'Disse Doatäi ferschuuwe',
'action-upload'               => 'Doatäie hoochtouleeden',
'action-reupload'             => 'ju foarhoundene Doatäi tou uurschrieuwen',
'action-reupload-shared'      => 'disse Doatäi uut dät gemeensoam nutsede Repositorium tou uurschrieuwen',
'action-upload_by_url'        => 'Doatäie fon ne Webadresse (URL) hoochtouleeden',
'action-writeapi'             => 'ju API mäd Schrieuwtougriepe tou ferweenden',
'action-delete'               => 'Sieden tou läskjen',
'action-deleterevision'       => 'Versione tou läskjen',
'action-deletedhistory'       => 'Lieste fon do läskede Versione tou bekiekjen',
'action-browsearchive'        => 'ätter läskede Sieden tou säiken',
'action-undelete'             => 'ju Siede wier häärtoustaalen',
'action-suppressrevision'     => 'ju ferstoppede Version tou bekiekjen un wier häärtoustaalen',
'action-suppressionlog'       => 'dät privoate Logbouk ientoukiekjen',
'action-block'                => 'dän Benutser tou speeren',
'action-protect'              => 'dän Schutsstatus fon Sieden tou annerjen',
'action-import'               => 'Sieden uut n uur Wiki tou importierjen',
'action-importupload'         => 'Sieden uur dät Hoochleeden fon ne Doatäi tou importierjen',
'action-patrol'               => 'do Beoarbaidengen fon uur Benutsere tou kontrollierjen',
'action-autopatrol'           => 'oaine Beoarbaidengen as kontrollierd tou markierjen',
'action-unwatchedpages'       => 'ju Lieste fon uunbeoboachtede Sieden tou bekiekjen',
'action-trackback'            => 'n Trackback tou uurdreegen',
'action-mergehistory'         => 'do Versionegeschichten fon Sieden tou fereenigjen',
'action-userrights'           => 'Benutsergjuchte tou annerjen',
'action-userrights-interwiki' => 'do Gjuchte fon Benutsere in uur Wikis annerje',
'action-siteadmin'            => 'ju Doatenboank tou speeren of fräitoureeken',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|Annerenge|Annerengen}}',
'recentchanges'                     => 'Lääste Annerengen',
'recentchanges-legend'              => 'Anwiesoptione',
'recentchangestext'                 => "Ap disse Siede koast du do lääste Annerengen ap '''{{SITENAME}}''' ättergunge.",
'recentchanges-feed-description'    => 'Ferfoulge mäd dissen Feed do lääste Annerengen in {{SITENAME}}.',
'recentchanges-label-newpage'       => 'Näie Siede',
'recentchanges-label-minor'         => 'Litje Annerenge',
'recentchanges-label-bot'           => 'Annerenge truch n Bot',
'recentchanges-label-unpatrolled'   => 'Nit-kontrollierde Annerenge',
'rcnote'                            => "Anwiesd {{PLURAL:$1|wäd '''1''' Annerenge|wäide do lääste '''$1''' Annerengen}} in {{PLURAL:$2|dän lääste Dai|do lääste '''$2''' Deege}} siet $5, $4.",
'rcnotefrom'                        => "Anwiesd wäide do Annerengen siet '''$2''' (max. '''$1''' Iendraage).",
'rclistfrom'                        => 'Bloot näie Annerengen siet $1 wiese.',
'rcshowhideminor'                   => 'Litje Annerengen $1',
'rcshowhidebots'                    => 'Bots $1',
'rcshowhideliu'                     => 'Anmäldede Benutsere $1',
'rcshowhideanons'                   => 'Anonyme Benutsere $1',
'rcshowhidepatr'                    => 'Pröiwede Annerengen $1',
'rcshowhidemine'                    => 'Oaine Biedraage $1',
'rclinks'                           => 'Wies do lääste $1 Annerengen; wies do lääste $2 Deege.<br />$3',
'diff'                              => 'Unnerschied',
'hist'                              => 'Versione',
'hide'                              => 'ferbierge',
'show'                              => 'ienbländje',
'minoreditletter'                   => 'L',
'newpageletter'                     => 'Näi',
'boteditletter'                     => 'B',
'number_of_watching_users_pageview' => '[$1 beooboachtjende {{PLURAL:$1|Benutser|Benutsere}}]',
'rc_categories'                     => 'Bloot Sieden uut do Kategorien (tränd mäd „|“):',
'rc_categories_any'                 => 'Aal',
'rc-change-size'                    => '$1 {{PLURAL:$1|Byte|Bytes}}',
'newsectionsummary'                 => 'Näie Apsats /* $1 */',
'rc-enhanced-expand'                => 'Details anwiese (bruukt JavaScript)',
'rc-enhanced-hide'                  => 'Details fersteete',

# Recent changes linked
'recentchangeslinked'          => 'Annerengen an ferlinkede Sieden',
'recentchangeslinked-feed'     => 'Annerengen an ferlinkede Sieden',
'recentchangeslinked-toolbox'  => 'Annerengen an ferlinkede Sieden',
'recentchangeslinked-title'    => 'Annerengen an Sieden, do der fon „$1“ ferbuunden sunt',
'recentchangeslinked-noresult' => 'In dän uutwäälde Tiedruum wuuden an do ferbuundene Sieden neen Annerengen foarnuumen.',
'recentchangeslinked-summary'  => "Disse Spezioalsiede liestet do lääste Annerengen fon ferbuundene Sieden ap (blw. bie Kategorien an do Meegliedere fon disse Kategorie). Sieden ap dien Beooboachtengslieste sunt '''fat''' schrieuwen.",
'recentchangeslinked-page'     => 'Siede:',
'recentchangeslinked-to'       => 'Wies Annerengen ap Sieden, do hierhäär ferlinkje',

# Upload
'upload'                      => 'Hoochleede',
'uploadbtn'                   => 'Doatäi hoochleede',
'reuploaddesc'                => 'Oubreeke un tourääch tou ju Hoochleede-Siede.',
'upload-tryagain'             => 'Annerde Doatäibeschrieuwenge ouseende',
'uploadnologin'               => 'Nit anmälded',
'uploadnologintext'           => 'Du moast [[Special:UserLogin|anmälded weese]], uum Doatäie hoochleede tou konnen.',
'upload_directory_missing'    => 'Dät Upload-Ferteeknis ($1) failt un kuud truch dän Webserver uk nit moaked wäide.',
'upload_directory_read_only'  => 'Die Webserver häd neen Schrieuwgjuchte foar dät Upload-Ferteeknis ($1).',
'uploaderror'                 => 'Failer bie dät Hoochleeden',
'uploadtext'                  => "Bruuk dit Formular uum näie Doatäie hoochtouleeden.

Gung tou ju [[Special:FileList|Lieste fon hoochleedene Doatäie]], uum foarhoundene Doatäie tou säiken un antouwiesen. Sjuch uk dät [[Special:Log/upload|Doatäi-]] un [[Special:Log/upload|Läsk-Logbouk]].

Klik ap '''„Truchsäike …“''', uum n Doatäiuutwoal-Dialog tou eepenjen.
Ätter dän Uutwoal fon ne Doatäi wäd die Doatäinoome in dät Textfäild '''„Wäldoatäi“''' anwiesd.
Bestäätigje dan ju Lizenz-Fereenboarenge un klik deerätter ap '''„Doatäi hoochleede“'''.
Dit kon n Schoft duurje, besunners bie ne loangsomme Internet-Ferbiendenge.

Uum ne '''Bielde''' in ne Siede tou ferweenden, schrieuw an Steede fon ju Bielde toun Biespil:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}:Doatäi.jpg<nowiki>]]</nowiki></tt>'''
* '''<tt><nowiki>[[</nowiki>{{ns:file}}:Doatäi.jpg|Link-Text<nowiki>]]</nowiki></tt>'''

Uum '''Mediendoatäie''' ientoubienden, ferweende toun Biespil:
* '''<tt><nowiki>[[</nowiki>{{ns:media}}:Doatäi.ogg<nowiki>]]</nowiki></tt>'''
* '''<tt><nowiki>[[</nowiki>{{ns:media}}:Doatäi.ogg|Link-Text<nowiki>]]</nowiki></tt>'''

Beoachtje, dät, juust as bie normoale Sieden-Inhoolde, uur Benutsere dien Doatäie läskje of annerje konnen.",
'upload-permitted'            => 'Ferlööwede Doatäitypen: $1.',
'upload-preferred'            => 'Ljoost antouweenden Doatäitypen: $1.',
'upload-prohibited'           => 'Nit ferlööwede Doatäitypen: $1.',
'uploadlog'                   => 'Doatäi-Logbouk',
'uploadlogpage'               => 'Doatäi-Logbouk',
'uploadlogpagetext'           => 'Dit is dät Logbouk fon do hoochleedene Doatäie, sjuch uk ju [[Special:NewFiles|Galerie fon näie Doatäie]] foar n visuellen Uurblik.',
'filename'                    => 'Doatäinoome',
'filedesc'                    => 'Beschrieuwenge, Wälle',
'fileuploadsummary'           => 'Beschrieuwenge/Wälle:',
'filereuploadsummary'         => 'Doatäi-Annerengen:',
'filestatus'                  => 'Copyright-Stoatus:',
'filesource'                  => 'Wälle:',
'uploadedfiles'               => 'Hoochleedene Doatäie',
'ignorewarning'               => 'Woarschauenge ignorierje un Doatäi daach spiekerje',
'ignorewarnings'              => 'Woarschauengen ignorierje',
'minlength1'                  => 'Bieldedoatäien mouten mindestens tjoo Bouksteeuwen foar dän (eersten) Punkt hääbe.',
'illegalfilename'             => 'Die Doatäinoome "$1" änthaalt ap minste een nit toulät Teeken. Benaam jädden ju Doatäi uum un fersäik, hier fon näien hoochtouleeden.',
'badfilename'                 => 'Die Datäi-Noome is automatisk annerd tou "$1".',
'filetype-mime-mismatch'      => 'Doatäi-Fergratterenge stimt nit mäd dän MIME-Typ uureen.',
'filetype-badmime'            => 'Doatäie mäd dän MIME-Typ „$1“ duuren nit hoochleeden wäide.',
'filetype-bad-ie-mime'        => 'Disse Doatäi kon nit hoochleeden wäide, deer die Internet Explorer ju as „$1“ ärkoant, wät n nit ferlööweden potentiell gefoarelken Doatäityp is.',
'filetype-unwanted-type'      => "'''„.$1“''' is n nit wonsked Doateiformoat.
{{PLURAL:$3|Ferlööwed Doatäiformoat is|Ferlööwede Doatäiformoate sunt}} $2.",
'filetype-banned-type'        => "'''„.$1“''' is n nit ferlööwed Doatäiformoat.
Ferlööwed {{PLURAL:$3|is|sunt}} $2.",
'filetype-missing'            => 'Ju hoochtouleedende Doatäi häd neen Fergratterenge (t. B. „.jpg“).',
'large-file'                  => 'Jädden neen Bielde uur $1 hoochleede; disse Doatäi is $2 groot.',
'largefileserver'             => 'Disse Doatäi is tou groot, deer die Server so konfigurierd is, dät Doatäien bloot bit tou ne bestimde Grööte apzeptierd wäide.',
'emptyfile'                   => 'Ju hoochleedene Doatäi is loos. Die Gruund kon n Typfailer in dän Doatäinoome weese. Kontrollierje jädden, of du ju Doatäi wuddelk hoochleede wolt.',
'fileexists'                  => "Ne Doatäi mäd dissen Noome bestoant al.
Wan du ap 'Doatäi spiekerje' klikst, wäd ju Doatäi uurschrieuwen.
Unner '''<tt>[[:$1]]</tt>''' koast du die bewisje, of du dät wuddelk wolt.
[[$1|thumb]]",
'filepageexists'              => "Ju Beschrieuwengssiede foar disse Doatäi wuude al moaked as '''<tt>[[:$1]]</tt>''', man der bestoant neen Doatäi mäd dissen Noome.
Ju ienroate Beschrieuwenge wäd nit ap ju Beschrieuwengssiede uurnuumen.
Ju Beschrieuwengssiede moast du ätter dät Hoochleeden fon ju Doatäi noch mäd de Hounde beoarbaidje.
[[$1|thumb]]",
'fileexists-extension'        => "Een Doatäi mäd n äänelken Noome existiert al: [[$2|thumb]]
* Noome fon ju hoochtouleedende Doatäi: '''<tt>[[:$1]]</tt>'''
* Noome fon ju anweesende Doatäi: '''<tt>[[:$2]]</tt>'''
Wääl n uur Noome.",
'fileexists-thumbnail-yes'    => "Bie ju Doatäi schient et sik uum ne Bielde fon ferlitjerde Grööte ''(thumbnail)'' tou honneljen. [[$1|thumb]]
Pröif ju Doatäi '''<tt>[[:$1]]</tt>'''.
Wan et sik uum ju Bielde in Originoalgrööte honnelt, dan houget neen apaate Foarschaubielde hoochleeden tou wäiden.",
'file-thumbnail-no'           => "Die Doatäinoome begint mäd '''<tt>$1</tt>'''. Dit tjut ap ne Bielde fon ferlitjerde Grööte ''(thumbnail)'' wai.
Pröif, of du ju Bielde in fulle Aplöösenge foarlääsen hääst un leed ju unner dän Originoalnoome hooch. Uurs annerje dän Doatäinoome.",
'fileexists-forbidden'        => 'Mäd dissen Noome bestoant al ne Doatäi un ju kon nit uurschieuwen wäide. Gung jädden tourääch un leede dien Doatäi unner n uur Noome hooch. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Mäd dissen Noome bestoant al ne Doatäi in dät zentroale Medienarchiv.
Wan du ju Doatäi daach hoochleede moatest, gung dan tourääch un leed dien Doatäi unner n uur Noome hooch. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Disse Doatäi is n Duplikoat fon foulgjende {{PLURAL:$1|Doatäi|$1 Doatäie}}:',
'file-deleted-duplicate'      => 'Ne identiske Doatäi fon disse Doatäi ([[$1]]) wuud fröier läsked. Wröich dät Läsklogbouk, eer du ju hoochlatst.',
'uploadwarning'               => 'Woarschauenge',
'uploadwarning-text'          => 'Annerje hierunner ju Doatäibeschrieuwenge un fersäik dät nochmoal.',
'savefile'                    => 'Doatäi spiekerje',
'uploadedimage'               => '"[[$1]]" hoochleeden',
'overwroteimage'              => 'häd ne näie Version fon „[[$1]]“ hoochleeden',
'uploaddisabled'              => 'Äntscheeldigenge, dät Hoochleeden is apstuuns deaktivierd.',
'uploaddisabledtext'          => 'Dät Hoochleeden fon Doatäie is nit muugelk.',
'php-uploaddisabledtext'      => 'PHP-Doatäi-Uploads wuuden deaktivierd. Wröich ju file_uploads-Ienstaalenge.',
'uploadscripted'              => 'Disse Doatäi änthaalt HTML- of Scriptcode, ju bie Fersjoon fon aan Webbrowser apfierd wäide kuude.',
'uploadvirus'                 => 'Disse Doatäi änthaalt n Virus! Details: $1',
'upload-source'               => 'Wälledoatäi',
'sourcefilename'              => 'Wälledoatäi:',
'sourceurl'                   => 'Wälle-URL:',
'destfilename'                => 'Sielnoome:',
'upload-maxfilesize'          => 'Maximoale Doatäigrööte: $1',
'upload-description'          => 'Doatäibeschrieuwenge',
'upload-options'              => 'Hoochleede-Optione',
'watchthisupload'             => 'Disse Doatäi beooboachtje',
'filewasdeleted'              => 'Ne Doatäi mäd dissen Noome wuude al moal hoochleeden un intwisken wier läsked. Pröif toueerst dän Iendraach in $1, eer du ju Doatäi wuddelk spiekerst.',
'upload-wasdeleted'           => "'''Woarschauenge: Du laatst ne Doatäi hooch, ju der al fröier läsked wuude.'''

Pröif suurgfooldich, of dät fernäide Hoochleeden do Gjuchtlienjen äntspräkt.
Tou Dien Information foulget dät Läsk-Logbouk mäd ju Begründenge foar ju foargungende Läskenge:",
'filename-bad-prefix'         => "Die Doatäinoome begint mäd '''„$1“'''. Dit is in algemeenen die fon ne Digitoalkamera foarroate Doatäinoome un deeruum nit gjucht uurtjuugend.
Reek ju Doatäi n Noome, die dän Inhoold beeter beschrift.",
'upload-success-subj'         => 'Mäd Ärfoulch hoochleeden',

'upload-proto-error'        => 'Falsk Protokol',
'upload-proto-error-text'   => 'Ju URL mout mäd <code>http://</code> of <code>ftp://</code> ounfange.',
'upload-file-error'         => 'Interne Failer',
'upload-file-error-text'    => 'Bie dät Moakjen fon ne tiedelke Doatäi ap dän Server is n internen Failer aptreeden. Informier jädden n [[Special:ListUsers/sysop|System-Administrator]].',
'upload-misc-error'         => 'Uunbekoanden Failer bie dät Hoochleeden',
'upload-misc-error-text'    => 'Bie dät Hoochleeden is n uunbekoanden Failer aptreeden.
Pröif ju URL ap Failere, dän Online-Stoatus fon ju Siede un fersäik et fonnäien.
Wan dät Problem fääre bestoant, informier n [[Special:ListUsers/sysop|System-Administrator]].',
'upload-too-many-redirects' => 'Ju URL äntheeld toufuul Fääreleedengen',
'upload-unknown-size'       => 'Uunbekoande Grööte',
'upload-http-error'         => 'N HTTP-Failer is aptreeden: $1',

# img_auth script messages
'img-auth-accessdenied' => 'Tougriep ferwäigerd',
'img-auth-nopathinfo'   => 'PATH_INFO failt.
Dien Server is nit deerfoar iengjucht, disse Information fääretoureeken.
Dät kuud CGI-basierd weese un unnerstutset img_auth nit.
Sjuch http://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-notindir'     => 'Dät wonskede Paad is nit in dät konfigurierde Uploadferteeknis.',
'img-auth-badtitle'     => 'Uut „$1“ kon naan gultigen Tittel moaked wäide.',
'img-auth-nologinnWL'   => 'Du bäst nit anmälded un „$1“ is nit in ju wiete Lieste.',
'img-auth-nofile'       => 'Doatäi „$1“ bestoant nit.',
'img-auth-isdir'        => 'Du fersäkst, ap n Ferteeknis „$1“ toutougriepen.
Bloot Doatäitougriep is ferlööwed.',
'img-auth-streaming'    => '„$1“ leede.',
'img-auth-public'       => 'img_auth.php rakt Doatäie fon ne privoate Wiki uut.
Dit Wiki wuud as n eepentelk Wiki konfigurierd.
Uut Sicherhaidsgruunde is img_auth.php deaktivierd.',
'img-auth-noread'       => 'Benutser häd neen Begjuchtigenge, „$1“ tou leesen.',

# HTTP errors
'http-invalid-url'      => 'Uungultige URL:$1',
'http-read-error'       => 'HTTP-Leesefailer.',
'http-timed-out'        => 'Tied is ferron bie ju HTTP-Anfroage.',
'http-host-unreachable' => 'URL is nit tou beloangjen',
'http-bad-status'       => 'Unner ju HTTP-Anfroage is n Failer aptreeden: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'URL is nit tou beloangjen',
'upload-curl-error6-text'  => 'Ju ounroate URL is nit tou beloangjen. Pröif ju URL ap Failere as uk dän Online-Stoatus fon ju Siede.',
'upload-curl-error28'      => 'Toufuul Tied nöödich foar dät Hoochleeden',
'upload-curl-error28-text' => 'Ju Siede bruukt tou loange foar ne Oantwoud. Pröif, of ju Siede online is, täif n kuuten Moment un fersäik et dan fonnäien. Dät kon sinful weese, n näien Fersäik tou ne uur Tied tou probierjen..',

'license'            => 'Lizenz:',
'license-header'     => 'Lizenz',
'nolicense'          => 'naan Foaruutwoal',
'license-nopreview'  => '(der is neen Foarschau ferföigboar)',
'upload_source_url'  => ' (gultige, eepentelk tougongelke URL)',
'upload_source_file' => ' (ne Doatäi ap Jou Computer)',

# Special:ListFiles
'listfiles-summary'     => 'Disse Spezialsiede liestet aal hoochleedene Doatäie ap. Standoardmäitich wäide do toulääst hoochleedene Doatäie toueerst anwiesd. Truch n Klik ap do Spaltenuurschrifte kon ju Sortierenge uumetroald wäide of der kon ätter ne uur Spalte sortierd wäide.',
'listfiles_search_for'  => 'Säik ätter Doatäi:',
'imgfile'               => 'Doatäi',
'listfiles'             => 'Bieldelieste',
'listfiles_date'        => 'Doatum',
'listfiles_name'        => 'Noome',
'listfiles_user'        => 'Benutser',
'listfiles_size'        => 'Grööte',
'listfiles_description' => 'Beschrieuwenge',
'listfiles_count'       => 'Versione',

# File description page
'file-anchor-link'          => 'Bielde',
'filehist'                  => 'Doatäiversione',
'filehist-help'             => 'Klik ap n Tiedpunkt, uum disse Version tou leeden.',
'filehist-deleteall'        => 'Aal do Versione läskje',
'filehist-deleteone'        => 'läskje',
'filehist-revert'           => 'touräächsätte',
'filehist-current'          => 'aktuäl',
'filehist-datetime'         => 'Version fon',
'filehist-thumb'            => 'Foarschaubielde',
'filehist-thumbtext'        => 'Foarschaubielde foar Version fon n $1',
'filehist-nothumb'          => 'Neen Foarschaubielde deer',
'filehist-user'             => 'Benutser',
'filehist-dimensions'       => 'Höchte un Bratte',
'filehist-filesize'         => 'Doatäigrööte',
'filehist-comment'          => 'Kommentoar',
'filehist-missing'          => 'Doatäi failt',
'imagelinks'                => 'Doatäiferweendengen',
'linkstoimage'              => '{{PLURAL:$1|Ju foulgjende Siede ferwoant|Do foulgjende $1 Sieden ferweende}} disse Doatäi:',
'linkstoimage-more'         => 'Moor as {{PLURAL:$1|een Siede ferlinket|$1 Sieden ferlinkje}} ap disse Doatäi.
Ju foulgjende Lieste wiest bloot {{PLURAL:$1|dän eerste Link|do eerste $1 Linke}} ap disse Doatäi.
Ne [[Special:WhatLinksHere/$2|fulständige Lieste]] is ferföigboar.',
'nolinkstoimage'            => 'Naan Artikkel benutset disse Bielde.',
'morelinkstoimage'          => '[[Special:WhatLinksHere/$1|Wiedere Ferbiendengen]] foar disse Doatäi.',
'redirectstofile'           => '{{PLURAL:$1|Ju foulgjende Doatäi laitet|Do foulgjende $1 Doatäie laitje}} ap disse Doatäi fääre:',
'duplicatesoffile'          => '{{PLURAL:$1|Ju foulgjende Doatäi is n Duplikoat|Do foulgjende $1 Doatäie sunt Duplikoate}} fon disse Doatäi ([[Special:FileDuplicateSearch/$2|wiedere Details]]):',
'sharedupload'              => 'Disse Doatäi stamt uut $1 un duur fon uur Projekte ferwoand wäide.',
'sharedupload-desc-there'   => 'Disse Doatäi stamt uut $1 un duur fon uur Projekte ferwoand wäide. Sjuch ap ju [$2 Doatäibeschrieuwengssiede] ätter wiedere Informatione.',
'sharedupload-desc-here'    => 'Disse Doatäi stamt uut $1 un duur fon uur Projekte ferwoand wäide. Ju Beschrieuwenge fon ju [$2 Doatäibeschrieuwengssiede] wäd hierunner anwiesd.',
'filepage-nofile'           => 'Der bestoant neen Doatäi mäd dissen Noome.',
'filepage-nofile-link'      => 'Der bestoant neen Doatäi mäd dissen Noome, man du koast [$1 disse Doatäi hoochleede].',
'uploadnewversion-linktext' => 'Ne näie Version fon disse Doatäi hoochleede',
'shared-repo-from'          => 'uut $1',
'shared-repo'               => 'n gemeensoam nutsed Medienarchiv',

# File reversion
'filerevert'                => 'Touräächsätte fon "$1"',
'filerevert-legend'         => 'Doatäi touräächsätte',
'filerevert-intro'          => "Du sätst ju Doatäi '''[[Media:$1|$1]]''' ap ju [$4 Version fon $2, $3 Uure] tourääch.",
'filerevert-comment'        => 'Kommentoar:',
'filerevert-defaultcomment' => 'touräächsät ap ju Version fon $1, $2 Uure',
'filerevert-submit'         => 'Touräächsätte',
'filerevert-success'        => "'''[[Media:$1|$1]]''' wuud ap ju [$4 Version fon $2, $3 Uure] touräächsät.",
'filerevert-badversion'     => 'Et rakt neen Version fon ju Doatäi tou dän ounroate Tiedpunkt.',

# File deletion
'filedelete'                  => 'Läskje "$1"',
'filedelete-legend'           => 'Läskje Doatäi',
'filedelete-intro'            => "Du läskest ju Doatäi '''„[[Media:$1|$1]]“''' touhoope mäd hiere Versionsgeschichte.",
'filedelete-intro-old'        => "Du läskest fon ju Doatäi '''„[[Media:$1|$1]]“''' ju [$4 Version fon $2, $3 Uur].",
'filedelete-comment'          => 'Gruund:',
'filedelete-submit'           => 'Läskje',
'filedelete-success'          => "'''\"\$1\"''' wuude läsked.",
'filedelete-success-old'      => '<span class="plainlinks">Fon ju Doatäi \'\'\'„[[Media:$1|$1]]“\'\'\' wuud ju Version $2, $3 Uure läsked.</span>',
'filedelete-nofile'           => "'''„$1“''' is nit deer.",
'filedelete-nofile-old'       => "Et rakt neen archivierde Version fon '''$1''' mäd do spezifizierde Määrkmoale.",
'filedelete-otherreason'      => 'Uur/touföigeden Gruund:',
'filedelete-reason-otherlist' => 'Uur Gruund',
'filedelete-reason-dropdown'  => '* Algemeene Läskgruunde
** Urhebergjuchtsferlätsenge
** Duplikoat',
'filedelete-edit-reasonlist'  => 'Läskgruunde beoarbaidje',
'filedelete-maintenance'      => 'Dät Läskjen un Wierhäärstaalen fon Doatäie is apgruund fon Oarbaiden apstuuns deaktivierd.',

# MIME search
'mimesearch'         => 'Säike ätter MIME-Typ',
'mimesearch-summary' => 'Ap disse Spezialsiede konnen do Doatäie ätter dän MIME-Typ filterd wäide. Ju Iengoawe mout immer dän Medien- un Subtyp beienhoolde: <tt>image/jpeg</tt> (sjuch Bieldbeschrieuwengssiede).',
'mimetype'           => 'MIME-Typ:',
'download'           => 'Deelleede',

# Unwatched pages
'unwatchedpages' => 'Nit beooboachtede Sieden',

# List redirects
'listredirects' => 'Lieste fon Fäärelaitengs-Sieden',

# Unused templates
'unusedtemplates'     => 'Nit benutsede Foarloagen',
'unusedtemplatestext' => 'Disse Siede liestet aal Sieden in dän {{ns:template}}-Noomensruum ap, do der nit bruukt wuuden sunt in uur Sieden. Pröif uur Ferbiendengen tou do Foarloagen, eer du do läskest.',
'unusedtemplateswlh'  => 'Uur Ferbiendengen',

# Random page
'randompage'         => 'Toufällige Siede',
'randompage-nopages' => 'Der sunt neen Sieden in {{PLURAL:$2|dän foulgjende Noomensruum|do foulgjende Noomensruume}} äntheelden: „$1“',

# Random redirect
'randomredirect'         => 'Toufällige Fäärelaitenge',
'randomredirect-nopages' => 'In dän Noomensruum "$1" sunt neen Fääreleedengen deer.',

# Statistics
'statistics'                   => 'Statistik',
'statistics-header-pages'      => 'Siedenstatistik',
'statistics-header-edits'      => 'Beoarbaidengsstatistik',
'statistics-header-views'      => 'Siedenaproupstatistik',
'statistics-header-users'      => 'Benutserstatistik',
'statistics-header-hooks'      => 'Uur Statistike',
'statistics-articles'          => 'Inhooldssieden',
'statistics-pages'             => 'Sieden',
'statistics-pages-desc'        => 'Aal Sieden in dissen Wiki, iensluutend Diskussionssieden, Fäärelaitengen usw.',
'statistics-files'             => 'Hoochleedene Doatäie',
'statistics-edits'             => 'Siedenbeoarbaidengen siet {{SITENAME}} waas ounfangd',
'statistics-edits-average'     => 'Beoarbaidengen pro Siede in n Truchsleek',
'statistics-views-total'       => 'Siedenaproupe mädnunner',
'statistics-views-peredit'     => 'Siedenaproupe pro Beoarbaidenge',
'statistics-users'             => 'Registrierde [[Special:ListUsers|Benutsere]]',
'statistics-users-active'      => 'Aktive Benutsere',
'statistics-users-active-desc' => 'Benutsere mäd Beoarbaidengen {{PLURAL:$1|in do lääste 24 Uuren|in do fergeene $1 Deege}}',
'statistics-mostpopular'       => 'Maast besoachte Sieden',

'disambiguations'      => 'Begriepskläärengssieden',
'disambiguationspage'  => 'Template:Begriepskläärenge',
'disambiguations-text' => "Do foulgjende Sieden ferlinkje ap ne Siede tou ju '''Begriepskläärenge'''.
Jie schuulen insteede deerfon ap ju eegentelk meende Siede ferlinkje.<br />
Ne Siede wäd as Begriepskläärengssiede behonneld, wan [[MediaWiki:Disambiguationspage]] ap ju ferlinket.",

'doubleredirects'            => 'Dubbelde Fäärelaitengen',
'doubleredirectstext'        => 'Disse Lieste änthoalt Fääreleedengen, do der ap wiedere Fääreleedengen ferlinkje.
Älke Riege änthoalt Links tou ju eerste un twäide Fääreleedenge as uk dät Siel fon ju twäide Fääreleedenge, wät foar gewöönelk ju wonskede Sielsiede is, ap ju al ju eerste Fääreleedenge wiese schuul.
<del>Truchstriekene</del> Iendraage wuuden al oumoaked.',
'double-redirect-fixed-move' => 'dubbelde Fäärelaitenge aplöösd: [[$1]] → [[$2]]',
'double-redirect-fixer'      => 'RedirectBot',

'brokenredirects'        => 'Ferkierde Fäärelaitengen',
'brokenredirectstext'    => 'Disse Spezioalsiede liestet Truchferwiese ap nit existierjende Sieden:',
'brokenredirects-edit'   => 'beoarbaidje',
'brokenredirects-delete' => 'läskje',

'withoutinterwiki'         => 'Sieden sunner Ferbiendengen tou uur Sproaken',
'withoutinterwiki-summary' => 'Do foulgjende Sieden ferlinkje nit ap uur Sproakversionen:',
'withoutinterwiki-legend'  => 'Präfix',
'withoutinterwiki-submit'  => 'Wies',

'fewestrevisions' => 'Sieden mäd do minste Versione',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|Byte|Bytes}}',
'ncategories'             => '$1 {{PLURAL:$1|Kategorie|Kategorien}}',
'nlinks'                  => '{{PLURAL:$1|1 Ferbiendenge|$1 Ferbiendengen}}',
'nmembers'                => '{{PLURAL:$1|1 Iendraach|$1 Iendraage}}',
'nrevisions'              => '{{PLURAL:$1|1 Beoarbaideng|$1 Beoarbaidengen}}',
'nviews'                  => '{{PLURAL:$1|1 Oufroage|$1 Oufroagen}}',
'specialpage-empty'       => 'Ju Siede änthaalt aktuell neen Iendraage.',
'lonelypages'             => 'Ferwaisde Sieden',
'lonelypagestext'         => 'Do foulgjende Sieden sunt nit ferlinked of ienbuunen fon uur Sieden in {{SITENAME}}.',
'uncategorizedpages'      => 'Nit kategorisierde Sieden',
'uncategorizedcategories' => 'Nit kategorisierde Kategorien',
'uncategorizedimages'     => 'Nit kategorisierde Doatäie',
'uncategorizedtemplates'  => 'Nit kategorisierde Foarloagen',
'unusedcategories'        => 'Ferwaisede Kategorien',
'unusedimages'            => 'Ferwaisede Bielden',
'popularpages'            => 'Sieden do oafte bekieked wäide',
'wantedcategories'        => 'Benutsede, man nit anlaide Kategorien',
'wantedpages'             => 'Wonskede Sieden',
'wantedpages-badtitle'    => 'Uungultigen Tittel in dat Resultoat: $1',
'wantedfiles'             => 'Failjende Doatäie',
'wantedtemplates'         => 'Failjende Foarloagen',
'mostlinked'              => 'Maast ferlinkede Sieden',
'mostlinkedcategories'    => 'Maast benutsede Kategorien',
'mostlinkedtemplates'     => 'Maastbenutsede Foarloagen',
'mostcategories'          => 'Maast kategorisierde Sieden',
'mostimages'              => 'Maast benutsede Doatäie',
'mostrevisions'           => 'Sieden mäd do maaste Versione',
'prefixindex'             => 'Aal Sieden (mäd Präfix)',
'shortpages'              => 'Kuute Sieden',
'longpages'               => 'Loange Sieden',
'deadendpages'            => 'Siede sunner Ferwiese',
'deadendpagestext'        => 'Do foulgjende Sieden linkje nit tou uur Sieden in {{SITENAME}}.',
'protectedpages'          => 'Schutsede Sieden',
'protectedpages-indef'    => 'Bloot uunbeschränkt bruukte Sieden wiese',
'protectedpages-cascade'  => 'Bloot Sieden mäd Kaskadenschuts',
'protectedpagestext'      => 'Do foulgjende Sieden sunt beschutsed juun Ferschuuwen of Beoarbaidjen',
'protectedpagesempty'     => 'Apstuuns sunt neen Sieden mäd disse Parametere schutsed.',
'protectedtitles'         => 'Speerde Tittele',
'protectedtitlestext'     => 'Do foulgjende Sieden sunt speerd uum näi tou moakjen',
'protectedtitlesempty'    => 'Apstuuns sunt mäd do ounroate Parametere neen Sieden speerd uum näi tou moakjen.',
'listusers'               => 'Benutser-Lieste',
'listusers-editsonly'     => 'Wies bloot Benutsere mäd Biedraage',
'listusers-creationsort'  => 'Ätter dän Moakdoatum sortierje',
'usereditcount'           => '$1 {{PLURAL:$1|Beoarbaidenge|Beoarbaidengen}}',
'usercreated'             => 'Moaked ap n $1 uum $2',
'newpages'                => 'Näie Sieden',
'newpages-username'       => 'Benutsernoome:',
'ancientpages'            => 'Siet loang uunbeoarbaidede Sieden',
'move'                    => 'ferschuuwe',
'movethispage'            => 'Artikkel ferschuuwe',
'unusedimagestext'        => 'Do foulgjende Doatäie bestounde, man sunt nit apnuumen in ne Siede.
Beoachtje jädden, dät uur Websieden ne Doatäi mäd n direkten URL ferlinkje konnen.
Deeruum konnen do hier noch aptäld weese, wan do uk aktiv benutsed wäide.',
'unusedcategoriestext'    => 'Do foulgjende Kategorien bestounde, wan do apstuuns uk nit in Ferweendenge sunt.',
'notargettitle'           => 'Naan Artikkel anroat',
'notargettext'            => 'Du hääst nit anroat, ap wäkke Siede disse Funktion anwoand wäide schäl.',
'nopagetitle'             => 'Sielsiede nit foarhounden',
'nopagetext'              => 'Ju anroate Sielsiede bestoant nit.',
'pager-newer-n'           => '{{PLURAL:$1|naisten|naiste $1}}',
'pager-older-n'           => '{{PLURAL:$1|foarigen|foarige $1}}',
'suppress'                => 'Uursicht',

# Book sources
'booksources'               => 'ISBN-Säike',
'booksources-search-legend' => 'Säik ätter Steeden wier me Bouke kriege kon',
'booksources-go'            => 'Säike (011)',
'booksources-text'          => 'Dit is ne Lieste mäd Ferbiendengen tou Internetsieden, do der näie un bruukte Bouke ferkoopje. Deer kon et uk wiedere Informatione uur do Bouke reeke. {{SITENAME}} is mäd neen fon disse Anbjoodere geschäftelk ferbuunen.',
'booksources-invalid-isbn'  => 'Fermoudelk is ju ISBN uungultich. Säik ätter Failere in ju Kopie.',

# Special:Log
'specialloguserlabel'  => 'Benutser:',
'speciallogtitlelabel' => 'Tittel:',
'log'                  => 'Logbouke',
'all-logs-page'        => 'Aal eepentelke Logbouke',
'alllogstext'          => 'Dit is ne kombinierde Anwiesenge fon aal Logbouke fon {{SITENAME}}.
Ju Uutgoawe kon truch ju Uutwoal fon dän Logbouktyp, fon dän Benutser of dän Siedentittel ienschränkt wäide (Groot-/Littekschrieuwen mout beoachtet wäide).',
'logempty'             => 'Neen paasende Iendraage.',
'log-title-wildcard'   => 'Tittel fangt oun mäd …',

# Special:AllPages
'allpages'          => 'Aal Artikkele',
'alphaindexline'    => '$1 bit $2',
'nextpage'          => 'Naiste Siede ($1)',
'prevpage'          => 'Foarige Siede ($1)',
'allpagesfrom'      => 'Sieden wiese fon:',
'allpagesto'        => 'Sieden anwiese bit:',
'allarticles'       => 'Aal do Artikkele',
'allinnamespace'    => 'Aal Sieden in $1 Noomenruum',
'allnotinnamespace' => 'Aal Sieden, bute in dän $1 Noomenruum',
'allpagesprev'      => 'Foargungende',
'allpagesnext'      => 'Naiste',
'allpagessubmit'    => 'Anweende',
'allpagesprefix'    => 'Sieden anwiese mäd Präfix:',
'allpagesbadtitle'  => 'Die ienroate Siedennoome is ungultich: Hie häd äntweeder n foaranstoald Sproak-, n Interwiki-Oukuutenge of änthaalt een of moor Teekene, do der in Siedennoomen nit verwoand wäide duuren.',
'allpages-bad-ns'   => 'Die Noomensruum „$1“ is in {{SITENAME}} nit deer.',

# Special:Categories
'categories'                    => 'Kategorien',
'categoriespagetext'            => 'Foulgjende {{PLURAL:$1|Kategorie änthoalt|Kategorien änthoolde}} Sieden of Doatäie.
[[Special:UnusedCategories|Nit benutsede Kategorien]] wäide hier nit apfierd.
Sjuch uk ju Lieste fon do [[Special:WantedCategories|wonskede Kategorien]].',
'categoriesfrom'                => 'Wies Kategorien siet:',
'special-categories-sort-count' => 'Sortierenge ätter Antaal',
'special-categories-sort-abc'   => 'Sortierenge ätter Alphabet',

# Special:DeletedContributions
'deletedcontributions'             => 'Läskede Benutserbiedraage',
'deletedcontributions-title'       => 'Läskede Benutserbiedraage',
'sp-deletedcontributions-contribs' => 'Benutserbiedraage',

# Special:LinkSearch
'linksearch'       => 'Webferbiendenge-Säike',
'linksearch-pat'   => 'Säikmuster:',
'linksearch-ns'    => 'Noomensruum:',
'linksearch-ok'    => 'Säike (012)',
'linksearch-text'  => 'Disse Spezialsiede moaket ju Säike muugelke ätter Sieden, in do bestimde Webferbiendengen äntheelden sunt. Deerbie konne Wildcards as biespilswiese <tt>*.example.com</tt> benutsed wäide.<br />Unnerstutsede Protokolle: <tt>$1</tt>',
'linksearch-line'  => '$1 is ferlinked fon $2',
'linksearch-error' => 'Wildcards konnen bloot an dän Ounfang fon ju URL ferwoand wäide.',

# Special:ListUsers
'listusersfrom'      => 'Wies Benutsere fon:',
'listusers-submit'   => 'Wies',
'listusers-noresult' => 'Naan Benutser fuunen.',
'listusers-blocked'  => '(speerd)',

# Special:ActiveUsers
'activeusers'            => 'Lieste fon aktive Benutsere',
'activeusers-intro'      => 'Dit is ne Lieste fon Benutsere, do binne {{PLURAL:$1|dän lääste Dai|do lääste $1 Deege}} Aktivitäte apwiese.',
'activeusers-count'      => '$1 {{PLURAL:$1|Beoarbaidenge|Beoarbaidengen}} in do {{PLURAL:$3|lääste 24 Uuren|fergeene $3 Deege}}',
'activeusers-from'       => 'Wies Benutsere ounfangend mäd:',
'activeusers-hidebots'   => 'Bots fersteete',
'activeusers-hidesysops' => 'Administratore fersteete',
'activeusers-noresult'   => 'Neen Benutsere fuunen.',

# Special:Log/newusers
'newuserlogpage'              => 'Näianmäldengs-Logbouk',
'newuserlogpagetext'          => 'Dit is dät Logbouk fon näi anmäldede Benutsere.',
'newuserlog-byemail'          => 'dät Paaswoud wuud uur E-Mail fersoand',
'newuserlog-create-entry'     => 'Benutser wuude näi registrierd',
'newuserlog-create2-entry'    => 'moakede näi Benutserkonto „$1“',
'newuserlog-autocreate-entry' => 'Benutserkonto wuud automatisk moaked',

# Special:ListGroupRights
'listgrouprights'                      => 'Benutsergruppen-Gjuchte',
'listgrouprights-summary'              => 'Dit is ne Lieste fon do in dissen Wiki definierde Benutsergruppen un do deermäd ferbuundene Gjuchte.
Informatione uurhäär uur eenpelde Gjuchte konnen [[{{MediaWiki:Listgrouprights-helppage}}|hier]] fuunen wäide.',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Bewilligd Gjucht</span>
* <span class="listgrouprights-revoked">Äntleeken Gjucht</span>',
'listgrouprights-group'                => 'Gruppe',
'listgrouprights-rights'               => 'Gjuchte',
'listgrouprights-helppage'             => 'Help:Gruppengjuchte',
'listgrouprights-members'              => '(Meegliedelieste)',
'listgrouprights-addgroup'             => 'Kon Benutser tou {{PLURAL:$2|disse Gruppe|disse Gruppen}} bietouföigje: $1',
'listgrouprights-removegroup'          => 'Kon Benutser uut {{PLURAL:$2|disse Gruppe|disse Gruppen}} wächhoalje: $1',
'listgrouprights-addgroup-all'         => 'Kon Benutser tou aal Gruppen bietouföigje',
'listgrouprights-removegroup-all'      => 'Kon Benutser uut aal Gruppen wächhoalje',
'listgrouprights-addgroup-self'        => 'Dät oaine Benutserkonto an disse {{PLURAL:$2|Gruppe|Gruppen}} bietouföigje: $1',
'listgrouprights-removegroup-self'     => 'Dät oaine Benutserkonto uut disse {{PLURAL:$2|Gruppe|Gruppen}} wächhoalje: $1',
'listgrouprights-addgroup-self-all'    => 'Kon aal Gruppen tou dät oaine Konto bietouföigje',
'listgrouprights-removegroup-self-all' => 'Kon aal Gruppen fon dät oaine Konto wächhoalje',

# E-mail user
'mailnologin'      => 'Du bäst nit anmälded.',
'mailnologintext'  => 'Du moast [[Special:UserLogin|anmälded weese]] un sälwen ne [[Special:Preferences|gultige E-Mail-Adrässe]] anroat hääbe, uum uur Benutsere ne E-Mail tou seenden.',
'emailuser'        => 'Seende E-Mail an dissen Benutser',
'emailpage'        => 'E-mail an Benutser',
'emailpagetext'    => 'Du koast dän Benutser mäd dän unner stoundene Formular ne E-Mail seende.
As Ouseender wäd ju E-Mail-Adresse uut dien [[Special:Preferences|Ienstaalengen]] iendrain, deermäd die Benutser die oantwoudje kon.',
'usermailererror'  => 'Dät Mail-Objekt roat n Failer tourääch:',
'defemailsubject'  => '{{SITENAME}}-E-Mail',
'noemailtitle'     => 'Neen Email-Adrässe',
'noemailtext'      => 'Dissen Benutser häd neen gultige Email-Adrässe anroat.',
'nowikiemailtitle' => 'E-Mail-Ferseendenge nit muugelk',
'nowikiemailtext'  => 'Dissen Benutser moate neen E-Mails fon uur Benutsere kriege.',
'email-legend'     => 'E-Mail an n uur {{SITENAME}}-Benutser seende',
'emailfrom'        => 'Fon:',
'emailto'          => 'An:',
'emailsubject'     => 'Beträf:',
'emailmessage'     => 'Ättergjucht:',
'emailsend'        => 'Seende',
'emailccme'        => 'Seend ne Kopie fon ju E-Mail an mie',
'emailccsubject'   => 'Kopie fon dien Ättergjucht an $1: $2',
'emailsent'        => 'Begjucht fersoand',
'emailsenttext'    => 'Jou Begjucht is soand wuuden.',
'emailuserfooter'  => 'Disse E-Mail wuude fon „Benutzer:$1“ an „Benutzer:$2“ mäd Hälpe fon ju „E-Mail an dissen Benutser“-Funktion fon {{SITENAME}} fersoand.',

# Watchlist
'watchlist'            => 'Beooboachtengslieste',
'mywatchlist'          => 'Beooboachtengslieste',
'watchlistfor2'        => 'Fon $1 $2',
'nowatchlist'          => 'Du hääst neen Iendraage ap dien Beooboachtengslieste. Du moast anmälded weese, dät die een Beooboachtengslieste tou Ferföigenge stoant.',
'watchlistanontext'    => 'Du moast die $1, uum dien Beooboachtengslieste tou sjoon of Iendraage ap hier tou beoarbaidjen.',
'watchnologin'         => 'Du bäst nit anmälded',
'watchnologintext'     => 'Du moast [[Special:UserLogin|anmälded]] weese, uum dien Beooboachtengslieste tou beoarbaidjen.',
'addedwatch'           => 'An Foulgelieste touföiged.',
'addedwatchtext'       => "Die Artikkel \"[[:\$1]]\" wuude an dien [[Special:Watchlist|Foulgelieste]] touföiged.
Leetere Annerengen an dissen Artikkel un ju touheerende Diskussionssiede wäide deer liested
un die Artikkel wäd in ju [[Special:RecentChanges|fon do lääste Annerengen]] in '''Fatschrift''' anroat.

Wan du die Artikkel wier fon ju Foulgelieste ou hoalje moatest, klik ap ju Siede ap \"Ferjeet disse Siede\".",
'removedwatch'         => 'Fon ju Beooboachtengsslieste ou hoald',
'removedwatchtext'     => 'Ju Siede „[[:$1]]“ wuude fon dien [[Special:Watchlist|Beooboachtengslieste]] wächhoald.',
'watch'                => 'Beooboachtje',
'watchthispage'        => 'Siede beooboachtje',
'unwatch'              => 'Nit moor beooboachtje',
'unwatchthispage'      => 'Nit moor beooboachtje',
'notanarticle'         => 'Naan Artikkel',
'notvisiblerev'        => 'Version wuude läsked',
'watchnochange'        => 'Neen fon do Sieden, do du beooboachtest, wuude in dän läästen Tiedruum beoarbaided.',
'watchlist-details'    => 'Jie beooboachtje {{PLURAL:$1|1 Siede|$1 Sieden}} (Diskussionssieden wuuden hier nit meetäld).',
'wlheader-enotif'      => '* E-Mail-Bescheed is aktivierd.',
'wlheader-showupdated' => "* Sieden, do ätter dien lääste Besäik annerd wuuden sunt, wäide '''fat''' deerstoald.",
'watchmethod-recent'   => 'Uurpröiwjen fon do lääste Beoarbaidengen foar ju Beooboachtengslieste',
'watchmethod-list'     => 'Uurpröiwjen fon ju Beooboachtengslieste ätter lääste Beoarbaidengen',
'watchlistcontains'    => 'Jou Beooboachtengslieste änthaalt $1 {{PLURAL:$1|Siede|Sieden}}.',
'iteminvalidname'      => "Problem mäd dän Iendraach '$1', ungultige Noome...",
'wlnote'               => "Hier {{PLURAL:$1|foulget do lääste Annerenge|foulgje do lääste '''$1''' Annerengen}} fon do lääste {{PLURAL:$2|Uur|'''$2''' Uuren}}.",
'wlshowlast'           => 'Wies do lääste $1 Uuren, $2 Deege, of $3 (in do lääste 30 Deege).',
'watchlist-options'    => 'Anwiesoptione',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Beooboachtje …',
'unwatching' => 'Nit beooboachtje …',

'enotif_mailer'                => '{{SITENAME}} tält Bescheed uur Email',
'enotif_reset'                 => 'Markier aal besoachte Sieden',
'enotif_newpagetext'           => 'Dit is ne näie Siede.',
'enotif_impersonal_salutation' => '{{SITENAME}} Benutser',
'changed'                      => 'annerd',
'created'                      => 'näi anlaid',
'enotif_subject'               => '{{SITENAME}} Siede $PAGETITLE wuude $CHANGEDORCREATED fon $PAGEEDITOR',
'enotif_lastvisited'           => 'Aal Annerengen ap aan Blik: $1',
'enotif_lastdiff'              => '$1 wiest alle Annerengen mäd aan Glap.',
'enotif_anon_editor'           => 'Anonyme Benutser $1',
'enotif_body'                  => 'Ljoowe $WATCHINGUSERNAME,

Ju {{SITENAME}} Siede $PAGETITLE wuude fon $PAGEEDITOR an dän $PAGEEDITDATE $CHANGEDORCREATED, sjuch $PAGETITLE_URL foar ju aktuälle Version.

$NEWPAGE

Touhoopefoatenge fon dän Editor: $PAGESUMMARY $PAGEMINOREDIT

Kontakt toun Beoarbaider:
Mail $PAGEEDITOR_EMAIL
Wiki $PAGEEDITOR_WIKI

Der wäide soloange neen wiedere Mails toun Bescheed soand, bit du ju Siede wier besoacht hääst. Ap dien Beooboachtengssiede koast du aal Markere toun Bescheed touhoope touräächsätte.

Dien früntelk {{SITENAME}} Becheedtälsystem

--

Uum do Ienstaalengen fon dien Beooboachtengslieste antoupaasjen, besäik
{{fullurl:{{#special:Watchlist}}/edit}}

Uum ju Siede fon dien Kontrollieste tou läskjen, besäik
$UNWATCHURL

Touräächmäldengen un wiedere Hälpe: {{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Siede läskje',
'confirm'                => 'Bestäätigje',
'excontent'              => "Oolde Inhoold: '$1'",
'excontentauthor'        => "Inhoold waas: '$1' (eensige Benutser: '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'          => "Inhoold foar dät Loosmoakjen fon de Siede: '$1'",
'exblank'                => 'Siede waas loos',
'delete-confirm'         => 'Läskjen fon „$1“',
'delete-legend'          => 'Läskje',
'historywarning'         => "'''Woarschauenge:''' Ju Siede, ju du läskje moatest, häd ne Versionsgeschichte mäd sowät $1 {{PLURAL:$1|Version|Versione}}:",
'confirmdeletetext'      => 'Jie sunt deerbie, n Artikkel of ne Bielde un aal allere Versione foar altied uut dän Doatenboank tou läskjen. Bitte bestäätigje Jie Jou Apsicht, dät tou dwoon, dät Jie Jou do Konsekwänsen bewust sunt, un dät Jie in Uureenstämmenge mäd uus [[{{MediaWiki:Policy-url}}]] honnelje.',
'actioncomplete'         => 'Aktion be-eended',
'actionfailed'           => 'Aktion misglukked',
'deletedtext'            => '"<nowiki>$1</nowiki>" wuude läsked.
In $2 fiende Jie ne Lieste fon do lääste Läskengen.',
'deletedarticle'         => '"[[$1]]" wuude läsked',
'suppressedarticle'      => 'feranderde ju Sichtboarhaid fon „[[$1]]“',
'dellogpage'             => 'Läsk-Logbouk',
'dellogpagetext'         => 'Hier is ne Lieste fon do lääste Läskengen.',
'deletionlog'            => 'Läsk-Logbouk',
'reverted'               => 'Ap ne oolde Version touräächsät',
'deletecomment'          => 'Gruund:',
'deleteotherreason'      => 'Uur/additionoalen Gruund:',
'deletereasonotherlist'  => 'Uur Gruund',
'deletereason-dropdown'  => '* Algemeene Läskgruunde
** Wonsk fon dän Autor
** Urhebergjuchtsferlätsenge
** Vandalismus',
'delete-edit-reasonlist' => 'Läskgruunde beoarbaidje',
'delete-toobig'          => 'Disse Siede häd mäd moor as $1 {{PLURAL:$1|Version|Versionen}} ne gjucht loange Versionsgeschichte. Dät Läskjen fon sukke Sieden wuud ienschränkt, uum ne toufällige Uurlastenge fon {{SITENAME}} tou ferhinnerjen.',
'delete-warning-toobig'  => 'Disse Siede häd mäd moor as $1 {{PLURAL:$1|Version|Versione}} ne gjucht loange Versionsgeschichte. Dät Läskjen kon tou Stöörengen in {{SITENAME}} fiere.',

# Rollback
'rollback'          => 'Touräächsätten fon do Annerengen',
'rollback_short'    => 'Touräächsätte',
'rollbacklink'      => 'touräächsätte',
'rollbackfailed'    => 'Touräächsätten misglukked',
'cantrollback'      => 'Disse Annerenge kon nit touräächstoald wäide; deer et naan fröieren Autor rakt.',
'alreadyrolled'     => 'Dät Touräächsätten fon do Annerengen fon [[User:$2|$2]] ([[User talk:$2|Diskussion]], [[Special:Contributions/$2|{{int:contribslink}}]]) an Siede [[:$1]] hied naan Ärfoulch, deer in ju Twiskentied al n uur Benutser Annerengen an disse Siede foarnuumen häd.

Ju lääste Annerenge stamt fon [[User:$3|$3]] ([[User talk:$3|Diskussion]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'       => "Ju Annerengs-Touhoopefoatenge waas: \"''\$1''\".",
'revertpage'        => 'Tounichte moakede Beoarbaidengen fon [[Special:Contributions/$2|$2]] ([[User talk:$2|Talk]]) tou ju lääste Version fon [[User:$1|$1]]',
'revertpage-nouser' => 'Annerengen fon (Benutsernoome wächhoald) touräächtroald un lääste Version fon [[User:$1|$1]] wier häärstoald',
'rollback-success'  => 'Do Annerengen fon $1 wuuden tourääch annerd un ju lääste Version fon $2 wuude wier moaked.',

# Edit tokens
'sessionfailure' => 'Dät roat n Problem mäd ju Uurdreegenge fon dien Benutserdoaten. Disse Aktion wuude deeruum sicherheidshoolwe oubreeken, uum ne falske Touoardnenge fon dien Annerengen tou n uur Benutser tou ferhinnerjen. Gung jädden tourääch un fersäik dän Foargong fonnäien uuttoufieren.',

# Protect
'protectlogpage'              => 'Siedenschuts-Logbouk',
'protectlogtext'              => 'Dit is ne Lieste fon do blokkierde Sieden.
Sjuch [[Special:ProtectedPages|Schutsede Siede]] foar moor Informatione.',
'protectedarticle'            => 'schutsede „[[$1]]“',
'modifiedarticleprotection'   => 'annerde dän Schuts fon „[[$1]]“',
'unprotectedarticle'          => 'hieuwede dän Schuts fon "[[$1]]" ap',
'movedarticleprotection'      => 'ferschoof do Siedenschutsienstaalengen fon „[[$2]]“ ätter „[[$1]]“',
'protect-title'               => 'Schuts annerje fon „$1“',
'prot_1movedto2'              => 'häd "[[$1]]" ätter "[[$2]]" ferschäuwen',
'protect-legend'              => 'Siedenschutsstoatus annerje',
'protectcomment'              => 'Gruund:',
'protectexpiry'               => 'Speerduur:',
'protect_expiry_invalid'      => 'Ju ienroate Duur is uungultich.',
'protect_expiry_old'          => 'Ju Speertied lait in ju fergeene Tied.',
'protect-unchain-permissions' => 'Fröiere Speeroptione aphieuwje',
'protect-text'                => "Hier koast du dän Schutsstoatus foar ju Siede '''<nowiki>$1</nowiki>''' ienkiekje un annerje.",
'protect-locked-blocked'      => "Du koast dän Siedenschuts nit annerje, deer dien Benutserkonto speerd is. Hier sunt do aktuelle Siedenschuts-Ienstaalengen foar ju Siede '''„$1“:'''",
'protect-locked-dblock'       => "Ju Doatenboank is speerd, die Siedenschuts kon deeruum nit annerd wäide. Hier sunt do aktuelle Siedenschuts-Ienstaalengen foar ju Siede '''„$1“:'''",
'protect-locked-access'       => "Du bäst nit begjuchtiged, dän Siedenschutsstoatus tou annerjen. Hier is die aktuälle Schutsstoatus fon ju Siede '''$1''':",
'protect-cascadeon'           => 'Disse Siede is apstuuns Deel fon ne Kaskadenspeere. Ju is in {{PLURAL:$1|ju foulgjende Siede|do foulgjende Sieden}} ienbuunen, do der truch ju Kaskadenspeerroption schutsed {{PLURAL:$1|is|sunt}}. Die Siedenschutsstoatus kon foar disse Siede annerd wäide, man dät häd naan Ienfloud ap ju Kaskadenspeere:',
'protect-default'             => 'Aal Benutsere',
'protect-fallback'            => 'Deer wäd ju „$1“-Begjuchtigenge benöödigd.',
'protect-level-autoconfirmed' => 'Speerenge foar näie un nit registrierde Benutsere',
'protect-level-sysop'         => 'Bloot Administration',
'protect-summary-cascade'     => 'kaskadierjend',
'protect-expiring'            => 'bit $1 (UTC)',
'protect-expiry-indefinite'   => 'uunbeschränkt',
'protect-cascade'             => 'Kaskadierjende Speere – aal in disse Siede ienbuundene Foarloagen wäide ieuwenfals speerd.',
'protect-cantedit'            => 'Du koast ju Speere fon disse Siede nit annerje, deer du neen Begjuchtigenge toun Beoarbaidjen fon ju Siede hääst.',
'protect-othertime'           => 'Uur Speerduur:',
'protect-othertime-op'        => 'uur Speerduur',
'protect-existing-expiry'     => 'Aktuel Siedenschutseende: $2, $3 Uure',
'protect-otherreason'         => 'Uur/touföigeden Gruund:',
'protect-otherreason-op'      => 'Uur Gruund',
'protect-dropdown'            => '*Algemeene Schutsgruunde
** Weblink-Spam
** Editwar
** Oafte ienbuundene Foarloage
** Siede mäd hooge Besäikertaal',
'protect-edit-reasonlist'     => 'Schutsgruunde beoarbaidje',
'protect-expiry-options'      => '1 Uure:1 hour,1 Dai:1 day,1 Wiek:1 week,2 Wieke:2 weeks,1 Mound:1 month,3 Mounde:3 months,6 Mounde:6 months,1 Jier:1 year,Uunbestimd:infinite',
'restriction-type'            => 'Schutsstoatus',
'restriction-level'           => 'Schutshöchte',
'minimum-size'                => 'Minstgrööte',
'maximum-size'                => 'Maximoalgrööte:',
'pagesize'                    => '(Bytes)',

# Restrictions (nouns)
'restriction-edit'   => 'beoarbaidje',
'restriction-move'   => 'ferschuuwe',
'restriction-create' => 'moakje',
'restriction-upload' => 'Hoochleede',

# Restriction levels
'restriction-level-sysop'         => 'schutsed (bloot Administratore)',
'restriction-level-autoconfirmed' => 'schutsed (bloot anmäldede, nit-näie Benutsere)',
'restriction-level-all'           => 'aal',

# Undelete
'undelete'                     => 'Läskede Siede wier häärstaale',
'undeletepage'                 => 'Läskede Siede wier häärstaale',
'undeletepagetitle'            => "'''Ju foulgjende Uutgoawe wiest do läskede Versione fon [[:$1|$1]]'''.",
'viewdeletedpage'              => 'Läskede Versione anwiese',
'undeletepagetext'             => '{{PLURAL:$1|Ju foulgjende Siede wuud läsked un kon|Do foulgjende $1 Sieden wuuden läsked un konnen}} fon Administratore wier häärstoald wäide:',
'undelete-fieldset-title'      => 'Beoarbaidengen wier häärstaale',
'undeleteextrahelp'            => "Uum ju Siede gans mäd aal Versione wiertoumoakjen, wääl neen Versione uut, reek ne Begruundenge an un klik ap '''''Wier moakje'''''.
* Moatest du bloot bestimde Versione wier moakje, so wääl do jädden eenpeld anhound fon do Markierengen uut, reek ne Begruundenge an un klik dan ap '''''Wier moakje'''''.
* '''''Oubreeke''''' moaket dät Kommentoarfäild loos un hoalt aal Markierengen wäch bie do Versione.",
'undeleterevisions'            => '{{PLURAL:$1|1 Version|$1 Versione}} archivierd',
'undeletehistory'              => 'Wan du disse Siede wier häärstoalst, wäide uk aal oolde Versione wier häärstoald. Wan siet ju Läskenge aan näien Artikkel mäd dän sälge Noome moaked wuude, wäide do wier häärstoalde Versione as oolde Versione fon dissen Artikkel ferschiene.',
'undeleterevdel'               => 'Dät wier Häärstaalen wäd nit truchfierd, wan deertruch ju aktuelste Version toun Deel läsked wäd.
In dissen Fal duur ju aktuelste Version nit markierd wäide of sichtboar moaked wäide.',
'undeletehistorynoadmin'       => 'Disse Siede wuude läsked. Die Gruund foar ju Läskenge is in ju Touhoopefoatenge ounroat,
juust as Details tou dän lääste Benutser, die der disse Siede foar ju Läskenge beoarbaided häd.
Die aktuelle Text fon ju läskede Siede is bloot Administratore tougongelk.',
'undelete-revision'            => 'Läskede Version fon $1 (fon dän $4 uum $5 Uure), $3:',
'undeleterevision-missing'     => 'Uungultige of failjende Version. Äntweeder is ju Ferbiendenge falsk of ju Version wuude uut dät Archiv wier moaked of wächhoald.',
'undelete-nodiff'              => 'Neen foargungende Version fuunen.',
'undeletebtn'                  => 'Wier häärstaale',
'undeletelink'                 => 'bekiekje/wier häärstaale',
'undeleteviewlink'             => 'bekiekje',
'undeletereset'                => 'Oubreeke',
'undeleteinvert'               => 'Uutwoal uumekiere',
'undeletecomment'              => 'Gruund:',
'undeletedarticle'             => 'häd "[[$1]]" wier häärstoald',
'undeletedrevisions'           => '{{PLURAL:$1|1 Version wuude|$1 Versione wuuden}} wier häärstoald',
'undeletedrevisions-files'     => '{{PLURAL:$1|1 Version|$1 Versione}} un {{PLURAL:$2|1 Doatäi|$2 Doatäie}} wuuden wier häärstoald',
'undeletedfiles'               => '{{PLURAL:$1|1 Doatäie wuude|$1 Doatäie wuuden}} wier häärstoald',
'cannotundelete'               => 'Wier Moakjen failsloain; uurswäl häd ju Siede al wier moaked.',
'undeletedpage'                => "'''$1''' wuude wier moaked.

In dät [[Special:Log/delete|Läsk-Logbouk]] finst du ne Uursicht fon do läskede un wier moakede Sieden.",
'undelete-header'              => 'Sjuch dät [[Special:Log/delete|Läsk-Logbouk]] foar knu läskede Sieden.',
'undelete-search-box'          => 'Säik ätter läskede Sieden',
'undelete-search-prefix'       => 'Säikbegriep (Woudounfang sunner Wildcards):',
'undelete-search-submit'       => 'Säike (013)',
'undelete-no-results'          => 'Der wuude in dät Archiv neen tou dän Säikbegriep paasjende Siede fuunen.',
'undelete-filename-mismatch'   => 'Ju Doatäiversion mäd dän Tiedstämpel $1 kuude nit wier moaked wäide: Do Doatäinoomen paasje nit tounonner.',
'undelete-bad-store-key'       => 'Ju Doatäiversion mäd dän Tiedstämpel $1 kuude nit wier moaked wäide: Ju Doatäi waas al foar dät Läskjen nit moor deer.',
'undelete-cleanup-error'       => 'Failer bie dät Läskjen fon ju nit benutsede Archiv-Version $1.',
'undelete-missing-filearchive' => 'Ju Doatäi mäd ju Archiv-ID $1 kon nit wier moaked wäide, deer ju nit in ju Doatenboank deer is. Muugelkerwiese wuude ju al wier moaked.',
'undelete-error-short'         => 'Failer bie dät wier moakjen fon ju Doatäi $1',
'undelete-error-long'          => 'Der wuuden Failere bie dät wier moakjen fon ne Doatäi fääststoald:

$1',
'undelete-show-file-confirm'   => 'Bäst du sicher, dät du ne läskede Version fon ju Doatäi „<nowiki>$1</nowiki>“ fon n $2, $3 Uure sjo wolt?',
'undelete-show-file-submit'    => 'Jee',

# Namespace form on various pages
'namespace'      => 'Noomensruum:',
'invert'         => 'Uutwoal uumekiere',
'blanknamespace' => '(Sieden)',

# Contributions
'contributions'       => 'Benutserbiedraage',
'contributions-title' => 'Benutserbiedraage fon „$1“',
'mycontris'           => 'Oaine Biedraage',
'contribsub2'         => 'Foar $1 ($2)',
'nocontribs'          => 'Deer wuuden neen Annerengen foar disse Kriterien fuunen.',
'uctop'               => '(aktuäl)',
'month'               => 'un Mound:',
'year'                => 'bit Jier:',

'sp-contributions-newbies'        => 'Wies bloot Biedraage fon näie Benutsere',
'sp-contributions-newbies-sub'    => 'Foar Näilinge',
'sp-contributions-newbies-title'  => 'Benutserbiedraage fon näie Benutsere',
'sp-contributions-blocklog'       => 'Speerlogbouk',
'sp-contributions-deleted'        => 'Läskede Benutserbiedraage',
'sp-contributions-logs'           => 'Logbouke',
'sp-contributions-talk'           => 'Diskussion',
'sp-contributions-userrights'     => 'Benutsergjuchteferwaltenge',
'sp-contributions-blocked-notice' => 'Dissen Benutser is apstuuns speerd. Hier foulget die aktuelle Iendraach uut dät Benutser-Logbouk:',
'sp-contributions-search'         => 'Säike ätter Benutserbiedraage',
'sp-contributions-username'       => 'IP-Adrässe af Benutsernoome:',
'sp-contributions-submit'         => 'Säike (014)',

# What links here
'whatlinkshere'            => 'Links ap disse Siede',
'whatlinkshere-title'      => 'Sieden, do der ap "$1" linkje',
'whatlinkshere-page'       => 'Siede:',
'linkshere'                => "Do foulgjende Sieden ferwiese hierhäär:  '''[[:$1]]''': <br /><small>(Moonige Sieden wäide eventuell moorfooldich liested, konnen in säildene Falle oawers uk miste. Dät kumt fon oolde Failere in dän Software häär, man schoadet fääre niks.)</small>",
'nolinkshere'              => "Naan Artikkel ferwiest hierhäär: '''[[:$1]]'''.",
'nolinkshere-ns'           => "Neen Siede ferlinket ap '''„[[:$1]]“''' in dän wäälde Noomensruum.",
'isredirect'               => 'Fäärelaitengs-Siede',
'istemplate'               => 'Foarloagenienbiendenge',
'isimage'                  => 'Doatäilink',
'whatlinkshere-prev'       => '{{PLURAL:$1|foarige|foarige $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|naiste|naiste $1}}',
'whatlinkshere-links'      => '← Links',
'whatlinkshere-hideredirs' => 'Fäärelaitengen $1',
'whatlinkshere-hidetrans'  => 'Foarloagenienbiendengen $1',
'whatlinkshere-hidelinks'  => 'Links $1',
'whatlinkshere-hideimages' => 'Doatäilinke $1',
'whatlinkshere-filters'    => 'Filtere',

# Block/unblock
'blockip'                         => 'Blokkierje Benutser',
'blockip-title'                   => 'Benutser speere',
'blockip-legend'                  => 'IP-Adresse/Benutser speere',
'blockiptext'                     => 'Mäd dit Formular speerst du ne IP-Adresse of n Benutsernoome, so dät fon deer neen Annerengen moor foarnuumen wäide konnen.
Dit schuul bloot geböäre, uum Vandalismus tou ferhinnerjen un in Uureenstimmenge mäd do [[{{MediaWiki:Policy-url}}|Gjuchtlienjen]].
Reek dän Gruund foar ju Speere oun.',
'ipaddress'                       => 'IP-Adrässe:',
'ipadressorusername'              => 'IP-Adresse of Benutsernoome:',
'ipbexpiry'                       => 'Oulooptied (Speerduur):',
'ipbreason'                       => 'Begruundenge:',
'ipbreasonotherlist'              => 'Uur Begründenge',
'ipbreason-dropdown'              => '* Algemeene Speergruunde
** Ienföigjen fon falske Information
** Loosmoakjen fon Sieden
** Föiget massenhaft externe Links ien
** Ienstaalen fon uunsinnige Seeken in Sieden
** betruujend Ferhoolden/Belästigenge
** Misbruuk truch moorere Benutserkonten
** Uungoadelge Benutsernoome',
'ipbanononly'                     => 'Bloot anonyme Benutsere speere',
'ipbcreateaccount'                => 'Dät Moakjen fon Benutserkonten ferhinnerje',
'ipbemailban'                     => 'E-Mail-Fersoand speere',
'ipbenableautoblock'              => 'Speer ju aktuell fon dissen Benutser nutsede IP-Adresse as uk automatisk aal foulgjende, fon do uut hie Beoarbaidengen of dät Anlääsen fon Benutseraccounts fersäkt',
'ipbsubmit'                       => 'Adrässe blokkierje',
'ipbother'                        => 'Uur Duur (ängelsk):',
'ipboptions'                      => '2 Uuren:2 hours,1 Dai:1 day,3 Deege:3 days,1 Wiek:1 week,2 Wieke:2 weeks,1 Mound:1 month,3 Mounde:3 months,6 Mounde:6 months,1 Jier:1 year,Uunbestimd:infinite',
'ipbotheroption'                  => 'Uur Duur',
'ipbotherreason'                  => 'Uur/additionelle Begründenge:',
'ipbhidename'                     => 'Benutsernoome in Beoarbaidengen un Liesten ferstopje.',
'ipbwatchuser'                    => 'Benutser(diskussions)siede beooboachtje',
'ipballowusertalk'                => 'Benutser duur oaine Diskussionssieden unner sien Speere beoarbaidje',
'ipb-change-block'                => 'Speere mäd disse Speerparametere fernäierje',
'badipaddress'                    => 'Dissen Benutser bestoant nit, d.h. die Noome is falsk',
'blockipsuccesssub'               => 'Blokkoade geloangen',
'blockipsuccesstext'              => 'Ju IP-Adrässe [[Special:Contributions/$1|$1]] wuude blokkierd.
<br />[[Special:IPBlockList|Lieste fon Blokkoaden]].',
'ipb-edit-dropdown'               => 'Speergruunde beoarbaidje',
'ipb-unblock-addr'                => '"$1" fräireeke',
'ipb-unblock'                     => 'IP-Adrässe/Benutser fräireeke',
'ipb-blocklist-addr'              => 'Aktuelle Speeren foar $1',
'ipb-blocklist'                   => 'Aal aktuelle Speeren anwiese',
'ipb-blocklist-contribs'          => 'Benutserbiedreege foar „$1“',
'unblockip'                       => 'IP-Adrässe fräireeke',
'unblockiptext'                   => 'Benutsje dät Formular, uum ne blokkierde IP-Adrässe fräitoureeken.',
'ipusubmit'                       => 'Disse Speerenge wächhoalje',
'unblocked'                       => '[[User:$1|$1]] wuude fräiroat',
'unblocked-id'                    => 'Speer-ID $1 wuude fräiroat',
'ipblocklist'                     => 'Speerde IP-Adrässen un Benutsernoomen',
'ipblocklist-legend'              => 'Säik ätter n speerden Benutser',
'ipblocklist-username'            => 'Benutsernoome of IP-Adrässe:',
'ipblocklist-sh-userblocks'       => '$1 Benutserspeeren',
'ipblocklist-sh-tempblocks'       => '$1 tiedwiese Speeren',
'ipblocklist-sh-addressblocks'    => '$1 IP-Speeren',
'ipblocklist-submit'              => 'Säike (015)',
'ipblocklist-localblock'          => 'Lokoale Speere',
'ipblocklist-otherblocks'         => 'Uur {{PLURAL:$1|Speere|Speeren}}',
'blocklistline'                   => '$1, $2 blokkierde $3 ($4)',
'infiniteblock'                   => 'uunbegränsed',
'expiringblock'                   => 'eendet an dän $1 uum $2 Uure',
'anononlyblock'                   => 'bloot Anonyme',
'noautoblockblock'                => 'Autoblock deaktivierd',
'createaccountblock'              => 'Dät Moakjen fon Benutserkonten speerd',
'emailblock'                      => 'E-Mail-Fersoand speerd',
'blocklist-nousertalk'            => 'duur oaine Diskussionssiede nit beoarbaidje',
'ipblocklist-empty'               => 'Ju Lieste änthaalt neen Iendraage.',
'ipblocklist-no-results'          => 'Ju soachte IP-Adresse/die Benutsernoome is nit speerd.',
'blocklink'                       => 'blokkierje',
'unblocklink'                     => 'fräireeke',
'change-blocklink'                => 'Speere annerje',
'contribslink'                    => 'Biedraage',
'autoblocker'                     => 'Du wierst blokkierd, deer du eene IP-Adrässe mäd "[[User:$1|$1]]" benutsjen dääst. Foar ju Blokkierenge fon dän Benutser waas as Gruund anroat: "$2".',
'blocklogpage'                    => 'Benutserblokkoaden-Logbouk',
'blocklog-showlog'                => 'Dissen Benutser wuud al eer speerd. Hier foulget die Iendraach uut dät Benutserspeer-Logbouk:',
'blocklog-showsuppresslog'        => 'Dissen Benutser wuud al eer speerd un ferstat.
Hier foulget die Iendraach uut dät Unnerdrukkengs-Logbouk:',
'blocklogentry'                   => '[[$1]] blokkierd foar n Tiedruum fon: $2 $3',
'reblock-logentry'                => 'annerde ju Speere foar „[[$1]]“ foar dän Tiedruum: $2 $3',
'blocklogtext'                    => 'Dit is n Logbouk fon Speerengen un Äntspeerengen fon Benutsere. Ju Sunnersiede fiert aal aktuäl speerde Benutsere ap, iensluutend automatisk blokkierde IP-Adrässe.',
'unblocklogentry'                 => 'Blokkade fon $1 aphieuwed',
'block-log-flags-anononly'        => 'bloot Anonyme',
'block-log-flags-nocreate'        => 'Dät Moakjen fon Benutserkonten speerd',
'block-log-flags-noautoblock'     => 'Autoblock deaktivierd',
'block-log-flags-noemail'         => 'E-Mail-Fersoand speerd',
'block-log-flags-nousertalk'      => 'duur oaine Diskussionssiede nit beoarbaidje',
'block-log-flags-angry-autoblock' => 'ärwiederden Autoblock aktivierd',
'block-log-flags-hiddenname'      => 'Benutsernoome ferstat',
'range_block_disabled'            => 'Ju Muugelkaid, ganse Adräsruume tou speeren, is nit aktivierd.',
'ipb_expiry_invalid'              => 'Ju anroate Oulooptied is nit gultich.',
'ipb_expiry_temp'                 => 'Ferstatte Benutsernoomen-Speeren schällen permanent weese.',
'ipb_hide_invalid'                => 'Dit Konto kon nit unnerdrukt wäide, deer dät toufuul Beoarbaidengen apwiest.',
'ipb_already_blocked'             => '„$1“ wuude al speerd.',
'ipb-needreblock'                 => '== Speere is al deer ==
„$1“ is al speerd. Moatest du do Speerparametere annerje?',
'ipb-otherblocks-header'          => 'Uur {{PLURAL:$1|Speere|Speeren}}',
'ipb_cant_unblock'                => 'Failer: Speer-ID $1 nit fuunen. Ju Speere wuude al aphieuwed.',
'ipb_blocked_as_range'            => 'Failer: Ju IP-Adresse $1 wuude as Deel fon ju Beräksspeere $2 indirekt speerd. Ne Äntspeerenge fon $1 alleene is nit muugelk.',
'ip_range_invalid'                => 'Uungultige IP-Adräsberäk.',
'ip_range_toolarge'               => 'Adräsberäkke, do der gratter sunt as /$1, sunt nit ferlööwed.',
'blockme'                         => 'Speer mie',
'proxyblocker'                    => 'Proxy blokker',
'proxyblocker-disabled'           => 'Disse Funktion is deaktivierd.',
'proxyblockreason'                => 'Jou IP-Adrässe wuude speerd, deer ju n eepenen Proxy is. Kontaktierje jädden Jou Provider af Jou Systemtechnik un informierje Jou jou uur dit muugelke Sicherhaidsproblem.',
'proxyblocksuccess'               => 'Kloor.',
'sorbsreason'                     => 'Dien IP-Adrässe is in ju DNSBL fon {{SITENAME}} as eepene PROXY liested.',
'sorbs_create_account_reason'     => 'Dien IP-Adrässe is in ju DNSBL fon {{SITENAME}} as eepene PROXY liested. Du koast neen Benutser-Account anlääse.',
'cant-block-while-blocked'        => 'Du duurst neen uur Benutsere speere, wan du sälwen speerd bäst.',
'cant-see-hidden-user'            => 'Die Benutser, dän du fersäkst tou speeren, wuud al speerd un ferstat. Deer du dät „hideuser“-Gjucht nit hääst, koast du ju Benutserspeere nit sjo un nit beoarbaidje.',

# Developer tools
'lockdb'              => 'Doatenboank speere',
'unlockdb'            => 'Doatenboank fräireeke',
'lockdbtext'          => 'Mäd dät Speeren fon de Doatenboank wäide aal Annerengen an Benutserienstaalengen, Beooboachtengsliesten, Artikkele usw. ferhinnerd. Betäätigje jädden dien Apsicht, ju Doatenboank tou speeren.',
'unlockdbtext'        => 'Dät Aphieuwjen fon ju Doatenboank-Speere wol aal Annerengen wier touläite. Bestäätige jädden dien Ousicht, ju Speerenge aptouhieuwjen.',
'lockconfirm'         => 'Jee, iek moate ju Doatenboank speere.',
'unlockconfirm'       => 'Jee, iek moate ju Doatenboank fräireeke.',
'lockbtn'             => 'Doatenboank speere',
'unlockbtn'           => 'Doatenboank fräireeke',
'locknoconfirm'       => 'Du hääst dät Bestäätigengsfäild nit markierd.',
'lockdbsuccesssub'    => 'Doatenboank wuude mäd Ärfoulch speerd',
'unlockdbsuccesssub'  => 'Doatenboank wuude mäd Ärfoulch fräiroat',
'lockdbsuccesstext'   => 'Ju Doatenboank wuude speerd.<br />
Reek jädden [[Special:UnlockDB|ju Doatenboank wier fräi]], so gau ju Fersuurgenge ousleeten is.',
'unlockdbsuccesstext' => 'Ju {{SITENAME}}-Doatenboank wuude fräiroat.',
'lockfilenotwritable' => 'Ju Doatenboank-Speerdoatäi is nit beschrieuwboar. Toun Speeren of Fräireeken fon ju Doatenboank mout ju foar dän Webserver beschrieuwboar weese.',
'databasenotlocked'   => 'Ju Doatenboank is nit speerd.',

# Move page
'move-page'                    => 'Ferschuuwe „$1“',
'move-page-legend'             => 'Siede ferschuuwe',
'movepagetext'                 => "Mäd dissen Formular koast du ne Siede tou n uur Noome ferschuuwe (touhoope mäd aal Versione).
Foar dän oolde Noome wäd ne Fäärelaitenge tou dän Näie iengjucht.
Du koast Fäärelaitengen, do ap dän Originoaltittel ferlinkje, automatisk korrigierje läite.
Fals du dit nit dääst, pröif ap [[Special:DoubleRedirects|dubbelde]] of [[Special:BrokenRedirects|defekte Fäärelaitengen]].
Du bäst deerfoar feroantwoudelk, dät Ferbiendengen noch altied waiwiese ätter wier jo dieden.

Beoachtje, dät ju Siede '''nit''' ferschäuwen wäd, wan dät al ne Siede mäd dän näie Tittel rakt, of et moaste weese dät ju loos is of ne Fäärelaitenge un dät ju nit neen allere Versione häd. Dät hat, dät du ne Siede ferschuuwe koast tou dän Noome, dän ju juust hiede, wan du die fersäin hiest. Un uk, dät du neen bestoundene Siede uurschrieuwe koast.

'''WOARSCHAUENGE!'''
Dit kon ne drastiske un uunferwachtede Ferannerenge reeke foar ne beljoowede Siede;
wääs die deeruum sicher, dät du do Konsequenzen deerfon iensjuchst, eer du fääre moakest.",
'movepagetalktext'             => "Ju touheerige Diskussionssiede wäd, sofier deer, mee ferschäuwen, '''of dät moast weese dät'''
* der bestoant al n Diskussionssiede mäd dän näie Noome
* du wäälst ju unnerstoundene Option ou.

In disse Falle moast du ju Siede, wan wonsked, fon Hounde ferschuuwe. Jädden dän '''näie''' Tittel unner '''Siel''' iendreege, deerunner ju Uumnaamenge jädden '''begründje'''.",
'movearticle'                  => 'Siede ferschuuwe:',
'moveuserpage-warning'         => "'''Woarschauenge:''' Du bäst tougong, ne Benutserssiede tou ferschuuwen. Betoank, dät deertruch bloot ju Benutsersiede ferschäuwen, man '''nit''' die Benutser uumenaamd wäd.",
'movenologin'                  => 'Du bäst nit anmälded',
'movenologintext'              => 'Du moast n registrierden Benutser un [[Special:UserLogin|anmälded]] weese, uum ne Siede ferschuuwe tou konnen.',
'movenotallowed'               => 'Du hääst neen Begjuchtigenge, Sieden tou ferschuuwen.',
'movenotallowedfile'           => 'Du hääst neen Begjuchtigenge, Doatäie tou ferschuuwen.',
'cant-move-user-page'          => 'Du hääst neen Begjuchtigenge, Benutserhaudsieden tou ferschuuwen.',
'cant-move-to-user-page'       => 'Du hääst nit ju  Begjuchtigenge, Sieden ap ne Benutsersiede tou ferschuuwen (mäd Uutnoame fon Benutserunnersieden).',
'newtitle'                     => 'Tou dän näie Tittel:',
'move-watch'                   => 'Disse Siede beooboachtje',
'movepagebtn'                  => 'Siede ferschuuwe',
'pagemovedsub'                 => 'Ferschuuwenge mäd Ärfoulch',
'movepage-moved'               => "'''Ju Siede „$1“ wuude ätter „$2“ ferschäuwen.'''",
'movepage-moved-redirect'      => 'Der wuud ne Fäärelaitenge moaked.',
'movepage-moved-noredirect'    => 'Dät Moakjen fon ne Fäärelaitenge wuud unnerdrukt.',
'articleexists'                => 'Dät rakt al n Siede mäd disse Noome, of uurs is die Noome dän du anroat hääst, nit toulät.
Fersäik jädden n uur Noome.',
'cantmove-titleprotected'      => 'Ju Ferschuuwenge kon nit truchfierd wäide, deeruum dät die Sieltittel speerd is uum tou moakjen.',
'talkexists'                   => 'Ju Siede sälwen wuude mäd Ärfoulch ferschäuwen, man ju Diskussionssiede nit, deer al een mäd dän näie Tittel bestoant. Glieke jädden do Inhoolde fon Hounde ou.',
'movedto'                      => 'ferschäuwen ätter',
'movetalk'                     => 'Ju Diskussionssiede mee ferschuuwe, wan muugelk.',
'move-subpages'                => 'Aal Unnersieden (bit tou $1) meeferschuuwe',
'move-talk-subpages'           => 'Aal Unnersieden fon Diskussionssieden (bit tou $1) meeferschuuwe',
'movepage-page-exists'         => 'Ju Siede „$1“ is al deer un kon nit automatisk uurschrieuwen wäide.',
'movepage-page-moved'          => 'Ju Siede „$1“ wuude ätter „$2“ ferschäuwen.',
'movepage-page-unmoved'        => 'Ju Siede „$1“ kuude nit ätter „$2“ ferschäuwen wäide.',
'movepage-max-pages'           => 'Ju Maximoalantaal fon $1 {{PLURAL:$1|Siede|Sieden}} wuude ferschäuwen. Aal wiedere Sieden konnen nit automatisk ferschäuwen wäide.',
'1movedto2'                    => 'häd "[[$1]]" ätter "[[$2]]" ferschäuwen',
'1movedto2_redir'              => 'häd „[[$1]]“ ätter „[[$2]]“ ferschäuwen un deerbie ne Fääreleedenge uurschrieuwen',
'move-redirect-suppressed'     => 'Fäärelaitenge unnerdrukt',
'movelogpage'                  => 'Ferschuuwengs-Logbouk',
'movelogpagetext'              => 'Dit is ne Lieste fon aal ferschäuwene Sieden.',
'movesubpage'                  => '{{PLURAL:$1|Unnersiede|Unnersieden}}',
'movesubpagetext'              => 'Disse Siede häd $1 {{PLURAL:$1|Unnersiede|Unnersieden}}.',
'movenosubpage'                => 'Disse Siede häd neen Unnersieden.',
'movereason'                   => 'Gruund:',
'revertmove'                   => 'tourääch ferschuuwe',
'delete_and_move'              => 'Läskje un ferschuuwe',
'delete_and_move_text'         => '==Sielartikkel is al deer, läskje?==
Die Artikkel "[[:$1]]" existiert al.
Moatest du him foar ju Ferschuuwenge läskje?',
'delete_and_move_confirm'      => 'Jee, Sielartikkel foar ju Ferschuuwenge läskje',
'delete_and_move_reason'       => 'Läsked uum Plats tou moakjen foar Ferschuuwenge',
'selfmove'                     => 'Uursproangs- un Sielnoome sunt gliek; ne Siede kon nit tou sik ferschäuwen wäide.',
'immobile-source-namespace'    => 'Sieden fon dän „$1“-Noomensruum konnen nit ferschäuwen wäide',
'immobile-target-namespace'    => 'Sieden konnen nit in dän „$1“-Noomensruum ferschäuwen wäide',
'immobile-target-namespace-iw' => 'Interwiki-Link is neen gultich Siel foar Siedenferschuuwengen.',
'immobile-source-page'         => 'Disse Siede is nit ferschuuwboar.',
'immobile-target-page'         => 'Der kon nit ap disse Sielsiede ferschäuwen wäide.',
'imagenocrossnamespace'        => 'Doatäie konnen nit uut dän {{ns:file}}-Noomensruum hääruut ferschäuwen wäide',
'imagetypemismatch'            => 'Ju näie Doatäifergratterenge is nit mäd ju oolde identisk',
'imageinvalidfilename'         => 'Die Siel-Doatäinoome is nit gultich',
'fix-double-redirects'         => 'Ätter dät Ferschuuwen dubbelde Fäärelaitengen aplööse',
'move-leave-redirect'          => 'Fäärelaitenge moakje',
'protectedpagemovewarning'     => "'''Woarschauenge:''' Disse Siede wuud speerd, so dät ju bloot fon Benutsere mäd Administratoregjuchte ferschäuwen wäide kon. Foar Information foulget die aktuelle Logboukiendraach:",
'semiprotectedpagemovewarning' => "'''Waiwiesenge:''' Disse Siede wuud speerd, so dät ju bloot fon anmäldede Benutsere ferschäuwen wäide kon. Foar Information foulget die aktuelle Logboukiendraach:",
'move-over-sharedrepo'         => '==Doatäi bestoant==
[[:$1]] bestoant in n gemeensoam nutsed Repositorium. Dät Ferschuuwen fon ne Doatäi tou dissen Tittel uurschrift ju gemeensoam nutsede Doatäi.',
'file-exists-sharedrepo'       => 'Die wäälde Doatäinoome wuud al in n gemeensoam nutsed Repositorium ferwoand.
Wääl n uur Noome.',

# Export
'export'            => 'Sieden exportierje',
'exporttext'        => 'Mäd disse Spezioalsiede koast du dän Täkst un ju Beoarbaidengshistorie fon eenpelde Sieden in ne XML-Doatäi exportierje.
Ju Doatäi kon in n uur MediaWiki-Wiki uur ju [[Special:Import|Importfunktion]] ienspield wäide.

Dräch dän of do äntspreekende Siedentittel(e) in dät foulgjende Textfäild ien (pro Riege älkemoal bloot foar een Siede).

Alternativ is die Export uk mäd de Syntax [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] muugelk, biespilswiese foar ju [[{{MediaWiki:Mainpage}}]].',
'exportcuronly'     => 'Bloot ju aktuälle Version fon de Siede exportierje',
'exportnohistory'   => "----
'''Waiwiesenge:''' Die Export fon komplette Versionsgeschichten is uut Performancegruunden bit ap fääre nit muugelk.",
'export-submit'     => 'Sieden exportierje',
'export-addcattext' => 'Sieden uut Kategorie bietouföigje:',
'export-addcat'     => 'Bietouföigje',
'export-addnstext'  => 'Sieden uut Noomensruum bietouföigje:',
'export-addns'      => 'Bietouföigje',
'export-download'   => 'As XML-Doatäi spiekerje',
'export-templates'  => 'Inklusive Foarloagen',
'export-pagelinks'  => 'Ferlinkede Sieden automatisk mee exportierje, bit tou ju Rekursionsjupte fon:',

# Namespace 8 related
'allmessages'                   => 'Aal Ättergjuchte',
'allmessagesname'               => 'Noome',
'allmessagesdefault'            => 'Standardtext',
'allmessagescurrent'            => 'Dissen Text',
'allmessagestext'               => 'Dit is ne Lieste fon aal System-Ättergjuchte do in dän MediaWiki-Noomenruum tou Ferföigenge stounde.
Besäik jädden [http://www.mediawiki.org/wiki/Localisation MediaWiki Localisation] un [http://translatewiki.net translatewiki.net], wan du mee-oarbaidje wolt an ju MediaWiki-Sortierenge.',
'allmessagesnotsupportedDB'     => 'Disse Spezioalsiede stoant nit tou Ferföigenge, deer ju uur dän Parameter <tt>$wgUseDatabaseMessages</tt> deaktivierd wuude.',
'allmessages-filter-legend'     => 'Sieuwe',
'allmessages-filter'            => 'Sieuwe foar anpaaseden Toustand:',
'allmessages-filter-unmodified' => 'Uunferannerd',
'allmessages-filter-all'        => 'Aal',
'allmessages-filter-modified'   => 'Ferannerd',
'allmessages-prefix'            => 'Präfix-Sieuwe:',
'allmessages-language'          => 'Sproake:',
'allmessages-filter-submit'     => 'Loos',

# Thumbnails
'thumbnail-more'           => 'fergratterje',
'filemissing'              => 'Doatäi failt',
'thumbnail_error'          => 'Failer bie dät Moakjen fon Foarschaubielde (Thumbnail): $1',
'djvu_page_error'          => 'DjVu-Siede buute dät Siedenberäk',
'djvu_no_xml'              => 'XML-Doaten konnen foar ju DjVu-Doatei nit ouruupen wäide',
'thumbnail_invalid_params' => 'Uungultige Thumbnail-Parameter',
'thumbnail_dest_directory' => 'Sielferteeknis kon nit moaked wäide.',
'thumbnail_image-type'     => 'Bieldetyp nit unnerstutsed',
'thumbnail_gd-library'     => 'Uunfulboodige Konfiguration fon ju GD-Bibliothek: Failjende Funktion $1',
'thumbnail_image-missing'  => 'Doatäi schient tou misjen: $1',

# Special:Import
'import'                     => 'Sieden importierje',
'importinterwiki'            => 'Transwiki-Import',
'import-interwiki-text'      => 'Wääl n Wiki un ne Siede toun Importierjen uut.
Do Versionsdoaten un Benutsernoomen blieuwe deerbie beheelden.
Aal Transwiki-Import-Aktione wäide in dät [[Special:Log/import|Import-Logbouk]] protokollierd.',
'import-interwiki-source'    => 'Wälle-Wiki/-Siede:',
'import-interwiki-history'   => 'Importier aal Versione fon disse Siede',
'import-interwiki-templates' => 'Aal Foarloagen iensluute',
'import-interwiki-submit'    => 'Import',
'import-interwiki-namespace' => 'Siel-Noomensruum:',
'import-upload-filename'     => 'Doatäinoome:',
'import-comment'             => 'Kommentoar:',
'importtext'                 => 'Ap disse Spezioalsiede konnen uur ju [[Special:Export|Exportfunktion]] in dän Wälwiki exportierde Sieden in dit Wiki importierd wäide.',
'importstart'                => 'Sieden importierje …',
'import-revision-count'      => '– {{PLURAL:$1|1 Version|$1 Versione}}',
'importnopages'              => 'Neen Sieden toun Importierjen anweesend.',
'importfailed'               => 'Import failsloain: $1',
'importunknownsource'        => 'Uunbekoande Importwälle',
'importcantopen'             => 'Importdoatäi kuude nit eepend wäide',
'importbadinterwiki'         => 'Falske Interwiki-Ferbiendenge',
'importnotext'               => 'Loos of neen Text',
'importsuccess'              => 'Import ousleeten!',
'importhistoryconflict'      => 'Deer bestounde al allere Versionen, do mäd disse kollidierje. Muugelkerwiese wuude ju Siede al eer importierd.',
'importnosources'            => 'Foar dän Transwiki Import sunt neen Wällen definierd un dät direkte Hoochleeden fon Versione is blokkierd.',
'importnofile'               => 'Deer is neen Importdoatäi hoochleeden wuuden.',
'importuploaderrorsize'      => 'Dät Hoochleeden fon ju Importdoatäi is failsloain. Ju Doatäi is gratter as ju maximoal toulätte Doatäigrööte.',
'importuploaderrorpartial'   => 'Dät Hoochleeden fon ju Importdoatäi is failsloain. Ju Doatäi wuude man deelwiese hoochleeden.',
'importuploaderrortemp'      => 'Dät Hoochleeden fon ju Importdoatäi is failsloain. N temporär Ferteeknis failt.',
'import-parse-failure'       => 'Failer bie dän XML-Import:',
'import-noarticle'           => 'Der wuude neen tou importierjenden Artikkel anroat!',
'import-nonewrevisions'      => 'Der sunt neen näie Versione toun Import foarhouden, aal Versione wuuden al eer importierd.',
'xml-error-string'           => '$1 Riege $2, Spalte $3, (Byte $4): $5',
'import-upload'              => 'XML-Doaten importierje',
'import-token-mismatch'      => 'Ferljus fon do Sessiondoaten. Fersäik et fon näien.',
'import-invalid-interwiki'   => 'Uut dän anroate Wiki is neen Import muugelk.',

# Import log
'importlogpage'                    => 'Import-Logbouk',
'importlogpagetext'                => 'Administrativen Import fon Sieden mäd Versionsgeschichte fon uur Wikis.',
'import-logentry-upload'           => 'häd „[[$1]]“ fon ne Doatäi importierd',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|Version|Versione}}',
'import-logentry-interwiki'        => 'häd „$1“ importierd (Transwiki)',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|Version|Versione}} fon $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Dien Benutsersiede',
'tooltip-pt-anonuserpage'         => 'Benutsersiede fon ju IP-Adresse fon ju uut du Annerengen truchfierst',
'tooltip-pt-mytalk'               => 'Dien Diskussionssiede',
'tooltip-pt-anontalk'             => 'Diskussion uur Annerengen fon disse IP-Adresse',
'tooltip-pt-preferences'          => 'Oaine Ienstaalengen',
'tooltip-pt-watchlist'            => 'Lieste fon do beooboachtede Sieden',
'tooltip-pt-mycontris'            => 'Lieste fon dien Biedraage',
'tooltip-pt-login'                => 'Jou ientoulogjen wäd wäil jädden blouked, man is neen Plicht.',
'tooltip-pt-anonlogin'            => 'Sik ientoulogjen wäd wäil jädden blouked, man is neen Plicht.',
'tooltip-pt-logout'               => 'Oumäldje',
'tooltip-ca-talk'                 => 'Diskussion uur dän Inhoold fon ju Siede',
'tooltip-ca-edit'                 => 'Siede beoarbaidje. Jädden foar dät Spiekerjen ju Foarschaufunktion benutsje.',
'tooltip-ca-addsection'           => 'Näi Stuk ounfange',
'tooltip-ca-viewsource'           => 'Disse Siede is schutsed. Die Wältext kon ankieked wäide.',
'tooltip-ca-history'              => 'Fröiere Versione fon disse Siede',
'tooltip-ca-protect'              => 'Disse Siede schutsje',
'tooltip-ca-unprotect'            => 'Disse Siede fräireeke',
'tooltip-ca-delete'               => 'Disse Siede läskje',
'tooltip-ca-undelete'             => 'Iendraage wier moakje, eer disse Siede läsked wuude',
'tooltip-ca-move'                 => 'Disse Siede ferschuuwe',
'tooltip-ca-watch'                => 'Disse Siede an dien Beoobachtengslieste touföigje',
'tooltip-ca-unwatch'              => 'Disse Siede fon ju persöönelke Beooboachtengslieste wächhoalje',
'tooltip-search'                  => '{{SITENAME}} truchsäike',
'tooltip-search-go'               => 'Gung fluks ätter ju Siede, ju der akroat dän ienroate Noome äntspräkt.',
'tooltip-search-fulltext'         => 'Säik ätter Sieden, do der dissen Text änthoolde',
'tooltip-p-logo'                  => 'Haudsiede',
'tooltip-n-mainpage'              => 'Haudsiede anwiese',
'tooltip-n-mainpage-description'  => 'Haudsiede besäike',
'tooltip-n-portal'                => 'Uur dät Portoal, wät du dwo koast, wier wät tou fienden is',
'tooltip-n-currentevents'         => 'Bäätergruundinformationen tou aktuelle Geböärnisse',
'tooltip-n-recentchanges'         => 'Lieste fon do lääste Annerengen in {{SITENAME}}.',
'tooltip-n-randompage'            => 'Toufällige Siede',
'tooltip-n-help'                  => 'Hälpesiede anwiese',
'tooltip-t-whatlinkshere'         => 'Lieste fon aal Sieden, do ap disse Siede linken',
'tooltip-t-recentchangeslinked'   => 'Lääste Annerengen an Sieden, do der fon hier ferlinked sunt',
'tooltip-feed-rss'                => 'RSS-Feed foar disse Siede',
'tooltip-feed-atom'               => 'Atom-Feed foar disse Siede',
'tooltip-t-contributions'         => 'Lieste fon do Biedraage fon dissen Benutser ankiekje',
'tooltip-t-emailuser'             => 'Een E-Mail an dissen Benutser seende',
'tooltip-t-upload'                => 'Doatäie hoochleede',
'tooltip-t-specialpages'          => 'Lieste fon aal Spezialsieden',
'tooltip-t-print'                 => 'Drukansicht fon disse Siede',
'tooltip-t-permalink'             => 'Duurhafte Ferbiendenge tou disse Siedenversion',
'tooltip-ca-nstab-main'           => 'Siedeninhoold anwiese',
'tooltip-ca-nstab-user'           => 'Benutsersiede anwiese',
'tooltip-ca-nstab-media'          => 'Mediendoatäiesiede anwiese',
'tooltip-ca-nstab-special'        => 'Dit is ne Spezialsiede. Ju kon nit ferannerd wäide.',
'tooltip-ca-nstab-project'        => 'Projektsiede anwiese',
'tooltip-ca-nstab-image'          => 'Doatäisiede anwiese',
'tooltip-ca-nstab-mediawiki'      => 'MediaWiki-Systemtext anwiese',
'tooltip-ca-nstab-template'       => 'Foarloage anwiese',
'tooltip-ca-nstab-help'           => 'Hälpesiede anwiese',
'tooltip-ca-nstab-category'       => 'Kategoriesiede anwiese',
'tooltip-minoredit'               => 'Disse Annerenge as littek markierje.',
'tooltip-save'                    => 'Annerengen spiekerje',
'tooltip-preview'                 => 'Foarschau fon do Annerengen an disse Siede. Jädden foar dät Spiekerjen benutsje!',
'tooltip-diff'                    => 'Wiest Annerengen an ju Text tabellarisk an',
'tooltip-compareselectedversions' => 'Unnerscheede twiske two uutwäälde Versione fon disse Siede ferglieke.',
'tooltip-watch'                   => 'Disse Siede beooboachtje',
'tooltip-recreate'                => 'Wier häärstaale',
'tooltip-upload'                  => 'Hoochleeden startje',
'tooltip-rollback'                => 'moaket aal lääste Annerengen fon ju Siede, do der fon dän glieke Benutser moaked sunt, truch aan Klik tounichte.',
'tooltip-undo'                    => 'moaket bloot disse eene Annerenge tounichte un wiest dät Resultoat in ju Foarschau an, deermäd in ju Touhoopefoatengsriege ne Begruundenge ounroat wäide kon.',

# Stylesheets
'common.css'   => '/** CSS an disse Steede wirket sik ap aal Skins uut */',
'monobook.css' => '/* Littikschrieuwen nit twinge */',

# Scripts
'common.js'   => '/* Älk JavaScript hier wäd foar aal Benutsere foar älke Siede leeden. */',
'monobook.js' => '/* Ferallerd; benutsje insteede deerfon [[MediaWiki:common.js]] */',

# Metadata
'nodublincore'      => 'Dublin-Core-RDF-Metadoaten sunt foar dissen Server deaktivierd.',
'nocreativecommons' => 'Creative-Commons-RDF-Metadoaten sunt foar dissen Server deaktivierd.',
'notacceptable'     => 'Die Wiki-Server kon do Doaten foar dien Uutgoawe-Reewe nit apberaitje.',

# Attribution
'anonymous'        => '{{PLURAL:$1|Anonymen Benutser|Anonyme Benutsere}} ap {{SITENAME}}',
'siteuser'         => '{{SITENAME}}-Benutser $1',
'anonuser'         => 'Anonymen {{SITENAME}}-Benutser $1',
'lastmodifiedatby' => 'Disse Siede wuude toulääst annerd uum $2, $1 fon $3.',
'othercontribs'    => 'Basierd ap ju Oarbaid fon $1.',
'others'           => 'uur',
'siteusers'        => '{{SITENAME}} {{PLURAL:$2|Benutser|Benutsere}} $1',
'anonusers'        => '{{PLURAL:$2|anonymen|anonyme}} {{SITENAME}}-{{PLURAL:$2|Benutser|Benutsere}} $1',
'creditspage'      => 'Siedenstatistik',
'nocredits'        => 'Foar disse Siede sunt neen Informationen deer.',

# Spam protection
'spamprotectiontitle' => 'Spamschutsfilter',
'spamprotectiontext'  => 'Ju Siede, ju du spiekerje wolt, wuude fon ju Spamschutssieuwe blokkierd. Dät lait woarschienelk an ne Ferbiendenge ätter ne fertoachte externe Siede.',
'spamprotectionmatch' => "'''Die foulgjende Text wuude fon uus Spam-Filter fuunen: ''$1'''''",
'spambot_username'    => 'MediaWiki Spam-Süüwerenge',
'spam_reverting'      => 'Lääste Version sunner Links tou $1 wier häärstoald.',
'spam_blanking'       => 'Aal Versione äntheelden Links tou $1, scheenmoaked.',

# Info page
'infosubtitle'   => 'Siedeninformation',
'numedits'       => 'Antaal fon do Artikkelversione: $1',
'numtalkedits'   => 'Antaal fon do Diskussionsversione: $1',
'numwatchers'    => 'Antaal fon do Beooboachtere: $1',
'numauthors'     => 'Antaal fon do Artikkelautore: $1',
'numtalkauthors' => 'Antaal fon do Diskutante: $1',

# Math options
'mw_math_png'    => 'Altied as PNG deerstaale',
'mw_math_simple' => 'Eenfache TeX as HTML deerstaale, uurs PNG',
'mw_math_html'   => 'Wan muugelk as HTML deerstaale, uurs PNG',
'mw_math_source' => 'As TeX beläite (foar Textbrowsere)',
'mw_math_modern' => 'Antouräiden foar moderne Browsere',
'mw_math_mathml' => 'MathML',

# Math errors
'math_failure'          => 'Parser-Failer',
'math_unknown_error'    => 'Uunbekoande Failer',
'math_unknown_function' => 'Uunbekoande Funktion',
'math_lexing_error'     => "'Lexing'-Failer",
'math_syntax_error'     => 'Syntaxfailer',
'math_image_error'      => 'ju PNG-Konvertierenge sluuch fail',
'math_bad_tmpdir'       => 'Kon dät Temporärferteeknis foar mathematiske Formeln nit anlääse of beschrieuwe.',
'math_bad_output'       => 'Kon dät Sielferteeknis foar mathematiske Formeln nit anlääse of beschrieuwe.',
'math_notexvc'          => 'Dät texvc-Program kon nit fuunen wäide. Beoachte jädden math/README.',

# Patrolling
'markaspatrolleddiff'                 => 'As pröiwed markierje',
'markaspatrolledtext'                 => 'Dissen Artikkel as pröiwed markierje',
'markedaspatrolled'                   => 'As pröiwed markierd',
'markedaspatrolledtext'               => 'Ju uutwäälde Version fon [[:$1|S1]] wuude as wröiged markierd.',
'rcpatroldisabled'                    => 'Pröiwenge fon do lääste Annerengen speerd',
'rcpatroldisabledtext'                => 'Ju Pröiwenge fon do lääste Annerengen ("Recent Changes Patrol") is apstuuns speerd.',
'markedaspatrollederror'              => 'Markierenge as „kontrollierd“ nit muugelk.',
'markedaspatrollederrortext'          => 'Du moast ne Siedenannerenge uutwääle.',
'markedaspatrollederror-noautopatrol' => 'Et is nit ferlööwed, oaine Beoarbaidengen as kontrollierd tou markierjen.',

# Patrol log
'patrol-log-page'      => 'Kontrol-Logbouk',
'patrol-log-header'    => 'Dit is dät Kontroll-Logbouk.',
'patrol-log-line'      => 'häd $1 fon $2 as kontrollierd markierd $3',
'patrol-log-auto'      => '(automatisk)',
'patrol-log-diff'      => 'Version $1',
'log-show-hide-patrol' => 'Kontroll-Logbouk $1',

# Image deletion
'deletedrevision'                 => 'Oolde Version $1 läsked',
'filedeleteerror-short'           => 'Failer bie dät Doatäi-Läskjen: $1',
'filedeleteerror-long'            => 'Bie dät Doatäi-Läskjen wuuden Failere fääststoald:

$1',
'filedelete-missing'              => 'Ju Doatäi „$1“ kon nit läsked wäide, deer ju nit bestoant.',
'filedelete-old-unregistered'     => 'Ju ounroate Doatäi-Version „$1“ is nit in ju Doatenboank deer.',
'filedelete-current-unregistered' => 'Ju ounroate Doatäi „$1“ is nit in ju Doatenboank deer.',
'filedelete-archive-read-only'    => 'Dät Archiv-Ferteeknis „$1“ is foar dän Webserver nit beschrieuwboar.',

# Browsing diffs
'previousdiff' => '← Tou ne allere Version',
'nextdiff'     => 'Tou ne näiere Version →',

# Media information
'mediawarning'         => "'''Woarschauenge:''' Disse Oard fon Doatäi kon n schoadelken Programcode änthoolde. Truch dät Deelleeden un Eepenjen fon disse Doatäi kon dän Computer Schoade toubroacht wäide.",
'imagemaxsize'         => "Maximoale Bieldegrööte:<br />''(foar Doatäibeschrieuwengssieden)''",
'thumbsize'            => 'Grööte fon do Foarschaubielden (thumbnails):',
'widthheightpage'      => '$1×$2, {{PLURAL:$3|1 Siede|$3 Sieden}}',
'file-info'            => '(Doatäigrööte: $1, MIME-Typ: $2)',
'file-info-size'       => '($1 × $2 Pixel, Doatäigrööte: $3, MIME-Typ: $4)',
'file-nohires'         => '<small>Neen haagere Aplöösenge foarhounden.</small>',
'svg-long-desc'        => '(SVG-Doatäi, Basisgrööte: $1 × $2 Pixel, Doatäigrööte: $3)',
'show-big-image'       => 'Bielde in hooge Aplöösenge',
'show-big-image-thumb' => '<small>Grööte fon disse Foarschau: $1 × $2 Pixel</small>',
'file-info-gif-looped' => 'Eendloos-Strik',
'file-info-gif-frames' => '$1 {{PLURAL:$1|Bielde|Bielden}}',

# Special:NewFiles
'newimages'             => 'Näie Bielden',
'imagelisttext'         => "Hier is ne Lieste fon '''$1''' {{PLURAL:$1|Doatäi|Doatäie}}, sortierd $2.",
'newimages-summary'     => 'Disse Spezioalsiede wiest do toulääst hoochleedene Doatäie an.',
'newimages-legend'      => 'Filter',
'newimages-label'       => 'Doatäinoome (of n Paat deerfon):',
'showhidebots'          => '(Bots $1)',
'noimages'              => 'neen Bielden fuunen.',
'ilsubmit'              => 'Säik',
'bydate'                => 'ätter Doatum',
'sp-newimages-showfrom' => 'Wies näie Doatäie, ounfangend mäd $1, $2 Uure',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'hours-abbrev' => 'U',

# Bad image list
'bad_image_list' => 'Dät Formoat is as foulget:

Bloot Liestnummere (Riegen, do der mäd n * ounfange), wäide betrachted.
As eerste ap ne Riege mout ne Ferbiendenge ap ne nit wonskede Doatäi stounde.
Deerap foulgjende Siedenferbiendengen in jusälge Riege wäide as Uutnoamen betrachted, dät sunt Sieden wierap ju Doatäi daach ärschiene duur.',

# Metadata
'metadata'          => 'Metadoaten',
'metadata-help'     => 'Disse Doatäi änthaalt wiedere Informatione, do in de Räägel von dän Digitoalkamera of dän ferwoanden Scanner stammen dwo.
Truch ätterdraine Beoarbaidenge fon ju Originoaldoatäi konnen eenige Details annerd wuuden weese.',
'metadata-expand'   => 'Wiedere Details ienbländje',
'metadata-collapse' => 'Details uutbländje',
'metadata-fields'   => 'Do foulgjende Fäildere fon do EXIF-Metadoaten in disse Media Wiki-Ättergjucht wäide ap Bieldbeschrieuwengssieden anwiesd;
wiedere standdoardmäitich "ienklapte" Details konnen anwiesd wäide.
* make
* model
* fnumber
* isospeedratings
* datetimeoriginal
* exposuretime
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Bratte',
'exif-imagelength'                 => 'Laangte',
'exif-bitspersample'               => 'Bits pro Faawenkomponente',
'exif-compression'                 => 'Oard fon ju Kompression',
'exif-photometricinterpretation'   => 'Pixeltouhoopesättenge',
'exif-orientation'                 => 'Kamera-Uutgjuchtenge',
'exif-samplesperpixel'             => 'Antaal Komponente',
'exif-planarconfiguration'         => 'Doatenuutgjuchtenge',
'exif-ycbcrsubsampling'            => 'Subsampling Rate fon Y bit C',
'exif-ycbcrpositioning'            => 'Y un C Positionierenge',
'exif-xresolution'                 => 'Horizontoale Aplöösenge',
'exif-yresolution'                 => 'Vertikoale Aplöösenge',
'exif-resolutionunit'              => 'Mäite-Eenhaid fon ju Aplöösenge',
'exif-stripoffsets'                => 'Bieldedoatenfersät',
'exif-rowsperstrip'                => 'Antaal Riegen pro Striepe',
'exif-stripbytecounts'             => 'Bytes pro komprimierten Striep',
'exif-jpeginterchangeformat'       => 'Offset tou JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Gratte fon do JPEG SOI-Doaten in Bytes',
'exif-transferfunction'            => 'Uurdreegengsfunktion',
'exif-whitepoint'                  => 'Manuell mäd Meetenge',
'exif-primarychromaticities'       => 'Chromatizitäte fon primäre Faawen',
'exif-ycbcrcoefficients'           => 'YCbCr-Koeffiziente',
'exif-referenceblackwhite'         => 'Swot/Wiet-Referenzpunkte',
'exif-datetime'                    => 'Spiekertiedpunkt',
'exif-imagedescription'            => 'Bieldetittel',
'exif-make'                        => 'Häärstaaler',
'exif-model'                       => 'Modäl',
'exif-software'                    => 'Software',
'exif-artist'                      => 'Photograph',
'exif-copyright'                   => 'Uurheebergjuchte',
'exif-exifversion'                 => 'Exif-Version',
'exif-flashpixversion'             => 'unnerstöände Flashpix-Version',
'exif-colorspace'                  => 'Faawenruum',
'exif-componentsconfiguration'     => 'Betjuudenge fon älke Komponente',
'exif-compressedbitsperpixel'      => 'Komprimierde Bits pro Pixel',
'exif-pixelydimension'             => 'Gultige Bieldebratte',
'exif-pixelxdimension'             => 'Gultige Bieldehöchte',
'exif-makernote'                   => 'Häärstaalernotiz',
'exif-usercomment'                 => 'Benutserkommentoare',
'exif-relatedsoundfile'            => 'Touheerige Toondoatäi',
'exif-datetimeoriginal'            => 'Ärfoatengstiedpunkt',
'exif-datetimedigitized'           => 'Digitalisierengstiedpunkt',
'exif-subsectime'                  => 'Spiekertiedpunkt',
'exif-subsectimeoriginal'          => 'Ärfoatengstiedpunkt',
'exif-subsectimedigitized'         => 'Digitoalisierengstiedpunkt',
'exif-exposuretime'                => 'Beljoachtengsduur',
'exif-exposuretime-format'         => '$1 Sekunden ($2)',
'exif-fnumber'                     => 'Blände',
'exif-exposureprogram'             => 'Beljuchtengsprogram',
'exif-spectralsensitivity'         => 'Beljoachtengstiedwäid',
'exif-isospeedratings'             => 'Film- of Sensorämpfiendelkaid (ISO)',
'exif-oecf'                        => 'Optoelektroniske Uumreekenengsfaktor',
'exif-shutterspeedvalue'           => 'Beluchtengstiedwäid',
'exif-aperturevalue'               => 'Bländenwäid',
'exif-brightnessvalue'             => 'Ljoachtegaidswäid',
'exif-exposurebiasvalue'           => 'Beljuchtengsfoargoawe',
'exif-maxaperturevalue'            => 'Grootste Blände',
'exif-subjectdistance'             => 'Fierte',
'exif-meteringmode'                => 'Meetferfoaren',
'exif-lightsource'                 => 'Luchtwälle',
'exif-flash'                       => 'Blits (Loai!)',
'exif-focallength'                 => 'Baadenwiete',
'exif-subjectarea'                 => 'Beräk',
'exif-flashenergy'                 => 'Blitsstäärke',
'exif-spatialfrequencyresponse'    => 'Ruumelke Frequenz-Reaktion',
'exif-focalplanexresolution'       => 'Sensoraplöösenge horizontoal',
'exif-focalplaneyresolution'       => 'Sensoraplöösenge vertikoal',
'exif-focalplaneresolutionunit'    => 'Eenhaid fon Sensoraplöösenge',
'exif-subjectlocation'             => 'Motivstandploats',
'exif-exposureindex'               => 'Beljuchtengsindex',
'exif-sensingmethod'               => 'Meetmethode',
'exif-filesource'                  => 'Wälle fon ju Doatäi',
'exif-scenetype'                   => 'Scenetyp',
'exif-cfapattern'                  => 'CFA-Muster',
'exif-customrendered'              => 'Benutserdefinierde Bieldeferoarbaidenge',
'exif-exposuremode'                => 'Beljuchtengsmodus',
'exif-whitebalance'                => 'Wiet-Ougliek',
'exif-digitalzoomratio'            => 'Digitoalzoom',
'exif-focallengthin35mmfilm'       => 'Baadenwiete',
'exif-scenecapturetype'            => 'Apnoame-Oard',
'exif-gaincontrol'                 => 'Ferstäärkenge',
'exif-contrast'                    => 'Kontrast',
'exif-saturation'                  => 'Säädigenge',
'exif-sharpness'                   => 'Schäärpegaid',
'exif-devicesettingdescription'    => 'Reewen-Ienstaalenge',
'exif-subjectdistancerange'        => 'Motivfierte',
'exif-imageuniqueid'               => 'Bielde-ID',
'exif-gpsversionid'                => 'GPS-Tag-Version',
'exif-gpslatituderef'              => 'noudelke of suudelke Bratte',
'exif-gpslatitude'                 => 'Geografiske Bratte',
'exif-gpslongituderef'             => 'aastelke of wäästelke Laangte',
'exif-gpslongitude'                => 'Geografiske Laangte',
'exif-gpsaltituderef'              => 'Beluukengshöchte',
'exif-gpsaltitude'                 => 'Höchte',
'exif-gpstimestamp'                => 'GPS-Tied',
'exif-gpssatellites'               => 'Foar ju Meetenge benutsede Satellite',
'exif-gpsstatus'                   => 'Ämpfangerstoatus',
'exif-gpsmeasuremode'              => 'Meetferfoaren',
'exif-gpsdop'                      => 'Meetpräzision',
'exif-gpsspeedref'                 => 'Gauegaids-Eenhaid',
'exif-gpsspeed'                    => 'Gauegaid fon dän GPS-Ämpfanger',
'exif-gpstrackref'                 => 'Referenz foar Bewäägengsgjuchte',
'exif-gpstrack'                    => 'Bewäägengsgjuchte',
'exif-gpsimgdirectionref'          => 'Referenz foar ju Uutgjuchtenge fon ju Bielde',
'exif-gpsimgdirection'             => 'Bieldegjuchte',
'exif-gpsmapdatum'                 => 'Geodätiske Apnoame-Doaten benutsed',
'exif-gpsdestlatituderef'          => 'Referenz foar ju Bratte',
'exif-gpsdestlatitude'             => 'Bratte',
'exif-gpsdestlongituderef'         => 'Referenz foar ju Laangte',
'exif-gpsdestlongitude'            => 'Laangte',
'exif-gpsdestbearingref'           => 'Referenz foar Motivgjuchte',
'exif-gpsdestbearing'              => 'Motivgjuchte',
'exif-gpsdestdistanceref'          => 'Referenz foar Motivfierte',
'exif-gpsdestdistance'             => 'Motivfierte',
'exif-gpsprocessingmethod'         => 'Noome fon dät GPS-Ferfoaren',
'exif-gpsareainformation'          => 'Noome fon dät GPS-Gestrich',
'exif-gpsdatestamp'                => 'GPS-Doatum',
'exif-gpsdifferential'             => 'GPS-Differentioalkorrektur',

# EXIF attributes
'exif-compression-1' => 'Uunkomprimierd',

'exif-unknowndate' => 'Uunbekoand Doatum',

'exif-orientation-1' => 'Normoal',
'exif-orientation-2' => 'Horizontoal uumewoand',
'exif-orientation-3' => 'Uum 180° uumewoand',
'exif-orientation-4' => 'Vertikoal uumewoand',
'exif-orientation-5' => 'Juun dän Klokkenwiesersin uum 90° troald un vertikoal uumewoand',
'exif-orientation-6' => 'Uum 90° in Klokkenwiesersin troald',
'exif-orientation-7' => 'Uum 90° in Klokkenwiesersin troald un vertikoal uumewoand',
'exif-orientation-8' => 'Uum 90° juun dän Klokkenwiesersin troald',

'exif-planarconfiguration-1' => 'Groafformoat',
'exif-planarconfiguration-2' => 'Planoarformoat',

'exif-componentsconfiguration-0' => 'Bestoant nit',

'exif-exposureprogram-0' => 'Uunbekoand',
'exif-exposureprogram-1' => 'Manuäl',
'exif-exposureprogram-2' => 'Standoardprogram',
'exif-exposureprogram-3' => 'Tiedautomatik',
'exif-exposureprogram-4' => 'Bländenautomatik',
'exif-exposureprogram-5' => 'Kreativprogram mäd Befoarluukenge fon ne hooge Schäärpendjupte',
'exif-exposureprogram-6' => 'Aktion-Program mäd Befoarluukenge fon ne kute Beljoachtengstied',
'exif-exposureprogram-7' => 'Portrait-Program',
'exif-exposureprogram-8' => 'Londskupsapnoamen',

'exif-subjectdistance-value' => '$1 Meters',

'exif-meteringmode-0'   => 'Uunbekoand',
'exif-meteringmode-1'   => 'in n Truchsleek',
'exif-meteringmode-2'   => 'Middezentrierd',
'exif-meteringmode-3'   => 'Punktmeetenge',
'exif-meteringmode-4'   => 'Moorfachpunktmeetenge',
'exif-meteringmode-5'   => 'Muster',
'exif-meteringmode-6'   => 'Bieldedeel',
'exif-meteringmode-255' => 'Uur',

'exif-lightsource-0'   => 'Uunbekoand',
'exif-lightsource-1'   => 'Deegeslucht',
'exif-lightsource-2'   => 'Fluoreszierjend',
'exif-lightsource-3'   => 'Gloilaampe',
'exif-lightsource-4'   => 'Blits (Loai)',
'exif-lightsource-9'   => 'Fluch Weeder',
'exif-lightsource-10'  => 'beleekene Luft',
'exif-lightsource-11'  => 'Schaad',
'exif-lightsource-12'  => 'Deegeslucht fluoreszierjend (D 5700–7100 K)',
'exif-lightsource-13'  => 'Deegeswiet fluoreszierjend (N 4600–5400 K)',
'exif-lightsource-14'  => 'Kooldwiet fluoreszierjend (W 3900–4500 K)',
'exif-lightsource-15'  => 'Wiet fluoreszierjend (WW 3200–3700 K)',
'exif-lightsource-17'  => 'Standoardlucht A',
'exif-lightsource-18'  => 'Standoardlucht B',
'exif-lightsource-19'  => 'Standoardlucht C',
'exif-lightsource-24'  => 'ISO Studio Kunstlucht',
'exif-lightsource-255' => 'Uur Luchtwälle',

# Flash modes
'exif-flash-fired-0'    => 'naan Lai',
'exif-flash-fired-1'    => 'Lai uutlöösd',
'exif-flash-return-0'   => 'Lai soant neen Doaten',
'exif-flash-return-2'   => 'neen Reflexion fon dän Lai fääststoald',
'exif-flash-return-3'   => 'Reflexion fon dän Lai fääststoald',
'exif-flash-mode-1'     => 'twoangen laien',
'exif-flash-mode-2'     => 'Lai ouschalted',
'exif-flash-mode-3'     => 'Automatik',
'exif-flash-function-1' => 'Neen Laifunktion',
'exif-flash-redeye-1'   => 'Roodoogene-Reduktion',

'exif-focalplaneresolutionunit-2' => 'Tuume',

'exif-sensingmethod-1' => 'Uundefinierd',
'exif-sensingmethod-2' => 'Een-Chip-Faawesensor',
'exif-sensingmethod-3' => 'Twoo-Chip-Faawesensor',
'exif-sensingmethod-4' => 'Trjoo-Chip-Faawesensor',
'exif-sensingmethod-5' => 'Color sequential area sensor',
'exif-sensingmethod-7' => 'Trilinearen Sensor',
'exif-sensingmethod-8' => 'Color sequential linear sensor',

'exif-scenetype-1' => 'Normoal',

'exif-customrendered-0' => 'Standoard',
'exif-customrendered-1' => 'Benutserdefinierd',

'exif-exposuremode-0' => 'Automatiske Beluchtenge',
'exif-exposuremode-1' => 'Manuelle Beluchtenge',
'exif-exposuremode-2' => 'Beluchtengsriege',

'exif-whitebalance-0' => 'Automatisk',
'exif-whitebalance-1' => 'Manuäl',

'exif-scenecapturetype-0' => 'Standoard',
'exif-scenecapturetype-1' => 'Londskup',
'exif-scenecapturetype-2' => 'Portrait',
'exif-scenecapturetype-3' => 'Noachtszene',

'exif-gaincontrol-0' => 'Neen',
'exif-gaincontrol-1' => 'Min',
'exif-gaincontrol-2' => 'High gain up',
'exif-gaincontrol-3' => 'Low gain down',
'exif-gaincontrol-4' => 'High gain down',

'exif-contrast-0' => 'Normoal',
'exif-contrast-1' => 'Swäk',
'exif-contrast-2' => 'Stäärk',

'exif-saturation-0' => 'Normoal',
'exif-saturation-1' => 'Min Säädigenge',
'exif-saturation-2' => 'Hooge Säädigenge',

'exif-sharpness-0' => 'Normoal',
'exif-sharpness-1' => 'Schäärpegaid min',
'exif-sharpness-2' => 'Schäärpegaid stäärk',

'exif-subjectdistancerange-0' => 'Uunbekoand',
'exif-subjectdistancerange-1' => 'Makro',
'exif-subjectdistancerange-2' => 'Nai',
'exif-subjectdistancerange-3' => 'Fier',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'noudelke Bratte',
'exif-gpslatitude-s' => 'suudelke Bratte',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'aastelke Laangte',
'exif-gpslongitude-w' => 'wäästelke Laangte',

'exif-gpsstatus-a' => 'Meetenge lapt',
'exif-gpsstatus-v' => 'Measurement interoperability',

'exif-gpsmeasuremode-2' => '2-dimensionoale Meetenge',
'exif-gpsmeasuremode-3' => '3-dimensionoale Meetenge',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'km/h',
'exif-gpsspeed-m' => 'mph',
'exif-gpsspeed-n' => 'Knätte',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Wuddelke Gjuchte',
'exif-gpsdirection-m' => 'Magnetiske Gjuchte',

# External editor support
'edit-externally'      => 'Disse Doatäi mäd n extern Program beoarbaidje',
'edit-externally-help' => '(Sjuch do [http://www.mediawiki.org/wiki/Manual:External_editors Installationsanwiesengen] foar wiedere Informatione)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'aal',
'imagelistall'     => 'aal',
'watchlistall2'    => 'aal',
'namespacesall'    => 'aal',
'monthsall'        => 'aal',
'limitall'         => 'aal',

# E-mail address confirmation
'confirmemail'              => 'Email-Adrässe bestäätigje',
'confirmemail_noemail'      => 'Du hääst neen gultige E-Mail-Adresse in dien [[Special:Preferences|persöönelke Ienstaalengen]] iendrain.',
'confirmemail_text'         => '{{SITENAME}} ärfoardert, dät du dien E-Mail-Adresse bestäätigest (authentifizierje), eer du do fergratterde E-Mail-Funktione benutsje koast. Truch n Klik ap ju Schaltfläche unner wäd ne E-Mail an die fersoand. Disse E-Mail änthaalt ne Ferbiendenge mäd n Bestäätigengs-Code. Truch Klikken ap disse Ferbiendenge wäd bestäätiged, dät dien E-Mail-Adresse gultich is.',
'confirmemail_pending'      => 'Der wuude die al n Bestäätigengs-Code per E-Mail tousoand. Wan du dien Benutserkonto eerste knu moaked hääst, täif noch n poor Minuten ap ju E-Mail, eer du n näien Code anfoarderst.',
'confirmemail_send'         => 'Bestäätigengscode touseende',
'confirmemail_sent'         => 'Bestäätigengs-E-Mail wuude fersoand.',
'confirmemail_oncreate'     => 'N Bestäätigengs-Code wuude an dien E-Mail-Adresse soand. Dissen Code is foar ju Anmäldenge nit nöödich, man daach wäd er tou ju Aktivierenge fon do E-Mail-Funktione binne dän Wiki bruukt.',
'confirmemail_sendfailed'   => '{{SITENAME}} kuud ju Bestäätigengs-E-Mail nit an die ferseende.
Wröich ju E-Mail-Adresse ap uungultige Teekene.

Touräächmäldenge fon dän Mailserver: $1',
'confirmemail_invalid'      => 'Uungultigen Bestäätigengscode. Eventuell is die Code al wier uungultich wuuden.',
'confirmemail_needlogin'    => 'Du moast die $1, uum dien E-Mail-Adresse tou bestäätigjen.',
'confirmemail_success'      => 'Dien E-Mail-Adresse wuude mäd Ärfoulch bestäätiged. Du koast die nu ienlogje.',
'confirmemail_loggedin'     => 'Dien E-Mail-Adresse wuude mäd Ärfoulch bestäätiged.',
'confirmemail_error'        => 'Et roat n Failer bie ju Bestäätigenge fon dien E-Mail-Adresse.',
'confirmemail_subject'      => '[{{SITENAME}}] - Bestäätigenge fon ju E-Mail-Adresse',
'confirmemail_body'         => 'Moin,

wäl mäd ju IP-Adresse $1, woarschienelk du sälwen, häd dät Benutserkonto "$2" in {{SITENAME}} registrierd.

Uum ju E-Mail-Funktion foar {{SITENAME}} (wier) tou aktivierjen un uum tou bestäätigjen, dät dit Benutserkonto wuddelk tou dien E-Mail-Adresse un deermäd tou die heert, eepenje ju foulgjende Web-Adresse:

$3

Schuul ju foarstoundende Adresse in dien E-Mail-Program uur moorere Riegen gunge, moast du ju eventuell mäd de Hounde in ju Adressriege fon din Web-Browser ienföigje.

Wan du dät naamde Benutserkonto *nit* registrierd hääst, foulgje dissen Link, uum dän Bestäätigengsprozess outoubreeken:

$5

Disse Bestäätigengscode is gultich bit $4.',
'confirmemail_body_changed' => 'Moin,

wäl mäd ju IP-Adresse $1, woarschienelk du sälwen, häd ju E-Mail-Adresse fon dät Benutserkonto "$2" ap {{SITENAME}} annerd.

Uum  tou bestäätigjen, dät dit Benutserkonto wuddelk tou  die heert un uum do E-Mail-Features ap {{SITENAME}} tou reaktivierjen, eepenje dissen Link:

$3

Fals dät Konto *nit* die heert, foulge dissen Link,
uum ju E-Mail-Adräs-Bestäätigenge outoubreeken:

$5

Disse Bestäätigengskode is gultich bit $4',
'confirmemail_invalidated'  => 'E-Mail-Adressbestäätigenge oubreeke',
'invalidateemail'           => 'E-Mail-Adressbestäätigenge oubreeke',

# Scary transclusion
'scarytranscludedisabled' => '[Interwiki-Ienbiendenge is deaktivierd]',
'scarytranscludefailed'   => '[Foarloagenienbiendenge foar $1 is misglukked]',
'scarytranscludetoolong'  => '[URL is tou loang]',

# Trackbacks
'trackbackbox'      => 'Trackbacks foar disse Siede:<br />
$1',
'trackbackremove'   => '([$1 läskje])',
'trackbacklink'     => 'Trackback',
'trackbackdeleteok' => 'Trackback wuude mäd Ärfoulch läsked.',

# Delete conflict
'deletedwhileediting' => 'Oachtenge: Disse Siede wuude al läsked, ätter dät du anfangd hiedest, hier tou beoarbaidjen!
Kiekje in dät [{{fullurl:Special:Log|type=delete&page=}}{{FULLPAGENAMEE}} Läsk-Logbouk] ätter,
wieruum ju Siede läsked wuude. Wan du ju Siede spiekerst, wäd ju näi anlaid.',
'confirmrecreate'     => "Benutser [[User:$1|$1]] ([[User talk:$1|Diskussion]]) häd disse Siede läsked, ätter dät du ounfangd hääst, ju tou beoarbaidjen. Ju Begruundenge lutte:
''$2''.
Bestäätigje, dät du disse Siede wuddelk näi moakje moatest.",
'recreate'            => 'Wierhäärstaale',

# action=purge
'confirm_purge_button' => 'OK',
'confirm-purge-top'    => 'Dän Cache fon disse Siede loosmoakje?',
'confirm-purge-bottom' => 'Moaket dän Cache fon ne Siede loos un twingt tou dät Anwiesen fon ju aktuelle Version.',

# Multipage image navigation
'imgmultipageprev' => '← foarige Siede',
'imgmultipagenext' => 'naiste Siede →',
'imgmultigo'       => 'OK',
'imgmultigoto'     => 'Gung tou Siede $1',

# Table pager
'ascending_abbrev'         => 'ap',
'descending_abbrev'        => 'fon',
'table_pager_next'         => 'Naiste Siede',
'table_pager_prev'         => 'Foarige Siede',
'table_pager_first'        => 'Eerste Siede',
'table_pager_last'         => 'Lääste Siede',
'table_pager_limit'        => 'Wies $1 Iendraage pro Siede',
'table_pager_limit_submit' => 'Loos',
'table_pager_empty'        => 'Neen Resultoate',

# Auto-summaries
'autosumm-blank'   => 'Ju Siede wuud loosmoaked.',
'autosumm-replace' => "Die Siedeninhoold wuude truch n uur Text ärsät: '$1'",
'autoredircomment' => 'Fäärelaited ätter [[$1]]',
'autosumm-new'     => 'Ju Siede wuud näi anlaid: „$1“',

# Live preview
'livepreview-loading' => 'Leede …',
'livepreview-ready'   => 'Leeden … Kloor!',
'livepreview-failed'  => 'Live-Foarschau nit muugelk! Benutsje ju normoale Foarschau.',
'livepreview-error'   => 'Ferbiendenge nit muugelk: $1 "$2". Benutsje ju normoale Foarschau.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Beoarbaidengen fon {{PLURAL:$1|ju lääste Sekunde|$1 do lääste Sekunden}} wäide in disse Lieste noch nit anwiesd.',
'lag-warn-high'   => 'Ap Gruund fon hooge Doatenboankuutläästenge wäide do Beoarbaidengen fon {{PLURAL:$1|ju lääste Sekunde|do lääste $1 Sekunden}} in disse Lieste noch nit anwiesd.',

# Watchlist editor
'watchlistedit-numitems'       => 'Dien Beooboachtengslieste änthaalt {{PLURAL:$1|1 Iendraach |$1 Iendraage}}, Diskussionssieden wäide nit täld.',
'watchlistedit-noitems'        => 'Dien Beooboachtengslieste is loos.',
'watchlistedit-normal-title'   => 'Beooboachtengslieste beoarbaidje',
'watchlistedit-normal-legend'  => 'Iendraage fon ju Beooboachtengslieste wächhoalje',
'watchlistedit-normal-explain' => 'Dit sunt do Iendraage fon dien Beooboachtengslieste. Uum Iendraage wächtouhoaljen, markier do litje Kasten ieuwenske do Iendraage un klik ap „Iendraage wächhoalje“. Du koast dien Beooboachtengslieste uk in dät [[Special:Watchlist/raw|Liestenformoat beoarbaidje]].',
'watchlistedit-normal-submit'  => 'Iendraage wächhoalje',
'watchlistedit-normal-done'    => '{{PLURAL:$1|1 Iendraach wuude|$1 Iendraage wuuden}} fon dien Beooboachtengslieste wächhoald:',
'watchlistedit-raw-title'      => 'Beooboachtengslieste in Liestenformoat beoarbaidje',
'watchlistedit-raw-legend'     => 'Beooboachtengslieste in Liestenformoat beoarbaidje',
'watchlistedit-raw-explain'    => 'Dit sunt do Iendraage fon dien Beooboachtengslieste in dät Liestenformoat. Do Iendraage konnen riegenwiese läsked of bietouföiged wäide.
	Pro Riege is aan Iendraach ferlööwed. Wan du kloor bäst, klik ap „Beooboachtengslieste spiekerje“.
	Du koast uk ju [[Special:Watchlist/edit|Standard-Beoarbaidengssiede]] benutsje.',
'watchlistedit-raw-titles'     => 'Iendraage:',
'watchlistedit-raw-submit'     => 'Beooboachtengslieste spiekerje',
'watchlistedit-raw-done'       => 'Dien Beooboachtengslieste wuude spiekerd.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 Iendraach wuude|$1 Iendraage wuuden}} bietouföiged:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 Iendraach wuude|$1 Iendraage wuuden}} wächhoald:',

# Watchlist editing tools
'watchlisttools-view' => 'Beooboachtengslieste: Annerengen',
'watchlisttools-edit' => 'normoal beoarbaidje',
'watchlisttools-raw'  => 'Liestenformoat beoarbaidje (Import/Export)',

# Core parser functions
'unknown_extension_tag' => 'Uunbekoanden Extension-Tag „$1“',
'duplicate-defaultsort' => 'Paas ap: Die Sortierengskoai „$2“ uurschrift dän toufoarne ferwoanden Koai „$1“.',

# Special:Version
'version'                          => 'Version',
'version-extensions'               => 'Installierde Ärwiederengen',
'version-specialpages'             => 'Spezioalsieden',
'version-parserhooks'              => 'Parser-Hooks',
'version-variables'                => 'Variablen',
'version-other'                    => 'Uurswät',
'version-mediahandlers'            => 'Medien-Handlere',
'version-hooks'                    => "Snitsteeden ''(Hooks)''",
'version-extension-functions'      => 'Funktionsaproupe',
'version-parser-extensiontags'     => "Parser-Ärwiederengen ''(tags)''",
'version-parser-function-hooks'    => 'Parser-Funktione',
'version-skin-extension-functions' => 'Skin-Ärwiederengs-Funktione',
'version-hook-name'                => 'Snitsteedennoome',
'version-hook-subscribedby'        => 'Aproup fon',
'version-version'                  => '(Version $1)',
'version-license'                  => 'Lizenz',
'version-software'                 => 'Installierde Software',
'version-software-product'         => 'Produkt',
'version-software-version'         => 'Version',

# Special:FilePath
'filepath'         => 'Doatäipaad',
'filepath-page'    => 'Doatäi:',
'filepath-submit'  => 'Paad säike',
'filepath-summary' => 'Mäd disse Spezialsiede lät sik die komplette Paad fon ju aktuelle Version fon ne Doatäi sunner Uumwai oufräigje. Ju anfräigede Doatäi wäd fluks deerstoald blw. mäd ju ferknätte Anweendenge started.',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Doatäi-Duplikoat-Säike',
'fileduplicatesearch-summary'  => 'Säike ätter Doatäi-Duplikoate ap Basis fon hieren Hash-Wäid.

Ju Iengoawe mout sunner dän Tousats „{{ns:file}}:“ geböäre.',
'fileduplicatesearch-legend'   => 'Säike ätter Duplikoate',
'fileduplicatesearch-filename' => 'Doatäinoome:',
'fileduplicatesearch-submit'   => 'Säike (016)',
'fileduplicatesearch-info'     => '$1 × $2 Pixel<br />Doatäigrööte: $3<br />MIME-Typ: $4',
'fileduplicatesearch-result-1' => 'Ju Doatäi „$1“ häd neen identiske Duplikoate.',
'fileduplicatesearch-result-n' => 'Ju Doatäi „$1“ häd {{PLURAL:$2|1 identisk Duplikoat|$2 identiske Duplikoate}}.',

# Special:SpecialPages
'specialpages'                   => 'Spezioalsieden',
'specialpages-note'              => '----
* Spezioalsieden foar Älkuneen
* <strong class="mw-specialpagerestricted">Spezioalsieden foar Benutsere mäd ärwiederde Gjuchte</strong>',
'specialpages-group-maintenance' => 'Fersuurgengsliesten',
'specialpages-group-other'       => 'Uur Spezioalsieden',
'specialpages-group-login'       => 'Anmäldje',
'specialpages-group-changes'     => 'Lääste Ferannerengen un Logbouke',
'specialpages-group-media'       => 'Medien',
'specialpages-group-users'       => 'Benutsere un Gjuchte',
'specialpages-group-highuse'     => 'Oafte benutsede Sieden',
'specialpages-group-pages'       => 'Liesten fon Sieden',
'specialpages-group-pagetools'   => 'Siedenreewen',
'specialpages-group-wiki'        => 'Systemdoaten un Reewen',
'specialpages-group-redirects'   => 'Fäärelaitjende Spezioalsieden',
'specialpages-group-spam'        => 'Spam-Reewen',

# Special:BlankPage
'blankpage'              => 'Loose Siede',
'intentionallyblankpage' => 'Disse Siede is apsichtelk sunner Inhoold. Ju wäd foar Benchmarks ferwoand.',

# External image whitelist
'external_image_whitelist' => '#Disse Riege nit ferannerje<pre>
#Unnerstoundend konnen Fragmente fon reguläre Uutdrukke (die Deel twiske do //) ienroat wäide.
#Disse wäide mäd do URLs fon Bielden uut externe Wällen ferglieked
#N positiven Fergliek fiert tou Anwiesenge fon ju Bielde, uurs wäd ju Bielde bloot as Link anwiesd
#Riegen, do der mäd n # ounfange, wäide as Kommentoar behonneld
#Der wäd nit twiske Groot- un Littikschrieuwenge unnerschat

#Fragmente fon reguläre Uutdrukke ätter disse Riege iendreege. Disse Riege nit ferannerje</pre>',

# Special:Tags
'tags'                    => 'Gultige Annerengsmarkierengen',
'tag-filter'              => '[[Special:Tags|Tag]]-Sieuwe:',
'tag-filter-submit'       => 'Sieuwe',
'tags-title'              => 'Marekierengen',
'tags-intro'              => 'Disse Siede wiest aal Markierengen, do foar Beoarbaidengen ferwoand wäide, as uk ju Betjuudenge deerfon.',
'tags-tag'                => 'Markierengsnoome',
'tags-display-header'     => 'Benaamenge ap do Annerengsliesten',
'tags-description-header' => 'Fulboodige Beschrieuwenge',
'tags-hitcount-header'    => 'Markierde Annerengen',
'tags-edit'               => 'beoarbaidje',
'tags-hitcount'           => '$1 {{PLURAL:$1|Annerenge|Annerengen}}',

# Database error messages
'dberr-header'      => 'Dit Wiki häd n Problem',
'dberr-problems'    => 'Äntscheeldenge. Disse Siede häd apstuuns techniske Meelasje.',
'dberr-again'       => 'Fersäik n poor Minuten tou täiwen un dan näi tou leeden.',
'dberr-info'        => '(Kon neen Ferbiendenge tou dän Doatenboank-Server moakje: $1)',
'dberr-usegoogle'   => 'Du kuust in ju Twisketied mäd Google säike.',
'dberr-outofdate'   => 'Beoachtje, dät die Säikindex fon uus Inhoolde ferallerd weese kon.',
'dberr-cachederror' => 'Dät Foulgjende is ne Kopie fon dän Cache fon ju anfoarderde Siede un kon ferallerd weese.',

# HTML forms
'htmlform-invalid-input'       => 'Mäd eenige Iengoawen rakt dät Probleme',
'htmlform-select-badoption'    => 'Die anroate Wäid is neen gultige Option.',
'htmlform-int-invalid'         => 'Die anroate Wäid is neen Gans-Taal.',
'htmlform-float-invalid'       => 'Die anroate Wäid is neen Taal.',
'htmlform-int-toolow'          => 'Die anroate Wäid is unner dät Minimum fon $1',
'htmlform-int-toohigh'         => 'Die anroate Wäid is buppe dät Maximum fon $1',
'htmlform-submit'              => 'Uurdreege',
'htmlform-reset'               => 'Annerengen touräächtraale',
'htmlform-selectorother-other' => 'Uur',

);
