<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2006 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file:
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')){
	die("Sorry. You can't access directly to this file");
	}

function haveRight($module,$right){

	$matches=array(
			""  => array("","r","w"), // ne doit pas arriver normalement
			"r" => array("r","w"),
			"w" => array("w"),
			"1" => array("1"),
			"0" => array("0","1"), // ne doit pas arriver non plus
		      );

	if (isset($_SESSION["glpiactiveprofile"][$module])&&in_array($_SESSION["glpiactiveprofile"][$module],$matches[$right]))
		return true;
	else return false;
}


/**
 * Get The Type Name of the Object
 *
 * @return String: name of the object type in the current language
 */
function haveTypeRight ($type,$right){
	global $LANG;

	switch ($type){
		case GENERAL_TYPE :
			return true;;
			break;
		case COMPUTER_TYPE :
			return haveRight("computer",$right);
			break;
		case NETWORKING_TYPE :
			return haveRight("networking",$right);
			break;
		case PRINTER_TYPE :
			return haveRight("printer",$right);
			break;
		case MONITOR_TYPE : 
			return haveRight("monitor",$right);
			break;
		case PERIPHERAL_TYPE : 
			return haveRight("peripheral",$right);
			break;				
		case PHONE_TYPE : 
			return haveRight("phone",$right);
			break;				
		case SOFTWARE_TYPE : 
		case LICENSE_TYPE : 
			return haveRight("software",$right);
			break;				
		case CONTRACT_TYPE : 
			return haveRight("contract_infocom",$right);
			break;				
		case ENTERPRISE_TYPE : 
			return haveRight("contact_enterprise",$right);
			break;
		case CONTACT_TYPE : 
			return haveRight("contact_enterprise",$right);
			break;
		case KNOWBASE_TYPE : 
			return haveRight("knowbase",$right);
			break;	
		case USER_TYPE : 
			return haveRight("user",$right);
			break;	
		case TRACKING_TYPE : 
			return haveRight("show_ticket",$right);
			break;	
		case CARTRIDGE_TYPE : 
			return haveRight("cartridge",$right);
			break;
		case CONSUMABLE_TYPE : 
			return haveRight("consumable",$right);
			break;					
		case LICENSE_TYPE : 
			return haveRight("software",$right);
			break;					
		case CARTRIDGE_ITEM_TYPE : 
			return haveRight("cartridge",$right);
			break;
		case CONSUMABLE_ITEM_TYPE : 
			return haveRight("consumable",$right);
			break;					
		case DOCUMENT_TYPE : 
			return haveRight("document",$right);
			break;					
		case GROUP_TYPE : 
			return haveRight("group",$right);
			break;					
	}
	return false;
}


function displayRightError(){
	global $LANG,$CFG_GLPI,$HEADER_LOADED;
	if (!$HEADER_LOADED){
			if (!isset($_SESSION["glpiactiveprofile"]["interface"]))
				nullHeader($LANG["login"][5],$_SERVER['PHP_SELF']);
			else if ($_SESSION["glpiactiveprofile"]["interface"]=="central")
				commonHeader($LANG["login"][5],$_SERVER['PHP_SELF']);
			else if ($_SESSION["glpiactiveprofile"]["interface"]=="helpdesk")
				helpHeader($LANG["login"][5],$_SERVER['PHP_SELF']);
		}
		echo "<div align='center'><br><br><img src=\"".$CFG_GLPI["root_doc"]."/pics/warning.png\" alt=\"warning\"><br><br>";
		echo "<b>".$LANG["login"][5]."</b></div>";
		nullFooter();
		exit();
}



function checkRight($module,$right) {
	global $CFG_GLPI;

	if (!haveRight($module,$right)){
		// Gestion timeout session
		if (!isset($_SESSION["glpiID"])){
			glpi_header($CFG_GLPI["root_doc"]."/index.php");
			exit();
		}

		displayRightError();
	}
}

function checkSeveralRightsOr($modules) {
	global $CFG_GLPI;

	$valid=false;
	if (count($modules))
		foreach ($modules as $mod => $right)
			if (haveRight($mod,$right)) $valid=true;

	if (!$valid){
		// Gestion timeout session
		if (!isset($_SESSION["glpiID"])){
			glpi_header($CFG_GLPI["root_doc"]."/index.php");
			exit();
		}

		displayRightError();
	}
}

function checkSeveralRightsAnd($modules) {
	global $CFG_GLPI;

	$valid=true;
	if (count($modules))
		foreach ($modules as $mod => $right)
			if (!haveRight($mod,$right)) $valid=false;

	if (!$valid){
		// Gestion timeout session
		if (!isset($_SESSION["glpiID"])){
			glpi_header($CFG_GLPI["root_doc"]."/index.php");
			exit();
		}
		displayRightError();
	}
}

