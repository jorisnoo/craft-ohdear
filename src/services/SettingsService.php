<?php
/**
 * Oh Dear plugin for Craft CMS 3.x
 *
 * Integrate Oh Dear into Craft CMS.
 *
 * @link      https://webhub.de
 * @copyright Copyright (c) 2019 webhub GmbH
 */

namespace webhubworks\ohdear\services;

use craft\base\Component;
use craft\elements\GlobalSet;
use OhDear\PhpSdk\OhDear as OhDearSdk;
use OhDear\PhpSdk\Resources\BrokenLink;
use OhDear\PhpSdk\Resources\CertificateHealth;
use OhDear\PhpSdk\Resources\Check;
use OhDear\PhpSdk\Resources\MixedContentItem;
use OhDear\PhpSdk\Resources\Site;
use Spatie\Url\Url;
use webhubworks\ohdear\OhDear;
use yii\base\Exception;

/**
 * https://craftcms.com/docs/plugins/services
 *
 * @author    webhub GmbH
 * @package   OhDear
 * @since     1.0.0
 */
class SettingsService extends Component
{
    /**
     * @param string $apiToken
     * @return array
     */
    public function getSites($apiToken)
    {
        return (new OhDearSdk($apiToken))->sites();
    }
}
