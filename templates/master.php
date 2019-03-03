<?php 
/**
 * This is a master template, used for most of the app views
 */
?><!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= $title ?></title>
        <link href="https://fonts.google.com/css?family=Open+Sans?selection.family=Open+Sans:400,700" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="/css/normalize.css">
        <link rel="stylesheet" type="text/css" href="/css/app.css">
    </head>

    <body>
        <div class="header">
            <h1><?= $title ?></h1>
        </div>
        <div class="content"><?= $content ?></div>
        <div class="footer"><?= $footer ?></div>
    </body>
</html>