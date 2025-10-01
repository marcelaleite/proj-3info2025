<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Árvore Genealógica Minimalista</title>
    <!-- Inclusão de bibliotecas externas -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
    <?php include("style.css")?>
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-center mb-6 text-gray-800">Árvore Genealógica Minimalista</h1>

        <!-- Painel de Ferramentas -->
        <div class="tool-panel">
            <div class="tool-btn active" id="select-tool" title="Selecionar">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </div>
            <div class="tool-btn" id="save-tool" title="Salvar Árvore">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>

        <!-- Legenda -->
        <div class="absolute top-20 left-20 bg-white p-4 rounded-lg shadow-md z-10">
            <h3 class="font-bold mb-2">Legenda:</h3>
            <div class="legend-item">
                <div class="legend-icon male-legend"></div>
                <span>Homem</span>
            </div>
            <div class="legend-item">
                <div class="legend-icon female-legend"></div>
                <span>Mulher</span>
            </div>
            <div class="legend-item">
                <div class="legend-icon" style="background-color: #10b981;"></div>
                <span>Traço Presente</span>
            </div>
            <div class="legend-item">
                <div class="legend-icon" style="background-color: #1f2937;"></div>
                <span>Traço Ausente</span>
            </div>
            <div class="legend-item">
                <div class="connection-line marriage-line" style="width: 20px;"></div>
                <span>Casamento</span>
            </div>
            <div class="legend-item">
                <div class="connection-line sibling-line" style="width: 20px;"></div>
                <span>Irmãos</span>
            </div>
        </div>

        <!-- Container da Árvore -->
        <div class="tree-container" id="tree-container">
            <div class="drag-handle" id="drag-handle"></div>
            <div id="connections-container" class="connections-container"></div>
            <div id="family-tree"></div>
        </div>

        <!-- Controles de Zoom -->
        <div class="zoom-controls">
            <div class="zoom-btn" id="zoom-out">-</div>
            <div class="zoom-level" id="zoom-level">100%</div>
            <div class="zoom-btn" id="zoom-in">+</div>
        </div>

        <!-- Modal de Edição/Adição -->
        <div id="person-modal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2 id="modal-title">Adicionar Pessoa</h2>
                <div id="relationship-info" class="mb-4" style="display: none;"></div>
                <form id="genealogicForm">
                    <div class="form-group">
                        <label for="nomeCompleto">Nome Completo</label>
                        <input type="text" id="nomeCompleto" name="nomeCompleto" placeholder="Ex: João Silva" required />
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
                            <label for="corOlho">Cor do Olho</label>
                            <input type="text" id="corOlho" name="corOlho" placeholder="Ex: castanho" required />
                        </div>
                        <div>
                            <label for="corCabelo">Cor do Cabelo</label>
                            <input type="text" id="corCabelo" name="corCabelo" placeholder="Ex: preto" required />
                        </div>
                    </div>
                    <div class="row two-cols">
                        <div>
                            <label for="tipoSanguineo">Tipo Sanguíneo</label>
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
                            <label for="nacionalidade">Nacionalidade</label>
                            <select id="nacionalidade" name="nacionalidade" required class="select" style="color: white; background-color: #222;">
                                <option value="Brasil">Brasil</option>
                                <option value="Argentina">Argentina</option>
                                <option value="Chile">Chile</option>
                                <!-- Adicione mais opções conforme necessário -->
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <label>Gênero</label>
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" name="gender" value="male" checked>
                                <span>Masculino</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="gender" value="female">
                                <span>Feminino</span>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <label>Presença do Traço</label>
                        <div class="trait-container">
                            <label class="radio-label">
                                <input type="radio" name="trait" id="trait-presence" value="presence" checked>
                                <span>Presente</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="trait" id="trait-absence" value="absence">
                                <span>Ausente</span>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <label>Formato da Orelha</label>
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" name="tipoOrelha" value="solta" checked>
                                <span>Solta</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="tipoOrelha" value="pres">
                                <span>Presa</span>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <label>Covinhas</label>
                        <div class="checkbox-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="covinhas" value="bochecha">
                                <span>Bochecha</span>
                            </label>
                            <label class="checkbox-label">
                                <input type="checkbox" name="covinhas" value="queixo">
                                <span>Queixo</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="doencaGenealogica">Doença Genealógica (Opcional)</label>
                        <textarea id="doencaGenealogica" name="doencaGenealogica" rows="3" placeholder="Ex.: Alzheimer, Hemofilia..."></textarea>
                    </div>
                    <div class="form-actions">
                        <a class="btn ghost" href="#" id="back-btn">Voltar</a>
                        <button type="submit" class="btn primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // --- 1. Inicialização de Variáveis e Elementos DOM ---
        let selectedTool = 'select';
        let familyTree = [];
        let familyUnits = [];
        let currentPersonId = 1;
        let currentFamilyUnitId = 1;
        let isDragging = false;
        let dragStartX = 0, dragStartY = 0;
        let treeOffsetX = 0, treeOffsetY = 0;
        let zoomLevel = 100;
        let selectedPersonId = null;
        let editingPersonId = null;
        let relationshipContext = null; // {type: 'spouse/child/parent/sibling', personId: id}
        let tempRelationshipContext = null; // Usado para armazenar contexto temporário após salvar no backend

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
        const modalTitle = document.getElementById('modal-title');

        // --- 2. Configuração de Eventos de Ferramentas ---
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
            openRelationshipModal();
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
                // Se for a primeira pessoa, direciona para o cadastro
                Swal.fire({
                    title: 'Primeira Pessoa',
                    text: 'Vamos começar com a primeira pessoa da árvore!',
                    icon: 'info',
                    confirmButtonText: 'Cadastrar'
                }).then(() => {
                    openPersonModal();
                });
                return;
            }
            openRelationshipModal();
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

        // --- 3. Funções de Interface e Modal ---
        function openRelationshipModal() {
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
        }

        function updateActiveTool() {
            document.querySelectorAll('.tool-btn').forEach(btn => btn.classList.remove('active'));
            if (selectedTool) {
                const toolButton = document.getElementById(selectedTool + '-tool');
                if (toolButton) {
                    toolButton.classList.add('active');
                }
            }
        }

        function openPersonModal(person = null, relationshipType = null, relatedPersonId = null) {
            personModal.style.display = 'block';
            editingPersonId = person ? person.id : null;
            modalTitle.textContent = person ? 'Editar Pessoa' : 'Adicionar Pessoa';

            if (relationshipType && relatedPersonId) {
                relationshipContext = {
                    type: relationshipType,
                    personId: relatedPersonId
                };
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

            if (person) {
                document.getElementById('nomeCompleto').value = person.name || '';
                document.getElementById('birthYear').value = person.birthYear || '';
                document.getElementById('deathYear').value = person.deathYear || '';
                document.getElementById('corOlho').value = person.eyeColor || '';
                document.getElementById('corCabelo').value = person.hairColor || '';
                document.getElementById('tipoSanguineo').value = person.bloodType || 'O+';
                document.getElementById('nacionalidade').value = person.nationality || 'Brasil';
                document.getElementById('doencaGenealogica').value = person.geneticDisease || '';

                const genderRadios = document.querySelectorAll('input[name="gender"]');
                genderRadios.forEach(radio => {
                    radio.checked = false;
                    if (person.gender && radio.value === person.gender) {
                        radio.checked = true;
                    }
                });

                const covinhasCheckboxes = document.querySelectorAll('input[name="covinhas"]');
                covinhasCheckboxes.forEach(cb => {
                    cb.checked = person.dimples && person.dimples.includes(cb.value);
                });

                document.getElementById('trait-presence').checked = person.trait === 'presence';
                document.getElementById('trait-absence').checked = person.trait === 'absence';

                document.getElementById('isAlive').checked = person.alive;
                document.getElementById('deathYear').disabled = person.alive;
            } else {
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

        // --- 4. Eventos de Formulário e Controles de UI ---
        genealogicForm.addEventListener('submit', (e) => {
            e.preventDefault();
            savePersonData();
        });

        backBtn.addEventListener('click', (e) => {
            e.preventDefault();
            personModal.style.display = 'none';
            relationshipContext = null;
            savePersonData();
        });

        document.getElementById('isAlive').addEventListener('change', function() {
            const deathYearInput = document.getElementById('deathYear');
            deathYearInput.disabled = this.checked;
            if (this.checked) {
                deathYearInput.value = '';
            }
        });

        // --- 5. Eventos de Arrastar e Zoom ---
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

        // --- 6. Funções de Gerenciamento de Dados ---
        function getOrCreateFamilyUnitForParents(parent1Id, parent2Id = null) {
            let unit = familyUnits.find(fu => {
                const hasParent1 = fu.parents.includes(parent1Id);
                const hasParent2 = parent2Id ? fu.parents.includes(parent2Id) : true;
                return hasParent1 && hasParent2;
            });
            if (!unit) {
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

        // --- 7. Funções de CRUD (Salvar, Editar, Excluir) ---
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

        function saveFamilyTree() {
            Swal.fire({
                title: 'Salvando...',
                text: 'Aguarde enquanto salvamos sua árvore genealógica',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });
            localStorage.setItem('familyTreeBackup', JSON.stringify({
                familyTree: familyTree,
                familyUnits: familyUnits,
                timestamp: new Date().toISOString()
            }));
            Swal.fire({
                icon: 'success',
                title: 'Salvo com sucesso!',
                text: 'Sua árvore foi salva localmente',
                timer: 2000
            });
        }

        function savePerfilToDatabase(person, parentId = null, motherId = null) {
            // Simula uma chamada AJAX para o backend PHP
            // Substitua 'save_perfil.php' pelo endpoint real
            const perfilData = {
                usuarioId: person.id,
                sexo: person.gender === 'male' ? 'M' : 'F',
                corOlho: person.eyeColor,
                corCabelo: person.hairColor,
                tipoOrelha: person.earType,
                tipoSanguineo: person.bloodType,
                daltonismo: false, // Exemplo de campo fixo
                sardas: false,
                fator: "",
                covQueixo: person.dimples && person.dimples.includes('queixo') ? true : false,
                covBochecha: person.dimples && person.dimples.includes('bochecha') ? true : false,
                albinismo: false,
                nacionalidade: person.nationality,
                doencaGenealogica: person.geneticDisease,
                idPai: parentId,
                idMae: motherId
            };

            // Simulação de sucesso/erro
            return new Promise((resolve, reject) => {
                setTimeout(() => {
                    if (Math.random() > 0.1) { // 90% de chance de sucesso
                        if (!person.dbId) {
                            person.dbId = Math.floor(Math.random() * 10000); // Simula ID retornado
                        }
                        console.log("Perfil salvo/atualizado com sucesso no banco de dados (simulado).", perfilData);
                        resolve({success: true, id: person.dbId});
                    } else {
                        console.error("Erro ao salvar/atualizar perfil no banco de dados (simulado): ", perfilData);
                        reject(new Error("Erro de conexão simulado."));
                    }
                }, 500); // Simula latência
            });

            // Exemplo real com jQuery:
            /*
            const isUpdate = person.dbId ? true : false;
            return $.ajax({
                url: 'save_perfil.php', // Endpoint PHP real
                type: isUpdate ? 'PUT' : 'POST',
                data: perfilData,
                success: function(response) {
                    if(response.success) {
                        if (!person.dbId) {
                            person.dbId = response.id;
                        }
                        console.log("Perfil salvo/atualizado com sucesso no banco de dados.");
                    } else {
                        console.error("Erro ao salvar/atualizar perfil no banco de dados: ", response.error);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Erro de AJAX ao salvar/atualizar perfil:", error);
                }
            });
            */
        }

        function deletePerfilFromDatabase(dbId) {
            if (!dbId) return;
            // Simula uma chamada AJAX para o backend PHP
            // Substitua 'delete_perfil.php' pelo endpoint real
            return new Promise((resolve, reject) => {
                setTimeout(() => {
                    if (Math.random() > 0.1) { // 90% de chance de sucesso
                        console.log("Perfil excluído com sucesso do banco de dados (simulado).", dbId);
                        resolve({success: true});
                    } else {
                        console.error("Erro ao excluir perfil do banco de dados (simulado): ", dbId);
                        reject(new Error("Erro de conexão simulado."));
                    }
                }, 500); // Simula latência
            });

            // Exemplo real com jQuery:
            /*
            return $.ajax({
                url: 'delete_perfil.php', // Endpoint PHP real
                type: 'DELETE',
                data: { id: dbId },
                success: function(response) {
                    if(response.success) {
                        console.log("Perfil excluído com sucesso do banco de dados.");
                    } else {
                        console.error("Erro ao excluir perfil do banco de dados: ", response.error);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Erro de AJAX ao excluir perfil:", error);
                }
            });
            */
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
                const personIndex = familyTree.findIndex(p => p.id === editingPersonId);
                if (personIndex !== -1) {
                    // Atualiza os campos no array
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
                    // familyTree[personIndex].dbId = document.getElementById('dbId').value; // Assumindo que não muda ao editar

                    const parentId = familyTree[personIndex].parentId || null; // Assume que ID dos pais não muda na edição
                    const motherId = familyTree[personIndex].motherId || null;

                    // Salva no backend
                    savePerfilToDatabase(familyTree[personIndex], parentId, motherId)
                        .then(() => {
                            renderFamilyTree(); // Re-renderiza a árvore após sucesso
                            personModal.style.display = 'none';
                            relationshipContext = null;
                            Swal.fire('Atualizado!', 'Informações atualizadas com sucesso.', 'success');
                        })
                        .catch(() => {
                            Swal.fire('Erro!', 'Falha ao atualizar no banco de dados.', 'error');
                        });
                }
            } else {
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
                    eyeColor: formData.get('corOlho'),
                    hairColor: formData.get('corCabelo'),
                    bloodType: formData.get('tipoSanguineo'),
                    nationality: formData.get('nacionalidade'),
                    dimples: dimplesValue,
                    earType: formData.get('tipoOrelha'),
                    geneticDisease: formData.get('doencaGenealogica') || ''
                };

                let parentId = null; // Inicialmente nulo
                let motherId = null; // Inicialmente nulo
                tempRelationshipContext = {...relationshipContext}; // Armazena cópia do contexto temporário

                // Salva no backend
                savePerfilToDatabase(person, parentId, motherId)
                    .then(() => {
                        // Adiciona à árvore local após salvar no backend
                        familyTree.push(person);

                        // Se houver contexto de relação, cria a relação e atualiza a árvore
                        if (tempRelationshipContext && tempRelationshipContext.personId) {
                             const relatedPerson = familyTree.find(p => p.id === tempRelationshipContext.personId);
                             if (relatedPerson) {
                                // Define posição baseada na relação
                                if (tempRelationshipContext.type === 'spouse') {
                                    person.x = relatedPerson.x + (person.gender === 'male' ? 100 : -100);
                                    person.y = relatedPerson.y;
                                } else if (tempRelationshipContext.type === 'child') {
                                    const unit = getOrCreateFamilyUnitForParents(relatedPerson.id);
                                    if (!unit.children.includes(person.id)) {
                                        unit.children.push(person.id);
                                    }
                                    person.y = relatedPerson.y + 150;
                                    person.x = relatedPerson.x; // Inicialmente alinhado com o pai/mãe
                                } else if (tempRelationshipContext.type === 'parent') {
                                    const unit = getOrCreateFamilyUnitForParents(person.id);
                                    if (!unit.children.includes(relatedPerson.id)) {
                                        unit.children.push(relatedPerson.id);
                                    }
                                    person.y = relatedPerson.y - 150;
                                    person.x = relatedPerson.x;
                                } else if (tempRelationshipContext.type === 'sibling') {
                                     const unit = getFamilyUnitForChild(tempRelationshipContext.personId) || getFamilyUnitForParent(tempRelationshipContext.personId);
                                     if (unit) {
                                         unit.children.push(person.id);
                                         // Ajusta posição X para alinhar com irmãos
                                         const siblings = unit.children.map(id => familyTree.find(p => p.id === id)).filter(Boolean);
                                         if (siblings.length > 1) {
                                             siblings.sort((a, b) => a.x - b.x);
                                             const lastSibling = siblings[siblings.length - 2]; // Penúltimo (o recém-adicionado é o último)
                                             person.x = lastSibling.x + 80; // Espaçamento fixo
                                         } else {
                                             person.x = relatedPerson.x + 80; // Se só tiver um irmão, coloca ao lado
                                         }
                                         person.y = relatedPerson.y;
                                     } else {
                                         // Se não encontrar unidade, cria uma nova e adiciona os dois como filhos
                                         const newUnit = getOrCreateFamilyUnitForParents(null);
                                         newUnit.children.push(relatedPerson.id, person.id);
                                         person.x = relatedPerson.x + 80;
                                         person.y = relatedPerson.y;
                                     }
                                }
                                // Atualiza os IDs dos pais no backend se necessário (não implementado aqui)
                             }
                        }
                        tempRelationshipContext = null; // Limpa o contexto temporário

                        renderFamilyTree(); // Re-renderiza a árvore após adicionar e posicionar
                        updateTreePosition(); // Atualiza zoom/posição
                        personModal.style.display = 'none';
                        relationshipContext = null; // Limpa o contexto de relação
                        Swal.fire('Sucesso!', 'Pessoa adicionada com todos os detalhes.', 'success');
                    })
                    .catch(() => {
                        Swal.fire('Erro!', 'Falha ao salvar no banco de dados.', 'error');
                    });
            }
        }

        // --- 8. Funções de Relacionamento ---
        function getFamilyUnitForChild(childId) {
            return familyUnits.find(fu => fu.children.includes(childId));
        }

        function getFamilyUnitForParent(parentId) {
            return familyUnits.find(fu => fu.parents.includes(parentId));
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

        // --- 9. Funções de Renderização da Árvore ---
        function renderFamilyTree() {
            familyTreeElement.innerHTML = '';
            connectionsContainer.innerHTML = '';
            const sortedPeople = [...familyTree].sort((a, b) => a.y - b.y);
            sortedPeople.forEach(person => {
                const node = createPersonNode(person);
                familyTreeElement.appendChild(node);
            });
            drawConnections();
        }

        function createPersonNode(person) {
            const node = document.createElement('div');
            node.className = 'node';
            node.dataset.id = person.id;
            node.style.left = `${person.x}px`;
            node.style.top = `${person.y}px`;

            const shape = document.createElement('div');
            shape.className = person.gender === 'male' ? 'male' : 'female';
            shape.textContent = person.name.charAt(0).toUpperCase();

            if (person.trait === 'presence') {
                shape.classList.add('trait-presence');
            } else if (person.trait === 'absence') {
                shape.classList.add('trait-absence');
            }

            node.addEventListener('click', (e) => {
                e.stopPropagation();
                if (selectedTool === 'select-for-relationship') {
                    if (relationshipContext) {
                        openPersonModal(null, selectedRelationshipType, person.id);
                        selectedTool = 'select';
                        updateActiveTool();
                    }
                    return;
                }
                if (selectedTool === 'select') {
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

            node.addEventListener('dblclick', (e) => {
                e.stopPropagation();
                if (selectedTool !== 'edit' && selectedTool !== 'select-for-relationship') {
                    openPersonModal(person);
                }
            });

            node.appendChild(shape);

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
            connectionsContainer.innerHTML = '';
            familyUnits.forEach(unit => {
                if (unit.parents.length === 0 && unit.children.length === 0) {
                    return;
                }
                const parents = unit.parents
                    .map(id => familyTree.find(p => p.id === id))
                    .filter(Boolean);
                const children = unit.children
                    .map(id => familyTree.find(p => p.id === id))
                    .filter(Boolean);

                if (parents.length === 0 && children.length === 0) return;

                let centerX;
                if (parents.length > 0) {
                    const leftMostParent = Math.min(...parents.map(p => p.x));
                    const rightMostParent = Math.max(...parents.map(p => p.x + 40));
                    centerX = (leftMostParent + rightMostParent) / 2;
                } else if (children.length > 0) {
                    const leftMostChild = Math.min(...children.map(c => c.x));
                    const rightMostChild = Math.max(...children.map(c => c.x + 40));
                    centerX = (leftMostChild + rightMostChild) / 2;
                }

                const parentLineY = parents.length > 0
                    ? Math.min(...parents.map(p => p.y)) + 40
                    : children[0].y - 80;
                const childLineY = children.length > 0
                    ? Math.min(...children.map(c => c.y)) - 40
                    : parents[0].y + 80;

                // Linha de casamento
                if (parents.length >= 2) {
                    const sortedParents = [...parents].sort((a, b) => a.x - b.x);
                    const marriageLine = document.createElement('div');
                    marriageLine.className = 'connection-line marriage-line';
                    marriageLine.style.left = `${sortedParents[0].x + 20}px`;
                    marriageLine.style.top = `${parentLineY - 20}px`;
                    marriageLine.style.width = `${sortedParents[1].x - sortedParents[0].x - 20}px`;
                    connectionsContainer.appendChild(marriageLine);
                }

                // Linha vertical principal entre pais e filhos
                if (parents.length > 0 && children.length > 0) {
                    const mainVerticalLine = document.createElement('div');
                    mainVerticalLine.className = 'connection-line vertical-line';
                    mainVerticalLine.style.left = `${centerX - 1}px`;
                    mainVerticalLine.style.top = `${parentLineY}px`;
                    mainVerticalLine.style.height = `${childLineY - parentLineY}px`;
                    connectionsContainer.appendChild(mainVerticalLine);
                }

                // Linhas para irmãos
                if (children.length > 1) {
                    const sortedChildren = [...children].sort((a, b) => a.x - b.x);
                    const siblingLine = document.createElement('div');
                    siblingLine.className = 'connection-line sibling-line';
                    siblingLine.style.left = `${sortedChildren[0].x + 20}px`;
                    siblingLine.style.top = `${childLineY}px`;
                    siblingLine.style.width = `${sortedChildren[sortedChildren.length - 1].x - sortedChildren[0].x}px`;
                    connectionsContainer.appendChild(siblingLine);

                    children.forEach(child => {
                        const childConnector = document.createElement('div');
                        childConnector.className = 'connection-line vertical-line';
                        childConnector.style.left = `${child.x + 20 - 1}px`;
                        childConnector.style.top = `${childLineY}px`;
                        childConnector.style.height = `${child.y - childLineY}px`;
                        connectionsContainer.appendChild(childConnector);
                    });
                } else if (children.length === 1) {
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
            const personIndex = familyTree.findIndex(p => p.id === id);
            if (personIndex === -1) return;

            const person = familyTree[personIndex];

            if (person.dbId) {
                deletePerfilFromDatabase(person.dbId)
                    .then(() => {
                        familyUnits.forEach(unit => {
                            unit.parents = unit.parents.filter(pid => pid !== id);
                            unit.children = unit.children.filter(cid => cid !== id);
                        });
                        familyUnits = familyUnits.filter(unit => unit.parents.length > 0 || unit.children.length > 0);
                        familyTree.splice(personIndex, 1);
                        renderFamilyTree();
                        Swal.fire({
                            icon: 'success',
                            title: 'Pessoa excluída!',
                            text: 'A pessoa foi removida da árvore genealógica e do banco de dados.'
                        });
                    })
                    .catch(() => {
                         Swal.fire({
                            icon: 'error',
                            title: 'Erro!',
                            text: 'Falha ao excluir do banco de dados. A pessoa não foi removida localmente.'
                        });
                    });
            } else {
                familyUnits.forEach(unit => {
                    unit.parents = unit.parents.filter(pid => pid !== id);
                    unit.children = unit.children.filter(cid => cid !== id);
                });
                familyUnits = familyUnits.filter(unit => unit.parents.length > 0 || unit.children.length > 0);
                familyTree.splice(personIndex, 1);
                renderFamilyTree();
                Swal.fire({
                    icon: 'success',
                    title: 'Pessoa excluída!',
                    text: 'A pessoa foi removida da árvore genealógica local.'
                });
            }
        }

        // --- 10. Inicialização ---
        document.addEventListener('DOMContentLoaded', () => {
            updateZoom();
        });
    </script>
</body>
<<<<<<< HEAD
</html>
=======
</html>
>>>>>>> origin/arvoreGenealogica
