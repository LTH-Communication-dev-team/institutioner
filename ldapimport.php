<?php
ini_set('memory_limit', '-1');
error_reporting(E_ERROR | E_WARNING | E_PARSE);
set_time_limit(0);
//error_reporting(E_ALL);

$admin_userid = 1;
$site_admin_usergroupid = 104;
//WHAT is this?!

//ldapOrganizations("departmentNumber=000006000,ou=Organizations,dc=lu,dc=se");
//ldapOrganizations("departmentNumber=000007000,departmentNumber=000006100,ou=Organizations,dc=lu,dc=se");
//$pid = "2199";
$pid = "5636";
//$pid = "105";
//$pid = "80";
//$pid = "12";
$dbhost = "dbmysql.kansli.lth.se";
//$dbhost = "localhost";
$db = "t3_clone";
//$db = "t3";
//$db="test";
//$db="typo_45";
//$db="typo3_demo";
//die(getLastModDate($pid, $db, $dbhost));
$lastmoddate = '';//getLastModDate($pid, $db, $dbhost);
//die(date('YmdHis\Z'));
//ldapOrganizationsTest($pid, $db, $dbhost);
	
		//L�ser in be_groupsArray
//$be_groupsArray = getBe_groupsArray($db, $dbhost);
	//	$perms_groupid = $be_groupsArray[$departmentnumber];
	
ldapOrganizations($pid, $db, $dbhost, $be_groupsArray, $admin_userid, $site_admin_usergroupid);

ldapTest($pid, $db, $dbhost, $lastmoddate);

echo 'ldapimport done';

function getLastModDate($pid, $db, $dbhost)
{
    //Database
    $conn = mysql_connect($dbhost, "fe_user_update", "ibi124Co") or die("35; ".mysql_error());
    $databas = mysql_select_db($db,$conn);
    $sql = "select MAX(tstamp) as maxdate from fe_users where tx_institutioner_lth_search=1";
    $result = mysql_query($sql) or die("40: ".mysql_error());
    $row = mysql_fetch_array($result);
    $maxdate = $row['maxdate'];
    mysql_close($conn);
    return date("YmdHis\Z", $maxdate-3600);
}

