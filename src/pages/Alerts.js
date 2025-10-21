import React, { useState } from 'react';
import { Bell, AlertTriangle, AlertCircle, Info, CheckCircle, Filter, Search } from 'lucide-react';
import { format } from 'date-fns';
import './Alerts.css';

const Alerts = () => {
  const [alerts, setAlerts] = useState([
    {
      id: 1,
      type: 'critical',
      title: 'Unauthorized Movement',
      message: 'Vehicle-001 moved outside designated area',
      asset: 'Vehicle-001',
      timestamp: new Date(Date.now() - 300000),
      read: false,
      location: 'New York, NY'
    },
    {
      id: 2,
      type: 'warning',
      title: 'Low Fuel Alert',
      message: 'Vehicle-002 fuel level below 20%',
      asset: 'Vehicle-002',
      timestamp: new Date(Date.now() - 900000),
      read: false,
      location: 'Manhattan, NY'
    },
    {
      id: 3,
      type: 'info',
      title: 'Maintenance Due',
      message: 'Equipment-001 requires scheduled maintenance',
      asset: 'Equipment-001',
      timestamp: new Date(Date.now() - 1800000),
      read: true,
      location: 'Brooklyn, NY'
    },
    {
      id: 4,
      type: 'critical',
      title: 'Speeding Violation',
      message: 'Vehicle-003 exceeded speed limit (95 km/h in 60 km/h zone)',
      asset: 'Vehicle-003',
      timestamp: new Date(Date.now() - 3600000),
      read: true,
      location: 'Queens, NY'
    },
    {
      id: 5,
      type: 'warning',
      title: 'Geofence Exit',
      message: 'Vehicle-001 exited Warehouse Zone',
      asset: 'Vehicle-001',
      timestamp: new Date(Date.now() - 5400000),
      read: true,
      location: 'New York, NY'
    },
    {
      id: 6,
      type: 'success',
      title: 'Delivery Completed',
      message: 'Vehicle-002 completed delivery at POI-045',
      asset: 'Vehicle-002',
      timestamp: new Date(Date.now() - 7200000),
      read: true,
      location: 'Manhattan, NY'
    },
    {
      id: 7,
      type: 'warning',
      title: 'Extended Idle Time',
      message: 'Vehicle-003 has been idle for over 2 hours',
      asset: 'Vehicle-003',
      timestamp: new Date(Date.now() - 10800000),
      read: true,
      location: 'Bronx, NY'
    },
    {
      id: 8,
      type: 'info',
      title: 'Route Deviation',
      message: 'Vehicle-001 deviated from planned route',
      asset: 'Vehicle-001',
      timestamp: new Date(Date.now() - 14400000),
      read: true,
      location: 'New York, NY'
    }
  ]);

  const [filterType, setFilterType] = useState('all');
  const [searchTerm, setSearchTerm] = useState('');
  const [showUnreadOnly, setShowUnreadOnly] = useState(false);

  const getAlertIcon = (type) => {
    switch (type) {
      case 'critical':
        return <AlertTriangle size={20} />;
      case 'warning':
        return <AlertCircle size={20} />;
      case 'success':
        return <CheckCircle size={20} />;
      default:
        return <Info size={20} />;
    }
  };

  const getAlertColor = (type) => {
    switch (type) {
      case 'critical':
        return '#ef4444';
      case 'warning':
        return '#f59e0b';
      case 'success':
        return '#10b981';
      default:
        return '#2563eb';
    }
  };

  const markAsRead = (id) => {
    setAlerts(alerts.map(alert =>
      alert.id === id ? { ...alert, read: true } : alert
    ));
  };

  const markAllAsRead = () => {
    setAlerts(alerts.map(alert => ({ ...alert, read: true })));
  };

  const deleteAlert = (id) => {
    setAlerts(alerts.filter(alert => alert.id !== id));
  };

  const filteredAlerts = alerts.filter(alert => {
    const matchesType = filterType === 'all' || alert.type === filterType;
    const matchesSearch = alert.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
                         alert.message.toLowerCase().includes(searchTerm.toLowerCase()) ||
                         alert.asset.toLowerCase().includes(searchTerm.toLowerCase());
    const matchesUnread = !showUnreadOnly || !alert.read;
    return matchesType && matchesSearch && matchesUnread;
  });

  const alertCounts = {
    all: alerts.length,
    critical: alerts.filter(a => a.type === 'critical').length,
    warning: alerts.filter(a => a.type === 'warning').length,
    info: alerts.filter(a => a.type === 'info').length,
    success: alerts.filter(a => a.type === 'success').length
  };

  const unreadCount = alerts.filter(a => !a.read).length;

  return (
    <div className="alerts-page">
      <div className="page-header">
        <div>
          <h1>Alerts & Notifications</h1>
          <p className="page-subtitle">Monitor and manage system alerts and notifications</p>
        </div>
        <div className="header-actions">
          {unreadCount > 0 && (
            <button className="btn btn-secondary" onClick={markAllAsRead}>
              <CheckCircle size={16} />
              Mark All Read
            </button>
          )}
        </div>
      </div>

      {/* Alert Stats */}
      <div className="alert-stats">
        <div className="stat-card">
          <div className="stat-icon" style={{ background: '#fee2e2', color: '#ef4444' }}>
            <AlertTriangle size={24} />
          </div>
          <div>
            <p className="stat-label">Critical Alerts</p>
            <p className="stat-value">{alertCounts.critical}</p>
          </div>
        </div>
        <div className="stat-card">
          <div className="stat-icon" style={{ background: '#fef3c7', color: '#f59e0b' }}>
            <AlertCircle size={24} />
          </div>
          <div>
            <p className="stat-label">Warnings</p>
            <p className="stat-value">{alertCounts.warning}</p>
          </div>
        </div>
        <div className="stat-card">
          <div className="stat-icon" style={{ background: '#dbeafe', color: '#2563eb' }}>
            <Info size={24} />
          </div>
          <div>
            <p className="stat-label">Information</p>
            <p className="stat-value">{alertCounts.info}</p>
          </div>
        </div>
        <div className="stat-card">
          <div className="stat-icon" style={{ background: '#e0e7ff', color: '#6366f1' }}>
            <Bell size={24} />
          </div>
          <div>
            <p className="stat-label">Unread</p>
            <p className="stat-value">{unreadCount}</p>
          </div>
        </div>
      </div>

      <div className="card">
        {/* Filter Bar */}
        <div className="alert-filter-bar">
          <div className="filter-tabs">
            {Object.entries(alertCounts).map(([type, count]) => (
              <button
                key={type}
                className={`filter-tab ${filterType === type ? 'active' : ''}`}
                onClick={() => setFilterType(type)}
              >
                {type.charAt(0).toUpperCase() + type.slice(1)}
                <span className="tab-badge">{count}</span>
              </button>
            ))}
          </div>

          <div className="filter-controls">
            <div className="search-box">
              <Search size={16} />
              <input
                type="text"
                placeholder="Search alerts..."
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
              />
            </div>
            <label className="checkbox-filter">
              <input
                type="checkbox"
                checked={showUnreadOnly}
                onChange={(e) => setShowUnreadOnly(e.target.checked)}
              />
              <span>Unread only</span>
            </label>
          </div>
        </div>

        {/* Alerts List */}
        <div className="alerts-list">
          {filteredAlerts.length > 0 ? (
            filteredAlerts.map((alert) => (
              <div
                key={alert.id}
                className={`alert-item ${alert.type} ${!alert.read ? 'unread' : ''}`}
              >
                <div
                  className="alert-icon"
                  style={{ background: `${getAlertColor(alert.type)}15`, color: getAlertColor(alert.type) }}
                >
                  {getAlertIcon(alert.type)}
                </div>
                <div className="alert-content">
                  <div className="alert-header">
                    <h4 className="alert-title">{alert.title}</h4>
                    <span className="alert-time">
                      {format(alert.timestamp, 'MMM dd, HH:mm')}
                    </span>
                  </div>
                  <p className="alert-message">{alert.message}</p>
                  <div className="alert-meta">
                    <span className="meta-tag">
                      <strong>Asset:</strong> {alert.asset}
                    </span>
                    <span className="meta-tag">
                      <strong>Location:</strong> {alert.location}
                    </span>
                  </div>
                </div>
                <div className="alert-actions">
                  {!alert.read && (
                    <button
                      className="action-btn"
                      onClick={() => markAsRead(alert.id)}
                      title="Mark as read"
                    >
                      <CheckCircle size={16} />
                    </button>
                  )}
                  <button
                    className="action-btn danger"
                    onClick={() => deleteAlert(alert.id)}
                    title="Delete"
                  >
                    ×
                  </button>
                </div>
              </div>
            ))
          ) : (
            <div className="empty-state">
              <Bell size={48} className="empty-icon" />
              <h3>No alerts found</h3>
              <p>Try adjusting your filters</p>
            </div>
          )}
        </div>
      </div>
    </div>
  );
};

export default Alerts;

