<?php

declare(strict_types=1);

namespace AkeneoTest\Acceptance\Storage\Sql;

use Akeneo\Pim\Enrichment\Bundle\Product\Query\Sql\GetDescendantVariantProductIdentifiers as BaseQuery;
use Akeneo\Test\Acceptance\Product\InMemoryProductRepository;

/**
 * @author    Mathias METAYER <mathias.metayer@akeneo.com>
 * @copyright 2019 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class InMemoryGetDescendantVariantProductIdentifiers extends BaseQuery
{
    /** @var InMemoryProductRepository */
    private $productRepository;

    public function __construct(InMemoryProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function fromProductModelCodes(array $productModelCodes): array
    {
        $identifiers = [];
        foreach ($this->productRepository->findAll() as $product) {
            if (null !== $product->getProductModel() && in_array($product->getProductModel()->getCode(), $productModelCodes)) {
                $identifiers[] = $product->getidentifier();
            }
        }

        return $identifiers;    }
}
