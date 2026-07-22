<?php
namespace App\Controllers; // Ce fichier appartient au namespace App\Controllers

use App\Core\Controller;        // Classe mère (view(), redirect(), requireAuth())
use App\Models\UtilisateurModel; // Modèle pour accéder à la table "utilisateur"
use App\Models\DetteModel;       // Modèle pour accéder à la table "dette"
use Validator;                   // Classe de validation de formulaires (namespace global)

require_once __DIR__ . '/../../helpers/validator.php'; // Charge la classe Validator (pas gérée par l'autoloader App\)

// Contrôleur qui gère : la connexion, l'espace admin (CRUD clients) et l'espace client (fiche personnelle)
class UtilisateurController extends Controller {
    private $model;      // Instance de UtilisateurModel, réutilisée dans toutes les méthodes
    private $detteModel; // Instance de DetteModel, réutilisée pour récupérer les dettes d'un client

    // Constructeur : appelé à chaque "new UtilisateurController()" (donc à chaque requête routée ici)
    public function __construct() {
        parent::__construct();               // Appelle le constructeur de Controller (démarre la session)
        $this->model = new UtilisateurModel(); // Prépare l'accès à la table utilisateur
        $this->detteModel = new DetteModel();  // Prépare l'accès à la table dette
    }

    /**
     * Liste des clients avec recherche par nom et par état, paginée.
     * Affiche "Liste de clients". Réservé à l'admin.
     */
    public function index() {
        $this->requireAuth('admin'); // Bloque l'accès si non connecté ou si le rôle n'est pas admin

        // On récupère les critères de recherche envoyés par le formulaire (méthode GET)
        $nom  = $_GET['nom']  ?? '';               // Terme recherché dans le nom (vide si absent de l'URL)
        $etat = $_GET['etat'] ?? '';               // État sélectionné dans le filtre (vide = tous les états)
        $page = max(1, (int) ($_GET['page'] ?? 1)); // Numéro de page demandé (jamais inférieur à 1)
        $perPage = 3;                               // Nombre de clients affichés par page

        $clients = $this->model->search($nom, $etat, $page, $perPage);       // Récupère uniquement les clients de la page courante
        $total   = $this->model->countSearch($nom, $etat);                   // Compte le nombre total de résultats (sans pagination)
        $totalPages = max(1, (int) ceil($total / $perPage));                 // Calcule le nombre total de pages nécessaires

        $this->view('admin/index', [ // Affiche la vue views/admin/index.php avec les données calculées
            'clients'    => $clients,
            'nom'        => $nom,
            'etat'       => $etat,
            'page'       => $page,
            'totalPages' => $totalPages,
        ]);
    }

    /**
     * Affiche le formulaire de création d'un client. Réservé à l'admin.
     */
    public function create() {
        $this->requireAuth('admin');  // Accès réservé à l'admin
        $this->view('admin/create');  // Affiche simplement le formulaire vide (pas de données à transmettre)
    }

    /**
     * Enregistre le nouveau client (traitement du formulaire). Réservé à l'admin.
     */
    public function store() {
        $this->requireAuth('admin'); // Accès réservé à l'admin

        if ($_SERVER['REQUEST_METHOD'] === 'POST') { // On ne traite que si le formulaire a bien été soumis en POST
            $validator = Validator::make($_POST) // Crée un validateur sur les données du formulaire
                ->required('prenom', "Le prénom est obligatoire.")                  // Le prénom ne doit pas être vide
                ->required('nom', "Le nom est obligatoire.")                        // Le nom ne doit pas être vide
                ->required('email', "L'email est obligatoire.")                     // L'email ne doit pas être vide
                ->email('email', "L'email saisi n'est pas valide.")                 // L'email doit être au bon format
                ->required('mot_de_passe', "Le mot de passe est obligatoire.")      // Le mot de passe ne doit pas être vide
                ->minLength('mot_de_passe', 6, "Le mot de passe doit contenir au moins 6 caractères.") // Longueur minimale
                ->phone('telephone', "Le numéro de téléphone n'est pas valide.")    // Format de téléphone plausible (si fourni)
                ->in('etat_client', ['nouveau', 'solvable', 'non solvable'], "L'état sélectionné n'est pas valide.") // Valeur autorisée
                ->image('photo', $_FILES, "La photo doit être une image (JPG, PNG, WEBP ou GIF) de 2 Mo maximum."); // Photo valide (si fournie)

            // Vérifie que l'email n'est pas déjà utilisé
            if (!empty($_POST['email']) && $this->model->findByEmail($_POST['email'])) { // Recherche en base un compte avec cet email
                $validator->addManualError('email', "Cet email est déjà utilisé."); // Ajoute une erreur "manuelle" (pas une règle générique)
            }

            if ($validator->fails()) { // S'il existe au moins une erreur de validation
                $this->view('admin/create', ['erreurs' => $validator->errors(), 'old' => $_POST]); // Réaffiche le formulaire avec les erreurs et les valeurs saisies
                return; // On arrête ici : pas de création en base
            }

            $photo = $this->storeUploadedPhoto($_FILES['photo'] ?? null); // Déplace la photo envoyée et récupère son nom de fichier (ou null)

            $this->model->create([ // Insère le nouveau client en base
                'nom'          => $_POST['nom'],
                'prenom'       => $_POST['prenom'],
                'email'        => $_POST['email'],
                'mot_de_passe' => $_POST['mot_de_passe'],
                'telephone'    => $_POST['telephone'] ?? null,
                'etat_client'  => $_POST['etat_client'] ?? 'nouveau',
                'photo'        => $photo,
            ]);

            $this->redirect('/clients'); // Redirige vers la liste des clients après la création
        }
    }

