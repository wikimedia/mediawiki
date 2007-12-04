<?php
/** Seeltersk (Seeltersk)
 *
 * @addtogroup Language
 *
 * @author Maartenvdbent
 * @author Nike
 * @author SPQRobin
 * @author Siebrand
 * @author Pyt
 */

$fallback = 'de';

$namespaceNames = array(
	NS_MEDIA          => 'Media',
	NS_SPECIAL        => 'Spezial',
	NS_MAIN           => '',
	NS_TALK           => 'Diskussion',
	NS_USER           => 'Benutser',
	NS_USER_TALK      => 'Benutser_Diskussion',
	# NS_PROJECT set by \$wgMetaNamespace
	NS_PROJECT_TALK   => '$1_Diskussion',
	NS_IMAGE          => 'Bielde',
	NS_IMAGE_TALK     => 'Bielde_Diskussion',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'MediaWiki_Diskussion',
	NS_TEMPLATE       => 'Foarloage',
	NS_TEMPLATE_TALK  => 'Foarloage_Diskussion',
	NS_HELP           => 'Hälpe',
	NS_HELP_TALK      => 'Hälpe_Diskussion',
	NS_CATEGORY       => 'Kategorie',
	NS_CATEGORY_TALK  => 'Kategorie_Diskussion',
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Ferwiese unnerstriekje:',
'tog-highlightbroken'         => 'Ätterdruk lääse ap Ferwiese ätter loose Themen',
'tog-justify'                 => 'Text as Bloksats',
'tog-hideminor'               => 'Litje Annerengen uutbländje',
'tog-extendwatchlist'         => 'Uutdiende Beooboachtengslieste',
'tog-usenewrc'                => 'Fermoorde Deerstaalenge (bruukt Javascript)',
'tog-numberheadings'          => 'Uurschrifte automatisk nuumerierje',
'tog-showtoolbar'             => 'Beoarbaidengs-Reewen anwiese',
'tog-editondblclick'          => 'Sieden mäd Dubbeldklik beoarbaidje (JavaScript)',
'tog-editsection'             => 'Links toun Beoarbaidjen fon eenpelde Ousatse anwiese',
'tog-editsectiononrightclick' => 'Eenpelde Ousatse mäd Gjuchtsklik beoarbaidje (JavaScript)',
'tog-showtoc'                 => 'Anwiesen fon n Inhooldsferteeknis bie Artikkele mäd moor as 3 Uurschrifte',
'tog-rememberpassword'        => 'Duurhaft Ienlogjen',
'tog-editwidth'               => 'Text-Iengoawenfäild mäd fulle Bratte',
'tog-watchcreations'          => 'Aal do sälwen näi anlaide Sieden beooboachtje',
'tog-watchdefault'            => 'Aal do sälwen annerde Sieden beooboachtje',
'tog-watchmoves'              => 'Aal do sälwen ferschäuwede Sieden beooboachtje',
'tog-watchdeletion'           => 'Aal do sälwen läskede Sieden beooboachtje',
'tog-minordefault'            => 'Alle Annerengen as littek markierje',
'tog-previewontop'            => 'Foarschau buppe dät Beoarbaidengsfinster anwiese',
'tog-previewonfirst'          => 'Bie dät eerste Beoarbaidjen altied ju Foarschau anwiese',
'tog-nocache'                 => 'Siedencache deaktivierje',
'tog-enotifwatchlistpages'    => 'Bie Annerengen an do Sieden E-Mails seende.',
'tog-enotifusertalkpages'     => 'Bie Annerengen an mien Benutser-Diskussionssiede E-Mails seende.',
'tog-enotifminoredits'        => 'Uk bie litje Annerengen an do Sieden E-Mails seende.',
'tog-enotifrevealaddr'        => 'Dien E-Mail-Adrässe wäd in Bescheed-Mails wiesed.',
'tog-shownumberswatching'     => 'Antaal fon do beooboachtjende Benutsere anwiese',
'tog-fancysig'                => 'Unnerschrift sunner automatiske Ferlinkenge tou ju Benutsersiede',
'tog-externaleditor'          => 'Externe Editor as Standoard benutsje',
'tog-externaldiff'            => 'Extern Diff-Program as Standoard benutsje',
'tog-showjumplinks'           => '"Wikselje tou"-Links muugelk moakje',
'tog-uselivepreview'          => 'Live-Foarschau nutsje (JavaScript) (experimentell)',
'tog-forceeditsummary'        => 'Woarschauje, wan bie dät Spiekerjen ju Touhoopefoatenge failt',
'tog-watchlisthideown'        => 'Oaine Biedraage in ju Beooboachtengslieste ferbierge',
'tog-watchlisthidebots'       => 'Bot-Biedraage in ju Beooboachtengslieste ferbierge',
'tog-watchlisthideminor'      => 'Litje Biedraage in ju Beooboachtengslieste ferbierge',
'tog-nolangconversion'        => 'Konvertierenge fon Sproakvarianten deaktivierje',
'tog-ccmeonemails'            => 'Seend mie Kopien fon do E-Maile, do iek uur Benutsere seende.',
'tog-diffonly'                => 'Wies bie dän Versionsfergliek bloot do Unnerscheede, nit ju fulboodige Siede',

'underline-always'  => 'Altied',
'underline-never'   => 'sieläärge nit',
'underline-default' => 'honget ou fon Browser-Ienstaalenge',

'skinpreview' => '(Foarschau)',

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

# Bits of text used by many pages
'categories'            => 'Kategorien',
'pagecategories'        => '{{PLURAL:$1|Kategorie|Kategorien}}',
'category_header'       => 'Artikkel in de Kategorie "$1"',
'subcategories'         => 'Unnerkategorien',
'category-media-header' => 'Media in Kategorie "$1"',
'category-empty'        => "''Disse Kategorie is loos.''",

'mainpagetext'      => 'Ju Wiki Software wuude mäd Ärfoulch installierd!',
'mainpagedocfooter' => 'Sjuch ju [http://meta.wikimedia.org/wiki/MediaWiki_localization Dokumentation tou de Anpaasenge fon dän Benutseruurfläche] un dät [http://meta.wikimedia.org/wiki/Help:Contents Benutserhondbouk] foar Hälpe tou ju Benutsenge un Konfiguration.',

'about'          => 'Uur',
'article'        => 'Inhoold Siede',
'newwindow'      => '(eepent in näi Finster)',
'cancel'         => 'Oubreeke',
'qbfind'         => 'Fiende',
'qbbrowse'       => 'Bleederje',
'qbedit'         => 'Annerje',
'qbpageoptions'  => 'Disse Siede',
'qbpageinfo'     => 'Siedendoatäie',
'qbmyoptions'    => 'Mien Sieden',
'qbspecialpages' => 'Spezialsieden',
'moredotdotdot'  => 'Moor …',
'mypage'         => 'Oaine Siede',
'mytalk'         => 'Oaine Diskussion',
'anontalk'       => 'Diskussionssiede foar dissen IP',
'navigation'     => 'Navigation',

# Metadata in edit box
'metadata_help' => 'Metadoatäie:',

'errorpagetitle'    => 'Failer',
'returnto'          => 'Tourääch tou Siede $1.',
'tagline'           => 'Uut {{SITENAME}}',
'help'              => 'Hälpe',
'search'            => 'Säike',
'searchbutton'      => 'Säike',
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
'editthispage'      => 'Siede beoarbaidje',
'delete'            => 'Läskje',
'deletethispage'    => 'Disse Siede läskje',
'undelete_short'    => '{{PLURAL:$1|1 Version|$1 Versione}} wier häärstaale',
'protect'           => 'schutsje',
'protect_change'    => 'annerde dän Siedenschutsstoatus',
'protectthispage'   => 'Siede schutsje',
'unprotect'         => 'Fräiroat',
'unprotectthispage' => 'Schuts aphieuwje',
'newpage'           => 'Näie Siede',
'talkpage'          => 'Diskussion',
'talkpagelinktext'  => 'Diskussion',
'specialpage'       => 'Spezioalsiede',
'personaltools'     => 'Persöönelke Reewen',
'postcomment'       => 'Kommentoar touföigje',
'articlepage'       => 'Siede',
'talk'              => 'Diskussion',
'views'             => 'Anwiesengen',
'toolbox'           => 'Reewen',
'userpage'          => 'Benutsersiede',
'projectpage'       => 'Meta-Text',
'imagepage'         => 'Bieldesiede',
'mediawikipage'     => 'Inhooldssiede anwiese',
'templatepage'      => 'Foarloagensiede anwiese',
'viewhelppage'      => 'Hälpesiede anwiese',
'categorypage'      => 'Kategoriesiede anwiese',
'viewtalkpage'      => 'Diskussion',
'otherlanguages'    => 'Uur Sproaken',
'redirectedfrom'    => '(Fäärelaited fon $1)',
'redirectpagesub'   => 'Fäärelaitenge',
'lastmodifiedat'    => 'Disse Siede wuude toulääst annerd uum $2, $1.', # $1 date, $2 time
'viewcount'         => 'Disse Siede wuude bit nu {{PLURAL:$1|eenmoal|$1 moal}} ouruupen.',
'protectedpage'     => 'Schutsede Siede',
'jumpto'            => 'Wikselje tou:',
'jumptonavigation'  => 'Navigation',
'jumptosearch'      => 'Säike',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Uur {{SITENAME}}',
'aboutpage'         => 'Project:Uur_{{SITENAME}}',
'bugreports'        => 'Kontakt',
'copyright'         => 'Inhoold is ferföichboar unner de $1.',
'copyrightpagename' => '{{SITENAME}} Uurheebergjuchte',
'copyrightpage'     => '{{ns:project}}:Uurheebergjuchte',
'currentevents'     => 'Aktuälle Geböärnisse',
'currentevents-url' => 'Project:Aktuälle Geböärnisse',
'disclaimers'       => 'Begriepskläärenge',
'disclaimerpage'    => 'Project:Siede tou Begriepskläärenge',
'edithelp'          => 'Beoarbaidengshälpe',
'edithelppage'      => 'Help:Beoarbaidengshälpe',
'faq'               => 'Oafte stoalde Froagen',
'faqpage'           => 'Project:FAQ',
'helppage'          => 'Help:Hälpe',
'mainpage'          => 'Haudsiede',
'policy-url'        => 'Projekt:Laitlienjen',
'portal'            => '{{SITENAME}}-Portoal',
'portal-url'        => 'Project:Portoal',
'privacy'           => 'Doatenschuts',
'privacypage'       => 'Project:Doatenschuts',
'sitesupport'       => 'Spenden',
'sitesupport-url'   => 'Project:Spenden',

'badaccess'        => 'Neen uträkkende Gjuchte',
'badaccess-group0' => 'Du hääst nit ju ärfoarderelke Begjuchtigenge foar disse Aktion.',
'badaccess-group1' => 'Disse Aktion ist bloot muugelk foar Benutsere, do der ju Gruppe „$1“ anheere.',
'badaccess-group2' => 'Disse Aktion is bloot muugelk foar Benutsere, do der een fon do Gruppen „$1“ anheere.',
'badaccess-groups' => 'Disse Aktion is bloot muugelk foar Benutsere, do der een fon do Gruppen „$1“ anheere.',

'versionrequired'     => 'Version $1 fon MediaWiki is nöödich',
'versionrequiredtext' => 'Version $1 fon MediaWiki is nöödich uum disse Siede tou nutsjen. Sjuch ju [[{{ns:special}}:Version|Versionssiede]]',

'ok'                      => 'Säike',
'retrievedfrom'           => 'Fon "$1"',
'youhavenewmessages'      => 'Du hääst $1 ($2).',
'newmessageslink'         => 'näie Ättergjuchte',
'newmessagesdifflink'     => 'Unnerscheed tou ju foarlääste Version',
'youhavenewmessagesmulti' => 'Du hääst näie Ättergjuchte: $1',
'editsection'             => 'Beoarbaidje',
'editold'                 => 'Beoarbaidje',
'editsectionhint'         => 'Apsats beoarbaidje: $1',
'toc'                     => 'Inhooldsferteeknis',
'showtoc'                 => 'Anwiese',
'hidetoc'                 => 'ferbierge',
'thisisdeleted'           => '$1 ankiekje of wier häärstaale?',
'viewdeleted'             => '$1 anwiese?',
'restorelink'             => '{{PLURAL:$1|1 läskede Beoarbaidengsfoargang|$1 läskede Beoarbaidengsfoargange}}',
'feedlinks'               => 'Feed:',
'feed-invalid'            => 'Ungultigen Abonnement-Typ.',
'site-rss-feed'           => '$1 RSS-Feed',
'site-atom-feed'          => '$1 Atom-Feed',
'page-rss-feed'           => '"$1" RSS-Feed',
'page-atom-feed'          => '"$1" Atom-Feed',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Artikkel',
'nstab-user'      => 'Benutsersiede',
'nstab-media'     => 'Media',
'nstab-special'   => 'Spezial',
'nstab-project'   => 'Projektsiede',
'nstab-image'     => 'Bielde',
'nstab-mediawiki' => 'Ättergjucht',
'nstab-template'  => 'Foarloage',
'nstab-help'      => 'Hälpe',
'nstab-category'  => 'Kategorie',

# Main script and global functions
'nosuchaction'      => 'Disse Aktion rakt et nit',
'nosuchactiontext'  => 'Disse Aktion wäd fon dän MediaWiki-Software nit unnerstöänd.',
'nosuchspecialpage' => 'Disse Spezialsiede rakt et nit',
'nospecialpagetext' => 'Disse Spezialsiede wäd fon dän MediaWiki-Software nit unnerstöänd.',

# General errors
'error'                => 'Failer',
'databaseerror'        => 'Failer in ju Doatenboank',
'dberrortext'          => 'Dät roat n Syntaxfailer in dän Doatenboankoufroage. Ju lääste Doatenboankoufroage lutte:
<blockquote><tt>$1</tt></blockquote> uut de Funktion "<tt>$2</tt>". MySQL mäldede dän Failer "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Dät roate n Syntaxfailer in ju Doatenboankoufroage.
Ju lääste Doatenboankoufroage lutte: „$1“ uut ju Funktion „<tt>$2</tt>“.
MySQL mäldede dän Failer: „<tt>$3: $4</tt>“.',
'noconnect'            => 'Spietelk kuude neen Ferbiendenge tou ju Doatenboank apbaud wäide. Die Doatenboankserver häd foulgjende Failere mälded: <i>$1</i>. Fersäik dät jädden noch moal of besäik uus Haudsiede.',
'nodb'                 => 'Kuude Doatenboank $1 nit beloangje',
'cachederror'          => 'Dät Foulgjende is ne Kopie uut de Cache un is fielicht ferallerd.',
'laggedslavemode'      => 'Woarschauenge: Ju anwiesde Siede kon unner Umstande do jungste Beoarbaidengen noch nit be-ienhoolde.',
'readonly'             => 'Doatenboank is speerd',
'enterlockreason'      => 'Reeke jädden n Gruund ien, wieruum ju Doatenboank speerd wäide schuul un ne Ouschätsenge uur ju Duur fon ju Speerenge',
'readonlytext'         => 'Ju Doatenboank is apstuuns foar Annerengen un näie Iendraage speerd.

As Gruund wuude anroat: $1',
'missingarticle'       => 'Die Text foar "$1" wuude nit in ju Doatenboank fuunen.

Ju Siede is muugelkerwiese läsked af ferschäuwen wuuden.

Dät is uk muugelk, dät et n Problem mäd dän Tougriep ap ju Doatenboank rakt. In dän Fal fersäik dät leeter jädden noch moal.',
'readonly_lag'         => 'Dät Spiekerjen fon Annerengen wuude foar ne kuute Tied automatisk speerd, uum doo Doatenboank-Servere fon dän Wikipedia Tied tou reeken, do Inhoolde unnernunner outouglieken. Fersäik dät jädden in n poor Minuten noch moal.',
'internalerror'        => 'Interne Failer',
'internalerror_info'   => 'Interne Failer: $1',
'filecopyerror'        => 'Kuude Doatäi "$1" nit ätter "$2" kopierje.',
'filerenameerror'      => 'Kuude Doatäi "$1" nit ätter "$2" uumenaame.',
'filedeleteerror'      => 'Kuude Doatäi "$1" nit läskje.',
'directorycreateerror' => 'Dät Ferteeknis „$1“ kuude nit anlaid wäide.',
'filenotfound'         => 'Kuude Doatäi "$1" nit fiende.',
'fileexistserror'      => 'In ju Doatäi „$1“ kuude nit schrieuwen wäide, deer ju Doatäi al bestoant.',
'unexpected'           => 'Uunferwachteden Wäid: „$1“=„$2“.',
'formerror'            => '<b style="color: #cc0000;">Failer: Do Iengoawen konne nit feroarbaided wäide.</b>',
'badarticleerror'      => 'Disse Honnelenge kon ap disse Siede nit moaked wäide.',
'cannotdelete'         => 'Kon spezifizierde Siede of Artikkel nit läskje. Fielicht is ju al läsked wuuden.',
'badtitle'             => 'Uungultige Tittel.',
'badtitletext'         => 'Die anfräigede Tittel waas uungultich, loos, of n uungultige Sproaklink fon n uur Wiki.',
'perfdisabled'         => 'Disse Funtion wuude weegen Uurbeläästenge fon dän Server foaruurgungend deaktivierd.',
'perfcached'           => 'Do foulgjende Doaten stamme uut dän Cache un sunt muugelkerwiese nit aktuäl:',
'perfcachedts'         => 'Disse Doaten stamme uut dän Cache, lääste Update: $1',
'querypage-no-updates' => "'''Ju Aktualisierengsfunktion foar disse Siede is apstuuns deaktivierd. Do Doaten wäide toueerst nit fernäid.'''",
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
'editinginterface'     => "'''Woarschauenge:''' Disse Siede änthaalt fon ju MediaWiki-Software benutseden Text. Annerengen wirkje sik ap ju Benutseruurfläche uut.",
'sqlhidden'            => '(SQL-Oufroage ferbierged)',
'cascadeprotected'     => 'Disse Siede is tou Beoarbaidenge speerd. Ju is in do {{PLURAL:$1|foulgjende Siede|foulgjende Sieden}} ienbuunen, do der middels ju Kaskadenspeeroption schutsed {{PLURAL:$1|is|sunt}}:
$2',
'namespaceprotected'   => "Du hääst neen Begjuchtigenge, ju Siede in dän '''$1'''-Noomensruum tou beoarbaidjen.",
'customcssjsprotected' => 'Du bäst nit begjuchtiged disse Siede tou beoarbaidjen, deer ju tou do persöönelke Ienstaalengen fon n uur Benutser heert.',
'ns-specialprotected'  => 'Sieden in dän {{ns:special}}-Noomensruum konnen nit beoarbaided wäide.',

# Login and logout pages
'logouttitle'                => 'Benutser-Oumäldenge',
'logouttext'                 => 'Du bäst nu oumälded.
Du koast {{SITENAME}} nu anonym fääre benutsje, of die fonnäien unner dän sälwe of n uur Benutsernoome wier anmäldje.',
'welcomecreation'            => '== Wäilkuumen, $1 ==

Dien Benutserkonto wuude mäd Ärfoulch iengjucht. Ferjeet nit, dien Ienstaalengen antoupaasjen.',
'loginpagetitle'             => 'Benutser-Anmäldenge',
'yourname'                   => 'Benutsernoome:',
'yourpassword'               => 'Paaswoud:',
'yourpasswordagain'          => 'Paaswoud wierhoalje:',
'remembermypassword'         => 'duurhaft anmäldje',
'yourdomainname'             => 'Dien Domain:',
'externaldberror'            => 'Äntweeder deer lait n Failer bie ju externe Authentifizierenge foar, of du duurst din extern Benutzerkonto nit aktualisierje.',
'loginproblem'               => "'''Dät roate n Problem mäd ju Anmäldenge.'''<br /> Fersäik dät jädden nochmoal!",
'login'                      => 'Anmäldje',
'loginprompt'                => 'Uum sik bie {{SITENAME}} anmäldje tou konnen, mouten Cookies aktivierd weese.',
'userlogin'                  => 'Anmäldje',
'logout'                     => 'Oumäldje',
'userlogout'                 => 'Oumäldje',
'notloggedin'                => 'Nit anmälded',
'nologin'                    => 'Noch neen Benutserkonto? $1.',
'nologinlink'                => 'Hier laist du n Konto an.',
'createaccount'              => 'Benutserkonto anlääse',
'gotaccount'                 => 'Du hääst al n Konto? $1.',
'gotaccountlink'             => 'Hier gungt dät ätter dän Login',
'createaccountmail'          => 'Uur Email',
'badretype'                  => 'Do bee Paaswoude stimme nit uureen.',
'userexists'                 => 'Disse Benutsernoomen is al ferroat. Wääl jädden n uur.',
'youremail'                  => 'E-Mail-Adrässe:',
'username'                   => 'Benutsernoome:',
'uid'                        => 'Benutser-ID:',
'yourrealname'               => 'Dien ächte Noome:',
'yourlanguage'               => 'Sproake fon ju Benutser-Uurfläche:',
'yourvariant'                => 'Variante',
'yournick'                   => 'Unnerschrift:',
'badsig'                     => 'Signatursyntax is uungultich; HTML uurpröiwje.',
'badsiglength'               => 'Ju Unnerschrift duur maximoal $1 Teekene loang weese.',
'email'                      => 'E-Mail',
'prefs-help-realname'        => 'Optional. Foar dät anärkaanende Naamen fon dien Noome in Touhoopehong mäd dien Biedraagen.',
'loginerror'                 => 'Failer bie ju Anmäldenge',
'prefs-help-email'           => 'Optional. Moaket uur Benutsere muugelk, uur Email Kontakt mäd die aptouniemen, sunner dät du dien Indentität eepenlääse hougest.',
'prefs-help-email-required'  => 'N gultige Email-Adrässe is nöödich.',
'nocookiesnew'               => 'Dien Benutsertougong wuude kloor moaked, man du bäst nit anmälded. {{SITENAME}} benutset Cookies toun Anmäldjen fon do Benutsere. Du hääst in dien Browser-Ienstaalengen Cookies deaktivierd. Uum dien näie Benutsertougong tou bruuken, läit jädden dien Browser Cookies foar {{SITENAME}} annieme un mäldje die dan mäd dien juust iengjuchten Benutsernoome un Paaswoud an.',
'nocookieslogin'             => '{{SITENAME}} benutset Cookies toun Anmäldjen fon dän Benutser. Du hääst in dien Browser-Ienstaalengen Cookies deaktivierd, jädden aktivierje do un fersäik et fonnäien.',
'noname'                     => 'Du moast n Benutsernoome anreeke.',
'loginsuccesstitle'          => 'Anmäldenge mäd Ärfoulch',
'loginsuccess'               => "'''Du bäst nu as \"\$1\" bie {{SITENAME}} anmälded.'''",
'nosuchuser'                 => 'Die Benutsernoome "$1" bestoant nit. Uurpröiwe ju Schrieuwwiese, of mälde die as näien Benutser an.',
'nosuchusershort'            => 'Die Benutsernooome "$1" bestoant nit. Jädden uurpröiwe ju Schrieuwwiese.',
'nouserspecified'            => 'Reek jädden n Benutsernoome an.',
'wrongpassword'              => 'Dät Paaswoud is falsk. Fersäik dät jädden fonnäien.',
'wrongpasswordempty'         => 'Du hääst ferjeeten, dien Paaswoud ientoureeken. Fersäk dät jädden fonnäien.',
'passwordtooshort'           => 'Dien Paaswoud is tou kuut. Dät mout mindestens $1 Teekene loang weese.',
'mailmypassword'             => 'Paaswoud ferjeeten?',
'passwordremindertitle'      => 'Paaswoudärinnerenge fon {{SITENAME}}',
'passwordremindertext'       => 'Wäl mäd ju IP-Adresse $1, woarschienelk du sälwen, häd n näi Paaswoud foar ju Anmäldenge bie {{SITENAME}} ($4) anfoarderd.

Dät automatisk generierde Paaswoud foar Benutser $2 lut nu: $3

Du schääst die nu anmäldje un dät Paaswoud annerje: {{fullurl:{{ns:special}}}}:Userlogin

Ignorier disse E-Mail, in dän Fal du disse nit sälwen anfoarderd hääst. Dät oolde Paaswoud blift dan wieders gultich.',
'noemail'                    => 'Benutser "$1" häd neen Email-Adrässe anroat of häd ju E-Mail-Funktion deaktivierd.',
'passwordsent'               => 'N näi temporär Paaswoud wuude an ju Email-Adrässe fon Benutser "$1" soand. Mäldje die jädden deermäd, soo gau as du dät kriegen hääst. Dät oolde Paaswoud blift uk ätters gultich.',
'blocked-mailpassword'       => 'Ju fon die ferwoande IP-Adresse is foar dät Annerjen fon Sieden speerd. Uum n Misbruuk tou ferhinnerjen, wuude ju Muugelkhaid tou ju Anfoarderenge fon n näi Paaswoud ieuwenfals speerd.',
'eauthentsent'               => 'Ne Bestäätigengs-Email wuude an ju anroate Adrässe fersoand. Aleer n Email fon uur
Benutsere uur ju {{SITENAME}}-Mailfunktion ämpfangd wäide kon, mout ju Adrässe un hiere
wuddelke Touheeregaid tou dit Benutserkonto eerste bestäätiged wäide. Befoulgje jädden do
Waiwiese in ju Bestätigengs-E-Mail.',
'throttled-mailpassword'     => 'Deer wuude binnen do lääste $1 Uuren al n näi Paaswoud anfoarderd. Uum n Misbruuk fon ju Funktion tou ferhinnerjen, kon bloot alle $1 Uuren n näi Paaswoud anfoarderd wäide.',
'mailerror'                  => 'Failer bie dät Seenden fon dän Email: $1',
'acct_creation_throttle_hit' => 'Du hääst al $1 Benutserkonten anlaid. Du koast fääre neen moor anlääse.',
'emailauthenticated'         => 'Jou Email-Adrässe wuude bestäätiged: $1.',
'emailnotauthenticated'      => 'Jou Email-Adrässe wuude <strong>noch nit bestäätiged</strong>. Deeruum is bit nu neen E-
Mail-Fersoand un Ämpfang foar do foulgjende Funktionen muugelk.',
'noemailprefs'               => '<strong>Du hääst neen Email-Adrässe anroat</strong>, do foulgjende Funktione sunt deeruum apstuuns nit muugelk.',
'emailconfirmlink'           => 'Bestäätigje Jou Email-Adrässe',
'invalidemailaddress'        => 'Ju Email-Adresse wuude nit akzeptierd deeruum dät ju n ungultich Formoat (eventuäl ungultige Teekene) tou hääben schient. Reek jädden ne korrekte Adrässe ien of moakje dät Fäild loos.',
'accountcreated'             => 'Benutserkonto näi anlaid',
'accountcreatedtext'         => 'Dät Benutserkonto $1 wuude iengjucht.',
'createaccount-title'        => 'Benutserkonto anlääse foar {{SITENAME}}',
'createaccount-text'         => 'Wäl ($1) häd foar die n Benutserkonto "$2" ap {{SITENAME}} ($4) moaked. Dät Paaswoud foar "$2" is "$3". Du schuust die nu anmäldje un dien Paaswoud annerje.

In dän Fal dät Benutserkonto uut Fersjoon anlaid wuude, koast du disse Ättergjucht ignorierje.',
'loginlanguagelabel'         => 'Sproake: $1',

# Password reset dialog
'resetpass'               => 'Paaswoud foar Benutserkonto touräächsätte',
'resetpass_announce'      => 'Anmäldenge mäd dän uur E-Mail tousoande Code. Uum ju Anmäldenge outousluuten, moast du nu n näi Paaswoud wääle.',
'resetpass_header'        => 'Paaswoud touräächsätte',
'resetpass_submit'        => 'Paaswoud ienbrange un anmäldje',
'resetpass_success'       => 'Dien Paaswoud wuude mäd Ärfoulch annerd. Nu foulget ju Anmäldenge...',
'resetpass_bad_temporary' => 'Ungultich foarlööpich Paaswoud. Du hääst dien Paaswoud al mäd Ärfoulch annerd of n näi, foarlööpich Paaswoud anfoarderd.',
'resetpass_forbidden'     => 'Dät Paaswoud kon in {{SITENAME}} nit annerd wäide.',
'resetpass_missing'       => 'Loos Formular.',

# Edit page toolbar
'bold_sample'     => 'Fatte Text',
'bold_tip'        => 'Fatte Text',
'italic_sample'   => 'Kursive Text',
'italic_tip'      => 'Kursive Text',
'link_sample'     => 'Link-Text',
'link_tip'        => 'Interne Link',
'extlink_sample'  => 'http://www.Biespil.de Link-Text',
'extlink_tip'     => 'Externen Link (http:// beoachtje)',
'headline_sample' => 'Ieuwene 2 Uurschrift',
'headline_tip'    => 'Ieuwene 2 Uurschrift',
'math_sample'     => 'Formel hier ienföigje',
'math_tip'        => 'Mathematiske Formel (LaTeX)',
'nowiki_sample'   => 'Uunformattierden Text hier ienföigje',
'nowiki_tip'      => 'Uunformattierden Text',
'image_sample'    => 'Biespil.jpg',
'image_tip'       => 'Bielde-Ferwies',
'media_sample'    => 'Biespil.ogg',
'media_tip'       => 'Mediendoatäi-Ferwies',
'sig_tip'         => 'Dien Signatur mäd Tiedstämpel',
'hr_tip'          => 'Horizontoale Lienje (spoarsoam ferweende)',

# Edit pages
'summary'                   => 'Touhoopefoatenge',
'subject'                   => 'Themoa',
'minoredit'                 => 'Bloot litje Seeken wuuden ferannerd',
'watchthis'                 => 'Disse Siede beooboachtje',
'savearticle'               => 'Siede spiekerje',
'preview'                   => 'Foarschau',
'showpreview'               => 'Foarschau wiese',
'showlivepreview'           => 'Live-Foarschau',
'showdiff'                  => 'Annerengen wiese',
'anoneditwarning'           => "'''Woarschauenge:''' Du beoarbaidest disse Siede, man du bäst nit anmälded. Wan du spiekerst, wäd dien aktuelle IP-Adresse in ju Versionsgeschichte apteekend un is deermäd  '''eepentelk''' ientousjoon.",
'missingsummary'            => "'''Waiwiesenge:''' Du hääst neen Touhoopefoatenge anroat. Wan du fonnäien ap „Siede spiekerje“ klikst, wäd dien Annerenge sunner Touhoopefoatenge uurnuumen.",
'missingcommenttext'        => 'Reek jädden ne Touhoopefoatenge ien.',
'missingcommentheader'      => "'''OACHTENGE:''' Du hääst neen Uurschrift in dät Fäild „Beträft:“ ienroat. Wan du fonnäien ap „Siede spiekerje“ klikst, wäd dien Beoarbaidenge sunner Uurschrift spiekerd.",
'summary-preview'           => 'Foarschau fon ju Touhoopefoatengsriege',
'subject-preview'           => 'Themoa bekiekje',
'blockedtitle'              => 'Benutser is blokkierd',
'blockedtext'               => "Dien Benutsernoome of dien IP-Adrässe wuude fon $1 speerd. As Gruund wuude anroat ''$2''.

Ju Duur fon ju Speerenge fint sik in $4. Deer IP-Adrässen bie fuul Providere dynamisk ferroat wäide, kon sun Speerenge oafte uk Uunscheeldige träffe, t.B. wan die bie dän Ienwoal ju IP-Adrässe fon wäl touwiesd wuude, die eer in Wikipedia Uunfuuch anstoald häd. Fals ju speerde IP-Adrässe n Proxy fon AOL is, koast du as AOL-Benutser ju Speerenge uumgunge, truch n uur [[Browser]] tou ferweenden as dän AOL-Browser. Wan du ju Meenenge bäst, dät ju Speerenge uungjuchtfäidich waas, weende die dan jädden mäd Angoawe fon ju IP-Adrässe ($3) of dän Benutsernoome, dän Speergruund un ne Beschrieuwenge fon dien Beoarbaidengen uur Email an (email-address) Uum ju Oarbaidsbeläästenge foar do Fräiwillige, do sik uum sukke Fälle kummerje, gering tou hoolden, weende die deerum jädden bloot bie laangere Speerengen an disse Adrässe. Speerengen weegen Vandalismus fon dän Benutser, die eer ju beträffende IP-Adrässe bruukt häd, schuulen ätter kuute Tied ouloope.",
'autoblockedtext'           => 'Dien IP-Adresse wuude automatisk speerd, deer ju fon n uur Benutser nutsed wuude, die truch $1 speerd wuude.
As Gruund wuude ounroat:

:\'\'$2\'\' (<span class="plainlinks">[{{fullurl:Special:Ipblocklist|&action=search&limit=&ip=%23}}$5 Logboukiendraach]</span>)

<p style="border-style: solid; border-color: red; border-width: 1px; padding:5px;"><b>N Leesetougriep is wieders muugelk,</b> 
bloot ju Beoarbaidenge un dät Moakjen fon Sieden in {{SITENAME}} wuude speerd.
Schuul disse Ättergjucht anwiesd wäide, owwol bloot leesend tougriepen wuude, bäst du ne (roode) Ferbiendenge ap ne noch nit existente Siede foulged.</p>

Du koast $1 of aan fon do uur [[{{MediaWiki:Grouppage-sysop}}|Administratore]] kontaktierje, uum uur ju Speere tou diskutierjen.

<div style="border-style: solid; border-color: red; border-width: 1px; padding:5px;">
\'\'\'Reek jädden foulgjende Doaten in älke Anfroage oun:\'\'\'
*Speerenden Administrator: $1
*Speergruund: $2
*Begin fon ju Speere: $8
*Speer-Eende: $6
*IP-Adresse: $3
*Speer-ID: #$5
</div>',
'blockednoreason'           => 'neen Begründenge ounroat',
'blockedoriginalsource'     => "Die Wältext fon '''$1''' wäd hier anwiesd:",
'blockededitsource'         => "Die Wältext '''fon dien Annerengen''' an '''$1''':",
'whitelistedittitle'        => 'Toun Beoarbaidjen is dät nöödich, anmälded tou weesen',
'whitelistedittext'         => 'Du moast die $1, uum Artikkele beoarbaidje tou konnen.',
'whitelistreadtitle'        => 'Toun Leesen is dät nöödich, anmälded tou weesen',
'whitelistreadtext'         => 'Du moast die [[Special:Userlogin|hier anmäldje]], uum Artikkele leese tou konnen.',
'whitelistacctitle'         => 'Du hääst neen Gjucht, n Benutserkonto tou moakjen.',
'whitelistacctext'          => 'Uum in {{SITENAME}} mäd n Benutserkonto tou oarbaidjen, moast du die eerste [[Special:Userlogin|hier anmäldje]], un do nöödige Begjuchtegengen hääbe.',
'confirmedittitle'          => 'Toun Beoarbaidjen is ju E-Mail-Anärkannenge nöödich.',
'confirmedittext'           => 'Du moast dien E-Mail-Adresse eerste anärkanne, eer du beoarbaidje koast. Fäl dien E-Mail uut un ärkanne ju an in do [[Special:Preferences|Ienstaalengen]].',
'nosuchsectiontitle'        => 'Oudeelenge bestoant nit',
'nosuchsectiontext'         => 'Du fersäkst ju nit bestoundende Oudeelenge $1 tou beoarbaidjen. Man bloot al bestoundende Oudeelengen konnen beoarbaided wäide.',
'loginreqtitle'             => 'Anmäldenge ärfoarderelk',
'loginreqlink'              => 'anmäldje',
'loginreqpagetext'          => 'Du moast die $1, uum uur Sieden betrachtje tou konnen.',
'accmailtitle'              => 'Paaswoud wuude fersoand.',
'accmailtext'               => 'Dät Paaswoud fon "$1" wuude an $2 soand.',
'newarticle'                => '(Näi)',
'newarticletext'            => 'Hier dän Text fon dän näie Artikkel iendreege. Jädden bloot in ganse Satse schrieuwe un neen truch dät Uurheebergjucht schutsede Texte fon uur Ljuude kopierje.',
'anontalkpagetext'          => "----''Dit is ju Diskussionssiede fon n uunbekoanden Benutser, die sik nit anmälded häd. Wail naan Noome deer is, wäd ju nuumeriske [[IP-Adrässe]] tou Identifizierenge ferwoand. Man oafte wäd sunne Adrässe fon moorere Benutsere ferwoand. Wan du n uunbekoanden Benutser bääst un du toankst dät du Kommentare krichst do nit foar die meend sunt, dan koast du ap bääste dien [[Special:Userlogin|anmäldje]], uum sukke Fertuusengen tou fermieden.''",
'noarticletext'             => '(Dissen Artikkel änthalt apstuuns neen Text)',
'userpage-userdoesnotexist' => 'Dät Benutserkonto „$1“ is nit deer. Pröif, of du disse Siede wuddelk moakje/beoarbaidje wolt.',
'clearyourcache'            => "'''Bemäärkenge:''' Ätter dät Fäästlääsen kon dät nöödich weese, dän Browser-Cache loostoumoakjen, uum do Annerengen sjo tou konnen.",
'usercssjsyoucanpreview'    => '<strong>Tipp:</strong> Benutse dän Foarschau-Knoop, uum dien näi CSS/JavaScript foar dät Spiekerjen tou tästjen.',
'usercsspreview'            => "==Foarschau fon die Benutser-CSS == 
'''Beoachtje:''' Ätter dät Spiekerjen moast du dien Browser kweede, ju näie Version tou leeden: '''Mozilla/Firefox:''' ''Strg-Shift-R'', '''Internet Explorer:''' ''Strg-F5'', '''Opera:''' ''F5'', '''Safari:''' ''Cmd-Shift-R'', '''Konqueror:''' ''F5''. Truch do Cache-
Mechanismen fon dän Server kon dät uk ne Wiele duurje, bit eene oolde Doatäi truch ne Näie ärsät wäd.",
'userjspreview'             => "== Foarschau fon dien Benutser-CSS ==
'''Beoachtje:''' Ätter dät Spiekerjen moast du dien Browser kweede, ju näie Version tou leeden: '''Mozilla/Firefox:''' ''Strg-Shift-R'', '''Internet Explorer:''' ''Strg-F5'', '''Opera:''' ''F5'', '''Safari:''' ''Cmd-Shift-R'', '''Konqueror:''' ''F5''.",
'userinvalidcssjstitle'     => "'''Woarschauenge:''' Deer existiert neen Skin \"\$1\". Betoank jädden, dät benutserspezifiske .css- un .js-Sieden män n Littek-Bouksteeuwe anfange mouten, also t.B. ''{{ns:user}}:Mustermann/monobook.css'', nit ''{{ns:user}}:Mustermann/Monobook.css''.",
'updated'                   => '(Annerd)',
'note'                      => '<strong>Waiwiesenge:</strong>',
'previewnote'               => 'Dit is man ne Foarschau, die Artikkel wuude noch nit spiekerd!',
'previewconflict'           => 'Disse Foarschau rakt dän Inhoold fon dät buppere Täkstfäild wier; so wol die Artikkel uutsjo, wan du nu spiekerjen dääst.',
'session_fail_preview'      => '<strong>Dien Beoarbaidenge kuud nit spiekerd wäide, deer dien Sitsengsdoaten ferlädden geen sunt. Fersäik dät jädden fonnäien. Schuul dät Problem bestounden blieuwe, logge die kuut uut un wier ien.</strong>',
'session_fail_preview_html' => "<strong>Dien Beoarbaidenge kuud nit spiekerd wäide, deer dien Sitsengsdoaten ferlädden geen sunt.</strong>

''Deer in dissen Wiki scheen HTML aktivierd is, wuude ju Foarschau uutblended uum JavaScript Angriepe tou ferhinnerjen.''

<strong>Fersäik et fonnäien, wan du unner ju foulgjende Textfoarschau noch moal ap „Siede spiekerje“ klikst. Schuul dät Problem bestounden blieuwe, mäld die ou un deerätter wier an.</strong>",
'token_suffix_mismatch'     => '<strong>Dien Beoarbaidenge wuude touräächwiesd, deer dien Browser Teekene in dät Beoarbaidje-Token ferstummeld häd.
Ne Spiekerenge kon dän Siedeninhoold fernäile. Dit geböärt bietiede truch ju Benutsenge fon n anonymen Proxy-Tjoonst, die der failerhaft oarbaidet.</strong>',
'editing'                   => 'Beoarbaidjen fon $1',
'editinguser'               => 'Beoarbaidje fon Benutser <b>$1</b>',
'editingsection'            => 'Beoarbaidje fon $1 (Apsats)',
'editingcomment'            => 'Beoarbaidjen fon $1 (Kommentoar)',
'editconflict'              => 'Beoarbaidengs-Konflikt: "$1"',
'explainconflict'           => "Uurswäl häd dissen Artikkel annerd, ätterdät du anfangd bäst, him tou beoarbaidjen. Dät buppere Textfäild änthaalt dän aktuälle Artikkel. Dät unnere Textfäild änthaalt dien Annerengen. Föige jädden dien Annerengen in dät buppere Textfäild ien.<br /> '''Bloot''' die Inhoold fon dät buppere Textfäild wäd spiekerd, wan du ap \"Spiekerje\" klikst!",
'yourtext'                  => 'Dien Text',
'storedversion'             => 'Spiekerde Version',
'nonunicodebrowser'         => '<strong style="color: #330000; background: #f0e000;">Oachtenge: Dien Browser kon Unicode-Teekene nit gjucht feroarbaidje. Benutse jädden n uur Browser uum Artikkele tou beoarbaidjen.</strong>',
'editingold'                => '<strong>OACHTENGE: Jie beoarbaidje ne oolde Version fon disse Artikkel. Wan Jie spiekerje, wäide alle näiere Versione uurschrieuwen.</strong>',
'yourdiff'                  => 'Unnerscheede',
'copyrightwarning'          => 'Aal Biedraage tou dän {{SITENAME}} wäide betrachted as stoundend unner ju $2 (sjuch fääre: "$1"). Fals Jie nit moaten dät Jou Oarbaid hier fon uur Ljuude ferannerd un fersprat wäd, dan drukke Jie nit ap "Spiekerje".<br />
Iek fersicherje hiermäd, dät iek dän Biedraach sälwen ferfoated hääbe blw. dät hie neen froamd Gjucht ferlätset un willigje ien, him unner dän GNU-Lizenz für freie Dokumentation tou fereepentlikjen.',
'copyrightwarning2'         => 'Aal Biedraage tou dän {{SITENAME}} konnen fon uur Ljuude ferannerd un fersprat wäide. Fals Jie nit moaten dät Jou Oarbaid hier fon uur Ljuude ferannerd un fersprat wäd, dan drukke Jie nit ap "Spiekerje".

Jie fersicherje hiermäd uk, dät Jie dän Biedraach sälwen ferfoated hääbe blw. dät hie neen froamd Gjucht ferlätset (sjuch fääre: $1).',
'longpagewarning'           => '<strong>WOARSCHAUENGE: Disse Siede is $1kb groot; eenige Browsere kuuden Probleme hääbe, Sieden tou beoarbaidjen, do der gratter as 32kb sunt. Uurlääse Jou jädden, of ne Oudeelenge fon do Sieden in litjere Ousnitte muugelk is.</strong>',
'longpageerror'             => '<strong>FAILER: Die Text, dän du tou spiekerjen fersäkst, is $1 KB groot. Dät is gratter as dät ferlööwede Maximum fon $2 KB – Spiekerenge nit muugelk.</strong>',
'readonlywarning'           => '<strong>WOARSCHAUENGE: Ju Doatenboank wuude foar Wartengsoarbaiden speerd, so dät dien Annerengen apstuuns nit spiekerd wäide konnen. Sicherje dän Text jädden lokoal ap dien Computer un fersäik tou n leeteren Tiedpunkt, do Annerengen in ju Wikipedia tou uurdreegen.</strong>',
'protectedpagewarning'      => '<strong>WOARSCHAUENGE: Disse Siede wuude speerd, so dät ju bloot truch Benutsere mäd Administrationsgjuchte beoarbeded wäide kon.</strong>',
'semiprotectedpagewarning'  => "'''Oachtenge:''' Disse Siede is ousleeten un kon bloot fon anmäldede Besäikere beoarbaided wäide.",
'cascadeprotectedwarning'   => "'''WOARSCHAUENGE: Disse Siede wuude speerd, so dät ju bloot truch Benutsere mäd Administratorgjuchte beoarbaided wäide kon. Ju is in do {{PLURAL:$1|foulgjende Siede|foulgjende Sieden}} ienbuunen, do der middels ju Kaskadenspeeroption schutsed {{PLURAL:$1|is|sunt}}:'''",
'templatesused'             => 'Foulgjende Foarloagen wäide fon disse Artikkele ferwoand:',
'templatesusedpreview'      => 'Foulgjende Foarloagen wäide fon disse Siedefoarschau ferwoand:',
'templatesusedsection'      => 'Foulgjende Foarloagen wuuden fon disse Oudeelenge ferwoand:',
'template-protected'        => '(schutsed)',
'template-semiprotected'    => '(Siedenschuts foar nit anmäldede un näie Benutser)',
'edittools'                 => '<!-- Text hier stoant unner Beoarbaidengsfäildere un Hoochleedefäildere. -->',
'nocreatetitle'             => 'Dät Moakjen fon näie Sieden is begränsed',
'nocreatetext'              => '{{SITENAME}} häd testwiese dät Moakjen fon näie Sieden begränsed. Du koast oawers al bestoundene Sieden beoarbaidje of die [[Special:Userlogin|anmäldje]].',
'nocreate-loggedin'         => 'Du hääst neen Begjuchtigenge, näie Sieden in dissen Wiki antoulääsen.',
'permissionserrors'         => 'Begjuchtigengs-Failere',
'permissionserrorstext'     => 'Du bäst nit begjuchtiged, ju Aktion uuttoufieren. {{PLURAL:$1|Gruund|Gruunde}}:',
'recreate-deleted-warn'     => "'''Oachtenge: Du moakest ne Siede, ju der al fröier läsked wuude.'''
 
Pröif mäd Suurge, of dät näi Moakjen fon ju Siede do Gjuchtlienjen äntspräkt.
Tou Dien Information foulget dät Läsk-Logbouk mäd ju Begründenge foar ju fröiere Läskenge:",

# "Undo" feature
'undo-success' => 'Ju Annerenge kuud mäd Ärfoulch tourääch annerd wäide. Jädden ju Beoarbaidenge in ju Ferglieksansicht kontrollierje un dan ap „Siede spiekerje“ klikke, uum ju tou spiekerjen.',
'undo-failure' => '<span class="error">Ju Annerenge kuud nit tourääch annerd wäide, deer ju betroffene Oudeelenge intwisken ferannerd wuude.</span>',
'undo-summary' => 'Annerenge $1 fon [[{{ns:special}}:Contributions/$2|$2]] ([[User_talk:$2|Diskussion]]) wuude tourääch annerd.',

# Account creation failure
'cantcreateaccounttitle' => 'Benutserkonto kon nit moaked wäide',
'cantcreateaccount-text' => "Dät Moakjen fon n Benutserkonto fon ju IP-Adresse <b>$1</b> uut wuude fon [[User:$3|$3]] speerd.

Gruund fon ju Speere: ''$2''",

# History pages
'viewpagelogs'        => 'Logbouke foar disse Siede anwiese',
'nohistory'           => 'Dät rakt neen fröiere Versione fon dissen Artikkel.',
'revnotfound'         => 'Disse Version wuude nit fuunen.',
'revnotfoundtext'     => 'Ju soachte Version fon dissen Artikkel kuude nit fuunen wäide. Uurpröiwe jädden ju URL fon disse Siede.',
'loadhist'            => 'Leede Lieste mäd fröiere Versione',
'currentrev'          => 'Aktuälle Version',
'revisionasof'        => 'Version fon $1',
'revision-info'       => 'Version fon $1 fon $2',
'previousrevision'    => '← Naistallere Version',
'nextrevision'        => 'Naistjungere Version →',
'currentrevisionlink' => 'Aktuälle Version',
'cur'                 => 'Aktuäl',
'next'                => 'Naiste',
'last'                => 'Foarige',
'orig'                => 'Originoal',
'page_first'          => 'Ounfang',
'page_last'           => 'Eend',
'histlegend'          => "Diff  Uutwoal: Do Boxen fon do wonskede Versionen markierje un 'Enter' drukke ap dän Button unner klikke.<br />
Legende: (Aktuäl) = Unnerscheed tou ju aktuälle Version, 
(Lääste) = Unnerscheed tou ju foarige Version, L = Litje Annerenge",
'deletedrev'          => '[läsked]',
'histfirst'           => 'Ooldste',
'histlast'            => 'Näiste',
'historysize'         => '({{PLURAL:$1|1 Byte|$1 Bytes}})',
'historyempty'        => '(loos)',

# Revision feed
'history-feed-title'          => 'Versionsgeschichte',
'history-feed-description'    => 'Versionsgeschichte foar disse Siede in {{SITENAME}}',
'history-feed-item-nocomment' => '$1 uum $2', # user at time
'history-feed-empty'          => 'Ju anfoarderde Siede existiert nit. Fielicht wuud ju läsked of ferschäuwen. [[Special:Search|Truchsäik]] {{SITENAME}} foar paasjende näie Sieden.',

# Revision deletion
'rev-deleted-comment' => '(Beoarbaidengskommentoar wächhoald)',
'rev-deleted-user'    => '(Benutsernoome wächhoald)',
'rev-deleted-event'   => '(Aktion wächhoald)',
'rev-delundel'        => 'wiese/ferbierge',
'revisiondelete'      => 'Versione läskje/wier häärstaale',
'revdelete-log'       => 'Kommentoar/Gruund:',

# Diffs
'history-title'           => 'Versionsgeschichte fon "$1"',
'difference'              => '(Unnerschied twiske Versionen)',
'lineno'                  => 'Riege $1:',
'compareselectedversions' => 'Wäälde Versione ferglieke',
'editundo'                => 'tounichte moakje',
'diff-multi'              => "<span style='font-size: smaller'>(Die Versionsfergliek belukt {{pluroal:$1|ne deertwiske lääsende Version|$1 deertwiske lääsende Versione}} mee ien.)</span>",

# Search results
'searchresults'     => 'Säikresultoate',
'noexactmatch'      => "'''Deer existiert neen Siede mäd dän Tittel „$1“.'''

Fersäik et uur ju Fultextsäike.
Alternativ koast du uk dän [[Special:Allpages|alphabetisken Index]] ätter äänelke Begriepe truchsäike.

Wan du die mäd dät Thema uutkoanst, koast du sälwen ju Siede „[[$1]]“ ferfoatje.",
'titlematches'      => 'Uureenstämmengen mäd Uurschrifte',
'notitlematches'    => 'Neen Uureenstimmengen',
'textmatches'       => 'Uureenstämmengen mäd Texte',
'notextmatches'     => 'Neen Uureenstimmengen',
'prevn'             => 'foarige $1',
'nextn'             => 'naiste $1',
'viewprevnext'      => 'Wies ($1) ($2) ($3)',
'showingresults'    => "Hier {{PLURAL:$1|is '''1''' Resultoat|sunt '''$1''' Resultoate}}, ounfangend mäd Nuumer '''$2'''.",
'showingresultsnum' => "Hier {{PLURAL:$3|is '''1''' Resultoat|sunt '''$1''' Resultoate}}, ounfangend mäd Nuumer '''$2'''.",
'nonefound'         => "<strong>Waiwiesenge:</strong> Säikanfroagen sunnerÄrfoulch wäide oafte feruurseeked truch dän Fersäik, ätter 'gewöönelke' Woude tou säiken; do sunt nit indizierd.",
'powersearch'       => 'Säik',
'powersearchtext'   => 'Säik in do Noomensruume:<br />$1<br />$2 Wies uk Fäärelaitengen<br />Säike ätter: $3 $9',
'searchdisabled'    => 'Ju {{SITENAME}} Fultextsäike is weegen Uurläästenge apstuuns deaktivierd. Du koast insteede deerfon ne Google- of Yahoo-Säike startje. Do Resultoate foar {{SITENAME}} speegelje oawers nit uunbedingd dän aktuällen Stand wier.',

# Preferences page
'preferences'           => 'Ienstaalengen',
'mypreferences'         => 'Ienstaalengen',
'prefsnologin'          => 'Nit anmälded',
'prefsnologintext'      => 'Du moast [[Special:Userlogin|anmälded]] weese, uum dien Ienstaalengen tou annerjen.',
'prefsreset'            => 'Ienstaalengen wuuden ap Standoard touräächsät.',
'qbsettings-none'       => 'Naan',
'changepassword'        => 'Paaswoud annerje',
'skin'                  => 'Skin',
'math'                  => 'TeX',
'dateformat'            => 'Doatumsformoat',
'datedefault'           => 'Neen Preferenz',
'datetime'              => 'Doatum un Tied',
'math_failure'          => 'Parser-Failer',
'math_unknown_error'    => 'Uunbekoande Failer',
'math_unknown_function' => 'Uunbekoande Funktion',
'math_lexing_error'     => "'Lexing'-Failer",
'math_syntax_error'     => 'Syntaxfailer',
'math_image_error'      => 'ju PNG-Konvertierenge sluuch fail',
'math_bad_tmpdir'       => 'Kon dät Temporärferteeknis foar mathematiske Formeln nit anlääse of beschrieuwe.',
'math_bad_output'       => 'Kon dät Sielferteeknis foar mathematiske Formeln nit anlääse of beschrieuwe.',
'math_notexvc'          => 'Dät texvc-Program kon nit fuunen wäide. Beoachte jädden math/README.',
'prefs-personal'        => 'Benutserdoaten',
'prefs-rc'              => 'Bekoandreekenge fon "Lääste Annerengen"',
'prefs-watchlist'       => 'Beooboachtengslieste',
'prefs-misc'            => 'Ferscheedene Ienstaalengen',
'saveprefs'             => 'Ienstaalengen spiekerje',
'resetprefs'            => 'Ienstaalengen touräächsätte',
'oldpassword'           => 'Oold Paaswoud:',
'newpassword'           => 'Näi Paaswoud:',
'retypenew'             => 'Näi Paaswoud (nochmoal):',
'textboxsize'           => 'Beoarbaidje',
'rows'                  => 'Riegen',
'searchresultshead'     => 'Säike',
'resultsperpage'        => 'Träffere pro Siede:',
'contextlines'          => 'Teekene pro Träffer:',
'contextchars'          => 'Teekene pro Riege:',
'stub-threshold'        => '<a href="#" class="stub">Kuute Artikkele</a> markierje bi (in Byte):',
'recentchangescount'    => 'Antaal fon do Iendraage in "Lääste Annerengen":',
'savedprefs'            => 'Dien Ienstaalengen wuuden spiekerd.',
'timezonelegend'        => 'Tiedzone',
'timezonetext'          => 'Reek ju Antaal fon Uuren ien, do twiske Jou Tiedzone un UPC lääse.',
'localtime'             => 'Tied bie Jou:',
'timezoneoffset'        => 'Unnerscheed¹:',
'servertime'            => 'Aktuälle Tied ap dän Server:',
'guesstimezone'         => 'Ienföigje uut dän Browser',
'allowemail'            => 'Emails fon uur Benutsere kriegen',
'defaultns'             => 'In disse Noomensruume schäl standoardmäitich soacht wäide:',
'default'               => 'Standoardienstaalenge',
'files'                 => 'Doatäie',

# User rights
'userrights-lookup-user'     => 'Ferwaltede Gruppentouheeregaid',
'userrights-user-editname'   => 'Benutsernoome anreeke:',
'editusergroup'              => 'Beoarbaidede Benutsergjuchte',
'userrights-editusergroup'   => 'Beoarbaide Gruppentouheeregaid fon dän Benutser',
'saveusergroups'             => 'Spiekerje Gruppentouheeregaid',
'userrights-groupsmember'    => 'Meeglid fon:',
'userrights-groupsavailable' => 'Ferföigboare Gruppen:',
'userrights-groupshelp'      => "Wääl do Gruppen, uut do die Benutser wächhoald of tou do hie touföiged wäide schäl. Nit selektierde Gruppen wäide nit annerd. Ne Uutwoal kon mäd '''Strg + Linksklick''' wier wächhoald wäide.",

# Groups
'group'            => 'Gruppe:',
'group-bot'        => 'Bots',
'group-sysop'      => 'Administratore',
'group-bureaucrat' => 'Bürokraten',
'group-all'        => '(aal)',

'group-autoconfirmed-member' => 'Bestäätigede Benutser',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Administrator',
'group-bureaucrat-member'    => 'Bürokrat',

'grouppage-autoconfirmed' => '{{ns:project}}:Bestäätigede Benutser',
'grouppage-bot'           => '{{ns:project}}:Bots',
'grouppage-sysop'         => '{{ns:project}}:Administratore',
'grouppage-bureaucrat'    => '{{ns:project}}:Bürokraten',

# User rights log
'rightslog'     => 'Gjuchte-Logbouk',
'rightslogtext' => 'Dit is dät Logbouk fon do Annerengen fon do Benutsergjuchte.',
'rightsnone'    => '(-)',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|Annerenge|Annerengen}}',
'recentchanges'                     => 'Lääste Annerengen',
'recentchanges-feed-description'    => 'Ferfoulge mäd dissen Feed do lääste Annerengen in {{SITENAME}}.',
'rcnote'                            => "Anwiesd {{PLURAL:$1|wäd '''1''' Annerenge|wäide do lääste '''$1''' Annerengen}} in do {{PLURAL:$2|lääste Dai|lääste '''$2''' Deege}}, fon $3. (<b><tt>Näi</tt></b>&nbsp;– näien Artikkel; <b><tt>L</tt></b>&nbsp;– litje Annerenge)",
'rcnotefrom'                        => 'Anwiesd wäide do Annerengen siet <b>$2</b> (max. <b>$1</b> Iendraage).',
'rclistfrom'                        => 'Bloot näie Annerengen siet $1 wiese.',
'rcshowhideminor'                   => 'Litje Annerengen $1',
'rcshowhidebots'                    => 'Bots $1',
'rcshowhideliu'                     => 'Anmäldede Benutser $1',
'rcshowhideanons'                   => 'Anonyme Benutser $1',
'rcshowhidepatr'                    => 'Pröiwede Annerengen $1',
'rcshowhidemine'                    => 'Oaine Biedraage $1',
'rclinks'                           => 'Wiese do lääste $1 Annerengen; wiese do lääste $2 Deege.<br />$3',
'diff'                              => 'Unnerschied',
'hist'                              => 'Versione',
'hide'                              => 'ferbierge',
'show'                              => 'ienbländje',
'minoreditletter'                   => 'L',
'newpageletter'                     => 'Näi',
'boteditletter'                     => 'B',
'number_of_watching_users_pageview' => '[$1 beooboachtjende {{PLURAL:$1|Benutser|Benutsere}}]',
'rc_categories_any'                 => 'Aal',
'rc-change-size'                    => '$1 {{PLURAL:$1|Byte|Bytes}}',
'newsectionsummary'                 => 'Näie Apsats /* $1 */',

