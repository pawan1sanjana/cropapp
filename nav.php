<?php
session_start();
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
<title>Customer Navigation Map</title>

<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config = { darkMode: 'class' };</script>

<!-- Leaflet CSS/JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css"/>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

<style>
body { margin:0; padding:0; }
#map { height:100vh; width:100vw; }
.transition-all { transition: all 0.3s ease-in-out; }

/* Loader */
#loader {
  display:flex; justify-content:center; align-items:center;
  position:fixed; inset:0;
  background:rgba(255,255,255,0.95);
  backdrop-filter:blur(4px);
  z-index:10000;
  transition:opacity 0.6s ease, visibility 0.6s ease;
}
#loader.hidden { opacity:0; visibility:hidden; pointer-events:none; }

.spinner-ring,.spinner-inner,.spinner-dot{border-radius:50%;position:absolute;}
.spinner-ring{width:80px;height:80px;border:6px solid #e8f5e9;border-top:6px solid #4CAF50;animation:spin 1s linear infinite;}
.spinner-inner{width:60px;height:60px;top:10px;left:10px;border:4px solid #e8f5e9;border-right:4px solid #2e7d32;animation:spin 1.5s linear infinite reverse;}
.spinner-dot{width:16px;height:16px;top:32px;left:32px;background:linear-gradient(135deg,#4CAF50,#2e7d32);animation:pulse 1s ease-in-out infinite;}

@keyframes spin{0%{transform:rotate(0deg);}100%{transform:rotate(360deg);}}
@keyframes pulse{0%,100%{transform:scale(1);opacity:1;}50%{transform:scale(1.1);opacity:0.8;}}
</style>
</head>
<body class="dark:bg-gray-900 transition-all">

<!-- Loader -->
<div id="loader">
  <div class="relative">
    <div class="spinner-ring"></div>
    <div class="spinner-inner"></div>
    <div class="spinner-dot"></div>
  </div>
</div>

<!-- Map -->
<div id="map"></div>

<!-- Light/Dark Toggle -->
<button id="theme-toggle" class="fixed top-3 right-3 bg-white dark:bg-gray-800 rounded-full p-3 z-[1000]">
  <svg id="sun-icon" class="w-6 h-6 text-yellow-500 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364 6.364l-1.414-1.414M6.343 6.343L4.93 4.93m0 14.142l1.414-1.414M17.657 6.343l1.414-1.414M12 8a4 4 0 100 8 4 4 0 000-8z" />
  </svg>
  <svg id="moon-icon" class="w-6 h-6 text-gray-200 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3a7 7 0 009.79 9.79z" />
  </svg>
</button>

<!-- Input for Route -->
<div class="fixed top-2 left-1/2 -translate-x-1/2 bg-white dark:bg-gray-800 rounded-lg shadow-lg w-11/12 max-w-md p-3 z-[999] border border-gray-200 dark:border-gray-700">
  <div class="flex flex-col sm:flex-row gap-2">
    <input type="text" id="customer-ids" placeholder="Enter customer IDs (e.g., 1,2,3)"
      class="flex-1 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400"/>
    <button id="set-route" class="bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg px-4 py-2 text-sm sm:text-base transition-colors">Set Route</button>
  </div>
</div>

<!-- Recenter -->
<button id="recenter" class="fixed bottom-32 right-4 bg-white dark:bg-gray-800 rounded-full w-12 h-12 shadow-lg flex items-center justify-center z-[1000]">
  <img src="https://upload.wikimedia.org/wikipedia/commons/3/3e/Location_dot_blue.svg" class="w-6 h-6">
</button>

<!-- Navigate -->
<button id="navigate" class="fixed bottom-16 right-4 bg-green-500 text-white rounded-full w-14 h-14 shadow-lg flex items-center justify-center z-[1000]">
  <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a2/Noun_Project_navigation_icon_2159057.svg/120px-Noun_Project_navigation_icon_2159057.svg.png" class="w-7 h-7">
</button>

<script>
const map = L.map('map').setView([6.9271, 79.8612], 8);
let currentLocationMarker = null;
let routingControl = null;
let userLocation = null;
let allCustomers = [];
const loader = document.getElementById('loader');

// Map Tiles
const lightTiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 });
const darkTiles = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { maxZoom: 19 });

function updateMapTheme() {
  map.eachLayer(layer => map.removeLayer(layer));
  (document.documentElement.classList.contains('dark') ? darkTiles : lightTiles).addTo(map);
  if (currentLocationMarker) currentLocationMarker.addTo(map);
  if (routingControl) routingControl.addTo(map);
}

// Fetch all customers and add markers
async function loadAllCustomers() {
    try {
        const res = await fetch('/api/customers.php');
        allCustomers = await res.json();
        allCustomers.forEach(c => {
            L.marker([c.latitude, c.longitude])
                .addTo(map)
                .bindPopup(`<b>${c.name}</b><br>Tel: ${c.tel}<br>Lat: ${c.latitude}, Lon: ${c.longitude}`);
        });
    } catch(err){ console.error(err); alert('Failed to load customers'); }
    finally { setTimeout(()=>loader.classList.add('hidden'), 800); }
}

// Update user location
function updateCurrentLocation() {
    if (!navigator.geolocation) return alert('Geolocation not supported');
    navigator.geolocation.watchPosition(pos => {
        userLocation = [pos.coords.latitude, pos.coords.longitude];
        if (currentLocationMarker) currentLocationMarker.setLatLng(userLocation);
        else currentLocationMarker = L.marker(userLocation,{title:'Your Location'}).addTo(map).bindPopup('Your Location');
    }, err => console.error(err), { enableHighAccuracy:true });
}

// Recenter
document.getElementById('recenter').addEventListener('click', () => {
    if (currentLocationMarker) map.setView(currentLocationMarker.getLatLng(), 15);
    else alert('Current location not ready');
});

// Dynamic Route
document.getElementById('set-route').addEventListener('click', async () => {
    const input = document.getElementById('customer-ids').value.trim();
    if (!input) return alert('Enter customer IDs');
    const ids = input.split(',').map(id=>parseInt(id.trim(),10));
    if (!userLocation) return alert('Waiting for your location...');
    try {
        const res = await fetch(`/api/customers.php?ids=${ids.join(',')}`);
        const selectedCustomers = await res.json();
        if (selectedCustomers.length !== ids.length) return alert('Some IDs are invalid');

        if (routingControl) map.removeControl(routingControl);
        const waypoints = [userLocation, ...selectedCustomers.map(c => L.latLng(c.latitude,c.longitude))];
        routingControl = L.Routing.control({ waypoints, routeWhileDragging:true, lineOptions:{styles:[{color:'#16a34a', weight:5, opacity:0.8}]} }).addTo(map);

    } catch(err){ console.error(err); alert('Error fetching selected customers'); }
});

// Navigate button
document.getElementById('navigate').addEventListener('click', () => {
    if (!routingControl) return alert('Set the route first');
    alert('Navigation started!');
});

// Theme toggle
const themeToggle = document.getElementById('theme-toggle');
if(localStorage.getItem('theme')==='dark') document.documentElement.classList.add('dark');
updateMapTheme();
themeToggle.addEventListener('click', () => {
    document.documentElement.classList.toggle('dark');
    localStorage.setItem('theme', document.documentElement.classList.contains('dark')?'dark':'light');
    updateMapTheme();
});

// Initialize
loadAllCustomers();
updateCurrentLocation();
</script>
</body>
</html>
