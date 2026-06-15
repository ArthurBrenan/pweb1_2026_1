<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contato - Post Mortem Festival</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        body {
            background: linear-gradient(135deg, #1a1a1a 0%, #0d0d0d 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .hero-banner {
            position: relative;
            background: linear-gradient(145deg, #f1c40f, #d4a00a);
            border-radius: 30px;
            overflow: hidden;
            margin: 30px auto;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4), 0 0 30px rgba(241,196,15,0.2);
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
        
        /* Accordion*/
        .accordion {
            --bs-accordion-bg:  #e0e0d1;
            --bs-accordion-border-color: #d4c5a0;
            --bs-accordion-btn-bg:  #e0e0d1;
            --bs-accordion-btn-color: #000000;
            --bs-accordion-active-bg:  #e0e0d1;
            --bs-accordion-active-color: #000000;
        }
        
        .accordion-item {
            border: 1px solid #d4c5a0;
            margin-bottom: 20px;
            border-radius: 15px !important;
            overflow: hidden;
            background-color:  #e0e0d1;
        }
        
        .accordion-button {
            font-weight: bold;
            font-size: 1.3rem;
            padding: 20px;
            background-color: #e0e0d1;
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
            background-color:  #e0e0d1;
            color: #000000 !important;
            border-bottom: 1px solid #f1c40f;
            box-shadow: none !important;
        }
        
        .accordion-button::after {
            filter: brightness(0);
        }
        
        .accordion-body {
            background-color: #0d0d0d;
            color:  #e0e0d1;
            padding: 25px;
            line-height: 1.6;
        }
        
        /* Card do mapa */
        .map-card {
            background: linear-gradient(145deg, #1a1a1a, #0d0d0d);
            border-radius: 20px;
            border: 1px solid #2c2c2c;
            overflow: hidden;
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .map-card:hover {
            border-color: #f1c40f;
            box-shadow: 0 10px 30px rgba(241,196,15,0.1);
        }
        
        .map-header {
            background: linear-gradient(145deg, #252525, #1a1a1a);
            padding: 15px 20px;
            border-bottom: 1px solid #f1c40f;
        }
        
        .map-header h3 {
            color: #f1c40f;
            margin: 0;
            font-size: 1.5rem;
            letter-spacing: 1px;
        }
        
        .map-container {
            padding: 20px;
        }
        
        iframe {
            width: 100%;
            height: 450px;
            border-radius: 15px;
            border: 1px solid #2c2c2c;
        }
        
        .link-mapa {
            color: #f1c40f;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .link-mapa:hover {
            color: #ffd700;
            text-decoration: underline;
        }
        
        .warning-text {
            background: rgba(241,196,15,0.08);
            border-left: 3px solid #f1c40f;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
            color: #b0b0b0;
        }
        
        .subtitle {
            color: #f1c40f;
            margin-top: 20px;
            margin-bottom: 10px;
            font-size: 1.1rem;
            font-weight: bold;
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
                font-size: 1rem;
                padding: 15px;
            }
            
            iframe {
                height: 300px;
            }
        }
        
        @media (max-width: 992px) {
            .row-cols-md-2 > .col {
                margin-bottom: 20px;
            }
        }
    </style>
</head>

<body>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/pweb1_2026_1/avaliacao_dois/site/admin/header.php'; ?>



<main class="container my-5">
    <div class="row row-cols-1 row-cols-lg-2 g-4">
        
        <!-- Coluna da Esquerda-->
        <div class="col">
            <div class="map-card">
                <div class="map-header">
                    <h3>COMO CHEGAR?</h3>
                </div>
                <div class="p-4">
                    <!-- Accordion  -->
                    <div class="accordion" id="accordionComoChegar">
                        
                        <!-- Accordion Vivos -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#collapseVivos" aria-expanded="true" 
                                        aria-controls="collapseVivos">
                                    SE VOCÊ É VIVO
                                </button>
                            </h2>
                            <div id="collapseVivos" class="accordion-collapse collapse show" 
                                 data-bs-parent="#accordionComoChegar">
                                <div class="accordion-body">
                                    <p>Se você é vivo e vai ao nosso festival em Copacabana, siga esses passos para chegar no Post Mortem:</p>
                                    
                                    <div class="subtitle">Para chegar de Ônibus:</div>
                                    <p>
                                        Diversas linhas cruzam a cidade em direção ao "além-túmulo" de Copacabana.
                                        Procure por letreiros que indiquem "Via Copacabana" ou "Castelo".
                                    </p>
                                    <p>
                                        Se você vem da Zona Norte ou do Centro, as linhas que passam pela Av.
                                        Nossa Senhora de Copacabana são as ideais; desça o mais próximo possível da estátua de
                                        Dorival Caymmi (um mestre que já confirmou presença no palco).
                                    </p>
                                    
                                    <div class="subtitle">Para chegar de metrô:</div>
                                    <p>
                                        Esta é a forma mais rápida de viajar entre as dimensões urbanas.
                                        Utilize a Linha 1 (Laranja) ou a Linha 4 (Amarela) e desembarque nas
                                        estações Cardeal Arcoverde, Siqueira Campos ou Cantagalo. Ao sair dos túneis profundos
                                        do metrô, siga o fluxo da corrente de ar frio e o som dos instrumentos.
                                        Mantenha seu bilhete em mãos; ele é o seu salvo-conduto para retornar seguro
                                        ao mundo dos vivos.
                                    </p>
                                    
                                    <div class="warning-text">
                                        <small>Fique atento: se o motorista for um esqueleto e não cobrar a passagem, 
                                        você provavelmente pegou a linha errada.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Accordion Mortos -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#collapseMortos" aria-expanded="false" 
                                        aria-controls="collapseMortos">
                                    SE VOCÊ É MORTO
                                </button>
                            </h2>
                            <div id="collapseMortos" class="accordion-collapse collapse" 
                                 data-bs-parent="#accordionComoChegar">
                                <div class="accordion-body">
                                    <p>
                                        Se você já cruzou o véu e deseja se juntar à nossa celebração em Copacabana,
                                        esqueça as leis da física e o trânsito caótico dos vivos, pois o seu trajeto
                                        ignora completamente o asfalto saturado.
                                    </p>
                                    <p>
                                        Para chegar ao palco principal do Post Mortem, você deve se sintonizar com as correntes 
                                        de convecção etérea que sopram do Morro do Leme, deixando que sua densidade espiritual 
                                        flutue livremente nas baixas frequências que estão na orla.
                                    </p>
                                    <p>
                                        Além disso, não tente utilizar os veículos convencionais; em vez disso, passe
                                        por dentro das rochas e utilize os túneis do metrô como aceleradores de partículas
                                        para o seu ectoplasma, desmaterializando-se na altura da estação Siqueira Campos e 
                                        subindo verticalmente até emergir com força no palco principal.
                                    </p>
                                    <p>
                                        O seu ponto de ancoragem definitivo é sinalizado por um feixe de luz azul que corta 
                                        as ondas, servindo como um farol interdimensional para todos os desencarnados
                                        que buscam o festival. Ao sentir a vibração do baixo de Tim Maia ressoando diretamente 
                                        no seu não-corpo, você saberá que atravessou a fronteira com sucesso e estará pronto 
                                        para o show.
                                    </p>
                                    
                                    <div class="warning-text">
                                        <small>Pedimos apenas a gentileza de utilizar as vias de acesso elevadas, mantendo-se 
                                        a pelo menos três metros acima do solo, para evitar atravessar os corpos dos seguranças 
                                        vivos, o que costuma causar calafrios e soluços persistentes na equipe de apoio terrestre.</small>
                                    </div>
                                    
                                    <div class="warning-text mt-3">
                                        <small>Lembre-se, na praia podem haver espíritos piratas prontos para saquear sua energia.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Coluna da Direita -->
        <div class="col">
            <div class="map-card">
                <div class="map-header">
                    <h3>MAPA DO LOCAL</h3>
                </div>
                <div class="map-container">
                    <iframe 
                        src="https://www.openstreetmap.org/export/embed.html?bbox=-43.21742534637451%2C-22.990199914929256%2C-43.16077709197999%2C-22.955034420717798&amp;layer=mapnik&amp;marker=-22.972618311479575%2C-43.189101219177246" 
                        style="border: 1px solid #2c2c2c;"
                        allowfullscreen>
                    </iframe>
                    <div class="text-center mt-3">
                        <a class="link-mapa" href="https://www.openstreetmap.org/?mlat=-22.97262&amp;mlon=-43.18910#map=15/-22.97262/-43.18910" target="_blank">
                            Ver mapa ampliado
                        </a>
                    </div>
                    
                    <!-- Infos -->
                    <div class="warning-text mt-4">
                        <strong>Horário de funcionamento:</strong><br>
                        Portões abrem às 16h · Último show às 04h<br>
                        <small class="text-muted">Espíritos têm entrada liberada a partir do pôr do sol</small>
                    </div>
                    
                    <div class="warning-text mt-3">
                        <strong>Pontos de venda:</strong><br>
                        Bilheteria física: Rua das Almas, 666 - Copacabana<br>
                        <small class="text-muted">Aceitamos dinheiro, cartão e almas penadas</small>
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