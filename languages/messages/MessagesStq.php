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
'category_header'       => 'Artikkel in ju Kategorie "$1"',
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
'bugreportspage'    => 'Project:Kontakt',
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
'badtitletext'         => 'Die anfräigede Tittel waas uungultich, loos, of n uungultigen Sproaklink fon n uur Wiki.',
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
'titleprotected'       => 'Ne Siede mäd dissen Noome kon nit moaked wäide. Ju Speere wuude truch [[{{ns:user}}:$1]] mäd ju Begruundenge <i>$2</i> ienroat.',

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
'yourvariant'                => 'Variante:',
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
'passwordremindertitle'      => 'Näi Paaswoud foar n {{SITENAME}}-Benutserkonto',
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
'bold_sample'     => 'Fatten Text',
'bold_tip'        => 'Fatten Text',
'italic_sample'   => 'Kursiven Text',
'italic_tip'      => 'Kursive Text',
'link_sample'     => 'Link-Text',
'link_tip'        => 'Internen Link',
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
'blockedtext'               => 'Din Benutsernoome of dien IP-Adrässe wuude fon $1 speerd. As Gruund wuude anroat:

:\'\'$2\'\' (<span class="plainlinks">[{{fullurl:Special:Ipblocklist|&action=search&limit=&ip=%23}}$5 Logboukiendraach]</span>)

<p style="border-style: solid; border-color: red; border-width: 1px; padding:5px;"><b>Een Leesetougriep is wieders muugelk,</b> 
bloot ju Beoarbaidenge un dät Moakjen fon Sieden in {{SITENAME}} wuude speerd.
Schuul disse Ättergjucht anwiesd wäide, wan uk bloot leesend tougriepen wuude, bäst du aan (rooden) Link ap ne noch nit existente Siede foulged.</p>

Du koast $1 of een fon do uur [[{{MediaWiki:Grouppage-sysop}}|Administratore]] kontaktierje, uum uur ju Speere tou diskutierjen.

<div style="border-style: solid; border-color: red; border-width: 1px; padding:5px;">
\'\'\'Reek jädden foulgjende Doaten in älke Anfroage an:\'\'\'
*Speerende Administrator: $1
*Speergruund: $2
*Begin fon ju Speere: $8
*Speer-Eende: $6
*IP-Adresse: $3
*Speere beträft: $7
*Speer-ID: #$5
</div>',
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
'whitelistacctext'          => 'Uum in {{SITENAME}} Benutserkonten anlääse tou muugen, moast du die eerste [[Special:Userlogin|hier anmäldje]], un do nöödige Begjuchtigengen hääbe.',
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
'noarticletext'             => '(Dissen Artikkel änthaalt apstuuns naan Text)',
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
'titleprotectedwarning'     => '<strong>OACHTENGE: Dät Moakjen fon Sieden wuude speerd. Bloot bestimde Benutsergruppen konnen ju Siede moakje.</strong>',
'templatesused'             => 'Foulgjende Foarloagen wäide fon disse Artikkele ferwoand:',
'templatesusedpreview'      => 'Foulgjende Foarloagen wäide fon disse Siedefoarschau ferwoand:',
'templatesusedsection'      => 'Foulgjende Foarloagen wuuden fon disse Oudeelenge ferwoand:',
'template-protected'        => '(schutsed)',
'template-semiprotected'    => '(Siedenschuts foar nit anmäldede un näie Benutsere)',
'edittools'                 => '<!-- Text hier stoant unner Beoarbaidengsfäildere un Hoochleedefäildere. -->',
'nocreatetitle'             => 'Dät Moakjen fon näie Sieden is begränsed',
'nocreatetext'              => 'Ap {{SITENAME}} wuude dät Moakjen fon näie Sieden begränsed. Du koast al bestoundene Sieden beoarbaidje of die [[Special:Userlogin|anmäldje]].',
'nocreate-loggedin'         => 'Du hääst neen Begjuchtigenge, näie Sieden in {{SITENAME}} antoulääsen.',
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
'revision-info'       => 'Dit is ne oolde Version. Tiedpunkt fon ju Beoarbaidenge: $1 truch $2.',
'previousrevision'    => '← Naistallere Version',
'nextrevision'        => 'Naistjungere Version →',
'currentrevisionlink' => 'Aktuälle Version',
'cur'                 => 'Aktuäl',
'next'                => 'Naiste',
'last'                => 'Foarige',
'orig'                => 'Originoal',
'page_first'          => 'Ounfang',
'page_last'           => 'Eend',
'histlegend'          => "Diff  Uutwoal: Do Boxen fon do wonskede Versione markierje un 'Enter' drukke of ap dän Knoop unner klikke.<br />
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
'rev-deleted-comment'         => '(Beoarbaidengskommentoar wächhoald)',
'rev-deleted-user'            => '(Benutsernoome wächhoald)',
'rev-deleted-event'           => '(Aktion wächhoald)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks"> Disse Version wuude läsked un is nit moor eepentelk ientousjoon.
Naiere Angoawen toun Läskfoargong as uk ne Begründenge fiende sik in dät [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} Läsk-Logbouk].</div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks">Disse Version wuude läsked un is nit moor eepentelk ientousjoon.
As Administrator koast du ju wieders ienkiekje.
Naiere Angoawen toun Läskfoargong as uk ne Begründenge fiende sik in dät [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} Läsk-Logbouk].</div>',
'rev-delundel'                => 'wiese/ferbierge',
'revisiondelete'              => 'Versione läskje/wier häärstaale',
'revdelete-nooldid-title'     => 'Neen Version ounroat',
'revdelete-nooldid-text'      => 'Du hääst neen Version ounroat, wierap disse Aktion uutfierd wäide schäl.',
'revdelete-selected'          => "{{PLURAL:$2|Uutwäälde Version|Uutwäälde Versione}} fon '''$1:'''",
'logdelete-selected'          => "{{PLURAL:$2|Uutwäälden Logboukiendraach|Uutwäälde Logboukiendraage}} foar '''$1:'''",
'revdelete-text'              => 'Die Inhoold of uur Bestanddeele fon läskede Versione sunt nit moor eepentelk ientousjoon, man ärschiene wieders as Iendraage in ju Versionsgeschichte. 

Uur Administratore ap {{SITENAME}} konnen dän wächhoalde Inhoold of uur wächhoalde Bestanddeele wieders ienkiekje un wier häärstaale, of dät moaste weese, dät fäästlaid wuude, dät do Tougongsbeschränkengen uk foar Administratore jäilde.',
'revdelete-legend'            => 'Ienschränkengen foar do Versione fäästlääse:',
'revdelete-hide-text'         => 'Text fon ju Version ferstopje',
'revdelete-hide-name'         => 'Logbouk-Aktion ferstopje',
'revdelete-hide-comment'      => 'Beoarbaidengskommentoar ferstopje',
'revdelete-hide-user'         => 'Benutsernoome/ju IP fon dän Beoarbaider ferstopje',
'revdelete-hide-restricted'   => 'Disse Ienschränkengen jäilde uk foar Administratore',
'revdelete-suppress'          => 'Gruund fon ju Läskenge uk foar Administratore ferstopped',
'revdelete-hide-image'        => 'Bielde-Inhoold ferstopje',
'revdelete-unsuppress'        => 'Ienschränkengen foar wier häärstoalde Versione aphieuwje',
'revdelete-log'               => 'Kommentoar/Gruund:',
'revdelete-submit'            => 'Ap uutwäälde Version anweende',
'revdelete-logentry'          => 'Versionsansicht annerd foar [[$1]]',
'logdelete-logentry'          => 'annerde ju Sichtboarkaid foar [[$1]]',
'revdelete-logaction'         => '$1 {{plural:$1|Version|Versione}} ap Modus $2 sät',
'logdelete-logaction'         => '$1 {{plural:$1|Iendraach|Iendraage}} foar [[$3]] ap Modus $2 sät',
'revdelete-success'           => 'Versionsansicht mäd Ärfoulch annerd.',
'logdelete-success'           => 'Logbouk-Aktion mäd Ärfoulch sät.',

# Oversight log
'oversightlog'    => 'Oversight-Logbouk',
'overlogpagetext' => 'Dät is dät Logbouk fon do Läskengen un Speeren, do der foar Administratore ferstopped sunt.',

# History merging
'mergehistory'                     => 'Versionsgeschichten fereenigje',
'mergehistory-header'              => "Mäd disse Spezialsiede koast du ju Versionsgeschichte fon ne Uursproangssiede mäd ju Versionsgeschichte fon ne Sielsiede fereenigje.
Staal deertruch sicher, dät ju Versionsgeschichte fon n Artikkel historisk akroat is.

'''As Minumum mout ju aktuelle Version fon ju Uursproangssiede bewoard blieuwe.'''",
'mergehistory-box'                 => 'Versionsgeschichten fon two Sieden fereenigje',
'mergehistory-from'                => 'Uursproangssiede:',
'mergehistory-into'                => 'Sielsiede:',
'mergehistory-list'                => 'Versione, do der fereeniged wäide konnen',
'mergehistory-merge'               => 'Do foulgjende Versione fon „[[:$1]]“ konnen ätter „[[:$2]]“ uurdrain wäide. Markier ju Version, bit tou ju (iensluutend) do Versione wäide schällen. Beoachte jädden, dät ju Nutsenge fon do Navigationslinke ju Uutwoal touräächsät.',
'mergehistory-go'                  => 'Wies Versione, do der fereeniged wäide konnen',
'mergehistory-submit'              => 'Fereenige Versione',
'mergehistory-empty'               => 'Der konnen neen Versione fereeniged wäide',
'mergehistory-success'             => '{{PLURAL:$3|1 Version|$3 Versione}} fon „[[:$1]]“ mäd Ärfoulch ätter „[[:$2]]“ fereeniged.',
'mergehistory-fail'                => 'Versionsfereenigenge nit muugelk, pröif ju Siede un do Tiedangoawen.',
'mergehistory-no-source'           => 'Uursproangssiede „$1“ is nit deer.',
'mergehistory-no-destination'      => 'Sielsiede „$1“ is nit deer.',
'mergehistory-invalid-source'      => 'Uursproangssiede mout n gultigen Siedennoome weese.',
'mergehistory-invalid-destination' => 'Sielsiede mout n gultigen Siedennoome weese.',

# Merge log
'mergelog'           => 'Fereenigengs-Logbouk',
'pagemerge-logentry' => 'fereenigede [[$1]] in [[$2]] (Versione bit $3)',
'revertmerge'        => 'tourääch annerd Fereenigenge',
'mergelogpagetext'   => 'Dit is dät Logbouk fon do fereenigede Versionsgeschichten.',

# Diffs
'history-title'           => 'Versionsgeschichte fon "$1"',
'difference'              => '(Unnerschied twiske Versionen)',
'lineno'                  => 'Riege $1:',
'compareselectedversions' => 'Wäälde Versione ferglieke',
'editundo'                => 'tounichte moakje',
'diff-multi'              => "<span style='font-size: smaller'>(Die Versionsfergliek belukt {{plural:$1|ne deertwiske lääsende Version|$1 deertwiske lääsende Versione}} mee ien.)</span>",