function checkTypeRight($type,$right) {
	global $CFG_GLPI;

	if (!haveTypeRight($type,$right)){
		// Gestion timeout session
		if (!isset($_SESSION["glpiID"])){
			glpi_header($CFG_GLPI["root_doc"]."/index.php");
			exit();
		}
		displayRightError();
	}
}

function checkCentralAccess(){

	global $CFG_GLPI;

	if (!isset($_SESSION["glpiactiveprofile"])||$_SESSION["glpiactiveprofile"]["interface"]!="central"){
		// Gestion timeout session
		if (!isset($_SESSION["glpiID"])){
			glpi_header($CFG_GLPI["root_doc"]."/index.php");
			exit();
		}
		displayRightError();
	}
}

function checkHelpdeskAccess(){

	global $CFG_GLPI;

	if (!isset($_SESSION["glpiactiveprofile"])||$_SESSION["glpiactiveprofile"]["interface"]!="helpdesk"){
		// Gestion timeout session
		if (!isset($_SESSION["glpiID"])){
			glpi_header($CFG_GLPI["root_doc"]."/index.php");
			exit();
		}
		displayRightError();
	}
}

function checkLoginUser(){

	global $CFG_GLPI;

	if (!isset($_SESSION["glpiname"])){
		// Gestion timeout session
		if (!isset($_SESSION["glpiID"])){
			glpi_header($CFG_GLPI["root_doc"]."/index.php");
			exit();
		}
		displayRightError();
	}
}

function checkFaqAccess(){
	global $CFG_GLPI;

	if ($CFG_GLPI["public_faq"] == 0 && !haveRight("faq","r")){
		displayRightError();
	}

}


/**
 * Include the good language dict.
 *
 * Get the default language from current user in $_SESSION["glpilanguage"].
 * And load the dict that correspond.
 *
 * @return nothing (make an include)
 *
 */
function loadLanguage() {

	global $LANG,$CFG_GLPI;
	$file="";

	if(empty($_SESSION["glpilanguage"])) {
		if (isset($CFG_GLPI["languages"][$CFG_GLPI["default_language"]][1])){
			$file= "/locales/".$CFG_GLPI["languages"][$CFG_GLPI["default_language"]][1];
		}
	} else {
		if (isset($CFG_GLPI["languages"][$_SESSION["glpilanguage"]][1])){
			$file = "/locales/".$CFG_GLPI["languages"][$_SESSION["glpilanguage"]][1];
		}
	}
	if (empty($file)||!is_file(GLPI_ROOT . $file)){
		$file="/locales/en_GB.php";
	}
	$options = array(
		'cacheDir' => GLPI_DOC_DIR."/_cache/",
		'lifeTime' => DEFAULT_CACHE_LIFETIME,
		'automaticSerialization' => true,
		'caching' => ENABLE_CACHE,
		'hashedDirectoryLevel' => 2,
		'masterFile' => GLPI_ROOT . $file,
		'fileLocking' => CACHE_FILELOCKINGCONTROL,
		'writeControl' => CACHE_WRITECONTROL,
		'readControl' => CACHE_READCONTROL,
	);
	$cache = new Cache_Lite_File($options);

	// Set a id for this cache : $file
	if (!($LANG = $cache->get($file,"GLPI_LANG"))) {
		// Cache miss !
		// Put in $LANG datas to put in cache
		include (GLPI_ROOT . $file);
		$cache->save($LANG,$file,"GLPI_LANG");
	}
	

	// Debug display lang element with item
	if ($CFG_GLPI["debug"]&&$CFG_GLPI["debug_lang"]){
		foreach ($LANG as $module => $tab){
			foreach ($tab as $num => $val){
				$LANG[$module][$num].="<span style='font-size:12px; color:red;'>$module/$num</span>";
			}
		}
	}

}

