<?php
namespace App\Models;
use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';          // ✅ 'users' et non 'user'
    protected $primaryKey = 'id';
    protected $allowedFields = [              // ✅ 'name' et 'password' ajoutés
        'name',
        'email',
        'password',
    ];
    protected $useTimestamps = true;

    protected $validationRules = [
        'name' => [                           // ✅ règle 'name' manquante ajoutée
            'label'  => 'Nom',
            'rules'  => 'required|min_length[2]',
        ],
        'email' => [
            'label'  => 'Email',
            'rules'  => 'required|valid_email|is_unique[users.email]',
        ],
        'password' => [                       // ✅ 'mdp' → 'password'
            'label'  => 'Mot de passe',
            'rules'  => 'required|min_length[8]',
        ],
    ];

    protected $validationMessages = [
        'name' => [
            'required'   => 'Le nom est obligatoire.',
            'min_length' => 'Le nom doit contenir au moins 2 caractères.',
        ],
        'email' => [
            'required'    => 'L\'adresse email est obligatoire.',
            'valid_email' => 'L\'adresse email n\'est pas valide.',
            'is_unique'   => 'Cette adresse email est déjà utilisée.',
        ],
        'password' => [
            'required'   => 'Le mot de passe est obligatoire.',
            'min_length' => 'Le mot de passe doit contenir au moins 8 caractères.',
        ],
    ];

    public function findByEmail(string $email): ?array
    {
        return $this->where('email', $email)->first();
    }
}