<?php
/**
 * DebugKitTestController
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org
 * @package       debug_kit
 * @subpackage    debug_kit.tests.test_app
 * @since         DebugKit 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 **/
class DebugKitTestController extends Controller {
	var $name = 'DebugKitTest';

	var $uses = array();

	var $components = array('DebugKit.Toolbar');

	function request_action_return() {
		$this->autoRender = false;
		return 'I am some value from requestAction.';
	}
	
	function request_action_render() {
		$this->set('test', 'I have been rendered.');
	}
	
}