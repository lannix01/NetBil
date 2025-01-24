document.addEventListener('DOMContentLoaded', function() {
    // Initialize the device management functionality
    initDeviceManagement();
});

function initDeviceManagement() {
    // Load devices
    loadDevices();
    // Load connected users
    loadConnectedUsers();

    // Handle add device form submission
    const addDeviceForm = document.getElementById('addDeviceForm');
    addDeviceForm.addEventListener('submit', handleAddDevice);
}

function loadDevices() {
    // TODO: Implement API call to fetch devices
    // For now, we'll add sample data
    const devices = [
        { name: 'Main Router', type: 'router', ip: '192.168.1.1', status: 'online' },
        { name: 'AP Floor 1', type: 'access-point', ip: '192.168.1.2', status: 'online' }
    ];

    const tableBody = document.getElementById('deviceTableBody');
    tableBody.innerHTML = devices.map(device => `
        <tr>
            <td>${device.name}</td>
            <td>${device.type}</td>
            <td>${device.ip}</td>
            <td><span class="badge bg-success">${device.status}</span></td>
            <td>
                <button class="btn btn-sm btn-primary" onclick="editDevice('${device.name}')">
                    <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-sm btn-danger" onclick="deleteDevice('${device.name}')">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

function loadConnectedUsers() {
    // TODO: Implement API call to fetch connected users
    // For now, we'll add sample data
    const users = [
        { device: 'Main Router', client: 'User1-Phone', ip: '192.168.1.100', connected: '2024-03-20 10:30', status: 'active' },
        { device: 'AP Floor 1', client: 'User2-Laptop', ip: '192.168.1.101', connected: '2024-03-20 09:15', status: 'active' }
    ];

    const tableBody = document.getElementById('connectedUsersTableBody');
    tableBody.innerHTML = users.map(user => `
        <tr>
            <td>${user.device}</td>
            <td>${user.client}</td>
            <td>${user.ip}</td>
            <td>${user.connected}</td>
            <td><span class="badge bg-success">${user.status}</span></td>
        </tr>
    `).join('');
}

function handleAddDevice(event) {
    event.preventDefault();
    // TODO: Implement device addition logic
    alert('Device added successfully!');
    $('#addDeviceModal').modal('hide');
}

function editDevice(deviceName) {
    // TODO: Implement device editing logic
    alert(`Editing device: ${deviceName}`);
}

function deleteDevice(deviceName) {
    // TODO: Implement device deletion logic
    if (confirm(`Are you sure you want to delete ${deviceName}?`)) {
        alert(`Device ${deviceName} deleted successfully!`);
    }
}
