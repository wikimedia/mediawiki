<?php
require_once( "LanguageUtf8.php" );

if ( $wgMetaNamespace == "Wikipedia" ) {
        $wgMetaNamespace = "Vikipedi";
}
/* private */ $wgNamespaceNamesTr = array(
        NS_MEDIA            => 'Media',
        NS_SPECIAL          => 'Özel',
        NS_MAIN             => '',
        NS_TALK             => 'Tartisma',
        NS_USER             => 'Kullanici',
        NS_USER_TALK        => 'Kullanici_mesaj',
        NS_PROJECT          => $wgMetaNamespace,
        NS_PROJECT_TALK     => $wgMetaNamespace . '_tartisma',
        NS_IMAGE            => 'Resim',
        NS_IMAGE_TALK       => 'Resim_tartisma',
        NS_MEDIAWIKI        => 'MedyaViki',
        NS_MEDIAWIKI_TALK   => 'MedyaViki_tartisma',
        NS_TEMPLATE         => 'Sablon',
        NS_TEMPLATE_TALK    => 'Sablon_tartisma',
        NS_HELP             => 'Yardim',
        NS_HELP_TALK        => 'Yardim_tartisma',
        NS_CATEGORY         => 'Kategori',
        NS_CATEGORY_TALK    => 'Kategori_tartisma',
) + $wgNamespaceNamesEn;

class LanguageTr extends LanguageUtf8 {
        function getNamespaces() {
                global $wgNamespaceNamesTr;
                return $wgNamespaceNamesTr;
        }

        function formatNum( $number, $year = false ) {
                return $year ? $number : strtr($this->commafy($number), '.,', ',.' );
        }



}


?>
