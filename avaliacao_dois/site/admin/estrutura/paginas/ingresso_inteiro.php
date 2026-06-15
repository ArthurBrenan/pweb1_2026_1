<?php
session_start();

// Verificar se usuário está logado
if(!isset($_SESSION['usuario_id'])) {
    header('Location: ../admin/login.php');
    exit;
}

// Incluir classes necessárias - ajustando caminhos
require_once $_SERVER['DOCUMENT_ROOT'] . '/pweb1_2026_1/avaliacao_dois/site/admin/db.class.php';

// Instanciar classes
$usuarioDB = new db('usuario');
$ingressoDB = new db('ingresso');

// Buscar dados do usuário logado
$usuario = $usuarioDB->find($_SESSION['usuario_id']);

// Processar compra
$mensagem = '';
$tipo_mensagem = '';

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comprar'])) {
    $tipo_ingresso = $_POST['tipo_ingresso'];
    $quantidade = (int)$_POST['quantidade'];
    $pagamento = $_POST['pagamento'];
    
    // Buscar o ingresso no banco
    $ingressos = $ingressoDB->all();
    $ingresso_selecionado = null;
    
    foreach($ingressos as $ing) {
        if($ing->nome == $tipo_ingresso) {
            $ingresso_selecionado = $ing;
            break;
        }
    }
    
    if($ingresso_selecionado && $quantidade > 0) {
        if($ingresso_selecionado->quantidade >= $quantidade) {
            // Atualizar estoque
            $novo_estoque = $ingresso_selecionado->quantidade - $quantidade;
            $atualizado = $ingressoDB->update($ingresso_selecionado->id, ['quantidade' => $novo_estoque]);
            
            if($atualizado) {
                $mensagem = "✅ Compra concluída com sucesso! Você comprou $quantidade ingresso(s) para \"$tipo_ingresso\". Pagamento: $pagamento";
                $tipo_mensagem = "success";
                
                // Recarregar dados do ingresso após atualização
                $ingressos = $ingressoDB->all();
                foreach($ingressos as $ing) {
                    if($ing->nome == $tipo_ingresso) {
                        $ingresso_selecionado = $ing;
                        break;
                    }
                }
            } else {
                $mensagem = "❌ Erro ao processar a compra. Tente novamente.";
                $tipo_mensagem = "error";
            }
        } else {
            $mensagem = "❌ Estoque insuficiente! Disponível: " . $ingresso_selecionado->quantidade . " ingressos.";
            $tipo_mensagem = "error";
        }
    } else {
        $mensagem = "❌ Quantidade inválida ou ingresso não encontrado.";
        $tipo_mensagem = "error";
    }
}

// Verificar qual tipo de ingresso foi selecionado
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'PASSAGEM DE IDA';

// Buscar estoque atual do ingresso selecionado
$ingressos = $ingressoDB->all();
$estoque_atual = 0;
foreach($ingressos as $ing) {
    if($ing->nome == $tipo) {
        $estoque_atual = $ing->quantidade;
        break;
    }
}

// Descrições dos ingressos
$descricoes = [
    'PASSAGEM DE IDA' => [
        'icone' => '🚪',
        'texto' => 'Para quem só quer dar uma espadinha no além sem se comprometer com a eternidade.'
    ],
    'CICLO COMPLETO' => [
        'icone' => '🔄',
        'texto' => 'Três dias inteiros de imersão total com quem já partiu.'
    ],
    'EXPERIÊNCIA DE QUASE-MORTE' => [
        'icone' => '✨',
        'texto' => 'A exclusividade que só quem tem conexões no submundo merece.'
    ]
];

