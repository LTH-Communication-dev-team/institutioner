<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class user_sampleflex_addFieldsToFlexForm {
    function addCustomCategories ($config) {
	//WHAT!
        $sys_language_uid = $config['row']['sys_language_uid'];
        if($sys_language_uid==0) {
            $sys_language = 'sv';
        } else {
            $sys_language = 'en';
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
                    $addpeople = $n->value;
                }
            }
        }
        
	if($scope) {
	    $scope = str_replace(' ', '', $scope);
	    $scope = str_replace(',', "\n", $scope);
	    $scopeArray = explode("\n", $scope);
	} else {
	    return 'You have to save a selection of departments/people!';
	}
        if($customcategories) {
            $customcategoriesArray = explode("\n", $customcategories);
        } else {
            return 'You have to save custom categories!';
        }

        $content = "<script language=\"javascript\">
        function updateIndex(catvalue,checked,username,sys_language) {
        Ext.Ajax.request({
        url: 'ajax.php?ajaxID=institutioner::ajaxControl&catvalue='+catvalue+'&action=updateindex&username='+username+'&checked='+checked+'&sys_language='+sys_language+'&pluginid=$pluginId',
        success: function(response, opts) {
        var obj = Ext.decode(response.responseText);
            console.dir(obj);
        },
        failure: function(response, opts) {
           console.log('server-side failure with status code ' + response.status);
        }
        });
        }
        </script><table style=\"padding:10px;\" cellspacing=\"5\"><tbody>";
   
        
        $queryFilterString = '';
        $offset=null;
        $limit=null;
        $okString = '';
        if($offset=='null' || $offset=='') $offset=0;
        if($limit=='null' || $limit=='') $limit=700;

        require __DIR__ . '/vendor/solr/Service.php';

        $solr = new Apache_Solr_Service( 'www2.lth.se', '8080', '/solr/personal' );

        if ( ! $solr->ping() ) {
            $content = 'Solr service not responding.';
            exit;
        }

	$i = 0;
        foreach($scopeArray as $key => $value) {
	    if($queries or i==0) {
		$queries = "group_lucat_id:$value";
	    } else {
		$queries .= " $value";
	    }
	    $i++;
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

        $response = $solr->search( $queries, $offset, $limit, $p );
        $numberOfHits = $response->response->numFound;
        $content .= "<tr>$myscope<td colspan=\"" . (count($customcategoriesArray) + 1) . "\">$numberOfHits</td></tr>";
        if ( $response->getHttpStatus() == 200 ) { 
            if ( $response->response->numFound > 0 ) {
                $i=0;
                
                //print_r($response);
                
                foreach ( $response->response->docs as $doc) {
                    $content .= "<tr><td>$doc->name</td>";
                    $ii=0;
                    foreach($customcategoriesArray as $key => $value) {
                        $value = $value;
                        $okString = '';

                            //if($pluginId . "_" . $value == $doc->staff_custom_category_sv) {
                            if($value == $doc->staff_custom_category) {
                                $okString = ' checked="checked"';
                            }
                        $content .= "<td><input type=\"checkbox\" name=\"staff_custom_category_sort[$i]\" value=\"$ii"."_".$value."\" onclick=\"updateIndex(this.value,this.checked,'$doc->id','$sys_language');\"$okString />$value</td>";
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