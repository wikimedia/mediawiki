<?php
/** Ancient Greek (Ἀρχαία ἑλληνικὴ)
 *
 * @ingroup Language
 * @file
 *
 * @author Omnipaedista
 * @author LeighvsOptimvsMaximvs
 * @author Lefcant
 * @author AndreasJS
 * @author Nychus
 * @author Neachili
 * @author SPQRobin
 * @author Yannos
 * @author ZaDiak
 */

$dateFormats = array(
	'mdy time' => 'H:i',
	'mdy date' => 'xg j, Y',
	'mdy both' => 'H:i, xg j, Y',

	'dmy time' => 'H:i',
	'dmy date' => 'j xg Y',
	'dmy both' => 'H:i, j xg Y',

	'ymd time' => 'H:i',
	'ymd date' => 'Y xg j',
	'ymd both' => 'H:i, Y xg j',
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Ὑπογραμμίζειν συνδέσμους:',
'tog-highlightbroken'         => 'Μορφοῦν ἀπωλομένους συνδέσμους <a href="" class="new">οὐτωσί</a> (ἄλλως: οὐτωσί <a href="" class="internal">;</a>).',
'tog-justify'                 => 'Στοιχίζειν παραγράφους',
'tog-hideminor'               => 'Κρύπτειν μικρὰς ἐγγραφὰς ἐν προσφάταις ἀλλαγαῖς',
'tog-extendwatchlist'         => 'Ἐφορώμενα ἐκτείνειν ἵνα ἅπασαι φανῶσιν αἱ ἁρμόδιοι ἀλλαγαὶ',
'tog-usenewrc'                => 'Προσκεκοσμημέναι πρόσφατοι ἀλλαγαί (JavaScript)',
'tog-numberheadings'          => 'Ἐξαριθμεῖν τίτλους αὐτομάτως',
'tog-showtoolbar'             => 'Δεικνύναι τὴν τῶν ἐργαλείων ἐπιμελείας μετώπην (JavaScript)',
'tog-editondblclick'          => 'Δέλτους δὶς θλιβείσας ἐπιμελεῖσθαι (JavaScript)',
'tog-editsection'             => 'Τμῆμα διὰ συνδέσμου [μεταγράφειν] μεταγράφειν παρέχειν',
'tog-editsectiononrightclick' => 'Τμῆμα μεταγράφειν παρέχειν <br /> διὰ τίτλον δεξιῷ ὀμφαλῷ θλίβειν (JavaScript)',
'tog-showtoc'                 => 'Δεικνύναι πίνακα περιεχομένων (ἐν δέλτοις περιεχούσαις πλείους τῶν 3 ἐπικεφαλίδων)',
'tog-rememberpassword'        => 'Ἐνθυμεῖσθαι ἐμὴν σύνδεσιν ἐν τῷδε ὑπολογιστῇ',
'tog-editwidth'               => 'Πλαίσιον ἐπιμελείας εἰς πλῆρες πλάτος',
'tog-watchcreations'          => 'Προστιθέναι τὰς δέλτους ἃς ποιῶ τοῖς ἐφορωμένοις μου',
'tog-watchdefault'            => 'Προστιθέναι τὰς δέλτους ἃς μεταγράφω τοῖς ἐφορωμένοις μου',
'tog-watchmoves'              => 'Προστιθέναι τὰς δέλτους ἃς κινῶ τοῖς ἐφορωμένοις μου',
'tog-watchdeletion'           => 'Προστιθέναι τὰς δέλτους ἃς διαγράφω τοῖς ἐφορωμένοις μου',
'tog-minordefault'            => 'Σημαίνειν ὅλας τὰς μεταγραφὰς ὡς ἥττονες προκαθωρισμένως',
'tog-previewontop'            => 'Δεικνύναι τὸ προεπισκοπεῖν πρὸ τοῦ κυτίου μεταγραφῆς',
'tog-previewonfirst'          => 'Τῆς πρώτης μεταγραφῆς, δεικνύναι τὸ προεπισκοπεῖν',
'tog-nocache'                 => 'Ἀπενεργοποιεῖν τὸ κρύπτειν τὰς δέλτους',
'tog-enotifwatchlistpages'    => 'Ἄγγειλον μοι ὅτε μία δέλτος ἐν τῇ ἐφοροδιαλογή μου μεταβάλλεται',
'tog-enotifusertalkpages'     => 'Ἄγγειλον μοι ὅταν ἡ δέλτος ὁμιλίας χρήστου μου μεταβάλληται',
'tog-enotifminoredits'        => 'Ἄγγειλον μοι ἐπἴσης τὰς ἥττονες ἀλλαγὰς δέλτων',
'tog-enotifrevealaddr'        => 'Ἀποκαλύπτειν τὴν ταχυδρομικὴν μου διεύθυνσιν ἐν τῇ εἰδοποιητηρίᾳ ἀλληλογραφίᾳ',
'tog-shownumberswatching'     => 'Δεικνύναι ἀριθμὸν παρακολουθούντων χρηστῶν',
'tog-fancysig'                => 'Ἀκατέργασται ὑπογραφαὶ (ἄνευ αὐτομάτου συνδέσμου)',
'tog-externaleditor'          => 'Χρῆσθαι ἐξώτερον πρόγραμμα επεξεργασίας κειμένων κατὰ προεπιλογήν (διὰ εἰδικοῦς μόνον· ἀπαραίτητοι εἰδικαὶ ῥυθμίσεις τινες ἐν τῇ υπολογιστικῇ μηχανῇ σου)',
'tog-externaldiff'            => 'Χρῆσθαι ἐξώτερον λογισμικὸν αντιπαραβολῆς κατὰ προεπιλογήν (διὰ εἰδικοῦς μόνον· ἀπαραίτητοι εἰδικαὶ ῥυθμίσεις τινες ἐν τῇ υπολογιστικῇ μηχανῇ σου)',
'tog-showjumplinks'           => 'Ἐνεργοποιεῖν τοὺς "ἅλμα πρὸς" συνδέσμους προσβασιμότητος',
'tog-uselivepreview'          => 'Χρῆσθαι ἄμεσον προσκόπησιν (JavaScript) (Πειραστικόν)',
'tog-forceeditsummary'        => 'Προμήνυσόν με εἰ εἰσάγω κενὴ περίληψιν μεταγραφῆς',
'tog-watchlisthideown'        => 'Οὐ δηλοῦν τὰς ἐμὰς μεταβολὴς ἐν τὰ ἐφορώμενά μου',
'tog-watchlisthidebots'       => 'Ἀποκρύπτειν τὰς αὐτόματας μεταγραφὰς ἐκ τῆς ἐφοροδιαλογῆς',
'tog-watchlisthideminor'      => 'Οὐ δηλοῦν τὰς μικρὰς μεταβολὰς ἐν τὰ ἐφορώμενά μου',
'tog-ccmeonemails'            => "Στεῖλον μοι ἀντίγραφα τῶν ἠλ.-ἐπιστολῶν τῶν ἀπεσταλμένων ὑπ'ἐμοῦ πρὸς ἑτέρους χρήστας",
'tog-diffonly'                => 'Οὐκ ἐμφανίζειν τὸ τῆς δέλτου περιεχόμενον ὑπὸ τῶν διαφορῶν',
'tog-showhiddencats'          => 'Κεκρυμμένας κατηγορίας δηλοῦν',

'underline-always'  => 'Ἀεὶ',
'underline-never'   => 'Οὔποτε',
'underline-default' => 'Πλοηγὸς ὡς προκαθωρισμένως',

# Dates
'sunday'        => 'Κυριακή',
'monday'        => 'Δευτέρα',
'tuesday'       => 'Τρίτη',
'wednesday'     => 'Τετάρτη',
'thursday'      => 'Πέμπτη',
'friday'        => 'Παρασκευή',
'saturday'      => 'Σάββατοv',
'sun'           => 'Κυ',
'mon'           => 'Δε',
'tue'           => 'Τρ',
'wed'           => 'Τε',
'thu'           => 'Πε',
'fri'           => 'Πα',
'sat'           => 'Σα',
'january'       => 'Ἰανουάριος',
'february'      => 'Φεβρουάριος',
'march'         => 'Μάρτιος',
'april'         => 'Ἀπρίλιος',
'may_long'      => 'Μάιος',
'june'          => 'Ἰούνιος',
'july'          => 'Ἰούλιος',
'august'        => 'Αὔγουστος',
'september'     => 'Σεπτέμβριος',
'october'       => 'Ὀκτώβριος',
'november'      => 'Νοέμβριος',
'december'      => 'Δεκέμβριος',
'january-gen'   => 'Ἰανουαρίου',
'february-gen'  => 'Φεβρουαρίου',
'march-gen'     => 'Μαρτίου',
'april-gen'     => 'Ἀπριλίου',
'may-gen'       => 'Μαΐου',
'june-gen'      => 'Ἰουνίου',
'july-gen'      => 'Ἰουλίου',
'august-gen'    => 'Αὐγούστου',
'september-gen' => 'Σεπτεμβρίου',
'october-gen'   => 'Ὀκτωβρίου',
'november-gen'  => 'Νοεμβρίου',
'december-gen'  => 'Δεκεμβρίου',
'jan'           => 'Ἰαν',
'feb'           => 'Φεβ',
'mar'           => 'Μάρ',
'apr'           => 'Ἀπρ',
'may'           => 'Μάι',
'jun'           => 'Ἰούν',
'jul'           => 'Ἰούλ',
'aug'           => 'Αὔγ',
'sep'           => 'Σεπτ',
'oct'           => 'Ὀκτ',
'nov'           => 'Νοέμ',
'dec'           => 'Δεκ',

# Categories related messages
'pagecategories'           => '{{PLURAL:$1|Κατηγορία|Κατηγορίαι}}',
'category_header'          => 'Χρήματα ἐν γένει "$1"',
'subcategories'            => 'Ὑποκατηγορίαι',
'category-media-header'    => 'Μέσα ἐν κατηγορίᾳ "$1"',
'category-empty'           => "''Ταύτη ἡ κατηγορία οὐ περιλαμβάνει δέλτους τῷ παρόντι.''",
'hidden-categories'        => '{{PLURAL:$1|Κεκρυμμένη Κατηγορία|Κεκρυμμέναι Κατηγορίαι}}',
'hidden-category-category' => 'Κεκρυμμέναι κατηγορίαι', # Name of the category where hidden categories will be listed
'listingcontinuesabbrev'   => 'συνεχίζεται',

'mainpagetext'      => "<big>'''Ἡ ἐγκατάστασις τῆς MediaWiki ἦν ἐπιτυχής'''</big>",
'mainpagedocfooter' => "Συμβουλευθήσεσθε τὸ [http://meta.wikimedia.org/wiki/Help:Contents User's Guide] ἵνα πληροφορηθῇτε ἐπὶ τοῦ οὐίκι λογισμικοῦ.

== Ἄρξασθε ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Configuration settings list]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]",

'about'          => 'Περὶ',
'article'        => 'Ἡ ἐγγραφή',
'newwindow'      => '(ἀνοίξεται ἐν νέᾳ θυρίδι)',
'cancel'         => 'Ἀκυροῦν',
'qbfind'         => 'Εὑρίσκειν',
'qbbrowse'       => 'Νέμου',
'qbedit'         => 'Μεταγράφειν',
'qbpageoptions'  => 'Ἥδε ἡ δέλτος',
'qbpageinfo'     => 'Συγκείμενον',
'qbmyoptions'    => 'Δέλτοι μου',
'qbspecialpages' => 'Εἰδικαὶ δέλτοι',
'moredotdotdot'  => 'πλέον...',
'mypage'         => 'Δέλτος μου',
'mytalk'         => 'Ἡ διάλεξίς μου',
'anontalk'       => 'Διάλεκτος πρὸ τοῦδε τοῦ IP',
'navigation'     => 'Πλοήγησις',
'and'            => 'καί',

# Metadata in edit box
'metadata_help' => 'Μεταδεδομένα:',

'errorpagetitle'    => 'Σφάλμα',
'returnto'          => 'Ἐπανέρχεσθαι εἰς $1.',
'tagline'           => 'Ἀπὸ {{SITENAME}}',
'help'              => 'Βοήθεια',
'search'            => 'Ζητεῖν',
'searchbutton'      => 'Ζητεῖν',
'go'                => 'Ἰέναι',
'searcharticle'     => 'Ἰέναι',
'history'           => 'Αἱ τῆς δέλτου προτέραι',
'history_short'     => 'Αἱ προτέραι',
'info_short'        => 'Μάθησις',
'printableversion'  => 'Ἐκτυπωτέα μορφή',
'permalink'         => 'Σύνδεσμος βέβαιος',
'print'             => 'Τυποῦν',
'edit'              => 'Μεταγράφειν',
'create'            => 'Δημιουργεῖν',
'editthispage'      => 'Μεταγράφειν τήνδε τὴν δέλτον',
'create-this-page'  => 'Δημιουργεῖν ταύτην δέλτον',
'delete'            => 'Σβεννύναι',
'deletethispage'    => 'Διαγράφειν τήνδε τὴν δέλτον',
'undelete_short'    => 'Ἐπανορθοῦν {{PLURAL:$1|ἕνα μεταγραφέν|$1 μεταγραφέντα}}',
'protect'           => 'Φυλλάττειν',
'protect_change'    => 'ἀλλάττειν προστασίαν',
'protectthispage'   => 'Tήνδε τὴν δέλτον φυλάττειν',
'unprotect'         => 'μὴ φυλάττειν',
'unprotectthispage' => 'Tήνδε τὴν δέλτον  μὴ φυλάττειν',
'newpage'           => 'Δέλτος νέα',
'talkpage'          => 'Διαλέγε τήνδε τὴν δέλτον',
'talkpagelinktext'  => 'Διαλέγεσθαι',
'specialpage'       => 'Εἰδικὴ δέλτος',
'personaltools'     => 'Ἴδια ἐργαλεῖα',
'postcomment'       => 'Ἀποστέλειν ἓν σχόλιον',
'articlepage'       => 'Χρήματος δέλτον ὁρᾶν',
'talk'              => 'Διάλεξις',
'views'             => 'Ποσάκις ἔσκεπται',
'toolbox'           => 'Ἐργαλειοκάδος',
'userpage'          => 'Ὁρᾶν δέλτον χρωμένου',
'projectpage'       => 'Ἰδὲ δέλτον σχεδίου',
'imagepage'         => 'Ὁρᾶν μέσων δέλτον',
'mediawikipage'     => 'Ὁρᾶν δέλτον μηνυμάτων',
'templatepage'      => 'Ὁρᾶν δέλτον ἐπιγραμμάτων',
'viewhelppage'      => 'Ὁρᾶν βοηθείας δέλτον',
'categorypage'      => 'Ὁρᾶν γένους δέλτον',
'viewtalkpage'      => 'Ὁρᾶν διάλεκτον',
'otherlanguages'    => 'Ἀλλογλωσσιστί',
'redirectedfrom'    => '(Ἀποστελτὸν παρὰ $1)',
'redirectpagesub'   => 'Ἐπανάγειν δέλτον',
'lastmodifiedat'    => 'Ἥδε ἡ δέλτος ὕστατον μετεβλήθη $2, $1.', # $1 date, $2 time
'protectedpage'     => 'Πεφυλαγμένη δέλτος',
'jumpto'            => 'Ἅλμα πρὸς:',
'jumptonavigation'  => 'περιήγησις',
'jumptosearch'      => 'ἐρευνᾶν',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Περὶ {{SITENAME}}',
'aboutpage'            => 'Project:Περί',
'bugreports'           => 'Ἀναφορὰ πλανῶν',
'bugreportspage'       => 'Project:Ἀναφορὰ πλανῶν',
'copyright'            => 'Ἡ διάθεσις τοῦδε περιεχομένου ἐστιν ὑπὸ $1.',
'copyrightpagename'    => '{{SITENAME}} πνευματικὰ δικαιώματα',
'copyrightpage'        => '{{ns:project}}:Πνευματικὰ Δικαιώματα',
'currentevents'        => 'Τὰ γιγνόμενα',
'currentevents-url'    => 'Project:Τὰ γιγνόμενα',
'disclaimers'          => 'Ἀποποιήσεις',
'disclaimerpage'       => 'Project:Γενικὴ ἀποποίησις',
'edithelp'             => 'Βοήθεια περὶ τοῦ μεταγράφειν',
'edithelppage'         => 'Help:Βοήθεια περὶ τοῦ μεταγράφειν',
'faq'                  => 'Τὰ πολλάκις αἰτηθέντα',
'faqpage'              => 'Project:Πολλάκις αἰτηθέντα',
'helppage'             => 'Help:Περιεχόμενα',
'mainpage'             => 'Κυρία Δέλτος',
'mainpage-description' => 'Κυρία Δέλτος',
'policy-url'           => 'Project:Πολιτική',
'portal'               => 'Πύλη πολιτείας',
'portal-url'           => 'Project:Πύλη Πολιτείας',
'privacy'              => 'Ἡ περὶ τῶν ἰδίων προαίρεσις',
'privacypage'          => 'Project:Περὶ τῶν ἰδιωτικῶν',