# Recent changes linked
'recentchangeslinked'          => 'Annerengen an ferlinkede Sieden',
'recentchangeslinked-title'    => 'Annerengen an Sieden, do der fon „$1“ ferbuunden sunt',
'recentchangeslinked-noresult' => 'In dän uutwäälde Tiedruum wuuden an do ferbuundene Sieden neen Annerengen foarnuumen.',
'recentchangeslinked-summary'  => "Disse Spezialsiede liestet do lääste Annerengen fon ferbuundene Sieden ap. Sieden ap dien Beooboachtengslieste sunt '''fat''' schrieuwen.",

# Upload
'upload'                      => 'Hoochleede',
'uploadbtn'                   => 'Doatäi hoochleede',
'reupload'                    => 'Fonnäien hoochleede',
'reuploaddesc'                => 'Tourääch tou Hoochleede-Siede.',
'uploadnologin'               => 'Nit anmälded',
'uploadnologintext'           => 'Du moast [[Special:Userlogin|anmälded weese]], uum Doatäie hoochleede tou konnen.',
'uploaderror'                 => 'Failer bie dät Hoochleeden',
'uploadtext'                  => "Uum hoochleedene Bielden tou säiken un tou bekiekjen, gunge jädden tou ju [[Special:Imagelist|Lieste fon hoochleedene Bielden]]. 

