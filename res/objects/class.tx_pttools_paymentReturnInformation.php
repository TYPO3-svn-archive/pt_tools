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
 * Payment return information class for pt_tools
 *
 * $Id$
 *
 * @author      Rainer Kuhn <kuhn@punkt.de>
 * @since       2009-02-04
 */



/**
 * Inclusion of extension specific resources
 */
require_once t3lib_extMgm::extPath('pt_tools').'res/abstract/class.tx_pttools_iSettableByArray.php';
require_once t3lib_extMgm::extPath('pt_tools').'res/objects/class.tx_pttools_exception.php'; // general exception class
require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_debug.php'; // debugging class with trace() function
require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_assert.php'; // assertion class
require_once t3lib_extMgm::extPath('pt_tools').'res/objects/class.tx_pttools_sessionStorageAdapter.php'; // storage adapter for TYPO3 _browser_ sessions



/**
 * Payment return information class
 *
 * @author      Rainer Kuhn <kuhn@punkt.de>
 * @since       2009-02-04
 * @package     TYPO3
 * @subpackage  tx_pttools
 */
class tx_pttools_paymentReturnInformation implements tx_pttools_iSettableByArray  {


    /***************************************************************************
     * Class Constants
     **************************************************************************/

    /**
     * @var     string      name of the session key used to store the payment return information into (to be read be appropriate epayment extension)
     */
    const SESSION_KEY_NAME_PAYMENT_RETURN = 'tx_pttools_paymentReturnInformation';

    /**
     * @var     integer     payment transaction status: success
     */
    const STATUS_SUCCESS = 0;

    /**
     * @var     integer     payment transaction status: error
     */
    const STATUS_ERROR = 1;

    /**
     * @var     integer     payment transaction status: abort
     */
    const STATUS_ABORT = 2;




    /***************************************************************************
     * Properties
     **************************************************************************/

    /**
     * @var     string  unique transaction identifier generated by payment extension
     */
    protected $transactionIdentifier;

    /**
     * @var     string  merchant/shop reference number of the related ordering process, e.g. invoice number, confirmation number or booking id
     */
    protected $merchantReference;

    /**
     * @var     double  amount/total sum to pay
     */
    protected $amount;

    /**
     * @var     integer  transaction status [self::STATUS_SUCCESS | self::STATUS_ERROR | self::STATUS_ABORT]
     * @see     self::STATUS_SUCCESS
     * @see     self::STATUS_ERROR
     * @see     self::STATUS_ABORT
     */
    protected $status;

    /**
     * @var     string  payment reference identifier created by payment provider
     */
    protected $paymentReferenceId;

    /**
     * @var     array  payment provider specific response data
     */
    protected $providerResponseArray;





    /***************************************************************************
     * Constructor
     **************************************************************************/

