<?php
# Nynorsk version translated by Olve Utne, Guttorm Flatabø and others
# see:
# <http://meta.wikimedia.org/w/wiki.phtml?title=LanguageNn.php&action=history>
# for edit history.
# To be included in the MediaWiki software.
# This file is dual-licensed under GFDL and GPL.

#--------------------------------------------------------------------------
# Language-specific text
#--------------------------------------------------------------------------

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
#

# Use UTF-8
require_once( 'LanguageUtf8.php' );

if($wgMetaNamespace === FALSE)
	$wgMetaNamespace = str_replace( ' ', '_', $wgSitename );

/* private */ $wgNamespaceNamesNn = array(
	NS_MEDIA          => 'Filpeikar',
	NS_SPECIAL        => 'Spesial',
	NS_MAIN           => '',
	NS_TALK           => 'Diskusjon',
	NS_USER           => 'Brukar',
	NS_USER_TALK      => 'Brukardiskusjon',
	NS_WIKIPEDIA        => $wgMetaNamespace,
	NS_WIKIPEDIA_TALK   => $wgMetaNamespace . '-diskusjon',
	NS_IMAGE          => 'Fil',
	NS_IMAGE_TALK     => 'Fildiskusjon',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'MediaWiki-diskusjon',
	NS_TEMPLATE       => 'Mal',
	NS_TEMPLATE_TALK  => 'Maldiskusjon',
	NS_HELP           => 'Hjelp',
	NS_HELP_TALK      => 'Hjelpdiskusjon',
	NS_CATEGORY       => 'Kategori',
	NS_CATEGORY_TALK  => 'Kategoridiskusjon'
) + $wgNamespaceNamesEn;

/* private */ $wgDefaultUserOptionsNn = array(
        /* Just change the date format to "DD Month Year" and inherit the rest */
        'date' => 2
) + $wgDefaultUserOptionsEn;

/* private */ $wgQuickbarSettingsNn = array(
	'Ingen', 'Venstre', 'Høgre', 'Fast venstre'
);

/* private */ $wgSkinNamesNn = array(
	'standard'        => 'Klassisk',
	'nostalgia'       => 'Nostalgi',
	'cologneblue'     => 'Kölnerblå',
	'davinci'         => 'DaVinci',
	'mono'            => 'Mono',
	'monobook'        => 'MonoBook',
	'myskin'          => 'MiDrakt'
);

/* private */ $wgMathNamesNn = array(
        'Vis alltid som PNG',
        'HTML om svært enkel, elles PNG',
        'HTML om mogleg, elles PNG',
        'Behald som TeX (for tekst-nettlesarar)',
        'Tilrådd for moderne nettlesarar',
        'MathML dersom mogleg (eksperimentell)'
);


/* private */ $wgDateFormatsNn = array(
	'Ingen preferanse',
	'januar 15, 2001',
	'15 januar 2001',
	'2001 januar 15',
	'2001-01-15'
);

/* private */ $wgBookstoreListNn = array(
	'Bibsys'       => 'http://wgate.bibsys.no/gate1/FIND?bibl=BIBSYS&sn=$1',
	'Bokkilden'    => 'http://www.bokkilden.no/ProductDetails.aspx?ProductId=$1',
	'Haugenbok'    => 'http://www.haugenbok.no/searchresults.cfm?searchtype=simple&isbn=$1',
	'Amazon.co.uk' => 'http://www.amazon.co.uk/exec/obidos/ISBN=$1',
	'Amazon.de'    => 'http://www.amazon.de/exec/obidos/ISBN=$1',
	'Amazon.com'   => 'http://www.amazon.com/exec/obidos/ISBN=$1'
);




# Note to translators:
#   Please include the English words as synonyms.  This allows people
#   from other wikis to contribute more easily.
#
/* private */ $wgMagicWordsNn = array(
#   ID                                 CASE  SYNONYMS
    MAG_REDIRECT             => array( 0,    '#redirect', '#omdiriger'                                 ),
    MAG_NOTOC                => array( 0,    '__NOTOC__', '__INGAINNHALDSLISTE__'                      ),
    MAG_FORCETOC             => array( 0,    '__FORCETOC__', '__ALLTIDINNHALDSLISTE__'                 ),
    MAG_TOC                  => array( 0,    '__TOC__', '__INNHALDSLISTE__'                            ),
    MAG_NOEDITSECTION        => array( 0,    '__NOEDITSECTION__', '__INGADELREDIGERING__'              ),
    MAG_START                => array( 0,    '__START__'                                               ),
    MAG_CURRENTMONTH         => array( 1,    'CURRENTMONTH', 'MÅNADNO', 'MÅNEDNÅ'                      ),
    MAG_CURRENTMONTHNAME     => array( 1,    'CURRENTMONTHNAME', 'MÅNADNONAMN', 'MÅNEDNÅNAVN'          ),
    MAG_CURRENTDAY           => array( 1,    'CURRENTDAY', 'DAGNO', 'DAGNÅ'                            ),
    MAG_CURRENTDAYNAME       => array( 1,    'CURRENTDAYNAME', 'DAGNONAMN', 'DAGNÅNAVN'                ),
    MAG_CURRENTYEAR          => array( 1,    'CURRENTYEAR', 'ÅRNO', 'ÅRNÅ'                             ),
    MAG_CURRENTTIME          => array( 1,    'CURRENTTIME', 'TIDNO', 'TIDNÅ'                           ),
    MAG_NUMBEROFARTICLES     => array( 1,    'NUMBEROFARTICLES', 'INNHALDSIDETAL', 'INNHOLDSIDETALL'   ),
    MAG_CURRENTMONTHNAMEGEN  => array( 1,    'CURRENTMONTHNAMEGEN', 'MÅNADNONAMNGEN', 'MÅNEDNÅNAVNGEN' ),
    MAG_PAGENAME             => array( 1,    'PAGENAME', 'SIDENAMN', 'SIDENAVN'                        ),
    MAG_PAGENAMEE            => array( 1,    'PAGENAMEE', 'SIDENAMNE', 'SIDENAVNE'                     ),
    MAG_NAMESPACE            => array( 1,    'NAMESPACE', 'NAMNEROM', 'NAVNEROM'                       ),
    MAG_MSG                  => array( 0,    'MSG:'                                                    ),
    MAG_SUBST                => array( 0,    'SUBST:', 'LIMINN:'                                       ),
    MAG_MSGNW                => array( 0,    'MSGNW:', 'IKWIKMELD:'                                    ),
    MAG_END                  => array( 0,    '__END__', '__SLUTT__'                                    ),
    MAG_IMG_THUMBNAIL        => array( 1,    'thumbnail', 'thumb', 'mini', 'miniatyr'                  ),
    MAG_IMG_RIGHT            => array( 1,    'right', 'høgre', 'høyre'                                 ),
    MAG_IMG_LEFT             => array( 1,    'left', 'venstre'                                         ),
    MAG_IMG_NONE             => array( 1,    'none', 'ingen'                                           ),
    MAG_IMG_WIDTH            => array( 1,    '$1px', '$1pk'                                            ),
    MAG_IMG_CENTER           => array( 1,    'center', 'centre', 'sentrum'                             ),
    MAG_IMG_FRAMED	     => array( 1,    'framed', 'enframed', 'frame', 'ramme'                    ),
    MAG_INT                  => array( 0,    'INT:'                                                    ),
    MAG_SITENAME             => array( 1,    'SITENAME', 'NETTSTADNAMN'                                ),
    MAG_NS                   => array( 0,    'NS:', 'NR:'                                              ),
    MAG_LOCALURL             => array( 0,    'LOCALURL:', 'LOKALLENKJE:', 'LOKALLENKE:'                ),
    MAG_LOCALURLE            => array( 0,    'LOCALURLE:', 'LOKALLENKJEE:', 'LOKALLENKEE:'             ),
    MAG_SERVER               => array( 0,    'SERVER', 'TENAR', 'TJENER'                               ),
    MAG_GRAMMAR              => array( 0,    'GRAMMAR:', 'GRAMMATIKK:'                                 )
);

#-------------------------------------------------------------------
# Default messages
#-------------------------------------------------------------------
# Allowed characters in keys are: A-Z, a-z, 0-9, underscore (_) and
# hyphen (-). If you need more characters, you may be able to change
# the regex in MagicWord::initRegex

