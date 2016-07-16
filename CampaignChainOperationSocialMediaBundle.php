<?php

namespace CampaignChain\Operation\SocialMediaBundle;

use CampaignChain\Operation\SocialMediaBundle\DependencyInjection\CampaignChainOperationSocialMediaExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CampaignChainOperationSocialMediaBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new CampaignChainOperationSocialMediaExtension();
    }
}
