<?php

/**
 * TDProject_Statistic_Model_Services_DomainProcessor
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

/**
 * @category   	TDProject
 * @package    	TDProject_Statistic
 * @copyright  	Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    	http://opensource.org/licenses/osl-3.0.php
 * 				Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Statistic_Model_Services_DomainProcessor
    extends TDProject_Statistic_Model_Services_AbstractDomainProcessor
{

	/**
	 * This method returns the logger of the requested
	 * type for logging purposes.
	 *
     * @param string The log type to use
	 * @return TechDivision_Logger_Logger Holds the Logger object
	 * @throws Exception Is thrown if the requested logger type is not initialized or doesn't exist
	 * @deprecated 0.6.26 - 2011/12/19
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
     * @since 0.6.27 - 2011/12/19
     */
    public function getLogger(
    	$logType = TechDivision_Logger_System::LOG_TYPE_SYSTEM)
    {
    	return $this->getContainer()->getLogger();
    }

    /**
     * (non-PHPdoc)
     * @see TDProject/Statistic/Common/Delegates/Interfaces/DomainProcessorDelegate#getUserPerformanceOverviewData()
     */
	public function getUserPerformanceOverviewData()
	{
	    try {
    		// assemble and return the Collection
    		return TDProject_Statistic_Model_Assembler_User_Performance::create($this->getContainer())
    		    ->getUserPerformanceOverviewData();
	    } catch(TechDivision_Model_Interfaces_Exception $e) {
            // log the exception message
            $this->_getLogger()->error($e->__toString());
            // throw a new exception
            throw new TDProject_Core_Common_Exceptions_SystemException(
                $e->__toString()
            );
        }
    }

    /**
     * (non-PHPdoc)
     * @see TDProject/Statistic/Common/Delegates/Interfaces/DomainProcessorDelegate#getUserPerformanceViewData(TechDivision_Lang_Integer $userId)
     */
	public function getUserPerformanceViewData(
	    TechDivision_Lang_Integer $userId) {
	    try {
    		// assemble and return the Collection
    		return TDProject_Statistic_Model_Assembler_User_Performance::create($this->getContainer())
    		    ->getUserPerformanceViewData($userId);
	    } catch(TechDivision_Model_Interfaces_Exception $e) {
            // log the exception message
            $this->_getLogger()->error($e->__toString());
            // throw a new exception
            throw new TDProject_Core_Common_Exceptions_SystemException(
                $e->__toString()
            );
        }
    }

    /**
     * (non-PHPdoc)
     * @see TDProject/Statistic/Common/Delegates/Interfaces/DomainProcessorDelegate#reorgUserPerformance()
     */
	public function reorgUserPerformance()
	{
	    try {
            // start the transaction
	        $this->beginTransaction();
            // reorganize the user performance
	        TDProject_Statistic_Model_Actions_User_Performance::create($this->getContainer())
	            ->reorg();
            // commit the transaction
	        $this->commitTransaction();
	    } catch(TechDivision_Model_Interfaces_Exception $e) {
            // rollback the transaction
	        $this->rollbackTransaction();
            // log the exception message
            $this->_getLogger()->error($e->__toString());
            // throw a new exception
            throw new TDProject_Core_Common_Exceptions_SystemException(
                $e->__toString()
            );
        }
    }
}