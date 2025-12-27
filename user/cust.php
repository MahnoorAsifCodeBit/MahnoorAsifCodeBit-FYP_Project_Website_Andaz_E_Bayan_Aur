<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../userLogin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Andaz-e-Bayan Aur - Design Studio</title>
  <script type="text/javascript">
  function googleTranslateElementInit() {
    new google.translate.TranslateElement({
      pageLanguage: 'en',       
      includedLanguages: 'en,ur,ar,hi,fr,id,ja',
      layout: google.translate.TranslateElement.InlineLayout.SIMPLE
    }, 'google_translate_element');
  }
</script>
<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
  <style>
   /* General Styles */
body {
  font-family: Arial, sans-serif;
  margin: 0;
  background-color: #f4f4f4;
}

/* Header */
header {
  text-align: center;
  background: linear-gradient(135deg, #800000, #b30000);
  color: white;
  padding: 20px 0;
  position: relative;
  box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
}
header h1 {
  margin: 0;
  font-size: 2rem;
}

/* Back Home Button */
.back-home-btn {
  position: absolute;
  top: 20px;
  left: 30px;
  background: rgba(255, 255, 255, 0.2);
  color: white;
  padding: 10px 18px;
  border-radius: 30px;
  text-decoration: none;
  font-weight: bold;
  font-size: 14px;
  backdrop-filter: blur(10px);
  transition: all 0.3s ease-in-out;
}
.back-home-btn:hover {
  background: rgba(255, 255, 255, 0.4);
  color: #800000;
}


/* Tabs */
.tabs {
  display: flex;
  justify-content: center;
  margin: 20px 0;
  flex-wrap: wrap;
}
.tab {
  padding: 12px 20px;
  margin-left: 20px;
  cursor: pointer;
  background: rgba(255, 255, 255, 0.2);
  border-radius: 8px;
  transition: all 0.3s ease;
}

.tab:hover, .tab.active {
  margin-left: 20px;
  background:rgb(148, 3, 3);
  color: white;
  transform: scale(1.1);
  box-shadow: 0 4px 10px rgba(255, 68, 68, 0.5);
}
/* Container Layout */
.container {
  display: flex;
  padding: 20px;
  flex-wrap: wrap;
}

/* Glassmorphism Canvas */
.canvas-container {
  flex: 3;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 15px;
  border: 2px dashed rgba(255, 255, 255, 0.3);
  backdrop-filter: blur(10px);
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
  position: relative;
  height: 600px;
  margin-right: 20px;
  overflow: hidden;
}

#designCanvas {
  width: 100%;
  height: 100%;
  position: relative;
}

/* Animated Urdu Line */
.animated-line {
  position: absolute;
  bottom: 10px;
  width: 100%;
  text-align: center;
  font-size: 24px;
  font-weight: bold;
  color: #800000;
  animation: fadeInOut 4s infinite;
  font-family: 'Noto Nastaliq Urdu', serif;
}

@keyframes fadeInOut {
  0%,
  100% {
    opacity: 0;
  }
  50% {
    opacity: 1;
  }
}

/* Right Panel */
.right-panel {
  flex: 1;
  background: #fff;
  border: 1px solid #ccc;
  padding: 10px;
  height: 600px;
  overflow-y: auto;
}

/* Draggable Images */
.draggable-image {
  width: 100%;
  margin-bottom: 10px;
  cursor: grab;
}

/* Canvas Items */
.canvas-item {
  position: absolute;
  cursor: move;
}

/* Form Elements */
input,
select,
button {
  width: 100%;
  padding: 8px;
  margin-bottom: 10px;
  border-radius: 5px;
  border: 1px solid #ccc;
}

/* Buttons */
#downloadBtn,
#downloadBtnPanel {
  padding: 10px 20px;
  background-color: #800000;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: all 0.3s ease;
}

#downloadBtn:hover,
#downloadBtnPanel:hover {
  background-color: #650000;
  transform: scale(1.05);
  box-shadow: 0 4px 10px rgba(255, 68, 68, 0.5);
}