function ldapTest($pid, $db, $dbhost, $lastmoddate)
{
        if($lastmoddate) $lastmoddate = "(modifytimestamp>=$lastmoddate)";
	/*$ldap_server = "ldap://ldap.lu.se:389";
	$auth_user = "";
	$auth_pass = "";
	$base_dn = "ou=people,dc=lu,dc=se";*/
	$lu_ldaphost = "ldap.lu.se ldap.student.lu.se";
	$dn = "uid=system_lth_fud,ou=admin,dc=lu,dc=se";
	$basedn = "ou=people,dc=lu,dc=se";
	$lu_pwd = 'repFUD287!x';
	
	$ds = ldap_connect($lu_ldaphost);
	if (!ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3)) {
		return "Failed to set LDAP Protocol version to 3, TLS not supported.";
	}
	if (!ldap_start_tls($ds)) {
		return "Ldap_start_tls failed";
	}
	//$ldapbind = @ldap_bind($ds, $dn, $lu_pwd);
	
	$r = @ldap_bind($ds, $dn, $lu_pwd);//ldap_bind($ds,$dn,'');
	//echo "\n" . ldap_error($ds) . "\n";
	//$filter = "(&(|(eduPersonOrgUnitDN=departmentnumber=*000006000*)(eduPersonOrgUnitDN=departmentNumber=*000007000*)(eduPersonPrimaryOrgUnitDN=departmentnumber=*000006000*)(eduPersonPrimaryOrgUnitDN=departmentNumber=*000007000*))(inetUserStatus=active))";
	//$filter = "(&(inetUserStatus=active)(eduPersonAffiliation=employee)(inetUserStatus=active)$lastmoddate)";
        $filter = "(&(inetUserStatus=active)(eduPersonAffiliation=employee)(inetUserStatus=active))";
	$justthese = array("uid", "departmentnumber", "ou", "sn", "givenname", "mail", "telephonenumber", "title", "labeleduri","roomnumber","registredaddress","street","postalcode","postalofficebox","luEduPersonPrivacy");
	$search = ldap_search($ds, $basedn, $filter, $justthese);
	if(!$search){
            echo 'no search';
            return false;
	}
	//LTH: 000006000
	//Kommservice: 011100007
	/*if (!($connect=@ldap_connect($ldap_server))) {
		 die("Could not connect to ldap server");
	}	
	if (!($bind=@ldap_bind($connect, $auth_user, $auth_pass))) {
		 die("Unable to bind to server");
	}
	if (!($search=@ldap_search($connect, $base_dn, $filter, $justthese))) {
		 die("Unable to search ldap server");
	}*/
	
	$number_returned = ldap_count_entries($ds,$search);
	$info = ldap_get_entries($ds, $search);
	//echo "The number of entries returned is ". $number_returned;
	
		//Database
	$conn = mysql_connect($dbhost, "fe_user_update", "ibi124Co") or die("45; ".mysql_error());
	$databas = mysql_select_db($db);
	
		//Trunkera
	$sql = "TRUNCATE TABLE tx_lucat";
	mysql_query($sql) or die("103: ".mysql_error());
	
		//L�ser in gruppid
	$sql = "SELECT uid, tx_institutioner_lucatid FROM fe_groups";
	$result = mysql_query($sql) or die("81: ".mysql_error());
	while($row = mysql_fetch_array($result)) {
		//return true;
		$fe_groups_id_array[$row['tx_institutioner_lucatid']] = $row['uid'];
	}

		//L�ser in grupptillh�righet
	$sql = "SELECT username, usergroup FROM fe_users";
	$result = mysql_query($sql) or die("89: ".mysql_error());
	while($row = mysql_fetch_array($result)) {
		//return true;
		$fe_users_usergroup_array[$row['username']] = $row['usergroup'];
	}
	
	for ($i=0; $i<$info["count"]; $i++) {

		//Tilldela v�rden till variablerna
		$username = $info[$i]["uid"][0];
		$departmentnumber = $info[$i]["departmentnumber"][0];
		//fe-gruppens uid
		$fe_groups_id = $fe_groups_id_array[$departmentnumber];
		//fe-anv�ndarens usergroup
		$fe_user_usergroup = $fe_users_usergroup_array[$username];
		//skapa array av fe-anv�ndarens usergroup
		$fe_user_userarray = explode(",", $fe_user_usergroup);
		if(count($fe_user_userarray ) > 1) {
			if(!in_array($fe_groups_id, $fe_user_userarray)) $fe_user_usergroup .= "," . $fe_groups_id;
		} else {
			if(!in_array($fe_groups_id, $fe_user_userarray)) $fe_user_usergroup = $fe_groups_id;
		}
                
		//$name = utf8_decode($info[$i]["cn"][0]);
		$fnamn = mysql_real_escape_string(utf8_decode($info[$i]["givenname"][0]));
		$enamn = mysql_real_escape_string(utf8_decode($info[$i]["sn"][0]));
		$mail = mysql_real_escape_string($info[$i]["mail"][0]);
		$telephonenumber = mysql_real_escape_string(str_replace(" ", "", $info[$i]["telephonenumber"][0]));
		$title = mysql_real_escape_string(utf8_decode($info[$i]["title"][0]));
		$labeleduri = mysql_real_escape_string($info[$i]["labeleduri"][0]);
                $ou = mysql_real_escape_string(utf8_decode($info[$i]["ou"][0]));
                $roomnumber = mysql_real_escape_string(utf8_decode($info[$i]["roomnumber"][0]));
                $registeredaddress = mysql_real_escape_string($info[$i]["registeredaddress"][0]);
                $street = mysql_real_escape_string(utf8_decode($info[$i]["street"][0]));
                $postalcode = mysql_real_escape_string(utf8_decode($info[$i]["postalcode"][0]));
                $postofficebox = mysql_real_escape_string(utf8_decode($info[$i]["postofficebox"][0]));
		$donotdisplayweb = mysql_real_escape_string(utf8_decode($info[$i]["luedupersonprivacy"][0]));
                
                if(!$registredaddress) $registredaddress="NULL";
                
		//H�mta sysfolderns uid
		$sql = "SELECT uid FROM pages WHERE deleted=0 AND hidden=0 AND doktype=254 AND subtitle = '" . trim($departmentnumber) . "'";
		$result = mysql_query($sql) or die("151".mysql_error());
		$row = mysql_fetch_array($result);
		$tmpPid = $row['uid'];
		if($tmpPid) $pid = $tmpPid;
		$pid = mysql_real_escape_string($pid);
		//echo "$uid;$name;$departmentnumber;$mail;$telephonenumber<br />"; 
		$sql = "INSERT INTO tx_lucat(pid, username, usergroup, name, mail, telephonenumber, first_name, last_name, title, www, ou, roomnumber, registeredaddress, street,postalcode,postofficebox,donotdisplayweb) ";
                $sql .= "VALUES($pid, '$username', '$fe_user_usergroup', '$enamn, $fnamn', '$mail', '$telephonenumber', '$fnamn' ,'$enamn', '$title', '$labeleduri', '$ou', '$roomnumber', '$registeredaddress', '$street','$postalcode','$postofficebox','$donotdisplayweb')";
		mysql_query($sql) or die("161: ".mysql_error().$sql);
	}
	
		//Uppdatera fe_users
	$sql = "UPDATE fe_users F INNER JOIN tx_lucat L ON F.username = L.username ";
	$sql .= "SET F.usergroup = L.usergroup, F.name = L.name, F.email = L.mail, F.telephone = L.telephonenumber, F.first_name = L.first_name, F.last_name = L.last_name, ";
	$sql .= "F.title=L.title, F.www=L.www, F.ou=L.ou, F.roomnumber=L.roomnumber, F.registeredaddress=L.registeredaddress, F.address=L.postofficebox, F.zip=L.postalcode, ";
        $sql .= "F.street=L.street, F.deleted = 0, F.tstamp = UNIX_TIMESTAMP(), F.tx_institutioner_lth_search = 1, L.updateflag = 1, F.module_sys_dmail_html = 1, F.pid = L.pid, F.tx_institutioner_donotdisplayweb = L.donotdisplayweb";
	mysql_query($sql) or die("141: ".mysql_error());
	
		//L�gg in nya anv�ndare i fe_users
	$sql = "INSERT INTO fe_users(pid, username, password, name, email, telephone, first_name, last_name, title, www, ou, roomnumber, registeredaddress, address, zip, street, usergroup, tstamp, crdate, tx_institutioner_lth_search, module_sys_dmail_html, tx_institutioner_donotdisplayweb) ";
	$sql .= "SELECT pid, username, MD5(RAND()), name, mail, telephonenumber, first_name, last_name, title, www, ou, roomnumber, registeredaddress, postofficebox, postalcode, street, usergroup, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 1, 1, donotdisplayweb FROM tx_lucat WHERE updateflag=0";
	mysql_query($sql) or die("146: ".mysql_error());
	
		//Ta bort anv�ndare som inte finns i lucat
	$sql = "UPDATE fe_users F LEFT JOIN tx_lucat T ON F.username = T.username SET deleted = 1 WHERE F.tx_institutioner_lth_search = 1 AND T.username is null";
	mysql_query($sql) or die("150: ".mysql_error());
	
	mysql_close($conn);
	ldap_free_result($search);
	echo 'ldaptest done';
}

