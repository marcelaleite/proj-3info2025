
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
        .node.selected .male,
        .node.selected .female {
            border-color: #10b981;
            box-shadow: 0 0 0 2px #10b981;
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
        /* Estilo para destacar o nó selecionado */
        .node.selected .male,
        .node.selected .female {
            border-color: #10b981;
            box-shadow: 0 0 0 2px #10b981;
        }
        
        /* Melhorias na estética das conexões */
        .connection-line {
            background-color: #4b5563;
            opacity: 0.8;
            transition: all 0.2s ease;
        }
        
        .vertical-line:hover, .horizontal-line:hover {
            background-color: #10b981;
            opacity: 1;
        }
        
        /* Animação suave para os nós */
        .node {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        
        /* Estilo para campo de vida */
        .alive-switch {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 20px;
        }
        
        .alive-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 20px;
        }
        
        .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 2px;
            bottom: 2px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        
        input:checked + .slider {
            background-color: #10b981;
        }
        
        input:checked + .slider:before {
            transform: translateX(20px);
        }
        
        /* Estilo para o campo de traço simplificado */
        .trait-container {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        
        .trait-option {
            display: flex;
            align-items: center;
            gap: 5px;
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.356-2m15.356 2H15" />
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
                <div id="relationship-info" style="display: none;"></div>
                <h2 class="text-xl font-bold mb-4">Informações Genealógicas</h2>
                <form id="genealogicForm">
                    <div class="row two-cols">
                        <div>
                            <label for="gender">Gênero</label>
                            <div class="radio-group">
                                <label class="radio-label">
                                    <input type="radio" name="gender" value="male" required checked>
                                    <span>Masculino</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="gender" value="female">
                                    <span>Feminino</span>
                                </label>
                            </div>
                        </div>
                        <div>
                            <label for="trait">Porta o traço genético?</label>
                            <div class="trait-container">
                                <div class="trait-option">
                                    <input type="radio" id="trait-presence" name="trait" value="presence" checked>
                                    <label for="trait-presence">Sim</label>
                                </div>
                                <div class="trait-option">
                                    <input type="radio" id="trait-absence" name="trait" value="absence">
                                    <label for="trait-absence">Não</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label for="nomeCompleto">Nome completo</label>
                        <input type="text" id="nomeCompleto" name="nomeCompleto" placeholder="Seu nome completo" required />
                    </div>
                    <div class="row two-cols">
                        <div>
                            <label for="birthYear">Ano de Nascimento</label>
                            <input type="number" id="birthYear" name="birthYear" placeholder="Ex: 1980" required />
                        </div>
                        <div>
                            <label for="deathYear">Ano de Falecimento</label>
                            <div class="relative">
                                <input type="number" id="deathYear" name="deathYear" placeholder="Ex: 2020" />
                                <div class="absolute right-0 top-0 mt-1 mr-2">
                                    <label class="alive-switch">
                                        <input type="checkbox" id="isAlive" name="isAlive">
                                        <span class="slider"></span>
                                    </label>
                                    <span class="text-xs text-gray-500 ml-2">Vivo(a)</span>
                                </div>
                            </div>
                        </div>
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
                            <select id="tipoSanguineo" name="tipoSanguineo" required class="select" style="color: white; background-color: #222;">
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
                                <option value="Brasil">Brasil</option>
                                <option value="Estados Unidos">Estados Unidos</option>
                                <option value="Argentina">Argentina</option>
                                <option value="México">México</option>
                                <option value="Colômbia">Colômbia</option>
                                <option value="Peru">Peru</option>
                                <option value="Chile">Chile</option>
                                <option value="Espanha">Espanha</option>
                                <option value="Portugal">Portugal</option>
                                <option value="França">França</option>
                                <option value="Itália">Itália</option>
                                <option value="Alemanha">Alemanha</option>
                                <option value="Reino Unido">Reino Unido</option>
                                <option value="Japão">Japão</option>
                                <option value="China">China</option>
                                <option value="Índia">Índia</option>
                                <option value="Canadá">Canadá</option>
                                <option value="Austrália">Austrália</option>
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
                                    <input type="radio" name="tipoOrelha" value="solta" required checked />
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
    </div>
    <script>
        // Initialize variables
        let selectedTool = 'select';
        let familyTree = []; // Array of person objects
        let familyUnits = []; // Array of family unit objects { id, parents: [], children: [] }
        let currentPersonId = 1;
        let currentFamilyUnitId = 1;
        let isDragging = false;
        let dragStartX = 0;
        let dragStartY = 0;
        let treeOffsetX = 0;
        let treeOffsetY = 0;
        let zoomLevel = 100;
        let selectedPersonId = null;
        let editingPersonId = null;
        let relationshipContext = null; // Para armazenar contexto de relacionamento
        
        // DOM Elements
        const treeContainer = document.getElementById('tree-container');
        const familyTreeElement = document.getElementById('family-tree');
        const connectionsContainer = document.getElementById('connections-container');
        const dragHandle = document.getElementById('drag-handle');
        const personModal = document.getElementById('person-modal');
        const genealogicForm = document.getElementById('genealogicForm');
        const closeModalBtns = document.querySelectorAll('.close');
        const zoomInBtn = document.getElementById('zoom-in');
        const zoomOutBtn = document.getElementById('zoom-out');
        const zoomLevelDisplay = document.getElementById('zoom-level');
        const backBtn = document.getElementById('back-btn');
        let selectedRelationshipType = null;

        // Tools
        document.getElementById('select-tool').addEventListener('click', () => {
            selectedTool = 'select';
            updateActiveTool();
        });

        document.getElementById('add-person-tool').addEventListener('click', () => {
            if (familyTree.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'Primeira Pessoa',
                    text: 'Clique em "Adicionar Relação" para criar a primeira pessoa da árvore.'
                });
                selectedTool = 'select';
                updateActiveTool();
                return;
            }
            
            Swal.fire({
                title: 'Selecione o tipo de relação',
                input: 'select',
                inputOptions: {
                    'spouse': 'Cônjuge',
                    'child': 'Filho(a)',
                    'parent': 'Pai/Mãe',
                    'sibling': 'Irmão(ã)'
                },
                inputPlaceholder: 'Escolha o tipo de relação',
                showCancelButton: true,
                confirmButtonText: 'Próximo',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Você precisa escolher um tipo de relação!';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Selecione a pessoa',
                        html: 'Clique na pessoa na árvore com quem deseja criar a relação.',
                        icon: 'info',
                        showConfirmButton: false,
                        timer: 2000
                    });
                    
                    selectedTool = 'select-for-relationship';
                    selectedRelationshipType = result.value;
                    updateActiveTool();
                }
            });
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
            if (familyTree.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'Primeira Pessoa',
                    text: 'Clique em "Adicionar Relação" para criar a primeira pessoa da árvore.'
                });
                selectedTool = 'select';
                updateActiveTool();
                return;
            }
            
            Swal.fire({
                title: 'Selecione o tipo de relação',
                input: 'select',
                inputOptions: {
                    'spouse': 'Cônjuge',
                    'child': 'Filho(a)',
                    'parent': 'Pai/Mãe',
                    'sibling': 'Irmão(ã)'
                },
                inputPlaceholder: 'Escolha o tipo de relação',
                showCancelButton: true,
                confirmButtonText: 'Próximo',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Você precisa escolher um tipo de relação!';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Selecione a pessoa',
                        html: 'Clique na pessoa na árvore com quem deseja criar a relação.',
                        icon: 'info',
                        showConfirmButton: false,
                        timer: 2000
                    });
                    
                    selectedTool = 'select-for-relationship';
                    selectedRelationshipType = result.value;
                    updateActiveTool();
                }
            });
        });

        document.getElementById('new-tree-tool').addEventListener('click', () => {
            Swal.fire({
                title: 'Reiniciar árvore genealógica?',
                text: "Você perderá todos os dados não salvos!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, reiniciar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    resetFamilyTree();
                }
            });
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
                const toolButton = document.getElementById(selectedTool + '-tool');
                if (toolButton) {
                    toolButton.classList.add('active');
                }
            }
        }

        // Open person modal
        function openPersonModal(person = null, relationshipType = null, relatedPersonId = null) {
            personModal.style.display = 'block';
            editingPersonId = person ? person.id : null;
            
            // Configurar contexto de relacionamento
            if (relationshipType && relatedPersonId) {
                relationshipContext = {
                    type: relationshipType,
                    personId: relatedPersonId
                };
                
                // Mostrar informação do relacionamento
                const relationshipInfo = document.getElementById('relationship-info');
                if (relationshipInfo) {
                    const relatedPerson = familyTree.find(p => p.id === relatedPersonId);
                    relationshipInfo.style.display = 'block';
                    relationshipInfo.innerHTML = `
                        <div class="bg-blue-50 p-3 rounded mb-4">
                            <strong>Adicionando como ${getRelationshipName(relationshipType)}</strong> de 
                            <span class="font-medium">${relatedPerson?.name || 'Pessoa'}</span>
                        </div>
                    `;
                }
            } else {
                relationshipContext = null;
                const relationshipInfo = document.getElementById('relationship-info');
                if (relationshipInfo) relationshipInfo.style.display = 'none';
            }
            
            // Preencher formulário com dados existentes ou limpar
            if (person) {
                // Fill form with existing data
                document.getElementById('nomeCompleto').value = person.name || '';
                document.getElementById('birthYear').value = person.birthYear || '';
                document.getElementById('deathYear').value = person.deathYear || '';
                document.getElementById('corOlho').value = person.eyeColor || '';
                document.getElementById('corCabelo').value = person.hairColor || '';
                document.getElementById('tipoSanguineo').value = person.bloodType || 'O+';
                document.getElementById('nacionalidade').value = person.nationality || 'Brasil';
                document.getElementById('doencaGenealogica').value = person.geneticDisease || '';
                document.getElementById('trait').value = person.trait || 'presence';
                
                // Handle gender
                const genderRadios = document.querySelectorAll('input[name="gender"]');
                genderRadios.forEach(radio => {
                    radio.checked = false;
                    if (person.gender && radio.value === person.gender) {
                        radio.checked = true;
                    }
                });
                if (!person.gender) genderRadios[0].checked = true;
                
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
                } else {
                    orelhaRadios[0].checked = true;
                }
                
                // Handle alive status
                const isAliveCheckbox = document.getElementById('isAlive');
                isAliveCheckbox.checked = person.alive || false;
                if (person.alive) {
                    document.getElementById('deathYear').disabled = true;
                }
                
                // Handle trait
                document.getElementById('trait-presence').checked = person.trait === 'presence';
                document.getElementById('trait-absence').checked = person.trait === 'absence';
            } else {
                // Clear form for new person
                genealogicForm.reset();
                document.querySelector('input[name="gender"][value="male"]').checked = true;
                document.getElementById('tipoSanguineo').value = 'O+';
                document.getElementById('nacionalidade').value = 'Brasil';
                document.querySelector('input[name="tipoOrelha"][value="solta"]').checked = true;
                document.getElementById('trait-presence').checked = true;
                document.getElementById('isAlive').checked = false;
                document.getElementById('deathYear').disabled = false;
            }
        }

        // Close modals
        closeModalBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                personModal.style.display = 'none';
                relationshipContext = null;
            });
        });

        window.addEventListener('click', (event) => {
            if (event.target === personModal) {
                personModal.style.display = 'none';
                relationshipContext = null;
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
            relationshipContext = null;
        });
        
        // Handle alive checkbox
        document.getElementById('isAlive').addEventListener('change', function() {
            document.getElementById('deathYear').disabled = this.checked;
            if (this.checked) {
                document.getElementById('deathYear').value = '';
            }
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

        // Family Unit Management
        function getOrCreateFamilyUnitForParents(parent1Id, parent2Id = null) {
            // Find a family unit that contains both parents (if provided)
            let unit = familyUnits.find(fu => {
                const hasParent1 = fu.parents.includes(parent1Id);
                const hasParent2 = parent2Id ? fu.parents.includes(parent2Id) : true;
                return hasParent1 && hasParent2;
            });
            if (!unit) {
                // Create a new family unit
                unit = {
                    id: currentFamilyUnitId++,
                    parents: [],
                    children: []
                };
                if (parent1Id) unit.parents.push(parent1Id);
                if (parent2Id) unit.parents.push(parent2Id);
                familyUnits.push(unit);
            }
            return unit;
        }

        function getFamilyUnitForChild(childId) {
            return familyUnits.find(fu => fu.children.includes(childId));
        }

        function getFamilyUnitForParent(parentId) {
            return familyUnits.find(fu => fu.parents.includes(parentId));
        }

        function getFamilyUnitForParents(parentIds) {
            return familyUnits.find(fu => {
                // Verifica se todos os pais estão na unidade
                return parentIds.every(id => fu.parents.includes(id));
            });
        }

        // Reset family tree
        function resetFamilyTree() {
            familyTree = [];
            familyUnits = [];
            currentPersonId = 1;
            currentFamilyUnitId = 1;
            selectedPersonId = null;
            editingPersonId = null;
            relationshipContext = null;
            
            familyTreeElement.innerHTML = '';
            connectionsContainer.innerHTML = '';
            
            Swal.fire({
                icon: 'success',
                title: 'Árvore reiniciada!',
                text: 'Você pode começar a construir sua nova árvore genealógica.'
            });
        }

        // Save family tree
        function saveFamilyTree() {
            Swal.fire({
                title: 'Salvando...',
                text: 'Aguarde enquanto salvamos sua árvore genealógica',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });

            // Primeiro tenta salvar no servidor (API real)
            const userId = localStorage.getItem('userId') || 'guest';
            const token = localStorage.getItem('authToken');
            
            fetch('https://api.genealogy.com/save-tree', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': token ? `Bearer ${token}` : ''
                },
                body: JSON.stringify({
                    familyTree: familyTree,
                    familyUnits: familyUnits,
                    userId: userId
                })
            })
            .then(response => {
                if (!response.ok) throw new Error('Falha na comunicação com o servidor');
                return response.json();
            })
            .then(data => {
                // Salva também no localStorage como backup
                localStorage.setItem('familyTreeBackup', JSON.stringify({
                    familyTree: familyTree,
                    familyUnits: familyUnits,
                    timestamp: new Date().toISOString(),
                    userId: userId
                }));
                
                Swal.fire({
                    icon: 'success',
                    title: 'Salvo com sucesso!',
                    text: 'Sua árvore foi salva no servidor e em backup local',
                    timer: 2000
                });
            })
            .catch(error => {
                console.error('Erro ao salvar:', error);
                
                // Se falhar no servidor, salva só no localStorage
                localStorage.setItem('familyTreeBackup', JSON.stringify({
                    familyTree: familyTree,
                    familyUnits: familyUnits,
                    timestamp: new Date().toISOString(),
                    userId: userId
                }));
                
                Swal.fire({
                    icon: 'warning',
                    title: 'Salvo localmente',
                    html: 'Não foi possível salvar no servidor.<br>Seus dados foram armazenados no navegador como backup.',
                    footer: '<a href="#" id="retry-save">Tentar novamente</a>'
                });
                
                // Permite tentar novamente
                document.getElementById('retry-save')?.addEventListener('click', () => {
                    saveFamilyTree();
                });
            });
        }

        // Load saved data
        function loadSavedData() {
            // Primeiro tenta carregar do servidor se estiver autenticado
            const userId = localStorage.getItem('userId');
            const token = localStorage.getItem('authToken');
            
            if (userId && token) {
                fetch(`https://api.genealogy.com/load-tree/${userId}`, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                })
                .then(response => {
                    if (response.ok) return response.json();
                    throw new Error('Dados não encontrados no servidor');
                })
                .then(data => {
                    familyTree = data.familyTree;
                    familyUnits = data.familyUnits;
                    currentPersonId = Math.max(...familyTree.map(p => p.id), 0) + 1;
                    currentFamilyUnitId = Math.max(...familyUnits.map(u => u.id), 0) + 1;
                    renderFamilyTree();
                })
                .catch(() => {
                    // Se falhar no servidor, tenta carregar do localStorage
                    loadFromLocalStorage();
                });
            } else {
                // Para usuários não autenticados, carrega só do localStorage
                loadFromLocalStorage();
            }
        }

        function loadFromLocalStorage() {
            const backup = localStorage.getItem('familyTreeBackup');
            if (backup) {
                try {
                    const { familyTree: ft, familyUnits: fu } = JSON.parse(backup);
                    familyTree = ft;
                    familyUnits = fu;
                    currentPersonId = Math.max(...familyTree.map(p => p.id), 0) + 1;
                    currentFamilyUnitId = Math.max(...familyUnits.map(u => u.id), 0) + 1;
                    renderFamilyTree();
                } catch (e) {
                    console.error('Erro ao carregar dados do localStorage:', e);
                }
            }
        }

        function savePersonData() {
            const formData = new FormData(genealogicForm);
            const dimples = [];
            document.querySelectorAll('input[name="covinhas"]:checked').forEach(cb => {
                dimples.push(cb.value);
            });
            
            const dimplesValue = dimples.length > 0 ? dimples : null;
            const isAlive = document.getElementById('isAlive').checked;
            
            if (editingPersonId) {
                // Atualizar pessoa existente
                const personIndex = familyTree.findIndex(p => p.id === editingPersonId);
                if (personIndex !== -1) {
                    // Atualizar todos os campos
                    familyTree[personIndex].name = formData.get('nomeCompleto');
                    familyTree[personIndex].birthYear = formData.get('birthYear') ? parseInt(formData.get('birthYear')) : null;
                    familyTree[personIndex].deathYear = isAlive ? null : (formData.get('deathYear') ? parseInt(formData.get('deathYear')) : null);
                    familyTree[personIndex].alive = isAlive;
                    familyTree[personIndex].eyeColor = formData.get('corOlho');
                    familyTree[personIndex].hairColor = formData.get('corCabelo');
                    familyTree[personIndex].bloodType = formData.get('tipoSanguineo');
                    familyTree[personIndex].nationality = formData.get('nacionalidade');
                    familyTree[personIndex].dimples = dimplesValue;
                    familyTree[personIndex].earType = formData.get('tipoOrelha');
                    familyTree[personIndex].geneticDisease = formData.get('doencaGenealogica') || '';
                    familyTree[personIndex].gender = formData.get('gender');
                    familyTree[personIndex].trait = formData.get('trait');
                    
                    renderFamilyTree();
                    personModal.style.display = 'none';
                    relationshipContext = null;
                    Swal.fire('Atualizado!', 'Informações atualizadas com sucesso.', 'success');
                }
            } else {
                // Adicionar nova pessoa
                const person = {
                    id: currentPersonId++,
                    name: formData.get('nomeCompleto'),
                    birthYear: formData.get('birthYear') ? parseInt(formData.get('birthYear')) : null,
                    deathYear: isAlive ? null : (formData.get('deathYear') ? parseInt(formData.get('deathYear')) : null),
                    alive: isAlive,
                    gender: formData.get('gender') || 'male',
                    trait: formData.get('trait') || 'presence',
                    x: 200 + Math.random() * 600,
                    y: 200 + Math.random() * 400,
                    // Todos os campos adicionais
                    eyeColor: formData.get('corOlho'),
                    hairColor: formData.get('corCabelo'),
                    bloodType: formData.get('tipoSanguineo'),
                    nationality: formData.get('nacionalidade'),
                    dimples: dimplesValue,
                    earType: formData.get('tipoOrelha'),
                    geneticDisease: formData.get('doencaGenealogica') || ''
                };
                
                familyTree.push(person);
                
                // Se estiver em contexto de relacionamento, criar a relação
                if (relationshipContext) {
                    createRelationship(
                        person.id,
                        relationshipContext.personId,
                        relationshipContext.type
                    );
                }
                
                renderFamilyTree();
                personModal.style.display = 'none';
                relationshipContext = null;
                
                Swal.fire('Sucesso!', 'Pessoa adicionada com todos os detalhes.', 'success');
            }
        }

        function createRelationship(newPersonId, existingPersonId, relationshipType) {
            const newPerson = familyTree.find(p => p.id === newPersonId);
            const existingPerson = familyTree.find(p => p.id === existingPersonId);
            
            if (!newPerson || !existingPerson) return;
            
            if (relationshipType === 'spouse') {
                // Encontrar ou criar unidade familiar
                let unit = getFamilyUnitForParent(existingPersonId);
                if (!unit) {
                    unit = getOrCreateFamilyUnitForParents(existingPersonId);
                }
                
                // Adicionar como cônjuge
                if (!unit.parents.includes(newPersonId)) {
                    unit.parents.push(newPersonId);
                }
                
                // Posicionar corretamente
                newPerson.gender = document.querySelector('input[name="gender"]:checked')?.value || 'male';
                newPerson.x = existingPerson.x + (newPerson.gender === 'male' ? 100 : -100);
                newPerson.y = existingPerson.y;
                newPerson.trait = document.getElementById('trait').value || 'none';
            } 
            else if (relationshipType === 'child') {
                // Encontrar ou criar unidade familiar
                let unit = getFamilyUnitForParent(existingPersonId);
                if (!unit) {
                    unit = getOrCreateFamilyUnitForParents(existingPersonId);
                }
                
                // Adicionar como filho
                if (!unit.children.includes(newPersonId)) {
                    unit.children.push(newPersonId);
                }
                
                // Posicionar corretamente
                newPerson.y = existingPerson.y + 150;
                
                // Posicionar horizontalmente baseado nos irmãos existentes
                const siblingCount = unit.children.length - 1;
                if (siblingCount > 0) {
                    // Calcular a posição média dos irmãos existentes
                    const children = unit.children
                        .map(id => familyTree.find(p => p.id === id))
                        .filter(Boolean)
                        .sort((a, b) => a.x - b.x);
                    
                    const firstChild = children[0];
                    const lastChild = children[children.length - 1];
                    const totalWidth = lastChild.x - firstChild.x + 40;
                    const averageSpacing = totalWidth / (children.length - 1);
                    
                    // Posicionar o novo irmão de forma equidistante
                    newPerson.x = firstChild.x + (siblingCount * averageSpacing);
                } else {
                    newPerson.x = existingPerson.x;
                }
                newPerson.trait = document.getElementById('trait').value || 'none';
            }
            else if (relationshipType === 'parent') {
                // Criar nova unidade familiar
                let unit = getOrCreateFamilyUnitForParents(newPersonId);
                if (!unit.children.includes(existingPersonId)) {
                    unit.children.push(existingPersonId);
                }
                
                // Posicionar corretamente
                newPerson.y = existingPerson.y - 150;
                newPerson.x = existingPerson.x;
                newPerson.trait = document.getElementById('trait').value || 'none';
            }
            else if (relationshipType === 'sibling') {
                // Encontrar unidade familiar
                let unit = getFamilyUnitForChild(existingPersonId) || getFamilyUnitForParent(existingPersonId);
                if (!unit) {
                    unit = getOrCreateFamilyUnitForParents(null);
                    unit.children.push(existingPersonId);
                }
                
                // Adicionar como irmão
                if (!unit.children.includes(newPersonId)) {
                    unit.children.push(newPersonId);
                }
                
                // Posicionar corretamente (solução melhorada para evitar sobreposição)
                const children = unit.children
                    .map(id => familyTree.find(p => p.id === id))
                    .filter(Boolean)
                    .sort((a, b) => a.x - b.x);
                
                if (children.length > 1) {
                    // Calcular a posição média dos irmãos existentes
                    const firstChild = children[0];
                    const lastChild = children[children.length - 1];
                    const totalWidth = lastChild.x - firstChild.x + 40;
                    const averageSpacing = totalWidth / (children.length - 1);
                    
                    // Encontrar o melhor lugar para inserir
                    let insertIndex = 0;
                    for (let i = 0; i < children.length - 1; i++) {
                        const midPoint = (children[i].x + children[i+1].x) / 2;
                        if (existingPerson.x < midPoint) {
                            insertIndex = i + 1;
                            break;
                        }
                    }
                    
                    // Recalcular posições para manter espaçamento uniforme
                    children.forEach((child, index) => {
                        child.x = firstChild.x + (index * averageSpacing);
                    });
                    
                    // Posicionar o novo irmão
                    newPerson.x = firstChild.x + (insertIndex * averageSpacing);
                    newPerson.y = existingPerson.y;
                } else {
                    // Primeiro irmão, posicionar à direita do irmão existente
                    newPerson.x = existingPerson.x + 80;
                    newPerson.y = existingPerson.y;
                }
                newPerson.trait = document.getElementById('trait').value || 'none';
            }
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
                
                if (selectedTool === 'select-for-relationship') {
                    openPersonModal(null, selectedRelationshipType, person.id);
                    selectedTool = 'select';
                    updateActiveTool();
                    return;
                }
                
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
                }
            });
            
            // Add double-click to edit
            node.addEventListener('dblclick', (e) => {
                e.stopPropagation();
                if (selectedTool !== 'edit' && selectedTool !== 'select-for-relationship') {
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
                label.textContent += ` (${person.birthYear}`;
                if (person.alive) {
                    label.textContent += `-atual`;
                } else if (person.deathYear) {
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
            
            familyUnits.forEach(unit => {
                if (unit.parents.length === 0 && unit.children.length === 0) {
                    return;
                }
                
                // Obter posições dos pais
                const parents = unit.parents
                    .map(id => familyTree.find(p => p.id === id))
                    .filter(Boolean);
                
                // Obter posições dos filhos
                const children = unit.children
                    .map(id => familyTree.find(p => p.id === id))
                    .filter(Boolean);
                
                if (parents.length === 0 && children.length === 0) return;
                
                // Calcular o centro dos pais
                let centerX;
                if (parents.length > 0) {
                    const leftMostParent = Math.min(...parents.map(p => p.x));
                    const rightMostParent = Math.max(...parents.map(p => p.x + 40)); // 40px é o tamanho do nó
                    centerX = (leftMostParent + rightMostParent) / 2;
                } else if (children.length > 0) {
                    // Se não tem pais, usar centro dos filhos
                    const leftMostChild = Math.min(...children.map(c => c.x));
                    const rightMostChild = Math.max(...children.map(c => c.x + 40));
                    centerX = (leftMostChild + rightMostChild) / 2;
                }
                
                // Calcular a altura da linha dos pais
                const parentLineY = parents.length > 0 
                    ? Math.min(...parents.map(p => p.y)) + 40 
                    : children[0].y - 80;
                
                // Calcular a altura da linha dos filhos
                const childLineY = children.length > 0 
                    ? Math.min(...children.map(c => c.y)) - 40 
                    : parents[0].y + 80;
                
                // 1. Desenhar linha de casamento entre pais
                if (parents.length >= 2) {
                    const sortedParents = [...parents].sort((a, b) => a.x - b.x);
                    const marriageLine = document.createElement('div');
                    marriageLine.className = 'connection-line marriage-line';
                    marriageLine.style.left = `${sortedParents[0].x + 20}px`;
                    marriageLine.style.top = `${parentLineY - 20}px`;
                    marriageLine.style.width = `${sortedParents[1].x - sortedParents[0].x - 20}px`;
                    connectionsContainer.appendChild(marriageLine);
                }
                
                // 2. Desenhar linha vertical principal
                if (parents.length > 0 && children.length > 0) {
                    const mainVerticalLine = document.createElement('div');
                    mainVerticalLine.className = 'connection-line vertical-line';
                    mainVerticalLine.style.left = `${centerX - 1}px`;
                    mainVerticalLine.style.top = `${parentLineY}px`;
                    mainVerticalLine.style.height = `${childLineY - parentLineY}px`;
                    connectionsContainer.appendChild(mainVerticalLine);
                }
                
                // 3. Desenhar linha horizontal dos filhos
                if (children.length > 1) {
                    const sortedChildren = [...children].sort((a, b) => a.x - b.x);
                    const siblingLine = document.createElement('div');
                    siblingLine.className = 'connection-line sibling-line';
                    siblingLine.style.left = `${sortedChildren[0].x + 20}px`;
                    siblingLine.style.top = `${childLineY}px`;
                    siblingLine.style.width = `${sortedChildren[sortedChildren.length - 1].x - sortedChildren[0].x}px`;
                    connectionsContainer.appendChild(siblingLine);
                    
                    // Conectar cada filho à linha dos irmãos
                    children.forEach(child => {
                        const childConnector = document.createElement('div');
                        childConnector.className = 'connection-line vertical-line';
                        childConnector.style.left = `${child.x + 20 - 1}px`;
                        childConnector.style.top = `${childLineY}px`;
                        childConnector.style.height = `${child.y - childLineY}px`;
                        connectionsContainer.appendChild(childConnector);
                    });
                } else if (children.length === 1) {
                    // Conexão direta para único filho
                    const childConnector = document.createElement('div');
                    childConnector.className = 'connection-line vertical-line';
                    childConnector.style.left = `${centerX - 1}px`;
                    childConnector.style.top = `${parentLineY}px`;
                    childConnector.style.height = `${children[0].y - parentLineY}px`;
                    connectionsContainer.appendChild(childConnector);
                    
                    const childHorizontalConnector = document.createElement('div');
                    childHorizontalConnector.className = 'connection-line horizontal-line';
                    childHorizontalConnector.style.top = `${children[0].y - 1}px`;
                    childHorizontalConnector.style.left = `${Math.min(centerX, children[0].x + 20)}px`;
                    childHorizontalConnector.style.width = `${Math.abs(centerX - (children[0].x + 20))}px`;
                    connectionsContainer.appendChild(childHorizontalConnector);
                }
                
                // 4. Conectar pais à linha vertical
                parents.forEach(parent => {
                    const parentConnector = document.createElement('div');
                    parentConnector.className = 'connection-line horizontal-line';
                    parentConnector.style.top = `${parent.y + 40 - 1}px`;
                    parentConnector.style.left = `${Math.min(centerX, parent.x + 20)}px`;
                    parentConnector.style.width = `${Math.abs(centerX - (parent.x + 20))}px`;
                    connectionsContainer.appendChild(parentConnector);
                });
            });
        }

        function deletePerson(id) {
            // Find the person
            const personIndex = familyTree.findIndex(p => p.id === id);
            if (personIndex === -1) return;
            const person = familyTree[personIndex];
            
            // Remove person from all family units
            familyUnits.forEach(unit => {
                unit.parents = unit.parents.filter(pid => pid !== id);
                unit.children = unit.children.filter(cid => cid !== id);
            });
            
            // Clean up empty family units
            familyUnits = familyUnits.filter(unit => unit.parents.length > 0 || unit.children.length > 0);
            
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
        document.addEventListener('DOMContentLoaded', () => {
            // Carregar dados salvos
            loadSavedData();
            
            // Atualizar zoom inicial
            updateZoom();
            
            // Adicionar evento para criar primeira pessoa
            document.getElementById('add-person-tool').addEventListener('click', () => {
                if (familyTree.length === 0) {
                    Swal.fire({
                        title: 'Primeira Pessoa',
                        text: 'Vamos começar com a primeira pessoa da árvore!',
                        icon: 'info',
                        confirmButtonText: 'Cadastrar'
                    }).then(() => {
                        openPersonModal();
                    });
                }
            });
            
            document.getElementById('relationship-tool').addEventListener('click', () => {
                if (familyTree.length === 0) {
                    Swal.fire({
                        title: 'Primeira Pessoa',
                        text: 'Vamos começar com a primeira pessoa da árvore!',
                        icon: 'info',
                        confirmButtonText: 'Cadastrar'
                    }).then(() => {
                        openPersonModal();
                    });
                }
            });
            
            // Atualiza o campo de ano de falecimento quando o checkbox de vivo é alterado
            document.getElementById('isAlive').addEventListener('change', function() {
                const deathYearInput = document.getElementById('deathYear');
                deathYearInput.disabled = this.checked;
                if (this.checked) {
                    deathYearInput.value = '';
                }
            });
        });
    </script>
</body>
</html>