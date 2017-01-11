<?php

namespace spec\Pim\Component\Catalog\Updater\Copier;

use PhpSpec\ObjectBehavior;
use Pim\Component\Catalog\Builder\ProductBuilderInterface;
use Pim\Component\Catalog\Model\AttributeInterface;
use Pim\Component\Catalog\Model\ProductInterface;
use Pim\Component\Catalog\Model\ProductPriceInterface;
use Pim\Component\Catalog\Model\ProductValue;
use Pim\Component\Catalog\Validator\AttributeValidatorHelper;
use Prophecy\Argument;

class PriceCollectionAttributeCopierSpec extends ObjectBehavior
{
    function let(ProductBuilderInterface $builder, AttributeValidatorHelper $attrValidatorHelper)
    {
        $this->beConstructedWith(
            $builder,
            $attrValidatorHelper,
            ['pim_catalog_price_collection'],
            ['pim_catalog_price_collection']
        );
    }

    function it_is_a_copier()
    {
        $this->shouldImplement('Pim\Component\Catalog\Updater\Copier\CopierInterface');
    }

    function it_supports_metric_attributes(
        AttributeInterface $fromPriceCollectionAttribute,
        AttributeInterface $toPriceCollectionAttribute,
        AttributeInterface $toTextareaAttribute,
        AttributeInterface $fromNumberAttribute,
        AttributeInterface $toNumberAttribute
    ) {
        $fromPriceCollectionAttribute->getAttributeType()->willReturn('pim_catalog_price_collection');
        $toPriceCollectionAttribute->getAttributeType()->willReturn('pim_catalog_price_collection');
        $this->supportsAttributes($fromPriceCollectionAttribute, $toPriceCollectionAttribute)->shouldReturn(true);

        $fromNumberAttribute->getAttributeType()->willReturn('pim_catalog_number');
        $toPriceCollectionAttribute->getAttributeType()->willReturn('pim_catalog_price_collection');
        $this->supportsAttributes($fromNumberAttribute, $toNumberAttribute)->shouldReturn(false);

        $this->supportsAttributes($fromPriceCollectionAttribute, $toNumberAttribute)->shouldReturn(false);
        $this->supportsAttributes($fromNumberAttribute, $toTextareaAttribute)->shouldReturn(false);
    }

    function it_copies_a_price_collection_value_to_a_product_value(
        $builder,
        $attrValidatorHelper,
        AttributeInterface $fromAttribute,
        AttributeInterface $toAttribute,
        ProductInterface $product1,
        ProductInterface $product2,
        ProductInterface $product3,
        ProductValue $fromProductValue,
        ProductValue $toProductValue,
        ProductPriceInterface $price
    ) {
        $fromLocale = 'fr_FR';
        $toLocale = 'fr_FR';
        $toScope = 'mobile';
        $fromScope = 'mobile';

        $fromAttribute->getCode()->willReturn('fromAttributeCode');
        $toAttribute->getCode()->willReturn('toAttributeCode');

        $attrValidatorHelper->validateLocale(Argument::cetera())->shouldBeCalled();
        $attrValidatorHelper->validateScope(Argument::cetera())->shouldBeCalled();

        $fromProductValue->getData()->willReturn([$price])->shouldBeCalledTimes(2);

        $price->getCurrency()->willReturn('USD');
        $price->getData()->willReturn(123);

        $product1->getValue('fromAttributeCode', $fromLocale, $fromScope)->willReturn($fromProductValue);
        $product1->getValue('toAttributeCode', $toLocale, $toScope)->shouldBeCalled()->willReturn($toProductValue);
        $product1->removeValue($toProductValue)->shouldBeCalled()->willReturn($product1);
        $builder
            ->addProductValue($product1, $toAttribute, $toLocale, $toScope, [$price])
            ->shouldBeCalled()
            ->willReturn($toProductValue);

        $product2->getValue('fromAttributeCode', $fromLocale, $fromScope)->willReturn(null);
        $product2->getValue('toAttributeCode', $toLocale, $toScope)->shouldNotBeCalled();
        $product2->removeValue(Argument::any())->shouldNotBeCalled();
        $builder
            ->addProductValue($product2, $toAttribute, $toLocale, $toScope, [$price])
            ->shouldNotBeCalled();

        $product3->getValue('fromAttributeCode', $fromLocale, $fromScope)->willReturn($fromProductValue);
        $product3->getValue('toAttributeCode', $toLocale, $toScope)->shouldBeCalled()->willReturn(null);
        $product3->removeValue(null)->shouldNotBeCalled();
        $builder
            ->addProductValue($product3, $toAttribute, $toLocale, $toScope, [$price])
            ->shouldBeCalled()
            ->willReturn($toProductValue);

        $products = [$product1, $product2, $product3];
        foreach ($products as $product) {
            $this->copyAttributeData(
                $product,
                $product,
                $fromAttribute,
                $toAttribute,
                [
                    'from_locale' => $fromLocale,
                    'to_locale' => $toLocale,
                    'from_scope' => $fromScope,
                    'to_scope' => $toScope
                ]
            );
        }
    }
}