function ldapOrganizations($pid, $db, $dbhost, $be_groupsArray, $admin_userid, $site_admin_usergroupid) {
	/*$base_dn = "ou=Organizations,dc=lu,dc=se";
	$ldap_server = "ldap://ldap.lu.se:389";
	$auth_user = "";
	$auth_pass = "";*/
	$lu_ldaphost = "ldap.lu.se ldap.student.lu.se";
	$dn = "uid=system_lth_fud,ou=admin,dc=lu,dc=se";
	$basedn = "ou=organizations,dc=lu,dc=se";
	$lu_pwd = 'repFUD287!x';
	
	$ds = ldap_connect($lu_ldaphost);
	if (!ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3)) {
		return "Failed to set LDAP Protocol version to 3, TLS not supported.";
	}
	if (!ldap_start_tls($ds)) {
		return "Ldap_start_tls failed";
	}

	$filter = "(departmentNumber=*)";
	$printFlag = false;
	/*if (!($ds=@ldap_connect($ldap_server))) {
		die("Could not connect to ldap server");
	}*/
	$justthese = array("ou", "departmentnumber");
	//$search = ldap_search( $ds, $base_dn, $filter, $justthese );
	$r = @ldap_bind($ds, $dn, $lu_pwd);
	$search = ldap_search($ds, $basedn, $filter, $justthese);
	if(!$search){
		echo 'no search!';
		return false;
	}
	
	$entry = ldap_first_entry( $ds, $search );

	while( $entry ){
		$parentDep = "";
		$parentGroupId = '';
		$parentFolderId = '';
		$departmentnumber = '';
		
		//Plats i organisationstr�det: child:parent:grandparent etc
		$dn = ldap_get_dn( $ds, $entry );

		$dn = str_ireplace(",ou=Organizations,dc=lu,dc=se", "", $dn);
		$dn = str_ireplace("departmentNumber=", "", $dn);
//print $dn . "\n";
		$dn_array = explode(",",$dn);
		//$antal = count($dn_array);
		//for( $i=0; $i<$antal; $i++ ){
		//	if($antal > 1) {
			// and (stristr($dn, "000006000") or stristr($dn, "000007000"))) {
		//		$subgroup = $dn_array[1];
//print $dn_array[0] . ':::' . $dn_array[1] . "\n";
				//Plocka ut uid fr�n gruppen ovanf�r
		//		$subgroupId = getGroupId($subgroup, $db, $dbhost);
		//		$parentId = getPageid($subgroup, $db, $dbhost);
				//print $subgroup . ";" . $subgroupId . ";" . $parentId . "\n";
		//	}
		//}
		if(count($dn_array) > 1) {
			$parentDep = $dn_array[1];
			$parentGroupId = getGroupId($parentDep, $db, $dbhost);
			$parentFolderId = getPageid($parentDep, $db, $dbhost);
		}

		$attrs = ldap_get_attributes( $ds, $entry );
		//$ou = utf8_decode($attrs[$attrs[1]][0]);
		if($attrs[0]=="ou") {
			$ou =  utf8_decode($attrs[$attrs[0]][0]);
			$ou_eng = utf8_decode($attrs[$attrs[1]][0]);
		} else {
			$ou = utf8_decode($attrs[$attrs[1]][0]);
			$ou_eng = utf8_decode($attrs[$attrs[0]][0]);
		}
		//$departmentnumber = $attrs[$attrs[2]][0];
		$departmentnumber = $dn_array[0];
//print $departmentnumber . "\n";
		//if(stristr($dn, "000006000") or stristr($dn, "000007000")) {
			//print "$pid; $ou; $departmentnumber; $subgroupId; $antal; $db; $dbhost\n";
			if(!$parentFolderId) $parentFolderId = $pid;
			folderExist($pid, $ou, $departmentnumber, $parentFolderId, $antal, $db, $dbhost, $be_groupsArray, $admin_userid, $site_admin_usergroupid);
			
			$myPid = getPageid($departmentnumber, $db, $dbhost);
			if(!$myPid) $myPid = $pid;
			//print $departmentnumber . "; " . $parentGroupId . "; " . $subgroup . "; " . $ou . "; " . $antal . "; " . $myPid . "\n";
			if(checkFegroupExist($departmentnumber, $db, $dbhost) == false) {
				insertFegroup($myPid, $ou, $ou_eng, $departmentnumber, $parentGroupId, $db, $dbhost);
			} else {
				updateFegroup($myPid, $ou, $ou_eng, $departmentnumber, $parentGroupId, $db, $dbhost);
			}
		//}
		
		$entry = ldap_next_entry( $ds, $entry );
	}

	ldap_free_result( $search );
	echo 'ldaporganizations done';
	//Vad vara detta? Nån skämta med mig aprilo!!!!!!!!!!!!!!!!!!!!!!!!!
}

