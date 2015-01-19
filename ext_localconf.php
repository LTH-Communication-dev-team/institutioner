<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

  ## Extending TypoScript from static template uid=43 to set up userdefined tag:
t3lib_extMgm::addTypoScript($_EXTKEY,'editorcfg','
	tt_content.CSS_editor.ch.tx_institutioner_pi1 = < plugin.tx_institutioner_pi1.CSS_editor
',43);


t3lib_extMgm::addPItoST43($_EXTKEY,'pi1/class.tx_institutioner_pi1.php','_pi1','list_type',1);


  ## Extending TypoScript from static template uid=43 to set up userdefined tag:
t3lib_extMgm::addTypoScript($_EXTKEY,'editorcfg','
	tt_content.CSS_editor.ch.tx_institutioner_pi2 = < plugin.tx_institutioner_pi2.CSS_editor
',43);

$TYPO3_CONF_VARS['FE']['eID_include']['tx_institutioner_pi1'] = 'EXT:institutioner/pi1/fe_index.php';
$TYPO3_CONF_VARS['FE']['eID_include']['tx_institutioner_pi1_ajax'] = 'EXT:ajax_lup/pi1/fe_index.php';
$TYPO3_CONF_VARS['BE']['AJAX']['institutioner::ajaxControl'] = t3lib_extMgm::extPath('institutioner').'mod1/ajax.php:institutioner->ajaxControl';
//$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = t3lib_extMgm::extPath($_EXTKEY).'/class.tx_sampleflex_addFieldsToFlexForm:user_sampleflex_addFieldsToFlexForm';


t3lib_extMgm::addPItoST43($_EXTKEY,'pi2/class.tx_institutioner_pi2.php','_pi2','list_type',1);
?>