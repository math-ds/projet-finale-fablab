<?php

class AuthModele {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * 🔍 Récupère un utilisateur par email
     */
    public function getUserByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM connexion WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * ➕ Crée un nouvel utilisateur
     */
    public function createUser($nom, $email, $mot_de_passe, $role = 'Utilisateur') {
        $stmt = $this->db->prepare("
            INSERT INTO connexion (nom, email, mot_de_passe, role)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$nom, $email, $mot_de_passe, $role]);
    }

    
    public function verifierConnexion($email, $mot_de_passe) {
        $stmt = $this->db->prepare("SELECT * FROM connexion WHERE email = ?");
        $stmt->execute([$email]);
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($utilisateur && password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
            return $utilisateur;
        }
        return false;
    }

   
    public function emailExiste($email) {
        $stmt = $this->db->prepare("SELECT id FROM connexion WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }
}
