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
        <link rel="stylesheet" type="text/css" href="/css/normalize.css">
        <link rel="stylesheet" type="text/css" href="/css/app.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="/js/app.js"></script>
        
    </head>

    <body>
        <div class="header">
            <h1 id="title"><?= $title ?></h1>
        </div>
        <div class="container"><?= $content ?></div>
        <div class="footer"></div>
    </body>
</html>
