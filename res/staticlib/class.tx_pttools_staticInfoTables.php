<?php
/***************************************************************
*  Copyright notice
*  
*  (c) 2005-2006 Rainer Kuhn (kuhn@punkt.de), Wolfgang Zenker <zenker@punkt.de>
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
 * Helper methods library (part of the library extension 'pt_tools') to use in combination with the TYPO3 extension 'Static info tables' (static_info_tables)
 *
 * $Id$
 *
 * @author      Rainer Kuhn <kuhn@punkt.de>, Wolfgang Zenker <zenker@punkt.de>
 * @since       2005-08-18
 */ 
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 */



/**
 * Inclusion of external resources
 */
require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_debug.php'; // debugging class with trace() function
require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_div.php'; // general helper library class
require_once t3lib_extMgm::extPath('pt_tools').'res/objects/class.tx_pttools_exception.php'; // general exception class



/**
 * Static helper methods class to use with 'Static info tables' (requires the static_info_tables extension to be installed!)
 *
 * @author      Rainer Kuhn <kuhn@punkt.de>, Wolfgang Zenker <zenker@punkt.de>
 * @since       2005-08-18
 * @package     TYPO3
 * @subpackage  tx_pttools
 */
class tx_pttools_staticInfoTables  {
    
    /***************************************************************************
     *   'STATIC COUNTRIES' DATA ACCESS METHODS
     **************************************************************************/
    
    /**
     * Returns the "short" name of a country (specified by 1st param) in the language specified by 2nd param from "static_countries" database table (part of "static_info_tables" extension)from the TYPO3 database
     *
     * This method requires the database table 'static_countries' originating from 'static_info_tables' extension
     *
     * @param   string      ISO2 country code of the requested country
     * @param   string      (optional) ISO2 country code of the language to return the country name. Currently available: 'en' (default), 'de', 'dk'
     * @global  object      $GLOBALS['TYPO3_DB']: t3lib_db Object (TYPO3 DB API)
     * @return  string      short name of the requested country, returned in in chosen language
     * @throws  tx_pttools_exception   if the query fails/returns false
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-04-19
     */
    public static function selectCountryName($iso2ccCountry, $iso2ccLang='en') {
        
        $iso2ccLang = (empty($iso2ccLang) ? 'en' : $iso2ccLang);
        
        // query preparation
        $select  = 'cn_short_'.strtolower($iso2ccLang);
        $from    = 'static_countries';
        $where   = 'cn_iso_2 LIKE '.$GLOBALS['TYPO3_DB']->fullQuoteStr(strtoupper($iso2ccCountry), $from). 
                   tx_pttools_div::enableFields($from);
        $groupBy = '';
        $orderBy = '';
        $limit   = '';
        
        // exec query using TYPO3 DB API
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $from, $where, $groupBy, $orderBy, $limit);
        trace(tx_pttools_div::returnLastBuiltSelectQuery($GLOBALS['TYPO3_DB'], $select, $from, $where, $groupBy, $orderBy, $limit));
        if ($res == false) {
            throw new tx_pttools_exception('Query failed', 1);
        }
        $a_row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
        $GLOBALS['TYPO3_DB']->sql_free_result($res);
        
