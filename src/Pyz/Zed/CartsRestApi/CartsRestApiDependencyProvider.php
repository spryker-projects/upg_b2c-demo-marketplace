<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CartsRestApi;

use Spryker\Zed\CartsRestApi\CartsRestApiDependencyProvider as SprykerCartsRestApiDependencyProvider;
use Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCreatorPluginInterface;
use Spryker\Zed\DiscountPromotionsRestApi\Communication\Plugin\CartsRestApi\DiscountPromotionCartItemMapperPlugin;
use Spryker\Zed\MerchantProductOffersRestApi\Communication\Plugin\CartsRestApi\MerchantProductOfferCartItemMapperPlugin;
use Spryker\Zed\ProductBundleCartsRestApi\Communication\Plugin\BundleItemQuoteItemReadValidatorPlugin;
use Spryker\Zed\ProductConfigurationsRestApi\Communication\Plugin\CartsRestApi\ProductConfigurationCartItemMapperPlugin;
use Spryker\Zed\ProductOptionsRestApi\Communication\Plugin\CartsRestApi\ProductOptionCartItemMapperPlugin;
use Spryker\Zed\SalesOrderThresholdsRestApi\Communication\Plugin\CartsRestApi\SalesOrderThresholdQuoteExpanderPlugin;

class CartsRestApiDependencyProvider extends SprykerCartsRestApiDependencyProvider
{
    /**
     * @return \Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCreatorPluginInterface
     */
    protected function getQuoteCreatorPlugin(): QuoteCreatorPluginInterface
    {
        return new SprykerQuoteCreatorPlugin();
    }

    /**
     * @return array<\Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\CartItemMapperPluginInterface>
     */
    protected function getCartItemMapperPlugins(): array
    {
        return [
            new ProductOptionCartItemMapperPlugin(),
            new DiscountPromotionCartItemMapperPlugin(),
            new MerchantProductOfferCartItemMapperPlugin(),
            new ProductConfigurationCartItemMapperPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteItemReadValidatorPluginInterface>
     */
    protected function getQuoteItemReadValidatorPlugins(): array
    {
        return [
            new BundleItemQuoteItemReadValidatorPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteExpanderPluginInterface>
     */
    protected function getQuoteExpanderPlugins(): array
    {
        return [
            new SalesOrderThresholdQuoteExpanderPlugin(),
        ];
    }
}
