<?php
include "connect.php";

$msg = "";
session_start();

// verifica se existe uma sessão válida, senão redireciona para a página de login
if (!isset($_SESSION['email'])) {
    header('Location: index.php?msg=Acesso negado.');
    exit();
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <?php include "head.php"; // Inclui o head de navegação 
    ?>
</head>

<body>
    <?php include "menu.php"; // Inclui o menu de navegação 
    ?>
<?php
/**
 * NmapScanner
 * ------------
 * Executa uma varredura de rede via nmap e retorna, para cada dispositivo
 * encontrado: endereço IPv4, endereço MAC, marca/fabricante (vendor),
 * hostname e sistema operacional estimado.
 *
 * Requisitos:
 *  - nmap instalado no servidor (ex: /usr/bin/nmap)
 *  - Permissão para o usuário do Apache/PHP (www-data) executar nmap
 *    com privilégios elevados (necessário para -O e detecção de MAC via ARP).
 *    Veja as observações no final do arquivo.
 */

class NmapScanner
{
    private string $nmapPath;

    public function __construct(string $nmapPath = '/usr/bin/nmap')
    {
        $this->nmapPath = $nmapPath;
    }

    /**
     * Executa a varredura em uma sub-rede (ex: "192.168.1.0/24")
     * e retorna um array associativo com os dados de cada host ativo.
     */
    public function scanNetwork(string $subnet): array
    {
        $subnetEscapado = escapeshellarg($subnet);
        $arquivoSaida = tempnam(sys_get_temp_dir(), 'nmap_') . '.xml';

        // -O         : tenta detectar o sistema operacional. Isso EXIGE uma
        //              varredura de portas de verdade (o nmap usa as respostas
        //              das portas para inferir o SO) — por isso não usamos "-sn"
        //              aqui, que faria só descoberta de host sem varrer portas.
        // --osscan-guess : aumenta a chance de "chutar" um SO quando não há certeza total
        // -oX        : salva a saída em formato XML (muito mais confiável de parsear que texto puro)
        //
        // OBS: NÃO usamos "sudo" aqui. No Windows não existe esse conceito da
        // mesma forma que no Linux — quem precisa ter privilégio de administrador
        // é o próprio processo do Apache/PHP (rode o XAMPP como administrador).
        // Em ambiente Linux, o equivalente seria configurar o sudoers para o
        // usuário www-data poder rodar o nmap sem senha.
        $comando = sprintf(
            '%s -O --osscan-guess -oX %s %s 2>&1',
            escapeshellarg($this->nmapPath),
            escapeshellarg($arquivoSaida),
            $subnetEscapado
        );

        exec($comando, $saidaComando, $codigoRetorno);

        if ($codigoRetorno !== 0 || !file_exists($arquivoSaida)) {
            $mensagem = implode("\n", $saidaComando);

            // No Windows, o terminal costuma usar CP-850/CP-1252 em vez de UTF-8,
            // o que corrompe acentos (ex: "n�o" em vez de "não"). Convertemos aqui
            // para exibir a mensagem de erro corretamente.
            if (PHP_OS_FAMILY === 'Windows' && function_exists('mb_convert_encoding')) {
                $convertido = @mb_convert_encoding($mensagem, 'UTF-8', 'CP850');
                if ($convertido !== false) {
                    $mensagem = $convertido;
                }
            }

            throw new RuntimeException(
                "Erro ao executar o nmap: {$mensagem}\n" .
                "Comando executado: {$comando}"
            );
        }

        $dispositivos = $this->parseXml($arquivoSaida);
        unlink($arquivoSaida);

        return $dispositivos;
    }

    /**
     * Faz o parse do XML gerado pelo nmap e extrai os campos desejados.
     */
    private function parseXml(string $arquivoXml): array
    {
        $xml = @simplexml_load_file($arquivoXml);

        if ($xml === false) {
            throw new RuntimeException('Erro ao interpretar o XML gerado pelo nmap.');
        }

        $dispositivos = [];

        foreach ($xml->host as $host) {
            // Ignora hosts que não estão "up"
            $estado = (string) $host->status['state'];
            if ($estado !== 'up') {
                continue;
            }

            $dispositivo = [
                'ipv4'                => null,
                'mac'                 => null,
                'marca'               => null,
                'hostname'            => null,
                'sistema_operacional' => null,
            ];

            // Endereços (pode haver mais de um: ipv4 e mac)
            foreach ($host->address as $endereco) {
                $tipo = (string) $endereco['addrtype'];

                if ($tipo === 'ipv4') {
                    $dispositivo['ipv4'] = (string) $endereco['addr'];
                } elseif ($tipo === 'mac') {
                    $dispositivo['mac'] = (string) $endereco['addr'];
                    // O nmap só preenche "vendor" quando reconhece o fabricante
                    // pelo prefixo do MAC (OUI). Se não reconhecer, fica vazio.
                    $dispositivo['marca'] = isset($endereco['vendor'])
                        ? (string) $endereco['vendor']
                        : null;
                }
            }

            // Hostname (pode não existir, dependendo do dispositivo/DNS reverso)
            if (isset($host->hostnames->hostname)) {
                $dispositivo['hostname'] = (string) $host->hostnames->hostname['name'];
            }

            // Sistema operacional: o nmap pode sugerir vários "osmatch" com
            // percentuais de precisão diferentes. Aqui pegamos o de maior precisão.
            if (isset($host->os->osmatch)) {
                $melhorNome = null;
                $melhorPrecisao = 0;

                foreach ($host->os->osmatch as $osmatch) {
                    $precisao = (int) $osmatch['accuracy'];
                    if ($precisao > $melhorPrecisao) {
                        $melhorPrecisao = $precisao;
                        $melhorNome = (string) $osmatch['name'];
                    }
                }

                $dispositivo['sistema_operacional'] = $melhorNome;
            }

            $dispositivos[] = $dispositivo;
        }

        return $dispositivos;
    }
}

/* =========================================================
 * EXEMPLO DE USO
 * ========================================================= */

try {
    // ATENÇÃO: ajuste esse caminho para o local real do nmap.exe na sua máquina.
    // Descubra rodando "where nmap" no Prompt de Comando.
    // Em Linux/servidor seria algo como '/usr/bin/nmap'.
    $scanner = new NmapScanner('C:\\Program Files (x86)\\Nmap\\nmap.exe');
    $dispositivos = $scanner->scanNetwork('192.168.1.0/24');

    foreach ($dispositivos as $d) {
        echo "IPv4: "                . ($d['ipv4'] ?? 'N/D') . "\n";
        echo "MAC: "                 . ($d['mac'] ?? 'N/D') . "\n";
        echo "Marca (Vendor): "      . ($d['marca'] ?? 'N/D') . "\n";
        echo "Hostname: "            . ($d['hostname'] ?? 'N/D') . "\n";
        echo "Sistema Operacional: " . ($d['sistema_operacional'] ?? 'N/D') . "\n";
        echo str_repeat('-', 40) . "\n";
    }
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
?>

</body>

</html>