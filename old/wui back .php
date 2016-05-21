<?php
require_once('./GameEngine.php');
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
        <form action="wui back .php" method="post">
            <fieldset class="blob-panel">
                <p class="name">
                </p>
                <img src="" />
                <p class="HP">
                </p>
                <button name="heal" value="#">heal</button>
                <button name="kick" value="#">kick</button>
                <button name="look" value="#">look</button>
            </fieldset>
            <fieldset class="new-blob-button">
                <button name="create">create</button>
            </fieldset>
        </form>
    </body>
</html>