'badaccess'        => 'Σφάλμα ἀδείας',
'badaccess-group0' => 'Οὐκ ἔξεστί σοι ταῦτα διαπράττειν.',

'versionrequired' => 'Ἔκδοσις $1 τῆς MediaWiki ἀπαιτεῖται',

'ok'                      => 'εἶεν',
'retrievedfrom'           => 'ᾑρεθὲν ἀπὸ "$1"',
'youhavenewmessages'      => 'Ἔχεις $1 ($2).',
'newmessageslink'         => 'νέαι ἀγγελίαι',
'newmessagesdifflink'     => 'ἐσχάτη μεταβολή',
'youhavenewmessagesmulti' => 'Νέας εἰσί σοι ἀγγελίας ἐν $1',
'editsection'             => 'μεταγράφειν',
'editold'                 => 'μεταγράφειν',
'viewsourceold'           => 'ὁρᾶν πηγήν',
'editsectionhint'         => 'Μεταγράφειν τὸ μέρος: $1',
'toc'                     => 'Περιεχόμενα',
'showtoc'                 => 'δεικνύναι',
'hidetoc'                 => 'κρύπτειν',
'thisisdeleted'           => 'Ὁρᾶν ἢ ἀποκαθίστασθαι $1;',
'viewdeleted'             => 'Ὁρᾶν $1;',
'feedlinks'               => 'Βοτήρ:',
'feed-invalid'            => 'Ἄκυρος τύπος συνδρομῆς εἰς ῥοὴν δεδομένων.',
'feed-unavailable'        => 'Αἱ ῥοαὶ οὔκ εἰσι διαθέσιμοι ἐν τῷ {{SITENAME}}',
'site-rss-feed'           => 'Ἡ τοῦ $1 RSS-παρασκευή',
'site-atom-feed'          => 'Ἡ τοῦ $1 Ἀτομο-παρασκευή',
'page-rss-feed'           => 'Βοτὴρ RSS "$1"',
'page-atom-feed'          => '"$1" Atom Ῥοή',
'red-link-title'          => '$1 (οὔπω γέγραπται)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Χρῆμα',
'nstab-user'      => 'Δέλτος χρωμένου',
'nstab-media'     => 'Δέλτος μέσων',
'nstab-special'   => 'Εἰδικόν',
'nstab-project'   => 'Δέλτος σχεδίου',
'nstab-image'     => 'Εἰκών',
'nstab-mediawiki' => 'Ἀγγελία',
'nstab-template'  => 'Πρότυπον',
'nstab-help'      => 'Βοήθεια',
'nstab-category'  => 'Γένος',

# Main script and global functions
'nosuchaction'      => 'Οὐδεμία τοιούτη λειτουργία',
'nosuchspecialpage' => 'Οὐδεμία τοιούτη δέλτος',

# General errors
'error'                => 'Σφάλμα',
'databaseerror'        => 'Σφάλμα βάσεως δεδομένων',
'dberrortext'          => 'Ἓν σφάλμα συντάξεως τῆς πεύσεως βάσεως δεδομένων ἀπήντησεν,
ὅπερ ὑποδηλοῖ τὴν ὕπαρξιν μίας πλάνης ἐν τῷ λογισμικῷ.
Ἡ ἔσχατη ἀποπειραθεῖσα πεῦσις βάσεως δεδομένων ἦν:
<blockquote><tt>$1</tt></blockquote>
ἐξ ἐντὸς τῆς τελέσεως "<tt>$2</tt>".
Ἡ MySQL ἐπέστρεψεν σφάλμα "<tt>$3: $4</tt>".',
'noconnect'            => 'Συγγνώμην! Τὸ οὐίκι ἔχει τεχνικὰς δυσχέρειας καὶ οὐ δύναται ἐπιμείγνυναι πρὸς τὸ ἐξυπηρετικὸν δεδομένων.<br />
$1',
'nodb'                 => 'Ἀδύνατον τὸ ἐπιλέγειν βάσιν δεδομένων $1',
'readonly'             => 'Βάσις δεδομένων ἀποκεκλεισμένη',
'missingarticle-rev'   => '(ἀναθεώρησις#: $1)',
'missingarticle-diff'  => '(Διαφ: $1, $2)',
'internalerror'        => 'Ἐσώτερον σφάλμα',
'internalerror_info'   => 'Ἐσώτερον σφάλμα: $1',
'filecopyerror'        => 'Οὐκ ἦν δυνατὴ ἡ ἀντιγραφὴ τοῦ ἀρχείου "$1" εἰς τὸ "$2".',
'filerenameerror'      => 'Οὐκ ἦν δυνατὴ ἡ μετωνόμασις τοῦ ἀρχείου "$1" ὡς "$2".',
'filedeleteerror'      => 'Οὐκ ἦν δυνατὴ ἡ διαγραφὴ τοῦ ἀρχείου "$1".',
'directorycreateerror' => 'Οὐκ ἦν δυνατὴ ἡ δημιουργία τοῦ ἀρχειοκαταλόγου "$1".',
'filenotfound'         => 'Γραφὴ "$1" οὐχ ηὑρέθη',
'fileexistserror'      => 'Οὐκ ἦν δυνατὴ ἡ ἐγγραφὴ εἰς τὸ ἀρχεῖον "$1": τὸ ἀρχεῖον ὑπάρχει',
'unexpected'           => 'Ἀπροσδόκητος τιμή: "$1"="$2".',
'formerror'            => 'Σφάλμα: οὐ δυναμένη ἡ ὑποβολὴ τοῦ τύπου ἐστίν',
'badarticleerror'      => 'Ἡ πρᾶξις οὐκ ἐκτελέσιμος ἦν ἐν τῇδε δέλτῳ.',
'badtitle'             => 'Κακὸν τὸ ἐπώνυμον',
'badtitletext'         => 'Ἡ ἐπιγραφὴ τῆς ᾐτουμένης δέλτου ἐστιν ἄκυρος, κενή, ἢ πρόκειται περὶ ἐσφαλμένως συνδεδεμένης ἐπιγραφῆς μεταξὺ διαφόρων οὐίκι· δύναται περιέχειν χαρακτῆρες μὴ χρηστέους ἐν ἐπιγραφαῖς.',
'wrong_wfQuery_params' => 'Ἐσφαλμέναι παράμετροι εἰς τὸ wfQuery()<br />
Λειτουργία: $1<br />
Πεῦσις: $2',
'viewsource'           => 'Πηγήν διασκοπεῖν',
'viewsourcefor'        => 'Ὑπὲρ τοῦ $1',
'actionthrottled'      => 'Δρᾶσις ἠγχθεῖσα',
'viewsourcetext'       => 'Ἔξεστί σοὶ ὁρᾶν τε ἀντιγράφειν τὴν τῆς δέλτου πηγὴν:',
'sqlhidden'            => '(πεῦσις SQL κεκρυμμένη)',

# Virus scanner
'virus-badscanner'     => 'Κακὸς σχηματισμός: ἄγνωτος σαρωτὴς ἰῶν: <i>$1</i>',
'virus-scanfailed'     => 'Σάρωσις πταιστή (κῶδιξ $1)',
'virus-unknownscanner' => 'ἄγνωτος ἀντιιός:',

# Login and logout pages
'logouttitle'                => 'Ἀποσυνδεῖσθαι χρήστου',
'welcomecreation'            => '== Ὡς εὖ παρέστης, $1! ==

Λογισμός σὸς πεποίηται. Ἔχε μνήμην μεταβάλλειν τὰς τοῦ [[Special:Preferences|{{SITENAME}} αἱρέσεις σου]].',
'loginpagetitle'             => 'Συνδεῖσθαι χρήστου',
'yourname'                   => 'Ὄνομα χρωμένου:',
'yourpassword'               => 'Σῆμα:',
'yourpasswordagain'          => 'Ἀνατυπῶσαι σύνθημα:',
'remembermypassword'         => 'Μίμνῃσκε ἐνθάδε τὸ συνδεῖσθαι',
'yourdomainname'             => 'Ὁ τομεύς σου:',
'login'                      => 'Συνδεῖσθαι',
'nav-login-createaccount'    => 'Συνδεῖσθαι/λογισμὸν ποιεῖν',
'loginprompt'                => 'Δεῖ ἐνεργὰ τὰ HTTP-πύσματα εἶναι πρὸ τοῦ συνδεῖσθαι τῷ {{SITENAME}}.',
'userlogin'                  => 'Συνδεῖσθαι/λογισμὸν ποιεῖν',
'logout'                     => 'Ἐξέρχεσθαι',
'userlogout'                 => 'Ἐξέρχεσθαι',
'notloggedin'                => 'Οὐ συνδεδεμένος',
'nologin'                    => 'Ἆρα λογισμὸν οὐκ ἔχεις; $1.',
'nologinlink'                => 'Λογισμὸν ποιεῖν',
'createaccount'              => 'Λογισμὸν ποιεῖν',
'gotaccount'                 => 'Ἆρα λογισμὸν ἤδη τινὰ ἔχεις; $1.',
'gotaccountlink'             => 'Συνδεῖσθαι',
'createaccountmail'          => 'ἠλεκτρονικῇ ἐπιστολῇ',
'youremail'                  => 'Ἠλεκτρονικαὶ ἐπιστολαί:',
'username'                   => 'Ὄνομα χρωμένου:',
'uid'                        => 'Ταυτότης χρήστου:',
'prefs-memberingroups'       => 'Μέλος {{PLURAL:$1|ομάδoς|ομάδων}}:',
'yourrealname'               => 'Τὸ ἀληθὲς ὄνομα:',
'yourlanguage'               => 'Γλῶττά σου:',
'yournick'                   => 'Προσωνυμία:',
'email'                      => 'ἠλεκτρονική ἐπιστολή',
'prefs-help-realname'        => 'Ἀληθὲς ὄνομα προαιρετικὸν ἐστίν.
Εἰ εἰσάγεις τὸ ὄνομά σου, ἀναγνωριστέον ἔσται τὸ ἔργον σου.',
'loginerror'                 => 'Ἡμάρτηκας περὶ τοῦ συνδεδεκαῖναι',
'prefs-help-email-required'  => 'Διεύθυνσις ἠλ-ταχυδρομείου προαπαιτεῖται.',
'loginsuccesstitle'          => 'Καλῶς συνδέδεσαι',
'loginsuccess'               => "'''συνδέδεσαι ἤδη τῷ {{SITENAME}} ὡς \"\$1\".'''",
'nosuchuser'                 => 'Οὐκ ἐστὶ χρώμενος "$1" ὀνομαστί.
Σκόπει τὴν τῶν γραμμάτων ἀκρίβειαν ἢ λογισμὸν νέον ποίει.',
'nosuchusershort'            => 'Οὔκ ἐστι χρήστης ἔχων τὸ ὄνομα "<nowiki>$1</nowiki>".
Ἔλεγξον τὴν ὀρθογραφίαν σου.',
'nouserspecified'            => 'Ὄνομα χρωμένου καθοριστέον ὑποχρεωτικώς.',
'wrongpassword'              => 'Εἰσηγμένον σύνθημα ἐσφαλμένον. Πείρασον πάλιν.',
'wrongpasswordempty'         => 'Σύνθημα οὐκ ἔγραψας.
Αὖθις πείρασον.',
'passwordtooshort'           => 'Τὸ σύνθημά σου ἄκυρον ἢ λίαν βραχὺ ἐστίν.
Δεῖ αὐτὸ ἔχειν τοὐλάχιστον {{PLURAL:$1|1 χαρακτὴρ|$1 χαρακτῆρες}} καὶ εἶναι διάφορον τοῦ ὀνόματος χρήστου σου.',
'mailmypassword'             => 'Τὸ σύνθημα ἠλεκτρονικῇ ἐπιστολῇ πέμπειν',
'passwordremindertitle'      => 'Νέον ἐφήμερον σύνθημα διὰ {{SITENAME}}',
'passwordremindertext'       => "Τίς (πιθανὼς σύ, μετὰ τῆς IP-διευθύνσεως \$1) ἐζήτησεν τὴν πρὸς σέ ἀποστολὴν νέου συνθήματος διὰ τὸν ἱστότοπον {{SITENAME}} (\$4). Τὸ σύνθημα διὰ τὸν χρήστην \"\$2\" ἐν τῷ παρόντι \"\$3\" ἐστίν. Δεῖ ''νῦν'' συνδεῖσθαι τε καὶ ἀλλάττειν τὸ σύνθημά σου.

