<?php

namespace CampaignChain\Operation\SocialMediaBundle\Form\Type;

use CampaignChain\CoreBundle\Form\Type\OperationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use CampaignChain\CoreBundle\Entity\Medium;

class SocialMediaScheduleOperationType extends OperationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('locations', 'entity', array(
                'label' => false,
                'multiple' => true,
                'class' => 'CampaignChainCoreBundle:Location',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('location')
                        ->where('location.status != :status_unpublished AND location.status != :status_inactive')
                        ->andWhere('location.parent IS NULL')
                        ->andWhere( // Skip Locations that don't provide Activities.
                            'EXISTS ('
                            .'SELECT channelModule.id FROM '
                            .'CampaignChain\CoreBundle\Entity\Channel channel, '
                            .'CampaignChain\CoreBundle\Entity\ChannelModule channelModule '
                            .'WHERE '
                            .'location.channel = channel.id AND '
                            .'channel.channelModule = channelModule.id AND '
                            .'SIZE(channelModule.activityModules) != 0'
                            .')'
                        )
                        ->from('CampaignChain\CoreBundle\Entity\Channel', 'channel2')
                        ->from('CampaignChain\CoreBundle\Entity\ChannelModule', 'channelModule2')
                        ->from('CampaignChain\CoreBundle\Entity\Bundle', 'bundle')
                        ->andWhere( // Select only Twitter, Facebook, Linkedin Locations.
                            'location.channel = channel2.id AND '
                            .'channel2.channelModule = channelModule2.id AND '
                            .'channelModule2.bundle = bundle.id AND '
                            .'('
                                .'bundle.name = :facebook_bundle OR '
                                .'bundle.name = :twitter_bundle OR '
                                .'bundle.name = :linkedin_bundle'
                            .')'
                        )
                        ->orderBy('location.name', 'ASC')
                        ->setParameter('status_unpublished', Medium::STATUS_UNPUBLISHED)
                        ->setParameter('status_inactive', Medium::STATUS_INACTIVE)
                        ->setParameter('facebook_bundle', 'campaignchain/channel-facebook')
                        ->setParameter('twitter_bundle', 'campaignchain/channel-twitter')
                        ->setParameter('linkedin_bundle', 'campaignchain/channel-linkedin');
                },
                'choice_label' => 'name',
                'placeholder' => 'Select a Location',
                'empty_data' => null,
                'attr' => array(
                    'show_image' => true,
                    'placeholder' => 'Select one or more Locations',
                )
            ));
        $builder
            ->add('message', 'textarea', array(
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Compose message...',
                    'help_text' => 'Character limits: Twitter 140, Facebook 2,000, Linkedin 200. Facebook posts will be public.',
                    'count_chars' => true,
                ),
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $defaults = array(
            'data_class' => 'CampaignChain\Operation\SocialMediaBundle\Entity\SocialMediaSchedule',
        );

        if($this->content){
            $defaults['data'] = $this->content;
        }
        $resolver->setDefaults($defaults);
    }

    public function getName()
    {
        return 'campaignchain_operation_social_media_schedule';
    }
}
