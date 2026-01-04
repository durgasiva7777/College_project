<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Experiment 1: HARTNEU GOVERNOR</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  
  <!-- External CSS -->
  <link rel="stylesheet" href="../../css/experiments.css" />
</head>
<body>
  <div class="container">
    <!-- Main Form -->
    <form id="exp1-form" method="post" onsubmit="event.preventDefault(); submitExperiment();" class="form-section">
      <!-- Experiment Header -->
      <div class="exp-header">
        <div style="display:flex;flex-direction:column;">
          <label for="expNo">Experiment No.</label>
          <input type="text" id="experiment_id" name="experiment_id" placeholder="Exp. No" />
        </div>
        <div style="display:flex;flex-direction:column;">
          <label for="expDate">Date</label>
          <input type="date" id="expDate" name="expDate" />
        </div>
      </div>

      <h2 style="font-size: 25px;">HARTNEU GOVERNOR</h2>

      <label for="aim">Aim</label>
      <textarea id="aim" name="aim" rows="3" placeholder="Enter experiment aim"></textarea>

      <label>Apparatus Used (Drag and Drop)</label>
      <div id="apparatus-dropbox" class="apparatus-dropbox" aria-label="Apparatus dropbox">
        <small id="apparatus-placeholder" style="color:#777;">Drag apparatus here</small>
      </div>
      <input type="hidden" id="apparatus_list" name="apparatus_list" value="">

      <h3>Procedure :- </h3>
      <textarea id="procedure" name="procedure" rows="4" placeholder="Enter Procedure "></textarea>



      <h4>Tabular Form</h4>
      <table>
            <thead>
    <tr>
        <th rowspan="2">S.No</th>
        <th colspan="2">Speed</th>
        <th colspan="2">Radius</th>
        <th colspan="2">Centrifugal Force</th>
        <th rowspan="2">Stiffed <br>(K)</th>
        <th rowspan="2">Sensitivity<br>(S)</th>
    </tr>
    <tr>
        <th>N<sub>max</sub></th>
        <th>N<sub>max</sub></th>
        <th>R<sub>max</sub></th>
        <th>R<sub>max</sub></th>
        <th>Fc<sub> max</sub></th>
        <th>Fc<sub> min</sub></th>
    </tr>
