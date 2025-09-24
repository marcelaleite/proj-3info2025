
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Árvore Genealógica Personalizável</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
        .node {
            position: relative;
            display: inline-block;
            margin: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 10;
        }
        
        .node:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .male {
            width: 40px;
            height: 40px;
            background-color: white;
            border: 2px solid #374151;
            border-radius: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: #374151;
        }
        
        .female {
            width: 40px;
            height: 40px;
            background-color: white;
            border: 2px solid #374151;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: #374151;
        }
        
        .presence {
            background-color: #10b981;
            border: 2px solid #374151;
            border-radius: 50%;
        }
        
        .absence {
            background-color: #1f2937;
            border: 2px solid #374151;
            border-radius: 50%;
        }
        
        .connection-line {
            position: absolute;
            background-color: #374151;
            z-index: 1;
        }
        
        .vertical-line {
            width: 2px;
            background-color: #374151;
        }
        
        .horizontal-line {
            height: 2px;
            background-color: #374151;
        }
        
        .marriage-line {
            width: 20px;
            height: 2px;
            background-color: #374151;
            position: absolute;
            z-index: 2;
        }
        
        .sibling-line {
            height: 2px;
            background-color: #6366f1;
            position: absolute;
            z-index: 2;
        }
        
        .label {
            text-align: center;
            font-size: 12px;
            margin-top: 5px;
            color: #374151;
            max-width: 80px;
            word-wrap: break-word;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        
        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 20px;
            border-radius: 8px;
            width: 600px;
            max-width: 90%;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .close:hover {
            color: black;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #374151;
        }
        
        .row {
            margin-bottom: 15px;
        }
        
        .two-cols {
            display: flex;
            gap: 15px;
        }
        
        .two-cols > div {
            flex: 1;
        }
        
        input[type="text"], select, textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #d1d5da;
            border-radius: 4px;
            box-sizing: border-box;
        }
        
        .select {
            color: white;
            background-color: #222;
            padding: 8px;
            border: 1px solid #d1d5da;
            border-radius: 4px;
            width: 100%;
            box-sizing: border-box;
        }
        
        .checkbox-group, .radio-group {
            display: flex;
            gap: 15px;
            margin-top: 5px;
        }
        
        .checkbox-label, .radio-label {
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
        }
        
        .checkbox-label input, .radio-label input {
            margin: 0;
        }
        
        textarea {
            resize: vertical;
        }
        
        .form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        
        .btn {
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn.primary {
            background-color: #10b981;
            color: white;
            border: none;
        }
        
        .btn.ghost {
            background-color: transparent;
            color: #374151;
            border: 1px solid #d1d5da;
        }
        
        .btn.primary:hover {
            background-color: #059669;
        }
        
        .btn.ghost:hover {
            background-color: #f3f4f6;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .legend-icon {
            width: 20px;
            height: 20px;
            margin-right: 8px;
            border: 2px solid #374151;
        }
        
        .tool-panel {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 100;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 10px;
            display: flex;
            flex-direction: column;
        }
        
        .tool-btn {
            width: 40px;
            height: 40px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
            background-color: #f3f4f6;
            border: 1px solid #d1d5da;
        }
        
        .tool-btn:hover {
            background-color: #e5e7eb;
        }
        
        .active {
            background-color: #10b981;
            color: white;
        }
        
        .tree-container {
            position: relative;
            width: 100%;
            height: 80vh;
            overflow-x: auto;
            overflow-y: auto;
            padding: 20px;
            background-color: #f9fafb;
        }
        
        .drag-handle {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            cursor: grab;
            z-index: 0;
        }
        
        .zoom-controls {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 100;
        }
        
        .zoom-btn {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: white;
            border: 1px solid #d1d5da;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .zoom-btn:hover {
            background-color: #f3f4f6;
        }
        
        .zoom-level {
            width: 60px;
            text-align: center;
            padding: 10px;
            background-color: white;
            border: 1px solid #d1d5da;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .connections-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 5;
        }
        
        .sibling-group-line {
            height: 2px;
            background-color: #6366f1;
            position: absolute;
            z-index: 2;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-center mb-6 text-gray-800">Árvore Genealógica Personalizável</h1>
        
        <!-- Tool Panel -->
        <div class="tool-panel">
            <div class="tool-btn active" id="select-tool" title="Selecionar">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7S3.732 16.057 2.458 12z" />
                </svg>
            </div>
            <div class="tool-btn" id="add-person-tool" title="Adicionar Pessoa">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
            </div>
            <div class="tool-btn" id="edit-tool" title="Editar">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
            <div class="tool-btn" id="delete-tool" title="Excluir">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <div class="tool-btn" id="relationship-tool" title="Adicionar Relação">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div class="tool-btn" id="new-tree-tool" title="Nova Árvore">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.356-2m15.356 2H15" />
                </svg>
            </div>
            <div class="tool-btn" id="save-tool" title="Salvar">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                </svg>
            </div>
        </div>

        <!-- Legend -->
        <div class="absolute top-20 left-20 bg-white p-4 rounded-lg shadow-md z-10">
            <h3 class="font-bold text-lg mb-2">Legenda</h3>
            <div class="space-y-2">
                <div class="legend-item">
                    <div class="legend-icon male"></div>
                    <span>Homem</span>
                </div>
                <div class="legend-item">
                    <div class="legend-icon female"></div>
                    <span>Mulher</span>
                </div>
                <div class="legend-item">
                    <div class="legend-icon presence"></div>
                    <span>Porta o traço</span>
                </div>
                <div class="legend-item">
                    <div class="legend-icon absence"></div>
                    <span>Não porta o traço</span>
                </div>
                <div class="legend-item">
                    <div style="width: 20px; height: 2px; background-color: #374151; margin-right: 8px;"></div>
                    <span>Linha de casamento</span>
                </div>
                <div class="legend-item">
                    <div style="width: 2px; height: 20px; background-color: #374151; margin-right: 8px;"></div>
                    <span>Linha de descendência</span>
                </div>
                <div class="legend-item">
                    <div style="width: 20px; height: 2px; background-color: #6366f1; margin-right: 8px;"></div>
                    <span>Linha de irmãos</span>
                </div>
            </div>
        </div>

        <!-- Tree Container -->
        <div class="tree-container" id="tree-container">
            <div class="drag-handle" id="drag-handle"></div>
            <div id="connections-container" class="connections-container"></div>
            <div id="family-tree"></div>
        </div>

        <!-- Zoom Controls -->
        <div class="zoom-controls">
            <div class="zoom-btn" id="zoom-out">-</div>
            <div class="zoom-level" id="zoom-level">100%</div>
            <div class="zoom-btn" id="zoom-in">+</div>
        </div>

        <!-- Add/Edit Person Modal -->
        <div id="person-modal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2 class="text-xl font-bold mb-4">Informações Genealógicas</h2>
                <form id="genealogicForm">
                    <div class="row">
                        <label for="nomeCompleto">Nome completo</label>
                        <input type="text" id="nomeCompleto" name="nomeCompleto" placeholder="Seu nome completo" required />
                    </div>

                    <div class="row two-cols">
                        <div>
                            <label for="corOlho">Cor do olho</label>
                            <input type="text" id="corOlho" name="corOlho" placeholder="verde" required />
                        </div>
                        <div>
                            <label for="corCabelo">Cor do cabelo</label>
                            <input type="text" id="corCabelo" name="corCabelo" placeholder="loiro" required />
                        </div>
                    </div>

                    <div class="row two-cols">
                        <div>
                            <label for="tipoSanguineo">Tipo sanguíneo</label>
                            <select id="tipoSanguineo" name="tipoSanguineo" placeholder="Selecione" required class="select" style="color: white; background-color: #222;">
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="nacionalidade">País de Origem/Nacionalidade</label>
                            <select id="nacionalidade" name="nacionalidade" required class="select" style="color: white; background-color: #222;">
                                <option value="Afeganistão">Afeganistão</option>
                                <option value="África do Sul">África do Sul</option>
                                <option value="Albânia">Albânia</option>
                                <option value="Alemanha">Alemanha</option>
                                <option value="Andorra">Andorra</option>
                                <option value="Angola">Angola</option>
                                <option value="Arábia Saudita">Arábia Saudita</option>
                                <option value="Argentina">Argentina</option>
                                <option value="Armênia">Armênia</option>
                                <option value="Austrália">Austrália</option>
                                <option value="Áustria">Áustria</option>
                                <option value="Bahamas">Bahamas</option>
                                <option value="Bangladesh">Bangladesh</option>
                                <option value="Barbados">Barbados</option>
                                <option value="Bélgica">Bélgica</option>
                                <option value="Bolívia">Bolívia</option>
                                <option value="Brasil">Brasil</option>
                                <option value="Bulgária">Bulgária</option>
                                <option value="Cabo Verde">Cabo Verde</option>
                                <option value="Camarões">Camarões</option>
                                <option value="Canadá">Canadá</option>
                                <option value="Chile">Chile</option>
                                <option value="China">China</option>
                                <option value="Colômbia">Colômbia</option>
                                <option value="Coreia do Sul">Coreia do Sul</option>
                                <option value="Costa Rica">Costa Rica</option>
                                <option value="Croácia">Croácia</option>
                                <option value="Cuba">Cuba</option>
                                <option value="Dinamarca">Dinamarca</option>
                                <option value="Egito">Egito</option>
                                <option value="El Salvador">El Salvador</option>
                                <option value="Equador">Equador</option>
                                <option value="Espanha">Espanha</option>
                                <option value="Estados Unidos">Estados Unidos</option>
                                <option value="Estônia">Estônia</option>
                                <option value="Etiópia">Etiópia</option>
                                <option value="Filipinas">Filipinas</option>
                                <option value="Finlândia">Finlândia</option>
                                <option value="França">França</option>
                                <option value="Grécia">Grécia</option>
                                <option value="Guatemala">Guatemala</option>
                                <option value="Haiti">Haiti</option>
                                <option value="Holanda">Holanda</option>
                                <option value="Honduras">Honduras</option>
                                <option value="Hungria">Hungria</option>
                                <option value="Índia">Índia</option>
                                <option value="Indonésia">Indonésia</option>
                                <option value="Inglaterra">Inglaterra</option>
                                <option value="Irlanda">Irlanda</option>
                                <option value="Islândia">Islândia</option>
                                <option value="Itália">Itália</option>
                                <option value="Jamaica">Jamaica</option>
                                <option value="Japão">Japão</option>
                                <option value="Jordânia">Jordânia</option>
                                <option value="Letônia">Letônia</option>
                                <option value="Líbano">Líbano</option>
                                <option value="Lituânia">Lituânia</option>
                                <option value="Luxemburgo">Luxemburgo</option>
                                <option value="Malásia">Malásia</option>
                                <option value="Marrocos">Marrocos</option>
                                <option value="México">México</option>
                                <option value="Moçambique">Moçambique</option>
                                <option value="Noruega">Noruega</option>
                                <option value="Nova Zelândia">Nova Zelândia</option>
                                <option value="Palestina">Palestina</option>
                                <option value="Panamá">Panamá</option>
                                <option value="Paraguai">Paraguai</option>
                                <option value="Peru">Peru</option>
                                <option value="Polônia">Polônia</option>
                                <option value="Portugal">Portugal</option>
                                <option value="Quênia">Quênia</option>
                                <option value="Reino Unido">Reino Unido</option>
                                <option value="República Dominicana">República Dominicana</option>
                                <option value="Rojava">Rojava</option>
                                <option value="Romênia">Romênia</option>
                                <option value="Rússia">Rússia</option>
                                <option value="Senegal">Senegal</option>
                                <option value="Singapura">Singapura</option>
                                <option value="Suécia">Suécia</option>
                                <option value="Suíça">Suíça</option>
                                <option value="Tailândia">Tailândia</option>
                                <option value="Tanzânia">Tanzânia</option>
                                <option value="Turquia">Turquia</option>
                                <option value="Ucrânia">Ucrânia</option>
                                <option value="Uganda">Uganda</option>
                                <option value="Uruguai">Uruguai</option>
                                <option value="Venezuela">Venezuela</option>
                                <option value="Vietnã">Vietnã</option>
                                <option value="Zapatistas">Zapatistas</option>
                            </select>
                        </div>
                    </div>

                    <div class="row two-cols">
                        <div>
                            <label for="covinhas">Covinhas</label>
                            <div class="checkbox-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="covinhas" value="buchechar" />
                                    <span>Bucheca</span>
                                </label>
                                <label class="checkbox-label">
                                    <input type="checkbox" name="covinhas" value="queixo" />
                                    <span>Queixo</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label for="tipoOrelha">Tipo de orelha</label>
                            <div class="radio-group">
                                <label class="radio-label">
                                    <input type="radio" name="tipoOrelha" value="solta" required />
                                    <span>Solta</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="tipoOrelha" value="presa" />
                                    <span>Presa</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <label for="doencaGenealogica">Doença genealógica (opcional)</label>
                        <textarea id="doencaGenealogica" name="doencaGenealogica" rows="3" 
                                  placeholder="Ex.: Alzheimer, Hemofilia, Daltonismo..."></textarea>
                    </div>

                    <div class="form-actions">
                        <a class="btn ghost" href="#" id="back-btn">Voltar</a>
                        <button type="submit" class="btn primary">Cadastrar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Add Relationship Modal -->
        <div id="relationship-modal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2 class="text-xl font-bold mb-4">Adicionar Relação</h2>
                <div id="selected-person-display" class="selected-person"></div>
                <div class="relationship-form">
                    <div class="form-group">
                        <label for="relationship-type">Tipo de Relação:</label>
                        <select id="relationship-type" class="relationship-select">
                            <option value="spouse">Cônjuge</option>
                            <option value="child">Filho/Filha</option>
                            <option value="parent">Pai/Mãe</option>
                            <option value="sibling">Irmão/Irmã</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="related-person-name">Nome da Pessoa Relacionada:</label>
                        <input type="text" id="related-person-name" name="related-person-name" required>
                    </div>
                    <div class="form-group">
                        <label for="related-person-gender">Gênero:</label>
                        <select id="related-person-gender" class="relationship-select">
                            <option value="male">Masculino</option>
                            <option value="female">Feminino</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="related-person-trait">Porta o traço genético?</label>
                        <select id="related-person-trait" class="relationship-select">
                            <option value="presence">Sim</option>
                            <option value="absence">Não</option>
                            <option value="none">Nenhum</option>
                        </select>
                    </div>
                    <button id="add-relationship-btn" class="w-full bg-purple-500 hover:bg-purple-600 text-white py-2 px-4 rounded">Adicionar Relação</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize variables
        let selectedTool = 'select';
        let familyTree = [];
        let currentId = 1;
        let isDragging = false;
        let dragStartX = 0;
        let dragStartY = 0;
        let treeOffsetX = 0;
        let treeOffsetY = 0;
        let zoomLevel = 100;
        let selectedPersonId = null;
        let editingPersonId = null;

        // DOM Elements
        const treeContainer = document.getElementById('tree-container');
        const familyTreeElement = document.getElementById('family-tree');
        const connectionsContainer = document.getElementById('connections-container');
        const dragHandle = document.getElementById('drag-handle');
        const personModal = document.getElementById('person-modal');
        const relationshipModal = document.getElementById('relationship-modal');
        const genealogicForm = document.getElementById('genealogicForm');
        const closeModalBtns = document.querySelectorAll('.close');
        const zoomInBtn = document.getElementById('zoom-in');
        const zoomOutBtn = document.getElementById('zoom-out');
        const zoomLevelDisplay = document.getElementById('zoom-level');
        const addRelationshipBtn = document.getElementById('add-relationship-btn');
        const selectedPersonDisplay = document.getElementById('selected-person-display');
        const backBtn = document.getElementById('back-btn');

        // Tools
        document.getElementById('select-tool').addEventListener('click', () => {
            selectedTool = 'select';
            updateActiveTool();
        });

        document.getElementById('add-person-tool').addEventListener('click', () => {
            selectedTool = 'add-person';
            openPersonModal(null);
            updateActiveTool();
        });

        document.getElementById('edit-tool').addEventListener('click', () => {
            selectedTool = 'edit';
            updateActiveTool();
        });

        document.getElementById('delete-tool').addEventListener('click', () => {
            selectedTool = 'delete';
            updateActiveTool();
        });

        document.getElementById('relationship-tool').addEventListener('click', () => {
            selectedTool = 'relationship';
            updateActiveTool();
        });

        document.getElementById('new-tree-tool').addEventListener('click', () => {
            if (confirm('Deseja realmente começar uma nova árvore?')) {
                resetFamilyTree();
            }
        });

        document.getElementById('save-tool').addEventListener('click', () => {
            saveFamilyTree();
        });

        // Update active tool styling
        function updateActiveTool() {
            document.querySelectorAll('.tool-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            if (selectedTool) {
                document.getElementById(selectedTool + '-tool').classList.add('active');
            }
        }

        // Open modals
        function openPersonModal(person = null) {
            personModal.style.display = 'block';
            editingPersonId = person ? person.id : null;
            
            if (person) {
                // Fill form with existing data
                document.getElementById('nomeCompleto').value = person.name || '';
                document.getElementById('corOlho').value = person.eyeColor || '';
                document.getElementById('corCabelo').value = person.hairColor || '';
                document.getElementById('tipoSanguineo').value = person.bloodType || 'A+';
                document.getElementById('nacionalidade').value = person.nationality || 'Brasil';
                document.getElementById('doencaGenealogica').value = person.geneticDisease || '';
                
                // Handle checkboxes
                const covinhasCheckboxes = document.querySelectorAll('input[name="covinhas"]');
                covinhasCheckboxes.forEach(cb => cb.checked = false);
                if (person.dimples && Array.isArray(person.dimples)) {
                    person.dimples.forEach(dimple => {
                        const checkbox = document.querySelector(`input[name="covinhas"][value="${dimple}"]`);
                        if (checkbox) checkbox.checked = true;
                    });
                }
                
                // Handle radio buttons
                const orelhaRadios = document.querySelectorAll('input[name="tipoOrelha"]');
                orelhaRadios.forEach(radio => radio.checked = false);
                if (person.earType) {
                    const radio = document.querySelector(`input[name="tipoOrelha"][value="${person.earType}"]`);
                    if (radio) radio.checked = true;
                }
            } else {
                // Clear form for new person
                genealogicForm.reset();
                const orelhaRadios = document.querySelectorAll('input[name="tipoOrelha"]');
                orelhaRadios[0].checked = true; // Default to "solta"
            }
        }

        function openRelationshipModal(person) {
            relationshipModal.style.display = 'block';
            selectedPersonId = person.id;
            selectedPersonDisplay.textContent = `Adicionando relação para: ${person.name}`;
            document.getElementById('related-person-name').value = '';
            document.getElementById('related-person-gender').value = 'male';
            document.getElementById('related-person-trait').value = 'none';
        }

        // Close modals
        closeModalBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                personModal.style.display = 'none';
                relationshipModal.style.display = 'none';
            });
        });

        window.addEventListener('click', (event) => {
            if (event.target === personModal) {
                personModal.style.display = 'none';
            }
            if (event.target === relationshipModal) {
                relationshipModal.style.display = 'none';
            }
        });

        // Form handlers
        genealogicForm.addEventListener('submit', (e) => {
            e.preventDefault();
            savePersonData();
        });

        backBtn.addEventListener('click', (e) => {
            e.preventDefault();
            personModal.style.display = 'none';
        });

        addRelationshipBtn.addEventListener('click', () => {
            addRelationship();
        });

        // Drag and zoom functionality
        dragHandle.addEventListener('mousedown', (e) => {
            isDragging = true;
            dragStartX = e.clientX - treeOffsetX;
            dragStartY = e.clientY - treeOffsetY;
        });

        document.addEventListener('mousemove', (e) => {
            if (isDragging) {
                treeOffsetX = e.clientX - dragStartX;
                treeOffsetY = e.clientY - dragStartY;
                updateTreePosition();
            }
        });

        document.addEventListener('mouseup', () => {
            isDragging = false;
        });

        zoomInBtn.addEventListener('click', () => {
            zoomLevel += 10;
            if (zoomLevel > 200) zoomLevel = 200;
            updateZoom();
        });

        zoomOutBtn.addEventListener('click', () => {
            zoomLevel -= 10;
            if (zoomLevel < 25) zoomLevel = 25;
            updateZoom();
        });

        function updateZoom() {
            zoomLevelDisplay.textContent = `${zoomLevel}%`;
            treeContainer.style.transform = `translate(${treeOffsetX}px, ${treeOffsetY}px) scale(${zoomLevel / 100})`;
        }

        function updateTreePosition() {
            treeContainer.style.transform = `translate(${treeOffsetX}px, ${treeOffsetY}px) scale(${zoomLevel / 100})`;
        }

        // Initial setup
        function initializeFamilyTree() {
            // Create initial sample data with all new fields
            const initialData = [
                {
                    id: 1,
                    name: 'Frank Harris',
                    birthYear: 1908,
                    deathYear: 2001,
                    gender: 'male',
                    trait: 'presence',
                    x: 400,
                    y: 100,
                    children: [3],
                    spouse: 2,
                    eyeColor: 'azul',
                    hairColor: 'cinza',
                    bloodType: 'O+',
                    nationality: 'Estados Unidos',
                    dimples: ['buchechar'],
                    earType: 'solta',
                    geneticDisease: 'Alzheimer'
                },
                {
                    id: 2,
                    name: 'Shirley Johns',
                    birthYear: 1910,
                    deathYear: 2000,
                    gender: 'female',
                    trait: 'absence',
                    x: 600,
                    y: 100,
                    children: [3],
                    spouse: 1,
                    eyeColor: 'verde',
                    hairColor: 'loiro',
                    bloodType: 'A-',
                    nationality: 'Canadá',
                    dimples: ['queixo'],
                    earType: 'presa',
                    geneticDisease: ''
                },
                {
                    id: 3,
                    name: 'Edward Harris',
                    birthYear: 1935,
                    deathYear: 2015,
                    gender: 'male',
                    trait: 'presence',
                    x: 500,
                    y: 200,
                    children: [5],
                    spouse: 4,
                    eyeColor: 'castanho',
                    hairColor: 'preto',
                    bloodType: 'B+',
                    nationality: 'Estados Unidos',
                    dimples: ['buchechar', 'queixo'],
                    earType: 'solta',
                    geneticDisease: 'Hemofilia'
                },
                {
                    id: 4,
                    name: 'Patricia Anderson',
                    birthYear: 1935,
                    deathYear: 2010,
                    gender: 'female',
                    trait: 'absence',
                    x: 700,
                    y: 200,
                    children: [5],
                    spouse: 3,
                    eyeColor: 'azul',
                    hairColor: 'ruivo',
                    bloodType: 'AB-',
                    nationality: 'Inglaterra',
                    dimples: [],
                    earType: 'solta',
                    geneticDisease: ''
                },
                {
                    id: 5,
                    name: 'Tony Harris',
                    birthYear: 1940,
                    deathYear: 2020,
                    gender: 'male',
                    trait: 'absence',
                    x: 600,
                    y: 300,
                    children: [6, 7],
                    spouse: 6,
                    eyeColor: 'verde',
                    hairColor: 'loiro',
                    bloodType: 'O-',
                    nationality: 'Estados Unidos',
                    dimples: ['buchechar'],
                    earType: 'presa',
                    geneticDisease: 'Daltonismo'
                },
                {
                    id: 6,
                    name: 'Mary Harris',
                    birthYear: 1970,
                    gender: 'female',
                    trait: 'absence',
                    x: 700,
                    y: 300,
                    children: [5],
                    spouse: 5,
                    eyeColor: 'castanho',
                    hairColor: 'castanho',
                    bloodType: 'A+',
                    nationality: 'Estados Unidos',
                    dimples: [],
                    earType: 'solta',
                    geneticDisease: ''
                },
                {
                    id: 7,
                    name: 'John Harris',
                    birthYear: 1975,
                    gender: 'male',
                    trait: 'presence',
                    x: 800,
                    y: 300,
                    children: [],
                    spouse: null,
                    eyeColor: 'azul',
                    hairColor: 'preto',
                    bloodType: 'B-',
                    nationality: 'Estados Unidos',
                    dimples: ['queixo'],
                    earType: 'solta',
                    geneticDisease: ''
                }
            ];

            familyTree = initialData;
            renderFamilyTree();
            currentId = 8;
        }

        function resetFamilyTree() {
            familyTree = [];
            currentId = 1;
            selectedPersonId = null;
            editingPersonId = null;
            familyTreeElement.innerHTML = '';
            connectionsContainer.innerHTML = '';
            renderFamilyTree();
            Swal.fire({
                icon: 'success',
                title: 'Árvore reiniciada!',
                text: 'Você pode começar a construir sua nova árvore genealógica.'
            });
        }

        function saveFamilyTree() {
            Swal.fire({
                icon: 'success',
                title: 'Árvore salva!',
                text: 'Seu arquivo foi salvo com sucesso.',
                timer: 1500
            });
        }

        function savePersonData() {
            const formData = new FormData(genealogicForm);
            const dimples = [];
            document.querySelectorAll('input[name="covinhas"]:checked').forEach(cb => {
                dimples.push(cb.value);
            });

            const dimplesValue = dimples.length > 0 ? dimples : null;

            if (editingPersonId) {
                // Edit existing person
                const personIndex = familyTree.findIndex(p => p.id === editingPersonId);
                if (personIndex !== -1) {
                    familyTree[personIndex].name = formData.get('nomeCompleto');
                    familyTree[personIndex].eyeColor = formData.get('corOlho');
                    familyTree[personIndex].hairColor = formData.get('corCabelo');
                    familyTree[personIndex].bloodType = formData.get('tipoSanguineo');
                    familyTree[personIndex].nationality = formData.get('nacionalidade');
                    familyTree[personIndex].dimples = dimplesValue;
                    familyTree[personIndex].earType = formData.get('tipoOrelha');
                    familyTree[personIndex].geneticDisease = formData.get('doencaGenealogica') || '';
                    
                    renderFamilyTree();
                    personModal.style.display = 'none';
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Pessoa atualizada!',
                        text: 'As informações foram atualizadas com sucesso.'
                    });
                }
            } else {
                // Add new person
                const person = {
                    id: currentId++,
                    name: formData.get('nomeCompleto'),
                    birthYear: null,
                    deathYear: null,
                    gender: 'male', // Default to male, will be set properly in relationship
                    trait: 'none',
                    x: 200 + Math.random() * 600,
                    y: 200 + Math.random() * 400,
                    children: [],
                    spouse: null,
                    eyeColor: formData.get('corOlho'),
                    hairColor: formData.get('corCabelo'),
                    bloodType: formData.get('tipoSanguineo'),
                    nationality: formData.get('nacionalidade'),
                    dimples: dimplesValue,
                    earType: formData.get('tipoOrelha'),
                    geneticDisease: formData.get('doencaGenealogica') || ''
                };

                familyTree.push(person);
                renderFamilyTree();
                personModal.style.display = 'none';
                
                Swal.fire({
                    icon: 'success',
                    title: 'Pessoa adicionada!',
                    text: 'A pessoa foi adicionada à árvore genealógica.'
                });
            }
        }

        function addRelationship() {
            const relatedName = document.getElementById('related-person-name').value;
            const gender = document.getElementById('related-person-gender').value;
            const trait = document.getElementById('related-person-trait').value;
            const relationshipType = document.getElementById('relationship-type').value;

            if (!relatedName) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: 'Por favor, insira o nome da pessoa relacionada.'
                });
                return;
            }

            if (!selectedPersonId) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: 'Por favor, selecione uma pessoa primeiro.'
                });
                return;
            }

            const selectedPerson = familyTree.find(p => p.id === selectedPersonId);
            if (!selectedPerson) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: 'Pessoa selecionada não encontrada.'
                });
                return;
            }

            // Create new person
            const newPerson = {
                id: currentId++,
                name: relatedName,
                birthYear: null,
                deathYear: null,
                gender: gender,
                trait: trait,
                x: selectedPerson.x,
                y: selectedPerson.y,
                children: [],
                spouse: null,
                eyeColor: 'castanho',
                hairColor: 'preto',
                bloodType: 'A+',
                nationality: 'Brasil',
                dimples: null,
                earType: 'solta',
                geneticDisease: ''
            };

            // Adjust position based on relationship
            if (relationshipType === 'child') {
                newPerson.y = selectedPerson.y + 120;
                newPerson.x = selectedPerson.x + (selectedPerson.children.length * 100) - 50;
                selectedPerson.children.push(newPerson.id);
            } else if (relationshipType === 'parent') {
                newPerson.y = selectedPerson.y - 120;
                newPerson.x = selectedPerson.x + (Math.random() > 0.5 ? 100 : -100);
                newPerson.children.push(selectedPerson.id);
            } else if (relationshipType === 'sibling') {
                // Find parent to position sibling correctly
                const parent = familyTree.find(p => p.children && p.children.includes(selectedPersonId));
                if (parent) {
                    newPerson.y = selectedPerson.y;
                    newPerson.x = parent.x + (parent.children.length * 80) - 40;
                } else {
                    newPerson.y = selectedPerson.y;
                    newPerson.x = selectedPerson.x + 100;
                }
                // Add to same parent's children
                if (parent) {
                    parent.children.push(newPerson.id);
                }
            } else { // spouse
                newPerson.y = selectedPerson.y;
                newPerson.x = selectedPerson.x + (selectedPerson.gender === 'male' ? 150 : -150);
                newPerson.spouse = selectedPersonId;
                selectedPerson.spouse = newPerson.id;
            }

            familyTree.push(newPerson);
            renderFamilyTree();
            relationshipModal.style.display = 'none';
            selectedPersonId = null;
            
            Swal.fire({
                icon: 'success',
                title: 'Relação adicionada!',
                text: `A relação de ${getRelationshipName(relationshipType)} foi criada com sucesso.`
            });
        }

        function getRelationshipName(type) {
            const names = {
                'spouse': 'cônjuge',
                'child': 'filho/filha',
                'parent': 'pai/mãe',
                'sibling': 'irmão/irmã'
            };
            return names[type] || type;
        }

        function renderFamilyTree() {
            // Clear containers
            familyTreeElement.innerHTML = '';
            connectionsContainer.innerHTML = '';

            // Sort people by y position to ensure proper layering
            const sortedPeople = [...familyTree].sort((a, b) => a.y - b.y);

            // Create all nodes first
            sortedPeople.forEach(person => {
                const node = createPersonNode(person);
                familyTreeElement.appendChild(node);
            });

            // Draw all connections
            drawConnections();
        }

        function createPersonNode(person) {
            const node = document.createElement('div');
            node.className = 'node';
            node.dataset.id = person.id;
            node.style.position = 'absolute';
            node.style.left = `${person.x}px`;
            node.style.top = `${person.y}px`;

            // Create the person shape based on gender
            const shape = document.createElement('div');
            shape.className = person.gender === 'male' ? 'male' : 'female';
            shape.textContent = person.name.charAt(0).toUpperCase();
            
            // Apply trait styles
            if (person.trait === 'presence') {
                shape.classList.add('presence');
            } else if (person.trait === 'absence') {
                shape.classList.add('absence');
            }

            // Add click event
            node.addEventListener('click', (e) => {
                e.stopPropagation();
                if (selectedTool === 'select') {
                    // Select the node
                    document.querySelectorAll('.node').forEach(n => n.classList.remove('selected'));
                    node.classList.add('selected');
                } else if (selectedTool === 'edit') {
                    openPersonModal(person);
                } else if (selectedTool === 'delete') {
                    Swal.fire({
                        title: 'Excluir pessoa?',
                        text: `Deseja excluir ${person.name}?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sim, excluir!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            deletePerson(person.id);
                        }
                    });
                } else if (selectedTool === 'relationship') {
                    openRelationshipModal(person);
                }
            });

            // Add double-click to edit
            node.addEventListener('dblclick', (e) => {
                e.stopPropagation();
                if (selectedTool !== 'edit') {
                    openPersonModal(person);
                }
            });

            // Add to node
            node.appendChild(shape);

            // Add label
            const label = document.createElement('div');
            label.className = 'label';
            label.textContent = person.name;
            if (person.birthYear) {
                label.textContent += `\n(${person.birthYear}`;
                if (person.deathYear) {
                    label.textContent += `-${person.deathYear}`;
                }
                label.textContent += ')';
            }
            node.appendChild(label);

            return node;
        }

        function drawConnections() {
            // Clear existing connections
            connectionsContainer.innerHTML = '';

            // Draw all connections
            familyTree.forEach(person => {
                // Draw marriage lines
                if (person.spouse) {
                    const spouse = familyTree.find(p => p.id === person.spouse);
                    if (spouse) {
                        const marriageLine = document.createElement('div');
                        marriageLine.className = 'connection-line marriage-line';
                        
                        // Position marriage line between spouses
                        const midX = (person.x + spouse.x) / 2;
                        marriageLine.style.left = `${midX - 10}px`;
                        marriageLine.style.top = `${person.y + 20}px`;
                        marriageLine.style.width = '20px';
                        marriageLine.style.height = '2px';
                        
                        connectionsContainer.appendChild(marriageLine);
                    }
                }

                // Draw parent-child lines
                if (person.children && person.children.length > 0) {
                    person.children.forEach(childId => {
                        const child = familyTree.find(p => p.id === childId);
                        if (child) {
                            // Draw vertical line from parent to child
                            const verticalLine = document.createElement('div');
                            verticalLine.className = 'connection-line vertical-line';
                            verticalLine.style.left = `${person.x + 20}px`;
                            verticalLine.style.top = `${person.y + 40}px`;
                            verticalLine.style.height = `${child.y - person.y - 40}px`;
                            verticalLine.style.width = '2px';
                            
                            connectionsContainer.appendChild(verticalLine);
                            
                            // Draw horizontal line to child
                            const horizontalLine = document.createElement('div');
                            horizontalLine.className = 'connection-line horizontal-line';
                            
                            // Position based on who is on the left
                            const leftX = Math.min(person.x + 20, child.x + 20);
                            const width = Math.abs(child.x - person.x);
                            
                            horizontalLine.style.left = `${leftX}px`;
                            horizontalLine.style.top = `${child.y}px`;
                            horizontalLine.style.width = `${width}px`;
                            horizontalLine.style.height = '2px';
                            
                            connectionsContainer.appendChild(horizontalLine);
                        }
                    });
                }

                // Draw sibling lines
                if (person.children && person.children.length > 1) {
                    // Group siblings together
                    const siblings = person.children.map(childId => 
                        familyTree.find(p => p.id === childId)
                    ).filter(sibling => sibling !== undefined);
                    
                    if (siblings.length > 1) {
                        // Sort siblings by x position
                        siblings.sort((a, b) => a.x - b.x);
                        
                        // Draw line connecting all siblings
                        const firstSibling = siblings[0];
                        const lastSibling = siblings[siblings.length - 1];
                        
                        const siblingLine = document.createElement('div');
                        siblingLine.className = 'connection-line sibling-line';
                        siblingLine.style.left = `${firstSibling.x + 20}px`;
                        siblingLine.style.top = `${firstSibling.y - 10}px`;
                        siblingLine.style.width = `${lastSibling.x - firstSibling.x}px`;
                        siblingLine.style.height = '2px';
                        
                        connectionsContainer.appendChild(siblingLine);
                        
                        // Draw vertical connectors from parent line to sibling line
                        siblings.forEach(sibling => {
                            const connector = document.createElement('div');
                            connector.className = 'connection-line vertical-line';
                            connector.style.left = `${sibling.x + 20}px`;
                            connector.style.top = `${firstSibling.y - 10}px`;
                            connector.style.height = '10px';
                            connector.style.width = '2px';
                            
                            connectionsContainer.appendChild(connector);
                        });
                    }
                }
            });
        }

        function deletePerson(id) {
            // Find the person
            const personIndex = familyTree.findIndex(p => p.id === id);
            if (personIndex === -1) return;

            const person = familyTree[personIndex];
            
            // Remove from children arrays of other people
            familyTree.forEach(p => {
                if (p.children && p.children.includes(id)) {
                    p.children = p.children.filter(c => c !== id);
                }
                if (p.spouse === id) {
                    p.spouse = null;
                }
            });
            
            // Remove the person
            familyTree.splice(personIndex, 1);
            
            // Re-render the tree
            renderFamilyTree();
            
            Swal.fire({
                icon: 'success',
                title: 'Pessoa excluída!',
                text: 'A pessoa foi removida da árvore genealógica.'
            });
        }

        // Initialize the app
        initializeFamilyTree();
        updateZoom();
    </script>
</body>
</html>
  