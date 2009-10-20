<?php
/***************************************************************
*  Copyright notice
*  
*  (c) 2007 Rainer Kuhn (kuhn@punkt.de)
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
 * CLI script handler (part of the library extension 'pt_tools')
 *
 * $Id$
 *
 * @author  Rainer Kuhn <kuhn@punkt.de>
 * @since   2007-08-23
 */ 
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 */



/**
 * CLI (command line interface) script handler
 *
 * @author      Rainer Kuhn <kuhn@punkt.de>
 * @since       2007-08-23
 * @package     TYPO3
 * @subpackage  tx_pttools
 */
class tx_pttools_cliHandler  {
        
    /**
     * Properties
     */
    protected $cliScriptName = ''; // (string) see param description of __construct()
    protected $adminMailRecipient = ''; // (string) see param description of __construct()
    protected $cliHostName = ''; // (string) see param description of __construct()
    protected $cliQuietMode = true; // (boolean) see param description of __construct()
    protected $cliEnableLogging = true; // (boolean) see param description of __construct()
    protected $cliLogDir = ''; // (string) see param description of __construct()
    
    protected $mailHeaders = ''; // (string) mail headers for system messages sent from the CLI script
    
    
    
    /***************************************************************************
     *   CONSTRUCTOR
     **************************************************************************/
     
    /**
     * Class constructor: Sets the objects properties
     * 
     * @param   string      the calling CLI script's name (e.g. $this->scriptName of calling CLI script)
     * @param   string      CLI admin email address: Email address for system messages sent from the CLI script. [Should be set in config of calling CLI script]
     * @param   string      CLI host name: Name of the host where the CLI script is used. This name is used for identification of the originating host in emails sent from the CLI script. [Should be set in config of calling CLI script]
     * @param   boolean     (optional) CLI quiet mode: Flag for executing the CLI script in quiet mode ('false' displays debugging messages while executing the CLI script). [Should be set in config of calling CLI script]
     * @param   boolean     (optional) CLI enable logging: Flag whether the CLI script logging should be used. If enabled, $cliLogDir has to be set! [Should be set in config of calling CLI script]
     * @param   string      (optional) CLI log output dir (absolute path): _Absolute_ path to the directory for generated logs of the CLI script (Important: don't forget the prefacing and closing slashes "/"). If not set, no log is written. [Should be set in config of calling CLI script]
     * @return  void
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2007-08-23
     */
    public function __construct($cliScriptName, $adminMailRecipient, $cliHostName, $cliQuietMode=true, $cliEnableLogging=true, $cliLogDir='') {
            
            $this->cliScriptName = $cliScriptName;
            $this->adminMailRecipient = $adminMailRecipient;
            $this->cliHostName = $cliHostName;
            $this->cliEnableLogging = $cliEnableLogging;
            $this->cliQuietMode = $cliQuietMode;
            $this->cliLogDir = $cliLogDir;
            
            $this->mailHeaders    = "From: ".$cliScriptName."@".$cliHostName."\r\n".
                                    "Content-Type: text/plain; charset=iso-8859-1\r\n".
                                    "Content-Transfer-Encoding: 8bit\r\n".
                                    "MIME-Version: 1.0";
    }
    
    
    
    /***************************************************************************
     *   GENERAL METHODS
     **************************************************************************/
    
    /** 
     * Parses arguments and returns options on success/dies with help information on error
     * 
     * IMPORTANT: This method requires the PEAR module 'Console_Getopt' (see http://pear.php.net/manual/en/package.console.console-getopt.php) 
     * to be installed on your server and to be included in the calling CLI script, e.g. by "require_once 'Console/Getopt.php';"
     *
     * @param   Console_Getopt      object of type Console_Getopt (see http://pear.php.net/manual/en/package.console.console-getopt.php)
     * @param   string      short options for Console_Getopt (see http://pear.php.net/manual/en/package.console.console-getopt.intro-options.php)
     * @param   string      help string of the calling CLI script with information about available options
     * @param   array       (optional) long options for Console_Getopt (see http://pear.php.net/manual/en/package.console.console-getopt.intro-options.php)
     * @param   boolean     (optional) flag whether the script should stop with the help info if no options are passed
     * @return  array       list of parsed options on sucess (script dies on error)        
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2007-08-23
     */
    public function getOptions(Console_Getopt $console, $shortOptions, $helpString, $longOptionsArr=array(), $stopOnNoOptions=true) {
            
            // get arguments
            $argumentsArr = $console->readPHPArgv();
            array_shift($argumentsArr);
            $optionsArr = $console->getopt($argumentsArr, $shortOptions, $longOptionsArr);
            
            // die on argument error
            if ($optionsArr instanceof PEAR_Error) {
                $this->cliMessage("[ERROR] ".$optionsArr->message."\n", false, 1);
                die($helpString);
            } 
            // prepare options on success
            $this->cliMessage(print_r($optionsArr, 1)."\n", false, 0);
            $parsedOptionsArr = $optionsArr[0];
            $nonOptionArgsArr = $optionsArr[1];
            
            // die with help display if no options given
            if ($stopOnNoOptions == true && empty($parsedOptionsArr)) {
                die($helpString);
            }
            
            return $parsedOptionsArr;
    
    }
    
