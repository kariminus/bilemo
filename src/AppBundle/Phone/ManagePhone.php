<?php

namespace AppBundle\Phone;

use AppBundle\Api\ManageApi;
use AppBundle\Entity\Phone;
use AppBundle\Form\PhoneType;
use AppBundle\Form\UpdatePhoneType;
use AppBundle\Pagination\PaginationFactory;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


class ManagePhone
{
    /**
     * @var AuthorizationChecker
     */
    private $authorizationChecker;

    /**
     * ManagePhone constructor.
     */
    public function __construct(EntityManager $em, $formFactory, ManageApi $manageApi, $router, PaginationFactory $paginationFactory, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->em = $em;
        $this->formFactory = $formFactory;
        $this->manageApi = $manageApi;
        $this->router = $router;
        $this->paginationFactory = $paginationFactory;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function phoneIndex()
    {

    }

    public function phoneNew($request)
    {

        if (!$this->authorizationChecker->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }
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

    public function phoneShow($slug)
    {
        $phone = $this->em
            ->getRepository('AppBundle:Phone')
            ->findOneBySlug($slug);

        if (!$phone) {
            throw new NotFoundHttpException(sprintf(
                'No phone found with name "%s"',
                $slug
            ));
        }

        $response = $this->manageApi->createApiResponse($phone, 200);

        return $response;
    }

    public function phoneList($request)
    {

        $filter = $request->query->get('filter');

        $qb = $this->em
            ->getRepository('AppBundle:Phone')
            ->findAllQueryBuilder($filter);

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