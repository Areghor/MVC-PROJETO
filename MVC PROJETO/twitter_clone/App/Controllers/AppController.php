<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action {

    public function timeline(){

            $this->validaAutenticacao();

            $tweet = Container::getModel('Tweet');
            $tweet->__set('id_usuario',$_SESSION['id']);

            $this->view->tweets = $tweet->getAll();


            $usuario = Container::getModel('Usuario');
            $usuario->__set('id',$_SESSION['id']);
            
            $this->view->nomeUsuario = $usuario->getNome();
            $this->view->totalTweet = $usuario->getNumTweets();
            $this->view->totalSeguidores = $usuario->getNumSeguidores();
            $this->view->totalSeguindo = $usuario->getNumSeguindo();
        
            $this->render('timeline');

    }

    public function tweet(){

        $this->validaAutenticacao();
        
        if($_POST['tweet']!=''){

        $tweet = Container::getModel('Tweet');

        $tweet->__set('tweet',$_POST['tweet']);
        $tweet->__set('id_usuario',$_SESSION['id']);

        $tweet->salvar();
        }
        header('Location: /timeline');

        
    }

    public function validaAutenticacao(){

        session_start();

        if(!isset($_SESSION['id']) || $_SESSION['id']  =='' || !isset($_SESSION['nome']) || $_SESSION['nome'] ==''){
            
            header('Location: /?login=erro');
        }
    }

    public function quem_seguir(){
    $this->validaAutenticacao();

    $pesquisarPor = array();

    $pesquisarPor = isset($_GET['pesquisarpor']) ? $_GET['pesquisarpor'] : "";

    if($pesquisarPor!=''){

        $usuario = Container::getModel('Usuario');
        $usuario->__set('nome',$pesquisarPor);
        $usuario->__set('id',$_SESSION['id']);
        $usuarios = $usuario->getAll();
    }


            $usuario_logado = Container::getModel('Usuario');
            $usuario_logado->__set('id',$_SESSION['id']);
            
            $this->view->nomeUsuario = $usuario_logado->getNome();
            $this->view->totalTweet = $usuario_logado->getNumTweets();
            $this->view->totalSeguidores = $usuario_logado->getNumSeguidores();
            $this->view->totalSeguindo = $usuario_logado->getNumSeguindo();
                                    

    $this->view->usuarios = $usuarios;

   $this->render('quemSeguir');

    }

    public function acao(){
    $this->validaAutenticacao();

    $acao = isset($_GET['acao'])? $_GET['acao'] : '';
    $pesquisa = isset($_GET['pesquisa'])? $_GET['pesquisa'] : '';
    $id_usuario_seguindo = isset($_GET['id'])? $_GET['id'] : '';
    $id_usuario = $_SESSION['id'];

   

    $usuario = Container::getModel('UsuariosSeguidores');

    $usuario->__set('id_usuario', $_SESSION['id']);
    $usuario->__set('id_usuario_seguindo', $id_usuario_seguindo);


    if($acao=='seguir'){

        
        $usuario->seguirUsuario($id_usuario_seguindo);
        header('Location: /quem_seguir?pesquisarpor='.$pesquisa);
    }else{
        $usuario->deixarSeguirUsuario($id_usuario_seguindo);
        header('Location: /quem_seguir?pesquisarpor='.$pesquisa);    }

    }

    public function remover_Tweet(){

        $tweet = Container::getModel('Tweet');
        $id_tweet = $_POST['id_tweet'];
        $tweet->removerTweet($id_tweet);
        header('Location: /timeline');

    }
    

}

?>