    /** 
     * Handles script messages and protocols actions of a CLI script
     *
     * Displays messages on screen and writes them to a log file. 
     * Automatically appends date/time and additional data to every log msg. 
     * If there's a problem with logging an admin email is sent.
     * Error messages will be written to STDERR and they'll terminate the script.
     *
     * @param   string      message to handle
     * @param   boolean     (optional) type of message: false=notice (default), true=error (error results in sending an error mail and terminating the script!)
     * @param   integer     (optional) message display status value (0= standard, 1=always/also in quiet mode, 2=never)
     * @param   boolean     (optional) flag whether the message is the initial script call message (default:false)
     * @param   boolean     (optional) flag whether a warning mail should be send for the given message even if it is not stated as an error at the 2nd param $isError (default:false)
     * @return  void        
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2007-08-23, based on handleMsg() code from 2004-11-29/2007-03-08
     */
    public function cliMessage($msg, $isError=false, $displayStatus=0, $initialMsg=false, $sendWarningMailforNonErrors=false) {
        
        static $loggingErrorMailSent = false;
        $logMsg = '';
        ob_clean(); // clean (erase) the output buffer to display only the echo's below (necessary if output buffering is enabled before)
               
        // prepare messages for display and logging
        $msg = $msg."\n";
        if ($isError == true) {
            $msg = "[ERROR] ".$msg."\nScript terminated.\n";
        } elseif ($sendWarningMailforNonErrors == true) {
            $msg = "[WARNING] ".$msg."\n";
        }
        if ($initialMsg == true) {
            $logMsg .= "\n".
                       "===========================================================================\n".
                       "    NEW SCRIPT CALL [".date("D Y-m-d H:i:s")."]\n".
                       "===========================================================================\n";
        }
        $logMsg .= "[".date("D Y-m-d H:i:s")."] ".$msg;
        
        if ($this->cliEnableLogging == true) {
            // try logging (dir exists? access rights ok?)
            if (@error_log($logMsg, 3, $this->cliLogDir.$this->cliScriptName.'_log')) {
                $loggingErrorMailSent = false; // unset loggingErrorMailSent-Flag
                
            // logging not possible: try to sent error mails to admin email address
            } elseif ($this->adminMailRecipient) {
                      
                // if logging not possible AND logging error mail not sent before: send logging error mail to admin          
                if ($loggingErrorMailSent == false) {
                    echo "LOGGING ERROR! Sending error mail to admin ".$this->adminMailRecipient."...\n";
                    ob_flush(); // sends the output buffer to display the echo message (necessary if output buffering is enabled before)
                    $mailSubject    = "Logging Error (".$this->cliScriptName.") on ".$this->cliHostName;
                    $mailMessage    = "Logging for ".$this->cliScriptName." on ".$this->cliHostName." not possible in\n".
                                      $this->cliLogDir.$this->cliScriptName."_log.\n\n".
                                      "Please check directory path and access rights.\n\n";
                    mail($this->adminMailRecipient, $mailSubject, $mailMessage, $this->mailHeaders);
                    $loggingErrorMailSent = true; // set loggingErrorMailSent-Flag
                }
            }
        }
    
        // if an error has occured: mail error to admin and terminate script
        if ($isError == true || $sendWarningMailforNonErrors == true) {
            $mailSubject    = "CLI SCRIPT ".($isError == true ? "ERROR" : "WARNING").": ".$this->cliScriptName." on ".$this->cliHostName;
            $mailMessage    = ($isError == true ? "ERROR" : "WARNING")." while executing ".$this->cliScriptName." on ".$this->cliHostName.":\n\n".$logMsg."\n\n";
            mail($this->adminMailRecipient, $mailSubject, $mailMessage, $this->mailHeaders);
            if ($isError == true) {
                echo $msg;
                ob_flush();  // sends the output buffer to display the echo message (necessary if output buffering is enabled before)
                fwrite(STDERR, $msg."\n");  // write error message to shell's STDERR (enables e.g. mailing of cronjob errors)
                die();
            }
        } 
        
        // no error: display of debug message if activated in config
        if (($this->cliQuietMode == false && $displayStatus != 2) || $displayStatus == 1) {
            echo $msg;
            ob_flush();  // sends the output buffer to display the echo message (necessary if output buffering is enabled before)
        }
    
    }
    
    /** 
     * Returns the content of a file identified by a file path relative to the calling CLI script
     *
     * @param   string      Absolute path to the calling CLI script (just pass TYPO3's constant 'PATH_thisScript')
     * @param   string      Relative path to the file to read, originating from the calling CLI scripts directory (e.g. for a file in fileadmin: "../../../../fileadmin/myFile.txt" if the CLI script is located at "typo3conf/ext/my_extension/cronmod/")
     * @return  string      content of the passed file
     * @throws  tx_pttools_exception    if file not found or not readable [catched exception could be passed to cliMessage() by calling CLI script: $cliHandler->cliMessage($excObj->__toString(), true, 1)]
     * @throws  tx_pttools_exception    if no file content found          [catched exception could be passed to cliMessage() by calling CLI script: $cliHandler->cliMessage($excObj->__toString(), true, 1)]
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2008-09-23 (based on code from 2006-12-21)
     */
    public function readFileRelToCliScript($callingCliScriptAbsPath, $fileRelPath) {
        
        $fileContent = '';
        $fileName = substr($callingCliScriptAbsPath, 0, (strrpos($callingCliScriptAbsPath, "/")+1)).$fileRelPath;
        
        if (!@is_file($fileName) || !@is_readable($fileName)) {
            throw new tx_pttools_exception('File not found or not readable in: '.$fileName);
        } else {
            $fileContent = @file_get_contents($fileName);
        }
        
        if (!isset($fileContent) || trim($fileContent) == '') {
            throw new tx_pttools_exception('No file content found: '.$fileName);
        }
        
        return $fileContent;
    
    }
    
    
    
} // end class



/*******************************************************************************
 *   TYPO3 XCLASS INCLUSION (for class extension/overriding)
 ******************************************************************************/
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/objects/class.tx_pttools_cliHandler.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/objects/class.tx_pttools_cliHandler.php']);
}

?>