<?php
/** Limburgish (Limburgs)
 *
 * @addtogroup Language
 *
 * @author Ooswesthoesbes
 * @author Cicero
 * @author Siebrand
 * @author SPQRobin
 * @author Jon Harald Søby
 * @author Nike
 * @author לערי ריינהארט
 * @author Tibor
 */

$fallback = 'nl';

$skinNames = array(
	'standard' => 'Standaard',
	'nostalgia' => 'Nostalgie',
	'cologneblue' => 'Keuls blauw',
);

$namespaceNames = array(
	NS_MEDIA          => 'Media',
	NS_SPECIAL        => 'Speciaal',
	NS_MAIN           => '',
	NS_TALK           => 'Euverlèk',
	NS_USER           => 'Gebroeker',
	NS_USER_TALK      => 'Euverlèk_gebroeker',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => 'Euverlèk_$1',
	NS_IMAGE          => 'Plaetje',
	NS_IMAGE_TALK     => 'Euverlèk_plaetje',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'Euverlèk_MediaWiki',
	NS_TEMPLATE       => 'Sjabloon',
	NS_TEMPLATE_TALK  => 'Euverlèk_sjabloon',
	NS_HELP           => 'Help',
	NS_HELP_TALK      => 'Euverlèk_help',
	NS_CATEGORY       => 'Categorie',
	NS_CATEGORY_TALK  => 'Euverlèk_categorie'
);

$namespaceAliases = array(
	'Kategorie'           => NS_CATEGORY,
	'Euverlèk_kategorie'  => NS_CATEGORY_TALK,
	'Aafbeilding'         => NS_IMAGE,
	'Euverlèk_afbeelding' => NS_IMAGE_TALK,
);

