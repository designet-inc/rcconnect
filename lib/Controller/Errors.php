<?php

namespace OCA\RcConnect\Controller;

use Closure;

use OCP\AppFramework\Http;
use OCP\AppFramework\Http\DataResponse;

use OCA\RcConnect\Service\RcService;

trait Errors {
        protected function handleNotFound(Closure $callback): DataResponse {
                try {
                        return new DataResponse($callback());
                } catch (RcService $e) {
                        $message = ['message' => $e->getMessage()];
                        return new DataResponse($message, Http::STATUS_NOT_FOUND);
                }
        }
}
