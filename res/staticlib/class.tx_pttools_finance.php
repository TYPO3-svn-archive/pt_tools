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
 * General finance related methods library (part of the library extension 'pt_tools')
 * IMPORTANT: This class requires PHP to be configured with '--enable-bcmath'!
 *
 * $Id$
 *
 * @author      Rainer Kuhn <kuhn@punkt.de>
 * @since       2005-08-19
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 */



/**
 * Inclusion of external resources
 */
require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_debug.php'; // debugging class with trace() function



/**
 * General library class with static finance related methods
 * IMPORTANT: This class requires PHP to be configured with '--enable-bcmath'!
 *
 * @author      Rainer Kuhn <kuhn@punkt.de>
 * @since       2005-08-19
 * @package     TYPO3
 * @subpackage  tx_pttools
 */
class tx_pttools_finance  {

    /***************************************************************************
     *   CLASS CONSTANTS
     **************************************************************************/

    /*
     * (integer) maximum default precision (=number of decimal places) to use for internal BCmath calculations
     */
    const MAX_DEFAULT_PRECISION = 10;



    /***************************************************************************
     *   SECTION: TAX RELATED METHODS
     **************************************************************************/

    /**
     * Returns the net price from a given gross price and the given taxrate. IMPORTANT: This method requires PHP to be configured with '--enable-bcmath'!
     *
     * @param   double      gross price
     * @param   double      taxrate in percent (e.g. tax 16% => taxrate = 16.0)
     * @param   integer     (optional) positive integer or 0: precision to round to (=number of digits after the decimal point, default=4) OR -1: do not not round at all)
     * @return  double      net price (rounded if specified)
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-07-29
     */
    public static function getNetPriceFromGross($grossPrice, $taxrate, $precision=4) {

        // float operations may lead to precision problems (see www.php.net/float), using bcmath instead: this requires PHP to be configured with '--enable-bcmath'
        $precision = intval($precision);
        $internalPrecision = ($precision >= 0 ? ($precision + 2) : self::MAX_DEFAULT_PRECISION);

        $netPrice = bcdiv($grossPrice, bcadd('1', bcdiv($taxrate, '100', $internalPrecision), $internalPrecision), $internalPrecision); // e.g. taxrate 16.0: netPrice = grossPrice / 1,16
            // original calculation: $netPrice = $grossPrice / (1 + $taxrate / 100);  // e.g. taxrate 16.0: netPrice = grossPrice / 1,16

        if ($precision >= 0) {
            $netPrice = round($netPrice, $precision);
        }

        return (double)$netPrice;

    }

    /**
     * Returns the gross price from a given net price and the given taxrate. IMPORTANT: This method requires PHP to be configured with '--enable-bcmath'!
     *
     * @param   double      net price
     * @param   double      taxrate in percent (e.g. tax 16% => taxrate = 16.0)
     * @param   integer     (optional) positive integer or 0: precision to round to (=number of digits after the decimal point, default=4) OR -1: do not not round at all)
     * @return  double      gross price (rounded if specified)
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-07-29
     */
    public static function getGrossPriceFromNet($netPrice, $taxrate, $precision=4) {

        // float operations may lead to precision problems (see www.php.net/float), using bcmath instead: this requires PHP to be configured with '--enable-bcmath'
        $precision = intval($precision);
        $internalPrecision = ($precision >= 0 ? ($precision + 2) : self::MAX_DEFAULT_PRECISION);

        $grossPrice = bcmul($netPrice, bcadd('1', bcdiv($taxrate, '100', $internalPrecision), $internalPrecision), $internalPrecision);
            // original calculation: $grossPrice = $netPrice * (1 + $taxrate / 100); e.g. taxrate 16.0: grossPrice = netPrice * 1,16

        if ($precision >= 0) {
            $grossPrice = round($grossPrice, $precision);
        }

        return (double)$grossPrice;

    }

    /**
     * Returns the tax cost from a given net price and the given taxrate. IMPORTANT: This method requires PHP to be configured with '--enable-bcmath'!
     *
     * @param   double      net price
     * @param   double      taxrate in percent (e.g. tax 16% => taxrate = 16.0)
     * @param   integer     (optional) positive integer or 0: precision to round to (=number of digits after the decimal point, default=4) OR -1: do not not round at all)
     * @return  double      tax cost (rounded if specified)
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-08-01
     */
    public static function getTaxCostFromNet($netPrice, $taxrate, $precision=4) {

        // float operations may lead to precision problems (see www.php.net/float), using bcmath instead: this requires PHP to be configured with '--enable-bcmath'
        $precision = intval($precision);
        $internalPrecision = ($precision >= 0 ? ($precision + 2) : self::MAX_DEFAULT_PRECISION);

        $taxCost = bcmul($netPrice, bcdiv($taxrate, '100', $internalPrecision), $internalPrecision);
            // original calculation: $taxCost = $netPrice * ($taxrate/100); e.g. taxrate 16.0: taxCost = netPrice * 0,16

        if ($precision >= 0) {
            $taxCost = round($taxCost, $precision);
        }

        return (double)$taxCost;

    }

