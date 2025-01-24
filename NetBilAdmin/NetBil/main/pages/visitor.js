const RouterOSAPI = require('routeros-client').RouterOSAPI;

const connectToMikrotik = async () => {
    const client = new RouterOSAPI({
        host: 'mikrotik.freeispradius.com:5806',   // Replace with your MikroTik IP
        user: 'YOUR_USERNAME',       // Replace with your username
        password: 'YOUR_PASSWORD',   // Replace with your password
        port: 8728                   // Default port
    });

    try {
        // Connect to the MikroTik Router
        await client.connect();

        // Fetch the registered users in HotSpot
        const users = await client.get('ip/hotspot/user');
        console.log(`Total Registered Users: ${users.length}`);

        // Optionally display user details
        users.forEach(user => {
            console.log(`User: ${user.name}, Status: ${user.status}`);
        });

        // Return the number of users
        return users.length;

    } catch (error) {
        console.error('Error connecting to MikroTik:', error);
    } finally {
        // Always close the connection
        client.close();
    }
};

// Call the function to fetch the number of users
connectToMikrotik();
