<?php
/*
 * Register necessary class names with autoloader
 */
$extensionPath = t3lib_extMgm::extPath('institutioner');
return array(
	'tx_institutioner_lucatimport' => $extensionPath . 'tasks/class.tx_institutioner_lucatimport.php',
);
?>