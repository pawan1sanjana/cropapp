<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.html"); // redirect to login page
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

  <!-- ✅ Favicon -->
  <link rel="icon" type="image/png" href="images/icon.png">

  <!-- ✅ Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = { darkMode: 'class' };
  </script>

  <style>
    .transition-all { transition: all 0.3s ease-in-out; }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    @keyframes pulse { 0%,100% { transform: scale(1); opacity:1; } 50% { transform: scale(1.1); opacity:0.8; } }
    #loader { display:flex; justify-content:center; align-items:center; position:fixed; inset:0; background:rgba(255,255,255,0.9); backdrop-filter:blur(4px); z-index:50; transition:opacity 0.5s ease, visibility 0.5s ease; }
    #loader.hidden { opacity:0; visibility:hidden; }
    .spinner-ring, .spinner-inner, .spinner-dot { border-radius:50%; position:absolute; }
    .spinner-ring { width:80px; height:80px; border:6px solid #e8f5e9; border-top:6px solid #4CAF50; animation:spin 1s linear infinite; }
    .spinner-inner { width:60px; height:60px; top:10px; left:10px; border:4px solid #e8f5e9; border-right:4px solid #2e7d32; animation:spin 1.5s linear infinite reverse; }
    .spinner-dot { width:16px; height:16px; top:32px; left:32px; background:linear-gradient(135deg,#4CAF50,#2e7d32); animation:pulse 1s ease-in-out infinite; }
  </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900 transition-all">

  <!-- Loader -->
  <div id="loader">
    <div class="relative w-20 h-20">
      <div class="spinner-ring"></div>
      <div class="spinner-inner"></div>
      <div class="spinner-dot"></div>
      <div class="absolute bottom-[-35px] left-1/2 transform -translate-x-1/2 text-green-800 dark:text-green-400 font-semibold text-sm">Loading...</div>
    </div>
  </div>

  <div class="flex h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-green-800 dark:bg-gray-800 text-white flex flex-col transition-all">
      <div class="text-center py-6 text-2xl font-bold border-b border-green-600 flex flex-col items-center gap-2">
        <img src="images/icon.png" alt="Company Logo" class="w-16 h-16 object-contain rounded-full shadow-md">
        <span>Green Leaves</span>
      </div>

      <nav class="flex-1 px-2 py-4 space-y-2">
        <a href="dashboard.php" class="block py-1 px-2 rounded hover:bg-green-700 dark:hover:bg-gray-700 flex items-center transition-all"><i class="fas fa-home mr-2"></i>Dashboard</a>
        <a href="cropchart.php" class="block py-1 px-2 rounded hover:bg-green-700 dark:hover:bg-gray-700 flex items-center transition-all"><i class="fas fa-database mr-2"></i>Crop Chart</a>
        <a href="farmers.php" class="block py-1 px-2 rounded hover:bg-green-700 dark:hover:bg-gray-700 flex items-center transition-all"><i class="fas fa-users mr-2"></i>Farmers</a>
        <a href="reports.php" class="block py-1 px-2 rounded hover:bg-green-700 dark:hover:bg-gray-700 flex items-center transition-all"><i class="fas fa-chart-bar mr-2"></i>Reports</a>
        <a href="settings.php" class="block py-1 px-2 rounded hover:bg-green-700 dark:hover:bg-gray-700 flex items-center transition-all"><i class="fas fa-cog mr-2"></i>Settings</a>
      </nav>

      <a href="logout.php" class="block py-3 text-center bg-green-700 dark:bg-green-600 hover:bg-green-600 dark:hover:bg-green-500 transition-all">Logout</a>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6 flex flex-col justify-between">

      <div>
        <!-- Header with Welcome + Dark/Light Toggle -->
        <header class="flex justify-between items-center mb-6">
          <h1 class="text-3xl font-semibold text-green-800 dark:text-green-400">Dashboard Overview</h1>
          <div class="flex items-center gap-4">
            <div class="bg-white dark:bg-gray-700 px-4 py-2 rounded shadow transition-all">
              <span class="text-gray-700 dark:text-gray-100">
                Welcome, <?php echo htmlspecialchars($username); ?>
              </span>
            </div>
            <button id="themeToggle" class="p-2 rounded-full bg-gray-200 dark:bg-gray-700 hover:scale-110 transition-all">
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

        <!-- Dashboard Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
          <div class="bg-white dark:bg-gray-700 p-4 rounded-2xl shadow hover:shadow-lg transition-all">
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Total Farmers</h2>
            <p class="text-2xl font-bold text-green-800 dark:text-green-400 mt-2">120</p>
          </div>
          <div class="bg-white dark:bg-gray-700 p-4 rounded-2xl shadow hover:shadow-lg transition-all">
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Today's Supply</h2>
            <p class="text-2xl font-bold text-green-800 dark:text-green-400 mt-2">350 kg</p>
          </div>
          <div class="bg-white dark:bg-gray-700 p-4 rounded-2xl shadow hover:shadow-lg transition-all">
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Pending Reports</h2>
            <p class="text-2xl font-bold text-green-800 dark:text-green-400 mt-2">5</p>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <footer class="text-center py-2 border-t border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-400 text-sm transition-all">
        &copy; 2025 Green Leaves Supply Tracker. All rights reserved.
      </footer>

    </main>
  </div>

  <!-- 🌗 Scripts -->
  <script>
    // Loader fade out
    window.addEventListener('load', () => { document.getElementById('loader').classList.add('hidden'); });

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
  </script>

</body>
</html>
