<?php
namespace OCA\RcConnect\Service;

use Exception;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;
use OCP\AppFramework\Http\DataResponse;
use OCP\Security\ICrypto;

use OCA\RcConnect\Db\Rc;
use OCA\RcConnect\Db\RcMapper;

class RcService {

    /** @var ICrypto */
    private $crypto;

    /** @var RcMapper */
    private $mapper;

    public function __construct(RcMapper $mapper, ICrypto $crypto) {
        $this->mapper = $mapper;
        $this->crypto = $crypto;
    }

    public function getUserInfo($uid) {
        return $this->mapper->getUserInfo($uid);
    }

    public function update($uid, $username, $password) {
        try {
            $password = $this->crypto->encrypt($password);
            return $this->mapper->UserUpdate($uid, $username, $password);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    public function delete($uid) {
        try {
            return $this->mapper->UserDelete($uid);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    private function handleException(Exception $e): void {
        if ($e instanceof DoesNotExistException ||
            $e instanceof MultipleObjectsReturnedException) {
            throw new RcNotFound($e->getMessage());
        } else {
            throw $e;
        }
    }

}
