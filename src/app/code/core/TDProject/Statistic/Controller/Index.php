<?php

/**
 * TDProject_Statistic_Controller_Index
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
require_once 'TDProject/Statistic/Block/Overview.php';

/**
 * @category   	TDProject
 * @package    	TDProject_Statistic
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 * 				Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Statistic_Controller_Index
    extends TDProject_Statistic_Controller_Abstract {

	/**
	 * The key for the ActionForward to the statistic overview.
	 * @var string
	 */
	const STATISTIC_OVERVIEW = "StatisticOverview";

	/**
	 * This method is automatically invoked by the controller and implements
	 * the functionality to load a list with with an user performance
	 * overview.
	 *
	 * @return void
	 */
	function __defaultAction()
	{
		// go to the standard page
		return $this->_findForward(
		    TDProject_Statistic_Controller_Index::STATISTIC_OVERVIEW
		);
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