# LibraFlow 📚
Système de gestion de bibliothèque scolaire numérique — Projet Laravel 12

## Présentation
LibraFlow numérise la gestion d'une bibliothèque scolaire :
catalogue de livres, emprunts, retours, réservations et pénalités de retard.

## Comptes de démonstration
| Rôle | Email | Mot de passe |
|------|-------|--------------|
| Admin | admin@libraflow.com | password |
| Bibliothécaire | biblio@libraflow.com | password |
| Lecteur | lecteur@libraflow.com | password |

## Installation

### Prérequis
- PHP 8.2+
- Composer
- MySQL (XAMPP)
- Node.js + npm

### Étapes

1. Cloner le projet
git clone https://github.com/votre-compte/libraflow.git
cd libraflow

2. Installer les dépendances PHP
composer install

3. Installer les dépendances JS
npm install && npm run build

4. Configurer l'environnement
cp .env.example .env
php artisan key:generate

5. Configurer la base de données dans .env
DB_DATABASE=libraflow
DB_USERNAME=root
DB_PASSWORD=

6. Lancer les migrations et seeders
php artisan migrate:fresh --seed

7. Créer le lien de stockage
php artisan storage:link

8. Lancer le serveur
php artisan serve

Accéder à http://127.0.0.1:8000

## Fonctionnalités
- Catalogue livres avec recherche (titre, auteur, catégorie)
- Affichage disponibilité en temps réel
- Gestion emprunts et retours
- Calcul automatique des pénalités (Carbon)
- File d'attente de réservations
- Notification email au retour d'un livre (Mailable)
- Paramètres configurables (durée emprunt, pénalité/jour)
- 3 rôles : Admin, Bibliothécaire, Lecteur

## Architecture technique
- Laravel 12 avec Breeze (auth)
- Eloquent Observer (LoanObserver)
- Scopes : overdue(), available(), search()
- Policies : BookPolicy, LoanPolicy, ReservationPolicy
- Middleware : CheckRole
- Mailable : ReservationDisponibleMail
- Carbon pour toutes les dates

## Structure des branches Git
- main : version stable finale
- phase-2 : migrations et modèles
- phase-3 : rôles et policies
- phase-4 : controllers
- phase-5 : vues Blade
- phase-6 : finalisation




### Phase 1 - Installation & Configuration

Allons-y etape par etape. Suis chaque commande dans l'ordre.

#### Etape 1 - Verifier que tout est pret

Ouvre CMD (invite de commandes) et tape ces commandes pour verifier:

```bash
php -v
```

Tu dois voir quelque chose comme PHP 8.2.x. Si tu vois une erreur, PHP n'est pas dans ton PATH.

```bash
composer -v
```

Tu dois voir la version de Composer. Si `composer` n'est pas reconnu, telecharge-le sur getcomposer.org.

```bash
node -v
npm -v
```

Node.js est necessaire pour Tailwind CSS. Si absent, telecharge-le sur nodejs.org.

#### Etape 2 - Creer le projet Laravel

Dans CMD, navigue vers le dossier `htdocs` de XAMPP:

```bash
cd C:\xampp\htdocs
```

Puis cree le projet:

```bash
composer create-project laravel/laravel libraflow
```

Cela va telecharger Laravel dans `C:\xampp\htdocs\libraflow`. Ca prend 2-3 minutes.

Entre dans le dossier:

```bash
cd libraflow
```

#### Etape 3 - Creer la base de donnees

1. Ouvre XAMPP Control Panel puis clique **Start** sur Apache et MySQL.
2. Ouvre ton navigateur puis va sur `http://localhost/phpmyadmin`.
3. Clique sur **Nouvelle base de donnees** (a gauche).
4. Nom: `libraflow`, puis clique **Creer**.

#### Etape 4 - Configurer le fichier .env

Dans le dossier `libraflow`, ouvre le fichier `.env` avec VS Code ou Notepad++.
Trouve ces lignes et modifie-les:

```env
APP_NAME=LibraFlow
APP_URL=http://localhost/libraflow/public

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=libraflow
DB_USERNAME=root
DB_PASSWORD=
```

Avec XAMPP, le mot de passe MySQL est vide par defaut. Laisse `DB_PASSWORD=` vide.

#### Etape 5 - Ajouter la colonne role aux utilisateurs

Avant d'installer Breeze, on va preparer la migration users pour y ajouter les roles.
Ouvre le fichier:

`database/migrations/xxxx_create_users_table.php`

Trouve le bloc `Schema::create` et ajoute la ligne `role`:

```php
Schema::create('users', function (Blueprint $table) {
	$table->id();
	$table->string('name');
	$table->string('email')->unique();
	$table->timestamp('email_verified_at')->nullable();
	$table->string('password');
	$table->enum('role', ['admin', 'bibliothecaire', 'lecteur'])->default('lecteur'); // <- AJOUTE CETTE LIGNE
	$table->rememberToken();
	$table->timestamps();
});
```

#### Etape 6 - Installer Laravel Breeze

Dans CMD (toujours dans le dossier `libraflow`):

```bash
composer require laravel/breeze --dev
```

Puis:

```bash
php artisan breeze:install blade
```

Reponds `yes` si on te demande confirmation. Cela installe l'authentification avec les vues Blade.

Installe les dependances JS:

```bash
npm install
npm run build
```

#### Etape 7 - Lancer les migrations

```bash
php artisan migrate
```

Tu dois voir quelque chose comme:

```text
Running migrations...
... create_users_table
... create_password_reset_tokens_table
```

#### Etape 8 - Modifier le modele User

Ouvre `app/Models/User.php` et modifie le tableau `$fillable` pour y ajouter `role`:

```php
protected $fillable = [
	'name',
	'email',
	'password',
	'role', // <- AJOUTE CETTE LIGNE
];
```

Ajoute aussi ces methodes pratiques en bas de la classe, juste avant le `}` final:

```php
// Verifie si l'utilisateur est admin
public function isAdmin(): bool
{
	return $this->role === 'admin';
}

// Verifie si l'utilisateur est bibliothecaire
public function isBibliothecaire(): bool
{
	return $this->role === 'bibliothecaire';
}

// Verifie si l'utilisateur est lecteur
public function isLecteur(): bool
{
	return $this->role === 'lecteur';
}
```

Pourquoi ces methodes ? En soutenance, tu expliqueras que plutot que de comparer `$user->role === 'admin'` partout dans le code, on centralise la logique dans le modele. C'est le principe de Single Responsibility.

#### Etape 9 - Tester que ca marche

Lance le serveur Laravel:

```bash
php artisan serve
```

Ouvre `http://127.0.0.1:8000` dans ton navigateur.
Tu dois voir la page d'accueil Laravel avec les liens Login et Register en haut a droite.

Teste en creant un compte via Register. Si ca fonctionne, la Phase 1 est terminee.

### Resume de ce qu'on a fait

| Fichier modifie | Ce qu'on a fait |
| --- | --- |
| `.env` | Connexion a la base MySQL de XAMPP |
| `create_users_table.php` | Ajout de la colonne `role` |
| `User.php` | Ajout de `role` dans `$fillable` + 3 methodes helper |

Dis-moi quand la Phase 1 est validee (page d'accueil visible + register qui fonctionne) et on attaque la Phase 2 - Migrations & Modeles Eloquent.




