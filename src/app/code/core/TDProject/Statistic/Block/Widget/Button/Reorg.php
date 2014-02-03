<?php

/**
 * TDProject_Statistic_Block_Widget_Button_Reorg
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

require_once 'TDProject/Core/Block/Widget/Button.php';
require_once 'TDProject/Core/Interfaces/Block/Widget/Button.php';
require_once 'TDProject/Core/Interfaces/Block/Widget/Form.php';

/**
 * @category    TDProject
 * @package     TDProject_Core
 * @copyright   Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license     http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Tim Wagner <tw@techdivision.com>
 */
class TDProject_Statistic_Block_Widget_Button_Reorg
    extends TDProject_Core_Block_Widget_Button
    implements TDProject_Core_Interfaces_Block_Widget_Button {

    /**
     * The unique block name.
     * @var string
     */
    const BLOCK_NAME = 'reorg';

    /**
     * Initialize the button with the context.
     *
     * @param string $blockTitle The button label
     * @return void
     */
    public function __construct(
        TDProject_Core_Interfaces_Block_Widget_Form $form,
        $blockTitle) {
        // call the parent constructor
        parent::__construct($form->getContext(), self::BLOCK_NAME, $blockTitle);
    	// set the icon to use
		$this->setIcon('ui-icon-document');
		// set the onClick event
		$this->setOnClick(
			'window.location="' . $form->getReorgUrl() . '"; return false;'
		);
    }
}