<?php
/** Limburgian (Limburgs)
  *
  * @addtogroup Language
 */

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
'tog-usenewrc'                => 'Oetgebreide recènte vervangeringe (neet vuur alle browsers)',
'tog-numberheadings'          => 'Köpkes automatisch nummere',
'tog-showtoolbar'             => 'Laot edit toolbar zeen',
'tog-editondblclick'          => "Bewirk pazjena's bie 'ne dubbelklik (JavaScript)",
'tog-editsection'             => 'Bewirke van secties via [bewirke] links',
'tog-editsectiononrightclick' => 'Sècties bewirke mit inne rechtermoesklik op sèctietitels (JavaScript)',
'tog-showtoc'                 => "Inhawdsopgaaf vuur pazjena's mit mië as 3 köpkes",
'tog-rememberpassword'        => "Wachwaord ónthauwe bie 't aafmèlde",
'tog-editwidth'               => 'Edit boks haet de vol breidte',
'tog-watchdefault'            => "Voog pazjena's die se bewirks toe aan dien volglies",
'tog-minordefault'            => 'Merkeer sjtandaard alle bewirke as klein',
'tog-previewonfirst'          => 'Preview laote zien bie de iesjte bewirking',
'tog-nocache'                 => 'Pazjena cache oetzitte',
'tog-fancysig'                => 'Handjteikening zónger link nao dien gebroekerspazjena',

# Dates
'sunday'    => 'zondig',
'monday'    => 'maondig',
'tuesday'   => 'dinsdig',
'wednesday' => 'goonsdag',
'thursday'  => 'donderdig',
'friday'    => 'vriedig',
'saturday'  => 'zaoterdig',
'january'   => 'jannewarie',
'february'  => 'fibberwari',
'march'     => 'maart',
'april'     => 'april',
'may_long'  => 'mei',
'june'      => 'juni',
'july'      => 'juli',
'august'    => 'augustus',
'september' => 'september',
'october'   => 'oktober',
'november'  => 'november',
'december'  => 'december',
'jan'       => 'jan',
'feb'       => 'feb',
'mar'       => 'mrt',
'apr'       => 'apr',
'may'       => 'mei',
'jun'       => 'jun',
'jul'       => 'jul',
'aug'       => 'aug',
'sep'       => 'sep',
'oct'       => 'okt',
'nov'       => 'nov',
'dec'       => 'dec',

# Bits of text used by many pages
'categories'      => 'Categorieë',
'pagecategories'  => '{{PLURAL:$1|Categorie|Categorieë}}',
'category_header' => 'Artikele in categorie "$1"',
'subcategories'   => 'Subkattegorië',

'mainpagetext' => 'Wiki software succesvol geïnsjtalleerd.',

'about'          => 'Info',
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

'errorpagetitle'    => 'Fout',
'returnto'          => 'Truuk nao $1.',
'tagline'           => 'Van {{SITENAME}}',
'help'              => 'Hulp',
'search'            => 'Zeuke',
'searchbutton'      => 'Zeuke',
'go'                => 'OK',
'searcharticle'     => 'OK',
'history'           => 'Historie',
'history_short'     => 'Historie',
'printableversion'  => 'Printer-vruntelike versie',
'edit'              => 'Bewirk',
'editthispage'      => 'Pazjena bewirke',
'delete'            => 'Wisse',
'deletethispage'    => 'Wisse',
'protect'           => 'Besjerm',
'protectthispage'   => 'Beveilige',
'unprotect'         => 'vriegaeve',
'unprotectthispage' => 'Besjerming opheffe',
'newpage'           => 'Nuuj pazjena',
'talkpage'          => 'euverlikpazjena',
'specialpage'       => 'Speciaal Pazjena',
'personaltools'     => 'Persoenlike hulpmiddele',
'articlepage'       => 'Artikel',
'talk'              => 'Euverlèk',
'toolbox'           => 'Gereidsjapskis',
'userpage'          => 'gebroekerspazjena',
'imagepage'         => 'Besjrievingspazjena',
'viewtalkpage'      => 'Bekiek euverlik',
'otherlanguages'    => 'Anger tale',
'redirectedfrom'    => '(Doorverweze van $1)',
'redirectpagesub'   => 'Redirectpazjena',
'lastmodifiedat'    => "Dees pazjena is 't litst verangert op $2, $1.", # $1 date, $2 time
'viewcount'         => 'Dees pazjena is $1 kier bekeke.',
'protectedpage'     => 'Beveiligde pazjena',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Euver {{SITENAME}}',
'aboutpage'         => '{{ns:project}}:Info',
'bugreports'        => 'Fouterapportaasj',
'bugreportspage'    => '{{ns:project}}:Fouterapportaasj',
'copyright'         => 'De inhawd is besjikbaar ónger de $1.',
'copyrightpagename' => '{{SITENAME}} auteursrechte',
'copyrightpage'     => '{{ns:project}}:Auteursrechte',
'currentevents'     => "In 't nuujs",
'currentevents-url' => "In 't nuujs",
'disclaimers'       => 'Aafwiezinge aansjprakelikheid',
'disclaimerpage'    => '{{SITENAME}}: Algemein aafwiezing aansjprakelikheid',
'edithelp'          => 'Hulp bie bewirke',
'edithelppage'      => '{{ns:help}}:Instructies',
'faqpage'           => '{{ns:project}}:Veulgestjilde vraoge',
'helppage'          => '{{ns:project}}:Help',
'mainpage'          => 'Huidpazjena',
'portal'            => 'Gebroekersportaol',
'portal-url'        => '{{ns:project}}:Gebroekersportaol',
'privacypage'       => '{{ns:project}}:Privacy_policy',
'sitesupport'       => 'Donaties',
'sitesupport-url'   => '{{ns:project}}:Gifte',

