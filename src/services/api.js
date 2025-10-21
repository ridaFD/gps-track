// API Service - Connect to Laravel Backend
import axios from 'axios';

// Base API URL - will be configured via environment variable
const API_BASE_URL = process.env.REACT_APP_API_URL || 'http://localhost:8000/api/v1';

// Create axios instance with default config
const apiClient = axios.create({
  baseURL: API_BASE_URL,
  timeout: 30000,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Request interceptor - add auth token
apiClient.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('auth_token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => Promise.reject(error)
);

// Response interceptor - handle errors
apiClient.interceptors.response.use(
  (response) => response.data,
  (error) => {
    if (error.response?.status === 401) {
      // Unauthorized - redirect to login
      localStorage.removeItem('auth_token');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

// Auth API
export const authAPI = {
  login: (email, password) => 
    apiClient.post('/login', { email, password }),
  
  logout: () => 
    apiClient.post('/logout'),
  
  me: () => 
    apiClient.get('/me'),
  
  register: (data) => 
    apiClient.post('/register', data),
};

// Assets/Devices API
export const assetsAPI = {
  getAll: (params = {}) => 
    apiClient.get('/devices', { params }),
  
  getById: (id) => 
    apiClient.get(`/devices/${id}`),
  
  create: (data) => 
    apiClient.post('/devices', data),
  
  update: (id, data) => 
    apiClient.put(`/devices/${id}`, data),
  
  delete: (id) => 
    apiClient.delete(`/devices/${id}`),
  
  getLastPosition: (deviceId) => 
    apiClient.get(`/positions/last`, { params: { device_id: deviceId } }),
};

// Positions/Telemetry API
export const positionsAPI = {
  getLast: (deviceId) => 
    apiClient.get('/positions/last', { params: { device_id: deviceId } }),
  
  getHistory: (deviceId, from, to) => 
    apiClient.get('/positions', { 
      params: { device_id: deviceId, from, to } 
    }),
  
  getTrips: (deviceId, from, to) => 
    apiClient.get('/trips', { 
      params: { device_id: deviceId, from, to } 
    }),
};

// Geofences API
export const geofencesAPI = {
  getAll: (params = {}) => 
    apiClient.get('/geofences', { params }),
  
  getById: (id) => 
    apiClient.get(`/geofences/${id}`),
  
  create: (data) => 
    apiClient.post('/geofences', data),
  
  update: (id, data) => 
    apiClient.put(`/geofences/${id}`, data),
  
  delete: (id) => 
    apiClient.delete(`/geofences/${id}`),
  
  checkInside: (lat, lng) => 
    apiClient.get('/geofences/check', { params: { lat, lng } }),
};

// Alerts API
export const alertsAPI = {
  getAll: (params = {}) => 
    apiClient.get('/alerts', { params }),
  
  getById: (id) => 
    apiClient.get(`/alerts/${id}`),
  
  markAsRead: (id) => 
    apiClient.patch(`/alerts/${id}/read`),
  
  markAllAsRead: () => 
    apiClient.post('/alerts/read-all'),
  
  delete: (id) => 
    apiClient.delete(`/alerts/${id}`),
};

// Alert Rules API
export const alertRulesAPI = {
  getAll: () => 
    apiClient.get('/alert-rules'),
  
  create: (data) => 
    apiClient.post('/alert-rules', data),
  
  update: (id, data) => 
    apiClient.put(`/alert-rules/${id}`, data),
  
  delete: (id) => 
    apiClient.delete(`/alert-rules/${id}`),
};

// Reports API
export const reportsAPI = {
  generate: (type, params) => 
    apiClient.post('/reports/generate', { type, ...params }),
  
  getStatus: (jobId) => 
    apiClient.get(`/reports/status/${jobId}`),
  
  download: (filename) => 
    apiClient.get(`/reports/download/${filename}`, { responseType: 'blob' }),
  
  delete: (filename) => 
    apiClient.delete(`/reports/delete/${filename}`),
  
  getAll: (params = {}) => 
    apiClient.get('/reports', { params }),
};

// Telemetry API
export const telemetryAPI = {
  getCurrent: (deviceId) => 
    apiClient.get(`/telemetry/current/${deviceId}`),
  
  getHistory: (deviceId, from, to, params) => 
    apiClient.get(`/telemetry/history/${deviceId}`, { 
      params: { from, to, ...params } 
    }),
  
  getDiagnostics: (deviceId) => 
    apiClient.get(`/telemetry/diagnostics/${deviceId}`),
};

// Organizations/Tenants API (if multi-tenant)
export const organizationsAPI = {
  getCurrent: () => 
    apiClient.get('/organizations/current'),
  
  update: (data) => 
    apiClient.put('/organizations/current', data),
  
  getUsers: () => 
    apiClient.get('/organizations/users'),
  
  inviteUser: (email, role) => 
    apiClient.post('/organizations/users/invite', { email, role }),
};

// Webhooks API
export const webhooksAPI = {
  getAll: () => 
    apiClient.get('/webhooks'),
  
  create: (data) => 
    apiClient.post('/webhooks', data),
  
  update: (id, data) => 
    apiClient.put(`/webhooks/${id}`, data),
  
  delete: (id) => 
    apiClient.delete(`/webhooks/${id}`),
  
  test: (id) => 
    apiClient.post(`/webhooks/${id}/test`),
};

// Statistics API
export const statisticsAPI = {
  getDashboard: () => 
    apiClient.get('/statistics/dashboard'),
  
  getFleetStatus: () => 
    apiClient.get('/statistics/fleet-status'),
  
  getActivityChart: (period) => 
    apiClient.get('/statistics/activity', { params: { period } }),
};

export default apiClient;

