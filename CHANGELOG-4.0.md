# 4.0.x

# Bug fixes

- GITHUB-10247: Fix regex to compile the frontend assets. Thanks @liamjtoohey!

# Technical Improvements

- TIP-1185: Use a single index "product_and_product_model_index" to search on product and product models, instead dedicated product/product model indexes

## BC breaks

### Elasticsearch

- Remove akeneo_pim_product and akeneo_pim_product_model ES indexes and merge into akeneo_pim_product_and_product_model.

### Doctrine mapping

- The entity `Akeneo\Pim\Enrichment\Component\Product\Model\Completeness` is removed, and is no more an association of the `Product` entity.
  The methods `getCompletenesses` and `setCompletenesses` of a `Product` are removed.
  If you want to access the Completeness of products, please use the dedicated class `Akeneo\Pim\Enrichment\Component\Product\Query\GetProductCompletenesses`.

### Codebase

- Remove class `Akeneo\Pim\Enrichment\Component\Product\Manager\CompletenessManager`
- Remove service `pim_catalog.manager.completeness`
- Remove class `Akeneo\Pim\Enrichment\Component\Product\Completeness\CompletenessGenerator`
- Remove interface `Akeneo\Pim\Enrichment\Component\Product\Completeness\CompletenessGeneratorInterface`
- Remove service `pim_catalog.completeness.generator`
- Remove interface `Akeneo\Pim\Enrichment\Component\Product\Completeness\CompletenessCalculatorInterface` replace by the implementation `Akeneo\Pim\Enrichment\Component\Product\Completeness\CompletenessCalculator`
- Remove method `calculate` from `Akeneo\Pim\Enrichment\Component\Product\Completeness\CompletenessCalculator`
- Remove `Akeneo\Pim\Enrichment\Component\Product\Normalizer\Indexing\Product\ProductNormalizer`
- Remove `Akeneo\Pim\Enrichment\Component\Product\Normalizer\Indexing\Product\PropertiesNormalizer`
- Remove `Akeneo\Pim\Enrichment\Component\Product\Normalizer\Indexing\ProductModel\ProductModelNormalizer`
- Remove `Akeneo\Pim\Enrichment\Component\Product\Normalizer\Indexing\ProductModel\ProductModelPropertiesNormalizer`
- ProductQueryBuilder` should be used to search products and `ProductModelQueryBuilder` should be used to search product models.
- Change constructor of `Akeneo\Pim\Enrichment\Bundle\Elasticsearch\Filter\Field\IdFilter` to add `$prefix`.
- Change constructor of `Akeneo\Pim\Enrichment\Bundle\Elasticsearch\Indexer\ProductIndexer` to remove
    - `string $productClient`
    - `Symfony\Component\Serializer\Normalizer\NormalizerInterface`
    - `Akeneo\Pim\Enrichment\Component\Product\Repository\ProductRepositoryInterface` and add
    - `Akeneo\Pim\Enrichment\Bundle\Elasticsearch\GetElasticsearchProductProjectionInterface`
- Change constructor of `Akeneo\Pim\Enrichment\Bundle\Elasticsearch\Indexer\ProductModelIndexer` to remove `$productClient` and `$productModelClient`.
- Change constructor of `Akeneo\Pim\Enrichment\Bundle\Elasticsearch\ProductAndProductModelQueryBuilderFactory` to add:
    - `Akeneo\Pim\Structure\Component\Repository\AttributeRepositoryInterface`
    - `Akeneo\Pim\Enrichment\Component\Product\Query\Filter\FilterRegistryInterface`
    - `Akeneo\Pim\Enrichment\Component\Product\Query\Sorter\SorterRegistryInterface`
    - `Akeneo\Tool\Component\StorageUtils\Cursor\CursorFactoryInterface`
    - `Akeneo\Pim\Enrichment\Component\Product\Query\ProductQueryBuilderOptionsResolverInterface`
- Change constructor of `Akeneo\Pim\Enrichment\Bundle\Elasticsearch\ProductAndProductModelQueryBuilderWithSearchAggregatorFactory` to add:
    - `Akeneo\Pim\Structure\Component\Repository\AttributeRepositoryInterface`
    - `Akeneo\Pim\Enrichment\Component\Product\Query\Filter\FilterRegistryInterface`
    - `Akeneo\Pim\Enrichment\Component\Product\Query\Sorter\SorterRegistryInterface`
    - `Akeneo\Tool\Component\StorageUtils\Cursor\CursorFactoryInterface`
    - `Akeneo\Pim\Enrichment\Component\Product\Query\ProductQueryBuilderOptionsResolverInterface`
- Change constructor of `Akeneo\Pim\Enrichment\Bundle\EventSubscriber` to remove `$productClient` and `$productModelClient`.
- Rename `Akeneo\Tool\Bundle\VersioningBundle\EventSubscriber\AddVersionSubscriber` as `Akeneo\Tool\Bundle\VersioningBundle\EventSubscriber\AddVersionListener`
- Rename `Akeneo\UserManagement\Bundle\EventListener\UserPreferencesSubscriber` as `Akeneo\UserManagement\Bundle\EventListener\UserPreferencesListener`
- Change constructor of `Akeneo\Pim\Enrichment\Bundle\EventSubscriber\LoadEntityWithValuesSubscriber` to remove `Symfony\Component\DependencyInjection\ContainerInterface` and to add `Akeneo\Pim\Enrichment\Component\Product\Factory\Write\WriteValueCollectionFactory`
- Change constructor of `Akeneo\Platform\Bundle\InstallerBundle\FixtureLoader\FixtureJobLoader` to remove `Symfony\Component\DependencyInjection\ContainerInterface` and to add:
    - `Akeneo\Tool\Component\StorageUtils\Saver\BulkSaverInterface`
    - `Akeneo\Tool\Component\StorageUtils\Remover\BulkRemoverInterface`
    - `Doctrine\Common\Persistence\ObjectRepository`
- Change constructor of `Akeneo\Tool\Bundle\VersioningBundle\EventSubscriber\AddVersionListener` to remove `Symfony\Component\DependencyInjection\ContainerInterface` and to add:
    - `Akeneo\Tool\Bundle\VersioningBundle\Manager\VersionManager`
    - `Symfony\Component\Serializer\Normalizer\NormalizerInterface`
    - `Akeneo\Tool\Bundle\VersioningBundle\UpdateGuesser\UpdateGuesserInterface`
    - `Akeneo\Tool\Bundle\VersioningBundle\Manager\VersionContext`
- Change constructor of `Akeneo\UserManagement\Bundle\EventListener\UserPreferencesSubscriber` to remove `Symfony\Component\DependencyInjection\ContainerInterface` and to add:
    - `Akeneo\Tool\Component\Classification\Repository\CategoryRepositoryInterface`
    - `Akeneo\Channel\Component\Repository\ChannelRepositoryInterface`
    - `Akeneo\Channel\Component\Repository\LocaleRepositoryInterface`
    - `Akeneo\UserManagement\Component\Repository\UserRepositoryInterface`
- Change constructor of `Oro\Bundle\DataGridBundle\Datagrid\Manager` to remove `Symfony\Component\DependencyInjection\ContainerInterface` and to add:
    - `Oro\Bundle\DataGridBundle\Datagrid\Builder`
    - `Oro\Bundle\DataGridBundle\Provider\ConfigurationProviderInterface`
    - `Oro\Bundle\DataGridBundle\Datagrid\Builder\RequestParameters`
- Change constructor of `Oro\Bundle\DataGridBundle\Datagrid\MetadataParser` to remove `Symfony\Component\DependencyInjection\ContainerInterface` and to add `Symfony\Component\HttpKernel\Fragment\FragmentHandler`
- Change constructor of `Oro\Bundle\PimDataGridBundle\Twig\FilterExtension` to remove `Symfony\Component\DependencyInjection\ContainerInterface` and to add:
    - `Oro\Bundle\DataGridBundle\Datagrid\Manager`
    - `Oro\Bundle\PimDataGridBundle\Datagrid\Configuration\Product\FiltersConfigurator`
    - `Symfony\Component\Translation\TranslatorInterface`
- Remove class `Oro\Bundle\PimDataGridBundle\Extension\MassAction\Handler\SequentialEditActionHandler`
- Remove class `Oro\Bundle\PimDataGridBundle\Extension\MassAction\Handler\ExportMassActionHandler`
- Remove class `Oro\Bundle\PimDataGridBundle\Extension\MassAction\Handler\DeleteProductsMassActionHandler`
- Remove class `Oro\Bundle\PimDataGridBundle\Extension\MassAction\Handler\DeleteMassActionHandler`
- Remove class `Oro\Bundle\DataGridBundle\Extension\MassAction\MassActionMediatorInterface`
- Remove class `Oro\Bundle\DataGridBundle\Extension\MassAction\MassActionMediator`
- Remove class `Oro\Bundle\DataGridBundle\Extension\MassAction\MassActionHandlerInterface`
- Remove class `Oro\Bundle\DataGridBundle\Extension\MassAction\MassActionDispatcher`
- Remove class `Oro\Bundle\DataGridBundle\Extension\MassAction\WindowMassAction`
- Remove class `Oro\Bundle\DataGridBundle\Extension\MassAction\DeleteMassAction`
- Change constructor of `Akeneo\Pim\Enrichment\Bundle\Doctrine\Common\Saver\ProductModelDescendantsSaver`, to remove
    `Doctrine\Common\Persistence\ObjectManager`,
    `Akeneo\Pim\Enrichment\Component\Product\Manager\CompletenessManager` and
    `Akeneo\Tool\Component\StorageUtils\Indexer\BulkIndexerInterface` and
    `Akeneo\Tool\Component\StorageUtils\Indexer\IndexerInterface` and
    `Akeneo\Pim\Enrichment\Component\Product\Query\ProductQueryBuilderFactoryInterface` and
    `Akeneo\Tool\Component\StorageUtils\Detacher\BulkObjectDetacherInterface`, and add
    `Akeneo\Pim\Enrichment\Bundle\Product\ComputeAndPersistProductCompletenesses` and
    `Akeneo\Tool\Component\StorageUtils\Indexer\ProductModelIndexerInterface`
- Change constructor of `Akeneo\Pim\Enrichment\Bundle\Doctrine\Common\Saver`, to remove `Akeneo\Pim\Enrichment\Component\Product\Manager\CompletenessManager`
- Delete `Akeneo\Pim\Enrichment\Component\Product\Normalizer\InternalApi\CompletenessNormalizer` (use `Akeneo\Pim\Enrichment\Component\Product\Normalizer\InternalApi\ProductCompletenessNormalizer` instead)
- Delete `Akeneo\Pim\Enrichment\Component\Product\Normalizer\InternalApi\CompletenessCollectionNormalizer` (use `Akeneo\Pim\Enrichment\Component\Product\Normalizer\InternalApi\ProductCompletenessCollectionNormalizer` instead)
- Delete `Akeneo\Pim\Enrichment\Component\Product\Normalizer\Indexing\CompletenessCollectionNormalizer` (use `Akeneo\Pim\Enrichment\Component\Product\Normalizer\Indexing\ProductCompletenessCollectionNormalizer` instead)
- Change constructor of `Akeneo\Pim\Enrichment\Component\Product\Normalizer\Indexing\Product` to add `Akeneo\Pim\Enrichment\Component\Product\Query\GetProductCompletenesses`
- Change constructor of `Akeneo\Pim\Enrichment\Component\Product\Normalizer\Indexing\ProductAndProductModel` to add `Akeneo\Pim\Enrichment\Component\Product\Query\GetProductCompletenesses`
- Change constructor of `Akeneo\Pim\Enrichment\Component\Product\Normalizer\InternalApi\EntityWithFamilyVariantNormalizer` to add
     `Akeneo\Pim\Enrichment\Component\Product\Normalizer\InternalApi\ProductCompletenessWithMissingAttributeCodesCollectionNormalizer`,
     `Akeneo\Pim\Enrichment\Component\Product\Completeness\CompletenessCalculator` and remove
     `Akeneo\Pim\Enrichment\Component\Product\Completeness\CompletenessCalculatorInterface` and `Symfony\Component\Serializer\Normalizer\NormalizerInterface` 
- Change constructor of `Akeneo\Pim\Enrichment\Component\Product\Normalizer\InternalApi\ProductNormalizer` to remove
    `Doctrine\Common\Persistence\ObjectManager`, `Akeneo\Pim\Enrichment\Component\Product\Manager\CompletenessManager`, `Akeneo\Channel\Component\Repository\ChannelRepositoryInterface`,
    `Akeneo\Pim\Enrichment\Bundle\Filter\CollectionFilterInterface`, `Akeneo\Pim\Enrichment\Component\Product\Completeness\CompletenessCalculatorInterface` and `Symfony\Component\Serializer\Normalizer\NormalizerInterface, and to add
    `Akeneo\Pim\Enrichment\Component\Product\Normalizer\InternalApi\ProductCompletenessCollectionWithMissingAttributeCodesNormalizer`, `Akeneo\Pim\Enrichment\Component\Product\Completeness\CompletenessCalculator` and
    `Akeneo\Pim\Enrichment\Component\Product\Normalizer\InternalApi\MissingRequiredAttributesNormalizerInterface`
