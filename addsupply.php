<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Supply | Green Leaves Supply Tracker</title>

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="images/icon.png">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = { darkMode: 'class' };
  </script>

  <style>
    .transition-all { transition: all 0.3s ease-in-out; }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    @keyframes pulse { 0%,100% { transform: scale(1); opacity:1; } 50% { transform: scale(1.1); opacity:0.8; } }
    @keyframes slideUp {
      from { opacity: 1; transform: translateY(0); }
      to { opacity: 0; transform: translateY(-20px); }
    }
    
    #loader { display:flex; justify-content:center; align-items:center; position:fixed; inset:0; background:rgba(255,255,255,0.9); backdrop-filter:blur(4px); z-index:50; transition:opacity 0.5s ease, visibility 0.5s ease; }
    #loader.hidden { opacity:0; visibility:hidden; pointer-events:none; }
    .spinner-ring, .spinner-inner, .spinner-dot { border-radius:50%; position:absolute; }
    .spinner-ring { width:80px; height:80px; border:6px solid #e8f5e9; border-top:6px solid #4CAF50; animation:spin 1s linear infinite; }
    .spinner-inner { width:60px; height:60px; top:10px; left:10px; border:4px solid #e8f5e9; border-right:4px solid #2e7d32; animation:spin 1.5s linear infinite reverse; }
    .spinner-dot { width:16px; height:16px; top:32px; left:32px; background:linear-gradient(135deg,#4CAF50,#2e7d32); animation:pulse 1s ease-in-out infinite; }
    
    /* Alert animation */
    .alert { animation: slideIn 0.4s ease-out; }
    @keyframes slideIn {
      from { opacity: 0; transform: translateX(100%); }
      to { opacity: 1; transform: translateX(0); }
    }
    
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


  <!-- Reports with Dropdown -->
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

<script>
function toggleDropdown(id) {
  const dropdown = document.getElementById(id);
  dropdown.classList.toggle('hidden');
}
</script>

      <a href="logout.php" class="block py-4 text-center bg-green-700 dark:bg-green-600 hover:bg-green-600 dark:hover:bg-green-500 transition-all font-semibold">
        <i class="fas fa-sign-out-alt mr-2"></i>
        Logout
      </a>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto">
      <div class="p-4 md:p-6 lg:p-8">

        <!-- Header -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
          <div class="flex items-center gap-3">
            <button onclick="toggleSidebar()" class="md:hidden p-2 rounded-lg bg-green-600 text-white hover:bg-green-700 transition-all">
              <i class="fas fa-bars"></i>
            </button>
            <h1 class="text-2xl md:text-3xl font-semibold text-green-800 dark:text-green-400">
              <i class="fas fa-plus-circle mr-2"></i>Add New Supply
            </h1>
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

        <!-- Alert Container - Fixed position -->
        <div id="alertContainer" class="fixed top-5 right-5 z-50 max-w-md space-y-2"></div>

        <!-- Quick Stats -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-xs text-gray-600 dark:text-gray-400 uppercase font-semibold">Today's Entries</p>
                <p id="todayCount" class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">0</p>
              </div>
              <i class="fas fa-calendar-day text-3xl text-green-600 dark:text-green-400 opacity-50"></i>
            </div>
          </div>

          <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-xs text-gray-600 dark:text-gray-400 uppercase font-semibold">This Week</p>
                <p id="weekCount" class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-1">0</p>
              </div>
              <i class="fas fa-calendar-week text-3xl text-blue-600 dark:text-blue-400 opacity-50"></i>
            </div>
          </div>

          <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-xs text-gray-600 dark:text-gray-400 uppercase font-semibold">This Month</p>
                <p id="monthCount" class="text-2xl font-bold text-purple-600 dark:text-purple-400 mt-1">0</p>
              </div>
              <i class="fas fa-calendar-alt text-3xl text-purple-600 dark:text-purple-400 opacity-50"></i>
            </div>
          </div>
        </div>

        <!-- Supply Entry Form -->
        <div class="max-w-6xl mx-auto">
          <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            
            <!-- Form Header -->
            <div class="bg-gradient-to-r from-green-600 to-green-700 dark:from-green-700 dark:to-green-800 px-6 py-4">
              <h2 class="text-xl font-semibold text-white flex items-center">
                <i class="fas fa-leaf mr-2"></i>
                Supply Information Form
              </h2>
              <p class="text-green-100 text-sm mt-1">Enter the details of the new supply delivery</p>
            </div>

            <!-- Form Body -->
            <form id="supplyForm" class="p-6 space-y-6">
              
              <!-- Farmer Information Section -->
              <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                  <i class="fas fa-user-tie mr-2 text-green-600"></i>
                  Farmer Information
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div class="form-group">
                    <label for="farmer_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                      Farmer ID <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="farmer_id" name="farmer_id" required
                      class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                      placeholder="e.g., F001">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Enter unique farmer identifier</p>
                  </div>

                  <div class="form-group">
                    <label for="farmer_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                      Farmer Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="farmer_name" name="farmer_name" required
                      class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                      placeholder="e.g., John Doe">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Full name of the farmer</p>
                  </div>
                </div>
              </div>

              <!-- Supply Details Section -->
              <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                  <i class="fas fa-box mr-2 text-green-600"></i>
                  Supply Details
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div class="form-group">
                    <label for="supply_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                      Supply Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="supply_date" name="supply_date" required
                      class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Date of supply delivery</p>
                  </div>

                  <div class="form-group">
                    <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                      Quantity (kg) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="quantity" name="quantity" required min="0" step="0.01"
                      class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                      placeholder="e.g., 100.50">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Weight in kilograms</p>
                  </div>

                  <div class="form-group">
                    <label for="level" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                      Quality Level <span class="text-red-500">*</span>
                    </label>
                    <select id="level" name="level" required
                      class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
                      <option value="">Select Quality Level</option>
                      <option value="Level 1">Level 1 - Premium</option>
                      <option value="Level 2">Level 2 - Standard</option>
                      <option value="Level 3">Level 3 - Basic</option>
                    </select>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Quality classification</p>
                  </div>
                </div>
              </div>

              <!-- Form Actions -->
              <div class="flex flex-col sm:flex-row gap-3 pt-4">
                <button type="submit" id="submitBtn"
                  class="flex-1 sm:flex-none px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2">
                  <i class="fas fa-save"></i>
                  <span>Save Supply</span>
                </button>
                
                <button type="reset" id="resetBtn"
                  class="flex-1 sm:flex-none px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2">
                  <i class="fas fa-redo"></i>
                  <span>Reset Form</span>
                </button>

                <a href="dashboard.php"
                  class="flex-1 sm:flex-none px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2">
                  <i class="fas fa-arrow-left"></i>
                  <span>Back to Dashboard</span>
                </a>
              </div>

            </form>
          </div>
        </div>

        <!-- Supply Table -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 mt-6 transition-all border border-gray-200 dark:border-gray-700">
          <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-100">
            <i class="fas fa-table mr-2 text-green-600 dark:text-green-400"></i>
            Recent Supply Entries
          </h2>
          <div class="overflow-x-auto">
            <table id="supplyTable" class="min-w-full text-sm border-collapse">
              <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                  <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Farmer ID</th>
                  <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Name</th>
                  <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Date</th>
                  <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Quantity</th>
                  <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Level</th>
                  <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 dark:divide-gray-700"></tbody>
            </table>
          </div>
        </div>

        <!-- Footer -->
        <footer class="text-center mt-8 py-3 border-t border-gray-300 dark:border-gray-700 text-gray-600 dark:text-gray-400 text-sm">
          &copy; 2025 Green Leaves Supply Tracker. All rights reserved.
        </footer>

      </div>
    </main>
  </div>

  <script>
    // Global variables
    let editingId = null;

    // Loader fade out
    window.addEventListener('load', () => { 
      document.getElementById('loader').classList.add('hidden'); 
    });

    // Sidebar toggle function - made global
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const overlay = document.getElementById('overlay');
      sidebar.classList.toggle('open');
      overlay.classList.toggle('open');
    }

    // Theme toggle
    const html = document.documentElement;
    const themeToggle = document.getElementById('themeToggle');
    const sunIcon = document.getElementById('sunIcon');
    const moonIcon = document.getElementById('moonIcon');

    if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      html.classList.add('dark');
      sunIcon.classList.remove('hidden');
      moonIcon.classList.add('hidden');
    } else {
      html.classList.remove('dark');
      sunIcon.classList.add('hidden');
      moonIcon.classList.remove('hidden');
    }

    themeToggle.addEventListener('click', () => {
      html.classList.toggle('dark');
      const darkMode = html.classList.contains('dark');
      sunIcon.classList.toggle('hidden', !darkMode);
      moonIcon.classList.toggle('hidden', darkMode);
      localStorage.theme = darkMode ? 'dark' : 'light';
    });

    // Single unified alert function
    function showAlert(message, type = 'success') {
      const alertContainer = document.getElementById('alertContainer');
      const alertClass = type === 'success' 
        ? 'bg-green-50 border-green-500 text-green-800 dark:bg-green-900 dark:border-green-700 dark:text-green-200' 
        : 'bg-red-50 border-red-500 text-red-800 dark:bg-red-900 dark:border-red-700 dark:text-red-200';
      const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
      
      const alert = document.createElement('div');
      alert.className = `alert border-l-4 p-4 rounded-lg shadow-lg ${alertClass}`;
      alert.innerHTML = `
        <div class="flex items-center">
          <i class="fas ${iconClass} mr-3 text-xl"></i>
          <div class="flex-1">
            <p class="font-semibold">${type === 'success' ? 'Success!' : 'Error!'}</p>
            <p class="text-sm">${message}</p>
          </div>
          <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-xl hover:opacity-70">
            <i class="fas fa-times"></i>
          </button>
        </div>
      `;
      
      alertContainer.appendChild(alert);
      
      setTimeout(() => {
        alert.style.animation = 'slideUp 0.4s ease-out forwards';
        setTimeout(() => alert.remove(), 400);
      }, 5000);
    }

    // Load all supply records
    async function loadSupplyTable() {
      try {
        const tbody = document.querySelector('#supplyTable tbody');
        tbody.innerHTML = '<tr><td colspan="6" class="px-4 py-8 text-center text-gray-500"><i class="fas fa-spinner fa-spin mr-2"></i>Loading...</td></tr>';
        
        const res = await fetch('supply_crud.php?action=read');
        const data = await res.json();
        
        tbody.innerHTML = '';
        
        if (data.length === 0) {
          tbody.innerHTML = '<tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">No records found</td></tr>';
          return;
        }
        
        data.forEach(row => {
          const tr = document.createElement('tr');
          tr.className = 'hover:bg-gray-50 dark:hover:bg-gray-700';
          tr.innerHTML = `
            <td class="px-4 py-3 text-gray-900 dark:text-gray-100">${row.farmer_id}</td>
            <td class="px-4 py-3 text-gray-900 dark:text-gray-100">${row.farmer_name}</td>
            <td class="px-4 py-3 text-gray-900 dark:text-gray-100">${row.supply_date}</td>
            <td class="px-4 py-3 text-gray-900 dark:text-gray-100">${row.quantity} kg</td>
            <td class="px-4 py-3 text-gray-900 dark:text-gray-100">${row.level}</td>
            <td class="px-4 py-3 text-center space-x-2">
              <button onclick="editSupply(${row.id})" class="px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition-all shadow-sm hover:shadow-md">
                <i class="fas fa-edit"></i>
              </button>
              <button onclick="deleteSupply(${row.id})" class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-all shadow-sm hover:shadow-md">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          `;
          tbody.appendChild(tr);
        });
      } catch (error) {
        console.error('Error loading supply table:', error);
        const tbody = document.querySelector('#supplyTable tbody');
        tbody.innerHTML = '<tr><td colspan="6" class="px-4 py-8 text-center text-red-500">Error loading data</td></tr>';
      }
    }

    // Edit function - made global
    async function editSupply(id) {
      try {
        const res = await fetch(`supply_crud.php?action=read&id=${id}`);
        const data = await res.json();
        
        document.getElementById('farmer_id').value = data.farmer_id;
        document.getElementById('farmer_name').value = data.farmer_name;
        document.getElementById('supply_date').value = data.supply_date;
        document.getElementById('quantity').value = data.quantity;
        document.getElementById('level').value = data.level;
        
        editingId = id;
        
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.innerHTML = '<i class="fas fa-edit"></i><span>Update Supply</span>';
        
        // Scroll to form
        document.getElementById('supplyForm').scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        showAlert('Edit mode activated. Update the form and click "Update Supply".', 'success');
      } catch (error) {
        console.error('Error loading record:', error);
        showAlert('Failed to load record for editing.', 'error');
      }
    }

    // Delete function - made global
    async function deleteSupply(id) {
      if (!confirm('Are you sure you want to delete this record? This action cannot be undone.')) return;
      
      try {
        const res = await fetch(`supply_crud.php?action=delete&id=${id}`);
        const result = await res.json();
        
        showAlert(result.message, result.success ? 'success' : 'error');
        
        if (result.success) {
          loadSupplyTable();
          loadStats();
        }
      } catch (error) {
        console.error('Error deleting record:', error);
        showAlert('Failed to delete record. Please try again.', 'error');
      }
    }

    // Load statistics
    async function loadStats() {
      try {
        const response = await fetch('get_entry_stats.php');
        const stats = await response.json();
        
        if (stats.success) {
          document.getElementById('todayCount').textContent = stats.today || 0;
          document.getElementById('weekCount').textContent = stats.week || 0;
          document.getElementById('monthCount').textContent = stats.month || 0;
        }
      } catch (error) {
        console.error('Error loading stats:', error);
      }
    }

    // Handle form submit (create/update)
    document.getElementById('supplyForm').addEventListener('submit', async function(e) {
      e.preventDefault();
      
      const submitBtn = document.getElementById('submitBtn');
      const originalHTML = submitBtn.innerHTML;
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i><span>Saving...</span>';
      
      try {
        const formData = new FormData(this);
        let action = editingId ? 'update' : 'create';
        
        if (editingId) {
          formData.append('id', editingId);
        }

        const res = await fetch(`supply_crud.php?action=${action}`, { 
          method: 'POST', 
          body: formData 
        });
        
        const result = await res.json();
        
        showAlert(result.message, result.success ? 'success' : 'error');
        
        if (result.success) {
          this.reset();
          document.getElementById('supply_date').valueAsDate = new Date();
          editingId = null;
          submitBtn.innerHTML = '<i class="fas fa-save"></i><span>Save Supply</span>';
          loadSupplyTable();
          loadStats();
        }
      } catch (error) {
        console.error('Error saving record:', error);
        showAlert('An error occurred while saving. Please check your connection and try again.', 'error');
      } finally {
        submitBtn.disabled = false;
        if (!editingId) {
          submitBtn.innerHTML = originalHTML;
        }
      }
    });

    // Reset form confirmation and cancel edit mode
    document.getElementById('resetBtn').addEventListener('click', function(e) {
      if (editingId) {
        editingId = null;
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.innerHTML = '<i class="fas fa-save"></i><span>Save Supply</span>';
        showAlert('Edit mode cancelled.', 'success');
      } else if (!confirm('Are you sure you want to reset the form? All entered data will be lost.')) {
        e.preventDefault();
      }
    });

    // Auto-capitalize farmer ID
    document.getElementById('farmer_id').addEventListener('input', function(e) {
      e.target.value = e.target.value.toUpperCase();
    });

    // Form validation styling
    const inputs = document.querySelectorAll('input[required], select[required]');
    inputs.forEach(input => {
      input.addEventListener('invalid', function() {
        this.classList.add('border-red-500');
        this.classList.remove('border-green-500');
      });
      
      input.addEventListener('input', function() {
        if (this.validity.valid) {
          this.classList.remove('border-red-500');
          this.classList.add('border-green-500');
        } else {
          this.classList.remove('border-green-500');
        }
      });
      
      // Remove validation colors on blur if empty
      input.addEventListener('blur', function() {
        if (this.value === '') {
          this.classList.remove('border-red-500', 'border-green-500');
        }
      });
    });

    // Initialize page
    document.addEventListener('DOMContentLoaded', () => {
      // Set default date to today
      document.getElementById('supply_date').valueAsDate = new Date();
      
      // Load initial data
      loadSupplyTable();
      loadStats();
    });
  </script>

</body>
</html>