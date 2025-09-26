<?php
namespace App\Models;

use App\Config\DB;
use PDO;

class Products
{
    public static function all(): array
    {
        $pdo = DB::getConnection();
        $stmt = $pdo->query("
            SELECT p.*, c.descategory
            FROM tb_products p
            LEFT JOIN tb_categories c ON p.idcategory = c.idcategory
            ORDER BY p.idproduct DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find(int $id)
    {
        $pdo = DB::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM tb_products WHERE idproduct = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create(array $data) 
    {
        $pdo = DB::getConnection();
        $stmt = $pdo->prepare("
            INSERT INTO tb_products (
                desproduct,
                vlprice, 
                vlwidth, 
                vlheight, 
                vllength, 
                vlweight, 
                url, 
                idcategory
            ) VALUES (
                :desproduct, 
                :vlprice, 
                :vlwidth, 
                :vlheight, 
                :vllength, 
                :vlweight, 
                :url,
                :idcategory
            )
        ");
        $stmt->execute([
            ':desproduct' => $data['desproduct'],
            ':vlprice' => $data['vlprice'],
            ':vlwidth' => $data['vlwidth'] ?? null,
            ':vlheight' => $data['vlheight'] ?? null,
            ':vllength' => $data['vllength'] ?? null,
            ':vlweight' => $data['vlweight'] ?? null,
            ':url' => $data['url'],
            ':idcategory' => $data['idcategory']
        ]);

        return $pdo->lastInsertId();
    }

    public static function update(int $idproduct, array $data): void
    {
        $pdo = DB::getConnection();
        $stmt = $pdo->prepare("
            UPDATE tb_products
            SET desproduct = :desproduct,
                vlprice = :vlprice, 
                vlwidth = :vlwidth, 
                vlheight = :vlheight, 
                vllength = :vllength, 
                vlweight = :vlweight, 
                url = :url,
                idcategory = :idcategory
            WHERE idproduct = :idproduct
        ");

        $stmt->bindValue(':idproduct', $idproduct, PDO::PARAM_INT);
        $stmt->bindValue(':desproduct', $data['desproduct']);
        $stmt->bindValue(':vlprice', $data['vlprice']);
        $stmt->bindValue(':vlwidth', $data['vlwidth']);
        $stmt->bindValue(':vlheight', $data['vlheight']);
        $stmt->bindValue(':vllength', $data['vllength']);
        $stmt->bindValue(':vlweight', $data['vlweight']);
        $stmt->bindValue(':url', $data['url']);
        $stmt->bindValue(':idcategory', $data['idcategory'], PDO::PARAM_INT);

        $stmt->execute();

    }

    public static function delete(int $idproduct): void
    {
        $pdo = DB::getConnection();
        $stmt = $pdo->prepare("DELETE FROM tb_products WHERE idproduct = :idproduct");
        $stmt->bindValue(':idproduct', $idproduct, PDO::PARAM_INT);
        $stmt->execute();
    }
}