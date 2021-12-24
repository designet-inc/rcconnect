<?php

declare(strict_types=1);

namespace OCA\RcConnect\AppInfo;

use OCA\RcConnect\Listener\UserDeletedListener;
use OCP\AppFramework\IAppContainer;
use OCP\EventDispatcher\IEventDispatcher;
use OCP\User\Events\UserDeletedEvent;
use OCP\Util;

class BootstrapSingleton {

	/** @var BootstrapSingleton */
	private static $instance = null;

	/** @var bool */
	private $booted = false;

	/** @var IAppContainer */
	private $container;

	private function __construct(IAppContainer $container) {
		$this->container = $container;
	}

	public static function getInstance(IAppContainer $container): BootstrapSingleton {
		if (self::$instance === null) {
			self::$instance = new static($container);
		}

		return self::$instance;
	}

	public function boot(): void {
		if ($this->booted) {
			return;
		}

		$this->registerEvents($this->container);
		$this->booted = true;
	}


	private function registerEvents(IAppContainer $container): void {
		/** @var IEventDispatcher $dispatcher */
		$dispatcher = $container->query(IEventDispatcher::class);
		$dispatcher->addServiceListener(UserDeletedEvent::class, UserDeletedListener::class);
	}
}