    /**
     * Set the object's properties by passing an array to it
     *
     * @param   array   (optional) array of properties to be set ('propertyName' => 'propertyValue')
     * @param   array   (optional) flag whether the array of properties should be read from session (this setting has no effect if 1st param is set)
     * @return  void
     * @author  Rainer Kuhn <kuhn@punkt.de>, Fabrizio Branca <fabrizio.branca@aoemedia.de>
     * @since   2009-02-04
     */
    public function __construct(array $dataArray=array(), $setDataFromSession=false) {

        if (!empty($dataArray)) {
            $propertyArray = $dataArray;
        } elseif ($setDataFromSession == true) {
            $propertyArray = tx_pttools_sessionStorageAdapter::getInstance()->read(self::SESSION_KEY_NAME_PAYMENT_RETURN);
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
     * @since   2009-02-04
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
     * Stores the payment return information as a serialized array to the browser session
     *
     * @param   void
     * @param   string 	(optional) foreign session id
     * @return  void
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2009-02-04
     */
    public function storeToSession($foreignSessionId=NULL) {

        $dataArray = array();

        foreach (get_class_vars(get_class($this)) as $propertyName => $propertyValue) {
            $getter = 'get_'.$propertyName;
            $dataArray[$propertyName] = $this->$getter();
        }

        tx_pttools_sessionStorageAdapter::getInstance()->store(self::SESSION_KEY_NAME_PAYMENT_RETURN, $dataArray, true, $foreignSessionId);
        if (TYPO3_DLOG) t3lib_div::devLog('Stored standardized payment return information into FE browser session key "'.self::SESSION_KEY_NAME_PAYMENT_RETURN.'"', 'pt_tools', 0, $dataArray);

    }



    /***************************************************************************
     *   Getter/Setter
     **************************************************************************/

    /**
     * Returns the property value
     *
     * @param   void
     * @return  string       property value
     * @since   2009-02-05
     */
    public function get_transactionIdentifier() {

        return $this->transactionIdentifier;

    }

    /**
     * Sets the property value
     *
     * @param   string       property value: transaction identifier generated by payment extension (to identify the original transaction request the transaction response was sent for)
     * @return  void
     * @since   2009-02-05
     */
    protected function set_transactionIdentifier($transactionIdentifier) {

        tx_pttools_assert::isNotEmptyString($transactionIdentifier, array('message' => 'No valid transaction identifier given.'));
        $this->transactionIdentifier = (string) $transactionIdentifier;

    }

    /**
     * Returns the property value
     *
     * @param   void
     * @return  string       property value
     * @since   2009-02-05
     */
    public function get_merchantReference() {

        return $this->merchantReference;

    }

    /**
     * Sets the property value
     *
     * @param   string       property value: merchant reference of the related ordering process, e.g. invoice number, confirmation number or booking id
     * @return  void
     * @since   2009-02-05
     */
    protected function set_merchantReference($merchantReference) {

        tx_pttools_assert::isNotEmptyString($merchantReference, array('message' => 'No valid merchant reference given.'));
        $this->merchantReference = (string) $merchantReference;

    }

    /**
     * Returns the property value
     *
     * @param   void
     * @return  double       property value
     * @since   2009-02-05
     */
    public function get_amount() {

        return $this->amount;

    }

    /**
     * Sets the property value
     *
     * @param   double       property value: amount/total sum to pay
     * @return  void
     * @since   2009-02-05
     */
    protected function set_amount($amount) {

        tx_pttools_assert::isNumeric($amount, array('message' => 'No valid amount given.'));
        $this->amount = (double) $amount;

    }

    /**
     * Returns the property value
     *
     * @param   void
     * @return  integer       property value: transaction status [self::STATUS_SUCCESS | self::STATUS_ERROR | self::STATUS_ABORT]
     * @since   2009-02-05
     */
    public function get_status() {

        return $this->status;

    }

    /**
     * Sets the property value
     *
     * @param   integer       property value: transaction status [self::STATUS_SUCCESS | self::STATUS_ERROR | self::STATUS_ABORT]
     * @return  void
     * @since   2009-02-05
     */
    protected function set_status($status) {

        tx_pttools_assert::isInteger($status, array('message' => 'No valid status given.'));
        tx_pttools_assert::isInList((string)$status, ((string)self::STATUS_SUCCESS.','.(string)self::STATUS_ERROR.','.(string)self::STATUS_ABORT), array('message'=>'No valid status given.'));
        $this->status = (int) $status;

    }

    /**
     * Returns the property value
     *
     * @param   void
     * @return  string       property value: payment reference identifier created by payment provider
     * @since   2009-02-05
     */
    public function get_paymentReferenceId() {

        return $this->paymentReferenceId;

    }

    /**
     * Sets the property value
     *
     * @param   string       property value: payment reference identifier created by payment provider
     * @return  void
     * @since   2009-02-05
     */
    protected function set_paymentReferenceId($paymentReferenceId) {

        tx_pttools_assert::isNotEmptyString($paymentReferenceId, array('message' => 'No valid payment reference id given.'));
        $this->paymentReferenceId = (string) $paymentReferenceId;

    }

    /**
     * Returns the property value
     *
     * @param   void
     * @return  array       array containing all payment provider specific response data
     * @since   2009-02-05
     */
    public function get_providerResponseArray() {

        return $this->providerResponseArray;

    }

    /**
     * Sets the property value
     *
     * @param   array       arbitrary array containing all payment provider specific response data
     * @return  void
     * @since   2009-02-05
     */
    protected function set_providerResponseArray(array $providerResponseArray) {

        tx_pttools_assert::isArray($providerResponseArray, array('message' => 'No valid provider response array given.'));
        $this->providerResponseArray = $providerResponseArray;

    }


} // end class



/*******************************************************************************
 *   TYPO3 XCLASS INCLUSION (for class extension/overriding)
 ******************************************************************************/
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/objects/class.tx_pttools_paymentReturnInformation.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/objects/class.tx_pttools_paymentReturnInformation.php']);
}

?>
