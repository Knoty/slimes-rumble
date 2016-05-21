<?php /** hijarian @ 22.01.16 12:48 */

require_once('./blob data.php');
require_once ('./tests_framework.php');

run_tests([
	'return same hp which where saved' => function()
	{
		$blob_number = create_blob(13);
		return look_blob($blob_number) == 13;
	},
	'check negative hp change' => function()
	{
		$blob_number = create_blob(20);
		modify_blob($blob_number, -5);
		return look_blob($blob_number) == 15;
	},
	'check positive hp change' => function()
	{
		$blob_number = create_blob(20);
		modify_blob($blob_number, 3);
		return look_blob($blob_number) == 23;
	},
	'check null hp change' => function()
	{
		$blob_number = create_blob(20);
		modify_blob($blob_number, 0);
		return look_blob($blob_number) == 20;
	}
          ]);