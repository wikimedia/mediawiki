<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
//  
//  Copyright (c) 2003 Laurent Bedubourg
//  
//  This library is free software; you can redistribute it and/or
//  modify it under the terms of the GNU Lesser General Public
//  License as published by the Free Software Foundation; either
//  version 2.1 of the License, or (at your option) any later version.
//  
//  This library is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
//  Lesser General Public License for more details.
//  
//  You should have received a copy of the GNU Lesser General Public
//  License along with this library; if not, write to the Free Software
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
//  
//  Authors: Laurent Bedubourg <laurent.bedubourg@free.fr>
//  
// $id$

/**
 * Generic date part format.
 */
define('TYPES_DATE_FMT_DATE', '%1$04s-%2$02s-%3$02s');
/**
 * French date part format.
 */
define('TYPES_DATE_FMT_DATE_FR', '%3$02s/%2$02s/%1$04s');
/**
 * Hours format.
 */
define('TYPES_DATA_FMT_HOUR', '%4$02s:%5$02s:%6$02s');

/**
 * Simple date manipulation object.
 *
 * Note:
 * -----
 * 
 * ODate::toString() can take a sprintf() format string as first parameter. 
 *
 * @author Laurent Bedubourg <laurent.bedubourg@free.fr>
 */
class ODate 
{
    var $_year = 0;
    var $_month = 0;
    var $_day = 0;
    var $_hours = 0;
    var $_minutes = 0;
    var $_seconds = 0;
    
    /**
     * ODate constructor.
     *
     * This constructor is for php v4.4 compliance.
     *
     * @param int $y  optional -- year 
     * @param int $mt optional -- month
     * @param int $d  optional -- day
     * @param int $h  optional -- hour
     * @param int $m  optional -- minutes
     * @param int $s  optional -- seconds
     */
    function __construct($y=0, $mt=0, $d=0, 
                         $h=0, $m=0, $s=0) 
    {
        $this->setYear($y);
        $this->setMonth($mt);
        $this->setDay($d);
        $this->setHours($h);
        $this->setMinutes($m);
        $this->setSeconds($s);
    }
    
    /**
     * ODate constructor.
     *
     * @param int $y  optional -- year
     * @param int $mt optional -- month
     * @param int $d  optional -- day
     * @param int $h  optional -- hour
     * @param int $m  optional -- minutes
     * @param int $s  optional -- seconds
     */
    function ODate($y=0, $mt=0, $d=0, 
                  $h=0, $m=0, $s=0) 
    {
        $this->__construct($y, $mt, $d, $h, $m, $s);
    }

    /**
     * Add a date to current date.
     */
    function add($date)
    {
        $this->inc($date->getYear(),
                   $date->getMonth(),
                   $date->getDay(),
                   $date->getHours(),
                   $date->getMinutes(),
                   $date->getSeconds());
    }

    /**
     * Add values to this date.
     * 
     * @param int $y  optional -- year
     * @param int $mt optional -- month
     * @param int $d  optional -- day
     * @param int $h  optional -- hour
     * @param int $m  optional -- minutes
     * @param int $s  optional -- seconds
     */
    function inc($y,$mt,$d,$h,$m,$s)
    {
        $this->addSeconds($s);
        $this->addMinutes($m);        
        $this->addHours($h);
        $this->addDays($d);        
        $this->addMonths($mt);
        $this->addYears($y);
    }

    /**
     * Set date part.
     *
     * @param int $y  -- year
     * @param int $mt -- month
     * @param int $d  -- day
     */
    function setDate($y,$m,$d)
    { 
        $this->setYear($y);
        $this->setMonth($m);
        $this->setDay($d);
    }
    
    /**
     * Set time part.
     * 
     * @param int $h  -- hour
     * @param int $m  -- minutes
     * @param int $s  -- seconds
     */
    function setTime($y,$m,$d)
    {
        $this->setHours($h);
        $this->setMinutes($m);
        $this->setSeconds($s);
    }  

    /**
     * Add some years to this date.
     * 
     * @param int $y  -- year
     */
    function addYears($y)
    {
        $this->_year += $y; 
    }

    /**
     * Add some months to this date.
     *
     * @param int $m  -- month
     */
    function addMonths($m)
    {
        $this->_month += $m;
        if ($this->_month > 12) {
            $this->addYears($this->_month / 12);
            $this->_month = $this->_month % 12;
        }
    }

    /**
     * Add some days to this date.
     *
     * @param int $d  -- days
     */
    function addDays($d)
    {
        $m = 0;
        $this->_day += $d;
        $m = $this->numberOfMonthDays();
        while ($this->_day > $m) {
            $this->addMonths(1);
            $this->_day -= $m;
            $m = $this->numberOfMonthDays();
        }
    }

