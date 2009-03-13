<?php
/***************************************************************
*  Copyright notice
*  
*  (c) 2005 Rainer Kuhn (kuhn@punkt.de)
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
 * General address class (part of the library extension 'pt_tools')
 *
 * $Id: class.tx_pttools_address.php,v 1.13 2007/11/20 12:34:01 ry44 Exp $
 *
 * @author      Rainer Kuhn <kuhn@punkt.de>
 * @since       2005-08-05
 */ 
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * ===== OLD INDEX FOR PHP4 VERSION AS AT 09.08.2005! =====
 *
 *   73: class tx_pttools_address
 *
 *              SECTION: CONSTRUCTOR
 *  121:     function tx_pttools_address()
 *
 *              SECTION: METHODS
 *  145:     function getFullName()
 *  165:     function getFullSalutation()
 *  186:     function getCityWithZip()
 *
 * TOTAL FUNCTIONS: 4
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */



/**
 * General address class
 *
 * @author      Rainer Kuhn <kuhn@punkt.de>
 * @since       2005-08-05
 * @package     TYPO3
 * @subpackage  tx_pttools
 */
abstract class tx_pttools_address {
    
    /**
     * Properties
     */
    protected $lastname = ''; // (string)
    protected $firstname = ''; // (string)
    protected $salutation = ''; // (string) Mr., Mrs., ...
    protected $title = ''; // (string) Dr., Prince, ...
    
    protected $company = ''; // (string) 
    protected $department = ''; // (string) department of company
    protected $position = ''; // (string) position in company
    
    protected $streetAndNo = ''; // (string)  street and house number
    protected $addrSupplement = ''; // (string) address supplement
    protected $zip = ''; // (string)
    protected $city = ''; // (string)
    protected $poBox = ''; // (string) post box address/number
    protected $poBoxZip = ''; // (string) zip code of the post box address
    protected $poBoxCity = ''; // (string) city of the post box address
    protected $state = ''; // (string)
    protected $country = ''; // (string) the iso-3166-2 country code (two letter code)
    
    protected $phone1 = ''; // (string)
    protected $phone2 = ''; // (string)
    protected $mobile1 = ''; // (string)
    protected $mobile2 = ''; // (string)
    protected $fax1 = ''; // (string)
    protected $fax2 = ''; // (string)
    protected $email1 = ''; // (string)
    protected $email2 = ''; // (string)
    protected $url = ''; // (string) URL of website
    
    
    
    /***************************************************************************
     *   CONSTRUCTOR
     **************************************************************************/
     
    /**
     * Class constructor, to be implemented in inheriting class: should "fill" the properties on instantiation
     *  
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-08-05
     */
    abstract public function __construct();
    
    
    
    /***************************************************************************
     *   GENERAL METHODS
     **************************************************************************/
    
    /**
     * returns array with data from all properties
     *
     * @param   void
     * @return  array    array with data from all properties        
     * @author  Wolfgang Zenker <entwicklung@punkt.de>
     * @since   2007-07-06
     */
    public function getAddressDataArray() {

        $dataArray = array();

        foreach (get_class_vars( __CLASS__ ) as $propertyname => $pvalue) {
            $getter = 'get_'.$propertyname;
            $dataArray[$propertyname] = $this->$getter();
        }

        return $dataArray;
    }
        
    /**
     * Returns the full name assembled of firstname and lastname
     *
     * @param   boolean     flag wether the full a natural persons name should be returned in reverse order ('Lastname, Firstname')
     * @return  string      full name
     * @global  
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-08-05
     */
    public function getFullName($inverseOrder=0) {
        
        $fullName = '';
        
        if ($inverseOrder == 0) {
            $fullName = $this->firstname.' '.$this->lastname;
        } else {
            if ($this->lastname) {
                $fullName = $this->lastname;
                if ($this->firstname) {
                    $fullName .= ', '.$this->firstname;
                }
            } else {
                $fullName = $this->firstname;
            }
        }
        $fullName = trim($fullName);
        
        return $fullName;
        
    }
    
    /**
     * Returns the full salutation assembled of salutation, title, firstname and lastname
     *
     * @param   void        
     * @return  string      full salutation incl. title and name
     * @global  
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-08-05
     */
    public function getFullSalutation() {
        
        $fullSalutation = '';
        
        $fullSalutation .= !empty($this->salutation) ? $this->salutation.' ' : '';
        $fullSalutation .= !empty($this->title) ? $this->title.' ' : '';
        $fullSalutation .= $this->getFullName();
        
        return $fullSalutation;
        
    }
    
    /**
     * Returns the city name prefixed with the zip code
     *
     * @param   void        
     * @return  string      city name prefixed with zip
     * @global  
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-08-08
     */
    public function getCityWithZip() {
        
        $cityLine = '';
        
        $cityLine .= isset($this->zip) ? $this->zip.' ' : '';
        $cityLine .= isset($this->city) ? $this->city : '';
        
        return $cityLine;
        
    }
    
    
    
    /***************************************************************************
     *   PROPERTY GETTER/SETTER METHODS
     **************************************************************************/
     
