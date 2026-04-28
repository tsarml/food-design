<?php

namespace App\Models; /*Shows aiza ilay classe io */

use CodeIgniter\Model;
/*pour faire extends Model */

class LivreModel extends Model {
protected $table = "livres";
/*utilise la table livres */
protected $primaryKey = "id";
/*Id no primary key */
protected $allowedFields = ['titre', 'auteur','isbn','anneepub','category','resumee','statut','nom_fic_couv'];
/*Afaka modifiena */
protected $useTimestamps = true;

protected $validationRules = [];

public function __construct()
{
parent::__construct();
$this->validationRules =[
    'titre' => 'required|min_length[3]',
    'auteur' => 'required|min_length[3]',
    'isbn' => 'required|is_unique[livres.isbn]',
    'anneepub' => 'required|integer'
];

}
protected $validationMessages = [
'titre' => ['required' => 'Le titre est obligatoire' ,
             'min_length' => 'Le titre devrait comprendre au moins 3 caractères'],
'auteur' => ['required' => 'Un auteur est obligatoire'],
'isbn' => ['required' => 'Insérez un isbn ',
'is_unique' => 'Cet ISBN a déjà été utilisé , veuillez en insérer un autre'],
'anneepub' => ['required' => 'Une année de publication est obligatoire'],
];

public function validationAnnees($annee)
{if($annee < date('Y') && $annee > 1800){
return true ;
} return false ;}


public function recherche($motcle = null, $categorie = null)
{
    $builder = $this->builder();
    if ($motcle) {
        $builder->like('titre', $motcle);
    }
    if ($categorie) {
        $builder->where('category', $categorie);
    }
    return $builder->get()->getResult();
}

public function getLivrePagine(){
return $this->Paginate(10);
}
}
?>