<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sobre - Post Mortem Festival</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        body {
            background: linear-gradient(135deg, #1a1a1a 0%, #0d0d0d 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        /* Banner de introdução */
        .hero-banner {
            position: relative;
            background: linear-gradient(145deg, #f1c40f, #d4a00a);
            border-radius: 30px;
            overflow: hidden;
            margin: 30px auto;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4), 0 0 30px rgba(241,196,15,0.2);
        }
        
        .hero-banner::before {
            content: "♪";
            position: absolute;
            font-size: 300px;
            opacity: 0.1;
            bottom: -80px;
            right: -50px;
            font-family: monospace;
            color: #000;
        }
        
        .hero-banner::after {
            content: "♫";
            position: absolute;
            font-size: 200px;
            opacity: 0.08;
            top: -50px;
            left: -30px;
            font-family: monospace;
            color: #000;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
            padding: 40px;
        }
        
        .hero-title {
            font-size: 3rem;
            font-weight: 900;
            color: #1a1a1a;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
            letter-spacing: 2px;
        }
        
        .hero-subtitle {
            font-size: 1.2rem;
            color: #2c2c2c;
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.6;
        }
        
        /* Cards */
        .info-card {
            background: linear-gradient(145deg, #1e1e1e, #161616);
            border-radius: 20px;
            border: 1px solid #2c2c2c;
            overflow: hidden;
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .info-card:hover {
            border-color: #f1c40f;
            box-shadow: 0 10px 30px rgba(241,196,15,0.1);
        }
        
        .card-header-custom {
            background: linear-gradient(145deg, #252525, #1e1e1e);
            padding: 15px 20px;
            border-bottom: 1px solid #f1c40f;
        }
        
        .card-header-custom h3 {
            color: #f1c40f;
            margin: 0;
            font-size: 1.5rem;
            letter-spacing: 1px;
        }
        
        .card-body-custom {
            padding: 25px;
            color: #c0c0c0;
            line-height: 1.6;
            text-align: justify;
        }
        
        /* Accordion estilizado */
        .accordion {
            --bs-accordion-bg: #f5f0e8;
            --bs-accordion-border-color: #d4c5a0;
            --bs-accordion-btn-bg: #f5f0e8;
            --bs-accordion-btn-color: #000000;
            --bs-accordion-active-bg: #f5f0e8;
            --bs-accordion-active-color: #000000;
        }
        
        .accordion-item {
            border: 1px solid #d4c5a0;
            margin-bottom: 20px;
            border-radius: 15px !important;
            overflow: hidden;
            background-color: #f5f0e8;
        }
        
        .accordion-button {
            font-weight: bold;
            font-size: 1.1rem;
            padding: 20px;
            background-color: #f5f0e8;
            color: #000000 !important;
            box-shadow: none !important;
            outline: none !important;
        }
        
        .accordion-button:focus {
            box-shadow: none !important;
            border-color: #d4c5a0 !important;
            outline: none !important;
        }
        
        .accordion-button:not(.collapsed) {
            background-color: #f5f0e8;
            color: #000000 !important;
            border-bottom: 1px solid #f1c40f;
            box-shadow: none !important;
        }
        
        .accordion-button::after {
            filter: brightness(0);
        }
        
        .accordion-body {
            background-color: #0d0d0d;
            color: #c0c0c0;
            padding: 25px;
            line-height: 1.6;
        }
        
        .section-title {
            color: #f1c40f;
            margin-bottom: 30px;
            font-size: 1.8rem;
            letter-spacing: 2px;
            border-left: 4px solid #f1c40f;
            padding-left: 20px;
        }
        
        hr {
            border-color: #2c2c2c;
        }
        
        /* Responsividade */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }
            
            .hero-subtitle {
                font-size: 1rem;
            }
            
            .hero-content {
                padding: 30px 20px;
            }
            
            .accordion-button {
                font-size: 0.9rem;
                padding: 15px;
            }
            
            .section-title {
                font-size: 1.4rem;
            }
        }
    </style>
</head>

<body>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/pweb1_2026_1/avaliacao_dois/site/admin/header.php'; ?>


<main class="container my-5">
    <!-- História e Comitê -->
    <div class="row g-4 mb-5">
        <div class="col-md-8">
            <div class="info-card">
                <div class="card-header-custom">
                    <h3>NOSSA HISTÓRIA</h3>
                </div>
                <div class="card-body-custom">
                    <p>
                        A fundação do Post Mortem não nasceu em um escritório, mas em um silêncio absoluto durante o 
                        eclipse solar total de 2018. O Dr. Jatamor Tinho, um engenheiro acústico obcecado por 
                        frequências residuais, descobriu que o som não morre; ele apenas "muda de estado". 
                        Ao sintonizar um rádio antigo pertencente a um médium já morto, Jatamor Tinho captou 
                        um ensaio inédito de Freddie Mercury que parecia acontecer naquele exato segundo, 
                        em uma dobra da realidade.
                    </p>
                    <p>
                        A partir disso o festival deixou de ser um experimento científico para se tornar um portal. 
                        Somado aos experimentos visuais e de alteração da matéria da Dra. Ane Urisma, realizou-se um 
                        ensaio onde os fantasmas dos músicos já mortos puderam interagir com o mundo dos vivos e 
                        realizar uma apresentação. Hoje, o Post Mortem utiliza a tecnologia ÉFAN-Tasma, que 
                        estabiliza o ectoplasma dos artistas através de campos eletromagnéticos, permitindo que 
                        eles se materializem com a mesma potência vocal de seus auges biológicos.
                    </p>
                    <p>
                        Não estamos apenas ouvindo o passado, estamos trazendo ele para o presente. Estamos 
                        oferecendo aos grandes mestres a chance de tocar o que compuseram após deixarem a Terra. 
                        É o primeiro festival interdimensional da história, onde o ingresso é um pacto de 
                        admiração eterna.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="info-card">
                <div class="card-header-custom">
                    <h3>COMITÊ ORGANIZADOR</h3>
                </div>
                <div class="card-body-custom">
                    <p>
                        O comitê é liderado pela Sociedade dos Mortos e Vivos, um grupo eclético composto por 
                        musicólogos forenses, engenheiros de som e médiuns de alta fidelidade.
                    </p>
                    <p>
                        A curadoria é rigorosa: para um artista se apresentar, ele precisa ter deixado a vida 
                        como conhecemos e estar no chamado pós-morte. A logística desafia as leis da física 
                        clássica, coordenando desde a temperatura do palco (que tende a cair drasticamente 
                        durante os solos) até a segurança contra "vazamentos ectoplásmicos" na área VIP.
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Perguntas Frequentes -->
    <div class="info-card mb-5">
        <div class="card-header-custom">
            <h3>PERGUNTAS FREQUENTES</h3>
        </div>
        <div class="card-body-custom">
            <div class="accordion" id="accordionExample">
                
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            É possível cancelar a compra de ingressos?
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <strong>Sim,</strong> mas apenas enquanto você estiver no plano terreno. Uma vez que a 
                            transição para o "pós-morte" ocorre, o ingresso é vinculado à sua assinatura de alma. 
                            Aceitamos cancelamentos até 7 dias após a compra, desde que você não tenha atravessado 
                            o portal precocemente. Lembre-se: no além, não existe Procon, apenas o julgamento eterno 
                            (e não aceitamos reclamações por lá).
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Tenho que estar morto para assistir?
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <strong>Não!</strong> De forma alguma! O festival é um evento interdimensional. Criamos
                            zonas de contenção eletromagnética para que os vivos possam transitar em
                            segurança sem serem "puxados" pelas frequências dos artistas. No entanto, recomendamos
                            o uso de protetores auriculares de quartzo, pois o som do baixo do Tim Maia em estado
                            espectral pode fazer seu coração físico vibrar fora do ritmo.
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Alma penada paga meia-entrada?
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <strong>Sim!</strong> Como almas penadas ainda possuem pendências, o comitê considera 
                            que as almas estão pela metade, sendo ofertado o recurso de meia entrada. Se sua metade 
                            presente no evento for "al" você possui 40% de desconto, já se for "ma", será cobrado 
                            apenas 30% do valor do ingresso. O benefício também se estende a estudantes de necromancia 
                            e médiuns com CRM (Conselho Regional de Mediunidade) ativo.
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            Se eu matar um artista bom ele pode participar do festival?
                        </button>
                    </h2>
                    <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <strong>Não em tempo de assistir.</strong> O Comitê Organizador possui um código de ética 
                            rigoroso: o festival celebra o legado, não acelera o destino. Artistas vítimas de 
                            "interferência humana intencional" entram em um período de quarentena espiritual de 50 
                            ou mais anos antes de poderem subir ao palco do Post Mortem. Caso após 50 anos seu 
                            assassino esteja vivo, o tempo de quarentena aumenta. Aprecie os vivos enquanto eles 
                            respiram!
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    
   
</main>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/pweb1_2026_1/avaliacao_dois/site/admin/footer.php';
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"></script>
</body>

</html>