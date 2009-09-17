<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2002-2008 Rainer Kuhn (kuhn@punkt.de)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * General debugging class and non-OO function shortcut 'trace()' (part of the library extension 'pt_tools')
 *
 * $Id$
 *
 * @author      Rainer Kuhn <kuhn@punkt.de>
 * @since       2005-08-18
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 */



/**
 * Inclusion of extension specific resources
 */
if (class_exists('t3lib_extMgm')) { // ignore in non TYPO3-mode
    require_once t3lib_extMgm::extPath('pt_tools').'res/objects/class.tx_pttools_exception.php'; // general exception class
}



/**
 * General debugging class for extension development
 *
 * @author      Rainer Kuhn <kuhn@punkt.de>
 * @since       2005-08-18
 * @package     TYPO3
 * @subpackage  tx_pttools
 */
class tx_pttools_debug {

    /***************************************************************************
     *   STATIC METHODS
     **************************************************************************/

    /**
     * Prints, returns or logs debugging/tracing information for a given element. The non-00 function trace() (see below this class) can be used as shortcut for this method (to use the method like a PHP command).
     *
     * This function analyzes an element's data type and outputs this together with its value/content.
     * Array and object values are displayed itemized as human-readable information.
     * Depending on the global variable $trace, which has to be set at the beginning of each script that uses trace(),
     * tracing output can be configured individually for each script/page: tracing can be switched off,
     * be printed/returned as a string, or be written to a log file (configurable in TYPO3's Constant Editor).
     *
     * IMPORTANT: Be careful with objects and multidimensional arrays (> 2D): this function is not able to
     * dissolve cyclic references. This means the output of an array or an object, that is contained in itself
     * (e.g. $GLOBALS) will never end!
     *
     * @param   mixed       element to analyse and output its value
     * @param   boolean     flag to return output as a string (e.g. to be placed inside a HTML page) instead of printing (optional, default: 0 = print)
     * @param   string      marker text to write at beginning and end of trace output (optional)
     * @return  mixed       HTML string with element type and element value if $return=0 (default), else void
     * @global  integer     $trace: variable set at each script that uses trace() to control function individually: 0=tracing off, 1=print tracing as HTML strings, 2=log tracing to file, 3=print tracing using PEAR's Var_Dump (if installed on server)
     * @throws  tx_pttools_exception   if global var $trace is set to 3 and PEAR's Var_Dump cannot be included
     * @see     trace()     non-00 function to be used as shortcut for this method
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2002-07 (major revision 2004-09)
     */
    public static function trace($element, $return=0, $marker=NULL) {

        global $trace;

        // stop tracing if no valid $trace value is set
        if (!in_array(intval($trace), Array(1, 2, 3))) {
            return;
        }


        // check trace type (set globally in every script)
        switch($trace) {

            // config for tracing output in HTML string ($trace=1)
            case 1:

                $diff['arrow'] = "&gt;";
                $diff['blank'] = "&nbsp;";
                $style         = "style=\"color:#993300; font-family:'Courier New'; font-size:12px\"";
                $traceString1  = "<table border=\"0\">\n<tr>\n<td valign=\"top\" nowrap=\"nowrap\">\n".
                                 "<b $style>trace ";
                break;

            // config for tracing in log file ($trace=2)
            case 2:

                // static var do not lose their values when program execution leaves this function scope
                static $headerAttached = FALSE;  // used to attach trace log header only once per script
                static $adminMailSent  = FALSE;  // used to prevent logging error mails multiple times per script

                $diff['arrow'] = ">";
                $diff['blank'] = " ";
                $traceString1  = "trace ";

                // compose log header for tracing of script on first call within current script/page
                if ($headerAttached == FALSE) {
                    $headerString =
                        "\n\n\n".
                        "*******************************************************************************\n".
                        "TRACE-LOG: ".date("Y-m-d, H:i:s")."\n".
                        "-------------------------------------------------------------------------------\n".
                        "REQUEST    : ".t3lib_div::getIndpEnv('TYPO3_REQUEST_URL')."\n".
                        "FILE/SCRIPT: ".t3lib_div::getIndpEnv('SCRIPT_NAME')."\n".
                        "HTTP HOST  : ".t3lib_div::getIndpEnv('HTTP_HOST')."\n".
                        "TYPO3 SITE : ".t3lib_div::getIndpEnv('TYPO3_SITE_URL')."\n".
                        "PHP VERSION: ".phpversion()."\n".
                        "IP ADDRESS : ".t3lib_div::getIndpEnv('REMOTE_ADDR')."\n".
                        "BROWSER    : ".t3lib_div::getIndpEnv('HTTP_USER_AGENT')."\n".
                        (t3lib_div::getIndpEnv('HTTP_REFERER')  ? ("REFERER    : ".t3lib_div::getIndpEnv('HTTP_REFERER')."\n") : "").
                        "-------------------------------------------------------------------------------\n".
                        "TRACE OUTPUTS OF ".t3lib_div::getIndpEnv('REQUEST_URI').":\n".
                        "-------------------------------------------------------------------------------\n";
               }

               break;

            // config for tracing using PEAR's Var_Dump ($trace=3)
            case 3:

                if (tx_pttools_div::includeOnceIfExists('Var_Dump.php') == false) {
                    // TODO: (Fabrizio) this returns the error "Allowed memory size of 100663296 bytes exhausted (tried to allocate 16 bytes)..."
                    throw new tx_pttools_exception('PEAR\'s Var_Dump cannot be included while $trace is set to 3!', 2);
                }

                Var_Dump::displayInit(array('display_mode' => 'HTML4_Table'));
                Var_Dump::display($element);

                break;

            // default = no tracing, e.g. $trace = 0
            default:

                return;

       } # end type switch


       // ***** START TRACING: *****
        $elemContent = "";

       // if array: itemize and save content
        if (is_array($element)) {
            $elemType = "[array] ";
            foreach ($element as $key => $val) {
                $elemContent .= "[".
                                (is_string($key) ? "'" : "").
                                $key.
                                (is_string($key) ? "'" : "").
                                "] =".$diff['arrow']." ".
                                (is_string($val) ? "'" : "").
                                (is_object($val) ? print_r($val, 1) : $val).
                                (is_string($val) ? "'" : "").
                                "\n";
                // check for multidimensional arrays
                if (is_array($val)) {
                    $elemType = "[array2]";
                    foreach ($val as $key2 => $val2) {
                        // intercept multidimensional arrays
                        if (is_array($val2)) {
                            $elemType = "[arrayM]";
                            $multidimArray = true;
                            unset($elemContent);
                        // itemize two-dimensional array and save content
                        } else {
                            $elemContent .= "             [".
                                            (is_string($key2) ? "'" : "").
                                            $key2.
                                            (is_string($key2) ? "'" : "").
                                            "] =".$diff['arrow']." ".
                                            (is_string($val2) ? "'" : "").
                                            (is_object($val2) ? print_r($val2, 1) : $val2).
                                            (is_string($val2) ? "'" : "").
                                            "\n";
                        }
                    }
                }
                // check for arrays with objects as values
                if (is_object($val)) {
                    $elemType = "[arrayObj]";
                    $objectArray = true;
                    unset($elemContent);
                }
            }

        // analyze all other data types and save element's value
        } else {
            $elemContent = $element;
            if (is_string($element)) {
                if (is_numeric($element)) {
                    $elemType = "[numstr]";
                } else {
                    $elemType = "[string]";
                }
            } elseif (is_int($element)) {
                $elemType = "[int] ".$diff['blank'].$diff['blank'];
            } elseif (is_float($element)) {
                $elemType = "[float] ";
            } elseif (is_bool($element)) {
                $elemType = "[bool] ".$diff['blank'];
                if (!$elemContent) {
                    $elemContent = 0;
                }
            } elseif (is_object($element)) {
                $elemType = "[object]";
            } elseif (is_resource($element)) {
                $elemType = "[resource: ".get_resource_type($element)."]";
            } elseif (is_null($element)) {
                $elemType = "[ NULL ]";
                $elemContent = "NULL";
            } else {
                $elemType = "[*** unknown type ***]";
            }
        }

        // compose element ouptput string (data type, value/content and marker text if called with 3rd param)
        $traceString1 .=
            $elemType.":".
            ($trace==1 ? "</b>\n</td>\n<td>\n<pre $style>\n" : " ").
            (isset($marker) ? ($trace==1 ? "<b $style>" : "\n")."------------ ".$marker." ------------".($trace==1 ? "</b>" : "")."\n" : "");
        $traceString2  =
            (isset($marker) ? ($trace==1 ? "<b $style>" : "")."------------ /".$marker." -----------".($trace==1 ? "</b>" : "")."\n" : "").
            ($trace==1 ? "</pre>\n</td>\n</tr>\n</table>\n" : "\n");

        // ...use print_r for multidimensional arrays and objects
        if (is_object($element) || isset($multidimArray) || isset($objectArray)) {
            $traceOutput = $traceString1.
                           print_r($element, 1).
                           $traceString2;

        // ...use trace output for all other datatypes
        } else {
            $traceOutput = $traceString1.
                           $elemContent.((isset($marker) & !is_array($element)) ? "\n" : "").
                           $traceString2;
        }

        // use output string to print or return (depending on global $trace value and function call/2nd param)
        if ($trace == 1) {

            if ($return == 1) {
                return $traceOutput;
            } else {
                echo $traceOutput;
                ob_flush(); // assure the trace output is sent to the browser (e.g. if output buffering is enabled and a page redirect follows the trace() call)
            }

        // use output string to write to logfile (depending on global $trace value)
        } elseif ($trace == 2) {

            // attach log header if not done yet
            if (isset($headerString)) {
                $traceOutput = $headerString . $traceOutput;
                $headerAttached = TRUE;
            }

            // get values from Constant Editor configuration
            $logDir = $GLOBALS['TSFE']->tmpl->setup['config.']['pt_tools.']['traceLogDir'];
            $adminMail = $GLOBALS['TSFE']->tmpl->setup['config.']['pt_tools.']['adminMail'];

            // try to write to log file if log directory is configured
            if (isset($logDir)) {
                // is log file writeable? (valid path/existing directory? access rights ok?)
                if (! @error_log($traceOutput, 3, $logDir."trace_log")) {

                    // ...NO: send error report if admin mail is configured
                    if (isset($adminMail) && $adminMailSent == FALSE) {
                        $mailSubject    = "Trace Logging Error (pt_tools) on ".t3lib_div::getIndpEnv('HTTP_HOST');
                        $mailHeaders    = "From: nobody@".t3lib_div::getIndpEnv('HTTP_HOST')."\r\n".
                                          "Content-Type: text/plain; charset=iso-8859-1\r\n".
                                          "Content-Transfer-Encoding: 8bit\r\n".
                                          "MIME-Version: 1.0";
                        $mailMessage    = "Trace Logging for extension 'pt_tools' on host ".t3lib_div::getIndpEnv('HTTP_HOST')." not possible in\n".
                                          $logDir."trace_log.\n\n".
                                          "Please check directory path (set in Constant Editor) and access rights.\n\n";
                        mail($adminMail, $mailSubject, $mailMessage, $mailHeaders);
                        $adminMailSent = TRUE;
                    }

                }   // ...YES: tracing has been written to log file

            }

        }

    } # end trace()

