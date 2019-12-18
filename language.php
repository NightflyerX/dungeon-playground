<?php

if( session_status() === PHP_SESSION_NONE ){

	session_start();

}

if( isset( $_POST['language'] ) ){

	$_SESSION['lang'] = (string) $_POST['language'];

exit;
}

$available_languages = array("en", "de");

if( !isset( $_SESSION['lang'] ) ){

	function prefered_language($available_languages, $http_accept_language) {
	
		$available_languages = array_flip($available_languages);
		$langs = array();
		preg_match_all('~([\w-]+)(?:[^,\d]+([\d.]+))?~', strtolower($http_accept_language), $matches, PREG_SET_ORDER);
		foreach($matches as $match) {
		
			list($a, $b) = explode('-', $match[1]) + array('', '');
			$value = isset($match[2]) ? (float) $match[2] : 1.0;
			if(isset($available_languages[$match[1]])) {
				$langs[$match[1]] = $value;
				continue;
			}
			if(isset($available_languages[$a])) {
				$langs[$a] = $value - 0.1;
			}
		}
		if($langs) {
			arsort($langs);
			return key($langs); // We don't need the whole array of choices since we have a match
		}
	}

	$lang = prefered_language($available_languages, $_SERVER["HTTP_ACCEPT_LANGUAGE"]);

}else{

	$lang = $_SESSION['lang'];

}