/* private */ $wgAllMessagesNn = array(
'special_version_prefix' => '',
'special_version_postfix' => '',


'tog-hover'		        => 'Vis svevetekst over wikilenkjer',
'tog-underline'             => 'Understrek lenkjer',
'tog-highlightbroken'       => 'Formatér lenkjer til tomme sider <a href="" class="new">slik</a> (alternativt slik<a href="" class="internal">?</a>)',
'tog-justify'	        => 'Blokkjusterte avsnitt',
'tog-hideminor'             => 'Skjul "uviktige redigeringar" på "siste endringar" sida',
'tog-usenewrc'              => 'Utvida funksjonalitet på "siste endringar" sida',
'tog-numberheadings'        => 'Nummerér overskrifter',
'tog-showtoolbar'           => 'Vis redigeringsknappar',
'tog-editondblclick'        => 'Redigér sider med dobbelklikk (JavaScript)',
'tog-editsection'           => 'Redigér avsnitt med hjelp av [redigér]-lenkje',
'tog-editsectiononrightclick' => 'Redigér avsnitt med å høgreklikke på avsnittsoverskrift (JavaScript)',
'tog-showtoc'               => 'Vis innhaldsliste (for sider med meir enn tre delar)',
'tog-rememberpassword'      => 'Hugs passordet til neste gong',
'tog-editwidth'             => 'Gje redigeringsboksen full breidd',
'tog-watchdefault'          => 'Legg sider eg redigerer i overvakingslista mi',
'tog-minordefault'          => 'Markér redigeringar uviktige som standard',
'tog-previewontop'          => 'Vis førehandsvisinga føre redigeringsboksen, ikkje etter',
'tog-previewonfirst'        => 'Førehandsvis første redigering',
'tog-nocache'               => 'Sidene skal ikkje leggjast i nettlesaren sitt mellomlager (cache)',

'sunday'	=> 'søndag',
'monday'	=> 'måndag',
'tuesday'	=> 'tysdag',
'wednesday'	=> 'onsdag',
'thursday'	=> 'torsdag',
'friday'	=> 'fredag',
'saturday'	=> 'laurdag',

'january'	=> 'januar',
'february'	=> 'februar',
'march'		=> 'mars',
'april'		=> 'april',
'may_long'	=> 'mai',
'june'		=> 'juni',
'july'		=> 'juli',
'august'	=> 'august',
'september'	=> 'september',
'october'	=> 'oktober',
'november'	=> 'november',
'december'	=> 'desember',

'jan' => 'jan', 
'feb' => 'feb', 
'mar' => 'mar', 
'apr' => 'apr', 
'may' => 'mai', 
'jun' => 'jun', 
'jul' => 'jul', 
'aug' => 'aug',
'sep' => 'sep', 
'oct' => 'okt', 
'nov' => 'nov', 
'dec' => 'des'

# Bits of text used by many pages:
'categories'              => 'Kategoriar',
'category'                => 'kategori',
'category_header'         => 'Artiklar i kategorien "$1"',
'subcategories'           => 'Underkategoriar',

'linktrail'		  => '/^((?:[a-z]|æ|ø|å)+)(.*)\$/sD',
'mainpage'		  => 'Hovudside',
'mainpagetext'	          => 'MediaWiki er no installert.',
'mainpagedocfooter'       => 'Sjå [http://meta.wikipedia.org/wiki/MediaWiki_localization dokumentasjon for å tilpasse brukargrensesnittet] og [http://meta.wikipedia.org/wiki/Help:Contents brukarmanualen] for bruk og konfigurasjonshjelp.',

# NOTE: To turn off "Community portal" in the title links,
# set 'portal' => '-'
'portal'		  => 'Brukarportal',
'portal-url'		  => '{{ns:4}}:Brukarportal',
'about'			  => 'Om',
'aboutwikipedia'          => 'Om {{SITENAME}}',
'aboutpage'		  => '{{ns:4}}:Om',
'article'                 => 'Innhaldsside',
'help'			  => 'Hjelp',
'helppage'		  => '{{ns:12}}:Innhald',
'wikititlesuffix'         => '{{SITENAME}}',
'bugreports'	          => 'Feilmeldingar',
'bugreportspage'          => '{{ns:4}}:Feilmeldingar',
'sitesupport'             => 'Donering', # Set a URL in $wgSiteSupportPage in LocalSettings.php
'faq'			  => 'OSS',
'faqpage'		  => '{{ns:4}}:OSS',
'edithelp'		  => 'Hjelp til redigering',
'newwindow'		  => '(vert opna i eit nytt vindauge)',
'edithelppage'	          => '{{ns:12}}:Redigering',
'cancel'		  => 'Avbryt',
'qbfind'		  => 'Finn',
'qbbrowse'		  => 'Bla gjennom',
'qbedit'		  => 'Redigér',
'qbpageoptions'           => 'Denne sida',
'qbpageinfo'	          => 'Samanheng',
'qbmyoptions'	          => 'Sidene mine',
'qbspecialpages'	  => 'Spesialsider',
'moredotdotdot'	          => 'Meir...',
'mypage'		  => 'Sida mi',
'mytalk'		  => 'Diskusjonssida mi',
'anontalk'		  => 'Diskusjonside for denne IP-adressa',
'navigation'              => 'Navigering',

# NOTE: To turn off "Current Events" in the sidebar,
# set 'currentevents' => '-'
'currentevents'           => '-', # Aktuelt

# NOTE: To turn off "Disclaimers" in the title links,
# set 'disclaimers' => '-'
'disclaimers'             => 'Vilkår',
'disclaimerpage'	  => '{{ns:4}}:Vilkår',
'errorpagetitle'          => 'Feil',
'returnto'		  => 'Attende til $1.',
'fromwikipedia'	          => 'Frå {{SITENAME}}',
'whatlinkshere'	          => 'Sider med lenkjer hit',
'help'			  => 'Hjelp',
'search'		  => 'Søk',
'go'		          => 'Gå',
'history'		  => 'Sidehistorikk',
'history_short'           => 'Historikk',
'info_short'	          => 'Informasjon',
'printableversion'        => 'Utskriftsversjon',
'edit'                    => 'Redigér',
'editthispage'	          => 'Redigér side',
'delete'                  => 'Slett',
'deletethispage'          => 'Slett side',
'undelete_short'          => 'Attopprett $1 redigeringar',
'protect'                 => 'Vern',
'protectthispage'         => 'Vern denne sida',
'unprotect'               => 'Fjern vern',
'unprotectthispage'       => 'Fjern vern av denne sida',
'newpage'                 => 'Ny side',
'talkpage'		  => 'Diskutér side',
'specialpage'             => 'Spesialside',
'personaltools'           => 'Personlege verktøy',
'postcomment'             => 'Legg til kommentar',
'addsection'              => '+',
'articlepage'	          => 'Vis innhaldsside',
'subjectpage'	          => 'Vis emne', # For compatibility
'talk'                    => 'Diskusjon',
'toolbox'                 => 'Verktøy',
'userpage'                => 'Vis brukarside',
'wikipediapage'           => 'Vis prosjektside',
'imagepage'               => 'Vis filside',
'viewtalkpage'            => 'Vis diskusjon',
'otherlanguages'          => 'Andre språk',
'redirectedfrom'          => '(Omdirigert frå $1)',
'lastmodified'	          => 'Denne sida vart sist endra $1.',
'viewcount'		  => 'Denne sida er vist $1 gonger.',
'copyright'	          => 'Innhaldet er utgjeve under $1.',
'poweredby'	          => '{{SITENAME}} bruker [http://www.mediawiki.org/ MediaWiki] som er fri wikiprogramvare.',
'printsubtitle'           => '(frå {{SERVER}})',
'protectedpage'           => 'Verna side',
'administrators'          => '{{ns:4}}:Administratorar',
'sysoptitle'	          => 'Administratortilgang trengst',
'sysoptext'		  => 'Funksjonen kan berre utførast av administratorar. Sjå $1.',
'developertitle'          => 'Utviklartilgang trengst.',
'developertext'	          => 'Funksjonen kan berre utførast av administratorar med utviklartilgang. Sjå $1.',
'bureaucrattitle'	  => 'Byråkrattilgang trengst.',
'bureaucrattext'	  => 'Funksjonen kan berre utførast av administratorar med byråkrattilgang.',
'nbytes'		  => '$1 byte',
'go'			  => 'Vis',
'ok'			  => 'OK',
'sitetitle'		  => '{{SITENAME}}',
'pagetitle'		  => '$1 - {{SITENAME}}',
'sitesubtitle'	          => 'Det frie oppslagsverket',
'retrievedfrom'           => 'Henta frå "$1"',
'newmessages'             => 'Du har $1.',
'newmessageslink'         => 'nye meldingar',
'editsection'             => 'redigér',
'toc'                     => 'Innhaldsliste',
'showtoc'                 => 'vis',
'hidetoc'                 => 'gøym',
'thisisdeleted'           => 'Sjå, eller attopprett $1?',
'restorelink'             => '$1 sletta endringar',
'feedlinks'               => 'Mating:',
'sitenotice'	          => '-', # the equivalent to wgSiteNotice

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'              => 'Innhaldsside',
'nstab-user'              => 'Brukarside',
'nstab-media'             => 'Filpeikar',
'nstab-special'           => 'Spesial',
'nstab-wp'                => 'Prosjektside',
'nstab-image'             => 'Fil',
'nstab-mediawiki'         => 'Systemmelding',
'nstab-template'          => 'Mal',
'nstab-help'              => 'Hjelp',
'nstab-category'          => 'Kategori',

# Main script and global functions
#
'nosuchaction'	          => 'Funksjonen finst ikkje',
'nosuchactiontext'        => 'Wikiprogramvaren kjenner ikkje att funksjonen som er spesifisert i nettadressa',
'nosuchspecialpage'       => 'Ei slik spesialside finst ikkje',
'nospecialpagetext'       => 'Du har bede om ei spesialside som wikiprogramvaren ikkje kjenner att.',

# General errors
#
'error'			  => 'Feil',
'databaseerror'           => 'Databasefeil',
'dberrortext'	          => 'Det oppstod ein syntaksfeil i databaseførespurnaden. Dette kan tyde på ein feil i programvaren. Den sist prøvde førespurnaden var: <blockquote><tt>$1</tt></blockquote> frå funksjonen "<tt>$2</tt>". MySQL returnerte feilen "<tt>$3: $4</tt>".',
'dberrortextcl'           => 'Det oppsto ein syntaksfeil i databaseførespurnaden. Den sist prøvde førespurnaden var: "$1" frå funksjonen "$2".
MySQL returnerte feilen "$3: $4".',
'noconnect'		  => 'Wikien har tekniske problem og kunne ikkje kople til databasen.<br />$1',
'nodb'			  => 'Kunne ikkje velje databasen $1',
'cachederror'	          => 'Det følgjande er ein lagra kopi av den ønska sida, og er ikkje nødvendigvis oppdatert.',
'readonly'		  => 'Databasen er skriveverna',
'enterlockreason'         => 'Skriv ein grunn for vernet, inkludert eit overslag for kva tid det vil bli oppheva',
'readonlytext'	          => 'Databasen er akkurat no skriveverna, truleg for rutinemessig vedlikehald. Administratoren som verna han har gjeve denne forklaringa:<p>$1',
'missingarticle'          => 'Databasen fann ikkje teksten til ei side han skulle ha funne, med namnet "$1".

<p>Dette skjer vanlegvis fordi du har fulgt ei lenkje til ei oppføring som har vorte sletta.
Sletta oppføringar kan vanlegvis attopprettast.

<p>Dersom dette ikkje er tilfellet kan du ha funne ein feil i programvaren. Gje melding om dette til ein administrator, med adressa åt sida.',
'internalerror'           => 'Intern feil',
'filecopyerror'           => 'Kunne ikkje kopiere fila frå "$1" til "$2".',
'filerenameerror'         => 'Kunne ikkje omdøype fila frå "$1" til "$2".',
'filedeleteerror'         => 'Kunne ikkje slette fila "$1".',
'filenotfound'	          => 'Kunne ikkje finne fila "$1".',
'unexpected'	          => 'Uventa verdi: "$1"="$2".',
'formerror'		  => 'Feil: Kunne ikkje sende skjema',	
'badarticleerror'         => 'Handlinga kan ikkje utførast på denne sida.',
'cannotdelete'	          => 'Kunne ikkje slette fila. (Ho kan vera sletta av andre.)',
'badtitle'	 	  => 'Feil i tittel',
'badtitletext'	          => 'Den ønska tittelen var ulovleg, tom, eller er feil lenka frå ei anna wiki.',
'perfdisabled'            => 'Beklagar! Denne funksjonen er mellombels deaktivert for å spare tenarkapasitet.',
'perfdisabledsub'         => 'Her er ein lagra kopi frå $1:',
'perfcached'              => 'Det følgjande er frå tenaren sitt mellomlager og er ikkje nødvendigvis oppdatert:',
'wrong_wfQuery_params'    => 'Feil parameter gjevne til wfQuery()<br />Funksjon: $1<br />Førespurnad: $2',
'viewsource'              => 'Vis kjeldetekst',
'protectedtext'           => 'Denne sida er verna for redigering. Det kan vera fleire grunnar til dette, sjå [[{{ns:4}}:Verna side]].

Du kan sjå og kopiere kjeldeteksten til denne sida:',
'seriousxhtmlerrors'      => 'HTML Tidy oppdaga alvorlege xhtml-kode feil.',

# Login and logout pages
#
'logouttitle'	          => 'Logg ut',
'logouttext'	          => 'Du er no utlogga. Avhengig av innstillingane på tenaren kan nettlesaren no brukast på {{SITENAME}} anonymt;
du kan logge inn att med same kontoen eller ein annan brukar kan logge inn. Ver merksam på at nokre sider kan fortsette å bli viste som om du er innlogga inntil du tømmer mellomlageret til nettlesaren din.',
'welcomecreation'         => '<h2>Hjarteleg velkommen til {{SITENAME}}, $1!</h2>

Brukarkontoen din har blitt oppretta. Det er tilrådd at du ser gjennom brukarinnstillingane dine.',
'loginpagetitle'          => 'Logg inn',
'yourname'		  => 'Brukarnamn',
'yourpassword'	          => 'Passord',
'yourpasswordagain'       => 'Skriv opp att passord',
'newusersonly'	          => ' (berre nye brukarar)',
'remembermypassword'      => 'Hugs passordet.',
'loginproblem'	          => '<b>Du vart ikkje innlogga.</b><br />Prøv om att!',
'alreadyloggedin'         => '<div class="alreadyloggedin">Brukar $1, du er allereie innlogga!</div>',
'login'			  => 'Logg inn',
'loginprompt'             => 'Nettlesaren din må akseptere informasjonskapslar for at du skal kunna logge inn.',
'userlogin'		  => 'Lag brukarkonto eller logg inn',
'logout'		  => 'Logg ut',
'userlogout'	          => 'Logg ut',
'notloggedin'	          => 'Ikkje innlogga',
'createaccount'	          => 'Opprett ny konto',
'createaccountmail'	  => 'over e-post',
'badretype'		  => 'Passorda du skreiv inn er ikkje like.',
'userexists'	          => 'Brukarnamnet er allereie i bruk. Vel eit nytt.',
'youremail'		  => 'E-postadresse*',
'yourrealname'		  => 'Verkeleg namn*',
'yournick'		  => 'Kallenamn (for signaturar)',
'emailforlost'	          => 'Felt merkte med ei stjerne (*) er valfrie. E-postadressa gjer det mogleg for andre brukarar å ta kontakt med deg utan at du offentleggjer ho. Ho kan òg brukast til å sende deg nytt passord. Ditt verkelege namn, dersom du vel å fylle ut dette feltet, vil bli brukt til å godskrive arbeid du har gjort.<br /><br />',
'prefs-help-userdata'     => '* <strong>Verkeleg namn</strong> (valfritt): Om du vel å fylle ut dette feltet, vil informasjonen bli brukt til å godskrive arbeid du har gjort.<br />
* <strong>E-post</strong> (valfritt): Gjer det mogleg for andre brukarar å ta kontakt med deg utan at du offentleggjer adressa. Ho kan òg brukast til å sende deg nytt passord.',
'loginerror'	          => 'Innloggingsfeil',
'nocookiesnew'	          => 'Brukarkontoen vart oppretta, men du er ikkje innlogga. {{SITENAME}} bruker informasjonskapslar for å logge inn brukarar,
nettlesaren din er innstilt for ikkje å godta desse. Etter at du har endra innstillingane slik at nettlesaren godtek informasjonskapslar, kan du logge inn med det nye brukarnamnet og passordet ditt.',
'nocookieslogin'	  => '{{SITENAME}} bruker informasjonskapslar for å logge inn brukarar, nettlesaren din er innstilt for ikkje å godta desse.
Etter at du har endra innstillingane slik at nettlesaren godtek informasjonskapslar kan du prøva å logge inn på nytt.',
'noname'		  => 'Du har ikkje gjeve gyldig brukarnamn.',
'loginsuccesstitle'       => 'Du er no innlogga',
'loginsuccess'	          => 'Du er no innlogga som "$1".',
'nosuchuser'	          => 'Det finst ikkje nokon brukar med brukarnamnet "$1". Sjekk at du har skrive rett, eller bruk skjemaet under til å opprette ein ny konto.',
'wrongpassword'	          => 'Du har gjeve eit ugyldig passord. Prøv om att.',
'mailmypassword'          => 'Send meg nytt passord.',
'passwordremindertitle'   => 'Nytt passord til {{SITENAME}}',
'passwordremindertext'    => 'Nokon (truleg du, frå IP-adressa $1) bad oss sende deg eit nytt passord til {{SITENAME}}. Passordet for brukaren "$2" er no "$3". Du bør logge inn og endre passordet så snart som råd.',
'noemail'	       	  => 'Det er ikkje registrert noka e-postadresse for brukaren "$1".',
'passwordsent'	          => 'Eit nytt passord er sendt åt e-postadressa registrert på brukaren "$1". Du bør logge inn og endre passordet så snart som råd.',
'loginend'		  => '&nbsp;',
'mailerror'               => 'Ein feil oppstod ved sending av e-post: $1',
'acct_creation_throttle_hit' => 'Beklagar, du har allereie laga $1 brukarkontoar. Du har ikkje høve til å laga fleire.',

# Edit page toolbar
'bold_sample'             => 'Feit tekst',
'bold_tip'                => 'Feit tekst',
'italic_sample'           => 'Kursiv tekst',
'italic_tip'              => 'Kursiv tekst',
'link_sample'             => 'Lenkjetittel',
'link_tip'                => 'Intern lenkje',
'extlink_sample'          => 'http://www.eksempel.no lenkjetittel',
'extlink_tip'             => 'Ekstern lenkje (hugs http:// prefiks)',
'headline_sample'         => 'Overskriftstekst',
'headline_tip'            => '2. Nivå-overskrift',
'math_sample'             => 'Skriv formel her',
'math_tip'                => 'Matematisk formel (LaTeX)',
'nowiki_sample'           => 'Skriv uformatert tekst her',
'nowiki_tip'              => 'Ignorér wikiformatering',
'image_sample'            => 'Eksempel.jpg',
'image_tip'               => 'Vis bilete',
'media_sample'            => 'Eksempel.ogg',
'media_tip'               => 'Filpeikar',
'sig_tip'                 => 'Signaturen din med tidsstempel',
'hr_tip'                  => 'Vassrett line',
'infobox'                 => 'Klikk på ein knapp for å få eksempeltekst',
# alert box shown in browsers where text selection does not work, test e.g. with mozilla or konqueror
'infobox_alert'           => 'Skriv inn teksten du vil ha formatert.\n Han vil bli vist i boksen slik at han kan kopierast og limast inn.\nEksempel:\n$1\nvert til:\n$2',

# Edit pages
#
'summary'		  => 'Samandrag',
'subject'		  => 'Emne/overskrift',
'minoredit'		  => 'Uviktig endring',
'watchthis'		  => 'Overvak side',
'savearticle'	          => 'Lagre',
'preview'		  => 'Førehandsvising',
'showpreview'	          => 'Førehandsvis',
'blockedtitle'	          => 'Brukaren er blokkert',
'blockedtext'	          => 'Brukarnamnet ditt eller IP-adressa di er blokkert frå redigering, av $1. Denne grunnen vart gjeven:<br />\'\'$2\'\'<p>Du kan kontakte $1 eller ein annan [[{{ns:4}}:Administratorar|administrator]] for å diskutere blokkeringa.

Ver merksam på at du ikkje kan bruka "send e-post åt brukar"-funksjonen så lenge du ikkje har ei gyldig e-postadresse registrert i [[Spesial:Innstillingar|innstillingane dine]].

IP-adressa di er $3. Legg henne ved eventuelle førespurnader.',
'whitelistedittitle'      => 'Du lyt logge inn for å redigere',
'whitelistedittext'       => 'Du lyt [[{{ns:-1}}:Userlogin|logge inn]] for å redigere sider.',
'whitelistreadtitle'      => 'Du lyt logge inn for å lesa',
'whitelistreadtext'       => 'Du lyt [[{{ns:-1}}:Userlogin|logge inn]] for å lesa sider.',
'whitelistacctitle'       => 'Du har ikkje løyve til å laga brukarkonto',
'whitelistacctext'        => 'For å laga brukarkontoar på denne wikien lyt du [[{{ns:-1}}:Userlogin|logge inn]] og ha rett type tilgang',
'loginreqtitle'	          => 'Innlogging trengst',
'loginreqtext'	          => 'Du lyt [[{{ns:-1}}:Userlogin|logge inn]] for å lesa andre sider.',
'accmailtitle'            => 'Passord er sendt.',
'accmailtext'             => 'Passordet for "$1" er vorte sendt til $2.',
'newarticle'	          => '(Ny)',
'newarticletext'          => '<div style="border: 1px solid #ccc; padding: 7px;">\'\'\'{{SITENAME}} har ikkje noka side med namnet {{PAGENAME}} enno.\'\'\'
* For å lage ei slik side kan du skrive i boksen under og klikke på "Lagre". Endringane vil vera synlege med det same.
* Om du er ny her er det tilrådd å sjå på [[{{ns:4}}:Retningsliner|retningsliner]] og [[{{ns:12}}:Innhald|hjelp]] først.
* Om du lagrar ei testside, vil du ikkje kunne slette ho sjølv. Ver difor venleg og bruk [[{{ns:4}}:Sandkassa|sandkassa]] til å eksperimentere.
* Dersom du ikkje ønskjer å redigere, kan du utan risiko klikke på \'\'\'attende\'\'\'-knappen i nettlesaren din.
</div>',
'talkpagetext'            => '<!-- MediaWiki:talkpagetext -->',
'anontalkpagetext'        => '---- \'\'Dette er ei diskusjonsside for ein anonym brukar som ikkje har logga inn på eigen brukarkonto. Vi er difor nøydde til å bruke den numeriske IP-adressa knytt til internettoppkoplinga åt brukaren. Same IP-adressa kan vera knytt til fleire brukarar. Om du er ein anonym brukar og meiner at du har fått irrelevante kommentarar på ei slik side, [[{{ns:-1}}:Userlogin|logg inn]] slik at vi unngår framtidige forvekslingar med andre anonyme brukarar.\'\'',
'noarticletext'           => '<div style="border: 1px solid #ccc; padding: 7px; background: white;">\'\'\'{{SITENAME}} har ikkje noka side med namnet {{PAGENAME}} enno.\'\'\'
* Klikk på \'\'\'[{{SERVER}}{{localurl:{{NAMESPACE}}:{{PAGENAME}}|action=edit}} redigér]\'\'\' for å laga ei slik side.</div>',
'clearyourcache'          => '\'\'\'Merk:\'\'\' Etter lagring bør du tømme nettlesaren sitt mellomlager for å sjå endringane. \'\'\'Mozilla og Konqueror:\'\'\' trykk \'\'Ctrl-R\'\', \'\'\'Internet Explorer:\'\'\' \'\'Ctrl-F5\'\', \'\'\'Opera:\'\'\' \'\'F5\'\', \'\'\'Safari:\'\'\' \'\'Cmd-R\'\'.',
'usercssjsyoucanpreview'  => '<strong>Tip:</strong> Bruk "Førehandsvis"-knappen for å teste den nye CSS- eller JS-koden din føre du lagrar.',
'usercsspreview'          => '\'\'\'Hugs at du berre testar ditt eige CSS, det har ikkje vorte lagra enno!\'\'\'',
'userjspreview'           => '\'\'\'Hugs at du berre testar ditt eige JavaScript, det har ikkje vorte lagra enno!!\'\'\'',
'updated'		  => '(Oppdatert)',
'note'			  => '<strong>Merk:</strong> ',
'previewnote'	          => 'Hugs at dette berre er ei førehandsvising og at teksten ikkje er lagra!',
'previewconflict'         => 'Dette er ei førehandsvising av teksten i redigeringsboksen over, slik han vil sjå ut om du lagrar han',
'editing'		  => 'Redigerer $1',
'editingsection'	  => 'Redigerer $1 (del)',
'editingcomment'	  => 'Redigerer $1 (kommentar)',
'editconflict'	          => 'Redigeringskonflikt: $1',
'explainconflict'         => 'Nokon annan har endra teksten sidan du byrja å redigere. Den øvste boksen inneheld den noverande teksten. Endringane dine er viste i den nedste boksen. Du lyt flette endringane dine saman med den noverande teksten. <strong>Berre</strong> teksten i den øvste tekstboksen vil bli lagra når du trykkjer "Lagre".<p>',
'yourtext'		  => 'Teksten din',
'storedversion'           => 'Den lagra versjonen',
'editingold'	          => '<strong>ÅTVARING: Du redigerer ein gammal versjon av denne sida. Om du lagrar ho, vil alle endringar gjorde sidan denne versjonen bli overskrivne.</strong><br />',
'yourdiff'		  => 'Skilnad',
'copyrightwarning'        => 'Merk deg at alle bidrag til {{SITENAME}} er å rekne som utgjevne under $2 (sjå $1 for detaljar). Om du ikkje vil ha teksten redigert og kopiert under desse vilkåra, kan du ikkje leggje han her.<br />
Teksten må du ha skrive sjølv, eller kopiert frå ein ressurs som er kompatibel med vilkåra eller ikkje verna av opphavsrett.

<strong>LEGG ALDRI INN MATERIALE SOM ANDRE HAR OPPHAVSRETT TIL UTAN LØYVE FRÅ DEI!</strong>',
'longpagewarning'         => 'ÅTVARING: Denne sida er $1 KB lang; nokre nettlesarar kan ha problem med å redigere sider som nærmar seg eller 
er lengre enn 32KB. Du bør vurdere å dele opp sida i mindre delar.<br />',
'readonlywarning'         => 'ÅTVARING: Databasen er skriveverna på grunn av vedlikehald, difor kan du ikkje lagre endringane dine akkurat no. Det kan vera lurt å  kopiere teksten din åt ei tekstfil, så du kan lagre han her seinare.<br />',
'protectedpagewarning'    => 'ÅTVARING: Denne sida er verna, slik at berre administratorar kan redigere ho.<br />',

# History pages
#
'revhistory'	          => 'Historikk',
'nohistory'		  => 'Det finst ikkje nokon historikk for denne sida.',
'revnotfound'	          => 'Fann ikkje versjonen',
'revnotfoundtext'         => 'Den gamle versjonen av sida du spurde etter finst ikkje. Sjekk nettadressa du brukte for å komma deg åt denne sida.',
'loadhist'		  => 'Lastar historikk',
'currentrev'	          => 'Noverande versjon',
'revisionasof'	          => 'Versjonen frå $1',
'cur'			  => 'no',
'next'			  => 'neste',
'last'			  => 'førre',
'orig'			  => 'orig',
'histlegend'	          => 'Forklaring: (no) = skilnad frå den noverande versjonen, (førre) = skilnad frå den førre versjonen, <b>u</b> = uviktig endring',
'history_copyright'       => '-',

# Diffs
#
'difference'	          => '(Skilnad mellom versjonar)',
'loadingrev'	          => 'lastar versjon for å sjå skilnad',
'lineno'		  => 'Line $1:',
'editcurrent'	          => 'Redigér den noverande versjonen av denne sida',
'selectnewerversionfordiff' => 'Velj ein nyare versjon for samanlikning',
'selectolderversionfordiff' => 'Velj ein eldre versjon for samanlikning',
'compareselectedversions' => 'Samalikn valde versjonar',

# Search results
#
'searchresults'           => 'Søkjeresultat',
'searchhelppage'          => '{{ns:4}}:Søking',
'searchingwikipedia'      => 'Søker gjennom {{SITENAME}}',
'searchresulttext'        => 'For meir info om søkjing i {{SITENAME}}, sjå $1.',
'searchquery'	          => 'For førespurnad "$1"',
'badquery'		  => 'Feil utforma førespurnad',
'badquerytext'	          => 'Vi kunne ikkje svara på denne førespurnaden &#8212; Truleg fordi du prøvde å søkje etter eit ord med færre enn tre bokstavar, noko som ikkje er mogleg enno. Det kan òg vera du skreiv feil... Prøv om att.',
'matchtotals'	          => 'Førespurnaden "$1" gav treff på $2 sidetitlar og på teksten i $3 sider.',
'nogomatch'               => '<span style="font-size: 135%; font-weight: bold; margin-left: .6em">Inga side med akkurat denne tittelen finst</span>

<span style="display: block; margin: 1.5em 2em">
Du kan <b><a href="$1" class="new">laga ei side med denne tittelen</a></b>.

<span style="display:block; font-size: 89%; margin-left:.2em">Søk {{SITENAME}} før du lagar ei side for å unngå dobling av eksisterande sider som kan ha eit anna namn eller ein annan stavemåte.</span></span>',
'titlematches'	          => 'Sidetitlar med treff på førespurnaden',
'notitlematches'          => 'Ingen sidetitlar hadde treff på førespurnaden',
'textmatches'	          => 'Sider med treff på førespurnaden',
'notextmatches'	          => 'Ingen sider hadde treff på førespurnaden',
'prevn'			  => 'førre $1',
'nextn'			  => 'neste $1',
'viewprevnext'	          => 'Vis ($1) ($2) ($3).',
'showingresults'          => 'Nedanfor er opp til <strong>$1</strong> resultat som byrjar med nummer <strong>$2</strong> viste.',
'showingresultsnum'       => 'Nedanfor er <strong>$3</strong> resultat som byrjar med nummer <strong>$2</strong> viste.',
'nonefound'		  => '<strong>Merk</strong>: søk utan resultat kjem ofte av at ein leitar etter alminnelege engelske ord som "have" og "from", som ikkje er indekserte, eller ved å spesifisere meir enn eitt søkjeord (ettersom berre sider som inneheld alle søkjeorda vil bli funne).',
'powersearch'             => 'Søk',
'powersearchtext'         => 'Søk i namnerom :<br />$1<br />$2 List opp omdirigeringar &nbsp; Søk etter $3 $9',
'searchdisabled'          => '<p style="margin: 1.5em 2em 1em">Søkefunksjonen på {{SITENAME}} er deaktivert på grunn av for stort press på tenaren akkurat no. I mellomtida kan du leite gjennom Google eller Yahoo! <span style="font-size: 89%; display: block; margin-left: .2em">Ver oppmerksam på at registra deira kan vera utdaterte.</span></p>',
'googlesearch'            => '<div style="margin-left: 2em">

<!-- Google search -->
<div style="width:130px;float:left;text-align:center;position:relative;top:-8px"><a href="http://www.google.com/" style="padding:0;background-image:none"><img src="http://www.google.com/logos/Logo_40wht.gif" alt="Google" style="border:none" /></a></div>

<form method="get" action="http://www.google.com/search" style="margin-left:135px">
  <div>
    <input type="hidden" name="domains" value="{{SERVER}}" />
    <input type="hidden" name="num" value="50" />
    <input type="hidden" name="ie" value="$2" />
    <input type="hidden" name="oe" value="$2" />
    
    <input type="text" name="q" size="31" maxlength="255" value="$1" />
    <input type="submit" name="btnG" value="Google Search" />
  </div>
  <div style="font-size:90%">
    <input type="radio" name="sitesearch" id="gwiki" value="{{SERVER}}" checked="checked" /><label for="gwiki">{{SITENAME}}</label>
    <input type="radio" name="sitesearch" id="gWWW" value="" /><label for="gWWW">WWW</label>
  </div>
</form>

<div style="clear:left;margin-top:10px">

<!-- Yahoo! search -->
<div style="width:130px;float:left;text-align:center;clear:left"><a href="http://search.yahoo.com/" style="padding:0;background-image:none"><img src="http://us.i1.yimg.com/us.yimg.com/i/us/search/ysan/ysanlogo.gif" alt="Yahoo!" style="border:none" /></a></div>

<form method="get" action="http://search.yahoo.com/search" style="margin-left:135px">
  <div>
    <input type="hidden" name="x" value="op" />
    <input type="hidden" name="va_vt" value="any" />
    <input type="text" name="va" size="31" value="$1" />
    <input type="submit" value="Yahoo! Search" />
  </div>
  <div style="font-size:90%">
    <input type="radio" name="vs" id="ywiki" value="{{SERVER}}" checked="checked" /><label for="ywiki">{{SITENAME}}</label>
    <input type="radio" name="vs" id="yWWW" value="" /><label for="yWWW">WWW</label>
  </div>
</form>

</div>

</div>',
'blanknamespace'        => '(Hovud)',

# Preferences page
#
'preferences'	        => 'Innstillingar',
'prefsnologin'          => 'Ikkje innlogga',
'prefsnologintext'	=> 'Du må vera [[{{ns:-1}}:Userlogin|innlogga]] for å endre brukarinnstillingane.',
'prefslogintext'        => 'Du er innlogga som "$1". Det interne ID-nummeret ditt er $2.

Sjå [[{{ns:12}}:Brukarinnstillingar]] for ei forklaring på dei ulike innstillingane.',
'prefsreset'	        => 'Innstillingane er tilbakestilte.',
'qbsettings'	        => 'Snøggmeny',
'qbsettingsnote'	=> 'Denne innstillinga har berre effekt på "Klassisk" og "Kölnerblå" draktene.',
'changepassword'        => 'Skift passord',
'skin'			=> 'Drakt',
'math'			=> 'Matematiske formlar',
'dateformat'	        => 'Datoformat',
'math_failure'		=> 'Klarte ikkje å tolke formelen',
'math_unknown_error'	=> 'ukjend feil',
'math_unknown_function'	=> 'ukjend funksjon ',
'math_lexing_error'	=> 'lexerfeil',
'math_syntax_error'	=> 'syntaksfeil',
'math_image_error'	=> 'PNG konvertering var mislukka; sjekk at latex, dvips, gs, og convert er rett installerte',
'math_bad_tmpdir'	=> 'Kan ikkje skriva til eller laga mellombels mattemappe',
'math_bad_output'	=> 'Kan ikkje skriva til eller laga mattemappe',
'math_notexvc'	        => 'Manglar texvc-program; sjå math/README for konfigurasjon.',
'saveprefs'		=> 'Lagre innstillingane',
'resetprefs'	        => 'Tilbakestill innstillingane',
'oldpassword'	        => 'Gammalt passord',
'newpassword'	        => 'Nytt passord',
'retypenew'		=> 'Skriv nytt passord om att',
'textboxsize'	        => 'Redigering',
'rows'			=> 'Rekker',
'columns'		=> 'Kolonnar',
'searchresultshead'     => 'Innstillingar for søkjeresultat',
'resultsperpage'        => 'Resultat per side',
'contextlines'	        => 'Liner per resultat',
'contextchars'	        => 'Teikn per line i resultatet',
'stubthreshold'         => 'Grense for vising av småartiklar',
'recentchangescount'    => 'Tal titlar på sida "siste endringar"',
'savedprefs'	        => 'Brukarinnstillingane er lagra.',
'timezonelegend'        => 'Tidssone',
'timezonetext'	        => 'Skriv inn tal timar lokal tid skil seg frå tenaren si tid.',
'localtime'	        => 'Lokaltid',
'timezoneoffset'        => 'Skilnad',
'servertime'	        => 'Tenaren si tid er no',
'guesstimezone'         => 'Hent tidssone frå nettlesaren',
'emailflag'	        => 'Ikkje akseptér e-post frå andre brukarar',
'defaultns'		=> 'Søk som standard i desse namneromma:',

# Recent changes
#
'changes'               => 'endringar',
'recentchanges'         => 'Siste endringar',
'recentchangestext'     => 'På denne sida ser du dei sist endra sidene i {{SITENAME}}.',
'rcloaderr'		=> 'Lastar sist endra sider',
'rcnote'		=> 'Nedanfor er dei siste <strong>$1</strong> endringane gjort dei siste <strong>$2</strong> dagane.',
'rcnotefrom'	        => 'Nedanfor er endringane frå <b>$2</b> inntil <b>$1</b> viste.',
'rclistfrom'	        => 'Vis nye endringar frå $1',
'showhideminor'         => '$1 uviktige redigeringar | $2 bottar | $3 innlogga brukarar',
'rclinks'		=> 'Vis siste $1 endringar dei siste $2 dagane<br />$3',
'rchide'		=> 'i $4 form; $1 uviktige endringar; $2 andre namnerom; $3 meir enn éi redigering.',
'rcliu'			=> '; $1 redigeringar av innlogga brukarar',
'diff'			=> 'skil',
'hist'			=> 'hist',
'hide'			=> 'gøym',
'show'			=> 'vis',
'tableform'		=> 'tabell',
'listform'		=> 'liste',
'nchanges'		=> '$1 endringar',
'minoreditletter'       => 'u',
'newpageletter'         => 'n',

# Upload
#
'upload'		=> 'Last opp fil',
'uploadbtn'		=> 'Last opp fil',
'uploadlink'	        => 'Last opp fil',
'reupload'		=> 'Nytt forsøk',
'reuploaddesc'	        => 'Attende til opplastingsskjemaet.',
'uploadnologin'         => 'Ikkje innlogga',
'uploadnologintext'	=> 'Du lyt vera [[{{ns:-1}}:Userlogin|innlogga]] for å kunna laste opp filer.',
'uploadfile'	        => 'Last opp bilete, rekneark, dokument, osb.',
'uploaderror'	        => 'Feil under opplasting av fil',
'uploadtext'	        => 'Denne sida kan brukast til å laste opp filer.

<div style="border: 1px solid grey; background: #ddf; padding: 7px; margin: 0 auto;">
<ul><li>For å bruke eit bilete på ei side, skriv inn ei lenkje av dette slaget: <tt>[[Fil:Eksempelbilete.jpg]]</tt> eller <tt>[[Fil:Eksempelbilete.png|bilettekst]]</tt> eller <tt>[[Filpeikar:Eksempelfil.ogg]]</tt> for lydar og andre filer. For å leggje inn eit bilete som miniatyr, skriv <tt>[[Fil:Eksempelbilete.jpg|mini|Bilettekst]]</tt>. Sjå [[{{ns:12}}:Biletsyntaks|biletesyntaks-hjelp]] for meir informasjon.<br />&nbsp;</li>

<li>Om du lastar opp ei fil med same namn som ei eksisterande fil vil du få spørsmål om å bekrefte, og den eksisterande fila vil ikkje bli sletta.<br />&nbsp;</li>
</ul>

Sjå [[{{ns:12}}:Laste opp fil|hjelp for filopplasting]] for meir informasjon om korleis dette skjemaet fungerer og korleis ein bruker filer på wikisider.
</div>

<p>For å laste opp ei fil bruker du "Browse..." eller "Bla gjennom..."-knappen som
opnar ein standarddialog for val av fil.
Når du vel ei fil, vil namnet på denne fila dukke opp i tekstfeltet ved sida av knappen.
Skriv inn <strong>all</strong> nødvendig informasjon i <i>Samandrag</i> feltet,
kryss av at du ikkje bryt nokon sin opphavsrett,
og klikk til slutt på <i>Last opp fil</i>.</p>',
'uploadlog'		=> 'opplastingslogg',
'uploadlogpage'         => 'Opplastingslogg',
'uploadlogpagetext'     => 'Dette er ei liste over dei filene som har vorte opplasta sist.',
'filename'		=> 'Filnamn',
'filedesc'		=> 'Samandrag',
'filestatus'            => 'Opphavsrettsleg status',
'filesource'            => 'Kjelde',
'affirmation'	        => 'Eg bekreftar at innehavaren av opphavsretten åt denne fila samtykkjer i at fila blir utgjeven under vilkåra for $1.',
'copyrightpage'         => '{{ns:4}}:Opphavsrett',
'copyrightpagename'     => '{{SITENAME}} opphavsrett',
'uploadedfiles'	        => 'Filer som er opplasta',
'noaffirmation'         => 'Du lyt bekrefte at du ikkje bryt nokon sin opphavsrett med å laste opp denne fila.',
'ignorewarning'	        => 'Ignorér åtvaringa og lagre fila likevel.',
'minlength'		=> 'Namnet på fila må ha minst tre teikn.',
'illegalfilename'	=> 'Filnamnet "$1" inneheld teikn som ikkje er tillatne i sidetitlar. Skift namn på fila og prøv på nytt.',
'badfilename'	        => 'Namnet på fila har vorte endra til "$1".',
'badfiletype'	        => '".$1" er ikkje eit tilrådd filformat.',
'largefile'		=> 'Det er frårådd å bruke filer som er større enn 100KB.',
'emptyfile'		=> 'Det ser ut til at fila du lasta opp er tom. Dette kan komma av ein skrivefeil i filnamnet. Sjekk og vurdér om du verkeleg vil laste opp fila.',
'fileexists'		=> 'Ei fil med dette namnet eksisterer allereie, sjekk $1 om du ikkje er sikker på om du vil endre namnet.',
'successfulupload'      => 'Opplastinga er ferdig',
'fileuploaded'	        => 'Fila "$1" er lasta opp. Følg denne lenkja: ($2) åt sida med skildring og fyll ut informasjon om fila, slik som kvar ho kom frå, kva tid ho vart laga og av kven, og andre ting du veit om fila.',
'uploadwarning'         => 'Opplastingsåtvaring',
'savefile'		=> 'Lagre fil',
'uploadedimage'         => 'Lasta opp "$1"',
'uploaddisabled'        => 'Beklagar, funksjonen for opplasting er deaktivert på denne nettenaren.',
'uploadcorrupt'         => 'Denne fila er øydelagd eller har feil etternamn. Sjekk fila og prøv på nytt.',

# Image list
#
'imagelist'		=> 'Filliste',
'imagelisttext'	        => 'Her er ei liste med $1 filer sorterte $2.',
'getimagelist'	        => 'hentar filliste',
'ilshowmatch'	        => 'Vis alle treff på filer med namn',
'ilsubmit'		=> 'Søk',
'showlast'		=> 'Vis dei siste $1 filene sorterte $2.',
'all'			=> 'alle',
'byname'		=> 'etter namn',
'bydate'		=> 'etter dato',
'bysize'		=> 'etter storleik',
'imgdelete'		=> 'slett',
'imgdesc'		=> 'skildr',
'imglegend'		=> 'Forklaring: (skildr) = vis/redigér filskildring.',
'imghistory'	        => 'Filhistorikk',
'revertimg'		=> 'rulltb',
'deleteimg'		=> 'slett',
'deleteimgcompletely'	=> 'Slett alle versjonar av fila',
'imghistlegend'         => 'Forklaring: (no) = dette er den noverande versjonen av fila, (slett) = slett denne versjonen, (rulltb) = tilbake til denne versjonen.<br /><i>Klikk på ein dato for å sjå fila som vart opplasta då</i>.',
'imagelinks'	        => 'Fillenkjer',
'linkstoimage'	        => 'Dei følgjande sidene har lenkjer til dette biletet:',
'nolinkstoimage'        => 'Det finst ikkje noka side med lenkje til dette biletet.',

# Statistics
#
'statistics'	        => 'Statistikk',
'sitestats'		=> '{{SITENAME}}-statistikk',
'userstats' 	        => 'Brukarstatistikk',
'sitestatstext'         => 'Det er i alt \'\'\'$1\'\'\' sider i databasen. Dette inkluderer diskusjonssider, sider om {{SITENAME}}, småsider,
omdirigeringssider, og andre som truleg ikkje kan kallast innhaldssider. Om ein ser bort frå desse sidene, er det \'\'\'$2\'\'\' sider som truleg er innhaldssider.

Alle sidene er vortne viste \'\'\'$3\'\'\' gonger og redigerte \'\'\'$4\'\'\' gonger sidan programvaren vart installert. Det vil seie at kvar side gjennomsnittleg har vorte redigert \'\'\'$5\'\'\' gonger, og vist \'\'\'$6\'\'\' gonger per redigering.',
'userstatstext'         => '{{SITENAME}} har \'\'\'$1\'\'\' registrerte brukarar. \'\'\'$2\'\'\' av desse er administratorar (sjå $3).',

# Maintenance Page
#
'maintenance'		=> 'Vedlikehaldsside',
'maintnancepagetext'	=> 'På denne sida er det ulike verktøy for å halde {{SITENAME}} ved like. Nokre av desse funksjonane er harde for databasen (dei tar lang tid), så lat vera å oppdatere sida kvar gong du har retta ein enkelt ting',
'maintenancebacklink'	=> 'Attende til vedlikehaldssida',
'disambiguations'	=> 'Sider med fleirtydige titlar',
'disambiguationspage'	=> '{{ns:4}}:Lenkjer_til_artiklar_med fleirtydige titlar',
'disambiguationstext'	=> 'Dei følgjande artiklane har lenkjer til <i>artiklar med fleirtydige titlar</i>. Dei burde heller lenkje til ein ikkje-fleirtydig  tittel i staden.<br />Ein artikkeltittel blir behandla som fleirtydig om han har lenkjer frå $1.<br />Lenkjer frå andre namnerom er <i>ikkje</i> opprekna her.',
'doubleredirects'	=> 'Doble omdirigeringar',
'doubleredirectstext'	=> '<b>Merk:</b> Denne lista kan innehalde galne resultat. Dette kjem oftast av at sida har ekstra tekst under den første #OMDIRIGER (#REDIRECT).<br />
Kvar line inneheld lenkjer til den første og den andre omdirigeringa, og den første lina frå den andre omdirigeringsteksten. Det gjev som regel den "rette" målartikkelen, som den første omdirigeringa skulle ha peikt på.',
'brokenredirects'	=> 'Dårlege omdirigeringar',
'brokenredirectstext'	=> 'Dei følgjande omdirigeringane viser til ei side som ikkje finst.',
'selflinks'		=> 'Sider som viser til seg sjølve',
'selflinkstext'		=> 'Dei følgjande sidene inneheld tilvisingar til seg sjølve, og det bør dei ikkje.',
'mispeelings'           => 'Sider med stavefeil',
'mispeelingstext'       => 'Dei følgjande sidene inneheld ein av dei vanlege stavefeila som er lista på $1. Den rette stavemåten kan bli attgjeven i parentes etter feilstavinga (slik).',
'mispeelingspage'       => 'Liste over vanlege stavefeil',
'missinglanguagelinks'  => 'Manglande språklenkjer',
'missinglanguagelinksbutton' => 'Finn manglande språklenkjer for',
'missinglanguagelinkstext' => 'Desse innhaldssidene har <i>ikkje</i> lenkjer til den same sida på $1. Omdirigeringar og undersider er <i>ikkje</i> viste.',

# Miscellaneous special pages
#
'orphans'		=> 'Foreldrelause sider',
'lonelypages'	        => 'Foreldrelause sider',
'uncategorizedpages'	=> 'Ikkje kategoriserte sider',
'unusedimages'	        => 'Ubrukte filer',
'popularpages'	        => 'Populære sider',
'nviews'		=> '$1 visingar',
'wantedpages'	        => 'Etterspurde sider',
'nlinks'		=> '$1 lenkjer',
'allpages'		=> 'Alle sider',
'nextpage'              => 'Neste side ($1)',
'randompage'	        => 'Tilfeldig side',
'shortpages'	        => 'Korte sider',
'longpages'		=> 'Lange sider',
'deadendpages'          => 'Blindvegsider',
'listusers'		=> 'Brukarliste',
'listadmins'	        => 'Administratorliste',
'specialpages'	        => 'Spesialsider',
'spheading'		=> 'Spesialsider for alle brukarar',
'sysopspheading'        => 'Berre for administrator-bruk',
'developerspheading'    => 'Berre for utviklar-bruk',
'protectpage'	        => 'Vern side',
'recentchangeslinked'   => 'Relaterte endringar',
'rclsub'		=> '(til sider med lenkje frå "$1")',
'debug'			=> 'Feilsøk',
'newpages'		=> 'Nye sider',
'ancientpages'		=> 'Eldste sider',
'intl'		        => 'Språklenkjer',
'move'                  => 'Flytt',
'movethispage'	        => 'Flytt side',
'unusedimagestext'      => '<p>Merk deg at andre internettsider kan ha lenkjer til filer som er lista her. Dei kan difor vere i aktiv bruk.',
'booksources'	        => 'Bokkjelder',
'categoriespagetext'    => 'Wikien har følgjande kategoriar.',
'booksourcetext'        => 'Her er ei liste over lenkjer til internettsider som låner ut og/eller sel nye og/eller brukte bøker, og som kanskje har meir informasjon om bøker du leitar etter. {{SITENAME}} er ikkje nødvendigvis assosiert med nokon av desse sidene, og lista er <b>ikkje</b> å rekne som ei spesifikk tilråding om å bruke dei.',
'isbn'	                => 'ISBN',
'rfcurl'                => 'http://www.ifi.uio.no/doc/rfc/rfc$1.txt',
'alphaindexline'        => '$1 til $2',
'version'		=> 'Versjon',

# Email this user
#
'mailnologin'	        => 'Inga avsendaradresse',
'mailnologintext'       => 'Du lyt vera [[{{ns:-1}}:Userlogin|innlogga]] og ha ei gyldig e-postadresse sett i [[{{ns:-1}}:Preferences|brukarinnstillingane]] for å sende e-post åt andre brukarar.',
'emailuser'		=> 'Send e-post åt denne brukaren',
'emailpage'		=> 'Send e-post åt brukar',
'emailpagetext'	        => 'Om denne brukaren har gjeve ei gyldig e-postadresse i brukarinnstillingane sine, vil dette skjemaet sende ei enkel melding. E-postadressa di frå brukarinnstillingane dine vil vera synleg i "Frå"-feltet i denne e-posten, slik at mottakaren kan svara deg.',
'usermailererror'       => 'E-post systemet gav feilmelding: ',
'defemailsubject'       => '{{SITENAME}} e-post',
'noemailtitle'	        => 'Inga e-postadresse',
'noemailtext'	        => 'Denne brukaren har ikkje gjeve ei gyldig e-postadresse, eller har valt å ikkje opne for e-post frå andre brukarar.',
'emailfrom'		=> 'Frå',
'emailto'		=> 'Åt',
'emailsubject'	        => 'Emne',
'emailmessage'	        => 'Melding',
'emailsend'		=> 'Send',
'emailsent'		=> 'E-posten er sendt',
'emailsenttext'         => 'E-postmeldinga er sendt.',

# Watchlist
#
'watchlist'		=> 'Overvakingsliste',
'watchlistsub'	        => '(for brukar "$1")',
'nowatchlist'	        => 'Du har ikkje noko i overvakingslista di.',
'watchnologin'	        => 'Ikkje innlogga',
'watchnologintext'	=> 'Du lyt vera [[{{ns:-1}}:Userlogin|innlogga]] for å kunna endre overvakingslista.',
'addedwatch'	        => 'Lagt til overvakingslista',
'addedwatchtext'        => 'Sida "$1" er lagt til [[{{ns:-1}}:Watchlist|overvakingslista]] di. Framtidige endringar av denne sida og den tilhøyrande diskusjonssida vil bli oppførde her, og sida vil vera \'\'\'utheva\'\'\' i [[{{ns:-1}}:Recentchanges|siste endringar]] lista for å gjera deg merksam på henne.

Om du seinere vil fjerne sida frå overvakingslista, klikk "Fjern overvaking" på den aktuelle sida.',
'removedwatch'	        => 'Fjerna frå overvakingslista',
'removedwatchtext'      => 'Sida "$1" er fjerna frå overvakingslista.',
'watch'                 => 'Overvak',
'watchthispage'	        => 'Overvak denne sida',
'unwatch'               => 'Fjern overvaking',
'unwatchthispage'       => 'Fjern overvaking',
'notanarticle'	        => 'Ikkje innhaldsside',
'watchnochange'         => 'Ingen av sidene i overvakingslista er endra i den valde perioden.',
'watchdetails'          => 'Du har $1 sider i overvakingslista di (diskusjonssider fråtrukke); du kan <a href="$4">vise og redigere den fullstendige lista</a>. I perioden vald under har brukarar gjort alt i alt $2 endringar på {{SITENAME}}',
'watchmethod-recent'    => 'sjekkar om siste endringar for dei overvaka sidene',
'watchmethod-list'      => 'sjekkar om dei overvaka sidene er vortne endra i det siste',
'removechecked'         => 'Fjern dei valde sidene frå overvakingslista',
'watchlistcontains'     => 'Overvakingslista inneheld $1 sider.',
'watcheditlist'         => 'Her er ei alfabetisk liste over sidene i overvakingslista. Velj dei sidene du vil fjerna frå overvakingslista 
og klikk på "Fjern overvaking"-knappen nedst på sida.',
'removingchecked'       => 'Fjernar dei valde sidene frå overvakingslista ...',
'couldntremove'         => 'Kunne ikkje fjerne "$1"...',
'iteminvalidname'       => 'Problem med "$1", ugyldig namn...',
'wlnote'                => 'Nedanfor er dei siste $1 endringane dei siste <b>$2</b> timane.',
'wlshowlast' 		=> 'Vis siste $1 timar $2 dagar $3',
'wlsaved'		=> 'Dette er ein mellomlagra versjon av overvakingslista di.',

# Delete/protect/revert
#
'deletepage'	        => 'Slett side',
'confirm'		=> 'Bekreft',
'excontent'             => 'innhaldet var:',
'exbeforeblank'         => 'innhaldet før sida vart tømd var:',
'exblank'               => 'sida var tom',
'confirmdelete'         => 'Bekreft sletting',
'deletesub'		=> '(Slettar "$1")',
'historywarning'        => 'Åtvaring: Sida du held på å slette har ein historikk: ',
'confirmdeletetext'     => 'Du held på å varig slette ei side eller eit bilete saman med heile den tilhøyrande historikken frå databasen. Bekreft at du verkeleg vil gjera dette, at du skjønner konsekvensane, og at du gjer dette i tråd med [[{{ns:4}}:Retningsliner|retningslinene]].',
'confirmcheck'	        => 'Ja, eg vil verkeleg slette.',
'actioncomplete'        => 'Ferdig.',
'deletedtext'	        => '"$1" er sletta. Sjå $2 for eit oversyn over dei siste slettingane.',
'deletedarticle'        => 'sletta "$1"',
'dellogpage'	        => 'Slettingslogg',
'dellogpagetext'        => 'Her er ei liste over dei siste slettingane.',
'deletionlog'	        => 'slettingslogg',
'reverted'		=> 'Tilbakerulla til ein tidlegare versjon',
'deletecomment'	        => 'Grunn for sletting',
'imagereverted'         => 'Tilbakerulling av tidlegare versjon ferdig.',
'rollback'		=> 'Rull tilbake redigeringar',
'rollback_short'        => 'Rull tilbake',
'rollbacklink'	        => 'rull tilbake',
'rollbackfailed'        => 'Kunne ikkje rulle tilbake',
'cantrollback'	        => 'Kan ikkje rulle tilbake fordi den siste brukaren er den einaste forfattaren.',
'alreadyrolled'	        => 'Kan ikkje rulle tilbake den siste redigeringa av [[$1]] gjort av [[{{ns:2}}:$2|$2]] ([[{{ns:3}}:$2|brukardiskusjon]]) fordi nokon andre allereie har redigert sida eller fjerna redigeringa. 

Den siste redigeringa vart gjort av [[{{ns:2}}:$3|$3]] ([[{{ns:3}}:$3|brukardiskusjon]]).',
#   only shown if there is an edit comment
'editcomment'           => 'Samandraget for redigeringa var: "<i>$1</i>".',
'revertpage'	        => 'Tilbakerulla redigering gjort av $2 til tidlegare versjon redigert av $1',
'protectlogpage'        => 'Vernelogg',
'protectlogtext'        => 'Dette er ei liste over sider som har blitt verna eller har fått fjerna vern. Sjå [[{{ns:4}}:Verna side]] for meir info.',
'protectedarticle'      => 'verna $1',
'unprotectedarticle'    => 'fjerna vern av $1',
'protectsub'            => '(Vernar "$1")',
'confirmprotecttext'    => 'Er du sikker på at du vil verne denne sida?',
'confirmprotect'        => 'Bekreft vern',
'protectcomment'        => 'Grunn til verning',
'unprotectsub'          => '(Fjernar vern frå "$1")',
'confirmunprotecttext'  => 'Er du sikker på at du vil fjerne vern av denne sida?',
'confirmunprotect'      => 'Bekreft fjerning av vern',
'unprotectcomment'      => 'Grunn til fjerning av vern',
'protectreason'         => '(gje ein grunn)',

# Undelete
'undelete'              => 'Attopprett ei sletta side',
'undeletepage'          => 'Sjå og attopprett sletta sider',
'undeletepagetext'      => 'Dei følgjande sidene er sletta, men dei finst enno i arkivet og kan attopprettast. Arkivet blir periodevis sletta.',
'undeletearticle'       => 'Attopprett sletta side',
'undeleterevisions'     => '$1 versjonar arkiverte',
'undeletehistory'       => 'Om du attopprettar sida, vil alle versjonane i historikken også bli attoppretta. Dersom ei ny side med det same namnet 
er oppretta sidan den gamle sida vart sletta, vil dei attoppretta versjonane dukke opp i historikken, og den nyaste versjonen vil bli verande som den er.',
'undeleterevision'      => 'Sletta versjon frå $1',
'undeletebtn'           => 'Attopprett!',
'undeletedarticle'      => 'attoppretta "$1"',
'undeletedtext'         => 'Sida [[$1]] er attoppretta. Sjå [[{{ns:4}}:Slettingslogg]] for oversyn over nylege slettingar og attopprettingar.',

# Contributions
#
'contributions'	        => 'Brukarbidrag',
'mycontris'             => 'Eigne bidrag',
'contribsub'	        => 'For $1',
'nocontribs'	        => 'Det vart ikkje funne nokon endringar som passa desse vilkåra.',
'ucnote'	        => 'Her er dei siste <b>$1</b> endringane frå denne brukaren dei siste <b>$2</b> dagane.',
'uclinks'	        => 'Vis dei siste $1 endringane; vis dei siste $2 dagane.',
'uctop'		        => ' (øvst)' ,

# What links here
#
'whatlinkshere'	        => 'Lenkjer hit',
'notargettitle'         => 'Inkje mål',
'notargettext'	        => 'Du har ikkje spesifisert noka målside eller nokon brukar å bruke denne funksjonen på.',
'linklistsub'	        => '(Liste over lenkjer)',
'linkshere'	        => 'Desse sidene har lenkjer hit:',
'nolinkshere'	        => 'Inga side har lenkje hit.',
'isredirect'	        => 'omdirigeringsside',

# Block/unblock IP
#
'blockip'		=> 'Blokkér brukar',
'blockiptext'	        => 'Bruk skjemaet nedanfor for å blokkere skrivetilgangen frå ei spesifikk IP-adresse eller brukarnamn. Dette bør berre gjerast for å hindre herverk, og i samsvar med [[{{ns:4}}:Retningsliner|retningslinene]]. Skriv grunngjeving nedanfor (t.d. med sitat frå sider som har vortne utsett for herverk). Opphørstid for blokkeringa skriv ein med GNU standard format, som er beskrive i [http://www.gnu.org/software/tar/manual/html_chapter/tar_7.html tar manualen] (engelsk), t.d. "1 hour", "2 days", "next Wednesday", "1 January 2017". Alternativt kan ei blokkering vera "indefinite" (ubestemd) eller "infinite" (uendeleg).

For informasjon om korleis ein kan blokkere seriar av IP-adresser, sjå [[{{ns:12}}:Blokkere IP-adresse serie|hjelp]]. For å oppheve blokkering, sjå  [[{{ns:-1}}:Ipblocklist|blokkeringslista]].',
'ipaddress'		=> 'IP-adresse/brukarnamn',
'ipbreason'		=> 'Grunngjeving',
'ipbsubmit'		=> 'Blokkér denne brukaren',
'badipaddress'	        => 'IP-adressa var ugyldig eller brukarblokkering er deaktivert på tenaren.',
'noblockreason'         => 'Du må gje ein grunn for blokkeringa.',
'blockipsuccesssub'     => 'Blokkering utført',
'blockipsuccesstext'    => '"$1" er blokkert. <br />Sjå [[{{ns:-1}}:Ipblocklist|blokkeringslista]] for alle blokkeringar.',
'unblockip'		=> 'Opphev blokkering',
'unblockiptext'	        => 'Bruk skjemaet nedanfor for å oppheve blokkeringa av ein tidlegare blokkert brukar.',
'ipusubmit'		=> 'Opphev blokkering',
'ipusuccess'	        => '"$1" har fått oppheva blokkeringa',
'ipblocklist'	        => 'Liste over blokkerte IP-adresser og brukarnamn',
'blocklistline'	        => '$1, $2 blokkerte $3 (opphørstid $4)',
'blocklink'		=> 'blokkér',
'unblocklink'	        => 'opphev blokkering',
'contribslink'	        => 'bidrag',
'autoblocker'	        => 'Automatisk blokkert fordi du deler IP-adresse med [[{{ns:2}}:$1|$1]]. Grunngjeving gjeve for blokkeringa av $2 var: "$2".',
'blocklogpage'	        => 'Blokkeringslogg',
'blocklogentry'	        => 'Blokkerte "$1" med opphørstid $2',
'blocklogtext'	        => 'Dette er ein logg over blokkeringar og oppheving av blokkeringar gjorde av [[{{ns:4}}:Administratorar|administratorar]].
IP-adresser som blir automatisk blokkerte er ikkje lista her. Sjå [[{{ns:-1}}:Ipblocklist|blokkeringslista]] for alle aktive blokkeringar.',
'unblocklogentry'	=> 'oppheva blokkering av "$1"',
'range_block_disabled'	=> 'Funksjonen for blokkering av IP-adresse-seriar er deaktivert på tenaren.',
'ipb_expiry_invalid'	=> 'Ugyldig opphørstid.',
'ip_range_invalid'	=> 'Ugyldig IP-adresseserie.',
'proxyblocker'	        => 'Proxy-blokkerar',
'proxyblockreason'	=> 'Du er blokkert frå å redigere fordi IP-adressa di tilhøyrer ein open mellomtenar (proxy). Du bør kontakte internettleverandøren din eller kundesørvis og gje dei beskjed, ettersom dette er eit alvorleg sikkerheitsproblem.',
'proxyblocksuccess'	=> 'Utført.',

# Developer tools
#
'lockdb'		=> 'Skrivevern (lock) database',
'unlockdb'		=> 'Opphev skrivevern (unlock) av databasen',
'lockdbtext'	        => 'Å skriveverne databasen vil gjere det umogleg for alle brukarar å redigere sider, endre innstillingane sine, redigere overvakingslistene sine og andre ting som krev endringar i databasen. Bekreft at du har til hensikt å gjera dette, og at du vil låse opp databasen når vedlikehaldet er ferdig.',
'unlockdbtext'	        => 'Å oppheve skrivevernet på databasen fører til at alle brukarar kan redigere sider, endre innstillingane sine, redigere  overvakingslistene sine og andre ting som krev endringar i databasen att. Bekreft at du har til hensikt å gjera dette.',
'lockconfirm'	        => 'Ja, eg vil verkeleg skriveverne databasen.',
'unlockconfirm'	        => 'Ja, eg vil verkeleg oppheve skrivevernet på databasen.',
'lockbtn'		=> 'Skrivevern databasen',
'unlockbtn'		=> 'Opphev skrivevern på databasen',
'locknoconfirm'         => 'Du har ikkje bekrefta handlinga.',
'lockdbsuccesssub'      => 'Databasen er no skriveverna',
'unlockdbsuccesssub'    => 'Srivevernet på databasen er no oppheva',
'lockdbsuccesstext'     => 'Databasen er no skriveverna. <br />Hugs å oppheve skrivevernet når du er ferdig med vedlikehaldet.',
'unlockdbsuccesstext'   => 'Skrivevernet er oppheva.',

# SQL query
#
'asksql'		=> 'SQL-førespurnad',
'asksqltext'	        => 'Bruk skjemaet nedanfor for direkte førespurnader i databasen. Bruk enkle hermeteikn (\'som dette\') for å skilje strenger.
Dette kan ofte belaste nettenaren kraftig, så bruk denne funksjonen med varsemd.',
'sqlislogged'	        => 'Merk deg at alle SQL-førespurnader blir lagra i ei loggfil.',
'sqlquery'		=> 'Skriv inn førespurnad',
'querybtn'		=> 'Send førespurnad',
'selectonly'	        => 'Ingen skrive-førespurnader er støtta.',
'querysuccessful'       => 'Førespurnaden er gjennomført',

# Make sysop
'makesysoptitle'	=> 'Gjer brukar om til administrator',
'makesysoptext'		=> 'Dette skjemaet kan brukast av byråkratar til å gjera vanlege brukarar om til administratorar. Skriv inn namnet på brukaren i tekstboksen og trykk på knappen for å gjere brukaren om til administrator',
'makesysopname'		=> 'Brukarnamn:',
'makesysopsubmit'	=> 'Gjer brukaren om til administrator',
'makesysopok'		=> '<b>Brukaren "$1" er no administrator</b>',
'makesysopfail'		=> '<b>Brukaren "$1" kunne ikkje gjerast om til administrator. (Skreiv du brukarnamnet rett?)</b>',
'setbureaucratflag'     => 'Gjer til byråkrat òg',
'bureaucratlog'		=> 'Byråkratlogg',
'bureaucratlogentry'	=> 'Tilgang for brukar "$1" sett til "$2"',
'rights'		=> 'Tilgang:',
'set_user_rights'	=> 'Set brukartilgang',
'user_rights_set'	=> '<b>Brukartilgang for "$1" er oppdatert</b>',
'set_rights_fail'	=> '<b>Brukartilgang for "$1" kunne ikkje setjast. (Skreiv du brukarnamnet rett?)</b>',
'makesysop'             => 'Gjer brukar om til administrator',

# Move page
#
'movepage'		=> 'Flytt side',
'movepagetext'	        => 'Ved å bruka skjemaet nedanfor kan du få omdøypt ei side og flytt heile historikken til det nye namnet. Den gamle tittelen vil bli ei omdirigeringsside til den nye tittelen. Lenkjer til den gamle tittelen vil ikkje bli endra. Pass på å [[Spesial:Maintenance|sjekke]] for doble eller dårlege omdirigeringar. Du er ansvarleg for at alle lenkjene stadig peiker dit det er meininga at dei skal peike.

Merk at sida \'\'\'ikkje\'\'\' kan flyttast dersom det allereie finst ei side med den nye tittelen. Du kan likevel flytte ei side attende dit ho vart flytta frå dersom du gjer ein feil, så lenge den sida du flyttar tilbake til ikkje har vorte endra sidan flyttinga.

<b>ÅTVARING!</b> Dette kan vera ei drastisk og uventa endring for ei populær side; ver sikker på at du skjønner konsekvensane av dette før du fortset.',
'movepagetalktext'      => 'Den tilhøyrande diskusjonssida, om ho finst, vil automatisk bli flytt med sida \'\'\'med mindre:\'\'\'
*Du flytter sida til eit anna namnerom,
*Ei diskusjonsside som ikkje er tom allereie finst under det nye namnet, eller
*Du fjernar markeringa i boksen nedanfor.

I desse falla lyt du flytte eller flette saman sida manuelt. Om det ikkje er mogleg for deg å gjera dette kan du kontakte ein [[{{ns:4}}:Administratorar|administrator]], men <b>ikkje</b> bruk klipp-og-lim metoden sidan dette ikkje tek vare på redigeringshistorikken.',
'movearticle'	        => 'Flytt side',
'movenologin'	        => 'Ikkje innlogga',
'movenologintext'       => 'Du lyt vera registrert brukar og vera [[{{ns:-1}}:Userlogin|innlogga]] for å flytte ei side.',
'newtitle'		=> 'Til ny tittel',
'movepagebtn'	        => 'Flytt side',
'pagemovedsub'	        => 'Flyttinga er gjennomført',
'pagemovedtext'         => 'Sida "[[$1]]" er flytt til "[[$2]]".',
'articleexists'         => 'Ei side med det namnet finst allereie, eller det namnet du har valt er ikkje gyldig. Vel eit anna namn.',
'talkexists'	        => 'Innhaldssida vart flytt, men diskusjonssida som høyrer til kunne ikkje flyttast fordi det allereie finst ei side med den nye tittelen. Du lyt flette dei saman manuelt. Dersom det ikkje er mogleg for deg å gjera dette kan du kontakte ein [[{{ns:4}}:Administratorar|administrator]] &#8212; men <b>ikkje</b> bruk klipp-og-lim metoden sidan dette ikkje tek vare på redigeringshistorikken.',
'movedto'		=> 'flytta til',
'movetalk'		=> 'Flytt diskusjonssida òg om ho finst.',
'talkpagemoved'         => 'Diskusjonssida som høyrer til vart òg flytt.',
'talkpagenotmoved'      => 'Diskusjonssida som høyrer til vart <strong>ikkje</strong> flytt.',
'1movedto2'		=> '$1 flytt to $2',
'1movedto2_redir'       => '$1 flytt til $2 over omdirigering',

# Export
'export'		=> 'Eksportér sider',
'exporttext'	        => 'Du kan eksportere teksten og redigeringshistorikken til ei side eller ein serie sider, pakka inn i litt XML. I framtida kan det hende at dette att kan bli importert til ei anna wiki som brukar MediaWiki-programvaren, men det er ikkje støtte for dette i denne versjonen av MediaWiki.

For å eksportere sider, skriv tittelen i tekstboksen nedanfor, ein tittel per line, og velj om du vil ha med alle versjonane eller berre siste versjon.

Dersom du berre vil ha den siste versjonen kan du òg bruke ei lenkje, t.d. [[{{ns:Special}}:Export/MediaWiki]] for [[MediaWiki]] sida.',
'exportcuronly'	        => 'Berre eksportér siste versjonen, ikkje med heile historikken.',

# Namespace 8 related
'allmessages'	        => 'Alle systemmeldingar',
'allmessagestext'	=> 'Dette er ei liste over alle systemmeldingar som er tilgjengelege i MediaWiki: namnerommet.',

# Thumbnails
'thumbnail-more'	=> 'Forstørr',
'missingimage'		=> '<b>Bilete manglar</b><br /><i>$1</i>',

# Special:Import
'import'	        => 'Importér sider',
'importtext'	        => 'Du må først eksportere sida du vil importere til ei fil som du lagrar på maskina di, deretter kan du laste han inn her.
For å eksportere bruker du [[{{ns:-1}}:Export|eksportsida]] på kjeldewikien, hugs at kjelda òg må bruke MediaWiki-programvaren.',
'importfailed'	        => 'Importeringa var mislukka: $1',
'importnotext'	        => 'Tom eller ingen tekst',
'importsuccess'	        => 'Importeringa er ferdig!',
'importhistoryconflict' => 'Det kan vera at det er konflikt i historikken (kanskje sida vart importert før)',

# Keyboard access keys for power users
'accesskey-search'      => 'f',
'accesskey-minoredit'   => 'i',
'accesskey-save'        => 's',
'accesskey-preview'     => 'p',
'accesskey-compareselectedversions' => 'v',

# tooltip help for some actions, most are in Monobook.js
'tooltip-search'        => 'Søk denne wikien [alt-f]',
'tooltip-minoredit'     => 'Merk dette som ei uviktig endring [alt-i]',
'tooltip-save'          => 'Lagre endringane dine [alt-s]',
'tooltip-preview'       => 'Førehandsvis endringane dine, bruk denne funksjonen før du lagrar! [alt-p]',
'tooltip-compareselectedversions' => 'Sjå endringane mellom dei valde versjonane av denne sida. [alt-v]',

# Metadata
'nodublincore'          => 'Funksjonen for Dublin Core RDF metadata er deaktivert på denne tenaren.',
'nocreativecommons'     => 'Funksjonen for Creative Commons RDF er deaktivert på denne tenaren.',
'notacceptable'         => 'Wikitenaren kan ikkje gje data i eit format som programmet ditt kan lesa.',

# Attribution
'anonymous'             => 'Anonym(e) brukar(ar) av {{SITENAME}}',
'siteuser'              => '{{SITENAME}} brukar $1',
'lastmodifiedby'        => 'Denne sida vart sist redigert $1 av $2.',
'and'                   => 'og',
'othercontribs'         => 'Basert på arbeid av $1.',
'others'                => 'andre',
'siteusers'             => '{{SITENAME}} brukar(ar) $1',
'creditspage'           => 'Sidegodskriving',
'nocredits'             => 'Det finst ikkje ikkje nokon godskrivingsinformasjon for denne sida.',

# Spam protection
'spamprotectiontitle'   => 'Filter for vern mot reklame',
'spamprotectiontext'    => 'Sida du prøvde å lagre vart blokkert av filteret for vern mot reklame (spam). Dette skjedde truleg på grunn av ei ekstern lenkje.',
'subcategorycount'      => 'Det er $1 underkategoriar av denne kategorien.',
'categoryarticlecount'  => 'Det er $1 innhaldssider i denne kategorien.',
'usenewcategorypage'    => '1

Skriv "0" som første bokstav for å slå av den nye kategoriutsjånaden.',

# Info page
'infosubtitle'          => 'Informasjon om side',
'numedits'              => 'Tal redigeringar (innhaldsside): $1',
'numtalkedits'          => 'Tal redigeringar (diskusjonsside): $1',
'numwatchers'           => 'Tal brukarar som overvakar: $1',
'numauthors'            => 'Tal ulike bidragsytarar (innhaldsside): $1',
'numtalkauthors'        => 'Tal ulike bidragsytarar (diskusjonsside): $1',

# stylesheets
'Monobook.css'          => '/*
<pre>
*/

a { text-decoration: underline }

/* Donations link to be uncommented during fundraising drives  */
#siteNotice {
    margin-top:5px;
    padding-left: 4px;
    font-style: italic;
    text-align: center;
}

