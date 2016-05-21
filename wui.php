<?php

require_once('./GameEngine.php');
require_once('./BlobDB.php');

    $engine = new GameEngine();
    $engine->restore();
    if (array_key_exists("create", $_POST))
    {
        $engine->create();
        $engine->save();
    }
    elseif (array_key_exists("kick", $_POST))
    {
        $engine->kick($_POST["kick"]);
        $engine->save();
    }
    elseif (array_key_exists("heal", $_POST))
    {
        $engine->heal($_POST["heal"]);
        $engine->save();
    }

?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>
            blobls battle
        </title>
        <link rel="stylesheet" href="wui.css" target="screen" />
    </head>
    <body>
        <pre>$_GET=<?php var_dump($_GET); ?></pre>
        <pre>$_POST=<?php var_dump($_POST); ?></pre>
        <form action="wui.php" method="post">
            <?php foreach($engine->getWorldState() as $i => $blob_hp):?>
            <fieldset class="blob-panel">
                <button name="heal" value="<?php echo $i; ?>">
                    <span class="name">
                        <?php echo "name ".$i; ?>
                    </span>
                    <span class="hp">
                        <?php echo $blob_hp;?>
                    </span>
                </button>
            </fieldset>
            <?php endforeach;?>
            <fieldset class="new-blob-button">
                <button name="create">create</button>
            </fieldset>
        </form>
    </body>
</html>