        trace($a_row[$select]); 
        return $a_row[$select];
        
    } 
       
    /**
     * Returns an 2-D array containing the short names and ISO2 country codes of all countries in the language specified by 1st param from the TYPO3 database
     *
     * This method requires the database table 'static_countries' originating from 'static_info_tables' extension
     *
     * @param   string      (optional) ISO2 country code of the language to return the country names. Currently available: 'en' (default), 'de', 'dk'
     * @global  object      $GLOBALS['TYPO3_DB']: t3lib_db Object (TYPO3 DB API)
     * @return  array       2-D array of short names and ISO2 country codes of all countries in the language specified by 1st param
     * @throws  tx_pttools_exception   if the query fails/returns false
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-04-20
     */ 
    public static function selectCountries($iso2ccLang='en') {
        
        $iso2ccLang = (empty($iso2ccLang) ? 'en' : $iso2ccLang);
        
        // query preparation
        $select  = 'cn_iso_2, cn_short_'.strtolower($iso2ccLang).' AS cn_short';
        $from    = 'static_countries';
        $where   = '1 '.
                   tx_pttools_div::enableFields($from);
        $groupBy = '';
        $orderBy = 'cn_short';
        $limit   = '';
        
        // exec query using TYPO3 DB API
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $from, $where, $groupBy, $orderBy, $limit);
        trace(tx_pttools_div::returnLastBuiltSelectQuery($GLOBALS['TYPO3_DB'], $select, $from, $where, $groupBy, $orderBy, $limit));
        if ($res == false) {
            throw new tx_pttools_exception('Query failed', 1);
        }
        $a_result = array();
        while($a_row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))    {
            $a_result[] = $a_row;
        }
        $GLOBALS['TYPO3_DB']->sql_free_result($res);
        
        trace($a_result);
        return $a_result;
        
    }
    
    /**
     * Check if given country is EU member
     *
     * This method requires the database table 'static_countries' originating from 'static_info_tables' extension
     *
     * @param   string      ISO2 country code of the country to check
     * @global  object      $GLOBALS['TYPO3_DB']: t3lib_db Object (TYPO3 DB API)
     * @return  bool        country is EU member
     * @throws  tx_pttools_exception   if the query fails/returns false
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2006-05-12
     */ 
    public static function isEuMember($iso2ccCountry) {
        
        // query preparation
        $select  = 'cn_eu_member';
        $from    = 'static_countries';
        $where   = 'cn_iso_2 = '.$GLOBALS['TYPO3_DB']->fullQuoteStr(strtoupper($iso2ccCountry), $from). 
                   tx_pttools_div::enableFields($from);
        $groupBy = '';
        $orderBy = '';
        $limit   = '';
        
        // exec query using TYPO3 DB API
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $from, $where, $groupBy, $orderBy, $limit);
        trace(tx_pttools_div::returnLastBuiltSelectQuery($GLOBALS['TYPO3_DB'], $select, $from, $where, $groupBy, $orderBy, $limit));
        if ($res == false) {
            throw new tx_pttools_exception('Query failed', 1);
        }
        $a_row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
        $GLOBALS['TYPO3_DB']->sql_free_result($res);
        
        trace($a_row);
        return $a_row['cn_eu_member'] ? true : false;
        
    }
    
    /**
     * Check if given countries are on the same continent
     *
     * This method requires the database tables 'static_countries' and 'static_territories' originating from 'static_info_tables' extension
     *
     * @param   string      ISO2 country code of the first country to check
     * @param   string      ISO2 country code of the second country to check
     * @global  object      $GLOBALS['TYPO3_DB']: t3lib_db Object (TYPO3 DB API)
     * @return  bool        countries are on same continent
     * @throws  tx_pttools_exception   if the query fails/returns false
     * @author  Dorit Rottner <rottner@punkt.de>
     * @since   2006-09-22
     */ 
    public static function areSameContinent($iso2cc1, $iso2cc2) {
        
        if ($iso2cc1 == $iso2cc2) {
            // Same country nothing has to be done
            return true;
        }
        
        // query preparation
        $select  = 'territories.tr_iso_nr, territories.tr_parent_iso_nr';
        $from    = 'static_countries countries, static_territories territories';
        $where   = 'countries.cn_iso_2 = '.$GLOBALS['TYPO3_DB']->fullQuoteStr(strtoupper($iso2cc1), 'static_countries').' '.
                   'AND countries.cn_parent_tr_iso_nr = territories.tr_iso_nr '.
                   tx_pttools_div::enableFields('static_countries', 'countries');
        $groupBy = '';
        $orderBy = '';
        $limit   = '';
        
        // get Continent ISO Number for cc1
        // exec query using TYPO3 DB API
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $from, $where, $groupBy, $orderBy, $limit);
        trace(tx_pttools_div::returnLastBuiltSelectQuery($GLOBALS['TYPO3_DB'], $select, $from, $where, $groupBy, $orderBy, $limit));
        if ($res == false) {
            throw new tx_pttools_exception('Query failed', 1);
        }
        $resArr1 = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
        $GLOBALS['TYPO3_DB']->sql_free_result($res);
        
        trace($resArr1);
        if ($resArr1 == false) {
            return false;
        }

        $where   = 'countries.cn_iso_2 = '.$GLOBALS['TYPO3_DB']->fullQuoteStr(strtoupper($iso2cc2), 'static_countries').' '.
                   'AND countries.cn_parent_tr_iso_nr = territories.tr_iso_nr '.
                   tx_pttools_div::enableFields('static_countries', 'countries');
        
        // get Continent ISO Number for cc2
        // exec query using TYPO3 DB API
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $from, $where, $groupBy, $orderBy, $limit);
        trace(tx_pttools_div::returnLastBuiltSelectQuery($GLOBALS['TYPO3_DB'], $select, $from, $where, $groupBy, $orderBy, $limit));
        if ($res == false) {
            throw new tx_pttools_exception('Query failed', 1);
        }
        $resArr2 = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
        $GLOBALS['TYPO3_DB']->sql_free_result($res);
        
        trace($resArr2);
        if ($resArr2 == false) {
            return false;
        }
        
        if ($resArr1['tr_parent_iso_nr'] == $resArr2['tr_parent_iso_nr']){
            return true;
        } else { 
            return false;
        }
        
    }
    
    /**
     * Check if given country needs region in address information
     *
     * This method requires the database table 'static_countries' originating from 'static_info_tables' extension
     *
     * @param   string      ISO2 country code of the country to check
     * @global  object      $GLOBALS['TYPO3_DB']: t3lib_db Object (TYPO3 DB API)
     * @return  bool        country needs region
     * @throws  tx_pttools_exception   if the query fails/returns false
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2006-05-12
     */ 
    public static function needsRegion($iso2ccCountry) {
        
        // query preparation
        $select  = 'cn_zone_flag';
        $from    = 'static_countries';
        $where   = 'cn_iso_2 = '.$GLOBALS['TYPO3_DB']->fullQuoteStr(strtoupper($iso2ccCountry), $from). 
                   tx_pttools_div::enableFields($from);
        $groupBy = '';
        $orderBy = '';
        $limit   = '';
        
        // exec query using TYPO3 DB API
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $from, $where, $groupBy, $orderBy, $limit);
        trace(tx_pttools_div::returnLastBuiltSelectQuery($GLOBALS['TYPO3_DB'], $select, $from, $where, $groupBy, $orderBy, $limit));
        if ($res == false) {
            throw new tx_pttools_exception('Query failed', 1);
        }
        $a_row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
        $GLOBALS['TYPO3_DB']->sql_free_result($res);
        
        trace($a_row);
        return $a_row['cn_zone_flag'] ? true : false;
        
    }
    
    /**
     * Returns address format nr. for given country
     *
     * This method requires the database table 'static_countries' originating from 'static_info_tables' extension
     *
     * @param   string      ISO2 country code of the country to check
     * @global  object      $GLOBALS['TYPO3_DB']: t3lib_db Object (TYPO3 DB API)
     * @return  integer        address format nr. (see static_countries docs)
     * @throws  tx_pttools_exception   if the query fails/returns false
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2006-05-12
     */ 
    public static function addressFormat($iso2ccCountry) {
        
        // query preparation
        $select  = 'cn_address_format';
        $from    = 'static_countries';
        $where   = 'cn_iso_2 = '.$GLOBALS['TYPO3_DB']->fullQuoteStr(strtoupper($iso2ccCountry), $from). 
                   tx_pttools_div::enableFields($from);
        $groupBy = '';
        $orderBy = '';
        $limit   = '';
        
        // exec query using TYPO3 DB API
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $from, $where, $groupBy, $orderBy, $limit);
        trace(tx_pttools_div::returnLastBuiltSelectQuery($GLOBALS['TYPO3_DB'], $select, $from, $where, $groupBy, $orderBy, $limit));
        if ($res == false) {
            throw new tx_pttools_exception('Query failed', 1);
        }
        $a_row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
        $GLOBALS['TYPO3_DB']->sql_free_result($res);
        
        trace($a_row);
        return intval($a_row['cn_address_format']);
        
    }
    
    /**
     * Returns the internet toplevel domain of the country specified by 1st param
     *
     * This method requires the database table 'static_countries' originating from 'static_info_tables' extension
     *
     * @param   string      ISO2 country code of the requested country
     * @global  object      $GLOBALS['TYPO3_DB']: t3lib_db Object (TYPO3 DB API)
     * @return  string      TLD of the requested country
     * @throws  tx_pttools_exception   if the query fails/returns false
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2005-05-15
     */
    public static function getTldByIso($iso2ccCountry) {
        
        // query preparation
        $select  = 'cn_tldomain';
        $from    = 'static_countries';
        $where   = 'cn_iso_2 = '.$GLOBALS['TYPO3_DB']->fullQuoteStr(strtoupper($iso2ccCountry), $from). 
                   tx_pttools_div::enableFields($from);
        $groupBy = '';
        $orderBy = '';
        $limit   = '';
        
        // exec query using TYPO3 DB API
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $from, $where, $groupBy, $orderBy, $limit);
        trace(tx_pttools_div::returnLastBuiltSelectQuery($GLOBALS['TYPO3_DB'], $select, $from, $where, $groupBy, $orderBy, $limit));
        if ($res == false) {
            throw new tx_pttools_exception('Query failed', 1);
        }
        $a_row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
        $GLOBALS['TYPO3_DB']->sql_free_result($res);
        
        trace($a_row[$select]); 
        return $a_row[$select];
    } 
       
    /**
     * Returns the ISO-2 CC of the country with the given internet toplevel domain
     *
     * This method requires the database table 'static_countries' originating from 'static_info_tables' extension
     *
     * @param   string      TLD of the requested country
     * @global  object      $GLOBALS['TYPO3_DB']: t3lib_db Object (TYPO3 DB API)
     * @return  string      ISO2 country code of the requested country
     * @throws  tx_pttools_exception   if the query fails/returns false
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2005-05-15
     */
    public static function getIsoByTld($tld) {
        
        // query preparation
        $select  = 'cn_iso_2';
        $from    = 'static_countries';
        $where   = 'cn_tldomain = '.$GLOBALS['TYPO3_DB']->fullQuoteStr(strtoupper($tld), $from). 
                   tx_pttools_div::enableFields($from);
        $groupBy = '';
        $orderBy = '';
        $limit   = '';
        
        // exec query using TYPO3 DB API
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $from, $where, $groupBy, $orderBy, $limit);
        trace(tx_pttools_div::returnLastBuiltSelectQuery($GLOBALS['TYPO3_DB'], $select, $from, $where, $groupBy, $orderBy, $limit));
        if ($res == false) {
            throw new tx_pttools_exception('Query failed', 1);
        }
        $a_row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
        $GLOBALS['TYPO3_DB']->sql_free_result($res);
        
        trace($a_row[$select]); 
        return $a_row[$select];
    } 
       
    /**
     * Returns the ISO-3 CC of the country with the given ISO-2 CC
     *
     * This method requires the database table 'static_countries' originating from 'static_info_tables' extension
     *
     * @param   string      ISO-2 CC of the requested country
     * @global  object      $GLOBALS['TYPO3_DB']: t3lib_db Object (TYPO3 DB API)
     * @return  string      ISO-3 country code of the requested country
     * @throws  tx_pttools_exception   if the query fails/returns false
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2008-01-31
     */
    public static function getIso3ByIso2($iso2ccCountry) {
        
        // query preparation
        $select  = 'cn_iso_3';
        $from    = 'static_countries';
        $where   = 'cn_iso_2 = '.$GLOBALS['TYPO3_DB']->fullQuoteStr(strtoupper($iso2ccCountry), $from). 
                   tx_pttools_div::enableFields($from);
        $groupBy = '';
        $orderBy = '';
        $limit   = '';
        
        // exec query using TYPO3 DB API
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $from, $where, $groupBy, $orderBy, $limit);
        trace(tx_pttools_div::returnLastBuiltSelectQuery($GLOBALS['TYPO3_DB'], $select, $from, $where, $groupBy, $orderBy, $limit));
        if ($res == false) {
            throw new tx_pttools_exception('Query failed', 1);
        }
        $a_row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
        $GLOBALS['TYPO3_DB']->sql_free_result($res);
        
        trace($a_row[$select]); 
        return $a_row[$select];
    } 

    /**
     * Returns the ISO-2 CC of the country with the given ISO-3 CC
     *
     * This method requires the database table 'static_countries' originating from 'static_info_tables' extension
     *
     * @param   string      ISO-3 CC of the requested country
     * @global  object      $GLOBALS['TYPO3_DB']: t3lib_db Object (TYPO3 DB API)
     * @return  string      ISO-2 country code of the requested country
     * @throws  tx_pttools_exception   if the query fails/returns false
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2008-01-31
     */
    public static function getIso2ByIso3($iso3ccCountry) {
        
        // query preparation
        $select  = 'cn_iso_2';
        $from    = 'static_countries';
        $where   = 'cn_iso_3 = '.$GLOBALS['TYPO3_DB']->fullQuoteStr(strtoupper($iso3ccCountry), $from). 
                   tx_pttools_div::enableFields($from);
        $groupBy = '';
        $orderBy = '';
        $limit   = '';
        
        // exec query using TYPO3 DB API
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $from, $where, $groupBy, $orderBy, $limit);
        trace(tx_pttools_div::returnLastBuiltSelectQuery($GLOBALS['TYPO3_DB'], $select, $from, $where, $groupBy, $orderBy, $limit));
        if ($res == false) {
            throw new tx_pttools_exception('Query failed', 1);
        }
        $a_row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
        $GLOBALS['TYPO3_DB']->sql_free_result($res);
        
        trace($a_row[$select]); 
        return $a_row[$select];
    } 



    /***************************************************************************
     *   'STATIC COUNTRIES' BUSINESS LOGIC METHODS
     **************************************************************************/
    
    /**
     * Checks the usage of a given language country code for ouptput of static_countries. If the passed country code is not available in the static_infotables extension, it is set to the given default value.
     *
     * @param   string      ISO2 country code of the currently chosen output language of static_countries. Be aware that this value is passed by reference and thus could be manipulated by this method!
     * @param   string      (optional) ISO2 country code of the default output language of static_countries. Currently available: 'en' (default), 'de', 'dk'
     * @return  void
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-10-17 (code base from 2005-04-20)
     */
    public static function checkConfStaticCountriesLang(&$currentStaticCountriesLang, $defaultLang='en') {
        
        if (!in_array(strtolower($currentStaticCountriesLang), array('de', 'en', 'dk'))) {
            $currentStaticCountriesLang = $defaultLang;
            trace($currentStaticCountriesLang, 0, 'Plugin\'s staticCountriesLang SET TO DEFAULT');
        }
        
    }
    
    
    
    /***************************************************************************
     *  'STATIC COUNTRIES' PRESENTATION METHODS
     **************************************************************************/
    
    /**
     * Returns the HTML options for a HTML pulldown selectorbox of all countries
     *
     * @param   string      (optional) ISO2 country code of the language to return the country names. Currently available: 'en' (default), 'de', 'dk'
     * @param   string      (optional) ISO2 country code of the country to preselect in selectorbox
     * @param   string      (optional) label for descriptive non-selectable first option (if not passed, there will be no non-selectable first option)
     * @return  string      HTML options for a HTML pulldown selectorbox of all countries
     * @global  
     * @throws  tx_pttools_exception   if no countries are found
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-14-07
     */
    public function generateCountriesOptionsHTML($iso2ccLang='en', $selectedCountryIso2ccCode='', $noSelectionLabel='') {
         
        $options = '';
            
        // get countries and throw exception if no countries are found
        $countriesArr = self::selectCountries($iso2ccLang); 
        if (empty($countriesArr)) {
            throw new tx_pttools_exception('No countries found for selectorbox', 3);
        }
        
        // create descriptive non-selectable first option (if passed by param only)
        if (!empty($noSelectionLabel)) {
            $options = '<option value="">['.$noSelectionLabel.']</option>'.chr(10);
        }
                        
        for ($i=0; $i<sizeOf($countriesArr); $i++) {
            $options .= '<option value="'.$countriesArr[$i]['cn_iso_2'].'"';
            $options .= (strtoupper($countriesArr[$i]['cn_iso_2']) == strtoupper($selectedCountryIso2ccCode) ? ' selected="selected">' : '>');
            $options .= $countriesArr[$i]['cn_short'];
            $options .= '</option>'.chr(10);
        }
        
        return $options;
        
    }
    
    
    
} // end class



/*******************************************************************************
 *   TYPO3 XCLASS INCLUSION (for class extension/overriding)
 ******************************************************************************/
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/staticlib/class.tx_pttools_staticInfoTables.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/staticlib/class.tx_pttools_staticInfoTables.php']);
}

?>