$dateFormats = array(	
	'mdy time' => 'H:i',
	'mdy date' => 'M j, Y',
	'mdy both' => 'M j, Y H:i',

	'dmy time' => 'H:i',
	'dmy date' => 'j M Y',
	'dmy both' => 'j M Y H:i',

	'ymd time' => 'H:i',
	'ymd date' => 'Y M j',
	'ymd both' => 'Y M j H:i',
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Links óngersjtriepe',
'tog-highlightbroken'         => 'Formatteer gebraoke links <a href="" class="new">op dees meneer</a> (angers: zoe<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Paragrafe oetvölle',
'tog-hideminor'               => 'Versjtaek klein bewirkinge bie lètste verangeringe',
'tog-extendwatchlist'         => 'Oetgebreide volglies',
'tog-usenewrc'                => 'Oetgebreide recènte vervangeringe (neet vuur alle browsers)',
'tog-numberheadings'          => 'Köpkes automatisch nummere',
'tog-showtoolbar'             => 'Laot edit toolbar zeen',
'tog-editondblclick'          => "Bewirk pazjena's bie 'ne dubbelklik (JavaScript)",
'tog-editsection'             => 'Bewirke van secties via [bewirke] links',
'tog-editsectiononrightclick' => 'Sècties bewirke mit inne rechtermoesklik op sèctietitels (JavaScript)',
'tog-showtoc'                 => "Inhawdsopgaaf vuur pazjena's mit mië as 3 köpkes",
'tog-rememberpassword'        => "Wachwaord ónthauwe bie 't aafmèlde",
'tog-editwidth'               => 'Edit boks haet de vol breidte',
'tog-watchcreations'          => "Pazjena's die ich aanmaak automatisch volge",
'tog-watchdefault'            => "Voog pazjena's die se bewirks toe aan dien volglies",
'tog-watchmoves'              => "Pazjena's die ich verplaats automatisch volge",
'tog-watchdeletion'           => "Pazjena's die ich ewegsjaf automatisch volge",
'tog-minordefault'            => 'Merkeer sjtandaard alle bewirke as klein',
'tog-previewontop'            => 'Veurvertuun baove bewèrkingsveld tune',
'tog-previewonfirst'          => 'Preview laote zien bie de iesjte bewirking',
'tog-nocache'                 => 'Pazjena cache oetzitte',
'tog-enotifwatchlistpages'    => "'ne E-mail nao mich verzende biej bewèrkinge van pazjena's op mien volglies",
'tog-enotifusertalkpages'     => "'ne E-mail nao mich verzende es emes mien euverlèkpazjena verangert",
'tog-enotifminoredits'        => "'ne E-mail nao mich verzende biej kleine bewèrkinge op pazjena's op mien volglies",
'tog-enotifrevealaddr'        => 'Mien e-mailadres tune in e-mailberichter',
'tog-shownumberswatching'     => "'t Aantal gebroekers tune die deze pazjena volge",
'tog-fancysig'                => 'Handjteikening zónger link nao dien gebroekerspazjena',
'tog-externaleditor'          => "Standaard 'ne externe teksbewèrker gebroeke",
'tog-externaldiff'            => "Standaard 'n extern vergeliekingsprogramma gebroeke",
'tog-showjumplinks'           => '"gao nao"-toegankelikheidslinks meugelik make',
'tog-uselivepreview'          => '"live veurbesjouwing" gebroeke (vereis JavaScript - experimenteel)',
'tog-forceeditsummary'        => "'ne Meljing gaeve biej 'n laege samevatting",
'tog-watchlisthideown'        => 'Eige bewèrkinge verberge op mien volglies',
'tog-watchlisthidebots'       => 'Botbewèrkinge op mien volglies verberge',
'tog-watchlisthideminor'      => 'Kleine bewèrkinge op mien volglies verberge',
'tog-nolangconversion'        => 'Variantconversie oetsjakele',
'tog-ccmeonemails'            => "'ne Kopie nao mich verzende van de e-mail dae ich nao anger gebroekers sjtuur",
'tog-diffonly'                => 'Pazjena-inhawd zonger verangeringe neet tune',

'underline-always'  => 'Altiejd',
'underline-never'   => 'Nooits',
'underline-default' => 'Standaard vanne browser',

'skinpreview' => '(Veurbesjouwing)',

# Dates
'sunday'       => 'zondig',
'monday'       => 'maondig',
'tuesday'      => 'dinsdig',
'wednesday'    => 'goonsdag',
'thursday'     => 'donderdig',
'friday'       => 'vriedig',
'saturday'     => 'zaoterdig',
'sun'          => 'zön',
'mon'          => 'mao',
'wed'          => 'woo',
'thu'          => 'dön',
'fri'          => 'vri',
'sat'          => 'zao',
'january'      => 'jannewarie',
'february'     => 'fibberwari',
'january-gen'  => 'jannewarie',
'february-gen' => 'fibberwari',
'feb'          => 'fib',
'mar'          => 'maa',

# Bits of text used by many pages
'categories'            => '{{PLURAL:$1|Categorie|Categorieë}}',
'pagecategories'        => '{{PLURAL:$1|Categorie|Categorieë}}',
'category_header'       => 'Artikele in categorie "$1"',
'subcategories'         => 'Subkattegorië',
'category-media-header' => 'Media in de categorie "$1"',
'category-empty'        => "''Deze categorie is laeg, hae bevat op 't memènt gén artiekele of media.''",

'mainpagetext' => 'Wiki software succesvol geïnsjtalleerd.',

'article'        => 'Contentpazjena',
'newwindow'      => '(in nuui venster)',
'cancel'         => 'Aafbraeke',
'qbfind'         => 'Zeuke',
'qbbrowse'       => 'Bladere',
'qbedit'         => 'Bewirke',
'qbpageoptions'  => 'Pazjena-opties',
'qbpageinfo'     => 'Pazjena-informatie',
'qbmyoptions'    => 'mien opties',
'qbspecialpages' => "Speciaal pazjena's",
'moredotdotdot'  => 'Miè...',
'mypage'         => 'Mien gebroekerspazjena',
'mytalk'         => 'Mien euverlikpazjena',
'anontalk'       => 'Euverlèk veur dit IP adres',
'navigation'     => 'Navegatie',

'returnto'          => 'Truuk nao $1.',
'help'              => 'Hulp',
'search'            => 'Zeuke',
'searchbutton'      => 'Zeuke',
'go'                => 'OK',
'history'           => 'Historie',
'history_short'     => 'Historie',
'updatedmarker'     => 'bewèrk sins mien letste bezeuk',
'info_short'        => 'Infermasie',
'printableversion'  => 'Printer-vruntelike versie',
'permalink'         => 'Permanente link',
'edit'              => 'Bewirk',
'editthispage'      => 'Pazjena bewirke',
'delete'            => 'Wisse',
'deletethispage'    => 'Wisse',
'undelete_short'    => '$1 {{PLURAL:$1|bewèrking|bewèrkinge}} trökplaatse',
'protect'           => 'Besjerm',
'protect_change'    => 'beveiligingsstatus verangere',
'protectthispage'   => 'Beveilige',
'unprotect'         => 'vriegaeve',
'unprotectthispage' => 'Besjerming opheffe',
'newpage'           => 'Nuuj pazjena',
'talkpage'          => 'euverlikpazjena',
'talkpagelinktext'  => 'Euverlèk',
'specialpage'       => 'Speciaal Pazjena',
'personaltools'     => 'Persoenlike hulpmiddele',
'postcomment'       => 'Opmèrking toevoge',
'articlepage'       => 'Artikel',
'talk'              => 'Euverlèk',
'toolbox'           => 'Gereidsjapskis',
'userpage'          => 'gebroekerspazjena',
'projectpage'       => 'Perjèkpazjena tune',
'imagepage'         => 'Besjrievingspazjena',
'mediawikipage'     => 'Berichpazjena tune',
'templatepage'      => 'Sjabloonblaad',
'viewhelppage'      => 'Hölppazjena tune',
'categorypage'      => 'Categoriepazjena tune',
'viewtalkpage'      => 'Bekiek euverlèk',
'otherlanguages'    => 'Anger tale',
'redirectedfrom'    => '(Doorverweze van $1)',
'redirectpagesub'   => 'Redirectpazjena',
'lastmodifiedat'    => "Dees pazjena is 't litst verangert op $2, $1.", # $1 date, $2 time
'viewcount'         => 'Dees pazjena is {{PLURAL:$1|1 kier|$1 kier}} bekeke.',
'protectedpage'     => 'Beveiligde pazjena',
'jumpto'            => 'Gao nao:',
'jumptonavigation'  => 'navigatie',
'jumptosearch'      => 'zeuke',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Euver {{SITENAME}}',
'aboutpage'         => 'Project:Info',
'bugreports'        => 'Fouterapportaasj',
'bugreportspage'    => 'Project:Fouterapportaasj',
'copyright'         => 'De inhawd is besjikbaar ónger de $1.',
'copyrightpagename' => '{{SITENAME}} auteursrechte',
'copyrightpage'     => '{{ns:project}}:Auteursrechte',
'currentevents'     => "In 't nuujs",
'currentevents-url' => "Project:In 't nuujs",
'disclaimers'       => 'Aafwiezinge aansjprakelikheid',
'disclaimerpage'    => 'Project:Algemein aafwiezing aansjprakelikheid',
'edithelp'          => 'Hulp bie bewirke',
'edithelppage'      => 'Help:Instructies',
'faqpage'           => 'Project:Veulgestjilde vraoge',
'helppage'          => 'Help:Help',
'mainpage'          => 'Veurblaad',
'portal'            => 'Gebroekersportaol',
'portal-url'        => 'Project:Gebroekersportaol',
'privacypage'       => 'Project:Privacy_policy',
'sitesupport'       => 'Donaties',
'sitesupport-url'   => 'Project:Gifte',

'badaccess'        => 'Toeganksfout',
'badaccess-group0' => 'Doe höbs gén rechte om de gevräögde hanjeling oet te veure.',
'badaccess-group1' => 'De gevräögde hanjeling is veurbehaoje aan gebroekers in de groep $1.',
'badaccess-group2' => 'De gevräögde hanjeling is veurbehaoje aan gebroekers in éin van de gruup $1.',
'badaccess-groups' => 'De gevräögde hanjeling is veurbehaoje aan gebroekers in éin van de gruup $1.',

'versionrequired'     => 'Versie $1 van MediaWiki is vereis',
'versionrequiredtext' => 'Versie $1 van MediaWiki is vereis om deze pazjena te gebroeke. Zee [[Special:Version|Softwareversie]]',

'retrievedfrom'           => 'Aafkómstig van "$1"',
'youhavenewmessages'      => 'Doe höbs $1 ($2).',
'newmessageslink'         => 'nuuj berichte',
'newmessagesdifflink'     => 'Lèste verangering',
'youhavenewmessagesmulti' => 'Doe höbs nuje berichter op $1',
'editsection'             => 'bewirk',
'editold'                 => 'bewèrke',
'editsectionhint'         => 'Deilpazjena bewèrke: $1',
'toc'                     => 'Inhawd',
'showtoc'                 => 'tune',
'hidetoc'                 => 'verberg',
'thisisdeleted'           => '$1 bekieke of trökzètte?',
'viewdeleted'             => 'tuun $1?',
'restorelink'             => '$1 verwiederde versies',
'feed-invalid'            => 'Feedtype wörd neet ongersteund.',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Artikel',
'nstab-user'      => 'Gebroeker',
'nstab-media'     => 'Mediapazjena',
'nstab-project'   => 'Perjèkpazjena',
'nstab-image'     => 'Aafbeilding',
'nstab-mediawiki' => 'Berich',
'nstab-help'      => 'Help pazjena',
'nstab-category'  => 'Categorie',

# Main script and global functions
'nosuchaction'      => 'Gevräögde hanjeling besjteit neet',
'nosuchactiontext'  => 'De door de URL gespecifieerde handeling wordt neet herkend door de MediaWiki software',
'nosuchspecialpage' => "D'r besjteit gein speciaal pazjena mit deze naam",
'nospecialpagetext' => "Doe höbs 'ne speciale pazjena aangevräögd dae neet wörd herkind door de MediaWiki software",

# General errors
'dberrortext'          => 'Bie \'t zeuke is \'n syntaxfout in de database opgetreje.
Dit kint zien veroorzaak door \'n óngeljige zeukactie (zuug $5),
of \'t duujt op \'n fout in de software. 
De lètste zeukpoeging in de database waor:
<blockquote><tt>$1</tt></blockquote>
vanoet de functie "<tt>$2</tt>".
MySQL gaof de foutmèlling "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Dao is \'n syntaxfout opgetreje bie \'t zeuke in de database.
De lèste opgevraogde zeukactie waor:
"$1"
vanoet de functie "$2".
MySQL brach fout "$3" nao veure: "$4"',
'noconnect'            => 'Verbinden met de database op $1 was neet mogelijk',
'nodb'                 => 'Selectie van database $1 neet mogelijk',
'cachederror'          => "Dit is 'n gearsjiveerde kopie van de gevraogde pazjena, en is mesjien neet gans actueel.",
'laggedslavemode'      => 'Waorsjuwing: De pazjena kin veraajerd zeen.',
'enterlockreason'      => "Gaef 'n rae veur de blokkering en wie lank 't dinkelik zal dore. De ingegaeve rae zal aan de gebroekers getuind waere.",
'readonlytext'         => 'De database van {{SITENAME}} is momenteel gesloten voor nieuwe bewerkingen en wijzigingen, waarschijnlijk voor bestandsonderhoud.
De verantwoordelijke systeembeheerder gaf hiervoor volgende reden op:
<p>$1',
'missingarticle'       => 'De database haet \'n pazjenatèks ("$1") die \'t zou motte vinge neet gevonge. Dit is gein fout in de database, mer waarscjienlik in de software. Meld dit estebleef aan inne adminstrator, mit vermèlding van de URL.',
'readonly_lag'         => 'De database is automatisch vergrendeld wiele de slave databaseservers synchronisere mèt de master.',
'filecopyerror'        => 'Besjtand "$1" nao "$2" kopiëre neet mugelik.',
'filerenameerror'      => 'Verangere van de titel van \'t besjtand "$1" in "$2" neet meugelik.',
'filedeleteerror'      => 'Kos bestjand "$1" neet weghaole.',
'directorycreateerror' => 'Map "$1" kòs neet aangemaak waere.',
'filenotfound'         => 'Kos bestjand "$1" neet vinge.',
'fileexistserror'      => 'Sjrijve nao bestandj "$1" woor neet meugelik: \'t bestandj besjteit al',
'formerror'            => 'Fout: kos formeleer neet verzende',
'badarticleerror'      => 'Dees hanjeling kint neet weure oetgeveurd op dees pazjena.',
'cannotdelete'         => 'Kós de pazjena of aafbeilding neet wisse.',
'badtitle'             => 'Óngeljige pazjenatitel',
'badtitletext'         => 'De opgevraogde pazjena is neet besjikbaar of laeg.',
'perfdisabled'         => 'Om te veurkomme dat de database weurd euverbelast is dees pazjena allein tusje 03:00 en 15:00 (Wes-Europiese zoemertied) besjikbaar.',
'perfcached'           => 'De volgende data is gecachet en is mesjien neet gans up to date:',
'perfcachedts'         => "De getuunde gegaeves komme oet 'n cache en zeen veur 't letst biejgewèrk op $1.",
'querypage-no-updates' => "Deze pazjena kin op 't memènt neet biejgewèrk waere. Deze gegaeves waere neet vervèrs.",
'wrong_wfQuery_params' => 'Verkeerde paramaeters veur wfQuery()<br />
Funksie: $1<br />
Query: $2',
'viewsource'           => 'Bekiek brónteks',
'protectedpagetext'    => 'Deze pazjena is beveilig. Bewèrke is neet meugelik.',
'viewsourcetext'       => 'Doe kins de bronteks van deze pazjena bekieke en kopiëre:',
'protectedinterface'   => 'Deze pazjena bevat teks veur berichte van de software en is beveilig om misbroek te veurkomme.',
'editinginterface'     => "'''Waorsjuwing:''' Doe bewèrks 'ne pazjena dae gebroek wörd door de software. Bewèrkinge op deze pazjena beïnvloeje de gebroekersinterface van ederein.",
'sqlhidden'            => '(SQL query verborge)',
'cascadeprotected'     => "Deze pazjena kin neet bewèrk waere, omdet dae is opgenaome in de volgende {{PLURAL:$1|pazjena|pazjena's}} die beveilig {{PLURAL:$1|is|zeen}} mèt de kaskaad-optie:
$2",
'namespaceprotected'   => "Doe höbs gén rechte om pazjena's in de naamruumde '''$1''' te bewèrke.",
'customcssjsprotected' => "Doe kins deze pazjena neet bewèrke omdet dae perseunlikke instellinge van 'ne angere gebroeker bevat.",
'ns-specialprotected'  => 'Pazjena\'s in de naamruumde "{{ns:special}}" kinne neet bewèrk waere.',

# Login and logout pages
'logouttitle'                => 'Aafmèlde gebroeker',
'logouttext'                 => 'De bis noe aafgemèld. De kins {{SITENAME}} noe anoniem (mit vermèlding van IP adres) gebroeke, of opnuui aanmèlde onger dezelfde of ein anger naam.',
'welcomecreation'            => '<h2>Wilkóm, $1!</h2><p>Dien gebroekersprofiel is vaerdig. De kins noe dien persuunlike veurkäöre insjtèlle.',
'loginpagetitle'             => 'gebroekersnaam',
'yourname'                   => 'Diene gebroekersnaam',
'yourpassword'               => 'Die wachwaord',
'yourpasswordagain'          => 'Wachwaord opnuuj intype',
'remembermypassword'         => 'Mien wachwaord onthouwe veur later sessies.',
'yourdomainname'             => 'Die domein',
'externaldberror'            => "d'r Is 'n fout opgetraoje biej 't aanmelje biej de database of doe höbs gén toesjtömming diene externe gebroeker biej te wèrke.",
'loginproblem'               => "<b>D'r is 'n prebleim mèt 't aanmèlde.</b><br />Probeer estebleef nog es.",
'login'                      => 'Aanmèlde',
'loginprompt'                => "Diene browser mót ''cookies'' acceptere óm in te logge op {{SITENAME}}.",
'userlogin'                  => 'Aanmèlde',
'logout'                     => 'Aafmèlde',
'userlogout'                 => 'Aafmèlde',
'notloggedin'                => 'Neet aangemeld',
'nologin'                    => 'Höbs te nog geine gebroekersnaam? $1.',
'nologinlink'                => "Maak 'ne gebroekersnaam aan",
'createaccount'              => 'Nuuj gebroekersprofiel aanmake.',
'gotaccount'                 => "Höbs te al 'ne gebroekersnaam? $1.",
'badretype'                  => 'De ingeveurde wachwäörd versjille vanein.',
'userexists'                 => "De gebroekersnaam dae se höbs ingeveurd weurt al gebroek. Kees estebleef 'n anger naam.",
'youremail'                  => 'Dien e-mailadres',
'username'                   => 'Gebroekersnaam:',
'uid'                        => 'Gebroekersnómmer:',
'yourrealname'               => 'Dienen echte naam*',
'yourlanguage'               => 'Taal van de gebroekersinterface',
'yournick'                   => "Diene bienaam (veur ''handjteikeninge'')",
'badsig'                     => 'Óngeljige roew handjteikening; zuug de HTML-tags nao.',
'badsiglength'               => "Biejnaam is te lank; 't mót onger $1 karakters zeen.",
'prefs-help-realname'        => '* Echte naam (opsjeneel): esse deze opgufs kin deze naam gebroek waere om dich erkinning te gaeve veur dien wèrk.',
'loginerror'                 => 'Inlogfout',
'prefs-help-email'           => '* E-mail (optioneel): Hiedoor kan me contak mit diech opnumme zónger dats te dien identiteit hoofs vrie te gaeve.',
'nocookiesnew'               => "De gebroeker is aangemaak mèr neet aangemeld. {{SITENAME}} gebroek cookies veur 't aanmelje van gebroekers. Sjakel die a.u.b. in en meld dao nao aan mèt diene nuje gebroekersnaam en wachwaord.",
'nocookieslogin'             => "{{SITENAME}} gebroek cookies veur 't aanmelje van gebroekers. Doe accepteers gén cookies. Sjakel deze optie a.u.b. in en perbeer 't oppernuuj.",
'noname'                     => "De mos 'n gebroekersnaam opgaeve.",
'loginsuccesstitle'          => 'Aanmèlde geluk.',
'loginsuccess'               => 'De bis noe es "$1" aangemèld bie {{SITENAME}}.',
'nosuchuser'                 => 'Er bestaat geen gebroeker met de naam "$1". Controleer uw spelling, of gebruik onderstaand formulier om een nieuw gebroekersprofiel aan te maken.',
'nosuchusershort'            => 'De gebroeker "$1" besjteit neet. Konterleer de sjriefwieze.',
'nouserspecified'            => "Doe deens 'ne gebroekersnaam op te gaeve.",
'wrongpassword'              => "'t Ingegaeve wachwaord is neet zjus. Perbeer 't obbenuujts.",
'wrongpasswordempty'         => "'t Ingegaeve wachwoord waor laeg. Perbeer 't obbenuujts.",
'passwordtooshort'           => "Dien wachwaord is te kort. 't Mót minstes oet $1 teikes besjtaon.",
'mailmypassword'             => "Sjik mich 'n nuuj wachwaord",
'passwordremindertitle'      => 'Wachwaordherinnering van {{SITENAME}}',
'passwordremindertext'       => 'Emes (waarsjienliek dich zelf) vanaaf IP-adres $1 haet verzoch u een nieuw wachtwoord voor {{SITENAME}} toe te zenden ($4). Het nieuwe wachtwoord voor gebroeker "$2" is "$3". Advies: nu aanmelden en uw wachtwoord wijzigigen.',
'noemail'                    => 'D\'r is gein geregistreerd e-mailadres veur "$1".',
'passwordsent'               => 'D\'r is \'n nuui wachwaord verzonde nao \'t e-mailadres dat geregistreerd sjtit veur "$1".
Gelieve na ontvangst opnieuw aan te melden.',
'blocked-mailpassword'       => "Dien IP-adres is geblokkeerd veur 't make van verangeringe. Om misbroek te veurkomme is 't neet meugelik om 'n nuuj wachwaord aan te vraoge.",
'eauthentsent'               => "Dao is 'ne bevèstigingse-mail nao 't genomineerd e-mailadres gesjik.
Iedat anger mail nao dat account versjik kan weure, mós te de insjtructies in daen e-mail volge,
óm te bevèstige dat dit wirkelik dien account is.",
'throttled-mailpassword'     => "'n Wachwaordherinnering wörd gedurende de letste $1 oer verzönje. Om misbroek te veurkomme, wörd d'r sjlechs éin herinnering per $1 oer verzönje.",
'mailerror'                  => "Fout bie 't versjture van mail: $1",
'acct_creation_throttle_hit' => "Sorry, de höbs al $1 accounts aangemak. De kins d'r gein mië aanmake.",
'emailauthenticated'         => 'Dien e-mailadres is op $1 geauthentiserd.',
'emailnotauthenticated'      => 'Dien e-mailadres is nog neet geauthentiseerd. De zals gein
e-mail óntvange veur alle volgende toepassinge.',
'noemailprefs'               => "Gaef 'n e-mailadres op om deze functies te gebroeke.",
'emailconfirmlink'           => 'Bevèstig dien e-mailadres',
'invalidemailaddress'        => "'t E-mailadres is neet geaccepteerd omdet 't 'n ongeldige opmaak haet. Gaef a.u.b. 'n geldig e-mailadres op of laot 't veld laeg.",
'accountcreated'             => 'Gebroeker aangemaak',
'accountcreatedtext'         => 'De gebroeker $1 is aangemaak.',
'loginlanguagelabel'         => 'Taol: $1',

# Password reset dialog
'resetpass'               => 'Wachwaord oppernuuj instelle',
'resetpass_announce'      => "Doe bös aangemeld mèt 'ne tiejdelikke code dae per e-mail is toegezönje. Veur 'n nuuj wachwaord in om 't aanmelje te voltooie:",
'resetpass_header'        => 'Wachwaord oppernuuj instelle',
'resetpass_submit'        => 'Wachwaord instelle en aanmelje',
'resetpass_success'       => 'Dien wachwaord is verangerd. Bezig mèt aanmelje...',
'resetpass_bad_temporary' => "Ongeldig tiejdelik wachwaord. Doe höbs dien wachwaord al verangerd of 'n nuuj tiejdelik wachwaord aangevräög.",
'resetpass_forbidden'     => 'Wachwäörd kinne neet verangerd waere op deze wiki',
'resetpass_missing'       => 'Doe höbs gén wachwaord ingegaeve.',

# Edit page toolbar
'bold_sample'     => 'Vetten teks',
'bold_tip'        => 'Vetten teks',
'italic_sample'   => 'Italic tèks',
'italic_tip'      => 'Italic tèks',
'link_sample'     => 'Link titel',
'extlink_sample'  => 'http://www.example.com link titel',
'extlink_tip'     => 'Externe link (mit de http:// prefix)',
'headline_sample' => 'Deilongerwerp',
'headline_tip'    => 'Tusseköpske (hoogste niveau)',
'math_sample'     => 'Veur de formule in',
'math_tip'        => 'Wiskóndige formule (LaTeX)',
'nowiki_sample'   => 'Veur hiej de neet op te make teks in',
'nowiki_tip'      => 'Verloup wiki-opmaak',
'image_tip'       => 'Aafbeilding',
'media_tip'       => 'Link nao bestandj',
'sig_tip'         => 'Diene handjteikening mèt datum en tiejd',
'hr_tip'          => 'Horizontale lien (gebroek spaarzaam)',

# Edit pages
'summary'                   => 'Samevatting',
'subject'                   => 'Ongerwerp/kop',
'minoredit'                 => "Dit is 'n klein verangering",
'watchthis'                 => 'Volg dees pazjena',
'savearticle'               => 'Pazjena opsjlaon',
'preview'                   => 'Naokieke',
'showpreview'               => 'Bekiek dees bewirking',
'showlivepreview'           => 'Bewèrking ter kontraol tune',
'showdiff'                  => 'Toen verangeringe',
'anoneditwarning'           => 'Doe bös neet ingelog. Dien IP adres wörd opgesjlage in de gesjiedenis van dees pazjena.',
'missingsummary'            => "'''Herinnering:''' Doe höbs gén samevatting opgegaeve veur dien bewèrking. Esse nogmaals op ''Pazjena opslaon'' kliks wörd de bewèrking zonger samevatting opgeslage.",
'missingcommenttext'        => 'Plaats dien opmèrking hiej onger, a.u.b.',
'missingcommentheader'      => "'''Let op:''' Doe höbs gén ongerwerp/kop veur deze opmèrking opgegaeve. Esse oppernuuj op \"opslaon\" kliks, wörd dien verangering zonger ongerwerp/kop opgeslage.",
'summary-preview'           => 'Naokieke samevatting',
'subject-preview'           => 'Naokieke ongerwerp/kop',
'blockedtitle'              => 'Gebroeker is geblokkeerd',
'blockedtext'               => 'Diene gebroekersnaam of IP-adres is geblokkeerd door $1. De opgegaeve raeje:<br />$2<br />De kins veur euverlik kontak opnumme mit de [[{{MediaWiki:Grouppage-sysop}}|systeemwèrkers]].

Your IP address is $3. Please include this address in any queries you make.',
'autoblockedtext'           => "Dien IP-adres is automatisch geblokkeerd omdet 't gebroek is door 'ne gebroeker, dae is geblokkeerd door $1.
De opgegaeve reje is:

:''$2''

* Aanvang blokkade: $8
* Einde blokkade: $6

Doe kins deze blokkaasj bespraeke mèt $1 of 'ne angere [[{{MediaWiki:Grouppage-sysop}}|beheerder]]. Doe kins gén gebroek make van de functie 'e-mail deze gebroeker', tenzijse 'n geldig e-mailadres opgegaeve höbs in dien [[{{ns:special}}:Preferences|veurkeure]] en 't gebroek van deze functie neet is geblokkeerd. Dien hujig IP-adres is $3 en ' nömmer vanne blokkaasj is #$5. Vermeld beide gegaeves esse örges euver deze blokkaasj reageers.",
'blockedoriginalsource'     => "Hiej onger stuit de bronteks van '''$1''':",
'blockededitsource'         => "Hiej onger stuit de teks van '''dien bewèrkinge''' aan '''$1''':",
'whitelistedittitle'        => 'Geer mót óch inlogke óm te bewirke',
'whitelistedittext'         => 'Geer mót uch $1 óm pajzená te bewirke.',
'whitelistreadtitle'        => 'Geer mót óch inlogke óm dit te kónne laeze',
'whitelistreadtext'         => "Geer mót óch [[Special:Userlogin|inlogke]] óm pazjena's te laeze.",
'whitelistacctitle'         => 'Geer maag gein account aanmake',
'whitelistacctext'          => 'Óm accounts op dees wiki aan te make mót geer [[Special:Userlogin|ingelog]] zeen en de zjuste permissies höbbe.',
'confirmedittitle'          => 'E-mailbevestiging is verplich veurdetse kins bewerke',
'confirmedittext'           => "Doe mos dien e-mailadres bevestige veurse kins bewerke. Veur dien e-mailadres in en bevestig 'm via [[{{ns:special}}:Preferences|dien veurkeure]].",
'nosuchsectiontitle'        => 'Deze subkop bestuit neet',
'nosuchsectiontext'         => "Doe probeers 'n subkop te bewerke dae neet bestuit. Omdet subkop $1 neet bestuit, kin diene bewerking ouch neet waere opgeslage.",
'loginreqtitle'             => 'Aanmelje verplich',
'loginreqlink'              => 'inglogge',
'loginreqpagetext'          => 'De mos $1 om anger pazjenas te bekieke.',
'accmailtitle'              => 'Wachwaord versjtuurd.',
'accmailtext'               => "'t Wachwaord veur '$1' is nao $2 versjtuurd.",
'newarticle'                => '(Nuuj)',
'newarticletext'            => "De höbs 'ne link gevolg nao 'n pazjena die nog neet besjteit. 
Type in de box hiejónger óm de pazjena te beginne (zuug de [[Help:Contents|helppazjena]] veur mier informatie). Es te hie per óngelök terech bis gekómme, klik dan op de '''trök'''-knóp van diene browser.",
'anontalkpagetext'          => "----''Dit is de euverlikpazjena veur 'ne anonieme gebroeker dae nog gein account haet aangemak of dae 't neet gebroek. Daorom gebroeke v'r 't IP adres om de gebroeker te identificere. Dat adres kint weure gedeild doer miedere gebroekers. As e 'ne anonieme gebroeker bis en de höbs 't geveul dat 'r onrillevante commentare aan dich gericht zint, kins e 't biste [[Special:Userlogin|'n account crëere of inlogge]] om toekomstige verwarring mit angere anonieme gebroekers te veurkomme.''",
'noarticletext'             => "(Dees pazjena bevat op 't moment gein teks)",
'clearyourcache'            => "'''Lèt op:''' Nao 't opsjlaon mós te diene browserbuffer wisse óm de verangeringe te zeen: '''Mozilla:''' klik ''Reload'' (of ''Ctrl-R''), '''Firefox / IE / Opera:''' ''Ctrl-F5'', '''Safari:''' ''Cmd-R'', '''Konqueror''' ''Ctrl-R''.",
'usercssjsyoucanpreview'    => "<strong>Tip:</strong> Gebroek de knoep 'Tuun bewerking ter controle' om dien nuuje css/js te teste alveures op sjlaon.",
'usercsspreview'            => "'''Dit is allein 'n veurvertuun van dien perseunlike css, deze is neet opgeslage!'''",
'userjspreview'             => "'''Let op: doe tes noe dien perseunlik JavaScript. De pazjena is neet opgeslage!'''",
'userinvalidcssjstitle'     => "'''Waorsjuwing:''' d'r is geine skin \"\$1\". Let op: dien eige .css- en .js-pazjena's beginne mèt  'ne kleine letter, bijveurbeeld {{ns:user}}:Naam/monobook.css in plaats van {{ns:user}}:Naam/Monobook.css.",
'updated'                   => '(Biegewèrk)',
'note'                      => '<strong>Opmirking:</strong>',
'previewnote'               => "Lèt op: dit is 'n controlepazjena; dien tèks is nog neet opgesjlage!",
'previewconflict'           => "Dees versie toent wie de tèks in 't bôvesjte vèld oet git zeen es e zouws opsjlaon.",
'session_fail_preview'      => "<strong>Sorry! Dien bewerking is neet verwerkt omdat sessiegegevens verlaore zeen gegaon.
Probeer 't opnieuw. Als 't dan nog neet lukt, meldt dich dan aaf en weer aan.</strong>",
'session_fail_preview_html' => "<strong>Sorry! Dien bewerking is neet verwerk omdat sessiegegevens verlaore zeen gegaon.</strong>

''Omdat in deze wiki ruwe HTML is ingesjakeld, is 'n voorvertoning neet meugelik als bescherming taege aanvalle met JavaScript.''

<strong>Als dit een legitieme bewerking is, probeer 't dan opnieuw. Als 't dan nog neet lukt, meldt dich dan aaf en weer aan.</strong>",
'token_suffix_mismatch'     => "<strong>Dien bewerking is geweigerd omdat dien client de laesteikes in 't bewerkingstoken onjuist haet behandeld. De bewerking is geweigerd om verminking van de paginateks te veurkomme. Dit gebeurt soms es d'r een webgebaseerde proxydienst wurt gebroek die foute bevat.</strong>",
'editing'                   => 'Bewirkingspazjena: $1',
'editinguser'               => 'Bewirkingspazjena: $1',
'editingsection'            => 'Bewirke van sectie van $1',
'editingcomment'            => 'Bewirk $1 (commentair)',
'editconflict'              => 'Bewirkingsconflik: $1',
'explainconflict'           => "Jemes angers haet dees pazjena verangerd naodats doe aan dees bewèrking bis begos. 't Ierste teksveld tuint de hujige versie van de pazjena. De mós dien eige verangeringe dao-in inpasse. Allein d'n tèks in 't ierste teksveld weurt opgesjlaoge wens te noe op \"Pazjena opsjlaon\" duujs.<br />",
'yourtext'                  => 'Euren teks',
'storedversion'             => 'Opgesjlage versie',
'nonunicodebrowser'         => '<strong>WAARSJUWING: Diene browser is voldit neet aan de unicode sjtandaarde, gebroek estebleef inne angere browser veurdas e artikele gis bewirke.</strong>',
'editingold'                => "<strong>WAARSJUWING: De bis 'n aw versie van dees pazjena aan 't bewirke. Es e dees bewirking opjsleis, gaon alle verangeringe die na dees versie zien aangebrach verlore.</strong>",
'yourdiff'                  => 'Verangeringe',
'copyrightwarning'          => "Opgelèt: Alle biedrage aan {{SITENAME}} weure geach te zeen vriegegaeve ónger de $2 (zuug $1 veur details). Wens te neet wils dat dienen teks door angere bewirk en versjpreid weurt, kees dan neet veur 'Pazjena opsjlaon'.<br /> Hiebie belaofs te ós ouch dats te dees teks zelf höbs gesjreve, of höbs euvergenómme oet 'n vriej, openbaar brón.<br /> <strong>GEBROEK GEI MATERIAAL DAT BESJIRMP WEURT DOOR AUTEURSRECH, BEHAUVE WENS TE DAO TOESJTÖMMING VEUR HÖBS!</strong>",
'copyrightwarning2'         => "Mèrk op dat alle biedrages aan {{SITENAME}} kinne weure verangerd, aangepas of weggehaold door anger luuj. As te neet wils dat dienen tèks zoemer kint weure aangepas mós te 't hie neet plaatsje.<br />
De beluifs ós ouch dats te dezen tèks zelf höbs gesjreve, of gekopieerd van 'n brón in 't publiek domein of get vergliekbaars (zuug $1 veur details).
<strong>HIE GEIN AUTEURSRECHTELIK BESJIRMP WERK ZÓNGER TOESJTUMMING!</strong>",
'longpagewarning'           => "WAARSJOEWING: Dees pazjena is $1 kilobytes lank; 'n aantal browsers kint probleme höbbe mit 't verangere van pazjena's in de buurt van of groeter es 32 kB. Kiek ofs te sjtökker van de pazjena mesjiens kins verplaatse nao 'n nuuj pazjena.",
'longpageerror'             => "<strong>ERROR: De teks diese höbs toegevoegd haet is $1 kilobyte
groot, wat groter is dan 't maximum van $2 kilobyte. Opslaon is neet meugelik.</strong>",
'readonlywarning'           => "WAARSJUWING: De database is vasgezèt veur ongerhoud, dus op 't mement kins e dien verangeringe neet opsjlaon. De kins dien tèks 't biste opsjlaon in 'n tèksbesjtand om 't later hie nog es te prebere.",
'protectedpagewarning'      => 'WAARSJUWING:  Dees pazjena is besjermd zoedat ze allein doer gebroekers mit administratorrechte kint weure verangerd.',
'semiprotectedpagewarning'  => "'''Let op:''' Deze pagina is beveilig en kin allein door geregistreerde gebroekers bewerk waere.",
'cascadeprotectedwarning'   => "'''Waarschuwing:''' Deze pagina is beveilig en kin allein door beheerders bewerk waere, omdat deze is opgenaome in de volgende {{PLURAL:$1|pagina|pagina's}} {{PLURAL:$1|dae|die}} beveilig {{PLURAL:$1|is|zeen}} met de cascade-optie:",
'templatesused'             => 'Sjablone gebroek in dees pazjena:',
'templatesusedpreview'      => 'Sjablone gebroek in deze veurvertuning:',
'templatesusedsection'      => 'Sjablone die gebroek waere in deze subkop:',
'template-protected'        => '(besjörmp)',
'template-semiprotected'    => '(semi-besjörmp)',
'nocreatetitle'             => "'t Aanmake van pazjena's is beperk",
'nocreatetext'              => "Deze website haet de meugelikheid om nuuje pazjena's te make bepèrk.
Doe kins al besjtaonde pazjena's verangere, of doe kins [[{{ns:special}}:Userlogin|dich aanmelje of 'ne gebroeker aanmake]].",
'nocreate-loggedin'         => "Doe kins gein nuuje pazjena's make op deze wiki.",
'permissionserrors'         => 'Foute inne rèchter',
'permissionserrorstext'     => 'Doe höbs gein rèchter om det te daon om de volgende {{PLURAL:$1|reje|rejer}}:',
'recreate-deleted-warn'     => "'''Waorsjuwing: Doe bös bezig mit 't aanmake van 'ne pazjena dae in 't verleje gewis is.'''

