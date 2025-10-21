import React, { useState } from 'react';
import { MapContainer, TileLayer, Circle, Polygon, Popup } from 'react-leaflet';
import { Plus, Navigation, Edit2, Trash2, MapPin, AlertCircle } from 'lucide-react';
import 'leaflet/dist/leaflet.css';
import './Geofencing.css';

const Geofencing = () => {
  const [geofences, setGeofences] = useState([
    {
      id: 1,
      name: 'Warehouse Zone',
      type: 'circle',
      center: [40.7580, -73.9855],
      radius: 500,
      color: '#10b981',
      alerts: true,
      description: 'Main warehouse facility',
      assets: 12
    },
    {
      id: 2,
      name: 'Restricted Area',
      type: 'circle',
      center: [40.7489, -73.9680],
      radius: 300,
      color: '#ef4444',
      alerts: true,
      description: 'No entry zone',
      assets: 0
    },
    {
      id: 3,
      name: 'Delivery Zone',
      type: 'polygon',
      positions: [
        [40.7614, -73.9776],
        [40.7654, -73.9776],
        [40.7654, -73.9706],
        [40.7614, -73.9706]
      ],
      color: '#2563eb',
      alerts: true,
      description: 'Active delivery area',
      assets: 5
    },
    {
      id: 4,
      name: 'Service Center',
      type: 'circle',
      center: [40.7128, -74.0060],
      radius: 400,
      color: '#f59e0b',
      alerts: false,
      description: 'Vehicle maintenance center',
      assets: 3
    }
  ]);

  const [showModal, setShowModal] = useState(false);
  const [editingGeofence, setEditingGeofence] = useState(null);
  const [formData, setFormData] = useState({
    name: '',
    type: 'circle',
    description: '',
    color: '#2563eb',
    alerts: true,
    radius: 500
  });

  const handleAddGeofence = () => {
    setEditingGeofence(null);
    setFormData({
      name: '',
      type: 'circle',
      description: '',
      color: '#2563eb',
      alerts: true,
      radius: 500
    });
    setShowModal(true);
  };

  const handleEditGeofence = (geofence) => {
    setEditingGeofence(geofence);
    setFormData({
      name: geofence.name,
      type: geofence.type,
      description: geofence.description,
      color: geofence.color,
      alerts: geofence.alerts,
      radius: geofence.radius || 500
    });
    setShowModal(true);
  };

  const handleDeleteGeofence = (id) => {
    if (window.confirm('Are you sure you want to delete this geofence?')) {
      setGeofences(geofences.filter(g => g.id !== id));
    }
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    if (editingGeofence) {
      setGeofences(geofences.map(g =>
        g.id === editingGeofence.id
          ? { ...g, ...formData }
          : g
      ));
    } else {
      const newGeofence = {
        id: geofences.length + 1,
        ...formData,
        center: [40.7580, -73.9855],
        assets: 0
      };
      setGeofences([...geofences, newGeofence]);
    }
    setShowModal(false);
  };

  return (
    <div className="geofencing-page">
      <div className="page-header">
        <div>
          <h1>Geofencing & POI</h1>
          <p className="page-subtitle">Create and manage geographic boundaries for asset monitoring</p>
        </div>
        <button className="btn btn-primary" onClick={handleAddGeofence}>
          <Plus size={16} />
          Create Geofence
        </button>
      </div>

      {/* Stats */}
      <div className="geofence-stats">
        <div className="stat-item">
          <div className="stat-icon" style={{ background: '#dbeafe', color: '#2563eb' }}>
            <Navigation size={20} />
          </div>
          <div>
            <p className="stat-label">Total Geofences</p>
            <p className="stat-value">{geofences.length}</p>
          </div>
        </div>
        <div className="stat-item">
          <div className="stat-icon" style={{ background: '#d1fae5', color: '#10b981' }}>
            <MapPin size={20} />
          </div>
          <div>
            <p className="stat-label">Active Zones</p>
            <p className="stat-value">{geofences.filter(g => g.alerts).length}</p>
          </div>
        </div>
        <div className="stat-item">
          <div className="stat-icon" style={{ background: '#fee2e2', color: '#ef4444' }}>
            <AlertCircle size={20} />
          </div>
          <div>
            <p className="stat-label">Alerts Today</p>
            <p className="stat-value">47</p>
          </div>
        </div>
      </div>

      <div className="geofencing-grid">
        {/* Map */}
        <div className="card map-card">
          <h3 className="card-title">Geofence Map</h3>
          <MapContainer
            center={[40.7580, -73.9855]}
            zoom={12}
            style={{ height: '600px', borderRadius: '8px' }}
          >
            <TileLayer
              attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
              url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
            />
            {geofences.map((geofence) => (
              <React.Fragment key={geofence.id}>
                {geofence.type === 'circle' ? (
                  <Circle
                    center={geofence.center}
                    radius={geofence.radius}
                    pathOptions={{
                      color: geofence.color,
                      fillColor: geofence.color,
                      fillOpacity: 0.2
                    }}
                  >
                    <Popup>
                      <div className="geofence-popup">
                        <h4>{geofence.name}</h4>
                        <p>{geofence.description}</p>
                        <p><strong>Radius:</strong> {geofence.radius}m</p>
                        <p><strong>Assets Inside:</strong> {geofence.assets}</p>
                        <p><strong>Alerts:</strong> {geofence.alerts ? 'Enabled' : 'Disabled'}</p>
                      </div>
                    </Popup>
                  </Circle>
                ) : (
                  <Polygon
                    positions={geofence.positions}
                    pathOptions={{
                      color: geofence.color,
                      fillColor: geofence.color,
                      fillOpacity: 0.2
                    }}
                  >
                    <Popup>
                      <div className="geofence-popup">
                        <h4>{geofence.name}</h4>
                        <p>{geofence.description}</p>
                        <p><strong>Assets Inside:</strong> {geofence.assets}</p>
                        <p><strong>Alerts:</strong> {geofence.alerts ? 'Enabled' : 'Disabled'}</p>
                      </div>
                    </Popup>
                  </Polygon>
                )}
              </React.Fragment>
            ))}
          </MapContainer>
        </div>

        {/* Geofence List */}
        <div className="card geofence-list-card">
          <h3 className="card-title">Geofence List</h3>
          <div className="geofence-list">
            {geofences.map((geofence) => (
              <div key={geofence.id} className="geofence-item">
                <div
                  className="geofence-color-indicator"
                  style={{ background: geofence.color }}
                ></div>
                <div className="geofence-info">
                  <h4>{geofence.name}</h4>
                  <p className="geofence-description">{geofence.description}</p>
                  <div className="geofence-meta">
                    <span className="meta-item">
                      <MapPin size={14} />
                      {geofence.type === 'circle' ? `${geofence.radius}m radius` : 'Polygon'}
                    </span>
                    <span className="meta-item">
                      {geofence.assets} assets
                    </span>
                    <span className={`meta-badge ${geofence.alerts ? 'active' : 'inactive'}`}>
                      {geofence.alerts ? 'Alerts On' : 'Alerts Off'}
                    </span>
                  </div>
                </div>
                <div className="geofence-actions">
                  <button
                    className="action-btn"
                    onClick={() => handleEditGeofence(geofence)}
                    title="Edit"
                  >
                    <Edit2 size={16} />
                  </button>
                  <button
                    className="action-btn danger"
                    onClick={() => handleDeleteGeofence(geofence.id)}
                    title="Delete"
                  >
                    <Trash2 size={16} />
                  </button>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>

      {/* Modal */}
      {showModal && (
        <div className="modal-overlay" onClick={() => setShowModal(false)}>
          <div className="modal" onClick={(e) => e.stopPropagation()}>
            <div className="modal-header">
              <h2 className="modal-title">
                {editingGeofence ? 'Edit Geofence' : 'Create Geofence'}
              </h2>
              <button className="modal-close" onClick={() => setShowModal(false)}>
                ×
              </button>
            </div>

            <form onSubmit={handleSubmit}>
              <div className="form-group">
                <label className="form-label">Geofence Name *</label>
                <input
                  type="text"
                  className="form-input"
                  value={formData.name}
                  onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                  required
                />
              </div>

              <div className="form-group">
                <label className="form-label">Type *</label>
                <select
                  className="form-select"
                  value={formData.type}
                  onChange={(e) => setFormData({ ...formData, type: e.target.value })}
                  required
                >
                  <option value="circle">Circle</option>
                  <option value="polygon">Polygon</option>
                </select>
              </div>

              {formData.type === 'circle' && (
                <div className="form-group">
                  <label className="form-label">Radius (meters) *</label>
                  <input
                    type="number"
                    className="form-input"
                    value={formData.radius}
                    onChange={(e) => setFormData({ ...formData, radius: parseInt(e.target.value) })}
                    min="50"
                    max="10000"
                    required
                  />
                </div>
              )}

              <div className="form-group">
                <label className="form-label">Description</label>
                <textarea
                  className="form-textarea"
                  value={formData.description}
                  onChange={(e) => setFormData({ ...formData, description: e.target.value })}
                  rows="3"
                ></textarea>
              </div>

              <div className="form-group">
                <label className="form-label">Color</label>
                <div className="color-picker">
                  {['#2563eb', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899'].map(color => (
                    <button
                      key={color}
                      type="button"
                      className={`color-option ${formData.color === color ? 'selected' : ''}`}
                      style={{ background: color }}
                      onClick={() => setFormData({ ...formData, color })}
                    />
                  ))}
                </div>
              </div>

              <div className="form-group">
                <label className="checkbox-label">
                  <input
                    type="checkbox"
                    checked={formData.alerts}
                    onChange={(e) => setFormData({ ...formData, alerts: e.target.checked })}
                  />
                  <span>Enable alerts for this geofence</span>
                </label>
              </div>

              <div className="modal-footer">
                <button type="button" className="btn btn-secondary" onClick={() => setShowModal(false)}>
                  Cancel
                </button>
                <button type="submit" className="btn btn-primary">
                  {editingGeofence ? 'Update Geofence' : 'Create Geofence'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};

export default Geofencing;

