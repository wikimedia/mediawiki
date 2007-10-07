<?php
/** Limburgian (Limburgs)
  *
  * @addtogroup Language
  *
  * @author Ooswesthoesbes
  * @author Cicero
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
'tog-underline'               => 'Links ongersjtreipe',
'tog-highlightbroken'         => 'Formatteer gebraoke links <a href="" class="new">op dees meneer</a> (angesj: zoe<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Paragrafe oetvulle',
'tog-hideminor'               => 'Verbèrg klein bewirking bie recènte verangeringe',
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
'tog-watchdeletion'           => "Pazjena's die ich verwieder automatisch volge",
'tog-minordefault'            => 'Merkeer sjtandaard alle bewirke as klein',
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
'viewcount'         => 'Dees pazjena is $1 kier bekeke.',
'protectedpage'     => 'Beveiligde pazjena',
'jumpto'            => 'Gao nao:',
'jumptonavigation'  => 'navigatie',
'jumptosearch'      => 'zeuke',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Euver {{SITENAME}}',
'aboutpage'         => '{{ns:project}}:Info',
'bugreports'        => 'Fouterapportaasj',
'bugreportspage'    => '{{ns:project}}:Fouterapportaasj',
'copyright'         => 'De inhawd is besjikbaar ónger de $1.',
'copyrightpagename' => '{{SITENAME}} auteursrechte',
'copyrightpage'     => '{{ns:project}}:Auteursrechte',
'currentevents'     => "In 't nuujs",
'currentevents-url' => "{{ns:project}}:In 't nuujs",
'disclaimers'       => 'Aafwiezinge aansjprakelikheid',
'disclaimerpage'    => '{{SITENAME}}: Algemein aafwiezing aansjprakelikheid',
'edithelp'          => 'Hulp bie bewirke',
'edithelppage'      => '{{ns:help}}:Instructies',
'faqpage'           => '{{ns:project}}:Veulgestjilde vraoge',
'helppage'          => '{{ns:project}}:Help',
'mainpage'          => 'Veurblaad',
'portal'            => 'Gebroekersportaol',
'portal-url'        => '{{ns:project}}:Gebroekersportaol',
'privacypage'       => '{{ns:project}}:Privacy_policy',
'sitesupport'       => 'Donaties',
'sitesupport-url'   => '{{ns:project}}:Gifte',

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

# Short words for each namespace, by default used in the 'article' tab in monobook
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
'blockedtext'               => 'Diene gebroekersnaam of IP-adres is geblokkeerd door $1. De opgegaeve raeje:<br />$2<br />De kins veur euverlik kontak opnumme mit de [[Project:Systeemwèrkers|systeemwèrkers]].

Your IP address is $3. Please include this address in any queries you make.',
'autoblockedtext'           => "Dien IP-adres is automatisch geblokkeerd omdet 't gebroek is door 'ne gebroeker, dae is geblokkeerd door $1.
De opgegaeve reje is:

:''$2''

* Aanvang blokkade: $8
* Einde blokkade: $6

Doe kins deze blokkaasj bespraeke mèt $1 of 'ne angere [[{{MediaWiki:grouppage-sysop}}|beheerder]]. Doe kins gén gebroek make van de functie 'e-mail deze gebroeker', tenzijse 'n geldig e-mailadres opgegaeve höbs in dien [[{{ns:special}}:Preferences|veurkeure]] en 't gebroek van deze functie neet is geblokkeerd. Dien hujig IP-adres is $3 en ' nömmer vanne blokkaasj is #$5. Vermeld beide gegaeves esse örges euver deze blokkaasj reageers.",
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
'anontalkpagetext'          => "----''Dit is de euverlikpazjena veur 'ne anonieme gebroeker dae nog gein account haet aangemak of dae 't neet gebroek. Daorom gebroeke v'r 't [[IP adres]] om de gebroeker te identificere. Dat adres kint weure gedeild doer miedere gebroekers. As e 'ne anonieme gebroeker bis en de höbs 't geveul dat 'r onrillevante commentare aan dich gericht zint, kins e 't biste [[Special:Userlogin|'n account crëere of inlogge]] om toekomstige verwarring mit angere anonieme gebroekers te veurkomme.''",
'noarticletext'             => "(Dees pazjena bevat op 't moment gein teks)",
'clearyourcache'            => "'''Lèt op:''' Nao 't opsjlaon mós te diene browserbuffer wisse óm de verangeringe te zeen: '''Mozilla:''' klik ''Reload'' (of ''Ctrl-R''), '''Firefox / IE / Opera:''' ''Ctrl-F5'', '''Safari:''' ''Cmd-R'', '''Konqueror''' ''Ctrl-R''.",
'usercssjsyoucanpreview'    => "<strong>Tip:</strong> Gebroek de knoep 'Tuun bewerking ter controle' om dien nuuje css/js te teste alveures op sjlaon.",
'usercsspreview'            => "'''Dit is allein 'n veurvertuun van dien perseunlike css, deze is neet opgeslage!'''",
'userjspreview'             => "'''Let op: doe tes noe dien perseunlik JavaScript. De pazjena is neet opgeslage!'''",
'userinvalidcssjstitle'     => "'''Waorsjuwing:''' d'r is geine skin \"\$1\". Let op: dien eige .css- en .js-pazjena's beginne mèt  'ne kleine letter, bijveurbeeld User:Naam/monobook.css in plaats van User:Naam/Monobook.css.",
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

# History pages
'revhistory'          => 'Bewirkingshistorie',
'nohistory'           => 'Dees pazjena is nog neet bewirk.',
'revnotfound'         => 'Wieziging neet gevonge',
'revnotfoundtext'     => 'De opgevraogde aw versie van dees pazjena is verzjwónde. Kontroleer estebleef de URL dieste gebroek höbs óm nao dees pazjena te gaon.',
'loadhist'            => "Bezig met 't laje van de pazjenahistorie",
'currentrev'          => 'Hujige versie',
'previousrevision'    => '← Awwer versie',
'currentrevisionlink' => 'zuug hujige versie',
'cur'                 => 'hujig',
'last'                => 'vörrige',
'histlegend'          => 'Verklaoring aafkortinge: (wijz) = versjil mit actueile versie, (vörrige) = versjil mit vörrige versie, K = kleine verangering',
'deletedrev'          => '[gewis]',

# Diffs
'difference'                => '(Versjil tösje bewirkinge)',
'loadingrev'                => "bezig mit 't laje van de pazjenaversie",
'editcurrent'               => 'De hujige versie van dees pazjena bewirke.',
'selectnewerversionfordiff' => "Kees 'n nuuiere versie om te vergelieke",
'selectolderversionfordiff' => "Kees 'n auwere versie om te vergelieke",
'compareselectedversions'   => 'Vergeliek geselecteerde versies',

# Search results
'searchresults'         => 'Zeukresultate',
'searchresulttext'      => 'Veur mier informatie euver zeuke op {{SITENAME}}, zuug [[{{MediaWiki:helppage}}|{{int:help}}]].',
'searchsubtitleinvalid' => 'Voor zoekopdracht "$1"',
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
'preferences'           => 'Veurkäöre',
'mypreferences'         => 'Mien veurkäöre',
'prefs-edits'           => 'Aantal bewèrkinge:',
'prefsnologin'          => 'Neet aangemèld',
'prefsnologintext'      => 'De mos zien [[Special:Userlogin|aangemèld]] om veurkäöre te kinne insjtèlle.',
'prefsreset'            => 'Sjtandaardveurkäöre hersjtèld.',
'qbsettings'            => 'Menubalkinsjtèllinge',
'qbsettings-none'       => 'Oetgesjakeld',
'qbsettings-fixedleft'  => 'Links vas',
'qbsettings-fixedright' => 'Rechts vas',
'changepassword'        => 'Wachwaord verangere',
'skin'                  => '{{SITENAME}}-uterlik',
'math'                  => 'Mattemetik rendere',
'dateformat'            => 'Datumformaat',
'datedefault'           => 'Gein veurkäör',
'datetime'              => 'Datum en tied',
'math_unknown_error'    => 'onbekènde fout',
'math_unknown_function' => 'onbekènde functie',
'math_bad_output'       => 'Kin neet sjrieve nao de output directory veur mattematik',
'prefs-personal'        => 'Gebroekersinfo',
'prefs-rc'              => 'Recènte verangeringe en weergaaf van sjtumpkes',
'prefs-misc'            => 'Anger insjtèllinge',
'saveprefs'             => 'Veurkäöre opsjlaon',
'resetprefs'            => 'Sjtandaardveurkäöre hersjtèlle',
'oldpassword'           => 'Hujig wachwaord',
'newpassword'           => 'Nuuj wachwaord',
'retypenew'             => "Veur 't nuuj wachwaord nogins in",
'textboxsize'           => 'Aafmeitinge tèksveld',
'rows'                  => 'Raegels',
'columns'               => 'Kolomme',
'searchresultshead'     => 'Insjtèllinge veur zeukresultate',
'resultsperpage'        => 'Aantal te toene zeukresultate per pazjena',
'contextlines'          => 'Aantal reigels per gevónje pazjena',
'contextchars'          => 'Aantal teikes van de conteks per reigel',
'recentchangescount'    => 'Aantal titels in lies recènte verangeringe',
'savedprefs'            => 'Dien veurkäöre zint opgesjlage.',
'timezonelegend'        => 'Tiedzone',
'timezonetext'          => "'t Aantal oere dat diene lokale tied versjilt van de servertied (UTC).",
'localtime'             => 'Plaotsjelike tied',
'timezoneoffset'        => 'tiedsverschil',
'servertime'            => 'Server tied is noe',
'guesstimezone'         => 'Invulle van browser',
'allowemail'            => 'E-mail van anger gebroekers toesjtaon',
'defaultns'             => 'Zeuk sjtandaard in dees naomruumdes:',
'default'               => 'sjtandaard',

# Recent changes
'recentchanges'     => 'Recènte verangeringe',
'recentchangestext' => 'literal translation',
'rcnote'            => 'Hiejónger sjtaon de <strong>$1</strong> lètste verangeringe van de aafgeloupe <strong>$2</strong> daag, $3.',
'rcnotefrom'        => "Verangeringe sins <b>$2</b> (mit 'n maximum van <b>$1</b> verangeringe).",
'rclistfrom'        => 'Toen de verangeringe vanaaf $1',
'rclinks'           => 'Bekiek de $1 litste verangeringe van de aafgelaupe $2 daag.<br />$3',
'diff'              => 'vera',
'hide'              => 'verberg',
'show'              => 'toen',
'minoreditletter'   => 'K',

# Recent changes linked
'recentchangeslinked' => 'Volg links',

# Upload
'upload'            => 'Upload',
'uploadbtn'         => 'upload file',
'reupload'          => 'Opnuui uploade',
'reuploaddesc'      => "Truuk nao 't uploadformeleer.",
'uploadnologin'     => 'Neet aangemèld',
'uploadnologintext' => 'De mos [[Special:Userlogin|zien aangemèld]] om besjtande te uploade.',
'uploaderror'       => 'upload fout',
'uploadtext'        => "Gebroek 't óngersjtaonde formuleer óm besjtande op te laje. Óm ierder opgelaje besjtande te bekieke of te zeuke, gank nao de [[Special:Imagelist|lies van opgelaje besjtande]]. Uploads en verwiederinge waere ouch biegehauwte in 't [[Special:Log/upload|uploadlogbook]]. 

Gebroek óm 'n plaetje of 'n besjtand in 'n pazjena op te numme 'ne link in de vörm:
* '''<nowiki>[[</nowiki>{{ns:Image}}<nowiki>:Besjtand.jpg]]</nowiki>'''
* '''<nowiki>[[</nowiki>{{ns:Image}}<nowiki>:Besjtand.png|alternatief teks]]</nowiki>'''
of veur mediabesjtande:
* '''<nowiki>[[</nowiki>{{ns:Media}}<nowiki>:Besjtand.ogg]]</nowiki>'''",
'uploadlog'         => 'uploadlogbook',
'uploadlogpage'     => 'Uploadlogbook',
'uploadlogpagetext' => 'Hieonger de lies mit de meist recent ge-uploade besjtande. Alle tiede zunt servertiede (UTC).',
'filename'          => 'Besjtandsnaom',
'filedesc'          => 'Besjrieving',
'uploadedfiles'     => 'Ge-uploade bestanden',
'badfilename'       => 'De naom van \'t besjtand is verangerd in "$1".',
'emptyfile'         => "'t Besjtand wats re höbs geupload is laeg. Dit kump waorsjienliek door 'n typfout in de besjtandsnaom. Kiek estebleef ofs te dit besjtand wirkelik wils uploade.",
'fileexists'        => "D'r is al e besjtand mit dees naam, bekiek $1 of se dat besjtand mesjien wils vervange.",
'successfulupload'  => 'De upload is geluk',
'uploadwarning'     => 'Upload waarsjuwing',
'savefile'          => 'Bestand opsjlaon',
'uploadedimage'     => 'haet ge-upload: [[$1]]',
'destfilename'      => 'Doeltitel',

# Image list
'imagelist'      => 'Lies van aafbeildinge',
'imagelisttext'  => "Hie volgt 'n lies mit $1 afbeildinge geordend $2.",
'getimagelist'   => 'Lies van aafbeildinge ophaole',
'ilsubmit'       => 'Zeuk',
'showlast'       => 'Toen de litste $1 aafbeildinge geordend $2.',
'byname'         => 'op naom',
'bysize'         => 'op gruutde',
'imgdesc'        => 'besc',
'imagelinks'     => 'Aafbeildingsverwiezinge',
'linkstoimage'   => "Dees aafbeilding weurt op de volgende pazjena's gebroek:",
'nolinkstoimage' => 'Gein enkele pazjena gebroek dees aafbeilding.',
'sharedupload'   => 'literal translation',

# Statistics
'statistics'    => 'Sjtattestieke',
'sitestats'     => 'Sjtatistieke euver {{SITENAME}}',
'userstats'     => 'Stattestieke euver gebroekers',
'sitestatstext' => "D'r zunt in totaal '''\$1''' pazjena's in de database.
Dit is inclusief \"euverlik\"-pazjena's, pazjena's euver {{SITENAME}}, extreem korte \"sjtumpkes\", redirects, en anger pazjena's die waarsjienlik neet as inhoud mote waere getèld. 't Aantal pazjena's mit content weurt gesjat op '''\$2'''.

D'r zunt '''\$8''' besjtande opgelaje.

D'r is in totaal '''\$3''' kier 'n pazjena bekeke en '''\$4''' kier 'n pazjena bewirk sins de wiki is opgezat. Dat geuf e gemiddelde van '''\$5''' bewirkinge per pazjena en '''\$6''' getuinde pazjena's per bewirking.

De lengde van de [http://meta.wikimedia.org/wiki/Help:Job_queue job queue] is '''\$7'''.",
'userstatstext' => "D'r zeen '''$1''' geregistreerde gebroekers; '''$2''' (of '''$4''') hievan zeen systeemwèrkers (zuug $3).",

'disambiguations'     => "Verdudelikingspazjena's",
'disambiguationspage' => 'Template:Verdudeliking',

'doubleredirects'     => 'Dobbel redirects',
'doubleredirectstext' => '<b>Kiek oet:</b> In dees lies kanne redirects sjtaon die neet dao-in toeshure. Dat kump meistal doordat nao de #REDIRECT nog anger links op de pazjena sjtaon.<br />
Op eder raegel vings te de ierste redirectpazjena, de twiede redirectpazjena en de iesjte raegel van de twiede redirectpazjena. Normaal bevat dees litste de pazjena woe de iesjte redirect naotoe zouw mótte verwieze.',

'brokenredirects'     => 'Gebraoke redirects',
'brokenredirectstext' => "De óngersjtaonde redirectpazjena's bevatte 'n redirect nao 'n neet-besjtaonde pazjena.",

# Miscellaneous special pages
'nbytes'                  => '$1 bytes',
'ncategories'             => '$1 categories',
'nlinks'                  => '$1 verwiezinge',
'nrevisions'              => '$1 revisions',
'nviews'                  => '$1 kier bekeke',
'lonelypages'             => "Weispazjena's",
'uncategorizedpages'      => "Ongekattegoriseerde pazjena's",
'uncategorizedcategories' => 'Ongekattegoriseerde kattegorië',
'unusedcategories'        => 'Óngebroekde kategorieë',
'unusedimages'            => 'Ongebroekde aafbeildinge',
'popularpages'            => 'Populaire artikels',
'wantedcategories'        => 'Gewunsjde categorieë',
'wantedpages'             => "Gewunsjde pazjena's",
'mostlinked'              => "Meis gelinkde pazjena's",
'mostcategories'          => 'Artikele mit de meiste kategorieë',
'mostimages'              => 'Meis gelinkde aafbeildinge',
'mostrevisions'           => 'Artikele mit de meiste bewirkinge',
'allpages'                => "Alle pazjena's",
'randompage'              => 'Willekäörige pazjena',
'shortpages'              => 'Korte artikele',
'longpages'               => 'Lang artikele',
'deadendpages'            => "Doedloupende pazjena's",
'listusers'               => 'Lies van gebroekers',
'specialpages'            => "Speciaal pazjena's",
'spheading'               => "Speciaal pazjena's",
'rclsub'                  => '(van pazjena\'s woe "$1" heen verwiest)',
'newpages'                => "Nuuj pazjena's",
'ancientpages'            => 'Artikele die lank neet bewèrk zeen',
'move'                    => 'Verplaats',
'movethispage'            => 'Verplaats dees pazjena',
'unusedimagestext'        => "<p>Lèt op! 't Zou kinne dat er via een directe link verweze weurt nao 'n aafbeilding, bevoorbild vanoet 'n angesjtalige {{SITENAME}}. Het is daorom meugelijk dat 'n aafbeilding hie vermeld sjtit terwiel e toch gebroek weurt.",

# Book sources
'booksources' => 'Bookwinkele',

'categoriespagetext' => 'De wiki haet de volgende categorieë:',
'data'               => 'Gegaeves',
'alphaindexline'     => '$1 nao $2',
'version'            => 'Versie',

# Special:Log
'specialloguserlabel'  => 'Gebroeker:',
'speciallogtitlelabel' => 'Titel:',
'log'                  => 'Logbeuk',
'alllogstext'          => "Dit is 't gecombineerd logbook. De kins ouch 'n bepaald logbook keze, of filtere op gebroekersnaam of  pazjena.",

# Special:Allpages
'nextpage'          => 'Volgende pazjena ($1)',
'allpagesfrom'      => "Tuin pazjena's vanaaf:",
'allarticles'       => 'Alle artikele',
'allinnamespace'    => "Alle pazjena's (naamruumde $1)",
'allnotinnamespace' => "Alle pazjena's (neet in naamruumde $1)",
'allpagesprev'      => 'Veurige',
'allpagesprefix'    => "Tuin pazjena's mèt 't veurvoogsel:",

# E-mail user
'mailnologin'     => 'Gein e-mailadres bekènd veur deze gebroeker',
'mailnologintext' => "De mos zien [[Special:Userlogin|aangemèld]] en 'n gèldig e-mailadres in bie dien [[Special:Preferences|veurkäöre]] höbbe ingevuld om mail nao anger gebroekers te sjture.",
'emailuser'       => "Sjik deze gebroeker 'nen e-mail",
'emailpage'       => "Sjik gebroeker 'nen e-mail",
'emailpagetext'   => "As deze gebroeker e geljig e-mailadres heet opgegaeve dan kant geer via dit formuleer e berich sjikke. 't E-mailadres wat geer heet opgegeve bie eur veurkäöre zal as versjikker aangegaeve waere.",
'noemailtitle'    => 'Gein e-mailadres bekènd veur deze gebroeker',
'noemailtext'     => 'Deze gebroeker haet gein gèldig e-mailadres opgegaeve of haet dees functie oetgesjakeld.',
'emailsubject'    => 'Óngerwerp',
'emailmessage'    => 'Berich',
'emailsend'       => 'Sjik berich',
'emailsent'       => 'E-mail sjikke',
'emailsenttext'   => 'Die berich is versjik.',

# Watchlist
'watchlist'            => 'Volglies',
'mywatchlist'          => 'Volglies',
'nowatchlist'          => "D'r sjtit niks op dien volglies.",
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
'wlheader-showupdated' => "* Pazjena's die verangerd zeen saers doe ze veur 't lètste bezaogs sjtaon '''vet'''",
'watchmethod-recent'   => "Controleer recènte verangere veur gevolgde pazjena's",
'watchmethod-list'     => "controlere van gevolgde pazjena's veur recènte verangeringe",
'watchlistcontains'    => "Dien volglies bevat $1 pazjena's.",
'wlnote'               => 'Hieonger de lètste $1 verangeringe van de lètste <b>$2</b> oor.',
'wlshowlast'           => 'Tuin lètste $1 ore $2 daag $3',

'enotif_mailer'      => '{{SITENAME}} notificatiemail',
'enotif_reset'       => "Mèrk alle bezochde pazjena's aan.",
'enotif_newpagetext' => "DIt is 'n nuuj pazjena.",
'changed'            => 'verangerd',
'created'            => 'aangemaak',
'enotif_subject'     => 'De {{SITENAME}}pazjena $PAGETITLE is $CHANGEDORCREATED door $PAGEEDITOR',
'enotif_lastvisited' => 'Zuug $1 veur al verangeringe saer dien lèste bezeuk.',
'enotif_body'        => 'Bèste $WATCHINGUSERNAME,

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
'deletepage'         => 'Pazjena wisse',
'confirm'            => 'Bevèstig',
'excontent'          => "inhawd waor: '$1'",
'excontentauthor'    => "inhawd waor: '$1' (aangemaak door [[Special:Contributions/$2|$2]])",
'exbeforeblank'      => "inhawd veur 't wisse waor: '$1'",
'exblank'            => 'pazjena waor laeg',
'confirmdelete'      => 'Bevèstig wisse',
'deletesub'          => '(Wisse "$1")',
'confirmdeletetext'  => "De sjteis op 't punt 'n pazjena of e plaetje veur ummer te wisse. Dit haolt allen inhawd en historie oet de database eweg. Bevèstig hieónger dat dit welzeker dien bedoeling is, dats te de gevolge begrieps.",
'actioncomplete'     => 'Actie voltoeid',
'deletedtext'        => '"$1" is gewis. Zuug $2 vuur \'n euverzich van recèntelik gewisde pazjena\'s.',
'deletedarticle'     => '"$1" is gewis',
'dellogpage'         => 'Wislogbook',
'dellogpagetext'     => "Hie volg 'n lies van de meis recèntelik gewisde pazjena's en plaetjes.",
'deletionlog'        => 'Wislogbook',
'reverted'           => 'Iedere versie hersjtèld',
'deletecomment'      => 'Rae veur wisactie',
'rollbacklink'       => 'Trukdrieje',
'cantrollback'       => 'Trökdrejje van verangeringe neet meugelik: Dit artikel haet mer einen auteur.',
'alreadyrolled'      => "'t Is neet meugelik óm de lèste verangering van [[$1]]
door [[User:$2|$2]] ([[User talk:$2|euverlik]]) óngedaon te make. Emes angers haet de pazjena al hersjtèld of haet 'n anger bewèrking gedaon. 

De lèste bewèrking is gedaon door [[User:$3|$3]] ([[User talk:$3|euverlik]]).",
'editcomment'        => '\'t Bewirkingscommentair waor: "<i>$1</i>".', # only shown if there is an edit comment
'revertpage'         => 'Wieziginge door [[Special:Contributions/$2|$2]] ([[User_talk:$2|Euverlik]]) trukgedriejd tot de lètste versie door [[User:$1|$1]]',
'protectlogpage'     => "Logbook besjermde pazjena's",
'protectedarticle'   => '$1 besjermd',
'unprotectedarticle' => 'besjerming van $1 opgeheve',
'protectsub'         => '(Besjerme van "$1")',
'confirmprotect'     => 'Bevèstig besjerme',
'protectcomment'     => 'Rede veur besjerming',
'unprotectsub'       => '(Besjerming van "$1" opheve)',

# Undelete
'undelete'           => 'Verwiederde pazjena trukplaatse',
'undeletepage'       => "Verwiederde pazjena's bekieke en trukplaatse",
'undeletepagetext'   => "De ongersjtaande pazjena's zint verwiederd, meh bevinge zich nog sjteeds in 't archief, en kinne weure truukgeplaatsj.",
'undeleterevisions'  => "$1 versies in 't archief",
'undeletehistory'    => 'Als u een pagina terugplaatst, worden alle versies als oude versies teruggeplaatst. Als er al een nieuwe pagina met dezelfde naam is aangemaakt, zullen deze versies als oude versies worden teruggeplaatst, maar de huidige versie neet gewijzigd worden.',
'undeletebtn'        => 'Trökzètte!',
'undeletedarticle'   => '"$1" is truukgeplaatsj.',
'undeletedrevisions' => '$1 versies truukgeplaatsj',

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

# What links here
'whatlinkshere' => 'Links nao dees pazjena',
'notargettitle' => 'Gein doelpagina',
'notargettext'  => 'Ger hubt neet gezag veur welleke pagina ger deze functie wilt bekieke.',
'linklistsub'   => '(lies van verwiezinge)',
'linkshere'     => "De volgende pazjena's verwieze hieheen:",
'nolinkshere'   => "D'r zint gein pazjena's mit links hiehaer.",
'isredirect'    => 'redirect pazjena',

# Block/unblock
'blockip'            => 'Blokkeer dit IP-adres',
'blockiptext'        => "Gebroek 't óngerstjaondj formeleer óm sjrieftoegank van e zeker IP-adres te verbeje. Dit maag allein gedaon weure om vandalisme te veurkómme.",
'ipbsubmit'          => 'Blokkeer dit IP-adres',
'ipboptions'         => '2 hours,1 day,3 days,1 week,2 weeks,1 month,3 months,6 months,1 year,infinite',
'badipaddress'       => "'t IP-adres haet 'n ongeldige opmaak.",
'blockipsuccesssub'  => 'Blokkaad gelök',
'blockipsuccesstext' => '\'t IP-adres "$1" is geblokkeerd.<br />
Zuug de [[Special:Ipblocklist|lies van geblokkeerde IP-adresse]].',
'unblockip'          => 'Deblokkeer IP adres',
'unblockiptext'      => 'Gebroek het ongersjtaonde formeleer om weer sjrieftoegang te gaeve aan e geblokkierd IP adres.',
'ipusubmit'          => 'Deblokkeer dit IP-adres.',
'ipblocklist'        => 'Lies van geblokkeerde IP-adressen',
'blocklistline'      => 'Op $1 blokkeerde $2 $3 ($4)',
'blocklink'          => 'Blokkeer',
'contribslink'       => 'biedrages',
'autoblocker'        => 'Ómdets te \'n IP-adres deils mit "$1" (geblokkeerd mit raeje "$2") bis te automatisch geblokkeerd.',
'blocklogpage'       => 'Blokkeerlogbook',
'blocklogentry'      => '"$1" is geblokkeerd veur d\'n tied van $2',
'blocklogtext'       => "Dit is 'n log van blokkades van gebroekers. Automatisch geblokkeerde IP-adresse sjtoon hie neet bie. Zuug de [[Special:Ipblocklist|Lies van geblokkeerde IP-adresse]] veur de lies van op dit mement wèrkende blokkades.",
'proxyblockreason'   => "Dien IP-adres is geblokkeerd ómdat 't 'n aope proxy is. Contacteer estebleef diene internet service provider of technische óngersjteuning en informeer ze euver dit serjeus veiligheidsprebleem.",
'proxyblocksuccess'  => 'Klaor.',

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
'movepage'               => 'Verplaats pazjena',
'movepagetext'           => "Mit 't óngersjtaond formuleer kans te 'n pazjena verplaatse. De historie van de ouw pazjena zal nao de nuuj mitgaon. De ouwe titel zal automatisch 'ne redirect nao de nuuj pazjena waere. Doe kans 'n pazjena allein verplaatse, es gein pazjena besjteit mit de nuje naam, of es op die pazjena allein 'ne redirect zónger historie sjteit.",
'movepagetalktext'       => "De biebehurende euverlikpazjena weurt ouch verplaats, mer '''neet''' in de volgende gevalle:
* es de pazjena nao 'n anger [[Project:Naamruumde|naamruumde]] verplaats weurt
* es al 'n euverlikpazjena besjteit ónger de angere naam
* es doe 't óngersjtaond vekske neet aanvinks",
'movearticle'            => 'Verplaats pazjena',
'movenologin'            => 'Neet aangemèld',
'movenologintext'        => "Veur 't verplaatsje van 'n pazjena mos e zien [[Special:Userlogin|aangemèld]].",
'newtitle'               => 'Nao de nuje titel',
'movepagebtn'            => 'Verplaats pazjena',
'pagemovedsub'           => 'De verplaatsing is gelök',
'articleexists'          => "Dao is al 'n pazjena mit dees titel of de titel is óngeljig. <br />Kees estebleef 'n anger titel.",
'talkexists'             => "De pazjena zelf is verplaats, meh de euverlikpazjena kós neet verplaats waere, ómdat d'r al 'n euverlikpazjena mit de nuje titel besjtóng. Combineer de euverlikpazjena's estebleef mit de hand.",
'movedto'                => 'verplaats nao',
'movetalk'               => 'Verplaats de euverlikpazjena ouch.',
'talkpagemoved'          => 'De biebehurende euverlikpazjena is ouch verplaats.',
'talkpagenotmoved'       => 'De biebehurende euverlikpazjena is <strong>neet</strong> verplaats.',
'1movedto2'              => '[[$1]] verplaats nao [[$2]]',
'1movedto2_redir'        => '[[$1]] euver redirect verplaats nao [[$2]]',
'movelogpage'            => "Logbook verplaatsde pazjena's",
'movelogpagetext'        => "Dit is de lies van verplaatsde pazjena's.",
'movereason'             => 'Lèk oet woeróm',
'revertmove'             => 'trökdrieje',
'delete_and_move'        => 'Wis en verplaats',
'delete_and_move_text'   => '==Wisse vereis==

De doeltitel "[[$1]]" besjteit al. Wils te dit artikel wisse óm ruumde te make veur de verplaatsing?',
'delete_and_move_reason' => 'Gewis óm artikel te kónne verplaatse',

# Export
'export' => "Exporteer pazjena's",

# Namespace 8 related
'allmessages'               => 'Alle systeemberichte',
'allmessagesdefault'        => 'Obligaten teks',
'allmessagescurrent'        => 'Hujige teks',
'allmessagestext'           => "Dit is 'n lies van alle systeemberichte besjikbaar in de MediaWiki:-naamruumde.",
'allmessagesnotsupportedDB' => 'special:Allmessages neet óngersjteundj ómdat wgUseDatabaseMessages oet (off) sjteit.',

# Thumbnails
'thumbnail-more' => 'Vergroete',
'missingimage'   => '<b>Plaetsje neet besjikbaar</b><br /><i>$1</i>',
'filemissing'    => 'Besjtand ontbrik',

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

# Attribution
'anonymous'        => 'Anoniem(e) gebroeker(s) van {{SITENAME}}',
'siteuser'         => '{{SITENAME}} gebroeker $1',
'lastmodifiedatby' => "Dees pazjena is 't litst verangert op $2, $1 doer $3.", # $1 date, $2 time, $3 user
'siteusers'        => '{{SITENAME}} gebroekers(s) $1',
'creditspage'      => 'Sjrievers van dees pazjena',

# Spam protection
'subcategorycount'     => 'Dees categorie haet {{PLURAL:$1|ein subcategorie|$1 subcategorieë}}.',
'categoryarticlecount' => 'Dao zeen $1 artikele in dees categorie.',

# Math options
'mw_math_png'    => 'Ummer PNG rendere',
'mw_math_simple' => 'HTML in erg simpele gevalle en angesj PNG',
'mw_math_html'   => 'HTML woe meugelik en angesj PNG',
'mw_math_source' => 'Laot de TeX code sjtaon (vuur tèksbrowsers)',
'mw_math_modern' => 'Aangeroaje vuur nuui browsers',
'mw_math_mathml' => 'MathML woe meugelik (experimenteil)',

# Image deletion
'deletedrevision' => 'Aw versie $1 gewis',

# Browsing diffs
'previousdiff' => '← Gank nao de vurrige diff',
'nextdiff'     => 'Gank nao de volgende diff →',

# Media information
'imagemaxsize' => "Bepèrk plaetsjes op de besjrievingspazjena's van aafbeildinge tot:",
'thumbsize'    => 'Thumbnail size :',

# Special:Newimages
'newimages' => 'Nuuj plaetjes',
'noimages'  => 'Niks te zeen.',

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

# E-mail address confirmation
'confirmemail'            => 'Bevèstig e-mailadres',
'confirmemail_text'       => "Deze wiki vereis dats te dien e-mailadres instèls iedats te e-mailfuncties
gebroeks. Klik op de knop hieónger óm e bevèstegingsberich nao dien adres te
sjikke. D'n e-mail zal 'ne link mèt 'n code bevatte; eupen de link in diene
browser óm te bevestege dat dien e-mailadres werk.",
'confirmemail_send'       => "Sjik 'n bevèstegingcode",
'confirmemail_sent'       => 'Bevèstegingsberich versjik.',
'confirmemail_sendfailed' => "Kós 't bevèstegingsberich neet versjikke. Zuug dien e-mailadres nao op óngeljige karakters.",
'confirmemail_invalid'    => 'Óngeljige bevèstigingscode. De code is meugelik verloupe.',
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
'scarytranscludefailed' => '[Sjabloon $1 kós neet opgehaold waere; sorry]',

# Delete conflict
'deletedwhileediting' => 'Waorsjoewing: dees pazjena is gewis naodats doe bis begós mit bewirke.',
'confirmrecreate'     => "Gebroeker [[User:$1|$1]] ([[User talk:$1|euverlèk]]) heet dit artikel gewis naodats doe mèt bewirke begós mèt de rae:
: ''$2''
Bevèsteg estebleef dats te dees pazjena ech obbenuujts wils make.",
'recreate'            => 'Pazjena obbenuujts make',

# action=purge
'confirm_purge' => 'Wils te de buffer vaan dees paas wisse?

$1',

);
