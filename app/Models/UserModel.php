<?php
namespace App\Models;
use CodeIgniter\Model;

/**
 * UserModel
 *
 * Modèle pour la gestion des utilisateurs
 * Sécurité: Les mots de passe sont toujours stockés en hash (BCRYPT)
 */
class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name',
        'email',
        'password',
    ];
    protected $useTimestamps = true;

    protected $validationRules = [
        'name' => [
            'label'  => 'Nom',
            'rules'  => 'required|min_length[2]',
        ],
        'email' => [
            'label'  => 'Email',
            'rules'  => 'required|valid_email|is_unique[users.email]',
        ],
        'password' => [
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

    /**
     * Rechercher un utilisateur par email
     *
     * @param string $email
     * @return array|null
     */
    public function findByEmail(string $email): ?array
    {
        return $this->where('email', $email)->first();
    }

    /**
     * Rechercher un utilisateur par ID
     *
     * @param int $id
     * @return array|null
     */
    public function findById(int $id): ?array
    {
        return $this->find($id);
    }

    /**
     * Vérifier un mot de passe
     * Utilise password_verify() pour la comparaison sécurisée
     *
     * @param string $plainPassword
     * @param string $hashedPassword
     * @return bool
     */
    public static function verifyPassword(string $plainPassword, string $hashedPassword): bool
    {
        return password_verify($plainPassword, $hashedPassword);
    }
}