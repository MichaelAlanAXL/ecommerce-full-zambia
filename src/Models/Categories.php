<?php
namespace App\Models;

use App\Config\DB;
use PDO;

class Categories {
    public static function all(): array {        
        $stmt = DB::getConnection()->query("SELECT * FROM tb_categories ORDER BY descategory");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create(string $descategory): void {
        $stmt = DB::getConnection()->prepare("INSERT INTO tb_categories (descategory) VALUES (:descategory)");
        $stmt->bindParam(':descategory', $descategory);
        $stmt->execute();
    }

    public static function find(int $idcategory): ?array {
        $stmt = DB::getConnection()->prepare("SELECT * FROM tb_categories WHERE idcategory = :idcategory");
        $stmt->bindParam(':idcategory', $idcategory);
        $stmt->execute();
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        return $category ?: null;
    }

    public static function update(int $idcategory, string $descategory): void {
        $stmt = DB::getConnection()->prepare("UPDATE tb_categories SET descategory = :descategory WHERE idcategory = :idcategory");
        $stmt->bindParam(':descategory', $descategory);
        $stmt->bindParam(':idcategory', $idcategory);
        $stmt->execute();
    }

    public static function delete(int $idcategory): void {
        $stmt = DB::getConnection()->prepare("DELETE FROM tb_categories WHERE idcategory = :idcategory");
        $stmt->bindParam(':idcategory', $idcategory);
        $stmt->execute();
    }
}