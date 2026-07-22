<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\DetteModel;
use App\Models\UtilisateurModel;

class DetteController extends Controller {
    private $detteModel;
    private $clientModel;
    private $perPage = 5; // 5 dettes par page

    public function __construct() {
        parent::__construct();
        $this->detteModel  = new DetteModel();
        $this->clientModel = new UtilisateurModel();
    }

    /**
     * Liste de toutes les dettes (paginée : 5 par page),
     * filtrable par état du client et/ou état de la dette.
     */
    public function index() {
        $etatClient = trim($_GET['etat_client'] ?? '');
        $etatDette  = trim($_GET['etat_dette'] ?? '');
        $page = max(1, (int) ($_GET['page'] ?? 1));

        $total = $this->detteModel->countAll($etatClient, $etatDette);
        $totalPages = max(1, (int) ceil($total / $this->perPage));

        view('dettes/index', [
            'dettes'     => $this->detteModel->getAllWithClients($etatClient, $etatDette, $page, $this->perPage),
            'etatClient' => $etatClient,
            'etatDette'  => $etatDette,
            'page'       => $page,
            'totalPages' => $totalPages,
        ]);
    }

    /**
     * Dettes non soldées (paginée : 5 par page).
     */
    public function nonSoldees() {
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $total = $this->detteModel->countNonSoldees();
        $totalPages = max(1, (int) ceil($total / $this->perPage));

        view('dettes/non_soldees', [
            'dettes'     => $this->detteModel->getNonSoldees($page, $this->perPage),
            'page'       => $page,
            'totalPages' => $totalPages,
        ]);
    }

    /**
     * Dettes soldées (paginée : 5 par page).
     */
    public function soldees() {
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $total = $this->detteModel->countSoldees();
        $totalPages = max(1, (int) ceil($total / $this->perPage));

        view('dettes/soldees', [
            'dettes'     => $this->detteModel->getSoldees($page, $this->perPage),
            'page'       => $page,
            'totalPages' => $totalPages,
        ]);
    }

    /**
     * Dettes d'un client (utilisateur) donné.
     */
    public function client($id) {
        view('dettes/client', [
            'client' => $this->clientModel->find($id),
            'dettes' => $this->detteModel->getByClientId($id),
        ]);
    }
}
