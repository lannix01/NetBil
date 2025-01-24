async function fetchNetworkSpeed() {
  try {
      const response = await fetch("https://api.fast.com/netflix/speedtest");
      const data = await response.json(); // Adjust this based on the response structure

      // Display the results
      document.getElementById("latency").innerText = data.latency || "N/A"; // Adjust keys based on actual data
      document.getElementById("ping").innerText = data.ping || "N/A";       // Adjust keys based on actual data
      document.getElementById("downloadSpeed").innerText = data.downloadSpeed || "N/A"; // Adjust keys based on actual data
      document.getElementById("uploadSpeed").innerText = data.uploadSpeed || "N/A";     // Adjust keys based on actual data
      document.getElementById("server").innerText = data.server || "N/A";               // Adjust keys based on actual data

  } catch (error) {
      console.error("Error fetching network speed data:", error);
      document.getElementById("latency").innerText = "Error fetching data";
      document.getElementById("ping").innerText = "Error fetching data";
      document.getElementById("downloadSpeed").innerText = "Error fetching data";
      document.getElementById("uploadSpeed").innerText = "Error fetching data";
      document.getElementById("server").innerText = "Error fetching data";
  }
}