/* Google Translate Language Switcher */
.goog-te-gadget img {
  display: none; /* Hide Google branding */
}
.goog-te-gadget-simple {
  background-color: #800e13 !important;
  border: none !important;
  color: white !important;
  padding: 5px 10px;
  border-radius: 4px;
  cursor: pointer;
  margin: 23px;
  transition: all 0.3s ease;
}
.goog-te-gadget-simple:hover {
  background-color: #750a0a !important;
}
.goog-te-gadget-simple .VIpgJd-ZVi9od-xl07Ob-lTBxed {
  color: white !important;
  font-size: 16px;
  font-weight: bold;
}

/* Language Dropdown Positioning */
.language-dropdown {
  position: absolute;
  right: 0px;
  top: 0px;
}

@media screen and (max-width: 768px) {
  .language-dropdown {
    right: 80px;
    top: 15px;
  }
}

button {
    user-select: none;  /* Prevent text selection */
    cursor: pointer;  /* Ensure correct cursor */
}

/* Grid Overlay */
#gridOverlay {
  display: none; /* Initially hidden */
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-size: 50px 50px;
  background-image: linear-gradient(to right, rgba(128, 0, 0, 0.2) 1px, transparent 1px),
                    linear-gradient(to bottom, rgba(128, 0, 0, 0.2) 1px, transparent 1px);
  pointer-events: none;
}


/* Alignment Guides */
.alignment-line {
    border: none !important;  /* Ensure no border is applied */
    background: none !important; /* Remove any background */
}

.alignment-line.vertical {
  width: 2px;
  height: 100%;
}

.alignment-line.horizontal {
  height: 2px;
  width: 100%;
}
input[type="checkbox"] {
  accent-color: #800000;
  width: 16px;
  height: 16px;
  margin-right: 5px;
}


/* 🎨 Styling for Sliders */
.slider-container {
  margin: 10px 0;
}

.slider-label {
  display: flex;
  align-items: center;
  font-weight: bold;
  margin-bottom: 5px;
  color: #800000;
}

.slider-label i {
  margin-right: 8px;
  font-size: 18px;
}

/* Custom Styled Sliders */
.custom-slider {
  width: 92%;
  -webkit-appearance: none;
  height: 0vh;
  /* border-radius: 5px; */
  background: #ddd;
  outline: none;
  opacity: 0.9;
  transition: 0.3s;
}

.custom-slider:hover {
  opacity: 1;
}

.custom-slider::-webkit-slider-thumb {
  -webkit-appearance: none;
  appearance: none;
  width: 18px;
  height: 18px;
  background: #800000;
  border-radius: 50%;
  cursor: pointer;
}

.custom-slider::-moz-range-thumb {
  width: 18px;
  height: 2x;
  background: #800000;
  border-radius: 50%;
  cursor: pointer;
}

  </style>
</head>
<body>
<header>
  <a href="index.php" class="back-home-btn">← Back to Home</a>
  <h1>Andaz-e-Bayan Aur - Design Studio</h1>
  <div  class="language-dropdown" id="google_translate_element"></div>
