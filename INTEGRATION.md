# Frontend-Backend Integration Guide

## 🔌 Connecting React Frontend to Laravel Backend

This guide shows you how to integrate the React GPS tracking frontend with your Laravel backend.

---

## 📋 Prerequisites

1. ✅ React frontend is running (already done)
2. ⬜ Laravel backend setup (follow `BACKEND_SETUP.md`)
3. ⬜ Docker services running (optional but recommended)

---

## 🚀 Quick Integration Steps

### 1. Environment Configuration

Create a `.env.local` file in your React project root:

```env
# API Configuration
REACT_APP_API_URL=http://localhost:8000/api/v1

# WebSocket Configuration
REACT_APP_WS_HOST=localhost
REACT_APP_WS_PORT=6001
REACT_APP_WS_KEY=gps-track-key

# Feature Flags
REACT_APP_ENABLE_WEBSOCKETS=true
```

### 2. Update Dashboard to Use Real Data

Replace mock data in `src/pages/Dashboard.js`:

```javascript
import { useEffect, useState } from 'react';
import { assetsAPI, statisticsAPI } from '../services/api';
import { subscribeToOrganizationDevices } from '../services/websocket';

const Dashboard = () => {
  const [assets, setAssets] = useState([]);
  const [stats, setStats] = useState({});

  useEffect(() => {
    // Fetch initial data
    const fetchData = async () => {
      try {
        const [assetsData, statsData] = await Promise.all([
          assetsAPI.getAll(),
          statisticsAPI.getDashboard()
        ]);
        setAssets(assetsData.data);
        setStats(statsData);
      } catch (error) {
        console.error('Error fetching data:', error);
      }
    };

    fetchData();

    // Subscribe to real-time updates
    const channel = subscribeToOrganizationDevices(1, (data) => {
      setAssets(prev => prev.map(asset => 
        asset.id === data.deviceId 
          ? { ...asset, ...data.position }
          : asset
      ));
    });

    return () => {
      if (channel) channel.unsubscribe();
    };
  }, []);

  // Rest of your component...
};
```

### 3. Update Assets Page

Replace mock data in `src/pages/Assets.js`:

```javascript
import { useEffect, useState } from 'react';
import { assetsAPI } from '../services/api';

const Assets = () => {
  const [assets, setAssets] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    loadAssets();
  }, []);

  const loadAssets = async () => {
    try {
      setLoading(true);
      const response = await assetsAPI.getAll();
      setAssets(response.data);
    } catch (error) {
      console.error('Error loading assets:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleAddAsset = async (formData) => {
    try {
      await assetsAPI.create(formData);
      await loadAssets(); // Reload list
      setShowModal(false);
    } catch (error) {
      console.error('Error creating asset:', error);
    }
  };

  const handleEditAsset = async (id, formData) => {
    try {
      await assetsAPI.update(id, formData);
      await loadAssets();
      setShowModal(false);
    } catch (error) {
      console.error('Error updating asset:', error);
    }
  };

  const handleDeleteAsset = async (id) => {
    if (window.confirm('Are you sure?')) {
      try {
        await assetsAPI.delete(id);
        await loadAssets();
      } catch (error) {
        console.error('Error deleting asset:', error);
      }
    }
  };

  // Rest of your component...
};
```

### 4. Update Geofencing Page

Replace mock data in `src/pages/Geofencing.js`:

```javascript
import { useEffect, useState } from 'react';
import { geofencesAPI } from '../services/api';

const Geofencing = () => {
  const [geofences, setGeofences] = useState([]);

  useEffect(() => {
    loadGeofences();
  }, []);

  const loadGeofences = async () => {
    try {
      const response = await geofencesAPI.getAll();
      setGeofences(response.data);
    } catch (error) {
      console.error('Error loading geofences:', error);
    }
  };

  const handleCreateGeofence = async (formData) => {
    try {
      // Convert React-Leaflet polygon to GeoJSON
      const geoJson = {
        type: 'Polygon',
        coordinates: [[
          [formData.center[1], formData.center[0]],
          // ... convert your polygon coordinates
        ]]
      };

      await geofencesAPI.create({
        name: formData.name,
        area: geoJson,
        color: formData.color,
        alerts_enabled: formData.alerts,
        meta: { description: formData.description }
      });

      await loadGeofences();
      setShowModal(false);
    } catch (error) {
      console.error('Error creating geofence:', error);
    }
  };

  // Rest of your component...
};
```

### 5. Update Alerts Page

Replace mock data in `src/pages/Alerts.js`:

```javascript
import { useEffect, useState } from 'react';
import { alertsAPI } from '../services/api';
import { subscribeToAlerts } from '../services/websocket';

const Alerts = () => {
  const [alerts, setAlerts] = useState([]);

  useEffect(() => {
    loadAlerts();

    // Subscribe to real-time alerts
    const channel = subscribeToAlerts((newAlert) => {
      setAlerts(prev => [newAlert, ...prev]);
      // Show notification
      if (Notification.permission === 'granted') {
        new Notification(newAlert.title, {
          body: newAlert.message,
          icon: '/logo192.png'
        });
      }
    });

    return () => {
      if (channel) channel.unsubscribe();
    };
  }, []);

  const loadAlerts = async () => {
    try {
      const response = await alertsAPI.getAll();
      setAlerts(response.data);
    } catch (error) {
      console.error('Error loading alerts:', error);
    }
  };

  const markAsRead = async (id) => {
    try {
      await alertsAPI.markAsRead(id);
      setAlerts(prev => prev.map(alert =>
        alert.id === id ? { ...alert, read: true } : alert
      ));
    } catch (error) {
      console.error('Error marking alert as read:', error);
    }
  };

  // Rest of your component...
};
```