'badaccess' => 'Toeganksfout',

'retrievedfrom'       => 'Aafkómstig van "$1"',
'youhavenewmessages'  => 'Doe höbs $1 ($2).',
'newmessageslink'     => 'nuuj berichte',
'newmessagesdifflink' => 'Lèste verangering',
'editsection'         => 'bewirk',
'toc'                 => 'Inhawd',
'hidetoc'             => 'verberg',
'thisisdeleted'       => '$1 bekieke of trökzètte?',
'restorelink'         => '$1 verwiederde versies',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'Artikel',
'nstab-user'      => 'Gebroeker',
'nstab-image'     => 'Aafbeilding',
'nstab-mediawiki' => 'Berich',
'nstab-template'  => 'Sjabloon',
'nstab-category'  => 'Kategorie',

# Main script and global functions
'nosuchaction'      => 'Gevraagde handeling bestjit neet',
'nosuchactiontext'  => 'De door de URL gespecifieerde handeling wordt neet herkend door de MediaWiki software',
'nosuchspecialpage' => "D'r besjteit gein speciaal pazjena mit deze naam",
'nospecialpagetext' => 'U heeft een speciale pagina aangevraagd die neet wordt herkend door de MediaWiki software',

# General errors
'error'           => 'Fout',
'databaseerror'   => 'Databasefout',
'dberrortext'     => 'Bie \'t zeuke is \'n syntaxfout in de database opgetreje.
Dit kint zien veroorzaak door \'n óngeljige zeukactie (zuug $5),
of \'t duujt op \'n fout in de software. 
De lètste zeukpoeging in de database waor:
<blockquote><tt>$1</tt></blockquote>
vanoet de functie "<tt>$2</tt>".
MySQL gaof de foutmèlling "<tt>$3: $4</tt>".',
'dberrortextcl'   => 'Dao is \'n syntaxfout opgetreje bie \'t zeuke in de database.
De lèste opgevraogde zeukactie waor:
"$1"
vanoet de functie "$2".
MySQL brach fout "$3" nao veure: "$4"',
'noconnect'       => 'Verbinden met de database op $1 was neet mogelijk',
'nodb'            => 'Selectie van database $1 neet mogelijk',
'cachederror'     => "Dit is 'n gearsjiveerde kopie van de gevraogde pazjena, en is mesjien neet gans actueel.",
'readonly'        => 'Database geblokkeerd',
'enterlockreason' => "Gaef 'n rae veur de blokkering en wie lank 't dinkelik zal dore. De ingegaeve rae zal aan de gebroekers getuind waere.",
'readonlytext'    => 'De database van {{SITENAME}} is momenteel gesloten voor nieuwe bewerkingen en wijzigingen, waarschijnlijk voor bestandsonderhoud.
De verantwoordelijke systeembeheerder gaf hiervoor volgende reden op:
<p>$1',
'missingarticle'  => 'De database haet \'n pazjenatèks ("$1") die \'t zou motte vinge neet gevonge. Dit is gein fout in de database, mer waarscjienlik in de software. Meld dit estebleef aan inne adminstrator, mit vermèlding van de URL.',
'internalerror'   => 'Interne fout',
'filecopyerror'   => 'Besjtand "$1" nao "$2" kopiëre neet mugelik.',
'filerenameerror' => 'Verangere van de titel van \'t besjtand "$1" in "$2" neet meugelik.',
'filedeleteerror' => 'Kos bestjand "$1" neet weghaole.',
'filenotfound'    => 'Kos bestjand "$1" neet vinge.',
'unexpected'      => 'Onverwachte waarde: "$1"="$2".',
'formerror'       => 'Fout: kos formeleer neet verzende',
'badarticleerror' => 'Dees hanjeling kint neet weure oetgeveurd op dees pazjena.',
'cannotdelete'    => 'Kós de pazjena of aafbeilding neet wisse.',
'badtitle'        => 'Óngeljige pazjenatitel',
'badtitletext'    => 'De opgevraogde pazjena is neet besjikbaar of laeg.',
'perfdisabled'    => 'Om te veurkomme dat de database weurd euverbelast is dees pazjena allein tusje 03:00 en 15:00 (Wes-Europiese zoemertied) besjikbaar.',
'perfcached'      => 'De volgende data is gecachet en is mesjien neet gans up to date:',
'viewsource'      => 'Bekiek brónteks',

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
'loginproblem'               => "<b>D'r is 'n prebleim mèt 't aanmèlde.</b><br />Probeer estebleef nog es.",
'alreadyloggedin'            => '<span style="color:#ff0000"><b>Gebroeker $1, de bis al aangemèld.</b></span><br />',
'login'                      => 'Aanmèlde',
'loginprompt'                => "Diene browser mót ''cookies'' acceptere óm in te logge op {{SITENAME}}.",
'userlogin'                  => 'Aanmèlde',
'logout'                     => 'Aafmèlde',
'userlogout'                 => 'Aafmèlde',
'nologin'                    => 'Höbs te nog geine gebroekersnaam? $1.',
'nologinlink'                => "Maak 'ne gebroekersnaam aan",
'createaccount'              => 'Nuuj gebroekersprofiel aanmake.',
'gotaccount'                 => "Höbs te al 'ne gebroekersnaam? $1.",
'createaccountmail'          => 'per e-mail',
'badretype'                  => 'De ingeveurde wachwäörd versjille vanein.',
'userexists'                 => "De gebroekersnaam dae se höbs ingeveurd weurt al gebroek. Kees estebleef 'n anger naam.",
'youremail'                  => 'Dien e-mailadres',
'username'                   => 'Gebroekersnaam:',
'uid'                        => 'Gebroekersnómmer:',
'yourrealname'               => 'Dienen echte naam*',
'yourlanguage'               => 'Taal van de gebroekersinterface',
'yourvariant'                => 'Taalvariant',
'yournick'                   => "Diene bienaam (veur ''handjteikeninge'')",
'badsig'                     => 'Óngeljige roew handjteikening; zuug de HTML-tags nao.',
'loginerror'                 => 'Inlogfout',
'prefs-help-email'           => '* E-mail (optioneel): Hiedoor kan me contak mit diech opnumme zónger dats te dien identiteit hoofs vrie te gaeve.',
'noname'                     => "De mos 'n gebroekersnaam opgaeve.",
'loginsuccesstitle'          => 'Aanmèlde geluk.',
'loginsuccess'               => 'De bis noe es "$1" aangemèld bie {{SITENAME}}.',
'nosuchuser'                 => 'Er bestaat geen gebroeker met de naam "$1". Controleer uw spelling, of gebruik onderstaand formulier om een nieuw gebroekersprofiel aan te maken.',
'wrongpassword'              => "'t Ingegaeve wachwaord is neet zjus. Perbeer 't obbenuujts.",
'wrongpasswordempty'         => "'t Ingegaeve wachwoord waor laeg. Perbeer 't obbenuujts.",
'mailmypassword'             => "Sjik mich 'n nuuj wachwaord",
'passwordremindertitle'      => 'Wachwaordherinnering van {{SITENAME}}',
'passwordremindertext'       => 'Emes (waarsjienliek dich zelf) vanaaf IP-adres $1 haet verzoch u een nieuw wachtwoord voor {{SITENAME}} toe te zenden ($4). Het nieuwe wachtwoord voor gebroeker "$2" is "$3". Advies: nu aanmelden en uw wachtwoord wijzigigen.',
'noemail'                    => 'D\'r is gein geregistreerd e-mailadres veur "$1".',
'passwordsent'               => 'D\'r is \'n nuui wachwaord verzonde nao \'t e-mailadres dat geregistreerd sjtit veur "$1".
Gelieve na ontvangst opnieuw aan te melden.',
'eauthentsent'               => "Dao is 'ne bevèstigingse-mail nao 't genomineerd e-mailadres gesjik.
Iedat anger mail nao dat account versjik kan weure, mós te de insjtructies in daen e-mail volge,
óm te bevèstige dat dit wirkelik dien account is.",
'mailerror'                  => "Fout bie 't versjture van mail: $1",
'acct_creation_throttle_hit' => "Sorry, de höbs al $1 accounts aangemak. De kins d'r gein mië aanmake.",
'emailauthenticated'         => 'Dien e-mailadres is op $1 geauthentiserd.',
'emailnotauthenticated'      => 'Dien e-mailadres is nog neet geauthentiseerd. De zals gein
e-mail óntvange veur alle volgende toepassinge.',
'emailconfirmlink'           => 'Bevèstig dien e-mailadres',

