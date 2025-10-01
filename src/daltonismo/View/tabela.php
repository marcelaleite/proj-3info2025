<!DOCTYPE html>
<html lang="pt-BR">
<head>
   
<style>

.action-buttons {
  display: flex;
  gap: 10px;
  margin-bottom: 18px;
  flex-wrap: wrap;
} 

.action-buttons button {
  background: #2d3a4a;
  color: #fff;
  border: none;
  border-radius: 6px;
  padding: 10px 20px;
  font-size: 1.05em;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.2s, box-shadow 0.2s;
  box-shadow: 0 2px 8px rgba(45,58,74,0.08);
  margin: 2px 0;
  letter-spacing: 0.2px;
}

.action-buttons button:hover,
.action-buttons button:focus {
  background: #4e5d6c;
  outline: 2px solid black;
  outline-offset: 2px;
}

@media (max-width: 700px) {
  .action-buttons {
    flex-direction: column;
    gap: 6px;
  }
  .action-buttons button {
    width: 100%;
    font-size: 1em;
    padding: 10px 0;
  }
}

#myTable {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
  background: rgba(255,255,255,0.92);
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 2px 16px rgba(0,0,0,0.10);
  margin-bottom: 24px;
  font-family: 'Poppins', Arial, sans-serif;
}

#myTable th, #myTable td {
  padding: 14px 18px;
  text-align: left;
}

#myTable th {
  background: black;
  color: #fff;
  font-weight: 600;
  letter-spacing: 0.5px;
  border-bottom: 2px solid #4e5d6c;
}

#myTable tr {
  transition: background 0.2s;
}

#myTable tbody tr:nth-child(even) {
  background: #f4f7fa;
}

#myTable tbody tr:hover {
  background: #e0e7ef;
}

.select-container {
  display: flex;
  gap: 8px;
  align-items: center;
  flex-wrap: wrap;
}

.select-container select {
  padding: 6px 12px;
  border-radius: 6px;
  border: 1px solid #bfc9d1;
  background: #fff;
  font-size: 1em;
  transition: border 0.2s;
}

.select-container select:focus {
  border-color: #2d3a4a;
  outline: none;
}

.select-container button,
#myTable button {
  background: #2d3a4a;
  color: #fff;
  border: none;
  border-radius: 6px;
  padding: 6px 14px;
  font-size: 0.98em;
  cursor: pointer;
  transition: background 0.2s, box-shadow 0.2s;
  margin: 2px 0;
}

.select-container button:hover,
#myTable button:hover {
  background: #4e5d6c;
  box-shadow: 0 2px 8px rgba(45,58,74,0.10);
}

@media (max-width: 700px) {
  #myTable th, #myTable td {
    padding: 10px 6px;
    font-size: 0.98em;
  }
  .select-container {
    flex-direction: column;
    gap: 4px;
  }
}
</style>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Tabela dalto</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../../public/css/styles.css" />
</head>
<body id="page-module-template">
  <header class="topbar" role="navigation" aria-label="Navegação principal">
    <nav class="nav-inner">
      <a href="daltonismo.html" class="nav-link">Retornar</a>
    </nav>
  </header>

  <div class="background-video" aria-hidden="true">
    <video autoplay loop muted playsinline>
      <source src="218955.mp4" type="video/mp4" />
    </video>
  </div>

  <div class="overlay" aria-hidden="true"></div>

  <main style="padding:80px 20px; min-height:100vh;">
    <div style="max-width:1100px; margin:0 auto; background: rgba(10,10,10,0.55); padding:24px; border-radius:12px;">
    <h1>Criação de tabela familiar</h1>
    <p>Neste módulo você poderá registrar os genes dos familiares que você sabe em uma tabela</p><br>
    <script src="app.js"></script>

  <!-- Tabela (substitua sua versão atual por esta) -->
<table id="myTable">
  <thead>
    <tr>
      <th>Casal 1</th>
      <th>Ações</th>
    </tr>
  </thead>
  <tbody>
    <!-- linhas serão geradas dinamicamente -->
  </tbody>
</table>

<!-- Botões de ação OUTSIDE da table -->
<div class="action-buttons" style="margin-top:12px;">
  <button onclick="addRow()">Adicionar geração</button>
  <button onclick="addColumn()">Adicionar Casal</button>
  <button onclick="removeColumn()">Remover Casal</button>
  <button onclick="exportTableToJSON()">Salvar em JSON</button>
  <button onclick="loadTableFromJSON()">Carregar do JSON</button>
</div>

<script>
/* === Helpers === */
function buildSelectHTML(selectedValue) {
  return `<select>
            <option value="1" ${selectedValue === "1" ? "selected" : ""}>Mulher normal</option>
            <option value="2" ${selectedValue === "2" ? "selected" : ""}>Mulher portadora</option>
            <option value="3" ${selectedValue === "3" ? "selected" : ""}>Mulher daltônica</option>
            <option value="4" ${selectedValue === "4" ? "selected" : ""}>Homem normal</option>
            <option value="5" ${selectedValue === "5" ? "selected" : ""}>Homem daltônico</option>
          </select>`;
}

function rebuildHeader(numFamilies) {
  const table = document.getElementById("myTable");
  const thead = table.querySelector("thead");
  const row = thead.rows[0];
  row.innerHTML = "";
  for (let i = 1; i <= numFamilies; i++) {
    const th = document.createElement("th");
    th.textContent = `Casal ${i}`;
    row.appendChild(th);
  }
  const thActions = document.createElement("th");
  thActions.textContent = "Ações";
  row.appendChild(thActions);
}

