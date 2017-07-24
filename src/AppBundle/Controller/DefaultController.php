<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Phone;
use AppBundle\Form\PhoneType;
use AppBundle\Form\UpdatePhoneType;
use AppBundle\Repository\PhoneRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * @Route(name="default_controller")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/api/phones")
     * @Method("POST")
     */
    public function newAction(Request $request)
    {
        $phone = new Phone();
        $form = $this->createForm(PhoneType::class, $phone);
        $this->processForm($request, $form);

        $em = $this->getDoctrine()->getManager();
        $em->persist($phone);
        $em->flush();

        $response = $this->createApiResponse($phone, 201);
        $phoneUrl = $this->generateUrl(
            'api_phones_show',
            ['name' => $phone->getName()]
        );
        $response->headers->set('Location', $phoneUrl);

        return $response;
    }

    /**
     * @Route("/api/phones/{name}", name="api_phones_show")
     * @Method("GET")
     */
    public function showAction($name)
    {
        $phone = $this->getDoctrine()
            ->getRepository('AppBundle:Phone')
            ->findOneByName($name);

        if (!$phone) {
            throw $this->createNotFoundException(sprintf(
                'No phone found with name "%s"',
                $name
            ));
        }

        $response = $this->createApiResponse($phone, 200);

        return $response;
    }

    /**
     * @Route("/api/phones")
     * @Method("GET")
     */
    public function listAction()
    {
        $phones = $this->getDoctrine()
            ->getRepository('AppBundle:Phone')
            ->findAll();

        $response = $this->createApiResponse(['phones' => $phones], 200);

        return $response;
    }

    /**
     * @Route("/api/phones/{name}")
     * @Method({"PUT", "PATCH"})
     */
    public function updateAction($name, Request $request)
    {
        $phone = $this->getDoctrine()
            ->getRepository('AppBundle:Phone')
            ->findOneByName($name);

        if (!$phone) {
            throw $this->createNotFoundException(sprintf(
                'No phone found with name "%s"',
                $name
            ));
        }

        $form = $this->createForm(UpdatePhoneType::class, $phone);
        $this->processForm($request, $form);

        $em = $this->getDoctrine()->getManager();
        $em->persist($phone);
        $em->flush();

        $response = $this->createApiResponse($phone, 200);

        return $response;
    }

    /**
     * @Route("/api/phones/{name}")
     * @Method("DELETE")
     */
    public function deleteAction($name)
    {
        $phone = $this->getDoctrine()
            ->getRepository('AppBundle:Phone')
            ->findOneByName($name);

        if ($phone) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($phone);
            $em->flush();
        }

        return new Response(null, 204);
    }


    private function processForm(Request $request, FormInterface $form)
    {
        $data = json_decode($request->getContent(), true);

        $clearMissing = $request->getMethod() != 'PATCH';
        $form->submit($data, $clearMissing);
    }

    protected function createApiResponse($data, $statusCode = 200)
    {
        $json = $this->serializer($data);

        return new Response($json, $statusCode, array(
            'Content-Type' => 'application/json'
        ));
    }

    public function serializer($data)
    {
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $json = $serializer->serialize($data, 'json');
        return $json;
    }
}
