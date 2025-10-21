import React, { useState, useEffect } from 'react';
import { LineChart, Line, AreaChart, Area, XAxis, YAxis, CartesianGrid, Tooltip, Legend, ResponsiveContainer } from 'recharts';
import { Activity, Zap, Thermometer, Gauge, Battery, Clock } from 'lucide-react';
import './Telemetry.css';

const Telemetry = () => {
  const [selectedAsset, setSelectedAsset] = useState('Vehicle-001');
  const [telemetryData, setTelemetryData] = useState([]);
  const [liveData, setLiveData] = useState({
    speed: 0,
    rpm: 0,
    temperature: 0,
    voltage: 0,
    fuel: 0,
    odometer: 0
  });

  const assets = [
    { id: 'Vehicle-001', name: 'Vehicle-001', type: 'Truck' },
    { id: 'Vehicle-002', name: 'Vehicle-002', type: 'Van' },
    { id: 'Vehicle-003', name: 'Vehicle-003', type: 'Car' },
    { id: 'Equipment-001', name: 'Equipment-001', type: 'Crane' }
  ];

  // Generate mock telemetry data
  useEffect(() => {
    const generateData = () => {
      const data = [];
      const now = Date.now();
      for (let i = 30; i >= 0; i--) {
        data.push({
          time: new Date(now - i * 60000).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' }),
          speed: Math.floor(Math.random() * 60) + 20,
          rpm: Math.floor(Math.random() * 3000) + 1000,
          temperature: Math.floor(Math.random() * 20) + 75,
          voltage: (Math.random() * 2 + 12).toFixed(1),
          fuel: Math.max(40, 100 - (30 - i) * 0.5)
        });
      }
      return data;
    };

    setTelemetryData(generateData());

    // Simulate real-time updates
    const interval = setInterval(() => {
      setLiveData({
        speed: Math.floor(Math.random() * 80) + 10,
        rpm: Math.floor(Math.random() * 4000) + 1000,
        temperature: Math.floor(Math.random() * 30) + 70,
        voltage: (Math.random() * 3 + 11).toFixed(1),
        fuel: Math.random() * 100,
        odometer: 45234 + Math.floor(Math.random() * 100)
      });

      setTelemetryData(prev => {
        const newData = [...prev.slice(1)];
        newData.push({
          time: new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' }),
          speed: Math.floor(Math.random() * 60) + 20,
          rpm: Math.floor(Math.random() * 3000) + 1000,
          temperature: Math.floor(Math.random() * 20) + 75,
          voltage: (Math.random() * 2 + 12).toFixed(1),
          fuel: Math.max(40, newData[newData.length - 1]?.fuel - 0.1 || 100)
        });
        return newData;
      });
    }, 3000);

    return () => clearInterval(interval);
  }, [selectedAsset]);

  const telemetryCards = [
    {
      title: 'Speed',
      value: `${liveData.speed} km/h`,
      icon: Gauge,
      color: '#2563eb',
      status: liveData.speed > 80 ? 'danger' : liveData.speed > 60 ? 'warning' : 'normal'
    },
    {
      title: 'RPM',
      value: `${liveData.rpm}`,
      icon: Activity,
      color: '#10b981',
      status: liveData.rpm > 3500 ? 'danger' : liveData.rpm > 2500 ? 'warning' : 'normal'
    },
    {
      title: 'Temperature',
      value: `${liveData.temperature}°C`,
      icon: Thermometer,
      color: '#f59e0b',
      status: liveData.temperature > 95 ? 'danger' : liveData.temperature > 85 ? 'warning' : 'normal'
    },
    {
      title: 'Voltage',
      value: `${liveData.voltage}V`,
      icon: Battery,
      color: '#8b5cf6',
      status: liveData.voltage < 11.5 ? 'danger' : liveData.voltage < 12.0 ? 'warning' : 'normal'
    },
    {
      title: 'Fuel Level',
      value: `${liveData.fuel.toFixed(1)}%`,
      icon: Zap,
      color: '#ef4444',
      status: liveData.fuel < 20 ? 'danger' : liveData.fuel < 40 ? 'warning' : 'normal'
    },
    {
      title: 'Odometer',
      value: `${liveData.odometer.toLocaleString()} km`,
      icon: Clock,
      color: '#06b6d4',
      status: 'normal'
    }
  ];

  return (
    <div className="telemetry-page">
      <div className="page-header">
        <div>
          <h1>Advanced Telemetry</h1>
          <p className="page-subtitle">Real-time monitoring of vehicle parameters and diagnostics</p>
        </div>
        <div className="live-status">
          <span className="pulse-dot"></span>
          Live Monitoring
        </div>
      </div>

      {/* Asset Selector */}
      <div className="card asset-selector-card">
        <label className="selector-label">Select Asset</label>
        <div className="asset-selector">
          {assets.map(asset => (
            <button
              key={asset.id}
              className={`asset-selector-btn ${selectedAsset === asset.id ? 'active' : ''}`}
              onClick={() => setSelectedAsset(asset.id)}
            >
              <Activity size={16} />
              <div>
                <div className="asset-name">{asset.name}</div>
                <div className="asset-type">{asset.type}</div>
              </div>
            </button>
          ))}
        </div>
      </div>

      {/* Live Telemetry Cards */}
      <div className="telemetry-grid">
        {telemetryCards.map((card, index) => (
          <div key={index} className={`telemetry-card ${card.status}`}>
            <div className="telemetry-card-header">
              <div className="telemetry-icon" style={{ background: `${card.color}15`, color: card.color }}>
                <card.icon size={24} />
              </div>
              <span className="telemetry-title">{card.title}</span>
            </div>
            <div className="telemetry-value">{card.value}</div>
            {card.status !== 'normal' && (
              <div className={`telemetry-alert ${card.status}`}>
                {card.status === 'danger' ? 'Critical' : 'Warning'}
              </div>
            )}
          </div>
        ))}
      </div>

      {/* Telemetry Charts */}
      <div className="charts-container">
        <div className="card">
          <h3 className="card-title">Speed & RPM (Last 30 Minutes)</h3>
          <ResponsiveContainer width="100%" height={300}>
            <LineChart data={telemetryData}>
              <CartesianGrid strokeDasharray="3 3" stroke="#f0f0f0" />
              <XAxis dataKey="time" />
              <YAxis yAxisId="left" />
              <YAxis yAxisId="right" orientation="right" />
              <Tooltip />
              <Legend />
              <Line yAxisId="left" type="monotone" dataKey="speed" stroke="#2563eb" strokeWidth={2} name="Speed (km/h)" dot={false} />
              <Line yAxisId="right" type="monotone" dataKey="rpm" stroke="#10b981" strokeWidth={2} name="RPM" dot={false} />
            </LineChart>
          </ResponsiveContainer>
        </div>

        <div className="card">
          <h3 className="card-title">Temperature & Voltage</h3>
          <ResponsiveContainer width="100%" height={300}>
            <LineChart data={telemetryData}>
              <CartesianGrid strokeDasharray="3 3" stroke="#f0f0f0" />
              <XAxis dataKey="time" />
              <YAxis yAxisId="left" />
              <YAxis yAxisId="right" orientation="right" />
              <Tooltip />
              <Legend />
              <Line yAxisId="left" type="monotone" dataKey="temperature" stroke="#f59e0b" strokeWidth={2} name="Temperature (°C)" dot={false} />
              <Line yAxisId="right" type="monotone" dataKey="voltage" stroke="#8b5cf6" strokeWidth={2} name="Voltage (V)" dot={false} />
            </LineChart>
          </ResponsiveContainer>
        </div>

        <div className="card">
          <h3 className="card-title">Fuel Level</h3>
          <ResponsiveContainer width="100%" height={300}>
            <AreaChart data={telemetryData}>
              <CartesianGrid strokeDasharray="3 3" stroke="#f0f0f0" />
              <XAxis dataKey="time" />
              <YAxis />
              <Tooltip />
              <Legend />
              <Area type="monotone" dataKey="fuel" stroke="#ef4444" fill="#ef4444" fillOpacity={0.3} name="Fuel (%)" />
            </AreaChart>
          </ResponsiveContainer>
        </div>
      </div>

      {/* Diagnostic Information */}
      <div className="card">
        <h3 className="card-title">Diagnostic Information</h3>
        <div className="diagnostic-grid">
          <div className="diagnostic-item">
            <span className="diagnostic-label">Engine Status</span>
            <span className="diagnostic-value status-good">Running</span>
          </div>
          <div className="diagnostic-item">
            <span className="diagnostic-label">Transmission</span>
            <span className="diagnostic-value status-good">Normal</span>
          </div>
          <div className="diagnostic-item">
            <span className="diagnostic-label">Brake System</span>
            <span className="diagnostic-value status-good">OK</span>
          </div>
          <div className="diagnostic-item">
            <span className="diagnostic-label">Oil Pressure</span>
            <span className="diagnostic-value status-good">Normal</span>
          </div>
          <div className="diagnostic-item">
            <span className="diagnostic-label">Coolant Level</span>
            <span className="diagnostic-value status-warning">Low</span>
          </div>
          <div className="diagnostic-item">
            <span className="diagnostic-label">Battery Health</span>
            <span className="diagnostic-value status-good">95%</span>
          </div>
          <div className="diagnostic-item">
            <span className="diagnostic-label">Tire Pressure</span>
            <span className="diagnostic-value status-good">Normal</span>
          </div>
          <div className="diagnostic-item">
            <span className="diagnostic-label">DTC Codes</span>
            <span className="diagnostic-value status-good">None</span>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Telemetry;

