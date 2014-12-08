<?php

// Exit, if script is called directly (must be included via eID in index_ts.php)
if (!defined ('PATH_typo3conf')) die ('Could not access this script directly!');

if (t3lib_extMgm::isLoaded('fpdf'))    {
    require(t3lib_extMgm::extPath('fpdf').'class.tx_fpdf.php');
}
require_once(__DIR__ . '/../vendor/solr/Service.php');
$id = isset($HTTP_GET_VARS['id'])?$HTTP_GET_VARS['id']:0;

initTSFE($id);
/*require_once(PATH_tslib.'class.tslib_fe.php');
require_once(PATH_t3lib.'class.t3lib_page.php');
require_once(PATH_t3lib.'class.t3lib_tstemplate.php');
require_once(PATH_t3lib.'class.t3lib_cs.php');
require_once(PATH_t3lib.'class.t3lib_userauth.php');
require_once(PATH_tslib.'class.tslib_feuserauth.php');
require_once(PATH_tslib.'class.tslib_content.php');

//$TSFEclassName = t3lib_div::makeInstance('tslib_fe');


// Connect to database:
tslib_eidtools::connectDB();

//$GLOBALS['TSFE'] = new $TSFEclassName($TYPO3_CONF_VARS, $id, '0', 1, '','','','');
$GLOBALS['TSFE'] = t3lib_div::makeInstance('tslib_fe', $TYPO3_CONF_VARS, $id, 0, true);

$GLOBALS['TSFE']->initFEuser();
$GLOBALS['TSFE']->fetch_the_id();
$GLOBALS['TSFE']->getPageAndRootline();
$GLOBALS['TSFE']->initTemplate();
$GLOBALS['TSFE']->tmpl->getFileName_backPath = PATH_site;
$GLOBALS['TSFE']->forceTemplateParsing = 1;
$GLOBALS['TSFE']->getConfigArray();
TSpagegen::pagegenInit();

// Initialize FE user object:
tslib_eidtools::initFeUser();
//$usergroup = $feUserObj->user['usergroup'];
*/
$query = htmlspecialchars(t3lib_div::_GP("query"));
$scope = htmlspecialchars(t3lib_div::_GP("scope"));
$action = htmlspecialchars(t3lib_div::_GP("action"));
$lastaction = htmlspecialchars(t3lib_div::_GP("lastaction"));
$lang = htmlspecialchars(t3lib_div::_GP("lang"));
$hide_search = htmlspecialchars(t3lib_div::_GP("hide_search"));
$html_template = htmlspecialchars(t3lib_div::_GP("html_template"));
$imagefolder = htmlspecialchars(t3lib_div::_GP("imagefolder"));
$addpeople = htmlspecialchars(t3lib_div::_GP("addpeople"));
$removepeople = htmlspecialchars(t3lib_div::_GP("removepeople"));
$categories = htmlspecialchars(t3lib_div::_GP("categories"));
$queryfilter = htmlspecialchars(t3lib_div::_GP("queryfilter"));
$issiteadmin = htmlspecialchars(t3lib_div::_GP("issiteadmin"));
$rvalue = htmlspecialchars(t3lib_div::_GP("rvalue"));
$cvalue = htmlspecialchars(t3lib_div::_GP("cvalue"));
$lextra = htmlspecialchars(t3lib_div::_GP("lextra"));
$pluginid = htmlspecialchars(t3lib_div::_GP("pluginid"));
$sid = htmlspecialchars(t3lib_div::_GP("sid"));

$imageSokvag = "/fileadmin/user_portraits/";

switch($action) {
    case "listaInstitutioner":
            echo listaInstitutioner($scope, $action, $query, $lang, $sorting, $lastaction, $unfold, $hide_search);
            break;
    case "listaPersoner":
    case "listaPersoner_no1":
            echo json_encode(listaPersoner($scope,$action,$query,$imageSokvag,$lang,$hide_search,$html_template,$imagefolder,$addpeople,$removepeople,$categories,$queryfilter,$issiteadmin,$pluginid));
            break;	
    case "flashMap":
            echo flashMap($scope, $query, $lang);
            break;
    case "exportChoice":
            echo json_encode(exportChoice($scope,$action,$query,$imageSokvag,$lang,$hide_search,$html_template,$imagefolder,$addpeople,$removepeople,$categories,$queryfilter,$issiteadmin,$pluginid));
            break;		
    case "exportDo":
            echo exportDo($scope,$action,$query,$imageSokvag,$lang,$hide_search,$html_template,$imagefolder,$addpeople,$removepeople,$categories,$queryfilter,$rvalue,$cvalue,$lextra);
            break;		
    case "telephoneList":
            echo telephoneList($scope,$action,$query,$imageSokvag,$lang,$hide_search,$html_template,$imagefolder,$addpeople,$removepeople,$categories,$queryfilter);
            break;
    case "editForm":
            echo json_encode(editForm($scope,$action,$lang,$imagefolder,$pluginid,$issiteadmin));
            break;
     case "saveEditForm":
            echo json_encode(saveEditForm($action,$query));
            break;       
}

function initTSFE($pageUid=1)
{
    require_once(PATH_tslib.'class.tslib_fe.php');
    require_once(PATH_t3lib.'class.t3lib_userauth.php');
    require_once(PATH_tslib.'class.tslib_feuserauth.php');
    require_once(PATH_t3lib.'class.t3lib_cs.php');
    require_once(PATH_tslib.'class.tslib_content.php');
    require_once(PATH_t3lib.'class.t3lib_tstemplate.php');
    require_once(PATH_t3lib.'class.t3lib_page.php');

    //$TSFEclassName = t3lib_div::makeInstance('tslib_fe');

    if (!is_object($GLOBALS['TT'])) {
        $GLOBALS['TT'] = new t3lib_timeTrack;
        $GLOBALS['TT']->start();
    }

    // Create the TSFE class.
    //$GLOBALS['TSFE'] = new $TSFEclassName($GLOBALS['TYPO3_CONF_VARS'],$pageUid,'0',1,'','','','');
    $GLOBALS['TSFE'] = t3lib_div::makeInstance('tslib_fe');
    $GLOBALS['TSFE']->connectToDB();
    $GLOBALS['TSFE']->initFEuser();
    $GLOBALS['TSFE']->fetch_the_id();
    $GLOBALS['TSFE']->getPageAndRootline();
    $GLOBALS['TSFE']->initTemplate();
    $GLOBALS['TSFE']->tmpl->getFileName_backPath = PATH_site;
    $GLOBALS['TSFE']->forceTemplateParsing = 1;
    $GLOBALS['TSFE']->getConfigArray();
}

function getSolrData($scope,$action,$query,$imageSokvag,$lang,$hide_search,$html_template,$imagefolder,$addpeople,$removepeople,$categories,$queryfilter,$pluginid)
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
	

    $content = '';
    $facets = '';
    $queryFilterString = '';
    $numberOfHits;
    $searchbox='';
    $export='';

    if($offset=='null' || $offset=='') $offset=0;
    if($limit=='null' || $limit=='') $limit=2000;

    //Language
    if($lang) {
        $langArray = getLang($lang);
    } else {
        $lang = 'sv';
    }

    //Sorting
    if($categories=='custom_category') {
        $sorting = 'staff_custom_category_sort_'.$lang.'_'.$pluginid.'_s' . ' ASC, alphaNameSort ASC';
    } else if($queryfilter and $categories=='standard_category') {
        $sorting = 'staff_standard_category_' . $lang . ' ASC, alphaNameSort ASC';
    } else {
        $sorting = 'alphaNameSort ASC';
    }
    //$solr = new Apache_Solr_Service( 'www2.lth.se', '8080', '/solr/personal' );
    
    $solr = new Apache_Solr_Service( $confArr['solrServer'], $confArr['solrPort'], $confArr['solrPath'] );

    if ( ! $solr->ping() ) {
        $content = 'Solr service not responding.';
        exit;
    }

    $queries = '';

    if(trim($scope)) {
        $tmpString = '';
        $scope = str_replace(' ', '', $scope);
        $scope = str_replace(':', '', $scope);
        $scopeArray = explode(',',$scope);
        foreach($scopeArray as $value) {
            $queries .= " group_lucat_id:$value";
        }
    }

    if(trim($addpeople)) {
        $addpeople = str_replace(' ', '', $addpeople);
        $addpeople = str_replace(':', '', $addpeople);
        $addpeopleArray = explode(',',$addpeople);
        foreach($addpeopleArray as $value) {
            $queries .= " id:$value";
        }
    }

    if(trim($removepeople)) {
        $removepeople = str_replace(' ', '', $removepeople);
        $removepeople = str_replace(':', '', $removepeople);
        $removepeopleArray = explode(',',$removepeople);
        foreach($removepeopleArray as $value) {
            $queries .= " !id:$value";
        }
    }

    if(trim($query)) {
        $queries = "($queries) AND (first_name:$query* OR last_name:$query*)";
    }

    //Queryfilter
    if($queryfilter) {
        $queryfilterArray = json_decode(html_entity_decode($queryfilter));
        //$queryfilterArray = explode(',',urldecode($queryfilter));
        foreach($queryfilterArray as $key=>$value) {

            if($value!='null') {
                $queryfiltertmpArray = explode('.',$value);
                if($queryFilterString) {
                    $queryFilterString .= ' OR ';
                }
                $queryFilterString .= "$queryfiltertmpArray[0]:" . $queryfiltertmpArray[1];
            }
        }
    }
    
    $facetField = null;
    if($categories!='no_categories') {
	$facetField = array('staff_' . $categories . '_facet_'.$lang);
    }

    $p = array(
        'fq' => $queryFilterString,
        'sort' => $sorting,
        'facet' => 'true',
        'facet.field' => $facetField,
        'facet.mincount' => 1
    );

    $response = $solr->search( htmlspecialchars($queries), $offset, $limit, $p );

    return $response;
}
    
