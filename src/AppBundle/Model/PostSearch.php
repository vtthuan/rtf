<?php

namespace AppBundle\Model;

use Symfony\Component\HttpFoundation\Request;

class PostSearch
{
    protected $title;
    
    // the default page number
    protected $page = 1;
    
    public function handleRequest(Request $request)
    {
        $this->setPage($request->get('page', 1));
    }
    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }
    
    public function getPage()
    {
        return $this->page;
    }


    public function setPage($page)
    {
        if ($page != null) {
            $this->page = $page;
        }

        return $this;
    }
}