/* Make all non-namespace pages have a light blue content area. This is done by
   setting the background color for all #content areas to light blue and then
   overriding it for any #content enclosed in a .ns-0 (main namespace). I then
   do the same for the "tab" background colors. --Lupo */

#content {
    background: #F8FCFF; /* a light blue */
}

.ns-0 * #content {
    background: white;
}

#mytabs li {
    background: #F8FCFF;
}

.ns-0 * #mytabs li {
    background: white;
}

#mytabs li a {
    background-color: #F8FCFF;
}

.ns-0 * #mytabs li a {
    background-color: white;
}

#p-cactions li {
    background: #F8FCFF;
}

.ns-0 * #p-cactions li {
    background: white;
}

#p-cactions li a {
    background-color: #F8FCFF;
}

.ns-0 * #p-cactions li a {
    background-color: white;
}

#bodyContent #siteSub a {
    color: #000;
    text-decoration: none;
    background-color: transparent;
    background-image: none;
    padding-right: 0;
}

/* Bold edit this page link to encourage newcomers */
#ca-edit a { font-weight: bold !important; }

/* Display ([[MediaWiki:Alreadyloggedin]]) in red and bold */
div.alreadyloggedin { color: red; font-weight: bold; }

@media print {
    /* Do not print edit link in templates using Template:Ed
       Do not print certain classes that shouldnt appear on paper */
    .editlink, .noprint, .metadata, .dablink { display: none }
}

