<?php
/**
 * Petit validateur de formulaires réutilisable (fluent API).
 * Usage : Validator::make($_POST)->required('nom')->email('email')->fails()
 */
class Validator { // Classe sans namespace : elle est chargée "à la main" via require_once dans les contrôleurs
    private $data;        // Tableau des données à valider (en général $_POST)
    private $errors = []; // Tableau associatif [nom_du_champ => message d'erreur] rempli au fil des vérifications

    // Constructeur privé : on ne crée pas l'objet directement, on passe par make() (voir juste en dessous)
    private function __construct(array $data) {
        $this->data = $data; // Mémorise les données à valider pour les méthodes suivantes
    }

    // Point d'entrée statique : permet d'écrire Validator::make($_POST)->... sans "new"
    public static function make(array $data) {
        return new self($data); // Crée et renvoie une nouvelle instance du validateur
    }

    /**
     * Le champ doit être présent et non vide (après trim).
     */
    public function required($field, $message = null) {
        $value = trim((string) ($this->data[$field] ?? '')); // Récupère la valeur (chaîne vide si absente) et retire les espaces
        if ($value === '') { // Si, après nettoyage, il ne reste rien
            $this->addError($field, $message ?? "Le champ {$field} est obligatoire."); // On enregistre l'erreur
        }
        return $this; // On renvoie l'objet lui-même pour pouvoir chaîner ->required()->email()->...
    }

    /**
     * Le champ, s'il est renseigné, doit être une adresse email valide.
     */
    public function email($field, $message = null) {
        $value = trim((string) ($this->data[$field] ?? '')); // Valeur nettoyée du champ
        if ($value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) { // Si non vide ET format email invalide
            $this->addError($field, $message ?? "L'email saisi n'est pas valide.");
        }
        return $this; // Chaînage
    }

    /**
     * Le champ, s'il est renseigné, doit contenir au moins $length caractères.
     */
    public function minLength($field, $length, $message = null) {
        $value = trim((string) ($this->data[$field] ?? '')); // Valeur nettoyée
        if ($value !== '' && mb_strlen($value) < $length) { // mb_strlen : compte les caractères (accents inclus) correctement
            $this->addError($field, $message ?? "Le champ {$field} doit contenir au moins {$length} caractères.");
        }
        return $this; // Chaînage
    }

    /**
     * Le champ, s'il est renseigné, ne doit pas dépasser $length caractères.
     */
    public function maxLength($field, $length, $message = null) {
        $value = trim((string) ($this->data[$field] ?? '')); // Valeur nettoyée
        if ($value !== '' && mb_strlen($value) > $length) { // Si la longueur dépasse la limite autorisée
            $this->addError($field, $message ?? "Le champ {$field} ne doit pas dépasser {$length} caractères.");
        }
        return $this; // Chaînage
    }

    /**
     * Le champ, s'il est renseigné, doit être un numéro de téléphone plausible
     * (chiffres, espaces, tirets, éventuellement un + au début, 7 à 20 caractères).
     */
    public function phone($field, $message = null) {
        $value = trim((string) ($this->data[$field] ?? '')); // Valeur nettoyée
        if ($value !== '' && !preg_match('/^\+?[0-9 \-]{7,20}$/', $value)) { // Expression régulière de validation du format
            $this->addError($field, $message ?? "Le numéro de téléphone n'est pas valide.");
        }
        return $this; // Chaînage
    }

    /**
     * Le champ, s'il est renseigné, doit faire partie des valeurs autorisées.
     */
    public function in($field, array $allowed, $message = null) {
        $value = trim((string) ($this->data[$field] ?? '')); // Valeur nettoyée
        if ($value !== '' && !in_array($value, $allowed, true)) { // Si la valeur n'est pas dans la liste blanche $allowed
            $this->addError($field, $message ?? "La valeur choisie pour {$field} n'est pas valide.");
        }
        return $this; // Chaînage
    }

    /**
     * Le fichier ($_FILES[$field]), s'il est fourni, doit être une image (jpg, png, webp, gif)
     * et ne pas dépasser $maxSizeBytes. Champ optionnel : rien n'est envoyé -> pas d'erreur.
     */
    public function image($field, array $files, $message = null, $maxSizeBytes = 2097152) { // 2097152 octets = 2 Mo
        $file = $files[$field] ?? null; // Récupère les infos du fichier envoyé (name, type, tmp_name, error, size)

        if (!$file || $file['error'] === UPLOAD_ERR_NO_FILE) { // Aucun fichier sélectionné : champ optionnel, on arrête là
            return $this;
        }

        if ($file['error'] !== UPLOAD_ERR_OK) { // Un autre code d'erreur PHP (fichier trop gros pour le serveur, etc.)
            $this->addError($field, $message ?? "Le téléversement de la photo a échoué.");
            return $this;
        }

        $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif']; // Types MIME d'images acceptés
        $mime = mime_content_type($file['tmp_name']); // Détecte le vrai type du fichier (pas seulement son extension)
        if (!in_array($mime, $allowed, true)) { // Si le type détecté n'est pas dans la liste autorisée
            $this->addError($field, $message ?? "La photo doit être une image (JPG, PNG, WEBP ou GIF).");
            return $this;
        }

        if ($file['size'] > $maxSizeBytes) { // Si le fichier dépasse la taille maximale autorisée
            $this->addError($field, $message ?? "La photo ne doit pas dépasser " . round($maxSizeBytes / 1048576, 1) . " Mo.");
            return $this;
        }

        return $this; // Toutes les vérifications sont passées : pas d'erreur ajoutée
    }

    /**
     * Le champ doit être identique à un autre champ (ex: confirmation de mot de passe).
     */
    public function same($field, $otherField, $message = null) {
        $value = (string) ($this->data[$field] ?? '');      // Valeur du premier champ
        $other = (string) ($this->data[$otherField] ?? ''); // Valeur du champ à comparer
        if ($value !== $other) { // Si les deux valeurs diffèrent
            $this->addError($field, $message ?? "Les champs {$field} et {$otherField} ne correspondent pas.");
        }
        return $this; // Chaînage
    }

    // Méthode interne utilisée par toutes les règles ci-dessus pour enregistrer une erreur
    private function addError($field, $message) {
        // On ne garde que la première erreur par champ, pour un affichage plus lisible.
        if (!isset($this->errors[$field])) { // Si ce champ n'a pas encore d'erreur enregistrée
            $this->errors[$field] = $message; // On enregistre le message (les suivants pour ce champ seront ignorés)
        }
    }

    // Renvoie true si au moins une règle de validation a échoué
    public function fails() {
        return !empty($this->errors);
    }

    // Renvoie true si toutes les règles de validation sont passées (aucune erreur)
    public function passes() {
        return empty($this->errors);
    }

    /**
     * Ajoute manuellement une erreur (ex: unicité vérifiée en base par le contrôleur).
     */
    public function addManualError($field, $message) {
        $this->addError($field, $message); // Réutilise la même logique interne que les autres règles
        return $this; // Chaînage
    }

    /**
     * @return array Tableau associatif [champ => message].
     */
    public function errors() {
        return $this->errors; // Renvoie toutes les erreurs accumulées, pour affichage dans la vue
    }
}