Benutse jädden dät Formular, uum näie Bielden hoochtouleeden un do in Artikkele tou ferweenden. In do maaste Browsere wollen Jie n \"Truchsäike\"-Fäild sjoo, dät n Standoard-Doatäidialog eepent. Säik jädden ne Doatäi uut. Ju Doatäi wäd dan in dät Textfäild anwiesd. Bestäätigje Jie dan ju Copyright-Fereenboarenge. Toulääst drukke Jie dän \"Hoochleede\"-Knoop. Dät kon ne Wiele duurje, besunners bie ne loangsoame Internet-Ferbiendenge. Foar Photos is ap Bääste dät JPEG-Formoat, foar Teekengen un Symbole dät PNG-Formoat. 

Uum ne Bielde in n Artikkel tou ferweenden, schrieuwe Jie an ju Steede fon de Bielde:
* '''<tt><nowiki>[[</nowiki>{{ns:image}}:Doatäi.jpg<nowiki>]]</nowiki></tt>'''
* '''<tt><nowiki>[[</nowiki>{{ns:image}}:Doatäi.jpg|Link-Text<nowiki>]]</nowiki></tt>'''

Uum ne Medium in n Artikkel tou ferweenden, schrieuwe Jie an ju Steede fon de Medium:
* '''<tt><nowiki>[[</nowiki>{{ns:media}}:Doatäi.ogg<nowiki>]]</nowiki></tt>'''
* '''<tt><nowiki>[[</nowiki>{{ns:media}}:Doatäi.ogg|Link-Text<nowiki>]]</nowiki></tt>'''