</thead>

            <tbody>
                <tr>
                    <td>1</td>
                    <td><input type="text" name="speed1"></td>
                    <td><input type="text" name="speed2"></td>
                    <td><input type="text" name="radius1"></td>
                    <td><input type="text" name="radius2"></td>
                    <td><input type="text" name="force1"></td>
                    <td><input type="text" name="force2"></td>
                    <td><input type="text" name="stiffed"></td>
                    <td><input type="text" name="sensitivity"></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td><input type="text" name="speed1"></td>
                    <td><input type="text" name="speed2"></td>
                    <td><input type="text" name="radius1"></td>
                    <td><input type="text" name="radius2"></td>
                    <td><input type="text" name="force1"></td>
                    <td><input type="text" name="force2"></td>
                    <td><input type="text" name="stiffed"></td>
                    <td><input type="text" name="sensitivity"></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td><input type="text" name="speed1"></td>
                    <td><input type="text" name="speed2"></td>
                    <td><input type="text" name="radius1"></td>
                    <td><input type="text" name="radius2"></td>
                    <td><input type="text" name="force1"></td>
                    <td><input type="text" name="force2"></td>
                    <td><input type="text" name="stiffed"></td>
                    <td><input type="text" name="sensitivity"></td>
                </tr>
                <tr>
                    <td>4</td>
                    <td><input type="text" name="speed1"></td>
                    <td><input type="text" name="speed2"></td>
                    <td><input type="text" name="radius1"></td>
                    <td><input type="text" name="radius2"></td>
                    <td><input type="text" name="force1"></td>
                    <td><input type="text" name="force2"></td>
                    <td><input type="text" name="stiffed"></td>
                    <td><input type="text" name="sensitivity"></td>
                </tr>
                <tr>
                    <td>5</td>
                    <td><input type="text" name="speed1"></td>
                    <td><input type="text" name="speed2"></td>
                    <td><input type="text" name="radius1"></td>
                    <td><input type="text" name="radius2"></td>
                    <td><input type="text" name="force1"></td>
                    <td><input type="text" name="force2"></td>
                    <td><input type="text" name="stiffed"></td>
                    <td><input type="text" name="sensitivity"></td>
                </tr>
                <tr>
                    <td>6</td>
                    <td><input type="text" name="speed1"></td>
                    <td><input type="text" name="speed2"></td>
                    <td><input type="text" name="radius1"></td>
                    <td><input type="text" name="radius2"></td>
                    <td><input type="text" name="force1"></td>
                    <td><input type="text" name="force2"></td>
                    <td><input type="text" name="stiffed"></td>
                    <td><input type="text" name="sensitivity"></td>
                </tr>
                
            </tbody>
        </table>
    <div style="font-family:Arial, sans-serif; border:1px solid #333; padding:15px; width:90%; margin:10px auto; background:#f9f9f9; border-radius:6px;">
      <h3 style="text-align:left; margin:0; color:#222;">Formulas :</h3>  
        <h4 style="text-align: left;"> H=b/a(R<sub>Max</sub> - R<sub>Max</sub>) </h4> 
          
        <h4 style="text-align: left;"> Centrifugal Force: F<sub>c</sub> = m × r × ω<sup>2</sup> </h4>
        <h4 style="text-align: left;"> Sensitivity: S = (R<sub>max</sub> - R<sub>min</sub>) / (F<sub>cmax</sub> - F<sub>cmin</sub>) </h4>
        <h4 style="text-align: left;"> Stiffness: k = 2 × (a/b)<sup>2</sup> × (F<sub>cmax</sub> - F<sub>cmin</sub>) / (R<sub>max</sub> - R<sub>min</sub>) </h4>
        
        <h3>Calculations</h3>   
        <textarea id="formula_calculations" name="formula_calculations" rows="4" placeholder="Enter your calculations here..."></textarea>
    </div>
      <h3>Precautions:</h3>
      <textarea id="precautions" name="precautions" rows="3" placeholder="Enter precautions here..."></textarea>
      <h3>Result:</h3>
      <textarea id="result" name="result" rows="3" placeholder="Write final conclusion"></textarea>
      <div class="btn-group">
    <div class="back-btn" 
      onclick="history.back()" 
      style="cursor:pointer; background:#1a347a; color:#fff; font-weight:600; padding:8px 16px; border-radius:6px; width: fit-content;">
      Back to experiments
    </div>
    
       <button type="button" onclick="previewExp()" style="cursor:pointer; background:#007bff; color:#fff; font-weight:600; padding:8px 16px; border-radius:6px; width: fit-content;">Preview </button>
       <button type="button" onclick="submitExperiment()" style="cursor:pointer; background:#1a347a; color:#fff; font-weight:600; padding:8px 16px; border-radius:6px; width: fit-content;">Submit</button>
      </div>
    </form>
   

    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="apparatus-box">
        <h3 style="font-size:22px;margin-bottom:12px;text-align:center;">Select Apparatus</h3>
        <div class="apparatus-list">
          <button type="button" class="apparatus-btn">Universal Governor Apparatus</button>
          <button type="button" class="apparatus-btn">Dimmer slat</button>
          
        </div>
      </div>
      <div class="calculator-box">
        <h3 style="font-size:22px;margin-bottom:16px;">Calculator</h3>
        <input type="text" id="calc-display" readonly />
        <div class="calc-buttons">
          <button class="calc-btn calc-red" onclick="clearCalc()">C</button>
          <button class="calc-btn" onclick="press('+')">+</button>
          <button class="calc-btn" onclick="press('*')">×</button>
          <button class="calc-btn" onclick="press('/')">÷</button>
          <button class="calc-btn" onclick="press('7')">7</button>
          <button class="calc-btn" onclick="press('8')">8</button>
          <button class="calc-btn" onclick="press('9')">9</button>
          <button class="calc-btn" onclick="press('-')">−</button>
          <button class="calc-btn" onclick="press('4')">4</button>
          <button class="calc-btn" onclick="press('5')">5</button>
          <button class="calc-btn" onclick="press('6')">6</button>
          <button class="calc-btn calc-equal" onclick="calculate()">=</button>
          <button class="calc-btn" onclick="press('1')">1</button>
          <button class="calc-btn" onclick="press('2')">2</button>
          <button class="calc-btn" onclick="press('3')">3</button>
          <button class="calc-btn" onclick="press('0')">0</button>
        </div>
      </div>
    </aside>
  </div>
 
  <script >


    // ---------- Calculator ----------