    /**
     * Outputs the stack backtrace for an error as a HTML string on the page
     *
     * @param   integer     error code (type) or appropriate E_* constant
     * @param   array       associative array of a PHP backtrace: the return value of PHP's debug_backtrace()
     * @return  void
     * @author  Fabrizio Branca <mail@fabrizio-branca.de>
     * @since   2007-05-03
     */
    public static function printBacktrace($severity, $arr) {

        $bgColor = "808070";
        if (($severity == E_COMPILE_ERROR) || ($severity == E_CORE_ERROR) || ($severity == E_USER_ERROR) || ($severity == E_ERROR)) {
            $bgColor="A08070";
        }

        echo "<table style='background-color:#".$bgColor.";color:#ffffff;font-size:8pt;'>\n";
        foreach ($arr as $linebefore){
            if (isset($linebefore['file'])) {
                echo "<tr>\n<td>file</td>\n<td>".$linebefore['file']."</td>\n</tr>\n";
            }
            echo "<tr>\n<td>function</td>\n<td>".$linebefore['function']."</td>\n</tr>\n";
            if (isset($linebefore['line'])) {
                echo "<tr>\n<td>line</td>\n<td>".$linebefore['line']."</td>\n</tr>\n";
            }
            echo "<tr>\n<td colspan='2'><hr /></td>\n</tr>\n";
        }
        echo "</table>\n";

    }

