<?php
/**
 * Tweakwise (https://www.tweakwise.com/) - All Rights Reserved
 *
 * @copyright Copyright (c) 2017-2022 Tweakwise.com B.V. (https://www.tweakwise.com)
 * @license   Proprietary and confidential, Unauthorized copying of this file, via any medium is strictly prohibited
 */

namespace Tweakwise\Magento2Tweakwise\Block\Catalog\Product\ProductList\Toolbar;


use Closure;
use Tweakwise\Magento2Tweakwise\Model\Catalog\Layer\NavigationContext\CurrentContext;
use Tweakwise\Magento2Tweakwise\Model\Client\Type\SortFieldType;
use Tweakwise\Magento2Tweakwise\Model\Config;
use Magento\Catalog\Block\Product\ProductList\Toolbar;

class Plugin
{
    /**
     * @var CurrentContext
     */
    protected $context;

    /**
     * @var Config
     */
    protected $config;

    /**
     * Plugin constructor.
     *
     * @param Config $config
     * @param CurrentContext $context
     */
    public function __construct(Config $config, CurrentContext $context)
    {
        $this->context = $context;
        $this->config = $config;
    }

    /**
     * @param Toolbar $subject
     * @param Closure $proceed
     * @return array
     */
    public function aroundGetAvailableOrders(Toolbar $subject, Closure $proceed)
    {
        if (!$this->config->isLayeredEnabled()) {
            return $proceed();
        }

        /** @var SortFieldType[] $sortFields */
        $sortFields = $this->context->getResponse()->getProperties()->getSortFields();

        $result = [];
        foreach ($sortFields as $field) {
            $result[$field->getUrlValue()] = $field->getDisplayTitle();
        }
        return $result;
    }

    /**
     * @param Toolbar $subject
     * @param string $result
     * @return false|string
     */
    public function afterGetWidgetOptionsJson(Toolbar $subject, string $result)
    {
        if (!$this->config->isAjaxFilters()) {
            return $result;
        }

        $options = json_decode($result, true);
        $options['productListToolbarForm']['ajaxFilters'] = true;

        return json_encode($options);
    }
}