- Change constructor of `Akeneo\Pim\Enrichment\Component\Product\Normalizer\InternalApi\ProductModelNormalizer` to remove
    `Symfony\Component\Serializer\Normalizer\NormalizerInterface` ($incompleteValuesNormalizer), and to add
    `Akeneo\Pim\Enrichment\Component\Product\Completeness\MissingRequiredAttributesCalculator` and `Akeneo\Pim\Enrichment\Component\Product\Normalizer\InternalApi\MissingRequiredAttributesNormalizerInterface`
- Change constructor of `Oro\Bundle\PimDataGridBundle\Normalizer\ProductAssociationNormalizer` to add `Akeneo\Pim\Enrichment\Component\Product\Query\GetProductCompletenesses`
- Change constructor of `Oro\Bundle\PimDataGridBundle\Normalizer\ProductNormalizer` to add `Akeneo\Pim\Enrichment\Component\Product\Query\GetProductCompletenesses`
- Removed the following class and the corresponding command:
    - `Akeneo\Pim\Enrichment\Bundle\Command\CreateProductCommand`
    - `Akeneo\Pim\Enrichment\Bundle\Command\GetProductCommand`
    - `Akeneo\Pim\Enrichment\Bundle\Command\QueryProductCommand`
    - `Akeneo\Pim\Enrichment\Bundle\Command\RemoveProductCommand`
    - `Akeneo\Pim\Enrichment\Bundle\Command\RemoveWrongBooleanValuesOnVariantProductsBatchCommand`
    - `Akeneo\Pim\Enrichment\Bundle\Command\RemoveWrongBooleanValuesOnVariantProductsCommand`
    - `Akeneo\Pim\Enrichment\Bundle\Command\UpdateProductCommand`
    - `Akeneo\Pim\Enrichment\Bundle\Command\ValidateObjectsCommand`
    - `Akeneo\Pim\Enrichment\Bundle\Command\ValidateProductCommand`
    - `Akeneo\Pim\Enrichment\Bundle\Command\AnalyzeProductCsvCommand`
