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
 * Session Storage Adapter for TYPO3 FE _browser_ sessions and BE user sessions (part of the library extension 'pt_tools')
 *
 * $Id$
 *
 * @author  Rainer Kuhn <kuhn@punkt.de>
 * @since   2005-09-23
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 */



/**
 * Inclusion of external resources
 */
require_once t3lib_extMgm::extPath('pt_tools').'res/abstract/class.tx_pttools_iStorageAdapter.php'; // storage adapter interface
require_once t3lib_extMgm::extPath('pt_tools').'res/abstract/class.tx_pttools_iSingleton.php'; // interface for Singleton design pattern
require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_assert.php';



/**
 * Session Storage Adapter class for TYPO3 Frontend _browser_ sessions and Backend user sessions
 *
 * @author      Rainer Kuhn <kuhn@punkt.de>
 * @since       2005-09-23
 * @package     TYPO3
 * @subpackage  tx_pttools
 */
class tx_pttools_sessionStorageAdapter implements tx_pttools_iSingleton, tx_pttools_iStorageAdapter {

    /**
     * Properties
     */
    private static $uniqueInstance = NULL; // (tx_pttools_sessionStorageAdapter object) Singleton unique instance



    /***************************************************************************
     *   CONSTRUCTOR
     **************************************************************************/

    /**
     * Class constructor: must not be called directly in order to use getInstance() to get the unique instance of the object
     *
     * @param   void
     * @return  void
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-09-26
     */
    private function __construct() {
    }

    /**
     * Returns a unique instance (Singleton) of the object. Use this method instead of the private/protected class constructor.
     *
     * @param   void
     * @return  tx_pttools_sessionStorageAdapter      unique instance of the object (Singleton)
     * @global
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-09-26
     */
    public static function getInstance() {

        if (self::$uniqueInstance === NULL) {
            $className = __CLASS__;
            self::$uniqueInstance = new $className;
        }

        return self::$uniqueInstance;

    }

    /**
     * Final method to prevent object cloning (using 'clone'), in order to use only the unique instance of the Singleton object.
     * @param   void
     * @return  void
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-09-15
     */
    public final function __clone() {

        trigger_error('Clone is not allowed for '.get_class($this).' (Singleton)', E_USER_ERROR);

    }



    /***************************************************************************
     *   GENERAL METHODS
     **************************************************************************/

    /**
     * Returns the value of a key from TYPO3 FE _browser_ session or a BE user session (if the session value is serialized it will be returned unserialized)
     *
     * @param   string      name of session key to get the value of
     * @param 	bool		(optional) allow automatic unserializing of objects within this method
     * @return  mixed       associated value from session
     * @global  object      $GLOBALS['TSFE']->fe_user: tslib_feUserAuth Object
     * @global  object      $GLOBALS['BE_USER']: t3lib_userAuth Object
     * @throws  tx_pttools_exceptionAssertion   if no valid frontend user and no valid backend user found
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-09-23
     */
    public function read($key, $allowUnserializing=true) {

        // TYPO3 Frontend mode
        if (TYPO3_MODE == 'FE' && ($GLOBALS['TSFE']->fe_user instanceof tslib_feUserAuth)) {

            $val = $GLOBALS['TSFE']->fe_user->getKey('ses', $key);
            if (TYPO3_DLOG) t3lib_div::devLog(sprintf('Reading "%s" from FE browser session in "$GLOBALS[\'TSFE\']->fe_user"', $key), 'pt_tools');

            if (($allowUnserializing == true) && (is_string($val) && unserialize($val) != false)) {
                $val = unserialize($val);
            }


        // TYPO3 Backend mode
        } else {

            tx_pttools_assert::isInstanceOf($GLOBALS['BE_USER'], 't3lib_userAuth', array('message' => 'No valid backend user found!'));

            $val = $GLOBALS['BE_USER']->getSessionData($key);
            if (TYPO3_DLOG) t3lib_div::devLog(sprintf('Reading "%s" from BE user session in "$GLOBALS[\'BE_USER\']"', $key), 'pt_tools');

        }

        return $val;

    }

