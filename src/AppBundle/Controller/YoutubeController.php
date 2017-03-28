<?php
/**
 * Created by PhpStorm.
 * User: Thuan
 * Date: 28/03/2017
 * Time: 20:36
 */


namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Sonata\MediaBundle\Model\Media;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use GuzzleHttp;

class YoutubeController extends Controller
{
    /**
     * @Route("/youtube", name="youtube")
     */
    public function youtubeAction() {

        $apiKey = "AIzaSyAyODj2dNyWdrTZia1DKPKVaiVZWpSie7Q"; // Change this line.
        // Warn if the API key isn't changed.
        if (strpos($apiKey, "<") !== false) {
            echo missingApiKeyWarning();
            exit;
        }
        $client = new Client();

        $em = $this->getDoctrine()->getManager();
        //$words = $em->getRepository(Media::getEntityName())->findPossibleWords($word);

        $video = 'JptwkEhdNfY';
        $format = 'https://www.googleapis.com/youtube/v3/videos?id=%s&key=%s&part=contentDetails';
        // send an asynchronous request.

        $request = new GuzzleHttp\Psr7\Request('GET', sprintf($format, $video, $apiKey));
        $promise = $client->sendAsync($request)->then(function ($response) {
            echo 'I completed! ' . $response->getBody();
        });
        $promise->wait();
        //$request = $client->createRequest('GET', sprintf($format, $video, $apiKey), ['future' => true]);

        // callback
//        $client->send($request)->then(function ($response) {
//            echo 'I completed! ';
//            $json = $response->json();
//        });
    }
}