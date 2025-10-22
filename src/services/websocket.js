// WebSocket Service - Real-time updates from Laravel WebSockets
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

let echoInstance = null;

// Initialize Echo (Pusher)
export const initWebSocket = (authToken) => {
  if (echoInstance) {
    return echoInstance;
  }

  // Get Pusher configuration from environment variables
  const pusherKey = process.env.REACT_APP_PUSHER_KEY;
  const pusherCluster = process.env.REACT_APP_PUSHER_CLUSTER || 'us2';
  const pusherHost = process.env.REACT_APP_PUSHER_HOST || '';
  const pusherPort = process.env.REACT_APP_PUSHER_PORT ? parseInt(process.env.REACT_APP_PUSHER_PORT) : 443;
  const pusherScheme = process.env.REACT_APP_PUSHER_SCHEME || 'https';
  const forceTLS = pusherScheme === 'https';

  // Warn if Pusher key is not configured
  if (!pusherKey) {
    console.warn('⚠️ REACT_APP_PUSHER_KEY not set. Real-time features disabled.');
    console.warn('📋 Create a .env file with your Pusher credentials.');
    console.warn('📖 See ENV_SETUP.md for instructions.');
    return null;
  }

  console.log('🔌 Initializing Pusher connection...');
  console.log(`   Cluster: ${pusherCluster}`);
  console.log(`   Host: ${pusherHost || 'default'}`);
  console.log(`   Port: ${pusherPort}`);
  console.log(`   TLS: ${forceTLS}`);

  const echoConfig = {
    broadcaster: 'pusher',
    key: pusherKey,
    cluster: pusherCluster,
    forceTLS: forceTLS,
    encrypted: true,
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
    authEndpoint: `${process.env.REACT_APP_API_URL || 'http://localhost:8000/api/v1'}/broadcasting/auth`,
    auth: {
      headers: {
        Authorization: `Bearer ${authToken}`,
        Accept: 'application/json',
      },
    },
  };

  // Add custom host/port if specified (for Soketi or local setup)
  if (pusherHost) {
    echoConfig.wsHost = pusherHost;
    echoConfig.wsPort = pusherPort;
    echoConfig.wssPort = pusherPort;
  }

  try {
    echoInstance = new Echo(echoConfig);
    
    // Connection success handler
    echoInstance.connector.pusher.connection.bind('connected', () => {
      console.log('✅ Pusher connected successfully!');
    });

    // Connection error handler
    echoInstance.connector.pusher.connection.bind('error', (err) => {
      console.error('❌ Pusher connection error:', err);
    });

    // Disconnection handler
    echoInstance.connector.pusher.connection.bind('disconnected', () => {
      console.log('🔌 Pusher disconnected');
    });

    return echoInstance;
  } catch (error) {
    console.error('❌ Failed to initialize Pusher:', error);
    return null;
  }
};

// Get existing Echo instance
export const getEcho = () => {
  if (!echoInstance) {
    const token = localStorage.getItem('auth_token');
    return initWebSocket(token);
  }
  return echoInstance;
};

// Disconnect WebSocket
export const disconnectWebSocket = () => {
  if (echoInstance) {
    echoInstance.disconnect();
    echoInstance = null;
  }
};

// Subscribe to device position updates
export const subscribeToDevicePosition = (deviceId, callback) => {
  const echo = getEcho();
  return echo.channel(`device.${deviceId}`)
    .listen('DevicePositionUpdated', (data) => {
      callback(data);
    });
};

// Subscribe to all devices in organization
export const subscribeToOrganizationDevices = (organizationId, callback) => {
  const echo = getEcho();
  return echo.private(`organization.${organizationId}.devices`)
    .listen('DevicePositionUpdated', (data) => {
      callback(data);
    });
};

// Subscribe to alerts
export const subscribeToAlerts = (callback) => {
  const echo = getEcho();
  return echo.private('alerts')
    .listen('AlertCreated', (data) => {
      callback(data);
    });
};

// Subscribe to telemetry updates
export const subscribeToTelemetry = (deviceId, callback) => {
  const echo = getEcho();
  return echo.channel(`telemetry.${deviceId}`)
    .listen('TelemetryUpdated', (data) => {
      callback(data);
    });
};

// Unsubscribe from channel
export const unsubscribe = (channelName) => {
  const echo = getEcho();
  echo.leave(channelName);
};

export default {
  initWebSocket,
  getEcho,
  disconnectWebSocket,
  subscribeToDevicePosition,
  subscribeToOrganizationDevices,
  subscribeToAlerts,
  subscribeToTelemetry,
  unsubscribe,
};

