<?php
namespace OCA\RcConnect\Db;

use JsonSerializable;
use OC;
use OCP\AppFramework\Db\Entity;
use OCP\Security\ICrypto;

class Rc extends Entity implements JsonSerializable {
        /** @var ICrypto */
        private $crypto;

	protected $uid;
	protected $password;
	protected $username;
	protected $count;


        public function __construct() {
                $this->crypto = OC::$server->getCrypto();
        }
	public function jsonSerialize(): array{
		return [
			'id' => $this->id,
			'uid' => $this->uid,
			'password' => $this->crypto->decrypt($this->password),
			'username' => $this->username,
			'count' => $this->count,
		];
	}
}