Εἰ τὶς ''ἕτερος'' τὴν αἴτησιν ταύτην ὑπέβαλεν ἢ εἰ οὐκ ἀμνηστεῖς τοῦ συνθήματός σου καὶ ''οὐκ'' επιθυμεῖς τὴν ἀλλαγὴν οὗ, δύνασαι ἀγνοῆσαι ταῦτο τὸ μήνυμα καὶ διατηρῆσαι τὸ παλαιὸν σύνθημά σου.",
'noemail'                    => 'Οὐδεμία ἠλεκτρονικὴ διεύθυνσις ἐγγεγραμένη διὰ τὸν χρώμενον "$1".',
'passwordsent'               => 'Νέον τι σύνθημα πρὸς τὴν τοῦ χρωμένου "$1" ὀνομαστὶ ἠλεκτρονικὴ διεύθυνσιν προσπέπεπται.
Τοῦτο δεχόμενος αὖθις συνδεῖσθαι.',
'eauthentsent'               => 'Μήνυμα τι ἐπαληθεύσεως ἐστάλη τῇ δεδηλωμένῃ ἠλεκτρονικῇ διευθύνσει σου. Πρὸ τῆς περαιτέρω ἀποστολῆς μηνυμάτων τῷ συγκεκριμένῳ λογισμῷ, δεῖ σὺ ἀκολουθῆσαι τὰς ὀδηγίας ἐν τῷ ἀπεσταλμένῳ μηνύματι πρὸς ἐπαλήθευσιν τῆς κυριότητός σου τοῦ συγκεκριμένου λογισμοῦ.',
'mailerror'                  => 'Σφάλμα κατὰ τὴν ἀποστολὴν τῆσδε ἐπιστολῆς: $1',
'acct_creation_throttle_hit' => 'Λυπούμεθα· πεποίηκας ἤδη $1 λογισμοῦς.
Οὐ δύνασαι ἔχειν πλείω τοῦ ἑνός.',
'emailconfirmlink'           => 'Ἐπιβεβαίωσον τὴν διεύθυνσιν ἠλ-ταχυδρομείου σου',
'accountcreated'             => 'Λογισμὸς δημιουργηθείς',
'loginlanguagelabel'         => 'Γλῶττα: $1',

# Password reset dialog
'resetpass'         => 'Ἀναδιορισμὸς συνθήματος λογισμοῦ',
'resetpass_header'  => 'Ἀναδιορισμὸς συνθήματος',
'resetpass_submit'  => 'Ἀναδιορισμὸς συνθήματος καὶ σύνδεσις',
'resetpass_missing' => 'Οὐδὲν δεδομένον τύπου.',

# Edit page toolbar
'bold_sample'     => 'Γράμματα παχέα',
'bold_tip'        => 'Γράμματα παχέα',
'italic_sample'   => 'Γράμματα πλάγια',
'italic_tip'      => 'Γράμματα πλάγια',
'link_sample'     => 'Συνδέσμου ὄνομα',
'link_tip'        => 'Σύνδεσμος οἰκεῖος',
'extlink_sample'  => 'http://www.example.com ὄνομα συνδέσμου',
'extlink_tip'     => 'Ἐξώτερος σύνδεσμος (μέμνησο τὸ πρόθεμα http:// )',
'headline_sample' => 'Κείμενον ἐπικεφαλίδος',
'headline_tip'    => 'Κλίμακος 2 ἐπικεφαλίς',
'math_sample'     => 'Εἰσάγειν τύπον ὧδε',
'math_tip'        => 'Μαθηματικὸς τύπος (LaTeX)',
'nowiki_sample'   => 'Εἰσάγειν ἀμόρφωτον κείμενον ὧδε',
'nowiki_tip'      => 'Ἀγνοεῖν οὐικιμορφοποιίαν',
'image_tip'       => 'Ἐμβεβαπτισμένον ἀρχεῖον',
'media_tip'       => 'Τὸ προσάγον πρὸς τὸ φορτίον',
'sig_tip'         => 'Ὑπογραφή σου μετὰ χρονοσφραγίδος',
'hr_tip'          => 'Ὁριζόντιος γραμμή (χρηστέα φειδωλώς)',

# Edit pages
'summary'                => 'Τὸ κεφάλαιον',
'subject'                => 'Χρῆμα/ἐπικεφαλίς',
'minoredit'              => 'Μικρὰ ἥδε ἡ μεταβολή',
'watchthis'              => 'Ἐφορᾶν τήνδε τὴν δέλτον',
'savearticle'            => 'Γράφειν τὴν δέλτον',
'preview'                => 'Τὸ προεπισκοπεῖν',
'showpreview'            => 'Προεπισκοπεῖν',
'showlivepreview'        => 'Ἄμεσος προθεώρησις',
'showdiff'               => 'Δεικνύναι τὰς μεταβολάς',
'anoneditwarning'        => "'''Προσοχή:''' Οὐ συνδεδεμένος εἶ.
Ἡ IP-διεύθυνσίς σου καταγεγραμμένη ἔσται ἐν τῇδε δέλτου ἱστορίᾳ.",
'summary-preview'        => 'Πρόβλεψις περιλήψεως',
'subject-preview'        => 'Ἀντικειμένου/ἐπικεφαλίδος προθεώρησις',
'blockedtitle'           => 'Ἀποκεκλεισμένος ὁ χρώμενος',
'blockedtext'            => "<big>'''Τὸ ὄνομα χρήστου σου ἢ ἡ IP-διεύθυνσις σου πεφραγμένα ἐστίν.'''</big>

Ἡ φραγὴ γέγονε ὑπὸ τὸν/τὴν $1.
Ἡ δεδομένη αἰτιολογία ἐστίν: ''$2''.

* Ἔναρξις φραγῆς: $8
* Λῆξις φραγῆς: $6
* Ἡ φραγὴ προορίζεται διὰ τὸν χρήστην: $7

Ἀποτάθητι εἰς τὸν/τὴν $1 ἢ ὅντινα ἕτερον [[{{MediaWiki:Grouppage-sysop}}|γέροντα]] διὰ τὸ διαλέγεσθαι περὶ τῆς φραγῆς. 
Οὐ δύνασαι χρῆσθαι τὴν δυνατότηταν «αποστολῆς ἠλεκτρονικῆς ἐπιστολῆς τῷδε χρήστη» εἰ οὐ ὁρίσεις μίαν ἔγκυρον ἠλεκτρονικὴν διεύθυνσιν ἐν ταῖς [[Special:Preferences|προκρίσεσί]] σου. 
Ἡ τρέχουσα IP-διεύθυνσις σου $3 ἐστίν, καὶ ἡ ἀναγνώρισις τῆς φραγῆς #$5 ἐστίν. 
Παρακαλοῦμεν σε περιλαμβάνειν οἱανδήποτε ἐξ αὐτῶν ἢ καὶ ἀμφοτέρας ἐν ταῖς ἐρωτήσεσί σου.",
'autoblockedtext'        => "Ἡ IP-διεύθυνσις σου ἐφράγη αυτομάτως ἐπεὶ κεχρησμένη ἦν ὑπὸ ἑτέρου τινὸς χρήστου, ὅπερ ἀποκεκλεισμένος ἐστὶν ἐκ τοῦ/τῆς $1.
Ἡ δεδομένη αἰτία ἐστὶν ὡς ἑξῆς:

:''$2''

*Ἔναρξις φραγῆς: $8
*Λῆξις φραγῆς: $6
*Προκαθωρισμένος πεφραχθείς: $7

Ἀποτάθητι εἰς τὸν/τὴν $1 ἢ εἰς ἑτέρους τινὲς 
[[{{MediaWiki:Grouppage-sysop}}|γέροντας]] διὰ τὸ διαλέγεσθαι περὶ τῆς φραγῆς.

Σημείωσον: οὐ δύνασαι χρῆσθαι τὸ γνώρισμα «αποστολῆς ἠλεκτρονικῆς ἐπιστολῆς τῷδε χρήστη» εἰ μὴ ἔχης ἔγκυρον τινὰ διεύθυνσιν ἠλεκτρονικοῦ ταχυδρομείου κατακεχωρημένην ἐν ταῖς [[Special:Preferences|προτιμήσεσι χρήστου]] σου.

Ἡ τρέχουσα IP-διεύθυνσις σου $3 ἐστίν, καὶ ἡ ἀναγνώρισις τῆς φραγῆς #$5 ἐστίν. 
Παρακαλοῦμεν σε περιλαμβάνειν οἱανδήποτε ἐξ αὐτῶν ἢ καὶ ἀμφοτέρας ἐν ταῖς ἐρωτήσεσί σου.",
'blockednoreason'        => 'οὐδεμία αἰτία ἐδόθη',
'nosuchsectiontitle'     => 'Οὐδὲν τοιοῦτον τμῆμα',
'loginreqtitle'          => 'Δεῖ συνδεῖσθαι',
'loginreqlink'           => 'συνδεῖσθαι',
'accmailtitle'           => 'Σύνθημα ἀπεστάλη.',
'newarticle'             => '(νέα)',
'newarticletext'         => "Ἀκολούθησας σύνδεμόν τινα πρὸς δέλτον εἰσέτι μὴ ὑπαρκτήν.
Δύνασαι δημιουργῆσαι τὴν δέλτον, τυπῶν ἐν τῷ κυτίῳ κατωτέρω (ἰδὲ [[{{MediaWiki:Helppage}}|δέλτος βοηθείας]] διά πλείονας πληροφορίας).
Εἰ ὧδε εἶ κατὰ λάθος, πίεσον τὸ κομβίον τοῦ πλοηγοῦ σου ὀνόματι '''ὀπίσω (back)'''.",
'noarticletext'          => 'Οὐδὲν ἐν τῇδε τῇ δέλτῳ γεγραμμένον, ἔξεστι σοὶ [[Special:Search/{{PAGENAME}}|δέλτον τινὰ ὧδε ὀνομαστὶ ζῆτειν]] ἢ [{{fullurl:{{FULLPAGENAME}}|action=edit}} τήνδε τὴν δέλτον μεταγράφειν].',
'userinvalidcssjstitle'  => "'''Προσοχή:''' Οὐκ ὑφίσταται ''skin'' \"\$1\". Μέμνησο: αἱ προσηρμοσμέναι δέλτοι .css καὶ .js χρῶνται ἐπώνυμον τι ἔχον πεζὰ γράμματα, π.χ. {{ns:user}}:Foo/monobook.css ἐν ἀντίθεσει πρὸς τὸν {{ns:user}}:Foo/Monobook.css.",
'updated'                => '(Ἐνημερωθέν)',
'note'                   => '<strong>Ἐπισήμανσις:</strong>',
'previewnote'            => '<strong>Ἥδε ἐστὶ προσκόπησις, οὐχὶ γράφειν τὰς μεταβολάς!</strong>',
'editing'                => 'Μεταγράφων $1',
'editingsection'         => 'Μεταγράφων $1 (μέρος)',
'editingcomment'         => 'Μεταγράφειν $1 (σχόλιον)',
'editconflict'           => 'Ἀντιμαχία μεταγραφῶν: $1',
'yourtext'               => 'Τὰ ὑπό σου γραφόμενα',
'storedversion'          => 'Ταμιευμένη ἔκδοσις',
'yourdiff'               => 'Τὰ διαφέροντα',
'copyrightwarning'       => 'Ὅλαι αἱ συμβολαὶ εἰς τὸ {{SITENAME}} θεωροῦνται ὡς σύμφωναι πρὸς τὴν $2 (βλ. $1 διὰ τὰς ἀκριβεῖας).
Εἰ οὐκ ἐπιθυμεῖτε τὰ ὑμέτερα κείμενα μεταγράψιμα καὶ διαδόσιμα εἰσὶν ὑπὸ ἄλλων χρωμένων, κατὰ τὴν βούλησίν των, παρακαλοῦμεν ὑμᾶς ἵνα μὴ αὐτὰ ἀναρτῆτε ἐν τούτῳ χώρῳ. Ὅ,τι συνεισφέρετε ἐνθάδε (κείμενα, διαγράμματα, στοιχεῖα ἢ εἰκόνας) δεῖ εἶναι ὑμέτερον ἔργον, ἢ ἀνῆκον τῷ δημοσίῳ τομεῖ, ἢ προερχόμενον ἐξ ἐλευθέρων ἢ ανοικτῶν πηγῶν μετὰ ῥητῆς ἀδείας ἀναδημοσιεύσεως. <br />

Βεβαιοῦτε ἡμᾶς περὶ τῆς πρωτοτυπίας ὅτου ἔργου γραφομένου ὑφ’ ὑμᾶς ἐνθάδε. Βεβαιοῦτε ἡμᾶς ἐπἴσης περὶ τῆς μὴ ἐκχωρήσεως αὐτου εἰς αλλοτρίους πρὸς ὑμᾶς τοῦ δικαιώματος δημοσιεύσεως καὶ ὀνήσεως του, ἥντινα ἔκτασιν αὐτὸν ἔχει.
<br />
<strong>ΠΑΡΑΚΑΛΟΥΜΕΝ ΙΝΑ ΜΗ ΑΝΑΡΤΗΤΕ ΚΕΙΜΕΝΑ ΑΛΛΟΤΡΙΩΝ ΕΙ ΜΗ ΕΧΕΤΕ ΤΗΝ ΑΔΕΙΑ ΤΟΥ ΙΔΙΟΚΤΗΤΟΥ ΤΩΝ ΠΝΕΥΜΑΤΙΚΩΝ ΔΙΚΑΙΩΜΑΤΩΝ!</strong>',
'longpagewarning'        => '<strong>ΠΡΟΣΟΧΗ: Ἡδε δέλτος μῆκος $1 χιλιοδυφίων (δυαδικῶν ψηφίων) ἔχει.
Ἐνδέχεται πλοηγοί τινες προβληματικὼς μεταγράφειν δέλτους προσεγγίζοντας τὰ ἢ μακροτέρας τῶν 32ΧΔ.
Θεωρήσατε τὸ διασπάσειν τὴν δέλτον εἰς μικρότερα τεμάχια.</strong>',
'templatesused'          => 'Πρότυπα κεχρησμένα ἐν τοιαύτῃ δελτῳ:',
'templatesusedpreview'   => 'Πρότυπα κεχρησμένα ἐν ταύτῃ προσκόπησει:',
'template-protected'     => '(φυλλάττεται)',
'template-semiprotected' => '(ἡμιπεφυλαγμένη)',
'nocreatetitle'          => 'Δημιουργία δέλτων περιωρισμένη',
'nocreatetext'           => "{{SITENAME}} οὐκ σ'ἐᾷ νέας δέλτους ποιεῖν.
Ἐᾷ σε δέλτον ἢδη οὖσαν μεταβάλλειν ἢ [[Special:UserLogin|συνδεῖσθαι ἢ λογισμὸν ποιεῖν]].",
'permissionserrors'      => 'Σφάλματα ἀδειῶν',
'recreate-deleted-warn'  => "'''Προσοχή: Ἀναδημιουργεῖς δέλτον πάλαι ποτὲ διαγραφεῖσα.'''

