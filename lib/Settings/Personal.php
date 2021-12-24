<?php
namespace OCA\RcConnect\Settings;

use OCP\AppFramework\Http\TemplateResponse;
use OCP\IURLGenerator;
use OCP\Settings\ISettings;

class Personal implements ISettings {

	/** @var IURLGenerator */
	protected $url;

	public function __construct(IURLGenerator $url) {
		$this->url = $url;
	}

	/**
	 * @return TemplateResponse
	 */
    public function getForm() {
        $parameters = [
            'url' => 'https://osslab02.designet.co.jp:9880/roundcubemail/',
        ];

        return new TemplateResponse('rcconnect', 'settings-personal', $parameters);
	}

	/**
	 * @return string the section ID, e.g. 'sharing'
	 */
	public function getSection() {
		return 'rcconnect';
	}

	/**
	 * @return int whether the form should be rather on the top or bottom of
	 * the admin section. The forms are arranged in ascending order of the
	 * priority values. It is required to return a value between 0 and 100.
	 *
	 * E.g.: 70
	 */
	public function getPriority() {
		return 100;
	}
}
