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
    	 if ($page < 1) {
      throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
      }

      $listArticles = array(
            array(
                'id' => 1,
                'title' => 'Mon dernier Weekend !',
                'author' => 'Mathieu',
                'content' => 'mon dernier weeken etatit formidable',
                'date' => new \DateTime()

            ),

            array(
               'id' => 2,
               'title' => 'Sortie de Symfony 2.1',
               'author' => 'Jack',
               'content' => "C'est la deronère version de framework Symfony",
               'date' => new \DateTime()
              
            ),
            array(
               'id' => 3,
               'title' => 'test',
               'author' => 'Bernard',
               'content' => 'test test test test grrrr !',
               'date' => new \DateTime()
            )
        );		
        //verifier l'existence de la page
     

    	//recuperer la liste d'annonces 
        return $this->render('OCBlogBundle:Article:index.html.twig', 
        	       array('listArticles' => $listArticles)
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
       
       $em = $this->getDoctrine()->getManager();
       $article = new Article();
       $article->setTitle('Mon dernier weekend!');
       $article->setAuthor("C'était vraiment super et on s'est bien amusé.");
       $article->setContent("C'est un petit test !.");
       
       $image = new Image();
       $image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
       $image->setAlt('weekend');

       $article->setImage($image);
       //création d'un premier commentaire
       $comment1= new Comment();
       $comment1->setAuthor('winzou');
       $comment1->setContent('on veut les photos !');
       //création d'un dexuième commentaire

       $comment2 = new Comment();
       $comment2->setAuthor('Choupy');
       $comment2->setContent('Les photos arrivent');
       
       $comment1->setArticle($article);
       $comment2->setArticle($article);


       $listSkills = $em->getRepository('OCBlogBundle:Skill')->findAll();

       foreach ($listSkills as $skill) {
           $articleSkill = new ArticleSkill();
           $articleSkill->setArticle($article);
           $articleSkill->setSkill($skill);
           $articleSkill->setLevel('Expert');

           $em->persist($articleSkill);
       }


       $em = $this->getDoctrine()->getManager();
       //persister un article
       $em->persist($article);
       //persister le premier commentaire
       $em->persist($comment1);
       //persister le deuxième commentaire
       $em->persist($comment2);


       

       $em->flush();


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

     // On récupère l'annonce
    $article = $em->getRepository('OCBlogBundle:Article')->find($id);
    // on récuper toute les categories de la base de donnée  
    $listCategories = $em->getRepository('OCBlogBundle:Category')->findAll();
  
    //on ajoute les categories à l'annonce
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

    public function menuAction()
    {
        $listArticles = array(
            array(
                'id' => 1,
                'title' => 'Mon dernier Weekend !'

            ),

            array(
               'id' => 2,
               'title' => 'Sortie de Symfony 2.1'
              
            ),
            array(
               'id' => 3,
               'title' => 'test'
            )
        );	

        return $this->render('OCBlogBundle:Article:menu.html.twig', array('listArticles' => $listArticles));
    }
}
