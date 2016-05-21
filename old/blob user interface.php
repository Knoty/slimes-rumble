<?php /** hijarian @ 20.01.16 13:10 */

require_once('./GameEngine.php');

$blob_quantity = 0;
while(true)
{
	echo "\n blobs quantity: $blob_quantity \n"
		 . "\n"
		 . "c: create blob \n"
		 . "k#: kick blob number # \n"
		 . "h#: heal blob number # \n"
		 . "l#: look at blob number # \n"
		 . "q: exit from the program \n \n";
	$command  = fgets(STDIN, 4);
	if ($command[0] == 'c')
	{
		$blob_number = create();
		$blob_quantity++;
		echo "\n blob #$blob_number with "
			. look($blob_number)
			. " hits was created \n";
	}
	elseif ($command[0] == 'k')
	{
		$blob_number = intval(substr($command, 1));
		kick($blob_number);
		$hp_status = last_modify();
		echo "\n blob #$blob_number was kicked for $hp_status HP. Now blob #$blob_number have "
			 . look($blob_number)
			 . " hits \n";
	}
	elseif ($command[0] == 'h')
	{
		$blob_number = intval(substr($command, 1));
		heal($blob_number);
		$hp_status = last_modify();
		echo "\n blob #$blob_number was healed for $hp_status HP. Now blob #$blob_number have "
			 . look($blob_number)
			 . " hits \n";
	}
	elseif ($command[0] == 'l')
	{
		$blob_number = intval(substr($command, 1));
		echo "\n Blob #$blob_number have "
			 . look($blob_number)
			 . " hits \n";
	}
	elseif($command[0] == 'q')
		break;
}