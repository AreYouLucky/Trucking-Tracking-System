
const http = require('http');
const socketIo = require('socket.io');

// Create an HTTP server
const server = http.createServer();

// Initialize Socket.IO with the HTTP server
const io = socketIo(server, {
    cors: {
        origin: "http://localhost", // Your frontend URL
        methods: ["GET", "POST"]
    }
});

// When a user connects to the WebSocket server
io.on('connection', (socket) => {
    console.log('a user connected');

    // Listen for location updates from clients
    socket.on('send-location', (location) => {
        console.log('Received location:', location);

        // Broadcast to other users (except the sender)
        socket.broadcast.emit('location-updated', location);
    });

    // When the user disconnects
    socket.on('disconnect', () => {
        console.log('user disconnected');
    });
});

// Start the WebSocket server
server.listen(3000, () => {
    console.log('Socket.IO server is running on http://localhost:3000');
});
