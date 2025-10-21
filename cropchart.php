<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

// Get username
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
      <a href="ph.html" class="block py-2 px-4 rounded-lg hover:bg-green-600 dark:hover:bg-gray-600 transition-all">pH Dolamite Cal</a>
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

<!-- Stats Cards - Improved with better visuals and context -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
  <div class="bg-gradient-to-br from-green-50 to-white dark:from-gray-800 dark:to-gray-800 rounded-xl shadow-md p-6 transition-all hover:shadow-xl border border-green-100 dark:border-gray-700">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-gray-600 dark:text-gray-400 text-xs uppercase tracking-wide font-semibold mb-1">Total Farmers</p>
        <p id="total-farmers" class="text-4xl font-bold text-green-600 dark:text-green-400 mt-2">0</p>
        <p class="text-gray-500 dark:text-gray-500 text-xs mt-1">Active suppliers</p>
      </div>
      <div class="bg-green-100 dark:bg-green-900 p-4 rounded-2xl">
        <i class="fas fa-users text-green-600 dark:text-green-400 text-3xl"></i>
      </div>
    </div>
  </div>

  <div class="bg-gradient-to-br from-blue-50 to-white dark:from-gray-800 dark:to-gray-800 rounded-xl shadow-md p-6 transition-all hover:shadow-xl border border-blue-100 dark:border-gray-700">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-gray-600 dark:text-gray-400 text-xs uppercase tracking-wide font-semibold mb-1">Total Supplies</p>
        <p id="total-supplies" class="text-4xl font-bold text-blue-600 dark:text-blue-400 mt-2">0</p>
        <p class="text-gray-500 dark:text-gray-500 text-xs mt-1">Deliveries tracked</p>
      </div>
      <div class="bg-blue-100 dark:bg-blue-900 p-4 rounded-2xl">
        <i class="fas fa-box text-blue-600 dark:text-blue-400 text-3xl"></i>
      </div>
    </div>
  </div>

  <div class="bg-gradient-to-br from-purple-50 to-white dark:from-gray-800 dark:to-gray-800 rounded-xl shadow-md p-6 transition-all hover:shadow-xl border border-purple-100 dark:border-gray-700">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-gray-600 dark:text-gray-400 text-xs uppercase tracking-wide font-semibold mb-1">On-Time Rate</p>
        <p id="on-time-rate" class="text-4xl font-bold text-purple-600 dark:text-purple-400 mt-2">0%</p>
        <p class="text-gray-500 dark:text-gray-500 text-xs mt-1">Delivery performance</p>
      </div>
      <div class="bg-purple-100 dark:bg-purple-900 p-4 rounded-2xl">
        <i class="fas fa-chart-line text-purple-600 dark:text-purple-400 text-3xl"></i>
      </div>
    </div>
  </div>
</div>


<!-- Compact Filters (Single Line Layout) -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6 border border-gray-100 dark:border-gray-700 overflow-x-auto">
  <div class="flex items-center justify-between mb-3 min-w-max">
    <h2 class="text-base font-semibold text-gray-800 dark:text-gray-100">
      <i class="fas fa-filter mr-2 text-green-600"></i>Filters
    </h2>
    <button id="reset-filters" class="text-xs text-gray-500 hover:text-green-600 transition-colors">
      <i class="fas fa-redo mr-1"></i>Reset
    </button>
  </div>

  <div class="flex flex-wrap lg:flex-nowrap items-end gap-4 min-w-max">
    <div>
      <label for="farmer-filter" class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 mb-1">
        Farmer
      </label>
      <select id="farmer-filter" class="w-36 px-2 py-1.5 text-xs border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-1 focus:ring-green-500 focus:border-green-500">
        <option value="">All Farmers</option>
      </select>
    </div>

    <div>
      <label for="date-from" class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 mb-1">
        Start Date
      </label>
      <input type="date" id="date-from" class="w-32 px-2 py-1.5 text-xs border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-1 focus:ring-green-500 focus:border-green-500">
    </div>

    <div>
      <label for="date-to" class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 mb-1">
        End Date
      </label>
      <input type="date" id="date-to" class="w-32 px-2 py-1.5 text-xs border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-1 focus:ring-green-500 focus:border-green-500">
    </div>

    <div>
      <label for="status-filter" class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 mb-1">
        Status
      </label>
      <select id="status-filter" class="w-32 px-2 py-1.5 text-xs border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-1 focus:ring-green-500 focus:border-green-500">
        <option value="">All</option>
        <option value="on-time">On Time</option>
        <option value="early">Early</option>
        <option value="delayed">Delayed</option>
      </select>
    </div>

    <div>
      <label for="level-filter" class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 mb-1">
        Quality
      </label>
      <select id="level-filter" class="w-32 px-2 py-1.5 text-xs border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-1 focus:ring-green-500 focus:border-green-500">
        <option value="">All</option>
        <option value="Level 1">Level 1</option>
        <option value="Level 2">Level 2</option>
        <option value="Level 3">Level 3</option>
      </select>
    </div>

    <div>
      <button id="apply-filters" class="w-32 px-3 py-1.5 text-xs bg-green-600 hover:bg-green-700 text-white font-medium rounded-md shadow transition-all">
        <i class="fas fa-search mr-1"></i>Apply
      </button>
    </div>
  </div>
