<?php

declare(strict_types=1);

namespace AkeneoTest\Acceptance\FamilyVariant;

use Akeneo\Pim\Structure\Component\Factory\FamilyFactory;
use Akeneo\Pim\Structure\Component\Updater\FamilyUpdater;
use Akeneo\Pim\Structure\Component\Updater\FamilyVariantUpdater;
use Akeneo\Test\Acceptance\Family\InMemoryFamilyRepository;
use Behat\Behat\Context\Context;

/**
 * @author    Mathias METAYER <mathias.metayer@akeneo.com>
 * @copyright 2019 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class FamilyContext implements Context
{
    /** @var InMemoryFamilyRepository */
    private $familyVariantRepository;

    /** @var FamilyFactory */
    private $familyFactory;

    /** @var FamilyVariantUpdater */
    private $familyVariantUpdater;
}