</header>


  <div class="tabs">
    <div class="tab active" onclick="showPanel('images')">Calligraphy Images</div>
    <div class="tab" onclick="showPanel('text')">Add Text</div>
    <div class="tab" onclick="showPanel('fonts')">Font Styles</div>
    <div class="tab" onclick="showPanel('background')">Background</div>
    <div class="tab" onclick="showPanel('color')">Font Colors</div>
    <div class="tab" onclick="showPanel('alignment')">Alignment & Snapping</div>
    <div class="tab" onclick="showPanel('download')">Download</div>
    
  </div>

  <div class="container">
    <div class="canvas-container">
      <div id="designCanvas">
        <div id="gridOverlay"></div>
        <div class="animated-line">الفاظ میں جادو، خیالات میں روشنی</div>
      </div>
    </div>
    <div class="right-panel" id="rightPanel">
      <!-- Dynamic content goes here -->
    </div>
  </div>

  <script>
    function showPanel(type) {
        document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
const tabToActivate = [...document.querySelectorAll('.tab')].find(tab => tab.textContent.trim().toLowerCase().includes(type));
if (tabToActivate) tabToActivate.classList.add('active');

      const panel = document.getElementById('rightPanel');
      panel.innerHTML = '';

      if (type === 'images') {
        panel.innerHTML = `
          <h3>Drag & Drop Calligraphy Images</h3>
          ${[...Array(10)].map((_, i) => 
            `<img src="images/Calligraphy${i+1}.png" class="draggable-image" draggable="true" ondragstart="drag(event)">`
          ).join('')}
        `;
      }
      if (type === 'text') {
        panel.innerHTML = `
          <h3>Add Custom Text</h3>
          <input type="text" id="customText" placeholder="Enter your text here">
          <button onclick="addCustomText()">Add Text to Canvas</button>
        `;
      }
      if (type === 'fonts') {
        panel.innerHTML = `
          <h3>Change Font Style</h3>
          <select id="fontSelect" onchange="changeFont()">
            <option value="Arial">Arial</option>
            <option value="Georgia">Georgia</option>
            <option value="Times New Roman">Times New Roman</option>
            <option value="'Noto Nastaliq Urdu', serif">Urdu Nastaliq</option>
          </select>
        
          <div class="slider-container">
              <div class="slider-label">
                  <i class="fas fa-adjust"></i> Opacity
              </div>
              <input type="range" id="opacitySlider" class="custom-slider" min="0.1" max="1" step="0.1" value="1" onchange="changeOpacity()">
          </div>

          <div class="slider-container">
              <div class="slider-label">
                  <i class="fas fa-sync-alt"></i> Rotation
              </div>
              <input type="range" id="rotateSlider" class="custom-slider" min="-180" max="180" step="1" value="0" onchange="rotateElement()">
          </div>

          <h3>Image Filters</h3>
          <select id="filterSelect" onchange="applyFilter()">
              <option value="none">None</option>
              <option value="grayscale(100%)">Grayscale</option>
              <option value="sepia(80%)">Sepia</option>
              <option value="blur(5px)">Blur</option>
              <option value="invert(100%)">Invert</option>
          </select>
    `;
      }
      if (type === 'background') {
        panel.innerHTML = `
          <h3>Choose Background Color</h3>
          <input type="color" id="bgColor" onchange="changeBackground()">
        `;
      }
      if (type === 'color') {
        panel.innerHTML = `
          <h3>Change Font Color</h3>
          <input type="color" id="fontColor" onchange="changeFontColor()">
        `;
      }
      if (type === 'download') {
        panel.innerHTML = `
          <h3>Save & Load Design</h3>
          <button onclick="saveDesign()">💾 Save Design</button>
          <button onclick="loadDesign()">📂 Load Design</button>
          <h3>Download Your Final Design</h3>
          <button id="downloadBtnPanel" onclick="downloadDesign()">Download Now</button>
        `;
      }
      if (type === 'alignment') {
        panel.innerHTML = `
            <h3>Alignment & Snapping</h3>
            <label>
              <input type="checkbox" id="toggleGrid" onchange="toggleGridSnap()"> Enable Grid Snapping
            </label>
    `;
}



    }

    function drag(ev) {
      ev.dataTransfer.setData("text", ev.target.src);
    }

    document.getElementById("designCanvas").addEventListener("dragover", function(ev) {
      ev.preventDefault();
    });
    document.getElementById("designCanvas").addEventListener("drop", function(ev) {
      ev.preventDefault();
      const src = ev.dataTransfer.getData("text");
      document.querySelectorAll('#designCanvas img.canvas-item').forEach(img => img.remove());

      const img = document.createElement("img");
      img.src = src;
      img.className = "canvas-item";
      img.style.position = "absolute";
      img.style.width = "870px";


      
      img.onload = () => {
        const imgHeight = img.height;
        img.style.left = (ev.offsetX - 150) + 'px';
        img.style.top = (ev.offsetY - imgHeight / 2) + 'px';
      };

      makeDraggable(img);
      this.appendChild(img);
    });

    function addCustomText() {
      const text = document.getElementById("customText").value;
      const div = document.createElement("div");
      div.className = "canvas-item";
      div.style.left = "50px";
      div.style.top = "50px";
      div.style.fontSize = "20px";
      div.style.fontWeight = "bold";
      div.innerText = text;
      makeDraggable(div);
      document.getElementById("designCanvas").appendChild(div);
    }

    function changeFont() {
      const font = document.getElementById("fontSelect").value;
      document.querySelectorAll('#designCanvas .canvas-item').forEach(item => {
        item.style.fontFamily = font;
      });
      document.querySelector('.animated-line').style.fontFamily = font;
    }

    function changeBackground() {
      const color = document.getElementById("bgColor").value;
      document.getElementById("designCanvas").style.backgroundColor = color;
    }

    function changeFontColor() {
      const color = document.getElementById("fontColor").value;
      document.querySelectorAll('#designCanvas .canvas-item').forEach(item => {
        item.style.color = color;
      });
      document.querySelector('.animated-line').style.color = color;
    }

    function makeDraggable(el) {
      let isDragging = false;
      let shiftX, shiftY;

      function startDrag(e) {
        e.preventDefault();
        isDragging = true;
        shiftX = e.clientX - el.getBoundingClientRect().left;
        shiftY = e.clientY - el.getBoundingClientRect().top;

        document.addEventListener('mousemove', onMouseMove);
        document.addEventListener('mouseup', stopDrag);
      }

      function onMouseMove(e) {
        if (!isDragging) return;
        const canvasRect = designCanvas.getBoundingClientRect();
        el.style.left = e.pageX - shiftX - canvasRect.left + 'px';
        el.style.top = e.pageY - shiftY - canvasRect.top + 'px';
      }

      function stopDrag() {
        if (isDragging) {
          isDragging = false;
          document.removeEventListener('mousemove', onMouseMove);
          document.removeEventListener('mouseup', stopDrag);
        }
      }

      el.onmousedown = startDrag;
      el.ondragstart = () => false;
    }

    function downloadDesign() {
      html2canvas(document.getElementById('designCanvas')).then(function(canvas) {
        const link = document.createElement('a');
        link.download = 'Andaz-e-Bayan-Design.png';
        link.href = canvas.toDataURL();
        link.click();
      });
    }

    showPanel('images');
  </script>
  <script>
    const gridSize = 50; // Set grid size for snapping
