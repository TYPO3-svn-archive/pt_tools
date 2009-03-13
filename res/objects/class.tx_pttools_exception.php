<?php
/***************************************************************
*  Copyright notice
*  
*  (c) 2005-2008 Rainer Kuhn (kuhn@punkt.de)
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
 * General exception class (part of the library extension 'pt_tools')
 *
 * $Id: class.tx_pttools_exception.php,v 1.28 2009/03/11 08:57:35 ry21 Exp $
 *
 * @author  Rainer Kuhn <kuhn@punkt.de>
 * @since   2005-08-12
 */ 
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 */



/**
 * Inclusion of external resources
 */
require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_debug.php'; // debugging class with trace() function

/**
 * Inclusion of inheriting exception classes for special exceptions (to ease the exception usage for the developer he has to include the general tx_pttools_exception class only)
 */
require_once t3lib_extMgm::extPath('pt_tools').'res/objects/exceptions/class.tx_pttools_exceptionDatabase.php';
require_once t3lib_extMgm::extPath('pt_tools').'res/objects/exceptions/class.tx_pttools_exceptionConfiguration.php';
require_once t3lib_extMgm::extPath('pt_tools').'res/objects/exceptions/class.tx_pttools_exceptionInternal.php';
require_once t3lib_extMgm::extPath('pt_tools').'res/objects/exceptions/class.tx_pttools_exceptionAuthentication.php';
require_once t3lib_extMgm::extPath('pt_tools').'res/objects/exceptions/class.tx_pttools_exceptionWebservice.php';
require_once t3lib_extMgm::extPath('pt_tools').'res/objects/exceptions/class.tx_pttools_exceptionAssertion.php'; // used in tx_pttools_assert when assertions fails
require_once t3lib_extMgm::extPath('pt_tools').'res/objects/exceptions/class.tx_pttools_exceptionNotYetImplemented.php';


/**
 * General exception class derived from PHP's default Exception class
 *
 * @author      Rainer Kuhn <kuhn@punkt.de>
 * @since       2005-08-12
 * @package     TYPO3
 * @subpackage  tx_pttools
 */
class tx_pttools_exception extends Exception {
    
    /*
    // Dev Info: Class structure of parent class (PHP5's default Exception):
    
    class Exception
    {
        protected $message = 'Unknown exception';   // exception message
        protected $code = 0;                        // user defined exception code
        protected $file;                            // source filename of exception
        protected $line;                            // source line of exception
    
        function __construct($message = null, $code = 0);
    
        final function getMessage();                // message of exception 
        final function getCode();                   // code of exception
        final function getFile();                   // source filename
        final function getLine();                   // source line
        final function getTrace();                  // an array of the backtrace()
        final function getTraceAsString();          // formated string of trace
    
        // Overrideable
        function __toString();                      // formated string for display
    }
    */
    
    /***************************************************************************
     *  CLASS CONSTANTS  // DEPRECATED for public usage: use special exception classes in res/objects/exceptions/ instead!
     **************************************************************************/
     
    /**
     * @const   integer     constant for database error exception
     */
    const EXCP_DATABASE = 1;
    
    /**
     * @const   integer     constant for configuration error exception
     */
    const EXCP_CONFIG = 2;
    
    /**
     * @const   integer     constant for internal error exception
     */
    const EXCP_INTERNAL = 3;
    
    /**
     * @const   integer     constant for authentication error exception
     */
    const EXCP_AUTH = 4;
    
    /**
     * @const   integer     constant for webservice error exception
     */
    const EXCP_WEBSERVICE = 5;
    
    
    
    /***************************************************************************
     *  PROPERTIES
     **************************************************************************/
     
    /**
     * @var     string      additional detailed debug message
     */
    protected $debugMsg = '';
    
    /**
     * @var     string      error type name (depending on error code param passed to constructor)
     */
    protected $errType = '';
    
    
    
    /***************************************************************************
     *   CONSTRUCTOR
     **************************************************************************/
     
