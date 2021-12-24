<?php
declare(strict_types=1);

namespace OCA\RcConnect\AppInfo;

use OCP\AppFramework\App;

class Application extends App {
	public const APP_ID = 'rcconnect';

	public function __construct() {
		parent::__construct(self::APP_ID);
                BootstrapSingleton::getInstance($this->getContainer())->boot();
	}
}
