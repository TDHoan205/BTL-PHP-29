<?php
class Controller {
    public function model($model) {
        $modelFile = __DIR__ . '/../models/' . $model . '.php';
        if (file_exists($modelFile)) {
            require_once $modelFile;
            require_once __DIR__ . '/../config/Database.php';
            $database = new Database();
            $db = $database->getConnection();
            return new $model($db);
        } else {
            die("Model file $modelFile not found.");
        }
    }

    public function view($view, $data = []) {
        require_once '../app/views/' . $view . '.php';
    }
}