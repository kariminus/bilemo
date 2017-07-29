<?php

namespace AppBundle\Phone;

use AppBundle\Api\ManageApi;
use AppBundle\Entity\Phone;
use AppBundle\Form\PhoneType;
use AppBundle\Form\UpdatePhoneType;
use AppBundle\Pagination\PaginatedCollection;
use AppBundle\Pagination\PaginationFactory;
use Doctrine\ORM\EntityManager;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Cache\Adapter\DoctrineAdapter;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;


class ManagePhone
{
    /**
     * ManagePhone constructor.
     */
    public function __construct(EntityManager $em, $formFactory, ManageApi $manageApi, $router, PaginationFactory $paginationFactory)
    {
        $this->em = $em;
        $this->formFactory = $formFactory;
        $this->manageApi = $manageApi;
        $this->router = $router;
        $this->paginationFactory = $paginationFactory;

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

    public function phoneList($request)
    {
        $qb = $this->em
            ->getRepository('AppBundle:Phone')
            ->findAllQueryBuilder();

        $paginatedCollection = $this->paginationFactory
            ->createCollection($qb, $request, 'api_phones_collection');

        $response = $this->manageApi->createApiResponse([
            $paginatedCollection
        ]);

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