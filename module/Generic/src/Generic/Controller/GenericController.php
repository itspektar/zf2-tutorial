<?php

namespace Generic\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class GenericController extends AbstractActionController
{
	public function indexAction()
	{
		$message = 'Hi';
		return array('message' => $message);
	}
}