- Remove class `Akeneo\Pim\Enrichment\Bundle\Command\PurgeCompletenessCommand`
- Remove class `Akeneo\Pim\Enrichment\Bundle\Command\PurgeProductsCompletenessCommand`
- Remove class `Akeneo\Pim\Enrichment\Bundle\Doctrine\ORM\CompletenessRemover`
- Remove class `Akeneo\Pim\Enrichment\Component\Product\Completeness\CompletenessRemoverInterface`
- Change constructor of `Akeneo\Pim\Enrichment\Bundle\Command\CalculateCompletenessCommand` to remove
    `Akeneo\Tool\Component\StorageUtils\Saver\BulkSaverInterface` and add
    `Akeneo\Pim\Enrichment\Bundle\Product\ComputeAndPersistProductCompletenesses` and
    `Akeneo\Pim\Enrichment\Bundle\Elasticsearch\Indexer\ProductIndexerInterface` and
    it does not extend `Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand` anymore
- Update constructor of `Akeneo\Pim\Enrichment\Bundle\Doctrine\ORM\Query\CompleteFilter`, remove `Doctrine\ORM\EntityManagerInterface` and add `Doctrine\DBAL\Connection`
- Remove methods `getCompletenesses` and `setCompletenesses` from `Akeneo\Pim\Enrichment\Component\Product\Model\ProductInterface`
- Replace `Akeneo\Pim\Enrichment\Component\Product\Factory\Write` by `Akeneo\Pim\Enrichment\Component\Product\Factory\Read` with method `createByCheckingData`
- Change constructor of `Akeneo\Platform\Bundle\UIBundle\Imagine\FlysystemLoader` to make `Akeneo\Tool\Component\FileStorage\Repository\FileInfoRepositoryInterface` mandatory
- Change constructor of `Akeneo\Pim\Enrichment\Component\Product\Normalizer\InternalApi\AxisValueLabelsNormalizer\MetricNormalizer` to make `Akeneo\Pim\Enrichment\Component\Product\Normalizer\Standard\Product\MetricNormalizer as StandardMetricNormalizer` and `Akeneo\Pim\Enrichment\Component\Product\Localization\Localizer\MetricLocalizer` mandatory
- Change constructor of `Akeneo\Pim\Enrichment\Bundle\Controller\InternalApi\ProductController` to make `Akeneo\Tool\Bundle\ElasticsearchBundle\Client` mandatory
- Change constructor of `Akeneo\Pim\Enrichment\Bundle\Controller\InternalApi\ProductModelController` to make `Akeneo\Tool\Bundle\ElasticsearchBundle\Client` mandatory
- Change constructor of `Akeneo\Pim\Enrichment\Bundle\PdfGeneration\Renderer\ProductPdfRenderer` to make `Akeneo\Tool\Component\StorageUtils\Repository\IdentifiableObjectRepositoryInterface` mandatory and `string $customFont` becomes the last argument
- Change constructor of `Akeneo\Pim\Enrichment\Component\Product\Validator\Constraints\UniqueVariantAxisValidator` to make `Akeneo\Pim\Enrichment\Component\Product\ProductModel\Query\GetValuesOfSiblings` mandatory and remove `Akeneo\Pim\Enrichment\Component\Product\Repository\EntityWithFamilyVariantRepositoryInterface`
- Replace methods and following interface from `Akeneo\Pim\Enrichment\Bundle\Elasticsearch\Indexer\ProductIndexer` by the single interface `Akeneo\Tool\Component\StorageUtils\Indexer\ProductIndexerInterface` and its new methods:
- Replace interfaces from `Akeneo\Pim\Enrichment\Bundle\Elasticsearch\Indexer\ProductIndexer` by the single interface `Akeneo\Tool\Component\StorageUtils\Indexer\ProductIndexerInterface` and change methods accordingly. Replaced interfaces are:
    - `Akeneo\Tool\Component\StorageUtils\Indexer\IndexerInterface`
    - `Akeneo\Tool\Component\StorageUtils\Indexer\BulkIndexerInterface`
    - `Akeneo\Tool\Component\StorageUtils\Remover\RemoverInterface`
    - `Akeneo\Tool\Component\StorageUtils\Remover\BulkRemoverInterface`