$l = array(

	"de" => array(

	"YES" => "Ja",
	"NO" => "Nein",
	"NAVBAR_CAMPAIGN" => "Kampagne" ,
	"NAVBAR_CAMPAIGNS" => "Kampagnen" ,
	"NAVBAR_CAMPAIGN_SHOP" => "Kampagne-Laden",
	"NAVBAR_LIBRARY" => "Bibliothek",
	"NAVBAR_DATABASE" => "Datenbank",
	"NAVBAR_WEAPONS" => "Waffen" ,
	"NAVBAR_ACTIONS" => "Aktionen",
	"NAVBAR_EQUIPMENT" => "Ausr&uuml;stung",
	"NAVBAR_OVERVIEW_ACTIONS" => "&Uuml;-Aktionen",
	"NAVBAR_OVERVIEW_FIELDS" => "&Uuml;-Felder",
	"NAVBAR_CHARS" => "Charaktere",
	"NAVBAR_CHAR_NEW" => "Charakter erstellen",
	"NAVBAR_CHAR_LIST" => "Charakter-Liste",
	"NAVBAR_GAME" => "Game",
	"NAVBAR_LOGOUT" => "Logout",
	"GAME_ROUND" => "Runde",
	"GAME_NEXT_ROUND" => "N&auml;chste Runde",
	"GAME_CHARMULTIDELETE" => "Char multil&ouml;sch",
	"GAME_RESET" => "Reset",
	"GAME_DELETE" => "Spiel beenden",
	"GAME_NEW_FIELD" => "Neues Feld",
	"GAME_LOAD_FIELD" => "Feld laden",
	"GAME_TITLE_NEW_GAME" => "Neues Spiel starten",
	"GAME_MSG_NEW_GAME" => "Du bist der Dungeonmaster der aktuellen Kampagne und kannst ein Spiel erstellen",
	"GAME_BUTTON_NEW_GAME" => "Neues Spiel beginnen",
	"GAME_MSG_NEW_GAME_2" => "Derzeit l&auml;ft kein Spiel. Nur der aktuelle Dungeonmaster kann ein Spiel erstellen.",
	"GAME_POOLTOKEN_DELETE" => "Pooltoken l&ouml;schen",
	"GAME_FIELDS_OWNER" => "Besitzer",
	"GAME_FIELDS_TARGETS" => "Ziele",
	"GAME_FIELDS_STATUS" => "Status",
	"GAME_FIELDS_EVENTS" => "Events",
	"GAME_FIELDS_UPKEEP" => "Upkeep-Kosten",
	"GAME_EVENT_SHOW" => "Events anzeigen",
	"GAME_EVENT_REMOVE" => "Event entfernen",
	"FOOTER_MSG" => "Nachricht",
	"FOOTER_CHAT" => "Chat",
	"CAMPAIGN_CAMP_TITLE" => "Neue Kampagne",
	"CAMPAIGN_CAMP_NAME" => "Campagnenname",
	"CAMPAIGN_CAMP_TEXT" => "Beim Erstellen wirst du automatisch Dungeonmaster",
	"CAMPAIGN_CAMP_DESCR" => "Beschreibung",
	"CAMPAIGN_CAMP_PRIVATE" => "Private Kampagne",
	"CAMPAIGN_CAMP_PUBLIC" => "&Ouml;ffentliche Kampagne",
	"CAMPAIGN_CAMP_SUBMIT" => "Neue Kampagne erstellen",
	"CAMPAIGN_CAMP_EDIT" => "Kampagne editieren",
	"CAMPAIGN_STATUS_NEW" => "Neu",
	"CAMPAIGN_STATUS_ACTIVE" => "Aktiv",
	"CAMPAIGN_STATUS_FINISHED" => "Abgeschlossen",
	"CAMPAIGN_SHOP_ERROR_TITLE" => "Fehler! Keine ative Kampagne",
	"CAMPAIGN_SHOP_ERROR_MSG" => "Du musst erst eine Kampagne erstellen und sie aktivieren",
	"CAMPAIGN_SHOP_TITLE" => "Laden f&uuml;r",
	"CAMPAIGN_SHOP_SHOP" => "Laden",
	"CAMPAIGN_SHOP_WEAPONS" => "Waffen",
	"CAMPAIGN_SHOP_EQUIPMENT" => "Ausr&uuml;stung",
	"CAMPAIGN_SHOP_LIBRARY" => "Bibliothek",
	"CAMPAIGN_CREATED_MSG" => "Neue Kampagne erstellt",
	"EDIT_CAMPAIGN_TITLE" => "Kampagne &auml;ndern",
	"EDIT_CAMPAIGN_ERROR" => "Fehler",
	"EDIT_CAMPAIGN_ERROR_MSG1" => "Eine Kampagne kann nur aktiviert werden wenn keine andere Kampagne aktiv ist!",
	"EDIT_CAMPAIGN_ERROR_MSG2" => "Zugang verwehrt. Die Kampagne ist privat.",
	"LIB_TYPES_TYPES" => "Klassen",
	"LIB_TYPES_RACES" => "Rassen",
	"LIB_TYPES_ATTRIBUTES" => "Attribute",
	"LIB_TYPES_ACTION_TYPES" => "Aktions-typen",
	"LIB_TYPES_DAMAGE_TYPES" => "Schadens-typen",
	"LIB_TYPES_WEAPON_TYPES" => "Waffen-typen",
	"LIB_TYPES_POOLS" => "Pools",
	"LIB_TYPES_MODIFICATORS" => "Modifikatoren",
	"LIB_TYPES_DEFENSIVE_SKILLS" => "Defensiv-Skills (R&uuml;stungen/Schilde)",
	"LIB_TYPES_MAGIC_CLASSES" => "Magieklassen",
	"LIB_TYPES_MAGIC_TYPES" => "Magie-typen",
	"LIB_TYPES_SKILL_CLASSES" => "Andere Skillklassen",
	"LIB_TYPES_SKILL_TYPES" => "Andere Skilltypen",
	"LIB_TYPES_EFFECTS" => "Effekte",
	"LIB_TYPES_ALL_DAMAGE_TYPES" => "Alle Schadenstypen",
	"LIB_TYPES_SPECIAL_TOKEN" => "Spezialtoken",
	"LIB_TYPES_EVENTS" => "Events",
	"ACTION_CLASS" => "Klassenaktion",
	"ACTION_WEAPON" => "Waffenaktion",
	"ACTION_MAGICAL" => "Magieaktion",
	"ACTION_ITEM" => "Trank/Item-Aktion",
	"ACTION_OTHER" => "Sonstige Aktionen",
	"ACTION_DICEROLL" => "Wurf",
	"SHOW_PLAYER_MOVEMENT" => "Bewegung",
	"SHOW_PLAYER_POISON" => "Gift",
	"SHOW_PLAYER_SICKNESS" => "Krankheit",
	"SHOW_PLAYER_SHIELD" => "LP-Schild",
	"SHOW_PLAYER_EQUIPMENT" => "Ausr&uuml;stung",
	"SHOW_PLAYER_TIER" => "Tier",
	"SHOW_PLAYER_PHYSICAL" => "physisch",
	"SHOW_PLAYER_MAGICAL" => "magisch",
	"SHOW_PLAYER_SAVE_STATE" => "Status speichern",
	"SHOW_PLAYER_REMOVE_STATE" => "Status entfernen",
	"SHOW_PLAYER_DEL_CHAR_MSG" => "Ja, der Charakter soll aus dem Spiel entfernt werden",
	"SHOW_PLAYER_MANUAL_CHNG" => "Manuelle &Auml;nderung",
	"SHOW_PLAYER_LIFE_P" => "Lebenspunkte",
	"SHOW_PLAYER_MANA_P" => "Manapunkte",
	"SHOW_PLAYER_ACTION_P" => "Aktionspunkte",
	"NORMAL" => "Normal",
	"EXPERT" => "Experte",
	"MASTER" => "Meister",
	"GRANDMASTER" => "Grossmeister",
	"SETDATA_SETSTATE" => array( 'erh&auml;lt den Status','f&uuml;r','Runden' ),
	"SETDATA_DICEROLL_TARGET" => "ziel",
	"SETDATA_DAMAGE_ON" => "auf",
	),

	"en" => array( 

	"YES" => "Yes",
	"NO" => "No",
	"NAVBAR_CAMPAIGN" => "Campaign",
	"NAVBAR_CAMPAIGNS" => "Campaigns",
	"NAVBAR_CAMPAIGN_SHOP" => "Campaign-shop",
	"NAVBAR_LIBRARY" => "Library",
	"NAVBAR_DATABASE" => "Database",
	"NAVBAR_WEAPONS" => "Weapons",
	"NAVBAR_ACTIONS" => "Actions",
	"NAVBAR_EQUIPMENT" => "Items/Equipment",
	"NAVBAR_OVERVIEW_ACTIONS" => "OV-Actions",
	"NAVBAR_OVERVIEW_FIELDS" => "OV-Fields",
	"NAVBAR_CHARS" => "Characters",
	"NAVBAR_CHAR_NEW" => "New char",
	"NAVBAR_CHAR_LIST" =>"Character list",
	"NAVBAR_GAME" => "Game",
	"NAVBAR_LOGOUT" => "Logout",
	"GAME_ROUND" => "Round",
	"GAME_NEXT_ROUND" => "Next Round",
	"GAME_CHARMULTIDELETE" => "Char multidelete",
	"GAME_RESET" => "Reset",
	"GAME_DELETE" => "End Game",
	"GAME_NEW_FIELD" => "New field",
	"GAME_LOAD_FIELD" => "Load field",
	"GAME_TITLE_NEW_GAME" => "Start new game",
	"GAME_MSG_NEW_GAME" => "You're the dungeonmaster of the current campaign and can start a new game",
	"GAME_BUTTON_NEW_GAME" => "Start new game",
	"GAME_MSG_NEW_GAME_2" => "No game is currently running. Only the dungeonmaster can start a new game.",
	"GAME_POOLTOKEN_DELETE" => "Delete pool-tokens",
	"GAME_FIELDS_OWNER" => "Owner",
	"GAME_FIELDS_TARGETS" => "Targets",
	"GAME_FIELDS_STATUS" => "Status",
	"GAME_FIELDS_EVENTS" => "Events",
	"GAME_FIELDS_UPKEEP" => "Upkeep-Cost",
	"GAME_EVENT_SHOW" => "Show event",
	"GAME_EVENT_REMOVE" => "Remove event",
	"FOOTER_MSG" => "Message",
	"FOOTER_CHAT" => "Chat",
	"CAMPAIGN_CAMP_TITLE" => "New Campagne",
	"CAMPAIGN_CAMP_NAME" => "Campagne name",
	"CAMPAIGN_CAMP_TEXT" => "After creating you're automatically going to be the dungeon master",
	"CAMPAIGN_CAMP_DESCR" => "Description",
	"CAMPAIGN_CAMP_PRIVATE" => "private campaign",
	"CAMPAIGN_CAMP_PUBLIC" => "public campaign",
	"CAMPAIGN_CAMP_SUBMIT" => "Create new campagne",
	"CAMPAIGN_CAMP_EDIT" => "Edit campaign",
	"CAMPAIGN_STATUS_NEW" => "New",
	"CAMPAIGN_STATUS_ACTIVE" => "Active",
	"CAMPAIGN_STATUS_FINISHED" => "Finished",
	"CAMPAIGN_SHOP_ERROR_TITLE" => "Error! No active campaign",
	"CAMPAIGN_SHOP_ERROR_MSG" => "You first have to create a campaign and then activate it",
	"CAMPAIGN_SHOP_TITLE" => "Shop for",
	"CAMPAIGN_SHOP_SHOP" => "Shop",
	"CAMPAIGN_SHOP_WEAPONS" => "Weapons",
	"CAMPAIGN_SHOP_EQUIPMENT" => "Equipment",
	"CAMPAIGN_SHOP_LIBRARY" => "Library",
	"CAMPAIGN_CREATED_MSG" => "New campaign created",
	"EDIT_CAMPAIGN_TITLE" => "Edit campaign",
	"EDIT_CAMPAIGN_ERROR" => "Error",
	"EDIT_CAMPAIGN_ERROR_MSG1" => "A campaign can only be activated if no other campaign is active!",
	"EDIT_CAMPAIGN_ERROR_MSG2" => "Access denied. The campaign is private.",
	"LIB_TYPES_TYPES" => "Classes",
	"LIB_TYPES_RACES" => "Races",
	"LIB_TYPES_ATTRIBUTES" => "Attributes",
	"LIB_TYPES_ACTION_TYPES" => "Actions-types",
	"LIB_TYPES_DAMAGE_TYPES" => "Damage-types",
	"LIB_TYPES_WEAPON_TYPES" => "Weapon-types",
	"LIB_TYPES_POOLS" => "Pools",
	"LIB_TYPES_MODIFICATORS" => "Modificators",
	"LIB_TYPES_DEFENSIVE_SKILLS" => "Defensiv-skills (Armor/Shields)",
	"LIB_TYPES_MAGIC_CLASSES" => "Magic-classes",
	"LIB_TYPES_MAGIC_TYPES" => "Magic-types",
	"LIB_TYPES_SKILL_CLASSES" => "Other skill classes",
	"LIB_TYPES_SKILL_TYPES" => "Other skill types",
	"LIB_TYPES_EFFECTS" => "Effects",
	"LIB_TYPES_ALL_DAMAGE_TYPES" => "All damage types",
	"LIB_TYPES_SPECIAL_TOKEN" => "special token",
	"LIB_TYPES_EVENTS" => "Events",
	"ACTION_CLASS" => "Class action",
	"ACTION_WEAPON" => "Weapon action",
	"ACTION_MAGICAL" => "Magical action",
	"ACTION_ITEM" => "Potion/Item action",
	"ACTION_OTHER" => "Other actions",
	"ACTION_DICEROLL" => "Diceroll",
	"SHOW_PLAYER_MOVEMENT" => "Movement",
	"SHOW_PLAYER_POISON" => "Poison",
	"SHOW_PLAYER_SICKNESS" => "Sickness",
	"SHOW_PLAYER_SHIELD" => "Shield",
	"SHOW_PLAYER_EQUIPMENT" => "Equipment",
	"SHOW_PLAYER_TIER" => "Tier",
	"SHOW_PLAYER_PHYSICAL" => "physical",
	"SHOW_PLAYER_MAGICAL" => "magical",
	"SHOW_PLAYER_SAVE_STATE" => "save state",
	"SHOW_PLAYER_REMOVE_STATE" => "remove state",
	"SHOW_PLAYER_DEL_CHAR_MSG" => "Yes, the character should be removed from the game",
	"SHOW_PLAYER_MANUAL_CHNG" => "manual change",
	"SHOW_PLAYER_LIFE_P" => "life points",
	"SHOW_PLAYER_MANA_P" => "mana points",
	"SHOW_PLAYER_ACTION_P" => "action points",
	"NORMAL" => "Normal",
	"EXPERT" => "Expert",
	"MASTER" => "Master",
	"GRANDMASTER" => "Grandmaster",
	"SETDATA_SETSTATE" => array( 'gets the state','for','rounds' ),
	"SETDATA_DICEROLL_TARGET" => "%target%",
	"SETDATA_DAMAGE_ON" => "on",
	)

);