# Edit page toolbar
'bold_sample'    => 'Vetten teks',
'bold_tip'       => 'Vetten teks',
'italic_sample'  => 'Italic tèks',
'italic_tip'     => 'Italic tèks',
'link_sample'    => 'Link titel',
'link_tip'       => 'Interne link',
'extlink_sample' => 'http://www.example.com link titel',
'extlink_tip'    => 'Externe link (mit de http:// prefix)',
'math_tip'       => 'Wiskundige formule (LaTeX)',

# Edit pages
'summary'              => 'Samevatting',
'minoredit'            => "Dit is 'n klein verangering",
'watchthis'            => 'Volg dees pazjena',
'savearticle'          => 'Pazjena opsjlaon',
'preview'              => 'Naokieke',
'showpreview'          => 'Bekiek dees bewirking',
'showdiff'             => 'Toen verangeringe',
'anoneditwarning'      => "You are not logged in. Your IP address will be recorded in this page's edit history.",
'blockedtitle'         => 'Gebroeker is geblokkeerd',
'blockedtext'          => 'Diene gebroekersnaam of IP-adres is geblokkeerd door $1. De opgegaeve raeje:<br />$2<br />De kins veur euverlik kontak opnumme mit de [[{{MediaWiki:grouppage-sysop}}|systeemwèrkers]].

