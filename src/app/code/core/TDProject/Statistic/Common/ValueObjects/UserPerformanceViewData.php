<?php

/**
 * TDProject_Statistic_Common_ValueObjects_UserPerformanceViewData
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TechDivision/Lang/Integer.php';
require_once 'TDProject/Core/Common/ValueObjects/UserValue.php';

/**
 * This class is the data transfer object between the model
 * and the controller for the table user performance.
 *
 * @category   	TDProject
 * @package     TDProject_Statistic
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 * 				Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Statistic_Common_ValueObjects_UserPerformanceViewData
    extends TDProject_Core_Common_ValueObjects_UserValue {

    /**
     * The the user's performance.
     * @var array
     */
    protected $_performance = array();

    /**
     * The the user's billable hours.
     * @var array
     */
    protected $_billableHours = array();

    /**
     * The the user's costs.
     * @var array
     */
    protected $_costs = array();

    /**
     * The the user's turnover.
     * @var array
     */
    protected $_turnover = array();

    /**
     * The constructor intializes the DTO with the
     * values passed as parameter.
     *
     * @param TDProject_Core_Common_ValueObjects_UserValue $vo
     * 		The user's VO
     * @return void
     */
    public function __construct(
    	TDProject_Core_Common_ValueObjects_UserValue $vo) {
        // call the parents constructor
        parent::__construct($vo);
    }

    /**
     * Sets the user's billable hours.
     *
     * @param array $billableHours
     * 		The array with the user's billable hours
     * @return void
     */
    public function setBillableHours(array $billableHours)
    {
        $this->_billableHours = $billableHours;
    }

    /**
     * Returns an array with the user's billable hours.
     *
     * @return array The user's billable hours as array
     */
    public function getBillableHours()
    {
        return $this->_billableHours;
    }

    /**
     * Sets the user's performance.
     *
     * @param array $performance
     * 		The array with the user's performance
     * @return void
     */
    public function setPerformance(array $performance)
    {
        $this->_performance = $performance;
    }

    /**
     * Returns an array with the user's performance.
     *
     * @return array The user's performance as array
     */
    public function getPerformance()
    {
        return $this->_performance;
    }

    /**
     * Sets the user's costs.
     *
     * @param array $costs
     * 		The array with the user's costs
     * @return void
     */
    public function setCosts(array $costs)
    {
        $this->_costs = $costs;
    }

    /**
     * Returns an array with the user's costs.
     *
     * @return array The user's costs as array
     */
    public function getCosts()
    {
        return $this->_costs;
    }

    /**
     * Sets the user's turnover.
     *
     * @param array $turnover
     * 		The array with the user's turnover
     * @return void
     */
    public function setTurnover(array $turnover)
    {
        $this->_turnover = $turnover;
    }

    /**
     * Returns an array with the user's turnover.
     *
     * @return array The user's turnover as array
     */
    public function getTurnover()
    {
        return $this->_turnover;
    }

    /**
     * Returns the user's performance concatenated
     * for usage with jqPlot.
     *
     * @return TechDivision_Lang_String The user's performance
     */
    public function getUserPerformance()
    {
        // initialize the array for the values
        $graph = array();
        // append the values
        foreach ($this->getPerformance() as $key => $performance) {
            $graph[] = "['$key',$performance]";
        }
        // concatenate and return the string
        return new TechDivision_Lang_String("[[" . implode(",", $graph) . "]]");
    }

    /**
     * Returns the user's billable hours concatenated
     * for usage with jqPlot.
     *
     * @return TechDivision_Lang_String The user's billable hours
     */
    public function getUserBillableHours()
    {
        // initialize the array for the values
        $graph = array();
        // append the values
        foreach ($this->getBillableHours() as $key => $billableHour) {
            $graph[] = "['$key',$billableHour]";
        }
        // concatenate and return the string
        return new TechDivision_Lang_String("[[" . implode(",", $graph) . "]]");
    }

    /**
     * Returns the user's costs concatenated
     * for usage with jqPlot.
     *
     * @return TechDivision_Lang_String The user's costs
     */
    public function getUserCosts()
    {
        // initialize the array for the values
        $graph = array();
        // append the values
        foreach ($this->getCosts() as $key => $costs) {
            $graph[] = "['$key',$costs]";
        }
        // concatenate and return the string
        return new TechDivision_Lang_String("[[" . implode(",", $graph) . "]]");
    }

    /**
     * Returns the user's turnover concatenated
     * for usage with jqPlot.
     *
     * @return TechDivision_Lang_String The user's turnover
     */
    public function getUserTurnover()
    {
        // initialize the array for the values
        $graph = array();
        // append the values
        foreach ($this->getTurnover() as $key => $turnover) {
            $graph[] = "['$key',$turnover]";
        }
        // concatenate and return the string
        return new TechDivision_Lang_String("[[" . implode(",", $graph) . "]]");
    }
}