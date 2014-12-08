<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA["tx_institutioner_institution"] = Array (
	"ctrl" => $TCA["tx_institutioner_institution"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,iid,hid,title,title_e,webbadress,webadress_e,lucatid,telefon,ladok_kod,dont_show"
	),
	"feInterface" => $TCA["tx_institutioner_institution"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (		
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.xml:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"iid" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:institutioner/locallang_db.xml:tx_institutioner_institution.iid",		
			"config" => Array (
				"type" => "select",	
				"items" => Array (
					Array("",0),
				),
				"foreign_table" => "tx_institutioner_institution",	
				"foreign_table_where" => "ORDER BY tx_institutioner_institution.uid",	
				"size" => 1,	
				"minitems" => 0,
				"maxitems" => 1,
			)
		),
		"hid" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:institutioner/locallang_db.xml:tx_institutioner_institution.hid",		
			"config" => Array (
				"type" => "select",	
				"foreign_table" => "tx_institutioner_hus",	
				"foreign_table_where" => "ORDER BY tx_institutioner_hus.uid",	
				"size" => 1,	
				"minitems" => 0,
				"maxitems" => 1,
			)
		),
		"title" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:institutioner/locallang_db.xml:tx_institutioner_institution.title",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"eval" => "trim",
			)
		),
		"title_e" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:institutioner/locallang_db.xml:tx_institutioner_institution.title_e",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"eval" => "trim",
			)
		),
		"webbadress" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:institutioner/locallang_db.xml:tx_institutioner_institution.webbadress",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"eval" => "trim",
			)
		),
		"webadress_e" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:institutioner/locallang_db.xml:tx_institutioner_institution.webadress_e",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"eval" => "trim",
			)
		),
		"lucatid" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:institutioner/locallang_db.xml:tx_institutioner_institution.lucatid",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"eval" => "trim",
			)
		),
		"telefon" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:institutioner/locallang_db.xml:tx_institutioner_institution.telefon",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"eval" => "trim",
			)
		),
		"ladok_kod" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:institutioner/locallang_db.xml:tx_institutioner_institution.ladok_kod",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
		"dont_show" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:institutioner/locallang_db.xml:tx_institutioner_institution.dont_show",		
			"config" => Array (
				"type" => "check",
			)
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "hidden;;1;;1-1-1, iid, hid, title;;;;2-2-2, title_e;;;;3-3-3, webbadress, webadress_e, lucatid, telefon, ladok_kod, dont_show")
	),
	"palettes" => Array (
		"1" => Array("showitem" => "")
	)
);



$TCA["tx_institutioner_hus"] = Array (
	"ctrl" => $TCA["tx_institutioner_hus"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,title,title_e,adress,hs,webbadress,webbaddress_e,husid,image,map"
	),
	"feInterface" => $TCA["tx_institutioner_hus"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (		
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.xml:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"title" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:institutioner/locallang_db.xml:tx_institutioner_hus.title",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"eval" => "trim",
			)
		),
		"title_e" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:institutioner/locallang_db.xml:tx_institutioner_hus.title_e",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"eval" => "trim",
			)
		),
		"adress" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:institutioner/locallang_db.xml:tx_institutioner_hus.adress",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"eval" => "trim",
			)
		),
		"hs" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:institutioner/locallang_db.xml:tx_institutioner_hus.hs",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"eval" => "trim",
			)
		),
		"webbadress" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:institutioner/locallang_db.xml:tx_institutioner_hus.webbadress",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"eval" => "trim",
			)
		),
		"webbaddress_e" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:institutioner/locallang_db.xml:tx_institutioner_hus.webbaddress_e",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"eval" => "trim",
			)
		),
		"husid" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:institutioner/locallang_db.xml:tx_institutioner_hus.husid",		
			"config" => Array (
				"type" => "input",	
				"size" => "5",	
				"eval" => "trim",
			)
		),
		"image" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:institutioner/locallang_db.xml:tx_institutioner_hus.image",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"eval" => "trim",
			)
		),
		"map" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:institutioner/locallang_db.xml:tx_institutioner_hus.map",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"eval" => "trim",
			)
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "hidden;;1;;1-1-1, title;;;;2-2-2, title_e;;;;3-3-3, adress, hs, webbadress, webbaddress_e, husid, image, map")
	),
	"palettes" => Array (
		"1" => Array("showitem" => "")
	)
);

$TCA["tx_institutioner_feuser_description"] = Array (
	"ctrl" => $TCA["tx_institutioner_feuser_description"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,sys_domain_title,sys_language_title"
	),
	"feInterface" => $TCA["tx_institutioner_filter_categories"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (		
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.xml:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"description" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:institutioner/locallang_db.xml:tx_institutioner_feuser_description.description",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"eval" => "trim",
			)
		),
		"sys_domain_id" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:institutioner/locallang_db.xml:tx_institutioner_feuser_description.sys_domain_id",		
			"config" => Array (
				"type" => "select",	
				"items" => Array (
					Array("",0),
				),
				"foreign_table" => "sys_domain",	
				"foreign_table_where" => "ORDER BY sys_domain.title",	
				"size" => 1,	
				"minitems" => 0,
				"maxitems" => 1,
			)
		),
            	"sys_language_id" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:institutioner/locallang_db.xml:tx_institutioner_feuser_description.sys_language_id",		
			"config" => Array (
				"type" => "select",	
				"items" => Array (
					Array("",0),
				),
				"foreign_table" => "sys_language",	
				"foreign_table_where" => "ORDER BY sys_language.title",	
				"size" => 1,	
				"minitems" => 0,
				"maxitems" => 1,
			)
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "hidden;;1;;1-1-1, title;;;;2-2-2, title_e;;;;3-3-3")
	),
	"palettes" => Array (
		"1" => Array("showitem" => "")
	)
);

?>