/* === Carregar === */
function loadTableFromJSON() {
  fetch("../Control/carregar.php")
    .then(response => response.json())
    .then(data => {
      const tabela = data.tabela || [];
      const table = document.getElementById("myTable");
      const tbody = table.querySelector("tbody");
      tbody.innerHTML = "";

      // determina o maior número de famílias entre as gerações (para reconstruir header)
      const maxFamilies = tabela.reduce((max, row) => Math.max(max, row.length), 0) || 1;
      rebuildHeader(maxFamilies);

      tabela.forEach(rowData => {
        const tr = tbody.insertRow();

        // para cada familia (0..maxFamilies-1)
        for (let i = 0; i < maxFamilies; i++) {
          const cellData = rowData[i] || []; // pode ser undefined -> tratamos como []
          const td = tr.insertCell();
          const container = document.createElement("div");
          container.className = "select-container";

          // se houver membros (valores) cria os selects correspondentes
          if (Array.isArray(cellData) && cellData.length > 0) {
            cellData.forEach(val => {
              // val é string (ex: "1")
              const wrapper = document.createElement("span");
              wrapper.innerHTML = buildSelectHTML(String(val));
              container.appendChild(wrapper.firstElementChild);
            });
          }
          // sempre adiciona os botões de adicionar/remover membro
          const btnAdd = document.createElement("button");
          btnAdd.textContent = "Adicionar Membro";
          btnAdd.onclick = function() { addSelect(this); };
          const btnRemove = document.createElement("button");
          btnRemove.textContent = "Remover Membro";
          btnRemove.onclick = function() { removeSelect(this); };

          container.appendChild(btnAdd);
          container.appendChild(btnRemove);
          td.appendChild(container);
        }

        // célula de ações (sempre a última)
        const tdActions = tr.insertCell();
        tdActions.innerHTML = `<button onclick="removeRow(this)">Remover</button>`;
      });
    })
    .catch(err => {
      console.error("Erro ao carregar JSON:", err);
      alert("Erro ao carregar tabela. Veja console.");
    });
}

/* === Exportar === */
function exportTableToJSON() {
  const table = document.getElementById("myTable");
  const tbody = table.querySelector("tbody");
  const data = [];

  for (let r = 0; r < tbody.rows.length; r++) {
    const row = tbody.rows[r];
    const rowData = [];
    const actionIndex = row.cells.length - 1; // última célula = Ações
    for (let c = 0; c < actionIndex; c++) {
      const selects = row.cells[c].querySelectorAll("select");
      const valores = [];
      selects.forEach(sel => valores.push(sel.value));
      rowData.push(valores);
    }
    data.push(rowData);
  }

  fetch("../Control/salvar.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ tabela: data })
  })
  .then(res => res.json())
  .then(resp => alert(resp.mensagem))
  .catch(err => {
    console.error(err);
    alert("Erro ao salvar. Veja console.");
  });
}

/* === Funções UI existentes (ajustadas pra trabalhar com header dinâmico) === */
function addRow() {
  const table = document.getElementById("myTable");
  const tbody = table.querySelector("tbody");
  const headerCells = table.querySelector("thead").rows[0].cells.length;
  const newRow = tbody.insertRow();

  for (let i = 0; i < headerCells; i++) {
    if (i === headerCells - 1) {
      newRow.insertCell(i).innerHTML = `<button onclick="removeRow(this)">Remover</button>`;
    } else {
      newRow.insertCell(i).innerHTML = `
        <div class="select-container">
          <button onclick="addSelect(this)">Adicionar Membro</button>
          <button onclick="removeSelect(this)">Remover Membro</button>
        </div>
      `;
    }
  }
}

function addSelect(button) {
  const container = button.parentNode;
  const newSelect = document.createElement("select");
  newSelect.innerHTML = `
      <option value="1">Mulher normal</option>
      <option value="2">Mulher portadora</option>
      <option value="3">Mulher daltônica</option>
      <option value="4">Homem normal</option>
      <option value="5">Homem daltônico</option>
  `;
  // insere novo select antes do botão "Adicionar Membro"
  container.insertBefore(newSelect, button);
}

function removeSelect(button) {
  const container = button.parentNode;
  const selects = container.querySelectorAll('select');
  if (selects.length > 0) {
    container.removeChild(selects[selects.length - 1]);
  }
}

function removeRow(button) {
  const row = button.parentNode.parentNode;
  row.parentNode.removeChild(row);
}

function addColumn() {
  const table = document.getElementById("myTable");
  const thead = table.querySelector('thead');
  const tbody = table.querySelector('tbody');
  const headerCells = thead.rows[0].cells.length;
  // insere um novo th antes da última coluna (Ações)
  const th = document.createElement('th');
  th.textContent = `Casal ${headerCells}`;
  thead.rows[0].insertBefore(th, thead.rows[0].cells[headerCells - 1]);
  // adiciona uma td em cada linha, antes da última coluna
  for (let row of tbody.rows) {
    const td = document.createElement('td');
    td.innerHTML = `
        <div class="select-container">
            <button onclick="addSelect(this)">Adicionar Membro</button>
            <button onclick="removeSelect(this)">Remover Membro</button>
        </div>
    `;
    row.insertBefore(td, row.cells[row.cells.length - 1]);
  }
}

function removeColumn() {
  const table = document.getElementById("myTable");
  const thead = table.querySelector('thead');
  const tbody = table.querySelector('tbody');
  const headerCells = thead.rows[0].cells.length;
  if (headerCells > 2) { // mantém pelo menos 1 casal + ações
    thead.rows[0].deleteCell(headerCells - 2);
    for (let row of tbody.rows) {
      row.deleteCell(headerCells - 2);
    }
  }
}

/* === Inicializa (se quiser carregar ao abrir) === */
// loadTableFromJSON(); // descomente se quiser carregar automaticamente ao abrir
</script>


  </main>
  
</body>
</html>