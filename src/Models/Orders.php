<?php
namespace App\Models;

use App\Config\DB;
use PDO;

class Orders
{
    public static function countAll(): int
    {
        $db = DB::getConnection();
        $stmt = $db->query("SELECT COUNT(*) as total FROM tb_orders");
        return (int)$stmt->fetchColumn();
    }
}