Jädden beoachtje Jie, dät, juust as bie do Artikkele, uur Benutsere hiere Doatäie läskje of annerje konnen.",
'uploadlog'                   => 'Doatäi-Logbouk',
'uploadlogpage'               => 'Doatäi-Logbouk',
'uploadlogpagetext'           => 'Hier is ju Lieste fon do lääste hoochleedene Doatäie, sjuch uk [[{{ns:special}}:Newimages]].',
'filename'                    => 'Doatäinoome',
'filedesc'                    => 'Beschrieuwenge, Wälle',
'fileuploadsummary'           => 'Beschrieuwenge/Wälle:',
'filesource'                  => 'Wälle',
'uploadedfiles'               => 'Hoochleedene Doatäie',
'ignorewarning'               => 'Woarschauenge ignorierje un Doatäi daach spiekerje.',
'ignorewarnings'              => 'Woarschauengen ignorierje',
'minlength1'                  => 'Bieldedoatäien mouten mindestens tjoo Bouksteeuwen foar dän (eersten) Punkt hääbe.',
'illegalfilename'             => 'Die Doatäinoome "$1" änthaalt ap minste een nit toulät Teeken. Benaam jädden ju Doatäi uum un fersäik, hier fon näien hoochtouleeden.',
'badfilename'                 => 'Die Datäi-Noome is automatisk annerd tou "$1".',
'large-file'                  => 'Jädden neen Bielde uur $1 hoochleede; disse Doatäi is $2 groot.',
'largefileserver'             => 'Disse Doatäi is tou groot, deer die Server so konfigurierd is, dät Doatäien bloot bit tou ne bestimde Grööte apzeptierd wäide.',
'emptyfile'                   => 'Ju hoochleedene Doatäi is loos. Die Gruund kon n Typfailer in dän Doatäinoome weese. Kontrollierje jädden, of du ju Doatäi wuddelk hoochleede wolt.',
'fileexists'                  => "Ne Doatäi mäd dissen Noome bestoant al. Wan du ap 'Doatäi spiekerje' klikst, wäd ju Doatäi
uurschrieuwen. Unner $1 koast du die bewisje, of du dät wuddelk wolt.",
'fileexists-forbidden'        => 'Mäd dissen Noome bestoant al ne Doatäi. Gung jädden tourääch un leede dien Doatäi unner n uur Noome hooch. [[Bielde:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Mäd dissen Noome bestoant al ne Doatäi ap Wikipedia Commons. Gung jädden tourääch un leede dien Doatäi unner n uur Noome hooch. [[Bielde:$1|thumb|center|$1]]',
'successfulupload'            => 'Mäd Ärfoulch hoochleeden',
'uploadwarning'               => 'Woarschauenge',
'savefile'                    => 'Doatäi spiekerje',
'uploadedimage'               => '"[[$1]]" hoochleeden',
'uploaddisabled'              => 'Äntscheeldigenge, dät Hoochleeden is apstuuns deaktivierd.',
'uploaddisabledtext'          => 'Dat Hoochleeden fon Doatäie is in dissen Wiki nit muugelk.',
'uploadscripted'              => 'Disse Doatäi änthaalt HTML- of Scriptcode, ju bie Fersjoon fon aan Webbrowser apfierd wäide kuude.',
'uploadcorrupt'               => 'Ju Doatäi is beschäädiged of häd n falsken Noome. Uurpröiwe jädden ju Doatäi un leede ju fonnäien hooch.',
'uploadvirus'                 => 'Disse Doatäi änthaalt n Virus! Details: $1',
'sourcefilename'              => 'Wäldoatäi',
'watchthisupload'             => 'Disse Siede beooboachtje',

