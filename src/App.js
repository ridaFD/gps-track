import React, { useState } from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import Sidebar from './components/Sidebar';
import Header from './components/Header';
import Dashboard from './pages/Dashboard';
import Assets from './pages/Assets';
import Geofencing from './pages/Geofencing';
import Alerts from './pages/Alerts';
import Reports from './pages/Reports';
import Telemetry from './pages/Telemetry';
import './App.css';

function App() {
  const [sidebarOpen, setSidebarOpen] = useState(true);

  return (
    <Router>
      <div className="app">
        <Sidebar isOpen={sidebarOpen} />
        <div className={`main-content ${sidebarOpen ? 'sidebar-open' : 'sidebar-closed'}`}>
          <Header toggleSidebar={() => setSidebarOpen(!sidebarOpen)} />
          <div className="content-wrapper">
            <Routes>
              <Route path="/" element={<Navigate to="/dashboard" replace />} />
              <Route path="/dashboard" element={<Dashboard />} />
              <Route path="/assets" element={<Assets />} />
              <Route path="/geofencing" element={<Geofencing />} />
              <Route path="/alerts" element={<Alerts />} />
              <Route path="/reports" element={<Reports />} />
              <Route path="/telemetry" element={<Telemetry />} />
            </Routes>
          </div>
        </div>
      </div>
    </Router>
  );
}

export default App;