    /**
     * Returns an array of code snippet lines from the specified file.
     *
     * @param 	string 	Absolute path and file name of the PHP file
     * @param 	int     Line number defining the center of the code snippet
     * @param 	int   	(optional) amount of lines to display before current line, default = 4
     * @param 	int     (optional) amount of lines to display after current line, default = 2
     * @return 	array	array of lines, key == 0 is the current one
     * @author	Fabrizio Branca <mail@fabrizio-branca.de>
     * @since	2008-06-11
     * @see		F3_FLOW3_Error_DebugExceptionHandler->getCodeSnippet (by Robert Lemke <robert@typo3.org>)
     */
    public static function getCodeSnippet($filePathAndName, $lineNumber, $linesBefore = 4, $linesAfter = 2) {
        $lines = array();
        if (@file_exists($filePathAndName)) {
            $phpFile = @file($filePathAndName);
            if (is_array($phpFile)) {
                $startLine = max($lineNumber-$linesBefore, 1);
                $endLine = min($lineNumber+$linesAfter+1, count($phpFile) +1);
                if ($endLine > $startLine) {
                    for ($line = $startLine; $line < $endLine; $line++) {
                        $lines[$line-$lineNumber] = rtrim(str_replace("\t", '    ', $phpFile[$line-1]));
                    }
                }
            }
        }
        return $lines;
    }

