// WebSocket Service - Real-time updates from Laravel WebSockets
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

let echoInstance = null;

// Initialize Echo (Laravel WebSockets)
export const initWebSocket = (authToken) => {
  if (echoInstance) {
    return echoInstance;
  }

  const wsHost = process.env.REACT_APP_WS_HOST || 'localhost';
  const wsPort = process.env.REACT_APP_WS_PORT || 6001;
  const wsKey = process.env.REACT_APP_WS_KEY || 'your-app-key';

  echoInstance = new Echo({
    broadcaster: 'pusher',
    key: wsKey,
    wsHost: wsHost,
    wsPort: wsPort,
    wssPort: wsPort,
    forceTLS: false,
    encrypted: true,
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
    authEndpoint: `${process.env.REACT_APP_API_URL}/broadcasting/auth`,
    auth: {
      headers: {
        Authorization: `Bearer ${authToken}`,
        Accept: 'application/json',
      },
    },
  });

  return echoInstance;
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

