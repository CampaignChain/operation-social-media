<?php
/*
 * Copyright 2016 CampaignChain, Inc. <info@campaignchain.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace CampaignChain\Operation\SocialMediaBundle\EntityService;

use Doctrine\ORM\EntityManager;
use CampaignChain\CoreBundle\EntityService\OperationServiceInterface;
use CampaignChain\CoreBundle\Entity\Operation;

class SocialMediaSchedule implements OperationServiceInterface
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getSocialMediaScheduleByOperation($id){
        $status = $this->em->getRepository('CampaignChainOperationSocialMediaBundle:SocialMediaSchedule')
            ->findOneByOperation($id);

        if (!$status) {
            throw new \Exception(
                'No SocialMediaSchedule found by operation id '.$id
            );
        }

        return $status;
    }

    public function cloneOperation(Operation $oldOperation, Operation $newOperation)
    {
        $status = $this->getSocialMediaScheduleByOperation($oldOperation);
        $clonedStatus = clone $status;
        $clonedStatus->setOperation($newOperation);
        $this->em->persist($clonedStatus);
        $this->em->flush();
    }

    public function removeOperation($id){
        try {
            $operation = $this->getSocialMediaScheduleByOperation($id);
            $this->em->remove($operation);
            $this->em->flush();
        } catch (\Exception $e) {

        }
    }
}