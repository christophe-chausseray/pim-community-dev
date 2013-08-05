<?php

namespace Pim\Bundle\BatchBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\Common\Util\Inflector;

/**
 * Form type for step element configuration
 *
 * @author    Gildas Quemener <gildas.quemener@gmail.com>
 * @copyright 2013 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class StepElementConfigurationType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $factory = $builder->getFormFactory();

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($factory) {
                $form   = $event->getForm();
                $stepElement = $event->getData();

                foreach ($stepElement->getConfigurationFields() as $field => $config) {
                    $config = array_merge(
                        array(
                            'type' => 'text',
                            'options' => array(),
                        ),
                        $config
                    );
                    $options = array_merge(
                        array(
                            'auto_initialize' => false,
                            'required'        => false,
                            'attr'            => array(
                                'help' => sprintf(
                                    'pim_batch.%s.%s.help',
                                    $this->getTableizedClassName($stepElement),
                                    $this->tableize($field)
                                )
                            )
                        ),
                        $config['options']
                    );

                    $form->add($factory->createNamed($field, $config['type'], null, $options));
                }
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Pim\\Bundle\\ImportExportBundle\\AbstractConfigurableStepElement',
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'pim_batch_step_element_configuration';
    }

    private function getTableizedClassName($object)
    {
        $classname = get_class($object);

        if (preg_match('@\\\\([\w]+)$@', $classname, $matches)) {
            $classname = $matches[1];
        }

        return $this->tableize($classname);
    }

    private function tableize($string)
    {
        return Inflector::tableize($string);
    }
}
