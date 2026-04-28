<?php
namespace App\Controllers;
use App\Models\UserModel;

class AuthController extends BaseController
{
    public function registerForm()
    {
        if (session()->get('user_id')) {
            return redirect()->to('/');
        }
        return view('auth/register');
    }

    public function register()
    {
        $model = new UserModel();

        $data = [
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
        ];

        $password  = $this->request->getPost('password');
        $password2 = $this->request->getPost('password2');

        if ($password !== $password2) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Les mots de passe ne correspondent pas.');
        }

        if (!$model->validate($data)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $model->errors());
        }

        $data['password'] = password_hash($password, PASSWORD_BCRYPT);

        $userId = $model->insert($data);

        session()->set([
            'user_id'   => $userId,
            'user_name' => $data['name'],
            'user_email'=> $data['email'],
            'last_activity' => time(),
        ]);

        return redirect()->to('/')->with('success', 'Bienvenue ' . $data['name'] . ' !');
    }

    /**
     * Afficher le formulaire de connexion
     */
    public function loginForm()
    {
        if (session()->get('user_id')) {
            return redirect()->to('/');
        }
        return view('auth/login');
    }

    /**
     * Traiter la connexion utilisateur
     * Vérification sécurisée du mot de passe avec password_verify()
     */
    public function login()
    {
        $model = new UserModel();

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Validation des données
        if (!$email || !$password) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email et mot de passe sont obligatoires.');
        }

        // Rechercher l'utilisateur
        $user = $model->findByEmail($email);

        if (!$user) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email ou mot de passe incorrect.');
        }

        // Vérifier le mot de passe avec password_verify()
        if (!password_verify($password, $user['password'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email ou mot de passe incorrect.');
        }

        // Authentification réussie
        session()->set([
            'user_id'   => $user['id'],
            'user'      => true,
            'user_name' => $user['name'],
            'user_email'=> $user['email'],
            'last_activity' => time(),
        ]);

        return redirect()->to('/')->with('success', 'Connexion réussie !');
    }

    /**
     * Déconnexion
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Vous avez été déconnecté.');
    }
}