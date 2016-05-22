<?php header("X-XSS-Protection: 0"); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>XSS basics</title>
</head>

<body>
<br>Testing XSS stuff...
<form>
    <p>Enter a message: <input type="text" name="Data"></p>
    <input type="Submit" name="submit">
</form>

	<pre>
	<?php
    	if(isset($_GET['submit'])) {
            include "XSSGuard.php";
            $xssguard = new XSSGuard();
            $flags = "HTML";
    ?>
    		<p>Your message : <b><?php $xssguard->XSSecho($_GET["Data"]). "\n"; ?></b> </p>
            <p>Your message : <b><?php echo $_GET["Data"]. "\n"; ?></b></p>
            <p>Your message : <b><?php echo $xssguard->sanitize($_GET["Data"],"HTML",'',''). "\n"; ?></b></p>
    <?php
        }
    ?>
	</pre>

</body>
</html>