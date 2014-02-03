<?php

/**
 * TDProject_Statistic_Controller_User_Performance
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TDProject/Core/Controller/Util/GlobalForwardKeys.php';
require_once 'TDProject/Statistic/Controller/Abstract.php';
require_once 'TDProject/Statistic/Controller/Util/WebRequestKeys.php';
require_once 'TDProject/Statistic/Controller/Util/WebSessionKeys.php';
require_once 'TDProject/Statistic/Controller/Util/MessageKeys.php';
require_once 'TDProject/Statistic/Controller/Util/ErrorKeys.php';
require_once 'TDProject/Statistic/Block/User/Performance/View.php';
require_once 'TDProject/Statistic/Block/User/Performance/Overview.php';

/**
 * @category   	TDProject
 * @package    	TDProject_Statistic
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 * 				Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Statistic_Controller_User_Performance
    extends TDProject_Statistic_Controller_Abstract {

	/**
	 * The key for the ActionForward to the user performance overview.
	 * @var string
	 */
	const USER_PERFORMANCE_OVERVIEW = "UserPerformanceOverview";

	/**
	 * The key for the ActionForward to the user performance view.
	 * @var string
	 */
	const USER_PERFORMANCE_VIEW = "UserPerformanceView";

	/**
	 * This method is automatically invoked by the controller and implements
	 * the functionality to load a list with with an user performance
	 * overview.
	 *
	 * @return void
	 */
	public function __defaultAction()
	{
		try {
			// replace the default ActionForm
			$this->getContext()->setActionForm(
				new TDProject_Statistic_Block_User_Performance_Overview(
				    $this->getContext()
				)
			);
            // load and register the user overview data
            $this->_getRequest()->setAttribute(
            	TDProject_Statistic_Controller_Util_WebRequestKeys::OVERVIEW_DATA,
            	$this->_getDelegate()->getUserPerformanceOverviewData()
            );
		} catch(Exception $e) {
			// create and add and save the error
			$errors = new TechDivision_Controller_Action_Errors();
			$errors->addActionError(new TechDivision_Controller_Action_Error(
                TDProject_Statistic_Controller_Util_ErrorKeys::SYSTEM_ERROR,
                $e->__toString())
            );
			// adding the errors container to the Request
			$this->_saveActionErrors($errors);
			// set the ActionForward in the Context
			return $this->_findForward(
			    TDProject_Core_Controller_Util_GlobalForwardKeys::SYSTEM_ERROR
			);
		}
		// go to the standard page
		return $this->_findForward(
		    TDProject_Statistic_Controller_User_Performance
		        ::USER_PERFORMANCE_OVERVIEW
		);
	}

	/**
	 * This method is automatically invoked by the controller and implements the
	 * functionality to edit the report with the ID passed as Request parameter.
	 *
	 * @return void
	 */
	public function viewAction()
	{
		try {
            // load the user ID from the request
            $userId = $this->_getRequest()->getParameter(
                TDProject_Statistic_Controller_Util_WebRequestKeys::USER_ID,
                FILTER_VALIDATE_INT
            );
            // initialize the ActionForm with the data from the DTO
            $this->_getActionForm()->populate(
                $dto = $this->_getDelegate()->getUserPerformanceViewData(
                    TechDivision_Lang_Integer::valueOf(
                        new TechDivision_Lang_String($userId)
                    )
                )
            );
		} catch(Exception $e) {
			// create and add and save the error
			$errors = new TechDivision_Controller_Action_Errors();
			$errors->addActionError(new TechDivision_Controller_Action_Error(
                TDProject_Report_Controller_Util_ErrorKeys::SYSTEM_ERROR,
                $e->__toString())
            );
			// adding the errors container to the Request
			$this->_saveActionErrors($errors);
			// set the ActionForward in the Context
			return $this->_findForward(
			    TDProject_Core_Controller_Util_GlobalForwardKeys::SYSTEM_ERROR
			);
		}
		// go to the user performance edit page
		return $this->_findForward(
		    TDProject_Statistic_Controller_User_Performance
		        ::USER_PERFORMANCE_VIEW
		);
	}

	/**
	 * This method is automatically invoked by the controller and implements
	 * the functionality to the performance of all users.
	 *
	 * @return void
	 */
	function reorgAction()
	{
		try {
            // reorganize the users performance
            $this->_getDelegate()->reorgUserPerformance();
		} catch(Exception $e) {
			// create and add and save the error
			$errors = new TechDivision_Controller_Action_Errors();
			$errors->addActionError(new TechDivision_Controller_Action_Error(
                TDProject_Statistic_Controller_Util_ErrorKeys::SYSTEM_ERROR,
                $e->__toString())
            );
			// adding the errors container to the Request
			$this->_saveActionErrors($errors);
			// set the ActionForward in the Context
			return $this->_findForward(
			    TDProject_Core_Controller_Util_GlobalForwardKeys::SYSTEM_ERROR
			);
		}
		// go to the user performance overview
        return $this->__defaultAction();
	}

	/**
	 * Tries to load the Block class specified as path parameter
	 * in the ActionForward. If a Block was found and the class
	 * can be instanciated, the Block was registered to the Request
	 * with the path as key.
	 *
	 * @param TechDivision_Controller_Action_Forward $actionForward
	 * 		The ActionForward to initialize the Block for
	 * @return void
	 */
	protected function _getBlock(
	    TechDivision_Controller_Action_Forward $actionForward) {
	    // check if the class required to initialize the Block is included
	    if (!class_exists($path = $actionForward->getPath())) {
	        return;
	    }
	    // initialize the page and add the Block
	    $page = new TDProject_Core_Block_Page($this->getContext());
	    $page->setPageTitle($this->_getPageTitle());
	    $page->addBlock($this->getContext()->getActionForm());
	    // register the Block in the Request
	    $this->_getRequest()->setAttribute($path, $page);
	}
}