'license'   => 'Lizenz',
'nolicense' => 'naan Foaruutwoal',

# Image list
'imagelist'                 => 'Bieldelieste',
'imagelisttext'             => 'Hier is ne Lieste fon $1 Bielden, sortierd $2.',
'getimagelist'              => 'Leede Bieldelieste',
'ilsubmit'                  => 'Säik',
'showlast'                  => 'Wies do lääste $1 Bielden, sortierd ätter $2.',
'byname'                    => 'ätter Noome',
'bydate'                    => 'ätter Doatum',
'bysize'                    => 'ätter Grööte',
'imgdelete'                 => 'Läskje',
'imgdesc'                   => 'Beschrieuwenge',
'imgfile'                   => 'Doatäi',
'filehist'                  => 'Doatäiversione',
'filehist-help'             => 'Klik ap n Tiedpunkt, uum disse Version tou leeden.',
'filehist-deleteall'        => 'Aal do Versione läskje',
'filehist-deleteone'        => 'Disse Version läskje',
'filehist-revert'           => 'touräächsätte',
'filehist-current'          => 'aktuäl',
'filehist-datetime'         => 'Version fon',
'filehist-user'             => 'Benutser',
'filehist-dimensions'       => 'Höchte un Bratte',
'filehist-filesize'         => 'Doatäigrööte',
'filehist-comment'          => 'Kommentoar',
'imagelinks'                => 'Bieldeferwiese',
'linkstoimage'              => 'Do foulgjende Artikkele benutsje disse Bielde: <br /><small>(Moonige Sieden wäide eventuell
moorfooldich liested, konnen in säildene Falle oawers uk miste. Dät kumt fon oolde Failere in
dän Software häär, man schoadet fääre niks.)</small>',
'nolinkstoimage'            => 'Naan Artikkel benutset disse Bielde.',
'sharedupload'              => 'Disse Doatäi is ne deelde Hoochleedenge un duur fon uur Projekte anwoand wäide.',
'shareduploadwiki'          => 'Jädden sjuch dän $1 foar wiedere Information.',
'shareduploadwiki-linktext' => 'Doatäi-Beschrieuwengssiede',
'noimage'                   => 'Ne Doatäi mäd dissen Noome existiert nit, du koast ju oawers $1.',
'noimage-linktext'          => 'hoochleede',
'uploadnewversion-linktext' => 'Ne näie Version fon disse Doatäi hoochleede',
'imagelist_date'            => 'Doatum',
'imagelist_name'            => 'Noome',
'imagelist_user'            => 'Benutser',
'imagelist_size'            => 'Grööte',
'imagelist_description'     => 'Beschrieuwenge',

# File reversion
'filerevert'         => 'Touräächsätte fon "$1"',
'filerevert-legend'  => 'Doatäi touräächsätte',
'filerevert-comment' => 'Kommentoar:',
'filerevert-submit'  => 'Touräächsätte',

# File deletion
'filedelete'         => 'Läskje "$1"',
'filedelete-legend'  => 'Läskje Doatäi',
'filedelete-comment' => 'Gruund:',
'filedelete-submit'  => 'Läskje',
'filedelete-success' => "'''\"\$1\"''' wuude läsked.",

# MIME search
'mimesearch' => 'Säike ätter MIME-Typ',
'download'   => 'Deelleede',

# Unwatched pages
'unwatchedpages' => 'Nit beooboachtede Sieden',

# List redirects
'listredirects' => 'Lieste fon Fäärelaitengs-Sieden',

# Unused templates
'unusedtemplates' => 'Nit benutsede Foarloagen',

# Random page
'randompage' => 'Toufällige Siede',

# Random redirect
'randomredirect' => 'Toufällige Fäärelaitenge',

# Statistics
'statistics'    => 'Statistik',
'sitestats'     => 'Siedenstatistik',
'userstats'     => 'Benutserstatistik',
'userstatstext' => "Dät rakt '''$1''' {{PLURAL:$1|registrierde|registrierde}} [[Special:Listusers|Benutser]].
Deerfon {{PLURAL:$2|häd|hääbe}} '''$2''' Benutser (=$4 %) $5-Rechte.",

'disambiguations'     => 'Begriepskläärengssieden',
'disambiguationspage' => 'Template:Begriepskläärenge',

'doubleredirects'     => 'Dubbelde Fäärelaitengen',
'doubleredirectstext' => '<b>Oachtenge:</b> Disse Lieste kon "falske Positive" änthoolde. Dät is dan dän Fal, wan aan
Fäärelaitengen buute dän Fäärelaitenge-Ferwies noch wiedere Text mäd uur Ferwiesen änthaalt. Doo
Lääste schällen dan wächhoald wäide.',

'brokenredirects'        => 'Ferkierde Truchferwiese',
'brokenredirectstext'    => 'Disse Truchferwiese laitje tou nit existierjende Artikkel:',
'brokenredirects-delete' => '(läskje)',

'withoutinterwiki' => 'Sieden sunner Ferbiendengen tou uur Sproaken',

