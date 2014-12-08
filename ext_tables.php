<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

include_once(t3lib_extMgm::extPath($_EXTKEY).'class.tx_sampleflex_addFieldsToFlexForm.php');

$TCA["tx_institutioner_institution"] = Array (
	"ctrl" => Array (
		'title' => 'LLL:EXT:institutioner/locallang_db.xml:tx_institutioner_institution',		
		'label' => 'title',	
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		"sortby" => "sorting",	
		"delete" => "deleted",	
		"enablecolumns" => Array (		
			"disabled" => "hidden",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_institutioner_institution.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, iid, hid, title, title_e, webbadress, webadress_e, lucatid, telefon, ladok_kod, dont_show",
	)
);

$TCA["tx_institutioner_hus"] = Array (
	"ctrl" => Array (
		'title' => 'LLL:EXT:institutioner/locallang_db.xml:tx_institutioner_hus',		
		'label' => 'title',	
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		"sortby" => "sorting",	
		"delete" => "deleted",	
		"enablecolumns" => Array (		
			"disabled" => "hidden",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_institutioner_hus.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, title, title_e, adress, hs, webbadress, webbaddress_e, husid, image, map",
	)
);

$TCA["tx_institutioner_feuser_description"] = Array (
	"ctrl" => Array (
		'title' => 'LLL:EXT:institutioner/locallang_db.xml:tx_institutioner_feuser_description',		
		'label' => 'title',	
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		"sortby" => "sorting",	
		"delete" => "deleted",	
		"enablecolumns" => Array (		
			"disabled" => "hidden",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icon_tx_institutioner_filter_categories.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, title, title_e",
	)
);


t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1']='layout,select_key';


t3lib_extMgm::addPlugin(Array('LLL:EXT:institutioner/locallang_db.xml:tt_content.list_type_pi1', $_EXTKEY.'_pi1'),'list_type');


t3lib_extMgm::addStaticFile($_EXTKEY,"pi1/static/","Institutioner");


t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi2']='layout,select_key';


t3lib_extMgm::addPlugin(Array('LLL:EXT:institutioner/locallang_db.xml:tt_content.list_type_pi2', $_EXTKEY.'_pi2'),'list_type');


t3lib_extMgm::addStaticFile($_EXTKEY,"pi2/static/","User Redirect");

$tempColumns = Array (
	"tx_institutioner_lucatid" => Array (		
		"exclude" => 1,		
		"label" => "LLL:EXT:institutioner/locallang_db.xml:fe_groups.tx_institutioner_lucatid",		
		"config" => Array (
			"type" => "input",	
			"size" => "30",	
			"eval" => "trim",
		)
	),
	"tx_institutioner_title_eng" => Array (		
		"exclude" => 1,		
		"label" => "LLL:EXT:institutioner/locallang_db.xml:fe_groups.tx_institutioner_title_eng",		
		"config" => Array (
			"type" => "input",	
			"size" => "30",	
			"eval" => "trim",
		)
	),
);


t3lib_div::loadTCA("fe_groups");
t3lib_extMgm::addTCAcolumns("fe_groups",$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes("fe_groups","tx_institutioner_lucatid;;;;1-1-1, tx_institutioner_title_eng");

$tempColumns = Array (
        'descrition' => array(
            'label' => 'Desription',
            'config' => array(
                    'type' => 'inline',
                    'foreign_table' => 'tx_institutioner_feuser_description',
                    'foreign_field' => 'sys_domain_title',
                    'maxitems'      => 9999
            )
        ),
	"tx_institutioner_lth_search" => Array (		
		"exclude" => 1,		
		"label" => "LLL:EXT:institutioner/locallang_db.xml:fe_users.tx_institutioner_lth_search",		
		"config" => Array (
			"type" => "check",
		)
	),
);


t3lib_div::loadTCA("fe_users");
t3lib_extMgm::addTCAcolumns("fe_users",$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes("fe_users","tx_institutioner_lth_search;;;;1-1-1,description");

$tempColumns = Array (
	"tx_institutioner_lucatid" => Array (		
		"exclude" => 1,		
		"label" => "LLL:EXT:institutioner/locallang_db.xml:be_groups.tx_institutioner_lucatid",		
		"config" => Array (
			"type" => "input",	
			"size" => "30",	
			"eval" => "trim",
		)
	),
);


$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_pi1']='pi_flexform'; //New
t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_pi1', 'FILE:EXT:institutioner/flexform_ds_pi1.xml'); //New


$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_pi2']='pi_flexform'; //New
t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_pi2', 'FILE:EXT:institutioner/flexform_ds_pi2.xml'); //New


t3lib_div::loadTCA("be_groups");
t3lib_extMgm::addTCAcolumns("be_groups",$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes("be_groups","tx_institutioner_lucatid;;;;1-1-1");
?>