<?php

namespace AkeneoTest\Pim\Enrichment\Integration\Doctrine\Common\Saver;

use Akeneo\Pim\Enrichment\Component\Product\Query\GetProductCompletenesses;
use Akeneo\Test\Integration\TestCase;
use Akeneo\Test\IntegrationTestsBundle\Launcher\JobLauncher;
use Akeneo\Tool\Bundle\ElasticsearchBundle\Client;
use AkeneoTest\Pim\Enrichment\Integration\Fixture\EntityBuilder;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Test product models and their descendants have been correctly indexed after being saved.
 */
class SavingProductModelDescendantsIntegration extends TestCase
{
    /** @var Client */
    private $esProductAndProductModelClient;

    /** @var JobLauncher */
    private $launcher;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->esProductAndProductModelClient = $this->get('akeneo_elasticsearch.client.product_and_product_model');

        $this->authenticateUserAdmin();

        $this->launcher = new JobLauncher(static::$kernel);
    }

    public function testIndexingProductModelDescendantsOnUnitarySave()
    {
        $this->createProductsAndProductModelsTree('seed');
        $this->get('doctrine.orm.entity_manager')->clear();
        $this->get('akeneo_elasticsearch.client.product_and_product_model')->refreshIndex();
        sleep(5);

        $rootProductModel = $this->get('pim_catalog.repository.product_model')
            ->findOneByIdentifier('seed_root_product_model');

        $this->get('pim_catalog.updater.product_model')->update($rootProductModel, [
            'values' => [
                'a_date' => [
                    ['locale' => null, 'scope' => null, 'data' => '2016-06-13T00:00:00+02:00'],
                ],
            ],
        ]);

        $this->get('pim_catalog.saver.product_model')->save($rootProductModel);

        while ($this->launcher->hasJobInQueue()) {
            $this->launcher->launchConsumerOnce();
        }

        $this->get('akeneo_elasticsearch.client.product_and_product_model')->refreshIndex();

        $this->assertDocumentIdsForSearch(
            [
                'seed_root_product_model',
                'seed_sub_product_model_1',
                'seed_sub_product_model_2',
                'seed_variant_product_1',
                'seed_variant_product_2',
                'seed_variant_product_3',
                'seed_variant_product_4',
            ],
            [
                '_source' => 'identifier',
                'query'   => [
                    'bool' => [
                        'filter' => [
                            'exists' => [
                                'field' => 'values.a_date-date.<all_channels>.<all_locales>',
                            ],
                        ],
                    ],
                ],
            ]
        );
    }

    public function testProductModelDescendantsCompletenessIsCalculatedOnUnitarySave()
    {
        $this->createProductsAndProductModelsTree('seed');
        $this->get('doctrine.orm.entity_manager')->clear();
        $this->get('akeneo_elasticsearch.client.product_and_product_model')->refreshIndex();
        sleep(5);

        $this->assertCompletenessForChannel('seed_variant_product_2', 'ecommerce', 5);
        $this->get('doctrine.orm.entity_manager')->clear();
        $this->get('akeneo_elasticsearch.client.product_and_product_model')->refreshIndex();
        sleep(5);

        $rootProductModel = $this->get('pim_catalog.repository.product_model')
            ->findOneByIdentifier('seed_root_product_model');

        $this->get('pim_catalog.updater.product_model')->update($rootProductModel, [
            'values' => [
                'a_date' => [
                    ['locale' => null, 'scope' => null, 'data' => '2016-06-13T00:00:00+02:00'],
                ],
            ],
        ]);

        $this->get('pim_catalog.saver.product_model')->save($rootProductModel);

        while ($this->launcher->hasJobInQueue()) {
            $this->launcher->launchConsumerOnce();
        }

        $this->get('akeneo_elasticsearch.client.product_and_product_model')->refreshIndex();

        $this->assertCompletenessForChannel('seed_variant_product_2', 'ecommerce', 10);
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfiguration()
    {
        return $this->catalog->useTechnicalCatalog();
    }

    /**
     * Checks the expected identifier code are returned by the ES search.
     *
     * @param array $expectedIdentifiers
     * @param array $search
     *
     * @return bool
     */
    private function assertDocumentIdsForSearch(array $expectedIdentifiers, array $search): bool
    {
        $documents = $this->esProductAndProductModelClient->search($search);
        $actualDocumentIdentifiers = array_map(function ($document) {
            return $document['_source']['identifier'];
        }, $documents['hits']['hits']);

        sort($expectedIdentifiers);
        sort($actualDocumentIdentifiers);

        $this->assertSame($expectedIdentifiers, $actualDocumentIdentifiers);

        return true;
    }

    /**
     * Creates some products and product models related to each other within the same familyVariant
     *
     * @param string $seed
     */
    private function createProductsAndProductModelsTree(string $seed)
    {
        $entityBuilder = $this->getFromTestContainer('akeneo_integration_tests.catalog.fixture.build_entity');

        $rootProductModel = $entityBuilder->createProductModel($seed . '_root_product_model', 'familyVariantA1', null, []);
        $subProductModel1 = $entityBuilder->createProductModel($seed . '_sub_product_model_1', 'familyVariantA1', $rootProductModel, []);
        $subProductModel2 = $entityBuilder->createProductModel($seed . '_sub_product_model_2', 'familyVariantA1', $rootProductModel, []);

        $entityBuilder->createVariantProduct($seed . '_variant_product_1', 'familyA', 'familyVariantA1', $subProductModel1, []);
        $entityBuilder->createVariantProduct($seed . '_variant_product_2', 'familyA', 'familyVariantA1', $subProductModel1, []);
        $entityBuilder->createVariantProduct($seed . '_variant_product_3', 'familyA', 'familyVariantA1', $subProductModel2, []);
        $entityBuilder->createVariantProduct($seed . '_variant_product_4', 'familyA', 'familyVariantA1', $subProductModel2, []);

    }

    /**
     * @param string $productName
     * @param string $expectedChannelCode
     * @param int    $expectedRatio
     */
    private function assertCompletenessForChannel(string $productName, string $expectedChannelCode, int $expectedRatio): void
    {
        $productVariant2 = $this->get('pim_catalog.repository.product')->findOneByIdentifier($productName);
        $completenessCollection = $this->getProductCompletenesses()->fromProductId($productVariant2->getId());

        foreach ($completenessCollection as $completeness) {
            if ($expectedChannelCode === $completeness->channelCode()) {
                $this->assertSame(
                    $expectedRatio,
                    $completeness->ratio(),
                    sprintf('Expect ratio to be "%s", "%s" given', $expectedRatio, $completeness->ratio())
                );
            }
        }
    }

    private function authenticateUserAdmin(): void
    {
        $user = $this->get('pim_user.provider.user')->loadUserByUsername('admin');
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->get('security.token_storage')->setToken($token);
    }

    private function getProductCompletenesses(): GetProductCompletenesses
    {
        return $this->get('akeneo.pim.enrichment.product.query.get_product_completenesses');
    }
}