    /**
     * Formats an exception as HTML.
     *
     * @param 	Exception	exception
     * @return 	string		HTML output
     * @author	Fabrizio Branca <mail@fabrizio-branca.de>
     * @since	2008-06-12
     */
    public static function exceptionToHTML(Exception $exception, $cssPath = '/typo3conf/ext/pt_tools/res/css/exception.css') {

        $output = '';

        $output .= '<!--'.chr(10).self::exceptionToTxt($exception).'-->';

        $output .= '<html>
            <head>
                <title>Uncaught '.get_class($exception).'</title>
                <link rel="stylesheet" type="text/css" href="'.$cssPath.'" />
            </head>
            <body>

                <h1 class="exceptionmessage">' . $exception->getMessage() . '</h1>

                <h2 class="exceptionclass">Uncaught "' .get_class($exception) . '"</h2>

                <div class="exceptioninfo">';

        $output .= '<span class="info locationfile"><span class="label">Thrown in file: </span><span class="value">' . $exception->getFile() . ' in line ' . $exception->getLine() . '</span></span>';

        if (method_exists($exception, 'getDebugMsg') && $exception->getDebugMsg() != '') {
            $output .= '<span class="info debugmessage"><span class="label">Debug Message: </span><span class="value">' . nl2br($exception->getDebugMsg()) . '</span></span>';
        }
        if ($exception->getCode() > 0) {
            $output .= '<span class="info code"><span class="label">Code: </span><span class="value">' . $exception->getCode() . '</span></span>';
        }
        if (method_exists($exception, 'getErrType')) {
            $output .= '<span class="info errtype"><span class="label">Error Type: </span><span class="value">' . $exception->getErrType() . '</span></span>';
        }

        $output .= '</div>';

        $output .= '<div class="backtrace">' . self::traceToHtml($exception->getTrace(), $exception->getFile(), $exception->getLine()) . '</div>';

        $output .= '</body></html>';

        return $output;
    }

    /**
     * Formats an exception as TXT.
     *
     * @param 	Exception	exception
     * @return 	string		TXT output
     * @author	Fabrizio Branca <mail@fabrizio-branca.de>
     * @since	2009-09-17
     */
    public static function exceptionToTxt(Exception $exception) {
        return get_class($exception).': '.$exception->getMessage().chr(10).chr(10).$exception->getTraceAsString().chr(10);
    }