    /**
     * Returns the property value
     *
     * @param   void        
     * @return  string      property value
     * @since   2005-09-20
     */
    public function get_lastname() {
        
        return $this->lastname;
        
    }
    
    /**
     * Returns the property value
     *
     * @param   void        
     * @return  string      property value
     * @since   2005-09-20
     */
    public function get_firstname() {
        
        return $this->firstname;
        
    }
    
    /**
     * Returns the property value
     *
     * @param   void        
     * @return  string      property value
     * @since   2005-09-20
     */
    public function get_salutation() {
        
        return $this->salutation;
        
    }
    
    /**
     * Returns the property value
     *
     * @param   void        
     * @return  string      property value
     * @since   2005-09-20
     */
    public function get_title() {
        
        return $this->title;
        
    }
    
    /**
     * Returns the property value
     *
     * @param   void        
     * @return  string      property value
     * @since   2005-09-20
     */
    public function get_company() {
        
        return $this->company;
        
    }
    
    /**
     * Returns the property value
     *
     * @param   void        
     * @return  string      property value
     * @since   2005-09-20
     */
    public function get_department() {
        
        return $this->department;
        
    }
    
    /**
     * Returns the property value
     *
     * @param   void        
     * @return  string      property value
     * @since   2005-09-20
     */
    public function get_position() {
        
        return $this->position;
        
    }
    
    /**
     * Returns the property value
     *
     * @param   void        
     * @return  string      property value
     * @since   2005-09-20
     */
    public function get_streetAndNo() {
        
        return $this->streetAndNo;
        
    }
    
    /**
     * Returns the property value
     *
     * @param   void        
     * @return  string      property value
     * @since   2005-09-20
     */
    public function get_addrSupplement() {
        
        return $this->addrSupplement;
        
    }
    
    /**
     * Returns the property value
     *
     * @param   void        
     * @return  string      property value
     * @since   2005-09-20
     */
    public function get_zip() {
        
        return $this->zip;
        
    }
    
    /**
     * Returns the property value
     *
     * @param   void        
     * @return  string      property value
     * @since   2005-09-20
     */
    public function get_city() {
        
        return $this->city;
        
    }
    
    /**
     * Returns the property value
     *
     * @param   void        
     * @return  string      property value
     * @since   2005-09-20
     */
    public function get_poBox() {
        
        return $this->poBox;
        
    }
    
    /**
     * Returns the property value
     *
     * @param   void        
     * @return  string      property value
     * @since   2005-09-20
     */
    public function get_poBoxZip() {
        
        return $this->poBoxZip;
        
    }
    
    /**
     * Returns the property value
     *
     * @param   void        
     * @return  string      property value
     * @since   2005-09-20
     */
    public function get_poBoxCity() {
        
        return $this->poBoxCity;
        
    }
    
    /**
     * Returns the property value
     *
     * @param   void        
     * @return  string      property value
     * @since   2005-09-20
     */
    public function get_state() {
        
        return $this->state;
        
    }
    
    /**
     * Returns the property value
     *
     * @param   void        
     * @return  string      property value
     * @since   2005-09-20
     */
    public function get_country() {
        
        return $this->country;
        
    }
    
    /**
     * Returns the property value
     *
     * @param   void        
     * @return  string      property value
     * @since   2005-09-20
     */
    public function get_phone1() {
        
        return $this->phone1;
        
    }
    
    /**
     * Returns the property value
     *
     * @param   void        
     * @return  string      property value
     * @since   2005-09-20
     */
    public function get_phone2() {
        
        return $this->phone2;
        
    }
    
    /**
     * Returns the property value
     *
     * @param   void        
     * @return  string      property value
     * @since   2005-09-20
     */
    public function get_mobile1() {
        
        return $this->mobile1;
        
    }
    
    /**
     * Returns the property value
     *
     * @param   void        
     * @return  string      property value
     * @since   2005-09-20
     */
    public function get_mobile2() {
        
        return $this->mobile2;
        
    }
    
    /**
     * Returns the property value
     *
     * @param   void        
     * @return  string      property value
     * @since   2005-09-20
     */
    public function get_fax1() {
        
        return $this->fax1;
        
    }
    
    /**
     * Returns the property value
     *
     * @param   void        
     * @return  string      property value
     * @since   2005-09-20
     */
    public function get_fax2() {
        
        return $this->fax2;
        
    }
    
    /**
     * Returns the property value
     *
     * @param   void        
     * @return  string      property value
     * @since   2005-09-20
     */
    public function get_email1() {
        
        return $this->email1;
        
    }
    
    /**
     * Returns the property value
     *
     * @param   void        
     * @return  string      property value
     * @since   2005-11-14
     */
    public function get_email2() {
        
        return $this->email2;
        
    }
    
    /**
     * Returns the property value
     *
     * @param   void        
     * @return  string      property value
     * @since   2005-09-20
     */
    public function get_url() {
        
        return $this->url;
        
    }
    
    /**
     * Sets the property value
     *
     * @param   string
     * @return  void
     * @since   2006-04-12
     */
    public function set_lastname($lastname) {

        $this->lastname = (string) $lastname;

    }