// Init glpiprofiles in _SESSION variable
function initEntityProfiles($userID){
	global $DB;

	$profile=new Profile;

	$query="SELECT DISTINCT glpi_profiles.* FROM glpi_users_profiles INNER JOIN glpi_profiles ON (glpi_users_profiles.FK_profiles = glpi_profiles.ID)
		WHERE glpi_users_profiles.active='1' AND glpi_users_profiles.FK_users='$userID'";
	$result=$DB->query($query);
	$_SESSION['glpiprofiles']=array();
	if ($DB->numrows($result)){
		while ($data=$DB->fetch_assoc($result)){
			$profile->fields=array();
			$profile->getFromDB($data['ID']);
			$profile->cleanProfile();
			$_SESSION['glpiprofiles'][$data['ID']]=$profile->fields;
		}
		
		foreach ($_SESSION['glpiprofiles'] as $key => $tab){
			$query2="SELECT glpi_users_profiles.FK_entities as eID, glpi_users_profiles.ID as kID, glpi_users_profiles.recursive as recursive, glpi_entities.* FROM glpi_users_profiles LEFT JOIN glpi_entities ON (glpi_users_profiles.FK_entities = glpi_entities.ID)
				WHERE glpi_users_profiles.FK_profiles='$key' AND glpi_users_profiles.active='1' AND glpi_users_profiles.FK_users='$userID'";
			$result2=$DB->query($query2);
			if ($DB->numrows($result2)){
				while ($data=$DB->fetch_array($result2)){
					$_SESSION['glpiprofiles'][$key]['entities'][$data['kID']]['ID']=$data['eID'];
					$_SESSION['glpiprofiles'][$key]['entities'][$data['kID']]['name']=$data['name'];
					$_SESSION['glpiprofiles'][$key]['entities'][$data['kID']]['completename']=$data['completename'];
					$_SESSION['glpiprofiles'][$key]['entities'][$data['kID']]['recursive']=$data['recursive'];
				}
			}
		}
	}
}

// Change current active profile
function changeProfile($ID){
	global $CFG_GLPI;
	if (isset($_SESSION['glpiprofiles'][$ID])&&count($_SESSION['glpiprofiles'][$ID]['entities'])){
		// glpiactiveprofile -> active profile
		$_SESSION['glpiactiveprofile']=$_SESSION['glpiprofiles'][$ID];
		$_SESSION['glpiactiveentities']=array();
		// glpiactiveentities -> active entities
		foreach ($_SESSION['glpiactiveprofile']['entities'] as $key => $val){
			if (!$val['recursive']){
				if (!array_search($val["ID"],$_SESSION['glpiactiveentities'])){
					$_SESSION['glpiactiveentities'][$val['ID']]=$val['ID'];
				}
			} else {
				$entities=getSonsOfTreeItem("glpi_entities",$val['ID']);
				if (count($entities)){
					foreach ($entities as $key2 =>$val2){
						$_SESSION['glpiactiveentities'][$val2]=$val2;	
					}
				}
			}
		}
		changeActiveEntity(key($_SESSION['glpiactiveentities']));
	}
	$CFG_GLPI["cache"]->remove($_SESSION["glpiID"],"GLPI_HEADER");
}

function changeActiveEntity($value){
	$_SESSION["glpiactive_entity"]=$value;
	loadGroups();

}

function loadGroups(){
	global $DB;

	$_SESSION["glpigroups"]=array();
	$query_gp="SELECT * FROM glpi_users_groups LEFT JOIN glpi_groups ON (glpi_users_groups.FK_groups = glpi_groups.ID) WHERE FK_users='".$_SESSION['glpiID']."' AND glpi_groups.FK_entities='".$_SESSION["glpiactive_entity"]."'";

	$result_gp=$DB->query($query_gp);
	if ($DB->numrows($result_gp)){
		while ($data=$DB->fetch_array($result_gp)){
			$_SESSION["glpigroups"][]=$data["FK_groups"];
		}
	}
}

// Check if you could access to the entity of id = $ID
function haveAccessToEntity($ID){
	if (isset($_SESSION['glpiactiveentities'])){
		return in_array($ID,$_SESSION['glpiactiveentities']);
	} else {
		return false;
	}
}
function getEntitiesRestrictRequest($separator="AND",$table="",$field=""){
	
	if (in_array(0,$_SESSION['glpiactiveentities'])){
		return "";
	}
	
	$query=$separator." ( ";

	if (count($_SESSION['glpiactiveentities'])==1){

		if (!empty($table)){
			$query.=$table.".";
		}

		if (!empty($field)){
			$query.=$field;
		} else {
			$query.="FK_entities";
		}
		$query.="=".current($_SESSION['glpiactiveentities']);
	} else {
		$first=true;
		foreach ($_SESSION['glpiactiveentities'] as $key => $val){
			if (!$first) {
				$query.=" OR ";
			} else {
				$first=false;
			}
		
			if (!empty($table)){
				$query.=$table.".";
			}
	
			if (!empty($field)){
				$query.=$field;
			} else {
				$query.="FK_entities";
			}
			$query.="=".$val;
		
		}
	}
	$query.=" ) ";
	return $query;
}

function connect_ldap($host,$port,$login="",$password=""){
	global $CFG_GLPI;

	$ds = @ldap_connect ($host,$port);
	if ($ds){
		@ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
		@ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
		if ($CFG_GLPI["ldap_use_tls"]){
			if (!@ldap_start_tls($ds)) {
				return false;
			} 
		}
		// Auth bind
		if ($login != '') {
			$b = @ldap_bind($ds, $login, $password);
		} else { // Anonymous bind
			$b = @ldap_bind($ds);
		}
		if ($b) {
			return $ds;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

?>
