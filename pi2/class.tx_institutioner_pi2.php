<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Tomas Havner <>
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
 * Plugin 'User Redirect' for the 'institutioner' extension.
 *
 * @author	Tomas Havner <>
 */


require_once(PATH_tslib.'class.tslib_pibase.php');

class tx_institutioner_pi2 extends tslib_pibase {
	var $prefixId = 'tx_institutioner_pi2';		// Same as class name
	var $scriptRelPath = 'pi2/class.tx_institutioner_pi2.php';	// Path to this script relative to the extension dir.
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
		$this->conf=$conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
	
		$this->pi_initPIflexForm();
		$piFlexForm = $this->cObj->data["pi_flexform"];
       	$index = $GLOBALS["TSFE"]->sys_language_uid;
		$sDef = current($piFlexForm["data"]);       
		$lDef = array_keys($sDef);
		$directory = $this->pi_getFFvalue($piFlexForm, "directory", "sDEF", $lDef[$index]);
		
		$user = t3lib_div::_GP("user");
		
		if($directory) {
			$directory = trim($directory);
			if(strpos($directory, "/") == 0) $directory = substr($directory, 1);
			if(substr($directory, -1) == "/") $directory = substr($directory, 0, -1);
			$content = $directory;
		}
		
		//$res = $GLOBALS["TYPO3_DB"]->exec_SELECTquery("username", "fe_users", "concat(first_name,' ', last_name)=" . mysql_real_escape_string($user) . " AND deleted=0", "", "", "") or die("67; ".mysql_error());
	//$i = 0;
	//while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
		
		//$GLOBALS["TYPO3_DB"]->sql_free_result($res);
	
		//return $this->pi_wrapInBaseClass($content);
		header("Location: http://localhost/$directory/?user=$user");
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/institutioner/pi2/class.tx_institutioner_pi2.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/institutioner/pi2/class.tx_institutioner_pi2.php']);
}

?>