<?php /** hijarian @ 20.01.16 11:28 */

require_once('./GameEngine.php');
require_once('./blob data.php');

function clear_all()
{
	global $ar;
	global $hp_change;
	$ar = array();
	$hp_change = 0;

}

run_tests(
	[
		'create' => function ()
		{
			$blob = create();

			return look($blob) > 0;
		},
		'beginning hp' => function ()
		{
			$blob = create();

			return (look($blob) > 15) && (look($blob) < 26);
		},
		'hit' => function ()
		{
			$blob = create();
			kick($blob);

			return look($blob) < 20;
		},
		'heal' => function ()
		{
			$blob = create();
			heal($blob);

			return look($blob) > 20;
		},
		'many blobs one heal' => function ()
		{
			$blob1 = create();
			$blob2 = create();
			heal($blob2);

			return look($blob1) < look($blob2);
		},
		'kick two' => function ()
		{
			$blob1 = create();
			$blob2 = create();
			kick($blob1);
			kick($blob2);

			return (look($blob1) < 20) && (look($blob2) < 20);
		},
		'true random' => function ()
		{
			$blob1 = create();
			$blob2 = create();
			kick($blob1);
			kick($blob2);

			return look($blob1) !== look($blob2);
		},
		'positive last damage' => function ()
		{
			$blob1    = create();
			$start_hp = look($blob1);
			kick($blob1);
			$result_hp = look($blob1);
			$hp_diff   = $start_hp - $result_hp;

			return $hp_diff == last_modify();
		},
		'last dmg on heal' => function ()
		{
			$blob1 = create();
			heal($blob1);

			return last_modify() == 0;
		},
		'last dmg on creation' => function ()
		{
			create();

			return last_modify() == 0;
		}
	], 'clear_all');