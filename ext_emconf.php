<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "institutioner".
 *
 * Auto generated 21-05-2014 18:09
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Institutioner',
	'description' => '',
	'category' => 'plugin',
	'author' => 'Tomas Havner',
	'author_email' => '',
	'shy' => '',
	'dependencies' => 'cms',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'alpha',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => '',
	'version' => '0.0.0',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:95:{s:9:"ChangeLog";s:4:"b5cc";s:12:"ext_icon.gif";s:4:"1bdc";s:17:"ext_localconf.php";s:4:"dd1a";s:14:"ext_tables.php";s:4:"61be";s:14:"ext_tables.sql";s:4:"1714";s:19:"flexform_ds_pi1.xml";s:4:"a2b1";s:19:"flexform_ds_pi2.xml";s:4:"3260";s:35:"icon_tx_institutioner_avdelning.gif";s:4:"475a";s:29:"icon_tx_institutioner_hus.gif";s:4:"475a";s:37:"icon_tx_institutioner_institution.gif";s:4:"475a";s:30:"icon_tx_institutioner_main.gif";s:4:"475a";s:16:"locallang_db.xml";s:4:"326a";s:10:"README.txt";s:4:"9fa9";s:7:"tca.php";s:4:"0ece";s:19:"doc/wizard_form.dat";s:4:"2918";s:20:"doc/wizard_form.html";s:4:"637d";s:24:"graphics/a_small_map.gif";s:4:"bbbf";s:20:"graphics/bgimage.gif";s:4:"88d3";s:22:"graphics/bgimage_2.gif";s:4:"ffbf";s:22:"graphics/bgimage_3.gif";s:4:"ffbf";s:26:"graphics/bmc_small_map.gif";s:4:"9b82";s:17:"graphics/dolj.gif";s:4:"6ef1";s:20:"graphics/dolj_en.gif";s:4:"b8e7";s:21:"graphics/dolj_new.gif";s:4:"2eea";s:24:"graphics/dolj_new_en.gif";s:4:"b8e7";s:20:"graphics/dolj_se.gif";s:4:"2eea";s:24:"graphics/e_small_map.gif";s:4:"77d5";s:18:"graphics/email.png";s:4:"af58";s:24:"graphics/f_small_map.gif";s:4:"880c";s:18:"graphics/house.png";s:4:"99be";s:27:"graphics/ikdc_small_map.gif";s:4:"13e2";s:25:"graphics/kc_small_map.gif";s:4:"c214";s:25:"graphics/kh_small_map.gif";s:4:"a7dd";s:24:"graphics/m_small_map.gif";s:4:"9eea";s:25:"graphics/mh_small_map.gif";s:4:"61ab";s:17:"graphics/novo.gif";s:4:"2abe";s:18:"graphics/phone.gif";s:4:"5795";s:16:"graphics/pin.png";s:4:"6f58";s:25:"graphics/sc_small_map.gif";s:4:"0c2a";s:18:"graphics/Thumbs.db";s:4:"4e5a";s:21:"graphics/transpix.gif";s:4:"fc94";s:19:"graphics/update.png";s:4:"d8d1";s:17:"graphics/user.png";s:4:"a8b9";s:20:"graphics/utbkgnd.gif";s:4:"0dfd";s:24:"graphics/v_small_map.gif";s:4:"e953";s:21:"graphics/visa_mer.gif";s:4:"c92e";s:24:"graphics/visa_mer_en.gif";s:4:"411e";s:25:"graphics/visa_mer_new.gif";s:4:"b4eb";s:24:"graphics/visa_mer_se.gif";s:4:"b4eb";s:24:"graphics/husbilder/a.jpg";s:4:"366b";s:27:"graphics/husbilder/ikdc.jpg";s:4:"628c";s:28:"graphics/husbilder/Thumbs.db";s:4:"3a72";s:14:"pi1/bibtex.php";s:4:"d703";s:34:"pi1/class.tx_institutioner_pi1.php";s:4:"b260";s:16:"pi1/fe_index.php";s:4:"c1b5";s:20:"pi1/HistoryFrame.htm";s:4:"8d56";s:20:"pi1/institutioner.js";s:4:"6fbc";s:28:"pi1/institutioner_normal.css";s:4:"4270";s:26:"pi1/institutioner_wide.css";s:4:"f412";s:17:"pi1/locallang.xml";s:4:"01c9";s:14:"pi1/titles.xml";s:4:"b807";s:31:"pi1/transtab_unicode_bibtex.php";s:4:"5a59";s:41:"pi1/tx_srfeuserregister_pi1_css_tmpl.html";s:4:"6179";s:25:"pi1/flash/campuskarta.swf";s:4:"65e8";s:26:"pi1/flash/campuskarta2.swf";s:4:"b751";s:26:"pi1/flash/campuskarta3.swf";s:4:"c45a";s:26:"pi1/flash/campuskarta4.swf";s:4:"660c";s:26:"pi1/flash/campuskarta5.swf";s:4:"69e1";s:26:"pi1/flash/campuskarta6.swf";s:4:"8c39";s:29:"pi1/flash/campuskarta_as2.swf";s:4:"0b5c";s:19:"pi1/flash/test2.swf";s:4:"19b7";s:36:"pi1/flash/_notes/campuskarta.swf.mno";s:4:"9773";s:37:"pi1/flash/_notes/campuskarta2.swf.mno";s:4:"9773";s:37:"pi1/flash/_notes/campuskarta3.swf.mno";s:4:"9773";s:37:"pi1/flash/_notes/campuskarta4.swf.mno";s:4:"9773";s:37:"pi1/flash/_notes/campuskarta5.swf.mno";s:4:"9773";s:37:"pi1/flash/_notes/campuskarta6.swf.mno";s:4:"9773";s:40:"pi1/flash/_notes/campuskarta_as2.swf.mno";s:4:"d0ed";s:30:"pi1/flash/_notes/test2.swf.mno";s:4:"72c5";s:28:"pi1/graphics/ajax-loader.gif";s:4:"2a66";s:28:"pi1/graphics/button_down.gif";s:4:"fa54";s:26:"pi1/graphics/button_up.gif";s:4:"0cc7";s:32:"pi1/graphics/forstoringsglas.jpg";s:4:"cf9d";s:27:"pi1/graphics/gra_streck.gif";s:4:"f122";s:25:"pi1/graphics/loading3.gif";s:4:"b0fa";s:21:"pi1/graphics/lock.gif";s:4:"1fc3";s:20:"pi1/graphics/pdf.gif";s:4:"f165";s:26:"pi1/graphics/small_map.gif";s:4:"c26a";s:30:"pi1/graphics/system-search.png";s:4:"a85d";s:22:"pi1/graphics/Thumbs.db";s:4:"3072";s:23:"pi1/graphics/update.gif";s:4:"bcdc";s:24:"pi1/static/editorcfg.txt";s:4:"4c82";s:34:"pi2/class.tx_institutioner_pi2.php";s:4:"14c8";s:17:"pi2/locallang.xml";s:4:"ab43";s:24:"pi2/static/editorcfg.txt";s:4:"572b";}',
	'suggests' => array(
	),
);

?>