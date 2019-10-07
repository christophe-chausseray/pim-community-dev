<?php

declare(strict_types=1);

namespace Akeneo\Test\Acceptance\Catalog\Context;

use Akeneo\Pim\Enrichment\Component\Product\Model\ProductModel;
use Akeneo\Pim\Enrichment\Component\Product\Value\ScalarValue;
use Akeneo\Test\Acceptance\ProductModel\InMemoryProductModelRepository;
use Akeneo\Tool\Component\StorageUtils\Saver\SaverInterface;
use Behat\Behat\Context\Context;
use Webmozart\Assert\Assert;

/**
 * @author    Mathias METAYER <mathias.metayer@akeneo.com>
 * @copyright 2019 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
final class ProductModelCreation implements Context
{
    /** @var InMemoryProductModelRepository */
    private $productModelRepository;

    /** @var SaverInterface */
    private $productModelSaver;

    public function __construct(
        InMemoryProductModelRepository $productModelRepository,
        SaverInterface $productModelSaver
    ) {
        $this->productModelRepository = $productModelRepository;
        $this->productModelSaver = $productModelSaver;
    }

    /**
     * @Given the product model :productModelCode and its children :variantProductIdentifiers
     */
    public function theProductModelAndItsVariantProductChildren(string $productModelCode, string $variantProductIdentifiers): void
    {
        $productModel = new ProductModel();
        $productModel->setCode($productModelCode);
        $this->productModelRepository->save($productModel);
    }

    /**
     * @When I add a required value to product model :productModelCode
     */
    public function iAddRequiredValueToProductModel(string $productModelCode): void
    {
        $productModel = $this->productModelRepository->findOneByIdentifier($productModelCode);
        Assert::notNull($productModel, sprintf('Could not find product model with code "%s"', $productModelCode));
        $productModel->getValues()->add(ScalarValue::value('name', 'my t-shirt'));
        $this->productModelSaver->save($productModel);
    }

    /**
     * @Then the completeness of :productIdentifiers should be impacted
     */
    public function theCompletenessOfProductsShouldBeImpacted(string $productIdentifiers)
    {
        var_dump($productIdentifiers);
    }
}
