<?php
require_once 'db.php';

class Shipping {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function saveAddress($userId, $data) {
        $sql = "INSERT INTO shipping_addresses 
                (user_id, first_name, last_name, street_address, apt_number, city, state, zip_code, phone, is_default) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $userId, 
            $data['first_name'],
            $data['last_name'],
            $data['street_address'],
            $data['apt_number'] ?? null,
            $data['city'],
            $data['state'],
            $data['zip_code'],
            $data['phone'],
            $data['is_default'] ?? 0
        ]);
    }
    
    public function getUserAddresses($userId) {
        $sql = "SELECT * FROM shipping_addresses WHERE user_id = ? ORDER BY is_default DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>