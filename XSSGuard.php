<?php

/**
 * Created by PhpStorm.
 * User: varun
 * Date: 4/18/16
 * Time: 10:51 AM
 */
class XSSGuard
{
    /**
     * XSSGuard constructor.
     */
    function XSSGuard()
    {
        echo "<pre>XSSGuard Enabled</pre>";
        $magic_quotes = (bool) ini_get('magic_quotes_gpc');
        if ($magic_quotes == TRUE) {
            define("MAGIC_QUOTES", 1);
        } else {
            define("MAGIC_QUOTES", 0);
        }
    }

    /**
     * Get all the Super Globals in PHP
     */
    private function printSuperGlobals()
    {
        echo "<hr>";
        echo '$_SERVER' . "\n";
        $this->XSSprint_r($_SERVER);

        if (!empty($_POST)) {
            echo "<hr>";
            echo '$_POST' . "\n";
            $this->XSSprint_r($_POST);
        }
        if(!empty($_GET)) {
            echo "<hr>";
            echo '$_GET' . "\n";
            foreach($_GET as $name=>$value)
            {
                $this->XSSecho($name. " " . $value . "\n");
            }
        }
        if (!empty($_FILES)) {
            echo "<hr>";
            echo '$_FILES' . "\n";
            $this->XSSprint_r($_FILES);
        }
        if (!empty($_ENV)) {
            echo "<hr>";
            echo '$_ENV' . "\n";
            $this->XSSprint_r($_ENV);
        }
        if (!empty($_COOKIE)) {
            echo "<hr>";
            echo '$_COOKIE' . "\n";
            $this->XSSprint_r($_COOKIE);
        }
        if (!empty($_SESSION)) {
            echo "<hr>";
            echo '$_SESSION' . "\n";
            $this->XSSprint_r($_SESSION);
        }
    }

    /**
     * Similar to PHP's echo call, but uses
     * htmlentities to avoid XSS
     * @param $var
     */
    function XSSecho($var) {
        echo htmlentities($var);
    }


    /**
     * Similar to PHP's print call, but uses
     * htmlentities to avoid XSS
     * @param $var
     */
    function XSSprint($var) {
        print htmlentities($var);
    }

    /** Similar to PHP's print_r call, but uses
     *  htmlentities to avoid XSS
     * @param $var
     */
    function XSSprint_r($var) {
        echo "Array\n";
        echo "(\n";
        foreach ($var as $key => $value)
            $this->XSSecho("    [" . $key. "] => " . $value . "\n");
        echo ")\n";
    }

    /** This function takes an unsanitized string and
     *  returns the sanitized string, based on flags set.
     * @param $input
     * @param $flag string containing the flag
     * @param $min
     * @param $max
     * @return bool|float|int|mixed|string
     */
    function sanitize($input, $flag, $min, $max) {

        if (strcmp($flag, 'PARANOID') == 0) $input = $this->sanitize_paranoid_string($input, $min, $max);
        if (strcmp($flag, 'INT') == 0) $input = $this->sanitize_int($input, $min, $max);
        if (strcmp($flag, 'FLOAT') == 0) $input = $this->sanitize_float($input, $min, $max);
        if (strcmp($flag, 'HTML') == 0) $input = $this->sanitize_html_string($input);
        if (strcmp($flag, 'LDAP') == 0) $input = $this->sanitize_ldap_string($input, $min, $max);
        if (strcmp($flag, 'SHELL') == 0) $input = $this->sanitize_shell_string($input, $min, $max);
        return $input;
    }


    /** input float, returns ONLY the float (no extraneous characters)
     * @param $float
     * @param string $min
     * @param string $max
     * @return bool|float
     */
    function sanitize_float($float, $min='', $max='')
    {
        $float = floatval($float);
        if((($min != '') && ($float < $min)) || (($max != '') && ($float > $max)))
            return FALSE;
        return $float;
    }


    /** input integer, returns ONLY the integer (no extraneous characters)
     * @param $integer
     * @param string $min
     * @param string $max
     * @return bool|int
     */
    function sanitize_int($integer, $min='', $max='')
    {
        $int = intval($integer);
        if((($min != '') && ($int < $min)) || (($max != '') && ($int > $max)))
            return FALSE;
        return $int;
    }

    /** Returns string stripped of all non alphanumeric characters
     * @param $string
     * @param string $min
     * @param string $max
     * @return bool|mixed
     */
    function sanitize_paranoid_string($string, $min='', $max='')
    {
        $string = preg_replace("/[^a-zA-Z0-9]/", "", $string);
        $len = strlen($string);
        if((($min != '') && ($len < $min)) || (($max != '') && ($len > $max)))
            return FALSE;
        return $string;
    }

    /** Sanitizes the strings that are provided to system() call
     *  and avoid shell injection.
     * @param $string
     * @param string $min
     * @param string $max
     * @return bool|string
     */
    function sanitize_shell_string($string, $min='', $max='')
    {
        $pattern = '/(;|\||`|>|<|&|^|"|'."\n|\r|'".'|{|}|[|]|\)|\()/i'; // no piping, passing possible environment variables ($),
        // seperate commands, nested execution, file redirection,
        // background processing, special commands (backspace, etc.), quotes
        // newlines, or some other special characters
        $string = preg_replace($pattern, '', $string);
        $string = '"'.preg_replace('/\$/', '\\\$', $string).'"'; //make sure this is only interpreted as ONE argument
        $len = strlen($string);
        if((($min != '') && ($len < $min)) || (($max != '') && ($len > $max)))
            return FALSE;
        return $string;
    }


    /** If MagicQuotes are on, returns the same string or
     *  else removes the slashes in the string
     * @param $string
     * @return string
     */
    function nice_addslashes($string)
    {
        // if magic quotes is on the string is already quoted, just return it
        if(MAGIC_QUOTES)
            return $string;
        else
            return addslashes($string);
    }

    /** This function sanitizes strings that will inserted in to the
     *  query or the entire string as query
     * @param $link MySQL Connection object
     * @param $string Query string or variable
     * @return string
     */
    function sanitize_sql_string($link, $string)
    {
        return mysqli_escape_string($link, $string);
    }


    /** Sanitizes LDAP strings that maybe injected with a string
     * @param $string
     * @param string $min
     * @param string $max
     * @return bool|string
     */
    function sanitize_ldap_string($string, $min='', $max='')
    {
        $pattern = '/(\)|\(|\||&)/';
        $len = strlen($string);
        if((($min != '') && ($len < $min)) || (($max != '') && ($len > $max)))
            return FALSE;
        return preg_replace($pattern, '', $string);
    }

    /** Sanitizes string that needs to be embedded into HTML page
     * @param $string
     * @return string
     */
    function sanitize_html_string($string)
    {
        return htmlentities($string);
    }
}