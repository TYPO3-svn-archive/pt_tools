<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Rainer Kuhn <kuhn@punkt.de>
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
 * Payment request information class for pt_tools
 *
 * $Id: class.tx_pttools_paymentRequestInformation.php,v 1.4 2009/02/13 13:51:12 ry37 Exp $
 *
 * @author      Rainer Kuhn <kuhn@punkt.de>
 * @since       2009-02-03
 */



/**
 * Inclusion of extension specific resources
 */
require_once t3lib_extMgm::extPath('pt_tools').'res/abstract/class.tx_pttools_iSettableByArray.php';
require_once t3lib_extMgm::extPath('pt_tools').'res/objects/class.tx_pttools_exception.php'; // general exception class
require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_debug.php'; // debugging class with trace() function
require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_assert.php'; // assertion class
require_once t3lib_extMgm::extPath('pt_tools').'res/objects/class.tx_pttools_sessionStorageAdapter.php'; // storage adapter for TYPO3 _browser_ sessions
require_once t3lib_extMgm::extPath('pt_tools').'res/abstract/class.tx_pttools_address.php'; // general address helper class



/**
 * Payment request information class
 *
 * @author      Rainer Kuhn <kuhn@punkt.de>
 * @since       2009-02-03
 * @package     TYPO3
 * @subpackage  tx_pttools
 */
class tx_pttools_paymentRequestInformation implements tx_pttools_iSettableByArray  {
    
    
    /***************************************************************************
     * Class Constants
     **************************************************************************/
    
    /**
     * @var     string      name of the session key used to store the payment request information into (to be read be appropriate epayment extension)
     */
    const SESSION_KEY_NAME_PAYMENT_REQUEST = 'tx_pttools_paymentRequestInformation';
    
    
    
    /***************************************************************************
     * Properties
     **************************************************************************/
    
    /**
     * @var 	string	merchant/shop reference identificator of the related ordering process, e.g. invoice number, confirmation number or booking id
     */
    protected $merchantReference;
    
    /**
     * @var     double  amount/total sum to pay  
     */
    protected $amount;
    
    /**
     * @var     string  currency code (e.g. 'EUR' for Euro)
     */
    protected $currencyCode;
    
    /**
     * @var     string  (optional) "salt" string to use for building an md5 hash within the appropriate epayment extension (to be used e.g. for security check of successful payment returns)
     */
    protected $salt;
    
    /**
     * @var     integer  (optional) quantity of articles in order
     */
    protected $articleQuantity;
    
    /**
     * @var     string  (optional) individual additional information text
     */
    protected $infotext;
    
    /**
     * @var     tx_pttools_address  (optional) billing address object of the ordering user
     */
    protected $billingAddress;
    
    
    
    
    
    /***************************************************************************
     * Constructor
     **************************************************************************/
    
    /**
     * Set the object's properties by passing an array to it 
     * 
     * @param   array   (optional) array of properties to be set ('propertyName' => 'propertyValue')
     * @param   array   (optional) flag whether the array of properties should be read from session (this setting has no effect if 1st param is set)
     * @return  void
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2009-02-03
     */
    public function __construct(array $dataArray=array(), $setDataFromSession=false) {
        
        if (!empty($dataArray)) {
            $propertyArray = $dataArray;
        } elseif ($setDataFromSession == true) {
            $propertyArray = tx_pttools_sessionStorageAdapter::getInstance()->read(self::SESSION_KEY_NAME_PAYMENT_REQUEST);
        }
        
        if (!empty($propertyArray)) {
            $this->setPropertiesFromArray($propertyArray);
        }
        
    }
    
    
    
    /***************************************************************************
     *   Methods implementing tx_pttools_iSettableByArray
     **************************************************************************/
    
    /**
     * Set the object's properties by passing an array to it 
     * 
     * @param   array   array of properties ('propertyName' => 'propertyValue') to be set
     * @return  void
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2009-02-03
     */
    public function setPropertiesFromArray(array $dataArray) {
        
        foreach (get_class_vars(get_class($this)) as $propertyName => $propertyValue) {
            if (isset($dataArray[$propertyName])) {
                $setter = 'set_'.$propertyName;
                $this->$setter($dataArray[$propertyName]);
            }
        }
        
    }
    
    
    
    /***************************************************************************
     *   Domain logic
     **************************************************************************/

    /**
     * Stores the payment request information as a serialized array to the browser session
     *
     * @param   void        
     * @return  void
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2009-02-03
     */
    public function storeToSession() {
        
        $dataArray = array();
        
        foreach (get_class_vars(get_class($this)) as $propertyName => $propertyValue) {
            $getter = 'get_'.$propertyName;
            $dataArray[$propertyName] = $this->$getter();
        }
        
        tx_pttools_sessionStorageAdapter::getInstance()->store(self::SESSION_KEY_NAME_PAYMENT_REQUEST, $dataArray);
        if (TYPO3_DLOG) t3lib_div::devLog('Stored standardized payment request information into FE browser session key "'.self::SESSION_KEY_NAME_PAYMENT_REQUEST.'"', 'pt_tools', 0, $dataArray);
        
    }
    
    
    
