<?php
class institutioner {
    
    public function ajaxControl() {
        $catvalue = t3lib_div::_GP('catvalue');
        $action = t3lib_div::_GP('action');
        $checked = t3lib_div::_GP('checked');
        $username = t3lib_div::_GP('username');
        $sys_language = t3lib_div::_GP('sys_language');
        $pluginid = t3lib_div::_GP('pluginid');
        $i = t3lib_div::_GP('i');
        $content = $this->updateIndex($catvalue, $username, $checked, $sys_language, $pluginid, $i);
        echo json_encode($content);
    }
    
    public function updateIndex($catvalue, $username, $checked, $sys_language, $pluginid, $i) 
    {
        require_once(__DIR__ . '/../vendor/solr/Service.php');

        $solr = new Apache_Solr_Service( 'www2.lth.se', '8080', '/solr/personal' );

        $query = "id:$username";
        $results = false;
        $limit = 1;
 
        if (get_magic_quotes_gpc() == 1) {
            $query = stripslashes($query);
        }
        
        try {
            $response = $solr->search($query, 0, $limit);
        }
        catch(Exception $e) {
            return '31:' . $e->getMessage();
            exit();
        }
        
        if(isset($response->response->docs[0])) {
 
            //$docs = array();
            foreach($response->response->docs as $document) {
                $doc = array();
                foreach($document as $field => $value) {
                    $doc[$field] = $value;
                }
                //staff_custom_category_facet_sv
                $catvalueArray = explode('_', $catvalue);
                if($checked==='true') {
                    if(is_array($doc['staff_custom_category_facet_'.$sys_language])) {
                        $doc['staff_custom_category_facet_'.$sys_language][] = urlencode($catvalue);
                    } else if($doc['staff_custom_category_facet_'.$sys_language]) {
                        $doc['staff_custom_category_facet_'.$sys_language] = explode(';', $doc['staff_custom_category_facet_'.$sys_language]);
                        $doc['staff_custom_category_facet_'.$sys_language][] = urlencode($catvalue);
                    } else {
                        $doc['staff_custom_category_facet_'.$sys_language] = urlencode($catvalue);
                    }
                    if($doc['staff_custom_category_sort_'.$sys_language.'_'.$pluginid.'_s']) {
                        if($catvalue < $doc['staff_custom_category_sort_'.$sys_language.'_'.$pluginid.'_s']) {
                            $doc['staff_custom_category_sort_'.$sys_language.'_'.$pluginid.'_s'] = $catvalue;
                        } 
                    } else {
                        $doc['staff_custom_category_sort_'.$sys_language.'_'.$pluginid.'_s'] = $catvalue;
                    }
                } else {
                    if(is_array($doc['staff_custom_category_facet_'.$sys_language])) {
                        unset($doc['staff_custom_category_facet_'.$sys_language][$catvalueArray[0]]);
                    } else {
                        unset($doc['staff_custom_category_facet_'.$sys_language]);
                    }
                    
                    if($doc['staff_custom_category_sort_'.$sys_language.'_'.$pluginid.'_s'] == $catvalue) {
                        unset($doc['staff_custom_category_sort_'.$sys_language.'_'.$pluginid.'_s']);
                    }
                }
                //staff_custom_category_facet_sv
                
                unset($doc['_version_']);
                unset($doc['alphaNameSort']);

               // $docs[] = $doc;
            }

            //$documents = array();

            //foreach ( $docs as $item => $fields ) {

                $part = new Apache_Solr_Document();

                foreach ( $doc as $key => $value ) {
                    if ( is_array( $value ) ) {
                        foreach ( $value as $data ) {
                            $part->setMultiValue( $key, $data );
                        }
                    }
                    else {
                        $part->$key = $value;
                    }
                }

               // $documents[] = $part;
            //}

            try {
                $solr->addDocument($part);
                $solr->commit();
                $solr->optimize();
                $response = 'getFeUsers done!'.$checked.$catvalueArray[0];
            }
            catch ( Exception $e ) {
                $response = $e->getMessage();
            }
        } else {
            $response = "Kein Eintrag gefunden";
        }
        
        return $response;
    }
}
?>