Euverwaeg of 't terech is detse wiejer wèrks aan dees pazjena. Veur dien gemaak stuit hiej onger 't logbook verwijderde pazjena's veur dees pazjena:",

# "Undo" feature
'undo-success' => "Hiej onger stuit de teks wo in de verangering ongedaon gemaak is. Controleer veur 't opslaon of 't resultaot gewins is.",
'undo-failure' => 'De verangering kòs neet ongedaon gemaak waere waeges angere striedige verangeringe.',
'undo-summary' => 'Versie $1 van [[{{ns:special}}:Contributions/$2|$2]] ([[User talk:$2|euverlèk]]) ongedaon gemaak',

# Account creation failure
'cantcreateaccounttitle' => 'Aanmake gebroeker misluk.',
'cantcreateaccount-text' => "'t Aanmake van gebroekers van dit IP-adres (<b>$1</b>) is geblokkeerd door [[User:$3|$3]].

De door $3 opgegaeve reje is ''$2''",

# History pages
'viewpagelogs'        => 'Logbeuk veur dees pazjena tune',
'nohistory'           => 'Dees pazjena is nog neet bewirk.',
'revnotfound'         => 'Wieziging neet gevonge',
'revnotfoundtext'     => 'De opgevraogde aw versie van dees pazjena is verzjwónde. Kontroleer estebleef de URL dieste gebroek höbs óm nao dees pazjena te gaon.',
'loadhist'            => "Bezig met 't laje van de pazjenahistorie",
'currentrev'          => 'Hujige versie',
'revisionasof'        => 'Versie op $1',
'revision-info'       => 'Versie op $1 door $2',
'previousrevision'    => '← Awwer versie',
'nextrevision'        => 'Nuujere versie→',
'currentrevisionlink' => 'zuug hujige versie',
'cur'                 => 'hujig',
'last'                => 'vörrige',
'orig'                => 'orzj',
'page_first'          => 'irste',
'page_last'           => 'litste',
'histlegend'          => 'Verklaoring aafkortinge: (wijz) = versjil mit actueile versie, (vörrige) = versjil mit vörrige versie, K = kleine verangering',
'deletedrev'          => '[gewis]',
'histfirst'           => 'Aajste',
'histlast'            => 'Nuujste',
'historyempty'        => '(laeg)',

# Revision feed
'history-feed-title'          => 'Bewerkingseuverzich',
'history-feed-description'    => 'Bewerkingseuverzich veur dees pazjena op de wiki',
'history-feed-item-nocomment' => '$1 op $2', # user at time
'history-feed-empty'          => "De gevraogde pazjena bestuit neet.
Wellich is d'r gewis of vernäömp.
[[Special:Search|Doorzeuk de wiki]] veur relevante pazjena's.",