Your IP address is $3. Please include this address in any queries you make.',
'whitelistedittitle'   => 'Geer mót óch inlogke óm te bewirke',
'whitelistedittext'    => 'Geer mót uch $1 óm pajzená te bewirke.',
'whitelistreadtitle'   => 'Geer mót óch inlogke óm dit te kónne laeze',
'whitelistreadtext'    => "Geer mót óch [[Special:Userlogin|inlogke]] óm pazjena's te laeze.",
'whitelistacctitle'    => 'Geer maag gein account aanmake',
'whitelistacctext'     => 'Óm accounts op dees wiki aan te make mót geer [[Special:Userlogin|ingelog]] zeen en de zjuste permissies höbbe.',
'loginreqlink'         => 'inglogge',
'loginreqpagetext'     => 'De mos $1 om anger pazjenas te bekieke.',
'accmailtitle'         => 'Wachwaord versjtuurd.',
'accmailtext'          => "'t Wachwaord veur '$1' is nao $2 versjtuurd.",
'newarticle'           => '(Nuuj)',
'newarticletext'       => "De höbs 'ne link gevolg nao 'n pazjena die nog neet besjteit. 
Type in de box hiejónger óm de pazjena te beginne (zuug de [[Help:Contents|helppazjena]] veur mier informatie). Es te hie per óngelök terech bis gekómme, klik dan op de '''trök'''-knóp van diene browser.",
'anontalkpagetext'     => "----''Dit is de euverlikpazjena veur 'ne anonieme gebroeker dae nog gein account haet aangemak of dae 't neet gebroek. Daorom gebroeke v'r 't IP adres om de gebroeker te identificere. Dat adres kint weure gedeild doer miedere gebroekers. As e 'ne anonieme gebroeker bis en de höbs 't geveul dat 'r onrillevante commentare aan dich gericht zint, kins e 't biste [[Special:Userlogin|'n account crëere of inlogge]] om toekomstige verwarring mit angere anonieme gebroekers te veurkomme.''",
'noarticletext'        => "(Dees pazjena bevat op 't moment gein teks)",
'clearyourcache'       => "'''Lèt op:''' Nao 't opsjlaon mós te diene browserbuffer wisse óm de verangeringe te zeen: '''Mozilla:''' klik ''Reload'' (of ''Ctrl-R''), '''Firefox / IE / Opera:''' ''Ctrl-F5'', '''Safari:''' ''Cmd-R'', '''Konqueror''' ''Ctrl-R''.",
'updated'              => '(Biegewèrk)',
'note'                 => '<strong>Opmirking:</strong>',
'previewnote'          => "Lèt op: dit is 'n controlepazjena; dien tèks is nog neet opgesjlage!",
'previewconflict'      => "Dees versie toent wie de tèks in 't bôvesjte vèld oet git zeen es e zouws opsjlaon.",
'editing'              => 'Bewirkingspazjena: $1',
'editinguser'          => 'Bewirkingspazjena: $1',
'editingsection'       => 'Bewirke van sectie van $1',
'editingcomment'       => 'Bewirk $1 (commentair)',
'editconflict'         => 'Bewirkingsconflik: $1',
'explainconflict'      => "Jemes angers haet dees pazjena verangerd naodats doe aan dees bewèrking bis begos. 't Ierste teksveld tuint de hujige versie van de pazjena. De mós dien eige verangeringe dao-in inpasse. Allein d'n tèks in 't ierste teksveld weurt opgesjlaoge wens te noe op \"Pazjena opsjlaon\" duujs.<br />",
'yourtext'             => 'Euren teks',
'storedversion'        => 'Opgesjlage versie',
'nonunicodebrowser'    => '<strong>WAARSJUWING: Diene browser is voldit neet aan de unicode sjtandaarde, gebroek estebleef inne angere browser veurdas e artikele gis bewirke.</strong>',
'editingold'           => "<strong>WAARSJUWING: De bis 'n aw versie van dees pazjena aan 't bewirke. Es e dees bewirking opjsleis, gaon alle verangeringe die na dees versie zien aangebrach verlore.</strong>",
'yourdiff'             => 'Verangeringe',
'copyrightwarning'     => "Opgelèt: Alle biedrage aan {{SITENAME}} weure geach te zeen vriegegaeve ónger de $2 (zuug $1 veur details). Wens te neet wils dat dienen teks door angere bewirk en versjpreid weurt, kees dan neet veur 'Pazjena opsjlaon'.<br /> Hiebie belaofs te ós ouch dats te dees teks zelf höbs gesjreve, of höbs euvergenómme oet 'n vriej, openbaar brón.<br /> <strong>GEBROEK GEI MATERIAAL DAT BESJIRMP WEURT DOOR AUTEURSRECH, BEHAUVE WENS TE DAO TOESJTÖMMING VEUR HÖBS!</strong>",
'copyrightwarning2'    => "Mèrk op dat alle biedrages aan {{SITENAME}} kinne weure verangerd, aangepas of weggehaold door anger luuj. As te neet wils dat dienen tèks zoemer kint weure aangepas mós te 't hie neet plaatsje.<br />
De beluifs ós ouch dats te dezen tèks zelf höbs gesjreve, of gekopieerd van 'n brón in 't publiek domein of get vergliekbaars (zuug $1 veur details).
<strong>HIE GEIN AUTEURSRECHTELIK BESJIRMP WERK ZÓNGER TOESJTUMMING!</strong>",
'longpagewarning'      => "WAARSJOEWING: Dees pazjena is $1 kilobytes lank; 'n aantal browsers kint probleme höbbe mit 't verangere van pazjena's in de buurt van of groeter es 32 kB. Kiek ofs te sjtökker van de pazjena mesjiens kins verplaatse nao 'n nuuj pazjena.",
'readonlywarning'      => "WAARSJUWING: De database is vasgezèt veur ongerhoud, dus op 't mement kins e dien verangeringe neet opsjlaon. De kins dien tèks 't biste opsjlaon in 'n tèksbesjtand om 't later hie nog es te prebere.",
'protectedpagewarning' => 'WAARSJUWING:  Dees pazjena is besjermd zoedat ze allein doer gebroekers mit administratorrechte kint weure verangerd.',
'templatesused'        => 'Sjablone gebroek in dees pazjena:',
'edittools'            => 'literal translation',

