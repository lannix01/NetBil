// Generate a random MAC address (for demo purposes)
function generateRandomMAC() {
  const hexDigits = "0123456789ABCDEF";
  let mac = "";
  for (let i = 0; i < 6; i++) {
      mac += hexDigits.charAt(Math.floor(Math.random() * 16));
      mac += hexDigits.charAt(Math.floor(Math.random() * 16));
      if (i !== 5) mac += ":";
  }
  return mac;
}

// Simulate radar scan to "detect" MAC addresses (mock)
function radarScan() {
  const macAddresses = Array.from({ length: 5 }, generateRandomMAC);
  document.getElementById("macRadar").innerText = `Nearby MACs:\n${macAddresses.join("\n")}`;
}

// Run radar scan every 10 seconds
setInterval(radarScan, 10000);
