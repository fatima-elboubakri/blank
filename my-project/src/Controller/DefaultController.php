<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
class DefaultController extends AbstractController
{
    /**
     * @Route("/home", name="app_default_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('default/index.html.twig');
    }
    /**
     * @Route("/", name="app_default_login", methods={"GET", "POST"})
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if($this->getUser()){
            return $this->render('default/login.html.twig');
        }
        //$error = $authenticationUtils ->get
        return $this->render('default/login.html.twig',[
            'authenticationUtils' => $authenticationUtils,
        ]);

    }
}