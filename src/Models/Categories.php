<?php
namespace App\Models;

use App\Config\DB;
use PDO;

class Categories {
    public static function all(): array {        
        $stmt = DB::getConnection()->query("SELECT * FROM tb_categories ORDER BY descategory");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function countAll(): int
    {
        $db = DB::getConnection();
        $stmt = $db->query("SELECT COUNT(*) as total FROM tb_categories");
        return (int)$stmt->fetchColumn();
    }

    public static function create(string $descategory, int $is_active = 1): void {
        $stmt = DB::getConnection()->prepare("INSERT INTO tb_categories (descategory, is_active) VALUES (:descategory, :is_active)");
        $stmt->bindParam(':descategory', $descategory);
        $stmt->bindParam(':is_active', $is_active);
        $stmt->execute();
    }

    public static function find(int $idcategory): ?array {
        $stmt = DB::getConnection()->prepare("SELECT * FROM tb_categories WHERE idcategory = :idcategory");
        $stmt->bindParam(':idcategory', $idcategory);
        $stmt->execute();
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        return $category ?: null;
    }

    public static function findBySlug(string $slug): ?array
    {
        $pdo = DB::getConnection();

        $stmt = $pdo->prepare("
            SELECT *,
                    LOWER(REPLACE(descategory, ' ', '-')) AS slug
            FROM tb_categories
            HAVING slug = :slug
            LIMIT 1
        ");
        
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function update(int $idcategory, string $descategory, int $is_active = 1): void {
        $stmt = DB::getConnection()->prepare("UPDATE tb_categories SET descategory = :descategory, is_active = :is_active WHERE idcategory = :idcategory");
        $stmt->bindParam(':descategory', $descategory);
        $stmt->bindParam(':idcategory', $idcategory);
        $stmt->bindParam(':is_active', $is_active);
        $stmt->execute();
    }

    public static function delete(int $idcategory): void {
        $stmt = DB::getConnection()->prepare("DELETE FROM tb_categories WHERE idcategory = :idcategory");
        $stmt->bindParam(':idcategory', $idcategory);
        $stmt->execute();
    }

    public static function allActive(): array {
        $stmt = DB::getConnection()->query("SELECT * FROM tb_categories WHERE is_active = 1 ORDER BY descategory");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}