function listaPersoner($scope,$action,$query,$imageSokvag,$lang,$hide_search,$html_template,$imagefolder,$addpeople,$removepeople,$categories,$queryfilter,$issiteadmin,$pluginid)
{
        
    $returnArray = array();
    
    if(!$imagefolder) {
	$imagefolder = 'uploads/tx_srfeuserregister/';
    }
    
    $lang = str_replace('se','sv',$lang);

    if($scope or $query) {
        $fe_user = $GLOBALS['TSFE']->fe_user->user;
        
        $response = getSolrData($scope,$action,$query,$imageSokvag,$lang,$hide_search,$html_template,$imagefolder,$addpeople,$removepeople,$categories,$queryfilter,$pluginid);

        //CONTENT*************************************************************************************

        // Get the template
        $cObj = t3lib_div::makeInstance('tslib_cObj');
        $templateHtml = $cObj->fileResource("fileadmin/templates/institutioner/$html_template");
        // Extract subparts from the template
        $subpart = $cObj->getSubpart($templateHtml, '###TEMPLATE###');
        $markerArray = array();
        
        $numberOfHits = $response->response->numFound;
        if ( $response->getHttpStatus() == 200 ) { 
            if ( $numberOfHits > 0 ) {
                $facetArray = (array)$response->facet_counts->facet_fields->{'staff_'.$categories.'_facet_'.$lang};
                foreach ( $response->response->docs as $doc ) {
                    
                    // Fill marker array
                    $markerArray['###NAME###'] = "$doc->first_name $doc->last_name";
                    $markerArray['###FIRST_NAME###'] = $doc->first_name;
                    $markerArray['###LAST_NAME###'] = $doc->last_name;
                    $markerArray['###TITLE###'] = ucfirst($doc->title);
                    $markerArray['###TELEPHONE###'] = $doc->telephone;
                    $markerArray['###EMAIL###'] = $doc->email;
                    $markerArray['###SUBJECT###'] = $doc->ou;
                    if($doc->image) {
			$markerArray['###IMAGE###'] = $imagefolder . $doc->image;
		    } else {
			$markerArray['###IMAGE###'] = '/typo3conf/ext/institutioner/graphics/placeholder.gif';
		    }
                    $markerArray['###WWW###'] = $doc->www;
                    $markerArray['###WWWLABEL###'] = $doc->wwwlabel;
                    $markerArray['###DESCRIPTION###'] = $doc->{'description_'.$pluginid.'_'.$lang.'_s'};
		    $markerArray['###WWWLABEL###'] = $doc->wwwlabel;
		    $markerArray['###COMMENTS###'] = $doc->comments;
                    
                    $edit = '';
                    /*if($fe_user['username']===$doc->id) {
                        // or $issiteadmin
                        $edit = "<p><a href=\"#\" onclick=\"lista('$doc->uid','editForm','$query','$lang','$hide_search','$html_template','$imagefolder','$addpeople','$removepeople','$categories','$queryfilter'); return false;\">Edit</a></p>";
                    }*/
                    $markerArray['###EDIT###'] = $edit;
                    
                    // Create the content by replacing the content markers in the template
                    $content .= $cObj->substituteMarkerArray($subpart, $markerArray);
                    
                }
            }
        }
        else {
            $content = '<div>' . $response->getHttpStatusMessage() . '</div>';
        }
        
        //FACETS**************************************************************************************

        //staff_category
        if(($categories=='standard_category' or $categories=='custom_category') and $action == 'listaPersoner_no1') {
            $facets .= "<ul class=\"filterbox\"><li><b>Filter</b></li>";
            $facetArray = array_reverse($facetArray);
            
            foreach($facetArray as $cat=>$count) {
                if($cat) {
		    $facets .= "<li><input type=\"checkbox\" name=\"staff_" . $categories . "_facet_" . "$lang\" value=\"$cat\" onclick=\"changeFilter('$scope','" . str_replace('_no1','',$action) . "','','$lang','$hide_search','$html_template','$imagefolder','$addpeople','$removepeople','$categories');\" /> " . strip_underscore($cat) . " ($count)</li>";
		}
            }
            $facets .= '</ul>';
            //$facets .= "<div id=\"staff_categoryClear\" class=\"clearfilter\" onclick=\"clearFilter('staff_category','$cat');return false;\"></div>";
        }
    } else {
        $returnArray['content'] = 'You have to limit the search!';
    }
    
    /*if(!$fe_user and !$issiteadmin or 1+1==2) {
        $content .= "<p><a href=\"#\" onclick=\"showHideLoginform();return false;\">Login to edit personal information</a></p>";
    }*/
    
    //SEARCHBOX******************************************************************************************
    if(!$hide_search and $action == 'listaPersoner_no1') {
        $searchbox = "<div id=\"tx_institutioner_searchbox_container\" style=\"font-size:0.8em;\">
        <div style=\"margin-top:7px; margin-bottom:10px; \">
        <input type=\"text\" size=\"25\" id=\"tx_institutioner_searchbox\" name=\"tx_institutioner_searchbox\" placeholder=\"Filtrera\" value=\"\" onfocus=\"this.value='';\" onkeyup=\"lista('$scope','" . str_replace('_no1','',$action) . "',this.value,'$lang','$hide_search','$html_template','$imagefolder','$addpeople','$removepeople','$categories','$luppage','$queryfilter');\" title=\"S&ouml;k namn, telefon, epost. Ange minst 2 tecken.\" style=\"margin-top:20px;\" />
        </div>
        </div>";
    }
    
    //EXPORT***********************************************************************************************
    if($action == 'listaPersoner_no1') {
        if($_SERVER['REMOTE_ADDR'] == "127.0.0.1" OR substr($_SERVER['REMOTE_ADDR'], 0, 7) == "130.235" or $_SERVER['REMOTE_ADDR'] == "::1") {
            $export = "<div>";
            $export .= "<select id=\"exportMenu\" onchange=\"exportChoice('$scope', this.options[this.selectedIndex].value, '$query', '', '$lang', '$hide_search','$html_template','$imagefolder','$addpeople','$removepeople','$categories','$queryfilter','$pluginid','$issiteadmin');\" name=\"export\">";
	    //$scope,$action,$query,$imageSokvag,$lang,$hide_search,$html_template,$imagefolder,$addpeople,$removepeople,$categories,$queryfilter,$pluginid,$issiteadmin
            //$scope,$action,$query,$imageSokvag,$lang,$hide_search,$html_template,$imagefolder,$addpeople,$removepeople,$categories,$queryfilter,$pluginid
            $export .= "<option value=\"\"";
            $export .= " selected";
            $export .= ">Export</option>";
            $export .= "<option value=\"telephoneList;$scope\">Telephonelist</option>";
            $export .= "<option value=\"exportChoice\"";
            $export .= ">Chose format</option>";
            $export .= "</select>";
            $export .= "</div>";
        }
    }
        
    $returnArray['facets'] = $facets;
    $returnArray['content'] = $content;
    $returnArray['searchbox'] = $searchbox;
    $returnArray['export'] = $export;
    $returnArray['hits'] = $numberOfHits;
    return $returnArray;
}

function editForm($scope,$action,$lang,$imagefolder,$pluginid,$issiteadmin)
{
    $username = addslashes($GLOBALS['TSFE']->fe_user->user['username']);
    
    if($username) {
    	$res = $GLOBALS["TYPO3_DB"]->exec_SELECTquery("FU.uid AS fu_uid, FU.pid, FU.image, FD.uid AS fd_uid, FD.description", 
            "fe_users FU LEFT JOIN tx_institutioner_feuser_description FD ON FU.username=FD.username  AND FD.pid=" . intval($pluginid) . " AND lang='" . addslashes($lang) . "'",
            "FU.uid = " . intval($scope) . " AND FU.deleted=0", "", "") or die("366; ".mysql_error());
        $row = $GLOBALS["TYPO3_DB"]->sql_fetch_assoc($res);
        $fu_uid = $row["fu_uid"];
        $fd_uid = $row["fd_uid"];
        $pid = $row["pid"];
        $image = $row["image"];
        $description = $row["description"];
    } else {
        return array('content' => 'No current user!');
    }
    
    if($image) {
        if(file_exists($imagefolder.$image)) {
            $displayImage = '<img style="width:auto;height:200px;" src="'.$imagefolder.$image.'" />';
        } else if(file_exists('uploads'.$image)) {
            $displayImage = '<img style="width:auto;height:200px;" src="uploads'.$image.'" />';
        } else {
            $displayImage = '';
        }
    }
   
    if(!$imagefolder) {
        $imagefolder = "uploads/";
    }
    
    $imagefolder = "<input type=\"hidden\" name=\"imagefolder\" id=\"imagefolder\" value=\"$imagefolder\" />";
    $myImage = "<input type=\"hidden\" name=\"image\" id=\"image\" value=\"$image\" />";
    
    $content .= "<form id=\"upload\" onsubmit=\"return false;\" action=\"/typo3conf/ext/institutioner/vendor/mini_ajax_file_upload/upload.php\" method=\"post\" enctype=\"multipart/form-data\">
        $imagefolder
        $myImage
	<fieldset>
	<legend>Fill</legend>
        <div>
            <label for=\"image\">Bild (max ???mb)</label>
            <div id=\"uploadimage\">$displayImage</div>
	</div>
        
        <div id=\"drop\">
            Drop Here

            <a>Browse</a>
            <input type=\"file\" name=\"upl\" multiple />
        </div>

        <ul>
            <!-- The file uploads will be shown here -->
        </ul>

	<div>
            <label for=\"description\">Beskrivning (???tkn)</label>
            <textarea id=\"description\" name=\"description\">$description</textarea>
	</div>
	
	<div>	
            <input type=\"submit\" name=\"submit\" value=\"Save\" onclick=\"saveEditForm('$fu_uid','$pluginid','$lang');return false;\" />
	</div>
        
        <p><a href=\"#\" onclick=\"showHideLoginform(); return false;\">Back</a></p>

	</fieldset>
    </form>";
    
    $returnArray['content'] = $content;
    return $returnArray;
}