$icone = isset($descricoes[$tipo]['icone']) ? $descricoes[$tipo]['icone'] : '🎫';
$descricao = isset($descricoes[$tipo]['texto']) ? $descricoes[$tipo]['texto'] : 'Experiência única no além.';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingresso - Experiência no Além</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #212529;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .ingresso-card {
            background: linear-gradient(135deg, #1a1a1a 0%, #0d0d0d 100%);
            border: 1px solid #f1c40f;
            border-radius: 20px;
            padding: 40px;
            margin: 40px auto;
            max-width: 700px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.5), 0 0 20px rgba(241,196,15,0.3);
            position: relative;
            overflow: hidden;
        }
        
        .ingresso-card::before {
            content: "☠";
            position: absolute;
            font-size: 200px;
            opacity: 0.05;
            bottom: -30px;
            right: -30px;
            font-family: monospace;
        }
        
        .ingresso-card::after {
            content: "⚰";
            position: absolute;
            font-size: 150px;
            opacity: 0.05;
            top: -20px;
            left: -20px;
            font-family: monospace;
        }
        
        .header-icon {
            font-size: 48px;
            text-align: center;
            margin-bottom: 20px;
            filter: drop-shadow(0 0 10px #f1c40f);
        }
        
        .tipo-ingresso {
            background: rgba(241,196,15,0.1);
            border-left: 4px solid #f1c40f;
            padding: 10px 20px;
            margin-bottom: 30px;
            border-radius: 8px;
        }
        
        .preco {
            font-size: 48px;
            font-weight: bold;
            color: #f1c40f;
            text-shadow: 0 0 10px rgba(241,196,15,0.5);
        }
        
        .form-label {
            color: #f1c40f;
            font-weight: 600;
            letter-spacing: 1px;
        }
        
        .form-control, .form-select {
            background-color: #2c2c2c;
            border: 1px solid #444;
            color: #fff;
            border-radius: 10px;
        }
        
        .form-control:focus, .form-select:focus {
            background-color: #333;
            border-color: #f1c40f;
            box-shadow: 0 0 0 0.2rem rgba(241,196,15,0.25);
            color: #fff;
        }
        
        .form-control[readonly] {
            background-color: #1a1a1a;
            cursor: not-allowed;
        }
        
        .btn-comprar {
            background-color: #f1c40f;
            color: #000;
            font-weight: bold;
            padding: 12px 30px;
            border-radius: 50px;
            font-size: 18px;
            transition: all 0.3s ease;
            width: 100%;
            border: none;
        }
        
        .btn-comprar:hover:not(:disabled) {
            background-color: #ffd700;
            transform: scale(1.02);
            box-shadow: 0 5px 20px rgba(241,196,15,0.4);
        }
        
        .btn-comprar:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .btn-voltar {
            background-color: transparent;
            border: 2px solid #f1c40f;
            color: #f1c40f;
            padding: 10px 25px;
            border-radius: 50px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        
        .btn-voltar:hover {
            background-color: #f1c40f;
            color: #000;
        }
        
        .badge-espiritual {
            background-color: #2c2c2c;
            color: #f1c40f;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: normal;
        }
        
        hr {
            background-color: #f1c40f;
            height: 1px;
            opacity: 0.3;
        }
        
        .total {
            background-color: rgba(241,196,15,0.05);
            border-radius: 15px;
            padding: 15px;
            margin-top: 20px;
        }
        
        .alert-success {
            background-color: rgba(40, 167, 69, 0.2);
            border: 1px solid #28a745;
            color: #28a745;
            border-radius: 10px;
        }
        
        .alert-error {
            background-color: rgba(220, 53, 69, 0.2);
            border: 1px solid #dc3545;
            color: #dc3545;
            border-radius: 10px;
        }
        
        .estoque-badge {
            background-color: #f1c40f;
            color: #000;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="ingresso-card">
        
        <!-- Ícone decorativo -->
        <div class="header-icon">
            🎟️👻
        </div>
        
        <!-- Título -->
        <h2 class="text-center mb-2" style="color: #f1c40f; letter-spacing: 2px;">
            PASSAGEM PARA O ALÉM
        </h2>
        <p class="text-center text-white-50 mb-4">
            Preencha seus dados e garanta sua jornada
        </p>
        
        <!-- Mensagem de feedback -->
        <?php if($mensagem): ?>
            <div class="alert alert-<?php echo $tipo_mensagem == 'success' ? 'success' : 'error'; ?> mb-4" role="alert">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>
        
        <!-- Tipo do ingresso -->
        <div class="tipo-ingresso">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span style="font-size: 24px;"><?php echo $icone; ?></span>
                    <span class="badge-espiritual ms-2">INGRESSO ESPIRITUAL</span>
                </div>
                <span class="preco">R$ 50,00</span>
            </div>
            <h4 class="mt-2" style="color: #fff;"><?php echo htmlspecialchars($tipo); ?></h4>
            <p class="text-white-50 small mb-0"><?php echo $descricao; ?></p>
            <div class="mt-2">
                <span class="estoque-badge">📦 Estoque: <?php echo $estoque_atual; ?> unidades</span>
            </div>
        </div>
        
        <!-- Formulário de compra -->
        <form method="POST" action="" id="formCompra">
            <input type="hidden" name="tipo_ingresso" value="<?php echo htmlspecialchars($tipo); ?>">
            
            <!-- Dados do usuário (auto-preenchidos) -->
            <div class="mb-3">
                <label class="form-label">👤 NOME COMPLETO</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($usuario->nome ?? ''); ?>" readonly>
            </div>
            
            <div class="mb-3">
                <label class="form-label">📧 E-MAIL</label>
                <input type="email" class="form-control" value="<?php echo htmlspecialchars($usuario->email ?? ''); ?>" readonly>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">🎂 DATA DE NASCIMENTO</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($usuario->data_nascimento ?? ''); ?>" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">🆔 CPF</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($usuario->cpf ?? ''); ?>" readonly>
                </div>
            </div>
            
            <!-- Quantidade de ingressos -->
            <div class="mb-3">
                <label class="form-label">🎫 QUANTIDADE DE INGRESSOS</label>
                <input type="number" class="form-control" name="quantidade" id="quantidade" 
                       min="1" max="<?php echo $estoque_atual; ?>" value="1" required 
                       onchange="atualizarTotal()">
                <small class="text-white-50">Máximo disponível: <?php echo $estoque_atual; ?> ingressos</small>
            </div>
            
            <!-- Forma de pagamento -->
            <div class="mb-4">
                <label class="form-label">💳 FORMA DE PAGAMENTO</label>
                <select class="form-select" name="pagamento" required>
                    <option value="">Selecione...</option>
                    <option value="Cartão de Crédito">Cartão de Crédito</option>
                    <option value="PIX">PIX (Pagamento Instantâneo)</option>
                    <option value="Boleto Bancário">Boleto Bancário</option>
                    <option value="Dinheiro Físico">Dinheiro Físico (Só aceitamos notas não rasgadas)</option>
                    <option value="Alma">Alma (Apenas para desencarnados)</option>
                </select>
            </div>
            
            <hr>
            
            <!-- Total -->
            <div class="total">
                <div class="d-flex justify-content-between align-items-center">
                    <span style="color: #fff;">Total</span>
                    <span class="preco" id="totalValor" style="font-size: 28px;">R$ 50,00</span>
                </div>
                <p class="text-white-50 small mt-2 mb-0">
                    ⚡ Sua alma está a salvo conosco. Garantia de satisfação ou o dobro da sua tristeza de volta.
                </p>
            </div>
            
            <div class="d-flex gap-3 mt-4">
                <a href="javascript:history.back()" class="btn-voltar" style="flex: 0.5;">
                    ← Voltar
                </a>
                <button type="submit" name="comprar" class="btn-comprar" style="flex: 1.5;" 
                        <?php echo $estoque_atual == 0 ? 'disabled' : ''; ?>>
                    💀 COMPRAR INGRESSO 💀
                </button>
            </div>
        </form>
        
        <!-- Aviso místico -->
        <div class="text-center mt-4">
            <small class="text-white-50">
                🕯️ Ao comprar este ingresso, você aceita os termos e condições do além. <br>
                Não nos responsabilizamos por possessões, arrependimentos ou encontros inesperados com parentes falecidos.
            </small>
        </div>
        
    </div>
</div>

<script>
    function atualizarTotal() {
        var quantidade = document.getElementById('quantidade').value;
        var total = quantidade * 50;
        document.getElementById('totalValor').innerHTML = 'R$ ' + total.toFixed(2).replace('.', ',');
    }
    
    // Atualizar total quando a página carregar
    document.addEventListener('DOMContentLoaded', function() {
        atualizarTotal();
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>