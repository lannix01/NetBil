// Mock function to simulate fetching CPU and RAM usage
function getSystemUsage() {
  return {
      cpuUsage: (Math.random() * 100).toFixed(2), // Mocked CPU usage as a percentage
      ramUsage: (Math.random() * 100).toFixed(2), // Mocked RAM usage as a percentage
      networkStatus: navigator.onLine ? 'Online' : 'Offline', // Network connection status
      pcUsage: (Math.random() * 100).toFixed(2) // Mocked overall PC usage
  };
}

// Function to update UI with system usage data
function updateSystemUsage() {
  const systemData = getSystemUsage();

  // Update DOM elements in the "Sales Value" card
  document.getElementById("cpuUsage").innerText = `${systemData.cpuUsage}%`;
  document.getElementById("ramUsage").innerText = `${systemData.ramUsage}%`;
  document.getElementById("networkStatus").innerText = systemData.networkStatus;
  document.getElementById("pcUsage").innerText = `${systemData.pcUsage}%`;
}

// Periodically fetch and update system usage data every 5 seconds
setInterval(updateSystemUsage, 5000);