- Class `Akeneo\Pim\Enrichment\Bundle\Elasticsearch\Indexer\ProductIndexer` now implements the single interface `Akeneo\Tool\Component\StorageUtils\Indexer\ProductIndexerInterface` instead of
    `Akeneo\Tool\Component\StorageUtils\Indexer\IndexerInterface`, `Akeneo\Tool\Component\StorageUtils\Indexer\BulkIndexerInterface`, `Akeneo\Tool\Component\StorageUtils\Remover\RemoverInterface` and `Akeneo\Tool\Component\StorageUtils\Remover\BulkRemoverInterface`
- Class `Akeneo\Pim\Enrichment\Bundle\Elasticsearch\Indexer\ProductModelIndexer` now implements the single interface `Akeneo\Tool\Component\StorageUtils\Indexer\ProductModelIndexerInterface` instead of
    `Akeneo\Tool\Component\StorageUtils\Indexer\IndexerInterface`, `Akeneo\Tool\Component\StorageUtils\Indexer\BulkIndexerInterface`, `Akeneo\Tool\Component\StorageUtils\Remover\RemoverInterface` and `Akeneo\Tool\Component\StorageUtils\Remover\BulkRemoverInterface`
- Change constructor of `Akeneo\Pim\Enrichment\Bundle\Elasticsearch\Indexer\ProductModelIndexer` to remove `$indexType`.
- Commands `Akeneo\Pim\Enrichment\Bundle\Command\IndexProductCommand` and `Akeneo\Pim\Enrichment\Bundle\Command\IndexProductModelCommand` do not extend `Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand` anymore
- Remove class `Akeneo\Pim\Enrichment\Bundle\EventSubscriberAddBooleanValuesToNewProductSubscriber`
- Change constructor of `Akeneo\Pim\Enrichment\Component\Product\ValuesFiller\AbstractEntityWithFamilyValuesFiller` to add `Akeneo\Pim\Enrichment\Component\Product\Factory\ValueFactory`
- Change constructor of `Akeneo\Pim\Enrichment\Component\Product\ValuesFiller\EntityWithFamilyVariantValuesFiller` to add `Akeneo\Pim\Enrichment\Component\Product\Factory\ValueFactory`
- Update interface `Akeneo\Pim\Enrichment\Component\Product\Model\ProductInterface` and class `Akeneo\Pim\Enrichment\Component\Product\Model\AbstractProduct`: `setIdentifier` method now takes a `string` as argument.
- Class `Akeneo\Pim\Enrichment\Bundle\Elasticsearch\Indexer\ProductModelDescendantsIndexer` does not implements these interfaces anymore:
    `Akeneo\Tool\Component\StorageUtils\Remover\BulkRemoverInterface` and
    `Akeneo\Tool\Component\StorageUtils\Remover\RemoverInterface` and
    `remove` and `removeAll` methods are removed from this class