'fewestrevisions' => 'Sieden mäd do minste Versione',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|Byte|Bytes}}',
'ncategories'             => '$1 {{PLURAL:$1|Kategorie|Kategorien}}',
'nlinks'                  => '{{PLURAL:$1|1 Ferwiese|$1 Ferwiese}}',
'nmembers'                => '{{PLURAL:$1|1 Iendraach|$1 Iendraage}}',
'nrevisions'              => '{{PLURAL:$1|1 Beoarbaideng|$1 Beoarbaidengen}}',
'nviews'                  => '{{PLURAL:$1|1 Oufroage|$1 Oufroagen}}',
'lonelypages'             => 'Ferwaisde Sieden',
'uncategorizedpages'      => 'Nit kategorisierde Sieden',
'uncategorizedcategories' => 'Nit kategorisierde Kategorien',
'uncategorizedimages'     => 'Nit kategorisierde Bielden',
'uncategorizedtemplates'  => 'Nit kategorisierde Foarloagen',
'unusedcategories'        => 'Ferwaisede Kategorien',
'unusedimages'            => 'Ferwaisede Bielden',
'popularpages'            => 'Sieden do oafte bekieked wäide',
'wantedcategories'        => 'Benutsede, man nit anlaide Kategorien',
'wantedpages'             => 'Wonskede Sieden',
'mostlinked'              => 'Maast ferlinkede Sieden',
'mostlinkedcategories'    => 'Maast benutsede Kategorien',
'mostlinkedtemplates'     => 'Maastbenutsede Foarloagen',
'mostcategories'          => 'Maast kategorisierde Artikkele',
'mostimages'              => 'Maast benutsede Bielden',
'mostrevisions'           => 'Artikkel mäd do maaste Versione',
'allpages'                => 'Aal Artikkele',
'prefixindex'             => 'Aal Artikkele (mäd Präfix)',
'shortpages'              => 'Kuute Artikkel',
'longpages'               => 'Loange Artikkel',
'deadendpages'            => 'Siede sunner Ferwiese',
'protectedpages'          => 'Schutsede Sieden',
'listusers'               => 'Benutser-Lieste',
'specialpages'            => 'Spezioalsieden',
'spheading'               => 'Spezioalsieden foar alle Benutsere',
'restrictedpheading'      => 'Spezialsieden foar Administratore',
'rclsub'                  => '(ap Artikkel fon "$1")',
'newpages'                => 'Näie Artikkel',
'ancientpages'            => 'Siet loang uunbeoarbaidede Sieden',
'move'                    => 'ferschäuwen',
'movethispage'            => 'Artikel ferschuuwe',
'unusedimagestext'        => '<p>Beoachtje jädden, dät uur Websieden muugelkerwiese eenige fon disse Bielden benutsje.</p>',
'unusedcategoriestext'    => 'Do foulgjende Kategorien bestounde, wan do apstuuns uk nit in Ferweendenge sunt.',
'notargettitle'           => 'Naan Artikkel anroat',
'notargettext'            => 'Du hääst nit anroat, ap wäkke Siede disse Funktion anwoand wäide schäl.',

# Book sources
'booksources' => 'ISBN-Säike',

'categoriespagetext' => 'Do foulgjende Kategorien existierje in de Wiki.',
'data'               => 'Failer in dän Doatenboank',
'userrights'         => 'Benutsergjuchteferwaltenge',
'groups'             => 'Benutsergruppen',
'alphaindexline'     => '$1 bit $2',
'version'            => 'Version',

# Special:Log
'specialloguserlabel'  => 'Benutser:',
'speciallogtitlelabel' => 'Tittel:',
'log'                  => 'Logbouke',
'all-logs-page'        => 'Aal Logbouke',
'alllogstext'          => 'Dit is ne kombinierde Anwiesenge fon aal Logs fon {{SITENAME}}.',
'logempty'             => 'Neen paasende Iendraage.',

# Special:Allpages
'nextpage'          => 'Naiste Siede ($1)',
'prevpage'          => 'Foarige Siede ($1)',
'allpagesfrom'      => 'Sieden wiese fon:',
'allarticles'       => 'Aal do Artikkele',
'allinnamespace'    => 'Aal Sieden in $1 Noomenruum',
'allnotinnamespace' => 'Aal Sieden, bute in dän $1 Noomenruum',
'allpagesprev'      => 'Foargungende',
'allpagesnext'      => 'Naiste',
'allpagessubmit'    => 'Anweende',
'allpagesprefix'    => 'Sieden anwiese mäd Präfix:',

# E-mail user
'mailnologin'     => 'Du bäst nit anmälded.',
'mailnologintext' => 'Du moast [[{{ns:special}}:Userlogin|anmälded weese]] un sälwen ne [[{{ns:special}}:Confirmemail|gultige]] E-Mail-Adrässe anroat hääbe, uum uur Benutsere ne E-Mail tou seenden.',
'emailuser'       => 'Seende E-Mail an disse Benutser',
'emailpage'       => 'E-mail an Benutser',
'emailpagetext'   => 'Wan disse Benutser ne gultige Email-Adrässe anroat häd, konnen Jie him mäd dän unnerstoundene Formuloar ne E-mail seende. As Ouseender wäd ju E-mail-Adrässe uut Jou Ienstaalengen iendrain, deermäd die Benutser Jou oantwoudje kon.',
'usermailererror' => 'Dät Mail-Objekt roat n Failer tourääch:',
'noemailtitle'    => 'Neen Email-Adrässe',
'noemailtext'     => 'Disse Benutser häd neen gultige Email-Adrässe anroat of moate neen E-Mail fon uur Benutsere ämpfange.',
'emailfrom'       => 'Fon',
'emailto'         => 'An',
'emailsubject'    => 'Beträf',
'emailmessage'    => 'Ättergjucht',
'emailsend'       => 'Seende',
'emailsent'       => 'Begjucht fersoand',
'emailsenttext'   => 'Jou Begjucht is soand wuuden.',

# Watchlist
'watchlist'            => 'Beooboachtengslieste',
'mywatchlist'          => 'Beooboachtengslieste',
'watchlistfor'         => "(foar '''$1''')",
'nowatchlist'          => 'Du hääst neen Iendraage ap dien Beooboachtengslieste. Du moast anmälded weese, dät die een Beooboachtengslieste tou Ferföigenge stoant.',
'watchnologin'         => 'Du bäst nit anmälded',
'watchnologintext'     => 'Du moast [[Special:Userlogin|anmälded]] weese, uum dien Beooboachtengslieste tou beoarbaidjen.',
'addedwatch'           => 'An Foulgelieste touföiged.',
'addedwatchtext'       => "Die Artikkel \"[[:\$1]]\" wuude an dien [[Special:Watchlist|Foulgelieste]] touföiged.
Leetere Annerengen an dissen Artikkel un ju touheerende Diskussionssiede wäide deer liested
un die Artikkel wäd in ju [[Special:Recentchanges|fon do lääste Annerengen]] in '''Fatschrift''' anroat. 

Wan du die Artikkel wier fon ju Foulgelieste ou hoalje moatest, klik ap ju Siede ap \"Ferjeet disse Siede\".",
'removedwatch'         => 'Fon ju Beooboachtengsslieste ou hoald',
'watch'                => 'Beooboachtje',
'watchthispage'        => 'Siede beooboachtje',
'unwatch'              => 'Nit moor beooboachtje',
'unwatchthispage'      => 'Nit moor beooboachtje',
'notanarticle'         => 'Naan Artikkel',
'watchnochange'        => 'Neen fon do Sieden, do du beooboachtest, wuude in dän läästen Tiedruum beoarbaided.',
'watchlist-details'    => 'Jie beooboachtje apstuuns mädnunner {{PLURAL:$1|1 Artikkel|$1 Artikkele}} (Diskussionssieden wuuden hier nit meetäld).',
'wlheader-enotif'      => '* E-Mail-Bescheed is aktivierd.',
'wlheader-showupdated' => "* Sieden, do ätter dien lääste Besäik annerd wuuden sunt, wäide '''fat''' deerstoald.",
'watchmethod-recent'   => 'Uurpröiwjen fon do lääste Beoarbaidengen foar ju Beooboachtengslieste',
'watchmethod-list'     => 'Uurpröiwjen fon ju Beooboachtengslieste ätter lääste Beoarbaidengen',
'watchlistcontains'    => 'Jou Beooboachtengslieste änthaalt $1 {{PLURAL:$1|Siede|Sieden}}.',
'iteminvalidname'      => "Problem mäd dän Iendraach '$1', ungultige Noome...",
'wlnote'               => "Hier {{PLURAL:$1|foulget do lääste Annerenge|foulgje do lääste '''$1''' Annerengen}} fon do lääste {{PLURAL:$2|Uur|'''$2''' Uuren}}.",
'wlshowlast'           => 'Wies do lääste $1 Uuren, $2 Deege, of $3 (in do lääste 30 Deege).',
'watchlist-show-bots'  => 'Bot-Annerengen ienbländje',
'watchlist-hide-bots'  => 'Bot-Annerengen ferbierge',
'watchlist-show-own'   => 'oaine Annerengen ienbländje',
'watchlist-hide-own'   => 'oaine Annerengen ferbierge',
'watchlist-show-minor' => 'litje Annerengen ienbländje',
'watchlist-hide-minor' => 'litje Annerengen ferbierge',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Beooboachtje …',
'unwatching' => 'Nit beooboachtje …',

'enotif_mailer'                => '{{SITENAME}} tält Bescheed uur Email',
'enotif_reset'                 => 'Markier aal besoachte Sieden',
'enotif_newpagetext'           => 'Dit is ne näie Siede.',
'enotif_impersonal_salutation' => '{{SITENAME}} Benutser',
'changed'                      => 'annerd',
'created'                      => 'näi anlaid',
'enotif_subject'               => '{{SITENAME}} Siede $PAGETITLE wuude $CHANGEDORCREATED fon $PAGEEDITOR',
'enotif_lastdiff'              => '$1 wiest alle Annerengen mäd aan Glap.',
'enotif_body'                  => 'Ljoowe $WATCHINGUSERNAME, 

ju {{SITENAME}} Siede $PAGETITLE wuude fon $PAGEEDITOR an dän $PAGEEDITDATE $CHANGEDORCREATED, ju aktuälle Version is: $PAGETITLE_URL 

$NEWPAGE 

Touhoopefoatenge fon dän Editor: $PAGESUMMARY $PAGEMINOREDIT 

Kontakt toun Editor: 
Mail $PAGEEDITOR_EMAIL 
Wiki $PAGEEDITOR_WIKI 

Deer wäide soloange neen wiedere Mails toun Bescheed soand, bit Jie ju Siede wier besäike. Ap Jou Beooboachtengssiede konnen Jie aal Markere toun Bescheed touhoope touräächsätte. 

Jou früntelke {{SITENAME}} Becheedtälsystem 

--

Jou Beooboachtengslieste 
{{fullurl:{{ns:special}}:Watchlist/edit}}

Hälpe tou ju Benutsenge rakt 
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete/protect/revert
'deletepage'                  => 'Siede läskje',
'confirm'                     => 'Bestäätigje',
'excontent'                   => "Oolde Inhoold: '$1'",
'excontentauthor'             => "Inhoold waas: '$1' (eensige Benutser: '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'               => "Inhoold foar dät Loosmoakjen fon de Siede: '$1'",
'exblank'                     => 'Siede waas loos',
'confirmdelete'               => 'Läskenge bestäätigje',
'deletesub'                   => '(Läskje "$1")',
'historywarning'              => 'WOARSCHAUENGE: Ju Siede, ju du läskje moatest, häd ne Versionsgeschichte: &nbsp;',
'confirmdeletetext'           => 'Jie sunt deerbie, n Artikkel of ne Bielde un aal allere Versione foar altied uut dän Doatenboank tou läskjen. Bitte bestäätigje Jie Jou Apsicht, dät tou dwoon, dät Jie Jou do Konsekwänsen bewust sunt, un dät Jie in Uureenstämmenge mäd uus [[{{MediaWiki:Policy-url}}]] honnelje.',
'deletedtext'                 => '"$1" wuude läsked. 
In $2 fiende Jie ne Lieste fon do lääste Läskengen.',
'deletedarticle'              => '"$1" wuude läsked',
'dellogpage'                  => 'Läsk-Logbouk',
'dellogpagetext'              => 'Hier is ne Lieste fon do lääste Läskengen.',
'deletionlog'                 => 'Läsk-Logbouk',
'reverted'                    => 'Ap ne oolde Version touräächsät',
'deletecomment'               => 'Gruund foar ju Läskenge',
'rollback'                    => 'Touräächsätten fon do Annerengen',
'rollback_short'              => 'Touräächsätte',
'rollbacklink'                => 'touräächsätte',
'rollbackfailed'              => 'Touräächsätten misglukked',
'cantrollback'                => 'Disse Annerenge kon nit touräächstoald wäide; deer et naan fröieren Autor rakt.',
'editcomment'                 => 'Ju Annerengskommentoar waas: "<i>$1</i>".', # only shown if there is an edit comment
'revertpage'                  => 'Annerengen fon [[User:$2|$2]] ([[{{ns:special}}:Contributions/$2|Biedraage]]) tounichte moaked un lääste Version fon $1 wierhäärstoald',
'sessionfailure'              => 'Dät roat n Problem mäd ju Uurdreegenge fon dien Benutserdoaten. Disse Aktion wuude deeruum sicherheidshoolwe oubreeken, uum ne falske Touoardnenge fon dien Annerengen tou n uur Benutser tou ferhinnerjen. Gung jädden tourääch un fersäik dän Foargong fonnäien uuttoufieren.',
'protectlogpage'              => 'Siedenschuts-Logbouk',
'protectlogtext'              => 'Dit is ne Lieste fon do blokkierde Sieden. Sjuch [[{{ns:special}}:Protectedpages|Schutsede Siede]] foar moor Informatione.',
'unprotectedarticle'          => 'hieuwede dän Schuts fon "[[$1]]" ap',
'protectcomment'              => 'Gruund:',
'unprotectsub'                => '(Aphieuwenge fon ju Speerenge fon "$1")',
'protect-unchain'             => 'Ferschuuweschuts annerje',
'protect-text'                => "Hier koast du dän Schutsstoatus foar ju Siede '''$1''' ienkiekje un annerje.",
'protect-locked-access'       => 'Du bäst nit begjuchtiged, dän Siedenschutsstoatus tou annerjen. Hier is die aktuälle Schutsstoatus fon ju Siede <strong>$1</strong>:',
'protect-default'             => 'Aal (Standoard)',
'protect-level-autoconfirmed' => 'Speerenge foar nit registrierde Benutsere',
'protect-level-sysop'         => 'Bloot Administration',
'pagesize'                    => '(Bytes)',

