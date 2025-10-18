<?php

namespace App\Config;

use PDO;
use PDOException;

class DB {
    private static ?PDO $connection = null;

    public static function getConnection(): PDO {
        if (self::$connection === null) {
            try {
                $host = "127.0.0.1";
                $dbname = "ecommerce_zambia";
                $username = "root";
                $password = "";

                self::$connection = new PDO(
                    "mysql:host=$host;dbname=$dbname;charset=utf8",
                    $username,
                    $password
                );
            
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                // Log de erro para o dev
                error_log("Erro de conexão com o banco: " . $e->getMessage());

                // Show a generic error message to the user
                self::showDatabaseErrorPage();
                exit;
            }
        }

        return self::$connection;
    }

    private static function showDatabaseErrorPage(): void {
        http_response_code(500);
        echo <<<HTML
        <!DOCTYPE html>
        <html lang="pt-br">
        <head>
            <meta charset="UTF-8">
            <title>Erro 505 - Banco de Dados</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background: #f7f7f7;
                    text-align: center;
                    padding: 80px;
                    color: #333;
                }
                h1 { color: #c0392b; }
                button {
                    background-color: #3498db;
                    color: white;
                    border: none;
                    padding: 10px 20px;
                    border-radius: 5px;
                    cursor: pointer;
                    margin-top: 20px;
                }
                button:hover { background-color: #2980b9; }
            </style>
        </head>
        <body>
            <h1>⚠️ Erro ao conectar com o banco de dados</h1>
            <p>Por favor, verifique sua conexão com o servidor de banco de dados.</p>
            <p><strong>Código:</strong> 505 - Banco de dados desconectado</p>
            <button onclick="window.location.reload()">Tentar novamente</button>
        </body>
        </html>
        HTML;
    }
}