<?php

namespace AppBundle\Pagination;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class PaginationFactory
{
    public function __construct(EntityManager $em, RouterInterface $router)
    {
        $this->em = $em;
        $this->router = $router;
    }

    public function createCollection(QueryBuilder $qb, Request $request, $route, array $routeParams = array())
    {
        $page = $request->query->get('page', 1);

        $adapter = new DoctrineORMAdapter($qb);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(10);
        $pagerfanta->setCurrentPage($page);

        $phones = array();
        foreach ($pagerfanta->getCurrentPageResults() as $phone) {
            $phones[] = $phone;
        }

        $paginatedCollection = new PaginatedCollection(
            $phones,
            $pagerfanta->getNbResults()
        );

        $routeParams = array_merge($routeParams, $request->query->all());
        $createLinkUrl = function ($targetPage) use ($route, $routeParams) {
            return $this->router->generate(
                $route,
                array_merge(
                    $routeParams,
                    array('page' => $targetPage)
                )
            );
        };

        $paginatedCollection->addLink('self', $createLinkUrl($page));
        $paginatedCollection->addLink('first', $createLinkUrl(1));
        $paginatedCollection->addLink('last', $createLinkUrl($pagerfanta->getNbPages()));

        if ($pagerfanta->hasNextPage()) {
            $paginatedCollection->addLink('next', $createLinkUrl($pagerfanta->getNextPage()));
        }

        if ($pagerfanta->hasPreviousPage()) {
            $paginatedCollection->addLink('prev', $createLinkUrl($pagerfanta->getPreviousPage()));
        }

        return $paginatedCollection;
    }
}