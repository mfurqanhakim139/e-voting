<?php
// Selalu mulai session di paling atas
session_start();

/**
 * =================================================================
 * 1. BOOTSTRAP APLIKASI
 * =================================================================
 */
require __DIR__ . '/../vendor/autoload.php';
try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
} catch (\Dotenv\Exception\InvalidPathException $e) {
    die("File .env tidak ditemukan. Harap salin .env.example menjadi .env");
}
date_default_timezone_set('Asia/Jakarta');
ini_set('display_errors', 1);
error_reporting(E_ALL);

/**
 * =================================================================
 * 2. DEKLARASI CONTROLLER (Namespaces)
 * =================================================================
 */
// Controller untuk alur Pemilih (Voter)
use Ugkmb\Evoting\Controllers\AuthController;
use Ugkmb\Evoting\Controllers\VoteController;

// Controller untuk alur Admin
use Ugkmb\Evoting\Controllers\Admin\AuthController as AdminAuthController;
use Ugkmb\Evoting\Controllers\Admin\DashboardController;
use Ugkmb\Evoting\Controllers\Admin\ElectionController;
use Ugkmb\Evoting\Controllers\Admin\CandidateController;
use Ugkmb\Evoting\Controllers\Admin\VoterController;
use Ugkmb\Evoting\Controllers\Admin\ResultController;

/**
 * =================================================================
 * 3. INISIALISASI ROUTER
 * =================================================================
 */
$router = new \Bramus\Router\Router();

/**
 * =================================================================
 * 4. MIDDLEWARE (Filter Keamanan)
 * =================================================================
 */
// Middleware: Cek apakah pemilih (voter) sudah login
$router->before('GET|POST', '/vote.*|/terima-kasih', function() {
    if (!isset($_SESSION['voter_id'])) {
        header('Location: /login?error=auth');
        exit();
    }
});
// Middleware: Cek apakah ADMIN sudah login
$router->before('GET|POST', '/admin/.*', function() {
    if (strpos($_SERVER['REQUEST_URI'], '/admin/login') === false) {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: /admin/login?error=auth');
            exit();
        }
    }
});

/**
 * =================================================================
 * 5. DEFINISI ROUTE (PETA JALAN LENGKAP)
 * =================================================================
 */

// == GRUP 1: Rute Publik & Pemilih (Voter) ==
// ----------------------------------------------------
$router->get('/', function() { (new AuthController())->showLoginPage(); });
$router->get('/login', function() { (new AuthController())->showLoginPage(); });
$router->post('/login', function() { (new AuthController())->processLogin(); });
$router->get('/logout', function() { (new AuthController())->logout(); });

// == INI PERUBAHANNYA ==
// GET /vote sekarang akan menampilkan halaman "Pilih Event"
$router->get('/vote', function() { (new VoteController())->showEventSelectionPage(); });
// GET /vote/{id} akan menampilkan halaman "Surat Suara" untuk event tsb
$router->get('/vote/(\d+)', function($electionId) { (new VoteController())->showVotePage($electionId); });
// ======================

$router->post('/vote/submit', function() { (new VoteController())->submitVote(); });
$router->get('/terima-kasih', function() { (new VoteController())->showThankYouPage(); });

// == GRUP 2: Rute Panel Admin ==
// ----------------------------------------------------
$router->get('/admin/login', function() { (new AdminAuthController())->showLoginPage(); });
$router->post('/admin/login', function() { (new AdminAuthController())->processLogin(); });
$router->get('/admin/logout', function() { (new AdminAuthController())->logout(); });
$router->get('/admin', function() { (new DashboardController())->index(); });
$router->get('/admin/dashboard', function() { (new DashboardController())->index(); });
$router->get('/admin/elections', function() { (new ElectionController())->index(); });
$router->get('/admin/elections/new', function() { (new ElectionController())->create(); });
$router->post('/admin/elections', function() { (new ElectionController())->store(); });
$router->get('/admin/elections/(\d+)/edit', function($id) { (new ElectionController())->edit($id); });
$router->post('/admin/elections/(\d+)', function($id) { (new ElectionController())->update($id); });
$router->post('/admin/elections/(\d+)/delete', function($id) { (new ElectionController())->destroy($id); });
$router->get('/admin/elections/(\d+)/candidates', function($electionId) { (new CandidateController())->index($electionId); });
$router->get('/admin/elections/(\d+)/candidates/new', function($electionId) { (new CandidateController())->create($electionId); });
$router->post('/admin/elections/(\d+)/candidates', function($electionId) { (new CandidateController())->store($electionId); });
$router->get('/admin/elections/(\d+)/candidates/(\d+)/edit', function($electionId, $candidateId) { (new CandidateController())->edit($electionId, $candidateId); });
$router->post('/admin/elections/(\d+)/candidates/(\d+)', function($electionId, $candidateId) { (new CandidateController())->update($electionId, $candidateId); });
$router->post('/admin/elections/(\d+)/candidates/(\d+)/delete', function($electionId, $candidateId) { (new CandidateController())->destroy($electionId, $candidateId); });
$router->get('/admin/voters', function() { (new VoterController())->index(); });
$router->get('/admin/voters/(\d+)', function($voterId) { (new VoterController())->show($voterId); });
$router->post('/admin/voters/(\d+)/reset', function($voterId) { (new VoterController())->resetVote($voterId); });
$router->get('/admin/results', function() { (new ResultController())->index(); });
$router->get('/admin/results/(\d+)', function($electionId) { (new ResultController())->show($electionId); });
$router->get('/admin/results/(\d+)/print', function($electionId) { (new ResultController())->print($electionId); });

/**
 * =================================================================
 * 6. JALANKAN ROUTER
 * =================================================================
 */
$router->run();