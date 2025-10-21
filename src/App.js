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
import Login from './pages/Login';
import { isAuthenticated } from './services/auth';
import './App.css';

// Protected Route Component
function ProtectedRoute({ children }) {
  return isAuthenticated() ? children : <Navigate to="/login" replace />;
}

function App() {
  const [sidebarOpen, setSidebarOpen] = useState(true);
  const authenticated = isAuthenticated();

  return (
    <Router>
      <div className="app">
        <Routes>
          {/* Login Route */}
          <Route 
            path="/login" 
            element={
              authenticated ? <Navigate to="/dashboard" replace /> : <Login />
            } 
          />

          {/* Protected Routes */}
          <Route 
            path="/*" 
            element={
              <ProtectedRoute>
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
              </ProtectedRoute>
            }
          />
        </Routes>
      </div>
    </Router>
  );
}

export default App;