# Revision deletion
'rev-deleted-comment'         => '(opmerking weggehaold)',
'rev-deleted-user'            => '(gebroeker weggehaold)',
'rev-deleted-event'           => '(actie weggehaold)',
'rev-deleted-text-permission' => "<div class=\"mw-warning plainlinks\">
De gesjiedenis van deze pagina is gewis oet de publieke archieve.
d'r Kinne details aanwezig zeen in 't [{{fullurl:Special:Log/delete|page={{PAGENAME}}}} logbook verwiederde pagina's].
</div>",
'rev-deleted-text-view'       => "<div class=\"mw-warning plainlinks\">
De gesjiedenis van deze pagina is gewis oet de publieke archieve.
Es beheerder van deze site kinse deze zeen;
d'r kinne details aanwezig zeen in 't [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} logbook verwiederde pagina's].
</div>",
'rev-delundel'                => 'tuun/verberg',
'revisiondelete'              => 'Verwijder/herstel bewerkinge',
'revdelete-nooldid-title'     => 'Geine doelverzie',
'revdelete-nooldid-text'      => 'Doe höbs gein(e) doelverzie(s) veur deze hanjeling opgegaeve.',
'revdelete-selected'          => "Geselecteerde {{PLURAL:$2|bewerking|bewerkinge}} van '''[[:$1]]''':",
'logdelete-selected'          => "{{PLURAL:$2|Geselecteerde log gebeurtenis|Geselecteerde log gebeurtenisse}} veur '''$1:'''",
'revdelete-text'              => "Gewisjde bewerkinge zeen zichbaar in de gesjiedenis, maar de inhoud is neet langer publiek toegankelik.

Anger beheerders van deze wiki kinne de verborge inhoud benäöjere en de verwiedering ongedaon make mit behölp van dit sjerm, tenzij d'r additionele restricties gelje die zeen ingesteld door de systeembeheerder.",
'revdelete-legend'            => 'Stel versiebeperkinge in:',
'revdelete-hide-text'         => 'Verberg de bewerkte teks',
'revdelete-hide-user'         => 'Verberg gebroekersnaam/IP van de gebroeker',
'revdelete-hide-restricted'   => 'Pas deze beperkinge toe op zowaal beheerders es angere',
'revdelete-suppress'          => 'Ongerdruk gegaeves veur zowaal admins es angere',
'revdelete-hide-image'        => 'Verberg bestandjsinhoud',
'revdelete-unsuppress'        => 'Verwijder beperkinge op truuk gezatte wieziginge',
'revdelete-log'               => 'Opmerking in logbook:',
'revdelete-submit'            => 'Pas toe op de geselecteerde bewèrking',
'revdelete-logentry'          => 'zichbaarheid van bewerkinge is gewiezig veur [[$1]]',
'logdelete-logentry'          => 'gewiezigde zichbaarheid van gebeurtenis [[$1]]',
'revdelete-logaction'         => '$1 {{PLURAL:$1|wieziging|wieziginge}} gezat nao mode $2',
'logdelete-logaction'         => '$1 {{PLURAL:$1|actie|acties}} om [[$3]] nao modus $2 in te stelle',
'revdelete-success'           => 'Wieziging zichbaarheid succesvol ingesteld.',
'logdelete-success'           => 'Zichbaarheid van de gebeurtenis succesvol ingesteld.',

# Oversight log
'oversightlog'    => 'Oversight-logbook',
'overlogpagetext' => "Hiej onher is 'ne lies mit de meist recente verwiederinge en blokkeringe mit betrekking tot informatie dae neet zichbaar is veur admins. Zee de [[Special:Ipblocklist|Lies van geblokkeerde gebroekers en IP-adresse]] veur 'ne lies van de blokkades en verbanninge die noe gelje.",

# Diffs
'history-title'           => 'Gesjiedenis van "$1"',
'difference'              => '(Versjil tösje bewirkinge)',
'lineno'                  => 'Tekslien $1:',
'compareselectedversions' => 'Vergeliek geselecteerde versies',
'editundo'                => 'ongedaon make',
'diff-multi'              => '({{plural:$1|éin tusseligkede versie wörd|$1 tusseligkede versies waere}} neet getuund)',

# Search results
'searchresults'         => 'Zeukresultate',
'searchresulttext'      => 'Veur mier informatie euver zeuke op {{SITENAME}}, zuug [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'        => "Doe zòchs veur '''[[:$1]]'''",
'searchsubtitleinvalid' => 'Voor zoekopdracht "$1"',
'noexactmatch'          => "'''d'r Bestuit geine pazjena mit ongerwerp  $1.''' Doe kins 'm [[:$1|aanmake]].",
'titlematches'          => 'Overeinkoms mèt volgende titels',
'notitlematches'        => 'Geen enkele paginatitel gevonden met de opgegeven zoekterm',
'textmatches'           => 'Euvereinkoms mèt artikelinhoud',
'notextmatches'         => 'Geen artikel gevonden met opgegeven zoekterm',
'prevn'                 => 'vörrige $1',
'viewprevnext'          => '($1) ($2) ($3) bekieke.',
'showingresults'        => 'Hieonger de <b>$1</b> resultate, vanaaf #<b>$2</b>.',
'showingresultsnum'     => 'Hieonger de <b>$3</b> resultate vanaaf #<b>$2</b>.',
'nonefound'             => '<strong>Lèt op:</strong> \'n zeukopdrach kan mislökke door \'t gebroek van (in \'t Ingelsj) väöl veurkómmende wäörd wie "of" en "be", die neet geïndexeerd zint, of door versjillende zeukterme tegeliek op te gaeve (de kries dan allein pazjena\'s te zeen woerin alle opgegaeve terme veurkómme).',
'powersearch'           => 'Zeuke',
'powersearchtext'       => '   
 Zeuk in naomroemdes :<br />
$1<br />
$2 Toen redirects   Zeuk: $3 $9',
'searchdisabled'        => '<p style="margin: 1.5em 2em 1em">Zeuke op {{SITENAME}} is oetgesjakeld vanweige gebrek aan servercapaciteit. Zoelang as de servers nog neet sjterk genog zunt kins e zeuke bie Google.
<span style="font-size: 89%; display: block; margin-left: .2em">Mèrk op dat hun indexe van {{SITENAME}} content e bietje gedatierd kint zien.</span></p>',

# Preferences page
'preferences'              => 'Veurkäöre',
'mypreferences'            => 'Mien veurkäöre',
'prefs-edits'              => 'Aantal bewèrkinge:',
'prefsnologin'             => 'Neet aangemèld',
'prefsnologintext'         => 'De mos zien [[Special:Userlogin|aangemèld]] om veurkäöre te kinne insjtèlle.',
'prefsreset'               => 'Sjtandaardveurkäöre hersjtèld.',
'qbsettings'               => 'Menubalkinsjtèllinge',
'qbsettings-none'          => 'Oetgesjakeld',
'qbsettings-fixedleft'     => 'Links vas',
'qbsettings-fixedright'    => 'Rechts vas',
'qbsettings-floatingleft'  => 'Links zjwevend',
'qbsettings-floatingright' => 'Rechs zjwevend',
'changepassword'           => 'Wachwaord verangere',
'skin'                     => '{{SITENAME}}-uterlik',
'math'                     => 'Mattemetik rendere',
'dateformat'               => 'Datumformaat',
'datedefault'              => 'Gein veurkäör',
'datetime'                 => 'Datum en tied',
'math_failure'             => 'Parse misluk',
'math_unknown_error'       => 'onbekènde fout',
'math_unknown_function'    => 'onbekènde functie',
'math_syntax_error'        => 'fout vanne syntax',
'math_image_error'         => 'PNG-conversie is misluk. Gao nao of latex, dvips en gs correc geïnstalleerd zeen en converteer nogmaols',
'math_bad_tmpdir'          => 'De map veur tiedelike bestenj veur wiskóndige formules bestuit neet of kin neet gemaak waere',
'math_bad_output'          => 'Kin neet sjrieve nao de output directory veur mattematik',
'math_notexvc'             => "Kin 't programma texvc neet vinje; stel alles in volges de besjrieving in math/README.",
'prefs-personal'           => 'Gebroekersinfo',
'prefs-rc'                 => 'Recènte verangeringe en weergaaf van sjtumpkes',
'prefs-watchlist'          => 'Volglies',
'prefs-watchlist-days'     => 'Maximaal aantal daag in de volglies:',
'prefs-watchlist-edits'    => 'Maximaal aantal bewerkinge in de oetgebreide volglies:',
'prefs-misc'               => 'Anger insjtèllinge',
'saveprefs'                => 'Veurkäöre opsjlaon',
'resetprefs'               => 'Sjtandaardveurkäöre hersjtèlle',
'oldpassword'              => 'Hujig wachwaord',
'newpassword'              => 'Nuuj wachwaord',
'retypenew'                => "Veur 't nuuj wachwaord nogins in",
'textboxsize'              => 'Aafmeitinge tèksveld',
'rows'                     => 'Raegels',
'columns'                  => 'Kolomme',
'searchresultshead'        => 'Insjtèllinge veur zeukresultate',
'resultsperpage'           => 'Aantal te toene zeukresultate per pazjena',
'contextlines'             => 'Aantal reigels per gevónje pazjena',
'contextchars'             => 'Aantal teikes van de conteks per reigel',
'stub-threshold'           => 'Drempel veur markering <a href="#" class="stub">begske</a>:',
'recentchangesdays'        => 'Aantal daag te tune in de recènte verangeringe:',
'recentchangescount'       => 'Aantal titels in lies recènte verangeringe',
'savedprefs'               => 'Dien veurkäöre zint opgesjlage.',
'timezonelegend'           => 'Tiedzone',
'timezonetext'             => "'t Aantal oere dat diene lokale tied versjilt van de servertied (UTC).",
'localtime'                => 'Plaotsjelike tied',
'timezoneoffset'           => 'tiedsverschil',
'servertime'               => 'Server tied is noe',
'guesstimezone'            => 'Invulle van browser',
'allowemail'               => 'E-mail van anger gebroekers toesjtaon',
'defaultns'                => 'Zeuk sjtandaard in dees naomruumdes:',
'default'                  => 'sjtandaard',
'files'                    => 'Bestenj',

# User rights
'userrights-lookup-user'      => 'Beheer gebroekersgruup',
'userrights-user-editname'    => "Veur 'ne gebroekersnaam in:",
'editusergroup'               => 'Bewerk gebroekersgruup',
'userrights-editusergroup'    => 'Bewerk gebroekersgruup',
'saveusergroups'              => 'Gebroekersgruup opslaon',
'userrights-groupsmember'     => 'Leed van:',
'userrights-groupsavailable'  => 'Besjikbare gruup:',
'userrights-groupshelp'       => 'Selecteer de gruup wo oetse de gebroeker wils wisse of aan toe wils voege.
Neet geselecteerde gruup waere neet gewiezig. Deselecteer \'ne groep mit "Ctrl + linkermoesknoep".',
'userrights-reason'           => "Reje veur 't verangere:",
'userrights-available-none'   => 'Doe moogs gein gebroekersrechte verangere.',
'userrights-available-add'    => 'Doe kins gebroekers toevoege aan $1.',
'userrights-available-remove' => 'Doe kins gebroekers verwiedere oet $1.',

# Groups
'group-autoconfirmed' => 'Geregistreerde gebroekers',
'group-bureaucrat'    => 'Bureaucrate',
'group-all'           => '(alle)',

'group-autoconfirmed-member' => 'Geregistreerde gebroeker',

'grouppage-autoconfirmed' => '{{ns:project}}:Geregistreerde gebroekers',
'grouppage-bureaucrat'    => '{{ns:project}}:Bureaucrate',

# User rights log
'rightslog'      => 'Gebroekersrechtelogbook',
'rightslogtext'  => 'Hiej onger staon de wieziginge in gebroekersrechte.',
'rightslogentry' => 'wiezigde de gebroekersrechte veur $1 van $2 nao $3',
'rightsnone'     => '(gein)',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|bewerking|bewerkinge}}',
'recentchanges'                     => 'Recènte verangeringe',
'recentchangestext'                 => 'literal translation',
'recentchanges-feed-description'    => 'Volg de meis recente bewerkinge in deze wiki via deze feed.',
'rcnote'                            => 'Hiejónger sjtaon de <strong>$1</strong> lètste verangeringe van de aafgeloupe <strong>$2</strong> daag, $3.',
'rcnotefrom'                        => "Verangeringe sins <b>$2</b> (mit 'n maximum van <b>$1</b> verangeringe).",
'rclistfrom'                        => 'Toen de verangeringe vanaaf $1',
'rcshowhideminor'                   => '$1 klein bewèrkinge',
'rcshowhideliu'                     => '$1 aangemelde gebroekers',
'rcshowhideanons'                   => '$1 anonieme gebroekers',
'rcshowhidepatr'                    => '$1 gecontroleerde bewerkinge',
'rcshowhidemine'                    => '$1 mien bewerkinge',
'rclinks'                           => 'Bekiek de $1 litste verangeringe van de aafgelaupe $2 daag.<br />$3',
'diff'                              => 'vers',
'hist'                              => 'gesj',
'hide'                              => 'verberg',
'show'                              => 'toen',
'minoreditletter'                   => 'K',
'number_of_watching_users_pageview' => "[$1 keer op 'ne volglies]",
'rc_categories'                     => 'Tuun allein categorië (sjèt mit \'ne "|")',
'rc_categories_any'                 => 'Iddere',
'newsectionsummary'                 => '/* $1 */ nuje subkop',

# Recent changes linked
'recentchangeslinked'          => 'Volg links',
'recentchangeslinked-title'    => 'Wieziginge verwantj mit $1',
'recentchangeslinked-noresult' => "d'r Zeen gein bewerkinge in de gegaeve periode gewaes op de pagina's die vanaaf hiej gelink waere.",
'recentchangeslinked-summary'  => "Deze speciale pagina tuunt de letste bewerkinge op pagina's die gelink waere vanaaf deze pagina. Pagina's die op [[Special:Watchlist|diene volglies]] staon waere '''vet''' weergegaeve.",

# Upload
'upload'                      => 'Upload',
'uploadbtn'                   => 'bestandj uploade',
'reupload'                    => 'Opnuui uploade',
'reuploaddesc'                => "Truuk nao 't uploadformeleer.",
'uploadnologin'               => 'Neet aangemèld',
'uploadnologintext'           => 'De mos [[Special:Userlogin|zien aangemèld]] om besjtande te uploade.',
'upload_directory_read_only'  => 'De webserver kin neet sjrieve in de uploadmap ($1).',
'uploaderror'                 => "fout in 't uploade",
'uploadtext'                  => "Gebroek 't óngersjtaonde formuleer óm besjtande op te laje. Óm ierder opgelaje besjtande te bekieke of te zeuke, gank nao de [[Special:Imagelist|lies van opgelaje besjtande]]. Uploads en verwiederinge waere ouch biegehauwte in 't [[Special:Log/upload|uploadlogbook]]. 

Gebroek óm 'n plaetje of 'n besjtand in 'n pazjena op te numme 'ne link in de vörm:
* '''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:Besjtand.jpg]]</nowiki>'''
* '''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:Besjtand.png|alternatief teks]]</nowiki>'''
of veur mediabesjtande:
* '''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:Besjtand.ogg]]</nowiki>'''",
'uploadlog'                   => 'uploadlogbook',
'uploadlogpage'               => 'Uploadlogbook',
'uploadlogpagetext'           => 'Hieonger de lies mit de meist recent ge-uploade besjtande. Alle tiede zunt servertiede (UTC).',
'filename'                    => 'Besjtandsnaom',
'filedesc'                    => 'Besjrieving',
'fileuploadsummary'           => 'Samevatting:',
'filesource'                  => 'Brón',
'uploadedfiles'               => 'Ge-uploade bestanden',
'ignorewarning'               => "Negeer deze waarsjuwing en slao 't bestandj toch op.",
'ignorewarnings'              => 'Negeer alle waarsjuwinge',
'minlength1'                  => 'Bestandsname mòtte minstes éine letter bevatte.',
'illegalfilename'             => 'De bestandjsnaam "$1" bevat ongeldige karakters. Gaef \'t bestandj \'ne angere naam, en probeer \'t dan opnuuj te uploade.',
'badfilename'                 => 'De naom van \'t besjtand is verangerd in "$1".',
'filetype-badmime'            => '\'t Is neet toegestaon om bestenj van MIME type "$1" te uploade.',
'filetype-missing'            => 'Dit bestandj haet gein extensie (wie ".jpg").',
'large-file'                  => 'Aanbeveling: maak bestenj neet groter dan $1, dit bestand is $2.',
'largefileserver'             => "'t Bestandj is groter dan de instelling van de server toestuit.",
'emptyfile'                   => "'t Besjtand wats re höbs geupload is laeg. Dit kump waorsjienliek door 'n typfout in de besjtandsnaom. Kiek estebleef ofs te dit besjtand wirkelik wils uploade.",
'fileexists'                  => "D'r is al e besjtand mit dees naam, bekiek $1 of se dat besjtand mesjien wils vervange.",
'fileexists-extension'        => "'n bestand met dezelfde naam bestuit al:<br />
Naam van 't geüploade bestand: <strong><tt>$1</tt></strong><br />
Naam van 't bestaonde bestand: <strong><tt>$2</tt></strong><br />
Lèver 'ne angere naam te keze.",
'fileexists-thumb'            => "<center>'''Bestaonde afbeilding'''</center>",
'fileexists-thumbnail-yes'    => "'t Liek 'n afbeilding van 'n verkleinde grootte te zeen <i>(thumbnail)</i>. Lèver 't bestand <strong><tt>$1</tt></strong> te controlere.<br />
Es 't gecontroleerde bestand dezelfde afbeilding van oorspronkelike grootte is, is 't neet noodzakelik 'ne extra thumbnail te uploade.",
'file-thumbnail-no'           => "De bestandsnaam begint met <strong><tt>$1</tt></strong>. 't Liek 'n verkleinde afbeelding te zeen <i>(thumbnail)</i>. Esse deze afbeelding in volledige resolutie höbs, upload dae afbeelding dan. Wiezig anges estebleef de bestandsnaam.",
'fileexists-forbidden'        => "d'r Bestuit al 'n bestand met deze naam. Upload dien bestand onger 'ne angere naam.
[[Image:$1|thumb|center|$1]]",
'fileexists-shared-forbidden' => "d'r Bestuit al 'n bestand met deze naam bie de gedeilde bestenj. Upload 't bestand onger  'ne angere naam. [[Image:$1|thumb|center|$1]]",
'successfulupload'            => 'De upload is geluk',
'uploadwarning'               => 'Upload waarsjuwing',
'savefile'                    => 'Bestand opsjlaon',
'uploadedimage'               => 'haet ge-upload: [[$1]]',
'overwroteimage'              => 'haet \'ne nuuje versie van "[[$1]]" toegevoeg',
'uploaddisabled'              => 'Uploade is oetgesjakeld',
'uploaddisabledtext'          => "'t uploade van bestenj is oetgesjakeld op deze wiki.",
'uploadscripted'              => 'Dit bestandj bevat HTML- of scriptcode die foutief door diene browser weergegaeve kinne waere.',
'uploadcorrupt'               => "'t bestand is corrup of haet 'n onzjuste extensie. Controleer 't bestand en upload 't opnuuj.",
'uploadvirus'                 => "'t Bestand bevat 'n virus! Details: $1",
'sourcefilename'              => 'Oorspronkelike bestandsnaam',
'destfilename'                => 'Doeltitel',
'watchthisupload'             => 'Volg dees pazjena',
'filewasdeleted'              => "d'r Is eerder 'n bestandj mit deze naam verwiederd. Raodpleeg 't $1 veurdetse 't opnuuj toevoegs.",
'upload-wasdeleted'           => "'''Waarsjuwing: Doe bös 'n bestand det eerder verwiederd woor aan 't uploade.'''

Lèver zeker te zeen detse gesjik bös om door te gaon met 't uploade van dit bestand.
't verwiederingslogbook van dit bestand kinse hiej zeen:",
'filename-bad-prefix'         => "De naam van 't bestand detse aan 't uploade bös begint met <strong>\"\$1\"</strong>, wat 'ne neet-besjrievende naam is dae meestal automatisch door 'ne digitale camera wörd gegaeve. Kees estebleef 'ne dudelike naam veur dien bestand.",

'upload-proto-error-text' => "Uploads via deze methode vereise URL's die beginne met <code>http://</code> of <code>ftp://</code>.",
'upload-file-error-text'  => "'n intern fuitke vonj plaats wen 'n tiedelik bestand op de server waerde aangemaak. Nöm aub contac op met 'ne systeembeheerder.",
'upload-misc-error'       => 'Onbekinde uploadfout',
'upload-misc-error-text'  => "d'r Is tiedes 't uploade 'ne onbekinde fout opgetraeje. Controleer of de URL correc en besjikbaar is en probeer 't opnuuj. Es 't probleem aanhaojt, nöm dan contac op met 'ne systeembeheerder.",

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Kòs de URL neet bereike',
'upload-curl-error6-text'  => 'De opgegaeve URL is neet bereikbaar. Controleer estebleef of de URL zjus is, en of de website besjikbaar is.',
'upload-curl-error28'      => 'time-out dèr upload',
'upload-curl-error28-text' => "'t duurde te lank veurdet de website antjwaorde. Controleer aub of de website besjikbaar is, wach ef en probeer 't dan opnuuj. Doe kins 't misschien probere es 't minder druk is.",

'license'            => 'Lisènsie',
'nolicense'          => "Maak 'ne keuze",
'license-nopreview'  => '(Veurvertuun neet besjikbaar)',
'upload_source_url'  => " ('ne geldige, publiek toegankelike URL)",
'upload_source_file' => " ('n bestand op diene computer)",

