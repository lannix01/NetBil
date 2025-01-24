// Function to fetch public IP address of the current device
async function fetchPublicIP() {
  try {
      const response = await fetch('https://api.ipify.org?format=json');
      const data = await response.j *son();
      document.getElementById("publicIP").innerText = `Public IP: ${data.ip}`;
  } catch (error) {
      console.error('Error fetching public IP:', error);
  }
}
document.addEventListener("DOMContentLoaded", function() {
  // Fetch the IP address of the current device
  fetch("https://api.ipify.org?format=json")
      .then(response => response.json())
      .then(data => {
          document.getElementById("ipAddress").innerText = data.ip;
      })
      .catch(error => {
          document.getElementById("ipAddress").innerText = "Error fetching IP";
          console.error("Error fetching IP address:", error);
      });
});


// Function to fetch device details (requires backend support)
async function fetchDeviceStatistics() {
  try {
      const response = await fetch('/api/device-statistics'); // Endpoint should return device and network info
      const data = await response.json();

      document.getElementById("macAddress").innerText = `MAC Address: ${data.mac}`;
      document.getElementById("connectedDevices").innerText = `Connected Devices: ${data.connectedDevices}`;
  } catch (error) {
      console.error('Error fetching device statistics:', error);
  }
}

// Initialize functions
fetchPublicIP();
fetchDeviceStatistics();
