<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>index view</title>
    </head>
    <body>
        <h1>Welcome to Core!</h1>
        <p>
            This is Core Frameworks - version 0.10<br/>
            Core -or Core Frameworks- is an ultra fast PHP framework with a minimal footprint.<br/>
            It is designed to be ultra fast, easy to learn and is easily extensible by third party packages.<br/>
            For more information see:<br/>
            https://github.com/estabij/core<br/>
            http://www.erikstabij.com/core<br/>
        </p>
        <p>
        <?php
            Note: 
            if (isset($note)) {
                echo $note;
            }
        ?>
        </p>
    </body>
</html>
