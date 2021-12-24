<?php
namespace OCA\RcConnect\Controller;

use OCA\RcConnect\AppInfo\Application;
use OCA\RcConnect\Service\RcService;

use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\DataResponse;

use OCP\IRequest;
use OCP\IUserSession;

class RcController extends Controller {
    /*
     * @var RcService
     */
    private $service;

    /** @var IUserSession */
    private $userSession;

    use Errors;

    public function __construct(IRequest $request, RcService $service, IUserSession $userSession){
        parent::__construct(Application::APP_ID, $request);
        $this->service = $service;
        $this->userSession = $userSession;
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function index(): DataResponse {
        $user = $this->userSession->getUser();                
        $uid = $user->getUID();

        return $this->handleNotFound(function () use($uid){
            return $this->service->getUserInfo($uid);
        });
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function update($username, $password): DataResponse {
        $user = $this->userSession->getUser();                
        $uid = $user->getUID();

        return $this->handleNotFound(function () use($uid, $username, $password){
            return $this->service->update($uid, $username, $password);
        });
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function destroy(): DataResponse {
        $user = $this->userSession->getUser();                
        $uid = $user->getUID();

        return $this->handleNotFound(function () use($uid){
            return $this->service->delete($uid);
        });
    }
}
