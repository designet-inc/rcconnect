<?php

declare(strict_types=1);

namespace OCA\RcConnect\Listener;

use OCA\RcConnect\Service\RcService;
use OCP\AppFramework\Db\DoesNotExistException;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\ILogger;
use OCP\User\Events\UserDeletedEvent;

class UserDeletedListener implements IEventListener {

	/** @var RcService */
	private $rcService;

	/** @var ILogger */
	private $logger;

	public function __construct(RcService $rcService,
							ILogger $logger) {
		$this->rcService = $rcService;
		$this->logger = $logger;
	}

	public function handle(Event $event): void {
		if (!($event instanceof UserDeletedEvent)) {
			// Unrelated
			return;
		}

		$user = $event->getUser();
		foreach ($this->rcService->getUserInfo($user->getUID()) as $account) {
			try {
				$this->rcService->delete(
					$user->getUID()
				);
			} catch (Exception $e) {
				$this->logger->logException($e, [
					'message' => 'Could not delete user: ' . $e->getMessage(),
					'level' => ILogger::ERROR,
				]);
			}
		}
	}
}