# Restrictions (nouns)
'restriction-edit' => 'beoarbaidje',
'restriction-move' => 'ferschäuwen',

# Undelete
'undelete'                 => 'Läskede Siede wier häärstaale',
'undeletepage'             => 'Läskede Siede wier häärstaale',
'viewdeletedpage'          => 'Läskede Versione anwiese',
'undeletepagetext'         => 'Do foulgjende Sieden wuuden läsked, man sunt altied noch spiekerd un konnen fon Administratore wier häärstoald wäide:',
'undeleterevisions'        => '{{PLURAL:$1|1 Version|$1 Versione}} archivierd',
'undeletehistory'          => 'Wan Jie disse Siede wier häärstaale, wäide uk aal oolde Versione wier häärstoald. Wan siet ju Läskenge aan näien Artikkel mäd dän sälge Noome moaked wuude, wäide do wier häärstoalde Versione as oolde Versione fon dissen Artikkel ferschiene.',
'undelete-revision'        => 'Läskede Versione fon $1 - $2, $3:',
'undeletebtn'              => 'Wier häärstaale',
'undeletereset'            => 'Oubreeke',
'undeletecomment'          => 'Gruund:',
'undeletedarticle'         => 'häd "[[$1]]" wier häärstoald',
'undeletedrevisions'       => '{{PLURAL:$1|1 Version wuude|$1 Versione wuuden}} wier häärstoald',
'undeletedrevisions-files' => '{{PLURAL:$1|1 Version|$1 Versione}} un {{PLURAL:$2|1 Doatäi|$2 Doatäie}} wuuden wier häärstoald',
'undeletedfiles'           => '{{PLURAL:$1|1 Doatäie wuude|$1 Doatäie wuuden}} wier häärstoald',
'undelete-search-submit'   => 'Säike',

# Namespace form on various pages
'namespace'      => 'Noomensruum:',
'invert'         => 'Uutwoal uumekiere',
'blanknamespace' => '(Sieden)',

# Contributions
'contributions' => 'Benutserbiedraage',
'mycontris'     => 'Oaine Biedraage',
'contribsub2'   => 'Foar $1 ($2)',
'nocontribs'    => 'Deer wuuden neen Annerengen foar disse Kriterien fuunen.',
'uclinks'       => 'Wies do lääste $1 Biedraage fon dän Benutser in do lääste $2 Deege.',
'uctop'         => ' (aktuäl)',
'month'         => 'un Mound:',
'year'          => 'bit Jier:',

'sp-contributions-newest'      => 'Jungste',
'sp-contributions-oldest'      => 'Ooldste',
'sp-contributions-newer'       => 'Näiere $1',
'sp-contributions-older'       => 'Allere $1',
'sp-contributions-newbies'     => 'Wies bloot Biedraage fon näie Benutsere',
'sp-contributions-newbies-sub' => 'Foar Näilinge',
'sp-contributions-blocklog'    => 'Speerlogbouk',
'sp-contributions-search'      => 'Säike ätter Benutserbiedraage',
'sp-contributions-username'    => 'IP-Adrässe af Benutsernoome:',
'sp-contributions-submit'      => 'Säike',

# What links here
'whatlinkshere'       => 'Links ap disse Siede',
'whatlinkshere-title' => 'Sieden, do ap "$1" linken',
'whatlinkshere-page'  => 'Siede:',
'linklistsub'         => '(Linklieste)',
'linkshere'           => "Do foulgjende Sieden ferwiese hierhäär:  '''[[:$1]]''': <br /><small>(Moonige Sieden wäide eventuell moorfooldich liested, konnen in säildene Falle oawers uk miste. Dät kumt fon oolde Failere in dän Software häär, man schoadet fääre niks.)</small>",
'nolinkshere'         => "Naan Artikkel ferwiest hierhäär: '''[[:$1]]'''.",
'isredirect'          => 'Fäärelaitengs-Siede',
'istemplate'          => 'Foarloagenienbiendenge',
'whatlinkshere-prev'  => '{{PLURAL:$1|foarige|foarige $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|naiste|naiste $1}}',
'whatlinkshere-links' => '← Links',

# Block/unblock
'blockip'                     => 'Blokkierje Benutser',
'ipaddress'                   => 'IP-Adrässe:',
'ipbexpiry'                   => 'Oulooptied (Speerduur):',
'ipbreason'                   => 'Begruundenge:',
'ipbsubmit'                   => 'Adrässe blokkierje',
'ipbother'                    => 'Uur Duur (ängelsk):',
'ipboptions'                  => '1 Uure:1 hour,2 Uuren:2 hours,6 Uuren:6 hours,1 Dai:1 day,3 Deege:3 days,1 Wiek:1 week,2 Wieke:2 weeks,1 Mound:1 month,3 Mounde:3 months,1 Jier:1 year,Uunbestimd:indefinite',
'ipbotheroption'              => 'Uur Duur',
'badipaddress'                => 'Dissen Benutser bestoant nit, d.h. die Noome is falsk',
'blockipsuccesssub'           => 'Blokkoade geloangen',
'blockipsuccesstext'          => 'Ju IP-Adrässe [[Special:Contributions/$1|$1]] wuude blokkierd.
<br />[[Special:Ipblocklist|Lieste fon Blokkoaden]].',
'ipb-unblock-addr'            => '"$1" fräireeke',
'ipb-unblock'                 => 'IP-Adrässe/Benutser fräireeke',
'unblockip'                   => 'IP-Adrässe fräireeke',
'unblockiptext'               => 'Benutsje dät Formular, uum ne blokkierde IP-Adrässe fräitoureeken.',
'ipusubmit'                   => 'Disse Adrässe fräireeke',
'ipblocklist'                 => 'Lieste fon blokkierde IP-Adrässen',
'ipblocklist-submit'          => 'Säike',
'blocklistline'               => '$1, $2 blokkierde $3 ($4)',
'blocklink'                   => 'blokkierje',
'unblocklink'                 => 'fräireeke',
'contribslink'                => 'Biedraage',
'autoblocker'                 => 'Du wierst blokkierd, deer du eene IP-Adrässe mäd "[[User:$1|$1]]" benutsjen dääst. Foar ju Blokkierenge fon dän Benutser waas as Gruund anroat: "$2".',
'blocklogpage'                => 'Benutserblokkoaden-Logbouk',
'blocklogentry'               => '[[$1]] blokkierd foar n Tiedruum fon: $2 $3',
'blocklogtext'                => 'Dit is n Logbouk fon Speerengen un Äntspeerengen fon Benutsere. Ju Sunnersiede fiert aal aktuäl speerde Benutsere ap, iensluutend automatisk blokkierde IP-Adrässe.',
'unblocklogentry'             => 'Blokkade fon $1 aphieuwed',
'range_block_disabled'        => 'Ju Muugelkaid, ganse Adräsruume tou speeren, is nit aktivierd.',
'ipb_expiry_invalid'          => 'Ju anroate Oulooptied is nit gultich.',
'ip_range_invalid'            => 'Uungultige IP-Adräsberäk.',
'proxyblockreason'            => 'Jou IP-Adrässe wuude speerd, deer ju n eepenen Proxy is. Kontaktierje jädden Jou Provider af Jou Systemtechnik un informierje Jou jou uur dit muugelke Sicherhaidsproblem.',
'proxyblocksuccess'           => 'Kloor.',
'sorbsreason'                 => 'Dien IP-Adrässe is bie ju DNSBL fon {{SITENAME}} as eepene PROXY liested.',
'sorbs_create_account_reason' => 'Dien IP-Adrässe is bie ju DNSBL fon {{SITENAME}} as eepene PROXY liested. Du koast neen Benutser-Account anlääse.',

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
'lockdbsuccesstext'   => 'Ju {{SITENAME}}-Doatenboank wuude speerd.<br />Reek jädden [[Special:Unlockdb|ju Doatenboank wier fräi]], so gau ju Woarschauenge ousleeten is.',
'unlockdbsuccesstext' => 'Ju {{SITENAME}}-Doatenboank wuude fräiroat.',

# Move page
'movepage'                => 'Siede ferschäuwen',
'movepagetext'            => 'Mäd dissen Formular koast du ne Siede touhoope mäd aal Versione tou n uur Noome ferschuuwe. Foar dän oolde Noome wäd ne Fäärelaitenge tou dän Näie iengjucht. Ferwiese ap dän oolde Noome wäide nit annerd.',
'movepagetalktext'        => "Ju touheerige Diskussionssiede wäd, sofier deer, mee ferschäuwen, '''of dät moast weese''' 
* du ferschufst ju Siede in n uur [[Wikipedia:Noomensruum|Noomensruum]] 
* deer bestoant al n Diskussionssiede mäd dän näie Noome 
* du wäälst ju unnerstoundene Option ou. 

In disse Falle moast du ju Siede, wan wonsked, fon Hounde ferschuuwe. Jädden dän '''näie''' Tittel unner '''Siel''' iendreege, deerunner ju Uumnaamenge jädden '''begründje'''.",
'movenologin'             => 'Du bäst nit anmälded',
'movenologintext'         => 'Du moast n registrierden Benutser un [[Special:Userlogin|anmälded]] weese, uum ne Siede ferschuuwe tou konnen.',
'newtitle'                => 'Tou dän näie Tittel:',
'movepagebtn'             => 'Siede ferschäuwen',
'pagemovedsub'            => 'Ferschuuwenge mäd Ärfoulch',
'articleexists'           => 'Dät rakt al n Siede mäd disse Noome, of uurs is die Noome dän du anroat hääst, nit toulät.
Fersäik jädden n uur Noome.',
'talkexists'              => 'Ju Siede sälwen wuude mäd Ärfoulch ferschäuwen, man ju Diskussionssiede nit, deer al een mäd dän näie Tittel bestoant. Glieke jädden do Inhoolde fon Hounde ou.',
'movedto'                 => 'ferschäuwen ätter',
'movetalk'                => 'Ju Diskussionssiede mee ferschuuwe, wan muugelk.',
'talkpagemoved'           => 'Ju Diskussions-Siede wuude uk ferschäuwen.',
'talkpagenotmoved'        => 'Ju Diskussions-Siede wuude <strong>nit</strong> ferschäuwen.',
'1movedto2'               => 'häd "[[$1]]" ätter "[[$2]]" ferschäuwen',
'movelogpage'             => 'Ferschäuwengs-Logbouk',
'movelogpagetext'         => 'Dit is ne Lieste fon aal ferschäuwene Sieden.',
'movereason'              => 'Kuute Begründenge:',
'revertmove'              => 'tourääch ferschuuwe',
'delete_and_move'         => 'Läskje un ferschuuwe',
'delete_and_move_text'    => '==Sielartikkel is al deer, läskje?== 

Die Artikkel "[[$1]]" existiert al. Moatest du him foar ju Ferschuuwenge läskje?',
'delete_and_move_confirm' => 'Jee, Sielartikkel foar ju Ferschuuwenge läskje',
'delete_and_move_reason'  => 'Läsked uum Plats tou moakjen foar Ferschuuwenge',
'selfmove'                => 'Uursproangs- un Sielnoome sunt gliek; ne Siede kon nit tou sik ferschäuwen wäide.',
'immobile_namespace'      => 'Die wonskede Siedentittel is aan besunneren; ju Siede kon nit in dissen (uur) Noomensruum ferschäuwen wäide.',

