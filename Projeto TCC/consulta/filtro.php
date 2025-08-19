<?php
include '../cadastro/conexao.php';

$firstname = $_POST['nome'];

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT * FROM usuario WHERE nome = :firstname");
    $stmt->bindParam(':firstname', $firstname);
    $stmt->execute();

    echo '
        <style>
            table {
                width: 80%;
                margin: 20px auto;
                border-collapse: collapse;
            }

            th, td {
                border: 1px solid #555;
                padding: 10px;
                text-align: left;
            }

            th {
                background-color: #8C5B3F;
                color: white;
            }

            tr:nth-child(even) {
                background-color: #f2f2f2;
            }

            tr:hover {
                background-color: #ddd;
            }
        </style>
    ';

    // Verificar se há resultados
    if ($stmt->rowCount() > 0) {
        echo "<table border='1'>
                <tr>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Endereço</th>
                    <th>Telefone</th>
                    <th>Email</th>
                    <th>Senha</th>
                </tr>";

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['nome']) . "</td>
                    <td>" . htmlspecialchars($row['cpf']) . "</td>
                    <td>" . htmlspecialchars($row['endereco']) . "</td>
                    <td>" . htmlspecialchars($row['telefone']) . "</td>
                    <td>" . htmlspecialchars($row['email']) . "</td>
                    <td>" . htmlspecialchars($row['senha']) . "</td>
                  </tr>";
        }

        echo "</table>";
    } else {
        echo "Nenhum resultado encontrado.";
    }
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}

$conn = null;
?>
