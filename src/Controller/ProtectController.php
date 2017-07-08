<?php
namespace Stauth\Controller;

use GuzzleHttp\Exception\ClientException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Request;

class ProtectController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function restrictedAction()
    {
        return $this->render('StauthProtectionBundle::protected.html.twig', [
            'csrfToken' => $this->container->get('form.csrf_provider')->generateCsrfToken('ajax')
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function authorizeAction(Request $request)
    {
        $jwt = $request->request->get('token');
        $client = new Client();
        try {
            $response = $client->post('https://www.stauth.io/api/authorize', [
                'headers' => [
                    'Referer' => $request->getUri(),
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Accept' => 'application/json',
                    'Authorization' => sprintf('Bearer %s', $this->getParameter('stauth.access_token'))
                ],
                'query' => [
                    'token' => $jwt,
                ]
            ]);
        } catch (ClientException $e) {
            $response = $e->getResponse();
        }
        if ($response->getStatusCode() === 200) {
            $this->get('session')->set('stauth-authorized', 1);
        }

        return new JsonResponse([$response->getBody()->getContents()], $response->getStatusCode());
    }
}