# Image list
'imagelist'                 => 'Lies van aafbeildinge',
'imagelisttext'             => "Hie volgt 'n lies mit $1 afbeildinge geordend $2.",
'getimagelist'              => 'Lies van aafbeildinge ophaole',
'ilsubmit'                  => 'Zeuk',
'showlast'                  => 'Toen de litste $1 aafbeildinge geordend $2.',
'byname'                    => 'op naom',
'bysize'                    => 'op gruutde',
'imgdelete'                 => 'wis',
'imgdesc'                   => 'besj',
'imgfile'                   => 'bestandj',
'filehist'                  => 'Bestandsgesjiedenis',
'filehist-help'             => "Klik op 'n(e) datum/tied om 't bestandj te zeen wie 't dend'rtieje woor.",
'filehist-deleteall'        => 'wis alles',
'filehist-deleteone'        => 'wis dit/deze',
'filehist-revert'           => 'trökdrèjje',
'filehist-current'          => 'hujig',
'filehist-datetime'         => 'Datum/tiejd',
'filehist-user'             => 'Gebroeker',
'filehist-dimensions'       => 'Aafmaetinge',
'filehist-filesize'         => 'Bestandjsgrootte',
'filehist-comment'          => 'Opmèrking',
'imagelinks'                => 'Aafbeildingsverwiezinge',
'linkstoimage'              => "Dees aafbeilding weurt op de volgende pazjena's gebroek:",
'nolinkstoimage'            => 'Gein enkele pazjena gebroek dees aafbeilding.',
'sharedupload'              => 'literal translation',
'shareduploadwiki'          => 'Zee $1 veur meer informatie.',
'shareduploadwiki-linktext' => 'bestandsbesjrieving',
'noimage'                   => "d'r bestuit gein bestand met deze naam. Doe kins 't $1.",
'noimage-linktext'          => 'uploade',
'uploadnewversion-linktext' => "Upload 'n nuuje versie van dit bestand",
'imagelist_name'            => 'Naom',
'imagelist_user'            => 'Gebroeker',
'imagelist_size'            => 'Gruutde (bytes)',
'imagelist_description'     => 'Besjrieving',
'imagelist_search_for'      => 'Zeuk nao bestand:',

# File reversion
'filerevert'                => '$1 trökdrèjje',
'filerevert-intro'          => '<span class="plainlinks">Doe bös \'\'\'[[Media:$1|$1]]\'\'\' aan \'t trökdrèjje tot de [$4 versie op $2, $3]</span>.',
'filerevert-comment'        => 'Opmèrking:',
'filerevert-defaultcomment' => 'Trökgedrèjt tot de versie op $1, $2',
'filerevert-submit'         => 'Trökdrèjje',
'filerevert-success'        => '<span class="plainlinks">\'\'\'[[Media:$1|$1]]\'\'\' is trökgedrèjt tot de [$4 versie op $2, $3]</span>.',
'filerevert-badversion'     => "d'r is geine vörge lokale versie van dit bestand mit 't opgegaeve tiejdstip.",

# File deletion
'filedelete'             => 'Wis $1',
'filedelete-legend'      => 'Wis bestand',
'filedelete-intro'       => "Doe bös '''[[Media:$1|$1]]''' aan 't wisse.",
'filedelete-intro-old'   => '<span class="plainlinks">Doe bös de versie van \'\'\'[[Media:$1|$1]]\'\'\' van [$4 $3, $2] aan \'t wisse.</span>',
'filedelete-comment'     => 'Opmèrking:',
'filedelete-submit'      => 'Wisse',
'filedelete-success'     => "'''$1''' is gewis.",
'filedelete-success-old' => '<span class="plainlinks">De versie van \'\'\'[[Media:$1|$1]]\'\'\' van $3, $2 is gewis.</span>',
'filedelete-nofile'      => "'''$1''' bestuit neet op deze site.",
'filedelete-nofile-old'  => "d'r is geine versie van '''$1''' in 't archief met de aangegaeve eigensjappe.",
'filedelete-iscurrent'   => "Doe probeers de nuujste versie van dit bestand te wisse. Plaats aub 'n aajere versie truuk.",

# MIME search
'mimesearch'         => 'Zeuk op MIME-type',
'mimesearch-summary' => "Deze pagina maak het filtere van bestenj veur 't MIME-type meugelik. Inveur: contenttype/subtype, bv <tt>image/jpeg</tt>.",
'download'           => 'Downloade',

# Unwatched pages
'unwatchedpages' => 'Neet-gevolgde pazjenas',

# List redirects
'listredirects' => 'Lies van redirects',

# Unused templates
'unusedtemplates'     => 'Óngerbroekde sjablone',
'unusedtemplatestext' => 'Deze pagina guf alle pagina\'s weer in de naamruumde sjabloon die op gein inkele pagina gebroek waere. Vergaet neet de "Links nao deze pagina" te controlere veures dit sjabloon te wösse.',
'unusedtemplateswlh'  => 'anger links',

# Random page
'randompage'         => 'Willekäörige pazjena',
'randompage-nopages' => "d'r zeen gein pagina's in deze naamruumde.",

# Random redirect
'randomredirect'         => 'Willekäörige redirect',
'randomredirect-nopages' => "d'r zeen gein redirects in deze naamruumde.",

# Statistics
'statistics'             => 'Sjtattestieke',
'sitestats'              => 'Sjtatistieke euver {{SITENAME}}',
'userstats'              => 'Stattestieke euver gebroekers',
'sitestatstext'          => "D'r zunt in totaal '''\$1''' pazjena's in de database.
Dit is inclusief \"euverlik\"-pazjena's, pazjena's euver {{SITENAME}}, extreem korte \"sjtumpkes\", redirects, en anger pazjena's die waarsjienlik neet as inhoud mote waere getèld. 't Aantal pazjena's mit content weurt gesjat op '''\$2'''.

D'r zunt '''\$8''' besjtande opgelaje.

D'r is in totaal '''\$3''' kier 'n pazjena bekeke en '''\$4''' kier 'n pazjena bewirk sins de wiki is opgezat. Dat geuf e gemiddelde van '''\$5''' bewirkinge per pazjena en '''\$6''' getuinde pazjena's per bewirking.

De lengde van de [http://meta.wikimedia.org/wiki/Help:Job_queue job queue] is '''\$7'''.",
'userstatstext'          => "D'r zeen '''$1''' geregistreerde gebroekers; '''$2''' (of '''$4''') hievan zeen systeemwèrkers (zuug $3).",
'statistics-mostpopular' => 'Meisbekeke pazjenas',

'disambiguations'      => "Verdudelikingspazjena's",
'disambiguationspage'  => 'Template:Verdudeliking',
'disambiguations-text' => "Hiej onger staon pagina's die verwieze nao 'ne '''redirect'''. Deze heure waarsjienlik direct nao 't zjuste ongerwerp te verwiezen. <br />'ne pagina wörd gezeen es redirect wen d'r 'n sjabloon op stuit det gelink is vanaaf [[MediaWiki:disambiguationspage]]",

'doubleredirects'     => 'Dobbel redirects',
'doubleredirectstext' => '<b>Kiek oet:</b> In dees lies kanne redirects sjtaon die neet dao-in toeshure. Dat kump meistal doordat nao de #REDIRECT nog anger links op de pazjena sjtaon.<br />
Op eder raegel vings te de ierste redirectpazjena, de twiede redirectpazjena en de iesjte raegel van de twiede redirectpazjena. Normaal bevat dees litste de pazjena woe de iesjte redirect naotoe zouw mótte verwieze.',

'brokenredirects'        => 'Gebraoke redirects',
'brokenredirectstext'    => "De óngersjtaonde redirectpazjena's bevatte 'n redirect nao 'n neet-besjtaonde pazjena.",
'brokenredirects-edit'   => '(bewerke)',
'brokenredirects-delete' => '(wisse)',

'withoutinterwiki'        => 'Interwikiloze pazjenas',
'withoutinterwiki-header' => "De volgende pagina's linke neet nao versies in 'n anger taal:",

'fewestrevisions' => 'Artikele met de minste bewerkinge',

# Miscellaneous special pages
'nbytes'                  => '$1 bytes',
'ncategories'             => '$1 categorië',
'nlinks'                  => '$1 verwiezinge',
'nmembers'                => '$1 {{PLURAL:$1|leed|lèjjer}}',
'nrevisions'              => '$1 herzeninge',
'nviews'                  => '$1 kier bekeke',
'specialpage-empty'       => 'Deze pagina is laeg.',
'lonelypages'             => "Weispazjena's",
'lonelypagestext'         => "Nao de ongerstäönde pagina's wörd vanoet deze wiki neet verweze.",
'uncategorizedpages'      => "Ongekattegoriseerde pazjena's",
'uncategorizedcategories' => 'Ongekattegoriseerde kattegorië',
'uncategorizedimages'     => 'Óngecategorizeerde aafbeeldinge',
'uncategorizedtemplates'  => 'Óngecategorizeerde sjablone',
'unusedcategories'        => 'Óngebroekde kategorieë',
'unusedimages'            => 'Ongebroekde aafbeildinge',
'popularpages'            => 'Populaire artikels',
'wantedcategories'        => 'Gewunsjde categorieë',
'wantedpages'             => "Gewunsjde pazjena's",
'mostlinked'              => "Meis gelinkde pazjena's",
'mostlinkedcategories'    => 'Meis-gelinkde categorië',
'mostlinkedtemplates'     => 'Meis-gebroekde sjablone',
'mostcategories'          => 'Artikele mit de meiste kategorieë',
'mostimages'              => 'Meis gelinkde aafbeildinge',
'mostrevisions'           => 'Artikele mit de meiste bewirkinge',
'allpages'                => "Alle pazjena's",
'prefixindex'             => 'Indèks dèr veurveugsele',
'shortpages'              => 'Korte artikele',
'longpages'               => 'Lang artikele',
'deadendpages'            => "Doedloupende pazjena's",
'deadendpagestext'        => "De ongerstäönde pagina's verwieze neet nao anger pagina's in deze wiki.",
'protectedpages'          => "Besjörmde pagina's",
'protectedpagestext'      => "De volgende pagina's zeen beveilig en kinne neet bewerk en/of hernömp waere",
'protectedpagesempty'     => "d'r Zeen noe gein pagina's besjörmp die aan deze paramaetere voldaon.",
'listusers'               => 'Lies van gebroekers',
'specialpages'            => "Speciaal pazjena's",
'spheading'               => "Speciaal pazjena's",
'restrictedpheading'      => "Speciale pagina's met bepèrkte toegank",
'newpages'                => "Nuuj pazjena's",
'newpages-username'       => 'Gebroekersnaam:',
'ancientpages'            => 'Artikele die lank neet bewèrk zeen',
'intl'                    => 'Intertäöllinke',
'move'                    => 'Verplaats',
'movethispage'            => 'Verplaats dees pazjena',
'unusedimagestext'        => "<p>Lèt op! 't Zou kinne dat er via een directe link verweze weurt nao 'n aafbeilding, bevoorbild vanoet 'n angesjtalige {{SITENAME}}. Het is daorom meugelijk dat 'n aafbeilding hie vermeld sjtit terwiel e toch gebroek weurt.",
'unusedcategoriestext'    => 'Hiej onger staon categorië die aangemaak zeen, mèr door geine inkele pagina of angere categorie gebroek waere.',
'notargettitle'           => 'Gein doelpagina',
'notargettext'            => 'Ger hubt neet gezag veur welleke pagina ger deze functie wilt bekieke.',

# Book sources
'booksources'               => 'Bookwinkele',
'booksources-search-legend' => "Zeuk informatie euver 'n book",
'booksources-go'            => 'Zeuk',
'booksources-text'          => "Hiej onger stuit 'n lies met koppelinge nao anger websites die nuuje of gebroekde beuk verkoupe, en die wellich meer informatie euver 't book detse zeuks höbbe:",

'categoriespagetext' => 'De wiki haet de volgende categorieë:',
'data'               => 'Gegaeves',
'userrights'         => 'Gebroekersrechtebeheer',
'groups'             => 'Gebroekersgruup',
'alphaindexline'     => '$1 nao $2',
'version'            => 'Versie',

# Special:Log
'specialloguserlabel'  => 'Gebroeker:',
'speciallogtitlelabel' => 'Titel:',
'log'                  => 'Logbeuk',
'all-logs-page'        => 'Alle logbeuk',
'log-search-legend'    => 'Zeuk logbeuk',
'log-search-submit'    => "Zeuk d'rs door",
'alllogstext'          => "Dit is 't gecombineerd logbook. De kins ouch 'n bepaald logbook keze, of filtere op gebroekersnaam of  pazjena.",
'logempty'             => "d'r Zeen gein regels in 't logbook die voldaon aan deze criteria.",
'log-title-wildcard'   => "Zeuk pagina's die met deze naam beginne",

# Special:Allpages
'nextpage'          => 'Volgende pazjena ($1)',
'prevpage'          => 'Vörge pazjena ($1)',
'allpagesfrom'      => "Tuin pazjena's vanaaf:",
'allarticles'       => 'Alle artikele',
'allinnamespace'    => "Alle pazjena's (naamruumde $1)",
'allnotinnamespace' => "Alle pazjena's (neet in naamruumde $1)",
'allpagesprev'      => 'Veurige',
'allpagesnext'      => 'Irsvolgende',
'allpagessubmit'    => 'Gao',
'allpagesprefix'    => "Tuin pazjena's mèt 't veurvoogsel:",
'allpagesbadtitle'  => "De opgegaeve paginanaam is ongeldig of haj 'n intertaal of interwiki veurvoegsel. Meugelik bevatte de naam karakters die neet gebroek moge waere in paginanäöm.",
'allpages-bad-ns'   => '{{SITENAME}} haet gein naamruumde mit de naam "$1".',

# Special:Listusers
'listusersfrom'      => 'Tuun gebroekers vanaaf:',
'listusers-submit'   => 'Tuun',
'listusers-noresult' => 'Gein(e) gebroeker(s) gevonje.',

# E-mail user
'mailnologin'     => 'Gein e-mailadres bekènd veur deze gebroeker',
'mailnologintext' => "De mos zien [[Special:Userlogin|aangemèld]] en 'n gèldig e-mailadres in bie dien [[Special:Preferences|veurkäöre]] höbbe ingevuld om mail nao anger gebroekers te sjture.",
'emailuser'       => "Sjik deze gebroeker 'nen e-mail",
'emailpage'       => "Sjik gebroeker 'nen e-mail",
'emailpagetext'   => "As deze gebroeker e geljig e-mailadres heet opgegaeve dan kant geer via dit formuleer e berich sjikke. 't E-mailadres wat geer heet opgegeve bie eur veurkäöre zal as versjikker aangegaeve waere.",
'usermailererror' => "Foutmeljing biej 't zenje:",
'noemailtitle'    => 'Gein e-mailadres bekènd veur deze gebroeker',
'noemailtext'     => 'Deze gebroeker haet gein gèldig e-mailadres opgegaeve of haet dees functie oetgesjakeld.',
'emailsubject'    => 'Óngerwerp',
'emailmessage'    => 'Berich',
'emailsend'       => 'Sjik berich',
'emailccme'       => "Stuur 'n kopie van dit berich nao mien e-mailadres.",
'emailccsubject'  => 'Kopie van dien berich aan $1: $2',
'emailsent'       => 'E-mail sjikke',
'emailsenttext'   => 'Die berich is versjik.',

# Watchlist
'watchlist'            => 'Volglies',
'mywatchlist'          => 'Volglies',
'watchlistfor'         => "(veur '''$1''')",
'nowatchlist'          => "D'r sjtit niks op dien volglies.",
'watchlistanontext'    => '$1 is verplich om dien volglies in te zeen of te wiezige.',
'watchnologin'         => 'De bis neet aangemèld',
'watchnologintext'     => "De mós [[Special:Userlogin|aangemèld]] zeen veur 't verangere van dien volglies.",
'addedwatch'           => 'Aan volglies toegeveug',
'addedwatchtext'       => 'De pazjena "$1" is aan dien [[Special:Watchlist|volglies]] toegeveug.
Toekomstige verangeringe aan deze pazjena en de biebehurende euverlikpazjena weure hie vermèld. 
Ouch versjiene gevolgde pazjena\'s in \'t <b>vet</b> in de [[Special:Recentchanges|liest van recènte verangeringe]]. <!-- zodat u ze eenvoudiger kan opmerken.-->

<!-- huh? Wen se ein pazjena van dien volgliest wils haole mos e op "sjtop volge"  -- pagina wenst te verwijderen van uw volgliest klik dan op "Van volgliest verwijderen" in de menubalk. -->',
'removedwatch'         => 'Van volglies aafhoale',
'removedwatchtext'     => 'De pazjena "$1" is van dien volglies aafgehaold.',
'watch'                => 'Volg',
'watchthispage'        => 'Volg dees pazjena',
'unwatch'              => 'Sjtop volge',
'unwatchthispage'      => 'Neet mië volge',
'notanarticle'         => 'Is gein artikel',
'watchnochange'        => 'Gein van dien gevolgde items is aangepas in dees periode.',
'watchlist-details'    => "Dao sjtaon $1 pazjena's op dien volglies mèt oetzunjering van de euverlikpazjena's.",
'wlheader-enotif'      => '* Doe wörs per e-mail gewaarsjuwd',
'wlheader-showupdated' => "* Pazjena's die verangerd zeen saers doe ze veur 't lètste bezaogs sjtaon '''vet'''",
'watchmethod-recent'   => "Controleer recènte verangere veur gevolgde pazjena's",
'watchmethod-list'     => "controlere van gevolgde pazjena's veur recènte verangeringe",
'watchlistcontains'    => "Dien volglies bevat $1 pazjena's.",
'iteminvalidname'      => "Probleem mit object '$1', ongeljige naam...",
'wlnote'               => 'Hieonger de lètste $1 verangeringe van de lètste <b>$2</b> oor.',
'wlshowlast'           => 'Tuin lètste $1 ore $2 daag $3',
'watchlist-show-bots'  => 'Tuun bots',
'watchlist-hide-bots'  => 'Verberg bots',
'watchlist-show-own'   => 'Tuun mien bewerkinge',
'watchlist-hide-own'   => 'Verberg mien bewerkinge',
'watchlist-show-minor' => 'Tuun kleine bewerkinge',
'watchlist-hide-minor' => 'Verberg kleine bewerkinge',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Aant volge...',
'unwatching' => 'Oet de volglies aant zètte...',

'enotif_mailer'                => '{{SITENAME}} notificatiemail',
'enotif_reset'                 => "Mèrk alle bezochde pazjena's aan.",
'enotif_newpagetext'           => "DIt is 'n nuuj pazjena.",
'enotif_impersonal_salutation' => '{{SITENAME}} gebroeker',
'changed'                      => 'verangerd',
'created'                      => 'aangemaak',
'enotif_subject'               => 'De {{SITENAME}}pazjena $PAGETITLE is $CHANGEDORCREATED door $PAGEEDITOR',
'enotif_lastvisited'           => 'Zuug $1 veur al verangeringe saer dien lèste bezeuk.',
'enotif_lastdiff'              => 'Zuug $1 om deze wieziging te zeen.',
'enotif_anon_editor'           => 'anonieme gebroeker $1',
'enotif_body'                  => 'Bèste $WATCHINGUSERNAME,

De {{SITENAME}}-pazjena "$PAGETITLE" is $CHANGEDORCREATED op $PAGEEDITDATE door $PAGEEDITOR, zuug $PAGETITLE_URL veur de hujige versie.

$NEWPAGE

Bewirkingssamevatting: $PAGESUMMARY $PAGEMINOREDIT

Contacteer de bewirker:
mail: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Dao zalle bie volgende verangeringe gein nuuj berichte kómme tenzies te dees pazjena obbenuujts bezeuks. De kans ouch de notificatievlegskes op dien volglies verzètte.

             \'t {{SITENAME}}-notificatiesysteem

--
Óm de insjtèllinge van dien volglies te verangere, zuug
{{fullurl:Special:Watchlist/edit}}

Commentaar en wiejer assistentie:
{{fullurl:Help:Contents}}',

# Delete/protect/revert
'deletepage'                  => 'Pazjena wisse',
'confirm'                     => 'Bevèstig',
'excontent'                   => "inhawd waor: '$1'",
'excontentauthor'             => "inhawd waor: '$1' (aangemaak door [[Special:Contributions/$2|$2]])",
'exbeforeblank'               => "inhawd veur 't wisse waor: '$1'",
'exblank'                     => 'pazjena waor laeg',
'confirmdelete'               => 'Bevèstig wisse',
'deletesub'                   => '(Wisse "$1")',
'historywarning'              => 'Waorsjuwing: de pazjena daese wils wisse haet meerdere versies:',
'confirmdeletetext'           => "De sjteis op 't punt 'n pazjena of e plaetje veur ummer te wisse. Dit haolt allen inhawd en historie oet de database eweg. Bevèstig hieónger dat dit welzeker dien bedoeling is, dats te de gevolge begrieps.",
'actioncomplete'              => 'Actie voltoeid',
'deletedtext'                 => '"$1" is gewis. Zuug $2 vuur \'n euverzich van recèntelik gewisde pazjena\'s.',
'deletedarticle'              => '"$1" is gewis',
'dellogpage'                  => 'Wislogbook',
'dellogpagetext'              => "Hie volg 'n lies van de meis recèntelik gewisde pazjena's en plaetjes.",
'deletionlog'                 => 'Wislogbook',
'reverted'                    => 'Iedere versie hersjtèld',
'deletecomment'               => 'Rae veur wisactie',
'rollback'                    => 'Verangering ongedaon gemaak',
'rollback_short'              => 'Trökdrèjje',
'rollbacklink'                => 'Trukdrieje',
'rollbackfailed'              => 'Ongedaon make van wieziginge mislùk.',
'cantrollback'                => 'Trökdrejje van verangeringe neet meugelik: Dit artikel haet mer einen auteur.',
'alreadyrolled'               => "'t Is neet meugelik óm de lèste verangering van [[$1]]
door [[User:$2|$2]] ([[User talk:$2|euverlik]]) óngedaon te make. Emes angers haet de pazjena al hersjtèld of haet 'n anger bewèrking gedaon. 

De lèste bewèrking is gedaon door [[User:$3|$3]] ([[User talk:$3|euverlik]]).",
'editcomment'                 => '\'t Bewirkingscommentair waor: "<i>$1</i>".', # only shown if there is an edit comment
'revertpage'                  => 'Wieziginge door [[Special:Contributions/$2|$2]] ([[User_talk:$2|Euverlik]]) trukgedriejd tot de lètste versie door [[User:$1|$1]]',
'rollback-success'            => 'Wieziginge door $1 trökgedrèjd; letste versie van $2 hersteld.',
'sessionfailure'              => "d'r Liek 'n probleem te zeen mit dien aanmelsessie. Diene hanjeling is gestop oet veurzorg taenge 'n beveiligingsrisico (det bestuit oet meugelik \"hijacking\"(euverkape) van deze sessie). Gao 'n pazjena trök, laaj die pazjena opnuuj en probeer 't nog ins.",
'protectlogpage'              => "Logbook besjermde pazjena's",
'protectlogtext'              => "Hiej onger staon pazjena's die recèntelik beveilig zeen, of wo van de beveiliging is opgeheve. Zuug de [[{{ns:special}}:Protectedpages|lies mit beveiligde pazjena's]] veur alle hujige beveiligde pazjena's.",
'protectedarticle'            => '$1 besjermd',
'modifiedarticleprotection'   => 'verangerde beveiligingsniveau van "[[$1]]"',
'unprotectedarticle'          => 'besjerming van $1 opgeheve',
'protectsub'                  => '(Besjerme van "$1")',
'confirmprotect'              => 'Bevèstig besjerme',
'protectcomment'              => 'Rede veur besjerming',
'protectexpiry'               => 'Verlöp:',
'protect_expiry_invalid'      => "De pazjena's aangegaeve verloup is ongeldig.",
'protect_expiry_old'          => "De pazjena verlöp in 't verleje.",
'unprotectsub'                => '(Besjerming van "$1" opheve)',
'protect-unchain'             => 'Maak verplaatse meugelik',
'protect-text'                => "Hiej kinse 't beveiligingsniveau veur de pazjena <strong>$1</strong> bekieke en wiezige.",
'protect-locked-blocked'      => "Doe kins 't beveiligingsniveau neet wiezige wielse geblokkeerd bös.
Hiej zeen de hujige instellinge veur de pazjena <strong>[[$1]]</strong>:",
'protect-locked-dblock'       => "'t Beveiligingsniveau kin neet waere gewiezig ómdet de database geslaote is.
Hiej zeen de hujige instellinge veur de pazjena <strong>[[$1]]</strong>:",
'protect-locked-access'       => "'''Diene gebroeker haet gein rechte om 't beveiligingsniveau te wiezige.'''
Dit zeen de hujige instellinge veur de pazjena <strong>[[$1]]</strong>:",
'protect-cascadeon'           => "Deze pazjena is beveilig ómdet d'r in de volgende {{PLURAL:$1|pazjena|pazjena's}} is opgenaome, {{PLURAL:$1|dae|die}} beveilig {{PLURAL:$1|is|zeen}} mit de kaskaad-opsie. 't Beveiligingsniveau wiezige haet gein inkel effèk.",
'protect-default'             => '(sjtandaard)',
'protect-fallback'            => 'Rech "$1" is neudig',
'protect-level-autoconfirmed' => 'Allein geregistreerde gebroekers',
'protect-level-sysop'         => 'Allein beheerders',
'protect-summary-cascade'     => 'kaskaad',
'protect-expiring'            => 'verlöp op $1',
'protect-cascade'             => "Kaskaadbeveiliging - beveilig alle pazjena's en sjablone die in deze pazjena opgenaome zeen (let op; dit kin grote gevolge höbbe).",
'restriction-type'            => 'Rech:',
'restriction-level'           => 'Bepèrkingsniveau:',
'minimum-size'                => 'Min. gruutde',
'maximum-size'                => 'Max. gruutde',