# History pages
'revhistory'          => 'Bewirkingshistorie',
'nohistory'           => 'Dees pazjena is nog neet bewirk.',
'revnotfound'         => 'Wieziging neet gevonge',
'revnotfoundtext'     => 'De opgevraogde aw versie van dees pazjena is verzjwónde. Kontroleer estebleef de URL dieste gebroek höbs óm nao dees pazjena te gaon.',
'loadhist'            => "Bezig met 't laje van de pazjenahistorie",
'currentrev'          => 'Hujige versie',
'revisionasof'        => 'Versie op $1',
'previousrevision'    => '← Awwer versie',
'currentrevisionlink' => 'zuug hujige versie',
'cur'                 => 'hujig',
'next'                => 'volgende',
'last'                => 'vörrige',
'histlegend'          => 'Verklaoring aafkortinge: (wijz) = versjil mit actueile versie, (vörrige) = versjil mit vörrige versie, K = kleine verangering',
'deletedrev'          => '[gewis]',

# Diffs
'difference'                => '(Versjil tösje bewirkinge)',
'loadingrev'                => "bezig mit 't laje van de pazjenaversie",
'lineno'                    => 'Regel $1:',
'editcurrent'               => 'De hujige versie van dees pazjena bewirke.',
'selectnewerversionfordiff' => "Kees 'n nuuiere versie om te vergelieke",
'selectolderversionfordiff' => "Kees 'n auwere versie om te vergelieke",
'compareselectedversions'   => 'Vergeliek geselecteerde versies',

