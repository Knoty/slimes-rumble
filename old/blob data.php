<?php /** hijarian @ 22.01.16 12:47 */

$ar = array();

function create_blob($blob_hp)
{
	global $ar;
	$new_count = array_push($ar, $blob_hp);
	return $new_count - 1;
}

function look_blob($blob_number)
{
	global $ar;
	return $ar[$blob_number];
}

function modify_blob($blob_number, $hp_change)
{
	global $ar;
	$ar[$blob_number] = $ar[$blob_number] + $hp_change;
}