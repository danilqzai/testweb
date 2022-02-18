<?php

define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "admin");
define("DB_NAME", "sv_db_show");

function check_game($conn, $name){
	if (!is_string($name)){
		exit("incorrect game name");
	}
	
	$result = $conn->query("SHOW TABLES FROM ".DB_NAME." LIKE '".$name."'");
	
	if ($result->fetch_array() != null)
		return true;
	return false;
}

function add_game($conn, $table_name, $game_name){
	if (!is_string($table_name))
		exit("error table name");
	elseif (!is_string($game_name))
		exit("error game name");
	elseif (mb_substr_count($table_name, " ") != 0)
		exit("incorrect table name");
		
	if (check_game($conn, $table_name))
		exit("game ".$game_name." already exists");
	
	$data = "(
		id INT PRIMARY KEY AUTO_INCREMENT,
		ip INT UNSIGNED NOT NULL,
		port SMALLINT UNSIGNED NOT NULL
	)";
	
	$conn->query("create table ".$table_name." ".$data);
	$conn->query("insert _names(table_name, game_name) VALUES (\"".$table_name."\", \"".$game_name."\")");
}

function add_server($conn, $game_table, $ip, $port){
	if (!is_string($game_table))
		exit("error table name");
	elseif (mb_substr_count($game_table, " ") != 0)
		exit("incorrect table name");
	elseif (!is_string($ip))
		exit("error incorrect ip");
	elseif (!is_int($port))
		exit("error incorrect port");
	
	if (!check_game($conn, $game_table))
		exit("game does not exists");
	
	$conn->query("insert ".$game_table."(id, ip, port) VALUES (0, ".ip2long($ip).", ".$port.")");
}

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

add_game($conn, "counter_strike_2d", "Counter-Strike 2D");
add_server($conn, "counter_strike_2d", "45.9.193.220", 27500);
?>