- Update class `Akeneo\Pim\Enrichment\Bundle\Elasticsearch\Indexer\ProductModelDescendantsIndexer`:
    - Remove implementation of `Akeneo\Tool\Component\StorageUtils\Indexer\IndexerInterface`
    - Remove implementation of `Akeneo\Tool\Component\StorageUtils\Indexer\BulkIndexerInterface`
    - Adds parameter `Akeneo\Pim\Enrichment\Component\Product\Repository\ProductModelRepositoryInterface` 
- Change constructor of `Akeneo\Pim\Enrichment\Bundle\Elasticsearch\Indexer\ProductModelIndexer` to 
    - remove `Akeneo\Pim\Enrichment\Component\Product\Repository\ProductModelRepositoryInterface`
    - remove `Symfony\Component\Serializer\Normalizer\NormalizerInterface` 
    - add `Akeneo\Pim\Enrichment\Bundle\Elasticsearch\GetElasticsearchProductModelProjectionInterface`
- Remove interface `Akeneo\Pim\Enrichment\Component\Product\Model\CompletenessInterface`, and its implementations `Akeneo\Pim\Enrichment\Component\Product\Model\AbstractCompleteness` and `Akeneo\Pim\Enrichment\Component\Product\Model\Completeness`
- Remove class `Akeneo\Pim\Enrichment\Component\Product\Normalizer\InternalApi\IncompleteValuesNormalizer`
- Remove classes `Akeneo\Pim\Enrichment\Component\Product\EntityWithFamily\RequiredValue`, `Akeneo\Pim\Enrichment\Component\Product\EntityWithFamily\RequiredValueCollection`, `Akeneo\Pim\Enrichment\Component\Product\EntityWithFamily\RequiredValueCollectionFactory`,
    `Akeneo\Pim\Enrichment\Component\Product\EntityWithFamily\IncompleteValueCollection` and `Akeneo\Pim\Enrichment\Component\Product\EntityWithFamily\IncompleteValueCollectionFactory` 
- Change constructor of `Akeneo\Pim\Enrichment\Bundle\PdfGeneration\Renderer` to add `Akeneo\Tool\Component\StorageUtils\Repository\IdentifiableObjectRepositoryInterface` (attribute option repository)
- Remove class `Akeneo\Pim\Enrichment\Bundle\Doctrine\ORM\Query\CompleteFilter`
- Remove class `Akeneo\Pim\Enrichment\Component\Product\Normalizer\Indexing\ProductAndProductModel\ProductModelNormalizer`
- Remove class `Akeneo\Pim\Enrichment\Component\Product\Normalizer\Indexing\ProductAndProductModel\PRoductModelPropertiesNormalizer`
- Remove class `Akeneo\Pim\Enrichment\Component\Product\ProductAndProductModel\Query\CompleteFilterData`
- Remove interface `Akeneo\Pim\Enrichment\Component\Product\ProductAndProductModel\Query\CompleteFilterInterface`
- Remove class `Akeneo\Pim\Enrichment\Bundle\EventSubscriber\ComputeProductModelDescendantsSubscriber` and replace it by
    - `Akeneo\Pim\Enrichment\Bundle\EventSubscriber\ProductModel\OnSave\ComputeProductAndAncestorsSubscriber` and
    - `Akeneo\Pim\Enrichment\Bundle\EventSubscriber\ProductModel\OnDelete\ComputeProductAndAncestorsSubscriber`
- Remove class `Akeneo\Pim\Enrichment\Bundle\EventSubscriber\IndexProductModelsSubscriber` and replace it by
    - `Akeneo\Pim\Enrichment\Bundle\EventSubscriber\ProductModel\OnSave\ComputeProductAndAncestorsSubscriber` and
    - `Akeneo\Pim\Enrichment\Bundle\EventSubscriber\ProductModel\OnDelete\ComputeProductAndAncestorsSubscriber`