Δεῖ θεωρήσειν εἰ ἁρμοστόν ἐστι τὸ συνεχίζειν μεταγράφειν τήνδε δέλτον.
Ὁ κατάλογος διαγραφῆς τῆσδε δέλτου διατίθεται ἐνθάδε πρὸς ἐπικουρίαν σου:",

# Account creation failure
'cantcreateaccounttitle' => 'Μὴ δυνατὴ ἡ ποίησις λογισμοῦ',

# History pages
'viewpagelogs'        => 'Ὁρᾶν καταλόγους διὰ ταύτην τὴν δέλτον',
'revnotfound'         => 'Ἀναθεώρησις μὴ εὑρεθεῖσα',
'currentrev'          => 'Τὸ νῦν',
'revisionasof'        => 'Τὰ ἐπὶ $1',
'revision-info'       => 'Τὸ ἐπὶ $1 ὑπὸ $2',
'previousrevision'    => '←Τὸ πρότερον',
'nextrevision'        => 'Τὸ νεώτερον→',
'currentrevisionlink' => 'Τὰ νῦν',
'cur'                 => 'ἡ νῦν',
'next'                => 'ἡ ἐχομένη',
'last'                => 'ἡ ὑστάτη',
'page_first'          => 'πρώτη',
'page_last'           => 'ἐσχάτη',
'histlegend'          => 'Σύγκρισις διαφορῶν: Ἐπιλέξατε τὰς συγκριτέας ἐκδοχὰς καὶ πατήσατε enter ἢ τὸ κομβίον  "Σύγκρισις...". <br />
Ὑπόμνημα: (τρέχον) = διαφοραὶ ὡς πρὸς τὴν τρέχουσαν ἐκδοχήν,
(ὕστατον) = διαφοραὶ ὡς πρὸς τὴν προηγουμένη ἐκδοχήν, μ = ἀλλαγαὶ μικρῆς κλίμακος.',
'history-search'      => 'Ζήτησις ἐν ταῖς προτέραις',
'deletedrev'          => '[διεγράφη]',
'histfirst'           => 'πρώτη',
'histlast'            => 'ἐσχάτη',
'historyempty'        => '(κενόν)',

# Revision feed
'history-feed-title'          => 'Ἱστορία ἀναθεωρήσεων',
'history-feed-description'    => 'Ἱστορία ἀναθεωρήσεων τῆσδε δέλτου ἐν τῷ οὐίκι',
'history-feed-item-nocomment' => '$1 ἐπὶ $2', # user at time

# Revision deletion
'rev-deleted-comment'     => '(σχόλιον ἀφελόμενον)',
'rev-deleted-user'        => '(ὄνομα χρωμένου ἀφελόμενον)',
'rev-deleted-event'       => '(δρᾶσις καταλόγου ἀφελομένη)',
'rev-delundel'            => 'δεικνύναι/κρύπτειν',
'revisiondelete'          => 'Διαγράφειν/ἐκδιαγράφειν ἀναθεωρήσεις',
'revdelete-nooldid-title' => 'Ἄκυρος ἀναθεώρησις-στόχος',
'revdelete-legend'        => 'Θέτειν περιορισμοῦς ὀρατότητος',
'revdelete-hide-text'     => 'Κρύπτειν κείμενον ἀναθεωρήσεως',
'revdelete-hide-name'     => 'Κρύπτειν δρᾶσιν τε καὶ στόχον',
'revdelete-hide-comment'  => 'Κρύπτειν σχόλιον μεταγραφῆς',
'revdelete-hide-user'     => 'Κρύπτειν μεταγραφέως ὄνομα/IP-διεύθυνσιν',
'revdelete-hide-image'    => 'Κρύπτειν περιεχόμενον ἀρχείου',
'revdelete-log'           => 'Σχόλιον καταλόγου:',
'revdel-restore'          => 'Ἀλλάττειν ὀρατότητα',
'pagehist'                => 'Ἱστορία δέλτου',
'deletedhist'             => 'Ἱστορία διεγραμμένη',
'revdelete-content'       => 'περιεχόμενον',
'revdelete-summary'       => 'σύνοψις μεταγραφῶν',
'revdelete-uname'         => 'ὄνομα χρήστου',
'revdelete-hid'           => 'κρύπ $1',
'revdelete-unhid'         => 'oὐ κρύπ $1',

# Suppression log
'suppressionlog' => 'Κατάλογος διαγραφῶν',

# History merging
'mergehistory'        => 'Συγχωνεύειν ἱστορίας δέλτων',
'mergehistory-box'    => 'Συγχωνεύειν τὰς ἀναθεωρήσεις τῶν δύο δέλτων:',
'mergehistory-from'   => 'Δέλτος πηγῶν:',
'mergehistory-into'   => 'Δέλτος προορισμοῦ:',
'mergehistory-list'   => 'Συγχωνεύσιμος ἱστορία μεταγραφῶν',
'mergehistory-go'     => 'Δεικνύειν συγχωνεύσιμας μεταγραφάς',
'mergehistory-submit' => 'Συγχωνεύειν ἀναθεωρήσεις',

# Merge log
'mergelog'    => 'Τῶν συγχωνεύσεων καταλόγος',
'revertmerge' => 'Ἀποσυγχωνεύειν',

# Diffs
'history-title'           => 'Αἱ προτέραι ἐκδόσεις τῆς δέλτου "$1"',
'difference'              => '(Τὰ μεταβεβλημένα)',
'lineno'                  => 'Γραμμή $1·',
'compareselectedversions' => 'Συγκρίνειν τὰς ἐπιλεγμένας δέλτους',
'editundo'                => 'ἀγέννητον τιθέναι',
'diff-multi'              => '({{PLURAL:$1|Μία ἐνδιάμεσος ἀναθεώρησις|$1 ἐνδιάμεσοι ἀναθεωρήσεις}} οὐ φαίνονται.)',

# Search results
'searchresults'             => 'Ἀποτελέσματα ἀναζητήσεως',
'noexactmatch'              => "'''Οὐκ ἐστὶ δέλτος \"\$1\" ὀνομαστί.'''
Ἔξεστι σοὶ [[:\$1|ταύτην ποιεῖν]].",
'prevn'                     => 'πρότερον $1',
'nextn'                     => 'τὸ $1 τὸ ἐχόμενον',
'viewprevnext'              => 'Ἐπισκοπεῖν ($1) ($2) ($3)',
'search-result-size'        => '$1 ({{PLURAL:$2|1 λέξις|$2 λέξεις}})',
'search-result-score'       => 'Σχετικότης: $1%',
'search-redirect'           => '(ἀναδιευθύνειν $1)',
'search-section'            => '(τμῆμα $1)',
'search-suggest'            => 'Συνίης: $1',
'search-interwiki-caption'  => 'Sister projects
Ἀδελφὰ σχέδια',
'search-interwiki-default'  => '$1 ἀποτελέσματα:',
'search-interwiki-more'     => '(πλείω)',
'search-mwsuggest-enabled'  => "μεθ'ὑποδείξεων",
'search-mwsuggest-disabled' => 'οὐκ αἵτινες ὑποδείξεις',
'search-relatedarticle'     => 'Σχετικά',
'searchrelated'             => 'σχετικά',
'searchall'                 => 'ἅπαντα',
'nonefound'                 => "'''Ἐπισημείωσις''': Μόνον οἵτινες ὀνοματικοὶ χώροι ἀναζητοῦνται κατὰ προεπιλογήν.
Πείρασον τὸ προθεματίζειν τὴν πεῦσιν σου μετὰ τοῦ ''ὅλα:'' διὰ τὸ ἀναζητεῖν εἰς παντὶ περιεχομένῳ (δέλτων διαλόγου, προτύπων, κ.λ., περιλαμβανομένων), ἢ χρῆσον τὸν ἐπιθυμητὸν ὀνοματικὸν χώρον ὡς πρόθεμα.",
'powersearch'               => 'Ζητεῖν ἀναλυτικῶς',
'powersearch-legend'        => 'Ἀνωτέρα ἀναζήτησις',
'powersearch-ns'            => 'Ζήτησις ἐν τοῖς ὀνοματεἰοις:',
'powersearch-redir'         => 'Ἀναδιευθύνειν καταλόγου',
'powersearch-field'         => 'Ἀναζήτησις διά',
'search-external'           => 'Ἐξωτέρα ἀναζήτησις',

# Preferences page
'preferences'              => 'Αἱρέσεις',
'mypreferences'            => 'Αἱ αἱρέσεις μου',
'prefs-edits'              => 'Τοσοῦται αἱ μεταβολαί:',
'prefsnologin'             => 'Οὐ συνδεδεμένος',
'qbsettings'               => 'Ταχεῖα πρόσβασις',
'qbsettings-none'          => 'Οὐδέν',
'qbsettings-fixedleft'     => 'Σταθερὰ ἀριστερώς',
'qbsettings-fixedright'    => 'Σταθερὰ δεξιώς',
'qbsettings-floatingleft'  => 'Πλανώμενα αριστερώς',
'qbsettings-floatingright' => 'Πλανώμενα δεξιώς',
'changepassword'           => 'Ἀλλάττειν σύνθημα',
'skin'                     => 'Ἐμφάνισις',
'skin-preview'             => 'Προεπισκοπεῖν',
'math'                     => 'Τὰ μαθηματικά',
'dateformat'               => 'Μορφοποιία χρονολογίας',
'datedefault'              => 'Οὐδεμία προτίμησις',
'datetime'                 => 'Χρονολογία καὶ ὥρα',
'math_failure'             => 'Λεξιανάλυσις ἀποτετυχηκυῖα',
'math_unknown_error'       => 'ἄγνωστον σφάλμα',
'math_unknown_function'    => 'ἄγνωστος λειτουργία',
'math_lexing_error'        => 'σφάλμα λεξικῆς ἀναλύσεως',
'math_syntax_error'        => 'σφάλμα συντάξεως',
'prefs-personal'           => 'Στοιχεῖα χρωμένου',
'prefs-rc'                 => 'Αἱ νέαι μεταβολαί',
'prefs-watchlist'          => 'Τὰ ἐφορώμενα',
'prefs-misc'               => 'Διάφορα',
'saveprefs'                => 'Γράφειν',
'oldpassword'              => 'Πρότερον σύνθημα:',
'newpassword'              => 'Νέον σύνθημα:',
'retypenew'                => 'Ἀνατύπωσις νέου συνθήματος:',
'textboxsize'              => 'Τὸ μεταγράφειν',
'rows'                     => 'Σειραί:',
'columns'                  => 'Στῆλαι:',
'searchresultshead'        => 'Ζητεῖν',
'resultsperpage'           => 'Ἀποτελέσματα ἄνα δέλτον:',
'contextlines'             => 'Σειραὶ ἄνα ἀποτέλεσμα:',
'timezonelegend'           => 'Χρονικὴ ζώνη',
'localtime'                => 'Τοπικὴ ὥρα',
'timezoneoffset'           => 'Ἐκτόπισμα¹',
'servertime'               => 'Ὥρα ἐξυπηρετικῆς ὑπολογιστικῆς μηχανῆς',
'prefs-namespaces'         => 'Ὄνοματικὸς χῶρος',
'defaultns'                => 'Ἀναζήτησις ἐν τοῖσδε ὀνοματικοῖς χώροις κατὰ προεπιλογήν:',
'default'                  => 'προκαθωρισμένον',
'files'                    => 'Ἀρχεῖα',

# User rights
'userrights-lookup-user'   => 'Χειρίζεσθαι ὁμάδας χρωμένου',
'userrights-user-editname' => 'Εἰσάγειν ἓν ὄνομα χρήστου:',
'editusergroup'            => 'Μεταγράφειν ὁμάδας χρωμένου',
'userrights-editusergroup' => 'Μεταγράφειν ὁμάδας χρωμένου',
'saveusergroups'           => 'Σῴζειν ὁμάδας χρωμένου',
'userrights-groupsmember'  => 'Μέλος τοῦ:',

# Groups
'group'            => 'Ὁμάς:',
'group-user'       => 'Χρώμενοι',
'group-bot'        => 'Αὐτόματα',
'group-sysop'      => 'Γέρoντες',
'group-bureaucrat' => 'Ἔφοροι',
'group-suppress'   => 'Παροράματα',
'group-all'        => '(ὅλοι)',

'group-user-member'       => 'Χρήστης',
'group-bot-member'        => 'Μεταβάλλων μηχανικός',
'group-sysop-member'      => 'Γέρων',
'group-bureaucrat-member' => 'Ἔφορος',
'group-suppress-member'   => 'Παρόραμα',

'grouppage-sysop'      => '{{ns:project}}:Γέροντες',
'grouppage-bureaucrat' => '{{ns:project}}:Ἔφοροι',

# Rights
'right-read'      => 'Ἀναγιγνώσκειν δέλτους',
'right-edit'      => 'Μεταγράφειν δέλτους',
'right-move'      => 'Μετακινεῖν δέλτους',
'right-upload'    => 'Ἐπιφορτίζειν ἀρχεῖα',
'right-delete'    => 'Δέλτους σβεννύναι',
'right-undelete'  => 'Δέλτον ἐπαναφέρειν',
'right-trackback' => 'Ὀνασύνδεσμον ὑποβάλλειν',