    /***************************************************************************
     *   Getter/Setter
     **************************************************************************/
    
    /**
     * Returns the property value
     *
     * @param   void        
     * @return  string       property value
     * @since   2009-02-03
     */
    public function get_salt() {
    
        return $this->salt;
        
    }

    /**
     * Sets the property value
     *
     * @param   string       property value       
     * @return  void
     * @since   2009-02-03
     */
    protected function set_salt($salt) {
    
        tx_pttools_assert::isNotEmptyString($salt, array('message' => 'No valid salt string given.'));
        $this->salt = (string) $salt;
        
    }
    
    /**
     * Returns the property value
     *
     * @param   void        
     * @return  string       property value
     * @since   2009-02-03
     */
    public function get_merchantReference() {
    
        return $this->merchantReference;
        
    }

    /**
     * Sets the property value
     *
     * @param   string       property value       
     * @return  void
     * @since   2009-02-03
     */
    public function set_merchantReference($merchantReference) {
    
        tx_pttools_assert::isNotEmptyString($merchantReference, array('message' => 'No valid merchant reference given.'));
        $this->merchantReference = (string) $merchantReference;
        
    }
    
    /**
     * Returns the property value
     *
     * @param   void        
     * @return  double       property value
     * @since   2009-02-03
     */
    public function get_amount() {
    
        return $this->amount;
        
    }

    /**
     * Sets the property value
     *
     * @param   double       property value       
     * @return  void
     * @since   2009-02-03
     */
    public function set_amount($amount) {
    
        tx_pttools_assert::isNumeric($amount, array('message' => 'No valid amount given.'));
        $this->amount = (double) $amount;
        
    }
    
    /**
     * Returns the property value
     *
     * @param   void        
     * @return  string       property value
     * @since   2009-02-03
     */
    public function get_currencyCode() {
    
        return $this->currencyCode;
        
    }

    /**
     * Sets the property value
     *
     * @param   string       property value: currency code (e.g. 'EUR' for Euro)    
     * @return  void
     * @since   2009-02-03
     */
    public function set_currencyCode($currencyCode) {
    
        tx_pttools_assert::isNotEmptyString($currencyCode, array('message' => 'No valid currency code given.'));
        $this->currencyCode = (string) $currencyCode;
        
    }
    /**
     * Returns the property value
     *
     * @param   void        
     * @return  string       property value
     * @since   2009-02-03
     */
    public function get_infotext() {
    
        return $this->infotext;
        
    }

    /**
     * Sets the property value
     *
     * @param   string       property value       
     * @return  void
     * @since   2009-02-03
     */
    public function set_infotext($infotext) {
    
        tx_pttools_assert::isNotEmptyString($infotext, array('message' => 'No valid infotext given.'));
        $this->infotext = (string) $infotext;
        
    }
    
    /**
     * Returns the property value
     *
     * @param   void        
     * @return  integer       property value
     * @since   2009-02-03
     */
    public function get_articleQuantity() {
    
        return $this->articleQuantity;
        
    }

    /**
     * Sets the property value
     *
     * @param   integer       property value       
     * @return  void
     * @since   2009-02-03
     */
    public function set_articleQuantity($articleQuantity) {
    
        tx_pttools_assert::isInteger($articleQuantity, array('message' => 'No valid article quantity given.'));
        $this->articleQuantity = (int) $articleQuantity;
        
    }
    
    /**
     * Returns the property value
     *
     * @param   void        
     * @return  tx_pttools_address       property value: billing address object of the ordering user
     * @since   2009-02-03
     */
    public function get_billingAddress() {
    
        return $this->billingAddress;
        
    }

    /**
     * Sets the property value
     *
     * @param   tx_pttools_address       property value: billing address object of the ordering user       
     * @return  void
     * @since   2009-02-03
     */
    public function set_billingAddress(tx_pttools_address $billingAddress) {
    
        tx_pttools_assert::isInstanceOf($billingAddress, 'tx_pttools_address', array('message' => 'No valid billing address object given.'));
        $this->billingAddress = $billingAddress;
        
    }
    
    
} // end class



/*******************************************************************************
 *   TYPO3 XCLASS INCLUSION (for class extension/overriding)
 ******************************************************************************/
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/objects/class.tx_pttools_paymentRequestInformation.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/objects/class.tx_pttools_paymentRequestInformation.php']);
}

?>
