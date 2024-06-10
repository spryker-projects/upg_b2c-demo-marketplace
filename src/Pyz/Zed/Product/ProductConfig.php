<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Product;

use Spryker\Shared\ProductBundleStorage\ProductBundleStorageConfig;
use Spryker\Zed\PriceProduct\Dependency\PriceProductEvents;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\Product\ProductConfig as SprykerProductConfig;
use Spryker\Zed\ProductCategory\Dependency\ProductCategoryEvents;
use Spryker\Zed\ProductImage\Dependency\ProductImageEvents;
use Spryker\Zed\ProductReview\Dependency\ProductReviewEvents;

class ProductConfig extends SprykerProductConfig
{
    /**
     * @api
     *
     * @return array<string>
     */
    public function getProductAbstractUpdateMessageBrokerPublisherSubscribedEvents(): array
    {
        return [
            ProductEvents::PRODUCT_ABSTRACT_PUBLISH,
            ProductCategoryEvents::PRODUCT_CATEGORY_PUBLISH,
            ProductImageEvents::PRODUCT_IMAGE_PRODUCT_ABSTRACT_PUBLISH,
            PriceProductEvents::PRICE_ABSTRACT_PUBLISH,
            ProductReviewEvents::PRODUCT_ABSTRACT_REVIEW_PUBLISH,
            \Spryker\Zed\ProductCategory\Dependency\ProductCategoryEvents::ENTITY_SPY_PRODUCT_CATEGORY_CREATE,
            \Spryker\Zed\ProductCategory\Dependency\ProductCategoryEvents::ENTITY_SPY_PRODUCT_CATEGORY_DELETE,
            \Spryker\Zed\ProductLabel\Dependency\ProductLabelEvents::ENTITY_SPY_PRODUCT_LABEL_PRODUCT_ABSTRACT_CREATE,
            \Spryker\Zed\ProductLabel\Dependency\ProductLabelEvents::ENTITY_SPY_PRODUCT_LABEL_PRODUCT_ABSTRACT_DELETE,
            \Spryker\Zed\PriceProduct\Dependency\PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_CREATE,
            \Spryker\Zed\PriceProduct\Dependency\PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_UPDATE,
            \Spryker\Zed\ProductReview\Dependency\ProductReviewEvents::ENTITY_SPY_PRODUCT_REVIEW_CREATE,
            \Spryker\Zed\ProductReview\Dependency\ProductReviewEvents::ENTITY_SPY_PRODUCT_REVIEW_UPDATE,
            \Spryker\Zed\ProductImage\Dependency\ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_CREATE,
            \Spryker\Zed\ProductImage\Dependency\ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_UPDATE,
        ];
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getProductUpdateMessageBrokerPublisherSubscribedEvents(): array
    {
        return [
            ProductEvents::ENTITY_SPY_PRODUCT_UPDATE,
            ProductEvents::PRODUCT_CONCRETE_UPDATE,
            ProductEvents::PRODUCT_CONCRETE_PUBLISH,
            ProductBundleStorageConfig::PRODUCT_BUNDLE_PUBLISH,
            ProductImageEvents::PRODUCT_IMAGE_PRODUCT_CONCRETE_PUBLISH,
            \Spryker\Shared\ProductBundleStorage\ProductBundleStorageConfig::ENTITY_SPY_PRODUCT_BUNDLE_CREATE,
            \Spryker\Shared\ProductBundleStorage\ProductBundleStorageConfig::ENTITY_SPY_PRODUCT_BUNDLE_UPDATE,
            \Spryker\Zed\ProductImage\Dependency\ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_CREATE,
            \Spryker\Zed\ProductImage\Dependency\ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_UPDATE,
            \Spryker\Zed\ProductImage\Dependency\ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_CREATE,
            \Spryker\Zed\ProductImage\Dependency\ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_UPDATE,
            \Spryker\Zed\PriceProduct\Dependency\PriceProductEvents::PRICE_CONCRETE_PUBLISH,
            \Spryker\Zed\PriceProduct\Dependency\PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_CREATE,
            \Spryker\Zed\PriceProduct\Dependency\PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_UPDATE,
            \Spryker\Zed\ProductSearch\Dependency\ProductSearchEvents::ENTITY_SPY_PRODUCT_SEARCH_CREATE,
            \Spryker\Zed\ProductSearch\Dependency\ProductSearchEvents::ENTITY_SPY_PRODUCT_SEARCH_UPDATE,
        ];
    }
}