- Move class from `Akeneo\Pim\Enrichment\Bundle\EventSubscriber\LocalizableSubscriber` to `Akeneo\Pim\Enrichment\Bundle\EventSubscriber\AttributeOption\LocalizableSubscriber` and mark it as final
- Move class from `Akeneo\Pim\Enrichment\Bundle\EventSubscriber\Category\CheckChannelsOnDeletionSubscriber` to `Akeneo\Pim\Enrichment\Bundle\EventSubscriber\Category\OnDelete\CheckChannelsOnDeletionSubscriber` and mark it as final
- Move class from `Akeneo\Pim\Enrichment\Bundle\EventSubscriber\Storage\RemoveCategoryFilterInJobInstanceSubscriber` to `Akeneo\Pim\Enrichment\Bundle\EventSubscriber\Category\OnDelete\RemoveCategoryFilterInJobInstanceSubscriber`
- Move class from `Akeneo\Pim\Enrichment\Bundle\EventSubscriber\UpdateIndexesOnCategoryDeletion` to `Akeneo\Pim\Enrichment\Bundle\EventSubscriber\Category\OnDelete\UpdateIndexesOnCategoryDeletion`
- Move class from `Akeneo\Pim\Enrichment\Bundle\EventSubscriber\ConfigureCategoryTreeForExportJobsAfterChangingTheChannelCategoryTree` to `Akeneo\Pim\Enrichment\Bundle\EventSubscriber\Category\OnSave\ConfigureCategoryTreeForExportJobsAfterChangingTheChannelCategoryTree`
- Move class from `Akeneo\Pim\Enrichment\Bundle\EventSubscriber\InitCompletenessDbSchemaSubscriber` to `Akeneo\Pim\Enrichment\Bundle\EventSubscriber\Db\InitCompletenessDbSchemaSubscriber`
- Move class from `Akeneo\Pim\Enrichment\Bundle\EventSubscriber\ComputeEntityRawValuesSubscriber` to `Akeneo\Pim\Enrichment\Bundle\EventSubscriber\EntityWithValues\ComputeEntityRawValuesSubscriber` and mark it as final
- Move class from `Akeneo\Pim\Enrichment\Bundle\EventSubscriber\LoadEntityWithValuesSubscriber` to `Akeneo\Pim\Enrichment\Bundle\EventSubscriber\EntityWithValues\LoadEntityWithValuesSubscriber` and mark it as final
- Move class from `Akeneo\Pim\Enrichment\Bundle\EventSubscriber\ComputeCompletenessOnFamilyUpdateSubscriber` to `Akeneo\Pim\Enrichment\Bundle\EventSubscriber\Family\ComputeCompletenessOnFamilyUpdateSubscriber` and mark it as final
- Remove class `Akeneo\Pim\Enrichment\Bundle\EventSubscriber\ComputeProductModelDescendantsSubscriber`
- Remove class `Akeneo\Pim\Enrichment\Bundle\EventSubscriber\IndexProductModelsSubscriber`
- Update class `Akeneo\Pim\Enrichment\Component\Product\Connector\Writer\Database\MassEdit\ProductAndProductModelWriter` to remove
    - `Akeneo\Tool\Bundle\BatchBundle\Launcher\JobLauncherInterface`,
    - `Akeneo\Tool\Component\StorageUtils\Repository\IdentifiableObjectRepositoryInterface`,
    - `Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface` and
    - `string $jobName`
- Remove class `Akeneo\Pim\Enrichment\Bundle\Doctrine\Common\Saver\ProductModelDescendantsSaver`
- Remove class `Akeneo\Pim\Enrichment\Component\Product\Job\ComputeProductModelsDescendantsTasklet`
- Remove class `Akeneo\Pim\Enrichment\Bundle\EventSubscriber\IndexProductsSubscriber`,
    replaced by `Akeneo\Pim\Enrichment\Bundle\EventSubscriber\Product\OnSave\ComputeProductsAndAncestorsSubscriber`
    and `Akeneo\Pim\Enrichment\Bundle\EventSubscriber\Product\OnDelete\ComputeProductsAndAncestorsSubscriber`
