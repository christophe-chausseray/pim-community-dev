<?php

namespace Pim\Component\Catalog\Factory\ProductValue;

use Pim\Component\Catalog\Model\AttributeInterface;

/**
 * Factory that creates date product values.
 *
 * @internal  Please, do not use this class directly. You must use \Pim\Component\Catalog\Factory\ProductValueFactory.
 *
 * @author    Damien Carcel (damien.carcel@akeneo.com)
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
class DateProductValueFactory implements ProductValueFactoryInterface
{
    /** @var string */
    protected $dateProductValueClass;

    /** @var string */
    protected $supportedAttributeType;

    /**
     * @param string $dateProductValueClass
     * @param string $supportedAttributeType
     */
    public function __construct($dateProductValueClass, $supportedAttributeType)
    {
        if (!class_exists($dateProductValueClass)) {
            throw new \InvalidArgumentException(
                sprintf('The product value class "%s" does not exist.', $dateProductValueClass)
            );
        }

        $this->dateProductValueClass = $dateProductValueClass;
        $this->supportedAttributeType = $supportedAttributeType;
    }

    /**
     * @inheritdoc
     */
    public function create(AttributeInterface $attribute, $channelCode, $localeCode, $data)
    {
        $value = new $this->dateProductValueClass();
        $value->setAttribute($attribute);
        $value->setScope($channelCode);
        $value->setLocale($localeCode);
        $value->setDate($data);

        return $value;
    }

    /**
     * @inheritdoc
     */
    public function supports($attributeType)
    {
        return $attributeType === $this->supportedAttributeType;
    }
}
