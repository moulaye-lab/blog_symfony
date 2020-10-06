<?php
 
namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;

use App\Form\CommentType;
use App\Repository\ArticleRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index()
    {   
 
        return $this->render('security/login.html.twig', [
            'controller_name' => 'SecurityController',
            
        ]);
    }

    
    /**
     * 
     * @Route("/" , name="home")
     * 
     */
    public function home(ArticleRepository $repo ){

        $articles = $repo->findAll();
        return $this->render('blog/home.html.twig', [
            'title' => "Bienvenue dans ce blog ",
            'age' => 31,
            'articles' => $articles
        ]);
        
    } 



    /**
     * 
     * @Route("blog/new", name="blog_create")
     * @Route("/blog/{id}/edit ", name="blog_edit")
     */

    public function form(Article $article = null,Request $request,ObjectManager $manager){

    if(!$article){
        
        $article = new Article ();

    }

      //  $form = $this->createFormBuilder($article) // on creer le formulaire
        //              ->add('title') //on cree les input
                      
         //             ->add('content')
                      
           //           ->add('image')

                    //  ->add('save' , SubmitType::class, [
                          
                            //  'label' => "Enregistrer"
                          
                    //  ])
                   //   ->getForm();//on recupere le form qu'on a creer
        
        $form =$this->createForm(ArticleType::class,$article);
 
        $form->handleRequest($request); //On demande a symfony grace a cette ligne de verifier le formulaire si tout lesn champs sont remplis etc... 
        
        if($form->isSubmitted() AND $form->isValid()){ //Si le formulaire est soumis et qu'il es valide 
           
            if(!$article->getId())
            {
                $article->setCreatedAt(new \DateTime());
            }

            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('blog_show' , ['id' => $article->getId()]);

        }
        
        return $this->render('blog/create.html.twig', [
        'editMode' => $article->getId() !== null,
        'title' => "CREATION D'UN ARTICLE !!!",
        'id' =>$article->getId(),
        'formArticle' => $form->createView() // on cree la vue du formulaire
    
    
    
        
        ]);


    }


    
    /**
     * show
     *
     * @Route("/blog/{id}", name="blog_show")
     */
    public function show(Article $article ,Request $request,ObjectManager $manager){
     
     $momo="moulaye";
     $comment= new Comment;
      //  $repo =$this->getDoctrine()->getRepository(Article::class);

      //  $article = $repo->find($id); 

      $form =$this->createForm(CommentType::class,$comment);
      
      $form->handleRequest($request);

      if ($form->isSubmitted() AND $form->isValid()) {
         
        $comment->setCreatedAT($momo)
                 ->setArticle($article);  
        
        $manager->persist($comment);
         $manager->flush($comment);

         $this->redirectToRoute('blog_show',['id'=>$article->getId()]);
      }

        return $this->render('blog/show.html.twig', [
            'article' => $article,
            'commentForm' =>$form->createView(),
             'momo' =>$momo
        ]);


    }

    


}



