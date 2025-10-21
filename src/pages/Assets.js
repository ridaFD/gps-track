import React, { useState } from 'react';
import { Plus, Search, MapPin, Edit2, Trash2, Eye, Download } from 'lucide-react';
import './Assets.css';

const Assets = () => {
  const [assets, setAssets] = useState([
    {
      id: 1,
      name: 'Vehicle-001',
      type: 'Truck',
      imei: '123456789012345',
      status: 'active',
      location: 'New York, NY',
      lastUpdate: '2 mins ago',
      driver: 'John Smith',
      speed: 65,
      fuel: 78,
      odometer: 45234
    },
    {
      id: 2,
      name: 'Vehicle-002',
      type: 'Van',
      imei: '123456789012346',
      status: 'idle',
      location: 'Manhattan, NY',
      lastUpdate: '5 mins ago',
      driver: 'Jane Doe',
      speed: 0,
      fuel: 45,
      odometer: 32890
    },
    {
      id: 3,
      name: 'Equipment-001',
      type: 'Crane',
      imei: '123456789012347',
      status: 'active',
      location: 'Brooklyn, NY',
      lastUpdate: '1 min ago',
      driver: 'Mike Johnson',
      speed: 12,
      fuel: 92,
      odometer: 12456
    },
    {
      id: 4,
      name: 'Vehicle-003',
      type: 'Car',
      imei: '123456789012348',
      status: 'moving',
      location: 'Queens, NY',
      lastUpdate: '3 mins ago',
      driver: 'Sarah Wilson',
      speed: 45,
      fuel: 60,
      odometer: 67123
    },
    {
      id: 5,
      name: 'Equipment-002',
      type: 'Excavator',
      imei: '123456789012349',
      status: 'inactive',
      location: 'Bronx, NY',
      lastUpdate: '2 hours ago',
      driver: 'Not assigned',
      speed: 0,
      fuel: 25,
      odometer: 8934
    }
  ]);

  const [searchTerm, setSearchTerm] = useState('');
  const [filterStatus, setFilterStatus] = useState('all');
  const [showModal, setShowModal] = useState(false);
  const [editingAsset, setEditingAsset] = useState(null);
  const [formData, setFormData] = useState({
    name: '',
    type: '',
    imei: '',
    driver: ''
  });

  const filteredAssets = assets.filter(asset => {
    const matchesSearch = asset.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                         asset.driver.toLowerCase().includes(searchTerm.toLowerCase()) ||
                         asset.type.toLowerCase().includes(searchTerm.toLowerCase());
    const matchesStatus = filterStatus === 'all' || asset.status === filterStatus;
    return matchesSearch && matchesStatus;
  });

  const handleAddAsset = () => {
    setEditingAsset(null);
    setFormData({ name: '', type: '', imei: '', driver: '' });
    setShowModal(true);
  };

  const handleEditAsset = (asset) => {
    setEditingAsset(asset);
    setFormData({
      name: asset.name,
      type: asset.type,
      imei: asset.imei,
      driver: asset.driver
    });
    setShowModal(true);
  };

  const handleDeleteAsset = (id) => {
    if (window.confirm('Are you sure you want to delete this asset?')) {
      setAssets(assets.filter(asset => asset.id !== id));
    }
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    if (editingAsset) {
      setAssets(assets.map(asset => 
        asset.id === editingAsset.id 
          ? { ...asset, ...formData }
          : asset
      ));
    } else {
      const newAsset = {
        id: assets.length + 1,
        ...formData,
        status: 'idle',
        location: 'Unknown',
        lastUpdate: 'Just now',
        speed: 0,
        fuel: 100,
        odometer: 0
      };
      setAssets([...assets, newAsset]);
    }
    setShowModal(false);
  };

  const statusCount = {
    all: assets.length,
    active: assets.filter(a => a.status === 'active').length,
    moving: assets.filter(a => a.status === 'moving').length,
    idle: assets.filter(a => a.status === 'idle').length,
    inactive: assets.filter(a => a.status === 'inactive').length
  };

  return (
    <div className="assets-page">
      <div className="page-header">
        <div>
          <h1>Asset Management</h1>
          <p className="page-subtitle">Monitor and manage all your GPS-tracked assets</p>
        </div>
        <button className="btn btn-primary" onClick={handleAddAsset}>
          <Plus size={16} />
          Add Asset
        </button>
      </div>

      {/* Status Filter Tabs */}
      <div className="status-tabs">
        {Object.entries(statusCount).map(([status, count]) => (
          <button
            key={status}
            className={`status-tab ${filterStatus === status ? 'active' : ''}`}
            onClick={() => setFilterStatus(status)}
          >
            <span className="tab-label">{status.charAt(0).toUpperCase() + status.slice(1)}</span>
            <span className="tab-count">{count}</span>
          </button>
        ))}
      </div>

      {/* Search and Filter */}
      <div className="card">
        <div className="search-filter-bar">
          <div className="search-box">
            <Search className="search-icon" size={18} />
            <input
              type="text"
              placeholder="Search assets by name, driver, or type..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
            />
          </div>
          <button className="btn btn-secondary">
            <Download size={16} />
            Export CSV
          </button>
        </div>

        {/* Assets Table */}
        <div className="table-container">
          <table>
            <thead>
              <tr>
                <th>Asset Name</th>
                <th>Type</th>
                <th>IMEI</th>
                <th>Status</th>
                <th>Location</th>
                <th>Driver</th>
                <th>Speed</th>
                <th>Fuel</th>
                <th>Odometer</th>
                <th>Last Update</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              {filteredAssets.map((asset) => (
                <tr key={asset.id}>
                  <td>
                    <div className="asset-name-cell">
                      <MapPin size={16} className="asset-icon" />
                      <strong>{asset.name}</strong>
                    </div>
                  </td>
                  <td>{asset.type}</td>
                  <td><code className="imei-code">{asset.imei}</code></td>
                  <td>
                    <span className={`status-badge ${asset.status}`}>
                      {asset.status}
                    </span>
                  </td>
                  <td>{asset.location}</td>
                  <td>{asset.driver}</td>
                  <td>{asset.speed} km/h</td>
                  <td>
                    <div className="fuel-cell">
                      <div className="fuel-bar">
                        <div 
                          className="fuel-fill" 
                          style={{ 
                            width: `${asset.fuel}%`,
                            background: asset.fuel > 50 ? '#10b981' : asset.fuel > 20 ? '#f59e0b' : '#ef4444'
                          }}
                        ></div>
                      </div>
                      <span>{asset.fuel}%</span>
                    </div>
                  </td>
                  <td>{asset.odometer.toLocaleString()} km</td>
                  <td>{asset.lastUpdate}</td>
                  <td>
                    <div className="action-buttons">
                      <button className="action-btn" title="View Details">
                        <Eye size={16} />
                      </button>
                      <button className="action-btn" title="Edit" onClick={() => handleEditAsset(asset)}>
                        <Edit2 size={16} />
                      </button>
                      <button className="action-btn danger" title="Delete" onClick={() => handleDeleteAsset(asset.id)}>
                        <Trash2 size={16} />
                      </button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>

        {filteredAssets.length === 0 && (
          <div className="empty-state">
            <MapPin size={48} className="empty-icon" />
            <h3>No assets found</h3>
            <p>Try adjusting your search or filters</p>
          </div>
        )}
      </div>

      {/* Add/Edit Modal */}
      {showModal && (
        <div className="modal-overlay" onClick={() => setShowModal(false)}>
          <div className="modal" onClick={(e) => e.stopPropagation()}>
            <div className="modal-header">
              <h2 className="modal-title">
                {editingAsset ? 'Edit Asset' : 'Add New Asset'}
              </h2>
              <button className="modal-close" onClick={() => setShowModal(false)}>
                ×
              </button>
            </div>
            
            <form onSubmit={handleSubmit}>
              <div className="form-group">
                <label className="form-label">Asset Name *</label>
                <input
                  type="text"
                  className="form-input"
                  value={formData.name}
                  onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                  required
                />
              </div>

              <div className="form-group">
                <label className="form-label">Asset Type *</label>
                <select
                  className="form-select"
                  value={formData.type}
                  onChange={(e) => setFormData({ ...formData, type: e.target.value })}
                  required
                >
                  <option value="">Select type</option>
                  <option value="Truck">Truck</option>
                  <option value="Van">Van</option>
                  <option value="Car">Car</option>
                  <option value="Crane">Crane</option>
                  <option value="Excavator">Excavator</option>
                  <option value="Other">Other</option>
                </select>
              </div>

              <div className="form-group">
                <label className="form-label">IMEI Number *</label>
                <input
                  type="text"
                  className="form-input"
                  value={formData.imei}
                  onChange={(e) => setFormData({ ...formData, imei: e.target.value })}
                  placeholder="15-digit IMEI number"
                  required
                />
              </div>

              <div className="form-group">
                <label className="form-label">Assigned Driver</label>
                <input
                  type="text"
                  className="form-input"
                  value={formData.driver}
                  onChange={(e) => setFormData({ ...formData, driver: e.target.value })}
                  placeholder="Driver name"
                />
              </div>

              <div className="modal-footer">
                <button type="button" className="btn btn-secondary" onClick={() => setShowModal(false)}>
                  Cancel
                </button>
                <button type="submit" className="btn btn-primary">
                  {editingAsset ? 'Update Asset' : 'Add Asset'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};

export default Assets;