# Search results
'searchresults'         => 'Zeukresultate',
'searchresulttext'      => 'Veur mier informatie euver zeuke op {{SITENAME}}, zuug [[{{MediaWiki:helppage}}|Zeuke op {{SITENAME}}]].',
'searchsubtitleinvalid' => 'Voor zoekopdracht "$1"',
'badquery'              => 'Ónzjus geformuleerde zeukopdrach',
'badquerytext'          => "Diene zeukopdrach kós neet oetgeveurd weure. Waarsjienlik kump dit doordas te höbs geperbeerd e woord van minder as drie lètters te zeuke; dat weurt neet doer de software óngersjteundj.  't Is ouch meugelik dats te de zeuktèrm verkierd höbs ingegaeve.",
'matchtotals'           => 'De zeukterm "$1" is gevonge in $2 pazjenatitels en in de tèks van $3 pazjena\'s.',
'titlematches'          => 'Overeinkoms mèt volgende titels',
'notitlematches'        => 'Geen enkele paginatitel gevonden met de opgegeven zoekterm',
'textmatches'           => 'Euvereinkoms mèt artikelinhoud',
'notextmatches'         => 'Geen artikel gevonden met opgegeven zoekterm',
'prevn'                 => 'vörrige $1',
'nextn'                 => 'volgende $1',
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
'blanknamespace'        => '(huidnaamruumde)',

# Preferences page
'preferences'              => 'Veurkäöre',
'prefsnologin'             => 'Neet aangemèld',
'prefsnologintext'         => 'De mos zien [[Special:Userlogin|aangemèld]] om veurkäöre te kinne insjtèlle.',
'prefsreset'               => 'Sjtandaardveurkäöre hersjtèld.',
'qbsettings'               => 'Menubalkinsjtèllinge',
'qbsettings-none'          => 'Oetgesjakeld',
'qbsettings-fixedleft'     => 'Links vas',
'qbsettings-fixedright'    => 'Rechts vas',
'qbsettings-floatingleft'  => 'Links zwevend',
'qbsettings-floatingright' => 'Rechts zwevend',
'changepassword'           => 'Wachwaord verangere',
'skin'                     => '{{SITENAME}}-uterlik',
'math'                     => 'Mattemetik rendere',
'dateformat'               => 'Datumformaat',
'datedefault'              => 'Gein veurkäör',
'datetime'                 => 'Datum en tied',
'math_unknown_error'       => 'onbekènde fout',
'math_unknown_function'    => 'onbekènde functie',
'math_bad_output'          => 'Kin neet sjrieve nao de output directory veur mattematik',
'prefs-personal'           => 'Gebroekersinfo',
'prefs-rc'                 => 'Recènte verangeringe en weergaaf van sjtumpkes',
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
'filesource'        => 'Bron',
'uploadedfiles'     => 'Ge-uploade bestanden',
'minlength'         => "De naom van 't besjtand mot oet minstes drie teikes besjtaon.",
'badfilename'       => 'De naom van \'t besjtand is verangerd in "$1".',
'emptyfile'         => "'t Besjtand wats re höbs geupload is laeg. Dit kump waorsjienliek door 'n typfout in de besjtandsnaom. Kiek estebleef ofs te dit besjtand wirkelik wils uploade.",
'fileexists'        => "D'r is al e besjtand mit dees naam, bekiek $1 of se dat besjtand mesjien wils vervange.",
'successfulupload'  => 'De upload is geluk',
'fileuploaded'      => '<b>Het uploaden van bestand "$1" is geslaagd.</b> Gelieve deze link naar de omschrijvingspagina te volgen: ($2). Vul daar informatie in over dit bestand, bijvoorbeeld de oorsprong, wanneer en door wie het gemaakt is en wat u verder er nog over te vertellen heeft.',
'uploadwarning'     => 'Upload waarsjuwing',
'savefile'          => 'Bestand opsjlaon',
'uploadedimage'     => 'haet ge-upload: [[$1]]',
'destfilename'      => 'Doeltitel',