# Search results
'searchresults'         => 'Säikresultoate',
'searchresulttext'      => 'Foar moor Informatione tou ju Säike sjuch ju [[{{MediaWiki:Helppage}}|Hälpesiede]].',
'searchsubtitle'        => 'Foar dien Säikanfroage „[[:$1|$1]]“.',
'searchsubtitleinvalid' => 'Foar dien Säikanfroage „$1“.',
'noexactmatch'          => "'''Deer existiert neen Siede mäd dän Tittel „$1“.'''

Fersäik et uur ju Fultextsäike.
Alternativ koast du uk dän [[Special:Allpages|alphabetisken Index]] ätter äänelke Begriepe truchsäike.

Wan du die mäd dät Thema uutkoanst, koast du sälwen ju Siede „[[$1]]“ ferfoatje.",
'noexactmatch-nocreate' => "'''Der bestoant neen Siede mäd dän Tittel „$1“.'''",
'titlematches'          => 'Uureenstämmengen mäd Uurschrifte',
'notitlematches'        => 'Neen Uureenstimmengen',
'textmatches'           => 'Uureenstämmengen mäd Texte',
'notextmatches'         => 'Neen Uureenstimmengen',
'prevn'                 => 'foarige $1',
'nextn'                 => 'naiste $1',
'viewprevnext'          => 'Wies ($1) ($2) ($3)',
'showingresults'        => "Hier {{PLURAL:$1|is '''1''' Resultoat|sunt '''$1''' Resultoate}}, ounfangend mäd Nuumer '''$2'''.",
'showingresultsnum'     => "Hier {{PLURAL:$3|is '''1''' Resultoat|sunt '''$1''' Resultoate}}, ounfangend mäd Nuumer '''$2'''.",
'nonefound'             => "<strong>Waiwiesenge:</strong> Säikanfroagen sunnerÄrfoulch wäide oafte feruurseeked truch dän Fersäik, ätter 'gewöönelke' Woude tou säiken; do sunt nit indizierd.",
'powersearch'           => 'Säik',
'powersearchtext'       => 'Säik in do Noomensruume:<br />$1<br />$2 Wies uk Fäärelaitengen<br />Säike ätter: $3 $9',
'searchdisabled'        => 'Ju {{SITENAME}} Fultextsäike is weegen Uurläästenge apstuuns deaktivierd. Du koast insteede deerfon ne Google- of Yahoo-Säike startje. Do Resultoate foar {{SITENAME}} speegelje oawers nit uunbedingd dän aktuällen Stand wier.',

# Preferences page
'preferences'              => 'Ienstaalengen',
'mypreferences'            => 'Ienstaalengen',
'prefs-edits'              => 'Antaal Beoarbaidengen:',
'prefsnologin'             => 'Nit anmälded',
'prefsnologintext'         => 'Du moast [[Special:Userlogin|anmälded]] weese, uum dien Ienstaalengen tou annerjen.',
'prefsreset'               => 'Ienstaalengen wuuden ap Standoard touräächsät.',
'qbsettings'               => 'Siedenlieste',
'qbsettings-none'          => 'Naan',
'qbsettings-fixedleft'     => 'Links, fääst',
'qbsettings-fixedright'    => 'Gjuchts, fääst',
'qbsettings-floatingleft'  => 'Links, swieuwjend',
'qbsettings-floatingright' => 'Gjuchts, swieuwjend',
'changepassword'           => 'Paaswoud annerje',
'skin'                     => 'Skin',
'math'                     => 'TeX',
'dateformat'               => 'Doatumsformoat',
'datedefault'              => 'Neen Preferenz',
'datetime'                 => 'Doatum un Tied',
'math_failure'             => 'Parser-Failer',
'math_unknown_error'       => 'Uunbekoande Failer',
'math_unknown_function'    => 'Uunbekoande Funktion',
'math_lexing_error'        => "'Lexing'-Failer",
'math_syntax_error'        => 'Syntaxfailer',
'math_image_error'         => 'ju PNG-Konvertierenge sluuch fail',
'math_bad_tmpdir'          => 'Kon dät Temporärferteeknis foar mathematiske Formeln nit anlääse of beschrieuwe.',
'math_bad_output'          => 'Kon dät Sielferteeknis foar mathematiske Formeln nit anlääse of beschrieuwe.',
'math_notexvc'             => 'Dät texvc-Program kon nit fuunen wäide. Beoachte jädden math/README.',
'prefs-personal'           => 'Benutserdoaten',
'prefs-rc'                 => 'Bekoandreekenge fon "Lääste Annerengen"',
'prefs-watchlist'          => 'Beooboachtengslieste',
'prefs-watchlist-days'     => 'Maximoale Antaal fon Deege, do ju Beooboachtengslieste standoardmäitich uumfoatje schäl:',
'prefs-watchlist-edits'    => 'Maximoale Antaal fon Iendraage in ju fergratterde Beooboachtengslieste:',
'prefs-misc'               => 'Ferscheedene Ienstaalengen',
'saveprefs'                => 'Ienstaalengen spiekerje',
'resetprefs'               => 'Ienstaalengen touräächsätte',
'oldpassword'              => 'Oold Paaswoud:',
'newpassword'              => 'Näi Paaswoud:',
'retypenew'                => 'Näi Paaswoud (nochmoal):',
'textboxsize'              => 'Beoarbaidje',
'rows'                     => 'Riegen',
'columns'                  => 'Spalten',
'searchresultshead'        => 'Säike',
'resultsperpage'           => 'Träffere pro Siede:',
'contextlines'             => 'Teekene pro Träffer:',
'contextchars'             => 'Teekene pro Riege:',
'stub-threshold'           => '<a href="#" class="stub">Kuute Artikkele</a> markierje bi (in Byte):',
'recentchangesdays'        => 'Antaal fon Deege, do ju Lieste fon „Lääste Annerengen“ standoardmäitich uumfoatje schäl:',
'recentchangescount'       => 'Antaal fon do Iendraage in "Lääste Annerengen":',
'savedprefs'               => 'Dien Ienstaalengen wuuden spiekerd.',
'timezonelegend'           => 'Tiedzone',
'timezonetext'             => 'Reek ju Antaal fon Uuren ien, do twiske Jou Tiedzone un UPC lääse.',
'localtime'                => 'Tied bie Jou:',
'timezoneoffset'           => 'Unnerscheed¹:',
'servertime'               => 'Aktuälle Tied ap dän Server:',
'guesstimezone'            => 'Ienföigje uut dän Browser',
'allowemail'               => 'Emails fon uur Benutsere kriegen',
'defaultns'                => 'In disse Noomensruume schäl standoardmäitich soacht wäide:',
'default'                  => 'Standoardienstaalenge',
'files'                    => 'Doatäie',

# User rights
'userrights-lookup-user'      => 'Ferwaltede Gruppentouheeregaid',
'userrights-user-editname'    => 'Benutsernoome anreeke:',
'editusergroup'               => 'Beoarbaidede Benutsergjuchte',
'userrights-editusergroup'    => 'Beoarbaidje Gruppentouheeregaid fon dän Benutser',
'saveusergroups'              => 'Spiekerje Gruppentouheeregaid',
'userrights-groupsmember'     => 'Meeglid fon:',
'userrights-groupsremovable'  => 'Gruppen, do der wächhoald wäide konnen:',
'userrights-groupsavailable'  => 'Ferföigboare Gruppen:',
'userrights-groupshelp'       => "Wääl do Gruppen, uut do die Benutser wächhoald of tou do hie touföiged wäide schäl. Nit selektierde Gruppen wäide nit annerd. Ne Uutwoal kon mäd '''Strg + Linksklick''' wier wächhoald wäide.",
'userrights-reason'           => 'Gruund:',
'userrights-available-none'   => 'Du duurst neen Benutsergjuchte ferannerje.',
'userrights-available-add'    => 'Du duurst Benutsere an {{PLURAL:$2|ju foulgjende Gruppe|do foulgjende $2 Grupppen}} touföigje: $1.',
'userrights-available-remove' => 'Du duurst Benutsere ut {{PLURAL:$2|ju foulgjende Gruppe|do foulgjende $2 Gruppen}} wächhoalje: $1.',
'userrights-no-interwiki'     => 'Du hääst neen Begjuchtigenge, do Benutsergjuchte in uur Wikis tou annerjen.',
'userrights-nodatabase'       => 'Ju Doatenboank $1 is nit deer of nit lokoal.',
'userrights-nologin'          => 'Du moast die mäd n Administrator-Benutserkonto [[Special:Userlogin|anmäldje]], uum Benutsergjuchte tou annerjen.',
'userrights-notallowed'       => 'Du hääst neen Begjuchtigenge, uum Benutsergjuchte tou reeken.',

# Groups
'group'               => 'Gruppe:',
'group-autoconfirmed' => 'Bestäätigede Benutsere',
'group-bot'           => 'Bots',
'group-sysop'         => 'Administratore',
'group-bureaucrat'    => 'Bürokraten',
'group-all'           => '(aal)',

'group-autoconfirmed-member' => 'Bestäätigede Benutser',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Administrator',
'group-bureaucrat-member'    => 'Bürokrat',

'grouppage-autoconfirmed' => '{{ns:project}}:Bestäätigede Benutser',
'grouppage-bot'           => '{{ns:project}}:Bots',
'grouppage-sysop'         => '{{ns:project}}:Administratore',
'grouppage-bureaucrat'    => '{{ns:project}}:Bürokraten',

