<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2007 Tomas Havner <>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * Plugin 'Institutioner' for the 'institutioner' extension.
 *
 * @author	Tomas Havner <>
 */


require_once(PATH_tslib.'class.tslib_pibase.php');

class tx_institutioner_pi1 extends tslib_pibase {
    var $prefixId = 'tx_institutioner_pi1';		// Same as class name
    var $scriptRelPath = 'pi1/class.tx_institutioner_pi1.php';	// Path to this script relative to the extension dir.
    var $extKey = 'institutioner';	// The extension key.
    var $pi_checkCHash = TRUE;

    /**
     * The main method of the PlugIn
     *
     * @param	string		$content: The PlugIn content
     * @param	array		$conf: The PlugIn configuration
     * @return	The content that is displayed on the website
     */
    function main($content,$conf)	{
//error_reporting(E_ALL);
        $this->conf=$conf;
        $this->pi_setPiVarDefaults();
        $this->pi_loadLL();
    //return $this->loginForm();
        $siteadmingroupid = $this->conf["siteadminid"];
        $pageid = intval($GLOBALS['TSFE']->id);

        $this->pi_initPIflexForm();
        $piFlexForm = $this->cObj->data["pi_flexform"];
        $index = $GLOBALS["TSFE"]->sys_language_uid;
        $sDef = current($piFlexForm["data"]);       
        $lDef = array_keys($sDef);
        $html_template = $this->pi_getFFvalue($piFlexForm, "html_template", "sDEF", $lDef[$index]);
        $action = $this->pi_getFFvalue($piFlexForm, "action", "sDEF", $lDef[$index]);
        $scope = $this->pi_getFFvalue($piFlexForm, "scope", "sDEF", $lDef[$index]);
        //$css_file = $this->pi_getFFvalue($piFlexForm, "css_file", "sDEF", $lDef[$index]);
        //$show_radio = $this->pi_getFFvalue($piFlexForm, "show_radio", "sDEF", $lDef[$index]);
        $sorting = $this->pi_getFFvalue($piFlexForm, "sorting", "sDEF", $lDef[$index]);
        $hide_search = $this->pi_getFFvalue($piFlexForm, "hide_search", "sDEF", $lDef[$index]);
        $imagefolder = $this->pi_getFFvalue($piFlexForm, "imagefolder", "sDEF", $lDef[$index]);
        $addpeople = $this->pi_getFFvalue($piFlexForm, "addpeople", "sDEF", $lDef[$index]);
        $removepeople = $this->pi_getFFvalue($piFlexForm, "removepeople", "sDEF", $lDef[$index]);
        $categories = $this->pi_getFFvalue($piFlexForm, "categories", "sDEF", $lDef[$index]);
        //$luppage = $this->pi_getFFvalue($piFlexForm, "luppage", "sDEF", $lDef[$index]);
        //$userpage = t3lib_div::_GP("userpage");
        $lang = $GLOBALS["TSFE"]->config["config"]["language"];
        $pluginid = $this->cObj->data['uid'];

        $searchval = $this->pi_getLL("searchHere");
	
	$scope=str_replace("\n",",",$scope);
	$addpeople=str_replace("\n",",",$addpeople);
	$removepeople=str_replace("\n",",",$removepeople);

        $GLOBALS["TSFE"]->additionalHeaderData["tx_institutioner_js_lang"] = '<script type="text/javascript" src="typo3conf/ext/institutioner/lang/lang_'.$lang.'.js"></script>';
        $GLOBALS["TSFE"]->additionalHeaderData["tx_institutioner_js"] = "<script language=\"JavaScript\" type=\"text/javascript\" src=\"/typo3conf/ext/institutioner/pi1/institutioner.js\"></script>"; 
        //mini-ajax-file-upload
        //$GLOBALS["TSFE"]->additionalHeaderData["mini_ajax_file_upload_knob_js"] = "<script type=\"text/javascript\" src=\"/typo3conf/ext/institutioner/vendor/mini_ajax_file_upload/assets/js/jquery.knob.js\"></script>";
        //$GLOBALS["TSFE"]->additionalHeaderData["mini_ajax_file_upload_iframe_js"] = "<script type=\"text/javascript\" src=\"/typo3conf/ext/institutioner/vendor/mini_ajax_file_upload/assets/js/jquery.iframe-transport.js\"></script>";
        //$GLOBALS["TSFE"]->additionalHeaderData["mini_ajax_file_upload_fileuplad_js"] = "<script type=\"text/javascript\" src=\"/typo3conf/ext/institutioner/vendor/mini_ajax_file_upload/assets/js/jquery.fileupload.js\"></script>";
        //$GLOBALS["TSFE"]->additionalHeaderData["mini_ajax_file_upload_widget_js"] = "<script type=\"text/javascript\" src=\"/typo3conf/ext/institutioner/vendor/mini_ajax_file_upload/assets/js/jquery.ui.widget.js\"></script>";
        
        $GLOBALS["TSFE"]->additionalHeaderData["tx_institutioner_css"] = "<link rel=\"stylesheet\" type=\"text/css\" href=\"/typo3conf/ext/institutioner/pi1/institutioner.css\" />";
        //$GLOBALS["TSFE"]->additionalHeaderData["mini_ajax_file_upload_css"] = "<link rel=\"stylesheet\" type=\"text/css\" href=\"/typo3conf/ext/institutioner/vendor/mini_ajax_file_upload/assets/css/style.css\" />";

        //$content .= "<form name=\"institutionerform\" id=\"institutionerform\" method=\"post\" enctype=\"multipart/form-data\" action=\"\">";

        //*****************************Status bar begin*********************************************************

        $content .= "<div class=\"sortrow\">";
        $content .= "<div class=\"tx_institiutioner_sortrow_buttons\">";

        //Name sorting
        $content .= "<a href=\"#\" class=\"sortingColumn name_sort_field asc\">";
        $content .= $this->pi_getLL("alphabetic_order");
        $content .= "<img src=\"/fileadmin/templates/images/shortcuts-up-bronze.png\" border=\"0\" class=\"sortbutton\" />";
        $content .= "</a>";
        
        //Category sorting
        if($categories) {
            $content .= "<a href=\"#\" class=\"sortingColumn category_sort_field asc\">";
            $content .= $this->pi_getLL("category_order");
            $content .= "<img src=\"/fileadmin/templates/images/shortcuts-up-bronze.png\" border=\"0\" class=\"sortbutton\" />";
            $content .= "</a>";
        }

        $content .= "</div>";
        $content .= "</div>";

        //Hits
        $content .= "<div style=\"padding-left:10px; color: #8E5614; \"><div id=\"hits\"></div></div>";

        //*****************************Status bar ends*********************************************************

        $content .= "<div id=\"txtContent\" class=\"tx_institutioner_maincontentdiv\">";
        $content .= "</div>";
        
        $content .= "<div id=\"txtLoginform\" class=\"tx_institutioner_loginformdiv\">";
        $content .= $this->loginForm();
        $content .= "<p><a href=\"#\" onclick=\"showHideLoginform(); return false;\">Back</a></p>";
        $content .= "</div>";
        
	if($GLOBALS['BE_USER']) {
	    $beUsergroupArray = explode(',',$GLOBALS["BE_USER"]->user["usergroup"]);
	    $issiteadmin = 0;
	    if((in_array($siteadmingroupid, $beUsergroupArray) and $GLOBALS['BE_USER']->isInWebMount($pageid)) or $GLOBALS['BE_USER']->user['admin']) {
		$issiteadmin = 1;
	    }
	    $content .= "<input type=\"hidden\" name=\"issiteadmin\" id=\"issiteadmin\" value=\"$issiteadmin\" />";
	}
        $content .= "<input type=\"hidden\" name=\"pluginid\" id=\"pluginid\" value=\"$pluginid\" />";
        $content .= "<input type=\"hidden\" name=\"serializevalues\" id=\"serializevalues\" value=\"" . json_encode(array('lang'=>$lang,'imagefolder'=>$imagefolder))."\" />";
        $content .= "</form>";
        $content .= "<iframe id=\"historyFrame\" src=\"/typo3conf/ext/institutioner/pi1/HistoryFrame.htm\" style=\"display:none;\"></iframe>";

        if($user) {
            $action='listaPerson';
            $query=$user;
        } else {
            $query='';
        }

        if($uid=="") {
            $content .= "<script language=\"javascript\">";
            $content .= "lista('$scope','$action" . "_no1" . "','$query','$lang','$hide_search','$html_template','$imagefolder','$addpeople','$removepeople','$categories','','$sorting');";
            $content .= "</script>";
        }

        $content .= "<noscript><p></p><p>JavaScript is turned off in your web browser. Turn it on to take full advantage of this site, then refresh the page.</p></noscript>";
        $content .= "<div style=\"clear:both;margin-bottom:10px;\"></div>";

        return $content;

    }

    function loginForm()
    {
        $conf = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_felogin_pi1.'];       
        $conf["templateFile"] = "fileadmin/templates/felogin/felogin.html";
        // Get plugin instance
        $cObj = t3lib_div::makeInstance('tslib_cObj');
        /* @var $cObj tslib_cObj */
        //$cObj->start(array(), '');
        $objType = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_felogin_pi1'];
        $content = $cObj->cObjGetSingle($objType, $conf);

        return $content;
    }
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/institutioner/pi1/class.tx_institutioner_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/institutioner/pi1/class.tx_institutioner_pi1.php']);
}

?>