function saveEditForm($action,$query)
{
    $username = addslashes($GLOBALS['TSFE']->fe_user->user['username']);
    $content = '';
    $query = str_replace('&quot;','"',$query);
    if (get_magic_quotes_gpc() == 1) {
        $query = stripslashes($query);
    }
    $query = str_replace('\\', '', $query);
    $data = json_decode($query);
    $myData = $data;
        
    if($data->uid) {
        try {
            $updateArray = array('pid' => $data->pid, 'description' => $data->description, 'lang' => $data->lang, 'tstamp' => time());
            $res = $GLOBALS['TYPO3_DB']->exec_UPDATEquery("tx_institutioner_feuser_description", "username='$username'", $updateArray);
        }
        catch(Exception $e) {
            $content = '448:' . $e->getMessage();
        }
    } else {
        try {
            $insertArray = array('pid' => $data->pid, 'username' => $username, 'description' => $data->description, 'lang' => $data->lang, 'tstamp' => time(), 'crdate' => time());
            $res = $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_institutioner_feuser_description', $insertArray);
        }
        catch(Exception $e) {
            $content = '456:' . $e->getMessage();
        }
    }
    
    if($data->image) {
        try {
            $updateArray = array('image' => $data->image, 'tstamp' => time());
            $res = $GLOBALS['TYPO3_DB']->exec_UPDATEquery("fe_users", "uid=".intval($data->uid), $updateArray);
        }
        catch(Exception $e) {
            $content = '466:' . $e->getMessage();
        }
    }
    
    //Solr
    require_once(__DIR__ . '/../vendor/solr/Service.php');
    $solr = new Apache_Solr_Service( 'www2.lth.se', '8080', '/solr/personal' );
    $query = "id:$username";
    $results = false;
    $limit = 1;
    
    try {
        $response = $solr->search($query, 0, $limit);
    }
    catch(Exception $e) {
        $content = '478:' . $e->getMessage();
    }
    
    if(isset($response->response->docs[0])) {
 
        foreach($response->response->docs as $document) {
            $doc = array();
            foreach($document as $field => $value) {
                $doc[$field] = $value;
            }
            $doc['description_'.$data->lang.'_'.$data->pid.'_s'] = $data->description;
            $doc['image'] = $data->image;
        }
        
        unset($doc['_version_']);
        unset($doc['alphaNameSort']);
        
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
        }
        catch ( Exception $e ) {
            $content = $e->getMessage();
        }
    } else {
        $content = "No data!";
    }
    
    if(!$content) {
        $content = 'ok';
    }
    
    $returnArray['content'] = $content;
    return $returnArray;
}

function strip_underscore($cat)
{
    $catArray = explode('_', $cat);
    return urldecode($catArray[1]);
}

function listaInstitutioner($scope, $action, $query, $lang, $sorting, $lastaction, $unfold, $hide_search)
{
	//echo "scope=$scope, action=$action, query=$query, lang=$lang, sorting=$sorting, lastaction=$lastaction";
	
	$query = trim($query);
			
	$letter_sort = "Bokstavsordning";
	$dep_sort = "Institutionsordning";
	
	$button_up = "/typo3conf/ext/institutioner/pi1/graphics/button_up.gif";
	
	switch($sorting) {
		case "letter";
			$sorting = "mysort2";
			$letter_sort = "<b>Bokstavsordning</b>";
			break;
		default:
			$sorting = "mysort1,mysort3,mysort2";
			$dep_sort = "<b>Institutionsordning</b>";
			break;
	}
	
	if($lang) $langArray = getLang($lang);
	//*****************************Status bar begin*********************************************************
	$content .= "<div style=\"margin-left:20px; margin-top:20px; font-weight:bold; clear:both;\">###hits###</div>";
	$content .= "<div class=\"sortrow\">";
	$content .= "<div class=\"tx_institiutioner_sortrow_buttons\">";
	
		//name
	$content .= "<a href=\"#\" onclick=\"lista('$scope', '$action', '$query', '1', '$lang', 'dep', '', '', '$unfold', '$hide_search'); return false;\">$dep_sort</a>";
	$content .= "<img onclick=\"lista('$scope', '$action', '$query', '1', '$lang', 'dep', '', '', '$unfold', '$hide_search'); return false;\" src=\"$button_up\" border=\"0\" class=\"sortbutton\" />";

		//Spacebar
	//$content .= "&nbsp;&nbsp;|&nbsp;&nbsp;";

		//title
	$content .= "<a href=\"#\" onclick=\"lista('$scope', '$action', '$query', '1', '$lang', 'letter', '', '', '$unfold', '$hide_search'); return false;\">$letter_sort</a>";
	$content .= "<img onclick=\"lista('$scope', '$action', '$query', '1', '$lang', 'letter', '', '', '$unfold', '$hide_search'); return false;\" src=\"$button_up\" border=\"0\" class=\"sortbutton\" />";
	$content .= "</div><div>";
	
	if($_SERVER['REMOTE_ADDR'] == "127.0.0.1" OR substr($_SERVER['REMOTE_ADDR'], 0, 7) == "130.235") {
		$content .= "<select onchange=\"lista('$scope', this.value, '$query', '1', '$lang', 'title', '', '$action', '$unfold', '$hide_search'); return false;\" name=\"more_search\" class=\"sortmenu\">";
		$content .= "<option value=\"\"";
		$content .= " selected";
		$content .= ">" . $langArray["more_search"] . "</option>";
		$content .= "<option value=\"listaEndastInstitutioner\"";
		$content .= ">" . $langArray["dep_only"] . "</option>";
		/* $content .= "<option value=\"titleChoice\"";
		$content .= ">" . $langArray["title_search"] . "</option>";	*/	
		$content .= "</select>";
	}
	
	$content .= "&nbsp;&nbsp;&nbsp;";
	
	if($_SERVER['REMOTE_ADDR'] == "127.0.0.1" OR substr($_SERVER['REMOTE_ADDR'], 0, 7) == "130.235") {
		$content .= "<select onchange=\"exportChoice('$scope', this.value, '$query', '1', $lang', '$sorting', '$sortorder', '$action', $unfold','$hide_search','$html_template','$imagefolder','$addpeople','$removepeople','$firstpeople'); return false;\" name=\"export\" class=\"sortmenu\">";
                //                                                  scope, action, query, startrecord, lang, sorting, sortorder, lastaction, unfold, hide_search, html_template, imagefolder,addpeople,removepeople,firstpeople
		$content .= "<option value=\"\"";
		$content .= " selected";
		$content .= ">" . $langArray["export"] . "</option>";
		$content .= $telephoneList;
		$content .= "<option value=\"exportDepChoice\"";
		$content .= ">" . $langArray["chooseFormat"] . "</option>";
		$content .= "</select>";
	}
	
	$content .= "</div>";
	$content .= "</div>";

	//*****************************Status bar ends*********************************************************

	if($scope) $urval = "(I1.lucatid IN($scope) OR I2.lucatid IN($scope)) AND";
	if($query) $urval .= "(I2.title LIKE '%$query%') AND";
	if($action == "listaEndastInstitutioner") {
		$urval .= " I2.uid = I2.iid AND";
		$sorting = "mysort2";
	}
	
	if($lang == "en") {
		$selection = "I1.title_e AS title1, I1.uid AS uid1, I1.lucatid AS lucatid1, I1.webbadress AS webbadress1, I1.webadress_e AS webbadress_e1, I2.title_e AS title2, I2.uid AS uid2, I2.lucatid AS lucatid2, I2.webbadress AS webbadress2, I2.webadress_e AS webbadress_e2, H.title AS hus, H.adress, H.hs, H.husid, REPLACE(I1.title_e, 'Department of ', '') AS mysort1, REPLACE(I2.title_e, 'Department of ', '') AS mysort2, IF(I1.title_e = I2.title_e, 0, 1) AS mysort3";
	} else {
		$selection = "I1.title AS title1, I1.uid AS uid1, I1.lucatid AS lucatid1, I1.webbadress AS webbadress1, I1.webadress_e AS webbadress_e1, I2.title AS title2, I2.title_e AS title2_e, I2.uid AS uid2, I2.lucatid AS lucatid2, I2.webbadress AS webbadress2, I2.webadress_e AS webbadress_e2, H.title AS hus, H.adress, H.hs, H.husid, REPLACE(I1.title, 'Institutionen f�r ', '') AS mysort1, REPLACE(I2.title, 'Institutionen f�r ', '') AS mysort2, IF(I1.title=I2.title, 0, 1) AS mysort3";
	}

	if(!$scope) $dont_show = " I1.dont_show = 0 AND I2.dont_show = 0 AND ";
	
	$res = $GLOBALS["TYPO3_DB"]->exec_SELECTquery($selection, "tx_institutioner_institution I1 INNER JOIN tx_institutioner_institution I2 ON I1.uid=I2.iid LEFT JOIN tx_institutioner_hus H ON I2.hid=H.uid", "$urval $dont_show I1.deleted=0 AND I2.deleted=0", "", $sorting, "") or die("206; ".mysql_error());
	$i = 0;
	while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
		$uid1 = $row["uid1"];
		$uid2 = $row["uid2"];
		$hus = $row["hus"];
		$adress = $row["adress"];
		$lucatid1 = $row["lucatid1"];
		$lucatid2 = $row["lucatid2"];
		$title1 = $row["title1"];
		$title2 = $row["title2"];
		$webbadress1 = $row["webbadress1"];
		$webbadress2 = $row["webbadress2"];
		$webbadress_e1 = $row["webbadress_e1"];
		$webbadress_e2 = $row["webbadress_e2"];		
		$hs = $row["hs"];
		$husid = $row["husid"];
		$maininst = $row["mysort3"];
		
		if($lang == "en" and $webbadress_e1) $webbadress1 = $webbadress_e1;
		if($lang == "en" and $webbadress_e2) $webbadress2 = $webbadress_e2;

		if($uid1 != $uid2) {
			$uid = $uid2;
			$lucatid = $lucatid2;
			$webbadress = $webbadress2;
			$title = $title2;
		} else {
			$uid = $uid1;
			$lucatid = $lucatid1;
			$webbadress = $webbadress1;
			$title = $title1;
		}
		
		$content .= returnDivInst($uid, $title, $hus, $lucatid, $adress, $webbadress, $hs, $husid, $maininst, $lang, $langArray, $unfold, $hide_search);
		
		$old_title = $title1;
		$i++;
	}
	
	//if($i > 1) {
		$content = str_replace("###hits###", $langArray["results"] . ": $i " . $langArray["hits"], $content);
	//}
	
	$GLOBALS["TYPO3_DB"]->sql_free_result($res);
	
	return $content;
	//utf8_encode("select $selection from $urval $dont_show I1.deleted=0 AND I2.deleted=0");
}

