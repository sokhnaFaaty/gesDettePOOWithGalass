<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\UtilisateurModel;

class UtilisateurController extends Controller {
    private $model;

    public function __construct() {
        $this->model = new UtilisateurModel();
    }

  

    /**
     * Liste des clients avec recherche par nom et par état.
     * Correspond à la maquette "Liste de clients".
     */
    public function index() {
        // On récupère les critères de recherche envoyés par le formulaire (méthode GET)
        $nom  = $_GET['nom']  ?? '';
        $etat = $_GET['etat'] ?? '';

        // Si un critère est présent, on filtre ; sinon on prend tous les clients
        if ($nom !== '' || $etat !== '') {
            $clients = $this->model->search($nom, $etat);
        } else {
            $clients = $this->model->allClients();
        }

        $this->view('clients/index', [
            'clients' => $clients,
            'nom'     => $nom,
            'etat'    => $etat,
        ]);
    }

    /**
     * Affiche le formulaire de création d'un client.
     */
    public function create() {
        $this->view('clients/create');
    }

    /**
     * Enregistre le nouveau client (traitement du formulaire).
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérifie que l'email n'est pas déjà utilisé
            if ($this->model->findByEmail($_POST['email'])) {
                die("Cet email est déjà utilisé.");
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
     * Affiche le formulaire d'édition d'un client.
     */
    public function edit($id) {
        $client = $this->model->find($id);
        if (!$client) {
            die("Client introuvable.");
        }
        $this->view('clients/edit', ['client' => $client]);
    }

    /**
     * Enregistre les modifications d'un client.
     */
    public function update($id) {
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
     * Supprime un client.
     */
    public function delete($id) {
        $this->model->delete($id);
        $this->redirect('/clients');
    }

  

    /**
     * Affiche le formulaire de connexion.
     */
    public function login() {
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
                // On démarre la session et on y stocke l'utilisateur connecté
                session_start();
                $_SESSION['user'] = [
                    'id'    => $user['id'],
                    'nom'   => $user['nom'],
                    'role'  => $user['role'],
                ];
                $this->redirect('/clients');
            } else {
                $this->view('auth/login', ['erreur' => 'Email ou mot de passe incorrect.']);
            }
        }
    }

    /**
     * Déconnexion.
     */
    public function logout() {
        session_start();
        session_destroy();
        $this->redirect('/login');
    }
}