let verticalGuide, horizontalGuide;

// Snapping function
function snapToGrid(value) {
    return Math.round(value / gridSize) * gridSize;
}

// Create Alignment Guides
function createGuides() {
    verticalGuide = document.createElement("div");
    verticalGuide.classList.add("alignment-line", "vertical");
    document.getElementById("designCanvas").appendChild(verticalGuide);

    horizontalGuide = document.createElement("div");
    horizontalGuide.classList.add("alignment-line", "horizontal");
    document.getElementById("designCanvas").appendChild(horizontalGuide);
}

createGuides();

// Function to check alignment and show guides
function checkAlignment(el) {
    const items = document.querySelectorAll('.canvas-item');
    let showVertical = false, showHorizontal = false;

    items.forEach(item => {
        if (item === el) return;

        const rect1 = el.getBoundingClientRect();
        const rect2 = item.getBoundingClientRect();

        if (Math.abs(rect1.left - rect2.left) < 5) {
            verticalGuide.style.left = rect1.left + "px";
            showVertical = true;
        }

        if (Math.abs(rect1.top - rect2.top) < 5) {
            horizontalGuide.style.top = rect1.top + "px";
            showHorizontal = true;
        }
    });

    verticalGuide.style.display = showVertical ? "block" : "none";
    horizontalGuide.style.display = showHorizontal ? "block" : "none";
}

// Update `makeDraggable` function to include snapping & alignment
function makeDraggable(el) {
    let isDragging = false;
    let shiftX, shiftY;

    function startDrag(e) {
        e.preventDefault();
        isDragging = true;
        shiftX = e.clientX - el.getBoundingClientRect().left;
        shiftY = e.clientY - el.getBoundingClientRect().top;

        document.addEventListener('mousemove', onMouseMove);
        document.addEventListener('mouseup', stopDrag);
    }

    function onMouseMove(e) {
        if (!isDragging) return;
        
        const canvasRect = designCanvas.getBoundingClientRect();
        let newX = e.pageX - shiftX - canvasRect.left;
        let newY = e.pageY - shiftY - canvasRect.top;

        // Snap to grid
        newX = snapToGrid(newX);
        newY = snapToGrid(newY);

        el.style.left = newX + 'px';
        el.style.top = newY + 'px';

        checkAlignment(el);
    }

    function stopDrag() {
        isDragging = false;
        verticalGuide.style.display = "none";
        horizontalGuide.style.display = "none";

        document.removeEventListener('mousemove', onMouseMove);
        document.removeEventListener('mouseup', stopDrag);
    }

    el.onmousedown = startDrag;
    el.ondragstart = () => false;
}