# User rights log
'rightslog'      => 'Gjuchte-Logbouk',
'rightslogtext'  => 'Dit is dät Logbouk fon do Annerengen fon do Benutsergjuchte.',
'rightslogentry' => 'annerde ju Gruppentouheeregaid foar „[[$1]]“ fon „$2“ ap „$3“.',
'rightsnone'     => '(-)',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|Annerenge|Annerengen}}',
'recentchanges'                     => 'Lääste Annerengen',
'recentchangestext'                 => "Ap disse Siede koast du do lääste Annerengen ap '''{{SITENAME}}''' ättergunge.",
'recentchanges-feed-description'    => 'Ferfoulge mäd dissen Feed do lääste Annerengen in {{SITENAME}}.',
'rcnote'                            => "Anwiesd {{PLURAL:$1|wäd '''1''' Annerenge|wäide do lääste '''$1''' Annerengen}} in {{PLURAL:$2|dän lääste Dai|do lääste '''$2''' Deege}}, fon $3. 
(<b><tt>Näi</tt></b>&nbsp;– näien Artikkel; <b><tt>L</tt></b>&nbsp;– litje Annerenge)
<b><tt>B</tt></b>&nbsp;– Annerenge truch n Bot; ''(± Taal)''&nbsp;– Gröötenannerenge in Byte)",
'rcnotefrom'                        => 'Anwiesd wäide do Annerengen siet <b>$2</b> (max. <b>$1</b> Iendraage).',
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
'upload_directory_read_only'  => 'Die Webserver häd neen Schrieuwgjuchte foar dät Upload-Ferteeknis ($1).',
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
'upload-permitted'            => 'Ferlööwede Doatäitypen: $1.',
'upload-preferred'            => 'Ljoost antouweenden Doatäitypen: $1.',
'upload-prohibited'           => 'Nit ferlööwede Doatäitypen: $1.',
'uploadlog'                   => 'Doatäi-Logbouk',
'uploadlogpage'               => 'Doatäi-Logbouk',
'uploadlogpagetext'           => 'Hier is ju Lieste fon do lääste hoochleedene Doatäie, sjuch uk [[{{ns:special}}:Newimages]].',
'filename'                    => 'Doatäinoome',
'filedesc'                    => 'Beschrieuwenge, Wälle',
'fileuploadsummary'           => 'Beschrieuwenge/Wälle:',
'filestatus'                  => 'Copyright-Stoatus',
'filesource'                  => 'Wälle',
'uploadedfiles'               => 'Hoochleedene Doatäie',
'ignorewarning'               => 'Woarschauenge ignorierje un Doatäi daach spiekerje.',
'ignorewarnings'              => 'Woarschauengen ignorierje',
'minlength1'                  => 'Bieldedoatäien mouten mindestens tjoo Bouksteeuwen foar dän (eersten) Punkt hääbe.',
'illegalfilename'             => 'Die Doatäinoome "$1" änthaalt ap minste een nit toulät Teeken. Benaam jädden ju Doatäi uum un fersäik, hier fon näien hoochtouleeden.',
'badfilename'                 => 'Die Datäi-Noome is automatisk annerd tou "$1".',
'filetype-badmime'            => 'Doatäie mäd dän MIME-Typ „$1“ duuren nit hoochleeden wäide.',
'filetype-unwanted-type'      => "'''„.$1“''' is n nit wonsked Doateiformoat. Ferlööwed sunt: $2.",
'filetype-banned-type'        => "'''„.$1“''' is n nit ferlööwed Doatäiformoat. Ferlööwed sunt: $2.",
'filetype-missing'            => 'Ju hoochtouleedende Doatäi häd neen Fergratterenge (t. B. „.jpg“).',
'large-file'                  => 'Jädden neen Bielde uur $1 hoochleede; disse Doatäi is $2 groot.',
'largefileserver'             => 'Disse Doatäi is tou groot, deer die Server so konfigurierd is, dät Doatäien bloot bit tou ne bestimde Grööte apzeptierd wäide.',
'emptyfile'                   => 'Ju hoochleedene Doatäi is loos. Die Gruund kon n Typfailer in dän Doatäinoome weese. Kontrollierje jädden, of du ju Doatäi wuddelk hoochleede wolt.',
'fileexists'                  => "Ne Doatäi mäd dissen Noome bestoant al. Wan du ap 'Doatäi spiekerje' klikst, wäd ju Doatäi
uurschrieuwen. Unner $1 koast du die bewisje, of du dät wuddelk wolt.",
'fileexists-extension'        => 'Een Doatei mäd n äänelken Noome existiert al:<br />
Noome fon ju hoochtouleedende Doatäi: <strong><tt>$1</tt></strong><br />
Noome fon ju anweesende Doatäi: <strong><tt>$2</tt></strong><br />
Bloot ju Doatäieendenge unnerschat sik in Groot-/Littikschrieuwenge. Pröif, of do Doatäie ätter dän Inhoold identisk sunt.',
'fileexists-thumb'            => "<center>'''Anweesende Bielde'''</center>",
'fileexists-thumbnail-yes'    => 'Bie ju Doatäi schient et sik uum ne Bielde fon ferlitjerde Grööte <i>(thumbnail)</i> tou honneljen. Pröif ju Doatäi <strong><tt>$1</tt></strong>.<br />
Wan et sik uum ju Bielde in Originoalgrööte honnelt, dan houget neen apaate Foarschaubielde hoochleeden tou wäiden.',
'file-thumbnail-no'           => 'Die Doatäinoome begint mäd <strong><tt>$1</tt></strong>. Dit tjut ap ne Bielde fon ferlitjerde Grööte <i>(thumbnail)</i> wai.
Pröif, of du ju Bielde in fulle Aplöösenge foarlääsen hääst un leed ju unner dän Originoalnoome hooch.',
'fileexists-forbidden'        => 'Mäd dissen Noome bestoant al ne Doatäi. Gung jädden tourääch un leede dien Doatäi unner n uur Noome hooch. [[Bielde:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Mäd dissen Noome bestoant al ne Doatäi ap Wikipedia Commons. Gung jädden tourääch un leede dien Doatäi unner n uur Noome hooch. [[Bielde:$1|thumb|center|$1]]',
'successfulupload'            => 'Mäd Ärfoulch hoochleeden',
'uploadwarning'               => 'Woarschauenge',
'savefile'                    => 'Doatäi spiekerje',
'uploadedimage'               => '"[[$1]]" hoochleeden',
'overwroteimage'              => 'häd ne näie Version fon „[[$1]]“ hoochleeden',
'uploaddisabled'              => 'Äntscheeldigenge, dät Hoochleeden is apstuuns deaktivierd.',
'uploaddisabledtext'          => 'Dät Hoochleeden fon Doatäie is in {{SITENAME}} nit muugelk.',
'uploadscripted'              => 'Disse Doatäi änthaalt HTML- of Scriptcode, ju bie Fersjoon fon aan Webbrowser apfierd wäide kuude.',
'uploadcorrupt'               => 'Ju Doatäi is beschäädiged of häd n falsken Noome. Uurpröiwe jädden ju Doatäi un leede ju fonnäien hooch.',
'uploadvirus'                 => 'Disse Doatäi änthaalt n Virus! Details: $1',
'sourcefilename'              => 'Wäldoatäi',
'destfilename'                => 'Sielnoome',
'watchthisupload'             => 'Disse Siede beooboachtje',
'filewasdeleted'              => 'Ne Doatäi mäd dissen Noome wuude al moal hoochleeden un intwisken wier läsked. Pröif toueerst dän Iendraach in $1, eer du ju Doatäi wuddelk spiekerst.',
'upload-wasdeleted'           => "'''Woarschauenge: Du laatst ne Doatäi hooch, ju der al fröier läsked wuude.'''
 
Pröif suurgfooldich, of dät fernäide Hoochleeden do Gjuchtlienjen äntspräkt.
Tou Dien Information foulget dät Läsk-Logbouk mäd ju Begründenge foar ju foargungende Läskenge:",
'filename-bad-prefix'         => 'Die Doatäinoome begint mäd <strong>„$1“</strong>. Dit is in algemeenen die fon ne Digitoalkamera foarroate Doatäinoome un deeruum nit gjucht uurtjuugend.
Reek ju Doatäi n Noome, die dän Inhoold beeter beschrift.',

'upload-proto-error'      => 'Falsk Protokol',
'upload-proto-error-text' => 'Ju URL mout mäd <code>http://</code> of <code>ftp://</code> ounfange.',
'upload-file-error'       => 'Interne Failer',
'upload-file-error-text'  => 'Bie dät Moakjen fon ne tiedelke Doatäi ap dän Server is n internen Failer aptreeden. Informier n System-Administrator.',
'upload-misc-error'       => 'Uunbekoanden Failer bie dät Hoochleeden',
'upload-misc-error-text'  => 'Bie dät Hoochleeden is n uunbekoanden Failer aptreeden. Pröif ju URL ap Failere, dän Online-Stoatus fon ju Siede un fersäik et fonnäien. Wan dät Problem fääre bestoant, informier n System-Administrator.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'URL is nit tou beloangjen',
'upload-curl-error6-text'  => 'Ju ounroate URL is nit tou beloangjen. Pröif ju URL ap Failere as uk dän Online-Stoatus fon ju Siede.',
'upload-curl-error28'      => 'Toufuul Tied nöödich foar dät Hoochleeden',
'upload-curl-error28-text' => 'Ju Siede bruukt tou loange foar ne Oantwoud. Pröif, of ju Siede online is, täif n kuuten Moment un fersäik et dan fonnäien. Dät kon sinful weese, n näien Fersäik tou ne uur Tied tou probierjen..',

'license'            => 'Lizenz',
'nolicense'          => 'naan Foaruutwoal',
'license-nopreview'  => '(der is neen Foarschau ferföigboar)',
'upload_source_url'  => ' (gultige, eepentelk tougongelke URL)',
'upload_source_file' => ' (ne Doatäi ap Jou Computer)',

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
'imagelist_search_for'      => 'Säik ätter Doatäi:',

# File reversion
'filerevert'                => 'Touräächsätte fon "$1"',
'filerevert-legend'         => 'Doatäi touräächsätte',
'filerevert-intro'          => '<span class="plainlinks">Du sätst ju Doatäi \'\'\'[[Media:$1|$1]]\'\'\' ap ju [$4 Version fon $2, $3 Uure] tourääch.</span>',
'filerevert-comment'        => 'Kommentoar:',
'filerevert-defaultcomment' => 'touräächsät ap ju Version fon $1, $2 Uure',
'filerevert-submit'         => 'Touräächsätte',
'filerevert-success'        => '<span class="plainlinks">\'\'\'[[Media:$1|$1]]\'\'\' wuud ap ju [$4 Version fon $2, $3 Uure] touräächsät.</span>',
'filerevert-badversion'     => 'Et rakt neen Version fon ju Doatäi tou dän ounroate Tiedpunkt.',

# File deletion
'filedelete'             => 'Läskje "$1"',
'filedelete-legend'      => 'Läskje Doatäi',
'filedelete-intro'       => "Du läskest ju Doatäi '''„[[Media:$1|$1]]“'''.",
'filedelete-intro-old'   => '<span class="plainlinks">Du läskest fon ju Doatäi \'\'\'„[[Media:$1|$1]]“\'\'\' ju [$4 Version fon $2, $3 Uur].</span>',
'filedelete-comment'     => 'Gruund:',
'filedelete-submit'      => 'Läskje',
'filedelete-success'     => "'''\"\$1\"''' wuude läsked.",
'filedelete-success-old' => '<span class="plainlinks">Fon ju Doatäi \'\'\'„[[Media:$1|$1]]“\'\'\' wuud ju Version $2, $3 Uure läsked.</span>',
'filedelete-nofile'      => "'''„$1“''' is ap {{SITENAME}} nit deer.",
'filedelete-nofile-old'  => "Et rakt fon '''„$1“''' neen Version fon $2, $3 Uure.",
'filedelete-iscurrent'   => 'Du fersäkst, ju aktuelle Version fon disse Doatäi tou läskjen. Sät foartied ap ne allere Version tourääch.',

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
'unusedtemplatestext' => 'Disse Siede liestet aal Sieden in dän Foarloage-Noomensruum, do der nit bruukt wuuden sunt in ne uur Siede. Oachtje deerap tou pröiwjen foar uur Ferbiendengen tou do Foarloagen, eer do wächtouhoaljen.',
'unusedtemplateswlh'  => 'Uur Ferbiendengen',