    /**
     * Déplace la photo envoyée (si présente) vers public/uploads/clients et renvoie
     * le nom de fichier généré à stocker en base, ou null si aucune photo n'a été fournie.
     */
    private function storeUploadedPhoto($file) {
        if (empty($file) || $file['error'] === UPLOAD_ERR_NO_FILE) { // Aucun fichier envoyé : rien à faire
            return null;
        }

        $extensions = [ // Table de correspondance type MIME -> extension de fichier (plus sûr que l'extension d'origine)
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
            'image/gif'  => 'gif',
        ];
        $mime = mime_content_type($file['tmp_name']);   // Détecte le vrai type du fichier temporaire reçu
        $extension = $extensions[$mime] ?? 'jpg';        // Choisit l'extension correspondante (jpg par défaut en secours)

        $uploadDir = __DIR__ . '/../../public/uploads/clients/'; // Dossier de destination des photos (accessible publiquement)
        if (!is_dir($uploadDir)) {        // Si le dossier n'existe pas encore
            mkdir($uploadDir, 0755, true); // On le crée (true = crée aussi les dossiers parents manquants)
        }

        $filename = uniqid('client_', true) . '.' . $extension; // Génère un nom de fichier unique (évite les collisions/écrasements)
        move_uploaded_file($file['tmp_name'], $uploadDir . $filename); // Déplace le fichier temporaire vers sa destination finale

        return $filename; // Renvoie le nom de fichier à enregistrer en base
    }

    /**
     * Affiche le formulaire d'édition d'un client. Réservé à l'admin.
     */
    public function edit($id) {
        $this->requireAuth('admin'); // Accès réservé à l'admin

        $client = $this->model->find($id); // Recherche le client à modifier par son id
        if (!$client) {                     // Si aucun client ne correspond à cet id
            die("Client introuvable.");     // On arrête avec un message d'erreur simple
        }
        $this->view('admin/edit', ['client' => $client]); // Affiche le formulaire pré-rempli avec les données du client
    }

    /**
     * Enregistre les modifications d'un client. Réservé à l'admin.
     */
    public function update($id) {
        $this->requireAuth('admin'); // Accès réservé à l'admin

        if ($_SERVER['REQUEST_METHOD'] === 'POST') { // On ne traite que si le formulaire a été soumis en POST
            $validator = Validator::make($_POST)
                ->required('prenom', "Le prénom est obligatoire.")
                ->required('nom', "Le nom est obligatoire.")
                ->required('email', "L'email est obligatoire.")
                ->email('email', "L'email saisi n'est pas valide.")
                ->phone('telephone', "Le numéro de téléphone n'est pas valide.")
                ->in('etat_client', ['nouveau', 'solvable', 'non solvable'], "L'état sélectionné n'est pas valide.");

            if ($validator->fails()) { // S'il existe au moins une erreur de validation
                $client = array_merge($this->model->find($id), $_POST); // Fusionne les données actuelles avec la saisie invalide (pour réafficher ce que l'utilisateur a tapé)
                $this->view('admin/edit', ['client' => $client, 'erreurs' => $validator->errors()]); // Réaffiche le formulaire avec les erreurs
                return; // On arrête ici : pas de mise à jour en base
            }

            $this->model->update($id, [ // Met à jour le client en base avec les nouvelles valeurs
                'nom'         => $_POST['nom'],
                'prenom'      => $_POST['prenom'],
                'email'       => $_POST['email'],
                'telephone'   => $_POST['telephone'] ?? null,
                'etat_client' => $_POST['etat_client'] ?? 'nouveau',
            ]);
            $this->redirect('/clients'); // Redirige vers la liste des clients après la modification
        }
    }