- Move class from `Akeneo\Pim\Enrichment\Bundle\Doctrine\ORM\Query\AscendantCategories` to `Akeneo\Pim\Enrichment\Bundle\Storage\Sql\Category\AscendantCategories` and mark it as final
- Move class from `Akeneo\Pim\Enrichment\Bundle\Doctrine\ORM\Query\AttributeIsAFamilyVariantAxis` to `Akeneo\Pim\Enrichment\Bundle\Storage\Sql\Attribute\AttributeIsAFamilyVariantAxis`
- Move class from `Akeneo\Pim\Enrichment\Bundle\Doctrine\ORM\Query\CountEntityWithFamilyVariant` to `Akeneo\Pim\Enrichment\Bundle\Storage\Sql\Family\CountEntityWithFamilyVariant` and mark it as final
- Move class from `Akeneo\Pim\Enrichment\Bundle\Doctrine\ORM\Query\CountImpactedProducts` to `Akeneo\Pim\Enrichment\Bundle\Storage\Sql\ProductGrid\CountImpactedProducts`
- Move class from `Akeneo\Pim\Enrichment\Bundle\Doctrine\ORM\Query\CountProductsWithFamily` to `Akeneo\Pim\Enrichment\Bundle\Storage\Sql\Family\CountProductsWithFamily` and mark it as final
- Move class from `Akeneo\Pim\Enrichment\Bundle\Doctrine\ORM\Query\DescendantProductIdsQuery` to `Akeneo\Pim\Enrichment\Bundle\Storage\Sql\ProductModel\DescendantProductIdsQuery` and mark it as final
- Move class from `Akeneo\Pim\Enrichment\Bundle\Doctrine\ORM\Query\DescendantProductModelIdsQuery` to `Akeneo\Pim\Enrichment\Bundle\Storage\Sql\ProductModel\DescendantProductModelIdsQuery` and mark it as final
- Move class from `Akeneo\Pim\Enrichment\Bundle\Doctrine\ORM\Query\FindAttributesForFamily` to `Akeneo\Pim\Enrichment\Bundle\Storage\Sql\Family\FindAttributesForFamily`
- Move class from `Akeneo\Pim\Enrichment\Bundle\Doctrine\ORM\Query\GetAssociatedProductCodesByProductFromDB` to `Akeneo\Pim\Enrichment\Bundle\Storage\Sql\Product\GetAssociatedProductCodesByProductFromDB` and mark it as final
- Move class from `Akeneo\Pim\Enrichment\Bundle\Doctrine\ORM\Query\GetAttributeOptionsMaxSortOrder` to `Akeneo\Pim\Enrichment\Bundle\Storage\Sql\Attribute\GetAttributeOptionsMaxSortOrder`
- Move class from `Akeneo\Pim\Enrichment\Bundle\Doctrine\ORM\Query\GetDescendentCategoryCodes` to `Akeneo\Pim\Enrichment\Bundle\Storage\Sql\Category\GetDescendentCategoryCodes`
- Move class from `Akeneo\Pim\Enrichment\Bundle\Doctrine\ORM\Query\SqlGetValuesOfSiblings` to `Akeneo\Pim\Enrichment\Bundle\Storage\Sql\Product\SqlGetValuesOfSiblings`
- Move class from `Akeneo\Pim\Enrichment\Bundle\Doctrine\ORM\Query\VariantProductRatio` to `Akeneo\Pim\Enrichment\Bundle\Storage\Sql\Completeness\VariantProductRatio` and mark it as final
- Move class from `Akeneo\Pim\Enrichment\Bundle\Product\Query\Sql\CountVariantProducts` to `Akeneo\Pim\Enrichment\Bundle\Storage\Sql\ProductModel\CountVariantProducts`
- Move class from `Akeneo\Pim\Enrichment\Bundle\Storage\Sql\Attribute\AttributeIsAFamilyVariantAxis` to `Akeneo\Pim\Structure\Bundle\Query\PublicApi\Attribute\Sql\AttributeIsAFamilyVariantAxis`

### CLI Commands

The following CLI commands were deleted:
- `pim:product:create`
- `pim:product:get`
- `pim:product:query`
- `pim:product:remove`
- `pim:product:validate`
- `pim:product:update`
- `pim:catalog:remove-wrong-boolean-values-on-variant-products-batch`
- `pim:catalog:remove-wrong-boolean-values-on-variant-products`
- `pim:objects:validate`
- `pim:connector:analyzer:csv-products`
- `pim:completeness:purge`
- `pim:completeness:purge-products`

If you want to purge the completeness in order to recalculate it, please use the dedicated command `pim:completeness:calculate`

### Services