function press(value) {
    const display = document.getElementById('calc-display');
    display.value += value;
}

function clearCalc() {
    document.getElementById('calc-display').value = "";
}

function calculate() {
    const display = document.getElementById('calc-display');
    try {
        display.value = eval(display.value);
    } catch (e) {
        display.value = "Error";
    }
}

// ---------- Drag & Drop ----------
document.addEventListener('DOMContentLoaded', () => {
    const tools = document.querySelectorAll('.apparatus-btn');
    tools.forEach(tool => {
        tool.setAttribute('draggable', 'true');
        
        tool.addEventListener('dragstart', (e) => {
            const name = tool.textContent.trim();
            e.dataTransfer.setData('text/plain', name);
            e.dataTransfer.effectAllowed = 'copy';
        });

        tool.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                addApparatusToDropbox(tool.textContent.trim());
            }
        });
    });

    const dropZone = document.getElementById('apparatus-dropbox');
    dropZone.addEventListener('dragover', (e) => { 
        e.preventDefault(); 
        e.dataTransfer.dropEffect = 'copy'; 
        dropZone.style.borderColor = '#3460d1';
        dropZone.style.backgroundColor = '#e8edff';
    });
    
    dropZone.addEventListener('dragenter', (e) => { 
        e.preventDefault(); 
        dropZone.style.borderColor = '#3460d1';
        dropZone.style.backgroundColor = '#e8edff';
    });
    
    dropZone.addEventListener('dragleave', (e) => { 
        dropZone.style.borderColor = '#ccd6ec';
        dropZone.style.backgroundColor = '#f8fafd';
    });
    
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = '#ccd6ec';
        dropZone.style.backgroundColor = '#f8fafd';
        const data = e.dataTransfer.getData('text/plain');
        if (!data) return;
        addApparatusToDropbox(data);
    });

    function addApparatusToDropbox(name) {
        const placeholder = document.getElementById('apparatus-placeholder');
        if (placeholder) placeholder.style.display = 'none';
        const dropZone = document.getElementById('apparatus-dropbox');
        const item = document.createElement('div');
        item.className = 'tool-item';
        item.textContent = name;
        item.title = 'Click to remove';
        item.setAttribute('role','button');
        item.setAttribute('tabindex','0');
        item.setAttribute('draggable', 'false');

        item.addEventListener('click', () => {
            item.remove();
            if (dropZone.children.length === 0 && placeholder) {
                placeholder.style.display = 'inline';
            }
        });
        
        item.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                item.remove();
                if (dropZone.children.length === 0 && placeholder) {
                    placeholder.style.display = 'inline';
                }
            }
        });

        dropZone.appendChild(item);
    }
});

// Prevent form submission on Enter key except for textareas
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('exp1-form');
    
    form.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            if (e.target.tagName !== 'TEXTAREA') {
                e.preventDefault();
                return false;
            }
        }
    });
});

function escapeHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

function formatTextWithBreaks(text) {
    if (!text) return '';
    const escaped = escapeHtml(text);
    return escaped.replace(/\n/g, '<br>');
}