# Random page
'randompage'         => 'Toufällige Siede',
'randompage-nopages' => 'In dissen Noomensruum sunt neen Sieden deer.',

# Random redirect
'randomredirect'         => 'Toufällige Fäärelaitenge',
'randomredirect-nopages' => 'In dissen Noomensruum sunt neen Fääreleedengen deer.',

# Statistics
'statistics'             => 'Statistik',
'sitestats'              => 'Siedenstatistik',
'userstats'              => 'Benutserstatistik',
'sitestatstext'          => "Dät rakt mädnunner '''$1''' {{PLURAL:$1|Siede|Sieden}} in ju Doatenboank.
Dät slut Diskussionssieden, Sieden uur {{SITENAME}}, litje Sieden, Fäärelaitengen un uur Sieden ien,
do der eventuell nit as Sieden wäided wäide konnen.

Disse uutgenuumen rakt et '''$2''' {{PLURAL:$2|Siede|Sieden}}, do der as Siede wäided wäide {{PLURAL:$2|kon|konnen}}.

Mädnunner {{PLURAL:$8|wuude '''1''' Doatäi|wuuden '''$8''' Doatäie}} hoochleeden.

Mädnunner roat et '''$3''' {{PLURAL:$3|Siedenouroup|Siedenouroupe}} un '''$4''' {{PLURAL:$4|Siedenbeoarbaidenge|Siedenbeoarbaidengen}} siet {{SITENAME}} iengjucht wuude.
Deeruut reeke sik '''$5''' Beoarbaidengen pro Siede un '''$6''' Siedenouroupe pro Beoarbaidenge.

Laangte fon ju [http://meta.wikimedia.org/wiki/Help:Job_queue „Job queue“]: '''$7'''",
'userstatstext'          => "Dät rakt '''$1''' {{PLURAL:$1|registrierde|registrierde}} [[Special:Listusers|Benutser]].
Deerfon {{PLURAL:$2|häd|hääbe}} '''$2''' Benutser (=$4 %) $5-Rechte.",
'statistics-mostpopular' => 'Maast besoachte Sieden',

'disambiguations'      => 'Begriepskläärengssieden',
'disambiguationspage'  => 'Template:Begriepskläärenge',
'disambiguations-text' => 'Do foulgjende Sieden ferlinkje ap ne Siede tou ju Begriepskläärenge. Jie schuulen insteede deerfon ap ju eegentelk meende Siede ferlinkje.<br />Ne Siede wäd as Begriepskläärengssiede behonneld, wan [[MediaWiki:disambiguationspage]] ap ju ferlinket.<br />Ferbiendengen uut Noomensruume wäide hier nit apliested.',

'doubleredirects'     => 'Dubbelde Fäärelaitengen',
'doubleredirectstext' => '<b>Oachtenge:</b> Disse Lieste kon "falske Positive" änthoolde. Dät is dan dän Fal, wan aan
Fäärelaitengen buute dän Fäärelaitenge-Ferwies noch wiedere Text mäd uur Ferwiesen änthaalt. Doo
Lääste schällen dan wächhoald wäide.',

'brokenredirects'        => 'Ferkierde Fäärelaitengen',
'brokenredirectstext'    => 'Disse Truchferwiese laitje tou nit existierjende Artikkel:',
'brokenredirects-edit'   => '(beoarbaidje)',
'brokenredirects-delete' => '(läskje)',

'withoutinterwiki'        => 'Sieden sunner Ferbiendengen tou uur Sproaken',
'withoutinterwiki-header' => 'Do foulgjende Sieden ferlinkje nit ap uur Sproakversionen:',

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
'lonelypagestext'         => 'Do foulgjende Sieden sunt nit ferlinked fon uur Sieden in {{SITENAME}}.',
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
'mostcategories'          => 'Maast kategorisierde Sieden',
'mostimages'              => 'Maast benutsede Bielden',
'mostrevisions'           => 'Sieden mäd do maaste Versione',
'allpages'                => 'Aal Artikkele',
'prefixindex'             => 'Aal Artikkele (mäd Präfix)',
'shortpages'              => 'Kuute Sieden',
'longpages'               => 'Loange Artikkel',
'deadendpages'            => 'Siede sunner Ferwiese',
'deadendpagestext'        => 'Do foulgjende Sieden linkje nit tou uur Sieden in {{SITENAME}}.',
'protectedpages'          => 'Schutsede Sieden',
'protectedpagestext'      => 'Do foulgjende Sieden sunt beschutsed juun Ferschuuwen of Beoarbaidjen',
'protectedpagesempty'     => 'Apstuuns sunt neen Sieden mäd disse Parametere schutsed.',
'protectedtitles'         => 'Speerde Tittele',
'protectedtitlestext'     => 'Do foulgjende Sieden sunt speerd uum näi tou moakjen',
'protectedtitlesempty'    => 'Apstuuns sunt mäd do ounroate Parametere neen Sieden speerd uum näi tou moakjen.',
'listusers'               => 'Benutser-Lieste',
'specialpages'            => 'Spezioalsieden',
'spheading'               => 'Spezioalsieden foar alle Benutsere',
'restrictedpheading'      => 'Spezialsieden foar Administratore',
'newpages'                => 'Näie Sieden',
'newpages-username'       => 'Benutsernoome:',
'ancientpages'            => 'Siet loang uunbeoarbaidede Sieden',
'intl'                    => 'Interwiki-Ferbiendengen',
'move'                    => 'ferschuuwe',
'movethispage'            => 'Artikkel ferschuuwe',
'unusedimagestext'        => '<p>Beoachtje jädden, dät uur Websieden muugelkerwiese eenige fon disse Bielden benutsje.</p>',
'unusedcategoriestext'    => 'Do foulgjende Kategorien bestounde, wan do apstuuns uk nit in Ferweendenge sunt.',
'notargettitle'           => 'Naan Artikkel anroat',
'notargettext'            => 'Du hääst nit anroat, ap wäkke Siede disse Funktion anwoand wäide schäl.',
'pager-newer-n'           => '{{PLURAL:$1|naisten|naiste $1}}',
'pager-older-n'           => '{{PLURAL:$1|foarigen|foarige $1}}',

# Book sources
'booksources'               => 'ISBN-Säike',
'booksources-search-legend' => 'Säik ätter Steeden wier me Bouke kriege kon',
'booksources-go'            => 'Säike',
'booksources-text'          => 'Dit is ne Lieste mäd Ferbiendengen tou Internetsieden, do der näie un bruukte Bouke ferkoopje. Deer kon et uk wiedere Informatione uur do Bouke reeke. {{SITENAME}} is mäd neen fon disse Anbjoodere geschäftelk ferbuunen.',

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
'log-search-legend'    => 'Logbouke truchsäike',
'log-search-submit'    => 'Säike',
'alllogstext'          => 'Dit is ne kombinierde Anwiesenge fon aal Logs fon {{SITENAME}}.',
'logempty'             => 'Neen paasende Iendraage.',
'log-title-wildcard'   => 'Tittel fangt oun mäd …',

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
'allpagesbadtitle'  => 'Die ienroate Siedennoome is ungultich: Hie häd äntweeder n foaranstoald Sproak-, n Interwiki-Oukuutenge of änthaalt een of moor Teekene, do der in Siedennoomen nit verwoand wäide duuren.',
'allpages-bad-ns'   => 'Die Noomensruum „$1“ is in {{SITENAME}} nit deer.',

# Special:Listusers
'listusersfrom'      => 'Wies Benutsere fon:',
'listusers-submit'   => 'Wies',
'listusers-noresult' => 'Naan Benutser fuunen.',

# E-mail user
'mailnologin'     => 'Du bäst nit anmälded.',
'mailnologintext' => 'Du moast [[{{ns:special}}:Userlogin|anmälded weese]] un sälwen ne [[{{ns:special}}:Confirmemail|gultige]] E-Mail-Adrässe anroat hääbe, uum uur Benutsere ne E-Mail tou seenden.',
'emailuser'       => 'Seende E-Mail an dissen Benutser',
'emailpage'       => 'E-mail an Benutser',
'emailpagetext'   => 'Wan disse Benutser ne gultige Email-Adrässe anroat häd, konnen Jie him mäd dän unnerstoundene Formuloar ne E-mail seende. As Ouseender wäd ju E-mail-Adrässe uut Jou Ienstaalengen iendrain, deermäd die Benutser Jou oantwoudje kon.',
'usermailererror' => 'Dät Mail-Objekt roat n Failer tourääch:',
'defemailsubject' => '{{SITENAME}}-E-Mail',
'noemailtitle'    => 'Neen Email-Adrässe',
'noemailtext'     => 'Disse Benutser häd neen gultige Email-Adrässe anroat of moate neen E-Mail fon uur Benutsere ämpfange.',
'emailfrom'       => 'Fon',
'emailto'         => 'An',
'emailsubject'    => 'Beträf',
'emailmessage'    => 'Ättergjucht',
'emailsend'       => 'Seende',
'emailccme'       => 'Seend ne Kopie fon ju E-Mail an mie',
'emailccsubject'  => 'Kopie fon dien Ättergjucht an $1: $2',
'emailsent'       => 'Begjucht fersoand',
'emailsenttext'   => 'Jou Begjucht is soand wuuden.',

# Watchlist
'watchlist'            => 'Beooboachtengslieste',
'mywatchlist'          => 'Beooboachtengslieste',
'watchlistfor'         => "(foar '''$1''')",
'nowatchlist'          => 'Du hääst neen Iendraage ap dien Beooboachtengslieste. Du moast anmälded weese, dät die een Beooboachtengslieste tou Ferföigenge stoant.',
'watchlistanontext'    => 'Du moast die $1, uum dien Beooboachtengslieste tou sjoon of Iendraage ap hier tou beoarbaidjen.',
'watchnologin'         => 'Du bäst nit anmälded',
'watchnologintext'     => 'Du moast [[Special:Userlogin|anmälded]] weese, uum dien Beooboachtengslieste tou beoarbaidjen.',
'addedwatch'           => 'An Foulgelieste touföiged.',
'addedwatchtext'       => "Die Artikkel \"[[:\$1]]\" wuude an dien [[Special:Watchlist|Foulgelieste]] touföiged.
Leetere Annerengen an dissen Artikkel un ju touheerende Diskussionssiede wäide deer liested
un die Artikkel wäd in ju [[Special:Recentchanges|fon do lääste Annerengen]] in '''Fatschrift''' anroat. 

Wan du die Artikkel wier fon ju Foulgelieste ou hoalje moatest, klik ap ju Siede ap \"Ferjeet disse Siede\".",
'removedwatch'         => 'Fon ju Beooboachtengsslieste ou hoald',
'removedwatchtext'     => 'Ju Siede „$1“ wuude fon dien Beooboachtengslieste wächhoald.',
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
'enotif_lastvisited'           => 'Aal Annerengen ap aan Blik: $1',
'enotif_lastdiff'              => '$1 wiest alle Annerengen mäd aan Glap.',
'enotif_anon_editor'           => 'Anonyme Benutser $1',
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
'actioncomplete'              => 'Aktion be-eended',
'deletedtext'                 => '"$1" wuude läsked. 
In $2 fiende Jie ne Lieste fon do lääste Läskengen.',
'deletedarticle'              => '"$1" wuude läsked',
'dellogpage'                  => 'Läsk-Logbouk',
'dellogpagetext'              => 'Hier is ne Lieste fon do lääste Läskengen.',
'deletionlog'                 => 'Läsk-Logbouk',
'reverted'                    => 'Ap ne oolde Version touräächsät',
'deletecomment'               => 'Gruund foar ju Läskenge',
'deleteotherreason'           => 'Uur/additionoalen Gruund:',
'deletereasonotherlist'       => 'Uur Gruund',
'deletereason-dropdown'       => '* Algemeene Läskgruunde
** Wonsk fon dän Autor
** Urhebergjuchtsferlätsenge
** Vandalismus',
'rollback'                    => 'Touräächsätten fon do Annerengen',
'rollback_short'              => 'Touräächsätte',
'rollbacklink'                => 'touräächsätte',
'rollbackfailed'              => 'Touräächsätten misglukked',
'cantrollback'                => 'Disse Annerenge kon nit touräächstoald wäide; deer et naan fröieren Autor rakt.',
'alreadyrolled'               => "Dät Touräächsätten fon do Annerengen fon [[User:$2|$2]] <span style='font-size: smaller'>([[User_talk:$2|Diskussion]], 
[[{{ns:special}}:Contributions/$2|Biedraage]])</span> an Siede [[:$1]] hied naan Ärfoulch, deer in ju Twiskentied al n uur Benutser 
Annerengen an disse Siede foarnuumen häd.<br />Ju lääste Annerenge stamt fon [[User:$3|$3]] <span style='font-size: smaller'>([[User_talk:$3|Diskussion]])</span>.",
'editcomment'                 => 'Ju Annerengskommentoar waas: "<i>$1</i>".', # only shown if there is an edit comment
'revertpage'                  => 'Annerengen fon [[User:$2|$2]] ([[{{ns:special}}:Contributions/$2|Biedraage]]) tounichte moaked un lääste Version fon $1 wierhäärstoald',
'rollback-success'            => 'Do Annerengen fon $1 wuuden tourääch annerd un ju lääste Version fon $2 wuude wier moaked.',
'sessionfailure'              => 'Dät roat n Problem mäd ju Uurdreegenge fon dien Benutserdoaten. Disse Aktion wuude deeruum sicherheidshoolwe oubreeken, uum ne falske Touoardnenge fon dien Annerengen tou n uur Benutser tou ferhinnerjen. Gung jädden tourääch un fersäik dän Foargong fonnäien uuttoufieren.',
'protectlogpage'              => 'Siedenschuts-Logbouk',
'protectlogtext'              => 'Dit is ne Lieste fon do blokkierde Sieden. Sjuch [[{{ns:special}}:Protectedpages|Schutsede Siede]] foar moor Informatione.',
'protectedarticle'            => 'schutsede „[[$1]]“',
'modifiedarticleprotection'   => 'annerde dän Schuts fon „[[$1]]“',
'unprotectedarticle'          => 'hieuwede dän Schuts fon "[[$1]]" ap',
'protectsub'                  => '(Schuts annerje fon „$1“)',
'confirmprotect'              => 'Siedenschutsstoatus annerje',
'protectcomment'              => 'Gruund:',
'protectexpiry'               => 'Speerduur:',
'protect_expiry_invalid'      => 'Ju ienroate Duur is uungultich.',
'protect_expiry_old'          => 'Ju Speertied lait in ju fergeene Tied.',
'unprotectsub'                => '(Aphieuwenge fon ju Speerenge fon "$1")',
'protect-unchain'             => 'Ferschuuweschuts annerje',
'protect-text'                => "Hier koast du dän Schutsstoatus foar ju Siede '''$1''' ienkiekje un annerje.",
'protect-locked-blocked'      => 'Du koast dän Siedenschuts nit annerje, deer dien Benutserkonto speerd is. Hier sunt do aktuelle Siedenschuts-Ienstaalengen foar ju Siede <strong>„$1“:</strong>',
'protect-locked-dblock'       => 'Ju Doatenboank is speerd, die Siedenschuts kon deeruum nit annerd wäide. Hier sunt do aktuelle Siedenschuts-Ienstaalengen foar ju Siede <strong>„$1“:</strong>',
'protect-locked-access'       => 'Du bäst nit begjuchtiged, dän Siedenschutsstoatus tou annerjen. Hier is die aktuälle Schutsstoatus fon ju Siede <strong>$1</strong>:',
'protect-cascadeon'           => 'Disse Siede is apstuuns Deel fon ne Kaskadenspeere. Ju is in {{PLURAL:$1|ju foulgjende Siede|do foulgjende Sieden}} ienbuunen, do der truch ju Kaskadenspeerroption schutsed {{PLURAL:$1|is|sunt}}. Die Siedenschutsstoatus kon foar disse Siede annerd wäide, man dät häd naan Ienfloud ap ju Kaskadenspeere:',
'protect-default'             => 'Aal (Standoard)',
'protect-fallback'            => 'Deer wäd ju „$1“-Begjuchtigenge benöödigd.',
'protect-level-autoconfirmed' => 'Speerenge foar nit registrierde Benutsere',
'protect-level-sysop'         => 'Bloot Administration',
'protect-summary-cascade'     => 'kaskadierjend',
'protect-expiring'            => 'bit $1 (UTC)',
'protect-cascade'             => 'Kaskadierjende Speere – aal in disse Siede ienbuundene Foarloagen wäide ieuwenfals speerd.',
'protect-cantedit'            => 'Du koast ju Speere fon disse Siede nit annerje, deer du neen Begjuchtigenge toun Beoarbaidjen fon ju Siede hääst.',
'restriction-type'            => 'Schutsstoatus',
'restriction-level'           => 'Schutshöchte',
'minimum-size'                => 'Minstgrööte:',
'maximum-size'                => 'Maximoalgrööte:',
'pagesize'                    => '(Bytes)',