function returnDivInst($uid, $title, $hus, $lucatid, $adress, $webbadress, $hs, $husid, $maininst, $lang, $langArray, $unfold, $hide_search) {
	
	$content .= "<div id=\"listitem_$uid\" class=\"institution_listitem_normal\"";
	if($unfold) $content .= "title=\"Klicka f&ouml;r att visa mer information\" onMouseOver=\"listitem_mouseOver('listitem_$uid','$lang');\" onMouseOut=\"listitem_mouseOut('listitem_$uid');\" onClick=\"listitem_click('listitem_$uid','expandInst','$uid','$husid','$maininst','$lang','$unfold');\"";
	$content .= ">";
	
	if(!$maininst) $content .= "<div style=\"height:15px; width:15px; background-color:#000080; margin-left:-19px; margin-right:9px; margin-top:5px; float:left\"></div>";
	
	$content .= "<div style=\"margin:5px;\"><b>$title</b></div>";
	
	$content .= "<div style=\"float:left;width:225px;margin:5px;line-height:1.5em;\" class=\"institution_listitem_link\"><a href=\"$webbadress\">" . $langArray["homepage"] . "</a><br />";
	$content .= "$adress";

	$content .= "</div>";
	
	$content .= "<div style=\"float:left;width:225px;margin:5px;line-height:1.5em;\">" . $langArray["registeredaddress"] . ": $hs<br />";
	$content .= $langArray["building"] . ": $hus</div>";
	
	if(!$unfold) $content .= "<div style=\"float:left;width:125px;margin:5px;line-height:1.5em;\"><a href=\"/institutioner\" onClick=\"lista('$lucatid', 'listaPersonerLucat', '', '', '$lang', '$sorting', '$sortorder', '$action', '', '$hide_search'); return false;\">" . $langArray["listofpersonel"] . "</a></div>";	
	
	$content .= "</div>";
	
	$content .= "<div class=\"tx_institutioner_gra_streck\"></div>";
	
	return $content;
	//<div>$lucatid</div>
}

function expandInst($uid, $husid, $lucatid, $lang, $maininst, $unfold) {

	if($lang) $langArray=getLang($lang);

	$res = $GLOBALS["TYPO3_DB"]->exec_SELECTquery("I.title, I.uid, I.lucatid, I.webbadress, H.title AS hus, H.adress, H.hs, H.husid", "tx_institutioner_institution I LEFT JOIN tx_institutioner_hus H ON I.hid=H.uid", "I.uid = $uid AND I.deleted=0", "", "") or die("165; ".mysql_error());

	$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
	$uid = $row["uid"];
	$hus = $row["hus"];
	$adress = $row["adress"];
	$lucatid = $row["lucatid"];
	$title = $row["title"];
	$webbadress = $row["webbadress"];
	$hs = $row["hs"];
	$husid = $row["husid"];
	
	$GLOBALS["TYPO3_DB"]->sql_free_result($res);
	
	if(!$maininst) $content .= "<div style=\"height:15px; width:15px; background-color:#000080; margin-left:-19px; margin-right:0px; margin-top:5px; float:left\"></div>";

	$content .= "<div class=\"institution_listitem_header\" style=\"float:left; width:245px; margin:5px;\">
	<a href=\"$webbadress\">$title</a></div>
	<div style=\"float:left;\"><img style=\"\" src=\"/typo3conf/ext/institutioner/graphics/dolj_$lang.gif\" border=\"0\" onClick=\"listitem_click('listitem_$uid', 'collapseInst', '$uid', '$husid', '$maininst', '$lang', '$unfold');\" />
	</div>
	
	<div style=\"clear:both;\"></div>
	
  	<div style=\"float: left; width: 225px;line-height:1.5em;margin:5px;\">
		$adress<br />" .
		$langArray["registeredaddress"] . ": $hs<br />" .
		$langArray["building"] . ": $hus		
	</div>
		
  	<div style=\"float: left; width: 225px;line-height:1.5em;margin:5px;\">
		<a href=\"$webbadress\">" .	$langArray["homepage"] . "</a><br />
		<a href=\"/institutioner\" onClick=\"lista('$lucatid', 'listaPersonerLucat', '', '', '$lang', '$sorting', '$sortorder', '$action', '$unfold', '$hide_search'); return false;\">" . $langArray["listofpersonel"] . "</a>		
	</div>
	
  	<div class=\"tx_institiutioner_mapdisplay\" align=\"center\" style=\"float:left; width:150px; margin-bottom:5px; background-color:#ffffff; line-height:1.5em;\" onClick=\"lista('listaInstitutioner', 'flashMap', '$husid', '', '$lang', '$sorting', '$sortorder', '$action', '$unfold', '$hide_search');\">
		<img src=\"/typo3conf/ext/institutioner/graphics/" . $husid . "_small_map.gif\" border=\"0\" height=\"150\" width=\"150\" /><br />" .
		$langArray["largemap"] . "
	</div>
	";
//
	return $content;
}

function collapseInst($uid, $husid, $lucatid, $lang, $maininst, $unfold) {

	if($lang) $langArray=getLang($lang);

	$res = $GLOBALS["TYPO3_DB"]->exec_SELECTquery("I.title, I.uid, I.lucatid, I.webbadress, H.title AS hus, H.adress, H.hs, H.husid", "tx_institutioner_institution I LEFT JOIN tx_institutioner_hus H ON I.hid=H.uid", "I.uid = $uid AND I.deleted=0", "", "") or die("209; ".mysql_error());

	$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
	$uid = $row["uid"];
	$hus = $row["hus"];
	$adress = $row["adress"];
	$lucatid = $row["lucatid"];
	$title = $row["title"];
	$webbadress = $row["webbadress"];
	$hs = $row["hs"];
	$husid = $row["husid"];
	
	$GLOBALS["TYPO3_DB"]->sql_free_result($res);
	
	if(!$maininst) $content .= "<div style=\"height:15px; width:15px; background-color:#000080; margin-left:-19px; margin-right:9px; margin-top:5px; float:left\"></div>";
	
	$content .= "<div style=\"margin:5px;\"><b>$title</b></div>";
	$content .= "<div style=\"float:left;width:225px;margin:5px;line-height:1.5em;\"><a href=\"$webbadress\">" . $langArray["homepage"] . "</a><br />";
	$content .= "$adress</div>";
	
	$content .= "<div style=\"float:left;width:225px;margin:5px;line-height:1.5em;\">" . $langArray["registeredaddress"] . ": $hs<br />";
	$content .= $langArray["building"] .  ": $hus</div>";
	
	$content .= "</div>";
	
	return $content;
}

function getFeGroup($usergroup, $lang, $action)
{
	$res = $GLOBALS["TYPO3_DB"]->exec_SELECTquery("uid, title, tx_institutioner_lucatid, tx_institutioner_title_eng AS title_eng", "fe_groups", "uid IN($usergroup) AND deleted=0", "", "") or die("561; ".mysql_error());
	while ($row = $GLOBALS["TYPO3_DB"]->sql_fetch_assoc($res)) {
		$uid = $row["uid"];
		$title = $row["title"];
		$title_eng = $row["title_eng"];
		$tx_institutioner_lucatid = $row["tx_institutioner_lucatid"];
		if($lang=="en") $title=$title_eng;
		
		$myTitle = substr($title, 0, 35);
		if(strlen($title) > 35 and $action != "exportDo") $myTitle .= "...";
		if($action == "listaPersonerLucat") {
			$content .= $myTitle;
		} elseif($action == "exportDo") {
			$content .= "$myTitle;$tx_institutioner_lucatid";
		} else {
			$content .= "<a href=\"#\" onClick=\"lista('$tx_institutioner_lucatid', 'listaPersonerLucat', '', '', '$lang', '$sorting', '$sortorder', '$action', '', '$hide_search'); return false;\">$myTitle</a>";
		}
	}
	
	$GLOBALS["TYPO3_DB"]->sql_free_result($res);
	
	return $content;
}

