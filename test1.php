<?php
include "XSSGuard.php";

// Note that this header is required to disable xss filters in modern web browsers
header("X-XSS-Protection: 0");
if (isset($_GET['submit'])) {
    ?>

    <!doctype html>

    <html lang="en">
    <head>
        <meta charset="utf-8">

        <title>Test 1 Comment</title>
    </head>

    <body>
    <pre>
        <?php
        $xssguard = new XSSGuard();
        ?>
    </pre>
    <h1>Thanks for your comment <?php $xssguard->XSSEcho($_GET['name']) ?></h1>
    <p><?php $xssguard->XSSprint("<em>Your comment</em>: ${_GET['comment']}"); ?></p>
    </body>
    </html>


    <?php


} else {

    ?>
    <!doctype html>

    <html lang="en">
    <head>
        <meta charset="utf-8">

        <title>Test 1</title>
    </head>

    <body>
    <h1>Hello!</h1>
    <p>Share your views with us:</p>
    <form>
        Name: <input name="name" type="text"><br>
        Comment: <textarea name="comment"></textarea><br>
        <input name="submit" type="submit">
    </form>
    </body>
    </html>

<?php } ?>
