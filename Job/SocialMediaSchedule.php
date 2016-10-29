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

namespace CampaignChain\Operation\SocialMediaBundle\Job;

use Doctrine\Common\Persistence\ManagerRegistry;
use CampaignChain\CoreBundle\Job\JobActionInterface;
use Guzzle\Http\Client;
use GuzzleHttp\Exception\RequestException;

class SocialMediaSchedule implements JobActionInterface
{
    protected $em;
    protected $container;
    protected $client;

    protected $message;

    public function __construct(ManagerRegistry $managerRegistry, $container)
    {
        $this->em = $managerRegistry->getManager();
        $this->container = $container;
        $this->client = new Client('http://127.0.0.1:8000/app_dev.php/api/v1/p/');
    }

    public function execute($operationId)
    {
        $operationService = $this->container->get('campaignchain.core.operation');
        $operation = $operationService->getOperation($operationId);

        $body['activity']['name'] = $operation->getActivity()->getName();
        $body['activity']['campaign'] = $operation->getActivity()->getCampaign()->getId();
        $body['activity']['parent'] = $operation->getActivity()->getId();

        $socialMediaScheduleService = $this->container->get('campaignchain.operation.social_media.schedule');
        $status = $socialMediaScheduleService->getSocialMediaScheduleByOperation($operation);

        foreach($status->getLocations() as $location){
            $body['activity']['location'] = $location->getId();

            switch($location->getLocationModule()->getBundle()->getName()){
                case 'campaignchain/location-twitter':
                    $body['activity']['campaignchain_twitter_update_status'] = array(
                        'message' => $status->getMessage()
                    );
                    $this->post('campaignchain/activity-twitter/statuses', $body);
                    unset($body['activity']['campaignchain_twitter_update_status']);
                    break;
                case 'campaignchain/location-facebook':
                    break;
                case 'campaignchain/location-linkedin':
                    break;
            }
        }

//        $this->message = 'The message "'.$params['message'].'" with the ID "'.$response['id'].'" has been posted on Facebook';
//        if($status instanceof UserStatus){
//            $this->message .= ' with privacy setting "'.$privacy['value'].'"';
//        }
//        $this->message .= '. See it on Facebook: <a href="'.$statusURL.'">'.$statusURL.'</a>';

        return self::STATUS_OK;
    }

    public function post($uri, $body)
    {
        try {
            $request = $this->client->post(
                $uri,
                array(
                    'headers' => array('Content-Type' => 'application/json'),
                    'body' => json_encode($body),
                )
            );
            dump($request->getResponseBody());
            exit;
        } catch (RequestException $e) {
            echo $e->getRequest() . "\n";
            if ($e->hasResponse()) {
                echo $e->getResponse() . "\n";
            }
            die();
        }
    }

    public function getMessage()
    {
        return $this->message;
    }
}