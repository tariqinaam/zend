<?php

/**
 * Returns true if the variable is boolean or null
 * 
 * @author Joe Green
 * @package Debug
 * @param mixed $var
 * @return boolean
 */
function is_primitive($var)
{
    return (
        null === $var || true === $var || false === $var
    ) ? true : false;
}

/**
 * Get the type of a boolean or null variable
 * 
 * @author Joe Green
 * @package Debug
 * @param boolean|null $var
 * @return string
 * @throws Exception Exception thrown if $var is not boolean or null
 */
function stringify_primitive($var)
{
    if (null === $var)
        return "NULL";
    elseif (false === $var)
        return "FALSE";
    elseif (true === $var)
        return "TRUE";
    else
        throw new Exception("stringify_primitive() expects param to be null or boolean");
}

/**
 * Get the type/class of any variable
 * 
 * @author Joe Green
 * @package Debug
 * @param mixed $var
 * @return string 
 */
function get_type($var)
{
    $types = array(
        'null', 'bool',
        'string', 'int',
        'array', 'resource', 'callable'
    );
    foreach ($types as $type)
    {
        $func = 'is_'.$type;
        if ($func($var)) return $type;
    }
    if (is_object($var))
        return get_class($var);
    return "Unknown Type";
}

/**
 * Dump a variable
 * 
 * @author Joe Green
 * @package Debug
 * @param mixed $var
 * @param boolean $return [optional] return output instead of printing it
 * @param boolean $extended [optional] uses var_dump output instead of print_r
 */
function vdump($var, $return=false, $extended=false)
{
    if (PHP_SAPI == "cli") {
        $vinfo = argument_name("vdump", false, true, true);
        $vname = argument_name("vdump", true, false, false);
        $out = PHP_EOL.$vinfo.PHP_EOL."vdump($vname): ";
        if (is_primitive($var))
            $out.= stringify_primitive($var);
        elseif ($extended) {
            ob_start();
            var_dump($var);
            $out.= ob_get_clean();
        }
        else
            $out.= print_r($var, true);
    }
    else {
        $vname = argument_name("vdump", true, true, true);
        if (is_primitive($var))
            $vdump = stringify_primitive($var);
        elseif ($extended) {
            ob_start();
            var_dump($var);
            $vdump = ob_get_clean();
        }
        else
            $vdump = print_r($var, true);
        $out = "<strong>$vname</strong><pre class=\"vdump\">".htmlentities($vdump)."</pre>";
    }
    echo $return ? '' : $out;
    return $return ? $out : true;
}

/**
 * Dump a variable and exit
 * 
 * @author Joe Green
 * @package Debug
 * @param mixed $var
 * @param boolean $extended [optional] uses var_dump output instead of print_r
 */
function vquit($var, $extended=false)
{
    if (PHP_SAPI == "cli") {
        $vinfo = argument_name("vquit", false, true, true);
        $vname = argument_name("vquit", true, false, false);
        $out = PHP_EOL.$vinfo.PHP_EOL."vquit($vname): ";
        if (is_primitive($var))
            $out.= stringify_primitive($var);
        elseif ($extended) {
            ob_start();
            var_dump($var);
            $out.= ob_get_clean();
        }
        else
            $out.= print_r($var, true);
    }
    else {
        $vname = argument_name("vquit", true, true, true);
        if (is_primitive($var))
            $vdump = stringify_primitive($var);
        elseif ($extended) {
            ob_start();
            var_dump($var);
            $vdump = ob_get_clean();
        }
        else
            $vdump = print_r($var, true);
        $out = "<strong>$vname</strong><pre class=\"vdump\">".htmlentities($vdump)."</pre>";
    }
    echo $out;
    exit;
}

/**
 * Get info about a single-argument function call.
 * 
 * Gets varname, filename and line number of the last called function with the given name.
 * Based on dBug.php
 * 
 * @author Joe Green
 * @package Debug
 * @param string function
 * @param boolean $varname Get name of variable(s) passed to function
 * @param boolean $filename Get filename of file where the function was called
 * @param boolean $linenum Get line number of line where the function was called
 * @return string
 */
function argument_name($function, $varname=true, $filename=true, $linenum=true) 
{
    $arrBacktrace = debug_backtrace();
    $arrInclude = array("include","include_once","require","require_once");
    for($i=count($arrBacktrace)-1; $i>=0; $i--) 
    {
        $arrCurrent = $arrBacktrace[$i];
        if(array_key_exists("function", $arrCurrent) && 
            (in_array($arrCurrent["function"], $arrInclude) || (0 != strcasecmp($arrCurrent["function"], $function))))
            continue;
        $arrFile = $arrCurrent;
        break;
    }
    if(isset($arrFile)) {
        $arrLines = file($arrFile["file"]);
        $code = $arrLines[($arrFile["line"]-1)];
        preg_match('/\b'.$function.'\s*\(\s*(.+)\s*\);/i', $code, $arrMatches);
        $return = array();
        !$filename || $return[] = $arrFile['file'];
        !$linenum || $return[] = 'Line ' . $arrFile['line'];
        !$varname || $return[] = $arrMatches[1];
        return implode(' - ', $return);
    }
    return "";
}