function folderExist($pid, $ou, $departmentnumber, $parentId, $antal, $db, $dbhost, $be_groupsArray, $admin_userid, $site_admin_usergroupid)
{
	//$be_groupsArray[0] = 5645454554
	//$myArray[5645454554] = 0
	
	if(is_array($be_groupsArray)) $myArray = array_flip($be_groupsArray);
	//$perms_groupid = $myArray[$departmentnumber];
	if(!$perms_groupid) $perms_groupid = "0";
	//if($antal > 1 and $parentId) $pid = $parentId;
	$conn = mysql_connect($dbhost, "fe_user_update", "ibi124Co") or die("158; ".mysql_error());
	$databas = mysql_select_db($db);
	$sql = "SELECT * FROM pages WHERE subtitle = '" . cleanChar($departmentnumber) . "' AND deleted=0 AND hidden=0 AND doktype=254";
	//print $sql.':::';
	$result = mysql_query($sql) or die("240: " . mysql_error() . $sql);
	$row = mysql_fetch_array($result);
	if(!$row) {
		//$ou=str_replace(",", "", $ou);
		$sql = "INSERT INTO pages(pid, title, perms_userid, perms_groupid, perms_group, perms_user, subtitle, doktype, crdate) VALUES($parentId, '" . cleanChar($ou) . "', $admin_userid, $site_admin_usergroupid, 31, 1, '" . cleanChar($departmentnumber) . "', 254, " . time() . ")";
		//print $sql.':::';
		mysql_unbuffered_query($sql) or die('246: ' . mysql_error() . $sql);
		mysql_close($conn);
		return "insert";
	} else {
		$uid = $row["uid"];
		$sql = "UPDATE pages SET pid = $parentId, subtitle = '" . cleanChar($departmentnumber) . "', perms_userid = $admin_userid, perms_groupid = $site_admin_usergroupid, perms_user = 31, perms_group = 1 WHERE uid = $uid";
		//print $sql.':::';
		mysql_unbuffered_query($sql) or die('253: ' . mysql_error().$sql);
		mysql_close($conn);
	}
}

