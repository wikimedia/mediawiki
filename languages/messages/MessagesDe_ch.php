<?php
/** Swiss High German (Schweizer Hochdeutsch)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Ianusius
 * @author Kghbln
 * @author MichaelFrey
 * @author SVG
 * @author The Evil IP address
 */

$fallback = 'de';
$separatorTransformTable = array( ',' => "'", '.' => ',' );

$messages = array(
# General errors
'actionthrottledtext' => 'Im Rahmen einer Anti-Spam-Massnahme oder aufgrund eines Missbrauchsfilters kann diese Aktion in einem kurzen Zeitabstand nur begrenzt oft ausgeführt werden. Diese Grenze hast du überschritten.
Bitte versuche es in ein paar Minuten erneut.',

# Login and logout pages
'nosuchuser' => 'Der Benutzername „$1“ existiert nicht.
Überprüfe die Schreibweise (Gross-/Kleinschreibung beachten) oder [[Special:UserLogin/signup|melde dich als neuer Benutzer an]].',

# Password reset dialog
'resetpass_announce' => 'Anmeldung mit dem per E-Mail zugesandten Code. Um die Anmeldung abzuschliessen, musst du jetzt ein neues Passwort wählen.',

# Edit pages
'longpageerror' => "'''Fehler: Der Text, den du zu speichern versuchst, ist $1 KB gross. Dies ist grösser als das erlaubte Maximum von $2 KB.'''
Er kann nicht gespeichert werden.",

# Parser/template warnings
'post-expand-template-inclusion-warning'  => 'Warnung: Die Grösse eingebundener Vorlagen ist zu gross, einige Vorlagen können nicht eingebunden werden.',
'post-expand-template-inclusion-category' => 'Seiten, in denen die maximale Grösse eingebundener Vorlagen überschritten ist',
'post-expand-template-argument-warning'   => "'''Warnung:''' Diese Seite enthält mindestens ein Argument in einer Vorlage, das expandiert zu gross ist. Diese Argumente werden ignoriert.",

# History merging
'mergehistory-merge' => 'Die folgenden Versionen von „[[:$1]]“ können nach „[[:$2]]“ übertragen werden. Markiere die Version, bis zu der (einschliesslich) die Versionen übertragen werden sollen. Bitte beachte, dass die Nutzung der Navigationslinks die Auswahl zurücksetzt.',

# Search results
'toomanymatches' => 'Die Anzahl der Suchergebnisse ist zu gross, bitte versuche eine andere Abfrage.',
'nonefound'      => "'''Hinweis:''' Es werden standardmässig nur einige Namensräume durchsucht. Setze ''all:'' vor deinen Suchbegriff, um alle Seiten (inkl. Diskussionsseiten, Vorlagen usw.) zu durchsuchen oder gezielt den Namen des zu durchsuchenden Namensraumes.",

# Preferences page
'prefs-watchlist-days' => 'Anzahl der Tage, die die Beobachtungsliste standardmässig umfassen soll:',
'prefs-edit-boxsize'   => 'Grösse des Bearbeitungsfensters:',
'recentchangesdays'    => 'Anzahl der Tage, die die Liste der „Letzten Änderungen“ standardmässig umfassen soll:',
'recentchangescount'   => 'Anzahl der standardmässig angezeigten Bearbeitungen:',
'defaultns'            => 'In diesen Namensräumen soll standardmässig gesucht werden:',
'prefs-textboxsize'    => 'Grösse des Bearbeitungsfensters',

# Rights
'right-createpage'            => 'Seiten erstellen (ausser Diskussionsseiten)',
'right-bigdelete'             => 'Seiten mit grosser Versionsgeschichte löschen',
'right-override-export-depth' => 'Exportiere Seiten einschliesslich verlinkter Seiten bis zu einer Tiefe von 5',

# Upload
'file-too-large'           => 'Die übertragene Datei ist zu gross',
'large-file'               => 'Die Dateigrösse sollte nach Möglichkeit $1 nicht überschreiten. Diese Datei ist $2 gross.',
'largefileserver'          => 'Die Datei ist grösser als die vom Server eingestellte Maximalgrösse.',
'fileexists-extension'     => "Eine Datei mit ähnlichem Namen existiert bereits: [[$2|thumb]]
* Name der hochzuladenden Datei: '''<tt>[[:$1]]</tt>'''
* Name der vorhandenen Datei: '''<tt>[[:$2]]</tt>'''
Nur die Dateiendung unterscheidet sich in Gross-/Kleinschreibung. Bitte prüfe, ob die Dateien inhaltlich identisch sind.",
'fileexists-thumbnail-yes' => "Bei der Datei scheint es sich um ein Bild verringerter Grösse ''(thumbnail)'' zu handeln. [[$1|thumb]]
Bitte prüfe die Datei '''<tt>[[:$1]]</tt>'''.
Wenn es sich um das Bild in Originalgrösse handelt, so braucht kein separates Vorschaubild hochgeladen zu werden.",
'file-thumbnail-no'        => "Der Dateiname beginnt mit '''<tt>$1</tt>'''. Dies deutet auf ein Bild verringerter Grösse ''(thumbnail)'' hin.
Bitte prüfe, ob du das Bild in voller Auflösung vorliegen hast und lade dieses unter dem Originalnamen hoch.",
'upload-maxfilesize'       => 'Maximale Dateigrösse: $1',

'upload-unknown-size' => 'Unbekannte Grösse',

# img_auth script messages
'img-auth-nologinnWL' => 'Du bist nicht angemeldet und „$1“ ist nicht in der weissen Liste.',

# Special:ListFiles
'listfiles-summary' => 'Diese Spezialseite listet alle hochgeladenen Dateien auf. Standardmässig werden die zuletzt hochgeladenen Dateien zuerst angezeigt. Durch einen Klick auf die Spaltenüberschriften kann die Sortierung umgedreht werden oder es kann nach einer anderen Spalte sortiert werden.',
'listfiles_size'    => 'Grösse',

# File description page
'filehist-dimensions' => 'Masse',
'filehist-filesize'   => 'Dateigrösse',

# Special:Log
'alllogstext' => 'Dies ist die kombinierte Anzeige aller in {{SITENAME}} geführten Logbücher.
Die Ausgabe kann durch die Auswahl des Logbuchtyps, des Benutzers oder des Seitentitels eingeschränkt werden (Gross-/Kleinschreibung muss beachtet werden).',

# Protect
'minimum-size' => 'Mindestgrösse',
'maximum-size' => 'Maximalgrösse:',

# Block/unblock
'ip_range_toolarge' => 'Adressbereiche, die größer als /$1 sind, sind nicht erlaubt.',

# Thumbnails
'thumbnail-more'  => 'vergrössern',
'djvu_page_error' => 'DjVu-Seite ausserhalb des Seitenbereichs',

# Special:Import
'import-interwiki-templates' => 'Alle Vorlagen einschliessen',
'importuploaderrorsize'      => 'Das Hochladen der Importdatei ist fehlgeschlagen. Die Datei ist grösser als die maximal erlaubte Dateigrösse.',

# Media information
'imagemaxsize'         => "Maximale Bildgrösse:<br />''(für Dateibeschreibungsseiten)''",
'thumbsize'            => 'Standardgrösse der Vorschaubilder:',
'file-info'            => 'Dateigrösse: $1, MIME-Typ: $2',
'file-info-size'       => '$1 × $2 Pixel, Dateigrösse: $3, MIME-Typ: $4',
'svg-long-desc'        => 'SVG-Datei, Basisgrösse: $1 × $2 Pixel, Dateigrösse: $3',
'show-big-image-thumb' => '<small>Grösse der Voransicht: $1 × $2 Pixel</small>',

# Metadata
'metadata-fields' => 'Die folgenden Felder der EXIF-Metadaten in diesem MediaWiki-Systemtext werden auf Bildbeschreibungsseiten angezeigt; weitere standardmässig „eingeklappte“ Details können angezeigt werden.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-resolutionunit'              => 'Masseinheit der Auflösung',
'exif-jpeginterchangeformatlength' => 'Grösse der JPEG-Daten in Bytes',
'exif-referenceblackwhite'         => 'Schwarz/Weiss-Referenzpunkte',
'exif-maxaperturevalue'            => 'Grösste Blende',
'exif-whitebalance'                => 'Weissabgleich',
'exif-gpsdop'                      => 'Masspräzision',

'exif-lightsource-13' => 'Tagesweiss fluoreszierend (N 4600–5400 K)',
'exif-lightsource-14' => 'Kaltweiss fluoreszierend (W 3900–4500 K)',
'exif-lightsource-15' => 'Weiss fluoreszierend (WW 3200–3700 K)',

# Special:Version
'version-license-info' => "MediaWiki ist freie Software, d. h. sie kann, gemäss den Bedingungen der von der Free Software Foundation veröffentlichten ''GNU General Public License'', weiterverteilt und/ oder modifiziert werden. Dabei kann die Version 2, oder nach eigenem Ermessen, jede neuere Version der Lizenz verwendet werden.

MediaWiki wird in der Hoffnung verteilt, dass es nützlich sein wird, allerdings OHNE JEGLICHE GARANTIE und sogar ohne die implizierte Garantie einer MARKTGÄNGIGKEIT oder EIGNUNG FÜR EINEN BESTIMMTEN ZWECK. Hierzu sind weitere Hinweise in der ''GNU General Public License'' enthalten.

Eine [{{SERVER}}{{SCRIPTPATH}}/COPYING Kopie der ''GNU General Public License''] sollte zusammen mit diesem Programm verteilt worden sein. Sofern dies nicht der Fall war, kann eine Kopie bei der Free Software Foundation Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA, schriftlich angefordert oder auf deren Website [http://www.gnu.org/licenses/old-licenses/gpl-2.0.html online gelesen] werden.",

# Special:FileDuplicateSearch
'fileduplicatesearch-info' => '$1 × $2 Pixel<br />Dateigrösse: $3<br />MIME-Typ: $4',

# External image whitelist
'external_image_whitelist' => ' #Diese Zeile nicht verändern<pre>
#Untenstehend können Fragmente regulärer Ausdrücke (der Teil zwischen den //) eingegeben werden.
#Diese werden mit den URLs von Bildern aus externen Quellen verglichen
#Ein positiver Vergleich führt zur Anzeige des Bildes, andernfalls wird das Bild nur als Link angezeigt
#Zeilen, die mit einem # beginnen, werden als Kommentar behandelt
#Es wird nicht zwischen Gross- und Kleinschreibung unterschieden

#Fragmente regulärer Ausdrücke nach dieser Zeile eintragen. Diese Zeile nicht verändern</pre>',

);
