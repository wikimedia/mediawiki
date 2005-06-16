<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */

require_once( 'LanguageUtf8.php' );

/* private */ $wgNamespaceNamesFur = array(
	NS_MEDIA			=> 'Media',
	NS_SPECIAL			=> 'Speciâl',
	NS_MAIN				=> '',
	NS_TALK				=> 'Discussion',
	NS_USER				=> 'Utent',
	NS_USER_TALK			=> 'Discussion_utent',        
	NS_PROJECT			=> $wgMetaNamespace,
	NS_PROJECT_TALK			=> 'Discussion_'.$wgMetaNamespace,
	NS_IMAGE			=> 'Figure',
	NS_IMAGE_TALK			=> 'Discussion_figure',
	NS_MEDIAWIKI			=> 'MediaWiki',
	NS_MEDIAWIKI_TALK		=> 'Discussion_MediaWiki',
	NS_TEMPLATE			=> 'Model',
	NS_TEMPLATE_TALK		=> 'Discussion_model',
	NS_HELP				=> 'Jutori',
	NS_HELP_TALK			=> 'Discussion_jutori',
	NS_CATEGORY			=> 'Categorie',
	NS_CATEGORY_TALK		=> 'Discussion_categorie'
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsFur = array(
	'Nissune', 'Fis a Çampe', 'Fis a Drete', 'Flutant a çampe'
);

/* private */ $wgSkinNamesFur = array(
	'nostalgia'		=> 'Nostalgie',
) + $wgSkinNamesEn;

// will make them not show up on the "Special Pages" page, which
// is the right thing for some of them (such as the "targeted" ones).

/* private */ $wgValidSpecialPagesFur = array(
	'Userlogin'     => '',
	'Userlogout'    => '',
	'Preferences'   => 'Preferencis',
	'Watchlist'     => 'Tignûts di voli',
	'Recentchanges' => 'Ultins cambiaments',
	'Upload'        => 'Cjame sù un file',
	'Imagelist'     => 'Liste des figuris',
	'Listusers'     => 'Liste dai utents',
	'Statistics'    => 'Statistichis',
	'Randompage'    => 'Une pagjine a câs',

	'Lonelypages'   => 'Pagjinis solitaris',
	'Unusedimages'  => 'Figuris no dopradis',
	'Popularpages'  => 'Lis plui popolârs',
	'Wantedpages'   => 'Lis plui desideradis',
	'Shortpages'    => 'Articui curts',
	'Longpages'     => 'Articui luncs',
	'Newpages'      => 'Pagjinis gnovis',
	'Ancientpages'	=> 'Pagjinis vieris',
	'Allpages'      => 'Ducj i articui',

	'Ipblocklist'   => 'Recapits IP blocâts',
	'Maintenance'   => 'Pagjine di manutenzion',
	'Specialpages'  => '', // ces pages doivent rester vides !
	'Contributions' => '',
	'Emailuser'     => '',
	'Whatlinkshere' => '',
	'Recentchangeslinked' => '',
	'Movepage'      => '',
	'Booksources'   => 'Libreriis in linee',
	'Categories'	=> 'Pagjine des categoriis',
	'Export'	=> 'Espuartâ in XML',
	'Version'	=> 'Version',
	'Allmessages'	=> 'Ducj i messaç di sistem'
);

/* private */ $wgSysopSpecialPagesFur = array(
	'Blockip'       => 'Bloche un recapit IP',
	'Asksql'        => 'Acès SQL',
	'Makesysop'		=> 'Dâ i dirits di aministradôr',
                                               
	'Undelete'      => 'Recupere lis pagjinis eliminadis',
	'Import'		=> 'Impuarte une pagjine cul storic'
);

/* private */ $wgDeveloperSpecialPagesFur = array(
	'Lockdb'        => 'Bloche la base di dâts',
	'Unlockdb'      => 'Gjave il bloc ae base di dâts',
);

$wgAllMessagesFur = array(
'about' => "Informazions",
'aboutsite' => "Informazions su la {{SITENAME}}",
'allarticles' => "Ducj i articui",
'allmessages' => "Ducj i messaç di sistem",
'allmessagescurrent' => "Test curint",
'allmessagesdefault' => "Test predeterminât",
'allmessagesname' => "Non",
'allpages' => "Dutis lis pagjinis",
'allpagessubmit' => "Va",
'apr' => "Avr",
'april' => "Avrîl",
'articlenamespace' => "(articui)",
'aug' => "Avo",
'august' => "Avost",
'bad_image_list' => "", #empty
'blocklink' => "bloche",
'cancel' => "Scancele",
'categories' => "Categoriis",
'category' => "categorie",
'category_header' => "Articui inte categorie \"$1\"",
'categoryarticlecount' => "In cheste categorie tu puedis cjatâ $1 articui.",
'categoryarticlecount1' => "In cheste categorie tu puedis cjatâ $1 articul.",
'confirm' => "Conferme",
'confirmdelete' => "Conferme eliminazion",
'confirmprotect' => "Conferme protezion",
'confirmprotecttext' => "Vuelistu pardabon protezi cheste pagjine?",
'confirmunprotect' => "Conferme par gjavâ la protezion",
'confirmunprotecttext' => "Vuelistu pardabon gjavâ la protezion a cheste pagjine?",
'contributions' => "Contribûts dal utent",
'copyright' => "Il contignût al è disponibil sot de $1",
'copyrightwarning' => "<!-- Perché i link non abbiano l'aspetto di link esterni: -->
<div class=\"plainlinks\">

<div style=\"margin-top:2em\">
<div style=\"font-weight: bold; font-size: 120%;\">I cambiaments che tu âs fat a saran visibii daurman.</div>
* Par plasê, dopre la [[Vichipedie:Sandbox|sandbox]] se tu vuelis fâ cualchi prove.
----
<p style=\"background: red; color: white; font-weight: bold; text-align: center; padding: 2px;\">'''NO STÂ DOPRÂ MATERIÂL CUVIERT DAL DIRIT DI AUTÔR (COPYRIGHT - ©) SE NO TU ÂS UNE AUTORIZAZION ESPLICITE!!!'''</p></div>

* Sta atent, par plasê, che ducj i contribûts ae Vichipedie a son considerâts come dâts fûr sot di une licence GNU Free Documentation License (cjale $1 par altris detais).
* Se no tu vuelis che il to test al puedi jessi gambiât e tornât a jessi distribuît da cualsisei persone cence limits, no stâ mandâlu ae Vichipedie, al è miôr se tu ti fasis un to sît web personâl.
* Inviant chest test, tu stâs garantint che chest al è stât scrit di te in origjin, o che al è stât copiât di une sorzint di public domini, o alc   di simil, opûr che tu âs vût une autorizazion esplicite pe publicazion e  tu puedis dimostrâ chest fat.
</div>

</div>",
'createaccount' => "Cree une gnove identitât",
'currentevents' => "Lis gnovis",
'dec' => "Dic",
'december' => "Dicembar",
'delete' => "Elimine",
'deletethispage' => "Elimine cheste pagjine",
'edit' => "Modifiche",
'editing' => "Modifiche di $1",
'editsection' => "modifiche",
'editthispage' => "Modifiche cheste pagjine",
'emailuser' => "Messaç di pueste a chest utent",
'error' => "Erôr",
'errorpagetitle' => "Erôr",
'feb' => "Fev",
'february' => "Fevrâr",
'filedesc' => "Descrizion",
'filename' => "Non dal file",
'friday' => "Vinars",
'go' => "Va",
'help' => "Jutori",
'helppage' => "Jutori:Contignûts",
'hide' => "plate",
'hidetoc' => "plate",
'hist' => "stor",
'history' => "Storic de pagjine",
'history_short' => "Storic",
'ilsubmit' => "Cîr",
'importnotext' => "Vueit o cence test",
'ipbsubmit' => "Bloche chest utent",
'jan' => "Zen",
'january' => "Zenâr",
'jul' => "Lui",
'jun' => "Zug",
'june' => "Zugn",
'lastmodified' => "Modificât par l'ultime volte il $1",
'lastmodifiedby' => "Modificât par l'ultime volte il $1 di",
'link_sample' => "Titul dal leam",
'listadmins' => "Liste dai aministradôrs",
'listform' => "liste",
'listusers' => "Liste dai utents",
'login' => "Jentre",
'loginpagetitle' => "Jentrade dal utent",
'loginsuccesstitle' => "Jentrât cun sucès",
'logout' => "Jes",
'mainpage' => "Pagjine principâl",
'may' => "Mai",
'may_long' => "Mai",
'minoredit' => "Cheste e je une piçule modifiche",
'minoreditletter' => "p",
'monday' => "Lunis",
'move' => "Môf",
'movearticle' => "Môf l'articul",
'movedto' => "Movude in",
'movenologin' => "No tu sês jentrât",
'movepage' => "Môf pagjine",
'movepagebtn' => "Môf pagjine",
'movethispage' => "Môf cheste pagjine",
'mycontris' => "Gno contribûts",
'navigation' => "somari",
'newarticle' => "(Gnûf)",
'newarticletext' => "Tu âs seguît un leam a une pagjine che no esist ancjemò. Par creâ une pagjine, scomence a scrivi tal spazi ca sot (cjale il [[Jutori:Contignûts|jutori]] par altris informazions). Se tu sês ca par erôr, frache semplicementri il boton '''Indaûr''' dal to sgarfadôr.",
'newmessages' => "Tu âs $1.",
'newmessageslink' => "gnûf(s) messaç",
'newpage' => "Gnove pagjine",
'newpageletter' => "G",
'newpages' => "Gnovis pagjinis",
'newusersonly' => "(dome gnûfs utents)",
'newwindow' => "(al vierç un gnûf barcon)",
'nlinks' => "$1 leams",
'noarticletext' => "(Par cumò nol è nuie in cheste pagjine)",
'nolinkshere' => "Nissune pagjine e à leams a chest articul",
'nov' => "Nov",
'november' => "Novembar",
'nstab-category' => "Categorie",
'nstab-help' => "Jutori",
'nstab-image' => "Figure",
'nstab-main' => "Articul",
'nstab-mediawiki' => "Messaç",
'nstab-special' => "Speciâl",
'nstab-template' => "Model",
'nstab-user' => "Pagjine dal utent",
'nstab-wp' => "Informazions",
'oct' => "Otu",
'october' => "Otubar",
'otherlanguages' => "Altris lenghis",
'pagemovedtext' => "Pagjine \"[[$1]]\" movude in \"[[$2]]\".",
'portal' => "Ostarie",
'portal-url' => "Vichipedie:Ostarie",
'powersearch' => "Cîr",
'preferences' => "Preferencis",
'preview' => "Anteprime",
'previewnote' => "Visiti che cheste e je dome une anteprime, e no je stade ancjemò salvade!",
'printableversion' => "Version stampabil",
'printsubtitle' => "(Articul dal sît {{SERVER}})",
'protect' => "Protêç",
'protectcomment' => "Reson pe protezion",
'protectedarticle' => "$1 protezût",
'protectedpage' => "Pagjine protezude",
'protectmoveonly' => "Protêç dome dai spostaments",
'protectpage' => "Protêç pagjine",
'protectreason' => "(inseris une reson)",
'protectsub' => "(Protezint \"$1\")",
'protectthispage' => "Protêç cheste pagjine",
'qbbrowse' => "Sgarfe",
'qbedit' => "Modifiche",
'qbfind' => "Cjate",
'qbspecialpages' => "Pagjinis speciâls",
'randompage' => "Une pagjine a câs",
'rclinks' => "Mostre i ultins $1 cambiaments tes ultimis $2 zornadis<br />$3",
'rclistfrom' => "Mostre i ultins cambiaments dal $1",
'rcnote' => "Ca sot tu cjatis i ultins <strong>$1</strong> cambiaments te ultimis <strong>$2</strong> zornadis.",
'recentchanges' => "Ultins cambiaments",
'recentchangeslinked' => "Cambiaments leâts",
'recentchangestext' => "Cheste pagjine e mostre i plui recents cambiaments inte Vichipedie.",
'redirectedfrom' => "(Inviât ca di $1)",
'retrievedfrom' => "Cjapât fûr di $1",
'returnto' => "Torne a $1.",
'reupload' => "Torne a cjamâ sù",
'revhistory' => "Storic des revisions",
'saturday' => "Sabide",
'savearticle' => "Salve la pagjine",
'savedprefs' => "Lis preferencis a son stadis salvadis",
'saveprefs' => "Salve lis preferencis",
'search' => "Cîr",
'show' => "mostre",
'showhideminor' => "$1 piçulis modifichis | $2 bots | $3 utents jentrâts | $4 modifichis verificadis",
'showpreview' => "Mostre anteprime",
'showtoc' => "mostre",
'sitestats' => "Statistichis dal sît",
'sitesubtitle' => "L'enciclopedie libare",
'specialloguserlabel' => "Utent:",
'specialpage' => "Pagjine speciâl",
'specialpages' => "Pagjinis speciâls",
'statistics' => "Statistichis",
'summary' => "Somari",
'sunday' => "Domenie",
'tagline' => "De {{SITENAME}}, l'enciclopedie libare dute in marilenghe.",
'talkpage' => "Fevelin di cheste pagjine",
'textboxsize' => "Modifiche",
'thumbnail-more' => "Slargje",
'thursday' => "Joibe",
'toc' => "Indis",
'toolbox' => "imprescj",
'tuesday' => "Martars",
'userlogin' => "Regjistriti o jentre",
'userlogout' => "Jes",
'viewsource' => "Cjale risultive",
'watch' => "Ten di voli",
'watchlist' => "Tignûts di voli",
'watchlistcontains' => "Tu stâs tignint di voli $1 pagjinis.",
'watchthis' => "Ten di voli cheste pagjine",
'watchthispage' => "Ten di voli cheste pagjine",
'wednesday' => "Miercus",
'whatlinkshere' => "Leams a chest articul",
);

class LanguageFur extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesFur;
		return $wgNamespaceNamesFur;
	}

	function getNsText( $index ) {
		global $wgNamespaceNamesFur;
		return $wgNamespaceNamesFur[$index];
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesFur, $wgSitename;

		foreach ( $wgNamespaceNamesFur as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		return false;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsFur;
		return $wgQuickbarSettingsFur;
	}

	function getSkinNames() {
		global $wgSkinNamesFur;
		return $wgSkinNamesFur;
	}


	// Inherit userAdjust()

	function date( $ts, $adj = false ) {
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$d = (0 + substr( $ts, 6, 2 )) . " " .
		  $this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) .
		  " " . substr( $ts, 0, 4 );
		return $d;
	}

	function timeanddate( $ts, $adj = false ) {
		return $this->date( $ts, $adj ) . " à " . $this->time( $ts, $adj );
	}

	var $digitTransTable = array(
		',' => '&nbsp;',
		'.' => ','
	);
	
	function formatNum( $number ) {
		return strtr($number, $this->digitTransTable);
	}


	function getValidSpecialPages() {
		global $wgValidSpecialPagesFur;
		return $wgValidSpecialPagesFur;
	}

	function getSysopSpecialPages() {
		global $wgSysopSpecialPagesFur;
		return $wgSysopSpecialPagesFur;
	}

	function getDeveloperSpecialPages() {
		global $wgDeveloperSpecialPagesFur;
		return $wgDeveloperSpecialPagesFur;
	}

	function getMessage( $key ) {
		global $wgAllMessagesFur;
		if( isset( $wgAllMessagesFur[$key] ) ) {
			return $wgAllMessagesFur[$key];
		} else {
			return parent::getMessage( $key );
		}
	}
	
}

?>
