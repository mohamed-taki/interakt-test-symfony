
# Projet Test - Application Symfony

Ceci est un projet Symfony (7.1) développé dans le cadre d'un test de recrutement. Le projet démontre diverses fonctionnalités de Symfony, y compris la vérification par email et autres.

## Demonstration Video

Pour voir une démonstration de l'application en action, vous pouvez regarder la vidéo ci-dessous :

**[Voir Le Demo](<https://drive.google.com/file/d/16QT6XcfxahphnU4mjd2g5qSalm1-HipY/view?usp=drive_link>)**

Cette vidéo fournit une présentation des fonctionnalités du projet. Elle couvre les fonctionnalités principales l'application.

## Instructions d'installation

Suivez les étapes ci-dessous pour configurer le projet localement :

### Prérequis

- Assurez-vous d'avoir installé le [Symfony CLI](https://symfony.com/download).
- PHP 8.1 ou supérieur.
- Composer.
- MySQL ou une autre base de données prise en charge.
- Node.js et npm.

### Étapes pour l'installation locale

1. **Cloner le dépôt**

   ```bash
   git clone https://github.com/mohamed-taki/interakt-test-symfony.git
   cd <repository-folder>
   ```

2. **Installer les dépendances**

   Exécutez la commande suivante pour installer les packages PHP requis :

   ```bash
   composer install
   ```

3. **Configurer les variables d'environnement**

   Le projet inclut un fichier `.env` avec des variables d'environnement préconfigurées, y compris la connexion a une base de donnee MySQL, et une token SMTP pour la vérification des emails.
   Ajustez le fichier `.env` si nécessaire (par exemple, pour les paramètres de connexion à la base de données).

5. **Créer la base de données**

   Créez la base de données en utilisant le Symfony CLI :

   ```bash
   symfony console doctrine:database:create
   ```

6. **Générer & Exécuter les migrations**
   Générer une migration :

   ```bash
   symfony console make:migration
   ```
   Appliquez la migration Génée dans la base de données :

   ```bash
   symfony console doctrine:migrations:migrate
   ```

7. **Générer & Exécuter les fixtures**
   Appliquez les fixtures dans la base de données :

   ```bash
   symfony console doctrine:fixtures:load
   ```
   Appliquez la migration Génée dans la base de données :

   ```bash
   symfony console doctrine:migrations:migrate
   ```

8. **Installer les assets frontend (optionnel)**

   Si le projet inclut des assets frontend, installez-les en utilisant npm :

   ```bash
   npm install
   npm run dev
   ```

8. **Démarrer le serveur Symfony**

   Lancez le serveur local Symfony :

   ```bash
   symfony server:start
   ```

   L'application devrait maintenant être accessible à l'adresse `http://localhost:8000`.

## Vérification par email

La fonctionnalité de vérification par email est configurée et prête à l'emploi. Le fichier `.env` dans le dépôt inclut le token SMTP nécessaire.