    /**
     * Returns the tax cost portion from a given gross price using the given taxrate. IMPORTANT: This method requires PHP to be configured with '--enable-bcmath'!
     *
     * @param   double      gross price
     * @param   double      taxrate in percent (e.g. tax 16% => taxrate = 16.0)
     * @param   integer     (optional) positive integer or 0: precision to round to (=number of digits after the decimal point, default=4) OR -1: do not not round at all)
     * @return  double      tax cost portion (rounded if specified)
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-08-01
     */
    public static function getTaxCostFromGross($grossPrice, $taxrate, $precision=4) {

        // float operations may lead to precision problems (see www.php.net/float), using bcmath instead: this requires PHP to be configured with '--enable-bcmath'
        $precision = intval($precision);
        $internalPrecision = ($precision >= 0 ? ($precision + 2) : self::MAX_DEFAULT_PRECISION);

        $taxPortion = bcdiv(bcmul($grossPrice, $taxrate, $internalPrecision), bcadd('100', $taxrate, $internalPrecision), $internalPrecision);  // tax = (price x taxrate) : (100 + taxrate)
            // original calculation: $taxPortion = ($grossPrice * $taxrate) / (100 + $taxrate); e.g. taxrate 16.0:  taxCost = (grossPrice * 16) : 116

        if ($precision >= 0) {
            $taxPortion = round($taxPortion, $precision);
        }

        return (double)$taxPortion;

    }

    /**
     * Returns the tax rate in percent from given gross and net prices. IMPORTANT: This method requires PHP to be configured with '--enable-bcmath'!
     *
     * @param   double      gross price
     * @param   double      net price
     * @param   integer     (optional) positive integer or 0: precission to round to (=number of digits after the decimal point, default=2) OR -1: do not not round at all.
     * @return  double      taxrate in percent (e.g. tax 16% => taxrate = 16.0)
     * @author  Dorit Rottner <rottner@punkt.de>, Rainer Kuhn <kuhn@punkt.de>
     * @since   2007-08-29
     */
    public static function getTaxRate($grossPrice, $netPrice, $precision=4) {

        // float operations may lead to precision problems (see www.php.net/float), using bcmath instead: this requires PHP to be configured with '--enable-bcmath'
        $precision = intval($precision);
        $internalPrecision = ($precision >= 0 ? ($precision + 4) : self::MAX_DEFAULT_PRECISION);

        $diff = bcsub($grossPrice, $netPrice, $internalPrecision);  // difference = (grossPrice - netPrice) / netPrice
        $tax = bcdiv($diff, $netPrice, $internalPrecision);         // tax = difference / netPrice
        $taxRate = bcmul($tax, 100, $internalPrecision);            // taxRate = tax * 100

        if ($precision >= 0) {
            $taxRate = round($taxRate, $precision);
        }

        return (double)$taxRate;

    }

    /**
     * Returns a given price as a string rounded and formatted to display with a specified number of decimal places
     *
     * @param   double      price to format
     * @param   string      optional currency to add after the price
     * @param   integer     (optional) positive integer or 0: number of decimals to round to and to display (=number of digits after the decimal point, default=2)
     * @return  string      rounded and formatted price string
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-09-20
     */
    public static function getFormattedPriceString($price, $currency='', $decimals=2) {

        $decimalpoint = '.';
        $locale_info = localeconv();

        if (isset($locale_info['mon_decimal_point']) && ($locale_info['mon_decimal_point'] != '')) {
            $decimalpoint = $locale_info['mon_decimal_point'];
        } elseif (isset($locale_info['decimal_point']) && ($locale_info['decimal_point'] != '')) {
            $decimalpoint = $locale_info['decimal_point'];
        }

        if (isset($locale_info['mon_thousands_sep'])) {
            $thousandsSeparator = $locale_info['mon_thousands_sep'];
        } elseif (isset($locale_info['thousands_sep'])) {
            $thousandsSeparator = $locale_info['thousands_sep'];
        } else {
            $thousandsSeparator = '';
        }

        // Why not using money_format here?
        $displayPrice = number_format(round($price, $decimals), $decimals, $decimalpoint, $thousandsSeparator);

        if ($currency != '') {
            if (is_object($GLOBALS['TSFE'])) {
                $displayPrice = sprintf($GLOBALS['TSFE']->tmpl->setup['config.']['pt_tools.']['currencyFormat'], $displayPrice, $currency);
            } else {
                $displayPrice = $displayPrice.' '.$currency;
            }
        }

        return $displayPrice;

    }

    /**
     * Returns a given float price value rounded down to two decimal places (e.g. 1.9999 will be returned as 1.99)
     *
     * @param   double      price to round down
     * @return  float       price value rounded down to two decimal places
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2007-07-17
     */
    public static function roundDownTwoDecimalPlaces($price) {

        $roundedDownPrice = bcdiv(floor(bcmul($price, '100', 4)), '100', 2);  // floor($price * 100) / 100

        return (double)$roundedDownPrice;

    }



} // end class



/*******************************************************************************
 *   TYPO3 XCLASS INCLUSION (for class extension/overriding)
 ******************************************************************************/
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/staticlib/class.tx_pttools_finance.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/staticlib/class.tx_pttools_finance.php']);
}

?>
