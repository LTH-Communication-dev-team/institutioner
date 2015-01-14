<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class user_sampleflex_addFieldsToFlexForm {
    
    
    function getSolrData ($config) 
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
    
        $addpeopleArray = array();
        $addpeople = '';

        $pi_flexform = $config['row']['pi_flexform'];
        $pluginId = $config['row']['uid'];
        $xml = simplexml_load_string($pi_flexform);
        $test = $xml->data->sheet[0]->language;
        foreach ($test->field as $n) {
            foreach($n->attributes() as $name => $val) {
		if ($val == 'customcategories') {
                    $customcategories = $n->value;
                } else if($val == 'scope') {
                    $scope = $n->value;
                } else if($val == 'addpeople') {
                    $addpeople = $n->value;
                } else if($val == 'removepeople') {
                    $removepeople = $n->value;
                }
            }
        }

	if(trim($scope) != '') {
	    $scope = str_replace(' ', '', $scope);
	    $scope = str_replace(',', "\n", $scope);
	    $scopeArray = explode("\n", $scope);
	} else if(trim($addpeople)) {
	    //
	} else {
	    return 'You have to save a selection of departments/people!';
	}
	
        if($customcategories) {
            $customcategoriesArray = explode("\n", $customcategories);
        } else {
            return 'You have to save custom categories!';
        }
        
        $queryFilterString = '';
        $offset=null;
        $limit=null;
        $okString = '';
        if($offset=='null' || $offset=='') $offset=0;
        if($limit=='null' || $limit=='') $limit=700;

        //require __DIR__ . '/vendor/solr/Service.php';

        //$solr = new Apache_Solr_Service( 'www2.lth.se', '8080', '/solr/personal' );
	$scheme = 'http';
        $solr = t3lib_div::makeInstance('tx_solr_ConnectionManager')->getConnection($confArr['solrServer'], $confArr['solrPort'], $confArr['solrPath'], $scheme);


        if ( ! $solr->ping() ) {
            $content = 'Solr service not responding.';
            exit;
        }
	
	if($scopeArray) {
	    $i = 0;
	    foreach($scopeArray as $key => $value) {
		if($queries or i==0) {
		    $queries = "group_lucat_id:$value";
		} else {
		    $queries .= " $value";
		}
		$i++;
	    }
	}
        
        if(trim($addpeople)) {
            $addpeople = str_replace(' ', '', $addpeople);
            $addpeople = str_replace(',', "\n", $addpeople);
            $addpeople = str_replace(':', '', $addpeople);
            $addpeopleArray = explode("\n",$addpeople);
            foreach($addpeopleArray as $value) {
                $queries .= " id:$value";
            }
        }
	
	if(trim($removepeople)) {
	    $removepeople = str_replace(' ', '', $removepeople);
	    $removepeople = str_replace(',', "\n", $removepeople);
	    $removepeople = str_replace(':', '', $removepeople);
	    $removepeopleArray = explode("\n",$removepeople);
	    foreach($removepeopleArray as $value) {
		$queries .= " !id:$value";
	    }
	}

        $p = array(
            'sort' => 'alphaNameSort ASC'
        );

	return array($solr->search( $queries, $offset, $limit, $p ),$customcategoriesArray,$pluginId);
    }
    
    function addCustomCategories ($config)
    {
	//print_r($config);
	$sys_language_uid = $config['row']['sys_language_uid'];
        if($sys_language_uid==0) {
            $sys_language = 'sv';
        } else {
            $sys_language = 'en';
        }
	
	$allResponse = $this->getSolrData($config);
	$response = $allResponse[0];
	$customcategoriesArray = $allResponse[1];
	$pluginId = $allResponse[2];
	
	$content = "<script language=\"javascript\">
	    
	var myVar=setInterval(function () {myTimer()}, 1000);
	function myTimer() {
	    Ext.each(Ext.query('input.not_saved'), function (el) {
	    //console.log(el.getValue());
		if(el.getValue()!='' && Ext.get('temp_'+el.id).getValue() != el.getValue()) {
		    strSave = el.getValue();
		    userName = Ext.get('user_'+el.id).getValue();
		    Ext.get(el).removeClass('not_saved');
		    updateIndex('updateImage',strSave,'',userName);
		    Ext.get('temp_'+el.id).set({'value': strSave});

		    Ext.get(el).addClass('saved');
		}
	    }, this);
	}

	function updateIndex(action,catvalue,checked,username,sys_language) {
	    //console.log(action);
	    Ext.Ajax.request({
		url: 'ajax.php?ajaxID=institutioner::ajaxControl&action='+action+'&catvalue='+catvalue+'&username='+username+'&checked='+checked+'&sys_language='+sys_language+'&pluginid=$pluginId',
		success: function(response, opts) {
		    //var obj = Ext.decode(response.responseText);
		    //console.dir(obj);
		    //console.log(response.responseText);
		    if(action=='updateText') {
			//alert(response.responseText);
		    }
		    console.log(response.responseText);
		},
		failure: function(response, opts) {
		   console.log('server-side failure with status code ' + response.status);
		}
	    });
	}
	
	function addTextarea(tdId,editId,username,pluginId)
	{
	    var strTextarea = '<textarea id=\"'+tdId+'_textarea\">'+Ext.get('temp_'+tdId).getValue()+ '</textarea>';
	    strEdit = '<a href=\"#\" onclick=\"removeTextarea(\''+tdId+'\',\''+editId+'\',\''+username+'\','+pluginId+');return false;\">Cancel</a> ';
	    strEdit += '<a href=\"#\" onclick=\"saveTextarea(\''+tdId+'\',\''+editId+'\',\''+username+'\','+pluginId+');return false;\">Save</a>';
	    document.getElementById(tdId).innerHTML = strTextarea;
	    document.getElementById(editId).innerHTML = strEdit;
	}
	
	function removeTextarea(tdId,editId,username,pluginId)
	{
	    var strText = Ext.get('temp_'+tdId).getValue();
	    var strEdit = '<a href=\"#\" onclick=\"addTextarea(\''+tdId+'\',\''+editId+'\',\''+username+'\','+pluginId+');return false;\">Edit</a>';
	    document.getElementById(tdId).innerHTML = strText;
	    document.getElementById(editId).innerHTML = strEdit;
	}
	
	function saveTextarea(tdId,editId,username,pluginId)
	{
	    strSave = Ext.get(tdId+'_textarea').getValue();
	    updateIndex('updateText',strSave,'',username);
	    Ext.get('temp_'+tdId).set({'value': strSave});
	    removeTextarea(tdId,editId,username,pluginId);
	}
        </script>";
	
	$content .= "<table style=\"padding:10px;width:700px;\" cellspacing=\"5\"><tbody>";

	$tmpCatArray = array();
	
        $numberOfHits = $response->response->numFound;
        $content .= "<tr><td colspan=\"" . (count($customcategoriesArray) + 1) . "\">$numberOfHits</td></tr>";
        if ( $response->getHttpStatus() == 200 ) { 
            if ( $response->response->numFound > 0 ) {
                $i=0;
                
                //print_r($response);
                
                foreach ( $response->response->docs as $doc) {
                    $content .= "<tr><td>$doc->name ($doc->id)</td>";
		    $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_devlog', array('msg' => $doc->{"staff_custom_category_facet_".$sys_language."_$pluginId"."_ss"}, 'crdate' => time()));
		    //Remove old categories
		    if(is_array($doc->{"staff_custom_category_facet_".$sys_language."_$pluginId"."_ss"})) {
			foreach($doc->{"staff_custom_category_facet_".$sys_language."_$pluginId"."_ss"} as $key9 => $value9) {
			    $value9Array = explode('_', $value9);
			    if(!in_array($value9Array[1], $customcategoriesArray)) {
				$this->deleteCategory($doc->id, "staff_custom_category_facet_".$sys_language."_$pluginId"."_ss", $value9);
			    }
			}
		    } else {
			$value9Array = explode('_', $doc->{"staff_custom_category_facet_".$sys_language."_$pluginId"."_ss"});
			if(!in_array($value9Array[1], $customcategoriesArray)) {
				$this->deleteCategory($doc->id, "staff_custom_category_facet_".$sys_language."_$pluginId"."_ss", $doc->{"staff_custom_category_facet_".$sys_language."_$pluginId"."_ss"});
			    }
		    }
		    
                    $ii=0;
                    foreach($customcategoriesArray as $key => $value) {
                        $okString = '';
			if(is_array($doc->{"staff_custom_category_facet_".$sys_language."_$pluginId"."_ss"})) {
			    if(in_array($ii.'_'.$value, str_replace('+',' ',$doc->{"staff_custom_category_facet_".$sys_language."_$pluginId"."_ss"}))) {
				$okString = ' checked="checked"';
			    }
			} else {
			    if($ii.'_'.$value == str_replace('+',' ',$doc->{"staff_custom_category_facet_".$sys_language."_$pluginId"."_ss"})) {
				$okString = ' checked="checked"';
			    }
			}
                        $content .= "<td><input type=\"checkbox\" value=\"$ii"."_".$value."\" onclick=\"updateIndex('updateIndex',this.value,this.checked,'$doc->id','$sys_language');\"$okString />$value</td>";
                        $ii++;
                    }
                    if(!$okString) $okString = ' checked="checked"';
                    $content .= "</tr>";
                    $i++;
                }
            }
        }
        else {
            $content = '<tr><td>' . $response->getHttpStatusMessage() . '</td></tr>';
        }
    
        $content .= "</tbody></table>";
        return $content;
    }
    
    function addCustomImages($config)
    {
	$tt_contentUid = $config['row']['uid'];
	$content = "<table style=\"padding:10px;width:700px;\" cellspacing=\"5\"><tbody>";
	
	$allResponse = $this->getSolrData($config);
	$response = $allResponse[0];
	$pluginId = $allResponse[2];

        $numberOfHits = $response->response->numFound;
        $content .= "<tr><td colspan=\"" . (count($customcategoriesArray) + 1) . "\">$numberOfHits</td></tr>";
        if ( $response->getHttpStatus() == 200 ) { 
            if ( $response->response->numFound > 0 ) {
                $i=0;
                
                //print_r($response);
                
                foreach ( $response->response->docs as $doc) {
		    $image = '';
		    if($doc->{'staff_custom_image_'.$pluginId.'_s'}) {
			$imageArray = explode('/',$doc->{'staff_custom_image_'.$pluginId.'_s'});
			$image = end($imageArray);
		    }
		    
                    $content .= "<tr><td>$doc->name  ($doc->id)</td>";
		    $content .= "<td>";
		    $content .= "<input id=\"user_image_$i\" type=\"hidden\" value=\"" . $doc->id . "\">";
		    $content .= "<input id=\"temp_image_$i\" type=\"hidden\" value=\"$image\"/>";
		    $content .= "<input id=\"image_$i\" class=\"not_saved\" type=\"hidden\" name=\"data[tt_content][$tt_contentUid][pi_flexform][data][addCustomImages][lDEF][image_$i][vDEF]\" value=\"$image\" />";
                    $content .= "<select class=\"addCustomImages\" name=\"data[tt_content][$tt_contentUid][pi_flexform][data][addCustomImages][lDEF][image_$i][vDEF]_list\"><option value=\"$image\">$image</option></select>";
                    //$content .= "<a href=\"#\" onclick=\"updateIndex('updateImage','','','$doc->id','');\">$image";
		    $content .= "<a href=\"#\" onclick=\"setFormValueOpenBrowser('db','data[tt_content][$tt_contentUid][pi_flexform][data][addCustomImages][lDEF][image_$i][vDEF]|||tx_dam|'); return false;\"><span title=\"Browse for records\" class=\"t3-icon t3-icon-actions t3-icon-actions-insert t3-icon-insert-record\">&nbsp;</span></a>";
		    $content .= "</td>";
                    $content .= "</tr>";
                    $i++;
                }
            }
        }
        else {
            $content = '<tr><td>' . $response->getHttpStatusMessage() . '</td></tr>';
        }
    
        $content .= "</tbody></table>";
        return $content;
    }
    
    function addCustomTexts($config)
    {
	$tt_contentUid = $config['row']['uid'];
	$content = "<table style=\"padding:10px;width:900px;\" cellspacing=\"5\"><tbody>";
	
	$allResponse = $this->getSolrData($config);
	$response = $allResponse[0];
	$pluginId = $allResponse[2];

        $numberOfHits = $response->response->numFound;
        $content .= "<tr><td colspan=\"3\">$numberOfHits</td></tr>";
        if ( $response->getHttpStatus() == 200 ) { 
            if ( $response->response->numFound > 0 ) {
                $i=0;
                
                //print_r($response);
                
                foreach ( $response->response->docs as $doc) {
                    $content .= "<tr><td style=\"width:200px;\" >$doc->name  ($doc->id)<input id=\"temp_user_text_$i\" type=\"hidden\" value=\"" . $doc->{'staff_custom_text_'.$pluginId.'_s'} . "\"/></td>";
		    $content .= "<td style=\"width:500px;\" id=\"user_text_$i\">";
		    $content .= $doc->{'staff_custom_text_'.$pluginId.'_s'} . "</td>";
		    $content .= "<td style=\"width:200px;\" id=\"user_edit_$i\">";
		    $content .= "<a href=\"#\" onclick=\"addTextarea('user_text_$i','user_edit_$i','" . $doc->id . "','$pluginId');return false;\">Edit</a>";
		    $content .= "</td>";
                    $content .= "</tr>";
                    $i++;
                }
            }
        }
        else {
            $content = '<tr><td>' . $response->getHttpStatusMessage() . '</td></tr>';
        }
    
        $content .= "</tbody></table>";
        return $content;
	
	
    }
    
    function manageCategories($config)
    {
	$sys_language_uid = $config['row']['sys_language_uid'];
        if($sys_language_uid==0) {
            $sys_language = 'sv';
        } else {
            $sys_language = 'en';
        }
	
	$oldCat = '';
	$cat = '';
	$allResponse = $this->getSolrData($config);
	$response = $allResponse[0];
	$pluginId = $allResponse[2];

        $numberOfHits = $response->response->numFound;
	$content = "<table style=\"padding:10px;\" cellspacing=\"5\"><tbody>";

        $content .= "<tr><td colspan=\"" . (count($customcategoriesArray) + 1) . "\">$numberOfHits</td></tr>";
        if ( $response->getHttpStatus() == 200 ) { 
            if ( $response->response->numFound > 0 ) {
                $i=0;
                
                //print_r($response);
                
                foreach ( $response->response->docs as $doc) {
		    $cat = $doc->{'staff_custom_category_facet_'.$sys_language};
		    
		    if(is_array($cat)) {
			foreach($cat as $key => $value) {
			    if($value and $value!=$oldCat) {
				$content .= "<tr>";
				$content .= "<td>";
				$content .= $value;
				$content .= "</td>";
				$content .= "<td>";
				$content .= "<a href=\"#\" onclick=\"updateIndex('deleteIndex','$value','staff_custom_category_facet_$sys_language" . "_".$pluginId."_ss','','');\">Delete</a>";
				$content .= "</td>";
				$content .= "</tr>";
			    }
			    $oldCat = $value;
			}
		    } else {
			if($cat and $cat!=$oldCat) {
			    $content .= "<tr>";
			    $content .= "<td>";
			    $content .= $cat;
			    $content .= "</td>";
			    $content .= "<td>";
			    $content .= "<a href=\"#\" onclick=\"updateIndex('deleteIndex','$cat','staff_custom_category_facet_'.$sys_language,'','');\">Delete</a>";
			    $content .= "</td>";
			    $content .= "</tr>";
			}
			$oldCat = $cat;
		    }
                }
            }
        }
        else {
            $content = '<tr><td>' . $response->getHttpStatusMessage() . '</td></tr>';
        }
    
        $content .= "</tbody></table>";
        return $content;
    }
    
    function deleteCategory($username, $cat, $val)
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
            return '437:' . $e->getMessage();
            exit();
        }
        
        if(isset($response->response->docs[0])) {
 
            //$docs = array();
            foreach($response->response->docs as $document) {
                $doc = array();
                foreach($document as $field => $value) {
                    $doc[$field] = $value;
                }
		if(is_array($doc[$cat])) {
		    $key = array_search($val,$doc[$cat]);
		} else {
		    $key = 0;
		}
		unset($doc[$cat[$key]]);

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
                $response = 'delete category done!';

            }
            catch ( Exception $e ) {
                $response = $e->getMessage();
            }
        } else {
            $response = "Kein Eintrag gefunden";
        }

	return $response;
    
    }
	    
    function objectToArray($d) {
	if (is_object($d)) {
	    // Gets the properties of the given object
	    // with get_object_vars function
	    $d = get_object_vars($d);
	}

	if (is_array($d)) {
	    /*
	    * Return array converted to object
	    * Using __FUNCTION__ (Magic constant)
	    * for recursive call
	    */
	    return array_map(array($this, 'objectToArray'), $d);
	//$this->d = get_object_vars($d);
	}
	else {
	    // Return array
	    return $d;
	}
    }
    /*function processDatamap_afterAllOperations($pObj)
    {
        print_r($pObj);
    }
     
     */
}