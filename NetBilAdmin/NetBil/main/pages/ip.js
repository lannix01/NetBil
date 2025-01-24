// ip.js: Fetch data from ip.json or ip.xml and display it

document.addEventListener("DOMContentLoaded", () => {
  const dataContainer = document.createElement("ul");
  dataContainer.classList.add("list-group");
  document.body.appendChild(dataContainer);

  // Function to fetch and display JSON data
  const fetchJSON = async () => {
      try {
          const response = await fetch("./ip.json");
          if (!response.ok) throw new Error("Failed to fetch JSON data");

          const data = await response.json();
          displayData(data);
      } catch (error) {
          console.error("Error fetching JSON:", error);
      }
  };

  // Function to fetch and display XML data
  const fetchXML = async () => {
      try {
          const response = await fetch("./ip.xml");
          if (!response.ok) throw new Error("Failed to fetch XML data");

          const text = await response.text();
          const parser = new DOMParser();
          const xmlDoc = parser.parseFromString(text, "application/xml");
          const items = Array.from(xmlDoc.getElementsByTagName("pool"));

          const data = items.map(item => ({
              ip: item.getElementsByTagName("ip")[0]?.textContent || "N/A",
              dhcp: item.getElementsByTagName("dhcp")[0]?.textContent || "N/A",
          }));

          displayData(data);
      } catch (error) {
          console.error("Error fetching XML:", error);
      }
  };

  // Function to display data
  const displayData = (data) => {
      dataContainer.innerHTML = ""; // Clear previous content

      data.forEach((item, index) => {
          const listItem = document.createElement("li");
          listItem.classList.add("list-group-item");
          listItem.innerHTML = `
              <strong>Pool ${index + 1}:</strong><br>
              <strong>IP:</strong> ${item.ip}<br>
              <strong>DHCP:</strong> ${item.dhcp}
          `;
          dataContainer.appendChild(listItem);
      });
  };

  // Attempt to fetch JSON first, fallback to XML if JSON fails
  fetchJSON().catch(() => fetchXML());
});