# Restrictions (nouns)
'restriction-edit'   => 'beoarbaidje',
'restriction-move'   => 'ferschuuwe',
'restriction-create' => 'moakje',

# Restriction levels
'restriction-level-sysop'         => 'schutsed (bloot Administratore)',
'restriction-level-autoconfirmed' => 'schutsed (bloot anmäldede, nit-näie Benutsere)',
'restriction-level-all'           => 'aal',

# Undelete
'undelete'                     => 'Läskede Siede wier häärstaale',
'undeletepage'                 => 'Läskede Siede wier häärstaale',
'viewdeletedpage'              => 'Läskede Versione anwiese',
'undeletepagetext'             => 'Do foulgjende Sieden wuuden läsked, man sunt altied noch spiekerd un konnen fon Administratore wier häärstoald wäide:',
'undeleteextrahelp'            => '* Uum ju Siede gans mäd aal Versione wiertoumoakjen, reek ne Begruundenge an un klik ap „Wier moakje“.
* Moatest du bloot bestimde Versione wier moakje, so wääl do jädden eenpeld anhound fon do Markierengen uut, reek ne Begruundenge an un klik dan ap „Wier moakje“.
* „Oubreeke“ moaket dät Kommentoarfäild loos un hoalt aal Markierengen wäch bie do Versione.',
'undeleterevisions'            => '{{PLURAL:$1|1 Version|$1 Versione}} archivierd',
'undeletehistory'              => 'Wan Jie disse Siede wier häärstaale, wäide uk aal oolde Versione wier häärstoald. Wan siet ju Läskenge aan näien Artikkel mäd dän sälge Noome moaked wuude, wäide do wier häärstoalde Versione as oolde Versione fon dissen Artikkel ferschiene.',
'undeleterevdel'               => 'Dät wier Moakjen wäd nit truchfierd, wan ju aktuelste Version ferstopped is of ferstoppede Deele änthaalt.
In dissen Fal duur ju aktuelste Version nit markierd wäide of hier Stoatus mout ap dän fon n normoalen Version annerd wäide.
Versione fon Doatäie, wier du naan Tougriep ap hääst, wäide nit wier moaked.',
'undeletehistorynoadmin'       => 'Disse Siede wuude läsked. Die Gruund foar ju Läskenge is in ju Touhoopefoatenge ounroat,
juust as Details tou dän lääste Benutser, die der disse Siede foar ju Läskenge beoarbaided häd.
Die aktuelle Text fon ju läskede Siede is bloot Administratore tougongelk.',
'undelete-revision'            => 'Läskede Versione fon $1 - $2, $3:',
'undeleterevision-missing'     => 'Uungultige of failjende Version. Äntweeder is ju Ferbiendenge falsk of ju Version wuude uut dät Archiv wier moaked of wächhoald.',
'undelete-nodiff'              => 'Neen foargungende Version fuunen.',
'undeletebtn'                  => 'Wier häärstaale',
'undeletereset'                => 'Oubreeke',
'undeletecomment'              => 'Gruund:',
'undeletedarticle'             => 'häd "[[$1]]" wier häärstoald',
'undeletedrevisions'           => '{{PLURAL:$1|1 Version wuude|$1 Versione wuuden}} wier häärstoald',
'undeletedrevisions-files'     => '{{PLURAL:$1|1 Version|$1 Versione}} un {{PLURAL:$2|1 Doatäi|$2 Doatäie}} wuuden wier häärstoald',
'undeletedfiles'               => '{{PLURAL:$1|1 Doatäie wuude|$1 Doatäie wuuden}} wier häärstoald',
'cannotundelete'               => 'Wier Moakjen failsloain; uurswäl häd ju Siede al wier moaked.',
'undeletedpage'                => "'''$1''' wuude wier moaked.

In dät [[Special:Log/delete|Läsk-Logbouk]] finst du ne Uursicht fon do läskede un wier moakede Sieden.",
'undelete-header'              => 'Sjuch dät [[{{ns:special}}:Log/delete|Läsk-Logbouk]] foar knu läskede Sieden.',
'undelete-search-box'          => 'Säik ätter läskede Sieden',
'undelete-search-prefix'       => 'Säikbegriep (Woudounfang sunner Wildcards):',
'undelete-search-submit'       => 'Säike',
'undelete-no-results'          => 'Der wuude in dät Archiv neen tou dän Säikbegriep paasjende Siede fuunen.',
'undelete-filename-mismatch'   => 'Ju Doatäiversion mäd dän Tiedstämpel $1 kuude nit wier moaked wäide: Do Doatäinoomen paasje nit tounonner.',
'undelete-bad-store-key'       => 'Ju Doatäiversion mäd dän Tiedstämpel $1 kuude nit wier moaked wäide: Ju Doatäi waas al foar dät Läskjen nit moor deer.',
'undelete-cleanup-error'       => 'Failer bie dät Läskjen fon ju nit benutsede Archiv-Version $1.',
'undelete-missing-filearchive' => 'Ju Doatäi mäd ju Archiv-ID $1 kon nit wier moaked wäide, deer ju nit in ju Doatenboank deer is. Muugelkerwiese wuude ju al wier moaked.',
'undelete-error-short'         => 'Failer bie dät wier moakjen fon ju Doatäi $1',
'undelete-error-long'          => 'Der wuuden Failere bie dät wier moakjen fon ne Doatäi fääststoald:

