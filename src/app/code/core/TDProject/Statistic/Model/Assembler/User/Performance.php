<?php

/**
 * TDProject_Statistic_Model_Assembler_User_Performance
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

/**
 * @category   	TDProject
 * @package    	TDProject_Core
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 * 				Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Statistic_Model_Assembler_User_Performance
    extends TDProject_Core_Model_Assembler_Abstract {

    /**
     * Factory method to create a new instance.
     *
     * @param TechDivision_Model_Interfaces_Container $container The container instance
     * @return TDProject_Channel_Model_Actions_Category
     * 		The requested instance
     */
    public static function create(TechDivision_Model_Interfaces_Container $container)
    {
        return new TDProject_Statistic_Model_Assembler_User_Performance($container);
    }

    /**
     * Returns an ArrayList with performances of the user with the passed ID,
     * assembled as DTO.
     *
     * @return TDProject_Statistic_Common_ValueObjects_UserPerformanceViewData
     * 		The requested DTO with the user's performance data
     */
    public function getUserPerformanceViewData(
        TechDivision_Lang_Integer $userId) {
        // load the users to calculate the performance
        $user = TDProject_Core_Model_Utils_UserUtil::getHome($this->getContainer())
            ->findByPrimaryKey($userId);
        // initialize the DTO
        $dto =
            new TDProject_Statistic_Common_ValueObjects_UserPerformanceViewData(
                $user
            );
        // initialize and return the DTO
        return $this->_getUserPerformanceViewData($dto);
    }

    /**
     * Returns an ArrayList with all users performances,
     * assembled as DTO's.
     *
     * @return TechDivision_Collections_ArrayList
     * 		The requested user DTO's
     */
    public function getUserPerformanceOverviewData()
    {
        // initialize a new ArrayList
        $list = new TechDivision_Collections_ArrayList();
        // load the users to calculate the performance
        $users = TDProject_Core_Model_Assembler_User::create($this->getContainer())
            ->getUserOverviewData();
        // initialize the local home to load the users performance
        $home = TDProject_Statistic_Model_Utils_UserPerformanceUtil::getHome($this->getContainer());
        // iterate over all users
        foreach ($users as $user) {
            // initialize the DTO
            $dto =
                new TDProject_Statistic_Common_ValueObjects_UserPerformanceOverviewData(
                    $user
                );
            // load all user performances
            $userPerformances = $home->findAllByUserIdFk($user->getUserId());
            // intialize the array with the available user performances
            $performance = array();
            // assemble the array with the user's performances
            foreach ($userPerformances as $userPerformance) {
                $performance[$userPerformance->getMonth()->intValue()] =
                    $userPerformance->getPerformance()->intValue();
            }
            // set the user's performance
            $dto->setPerformance($performance);
            $dto->setActualPerformance(
                TDProject_Project_Model_Assembler_Logging::create($this->getContainer())
                    ->getPerformanceThisMonth($user->getUserId())
            );
            // add the DTO to the ArrayList
            $list->add($dto);
        }
        // return the ArrayList with the UserPerformanceOverviewData
        return $list;
    }

    /**
     * Creates and sets the performance for the passed user.
     *
     * @param TDProject_Statistic_Common_ValueObjects_UserPerformanceViewData $user
     * 		The user to calculate and append the performance for
     * @return void
     */
    protected function _getUserPerformanceViewData(
        TDProject_Statistic_Common_ValueObjects_UserPerformanceViewData $dto) {
        // intialize the array with the available user performances
        $performance = array();
        $costs = array();
        $turnover = array();
        $billableHours = array();
        // load all user performances
        $userPerformances = TDProject_Statistic_Model_Utils_UserPerformanceUtil::getHome($this->getContainer())
            ->findAllByUserIdFk($dto->getUserId());
        // initialize the counter
        $counter = $userPerformances->size();
        // assemble the array with the user's performances
        foreach ($userPerformances as $userPerformance) {
            // initialize the date
            $month = $userPerformance->getMonth()->intValue();
            $year = $userPerformance->getYear()->intValue();
            // for the actual month set the complete date
            if ($userPerformances->size() == $counter++) {
                $now = Zend_Date::now()
                    ->setYear($year)
                    ->setMonth($month);
            } else {
                // for the previous, set the day to the first following month
                $now = Zend_Date::now()
                    ->setDay(1)
                    ->setYear($year)
                    ->setMonth($month + 1);
            }
            // add the performance value to the array
            $key = $now->toString();
            // append the performance data
            $performance[$key] =
                $userPerformance->getPerformance()->intValue();
            $costs[$key] =
                $userPerformance->getCosts()->intValue();
            $turnover[$key] =
                $userPerformance->getTurnover()->intValue();
            $billableHours[$key] =
                $userPerformance->getBillableHours()->intValue();
        }
        // set the user's performance data
        $dto->setPerformance($performance);
        $dto->setCosts($costs);
        $dto->setTurnover($turnover);
        $dto->setBillableHours($billableHours);
        // return the initialized DTO
        return $dto;
    }
}