</div>



        <!-- Compact Data Table -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden mb-4 border border-gray-100 dark:border-gray-700">
  <div class="overflow-x-auto">
    <table class="w-full border-collapse text-xs">
      <thead>
        <tr class="bg-green-600 dark:bg-green-700 text-white">
          <th class="border border-gray-300 dark:border-gray-600 px-2 py-2 text-center font-semibold">Farmer ID</th>
          <th class="border border-gray-300 dark:border-gray-600 px-2 py-2 text-center font-semibold">Farmer Name</th>
          <th class="border border-gray-300 dark:border-gray-600 px-2 py-2 text-center font-semibold">Supply Date</th>
          <th class="border border-gray-300 dark:border-gray-600 px-2 py-2 text-center font-semibold">Quantity (kg)</th>
          <th class="border border-gray-300 dark:border-gray-600 px-2 py-2 text-center font-semibold">Expected Date</th>
          <th class="border border-gray-300 dark:border-gray-600 px-2 py-2 text-center font-semibold">Status</th>
          <th class="border border-gray-300 dark:border-gray-600 px-2 py-2 text-center font-semibold">Days Off</th>
          <th class="border border-gray-300 dark:border-gray-600 px-2 py-2 text-center font-semibold">Quality</th>
        </tr>
      </thead>
      <tbody id="supply-data" class="text-[13px] bg-white dark:bg-gray-800"></tbody>
    </table>
  </div>
</div>

<!-- Compact Pagination -->
<div id="pagination" class="text-center mb-4 flex justify-center gap-1 flex-wrap text-xs"></div>

<!-- Compact Farmer Schedule -->
<div id="farmer-schedule" class="hidden mb-4 p-4 bg-green-50 dark:bg-gray-700 rounded-lg shadow transition text-sm">
  <h2 id="schedule-farmer-name" class="text-lg font-bold text-green-800 dark:text-green-300 mb-2"></h2>
  <div id="supply-pattern" class="mb-2 text-gray-700 dark:text-gray-300 text-[13px]"></div>
  <h3 class="text-sm font-semibold text-green-700 dark:text-green-400 mb-2">Upcoming Supply Dates</h3>
  <ul id="upcoming-dates" class="list-none space-y-1 text-gray-700 dark:text-gray-300 text-[13px]"></ul>
