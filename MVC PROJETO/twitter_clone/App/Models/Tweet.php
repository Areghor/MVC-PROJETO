<?php
namespace App\Models;

use MF\Model\Model;


class Tweet extends Model{

    private $id;
    private $id_usuario;
    private $tweet;
    private $data;

    public function __get($atributo){
        return $this->$atributo;
    }

    public function __set($atributo, $valor){
        $this->$atributo = $valor;
    }

    public function salvar(){
        $query = "insert into tweets (id_usuario, tweet) values (:id_usuario, :tweet)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario',$this->__get('id_usuario'))  ;
        $stmt->bindValue(':tweet',$this->__get('tweet'))  ;
        $stmt->execute();

        return $this;
    }

    public function getAll(){
        $query = "
        select t.id, 
            t.id_usuario, 
            t.tweet, 
            t.data, 
            u.nome  
        from 
            tweets as t 
            left join usuarios as u on (t.id_usuario = u.id) 
        where 
            id_usuario = :id_usuario 

            OR t.id_usuario in (select id_usuario_seguindo from usuarios_seguidores where id_usuario = :id_usuario)

        ORDER BY data DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario',$this->__get('id_usuario'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function removerTweet($id_tweet){
        $query="delete from tweets where id = :id_tweet";
        
        $stmt= $this->db->prepare($query);
        $stmt->bindValue(':id_tweet',$id_tweet);
        $stmt->execute();

        return true;    }
}

?>