    /**
     * Supprime un client. Réservé à l'admin.
     */
    public function delete($id) {
        $this->requireAuth('admin'); // Accès réservé à l'admin
        $this->model->delete($id);   // Supprime le client correspondant à cet id (hérité de Model::delete)
        $this->redirect('/clients'); // Redirige vers la liste des clients après la suppression
    }

    /**
     * Fiche client : infos du client + liste de ses dettes. Réservé à l'admin
     * (correspond au clic "voir fiche" depuis la liste des clients).
     */
    public function show($id) {
        $this->requireAuth('admin'); // Accès réservé à l'admin

        $client = $this->model->find($id); // Recherche le client à afficher par son id
        if (!$client) {                     // Si aucun client ne correspond
            die("Client introuvable.");     // On arrête avec un message d'erreur simple
        }
        $dettes = $this->detteModel->findByUtilisateur($id); // Récupère toutes les dettes liées à ce client

        $this->view('admin/show', [ // Affiche la fiche complète du client
            'client' => $client,
            'dettes' => $dettes,
        ]);
    }

    /**
     * Fiche du client connecté : ses propres infos + ses propres dettes.
     * Réservé au rôle client.
     */
    public function profil() {
        $user = $this->requireAuth('client'); // Accès réservé au rôle client ; renvoie les infos de session de l'utilisateur connecté

        $client = $this->model->find($user['id']);              // Récupère les infos complètes du client connecté (via son id de session)
        $dettes = $this->detteModel->findByUtilisateur($user['id']); // Récupère les dettes de ce même client

        $this->view('client/dashboard', [ // Affiche le tableau de bord du client connecté
            'client' => $client,
            'dettes' => $dettes,
        ]);
    }

    /**
     * Affiche le formulaire de connexion.
     */
    public function login() {
        // Déjà connecté : direction la page qui correspond à son rôle
        if (!empty($_SESSION['user'])) { // Si une session utilisateur existe déjà
            $this->redirect($_SESSION['user']['role'] === 'admin' ? '/clients' : '/profil'); // On l'envoie directement vers son espace (inutile de se reconnecter)
        }
        $this->view('auth/login'); // Sinon, affiche simplement le formulaire de connexion
    }

    /**
     * Traite la connexion.
     */
    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { // On ne traite que si le formulaire a été soumis en POST
            $validator = Validator::make($_POST)
                ->required('email', "L'email est obligatoire.")           // L'email doit être renseigné
                ->email('email', "L'email saisi n'est pas valide.")       // L'email doit être au bon format
                ->required('mot_de_passe', "Le mot de passe est obligatoire."); // Le mot de passe doit être renseigné

            if ($validator->fails()) { // Si les champs de base sont invalides (avant même d'interroger la base)
                $this->view('auth/login', ['erreurs' => $validator->errors()]); // Réaffiche le formulaire avec les erreurs
                return; // On arrête ici : inutile d'aller vérifier en base
            }

            $email = $_POST['email'];             // Email saisi (déjà validé ci-dessus)
            $motDePasse = $_POST['mot_de_passe']; // Mot de passe saisi

            $user = $this->model->verifyLogin($email, $motDePasse); // Vérifie les identifiants en base

            if ($user) { // Si les identifiants sont corrects
                // On stocke l'utilisateur connecté dans la session
                $_SESSION['user'] = [ // On ne garde que les infos utiles en session (jamais le mot de passe)
                    'id'    => $user['id'],
                    'nom'   => $user['nom'],
                    'prenom'=> $user['prenom'],
                    'role'  => $user['role'],
                ];
                $this->redirect($user['role'] === 'admin' ? '/clients' : '/profil'); // Redirige vers l'espace correspondant au rôle
            } else { // Si l'email est inconnu ou le mot de passe incorrect
                $this->view('auth/login', ['erreurs' => ['general' => 'Email ou mot de passe incorrect.']]); // Réaffiche le formulaire avec un message générique (ne précise pas lequel des deux est faux, pour la sécurité)
            }
        }
    }

    /**
     * Déconnexion.
     */
    public function logout() {
        $_SESSION = [];       // Vide toutes les données de la session courante
        session_destroy();    // Détruit la session côté serveur
        $this->redirect('/login'); // Redirige vers la page de connexion
    }
}
