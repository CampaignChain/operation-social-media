<?php
/*
 * This file is part of the CampaignChain package.
 *
 * (c) CampaignChain, Inc. <info@campaignchain.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CampaignChain\Operation\SocialMediaBundle\Job;

use Doctrine\ORM\EntityManager;
use CampaignChain\CoreBundle\Job\JobActionInterface;
use Guzzle\Http\Client;
use GuzzleHttp\Exception\RequestException;

class SocialMediaSchedule implements JobActionInterface
{
    protected $em;
    protected $container;
    protected $client;

    protected $message;

    public function __construct(EntityManager $em, $container)
    {
        $this->em = $em;
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