// ---------- Preview ----------
// ---------- Preview ----------
function previewExp() {
    const form = document.getElementById('exp1-form');
    const apparatusList = Array.from(document.querySelectorAll("#apparatus-dropbox .tool-item"))
        .map(el => el.textContent.trim());

    const previewHtml = `
<style>
    .header-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        font-weight: 400;
        font-size: 1rem;
        color: #000000;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }
    table, th, td {
        border: 1px solid #000;
    }
    th, td {
        padding: 8px 10px;
        text-align: center;
    }
</style>
<div class="header-row">
    <div><b>Experiment No.:</b> ${escapeHtml(form.experiment_id.value || '')}</div>
    <div><b>Date:</b> ${escapeHtml(form.expDate.value || '')}</div>
</div>
<h2 style="text-align:center; margin-top: 0;">HARTNEU GOVERNOR</h2>

<p><b>Aim:</b> ${formatTextWithBreaks(form.aim.value || '')}</p>
<p><b>Apparatus Used:</b> ${apparatusList.length ? escapeHtml(apparatusList.join(", ")) : '—'}</p>

<h3>Procedure:</h3>
<p>${formatTextWithBreaks(form.procedure.value || '')}</p>

<h4>Tabular Form</h4>
<table border="1" cellspacing="0" cellpadding="5" style="width:100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th rowspan="2">S.No</th>
            <th colspan="2">Speed</th>
            <th colspan="2">Radius</th>
            <th colspan="2">Centrifugal Force</th>
            <th rowspan="2">Stiffed<br>(K)</th>
            <th rowspan="2">Sensitivity<br>(S)</th>
        </tr>
        <tr>
            <th>N<sub>max</sub></th>
            <th>N<sub>min</sub></th>
            <th>R<sub>max</sub></th>
            <th>R<sub>min</sub></th>
            <th>Fc<sub>max</sub></th>
            <th>Fc<sub>min</sub></th>
        </tr>
    </thead>
    <tbody>
        ${Array.from({length: 6}, (_, i) => {
            // Get all input elements by their type and position in the table
            const inputs = document.querySelectorAll(`table tbody tr:nth-child(${i + 1}) input[type="text"]`);
            return `
        <tr>
            <td>${i + 1}</td>
            <td>${inputs[0] ? escapeHtml(inputs[0].value || '') : ''}</td>
            <td>${inputs[1] ? escapeHtml(inputs[1].value || '') : ''}</td>
            <td>${inputs[2] ? escapeHtml(inputs[2].value || '') : ''}</td>
            <td>${inputs[3] ? escapeHtml(inputs[3].value || '') : ''}</td>
            <td>${inputs[4] ? escapeHtml(inputs[4].value || '') : ''}</td>
            <td>${inputs[5] ? escapeHtml(inputs[5].value || '') : ''}</td>
            <td>${inputs[6] ? escapeHtml(inputs[6].value || '') : ''}</td>
            <td>${inputs[7] ? escapeHtml(inputs[7].value || '') : ''}</td>
        </tr>
        `}).join('')}
    </tbody>
</table><br>

<div style="font-family:Arial, sans-serif;   text-align: left;">
    <h3 style="margin:0; color:#222;">Formulas:</h3>  
    <p>Centrifugal Force: F<sub>c</sub> = m × r × ω<sup>2</sup></p>
    <p>Sensitivity: S = (R<sub>max</sub> - R<sub>min</sub>) / (F<sub>cmax</sub> - F<sub>cmin</sub>)</p>
    <p>Stiffness: k = 2 × (a/b)<sup>2</sup> × (F<sub>cmax</sub> - F<sub>cmin</sub>) / (R<sub>max</sub> - R<sub>min</sub>)</p>
    
    <h3 style="margin-top: 20px;">Calculations</h3>   
    <p>${formatTextWithBreaks(form.formula_calculations.value || '')}</p>
</div>

<h3>Precautions:</h3>
<p>${formatTextWithBreaks(form.precautions.value || '')}</p>

<h3>Result:</h3>
<p>${formatTextWithBreaks(form.result.value || '')}</p>`;

    const win = window.open('', '_blank', 'width=900,height=800');
    win.document.write('<!DOCTYPE html><html><head><title>Preview - HARTNEU GOVERNOR</title><meta charset="utf-8"></head><body style="font-family:Arial,sans-serif; padding:20px;">');
    win.document.write(previewHtml);
    win.document.write('</body></html>');
    win.document.close();
}

