<?php

namespace OC\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('OCBlogBundle:Default:index.html.twig');
    }
}