# Restrictions (nouns)
'restriction-edit' => 'Bewèrke',
'restriction-move' => 'Verplaatse',

# Restriction levels
'restriction-level-sysop'         => 'volledig beveilig',
'restriction-level-autoconfirmed' => 'semibeveilig',
'restriction-level-all'           => 'eder niveau',

# Undelete
'undelete'                     => 'Verwiederde pazjena trukplaatse',
'undeletepage'                 => "Verwiederde pazjena's bekieke en trukplaatse",
'viewdeletedpage'              => "Tuun gewisde pazjena's",
'undeletepagetext'             => "De ongersjtaande pazjena's zint verwiederd, meh bevinge zich nog sjteeds in 't archief, en kinne weure truukgeplaatsj.",
'undeleteextrahelp'            => "Om de algehele pazjena inclusief alle irdere versies trök te plaatse: laot alle hökskes onafgevink en klik op '''''Trökplaatse'''''. Om slechs bepaalde versies trök te zètte: vink de trök te plaatse versies aan en klik op '''''Trökplaatse'''''. Esse op '''''Reset''''' kliks wörd 't toelichtingsveld laeggemaak en waere alle versies gedeselecteerd.",
'undeleterevisions'            => "$1 versies in 't archief",
'undeletehistory'              => 'Als u een pagina terugplaatst, worden alle versies als oude versies teruggeplaatst. Als er al een nieuwe pagina met dezelfde naam is aangemaakt, zullen deze versies als oude versies worden teruggeplaatst, maar de huidige versie neet gewijzigd worden.',
'undeleterevdel'               => 'Herstelle is neet meugelik es dao door de meis recènte versie van de pazjena gedeiltelik vewiederd wörd. Wis in zulke gevalle de meis recènt gewisde versies oet de selectie. Versies van bestenj waose gein toegang toe höbs waere neet hersteld.',
'undeletehistorynoadmin'       => 'Deze pazjena is gewis. De reje hiej veur stuit hiej onger, same mit de details van de gebroekers die deze pazjena höbbe bewerk véur de verwiedering. De verwiederde inhoud van de pazjena is allein zichbaar veur beheerders.',
'undelete-revision'            => 'Verwiederde versie van $1 (per $2) door $3:',
'undeleterevision-missing'     => "Ongeldige of missende versie. Meugelik höbse 'n verkeerde verwiezing of is de versie hersteld of verwiederd oet 't archief.",
'undeletebtn'                  => 'Trökzètte!',
'undeletereset'                => 'Resette',
'undeletecomment'              => 'Infermasie:',
'undeletedarticle'             => '"$1" is truukgeplaatsj.',
'undeletedrevisions'           => '$1 versies truukgeplaatsj',
'undeletedrevisions-files'     => '$1 versies en $2 bestandj/bestenj trökgeplaats',
'undeletedfiles'               => '$1 bestandj/bestenj trökgeplaats',
'cannotundelete'               => "Verwiedere mislùk. Mesjien haet 'ne angere gebroeker de pazjena al verwiederd.",
'undeletedpage'                => "<big>'''$1 is trökgeplaats'''</big>

In 't [[{{ns:special}}:Log/delete|logbook verwiederde pazjena's]] staon recènte verwiederinge en herstelhanjelinge.",
'undelete-header'              => "Zuug [[{{ns:special}}:Log/delete|'t logbook verwiederde pazjena's]] veur recènt verwiederde pazjena's.",
'undelete-search-box'          => "Doorzeuk verwiederde pazjena's",
'undelete-search-prefix'       => "Tuun pazjena's die beginne mit:",
'undelete-search-submit'       => 'Zeuk',
'undelete-no-results'          => "Gein pazjena's gevonje in 't archief mit verwiederde pazjena's.",
'undelete-filename-mismatch'   => 'Bestandsversie van tiedstip $1 kos neet hersteld waere: bestandsnaam klopte neet',
'undelete-bad-store-key'       => "Bestandsversie van tiedstip $1 kos neet hersteld waere: 't bestand miste al veurdet 't waerde verwiederd.",
'undelete-cleanup-error'       => 'Fout bie \'t herstelle van ongebroek archiefbestand "$1".',
'undelete-missing-filearchive' => "'t Luk neet om ID $1 trök te plaatse omdet 't neet in de database is. Mesjien is 't al trökgeplaats.",
'undelete-error-short'         => "Fout bie 't herstelle van bestand: $1",
'undelete-error-long'          => "d'r Zeen foute opgetraeje bie 't herstelle van 't bestand:

$1",

# Namespace form on various pages
'namespace'      => 'Naamruumde:',
'invert'         => 'Ómgedriejde selectie',
'blanknamespace' => '(huidnaamruumde)',

# Contributions
'contributions' => 'Biedrages per gebroeker',
'mycontris'     => 'Mien biedraag',
'contribsub2'   => 'Veur $1 ($2)',
'nocontribs'    => 'Gein wijzigingen gevonden die aan de gestelde criteria voldoen.',
'ucnote'        => 'Hieonger sjtont de litste <b>$1</b> verangeringe van deze gebroeker in de litste <b>$2</b> daag.',
'uclinks'       => 'Bekiek de litste <b>$1</b> verangeringe; bekiek de litste <b>$2</b> daag.',
'uctop'         => ' (litste verangering)',
'month'         => 'Van maondj (en irder):',
'year'          => 'Van jaor (en irder):',

'sp-contributions-newbies'     => 'Tuun allein de bijdrages van nuuje gebroekers',
'sp-contributions-newbies-sub' => 'Veur nuujelinge',
'sp-contributions-blocklog'    => 'Blokkeerlogbook',
'sp-contributions-search'      => 'Zeuke nao biedrages',
'sp-contributions-username'    => 'IP-adres of gebroekersnaam:',
'sp-contributions-submit'      => 'Zeuk/tuun',

'sp-newimages-showfrom' => 'Tuun nuuje afbeildinge vanaaf $1',

# What links here
'whatlinkshere'       => 'Links nao dees pazjena',
'whatlinkshere-title' => "Pazjena's die verwieze nao $1",
'linklistsub'         => '(lies van verwiezinge)',
'linkshere'           => "De volgende pazjena's verwieze hieheen:",
'nolinkshere'         => "D'r zint gein pazjena's mit links hiehaer.",
'nolinkshere-ns'      => "Geine inkele pazjena link nao '''[[:$1]]''' in de gekaoze naamruumde.",
'isredirect'          => 'redirect pazjena',
'istemplate'          => 'ingevoeg es sjabloon',
'whatlinkshere-prev'  => '{{PLURAL:$1|vörge|vörge $1}}',

