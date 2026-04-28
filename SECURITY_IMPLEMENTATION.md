# Guide d'Implémentation de Sécurité - Part 11

## 1. Protection CSRF (Cross-Site Request Forgery)

### ✅ Activée dans Filters.php
Le filtre CSRF est maintenant activé globalement pour toutes les requêtes.

### 📝 Intégration dans les Formulaires HTML

Ajouter le jeton CSRF dans tous les formulaires POST:

```php
<form method="POST" action="<?= site_url('auth/login') ?>">
    <!-- Jeton CSRF obligatoire -->
    <?= csrf_field() ?>
    
    <label>Email</label>
    <input type="email" name="email" required />
    
    <label>Mot de passe</label>
    <input type="password" name="password" required />
    
    <button type="submit">Connexion</button>
</form>
```

### 🔄 Configuration CSRF pour AJAX

Pour les requêtes AJAX, ajouter le jeton dans les en-têtes:

```javascript
// Récupérer le jeton CSRF
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

// Utiliser dans la requête AJAX
fetch('/api/endpoint', {
    method: 'POST',
    headers: {
        'X-CSRF-Token': csrfToken,
        'Content-Type': 'application/json'
    },
    body: JSON.stringify(data)
});
```

Ou avec jQuery:

```javascript
$.ajaxSetup({
    headers: {
        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
    }
});
```

---

## 2. Protection XSS (Cross-Site Scripting)

### ✅ Utiliser `esc()` pour toutes les données affichées

La fonction `esc()` échappe les caractères spéciaux pour prévenir les attaques XSS.

#### Exemples d'utilisation:

```php
<!-- ❌ DANGEREUX - NE PAS FAIRE -->
<h1><?= $user['name'] ?></h1>

<!-- ✅ SÉCURISÉ - À FAIRE -->
<h1><?= esc($user['name']) ?></h1>

<!-- ✅ Contexte HTML (défaut) -->
<p><?= esc($text) ?></p>
<?= esc($text, 'html') ?>

<!-- ✅ Attribut HTML -->
<img src="<?= esc($imageUrl, 'attr') ?>" alt="<?= esc($altText, 'attr') ?>" />

<!-- ✅ URL -->
<a href="<?= esc($url, 'url') ?>">Lien</a>

<!-- ✅ JavaScript -->
<script>
    var data = <?= esc($jsonData, 'js') ?>;
</script>

<!-- ✅ CSS -->
<style>
    body { background: <?= esc($color, 'css') ?>; }
</style>
```

---

## 3. Authentification Sécurisée

### ✅ Filtre d'Authentification Activé

Le filtre `AuthFilter.php` protège les routes suivantes:
- `home/*`
- `stats/*`
- `add-food/*`

### 📋 Vérification d'Authentification

```php
// Dans les contrôleurs
public function dashboard()
{
    // Le filtre a déjà vérifié que l'utilisateur est authentifié
    $user = session()->get('user');
    
    if (!$user) {
        return redirect()->to('/login');
    }
}
```

### 🔐 Gestion des Mots de Passe

#### Enregistrement (Hachage):

```php
$password = $this->request->getPost('password');
$hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

$model->save([
    'user_id' => $userId,
    'password' => $hashedPassword
]);
```

#### Connexion (Vérification):

```php
$user = $model->findByEmail($email);

if (password_verify($plainPassword, $user['password'])) {
    // Mot de passe correct
    session()->set(['user_id' => $user['id'], 'user' => true]);
} else {
    // Mot de passe incorrect
    return redirect()->back()->with('error', 'Identifiants invalides');
}
```

### 📊 Données de Session

```php
session()->set([
    'user_id'       => $user['id'],            // ID de l'utilisateur
    'user'          => true,                   // Flag d'authentification
    'user_name'     => $user['name'],          // Nom de l'utilisateur
    'user_email'    => $user['email'],         // Email de l'utilisateur
    'last_activity' => time(),                 // Dernier accès (pour timeout)
]);

// Récupérer l'utilisateur authentifié
$userId = session()->get('user_id');
$userName = session()->get('user_name');
```

### ⏱️ Expiration de Session

Le filtre vérifie automatiquement que la session ne s'est pas expirée (30 minutes par défaut).

```php
// Dans AuthFilter.php
$timeout = 1800; // 30 minutes

if (time() - $lastActivity > $timeout) {
    session()->destroy();
    return redirect()->to('/login')->with('error', 'Session expirée');
}
```

---

## 4. Routes Protégées

### Configuration dans Routes.php

```php
// Routes publiques
$routes->get('/', 'Home::index');
$routes->get('/login', 'AuthController::loginForm');
$routes->post('/login', 'AuthController::login');
$routes->get('/register', 'AuthController::registerForm');
$routes->post('/register', 'AuthController::register');

// Routes protégées (appliquent le filtre 'auth')
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('/home', 'Home::dashboard');
    $routes->get('/home/stats', 'Home::stats');
    $routes->get('/add-food', 'Home::addFoodForm');
    $routes->post('/add-food', 'Home::addFood');
    $routes->get('/logout', 'AuthController::logout');
});
```

---

## 5. En-têtes de Sécurité

### ✅ Automatiquement ajoutés par AuthFilter

```php
X-Content-Type-Options: nosniff        // Prévenir le MIME-sniffing
X-Frame-Options: SAMEORIGIN            // Prévenir le clickjacking
X-XSS-Protection: 1; mode=block        // Prévention XSS pour navigateurs
```

---

## 6. Checklist d'Implémentation

- [x] **CSRF Protection** activée globalement
- [x] **Token CSRF** dans tous les formulaires avec `<?= csrf_field() ?>`
- [x] **CSRF pour AJAX** via header `X-CSRF-Token`
- [x] **UserModel** avec hachage de mot de passe BCRYPT
- [x] **AuthController** avec connexion sécurisée
- [x] **AuthFilter** pour protéger les routes
- [x] **Session Management** avec expiration
- [x] **En-têtes de Sécurité** configurés
- [ ] **XSS Protection** - À appliquer dans chaque vue avec `esc()`
- [ ] **Routes** - À configurer avec le filtre d'authentification

---

## 7. Mise à Jour des Vues

### Fichiers à Mettre à Jour:

- `app/Views/login.php` - Utiliser le formulaire POST avec CSRF
- `app/Views/register.php` - Utiliser le formulaire POST avec CSRF
- `app/Views/home.php` - Ajouter `esc()` sur tous les données affichées
- `app/Views/add-food.php` - Ajouter `esc()` et CSRF token
- `app/Views/stats.php` - Ajouter `esc()` sur les statistiques

### Exemple de Mise à Jour:

```php
<!-- Avant (DANGEREUX) -->
<h1>Bienvenue <?= $user['name'] ?></h1>

<!-- Après (SÉCURISÉ) -->
<h1>Bienvenue <?= esc($user['name']) ?></h1>
```

---

## 8. Configuration de Sécurité Appliquée

Fichiers modifiés:
- ✅ `app/Config/Security.php` - Configuration CSRF et sécurité
- ✅ `app/Config/Filters.php` - Activation CSRF global et AuthFilter
- ✅ `app/Filters/AuthFilter.php` - Filtre d'authentification créé
- ✅ `app/Models/UserModel.php` - Amélioration validation et gestion passwords
- ✅ `app/Controllers/AuthController.php` - Méthodes login/logout avec password_verify