$1',

# Namespace form on various pages
'namespace'      => 'Noomensruum:',
'invert'         => 'Uutwoal uumekiere',
'blanknamespace' => '(Sieden)',

# Contributions
'contributions' => 'Benutserbiedraage',
'mycontris'     => 'Oaine Biedraage',
'contribsub2'   => 'Foar $1 ($2)',
'nocontribs'    => 'Deer wuuden neen Annerengen foar disse Kriterien fuunen.',
'ucnote'        => 'Dit sunt do lääste <b>$1</b> Biedraage fon dän Benutser in do lääste <b>$2</b> Deege.',
'uclinks'       => 'Wies do lääste $1 Biedraage fon dän Benutser in do lääste $2 Deege.',
'uctop'         => ' (aktuäl)',
'month'         => 'un Mound:',
'year'          => 'bit Jier:',

'sp-contributions-newbies'     => 'Wies bloot Biedraage fon näie Benutsere',
'sp-contributions-newbies-sub' => 'Foar Näilinge',
'sp-contributions-blocklog'    => 'Speerlogbouk',
'sp-contributions-search'      => 'Säike ätter Benutserbiedraage',
'sp-contributions-username'    => 'IP-Adrässe af Benutsernoome:',
'sp-contributions-submit'      => 'Säike',

'sp-newimages-showfrom' => 'Wies näie Doatäie fon $1 ou',

# What links here
'whatlinkshere'       => 'Links ap disse Siede',
'whatlinkshere-title' => 'Sieden, do der ap "$1" linkje',
'whatlinkshere-page'  => 'Siede:',
'linklistsub'         => '(Linklieste)',
'linkshere'           => "Do foulgjende Sieden ferwiese hierhäär:  '''[[:$1]]''': <br /><small>(Moonige Sieden wäide eventuell moorfooldich liested, konnen in säildene Falle oawers uk miste. Dät kumt fon oolde Failere in dän Software häär, man schoadet fääre niks.)</small>",
'nolinkshere'         => "Naan Artikkel ferwiest hierhäär: '''[[:$1]]'''.",
'nolinkshere-ns'      => "Neen Siede ferlinket ap '''„[[:$1]]“''' in dän wäälde Noomensruum.",
'isredirect'          => 'Fäärelaitengs-Siede',
'istemplate'          => 'Foarloagenienbiendenge',
'whatlinkshere-prev'  => '{{PLURAL:$1|foarige|foarige $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|naiste|naiste $1}}',
'whatlinkshere-links' => '← Links',

# Block/unblock
'blockip'                     => 'Blokkierje Benutser',
'blockiptext'                 => 'Mäd dit Formular speerst du ne IP-Adresse of n Benutsernoome, so dät fon deer neen Annerengen moor foarnuumen wäide konnen.
Dit schuul bloot geböäre, uum Vandalismus tou ferhinnerjen un in Uureenstimmenge mäd do [[{{MediaWiki:Policy-url}}|Gjuchtlienjen]].
Reek dän Gruund foar ju Speere oun.',
'ipaddress'                   => 'IP-Adrässe:',
'ipadressorusername'          => 'IP-Adresse of Benutsernoome:',
'ipbexpiry'                   => 'Oulooptied (Speerduur):',
'ipbreason'                   => 'Begruundenge:',
'ipbreasonotherlist'          => 'Uur Begründenge',
'ipbreason-dropdown'          => '* Algemeene Speergruunde
** Läskjen fon Sieden
** Ienstaalen fon uunsinnige Sieden
** Foutsätte Fersteete juun do Gjuchtlienjen foar Webferbiendengen
** Fersteet juun dän Gruundsats „Neen persöönelke Angriepe“
* Benutserspezifiske Speergruunde
** Nit paasjende Benutsernoome
** Näianmäldenge fon n uunbeschränkt speerden Benutser
* IP-spezifiske Speergruunde
** Proxy, weegen Vandalismus fon eenpelde Benutsere foar laangere Tied speerd',
'ipbanononly'                 => 'Bloot anonyme Benutsere speere',
'ipbcreateaccount'            => 'Dät Moakjen fon Benutserkonten ferhinnerje',
'ipbemailban'                 => 'E-Mail-Fersoand speere',
'ipbenableautoblock'          => 'Speer ju aktuell fon dissen Benutser nutsede IP-Adresse as uk automatisk aal foulgjende, fon do uut hie Beoarbaidengen of dät Anlääsen fon Benutseraccounts fersäkt',
'ipbsubmit'                   => 'Adrässe blokkierje',
'ipbother'                    => 'Uur Duur (ängelsk):',
'ipboptions'                  => '1 Uure:1 hour,2 Uuren:2 hours,6 Uuren:6 hours,1 Dai:1 day,3 Deege:3 days,1 Wiek:1 week,2 Wieke:2 weeks,1 Mound:1 month,3 Mounde:3 months,1 Jier:1 year,Uunbestimd:indefinite', # display1:time1,display2:time2,...
'ipbotheroption'              => 'Uur Duur',
'ipbotherreason'              => 'Uur/additionelle Begründenge:',
'ipbhidename'                 => 'Benutsernoome/IP-Adresse in dät Speer-Logbouk, in ju Lieste fon aktive Speeren un in dät Benutserferteeknis ferstopje.',
'badipaddress'                => 'Dissen Benutser bestoant nit, d.h. die Noome is falsk',
'blockipsuccesssub'           => 'Blokkoade geloangen',
'blockipsuccesstext'          => 'Ju IP-Adrässe [[Special:Contributions/$1|$1]] wuude blokkierd.
<br />[[Special:Ipblocklist|Lieste fon Blokkoaden]].',
'ipb-edit-dropdown'           => 'Speergruunde beoarbaidje',
'ipb-unblock-addr'            => '"$1" fräireeke',
'ipb-unblock'                 => 'IP-Adrässe/Benutser fräireeke',
'ipb-blocklist-addr'          => 'Aktuelle Speere foar „$1“ anwiese',
'ipb-blocklist'               => 'Aal aktuelle Speeren anwiese',
'unblockip'                   => 'IP-Adrässe fräireeke',
'unblockiptext'               => 'Benutsje dät Formular, uum ne blokkierde IP-Adrässe fräitoureeken.',
'ipusubmit'                   => 'Disse Adrässe fräireeke',
'unblocked'                   => '[[User:$1|$1]] wuude fräiroat',
'unblocked-id'                => 'Speer-ID $1 wuude fräiroat',
'ipblocklist'                 => 'Lieste fon blokkierde IP-Adrässen',
'ipblocklist-legend'          => 'Säik ätter n speerden Benutser',
'ipblocklist-username'        => 'Benutsernoome of IP-Adrässe:',
'ipblocklist-submit'          => 'Säike',
'blocklistline'               => '$1, $2 blokkierde $3 ($4)',
'infiniteblock'               => 'uunbegränsed',
'expiringblock'               => '$1',
'anononlyblock'               => 'bloot Anonyme',
'noautoblockblock'            => 'Autoblock deaktivierd',
'createaccountblock'          => 'Dät Moakjen fon Benutserkonten speerd',
'emailblock'                  => 'E-Mail-Fersoand speerd',
'ipblocklist-empty'           => 'Ju Lieste änthaalt neen Iendraage.',
'ipblocklist-no-results'      => 'Ju soachte IP-Adresse/die Benutsernoome is nit speerd.',
'blocklink'                   => 'blokkierje',
'unblocklink'                 => 'fräireeke',
'contribslink'                => 'Biedraage',
'autoblocker'                 => 'Du wierst blokkierd, deer du eene IP-Adrässe mäd "[[User:$1|$1]]" benutsjen dääst. Foar ju Blokkierenge fon dän Benutser waas as Gruund anroat: "$2".',
'blocklogpage'                => 'Benutserblokkoaden-Logbouk',
'blocklogentry'               => '[[$1]] blokkierd foar n Tiedruum fon: $2 $3',
'blocklogtext'                => 'Dit is n Logbouk fon Speerengen un Äntspeerengen fon Benutsere. Ju Sunnersiede fiert aal aktuäl speerde Benutsere ap, iensluutend automatisk blokkierde IP-Adrässe.',
'unblocklogentry'             => 'Blokkade fon $1 aphieuwed',
'block-log-flags-anononly'    => 'bloot Anonyme',
'block-log-flags-nocreate'    => 'Dät Moakjen fon Benutserkonten speerd',
'block-log-flags-noautoblock' => 'Autoblock deaktivierd',
'block-log-flags-noemail'     => 'E-Mail-Fersoand speerd',
'range_block_disabled'        => 'Ju Muugelkaid, ganse Adräsruume tou speeren, is nit aktivierd.',
'ipb_expiry_invalid'          => 'Ju anroate Oulooptied is nit gultich.',
'ipb_already_blocked'         => '„$1“ wuude al speerd',
'ipb_cant_unblock'            => 'Failer: Speer-ID $1 nit fuunen. Ju Speere wuude al aphieuwed.',
'ipb_blocked_as_range'        => 'Failer: Ju IP-Adresse $1 wuude as Deel fon ju Beräksspeere $2 indirekt speerd. Ne Äntspeerenge fon $1 alleene is nit muugelk.',
'ip_range_invalid'            => 'Uungultige IP-Adräsberäk.',
'blockme'                     => 'Speer mie',
'proxyblocker'                => 'Proxy blokker',
'proxyblocker-disabled'       => 'Disse Funktion is deaktivierd.',
'proxyblockreason'            => 'Jou IP-Adrässe wuude speerd, deer ju n eepenen Proxy is. Kontaktierje jädden Jou Provider af Jou Systemtechnik un informierje Jou jou uur dit muugelke Sicherhaidsproblem.',
'proxyblocksuccess'           => 'Kloor.',
'sorbsreason'                 => 'Dien IP-Adrässe is in ju DNSBL fon {{SITENAME}} as eepene PROXY liested.',
'sorbs_create_account_reason' => 'Dien IP-Adrässe is in ju DNSBL fon {{SITENAME}} as eepene PROXY liested. Du koast neen Benutser-Account anlääse.',

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
'lockfilenotwritable' => 'Ju Doatenboank-Speerdoatäi is nit beschrieuwboar. Toun Speeren of Fräireeken fon ju Doatenboank mout ju foar dän Webserver beschrieuwboar weese.',
'databasenotlocked'   => 'Ju Doatenboank is nit speerd.',

