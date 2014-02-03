<?php

/**
 * TDProject_Statistic_Model_Actions_User_Performance
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

/**
 * @category   	TDProject
 * @package    	TDProject_Project
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 * 				Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Statistic_Model_Actions_User_Performance
    extends TDProject_Core_Model_Actions_Abstract {

    /**
     * Factory method to create a new instance.
     *
     * @param TechDivision_Model_Interfaces_Container $container The container instance
     * @return TDProject_Channel_Model_Actions_Category
     * 		The requested instance
     */
    public static function create(TechDivision_Model_Interfaces_Container $container)
    {
        return new TDProject_Statistic_Model_Actions_User_Performance($container);
    }

	/**
	 * This method returns the logger of the requested
	 * type for logging purposes.
	 *
     * @param string The log type to use
	 * @return TechDivision_Logger_Logger Holds the Logger object
	 * @throws Exception Is thrown if the requested logger type is not initialized or doesn't exist
	 * @deprecated 0.6.15 - 2011/12/19
	 */
    protected function _getLogger(
        $logType = TechDivision_Logger_System::LOG_TYPE_SYSTEM)
    {
    	return $this->getLogger();
    }   
    
    /**
     * This method returns the logger of the requested
     * type for logging purposes.
     *
     * @param string The log type to use
     * @return TechDivision_Logger_Logger Holds the Logger object
     * @since 0.6.16 - 2011/12/19
     */
    public function getLogger(
    	$logType = TechDivision_Logger_System::LOG_TYPE_SYSTEM)
    {
    	return $this->getContainer()->getLogger();
    }

	/**
	 * Saves the passed project and returns
	 * the ID.
	 *
	 * @return void
	 */
	public function reorg()
	{
        // load all users
        $users = TDProject_Core_Model_Utils_UserUtil::getHome($this->getContainer())->findAll();
        // iterate over all found users
        foreach ($users as $user) {
            // initialize the actual year
            $year = Zend_Date::now()
                ->getYear()
                ->toValue(Zend_Date::YEAR);
            // initialize the actual month
            $month = Zend_Date::now()
                ->getMonth()
                ->toValue(Zend_Date::MONTH_SHORT);
            // iterate over the year's month
            for ($i = $month; $i > 0; $i--) {
                // initialize month start and end date
                $fromDate = Zend_Date::now()
                    ->setTime('00:00:00', 'H:m:s')
                    ->setDay(1)
                    ->setMonth($i)
                    ->setYear($year);
                // clone the from date and add exactly one month
                $untilDate = clone $fromDate;
                $untilDate->addMonth(1);
                // try to load the user's performance
                $performances =
                    TDProject_Statistic_Model_Utils_UserPerformanceUtil
                        ::getHome($this->getContainer())->findAllByUserIdFkAndYearAndMonth(
                            $userId = $user->getUserId(),
                            $y = new TechDivision_Lang_Integer($year),
                            $m = new TechDivision_Lang_Integer($i)
                        );
                // initialize the assembler to load the performance data
                $assembler =
                    TDProject_Project_Model_Assembler_Logging::create($this->getContainer());
                // calculate the performance for the period
                $performance = $assembler->getPerformanceByMonth(
                    $user->getUserId(),
                    $this->_getTimestamp($fromDate),
                    $this->_getTimestamp($untilDate)
                );
                // calculate the billable seconds for the period
                $billableSeconds = $assembler->getBillableSecondsByMonth(
                    $user->getUserId(),
                    $this->_getTimestamp($fromDate),
                    $this->_getTimestamp($untilDate)
                );
                // calculate the billable hours
                $billableHours = $this->getBillablHours($billableSeconds);
                // calculate the user's turnover
                $turnover = $this->getTurnover($user, $billableHours);
                // calculate the user's costs
                $costs = $this->getCosts($user, $turnover);
                // check if alread a user performance exists
                if ($performances->size() == 0) {
                    // if not, create the user's performance
                    $this->createUserPerformance(
                        $userId,
                        $m,
                        $y,
                        $performance,
                        $billableHours,
                        $costs,
                        $turnover
                    );
                }
                // if yes, and month is actual or previous one, update it
                elseif ($month == $i || $month - 1 == $i) {
                    // update the found user performance
                    foreach ($performances as $userPerformance) {
                        $this->updateUserPerformance(
                            $userPerformance,
                            $performance,
                            $billableHours,
                            $costs,
                            $turnover
                        );
                    }
                }
                // simple log a debug message
                else {
                    $this->_getLogger()->debug(
                        "Performance available for user $userId, $m, $y"
                    );
                }
            }
        }
	}

	/**
	 * This method creates the user performance for the
	 * user with the passed ID, month and year.
	 *
	 * @param TechDivision_Lang_Integer $userId
	 * 		The user ID to create the performance for
	 * @param TechDivision_Lang_Integer $year
	 * 		The year to create the performance
	 * @param TechDivision_Lang_Integer $month
	 * 		The month to create the performance
	 * @param TechDivision_Lang_Integer $performance
	 * 		The performance to create
	 * @param TechDivision_Lang_Integer $billableHours
	 * 		The user's billable hours to create
	 * @param TechDivision_Lang_Integer $costs
	 * 		The user's costs to create
	 * @param TechDivision_Lang_Integer $turnover
	 * 		The user's turnover to create
	 * @return TechDivision_Lang_Integer The user performance ID
	 */
	public function createUserPerformance(
	    TechDivision_Lang_Integer $userId,
	    TechDivision_Lang_Integer $month,
	    TechDivision_Lang_Integer $year,
	    TechDivision_Lang_Integer $performance,
	    TechDivision_Lang_Integer $billableHours,
	    TechDivision_Lang_Integer $costs,
	    TechDivision_Lang_Integer $turnover) {
        // create a new user performance
        $userPerformance =
            TDProject_Statistic_Model_Utils_UserPerformanceUtil::getHome($this->getContainer())
                ->epbCreate();
        $userPerformance->setUserIdFk($userId);
        $userPerformance->setMonth($month);
        $userPerformance->setYear($year);
        $userPerformance->setPerformance($performance);
        $userPerformance->setBillableHours($billableHours);
        $userPerformance->setCosts($costs);
        $userPerformance->setTurnover($turnover);
        // create the user performance and return the ID
        return $userPerformance->create();
	}

	/**
	 * This method updates the passed user performance.
	 *
	 * @param TDProject_Statistic_Model_Entities_UserPerformance
	 * 		The performanc to update
	 * @param TechDivision_Lang_Integer $performance
	 * 		The performance to update
	 * @param TechDivision_Lang_Integer $billableHours
	 * 		The user's billable hours to update
	 * @param TechDivision_Lang_Integer $costs
	 * 		The user's costs to update
	 * @param TechDivision_Lang_Integer $turnover
	 * 		The user's turnover to update
	 * @return void
	 */
	public function updateUserPerformance(
	    TDProject_Statistic_Model_Entities_UserPerformance $userPerformance,
	    TechDivision_Lang_Integer $performance,
	    TechDivision_Lang_Integer $billableHours,
	    TechDivision_Lang_Integer $costs,
	    TechDivision_Lang_Integer $turnover) {
        // update the passed user performance
        $userPerformance->setPerformance($performance);
        $userPerformance->setBillableHours($billableHours);
        $userPerformance->setTurnover($turnover);
        $userPerformance->setCosts($costs);
        $userPerformance->update();
	}

	/**
	 * Calculate the billable hours from the passed billable seconds.
	 *
	 * @param TechDivision_Lang_Integer $billableSeconds
	 * 		The billable seconds to calculate the hours for
	 * @return TechDivision_Lang_Integer
	 * 		The calculated billable hours
	 */
	public function getBillablHours(TechDivision_Lang_Integer $billableSeconds)
	{
        return new TechDivision_Lang_Integer(
            (int) round($billableSeconds->intValue() / 3600)
        );
	}

	/**
	 * Calculate the costs for the passed user.
	 *
	 * @param TDProject_Core_Model_Entities_User $user
	 * @param TechDivision_Lang_Integer $turnover
	 * @return TechDivision_Lang_Integer The costs for the user
	 */
	public function getCosts(
	    TDProject_Core_Model_Entities_User $user,
	    TechDivision_Lang_Integer $turnover) {
        // intialize the rate and the contracted hours
        $rate = $user->getRate()->intValue();
        $contractedHours = $user->getContractedHours()->intValue();
        // calculate the costs
        $costs = (int) (($rate/100 * $contractedHours) - $turnover->intValue());
        // costs can not be lower than 0
        if ($costs < 0) {
            $costs = 0;
        }
        // return the costs
        return new TechDivision_Lang_Integer($costs);
	}

	/**
	 * Calculate the turnover for the passed user.
	 *
	 * @param TDProject_Core_Model_Entities_User $user
	 * 		The user to calculate the turnover for
	 * @param TechDivision_Lang_Integer $billableHours
	 * 		The user's billable hours to calculate the turnover for
	 * @return The user's turnover for the billable hours
	 */
	public function getTurnover(
	    TDProject_Core_Model_Entities_User $user,
	    TechDivision_Lang_Integer $billableHours) {
        return new TechDivision_Lang_Integer(
            (int) (105 * $billableHours->intValue())
        );
	}

    /**
     * Returns the timestamp representation for the passed
     * date as Integer.
     *
     * @param Zend_Date $date
     * 		The date to return the timestamp representation
     * @return TechDivision_Lang_Integer
     * 		The Integer representation of the timestamp
     */
    protected function _getTimestamp(Zend_Date $date)
    {
        return TechDivision_Lang_Integer::valueOf(
            new TechDivision_Lang_String(
                $date->getTimestamp()
            )
        );
    }
}