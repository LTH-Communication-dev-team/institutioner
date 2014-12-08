<?php

// Exit, if script is called directly (must be included via eID in index_ts.php)
if (!defined ('PATH_typo3conf')) die ('Could not access this script directly!');

if (t3lib_extMgm::isLoaded('fpdf'))    {
    require(t3lib_extMgm::extPath('fpdf').'class.tx_fpdf.php');
}

require_once(PATH_tslib.'class.tslib_fe.php');
require_once(PATH_t3lib.'class.t3lib_page.php');
require_once(PATH_t3lib.'class.t3lib_tstemplate.php');
require_once(PATH_t3lib.'class.t3lib_cs.php');
require_once(PATH_t3lib.'class.t3lib_userauth.php');
require_once(PATH_tslib.'class.tslib_feuserauth.php');
require_once(PATH_tslib.'class.tslib_content.php');

$TSFEclassName = t3lib_div::makeInstance('tslib_fe');
$id = isset($HTTP_GET_VARS['id'])?$HTTP_GET_VARS['id']:0;

$GLOBALS['TSFE'] = new $TSFEclassName($TYPO3_CONF_VARS, $id, '0', 1, '','','','');
//$GLOBALS['TSFE']->connectToMySQL();
$GLOBALS['TSFE']->initFEuser();
$GLOBALS['TSFE']->fetch_the_id();
$GLOBALS['TSFE']->getPageAndRootline();
$GLOBALS['TSFE']->initTemplate();
$GLOBALS['TSFE']->tmpl->getFileName_backPath = PATH_site;
$GLOBALS['TSFE']->forceTemplateParsing = 1;
$GLOBALS['TSFE']->getConfigArray();

// Initialize FE user object:
$feUserObj = tslib_eidtools::initFeUser();
$usergroup = $feUserObj->user['usergroup'];

// Connect to database:
tslib_eidtools::connectDB();

$i=0;
$query = htmlspecialchars(t3lib_div::_GP("query"));
$scope = htmlspecialchars(t3lib_div::_GP("scope"));
$action = htmlspecialchars(t3lib_div::_GP("action"));
$lang = htmlspecialchars(t3lib_div::_GP("lang"));
$sorting = htmlspecialchars(t3lib_div::_GP("sorting"));
$sortorder = htmlspecialchars(t3lib_div::_GP("sortorder"));
$unfold = htmlspecialchars(t3lib_div::_GP("unfold"));
$hide_search = htmlspecialchars(t3lib_div::_GP("hide_search"));
$html_template = htmlspecialchars(t3lib_div::_GP("html_template"));
$imagefolder = htmlspecialchars(t3lib_div::_GP("imagefolder"));
$addpeople = htmlspecialchars(t3lib_div::_GP("addpeople"));
$removepeople = htmlspecialchars(t3lib_div::_GP("removepeople"));
$firstpeople = htmlspecialchars(t3lib_div::_GP("firstpeople"));

$imageSokvag = "/fileadmin/user_portraits/";

//echo "scope=$scope, action=$action, query=$query, lang=$lang, sorting=$sorting, sortorder=$sortorder";
switch($action) {
	case "listaInstitutioner":
		echo listaInstitutioner($scope, $action, $query, $lang, $sorting, $lastaction, $unfold, $hide_search);
		break;
	case "listaInstitutionerBokstav":
		echo listaInstitutioner($scope, $action, $query, $lang, $sorting, $lastaction, $unfold, $hide_search);
		break;
	case "listaEndastInstitutioner":
		echo listaInstitutioner($scope, $action, $query, $lang, $sorting, $lastaction, $unfold, $hide_search);
		break;	
	case "expandInst":
		echo expandInst($uid, $husid, $lucatid, $lang, $maininst, $unfold);
		break;
	case "collapseInst":
		echo collapseInst($uid, $husid, $lucatid, $lang, $maininst, $unfold);
		break;		
	case "listaPersoner":
		//echo listaBokstaver($scope, $query, $lang);	
		echo listaPersoner(data, query);
		break;		
	case "listaPersonerLucat":
		//echo listaBokstaver($scope, $query, $lang);	
		//$sorting .= ' COLLATE utf8_swedish_ci ';
		//$sortorder = 'title';
		//echo 'test';
		echo listaPersoner($scope, $action, $query, $imageSokvag, $lang, $sorting, $sortorder, $unfold, $hide_search, $html_template,$imagefolder,$addpeople,$removepeople,$firstpeople);
                break;
        case "listaPerson":
		//echo listaBokstaver($scope, $query, $lang);	
		echo listaPerson($query,$html_template,$imagefolder);
		break;
	case "listaPersonerHus":
		//echo listaBokstaver($scope, $query, $lang);	
		echo listaPersonerHus($scope, $action, $query, $imageSokvag, $lang);
		break;		
	case "listaPersonerBokstav":
		//echo listaBokstaver($scope, $query, $lang);	
		echo listaPersoner($scope, $action, $query, $imageSokvag, $lang, $sorting, $sortorder, $unfold, $hide_search, $html_template, $imagefolder,$addpeople,$removepeople,$firstpeople);
		break;
	case "listaPersonerSokning":
		//echo listaBokstaver($scope, $query, $lang);	
		echo listaPersoner($scope, $action, $query, $imageSokvag, $lang, $sorting, $sortorder, $unfold, $hide_search, $html_template, $imagefolder,$addpeople,$removepeople,$firstpeople);
		break;
	case "listaPersonerTjanster":
		echo listaPersoner($scope, $action, $query, $imageSokvag, $lang, $sorting, $sortorder, $unfold, $hide_search, $html_template, $imagefolder,$addpeople,$removepeople,$firstpeople);
		break;		
	case "expandPerson":
		echo expandPerson($uid, $imageSokvag, $lang, $maininst);
		break;
	case "collapsePerson":
		echo collapsePerson($uid, $imageSokvag, $lang);
		break;
	case "fullPerson":
		echo fullPerson($scope, $action, $imageSokvag, $startrecord, $lang);
		break;
	case "listaByggnader":
		echo listaByggnader($query, $lang);
		break;
	case "expandByggnad":
		echo expandByggnad($uid, $lang);
		break;
	case "collapseByggnad":
		echo collapseByggnad($uid, $lang);
		break;		
	case "listaBokstaver":
		echo listaBokstaver($scope, $query, $lang);	
		break;
	case "flashMap":
		echo flashMap($scope, $query, $lang);
		break;
	case "exportChoice":
		echo exportChoice($scope, $action, $query, $imageSokvag, $lang, $sorting, $sortorder, $lastaction);
		break;
	case "exportDepChoice":
		echo exportDepChoice($scope, $action, $query, $imageSokvag, $lang, $sorting, $sortorder, $lastaction);
		break;
	case "titleChoice":
		echo titleChoice($scope, $action, $query, $imageSokvag, $lang, $sorting, $sortorder);
		break;		
	case "exportDo":
		echo exportDo($scope, $action, $query, $imageSokvag, $lang, $sorting, $sortorder, $rvalue, $cvalue, $lextra);
		break;
	case "exportDepDo":
		echo exportDo($scope, $action, $query, $imageSokvag, $lang, $sorting, $sortorder, $rvalue, $cvalue, $lextra);
		break;		
	case "telephoneList":
		echo telephoneList($uid, $scope, $lang);
		break;
        case "loginbox":
		echo loginbox();
		break;	
}

