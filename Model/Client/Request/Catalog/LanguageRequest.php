<?php

namespace Tweakwise\Magento2Tweakwise\Model\Client\Request\Catalog;

use Tweakwise\Magento2Tweakwise\Model\Client\Request;
use Tweakwise\Magento2Tweakwise\Model\Client\Response\Catalog\LanguageResponse;

/**
 * Class LanguageRequest
 * @package Tweakwise\Magento2Tweakwise\Model\Client\Request\Catalog
 */
class LanguageRequest extends Request
{
    /**
     * @var string
     */
    protected $path = 'catalog/languages';

    /**
     * @return string
     */
    public function getResponseType()
    {
        return LanguageResponse::class;
    }
}
