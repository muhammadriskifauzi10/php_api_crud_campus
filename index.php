<?php

include 'connect.php';

class Index extends Connect
{
    public function __construct()
    {
        parent::__construct();

        // Ambil URL
        $url = $_SERVER['REQUEST_URI'];
        $parts = parse_url($url);
        $path = $parts['path'];
        // Pecah URL Menjadi Array
        $segments = explode("/", $path);
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
                // Jika Method GET
            case "GET":
                if (count($segments) > 3 && is_numeric(end($segments))) {
                    // Ambil 1 Data
                    $sql = "SELECT * FROM students WHERE id=" . end($segments) . "";
                    $this->stmt = $this->mysqli->prepare($sql);
                } else {
                    // Ambil Semua Data
                    $sql = "SELECT * FROM students";
                    $this->stmt = $this->mysqli->prepare($sql);
                }

                if ($this->stmt->execute()) {
                    $result = $this->stmt->get_result();
                    $data = [];
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $data[] = $row;
                        }
                    }
                    echo json_encode([
                        "status" => 200,
                        "message" => "Get Data",
                        "data" => $data,
                    ]);
                }
                break;
                // Jika Method POST
            case "POST":
                // Dapatkan Input Data
                $data = json_decode(file_get_contents("php://input"), true);

                // Validasi Input Data Jika Salah Satu Input Data Tidak Ada
                if (!isset($data["name"]) || !isset($data["gender"]) || !isset($data["major"]) || !isset($data["address"])) {
                    echo json_encode([
                        "status" => 401,
                        "message" => "Opps, Error"
                    ]);
                    return;
                }

                // Defenisi Input Data
                $name = htmlspecialchars(ucwords($data["name"]), true);
                $gender = htmlspecialchars(ucwords($data["gender"]), true);
                $major = htmlspecialchars(ucwords($data["major"]), true);
                $address = htmlspecialchars(ucwords($data["address"]), true);

                // Masukkan Data Kedalam Table Database
                $sql = "INSERT INTO students (name, gender, major, address) VALUES (?,?,?,?)";
                $this->stmt = $this->mysqli->prepare($sql);
                $this->stmt->bind_param('ssss', $name, $gender, $major, $address);
                $this->stmt->execute();
                echo json_encode([
                    "status" => 201,
                    "message" => "Post Data"
                ]);
                break;
                // Jika Method PUT
            case "PUT":
                // Dapatkan Input Data
                $data = json_decode(file_get_contents("php://input"), true);

                // Validasi Input Data Jika Salah Satu Input Data Tidak Ada
                if (!isset($data["id"]) || !isset($data["name"]) || !isset($data["gender"]) || !isset($data["major"]) || !isset($data["address"])) {
                    echo json_encode([
                        "status" => 401,
                        "message" => "Opps, Error"
                    ]);
                    return;
                }

                // Defenisi Input Data
                $id = $data["id"];
                $name = htmlspecialchars(ucwords($data["name"]), true);
                $gender = htmlspecialchars(ucwords($data["gender"]), true);
                $major = htmlspecialchars(ucwords($data["major"]), true);
                $address = htmlspecialchars(ucwords($data["address"]), true);

                // Edit Data dalam Table Database
                $sql = "UPDATE students SET name=?, gender=?, major=?, address=?, updated_at=date('Y-m-d H:i:s') WHERE id='$id'";
                $this->stmt = $this->mysqli->prepare($sql);
                $this->stmt->bind_param('ssss', $name, $gender, $major, $address);
                $this->stmt->execute();
                echo json_encode([
                    "status" => 200,
                    "message" => "Put Data"
                ]);
                break;
                // Jika Method DELETE
            case "DELETE":
                if (count($segments) > 3 && is_numeric(end($segments))) {
                    // Delete 1 Data
                    $sql = "DELETE FROM students WHERE id=" . end($segments) . "";
                    $this->stmt = $this->mysqli->prepare($sql);
                    $this->stmt->execute();
                } else {
                    echo json_encode([
                        "status" => 401,
                        "message" => "Opps, Error"
                    ]);
                    return;
                }

                echo json_encode([
                    "status" => 200,
                    "message" => "Delete Data"
                ]);
                break;
                // Jika Method DEFAULT
            default:
                echo json_encode([
                    "status" => 401,
                    "message" => "Not Found"
                ]);
                break;
        }
    }
}

// Inisialisasi Pemanggilan Class
new Index();