function listaInstitutioner($scope, $action, $query, $lang, $sorting, $lastaction, $unfold, $hide_search) {
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
		$content .= "<select onchange=\"exportChoice('$scope', this.value, '$query', '1', '$lang', '$sorting', '', '$action'); return false;\" name=\"export\" class=\"sortmenu\">";
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

function listaPersoner($scope, $action, $query, $imageSokvag, $lang, $sorting, $sortorder, $unfold, $hide_search, $html_template, $imagefolder,$addpeople,$removepeople,$firstpeople) {
	//echo "scope=$scope, action=$action, query=$query, imageSokvag=$imageSokvag, lang=$lang, sorting=$sorting, sortorder=$sortorder";
	
	$query = trim($query);
	//$query = str_replace(",", "hh", $query);

	$button_up = "/fileadmin/templates/images/shortcuts-up-bronze.png";
	$button_down = "/fileadmin/templates/images/shortcuts-down-bronze.png";
		
	$name_sort = "Namn";
	$title_sort = "Titel";
	$name_button = $button_up;
	$title_button = $button_up;
        
        $i=0;
        $tmpSort = '';
        if($firstpeople) {
            $firstpeople = str_replace(' ', '', $firstpeople);
            $firstpeopleArray = explode(',',$firstpeople);
            foreach ($firstpeopleArray as $value) {
                $tmpSort .= "WHEN username=\"$value\" THEN $i";
                $i++;
            }
            if($tmpSort) {
                $tmpSort = "CASE $tmpSort ELSE 1000000 END,";          
            }
      /*  CASE 
     WHEN username="kans-th0" THEN 1  -- first
     WHEN username="math-reb" THEN 0 -- last
     ELSE 10000
   END,name;*/
        }
	
	switch($sorting) {
	case "title";
		$title_sort = "<b>Titel</b>";
		switch($sortorder) {
			case " ASC":
				$title_sortorder = " DESC";
				$title_button = $button_up;
				break;
			case " DESC":					
				$title_sortorder = " ASC";
				$title_button = $button_down;
				break;
			default:
				$sortorder = " ASC";
				$title_sortorder = " DESC";
				$title_button = $button_up;
				break;
			}
		break;		
	default:
		$sorting = "name";
		$name_sort = "<b>Namn</b>";
		switch($sortorder) {
			case " ASC":
				$name_sortorder = " DESC";
				$name_button = $button_up;
				break;
			case " DESC":					
				$name_sortorder = " ASC";
				$name_button = $button_down;
				break;
			default:
				$sortorder = " ASC";
				$name_sortorder = " DESC";
				$name_button = $button_up;
				break;
		}
		break;
	}

	if($lang) $langArray = getLang($lang);
		
	if($action=="listaPersonerBokstav") {
		if($scope) {
			$res = $GLOBALS["TYPO3_DB"]->exec_SELECTquery("uid", "fe_groups", "tx_institutioner_lucatid IN($scope) AND deleted=0", "", "") or die("268; ".mysql_error());
			while ($row = $GLOBALS["TYPO3_DB"]->sql_fetch_assoc($res)) {
				if($groupid) $groupid .= ",";
				$groupid .= $row["uid"];
			}
			
			$urval = "usergroup IN($groupid) AND ";			
		}
		$urval .= "name LIKE '$query%' AND ";
	} elseif($action=="listaPersonerTjanster") {
		if($query) {
			if(substr($query, -1) == ",") $query = substr($query, 0, strlen($query) -1);
			$urval .= "title IN(SELECT title FROM fe_users WHERE uid IN($query))";
		}
		if(substr($urval, -1) == ",") $urval = substr($urval, 0, strlen($urval) -1);
		$urval .= " AND ";
		//echo $urval;
	}	
	elseif($query) {
		if($scope) {
			$res = $GLOBALS["TYPO3_DB"]->exec_SELECTquery("G1.uid AS avdid, G2.uid AS instid, G1.title, G2.subgroup, G1.tx_institutioner_title_eng AS title_eng", "fe_groups G1 LEFT JOIN fe_groups G2 ON G2.subgroup = G1.uid", "G1.tx_institutioner_lucatid IN($scope) AND G1.deleted=0", "", "") or die("246; ".mysql_error());
			while ($row = $GLOBALS["TYPO3_DB"]->sql_fetch_assoc($res)) {
				if($groupid) $groupid .= ",";
				$subgroup = $row["subgroup"];
				if($subgroup) {
					$groupid .= $row["instid"];
				} else {
					$groupid .= $row["avdid"];
				}
				if($subgroup) $groupid .= ",$subgroup";
				$urval = "usergroup IN($groupid) AND ";
			}
		}
		$urval .= "(name LIKE '%$query%' OR email LIKE '%$query%' OR telephone LIKE '%$query%' OR concat(first_name, ' ', last_name) LIKE '%$query%')";
	} else {
            /*
             * $res = $GLOBALS["TYPO3_DB"]->exec_SELECTquery("f1.uid as uid1,f2.uid as uid2,f3.uid as uid3,f4.uid as uid4,f5.uid as uid5", 
                                "LEFT JOIN fe_groups f2 ON f1.uid = f2.subgroup 
                                    LEFT JOIN fe_groups f3 ON f2.uid = f3.subgroup
                                    LEFT JOIN fe_groups f4 ON f3.uid = f4.subgroup
                                    LEFT JOIN fe_groups f5 ON f4.uid = f5.subgroup",
                                "f1.tx_institutioner_lucatid IN($scope)
                                AND f1.deleted=0",
                                "", "") or die("268; ".mysql_error());
			while ($row = $GLOBALS["TYPO3_DB"]->sql_fetch_assoc($res)) {
				//if($groupid) $groupid .= ",";
				$uid1 = $row["uid1"].'';
                                $uid2 = $row["uid2"].'';
                                $uid3 = $row["uid3"].'';
                                $uid4 = $row["uid4"].'';
                                $uid5 = $row["uid5"].'';
                                if(!in_array($uid1,$groupidArray) && $uid1!='') $groupidArray[] = $uid1;
			}
			
			$urval = "usergroup IN(" . implode(',',$groupidArray) . " AND ";			
		}
                return '$ur
             */
            $groupidArray = array();
            $avdId = '';
            $instId = '';
            if($scope) {
                $res = $GLOBALS["TYPO3_DB"]->exec_SELECTquery("G1.uid AS avdid, G2.uid AS instid, G1.title, G2.subgroup, G1.tx_institutioner_title_eng AS title_eng", "fe_groups G1 LEFT JOIN fe_groups G2 ON G2.subgroup = G1.uid", "G1.tx_institutioner_lucatid IN($scope) AND G1.deleted=0", "", "") or die("246; ".mysql_error());
                while ($row = $GLOBALS["TYPO3_DB"]->sql_fetch_assoc($res)) {
                        /*if($groupid) $groupid .= ",";
                        $subgroup = $row["subgroup"];
                        if($subgroup) {
                                $groupid .= $row["instid"];
                        } else {
                                $groupid .= $row["avdid"];
                        }*/
                    $avdId = $row["avdid"].'';
                    $instId = $row["instid"].'';
                    if(!in_array($avdId,$groupidArray) && $avdId!='') $groupidArray[] = $avdId;
                    if(!in_array($instId,$groupidArray) && $instId!='') $groupidArray[] = $instId;
                        $title = $row["title"];
                        $title_eng = $row["title_eng"];
                        if($lang=="en") $title = $title_eng;
                }
                if($subgroup) $groupid .= ",$subgroup";
                //$urval = "usergroup IN($groupid) AND ";
                //$groupidArray = explode(',',$groupid);
                foreach($groupidArray as $key => $value) {
                        if($urval) $urval .= " OR ";
                        $urval .= "FIND_IN_SET('$value', usergroup)";
                }
                //$urval = "FIND_IN_SET('$groupid', usergroup) AND ";

                $content .= "<div align=\"center\" style=\"width:650px; margin-top:15px;margin-bottom:15px; \">";
//////////////////////////////////////////////////$content .= "<div style=\"font-weight:bold; width:500px;font-size:14px; color:#996633;\">$title</div>";
                if($_SERVER['REMOTE_ADDR'] == "127.0.0.1" OR substr($_SERVER['REMOTE_ADDR'], 0, 7) == "130.235" or 1+1==2) {
                        $telephoneList = "<option value=\"telephoneList;$groupid;$title\">" . $langArray["telephoneList"] . "</option>";
                }
                $content .= "</div>";

                $content .= "<div style=\"clear:both; margin:30px;\"></div>";		
            }
	}
	$i=0;
        
        $tmpArray = array();
        if(trim($addpeople)) {
            $addpeople = str_replace(' ', '', $addpeople);
            $addpeopleArray = explode(',',$addpeople);
            foreach($addpeopleArray as $value) {
                $tmpArray[] = "'$value'";
                
            }
            $urval .= " OR username in(".implode(',',$tmpArray) . ")";
        }
        
        $urval = "($urval)";
        
        $tmpArray = array();
        if(trim($removepeople)) {
            $removepeople = str_replace(' ', '', $removepeople);
            $removepeopleArray = explode(',',$removepeople);
            foreach($removepeopleArray as $value) {
                $tmpArray[] = "'$value'";
                
            }
            $urval .= " AND username not in(".implode(',',$tmpArray) . ")";
        }
        
        if($query) $urval .= " AND (name LIKE '%$query%' OR email LIKE '%$query%' OR telephone LIKE '%$query%' OR concat(first_name, ' ', last_name) LIKE '%$query%')";
        
	if($scope or $query) {
            //echo $query;

            //*****************************Status bar begin*********************************************************
            //scope, action, query, startrecord, lang, sorting, sortorder
            
            $content .= "<div class=\"sortrow\">";
            $content .= "<div class=\"tx_institiutioner_sortrow_buttons\">";

            //name
            $content .= "<a href=\"#\" onclick=\"lista('$scope', '$action', '$query', '1', '$lang', 'name', '$name_sortorder', '', '$unfold', '$hide_search', '$html_template', '$imagefolder','$addpeople','$removepeople'); return false;\">";
            $content .= "$name_sort";
            $content .= "<img onclick=\"lista('$scope', '$action', '$query', '1', '$lang', 'name', '$name_sortorder', '', '$unfold', '$hide_search', '$html_template', '$imagefolder','$addpeople','$removepeople'); return false;\" src=\"$name_button\" border=\"0\" class=\"sortbutton\" />";
            $content .= "</a>";
            
            //title
            $content .= "<a href=\"#\" onclick=\"lista('$scope', '$action', '$query', '1', '$lang', 'title', '$title_sortorder', '', '$unfold', '$hide_search', '$html_template', '$imagefolder','$addpeople','$removepeople'); return false;\">";
            $content .= "$title_sort";
            $content .= "<img onclick=\"lista('$scope', '$action', '$query', '1', '$lang', 'title', '$title_sortorder', '', '$unfold', '$hide_search', '$html_template', '$imagefolder','$addpeople','$removepeople'); return false;\" src=\"$title_button\" border=\"0\" class=\"sortbutton\" />";
            
            $content .= "</a>";
            
            $content .= "</div><div>";
            if($_SERVER['REMOTE_ADDR'] == "127.0.0.1" OR substr($_SERVER['REMOTE_ADDR'], 0, 7) == "130.235" or 1+1==2) {
                $content .= "<select id=\"exportMenu\" onchange=\"exportChoice('$scope', this.value, '$query', '1', '$lang', '$sorting', '$sortorder', '$action'); return false;\" name=\"export\" class=\"sortmenu\">";
                $content .= "<option value=\"\"";
                $content .= " selected";
                $content .= ">" . $langArray["export"] . "</option>";
                $content .= $telephoneList;
                $content .= "<option value=\"exportChoice\"";
                $content .= ">" . $langArray["chooseFormat"] . "</option>";
                $content .= "</select>";
            }

            $content .= "</div>";
            $content .= "</div>";

            $content .= "<div style=\"padding-left:10px; color: #8E5614; \">###HITS###</div>";
            //*****************************Status bar ends*********************************************************

            // Get the template
            $cObj = t3lib_div::makeInstance('tslib_cObj');
            //$templateFile = 'contact_with_image_and_ingress.html';
            $templateHtml = $cObj->fileResource("fileadmin/templates/institutioner/$html_template");
            // Extract subparts from the template
            $subpart = $cObj->getSubpart($templateHtml, '###TEMPLATE###');
            $i=0;
            $markerArray = array();
            
            $res = $GLOBALS["TYPO3_DB"]->exec_SELECTquery("uid, name, email, telephone, username, usergroup, title, image, www, first_name, last_name", "fe_users", $urval . " AND deleted=0 AND tx_institutioner_lth_search=1", "", "$tmpSort$sorting$sortorder") or die("282; ".mysql_error().$urval);
            while ($row = $GLOBALS["TYPO3_DB"]->sql_fetch_assoc($res)) {
                $uid = $row["uid"];
                $title = ucfirst($row["title"]);
                //$title_eng = $row["title_eng"];
                $name = $row["name"];
                $email = strtolower($row["email"]);
                $telephone = formatPhone($row["telephone"], $lang);
                $username = $row["username"];
                $usergroup = $row["usergroup"];
                $image = $row["image"];
                $www = $row["www"];
                $first_name = $row["first_name"];
                $last_name = $row["last_name"];
                if($usergroup) $displayGroup = getFeGroup($usergroup, $lang, $action);
                /*if($name) {
                        if($unfold) {
                                $content .= returnDivPerson($uid, $name, $email, $telephone, $lang, $displayGroup, $action, $title, $unfold);
                        } else {
                                $content .= returnDivPersonExtended($uid, $imageSokvag, $lang, $original_action, $hide_search);
                        }
                        $i++;
                }*/
                //Images
                $path = $_SERVER['DOCUMENT_ROOT'];
                if($imagefolder && stristr($templateHtml,'###IMAGE###')) {
                    if(is_file("$path/$imagefolder" . "$username.jpg")) {
                        $image = "/$imagefolder" . "$username.jpg";
                    }
                }
                if(!$image) $image = "/typo3conf/ext/institutioner/graphics/placeholder.gif";
                if($www) {
                    $www = "Hemsida: $www<br />";
                } else {
                    $www = $unfold . str_replace(' ', '_', $first_name) . '_' . str_replace(' ', '_', $last_name);
                }
                // Fill marker array
                $markerArray['###NAME###'] = "$first_name $last_name";
                $markerArray['###TITLE###'] = $title;
                $markerArray['###TELEPHONE###'] = $telephone;
                $markerArray['###EMAIL###'] = $email;
                $markerArray['###SUBJECT###'] = $displayGroup;
                $markerArray['###IMAGE###'] = $image;
                $markerArray['###WWW###'] = $www;
                
                // Create the content by replacing the content markers in the template
                $content .= $cObj->substituteMarkerArray($subpart, $markerArray);
                $i++;
            }
            $GLOBALS["TYPO3_DB"]->sql_free_result($res);
	}

	//if($i > 1) {
		$content = str_replace("###HITS###", $langArray["results"] . ": $i " . $langArray["hits"], $content.$urval);
	//}
	//echo $urval;and $action != "listaPersonerLucat"

        
	return $content;
}

function listaPerson($query,$html_template,$imagefolder) {
	//echo "scope=$scope, action=$action, query=$query, imageSokvag=$imageSokvag, lang=$lang, sorting=$sorting, sortorder=$sortorder";
	
	$query = trim($query);
	//$query = str_replace(",", "hh", $query);

	if($lang) $langArray = getLang($lang);
		
        // Get the template
        $cObj = t3lib_div::makeInstance('tslib_cObj');
        //$templateFile = 'contact_with_image_and_ingress.html';
        $templateHtml = $cObj->fileResource("fileadmin/templates/institutioner/$html_template");
        // Extract subparts from the template
        $subpart = $cObj->getSubpart($templateHtml, '###TEMPLATE###');
        $i=0;
        $markerArray = array();

        $res = $GLOBALS["TYPO3_DB"]->exec_SELECTquery("uid, name, email, telephone, username, usergroup, title, image, first_name, last_name", "fe_users", "concat(first_name,'_',last_name)='$query'", "", "") or die("282; ".mysql_error().$urval);
        while ($row = $GLOBALS["TYPO3_DB"]->sql_fetch_assoc($res)) {
            $uid = $row["uid"];
            $title = ucfirst($row["title"]);
            //$title_eng = $row["title_eng"];
            $name = $row["name"];
            $email = strtolower($row["email"]);
            $telephone = formatPhone($row["telephone"], $lang);
            $username = $row["username"];
            $usergroup = $row["usergroup"];
            $image = $row["image"];
            $first_name = $row["first_name"];
            $last_name = $row["last_name"];
                
            if($usergroup) $displayGroup = getFeGroup($usergroup, $lang, $action);
            
            //Images
            $path = $_SERVER['DOCUMENT_ROOT'];
            if($imagefolder && stristr($templateHtml,'###IMAGE###')) {
                if(is_file("$path/$imagefolder" . "$username.jpg")) {
                    $image = "/$imagefolder" . "$username.jpg";
                }
            }
            if(!$image) $image = "/typo3conf/ext/institutioner/graphics/placeholder.gif";
            if($www) {
                $www = "Hemsida: $www<br />";
            } else {
                $www = "$unfold/$query";
            }
            // Fill marker array
            $markerArray['###NAME###'] = "$first_name $last_name";
            $markerArray['###TITLE###'] = $title;
            $markerArray['###TELEPHONE###'] = $telephone;
            $markerArray['###EMAIL###'] = $email;
            //$markerArray['###SUBJECT###'] = $displayGroup;
            $markerArray['###IMAGE###'] = $image;

            // Create the content by replacing the content markers in the template
            $content .= $cObj->substituteMarkerArray($subpart, $markerArray);
            $i++;
        }
        $GLOBALS["TYPO3_DB"]->sql_free_result($res);
        
        $loginButton = "<p><a href=\"#\" onclick=\"loginbox();return false;\">Logga in</a></p>";
        
        
       
	return $content.$loginButton;
}

function loginbox()
{
    
$conf = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_felogin_pi1.'];
		$conf["templateFile"] = "fileadmin/template/felogin/template.html";
		// Get plugin instance
		$cObj = t3lib_div::makeInstance('tslib_cObj');
		/* @var $cObj tslib_cObj */
		$cObj->start(array(), '');
		$objType = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_felogin_pi1'];
		$content = $cObj->cObjGetSingle($objType, $conf);
		return $content;

}

function listaPersonerHus($scope, $action, $query, $imageSokvag, $lang) {
	//echo "scope=$scope, action=$action, query=$query, lang=$lang, sorting=$sorting";
	if($lang) $langArray = getLang($lang);

	$res = $GLOBALS["TYPO3_DB"]->exec_SELECTquery("F.uid, F.name, F.username, F.email, F.telephone, F.image, F.title AS persontitle, F.www, H.map, G.title, G.tx_institutioner_title_eng, H.husid, H.title AS hus", "fe_users F LEFT JOIN fe_groups G ON G.uid IN(F.usergroup) INNER JOIN tx_institutioner_institution I ON G.tx_institutioner_lucatid = I.lucatid INNER JOIN tx_institutioner_hus H ON I.hid = H.uid", "H.husid='$scope' AND G.deleted = 0 AND F.deleted=0 AND F.tx_institutioner_lth_search=1", "F.name", "") or die("317; ".mysql_error());
	while ($row = $GLOBALS["TYPO3_DB"]->sql_fetch_assoc($res)) {
		$uid = $row["uid"];
		$name = $row["name"];
		$email = strtolower($row["email"]);
		$telephone = $row["telephone"];
		$image = $row["image"];
		$map = $row["map"];
		$husid = $row["husid"];
		$username = $row["username"];
		$persontitle = ucfirst(trim($row["persontitle"]));
		$www = $row["www"];
		$hus = $row["hus"];
		$title = $row["title"];
		$title_eng = $row["title_eng"];
		if($lang=="en") $title = $title_eng;
		if($name) {
			$content .= returnDivPerson($uid, $name, $email, $telephone, $lang, $displayGroup, $action, $title, "", "");
			$i++;
		}
	}
	$GLOBALS["TYPO3_DB"]->sql_free_result($res);
	
	if($i>1) {
		$content = "<div align=\"center\" style=\"font-weight:bold; width:500px;font-size:14px; color:#996633; margin:10px;\">$hus (" . $langArray["results"] . ": $i " . $langArray["hits"] . ")</div>" . $content;
	}
	
	return $content;
}

function returnDivPerson($uid, $name, $email, $telephone, $lang, $usergroup, $action, $title, $unfold) {
	
	$content .= "<div id=\"listitem_$uid\" class=\"institution_listitem_normal\" onMouseOver=\"listitem_mouseOver('listitem_$uid','$lang');\" onMouseOut=\"listitem_mouseOut('listitem_$uid');\" 
	onClick=\"listitem_click('listitem_$uid', 'expandPerson', '$uid', '', '$action', '$lang', '$unfold');\">";
	
		$content .= "<div style=\"margin:5px;\" class=\"institution_listitem_header\">$name</div>";
				
		$content .= "<div style=\"float:left; width:225px;margin:5px; line-height:1.5em;\"><a href=\"mailto:$email\">$email</a><br />";
		$content .= "$telephone</div>";
		
		$content .= "<div style=\"float:left; width:225px;margin:5px; line-height:1.5em;\">$title<br />";
		$content .= "$usergroup </div>";
		
	$content .= "</div>";
	
	$content .= "<div class=\"tx_institutioner_gra_streck\"></div>";
	
	return $content;
}

function returnDivPersonExtended($uid, $imageSokvag, $lang, $original_action, $hide_search) {
	
	if($lang) $langArray=getLang($lang);
	
	$res = $GLOBALS["TYPO3_DB"]->exec_SELECTquery("U.name, U.username, U.email, U.telephone, U.image, U.title AS persontitle, U.www, G.uid, G.title, G.description, G.tx_institutioner_title_eng AS title_eng, G.tx_institutioner_lucatid, H.map, H.husid", "fe_users U LEFT JOIN fe_groups G ON G.uid IN(U.usergroup) LEFT JOIN tx_institutioner_institution I ON G.tx_institutioner_lucatid = I.lucatid LEFT JOIN tx_institutioner_hus H ON I.hid = H.uid", "U.uid = $uid AND U.deleted=0 AND U.tx_institutioner_lth_search=1", "", "") or die("335".mysql_error());
	
	$row = $GLOBALS["TYPO3_DB"]->sql_fetch_assoc($res);
	
	$name = $row["name"];
	$email = strtolower($row["email"]);
	$telephone = formatPhone($row["telephone"], $lang);
	$image = $row["image"];
	$groupid = $row["uid"];
	$title = $row["title"];
	$title_eng = $row["title_eng"];
	$tx_institutioner_lucatid = $row["tx_institutioner_lucatid"];
	$map = $row["map"];
	$husid = $row["husid"];
	$username = $row["username"];
	$persontitle = ucfirst(trim($row["persontitle"]));
	$www = $row["www"];
	
	//hh
	$content .= "<div id=\"listitem_$uid\" style=\"margin-left:20px;\">";
	
	if($image) {
		$imageArray = explode(",", $image);
		$image = $imageArray[0];
		$dimensions = getimagesize("http://localhost$imageSokvag$image");
		$width = $dimensions[0];
		$height = $dimensions[1];
		$maxHeight = 100;
		$divFactor = $maxHeight /$height;
		if($height > $maxHeight) {
			$width = $width * $divFactor;
			$height = $height * $divFactor;
		}
		$content .= "<div style=\"margin:5px; width:100px; float:left;\"><img src=\"$imageSokvag$image\" height=\"$height\" width=\"$width\" border=\"0\" /></div>";
	}

	$content .= "<div style=\"margin:5px; width:225px; float:left;line-height:1.5em;\"><b>$name</b>, $persontitle<br />$title<br /><a href=\"mailto:$email\">$email</a><br />";
	$content .= "$telephone</div>";
	if($www) {
		if(!strstr($www, "http://")) $www = "http://" . $www;
		$content .= "<br /><a href=\"$www\">" . $langArray["personal_web"] . "</a></div>";
	} else {
	$content .= "<div style=\"margin-top:23px; width:225px; float:left;\"><a href=\"#\" onclick=\"lista('$uid', 'fullPerson', '', '1', '$lang', '$sorting', '$sortorder', '$action', '', '$hide_search'); return false;\">" . $langArray["personal_web"] . "<br />" . $langArray["with_publications"] . "</a></div>";
	}
	$content .= "</div>";
			
	//$content .= "<div style=\"float:left; width:225px;margin:5px; line-height:1.5em;\"></div>";
	
	//$content .= "<div style=\"width:225px;margin:5px; line-height:1.5em;\">$title<br />";
	//$content .= "</div>";
		
	$content .= "</div>";
	
	$content .= "<div class=\"tx_institutioner_gra_streck\" style=\"clear:both;\"></div>";
	
	$GLOBALS["TYPO3_DB"]->sql_free_result($res);
	
	return $content;
}

function expandPerson($uid, $imageSokvag, $lang, $original_action) {
	
	if($lang) $langArray=getLang($lang);
	
	$res = $GLOBALS["TYPO3_DB"]->exec_SELECTquery("U.name, U.username, U.email, U.telephone, U.image, U.title AS persontitle, U.www, G.uid, G.title, G.description, G.tx_institutioner_title_eng AS title_eng, G.tx_institutioner_lucatid, H.map, H.husid", "fe_users U LEFT JOIN fe_groups G ON G.uid IN(U.usergroup) LEFT JOIN tx_institutioner_institution I ON G.tx_institutioner_lucatid = I.lucatid LEFT JOIN tx_institutioner_hus H ON I.hid = H.uid", "U.uid = $uid AND U.deleted=0 AND U.tx_institutioner_lth_search=1", "", "") or die("335".mysql_error());
	
	$row = $GLOBALS["TYPO3_DB"]->sql_fetch_assoc($res);
	
	$name = $row["name"];
	$email = strtolower($row["email"]);
	$telephone = $row["telephone"];
	$image = $row["image"];
	$groupid = $row["uid"];
	$title = $row["title"];
	$title_eng = $row["title_eng"];
	$tx_institutioner_lucatid = $row["tx_institutioner_lucatid"];
	$map = $row["map"];
	$husid = $row["husid"];
	$username = $row["username"];
	$persontitle = ucfirst(trim($row["persontitle"]));
	$www = $row["www"];

	//Rubrik
	$content .= "<div class=\"institution_listitem_header\" style=\"float:left; width:245px; margin:5px;\">";
	$content .= $name;
	$content .= "</div>
	<div style=\"float:left; width:45px;\">
	<img src=\"/typo3conf/ext/institutioner/graphics/dolj_$lang.gif\" border=\"0\" onClick=\"listitem_click('listitem_$uid','collapsePerson','$uid','','','$lang','$unfold');\" />
	</div>";
	
	$content .= "<div style=\"clear:both;\"></div>";
	
	//Text
	$content .= "<div style=\"float:left; width:225px; margin:5px; line-height:1.5em;\"><a href=\"mailto:$email\">$email</a><br />";
	$content .= "$telephone";
	if($www) {
		if(!strstr($www, "http://")) $www = "http://" . $www;
		$content .= "<br /><a href=\"$www\">" . $langArray["personalHomepage"] . "</a>";
	}
	if($image) $content .= "<br /><img src=\"$imageSokvag$image\" border=\"0\" />";
	$content .= "</div>";
	
	$content .= "<div style=\"float:left; width:225px; margin:5px; line-height:1.5em;\">$persontitle<br />";
	if($original_action != "listaPersonerLucat") {
		$content .= "<a href=\"#\" onClick=\"lista('$tx_institutioner_lucatid', 'listaPersonerLucat', '', '', '$lang', '$sorting', '$sortorder', '$action', '', '$hide_search'); return false;\">$title</a><br />";
	} else {
		$content .= "$title<br />";	
	}
	$content .= "<a href=\"#\" onclick=\"lista('$uid', 'fullPerson', '', '1', '$lang', '$sorting', '$sortorder', '$action', '', '$hide_search'); return false;\"><br />" . $langArray["publications"] . "</a></div>";
	
	//Karta<br /> $name
	if($husid) {
	$content .= "<div class=\"tx_institiutioner_mapdisplay\" align=\"center\" style=\"float:left; width:150px; background-color:#ffffff; line-height:1.5em;\" onClick=\"lista('listaPersoner', 'flashMap', '$husid', '', '$lang', '$sorting', '$sortorder', '$action', '', '$hide_search');\">";
	$content .= "<img src=\"/typo3conf/ext/institutioner/graphics/" . $husid . "_small_map.gif\" border=\"0\" height=\"150\" width=\"150\" /><br />";
	$content .= $langArray["largemap"] . "</div>";
	}
	
	$GLOBALS["TYPO3_DB"]->sql_free_result($res);
	
	return $content;
}

function collapsePerson($uid, $imageSokvag, $lang) {
	if($lang) $langArray=getLang($lang);
	
	$res = $GLOBALS["TYPO3_DB"]->exec_SELECTquery("name, usergroup, email, telephone, image, title", "fe_users", "uid = $uid AND deleted=0 AND tx_institutioner_lth_search=1", "", "") or die("255; ".mysql_error());
	$row = $GLOBALS["TYPO3_DB"]->sql_fetch_assoc($res);
	
	$name = $row["name"];
	$title = ucfirst($row["title"]);
	$usergroup = $row["usergroup"];
	$email = strtolower($row["email"]);
	$telephone = $row["telephone"];
	$image = $row["image"];
	
	if($usergroup) $displayGroup = getFeGroup($usergroup, $lang, $action);
	
	if($image) $imageDisplay = "<img src=\"$imageSokvag$image\" border=\"0\" />";
	
	$content .= "<div style=\"margin:5px;\" class=\"institution_listitem_header\"><a href=\"#\" onclick=\"lista('$uid', 'fullPerson', '', '', '$lang', '$sorting', '$sortorder', '$action', '', '$hide_search'); return false;\">$name</a></div>";
	
	if($image) $content .= "<div style=\"float:left\">$imageDisplay</div>";
	
	$content .= "<div style=\"float:left; width:225px; margin:5px; line-height:1.5em;\"><a href=\"mailto:$email\">$email</a><br />";
	$content .= "$telephone</div>";

	$content .= "<div style=\"float:left; width:225px; margin:5px; line-height:1.5em;\">$title<br />";
	$content .= $displayGroup;
	$content .= "</div>";
	
	$GLOBALS["TYPO3_DB"]->sql_free_result($res);
	
	return $content;
}

function listaByggnader($query, $lang) {
	
	if($lang) $langArray = getLang($lang);
	
	if($query) {
		$urval = "(title LIKE '%$query%' OR adress LIKE '%$query%' OR hs LIKE '%$query%') AND ";
	}
	//echo $urval;
	
	$res = $GLOBALS["TYPO3_DB"]->exec_SELECTquery("uid, title, adress, hs, husid, image, webbadress", "tx_institutioner_hus", $urval . "deleted=0", "", "title") or die("420; ".mysql_error());

	while ($row = $GLOBALS["TYPO3_DB"]->sql_fetch_assoc($res)) {
		$uid = $row["uid"];
		$title = $row["title"];
		$adress = $row["adress"];
		$hs = $row["hs"];
		$husid = $row["husid"];
		$image = $row["image"];
		$webbadress = $row["webbadress"];

		if($title) {
			$content .= returnDivByggnad($uid, $title, $adress, $hs, $husid, $image, $lang, $webbadress, $langArray);
		}
	}
	
	$GLOBALS["TYPO3_DB"]->sql_free_result($res);

	return $content;
}

function returnDivByggnad($uid, $title, $adress, $hs, $husid, $image, $lang, $webbadress, $langArray) {

	$content .= "<div id=\"listitem_$uid\" class=\"institution_listitem_normal\" onMouseOver=\"listitem_mouseOver('listitem_$uid','$lang');\" onMouseOut=\"listitem_mouseOut('listitem_$uid');\" onClick=\"listitem_click('listitem_$uid','expandByggnad','$uid','$husid','','$lang','$unfold');\">";
	
		$content .= "<div style=\"float:left; width:225px; margin:5px; line-height:1.5em;\"><span class=\"institution_listitem_header\">$title</span><br />";
		if($webbadress) $content .= "<a href=\"$webbadress\">" . $langArray["homepage"] . "</a><br />";
		$content .= "</div>"; 
		
		$content .= "<div style=\"float:left; width:225px; margin:5px; line-height:1.5em;\">&nbsp;<br />";
		$content .= "$adress<br />";
		$content .= $langArray["registeredaddress"] . ": $hs</div>";
		
		if($image) {
			$content .= "<div style=\"float:left; width:100px; margin:5px;\">";
			if($image) {
				$content .= "<img src=\"/typo3conf/ext/institutioner/graphics/husbilder/$image\" border=\"0\" height=\"58\" width=\"75\" />";
			} else {
				$content .= "<img src=\"/typo3conf/ext/institutioner/graphics/transpix.gif\" border=\"0\" height=\"58\" width=\"75\" />";
			}
			$content .= "</div>";
		}
	
	$content .= "</div>";
	
	$content .= "<div class=\"tx_institutioner_gra_streck\"></div>";

	return $content;
}

function expandByggnad($uid, $lang) {
	if($lang) $langArray=getLang($lang);
	
	$res = $GLOBALS["TYPO3_DB"]->exec_SELECTquery("uid, title, adress, hs, husid, image, webbadress, map", "tx_institutioner_hus", "uid = $uid AND deleted=0", "", "") or die("409".mysql_error());
	$row = $GLOBALS["TYPO3_DB"]->sql_fetch_assoc($res);
	$uid = $row["uid"];$row["title"];
	$adress = $row["adress"];
	$hs = $row["hs"];
	$husid = $row["husid"];
	$image = $row["image"];
	$map = $row["map"];
	$webbadress = $row["webbadress"];

	$content .= "<div style=\"float:left; width:245px; margin:5px;\" class=\"institution_listitem_header\">$title</div>";
	$content .= "<div style=\"float:left; width:45px;\"><img src=\"/typo3conf/ext/institutioner/graphics/dolj_$lang.gif\" border=\"0\" onClick=\"listitem_click('listitem_$uid','collapseByggnad','$uid','','','$lang','$unfold');\" /></div>";
	
	$content .= "<div style=\"clear:both;\"></div>";

	$content .= "<div style=\"float:left; width:225px; margin:5px; line-height:1.5em;\">";
	if($webbadress) $content .= "<a href=\"$webbadress\">" . $langArray["homepage"] . "</a><br />";
	//New
	$content .= "<a href=\"/institutioner\" onClick=\"lista('$husid', 'listaPersonerHus', '', '', '$lang', '$sorting', '$sortorder', '$action', '', '$hide_search'); return false;\">" . $langArray["listofpersonelhouse"] . "</a>";
			
			
	$content .= "</div>";
	
	$content .= "<div style=\"float:left; width:225px; margin:5px; line-height:1.5em;\">";
		$content .= "$adress;<br />";
		$content .= $langArray["registeredaddress"] . ": $hs";
	$content .= "</div>";
	
	$content .= "<div align=\"center\" class=\"institution_listitem_image\" style=\"float:left; width:150px; line-height:1.5em;\" onClick=\"lista('listaByggnader', 'flashMap', '$husid', '', '$lang', '$sorting', '$sortorder', '$action', '', '$hide_search');\">";
		$content .= "<img src=\"/typo3conf/ext/institutioner/graphics/" . $husid . "_small_map.gif\" border=\"0\" height=\"150\" width=\"150\" /><br />";
		$content .= $langArray["largemap"];
	$content .= "</div>";
	
	$GLOBALS["TYPO3_DB"]->sql_free_result($res);
	
	return $content;
}

function collapseByggnad($uid, $lang) {
	if($lang) $langArray=getLang($lang);
	
	$res = $GLOBALS["TYPO3_DB"]->exec_SELECTquery("uid, title, adress, hs, husid, image, webbadress,  map", "tx_institutioner_hus", "uid = $uid AND deleted=0", "", "") or die("188".mysql_error());
	$row = $GLOBALS["TYPO3_DB"]->sql_fetch_assoc($res);
	$uid = $row["uid"];
	$title = $row["title"];
	$adress = $row["adress"];
	$hs = $row["hs"];
	$husid = $row["husid"];
	$image = $row["image"];
	$webbadress = $row["webbadress"];

	$content .= "<div style=\"float:left; width:225px; margin:5px; line-height:1.5em;\"><span class=\"institution_listitem_header\">$title</span><br />";
	if($webbadress) $content .= "<a href=\"$webbadress\">" . $langArray["homepage"] . "</a>";
	$content .= "</div>"; 
		
	$content .= "<div style=\"float:left; width:225px; margin:5px; line-height:1.5em;\">&nbsp;<br />";
	$content .= "$adress<br />";
	$content .= $langArray["registeredaddress"] . ": $hs</div>";
	
	$content .= "<div style=\"float:left; width:100px; margin:5px;\">";
	if($image) {
		$content .= "<img src=\"/typo3conf/ext/institutioner/graphics/husbilder/$image\" border=\"0\" height=\"58\" width=\"75\" />";
	} else {
		$content .= "<img src=\"/typo3conf/ext/institutioner/graphics/transpix.gif\" border=\"0\" height=\"58\" width=\"75\" />";
	}
	$content .= "</div>";
	
	$GLOBALS["TYPO3_DB"]->sql_free_result($res);
	
	return $content;
}

function getFeGroup($usergroup, $lang, $action) {
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
				$dimensions = getimagesize("http://localhost$imageSokvag$imageItem");
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

function listaBokstaver($scope, $query, $lang) {
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

function getLang($lang) {
	$xmlpath = "http://localhost/typo3conf/ext/institutioner/pi1/locallang.xml";
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

function getTitles() {
	$xmlpath = "http://localhost/typo3conf/ext/institutioner/pi1/titles.xml";
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

function exportChoice($scope, $action, $query, $imageSokvag, $lang, $sorting, $sortorder, $lastaction) {
	//echo "$scope, $action, $query, $imageSokvag, $lang, $sorting, $sortorder";
	$content = "
	<table style=\"font-size:1.1em; margin-bottom:50px; margin-top:70px;\" width=\"700\" border=\"0\" cellspacing=\"3\" cellpadding=\"0\">
			  <tr>
		<td colspan=\"3\" class=\"sortrow\" style=\"font-size:1em;\"><b>&nbsp;&nbsp;V&auml;lj exportformat</b>
		</td>
		</tr>
		  <tr>
		<td colspan=\"3\">&nbsp;
		</td>
		</tr>
	  <tr>
		<td> <input onclick=\"disableGroup(this.value, 'txtHeader');\" type=\"radio\" name=\"rblExportFormat\" id=\"rblExportFormat_pdfList\" value=\"cblPdfList\" checked />
	  Lista i pdf-format</td>
		<td><input type=\"checkbox\" name=\"cblPdfList\" id=\"cblPdfList_title\" value=\"title\" checked />
		  Titel
			<input type=\"checkbox\" name=\"cblPdfList\" id=\"cblPdfList_name\" value=\"name\" checked />
	Namn
	<input type=\"checkbox\" name=\"cblPdfList\" id=\"cblPdfList_dep\" value=\"dep\" checked />
	Inst/avd
	<input type=\"checkbox\" name=\"cblPdfList\" id=\"cblPdfList_telephone\" value=\"telephone\" checked />
	telefon
	<input type=\"checkbox\" name=\"cblPdfList\" id=\"cblPdfList_email\" value=\"email\" checked />
	email
	</td>
	<td>
	Rubrik: 
	<input type=\"text\" name=\"txtHeader\" id=\"txtHeader\" /></td>
	  </tr>
	  
	  <tr>
		<td> <input onclick=\"disableGroup(this.value, '');\" type=\"radio\" name=\"rblExportFormat\" id=\"rblExportFormat\" value=\"cblTabSep\" />
	  Tab-separerad textfil</td>
		<td><input type=\"checkbox\" name=\"cblTabSep\" id=\"cblTabSep_title\" value=\"title\" />
		  Titel
			<input type=\"checkbox\" name=\"cblTabSep\" id=\"cblTabSep_name\" value=\"name\" />
	Namn
	<input type=\"checkbox\" name=\"cblTabSep\" id=\"cblTabSep_dep\" value=\"dep\" />
	Inst/avd
	<input type=\"checkbox\" name=\"cblTabSep\" id=\"cblTabSep_telephone\" value=\"telephone\" />
	telefon
	<input type=\"checkbox\" name=\"cblTabSep\" id=\"cblTabSep_email\" value=\"email\" />
	email</td><td></td>
	  </tr>
	  
	  <tr>
		<td> <input onclick=\"disableGroup(this.value, '');\" type=\"radio\" name=\"rblExportFormat\" id=\"rblExportFormat\" value=\"cblKommaSep\" />
	  Komma-separerad textfil</td>
		<td><input type=\"checkbox\" name=\"cblKommaSep\" id=\"cblKommaSep_title\" value=\"title\" />
		  Titel
			<input type=\"checkbox\" name=\"cblKommaSep\" id=\"cblKommaSep_name\" value=\"name\" />
	Namn
	<input type=\"checkbox\" name=\"cblKommaSep\" id=\"cblKommaSep_dep\" value=\"dep\" />
	Inst/avd
	<input type=\"checkbox\" name=\"cblKommaSep\" id=\"cblKommaSep_telephone\" value=\"telephone\" />
	telefon
	<input type=\"checkbox\" name=\"cblKommaSep\" id=\"cblKommaSep_email\" value=\"email\" />
	email</td><td></td>
	  </tr>
	  
	 <tr>
		<td> <input onclick=\"disableGroup(this.value, '');\" type=\"radio\" name=\"rblExportFormat\" id=\"rblExportFormat\" value=\"cblCsv\" />
	  CSV-fil</td>
		<td><input type=\"checkbox\" name=\"cblCsv\" id=\"cblCsv_title\" value=\"title\" />
		  Titel
			<input type=\"checkbox\" name=\"cblCsv\" id=\"cblCsv_name\" value=\"name\" />
	Namn
	<input type=\"checkbox\" name=\"cblCsv\" id=\"cblCsv_dep\" value=\"dep\" />
	Inst/avd
	<input type=\"checkbox\" name=\"cblCsv\" id=\"cblCsv_telephone\" value=\"telephone\" />
	telefon
	<input type=\"checkbox\" name=\"cblCsv\" id=\"cblCsv_email\" value=\"email\" />
	email</td><td></td>
	  </tr>
	  
	  <tr>
		<td> <input onclick=\"disableGroup(this.value, 'txtLabelExtra');\" type=\"radio\" name=\"rblExportFormat\" id=\"rblExportFormat\" value=\"cblLabels\" />
	  Etiketter (avery 7160)</td>
		<td><input type=\"checkbox\" name=\"cblLabels\" id=\"cblLabels_title\" value=\"title\" />
		  Titel
			<input type=\"checkbox\" name=\"cblLabels\" id=\"cblLabels_name\" value=\"name\" />
	Namn
	<input type=\"checkbox\" name=\"cblLabels\" id=\"cblLabels_dep\" value=\"dep\" />
	Inst/avd
	<input type=\"checkbox\" name=\"cblLabels\" id=\"cblLabels_hs\" value=\"hs\" />
	H&auml;mtst&auml;lle</td><td>
	Extra: 
	<input type=\"text\" name=\"txtLabelExtra\" id=\"txtLabelExtra\" /></td>
	  </tr>
	  <tr>
		<td colspan=\"3\">&nbsp;
		</td>
		</tr>
	  <tr>
		<td colspan=\"3\"><input onclick=\"exportDo('$scope', '$action', '$query', '$imageSokvag', '$lang', '$sorting', '$sortorder', '$html_template', '$imagefolder','$addpeople','$removepeople','$firstpeople'); return false;\" type=\"button\" name=\"btnExport\" id=\"btnExport\" value=\"Exportera\" />
		  &nbsp;&nbsp;
		  <input onClick=\"lista('$scope', '$lastaction', '$query', '$imageSokvag', '$lang', '$sorting', '$sortorder', '$action', '', '$hide_search', '$html_template', '$imagefolder','$addpeople','$removepeople','$firstpeople'); return false;\" type=\"button\" name=\"btnCancel\" id=\"btnCancel\" value=\"Avbryt\" /></td>
		</tr>
	</table>";
	return $content;
}

function exportDepChoice($scope, $action, $query, $imageSokvag, $lang, $sorting, $sortorder, $lastaction) {
	//die("$scope, $action, $query, $imageSokvag, $lang, $sorting, $sortorder, $lastaction");
	$content = "<table style=\"font-size:1.1em; margin-bottom:50px; margin-top:70px;\" width=\"700\" border=\"0\" cellspacing=\"3\" cellpadding=\"0\">
		  <tr>
		<td colspan=\"3\" class=\"sortrow\" style=\"font-size:1em;\"><b>&nbsp;&nbsp;V&auml;lj exportformat</b>
		</td>
		</tr>
		  <tr>
		<td colspan=\"3\">&nbsp;
		</td>
		</tr>		
	  <tr>
		<td> <input onclick=\"disableGroup(this.value, 'txtHeader');\" type=\"radio\" name=\"rblExportFormat\" id=\"rblExportFormat_pdfList\" value=\"cblPdfList\" checked />
	  Lista i pdf-format</td>
		<td><input type=\"checkbox\" name=\"cblPdfList\" id=\"cblPdfList_title\" value=\"title\" checked />
		  Titel
			<input type=\"checkbox\" name=\"cblPdfList\" id=\"cblPdfList_adress\" value=\"adress\" checked />
	Adress
	<input type=\"checkbox\" name=\"cblPdfList\" id=\"cblPdfList_hus\" value=\"hus\" checked />
	Hus
	<input type=\"checkbox\" name=\"cblPdfList\" id=\"cblPdfList_webbaddress\" value=\"webbaddress\" checked />
	Webbadress
	<input type=\"checkbox\" name=\"cblPdfList\" id=\"cblPdfList_hs\" value=\"hs\" checked />
	HS	
	</td>
	<td>
	Rubrik: 
	<input type=\"text\" name=\"txtHeader\" id=\"txtHeader\" /></td>
	  </tr>
	 
	 
	  <tr>
		<td> <input onclick=\"disableGroup(this.value, '');\" type=\"radio\" name=\"rblExportFormat\" id=\"rblExportFormat\" value=\"cblTabSep\" />
	  Tab-separerad textfil</td>
		<td><input type=\"checkbox\" name=\"cblTabSep\" id=\"cblTabSep_title\" value=\"title\" />
		  Titel
			<input type=\"checkbox\" name=\"cblTabSep\" id=\"cblTabSep_adress\" value=\"adress\" />
	Adress
	<input type=\"checkbox\" name=\"cblTabSep\" id=\"cblTabSep_hus\" value=\"hus\" />
	Hus
	<input type=\"checkbox\" name=\"cblTabSep\" id=\"cblTabSep_webbaddress\" value=\"webbaddress\" />
	Webbadress
	<input type=\"checkbox\" name=\"cblTabSep\" id=\"cblTabSep_hs\" value=\"hs\" />
	HS	
	</td><td></td>
	  </tr>
	  
	  
	  <tr>
		<td> <input onclick=\"disableGroup(this.value, '');\" type=\"radio\" name=\"rblExportFormat\" id=\"rblExportFormat\" value=\"cblKommaSep\" />
	  Komma-separerad textfil</td>
		<td><input type=\"checkbox\" name=\"cblKommaSep\" id=\"cblKommaSep_title\" value=\"title\" />
		  Titel
			<input type=\"checkbox\" name=\"cblKommaSep\" id=\"cblKommaSep_adress\" value=\"adress\" />
	Adress
	<input type=\"checkbox\" name=\"cblKommaSep\" id=\"cblKommaSep_hus\" value=\"hus\" />
	Hus
	<input type=\"checkbox\" name=\"cblKommaSep\" id=\"cblKommaSep_webbaddress\" value=\"webbaddress\" />
	Webbadress
	<input type=\"checkbox\" name=\"cblKommaSep\" id=\"cblKommaSep_hs\" value=\"hs\" />
	HS	
	</td><td></td>
	  </tr>
	  
	  
	 <tr>
		<td> <input onclick=\"disableGroup(this.value, '');\" type=\"radio\" name=\"rblExportFormat\" id=\"rblExportFormat\" value=\"cblCsv\" />
	  CSV-fil</td>
		<td><input type=\"checkbox\" name=\"cblCsv\" id=\"cblCsv_title\" value=\"title\" />
		  Titel
			<input type=\"checkbox\" name=\"cblCsv\" id=\"cblCsv_adress\" value=\"adress\" />
	Adress
	<input type=\"checkbox\" name=\"cblCsv\" id=\"cblCsv_hus\" value=\"hus\" />
	Hus
	<input type=\"checkbox\" name=\"cblCsv\" id=\"cblCsv_webbaddress\" value=\"webbaddress\" />
	Webbadress
	<input type=\"checkbox\" name=\"cblCsv\" id=\"cblCsv_hs\" value=\"hs\" />
	HS	
	</td><td></td>
	  </tr>
	  
	  
	  <tr>
		<td> <input onclick=\"disableGroup(this.value, 'txtLabelExtra');\" type=\"radio\" name=\"rblExportFormat\" id=\"rblExportFormat\" value=\"cblLabels\" />
	  Etiketter (avery 7160)</td>
		<td><input type=\"checkbox\" name=\"cblLabels\" id=\"cblLabels_title\" value=\"title\" />
		  Titel
			<input type=\"checkbox\" name=\"cblLabels\" id=\"cblLabels_adress\" value=\"adress\" />
	Adress
	<input type=\"checkbox\" name=\"cblLabels\" id=\"cblLabels_hus\" value=\"hus\" />
	Hus
	<input type=\"checkbox\" name=\"cblLabels\" id=\"cblLabels_hs\" value=\"hs\" />
	H&auml;mtst&auml;lle</td><td>
	Extra: 
	<input type=\"text\" name=\"txtLabelExtra\" id=\"txtLabelExtra\" /></td>
	  </tr>
	  <tr>
		<td colspan=\"3\">&nbsp;
		</td>
		</tr>
		
		
	  <tr>
		<td colspan=\"3\"><input onclick=\"exportDo('$scope', '$action', '$query', '$imageSokvag', '$lang', '$sorting', '$sortorder'); return false;\" type=\"button\" name=\"btnExport\" id=\"btnExport\" value=\"Exportera\" />
		  &nbsp;&nbsp;
		  <input onClick=\"lista('$scope', '$lastaction', '$query', '$imageSokvag', '$lang', '$sorting', '$sortorder', '$action', '', '$hide_search'); return false;\" type=\"button\" name=\"btnCancel\" id=\"btnCancel\" value=\"Avbryt\" /></td>
		</tr>
	</table>";
	return $content;
}

function titleChoice($scope, $action, $query, $imageSokvag, $lang, $sorting, $sortorder) {
	$titleArray = getTitles();
	$teacherArray = $titleArray["teacher"];
	$doctoralArray = $titleArray["doctoral"];
	$adminArray = $titleArray["admin"];
	//print_r($titleArray);
	$res = $GLOBALS["TYPO3_DB"]->exec_SELECTquery("DISTINCT uid, title", "fe_users", "tx_institutioner_lth_search = 1 AND deleted = 0 AND TRIM(title) != '' ", "", "title", "") or die("1344; ".mysql_error());
	while ($row = $GLOBALS["TYPO3_DB"]->sql_fetch_assoc($res)) {
		$title = $row["title"];
		$uid = $row["uid"];
		//echo "-$old_title- != -$title-";
		if(strtolower(trim($old_title)) != strtolower(trim($title))) {
			//echo "yes";
			if(in_array(strtolower(trim($title)), $teacherArray)) {
				$teacherList .= "<option value=\"$uid\">$title</option>";
			} elseif(in_array(strtolower(trim($title)), $doctoralArray)) {
				$doctoralList .= "<option value=\"$uid\">$title</option>";
			} elseif(in_array(strtolower(trim($title)), $adminArray)) {
				$adminList .= "<option value=\"$uid\">$title</option>";
			} else {
				$mainList .= "<option value=\"$uid\">$title</option>";
			}
		} else {
			//echo "no";
		}
		//echo "<br />";
		$old_title = $title;
	}
	$GLOBALS["TYPO3_DB"]->sql_free_result($res);
	
	$content = "<table width=\"600\" border=\"0\" cellspacing=\"8\" cellpadding=\"0\">
	<tr>
	<td>
	<select name=\"teacher\" size=\"8\" id=\"teacher\" style=\"font-size:1em;width:250px;\">
	$teacherList
	</select>
	<br /><input type=\"button\" name=\"ff\" id=\"ff\" value=\"S&ouml;k\" onclick=\"listaTjanster('teacher', '$scope', '$action', 'listaPersonerTjanster', '1', '$lang', '$sorting', '$sortorder', ''); return false;\"/>
	</td>
	<td><input type=\"button\" name=\"btnMove1a\" id=\"btnMove1b\" value=\"&lt;\" onclick=\"MoveOptions('source', 'teacher'); return false;\"/>
	<br />
	<input type=\"button\" name=\"btnMove2a\" id=\"btnMove2b\" value=\"&gt;\" onclick=\"MoveOptions('teacher', 'source'); return false;\" /></td>
	<td rowspan=\"4\" valign=\"top\">
	<select name=\"source\" size=\"40\" id=\"source\" style=\"font-size:1em;width:250px;\">
	$mainList
	</select>
	</td>
	</tr>
	<tr>
	<td>
	<select name=\"doctoral\" size=\"8\" id=\"doctoral\" style=\"font-size:1em;width:250px;\">
	$doctoralList
	</select>
	<br /><input type=\"button\" name=\"ff\" id=\"ff\" value=\"S&ouml;k\" onclick=\"listaTjanster('doctoral', '$scope', '$action', 'listaPersonerTjanster', '1', '$lang', '$sorting', '$sortorder'); return false;\"/>
	</td>
	<td><input type=\"button\" name=\"btnMove3a\" id=\"btnMove3b\" value=\"&lt;\" onclick=\"MoveOptions('source', 'doctoral'); return false;\" />
	<br />
	<input type=\"button\" name=\"btnMove4a\" id=\"btnMove4b\" value=\"&gt;\" onclick=\"MoveOptions('doctoral', 'source'); return false;\" /></td>
	</tr>
	<tr>
	<td>
	<select name=\"admin\" size=\"8\" id=\"admin\" style=\"font-size:1em;width:250px;\">
	$adminList
	</select>
	<br /><input type=\"button\" name=\"ff\" id=\"ff\" value=\"S&ouml;k\" onclick=\"listaTjanster('teacher', '$scope', '$action', 'listaPersonerTjanster', '1', '$lang', '$sorting', '$sortorder'); return false;\"/>
	</td>
	<td><input type=\"button\" name=\"btnMove5a\" id=\"btnMove5b\" value=\"&lt;\" onclick=\"MoveOptions('source', 'admin'); return false;\" />
	<br />
	<input type=\"button\" name=\"btnMove6a\" id=\"btnMove6b\" value=\"&gt;\" onclick=\"MoveOptions('admin', 'source'); return false;\" /></td>
	</tr>
	<tr>
	<td>
	<select name=\"custom\" size=\"8\" id=\"custom\" style=\"font-size:1em;width:250px;\">
	</select>
	<br /><input type=\"button\" name=\"ff\" id=\"ff\" value=\"S&ouml;k\" onclick=\"listaTjanster('custom', '$scope', '$action', 'listaPersonerTjanster', '1', '$lang', '$sorting', '$sortorder'); return false;\"/>
	</td>
	<td><input type=\"button\" name=\"btnMove7a\" id=\"btnMove7b\" value=\"&lt;\" onclick=\"MoveOptions('source', 'custom'); return false;\" />
	<br />
	<input type=\"button\" name=\"btnMove8a\" id=\"btnMove8b\" value=\"&gt;\" onclick=\"MoveOptions('custom', 'source'); return false;\" /></td>
	</tr>
	<tr>
	<td colspan=\"3\">
	<input type=\"button\" name=\"gg\" id=\"gg\" value=\"Avbryt\" /></td>
	</tr>
	</table>";
	
	return $content;
}

function exportDo($scope, $action, $query, $imageSokvag, $lang, $sorting, $sortorder, $rvalue, $cvalue, $lextra) {
	//die("scope=$scope, action=$action, query=$query, imageSokvag=$imageSokvag, lang=$lang, sorting=$sorting, sortorder=$sortorder, rvalue=$rvalue, cvalue=$cvalue, lextra=$lextra");
	//; exportDepDo; ; cblPdfList; title,adress,hus,telephone,webbaddress; se; 
	if (!t3lib_extMgm::isLoaded('fpdf')) return "fpdf library not loaded!";
	$i = 0;
	$x = 0;
	$y = 0;
	
	if($action == "exportDepDo") {

		$table = "tx_institutioner_institution I1 INNER JOIN tx_institutioner_institution I2 ON I1.uid=I2.iid LEFT JOIN tx_institutioner_hus H ON I1.hid=H.uid";
		$selection = "I1.title AS title1, I1.webbadress AS webbaddress1, I1.webadress_e AS webbaddress_e1, I2.title AS title2, I2.title_e AS title2_e, I2.webbadress AS webbaddress2, I2.webadress_e AS webbaddress_e2, H.title AS hus, H.adress AS adress, H.hs AS hs, REPLACE(I1.title, 'Institutionen f�r ', '') AS mysort1, REPLACE(I2.title, 'Institutionen f�r ', '') AS mysort2, IF(I1.title=I2.title, 0, 1) AS mysort3";
		if($query) $criteria .= "(I2.title LIKE '%$query%') AND ";
		if($sortorder) {
			$criteria .= "I2.uid = I2.iid AND ";
			$sorting = "mysort2";
			$sortorder = "";
		}		
		$criteria .= "I1.deleted=0 AND I2.deleted=0";
	} else {
		if($scope) {
			/*$res = $GLOBALS["TYPO3_DB"]->exec_SELECTquery("G1.uid AS avdid, G2.uid AS instid, G1.title, G2.subgroup, G1.tx_institutioner_title_eng AS title_eng", "fe_groups G1 LEFT JOIN fe_groups G2 ON G2.subgroup = G1.uid", "G1.tx_institutioner_lucatid IN($scope) AND G1.deleted=0", "", "") or die("1405; ".mysql_error());
			while ($row = $GLOBALS["TYPO3_DB"]->sql_fetch_assoc($res)) {
				if($groupid) $groupid .= ",";
				$subgroup = $row["subgroup"];
				if($subgroup) {
					$groupid .= $row["instid"];
				} else {
					$groupid .= $row["avdid"];
				}
				if($subgroup) $groupid .= ",$subgroup";
				$criteria = "usergroup IN($groupid) AND ";
			}*/
                    $res = $GLOBALS["TYPO3_DB"]->exec_SELECTquery("G1.uid AS avdid, G2.uid AS instid, G1.title, G2.subgroup, G1.tx_institutioner_title_eng AS title_eng", "fe_groups G1 LEFT JOIN fe_groups G2 ON G2.subgroup = G1.uid", "G1.tx_institutioner_lucatid IN($scope) AND G1.deleted=0", "", "") or die("246; ".mysql_error());
                    while ($row = $GLOBALS["TYPO3_DB"]->sql_fetch_assoc($res)) {
                            /*if($groupid) $groupid .= ",";
                            $subgroup = $row["subgroup"];
                            if($subgroup) {
                                    $groupid .= $row["instid"];
                            } else {
                                    $groupid .= $row["avdid"];
                            }*/
                        $avdId = $row["avdid"].'';
                        $instId = $row["instid"].'';
                        if(!in_array($avdId,$groupidArray) && $avdId!='') $groupidArray[] = $avdId;
                        if(!in_array($instId,$groupidArray) && $instId!='') $groupidArray[] = $instId;
                            $title = $row["title"];
                            $title_eng = $row["title_eng"];
                            if($lang=="en") $title = $title_eng;
                    }
                    if($subgroup) $groupid .= ",$subgroup";
                    //$urval = "usergroup IN($groupid) AND ";
                    //$groupidArray = explode(',',$groupid);
                    foreach($groupidArray as $key => $value) {
                            if($urval) $urval .= " OR ";
                            $urval .= "FIND_IN_SET('$value', usergroup)";
                    }
		}
		$table = "fe_users";
		$selection = "uid, name, email, telephone, username, usergroup, title";
		
		$criteria .= "(name LIKE '%$query%' OR email LIKE '%$query%' OR telephone LIKE '%$query%' OR concat(first_name, ' ', last_name) LIKE '%$query%') AND deleted=0 AND tx_institutioner_lth_search=1";
		$sorting = "last_name,first_name";
		$sortorder = "";
		//die("$urval, $table, $selection, $criteria");
	}
	
	//cblPdfList cblTabSep cblKommaSep cblCsv cblLabels
	//title,adress,hus,telephone,webbaddress
	$cArray = explode(",", $cvalue);
	if(in_array("title", $cArray)) $title_display = true;
	if(in_array("name", $cArray)) $name_display = true;
	if(in_array("dep", $cArray)) $dep_display = true;
	if(in_array("telephone", $cArray)) $telephone_display = true;
	if(in_array("email", $cArray)) $email_display = true;
	if(in_array("hs", $cArray)) $hs_display = true;
	if(in_array("adress", $cArray)) $adress_display = true;
	if(in_array("hus", $cArray)) $hus_display = true;
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
	
	$res = $GLOBALS["TYPO3_DB"]->exec_SELECTquery($selection, $table, $urval, "", "$sorting$sortorder") or die("1537; ".mysql_error());
	while ($row = $GLOBALS["TYPO3_DB"]->sql_fetch_assoc($res)) {
		$title = ucfirst($row["title"]) . ucfirst($row["title2"]);
		$name = $row["name"];
		$email = strtolower($row["email"]);
		$telephone = $row["telephone"];
		if($telephone) formatPhone($telephone, $lang);
		$usergroup = $row["usergroup"];
		if($usergroup) $displayGroup = getFeGroup($usergroup, $lang, $action);
		$displayArray = explode(";", $displayGroup);
		$adress = $row["adress"];
		$hus = $row["hus"];
		$hs = $row["hs"];
		if($lang=="se") $webbaddress = $row["webbaddress2"];
		if($lang=="en") $webbaddress = $row["webbaddress_e2"];
		
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
				if($dep_display) $pdf->Cell(60,6,$displayGroup[0],1,0,L,0);
				if($adress_display) $pdf->Cell(40,6,$adress,1,0,L,0);
				if($hs_display) $pdf->Cell(10,6,$hs,1,0,L,0);
				if($hus_display) $pdf->Cell(35,6,$hus,1,0,L,0);
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
				if($dep_display) $s .= $displayArray[0] . "\t";
				if($telephone_display) $s .= "$telephone\t";
				if($email_display) $s .= "$email\t";
				if($adress_display) $s .= "$adress\t";				
				if($hs_display) $s .= "$hs\t";
				if($hus_display) $s .= "$hus\t";
				if($webbaddress_display) $s .= "$webbaddress\t";
				if(substr($s, -2) == "\t") $s = substr($s, 0, strlen($s) - 2);			
				$s .= "\r\n";
				$content .= $s;
				break;
			case cblKommaSep:
				if($title_display) $s .= "$title,";
				if($name_display) $s .= "$name,";
				if($dep_display) $s .= $displayArray[0] . ",";
				if($telephone_display) $s .= "$telephone,";
				if($email_display) $s .= "$email";
				if($adress_display) $s .= "$adress,";
				if($hs_display) $s .= "$hs,";
				if($hus_display) $s .= "$hus,";
				if($webbaddress_display) $s .= "$webbaddress,";
				if(substr($s, -1) == ",") $s = substr($s, 0, strlen($s) - 1);			
				$s .= "\r\n";
				$content .= $s;
				break;			
			case cblCsv:
				if($title_display) $s .= $title . ";";
				if($name_display) $s .= $name . ";";
				if($dep_display) $s .= $displayArray[0] . ";";
				if($telephone_display) $s .= "$telephone;";
				if($email_display) $s .= "$email;";
				if($adress_display) $s .= "$adress;";				
				if($hs_display) $s .= "$hs;";
				if($hus_display) $s .= "$hus;";
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
				if($hus_display) $s .= "$hus\n";
				if($name_display) $s .= "$name\n";
				if($dep_display) $s .= $displayArray[0] . "\n";
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
	$GLOBALS["TYPO3_DB"]->sql_free_result($res);
	
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

function telephoneList($groupid, $scope, $lang) {

		// Check if the FPDF library is loaded (extension 'FDPF'). If not, return immediately.
	if (!t3lib_extMgm::isLoaded('fpdf')) return "sucker";
	
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
	$pdf->Cell(297, 25, utf8_decode($langArray["telephoneListFor"]) . ' ' . $scope,0,0,'C');
	
	$pdf->SetFont('Helvetica', '', 11);

	$res = $GLOBALS["TYPO3_DB"]->exec_SELECTquery("first_name, last_name, telephone", "fe_users", "usergroup IN($groupid) AND deleted=0", "", "last_name,first_name") or die("729; ".mysql_error());
	while ($row = $GLOBALS["TYPO3_DB"]->sql_fetch_assoc($res)) {
		$tele = $row["telephone"];
		$name = $row["first_name"] . " " . $row["last_name"];
		$tele = str_replace(" ","",$tele);
		$tele = str_replace("046-22","",$tele);
		$tele = str_replace("042-3","",$tele);
		$tele = str_replace("+464622","",$tele);
		$tele = str_replace("28276","28135",$tele);
		if ($tele != "" and trim($name) != "") {
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
			$pdf->Cell(0,0,$name);
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

	$GLOBALS['TYPO3_DB']->sql_free_result($res);

	$pdf->Output();
	
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

   /* function Circle($x,$y,$r,$style='')
    {
        $this->Ellipse($x,$y,$r,$r,$style);
    }

    function Ellipse($x,$y,$rx,$ry,$style='D')
    {
        if($style=='F')
            $op='f';
        elseif($style=='FD' or $style=='DF')
            $op='B';
        else
            $op='S';
        $lx=4/3*(M_SQRT2-1)*$rx;
        $ly=4/3*(M_SQRT2-1)*$ry;
        $k=$this->k;
        $h=$this->h;
        $this->_out(sprintf('%.2f %.2f m %.2f %.2f %.2f %.2f %.2f %.2f c',
            ($x+$rx)*$k,($h-$y)*$k,
            ($x+$rx)*$k,($h-($y-$ly))*$k,
            ($x+$lx)*$k,($h-($y-$ry))*$k,
            $x*$k,($h-($y-$ry))*$k));
        $this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c',
            ($x-$lx)*$k,($h-($y-$ry))*$k,
            ($x-$rx)*$k,($h-($y-$ly))*$k,
            ($x-$rx)*$k,($h-$y)*$k));
        $this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c',
            ($x-$rx)*$k,($h-($y+$ly))*$k,
            ($x-$lx)*$k,($h-($y+$ry))*$k,
            $x*$k,($h-($y+$ry))*$k));
        $this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c %s',
            ($x+$lx)*$k,($h-($y+$ry))*$k,
            ($x+$rx)*$k,($h-($y+$ly))*$k,
            ($x+$rx)*$k,($h-$y)*$k,
            $op));
    }

    function RoundedRect($x, $y, $w, $h,$r, $style = '')
    {
        $k = $this->k;
        $hp = $this->h;
        if($style=='F')
            $op='f';
        elseif($style=='FD' or $style=='DF')
            $op='B';
        else
            $op='S';
        $MyArc = 4/3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2f %.2f m',($x+$r)*$k,($hp-$y)*$k ));
        $xc = $x+$w-$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2f %.2f l', $xc*$k,($hp-$y)*$k ));

        $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
        $xc = $x+$w-$r ;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2f %.2f l',($x+$w)*$k,($hp-$yc)*$k));
        $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
        $xc = $x+$r ;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2f %.2f l',$xc*$k,($hp-($y+$h))*$k));
        $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
        $xc = $x+$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2f %.2f l',($x)*$k,($hp-$yc)*$k ));
        $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
        $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
    {
        $h = $this->h;
        $this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c ', $x1*$this->k, ($h-$y1)*$this->k,
            $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
    }

*/

?>