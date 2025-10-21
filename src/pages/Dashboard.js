import React, { useState, useEffect } from 'react';
import { MapContainer, TileLayer, Marker, Popup, Circle } from 'react-leaflet';
import { LineChart, Line, AreaChart, Area, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer } from 'recharts';
import { MapPin, Activity, AlertTriangle, TrendingUp, Navigation, Zap } from 'lucide-react';
import 'leaflet/dist/leaflet.css';
import L from 'leaflet';
import './Dashboard.css';

// Fix Leaflet icon issue
delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
  iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
  iconUrl: require('leaflet/dist/images/marker-icon.png'),
  shadowUrl: require('leaflet/dist/images/marker-shadow.png'),
});

const Dashboard = () => {
  const [assets, setAssets] = useState([]);
  const [selectedAsset, setSelectedAsset] = useState(null);

  // Mock data for assets with random positions
  useEffect(() => {
    const mockAssets = [
      {
        id: 1,
        name: 'Vehicle-001',
        type: 'Truck',
        status: 'moving',
        position: [40.7128, -74.0060],
        speed: 65,
        fuel: 78,
        driver: 'John Smith',
        lastUpdate: new Date()
      },
      {
        id: 2,
        name: 'Vehicle-002',
        type: 'Van',
        status: 'idle',
        position: [40.7580, -73.9855],
        speed: 0,
        fuel: 45,
        driver: 'Jane Doe',
        lastUpdate: new Date()
      },
      {
        id: 3,
        name: 'Equipment-001',
        type: 'Crane',
        status: 'active',
        position: [40.7489, -73.9680],
        speed: 12,
        fuel: 92,
        driver: 'Mike Johnson',
        lastUpdate: new Date()
      },
      {
        id: 4,
        name: 'Vehicle-003',
        type: 'Car',
        status: 'moving',
        position: [40.7614, -73.9776],
        speed: 45,
        fuel: 60,
        driver: 'Sarah Wilson',
        lastUpdate: new Date()
      }
    ];

    setAssets(mockAssets);

    // Simulate real-time updates
    const interval = setInterval(() => {
      setAssets(prev => prev.map(asset => ({
        ...asset,
        speed: asset.status === 'moving' ? Math.floor(Math.random() * 80) + 20 : 0,
        fuel: Math.max(0, asset.fuel - Math.random() * 0.5),
        lastUpdate: new Date()
      })));
    }, 3000);

    return () => clearInterval(interval);
  }, []);

  // Mock data for charts
  const activityData = [
    { time: '00:00', active: 12, idle: 3, offline: 1 },
    { time: '04:00', active: 8, idle: 5, offline: 3 },
    { time: '08:00', active: 24, idle: 2, offline: 0 },
    { time: '12:00', active: 28, idle: 4, offline: 1 },
    { time: '16:00', active: 22, idle: 6, offline: 2 },
    { time: '20:00', active: 15, idle: 8, offline: 3 },
  ];

  const performanceData = [
    { month: 'Jan', distance: 12500, trips: 245 },
    { month: 'Feb', distance: 13200, trips: 268 },
    { month: 'Mar', distance: 14800, trips: 298 },
    { month: 'Apr', distance: 13900, trips: 275 },
    { month: 'May', distance: 15600, trips: 312 },
    { month: 'Jun', distance: 16200, trips: 328 },
  ];

  const stats = [
    {
      title: 'Total Assets',
      value: assets.length,
      change: '+12%',
      icon: MapPin,
      color: '#2563eb'
    },
    {
      title: 'Active Now',
      value: assets.filter(a => a.status === 'moving' || a.status === 'active').length,
      change: '+8%',
      icon: Activity,
      color: '#10b981'
    },
    {
      title: 'Alerts Today',
      value: '23',
      change: '-15%',
      icon: AlertTriangle,
      color: '#f59e0b'
    },
    {
      title: 'Total Distance',
      value: '15.6K km',
      change: '+24%',
      icon: TrendingUp,
      color: '#8b5cf6'
    }
  ];

  return (
    <div className="dashboard">
      <div className="dashboard-header">
        <h1>Dashboard</h1>
        <div className="dashboard-actions">
          <button className="btn btn-secondary">
            <Navigation size={16} />
            Export Data
          </button>
          <button className="btn btn-primary">
            <Zap size={16} />
            Live Tracking
          </button>
        </div>
      </div>

      {/* Stats Grid */}
      <div className="stats-grid">
        {stats.map((stat, index) => (
          <div key={index} className="stat-card">
            <div className="stat-icon" style={{ background: `${stat.color}15`, color: stat.color }}>
              <stat.icon size={24} />
            </div>
            <div className="stat-content">
              <p className="stat-title">{stat.title}</p>
              <h3 className="stat-value">{stat.value}</h3>
              <span className={`stat-change ${stat.change.startsWith('+') ? 'positive' : 'negative'}`}>
                {stat.change} from last month
              </span>
            </div>
          </div>
        ))}
      </div>

      {/* Map and Assets */}
      <div className="dashboard-grid">
        <div className="map-container card">
          <div className="card-header">
            <h2 className="card-title">Live Asset Tracking</h2>
            <span className="live-indicator">
              <span className="pulse"></span>
              Live
            </span>
          </div>
          <MapContainer 
            center={[40.7580, -73.9855]} 
            zoom={12} 
            style={{ height: '500px', borderRadius: '8px' }}
          >
            <TileLayer
              attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
              url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
            />
            {assets.map((asset) => (
              <React.Fragment key={asset.id}>
                <Marker 
                  position={asset.position}
                  eventHandlers={{
                    click: () => setSelectedAsset(asset)
                  }}
                >
                  <Popup>
                    <div className="map-popup">
                      <h3>{asset.name}</h3>
                      <p><strong>Type:</strong> {asset.type}</p>
                      <p><strong>Status:</strong> <span className={`status-badge ${asset.status}`}>{asset.status}</span></p>
                      <p><strong>Speed:</strong> {asset.speed} km/h</p>
                      <p><strong>Fuel:</strong> {asset.fuel.toFixed(1)}%</p>
                      <p><strong>Driver:</strong> {asset.driver}</p>
                    </div>
                  </Popup>
                </Marker>
                {asset.status === 'moving' && (
                  <Circle
                    center={asset.position}
                    radius={200}
                    pathOptions={{ 
                      color: '#2563eb', 
                      fillColor: '#2563eb', 
                      fillOpacity: 0.1 
                    }}
                  />
                )}
              </React.Fragment>
            ))}
          </MapContainer>
        </div>

        <div className="assets-sidebar">
          <div className="card">
            <h3 className="card-title">Active Assets</h3>
            <div className="assets-list">
              {assets.map((asset) => (
                <div 
                  key={asset.id} 
                  className={`asset-item ${selectedAsset?.id === asset.id ? 'selected' : ''}`}
                  onClick={() => setSelectedAsset(asset)}
                >
                  <div className={`asset-status-dot ${asset.status}`}></div>
                  <div className="asset-info">
                    <p className="asset-name">{asset.name}</p>
                    <p className="asset-type">{asset.type} • {asset.driver}</p>
                  </div>
                  <div className="asset-speed">
                    {asset.speed > 0 ? `${asset.speed} km/h` : 'Stopped'}
                  </div>
                </div>
              ))}
            </div>
          </div>
        </div>
      </div>

      {/* Charts */}
      <div className="charts-grid">
        <div className="card">
          <h3 className="card-title">Asset Activity (24h)</h3>
          <ResponsiveContainer width="100%" height={300}>
            <AreaChart data={activityData}>
              <CartesianGrid strokeDasharray="3 3" stroke="#f0f0f0" />
              <XAxis dataKey="time" />
              <YAxis />
              <Tooltip />
              <Area type="monotone" dataKey="active" stackId="1" stroke="#10b981" fill="#10b981" fillOpacity={0.6} />
              <Area type="monotone" dataKey="idle" stackId="1" stroke="#f59e0b" fill="#f59e0b" fillOpacity={0.6} />
              <Area type="monotone" dataKey="offline" stackId="1" stroke="#ef4444" fill="#ef4444" fillOpacity={0.6} />
            </AreaChart>
          </ResponsiveContainer>
        </div>

        <div className="card">
          <h3 className="card-title">Performance Overview</h3>
          <ResponsiveContainer width="100%" height={300}>
            <LineChart data={performanceData}>
              <CartesianGrid strokeDasharray="3 3" stroke="#f0f0f0" />
              <XAxis dataKey="month" />
              <YAxis yAxisId="left" />
              <YAxis yAxisId="right" orientation="right" />
              <Tooltip />
              <Line yAxisId="left" type="monotone" dataKey="distance" stroke="#2563eb" strokeWidth={3} dot={{ r: 5 }} />
              <Line yAxisId="right" type="monotone" dataKey="trips" stroke="#8b5cf6" strokeWidth={3} dot={{ r: 5 }} />
            </LineChart>
          </ResponsiveContainer>
        </div>
      </div>
    </div>
  );
};

export default Dashboard;