// ---------- Submit Experiment ----------
function submitExperiment() {
    const form = document.getElementById('exp1-form');
    const experiment_id = form.experiment_id.value.trim();
    const employee_id = '123'; // Changed to 123 as requested

    // Validation
    if (!experiment_id) {
        alert("Please enter Experiment No.");
        return;
    }
    
    if (!form.aim.value.trim() || !form.procedure.value.trim() || 
        !form.result.value.trim()) {
        alert("Please fill all required fields.");
        return;
    }

    const apparatusList = Array.from(document.querySelectorAll("#apparatus-dropbox .tool-item"))
        .map(el => el.textContent.trim());

    if (apparatusList.length === 0) {
        alert("Please add at least one apparatus.");
        return;
    }

    // Prepare submission data
    const submissionHtml = `
<style>
    .header-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        font-weight: 400;
        font-size: 1rem;
        color: #000000;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }
    table, th, td {
        border: 1px solid #000;
    }
    th, td {
        padding: 8px 10px;
        text-align: center;
    }
</style>
<div class="header-row">
    <div><b>Experiment No.:</b> ${escapeHtml(form.experiment_id.value || '')}</div>
    <div><b>Date:</b> ${escapeHtml(form.expDate.value || '')}</div>
</div>
<h2 style="text-align:center; margin-top: 0;">HARTNEU GOVERNOR</h2>

<p><b>Aim:</b> ${formatTextWithBreaks(form.aim.value || '')}</p>
<p><b>Apparatus Used:</b> ${apparatusList.length ? escapeHtml(apparatusList.join(", ")) : '—'}</p>

<h3>Procedure:</h3>
<p>${formatTextWithBreaks(form.procedure.value || '')}</p>

<h4>Tabular Form</h4>
<table border="1" cellspacing="0" cellpadding="5" style="width:100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th rowspan="2">S.No</th>
            <th colspan="2">Speed</th>
            <th colspan="2">Radius</th>
            <th colspan="2">Centrifugal Force</th>
            <th rowspan="2">Stiffed<br>(K)</th>
            <th rowspan="2">Sensitivity<br>(S)</th>
        </tr>
        <tr>
            <th>N<sub>max</sub></th>
            <th>N<sub>min</sub></th>
            <th>R<sub>max</sub></th>
            <th>R<sub>min</sub></th>
            <th>Fc<sub>max</sub></th>
            <th>Fc<sub>min</sub></th>
        </tr>
    </thead>
    <tbody>
        ${Array.from({length: 6}, (_, i) => {
            // Get all input elements by their type and position in the table
            const inputs = document.querySelectorAll(`table tbody tr:nth-child(${i + 1}) input[type="text"]`);
            return `
        <tr>
            <td>${i + 1}</td>
            <td>${inputs[0] ? escapeHtml(inputs[0].value || '') : ''}</td>
            <td>${inputs[1] ? escapeHtml(inputs[1].value || '') : ''}</td>
            <td>${inputs[2] ? escapeHtml(inputs[2].value || '') : ''}</td>
            <td>${inputs[3] ? escapeHtml(inputs[3].value || '') : ''}</td>
            <td>${inputs[4] ? escapeHtml(inputs[4].value || '') : ''}</td>
            <td>${inputs[5] ? escapeHtml(inputs[5].value || '') : ''}</td>
            <td>${inputs[6] ? escapeHtml(inputs[6].value || '') : ''}</td>
            <td>${inputs[7] ? escapeHtml(inputs[7].value || '') : ''}</td>
        </tr>
        `}).join('')}
    </tbody>
</table>

<div style="font-family:Arial, sans-serif;  text-align: left;">
    <h3 style="margin:0; color:#222;">Formulas:</h3>  
    <p>Centrifugal Force: F<sub>c</sub> = m × r × ω<sup>2</sup></p>
    <p>Sensitivity: S = (R<sub>max</sub> - R<sub>min</sub>) / (F<sub>cmax</sub> - F<sub>cmin</sub>)</p>
    <p>Stiffness: k = 2 × (a/b)<sup>2</sup> × (F<sub>cmax</sub> - F<sub>cmin</sub>) / (R<sub>max</sub> - R<sub>min</sub>)</p>
    
    <h3 style="margin-top: 20px;">Calculations</h3>   
    <p>${formatTextWithBreaks(form.formula_calculations.value || '')}</p>
</div>

<h3>Precautions:</h3>
<p>${formatTextWithBreaks(form.precautions.value || '')}</p>

<h3>Result:</h3>
<p>${formatTextWithBreaks(form.result.value || '')}</p>`;

    const postData = new URLSearchParams();
    postData.append('experiment_id', experiment_id);
    postData.append('employee_id', employee_id);
    postData.append('submission_data', submissionHtml);

    fetch('../../submit_experiment.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: postData.toString()
    })
    .then(res => res.text())
    .then(data => {
        alert(data);
        // Optional: redirect or clear form on success
    })
    .catch(err => {
        alert('Error submitting experiment: ' + err.message);
    });
}
  </script>
</body>
</html>