    /**
     * Formats a trace array as HTML
     *
     * @param 	array	trace
     * @return 	string 	HTML output
     * @author	Fabrizio Branca <mail@fabrizio-branca.de>
     * @since	2008-06-12
     */
    public static function traceToHtml(array $trace, $callingFile = '', $callingLine = '') {
        $backtraceCode = '';

        if (count($trace)) {

            // loop over all steps
            foreach ($trace as $index => $step) {

                if ($index == 0) {
                    if (empty($step['file'])) $step['file'] = $callingFile;
                    if (empty($step['line'])) $step['line'] = $callingLine;
                }

                $backtraceCode .= '<div class="step">';



                // process file name
                if (isset($step['file'])) {
                    $stepFileName = preg_replace('/typo3conf\/ext\/([_a-z0-9]+)\//', 'typo3conf/ext/<span class="extension">$1</span>/', $step['file']);
                    $stepFileName = preg_replace('/class.([-_a-zA-Z0-9]+).php/', 'class.<span class="classname">$1</span>.php', $stepFileName);
                } else {
                    $stepFileName = '<unknown>';
                }

                // process arguments
                $arguments = array();
                if (isset($step['args']) && is_array($step['args'])) {

                    // loop over arguments
                    foreach ($step['args'] as $argument) {
                        if (is_object($argument)) {
                            $arguments[] = '<span class="argument isobject">' . get_class($argument) . '</span>';
                        } elseif (is_string($argument)) {
                            $tmp = (strlen($argument) < 40) ? $argument : substr($argument, 0, 20) . '...' . substr($argument, -20);
                            $tmp = htmlspecialchars($tmp);
                            $tmp = str_replace("\n", '[break]', $tmp);
                            $tmp = "'".$tmp."'";

                            $arguments[] = '<span class="argument isstring">' . $tmp . '</span>';
                        } elseif (is_numeric($argument)) {
                            $arguments[] = '<span class="argument isnumeric">' . (string)$argument . '</span>';
                        } else {
                            $arguments[] = '<span class="argument istype">' . gettype($argument) . '</span>';
                        }
                    }

                }

                // build step name
                $stepName = isset($step['class']) ? '<span class="identifier classname">' . $step['class'] . '</span>::' : '';
                $stepName .= '<span class="identifier functionname">' . $step['function'] . '</span>';
                $stepName .= '<span class="identifier arguments">' . '('.implode(', ', $arguments).')' . '</span>';

                $backtraceCode .= '<span class="stepname">' . '<span class="position">' . (count($trace) - $index) . ':</span> ' . $stepName . '</span>';
                $backtraceCode .= '<span class="filename">' . $stepFileName . '</span>';

                if (isset($step['file'])) {
                    $lines = self::getCodeSnippet($step['file'], $step['line'], 10, 4);
                    $keys = array_keys($lines);

                    if (t3lib_extMgm::isLoaded('geshilib')) {
                        require_once t3lib_extMgm::extPath('geshilib'). 'res/geshi.php';

                        $geshi = new GeSHi(implode(chr(10), $lines), 'php');
                        $geshi->set_tab_width(4);
                        $geshi->set_code_style('font-family: Consolas, \'Courier New\', Courier, monospace; font-weight: normal;');
                        $geshi->start_line_numbers_at($step['line']+$keys[0]);
                        $geshi->highlight_lines_extra(-1 * $keys[0] + 1);
                        $geshi->set_highlight_lines_extra_style('background-color: #eee; font-weight: bold;');
                        $geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);

                        $code = $geshi->parse_code();

                    } else {

                        $code = '<pre class="php">';
                        $code .= '<ol start="' . ($step['line']+$keys[0]) . '">';
                        foreach ($lines as $key => $line) {
                            $code .= '<li class="codeline'. (($key == 0) ? ' current' : '') .'">' . $line . '</li>';
                        }
                        $code .= '</ol>';
                        $code .= '</pre>';

                    }
                }
                $backtraceCode .= $code;

                $backtraceCode .= '</div>'; // class "step"
            }
        }