# User rights log
'rightslog'  => 'Κατάλογος δικαιωμάτων χρωμένων',
'rightsnone' => '(Οὐδέν)',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|μεταβολή|μεταβολαί}}',
'recentchanges'                  => 'Αἱ νέαι μεταβολαί',
'recentchanges-feed-description' => 'Παρακολουθεῖν τὰς πλείω πρόσφατας ἀλλαγὰς τοῦ οὐίκι ἐν ταύτῃ περιλήψει.',
'rcnote'                         => "Κατωτέρω εἰσὶ {{PLURAL:$1|'''1''' ἀλλαγὴ|αἱ τελευταίαι '''$1''' ἀλλαγαὶ}} ἐν {{PLURAL:$2|τῇ τελευταίᾳ ἡμέρᾳ|ταῖς τελευταίαις '''$2''' ἡμέραις}}, ὡς καὶ $5, $4.",
'rcnotefrom'                     => "Ἰδοῦ αἱ ἀλλαγαὶ ἐκ τοῦ '''$2''' (ἕως τὸ '''$1''').",
'rclistfrom'                     => 'Δεικνύειν νέας ἀλλαγάς. Ἐκκίνησις ἐκ τοῦ $1',
'rcshowhideminor'                => '$1 μικραὶ μεταβολαὶ',
'rcshowhidebots'                 => '$1 αὐτόματα',
'rcshowhideliu'                  => '$1 χρωμένους συνδεδεμένους',
'rcshowhideanons'                => '$1 χρώμενοι ἀνώνυμοι',
'rcshowhidepatr'                 => '$1 Τὰς φυλασσόμενας μεταβολάς',
'rcshowhidemine'                 => '$1 μεταβολὰς μου',
'rclinks'                        => 'Ἐμφάνισις τῶν τελευταίων $1 ἀλλαγῷν τῷ χρονικῷ διαστήματι τῶν τελευταίων $2 ἡμερῷν <br />$3',
'diff'                           => 'διαφ.',
'hist'                           => 'Προτ.',
'hide'                           => 'Κρύπτειν',
'show'                           => 'Δεικνύναι',
'minoreditletter'                => 'μ',
'newpageletter'                  => 'Ν',
'boteditletter'                  => 'α',
'rc_categories_any'              => 'Οἵα δήποτε',
'newsectionsummary'              => '/* $1 */ νέον τμῆμα',

# Recent changes linked
'recentchangeslinked'          => 'Οἰκεῖαι μεταβολαί',
'recentchangeslinked-title'    => 'Μεταβολαὶ οἰκεῖαι "$1"',
'recentchangeslinked-noresult' => 'Οὐδεμία ἀλλαγὴ τῶν συνδεδεμένων δέλτων ἐν τῇ δεδομένῃ χρονικῇ περιόδῳ.',
'recentchangeslinked-summary'  => "Ὅδε ἐστὶ κατάλογος τῶν νέων μεταβόλων κατὰ δέλτους συνδεδεμένας σὺν δέλτῳ τινί (ἢ κατὰ τὰς κατηγορίας τινός).
Δέλτοι ἐν τῷ [[Special:Watchlist|καταλόγῳ ἐφορωμένων]] σου '''ἔντονοι''' εἰσίν.",
'recentchangeslinked-page'     => 'Ὄνομα δέλτου:',

# Upload
'upload'             => 'Ἐπιφορτίζειν ἀρχεῖον',
'uploadbtn'          => 'Φορτίον ἐντιθέναι',
'reupload'           => 'Ἀναεπιφορτίζειν',
'uploadnologin'      => 'Οὐ συνδεδεμένος',
'uploaderror'        => 'Σφάλμα ἐπιφορτίσεως',
'upload-permitted'   => 'Ἐπιτρεπόμενοι τύποι ἀρχείων: $1.',
'upload-preferred'   => 'Προκρινόμενοι τύποι ἀρχείων: $1.',
'upload-prohibited'  => 'Ἀπηγορευμένοι τύποι ἀρχείων: $1.',
'uploadlog'          => 'ἐπιφορτίζειν κατάλογον',
'uploadlogpage'      => 'Ἐπιφόρτωσις καταλόγου',
'filename'           => 'Ὄνομα ἀρχείου',
'filedesc'           => 'Τὸ κεφάλαιον',
'fileuploadsummary'  => 'Τὸ κεφάλαιον:',
'filestatus'         => 'Κατάστασις πνευματικῶν δικαιωμάτων:',
'filesource'         => 'Πηγή:',
'uploadedfiles'      => 'Ἀρχεῖα ἐπιπεφορτισμένα',
'successfulupload'   => 'Ἐπιφόρτισις ἐπιτυχής',
'uploadwarning'      => 'Προμήνυσις ἐπιφορτώσεως',
'savefile'           => 'Σῴζειν ἀρχεῖον',
'uploadedimage'      => 'ἐπιφορτισμένον "[[$1]]"',
'upload-maxfilesize' => 'Μέγιστον μέγεθος ἀρχείου: $1',
'watchthisupload'    => 'Ἐφορᾶν τήνδε τὴν δέλτον',

'upload-proto-error' => 'Ἐσφαλμένον πρωθυπόμνημα',
'upload-file-error'  => 'Ἐσώτερον σφάλμα',

'license'   => 'Ἀδειηδότησις:',
'nolicense' => 'Οὐδὲν ἐπιλεγμένον',

# Special:ImageList
'imagelist-summary'     => 'Ἡδε εἰδικὴ δέλτος δεικνύει ὅλα τὰ ἐπιφορτισμένα αρχεῖα.
Κατὰ προεπιλογὴν τὰ υστάτως ἐπιφορισμένα ἀρχεῖα δεινύονται ἐν τῇ κορυφῇ τοῦ καταλόγου.
Πιέσατε ἐπικεφαλίδα στήλης τινὰ ἵνα ἡ καταλογὴ ἀλλάξηται.',
'imagelist_search_for'  => 'Ἀναζήτησις τοῦ τῶν μέσων ὀνόματος:',
'imgfile'               => 'ἀρχεῖον',
'imagelist'             => 'Κατάλογος πάντων τῶν φορτίων',
'imagelist_date'        => 'Χρονολογία',
'imagelist_name'        => 'Ὄνομα',
'imagelist_user'        => 'Χρώμενος',
'imagelist_size'        => 'Ὁπόσος',
'imagelist_description' => 'Διέξοδος',

# Image description page
'filehist'                       => 'Τοῦ ἀρχείου συγγραφή',
'filehist-help'                  => 'Πατήσατε ἐπὶ μίας χρονολογίας/ὥρας ἵνα ἴδητε τὸ ἀρχεῖον ὡς ἐμφανισθὲν ἐν ᾗπερ ὥρᾳ ᾗ.',
'filehist-deleteall'             => 'διαγράφειν ὅλα',
'filehist-deleteone'             => 'διαγράφειν',
'filehist-revert'                => 'ἀναστρέφειν',
'filehist-current'               => 'Τὸ νῦν',
'filehist-datetime'              => 'Ἡμέρα/Ὥρα',
'filehist-user'                  => 'Χρώμενος',
'filehist-dimensions'            => 'Τὸ μέγαθος',
'filehist-filesize'              => 'Μέγεθος',
'filehist-comment'               => 'Σχόλιον',
'imagelinks'                     => 'Σύνδεσμοι',
'linkstoimage'                   => 'Αἱ ἀκόλουθοι/Ἡ ἀκόλουθος {{PLURAL:$1|δέλτοι σύνδεσμοι|$1 δέλτος σύνδεσμος}} πρὸς ταύτην εἰκόναν {{PLURAL:$1|εἰσίν|$1 ἐστίν}}.',
'nolinkstoimage'                 => 'Οὐδένα ἐστὶ προσάγον τόδε τὸ φορτίον.',
'sharedupload'                   => 'Τὸ ἀρχεῖον ταῦτο ἐπιφορτίσθη πρὸς κοινὴν χρῆσιν καὶ δύνασθε χρῆσθαι αὐτὸ εἰς ἕτερα σχέδια καὶ δέλτους ἐξἴσου.',
'shareduploadduplicate-linktext' => 'ἕτερον ἀρχεῖον',
'shareduploadconflict-linktext'  => 'ἕτερον ἀρχεῖον',
'noimage'                        => 'Οὐδένα ἐστὶ οὕτως ὀνομαστί, ἔξεστι σοὶ $1.',
'noimage-linktext'               => 'Ἐντιθέναι',
'uploadnewversion-linktext'      => 'Ἐπιφορτίζειν μίαν νέαν ἐκδοχὴν ταύτου τοῦ ἀρχείου',

# File reversion
'filerevert'         => 'Ἐπαναφέρειν  $1',
'filerevert-legend'  => 'Ἐπαναφέρειν ἀρχεῖον',
'filerevert-comment' => 'Σχόλιον:',
'filerevert-submit'  => 'Ἀναστρέφειν',

# File deletion
'filedelete'                  => 'Διαγράφειν $1',
'filedelete-legend'           => 'Διαγράφειν ἀρχεῖον',
'filedelete-submit'           => 'Διαγράφειν',
'filedelete-reason-otherlist' => 'Ἑτέρα αἰτία',

# MIME search
'mimesearch' => 'MIME Ζητεῖν',
'mimetype'   => 'τύπος MIME:',
'download'   => 'καταφορτίζειν',

# List redirects
'listredirects' => 'Κατάλογος ἀναδιευθύνσεων',

# Unused templates
'unusedtemplates'    => 'Ἄχρηστα πρότυπα',
'unusedtemplateswlh' => 'οἱ σύνδεσμοι οἱ ἄλλοι',

# Random page
'randompage' => 'Δέλτος τυχοῦσα',

# Random redirect
'randomredirect' => 'Τυχαία ἀναδιεύθυνσις',

# Statistics
'statistics' => 'Τὰ περὶ τῶν δεδομένων',
'userstats'  => 'Χρωμένου στατιστικά',

'disambiguations'     => 'Αἱ τινὰ ἱστάναι εἰς τὸ ἀναμφισβήτητον δέλτοι',
'disambiguationspage' => 'Template:ἐκσαφήνισις',

'doubleredirects' => 'Ἀναδιευθύνσεις διπλασιοζόμεναι',

'brokenredirects'        => 'Ἀναδιευθύνσεις οὐκέτι προὔργου οὖσαι',
'brokenredirects-edit'   => '(μεταγράφειν)',
'brokenredirects-delete' => '(διαγράφειν)',

'withoutinterwiki'        => 'Δέλτοι ἄνευ γλωττικῶν συνδέσμων',
'withoutinterwiki-legend' => 'Πρόθεμα',
'withoutinterwiki-submit' => 'Ἐμφανίζειν',

'fewestrevisions' => 'Δέλτοι ἔχουσαι τὰς ὀλιγωτέρας ἀναθεωρήσεις',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|δυφιολέξις|δυφιολέξεις}}',
'ncategories'             => '$1 {{PLURAL:$1|κατηγορία|κατηγορίαι}}',
'nlinks'                  => '$1 {{PLURAL:$1|σύνδεσμος|σύνδεσμοι}}',
'nmembers'                => '$1 {{PLURAL:$1|μέλος|μέλη}}',
'nrevisions'              => '$1 {{PLURAL:$1|ἀναθεώρησις|ἀναθεωρήσεις}}',
'nviews'                  => '$1 {{PLURAL:$1|βλέψις|βλέψεις}}',
'lonelypages'             => 'Δέλτοι ὀρφαναί',
'uncategorizedpages'      => 'Αἱ δέλτοι αἱ οὐκ ἐνοῦσαι γένεσι',
'uncategorizedcategories' => 'Αἱ κατηγορίαι αἱ μὴ προσήκουσαι κατηγορέσι',
'uncategorizedimages'     => 'Ἀκατηγοριοποίητα ἀρχεῖα',
'uncategorizedtemplates'  => 'Ἀκατηγοριοποίητα πρότυπα',
'unusedcategories'        => 'Ἄχρησται κατηγορίαι',
'unusedimages'            => 'Ἄχρηστα ἀρχεῖα',
'popularpages'            => 'Δημοφιλεῖς δέλτοι',
'wantedcategories'        => 'Αἰτούμεναι κατηγορίαι',
'wantedpages'             => 'Αἱ δέλτοι οἷας ἱμείρομεν',
'missingfiles'            => 'Ἀπωλολότα ἀρχεῖα',
'mostlinked'              => 'Αἱ πλέον προσσυνδεδεμέναι δέλτοι',
'mostlinkedcategories'    => 'Αἱ πλέον προσσυνδεδεμέναι κατηγορίαι',
'mostlinkedtemplates'     => 'Τὰ πλέον προσσυνδεδεμένα πρότυπα',
'mostcategories'          => 'Δέλτοι ἔχουσαι τὰς πλείονας κατηγορίας',
'mostimages'              => 'Τὰ πλέον προσσυνδεδεμένα ἀρχεῖα',
'mostrevisions'           => 'Αἱ δέλτοι αἱ πλειστάκις μεταβεβλήμεναι',
'prefixindex'             => 'Προθέματος δείκτης',
'shortpages'              => 'Δέλτοι μικραί',
'longpages'               => 'Δέλτοι μακραί',
'deadendpages'            => 'Ἀδιέξοδαι δέλτοι',
'protectedpages'          => 'Αἱ δέλτοι αἱ φυλαττομέναι',
'protectedtitles'         => 'Πεφυλαγμέναι ἐπιγραφαί',
'listusers'               => 'Κατάλογος πάντων τῶν χρωμένων',
'newpages'                => 'Δέλτοι νέαι',
'newpages-username'       => 'Ὄνομα χρωμένου:',
'ancientpages'            => 'Αἱ παλαιόταται δέλτοι',
'move'                    => 'κινεῖν',
'movethispage'            => 'Κινεῖν τήνδε τὴν δέλτον',
'suppress'                => 'Παρόραμα',

# Book sources
'booksources'    => 'Αἱ ἐν βίβλοις πηγαί',
'booksources-go' => 'Ἰέναι',

# Special:Log
'specialloguserlabel'  => 'Χρήστης·',
'speciallogtitlelabel' => 'Ὄνομα:',
'log'                  => 'Κατάλογοι',
'all-logs-page'        => 'Κατάλογοι ἅπαντες',
'log-search-legend'    => 'Ἀναζητεῖν καταλόγους',
'log-search-submit'    => 'Ἰέναι',

# Special:AllPages
'allpages'       => 'Πᾶσαι αἱ δέλτοι',
'alphaindexline' => '$1 ἕως $2',
'nextpage'       => 'Ἡ δέλτος ἡ ἐχομένη ($1)',
'prevpage'       => 'Ἡ δέλτος ἡ προτέρη ($1)',
'allpagesfrom'   => 'Ἐπιδεικνύειν τὰς δέλτους ἐκ:',
'allarticles'    => 'Πάντες γραφαί',
'allpagesprev'   => 'Προηγούμενον',
'allpagesnext'   => 'Ἐπόμενον',
'allpagessubmit' => 'Ἰέναι',
'allpagesprefix' => 'Ἐπιδεικνύειν δέλτους ἔχουσας πρόθεμα:',

# Special:Categories
'categories' => 'Κατηγορίαι',

# Special:ListUsers
'listusers-submit' => 'Ἐμφανίζειν',

# Special:ListGroupRights
'listgrouprights'        => 'Δικαιώματα ὁμάδος χρωμένου',
'listgrouprights-group'  => 'Ὁμάς',
'listgrouprights-rights' => 'Δικαιώματα',

