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
	switch($action) {
	    case 'updateIndex':
		$content = $this->updateIndex($catvalue, $username, $checked, $sys_language, $pluginid, $i);
		break;
	    case 'updateImage':
		$content = $this->updateImage($catvalue, $username, $checked, $sys_language, $pluginid, $i);
		break;
	    case 'updateText':
		$content = $this->updateText($catvalue, $username, $checked, $sys_language, $pluginid, $i);
		break;
	}
        
        echo json_encode($content);
    }
    
    public function updateIndex($catvalue, $username, $checked, $sys_language, $pluginid, $i) 
    {
	$confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['institutioner']);
    
	if (!$confArr['solrServer']) {
	    return 'Ange Solr-server';
	}

	if (!$confArr['solrPort']) {
	    return 'Ange Solr-port';
	}

	if (!$confArr['solrPath']) {
	    return 'Ange Solr-path';
	}
	//$catvalue = str_replace(' ', '_', $catvalue);
        //require_once(__DIR__ . '/../vendor/solr/Service.php');

        //$solr = new Apache_Solr_Service( 'www2.lth.se', '8080', '/solr/personal' );
	$scheme = 'http';
	$solr = t3lib_div::makeInstance('tx_solr_ConnectionManager')->getConnection($confArr['solrServer'], $confArr['solrPort'], $confArr['solrPath'], $scheme);

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
                //$catvalueArray = explode('_', $catvalue);
		$sucker = '';
                if($checked==='true') {
                    if(is_array($doc['staff_custom_category_facet_'.$sys_language.'_'.$pluginid.'_ss'])) {
                        $doc['staff_custom_category_facet_'.$sys_language.'_'.$pluginid.'_ss'][] = $catvalue;
                    } else if($doc['staff_custom_category_facet_'.$sys_language.'_'.$pluginid.'_ss']) {
			$doc['staff_custom_category_facet_'.$sys_language.'_'.$pluginid.'_ss'] = array($doc['staff_custom_category_facet_'.$sys_language.'_'.$pluginid.'_ss']);
			$doc['staff_custom_category_facet_'.$sys_language.'_'.$pluginid.'_ss'][] = $catvalue;
                    } else {
                        $doc['staff_custom_category_facet_'.$sys_language.'_'.$pluginid.'_ss'] = $catvalue;
                    }
                    if($doc['staff_custom_category_sort_'.$sys_language.'_'.$pluginid.'_s']) {
                        if($catvalue < $doc['staff_custom_category_sort_'.$sys_language.'_'.$pluginid.'_s']) {
                            $doc['staff_custom_category_sort_'.$sys_language.'_'.$pluginid.'_s'] = $catvalue;
                        } 
                    } 
                } else {
                    if(is_array($doc['staff_custom_category_facet_'.$sys_language.'_'.$pluginid.'_ss'])) {
                        unset($doc['staff_custom_category_facet_'.$sys_language.'_'.$pluginid.'_ss'][array_search($catvalue,$doc['staff_custom_category_facet_'.$sys_language.'_'.$pluginid.'_ss'])]);
			//$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_devlog', array('msg' => , 'crdate' => time()));

                    } else {
                        unset($doc['staff_custom_category_facet_'.$sys_language.'_'.$pluginid.'_ss']);
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
                $response = 'getFeUsers done!'.$sucker;
            }
            catch ( Exception $e ) {
                $response = $e->getMessage();
            }
        } else {
            $response = "Kein Eintrag gefunden";
        }
        
        return $response;
    }
    
    function updateImage($imageId, $username, $checked, $sys_language, $pluginId, $i)
    {
	$imageIdArray = explode('_',$imageId);
	$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('file_name,file_path', 'tx_dam', 'uid='.intval($imageIdArray[2]), '', '', '') or die('149; '.mysql_error());
	$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
	$file_name = $row['file_name'];
	$file_path = $row['file_path'];
	$GLOBALS['TYPO3_DB']->sql_free_result($res);
	
	$confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['institutioner']);
    
	if (!$confArr['solrServer']) {
	    return 'Ange Solr-server';
	}

	if (!$confArr['solrPort']) {
	    return 'Ange Solr-port';
	}

	if (!$confArr['solrPath']) {
	    return 'Ange Solr-path';
	}
	//$catvalue = str_replace(' ', '_', $catvalue);
        //require_once(__DIR__ . '/../vendor/solr/Service.php');

        //$solr = new Apache_Solr_Service( 'www2.lth.se', '8080', '/solr/personal' );
	$scheme = 'http';
	$solr = t3lib_div::makeInstance('tx_solr_ConnectionManager')->getConnection($confArr['solrServer'], $confArr['solrPort'], $confArr['solrPath'], $scheme);

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
            $response = '180:' . $e->getMessage();
            //exit();
        }
        if(isset($response->response->docs[0])) {
 
            //$docs = array();
            foreach($response->response->docs as $document) {
                $doc = array();
                foreach($document as $field => $value) {
                    $doc[$field] = $value;
                }

                $doc['staff_custom_image_'.$pluginId . '_s'] = $file_path.$file_name;
                
                unset($doc['_version_']);
                unset($doc['alphaNameSort']);
            }

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
        

            try {
                $solr->addDocument($part);
                $solr->commit();
                $solr->optimize();
                $response = 'updateImage done!';
            }
            catch ( Exception $e ) {
                $response = $e->getMessage();
            }
        } else {
            $response = "Kein Eintrag gefunden";
        }
	return $response;
    }
    
    function updateText($strSave, $username, $checked, $sys_language, $pluginId, $i)
    {
	$confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['institutioner']);
    
	if (!$confArr['solrServer']) {
	    return 'Ange Solr-server';
	}

	if (!$confArr['solrPort']) {
	    return 'Ange Solr-port';
	}

	if (!$confArr['solrPath']) {
	    return 'Ange Solr-path';
	}

	$scheme = 'http';
	
	$solr = t3lib_div::makeInstance('tx_solr_ConnectionManager')->getConnection($confArr['solrServer'], $confArr['solrPort'], $confArr['solrPath'], $scheme);

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
            return '180:' . $e->getMessage();
            exit();
        }
        
        if(isset($response->response->docs[0])) {
 
            //$docs = array();
            foreach($response->response->docs as $document) {
                $doc = array();
                foreach($document as $field => $value) {
                    $doc[$field] = $value;
                }

                $doc['staff_custom_text_'.$pluginId . '_s'] = $strSave;
                
                unset($doc['_version_']);
                unset($doc['alphaNameSort']);
            }

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

            try {
                $solr->addDocument($part);
                $solr->commit();
                $solr->optimize();
                $response = 'updateText done!';
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