        return $backtraceCode;
    }

    /**
     * Cleans backtrace array (removes references to objects)
     *
     * @param   array	trace
     * @return  array	trace
     * @author  Fabrizio Branca <mail@fabrizio-branca.de>
     * @since	2008-08-27
     */
    public static function cleanBacktrace(array $trace) {

        foreach ($trace as $key => $value) {
            if (isset($trace[$key]['object'])) {
                unset($trace[$key]['object']);
            }
        }

        return $trace;

    }

    /**
     * Returns true if in developement context (for debugging outputs)
     *
     * @return 	bool	if visitors ip matches the devIPmask or a logged in admin backend user is present
     * @author	Fabrizio Branca <mail@fabrizio-branca.de>
     * @since	2008-06-15
     */
    public static function inDevContext() {

    	$inDevContext = false;

    	if (t3lib_div::cmpIP(t3lib_div::getIndpEnv('REMOTE_ADDR'), $GLOBALS['TYPO3_CONF_VARS']['SYS']['devIPmask'])) {
    		$inDevContext = true;
    	} else {

	    	require_once PATH_t3lib.'class.t3lib_userauth.php';
			require_once PATH_t3lib.'class.t3lib_userauthgroup.php';
			require_once PATH_t3lib.'class.t3lib_beuserauth.php';

	    	if (($GLOBALS['BE_USER'] instanceof t3lib_beUserAuth) &&  $GLOBALS['BE_USER']->isAdmin()) {
	    		$inDevContext = true;
	    	}
    	}

    	return $inDevContext;
    }

    /**
     * Error handler: Converts some php errors into exceptions
     *
     * @param   int     $errno
     * @param   string  $errstr
     * @param   string  $errfile
     * @param   int     $errline
     * @return  void    returns false (if no exception is thrown), so the normal error handler continues
     * @throws  tx_pttools_exception
     * @author  Fabrizio Branca <mail@fabrizio-branca.de>
     * @since   2008-06-22
     */
    public static function convertErrorToExceptionErrorHandler($errno, $errstr, $errfile, $errline) {
        $errorLevels = array (
            E_ERROR              => 'Error',
            E_WARNING            => 'Warning',
            E_PARSE              => 'Parsing Error',
            E_NOTICE             => 'Notice',
            E_CORE_ERROR         => 'Core Error',
            E_CORE_WARNING       => 'Core Warning',
            E_COMPILE_ERROR      => 'Compile Error',
            E_COMPILE_WARNING    => 'Compile Warning',
            E_USER_ERROR         => 'User Error',
            E_USER_WARNING       => 'User Warning',
            E_USER_NOTICE        => 'User Notice',
            E_STRICT             => 'Runtime Notice',
            E_RECOVERABLE_ERROR  => 'Catchable Fatal Error'
        );

        // if (error has not been supressed with an @) AND (error matches the definied error reporting level)
        if ((error_reporting() != 0) && ($errno & $GLOBALS['tx_pttools_debug']['errors'])) {
            if (ini_get ('log_errors')) {
                error_log(sprintf('PHP %s:  %s in %s on line %d', $errorLevels[$errno], $errstr, $errfile, $errline));
            }
            throw new tx_pttools_exception($errstr, 0, $errorLevels[$errno] . ': ' . $errstr . ' in ' . $errfile . ' line ' . $errline);
        }
        return false; // If the function returns FALSE then the normal error handler continues.
    }

    /**
     * Exception handler
     *
     * @param   Exception   exception
     * @return  string      HTML output or simple exception message
     * @author  Fabrizio Branca <mail@fabrizio-branca.de>
     * @since   2008-06-27
     */
    public static function catchUncaughtExceptionsExceptionHandler(Exception $exception) {
        if (method_exists($exception, 'handle')) {
            $exception->handle();
        }

        if (self::inDevContext()) {
            if (PHP_SAPI == 'cli') {
                echo tx_pttools_debug::exceptionToTxt($exception);
            } elseif (TYPO3_MODE == 'FE') {
                echo tx_pttools_debug::exceptionToHTML($exception);
            } else {
                echo tx_pttools_debug::exceptionToHTML($exception, $GLOBALS['BACK_PATH'].'../typo3conf/ext/pt_tools/res/css/exception.css');
            }
        } else {
            echo $exception->__toString();
        }
    }

} // end class



/*******************************************************************************
    NON-OO FUNCTION SHORTCUTS (not included in class tx_pttools_debug)
*******************************************************************************/

/**
 * Shortcut function for tx_pttools_debug::trace() to use the method with a shorthand notation like a PHP command.
 *
 * Prints, returns or logs debugging/tracing information for a given element. See tx_pttools_debug::trace for description and details.
 *
 * @param   mixed       see tx_pttools_debug::trace
 * @param   boolean     see tx_pttools_debug::trace
 * @param   string      see tx_pttools_debug::trace
 * @return  mixed       see tx_pttools_debug::trace
 * @see     tx_pttools_debug::trace
 * @access  public
 * @author  Rainer Kuhn <kuhn@punkt.de>
 * @since   2005-18-08
 */
function trace($element, $return=0, $marker=NULL) {
    return tx_pttools_debug::trace($element, $return, $marker);
}



/*******************************************************************************
 *   TYPO3 XCLASS INCLUSION (for class extension/overriding)
 ******************************************************************************/
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/staticlib/class.tx_pttools_debug.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/staticlib/class.tx_pttools_debug.php']);
}

?>