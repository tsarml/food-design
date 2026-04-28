<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * AuthFilter
 *
 * Filtre d'authentification pour protéger les routes privées.
 * Aucune route protégée ne doit être accessible sans session active.
 */
class AuthFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not change the request or response.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Vérifier si l'utilisateur est authentifié
        if (!$session->has('user_id') || !$session->has('user')) {
            // L'utilisateur n'est pas authentifié
            // Rediriger vers la page de connexion
            return redirect()->to('/login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        // Vérifier que la session n'a pas expiré
        $lastActivity = $session->get('last_activity');
        $timeout = 1800; // 30 minutes

        if (time() - $lastActivity > $timeout) {
            // Session expirée
            $session->destroy();
            return redirect()->to('/login')->with('error', 'Votre session a expiré. Veuillez vous reconnecter.');
        }

        // Mettre à jour le dernier accès
        $session->set('last_activity', time());
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not need to return anything.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Ajouter les en-têtes de sécurité
        $response->setHeader('X-Content-Type-Options', 'nosniff');
        $response->setHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->setHeader('X-XSS-Protection', '1; mode=block');
    }
}
