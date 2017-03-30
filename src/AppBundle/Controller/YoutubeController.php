<?php
/**
 * Created by PhpStorm.
 * User: Thuan
 * Date: 28/03/2017
 * Time: 20:36
 */


namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Application\Sonata\MediaBundle\Entity\Media;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

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

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Media::getEntityName());
        $medias = $repository->findAll();
        foreach ($medias as $media) {
            if($media->getProviderName() == "sonata.media.provider.youtube" && $media->getDuration() == null)
            {
                $video = $media->getProviderReference();

                $url = sprintf('https://www.googleapis.com/youtube/v3/videos?id=%s&key=%s&part=contentDetails',$video,$apiKey);
				var_dump($url);
                try {
                    $ch = curl_init();

                    if (FALSE === $ch)
                        throw new Exception('failed to initialize');

                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json')); // Assuming you're requesting JSON
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    $response = curl_exec($ch);

                    if (FALSE === $response)
                        echo(curl_error($ch));

                    $data = json_decode($response, true);
			
					$media->setDuration($this->covtime($data['items'][0]['contentDetails']['duration']));

					$em->persist($media);
					$em->flush();
					

                } catch(Exception $e) {
                    trigger_error(sprintf(
                        'Curl failed with error #%d: %s',
                        $e->getCode(), $e->getMessage()),
                        E_USER_ERROR);

                }
            }
        }

        return new Response('Success');

    }

    // convert youtube v3 api duration e.g. PT1M3S to HH:MM:SS
    function covtime($yt){
        $yt=str_replace('PT','',$yt);
        foreach(['H','M','S'] as $a){
            $pos=strpos($yt,$a);
            if($pos!==false) ${$a}=substr($yt,0,$pos); else { ${$a}=0; continue; }
            $yt=substr($yt,$pos+1);
        }
        if($H>0){
            $M=str_pad($M,2,'0',STR_PAD_LEFT);
            $S=str_pad($S,2,'0',STR_PAD_LEFT);
            return "$H:$M:$S";
        } else {
            $S=str_pad($S,2,'0',STR_PAD_LEFT);
            return "$M:$S";
        }
    }
}