# E-mail user
'emailuser'       => 'Ἠλεκτρονικὴν ἐπιστολὴν τῷδε τῷ χρήστῃ πέμπειν',
'emailpage'       => 'Χρώμενος ἠλ.-ταχυδρομείου',
'defemailsubject' => '{{SITENAME}} ἠλ.-ταχυδρομεῖον',
'emailfrom'       => 'Ἐκ',
'emailto'         => 'Πρός',
'emailsubject'    => 'Θέμα',
'emailmessage'    => 'Ἀγγελία',
'emailsend'       => 'Πέμπειν',
'emailsent'       => 'Ἠλ.-ἐπιστολὴ ἀπεστάλη',

# Watchlist
'watchlist'            => 'Τὰ ἐφορώμενά μου',
'mywatchlist'          => 'Τὰ ἐφορώμενά μου',
'watchlistfor'         => "(πρό '''$1''')",
'watchnologin'         => 'Οὐ συνδεδεμένος',
'addedwatch'           => 'Δέλτος προστεθειμένη εἰς τὸν ἐποπτευομένων κατάλογον ἐστίν',
'addedwatchtext'       => "Ἡ δέλτος \"[[:\$1]]\" προσετέθη εἰς τῷ [[Special:Watchlist|καταλόγῳ ἐφορωμένων]].
Μελλοντικαὶ ἀλλαγαὶ τῆσδε δέλτου καὶ τῆς σχετικῆς δέλτου διαλόγου καταλεχθήσονται ὧδε, καὶ ἡ δέλτος ἐμφανίσεται '''ἔντονος''' ἐν τῷ [[Special:RecentChanges|καταλόγῳ προσφάτων ἀλλαγων]] οὕτωσι εὐχερέστερός ἐστιν ἡ διάκρισις αὐτῆς.",
'removedwatch'         => 'Ἀνεώραται ἥδε ἡ δέλτος',
'removedwatchtext'     => 'Ἡ δέλτος "[[:$1]]" ἀφῃρέθη ἐκ τοῦ [[Special:Watchlist|καταλόγου ἐφορωμένων σου]].',
'watch'                => 'Ἐφορᾶν',
'watchthispage'        => 'Ἐφορᾶν τήνδε τὴν δέλτον',
'unwatch'              => 'Ἀνεφορᾶν',
'unwatchthispage'      => 'Παῦσαι τὸ ἐφορᾶν',
'notanarticle'         => 'οὐ χρῆμα δέλτος',
'watchlist-details'    => '{{PLURAL:$1|$1 δέλτος|$1 δέλτοι}} ἐφορωμέναι, ἄνευ τῶν δέλτων συζητήσεως περιλαμβανομένων.',
'wlshowlast'           => 'Ἐμφάνισις τῶν τελευταίων $1 ὡρῶν $2 ἡμερῶν $3',
'watchlist-hide-bots'  => 'Κρύπτειν τὰς ὑπ᾿ αὐτομάτων μεταβολάς',
'watchlist-show-own'   => 'Δεικνύναι τοὺς ἐράνους μου',
'watchlist-hide-own'   => 'Κρύπτειν τὰς ὑπ᾿ ἐμοῦ μεταβολάς',
'watchlist-show-minor' => 'Δεικνύναι τὰς μικρὰς μεταβολάς',
'watchlist-hide-minor' => 'Κρύπτειν τὰς μικρὰς μεταβολάς',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Ἐφορῶν...',
'unwatching' => 'Ἀνεφορῶν...',

'enotif_impersonal_salutation' => 'Χρώμενος τοῦ {{SITENAME}}',
'changed'                      => 'ἀλλαχθέν',
'created'                      => 'δημιουργηθέν',
'enotif_anon_editor'           => 'ἀνώνυμος χρώμενος $1',

# Delete/protect/revert
'deletepage'                  => 'Διαγράφειν τὴν δέλτον',
'confirm'                     => 'Κυροῦν',
'excontent'                   => "ἡ ὕλη ἦτο: '$1'",
'excontentauthor'             => "ἡ ὕλη ἦτο: '$1' (καὶ ὁ μόνος ἐρανίζων ἦτο ὁ '[[Special:Contributions/$2|$2]]')",
'exblank'                     => 'δέλτος κενὴ ἦν',
'delete-confirm'              => 'Διαγράφειν "$1"',
'delete-legend'               => 'Διαγράφειν',
'historywarning'              => 'Προσοχή: Ἡ δέλτος ἥντινα βούλεσαι διαγράψειν ἔχει ἱστορίαν:',
'confirmdeletetext'           => 'Πρόκεισαι διαγράψειν ὁριστικὼς ἐκ τῆς βάσεως δεδομένων δέλτον τίνα (ἢ εἰκόνα τινα) μετὰ τῆς ἐῆς ἱστορίας. Παρακαλοῦμεν ἵνα ἐπιβεβαιώσητε τὴν θέλησιν ὑμῶν περὶ τοῦ αὐτὸ πράττειν καὶ περὶ τῆς ἀντιλήψεως τῶν συνεπειῶν, και περὶ τοῦ πράττειν ὑμῶν συμφώνως τῶν [[{{MediaWiki:Policy-url}}|κανόνων]].',
'actioncomplete'              => 'Τέλειον τὸ ποιούμενον',
'deletedtext'                 => '"<nowiki>$1</nowiki>" διεγράφηκεν.
Ἰδὲ $2 διὰ τὸ μητρῷον τῶν προσφάτων διαγραφῶν.',
'deletedarticle'              => 'Ἐσβέσθη τὴν δέλτον "[[$1]]"',
'dellogpage'                  => 'Τὰ ἐσβεσένα',
'deletionlog'                 => 'κατάλογος διαγραφῶν',
'deletecomment'               => 'Αἰτία τοῦ σβεννύναι:',
'deleteotherreason'           => 'Αἰτία ἄλλα:',
'deletereasonotherlist'       => 'Αἰτία ἄλλα',
'rollback'                    => 'Ἀναστρέφειν μεταγραφάς',
'rollback_short'              => 'Ἀναστροφή',
'rollbacklink'                => 'ἀναστροφή',
'rollbackfailed'              => 'Ἀναστροφὴ μὴ ἐπιτυχής',
'protectlogpage'              => 'Κατάλογος προφυλάξεων',
'protectcomment'              => 'Σχόλιον:',
'protectexpiry'               => 'Ἐξήξει:',
'protect_expiry_invalid'      => 'Ἄκυρος χρόνος λήξεως.',
'protect_expiry_old'          => 'Χρόνος λήξεως ἐν τῷ παρελθόντι ἐστίν.',
'protect-unchain'             => 'Δικαιώματα μετακινήσεως ἐκκλειδοῦν',
'protect-text'                => 'Ὁρᾶν τε καὶ ἀλλάττειν δύνασθε τὴν κλίμακα προστασίας ἐνθάδε διὰ τὴν δέλτον <strong><nowiki>$1</nowiki></strong>.',
'protect-locked-access'       => 'Ὁ λογισμός σου οὐκ ἔχει ἄδειαν ἀλλαγῆς τῆς κλίμακος προστασίας δέλτων.
Ἐνθάδε εἰσὶν αἱ τρέχουσαι ῥυθμίσεις διὰ τὴν δέλτον <strong>$1</strong>:',
'protect-cascadeon'           => 'Ἡδε δέλτος τῷ παρόντι προστατευμένη ἐστὶν ἐπεὶ περιλαμβάνεται {{PLURAL:$1|τῇ ἀκολούθῳ δέλτῳ, ἥπερ ἔχει|ταῖς ἀκολούθοις δέλτοις, αἵπερ ἔχουσι}} τὴν διαδοχικὴν προστασίαν ἐνεργόν. Δύνασαι ἀλλάττειν τὴν κλίμακα προστασίας  τῆσδε δέλτου, ἄνευ ἐπηρεασμοῦ τῆς διαδοχικὴς προστασίας.',
'protect-default'             => '(κριτήριον)',
'protect-fallback'            => 'Δεῖ ἐχεῖν τὴν "$1" οὐσίαν',
'protect-level-autoconfirmed' => 'Ἀποκλῄειν τοὺς ἀγράφους',
'protect-level-sysop'         => 'Μόνοι οἱ γέροντες',
'protect-summary-cascade'     => 'Διαδεχόμενον',
'protect-expiring'            => 'Ἐξήξει $1 (UTC)',
'protect-cascade'             => 'Προφυλάττειν δέλτους περικεκλεισμένας ἐν τοιαύτῃ δελτῳ (διαδοχικὴ προφύλαξις)',
'protect-cantedit'            => 'Οὐ δύνασθε ἀλλάττειν τὴν κλίμακα προστασίας ταύτης τῆς δέλτου, διότι οὐκ ἔχετε τὴν ἀδείαν τοῦ μεταγράφειν αὐτήν.',
'restriction-type'            => 'Ἐξουσία:',
'restriction-level'           => 'Κλῖμαξ περιορισμοῦ:',
'minimum-size'                => 'Ἐλάχιστον μέγεθος',
'maximum-size'                => 'Μέγιστον μέγεθος:',
'pagesize'                    => '(δυφία)',

# Restrictions (nouns)
'restriction-edit'   => 'Μεταγράφειν',
'restriction-move'   => 'Kινεῖν',
'restriction-create' => 'Δημιουργεῖν',
'restriction-upload' => 'Ἐπιφορτίζειν',

# Restriction levels
'restriction-level-sysop'         => 'πλήρως διαφυλαττομένη',
'restriction-level-autoconfirmed' => 'ἡμιδιαφυλαττομένη',
'restriction-level-all'           => 'οἵα δήποτε κλῖμαξ περιορισμοῦ',

# Undelete
'viewdeletedpage'        => 'Δεικνύναι διαγραφείσας δέλτους',
'undeletebtn'            => 'Ἀνορθοῦν',
'undeletelink'           => 'ἐπανίσταναι',
'undeletereset'          => 'Ἐπαναθέτειν',
'undeletecomment'        => 'Σχόλιον:',
'undeletedarticle'       => 'ἐπανιστάν "[[$1]]"',
'undelete-search-submit' => 'Ζητεῖν',

# Namespace form on various pages
'namespace'      => 'Ὀνοματεῖον:',
'invert'         => 'Ἀντιστρέφειν ἐπιλογήν',
'blanknamespace' => '(Κυρία γραφή)',

# Contributions
'contributions'       => 'Ἔρανοι χρωμένου',
'contributions-title' => 'Ἔρανοι χρωμένου διὰ τὸ $1',
'mycontris'           => 'Οἱ ἔρανοί μου',
'contribsub2'         => 'Πρὸς $1 ($2)',
'uctop'               => '(ἄκρον)',
'month'               => 'Μήν:',
'year'                => 'Ἔτος:',

'sp-contributions-newbies-sub' => 'Ἔρανοι νέων χρωμένων',
'sp-contributions-blocklog'    => 'Τὰ ἀποκλῄειν',
'sp-contributions-username'    => 'IP-διεύθυνσις ἢ ὄνομα χρωμένου:',
'sp-contributions-submit'      => 'Ζητεῖν',

# What links here
'whatlinkshere'            => 'Τὰ ἐνθάδε ἄγοντα',
'whatlinkshere-title'      => 'Δέλτοι ἐζεύγμεναι ὑπὸ "$1"',
'whatlinkshere-page'       => 'Δέλτος:',
'linkshere'                => "Τάδε ἄγουσι πρὸς '''[[:$1]]''':",
'nolinkshere'              => "Οὐδένα ἄγουσι πρὸς '''[[:$1]]'''.",
'isredirect'               => 'ἀναδιευθύνειν δέλτον',
'istemplate'               => 'περίκλεισις',
'isimage'                  => 'σύνδεσμος εἰκόνος',
'whatlinkshere-prev'       => '{{PLURAL:$1|προτἐρα|Αἱ $1 προτέραι}}',
'whatlinkshere-next'       => '{{PLURAL:$1|ἑξῆς|οἱ $1 ἑξῆς}}',
'whatlinkshere-links'      => '← σύνδεσμοι',
'whatlinkshere-hideredirs' => '$1 ἀναδιευθύνσεις',
'whatlinkshere-hidelinks'  => '$1 συνδέσμους',
'whatlinkshere-hideimages' => '$1 συνδέσμους εἰκόνων',
'whatlinkshere-filters'    => 'Ἠθητήρια',

# Block/unblock
'blockip'            => 'Ἀποκλῄειν τόνδε τὸν χρώμενον',
'ipaddress'          => 'IP-Διεύθυνσις:',
'ipadressorusername' => 'IP-Διεύθυνσις ἢ ὄνομα χρωμένου :',
'ipbexpiry'          => 'Λῆξις:',
'ipbreason'          => 'Αἰτία:',
'ipbreasonotherlist' => 'Ἑτέρα αἰτία',
'ipboptions'         => 'βʹ ὥραι:2 hours,αʹ ἡμέρα day:1 day,γʹ ἡμέραι:3 days,ζʹ ἡμέραι:1 week,ιδʹ ἡμέραι:2 weeks,αʹ μήν:1 month,γʹ μήνες:3 months,ϝʹ μήνες:6 months,αʹ ἔτος:1 year,ἄπειρον:infinite', # display1:time1,display2:time2,...
'ipbotheroption'     => 'ἄλλη',
'ipbotherreason'     => 'Πρόσθετος/ἄλλη αἰτία:',
'ipblocklist'        => 'Πεφραγμέναι IP-διευθύνσεις καὶ ὀνόματα τῶν χρωμένων',
'ipblocklist-submit' => 'Ζητεῖν',
'infiniteblock'      => 'ἄπειρον',
'anononlyblock'      => 'ἀνωνύμους μόνον',
'emailblock'         => 'ἠλεκτρονικὸν ταχυδρομεῖον πεφραγμένον',
'blocklink'          => 'ἀποκλῄειν',
'unblocklink'        => 'χαλᾶν φραγήν',
'contribslink'       => 'Ἔρανοι',
'blocklogpage'       => 'Τὰ ἀποκλῄειν',
'blocklogentry'      => 'Κεκλῃμένος [[$1]] μέχρι οὗ $2 $3',

# Move page
'move-page-legend'     => 'Κινεῖν τὴν δέλτον',
'movepagetext'         => "Χρῆτε τὸν ἀκόλουθον τύπον διὰ τὴν μετωνόμασιν τῆς δέλτου καὶ διὰ τὴν μεταφορὰν ὅλου τοῦ ἑοῦ ἱστορικοῦ ὑπὸ τὸ νέον ὄνομα. 
Ἡ προηγουμένη ἐπιγραφὴ τῆς δέλτου ἔσται δέλτος τις ἀνακατευθύνσεως. Οἱ τυχόντες σύνδεσμοι πρὸς τὴν προηγουμένην δέλτον ἀναλλοίωτοι ἔσονται. 
Βεβαιοῦσθε περὶ τῆς μὴ ὑπάρξεως [[Special:DoubleRedirects|διπλῶν]] ἢ [[Special:BrokenRedirects|διεφθαρμένων συνδέσμων]]. 
Ἀναλαμβάνετε τὴν εὐθύνην τοῦ ἐπιβεβαιῶσαι τὴν ὀρθὴν καὶ ὑποτιθεμένην κατεύθυνσιν τῶν συνδέσμων.