    /**
     * Class constructor: sets internal properties and calls the parent constructor (Exception::__construct(...)
     * 
     * @param   string    optional error message (used for frontend/enduser display, too)    
     * @return  integer   DEPRECATED: optional error code, see EXCP_* class constants (currently: 1=DATABASE ERR, 2=CONFIG ERR, 3=INTERNAL ERR, 4=AUTH ERR, 5=WEBSERVICE ERR) - DEPRECATED for public usage: use special exception classes in res/objects/exceptions/ instead!
     * @param   string    optional detailed debug message (not used for frontend display). For database errors (error code 1) the last TYPO3 DB SQL error is set to the debug message by default. To suppress this or to trace another DB object's SQL error use the third param to replace this default.    
     * @global  object    $GLOBALS['TYPO3_DB']: t3lib_db Object (TYPO3 DB API)
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-08-12
     */
    public function __construct($errMsg='', $errCode=0, $debugMsg='') {
        
        trace('***** Creating new '.__CLASS__.' object. *****');
        
        $this->debugMsg = $debugMsg;
        
        // handle different error types ("old" switch structure remains for backwards compatibility)
        switch ($errCode) {
            case self::EXCP_DATABASE:
                $this->errType = 'DATABASE ERROR';
                break;
            case self::EXCP_CONFIG:
                $this->errType = 'CONFIGURATION ERROR';
                break;
            case self::EXCP_INTERNAL:
                $this->errType = 'INTERNAL ERROR';
                break;
            case self::EXCP_AUTH:
                $this->errType = 'AUTHENTICATION ERROR';
                break;
            case self::EXCP_WEBSERVICE:
                $this->errType = 'WEBSERVICE ERROR';
                break;
            default:
                $this->errType = 'ERROR';
                break;
        }
        
        // write to devlog
        if (TYPO3_DLOG) {
            t3lib_div::devLog(
                $this->getMessage(), 
                'pt_tools', 
                1, // "notice"
                array(
                    'exceptionClass' => get_class($this), 
                    'debugMsg' => $this->debugMsg, 
                    'file' => $this->getFile(), 
                    'line' => $this->getLine(), 
                    'code' => $this->getCode(),
                    // 'trace' => tx_pttools_debug::cleanBacktrace($this->getTrace()),
                    'trace' => $this->getTraceAsString(),
                )
            );
        }
        
        // call parent constructor to make sure everything is assigned properly
        parent::__construct($errMsg, $errCode);
        
    }
    
    
    
    /***************************************************************************
     *   GENERAL METHODS
     **************************************************************************/
    
    /**
     * Custom string representation of the object - can be used for frontend/enduser display
     *
     * @param   void       
     * @return  string      Error type and error display message
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-08-12
     */
    public function __toString() {
        
        $displayString = '[' . $this->errType.(!empty($this->message) ? ': '.$this->message : '!') . ']';
        return $displayString;
        
    }
    
    /**
     * DEPRECATED: use $this->handle() instead
     * 
     * Handles an exception: Debug information is written to TYPO3 devlog, TYPO3 syslog, TYPO3 TS log and is sent to trace()
     *
     * @deprecated use $this->handle() instead
     * 
     * @param   void       
     * @return  void
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-08-12
     */
    public function handleException() {
        
        $this->handle();
        
    }
    
    /**
     * Handles an exception: Debug information is written to TYPO3 devlog, TYPO3 syslog, TYPO3 TS log and is sent to trace()
     *
     * @param   void       
     * @return  void
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-08-12
     */
    public function handle() {
        
        $traceString = 
            'Error Type     : '.$this->errType.chr(10).
            'Exception Class: '.get_class($this).chr(10).
            'Error Message  : '.$this->getMessage().chr(10).
            (!empty($this->debugMsg) ? 'Debug Message  : '.$this->debugMsg.chr(10) : '').
            /*
            'Code           : '.$this->getCode().chr(10).
            'File           : '.$this->getFile().chr(10).
            'Line           : '.$this->getLine().chr(10).
            'getTrace(): '.trace($this->getTrace(), 1).chr(10).chr(10).
            */
            'Stack Trace    : '.chr(10).$this->getTraceAsString().chr(10).chr(10)
            ;
        
        // write to TYPO3 devlog
        if (TYPO3_DLOG) {
            t3lib_div::devLog(
                $this->getMessage(), 
                'pt_tools', 
                3, // "error"
                array(
                    'exceptionClass' => get_class($this), 
                    'debugMsg' => $this->debugMsg, 
                    'file' => $this->getFile(), 
                    'line' => $this->getLine(), 
                    'code' => $this->getCode(),
                    // 'trace' => tx_pttools_debug::cleanBacktrace($this->getTrace()),
                    'trace' => $this->getTraceAsString(),
                )
            );
        }
        
        // write to TYPO3 syslog
        t3lib_div::sysLog(
            $this->getMessage().'['.get_class($this).': '.$this->debugMsg.']', 
            'pt_tools', 
            3 // "error"
        );
        
        // write to TS log if appropriate
        if ($GLOBALS['TT'] instanceof t3lib_timeTrack) {
            $GLOBALS['TT']->setTSlogMessage($this->getMessage() . '['.get_class($this).': '.$this->debugMsg.']', 3);
        }
            
        trace($traceString, 0, '############ '.get_class($this).' ############');
        
    }
    
    
    
    /***************************************************************************
     *   GETTER
     **************************************************************************/
    
    /**
     * Return the error type of the exception
     *
     * @param   void
     * @return  string
     * @since   2008-06-12
     */
    public function getErrType() {
        
        return $this->errType;
        
    }
    
    /**
     * Return the debug message of the exception
     *
     * @param   void
     * @return  string
     * @since   2008-06-12
     */
    public function getDebugMsg() {
        
        return $this->debugMsg;
        
    }
    
    
    
} // end class



/*******************************************************************************
 *   TYPO3 XCLASS INCLUSION (for class extension/overriding)
 ******************************************************************************/
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/objects/class.tx_pttools_exception.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/objects/class.tx_pttools_exception.php']);
}

?>