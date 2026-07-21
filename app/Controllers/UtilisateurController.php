<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\UtilisateurModel;
use App\Models\DetteModel;

class UtilisateurController extends Controller {
    private $model;
    private $detteModel;

    public function __construct() {
        parent::__construct();
        $this->model = new UtilisateurModel();
        $this->detteModel = new DetteModel();
    }

    /**
     * Liste des clients avec recherche par nom et par état, paginée.
     * Affiche "Liste de clients". Réservé à l'admin.
     */
    public function index() {
        $this->requireAuth('admin');

        // On récupère les critères de recherche envoyés par le formulaire (méthode GET)
        $nom  = $_GET['nom']  ?? '';
        $etat = $_GET['etat'] ?? '';
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 10;

        $clients = $this->model->search($nom, $etat, $page, $perPage);
        $total   = $this->model->countSearch($nom, $etat);
        $totalPages = max(1, (int) ceil($total / $perPage));

        $this->view('admin/index', [
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
        $this->requireAuth('admin');
        $this->view('admin/create');
    }

    /**
     * Enregistre le nouveau client (traitement du formulaire). Réservé à l'admin.
     */
    public function store() {
        $this->requireAuth('admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérifie que l'email n'est pas déjà utilisé
            if ($this->model->findByEmail($_POST['email'])) {
                $this->view('admin/create', ['erreur' => "Cet email est déjà utilisé."]);
                return;
            }

            $this->model->create([
                'nom'          => $_POST['nom'],
                'prenom'       => $_POST['prenom'],
                'email'        => $_POST['email'],
                'mot_de_passe' => $_POST['mot_de_passe'],
                'telephone'    => $_POST['telephone'] ?? null,
                'etat_client'  => $_POST['etat_client'] ?? 'nouveau',
            ]);

            $this->redirect('/clients');
        }
    }

    /**
     * Affiche le formulaire d'édition d'un client. Réservé à l'admin.
     */
    public function edit($id) {
        $this->requireAuth('admin');

        $client = $this->model->find($id);
        if (!$client) {
            die("Client introuvable.");
        }
        $this->view('admin/edit', ['client' => $client]);
    }

    /**
     * Enregistre les modifications d'un client. Réservé à l'admin.
     */
    public function update($id) {
        $this->requireAuth('admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->update($id, [
                'nom'         => $_POST['nom'],
                'prenom'      => $_POST['prenom'],
                'email'       => $_POST['email'],
                'telephone'   => $_POST['telephone'] ?? null,
                'etat_client' => $_POST['etat_client'] ?? 'nouveau',
            ]);
            $this->redirect('/clients');
        }
    }

    /**
     * Supprime un client. Réservé à l'admin.
     */
    public function delete($id) {
        $this->requireAuth('admin');
        $this->model->delete($id);
        $this->redirect('/clients');
    }

    /**
     * Fiche client : infos du client + liste de ses dettes. Réservé à l'admin
     * (correspond au clic "voir fiche" depuis la liste des clients).
     */
    public function show($id) {
        $this->requireAuth('admin');

        $client = $this->model->find($id);
        if (!$client) {
            die("Client introuvable.");
        }
        $dettes = $this->detteModel->findByUtilisateur($id);

        $this->view('admin/show', [
            'client' => $client,
            'dettes' => $dettes,
        ]);
    }

    /**
     * Fiche du client connecté : ses propres infos + ses propres dettes.
     * Réservé au rôle client.
     */
    public function profil() {
        $user = $this->requireAuth('client');

        $client = $this->model->find($user['id']);
        $dettes = $this->detteModel->findByUtilisateur($user['id']);

        $this->view('client/dashboard', [
            'client' => $client,
            'dettes' => $dettes,
        ]);
    }

    /**
     * Affiche le formulaire de connexion.
     */
    public function login() {
        // Déjà connecté : direction la page qui correspond à son rôle
        if (!empty($_SESSION['user'])) {
            $this->redirect($_SESSION['user']['role'] === 'admin' ? '/clients' : '/profil');
        }
        $this->view('auth/login');
    }

    /**
     * Traite la connexion.
     */
    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $motDePasse = $_POST['mot_de_passe'] ?? '';

            $user = $this->model->verifyLogin($email, $motDePasse);

            if ($user) {
                // On stocke l'utilisateur connecté dans la session
                $_SESSION['user'] = [
                    'id'    => $user['id'],
                    'nom'   => $user['nom'],
                    'prenom'=> $user['prenom'],
                    'role'  => $user['role'],
                ];
                $this->redirect($user['role'] === 'admin' ? '/clients' : '/profil');
            } else {
                $this->view('auth/login', ['erreur' => 'Email ou mot de passe incorrect.']);
            }
        }
    }

    /**
     * Déconnexion.
     */
    public function logout() {
        $_SESSION = [];
        session_destroy();
        $this->redirect('/login');
    }
}