# Image list
'imagelist'           => 'Lies van aafbeildinge',
'imagelisttext'       => "Hie volgt 'n lies mit $1 afbeildinge geordend $2.",
'getimagelist'        => 'Lies van aafbeildinge ophaole',
'ilsubmit'            => 'Zeuk',
'showlast'            => 'Toen de litste $1 aafbeildinge geordend $2.',
'byname'              => 'op naom',
'bydate'              => 'op datum',
'bysize'              => 'op gruutde',
'imgdelete'           => 'verw',
'imgdesc'             => 'besc',
'imglegend'           => 'Oetlik: (besc) = toen/veranger besjrieving van de aafbeilding, (verw) = wis de aafbeilding.',
'imghistory'          => 'Historie van de aafbeilding',
'deleteimg'           => 'wis',
'deleteimgcompletely' => 'Wis al versies',
'imghistlegend'       => 'Oetlik: (cur)= huidige aafbeilding, (verw) = wis de aw versie, (rev) = zit aw versie truuk.<br />
<i>Klik op de datum om de aafbeildinge die ge-upload zint op die datum te zeen</i>.',
'imagelinks'          => 'Aafbeildingsverwiezinge',
'linkstoimage'        => "Dees aafbeilding weurt op de volgende pazjena's gebroek:",
'nolinkstoimage'      => 'Gein enkele pazjena gebroek dees aafbeilding.',
'sharedupload'        => 'literal translation',

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
'allpagesnext'      => 'Volgende',
'allpagessubmit'    => 'OK',
'allpagesprefix'    => "Tuin pazjena's mèt 't veurvoogsel:",

# E-mail user
'mailnologin'     => 'Gein e-mailadres bekènd veur deze gebroeker',
'mailnologintext' => "De mos zien [[Special:Userlogin|aangemèld]] en 'n gèldig e-mailadres in bie dien [[Special:Preferences|veurkäöre]] höbbe ingevuld om mail nao anger gebroekers te sjture.",
'emailuser'       => "Sjik deze gebroeker 'nen e-mail",
'emailpage'       => "Sjik gebroeker 'nen e-mail",
'emailpagetext'   => "As deze gebroeker e geljig e-mailadres heet opgegaeve dan kant geer via dit formuleer e berich sjikke. 't E-mailadres wat geer heet opgegeve bie eur veurkäöre zal as versjikker aangegaeve waere.",
'noemailtitle'    => 'Gein e-mailadres bekènd veur deze gebroeker',
'noemailtext'     => 'Deze gebroeker haet gein gèldig e-mailadres opgegaeve of haet dees functie oetgesjakeld.',
'emailfrom'       => 'Van',
'emailto'         => 'Aan',
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
'removechecked'        => "Verwieder aangevinkde pazjena's van dien volglies",
'watchlistcontains'    => "Dien volglies bevat $1 pazjena's.",
'watcheditlist'        => 'Hie is ein alfabetische lies van de door dich gevolgde pazjena\'s. Vink de veerkentjes van de pazjena\'s dies te van dien volglies wils haole aan en klik op de "wisse"-knop hieonger.',
'removingchecked'      => "Pazjena's van volglies aafgehaold...",
'couldntremove'        => "Kós item '$1' neet wisse...",
'wlnote'               => 'Hieonger de lètste $1 verangeringe van de lètste <b>$2</b> oor.',
'wlshowlast'           => 'Tuin lètste $1 ore $2 daag $3',
'wlsaved'              => "Dit is 'n opgesjlage versie van dien volglies.",

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
'imagereverted'      => 'De omzetting naar de oudere versie is geslaagd.',
'rollback'           => 'Wijzigingen ongedaan maken',
'rollbacklink'       => 'Trukdrieje',
'cantrollback'       => 'Trökdrejje van verangeringe neet meugelik: Dit artikel haet mer einen auteur.',
'alreadyrolled'      => "'t Is neet meugelik óm de lèste verangering van [[:$1]]
door [[User:$2|$2]] ([[User talk:$2|euverlik]]) óngedaon te make. Emes angers haet de pazjena al hersjtèld of haet 'n anger bewèrking gedaon. 