# Block/unblock
'blockip'                     => 'Blokkeer dit IP-adres',
'blockiptext'                 => "Gebroek 't óngerstjaondj formeleer óm sjrieftoegank van e zeker IP-adres te verbeje. Dit maag allein gedaon weure om vandalisme te veurkómme.",
'ipadressorusername'          => 'IP-adres of gebroekersnaam',
'ipbexpiry'                   => "Verlöp (maak 'n keuze)",
'ipbreason'                   => 'Reje',
'ipbreasonotherlist'          => 'Angere reje',
'ipbreason-dropdown'          => '*Väöl veurkommende rejer veur blokkaazjes
** Foutieve informatie inveure
** Verwiedere van informatie oet artikele
** Spamlinks nao externe websites
** Inveuge van nonsens in artikele
** Intimiderend gedraag
** Misbroek van meerdere gebroekers
** Onacceptabele gebroekersnaam',
'ipbanononly'                 => 'Blokkeer allein anonieme gebroekers',
'ipbcreateaccount'            => 'Blokkeer aanmake gebroekers',
'ipbemailban'                 => "Haoj de gebrorker van 't sture van e-mail",
'ipbenableautoblock'          => 'Automatisch de IP-adresse van deze gebroeker blokkere',
'ipbsubmit'                   => 'Blokkeer dit IP-adres',
'ipbother'                    => 'Anger verloup',
'ipboptions'                  => '2 oer:2 hours,1 daag:1 day,3 daag:3 days,1 waek:1 week,2 waek:2 weeks,1 maondj:1 month,3 maondj:3 months,6 maondj:6 months,1 jaor:1 year,veur iwweg:infinite', # display1:time1,display2:time2,...
'ipbotheroption'              => 'anger verloup',
'ipbotherreason'              => 'Angere/eventuele rejer:',
'ipbhidename'                 => "Verberg gebroekersnaam/IP van 't blokkeerlogbook, de actieve blokkeerlies en de gebroekerslies",
'badipaddress'                => "'t IP-adres haet 'n ongeldige opmaak.",
'blockipsuccesssub'           => 'Blokkaad gelök',
'blockipsuccesstext'          => '\'t IP-adres "$1" is geblokkeerd.<br />
Zuug de [[Special:Ipblocklist|lies van geblokkeerde IP-adresse]].',
'ipb-edit-dropdown'           => 'Bewerk lies van rejer',
'ipb-unblock-addr'            => 'Ónblokkeer $1',
'ipb-unblock'                 => "Ónblokkeer 'ne gebroeker of IP-adres",
'ipb-blocklist-addr'          => 'Bekiek bestaonde blokkades veur $1',
'ipb-blocklist'               => 'Bekiek bestaonde blokkades',
'unblockip'                   => 'Deblokkeer IP adres',
'unblockiptext'               => 'Gebroek het ongersjtaonde formeleer om weer sjrieftoegang te gaeve aan e geblokkierd IP adres.',
'ipusubmit'                   => 'Deblokkeer dit IP-adres.',
'unblocked'                   => 'Blokkade van [[User:$1|$1]] is opgeheve',
'unblocked-id'                => 'Blokkade $1 is opgeheve',
'ipblocklist'                 => 'Lies van geblokkeerde IP-adressen',
'ipblocklist-legend'          => "'ne Geblokkeerde gebroeker zeuke",
'ipblocklist-username'        => 'Gebroekersnaam of IP-adres:',
'ipblocklist-submit'          => 'Zeuk',
'blocklistline'               => 'Op $1 blokkeerde $2 $3 ($4)',
'infiniteblock'               => 'veur iwweg',
'expiringblock'               => 'verlöp op $1',
'anononlyblock'               => 'allein anoniem',
'noautoblockblock'            => 'autoblok neet actief',
'createaccountblock'          => 'aanmake gebroekers geblokkeerd',
'ipblocklist-empty'           => 'De blokkeerlies is laeg.',
'ipblocklist-no-results'      => 'Dit IP-adres of deze gebroekersnaam is neet geblokkeerd.',
'blocklink'                   => 'Blokkeer',
'contribslink'                => 'biedrages',
'autoblocker'                 => 'Ómdets te \'n IP-adres deils mit "$1" (geblokkeerd mit raeje "$2") bis te automatisch geblokkeerd.',
'blocklogpage'                => 'Blokkeerlogbook',
'blocklogentry'               => '"[[$1]]" is geblokkeerd veur d\'n tied van $2 $3',
'blocklogtext'                => "Dit is 'n log van blokkades van gebroekers. Automatisch geblokkeerde IP-adresse sjtoon hie neet bie. Zuug de [[Special:Ipblocklist|Lies van geblokkeerde IP-adresse]] veur de lies van op dit mement wèrkende blokkades.",
'unblocklogentry'             => 'blokkade van $1 opgeheve',
'block-log-flags-anononly'    => 'allein anoniem',
'block-log-flags-nocreate'    => 'aanmake gebroekers geblokkeerd',
'block-log-flags-noautoblock' => 'autoblok ongedaon gemaak',
'range_block_disabled'        => "De meugelikheid veur beheerders om 'ne groep IP-adresse te blokkere is oetgesjakeld.",
'ipb_expiry_invalid'          => 'Ongeldig verloup.',
'ipb_cant_unblock'            => 'Fout: Blokkadenummer $1 neet gevonje. Mesjiens is de blokkade al opgeheve.',
'proxyblocker'                => 'Proxyblokker',
'proxyblockreason'            => "Dien IP-adres is geblokkeerd ómdat 't 'n aope proxy is. Contacteer estebleef diene internet service provider of technische óngersjteuning en informeer ze euver dit serjeus veiligheidsprebleem.",
'proxyblocksuccess'           => 'Klaor.',
'sorbsreason'                 => 'Dien IP-adres is opgenaome in de DNS-blacklist es open proxyserver.',
'sorbs_create_account_reason' => 'Dien IP-adres is opgenaome in de DNS-blacklist es open proxyserver. Doe kins geine gebroeker aanmake.',

# Developer tools
'unlockdb'            => 'Deblokkeer de database',
'lockdbtext'          => "Waarsjoewing: De database blokkere haet 't gevolg dat nemes nog pazjena's kint bewirke, veurkäöre kint verangere of get angers kint doon woeveur d'r verangeringe in de database nudig zint.",
'unlockdbtext'        => "Het de-blokkeren van de database zal de gebroekers de mogelijkheid geven om wijzigingen aan pagina's op te slaan, hun voorkeuren te wijzigen en alle andere bewerkingen waarvoor er wijzigingen in de database nodig zijn. Is dit inderdaad wat u wilt doen?.",
'lockconfirm'         => 'Jao, ich wil de database blokkere.',
'unlockconfirm'       => 'Ja, ik wil de database de-blokkeren.',
'unlockbtn'           => 'Deblokkeer de database',
'locknoconfirm'       => "De höbs 't vekske neet aangevink om dien keuze te bevèstige.",
'lockdbsuccesssub'    => 'Blokkering database succesvol',
'unlockdbsuccesssub'  => 'Blokkering van de database opgeheven',
'lockdbsuccesstext'   => "De database van {{SITENAME}} is geblokkeerd. Vergaet neet de database opnuuj te deblokkere wens te klaor bis mit 't óngerhaud.",
'unlockdbsuccesstext' => 'Blokkering van de database van {{SITENAME}} is opgeheven.',

# Move page
'movepage'                => 'Verplaats pazjena',
'movepagetext'            => "Mit 't óngersjtaond formuleer kans te 'n pazjena verplaatse. De historie van de ouw pazjena zal nao de nuuj mitgaon. De ouwe titel zal automatisch 'ne redirect nao de nuuj pazjena waere. Doe kans 'n pazjena allein verplaatse, es gein pazjena besjteit mit de nuje naam, of es op die pazjena allein 'ne redirect zónger historie sjteit.",
'movepagetalktext'        => "De biebehurende euverlikpazjena weurt ouch verplaats, mer '''neet''' in de volgende gevalle:
* es al 'n euverlikpazjena besjteit ónger de angere naam
* es doe 't óngersjtaond vekske neet aanvinks",
'movearticle'             => 'Verplaats pazjena',
'movenologin'             => 'Neet aangemèld',
'movenologintext'         => "Veur 't verplaatsje van 'n pazjena mos e zien [[Special:Userlogin|aangemèld]].",
'movenotallowed'          => "Doe kins gein pazjena's verplaatse op deze wiki.",
'newtitle'                => 'Nao de nuje titel',
'move-watch'              => 'Volg deze pazjena',
'movepagebtn'             => 'Verplaats pazjena',
'pagemovedsub'            => 'De verplaatsing is gelök',
'movepage-moved'          => '<big>\'\'\'"$1" is verplaats nao "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => "Dao is al 'n pazjena mit dees titel of de titel is óngeljig. <br />Kees estebleef 'n anger titel.",
'talkexists'              => "De pazjena zelf is verplaats, meh de euverlikpazjena kós neet verplaats waere, ómdat d'r al 'n euverlikpazjena mit de nuje titel besjtóng. Combineer de euverlikpazjena's estebleef mit de hand.",
'movedto'                 => 'verplaats nao',
'movetalk'                => 'Verplaats de euverlikpazjena ouch.',
'talkpagemoved'           => 'De biebehurende euverlikpazjena is ouch verplaats.',
'talkpagenotmoved'        => 'De biebehurende euverlikpazjena is <strong>neet</strong> verplaats.',
'1movedto2'               => '[[$1]] verplaats nao [[$2]]',
'1movedto2_redir'         => '[[$1]] euver redirect verplaats nao [[$2]]',
'movelogpage'             => "Logbook verplaatsde pazjena's",
'movelogpagetext'         => "Dit is de lies van verplaatsde pazjena's.",
'movereason'              => 'Lèk oet woeróm',
'revertmove'              => 'trökdrieje',
'delete_and_move'         => 'Wis en verplaats',
'delete_and_move_text'    => '==Wisse vereis==

De doeltitel "[[$1]]" besjteit al. Wils te dit artikel wisse óm ruumde te make veur de verplaatsing?',
'delete_and_move_confirm' => 'Jao, wis de pazjena',
'delete_and_move_reason'  => 'Gewis óm artikel te kónne verplaatse',
'selfmove'                => "Doe kins 'ne pazjena neet verplaatse nao dezelfde paginanaam.",
'immobile_namespace'      => "De gewinsde paginanaam is van 'n speciaal type. 'ne Pazjena kin neet hernömp waere nao die naamruumde.",

# Export
'export' => "Exporteer pazjena's",

# Namespace 8 related
'allmessages'               => 'Alle systeemberichte',
'allmessagesdefault'        => 'Obligaten teks',
'allmessagescurrent'        => 'Hujige teks',
'allmessagestext'           => "Dit is 'n lies van alle systeemberichte besjikbaar in de MediaWiki:-naamruumde.",
'allmessagesnotsupportedDB' => '{{ns:special}}:Allmessages neet óngersjteundj ómdat wgUseDatabaseMessages oet (off) sjteit.',
'allmessagesfilter'         => 'Berich naamfilter:',
'allmessagesmodified'       => 'Tuun allein gewiezigde systeemtekste',

# Thumbnails
'thumbnail-more'           => 'Vergroete',
'missingimage'             => '<b>Plaetsje neet besjikbaar</b><br /><i>$1</i>',
'filemissing'              => 'Besjtand ontbrik',
'thumbnail_error'          => "Fout bie 't aanmake van thumbnail: $1",
'djvu_page_error'          => 'DjVu-pagina boete bereik',
'djvu_no_xml'              => "De XML veur 't DjVu-bestandj kos neet opgehaald waere",
'thumbnail_invalid_params' => 'Onzjuste thumbnailparamaetere',
'thumbnail_dest_directory' => 'Neet in staat doel directory aan te make',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Mien gebroekerspazjena',
'tooltip-pt-anonuserpage'         => 'De gebroekerspazjena veur dit IP adres',
'tooltip-pt-mytalk'               => 'Mien euverlikpazjena',
'tooltip-pt-anontalk'             => 'Euverlèk euver verangeringe doer dit IP addres',
'tooltip-pt-preferences'          => 'Mien veurkäöre',
'tooltip-pt-watchlist'            => 'De liest van gevolgde pazjenas.',
'tooltip-pt-mycontris'            => 'Liest van mien biedraag',
'tooltip-pt-login'                => 'De weurs aangemodigd om in te logge, meh t is neet verplich.',
'tooltip-pt-anonlogin'            => 'De weurs aangemodigd om in te logge, meh t is neet verplich.',
'tooltip-pt-logout'               => 'Aafmèlde',
'tooltip-ca-talk'                 => 'Euverlèk euver dit artikel',
'tooltip-ca-edit'                 => 'De kins dees pazjena verangere.',
'tooltip-ca-addsection'           => 'Opmèrking toevoge aan dees discussie.',
'tooltip-ca-viewsource'           => 'Dees pazjena is besjermd. De kins häör brontèks bekieke.',
'tooltip-ca-history'              => 'Auw versies van dees pazjena.',
'tooltip-ca-protect'              => 'Besjerm dees pazjena',
'tooltip-ca-delete'               => 'Verwieder dees pazjena',
'tooltip-ca-undelete'             => 'Hersjtèl de verangeringe van dees pazjena van veurdat ze gewist woerd',
'tooltip-ca-move'                 => 'Verplaats dees pazjena',
'tooltip-ca-watch'                => 'Dees pazjena toeveuge aan volgliest',
'tooltip-ca-unwatch'              => 'Dees pazjena van volgliest aafhaole',
'tooltip-search'                  => 'Doorzeuk {{SITENAME}}',
'tooltip-search-go'               => "Gao nao 'ne pazjena mit dezelfde naam es d'r bestuit",
'tooltip-search-fulltext'         => 'Zeuk de pazjenas veur deze teks',
'tooltip-p-logo'                  => 'Veurblaad',
'tooltip-n-mainpage'              => "Bezeuk 't veurblaad",
'tooltip-n-portal'                => 'Euver t projèk, was e kins doon, woe se dinger kins vinge',
'tooltip-n-currentevents'         => 'Achtergrondinfo van t nuuis',
'tooltip-n-recentchanges'         => 'De lies van recènte verangeringe in de wiki.',
'tooltip-n-randompage'            => 'Laadt n willekäörige pazjena',
'tooltip-n-help'                  => 'De plek om informatie euver dit projèk te vinge.',
'tooltip-n-sitesupport'           => 'Sjteun os',
'tooltip-t-whatlinkshere'         => 'Liest van alle wiki pazjenas die hieheen linke',
'tooltip-t-recentchangeslinked'   => 'Recènte verangeringe in pazjenas woeheen gelinkt weurd',
'tooltip-feed-rss'                => 'RSS feed veur dees pazjena',
'tooltip-feed-atom'               => 'Atom feed veur dees pazjena',
'tooltip-t-contributions'         => 'Bekiek de liest van contributies van dizze gebroeker',
'tooltip-t-emailuser'             => 'Sjtuur inne mail noa dizze gebroeker',
'tooltip-t-upload'                => 'Upload plaetsjes of media besjtande',
'tooltip-t-specialpages'          => 'Liest van alle speciale pazjenas',
'tooltip-t-print'                 => 'Printvruntjelike versie van deze pagina',
'tooltip-t-permalink'             => 'Permanente link nao deze versie van de pagina',
'tooltip-ca-nstab-main'           => 'Bekiek de pazjena',
'tooltip-ca-nstab-user'           => 'Bekiek de gebroekerspazjena',
'tooltip-ca-nstab-media'          => 'Bekiek de mediapazjena',
'tooltip-ca-nstab-special'        => 'Dit is n speciaal pazjena, de kins dees pazjena neet zelf editte.',
'tooltip-ca-nstab-project'        => 'Bekiek de projèkpazjena',
'tooltip-ca-nstab-image'          => 'Bekiek de plaetsjespazjena',
'tooltip-ca-nstab-mediawiki'      => 'Bekiek t systeimberich',
'tooltip-ca-nstab-template'       => 'Bekiek t sjabloon',
'tooltip-ca-nstab-help'           => 'Bekiek de helppazjena',
'tooltip-ca-nstab-category'       => 'Bekiek de kattegoriepazjena',
'tooltip-minoredit'               => "Markeer dit as 'n kleine verangering",
'tooltip-save'                    => 'Bewaar dien verangeringe',
'tooltip-preview'                 => 'Bekiek dien verangeringe veurdets te ze definitief opsjleis!',
'tooltip-diff'                    => 'Bekiek dien verangeringe in de teks.',
'tooltip-compareselectedversions' => 'Bekiek de versjille tusje de twie geselectierde versies van dees pazjena.',
'tooltip-watch'                   => 'Voog dees pazjena toe aan dien volglies',
'tooltip-recreate'                => 'Maak deze pagina opnuuj aan ondanks irdere verwiedering',
'tooltip-upload'                  => 'Uploade',

# Metadata
'nodublincore'      => 'Dublin Core RDF metadata is oetgesjakeld op deze server.',
'nocreativecommons' => 'Creative Commons RDF metadata is oetgesjakeld op deze server.',
'notacceptable'     => "De wikiserver kin de gegaeves neet levere in  'ne vorm dae diene client kin laeze.",

