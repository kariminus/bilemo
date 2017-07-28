<?php

namespace AppBundle\Phone;

use AppBundle\Api\ManageApi;
use AppBundle\Entity\Phone;
use AppBundle\Form\PhoneType;
use AppBundle\Form\UpdatePhoneType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class ManagePhone
{

    /**
     * ManagePhone constructor.
     */
    public function __construct(EntityManager $em, $formFactory, ManageApi $manageApi, $router)
    {
        $this->em = $em;
        $this->formFactory = $formFactory;
        $this->manageApi = $manageApi;
        $this->router = $router;

    }

    public function phoneIndex()
    {

    }

    public function phoneNew($request)
    {
        $phone = new Phone();
        $form = $this->formFactory->create(PhoneType::class, $phone);
        $this->manageApi->processForm($request, $form);

        if (!$form->isValid()) {
            $this->manageApi->throwApiProblemException($form);
        }

        $this->em->persist($phone);
        $this->em->flush();

        $response = $this->manageApi->createApiResponse($phone, 201);
        $phoneUrl = $this->router->generate(
            'api_phones_show',
            ['name' => $phone->getName()]
        );
        $response->headers->set('Location', $phoneUrl);

        return $response;
    }

    public function phoneShow($name)
    {
        $phone = $this->em
            ->getRepository('AppBundle:Phone')
            ->findOneByName($name);

        if (!$phone) {
            throw new NotFoundHttpException(sprintf(
                'No phone found with name "%s"',
                $name
            ));
        }

        $response = $this->manageApi->createApiResponse($phone, 200);

        return $response;
    }

    public function phoneList()
    {
        $phones = $this->em
            ->getRepository('AppBundle:Phone')
            ->findAll();

        $response = $this->manageApi->createApiResponse(['phones' => $phones], 200);

        return $response;
    }

    public function phoneUpdate($request, $name)
    {
        $phone = $this->em
            ->getRepository('AppBundle:Phone')
            ->findOneByName($name);

        if (!$phone) {
            throw new NotFoundHttpException(sprintf(
                'No phone found with name "%s"',
                $name
            ));
        }

        $form = $this->formFactory->create(UpdatePhoneType::class, $phone);
        $this->manageApi->processForm($request, $form);

        if (!$form->isValid()) {
            $this->manageApi->throwApiProblemException($form);
        }

        $this->em->persist($phone);
        $this->em->flush();

        $response = $this->manageApi->createApiResponse($phone, 200);

        return $response;
    }

    public function phoneDelete($name)
    {
        $phone = $this->em
            ->getRepository('AppBundle:Phone')
            ->findOneByName($name);

        if ($phone) {
            $this->em->remove($phone);
            $this->em->flush();
        }

        return new Response(null, 204);
    }

}