function checkFegroupExist($departmentnumber, $db, $dbhost)
{
	$conn = mysql_connect($dbhost, "fe_user_update", "ibi124Co") or die("185; ".mysql_error());
	$databas = mysql_select_db($db);
	$sql = "SELECT * FROM fe_groups WHERE tx_institutioner_lucatid = '" . cleanChar($departmentnumber) . "'";
	$result = mysql_query($sql) or die("262: ".mysql_error().$sql);
	while($rad = mysql_fetch_array($result)) {
		return true;
	}
	mysql_close($conn);
}

function insertFegroup($pid, $ou, $ou_eng, $departmentnumber, $subgroup, $db, $dbhost)
{
	$subgroup = getFegroupUid($subgroup, $db, $dbhost);
	$conn = mysql_connect($dbhost, "fe_user_update", "ibi124Co") or die("188; ".mysql_error());
	$databas = mysql_select_db($db);
	$sql = "INSERT INTO fe_groups(pid, title, tx_institutioner_lucatid, subgroup, tx_institutioner_title_eng) VALUES($pid, '" . cleanChar($ou) . "', '" . cleanChar($departmentnumber) . "', '" . cleanChar($subgroup) . "', '" . cleanChar($ou_eng) . "')";
	//print $sql . "\n";
	mysql_unbuffered_query($sql) or die("275: " . mysql_error().$sql);
	mysql_close($conn);
}

