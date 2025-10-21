import React, { useState } from 'react';
import { BarChart, Bar, LineChart, Line, PieChart, Pie, Cell, XAxis, YAxis, CartesianGrid, Tooltip, Legend, ResponsiveContainer } from 'recharts';
import { FileText, Download, Calendar, Filter, TrendingUp, TrendingDown, Activity } from 'lucide-react';
import './Reports.css';

const Reports = () => {
  const [dateRange, setDateRange] = useState('last7days');
  const [selectedReport, setSelectedReport] = useState('overview');

  // Mock data for various reports
  const distanceData = [
    { date: 'Mon', distance: 245, trips: 12 },
    { date: 'Tue', distance: 312, trips: 15 },
    { date: 'Wed', distance: 289, trips: 14 },
    { date: 'Thu', distance: 401, trips: 18 },
    { date: 'Fri', distance: 378, trips: 17 },
    { date: 'Sat', distance: 156, trips: 8 },
    { date: 'Sun', distance: 198, trips: 10 }
  ];

  const fuelData = [
    { asset: 'Vehicle-001', consumption: 245, cost: 342 },
    { asset: 'Vehicle-002', consumption: 189, cost: 265 },
    { asset: 'Vehicle-003', consumption: 312, cost: 437 },
    { asset: 'Equipment-001', consumption: 428, cost: 599 },
    { asset: 'Equipment-002', consumption: 167, cost: 234 }
  ];

  const assetUtilization = [
    { name: 'Active', value: 65, color: '#10b981' },
    { name: 'Idle', value: 25, color: '#f59e0b' },
    { name: 'Maintenance', value: 7, color: '#ef4444' },
    { name: 'Offline', value: 3, color: '#6b7280' }
  ];

  const driverPerformance = [
    { driver: 'John Smith', trips: 45, distance: 1234, violations: 2, rating: 4.5 },
    { driver: 'Jane Doe', trips: 52, distance: 1456, violations: 1, rating: 4.8 },
    { driver: 'Mike Johnson', trips: 38, distance: 987, violations: 5, rating: 3.9 },
    { driver: 'Sarah Wilson', trips: 41, distance: 1123, violations: 0, rating: 5.0 },
    { driver: 'Tom Brown', trips: 36, distance: 945, violations: 3, rating: 4.2 }
  ];

  const reportTypes = [
    { id: 'overview', name: 'Overview', icon: Activity },
    { id: 'distance', name: 'Distance & Trips', icon: TrendingUp },
    { id: 'fuel', name: 'Fuel Consumption', icon: Activity },
    { id: 'driver', name: 'Driver Performance', icon: FileText }
  ];

  const summaryStats = [
    { label: 'Total Distance', value: '2,234 km', change: '+12.5%', trend: 'up' },
    { label: 'Total Trips', value: '94', change: '+8.3%', trend: 'up' },
    { label: 'Fuel Cost', value: '$1,877', change: '-5.2%', trend: 'down' },
    { label: 'Avg. Speed', value: '52 km/h', change: '+2.1%', trend: 'up' }
  ];

  return (
    <div className="reports-page">
      <div className="page-header">
        <div>
          <h1>Reports & Analytics</h1>
          <p className="page-subtitle">Generate comprehensive reports and analyze fleet performance</p>
        </div>
        <button className="btn btn-primary">
          <Download size={16} />
          Export Report
        </button>
      </div>

      {/* Controls */}
      <div className="report-controls">
        <div className="control-group">
          <label>Report Type</label>
          <div className="report-type-tabs">
            {reportTypes.map(type => (
              <button
                key={type.id}
                className={`type-tab ${selectedReport === type.id ? 'active' : ''}`}
                onClick={() => setSelectedReport(type.id)}
              >
                <type.icon size={16} />
                {type.name}
              </button>
            ))}
          </div>
        </div>

        <div className="control-group">
          <label>Date Range</label>
          <select
            className="form-select"
            value={dateRange}
            onChange={(e) => setDateRange(e.target.value)}
          >
            <option value="today">Today</option>
            <option value="yesterday">Yesterday</option>
            <option value="last7days">Last 7 Days</option>
            <option value="last30days">Last 30 Days</option>
            <option value="thismonth">This Month</option>
            <option value="lastmonth">Last Month</option>
            <option value="custom">Custom Range</option>
          </select>
        </div>
      </div>

      {/* Summary Stats */}
      <div className="summary-stats">
        {summaryStats.map((stat, index) => (
          <div key={index} className="summary-card">
            <p className="summary-label">{stat.label}</p>
            <h3 className="summary-value">{stat.value}</h3>
            <span className={`summary-change ${stat.trend}`}>
              {stat.trend === 'up' ? <TrendingUp size={14} /> : <TrendingDown size={14} />}
              {stat.change}
            </span>
          </div>
        ))}
      </div>

      {/* Report Content */}
      {selectedReport === 'overview' && (
        <div className="report-content">
          <div className="charts-grid">
            <div className="card">
              <h3 className="card-title">Distance & Trips (Last 7 Days)</h3>
              <ResponsiveContainer width="100%" height={300}>
                <LineChart data={distanceData}>
                  <CartesianGrid strokeDasharray="3 3" stroke="#f0f0f0" />
                  <XAxis dataKey="date" />
                  <YAxis yAxisId="left" />
                  <YAxis yAxisId="right" orientation="right" />
                  <Tooltip />
                  <Legend />
                  <Line yAxisId="left" type="monotone" dataKey="distance" stroke="#2563eb" strokeWidth={3} name="Distance (km)" />
                  <Line yAxisId="right" type="monotone" dataKey="trips" stroke="#10b981" strokeWidth={3} name="Trips" />
                </LineChart>
              </ResponsiveContainer>
            </div>

            <div className="card">
              <h3 className="card-title">Asset Utilization</h3>
              <ResponsiveContainer width="100%" height={300}>
                <PieChart>
                  <Pie
                    data={assetUtilization}
                    cx="50%"
                    cy="50%"
                    labelLine={false}
                    label={({ name, percent }) => `${name} ${(percent * 100).toFixed(0)}%`}
                    outerRadius={100}
                    fill="#8884d8"
                    dataKey="value"
                  >
                    {assetUtilization.map((entry, index) => (
                      <Cell key={`cell-${index}`} fill={entry.color} />
                    ))}
                  </Pie>
                  <Tooltip />
                </PieChart>
              </ResponsiveContainer>
            </div>
          </div>
        </div>
      )}

      {selectedReport === 'distance' && (
        <div className="report-content">
          <div className="card">
            <h3 className="card-title">Distance Analysis</h3>
            <ResponsiveContainer width="100%" height={400}>
              <BarChart data={distanceData}>
                <CartesianGrid strokeDasharray="3 3" stroke="#f0f0f0" />
                <XAxis dataKey="date" />
                <YAxis />
                <Tooltip />
                <Legend />
                <Bar dataKey="distance" fill="#2563eb" name="Distance (km)" />
                <Bar dataKey="trips" fill="#10b981" name="Number of Trips" />
              </BarChart>
            </ResponsiveContainer>
          </div>
        </div>
      )}

      {selectedReport === 'fuel' && (
        <div className="report-content">
          <div className="card">
            <h3 className="card-title">Fuel Consumption & Cost Analysis</h3>
            <ResponsiveContainer width="100%" height={400}>
              <BarChart data={fuelData}>
                <CartesianGrid strokeDasharray="3 3" stroke="#f0f0f0" />
                <XAxis dataKey="asset" />
                <YAxis yAxisId="left" />
                <YAxis yAxisId="right" orientation="right" />
                <Tooltip />
                <Legend />
                <Bar yAxisId="left" dataKey="consumption" fill="#f59e0b" name="Consumption (L)" />
                <Bar yAxisId="right" dataKey="cost" fill="#ef4444" name="Cost ($)" />
              </BarChart>
            </ResponsiveContainer>
          </div>

          <div className="card">
            <h3 className="card-title">Fuel Summary by Asset</h3>
            <div className="table-container">
              <table>
                <thead>
                  <tr>
                    <th>Asset</th>
                    <th>Consumption (L)</th>
                    <th>Cost ($)</th>
                    <th>Avg. per 100km</th>
                  </tr>
                </thead>
                <tbody>
                  {fuelData.map((item) => (
                    <tr key={item.asset}>
                      <td><strong>{item.asset}</strong></td>
                      <td>{item.consumption} L</td>
                      <td>${item.cost}</td>
                      <td>{(item.consumption / (item.consumption * 2) * 100).toFixed(1)} L</td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      )}

      {selectedReport === 'driver' && (
        <div className="report-content">
          <div className="card">
            <h3 className="card-title">Driver Performance</h3>
            <div className="table-container">
              <table>
                <thead>
                  <tr>
                    <th>Driver</th>
                    <th>Total Trips</th>
                    <th>Distance (km)</th>
                    <th>Violations</th>
                    <th>Rating</th>
                    <th>Performance</th>
                  </tr>
                </thead>
                <tbody>
                  {driverPerformance.map((driver) => (
                    <tr key={driver.driver}>
                      <td><strong>{driver.driver}</strong></td>
                      <td>{driver.trips}</td>
                      <td>{driver.distance} km</td>
                      <td>
                        <span className={`violations-badge ${driver.violations === 0 ? 'good' : driver.violations < 3 ? 'warning' : 'danger'}`}>
                          {driver.violations}
                        </span>
                      </td>
                      <td>
                        <div className="rating">
                          {'★'.repeat(Math.floor(driver.rating))}
                          {'☆'.repeat(5 - Math.floor(driver.rating))}
                          <span className="rating-value">{driver.rating}</span>
                        </div>
                      </td>
                      <td>
                        <div className="performance-bar">
                          <div
                            className="performance-fill"
                            style={{
                              width: `${(driver.rating / 5) * 100}%`,
                              background: driver.rating >= 4.5 ? '#10b981' : driver.rating >= 3.5 ? '#f59e0b' : '#ef4444'
                            }}
                          ></div>
                        </div>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export default Reports;