    /**
     * Sets the property value
     *
     * @param   string
     * @return  void
     * @since   2006-04-12
     */
    public function set_firstname($firstname) {

        $this->firstname = (string) $firstname;

    }

    /**
     * Sets the property value
     *
     * @param   string
     * @return  void
     * @since   2006-04-12
     */
    public function set_salutation($salutation) {

        $this->salutation = (string) $salutation;

    }

    /**
     * Sets the property value
     *
     * @param   string
     * @return  void
     * @since   2006-04-12
     */
    public function set_title($title) {

        $this->title = (string) $title;

    }

    /**
     * Sets the property value
     *
     * @param   string
     * @return  void
     * @since   2006-04-12
     */
    public function set_company($company) {

        $this->company = (string) $company;

    }

    /**
     * Sets the property value
     *
     * @param   string
     * @return  void
     * @since   2006-04-12
     */
    public function set_department($department) {

        $this->department = (string) $department;

    }

    /**
     * Sets the property value
     *
     * @param   string
     * @return  void
     * @since   2006-04-12
     */
    public function set_position($position) {

        $this->position = (string) $position;

    }

    /**
     * Sets the property value
     *
     * @param   string
     * @return  void
     * @since   2006-04-12
     */
    public function set_streetAndNo($streetAndNo) {

        $this->streetAndNo = (string) $streetAndNo;

    }

    /**
     * Sets the property value
     *
     * @param   string
     * @return  void
     * @since   2006-04-12
     */
    public function set_addrSupplement($addrSupplement) {

        $this->addrSupplement = (string) $addrSupplement;

    }

    /**
     * Sets the property value
     *
     * @param   string
     * @return  void
     * @since   2006-04-12
     */
    public function set_zip($zip) {

        $this->zip = (string) $zip;

    }

    /**
     * Sets the property value
     *
     * @param   string
     * @return  void
     * @since   2006-04-12
     */
    public function set_city($city) {

        $this->city = (string) $city;

    }

    /**
     * Sets the property value
     *
     * @param   string
     * @return  void
     * @since   2006-04-12
     */
    public function set_poBox($poBox) {

        $this->poBox = (string) $poBox;

    }

    /**
     * Sets the property value
     *
     * @param   string
     * @return  void
     * @since   2006-04-12
     */
    public function set_poBoxZip($poBoxZip) {

        $this->poBoxZip = (string) $poBoxZip;

    }

    /**
     * Sets the property value
     *
     * @param   string
     * @return  void
     * @since   2006-04-12
     */
    public function set_poBoxCity($poBoxCity) {

        $this->poBoxCity = (string) $poBoxCity;

    }

    /**
     * Sets the property value
     *
     * @param   string
     * @return  void
     * @since   2006-04-12
     */
    public function set_state($state) {

        $this->state = (string) $state;

    }

    /**
     * Sets the property value
     *
     * @param   string
     * @return  void
     * @since   2006-04-12
     */
    public function set_country($country) {

        $this->country = (string) $country;

    }

    /**
     * Sets the property value
     *
     * @param   string
     * @return  void
     * @since   2006-04-12
     */
    public function set_phone1($phone1) {

        $this->phone1 = (string) $phone1;

    }

    /**
     * Sets the property value
     *
     * @param   string
     * @return  void
     * @since   2006-04-12
     */
    public function set_phone2($phone2) {

        $this->phone2 = (string) $phone2;

    }

    /**
     * Sets the property value
     *
     * @param   string
     * @return  void
     * @since   2006-04-12
     */
    public function set_mobile1($mobile1) {

        $this->mobile1 = (string) $mobile1;

    }

    /**
     * Sets the property value
     *
     * @param   string
     * @return  void
     * @since   2006-04-12
     */
    public function set_mobile2($mobile2) {

        $this->mobile2 = (string) $mobile2;

    }

    /**
     * Sets the property value
     *
     * @param   string
     * @return  void
     * @since   2006-04-12
     */
    public function set_fax1($fax1) {

        $this->fax1 = (string) $fax1;

    }

    /**
     * Sets the property value
     *
     * @param   string
     * @return  void
     * @since   2006-04-12
     */
    public function set_fax2($fax2) {

        $this->fax2 = (string) $fax2;

    }

    /**
     * Sets the property value
     *
     * @param   string
     * @return  void
     * @since   2006-04-12
     */
    public function set_email1($email1) {

        $this->email1 = (string) $email1;

    }

    /**
     * Sets the property value
     *
     * @param   string
     * @return  void
     * @since   2006-04-12
     */
    public function set_email2($email2) {

        $this->email2 = (string) $email2;

    }

    /**
     * Sets the property value
     *
     * @param   string
     * @return  void
     * @since   2006-04-12
     */
    public function set_url($url) {

        $this->url = (string) $url;

    }

    
    
} // end class



/*******************************************************************************
 *   TYPO3 XCLASS INCLUSION (for class extension/overriding)
 ******************************************************************************/
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/abstract/class.tx_pttools_address.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/abstract/class.tx_pttools_address.php']);
}

?>