/**
 * Send a variable or message to the javascript console.
 * 
 * @author Joe Green
 * @pacakge Debug
 * @param mixed $var send a variable for debugging (json encoded) or a string to pass a message
 * @param int $type CONSOLE_LOG | CONSOLE_INFO | CONSOLE_WARN | CONSOLE_DEBUG | CONSOLE_ERROR
 */
function console($var, $type=1)
{
    $layout = Zend_Layout::getMvcInstance()->getView();
    static $i = 1;
    static $noConsole = false;
    if (!$noConsole) {
        $layout->headScript()->appendScript(
"if (!window.console) window.console = {};
console.log = console.log || function(){};
console.warn = console.warn || function(){};
console.error = console.error || function(){};
console.info = console.info || function(){};
console.debug = console.debug || function(){};
"
        );
    }
    $noConsole = true;
    if (is_bool($var))
        $var = $var ? "TRUE" : "FALSE";
    $out = "";
    if (is_object($var) || is_array($var)) {
        $object = json_encode($var);
        $filename = str_replace(
            array('"', APPLICATION_PATH, ', CONSOLE_LOG', ', CONSOLE_DEBUG', ', CONSOLE_INFO', ', CONSOLE_WARN', ', CONSOLE_ERROR'),
            array('\"', '', '', '', '', '', ''),
            argument_name('console', false, true, true)
        );
        $varname = str_replace(
            array('"', APPLICATION_PATH, ', CONSOLE_LOG', ', CONSOLE_DEBUG', ', CONSOLE_INFO', ', CONSOLE_WARN', ', CONSOLE_ERROR'),
            array('\"', '', '', '', '', '', ''),
            argument_name('console', true, false, false)
        );
        $out.='var spawnConsole_'.$i.' = \''.str_replace("'","\'",$object).'\';'."\r\n";
        $out.='var val_'.$i.' = eval("(" + spawnConsole_'.$i.' + ")" );'."\r\n";
        switch($type) {
            case CONSOLE_LOG:
                $out.='console.log(val_'.$i.', "'.$filename.' - '.$varname.'");'."\r\n";
            break;
            case CONSOLE_DEBUG:
                $out.='console.debug(val_'.$i.', "'.$filename.' - '.$varname.'");'."\r\n";
            break;
            case CONSOLE_INFO:
                $out.='console.info(val_'.$i.', "'.$filename.' - '.$varname.'");'."\r\n";
            break;
            case CONSOLE_WARN:
                $out.='console.warn(val_'.$i.', "'.$filename.' - '.$varname.'");'."\r\n";
            break;
            case CONSOLE_ERROR:
                $out.='console.error(val_'.$i.', "'.$filename.' - '.$varname.'");'."\r\n";
            break;
        }
    } else {
        switch($type) {
            case CONSOLE_LOG:
                $out.='console.log("'.str_replace('"','\\"',$var).'");'."\r\n";
            break;
            case CONSOLE_DEBUG:
                $varname = str_replace(
                    array('"', APPLICATION_PATH, ', CONSOLE_LOG', ', CONSOLE_DEBUG', ', CONSOLE_INFO', ', CONSOLE_WARN', ', CONSOLE_ERROR'),
                    array('\"', '', '', '', '', '', ''),
                    argument_name('console', true, false, false)
                );
                $out.='console.debug("['.$varname.'] '.str_replace('"','\\"',$var).'");'."\r\n";
            break;
            case CONSOLE_INFO:
                $out.='console.info("'.str_replace('"','\\"',$var).'");'."\r\n";
            break;
            case CONSOLE_WARN:
                $out.='console.warn("'.str_replace('"','\\"',$var).'");'."\r\n";
            break;
            case CONSOLE_ERROR:
                $out.= 'console.error("'.str_replace('"','\\"',$var).'");'."\r\n";
            break;
        }
    }
    $i++;
    $layout->headScript()->appendScript($out);
}
defined('CONSOLE_LOG') || define('CONSOLE_LOG', 1);
defined('CONSOLE_INFO') || define('CONSOLE_INFO', 2);
defined('CONSOLE_WARN') || define('CONSOLE_WARN', 3);
defined('CONSOLE_DEBUG') || define('CONSOLE_DEBUG', 4);
defined('CONSOLE_ERROR') || define('CONSOLE_ERROR', 5);

function dev_console($var, $type=1)
{
    if (APPLICATION_ENV == 'development' || APPLICATION_ENV == 'testing')
        console($var, $type);
}

/**
 * Measure time between two events 
 * 
 * Each timer has its own key.
 * $key = debug_timer() to start timing.
 * debug_timer($key) to get time elapsed.
 * @author Joe green
 * @version 1.0
 */
function debug_timer($key=null)
{
    static $timers = array();
    if (is_null($key)) {
        $startTime = microtime(true);
        $key = strval($startTime);
        $timers[$key] = $startTime; 
        return $key;
    } 
    else {
        $endTime = microtime(true);
        $startTime = $timers[$key]; 
        return $endTime - $startTime;
    }
}

/**
 * Display php environment info 
 * 
 * @author Joe Green
 * @version 0.1
 */
function environment()
{
    exec('whoami', $whoami);
    vdump($whoami);
    phpinfo();
}