# Move page
'movepage'                => 'Siede ferschuuwe',
'movepagetext'            => 'Mäd dissen Formular koast du ne Siede touhoope mäd aal Versione tou n uur Noome ferschuuwe. Foar dän oolde Noome wäd ne Fäärelaitenge tou dän Näie iengjucht. Ferwiese ap dän oolde Noome wäide nit annerd.',
'movepagetalktext'        => "Ju touheerige Diskussionssiede wäd, sofier deer, mee ferschäuwen, '''of dät moast weese''' 
* du ferschufst ju Siede in n uur [[Wikipedia:Noomensruum|Noomensruum]] 
* deer bestoant al n Diskussionssiede mäd dän näie Noome 
* du wäälst ju unnerstoundene Option ou. 

In disse Falle moast du ju Siede, wan wonsked, fon Hounde ferschuuwe. Jädden dän '''näie''' Tittel unner '''Siel''' iendreege, deerunner ju Uumnaamenge jädden '''begründje'''.",
'movearticle'             => 'Siede ferschuuwe:',
'movenologin'             => 'Du bäst nit anmälded',
'movenologintext'         => 'Du moast n registrierden Benutser un [[Special:Userlogin|anmälded]] weese, uum ne Siede ferschuuwe tou konnen.',
'movenotallowed'          => 'Du hääst in {{SITENAME}} neen Begjuchtigenge, Sieden tou ferschuuwen.',
'newtitle'                => 'Tou dän näie Tittel:',
'move-watch'              => 'Disse Siede beooboachtje',
'movepagebtn'             => 'Siede ferschuuwe',
'pagemovedsub'            => 'Ferschuuwenge mäd Ärfoulch',
'movepage-moved'          => "<big>'''Ju Siede „$1“ wuude ätter „$2“ ferschäuwen.'''</big>", # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => 'Dät rakt al n Siede mäd disse Noome, of uurs is die Noome dän du anroat hääst, nit toulät.
Fersäik jädden n uur Noome.',
'cantmove-titleprotected' => 'Ju Ferschuuwenge kon nit truchfierd wäide, deeruum dät die Sieltittel speerd is uum tou moakjen.',
'talkexists'              => 'Ju Siede sälwen wuude mäd Ärfoulch ferschäuwen, man ju Diskussionssiede nit, deer al een mäd dän näie Tittel bestoant. Glieke jädden do Inhoolde fon Hounde ou.',
'movedto'                 => 'ferschäuwen ätter',
'movetalk'                => 'Ju Diskussionssiede mee ferschuuwe, wan muugelk.',
'talkpagemoved'           => 'Ju Diskussions-Siede wuude uk ferschäuwen.',
'talkpagenotmoved'        => 'Ju Diskussions-Siede wuude <strong>nit</strong> ferschäuwen.',
'1movedto2'               => 'häd "[[$1]]" ätter "[[$2]]" ferschäuwen',
'1movedto2_redir'         => 'häd „[[$1]]“ ätter „[[$2]]“ ferschäuwen un deerbie ne Fääreleedenge uurschrieuwen',
'movelogpage'             => 'Ferschuuwengs-Logbouk',
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
'export'            => 'Sieden exportierje',
'exporttext'        => 'Du koast dän Täkst un ju Beoarbaidengshistorie fon ne bestimde Siede of fon n Uutwoal fon Sieden ättter XML exportierje.',
'exportcuronly'     => 'Bloot ju aktuälle Version fon de Siede exportierje',
'exportnohistory'   => "--- 
'''Waiwiesenge:''' Die Export fon komplette Versionsgeschichten is uut Performancegruunden bit ap fääre nit muugelk. Ne Deelleedenge fon Versiongeschichten as Dump is oawers muugelk unner [http://download.wikimedia.org/ download.wikimedia.org] — ''Wikimedia-Serveradministratoren''.",
'export-submit'     => 'Sieden exportierje',
'export-addcattext' => 'Sieden uut Kategorie bietouföigje:',
'export-addcat'     => 'Bietouföigje',
'export-download'   => 'As XML-Doatäi spiekerje',

# Namespace 8 related
'allmessages'               => 'Aal Ättergjuchte',
'allmessagesname'           => 'Noome',
'allmessagesdefault'        => 'Standardtext',
'allmessagescurrent'        => 'Dissen Text',
'allmessagestext'           => 'Dit is ne Lieste fon aal System-Ättergjuchte do in dän MediaWiki-Noomenruum tou Ferföigenge stounde.',
'allmessagesnotsupportedDB' => "'''{{ns:special}}:Allmessages''' is in Momänt nit muugelk, deeruum dät die Doatenboank offline is.",
'allmessagesfilter'         => 'Ättergjuchtennoomensfilter:',
'allmessagesmodified'       => 'Bloot annerde wiese',

# Thumbnails
'thumbnail-more'           => 'fergratterje',
'missingimage'             => '<b>Failjende Bielde</b><br /><i>$1</i>',
'filemissing'              => 'Doatäi failt',
'thumbnail_error'          => 'Failer bie dät Moakjen fon Foarschaubielde (Thumbnail): $1',
'djvu_page_error'          => 'DjVu-Siede buute dät Siedenberäk',
'djvu_no_xml'              => 'XML-Doaten konnen foar ju DjVu-Doatei nit ouruupen wäide',
'thumbnail_invalid_params' => 'Uungultige Thumbnail-Parameter',
'thumbnail_dest_directory' => 'Sielferteeknis kon nit moaked wäide.',

# Special:Import
'import'                     => 'Sieden importierje',
'importinterwiki'            => 'Transwiki-Import',
'import-interwiki-text'      => 'Wääl n Wiki un ne Siede toun Importierjen uut.
Do Versionsdoaten un Benutsernoomen blieuwe deerbie beheelden.
Aal Transwiki-Import-Aktione wäide in dät [[Special:Log/import|Import-Logbouk]] protokollierd.',
'import-interwiki-history'   => 'Importier aal Versione fon disse Siede',
'import-interwiki-submit'    => 'Import',
'import-interwiki-namespace' => 'Importier ju Siede in dän Noomensruum:',
'importtext'                 => 'Exportiere ju Siede fon dän Wälwiki middels [[{{ns:special}}:Export]] un leede ju Doatäi dan uur disse Siede wier hooch.',
'importstart'                => 'Sieden importierje …',
'import-revision-count'      => '– {{PLURAL:$1|1 Version|$1 Versione}}',
'importnopages'              => 'Neen Sieden toun Importierjen anweesend.',
'importfailed'               => 'Import failsloain: $1',
'importunknownsource'        => 'Uunbekoande Importwälle',
'importcantopen'             => 'Importdoatäi kuude nit eepend wäide',
'importbadinterwiki'         => 'Falske Interwiki-Ferbiendenge',
'importnotext'               => 'Loos of neen Text',
'importsuccess'              => 'Import fuller Ärfoulch!',
'importhistoryconflict'      => 'Deer bestounde al allere Versionen, do mäd disse kollidierje. Muugelkerwiese wuude ju Siede al eer importierd.',
'importnosources'            => 'Foar dän Transwiki Import sunt neen Wällen definierd un dät direkte Hoochleeden fon Versione is blokkierd.',
'importnofile'               => 'Deer is neen Importdoatäi hoochleeden wuuden.',
'importuploaderrorsize'      => 'Dät Hoochleeden fon ju Importdoatäi is failsloain. Ju Doatäi is gratter as ju maximoal toulätte Doatäigrööte.',
'importuploaderrorpartial'   => 'Dät Hoochleeden fon ju Importdoatäi is failsloain. Ju Doatäi wuude man deelwiese hoochleeden.',
'importuploaderrortemp'      => 'Dät Hoochleeden fon ju Importdoatäi is failsloain. N temporär Ferteeknis failt.',

# Import log
'importlogpage'                    => 'Import-Logbouk',
'importlogpagetext'                => 'Administrativen Import fon Sieden mäd Versionsgeschichte fon uur Wikis.',
'import-logentry-upload'           => 'häd „[[$1]]“ fon ne Doatäi importierd',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|Version|Versione}}',
'import-logentry-interwiki'        => 'häd „[[$1]]“ importierd (Transwiki)',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|Version|Versione}} fon $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Oaine Benutsersiede',
'tooltip-pt-anonuserpage'         => 'Benutsersiede fon ju IP-Adresse fon ju uut du Annerengen truchfierst',
'tooltip-pt-mytalk'               => 'Oaine Diskussionssiede',
'tooltip-pt-anontalk'             => 'Diskussion uur Annerengen fon disse IP-Adresse',
'tooltip-pt-preferences'          => 'Oaine Ienstaalengen',
'tooltip-pt-watchlist'            => 'Lieste fon do beooboachtede Sieden',
'tooltip-pt-mycontris'            => 'Lieste fon oaine Biedraage',
'tooltip-pt-login'                => 'Jou ientoulogjen wäd wäil jädden blouked, man is neen Plicht.',
'tooltip-pt-anonlogin'            => 'Sik ientoulogjen wäd wäil jädden blouked, man is neen Plicht.',
'tooltip-pt-logout'               => 'Oumäldje',
'tooltip-ca-talk'                 => 'Diskussion uur dän Inhoold fon ju Siede',
'tooltip-ca-edit'                 => 'Siede beoarbaidje. Jädden foar dät Spiekerjen ju Foarschaufunktion benutsje.',
'tooltip-ca-addsection'           => 'N Kommentoar tou disse Diskussion bietouföigje.',
'tooltip-ca-viewsource'           => 'Disse Siede is schutsed. Die Wältext kon ankieked wäide.',
'tooltip-ca-history'              => 'Fröiere Versione fon disse Siede',
'tooltip-ca-protect'              => 'Disse Siede schutsje',
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
'tooltip-n-portal'                => 'Uur dät Portoal, wät du dwo koast, wier wät tou fienden is',
'tooltip-n-currentevents'         => 'Bäätergruundinformationen tou aktuelle Geböärnisse',
'tooltip-n-recentchanges'         => 'Lieste fon do lääste Annerengen in {{SITENAME}}.',
'tooltip-n-randompage'            => 'Toufällige Siede',
'tooltip-n-help'                  => 'Hälpesiede anwiese',
'tooltip-n-sitesupport'           => 'Unnerstutse uus',
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
'tooltip-ca-nstab-image'          => 'Bieldesiede anwiese',
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
'anonymous'        => 'Anonyme(n) Benutser ap {{SITENAME}}',
'siteuser'         => '{{SITENAME}}-Benutser $1',
'lastmodifiedatby' => 'Disse Siede wuude toulääst annerd uum $2, $1 fon $3.', # $1 date, $2 time, $3 user
'and'              => 'un',
'othercontribs'    => 'Basierd ap ju Oarbaid fon $1.',
'others'           => 'uur',
'siteusers'        => '{{SITENAME}}-Benutser $1',
'creditspage'      => 'Siedenstatistik',
'nocredits'        => 'Foar disse Siede sunt neen Informationen deer.',