function updateFegroup($pid, $ou, $ou_eng, $departmentnumber, $subgroup, $db, $dbhost)
{
	$perms_groupid = $be_groupsArray[$departmentnumber];
	$conn = mysql_connect($dbhost, "fe_user_update", "ibi124Co") or die("199; ".mysql_error());
	$databas = mysql_select_db($db);
	$sql = "UPDATE fe_groups SET pid=$pid, title='" . cleanChar($ou) . "', subgroup='" . cleanChar($subgroup) . "', tx_institutioner_title_eng = '" . cleanChar($ou_eng) . "' WHERE tx_institutioner_lucatid = '" . cleanChar($departmentnumber) . "' AND deleted = 0";
	//print $sql . "\n";
	mysql_unbuffered_query($sql) or die("284: " . mysql_error().$sql);
	mysql_close($conn);
}

function getPageid($departmentnumber, $db, $dbhost)
{
	//die($departmentnumber . $db . $dbhost);
	$conn = mysql_connect($dbhost, "fe_user_update", "ibi124Co") or die("251; ".mysql_error());
	$databas = mysql_select_db($db);
	$sql = "SELECT uid FROM pages WHERE deleted=0 AND hidden=0 AND subtitle = '" . cleanChar($departmentnumber) . "'";
	$result = mysql_query($sql) or die("293: ".mysql_error());
	$row = mysql_fetch_array($result);
	$uid = $row['uid'];
	mysql_close($conn);
	return $uid;
}

function getPid($departmentnumber, $db, $dbhost)
{
	//die($departmentnumber . $db . $dbhost);
	$conn = mysql_connect($dbhost, "fe_user_update", "ibi124Co") or die("251; ".mysql_error());
	$databas = mysql_select_db($db);
	$sql = "SELECT pid FROM pages WHERE deleted=0 AND hidden=0 AND subtitle = '" . cleanChar($departmentnumber) . "'";
	$result = mysql_query($sql) or die("293: ".mysql_error());
	$row = mysql_fetch_array($result);
	$pid = $row['pid'];
	mysql_close($conn);
	return $pid;
}

function getGroupId($departmentnumber, $db, $dbhost)
{
	//die($departmentnumber . $db . $dbhost);
	$conn = mysql_connect($dbhost, "fe_user_update", "ibi124Co") or die("263; ".mysql_error());
	$databas = mysql_select_db($db);
	$sql = "SELECT uid FROM fe_groups WHERE deleted=0 AND hidden=0 AND tx_institutioner_lucatid = '" . $departmentnumber . "'";
	$result = mysql_query($sql) or die("305: ".mysql_error());
	$row = mysql_fetch_array($result);
	$uid = $row["uid"];
	mysql_close($conn);
	return $uid;
}

function getFegroupUid($departmentnumber, $db, $dbhost) {
	$conn = mysql_connect($dbhost, "fe_user_update", "ibi124Co") or die("274; ".mysql_error());
	$databas = mysql_select_db($db);
	$sql = "SELECT uid FROM fe_groups WHERE tx_institutioner_lucatid = '" . $departmentnumber . "'";
	$result = mysql_query($sql) or die("316: ".mysql_error());
	$row = mysql_fetch_array($result);
	$uid = $row["uid"];
	mysql_close($conn);
	return $uid;
}

function getBe_groupsArray($db, $dbhost) {
	$conn = mysql_connect($dbhost, "fe_user_update", "ibi124Co") or die("285; ".mysql_error());
	$databas = mysql_select_db($db);
	$sql = "SELECT uid, tx_institutioner_lucatid FROM be_groups WHERE deleted=0 and tx_institutioner_lucatid != ''";
	$result = mysql_query($sql) or die("327: ".mysql_error());
	while($row = mysql_fetch_array($result)) {
		$uid = $row["uid"];
		$tx_institutioner_lucatid = $row["tx_institutioner_lucatid"];
		$be_groupsArray[$uid] = $tx_institutioner_lucatid;
	}
	mysql_close($conn);
	return $be_groupsArray;	
}

function cleanChar($input)
{
	return mysql_real_escape_string($input);
}