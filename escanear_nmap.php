<?php
include "connect.php";
$msg = "";
session_start();

// verifica se existe uma sessão válida, senão redireciona para a página de login
if (!isset($_SESSION['email'])) {
    header('Location: index.php?msg=Acesso negado.');
    exit();
}

/**
 * NmapScanner
 * ------------
 * Executa uma varredura de rede utilizando o Nmap e devolve, para cada
 * dispositivo encontrado: endereço IPv4, endereço MAC, marca/fabricante
 * (vendor), hostname e sistema operacional estimado.
 *
 * Requisitos:
 *  - O Nmap precisa estar instalado no computador que está rodando o
 *    Apache/PHP (nesta máquina, em C:\Arquivos de Programas (x86)\Nmap).
 *  - No Windows, quem precisa ter privilégio de administrador é o
 *    próprio processo do Apache/PHP (é necessário rodar o XAMPP "como
 *    administrador"), pois a detecção de sistema operacional e de
 *    endereço MAC depende disso.
 */
class NmapScanner
{
    private string $nmapPath;

    public function __construct(string $nmapPath)
    {
        $this->nmapPath = $nmapPath;
    }

    /**
     * Executa a varredura em uma sub-rede (por exemplo, "192.168.1.0/24")
     * e devolve um array associativo com os dados de cada host ativo.
     */
    public function scanNetwork(string $subnet): array
    {
        $subnetEscapado = escapeshellarg($subnet);
        $arquivoSaida = tempnam(sys_get_temp_dir(), 'nmap_') . '.xml';

        // -O             : tenta detectar o sistema operacional do
        //                  dispositivo. Essa detecção exige uma varredura
        //                  de portas de verdade, por isso não é usado o
        //                  parâmetro "-sn", que faria apenas a descoberta
        //                  de hosts sem testar nenhuma porta.
        // --osscan-guess : aumenta a chance do nmap arriscar um sistema
        //                  operacional quando não há certeza total.
        // -oX            : salva o resultado em formato XML, que é bem
        //                  mais fácil de interpretar do que o texto
        //                  normal exibido no terminal.
        $comando = sprintf(
            '%s -O --osscan-guess -oX %s %s 2>&1',
            escapeshellarg($this->nmapPath),
            escapeshellarg($arquivoSaida),
            $subnetEscapado
        );

        exec($comando, $saidaComando, $codigoRetorno);

        if ($codigoRetorno !== 0 || !file_exists($arquivoSaida)) {
            $mensagem = implode("\n", $saidaComando);

            // No Windows, o terminal costuma devolver o texto na
            // codificação CP-850 em vez de UTF-8, o que corrompe os
            // acentos. Por isso a mensagem é convertida antes de ser
            // exibida na tela.
            if (PHP_OS_FAMILY === 'Windows' && function_exists('mb_convert_encoding')) {
                $convertido = @mb_convert_encoding($mensagem, 'UTF-8', 'CP850');

                if ($convertido !== false) {
                    $mensagem = $convertido;
                }
            }

            throw new RuntimeException(
                "Erro ao executar o nmap: {$mensagem}\nComando executado: {$comando}"
            );
        }

        $dispositivos = $this->parseXml($arquivoSaida);
        unlink($arquivoSaida);

        return $dispositivos;
    }