</div>

        <!-- Footer -->
        <footer class="text-center mt-8 py-3 border-t border-gray-300 dark:border-gray-700 text-gray-600 dark:text-gray-400 text-sm">
          &copy; 2025 Green Leaves Supply Tracker. All rights reserved.
        </footer>

      </div>
    </main>
  </div>

  <!-- Main Script -->
  <script>
    let sampleData = [];
    let filteredData = [];
    let currentPage = 1;
    const itemsPerPage = 10;

    // Sidebar toggle function
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const overlay = document.getElementById('overlay');
      sidebar.classList.toggle('open');
      overlay.classList.toggle('open');
    }

    document.addEventListener('DOMContentLoaded', async function() {
      const loader = document.getElementById('loader');
      loader.classList.remove('hidden');

      try {
        const response = await fetch('get_data.php');

        if (!response.ok) {
          throw new Error('Network response was not ok');
        }

        const data = await response.json();

        if (data.error) {
          showErrorMessage(`⚠️ Database Error: ${data.error}`);
          loader.classList.add('hidden');
          return;
        }

        if (!Array.isArray(data) || data.length === 0) {
          showErrorMessage("No supply records found in the database.");
          loader.classList.add('hidden');
          return;
        }

        sampleData = processSupplyData(data);
        filteredData = analyzeSupplySchedule(sampleData);

        populateFarmerFilter(sampleData);
        updateDashboardStats(filteredData);
        displayData(filteredData, currentPage);
        setupPagination(filteredData);

      } catch (error) {
        console.error("Error fetching data:", error);
        showErrorMessage("Unable to connect to the database or fetch data.");
      } finally {
        loader.classList.add('hidden');
      }

      document.getElementById('apply-filters').addEventListener('click', applyFilters);
    });

    function showErrorMessage(message) {
      const tableBody = document.getElementById('supply-data');
      tableBody.innerHTML = `
        <tr>
          <td colspan="8" class="text-center py-6 text-red-600 font-semibold bg-red-50 dark:bg-red-900 dark:text-red-200">
            ${message}
          </td>
        </tr>`;
    }

    function processSupplyData(data) {
      return data
        .filter(i => i.farmer_id && i.farmer_name && i.supply_date)
        .sort((a, b) => a.farmer_id.localeCompare(b.farmer_id) || new Date(a.supply_date) - new Date(b.supply_date));
    }

    function analyzeSupplySchedule(data) {
      const farmers = {};
      const result = [];

      data.forEach(supply => {
        if (!farmers[supply.farmer_id]) farmers[supply.farmer_id] = [];
        farmers[supply.farmer_id].push({ ...supply, supply_date_obj: new Date(supply.supply_date) });
      });

      for (const farmerId in farmers) {
        const supplies = farmers[farmerId].sort((a, b) => a.supply_date_obj - b.supply_date_obj);
        supplies.forEach((supply, index) => {
          let expectedDate = null, daysOff = 0, status = "on-time";
          if (index > 0) {
            const prevDate = supplies[index - 1].supply_date_obj;
            expectedDate = new Date(prevDate);
            expectedDate.setDate(prevDate.getDate() + 7);
            daysOff = Math.round((supply.supply_date_obj - expectedDate) / (1000 * 60 * 60 * 24));
            if (daysOff < 0) status = "early";
            else if (daysOff > 0) status = "delayed";
          }
          result.push({
            ...supply,
            expected_date: expectedDate ? formatDate(expectedDate) : "First Supply",
            days_off: daysOff,
            status
          });
        });
      }
      return result;
    }

    function formatDate(date) {
      return date.toISOString().split("T")[0];
    }

    function displayData(data, page) {
      const tableBody = document.getElementById('supply-data');
      tableBody.innerHTML = '';
      const start = (page - 1) * itemsPerPage;
      const pageData = data.slice(start, start + itemsPerPage);

      if (pageData.length === 0) {
        showErrorMessage("No records match the selected filters.");
        return;
      }

      pageData.forEach(item => {
        const row = document.createElement('tr');
        row.className = 'hover:bg-green-50 dark:hover:bg-gray-700 transition duration-150';
        const statusText = item.status === "on-time" ? "On Schedule" :
                           item.status === "early" ? "Early" : "Delayed";
        const statusClass = item.status === "on-time" ? "bg-green-500" :
                            item.status === "early" ? "bg-blue-600" : "bg-red-600";
        const levelClass = item.level === "Level 1" ? "bg-emerald-600" :
                           item.level === "Level 2" ? "bg-yellow-600" : "bg-orange-600";

        row.innerHTML = `
          <td class="border border-gray-300 dark:border-gray-600 px-4 py-3 text-center text-gray-900 dark:text-gray-100">${item.farmer_id}</td>
          <td class="border border-gray-300 dark:border-gray-600 px-4 py-3 text-center">
            <a href="#" class="farmer-link text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 hover:underline" data-id="${item.farmer_id}">
              ${item.farmer_name}
            </a>
          </td>
          <td class="border border-gray-300 dark:border-gray-600 px-4 py-3 text-center text-gray-900 dark:text-gray-100">${item.supply_date}</td>
          <td class="border border-gray-300 dark:border-gray-600 px-4 py-3 text-center text-gray-900 dark:text-gray-100">${item.quantity ?? '-'}</td>
          <td class="border border-gray-300 dark:border-gray-600 px-4 py-3 text-center text-gray-900 dark:text-gray-100">${item.expected_date}</td>
          <td class="border border-gray-300 dark:border-gray-600 px-4 py-3 text-center">
            <span class="inline-block px-3 py-1 rounded-lg text-white text-xs font-medium ${statusClass}">
              ${statusText}
            </span>
          </td>
          <td class="border border-gray-300 dark:border-gray-600 px-4 py-3 text-center text-gray-900 dark:text-gray-100">${item.expected_date === 'First Supply' ? '-' : item.days_off}</td>
          <td class="border border-gray-300 dark:border-gray-600 px-4 py-3 text-center">
            <span class="inline-block px-3 py-1 rounded-lg text-white text-xs font-medium ${levelClass}">
              ${item.level ?? 'N/A'}
            </span>
          </td>`;
        tableBody.appendChild(row);
      });

      document.querySelectorAll('.farmer-link').forEach(link => {
        link.addEventListener('click', e => {
          e.preventDefault();
          showFarmerSchedule(e.target.dataset.id);
        });
      });
    }

    function setupPagination(data) {
      const pagination = document.getElementById('pagination');
      pagination.innerHTML = '';
      const totalPages = Math.ceil(data.length / itemsPerPage);
      
      if (totalPages <= 1) return;

      for (let i = 1; i <= totalPages; i++) {
        const btn = document.createElement('button');
        btn.className = `px-4 py-2 rounded-lg text-white font-medium transition duration-300 ${
          i === currentPage ? 'bg-green-800 dark:bg-green-600 shadow-lg' : 'bg-green-600 dark:bg-green-700 hover:bg-green-700 dark:hover:bg-green-600'
        }`;
        btn.textContent = i;
        btn.addEventListener('click', () => {
          currentPage = i;
          displayData(filteredData, currentPage);
          setupPagination(filteredData);
          window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        pagination.appendChild(btn);
      }
    }

    function populateFarmerFilter(data) {
      const farmerFilter = document.getElementById('farmer-filter');
      const farmers = [...new Set(data.map(i => i.farmer_id))].sort();
      farmers.forEach(farmerId => {
        const farmerName = data.find(i => i.farmer_id === farmerId).farmer_name;
        const option = document.createElement('option');
        option.value = farmerId;
        option.textContent = `${farmerId} - ${farmerName}`;
        farmerFilter.appendChild(option);
      });
    }

    function applyFilters() {
      const farmerId = document.getElementById('farmer-filter').value;
      const dateFrom = document.getElementById('date-from').value;
      const dateTo = document.getElementById('date-to').value;
      const status = document.getElementById('status-filter').value;
      const levelFilter = document.getElementById('level-filter').value;

      document.getElementById('loader').classList.remove('hidden');
      setTimeout(() => {
        let filtered = analyzeSupplySchedule(sampleData);
        if (farmerId) filtered = filtered.filter(i => i.farmer_id === farmerId);
        if (dateFrom) filtered = filtered.filter(i => i.supply_date >= dateFrom);
        if (dateTo) filtered = filtered.filter(i => i.supply_date <= dateTo);
        if (status) filtered = filtered.filter(i => i.status === status);
        if (levelFilter) filtered = filtered.filter(i => i.level === levelFilter);

        filteredData = filtered;
        currentPage = 1;
        displayData(filteredData, currentPage);
        setupPagination(filteredData);
        updateDashboardStats(filteredData);
        document.getElementById('loader').classList.add('hidden');
      }, 600);
    }

    function updateDashboardStats(data) {
      const uniqueFarmers = [...new Set(data.map(i => i.farmer_id))].length;
      const totalSupplies = data.length;
      const onTimeRate = totalSupplies ? Math.round(data.filter(i => i.status === 'on-time').length / totalSupplies * 100) : 0;
      document.getElementById('total-farmers').textContent = uniqueFarmers;
      document.getElementById('total-supplies').textContent = totalSupplies;
      document.getElementById('on-time-rate').textContent = onTimeRate + '%';
    }

    function showFarmerSchedule(farmerId) {
      const farmerData = sampleData.filter(i => i.farmer_id === farmerId).sort((a, b) => new Date(a.supply_date) - new Date(b.supply_date));
      if (!farmerData.length) return;
      
      const farmerName = farmerData[0].farmer_name;
      document.getElementById('schedule-farmer-name').textContent = `Supply Schedule for ${farmerName}`;
      
      const intervals = [];
      for (let i = 1; i < farmerData.length; i++) {
        const diff = Math.round((new Date(farmerData[i].supply_date) - new Date(farmerData[i - 1].supply_date)) / (1000 * 60 * 60 * 24));
        intervals.push(diff);
      }
      const avgInterval = intervals.length ? (intervals.reduce((a, b) => a + b, 0) / intervals.length).toFixed(1) : 'N/A';
      document.getElementById('supply-pattern').textContent = `Average interval: ${avgInterval} days`;

      const lastSupply = new Date(farmerData[farmerData.length - 1].supply_date);
      const nextDates = [];
      for (let i = 1; i <= 3; i++) {
        const next = new Date(lastSupply);
        next.setDate(next.getDate() + 7 * i);
        nextDates.push(formatDate(next));
      }
      const upcomingList = document.getElementById('upcoming-dates');
      upcomingList.innerHTML = nextDates.map(d => `<li>🌱 ${d}</li>`).join('');
      document.getElementById('farmer-schedule').classList.remove('hidden');
      document.getElementById('farmer-schedule').scrollIntoView({ behavior: 'smooth' });
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
  </script>
</body>
</html>