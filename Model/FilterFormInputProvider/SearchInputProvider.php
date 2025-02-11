<?php

namespace Tweakwise\Magento2Tweakwise\Model\FilterFormInputProvider;

use Tweakwise\Magento2Tweakwise\Model\Catalog\Layer\NavigationContext\CurrentContext;
use Tweakwise\Magento2Tweakwise\Model\Config;
use Tweakwise\Magento2Tweakwise\Model\Seo\FilterHelper;
use Tweakwise\Magento2TweakwiseExport\Model\Helper;

class SearchInputProvider implements FilterFormInputProviderInterface
{
    /**
     *
     */
    public const TYPE = 'search';

    /**
     * @var CurrentContext
     */
    protected $currentNavigationContext;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var ToolbarInputProvider
     */
    protected $toolbarInputProvider;

    /**
     * SearchParameterProvider constructor.
     * @param Config $config
     * @param CurrentContext $currentNavigationContext
     * @param ToolbarInputProvider $toolbarInputProvider
     */
    public function __construct(
        Config $config,
        CurrentContext $currentNavigationContext,
        ToolbarInputProvider $toolbarInputProvider
    ) {
        $this->config = $config;
        $this->currentNavigationContext = $currentNavigationContext;
        $this->toolbarInputProvider = $toolbarInputProvider;
    }

    /**
     * @inheritDoc
     */
    public function getFilterFormInput(): array
    {
        $parameters = [
            'q' => $this->getSearchTerm()
        ];

        if ($categoryFilter = $this->getCategoryFilter()) {
            $parameters[FilterHelper::TWEAKWISE_CATEGORY_FILTER_NAME] = $categoryFilter;
        }

        if (!$this->config->isAjaxFilters()) {
            return $parameters;
        }

        return array_merge(
            $parameters,
            [
                '__tw_ajax_type' => self::TYPE,
                '__tw_original_url' => 'catalogsearch/result/index',
            ],
            $this->toolbarInputProvider->getFilterFormInput()
        );
    }

    /**
     * @return string|null
     */
    protected function getSearchTerm()
    {
        return $this->currentNavigationContext
            ->getRequest()
            ->getParameter('tn_q');
    }

    /**
     * @return string|null
     */
    protected function getCategoryFilter()
    {
        return $this->currentNavigationContext
            ->getRequest()
            ->getCategoryPathFilter();
    }
}