# Spam protection
'spamprotectiontitle'    => 'Spamschutsfilter',
'spamprotectiontext'     => 'Ju Siede, ju du spiekerje wolt, wuude fon dät Spamschutsfilter blokkierd. Dät lait woarschienelk an ne Ferbiendenge ätter ne externe Siede.',
'spamprotectionmatch'    => "'''Die foulgjende Text wuude fon uus Spam-Filter fuunen: ''$1'''''",
'subcategorycount'       => 'Disse Kategorie häd {{PLURAL:$1|1 Unnerkategorie|$1 Unnerkategorien}}.',
'categoryarticlecount'   => 'Tou disse Kategorie heere $1 Artikkele.',
'category-media-count'   => 'Tou disse Kategorie heere $1 Artikkele.',
'listingcontinuesabbrev' => '(Foutsättenge)',
'spambot_username'       => 'MediaWiki Spam-Süüwerenge',
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
'markaspatrolleddiff'                 => 'As pröiwed markierje',
'markaspatrolledtext'                 => 'Dissen Artikkel as pröiwed markierje',
'markedaspatrolled'                   => 'As pröiwed markierd',
'markedaspatrolledtext'               => 'Ju uutwoalde Artikkelannerenge wuude as pröiwed markierd.',
'rcpatroldisabled'                    => 'Pröiwenge fon do lääste Annerengen speerd',
'rcpatroldisabledtext'                => 'Ju Pröiwenge fon do lääste Annerengen ("Recent Changes Patrol") is apstuuns speerd.',
'markedaspatrollederror'              => 'Markierenge as „kontrollierd“ nit muugelk.',
'markedaspatrollederrortext'          => 'Du moast ne Siedenannerenge uutwääle.',
'markedaspatrollederror-noautopatrol' => 'Et is nit ferlööwed, oaine Beoarbaidengen as kontrollierd tou markierjen.',

# Patrol log
'patrol-log-page' => 'Kontrol-Logbouk',
'patrol-log-line' => 'häd $1 fon $2 as kontrollierd markierd $3',
'patrol-log-auto' => '(automatisk)',
'patrol-log-diff' => 'Version $1',

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
'previousdiff' => '← Toun foarige Versionsunnerscheed',
'nextdiff'     => 'Toun naisten Versionsunnerscheed →',

# Media information
'mediawarning'         => "'''Warnung:''' Disse Oard fon Doatäi kon n schoadelken Programcode änthoolde. Truch dät Deelleeden of Eepenjen fon dissen Doatäi kon dän Computer Schoade toubroacht wäide. Al dät Anklikken fon dän Link kon deertou fiere, dät die Browser ju Doatäi eepen moaket un uunbekoande Programcode tou Uutfierenge kumt. Do Bedrieuwere fon ju Wikipedia uurnieme neen Feroantwoudenge foar dän Inhoold fon disse Doatäi! Schuul disse Doatäi wuddelk schoadelke Programcode änthoolde, schuul n Administrator informierd wäide.<hr />",
'imagemaxsize'         => 'Maximoale Bieldegrööte ap Bieldebeschrieuwengssieden:',
'thumbsize'            => 'Grööte fon do Foarschaubielden (thumbnails):',
'widthheightpage'      => '$1×$2, $3 Sieden',
'file-info'            => '(Doatäigrööte: $1, MIME-Typ: $2)',
'file-info-size'       => '($1 × $2 Pixel, Doatäigrööte: $3, MIME-Typ: $4)',
'file-nohires'         => '<small>Neen haagere Aplöösenge foarhounden.</small>',
'svg-long-desc'        => '(SVG-Doatäi, Basisgrööte: $1 × $2 Pixel, Doatäigrööte: $3)',
'show-big-image'       => 'Bielde in hooge Aplöösenge',
'show-big-image-thumb' => '<small>Grööte fon disse Foarschau: $1 × $2 Pixel</small>',

# Special:Newimages
'newimages'    => 'Näie Bielden',
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
* focallength', # Do not translate list items

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

'exif-orientation-1' => 'Normoal', # 0th row: top; 0th column: left
'exif-orientation-2' => 'Horizontoal uumewoand', # 0th row: top; 0th column: right
'exif-orientation-3' => 'Uum 180° uumewoand', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Vertikoal uumewoand', # 0th row: bottom; 0th column: left
'exif-orientation-5' => 'Juun dän Klokkenwiesersin uum 90° troald un vertikoal uumewoand', # 0th row: left; 0th column: top
'exif-orientation-6' => 'Uum 90° in Klokkenwiesersin troald', # 0th row: right; 0th column: top
'exif-orientation-7' => 'Uum 90° in Klokkenwiesersin troald un vertikoal uumewoand', # 0th row: right; 0th column: bottom
'exif-orientation-8' => 'Uum 90° juun dän Klokkenwiesersin troald', # 0th row: left; 0th column: bottom

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

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'km/h',
'exif-gpsspeed-m' => 'mph',
'exif-gpsspeed-n' => 'Knätte',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Wuddelke Gjuchte',
'exif-gpsdirection-m' => 'Magnetiske Gjuchte',

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

# E-mail address confirmation
'confirmemail'            => 'Email-Adrässe bestäätigje',
'confirmemail_noemail'    => 'Du hääst neen gultige E-Mail-Adresse in dien [[Special:Preferences|persöönelke Ienstaalengen]] iendrain.',
'confirmemail_text'       => '{{SITENAME}} ärfoardert, dät du dien E-Mail-Adresse bestäätigest (authentifizierje), eer du do fergratterde E-Mail-Funktione benutsje koast. Truch n Klik ap ju Schaltfläche unner wäd ne E-Mail an die fersoand. Disse E-Mail änthaalt ne Ferbiendenge mäd n Bestäätigengs-Code. Truch Klikken ap disse Ferbiendenge wäd bestäätiged, dät dien E-Mail-Adresse gultich is.',
'confirmemail_pending'    => '<div class="error">Der wuude die al n Bestäätigengs-Code per E-Mail tousoand. Wan du dien Benutserkonto eerste knu moaked hääst, täif noch n poor Minuten ap ju E-Mail, eer du n näien Code anfoarderst.</div>',
'confirmemail_send'       => 'Bestäätigengscode touseende',
'confirmemail_sent'       => 'Bestäätigengs-E-Mail wuude fersoand.',
'confirmemail_oncreate'   => 'N Bestäätigengs-Code wuude an dien E-Mail-Adresse soand. Dissen Code is foar ju Anmäldenge nit nöödich, man daach wäd er tou ju Aktivierenge fon do E-Mail-Funktione binne dän Wiki bruukt.',
'confirmemail_sendfailed' => 'Ju Bestäätigengs-E-Mail kuude nit fersoand wäide. Pröif ju E-Mail-Adresse ap uungultige Teekene.

Touräächmäldenge fon dän Mailserver: $1',
'confirmemail_invalid'    => 'Uungultigen Bestäätigengscode. Eventuell is die Code al wier uungultich wuuden.',
'confirmemail_needlogin'  => 'Du moast die $1, uum dien E-Mail-Adresse tou bestäätigjen.',
'confirmemail_success'    => 'Dien E-Mail-Adresse wuude mäd Ärfoulch bestäätiged. Du koast die nu ienlogje.',
'confirmemail_loggedin'   => 'Dien E-Mail-Adresse wuude mäd Ärfoulch bestäätiged.',
'confirmemail_error'      => 'Et roat n Failer bie ju Bestäätigenge fon dien E-Mail-Adresse.',
'confirmemail_subject'    => '[{{SITENAME}}] - Bestäätigenge fon ju E-Mail-Adresse',
'confirmemail_body'       => 'Moin,

wäl mäd ju IP-Adresse $1, woarschienelk du sälwen, häd ne Bestäätigenge fon disse E-Mail-Adresse foar dät Benutserkonto "$2" in {{SITENAME}} anfoarderd. 

Uum ju E-Mail-Funktion foar {{SITENAME}} (wier) tou aktivierjen un uum tou bestäätigjen, dät dit Benutserkonto wuddelk tou dien E-Mail-Adresse un deermäd tou die heert, eepenje ju foulgjende Web-Adresse:

$3

Schuul ju foarstoundende Adresse in dien E-Mail-Program uur moorere Riegen gunge, moast du ju eventuell mäd de Hounde in ju Adressriege fon din Web-Browser ienföigje. 

Disse Bestäätigengscode is gultich bit $4.

Wan disse E-Mail-Adresse nit tou dän naamde Benutserkonto heert, foulgje ju Ferbiendenge ljauer nit.

-- 
{{SITENAME}}: {{fullurl:{{Mediawiki:mainpage}}}}',

# Scary transclusion
'scarytranscludedisabled' => '[Interwiki-Ienbiendenge is deaktivierd]',
'scarytranscludefailed'   => '[Foarloagenienbiendenge foar $1 is misglukked]',
'scarytranscludetoolong'  => '[URL is tou loang; Äntscheeldegenge]',

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
'confirmrecreate'     => "Benutser [[User:$1|$1]] ([[User_talk:$1|Diskussion]]) häd disse Siede läsked, ätter dät du ounfangd hääst, ju tou beoarbaidjen. Ju Begruundenge lutte:
''$2''.
Bestäätigje, dät du disse Siede wuddelk näi moakje moatest.",
'recreate'            => 'Wierhäärstaale',

# HTML dump
'redirectingto' => 'Fäärelaited ätter [[$1]]',

# action=purge
'confirm_purge'        => 'Dän Cache fon disse Siede loosmoakje?

$1',
'confirm_purge_button' => 'OK',

# AJAX search
'searchcontaining' => "Säik ätter Sieden, in do ''$1'' foarkumt.",
'searchnamed'      => "Säik ätter Sieden, wierfon die Noome ''$1'' änthaalt.",
'articletitles'    => "Sieden, do der mäd ''$1'' ounfange",
'hideresults'      => 'ferbierge',
'useajaxsearch'    => 'Benutsje AJAX-unnerstutsede Säike',

# Multipage image navigation
'imgmultipageprev'   => '← foarige Siede',
'imgmultipagenext'   => 'naiste Siede →',
'imgmultigo'         => 'OK',
'imgmultigotopre'    => 'Gung tou Siede',
'imgmultiparseerror' => 'Ju Doatäi schient stukken tou weesen, so dät {{SITENAME}} neen Siedenlieste moakje kon.',

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
'autosumm-blank'   => 'Disse Siede wuude loosmoaked.',
'autosumm-replace' => "Die Siedeninhoold wuude truch n uur Text ärsät: '$1'",
'autoredircomment' => 'Fäärelaited ätter [[$1]]',
'autosumm-new'     => 'Ju Siede wuude näi anlaid: $1',

# Live preview
'livepreview-loading' => 'Leede …',
'livepreview-ready'   => 'Leeden … Kloor!',
'livepreview-failed'  => 'Live-Foarschau nit muugelk! Benutsje ju normoale Foarschau.',
'livepreview-error'   => 'Ferbiendenge nit muugelk: $1 "$2". Benutsje ju normoale Foarschau.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Beoarbaidengen fon do lääste $1 Sekunden wäide in disse Lieste noch nit anwiesd.',
'lag-warn-high'   => 'Ap Gruund fon hooge Doatenboankuutläästenge wäide do Beoarbaidengen fon do lääste $1 Sekunden in disse Lieste noch nit anwiesd.',

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

);
