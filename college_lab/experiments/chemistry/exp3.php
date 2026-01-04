
<?php 
session_start(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Experiment 3: Preparation of Phenol Formaldehyde Resin (Bakelite)</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../../css/experiments.css">
</head>
<body>
    <div class="container">
        <!-- Main Form -->
        <form id="exp3-form" method="post" action="submit_experiment.php" class="form-section">
            <!-- Add experiment header here -->
            <div class="exp-header">
                <div style="display:flex;flex-direction:column;">
                    <label for="expNo">Experiment No.</label>
                    <input type="text" id="expNo" name="expNo" placeholder="Exp. No" required/>
                </div>
                <div style="display:flex;flex-direction:column;">
                    <label for="expDate">Date</label>
                    <input type="date" id="expDate" name="expDate" required/>
                </div>
            </div>
            <h2>Preparation of Phenol Formaldehyde Resin (Bakelite)</h2>
            <label for="aim">Aim</label>
            <textarea id="aim" name="aim" rows="3" placeholder="Enter experiment aim" required></textarea>
            <label>Apparatus Used (Drag and Drop)</label>
            <div id="apparatus-dropbox" class="apparatus-dropbox" aria-label="Apparatus dropbox">
                <small id="apparatus-placeholder" style="color:#777;">Drag apparatus here</small>
            </div>
            <!-- will store apparatus for submission -->
            <input type="hidden" id="apparatus_list" name="apparatus_list" value="">
            <label for="chemicals">Chemicals Required</label>
            <textarea id="chemicals" name="chemicals" rows="3" placeholder="List chemicals" required></textarea>
            <label for="theory">Theory</label>
            <textarea id="theory" name="theory" rows="3" placeholder="Explain theory" required></textarea>
            <div style="margin-top: 12px;">
                <img src="../../exp_3.jpg" alt="Theory image" style="max-width: 100%; height: auto;" />
            </div>
            <h3>Procedure</h3>
            <textarea id="procedure" name="procedure" rows="4" placeholder="Enter Procedure" required></textarea>
            <label for="precautions">Precautions</label>
            <textarea id="precautions" name="precautions" rows="4" placeholder="Enter Precautions" required></textarea>
            <label for="result">Result</label>
            <textarea id="result" name="result" rows="3" placeholder="Write final conclusion" required></textarea>
            <div class="btn-group">
                <div class="back-btn" onclick="history.back()" style="cursor:pointer; background:#1a347a; color:#fff; font-weight:600; padding:8px 16px; border-radius:6px; width: fit-content;"> Back to experiments </div>
                <button type="button" onclick="previewExp()" style="cursor:pointer; background: #5396ff; color:#fff; font-weight:600; padding:8px 16px; border-radius:6px; width: fit-content;">Preview </button>
                <button type="submit" style="cursor:pointer; background:#1a347a; color:#fff; font-weight:600; padding:8px 16px; border-radius:6px; width: fit-content;">Submit</button>
            </div>
        </form>
        <!-- Sidebar with apparatus and calculator -->
        <aside class="sidebar">
            <div class="apparatus-box">
                <h3 style="font-size:22px;margin-bottom:12px;text-align:center;">Select Apparatus</h3>
                <div class="apparatus-list">
                    <button type="button" class="apparatus-btn">Beaker</button>
                    <button type="button" class="apparatus-btn">Burette</button>
                    <button type="button" class="apparatus-btn">Pipette</button>
                    <button type="button" class="apparatus-btn">Conical Flask</button>
                    <button type="button" class="apparatus-btn">Measuring Cylinder</button>
                    <button type="button" class="apparatus-btn">Test Tube</button>
                    <button type="button" class="apparatus-btn">Volumetric Flask</button>
                    <button type="button" class="apparatus-btn">Thermometer</button>
                    <button type="button" class="apparatus-btn">pH Meter</button>
                    <button type="button" class="apparatus-btn">Bunsen Burner</button>
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
    <script>
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

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('exp3-form');
            form.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    if (e.target.tagName !== 'TEXTAREA') {
                        e.preventDefault();
                        return false;
                    }
                }
            });
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                submitExperiment();
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
        function previewExp() {
            const form = document.getElementById('exp3-form');
            const apparatusList = Array.from(document.querySelectorAll("#apparatus-dropbox .tool-item"))
                .map(el => el.textContent.trim());

            const previewHtml = `<style>
                .header-row { display: flex; justify-content: space-between; margin-bottom: 15px; font-weight: 400; font-size: 1rem; color: #000000; }
            </style>
            <div class="header-row">
                <div><b>Experiment No.:</b> ${escapeHtml(form.expNo.value || '')}</div>
                <div><b>Date:</b> ${escapeHtml(form.expDate.value || '')}</div>
            </div>
            <h2 style="text-align:center; margin-top: 0;">Preparation of Phenol Formaldehyde Resin (Bakelite)</h2>
            <p><b>Aim:</b> ${formatTextWithBreaks(form.aim.value || '')}</p>
            <p><b>Apparatus Used:</b> ${apparatusList.length ? escapeHtml(apparatusList.join(", ")) : '—'}</p>
            <p><b>Chemicals Required:</b> ${formatTextWithBreaks(form.chemicals.value || '')}</p>
            <p><b>Theory:</b> ${formatTextWithBreaks(form.theory.value || '')}</p>
            <div style="margin:12px 0;"><img src="../../exp_3.jpg" alt="Theory image" style="max-width:100%;height:auto;"></div>
            <h3>Procedure :</h3>
            <p>${formatTextWithBreaks(form.procedure.value || '')}</p>
            <h3>Precautions :</h3>
            <p>${formatTextWithBreaks(form.precautions.value || '')}</p>
            <h3>Result :</h3>
            <p>${formatTextWithBreaks(form.result.value || '')}</p>`;

            const win = window.open('', '_blank', 'width=900,height=800');
            win.document.write('<!DOCTYPE html><html><head><title>Preview</title><meta charset="utf-8"></head><body style="font-family:Arial,sans-serif; padding:20px;">');
            win.document.write(previewHtml);
            win.document.write('</body></html>');
            win.document.close();
        }

        // ---------- Submit Experiment ----------
        function submitExperiment() {
            const form = document.getElementById('exp3-form');
            const expNo = form.expNo.value.trim();
            const employee_id = '456'; // Validation
            if (!expNo) {
                alert("Please enter Experiment No.");
                return;
            }
            if (!form.aim.value.trim() || !form.chemicals.value.trim() || !form.theory.value.trim() || !form.procedure.value.trim() || !form.precautions.value.trim() || !form.result.value.trim()) {
                alert("Please fill all required fields.");
                return;
            }
            const apparatusList = Array.from(document.querySelectorAll("#apparatus-dropbox .tool-item"))
                .map(el => el.textContent.trim());
            if (apparatusList.length === 0) {
                alert("Please add at least one apparatus.");
                return;
            }

            // Use the EXACT same template as preview
            const submissionHtml = `<style>
                .header-row { display: flex; justify-content: space-between; margin-bottom: 15px; font-weight: 400; font-size: 1rem; color: #000000; }
            </style>
            <div class="header-row">
                <div><b>Experiment No.:</b> ${escapeHtml(form.expNo.value || '')}</div>
                <div><b>Date:</b> ${escapeHtml(form.expDate.value || '')}</div>
            </div>
            <h2 style="text-align:center; margin-top: 0;">Preparation of Phenol Formaldehyde Resin (Bakelite)</h2>
            <p><b>Aim:</b> ${formatTextWithBreaks(form.aim.value || '')}</p>
            <p><b>Apparatus Used:</b> ${apparatusList.length ? escapeHtml(apparatusList.join(", ")) : '—'}</p>
            <p><b>Chemicals Required:</b> ${formatTextWithBreaks(form.chemicals.value || '')}</p>
            <p><b>Theory:</b> ${formatTextWithBreaks(form.theory.value || '')}</p>
            <div style="margin:12px 0;"><img src="../../exp_3.jpg" alt="Theory image" style="max-width:100%;height:auto;"></div>
            <h3>Procedure :</h3>
            <p>${formatTextWithBreaks(form.procedure.value || '')}</p>
            <h3>Precautions :</h3>
            <p>${formatTextWithBreaks(form.precautions.value || '')}</p>
            <h3>Result :</h3>
            <p>${formatTextWithBreaks(form.result.value || '')}</p>`;

            const postData = new URLSearchParams();
            postData.append('experiment_id', expNo);
            postData.append('employee_id', employee_id);
            postData.append('submission_data', submissionHtml);

            fetch('../../submit_experiment.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: postData.toString()
            })
            .then(res => res.text())
            .then(data => {
                alert(data); // Optional: redirect or clear form on success
            })
            .catch(err => {
                alert('Error submitting experiment: ' + err.message);
            });
        }
    </script>
</body>
</html>