Ἐπισημείωσις: ἡ δέλτος '''οὐ''' κινήσεται εἰ ὑπάρχει ἤδη ἑτέρα δέλτος ὑπὸ τὴν νέαν ἐπιγραφήν, εἰ μὴ ἡ δελτος ταύτη κενή ἐστι '''καὶ οὐκ''' ἔχει ἱστορίαν. Δῆλα δή, ἐν περιπτώσει λάθους ὑμῶν, δύνασθε μετωνομάσειν δέλτον τινά, δίδοντες αὐτῇ τὴν ἑὴν πρότερην ὀνομασίαν, ἀλλὰ οὐ δύνασθε ὑποκαταστήσειν προϋπάρχουσαν δέλτον τινά.

'''ΠΡΟΣΟΧΗ!'''
Ἡ μετωνόμασις δέλτου τινὸς αἰφνιδία καὶ δραστικὴ ἀλλαγή ἐστιν ὁπηνίκα πρόκειται περὶ δημοφιλοῦς δέλτου· παρακαλοῦμεν ἵνα ἐξετάζητε τὰς πιθανὰς ἐπιπτώσεις ταύτης τῆς ἐνεργείας, πρὸ τῆς ἀποφάσεως ὑμῶν.",
'movepagetalktext'     => "Ἡ σχετικὴ δέλτος συζητήσεως μετακινήσεται αὐτομάτως μετὰ τῆς δέλτου ἐγγραφῆς '''ἐκτός εἰ οὐ(κ):'''
*Μετακινήσεις τὴν δέλτον εἰς διάφορον ὀνοματικὸν χῶρον (namespace), ἢ
*Ὑπάρχει ὑπὸ τὸ νέον ὄνομα μὴ κενὴ δέλτος τις συζητήσεως, ἢ
*Ἀφῄρηκας τὴν κατασήμανσιν (check) ἐκ του κυτίου κατωτέρω.

Ἐν ταύταις ταῖς περιπτώσεσιν, δεῖ μετακινῆσαι ἢ συγχωνεῦσαι τὴν δέλτον μέσῳ ἀντιγραφῆς-καὶ-ἐπικολήσεως.",
'movearticle'          => 'Κινεῖν τὴν δέλτον:',
'newtitle'             => 'Πρὸς τὸ νέον ὄνομα:',
'move-watch'           => 'Ἑφορᾶν τήνδε τὴν δέλτον',
'movepagebtn'          => 'Κινεῖν τὴν δέλτον',
'pagemovedsub'         => 'Κεκίνηται ἡ δέλτος',
'movepage-moved'       => '<big>\'\'\'"$1" κεκίνηται πρὸς "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'        => 'Ἢ ἐστὶ δέλτος τις οὔτως ὀνομαστὶ ἢ ἀπόρρητον τὸ ὄνομα.
Ἄλλως τὴν δέλτον ὀνόμασον.',
'talkexists'           => "'''Κεκίνηται μὲν ἡ δέλτος αὐτὴ, ἡ δὲ διαλόγου δέλτος οὐ κεκίνηται ὅτι ἤδη ἐστὶ ἐνθάδε διαλόγου δέλτος.
Δεῖ σοι καθ'ἕκαστον συγκεραννύναι.'''",
'movedto'              => 'Κεκίνηται πρὸς',
'movetalk'             => 'Κινεῖν τὴν διαλόγου δέλτον',
'1movedto2'            => '[[:$1]] ἐκινήθη πρὸς [[:$2]]',
'movelogpage'          => 'Τὰ κινεῖν',
'movereason'           => 'Ἀπολογία:',
'revertmove'           => 'Ἐπανέρχεσθαι',
'delete_and_move_text' => '==Διαγραφὴ ἀπαραίτητος==
Ἡ ἐγγραφὴ [[:$1]] ὑπάρχει ἤδη. Βούλῃ διαγράψειν τήνδε ἵνα ἐκτελέσηται ἡ μετακίνησις;',

# Export
'export'            => 'Δέλτους ἐξάγειν',
'export-submit'     => 'Ἐξάγειν',
'export-addcattext' => 'Προστιθέναι δέλτους ἐκ τῆς κατηγορίας:',
'export-addcat'     => 'Προστιθέναι',
'export-download'   => 'Σῴζειν ὡς ἀρχεῖον',

# Namespace 8 related
'allmessages'        => 'Μυνήματα συστήματος',
'allmessagesname'    => 'Ὄνομα',
'allmessagesdefault' => 'Προεπειλεγμένον κείμενον',
'allmessagescurrent' => 'Τρέχον κείμενον',

# Thumbnails
'thumbnail-more'  => 'Αὐξάνειν',
'filemissing'     => 'Ἀρχεῖον ἐκλιπόν',
'thumbnail_error' => 'Σφάλμα τοῦ δημιουργεῖν σύνοψιν: $1',

# Special:Import
'import-interwiki-submit' => 'Εἰσάγειν',
'import-upload'           => 'Ἐπιφόρτωσις δεδομένων XML',

# Import log
'importlogpage'             => 'Εἰσάγειν κατάλογον',
'import-logentry-interwiki' => 'οὐικιπεποιημένη $1',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Τὴν δέλτον χρωμένου ἐμήν',
'tooltip-pt-mytalk'               => 'Ἡ διάλεξίς μου',
'tooltip-pt-preferences'          => 'Αἱ αἱρέσεις μου',
'tooltip-pt-watchlist'            => 'Κατάλογος τῶν ἐφορωμένων μου',
'tooltip-pt-mycontris'            => 'Κατάλογος τῶν ἐράνων μου',
'tooltip-pt-login'                => 'Ἐνθαρρυντέον τὸ συνδεῖσθαι, οὐκ υποχρεωτικόν.',
'tooltip-pt-anonlogin'            => 'Ἐνθαρρυντέον τὸ συνδεῖσθαι, οὐκ υποχρεωτικόν.',
'tooltip-pt-logout'               => 'Ἐξέρχεσθαι',
'tooltip-ca-talk'                 => 'Διάλεκτος περὶ τῆς δέλτου',
'tooltip-ca-edit'                 => 'Ἔξεστι σοι μεταγράφειν τήνδε τὴν δέλτον. Προεπισκοπεῖν πρὶν ἂν γράφῃς τὴν δέλτον.',
'tooltip-ca-addsection'           => 'Προστιθέναι σχόλιόν τι τῇ συζητήσει.',
'tooltip-ca-viewsource'           => 'Σῴζεται ἥδε ἡ δέλτος.
Ἔξεστι σοὶ τὴν πηγήν ἐπισκοπεῖν.',
'tooltip-ca-protect'              => 'Ἀμύνειν τῇδε τῇ δέλτῳ',
'tooltip-ca-delete'               => 'Διαγράφειν τήνδε τὴν δέλτον',
'tooltip-ca-move'                 => 'Κινεῖν τήνδε τὴν δέλτον',
'tooltip-ca-watch'                => 'Ἐφορᾶν τήνδε τὴν δέλτον',
'tooltip-ca-unwatch'              => 'Ἀνεφορᾶν τήνδε τὴν δέλτον',
'tooltip-search'                  => 'Ζητεῖν {{SITENAME}}',
'tooltip-p-logo'                  => 'Δέλτος Μεγίστη',
'tooltip-n-mainpage'              => 'Πορεύεσθαι τὴν κυρίαν Δέλτον',
'tooltip-n-portal'                => 'Τὰ περὶ ταῦτης τε τὴς ἐνκυκλοπαιδείας, τῶν τε οἷων ἔξεστι σοὶ πράττεις, οὗ παρεστὶ τινά',
'tooltip-n-currentevents'         => 'Πληροφορίαι διὰ ἐπίκαιρα γεγονότα',
'tooltip-n-recentchanges'         => 'Κατάλογος κατὰ πᾶσας τὰς νέας μεταβολάς.',
'tooltip-n-randompage'            => 'Τινὰ γραφὴν χύδην δηλοῦν.',
'tooltip-n-help'                  => 'Μάθησις περὶ τῆσδε Οὐίκεως',
'tooltip-t-whatlinkshere'         => 'Κατάλογος τῶν ἐνθάδε ἀγόντων',
'tooltip-feed-rss'                => 'RSS Ῥοὴ διὰ τήνδε δέλτον',
'tooltip-feed-atom'               => 'Atom Ῥοὴ διὰ τήνδε δέλτον',
'tooltip-t-contributions'         => 'Ὁρᾶν τοὺς τοῦδε τοῦ χρωμένου ἐράνους',
'tooltip-t-emailuser'             => 'Ἠλεκτρονικὴν ἐπιστολὴν τῷδε τῷ χρήστῃ πέμπειν',
'tooltip-t-upload'                => 'Φορτία ἐντιθέναι',
'tooltip-t-specialpages'          => 'Κατάλογος κατὰ πᾶσας τὰς εἰδικὰς δέλτους',
'tooltip-ca-nstab-main'           => 'χρῆμα δέλτον ὁρᾶν',
'tooltip-ca-nstab-user'           => 'Δέλτος χρωμένου ὁρᾶν',
'tooltip-ca-nstab-project'        => 'Ὁρᾶν τὴν δέλτον τοῦ σχεδίου',
'tooltip-ca-nstab-image'          => 'Ὁρᾶν τὴν τοῦ φορτίου δέλτον',
'tooltip-ca-nstab-template'       => 'Ὁρᾶν πρότυπον',
'tooltip-ca-nstab-help'           => 'Ὁρᾶν δέλτον βοηθείας',
'tooltip-ca-nstab-category'       => 'Ἐπισκοπεῖν τὴν τῆς κατηγορίας δέλτον',
'tooltip-minoredit'               => 'Δεικνύναι ἥδε ἡ μεταβολή μικρά εἴναι',
'tooltip-save'                    => 'Γράφειν τὰς μεταβολάς σου',
'tooltip-preview'                 => 'Προεπισκοπεῖν τὰς ἀλλαγὰς ὑμῶν. Παρακαλοῦμεν ὑμᾶς ἵνα χρῆτε ταύτην τὴν ἐπιλογὴν πρὸ τοῦ αποθηκεύειν!',
'tooltip-diff'                    => 'Δεῖξαι τὰς γεγράμμενας μεταβολάς.',
'tooltip-compareselectedversions' => 'Ὁρᾶν τὰς διαφορὰς μεταξὺ τῶν δύω ἐπιλεγμένων ἐκδοχῶν ταύτης τῆς δέλτου.',
'tooltip-watch'                   => 'Ἐφορᾶν τήνδε τὴν δέλτον',
'tooltip-upload'                  => 'Ἐκκινεῖν ἀναφόρτωσιν',

# Attribution
'others'      => 'ἄλλοι',
'creditspage' => 'Διαπιστεύσεις δέλτου',

# Info page
'numtalkedits' => 'Ἀριθμὸς μεταγραφῶν (δέλτος συζητήσεως): $1',

# Math options
'mw_math_png' => 'Ἀπόδοσις PNG πάντοτε',

# Patrolling
'markaspatrolleddiff' => 'Σεσημασμένη ὡς ἐπιτηρουμένη',

# Patrol log
'patrol-log-page' => 'Κατάλογος περιπόλων',
'patrol-log-auto' => '(αὐτόματον)',

# Browsing diffs
'previousdiff' => '← ἡ μεταβολὴ ἡ προτέρη',
'nextdiff'     => 'ἡ μεταβολὴ ἡ ἐχομένη →',

# Media information
'file-info-size'       => '($1 × $2 εἰκονοστοιχεῖα, μέγεθος ἀρχείου: $3, τύπος MIME: $4)',
'file-nohires'         => '<small>Οὐ διατίθεται ὑψηλοτέρα ἀνάλυσις.</small>',
'svg-long-desc'        => '(αρχεῖον SVG, ὀνομαστικὼς $1 × $2 εἰκονοστοιχεῖα, μέγεθος ἀρχείου: $3)',
'show-big-image'       => 'Πλήρης ἀνάλυσις',
'show-big-image-thumb' => '<small>Τοῦδε προεπισκοπεῖν μέγεθος: $1 × $2 εἰκονοστοιχεία</small>',

# Special:NewImages
'newimages'        => 'Τὰ νέα φορτία δεῦρο ἀθροίζειν',
'newimages-legend' => 'Διηθητήριον',
'showhidebots'     => '($1 αὐτόματα)',
'ilsubmit'         => 'Ζητεῖν',
'bydate'           => 'κατὰ χρονολογίαν',

# Bad image list
'bad_image_list' => 'Ἡ μορφοποιία ὡς ἑξῆς ἐστίν:

Μόνον ἀντικείμενα διαλογῆς (γραμμαὶ ἐκκινουμέναι μετὰ τοῦ *) δεκτὰ εἰσί. 
Ὁ πρῶτος σύνδεσμος ἐν ἐνίτινι γραμμῇ ὀφείλει εἶναι σύνδεσμος πρὸς ἕνα κακὸν ἀρχεῖον.
Οἷοι δή ποτε ἐπακόλουθοι σύνδεσμοι ἐν τῇ αυτῇ γραμμῇ θεωρουμένοι ἐξαιρέσεις εἰσίν, δῆλα δὴ δέλτοι ὅπου ἡ εἰκὼν δύναται ξυμβῆναι ἐν συνδέσει.',

# Metadata
'metadata'          => 'Μεταδεδομένα',
'metadata-help'     => 'Τόδε ἀρχεῖον περιέχει προσθέτους πληροφορίας, πιθανὼς προστεθειμένας ἐκ τῆς ψηφιακῆς φωτογραφικῆς μηχανῆς ἢ τοῦ σαρωτοῦ χρησθέντος τῇ δημιουργίᾳ ἢ τῇ ἑῇ ψηφιοποιήσει. Εἰ τὸ ἀρχεῖον ἤλλακται ἐκ τῆς ἑῆς ἀρχικῆς καταστάσεως, ἀκρίβειαί τινες πιθανὼς μὴ πλήρως τὸ ἠλλαγμενον ἀρχεῖον ἀνακλοῦσιν.',
'metadata-expand'   => 'Δηλοῦν τὰς ἀκριβείας',
'metadata-collapse' => 'Κρύπτειν τὰς ἀκριβείας',
'metadata-fields'   => 'Τὰ πεδία μεταδεδομένων EXIF ταύτου τοῦ μηνύματος περιλήψονται ἐν τῇ δέλτῳ ἐμφανίσεως εἰκόνος ὁπηνίκα ὁ πίναξ μεταδεδομένων ἀποκρύψηται. Τὰ ἕτερα πεδία ἔσονται κεκρυμμένα κατὰ προεπιλογήν.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength', # Do not translate list items

