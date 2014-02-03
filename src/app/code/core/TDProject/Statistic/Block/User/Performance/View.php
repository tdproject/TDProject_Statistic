<?php

/**
 * TDProject_Statistic_Block_User_Performance_View
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TechDivision/Lang/String.php';
require_once 'TDProject/Core/Block/Widget/Button/Save.php';
require_once 'TDProject/Core/Block/Widget/Button/Delete.php';
require_once 'TDProject/Core/Block/Widget/Form/Abstract/View.php';
require_once
	'TDProject/Statistic/Common/ValueObjects/UserPerformanceViewData.php';

/**
 * @category    TDProject
 * @package     TDProject_Statistic
 * @copyright   Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license     http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Statistic_Block_User_Performance_View
	extends TDProject_Core_Block_Widget_Form_Abstract_View {

	/**
	 * Contains the user's performance.
	 * @var TechDivision_Lang_String
	 */
	protected $_userPerformance = null;

	/**
	 * Contains the user's billable hours.
	 * @var TechDivision_Lang_String
	 */
	protected $_userBillableHours = null;

	/**
	 * Contains the user's costs.
	 * @var TechDivision_Lang_String
	 */
	protected $_userCosts = null;

	/**
	 * Contains the user's turnover.
	 * @var TechDivision_Lang_String
	 */
	protected $_userTurnover = null;

	/**
	 * (non-PHPdoc)
	 * @see TDProject_Statistic_Block_Abstract_UserPerformance::getDeleteUrl()
	 */
    public function getDeleteUrl()
    {
        return '';
    }

    /**
     * (non-PHPdoc)
     * @see TDProject_Core_Block_Abstract::reset()
     */
	public function reset()
	{
		return;
	}

	/**
	 * Populates the form with the data of the
	 * passed LVO.
	 *
	 * @param TDProject_Statistic_Common_ValueObjects_UserPerformanceViewData $vo
	 * 		The VO to populate the form with
	 * @return TDProject_Statistic_Block_Abstract_UserPerformance
	 * 		The instance itself
	 */
	public function populate(
	    TDProject_Statistic_Common_ValueObjects_UserPerformanceViewData $vo) {
        // initialize the data
	    $this->setUserPerformance($vo->getUserPerformance());
	    $this->setUserBillableHours($vo->getUserBillableHours());
	    $this->setUserCosts($vo->getUserCosts());
	    $this->setUserTurnover($vo->getUserTurnover());
	    // return the instance itself
		return $this;
	}

	/**
	 * Initializes a new LVO with the data from
	 * the form and returns it.
	 *
	 * @return TDProject_Statistic_Common_ValueObjects_UserPerformanceLightValue
	 * 		The LVO initialized with the data of the form
	 */
	public function repopulate() {
		return new TDProject_Statistic_Common_ValueObjects_UserPerformanceLightValue();
	}

	/**
	 * Sets the user's performance.
	 *
	 * @param TechDivision_Lang_String $userPerformance
	 * 		The user's performance
	 */
	public function setUserPerformance(
	    TechDivision_Lang_String $userPerformance) {
	    $this->_userPerformance = $userPerformance;
	}

	/**
	 * Returns the user's performance.
	 *
	 * @return TechDivision_Lang_String
	 * 		The user's performance
	 */
	public function getUserPerformance()
	{
	    return $this->_userPerformance;
	}

	/**
	 * Sets the user's billable hours.
	 *
	 * @param TechDivision_Lang_String $userBillableHours
	 * 		The user's billable hours
	 */
	public function setUserBillableHours(
	    TechDivision_Lang_String $userBillableHours) {
	    $this->_userBillableHours = $userBillableHours;
	}

	/**
	 * Returns the user's billable hours.
	 *
	 * @return TechDivision_Lang_String
	 * 		The user's billable hours
	 */
	public function getUserBillableHours()
	{
	    return $this->_userBillableHours;
	}

	/**
	 * Sets the user's costs.
	 *
	 * @param TechDivision_Lang_String $userCosts
	 * 		The user's costs
	 */
	public function setUserCosts(
	    TechDivision_Lang_String $userCosts) {
	    $this->_userCosts = $userCosts;
	}

	/**
	 * Returns the user's costs.
	 *
	 * @return TechDivision_Lang_String
	 * 		The user's costs
	 */
	public function getUserCosts()
	{
	    return $this->_userCosts;
	}

	/**
	 * Sets the user's turnover.
	 *
	 * @param TechDivision_Lang_String $userTurnover
	 * 		The user's turnover
	 */
	public function setUserTurnover(
	    TechDivision_Lang_String $userTurnover) {
	    $this->_userTurnover = $userTurnover;
	}

	/**
	 * Returns the user's turnover.
	 *
	 * @return TechDivision_Lang_String
	 * 		The user's turnover
	 */
	public function getUserTurnover()
	{
	    return $this->_userTurnover;
	}

	/**
     * (non-PHPdoc)
     * @see TDProject/Interfaces/Block#prepareLayout()
     */
    public function prepareLayout()
    {
        // call the parent layout
        parent::prepareLayout();
        // remove unnecessary buttons from the toolbar
        $this->getToolbar()
            ->removeButton(
                TDProject_Core_Block_Widget_Button_Delete::BLOCK_NAME
            )
            ->removeButton(
                TDProject_Core_Block_Widget_Button_Save::BLOCK_NAME
            );
    	// initialize the tabs
    	$tabs = $this->addTabs('tabsUserStatistic', 'Tabs');
        // add the tab for the user data
        $tabs->addTab(
        	'user', 'User'
        )
    	->addFieldset(
    		'performance', 'Performance'
    	)
        ->addElement(
            $this->getElement(
            	'graph', 'userPerformance', 'Performance'
            )
        )
        ->addElement(
            $this->getElement(
            	'graph', 'userBillableHours', 'Billable Hours'
            )
        )
        ->addElement(
            $this->getElement(
            	'graph', 'userCosts', 'Costs'
            )
        )
        ->addElement(
            $this->getElement(
            	'graph', 'userTurnover', 'Turnover'
            )
        );
	    // return the instance itself
	    return $this;
    }
}