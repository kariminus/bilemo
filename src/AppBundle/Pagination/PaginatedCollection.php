<?php

namespace AppBundle\Pagination;

class PaginatedCollection
{
    public  $items;
    public  $total;
    public  $count;

    public $_links = array();

    public function __construct($items, $total)
    {
        $this->items = $items;
        $this->total = $total;
        $this->count = count($items);
    }

    public function addLink($ref, $url)
    {
        $this->_links[$ref] = $url;
    }
}