    /**
     * Add some hours to this date.
     *
     * @param int $h  -- hours
     */
    function addHours($h)
    {
        $this->_hours += $h;
        if ($this->_hours >= 24) {
            $this->addDays($this->_hours / 24);
            $this->_hours = $this->_hours % 24;
        }
    }

    /**
     * Add minutes to this date.
     *
     * @param int $m  -- minutes
     */
    function addMinutes($m)
    {
        $this->_minutes += $m;
        if ($this->_minutes >= 60) {
            $this->addHours($this->_minutes / 60);
            $this->_minutes = $this->_minutes % 60;
        }
    }

    /**
     * Add seconds to this date.
     *
     * @param int $s  -- seconds
     */
    function addSeconds($s)
    {
        $this->_seconds += $s;
        if ($this->_seconds >= 60) {
            $this->addMinutes($this->_seconds / 60);
            $this->_seconds = $this->_seconds % 60;
        }
    }
    
    /**
     * Return true if bissextile year.
     *
     * @return boolean
     */
    function isBissextile()
    {
        if ($this->_year % 400 == 0) return true;
        if ($this->_year % 100 == 0) return false;
        if ($this->_year % 4 == 0)   return true;
        return false;
    }

    /**
     * Get the number of days in current month
     *
     * @return int
     */
    function numberOfMonthDays()
    {
        if ($this->_month == 2 && $this->isBissextile()) {
            return 29;
        }
        $DOM = array(-1, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        return $DOM[$this->_month];
    }

    /**
     * Set now time values to this object.
     */
    function setNow()
    {
        $d = getdate();
        $this->setSeconds($d['seconds']);
        $this->setMinutes($d['minutes']);
        $this->setHours($d['hours']);
        $this->setDay($d['mday']);
        $this->setMonth($d['mon']);
        $this->setYear($d['year']);
    }
    
    /**
     * Ensure that date is correct.
     */
    function cleanup()
    {
        $this->add(0,0,0,0,0,0);
    }
    
    /**
     * Compare with another date, return < 0 if less, == 0 if equals, > 0 if
     * greater.
     *
     * @param ODate $date -- date to compare with
     *
     * @return int (strcmp)
     */
    function compare($date)
    {
        return strcmp($this->toNumericString(), 
                      $date->toNumericString());
    }

    /**
     * Return a concatenation of date fields (numeric date).
     *
     * @return string
     */
    function toNumericString()
    {
        return (int)"$this->_year$this->_month$this->_day".
            "$this->_hours$this->_minutes$this->_seconds";
    }

    /**
     * Simple date format.
     *
     * @param string $format optional
     *        Format to pass to sprintf (ex:"%04d-%02d-%02d %02d:%02d:%02d")
     *
     * @return string
     */
    function toString($format="%04d-%02d-%02d %02d:%02d:%02d")
    {
        $this->cleanup();
        return sprintf($format,
                       $this->_year,
                       $this->_month,
                       $this->_day,
                       $this->_hours,
                       $this->_minutes,
                       $this->_seconds);
    }

    
    // 
    // OTHER GETTER SETTERS
    // 

    /**
     * Retrieve year.
     * @return int
     */
    function getYear()
    { 
        return $this->_year; 
    }
    
    /**
     * Retrieve month.
     * @return int
     */
    function getMonth()
    { 
        return $this->_month; 
    }
    
    /**
     * Retrieve day.
     * @return int
     */
    function getDay()
    { 
        return $this->_day; 
    }
    
    /**
     * Retrieve hour.
     * @return int
     */
    function getHours()
    { 
        return $this->_hours; 
    }
    
    /**
     * Retrieve minutes.
     * @return int
     */
    function getMinutes()
    {
        return $this->_minutes; 
    }
    
    /**
     * Retrieve seconds.
     * @return int
     */
    function getSeconds()
    { 
        return $this->_seconds; 
    }
    
    /**
     * Set year.
     * @param int $y -- year
     */
    function setYear($y)
    { 
        $this->_year = $y; 
    }
    
    /**
     * Set month.
     * @param int $m -- month
     */
    function setMonth($m)
    { 
        $this->_month = $m; 
    }
    
    /**
     * Set day.
     * @param int $d -- day
     */
    function setDay($d)
    { 
        $this->_day = $d; 
    }
    
    /**
     * Set hours.
     * @param int $h -- hours
     */
    function setHours($h)
    { 
        $this->_hours = $h; 
    }
    
    /**
     * Set minutes.
     * @param int $m -- minutes
     */
    function setMinutes($m)
    { 
        $this->_minutes = $m; 
    }
    
    /**
     * Set seconds.
     * @param int $s -- seconds
     */
    function setSeconds($s)
    { 
        $this->_seconds = $s; 
    }
}

?>