    /**
     * Saves a value (objects and arrays will be serialized before) into a session key of FE _browser_ session or a BE user session *immediately* (does not wait for complete script execution)
     *
     * @param   string      name of session key to save value into
     * @param   string      value to be saved with session key
     * @param 	bool		(optional) allow automatic serializing of objects within this method
     * @return  void
     * @global  object      $GLOBALS['TSFE']->fe_user: tslib_feUserAuth Object
     * @global  object      $GLOBALS['BE_USER']: t3lib_userAuth Object
     * @throws  tx_pttools_exceptionAssertion   if no valid frontend user and no valid backend user found
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-09-23
     */
    public function store($key, $val, $allowSerializing=true, $foreignSessionId=NULL) {

        // TYPO3 Frontend mode
        if (TYPO3_MODE == 'FE' && ($GLOBALS['TSFE']->fe_user instanceof tslib_feUserAuth)) {

            if (($allowSerializing == true) && (is_object($val) || is_array($val))) {
                $val = serialize($val);
            }

            if (is_null($foreignSessionId)) {
                $GLOBALS['TSFE']->fe_user->setKey('ses', $key, $val);
                $GLOBALS['TSFE']->fe_user->sesData_change = 1;
                $GLOBALS['TSFE']->fe_user->storeSessionData();
                if (TYPO3_DLOG) t3lib_div::devLog(sprintf('Storing "%s" into FE browser session using "$GLOBALS[\'TSFE\']->fe_user"', $key), 'pt_tools');
            } else {

                tx_pttools_assert::isString($foreignSessionId);

                // read current foreign session data
                $rows = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
                    '*',
                    'fe_session_data',
                    'hash='.$GLOBALS['TYPO3_DB']->fullQuoteStr($foreignSessionId, 'fe_session_data')
                );
                $sessionData = unserialize($rows[0]['content']);

                // update sessionData
                $sessionData[$key] = $val;

                // write sessionData back to database
                $insertFields = array (
                    'hash' => $foreignSessionId,
                    'content' => serialize($sessionData),
                    'tstamp' => time()
                );
                $GLOBALS['TYPO3_DB']->exec_DELETEquery('fe_session_data', 'hash='.$GLOBALS['TYPO3_DB']->fullQuoteStr($foreignSessionId, 'fe_session_data'));
                $GLOBALS['TYPO3_DB']->exec_INSERTquery('fe_session_data', $insertFields);
                if (TYPO3_DLOG) t3lib_div::devLog(sprintf('Storing "%s" into foreign FE browser session "%s"', $key, $foreignSessionId), 'pt_tools');
            }

        // TYPO3 Backend mode
        } else {

            tx_pttools_assert::isInstanceOf($GLOBALS['BE_USER'], 't3lib_userAuth', array('message' => 'No valid backend user found!'));

            $GLOBALS['BE_USER']->setAndSaveSessionData($key, $val);
            if (TYPO3_DLOG) t3lib_div::devLog(sprintf('Storing "%s" into BE user session using "$GLOBALS[\'BE_USER\']"', $key), 'pt_tools');

        }

    }

    /**
     * Deletes a session value from TYPO3 FE _browser_ session or a BE user session *immediately* (does not wait for complete script execution)
     *
     * @param   string      name of session key to delete (array key)
     * @return  void
     * @global  object      $GLOBALS['TSFE']->fe_user: tslib_feUserAuth Object
     * @global  object      $GLOBALS['BE_USER']: t3lib_userAuth Object
     * @throws  tx_pttools_exceptionAssertion   if no valid frontend user and no valid backend user found
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-09-23
     */
    public function delete($key) {

        // TYPO3 Frontend mode
        if (TYPO3_MODE == 'FE' && ($GLOBALS['TSFE']->fe_user instanceof tslib_feUserAuth)) {

            if (!empty($GLOBALS['TSFE']->fe_user->sesData[$key])) {
                unset($GLOBALS['TSFE']->fe_user->sesData[$key]);
                $GLOBALS['TSFE']->fe_user->sesData_change = 1;
                $GLOBALS['TSFE']->fe_user->storeSessionData();
                if (TYPO3_DLOG) t3lib_div::devLog(sprintf('Deleting "%s" from FE browser session in "$GLOBALS[\'TSFE\']->fe_user"', $key), 'pt_tools');
            }

        // TYPO3 Backend mode
        } else {

            tx_pttools_assert::isInstanceOf($GLOBALS['BE_USER'], 't3lib_userAuth', array('message' => 'No valid backend user found!'));

            $sesDat = unserialize($GLOBALS['BE_USER']->user['ses_data']);

            if (!empty($sesDat[$key])) {
                unset($sesDat[$key]);
                $GLOBALS['BE_USER']->user['ses_data'] = (!empty($sesDat) ? serialize($sesDat) : '');
                // this is adapted from t3lib_userAuth::setAndSaveSessionData()
                $GLOBALS['TYPO3_DB']->exec_UPDATEquery($GLOBALS['BE_USER']->session_table,
                                                       'ses_id='.$GLOBALS['TYPO3_DB']->fullQuoteStr($GLOBALS['BE_USER']->user['ses_id'], $GLOBALS['BE_USER']->session_table),
                                                       array('ses_data' => $GLOBALS['BE_USER']->user['ses_data'])
                                                      );
                if (TYPO3_DLOG) t3lib_div::devLog(sprintf('Deleting "%s" from BE user in "$GLOBALS[\'BE_USER\']"', $key), 'pt_tools');
            }
        }

    }



} // end class



/*******************************************************************************
 *   TYPO3 XCLASS INCLUSION (for class extension/overriding)
 ******************************************************************************/
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/objects/class.tx_pttools_sessionStorageAdapter.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/objects/class.tx_pttools_sessionStorageAdapter.php']);
}

?>