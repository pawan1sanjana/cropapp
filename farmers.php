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

        <!-- Add/Update Form -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-4 mb-6 border border-gray-200 dark:border-gray-700 transition-all">
          <div class="flex items-center justify-between mb-3">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
              <i class="fas fa-user-plus mr-2 text-green-600 dark:text-green-400"></i>
              <span id="formTitle">Add New User</span>
            </h2>
            <button id="resetBtn" onclick="resetForm()" 
              class="px-3 py-1 text-xs bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-all hidden">
              <i class="fas fa-times mr-1"></i> Cancel
            </button>
          </div>

          <!-- Compact Single-Line Form -->
          <form id="userForm" class="flex flex-wrap items-end gap-3">
            <input type="hidden" id="id">

            <div class="flex flex-col w-full sm:w-1/6">
              <label class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Reg No *</label>
              <input type="text" id="regno" placeholder="Reg No" required
                class="px-2 py-1 rounded-md border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
            </div>

            <div class="flex flex-col w-full sm:w-1/6">
              <label class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Route *</label>
              <select id="route" required
                class="px-2 py-1 rounded-md border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                <option value="" disabled selected>Select route</option>
                <option value="route1">Route 1</option>
                <option value="route2">Route 2</option>
                <option value="route3">Route 3</option>
                <option value="route4">Route 4</option>
              </select>
            </div>

            <div class="flex flex-col w-full sm:w-1/6">
              <label class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Name *</label>
              <input type="text" id="name" placeholder="Name" required
                class="px-2 py-1 rounded-md border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
            </div>

            <div class="flex flex-col w-full sm:w-1/5">
              <label class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Address *</label>
              <input type="text" id="address" placeholder="Address" required
                class="px-2 py-1 rounded-md border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
            </div>

            <div class="flex flex-col w-full sm:w-1/6">
              <label class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Latitude *</label>
              <input type="text" id="latitude" placeholder="7.2345" required
                class="px-2 py-1 rounded-md border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
            </div>

            <div class="flex flex-col w-full sm:w-1/6">
              <label class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Longitude *</label>
              <input type="text" id="longitude" placeholder="79.8612" required
                class="px-2 py-1 rounded-md border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
            </div>

            <div class="flex flex-col w-full sm:w-1/6">
              <label class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Tel *</label>
              <input type="tel" id="tel" placeholder="0771234567" required
                class="px-2 py-1 rounded-md border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
            </div>

            <div class="w-full sm:w-auto">
              <button type="submit"
                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg shadow-sm hover:shadow-md transition-all mt-4 sm:mt-0">
                <i class="fas fa-save mr-1"></i> <span id="submitBtnText">Save</span>
              </button>
            </div>
          </form>
        </div>

        <!-- Users Table -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 transition-all border border-gray-200 dark:border-gray-700">
          <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-100">
            <i class="fas fa-users mr-2 text-green-600 dark:text-green-400"></i>
            All Users
          </h2>
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-600">
              <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                  <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Reg No</th>
                  <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Route</th>
                  <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Name</th>
                  <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Address</th>
                  <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Latitude</th>
                  <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Longitude</th>
                  <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Tel</th>
                  <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody id="userTable" class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                <tr>
                  <td colspan="8" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                    <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                    <p>Loading users...</p>
                  </td>
                </tr>
              </tbody>
            </table>
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

    // CRUD JS
    const tableBody = document.getElementById("userTable");
    const form = document.getElementById("userForm");
    const formTitle = document.getElementById("formTitle");
    const submitBtnText = document.getElementById("submitBtnText");
    const resetBtn = document.getElementById("resetBtn");

    function loadUsers() {
      fetch("crud.php?action=read")
        .then(res => res.json())
        .then(data => {
          tableBody.innerHTML = "";
          if (data.length === 0) {
            tableBody.innerHTML = `
              <tr>
                <td colspan="8" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                  <i class="fas fa-inbox text-4xl mb-2"></i>
                  <p>No users found. Add your first user above!</p>
                </td>
              </tr>
            `;
            return;
          }
          data.forEach((user, index) => {
            const row = document.createElement("tr");
            row.className = "hover:bg-green-50 dark:hover:bg-gray-700 transition-colors";
            row.innerHTML = `
              <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">${user.user_id}</td>
              <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">${user.route || 'N/A'}</td>
              <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100 font-medium">${user.name}</td>
              <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">${user.address || 'N/A'}</td>
              <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">${user.latitude}</td>
              <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">${user.longitude}</td>
              <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">${user.phone}</td>
              <td class="px-4 py-3 text-sm space-x-2">
                <button onclick="editUser(${user.id})" class="px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition-all shadow-sm hover:shadow-md">
                  <i class="fas fa-edit"></i>
                </button>
                <button onclick="deleteUser(${user.id})" class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-all shadow-sm hover:shadow-md">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            `;
            tableBody.appendChild(row);
          });
        })
        .catch(err => {
          tableBody.innerHTML = `
            <tr>
              <td colspan="8" class="px-4 py-8 text-center text-red-500">
                <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                <p>Error loading users. Please try again.</p>
              </td>
            </tr>
          `;
          console.error(err);
        });
    }

    form.addEventListener("submit", e => {
      e.preventDefault();
      const formData = new FormData();
      
      const id = document.getElementById('id').value;
      formData.append("action", id ? "update" : "create");
      if (id) formData.append("id", id);
      
      formData.append("user_id", document.getElementById('regno').value);
      formData.append("route", document.getElementById('route').value);
      formData.append("name", document.getElementById('name').value);
      formData.append("address", document.getElementById('address').value);
      formData.append("latitude", document.getElementById('latitude').value);
      formData.append("longitude", document.getElementById('longitude').value);
      formData.append("phone", document.getElementById('tel').value);

      fetch("crud.php", { method: "POST", body: formData })
        .then(res => res.text())
        .then(msg => {
          alert(msg);
          loadUsers();
          resetForm();
        })
        .catch(err => {
          alert("Error saving user. Please try again.");
          console.error(err);
        });
    });

    function editUser(id) {
      fetch(`crud.php?action=read&id=${id}`)
        .then(res => res.json())
        .then(user => {
          document.getElementById('id').value = user.id;
          document.getElementById('regno').value = user.user_id;
          document.getElementById('route').value = user.route || '';
          document.getElementById('name').value = user.name;
          document.getElementById('address').value = user.address || '';
          document.getElementById('latitude').value = user.latitude;
          document.getElementById('longitude').value = user.longitude;
          document.getElementById('tel').value = user.phone;
          
          formTitle.textContent = "Update User";
          submitBtnText.textContent = "Update";
          resetBtn.classList.remove('hidden');
          
          // Scroll to form
          form.scrollIntoView({ behavior: 'smooth', block: 'start' });
        })
        .catch(err => {
          alert("Error loading user data.");
          console.error(err);
        });
    }

    function deleteUser(id) {
      if (confirm("Are you sure you want to delete this user? This action cannot be undone.")) {
        fetch(`crud.php?action=delete&id=${id}`)
          .then(res => res.text())
          .then(msg => {
            alert(msg);
            loadUsers();
          })
          .catch(err => {
            alert("Error deleting user. Please try again.");
            console.error(err);
          });
      }
    }

    function resetForm() {
      form.reset();
      document.getElementById('id').value = '';
      formTitle.textContent = "Add New User";
      submitBtnText.textContent = "Save";
      resetBtn.classList.add('hidden');
    }

    // Load users on page load
    loadUsers();
  </script>

</body>
</html>