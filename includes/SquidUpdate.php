<?php
# See deferred.doc

class SquidUpdate {

        function SquidUpdate( $title, $urlArr = Array() )
        {
                $this->title = $title;
                $this->urlArr = $urlArr;
        }


        function doUpdate()
        {
                if (count( $this->urlArr ) == 0) { // newly created Article
                        global $wgInternalServer;
                        /* prepare the list of urls to purge */
                        $id= $this->title->getArticleID();
                        $sql = "SELECT l_from FROM links WHERE l_to={$id}" ;
                        $res = wfQuery ( $sql, DB_READ ) ;
                        while ( $BL = wfFetchObject ( $res ) )
                        {
                                $t = Title::newFromDBkey( $BL->l_from) ; 
                                $this->urlArr[] = $wgInternalServer.wfLocalUrl( $t->getPrefixedURL() );
                        }
                        wfFreeResult ( $res ) ;

        }

        wfPurgeSquidServers($this->urlArr);
}
}

?>