function fullPerson($scope, $action, $imageSokvag, $startrecord, $lang) {
	//echo "scope=$scope, action=$action, imageSokvag=$imageSokvag, lang=$lang";
	if($lang) $langArray=getLang($lang);
	$i = 0;
	
	if($scope) {
		$res = $GLOBALS["TYPO3_DB"]->exec_SELECTquery("uid, username, usergroup, name, email, telephone, image, title, comments", "fe_users", "uid=$scope AND deleted=0", "", "") or die("612; ".mysql_error());
		$row = $GLOBALS["TYPO3_DB"]->sql_fetch_assoc($res);
		$uid = $row["uid"];
		$username = $row["username"];
		$name = $row["name"];
		$email = $row["email"];
		$usergroup = $row["usergroup"];
		$telephone = formatPhone($row["telephone"], $lang);
		$image = $row["image"];
		$title = $row["title"];
		$comments = $row["comments"];

		if($usergroup) $displayGroup = getFeGroup($usergroup, $lang, $action);
		
		//$content .= "<div style=\"clear:both;\"><h1>$name, $title</h1></div>";
		
		if($image) {

			$imageArray = explode(",", $image);
			foreach($imageArray as $imageItem) {
				$dimensions = getimagesize("$imageSokvag$imageItem");
				$width = $dimensions[0];
				$height = $dimensions[1];

				$maxHeight = 33;
				$divFactor = $maxHeight / $height;
				$width1 = $width * $divFactor;
				$height1 = $height * $divFactor;
				
				$maxHeight = 200;
				$divFactor = $maxHeight / $height;
				$width2 = $width * $divFactor;
				$height2 = $height * $divFactor;
				
				if($i==0) {
					$imageDisplay .= "<div align=\"center\"><img id=\"mainImage\" style=\"margin-bottom:6px; border: 1px solid #b8b8b8;\" src=\"$imageSokvag$imageItem\" width=\"$width2\" height=\"$height2\" border=\"0\" /></div>";
				} 

				$miniDisplay .= "<div style=\"float:left; margin:2px; border: 1px solid #b8b8b8;\">";
				$miniDisplay .= "<a href=\"#\" onclick=\"document.getElementById('mainImage').src='$imageSokvag$imageItem'; document.getElementById('mainImage').width='$width2'; document.getElementById('mainImage').height='$height2'; return false;\">";
				$miniDisplay .= "<img style=\"\" src=\"$imageSokvag$imageItem\" width=\"$width1\" height=\"$height1\" border=\"0\" />";
				$miniDisplay .= "</a>";
				$miniDisplay .= "</div>";
				
				$i++;
			}
			if($miniDisplay) $miniDisplay = "<div style=\"border: 1px solid #b8b8b8; padding:2px; height:37px; margin-top:6px;\">$miniDisplay</div>";
			$imageDisplay = "<div style=\"float:left; width:200px; padding:6px; margin-right:20px; margin-bottoms:10px; border:1px solid #b8b8b8;\">$imageDisplay$miniDisplay</div>";
		}
		$content .= "<div style=\"clear:both;\">";
		if($image) $content .= $imageDisplay;
		$content .= "<div style=\"line-height:2em;\"><span style=\"color:#996633; font-size: 1.5em; font-weight:bold;\">$name</span><br /><b>$title</b><br /><a href=\"mailto:$email\">$email</a><br />$telephone</div><div>$comments</div>";
		$content .= "</div>";
		$content .= "<div style=\"clear:both;\"></div>";
		//$content .= "<div style=\"margin-left:0px; margin-top:25px; line-height:1.5em;\">";
		//$content .= "<div style=\"font-weight:bold;\">$name, $title</div>";
		//$content .= "<div style=\"float:left;line-height:1.5em;\">"; 
		//if($image) $content .= $imageDisplay;
		//$content .= "<br /><a href=\"mailto:$email\">Email</a><br />";
		//$content .= "$telephone</div><div>$comments</div>";
		//$content .= "<div style=\"float:left;\"><div>$comments</div>";
			//$content .= "<a href=\"mailto:$email\">$email</a><br />";
			//$content .= "$telephone";
		//$content .= "</div>";
		
		//$content .= "<div style=\"float:left;width:250px;\">";
		//	$content .= "<br />";
			//?????????????????if($displayGroup) $content .= $displayGroup;
		//$content .= "</div>";
		//$content .= "</div>";
		//lup
		//$startrecord = 1;
		$xmlpath = "http://luur.lub.lu.se/luurSru?version=1.1&operation=searchRetrieve&query=author%20exact%20$username%20&sortKeys=publishingYear,,0%20title&startRecord=$startrecord";
		//echo $xmlpath;
		$dom = new domDocument;
		$dom->preserveWhiteSpace = false;
		$dom->load($xmlpath);
	
		$artists = $dom->documentElement;
		$results = $dom->getElementsByTagName('records')->item(0);
	
		$numberofrecords = $dom->getElementsByTagName("numberOfRecords");
		$numberofrecord = $numberofrecords->item(0)->firstChild->nodeValue;
		
		$previousrecord = $startrecord - 10;
		$nextrecord = $startrecord + 10;
		
		if($nextrecord > $numberofrecord) {
			$till = $numberofrecord;
		} else {
			$till = $nextrecord - 1;
		}
				
		$content .= "<div style=\"clear:both;\"></div>";
		if($numberofrecord > 0) {
			$content .= "<div style=\"height:27px; margin-top:25px; margin-bottom:15px; margin-left:0px; font-weight:bold; background-color:#dedede;\">";
			$content .= "<div style=\"float:left; margin-left:10px; padding-top:5px; \">$startrecord till $till av $numberofrecord poster ur LUP</div>";
			$content .= "<div align=\"right\" style=\"font-size:0.9em; padding-top:5px; \">
			<label><a hreg=\"#\" title=\"" . $langArray["export_bibtex"] . "\" onclick=\"exportBibtex(); return false;\">Exportera bibtex</a></label>";
			$content .= "&nbsp;&nbsp;<input type=\"checkbox\" title=\"" . $langArray["select_all"] . "\" id=\"\" name=\"\" onclick=\"checkAll(this.checked);\" /></div>";
			//$content .= " <img src=\"/typo3conf/ext/ajax_lup/pi1/graphics/select_all.gif\" border=\"0\" />";
			$content .= "</div>";
		}

		foreach ($artists->childNodes as $artist) {
			
			$records = $artist->getElementsByTagName('record');
	
			foreach ($records as $record) {
				$namepart = '';
				$roleterm = '';
				$affiliation = '';
				$author = '';
				$conference = '';
				$placeTerm = '';
				$genre = '';
				$journal = '';
				$url = '';
				$accessCondition = '';
				$year = '';
				
				$recordidentifiers = $record->getElementsByTagName('recordIdentifier');
				$recordidentifier = $recordidentifiers->item(0)->firstChild->nodeValue;
		
				$titles = $record->getElementsByTagName('title');
				$title = $titles->item(0)->firstChild->nodeValue;
				
				$names = $record->getElementsByTagName('name');
				$author="";
				foreach ($names as $name) {
					$nameparts = $name->getElementsByTagName('namePart');
					$namepart = $nameparts->item(0)->firstChild->nodeValue;
					$namepart .= ' ' . $nameparts->item(1)->firstChild->nodeValue;
					$namepart .= ' ' . $nameparts->item(2)->firstChild->nodeValue;
					
					$roleterms = $name->getElementsByTagName('roleTerm');
					$roleterm = $roleterms->item(0)->firstChild->nodeValue;
					
					$affiliations = $name->getElementsByTagName('affiliation');
					$affiliation = $affiliations->item(0)->firstChild->nodeValue;
		
					if($roleterm == "author") {
						if($author) $author .= ", ";
						if($affiliation == "") {
							$author .= $namepart;
						} else {
							$author .= $namepart;
						}
					}
					
						//Ev konferens
					if($name->getAttribute('type')=="conference") {
						$conference = trim($namepart);
					}
				}
				
					//Ev plats f�r konferens
				$placeTerms = $record->getElementsByTagName('placeTerm');
				$placeTerm = $placeTerms->item(0)->firstChild->nodeValue;
				
					// Dokumenttyp
				$genres = $record->getElementsByTagName('genre');
				$genre = $genres->item(0)->firstChild->nodeValue;
				
					//Ev tidskrift
				if($genre == "article") {
					$relatedItems = $record->getElementsByTagName('relatedItem');
					foreach ($relatedItems as $relatedItem) {
						if($identifier) $identifier .= ", ";
						if($relatedItem->getAttribute('type')=="host") {
							$journals = $relatedItem->getElementsByTagName('title');
							$journal = $journals->item(0)->firstChild->nodeValue;
							$details = $relatedItem->getElementsByTagName('detail');
							foreach ($details as $detail) {
								if($detail->getAttribute('type')=="volume") {
									$journal .=  ", " . $langArray["volume"] . " " . $details->item(0)->firstChild->nodeValue;
								} elseif($detail->getAttribute('type')=="issue") {
									$journal .=  ", " . $langArray["issue"] . " " . $details->item(1)->firstChild->nodeValue;
								}
							}
							$starts = $relatedItem->getElementsByTagName('start');
							$start =  $starts->item(0)->firstChild->nodeValue;
							if($start) $journal .=  ", " . $langArray["page"] . " " . $start;
							
							$ends = $relatedItem->getElementsByTagName('end');
							$end = $ends->item(0)->firstChild->nodeValue;
							if($end) $journal .= ", " . $langArray["to"] . " " . $end;
							//Ev l�nk till tidskrift
							$journal_urls = $relatedItem->getElementsByTagName('url');
							$journal_url = $journal_urls->item(0)->firstChild->nodeValue;
						}
					}
				}
				
					//L�nk till fulltext
				$urls = $record->getElementsByTagName('url');
				$url = $urls->item(0)->firstChild->nodeValue;
				
					//accessCondition
				$accessConditions = $record->getElementsByTagName('accessCondition');
				$accessCondition = $accessConditions->item(0)->firstChild->nodeValue;
				if(strtolower($accessCondition) == "yes") {
					$accessCondition = "<img src=\"/typo3conf/ext/institutioner/pi1/graphics/lock.gif\" border=\"0\" title=\"" . $langArray["restrictedAccess"] . "\" />";
				} else {
					$accessCondition = "";
				}
						
				$years = $record->getElementsByTagName('dateIssued');
				$year = $years->item(0)->firstChild->nodeValue;
				$content .= "<div class=\"tx_institutioner_lup_container\">";
				$content .= "<div class=\"tx_institutioner_lup_content\">";
				$content .= "<div style=\"\">";
					$content .= "<strong><a style=\"text-decoration:none;\" href=\"#\" onClick=\"lista('$scope', 'fullpost', '$recordidentifier', '$startrecord', '$lang', '$sorting', '$sortorder', '$action', '', '$hide_search'); return false;\">$title</a></strong>";
				$content .= "</div>";
				$content .= "<div style=\"margin-left:0px;\">F&ouml;rfattare: $author</div>";
					//Genre
				$content .= "<div style=\"margin-left:0px;\">Genre: $genre</div>";
					//Ev konferens
				if($conference) {
					$content .= "<div style=\"margin-left:0px;\">" . $langArray["conference"] . ": $conference";
					if($placeTerm) $content .= ", $placeTerm";
					$content .= "</div>";
				}
					//Ev tidskrift
				if($journal) {
					$content .= "<div style=\"margin-left:0px;\">" . $langArray["journal"] . ": ";
					if($journal_url) $content .= "<a href=\"$journal_url\" target=\"_blank\">";
					$content .= $journal;
					if($journal_url) $content .= "</a>";
					$content .= "</div>";
				}
				$content .= "<div style=\"margin-left:0px;\">&Aring;r: $year</div>";
				if($url) $content .= "<div style=\"margin-left:0px;\" class=\"lupPdf\">$accessCondition <a href=\"$url\" target=\"_blank\">" . $langArray["fulltext"] . "</a></div>";
				$content .= "</div>";
				$content .= "<div align\"right\" class=\"tx_institutioner_lup_bibbox\"><input type=\"checkbox\" value=\"$recordidentifier\" name=\"bibBox\" /></div>";		
				$content .= "</div>";
				$content .= "<div class=\"tx_institutioner_gra_streck_fullperson\"></div>";
			}
		}
		
		//Sidbl�ddring start
		$content .= "<div align=\"center\" style=\"margin-top:20px;\">";
		if($startrecord > 10) {
			$content .= "<a href=\"#\" onClick=\"lista('$scope', '$action', '$query', '$previousrecord', '$lang', '$sorting', '$sortorder', '$action', '', '$hide_search'); return false;\"><< f&ouml;rra</a>&nbsp;&nbsp;&nbsp;&nbsp;";
			//. $langArray["previous"];
		}
	
		if($numberofrecord > $nextrecord) {
			$content .= "<a href=\"#\" onClick=\"lista('$scope', '$action', '$query', '$nextrecord', '$lang', '$sorting', '$sortorder', '$action', '', '$hide_search'); return false;\">n&auml;sta >></a>";
			//. $langArray["next"];
		}
		$content .= "</div>";

		//Sidbl�ddring slut
		$content .= "";
	}
	
	$GLOBALS['TYPO3_DB']->sql_free_result($res);
	
	$content .= "<div style=\"clear:both\">";
	$content .= "<input name=\"update_user\" type=\"hidden\" value=\"1\" />";
	//$content .= "<input name=\"submit\" type=\"submit\" value=\"Uppdatera\" />";
	//$content .= "<a href=\"#\" onclick=\"lista('$username', 'editPerson', '', '', '$lang', '$sorting', '$sortorder', '$action', '', '$hide_search'); return false;\">Uppdatera</a>";
	$content .= "</div>";
	$content .= "<div style=\"position: relative; left:520px; top:167px;\"><a href=\"#\"><img src=\"/typo3conf/ext/institutioner/pi1/graphics/update.gif\" border=\"0\" onclick=\"document.institutionerform.submit(); return false;\"/></a></div>";
	return $content;
}

