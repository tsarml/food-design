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
        ]);

        return redirect()->to('/')->with('success', 'Bienvenue ' . $data['name'] . ' !');
    }
}