// Apply grid snapping & alignment to all existing elements
document.querySelectorAll('.canvas-item').forEach(makeDraggable);


let isSnapEnabled = false;
let isGuideEnabled = false;

function toggleGridSnap() {
    const gridOverlay = document.getElementById("gridOverlay");
    const isChecked = document.getElementById("toggleGrid").checked;

    if (isChecked) {
        gridOverlay.style.display = "block"; // Show grid
    } else {
        gridOverlay.style.display = "none"; // Hide grid
    }
}

function toggleGuides() {
    const isChecked = document.getElementById("toggleGuides").checked;
    
    if (isChecked) {
        createGuides(); // Create guides only when enabled
    } else {
        document.querySelectorAll('.alignment-line').forEach(guide => guide.remove());
    }
}


// Modify snapping function to check if snapping is enabled
function snapToGrid(value) {
    return isSnapEnabled ? Math.round(value / gridSize) * gridSize : value;
}

// Modify `checkAlignment` to respect the guide toggle
function checkAlignment(el) {
    if (!isGuideEnabled) {
        verticalGuide.style.display = "none";
        horizontalGuide.style.display = "none";
        return;
    }

    const items = document.querySelectorAll('.canvas-item');
    let showVertical = false, showHorizontal = false;

    items.forEach(item => {
        if (item === el) return;

        const rect1 = el.getBoundingClientRect();
        const rect2 = item.getBoundingClientRect();

        if (Math.abs(rect1.left - rect2.left) < 5) {
            verticalGuide.style.left = rect1.left + "px";
            showVertical = true;
        }

        if (Math.abs(rect1.top - rect2.top) < 5) {
            horizontalGuide.style.top = rect1.top + "px";
            showHorizontal = true;
        }
    });

    verticalGuide.style.display = showVertical ? "block" : "none";
    horizontalGuide.style.display = showHorizontal ? "block" : "none";
}

// Opacity, Rotation, Image Filters
let selectedElement = null; // Store the selected element

// Select an element when clicked
document.getElementById("designCanvas").addEventListener("click", function(event) {
    if (event.target.classList.contains("canvas-item")) {
        selectedElement = event.target;
    }
});

// Change Opacity
function changeOpacity() {
    if (selectedElement) {
        const opacityValue = document.getElementById("opacitySlider").value;
        selectedElement.style.opacity = opacityValue;
    }
}

// Rotate Element
function rotateElement() {
    if (selectedElement) {
        const rotateValue = document.getElementById("rotateSlider").value;
        selectedElement.style.transform = `rotate(${rotateValue}deg)`;
    }
}

// Apply Image Filters
function applyFilter() {
    if (selectedElement && selectedElement.tagName === "IMG") {
        const filterValue = document.getElementById("filterSelect").value;
        selectedElement.style.filter = filterValue;
    }
}

// Save and Load Design
// Function to save design
function saveDesign() {
    const designCanvas = document.getElementById("designCanvas").innerHTML;
    const bgColor = document.getElementById("designCanvas").style.backgroundColor;
    
    // Store in LocalStorage
    localStorage.setItem("savedDesign", designCanvas);
    localStorage.setItem("bgColor", bgColor);
    
    alert(" Design saved successfully!");
}

// Function to load saved design
function loadDesign() {
    const savedDesign = localStorage.getItem("savedDesign");
    const savedBgColor = localStorage.getItem("bgColor");

    if (savedDesign) {
        document.getElementById("designCanvas").innerHTML = savedDesign;
        document.getElementById("designCanvas").style.backgroundColor = savedBgColor;

        restoreDraggableEvents(); // Restore drag functionality
        alert(" Design loaded successfully!");
    } else {
        alert("⚠ No saved design found.");
    }
}

// Automatically load design on page refresh
window.onload = function() {
    if (localStorage.getItem("savedDesign")) {
        loadDesign();
    }
};

window.addEventListener("load", function () {
    sessionStorage.removeItem("savedDesign");  // Clear session storage
    localStorage.removeItem("savedDesign");   // Clear local storage
});



  </script>
  <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
  <script>
    document.getElementById('downloadBtn').addEventListener('click', downloadDesign);
  </script>


</body>
</html>
