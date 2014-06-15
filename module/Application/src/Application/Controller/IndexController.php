<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Math\Rand;
use Zend\Console\Request as ConsoleRequest;
use Zend\Validator\Date as ValidatorDate;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }

	public function resetpasswordAction()
	{
		$request = $this->getRequest();
		// Make sure that we are running in a console and the user has not tricked our
		// application into running this action from a public web server.

		if (!$request instanceof ConsoleRequest) {
				throw new \RuntimeException('You can only use this action from a console!');
		}

		// Get user email from console and check if the user used --verbose or -v flag
		$userEmail = $request->getParam('userEmail');
		$verbose = $request->getParam('verbose') || $request->getParam('v');

		// reset new password
		$newPassword = Rand::getString(16);

		// Fetch the user and change his password, then email him ...
		// [...]

		if (!$verbose) {
			return "Done! $userEmail has received an email with his new password.\n";
		} else {
			return "Done! New password for user $userEmail is '$newPassword'. It has also been emailed\n";
		}
	}

	public function primetimeAction()
	{
		$request = $this->getRequest();
		// Make sure that we are running in a console and the user has not tricked our
		// application into running this action from a public web server.

		if (!$request instanceof ConsoleRequest) {
			throw new \RuntimeException('You can only use this action from a console!');
		}

		// Get primetime percent from console and check if the user used --verbose or -v flag
		$action = $request->getParam('type');
		$percent = $request->getParam('percent');
		$verbose = $request->getParam('verbose') || $request->getParam('v');
		$time = $request->getParam('time');
		$date = $request->getParam('date');

		$percent = (float)$percent;
		if ($percent <= 1) {
			$percent *= 100;
		}

		$validatorDate = new ValidatorDate(array('format' => 'Y-m-d'));
		if (!$validatorDate->isValid($date)) {
			if ($verbose) {
				echo "Error on date {$date} " . implode(', ',$validatorDate->getMessages()) . "\n";
			}
		}

		$validatorTime = new ValidatorDate(array('format' => 'H:m:s'));
		if (!$validatorTime->isValid($time)) {
			if ($verbose) {
				echo "Error on time {$time} " . implode(', ', $validatorTime->getMessages()) . "\n";
			}
		}

		// Fetch the user and change his password, then email him ...
		// [...]

		$result = "\nprimetime ($action) --date=$date --time={$time} {$percent}%\n";
		return $result;

		if (!$verbose) {
			return "Done! $userEmail has received an email with his new password.\n";
		} else {
			return "Done! New password for user $userEmail is '$newPassword'. It has also been emailed\n";
		}
	}

}