    /**
     * Faz o parse do XML gerado pelo nmap e extrai os campos desejados
     * para cada dispositivo encontrado.
     */
    private function parseXml(string $arquivoXml): array
    {
        $xml = @simplexml_load_file($arquivoXml);

        if ($xml === false) {
            throw new RuntimeException('Erro ao interpretar o XML gerado pelo nmap.');
        }

        $dispositivos = [];

        foreach ($xml->host as $host) {
            // Ignora hosts que não responderam ao escaneamento.
            $estado = (string) $host->status['state'];

            if ($estado !== 'up') {
                continue;
            }

            $dispositivo = [
                'ipv4' => '',
                'mac' => '',
                'marca' => '',
                'hostname' => '',
                'sistema_operacional' => '',
            ];

            // Um mesmo host pode ter mais de um endereço listado (IPv4 e
            // MAC), por isso é necessário percorrer a lista de endereços.
            foreach ($host->address as $endereco) {
                $tipo = (string) $endereco['addrtype'];

                if ($tipo === 'ipv4') {
                    $dispositivo['ipv4'] = (string) $endereco['addr'];
                }

                if ($tipo === 'mac') {
                    $dispositivo['mac'] = (string) $endereco['addr'];

                    // O nmap só preenche o atributo "vendor" quando
                    // reconhece o fabricante pelo prefixo do endereço
                    // MAC (chamado de OUI). Quando não reconhece, o
                    // campo simplesmente não existe no XML.
                    if (isset($endereco['vendor'])) {
                        $dispositivo['marca'] = (string) $endereco['vendor'];
                    }
                }
            }

            // O hostname pode não existir, dependendo do dispositivo e
            // da configuração de DNS reverso da rede.
            if (isset($host->hostnames->hostname)) {
                $dispositivo['hostname'] = (string) $host->hostnames->hostname['name'];
            }

            // O nmap pode sugerir mais de um "osmatch", cada um com um
            // percentual de precisão diferente. Aqui é escolhido o
            // palpite com a maior precisão.
            if (isset($host->os->osmatch)) {
                $melhorNome = '';
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

// Caminho do executável do nmap nesta máquina. Caso o programa esteja
// instalado em outro local, ou o sistema seja usado em outro
// computador, esse caminho precisa ser ajustado.
$caminho_nmap = 'C:\Program Files (x86)\\Nmap\\nmap.exe';

/**
 * Recebe um valor vindo do escaneamento e devolve um texto para exibir
 * na tabela. Quando o Nmap não consegue descobrir a informação (o que é
 * comum com hostname, MAC e marca, já que dependem de DNS reverso e de
 * privilégio de administrador), o valor chega vazio, e essa função
 * mostra um aviso ao invés de deixar a célula em branco.
 */
function textoParaExibicao(string $valor): string
{
    if ($valor === "") {
        return "Não identificado";
    }

    return $valor;
}

$dispositivos_encontrados = [];
$rede_pesquisada = "";

// O formulário de escaneamento é enviado pelo método POST.
if ($_POST) {
    $rede_pesquisada = $_POST['subnet'];

    try {
        $scanner = new NmapScanner($caminho_nmap);
        $dispositivos_encontrados = $scanner->scanNetwork($rede_pesquisada);

        if (count($dispositivos_encontrados) === 0) {
            $msg = "Nenhum dispositivo respondeu dentro da faixa informada.";
        }
    } catch (Exception $erro) {
        $msg = "Erro ao escanear a rede: " . $erro->getMessage();
    }
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

    <div class="fundo-tabela">
        <div class="card-tabela">

            <div class="titulo">
                <h2>Escanear Equipamentos</h2>
            </div>

            <!-- Formulário onde o usuário informa a faixa de rede a ser escaneada -->
            <div class="painel-filtros">
                <div class="filtros-titulo">Escanear rede</div>
                <form method="post" action="">
                    <div class="filtros-grid">
                        <div class="filtro-grupo">
                            <label for="subnet">Rede a ser escaneada</label>
                            <input type="text" id="subnet" name="subnet" placeholder="Ex.: 192.168.1.0/24"
                                value="<?php echo htmlspecialchars($rede_pesquisada); ?>" required>
                        </div>
                    </div>
                    <div class="filtros-acoes" style="margin-top: 12px;">
                        <button type="submit" class="btn-filtrar">Escanear</button>
                    </div>
                </form>
            </div>

            <?php if ($msg !== "") { ?>
                <p class="info-resultado"><?php echo $msg; ?></p>
            <?php } ?>

            <!-- Tabela com os dispositivos encontrados, exibida apenas depois de um escaneamento -->
            <?php if (count($dispositivos_encontrados) > 0) { ?>
                <table>
                    <thead>
                        <tr>
                            <th>Hostname</th>
                            <th>IPv4</th>
                            <th>MAC</th>
                            <th>Marca</th>
                            <th>Sistema Operacional</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dispositivos_encontrados as $dispositivo) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars(textoParaExibicao($dispositivo['hostname'])); ?></td>
                                <td><?php echo htmlspecialchars($dispositivo['ipv4']); ?></td>
                                <td><?php echo htmlspecialchars(textoParaExibicao($dispositivo['mac'])); ?></td>
                                <td><?php echo htmlspecialchars(textoParaExibicao($dispositivo['marca'])); ?></td>
                                <td><?php echo htmlspecialchars(textoParaExibicao($dispositivo['sistema_operacional'])); ?></td>
                                <td>
                                    <div class="opcoes">
                                        <!--
                                            Os dados do dispositivo são enviados pela própria URL
                                            (método GET) para a tela de cadastro. Quando algum campo
                                            não foi identificado pelo Nmap, ele chega vazio, e o
                                            usuário pode preenchê-lo manualmente antes de salvar.
                                        -->
                                        <a class="btn-salvar" href="cadastro_maquina.php?hostname=<?php echo urlencode($dispositivo['hostname']); ?>&ipv4=<?php echo urlencode($dispositivo['ipv4']); ?>&mac=<?php echo urlencode($dispositivo['mac']); ?>&marca=<?php echo urlencode($dispositivo['marca']); ?>&so=<?php echo urlencode($dispositivo['sistema_operacional']); ?>">
                                            Cadastrar
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <p class="info-resultado">
                    Os campos marcados como "Não identificado" não foram encontrados pelo Nmap.
                    Isso costuma acontecer quando o programa não está sendo executado com
                    privilégio de administrador. Você pode preenchê-los manualmente na tela
                    de cadastro antes de salvar.
                </p>
            <?php } ?>

        </div>
    </div>
</body>

</html>