function listaBokstaver($scope, $query, $lang)
{
	//echo $scope . $query .$lang;
	if($scope) {
		//$res = $GLOBALS["TYPO3_DB"]->exec_SELECTquery("uid", "fe_groups", "tx_institutioner_lucatid IN($scope) AND deleted=0", "", "") or die("714; ".mysql_error());
		$res = $GLOBALS["TYPO3_DB"]->exec_SELECTquery("G1.uid AS avdid, G2.uid AS instid, G1.title, G2.subgroup", "fe_groups G1 LEFT JOIN fe_groups G2 ON G2.subgroup = G1.uid", "G1.tx_institutioner_lucatid IN($scope) AND G1.deleted=0", "", "") or die("716; ".mysql_error());
		while ($row = $GLOBALS["TYPO3_DB"]->sql_fetch_assoc($res)) {
			//if($groupid) $groupid .= ",";
			//$groupid .= $row["uid"];
			if($groupid) $groupid .= ",";
			$subgroup = $row["subgroup"];
			if($subgroup) {
				$groupid .= $row["instid"];
			} else {
				$groupid .= $row["avdid"];
			}
		}
		$urval = "usergroup IN($groupid) AND ";
	}
	//echo $urval;
	$content = "<div style=\"text-align:center; margin-bottom:10px;\">";
	$res = $GLOBALS["TYPO3_DB"]->exec_SELECTquery("DISTINCT left(name, 1) AS letter", "fe_users", $urval . "name != '' AND name != ',' AND deleted=0 AND tx_institutioner_lth_search=1", "", "name") or die("723; ".mysql_error());
	while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
		$letter = strtoupper($row['letter']);
		$content .= "<a href=\"#\" onClick=\"lista('$scope', 'listaPersonerBokstav', '$letter', '', '$lang', '$sorting', '$sortorder', '$action', '', '$hide_search'); return false;\">$letter</a>&nbsp;&nbsp;";
	}
	$content .= "</div>";
	
	
	$GLOBALS['TYPO3_DB']->sql_free_result($res);

	return $content;
}

function flashMap($returnValue, $husid, $lang) {
	//echo $husid;
	if($lang) $langArray=getLang($lang);
	//
	$content = "
	<div class=\"institution_flashmap\"  style=\"margin-top:-5px;\" onClick=\"lista('', '$returnValue', '', '', '$lang', '$sorting', '$sortorder', '$action', '', '$hide_search'); return false;\"><< " . $langArray["back"] . "</div>
	<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" width=\"505\" height=\"669\" id=\"FlashID\" title=\"test2\" style=\"margin-left:70px;\">
	<param name=\"movie\" value=\"/typo3conf/ext/institutioner/pi1/flash/campuskarta.swf?hus=$husid\" />
	<param name=\"quality\" value=\"high\" />
	<param name=\"wmode\" value=\"opaque\" />
	<param name=\"swfversion\" value=\"6.0.65.0\" />
	<!-- This param tag prompts users with Flash Player 6.0 r65 and higher to download the latest version of Flash Player. Delete it if you don�t want users to see the prompt. -->
	<param name=\"expressinstall\" value=\"../../../Scripts/expressInstall.swf\" />
	<!-- Next object tag is for non-IE browsers. So hide it from IE using IECC. -->
	<!--[if !IE]>-->
	<object type=\"application/x-shockwave-flash\" data=\"/typo3conf/ext/institutioner/pi1/flash/campuskarta.swf?hus=$husid\" width=\"505\" height=\"669\">
	<!--<![endif]-->
	<param name=\"quality\" value=\"high\" />
	<param name=\"wmode\" value=\"opaque\" />
	<param name=\"swfversion\" value=\"6.0.65.0\" />
	<param name=\"expressinstall\" value=\"../../../Scripts/expressInstall.swf\" />
	<!-- The browser displays the following alternative content for users with Flash Player 6.0 and older. -->
	<div>
	<h4>Content on this page requires a newer version of Adobe Flash Player.</h4>
	<p><a href=\"http://www.adobe.com/go/getflashplayer\"><img src=\"http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif\" alt=\"Get Adobe Flash player\" width=\"112\" height=\"33\" /></a></p>
	</div>
	<!--[if !IE]>-->
	</object>
	<!--<![endif]-->
	</object>
	<div class=\"institution_flashmap\" onClick=\"lista('', '$returnValue', '', '', '$lang', '$sorting', '$sortorder', '$action', '', '$hide_search'); return false;\" style=\"margin-top:-5px; margin-bottom:30px;\"><< " . $langArray["back"] . "</div>";
	return $content;
}

function getLang($lang)
{
	$xmlpath = "typo3conf/ext/institutioner/pi1/locallang.xml";
	$xml = simplexml_load_file($xmlpath);
	
	//print_r($xml);
	if($lang == "en") $lang = "default";
	foreach ($xml->data->languageKey as $languageKey) {
		//echo $languageKey->attributes()->getName();
		if($languageKey->attributes()==$lang) {
			foreach($languageKey->label as $label) {
				
				$tempLabel = $label->attributes();
				$langArray["$tempLabel"] = $label;
				//echo $label . $tempLabel;
			}
		}
	}
	return $langArray;
}

function getTitles()
{
	$xmlpath = "typo3conf/ext/institutioner/pi1/titles.xml";
	$xml = simplexml_load_file($xmlpath);
	//print_r($xml);
	foreach ($xml->data->titleKey as $titleKey) {
		$category = $titleKey->attributes();
		$i = 0;
		foreach($titleKey->title as $title) {
				//$tempTitle = $titleKey->attributes();
				$titleArray["$category"]["$i"] = strtolower(trim($title));
				//echo $category .$i. $title."<br />";
				$i++;
		}
	}
	return $titleArray;
}

