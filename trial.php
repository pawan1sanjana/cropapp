<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

// Get the username
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard | Green Leaves Supply Tracker</title>

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="images/icon.png">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = { darkMode: 'class' };
  </script>
  
  <!-- jsPDF Library -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

  <style>
    .transition-all { transition: all 0.3s ease-in-out; }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    @keyframes pulse { 0%,100% { transform: scale(1); opacity:1; } 50% { transform: scale(1.1); opacity:0.8; } }
    #loader { display:flex; justify-content:center; align-items:center; position:fixed; inset:0; background:rgba(255,255,255,0.9); backdrop-filter:blur(4px); z-index:50; transition:opacity 0.5s ease, visibility 0.5s ease; }
    #loader.hidden { opacity:0; visibility:hidden; pointer-events:none; }
    .spinner-ring, .spinner-inner, .spinner-dot { border-radius:50%; position:absolute; }
    .spinner-ring { width:80px; height:80px; border:6px solid #e8f5e9; border-top:6px solid #4CAF50; animation:spin 1s linear infinite; }
    .spinner-inner { width:60px; height:60px; top:10px; left:10px; border:4px solid #e8f5e9; border-right:4px solid #2e7d32; animation:spin 1.5s linear infinite reverse; }
    .spinner-dot { width:16px; height:16px; top:32px; left:32px; background:linear-gradient(135deg,#4CAF50,#2e7d32); animation:pulse 1s ease-in-out infinite; }
    
    /* Smooth table animations */
    tbody tr { transition: background-color 0.2s ease; }
    tbody tr:hover { background-color: rgba(74, 222, 128, 0.1); }
    
    /* Mobile sidebar toggle */
    @media (max-width: 768px) {
      #sidebar { transform: translateX(-100%); position: fixed; z-index: 40; height: 100vh; }
      #sidebar.open { transform: translateX(0); }
      #overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 30; }
      #overlay.open { display: block; }
    }

    /* Calculator Form Styles */
    .section-divider {
      border: 0;
      height: 2px;
      background: linear-gradient(to right, transparent, #4CAF50, transparent);
      margin: 1.5rem 0;
    }
  </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900 transition-all">

  <!-- Loader -->
  <div id="loader">
    <div class="relative w-20 h-20">
      <div class="spinner-ring"></div>
      <div class="spinner-inner"></div>
      <div class="spinner-dot"></div>
      <div class="absolute bottom-[-35px] left-1/2 transform -translate-x-1/2 text-green-800 dark:text-green-400 font-semibold text-sm whitespace-nowrap">Loading...</div>
    </div>
  </div>

  <!-- Mobile Overlay -->
  <div id="overlay" onclick="toggleSidebar()"></div>

  <div class="flex h-screen overflow-hidden">

   <!-- Sidebar -->
    <aside id="sidebar" class="w-64 bg-green-800 dark:bg-gray-800 text-white flex flex-col transition-all shadow-xl">
      <div class="text-center py-6 text-2xl font-bold border-b border-green-600 dark:border-gray-700 flex flex-col items-center gap-2">
        <img src="images/icon.png" alt="Company Logo" class="w-16 h-16 object-contain rounded-full shadow-md">
        <span class="text-white">Green Leaves</span>
      </div>

      <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        <a href="dashboard.php" class="block py-3 px-4 rounded-lg hover:bg-green-700 dark:hover:bg-gray-700 flex items-center transition-all">
          <i class="fas fa-home mr-3 w-5"></i>
          <span>Dashboard</span>
        </a>

  <!-- Crop with Dropdown -->
  <div class="relative">
    <button onclick="toggleDropdown('cropDropdown')" class="w-full flex items-center justify-between py-3 px-4 rounded-lg hover:bg-green-700 dark:hover:bg-gray-700 transition-all">
      <span class="flex items-center">
        <i class="fas fa-chart-bar mr-3 w-5"></i>
        Crop
      </span>
      <i class="fas fa-chevron-down w-3"></i>
    </button>

    <!-- Dropdown Menu -->
    <div id="cropDropdown" class="hidden flex-col mt-1 ml-8 space-y-1">
      <a href="cropchart.php" class="block py-2 px-4 rounded-lg hover:bg-green-700 dark:hover:bg-gray-700 flex items-center transition-all">
          <i class="fas fa-database mr-3 w-5"></i>
          <span>Crop Chart</span></a>
      
      <a href="addsupply.php" class="block py-2 px-4 rounded-lg hover:bg-green-700 dark:hover:bg-gray-700 flex items-center transition-all bg-green-700 dark:bg-gray-700">
          <i class="fas fa-plus-circle mr-3 w-5"></i>
          <span>Add Supply</span>
      </a>

      <a href="farmers.php" class="block py-2 px-4 rounded-lg hover:bg-green-600 dark:hover:bg-gray-600 transition-all">
          <i class="fas fa-users mr-3 w-5"></i>
          <span>Farmers</span>
      </a>
    </div>
  </div>


  <!-- Fertilizer with Dropdown -->
  <div class="relative">
    <button onclick="toggleDropdown('fertilizerDropdown')" class="w-full flex items-center justify-between py-3 px-4 rounded-lg hover:bg-green-700 dark:hover:bg-gray-700 transition-all">
      <span class="flex items-center">
        <i class="fas fa-chart-bar mr-3 w-5"></i>
        Fertilizer
      </span>
      <i class="fas fa-chevron-down w-3"></i>
    </button>

    <!-- Dropdown Menu -->
    <div id="fertilizerDropdown" class="hidden flex-col mt-1 ml-8 space-y-1">
      <a href="daily_report.php" class="block py-2 px-4 rounded-lg hover:bg-green-600 dark:hover:bg-gray-600 transition-all">pH Dolamite Cal</a>
      <a href="monthly_report.php" class="block py-2 px-4 rounded-lg hover:bg-green-600 dark:hover:bg-gray-600 transition-all">Monthly Report</a>
      <a href="annual_report.php" class="block py-2 px-4 rounded-lg hover:bg-green-600 dark:hover:bg-gray-600 transition-all">Annual Report</a>
    </div>
  </div>


        <a href="nav.php" class="block py-3 px-4 rounded-lg hover:bg-green-700 dark:hover:bg-gray-700 flex items-center transition-all">
          <i class="fas fa-map mr-3 w-5"></i>
          <span>Navigation</span>
        </a>


  <!-- Reports with Dropdown -->
  <div class="relative">
    <button onclick="toggleDropdown('reportsDropdown')" class="w-full flex items-center justify-between py-3 px-4 rounded-lg hover:bg-green-700 dark:hover:bg-gray-700 transition-all">
      <span class="flex items-center">
        <i class="fas fa-chart-bar mr-3 w-5"></i>
        Reports
      </span>
      <i class="fas fa-chevron-down w-3"></i>
    </button>

    <!-- Dropdown Menu -->
    <div id="reportsDropdown" class="hidden flex-col mt-1 ml-8 space-y-1">
      <a href="daily_report.php" class="block py-2 px-4 rounded-lg hover:bg-green-600 dark:hover:bg-gray-600 transition-all">Daily Report</a>
      <a href="monthly_report.php" class="block py-2 px-4 rounded-lg hover:bg-green-600 dark:hover:bg-gray-600 transition-all">Monthly Report</a>
      <a href="annual_report.php" class="block py-2 px-4 rounded-lg hover:bg-green-600 dark:hover:bg-gray-600 transition-all">Annual Report</a>
    </div>
  </div>

  <a href="settings.php" class="block py-3 px-4 rounded-lg hover:bg-green-700 dark:hover:bg-gray-700 flex items-center transition-all">
    <i class="fas fa-cog mr-3 w-5"></i>
    <span>Settings</span>
  </a>
</nav>

      <a href="logout.php" class="block py-4 text-center bg-green-700 dark:bg-green-600 hover:bg-green-600 dark:hover:bg-green-500 transition-all font-semibold">
        <i class="fas fa-sign-out-alt mr-2"></i>
        Logout
      </a>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto">
      <div class="p-4 md:p-6 lg:p-8">

        <!-- Header with Welcome + Dark/Light Toggle -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
          <div class="flex items-center gap-3">
            <button onclick="toggleSidebar()" class="md:hidden p-2 rounded-lg bg-green-600 text-white hover:bg-green-700 transition-all">
              <i class="fas fa-bars"></i>
            </button>
            <h1 class="text-2xl md:text-3xl font-semibold text-green-800 dark:text-green-400">Dashboard Overview</h1>
          </div>
          <div class="flex items-center gap-3 w-full sm:w-auto">
            <div class="flex-1 sm:flex-none bg-white dark:bg-gray-700 px-4 py-2 rounded-lg shadow transition-all">
              <span class="text-gray-700 dark:text-gray-100 text-sm md:text-base">
                <i class="fas fa-user mr-2 text-green-600 dark:text-green-400"></i>
                Welcome, <strong><?php echo htmlspecialchars($username); ?></strong>
              </span>
            </div>
            <button id="themeToggle" class="p-2 rounded-full bg-gray-200 dark:bg-gray-700 hover:scale-110 transition-all shadow-md">
              <svg id="sunIcon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500 hidden" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10 15a5 5 0 100-10 5 5 0 000 10z" />
                <path fill-rule="evenodd" d="M10 1a1 1 0 011 1v1a1 1 0 11-2 0V2a1 1 0 011-1zm0 16a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zm9-7a1 1 0 100 2h-1a1 1 0 110-2h1zM3 10a1 1 0 100 2H2a1 1 0 110-2h1zm12.364-6.364a1 1 0 00-1.414 1.414L15.95 6.95a1 1 0 001.414-1.414L15.364 3.636zM5.636 15.364a1 1 0 00-1.414 1.414L6.95 15.95a1 1 0 001.414-1.414L5.636 15.364zm9.728 0l1.414 1.414a1 1 0 001.414-1.414L16.95 15.95a1 1 0 00-1.586 0zM4.05 4.05A1 1 0 105.464 5.464L4.05 4.05z" clip-rule="evenodd"/>
              </svg>
              <svg id="moonIcon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800 dark:text-gray-100" viewBox="0 0 20 20" fill="currentColor">
                <path d="M17.293 13.293A8 8 0 016.707 2.707 8.001 8.001 0 0010 18a8 8 0 007.293-4.707z" />
              </svg>
            </button>
          </div>
        </header>

        
        <!-- Dolomite Calculator Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 transition-all max-w-2xl mx-auto">
          <h2 class="text-2xl font-bold text-center text-green-800 dark:text-green-400 mb-2">Dolomite Calculator</h2>
          <hr class="section-divider">

          <!-- Form Section -->
          <form id="calculator-form" class="space-y-3">
            <div>
              <label for="reg_no" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Registration No:</label>
              <input type="text" class="w-full px-3 py-1.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white transition-all" id="reg_no" name="reg_no" placeholder="Eg., REG-2025-001" required>
            </div>

            <div>
              <label for="farmer_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Farmer Name:</label>
              <input type="text" class="w-full px-3 py-1.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white transition-all" id="farmer_name" name="farmer_name" placeholder="Eg., John Doe" required>
            </div>

            <div>
              <label for="ph" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Soil pH:</label>
              <input type="number" step="0.01" class="w-full px-3 py-1.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white transition-all" id="ph" name="ph" placeholder="Eg., 5.5" required>
            </div>

            <div>
              <label for="land_area" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Land Area:</label>
              <input type="number" step="0.01" class="w-full px-3 py-1.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white transition-all" id="land_area" name="land_area" placeholder="Eg., 2.5" required>
            </div>

            <div>
              <label for="area_unit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Area Unit:</label>
              <select class="w-full px-3 py-1.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white transition-all" id="area_unit" name="area_unit" required>
                <option value="hectares" selected>Hectares</option>
                <option value="acres">Acres</option>
                <option value="perches">Perches</option>
              </select>
            </div>
            
            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-lg transition-all shadow-md hover:shadow-lg text-sm">
              <i class="fas fa-calculator mr-2"></i>Calculate
            </button>
          </form>

          <!-- Results Section -->
          <div id="results" class="mt-5 p-4 bg-green-50 dark:bg-gray-700 rounded-lg border border-green-200 dark:border-gray-600 hidden">
            <h3 class="text-lg font-semibold text-center text-green-800 dark:text-green-400 mb-3">Results</h3>
            <div class="space-y-2">
              <p class="text-gray-700 dark:text-gray-300"><strong class="text-green-700 dark:text-green-400">Soil pH:</strong> <span id="result-ph" class="font-mono">N/A</span></p>
              <p class="text-gray-700 dark:text-gray-300"><strong class="text-green-700 dark:text-green-400">Land Area:</strong> <span id="result-area" class="font-mono">N/A</span></p>
              <p class="text-gray-700 dark:text-gray-300"><strong class="text-green-700 dark:text-green-400">Rate:</strong> <span id="rate" class="font-mono">N/A</span></p>
              <p class="text-gray-700 dark:text-gray-300"><strong class="text-green-700 dark:text-green-400">Total Dolomite:</strong> <span id="total" class="font-mono">N/A</span></p>
            </div>
            
            <button onclick="generatePDF()" class="w-full mt-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition-all shadow-md hover:shadow-lg text-sm">
              <i class="fas fa-file-pdf mr-2"></i>Generate PDF Report
            </button>
          </div>
        </div>

        <!-- Footer -->
        <footer class="text-center py-6 mt-8 border-t border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-400 text-sm transition-all">
          <p>&copy; 2025 Green Leaves Supply Tracker. All rights reserved.</p>
        </footer>

      </div>
    </main>
  </div>

  <!-- Scripts -->
  <script>
    // Loader fade out
    window.addEventListener('load', () => { 
      setTimeout(() => {
        document.getElementById('loader').classList.add('hidden');
      }, 500);
    });

    // Mobile sidebar toggle
    function toggleSidebar() {
      document.getElementById('sidebar').classList.toggle('open');
      document.getElementById('overlay').classList.toggle('open');
    }

    // Sidebar dropdown toggle
    function toggleDropdown(id) {
      const dropdown = document.getElementById(id);
      dropdown.classList.toggle('hidden');
    }

    // Dark/Light Mode Toggle
    const html = document.documentElement;
    const toggleBtn = document.getElementById('themeToggle');
    const sunIcon = document.getElementById('sunIcon');
    const moonIcon = document.getElementById('moonIcon');

    if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      html.classList.add('dark');
      sunIcon.classList.remove('hidden');
      moonIcon.classList.add('hidden');
    }

    toggleBtn.addEventListener('click', () => {
      html.classList.toggle('dark');
      if (html.classList.contains('dark')) {
        localStorage.theme = 'dark';
        sunIcon.classList.remove('hidden');
        moonIcon.classList.add('hidden');
      } else {
        localStorage.theme = 'light';
        sunIcon.classList.add('hidden');
        moonIcon.classList.remove('hidden');
      }
    });

    // Dolomite Calculator Logic
    document.getElementById('calculator-form').addEventListener('submit', function (e) {
      e.preventDefault();

      try {
        // Get form values
        const ph = parseFloat(document.getElementById('ph').value);
        const landArea = parseFloat(document.getElementById('land_area').value);
        const areaUnit = document.getElementById('area_unit').value;

        // Validate inputs
        if (isNaN(ph) || isNaN(landArea)) {
          alert('Invalid input. Please enter numeric values.');
          return;
        }

        // Convert land area to acres
        let landAreaAcres;
        if (areaUnit === "hectares") {
          landAreaAcres = landArea * 2.47105; // 1 hectare = 2.47105 acres
        } else if (areaUnit === "perches") {
          landAreaAcres = landArea * 0.0252929; // 1 perch = 0.0252929 acres
        } else { // acres
          landAreaAcres = landArea;
        }

        // Determine dolomite rate based on pH
        let dolomiteRate;
        if (ph < 3.9) {
          dolomiteRate = 1000; // kg per acre
        } else if (ph >= 3.9 && ph < 4.2) {
          dolomiteRate = 800;
        } else if (ph >= 4.2 && ph < 4.5) {
          dolomiteRate = 600;
        } else { // pH >= 4.5
          dolomiteRate = 400;
        }

        // Calculate total dolomite
        const totalDolomite = dolomiteRate * landAreaAcres;

        // Display results
        document.getElementById('result-ph').textContent = ph.toFixed(2);
        document.getElementById('result-area').textContent = `${landArea} ${areaUnit}`;
        document.getElementById('rate').textContent = `${dolomiteRate} kg/ac`;
        document.getElementById('total').textContent = `${totalDolomite.toFixed(2)} kg`;
        
        // Show results section
        document.getElementById('results').classList.remove('hidden');

      } catch (error) {
        console.error('Error:', error);
        alert('An error occurred during calculation.');
      }
    });

    // Generate PDF Report
    function generatePDF() {
      const { jsPDF } = window.jspdf;
      const doc = new jsPDF();
      
      // Get values
      const ph = document.getElementById('result-ph').textContent;
      const area = document.getElementById('result-area').textContent;
      const rate = document.getElementById('rate').textContent;
      const total = document.getElementById('total').textContent;
      
      // Add header
      doc.setFillColor(76, 175, 80);
      doc.rect(0, 0, 210, 35, 'F');
      
      doc.setTextColor(255, 255, 255);
      doc.setFontSize(22);
      doc.setFont(undefined, 'bold');
      doc.text('Green Leaves Supply Tracker', 105, 15, { align: 'center' });
      
      doc.setFontSize(16);
      doc.text('Dolomite Application Report', 105, 25, { align: 'center' });
      
      // Reset text color
      doc.setTextColor(0, 0, 0);
      
      // Date
      doc.setFontSize(10);
      doc.setFont(undefined, 'normal');
      const today = new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
      doc.text(`Report Generated: ${today}`, 20, 45);
      
      // Calculation Results Section
      doc.setFontSize(14);
      doc.setFont(undefined, 'bold');
      doc.text('Calculation Results', 20, 60);
      
      doc.setFontSize(11);
      doc.setFont(undefined, 'normal');
      let yPos = 70;
      
      doc.text(`Soil pH Level: ${ph}`, 25, yPos);
      yPos += 8;
      doc.text(`Land Area: ${area}`, 25, yPos);
      yPos += 8;
      doc.text(`Dolomite Rate: ${rate}`, 25, yPos);
      yPos += 8;
      doc.setFont(undefined, 'bold');
      doc.text(`Total Dolomite Required: ${total}`, 25, yPos);
      
      // Guidelines Section
      yPos += 15;
      doc.setFontSize(14);
      doc.setFont(undefined, 'bold');
      doc.text('Application Guidelines', 20, yPos);
      
      yPos += 10;
      doc.setFontSize(10);
      doc.setFont(undefined, 'bold');
      doc.text('1. Pre-Application Preparation:', 20, yPos);
      yPos += 6;
      doc.setFont(undefined, 'normal');
      const prep = [
        '   • Conduct soil testing to confirm pH levels',
        '   • Clear the field of weeds and debris',
        '   • Ensure soil is not waterlogged'
      ];
      prep.forEach(line => {
        doc.text(line, 20, yPos);
        yPos += 5;
      });
      
      yPos += 3;
      doc.setFont(undefined, 'bold');
      doc.text('2. Application Method:', 20, yPos);
      yPos += 6;
      doc.setFont(undefined, 'normal');
      const method = [
        '   • Apply dolomite evenly across the field',
        '   • Use mechanical spreader for uniform distribution',
        '   • Apply during dry weather conditions',
        '   • Best time: 2-3 months before planting'
      ];
      method.forEach(line => {
        doc.text(line, 20, yPos);
        yPos += 5;
      });
      
      yPos += 3;
      doc.setFont(undefined, 'bold');
      doc.text('3. Post-Application:', 20, yPos);
      yPos += 6;
      doc.setFont(undefined, 'normal');
      const post = [
        '   • Incorporate dolomite into soil (6-8 inches deep)',
        '   • Use plowing or tilling equipment',
        '   • Water lightly if possible to activate the lime',
        '   • Wait 2-4 weeks before planting'
      ];
      post.forEach(line => {
        doc.text(line, 20, yPos);
        yPos += 5;
      });
      
      yPos += 3;
      doc.setFont(undefined, 'bold');
      doc.text('4. Safety Precautions:', 20, yPos);
      yPos += 6;
      doc.setFont(undefined, 'normal');
      const safety = [
        '   • Wear protective gear (mask, gloves, goggles)',
        '   • Avoid application on windy days',
        '   • Keep away from water sources during application',
        '   • Store remaining dolomite in dry place'
      ];
      safety.forEach(line => {
        doc.text(line, 20, yPos);
        yPos += 5;
      });
      
      yPos += 3;
      doc.setFont(undefined, 'bold');
      doc.text('5. Monitoring:', 20, yPos);
      yPos += 6;
      doc.setFont(undefined, 'normal');
      const monitor = [
        '   • Re-test soil pH after 3-4 months',
        '   • Monitor crop response and growth',
        '   • Adjust application rates for future seasons',
        '   • Keep records of application dates and amounts'
      ];
      monitor.forEach(line => {
        doc.text(line, 20, yPos);
        yPos += 5;
      });
      
      // Footer
      doc.setFontSize(8);
      doc.setTextColor(128, 128, 128);
      doc.text('Green Leaves Supply Tracker - Dolomite Application Report', 105, 285, { align: 'center' });
      doc.text('For questions, contact your agricultural advisor', 105, 290, { align: 'center' });
      
      // Save PDF
      doc.save(`Dolomite_Report_${new Date().toISOString().split('T')[0]}.pdf`);
    }
  </script>

</body>
</html>