- Update `pim_catalog.factory.product_cursor` to use `akeneo_elasticsearch.client.product_and_product_model`
- Update `pim_catalog.factory.product_search_after_size_cursor` to use `akeneo_elasticsearch.client.product_and_product_model`
- Update `pim_catalog.factory.product_from_size_cursor` to use `akeneo_elasticsearch.client.product_and_product_model`
- Update `pim_catalog.factory.product_model_cursor` to use `akeneo_elasticsearch.client.product_and_product_model`
- Update `pim_catalog.factory.product_model_from_size_cursor` to use `akeneo_elasticsearch.client.product_and_product_model`
- Update `pim_catalog.factory.product_model_search_after_size_cursor` to use `akeneo_elasticsearch.client.product_and_product_model`
- Update `akeneo.pim.enrichment.factory.product_identifier_cursor_from_size_cursor` to use `akeneo_elasticsearch.client.product_and_product_model`
- Update `akeneo.pim.enrichment.factory.product_identifier_cursor_search_after_size_cursor` to use `akeneo_elasticsearch.client.product_and_product_model`
- Update `akeneo.pim.enrichment.factory.product_model_identifier_cursor_search_after_size_cursor` to use `akeneo_elasticsearch.client.product_and_product_model`
- Remove `akeneo_elasticsearch.client.product` from `pim_catalog.elasticsearch.indexer.product`
- Remove `akeneo_elasticsearch.client.product` and `akeneo_elasticsearch.client.product_model` from `pim_catalog.elasticsearch.indexer.product_model`
- Update `akeneo.pim.enrichment.follow_up.completeness_widget_query` to use `akeneo_elasticsearch.client.product_and_product_model`
- Remove `akeneo_elasticsearch.client.product` and `akeneo_elasticsearch.client.product_model` from `pim_catalog.event_subscriber.category.update_indexes_on_category_deletion`
- Update `akeneo.pim.enrichment.category.category_tree.query.list_root_categories_with_count_including_sub_categories` to use `akeneo_elasticsearch.client.product_and_product_model`
- Update `akeneo.pim.enrichment.category.category_tree.query.list_root_categories_with_count_not_including_sub_categories` to use `akeneo_elasticsearch.client.product_and_product_model`
- Update `akeneo.pim.enrichment.category.category_tree.query.list_children_categories_with_count_including_sub_categories` to use `akeneo_elasticsearch.client.product_and_product_model`
- Update `akeneo.pim.enrichment.category.category_tree.query.list_children_categories_with_count_not_including_sub_categories` to use `akeneo_elasticsearch.client.product_and_product_model`
- Update `pim_catalog.query.product_model_query_builder_search_after_size_factory_external_api` to use `Akeneo\Pim\Enrichment\Component\Product\Query\ProductModelQueryBuilder`
- Update `pim_catalog.query.product_model_query_builder_from_size_factory_external_api` to use `Akeneo\Pim\Enrichment\Component\Product\Query\ProductModelQueryBuilder`
- Update `pim_catalog.query.product_model_query_builder_factory` to use `pim_catalog.query.product_model_query_builder`
- Update `pim_catalog.query.product_model_query_builder_from_size_factory` to use `pim_catalog.query.product_model_query_builder`
- Update `pim_catalog.query.product_model_query_builder_search_after_size_factory` to use `pim_catalog.query.product_model_query_builder`
- Remove `pim_catalog.query.product_and_product_model_query_builder_factory.with_product_and_product_model_cursor`
- Update `akeneo.pim.enrichment.query.product_query_builder_from_size_factory.with_product_identifier_cursor` to use `Akeneo\Pim\Enrichment\Bundle\Elasticsearch\ProductAndProductModelQueryBuilderFactory`
- Update `pim_enrich.query.product_query_builder_from_size_factory.with_product_and_product_model_from_size_cursor` to use `pim_catalog.query.elasticsearch.product_and_model_query_builder_factory`
- Update `pim_enrich.query.product_query_sequential_edit_builder_factory` to use `pim_catalog.query.product_and_product_model_query_builder.class`
- Rename `pim_versioning.event_subscriber.addversion` to `pim_versioning.event_listener.addversion`
- Rename `pim_user.event_listener.user_preferences` to `pim_user.event_subscriber.user_preferences`
- Remove `pim_enrich.normalizer.completeness` (use `pim_enrich.normalizer.product_completeness` instead)
- Remove `pim_enrich.normalizer.completeness_collection` (use `pim_enrich.normalizer.product_completeness_collection` instead)
- Remove `pim_catalog.normalizer.indexing_product.product.completeness_collection` (use `pim_catalog.normalizer.indexing_product.product.product_completeness_collection` instead)
- Update service `pim_pdf_generator.renderer.product_pdf` to use `pim_catalog.repository.cached_attribute_option` as the 9th argument and `pim_pdf_generator_font` as the 10th
- Update service `pim_catalog.validator.constraint.unique_variant_axes` to remove `pim_catalog.repository.entity_with_family_variant`
- Remove duplicated service definitions for `pim_catalog.validator.constraint.family_variant_axes` and `pim_catalog.validator.constraint.immutable_family_variant_axes` in `src/Akeneo/Pim/Enrichment/Bundle/Resources/cofig/validators.yml`
- Remove `pim_catalog.event_subscriber.add_boolean_values_to_new_product`
- Remove `pim_enrich.normalizer.incomplete_values`
- Remove `pim_catalog.entity_with_family.required_value_collection_factory` and `pim_catalog.entity_with_family.incomplete_value_collection_factory`
- Remove `pim_catalog.event_subscriber.compute_product_model_descendants`
- Remove `pim_catalog.event_subscriber.index_product_models`
- Remove `pim_catalog.job.job_parameters.default_values_provider.compute_product_models_descendants`
- Remove `pim_catalog.tasklet.compute_product_models_descendants`
- Remove `pim_catalog.step.compute_product_models_descendants`
- Remove `pim_catalog.job.compute_product_models_descendants`
- Remove `pim_connector.step.csv_compute_product_models_descendants.import` from `pim_connector.job.csv_product_model_import`
- Remove `pim_connector.step.xlsx_compute_product_models_descendants.import` from `pim_connector.job.xlsx_product_model_import`
- Remove `pim_connector.processor.denormalization.product_model_loader`
- Remove `pim_catalog.saver.product_model_descendants`
- Remove `pim_connector.step.csv_compute_product_models_descendants.import`
- Remove `pim_connector.step.csv_compute_product_models_descendants.import` from `pim_installer.job.fixtures_product_model_csv`
- Remove `pim_connector.step.xlsx_compute_product_models_descendants.import`
- Remove `pim_connector.writer.database.product_model_descendants`
- Remove from `pim_enrich.writer.database.product_and_product_model_writer`:
    - `security.token_storage`
    - `akeneo_batch_queue.launcher.queue_job_launcher`
    - `akeneo_batch.job.job_instance_repository`
    - `string $jobName`
- Remove `pim_catalog.event_subscriber.index_products`
- Rename service `pim_catalog.doctrine.query.attribute_is_an_family_variant_axis` in `akeneo.pim.structure.query.attribute_is_an_family_variant_axis`