# Export
'export'          => 'Sieden exportierje',
'exporttext'      => 'Du koast dän Täkst un ju Beoarbaidengshistorie fon ne bestimde Siede of fon n Uutwoal fon Sieden ättter XML exportierje.',
'exportcuronly'   => 'Bloot ju aktuälle Version fon de Siede exportierje',
'exportnohistory' => "--- 
'''Waiwiesenge:''' Die Export fon komplette Versionsgeschichten is uut Performancegruunden bit ap fääre nit muugelk. Ne Deelleedenge fon Versiongeschichten as Dump is oawers muugelk unner [http://download.wikimedia.org/ download.wikimedia.org] — ''Wikimedia-Serveradministratoren''.",

# Namespace 8 related
'allmessages'        => 'Aal Ättergjuchte',
'allmessagesname'    => 'Noome',
'allmessagesdefault' => 'Standardtext',
'allmessagescurrent' => 'Disse Text',
'allmessagestext'    => 'Dit is ne Lieste fon aal System-Ättergjuchte do in dän MediaWiki-Noomenruum tou Ferföigenge stounde.',

# Thumbnails
'thumbnail-more'  => 'fergratterje',
'missingimage'    => '<b>Failjende Bielde</b><br /><i>$1</i>',
'filemissing'     => 'Doatäi failt',
'thumbnail_error' => 'Failer bie dät Moakjen fon Foarschaubielde (Thumbnail): $1',

# Special:Import
'import'                => 'Sieden importierje',
'importtext'            => 'Exportiere ju Siede fon dän Wälwiki middels [[{{ns:special}}:Export]] un leede ju Doatäi dan uur disse Siede wier hooch.',
'importfailed'          => 'Import failsloain: $1',
'importnotext'          => 'Loos of neen Text',
'importsuccess'         => 'Import fuller Ärfoulch!',
'importhistoryconflict' => 'Deer bestounde al allere Versionen, do mäd disse kollidierje. Muugelkerwiese wuude ju Siede al eer importierd.',
'importnosources'       => 'Foar dän Transwiki Import sunt neen Wällen definierd un dät direkte Hoochleeden fon Versione is blokkierd.',
'importnofile'          => 'Deer is neen Importdoatäi hoochleeden wuuden.',
'importuploaderror'     => 'Dät Hoochleeden fon ju Importdoatäi glipte (sluuch fail). Fielicht is ju Doatäi gratter as toulät.',

# Import log
'importlogpage' => 'Import-Logbouk',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Oaine Benutsersiede',
'tooltip-pt-mytalk'               => 'Oaine Diskussionssiede',
'tooltip-pt-preferences'          => 'Oaine Ienstaalengen',
'tooltip-pt-watchlist'            => 'Lieste fon do beooboachtede Sieden',
'tooltip-pt-mycontris'            => 'Lieste fon oaine Biedraage',
'tooltip-pt-login'                => 'Jou ientoulogjen wäd wäil jädden blouked, man is neen Plicht.',
'tooltip-pt-logout'               => 'Oumäldje',
'tooltip-ca-talk'                 => 'Diskussion uur dän Inhoold fon ju Siede',
'tooltip-ca-edit'                 => 'Siede beoarbaidje. Jädden foar dät Spiekerjen ju Foarschaufunktion benutsje.',
'tooltip-ca-addsection'           => 'N Kommentoar tou disse Diskussion bietouföigje.',
'tooltip-ca-viewsource'           => 'Disse Siede is schutsed. Die Wältext kon ankieked wäide.',
'tooltip-ca-protect'              => 'Disse Siede schutsje',
'tooltip-ca-delete'               => 'Disse Siede läskje',
'tooltip-ca-move'                 => 'Disse Siede ferschäuwen',
'tooltip-ca-watch'                => 'Disse Siede an dien Beoobachtengslieste touföigje',
'tooltip-ca-unwatch'              => 'Disse Siede fon ju persöönelke Beooboachtengslieste wächhoalje',
'tooltip-search'                  => '{{SITENAME}} truchsäike',
'tooltip-n-mainpage'              => 'Haudsiede anwiese',
'tooltip-n-portal'                => 'Uur dät Portoal, wät du dwo koast, wier wät tou fienden is',
'tooltip-n-currentevents'         => 'Bäätergruundinformationen tou aktuelle Geböärnisse',
'tooltip-n-recentchanges'         => 'Lieste fon ju Lääste Annerengen in {{SITENAME}}.',
'tooltip-n-randompage'            => 'Toufällige Siede',
'tooltip-n-help'                  => 'Hälpesiede anwiese',
'tooltip-n-sitesupport'           => 'Unnerstutse uus',
'tooltip-t-whatlinkshere'         => 'Lieste fon aal Sieden, do ap disse Siede linken',
'tooltip-t-contributions'         => 'Lieste fon do Biedraage fon dissen Benutser ankiekje',
'tooltip-t-emailuser'             => 'Een E-Mail an dissen Benutser seende',
'tooltip-t-upload'                => 'Doatäie hoochleede',
'tooltip-t-specialpages'          => 'Lieste fon aal Spezialsieden',
'tooltip-ca-nstab-user'           => 'Benutsersiede anwiese',
'tooltip-ca-nstab-project'        => 'Projektsiede anwiese',
'tooltip-ca-nstab-image'          => 'Bieldesiede anwiese',
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

# Metadata
'nodublincore'      => 'Dublin-Core-RDF-Metadoaten sunt foar dissen Server deaktivierd.',
'nocreativecommons' => 'Creative-Commons-RDF-Metadoaten sunt foar dissen Server deaktivierd.',
'notacceptable'     => 'Die Wiki-Server kon do Doaten foar dien Uutgoawe-Reewe nit apberaitje.',

# Attribution
'siteuser'         => '{{SITENAME}}-Benutser $1',
'lastmodifiedatby' => 'Disse Siede wuude toulääst annerd uum $2, $1 fon $3.', # $1 date, $2 time, $3 user
'othercontribs'    => 'Basierd ap ju Oarbaid fon $1.',
'others'           => 'uur',
'siteusers'        => '{{SITENAME}}-Benutser $1',
'creditspage'      => 'Siedenstatistik',
'nocredits'        => 'Foar disse Siede sunt neen Informationen deer.',

# Spam protection
'spamprotectiontitle'    => 'Spamschutsfilter',
'spamprotectionmatch'    => "'''Die foulgjende Text wuude fon uus Spam-Filter fuunen: ''$1'''''",
'subcategorycount'       => 'Disse Kategorie häd {{PLURAL:$1|1 Unnerkategorie|$1 Unnerkategorien}}.',
'categoryarticlecount'   => 'Tou disse Kategorie heere $1 Artikkele.',
'category-media-count'   => 'Tou disse Kategorie heere $1 Artikkele.',
'listingcontinuesabbrev' => '(Foutsättenge)',
'spam_reverting'         => 'Lääste Version sunner Links tou $1 wier häärstoald.',
'spam_blanking'          => 'Aal Versione äntheelden Links tou $1, scheenmoaked.',

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

# Patrolling
'markaspatrolleddiff'   => 'As pröiwed markierje',
'markaspatrolledtext'   => 'Dissen Artikkel as pröiwed markierje',
'markedaspatrolled'     => 'As pröiwed markierd',
'markedaspatrolledtext' => 'Ju uutwoalde Artikkelannerenge wuude as pröiwed markierd.',
'rcpatroldisabled'      => 'Pröiwenge fon do lääste Annerengen speerd',
'rcpatroldisabledtext'  => 'Ju Pröiwenge fon do lääste Annerengen ("Recent Changes Patrol") is apstuuns speerd.',

# Patrol log
'patrol-log-diff' => 'Version $1',

# Image deletion
'deletedrevision' => 'Oolde Version $1 läsked',

# Browsing diffs
'previousdiff' => '← Toun foarigen Versionsunnerscheed',
'nextdiff'     => 'Toun naisten Versionsunnerscheed →',

# Media information
'mediawarning'         => "'''Warnung:''' Disse Oard fon Doatäi kon n schoadelken Programcode änthoolde. Truch dät Deelleeden of Eepenjen fon dissen Doatäi kon dän Computer Schoade toubroacht wäide. Al dät Anklikken fon dän Link kon deertou fiere, dät die Browser ju Doatäi eepen moaket un uunbekoande Programcode tou Uutfierenge kumt. Do Bedrieuwere fon ju Wikipedia uurnieme neen Feroantwoudenge foar dän Inhoold fon disse Doatäi! Schuul disse Doatäi wuddelk schoadelke Programcode änthoolde, schuul n Administrator informierd wäide.<hr />",
'thumbsize'            => 'Grööte fon do Foarschaubielden (thumbnails):',
'widthheightpage'      => '$1×$2, $3 Sieden',
'file-info-size'       => '($1 × $2 Pixel, Doatäigrööte: $3, MIME-Typ: $4)',
'file-nohires'         => '<small>Neen haagere Aplöösenge foarhounden.</small>',
'svg-long-desc'        => '(SVG-Doatäi, Basisgrööte: $1 × $2 Pixel, Doatäigrööte: $3)',
'show-big-image'       => 'Bielde in hooge Aplöösenge',
'show-big-image-thumb' => '<small>Grööte fon disse Foarschau: $1 × $2 Pixel</small>',

# Special:Newimages
'newimages'    => 'Näie Bielde',
'showhidebots' => '(Bots $1)',
'noimages'     => 'neen Bielden fuunen.',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'hours-abbrev' => 'U',

# Bad image list
'bad_image_list' => 'Formoat:

Bloot Riegen, do der mäd n * ounfange, wäide betrachted. As eerste ätter dän * mout ne Ferbiendenge ap ne nit wonskede Bielde stounde.
Deerap foulgjende Siedenferbiendengen in jusälge Riege definierje Uutnoamen, in dän Kontext wierfon ju Bielde daach ärschiene duur.',

# Metadata
'metadata'          => 'Metadoaten',
'metadata-help'     => 'Disse Doatäi änthaalt wiedere Informatione, do in de Räägel von dän Digitoalkamera of dän ferwoanden Scanner stammen dwo. Truch ätterdraine Beoarbaidenge fon ju Originoaldoatäi konnen eenige Details annerd wuuden weese.',
'metadata-expand'   => 'Wiedere Details ienbländje',
'metadata-collapse' => 'Details uutbländje',
'metadata-fields'   => 'Do foulgjende Fäildere fon do EXIF-Metadoaten in disse Media Wiki-Ättergjucht wäide ap Bieldbeschrieuwengssieden anwiesd; wiedere standdoardmäitich "ienklapte" Details konnen anwiesd wäide. 
* make 
* model 
* fnumber 
* datetimeoriginal 
* exposuretime 
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Bratte',
'exif-imagelength'                 => 'Laangte',
'exif-bitspersample'               => 'Bits pro Faawenkomponente',
'exif-compression'                 => 'Oard fon ju Kompression',
'exif-orientation'                 => 'Kamera-Uutgjuchtenge',
'exif-planarconfiguration'         => 'Doatenuutgjuchtenge',
'exif-yresolution'                 => 'Vertikoale Aplöösenge',
'exif-resolutionunit'              => 'Mäite-Eenhaid fon ju Aplöösenge',
'exif-stripoffsets'                => 'Bieldedoatenfersät',
'exif-rowsperstrip'                => 'Antaal Riegen pro Striepe',
'exif-jpeginterchangeformat'       => 'Offset tou JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Gratte fon do JPEG SOI-Doaten in Bytes',
'exif-transferfunction'            => 'Uurdreegengsfunktion',
'exif-datetime'                    => 'Spiekertiedpunkt',
'exif-imagedescription'            => 'Bieldetittel',
'exif-make'                        => 'Häärstaaler',
'exif-model'                       => 'Modäl',
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
'exif-aperturevalue'               => 'Bländenwäid',
'exif-brightnessvalue'             => 'Ljoachtegaidswäid',
'exif-exposurebiasvalue'           => 'Beljuchtengsfoargoawe',
'exif-maxaperturevalue'            => 'Grootste Blände',
'exif-subjectdistance'             => 'Fierte',
'exif-meteringmode'                => 'Meetferfoaren',
'exif-lightsource'                 => 'Luchtwälle',
'exif-flash'                       => 'Blits (Loai!)',
'exif-flashenergy'                 => 'Blitsstäärke',
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
'exif-gpsaltituderef'              => 'Beluukengshöchte',
'exif-gpsaltitude'                 => 'Höchte',
'exif-gpsdestlatituderef'          => 'Referenz foar ju Bratte',
'exif-gpsdestlatitude'             => 'Bratte',
'exif-gpsdestlongituderef'         => 'Referenz foar ju Laangte',
'exif-gpsdestlongitude'            => 'Laangte',
'exif-gpsdestbearingref'           => 'Referenz foar Motivgjuchte',
'exif-gpsdestbearing'              => 'Motivgjuchte',
'exif-gpsdestdistanceref'          => 'Referenz foar Motivfierte',
'exif-gpsdestdistance'             => 'Motivfierte',
'exif-gpsareainformation'          => 'Noome fon dät GPS-Gestrich',
'exif-gpsdatestamp'                => 'GPS-Doatum',

# EXIF attributes
'exif-compression-1' => 'Uunkomprimierd',

'exif-orientation-1' => 'Normoal', # 0th row: top; 0th column: left
'exif-orientation-2' => 'Horizontoal uumewoand', # 0th row: top; 0th column: right
'exif-orientation-3' => 'Uum 180° uumewoand', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Vertikoal uumewoand', # 0th row: bottom; 0th column: left
'exif-orientation-5' => 'Juun dän Klokkenwiesersin uum 90° troald un vertikoal uumewoand', # 0th row: left; 0th column: top
'exif-orientation-6' => 'Uum 90° in Klokkenwiesersin troald', # 0th row: right; 0th column: top
'exif-orientation-7' => 'Uum 90° in Klokkenwiesersin troald un vertikoal uumewoand', # 0th row: right; 0th column: bottom
'exif-orientation-8' => 'Uum 90° juun dän Klokkenwiesersin troald', # 0th row: left; 0th column: bottom

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
'exif-lightsource-3'   => 'Gloilaampe',
'exif-lightsource-4'   => 'Blits (Loai)',
'exif-lightsource-9'   => 'Fluch Weeder',
'exif-lightsource-10'  => 'beleekene Luft',
'exif-lightsource-11'  => 'Schaad',
'exif-lightsource-17'  => 'Standoardlucht A',
'exif-lightsource-18'  => 'Standoardlucht B',
'exif-lightsource-19'  => 'Standoardlucht C',
'exif-lightsource-255' => 'Uur Luchtwälle',

'exif-focalplaneresolutionunit-2' => 'Tuume',

'exif-sensingmethod-1' => 'Uundefinierd',
'exif-sensingmethod-2' => 'Een-Chip-Faawesensor',
'exif-sensingmethod-3' => 'Twoo-Chip-Faawesensor',
'exif-sensingmethod-4' => 'Trjoo-Chip-Faawesensor',

'exif-scenetype-1' => 'Normoal',

'exif-customrendered-0' => 'Standoard',
'exif-customrendered-1' => 'Benutserdefinierd',

'exif-whitebalance-0' => 'Automatisk',
'exif-whitebalance-1' => 'Manuäl',

'exif-scenecapturetype-0' => 'Standoard',
'exif-scenecapturetype-1' => 'Londskup',
'exif-scenecapturetype-2' => 'Portrait',
'exif-scenecapturetype-3' => 'Noachtszene',

'exif-gaincontrol-0' => 'Neen',

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

# External editor support
'edit-externally'      => 'Disse Doatäi mäd n extern Program beoarbaidje',
'edit-externally-help' => 'Sjuch [http://meta.wikimedia.org/wiki/Hilfe:Externe_Editoren Installations-Anweisungen] foar
wiedere Informatione.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'aal',
'imagelistall'     => 'aal',
'watchlistall2'    => 'aal',
'namespacesall'    => 'aal',
'monthsall'        => 'aal',

# Scary transclusion
'scarytranscludetoolong' => '[URL is tou loang; Äntscheeldegenge]',

# Trackbacks
'trackbackbox'      => '<div id="mw_trackbacks">
Trackbacks foar dissen Artikkel:<br />
$1
</div>',
'trackbackremove'   => '([$1 läskje])',
'trackbacklink'     => 'Trackback',
'trackbackdeleteok' => 'Trackback wuude mäd Ärfoulch läsked.',

# Delete conflict
'deletedwhileediting' => 'Oachtenge: Disse Siede wuude al läsked ätter dät du anfangd hiedest, hier tou beoarbaidjen!
Wan du disse Siede spiekerst, wäd ju deeruum näi anlaid.',
'recreate'            => 'Wierhäärstaale',

# HTML dump
'redirectingto' => 'Fäärelaited ätter [[$1]]',

# action=purge
'confirm_purge' => 'Dän Cache fon disse Siede loosmoakje?

$1',

# AJAX search
'hideresults' => 'ferbierge',

# Watchlist editing tools
'watchlisttools-view' => 'Beooboachtengslieste: Annerengen',
'watchlisttools-edit' => 'normoal beoarbaidje',
'watchlisttools-raw'  => 'Liestenformoat beoarbaidje (Import/Export)',

);
