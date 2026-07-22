<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\DetteModel;
use App\Models\ClientModel;

class DetteController extends Controller {
    private $detteModel;
    private $clientModel;
    
    public function __construct() {
        $this->detteModel = new DetteModel();
        $this->clientModel = new ClientModel();
    }
    
    // Liste des dettes
    public function index() {
        $dettes = $this->detteModel->getAllWithClients();
        $this->view('dettes/index', ['dettes' => $dettes]);
    }
    
    // Dettes non soldées
    public function nonSoldees() {
        $dettes = $this->detteModel->getNonSoldees();
        $this->view('dettes/non_soldees', ['dettes' => $dettes]);
    }
    
    // Dettes d'un client
    public function client($id) {
        $client = $this->clientModel->find($id);
        $dettes = $this->detteModel->getByClientId($id);
        $this->view('dettes/client', ['client' => $client, 'dettes' => $dettes]);
    }
    
    //  Formulaire d'ajout (GET)
    public function ajouter() {
        $clients = $this->clientModel->all();
        $this->view('dettes/ajouter', ['clients' => $clients]);
    }
    
    // Traitement du formulaire (POST)
    public function enregistrer() {
        // Vérifier que c'est une requête POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/dettes');
        }
        
        // Récupérer les données
        $data = [
            'client_id' => $_POST['client_id'] ?? null,
            'montant' => $_POST['montant'] ?? 0,
            'date' => $_POST['date'] ?? date('Y-m-d'),
            'description' => $_POST['description'] ?? ''
        ];
        
        // Validation
        if (!$data['client_id']) {
            flash('error', 'Veuillez sélectionner un client');
            $this->redirect('/dettes/ajouter');
        }
        
        if ($data['montant'] <= 0) {
            flash('error', 'Le montant doit être supérieur à 0');
            $this->redirect('/dettes/ajouter');
        }
        
        // Créer la dette
        $id = $this->detteModel->createDette($data);
        
        if ($id) {
            flash('success', 'Dette créée avec succès !');
        } else {
            flash('error', 'Erreur lors de la création de la dette');
        }
        
        $this->redirect('/dettes');
    }
    
    //  Formulaire de modification (GET)
    public function modifier($id) {
        $dette = $this->detteModel->find($id);
        if (!$dette) {
            flash('error', 'Dette non trouvée');
            $this->redirect('/dettes');
        }
        
        $clients = $this->clientModel->all();
        $this->view('dettes/modifier', [
            'dette' => $dette,
            'clients' => $clients
        ]);
    }
    
    // Traitement du formulaire de modification (POST)
    public function mettreAJour($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/dettes');
        }
        
        $dette = $this->detteModel->find($id);
        if (!$dette) {
            flash('error', 'Dette non trouvée');
            $this->redirect('/dettes');
        }
        
        $data = [
            'client_id' => $_POST['client_id'] ?? $dette['client_id'],
            'montant' => $_POST['montant'] ?? $dette['montant'],
            'date' => $_POST['date'] ?? $dette['date'],
            'description' => $_POST['description'] ?? $dette['description']
        ];
        
        if ($data['montant'] <= 0) {
            flash('error', 'Le montant doit être supérieur à 0');
            $this->redirect('/dettes/modifier/' . $id);
        }
        
        $result = $this->detteModel->update($id, $data);
        flash($result ? 'success' : 'error', 
              $result ? 'Dette mise à jour avec succès' : 'Erreur lors de la mise à jour');
        
        $this->redirect('/dettes');
    }
    
    // Supprimer une dette
    public function supprimer($id) {
        $dette = $this->detteModel->find($id);
        if (!$dette) {
            flash('error', 'Dette non trouvée');
            $this->redirect('/dettes');
        }
        
        if ($dette['etat'] === 'soldee') {
            flash('warning', 'Impossible de supprimer une dette soldée');
            $this->redirect('/dettes');
        }
        
        $result = $this->detteModel->delete($id);
        flash($result ? 'success' : 'error', 
              $result ? 'Dette supprimée avec succès' : 'Erreur lors de la suppression');
        
        $this->redirect('/dettes');
    }
    
    // Marquer une dette comme soldée
    public function soldeer($id) {
        $dette = $this->detteModel->find($id);
        if (!$dette) {
            flash('error', 'Dette non trouvée');
            $this->redirect('/dettes');
        }
        
        if ($dette['etat'] === 'soldee') {
            flash('warning', 'Cette dette est déjà soldée');
            $this->redirect('/dettes');
        }
        
        $result = $this->detteModel->marquerSoldee($id);
        flash($result ? 'success' : 'error', 
              $result ? 'Dette soldée avec succès' : 'Erreur lors du soldage');
        
        $this->redirect('/dettes');
    }
}