# EXIF tags
'exif-imagewidth'          => 'Πλάτος',
'exif-imagelength'         => 'Ὕψος',
'exif-orientation'         => 'Προσανατόλισις',
'exif-planarconfiguration' => 'Παράταξις δεδομένων',
'exif-xresolution'         => 'Ὁριζόντιος ἀνάλυσις',
'exif-yresolution'         => 'Κάθετος ἀνάλυσις',
'exif-imagedescription'    => 'Ἐπιγραφὴ εἰκόνος',
'exif-make'                => 'Ἐξεργαστὴς τῆς εἰκονοληπτικῆς μηχανῆς',
'exif-model'               => 'Πρότυπον τῆς εἰκονοληπτικῆς μηχανῆς',
'exif-artist'              => 'Πρωτουργός',
'exif-colorspace'          => 'Χρωματικὸς χῶρος',
'exif-exposuretime'        => 'Χρόνος ἐκθέσεως',
'exif-exposuretime-format' => '$1 δευτ. ($2)',
'exif-fnumber'             => 'F Ἀριθμός',
'exif-oecf'                => 'Παράγων ὀπτοηλεκτονικῆς μετατροπῆς',
'exif-aperturevalue'       => 'Ἄνοιξις διαφράγματος',
'exif-brightnessvalue'     => 'Φωτεινότης',
'exif-lightsource'         => 'Πηγὴ φωτός',
'exif-flash'               => 'Ἀστραποβόλον',
'exif-subjectarea'         => 'Θεματικὸν πεδίον',
'exif-filesource'          => 'Πηγὴ ἀρχείου',
'exif-contrast'            => 'Ἀντίθεσις',
'exif-saturation'          => 'Κορεσμός',
'exif-sharpness'           => 'Ὀξύτης',
'exif-gpslatituderef'      => 'Βόρειον ἢ Νότιον γεωγραφικὸν πλάτος',
'exif-gpslatitude'         => 'Πλάτος γεωγραφικόν',
'exif-gpslongituderef'     => 'Ἀνατολικὸν ἢ Δυτικὸν γεωγραφικὸν μῆκος',
'exif-gpslongitude'        => 'Γεωγραφικὸν μῆκος',
'exif-gpsaltituderef'      => 'Ἀναφορὰ ὕψους γεωγραφικοῦ',
'exif-gpsaltitude'         => 'Γεωγραφικὸν ὕψος',
'exif-gpsspeedref'         => 'Μονὰς ταχύτητος',
'exif-gpsdatestamp'        => 'Χρονολογία GPS',

'exif-unknowndate' => 'Ἄγνωτος χρονολογία',

'exif-orientation-1' => 'Κανονικόν', # 0th row: top; 0th column: left
'exif-orientation-3' => 'Περιεστραμμένον κατὰ 180°', # 0th row: bottom; 0th column: right

'exif-componentsconfiguration-0' => 'Οὐκ ἔστι',

'exif-exposureprogram-1' => 'Χειροκίνητον',
'exif-exposureprogram-3' => 'Προτεραιότης ἀνοἰξεως διαφράγματος',

'exif-subjectdistance-value' => '$1 μέτρα',

'exif-meteringmode-0'   => 'Ἄγνωτον',
'exif-meteringmode-1'   => 'Μέσον',
'exif-meteringmode-5'   => 'Ὑπόδειγμα',
'exif-meteringmode-6'   => 'Μερικόν',
'exif-meteringmode-255' => 'Ἄλλη',

'exif-lightsource-0'   => 'Ἄγνωτη',
'exif-lightsource-2'   => 'Φθορίζον',
'exif-lightsource-3'   => 'Βαρυλίθιον (πυρακτωσικὸν φῶς)',
'exif-lightsource-4'   => 'Ἀστραποβόλος συσκευή',
'exif-lightsource-11'  => 'Σκίασις',
'exif-lightsource-12'  => 'Ἡμερινοφωτικὴ φθοριοφάνεια (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Ἡμερινὴ λευκὴ φθοριοφάνεια (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Ψυχρὴ λευκὴ φθοριοφάνεια (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Λευκὴ φθοριοφάνεια (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Τυπικὸν φῶς A',
'exif-lightsource-18'  => 'Τυπικὸν φῶς B',
'exif-lightsource-19'  => 'Τυπικὸν φῶς C',
'exif-lightsource-24'  => 'Βαρυλίθιον τοῦ ἐργαστηρίου ISO',
'exif-lightsource-255' => 'Ἑτέραι φωτοπηγαί',

'exif-focalplaneresolutionunit-2' => 'οὐγκιαί',

'exif-sensingmethod-1' => 'Ἀόριστος',
'exif-sensingmethod-7' => 'Τριγραμμικὸν αἰσθητήριον',

'exif-customrendered-0' => 'Κανονικὴ διαδικασία',
'exif-customrendered-1' => 'Συνήθης διαδικασία',

'exif-exposuremode-0' => 'Αὐτοέκθεσις',
'exif-exposuremode-1' => 'Χειροκίνητος ἔκθεσις',

'exif-scenecapturetype-0' => 'Συνήθης',
'exif-scenecapturetype-1' => 'Τοπίον',
'exif-scenecapturetype-2' => 'Παράστασις',
'exif-scenecapturetype-3' => 'Νυκτερινὴ σκηνή',

'exif-gaincontrol-0' => 'Οὐδεμία',
'exif-gaincontrol-1' => 'Χθαμηλὸν κέρδος ἄνω',
'exif-gaincontrol-2' => 'Ὑψηλὸν κέρδος ἄνω',
'exif-gaincontrol-3' => 'Χθαμηλὸν κέρδος κάτω',
'exif-gaincontrol-4' => 'Ὑψηλὸν κέρδος κάτω',

'exif-contrast-0' => 'Κανονική',
'exif-contrast-1' => 'Ἁπαλή',
'exif-contrast-2' => 'Σκληρή',

'exif-saturation-0' => 'Κανονικόν',
'exif-saturation-1' => 'Χθαμηλὸς κορεσμός',
'exif-saturation-2' => 'Ὑψηλὸς κορεσμός',

'exif-sharpness-0' => 'Κανονική',
'exif-sharpness-1' => 'Ἁπαλή',
'exif-sharpness-2' => 'Σκληρή',

'exif-subjectdistancerange-0' => 'Ἄγνωτη',
'exif-subjectdistancerange-1' => 'Μακρο.',
'exif-subjectdistancerange-2' => 'Ἐγγεῖα θέα',
'exif-subjectdistancerange-3' => 'Ἀφεστηκυῖα θέα',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Βόρειον γεωγραφικὸν πλάτος',
'exif-gpslatitude-s' => 'Νότιον γεωγραφικὸν πλάτος',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Ἀνατολικὸν γεωγραφικὸν μῆκος',
'exif-gpslongitude-w' => 'Δυτικὸν γεωγραφικὸν μῆκος',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'Χιλιόμετρα ἀνὰ ὥρα',
'exif-gpsspeed-m' => 'Μίλια ἀνὰ ὥρα',
'exif-gpsspeed-n' => 'Κόμβοι',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Ἀληθὴς κατεύθυνσις',

# External editor support
'edit-externally'      => 'Μεταγράφειν ταῦτο τὸ ἀρχεῖον χρωμένοι μίαν ἐξώτερην ἐφαρμογήν.',
'edit-externally-help' => 'Εἰ πλείοντα βούλει μαθεῖν, [http://www.mediawiki.org/wiki/Manual:External_editors τὰς περὶ τοῦ σχῆματος διδασκαλίας] λέξε.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'Πᾶσαι',
'imagelistall'     => 'Πᾶσαι',
'watchlistall2'    => 'Πάντα',
'namespacesall'    => 'πάντα',
'monthsall'        => 'ἅπαντες',

# E-mail address confirmation
'confirmemail_subject' => '{{SITENAME}} ἐπιβεβαίωσις διευθύνσεως ἠλ.-ταχυδρομείου',

# Scary transclusion
'scarytranscludefailed'  => '[Τὸ προσκομίζειν τὸ πρότυπον διὰ τὸ $1 ἀπετεύχθη· συγγνώμην]',
'scarytranscludetoolong' => '[Ὁ URL ὑπὲρ τὸ δέον μακρύς ἐστιν· συγγνώμην]',

# Trackbacks
'trackbackbox'      => '<div id="mw_trackbacks">Ὀνασύνδεσμοι διὰ τήνδε ἐγγραφήν:<br />
$1
</div>',
'trackbackremove'   => ' ([$1 Διαγράφειν])',
'trackbacklink'     => 'Ὀνασύνδεσμος',
'trackbackdeleteok' => 'Ὀνασύνδεσμος ἐπιτυχὼς διαγραφείς.',

# Delete conflict
'recreate' => 'Ἀναδημιουργεῖν',

# action=purge
'confirm_purge'        => 'Καθαίρειν τὴν λανθάνουσαν μνήμην τῆσδε δέλτου;

$1',
'confirm_purge_button' => 'εἶεν',

# AJAX search
'hideresults'   => 'Ἀποκρύπτειν ἀποτελέσματα',
'useajaxsearch' => 'Χρῆσθαι ἀναζήτησιν AJAX',

# Multipage image navigation
'imgmultipageprev' => '← Δέλτος προτέρα',
'imgmultipagenext' => 'Δέλτος ἡ ἐχομένη →',
'imgmultigo'       => 'Ἰέναι!',
'imgmultigoto'     => 'Μεταβαίνειν εἰς δέλτον $1',

# Table pager
'ascending_abbrev'         => 'ἀναβ',
'descending_abbrev'        => 'καταβ',
'table_pager_next'         => 'Δέλτος ἡ ἐχομένη',
'table_pager_prev'         => 'Δέλτος προτέρα',
'table_pager_first'        => 'Δέλτος πρώτη',
'table_pager_last'         => 'Δέλτος ἐσχάτη',
'table_pager_limit_submit' => 'Ἰέναι',
'table_pager_empty'        => 'Οὐδὲν ἀποτέλεσμα',

# Auto-summaries
'autosumm-new' => 'Δέλτος νέα: $1',

# Size units
'size-bytes'     => '$1 Δ',
'size-kilobytes' => '$1 ΧΔ',
'size-megabytes' => '$1 ΜΔ',
'size-gigabytes' => '$1 ΓΔ',

# Live preview
'livepreview-loading' => 'Φορτίζειν…',
'livepreview-ready'   => 'Φορτίζειν… Ἕτοιμον!',

# Watchlist editor
'watchlistedit-normal-title'  => 'Μεταγράφειν κατάλογον ἐφορωμένων',
'watchlistedit-normal-submit' => 'Ἀφαιρεῖν ἐπιγραφάς',
'watchlistedit-raw-title'     => 'Μεταγράφειν πρωταρχικὸν κατάλογον ἐφορωμένων',
'watchlistedit-raw-legend'    => 'Μεταγράφειν πρωταρχικὸν κατάλογον ἐφορωμένων',
'watchlistedit-raw-titles'    => 'Ἐπιγραφαί:',
'watchlistedit-raw-submit'    => 'Ἐκσυγχρονίζειν τὸν κατάλογον ἐφορωμένων',

# Watchlist editing tools
'watchlisttools-view' => 'Ὁρᾶν τὰς πρὸς ταῦτα μεταβολὰς',
'watchlisttools-edit' => 'Ὁρᾶν τε καὶ μεταγράφειν τὰ ἐφορωμένα',
'watchlisttools-raw'  => 'Μεταγράφειν τὸν πρωτογενῆ κατάλογον ἐφορωμένων',

# Special:Version
'version'                   => 'Ἐπανόρθωμα', # Not used as normal message but as header for the special page itself
'version-extensions'        => 'Ἐγκατεστημέναι ἐπεκτάσεις',
'version-specialpages'      => 'Εἰδικαὶ δέλτοι',
'version-parserhooks'       => 'Ἐπεκτάσεις λεξιαναλυτικοῦ προγράμματος',
'version-variables'         => 'Μεταβληταί',
'version-other'             => 'Ἄλλα',
'version-mediahandlers'     => 'Χειρισταὶ μέσων',
'version-hooks'             => 'Ἀγγύλαι',
'version-hook-name'         => 'Ὄνομα ἀγκύλης',
'version-hook-subscribedby' => 'Προγεγραφὼς ἀπὸ',
'version-version'           => 'Ἐκδοχή',
'version-license'           => 'Ἄδεια',
'version-software'          => 'Ἐγκατεστημένον λογισμικόν',
'version-software-product'  => 'Προϊόν',
'version-software-version'  => 'Ἐκδοχή',

# Special:FilePath
'filepath'        => 'Διαδρομὴ ἀρχείου',
'filepath-page'   => 'Ἀρχεῖον:',
'filepath-submit' => 'Διαδρομή',

# Special:FileDuplicateSearch
'fileduplicatesearch-filename' => 'Ὄνομα ἀρχείου:',
'fileduplicatesearch-submit'   => 'Ἀναζήτησις',

# Special:SpecialPages
'specialpages'                   => 'Εἰδικαὶ δέλτοι',
'specialpages-group-maintenance' => 'Ἀναφοραὶ συντηρήσεως',
'specialpages-group-other'       => 'Ἕτεραι εἰδικαὶ δέλτοι',
'specialpages-group-login'       => 'Συνδεῖσθαι / ἐγγράφεσθαι',
'specialpages-group-changes'     => 'Πρόσφατοι ἀλλαγαὶ καὶ κατάλογοι',
'specialpages-group-media'       => 'Ἀναφοραὶ μέσων καὶ ἐπιφορτίσεις',
'specialpages-group-users'       => 'Χρώμενοι καὶ δικαιώματα',
'specialpages-group-highuse'     => 'Ὑψηλῆς χρήσεως δέλτοι',
'specialpages-group-pages'       => 'Κατάλογος δέλτων',
'specialpages-group-pagetools'   => 'Ἐργαλεῖα δέλτου',
'specialpages-group-wiki'        => 'Οὐκιδεδομένα καὶ στοιχεῖα',
'specialpages-group-redirects'   => 'Ἀναδιευθύνειν εἰδικὰς δέλτους',
'specialpages-group-spam'        => 'Ἐργαλεῖα κατὰ τῶν ἀνεπιθυμήτων διαγγελιῶν',

# Special:BlankPage
'blankpage'              => 'Κενὴ δέλτος',
'intentionallyblankpage' => 'Ταῦτη ἡ δέλτος ἀφίεται ἐσκεμμένως κενὴ καὶ ἐστὶ χρήσιμη ὡς σημεῖον ἀναφορᾶς, κτλ.',

);