function exportChoice($scope,$action,$query,$imageSokvag,$lang,$hide_search,$html_template,$imagefolder,$addpeople,$removepeople,$categories,$queryfilter,$issiteadmin,$pluginid)
{
	$content = "
	<form name=\"institutionerform\">
	<h2>V&auml;lj exportformat</h2>
	<table style=\"margin-bottom:50px; margin-top:20px;\" width=\"420\" border=\"0\" cellspacing=\"3\" cellpadding=\"0\">
	    <tr>
		<td>
		    <input onclick=\"disableGroup(this.value, 'txtHeader');\" type=\"radio\" name=\"rblExportFormat\" id=\"rblExportFormat_pdfList\" value=\"cblPdfList\" checked />Lista i pdf-format
		</td>
		<td>
		    <input type=\"checkbox\" name=\"cblPdfList\" id=\"cblPdfList_title\" value=\"title\" checked />
		    Titel
		    <input type=\"checkbox\" name=\"cblPdfList\" id=\"cblPdfList_name\" value=\"name\" checked />
		    Namn
		    <input type=\"checkbox\" name=\"cblPdfList\" id=\"cblPdfList_dep\" value=\"dep\" checked />
		    Inst/avd
		    <br />
		    <input type=\"checkbox\" name=\"cblPdfList\" id=\"cblPdfList_telephone\" value=\"telephone\" checked />
		    telefon
		    <input type=\"checkbox\" name=\"cblPdfList\" id=\"cblPdfList_email\" value=\"email\" checked />
		    email
		    <br />
		    Rubrik:
		    <br />
		    <input type=\"text\" name=\"txtHeader\" id=\"txtHeader\" />
		</td>
	    </tr>
	  
	    <tr>
		<td>
		    <input onclick=\"disableGroup(this.value, '');\" type=\"radio\" name=\"rblExportFormat\" id=\"rblExportFormat\" value=\"cblTabSep\" />
		    Tab-separerad textfil
		</td>
		<td>
		    <input type=\"checkbox\" name=\"cblTabSep\" id=\"cblTabSep_title\" value=\"title\" />
		    Titel
		    <input type=\"checkbox\" name=\"cblTabSep\" id=\"cblTabSep_name\" value=\"name\" />
		    Namn
		    <input type=\"checkbox\" name=\"cblTabSep\" id=\"cblTabSep_dep\" value=\"dep\" />
		    Inst/avd
		    <br />
		    <input type=\"checkbox\" name=\"cblTabSep\" id=\"cblTabSep_telephone\" value=\"telephone\" />
		    telefon
		    <input type=\"checkbox\" name=\"cblTabSep\" id=\"cblTabSep_email\" value=\"email\" />
		    email
		</td>
	    </tr>
	  
	    <tr>
		<td>
		    <input onclick=\"disableGroup(this.value, '');\" type=\"radio\" name=\"rblExportFormat\" id=\"rblExportFormat\" value=\"cblKommaSep\" />
		    Komma-separerad textfil
		</td>
		<td>
		    <input type=\"checkbox\" name=\"cblKommaSep\" id=\"cblKommaSep_title\" value=\"title\" />
		    Titel
		    <input type=\"checkbox\" name=\"cblKommaSep\" id=\"cblKommaSep_name\" value=\"name\" />
		    Namn
		    <input type=\"checkbox\" name=\"cblKommaSep\" id=\"cblKommaSep_dep\" value=\"dep\" />
		    Inst/avd
		    <br />
		    <input type=\"checkbox\" name=\"cblKommaSep\" id=\"cblKommaSep_telephone\" value=\"telephone\" />
		    telefon
		    <input type=\"checkbox\" name=\"cblKommaSep\" id=\"cblKommaSep_email\" value=\"email\" />
		    email
		</td>
	    </tr>
	  
	    <tr>
		<td> 
		    <input onclick=\"disableGroup(this.value, '');\" type=\"radio\" name=\"rblExportFormat\" id=\"rblExportFormat\" value=\"cblCsv\" />
		    CSV-fil
		</td>
		<td>
		    <input type=\"checkbox\" name=\"cblCsv\" id=\"cblCsv_title\" value=\"title\" />
		    Titel
		    <input type=\"checkbox\" name=\"cblCsv\" id=\"cblCsv_name\" value=\"name\" />
		    Namn
		    <input type=\"checkbox\" name=\"cblCsv\" id=\"cblCsv_dep\" value=\"dep\" />
		    Inst/avd
		    <br />
		    <input type=\"checkbox\" name=\"cblCsv\" id=\"cblCsv_telephone\" value=\"telephone\" />
		    telefon
		    <input type=\"checkbox\" name=\"cblCsv\" id=\"cblCsv_email\" value=\"email\" />
		    email
		</td>
	    </tr>
	  
	    <tr>
		<td> 
		    <input onclick=\"disableGroup(this.value, 'txtLabelExtra');\" type=\"radio\" name=\"rblExportFormat\" id=\"rblExportFormat\" value=\"cblLabels\" />
		    Etiketter (avery 7160)
		</td>
		<td>
		    <input type=\"checkbox\" name=\"cblLabels\" id=\"cblLabels_title\" value=\"title\" />
		    Titel
		    <input type=\"checkbox\" name=\"cblLabels\" id=\"cblLabels_name\" value=\"name\" />
		    Namn
		    <input type=\"checkbox\" name=\"cblLabels\" id=\"cblLabels_dep\" value=\"dep\" />
		    Inst/avd
		    <br />
		    <input type=\"checkbox\" name=\"cblLabels\" id=\"cblLabels_hs\" value=\"hs\" />
		    H&auml;mtst&auml;lle
		    <br />
		    Extra:
		    <br />
		    <input type=\"text\" name=\"txtLabelExtra\" id=\"txtLabelExtra\" />
		</td>
	    </tr>
	    <tr>
		<td colspan=\"2\">&nbsp;</td>
	    </tr>
	    <tr>
		<td colspan=\"2\">
		    <input onclick=\"exportDo('$scope','exportDo','$query','$imageSokvag','$lang','$hide_search','$html_template','$imagefolder','$addpeople','$removepeople','$categories','$queryfilter','$pluginid','$issiteadmin'); return false;\" type=\"button\" name=\"btnExport\" id=\"btnExport\" value=\"Exportera\" />
		    &nbsp;&nbsp;
		    <input onClick=\"lista('$scope','listaPersoner','$query','$lang','$hide_search','$html_template','$imagefolder','$addpeople','$removepeople','$categories','$queryfilter'); return false;\" type=\"button\" name=\"btnCancel\" id=\"btnCancel\" value=\"Avbryt\" />
		</td>
	    </tr>
	</table>
	</form>";
	$returnArray = array();
    $returnArray['content'] = $content;
    $returnArray['rightcol'] = '';
    $returnArray['hits'] = '';
    return $returnArray;
}

function exportDo($scope,$action,$query,$imageSokvag,$lang,$hide_search,$html_template,$imagefolder,$addpeople,$removepeople,$categories,$queryfilter,$rvalue,$cvalue,$lextra) 
{
    if (!t3lib_extMgm::isLoaded('fpdf')) return "fpdf library not loaded!";
    $i = 0;
    $x = 0;
    $y = 0;

    $response = getSolrData($scope,$action,$query,$imageSokvag,$lang,$hide_search,$html_template,$imagefolder,$addpeople,$removepeople,$categories,$queryfilter);

    $cArray = explode(",", $cvalue);
    if(in_array("title", $cArray)) $title_display = true;
    if(in_array("name", $cArray)) $name_display = true;
    if(in_array("dep", $cArray)) $dep_display = true;
    if(in_array("telephone", $cArray)) $telephone_display = true;
    if(in_array("email", $cArray)) $email_display = true;
    if(in_array("hs", $cArray)) $hs_display = true;
    if(in_array("adress", $cArray)) $adress_display = true;
    if(in_array("webbaddress", $cArray)) $webbaddress_display = true;

    switch($rvalue) {
        case "cblPdfList":
            $pdf = new PDF();
            $pdf->AddPage("L", "A4");
            $pdf->SetAutoPageBreak(false);

            //if($lextra) {
                    $pdf->SetFont('Helvetica', 'B', 9);
                    $pdf->Cell(300,10,$lextra,0,1,C,0);
                    $pdf->Ln(2);
            //}
            $pdf->SetMargins(10,10);
            $pdf->SetFont('Helvetica', '', 9);
            break;
        case "cblLabels":
            $pdf = new PDF();
            $pdf->AddPage("P", "A4");
            $pdf->SetMargins(0,0);
            $pdf->SetAutoPageBreak(false);
            $pdf->SetFont('Helvetica', '', 9);		
            break;
    }

    foreach ( $response->response->docs as $doc ) {
        $title = utf8_decode(ucfirst($doc->title));
        $name = utf8_decode($doc->first_name) . ' ' . utf8_decode($doc->last_name);
        $email = strtolower($doc->email);
        $telephone = $doc->telephone;
        if($telephone) formatPhone($telephone, $lang);
        $dep = utf8_decode($doc->ou);
        $adress = utf8_decode($doc->street);
        $hs = $doc->registeredaddress;
        $webbaddress = $doc->www;
        
        /*
         *                     $markerArray['###NAME###'] = "$doc->first_name $doc->last_name";
                    $markerArray['###FIRST_NAME###'] = $doc->first_name;
                    $markerArray['###LAST_NAME###'] = $doc->last_name;
                    $markerArray['###TITLE###'] = ucfirst($doc->title);
                    $markerArray['###TELEPHONE###'] = $doc->telephone;
                    $markerArray['###EMAIL###'] = $doc->email;
                    $markerArray['###SUBJECT###'] = $doc->ou;
                    $markerArray['###IMAGE###'] = $doc->image;
                    $markerArray['###WWW###'] = $doc->www;
                    $markerArray['###WWWLABEL###'] = $doc->wwwlabel;
                    $markerArray['###COMMENTS###'] = $doc->comments;
                    $markerArray['###CATEGORY###'] = $cat;
         * 
         */

        $s = "";
        //$lc = 0;
        switch($rvalue) {
            case cblPdfList:
                $pdf->SetMargins(10,10);
                if($action == "exportDepDo") {
                        if($title_display) $pdf->Cell(120,6,$title,1,0,L,0);
                } else {
                        if($title_display) $pdf->Cell(60,6,$title,1,0,L,0);
                }
                if($name_display) $pdf->Cell(50,6,$name,1,0,L,0);
                if($dep_display) $pdf->Cell(60,6,$dep,1,0,L,0);
                if($adress_display) $pdf->Cell(40,6,$adress,1,0,L,0);
                if($hs_display) $pdf->Cell(10,6,$hs,1,0,L,0);
                if($telephone_display) $pdf->Cell(30,6,$telephone,1,0,L,0);
                if($email_display) $pdf->Cell(65,6,$email,1,0,L,0);
                if($webbaddress_display) $pdf->Cell(65,6,$webbaddress,1,0,L,0);
                $pdf->Cell(5,6,"",1,0,L,0);
                $pdf->Ln(6);
                $y++;

                if($y==28) {
                        $i++;
                        $pdf->Cell(300,20,$i,0,1,C,0);
                        $pdf->AddPage("L", "A4");
                        if($lextra) {
                                $pdf->SetFont('Helvetica', 'B', 9);
                                $pdf->Cell(300,15,$lextra,0,1,C,0);
                                $pdf->Ln(2);
                        }
                        $y = 0;

                }
                break;
            case cblTabSep:
                if($title_display) $s .= "$title\t";
                if($name_display) $s .= "$name\t";
                if($dep_display) $s .= $dep . "\t";
                if($telephone_display) $s .= "$telephone\t";
                if($email_display) $s .= "$email\t";
                if($adress_display) $s .= "$adress\t";				
                if($hs_display) $s .= "$hs\t";
                if($webbaddress_display) $s .= "$webbaddress\t";
                if(substr($s, -2) == "\t") $s = substr($s, 0, strlen($s) - 2);			
                $s .= "\r\n";
                $content .= $s;
                break;
            case cblKommaSep:
                if($title_display) $s .= "$title,";
                if($name_display) $s .= "$name,";
                if($dep_display) $s .= $dep . ",";
                if($telephone_display) $s .= "$telephone,";
                if($email_display) $s .= "$email";
                if($adress_display) $s .= "$adress,";
                if($hs_display) $s .= "$hs,";
                if($webbaddress_display) $s .= "$webbaddress,";
                if(substr($s, -1) == ",") $s = substr($s, 0, strlen($s) - 1);			
                $s .= "\r\n";
                $content .= $s;
                break;			
            case cblCsv:
                if($title_display) $s .= $title . ";";
                if($name_display) $s .= $name . ";";
                if($dep_display) $s .= $dep . ";";
                if($telephone_display) $s .= "$telephone;";
                if($email_display) $s .= "$email;";
                if($adress_display) $s .= "$adress;";				
                if($hs_display) $s .= "$hs;";
                if($webbaddress_display) $s .= "$webbaddress;";
                if(substr($s, -1) == ";") $s = substr($s, 0, strlen($s) - 1);			
                $s .= "\n";
                $content .= $s;
                break;
            case cblLabels:
                if($displayArray[1]) $hs = getHamtstalle($displayArray[1]);
                if($hs_display) $s .= "$hs\n";
                if($title_display) $s .= "$title\n";
                if($adress_display) $s .= "$adress\n";				
                if($name_display) $s .= "$name\n";
                if($dep_display) $s .= $dep . "\n";
                if($lextra) $s .= "$lextra";
                if(substr($s, -2) == "\n") $s = substr($s, 0, strlen($s) - 2);
                Avery7160($x,$y,$pdf,$s);

                $y++;

                if($y==7) {
                        $x++;
                        $y=0;
                        if ($x == 3 ) { 
                                $x = 0;
                                $y = 0;
                                $pdf->AddPage();
                        }
                }
                break;
            }
    }

    switch($rvalue) {
            case cblPdfList:
                    $i++;
                    $pdf->SetY(-15); 
                    $pdf->Cell(300,15,$i,0,1,C,0);
                    $pdf->Output();
                    break;
            case cblTabSep:
                    header("Content-type: text/plain;");
                    header("Content-disposition: attachment; filename=" . date("Y-m-d"). ".txt");
                    print $content;
                    break;
            case cblKommaSep:
                    header("Content-type: text/plain;");
                    header("Content-disposition: attachment; filename=" . date("Y-m-d"). ".txt");
                    print $content;
                    break;			
            case cblCsv:
                    header("Content-type: application/vnd.ms-excel; ");
                    header("Content-disposition: attachment; filename=" . date("Y-m-d"). ".csv");
                    print $content;
                    break;
            case cblLabels:
                    $pdf->Output();
                    break;
    }

    exit;
}