/* Temp IE fix to stop it re-fetching the logo */
#p-logo a {
  background-image: url(http://upload.wikimedia.org/wikipedia/nn/b/bc/Wiki.png)!important;
}

/* Style for "notices" */
.notice {
    text-align: justify;
    margin: 1em 0.5em;
    padding: 0.5em;
}

#disambig {
    border-top: 3px double #cccccc; 
    border-bottom: 3px double #cccccc;
}

/*
</pre>
*/
',

'Monobook.js'           => '/*
<pre>
*/
/* verktøytips og snøggtastar */
ta = new Object();
ta[\'pt-userpage\']             = new Array(\'.\',\'Brukarsida mi\'); 
ta[\'pt-anonuserpage\']         = new Array(\'.\',\'Brukarsida for ip-adressa du redigerer under\'); 
ta[\'pt-mytalk\']               = new Array(\'n\',\'Diskusjonssida mi\'); 
ta[\'pt-anontalk\']             = new Array(\'n\',\'Diskusjon om redigeringar gjorde av denne ip-adressa\'); 
ta[\'pt-preferences\']          = new Array(\'\',\'Innstillingane mine\'); 
ta[\'pt-watchlist\']            = new Array(\'l\',\'Lista over sidene du overvakar.\'); 
ta[\'pt-mycontris\']            = new Array(\'y\',\'Liste over bidraga mine\'); 
ta[\'pt-login\']                = new Array(\'o\',\'Du er oppfordra til å logge inn, men det er ikkje obligatorisk.\'); 
ta[\'pt-anonlogin\']            = new Array(\'o\',\'Du er oppfordra til å logge inn, men det er ikkje obligatorisk.\'); 
ta[\'pt-logout\']               = new Array(\'o\',\'Logg ut\'); 
ta[\'ca-talk\']                 = new Array(\'t\',\'Diskusjon om innhaldssida\'); 
ta[\'ca-edit\']                 = new Array(\'e\',\'Du kan redigere denne sida. Ver venleg og bruk førehandsvisings-knappen før du lagrar.\'); 
ta[\'ca-addsection\']           = new Array(\'+\',\'Legg til ein del på denne diskusjonssida.\'); 
ta[\'ca-viewsource\']           = new Array(\'e\',\'Denne sida er verna, men du kan sjå kjeldeteksten.\'); 
ta[\'ca-history\']              = new Array(\'h\',\'Eldre versjonar av denne sida.\'); 
ta[\'ca-protect\']              = new Array(\'=\',\'Vern denne sida\'); 
ta[\'ca-delete\']               = new Array(\'d\',\'Slett denne sida\'); 
ta[\'ca-undelete\']             = new Array(\'d\',\'Attopprett denne sida\'); 
ta[\'ca-move\']                 = new Array(\'m\',\'Flytt denne sida\'); 
ta[\'ca-nomove\']               = new Array(\'\',\'Du har ikkje tilgang til å flytte denne sida\'); 
ta[\'ca-watch\']                = new Array(\'w\',\'Legge denne sida til i overvakingslista di\'); 
ta[\'ca-unwatch\']              = new Array(\'w\',\'Fjern denne sida frå overvakingslista di\'); 
ta[\'search\']                  = new Array(\'f\',\'Søk gjennom denne wikien\'); 
ta[\'p-logo\']                  = new Array(\'\',\'Hovudside\'); 
ta[\'n-mainpage\']              = new Array(\'z\',\'Gå til hovudsida\'); 
ta[\'n-portal\']                = new Array(\'\',\'Om prosjektet, kva du kan gjera, kor ein finn saker og ting\'); 
ta[\'n-currentevents\']         = new Array(\'\',\'Aktuelt\'); 
ta[\'n-recentchanges\']         = new Array(\'r\',\'Liste over dei siste endringane som er gjort på wikien.\'); 
ta[\'n-randompage\']            = new Array(\'x\',\'Vis ei tilfeldig side\'); 
ta[\'n-help\']                  = new Array(\'\',\'Hjelp til å bruke alle funksjonane.\'); 
ta[\'n-sitesupport\']           = new Array(\'\',\'Støtt oss!\'); 
ta[\'t-whatlinkshere\']         = new Array(\'j\',\'Liste over alle wikisidene som har lenkjer hit\'); 
ta[\'t-recentchangeslinked\']   = new Array(\'k\',\'Siste endringar på sider som har lenkjer hit\'); 
ta[\'feed-rss\']                = new Array(\'\',\'RSS-mating for denne sida\'); 
ta[\'feed-atom\']               = new Array(\'\',\'Atom-mating for denne sida\'); 
ta[\'t-contributions\']         = new Array(\'\',\'Sjå liste over bidrag frå denne brukaren\'); 
ta[\'t-emailuser\']             = new Array(\'\',\'Send ein e-post til denne brukaren\'); 
ta[\'t-upload\']                = new Array(\'u\',\'Last opp filer\'); 
ta[\'t-specialpages\']          = new Array(\'q\',\'Liste over spesialsider\'); 
ta[\'ca-nstab-main\']           = new Array(\'c\',\'Vis innhaldssida\'); 
ta[\'ca-nstab-user\']           = new Array(\'c\',\'Vis brukarsida\'); 
ta[\'ca-nstab-media\']          = new Array(\'c\',\'Direktelenkje (filpeikar) til fil\'); 
ta[\'ca-nstab-special\']        = new Array(\'\',\'Dette er ei spesialside, du kan ikkje redigere ho.\'); 
ta[\'ca-nstab-wp\']             = new Array(\'c\',\'Vis prosjektside\'); 
ta[\'ca-nstab-image\']          = new Array(\'c\',\'Vis filside\'); 
ta[\'ca-nstab-mediawiki\']      = new Array(\'c\',\'Vis systemmelding\'); 
ta[\'ca-nstab-template\']       = new Array(\'c\',\'Vis mal\'); 
ta[\'ca-nstab-help\']           = new Array(\'c\',\'Vis hjelpeside\'); 
ta[\'ca-nstab-category\']       = new Array(\'c\',\'Vis kategoriside\');
/*
</pre>
*/
'

);

class LanguageNn extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesNn;
		return $wgNamespaceNamesNn;
	}

	function getDefaultUserOptions () {
		global $wgDefaultUserOptionsNn;
		return $wgDefaultUserOptionsNn;
		}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsNn;
		return $wgQuickbarSettingsNn;
	}

	function getSkinNames() {
		global $wgSkinNamesNn;
		return $wgSkinNamesNn;
	}

	function getMathNames() {
		global $wgMathNamesNn;
		return $wgMathNamesNn;
	}

	function getDateFormats() {
		global $wgDateFormatsNn;
		return $wgDateFormatsNn;
	}

	function getBookstoreList () {
		global $wgBookstoreListNn ;
		return $wgBookstoreListNn ;
	}

	function getMagicWords() 
	{
		global $wgMagicWordsNn;
		return $wgMagicWordsNn;
	}

	function getNsText( $index ) {
		global $wgNamespaceNamesNn;
		return $wgNamespaceNamesNn[$index];
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesNn;

		foreach ( $wgNamespaceNamesNn as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		return false;
	}

	# Inherit userAdjust()

	function date( $ts, $adj = false )
	{
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$d = (0 + substr( $ts, 6, 2 )) . '. ' .
		  $this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) . ' ' .
		  substr( $ts, 0, 4 );
		return $d;
	}

	function time( $ts, $adj = false )
	{
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$t = substr( $ts, 8, 2 ) . ':' . substr( $ts, 10, 2 );
		return $t;
	}

	function timeanddate( $ts, $adj = false )
	{
		return $this->date( $ts, $adj ) . ' kl. ' . $this->time( $ts, $adj );
	}

	# Inherit rfc1123()

	function getMessage( $key )
	{
		global $wgAllMessagesNn;
		if( isset( $wgAllMessagesNn[$key] ) ) {
			return $wgAllMessagesNn[$key];
		} else {
			return ''; # ??
		}
	}

	# Inherit ucfirst()
	
	# Inherit checkTitleEncoding()
	
	# Inherit stripForSearch()
	
	# Inherit setAltEncoding()
	
	# Inherit recodeForEdit()
	
	# Inherit recodeInput()
	
	# Inherit replaceDates()
	
	# Inherit isRTL()

}

?>
