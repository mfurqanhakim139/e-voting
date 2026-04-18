<?php
namespace Ugkmb\Evoting\Controllers\Admin;

use Ugkmb\Evoting\Config\Database;
use PDO;

class ElectionController {

    private $pdo;

    public function __construct() {
        // Mengambil koneksi database dari file Config
        $this->pdo = Database::getConnection();
    }

    /**
     * Menampilkan daftar semua event (Read)
     * Ini dipanggil oleh GET /admin/elections
     */
    public function index() {
        // Ambil semua data event dari database
        $stmt = $this->pdo->query("SELECT * FROM elections ORDER BY id DESC");
        $elections = $stmt->fetchAll();
        
        // Tampilkan file view-nya
        require_once __DIR__ . '/../../../templates/admin/elections/index.php';
    }

    /**
     * Menampilkan form untuk membuat event baru (Create Form)
     * Ini dipanggil oleh GET /admin/elections/new
     */
    public function create() {
        $election = []; // Siapkan variabel kosong agar form tidak error
        require_once __DIR__ . '/../../../templates/admin/elections/form.php';
    }

    /**
     * Menyimpan event baru ke database (Create Logic)
     * Ini dipanggil oleh POST /admin/elections
     */
    public function store() {
        // Ambil data dari form
        $name = $_POST['name'];
        $status = $_POST['status'];
        $start_time = !empty($_POST['start_time']) ? $_POST['start_time'] : null;
        $end_time = !empty($_POST['end_time']) ? $_POST['end_time'] : null;

        // Masukkan ke database
        $stmt = $this->pdo->prepare(
            "INSERT INTO elections (name, status, start_time, end_time) 
             VALUES (:name, :status, :start, :end)"
        );
        $stmt->execute([
            ':name' => $name,
            ':status' => $status,
            ':start' => $start_time,
            ':end' => $end_time
        ]);

        // Kembalikan ke halaman daftar event
        $this->redirect('/admin/elections');
    }

    /**
     * Menampilkan form untuk mengedit event (Update Form)
     * Ini dipanggil oleh GET /admin/elections/{id}/edit
     */
    public function edit($id) {
        // Ambil data event yang mau diedit
        $stmt = $this->pdo->prepare("SELECT * FROM elections WHERE id = ?");
        $stmt->execute([$id]);
        $election = $stmt->fetch();

        // Tampilkan file view (form yang sama dengan 'create')
        require_once __DIR__ . '/../../../templates/admin/elections/form.php';
    }

    /**
     * Memperbarui event di database (Update Logic)
     * Ini dipanggil oleh POST /admin/elections/{id}
     */
    public function update($id) {
        // Ambil data dari form
        $name = $_POST['name'];
        $status = $_POST['status'];
        $start_time = !empty($_POST['start_time']) ? $_POST['start_time'] : null;
        $end_time = !empty($_POST['end_time']) ? $_POST['end_time'] : null;

        // Update data di database
        $stmt = $this->pdo->prepare(
            "UPDATE elections SET name = :name, status = :status, start_time = :start, end_time = :end 
             WHERE id = :id"
        );
        $stmt->execute([
            ':name' => $name,
            ':status' => $status,
            ':start' => $start_time,
            ':end' => $end_time,
            ':id' => $id
        ]);

        // Kembalikan ke halaman daftar event
        $this->redirect('/admin/elections');
    }

    /**
     * Menghapus event (Delete)
     * Ini dipanggil oleh POST /admin/elections/{id}/delete
     */
    public function destroy($id) {
        // Hapus data dari database
        $stmt = $this->pdo->prepare("DELETE FROM elections WHERE id = ?");
        $stmt->execute([$id]);

        // Kembalikan ke halaman daftar event
        $this->redirect('/admin/elections');
    }

    // Fungsi helper untuk redirect
    private function redirect($url) {
        header("Location: " . $url);
        exit();
    }
}