function telephoneList($scope,$action,$query,$imageSokvag,$lang,$hide_search,$html_template,$imagefolder,$addpeople,$removepeople,$categories,$queryfilter)
{
    // Check if the FPDF library is loaded (extension 'FDPF'). If not, return immediately.
    if (!t3lib_extMgm::isLoaded('fpdf')) return "No pdf-library!";

    $response = getSolrData($scope,$action,$query,$imageSokvag,$lang,$hide_search,$html_template,$imagefolder,$addpeople,$removepeople,$categories,$queryfilter);

    $numberOfHits = $response->response->numFound;
    if ( $response->getHttpStatus() == 200 ) { 
        if ( $numberOfHits > 0 ) {

            if($lang) $langArray=getLang($lang);

            $x=0;
            $y=0;

            $pdf = new PDF();
            $pdf->AddPage("L");
            $pdf->SetAutoPageBreak(false);
            $pdf->SetFont('Helvetica', 'B', 11);
            $pdf->SetXY(0,0);
            $pdf->Cell(289,12,date('ymd'),0,0,'R');
            $pdf->SetFont('Helvetica', 'B', 14);
            $pdf->SetXY(0,0);
            $pdf->Cell(297, 25, 'Telefonlista',0,0,'C');

            $pdf->SetFont('Helvetica', '', 11);

            foreach ( $response->response->docs as $doc ) {
                $tele = $doc->telephone;
                $name = $doc->first_name . " " . $doc->last_name;
                $tele = str_replace(" ","",$tele);
                $tele = str_replace("046-22","",$tele);
                $tele = str_replace("042-3","",$tele);
                $tele = str_replace("+464622","",$tele);
                $tele = str_replace("+4622","",$tele);
                $tele = str_replace("28276","28135",$tele);
                if ($tele != "" and trim($name) != "") {
                    if($x>3) {
                        $pdf->AddPage("L", "A4");
                        $x=0;
                    }
                    if ($y==30) {
                            $y=0;
                            $x++;
                    }
                    if ($y%3==0) $pdf->Line(70*$x+8,6*$y+23,70*($x+1)+8,6*$y+23);
                    $y++;
                    $pdf->SetXY(70*$x+5,6*$y+20);
                    if (strlen($tele) > 5) $pdf->SetFont('Helvetica', '', 11);
                    $pdf->Cell(20,0,$tele,0,0,'R');
                    $pdf->SetFont('Helvetica', '', 11);
                    $pdf->Cell(0,0,utf8_decode($name));
                }
            }
            $pdf->Output();
        }
    }
                    //add_number($tele, utf8_decode($row["first_name"] . " " . $row["last_name"]));
            //}
    //}
    //while ($y<23) {
            //add_number('','');
    //}
    //$pdf->Cell(20,0,'20700, Securitas (br�dskande)',0,0,'R');
    //add_number('27200','LTH (infodisken)');
    //add_number('27600','Helpdesk');
    //add_number('','');
    //add_number('046-311300','Akademiska Hus');
    //add_number('046-311310','Akademiska Hus (jour)');
    //add_number('29000','LDCs servicedesk');
    //add_number('21610','Securitas');
    //add_number('20700','Securitas (br�dskande)');




    /*
    $pdf->AddFont('Arial Narrow','','arialn.php');
    $pdf->AddPage('L');
    $pdf->SetAutoPageBreak(false);

    $pdf->SetFont('Arial','','6');
    $pdf->SetXY(0,0);
    $pdf->Cell(289,12,date('ymd'),0,0,'R');

    $pdf->SetFont('Arial','','24');
    $pdf->SetXY(0,0);
    $pdf->Cell(297,25,'Telefonlista f�r LTHs kansli',0,0,'C');
    //$pdf->Cell(297,25,'Telefonlista f�r Elektro- och informationsteknik',0,0,'C');

    $pdf->SetFont('Arial','',11);

    */


}

/* function add_number($number,$name) {
	global $pdf;
	global $x;
	global $y;

	if ($y==30) {
		$y=0;
		$x++;
	}
	if ($y%3==0) $pdf->Line(70*$x+8,6*$y+23,70*($x+1)+8,6*$y+23);
	$y++;
	$pdf->SetXY(70*$x+5,6*$y+20);
	if (strlen($number) > 5) $pdf->SetFont('Helvetica');
	$pdf->Cell(20,0,$number,0,0,'R');
	$pdf->SetFont('Helvetica');
	$pdf->Cell(0,0,$name);
} */

function Avery7160($x, $y, &$pdf, $Data) {

	$LeftMargin = 10;
	$TopMargin = 20;
	$LabelWidth = 63;
	$LabelHeight = 40;
	// Create Co-Ords of Upper left of the Label
	$AbsX = $LeftMargin + (($LabelWidth + 4.22) * $x);
	$AbsY = $TopMargin + ($LabelHeight * $y);
	
	// Fudge the Start 3mm inside the label to avoid alignment errors
	$pdf->SetXY($AbsX+3,$AbsY+3);
	$pdf->MultiCell($LabelWidth-8,4.5,$Data);
	
	return;
}

function formatPhone($phone, $lang) {
	//046-2880941
	$phone = str_replace(" ", "", $phone);
	if($phone and strlen($phone) == 11) {
		if($lang=="en") {
			$content = "+46-" . substr($phone, 1, 2) . " ";
		} else {
			$content = substr($phone, 0, 4);
		}
		$content .= substr($phone, 4, 3) . " " . substr($phone, 7, 2) . " " . substr($phone, 9, 2);
	} elseif($phone) {
		$content = $phone;
	}
	return $content;
}

function getHamtstalle($lucatId) {
	$res = $GLOBALS["TYPO3_DB"]->exec_SELECTquery("hs", "tx_institutioner_institution I INNER JOIN tx_institutioner_hus H ON I.hid = H.uid", "I.lucatid='$lucatId' AND I.deleted=0", "", "") or die("1366; ".mysql_error());
	while ($row = $GLOBALS["TYPO3_DB"]->sql_fetch_assoc($res)) {		
		$hs = $row["hs"];
	}
	$GLOBALS["TYPO3_DB"]->sql_free_result($res);
	return $hs;
}
?>