De lèste bewèrking is gedaon door [[User:$3|$3]] ([[User talk:$3|euverlik]]).",
'editcomment'        => '\'t Bewirkingscommentair waor: "<i>$1</i>".', # only shown if there is an edit comment
'revertpage'         => 'Wieziginge door [[Special:Contributions/$2|$2]] ([[User_talk:$2|Euverlèk]]) trukgedriejd tot de lètste versie door [[User:$1|$1]]',
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
'namespace' => 'Naamruumde:',
'invert'    => 'Ómgedriejde selectie',

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
'ipaddress'          => 'IP-adres',
'ipbreason'          => 'Reden',
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
'unblocklink'        => 'deblokkeer',
'contribslink'       => 'biedrages',
'autoblocker'        => 'Ómdets te \'n IP-adres deils mit "$1" (geblokkeerd mit raeje "$2") bis te automatisch geblokkeerd.',
'blocklogpage'       => 'Blokkeerlogbook',
'blocklogentry'      => '"$1" is geblokkeerd veur d\'n tied van $2',
'blocklogtext'       => "Dit is 'n log van blokkades van gebroekers. Automatisch geblokkeerde IP-adresse sjtoon hie neet bie. Zuug de [[Special:Ipblocklist|Lies van geblokkeerde IP-adresse]] veur de lies van op dit mement wèrkende blokkades.",
'proxyblockreason'   => "Dien IP-adres is geblokkeerd ómdat 't 'n aope proxy is. Contacteer estebleef diene internet service provider of technische óngersjteuning en informeer ze euver dit serjeus veiligheidsprebleem.",
'proxyblocksuccess'  => 'Klaor.',

# Developer tools
'lockdb'              => 'Blokkeer de database',
'unlockdb'            => 'Deblokkeer de database',
'lockdbtext'          => "Waarsjoewing: De database blokkere haet 't gevolg dat nemes nog pazjena's kint bewirke, veurkäöre kint verangere of get angers kint doon woeveur d'r verangeringe in de database nudig zint.",
'unlockdbtext'        => "Het de-blokkeren van de database zal de gebroekers de mogelijkheid geven om wijzigingen aan pagina's op te slaan, hun voorkeuren te wijzigen en alle andere bewerkingen waarvoor er wijzigingen in de database nodig zijn. Is dit inderdaad wat u wilt doen?.",
'lockconfirm'         => 'Jao, ich wil de database blokkere.',
'unlockconfirm'       => 'Ja, ik wil de database de-blokkeren.',
'lockbtn'             => 'Blokkeer de database',
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
* es de pazjena nao 'n anger naamruumde verplaats weurt
* es al 'n euverlikpazjena besjteit ónger de angere naam
* es doe 't óngersjtaond vekske neet aanvinks",
'movearticle'            => 'Verplaats pazjena',
'movenologin'            => 'Neet aangemèld',
'movenologintext'        => "Veur 't verplaatsje van 'n pazjena mos e zien [[Special:Userlogin|aangemèld]].",
'newtitle'               => 'Nao de nuje titel',
'movepagebtn'            => 'Verplaats pazjena',
'pagemovedsub'           => 'De verplaatsing is gelök',
'pagemovedtext'          => 'Pazjena "[[$1]]" verplaats nao "[[$2]]".',
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
'allmessagesname'           => 'Naam',
'allmessagesdefault'        => 'Obligaten teks',
'allmessagescurrent'        => 'Hujige teks',
'allmessagestext'           => "Dit is 'n lies van alle systeemberichte besjikbaar in de MediaWiki:-naamruumde.",
'allmessagesnotsupportedUI' => 'Dien huidige interface taol <b>$1</b> weurt bie dees site neet ongerstjeund doer special:Allmessages.',
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
'tooltip-search'                  => 'Doerzeuk dizze wiki',
'tooltip-p-logo'                  => 'Huidpazjena',
'tooltip-n-mainpage'              => 'Bezeuk de huidpazjena',
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
'and'              => 'en',
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
'deletedrevision' => 'Aw versie $1 gewis.',

# Browsing diffs
'previousdiff' => '← Gank nao de vurrige diff',
'nextdiff'     => 'Gank nao de volgende diff →',

# Media information
'imagemaxsize' => "Bepèrk plaetsjes op de besjrievingspazjena's van aafbeildinge tot:",
'thumbsize'    => 'Thumbnail size :',

'newimages' => 'Nuuj plaetjes',
'noimages'  => 'Niks te zeen.',

# EXIF tags
'exif-bitspersample'            => 'Bits per componènt',
'exif-compression'              => 'Cómpressiesjema',
'exif-datetime'                 => 'Datum en momènt besjtandjsverangering',
'exif-artist'                   => 'Auteur',
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

'exif-contrast-0' => 'Normaal',
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

# Inputbox extension, may be useful in other contexts as well
'createarticle' => 'Maak artikel aan',

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