### 6. Update Telemetry Page

Replace mock data in `src/pages/Telemetry.js`:

```javascript
import { useEffect, useState } from 'react';
import { telemetryAPI } from '../services/api';
import { subscribeToTelemetry } from '../services/websocket';

const Telemetry = () => {
  const [selectedAsset, setSelectedAsset] = useState('Vehicle-001');
  const [liveData, setLiveData] = useState({});
  const [telemetryData, setTelemetryData] = useState([]);

  useEffect(() => {
    if (selectedAsset) {
      loadTelemetry(selectedAsset);

      // Subscribe to real-time telemetry
      const channel = subscribeToTelemetry(selectedAsset, (data) => {
        setLiveData(data);
        setTelemetryData(prev => [...prev.slice(-29), data]); // Keep last 30
      });

      return () => {
        if (channel) channel.unsubscribe();
      };
    }
  }, [selectedAsset]);

  const loadTelemetry = async (deviceId) => {
    try {
      const [current, history] = await Promise.all([
        telemetryAPI.getCurrent(deviceId),
        telemetryAPI.getHistory(deviceId, new Date(Date.now() - 1800000), new Date())
      ]);
      setLiveData(current);
      setTelemetryData(history.data);
    } catch (error) {
      console.error('Error loading telemetry:', error);
    }
  };

  // Rest of your component...
};
```

---

## 🔐 Authentication Setup

### Add Login Page

Create `src/pages/Login.js`:

```javascript
import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { authAPI } from '../services/api';
import { initWebSocket } from '../services/websocket';

const Login = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const navigate = useNavigate();

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const response = await authAPI.login(email, password);
      localStorage.setItem('auth_token', response.token);
      localStorage.setItem('user', JSON.stringify(response.user));
      
      // Initialize WebSocket
      initWebSocket(response.token);
      
      navigate('/dashboard');
    } catch (err) {
      setError('Invalid credentials');
    }
  };

  return (
    <div className="login-page">
      <form onSubmit={handleSubmit}>
        <h1>GPS Track Login</h1>
        {error && <div className="error">{error}</div>}
        <input
          type="email"
          placeholder="Email"
          value={email}
          onChange={(e) => setEmail(e.target.value)}
          required
        />
        <input
          type="password"
          placeholder="Password"
          value={password}
          onChange={(e) => setPassword(e.target.value)}
          required
        />
        <button type="submit">Login</button>
      </form>
    </div>
  );
};

export default Login;
```

### Update App.js with Protected Routes

```javascript
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import Login from './pages/Login';
// ... other imports

const ProtectedRoute = ({ children }) => {
  const token = localStorage.getItem('auth_token');
  return token ? children : <Navigate to="/login" />;
};

function App() {
  return (
    <Router>
      <Routes>
        <Route path="/login" element={<Login />} />
        <Route path="/" element={<ProtectedRoute><MainApp /></ProtectedRoute>} />
      </Routes>
    </Router>
  );
}

const MainApp = () => {
  // Your existing App component code
};
```

---

## 🧪 Testing the Integration

### 1. Start Backend Services

```bash
# Using Docker Compose
docker-compose -f docker-compose.backend.yml up -d

# Or manually
cd backend
php artisan serve
php artisan websockets:serve
php artisan horizon
```

### 2. Start React Frontend

```bash
npm start
```

### 3. Test API Endpoints

```bash
# Login
curl -X POST http://localhost:8000/api/v1/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}'

# Get Devices (with token)
curl http://localhost:8000/api/v1/devices \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### 4. Test WebSocket Connection

Open browser console and check for:
```
WebSocket connection established
Subscribed to channel: device.1
```

---

## 🐛 Troubleshooting

### CORS Issues

Add to Laravel `config/cors.php`:
```php
'paths' => ['api/*', 'broadcasting/auth'],
'allowed_origins' => ['http://localhost:3000'],
'supports_credentials' => true,
```

### WebSocket Not Connecting

1. Check if WebSocket server is running: `php artisan websockets:serve`
2. Verify `.env` has correct `REACT_APP_WS_PORT=6001`
3. Check browser console for connection errors

### API 401 Errors

1. Verify token is stored: `localStorage.getItem('auth_token')`
2. Check token expiry in Laravel
3. Try logging in again

---

## 📊 Data Flow Diagram

```
React App
    │
    ├─→ API Calls (axios) ──→ Laravel API ──→ Database
    │                            │
    │                            ├─→ Queue Jobs
    │                            └─→ Events
    │
    └─→ WebSocket (Echo) ←──── Laravel WebSockets
                                     ↑
                              Broadcast Events
```

---

## 🎯 Next Steps

1. ✅ Set up authentication
2. ✅ Replace all mock data with API calls
3. ✅ Subscribe to WebSocket channels
4. ⬜ Add loading states and error handling
5. ⬜ Implement pagination for large datasets
6. ⬜ Add offline support with service workers
7. ⬜ Implement push notifications
8. ⬜ Add analytics tracking

---

**Ready to integrate?** Start with authentication, then replace one page at a time with real data!

