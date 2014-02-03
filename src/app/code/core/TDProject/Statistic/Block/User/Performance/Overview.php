<?php

/**
 * TDProject_Statistic_Block_User_Performance_Overview
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TDProject/Core/Block/Widget/Form/Abstract/Overview.php';
require_once 'TDProject/Core/Block/Widget/Grid.php';
require_once 'TDProject/Core/Block/Widget/Grid/Column.php';
require_once 'TDProject/Core/Block/Widget/Grid/Column/Sparklines.php';
require_once 'TDProject/Core/Block/Widget/Grid/Column/Mapping.php';
require_once 'TDProject/Core/Block/Widget/Grid/Column/Actions.php';
require_once 'TDProject/Core/Block/Widget/Grid/Column/Actions/View.php';
require_once 'TDProject/Statistic/Block/Widget/Button/Reorg.php';

/**
 * @category    TDProject
 * @package     TDProject_Core
 * @copyright   Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license     http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Statistic_Block_User_Performance_Overview
	extends TDProject_Core_Block_Widget_Form_Abstract_Overview {

	/**
	 * Return URL to invoke the reorganization.
	 *
	 * @return string The reorganization URL
	 */
	public function getReorgUrl()
	{
        return $this->getUrl(
            array(
            	'path' => '/user/performance',
            	// 'method' => 'reorg'
            )
        );
	}

    /**
     * (non-PHPdoc)
     * @see TDProject_Core_Interfaces_Block_Widget_Form_Overview::prepareGrid()
     */
    public function prepareGrid()
    {
		// add the toolbar's default buttons
    	$this->getToolbar()
			->addButton(
				new TDProject_Statistic_Block_Widget_Button_Reorg(
					$this,
					'Reorg'
				)
			)
			->removeButton(
			    TDProject_Core_Block_Widget_Button_New::BLOCK_NAME
			);
    	// instanciate the grid
    	$grid = new TDProject_Core_Block_Widget_Grid($this, 'grid', 'UserPerformance');
    	// set the collection with the data to render
    	$grid->setCollection($this->getCollection());
    	// add the columns
    	$grid->addColumn(
    	    new TDProject_Core_Block_Widget_Grid_Column(
    	    	'userId', 'ID', 10
    	    )
    	);
    	$grid->addColumn(
    	    new TDProject_Core_Block_Widget_Grid_Column(
    	    	'roleName', 'Role', 25
    	    )
    	);
    	$grid->addColumn(
    	    new TDProject_Core_Block_Widget_Grid_Column(
    	    	'username', 'Username', 25
    	    )
    	);
    	$grid->addColumn(
    	    new TDProject_Core_Block_Widget_Grid_Column(
    	    	'actualPerformance', 'Actual', 10
    	    )
    	);
    	$grid->addColumn(
    	    new TDProject_Core_Block_Widget_Grid_Column_Sparklines(
    	    	'userPerformance', 'Performance', 15
    	    )
    	);
    	// add the actions
    	$action = new TDProject_Core_Block_Widget_Grid_Column_Actions(
    		'actions', 'Actions', 15
    	);
    	$action->addAction(
    	    new TDProject_Core_Block_Widget_Grid_Column_Actions_View(
    	        $this->getContext(),
    	        'userId',
    	        '?path=/user/performance&method=view'
    	    )
    	);
    	$grid->addColumn($action);
    	// return the initialized instance
    	return $grid;
    }
}