# Attribution
'anonymous'        => 'Anoniem(e) gebroeker(s) van {{SITENAME}}',
'siteuser'         => '{{SITENAME}} gebroeker $1',
'lastmodifiedatby' => "Dees pazjena is 't litst verangert op $2, $1 doer $3.", # $1 date, $2 time, $3 user
'othercontribs'    => 'Gebaseerd op wèrk van $1.',
'others'           => 'angere',
'siteusers'        => '{{SITENAME}} gebroekers(s) $1',
'creditspage'      => 'Sjrievers van dees pazjena',
'nocredits'        => "d'r Is gein auteursinformatie besjikbaar veur deze pagina.",

# Spam protection
'spamprotectiontext'     => "De pagina daese wildes opslaon is geblokkeerd door de spamfilter. Meistal wörd dit door 'ne externe link veroorzaak.",
'spamprotectionmatch'    => "De volgende teks veroorzaakte 'n alarm van de spamfilter: $1",
'subcategorycount'       => 'Dees categorie haet {{PLURAL:$1|ein subcategorie|$1 subcategorieë}}.',
'categoryarticlecount'   => 'Dao zeen $1 artikele in dees categorie.',
'category-media-count'   => "d'r {{PLURAL:$1|stuit éin bestandj|staon $1 bestenj}} in deze categorie.",
'listingcontinuesabbrev' => 'wiejer',
'spambot_username'       => 'MediaWiki spam opruming',
'spam_reverting'         => 'Bezig mit trökdrèjje nao de letste versie die gein verwiezing haet nao $1',
'spam_blanking'          => "Alle wieziginge mit 'ne link nao $1 waere verwiederd",

# Info page
'infosubtitle' => 'Informatie veur pagina',
'numedits'     => 'Aantal bewerkinge (pagina): $1',
'numtalkedits' => 'Aantal bewerkinge (euverlikpagina): $1',
'numwatchers'  => 'Aantal volgende: $1',
'numauthors'   => 'Aantal sjrievers (pagina): $1',

# Math options
'mw_math_png'    => 'Ummer PNG rendere',
'mw_math_simple' => 'HTML in erg simpele gevalle en angesj PNG',
'mw_math_html'   => 'HTML woe meugelik en angesj PNG',
'mw_math_source' => 'Laot de TeX code sjtaon (vuur tèksbrowsers)',
'mw_math_modern' => 'Aangeroaje vuur nuui browsers',
'mw_math_mathml' => 'MathML woe meugelik (experimenteil)',

# Patrolling
'markaspatrolleddiff'                 => 'Markeer es gecontroleerd',
'markaspatrolledtext'                 => 'Markeer deze pagina es gecontroleerd',
'markedaspatrolled'                   => 'Gemarkeerd es gecontroleerd',
'markedaspatrolledtext'               => 'De gekaoze versie is gemarkeerd es gecontroleerd.',
'rcpatroldisabled'                    => 'De controlemeugelikheid op recènte wieziginge is oetgesjakeld.',
'rcpatroldisabledtext'                => 'De meugelikheid om recènte verangeringe es gecontroleerd aan te mèrke is op dit ougeblik oetgesjakeld.',
'markedaspatrollederror'              => 'Kin neet es gecontroleerd waere aangemèrk',
'markedaspatrollederrortext'          => "Selecteer 'ne versie om es gecontroleerd aan te mèrke.",
'markedaspatrollederror-noautopatrol' => 'Doe kins dien eige wieziginge neet es gecontroleerd markere.',

# Patrol log
'patrol-log-page' => 'Markeerlogbook',
'patrol-log-line' => 'markeerde versie $1 van $2 es gecontroleerd $3',

# Image deletion
'deletedrevision'                 => 'Aw versie $1 gewis',
'filedeleteerror-short'           => "Fout biej 't wisse van bestandj: $1",
'filedeleteerror-long'            => "d'r Zeen foute opgetraoje bie 't verwiedere van 't bestandj:

$1",
'filedelete-missing'              => '\'t Bestandj "$1" kin neet gewis waere, ómdet \'t neet bestuit.',
'filedelete-old-unregistered'     => 'De aangegaeve bestandjsversie "$1" stuit neet in de database.',
'filedelete-current-unregistered' => '\'t Aangegaeve bestandj "$1" stuit neet in de database.',
'filedelete-archive-read-only'    => 'De webserver kin neet in de archiefmap "$1" sjrieve.',

# Browsing diffs
'previousdiff' => '← Gank nao de vurrige diff',
'nextdiff'     => 'Gank nao de volgende diff →',

# Media information
'mediawarning'         => "'''Waorsjuwing''': Dit bestanj kin 'ne angere code höbbe, door 't te doorveure in dien systeem kin 't gecompromiseerde dinger oplevere.<hr />",
'imagemaxsize'         => "Bepèrk plaetsjes op de besjrievingspazjena's van aafbeildinge tot:",
'thumbsize'            => 'Gruutde vanne thumbnail:',
'widthheightpage'      => "$1×$2, $3 pazjena's",
'file-info'            => '(bestandsgruutde: $1, MIME-type: $2)',
'file-info-size'       => '($1 × $2 pixels, bestandsgruutde: $3, MIME-type: $4)',
'file-nohires'         => '<small>Gein hogere resolutie besjikbaar.</small>',
'svg-long-desc'        => '(SVG-bestandj, nominaal $1 × $2 pixels, bestandsgruutde: $3)',
'show-big-image'       => 'Hogere rezolusie',
'show-big-image-thumb' => '<small>Gruutde van deze afbeilding: $1 × $2 pixels</small>',

# Special:Newimages
'newimages' => 'Nuuj plaetjes',
'noimages'  => 'Niks te zeen.',

# Bad image list
'bad_image_list' => "De opmaak is es volg:

Allein regels in 'ne lies (regels die beginne mit *) waere verwerk. De irste link op 'ne regel mot 'ne link zeen nao 'ne óngewunsjde afbeilding.
Alle volgende links die op dezelfde regel staon, waere behanjeld es oetzönjering, wie beveurbeeld pazjena's wo op de afbeilding in de teks opgenaome is.",

# Metadata
'metadata-help'     => "Dit bestandj bevat aanvullende informatie, dae door 'ne fotocamera, 'ne scanner of 'n fotobewèrkingsprogramma toegevoeg kin zeen. Es 't bestandj aangepas is, dan komme details meugelik neet overein mit de gewiezigde afbeilding.",
'metadata-expand'   => 'Tuun oetgebreide gegaeves',
'metadata-collapse' => 'Verberg oetgebreide gegaeves',
'metadata-fields'   => "De EXIF metadatavelde in dit berich waere ouch getuund op 'ne afbeildingspazjena es de metadatatabel is ingeklap. Angere velde waere verborge.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength", # Do not translate list items

# EXIF tags
'exif-bitspersample'            => 'Bits per componènt',
'exif-compression'              => 'Cómpressiesjema',
'exif-datetime'                 => 'Datum en momènt besjtandjsverangering',
'exif-copyright'                => 'Copyrighthawter',
'exif-colorspace'               => 'Kläörruumde',
'exif-componentsconfiguration'  => 'Beteikenis van edere componènt',
'exif-compressedbitsperpixel'   => 'Cómpressiemeneer bie dit plaetje',
'exif-pixelxdimension'          => 'Valind image height',
'exif-datetimeoriginal'         => 'Datum en momint van verwèkking',
'exif-datetimedigitized'        => 'Datum en momènt van digitizing',
'exif-aperturevalue'            => 'Eupening',
'exif-brightnessvalue'          => 'Heljerheid',
'exif-cfapattern'               => 'CFA-patroen',
'exif-contrast'                 => 'Contras',
'exif-devicesettingdescription' => 'Besjrieving methode-opties',

# EXIF attributes
'exif-compression-1' => 'Óngecómprimeerd',

'exif-componentsconfiguration-0' => 'besjteit neet',

'exif-customrendered-0' => 'Normaal perces',

'exif-contrast-1' => 'Weik',
'exif-contrast-2' => 'Hel',

# External editor support
'edit-externally'      => "Bewirk dit bestand mit 'n extern toepassing",
'edit-externally-help' => 'Zuug de [http://meta.wikimedia.org/wiki/Help:External_editors setupinsjtructies] veur mier informatie.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'al',
'imagelistall'     => 'al',
'watchlistall2'    => 'al',
'namespacesall'    => 'al',
'monthsall'        => 'al',

# E-mail address confirmation
'confirmemail'            => 'Bevèstig e-mailadres',
'confirmemail_noemail'    => 'Doe höbs gein geldig e-mailadres ingegaeve in dien [[{{ns:special}}:Preferences|veurkäöre]].',
'confirmemail_text'       => "Deze wiki vereis dats te dien e-mailadres instèls iedats te e-mailfuncties
gebroeks. Klik op de knop hieónger óm e bevèstegingsberich nao dien adres te
sjikke. D'n e-mail zal 'ne link mèt 'n code bevatte; eupen de link in diene
browser óm te bevestege dat dien e-mailadres werk.",
'confirmemail_pending'    => "<div class=\"error\">Dao is al 'n bevestigingsberich aan dich versjik. Wens te zjus diene gebroeker aangemaak höbs, wach dan e paar minute pès 't aankump veurdets te opnuuj 'n e-mail leuts sjikke.</div>",
'confirmemail_send'       => "Sjik 'n bevèstegingcode",
'confirmemail_sent'       => 'Bevèstegingsberich versjik.',
'confirmemail_oncreate'   => "D'r is 'n bevestigingscode nao dien e-mailadres versjik. Dees code is neet nudig óm aan te melje, meh doe mós dees waal bevestige veurdets te de e-mailmäögelikheite van deze wiki kèns nótse.",
'confirmemail_sendfailed' => "Kós 't bevèstegingsberich neet versjikke. Zuug dien e-mailadres nao op óngeljige karakters.",
'confirmemail_invalid'    => 'Óngeljige bevèstigingscode. De code is meugelik verloupe.',
'confirmemail_needlogin'  => 'Doe mós $1 óm dien e-mailadres te bevestige.',
'confirmemail_success'    => 'Dien e-mailadres is bevesteg. De kins noe inlogke en van de wiki genete.',
'confirmemail_loggedin'   => 'Dien e-mailadres is noe vasgelag.',
'confirmemail_error'      => "Bie 't opsjlaon van eur bevèstiging is get fout gegange.",
'confirmemail_subject'    => 'Bevèstiging e-mailadres veur {{SITENAME}}',
'confirmemail_body'       => "Emes, waorsjienlik doe vanaaf 't IP-adres $1, heet 'n account $2
aangemaak mit dit e-mailadres op {{SITENAME}}.

Eupen óm te bevèstige dat dit account wirkelik van dich is en de
e-mailgegaeves op {{SITENAME}} te activere deze link in diene browser:

$3

Es geer dit *neet* zeet, dan volg de link neet. Dees bevèstigingscode
blief geljig tot $4",

# Scary transclusion
'scarytranscludedisabled' => '[Interwikitransclusie is oetgesjakeld]',
'scarytranscludefailed'   => '[Sjabloon $1 kós neet opgehaold waere; sorry]',
'scarytranscludetoolong'  => '[URL is te lank; sorry]',

# Trackbacks
'trackbackbox'      => "<div id='mw_trackbacks'>
Trackbacks veur deze pazjena:<br />
$1
</div>",
'trackbackremove'   => ' ([$1 Wusje])',
'trackbackdeleteok' => 'De trackback is gewusj.',

# Delete conflict
'deletedwhileediting' => 'Waorsjoewing: dees pazjena is gewis naodats doe bis begós mit bewirke.',
'confirmrecreate'     => "Gebroeker [[User:$1|$1]] ([[User talk:$1|euverlèk]]) heet dit artikel gewis naodats doe mèt bewirke begós mèt de rae:
: ''$2''
Bevèsteg estebleef dats te dees pazjena ech obbenuujts wils make.",
'recreate'            => 'Pazjena obbenuujts make',

# HTML dump
'redirectingto' => "Aan 't doorverwieze nao [[$1]]...",

# action=purge
'confirm_purge' => 'Wils te de buffer vaan dees paas wisse?

$1',

# AJAX search
'searchcontaining' => "Zeuk nao pazjena's die ''$1'' bevatte.",
'searchnamed'      => "Zeuk nao pazjena's mit de naam ''$1''.",
'articletitles'    => "Pazjena's die mit ''$1'' beginne",
'hideresults'      => 'Versjtaek resultate',

# Multipage image navigation
'imgmultipageprev'   => '← veurige pazjena',
'imgmultipagenext'   => 'volgende pazjena →',
'imgmultigo'         => 'Gank!',
'imgmultigotopre'    => 'Gank nao pazjena',
'imgmultiparseerror' => "'t Aafbeeldingsbesjtandj sjint neet richtig te zeen, zoedet {{SITENAME}} gein lies van pazjena's kós trökvènje.",

# Table pager
'table_pager_next'  => 'Volgende pazjena',
'table_pager_prev'  => 'Veurige pazjena',
'table_pager_first' => 'Ierste pazjena',
'table_pager_last'  => 'Lètste pazjena',
'table_pager_limit' => 'Tuin $1 resultate per pazjena',
'table_pager_empty' => 'Gein resultate',

# Auto-summaries
'autosumm-blank'   => 'Pazjena laeggehaold',
'autosumm-replace' => "Teks vervange mit '$1'",
'autoredircomment' => 'Verwies door nao [[$1]]',
'autosumm-new'     => 'Nuje pazjena: $1',

# Live preview
'livepreview-loading' => 'Laje…',
'livepreview-ready'   => 'Laje… Vaerdig!',
'livepreview-failed'  => 'Live veurvertuun mislök!
Probeer normaal veurvertuin.',
'livepreview-error'   => 'Verbènje mislök: $1 "$2"
Probeer normaal veurvertuin.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Verangeringe die nujer zeen es $1 seconde waere mesjiens neet getuind in dees lies.',
'lag-warn-high'   => "Door 'ne hoege database-servertoeveur zeen verangeringe nujer es $1 seconde mäögelik neet besjikbaar in de lies.",

# Watchlist editor
'watchlistedit-numitems'       => "Op dien volglies sjtaon {{PLURAL:$1|1 pazjena|$1 pazjena's}}, exclusief euverlèkpazjena's.",
'watchlistedit-noitems'        => "Dao sjtaon gein pazjena's op dien volglies.",
'watchlistedit-normal-title'   => 'Volglies bewirke',
'watchlistedit-normal-legend'  => "Pazjena's ewegsjaffe van dien volglies",
'watchlistedit-normal-explain' => "Pazjena's op dien volglies waere hiejónger getuind. Klik op 't veerkentje d'rnaeve óm 'ne pazjena eweg te sjaffe. Klik daonao op 'Pazjena's ewegsjaffe'. Doe kins ouch [[{{ns:special}}:Watchlist/raw|de roew lies bewirke]].",
'watchlistedit-normal-submit'  => "Pazjena's ewegsjaffe",
'watchlistedit-normal-done'    => "{{PLURAL:$1|1 pazjena is|$1 pazjena's zeen}} eweggesjaf van dien volglies:",
'watchlistedit-raw-title'      => 'Roew volglies bewirke',
'watchlistedit-raw-legend'     => 'Roew volglies bewirke',
'watchlistedit-raw-explain'    => "Hiejónger sjtaon pazjena's op dien volglies. Doe kins de lies bewirke door pazjena's eweg te sjaffe en toe te voge. Eine pazjena per regel. Wens te vaerdig bis, klik dan op 'Volglies biewirke'. Doe kins ouch [[{{ns:special}}:Watchlist/edit|'t sjtanderd bewirkingssjirm gebroeke]].",
'watchlistedit-raw-titles'     => "Pazjena's:",
'watchlistedit-raw-submit'     => 'Volglies biewirke',
'watchlistedit-raw-done'       => 'Dien volglies is biegewirk.',
'watchlistedit-raw-added'      => "{{PLURAL:$1|1 pazjena is|$1 pazjena's zeen}} toegevoog:",
'watchlistedit-raw-removed'    => "{{PLURAL:$1|1 pazjena is|$1 pazjena's zeen}} eweggesjaf:",

# Watchlist editing tools
'watchlisttools-view' => 'Volglies bekieke',
'watchlisttools-edit' => 'Volglies bekieke en bewirke',
'watchlisttools-raw'  => 'Roew volglies bewirke',

);
