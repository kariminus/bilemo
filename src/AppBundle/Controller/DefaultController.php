<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Phone;
use AppBundle\Form\PhoneType;
use AppBundle\Form\UpdatePhoneType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


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
            'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/api/phones")
     * @Method("POST")
     */
    public function newAction(Request $request)
    {
        return $this->get('manage_phone')->phoneNew($request);
    }

    /**
     * @Route("/api/phones/{name}", name="api_phones_show")
     * @Method("GET")
     */
    public function showAction($name)
    {
        return $this->get('manage_phone')->phoneShow($name);
    }

    /**
     * @Route("/api/phones")
     * @Method("GET")
     */
    public function listAction()
    {
        return $this->get('manage_phone')->phoneList();
    }

    /**
     * @Route("/api/phones/{name}")
     * @Method({"PUT", "PATCH"})
     */
    public function updateAction($name, Request $request)
    {
        return $this->get('manage_phone')->phoneUpdate($request, $name);
    }

    /**
     * @Route("/api/phones/{name}")
     * @Method("DELETE")
     */
    public function deleteAction($name)
    {
        return $this->get('manage_phone')->phoneDelete($name);
    }

}
