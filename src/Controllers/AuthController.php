<?php
namespace Ugkmb\Evoting\Controllers;

use Ugkmb\Evoting\Config\Database;
use PDO;

class AuthController {

    public function showLoginPage() {
        // Memanggil file template login
        require_once __DIR__ . '/../../templates/voter/login.php';
    }

    public function processLogin() {
        $nipd = $_POST['nipd'] ?? null;
        $tgl_lahir = $_POST['tgl_lahir'] ?? null;

        if (!$nipd || !$tgl_lahir) {
            $this->redirect('/login?error=empty');
            return;
        }

        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT * FROM voters WHERE nipd = :nipd");
            $stmt->execute([':nipd' => $nipd]);
            $voter = $stmt->fetch();

            if (!$voter) {
                $this->redirect('/login?error=notfound');
                return;
            }

            if (!password_verify($tgl_lahir, $voter['tgl_lahir_hash'])) {
                $this->redirect('/login?error=wrongpass');
                return;
            }

            // TODO: Cek apakah sudah vote di event yg aktif
            // if ($voter['has_voted']) {
            //     $this->redirect('/login?error=voted');
            //     return;
            // }

            // Login sukses, simpan di session
            $_SESSION['voter_id'] = $voter['id'];
            $_SESSION['voter_name'] = $voter['nm_pd'];
            $_SESSION['voter_nipd'] = $voter['nipd'];

            $this->redirect('/vote');

        } catch (\Exception $e) {
            $this->redirect('/login?error=system');
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        $this->redirect('/login?status=loggedout');
    }

    private function redirect($url) {
        header("Location: " . $url);
        exit();
    }
}