<?php

/**
 * TDProject_Statistic_Common_ValueObjects_UserPerformanceOverviewData
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TechDivision/Lang/Integer.php';
require_once 'TDProject/Core/Common/ValueObjects/UserOverviewData.php';

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
class TDProject_Statistic_Common_ValueObjects_UserPerformanceOverviewData
    extends TDProject_Core_Common_ValueObjects_UserOverviewData {
    /**
     * The the user's actual performance.
     * @var array
     */
    protected $_actualPerformance = null;

    /**
     * The the user's performance.
     * @var array
     */
    protected $_performance = null;

    /**
     * The constructor intializes the DTO with the
     * values passed as parameter.
     *
     * @param TDProject_Core_Common_ValueObjects_UserOverviewData $dto
     * 		The user's DTO
     * @return void
     */
    public function __construct(
    	TDProject_Core_Common_ValueObjects_UserOverviewData $dto) {
        // call the parents constructor
        parent::__construct($dto, $dto->getDefaultRole());
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
     * Sets the user's performance.
     *
     * @param TechDivision_Lang_Integer $actualPerformance
     * 		The user's actual performance
     * @return void
     */
    public function setActualPerformance($actualPerformance)
    {
        $this->_actualPerformance = $actualPerformance;
    }

    /**
     * Returns the user's actual performance.
     *
     * @return TechDivision_Lang_Integer The user's actual performance
     */
    public function getActualPerformance()
    {
        return $this->_actualPerformance;
    }

    /**
     * Returns the user's performance as comma separated string.
     *
     * @return string The concatenated user's performance
     */
    public function getUserPerformance()
    {
        return implode(",", $this->getPerformance());
    }
}