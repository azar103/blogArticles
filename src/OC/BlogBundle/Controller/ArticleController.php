<?php
// src/OC/BlogBundle/Controller/ArticleController.php
namespace OC\BlogBundle\Controller;

use OC\BlogBundle\Entity\ArticleSkill;
use OC\BlogBundle\Entity\Article;
use OC\BlogBundle\Entity\Image;
use OC\BlogBundle\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class ArticleController extends Controller
{
    public function indexAction($page)
    {
      if($page < 1)
      {
        throw $this->createNotFoundException("La page ".$page." n'existe pas.");
      }
      $em = $this->getDoctrine()->getManager();
       
      $nbPerPage = 3;
       $listArticles = $em->getRepository('OCBlogBundle:Article')->getArticles($page,$nbPerPage);
      $nbPages = ceil(count($listArticles)/$nbPerPage);

    	 if ($page > $nbPages) {
      throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
      }

    
        //verifier l'existence de la page
  

    	//recuperer la liste d'annonces 
        return $this->render('OCBlogBundle:Article:index.html.twig', 
        	       array('listArticles' => $listArticles,
                       'nbPages'=>$nbPages,
                       'page' =>$page
                      )
        	   );

     
    }

    public function viewAction($id)
    {
    	//recuper une annonce
      $em = $this->getDoctrine()->getManager();

       $article = $em->getRepository('OCBlogBundle:Article')->find($id);

       $listComments = $em->getRepository('OCBlogBundle:Comment')->findBy(array('article' => $article));
       $listArticleSkills = $em->getRepository('OCBlogBundle:ArticleSkill')->findBy(array('article' => $article));

     if(null == $article)
     {
       throw New NotFoundHttpException("L'annonce d'id ".$id." n'existe pas ");
     }        
    	return $this->render('OCBlogBundle:Article:view.html.twig', 
                               array('article' => $article, 
                               'listComments' =>$listComments,
                               'listArticleSkills' => $listArticleSkills));
     }
    

    public function addAction(Request $request)
    {
       
     

       if ($request->isMethod('POST'))
       {
       	  $request->getSession()->getFlashBag->add('notice','article bien enregistré');
          return $this->redirectToRoute('oc_blog_view', array('id' => $article->getId()));   
       }

       return $this->render('OCBlogBundle:Article:add.html.twig', array('article' => $article));

    }

    public function editAction($id, Request $request)
    {
      $em = $this->getDoctrine()->getManager();

     // On récupère l'article
    $article = $em->getRepository('OCBlogBundle:Article')->find($id);
    // on récuper toute les categories de la base de donnée  
    $listCategories = $em->getRepository('OCBlogBundle:Category')->findAll();
  
    //on ajoute les categories à l'article
    foreach ($listCategories as $category) {
     $article->addCategory($category);
    }
    $article->setAuthor('Mathieu');

     // On déclenche la modification
     $em->flush();



    	if ($request->isMethod('POST'))
       {
       	  $request->getSession()->getFlashBag->add('notice','article bien modifié');
          return $this->redirectToRoute('oc_blog_view', array('id' => $id));   
       }

       return $this->render('OCBlogBundle:Article:edit.html.twig');
    }

    public function deleteAction($id)
    {
      $em = $this->getDoctrine()->getManager();
      $article = $em->getRepository('OCBlogBundle:Article')->find($id);

      //on boucle sur les categories de l'annonces pour les supprimer
      foreach ($category->getCategories() as $category) {
        $article->removeCategory($category);
      }
    	return $this->render('OCBlogBundle:Article:delete.html.twig');
    }

    public function menuAction($limit)
    {
      $em = $this->getDoctrine()->getManager();
      $listArticles = $em->getRepository('OCBlogBundle:Article')
                      ->findBy(
                               array(),
                               array('date' => 'DESC'),
                               $limit,
                               0  
                             );
     
        return $this->render('OCBlogBundle:Article:menu.html.twig', array('listArticles' => $listArticles));
    }
}
